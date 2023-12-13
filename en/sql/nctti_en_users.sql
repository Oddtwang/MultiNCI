-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: db.inf.ufrgs.br
-- Generation Time: Jan 21, 2016 at 06:08 PM
-- Server version: 5.1.66
-- PHP Version: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mwe`
--

-- --------------------------------------------------------

--
-- Table structure for table `mturk_en_users`
--

-- `surname` varchar(50) CHARACTER SET latin1 NOT NULL,
-- `country` varchar(50) CHARACTER SET latin1 NOT NULL,
-- `age` varchar(10) CHARACTER SET latin1 NOT NULL

CREATE TABLE IF NOT EXISTS `nctti_en_users` (
  `anotador` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL  
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


