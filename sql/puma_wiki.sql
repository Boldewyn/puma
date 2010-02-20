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
  PRIMARY KEY ( `id` ),
  INDEX ( `item`(10) )
);
