<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>publications/delete/<?php echo $publication->pub_id?>/commit" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the publication &ldquo;%s&rdquo;?'), h($publication->title)) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('publications/show/'.$publication->pub_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
