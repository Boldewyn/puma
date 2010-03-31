<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin  = getUserLogin();
        
$name = $attachment->name;
$this->load->helper('utf8');
if (utf8_strlen($name)>31) {
    $name = utf8_substr(h($name), 0, 30).'&hellip;';
} else {
    $name = h($name);
} ?>
<p>
  <?php if ($attachment->isremote): ?>
    <a href="<?php echo prep_url($attachment->location)?>" rel="external"
       title="<?php printf(__('Download %s'), h($attachment->name))?>"><?php echo $name ?></a>
  <?php else:
    $extension=strtolower(substr(strrchr($attachment->location,"."),1));
    $params = array('title'=>sprintf(__('Download %s'),$attachment->name));
    if ($userlogin->getPreference('newwindowforatt')=='TRUE') {
        $params['rel'] = 'external';
    }
    _a('attachments/single/'.$attachment->att_id, 
       $name.' '.icon('attachment_'.$extension, 'attachment'), $params);
  endif;
    
  if ($userlogin->hasRights('attachment_edit') && 
      $this->accesslevels_lib->canEditObject($attachment)): ?>
    <br/>
    <?php _a('attachments/delete/'.$attachment->att_id, '['.__('delete').']', array('title'=>sprintf(__('Delete %s'), h($attachment->name)))) ?>
    <?php _a('attachments/edit/'.$attachment->att_id, '['.__('edit').']', array('title'=>sprintf(__('Edit information for %s'), h($attachment->name)))) ?>
    <?php if ($attachment->ismain): ?>
        <?php _a('attachments/unsetmain/'.$attachment->att_id, '['.__('unset main').']', array('title'=>__('Unset as main attachment'))) ?>
    <?php else: ?>
        <?php _a('attachments/setmain/'.$attachment->att_id, '['.__('set main').']', array('title'=>__('Set as main attachment'))) ?>
    <?php endif; ?>
    
    <?php _e('Attachment access rights:')?> <span class="rights">[
      r: <a href="<?php echo site_url('/accesslevels/toggle/attachment/'.$attachment->att_id.'/read') ?>"
        class="rights_switch read_switch <?php echo $attachment->derived_read_access_level ?>"><?php _icon('rights_'.$attachment->derived_read_access_level) ?></a>
      e: <a href="<?php echo site_url('/accesslevels/toggle/attachment/'.$attachment->att_id.'/edit') ?>"
        class="rights_switch edit_switch <?php echo $attachment->derived_edit_access_level ?>"><?php _icon('rights_'.$attachment->derived_edit_access_level) ?></a>
      ]</span>
  <?php endif; ?>
</p>
<?php if ($attachment->note!=''): ?>
  <p>(<?php _h($attachment->note)?>)</p>
<?php endif; ?>
