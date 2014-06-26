CREATE TABLE `task_daily` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text,
  `remind_hour` int(11),
  `remind_min` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
