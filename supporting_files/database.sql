CREATE DATABASE IF NOT EXISTS `floppier`;
USE `floppier`;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `salt`) VALUES
	(8, 'admin', '', ''), (24, 'madales', '', '');


CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(127) NOT NULL,
  `size` int(11) NOT NULL,
  `integrity` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `files` (`id`, `owner`, `name`, `type`, `size`, `integrity`) VALUES
	(56, 8, 'secret.txt', 'text/plain', 158, '40a4f19c6f6c189a8beeed84a499b2bc'),
	(99, 24, 'boss.jpg', 'image/jpeg', 77727, '4f731edd3d2cb84d6eae64f235daab2b');

