<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
if(! isset($query)) { $query = ""; }
if(! isset($searchengines)) { $searchengines = array(); }
?>
<form method="get" action="." onsubmit="return false;" id="search_external_form">
  <h2><?php _e('Search on external sites')?></h2>
  <fieldset>
    <legend><?php _e('Search term')?></legend>
    <p>
      <input type="text" class="text extended_input" name="q" id="search_external_query" value="<?php _h($query)?>" />
    </p>
  </fieldset>
  <script type="text/javascript">
  /*<![CDATA[*/
    function searchExtern (action, method, parameters) {
      var q = document.getElementById('search_external_query');
      var f = q.form;
      var hiddens = [];
      f.method = method;
      f.action = action;
      f.target = "_blank";
      f.encoding = "application/x-www-form-urlencoded;charset=utf-8";
      for (var v in parameters) {
        if (parameters[v].search(/{query}/) > -1) {
          q.name = v;
          q.value = parameters[v].replace(/{query}/, q.value);
        } else {
          hiddens.unshift(hidden(v, parameters[v]));
          f.appendChild(hiddens[0]);
        }
      }
      f.submit();
      /** remove hidden inputs after submit */
      while (hiddens.length > 0) {
        hiddens[0].parentNode.removeChild(hiddens[0]);
        hiddens.shift();
      }
      return false;
    }
    
    function hidden (name, value) {
      var i = document.createElement("input");
      i.type = "hidden";
      i.name = name;
      i.value = value;
      return i;
    }
  /*   ]]>   */
  </script>
  <ul>
    <?php
      foreach ($searchengines as $e):
        if (isset($e['active']) && $e['active'] == 1) :
          $params = unserialize($e['parameters']);
          $s = '{';
          foreach ($params as $p => $v) {
            $s .= "&apos;$p&apos; : &apos;$v&apos;,";
          }
          $s = substr($s, 0, -1) . '}';
          ?>
          <li>
            <button type="button" title="<?php echo $e['url'];?>" onclick="searchExtern('<?php echo $e['url'];?>', '<?php echo $e['method'];?>', <?php _h($s)?>);"><?php
              if (isset ($e['image']) && $e['image'] != '') {
                echo '<img src="',$e['image'],'" alt="',$e['name'],'" />';
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
  <p>
    <?php _e('This form allows you to search external websites.')?>
  </p>
</form>
