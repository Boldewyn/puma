<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$isAddForm = False;
if (!isset($group)||($group==null)||(isset($action)&&$action=='add')) {
    $isAddForm = True;
    if (!isset($action)||$action!='add')
        $group = new Group;
}?>
<form method="post" action="<?php _url('groups/commit') ?>">
  <h2><?php $isAddForm? _e('Create a new group') : _e('Edit group settings') ?></h2>
  <?php echo $this->validation->error_string; ?>
  <h3><?php _e('Group details:') ?></h3>
  <p>
    <label class="block" for="groups_edit_name"><?php _e('Name') ?></label>
    <input type="text" class="text" name="name" id="groups_edit_name" 
      value="<?php _h($group->name) ?>" />
  </p>
  <p>
    <label class="block" for="groups_edit_abbreviation"><?php _e('Abbreviation') ?></label>
    <input type="text" class="text" name="abbreviation" id="groups_edit_abbreviation"
      value="<?php _h($group->abbreviation) ?>" />
  </p>
  <?php
  $userlogin = getUserLogin(); 
  if ($userlogin->hasRights('user_assign_rights')): ?>
    <h3><?php _e('Rights profiles:') ?></h3>
    <p><?php _e('The following rights profiles will by default be assigned to '.
      'a user when it is added to this group.') ?></p>
    <?php foreach ($this->rightsprofile_db->getAllRightsprofiles() as $rightsprofile):
      $checked = FALSE;
      if (in_array($rightsprofile->rightsprofile_id,$group->rightsprofile_ids)) $checked=TRUE; ?>
      <p>
        <label class="block" for="rightsprofile_<?php
          echo $rightsprofile->rightsprofile_id ?>"><?php _h($rightsprofile->name) ?></label>
        <input type="checkbox" name="rightsprofile_<?php echo $rightsprofile->rightsprofile_id ?>"
          id="rightsprofile_<?php echo $rightsprofile->rightsprofile_id ?>"
          <?php echo $checked? 'checked="checked"' : '' ?> />
    <?php endforeach;
  endif; ?>
  <p>
    <input type="hidden" name="action" value="<?php echo $isAddForm? 'add' : 'edit' ?>" />
    <input type="hidden" name="group_id" value="<?php echo $isAddForm? '' : $group->group_id ?>" />
    <input type="hidden" name="formname" value="group" />
    <input type="submit" value="<?php $isAddForm? _e('Add') : _e('Change') ?>" />
    <a href="<?php _url() ?>" class="pseudobutton"><?php _e('Cancel') ?></a>
  </p>
</form>
