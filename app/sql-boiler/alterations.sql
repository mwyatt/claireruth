ALTER TABLE `main_media`  ADD `description` TEXT NOT NULL AFTER `title`

CREATE TABLE IF NOT EXISTS `main_media_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;