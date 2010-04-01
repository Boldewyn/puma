<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo site_url($url) ?>" class="confirmform">
  <p>
    <?php echo $question ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a((isset($cancel_url)? $cancel_url : ''), __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>

