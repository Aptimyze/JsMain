use test;
CREATE TABLE `VIEW_LOG_ACTIVE` (
 `VIEWER` int(8) unsigned NOT NULL default '0',
 `VIEWED` int(8) unsigned NOT NULL default '0',
 `DATE` date NOT NULL default '0000-00-00',
 `VIEWED_MMM` char(1) NOT NULL default 'N',
 UNIQUE KEY `UNI1` (`VIEWER`,`VIEWED`)
) ENGINE=MyISAM ; 







CREATE TABLE `VIEW_LOG_INACTIVE` (
 `VIEWER` int(8) unsigned NOT NULL default '0',
 `VIEWED` int(8) unsigned NOT NULL default '0',
 `DATE` date NOT NULL default '0000-00-00',
 `VIEWED_MMM` char(1) NOT NULL default 'N',
 UNIQUE KEY `UNI1` (`VIEWER`,`VIEWED`)
) ENGINE=MyISAM ; 



