use sugarcrm;

UPDATE `fields_meta_data` SET `default_value` = '' WHERE `id` IN ('Leadsgender_c','Leadsmanglik_c','Leadsdrink_c','Leadssmoke_c','Leadswork_c');

use sugarcrm_housekeeping;

ALTER TABLE `connected_leads_cstm` ADD `horoscope_dob_time_c` DATETIME NOT NULL;

ALTER TABLE `inactive_leads_cstm` ADD `horoscope_dob_time_c` DATETIME NOT NULL;
