<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul class='nosymbol'>
<?php
  $initial = '';
  foreach ($authorlist as $author)
  {
    if ($author->cleanname!='' && strtolower($author->cleanname[0])!=$initial) {
        $initial = strtolower($author->cleanname[0]);
        echo '<li><b>'.$author->cleanname[0].'</b></li>';
    }
    echo "  <li>".anchor('authors/show/'.$author->author_id, $author->getName('vlf'), array('title' => sprintf(__('All information on %s'), $author->cleanname)))."</li>\n";
  }
?>
</ul>
