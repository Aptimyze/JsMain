use billing;
CREATE TABLE billing.`DOL_BILLING_USERS_FOR_TEST` ( `ID` INT( 11 ) NOT NULL AUTO_INCREMENT , `PROFILEID` INT( 11 ) NOT NULL , `ENTRY_DT` DATETIME NOT NULL ,PRIMARY KEY (  `ID` ) , UNIQUE KEY `PROFILEID` (`PROFILEID`)) COMMENT =  'To store profileids for test environment to display dollar for payments';
