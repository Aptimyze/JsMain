-- Database: `MIS`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `LTF`
-- 

DROP TABLE IF EXISTS `LTF`;
CREATE TABLE `LTF` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) NOT NULL,
  `TYPE` char(5) NOT NULL,
  `EXECUTIVE` varchar(50) NOT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PROFILEID` (`PROFILEID`,`TYPE`)
);


