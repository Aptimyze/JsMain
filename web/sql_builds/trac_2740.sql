use billing;

INSERT INTO `SERVICES` ( `ID` , `SERVICEID` , `NAME` , `DESCRIPTION` , `DURATION` , `PRICE_RS` , `PRICE_RS_TAX` , `PRICE_DOL` , `PACKAGE` , `COMPID` , `PACKID` , `ADDON` , `SORTBY` , `SHOW_ONLINE` , `ACTIVE` , `MOST_POPULAR` , `FREEBIES` , `ENABLE` )
VALUES (
'', 'L12', 'Profile home delivery - 12 months', '', '12', '0', '2395', '72', 'N', 'L12', '', 'Y', '0', 'Y', 'Y', 'N', ' ', 'Y'
);


UPDATE `SERVICES` SET `PRICE_RS_TAX` = '695',
`PRICE_DOL` = '21' WHERE `SERVICEID` = 'L3' LIMIT 1 ;
