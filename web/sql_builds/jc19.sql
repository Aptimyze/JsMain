use billing;

INSERT INTO  `SERVICES` (  `ID` ,  `SERVICEID` ,  `NAME` ,  `DESCRIPTION` ,  `DURATION` ,  `PRICE_RS` ,  `PRICE_RS_TAX` ,  `PRICE_DOL` ,  `PACKAGE` ,  `COMPID` ,  `PACKID` ,  `ADDON` ,  `SORTBY` ,  `SHOW_ONLINE` ,  `ACTIVE` ,  `ENABLE` ,  `MOST_POPULAR` ,  `FREEBIES` ) 
VALUES (
'',  'ESJA6',  'JS Assisted - 6 Months',  '',  '6',  '9968.27',  '10995',  '295',  'Y', NULL ,  'PESJA6',  'N',  '156',  'N',  'Y', NULL ,  'N',  ' N'
);

INSERT INTO  `SERVICES` (  `ID` ,  `SERVICEID` ,  `NAME` ,  `DESCRIPTION` ,  `DURATION` ,  `PRICE_RS` ,  `PRICE_RS_TAX` ,  `PRICE_DOL` ,  `PACKAGE` ,  `COMPID` ,  `PACKID` ,  `ADDON` ,  `SORTBY` ,  `SHOW_ONLINE` ,  `ACTIVE` ,  `ENABLE` ,  `MOST_POPULAR` ,  `FREEBIES` ) 
VALUES (
'',  'ESJA12',  'JS Assisted - 12 Months',  '',  '12',  '17221.21',  '18995',  '395',  'Y', NULL ,  'PESJA12',  'N',  '157',  'N',  'Y', NULL ,  'N',  ' N'
);

INSERT INTO  `PACK_COMPONENTS` (  `ID` ,  `PACKID` ,  `COMPID` ) 
VALUES (
'',  'PESJA6',  'P6'
), (
'',  'PESJA6',  'T6'
);

INSERT INTO  `PACK_COMPONENTS` (  `ID` ,  `PACKID` ,  `COMPID` ) 
VALUES (
'',  'PESJA12',  'P12'
), (
'',  'PESJA12',  'T12'
);

UPDATE  `SERVICES` SET  `PRICE_DOL` =  '195' WHERE  `SERVICEID` =  'ESJA3' LIMIT 1;
