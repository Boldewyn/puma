<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
  $authorfields = array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 'email'=>__('Email'), 'institute'=>__('Institute'));
  $formAttributes = array('ID' => 'author_'.$author->author_id.'_edit');
?>
<div class='author'>
  <div class='header'><?php 
    switch ($edit_type) {
      case 'new':
        echo __('New Author');
        break;
      case 'edit':
      default:
        echo __('Edit Author');
        break;
    }
    ?>
    </div>
<?php
  //open the edit form
  echo form_open('authors/commit', $formAttributes)."\n";
  echo form_hidden('edit_type',   $edit_type)."\n";
  echo form_hidden('author_id',   $author->author_id)."\n";
  echo form_hidden('formname','author');
  if (isset($review))
    echo form_hidden('submit_type', 'review');
  else
    echo form_hidden('submit_type', 'submit')."\n";
?>
  <table class='author_edit_form' width='100%'>
<?php
    if (isset($review)):
?>    
    <tr>
      <td colspan = 2>
        <div class='errormessage'><?php echo $review['author']; ?></div>
      </td>
    </tr>
<?php
    endif;
    foreach ($authorfields as $field=>$display):
?>
    <tr>
      <td valign='top'><?php echo $display; ?>:</td>
      <td valign='top'><?php echo form_input(array('name' => $field, 'id' => $field, 'size' => '45', 'alt' => $field), $author->$field);?></td>
    </tr>
<?php
    endforeach;
?>
  </table>
<?php
if ($edit_type=='edit') {
  echo form_submit('publication_submit', __('Change'))."\n";
} else {
  echo form_submit('publication_submit', __('Add'))."\n";
}
  echo form_close()."\n";
if ($edit_type=='edit') {
  echo form_open('authors/show/'.$author->author_id);
} else {
  echo form_open('');
}
  echo form_submit('Cancel', __('Cancel'));
  echo form_close()."\n";
?>
</div>