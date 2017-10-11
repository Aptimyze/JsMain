-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3307
-- Generation Time: Apr 11, 2013 at 12:28 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 

-- 

-- --------------------------------------------------------

USE Assisted_Product;

ALTER TABLE AP_DPP_FILTER_ARCHIVE DROP COLUMN PARTNER_ELEVEL;

ALTER TABLE AP_TEMP_DPP DROP COLUMN PARTNER_ELEVEL;

USE newjs;

ALTER TABLE SEARCH_FEMALE_REV DROP COLUMN PARTNER_ELEVEL;

ALTER TABLE SEARCH_MALE_REV DROP COLUMN PARTNER_ELEVEL;

