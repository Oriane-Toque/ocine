-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `casting`;
CREATE TABLE `casting` (
  `movie_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `role` varchar(100) NOT NULL,
  `credit_order` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `casting` (`movie_id`, `person_id`, `role`, `credit_order`) VALUES
(1,	1,	'Atchoum',	1),
(1,	2,	'Babar',	3),
(1,	3,	'Le T-1000',	2),
(2,	2,	'James Notch',	2),
(2,	3,	'Alfonsine',	1);

DROP TABLE IF EXISTS `genre`;
CREATE TABLE `genre` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `genre` (`id`, `name`) VALUES
(1,	'Comédie'),
(2,	'Thriller'),
(3,	'Fantastique'),
(4,	'Aventure'),
(5,	'Science-fiction');

DROP TABLE IF EXISTS `movie`;
CREATE TABLE `movie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `release_date` date NOT NULL,
  `duration` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `movie` (`id`, `title`, `release_date`, `duration`) VALUES
(1,	'Avatar',	'2012-12-21',	180),
(2,	'Terminator 2',	'1992-01-01',	140),
(3,	'Camping 8',	'2027-03-12',	120);

DROP TABLE IF EXISTS `movie_genre`;
CREATE TABLE `movie_genre` (
  `movie_id` int(10) unsigned NOT NULL,
  `genre_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`movie_id`,`genre_id`),
  KEY `genre_id` (`genre_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `movie_genre_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  CONSTRAINT `movie_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `movie_genre` (`movie_id`, `genre_id`) VALUES
(1,	1),
(1,	2),
(2,	2),
(2,	3),
(3,	4),
(3,	5);

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `birth_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `person` (`id`, `firstname`, `lastname`, `birth_date`) VALUES
(1,	'Audrey',	'Tautou',	'2020-12-09 14:22:50'),
(2,	'Jean-Paul',	'Belmondo',	'2020-12-09 14:23:00'),
(3,	'Elijah',	'Wood',	'2020-12-09 14:23:21');

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `published_date` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `movie_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `review` (`id`, `content`, `rating`, `published_date`, `user_id`, `movie_id`) VALUES
(1,	'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.',	3,	'2020-12-09 14:26:12',	1,	1),
(2,	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.',	3,	'2020-12-09 14:26:33',	2,	1),
(3,	'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.',	4,	'2020-12-09 14:26:52',	3,	2);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`id`, `email`, `nickname`) VALUES
(1,	'toto@toto.com',	'André'),
(2,	'titi@titi.com',	'Christophe'),
(3,	'tata@tata.com',	'Pauline');

-- 2020-12-09 13:27:27
