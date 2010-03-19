<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="topictree-holder">
  <ul class="topictree-list">
    <?php $this->load->view('topics/tree',
            array('topics'   => $topics,
                  'showroot'  => True,
                  'depth'     => -1)) ?>
  </ul>
</div>
