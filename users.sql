-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Machine: db.visionsandviews.net
-- Genereertijd: 15 Aug 2013 om 09:03
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
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `pass` varchar(60) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `level` int(10) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) DEFAULT NULL,
  `lastlog` datetime NOT NULL,
  `regdate` datetime NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `location` varchar(40) DEFAULT NULL,
  `info` varchar(40) DEFAULT NULL,
  `avatar` varchar(40) NOT NULL,
  `task` varchar(20) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;
