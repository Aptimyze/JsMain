-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:34 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 
USE test;
-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_11Master_new`
-- 

CREATE TABLE `master_table_11Master_new` (
  `ID` int(16) unsigned NOT NULL DEFAULT '0',
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_11Master_new`
-- 

INSERT INTO `master_table_11Master_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (2, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12966335, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (3, 'newjs', 'DELETED_MESSAGE_LOG', 102138420, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (4, 'newjs', 'DELETED_PROFILE_CONTACTS', 70224867, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (5, 'newjs', 'LOGIN_HISTORY', 48846008, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (6, 'newjs', 'LOGIN_HISTORY_COUNT', 731726, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (7, 'newjs', 'LOG_LOGIN_HISTORY', 67362532, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (8, 'newjs', 'PROFILEID_SERVER_MAPPING', 2648956, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (9, 'newjs', 'TEMP_JP', 6215, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (10, 'sharding', 'LAST_LOGIN_PROFILES', 0, 'alter table sharding.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (11, 'visitoralert', 'MAILER_VISITORS', 67496, 'alter table visitoralert.MAILER_VISITORS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (12, 'visitoralert', 'PROFILES_SET_D_BEFORE_3173', 1948, 'alter table visitoralert.PROFILES_SET_D_BEFORE_3173 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Master_new` VALUES (13, 'visitoralert', 'VISITOR_ALERT_OPTION', 147167, 'alter table visitoralert.VISITOR_ALERT_OPTION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);



-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:35 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_11Slave_new`
-- 

CREATE TABLE `master_table_11Slave_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_11Slave_new`
-- 

INSERT INTO `master_table_11Slave_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (4, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12966335, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (5, 'newjs', 'DELETED_MESSAGE_LOG', 102138420, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (6, 'newjs', 'DELETED_PROFILE_CONTACTS', 70224867, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (8, 'newjs', 'LOGIN_HISTORY', 48846008, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (9, 'newjs', 'LOGIN_HISTORY_COUNT', 731726, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (10, 'newjs', 'LOG_LOGIN_HISTORY', 67362532, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (11, 'newjs', 'MESSAGE_LOG_1', 33604695, 'alter table newjs.MESSAGE_LOG_1 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (12, 'newjs', 'MESSAGE_LOG_2', 8819934, 'alter table newjs.MESSAGE_LOG_2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (14, 'newjs', 'PROFILEID_SERVER_MAPPING', 2648956, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (15, 'newjs', 'TEMP_JP', 6215, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (16, 'sharding', 'LAST_LOGIN_PROFILES', 0, 'alter table sharding.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (20, 'visitoralert', 'MAILER_VISITORS', 67496, 'alter table visitoralert.MAILER_VISITORS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (21, 'visitoralert', 'PROFILES_SET_D_BEFORE_3173', 1948, 'alter table visitoralert.PROFILES_SET_D_BEFORE_3173 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_11Slave_new` VALUES (22, 'visitoralert', 'VISITOR_ALERT_OPTION', 147167, 'alter table visitoralert.VISITOR_ALERT_OPTION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);



-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:35 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_211_new`
-- 

CREATE TABLE `master_table_211_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_211_new`
-- 

INSERT INTO `master_table_211_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 5145124, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 1);
INSERT INTO `master_table_211_new` VALUES (4, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12294127, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (5, 'newjs', 'DELETED_MESSAGE_LOG', 100760583, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 1);
INSERT INTO `master_table_211_new` VALUES (6, 'newjs', 'DELETED_PROFILE_CONTACTS', 68093069, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (8, 'newjs', 'LOGIN_HISTORY', 49103919, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (9, 'newjs', 'LOGIN_HISTORY_COUNT', 733889, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 1);
INSERT INTO `master_table_211_new` VALUES (10, 'newjs', 'LOG_LOGIN_HISTORY', 66445630, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 1);
INSERT INTO `master_table_211_new` VALUES (12, 'newjs', 'PROFILEID_SERVER_MAPPING', 2639546, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (13, 'newjs', 'TEMP_JP', 6231, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (14, 'newjs', 'VIEW_LOG_BEFOREHOUSEKEEPING', 290701851, 'alter table newjs.VIEW_LOG_BEFOREHOUSEKEEPING change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211_new` VALUES (15, 'newjs', 'VIEW_LOG_TRIGGER', 8699115, 'alter table newjs.VIEW_LOG_TRIGGER change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);


-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:36 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_211Slave_new`
-- 

CREATE TABLE `master_table_211Slave_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_211Slave_new`
-- 

INSERT INTO `master_table_211Slave_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 5145124, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (4, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12294127, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (5, 'newjs', 'DELETED_MESSAGE_LOG', 100760583, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (6, 'newjs', 'DELETED_PROFILE_CONTACTS', 68093069, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (8, 'newjs', 'LOGIN_HISTORY', 49103919, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (9, 'newjs', 'LOGIN_HISTORY_COUNT', 733889, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (10, 'newjs', 'LOG_LOGIN_HISTORY', 66445630, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (11, 'newjs', 'MESSAGE_LOG3', 23043818, 'DROP table newjs.MESSAGE_LOG3', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (12, 'newjs', 'MESSAGE_LOG4', 27079260, 'DROP table newjs.MESSAGE_LOG4', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (14, 'newjs', 'PROFILEID_SERVER_MAPPING', 2639546, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (15, 'newjs', 'TEMP_JP', 6231, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (16, 'newjs', 'VIEW_LOG5', 78613622, 'DROP table newjs.VIEW_LOG5', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (17, 'newjs', 'VIEW_LOG6', 8075947, 'DROP table newjs.VIEW_LOG6', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (18, 'newjs', 'VIEW_LOG7', 159213327, 'DROP table newjs.VIEW_LOG7', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (19, 'newjs', 'VIEW_LOG8', 44798955, 'DROP table newjs.VIEW_LOG8', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (21, 'newjs', 'VIEW_LOG_TRIGGER', 8699115, 'alter table newjs.VIEW_LOG_TRIGGER change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_211Slave_new` VALUES (22, 'sharding', 'LAST_LOGIN_PROFILES', 409165, 'alter table sharding.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);



-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:36 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_303Master_new`
-- 

CREATE TABLE `master_table_303Master_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_303Master_new`
-- 

INSERT INTO `master_table_303Master_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 5115753, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (4, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12594007, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (5, 'newjs', 'DELETED_MESSAGE_LOG', 101489126, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (6, 'newjs', 'DELETED_PROFILE_CONTACTS', 68721888, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (8, 'newjs', 'LOGIN_HISTORY', 48874796, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (9, 'newjs', 'LOGIN_HISTORY_COUNT', 741782, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (10, 'newjs', 'LOG_LOGIN_HISTORY', 68027031, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (12, 'newjs', 'PROFILEID_SERVER_MAPPING', 2658718, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (13, 'newjs', 'PROFILEID_SERVER_MAPPING_DUMP', 3171, 'alter table newjs.PROFILEID_SERVER_MAPPING_DUMP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (14, 'newjs', 'TEMP_JP', 6162, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Master_new` VALUES (15, 'sharding', 'LAST_LOGIN_PROFILES', 102258, 'alter table sharding.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);


-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:36 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_303Slave_new`
-- 

CREATE TABLE `master_table_303Slave_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_303Slave_new`
-- 

INSERT INTO `master_table_303Slave_new` VALUES (1, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 5115753, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (4, 'newjs', 'DELETED_EOI_VIEWED_LOG', 12594007, 'alter table newjs.DELETED_EOI_VIEWED_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (5, 'newjs', 'DELETED_MESSAGE_LOG', 101489126, 'alter table newjs.DELETED_MESSAGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (6, 'newjs', 'DELETED_PROFILE_CONTACTS', 68721888, 'alter table newjs.DELETED_PROFILE_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (8, 'newjs', 'LOGIN_HISTORY', 48874796, 'alter table newjs.LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (9, 'newjs', 'LOGIN_HISTORY_COUNT', 741782, 'alter table newjs.LOGIN_HISTORY_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (10, 'newjs', 'LOG_LOGIN_HISTORY', 68027031, 'alter table newjs.LOG_LOGIN_HISTORY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (11, 'newjs', 'MESSAGE_LOG_1', 34826899, 'DROP table newjs.MESSAGE_LOG_1', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (12, 'newjs', 'MESSAGE_LOG_2', 15527751, 'DROP table newjs.MESSAGE_LOG_2', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (14, 'newjs', 'PROFILEID_SERVER_MAPPING', 2658718, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (15, 'newjs', 'PROFILEID_SERVER_MAPPING_DUMP', 3171, 'alter table newjs.PROFILEID_SERVER_MAPPING_DUMP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (16, 'newjs', 'TEMP_JP', 6162, 'alter table newjs.TEMP_JP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_303Slave_new` VALUES (17, 'sharding', 'LAST_LOGIN_PROFILES', 102258, 'alter table sharding.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);




-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:37 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_737_new`
-- 

CREATE TABLE `master_table_737_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=290 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_737_new`
-- 

INSERT INTO `master_table_737_new` VALUES (1, 'billing', 'BLUEDART_COD_REQUEST', 0, 'alter table billing.BLUEDART_COD_REQUEST change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (2, 'billing', 'EASY_BILL', 26418, 'alter table billing.EASY_BILL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (3, 'billing', 'FAILED_PAYMENT_MAILS', 0, 'alter table billing.FAILED_PAYMENT_MAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (4, 'billing', 'IVR_DETAILS', 48851, 'alter table billing.IVR_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (7, 'billing', 'MATRI_ONHOLD', 0, 'alter table billing.MATRI_ONHOLD change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (8, 'billing', 'OFFER_DISCOUNT', 777008, 'alter table billing.OFFER_DISCOUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (9, 'billing', 'OFFER_DISCOUNT_TEMP', 0, 'alter table billing.OFFER_DISCOUNT_TEMP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (12, 'billing', 'VOUCHER_OPTIN', 38365, 'alter table billing.VOUCHER_OPTIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (13, 'billing', 'VOUCHER_SUCCESSSTORY', 0, 'alter table billing.VOUCHER_SUCCESSSTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (21, 'incentive', 'CRM_VOICE_LOG', 184030, 'alter table incentive.CRM_VOICE_LOG change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (22, 'incentive', 'INBOUND_ALLOT', 108633, 'alter table incentive.INBOUND_ALLOT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (23, 'incentive', 'INVALID_PHONE_COUNT', 0, 'alter table incentive.INVALID_PHONE_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (24, 'incentive', 'MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS', 0, 'alter table incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (25, 'incentive', 'NAME_OF_USER', 3087501, 'alter table incentive.NAME_OF_USER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (26, 'incentive', 'PHONE_DAILY_VERIFICATION', 0, 'alter table incentive.PHONE_DAILY_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (27, 'incentive', 'PROFILE_ALLOCATION', 0, 'DROP table incentive.PROFILE_ALLOCATION', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (28, 'incentive', 'PROFILE_ALLOCATION1', 0, 'DROP table incentive.PROFILE_ALLOCATION1', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (29, 'incentive', 'PROFILE_ALLOCATION2', 0, 'DROP table incentive.PROFILE_ALLOCATION2', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (30, 'incentive', 'PROFILE_ALLOCATION_TECH', 0, 'alter table incentive.PROFILE_ALLOCATION_TECH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (31, 'incentive', 'PROFILE_ALLOCATION_TEMP', 0, 'DROP table incentive.PROFILE_ALLOCATION_TEMP ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (32, 'incentive', 'PROFILE_ALTERNATE_NUMBER', 0, 'alter table incentive.PROFILE_ALTERNATE_NUMBER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (33, 'incentive', 'TEMP_ALLOCATION_BUCKET', 0, 'alter table incentive.TEMP_ALLOCATION_BUCKET change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (34, 'incentive', 'TEST_PROFILE_ALLOCATION', 0, 'DROP table incentive.TEST_PROFILE_ALLOCATION ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (35, 'incentive', 'Temp_Called_Table', 0, 'DROP table incentive.Temp_Called_Table', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (36, 'incentive', 'UNALLOTED_FAILED_PAYMENT', 0, 'alter table incentive.UNALLOTED_FAILED_PAYMENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (38, 'infovision', 'INF_USER_PIN', 7945552, 'alter table infovision.INF_USER_PIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (39, 'infovision', 'SEARCHQUERY', 2008, 'alter table infovision.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (40, 'infovision', 'VIEW_COUNT', 2354, 'alter table infovision.VIEW_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (41, 'jsadmin', 'ADDRESS_VERIFICATION', 0, 'alter table jsadmin.ADDRESS_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (42, 'jsadmin', 'AFFILIATE_DATA', 251634, 'alter table jsadmin.AFFILIATE_DATA change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (43, 'jsadmin', 'ASSIGNED_101', 91, 'alter table jsadmin.ASSIGNED_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (44, 'jsadmin', 'ASSIGNLOG_101', 91, 'alter table jsadmin.ASSIGNLOG_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (45, 'jsadmin', 'COMPLETE_BY_SYSTEM', 0, 'alter table jsadmin.COMPLETE_BY_SYSTEM change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (46, 'jsadmin', 'CONTACTS_ALLOTED', 189769, 'alter table jsadmin.CONTACTS_ALLOTED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (47, 'jsadmin', 'CONTACTS_ALLOTED_HISTORY', 0, 'alter table jsadmin.CONTACTS_ALLOTED_HISTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (48, 'jsadmin', 'DELETED_BECOME_INCOMPLETE', 0, 'alter table jsadmin.DELETED_BECOME_INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (49, 'jsadmin', 'DELETED_OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.DELETED_OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (50, 'jsadmin', 'DELETED_PROFILES', 622327, 'alter table jsadmin.DELETED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (51, 'jsadmin', 'DEL_STATUS', 9179, 'alter table jsadmin.DEL_STATUS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (52, 'jsadmin', 'DUPLICATE_NUMBER_PROFILE', 0, 'alter table jsadmin.DUPLICATE_NUMBER_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (53, 'jsadmin', 'INCOMPLETE', 707766, 'alter table jsadmin.INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (54, 'jsadmin', 'MARK_DELETE', 4041, 'alter table jsadmin.MARK_DELETE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (55, 'jsadmin', 'NON_SERIOUS_PROFILES', 0, 'alter table jsadmin.NON_SERIOUS_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (56, 'jsadmin', 'NON_SPAMMERS', 44, 'alter table jsadmin.NON_SPAMMERS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (57, 'jsadmin', 'OFFLINE_EMAIL', 1221, 'alter table jsadmin.OFFLINE_EMAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (58, 'jsadmin', 'OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (59, 'jsadmin', 'OFFLINE_NUDGE_LOG_BCK', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG_BCK change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (60, 'jsadmin', 'OFFLINE_OPERATOR_MESSAGES', 0, 'alter table jsadmin.OFFLINE_OPERATOR_MESSAGES change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change MATCH_ID MATCH_ID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (61, 'jsadmin', 'ON_HOLD_PROFILES', 128952, 'alter table jsadmin.ON_HOLD_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (62, 'jsadmin', 'PHONE_UNVERIFIED_LOG', 0, 'alter table jsadmin.PHONE_UNVERIFIED_LOG change PHONE_VERIFIED_LOG_ID PHONE_VERIFIED_LOG_ID int(11) unsigned  NOT NULL,change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (63, 'jsadmin', 'PHONE_VERIFIED_LOG', 0, 'alter table jsadmin.PHONE_VERIFIED_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (64, 'jsadmin', 'PROFILE_CHANGE_REQUEST', 0, 'alter table jsadmin.PROFILE_CHANGE_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (65, 'jsadmin', 'REPORT_INVALID_PHONE', 0, 'alter table jsadmin.REPORT_INVALID_PHONE change SUBMITTER SUBMITTER int(11) unsigned  NOT NULL,change SUBMITTEE SUBMITTEE int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (66, 'jsadmin', 'RETRIEVED_PROFILES', 0, 'alter table jsadmin.RETRIEVED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (67, 'jsadmin', 'SCREENING_GRADES', 27904, 'alter table jsadmin.SCREENING_GRADES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);

INSERT INTO `master_table_737_new` VALUES (71, 'jsadmin', 'SCREENING_LOG_OLD', 0, 'alter table jsadmin.SCREENING_LOG_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (72, 'jsadmin', 'SCREEN_TEMP_CHECK', 0, 'alter table jsadmin.SCREEN_TEMP_CHECK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (73, 'jsadmin', 'SPAMMERS', 52444, 'alter table jsadmin.SPAMMERS change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (74, 'jsadmin', 'SPAMMERS_BACKUP', 5965, 'alter table jsadmin.SPAMMERS_BACKUP change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (75, 'jsadmin', 'SPAMMERS_BACKUP_13AUG', 0, 'alter table jsadmin.SPAMMERS_BACKUP_13AUG change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (76, 'jsadmin', 'VIEW_CONTACTS_LOG', 0, 'alter table jsadmin.VIEW_CONTACTS_LOG change VIEWER VIEWER int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (77, 'jsmailer', 'MAILER', 45884, 'alter table jsmailer.MAILER change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (78, 'mailer', 'DISCOUNT_MAILER', 1550798, 'alter table mailer.DISCOUNT_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (79, 'mailer', 'DISCOUNT_MAILER_SMS', 0, 'alter table mailer.DISCOUNT_MAILER_SMS change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (80, 'mailer', 'MATRI_MAILER', 3152538, 'alter table mailer.MATRI_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (81, 'mailer', 'MERCHANT_NAVY_MAILER', 0, 'alter table mailer.MERCHANT_NAVY_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (82, 'mailer', 'NEW_SHOPPING_MAILER_DETAILS', 0, 'alter table mailer.NEW_SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (83, 'marriage_bureau', 'BUREAU_PROFILE', 33, 'alter table marriage_bureau.BUREAU_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (84, 'marriage_bureau', 'CPP_UPDATE_LOG', 5, 'alter table marriage_bureau.CPP_UPDATE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (85, 'marriage_bureau', 'VIEWED', 2367, 'alter table marriage_bureau.VIEWED change AGAINST_PROFILE AGAINST_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (86, 'MATCHALERT_TRACKING', 'MA_HISTORY', 2181784, 'alter table MATCHALERT_TRACKING.MA_HISTORY change PID PID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (87, 'MIS', 'ASTRO_CLICK_COUNT', 0, 'alter table MIS.ASTRO_CLICK_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (88, 'MIS', 'ASTRO_COMMUNITY_WISE', 0, 'alter table MIS.ASTRO_COMMUNITY_WISE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (89, 'MIS', 'ASTRO_DATA_COUNT', 1147937, 'alter table MIS.ASTRO_DATA_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (90, 'MIS', 'ASTRO_IMAGE_TRACK', 0, 'alter table MIS.ASTRO_IMAGE_TRACK change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED_PROFILEID VIEWED_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (91, 'MIS', 'CONTACTS_FAULT_MONITOR', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (92, 'MIS', 'CONTACTS_FAULT_MONITOR1', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (93, 'MIS', 'CONTACTS_TEST_TEST', 0, 'alter table MIS.CONTACTS_TEST_TEST change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (94, 'MIS', 'DATESORT', 0, 'alter table MIS.DATESORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (95, 'MIS', 'FEMALE_BAND', 229140, 'alter table MIS.FEMALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (96, 'MIS', 'FORCE_SCREEN', 21, 'alter table MIS.FORCE_SCREEN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (97, 'MIS', 'INC_COUNT', 126424, 'alter table MIS.INC_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (98, 'MIS', 'INC_COUNT_BCK', 235981, 'alter table MIS.INC_COUNT_BCK change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (99, 'MIS', 'KUNDLI_MAILER_TRACKING', 0, 'alter table MIS.KUNDLI_MAILER_TRACKING change PROFILES_CONSIDERED PROFILES_CONSIDERED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (100, 'MIS', 'LANG_REGISTER', 1042, 'alter table MIS.LANG_REGISTER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (101, 'MIS', 'LOG_CONTACT_ERROR', 0, 'alter table MIS.LOG_CONTACT_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (102, 'MIS', 'MALE_BAND', 462117, 'alter table MIS.MALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (103, 'MIS', 'MAPPING_MTON_CITY', 0, 'alter table MIS.MAPPING_MTON_CITY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (104, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (105, 'MIS', 'MYJS_PROFILING', 2328, 'alter table MIS.MYJS_PROFILING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (106, 'MIS', 'REDIFF_SRCH_REG', 14365, 'alter table MIS.REDIFF_SRCH_REG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (107, 'MIS', 'REG_COUNT', 3315835, 'alter table MIS.REG_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (108, 'MIS', 'REG_HOME', 653, 'alter table MIS.REG_HOME change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (109, 'MIS', 'REG_HOME_OLD', 581, 'alter table MIS.REG_HOME_OLD change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (110, 'MIS', 'RELSORT', 0, 'alter table MIS.RELSORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (111, 'MIS', 'REVERSE_FLAG_TRACKING', 0, 'alter table MIS.REVERSE_FLAG_TRACKING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (112, 'MIS', 'SEARCHQUERY', 35938696, 'alter table MIS.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (113, 'MIS', 'SEARCHQUERY1', 19317437, 'alter table MIS.SEARCHQUERY1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (114, 'MIS', 'SEARCHQUERY2', 34350109, 'alter table MIS.SEARCHQUERY2 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (115, 'MIS', 'SEARCHQUERY3', 30929007, 'alter table MIS.SEARCHQUERY3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (116, 'MIS', 'SEARCHQUERY_16jul2010', 0, 'alter table MIS.SEARCHQUERY_16jul2010 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (117, 'MIS', 'SEARCHQUERY_APRIL2009', 0, 'alter table MIS.SEARCHQUERY_APRIL2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (118, 'MIS', 'SEARCHQUERY_MAY2009', 0, 'alter table MIS.SEARCHQUERY_MAY2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (119, 'MIS', 'SEARCHQUERY_NEW', 0, 'alter table MIS.SEARCHQUERY_NEW change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (120, 'MIS', 'SEARCHQUERY_TEMP', 0, 'alter table MIS.SEARCHQUERY_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (121, 'MIS', 'SEARCHQUERY_TEMP_OLD', 0, 'alter table MIS.SEARCHQUERY_TEMP_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (122, 'MIS', 'SEARCHQUERY_TRAC280', 0, 'alter table MIS.SEARCHQUERY_TRAC280 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (123, 'MIS', 'TRACK_ASTRO_DETAILS', 0, 'alter table MIS.TRACK_ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (124, 'MIS', 'VIEW_FOR_MIS', 849963, 'alter table MIS.VIEW_FOR_MIS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (125, 'MIS', 'WHY_FILTER', 33262012, 'alter table MIS.WHY_FILTER change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (126, 'MIS', 'new_table', 0, 'alter table MIS.new_table change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (127, 'newjs', 'ANNULLED', 33369, 'alter table newjs.ANNULLED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (128, 'newjs', 'ASTRO_DETAILS', 1130827, 'alter table newjs.ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (129, 'newjs', 'ASTRO_PULLING_REQUEST', 0, 'alter table newjs.ASTRO_PULLING_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (130, 'newjs', 'AUTOLOGIN_CONTACTS', 0, 'alter table newjs.AUTOLOGIN_CONTACTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (131, 'newjs', 'AUTOLOGIN_LOGIN', 10261, 'alter table newjs.AUTOLOGIN_LOGIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (133, 'newjs', 'CHAT_INVITATION', 8559, 'alter table newjs.CHAT_INVITATION change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (134, 'newjs', 'COMPATIBILITY', 0, 'alter table newjs.COMPATIBILITY change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (135, 'newjs', 'CONNECT', 0, 'alter table newjs.CONNECT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (136, 'newjs', 'CONTACTS_ONCE', 265380, 'alter table newjs.CONTACTS_ONCE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (139, 'newjs', 'CONTACTS_STATUS', 1605994, 'alter table newjs.CONTACTS_STATUS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (140, 'newjs', 'CONTACTS_STATUS_TRACK', 0, 'alter table newjs.CONTACTS_STATUS_TRACK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (141, 'newjs', 'CONTACTS_TEMP', 1713278, 'alter table newjs.CONTACTS_TEMP change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (142, 'newjs', 'CONTACT_ARCHIVE', 13429066, 'alter table newjs.CONTACT_ARCHIVE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (143, 'newjs', 'CONTACT_LIMIT', 934110, 'alter table newjs.CONTACT_LIMIT change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (144, 'newjs', 'COSMO', 1523837, 'alter table newjs.COSMO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (145, 'newjs', 'CUSTOMISED_USERNAME', 0, 'alter table newjs.CUSTOMISED_USERNAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (146, 'newjs', 'DAILY_CONTACT_SMS', 0, 'alter table newjs.DAILY_CONTACT_SMS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (147, 'newjs', 'DELETED_BOOKMARKS', 0, 'alter table newjs.DELETED_BOOKMARKS change BOOKMARKER BOOKMARKER int(11) unsigned  DEFAULT ''0'' NOT NULL,change BOOKMARKEE BOOKMARKEE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (148, 'newjs', 'DISCOUNT_CODE', 1843300, 'alter table newjs.DISCOUNT_CODE change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (149, 'newjs', 'DISCOUNT_CODE_USED', 0, 'alter table newjs.DISCOUNT_CODE_USED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (150, 'newjs', 'DOUBLE_OPTIN', 0, 'alter table newjs.DOUBLE_OPTIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (151, 'newjs', 'DRAFTS', 432539, 'alter table newjs.DRAFTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (152, 'newjs', 'DUP_CONTACTS', 104158, 'alter table newjs.DUP_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (153, 'newjs', 'EDIT_LOG', 11460217, 'alter table newjs.EDIT_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (154, 'newjs', 'EDIT_LOG_JPC', 36454, 'alter table newjs.EDIT_LOG_JPC change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (155, 'newjs', 'EDIT_LOG_JPJ', 17003, 'alter table newjs.EDIT_LOG_JPJ change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (156, 'newjs', 'EDIT_LOG_JPM', 80725, 'alter table newjs.EDIT_LOG_JPM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (157, 'newjs', 'EDIT_LOG_JPP', 374, 'alter table newjs.EDIT_LOG_JPP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (158, 'newjs', 'EDIT_LOG_JPS', 31301, 'alter table newjs.EDIT_LOG_JPS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (159, 'newjs', 'FASHION', 212, 'alter table newjs.FASHION change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (160, 'newjs', 'FEATURED_PROFILE_LOG', 0, 'alter table newjs.FEATURED_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (161, 'newjs', 'FILTERS', 1775668, 'alter table newjs.FILTERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (162, 'newjs', 'FILTER_LOG', 11096735, 'alter table newjs.FILTER_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (163, 'newjs', 'FOLDERS', 48586, 'alter table newjs.FOLDERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (164, 'newjs', 'FREE_CONTACTED_PROFILE', 0, 'alter table newjs.FREE_CONTACTED_PROFILE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (165, 'newjs', 'GIF_PHOTO', 1667, 'alter table newjs.GIF_PHOTO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (166, 'newjs', 'HIDE_DOB', 3, 'alter table newjs.HIDE_DOB change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (167, 'newjs', 'HOMEPAGE_PHOTO', 343, 'alter table newjs.HOMEPAGE_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (168, 'newjs', 'HOMEPAGE_PROFILES', 0, 'alter table newjs.HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (169, 'newjs', 'HOROSCOPE_CAPTURE', 0, 'alter table newjs.HOROSCOPE_CAPTURE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (170, 'newjs', 'INCOMPLETE_PROFILES', 0, 'alter table newjs.INCOMPLETE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (171, 'newjs', 'INCREASE_RESPONSE', 0, 'alter table newjs.INCREASE_RESPONSE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (172, 'newjs', 'INSURANCE_MAIL', 5355, 'alter table newjs.INSURANCE_MAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (173, 'newjs', 'INVALID_EMAIL_MAILER', 0, 'alter table newjs.INVALID_EMAIL_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (174, 'newjs', 'INVALID_PHONE_MAILER', 0, 'alter table newjs.INVALID_PHONE_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (175, 'newjs', 'INVITEE', 17707, 'alter table newjs.INVITEE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (176, 'newjs', 'ISEARCH_CHECK', 1016, 'alter table newjs.ISEARCH_CHECK change DATA_PROFILEID DATA_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (177, 'newjs', 'JHOBBY', 2410658, 'alter table newjs.JHOBBY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (179, 'newjs', 'JPROFILE_ERRORS', 2902, 'alter table newjs.JPROFILE_ERRORS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (180, 'newjs', 'JPROFILE_OFFLINE', 3179, 'alter table newjs.JPROFILE_OFFLINE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (181, 'newjs', 'JPROFILE_PAGE3', 459778, 'alter table newjs.JPROFILE_PAGE3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (182, 'newjs', 'JP_CHRISTIAN', 96940, 'alter table newjs.JP_CHRISTIAN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (183, 'newjs', 'JP_JAIN', 29856, 'alter table newjs.JP_JAIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (184, 'newjs', 'JP_MUSLIM', 204070, 'alter table newjs.JP_MUSLIM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (185, 'newjs', 'JP_NTIMES', 4817806, 'alter table newjs.JP_NTIMES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (186, 'newjs', 'JP_PARSI', 2019, 'alter table newjs.JP_PARSI change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (187, 'newjs', 'JP_SIKH', 90269, 'alter table newjs.JP_SIKH change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (188, 'newjs', 'JS_PREDICTIVE', 1487827, 'alter table newjs.JS_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (189, 'newjs', 'KNWLARITYVNO', 51983, 'alter table newjs.KNWLARITYVNO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (190, 'newjs', 'KUNDALI_CAPTURE', 2919, 'alter table newjs.KUNDALI_CAPTURE change MATCH_BY MATCH_BY int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCH_TO MATCH_TO int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (191, 'newjs', 'LAST_LOGIN_PROFILES', 0, 'alter table newjs.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (192, 'newjs', 'LOGIN_DATA', 897691, 'alter table newjs.LOGIN_DATA change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (193, 'newjs', 'MATCHALERT_CONTACTS', 0, 'alter table newjs.MATCHALERT_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (194, 'newjs', 'MATCHALERT_PROFILEID', 0, 'alter table newjs.MATCHALERT_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (195, 'newjs', 'MATRIMONIAL_PHOTO', 0, 'alter table newjs.MATRIMONIAL_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (196, 'newjs', 'MATRIMONIAL_PHOTO1', 0, 'alter table newjs.MATRIMONIAL_PHOTO1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (197, 'newjs', 'NO_SIMILAR_PROFILES', 0, 'alter table newjs.NO_SIMILAR_PROFILES change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (198, 'newjs', 'OBSCENE_MESSAGE', 449595, 'alter table newjs.OBSCENE_MESSAGE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (199, 'newjs', 'OFFLINE_SMS_PUSH', 58482, 'alter table newjs.OFFLINE_SMS_PUSH change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (200, 'newjs', 'OFFLINE_SMS_PUSH1', 0, 'alter table newjs.OFFLINE_SMS_PUSH1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (201, 'newjs', 'OLDEMAIL', 525306, 'alter table newjs.OLDEMAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (202, 'newjs', 'OLD_CONTACTS', 1420968, 'alter table newjs.OLD_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (203, 'newjs', 'PAGE_VIEWS', 57638, 'alter table newjs.PAGE_VIEWS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (204, 'newjs', 'PHONE_VERIFY_CODE', 0, 'alter table newjs.PHONE_VERIFY_CODE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (205, 'newjs', 'PICTURE_TITLES', 222904, 'alter table newjs.PICTURE_TITLES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (206, 'newjs', 'PROFILEID_SERVER_MAPPING', 0, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (207, 'newjs', 'PROFILE_DEL_REASON', 0, 'alter table newjs.PROFILE_DEL_REASON change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (208, 'newjs', 'PROFILE_NAME', 115911, 'alter table newjs.PROFILE_NAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (209, 'newjs', 'PROMOTIONAL_MAIL', 4299107, 'alter table newjs.PROMOTIONAL_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (210, 'newjs', 'SEARCHQUERY', 194099, 'alter table newjs.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (211, 'newjs', 'SEARCHQUERY_TMP', 0, 'alter table newjs.SEARCHQUERY_TMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (212, 'newjs', 'SEARCH_AGENT', 313418, 'alter table newjs.SEARCH_AGENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (213, 'newjs', 'SEARCH_FEMALE_FULL1', 0, 'alter table newjs.SEARCH_FEMALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (214, 'newjs', 'SEARCH_MALE_FULL1', 0, 'alter table newjs.SEARCH_MALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (215, 'newjs', 'SENT_VERIFICATION_SMS', 0, 'alter table newjs.SENT_VERIFICATION_SMS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (216, 'newjs', 'SHOPPING_MAILER_DETAILS', 0, 'alter table newjs.SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (217, 'newjs', 'SIM_PROFILE_LOG', 6327673, 'alter table newjs.SIM_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (218, 'newjs', 'SIM_PROFILE_LOG_TEMP', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (219, 'newjs', 'SIM_PROFILE_LOG_TEMP1', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (223, 'newjs', 'SMS_CONTACT_LOG', 138230, 'alter table newjs.SMS_CONTACT_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (224, 'newjs', 'SMS_SEARCHLOG', 1074113, 'alter table newjs.SMS_SEARCHLOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (225, 'newjs', 'SMS_SUBSCRIPTION_DEACTIVATED', 0, 'alter table newjs.SMS_SUBSCRIPTION_DEACTIVATED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (226, 'newjs', 'SMS_TEMP_TABLE', 767240, 'alter table newjs.SMS_TEMP_TABLE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (228, 'newjs', 'STOCK_TRADING_MAIL', 0, 'alter table newjs.STOCK_TRADING_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (229, 'newjs', 'SWAP_FULL', 0, 'alter table newjs.SWAP_FULL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (230, 'newjs', 'SWAP_JPARTNER', 9355, 'alter table newjs.SWAP_JPARTNER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (231, 'newjs', 'SWAP_JPARTNER1', 0, 'alter table newjs.SWAP_JPARTNER1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (232, 'newjs', 'SWAP_JPARTNER_24FEB', 0, 'DROP table newjs.SWAP_JPARTNER_24FEB ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (233, 'newjs', 'SWAP_JPARTNER_ERROR', 0, 'alter table newjs.SWAP_JPARTNER_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (234, 'newjs', 'SWAP_JPROFILE', 18088, 'alter table newjs.SWAP_JPROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (235, 'newjs', 'SWAP_JPROFILE1', 0, 'alter table newjs.SWAP_JPROFILE1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (236, 'newjs', 'SWAP_JPROFILE_ERROR', 0, 'alter table newjs.SWAP_JPROFILE_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (237, 'newjs', 'SWAP_JPROFILE_ERROR_PIDS', 0, 'alter table newjs.SWAP_JPROFILE_ERROR_PIDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (238, 'newjs', 'SWAP_REV_24FEB', 88487, 'DROP table newjs.SWAP_REV_24FEB', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (239, 'newjs', 'SWAP_SEARCH_FULL1', 0, 'alter table newjs.SWAP_SEARCH_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (240, 'newjs', 'TEMP_HOMEPAGE_PROFILES', 0, 'alter table newjs.TEMP_HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (241, 'newjs', 'TEMP_PREDICTIVE', 1887821, 'alter table newjs.TEMP_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (242, 'newjs', 'TEMP_PROFILEID', 0, 'alter table newjs.TEMP_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (243, 'newjs', 'UNAVAILABLE_ASTRO_COUNTRY', 0, 'alter table newjs.UNAVAILABLE_ASTRO_COUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (244, 'newjs', 'UNMATCHED_NAKSHATRA_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_NAKSHATRA_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (245, 'newjs', 'UNMATCHED_RASHI_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_RASHI_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (246, 'newjs', 'UNMATCHED_SUNSIGN_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_SUNSIGN_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (247, 'newjs', 'USER_STARTS_PAYING', 0, 'alter table newjs.USER_STARTS_PAYING change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (248, 'newjs', 'VOUCHER_INTERMEDIATE_VIEWED', 0, 'alter table newjs.VOUCHER_INTERMEDIATE_VIEWED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (250, 'search_intel', 'ISCASTE', 471838, 'alter table search_intel.ISCASTE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (251, 'search_intel', 'ISCITY', 174108, 'alter table search_intel.ISCITY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (252, 'search_intel', 'ISCOUNTRY', 267802, 'alter table search_intel.ISCOUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (253, 'search_intel', 'ISEARCH', 198457, 'alter table search_intel.ISEARCH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (254, 'sms', 'SMSLOG', 735, 'alter table sms.SMSLOG change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (255, 'sms', 'TEMPSORT', 0, 'alter table sms.TEMPSORT change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (256, 'srch', 'JPARTNER', 102203, 'alter table srch.JPARTNER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (271, 'twowaymatch', 'TEMP_CALCULATE', 1110353, 'alter table twowaymatch.TEMP_CALCULATE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (272, 'twowaymatch', 'TRENDS', 1833904, 'alter table twowaymatch.TRENDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (273, 'twowaymatch', 'TRENDS_FOR_SPAM', 652396, 'alter table twowaymatch.TRENDS_FOR_SPAM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (274, 'userplane', 'CHAT_REQUESTS', 0, 'alter table userplane.CHAT_REQUESTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (275, 'userplane', 'CHECK_TABLE', 24586, 'alter table userplane.CHECK_TABLE change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (277, 'userplane', 'LOG_AD', 11767055, 'alter table userplane.LOG_AD change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (278, 'userplane', 'LOG_CHAT_REQUEST', 4082425, 'alter table userplane.LOG_CHAT_REQUEST change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (280, 'userplane', 'USERS_AD', 15045521, 'alter table userplane.USERS_AD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (282, 'userplane', 'blocked', 27713, 'alter table userplane.blocked change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL,change destinationUserID destinationUserID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_737_new` VALUES (288, 'userplane', 'users', 0, 'alter table userplane.users change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);



-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:37 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_master_new`
-- 

CREATE TABLE `master_table_master_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_master_new`
-- 

INSERT INTO `master_table_master_new` VALUES (1, 'billing', 'BLUEDART_COD_REQUEST', 0, 'alter table billing.BLUEDART_COD_REQUEST change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (2, 'billing', 'EASY_BILL', 26418, 'alter table billing.EASY_BILL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (3, 'billing', 'FAILED_PAYMENT_MAILS', 0, 'alter table billing.FAILED_PAYMENT_MAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (4, 'billing', 'IVR_DETAILS', 48851, 'alter table billing.IVR_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (8, 'billing', 'OFFER_DISCOUNT', 777008, 'alter table billing.OFFER_DISCOUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (9, 'billing', 'OFFER_DISCOUNT_TEMP', 0, 'alter table billing.OFFER_DISCOUNT_TEMP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (12, 'billing', 'VOUCHER_OPTIN', 38365, 'alter table billing.VOUCHER_OPTIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (13, 'billing', 'VOUCHER_SUCCESSSTORY', 0, 'alter table billing.VOUCHER_SUCCESSSTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (21, 'incentive', 'CRM_VOICE_LOG', 184030, 'alter table incentive.CRM_VOICE_LOG change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (22, 'incentive', 'INBOUND_ALLOT', 108633, 'alter table incentive.INBOUND_ALLOT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (23, 'incentive', 'INVALID_PHONE_COUNT', 0, 'alter table incentive.INVALID_PHONE_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (24, 'incentive', 'MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS', 0, 'alter table incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (25, 'incentive', 'NAME_OF_USER', 3087501, 'alter table incentive.NAME_OF_USER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (26, 'incentive', 'PHONE_DAILY_VERIFICATION', 0, 'alter table incentive.PHONE_DAILY_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (27, 'incentive', 'PROFILE_ALLOCATION', 0, 'DROP table incentive.PROFILE_ALLOCATION', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (28, 'incentive', 'PROFILE_ALLOCATION1', 0, 'DROP table incentive.PROFILE_ALLOCATION1', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (29, 'incentive', 'PROFILE_ALLOCATION2', 0, 'DROP table incentive.PROFILE_ALLOCATION2', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (30, 'incentive', 'PROFILE_ALLOCATION_TECH', 0, 'alter table incentive.PROFILE_ALLOCATION_TECH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (31, 'incentive', 'PROFILE_ALLOCATION_TEMP', 0, 'DROP table incentive.PROFILE_ALLOCATION_TEMP', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (32, 'incentive', 'PROFILE_ALTERNATE_NUMBER', 0, 'alter table incentive.PROFILE_ALTERNATE_NUMBER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (33, 'incentive', 'TEMP_ALLOCATION_BUCKET', 0, 'alter table incentive.TEMP_ALLOCATION_BUCKET change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (34, 'incentive', 'TEST_PROFILE_ALLOCATION', 0, 'DROP table incentive.TEST_PROFILE_ALLOCATION', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (35, 'incentive', 'UNALLOTED_FAILED_PAYMENT', 0, 'alter table incentive.UNALLOTED_FAILED_PAYMENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (37, 'infovision', 'INF_USER_PIN', 7945552, 'alter table infovision.INF_USER_PIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (38, 'infovision', 'SEARCHQUERY', 2008, 'alter table infovision.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (39, 'infovision', 'VIEW_COUNT', 2354, 'alter table infovision.VIEW_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (40, 'jsadmin', 'ADDRESS_VERIFICATION', 0, 'alter table jsadmin.ADDRESS_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (41, 'jsadmin', 'AFFILIATE_DATA', 251634, 'alter table jsadmin.AFFILIATE_DATA change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (42, 'jsadmin', 'ASSIGNED_101', 91, 'alter table jsadmin.ASSIGNED_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (43, 'jsadmin', 'ASSIGNLOG_101', 91, 'alter table jsadmin.ASSIGNLOG_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (44, 'jsadmin', 'COMPLETE_BY_SYSTEM', 0, 'alter table jsadmin.COMPLETE_BY_SYSTEM change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (45, 'jsadmin', 'CONTACTS_ALLOTED', 189769, 'alter table jsadmin.CONTACTS_ALLOTED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (46, 'jsadmin', 'CONTACTS_ALLOTED_HISTORY', 0, 'alter table jsadmin.CONTACTS_ALLOTED_HISTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (47, 'jsadmin', 'DELETED_BECOME_INCOMPLETE', 0, 'alter table jsadmin.DELETED_BECOME_INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (48, 'jsadmin', 'DELETED_OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.DELETED_OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (49, 'jsadmin', 'DELETED_PROFILES', 622327, 'alter table jsadmin.DELETED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (50, 'jsadmin', 'DEL_STATUS', 9179, 'alter table jsadmin.DEL_STATUS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (51, 'jsadmin', 'DUPLICATE_NUMBER_PROFILE', 0, 'alter table jsadmin.DUPLICATE_NUMBER_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (52, 'jsadmin', 'INCOMPLETE', 707766, 'alter table jsadmin.INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (53, 'jsadmin', 'MARK_DELETE', 4041, 'alter table jsadmin.MARK_DELETE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (54, 'jsadmin', 'NON_SERIOUS_PROFILES', 0, 'alter table jsadmin.NON_SERIOUS_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (55, 'jsadmin', 'NON_SPAMMERS', 44, 'alter table jsadmin.NON_SPAMMERS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (56, 'jsadmin', 'OFFLINE_EMAIL', 1221, 'alter table jsadmin.OFFLINE_EMAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (57, 'jsadmin', 'OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (58, 'jsadmin', 'OFFLINE_NUDGE_LOG_BCK', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG_BCK change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (59, 'jsadmin', 'OFFLINE_OPERATOR_MESSAGES', 0, 'alter table jsadmin.OFFLINE_OPERATOR_MESSAGES change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change MATCH_ID MATCH_ID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (60, 'jsadmin', 'ON_HOLD_PROFILES', 128952, 'alter table jsadmin.ON_HOLD_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (61, 'jsadmin', 'PHONE_UNVERIFIED_LOG', 0, 'alter table jsadmin.PHONE_UNVERIFIED_LOG change PHONE_VERIFIED_LOG_ID PHONE_VERIFIED_LOG_ID int(11) unsigned  NOT NULL,change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (62, 'jsadmin', 'PHONE_VERIFIED_LOG', 0, 'alter table jsadmin.PHONE_VERIFIED_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (63, 'jsadmin', 'PROFILE_CHANGE_REQUEST', 0, 'alter table jsadmin.PROFILE_CHANGE_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (64, 'jsadmin', 'REPORT_INVALID_PHONE', 0, 'alter table jsadmin.REPORT_INVALID_PHONE change SUBMITTER SUBMITTER int(11) unsigned  NOT NULL,change SUBMITTEE SUBMITTEE int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (65, 'jsadmin', 'RETRIEVED_PROFILES', 0, 'alter table jsadmin.RETRIEVED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (66, 'jsadmin', 'SCREENING_GRADES', 27904, 'alter table jsadmin.SCREENING_GRADES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);

INSERT INTO `master_table_master_new` VALUES (70, 'jsadmin', 'SCREENING_LOG_OLD', 0, 'alter table jsadmin.SCREENING_LOG_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (71, 'jsadmin', 'SCREEN_TEMP_CHECK', 0, 'alter table jsadmin.SCREEN_TEMP_CHECK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (72, 'jsadmin', 'SPAMMERS', 52444, 'alter table jsadmin.SPAMMERS change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (73, 'jsadmin', 'SPAMMERS_BACKUP', 5965, 'alter table jsadmin.SPAMMERS_BACKUP change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (74, 'jsadmin', 'SPAMMERS_BACKUP_13AUG', 0, 'alter table jsadmin.SPAMMERS_BACKUP_13AUG change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (75, 'jsadmin', 'VIEW_CONTACTS_LOG', 0, 'alter table jsadmin.VIEW_CONTACTS_LOG change VIEWER VIEWER int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (76, 'jsmailer', 'MAILER', 45884, 'alter table jsmailer.MAILER change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (77, 'mailer', 'DISCOUNT_MAILER', 1550798, 'alter table mailer.DISCOUNT_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (78, 'mailer', 'DISCOUNT_MAILER_SMS', 0, 'alter table mailer.DISCOUNT_MAILER_SMS change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (79, 'mailer', 'MATRI_MAILER', 3152538, 'alter table mailer.MATRI_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (80, 'mailer', 'MERCHANT_NAVY_MAILER', 0, 'alter table mailer.MERCHANT_NAVY_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (81, 'mailer', 'NEW_SHOPPING_MAILER_DETAILS', 0, 'alter table mailer.NEW_SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (82, 'marriage_bureau', 'BUREAU_PROFILE', 33, 'alter table marriage_bureau.BUREAU_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (83, 'marriage_bureau', 'CPP_UPDATE_LOG', 5, 'alter table marriage_bureau.CPP_UPDATE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (84, 'marriage_bureau', 'VIEWED', 2367, 'alter table marriage_bureau.VIEWED change AGAINST_PROFILE AGAINST_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (85, 'MATCHALERT_TRACKING', 'MA_HISTORY', 2181784, 'alter table MATCHALERT_TRACKING.MA_HISTORY change PID PID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (86, 'MIS', 'ASTRO_CLICK_COUNT', 0, 'alter table MIS.ASTRO_CLICK_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (87, 'MIS', 'ASTRO_COMMUNITY_WISE', 0, 'alter table MIS.ASTRO_COMMUNITY_WISE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (88, 'MIS', 'ASTRO_DATA_COUNT', 1147937, 'alter table MIS.ASTRO_DATA_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (89, 'MIS', 'ASTRO_IMAGE_TRACK', 0, 'alter table MIS.ASTRO_IMAGE_TRACK change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED_PROFILEID VIEWED_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (90, 'MIS', 'CONTACTS_FAULT_MONITOR', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (91, 'MIS', 'CONTACTS_FAULT_MONITOR1', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (92, 'MIS', 'CONTACTS_TEST_TEST', 0, 'alter table MIS.CONTACTS_TEST_TEST change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (93, 'MIS', 'DATESORT', 0, 'alter table MIS.DATESORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (94, 'MIS', 'FEMALE_BAND', 229140, 'alter table MIS.FEMALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (95, 'MIS', 'FORCE_SCREEN', 21, 'alter table MIS.FORCE_SCREEN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (96, 'MIS', 'INC_COUNT', 126424, 'alter table MIS.INC_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (97, 'MIS', 'INC_COUNT_BCK', 235981, 'alter table MIS.INC_COUNT_BCK change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (98, 'MIS', 'KUNDLI_MAILER_TRACKING', 0, 'alter table MIS.KUNDLI_MAILER_TRACKING change PROFILES_CONSIDERED PROFILES_CONSIDERED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (99, 'MIS', 'LANG_REGISTER', 1042, 'alter table MIS.LANG_REGISTER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (100, 'MIS', 'LOG_CONTACT_ERROR', 0, 'alter table MIS.LOG_CONTACT_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (101, 'MIS', 'MALE_BAND', 462117, 'alter table MIS.MALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (102, 'MIS', 'MAPPING_MTON_CITY', 0, 'alter table MIS.MAPPING_MTON_CITY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (103, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (104, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD0', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD0 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (105, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD1', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (106, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD2', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD2 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (107, 'MIS', 'MYJS_PROFILING', 2328, 'alter table MIS.MYJS_PROFILING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (108, 'MIS', 'REDIFF_SRCH_REG', 14365, 'alter table MIS.REDIFF_SRCH_REG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (109, 'MIS', 'REG_COUNT', 3315835, 'alter table MIS.REG_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (110, 'MIS', 'REG_HOME', 653, 'alter table MIS.REG_HOME change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (111, 'MIS', 'REG_HOME_OLD', 581, 'alter table MIS.REG_HOME_OLD change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (112, 'MIS', 'RELSORT', 0, 'alter table MIS.RELSORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (113, 'MIS', 'REVERSE_FLAG_TRACKING', 0, 'alter table MIS.REVERSE_FLAG_TRACKING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (114, 'MIS', 'SEARCHQUERY', 35938696, 'alter table MIS.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (115, 'MIS', 'SEARCHQUERY1', 19317437, 'alter table MIS.SEARCHQUERY1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (116, 'MIS', 'SEARCHQUERY2', 34350109, 'alter table MIS.SEARCHQUERY2 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (117, 'MIS', 'SEARCHQUERY3', 30929007, 'alter table MIS.SEARCHQUERY3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (118, 'MIS', 'SEARCHQUERY_16jul2010', 0, 'alter table MIS.SEARCHQUERY_16jul2010 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (119, 'MIS', 'SEARCHQUERY_APRIL2009', 0, 'alter table MIS.SEARCHQUERY_APRIL2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (120, 'MIS', 'SEARCHQUERY_MAY2009', 0, 'alter table MIS.SEARCHQUERY_MAY2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (121, 'MIS', 'SEARCHQUERY_NEW', 0, 'alter table MIS.SEARCHQUERY_NEW change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (122, 'MIS', 'SEARCHQUERY_TEMP', 0, 'alter table MIS.SEARCHQUERY_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (123, 'MIS', 'SEARCHQUERY_TEMP_OLD', 0, 'alter table MIS.SEARCHQUERY_TEMP_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (124, 'MIS', 'SEARCHQUERY_TRAC280', 0, 'alter table MIS.SEARCHQUERY_TRAC280 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (125, 'MIS', 'TRACK_ASTRO_DETAILS', 0, 'alter table MIS.TRACK_ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (126, 'MIS', 'VIEW_FOR_MIS', 849963, 'alter table MIS.VIEW_FOR_MIS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (127, 'MIS', 'WHY_FILTER', 33262012, 'alter table MIS.WHY_FILTER change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (128, 'MIS', 'new_table', 0, 'alter table MIS.new_table change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (129, 'newjs', 'ANNULLED', 33369, 'alter table newjs.ANNULLED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (130, 'newjs', 'ASTRO_DETAILS', 1130827, 'alter table newjs.ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (131, 'newjs', 'ASTRO_PULLING_REQUEST', 0, 'alter table newjs.ASTRO_PULLING_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (132, 'newjs', 'AUTOLOGIN_CONTACTS', 0, 'alter table newjs.AUTOLOGIN_CONTACTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (133, 'newjs', 'AUTOLOGIN_LOGIN', 10261, 'alter table newjs.AUTOLOGIN_LOGIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (135, 'newjs', 'CHAT_INVITATION', 8559, 'alter table newjs.CHAT_INVITATION change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (136, 'newjs', 'COMPATIBILITY', 0, 'alter table newjs.COMPATIBILITY change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (137, 'newjs', 'CONNECT', 0, 'alter table newjs.CONNECT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (138, 'newjs', 'CONTACTS_ONCE', 265380, 'alter table newjs.CONTACTS_ONCE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (141, 'newjs', 'CONTACTS_SEARCH', 0, 'alter table newjs.CONTACTS_SEARCH change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (142, 'newjs', 'CONTACTS_SEARCH2', 0, 'alter table newjs.CONTACTS_SEARCH2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (143, 'newjs', 'CONTACTS_SEARCH2_PREV', 0, 'alter table newjs.CONTACTS_SEARCH2_PREV change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (144, 'newjs', 'CONTACTS_SEARCH_NEW', 0, 'alter table newjs.CONTACTS_SEARCH_NEW change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (145, 'newjs', 'CONTACTS_SEARCH_NEW_1', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_1 change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (146, 'newjs', 'CONTACTS_SEARCH_NEW_PREV', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_PREV change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (147, 'newjs', 'CONTACTS_SEARCH_NEW_TEMP', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_TEMP change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (148, 'newjs', 'CONTACTS_SEARCH_PREV', 0, 'alter table newjs.CONTACTS_SEARCH_PREV change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (149, 'newjs', 'CONTACTS_SEARCH_TEMP', 0, 'alter table newjs.CONTACTS_SEARCH_TEMP change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (150, 'newjs', 'CONTACTS_SEARCH_TEMP2', 0, 'alter table newjs.CONTACTS_SEARCH_TEMP2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (151, 'newjs', 'CONTACTS_SHARD0', 0, 'alter table newjs.CONTACTS_SHARD0 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (152, 'newjs', 'CONTACTS_SHARD1', 0, 'alter table newjs.CONTACTS_SHARD1 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (153, 'newjs', 'CONTACTS_SHARD2', 0, 'alter table newjs.CONTACTS_SHARD2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (154, 'newjs', 'CONTACTS_STATUS', 1605994, 'alter table newjs.CONTACTS_STATUS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (155, 'newjs', 'CONTACTS_STATUS_TRACK', 0, 'alter table newjs.CONTACTS_STATUS_TRACK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (156, 'newjs', 'CONTACTS_TEMP', 1713278, 'alter table newjs.CONTACTS_TEMP change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (157, 'newjs', 'CONTACT_ARCHIVE', 13429066, 'alter table newjs.CONTACT_ARCHIVE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (158, 'newjs', 'CONTACT_LIMIT', 934110, 'alter table newjs.CONTACT_LIMIT change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (159, 'newjs', 'COSMO', 1523837, 'alter table newjs.COSMO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (160, 'newjs', 'CUSTOMISED_USERNAME', 0, 'alter table newjs.CUSTOMISED_USERNAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (161, 'newjs', 'DAILY_CONTACT_SMS', 0, 'alter table newjs.DAILY_CONTACT_SMS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (162, 'newjs', 'DELETED_BOOKMARKS', 0, 'alter table newjs.DELETED_BOOKMARKS change BOOKMARKER BOOKMARKER int(11) unsigned  DEFAULT ''0'' NOT NULL,change BOOKMARKEE BOOKMARKEE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (163, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD0', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD0 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (164, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD1', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD1 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (165, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD2', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (166, 'newjs', 'DISCOUNT_CODE', 1843300, 'alter table newjs.DISCOUNT_CODE change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (167, 'newjs', 'DISCOUNT_CODE_USED', 0, 'alter table newjs.DISCOUNT_CODE_USED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (168, 'newjs', 'DOUBLE_OPTIN', 0, 'alter table newjs.DOUBLE_OPTIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (169, 'newjs', 'DRAFTS', 432539, 'alter table newjs.DRAFTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (170, 'newjs', 'DUP_CONTACTS', 104158, 'alter table newjs.DUP_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (171, 'newjs', 'EDIT_LOG', 11460217, 'alter table newjs.EDIT_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (172, 'newjs', 'EDIT_LOG_JPC', 36454, 'alter table newjs.EDIT_LOG_JPC change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (173, 'newjs', 'EDIT_LOG_JPJ', 17003, 'alter table newjs.EDIT_LOG_JPJ change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (174, 'newjs', 'EDIT_LOG_JPM', 80725, 'alter table newjs.EDIT_LOG_JPM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (175, 'newjs', 'EDIT_LOG_JPP', 374, 'alter table newjs.EDIT_LOG_JPP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (176, 'newjs', 'EDIT_LOG_JPS', 31301, 'alter table newjs.EDIT_LOG_JPS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (177, 'newjs', 'FASHION', 212, 'alter table newjs.FASHION change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (178, 'newjs', 'FEATURED_PROFILE_LOG', 0, 'alter table newjs.FEATURED_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (179, 'newjs', 'FILTERS', 1775668, 'alter table newjs.FILTERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (180, 'newjs', 'FILTER_LOG', 11096735, 'alter table newjs.FILTER_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (181, 'newjs', 'FOLDERS', 48586, 'alter table newjs.FOLDERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (182, 'newjs', 'FREE_CONTACTED_PROFILE', 0, 'alter table newjs.FREE_CONTACTED_PROFILE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (183, 'newjs', 'GIF_PHOTO', 1667, 'alter table newjs.GIF_PHOTO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (184, 'newjs', 'HIDE_DOB', 3, 'alter table newjs.HIDE_DOB change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (185, 'newjs', 'HOMEPAGE_PHOTO', 343, 'alter table newjs.HOMEPAGE_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (186, 'newjs', 'HOMEPAGE_PROFILES', 0, 'alter table newjs.HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (187, 'newjs', 'HOROSCOPE_CAPTURE', 0, 'alter table newjs.HOROSCOPE_CAPTURE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (188, 'newjs', 'INCOMPLETE_PROFILES', 0, 'alter table newjs.INCOMPLETE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (189, 'newjs', 'INCREASE_RESPONSE', 0, 'alter table newjs.INCREASE_RESPONSE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (190, 'newjs', 'INSURANCE_MAIL', 5355, 'alter table newjs.INSURANCE_MAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (191, 'newjs', 'INVALID_EMAIL_MAILER', 0, 'alter table newjs.INVALID_EMAIL_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (192, 'newjs', 'INVALID_PHONE_MAILER', 0, 'alter table newjs.INVALID_PHONE_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (193, 'newjs', 'INVITEE', 17707, 'alter table newjs.INVITEE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (194, 'newjs', 'ISEARCH_CHECK', 1016, 'alter table newjs.ISEARCH_CHECK change DATA_PROFILEID DATA_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (195, 'newjs', 'JHOBBY', 2410658, 'alter table newjs.JHOBBY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (197, 'newjs', 'JPROFILE_ERRORS', 2902, 'alter table newjs.JPROFILE_ERRORS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (198, 'newjs', 'JPROFILE_OFFLINE', 3179, 'alter table newjs.JPROFILE_OFFLINE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (199, 'newjs', 'JPROFILE_PAGE3', 459778, 'alter table newjs.JPROFILE_PAGE3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (200, 'newjs', 'JP_CHRISTIAN', 96940, 'alter table newjs.JP_CHRISTIAN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (201, 'newjs', 'JP_JAIN', 29856, 'alter table newjs.JP_JAIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (202, 'newjs', 'JP_MUSLIM', 204070, 'alter table newjs.JP_MUSLIM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (203, 'newjs', 'JP_NTIMES', 4817806, 'alter table newjs.JP_NTIMES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (204, 'newjs', 'JP_PARSI', 2019, 'alter table newjs.JP_PARSI change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (205, 'newjs', 'JP_SIKH', 90269, 'alter table newjs.JP_SIKH change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (206, 'newjs', 'JS_PREDICTIVE', 1487827, 'alter table newjs.JS_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (207, 'newjs', 'KNWLARITYVNO', 51983, 'alter table newjs.KNWLARITYVNO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (208, 'newjs', 'KUNDALI_CAPTURE', 2919, 'alter table newjs.KUNDALI_CAPTURE change MATCH_BY MATCH_BY int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCH_TO MATCH_TO int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (209, 'newjs', 'LAST_LOGIN_PROFILES', 0, 'alter table newjs.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (210, 'newjs', 'LOGIN_DATA', 897691, 'alter table newjs.LOGIN_DATA change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (211, 'newjs', 'MATCHALERT_CONTACTS', 0, 'alter table newjs.MATCHALERT_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (212, 'newjs', 'MATCHALERT_PROFILEID', 0, 'alter table newjs.MATCHALERT_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (213, 'newjs', 'MATRIMONIAL_PHOTO', 0, 'alter table newjs.MATRIMONIAL_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (214, 'newjs', 'MATRIMONIAL_PHOTO1', 0, 'alter table newjs.MATRIMONIAL_PHOTO1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (215, 'newjs', 'NO_SIMILAR_PROFILES', 0, 'alter table newjs.NO_SIMILAR_PROFILES change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (216, 'newjs', 'OBSCENE_MESSAGE', 449595, 'alter table newjs.OBSCENE_MESSAGE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (217, 'newjs', 'OLDEMAIL', 525306, 'alter table newjs.OLDEMAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (218, 'newjs', 'OLD_CONTACTS', 1420968, 'alter table newjs.OLD_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (219, 'newjs', 'PAGE_VIEWS', 57638, 'alter table newjs.PAGE_VIEWS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (220, 'newjs', 'PHONE_VERIFY_CODE', 0, 'alter table newjs.PHONE_VERIFY_CODE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (221, 'newjs', 'PICTURE_OLD', 0, 'alter table newjs.PICTURE_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (222, 'newjs', 'PICTURE_TITLES', 222904, 'alter table newjs.PICTURE_TITLES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (223, 'newjs', 'PROFILEID_SERVER_MAPPING', 0, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (224, 'newjs', 'PROFILE_DEL_REASON', 0, 'alter table newjs.PROFILE_DEL_REASON change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (225, 'newjs', 'PROFILE_NAME', 115911, 'alter table newjs.PROFILE_NAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (226, 'newjs', 'PROMOTIONAL_MAIL', 4299107, 'alter table newjs.PROMOTIONAL_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (227, 'newjs', 'SEARCHQUERY', 194099, 'alter table newjs.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (228, 'newjs', 'SEARCHQUERY_TMP', 0, 'alter table newjs.SEARCHQUERY_TMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (229, 'newjs', 'SEARCH_AGENT', 313418, 'alter table newjs.SEARCH_AGENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (230, 'newjs', 'SEARCH_FEMALE_FULL1', 0, 'alter table newjs.SEARCH_FEMALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (231, 'newjs', 'SEARCH_MALE_FULL1', 0, 'alter table newjs.SEARCH_MALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (232, 'newjs', 'SENT_VERIFICATION_SMS', 0, 'alter table newjs.SENT_VERIFICATION_SMS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (233, 'newjs', 'SHOPPING_MAILER_DETAILS', 0, 'alter table newjs.SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (234, 'newjs', 'SIM_PROFILE_LOG', 6327673, 'alter table newjs.SIM_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (235, 'newjs', 'SIM_PROFILE_LOG_TEMP', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (236, 'newjs', 'SIM_PROFILE_LOG_TEMP1', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (240, 'newjs', 'SMS_CONTACT_LOG', 138230, 'alter table newjs.SMS_CONTACT_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (241, 'newjs', 'SMS_SEARCHLOG', 1074113, 'alter table newjs.SMS_SEARCHLOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (242, 'newjs', 'SMS_SUBSCRIPTION_DEACTIVATED', 0, 'alter table newjs.SMS_SUBSCRIPTION_DEACTIVATED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (243, 'newjs', 'SMS_TEMP_TABLE', 767240, 'alter table newjs.SMS_TEMP_TABLE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (245, 'newjs', 'STOCK_TRADING_MAIL', 0, 'alter table newjs.STOCK_TRADING_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (246, 'newjs', 'SWAP_FULL', 0, 'alter table newjs.SWAP_FULL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (247, 'newjs', 'SWAP_JPARTNER', 9355, 'alter table newjs.SWAP_JPARTNER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (248, 'newjs', 'SWAP_JPARTNER1', 0, 'alter table newjs.SWAP_JPARTNER1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (249, 'newjs', 'SWAP_JPARTNER_24FEB', 0, 'DROP table newjs.SWAP_JPARTNER_24FEB', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (250, 'newjs', 'SWAP_JPARTNER_ERROR', 0, 'alter table newjs.SWAP_JPARTNER_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (251, 'newjs', 'SWAP_JPROFILE', 18088, 'alter table newjs.SWAP_JPROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (252, 'newjs', 'SWAP_JPROFILE1', 0, 'alter table newjs.SWAP_JPROFILE1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (253, 'newjs', 'SWAP_JPROFILE_ERROR', 0, 'alter table newjs.SWAP_JPROFILE_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (254, 'newjs', 'SWAP_JPROFILE_ERROR_PIDS', 0, 'alter table newjs.SWAP_JPROFILE_ERROR_PIDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (255, 'newjs', 'SWAP_REV_24FEB', 88487, 'DROP table newjs.SWAP_REV_24FEB', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (256, 'newjs', 'SWAP_SEARCH_FULL1', 0, 'alter table newjs.SWAP_SEARCH_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (257, 'newjs', 'TEMP_HOMEPAGE_PROFILES', 0, 'alter table newjs.TEMP_HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (258, 'newjs', 'TEMP_PREDICTIVE', 1887821, 'alter table newjs.TEMP_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (259, 'newjs', 'TEMP_PROFILEID', 0, 'alter table newjs.TEMP_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (260, 'newjs', 'UNAVAILABLE_ASTRO_COUNTRY', 0, 'alter table newjs.UNAVAILABLE_ASTRO_COUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (261, 'newjs', 'UNMATCHED_NAKSHATRA_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_NAKSHATRA_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (262, 'newjs', 'UNMATCHED_RASHI_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_RASHI_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (263, 'newjs', 'UNMATCHED_SUNSIGN_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_SUNSIGN_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (264, 'newjs', 'USER_STARTS_PAYING', 0, 'alter table newjs.USER_STARTS_PAYING change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (265, 'newjs', 'VOUCHER_INTERMEDIATE_VIEWED', 0, 'alter table newjs.VOUCHER_INTERMEDIATE_VIEWED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (267, 'search_intel', 'ISCASTE', 471838, 'alter table search_intel.ISCASTE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (268, 'search_intel', 'ISCITY', 174108, 'alter table search_intel.ISCITY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (269, 'search_intel', 'ISCOUNTRY', 267802, 'alter table search_intel.ISCOUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (270, 'search_intel', 'ISEARCH', 198457, 'alter table search_intel.ISEARCH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (271, 'sms', 'SMSLOG', 735, 'alter table sms.SMSLOG change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (272, 'sms', 'TEMPSORT', 0, 'alter table sms.TEMPSORT change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (273, 'srch', 'JPARTNER', 102203, 'alter table srch.JPARTNER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (275, 'twowaymatch', 'TEMP_CALCULATE', 1110353, 'alter table twowaymatch.TEMP_CALCULATE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (276, 'twowaymatch', 'TRENDS', 1833904, 'alter table twowaymatch.TRENDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (277, 'twowaymatch', 'TRENDS_FOR_SPAM', 652396, 'alter table twowaymatch.TRENDS_FOR_SPAM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (278, 'userplane', 'CHAT_REQUESTS', 0, 'alter table userplane.CHAT_REQUESTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (279, 'userplane', 'CHECK_TABLE', 24586, 'alter table userplane.CHECK_TABLE change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (281, 'userplane', 'LOG_AD', 11767055, 'alter table userplane.LOG_AD change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (282, 'userplane', 'LOG_CHAT_REQUEST', 4082425, 'alter table userplane.LOG_CHAT_REQUEST change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (284, 'userplane', 'USERS_AD', 15045521, 'alter table userplane.USERS_AD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (286, 'userplane', 'blocked', 27713, 'alter table userplane.blocked change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL,change destinationUserID destinationUserID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_master_new` VALUES (292, 'userplane', 'users', 0, 'alter table userplane.users change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);





-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 02, 2012 at 02:37 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `test`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `master_table_slave_new`
-- 

CREATE TABLE `master_table_slave_new` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `DATABASE_NAME` text,
  `TABLE_NAME` text,
  `COUNT_ENTRIES` int(8) unsigned DEFAULT '0',
  `Query_name` text,
  `Done` char(1) DEFAULT 'N',
  `Pending` char(1) DEFAULT 'N',
  `Time` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `master_table_slave_new`
-- 

INSERT INTO `master_table_slave_new` VALUES (1, 'billing', 'BLUEDART_COD_REQUEST', 0, 'alter table billing.BLUEDART_COD_REQUEST change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (2, 'billing', 'EASY_BILL', 26418, 'alter table billing.EASY_BILL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (3, 'billing', 'FAILED_PAYMENT_MAILS', 0, 'alter table billing.FAILED_PAYMENT_MAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (4, 'billing', 'IVR_DETAILS', 48851, 'alter table billing.IVR_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (8, 'billing', 'OFFER_DISCOUNT', 777008, 'alter table billing.OFFER_DISCOUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (9, 'billing', 'OFFER_DISCOUNT_TEMP', 0, 'alter table billing.OFFER_DISCOUNT_TEMP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (12, 'billing', 'VOUCHER_OPTIN', 38365, 'alter table billing.VOUCHER_OPTIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (13, 'billing', 'VOUCHER_SUCCESSSTORY', 0, 'alter table billing.VOUCHER_SUCCESSSTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (21, 'incentive', 'CRM_VOICE_LOG', 184030, 'alter table incentive.CRM_VOICE_LOG change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (22, 'incentive', 'INBOUND_ALLOT', 108633, 'alter table incentive.INBOUND_ALLOT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (23, 'incentive', 'INVALID_PHONE_COUNT', 0, 'alter table incentive.INVALID_PHONE_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (24, 'incentive', 'MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS', 0, 'alter table incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (25, 'incentive', 'NAME_OF_USER', 3087501, 'alter table incentive.NAME_OF_USER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (26, 'incentive', 'PHONE_DAILY_VERIFICATION', 0, 'alter table incentive.PHONE_DAILY_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (27, 'incentive', 'PROFILE_ALLOCATION', 0, 'DROP table incentive.PROFILE_ALLOCATION ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (28, 'incentive', 'PROFILE_ALLOCATION1', 0, 'DROP table incentive.PROFILE_ALLOCATION1 ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (29, 'incentive', 'PROFILE_ALLOCATION2', 0, 'DROP table incentive.PROFILE_ALLOCATION2', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (30, 'incentive', 'PROFILE_ALLOCATION_TECH', 0, 'alter table incentive.PROFILE_ALLOCATION_TECH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (31, 'incentive', 'PROFILE_ALLOCATION_TEMP', 0, 'DROP table incentive.PROFILE_ALLOCATION_TEMP ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (32, 'incentive', 'PROFILE_ALTERNATE_NUMBER', 0, 'alter table incentive.PROFILE_ALTERNATE_NUMBER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (33, 'incentive', 'TEMP_ALLOCATION_BUCKET', 0, 'alter table incentive.TEMP_ALLOCATION_BUCKET change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (34, 'incentive', 'TEST_PROFILE_ALLOCATION', 0, 'DROP table incentive.TEST_PROFILE_ALLOCATION ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (35, 'incentive', 'UNALLOTED_FAILED_PAYMENT', 0, 'alter table incentive.UNALLOTED_FAILED_PAYMENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (37, 'infovision', 'INF_USER_PIN', 7945552, 'alter table infovision.INF_USER_PIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (38, 'infovision', 'SEARCHQUERY', 2008, 'alter table infovision.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (39, 'infovision', 'VIEW_COUNT', 2354, 'alter table infovision.VIEW_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (40, 'jsadmin', 'ADDRESS_VERIFICATION', 0, 'alter table jsadmin.ADDRESS_VERIFICATION change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (41, 'jsadmin', 'AFFILIATE_DATA', 251634, 'alter table jsadmin.AFFILIATE_DATA change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (42, 'jsadmin', 'ASSIGNED_101', 91, 'alter table jsadmin.ASSIGNED_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (43, 'jsadmin', 'ASSIGNLOG_101', 91, 'alter table jsadmin.ASSIGNLOG_101 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (44, 'jsadmin', 'COMPLETE_BY_SYSTEM', 0, 'alter table jsadmin.COMPLETE_BY_SYSTEM change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (45, 'jsadmin', 'CONTACTS_ALLOTED', 189769, 'alter table jsadmin.CONTACTS_ALLOTED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (46, 'jsadmin', 'CONTACTS_ALLOTED_HISTORY', 0, 'alter table jsadmin.CONTACTS_ALLOTED_HISTORY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (47, 'jsadmin', 'DELETED_BECOME_INCOMPLETE', 0, 'alter table jsadmin.DELETED_BECOME_INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (48, 'jsadmin', 'DELETED_OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.DELETED_OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (49, 'jsadmin', 'DELETED_PROFILES', 622327, 'alter table jsadmin.DELETED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (50, 'jsadmin', 'DEL_STATUS', 9179, 'alter table jsadmin.DEL_STATUS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (51, 'jsadmin', 'DUPLICATE_NUMBER_PROFILE', 0, 'alter table jsadmin.DUPLICATE_NUMBER_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (52, 'jsadmin', 'INCOMPLETE', 707766, 'alter table jsadmin.INCOMPLETE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (53, 'jsadmin', 'MARK_DELETE', 4041, 'alter table jsadmin.MARK_DELETE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (54, 'jsadmin', 'NON_SERIOUS_PROFILES', 0, 'alter table jsadmin.NON_SERIOUS_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (55, 'jsadmin', 'NON_SPAMMERS', 44, 'alter table jsadmin.NON_SPAMMERS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (56, 'jsadmin', 'OFFLINE_EMAIL', 1221, 'alter table jsadmin.OFFLINE_EMAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (57, 'jsadmin', 'OFFLINE_NUDGE_LOG', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (58, 'jsadmin', 'OFFLINE_NUDGE_LOG_BCK', 0, 'alter table jsadmin.OFFLINE_NUDGE_LOG_BCK change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (59, 'jsadmin', 'OFFLINE_OPERATOR_MESSAGES', 0, 'alter table jsadmin.OFFLINE_OPERATOR_MESSAGES change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change MATCH_ID MATCH_ID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (60, 'jsadmin', 'ON_HOLD_PROFILES', 128952, 'alter table jsadmin.ON_HOLD_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (61, 'jsadmin', 'PHONE_UNVERIFIED_LOG', 0, 'alter table jsadmin.PHONE_UNVERIFIED_LOG change PHONE_VERIFIED_LOG_ID PHONE_VERIFIED_LOG_ID int(11) unsigned  NOT NULL,change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (62, 'jsadmin', 'PHONE_VERIFIED_LOG', 0, 'alter table jsadmin.PHONE_VERIFIED_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (63, 'jsadmin', 'PROFILE_CHANGE_REQUEST', 0, 'alter table jsadmin.PROFILE_CHANGE_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (64, 'jsadmin', 'REPORT_INVALID_PHONE', 0, 'alter table jsadmin.REPORT_INVALID_PHONE change SUBMITTER SUBMITTER int(11) unsigned  NOT NULL,change SUBMITTEE SUBMITTEE int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (65, 'jsadmin', 'RETRIEVED_PROFILES', 0, 'alter table jsadmin.RETRIEVED_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (66, 'jsadmin', 'SCREENING_GRADES', 27904, 'alter table jsadmin.SCREENING_GRADES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);

INSERT INTO `master_table_slave_new` VALUES (70, 'jsadmin', 'SCREENING_LOG_OLD', 0, 'alter table jsadmin.SCREENING_LOG_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (71, 'jsadmin', 'SCREEN_TEMP_CHECK', 0, 'alter table jsadmin.SCREEN_TEMP_CHECK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (72, 'jsadmin', 'SPAMMERS', 52444, 'alter table jsadmin.SPAMMERS change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (73, 'jsadmin', 'SPAMMERS_BACKUP', 5965, 'alter table jsadmin.SPAMMERS_BACKUP change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (74, 'jsadmin', 'SPAMMERS_BACKUP_13AUG', 0, 'alter table jsadmin.SPAMMERS_BACKUP_13AUG change SPAMMER SPAMMER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (75, 'jsadmin', 'VIEW_CONTACTS_LOG', 0, 'alter table jsadmin.VIEW_CONTACTS_LOG change VIEWER VIEWER int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (76, 'jsmailer', 'MAILER', 45884, 'alter table jsmailer.MAILER change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (77, 'mailer', 'DISCOUNT_MAILER', 1550798, 'alter table mailer.DISCOUNT_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (78, 'mailer', 'DISCOUNT_MAILER_SMS', 0, 'alter table mailer.DISCOUNT_MAILER_SMS change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (79, 'mailer', 'MATRI_MAILER', 3152538, 'alter table mailer.MATRI_MAILER change PROFILE_ID PROFILE_ID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (80, 'mailer', 'MERCHANT_NAVY_MAILER', 0, 'alter table mailer.MERCHANT_NAVY_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (81, 'mailer', 'NEW_SHOPPING_MAILER_DETAILS', 0, 'alter table mailer.NEW_SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (82, 'marriage_bureau', 'BUREAU_PROFILE', 33, 'alter table marriage_bureau.BUREAU_PROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (83, 'marriage_bureau', 'CPP_UPDATE_LOG', 5, 'alter table marriage_bureau.CPP_UPDATE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (84, 'marriage_bureau', 'VIEWED', 2367, 'alter table marriage_bureau.VIEWED change AGAINST_PROFILE AGAINST_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (85, 'MATCHALERT_TRACKING', 'MA_HISTORY', 2181784, 'alter table MATCHALERT_TRACKING.MA_HISTORY change PID PID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (86, 'MIS', 'ASTRO_CLICK_COUNT', 0, 'alter table MIS.ASTRO_CLICK_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (87, 'MIS', 'ASTRO_COMMUNITY_WISE', 0, 'alter table MIS.ASTRO_COMMUNITY_WISE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (88, 'MIS', 'ASTRO_DATA_COUNT', 1147937, 'alter table MIS.ASTRO_DATA_COUNT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (89, 'MIS', 'ASTRO_IMAGE_TRACK', 0, 'alter table MIS.ASTRO_IMAGE_TRACK change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED_PROFILEID VIEWED_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (90, 'MIS', 'CONTACTS_FAULT_MONITOR', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (91, 'MIS', 'CONTACTS_FAULT_MONITOR1', 0, 'alter table MIS.CONTACTS_FAULT_MONITOR1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (92, 'MIS', 'CONTACTS_TEST_TEST', 0, 'alter table MIS.CONTACTS_TEST_TEST change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (93, 'MIS', 'DATESORT', 0, 'alter table MIS.DATESORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (94, 'MIS', 'FEMALE_BAND', 229140, 'alter table MIS.FEMALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (95, 'MIS', 'FORCE_SCREEN', 21, 'alter table MIS.FORCE_SCREEN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (96, 'MIS', 'INC_COUNT', 126424, 'alter table MIS.INC_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (97, 'MIS', 'INC_COUNT_BCK', 235981, 'alter table MIS.INC_COUNT_BCK change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (98, 'MIS', 'KUNDLI_MAILER_TRACKING', 0, 'alter table MIS.KUNDLI_MAILER_TRACKING change PROFILES_CONSIDERED PROFILES_CONSIDERED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (99, 'MIS', 'LANG_REGISTER', 1042, 'alter table MIS.LANG_REGISTER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (100, 'MIS', 'LOG_CONTACT_ERROR', 0, 'alter table MIS.LOG_CONTACT_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (101, 'MIS', 'MALE_BAND', 462117, 'alter table MIS.MALE_BAND change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (102, 'MIS', 'MAPPING_MTON_CITY', 0, 'alter table MIS.MAPPING_MTON_CITY change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (103, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (104, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD0', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD0 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (105, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD1', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (106, 'MIS', 'MATCHALERT_CONTACT_BY_RECOMEND_SHARD2', 0, 'alter table MIS.MATCHALERT_CONTACT_BY_RECOMEND_SHARD2 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (107, 'MIS', 'MYJS_PROFILING', 2328, 'alter table MIS.MYJS_PROFILING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (108, 'MIS', 'REDIFF_SRCH_REG', 14365, 'alter table MIS.REDIFF_SRCH_REG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (109, 'MIS', 'REG_COUNT', 3315835, 'alter table MIS.REG_COUNT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (110, 'MIS', 'REG_HOME', 653, 'alter table MIS.REG_HOME change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (111, 'MIS', 'REG_HOME_OLD', 581, 'alter table MIS.REG_HOME_OLD change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (112, 'MIS', 'RELSORT', 0, 'alter table MIS.RELSORT change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (113, 'MIS', 'REVERSE_FLAG_TRACKING', 0, 'alter table MIS.REVERSE_FLAG_TRACKING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (114, 'MIS', 'SEARCHQUERY', 35938696, 'alter table MIS.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (115, 'MIS', 'SEARCHQUERY1', 19317437, 'alter table MIS.SEARCHQUERY1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (116, 'MIS', 'SEARCHQUERY2', 34350109, 'alter table MIS.SEARCHQUERY2 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (117, 'MIS', 'SEARCHQUERY3', 30929007, 'alter table MIS.SEARCHQUERY3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (118, 'MIS', 'SEARCHQUERY_16jul2010', 0, 'alter table MIS.SEARCHQUERY_16jul2010 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (119, 'MIS', 'SEARCHQUERY_APRIL2009', 0, 'alter table MIS.SEARCHQUERY_APRIL2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (120, 'MIS', 'SEARCHQUERY_MAY2009', 0, 'alter table MIS.SEARCHQUERY_MAY2009 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (121, 'MIS', 'SEARCHQUERY_NEW', 0, 'alter table MIS.SEARCHQUERY_NEW change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (122, 'MIS', 'SEARCHQUERY_TEMP', 0, 'alter table MIS.SEARCHQUERY_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (123, 'MIS', 'SEARCHQUERY_TEMP_OLD', 0, 'alter table MIS.SEARCHQUERY_TEMP_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (124, 'MIS', 'SEARCHQUERY_TRAC280', 0, 'alter table MIS.SEARCHQUERY_TRAC280 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (125, 'MIS', 'TRACK_ASTRO_DETAILS', 0, 'alter table MIS.TRACK_ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (126, 'MIS', 'VIEW_FOR_MIS', 849963, 'alter table MIS.VIEW_FOR_MIS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (127, 'MIS', 'WHY_FILTER', 33262012, 'alter table MIS.WHY_FILTER change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (128, 'MIS', 'new_table', 0, 'alter table MIS.new_table change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (129, 'newjs', 'ANNULLED', 33369, 'alter table newjs.ANNULLED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (130, 'newjs', 'ASTRO_DETAILS', 1130827, 'alter table newjs.ASTRO_DETAILS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (131, 'newjs', 'ASTRO_PULLING_REQUEST', 0, 'alter table newjs.ASTRO_PULLING_REQUEST change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (132, 'newjs', 'AUTOLOGIN_CONTACTS', 0, 'alter table newjs.AUTOLOGIN_CONTACTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (133, 'newjs', 'AUTOLOGIN_LOGIN', 10261, 'alter table newjs.AUTOLOGIN_LOGIN change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (135, 'newjs', 'CHAT_INVITATION', 8559, 'alter table newjs.CHAT_INVITATION change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (136, 'newjs', 'COMPATIBILITY', 0, 'alter table newjs.COMPATIBILITY change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (137, 'newjs', 'CONNECT', 0, 'alter table newjs.CONNECT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (138, 'newjs', 'CONTACTS_ONCE', 265380, 'alter table newjs.CONTACTS_ONCE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (141, 'newjs', 'CONTACTS_SEARCH', 0, 'alter table newjs.CONTACTS_SEARCH change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (142, 'newjs', 'CONTACTS_SEARCH2', 0, 'alter table newjs.CONTACTS_SEARCH2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (143, 'newjs', 'CONTACTS_SEARCH2_PREV', 0, 'alter table newjs.CONTACTS_SEARCH2_PREV change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (144, 'newjs', 'CONTACTS_SEARCH_NEW', 0, 'alter table newjs.CONTACTS_SEARCH_NEW change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (145, 'newjs', 'CONTACTS_SEARCH_NEW_1', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_1 change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (146, 'newjs', 'CONTACTS_SEARCH_NEW_PREV', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_PREV change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (147, 'newjs', 'CONTACTS_SEARCH_NEW_TEMP', 0, 'alter table newjs.CONTACTS_SEARCH_NEW_TEMP change SENDER SENDER int(11) unsigned  NOT NULL,change RECEIVER RECEIVER int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (148, 'newjs', 'CONTACTS_SEARCH_PREV', 0, 'alter table newjs.CONTACTS_SEARCH_PREV change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (149, 'newjs', 'CONTACTS_SEARCH_TEMP', 0, 'alter table newjs.CONTACTS_SEARCH_TEMP change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (150, 'newjs', 'CONTACTS_SEARCH_TEMP2', 0, 'alter table newjs.CONTACTS_SEARCH_TEMP2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (151, 'newjs', 'CONTACTS_SHARD0', 0, 'alter table newjs.CONTACTS_SHARD0 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (152, 'newjs', 'CONTACTS_SHARD1', 0, 'alter table newjs.CONTACTS_SHARD1 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (153, 'newjs', 'CONTACTS_SHARD2', 0, 'alter table newjs.CONTACTS_SHARD2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (154, 'newjs', 'CONTACTS_STATUS', 1605994, 'alter table newjs.CONTACTS_STATUS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (155, 'newjs', 'CONTACTS_STATUS_TRACK', 0, 'alter table newjs.CONTACTS_STATUS_TRACK change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (156, 'newjs', 'CONTACTS_TEMP', 1713278, 'alter table newjs.CONTACTS_TEMP change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (157, 'newjs', 'CONTACT_ARCHIVE', 13429066, 'alter table newjs.CONTACT_ARCHIVE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (158, 'newjs', 'CONTACT_LIMIT', 934110, 'alter table newjs.CONTACT_LIMIT change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (159, 'newjs', 'COSMO', 1523837, 'alter table newjs.COSMO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (160, 'newjs', 'CUSTOMISED_USERNAME', 0, 'alter table newjs.CUSTOMISED_USERNAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (161, 'newjs', 'DAILY_CONTACT_SMS', 0, 'alter table newjs.DAILY_CONTACT_SMS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (162, 'newjs', 'DELETED_BOOKMARKS', 0, 'alter table newjs.DELETED_BOOKMARKS change BOOKMARKER BOOKMARKER int(11) unsigned  DEFAULT ''0'' NOT NULL,change BOOKMARKEE BOOKMARKEE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (163, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD0', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD0 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (164, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD1', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD1 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (165, 'newjs', 'DELETED_PROFILE_CONTACTS_SHARD2', 0, 'alter table newjs.DELETED_PROFILE_CONTACTS_SHARD2 change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (166, 'newjs', 'DISCOUNT_CODE', 1843300, 'alter table newjs.DISCOUNT_CODE change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (167, 'newjs', 'DISCOUNT_CODE_USED', 0, 'alter table newjs.DISCOUNT_CODE_USED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (168, 'newjs', 'DOUBLE_OPTIN', 0, 'alter table newjs.DOUBLE_OPTIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (169, 'newjs', 'DRAFTS', 432539, 'alter table newjs.DRAFTS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (170, 'newjs', 'DUP_CONTACTS', 104158, 'alter table newjs.DUP_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (171, 'newjs', 'EDIT_LOG', 11460217, 'alter table newjs.EDIT_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (172, 'newjs', 'EDIT_LOG_JPC', 36454, 'alter table newjs.EDIT_LOG_JPC change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (173, 'newjs', 'EDIT_LOG_JPJ', 17003, 'alter table newjs.EDIT_LOG_JPJ change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (174, 'newjs', 'EDIT_LOG_JPM', 80725, 'alter table newjs.EDIT_LOG_JPM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (175, 'newjs', 'EDIT_LOG_JPP', 374, 'alter table newjs.EDIT_LOG_JPP change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (176, 'newjs', 'EDIT_LOG_JPS', 31301, 'alter table newjs.EDIT_LOG_JPS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (177, 'newjs', 'FASHION', 212, 'alter table newjs.FASHION change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (178, 'newjs', 'FEATURED_PROFILE_LOG', 0, 'alter table newjs.FEATURED_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  NOT NULL,change VIEWED VIEWED int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (179, 'newjs', 'FILTERS', 1775668, 'alter table newjs.FILTERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (180, 'newjs', 'FILTER_LOG', 11096735, 'alter table newjs.FILTER_LOG change VIEWER VIEWER int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED VIEWED int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (181, 'newjs', 'FOLDERS', 48586, 'alter table newjs.FOLDERS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (182, 'newjs', 'FREE_CONTACTED_PROFILE', 0, 'alter table newjs.FREE_CONTACTED_PROFILE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (183, 'newjs', 'GIF_PHOTO', 1667, 'alter table newjs.GIF_PHOTO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (184, 'newjs', 'HIDE_DOB', 3, 'alter table newjs.HIDE_DOB change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (185, 'newjs', 'HOMEPAGE_PHOTO', 343, 'alter table newjs.HOMEPAGE_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (186, 'newjs', 'HOMEPAGE_PROFILES', 0, 'alter table newjs.HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (187, 'newjs', 'HOROSCOPE_CAPTURE', 0, 'alter table newjs.HOROSCOPE_CAPTURE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (188, 'newjs', 'INCOMPLETE_PROFILES', 0, 'alter table newjs.INCOMPLETE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (189, 'newjs', 'INCREASE_RESPONSE', 0, 'alter table newjs.INCREASE_RESPONSE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (190, 'newjs', 'INSURANCE_MAIL', 5355, 'alter table newjs.INSURANCE_MAIL change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (191, 'newjs', 'INVALID_EMAIL_MAILER', 0, 'alter table newjs.INVALID_EMAIL_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (192, 'newjs', 'INVALID_PHONE_MAILER', 0, 'alter table newjs.INVALID_PHONE_MAILER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (193, 'newjs', 'INVITEE', 17707, 'alter table newjs.INVITEE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (194, 'newjs', 'ISEARCH_CHECK', 1016, 'alter table newjs.ISEARCH_CHECK change DATA_PROFILEID DATA_PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (195, 'newjs', 'JHOBBY', 2410658, 'alter table newjs.JHOBBY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (197, 'newjs', 'JPROFILE_ERRORS', 2902, 'alter table newjs.JPROFILE_ERRORS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (198, 'newjs', 'JPROFILE_OFFLINE', 3179, 'alter table newjs.JPROFILE_OFFLINE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (199, 'newjs', 'JPROFILE_PAGE3', 459778, 'alter table newjs.JPROFILE_PAGE3 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (200, 'newjs', 'JP_CHRISTIAN', 96940, 'alter table newjs.JP_CHRISTIAN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (201, 'newjs', 'JP_JAIN', 29856, 'alter table newjs.JP_JAIN change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (202, 'newjs', 'JP_MUSLIM', 204070, 'alter table newjs.JP_MUSLIM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (203, 'newjs', 'JP_NTIMES', 4817806, 'alter table newjs.JP_NTIMES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (204, 'newjs', 'JP_PARSI', 2019, 'alter table newjs.JP_PARSI change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (205, 'newjs', 'JP_SIKH', 90269, 'alter table newjs.JP_SIKH change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (206, 'newjs', 'JS_PREDICTIVE', 1487827, 'alter table newjs.JS_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (207, 'newjs', 'KNWLARITYVNO', 51983, 'alter table newjs.KNWLARITYVNO change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (208, 'newjs', 'KUNDALI_CAPTURE', 2919, 'alter table newjs.KUNDALI_CAPTURE change MATCH_BY MATCH_BY int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCH_TO MATCH_TO int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (209, 'newjs', 'LAST_LOGIN_PROFILES', 0, 'alter table newjs.LAST_LOGIN_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (210, 'newjs', 'LOGIN_DATA', 897691, 'alter table newjs.LOGIN_DATA change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (211, 'newjs', 'MATCHALERT_CONTACTS', 0, 'alter table newjs.MATCHALERT_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (212, 'newjs', 'MATCHALERT_PROFILEID', 0, 'alter table newjs.MATCHALERT_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (213, 'newjs', 'MATRIMONIAL_PHOTO', 0, 'alter table newjs.MATRIMONIAL_PHOTO change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (214, 'newjs', 'MATRIMONIAL_PHOTO1', 0, 'alter table newjs.MATRIMONIAL_PHOTO1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (215, 'newjs', 'NO_SIMILAR_PROFILES', 0, 'alter table newjs.NO_SIMILAR_PROFILES change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (216, 'newjs', 'OBSCENE_MESSAGE', 449595, 'alter table newjs.OBSCENE_MESSAGE change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (217, 'newjs', 'OLDEMAIL', 525306, 'alter table newjs.OLDEMAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (218, 'newjs', 'OLD_CONTACTS', 1420968, 'alter table newjs.OLD_CONTACTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (219, 'newjs', 'PAGE_VIEWS', 57638, 'alter table newjs.PAGE_VIEWS change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (220, 'newjs', 'PHONE_VERIFY_CODE', 0, 'alter table newjs.PHONE_VERIFY_CODE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (221, 'newjs', 'PICTURE_OLD', 0, 'alter table newjs.PICTURE_OLD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (222, 'newjs', 'PICTURE_TITLES', 222904, 'alter table newjs.PICTURE_TITLES change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (223, 'newjs', 'PROFILEID_SERVER_MAPPING', 0, 'alter table newjs.PROFILEID_SERVER_MAPPING change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (224, 'newjs', 'PROFILE_DEL_REASON', 0, 'alter table newjs.PROFILE_DEL_REASON change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (225, 'newjs', 'PROFILE_NAME', 115911, 'alter table newjs.PROFILE_NAME change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (226, 'newjs', 'PROMOTIONAL_MAIL', 4299107, 'alter table newjs.PROMOTIONAL_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (227, 'newjs', 'SEARCHQUERY', 194099, 'alter table newjs.SEARCHQUERY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (228, 'newjs', 'SEARCHQUERY_TMP', 0, 'alter table newjs.SEARCHQUERY_TMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (229, 'newjs', 'SEARCH_AGENT', 313418, 'alter table newjs.SEARCH_AGENT change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (230, 'newjs', 'SEARCH_FEMALE_FULL1', 0, 'alter table newjs.SEARCH_FEMALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (231, 'newjs', 'SEARCH_MALE_FULL1', 0, 'alter table newjs.SEARCH_MALE_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (232, 'newjs', 'SENT_VERIFICATION_SMS', 0, 'alter table newjs.SENT_VERIFICATION_SMS change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (233, 'newjs', 'SHOPPING_MAILER_DETAILS', 0, 'alter table newjs.SHOPPING_MAILER_DETAILS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (234, 'newjs', 'SIM_PROFILE_LOG', 6327673, 'alter table newjs.SIM_PROFILE_LOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (235, 'newjs', 'SIM_PROFILE_LOG_TEMP', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (236, 'newjs', 'SIM_PROFILE_LOG_TEMP1', 0, 'alter table newjs.SIM_PROFILE_LOG_TEMP1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL,change VIEWED_PROFILE VIEWED_PROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (240, 'newjs', 'SMS_CONTACT_LOG', 138230, 'alter table newjs.SMS_CONTACT_LOG change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (241, 'newjs', 'SMS_SEARCHLOG', 1074113, 'alter table newjs.SMS_SEARCHLOG change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (242, 'newjs', 'SMS_SUBSCRIPTION_DEACTIVATED', 0, 'alter table newjs.SMS_SUBSCRIPTION_DEACTIVATED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (243, 'newjs', 'SMS_TEMP_TABLE', 767240, 'alter table newjs.SMS_TEMP_TABLE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (245, 'newjs', 'STOCK_TRADING_MAIL', 0, 'alter table newjs.STOCK_TRADING_MAIL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (246, 'newjs', 'SWAP_FULL', 0, 'alter table newjs.SWAP_FULL change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (247, 'newjs', 'SWAP_JPARTNER', 9355, 'alter table newjs.SWAP_JPARTNER change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (248, 'newjs', 'SWAP_JPARTNER1', 0, 'alter table newjs.SWAP_JPARTNER1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (249, 'newjs', 'SWAP_JPARTNER_24FEB', 0, 'DROP table newjs.SWAP_JPARTNER_24FEB', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (250, 'newjs', 'SWAP_JPARTNER_ERROR', 0, 'alter table newjs.SWAP_JPARTNER_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (251, 'newjs', 'SWAP_JPROFILE', 18088, 'alter table newjs.SWAP_JPROFILE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (252, 'newjs', 'SWAP_JPROFILE1', 0, 'alter table newjs.SWAP_JPROFILE1 change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (253, 'newjs', 'SWAP_JPROFILE_ERROR', 0, 'alter table newjs.SWAP_JPROFILE_ERROR change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (254, 'newjs', 'SWAP_JPROFILE_ERROR_PIDS', 0, 'alter table newjs.SWAP_JPROFILE_ERROR_PIDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (255, 'newjs', 'SWAP_REV_24FEB', 88487, 'DROP table newjs.SWAP_REV_24FEB\r\n', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (256, 'newjs', 'SWAP_SEARCH_FULL1', 0, 'alter table newjs.SWAP_SEARCH_FULL1 change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (257, 'newjs', 'TEMP_HOMEPAGE_PROFILES', 0, 'alter table newjs.TEMP_HOMEPAGE_PROFILES change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (258, 'newjs', 'TEMP_PREDICTIVE', 1887821, 'alter table newjs.TEMP_PREDICTIVE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (259, 'newjs', 'TEMP_PROFILEID', 0, 'alter table newjs.TEMP_PROFILEID change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (260, 'newjs', 'UNAVAILABLE_ASTRO_COUNTRY', 0, 'alter table newjs.UNAVAILABLE_ASTRO_COUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (261, 'newjs', 'UNMATCHED_NAKSHATRA_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_NAKSHATRA_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (262, 'newjs', 'UNMATCHED_RASHI_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_RASHI_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (263, 'newjs', 'UNMATCHED_SUNSIGN_MATCHASTRO', 0, 'alter table newjs.UNMATCHED_SUNSIGN_MATCHASTRO change PROFILEID PROFILEID int(11) unsigned ', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (264, 'newjs', 'USER_STARTS_PAYING', 0, 'alter table newjs.USER_STARTS_PAYING change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (265, 'newjs', 'VOUCHER_INTERMEDIATE_VIEWED', 0, 'alter table newjs.VOUCHER_INTERMEDIATE_VIEWED change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (267, 'search_intel', 'ISCASTE', 471838, 'alter table search_intel.ISCASTE change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (268, 'search_intel', 'ISCITY', 174108, 'alter table search_intel.ISCITY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (269, 'search_intel', 'ISCOUNTRY', 267802, 'alter table search_intel.ISCOUNTRY change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (270, 'search_intel', 'ISEARCH', 198457, 'alter table search_intel.ISEARCH change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (271, 'sms', 'SMSLOG', 735, 'alter table sms.SMSLOG change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (272, 'sms', 'TEMPSORT', 0, 'alter table sms.TEMPSORT change SMSUSER SMSUSER int(11) unsigned  DEFAULT ''0'' NOT NULL,change MATCHPROFILE MATCHPROFILE int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (273, 'srch', 'JPARTNER', 102203, 'alter table srch.JPARTNER change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (274, 'twowaymatch', 'TEMP_CALCULATE', 1110353, 'alter table twowaymatch.TEMP_CALCULATE change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (275, 'twowaymatch', 'TRENDS', 1833904, 'alter table twowaymatch.TRENDS change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (276, 'twowaymatch', 'TRENDS_FOR_SPAM', 652396, 'alter table twowaymatch.TRENDS_FOR_SPAM change PROFILEID PROFILEID int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (277, 'userplane', 'CHAT_REQUESTS', 0, 'alter table userplane.CHAT_REQUESTS change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (278, 'userplane', 'CHECK_TABLE', 24586, 'alter table userplane.CHECK_TABLE change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (280, 'userplane', 'LOG_AD', 11767055, 'alter table userplane.LOG_AD change SENDER SENDER int(11) unsigned  DEFAULT ''0'' NOT NULL,change RECEIVER RECEIVER int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (281, 'userplane', 'LOG_CHAT_REQUEST', 4082425, 'alter table userplane.LOG_CHAT_REQUEST change SEN SEN int(11) unsigned  NOT NULL,change REC REC int(11) unsigned  NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (283, 'userplane', 'USERS_AD', 15045521, 'alter table userplane.USERS_AD change PROFILEID PROFILEID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (285, 'userplane', 'blocked', 27713, 'alter table userplane.blocked change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL,change destinationUserID destinationUserID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
INSERT INTO `master_table_slave_new` VALUES (291, 'userplane', 'users', 0, 'alter table userplane.users change userID userID int(11) unsigned  DEFAULT ''0'' NOT NULL', 'N', 'N', 0);
