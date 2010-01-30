-- puma statistics table

CREATE TABLE `puma_statistics` (
  `user` VARCHAR( 255 ) NOT NULL ,
  `with` VARCHAR( 255 ) NOT NULL ,
  `speaking` VARCHAR( 255 ) NOT NULL DEFAULT 'en',
  `wants` VARCHAR( 255 ) NOT NULL ,
  `at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
