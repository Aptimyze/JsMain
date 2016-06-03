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
-- Table structure for table `PARTNER_ELEVEL_UPDATE_MIS`
-- 
USE newjs;
CREATE TABLE `PARTNER_ELEVEL_UPDATE_MIS` (
  `PROFILEID` mediumint(9) NOT NULL,
  PRIMARY KEY (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

UPDATE `EDUCATION_LEVEL_NEW` SET OLD_VALUE=5 WHERE VALUE IN ('30','19')        
