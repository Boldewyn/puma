<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>rightsprofiles/delete/<?php echo $rightsprofile->rightsprofile_id?>/commit" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the rights profile &ldquo;%s&rdquo;?'), h($rightsprofile->name)) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('users/manage/', __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
