use duplicates;

-- --------------------------------------------------------

-- 
-- For table `PROBABLE_DUPLICATES`
-- 
ALTER TABLE `PROBABLE_DUPLICATES` CHANGE `SCREEN_ACTION` `SCREEN_ACTION` ENUM( 'SKIP', 'HOLD', 'NONE', 'IN', 'OUT' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL ;

-- --------------------------------------------------------

-- 
-- For table `DUPLICATE_PROFILE_LOG`
--
ALTER TABLE `DUPLICATE_PROFILE_LOG` ADD `MARKED_BY` ENUM( 'SYSTEM', 'EXECUTIVE', 'SUPERVISOR' ) NOT NULL ;
ALTER TABLE `DUPLICATE_PROFILE_LOG` ADD `IDENTIFIED_ON` DATETIME NOT NULL AFTER `ENTRY_DATE` ;
