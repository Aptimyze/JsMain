use crawler;

UPDATE `crawler_JS_competition_gender_values_mapping` SET `COMPETITION_FIELD_LABEL` = 'M' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Male' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'MALE' AND CONVERT( `JS_FIELD_VALUE` USING utf8 ) = 'M' LIMIT 1 ;

UPDATE `crawler_JS_competition_gender_values_mapping` SET `COMPETITION_FIELD_LABEL` = 'F' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Female' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'FEMALE' AND CONVERT( `JS_FIELD_VALUE` USING utf8 ) = 'F' LIMIT 1 ;

UPDATE `crawler_JS_competition_height_values_mapping` SET COMPETITION_FIELD_LABEL = REPLACE(COMPETITION_FIELD_LABEL,"\"",'in') WHERE SITE_ID=3;

UPDATE `crawler_JS_competition_height_values_mapping` SET COMPETITION_FIELD_LABEL = REPLACE(COMPETITION_FIELD_LABEL,"\'",'ft') WHERE SITE_ID=3;

UPDATE `crawler_JS_competition_height_values_mapping` SET `COMPETITION_FIELD_LABEL` = '4ft' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = '4ft 0in' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'FT4_IN5' AND `JS_FIELD_VALUE` = '1' LIMIT 1 ;

UPDATE `crawler_JS_competition_height_values_mapping` SET `COMPETITION_FIELD_LABEL` = '5ft' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = '5ft 0in' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = '' AND `JS_FIELD_VALUE` = '13' LIMIT 1 ;

UPDATE `crawler_JS_competition_height_values_mapping` SET `COMPETITION_FIELD_LABEL` = '6ft' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = '6ft 0in' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = '' AND `JS_FIELD_VALUE` = '25' LIMIT 1 ;

DELETE FROM `crawler_JS_competition_mtongue_values_mapping` WHERE SITE_ID=3;

INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Arunachali', '', 4);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Assamese', '', 5);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Awadhi', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Badaga', '', 16);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Bengali', '', 6);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Bhojpuri', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Bhutia/Sikkimese', '', 29);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Bihari', '', 7);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Brij', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Chatisgarhi', '', 19);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Coorgi', '', 31);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Dhivehi', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Dogri', '', 15);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'English', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Foreign Language', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'French', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Garhwali', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Garo', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Gujarati', '', 12);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Haryanvi', '', 13);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Himachali/Pahari', '', 14);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Hindi', '', 10);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Jaintia', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Kanauji', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Kannada', '', 16);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Kashmiri', '', 15);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Khandesi', '', 20);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Khasi', '', 22);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Konkani', '', 34);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Koshali', '', 25);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Kumoani', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Kutchi', '', 12);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Ladacki', '', 15);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Lepcha', '', 29);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Limbu', '', 29);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Magahi', '', 7);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Maithili', '', 7);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Malayalam', '', 17);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Manipuri', '', 21);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Marathi', '', 20);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Marwari', '', 28);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Miji', '', 5);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Mizo', '', 23);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Monpa', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Nepali', '', 29);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Nicobarese', '', 2);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Oriya', '', 25);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Others', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Portuguese', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Punjabi', '', 27);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Pushtu', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Rajasthani', '', 28);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Sanskrit', '', 33);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Santhali', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Sindhi', '', 30);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Singhalese', '', 1);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Sourashtra', '', 20);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Tamil', '', 31);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Telugu', '', 3);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Tripuri', '', 32);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Tulu', '', 31);
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (3, 'Urdu', '', 33);

ALTER TABLE `crawler_search_results` ADD `DETAIL_PAGE_PARAMS_SM` VARCHAR( 100 ) DEFAULT NULL ;

UPDATE `crawler_JS_competition_field_name_mapping` SET `COMPETITION_FIELD_NAME` = 'City of Birth' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_NAME` USING utf8 ) = 'Place of Birth' AND CONVERT( `JS_FIELD_NAME` USING utf8 ) = 'CITY_BIRTH' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = '' AND CONVERT( `ACTION` USING utf8 ) = 'detail_view' LIMIT 1 ;

UPDATE `crawler_JS_competition_field_name_mapping` SET `COMPETITION_FIELD_NAME` = 'City of Residence' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_NAME` USING utf8 ) = 'City' AND CONVERT( `JS_FIELD_NAME` USING utf8 ) = 'CITY_RES' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = 'Y' AND CONVERT( `ACTION` USING utf8 ) = 'detail_view' LIMIT 1 ;

