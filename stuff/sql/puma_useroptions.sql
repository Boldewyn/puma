-- 
-- Table for single users' options
-- 

CREATE TABLE `puma_useroptions` (
  `user_id` INT( 10 ) NOT NULL ,
  `key` VARCHAR( 255 ) NOT NULL ,
  `value` MEDIUMTEXT NOT NULL
);

ALTER TABLE `puma_useroptions` ADD PRIMARY KEY ( `user_id` , `key` );
