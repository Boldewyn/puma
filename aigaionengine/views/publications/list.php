<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
//required parameters:
//$publications[] - array with publication objects
//
//optional parameters
//$order - Element for sub header content, defaults to 'year'
//$noBookmarkList - bool, show no bookmarking icons
//$noNotes - bool, show no notes
//

$userlogin  = getUserLogin();
$this->load->helper('publication');

//note that when 'order' is set, this view supposes that the data is actually ordered in that way!
//use 'none' or other nonexisting fieldname for no headers.
if (!isset($order))
  $order = 'year';

if (!isset($pubCount) || ($pubCount==0))
  $pubCount = sizeof($publications);
  
//retrieve the publication summary and list stype preferences (author first or title first etc)
$summarystyle = $userlogin->getPreference('summarystyle');  
$liststyle    = $userlogin->getPreference('liststyle');

if (isset($multipageprefix) && isset($currentpage)) {
    $pagination = $this->load->view('pagination', array('paginationPrefix' => $multipageprefix,
        'paginationCounter' => $pubCount, 'paginationCurrent' => $currentpage), true);
} else {
    $pagination = '';
}

$segments = $this->uri->segment_array();
$last = array_pop($segments);
if (! in_array($last, array('recent', 'year', 'author', 'title', 'type'))) {
    $segments[] = $last;
}
$segments = join('/', $segments);

