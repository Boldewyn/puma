<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); $userlogin = getUserLogin(); ?>

<p><?php _a('user', __('&laquo; Back to all users’ overview'))?></p>

<h2><?php printf(__('Group Details: %s'), h($group['firstname']))?></h2>

<table class="datatable">
  <tbody>
    <tr>
      <th><?php _e('Name:')?></th>
      <td><?php _h($group['firstname']) ?></td>
    </tr>
    <tr>
      <th><?php _e('NDS short name:')?></th>
      <td><?php _h($group['surname']); ?></td>
    </tr>
    <tr>
      <th><?php _e('Known group members:')?></th>
      <td>
        <ul>
          <?php foreach ($users as $user):?>
            <li><?php _a('user/'.h($user['login']), h($user['firstname']).' '.h($user['surname']))?></li>
          <?php endforeach ?>
        </ul>
      </td>
    </tr>
  </tbody>
</table>

