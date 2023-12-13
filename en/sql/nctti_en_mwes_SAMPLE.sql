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
-- Table structure for table `mturk_en_mwes`
--

CREATE TABLE IF NOT EXISTS `nctti_en_mwes` (
  `id` int(11) NOT NULL,
  `compound` varchar(250) NOT NULL,
  `source` varchar(100) NOT NULL,      
  `a_modif` varchar(100) NOT NULL,  
  `modifier` varchar(100) NOT NULL,  
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
  `relatedto_modifier` varchar(100) NOT NULL,
  `something_modifier` varchar(100) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (5,"travel guide","NEW","a","travel","N","a","guide","S","C","3926","I did research, I revised a <b>travel guide</b> on Salzburg, I did some creative writing, and I also spent some time doing nothing.","Map and travel bookshop, Stanfords has a comprehensive range of world maps, <b>travel guides</b> and books.","A description often used for Antwerp in <b>travel guides</b> for backpackers.","is","has","a","a","related to","<!--X-->");
INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (29,"biological clock","NEW","a","biological","J","a","clock","S","C","467","The <b>biological clock</b> ticks — but slowly It is not so bad to delay having a first baby until your thirties, according to Professor John Mirowsky.","Closely allied to these theories is that of the <b>biological clock</b> with triggers at certain stages of development for the ageing process","In mammals, the <b>biological clock</b> is controlled from the suprachaismatic nucleus (SCN), an area of the brain in the hypothalamus.","is","has","a","a","<!--X-->","something");
INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (90,"bad apple","NEW","a","bad","J","an","apple","S","PC","337","The problem for them, of course, is how to explain how these few <b>bad apples</b> managed to stay in place for so many years.","However it's the same old story: a few <b>bad apples</b> and we all get it in the neck!","However, it will not work unless every single person does it, because one <b>bad apple</b> ruins the whole barrel.","is","has","an","a","<!--X-->","something");
INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (62,"big wig","NEW","a","big","J","a","wig","S","NC","137","To secure promotion you have to impress your employers, the suits on high, the <b>big wigs</b>, and the fat cats.","The popstar turned DJ has been booked as a panellist on the BBC's Question Time, a slot more usually reserved for political <b>big wigs</b> and the like.","He was married three times, was a mason, a championship level clay pigeon shooter and a <b>big wig</b> in the local tory party.","is","has","a","a","<!--X-->","something");
INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (48,"brain drain","NEW","a","brain","N","a","drain","S","NC","618","This <b>brain drain</b> exacerbates the situation for those staying behind, as it leads to a loss of skills which are then not to be passed on to the next generation.","A flow of scientists in both directions between the US and Europe would make the perceived \" <b>brain drain</b> \" of researchers to the former much less of a problem.","We are suffering a chronic \" <b>brain drain</b> \" as qualified people leave for greener pastures abroad.","is","has","a","a","related to","<!--X-->");
INSERT INTO nctti_en_mwes(id, compound,source,a_modif,modifier,modif_pos,a_head,head,number,comp,frequkWaC,examplesent1,examplesent2,examplesent3,be,have,undefdet_head,undefdet_compound,relatedto_modifier,something_modifier) VALUES (82,"ghost town","NEW","a","ghost","N","a","town","S","PC","1013","The town centre is now deserted - it's almost like a <b>ghost town</b>!","Dilapidated Virginia City of old silver boom days is determined not to become a <b>ghost town</b>.","With the mines closing down in the early 1990's it tumbled into the darkness of a small quiet <b>ghost town</b>.","is","has","a","a","related to","<!--X-->");
