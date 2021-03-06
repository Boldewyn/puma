<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
$this->load->helper('publication');?>

<?php if (isset ($quicksearch) && $quicksearch): ?>
  <h2><?php _e('Quicksearch results')?></h2>
  <p><?php _a('search?q='.$query, __('(Proceed to advanced search)'))?></p>
<?php else: ?>
  <h2><?php _e('Advanced Search Results')?></h2>
<?php endif ?>

<?php
//$resulttabs will be 'title'=>'resultdisplay'.
//later on, display will take care of surrounding divs, and show-and-hide-scripts for the tabs
$resulttabs = array();
foreach ($searchresults as $type=>$resultList) {
    switch ($type) {
        case 'authors':
            $authordisplay = '<ul>';
            foreach ($resultList as $author) {
                $authordisplay .= '<li>'.anchor('authors/show/'.$author->author_id,$author->getName()).'</li>';
            }
            $authordisplay .= '</ul>';
            $resulttabs[sprintf(__('Authors: %s'),count($resultList))] = $authordisplay;
            break;
        case 'topics':
            $topicdisplay = '<ul>';
            foreach ($resultList as $topic) {
                $topicdisplay .= '<li>'.anchor('topics/single/'.$topic->topic_id,$topic->name).'</li>';
            }
            $topicdisplay .= '</ul>';
            $resulttabs[sprintf(__('Topics: %s'),count($resultList))] = $topicdisplay;
            break;
        case 'keywords':
            $keyworddisplay = '<ul>';
            foreach ($resultList as $kw) {
                $keyworddisplay .= '<li>'.anchor('keywords/single/'.$kw->keyword_id,$kw->keyword).'</li>';
            }
            $keyworddisplay .= '</ul>';
            $resulttabs[sprintf(__('Keywords: %s'),count($resultList))] = $keyworddisplay;
            break;
/*        case 'publications_titles':
            $pubdisplay = '<ul>';
            foreach ($resultList as $publication) {
                $pubdisplay .= '<li>';
                $pubdisplay .= anchor('publications/show/'.$publication->pub_id,$publication->title);
                $pubdisplay .= '</li>';
            }
            $pubdisplay .= "</ul>";
            $resulttabs[sprintf(__('Publications: %s'),count($resultList))] = $pubdisplay;
            //option below displays the publciations as list, but I don't want the headers and everything... maybe make an option in that view that 
            //determines whether headers are displayed?
            //$resulttabs[sprintf(__('Publications: %s'),count($resultList))] = $this->load->view('publications/list', array('publications'=>$resultList), true);
            break;
        case 'publications_bibtex':
            $pubdisplay = "<ul>";
            foreach ($resultList as $publication) {
                $pubdisplay .= '<li>';
                $pubdisplay .= anchor('publications/show/'.$publication->pub_id,$publication->bibtex_id.': '.$publication->title);
                $pubdisplay .= '</li>';
            }
            $pubdisplay .= "</ul>";
            $resulttabs[sprintf(__('Citation ID: %s'),count($resultList))] = $pubdisplay;
            break;
        case 'publications_notes':
            $pubdisplay = "<ul>";
            foreach ($resultList as $publication) {
                $pubdisplay .= '<li>';
                $pubdisplay .= anchor('publications/show/'.$publication->pub_id,$publication->title);
                $pubdisplay .= '</li>';
            }
            $pubdisplay .= "</ul>";
            $resulttabs[sprintf(__('Notes: %s'),count($resultList))] = $pubdisplay;
            break;
  */
        default:
            break;
    }
  
}


//show all relevant result tabs
foreach ($resulttabs as $title=>$tabdisplay) {
    echo '<h3>'.sprintf(__('%s matches'), $title).'</h3>';
    echo $tabdisplay;
}

$types = array();
$resultHeaders = array();
$result_div_ids = array();
foreach ($searchresults as $title=>$content) {
  if (substr($title, 0, strlen('publication')) == 'publication') {
    $type = substr($title, strlen('publication') + 2);
    $types[] = $type;
    $resultHeaders[$type] = ucfirst($type).' ('.count($content).')';
    $result_div_ids[$type] = 'result_'.$type;
    $result_views[$type] = $this->load->view('publications/list', array('publications' => $content, 'order' => 'year'), true);
  }
}

if (count($types) > 0) {
  echo '<h3>'.__('Publication matches').'</h3>';
  $cells = '';
  $divs  = '';
  $hideall = '';
  foreach ($types as $type) {
    $cells .= '<td><h4><a href="#" onclick="';
    foreach ($types as $type2) {
      if ($type2 == $type)
        $cells .= '$(\'#'.$result_div_ids[$type2].'\').show();';
      else
        $cells .= '$(\'#'.$result_div_ids[$type2].'\').hide();';
    }
    $cells .= '">'.$resultHeaders[$type].'</a></h4></td>';
    $divs .= '<div id="'.$result_div_ids[$type].'">'.$result_views[$type].'</div></div>';
    $hideall .= '$(\'#'.$result_div_ids[$type].'\').hide();';
  }
  $showfirst = '$(\'#'.$result_div_ids[$types[0]].'\').show();';
?>  
  <table style="width:100%">
    <tr>
      <?php echo $cells ?>
    </tr>
  </table>
<?php
  echo $divs;
  echo '<script type="text/javascript">'.$hideall.$showfirst.'</script>';
} else { //no publication results
    if (count($resulttabs)==0) {
        echo '<p class="info">'.sprintf(__('No search results found for query: %s'), '<strong>'.h($query).'</strong>').'</p>';
    } else {
        echo '<p class="info">'.sprintf(__('Search results for query: %s'), '<strong>'.h($query).'</strong>').'</p>';
    } 
}
