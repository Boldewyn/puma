<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2>Access rights on different levels</h2>
  <p>Aigaion users can be anonymous or not, can be assigned to different groups, and can be assigned different user rights. This makes it possible to restrict access to certain objects to a limited set of users. This restriction can apply to topics, publications, notes and attachments, and can be set separately for <i>reading</i> and for <em>editing</em> them.</p>

  <h3>What access levels are there?</h3>
  <p>For each of the above mentioned objects one can set a <em>read access level</em> and an <em>edit access level</em>. These levels can be:</p>
  <ul>
    <li>'public' (object can be read or edited by everyone including anonymous users),</li>
    <li>'intern' (object can be read or edited by all non-anonymous users),</li>
    <li>'private' (object can be read or edited by the owner only)</li>
  </ul>

  <h3>What user rights are involved?</h3>
  <p>There are two types of user rights that also influence reading and editing access. The first are the normal read and edit rights. For example, a user who has no 'topic_edit' rights cannot edit a topic, even if all access levels of that topic are set to 'public'. The second are the 'override rights'. A user who has for example the right 'topic_read_all' can read every topic, even if he is not the owner and the access levels are set to 'private'.</p>

  <h3>Who can change the access level of an object?</h3>
  <p>The access levels can normally be changed only by the owner. However, users who have the appropriate override rights for editing (see above) can also change the access levels of an object.</p>
</div>
