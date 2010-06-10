<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$importCount = count($publications);
$b_even = true; ?>

<form method="post" action="<?php _url('import/commit') ?>">
  <h2><?php _e('Review publications') ?></h2>
  <?php for ($i = 0; $i < $importCount; $i++):

    $b_even = !$b_even;
    if ($b_even) {
        $even = 'even';
    } else {
        $even = 'odd';
    }

    echo "<div class='publication_summary ".$even."' id='publicationsummary".$i."'>\n";
        echo "<table width='100%'>\n";
        //open the edit form
        echo '<input type="hidden" name="pub_type_'.$i.'" value="'.$publications[$i]->pub_type.'" />';
        //bibtex_id

        ?>
        <tr>
          <td colspan = 2><?php
            echo form_checkbox(array('name' => 'do_import_'.$i, 'id' => 'import_'.$i, 'value'=>'CHECKED', 'checked' => TRUE));
            echo __('Import:')." <b>".$publications[$i]->title."</b>\n"; 
            
            if ($reviews[$i]['title'] != null)
              echo "<p class='error'>".$reviews[$i]['title'].'</p>';
            ?>
            </td>
        </tr>
        <?php
        if ($reviews[$i]['bibtex_id'] != null)
        {
        ?>
        <tr>
          <td colspan = 2><p class='error'><?php echo $reviews[$i]['bibtex_id'] ?></p></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td style='width:2em;'></td>
          <td><?php echo __('Citation')." ".form_input(array('name' => 'bibtex_id_'.$i, 'id' => 'bibtex_id_'.$i, 'size' => '45'), $publications[$i]->bibtex_id); ?></td>
        </tr>
        <?php

        echo '<input type="hidden" name="authorcount_'.$i.'" value="'.count($publications[$i]->authors).'" />';
        
        /** 
        the review form contains the following data,
        for a to-be-reviewed author j [0..nrAuthors] 
        for publication i [0..import_count]:
          author_i_j_input: the original bibtex parsed version of the input text for this author as hidden field
          author_i_j_alternative: a value normally determined by a radio button selection. 
               A value of -1 means: create new author from input text. (if no alternatives at all, -1 is used)
               If this radio button has another value it determines the existing author that should be used.
        */
        if ($reviews[$i]['authors'] != null) //each item consists of an array A with A[0] a review message, and A[1] an array of the similar author ID
        {

          ?>
          <tr>
            <td></td><td valign='top'><br/><b><?php echo __('Choose alternative authors:'); ?></b></td>
          </tr>
          <tr>
            <td></td><td>
              <?php
              $j = 0;
              foreach ($publications[$i]->authors as $author)
              {
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputfirst" value="'.$author->firstname.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputvon" value="'.$author->von.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputlast" value="'.$author->surname.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputjr" value="'.$author->jr.'" />';
                $similar_authors = $reviews[$i]['authors'][1][$j];
                if (count($similar_authors)!=0 ) {
                    echo '<br/>'.sprintf(__('Options for import-author "%s":'), $author->getName('lvf')).'<br/>';
                    $exactMatch = false;
                    $alternatives = '';
                    $radiocheck = false;
                    foreach ($similar_authors as $sa_id) {
                      $sa = $this->author_db->getByID($sa_id);
                      $feedback = '['.__('from database').']';
                      if ($sa->getName('lvf') == $author->getName('lvf')) { //exact match!
                        $exactMatch = True;
                        $feedback = '['.__('keep').']';
                        $radiocheck = true;
                      }
                      $alternatives .= form_radio(array('name'        => 'author_'.$i.'_'.$j.'_alternative',
                                            'id'          => 'author_'.$i.'_'.$j.'_alternative',
                                            'title'       => __('select to use similar author found in database'),
                                            'value'       => $sa_id,
                                            'checked'     => $radiocheck
                                           )).$sa->getName('lvf').' '.$feedback.'<br/>';
                    }
                    if (!$exactMatch)
                      echo form_radio(array('name'        => 'author_'.$i.'_'.$j.'_alternative',
                                            'id'          => 'author_'.$i.'_'.$j.'_alternative',
                                            'title'       => __('select to use author as found in BibTeX'),
                                            'value'       => '-1',
                                            'checked'     => TRUE
                                           )).$author->getName('lvf').' [add new]<br/>';
                    echo $alternatives;
                } else {
                    //no similar authors. Either we have ONE exact match, OR we have NO macth at all
                    $exactMatchingAuthor = $this->author_db->getByExactName($author->firstname, $author->von, $author->surname, $author->jr);
                    if ($exactMatchingAuthor == null) {
                        echo '<input type="hidden" name="author_'.$i.'_'.$j.'_alternative" value="-1" />';
                    } else {
                        echo '<input type="hidden" name="author_'.$i.'_'.$j.'_alternative" value="'.$exactMatchingAuthor->author_id.'" />';
                    }
                }
                $j++;
              }

              ?>
            </td>
          </tr>
          <?php
        }
        else
        {
          //authors 
          //no review message, i.e. either exact matches or new authors. proceed accordingly to build up hidden fields.
              $j = 0;
              foreach ($publications[$i]->authors as $author)
              {
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputfirst" value="'.$author->firstname.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputvon" value="'.$author->von.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputlast" value="'.$author->surname.'" />';
                echo '<input type="hidden" name="author_'.$i.'_'.$j.'_inputjr" value="'.$author->jr.'" />';
                //no similar authors. Either we have ONE exact match, OR we have NO macth at all
                $exactMatchingAuthor = $this->author_db->getByExactName($author->firstname, $author->von, $author->surname, $author->jr);
                if ($exactMatchingAuthor == null) {
                    echo '<input type="hidden" name="author_'.$i.'_'.$j.'_alternative" value="-1" />';
                } else {
                    echo '<input type="hidden" name="author_'.$i.'_'.$j.'_alternative" value="'.$exactMatchingAuthor->author_id.'" />';
                }
                $j++;
              }
        }

        echo '<input type="hidden" name="editorcount_'.$i.'" value="'.count($publications[$i]->editors).'" />';
        
        /** 
        the review form contains the following data,
        for a to-be-reviewed editor j [0..nrEditors] 
        for publication i [0..import_count]:
          editor_i_j_input: the original bibtex parsed version of the input text for this editor as hidden field
          editor_i_j_alternative: a value normally determined by a radio button selection. 
               A value of -1 means: create new editor from input text. (if no alternatives at all, -1 is used)
               If this radio button has another value it determines the existing editor that should be used.
        */
        if ($reviews[$i]['editors'] != null) //each item consists of an array A with A[0] a review message, and A[1] an array of the similar author ID
        {

          ?>
          <tr>
            <td></td><td valign='top'><br/><b><?php echo __('Choose alternative editors:')?></b></td>
          </tr>
          <tr>
            <td></td><td>
              <?php
              $j = 0;
              foreach ($publications[$i]->editors as $editor)
              {
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputfirst" value="'.$editor->firstname.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputvon" value="'.$editor->von.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputlast" value="'.$editor->surname.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputjr" value="'.$editor->jr.'" />';
                $similar_editors = $reviews[$i]['editors'][1][$j];
                if (count($similar_editors)!=0 ) {
                    echo '<br/>Options for import-editor '.$editor->getName('lvf').':<br/>';
                    $exactMatch = false;
                    $alternatives = '';
                    $radiocheck = false;
                    foreach ($similar_editors as $sa_id) {
                      $sa = $this->author_db->getByID($sa_id);
                      $feedback = '['.__('from database').']';
                      if ($sa->getName('lvf') == $editor->getName('lvf')) { //exact match!
                        $exactMatch = True;
                        $feedback = '['.__('keep').']';
                        $radiocheck = true;
                      }
                      $alternatives .= form_radio(array('name'        => 'editor_'.$i.'_'.$j.'_alternative',
                                            'id'          => 'editor_'.$i.'_'.$j.'_alternative',
                                            'title'       => __('select to use similar editor found in database'),
                                            'value'       => $sa_id,
                                            'checked'     => $radiocheck
                                           )).$sa->getName('lvf').' '.$feedback.'<br/>';
                    }
                    if (!$exactMatch)
                      echo form_radio(array('name'        => 'editor_'.$i.'_'.$j.'_alternative',
                                            'id'          => 'editor_'.$i.'_'.$j.'_alternative',
                                            'title'       => __('select to use editor as found in BibTeX'),
                                            'value'       => '-1',
                                            'checked'     => TRUE
                                           )).$editor->getName('lvf').' [add new]<br/>';
                    echo $alternatives;
                } else {
                    //no similar editors. Either we have ONE exact match, OR we have NO macth at all
                    $exactMatchingEditor = $this->author_db->getByExactName($editor->firstname, $editor->von, $editor->surname, $editor->jr);
                    if ($exactMatchingEditor == null) {
                        echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_alternative" value="-1" />';
                    } else {
                        echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_alternative" value="'.$exactMatchingEditor->author_id.'" />';
                    }
                }
                $j++;
              }

              ?>
            </td>
          </tr>
          <?php
        }
        else
        {
          //editor 
          //no review message, i.e. either exact matches or new editors. proceed accordingly to build up hidden fields.
              $j = 0;
              foreach ($publications[$i]->editors as $editor)
              {
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputfirst" value="'.$editor->firstname.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputvon" value="'.$editor->von.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputlast" value="'.$editor->surname.'" />';
                echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_inputjr" value="'.$editor->jr.'" />';
                //no similar editors. Either we have ONE exact match, OR we have NO macth at all
                $exactMatchingEditor = $this->author_db->getByExactName($editor->firstname, $editor->von, $editor->surname, $editor->jr);
                if ($exactMatchingEditor == null) {
                    echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_alternative" value="-1" />';
                } else {
                    echo '<input type="hidden" name="editor_'.$i.'_'.$j.'_alternative" value="'.$exactMatchingEditor->author_id.'" />';
                }
                $j++;
              }
        }



        if ($reviews[$i]['keywords'] != null)
        {
          $keywords = $publications[$i]->keywords;
          $keyword_string = "";
          if (is_array($keywords))
          foreach ($keywords as $keyword)
          {
            $keyword_string .= $keyword->keyword.", ";
          }
          $keywords = $keyword_string;

          ?>
          <tr>
            <td colspan = 2><p class='error'><?php echo $reviews[$i]['keywords'] ?></p></td>
          </tr>
          <tr>
            <td valign='top'><label for="import_review_keywords_<?php echo $i?>"><?php _e('Keywords:')?></label></td>
            <td valign='top'>
              <input type="text" class="optional extended_input text" name="keywords_<?php echo $i?>" id="import_review_keywords_<?php echo $i?>" value="<?php _h($keywords) ?>" />
              <script type="text/javascript">
              $(function () {
                Puma.tokenized_autocomplete('#import_review_keywords_<?php echo $i?>', '<?php _url('keywords/li_keywords')?>', 'keywords');
              });
              </script>
            </td>
          </tr>
        <?php
        }
        foreach (getFullFieldArray() as $field)
        {
          if ($field =="month")
          {
            echo '<input type="hidden" name="'.$field."_".$i.'" value="'.formatMonthBibtexForEdit($publications[$i]->$field).'" />';
          } else if ($field != "keywords") {
            echo '<input type="hidden" name="'.$field."_".$i.'" value="'.$publications[$i]->$field.'" />';
          }
          else if ($reviews[$i]['keywords'] == null)
          {
            if (is_array($publications[$i]->keywords))
            {
              $keywords = $publications[$i]->keywords;
              $keyword_string = "";
              foreach ($keywords as $keyword)
              {
                $keyword_string .= $keyword->keyword.", ";
              }
              $keywords = substr($keyword_string, 0, -2);
              echo '<input type="hidden" name="keywords_'.$i.'" value="'.$keywords.'" />';
            }
            else
            echo '<input type="hidden" name="keywords_'.$i.'" value="'.$publications[$i]->keywords.'" />';
          }
        }
        echo '<input type="hidden" name="actualyear_'.$i.'" value="'.$publications[$i]->actualyear.'" />'; //don't forget to remember this one... as during import, actualyear is determined in parser_import.php
        echo '<input type="hidden" name="old_bibtex_id_'.$i.'" value="'.$publications[$i]->bibtex_id.'" />'; //don't forget to remember this one... when the bibtexID is changed in the edit box, we need to know whether we should change any crossrefs (later on in controller import.php#commit() )

        ?>
      </table>
    </div>
  <?php endfor; ?>
  <p>
    <input type="hidden" name="import_count" value="<?php echo $importCount ?>" />
    <input type="hidden" name="markasread" value="<?php echo $markasread? 'markasread' : '' ?>" />
    <input type="hidden" name="formname" value="import" />
    <input type="submit" value="<?php _e('Import') ?>" />
  </p>
</form>
