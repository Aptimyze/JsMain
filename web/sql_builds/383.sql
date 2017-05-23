use newjs;

ALTER TABLE `JPROFILE` ADD `SERIOUSNESS_COUNT` TINYINT DEFAULT '1' NOT NULL;

use sugarcrm_housekeeping;

ALTER TABLE `connected_leads_cstm` ADD `seriousness_count_c` INT DEFAULT '1' NOT NULL ;

ALTER TABLE `inactive_leads_cstm` ADD `seriousness_count_c` INT DEFAULT '1' NOT NULL ;
