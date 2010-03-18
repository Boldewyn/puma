<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>groups/delete/<?php echo $group->group_id?>/commit" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the group &ldquo;%s&rdquo;?'), h($group->name)) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('', __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
