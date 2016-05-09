-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Feb 13, 2013 at 11:53 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `MIS`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `INCOMPLETE_SCREENING`
-- 
use MIS;
CREATE TABLE `INCOMPLETE_SCREENING` (
  `PROFILEID` int(10) unsigned NOT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='contains new profiles which are marked incomplete after screening';

-- 
-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Feb 13, 2013 at 02:06 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `newjs`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `YOUR_INFO_OLD`
-- 
use newjs;
CREATE TABLE `YOUR_INFO_OLD` (
  `PROFILEID` int(11) unsigned NOT NULL,
  `YOUR_INFO_OLD` text,
  UNIQUE KEY `PROFILEID` (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
