<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin  = getUserLogin();
        
$name = $attachment->name;
$this->load->helper('utf8');
if (utf8_strlen($name)>31) {
    $name = utf8_substr($name,0,30)."...";
}
if ($attachment->isremote): ?>
    <a href="<?php echo prep_url($attachment->location)?>" rel="external"
       title="<?php printf(__('Download %s'), h($attachment->name))?>"><?php _h($name) ?></a>
<?php else:
    $extension=strtolower(substr(strrchr($attachment->location,"."),1));
    $params = array('title'=>sprintf(__('Download %s'),$attachment->name));
    if ($userlogin->getPreference('newwindowforatt')=='TRUE') {
        $params['rel'] = 'external';
    }
    _a('attachments/single/'.$attachment->att_id, 
       _h($name).' '.icon('attachment_'.$extension, '', '', 'attachment'), $params);
endif;
    
if ($userlogin->hasRights('attachment_edit') && 
    $this->accesslevels_lib->canEditObject($attachment)) {
    echo "&nbsp;&nbsp;".anchor('attachments/delete/'.$attachment->att_id,"[".__('delete')."]",array('title'=>sprintf(__('Delete %s'), $attachment->name)));
    echo "&nbsp;".anchor('attachments/edit/'.$attachment->att_id,"[".__('edit')."]",array('title'=>sprintf(__('Edit information for %s'),$attachment->name)));
    if ($attachment->ismain) {
        echo "&nbsp;".anchor('attachments/unsetmain/'.$attachment->att_id,"[".__('unset main')."]",array('title'=>__('Unset as main attachment')));
    } else {
        echo "&nbsp;".anchor('attachments/setmain/'.$attachment->att_id,"[".__('set main')."]",array('title'=>__('Set as main attachment')));
    }
    
    $read_icon = $this->accesslevels_lib->getReadAccessLevelIcon($attachment);
    $edit_icon = $this->accesslevels_lib->getEditAccessLevelIcon($attachment);
    
    $readrights = $this->ajax->link_to_remote($read_icon,
                  array('url'     => site_url('/accesslevels/toggle/attachment/'.$attachment->att_id.'/read'),
                        'update'  => 'attachment_rights_'.$attachment->att_id
                       )
                  );
    $editrights = $this->ajax->link_to_remote($edit_icon,
                  array('url'     => site_url('/accesslevels/toggle/attachment/'.$attachment->att_id.'/edit'),
                        'update'  => 'attachment_rights_'.$attachment->att_id
                       )
                  );
    
    echo "[<span title='".__('attachment read / edit rights')."'><span id='attachment_rights_".$attachment->att_id."'>r:".$readrights."e:".$editrights."</span></span>]";
    _e('Attachment access rights:')?> <span>r:
      <a href="<?php echo site_url('/accesslevels/toggle/attachment/'.$attachment->attachment_id.'/read') ?>"
        class="rights_switch read_switch <?php echo $attachment->derived_read_access_level ?>"><?php _icon('rights_'.$attachment->derived_read_access_level) ?></a>
      e: <a href="<?php echo site_url('/accesslevels/toggle/attachment/'.$attachment->attachment_id.'/edit') ?>"
        class="rights_switch edit_switch <?php echo $attachment->derived_edit_access_level ?>"><?php _icon('rights_'.$attachment->derived_edit_access_level) ?></a></span>
<?php }

//always show note
if ($attachment->note!='') {
    echo "<br/>&nbsp;&nbsp;&nbsp;(".$attachment->note.")";
}
?>
