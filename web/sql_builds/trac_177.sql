use newjs;
ALTER TABLE `JPROFILE` ADD `PROFILE_HANDLER_NAME` varchar(40),
        ADD `PARENT_PINCODE` Varchar(10) AFTER `PINCODE`,
        ADD `FAMILY_INCOME` tinyint(3) unsigned AFTER `FAMILY_STATUS`,
        ADD `THALASSEMIA` char(1) AFTER `HIV`,
        ADD `GOTHRA_MATERNAL` varchar(250) AFTER `GOTHRA`,
        ADD `GOING_ABROAD` char(1),
        ADD `OPEN_TO_PET` char(1),
        ADD `HAVE_CAR` char(1),
        ADD `OWN_HOUSE` char(1),
        ADD `SECT` tinyint(3) unsigned AFTER `CASTE`,
        ADD `COMPANY_NAME` text,
        ADD `SUNSIGN` tinyint(3) unsigned AFTER `RASHI`,
        ADD `HAVE_JCONTACT` enum('Y','N') NOT NULL default 'N',
        ADD `HAVE_JEDUCATION` enum('Y','N') NOT NULL default 'N';
		MODIFY `SCREENING` `SCREENING` BIGINT( 15 ) UNSIGNED DEFAULT '0';
        
ALTER TABLE `JHOBBY` ADD `FAV_MOVIE` text,
        ADD `FAV_TVSHOW` text,
        ADD `FAV_FOOD` text,
        ADD `FAV_BOOK` text,
        ADD `FAV_VAC_DEST` text;
        
CREATE TABLE `SECT` (
        `ID` smallint(3)  NOT NULL auto_increment,
        `PARENT_RELIGION` tinyint(3) unsigned NOT NULL,
        `LABEL` varchar(100) NOT NULL,
        `SMALL_LABEL` varchar(100),
        `VALUE` smallint(3) NOT NULL,
        `SORTBY` smallint(3) NOT NULL,
        PRIMARY KEY (`ID`)
       );
       
CREATE TABLE `JPROFILE_CONTACT` (
        `PROFILEID` int(11) unsigned NOT NULL,
        `ALT_MOBILE` Varchar(100),
        `ALT_MOBILE_ISD` varchar(5),
        `SHOWALT_MOBILE` char(1),
        `ALT_MOBILE_OWNER_NAME` varchar(40),
        `ALT_MOBILE_NUMBER_OWNER` Char(2),
        `ALT_MESSENGER_ID` Varchar(50),
        `ALT_MESSENGER_CHANNEL` Varchar(50),
        `SHOW_ALT_MESSENGER` char(1),
        `BLACKBERRY` Varchar(10),
        `LINKEDIN_URL` text,
        `FB_URL` text,
		`SHOWBLACKBERRY` CHAR( 1 ) ,
		`SHOWLINKEDIN` CHAR( 1 ) ,
		`SHOWFACEBOOK` CHAR( 1 ) ,
        PRIMARY KEY (`PROFILEID`)
        );
CREATE TABLE `JPROFILE_EDUCATION` (
        `PROFILEID` int(11) unsigned NOT NULL,
        `PG_COLLEGE` text,
        `PG_DEGREE` tinyint(3),
        `UG_DEGREE` tinyint(3),
        `OTHER_UG_DEGREE` varchar(250),
        `OTHER_PG_DEGREE` varchar(250),
        `SCHOOL` text,
        `COLLEGE` text,
        `OTHER_UG_COLLEGE` text,
        `OTHER_PG_COLLEGE` text,
        PRIMARY KEY (`PROFILEID`)
        );


