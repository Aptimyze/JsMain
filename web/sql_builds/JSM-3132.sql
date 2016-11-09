use PROFILE;
CREATE TABLE  `JUNK_CHARACTER_TEXT` (
 `id` INT( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
 `PROFILEID` INT( 11 ) unsigned NOT NULL ,
 `original_text` TEXT DEFAULT NULL ,
 `modified_custom` TEXT DEFAULT NULL ,
 `modified_automate` TEXT DEFAULT NULL ,
PRIMARY KEY (  `id` ) 
) ENGINE=MyISAM ;