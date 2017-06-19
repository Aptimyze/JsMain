use sugarcrm;

UPDATE sugarcrm.leads SET status='13' WHERE status IN ('','New','1','10','11','2','3','4','43','44','8');

create database sugarcrm_housekeeping;

-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 15, 2011 at 07:13 PM
-- Server version: 5.0.27
-- PHP Version: 5.3.6
-- 
-- Database: `sugarcrm_housekeeping`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `connected_email_addr_bean_rel`
-- 

use sugarcrm_housekeeping;

DROP TABLE IF EXISTS connected_leads;
DROP TABLE IF EXISTS connected_leads_cstm;
DROP TABLE IF EXISTS connected_email_addr_bean_rel;
DROP TABLE IF EXISTS connected_email_addresses;
DROP TABLE IF EXISTS inactive_leads;
DROP TABLE IF EXISTS inactive_leads_cstm;
DROP TABLE IF EXISTS inactive_email_addr_bean_rel;
DROP TABLE IF EXISTS inactive_email_addresses;
DROP TABLE IF EXISTS connected_tracker;
DROP TABLE IF EXISTS inactive_tracker;

CREATE TABLE connected_leads LIKE sugarcrm.leads;
CREATE TABLE connected_leads_cstm LIKE sugarcrm.leads_cstm;
CREATE TABLE connected_email_addresses LIKE sugarcrm.email_addresses;
CREATE TABLE connected_email_addr_bean_rel LIKE sugarcrm.email_addr_bean_rel;
CREATE TABLE connected_tracker LIKE sugarcrm.tracker;
CREATE TABLE inactive_leads LIKE sugarcrm.leads;
CREATE TABLE inactive_leads_cstm LIKE sugarcrm.leads_cstm;
CREATE TABLE inactive_email_addresses LIKE sugarcrm.email_addresses;
CREATE TABLE inactive_email_addr_bean_rel LIKE sugarcrm.email_addr_bean_rel;
CREATE TABLE inactive_tracker LIKE sugarcrm.tracker;
