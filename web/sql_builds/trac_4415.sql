use MATCHALERT_TRACKING;

TRUNCATE TABLE MATCHALERT_TRACKING.TRACK_EDIT_DPP;

ALTER TABLE MATCHALERT_TRACKING.TRACK_EDIT_DPP MODIFY COLUMN LOGIC VARCHAR(4);

ALTER TABLE MATCHALERT_TRACKING.TRACK_EDIT_DPP MODIFY COLUMN STATUS ENUM('V','E','WV','WE');

ALTER TABLE `TRACK_EDIT_DPP` DROP INDEX `DATE`;

ALTER TABLE `TRACK_EDIT_DPP` ADD UNIQUE `DATE` ( `PROFILEID` , `DATE` , `LOGIC` ) ;
