<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$grey='_grey';
$editR = $editE = '<em>'.__('Cannot edit access levels of this object').'</em>';
if ($this->accesslevels_lib->canEditObject($object)) {
    $grey = '';
    $options = array('public'=>__('public'),'intern'=>__('intern'));
    $userlogin = getUserLogin();
    if ($userlogin->userid() == $object->user_id) {
        $options['private'] = __('private');
    }
    $editR = form_dropdown('read', $options, $object->read_access_level, ' id="accesslevels_edit_read_'.$type.'_'.$object_id.'"');
    $editE = form_dropdown('edit', $options, $object->edit_access_level, ' id="accesslevels_edit_edit_'.$type.'_'.$object_id.'"');
} ?>
<form method="post" action="<?php _url('accesslevels/set') ?>">
  <p>
    <span class="read_level">
      <?php if ($object->derived_read_access_level!=$object->read_access_level): ?>
        <span title="<?php _e('effective access level is different')?>" style="color:red;font-weight:bold;">!</span>
      <?php endif; ?>
      r:<?php _icon('rights_'.$object->read_access_level.$grey)?>
      <?php echo $editR; ?>
    </span>
    <span class="edit_level">
      <?php if ($object->derived_edit_access_level!=$object->edit_access_level): ?>
        <span title="<?php _e('effective access level is different')?>" style="color:red;font-weight:bold;">!</span>
      <?php endif; ?>
      e:<?php _icon('rights_'.$object->edit_access_level.$grey)?>
      <?php echo $editE; ?>
    </span>
    <input type="hidden" name="type" value="<?php echo $type ?>" />
    <input type="hidden" name="id" value="<?php echo $object_id ?>" />
    <input type="submit" value="<?php _e('Change') ?>" id="accesslevels_edit_<?php echo $type.'_'.$object_id ?>" />
  </p>
  <script type="text/javascript">
    $(function () {
      $('#accesslevels_edit_<?php echo $type.'_'.$object_id ?>').hide();
      $('#accesslevels_edit_read_<?php echo $type.'_'.$object_id ?>').change(function () {
        $.post('<?php _url('accesslevels/set') ?>', {
          'type': '<?php echo $type ?>',
          'id': '<?php echo $object_id ?>',
          'read': $(this).val()
        });
      });
      $('#accesslevels_edit_edit_<?php echo $type.'_'.$object_id ?>').change(function () {
        $.post('<?php _url('accesslevels/set') ?>', {
          'type': '<?php echo $type ?>',
          'id': '<?php echo $object_id ?>',
          'edit': $(this).val()
        });
      });
    });
  </script>
</form>


