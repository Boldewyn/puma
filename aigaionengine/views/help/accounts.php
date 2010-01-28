<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2>Groups, user accounts and rights profiles</h2>
  <p>Access to the Aigaion database is possible through logging in with a user account, using the Aigaion login forms or some external module using e.g. LDAP or some CMS for authentication. Individual users can have certain access rights assigned to them. Individual users can also be assigned to groups. Groups facilitate group-defined topic subscriptions, quick assignment of default right profiles and restriction of read and write access for certain notes, publications, attachments or topics to a subset of users.</p>

  <h3>Group topic subscriptions</h3>
  <p>Users with sufficient rights ('user_edit_all') can subscribe a group to certain topics from the 'manage accounts' page. In that case all users that belong to that group will be counted 'subscribed' to that topic, no matter whether they were individually subscribed or not.</p>

  <h3>Default rights profiles for groups</h3>
  <p>Each group can be associated with one or more <i>rights profiles</i>, collections of user rights. This association has no influence at all on the user rights considered to be assigned to the users currently belonging to the group. However, whenever you newly assign a user to a group, that user will immediately also receive all user rights from the rights profiles associated to the group. This helps in quickly establishing default rights for users in certain groups.</p>

  <h3>Access levels</h3>
  <p>See <?php _a('help/accessrights', __('the section about access rights')); ?> for more information about access levels.</p>

  <h3>External login modules</h3>
  <p>Still to be documented. See also explanation on the site configuration page. Allows login to be controlled through an external system such as LDAP, .htpasswd files or some CMS login state.</p>
</div>
