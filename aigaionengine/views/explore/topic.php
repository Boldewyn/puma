<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul class="topic-tree">
<?php
/*
    'topics'       => $topics, //array of topics to be shown
    'showroot'      => False,    //if False, don't show the root(s) of the passed (sub)trees
    'depth'         => -1,       //max depth for which to render the trees
    'collapseAll' =>False
    The following var is passed around a lot, and not modified along the way, so it can be loaded using
    $this->load->vars(array( 
    
    Maybe optional: pass css classnames for node, leaf, subtree, etc. Just so we can make different trees even have different styling.
    Typically loaded with $this->load->vars(
*/
    if (!isset($depth))$depth = -1;
    if (!isset($showroot))$showroot = True;
    if (!isset($collapseAll))$collapseAll = False;
    
    $todo = array();
    if (isset($topics)) {
        if (is_array($topics)) {
            $todo = $topics;
        } else {
            $todo = array($topics);
        }
    }
    $currentdepth=0;
    $first = True;
    /* This is an experiment in left traversal of the tree that does not need nested views. (loading nested views seems to be extremely inefficient) */
    while (sizeof($todo)>0){
        //get next topic to be displayed
        $next = $todo[0];
        unset($todo[0]);
        if (!is_a($next,'Topic') && ($next=='end')) {
            //if next is an end marker:
            echo '</ul></div>';
            //should we collapse?
            echo $todo[1],'</li>';
            unset($todo[1]);
            $todo = array_values($todo); //reindex
            $currentdepth--;
        } else {
            //if next is a node: 
            $children = $next->getChildren();
            if (!$first || $showroot) {
                if (sizeof($children)==0) {
                    $li_class = 'topictree-leaf';
                } else {
                    $li_class = 'topictree-node';
                }
                echo '<li class="',$li_class,'">';
                echo $this->load->view('explore/topic/leaf',
                                      array('useCollapseCallback'=>True, 'topic' => $next),
                                      True);
                if ((sizeof($children)>0) && (($depth<0) || ($currentdepth<$depth))) {
                    $currentdepth++;
                    echo '<div id="topic_children_',
                         $next->topic_id,'" class="topictree-children"><ul class="topictree-list">';
                    $collapse='';
                    if ($collapseAll||array_key_exists('flagCollapsed',$next->configuration)&&$next->flags['userIsCollapsed']) {
                        $collapse = '<script type="text/javascript">$("#control_topic_'.$next->topic_id.'").click()</script>';
                    }
                    $todo = array_merge($children,array('end',$collapse),array(),$todo); //merge and reindex
                } else {
                    $todo = array_values($todo); //reindex
                    echo '</li>';
                }
            }
            $first = False;
        }
         //reindex
    }    
    
?>
</ul>
