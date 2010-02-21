<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
?>
<h2><?php _h($keyword->keyword) ?></h2>
<?php 
    if ($userlogin->hasRights('bookmarklist')) {
      ?><p class="optionbox"><?php
        _a('bookmarklist/addkeyword/'.$keyword->keyword_id, '['.__('BookmarkAll').']').' '.
        _a('bookmarklist/removekeyword/'.$keyword->keyword_id, '['.__('UnBookmarkAll').']');
      ?></p><?php
    }
?>
