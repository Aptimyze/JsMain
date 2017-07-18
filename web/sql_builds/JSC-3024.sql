use billing;

CREATE TABLE billing.`EXCLUSIVE_SERVICING` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `AGENT_USERNAME` varchar(20) NOT NULL,
 `CLIENT_ID` int(11) NOT NULL,
 `ASSIGNED_DT` date NOT NULL,
 `ENTRY_DT` datetime DEFAULT NULL,
 `SERVICE_DAY` enum('NA','SUN','MON','TUE','WED','THUR','FRI','SAT') DEFAULT 'NA',
 `SERVICE_SET_DT` date DEFAULT "0000-00-00",
 `BIODATA_LOCATION` varchar(100) DEFAULT NULL,
 `BIODATA_UPLOAD_DT` datetime DEFAULT "0000-00-00 00:00:00",
 `SCREENED_DT` date DEFAULT "0000-00-00",
 `SCREENED_STATUS` enum('Y', 'N') DEFAULT 'N',
 PRIMARY KEY (`ID`),
 UNIQUE COMBINATION(`AGENT_USERNAME`,`CLIENT_ID`,`ASSIGNED_DT`),
 KEY AGENT_USERNAME(`AGENT_USERNAME`),
 KEY CLIENT_ID(`CLIENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE billing.`EXCLUSIVE_CLIENT_MEMBER_MAPPING` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `CLIENT_ID` int(11) NOT NULL,
 `MEMBER_ID` int(11) NOT NULL,
 `ENTRY_DT` datetime DEFAULT NULL,
 `SCREENED_STATUS` enum('Y', 'N') DEFAULT 'N',
 PRIMARY KEY (`ID`),
 KEY CLIENT_ID(`CLIENT_ID`)
) ENGINE=Myisam DEFAULT CHARSET=latin1;