<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php printf(__('%s Wiki'), puma())?></h2>

<p><?php printf(__('Welcome to the %s wiki.'), puma())?></p>

<?php if(isset($entries) and count($entries) > 0): ?>
  <p><?php _e('Latest edits:')?></p>

  <ul class="reallist">
    <?php foreach ($entries as $entry => $created): ?>
      <li><?php _a('wiki/'.h($entry), h($entry)) ?> (<?php echo $created[0]; if ($created[1]) { echo ', '.$created[1]; }?>)</li>
    <?php endforeach ?>
  </ul>
<?php endif ?>
