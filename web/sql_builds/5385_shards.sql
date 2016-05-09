/*Need to run on slave only*/
use test;
CREATE TABLE `INACTIVE_RECORDS_6_MONTHS_FOR_CRON` (
  `PROFILEID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`PROFILEID`)
) ENGINE=MyISAM;
