<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
?>
<div class='author'>
  <h2><?php _h($author->getName()) ?></h2>
  <?php foreach (array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 
                 'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 
                 'email'=>__('Email'), 'institute'=>__('Institute')) as $field=>$display):
        if (trim($author->$field) != ''): ?>
          <p>
            <label class="block"><?php _h($display) ?>:</label>
            <span><?php _h($author->$field) ?></span>
          </p>
        <?php endif;
      endforeach; ?>
  <ul>
    <li><?php _a('export/author/'.$author->author_id, '[BibTeX]') ?></li>
    <li><?php _a('export/author/'.$author->author_id.'/ris', '[RIS]') ?></li>
    <li><?php _a('authors/embed/'.$author->author_id.'/type', '['.__('Order on Type/Journal').']') ?></li>
    <li><?php _a('authors/embed/'.$author->author_id.'/title', '['.__('Order alphabetically on Title').']') ?></li>
    <li><?php _a('authors/embed/'.$author->author_id.'/author', '['.__('Order alphabetically on Author').']') ?></li>
    <li><?php _a('authors/embed/'.$author->author_id.'/year', '['.__('Order on Year').']') ?></li>
  </ul>
</div>