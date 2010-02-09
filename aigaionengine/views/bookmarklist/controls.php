<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php
/**
views/bookmarklist/controls

Shows the controls for using the bookmarklist

access rights: we presume that this view is not loaded when the user doesn't have the bookmarklist rights.
Some controls may be shown only dependent on other rights, though.

*/
$this->load->helper('form');
$userlogin = getUserLogin();
?>
<h2><?php echo __('Bookmark list controls');?></h2>

<p style="float:right">
  <?php _a('bookmarklist/clear', __('Clear bookmarklist'),
          sprintf('title="%s" class="pseudobutton"', __('Clear the bookmarklist'))); ?>
  <?php if ($userlogin->hasRights('publication_edit') && $userlogin->hasRights('topic_edit')) {
      echo ' | ';
      _a('bookmarklist/maketopic', __('Make into new topic'),
          sprintf('title="%s" class="pseudobutton"', __('Make a new topic from the bookmarked publications')));
  } ?>
  <?php if ($userlogin->hasRights('publication_edit')) {
      echo ' | ';
      _a('bookmarklist/deleteall', __('Delete all'),
          sprintf('title="%s" class="pseudobutton"',
          __('Delete all publications on the bookmarklist from the database')));
  } ?>
  | <?php _a('export/bookmarklist', __('Export bookmarklist'),
              'class="pseudobutton"'); ?>
</p>

<?php     
//add to topic only if you are allowed to edit publications. Note that
//for some publicatibns in the bookmarklist the operation might still fail if the access levels are wrong.
//In that case the user will be notified after the (failed) attempts
if ($userlogin->hasRights('publication_edit')) {
    echo form_open('bookmarklist/addtotopic');
    $user = $this->user_db->getByID($userlogin->userId());
    $config = array('onlyIfUserSubscribed'=>True,
                    'includeGroupSubscriptions'=>True,
                    'user'=>$user);
    ?><p><?php
    $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => False,
                            'depth'     => -1,
                            'selected'  => -1,
                            'dropdownname' => 'topic_id',
                            'header'    => __('Add bookmarked to topic...')
                            ));
    echo form_submit(array('name'=>'addtotopic',
        'title'=>__('Add all bookmarked publications to the selected topic'),
        'class'=>'submit'),__('Add all to topic'));
    ?></p></form><?php
    
    echo form_open('bookmarklist/removefromtopic');
    $user = $this->user_db->getByID($userlogin->userId());
    $config = array('onlyIfUserSubscribed'=>True,
                    'includeGroupSubscriptions'=>True,
                    'user'=>$user);
    ?><p><?php
    $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => False,
                            'depth'     => -1,
                            'selected'  => -1,
                            'dropdownname' => 'topic_id',
                            'header'    => __('Remove bookmarked from topic...')
                            ));
    echo form_submit(array('name'=>'removefromtopic',
        'title'=>__('Remove all bookmarked publications from the selected topic'),
        'class'=>'submit'),__('Remove all from topic'));
    ?></p></form><?php
    

}
?>

<?php if ($userlogin->hasRights('publication_edit')):
    echo form_open('bookmarklist/setpubaccesslevel');?>
        <p class="xlarge_label medium_input">
            <?php
            printf('<label class="block" for="bookmarklist_controls_setpubaccesslevel">%s</label> ',
                  __('Set read access level for all bookmarked publications:'));
            echo form_dropdown('accesslevel',
                  array('public'=>__('public'),'intern'=>__('intern'),'private'=>__('private')),'intern',
                  'id="bookmarklist_controls_setpubaccesslevel"');
            echo ' ';
            echo form_submit(array('name'=>'setpubaccesslevel',
                 'title'=>__('Set the read  access levels for all publications on the bookmarklist'),
                 'class'=>'submit'),__('Set publication access level'));
            ?>
        </p>
    </form>
<?php endif;

if ($userlogin->hasRights('publication_edit')):
    echo form_open('bookmarklist/setattaccesslevel'); ?>
        <p class="xlarge_label medium_input">
            <?php
            printf('<label class="block" for="bookmarklist_controls_setattaccesslevel">%s</label>',
                   __('Set read access level for all attachments of bookmarked publications:'));
            echo form_dropdown('accesslevel',
                  array('public'=>__('public'),'intern'=>__('intern'),'private'=>__('private')),'intern',
                  'id="bookmarklist_controls_setattaccesslevel"');
            echo ' ';
            echo form_submit(array('name'=>'setattaccesslevel',
                  'title'=>__('Set the read access levels for all attachments of publications on the bookmarklist'),
                  'class'=>'submit'), __('Set attachment access level'));
            ?>
        </p>
    </form>
<?php endif;

if ($userlogin->hasRights('publication_edit')):
    echo form_open('bookmarklist/seteditpubaccesslevel'); ?>
        <p class="xlarge_label medium_input">
            <?php
            printf('<label class="block" for="bookmarklist_controls_seteditpubaccesslevel">%s</label>',
                   __('Set edit access level for all bookmarked publications:'));
            echo form_dropdown('accesslevel',
                  array('public'=>__('public'),'intern'=>__('intern'),'private'=>__('private')),'intern',
                  'id="bookmarklist_controls_seteditpubaccesslevel"');
            echo ' ';
            echo form_submit(array('name'=>'seteditpubaccesslevel',
                  'title'=>__('Set the edit  access levels for all publications on the bookmarklist'),
                  'class'=>'submit'),__('Set publication edit access level'));
            ?>
        </p>
    </form>
<?php endif;

if ($userlogin->hasRights('publication_edit')):
    echo form_open('bookmarklist/seteditattaccesslevel'); ?>
        <p class="xlarge_label medium_input">
            <?php
            printf('<label class="block" for="bookmarklist_controls_seteditattaccesslevel">%s</label>',
                   __('Set edit access level for all attachments of bookmarked publications:'));
            echo form_dropdown('accesslevel',
                  array('public'=>__('public'),'intern'=>__('intern'),'private'=>__('private')),'intern',
                  'id="bookmarklist_controls_seteditattaccesslevel"');
            echo ' ';
            echo form_submit(array('name'=>'seteditattaccesslevel',
                  'title'=>__('Set the edit access levels for all attachments of publications on the bookmarklist'),
                  'class'=>'submit'),__('Set attachment edit access level'));
            ?>
        </p>
    </form>
<?php endif; ?>
<hr/>