use newjs;
CREATE TABLE IF NOT EXISTS `LOGIN_TRACKING` (
  `PREVIOUS_PID` int(11) NOT NULL,
  `NEW_PID` int(11) NOT NULL,
  `SOURCE` varchar(15) NOT NULL,
  `DATE` datetime NOT NULL
)
        
