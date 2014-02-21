CREATE TABLE `log_admin_unseen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
