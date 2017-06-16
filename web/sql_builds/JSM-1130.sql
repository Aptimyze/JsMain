use reg;

INSERT INTO  `EDIT_FIELDS` (  `FIELD_NAME` ,  `TYPE` ,  `CONSTRAINT_CLASS` ,  `TABLE_NAME` ) 
VALUES (
'P_HAVECHILD',  'dropdown',  'partner_havechild',  'JPARTNER:CHILDREN'
);

use Assisted_Product;

ALTER TABLE  `AP_TEMP_DPP` CHANGE  `CHILDREN`  `CHILDREN` CHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;	
use newjs;	

UPDATE  `CASTE` SET  `SMALL_LABEL` =  '-Sikh: Bhatia' WHERE  `ID` =  '474' LIMIT 1 ;

UPDATE  `CASTE` SET  `SMALL_LABEL` =  '-Hindu: Bhatia' WHERE  `ID` =  '231' LIMIT 1 ;