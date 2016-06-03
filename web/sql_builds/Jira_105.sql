Use PICTURE

CREATE TABLE `PictureUploadCheck` (
 `PROFILEID` int(11) unsigned NOT NULL,
 `ENTRY_DT` date NOT NULL DEFAULT '0000-00-00',
 `UPLOAD_PAGE` tinyint(6) unsigned DEFAULT '0',
 `ALBUM_PAGE` tinyint(6) unsigned DEFAULT '0',
 `RETRY` tinyint(6) unsigned DEFAULT '0',
 PRIMARY KEY (`PROFILEID`,`ENTRY_DT`)
) ENGINE=MyISAM;


CREATE TABLE `ErrorForPictureUpload` (
 `PROFILEID` int(11) unsigned DEFAULT NULL,
 `ENTRY_DT` date DEFAULT '0000-00-00',
 `error_type` varchar(10) DEFAULT NULL,
 `error_msg` varchar(100) DEFAULT NULL,
 KEY `PROFILEID` (`PROFILEID`)
) ENGINE=MyISAM;
