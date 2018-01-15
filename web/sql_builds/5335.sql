use billing;

INSERT INTO `SERVICES` ( `ID` , `SERVICEID` , `NAME` , `DESCRIPTION` , `DURATION` , `PRICE_RS` , `PRICE_RS_TAX` , `PRICE_DOL` , `PACKAGE` , `COMPID` , `PACKID` , `ADDON` , `SORTBY` , `SHOW_ONLINE` , `ACTIVE` )
VALUES (
'', 'P6W', 'e-Rishta -45 days', '', '0', '0', '995', '35', 'Y', '', 'PF6W', 'N', '0', 'N', 'Y'
), (
'', 'C6W', 'e-Value Pack -45 days ', '', '0', '0', '1195', '40', 'Y', '', 'PC6W ', 'N', '0', 'N', 'Y'
);

INSERT INTO `PACK_COMPONENTS` ( `ID` , `PACKID` , `COMPID` )
VALUES (
'', 'PC6W', 'D6W'
), (
'', 'PC6W', 'F6W'
);

INSERT INTO `PACK_COMPONENTS` ( `ID` , `PACKID` , `COMPID` )
VALUES (
'', 'PF6W', 'F6W'
);

INSERT INTO `COMPONENTS` ( `ID` , `COMPID` , `NAME` , `DESCRIPTION` , `DURATION` , `PRICE_RS` , `PRICE_DOL` , `RIGHTS` , `TYPE` , `ACC_COUNT` )
VALUES (
'', 'F6W', 'Full Membership-45 days', '', '1.5', '0', '0', 'F', 'D', '0'
), (
'', 'D6W', 'Display -45 days ', '', '1.5', '0', '0', 'D', 'D', '0'
);

UPDATE SERVICES SET PRICE_RS_TAX=995,PRICE_DOL=35,PRICE_RS=ROUND(PRICE_RS_TAX/1.103,2) WHERE SERVICEID='P6W';
UPDATE SERVICES SET PRICE_RS_TAX=1195,PRICE_DOL=40,PRICE_RS=ROUND(PRICE_RS_TAX/1.103,2) WHERE SERVICEID='C6W';

INSERT INTO `DIRECT_CALL_COUNT` ( `SERVICEID` , `COUNT` ) VALUES ( 'P6W', '4'), ('C6W', '4');
