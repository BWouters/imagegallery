-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Machine: db.visionsandviews.net
-- Genereertijd: 15 Aug 2013 om 08:59
-- Serverversie: 5.1.66
-- PHP-Versie: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `md155287db103792`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(40) NOT NULL AUTO_INCREMENT,
  `directory` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `directory_nice` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `is_default_front` int(11) NOT NULL,
  `blogid` int(128) NOT NULL,
  `jaar` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;
