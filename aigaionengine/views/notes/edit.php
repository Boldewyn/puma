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
$user_id = $userlogin->userId();

if (!isset($note)||($note==null)||(isset($action)&&$action=='add')) {
    $isAddForm = True;
    if (!isset($note)||($note==null)) {
        $note = new Note;
    } else {
        $pub_id = $note->pub_id;
    }
} else {
    $pub_id = $note->pub_id;
    $user_id = $note->user_id;
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
  <?php if (!$isAddForm): ?>
    <p>
      <?php _e('Access rights:')?> <span>r:
      <a href="<?php _url('/accesslevels/toggle/note/'.$note->note_id.'/read') ?>"
        class="rights_switch read_switch <?php echo $note->derived_read_access_level ?>"><?php _icon('rights_'.$note->derived_read_access_level) ?></a>
      e: <a href="<?php _url('/accesslevels/toggle/note/'.$note->note_id.'/edit') ?>"
        class="rights_switch edit_switch <?php echo $note->derived_edit_access_level ?>"><?php _icon('rights_'.$note->derived_edit_access_level) ?></a></span>
    </p>
  <?php endif ?>
  <p>
    <input type="hidden" name="action" value="<?php echo ($isAddForm? 'add' : 'edit') ?>" />
    <input type="hidden" name="pub_id" value="<?php echo $pub_id ?>" />
    <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
    <?php if (! $isAddForm): ?>
      <input type="hidden" name="note_id" value="<?php echo $note->note_id ?>" />
    <?php endif; ?>
    <input type="hidden" name="formname" value="note" />
    <input type="submit" class="standard_input" value="<?php $isAddForm? _e('Add') : _e('Change') ?>" />
    <?php _a($isAddForm? '' : 'publications/show/'.$note->pub_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
