-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 10, 2022 alle 23:48
-- Versione del server: 10.4.14-MariaDB
-- Versione PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookshelf`
--
CREATE DATABASE IF NOT EXISTS `bookshelf` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bookshelf`;

-- --------------------------------------------------------

--
-- Struttura della tabella `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `user_id` varchar(64) NOT NULL,
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `author` varchar(64) NOT NULL,
  `category` int(32) UNSIGNED DEFAULT NULL,
  `state` varchar(64) NOT NULL,
  `price` char(8) NOT NULL,
  `available` enum('FREE','IN_CART','SOLD') NOT NULL,
  `image` char(255) NOT NULL,
  `owner` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_owner` (`owner`),
  KEY `fk_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `carted_books`
--

DROP TABLE IF EXISTS `carted_books`;
CREATE TABLE IF NOT EXISTS `carted_books` (
  `book_id` int(32) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  PRIMARY KEY (`book_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `carted_books`
--
DROP TRIGGER IF EXISTS `set_book_availability`;
DELIMITER $$
CREATE TRIGGER `set_book_availability` AFTER INSERT ON `carted_books` FOR EACH ROW UPDATE books SET available = 'IN_CART' WHERE id = NEW.book_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_book_availability`;
DELIMITER $$
CREATE TRIGGER `update_book_availability` AFTER DELETE ON `carted_books` FOR EACH ROW IF ((SELECT COUNT(*) FROM books WHERE id = OLD.book_id) = 0) THEN
	UPDATE books SET availabiliy = 'FREE' WHERE id = OLD.book_id; 
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `user` varchar(64) NOT NULL,
  `from_user` varchar(64) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(32) NOT NULL,
  `order_state` enum('WAITING','SENT','RECEIVED','CANCELED') NOT NULL,
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `seen` tinyint(4) NOT NULL DEFAULT 0,
  `created_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user`),
  KEY `fk_from` (`from_user`),
  KEY `idx_timestamp` (`created_timestamp`),
  KEY `fk_order` (`order_id`),
  KEY `fk_book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordered_books`
--

DROP TABLE IF EXISTS `ordered_books`;
CREATE TABLE IF NOT EXISTS `ordered_books` (
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `advancement` enum('WAITING','SENT','RECEIVED') NOT NULL DEFAULT 'WAITING',
  PRIMARY KEY (`order_id`,`book_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `ordered_books`
--
DROP TRIGGER IF EXISTS `remove_soldbooksfromcart`;
DELIMITER $$
CREATE TRIGGER `remove_soldbooksfromcart` AFTER INSERT ON `ordered_books` FOR EACH ROW DELETE FROM carted_books WHERE book_id = NEW.book_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `set_book_available_after_order_cancel`;
DELIMITER $$
CREATE TRIGGER `set_book_available_after_order_cancel` AFTER DELETE ON `ordered_books` FOR EACH ROW UPDATE books SET books.available = 'FREE' WHERE books.id = OLD.book_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `set_book_sold`;
DELIMITER $$
CREATE TRIGGER `set_book_sold` AFTER INSERT ON `ordered_books` FOR EACH ROW UPDATE books SET available = 'SOLD' WHERE id = NEW.book_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `user_id` varchar(64) NOT NULL,
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `payment_id` (`payment_id`),
  KEY `address_id` (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `payment_id` int(11) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `number` int(11) NOT NULL,
  `cvv` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(64) NOT NULL,
  `password` char(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);

--
-- Limiti per la tabella `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_category_fk` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `carted_books`
--
ALTER TABLE `carted_books`
  ADD CONSTRAINT `carted_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `carted_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Limiti per la tabella `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_book` FOREIGN KEY (`book_id`) REFERENCES `ordered_books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_from` FOREIGN KEY (`from_user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `ordered_books` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordered_books`
--
ALTER TABLE `ordered_books`
  ADD CONSTRAINT `ordered_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `ordered_books_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Limiti per la tabella `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment_methods` (`payment_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);

--
-- Limiti per la tabella `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
