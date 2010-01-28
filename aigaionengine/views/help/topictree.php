<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2>The Topic Tree</h2>
  <p>Click on a topic to read its description and find all <?php _a('help/publicationlists', __('papers')) ?> that are attached to it. A paper can be attached to multiple topics. If you want to find for example the publications that are part of two topics, you can use the search interface from the menu.</p>
  <div class='message'>
    <?php _icon("tree_min") ?>&nbsp;<a href="#" title="Go to topic First Topic">First Topic</a> <br/>
    &nbsp;&nbsp;&nbsp;<?php _icon("tree_blank") ?>&nbsp;<a href="#" title="Go to topic Child of First Topic">Child of First Topic</a><br/>
    <?php _icon("tree_blank") ?>&nbsp;<a href="#" title="Go to topic Second Topic">Second Topic</a><br/>
  </div>
  <p class="caption">An example topic tree fragment</p>
  <h3>Icons in the topic tree and their functions.</h3>
    <ul>
      <li><?php _icon("tree_plus") ?> Expand the topic in the tree view.</li>
      <li><?php _icon("tree_min") ?> Collapse the topic in the tree view.</li>
    </ul>
  <h3>Subscribing for topics.</h3>
  <p>It may be that not every user of a certain Aigaion database is interested in exactly the same topics. Therefore Aigaion contains a mechanism that allows you to subscribe to - or unsubscribe from - any topic, meaning that you can determine which topics you will see while browsing the system.
  By default, a new user will not be subscribed to any topic. To review which topics already exist in this copy of Aigaion, go to the <?php _a('users/topicreview', __('topic review page')); ?> and subscribe to any topic that you are interested in. Those topics will then appear in your topic tree.</p>
</div>
