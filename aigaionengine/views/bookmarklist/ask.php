<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ask a confirming question
 */
$this->load->helper('form');
if (! isset($accesslevel)) { $accesslevel = Null; } ?>
<h2><?php _e('Confirmation needed')?></h2>
<?php echo form_open($target); ?>
  <p class="confirmform">
      <?php if ($accesslevel) {
          printf($question, $accesslevel);
      } else {
          echo $question;
      }?>
  </p>
  <p>
    <?php if ($accesslevel) { echo form_hidden('accesslevel', $accesslevel); } ?>
    <?php echo form_submit(array('name'=>'confirm', 'class'=>'submit'),__('Confirm')); ?>
    <?php _a('bookmarklist', __('Cancel'), 'class="pseudobutton"')?>
  </p>
</form>