INSERT INTO `SECT` VALUES (1, 7, 'Buddhist: Mahayana', '-Mahayana', 1, 1);
INSERT INTO `SECT` VALUES (2, 7, 'Buddhist:Vajrayana', '-Vajrayana', 2, 2);
INSERT INTO `SECT` VALUES (3, 7, 'Buddhist:Theravada', '-Theravada', 3, 3);
INSERT INTO `SECT` VALUES (4, 4, 'Sikh: Radha Soami', '-Radha Soami', 4, 4);
INSERT INTO `SECT` VALUES (5, 4, 'Sikh: Dera Follower', '-Dera Follower', 5, 5);
INSERT INTO `SECT` VALUES (6, 4, 'Sikh: Other', '-Other', 6, 52);
INSERT INTO `SECT` VALUES (7, 9, 'Jain: Agrawal', '-Agrawal', 7, 7);
INSERT INTO `SECT` VALUES (8, 9, 'Jain: Chaturth', '-Chaturth', 8, 8);
INSERT INTO `SECT` VALUES (9, 9, 'Jain: Khandelwal', '-Khandelwal', 9, 9);
INSERT INTO `SECT` VALUES (10, 9, 'Jain: Oswal', '-Oswal', 10, 10);
INSERT INTO `SECT` VALUES (11, 9, 'Jain: Pancham', '-Pancham', 11, 11);
INSERT INTO `SECT` VALUES (12, 9, 'Jain: Porwal', '-Porwal', 12, 12);
INSERT INTO `SECT` VALUES (13, 9, 'Jain: Shrimali', '-Shrimali', 13, 13);
INSERT INTO `SECT` VALUES (14, 9, 'Jain: Other', '-Other', 14, 14);
INSERT INTO `SECT` VALUES (15, 1, 'Hindu: Arya Samaji', '-Arya Samaji', 15, 15);
INSERT INTO `SECT` VALUES (16, 1, 'Hindu: Radha Soami', '-Radha Soami', 16, 16);
INSERT INTO `SECT` VALUES (17, 1, 'Hindu: Brahmo Samaj', '-Brahmo Samaj', 17, 17);
INSERT INTO `SECT` VALUES (18, 1, 'Hindu: Shaivism(Shiva Follower)', '-Shiva Follower', 18, 18);
INSERT INTO `SECT` VALUES (19, 1, 'Hindu: Vaishnav(Vishnu follower)', '-Vishnu follower', 19, 19);
INSERT INTO `SECT` VALUES (20, 2, 'Muslim: Ansari', '-Ansari', 20, 20);
INSERT INTO `SECT` VALUES (21, 2, 'Muslim: Arain', '-Arain', 21, 21);
INSERT INTO `SECT` VALUES (22, 2, 'Muslim: Awan', '-Awan', 22, 22);
INSERT INTO `SECT` VALUES (23, 2, 'Muslim: Barhai', '-Barhai', 23, 23);
INSERT INTO `SECT` VALUES (24, 2, 'Muslim: Bohra', '-Bohra', 24, 24);
INSERT INTO `SECT` VALUES (25, 2, 'Muslim: Chikwa', '-Chikwa', 25, 25);
INSERT INTO `SECT` VALUES (26, 2, 'Muslim: Dekkani', '-Dekkani', 26, 26);
INSERT INTO `SECT` VALUES (27, 2, 'Muslim: Dhunia', '-Dhunia', 27, 27);
INSERT INTO `SECT` VALUES (28, 2, 'Muslim: Dudekula', '-Dudekula', 28, 28);
INSERT INTO `SECT` VALUES (29, 2, 'Muslim: Hajjam', '-Hajjam', 29, 29);
INSERT INTO `SECT` VALUES (30, 2, 'Muslim: Hanafi', '-Hanafi', 30, 30);
INSERT INTO `SECT` VALUES (31, 2, 'Muslim: Jat', '-Jat', 31, 31);
INSERT INTO `SECT` VALUES (32, 2, 'Muslim: Kabaria', '-Kabaria', 32, 32);
INSERT INTO `SECT` VALUES (33, 2, 'Muslim: Khoja', '-Khoja', 33, 33);
INSERT INTO `SECT` VALUES (34, 2, 'Muslim: Kumhar ', '-Kumhar', 34, 34);
INSERT INTO `SECT` VALUES (35, 2, 'Muslim: Lebbai', '-Lebbai', 35, 35);
INSERT INTO `SECT` VALUES (36, 2, 'Muslim: Malik', '-Malik', 36, 36);
INSERT INTO `SECT` VALUES (37, 2, 'Muslim: Manihar', '-Manihar', 37, 37);
INSERT INTO `SECT` VALUES (38, 2, 'Muslim: Mapila', '-Mapila', 38, 38);
INSERT INTO `SECT` VALUES (39, 2, 'Muslim: Maraicar', '-Maraicar', 39, 39);
INSERT INTO `SECT` VALUES (40, 2, 'Muslim: Memon', '-Memon', 40, 40);
INSERT INTO `SECT` VALUES (41, 2, 'Muslim: Mughal', '-Mughal', 41, 41);
INSERT INTO `SECT` VALUES (42, 2, 'Muslim: Pathan', '-Pathan', 42, 42);
INSERT INTO `SECT` VALUES (43, 2, 'Muslim: Qureshi', '-Qureshi', 43, 43);
INSERT INTO `SECT` VALUES (44, 2, 'Muslim: Rajput', '-Rajput', 44, 44);
INSERT INTO `SECT` VALUES (45, 2, 'Muslim: Sheikh', '-Sheikh', 45, 45);
INSERT INTO `SECT` VALUES (46, 2, 'Muslim: Syed', '-Syed', 46, 46);
INSERT INTO `SECT` VALUES (47, 2, 'Muslim: Teli', '-Teli', 47, 47);
INSERT INTO `SECT` VALUES (48, 2, 'Muslim: Other', '-Other', 48, 48);
INSERT INTO `SECT` VALUES (49, 4, 'Sikh: Amritdhari', '-Amritdhari', 49, 48);
INSERT INTO `SECT` VALUES (50, 4, 'Sikh: Namdhari', '-Namdhari', 50, 49);
INSERT INTO `SECT` VALUES (51, 4, 'Sikh: Nirankari', '-Nirankari', 51, 50);
INSERT INTO `SECT` VALUES (52, 4, 'Sikh: Gurusikh', '-Gurusikh', 52, 51);


