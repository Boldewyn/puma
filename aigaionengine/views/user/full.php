<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php printf(_e('User %s %s'), h($user->firstname), h($user->surname));?></h2>


<form accept-charset="ISO-8859-1" action="http://www-cgi.uni-regensburg.de/Fakultaeten/Physik/Fakultaet/people/mit_abfrage.php" method="post">
  <p>
    <input type="hidden" name="Bruch" value="<?php echo h($user->firstname), " ", h($user->surname);?>" />
    <input type="submit" class="pseudolink submit" value="<?php _e('Search this user in the faculty&raquo;s database.');?>" />
  </p>
</form>
