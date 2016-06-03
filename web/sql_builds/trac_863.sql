use duplicates;
CREATE TABLE `DUPLICATE_IDS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `DUPLICATE_PROFILES`
-- 

CREATE TABLE `DUPLICATE_PROFILES` (
  `DUPLICATE_ID` int(11) NOT NULL,
  `PROFILEID` int(11) NOT NULL,
  UNIQUE KEY `PROFILEID` (`PROFILEID`),
  KEY `DUPLICATE_ID` (`DUPLICATE_ID`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `DUPLICATE_PROFILE_LOG`
-- 

CREATE TABLE `DUPLICATE_PROFILE_LOG` (
  `PROFILE1` int(11) NOT NULL,
  `PROFILE2` int(11) NOT NULL,
  `REASON` set('EMAIL','CRAWLER','MESSENGER','IPADDRESS','PHOTO','TEXT','PHONE','ID_PROOF','LINKEDIN','FACEBOOK','BLACKBERRY','EMAIL_PREFIX') NOT NULL,
  `ENTRY_DATE` datetime NOT NULL,
  `IS_DUPLICATE` enum('YES','NO','PROBABLE','CANTSAY') NOT NULL,
  `SCREENED_BY` varchar(50) DEFAULT NULL,
  `COMMENTS` text NOT NULL,
  `SCREENED_ACTION` enum('NONE','IN','OUT','HOLD','SKIP') NOT NULL,
  KEY `PROFILE1` (`PROFILE1`),
  KEY `PROFILE2` (`PROFILE2`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `MARK_NOT_DUPLICATE`
-- 

CREATE TABLE `MARK_NOT_DUPLICATE` (
  `PROFILE1` int(11) NOT NULL,
  `PROFILE2` int(11) NOT NULL,
  `REASON` set('EMAIL','CRAWLER','MESSENGER','IPADDRESS','PHOTO','TEXT','PHONE','ID_PROOF','LINKEDIN','FACEBOOK','BLACKBERRY','EMAIL_PREFIX') NOT NULL,
  UNIQUE KEY `PROFILE1` (`PROFILE1`,`PROFILE2`)
);
CREATE TABLE PERMANENT_NOT_DUPLICATE LIKE  `MARK_NOT_DUPLICATE`;
-- --------------------------------------------------------

-- 
-- Table structure for table `NOT_DUPLICATE`
-- 

CREATE TABLE `NOT_DUPLICATE` (
  `PROFILEID` int(11) NOT NULL,
  `ENTRY_DATE` datetime NOT NULL
);

-- --------------------------------------------------------

-- 
-- Table structure for table `NOT_DUPLICATE_LOG`
-- 

CREATE TABLE `NOT_DUPLICATE_LOG` (
  `PROFILEID` int(11) DEFAULT NULL,
  `ENTRY_DATE` datetime NOT NULL,
  `ENTRY_BY` varchar(50) DEFAULT NULL,
  `COMMENTS` text NOT NULL,
  KEY `PROFILE1` (`PROFILEID`)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `PROBABLE_DUPLICATES`
-- 

CREATE TABLE `PROBABLE_DUPLICATES` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PROFILE1` int(11) NOT NULL,
  `PROFILE2` int(11) NOT NULL,
  `REASON` set('EMAIL','CRAWLER','MESSENGER','IPADDRESS','PHOTO','TEXT','PHONE','ID_PROOF','LINKEDIN','FACEBOOK','BLACKBERRY','EMAIL_PREFIX') NOT NULL,
  `ENTRY_DATE` datetime NOT NULL,
  `CURRENT_STATE` enum('PROBABLE','CANTSAY') NOT NULL DEFAULT 'PROBABLE',
  `SCREEN_ACTION` enum('SKIP','HOLD','NONE') NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PROFILE1` (`PROFILE1`,`PROFILE2`)
);