//here the output starts
?><div class="publication_list">
    <p class="optionbox">
        <?php _e('Sort by') ?>
        <?php _a($segments.'/recent', '['.__('recency').']') ?>
        <?php _a($segments.'/year', '['.__('year').']') ?>
        <?php _a($segments.'/author', '['.__('author').']') ?>
        <?php _a($segments.'/title', '['.__('title').']') ?>
        <?php _a($segments.'/type', '['.__('type').']') ?>
    </p>
    <?php

    if (isset($header) && ($header != '')) {
        echo '<h2>',$header,'</h2>';
    }
    echo $pagination;
    
    $even = 'odd';
    $subheader = '';
    $subsubheader = '';
    if (in_array($order, array('recent', 'type'))) {
        echo '<div class="section">';
    }
    foreach ($publications as $publication) {
        if ($publication!=null) {
            $even = $even=='odd'? 'even' : 'odd';
       
        //check whether we should display a new header/subheader, depending on the $order parameter
        switch ($order) {
          case 'year':
            $newsubheader = $publication->actualyear;
            if ($newsubheader!=$subheader) {
              if ($subheader != '') { echo '</div>'; }
              $subheader = $newsubheader;
              $even = 'odd';
              echo '<div class="section"><h3>',$subheader,'</h3>';
            }
            break;
          case 'title':
            $newsubheader = "";
            if (strlen($publication->cleantitle)>0)
                $newsubheader = $publication->cleantitle[0];
            if ($newsubheader!=$subheader) {
              if ($subheader != '') { echo '</div>'; }
              $subheader = $newsubheader;
              $even = 'odd';
              echo '<div class="section"><h3>',$subheader,'</h3>';
            }
            break;
          case 'author':
            $newsubheader = "";
            if (strlen($publication->cleanauthor)>0)
                $newsubheader = $publication->cleanauthor[0];
            if ($newsubheader!=$subheader) {
              if ($subheader != '') { echo '</div>'; }
              $subheader = $newsubheader;
              $even = 'odd';
              echo '<div class="section"><h3>',$subheader,'</h3>';
            }
            break;
          case 'type':
            $newsubheader = $publication->pub_type;
            if ($newsubheader!=$subheader) {
              $subheader = $newsubheader;
              if ($publication->pub_type!='Article')
                $even = 'odd';
                echo '<h3>',sprintf(__('Publications of type %s'),$subheader),'</h3>';
            }
            if ($publication->pub_type=='Article') {
                $newsubsubheader = $publication->cleanjournal;
                if ($newsubsubheader!=$subsubheader) {
                  $subsubheader = $newsubsubheader;
                  echo '<h4>',$publication->journal,'</h4>';
                }
            } else {
                $newsubsubheader = $publication->actualyear;
                if ($newsubsubheader!=$subsubheader) {
                  $subsubheader = $newsubsubheader;
                  echo '<h4>',$subsubheader,'</h4>';
                }
            }
            break;
          case 'recent':
            break;
          default:
            break;
        }
        
        ?><div class="publication_summary publication_summary_<?php echo $even?>" id="publicationsummary_<?php echo $publication->pub_id?>">

            <p class="publicationlist_controls"><?php
              
                if ((isset($noBookmarkList) && ($noBookmarkList == true))|| $userlogin->hasRights('bookmarklist')) {
                    if ($publication->isBookmarked) {
                        _a('bookmarklist/removepublication/'.$publication->pub_id,
                           icon('bookmarked'),
                           sprintf('title="%s" id="bookmark_icon_%s" class="remove"',
                              __('remove from bookmark list'), $publication->pub_id));
                    } else {
                        _a('bookmarklist/addpublication/'.$publication->pub_id,
                           icon('nonbookmarked'),
                           sprintf('title="%s" id="bookmark_icon_%s" class="add"',
                              __('add to bookmark list'), $publication->pub_id));
                    }
                }
                
                $attachments = $publication->getAttachments();
                if (count($attachments) != 0) {
                    if ($attachments[0]->isremote) {
                        _a(prep_url($attachments[0]->location), icon('attachment_html'),
                            'rel="external" title="'.sprintf(__('Download %s'),h($attachments[0]->name)).'"');
                    } else {
                        $extension=strtolower(substr(strrchr($attachments[0]->location,"."),1));
                        $params = 'title="'.sprintf(__('Download %s'),$attachments[0]->name).'"';
                        if ($userlogin->getPreference('newwindowforatt')=='TRUE') {
                            $params .= ' rel="external"';
                        }
                        _a('attachments/single/'.$attachments[0]->att_id, icon('attachment_'.$extension, 'attachment'), $params);
                    }
                } else {
                    echo '<span class="inactive_link">'.icon('attachment_none').'</span>';
                }
                
                if (utf8_trim($publication->doi)!='') {
                    echo ' <a title="'.__('Click to follow Digital Object Identifier link to online publication').'"
                          class="doi_link" rel="external" href="http://dx.doi.org/'.$publication->doi.'">DOI</a> ';
                } else {
                    echo ' <span class="inactive_link doi_link">DOI</span>';
                }
                
                if (utf8_trim($publication->url)!='') {
                    echo ' <a title="'.prep_url($publication->url).'"
                          class="pub_link" rel="external" href="'.prep_url($publication->url).'">URL</a> ';
                } else {
                    echo ' <span class="inactive_link pub_link">URL</span>';
                }

            ?></p>
            <p class="publicationlist_data"><?php
            $displayTitle = $publication->title;
            //remove braces in list display
            if (strpos($displayTitle,'$')===false &&
                strpos($displayTitle,'\\')===false) {
                $displayTitle = str_replace(array('{','}'), '', $displayTitle);
            }

            $num_authors = count($publication->authors);

            if ($summarystyle == 'title') {
                ?><span class="title"><?php
                    _a('publications/show/'.$publication->pub_id, h($displayTitle),
                    array('title' => __('View publication details')))
                ?></span><?php
            }

            $current_author = 1;
            foreach ($publication->authors as $author) {
                if (($current_author == $num_authors) && ($num_authors > 1)) {
                    echo __(' and ');
                } else if ($current_author>1 || $summarystyle == 'title') {
                    echo __(', ');
                }

                ?><span class="author"><?php _a('authors/show/'.$author->author_id,
                    h($author->getName()),
                    array('title' => sprintf(__('All information on %s'), $author->cleanname)))
                ?></span><?php
                $current_author++;
            }

            if ($summarystyle != 'title') {
                if ($num_authors > 0) {
                    echo __(', ');
                }
                ?><span class="title"><?php
                    _a('publications/show/'.$publication->pub_id, h($displayTitle),
                    array('title' => __('View publication details')))
                ?></span><?php
            }

            $summaryfields = getPublicationSummaryFieldArray($publication->pub_type);
            foreach ($summaryfields as $key => $prefix) {
                $val = utf8_trim($publication->$key);
                if ($key=='month') { $val=formatMonthText($val); }
                $postfix = '';
                if (is_array($prefix)) {
                    $postfix = $prefix[1];
                    $prefix = $prefix[0];
                }
                if ($val) {
                    echo $prefix.h($val).$postfix;
                }
            }
            
            ?></p><?php

            if (!(isset($noNotes) && ($noNotes == true))) {
                $notes = $publication->getNotes();
                if ($notes != null) {
                    ?><ul class="notelist"><?php
                    foreach ($notes as $note) {
                        ?><li><?php echo $this->load->view('notes/summary', array('note' => $note), true)?></li><?php
                    }
                    ?></ul><?php
                }
            }

        ?></div><?php
        }
    }
    if (in_array($order, array('recent', 'type'))) {
        echo '</div>';
    }
    echo $pagination; ?>
</div>
