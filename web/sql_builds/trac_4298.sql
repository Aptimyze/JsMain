ALTER TABLE incentive.CRM_DAILY_ALLOT ADD `REAL_DE_ALLOCATION_DT` datetime NOT NULL DEFAULT '00-00-00 00:00:00' AFTER `DE_ALLOCATION_DT`;
ALTER TABLE incentive.CRM_DAILY_ALLOT_TRACK ADD `REAL_DE_ALLOCATION_DT` datetime NOT NULL DEFAULT '00-00-00 00:00:00' AFTER `DE_ALLOCATION_DT`;