ALTER TABLE newjs.EDIT_LOG ADD `PROFILE_HANDLER_NAME` VARCHAR(40) NOT NULL,ADD `PARENT_PINCODE` VARCHAR(10) NOT NULL,ADD
`GOTHRA_MATERNAL` VARCHAR(250) NOT NULL,ADD `COMPANY_NAME` text NOT NULL,ADD `FAV_MOVIE` text NOT NULL,ADD `FAV_TVSHOW` text NOT
NULL,ADD `FAV_FOOD` text NOT NULL,ADD `FAV_COOK` text NOT NULL,ADD `FAV_VAC_DEST` text NOT NULL,ADD `BLACKBERRY` VARCHAR(10) NOT
NULL,ADD `LINKEDIN_URL` text NOT NULL,ADD `FB_URL` text NOT NULL,ADD `ALT_MOBILE` VARCHAR(40) NOT NULL,ADD `ALT_MOBILE_ISD`
VARCHAR(5) NOT NULL,ADD `ALT_MOBILE_OWNER_NAME` VARCHAR(40) NOT NULL,ADD `ALT_MOBILE_NUMBER_OWNER` CHAR(2) NOT NULL,ADD
`ALT_MESSENGER_ID` VARCHAR(50) NOT NULL,ADD `ALT_MESSENGER_CHANNEL` VARCHAR(50) NOT NULL,ADD `PG_COLLEGE` TEXT NOT NULL,ADD
`SCHOOL` TEXT NOT NULL,ADD `COLLEGE` TEXT NOT NULL,ADD `OTHER_UG_COLLEGE` TEXT NOT NULL,ADD `OTHER_PG_COLLEGE` TEXT NOT NULL,ADD
FAMILY_INCOME tinyint(2) NOT NULL, ADD THALASSEMIA CHAR(1) NOT NULL,ADD GOING_ABROAD CHAR(1) NOT NULL,ADD OPEN_TO_PET CHAR(1) NOT
NULL,ADD HAVE_CAR CHAR(1) NOT NULL,ADD OWN_HOUSE CHAR(1) NOT NULL,ADD SHOW_ALT_MESSENGER CHAR(1) NOT NULL,ADD SHOWALT_MOBILE
CHAR(1) NOT NULL,ADD SECT tinyint(3) NOT NULL, ADD PG_DEGREE tinyint(3) NOT NULL,ADD OTHER_UG_DEGREE varchar(250) NOT NULL, ADD
OTHER_PG_DEGREE varchar(250) NOT NULL, ADD UG_DEGREE TINYINT(3) NOT NULL,ADD SUNSIGN TINYINT(3), MODIFY `SCREENING` `SCREENING` BIGINT( 15 ) UNSIGNED DEFAULT '0';

