<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); $userlogin = getUserLogin(); ?>

<h2><?php _e("User Details:"); echo " "; _h($user->firstname); echo ' '; _h($user->surname);?></h2>

<?php $this->load->view("user/contact", array("embed"=>true, "success" => false));?>

<?php if ($userlogin->hasRights('user_edit_all') ||
          ($userlogin->hasRights('user_edit_self') && $userlogin->userId() == $user->user_id)): ?>
  <p><?php _a("user/{$user->login}/edit", __("Edit account details.")); ?></p>
<?php endif; ?>

<?php if (preg_match('/^[a-z]{3}[0-9]{5}$/', $user->login)): ?>
  <form accept-charset="ISO-8859-1" action="http://www-cgi.uni-regensburg.de/Fakultaeten/Physik/Fakultaet/people/mit_abfrage.php" method="post">
    <p>
      <input type="hidden" name="Bruch" value="<?php _h($user->surname) ?>" />
      <input type="submit" class="pseudolink submit" value="<?php _e('Search this user in the faculty&rsquo;s user database.');?>" />
    </p>
  </form>
<?php endif ?>

<table>
  <tbody>
    <tr>
      <th><?php _e('Name:')?></th>
      <td><?php _h($user->firstname) ?> <?php _h($user->surname) ?></td>
    </tr>
    <tr>
      <th><?php _e('NDS login name:')?></th>
      <td><?php if (preg_match('/^[a-z]{3}[0-9]{5}$/', $user->login)): _h($user->login); else: echo '<em>',__('guest'),'</em>'; endif; ?></td>
    </tr>
    <tr>
      <th><?php _e('E-Mail address:')?></th>
      <td><?php _h($user->email) ?></td>
    </tr>
    <tr>
      <th><?php _e('Groups:')?></th>
      <td><?php $g = array(); foreach ($user->groups as $gr): if ($gr->user_id > 6) $g[] = anchor("group/".$gr->user_id, h($gr->name)); endforeach; echo implode(", ", $g); ?></td>
    </tr>
  </tbody>
</table>

