use matchalerts;

ALTER TABLE  `LOG_TEMP` ADD INDEX (  `RECEIVER` );

use MATCHALERT_TRACKING;

CREATE TABLE  `LOGICLEVEL_PROFILES_SENT` (
 `DATE` DATE NOT NULL ,
 `COUNT` INT( 6 ) NOT NULL ,
 `LOGICLEVEL` SMALLINT( 2 ) NOT NULL ,
INDEX (  `DATE` )
)
