use newjs;
ALTER REGISTRATION_PAGE1 ADD COLUMN `PHONE_MOB` varchar(100) NOT NULL;
create database reg;
use reg;
CREATE TABLE `REG_EDIT_PAGE_FIELDS` (
  `PAGE` varchar(15) NOT NULL,
  `FIELD_ID` smallint(6) NOT NULL,
  `GROUP` char(1) NOT NULL,
  `TABLE_NAME` varchar(100) DEFAULT 'JPROFILE',
  `LABEL` varchar(140) DEFAULT NULL,
  `BLANK_VALUE` varchar(30) NOT NULL,
  `BLANK_LABEL` varchar(100) NOT NULL
);

-- 
-- Dumping data for table `REG_EDIT_PAGE_FIELDS`
-- 

INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 1, 'a', 'REGISTRATION_PAGE1:EMAIL', 'Your Email', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 2, 'a', 'REGISTRATION_PAGE1:PASSWORD', 'Password', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 3, 'a', 'REGISTRATION_PAGE1:RELATION', 'Create Profile For', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 4, 'b', 'REGISTRATION_PAGE1:GENDER', 'Gender', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 5, 'b', 'REGISTRATION_PAGE1:DTOFBIRTH', 'Date of Birth', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 8, 'b', 'JPROFILE:HEIGHT', 'Height<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 9, 'b', 'JPROFILE:COUNTRY_RES', 'Country living in<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 10, 'b', 'JPROFILE:CITY_RES', 'City<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 11, 'c', 'JPROFILE:MSTATUS', 'Marital Status<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 12, 'c', 'JPROFILE:HAVECHILD', 'Have Children :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 13, 'c', 'REGISTRATION_PAGE1:MTONGUE', 'Mother Tongue', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 14, 'c', 'JPROFILE:RELIGION', 'Religion<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 15, 'c', 'JPROFILE:CASTE', 'Caste<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 18, 'd', 'REGISTRATION_PAGE1:ISD-isd,PHONE_MOB-mobile', 'Mobile Number', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 21, 'd', 'JPROFILE:ISD-isd,STD-std,PHONE_RES-landline', 'LandLine Number :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 22, 'e', 'JPROFILE:PROMO', 'Send me third-party marketing ', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 16, 'e', 'JPROFILE:SOURCE', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 35, 'a', 'JPROFILE:EDU_LEVEL_NEW', 'Highest Degree<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 30, 'a', 'JPROFILE:OCCUPATION', 'Work Area<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 34, 'a', 'JPROFILE:INCOME', 'Annual Income<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 24, 'a', 'JPROFILE:DIET', 'Diet :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 26, 'a', 'JPROFILE:DRINK', 'Drink :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 25, 'a', 'JPROFILE:SMOKE', 'Smoke :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 27, 'a', 'JPROFILE:COMPLEXION', 'Complexion :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 37, 'a', 'JPROFILE:RES_STATUS', 'Resident Status :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 28, 'a', 'JPROFILE:BTYPE', 'Body Type :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP2', 38, 'a', 'JPROFILE:YOURINFO', 'Write about your<u>*</u> :<br/> Education<br/> Work<br/> Family', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 39, 'a', 'JPROFILE:EDUCATION', 'Educational Background :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 40, 'a', 'JPROFILE:JOB_INFO', 'Professional Background :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 41, 'a', 'JPROFILE:WORK_STATUS', 'Work Status :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 43, 'b', 'JPROFILE:BLOOD_GROUP', 'Blood Group :', '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 44, 'b', 'JPROFILE:HIV', 'HIV :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 42, 'b', 'JPROFILE:HANDICAPPED', 'Challenged :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 46, 'b', 'JPROFILE:MESSENGER_ID', 'Messenger ID :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 49, 'b', 'JPROFILE:MESSENGER_CHANNEL', 'Messenger Channel', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 47, 'b', 'JPROFILE:SHOWMESSENGER', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 48, 'b', 'JPROFILE:SHOWADDRESS', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 45, 'b', 'JPROFILE:CONTACT', 'Your Contact Address :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 50, '', 'JPARTNER:P_LAGE', 'Age :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 14, 'a', 'JPROFILE:RELIGION', 'Religion', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 15, 'a', 'JPROFILE:CASTE', 'Caste', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 11, 'a', 'JPROFILE:MSTATUS', 'Marital Status', '', 'Select Marital Status');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 12, 'a', 'JPROFILE:HAVECHILD', 'Have Children', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 8, 'a', 'JPROFILE:HEIGHT', 'Height', '', 'Select Height');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 9, 'a', 'JPROFILE:COUNTRY_RES', 'Country living in', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 51, '', 'JPARTNER:P_HAGE', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 57, '', 'JPARTNER:P_HDS', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 52, '', 'JPARTNER:P_LHEIGHT', 'Height :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 53, '', 'JPARTNER:P_HHEIGHT', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 56, '', 'JPARTNER:P_LDS', 'Annual Income :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 54, '', 'JPARTNER:P_LRS', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 55, '', 'JPARTNER:P_HRS', '', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP4', 58, 'b', 'JPROFILE:NATURE_HANDICAP', 'Nature of handicap :', '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 59, 'a', 'FILTERS:AGE', 'check Age filter is set or not', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 60, 'a', 'FILTERS:MSTATUS', 'check Mstatus filter is set or', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 61, 'a', 'FILTERS:RELIGION', 'check RELIGION filter is set o', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 62, 'a', 'FILTERS:CASTE', 'check CASTE filter is set or n', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 63, 'a', 'FILTERS:COUNTRY_RES', 'check SOUNTRY_RES filter is se', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 64, 'a', 'FILTERS:CITY_RES', 'check CITY_RES filter is set o', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 65, 'a', 'FILTERS:MTONGUE', 'check MTONGUE filter is set or', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP6', 66, 'a', 'FILTERS:INCOME', 'check INCOME filter is set or ', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 1, '', 'REG_LEAD:EMAIL', '* Email:', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 18, '', 'REG_LEAD:ISD-isd,PHONE_MOB-mobile', '* Mobile Number:', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 3, '', 'REG_LEAD:RELATIONSHIP', '* I am looking for:', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 5, '', 'REG_LEAD:DTOFBIRTH', '* Date of Birth of Boy / Girl:', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 13, '', 'REG_LEAD:MTONGUE', '* Mother Tongue/Community:', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP3', 35, 'a', 'JPROFILE:EDU_LEVEL_NEW', 'Highest Degree', '', 'Select Degree');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP3', 30, 'a', 'JPROFILE:OCCUPATION', 'Work Area', '', 'Select Occupation');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP3', 34, 'a', 'JPROFILE:INCOME', 'Annual Income', '', 'Select Income');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP3', 10, 'a', 'JPROFILE:CITY_RES', 'City', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 17, '', 'NO_TABLE', NULL, '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 67, '', 'NAME_OF_USER', 'Your Name :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 68, '', 'JP_JAIN:SAMPRADAY', 'Sampraday :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 69, '', 'JPROFILE:FAMILY_VALUES', 'Family Values :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 70, '', 'JPROFILE:FAMILY_TYPE', 'Family Type :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 71, '', 'JPROFILE:FAMILY_STATUS', 'Family Status :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 72, '', 'JPROFILE:FAMILY_BACK', 'Father''s Occupation :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 73, '', 'JPROFILE:MOTHER_OCC', 'Mother''s Occupation :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 74, '', 'JPROFILE:T_BROTHER', 'Brother(s) :', '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 75, '', 'JPROFILE:M_BROTHER', NULL, '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 76, '', 'JPROFILE:T_SISTER', 'Sister(s) :', '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 77, '', 'JPROFILE:M_SISTER', NULL, '', 'Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 78, '', 'JPROFILE:PARENT_CITY_SAME', 'Do you live with your parents :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 79, '', 'JPROFILE:FAMILYINFO', 'Write about your Family :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 80, '', 'JPROFILE:SUBCASTE', 'Subcaste :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 81, '', 'JPROFILE:GOTHRA', 'Gotra /Gothram :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 82, '', 'JPROFILE:MANGLIK', 'Manglik :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 83, '', 'JPROFILE:RASHI', 'Rashi/ Moon sign :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 84, '', 'JPROFILE:ANCESTRAL_ORIGIN', 'Ancestral Origin <b style=\\"font-weight:normal;\\">( Native )</b> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 85, '', 'JPROFILE:NAKSHATRA', 'Nakshatra (m) :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 86, '', 'JP_SIKH:AMRITDHARI', 'Are you a Amritdhari? :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 87, '', 'JP_SIKH:CUT_HAIR', 'Do you cut your hair? :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 88, '', 'JP_SIKH:TRIM_BEARD', 'Do you trim your beard? :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 89, '', 'JP_SIKH:WEAR_TURBAN', 'Do you wear turban? :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 90, '', 'JP_SIKH:CLEAN_SHAVEN', 'Are you clean-shaven? :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 91, '', 'JPROFILE:HOROSCOPE_MATCH', 'Horoscope match :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 92, '', 'JP_MUSLIM:MATHTHAB', 'Ma''thab :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 93, '', 'JP_MUSLIM:NAMAZ', 'Namaz :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 94, '', 'JP_MUSLIM:FASTING', 'Fasting :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 95, '', 'JP_MUSLIM:ZAKAT', 'Zakat :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 96, '', 'JP_MUSLIM:QURAN', 'Do you read the Quran :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 97, '', 'JP_MUSLIM:UMRAH_HAJJ', 'Umrah/Hajj :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 98, '', 'JP_MUSLIM:SUNNAH_BEARD', 'Sunnah beard :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 99, '', 'JP_MUSLIM:SUNNAH_CAP', 'Sunnah Cap :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 100, '', 'JP_MUSLIM:HIJAB', 'Hijab :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 101, '', 'JP_MUSLIM:HIJAB_MARRIAGE', 'Willing to wear hijab :<br/> after marriage? ', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 102, '', 'JP_MUSLIM:WORKING_MARRIAGE', 'Can the girl work after : marriage?', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 103, '', 'JPROFILE:SPEAK_URDU', 'Speak Urdu :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 104, '', 'JP_CHRISTIAN:DIOCESE', 'Diocese :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 105, '', 'JP_CHRISTIAN:BAPTISED', 'Baptised :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 107, '', 'JP_CHRISTIAN:READ_BIBLE', 'Do you read Bible : <br />everyday? ', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 108, '', 'JP_CHRISTIAN:OFFER_TITHE', 'Do you offer Tithe : <br /> regularly? ', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 109, '', 'JP_CHRISTIAN:SPREADING_GOSPEL', 'Interested in spreading :<br />the Gospel?', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 110, '', 'JP_PARSI:ZARATHUSHTRI', 'Are you a Zarathushtri :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP3', 111, '', 'JP_PARSI:PARENTS_ZARATHUSHTRI', 'Are both parents :<br /> Zarathushtri?', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP4', 38, '', 'JPROFILE:YOURINFO', 'Write about your family background, education, work, interests & hobbies. (Provide atleast 100 characters)', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 1, 'a', 'JPROFILE:EMAIL', 'Email<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 2, 'a', 'JPROFILE:PASSWORD', 'Password<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 3, 'a', 'JPROFILE:RELATION', 'Create Profile For<u>*</u> :', '', 'Please Select');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 4, 'b', 'JPROFILE:GENDER', 'Gender<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 5, 'b', 'JPROFILE:DTOFBIRTH', 'Date of Birth<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 13, 'c', 'JPROFILE:MTONGUE', 'Mother Tongue<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 18, 'd', 'JPROFILE:ISD-isd,PHONE_MOB-mobile', 'Mobile Number<u>*</u> :', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MR', 16, '', 'REG_LEAD:SOURCE', NULL, '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP5', 113, '', 'JPROFILE:SPOUSE', 'Describe your : desired partner', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP2', 114, '', 'NO_TABLE', 'REG_ID', '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 115, '', 'JPROFILE:SHOWPHONE_RES', NULL, '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('DP1', 116, '', 'JPROFILE:SHOWPHONE_MOB', NULL, '', '');
INSERT INTO `REG_EDIT_PAGE_FIELDS` VALUES ('MP1', 16, 'a', 'REGISTRATION_PAGE1:SOURCE', NULL, '', '');

