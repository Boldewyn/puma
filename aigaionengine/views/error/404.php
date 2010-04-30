<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<h2><?php _e('Error: Resource not found.')?></h2>

<p><?php printf(__('The resource %s could not be found (error 404).'), '<strong>'.h(base_url().$this->uri->uri_string()).'</strong>')?></p>
<p><?php _e('If this error continues to occur, please use the form at the end of this page to contact the site admin.')?></p>
<hr/>
<h3><?php _e('Search the site:')?></h3>
<form action="<?php echo base_url()?>search/quicksearch" method="post">
  <p>
    <input type="hidden" name="formname" value="simplesearch" />
    <input type="text" class="text" name="q" value="" />
    <input type="submit" name="submit_search" value="<?php _e('Search')?>" />
  </p>
</form>
