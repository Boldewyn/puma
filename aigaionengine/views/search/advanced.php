<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$userlogin = getUserLogin();
if (!isset($query)||($query==null)) $query='';
if (!isset($options)||($options==null))
            $options = array('authors',
                             'topics',
                             'keywords',
                             'publications',
                             'publications_titles',
                             'publications_notes',
                             'publications_bibtex_id',
                             'publications_abstracts');
?>
<h2><?php _e('Advanced Search')?></h2>
<?php echo form_open('search/advancedresults')?>
  <fieldset>
    <legend><?php _e('Search terms')?></legend>
    <p>
      <input type="text" class="text extended_input" name="q" value="<?php _h($query)?>" />
    </p>
  </fieldset>
  <fieldset class="half">
    <legend><?php _e('Result types')?></legend>
    <p><?php _e('Choose which types of results you want returned')?></p>
    <p>
      <?php echo form_checkbox('return_authors','return_authors',in_array('authors',$options), 'id="search_advanced_authors"')?>
          <label for="search_advanced_authors"><?php _e('Return authors')?></label><br/>
      <?php echo form_checkbox('return_publications','return_publications',in_array('publications',$options), 'id="search_advanced_publications"')?>
          <label for="search_advanced_publications"><?php _e('Return publications')?></label><br/>
      <?php echo form_checkbox('return_topics','return_topics',in_array('topics',$options), 'id="search_advanced_topics"')?>
          <label for="search_advanced_topics"><?php _e('Return topics')?></label><br/>
      <?php echo form_checkbox('return_keywords','return_keywords',in_array('keywords',$options), 'id="search_advanced_keywords"')?>
          <label for="search_advanced_keywords"><?php _e('Return tags')?></label>
    </p>
  </fieldset>
  <fieldset class="half">
    <legend><?php _e('Publication search')?></legend>
    <p><?php _e('Choose, if you are searching for publications (see above!), which fields are searched')?></p>
    <p>
      <?php echo form_checkbox('search_publications_titles','search_publications_titles',
                in_array('publications_titles',$options), 'id="search_advanced_publication_titles"')?>
          <label for="search_advanced_publication_titles"><?php _e('Search publication titles')?></label><br/>
      <?php echo form_checkbox('search_publications_notes','search_publications_notes',
                in_array('publications_notes',$options), 'id="search_advanced_publications_notes"')?>
          <label for="search_advanced_publications_notes"><?php _e('Search publication notes')?></label><br/>
      <?php echo form_checkbox('search_publications_bibtex_id','search_publications_bibtex_id',
                in_array('publications_bibtex_id',$options), 'id="search_advanced_publications_bibtex_id"')?>
          <label for="search_advanced_publications_bibtex_id"><?php _e('Search publication Citation')?></label><br/>
      <?php echo form_checkbox('search_publications_abstracts','search_publications_abstracts',
                in_array('publications_abstracts',$options), 'id="search_advanced_publications_abstracts"')?>
          <label for="search_advanced_publications_abstracts"><?php _e('Search publication abstract')?></label><br/>
    </p>
  </fieldset>
  <p>
    <input type="hidden" name="formname" value="advancedsearch" />
    <input type="submit" name="submit_search" class="wide_button" value="<?php _e('Search')?>" />
  </p>
</form>

<hr/>

<h2><?php _e('Advanced Search: Publications on topic restriction')?></h2>
<?php echo form_open('search/advancedresults')?>
  <fieldset class="half">
    <legend><?php _e('Search terms')?></legend>
    <p><?php _e('Leave empty if you want to search all publications')?></p>
    <p>
      <input type="text" class="text extended_input" name="q" value="<?php _h($query)?>" />
      <input type="hidden" name="return_publications" value="return_publications" />
    </p>
    <p><?php _e('Search publications with these terms in the following fields:')?></p>
    <p>
      <?php echo form_checkbox('search_publications_titles','search_publications_titles',
                 in_array('publications_titles',$options), 'id="search_advanced_2_publications_titles"')?>
          <label for="search_advanced_2_publications_titles"><?php _e('Search publication titles')?></label><br/>
      <?php echo form_checkbox('search_publications_notes','search_publications_notes',
                 in_array('publications_notes',$options), 'id="search_advanced_2_publications_notes"')?>
          <label for="search_advanced_2_publications_notes"><?php _e('Search publication notes')?></label><br/>
      <?php echo form_checkbox('search_publications_bibtex_id','search_publications_bibtex_id',
                 in_array('publications_bibtex_id',$options), 'id="search_advanced_2_publications_bibtex_id"')?>
          <label for="search_advanced_2_publications_bibtex_id"><?php _e('Search publication Citation id')?></label><br/>
      <?php echo form_checkbox('search_publications_abstracts','search_publications_abstracts',
                 in_array('publications_abstracts',$options), 'id="search_advanced_2_publications_abstracts"')?>
          <label for="search_advanced_2_publications_abstracts"><?php _e('Search publication abstract')?></label>
    </p>
  </fieldset>
  <fieldset class="half">
    <legend><?php _e('Choose the topic restrictions that apply.')?></legend>
    <p>
      <?php printf( __('Return all publications that satisfy %s of the following conditions:'),
               '<br/>
                <input type="radio" name="anyAll" value="All" checked="checked" />'.__('all').'<br/>
                <input type="radio" name="anyAll" value="Any"/>'.__('any').'<br/>'
             )
      ?>
    </p>
    <div>
      <script type='text/javascript'>
      /*<![CDATA[*/
        function more_conditions() {
            var n = Number($('#search_advanced_n').val())+1;
            var $cond = $('#search_advanced_initcond').clone();
            $cond.removeAttr('id');
            $('label', $cond).attr('for', 'condition_'+n)
                             .text('<?php _e('Condition %d:')?>'.replace("%d", n));
            $('input:radio', $cond).attr('name', 'doOrNot'+n);
            $('select', $cond).attr('id', 'condition_'+n)
                              .attr('name', 'topicSelection'+n);
            $('#conditions').append($cond);
            $('#search_advanced_n').val(n);
        }
    /*   ]]>   */
    </script>
      <div id="conditions">
        <p class="search_advanced_condition" id="search_advanced_initcond">
          <label for="condition_1"><?php printf(__('Condition %d:'), 1)?></label>
          <input type="radio" name="doOrNot1" value="True" checked="checked" /> <?php _e('Do')?><br/>
          <input type="radio" name="doOrNot1" value="False" /> <?php _e('Do not')?>
          &nbsp; &nbsp; <?php _e(' appear in ')?>
<?php
$config = array('onlyIfUserSubscribed'=>False,
                'includeGroupSubscriptions'=>True,);
if (is_user()) { $config['user'] = $userlogin->user(); }
$this->load->view('topics/optiontree',
              array('topics'   => $this->topic_db->getByID(1,$config),
              'showroot'  => False,
              'header'    => __('Select topic to include or exclude').'...',
              'dropdownname' => 'topicSelection1',
              'depth'     => -1,
              'selected'  => 1,
              'id'        => 'condition_1',
    )
)
?>
        </p>
      </div>
      <p id='moreconditions'>
        <input type="hidden" name="numberoftopicconditions" id="search_advanced_n" value="1" />
        <input type="button" class="button" value="<?php _e('More&hellip;')?>" onclick="more_conditions()" />
      </p>
    </div>

  </fieldset>
  <p>
    <input type="hidden" name="formname" value="advancedsearch" />
    <input type="submit" name="submit_search" class="wide_button" value="<?php _e('Search')?>" />
  </p>
</form>
