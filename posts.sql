-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: May 17, 2014 at 09:44 AM
-- Server version: 5.5.34
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `polytech_abroad`
--

-- --------------------------------------------------------

--
-- Table structure for table `POLY_INT_posts`
--

CREATE TABLE `POLY_INT_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `body` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `POLY_INT_posts`
--

INSERT INTO `POLY_INT_posts` (`id`, `title`, `body`, `created`, `modified`, `user_id`) VALUES
(1, 'The title', 'This is the post body.', '2014-05-13 20:47:16', NULL, 3),
(2, 'A title once again', 'And the post body follows.', '2014-05-13 20:47:16', NULL, 3),
(3, 'Title strikes back', 'This is really exciting! Not.', '2014-05-13 20:47:16', NULL, 3);
