<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin = getUserLogin();
?>
<h2><?php printf(__('Upload new attachment for publication &ldquo;%s&rdquo;'), h($publication->title)) ?></h2>
<?php if (getConfigurationSetting('SERVER_NOT_WRITABLE')!= 'TRUE'): ?>
  <div class="editform">
    <?php echo form_open_multipart('attachments/commit') ?>
      <h3><?php _e('Upload new attachment from this computer') ?></h3>
      <p>
        <label class="block" for="attachments_add_file_upload"><?php _e('Select a file') ?></label>
        <input type="file" class="file" name="upload" id="attachments_add_file_upload" />
      </p>
      <p>
        <label class="block" for="attachments_add_file_name"><?php _e('Set new name (blank: keep original name)') ?></label>
        <input type="text" class="text" name="name" id="attachments_add_file_name" />
      </p>
      <p>
        <label class="block" for="attachments_add_file_note"><?php _e('Note') ?></label>
        <input type="text" class="text" name="note" id="attachments_add_file_note" />
      </p>
      <p>
        <input type="hidden" name="action" value="add" />
        <input type="hidden" name="pub_id" value="<?php echo $publication->pub_id ?>" />
        <input type="hidden" name="isremote" value="" />
        <input type="hidden" name="ismain" value="" />
        <input type="hidden" name="formname" value="attachment" />
        <input type="hidden" name="user_id" value="<?php echo $userlogin->userId()?>" />
        <input type="submit" class="submit" value="<?php _e('Upload attachment')?>" />
        <?php _a('', __('Cancel'), 'class="pseudobutton"')?>
      </p>
    </form>
  </div>
<?php endif; ?>
<div class="editform">
  <?php echo form_open_multipart('attachments/commit');?>
    <h3><?php _e('Add new attachment (or web site) as a link, without uploading') ?></h3>
    <p>
      <label class="block" for="attachments_add_link_location"><?php _e('Location of file or web address') ?></label>
      <input type="text" class="text" name="location" id="attachments_add_link_location" />
    </p>
    <p>
      <label class="block" for="attachments_add_link_name"><?php _e('Set internal name (blank: keep original name)') ?></label>
      <input type="text" class="text" name="name" id="attachments_add_link_name" />
    </p>
    <p>
      <label class="block" for="attachments_add_link_note"><?php _e('Note') ?></label>
      <input type="text" class="text" name="note" id="attachments_add_link_note" />
    </p>
    <p>
      <input type="hidden" name="action" value="add" />
      <input type="hidden" name="pub_id" value="<?php echo $publication->pub_id ?>" />
      <input type="hidden" name="isremote" value="1" />
      <input type="hidden" name="ismain" value="" />
      <input type="hidden" name="formname" value="attachment" />
      <input type="hidden" name="user_id" value="<?php echo $userlogin->userId()?>" />
      <input type="submit" class="submit" value="<?php _e('Add file link')?>" />
      <?php _a('', __('Cancel'), 'class="pseudobutton"')?>
    </p>
  </form>
</div>
