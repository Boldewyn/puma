<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo site_url('attachments/commit') ?>">
  <h2><?php printf(__('Edit attachment info for &ldquo;%s&rdquo;'), h($attachment->name)) ?></h2>
  <p>
    <?php echo $attachment->isremote? __('Link to remote attachment.') : __('Attachment stored on server.') ?>
  </p>
  <p>
    <label class="block" for="attachments_edit_name"><?php _e('Set internal name') ?></label>
    <input type="text" class="text" name="name" id="attachments_edit_name" value="<?php _h($attachment->name)?>" />
  </p>
  <?php if (!$attachment->isremote): ?>
    <div><input type="hidden" name="location" value="<?php _h($attachment->location) ?>" /></div>
  <?php else: ?>
    <p>
      <label class="block" for="attachments_edit_location"><?php _e('Set URL') ?></label>
      <input type="text" class="text" name="location" id="attachments_edit_location" value="<?php _h($attachment->location) ?>" />
    </p>
  <?php endif; ?>
  <p>
    <label class="block" for="attachments_edit_note"><?php _e('Note') ?></label>
    <input type="text" class="text" name="note" id="attachments_edit_note" value="<?php _h($attachment->note) ?>" />
  </p>
  <p>
    <input type="hidden" name="action" value="edit" />
    <input type="hidden" name="att_id" value="<?php echo $attachment->att_id ?>" />
    <input type="hidden" name="isremote" value="<?php echo $attachment->isremote? '1' : '' ?>" />
    <input type="hidden" name="ismain" value="<?php echo $attachment->ismain? '1' : '' ?>" />
    <input type="hidden" name="pub_id" value="<?php echo $attachment->pub_id ?>" />
    <input type="hidden" name="user_id" value="<?php echo $attachment->user_id ?>" />
    <input type="hidden" name="mime" value="<?php echo $attachment->mime ?>" />
    <input type="hidden" name="formname" value="attachment" />
    <input type="submit" class="submit" value="<?php _e('Change') ?> "/>
    <?php _a('publications/show/'.$attachment->pub_id, __('Cancel'), 'class="pseudobutton"') ?>
  </p>
</form>
