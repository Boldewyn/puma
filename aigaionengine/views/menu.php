<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (! isset($nav_current)) { $nav_current = $this->uri->segment(1); }
?>
<div id="nav">
  <ul>
    <li class="<?php if($nav_current == 'explore') { echo 'current'; } ?> first">
      <?php _a('explore/', icon('edit-find').' '.__('Explore')) ?>
    </li>
    <li class="<?php if($nav_current == 'create') { echo 'current'; } ?>">
      <?php _a('import/', icon('bookmark-new').' '.__('Create')) ?>
    </li>
    <li class="<?php if($nav_current == 'search') { echo 'current'; } ?>">
      <?php _a('search/', icon('system-search').' '.__('Search')) ?>
    </li>
    <li class="<?php if($nav_current == 'user') { echo 'current'; } ?>">
      <?php _a('user/', icon('system-users').' '.__('Users')) ?>
    </li>
    <li class="<?php if($nav_current == 'wiki') { echo 'current'; } ?>">
      <?php _a('wiki/', icon('accessories-text-editor').' '.__('Wiki')) ?>
    </li>
  </ul>
</div>

<?php
    $err = getErrorMessage();
    if ($err != '') {
        echo '<p class="error global-error">',$err,'</p>';
        clearErrorMessage();
    }
    $msg = getMessage();
    if ($msg != '') {
        echo '<p class="info global-info">',$msg,'</p>';
        clearMessage();
    }
?>

<div id="subnav" class="<?php if(! isset($subnav) || count($subnav) == 0): echo 'min">'; else: ?>">
  <ul>
    <?php
      if (! isset($subnav_current)) { $subnav_current = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2); }
      $i = 0;
      foreach($subnav as $url => $title): ?>
        <li class="<?php
          if($i == 0){
            echo ' first';
          }
          if ($i == count($subnav) - 1) {
            echo ' last';
          }
          if ($subnav_current == $url) {
            echo ' current';
          }
          ?>"><a href="<?php _h($url)?>"><?php _h($title)?></a></li>
      <?php $i++;
      endforeach; ?>
  </ul>
  <?php endif ?>
</div>

<!-- End of menu -->
