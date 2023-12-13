-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: db.inf.ufrgs.br
-- Generation Time: Jan 21, 2016 at 02:17 PM
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
-- Table structure for table `nctti_en_mwes`
--

CREATE TABLE IF NOT EXISTS `nctti_ro_mwes` (
  `id` int(11) NOT NULL,
  `compound` varchar(250) NOT NULL,
  `source` varchar(100) NOT NULL,      
  `a_modif` varchar(100) NOT NULL,  
  `modifier` varchar(100) NOT NULL,

  `modifier_2_N_idf` varchar(100) NOT NULL,
  `modifier_2` varchar(100) NOT NULL,
  `modifier_3` varchar(100) NOT NULL,

  `modif_pos` varchar(100) NOT NULL,  
  `a_head` varchar(100) NOT NULL,
  `head` varchar(100) NOT NULL,  
  `number` varchar(100) NOT NULL,    
  `comp` varchar(4) NOT NULL,
  `frequkWaC` varchar(20) NOT NULL,  
  `examplesent1` text NOT NULL,
  `examplesent2` text NOT NULL,
  `examplesent3` text NOT NULL,  
  `be` varchar(20) NOT NULL,
  `have` varchar(20) NOT NULL,
  `undefdet_head` varchar(100) NOT NULL,
  `undefdet_compound` varchar(100) NOT NULL,
  
  `possessive_article` varchar(100) NOT NULL,
  
  `relatedto_modifier` varchar(100) NOT NULL,
  `something_modifier` varchar(100) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

