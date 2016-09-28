use Assisted_Product;
ALTER TABLE  `AP_TEMP_DPP` ADD  `STATE` TEXT DEFAULT NULL ;

ALTER TABLE  `AP_DPP_FILTER_ARCHIVE` ADD  `STATE` TEXT DEFAULT NULL ;


use newjs;
CREATE TABLE `TOP_CITY_INDIA_NEW` (
 `ID` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
 `LABEL` varchar(200) DEFAULT NULL,
 `VALUE` text NOT NULL,
 `SORTBY` smallint(6) unsigned DEFAULT '0',
 PRIMARY KEY (`ID`)
)ENGINE=MyISAM;

INSERT INTO `TOP_CITY_INDIA_NEW` (`ID`, `LABEL`, `VALUE`, `SORTBY`) VALUES ('1', 'New Delhi', 'DE00', '1'), ('2', 'Mumbai', 'MH04','2'), ('3', 'Bangalore', 'KA02','3'), ('4', 'Hyderabad/Secunderabad', 'AP03','4'), ('5', 'Pune/ Chinchwad', 'MH08','5'), ('6', 'Chennai', 'TN02','6'), ('7', 'Kolkata', 'WB05','7'), ('8', 'Delhi NCR', "DE00,UP25,HA03,HA02,UP12,UP47,UP48",'8'), ('9', 'Mumbai Region', "MH04,MH12,MH28,MH29",'9');