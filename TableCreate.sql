CREATE TABLE IF NOT EXISTS `petpic` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` smallint(3) NOT NULL,
  `hit` bigint(20) NOT NULL,
  `xSize` mediumint(9) NOT NULL,
  `ySize` mediumint(9) NOT NULL,
  `typeSize` smallint(6) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `url` text NOT NULL,
  `fromSite` text,
  `tag` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
