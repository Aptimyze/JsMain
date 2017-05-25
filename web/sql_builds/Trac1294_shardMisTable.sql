-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3307
-- Generation Time: Apr 11, 2013 at 12:28 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `newjs`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `PARTNER_ELEVEL_MIS`
-- 
USE newjs;
CREATE TABLE `PARTNER_ELEVEL_MIS` (
 `PROFILEID` int(11) NOT NULL,
 `PARTNER_ELEVEL` text NOT NULL,
 PRIMARY KEY (`PROFILEID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT IGNORE INTO PARTNER_ELEVEL_MIS (PROFILEID,PARTNER_ELEVEL) SELECT PROFILEID ,PARTNER_ELEVEL FROM newjs.JPARTNER;
