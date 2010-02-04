<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul>
<?php
$this->load->view('topics/tree', array('topics' => $topics,
                                       'showroot'  => True,
                                       'depth'     => -1,));
?>
</ul>
