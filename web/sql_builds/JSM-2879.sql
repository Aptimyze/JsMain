USE REGISTER;
ALTER TABLE `REGISTRATION_QUALITY` ADD `SOURCECITY` VARCHAR( 10 ) AFTER `SOURCEID` ;
ALTER TABLE `REGISTRATION_QUALITY` DROP INDEX `DATE_SOURCE_UNIQUE` ,ADD UNIQUE `DATE_SOURCE_UNIQUE` ( `REG_DATE` , `SOURCEID` , `SOURCECITY` ) 
