use crawler;

UPDATE  `crawler`.`crawler_sites_urls_parameters` SET  `VALUE` =  'Protected' WHERE  `crawler_sites_urls_parameters`.`URL_ID` =2 AND  `crawler_sites_urls_parameters`.`PARAMETER` =  'photograph' AND `crawler_sites_urls_parameters`.`FIELD_NAME` =  'Photos' AND  `crawler_sites_urls_parameters`.`PARENT_CLASS` =  '' AND  `crawler_sites_urls_parameters`.`TYPE` =  'VARIABLE' AND  `crawler_sites_urls_parameters`.`MAPPING_REQUIRED` = '' AND  `crawler_sites_urls_parameters`.`DYNAMIC` =  '' AND  `crawler_sites_urls_parameters`.`VALUE` =  'Visible' LIMIT 1 ;
