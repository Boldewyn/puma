<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Essentially helper functions for quoting stuff and faster echoing
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

function _a() {
  $args = func_get_args();
  echo call_user_func_array('anchor', (array)$args);
}

function _icon($id, $class="") {
  $theme = 'puma';
  $alt = 'Icon '.$id;
  $path = 'static/'.$theme.'/images/icons/'.$id.'.';
  if (file_exists(dirname(FCPATH).'/'.$path.'png')) {
      $src = base_url().$path."png";
  } elseif (file_exists(dirname(FCPATH).'/'.$path.'gif')) {
      $src = base_url().$path."gif";
  } elseif (file_exists(dirname(FCPATH).'/'.$path.'jpg')) {
      $src = base_url().$path."jpg";
  } else {
      $src = base_url().'static/puma/images/icons/missing.png';
  }
  echo sprintf('<img class="icon %s" alt="%s" src="%s" />', $class, $alt, $src);
}

//__END__