<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<p>
  <?php
    _a('groups/edit/'.$group->group_id, '['.__('edit').']'); echo ' ';
    _a('groups/delete/'.$group->group_id, '['.__('delete').']'); echo ' ';
    $userlogin = getUserLogin();
    if ($userlogin->hasRights('topic_subscription')):
      _a('groups/topicreview/'.$group->group_id, '['.__('topic subscription').']'); echo ' ';
    endif;
    _h($group->name);
  ?>
</p>
