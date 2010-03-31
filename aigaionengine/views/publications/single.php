<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$publicationfields = getPublicationFieldArray($publication->pub_type);
if (!isset($categorize)) $categorize= False;

//some things are dependent on user rights.
//$accessLevelEdit is set to true iff the edit access level of the publication does not make it
//inaccessible to the logged user. Note: this does NOT yet garantuee atachemtn_edit or note_ediot or publication_edit rights
$accessLevelEdit = $this->accesslevels_lib->canEditObject($publication);
$userlogin  = getUserLogin();
$user       = $this->user_db->getByID($userlogin->userID());
$this->load->helper('translation');
?>
<div class='publication'>
  <p class='optionbox'><?php
    if ($userlogin->hasRights('publication_edit') && $accessLevelEdit) {
        _a('publications/delete/'.$publication->pub_id, '['.__('delete').'] ', array('title' => __('Delete this publication')));
        _a('publications/edit/'.$publication->pub_id, '['.__('edit').'] ', array('title' => __('Edit this publication')));
    }
    if ($userlogin->hasRights('bookmarklist')) {
         if ($publication->isBookmarked) {
            _a('bookmarklist/removepublication/'.$publication->pub_id,
               '['.__('unbookmark').'] ',
               sprintf('title="%1$s" id="bookmark_icon_%2$s" class="remove"',
                  __('remove from bookmark list'), $publication->pub_id));
        } else {
            _a('bookmarklist/addpublication/'.$publication->pub_id,
               '['.__('bookmark').'] ',
               sprintf('title="%1$s" id="bookmark_icon_%2$s" class="add"',
                  __('add to bookmark list'), $publication->pub_id));
        }
        if ($userlogin->hasRights('export_email')) {
            _a('publications/exportEmail/'.$publication->pub_id.'/', '['.__('E-mail').'] ', array('title'=>__('Export by e-mail')));
        }
        _a('export/publication/'.$publication->pub_id.'/bibtex', '['.__('BibTeX').'] ', array('rel'=>'external'));
        _a('export/publication/'.$publication->pub_id.'/ris',    '['.__('RIS').'] ',    array('rel'=>'external'));

        if ($userlogin->hasRights('request_copies')) {
            $author_email = '';
            if (count($publication->authors)>0) {
                foreach ($publication->authors as $author) {
                    if ($author->email != '') {
                        $author_email = $author->email;
                        break;
                    }
                }
            }
            if($author_email != '') {
                $this->load->helper('encode');
                $subject=rawurlencode(sprintf(__('Request for publication &ldquo;%s&rdquo;'), $publication->title));
                $bodytext=
                  rawurlencode(__('Publication').': '.$publication->title.' : '.AIGAION_ROOT_URL.'index.php/publications/show/'.$publication->pub_id)
                 .rawurlencode("\n\n".__('I understand that the document referenced above is subject to copyright.').' ')
                 .rawurlencode(__('I hereby request a copy strictly for my personal use.'))
                 .rawurlencode("\n\n".__('Name and contact details:')."\n");
                 echo '<a href="mailto:'.$author->email.'?Subject='.$subject.'&Body='.$bodytext.'" title="'.__('Request publication by e-mail').'">['.__('Request').']</a>';
            } else {
                echo '<span title="'.__('No e-mail address available for neither of the authors').'">['.__('Request').']</span>';
            }
        }
    }
    ?>
  </p>
  <h2><?php _h($publication->title); ?></h2>
  <table class="publication_details" summary="<?php _e('Publication details')?>">
    <tr>
      <th><?php _e('Type of publication:') ?></th>
      <td><?php echo translateType($publication->pub_type); ?></td>
    </tr>
    <tr>
      <th><?php _e('Citation:') ?></th>
      <td><?php echo $publication->bibtex_id; ?></td>
    </tr>
<?php
    $capitalfields = getCapitalFieldArray();
    foreach ($publicationfields as $key => $class):
      if ($publication->$key):
