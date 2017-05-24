
USE newjs;

CREATE TABLE `NATIVE_PLACE` (
`PROFILEID` INT( 11 ) UNSIGNED NOT NULL ,
`NATIVE_COUNTRY` smallint( 5 ) ,
`NATIVE_STATE` VARCHAR( 10 ) ,
`NATIVE_CITY` VARCHAR( 10 ) ,
PRIMARY KEY ( `PROFILEID` )
);


use reg;
INSERT INTO `PROFILE_FIELDS` ( `ID` , `FIELD_NAME` , `TYPE` , `CONSTRAINT_CLASS` , `JAVASCRIPT_VALIDATION` , `DEPENDENT_FIELD` , `LABEL` )
VALUES  (
'118', 'NATIVE_COUNTRY', 'dropdown', 'dropdown_not_req', 'validate_select', NULL , ''
), (
'119', 'NATIVE_STATE', 'dropdown', 'native_place', 'validate_select', NULL , ''
), (
'120', 'NATIVE_CITY', 'dropdown', 'native_place', 'validate_select', NULL , ''
)
;


INSERT INTO `REG_EDIT_PAGE_FIELDS` ( `PAGE` , `FIELD_ID` , `GROUP` , `TABLE_NAME` , `LABEL` , `BLANK_VALUE` , `BLANK_LABEL` )
VALUES (
'DP3', '118', '', 'NATIVE_PLACE:NATIVE_COUNTRY', NULL , '', 'Select Country'
), (
'DP3', '119', '', 'NATIVE_PLACE:NATIVE_STATE', 'Family based out of:', NULL , 'Select State'
), (
'DP3', '120', '', 'NATIVE_PLACE:NATIVE_CITY', NULL , '', 'Select City'
);

UPDATE `REG_EDIT_PAGE_FIELDS` SET `LABEL` = 'Specify City/Town :' WHERE CONVERT( `PAGE` USING utf8 ) = 'DP3' AND `FIELD_ID` = '84' AND CONVERT( `GROUP` USING utf8 ) = '' AND CONVERT( `TABLE_NAME` USING utf8 ) = 'JPROFILE:ANCESTRAL_ORIGIN' AND CONVERT( `LABEL` USING utf8 ) = 'Native Place/ Hometown :' AND CONVERT( `BLANK_VALUE` USING utf8 ) = '' AND CONVERT( `BLANK_LABEL` USING utf8 ) = '' LIMIT 1 ;

INSERT INTO `REG_EDIT_PAGE_FIELDS` ( `PAGE` , `FIELD_ID` , `GROUP` , `TABLE_NAME` , `LABEL` , `BLANK_VALUE` , `BLANK_LABEL` )
VALUES (
'MP5', '118', '', 'NATIVE_PLACE:NATIVE_COUNTRY', NULL , '', 'Select Country'
), (
'MP5', '119', '', 'NATIVE_PLACE:NATIVE_STATE', 'Family based out of:', NULL , 'Select State'
), (
'MP5', '120', '', 'NATIVE_PLACE:NATIVE_CITY', NULL , '', 'Select City'
),(
'MP5', '84', '', 'JPROFILE:ANCESTRAL_ORIGIN', 'Specify City/Town :', NULL , NULL
),(
'MP5', '81', '', 'JPROFILE:GOTHRA', 'Gotra /Gothram :', NULL , NULL
),(
'MP5', '72', '', 'JPROFILE:FAMILY_BACK',  'Father''s Occupation :', NULL , 'Please Select'
),(
'MP5', '73', '', 'JPROFILE:MOTHER_OCC',  'Mother''s Occupation :', NULL , 'Please Select'
),(
'MP5', '74', '', 'JPROFILE:T_BROTHER',  'Brother(s) :', NULL , 'Select'
),(
'MP5', '75', '', 'JPROFILE:M_BROTHER',  NULL, NULL , 'Select'
),(
'MP5', '76', '', 'JPROFILE:T_SISTER',  'Sister(s) :', NULL , 'Select'
),(
'MP5', '77', '', 'JPROFILE:M_SISTER',  NULL, NULL , 'Select'
),(
'MP5', '79', '', 'JPROFILE:FAMILYINFO',  'Write about your Family :', NULL , NULL
)
;


CREATE TABLE  `EDIT_LOG_NATIVE_PLACE` (
 `PROFILEID` INT( 11 ) UNSIGNED NOT NULL ,
 `NATIVE_COUNTRY` SMALLINT( 5 ) NOT NULL ,
 `NATIVE_STATE` VARCHAR( 10 ) NOT NULL ,
 `NATIVE_CITY` VARCHAR( 10 ) NOT NULL ,
 `MOD_DT` DATETIME NOT NULL
);
