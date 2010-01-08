<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Essentially helper functions for quoting stuff
 */


function h($s) {
  return htmlspecialchars($s, ENT_COMPAT, "UTF-8");
}

function _h($s) {
  echo h($s);
}

function js($s) {
  return str_replace(array("'", "\n"), array("\\'", "\\\n"), $s);
}

function _js($s) {
  echo js($s);
}


