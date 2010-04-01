<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo site_url('topics/delete/'.$topic->topic_id.'/commit') ?>" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the topic &ldquo;%s&rdquo;?'), h($topic->name)) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('topics/single/'.$topic->topic_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
