UPDATE  MIS.`MIS_MAINPAGE` SET  `MAIN_URL` =  '/operations.php/crmMis/gatewayWiseMis?cid=$cid',
`JUMP_URL` =  '/operations.php/crmMis/gatewayWiseMis?cid=$cid&outside=Y' WHERE  `ID` =  '12' LIMIT 1 ;

ALTER TABLE  billing.`ORDERS_DEVICE` ADD INDEX (  `ID` );
ALTER TABLE  billing.`ORDERS_DEVICE` ADD INDEX (  `PROFILEID` );