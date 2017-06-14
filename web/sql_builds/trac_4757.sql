-- 
-- Database: `jsadmin`
-- 

-- --------------------------------------------------------
USE jsadmin;
-- 
-- Table structure for table `PSWRDS`
-- 

CREATE TABLE `PSWRDS_LOG` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RESID` int(11) NOT NULL,
  `USERNAME` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `PASSWORD` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `EMAIL` varchar(50) NOT NULL DEFAULT '',
  `PRIVILAGE` varchar(500) DEFAULT 'none',
  `CENTER` varchar(20) NOT NULL DEFAULT '',
  `ACTIVE` char(1) NOT NULL DEFAULT '',
  `MOD_DT` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ENTRYBY` varchar(50) NOT NULL DEFAULT '',
  `PHONE` varchar(250) DEFAULT NULL,
  `SIGNATURE` text NOT NULL,
  `LAST_LOGIN_DT` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `COMPANY` varchar(10) NOT NULL DEFAULT 'JS',
  `EMP_ID` int(6) NOT NULL DEFAULT '0',
  `HEAD_ID` int(6) NOT NULL DEFAULT '0',
  `SUB_CENTER` varchar(20) NOT NULL,
  `FIRST_NAME` varchar(50) NOT NULL,
  `LAST_NAME` varchar(50) NOT NULL,
  `PHOTO_URL` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;