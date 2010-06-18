<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Topic_model extends Model {
    
    public function __construct() {
        parent::Model();
    }
   
    /**
     * Return the Topic with the given ID
     */
    public function getByID($id, $configuration=array()) {
        $Q = $this->db->get_where('topics', array('topic_id' => $id));
        if ($Q->num_rows() > 0) {
            return $this->getFromRow($Q->row(), $configuration);
        } else {
            return null;
        }
    }

    /**
     * Returns the Topic stored in the given table row, or null if insufficient rights 
     */
    function getFromRow($R, &$configuration) {
        $topic = new Topic;
        foreach ($R as $key => $value) {
            $topic->$key = $value;
        }
        $userlogin  = getUserLogin();
        //check rights, if fail return null
        if ( !$this->accesslevels_lib->canReadObject($topic))return null;
        $topic->configuration = $configuration;
        //process configuration settings
        /*  onlyIfUserSubscribed            -- if set to True, only those topics 
                                               will be included in the tree to which the user specified by 'user' is 
                                               subscribed 
            user                            -- if set, the 'userIsSubscribed' flag will be set for all topics that this user 
                                               is subscribed to
            includeGroupSubscriptions       -- if set together with user, all topic subscriptions inherited from group
                                               memberships are taken into account as well
            flagCollapsed                   -- if set to True, the collapse status of the topic for the user specified by 
                                               'user' will be flagged by 'userIsCollapsed'
            Flags:                                   
                userIsSubscribed
                userIsCollapsed
                userIsGroupSubscribed
        */
        if (array_key_exists('user',$configuration)) {
            $userSubscribedQ = $this->db->get_where('usertopiclink', array('topic_id' => $topic->topic_id,  
                                                                          'user_id' => $configuration['user']->user_id));
            $groupIrrelevant = True;
            $groupSubscribed = False;
            if (array_key_exists('includeGroupSubscriptions',$configuration)) {
                $groupIrrelevant = False;
                if (count($configuration['user']->group_ids)>0) {
                    $groupSubscribedQ = $this->db->query('SELECT * FROM '.AIGAION_DB_PREFIX.'usertopiclink WHERE topic_id='.$this->db->escape($topic->topic_id).' AND user_id IN ('.$this->db->escape(implode(',',$configuration['user']->group_ids)).');');
                    $groupSubscribed = $groupSubscribedQ->num_rows()>0;
                } else {
                    $groupSubscribed = FALSE;
                }
                $topic->flags['userIsGroupSubscribed'] = $groupSubscribed;
            }
            if (array_key_exists('onlyIfUserSubscribed',$configuration) && $configuration['onlyIfUserSubscribed']) {
                if ($userSubscribedQ->num_rows() == 0) { //not subscribed: check group subscriptions
                    if ($groupIrrelevant || !$groupSubscribed) {
                        return null;
                    }
                }
            }
            if (($userSubscribedQ->num_rows() > 0) || $groupSubscribed) {
                $topic->flags['userIsSubscribed'] = True;
                if (array_key_exists('flagCollapsed',$configuration)) {
                    if ($userSubscribedQ->num_rows() > 0) {
                        $R = $userSubscribedQ->row();
                        $topic->flags['userIsCollapsed'] = $R->collapsed=='1';
                    } else {
                        $topic->flags['userIsCollapsed'] = True;
                    }
                }
            } else {
                $topic->flags['userIsSubscribed'] = False;
                $topic->flags['userIsCollapsed'] = True;
            }
                
        }
        /*  onlyIfPublicationSubscribed     -- if set to True, only those topics 
                                               will be included in the tree to which the publication specified by 
                                               'publicationId' is subscribed
            publicationId                   -- if set, the 'publicationIsSubscribed' will be set for all topics that this 
                                               publication is subscribed to
            Flags:                                   
                publicationIsSubscribed
                                               */
        if (array_key_exists('publicationId',$configuration)) {
            $pubSubscribedQ = $this->db->get_where('topicpublicationlink', 
                                                       array('topic_id' => $topic->topic_id,  
                                                             'pub_id'=>$configuration['publicationId']));
            $topic->flags['publicationIsSubscribed'] = False;
            if (array_key_exists('onlyIfPublicationSubscribed',$configuration)) {
                if ($pubSubscribedQ->num_rows() == 0) { //not subscribed: return null!
                    return null;
                }
                $topic->flags['isPublicationSubscriptionTree'] = True;
            }
            if ($pubSubscribedQ->num_rows() > 0) {
                $topic->flags['publicationIsSubscribed'] = True;
            } 
        }
            
        //always get parent
        $topic->parent_id = $this->_getParentId($topic->topic_id);
        return $topic;
    }

    /**
     * Construct a topic from the POST data present in the topics/edit view. 
     * Return null if the POST data was not present.
     */
    public function getFromPost() {
        $topic = new Topic;
        if ($this->input->post('formname')!='topic') {
            return null;
        }
        $topic->topic_id           = $this->input->post('topic_id');
        $topic->id                 = $topic->topic_id;
        $topic->name               = $this->input->post('name');
        $topic->description        = $this->input->post('description');
        $topic->url                = $this->input->post('url');
        $topic->parent_id          = $this->input->post('parent_id');
        $topic->user_id            = $this->input->post('user_id');
        return $topic;
    }
    
    /**
     * Return an array of Topic's retrieved from the database that are the children of the given topic. 
     */
    function getChildren($id, &$configuration) {
        $children = array();
        //get children from database; add to array
        $query = $this->db->query("SELECT ".AIGAION_DB_PREFIX."topics.* FROM ".AIGAION_DB_PREFIX."topics, ".AIGAION_DB_PREFIX."topictopiclink
                                        WHERE ".AIGAION_DB_PREFIX."topictopiclink.target_topic_id=".$this->db->escape($id)."
                                          AND ".AIGAION_DB_PREFIX."topictopiclink.source_topic_id=".AIGAION_DB_PREFIX."topics.topic_id
                                     ORDER BY name");
        foreach ($query->result() as $row) {
            $c = $this->getFromRow($row,$configuration);
            if ($c != null) {
                $children[] = $c;
            }
        }
        return $children;
    }
    
    public function count() {
        return $this->db->count_all("topics");
    }

    public function getPublicationCount($id) {
        $this->db->select('pub_id')->distinct()->where(array('topic_id'=>$id))->from("topicpublicationlink");
        return $this->db->count_all_results();
    } 

    /**
     * Return the topic_id of the parent of the given Topic through database access.
     */
    protected function _getParentId($id) {
        $query = $this->db->get_where('topictopiclink',array('source_topic_id'=>$id));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->target_topic_id;
        } else {
            return null;
        }
    }
}

//__END__