?>
    <tr>
      <th><?php
        if ($key=='namekey') {
            //stored in the databse as namekey, it is actually the bibtex field 'key'
            echo __('Key').' <span title="'.__('This is the BibTeX &ldquo;key&rdquo; field used to define sorting keys').'">(?)</span>';
        } else {
            if (in_array($key,$capitalfields)) {
                echo utf8_strtoupper(translateField($key));
            } else  {
                echo translateField($key,true);
            }
        }
      ?>:</th>
      <td valign='top'><?php
        switch ($key):
            case 'doi':
                echo '<a href="http://dx.doi.org/'.$publication->$key.'" rel="external">'.$publication->$key.'</a>';
                break;
            case 'url':
                $this->load->helper('utf8');
                $urlname = prep_url($publication->url);
                if (utf8_strlen($urlname)>21) {
                    $urlname = utf8_substr($urlname,0,30).'&hellip;';
                }
                echo '<a title="'.prep_url($publication->url).'" href="'.prep_url($publication->url).'" rel="external">'.$urlname.'</a>';
                break;
            case 'month':
                echo formatMonthText($publication->month);
                break;
            case 'pages':
                echo $publication->pages;
                break;
            case 'crossref':
                $xref_pub = $this->publication_db->getByBibtexID($publication->$key);
                if ($xref_pub != null) {
                    echo '<em>'.anchor('publications/show/'.$xref_pub->pub_id,$publication->$key).':</em>';
                    //and then the summary of the crossreffed pub. taken from views/publications/list
                    $summaryfields = getPublicationSummaryFieldArray($xref_pub->pub_type);
                    echo '<div class="message">
                          <span class="title">'.anchor('publications/show/'.$xref_pub->pub_id,
                            $xref_pub->title, array('title' => __('View publication details'))).
                          '</span>';

                    //authors of crossref
                    $num_authors    = count($xref_pub->authors);
                    $current_author = 1;

                    foreach ($xref_pub->authors as $author)
                    {
                      if (($current_author == $num_authors) & ($num_authors > 1)) {
                        echo ' '.__('and').' ';
                      }
                      else {
                        echo ', ';
                      }

                      echo '<span class="author">'.anchor('authors/show/'.$author->author_id,
                        $author->getName('vlf'), array('title' => __('All information on').' '.$author->cleanname)).
                        '</span>';
                      $current_author++;
                    }

                    //editors of crossref
                    $num_editors    = count($xref_pub->editors);
                    $current_editor= 1;

                    foreach ($xref_pub->editors as $editor)
                    {
                      if (($current_editor == $num_editors) & ($num_editors > 1)) {
                        echo ' '.__('and').' ';
                      }
                      else {
                        echo ', ';
                      }

                      echo  '<span class="author">'.anchor('authors/show/'.$editor->author_id,
                        $editor->getName('vlf'), array('title' => __('All information on').' '.$editor->cleanname)).
                        '</span>';
                      $current_editor++;
                    }
                    if ($num_editors>1) {
                        echo ' '.__('(eds)');
                    } elseif ($num_editors>0) {
                        echo ' '.__('(ed)');
                    }
                    foreach ($summaryfields as $key => $prefix) {
                      $val = trim($xref_pub->$key);
                      $postfix='';
                      if (is_array($prefix)) {
                        $postfix = $prefix[1];
                        $prefix = $prefix[0];
                      }
                      if ($val) {
                        echo $prefix.$val.$postfix;
                      }
                    }
                    echo '</div>'; //end of publication_summary div for crossreffed publication
                } else {
                    echo $publication->$key;
                }
                break;
            default:
                _h($publication->$key);
        endswitch;
      ?></td>
    </tr>
<?php
      endif;
    endforeach;

    $keywords = $publication->getKeywords();
    if (is_array($keywords)) {
      $keyword_string = '';
      foreach ($keywords as $keyword) {
        $keyword_string .= anchor('keywords/single/'.$keyword->keyword_id, h($keyword->keyword)).', ';
      }
      $keyword_string = substr($keyword_string, 0, -2);
    ?>
    <tr>
      <th><?php _e('Keywords:') ?></th>
      <td><?php echo $keyword_string ?></td>
    </tr>
    <?php
    }

    if (count($publication->authors) > 0):
?>
    <tr>
      <th><?php _e('Authors:')?></th>
      <td>
        <span class='authorlist'>
<?php     foreach ($publication->authors as $author) {
            echo anchor('authors/show/'.$author->author_id, h($author->getName('fvl')),
                        array('title' => sprintf(__('All information on %s'), $author->cleanname))).', ';
          }
?>
        </span>
      </td>
    </tr>
<?php
    endif;
    if (count($publication->editors) > 0):
?>
    <tr>
      <th><?php _e('Editors:') ?></th>
      <td>
        <span class='authorlist'>
<?php     foreach ($publication->editors as $author) {
            echo anchor('authors/show/'.$author->author_id, h($author->getName('fvl')),
                        array('title' => sprintf(__('All information on %s'), $author->cleanname))).', ';
          }
?>
        </span>
      </td>
    </tr>
<?php
    endif;

    $crossrefpubs = $this->publication_db->getXRefPublicationsForPublication($publication->bibtex_id);
    if (count($crossrefpubs)>0):
?>
    <tr>
      <th><?php _e('Crossref by:') ?></th>
      <td>
<?php
        foreach ($crossrefpubs as $crossrefpub) {
            $linkname = $crossrefpub->bibtex_id;
            if ($linkname == '') {
                $linkname = $crossrefpub->title;
            }
            echo anchor('/publications/show/'.$crossrefpub->pub_id, $linkname).', ';
        }
?>
      </td>
    </tr>
<?php
    endif;
