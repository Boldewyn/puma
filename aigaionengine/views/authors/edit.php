<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$authorfields = array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 'email'=>__('Email'), 'institute'=>__('Institute'));
?>
<div class='author'>
  <h2><?php if ($edit_type == 'new') {
    _e('New Author');
  } else {
    _e('Edit Author');
  } ?></h2>
  <?php echo form_open('authors/commit') ?>
    <fieldset class="disguised">
    <?php if (isset($review)): ?>
      <p class="error">
        <?php echo $review['author']; ?>
      </p>
    <?php endif;
    foreach ($authorfields as $field=>$display): ?>
      <p>
        <label class="block" for="authors_edit_<?php echo $field ?>"><?php echo $display; ?>:</label>
        <input type="text" class="extended_input text" name="<?php echo $field ?>" id="authors_edit_<?php echo $field ?>" value="<?php _h($author->$field)?>" />
      </p>
    <?php endforeach; ?>
    </fieldset>
    <p>
      <input type="hidden" name="edit_type" value="<?php _h($edit_type)?>" />
      <input type="hidden" name="author_id" value="<?php _h($author->author_id)?>" />
      <input type="hidden" name="submit_type" value="<?php echo isset($review)? 'review':'submit'?>" />
      <input type="hidden" name="formname" value="author" />
      <input type="submit" class="submit standard_input" value="<?php $edit_type=='edit'? _e('Change') : _e('Add') ?>" />
      <?php _a($edit_type=='edit'? 'authors/show/'.$author->author_id : '', __('Cancel'), 'class="pseudobutton standard_input"') ?>
    </p>
  </form>
</div>