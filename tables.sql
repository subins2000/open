-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Host: 127.13.151.2:3306
-- Generation Time: Aug 14, 2014 at 03:20 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `open`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `msg` text NOT NULL,
  `posted` varchar(20) NOT NULL,
  `red` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commentLikes`
--

CREATE TABLE IF NOT EXISTS `commentLikes` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `liked` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` varchar(30) NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conn`
--

CREATE TABLE IF NOT EXISTS `conn` (
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `since` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `txt` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `liked` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `sub` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notify`
--

CREATE TABLE IF NOT EXISTS `notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `ty` varchar(10) NOT NULL,
  `post` varchar(100) NOT NULL,
  `posted` varchar(20) NOT NULL,
  `red` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_session`
--

CREATE TABLE IF NOT EXISTS `oauth_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session` char(32) NOT NULL DEFAULT '',
  `state` char(32) NOT NULL DEFAULT '',
  `access_token` mediumtext NOT NULL,
  `expiry` datetime DEFAULT NULL,
  `type` char(12) NOT NULL DEFAULT '',
  `server` char(12) NOT NULL DEFAULT '',
  `creation` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `access_token_secret` mediumtext NOT NULL,
  `authorized` char(1) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL,
  `refresh_token` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_oauth_session_index` (`session`,`server`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post` text NOT NULL,
  `time` varchar(30) NOT NULL,
  `privacy` varchar(3) NOT NULL DEFAULT 'p',
  `likes` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trend`
--

CREATE TABLE IF NOT EXISTS `trend` (
  `title` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `psalt` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `udata` text NOT NULL,
  `seen` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE IF NOT EXISTS `verify` (
  `uid` int(11) NOT NULL,
  `code` varchar(35) NOT NULL,
  `posted` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
