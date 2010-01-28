<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
  //view parameter: if $sortPrefix is set, the sort options will be shown in the menu as links to $sortPrefix.'title' etc
  //view parameter: if $exportCommand is set, the export block will include an export command for the browse list
  //view parameter: if $exportName is set, this determines the text for the exportCommand menu option

  $userlogin = getUserLogin();
?>
<div id="nav">
  <ul>
    <li class="first <?php if($this->uri->segment(1) == "topics") { echo 'current'; } ?>">
      <!--h3><?php _e('Browse'); ?></h3-->
      <?php _a('topics', __('browse')); ?>
      <ul>
        <li><?php _a('topics', __('My Topics')); ?></li>
        <?php if ($userlogin->hasRights('bookmarklist')): ?>
          <li><?php _a('bookmarklist', __('My Bookmarks')); ?></li>
        <?php endif; ?>
        <li><?php _a('topics/all', __('All Topics')); ?></li>
        <li><?php _a('publications', __('All Publications')); ?></li>
        <li><?php _a('authors', __('All Authors')); ?></li>
        <li><?php _a('keywords', __('All Keywords')); ?></li>
        <li><?php _a('publications/unassigned', __('Unassigned')); ?></li>
        <li><?php _a('publications/showlist/recent', __('Recent')); ?></li>
        <li><?php _a('search', __('Search')); ?></li>
      </ul>
    </li>
    <li>
      <h3><?php _e('Export'); ?></h3>
      <ul>
        <li><?php _a('export', __('Export all publications')); ?></li>
        <?php if (isset($exportCommand)&&($exportCommand!='')): ?>
          <li><?php _a($exportCommand, $exportName); ?></li>
        <?php endif; ?>
      </ul>
    </li>

    <?php if (isset($sortPrefix)&&($sortPrefix!='')): ?>
      <li>
        <h3><?php _e('Sort by'); ?></h3>
        <ul>
          <li><?php _a($sortPrefix.'author', __('Author')); ?></li>
          <li><?php _a($sortPrefix.'title',  __('Title')); ?></li>
          <li><?php _a($sortPrefix.'type',   __('Type/Journal')); ?></li>
          <li><?php _a($sortPrefix.'year',   __('Year')); ?></li>
          <li><?php _a($sortPrefix.'recent', __('Recently added')); ?></li>
        </ul>
      </li>
    <?php endif; ?>

    <?php if ($userlogin->hasRights('publication_edit')): ?>
      <li>
        <h3><?php _e('New Data'); ?></h3>
        <ul>
          <li><?php _a('publications/add', __('New Publication')); ?></li>
          <li><?php _a('authors/add', __('New Author')); ?></li>
          <?php if ($userlogin->hasRights('topic_edit')): ?>
            <li><?php _a('topics/add', __('New Topic')); ?></li>
          <?php endif; ?>
          <li><?php _a('import', __('Import')); ?></li>
        </ul>
      </li>
    <?php endif; ?>

    <li>
      <h3><?php _e('Site'); ?></h3>
      <ul>
        <li><?php _a('help/', __('Help')); ?></li>
        <li><?php _a('help/viewhelp/about', __('About this site')); ?></li>
        <?php if ($userlogin->hasRights('database_manage')): ?>
          <li><?php _a('site/configure', __('Site Configuration')); ?></li>
          <li><?php _a('site/maintenance', __('Site Maintenance')); ?></li>
        <?php endif; ?>
        <?php if ($userlogin->hasRights('user_edit_all')): ?>
          <li><?php _a('users/manage', __('Manage All Accounts')); ?></li>
        <?php endif; ?>
      </ul>
    </li>

    <li class="last">
      <?php if ($userlogin->isAnonymous()): ?>
        <h3><?php _e('Guest User'); ?></h3>
        <ul>
          <li><?php _a('login/nds/', __('Login (NDS)')); ?></li>
          <li><?php _a('login', __('Login (Guest account)')); ?></li>
        </ul>
      <?php else: ?>
        <h3><?php printf(__('Logged In: %s'), $userlogin->loginName()); ?></h3>
        <ul>
          <?php if ($userlogin->hasRights('user_edit_self')): ?>
            <li><?php _a('users/edit/'.$userlogin->userId(), __('My Profile')); ?></li>
          <?php endif;
          if ($userlogin->hasRights('topic_subscription')): ?>
            <li><?php _a('users/topicreview/', __('Topic Subscribe')); ?></li>
          <?php endif; ?>
          <li><?php _a('login/dologout', __('Logout')); ?></li>
        </ul>
      <?php endif; ?>
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
