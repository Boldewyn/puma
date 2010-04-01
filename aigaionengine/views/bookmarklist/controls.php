<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin = getUserLogin();
$user = $this->user_db->getByID($userlogin->userId());
?>

<div id="bookmarklist_controls">

<h2><?php echo __('Bookmark list controls');?></h2>

<p class="optionbox">
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

<?php if ($userlogin->hasRights('publication_edit')): ?>
  <form method="post" action="<?php _url('bookmarklist/addtotopic') ?>">
    <p>
      <?php $config = array('onlyIfUserSubscribed'=>True, 'user' => $user,
                            'includeGroupSubscriptions'=>True);
      $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => False,
                            'depth'     => -1,
                            'selected'  => -1,
                            'dropdownname' => 'topic_id',
                            'header'    => __('Add bookmarked to topic...')
                            )); ?>
      <input type="submit" class="submit" name="addtotopic" title="<?php 
        _e('Add all bookmarked publications to the selected topic') ?>"
        value="<?php _e('Add all to topic') ?>" />
    </p>
  </form>
  <form method="post" action="<?php _url('bookmarklist/removefromtopic') ?>">
    <p>
      <?php $config = array('onlyIfUserSubscribed'=>True, 'user'=>$user,
                    'includeGroupSubscriptions'=>True);
      $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => False,
                            'depth'     => -1,
                            'selected'  => -1,
                            'dropdownname' => 'topic_id',
                            'header'    => __('Remove bookmarked from topic...')
                            ));?>
      <input type="submit" class="submit" name="removefromtopic" title="<?php 
        _e('Remove all bookmarked publications from the selected topic') ?>"
        value="<?php _e('Remove all from topic') ?>" />
    </p>
  </form>
<?php endif; ?>

<?php if ($userlogin->hasRights('publication_edit')): ?>
  <form method="post" action="<?php _url('bookmarklist/setpubaccesslevel') ?>">
        <p class="xlarge_label medium_input">
            <?php
            printf('<label class="" for="bookmarklist_controls_setpubaccesslevel">%s</label><br/> ',
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
            printf('<label class="" for="bookmarklist_controls_setattaccesslevel">%s</label><br/>',
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
            printf('<label class="" for="bookmarklist_controls_seteditpubaccesslevel">%s</label><br/>',
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
            printf('<label class="" for="bookmarklist_controls_seteditattaccesslevel">%s</label><br/>',
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

</div>
<hr/>
