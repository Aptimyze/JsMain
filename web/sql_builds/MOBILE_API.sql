
Create DATABASE MOBILE_API;



use database MOBILE_API;

-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Dec 12, 2013 at 04:28 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.15
-- 
-- Database: `MOBILE_API`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `CLIENT_INFO`
-- 

CREATE TABLE `CLIENT_INFO` (
  `APPID` int(3) NOT NULL AUTO_INCREMENT,
  `CLIENT` varchar(100) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `MOBILE` int(11) DEFAULT NULL,
  `AUTHKEY` varchar(32) NOT NULL,
  `STATUS` enum('E','D') DEFAULT 'D',
  `ADD_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UID` varchar(32) NOT NULL,
  `CURRENT_IP` varchar(20) NOT NULL,
  `IP_COUNT` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`APPID`),
  UNIQUE KEY `AUTHKEY` (`AUTHKEY`)
) ENGINE=MyISAM AUTO_INCREMENT=1356 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1356 ;
