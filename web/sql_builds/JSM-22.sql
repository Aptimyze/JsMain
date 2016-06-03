use PROFILE;

CREATE TABLE `CA_LAYER_DISPLAY_DATA` (
  `LAYERID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `PRIORITY` tinyint(3) unsigned DEFAULT NULL,
  `TIMES` tinyint(3) unsigned DEFAULT NULL,
  `TITLE` varchar(70) DEFAULT NULL,
  `BUTTON1` varchar(20) DEFAULT NULL,
  `BUTTON2` varchar(20) DEFAULT NULL,
  `ACTION1` varchar(150) DEFAULT NULL,
  `ACTION2` varchar(150) DEFAULT NULL,
  `TEXT` text,
  PRIMARY KEY (`LAYERID`),
  UNIQUE KEY `PRIORITY` (`PRIORITY`)
) ENGINE=MyISAM;

INSERT INTO `CA_LAYER_DISPLAY_DATA` VALUES (1, 1, 3, 'You have not uploaded your Photos yet', 'Yes, sure.', 'Not now, thanks.', '/social/addPhotos', 'close', 'People who upload their photos receive 8 times more relevant responses. Do you want to upload your photos now?');
INSERT INTO `CA_LAYER_DISPLAY_DATA` VALUES (2, 2, 3, 'You have not mentioned about your family in your profile yet', 'Yes, sure. ', 'Not now, thanks.', '/profile/viewprofile.php', 'close', 'You will receive more interests and acceptances from others if they know a little about your family. Do you want to mention a few things?');
INSERT INTO `CA_LAYER_DISPLAY_DATA` VALUES (3, 3, 3, 'You may want to respond to the Interests you have received', 'Yes, sure. ', 'Not now, thanks.', '/profile/contacts_made_received.php?page=eoi&filter=R', 'close', 'It is always nice to respond as soon as you can. Do you want to Accept or Decline the interests you have received?');
        
use MIS;

CREATE TABLE `CA_LAYER_TRACK` (
  `SNO` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) unsigned DEFAULT NULL,
  `LAYERID` smallint(6) DEFAULT NULL,
  `ENTRY_DT` datetime DEFAULT NULL,
  `BUTTON` enum('B1','B2') DEFAULT NULL,
  PRIMARY KEY (`SNO`),
  KEY `PROFILEID` (`PROFILEID`)
) ENGINE=MyISAM;
