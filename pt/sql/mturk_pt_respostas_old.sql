-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: db.inf.ufrgs.br
-- Generation Time: Jan 21, 2016 at 02:18 PM
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
-- Table structure for table `mturk_pt_respostas`
--

CREATE TABLE IF NOT EXISTS `mturk_pt_respostas` (
  `idMWE` int(11) NOT NULL,
  `idSent` int(11) NOT NULL,
  `anotador` varchar(500) NOT NULL,
  `resp1` int(11) NOT NULL,
  `resp2` int(11) NOT NULL,
  `resp3` int(11) NOT NULL,
  `comments` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mturk_pt_respostas`
--

INSERT INTO `mturk_pt_respostas` (`idMWE`, `idSent`, `anotador`, `resp1`, `resp2`, `resp3`, `comments`) VALUES
(3, 3, 'user1', -1, -1, -1, 'pulou'),
(3, 3, 'user1', -1, -1, -1, 'pulou'),
(13, 13, 'user1', -1, -1, -1, 'pulou'),
(36, 36, 'user1', -1, -1, -1, 'pulou'),
(87, 87, 'user1', -1, -1, -1, 'pulou'),
(32, 32, 'user1', -1, -1, -1, 'pulou'),
(77, 77, 'user1', -1, -1, -1, 'pulou'),
(31, 31, 'user1', -1, -1, -1, 'pulou'),
(7, 7, 'user1', -1, -1, -1, 'pulou'),
(117, 117, 'user1', -1, -1, -1, 'pulou'),
(101, 101, 'user1', -1, -1, -1, 'pulou'),
(146, 146, 'user1', -1, -1, -1, 'pulou'),
(41, 41, 'user1', 1, 1, 2, ''),
(22, 22, 'user1', 5, 4, 3, '');
