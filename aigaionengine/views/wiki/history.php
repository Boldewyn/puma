<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php printf(__('History of &ldquo;%s&rdquo;'), h($item))?></h2>

<ol>
  <?php foreach ($data as $d): ?>
    <li><?php _a('wiki/Show_History:'.$d->id, $d->created)?>
        &mdash; <?php printf(__('edited by %s'), anchor('/user/'.$d->login, $d->login)) ?>
        <?php if($d->description): ?>&mdash; <span class="wiki_description"><?php _h($d->description) ?></span><?php endif; ?>
    </li>
  <?php endforeach ?>
</ol>