UPDATE `crawler_JS_competition_field_name_mapping` SET `COMPETITION_FIELD_NAME` = 'Country of Residence' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_NAME` USING utf8 ) = 'Country' AND CONVERT( `JS_FIELD_NAME` USING utf8 ) = 'COUNTRY_RES' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = 'Y' AND CONVERT( `ACTION` USING utf8 ) = 'detail_view' LIMIT 1 ;

INSERT INTO `crawler_JS_competition_field_name_mapping` ( `SITE_ID` , `COMPETITION_FIELD_NAME` , `JS_FIELD_NAME` , `MAPPING_REQUIRED` , `ACTION` )
VALUES (
'3', 'Country of Birth', 'COUNTRY_BIRTH', 'Y', 'detail_view'
);

INSERT INTO `crawler_JS_competition_field_name_mapping` ( `SITE_ID` , `COMPETITION_FIELD_NAME` , `JS_FIELD_NAME` , `MAPPING_REQUIRED` , `ACTION` )
VALUES (
'3', 'Brothers', 'T_BROTHERS', 'Y', 'detail_view'
);

INSERT INTO `crawler_JS_competition_field_name_mapping` ( `SITE_ID` , `COMPETITION_FIELD_NAME` , `JS_FIELD_NAME` , `MAPPING_REQUIRED` , `ACTION` )
VALUES (
'3', 'Sub Caste', 'SUBCASTE', '', 'detail_view'
);

INSERT INTO `crawler_JS_competition_field_name_mapping` ( `SITE_ID` , `COMPETITION_FIELD_NAME` , `JS_FIELD_NAME` , `MAPPING_REQUIRED` , `ACTION` )
VALUES (
'3', 'Sisters', 'T_SISTERS', 'Y', 'detail_view'
);

DELETE FROM `crawler_JS_competition_field_name_mapping` WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_NAME` USING utf8 ) = 'STD' AND CONVERT( `JS_FIELD_NAME` USING utf8 ) = 'STD' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = '' AND CONVERT( `ACTION` USING utf8 ) = 'contact_detail_view' LIMIT 1 ;

