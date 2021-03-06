<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
if(! isset($query)) { $query = ""; }
if(! isset($searchengines)) { $searchengines = array(); }
?>
<form method="get" action="." onsubmit="return false;" id="search_external_form">
  <h2><?php _e('Search on external sites')?></h2>
  <p>
    <label for="search_external_query" class="block"><?php _e('Search term:')?></label>
    <input type="text" class="text" style="width:72.75em" name="q" id="search_external_query" value="<?php _h($query)?>" />
  </p>
  <script type="text/javascript">
  /*<![CDATA[*/
    function search_external_submit (action, method, q, p) {
      var $q = $('#search_external_query');
      var $f = $('#search_external_form');
      $f.attr('method', method)
        .attr('action', action)
        .attr('target', "_blank")
        .attr('encoding', "application/x-www-form-urlencoded;charset=utf-8");
      $q.attr('name', q);
      if (p) {
        for (var i = 0; i < p.length; i++) {
          $f.append('<input type="hidden" name="'+p[i][0]+'" value="'+p[i][1].replace("{query}", $q.val())+'" />');
        }
      }
      $f.submit();
      $('input:hidden', $f).remove();
      return false;
    }
  /*   ]]>   */
  </script>
  <fieldset>
  <legend><?php _e('Search engine:') ?></legend>
  <ul>
    <?php
      foreach ($searchengines as $e):
        if (isset($e['active']) && $e['active'] == 1) : ?>
          <li>
            <button type="button" title="<?php echo $e['url'];?>"
                onclick="search_external_submit('<?php _h($e['url']) ?>', '<?php echo $e['method'];?>', '<?php echo $e['q'] ?>'
                <?php if(isset($e['p']) && strlen($e['p']) > 0) { echo ', ',h($e['p']); }?>
                );"><?php
              if (isset ($e['image']) && $e['image'] != '') {
                echo '<img src="',base_url(),$e['image'],'" alt="',$e['name'],'" />';
              } else {
                echo '<span class="text">',$e['name'],'</span>';
              }
            ?></button>
          </li>
        <?php
        endif;
      endforeach;
    ?>
  </ul>
  </fieldset>
  <p>
    <?php _e('This form allows you to search external websites. Searches will open in new windows.')?>
  </p>
</form>
