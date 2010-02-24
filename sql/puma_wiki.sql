-- 
-- Wiki model and controller
-- 

CREATE TABLE `puma_wiki_active` (
  `id` INT( 10 ) NOT NULL ,
  `item` VARCHAR( 255 ) NOT NULL ,
  PRIMARY KEY ( `id` , `item` ),
  INDEX( `item`(10) )
);

CREATE TABLE `puma_wiki_pages` (
  `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `item` VARCHAR( 255 ) NOT NULL ,
  `content` MEDIUMTEXT NOT NULL ,
  `original_content` MEDIUMTEXT NOT NULL ,
  `description` VARCHAR( 255 ) NOT NULL,
  `editor` INT( 10 ) NOT NULL ,
  `replaces` INT( 10 ) NULL DEFAULT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `read_access_level` ENUM( 'private', 'public', 'intern', 'group' ) NOT NULL DEFAULT 'intern' ,
  `edit_access_level` ENUM( 'private', 'public', 'intern', 'group' ) NOT NULL DEFAULT 'intern' ,
  PRIMARY KEY ( `id` ),
  INDEX ( `item`(10) )
);

INSERT INTO `puma_availablerights`
( `name` , `description` )
VALUES
( 'wiki_read', 'read wiki entries' ),
( 'wiki_edit', 'edit, change and revert wiki entries' );

INSERT INTO `puma_rightsprofilerightlink`
(`rightsprofile_id`, `right_name`)
VALUES
('2', 'wiki_edit'),
('3', 'wiki_read');

INSERT INTO `puma_userrights`
(`user_id`, `right_name`)
VALUES
('1', 'wiki_edit'),
('1', 'wiki_read');
