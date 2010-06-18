<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$initial = '';
if ($authorlist[0]->cleanname == '') {
  echo '<ul>';
  $initial = 'not matching me';
}
foreach ($authorlist as $author) {
  if ($author->cleanname != '' && strtoupper($author->cleanname[0]) != $initial) {
      if ($initial != '') { echo '</ul>'; }
      $initial = strtoupper($author->cleanname[0]);
      echo '<h3 id="author_'.$initial.'">'.$initial.'</h3>';
      echo '<ul>';
  }
  echo '<li>'.anchor('authors/show/'.$author->author_id, $author->getName('vlf')).'</li>';
}
?>
</ul>
