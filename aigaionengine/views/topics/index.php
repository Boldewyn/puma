<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="topictree-holder">
  <?php if (isset($all)):
    if (! $all):
      $c1 = 'checked="checked"';
      $c2 = '';
    else:
      $c1 = '';
      $c2 = 'checked="checked"';
    endif; ?>
    <p id="show_all">
      <input type="radio" name="show_all" value="0" id="show_all_false" <?php echo $c1 ?> />
      <label for="show_all_false"><?php _e('show my subscribed topics') ?></label>
      <input type="radio" name="show_all" value="1" id="show_all_true" <?php echo $c2 ?> />
      <label for="show_all_true"><?php _e('show all topics') ?></label>
    </p>
    <script type="text/javascript">
    var cur_show_all_state = <?php echo $all? 'true' : 'false'; ?>;
    $(function () {
      $('#show_all').buttonset().children('input').change(function () {
        if ($(this).attr('id') == 'show_all_true' && !cur_show_all_state) {
          window.location.href += '/all';
        } else if ($(this).attr('id') == 'show_all_false' && cur_show_all_state) {
          window.location.href = window.location.href.replace(/\/all/, '');
        }
      });
    });
    </script>
  <?php endif; ?>
  <ul class="topictree-list">
    <?php $this->load->view('topics/tree',
            array('topics'   => $topics,
                  'showroot'  => True,
                  'depth'     => -1)) ?>
  </ul>
</div>
