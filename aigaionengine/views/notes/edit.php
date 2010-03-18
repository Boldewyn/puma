<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
views/notes/edit

Shows a form for editing notes.

Parameters:
    $note=>the Note object to be edited
    
If $note is null, the edit for will be restyled as an 'add new note' form
if $note is not null, but $action == 'add', the edit form will be restyled as a
pre filled 'add new note' form
*/

$this->load->helper('form');
echo form_open('notes/commit');
$isAddForm = False;
$userlogin  = getUserLogin();

if (!isset($note)||($note==null)||(isset($action)&&$action=='add')) {
    $isAddForm = True;
    echo form_hidden('action','add');
    if (!isset($note)||($note==null)) {
        $note = new Note;
        echo form_hidden('pub_id',$pub_id);
    } else {
        echo form_hidden('pub_id',$note->pub_id);
    }
    echo form_hidden('user_id',$userlogin->userId());
} else {
    echo form_hidden('action','edit');
    echo form_hidden('note_id',$note->note_id);
    echo form_hidden('user_id',$note->user_id);
    echo form_hidden('pub_id',$note->pub_id);
}

if ($isAddForm): ?>
  <h2><?php _e('Add a note') ?></h2>
<?php else: ?>
  <h2><?php _e('Change note') ?></h2>
<?php endif;
echo $this->validation->error_string;
?>
  <p>
    <label for="text"><?php _e('Text:') ?></label>
    <textarea name="text" id="text" class="extralarge_input richtext" rows="10" cols="30"><?php _h($note->text); ?></textarea>
    <script type="text/javascript" src="<?php echo base_url()?>static/js/tiny_mce/tiny_mce.js"></script>
  </p>
  <p style="text-align:right;">
    <button type="button" onclick="Puma.toggleEditor('text')"><?php _e('Show/hide rich text editor')?></button>
  </p>
  <?php if (!$isAddForm):
    $read_icon = $this->accesslevels_lib->getReadAccessLevelIcon($note);
    $edit_icon = $this->accesslevels_lib->getEditAccessLevelIcon($note);
    
    $readrights = $this->ajax->link_to_remote($read_icon,
                  array('url'     => site_url('/accesslevels/toggle/note/'.$note->note_id.'/read'),
                        'update'  => 'note_rights_'.$note->note_id
                       )
                  );
    $editrights = $this->ajax->link_to_remote($edit_icon,
                  array('url'     => site_url('/accesslevels/toggle/note/'.$note->note_id.'/edit'),
                        'update'  => 'note_rights_'.$note->note_id
                       )
                  );
      ?>
      <p>
        <?php echo __('Access rights').": <span id='note_rights_".$note->note_id."' title='".sprintf(__('%s read / edit rights'), __('note'))."'>r:".$readrights."e:".$editrights."</span>";?>
      </p>
    <?php endif ?>
  <p>
    <input type="hidden" name="formname" value="note" />
    <input type="submit" class="submit standard_input" value="<?php $isAddForm? _e('Add') : _e('Change') ?>" />
    <?php _a($isAddForm? '' : 'publications/show/'.$note->pub_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
