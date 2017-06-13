use search;

CREATE TABLE `MATCH_ALERT_LAST_VISIT` (
 `PROFILEID` int(11) unsigned NOT NULL,
 `LAST_VISITED_DT` datetime DEFAULT NULL,
 PRIMARY KEY (`PROFILEID`)
) ENGINE=MyISAM;
set session sql_log_bin=0;
ALTER TABLE `MATCH_ALERT_LAST_VISIT` ENGINE = innodb
set session sql_log_bin=1;


