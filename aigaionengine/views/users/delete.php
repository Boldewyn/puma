<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>users/delete/<?php echo $user->user_id?>/commit" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the user &ldquo;%s&rdquo;?'), h($user->login)) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('', __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
