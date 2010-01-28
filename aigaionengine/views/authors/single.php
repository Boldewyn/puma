<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
?>
<div class='author'>
<?php
if ($userlogin->hasRights('publication_edit'))
{
?>
  <div class='optionbox'><?php echo "[".anchor('authors/delete/'.$author->author_id, __('delete'), array('title' => __('Delete this author')))."]&nbsp[".anchor('authors/edit/'.$author->author_id, __('edit'), array('title' => __('Edit this author')))."]"; ?>
  </div>
<?php
}   
?>
  
  <div class='header'><?php echo $author->getName() ?></div>
<table width='100%'>
<tr>
    <td  width='100%'>
      <table class='author_details'>
<?php
      $authorfields = array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 'email'=>__('Email'), 'institute'=>__('Institute'));
      foreach ($authorfields as $field=>$display)
      {
        if (trim($author->$field) != '')
        {
?>
          <tr>
            <td valign='top'><?php echo $display; ?>:</td>
            <td valign='top'><?php echo $author->$field; ?></td>
          </tr>
<?php
        }
      }
      if ($author->url != '') {
        $this->load->helper('utf8');
        $urlname = prep_url($author->url);
        if (utf8_strlen($urlname)>21) {
            $urlname = utf8_substr($urlname,0,30)."...";
        }
        echo "<tr><td>".__('URL').":</td><td><a title='".prep_url($author->url)."' href='".prep_url($author->url)."' class='open_extern'>".$urlname."</a></td></tr>\n";
      }
?>
      </table>
    </td>
    <td>
<?php 
    if ($userlogin->hasRights('bookmarklist')) {
      echo '<div style="border:1px solid black;padding-right:0.2em;margin:0.2em;">';
      echo "<ul>";
      echo  '<li><nobr>['
           .anchor('bookmarklist/addauthor/'.$author->author_id,__('BookmarkAll'))
           .']</nobr></li><li><nobr>['
           .anchor('bookmarklist/removeauthor/'.$author->author_id,__('UnBookmarkAll')).']</nobr></li>';
      echo  "</ul>";
    }
//echo  "<li><nobr>["
//      .anchor('export/author/'.$author->author_id,__('Export'))."]</nobr></li>

echo '</div>';
?>
    </td>
</tr>
</table>
<?php
if ($userlogin->hasRights("publication_edit")) {
    
    $similar = $author->getSimilarAuthors();
    if (count($similar)>0) {
        echo "<div class='message'>".__('Found authors with very similar names. You can choose to merge the following authors with this author by clicking on the merge link.')."<br/>\n";
        foreach ($similar as $simauth) {
            echo anchor('authors/show/'.$simauth->author_id, $simauth->getName(), array('title' => __('Click to show details')))."\n";
		    echo '('.anchor('authors/merge/'.$author->author_id.'/'.$simauth->author_id, 'merge', array('title' => __('Click to merge'))).")<br/>\n";
		}
		echo "</div>\n";
    }
}
?>

  <br/>
</div>
<?php
echo "<div id='tagcloud'>\n";
$keywords = $author->getKeywords();
if (sizeof($keywords) > 0)
{
  echo "<p class=header2>".__('Keywords').":</p>\n";
  $keywordContent['keywordList'] = $keywords;
  $keywordContent['isCloud'] = true;
  echo $this->load->view('keywords/list_items', $keywordContent, true);
}
echo "</div>\n"; //tagcloud
?>