DELETE FROM `crawler_JS_competition_field_name_mapping` WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_NAME` USING utf8 ) = 'Phone' AND CONVERT( `JS_FIELD_NAME` USING utf8 ) = 'PHONE_RES' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = '' AND CONVERT( `ACTION` USING utf8 ) = 'contact_detail_view' LIMIT 1 ;

TRUNCATE TABLE crawler_priority_communities;

ALTER TABLE `crawler_priority_communities` ADD `SITE_ID` INT DEFAULT NULL FIRST ;

INSERT INTO `crawler_priority_communities` VALUES (1, 1, 20, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 2, 20, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 3, 20, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 4, 10, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 5, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 6, 33, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 7, 7, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 8, 28, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 9, 13, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 10, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 11, 10, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 12, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 13, 33, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 14, 7, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 15, 28, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 16, 13, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 17, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 18, 10, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 19, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 20, 33, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 21, 7, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 22, 28, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 23, 13, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 24, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 25, 27, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 26, 27, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 27, 27, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 28, 20, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 29, 20, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 30, 10, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 31, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 32, 33, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 33, 7, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 34, 28, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 35, 13, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 36, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 37, 10, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 38, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 39, 33, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 40, 7, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 41, 28, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 42, 13, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 43, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 44, 27, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 45, 27, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 46, 20, 'M', 23, 25, 51, 1, 37, 1, 'N', 'Y');
INSERT INTO `crawler_priority_communities` VALUES (1, 47, 10, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 48, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 49, 33, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 50, 7, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 51, 28, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 52, 13, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 53, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 54, 27, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 55, 10, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 56, 20, 'M', 26, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 57, 10, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 58, 27, 'M', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 59, 10, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 60, 10, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 61, 20, 'F', 25, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 62, 20, 'F', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 63, 20, 'F', 18, 24, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 64, 20, 'M', 23, 25, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 65, 20, 'M', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 66, 10, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 67, 10, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 68, 10, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 69, 10, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 70, 19, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 71, 19, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 72, 19, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 73, 19, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 74, 19, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 75, 19, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 76, 33, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 77, 33, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 78, 33, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 79, 33, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 80, 33, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 81, 33, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 82, 7, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 83, 7, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 84, 7, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 85, 7, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 86, 7, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 87, 7, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 88, 28, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 89, 28, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 90, 28, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 91, 28, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 92, 28, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 93, 28, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 94, 13, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 95, 13, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 96, 13, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 97, 13, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 98, 13, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 99, 13, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 100, 10, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 101, 10, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 102, 10, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 103, 10, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 104, 19, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 105, 19, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 106, 19, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 107, 19, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 108, 19, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 109, 19, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 110, 33, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 111, 33, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 112, 33, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 113, 33, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 114, 33, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 115, 33, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 116, 7, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 117, 7, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 118, 7, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 119, 7, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 120, 7, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 121, 7, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 122, 28, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 123, 28, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 124, 28, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 125, 28, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 126, 28, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 127, 28, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 128, 13, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 129, 13, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 130, 13, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 131, 13, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 132, 13, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 133, 13, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 134, 27, 'M', 26, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 135, 27, 'M', 23, 25, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 136, 27, 'F', 18, 24, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 137, 27, 'F', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 138, 27, 'F', 25, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 139, 6, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 140, 6, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 141, 6, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 142, 6, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 143, 6, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 144, 6, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 145, 25, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 146, 25, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 147, 25, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 148, 25, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 149, 25, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 150, 25, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 151, 12, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 152, 12, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 153, 12, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 154, 12, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 155, 12, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 156, 12, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 157, 30, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 158, 30, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 159, 30, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 160, 30, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 161, 30, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (1, 162, 30, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 1, 20, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 2, 20, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 3, 20, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 4, 10, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 5, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 6, 33, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 7, 7, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 8, 28, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 9, 13, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 10, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 11, 10, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 12, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 13, 33, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 14, 7, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 15, 28, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 16, 13, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 17, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 18, 10, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 19, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 20, 33, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 21, 7, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 22, 28, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 23, 13, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 24, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 25, 27, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 26, 27, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 27, 27, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 28, 20, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 29, 20, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 30, 10, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 31, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 32, 33, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 33, 7, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 34, 28, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 35, 13, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 36, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 37, 10, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 38, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 39, 33, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 40, 7, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 41, 28, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 42, 13, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 43, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 44, 27, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 45, 27, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 46, 20, 'M', 23, 25, 51, 1, 37, 1, 'N', 'Y');
INSERT INTO `crawler_priority_communities` VALUES (2, 47, 10, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 48, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 49, 33, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 50, 7, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 51, 28, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 52, 13, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 53, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 54, 27, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 55, 10, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 56, 20, 'M', 26, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 57, 10, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 58, 27, 'M', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 59, 10, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 60, 10, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 61, 20, 'F', 25, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 62, 20, 'F', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 63, 20, 'F', 18, 24, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 64, 20, 'M', 23, 25, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 65, 20, 'M', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 66, 10, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 67, 10, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 68, 10, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 69, 10, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 70, 19, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 71, 19, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 72, 19, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 73, 19, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 74, 19, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 75, 19, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 76, 33, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 77, 33, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 78, 33, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 79, 33, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 80, 33, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 81, 33, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 82, 7, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 83, 7, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 84, 7, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 85, 7, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 86, 7, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 87, 7, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 88, 28, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 89, 28, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 90, 28, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 91, 28, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 92, 28, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 93, 28, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 94, 13, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 95, 13, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 96, 13, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 97, 13, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 98, 13, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 99, 13, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 100, 10, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 101, 10, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 102, 10, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 103, 10, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 104, 19, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 105, 19, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 106, 19, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 107, 19, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 108, 19, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 109, 19, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 110, 33, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 111, 33, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 112, 33, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 113, 33, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 114, 33, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 115, 33, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 116, 7, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 117, 7, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 118, 7, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 119, 7, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 120, 7, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 121, 7, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 122, 28, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 123, 28, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 124, 28, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 125, 28, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 126, 28, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 127, 28, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 128, 13, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 129, 13, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 130, 13, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 131, 13, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 132, 13, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 133, 13, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 134, 27, 'M', 26, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 135, 27, 'M', 23, 25, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 136, 27, 'F', 18, 24, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 137, 27, 'F', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 138, 27, 'F', 25, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 139, 6, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 140, 6, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 141, 6, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 142, 6, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 143, 6, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 144, 6, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 145, 25, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 146, 25, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 147, 25, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 148, 25, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 149, 25, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 150, 25, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 151, 12, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 152, 12, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 153, 12, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 154, 12, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 155, 12, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 156, 12, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 157, 30, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 158, 30, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 159, 30, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 160, 30, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 161, 30, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (2, 162, 30, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 1, 20, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 2, 20, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 3, 20, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 4, 10, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 5, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 6, 33, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 7, 7, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 8, 28, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 9, 13, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 10, 19, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 11, 10, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 12, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 13, 33, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 14, 7, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 15, 28, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 16, 13, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 17, 19, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 18, 10, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 19, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 20, 33, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 21, 7, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 22, 28, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 23, 13, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 24, 19, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 25, 27, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 26, 27, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 27, 27, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 28, 20, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 29, 20, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 30, 10, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 31, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 32, 33, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 33, 7, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 34, 28, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 35, 13, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 36, 19, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 37, 10, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 38, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 39, 33, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 40, 7, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 41, 28, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 42, 13, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 43, 19, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 44, 27, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 45, 27, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 46, 20, 'M', 23, 25, 51, 1, 37, 1, 'N', 'Y');
INSERT INTO `crawler_priority_communities` VALUES (3, 47, 10, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 48, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 49, 33, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 50, 7, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 51, 28, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 52, 13, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 53, 19, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 54, 27, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 55, 10, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 56, 20, 'M', 26, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 57, 10, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 58, 27, 'M', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 59, 10, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 60, 10, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 61, 20, 'F', 25, 30, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 62, 20, 'F', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 63, 20, 'F', 18, 24, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 64, 20, 'M', 23, 25, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 65, 20, 'M', 31, 43, 51, 1, 37, 7, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 66, 10, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 67, 10, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 68, 10, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 69, 10, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 70, 19, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 71, 19, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 72, 19, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 73, 19, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 74, 19, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 75, 19, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 76, 33, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 77, 33, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 78, 33, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 79, 33, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 80, 33, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 81, 33, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 82, 7, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 83, 7, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 84, 7, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 85, 7, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 86, 7, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 87, 7, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 88, 28, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 89, 28, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 90, 28, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 91, 28, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 92, 28, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 93, 28, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 94, 13, 'M', 26, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 95, 13, 'M', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 96, 13, 'M', 23, 25, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 97, 13, 'F', 18, 24, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 98, 13, 'F', 25, 30, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 99, 13, 'F', 31, 43, 51, 1, 37, 2, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 100, 10, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 101, 10, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 102, 10, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 103, 10, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 104, 19, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 105, 19, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 106, 19, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 107, 19, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 108, 19, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 109, 19, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 110, 33, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 111, 33, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 112, 33, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 113, 33, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 114, 33, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 115, 33, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 116, 7, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 117, 7, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 118, 7, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 119, 7, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 120, 7, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 121, 7, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 122, 28, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 123, 28, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 124, 28, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 125, 28, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 126, 28, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 127, 28, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 128, 13, 'M', 26, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 129, 13, 'M', 23, 25, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 130, 13, 'M', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 131, 13, 'F', 18, 24, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 132, 13, 'F', 31, 43, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 133, 13, 'F', 25, 30, 51, 1, 37, 9, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 134, 27, 'M', 26, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 135, 27, 'M', 23, 25, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 136, 27, 'F', 18, 24, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 137, 27, 'F', 31, 43, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 138, 27, 'F', 25, 30, 51, 1, 37, 4, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 139, 6, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 140, 6, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 141, 6, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 142, 6, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 143, 6, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 144, 6, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 145, 25, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 146, 25, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 147, 25, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 148, 25, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 149, 25, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 150, 25, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 151, 12, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 152, 12, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 153, 12, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 154, 12, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 155, 12, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 156, 12, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 157, 30, 'M', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 158, 30, 'M', 26, 30, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 159, 30, 'M', 23, 25, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 160, 30, 'F', 18, 24, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 161, 30, 'F', 31, 43, 51, 1, 37, 1, 'N', 'N');
INSERT INTO `crawler_priority_communities` VALUES (3, 162, 30, 'F', 25, 30, 51, 1, 37, 1, 'N', 'N');

DELETE FROM `crawler_sites_urls` WHERE SITE_ID=3;

INSERT INTO `crawler_sites_urls` VALUES (13, 3, 'login', 'www.simplymarry.com/users/j_spring_security_check', 'POST', 0);
INSERT INTO `crawler_sites_urls` VALUES (14, 3, 'logout', 'www.simplymarry.com/logout', 'GET', 0);
INSERT INTO `crawler_sites_urls` VALUES (15, 3, 'search', 'www.simplymarry.com/search/performSearch', 'POST', 0);
INSERT INTO `crawler_sites_urls` VALUES (16, 3, 'search_pagination', 'www.simplymarry.com/search', 'GET', 0);
INSERT INTO `crawler_sites_urls` VALUES (17, 3, 'detail_view', 'www.simplymarry.com', 'GET', 0);
INSERT INTO `crawler_sites_urls` VALUES (18, 3, 'contact_detail_view', 'www.simplymarry.com/user_profiles/viewContact', 'GET', 0);

DELETE FROM `crawler_sites_urls_parameters` WHERE URL_ID>=13;

INSERT INTO `crawler_sites_urls_parameters` VALUES (13, 'j_username', 'USERNAME', 'CrawlerUser', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (13, 'submit.x', 'submit.x', '', 'VARIABLE', '', '', '12');
INSERT INTO `crawler_sites_urls_parameters` VALUES (13, 'submit.y', 'submit.y', '', 'VARIABLE', '', '', '14');
INSERT INTO `crawler_sites_urls_parameters` VALUES (13, 'j_password', 'PASSWORD', 'CrawlerUser', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (18, 'userId', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'maxHeight', 'HHEIGHT', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'motherTongues', 'MTONGUE', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'locations', 'COUNTRY_RES', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'gender', 'GENDER', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'x', 'x', '', 'VARIABLE', '', '', '46');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'y', 'y', '', 'VARIABLE', '', '', '13');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'maxAge', 'HAGE', 'CrawlerPriorityCommunity', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'minAge', 'LAGE', 'CrawlerPriorityCommunity', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'minHeight', 'LHEIGHT', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'religions', 'RELIGION', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'maritalStatus', 'MSTATUS', 'CrawlerPriorityCommunity', 'VARIABLE', 'Y', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (16, 'page', 'pageNo', 'Crawler', 'VARIABLE', '', 'Y', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (16, 'parameter_temp', 'actionId', 'Crawler', 'VARIABLE', '', 'Y', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (16, '?sortBy', '?sortBy', '', 'VARIABLE', '', '', '1');
INSERT INTO `crawler_sites_urls_parameters` VALUES (16, 'gridView', 'gridView', '', 'VARIABLE', '', '', 'false');
INSERT INTO `crawler_sites_urls_parameters` VALUES (17, 'detailViewParams', 'DETAIL_PAGE_PARAMS_SM', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (27, 'ID', 'USERNAME', 'CrawlerUser', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (27, 'PASSWORD', 'PASSWORD', 'CrawlerUser', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (31, 'ID', 'USERNAME', 'CrawlerUser', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (31, 'PASSWORD', 'PASSWORD', 'CrawlerUser', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (29, 'id', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (33, 'id', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (30, 'matid', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (30, 'userphoneavailable', 'userphoneavailable', '', 'VARIABLE', '', '', '1');
INSERT INTO `crawler_sites_urls_parameters` VALUES (26, 'matid', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', '');
INSERT INTO `crawler_sites_urls_parameters` VALUES (26, 'userphoneavailable', 'userphoneavailable', '', 'VARIABLE', '', '', '1');
INSERT INTO `crawler_sites_urls_parameters` VALUES (34, 'matid', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (34, 'userphoneavailable', 'userphoneavailable', '', 'VARIABLE', '', '', '1');
INSERT INTO `crawler_sites_urls_parameters` VALUES (35, 'matid', 'COMPETITION_ID', 'CrawlerCompetitionProfile', 'VARIABLE', '', '', NULL);
INSERT INTO `crawler_sites_urls_parameters` VALUES (35, 'userphoneavailable', 'userphoneavailable', '', 'VARIABLE', '', '', '1');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'withPhoto', 'Photos', '', 'VARIABLE', '', '', 'on');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, '_castes', '', '', 'VARIABLE', '', '', 'on');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, '_motherTongues', '', '', 'VARIABLE', '', '', 'on');
INSERT INTO `crawler_sites_urls_parameters` VALUES (15, 'castes', '', '', 'VARIABLE', '', '', 'DONM');

ALTER TABLE `crawler_priority_communities` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `COMMUNITY_ID` , `SITE_ID` ) ;

UPDATE `crawler`.`crawler_JS_competition_country_res_values_mapping` SET `COMPETITION_FIELD_VALUE` = 'ANYW' WHERE `crawler_JS_competition_country_res_values_mapping`.`SITE_ID` =3 AND `crawler_JS_competition_country_res_values_mapping`.`COMPETITION_FIELD_LABEL` = 'India' AND `crawler_JS_competition_country_res_values_mapping`.`COMPETITION_FIELD_VALUE` = '' AND `crawler_JS_competition_country_res_values_mapping`.`JS_FIELD_VALUE` =51 LIMIT 1 ;

UPDATE `crawler`.`crawler_JS_competition_mstatus_values_mapping` SET `COMPETITION_FIELD_VALUE` = 'NEVER_MARRIED' WHERE `crawler_JS_competition_mstatus_values_mapping`.`SITE_ID` =3 AND `crawler_JS_competition_mstatus_values_mapping`.`COMPETITION_FIELD_LABEL` = 'Never Married' AND `crawler_JS_competition_mstatus_values_mapping`.`COMPETITION_FIELD_VALUE` = '' AND `crawler_JS_competition_mstatus_values_mapping`.`JS_FIELD_VALUE` = 'N' LIMIT 1 ;

UPDATE crawler_JS_competition_mtongue_values_mapping SET COMPETITION_FIELD_VALUE=UPPER(COMPETITION_FIELD_LABEL) WHERE SITE_ID=3;

UPDATE `crawler`.`crawler_JS_competition_gender_values_mapping` SET `COMPETITION_FIELD_LABEL` = 'M',
`COMPETITION_FIELD_VALUE` = 'MALE' WHERE `crawler_JS_competition_gender_values_mapping`.`SITE_ID` =3 AND `crawler_JS_competition_gender_values_mapping`.`COMPETITION_FIELD_LABEL` = 'Male' AND `crawler_JS_competition_gender_values_mapping`.`COMPETITION_FIELD_VALUE` = 'M' AND `crawler_JS_competition_gender_values_mapping`.`JS_FIELD_VALUE` = 'M' LIMIT 1 ;

UPDATE `crawler`.`crawler_JS_competition_gender_values_mapping` SET `COMPETITION_FIELD_LABEL` = 'F',
`COMPETITION_FIELD_VALUE` = 'FEMALE' WHERE `crawler_JS_competition_gender_values_mapping`.`SITE_ID` =3 AND `crawler_JS_competition_gender_values_mapping`.`COMPETITION_FIELD_LABEL` = 'Female' AND `crawler_JS_competition_gender_values_mapping`.`COMPETITION_FIELD_VALUE` = 'F' AND `crawler_JS_competition_gender_values_mapping`.`JS_FIELD_VALUE` = 'F' LIMIT 1 ;


UPDATE `crawler_sites_actions` SET `RESULTS_PER_PAGE` = '12' WHERE `SITE_ID` = '3' AND CONVERT( `ACTION` USING utf8 ) = 'search' AND CONVERT( `LOGIN_REQUIRED` USING utf8 ) = '' AND CONVERT( `PAID_LOGIN_REQUIRED` USING utf8 ) = '' AND `RESULTS_PER_PAGE` = '20' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Magahi' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'MAGAHI' AND `JS_FIELD_VALUE` = '7' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Maithili' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'MAITHILI' AND `JS_FIELD_VALUE` = '7' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Kutchi' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'KUTCHI' AND `JS_FIELD_VALUE` = '12' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Khandesi' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'KHANDESI' AND `JS_FIELD_VALUE` = '20' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Sourashtra' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'SOURASHTRA' AND `JS_FIELD_VALUE` = '20' LIMIT 1 ;
	
UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Koshali' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'KOSHALI' AND `JS_FIELD_VALUE` = '25' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Marwari' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'MARWARI' AND `JS_FIELD_VALUE` = '28' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Sanskrit' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'SANSKRIT' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Awadhi' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'AWADHI' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Others' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'OTHERS' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Kumoani' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'KUMOANI' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Kanauji' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'KANAUJI' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Garhwali' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'GARHWALI' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Brij' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'BRIJ' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

UPDATE `crawler_JS_competition_mtongue_values_mapping` SET `COMPETITION_FIELD_VALUE` = '' WHERE `SITE_ID` = '3' AND CONVERT( `COMPETITION_FIELD_LABEL` USING utf8 ) = 'Bhojpuri' AND CONVERT( `COMPETITION_FIELD_VALUE` USING utf8 ) = 'BHOJPURI' AND `JS_FIELD_VALUE` = '33' LIMIT 1 ;

CREATE TABLE `TRACK_SUGAR_LEAD_CREATION` (
 `SITE_ID` tinyint(4) NOT NULL,
 `COMPETITION_ID` varchar(255) NOT NULL,
 `RESPONSE` varchar(255) NOT NULL,
 `DATE` date NOT NULL
) ENGINE=MyISAM;
