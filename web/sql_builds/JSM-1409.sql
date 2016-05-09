CREATE TABLE PICTURE.`COVER_PHOTO` (
 `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) unsigned NOT NULL,
 `PHOTOID` char(4) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE (
`PROFILEID`
)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE PICTURE.`COVER_PHOTO_URL` (
`ID` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`PHOTOID` CHAR( 4 ) NOT NULL ,
`PHOTO_URL` VARCHAR( 40 ) NOT NULL ,
PRIMARY KEY ( `ID` )
);


INSERT INTO PICTURE.`COVER_PHOTO_URL` ( `PHOTOID` , `PHOTO_URL` )
VALUES 
(
'SP01', '/images/jspc/viewProfileImg/SP/SP01.jpg'
),
(
'SP02', '/images/jspc/viewProfileImg/SP/SP02.jpg'
),
(
'SP03', '/images/jspc/viewProfileImg/SP/SP03.jpg'
),
(
'SP04', '/images/jspc/viewProfileImg/SP/SP04.jpg'
),
(
'SP05', '/images/jspc/viewProfileImg/SP/SP05.jpg'
),
(
'SP06', '/images/jspc/viewProfileImg/SP/SP06.jpg'
),
(
'SP07', '/images/jspc/viewProfileImg/SP/SP07.jpg'
),
(
'SP08', '/images/jspc/viewProfileImg/SP/SP08.jpg'
),
(
'SP09', '/images/jspc/viewProfileImg/SP/SP09.jpg'
),
(
'SP10', '/images/jspc/viewProfileImg/SP/SP10.jpg'
),
(
'SP11', '/images/jspc/viewProfileImg/SP/SP11.jpg'
),
(
'SP12', '/images/jspc/viewProfileImg/SP/SP12.jpg'
),
(
'CK01', '/images/jspc/viewProfileImg/CK/CK01.jpg'
),
(
'CK02', '/images/jspc/viewProfileImg/CK/CK02.jpg'
),
(
'CK03', '/images/jspc/viewProfileImg/CK/CK03.jpg'
),
(
'CK04', '/images/jspc/viewProfileImg/CK/CK04.jpg'
),
(
'CK05', '/images/jspc/viewProfileImg/CK/CK05.jpg'
),
(
'CK06', '/images/jspc/viewProfileImg/CK/CK06.jpg'
),
(
'CK07', '/images/jspc/viewProfileImg/CK/CK07.jpg'
),
(
'CK08', '/images/jspc/viewProfileImg/CK/CK08.jpg'
),
(
'CK09', '/images/jspc/viewProfileImg/CK/CK09.jpg'
),
(
'CK10', '/images/jspc/viewProfileImg/CK/CK10.jpg'
),
(
'CK11', '/images/jspc/viewProfileImg/CK/CK11.jpg'
),
(
'CK12', '/images/jspc/viewProfileImg/CK/CK12.jpg'
),
(
'MD01', '/images/jspc/viewProfileImg/MD/MD01.jpg'
),
(
'MD02', '/images/jspc/viewProfileImg/MD/MD02.jpg'
),
(
'MD03', '/images/jspc/viewProfileImg/MD/MD03.jpg'
),
(
'MD04', '/images/jspc/viewProfileImg/MD/MD04.jpg'
),
(
'MD05', '/images/jspc/viewProfileImg/MD/MD05.jpg'
),
(
'MD06', '/images/jspc/viewProfileImg/MD/MD06.jpg'
),
(
'MD07', '/images/jspc/viewProfileImg/MD/MD07.jpg'
),
(
'MD08', '/images/jspc/viewProfileImg/MD/MD08.jpg'
),
(
'MD09', '/images/jspc/viewProfileImg/MD/MD09.jpg'
),
(
'MD10', '/images/jspc/viewProfileImg/MD/MD10.jpg'
),
(
'MD11', '/images/jspc/viewProfileImg/MD/MD11.jpg'
),
(
'MD12', '/images/jspc/viewProfileImg/MD/MD12.jpg'
),
(
'TR01', '/images/jspc/viewProfileImg/TR/TR01.jpg'
),
(
'TR02', '/images/jspc/viewProfileImg/TR/TR02.jpg'
),
(
'TR03', '/images/jspc/viewProfileImg/TR/TR03.jpg'
),
(
'TR04', '/images/jspc/viewProfileImg/TR/TR04.jpg'
),
(
'TR05', '/images/jspc/viewProfileImg/TR/TR05.jpg'
),
(
'TR06', '/images/jspc/viewProfileImg/TR/TR06.jpg'
),
(
'TR07', '/images/jspc/viewProfileImg/TR/TR07.jpg'
),
(
'TR08', '/images/jspc/viewProfileImg/TR/TR08.jpg'
),
(
'TR09', '/images/jspc/viewProfileImg/TR/TR09.jpg'
),
(
'TR10', '/images/jspc/viewProfileImg/TR/TR10.jpg'
),
(
'TR11', '/images/jspc/viewProfileImg/TR/TR11.jpg'
),
(
'TR12', '/images/jspc/viewProfileImg/TR/TR12.jpg'
),
(
'PH01', '/images/jspc/viewProfileImg/PH/PH01.jpg'
),
(
'PH02', '/images/jspc/viewProfileImg/PH/PH02.jpg'
),
(
'PH03', '/images/jspc/viewProfileImg/PH/PH03.jpg'
),
(
'PH04', '/images/jspc/viewProfileImg/PH/PH04.jpg'
),
(
'PH05', '/images/jspc/viewProfileImg/PH/PH05.jpg'
),
(
'PH06', '/images/jspc/viewProfileImg/PH/PH06.jpg'
),
(
'PH07', '/images/jspc/viewProfileImg/PH/PH07.jpg'
),
(
'PH08', '/images/jspc/viewProfileImg/PH/PH08.jpg'
),
(
'PH09', '/images/jspc/viewProfileImg/PH/PH09.jpg'
),
(
'PH10', '/images/jspc/viewProfileImg/PH/PH10.jpg'
),
(
'PH11', '/images/jspc/viewProfileImg/PH/PH11.jpg'
),
(
'PH12', '/images/jspc/viewProfileImg/PH/PH12.jpg'
),
(
'BO01', '/images/jspc/viewProfileImg/BO/BO01.jpg'
),
(
'BO02', '/images/jspc/viewProfileImg/BO/BO02.jpg'
),
(
'BO03', '/images/jspc/viewProfileImg/BO/BO03.jpg'
),
(
'BO04', '/images/jspc/viewProfileImg/BO/BO04.jpg'
),
(
'BO05', '/images/jspc/viewProfileImg/BO/BO05.jpg'
),
(
'BO06', '/images/jspc/viewProfileImg/BO/BO06.jpg'
),
(
'BO07', '/images/jspc/viewProfileImg/BO/BO07.jpg'
),
(
'BO08', '/images/jspc/viewProfileImg/BO/BO08.jpg'
),
(
'BO09', '/images/jspc/viewProfileImg/BO/BO09.jpg'
),
(
'BO10', '/images/jspc/viewProfileImg/BO/BO10.jpg'
),
(
'BO11', '/images/jspc/viewProfileImg/BO/BO11.jpg'
),
(
'BO12', '/images/jspc/viewProfileImg/BO/BO12.jpg'
),
(
'NA01', '/images/jspc/viewProfileImg/NA/NA01.jpg'
),
(
'NA02', '/images/jspc/viewProfileImg/NA/NA02.jpg'
),
(
'NA03', '/images/jspc/viewProfileImg/NA/NA03.jpg'
),
(
'NA04', '/images/jspc/viewProfileImg/NA/NA04.jpg'
),
(
'NA05', '/images/jspc/viewProfileImg/NA/NA05.jpg'
),
(
'NA06', '/images/jspc/viewProfileImg/NA/NA06.jpg'
),
(
'NA07', '/images/jspc/viewProfileImg/NA/NA07.jpg'
),
(
'NA08', '/images/jspc/viewProfileImg/NA/NA08.jpg'
),
(
'NA09', '/images/jspc/viewProfileImg/NA/NA09.jpg'
),
(
'NA10', '/images/jspc/viewProfileImg/NA/NA10.jpg'
),
(
'NA11', '/images/jspc/viewProfileImg/NA/NA11.jpg'
),
(
'NA12', '/images/jspc/viewProfileImg/NA/NA12.jpg'
),
(
'PE01', '/images/jspc/viewProfileImg/PE/PE01.jpg'
),
(
'PE02', '/images/jspc/viewProfileImg/PE/PE02.jpg'
),
(
'PE03', '/images/jspc/viewProfileImg/PE/PE03.jpg'
),
(
'PE04', '/images/jspc/viewProfileImg/PE/PE04.jpg'
),
(
'PE05', '/images/jspc/viewProfileImg/PE/PE05.jpg'
),
(
'PE06', '/images/jspc/viewProfileImg/PE/PE06.jpg'
),
(
'PE07', '/images/jspc/viewProfileImg/PE/PE07.jpg'
),
(
'PE08', '/images/jspc/viewProfileImg/PE/PE08.jpg'
),
(
'PE09', '/images/jspc/viewProfileImg/PE/PE09.jpg'
),
(
'PE10', '/images/jspc/viewProfileImg/PE/PE10.jpg'
),
(
'PE11', '/images/jspc/viewProfileImg/PE/PE11.jpg'
),
(
'PE12', '/images/jspc/viewProfileImg/PE/PE12.jpg'
),
(
'TE01', '/images/jspc/viewProfileImg/TE/TE01.jpg'
),
(
'TE02', '/images/jspc/viewProfileImg/TE/TE02.jpg'
),
(
'TE03', '/images/jspc/viewProfileImg/TE/TE03.jpg'
),
(
'TE04', '/images/jspc/viewProfileImg/TE/TE04.jpg'
),
(
'TE05', '/images/jspc/viewProfileImg/TE/TE05.jpg'
),
(
'TE06', '/images/jspc/viewProfileImg/TE/TE06.jpg'
),
(
'TE07', '/images/jspc/viewProfileImg/TE/TE07.jpg'
),
(
'TE08', '/images/jspc/viewProfileImg/TE/TE08.jpg'
),
(
'TE09', '/images/jspc/viewProfileImg/TE/TE09.jpg'
),
(
'TE10', '/images/jspc/viewProfileImg/TE/TE10.jpg'
),
(
'TE11', '/images/jspc/viewProfileImg/TE/TE11.jpg'
),
(
'TE12', '/images/jspc/viewProfileImg/TE/TE12.jpg'
),
(
'PU01', '/images/jspc/viewProfileImg/PU/PU01.jpg'
),
(
'PU02', '/images/jspc/viewProfileImg/PU/PU02.jpg'
),
(
'PU03', '/images/jspc/viewProfileImg/PU/PU03.jpg'
),
(
'PU04', '/images/jspc/viewProfileImg/PU/PU04.jpg'
),
(
'PU05', '/images/jspc/viewProfileImg/PU/PU05.jpg'
),
(
'PU06', '/images/jspc/viewProfileImg/PU/PU06.jpg'
),
(
'PU07', '/images/jspc/viewProfileImg/PU/PU07.jpg'
),
(
'PU08', '/images/jspc/viewProfileImg/PU/PU08.jpg'
),
(
'PU09', '/images/jspc/viewProfileImg/PU/PU09.jpg'
),
(
'PU10', '/images/jspc/viewProfileImg/PU/PU10.jpg'
),
(
'PU11', '/images/jspc/viewProfileImg/PU/PU11.jpg'
),
(
'PU12', '/images/jspc/viewProfileImg/PU/PU12.jpg'
),
(
'GA01', '/images/jspc/viewProfileImg/GA/GA01.jpg'
),
(
'GA02', '/images/jspc/viewProfileImg/GA/GA02.jpg'
),
(
'GA03', '/images/jspc/viewProfileImg/GA/GA03.jpg'
),
(
'GA04', '/images/jspc/viewProfileImg/GA/GA04.jpg'
),
(
'GA05', '/images/jspc/viewProfileImg/GA/GA05.jpg'
),
(
'GA06', '/images/jspc/viewProfileImg/GA/GA06.jpg'
),
(
'GA07', '/images/jspc/viewProfileImg/GA/GA07.jpg'
),
(
'GA08', '/images/jspc/viewProfileImg/GA/GA08.jpg'
),
(
'GA09', '/images/jspc/viewProfileImg/GA/GA09.jpg'
),
(
'GA10', '/images/jspc/viewProfileImg/GA/GA10.jpg'
),
(
'GA11', '/images/jspc/viewProfileImg/GA/GA11.jpg'
),
(
'GA12', '/images/jspc/viewProfileImg/GA/GA12.jpg'
),
(
'TV01', '/images/jspc/viewProfileImg/TV/TV01.jpg'
),
(
'TV02', '/images/jspc/viewProfileImg/TV/TV02.jpg'
),
(
'TV03', '/images/jspc/viewProfileImg/TV/TV03.jpg'
),
(
'TV04', '/images/jspc/viewProfileImg/TV/TV04.jpg'
),
(
'TV05', '/images/jspc/viewProfileImg/TV/TV05.jpg'
),
(
'TV06', '/images/jspc/viewProfileImg/TV/TV06.jpg'
),
(
'TV07', '/images/jspc/viewProfileImg/TV/TV07.jpg'
),
(
'TV08', '/images/jspc/viewProfileImg/TV/TV08.jpg'
),
(
'TV09', '/images/jspc/viewProfileImg/TV/TV09.jpg'
),
(
'TV10', '/images/jspc/viewProfileImg/TV/TV10.jpg'
),
(
'TV11', '/images/jspc/viewProfileImg/TV/TV11.jpg'
),
(
'TV12', '/images/jspc/viewProfileImg/TV/TV12.jpg'
);

CREATE TABLE PICTURE.`COVER_PHOTO_CATEGORIES` (
`ID` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`CATEGORY_NAME` VARCHAR( 30 ) NOT NULL ,
`CATEGORY_ID` CHAR( 2 ) NOT NULL ,
PRIMARY KEY ( `ID` )
);

INSERT INTO PICTURE.`COVER_PHOTO_CATEGORIES` VALUES (null, 'Sports', 'SP'),
(null, 'Cooking', 'CK'),
(null, 'Music/Dance', 'MD'),
(null, 'Travel', 'TR'),
(null, 'Photography', 'PH'),
(null, 'Books', 'BO'),
(null, 'Nature', 'NA'),
(null, 'Pets', 'PE'),
(null, 'Techie', 'TE'),
(null, 'Puzzles', 'PU'),
(null, 'Gardening', 'GA'),
(null, 'TV/Movies', 'TV'); 