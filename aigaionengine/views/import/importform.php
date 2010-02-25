<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$importTypes = $this->import_lib->getAvailableImportTypes();
$importTypes['auto'] = 'auto';
if (!isset($content)||($content==null)) {
   $content = '';
}
?>
<div class="publication">
  <h2><?php echo __('Import publications'); ?></h2>
  <p>
    <?php printf(__('Paste the entries (%s) to import in the text area below and then press &ldquo;%s&rdquo;.'), implode(', ',$importTypes), __('Import'));?>
  </p>
  <?php echo form_open('import/submit', array('id' => 'import_form')); ?>
    <p>
      <input type="hidden" name="submit_type" value="submit" />
      <input type="hidden" name="formname" value="import" />
      <textarea name="import_data" "id"="import_data" rows="20" cols="60"><?php _h($content) ?></textarea>
    </p>
    <p>
      <input type="submit" class="submit" value="<?php _e('Import')?>" /> &nbsp;
      <label for="import_importform_format"
         title="<?php printf(__('Select the format of the data entered in the form above, '.
               'or &ldquo;auto&rdquo; to let %s automatically detect the format.'),
               site_title()) ?>"><?php _e('Format:') ?></label>
      <?php echo form_dropdown('format',$importTypes,'auto') ?> &nbsp;
      <?php echo form_checkbox('markasread','markasread',False); ?>
      <label for="import_importform_markasread"><?php _e('Mark imported entries as read.')?></label>
    </p>
  </form>
</div>
