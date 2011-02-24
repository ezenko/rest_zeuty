ALTER TABLE `re_user_rent_pays` ADD `offer_type` ENUM( 'sell', 'rent' ) NULL ,
ADD `floor` TINYINT NULL ,
ADD `floors` TINYINT NULL ,
ADD `min_flats_square` FLOAT( 6, 2 ) NULL ,
ADD `max_flats_square` FLOAT( 6, 2 ) NULL ,
ADD `total_square` FLOAT( 8, 2 ) NULL ,
ADD `ceil_heigh` FLOAT( 4, 2 ) NULL ,
ADD `sea_distance` TEXT NULL ,
ADD `term` VARCHAR( 255 ) NULL ,
ADD `investor` VARCHAR( 255 ) NULL ,
ADD `parking` TEXT NULL ;
ALTER TABLE `re_user_rent_pays` CHANGE `ceil_heigh` `ceil_height` FLOAT( 4, 2 ) NULL DEFAULT NULL;