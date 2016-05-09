
/*** remember class in bms_comfig.php */
/***remember bmsVariable in config file*/
use bms2;
ALTER TABLE `BANNER` ADD `BannerJsVd` VARCHAR( 255 ) NOT NULL; 
ALTER TABLE `BANNER` ADD `BannerJsProfileStatus` VARCHAR( 255 ) NOT NULL ,
ADD `BannerJsMailID` VARCHAR( 255 ) NOT NULL ,
ADD `BannerJsEoiStatus` VARCHAR( 255 ) NOT NULL ;

UPDATE ZONE SET ZoneCriterias=CONCAT(ZoneCriterias,',VARIABLE_DISCOUNT,PROFILE_STATUS,GMAIL_ID,EOI_STATUS')  WHERE   ZoneCriterias='AGE,GENDER,LOCATION,INCOME,SUBSCRIPTION,RELIGION,EDUCATION,OCCUPATION,COMMUNITY,MARITALSTATUS'  OR ZoneCriterias='IP,AGE,GENDER,LOCATION,INCOME,SUBSCRIPTION,RELIGION,EDUCATION,OCCUPATION,COMMUNITY,MARITALSTATUS';

RENAME TABLE CRITERIA_MAPPING TO CRITERIA_MAPPING_BEFORE_TRAC113;
CREATE TABLE `CRITERIA_MAPPING` (
  `CriteriaId` smallint(6) NOT NULL auto_increment,
  `CriteriaName` varchar(255) NOT NULL default '',
  `IP` enum('Y','N') NOT NULL default 'N',
  `AGE` enum('Y','N') NOT NULL default 'N',
  `GENDER` enum('Y','N') NOT NULL default 'N',
  `LOCATION` enum('Y','N') NOT NULL default 'N',
  `INCOME` enum('Y','N') NOT NULL default 'N',
  `SUBSCRIPTION` enum('Y','N') NOT NULL default 'Y',
  `RELIGION` enum('Y','N') NOT NULL default 'Y',
  `EDUCATION` enum('Y','N') NOT NULL default 'Y',
  `OCCUPATION` enum('Y','N') NOT NULL default 'Y',
  `COMMUNITY` enum('Y','N') NOT NULL default 'Y',
  `MARITALSTATUS` enum('Y','N') NOT NULL default 'Y',
  `VARIABLE_DISCOUNT` enum('Y','N') NOT NULL,
  `PROFILE_STATUS` enum('Y','N') NOT NULL,
  `GMAIL_ID` enum('Y','N') NOT NULL,
  `EOI_STATUS` enum('Y','N') NOT NULL,
  PRIMARY KEY  (`CriteriaId`)
) ENGINE=MyISAM;

INSERT INTO `CRITERIA_MAPPING` VALUES (1, 'No Criteria', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
INSERT INTO `CRITERIA_MAPPING` VALUES (2, 'Only IP', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
INSERT INTO `CRITERIA_MAPPING` VALUES (3, 'Search Based', 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y');
INSERT INTO `CRITERIA_MAPPING` VALUES (4, 'Logged In', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y');

INSERT INTO `EDUCATION` VALUES (25, 'BAMS', 25, 4, 5);
INSERT INTO `EDUCATION` VALUES (26, 'BHMS', 26, 4, 6);

use newjs;
CREATE TABLE `ANALYTICS_VARIABLE_DISCOUNT` (
  `PROFILEID` int(11) unsigned NOT NULL,
  `SLAB` smallint(2) NOT NULL,
  `SLAB_DETAILS` varchar(60) default NULL,
  PRIMARY KEY  (`PROFILEID`)
) ENGINE=MyISAM;

CREATE TABLE `ANALYTICS_EOI_STATUS` (
  `PROFILEID` int(11) unsigned NOT NULL,
  `SLAB` smallint(2) NOT NULL,
  `SLAB_DETAILS` varchar(60) default NULL,
  PRIMARY KEY  (`PROFILEID`)
) ENGINE=MyISAM;
