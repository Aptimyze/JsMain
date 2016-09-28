use MIS;

CREATE TABLE  `OTP_LOG` (
 `ID` MEDIUMINT( 11 ) NOT NULL ,
 `DATE` DATETIME NOT NULL ,
 `PHONE_NO` VARCHAR( 13 ) NOT NULL ,
 `CHANNEL` CHAR( 2 ) NOT NULL ,
 `ISD` INT NOT NULL ,
PRIMARY KEY (  `ID` ) ,
INDEX (  `DATE` )
) COMMENT =  'OTP CHANNEL WISE LOGGING';