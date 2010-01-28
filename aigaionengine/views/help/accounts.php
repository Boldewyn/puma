<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2><?php _e('Groups, user accounts and rights profiles')?></h2>
  <p><?php _e('Access to the database is possible through logging in with a NDS or a guest user account. Individual users can have certain access rights assigned to them. Individual users can also be assigned to groups. Groups facilitate group-defined topic subscriptions, quick assignment of default right profiles and restriction of read and write access for certain notes, publications, attachments or topics to a subset of users.')?></p>

  <h3><?php _e('Group topic subscriptions')?></h3>
  <p><?php _e('Users with sufficient rights (&lsquo;user_edit_all&rsquo;) can subscribe a group to certain topics from the &lsquo;manage accounts&rsquo; page. In that case all users that belong to that group will be counted &lsquo;subscribed&rsquo; to that topic, no matter whether they were individually subscribed or not.')?></p>

  <h3><?php _e('Default rights profiles for groups')?></h3>
  <p><?php _e('Each group can be associated with one or more <em>rights profiles</em>, collections of user rights. This association has no influence at all on the user rights considered to be assigned to the users currently belonging to the group. However, whenever you newly assign a user to a group, that user will immediately also receive all user rights from the rights profiles associated to the group. This helps in quickly establishing default rights for users in certain groups.')?></p>

  <h3><?php _e('Access levels')?></h3>
  <p><?php _e('See %s for more information about access levels.', anchor('help/accessrights', __('the section about access rights')))?></p>
</div>
