<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
views/notes/summary

Shows a summary of a note: who entered it, what is the text, and some edit buttons etc

Parameters:
    $note=>the Note object that is to be shown
    
appropriate read rights are assumed. Edit block depends on other rights.
*/
$this->load->helper('user');
//get text, replace links
$text = auto_link($note->text);

//replace bibtex cite_ids that appear in the text with a link to the publication
$link = '';
$bibtexidlinks = getBibtexIdLinks();
foreach ($note->xref_ids as $xref_id) {
    $link = $bibtexidlinks[$xref_id];
    //check whether the xref is present in the session var (should be). If not, try to correct the issue.
    if ($link == '') {
      $this->db->select('bibtex_id');
      $Q = $this->db->get_where('publication',array('pub_id'=>$xref_id));
      if ($Q->num_rows() > 0) {
        $R = $Q->row();
        if (trim($R->bibtex_id) != '') {
          $bibtexidlinks[$xref_id ] = array($R->bibtex_id, '/\b(?<!\.)('.preg_quote($R->bibtex_id, '/').')\b/');
          $link = $bibtexidlinks[$xref_id ];
        }
      }
    }

    if ($link != '') {
      $text = preg_replace(
        $link[1],
        anchor('/publications/show/'.$xref_id,$link[0]),
        $text);
    }
} ?>
<div class="readernote">
  <strong><?php _a(getUrlForUser($note->user_id), '['.getAbbrevForUser($note->user_id).']')?></strong> 
  <?php  echo $text;

  $userlogin  = getUserLogin();
  if ($userlogin->hasRights('note_edit') && 
      $this->accesslevels_lib->canEditObject($note)): ?>
    <br/>
    <?php _a('notes/delete/'.$note->note_id, '['.__('delete').']')?>
    <?php _a('notes/edit/'.$note->note_id, '['.__('edit').']')?>
    <span class="rights">[
     r: <a href="<?php _url('/accesslevels/toggle/note/'.$note->note_id.'/read') ?>"
        class="rights_switch read_switch <?php echo $note->derived_read_access_level ?>"><?php
        _icon('rights_'.$note->derived_read_access_level) ?></a>
      e: <a href="<?php _url('/accesslevels/toggle/note/'.$note->note_id.'/edit') ?>"
        class="rights_switch edit_switch <?php echo $note->derived_edit_access_level ?>"><?php
        _icon('rights_'.$note->derived_edit_access_level) ?></a>
    ]</span>
  <?php endif ?>
</div>
