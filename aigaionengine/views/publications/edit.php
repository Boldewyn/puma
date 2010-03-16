<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$publicationfields  = getPublicationFieldArray($publication->pub_type);

$authors = array();
$editors = array();
foreach($publication->authors as $autor) {
  $authors[] = $author->author_id;
}
foreach($publication->editors as $editor) {
  $editors[] = $editor->author_id;
}
$authors = join(',', $authors);
$editors = join(',', $editors);

$userlogin          = getUserLogin();
$user               = $this->user_db->getByID($userlogin->userID());
$this->load->helper('translation');
$isAddForm = $edit_type=='add';
?>
<div class='publication'>
  <h2><?php if ($edit_type == 'add') {
        _e('New Publication');
    } else {
        _e('Edit Publication');
    } ?></h2>
  <p class="note"><?php _e('Fields with a red border are required.')?></p>
  <?php echo form_open('publications/commit', array('id' => 'publication_edit_form', 'class' => 'block')) ?>
    <fieldset class="half">
    <p>
      <label for="publication_edit_pub_type"><?php _e('Type of publication:') ?></label>
      <?php echo form_dropdown('pub_type', getPublicationTypes(), $publication->pub_type, 'id="publication_edit_pub_type" class="extended_input"') ?>
      <script type="text/javascript">
      $('#publication_edit_pub_type').change(function () {
        $.getJSON(config.base_url+"publications/fields/"+$(this).val(), function (data) {
          var key, val;
          $('#publication_edit_form :input').removeClass('required')
            .removeClass('hidden').removeClass('optional').removeClass('optional');
          $('#publication_edit_form p').removeClass('ui-helper-hidden');
          for (key in data) {
            val = data[key];
            $('#publication_edit_'+key).addClass(val);
            if (val == 'hidden' && $('#publication_edit_'+key).val() == '') {
              $('#publication_edit_'+key).parent().addClass('ui-helper-hidden');
            }
          }
          $('#publication_edit_title').addClass('required');
          $('fieldset > *.even').removeClass('even');
          $('fieldset > *:not(.note):not(.ui-helper-hidden):odd').addClass('even');
        });
      });
      </script>
    </p>
    <p>
      <label for="publication_edit_title"><?php _e('Title:') ?></label>
      <input type="text" class="required extended_input text" name="title" id="publication_edit_title" value="<?php _h($publication->title)?>" />
    </p>
    <p>
      <label for="publication_edit_bibtex_id"><?php _e('Citation:') ?></label>
      <input type="text" class="text extended_input" name="bibtex_id" id="publication_edit_bibtex_id" value="<?php _h($publication->bibtex_id) ?>" />
    </p>
    <?php 
    $capitalfields = getCapitalFieldArray();
    $i = 3;
    foreach ($publicationfields as $key => $class):
        $markup = 'p';
        //fields that are hidden but non empty are shown nevertheless
        if (($class == 'hidden') && ($publication->$key != '')) {
            $class = 'nonstandard';
        }
        $fieldCol = '';
        if ($key=='namekey') {
            $fieldCol = __('Key').' <span title="'.__('This is the bibtex &ldquo;key&rdquo; field, used to define sorting keys').'">(?)</span>';
        } elseif (in_array($key,$capitalfields)) {
            $fieldCol = utf8_strtoupper(translateField($key));
        } else  {
            $fieldCol = translateField($key,true);
        }
        if ($class=='nonstandard') {
            $fieldCol .= ' <span title="'.__('This field might not be used by BibTeX for this publication type').'">(*)</span>';
        }
        $fieldCol = '<label for="publication_edit_'.$key.'">'.$fieldCol.':</label>';
        $valCol = '';
        if ($key == 'month') {
          
          $markup = 'div';
          $month = $publication->month;
          if (array_key_exists($month,getMonthsInternal())) {
              $simple_style = '';
              $complex_style = 'display:none;';
          } else {
              $simple_style = 'display:none;';
              $complex_style = '';
          }
          $valCol .= '<div id="publication_edit_month">
              <p id="monthbox_simple" style="clear:none;'.$simple_style.'">'.
                  form_dropdown($simple_style?'':'month', getMonthsInternalNoQuotes(), formatMonthBibtexForEdit($publication->month)).
                  ' <button type="button" onclick="toggleMonth(\'simple\',\'complex\')">'.__('Complex').'</button>
              </p>
              <p id="monthbox_complex" style="clear:none;'.$complex_style.'">
                <input type="text" class="optional text" name="'.($complex_style?'':'month').'"
                  id="month" value="'.formatMonthBibtexForEdit($publication->month).'"/>
                <button type="button" onclick="toggleMonth(\'complex\',\'simple\')">'.__('Simple').'</button><br/>
                <span class="inline_note note">'.__('In the input field above, you can enter a month '.
                  'using bibtex codes containing things such as the default month abbreviations. '.
                  'Do not forget to use outer braces or quotes for literal strings.').'<br/>'.__('Examples:').
                ' <code>aug</code>, <code>nov#{~1st}</code>, <code>{'.__('Between January and May').'}</code></span>
              </p>
            </div>
            <script type="text/javascript">
            function toggleMonth (v1, v2) {
              $("#monthbox_"+v1).hide().find("select, input").removeAttr("name");
              $("#monthbox_"+v2).show().find("select, input").attr("name", "month");
            }
            </script>';
        } else if ($key == 'pages') {
            $valCol .= '<input type="text" class="'.$class.' extended_input text" 
                         name="pages" id="publication_edit_pages" 
                         value="'.h($publication->pages).'"/>';
        } elseif ($key == 'abstract' || $key == 'userfields') {
            $valCol .= '<textarea class="extended_input '.h($class).'" name="'.$key.'" id="publication_edit_'.$key.'" cols="87" rows="3">'.h($publication->$key).'</textarea>';
        } else {
            $valCol .= '<input type="text" class="extended_input '.$class.' text" name="'.h($key).
                       '" id="publication_edit_'.h($key).'" value="'.
                       h($publication->$key).'" />';
        }
      
        if ($class=='hidden') {
            echo '<'.$markup.' class="ui-helper-hidden">'.$fieldCol.$valCol.'</'.$markup.'>';
        } else {
            $emarkup = $markup;
            if ($i % 10 == 0) {
                $markup = '/fieldset><fieldset class="half"><'.$markup;
            }
            $i++;
            echo '<'.$markup.'>'.$fieldCol.$valCol.'</'.$emarkup.'>';
        }   
    endforeach;

    $keywords = $publication->keywords;
    if (is_array($keywords)) {
        $keyword_string = '';
        foreach ($keywords as $keyword) {
            $keyword_string .= $keyword->keyword.', ';
        }
        $keywords = substr($keyword_string, 0, -2);
    } else {
        $keywords = '';
    }
