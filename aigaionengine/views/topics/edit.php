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

echo form_open('topics/commit', array('class'=>'extended_input'));
$isAddForm = False;
$userlogin  = getUserLogin();
$user       = $this->user_db->getByID($userlogin->userID());

if (!isset($topic)||($topic==null)||(isset($action)&&$action=='add')) {
    $isAddForm = True;
    if (!isset($action)||$action!='add')
        $topic = new Topic;
}


if ($isAddForm) : ?>
    <h2><?php _e('Add a topic') ?></h2>
<?php else: ?>
    <h2><?php printf(__('Change topic &ldquo;%s&rdquo;'), $topic->name) ?></h2>
<?php endif;
//validation feedback
echo $this->validation->error_string;
?>
    <p>
      <label class="block" for="topics_edit_name"><?php echo __('Name');?></label>
      <input type="text" class="text" name="name" id="topics_edit_name" value="<?php _h($topic->name) ?>" />
    </p>
    <p>
      <label class="block" for="topics_edit_parent_id"><?php echo __('Parent');?></label>
      <?php     
        $config = array('onlyIfUserSubscribed'=>True,
                        'includeGroupSubscriptions'=>True,
                        'user'=>$user);
        $parent_id = (isset($parent))? $parent->topic_id : $parent_id = $topic->parent_id;
        echo $this->load->view('topics/optiontree',
                       array('topics'   => $this->topic_db->getByID(1,$config),
                            'showroot'  => True,
                            'depth'     => -1,
                            'selected'  => $parent_id,
                            'id'        => 'topics_edit_parent_id',
                            ),  
                       true);
       ?>
    </p>
    <p>
      <label class="block" for='topics_edit_description'><?php echo __('Description');?></label>
      <textarea name="description" id="topics_edit_description" rows="7" cols="70"><?php _h($topic->description) ?></textarea>
    </p>
    <p>
      <label class="block" for="topics_edit_url"><?php echo __('URL');?></label>
      <input type="text" class="text" name="url" id="topics_edit_url" value="<?php _h($topic->url) ?>" />
  </p>    
  <p>
    <input type="hidden" name="formname" value="topic" />
    <?php if ($isAddForm) : ?>
        <input type="hidden" name="action" value="add" />
        <input type="hidden" name="user_id" value="<?php _h($userlogin->userId()) ?>" />
        <input type="submit" class="submit" value="<?php _e('Add') ?>" />
    <?php else : ?>
        <input type="hidden" name="action" value="edit" />
        <input type="hidden" name="topic_id" value="<?php _h($topic->topic_id) ?>"/>
        <input type="hidden" name="user_id" value="<?php _h($topic->user_id) ?>" />
        <input type="submit" class="submit" value="<?php _e('Change') ?>" />
    <?php endif; ?>
    <?php _a('/topics/single/'.$topic->topic_id, __('Cancel'), 'class="pseudobutton"') ?>
  </p>
</form>

