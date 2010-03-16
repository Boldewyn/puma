<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php
/**
views/topics/edit

Shows a form for editing topics.

Parameters:
    $topic=>the Topic object to be edited
    
If $topic is null, the edit for will be restyled as an 'add new topic' form
if $topic is not null, but $action == 'add', the edit form will be restyled as a
pre filled 'add new topic' form
*/

$isAddForm = False;
$userlogin  = getUserLogin();
$user       = $this->user_db->getByID($userlogin->userID());

if (!isset($topic)||($topic==null)||(isset($action)&&$action=='add')) {
    $isAddForm = True;
    if (!isset($action)||$action!='add')
        $topic = new Topic;
}

?>
<form method="post" action="<?php echo base_url()?>topics/commit">
  <?php if ($isAddForm) : ?>
    <h2><?php _e('Add a topic') ?></h2>
  <?php else: ?>
    <h2><?php printf(__('Change topic &ldquo;%s&rdquo;'), $topic->name) ?></h2>
  <?php endif;
  echo $this->validation->error_string; ?>
  <fieldset class="disguised">
    <p>
      <label class="block" for="topics_edit_name"><?php echo __('Name');?></label>
      <input type="text" class="extended_input text" name="name" id="topics_edit_name" value="<?php _h($topic->name) ?>" />
    </p>
    <p>
      <label class="block" for="topics_edit_parent_id"><?php echo __('Parent');?></label>
      <?php     
        $config = array('onlyIfUserSubscribed'=>True,
                        'includeGroupSubscriptions'=>True,
                        'user'=>$user);
        $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => True,
                            'depth'     => -1,
                            'selected'  => (isset($parent))? $parent->topic_id : $topic->parent_id,
                            'id'        => 'topics_edit_parent_id',
                            'add'       => 'class="extended_input"',
                            ));
       ?>
    </p>
    <p>
      <label class="block" for='topics_edit_description'><?php echo __('Description');?></label>
      <textarea name="description" id="topics_edit_description" rows="7" cols="70" class="extended_input"><?php _h($topic->description) ?></textarea>
    </p>
    <p>
      <label class="block" for="topics_edit_url"><?php echo __('URL');?></label>
      <input type="text" class="text extended_input" name="url" id="topics_edit_url" value="<?php _h($topic->url) ?>" />
    </p>
  </fieldset>
  <p>
    <input type="hidden" name="formname" value="topic" />
    <?php if ($isAddForm) : ?>
        <input type="hidden" name="action" value="add" />
        <input type="hidden" name="user_id" value="<?php _h($userlogin->userId()) ?>" />
        <input type="submit" class="submit standard_input" value="<?php _e('Add') ?>" />
    <?php else : ?>
        <input type="hidden" name="action" value="edit" />
        <input type="hidden" name="topic_id" value="<?php _h($topic->topic_id) ?>"/>
        <input type="hidden" name="user_id" value="<?php _h($topic->user_id) ?>" />
        <input type="submit" class="submit standard_input" value="<?php _e('Change') ?>" />
    <?php endif; ?>
    <?php _a('/topics/single/'.$topic->topic_id, __('Cancel'), 'class="standard_input pseudobutton"') ?>
  </p>
</form>

