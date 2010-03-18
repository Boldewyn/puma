<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form method="post" action="<?php echo base_url()?>notes/delete/<?php echo $note->note_id?>/commit" class="confirmform">
  <p>
    <?php _e('Are you sure, that you want to delete the note below?') ?>
  </p>
  <p>
    <input type="submit" class="submit standard_input" value="<?php _e('Confirm') ?>" />
    <?php _a('publications/show/'.$note->pub_id, __('Cancel'), 'class="pseudobutton standard_input"') ?>
  </p>
</form>
<h3><?php _e('Note text:')?></h3>
<?php echo $note->text ?>
