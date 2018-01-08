use crawler;

UPDATE  `crawler`.`crawler_sites_urls_parameters` SET  `VALUE` =  'Protected' WHERE  `crawler_sites_urls_parameters`.`URL_ID` =2 AND  `crawler_sites_urls_parameters`.`PARAMETER` =  'photograph' AND `crawler_sites_urls_parameters`.`FIELD_NAME` =  'Photos' AND  `crawler_sites_urls_parameters`.`PARENT_CLASS` =  '' AND  `crawler_sites_urls_parameters`.`TYPE` =  'VARIABLE' AND  `crawler_sites_urls_parameters`.`MAPPING_REQUIRED` = '' AND  `crawler_sites_urls_parameters`.`DYNAMIC` =  '' AND  `crawler_sites_urls_parameters`.`VALUE` =  'Visible' LIMIT 1 ;







CREATE TABLE `crawler_no_of_search_results` (
 `SITE_ID` tinyint(4) NOT NULL,
 `DATE` date NOT NULL,
 `NO_OF_RESULTS_ENTERED` int(11) NOT NULL
) ENGINE=MYISAM;






ALTER TABLE `crawler_search_results` ADD `DETAIL_VIEW_DATE` DATE NOT NULL ,
ADD `CONTACT_DETAIL_VIEW_DATE` DATE NOT NULL ;

UPDATE `crawler_sites_urls` SET `URL` = 'ww2.shaadi.com/profile/ajax/show-contact-details' WHERE `URL_ID` = '6' ;

DELETE FROM `crawler_sites_urls_parameters` WHERE `URL_ID` = '6' AND CONVERT( `PARAMETER` USING utf8 ) = 'show_number' AND CONVERT( `FIELD_NAME` USING utf8 ) = '' AND CONVERT( `PARENT_CLASS` USING utf8 ) = '' AND CONVERT( `TYPE` USING utf8 ) = 'VARIABLE' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = '' AND CONVERT( `DYNAMIC` USING utf8 ) = '' AND CONVERT( `VALUE` USING utf8 ) = 'Y' LIMIT 1 ;






--mappings related

DELETE FROM `crawler_JS_competition_edu_level_new_values_mapping` WHERE SITE_ID=1;

ALTER TABLE `crawler_JS_competition_edu_level_new_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` VARCHAR( 100 ) DEFAULT NULL;

--import education.csv

UPDATE `crawler_JS_competition_edu_level_new_values_mapping` SET JS_FIELD_VALUE = 'Other' WHERE `JS_FIELD_VALUE` = 'Others';

UPDATE `crawler_JS_competition_edu_level_new_values_mapping` SET JS_FIELD_VALUE = 'B.A' WHERE `JS_FIELD_VALUE` = 'BA';

--execute mappings php file for education

ALTER TABLE `crawler_JS_competition_edu_level_new_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` TINYINT DEFAULT NULL;







DELETE FROM `crawler_JS_competition_caste_values_mapping` WHERE SITE_ID=1;

ALTER TABLE `crawler_JS_competition_caste_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` VARCHAR( 100 ) DEFAULT NULL;

--import caste.csv

UPDATE `crawler_JS_competition_caste_values_mapping` SET JS_FIELD_VALUE='Shia' WHERE JS_FIELD_VALUE='shia';

UPDATE `crawler_JS_competition_caste_values_mapping` SET JS_FIELD_VALUE='Sunni' WHERE JS_FIELD_VALUE='sunni';

--execute mappings.php for caste

ALTER TABLE `crawler_JS_competition_caste_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` INT DEFAULT NULL;




DELETE FROM `crawler_JS_competition_city_res_values_mapping` WHERE SITE_ID=1;

ALTER TABLE `crawler_JS_competition_city_res_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

--import city.csv

UPDATE `crawler_JS_competition_city_res_values_mapping` SET JS_FIELD_VALUE='Coochbehar' WHERE JS_FIELD_VALUE='CoochBehar';

--execute mapppings.php for city

ALTER TABLE `crawler_JS_competition_city_res_values_mapping` CHANGE `JS_FIELD_VALUE` `JS_FIELD_VALUE` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;






UPDATE `crawler_sites_urls_parameters` SET `PARAMETER` = 'countryofresidencearray[]' WHERE `URL_ID` = '2' AND CONVERT( `PARAMETER` USING utf8 ) = 'countryofresidence' AND CONVERT( `FIELD_NAME` USING utf8 ) = 'COUNTRY_RES' AND CONVERT( `PARENT_CLASS` USING utf8 ) = 'CrawlerPriorityCommunity' AND CONVERT( `TYPE` USING utf8 ) = 'VARIABLE' AND CONVERT( `MAPPING_REQUIRED` USING utf8 ) = 'Y' AND CONVERT( `DYNAMIC` USING utf8 ) = '' AND CONVERT( `VALUE` USING utf8 ) = '' LIMIT 1 ;
