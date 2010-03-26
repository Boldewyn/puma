<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Essentially helper functions for quoting stuff and faster echoing
 */


function h($s) {
  return htmlspecialchars($s, ENT_COMPAT, 'UTF-8');
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

function site_title($clean=false) {
  return $clean?
    'Puma.Φ':
    'Puma.<em style="font-family:serif">Φ</em>';
}

function _site_title($clean=false) {
  echo site_title($clean);
}

function iconpath($id, $fallback=Null) {
  $theme = 'puma';
  $path = STATICPATH.'themes/'.$theme.'/images/icons/'.$id.'.';
  $url = site_url('static/themes/'.$theme.'/images/icons').'/';
  if (file_exists($path.'png')) {
      $src = $url.$id.'.png';
  } elseif (file_exists($path.'gif')) {
      $src = $url.$id.'.gif';
  } elseif (file_exists($path.'jpg')) {
      $src = $url.$id.'.jpg';
  } elseif ($fallback) {
      $src = $fallback;
  } else {
      $src = base_url().'static/themes/puma/images/icons/missing.png';
  }
  return $src;
}

function icon($id, $class='', $js='', $fallback_id=Null) {
  return sprintf('<img class="icon %s" alt="Icon %s" src="%s" %s />', $class, $id, iconpath($id, iconpath($fallback_id)), $js);
}

function _icon($id, $class='', $js='', $fallback_id=Null) {
  echo icon($id, $class, $js, $fallback_id);
}

function url($url) {
    // do some magic
    return $url;
}

function _url($url) {
    echo url($url);
}

//__END__
