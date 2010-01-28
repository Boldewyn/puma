<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
?>
<div class='keyword'>
  <div class='header'><?php echo $keyword->keyword ?></div>
<table width='100%'>
<tr>
    <td>
<?php 
echo "<div style='border:1px solid black;padding-right:0.2em;margin:0.2em;'>
<ul>
";
    if ($userlogin->hasRights('bookmarklist')) {
      echo '<li><nobr>['.anchor('bookmarklist/addkeyword/'.$keyword->keyword_id,__('BookmarkAll')).']</nobr></li>
<li><nobr>['.anchor('bookmarklist/removekeyword/'.$keyword->keyword_id,__('UnBookmarkAll')).']</nobr></li>';
    }
echo  "
</ul>
</div>";
?>
    </td>
</tr>
</table>
  <br/>
</div>