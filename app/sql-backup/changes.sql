ALTER TABLE `user` CHANGE `date_registered` `time_registered` INT(10) UNSIGNED NOT NULL
ALTER TABLE `content` CHANGE `date_published` `time_published` INT(10) UNSIGNED NULL DEFAULT '0'