?>
    <tr>
      <th><?php _e('Added by:') ?></th>
      <td>
        <?php 
            $adder = $this->user_db->getByID($publication->user_id);
             _a('user/'.$adder->login, $adder->abbreviation);
        ?>
      </td>
    </tr>
    <?php if ($accessLevelEdit): ?>
    <tr>
      <th><?php _e('Access rights:') ?></th>
      <td>
        r: <a href="<?php echo site_url('/accesslevels/toggle/publication/'.$publication->pub_id.'/read') ?>"
          class="rights_switch read_switch <?php echo $publication->derived_read_access_level ?>"><?php
          _icon('rights_'.$publication->derived_read_access_level) ?></a>
        e: <a href="<?php echo site_url('/accesslevels/toggle/publication/'.$publication->pub_id.'/edit') ?>"
          class="rights_switch edit_switch <?php echo $publication->derived_edit_access_level ?>"><?php
          _icon('rights_'.$publication->derived_edit_access_level) ?></a>
        (<?php _a('accesslevels/edit/publication/'.$publication->pub_id, __('Edit all rights')) ?>)
      </td>
    </tr>
    <?php endif; ?>
    <tr>
      <th><?php _e('Total mark:') ?></th>
      <td><?php echo $publication->mark; ?></td>
    </tr>
    <?php
    if ($userlogin->hasRights('note_edit')):
      $this->load->helper('form');
    ?>
      <tr>
        <th><?php _e('Your mark:') ?></th>
        <td>
          <div class="publication_mark">
            <?php
              echo form_open('publications/read/'.$publication->pub_id).'<p>';

              $mark = $publication->getUserMark();
              if ($mark==-1) {//not read
                echo form_submit('read',__('Read/Add mark'));
              } else {
                echo form_submit('read',__('Update mark'));
              }
              echo '1';
              for ($i = 1; $i < 6; $i++)
              {
                echo form_radio('mark',$i,$i==$mark);
              }
              echo '5&nbsp;';
              if ($mark!=-1) {// read
                echo '</p></form>';
                echo form_open('publications/unread/'.$publication->pub_id).'<p>';
                echo form_submit('unread',__('Unread'));
              }
            ?>
            </p>
            </form>
          </div>
        </td>
      </tr>
    <?php endif; ?>
  </table>
  <div>
    <?php if ($userlogin->hasRights('attachment_edit') && $accessLevelEdit): ?>
      <p class='optionbox'>
        <?php _a('attachments/add/'.$publication->pub_id, '['.__('add attachment').']')?>
      </p>
    <?php endif ?>
    <h3><?php _e('Attachments') ?></h3>
    <ul class="attachmentlist">
    <?php
      $attachments = $publication->getAttachments();
      foreach ($attachments as $attachment) {
          echo '<li>'.$this->load->view('attachments/summary',
                            array('attachment'   => $attachment),
                            true).'</li>';
      }
    ?>
    </ul>
  </div>
  <div>
    <?php if ($userlogin->hasRights('note_edit') && $accessLevelEdit): ?>
      <p class='optionbox'>
        <?php _a('notes/add/'.$publication->pub_id, '['.__('add note').']') ?>
      </p>
    <?php endif ?>
    <h3><?php _e('Notes') ?></h3>
    <ul class="notelist">
    <?php
        $notes = $publication->getNotes();
        foreach ($notes as $note) {
            echo '<li>'.$this->load->view('notes/summary',
                              array('note'   => $note),
                              true).'</li>';
        }
    ?>
    </ul>
  </div>
  <div>
    <p class='optionbox'>
      <?php if ($userlogin->hasRights('publication_edit') && $accessLevelEdit):
        if ($categorize == True): ?>
          <?php _a('publications/show/'.$publication->pub_id, '['.__('finish categorization').']') ?>
      <?php else: ?>
          <?php _a('publications/show/'.$publication->pub_id.'/categorize', '['.__('categorize publication').']') ?>
      <?php endif;
    endif ?>
    </p>
    <h3><?php _e('Topics') ?></h3>
    <?php if ($userlogin->hasRights('publication_edit') && $accessLevelEdit && $categorize == True) {
        echo '<p class="info">'.__('Click on a topic name to change its subscription status.').'</p>';
        $config = array('onlyIfUserSubscribed'=>True,
                          'user'=>$user,
                          'includeGroupSubscriptions'=>True,
                          'publicationId'=>$publication->pub_id,);
        $root = $this->topic_db->getByID(1, $config);
        $this->load->vars(array('subviews'  => array('topics/publicationsubscriptiontreerow'=>array())));
    } else {
        $config = array('onlyIfUserSubscribed'=>True,
                          'user'=>$user,
                          'includeGroupSubscriptions'=>True,
                          'onlyIfPublicationSubscribed'=>True,
                          'publicationId'=>$publication->pub_id, );
        $root = $this->topic_db->getByID(1, $config);
        $this->load->vars(array('subviews'  => array('topics/maintreerow'=>array())));
    } ?>
    <ul class='topictree-list'>
      <?php $this->load->view('topics/tree', array('topics'   => $root->getChildren(),
                                                   'showroot'  => True,
                                                   'depth'     => -1,)) ?>
    </ul>
  </div>
</div>
