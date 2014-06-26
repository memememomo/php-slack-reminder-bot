CREATE TABLE `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text,
  `remind_at` DATETIME,
  PRIMARY KEY (`id`),
  INDEX remind_at_idx(`remind_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
