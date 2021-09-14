SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `bookshelf` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bookshelf`;

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `user_id` varchar(64) NOT NULL,
  `address_id` int(11) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `author` varchar(64) NOT NULL,
  `category` int(32) UNSIGNED DEFAULT NULL,
  `state` varchar(64) NOT NULL,
  `price` char(8) NOT NULL,
  `available` enum('FREE','IN_CART','SOLD') NOT NULL,
  `owner` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `carted_books`;
CREATE TABLE `carted_books` (
  `book_id` int(32) NOT NULL,
  `user_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
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

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(32) UNSIGNED NOT NULL,
  `name` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ordered_books`;
CREATE TABLE `ordered_books` (
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `user_id` varchar(64) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (
  `payment_id` int(11) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `number` int(11) NOT NULL,
  `cvv` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `email` varchar(64) NOT NULL,
  `password` char(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_owner` (`owner`),
  ADD KEY `fk_category` (`category`);

ALTER TABLE `carted_books`
  ADD PRIMARY KEY (`book_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ordered_books`
  ADD PRIMARY KEY (`order_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);


ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `books`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categories`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);

ALTER TABLE `books`
  ADD CONSTRAINT `books_category_fk` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `carted_books`
  ADD CONSTRAINT `carted_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `carted_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

ALTER TABLE `ordered_books`
  ADD CONSTRAINT `ordered_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `ordered_books_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment_methods` (`payment_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);

ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
