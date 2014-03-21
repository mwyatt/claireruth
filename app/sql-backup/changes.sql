CREATE TABLE `log_admin_unseen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(75) NOT NULL,
  `from` varchar(75) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;