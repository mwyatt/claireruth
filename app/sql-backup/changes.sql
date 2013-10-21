CREATE TABLE IF NOT EXISTS `content_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;