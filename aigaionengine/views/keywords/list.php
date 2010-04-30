<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?php echo $header ?></h2>
<?php 
if (isset($searchbox)&&$searchbox) {
    echo form_open('keywords/searchlist');?>
      <p>
        <input type="text" class="text extended_input" name="keyword_search" id="keyword_search" />
        <input type="submit" value="<?php _e('Show')?>" />
        <script type="text/javascript">
          $('#keyword_search').keyup(function () {
              var url = config.base_url + 'keywords/searchlist/' + this.value;
              $.get(url, function (data) {
                $('#autocomplete_results').html(data);
              });
          });
        </script>
      </p>
    </form><?php
}
?>
<div id="autocomplete_results">
  <?php $this->load->view('keywords/list_items', array('keywordList' => $keywordList, 'useHeaders' => true, 'isCloud' => false)) ?>
</div>
