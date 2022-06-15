CREATE TABLE  IF NOT EXISTS  `ToDoTasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `dateCompleted` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `edited` int(11) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;