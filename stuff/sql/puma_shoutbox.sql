-- puma shoutbox


CREATE TABLE `puma_shoutbox` (
  `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT( 10 ) NOT NULL ,
  `at` INT( 10 ) NULL DEFAULT NULL ,
  `content` MEDIUMTEXT NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY ( `id` )
);



