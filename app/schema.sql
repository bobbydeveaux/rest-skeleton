

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

/* password123 */
INSERT INTO `users` (`id`, `username`, `password`, `email`)
VALUES
    (1,'bobby','$2y$11$LHKx9.Ax9cn4vvEdBPNy/OLF4RPhjVyWPW5zOrjGl/qWLpwjdHXWi','bobby@dvomedia.net');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

