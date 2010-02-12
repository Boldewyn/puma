<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php printf(__('Edit %s'), h($item))?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('wiki/Edit:'.h($item), array('class'=>'extralarge_input wiki_edit')); ?>
  <p>
    <textarea name="content" class="richtext" rows="10" cols="30"><?php echo set_value('content', $original_content); ?></textarea>
  </p>
  <p>
    <label for="wiki_edit_description"><?php _e('A short description of this edit:')?></label>
    <input type="text" class="text" name="description" id="wiki_edit_description" value="<?php echo set_value('description') ?>" />
  </p>
  <p>
    <input type="submit" class="submit wide_button" value="<?php _e('Submit') ?>" />
  </p>
</form>
