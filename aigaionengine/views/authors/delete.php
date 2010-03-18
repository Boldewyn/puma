<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>authors/delete/<?php echo $author->author_id?>/commit" class="confirmform">
  <p>
    <?php printf(__('Are you sure, that you want to delete the author &ldquo;%s&rdquo;?'), h($author->getName())) ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('authors/show/'.$author->author_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