TRUNCATE TABLE `DIOCESES`;

INSERT INTO `DIOCESES` VALUES (1, 'Amritsar');
INSERT INTO `DIOCESES` VALUES (2, 'Agra');
INSERT INTO `DIOCESES` VALUES (3, 'Andaman and Nicobar');
INSERT INTO `DIOCESES` VALUES (4, 'Andhra Pradesh');
INSERT INTO `DIOCESES` VALUES (5, 'Barrackpore');
INSERT INTO `DIOCESES` VALUES (6, 'Bhopal');
INSERT INTO `DIOCESES` VALUES (7, 'Chandigarh');
INSERT INTO `DIOCESES` VALUES (8, 'Chota Nagpur');
INSERT INTO `DIOCESES` VALUES (9, 'Coimbatore');
INSERT INTO `DIOCESES` VALUES (10, 'Cuttack');
INSERT INTO `DIOCESES` VALUES (11, 'Delhi');
INSERT INTO `DIOCESES` VALUES (12, 'Dornakal');
INSERT INTO `DIOCESES` VALUES (13, 'Durgapur');
INSERT INTO `DIOCESES` VALUES (14, 'East Kerala');
INSERT INTO `DIOCESES` VALUES (15, 'Eastern Himalaya');
INSERT INTO `DIOCESES` VALUES (16, 'Gujarat');
INSERT INTO `DIOCESES` VALUES (17, 'Jabalpur');
INSERT INTO `DIOCESES` VALUES (18, 'Kanyakumari');
INSERT INTO `DIOCESES` VALUES (19, 'Karimnagar');
INSERT INTO `DIOCESES` VALUES (20, 'Karnataka Northern');
INSERT INTO `DIOCESES` VALUES (21, 'Karnataka Central');
INSERT INTO `DIOCESES` VALUES (22, 'Karnataka Southern');
INSERT INTO `DIOCESES` VALUES (23, 'Kolhapur');
INSERT INTO `DIOCESES` VALUES (24, 'Kolkata');
INSERT INTO `DIOCESES` VALUES (25, 'Krishna-Godavari');
INSERT INTO `DIOCESES` VALUES (26, 'Lucknow');
INSERT INTO `DIOCESES` VALUES (27, 'Madhya Kerala');
INSERT INTO `DIOCESES` VALUES (28, 'Madras');
INSERT INTO `DIOCESES` VALUES (29, 'Madurai-Ramnad');
INSERT INTO `DIOCESES` VALUES (30, 'Marathwada');
INSERT INTO `DIOCESES` VALUES (31, 'Medak');
INSERT INTO `DIOCESES` VALUES (32, 'Mumbai');
INSERT INTO `DIOCESES` VALUES (33, 'Nagpur');
INSERT INTO `DIOCESES` VALUES (34, 'Nandyal');
INSERT INTO `DIOCESES` VALUES (35, 'Nasik');
INSERT INTO `DIOCESES` VALUES (36, 'North East');
INSERT INTO `DIOCESES` VALUES (37, 'North Kerala');
INSERT INTO `DIOCESES` VALUES (38, 'Patna');
INSERT INTO `DIOCESES` VALUES (39, 'Phulbani');
INSERT INTO `DIOCESES` VALUES (40, 'Pune');
INSERT INTO `DIOCESES` VALUES (41, 'Rajasthan');
INSERT INTO `DIOCESES` VALUES (42, 'Rayalaseema');
INSERT INTO `DIOCESES` VALUES (43, 'Sambalpur');
INSERT INTO `DIOCESES` VALUES (44, 'South Kerala');
INSERT INTO `DIOCESES` VALUES (45, 'Tiruchirapalli');
INSERT INTO `DIOCESES` VALUES (46, 'Tirunelveli');
INSERT INTO `DIOCESES` VALUES (47, 'Tuticorin ');
INSERT INTO `DIOCESES` VALUES (48, 'Vellore');

