<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul>
  <?php foreach ($authors as $author): ?>
    <li><?php _h($author->getName()) ?></li>
  <?php endforeach; ?>
</ul>