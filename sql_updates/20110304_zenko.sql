ALTER TABLE `re_user_rent_pays` ADD `days` VARCHAR( 255 ) NULL ,
ADD `hotel` VARCHAR( 255 ) NULL ,
ADD `route` TEXT NULL ,
ADD `facilities` VARCHAR( 255 ) NULL ,
ADD `meals` VARCHAR( 255 ) NULL ;
ALTER TABLE `re_user_rent_pays` ADD `is_hot` ENUM( '1', '0' ) NOT NULL DEFAULT '0';