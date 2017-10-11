use newjs;

ALTER TABLE  `PROFILE_DEL_REASON` CHANGE  `DEL_REASON`  `DEL_REASON` ENUM(  'I found my match on Jeevansathi.com',  'I found my match elsewhere',  'I am unhappy about services',  'I found my match from other website',  'Other reasons','I found my match on another matrimonial site','I am unhappy with Jeevansathi.com services') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

