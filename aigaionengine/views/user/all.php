<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php _e('All Users')?></h2>

<p><?php printf(__('This is a list of all current users of %s. Click on any link to see a detailed description of the user.'), site_title())?></p>

<div class="masonry">
  <?php foreach ($groups as $group => $users): if (count($users['users'])): ?>
      <fieldset class="brick user_all_group">
        <legend><?php _a('group/'.h($users['abbreviation']), sprintf(__('Group: %s'), h($users['name'])))?></legend>
        <ul>
          <?php foreach ($users['users'] as $user): ?>
            <li><?php _a('user/'.h($user['login']), h($user['firstname'].' '.h($user['surname'])))?></li>
          <?php endforeach?>
        </ul>
      </fieldset>
  <?php endif; endforeach?>
</div>
