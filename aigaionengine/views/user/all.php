<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php _e("All Users")?></h2>

<?php foreach ($groups as $group => $users): ?>
  <div class="user_all_group">
    <h3><?php _h($group)?></h3>
    <ul>
      <?php foreach ($users as $user): ?>
        <li><?php _a('user/'.h($user->loginname), h($user->loginname))?></li>
      <?php endforeach?>
    </ul>
  </div>
<?php endforeach?>
