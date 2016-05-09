use incentive;

INSERT INTO `LOCATION` ( `ID` , `VALUE` , `NAME` , `STATE` , `REGION` )
VALUES (
'', 'MH06', 'NANDED', 'MH', ''
), (
'', 'MP07', 'GWALIOR', 'MP', ''
), (
'', 'MH09', 'JABALPUR', 'MH', ''
), (
'', 'UP30', 'VARANASI', 'MP', ''
), (
'', 'PU10', 'JALANDHAR', 'MP', ''
);


INSERT INTO `SUB_LOCATION` ( `ID` , `LABEL` , `VALUE` , `PRIORITY` )
VALUES (
'', 'Nanded', 'MH06', 'MH06'
), (
'', 'Gwalior', 'MP07', 'MP07'
), (
'', 'Jabalpur', 'MP09', 'MP09'
), (
'', 'Varanasi', 'UP30', 'UP30'
), (
'', 'Jalandhar', 'PU10', 'PU10'
);

use billing;

INSERT INTO `BRANCHES` ( `ID` , `NAME` , `REGION_ACC` , `VALUE` )
VALUES (
'', 'NANDED', '', ''
), (
'', 'GWALIOR', '', ''
), (
'', 'JABALPUR', '', ''
), (
'', 'VARANASI', '', ''
), (
'', 'JALANDHAR', '', ''
);
