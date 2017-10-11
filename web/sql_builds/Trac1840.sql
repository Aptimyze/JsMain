use test;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH`
-- 

CREATE TABLE `CONTACTS_SEARCH` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH2`
-- 

CREATE TABLE `CONTACTS_SEARCH2` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH2_PREV`
-- 

CREATE TABLE `CONTACTS_SEARCH2_PREV` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_NEW`
-- 

CREATE TABLE `CONTACTS_SEARCH_NEW` (
  `SENDER` mediumint(11) unsigned NOT NULL,
  `RECEIVER` mediumint(11) unsigned NOT NULL,
  `RECEIVER_TOTAL_POINTS` smallint(2) NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_NEW_1`
-- 

CREATE TABLE `CONTACTS_SEARCH_NEW_1` (
  `SENDER` mediumint(11) unsigned NOT NULL,
  `RECEIVER` mediumint(11) unsigned NOT NULL,
  `RECEIVER_TOTAL_POINTS` smallint(2) NOT NULL DEFAULT '0',
  KEY `SENDER` (`SENDER`,`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_NEW_PREV`
-- 

CREATE TABLE `CONTACTS_SEARCH_NEW_PREV` (
  `SENDER` mediumint(11) unsigned NOT NULL,
  `RECEIVER` mediumint(11) unsigned NOT NULL,
  `RECEIVER_TOTAL_POINTS` smallint(2) NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_NEW_TEMP`
-- 

CREATE TABLE `CONTACTS_SEARCH_NEW_TEMP` (
  `SENDER` mediumint(11) unsigned NOT NULL,
  `RECEIVER` mediumint(11) unsigned NOT NULL,
  `RECEIVER_TOTAL_POINTS` smallint(2) NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_PREV`
-- 

CREATE TABLE `CONTACTS_SEARCH_PREV` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_TEMP`
-- 

CREATE TABLE `CONTACTS_SEARCH_TEMP` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_TEMP2`
-- 

CREATE TABLE `CONTACTS_SEARCH_TEMP2` (
  `SENDER` mediumint(11) NOT NULL DEFAULT '0',
  `RECEIVER` mediumint(11) NOT NULL DEFAULT '0',
  `WEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `RECEIVER_INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CONTACT_SCORE` double NOT NULL,
  UNIQUE KEY `SENDER` (`SENDER`,`RECEIVER`,`WEIGHT`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACTS_SEARCH_TEMP3`
-- 

CREATE TABLE `CONTACTS_SEARCH_TEMP3` (
  `PROFILEID` int(11) NOT NULL,
  `RESID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `LAST_LOGIN_PROFILES`
-- 

CREATE TABLE `LAST_LOGIN_PROFILES` (
  `PROFILEID` int(11) unsigned NOT NULL DEFAULT '0',
  `DATE` date NOT NULL DEFAULT '0000-00-00',
  KEY `PROFILEID` (`PROFILEID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `TEMPRECEIVER`
-- 

CREATE TABLE `TEMPRECEIVER` (
  `receiverId` mediumint(11) DEFAULT NULL,
  KEY `id` (`receiverId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `TEMPRECEIVER_PREV`
-- 

CREATE TABLE `TEMPRECEIVER_PREV` (
  `receiverId` mediumint(11) DEFAULT NULL,
  KEY `id` (`receiverId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `TEMPSENDER`
-- 

CREATE TABLE `TEMPSENDER` (
  `senderId` mediumint(11) DEFAULT NULL,
  `numSent` mediumint(11) DEFAULT NULL,
  KEY `id` (`senderId`),
  KEY `num` (`numSent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `TEMPSENDER_PREV`
-- 

CREATE TABLE `TEMPSENDER_PREV` (
  `senderId` mediumint(11) DEFAULT NULL,
  `numSent` mediumint(11) DEFAULT NULL,
  KEY `id` (`senderId`),
  KEY `num` (`numSent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
