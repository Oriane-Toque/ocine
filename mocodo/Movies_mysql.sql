CREATE DATABASE IF NOT EXISTS `MOVIES` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `MOVIES`;

CREATE TABLE `USER` (
  `email` VARCHAR(42),
  `username` VARCHAR(42),
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `REVIEWS` (
  `email` VARCHAR(42),
  `isan` VARCHAR(42),
  `comment` VARCHAR(42),
  `rating` VARCHAR(42),
  PRIMARY KEY (`email`, `isan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BELONGS_TO` (
  `isan` VARCHAR(42),
  `genre_code` VARCHAR(42),
  PRIMARY KEY (`isan`, `genre_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `MOVIE` (
  `isan` VARCHAR(42),
  `title` VARCHAR(42),
  `release_date` VARCHAR(42),
  PRIMARY KEY (`isan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `GENRE` (
  `genre_code` VARCHAR(42),
  `name` VARCHAR(42),
  PRIMARY KEY (`genre_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `PLAYS` (
  `actor_code` VARCHAR(42),
  `isan` VARCHAR(42),
  `role` VARCHAR(42),
  PRIMARY KEY (`actor_code`, `isan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ACTOR` (
  `actor_code` VARCHAR(42),
  `firstname` VARCHAR(42),
  `lastname` VARCHAR(42),
  PRIMARY KEY (`actor_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `REVIEWS` ADD FOREIGN KEY (`isan`) REFERENCES `MOVIE` (`isan`);
ALTER TABLE `REVIEWS` ADD FOREIGN KEY (`email`) REFERENCES `USER` (`email`);
ALTER TABLE `BELONGS_TO` ADD FOREIGN KEY (`genre_code`) REFERENCES `GENRE` (`genre_code`);
ALTER TABLE `BELONGS_TO` ADD FOREIGN KEY (`isan`) REFERENCES `MOVIE` (`isan`);
ALTER TABLE `PLAYS` ADD FOREIGN KEY (`isan`) REFERENCES `MOVIE` (`isan`);
ALTER TABLE `PLAYS` ADD FOREIGN KEY (`actor_code`) REFERENCES `ACTOR` (`actor_code`);