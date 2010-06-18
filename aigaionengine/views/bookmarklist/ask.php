<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (! isset($accesslevel)) { $accesslevel = ''; } ?>
<form method="post" action="<?php _url($target) ?>">
  <h2><?php _e('Confirmation needed')?></h2>
  <p class="confirmform">
    <?php printf($question, $accesslevel); ?>
  </p>
  <p>
    <?php if ($accesslevel) { echo '<input type="hidden" name="accesslevel" value="'.$accesslevel.'" />'); } ?>
    <input type="submit" name="confirm" value="<?php _e('Confirm') ?>" />
    <?php _a('bookmarklist', __('Cancel'), 'class="pseudobutton"')?>
  </p>
</form>
