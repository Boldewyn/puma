<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-wiki" class="help-content regular-text">
  <h2><?php printf(__('The %s Wiki'), site_title())?></h2>
  <p><?php _e('The wiki works just like a regular wiki, but uses ordinary HTML for markup. To simplify entering text, the edit pages provide an optional rich text editor.')?></p>
  <p><?php _e('Some specialities:')?></p>
  <ul>
    <li><?php printf(__('The only wiki syntax from Wikipedia, that is recognized, is the link to another wiki page: %s.'), sprintf('<code>[[%s]]</code>', __('Other page')))?></li>
    <li><?php printf(__('To reference any paper or publication inside %s, use LaTeX style markup: %s.'), site_title(), '<code>\\ref{bibtex_id}</code>')?></li>
    <li><?php _e('JavaScript is not allowed on wiki pages.')?></li>
  </ul>
  <p><?php printf(__('The wiki allows additionally a subset of LaTeX commandos to be used. The recognized commandos are mostly text styles, like %s, or escape sequences, like %s.'), '<code>\\textbf{}</code>', sprintf('<code>\\ss</code> %1$s <code>ß</code> %2$s <code>\\alpha</code> %1$s <code>&alpha</code>', 'for', 'or')) ?></p>
</div>
