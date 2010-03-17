<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-wiki" class="help-content regular-text">
  <h2><?php printf(__('The %s Wiki'), site_title())?></h2>
  <p><?php _e('The wiki works just like a regular wiki, but uses ordinary HTML for markup.')?></p>
  <p><?php _e('Some specialities:')?></p>
  <ul>
    <li><?php printf(__('The only wiki syntax from Wikipedia, that is recognized, is the link to another wiki page: %s.'), sprintf('<code>[[%s]]</code>', __('Other page')))?></li>
    <li><?php printf(__('To reference any paper or publication inside %s, use LaTeX style markup: %s.'), site_title(), '<code>\\ref{bibtex_id}</code>')?></li>
    <li><?php _e('JavaScript is not allowed on wiki pages.')?></li>
  </ul>
</div>
