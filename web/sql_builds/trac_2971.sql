use incentive;
CREATE TABLE `PHONE_OPS_DIALER_DATA` (
 `NUMBER_CALLED` varchar(11) DEFAULT NULL,
 `NUMBER_DISPLAY` varchar(11) NOT NULL,
 `LEAD_ID` varchar(5) NOT NULL,
 `USERNAME` varchar(12) NOT NULL,
 `NAME` varchar(40) DEFAULT NULL,
 `SCREENED_TIME` datetime NOT NULL,
 `EMAIL` varchar(25) NOT NULL,
 `ENTRY_TIME` datetime NOT NULL,
 PRIMARY KEY (`USERNAME`)
);