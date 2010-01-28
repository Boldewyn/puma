<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2><?php _e('Access rights on different levels')?></h2>
  <p><?php _e('The users can be anonymous or not, can be assigned to different groups, and can be assigned different user rights. This makes it possible to restrict access to certain objects to a limited set of users. This restriction can apply to topics, publications, notes and attachments, and can be set separately for <em>reading</em> and for <em>editing</em> them.')?></p>

  <h3><?php _e('What access levels are there?')?></h3>
  <p><?php _e('For each of the above mentioned objects one can set a <em>read access level</em> and an <em>edit access level</em>. These levels can be:')?></p>
  <ul>
    <li><?php _e('&lsquo;public&rsquo; (object can be read or edited by everyone including anonymous users),')?></li>
    <li><?php _e('&lsquo;intern&rsquo; (object can be read or edited by all non-anonymous users),')?></li>
    <li><?php _e('&lsquo;private&rsquo; (object can be read or edited by the owner only)')?></li>
  </ul>

  <h3><?php _e('What user rights are involved?')?></h3>
  <p><?php _e('There are two types of user rights that also influence reading and editing access. The first are the normal read and edit rights. For example, a user who has no <em>topic edit</em> rights cannot edit a topic, even if all access levels of that topic are set to &lsquo;public&rsquo;. The second are the <em>override rights</em>. A user who has for example the right <em>topic read all</em> can read every topic, even if he is not the owner and the access levels are set to &lsquo;private&rsquo;.')?></p>

  <h3><?php _e('Who can change the access level of an object?')?></h3>
  <p><?php _e('The access levels can normally be changed only by the owner. However, users who have the appropriate override rights for editing (see above) can also change the access levels of an object.')?></p>
</div>
