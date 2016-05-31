use MIS;
DROP TABLE IF EXISTS PHONE_LAYER_TRACKING;
CREATE TABLE PHONE_LAYER_TRACKING (
 `PROFILEID` int(12) NOT NULL,
 `COUNT_EOI` int(10) NOT NULL DEFAULT '0',
 `COUNT_VIEW_CONTACT` int(10) NOT NULL DEFAULT '0',
 `COUNT_OTHERS` int(10) NOT NULL DEFAULT '0',
 `LAST_CLICK_ON` varchar(22) DEFAULT NULL,
 `LAST_CLICK_DATE` datetime DEFAULT NULL,
 PRIMARY KEY (`PROFILEID`)
) ENGINE=MyISAM;