-- --------------------------------------------------------

--
-- Table structure for table `re_entertaiment`
--

CREATE TABLE IF NOT EXISTS `re_entertaiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `re_entertaiment`
--

INSERT INTO `re_entertaiment` (`id`, `language_id`, `sequence`, `caption`, `image`, `content`, `country_id`, `region_id`, `city_id`, `type_id`) VALUES
(1, 2, 1, '434343', 'X:/home/rest/www/uploades/entertaiments/pict.jpg', '<p>423424</p><p>rere</p><p>tret</p><p>retret</p><p>ertret <br></p>', 1, 0, 0, 39),
(2, 2, 2, 'erewrwer', '/uploades/entertaiments/img1.jpg', 'rewrwerewr', 1, 0, 0, 40),
(3, 2, 3, 'rewrew', '', 'rewrewrxwxwxwxwxw', 0, 0, 0, 39),
(6, 2, 4, 'fsdfds', '', '<p>fsdfdsf</p><p>csacsac</p><p>csacsa <br></p>', 1, 1, 1, 39),
(7, 2, 5, 'vxvcv', '', 'dsvdsvdvdsvdsvdsv dd<br>', 1, 1, 1, 40);

--
-- Dumping data for table `re_spr_theme_rest`
--

INSERT INTO `re_spr_theme_rest` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(3, 'Развлечения', 3, '2', '2', '1');

-- --------------------------------------------------------
--
-- Dumping data for table `re_values_theme_rest`
--

INSERT INTO `re_values_theme_rest` (`id`, `id_spr`, `name`) VALUES
(39, 3, 'Аквапарк'),
(40, 3, 'Снегоход');
