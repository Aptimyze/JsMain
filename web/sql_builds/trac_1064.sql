use jsadmin;
CREATE TABLE  `PremiumUsers` (
 `PID` INT( 11 ) NOT NULL ,
 `DID` INT( 11 ) NOT NULL ,
 `DATE` DATETIME NOT NULL,
 UNIQUE KEY `DID` (`DID`)
);
