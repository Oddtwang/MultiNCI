-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: db.inf.ufrgs.br
-- Generation Time: Jan 21, 2016 at 02:19 PM
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
-- Table structure for table `mturk_pt_anotacao`
--

CREATE TABLE IF NOT EXISTS `mturk_pt_anotacao` (
  `idmwe` int(11) NOT NULL,
  `idsent` int(11) NOT NULL,
  `idanno` varchar(500) NOT NULL,
  `word` varchar(500) NOT NULL,
  PRIMARY KEY (`idmwe`,`idsent`,`idanno`,`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mturk_pt_anotacao`
--

INSERT INTO `mturk_pt_anotacao` (`idmwe`, `idsent`, `idanno`, `word`) VALUES
(18, 18, 'user1', 'ss'),
(22, 22, 'user1', 'aa'),
(22, 22, 'user1', 'sss'),
(41, 41, 'user1', 'ddd'),
(41, 41, 'user1', 'ss'),
(158, 158, 'user1', 'aa'),
(158, 158, 'user1', 'sss');
