<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="nav">
  <ul>
    <li class="<?php if($this->uri->segment(1) == "explore") { echo 'current'; } ?> first">
      <?php _a('explore', __('Explore')) ?>
    </li>
    <li class="<?php if($this->uri->segment(1) == "create") { echo 'current'; } ?>">
      <?php _a('create', __('Create')) ?>
    </li>
    <li class="<?php if($this->uri->segment(1) == "search") { echo 'current'; } ?>">
      <?php _a('search', __('Search')) ?>
    </li>
    <li class="<?php if($this->uri->segment(1) == "user") { echo 'current'; } ?>">
      <?php _a('user', __('Users')) ?>
    </li>
    <li class="<?php if($this->uri->segment(1) == "wiki") { echo 'current'; } ?>">
      <?php _a('wiki', __('Wiki')) ?>
    </li>
  </ul>
</div>

<div id="subnav" class="<?php if(! isset($subnav) || count($subnav) == 0): echo 'min">'; else: ?>">
  <ul>
    <?php switch($this->uri->segment(1)):
      case "topics": ?>
        <li class="first <?php if($this->uri->segment(2) == "all") { echo 'current'; } ?>"><?php _a('topics/all', __('Topics')); ?></li>
        <li class=" <?php if($this->uri->segment(2) == "authors") { echo 'current'; } ?>"><?php _a('authors', __('Authors')); ?></li>
        <li class=" <?php if($this->uri->segment(2) == "keywords") { echo 'current'; } ?>"><?php _a('keywords', __('Keywords')); ?></li>
        <li class="last <?php if($this->uri->segment(2) == "recent") { echo 'current'; } ?>"><?php _a('publications/showlist/recent', __('Recent')); ?></li>
      <?php break;
      default: ?>
        <li class="current last"><a href="#"><?php _e('Standard view'); ?></a></li>
    <?php endswitch; ?>
  </ul>
  <?php endif; ?>
</div>

<!-- End of menu -->
