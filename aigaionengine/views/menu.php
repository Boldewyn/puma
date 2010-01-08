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
      <?php echo anchor('topics', __('browse')); ?>
      <ul>
        <li><?php echo anchor('topics', __('My Topics')); ?></li>
        <?php if ($userlogin->hasRights('bookmarklist')): ?>
          <li><?php echo anchor('bookmarklist', __('My Bookmarks')); ?></li>
        <?php endif; ?>
        <li><?php echo anchor('topics/all', __('All Topics')); ?></li>
        <li><?php echo anchor('publications', __('All Publications')); ?></li>
        <li><?php echo anchor('authors', __('All Authors')); ?></li>
        <li><?php echo anchor('keywords', __('All Keywords')); ?></li>
        <li><?php echo anchor('publications/unassigned', __('Unassigned')); ?></li>
        <li><?php echo anchor('publications/showlist/recent', __('Recent')); ?></li>
        <li><?php echo anchor('search', __('Search')); ?></li>
      </ul>
    </li>
    <li>
      <h3><?php _e('Export'); ?></h3>
      <ul>
        <li><?php echo anchor('export', __('Export all publications')); ?></li>
        <?php if (isset($exportCommand)&&($exportCommand!='')): ?>
          <li><?php echo anchor($exportCommand, $exportName); ?></li>
        <?php endif; ?>
      </ul>
    </li>

    <?php if (isset($sortPrefix)&&($sortPrefix!='')): ?>
      <li>
        <h3><?php _e('Sort by'); ?></h3>
        <ul>
          <li><?php echo anchor($sortPrefix.'author', __('Author')); ?></li>
          <li><?php echo anchor($sortPrefix.'title',  __('Title')); ?></li>
          <li><?php echo anchor($sortPrefix.'type',   __('Type/Journal')); ?></li>
          <li><?php echo anchor($sortPrefix.'year',   __('Year')); ?></li>
          <li><?php echo anchor($sortPrefix.'recent', __('Recently added')); ?></li>
        </ul>
      </li>
    <?php endif; ?>

    <?php if ($userlogin->hasRights('publication_edit')): ?>
      <li>
        <h3><?php _e('New Data'); ?></h3>
        <ul>
          <li><?php echo anchor('publications/add', __('New Publication')); ?></li>
          <li><?php echo anchor('authors/add', __('New Author')); ?></li>
          <?php if ($userlogin->hasRights('topic_edit')): ?>
            <li><?php echo anchor('topics/add', __('New Topic')); ?></li>
          <?php endif; ?>
          <li><?php echo anchor('import', __('Import')); ?></li>
        </ul>
      </li>
    <?php endif; ?>

    <li>
      <h3><?php _e('Site'); ?></h3>
      <ul>
        <li><?php echo anchor('help/', __('Help')); ?></li>
        <li><?php echo anchor('help/viewhelp/about', __('About this site')); ?></li>
        <?php if ($userlogin->hasRights('database_manage')): ?>
          <li><?php echo anchor('site/configure', __('Site Configuration')); ?></li>
          <li><?php echo anchor('site/maintenance', __('Site Maintenance')); ?></li>
        <?php endif; ?>
        <?php if ($userlogin->hasRights('user_edit_all')): ?>
          <li><?php echo anchor('users/manage', __('Manage All Accounts')); ?></li>
        <?php endif; ?>
      </ul>
    </li>

    <li class="last">
      <?php if ($userlogin->isAnonymous()): ?>
        <h3><?php _e('Guest User'); ?></h3>
        <ul>
          <li><?php echo anchor('login/nds/', __('Login (NDS)')); ?></li>
          <li><?php echo anchor('login', __('Login (Guest account)')); ?></li>
        </ul>
      <?php else: ?>
        <h3><?php printf(__('Logged In: %s'), $userlogin->loginName()); ?></h3>
        <ul>
          <?php if ($userlogin->hasRights('user_edit_self')): ?>
            <li><?php echo anchor('users/edit/'.$userlogin->userId(), __('My Profile')); ?></li>
          <?php endif;
          if ($userlogin->hasRights('topic_subscription')): ?>
            <li><?php echo anchor('users/topicreview/', __('Topic Subscribe')); ?></li>
          <?php endif; ?>
          <li><?php echo anchor('login/dologout', __('Logout')); ?></li>
        </ul>
      <?php endif; ?>
    </li>
  </ul>
</div>

<div id="subnav" class="<?php if(in_array($this->uri->segment(1), array(""))) : echo 'min">'; else: ?>">
  <ul>
    <?php switch($this->uri->segment(1)):
      case "topics": ?>
        <li class="first <?php if($this->uri->segment(2) == "all") { echo 'current'; } ?>"><?php echo anchor('topics/all', __('Topics')); ?></li>
        <li class=" <?php if($this->uri->segment(2) == "authors") { echo 'current'; } ?>"><?php echo anchor('authors', __('Authors')); ?></li>
        <li class=" <?php if($this->uri->segment(2) == "keywords") { echo 'current'; } ?>"><?php echo anchor('keywords', __('Keywords')); ?></li>
        <li class="last <?php if($this->uri->segment(2) == "recent") { echo 'current'; } ?>"><?php echo anchor('publications/showlist/recent', __('Recent')); ?></li>
      <?php break;
      default: ?>
        <li class="current last"><a href="#"><?php _e('Standard view'); ?></a></li>
    <?php endswitch; ?>
  </ul>
  <?php endif; ?>
</div>

<!-- End of menu -->
