/* run on shard server */

USE newjs;

CREATE TABLE `LOG_LOGOUT_HISTORY` (
 `PROFILEID` mediumint(11) NOT NULL DEFAULT '0',
 `TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `IPADDR` varchar(16) NOT NULL
)