CREATE TABLE `PROFILE_FIELDS` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `FIELD_NAME` varchar(100) NOT NULL,
  `TYPE` varchar(50) NOT NULL
  `JAVASCRIPT_VALIDATION` varchar(50) NOT NULL,
  `DEPENDENT_FIELD` smallint(6) DEFAULT NULL,
  `LABEL` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
);

-- 
-- Dumping data for table `PROFILE_FIELDS`
-- 

INSERT INTO `PROFILE_FIELDS` VALUES (1, 'EMAIL', 'text', 'email', 'validate_email', NULL, 'Email');
INSERT INTO `PROFILE_FIELDS` VALUES (2, 'PASSWORD', 'password', 'password', 'validate_password', NULL, 'Password');
INSERT INTO `PROFILE_FIELDS` VALUES (3, 'RELATIONSHIP', 'dropdown', 'dropdown_req', 'validate_select', NULL, 'Create Profile For');
INSERT INTO `PROFILE_FIELDS` VALUES (4, 'GENDER', 'radio', 'dropdown_req', 'validate_radio', 3, 'Gender');
INSERT INTO `PROFILE_FIELDS` VALUES (5, 'DTOFBIRTH', 'date', 'dob', 'validate_select', NULL, 'Date of Birth');
INSERT INTO `PROFILE_FIELDS` VALUES (8, 'HEIGHT', 'dropdown', 'dropdown_req', 'validate_select', NULL, 'Height');
INSERT INTO `PROFILE_FIELDS` VALUES (9, 'COUNTRY_RES', 'dropdown', 'string', 'validate_select', NULL, 'Country living in');
INSERT INTO `PROFILE_FIELDS` VALUES (10, 'CITY_RES', 'dropdown', 'mandatory', 'validate_select', 9, 'City');
INSERT INTO `PROFILE_FIELDS` VALUES (11, 'MSTATUS', 'dropdown', 'mstatus', 'validate_select', NULL, 'Marital Status');
INSERT INTO `PROFILE_FIELDS` VALUES (12, 'HAVECHILD', 'dropdown', 'havechild', 'validate_select', 11, 'Have Children');
INSERT INTO `PROFILE_FIELDS` VALUES (13, 'MTONGUE', 'dropdown', 'dropdown_req', 'validate_select', 14, 'Mother Tongue');
INSERT INTO `PROFILE_FIELDS` VALUES (14, 'RELIGION', 'dropdown', 'mandatory', 'validate_select', 11, 'Religion');
INSERT INTO `PROFILE_FIELDS` VALUES (15, 'CASTE', 'dropdown', 'caste', 'validate_select', 14, 'Caste');
INSERT INTO `PROFILE_FIELDS` VALUES (16, 'SOURCE', 'hidden', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (17, 'record_id', 'hidden', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (18, 'PHONE_MOB', 'mobile', 'mobile', 'validate_contact', 19, 'Mobile Number');
INSERT INTO `PROFILE_FIELDS` VALUES (19, 'ISD', 'text', 'string', 'validate_contact', 9, '');
INSERT INTO `PROFILE_FIELDS` VALUES (20, 'STD', 'text', 'string', 'validate_contact', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (21, 'PHONE_RES', 'landline', 'landline', 'validate_contact', NULL, 'LandLine Number');
INSERT INTO `PROFILE_FIELDS` VALUES (22, 'PROMO', 'checkbox', 'string', 'validate_select', NULL, 'Send me third-party marketing mails');
INSERT INTO `PROFILE_FIELDS` VALUES (24, 'DIET', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'Diet');
INSERT INTO `PROFILE_FIELDS` VALUES (25, 'SMOKE', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'Smoke');
INSERT INTO `PROFILE_FIELDS` VALUES (26, 'DRINK', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'Drink');
INSERT INTO `PROFILE_FIELDS` VALUES (27, 'COMPLEXION', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'Complexion');
INSERT INTO `PROFILE_FIELDS` VALUES (28, 'BTYPE', 'radio', 'dropdown_not_req', 'validate_radio', 0, 'Body Type');
INSERT INTO `PROFILE_FIELDS` VALUES (30, 'OCCUPATION', 'dropdown', 'mandatory', 'validate_select', NULL, 'Work Area*');
INSERT INTO `PROFILE_FIELDS` VALUES (31, 'MANGLIK', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'Manglik');
INSERT INTO `PROFILE_FIELDS` VALUES (32, 'HANDICAPPED', 'radio', 'dropdown_not_req', 'validate_radio', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (33, 'AGE', 'dropdown', 'string', 'validate_select', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (34, 'INCOME', 'dropdown', 'mandatory', 'validate_select', NULL, 'Annual Income*');
INSERT INTO `PROFILE_FIELDS` VALUES (35, 'EDU_LEVEL_NEW', 'dropdown', 'mandatory', 'validate_select', NULL, 'Highest Degree*');
INSERT INTO `PROFILE_FIELDS` VALUES (36, 'CASTE_HIDDEN', 'hidden', 'string', 'validate_select', NULL, 'Caste Hidden');
INSERT INTO `PROFILE_FIELDS` VALUES (37, 'RES_STATUS', 'dropdown', 'string', '', NULL, 'Resident Status');
INSERT INTO `PROFILE_FIELDS` VALUES (38, 'YOURINFO', 'textarea', 'yourinfo', '', NULL, 'Write about your Education Work Family');
INSERT INTO `PROFILE_FIELDS` VALUES (39, 'EDUCATION', 'textarea', 'string', '', NULL, 'Educational Background');
INSERT INTO `PROFILE_FIELDS` VALUES (40, 'JOB_INFO', 'textarea', 'string', '', NULL, 'Professional Background');
INSERT INTO `PROFILE_FIELDS` VALUES (41, 'WORK_STATUS', 'dropdown', 'string', 'validate_select', NULL, 'Work Status');
INSERT INTO `PROFILE_FIELDS` VALUES (42, 'HANDICAPPED', 'dropdown', 'string', 'validate_select', NULL, 'Challenged');
INSERT INTO `PROFILE_FIELDS` VALUES (43, 'BLOOD_GROUP', 'dropdown', 'string', 'validate_select', NULL, 'Blood Group');
INSERT INTO `PROFILE_FIELDS` VALUES (44, 'HIV', 'radio', 'dropdown_not_req', 'validate_radio', NULL, 'HIV');
INSERT INTO `PROFILE_FIELDS` VALUES (45, 'CONTACT', 'textarea', 'string', '', NULL, 'Your Contact Address');
INSERT INTO `PROFILE_FIELDS` VALUES (46, 'MESSENGER_ID', 'text', 'messenger_id', '', NULL, 'Messenger ID');
INSERT INTO `PROFILE_FIELDS` VALUES (47, 'SHOWMESSENGER', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (48, 'SHOWADDRESS', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (49, 'MESSENGER_CHANNEL', 'dropdown', 'messenger_channel', '', NULL, 'Messenger Channel');
INSERT INTO `PROFILE_FIELDS` VALUES (50, 'P_LAGE', 'dropdown', 'string', '', NULL, 'Age');
INSERT INTO `PROFILE_FIELDS` VALUES (51, 'P_HAGE', 'dropdown', 'partner_age', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (52, 'P_LHEIGHT', 'dropdown', 'string', '', NULL, 'Height');
INSERT INTO `PROFILE_FIELDS` VALUES (53, 'P_HHEIGHT', 'dropdown', 'partner_height', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (54, 'P_LRS', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (55, 'P_HRS', 'dropdown', 'partner_rupee', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (56, 'P_LDS', 'dropdown', 'string', '', NULL, 'Annual Income');
INSERT INTO `PROFILE_FIELDS` VALUES (57, 'P_HDS', 'dropdown', 'partner_dollar', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (58, 'NATURE_HANDICAP', 'dropdown', 'string', '', NULL, 'Nature of handicap');
INSERT INTO `PROFILE_FIELDS` VALUES (59, 'F_AGE', 'checkbox', 'string', 'validate_select', NULL, 'check Age filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (60, 'F_MSTATUS', 'checkbox', 'string', 'validate_select', NULL, 'check Mstatus filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (61, 'F_RELIGION', 'checkbox', 'string', 'validate_select', NULL, 'check RELIGION filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (62, 'F_CASTE', 'checkbox', 'string', 'validate_select', NULL, 'check CASTE filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (63, 'F_COUNTRY_RES', 'checkbox', 'string', 'validate_select', NULL, 'check SOUNTRY_RES filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (64, 'F_CITY_RES', 'checkbox', 'string', 'validate_select', NULL, 'check CITY_RES filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (65, 'F_MTONGUE', 'checkbox', 'string', 'validate_select', NULL, 'check MTONGUE filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (66, 'F_INCOME', 'checkbox', 'string', 'validate_select', NULL, 'check INCOME filter is set or not');
INSERT INTO `PROFILE_FIELDS` VALUES (67, 'NAME_OF_USER', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (68, 'SAMPRADAY', 'dropdown', 'string', '', NULL, 'Sampraday');
INSERT INTO `PROFILE_FIELDS` VALUES (69, 'FAMILY_VALUES', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (70, 'FAMILY_TYPE', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (71, 'FAMILY_STATUS', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (72, 'FAMILY_BACK', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (73, 'MOTHER_OCC', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (74, 'T_BROTHER', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (75, 'M_BROTHER', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (76, 'T_SISTER', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (77, 'M_SISTER', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (78, 'PARENT_CITY_SAME', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (79, 'FAMILYINFO', 'textarea', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (80, 'SUBCASTE', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (81, 'GOTHRA', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (82, 'MANGLIK', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (83, 'RASHI', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (84, 'ANCESTRAL_ORIGIN', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (85, 'NAKSHATRA', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (86, 'AMRITDHARI', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (87, 'CUT_HAIR', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (88, 'TRIM_BEARD', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (89, 'WEAR_TURBAN', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (90, 'CLEAN_SHAVEN', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (91, 'HOROSCOPE_MATCH', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (92, 'MATHTHAB', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (93, 'NAMAZ', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (94, 'FASTING', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (95, 'ZAKAT', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (96, 'QURAN', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (97, 'UMRAH_HAJJ', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (98, 'SUNNAH_BEARD', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (99, 'SUNNAH_CAP', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (100, 'HIJAB', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (101, 'HIJAB_MARRIAGE', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (102, 'WORKING_MARRIAGE', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (103, 'SPEAK_URDU', 'checkbox', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (104, 'DIOCESE', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (105, 'BAPTISED', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (107, 'READ_BIBLE', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (108, 'OFFER_TITHE', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (109, 'SPREADING_GOSPEL', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (110, 'ZARATHUSHTRI', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (111, 'PARENTS_ZARATHUSHTRI', 'radio', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (113, 'SPOUSE', 'textarea', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (114, 'REG_ID', 'hidden', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (115, 'SHOWPHONE', 'dropdown', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (116, 'SHOWMOBILE', 'dropdown', 'string', '', NULL, '');

CREATE TABLE `LOG_SERVER_ERRORS` (
	 `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	 `PAGE` char(5) DEFAULT NULL,
	 `ERROR` varchar(1000) DEFAULT NULL,
	 KEY `Date` (`DATE`,`PAGE`)
);

use jeevansathi_mailer;

INSERT INTO `EMAIL_TYPE` VALUES (1771, 1771, 'registration_mailer_Page1.tpl', NULL, NULL, NULL, 21, 1, 'register@jeevansathi.com', NULL, 'D', '', '', NULL, '', NULL, NULL, NULL, '', NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1773, 1773, 'under_screening.tpl', NULL, NULL, NULL, 20, 1, 'register@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `MAILER_SUBJECT` VALUES (1771, 'D', 'Thank you for registering with jeevansathi.com', 'registration page1 mailer subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1773, 'D', 'Welcome to Jeevansathi.com', 'Screening mailer  subject line Page2');
INSERT INTO `LINK_MAILERS` VALUES (31, 'PROFILE_DELETION_URL', 'profile/hide_delete_revamp.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (32, 'FAQS_LAYER', 'profile/faqs_layer.php', NULL, 'Y', 'N');

