ALTER TABLE  `re_rent_ads` ADD  `parent_id` INT UNSIGNED ZEROFILL NOT NULL AFTER  `id` ,
ADD INDEX (  `parent_id` );
ALTER TABLE `re_user_rent_pays` ADD `furniture` TEXT NULL ;
CREATE TABLE IF NOT EXISTS `re_spr_rent_theme_rest_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `re_spr_theme_rest` CHANGE `visible_in` `visible_in` ENUM( '1', '2', '3', '4' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 