?>      
      <p>
        <label for="publication_edit_keywords"><?php _e('Keywords:') ?></label>
        <input type="text" class="optional extended_input text" name="keywords" id="publication_edit_keywords" value="<?php _h($keywords) ?>" />
        <?php /*echo $this->ajax->auto_complete_field('keywords', $options = array('url' => base_url().'index.php/keywords/li_keywords/', 'update' => 'keyword_autocomplete', 'tokens' => array(",", ";"), 'frequency' => '0.01'))."\n";*/?>
      </p>
    </fieldset>
    <fieldset>
      <legend><?php _e('Authors and Editors') ?></legend>
      <table width='100%'>
        <tr>
          <td style="width:45%">
            <table width="100%">
              <tr>
                <th><?php _e('Authors') ?></th>
              </tr>
              <tr>
                <td>
                  <select name='selectedauthors' id='selectedauthors' style='width:100%;' size='5'>
                    <?php if (is_array($publication->authors)) {
                        foreach ($publication->authors as $author) {
                            echo '<option value='.$author->author_id.'>'.$author->getName('vlf').'</option>';
                        }
                    } ?>
                  </select>
                  <input type="hidden" name="pubform_authors" id="pubform_authors" value="<?php _h($authors) ?>" />
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php _a('#', icon('go-up', ''), 'onclick="AuthorUp()" title="'.__('up').'"') ?>
                  <?php _a('#', icon('go-down', ''), 'onclick="AuthorDown()" title="'.__('down').'"') ?>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <?php _a('#', icon('go-previous', ''), 'id="publication_edit_add_author" title="'.__('add').'"') ?><br/>
            <?php _a('#', icon('go-next', ''), 'id="publication_edit_remove_author" title="'.__('remove').'"') ?>
          </td>
          <td rowspan="2" style="width:45%">
            <?php _e('Search:') ?> <input title="<?php _e('Type in name to quick search. Note: use unaccented letters!');?>" type='text' id='authorinputtext' />
            [<a href="#" onclick="AddNewAuthor(); return false;"><?php _e('Create as new name') ?></a>]<br/>
            <select size="12" id="authorinputselect">
              <?php $Q = $this->db->orderby('cleanname')->get("author");
                foreach ($Q->result() as $author):?>
                  <option value="<?php echo $author->author_id ?>"><?php _h($author->cleanname) ?></option>
              <?php endforeach; ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <table width='100%'>
              <tr>
                <th><?php _e('Editors') ?></th>
              </tr>
              <tr>
                <td>
                  <select name='selectededitors' id='selectededitors' style='width: 100%;' size='5'>
                    <?php if (is_array($publication->editors)) {
                        foreach ($publication->editors as $editor) {
                            echo '<option value='.$editor->author_id.'>'.$editor->getName('vlf').'</option>';
                        }
                    } ?>
                  </select>
                  <input type="hidden" name="pubform_editors" id="pubform_editors" value="<?php _h($editors) ?>" />
                </td>
              </tr>
              <tr>
                <td>
                  <?php _a('#', icon('go-up', ''), 'onclick="EditorUp()" title="'.__('up').'"') ?>
                  <?php _a('#', icon('go-down', ''), 'onclick="EditorDown()" title="'.__('down').'"') ?>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <?php _a('#', icon('go-previous', ''), 'id="publication_edit_add_editor" title="'.__('add').'"') ?><br/>
            <?php _a('#', icon('go-next', ''), 'id="publication_edit_remove_editor" title="'.__('remove').'"') ?>
          </td>
        </tr>
      </table>
      <script type="text/javascript">
      function getOptionVals(who) {
        var val = [];
        $('#selected'+who+' option').each(function () {
          val.push($(this).attr('value'));
        });
        return val.join(',');
      };
      function add(who) {
        $('#authorinputselect option:selected').each(function () {
          if ($('#selected'+who+' option[value='+$(this).val()+']').length == 0) {
            $(this).clone().appendTo($('#selected'+who));
            $('#pubform_'+who).val(getOptionVals(who));
          }
        });
      };
      function remove(who) {
        $('#selected'+who+' option:selected').each(function () {
          $(this).remove();
          $('#pubform_'+who).val(getOptionVals(who));
        });
      };
      $('#publication_edit_add_author').click(function () {
        add('authors');
        return false;
      });
      $('#publication_edit_add_editor').click(function () {
        add('editors');
        return false;
      });
      $('#publication_edit_remove_author').click(function () {
        remove('authors');
        return false;
      });
      $('#publication_edit_remove_editor').click(function () {
        remove('editors');
        return false;
      });
      $('#authorinputtext').keyup(function () {
        var val = $(this).val().toLowerCase();
        $('#authorinputselect option').each(function () {
          if ($(this).text().toLowerCase().indexOf(val) > -1) {
            $(this).removeClass('ui-helper-hidden');
          } else {
            $(this).addClass('ui-helper-hidden');
          }
        });
      });
      </script>
    </fieldset>
    <p>
      <input type="hidden" name="edit_type" value="<?php _h($edit_type)?>" />
      <input type="hidden" name="pub_id" value="<?php _h($publication->pub_id)?>" />
      <input type="hidden" name="user_id" value="<?php _h($publication->user_id)?>" />
      <input type="hidden" name="submit_type" value="submit" />
      <input type="hidden" name="formname" value="publication" />
      <input type="submit" class="submit standard_input" value="<?php $edit_type=='edit'? _e('Change') : _e('Add') ?>" />
      <?php _a($edit_type=='edit'? 'publications/show/'.$publication->pub_id : '', __('Cancel'), 'class="pseudobutton standard_input"') ?>
    </p>
  </form>
</div>
