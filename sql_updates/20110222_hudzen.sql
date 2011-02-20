delete from `re_values_theme_rest` where id in (39, 40);

--
-- Dumping data for table `re_values_theme_rest`
--

INSERT INTO `re_values_theme_rest` (`id`, `id_spr`, `name`) VALUES
(39, 3, 'Аквапарк'),
(40, 3, 'Снегоход'),
(41, 3, 'Ночные клубы'),
(42, 3, 'Аквапарки'),
(43, 3, 'Морские прогулки'),
(44, 3, 'Экскурсии');



delete from `re_entertaiment`;
--
-- Dumping data for table `re_entertaiment`
--

INSERT INTO `re_entertaiment` (`id`, `language_id`, `sequence`, `caption`, `image`, `content`, `country_id`, `region_id`, `city_id`, `type_id`) VALUES
(1, 2, 1, 'Развлечение 1', '1298359313_img1.jpg', '<p>Это развлечение 1. </p><p>Тут будет много текста и картинок.<br></p>', 1, 1, 1, 39),
(2, 2, 2, 'Развлечение 2', '1298359430_img1.jpg', 'Это развлечение 2<br>', 1, 1, 1, 43);
