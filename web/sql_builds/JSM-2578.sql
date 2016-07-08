use MIS;
INSERT INTO  `MIS_MAINPAGE` (  `ID` ,  `NAME` ,  `MAIN_URL` ,  `JUMP_URL` ,  `PRIVILEGE` ,  `ACTIVE` ,  `PUBLIC` ) 
VALUES (
'',  'Screening Queue Counts',  '/operations.php/registerMis/ScreeningCountMis?cid=$cid', NULL ,  'P+MG',  'Y',  ''
);
CREATE TABLE `SCREENING_QUEUE_COUNTS` (
 `DATE` date NOT NULL,
 `AT_HOUR` int(11) NOT NULL,
 `PROFILE_NEW` int(11) NOT NULL,
 `PROFILE_EDIT` int(11) NOT NULL,
 `PHOTO_ACCEPT_REJ_NEW` int(11) NOT NULL,
 `PHOTO_ACCEPT_REJ_EDIT` int(11) NOT NULL,
 `PHOTO_PROCESS_NEW` int(11) NOT NULL,
 `PHOTO_PROCESS_EDIT` int(11) NOT NULL,
 PRIMARY KEY (`DATE`,`AT_HOUR`)
);
