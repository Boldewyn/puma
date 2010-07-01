<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('site/stats', array('embed'=>1));

?><h2><?php _e('Keywords')?></h2><?php
$this->load->view('keywords/list_items', $keywords);

?><h2><?php _e('Most recent publications')?></h2><?php
$this->load->view('publications/list', array('order' => 'recent',
    'publications' => $publications));

