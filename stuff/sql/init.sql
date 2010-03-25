
ALTER TABLE `puma_users` ADD COLUMN modified TIMESTAMP NOT NULL;

INSERT INTO `puma_grouprightsprofilelink` VALUES (7,5);
INSERT INTO `puma_rightsprofilerightlink` VALUES (5,'attachment_edit'),(5,'attachment_read'),(5,'bookmarklist'),
                                                 (5,'export_email'),(5,'note_edit'),(5,'note_read'),(5,'publication_edit'),
                                                 (5,'request_copies'),(5,'topic_edit'),(5,'topic_subscription'),
                                                 (5,'user_edit_self');
INSERT INTO `puma_rightsprofiles` VALUES (5,'faculty');

--  `topic_id` int(10) NOT NULL auto_increment,
--  `name` varchar(255) default NULL,
--  `cleanname` varchar(255) default NULL,
--  `description` mediumtext,
--  `url` varchar(255) NOT NULL default '',
--  `user_id` int(10) unsigned NOT NULL default '0',
--  `read_access_level` enum('private','public','intern','group') NOT NULL default 'intern',
--  `edit_access_level` enum('private','public','intern','group') NOT NULL default 'intern',
--  `group_id` int(10) unsigned NOT NULL default '0',
--  `derived_read_access_level` enum('private','public','intern','group') NOT NULL default 'intern',
--  `derived_edit_access_level` enum('private','public','intern','group') NOT NULL default 'intern',
-- INSERT INTO `puma_topics` VALUES (1,'Top','','No description. This topic is in itself not relevant, it is just a \'topmost parent\' for the topic hierarchy.','',0,'public','intern',0,'public','intern');
-- INSERT INTO `puma_topictopiclink` VALUES ('source', 'target');

INSERT INTO `puma_usergrouplink` VALUES (6,5);

INSERT INTO `puma_users`
     VALUES
          (6,'puma','TRUE', 'FALSE','default','default',0,'guest','','',  '',  '',  '',       NULL,'',       '',  0,0,'anon', 0,'FALSE','FALSE','default','default'),
          (7,'puma','FALSE','FALSE','default','default',0,'',     '',NULL,NULL,NULL,'ndsuser',NULL,'ndsuser',NULL,0,1,'group',0,'TRUE', 'FALSE','default','default');

INSERT INTO `puma_usertopiclink` VALUES (0,6,1,0),(0,7,1,0);

UPDATE `puma_config` SET `value` = 'Manuel Strehl' WHERE `setting` = 'CFG_ADMIN';
UPDATE `puma_config` SET `value` = 'manuel.strehl@physik.uni-regensburg.de' WHERE `setting` = 'CFG_ADMINMAIL';
UPDATE `puma_config` SET `value` = 'A Publication Management System' WHERE `setting` = 'WINDOW_TITLE';
UPDATE `puma_config` SET `value` = 'TRUE' WHERE `setting` = 'SHOW_TOPICS_ON_FRONTPAGE';
UPDATE `puma_config` SET `value` = 'TRUE' WHERE `setting` = 'LOGIN_ENABLE_ANON';
UPDATE `puma_config` SET `value` = '6'    WHERE `setting` = 'LOGIN_DEFAULT_ANON';
UPDATE `puma_config` SET `value` = 'puma' WHERE `setting` = 'DEFAULTPREF_THEME';
UPDATE `puma_config` SET `value` = 'de'   WHERE `setting` = 'DEFAULTPREF_LANGUAGE';
UPDATE `puma_config` SET `value` = 'TRUE' WHERE `setting` = 'LOGIN_CREATE_MISSING_USER';
UPDATE `puma_config` SET `value` = 'TRUE' WHERE `setting` = 'ENABLE_TINYMCE';
UPDATE `puma_config` SET `value` =  'il'  WHERE `setting` = 'DEFAULTPREF_SIMILAR_AUTHOR_TEST';
