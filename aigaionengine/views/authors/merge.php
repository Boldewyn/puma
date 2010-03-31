<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$authorfields = array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 'email'=>__('Email'), 'institute'=>__('Institute'));
$formAttributes = array('ID' => 'author_'.$author->author_id.'_edit');
?>
<div class='author'>
  <h2><?php _e('Merge authors') ?></h2>
  <p><?php _e('Merges the source author with the target author. The source '.
    'author will be deleted, all publications will be transferred to the target '.
    'author.') ?></p>
  <?php
    //open the edit form
    echo form_open('authors/mergecommit', $formAttributes)."\n";
    echo form_hidden('author_id',   $author->author_id)."\n";
    echo form_hidden('simauthor_id',   $simauthor->author_id)."\n";
    echo form_hidden('formname','author');
?>
  <table>
    <tr><td>
    <table class='author_edit_form' width='100%'>
        <tr>
        <td colspan=2><p class='header2'><?php echo __('Target author');?></p></td>
        <td><p class='header2'></p></td>
        <td colspan=2><p class='header2'><?php echo __('Source author');?></p></td>
        </tr>
<?php
        foreach ($authorfields as $field=>$display):
?>
        <tr>
        <td valign='top'><?php echo $display; ?>:</td>
        <td valign='top'><?php echo form_input(array('name' => $field, 'id' => $field, 'size' => '30', 'alt' => $field), $author->$field);?></td>
        <td valign='top'><button type="button" onclick="$('#<?php echo $field?>').val($('#sim<?php echo $field ?>').val());">&lt;&lt;</button></td>
        <td valign='top'><?php echo $display; ?>:</td>
        <td valign='top'><?php echo form_input(array('name' => 'sim'.$field, 'id' => 'sim'.$field, 'size' => '30', 'alt' => $field), $simauthor->$field);?></td>
        </tr>
<?php
        endforeach;
?>
    </table>
    </td></tr>
    <tr><td colspan='2'>
      <input type="submit" class="submit" value="<?php _e('Merge')?>" />
      <?php _a('authors/show/'.$author->author_id, __('Cancel'), 'class="pseudobutton"'); ?>
    </td></tr>
  </table>
</form>
</div>