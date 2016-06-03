CREATE TABLE `NET_BANK` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `VALUE` char(20) NOT NULL DEFAULT '',
 `LABEL` varchar(100) NOT NULL DEFAULT '',
 `ORDER_ID` tinyint(2) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `VALUE` (`VALUE`)
) ENGINE=MyISAM;

INSERT INTO `NET_BANK` VALUES ('', 'AND_N', 'Andhra Bank','7');
INSERT INTO `NET_BANK` VALUES ('', 'UTI_N', 'Axis Bank','4');
INSERT INTO `NET_BANK` VALUES ('', 'BOB_N', 'Bank of Baroda','8');
INSERT INTO `NET_BANK` VALUES ('', 'BOI_N', 'Bank of India','9');
INSERT INTO `NET_BANK` VALUES ('', 'CAN_N', 'Canara Bank','10');
INSERT INTO `NET_BANK` VALUES ('', 'CBIBAN_N', 'Citibank Bank','5');
INSERT INTO `NET_BANK` VALUES ('', 'COP_N', 'Corporation Bank','11');
INSERT INTO `NET_BANK` VALUES ('', 'DCB_N', 'DCB Bank','12');
INSERT INTO `NET_BANK` VALUES ('', 'FDEB_N', 'Federal Bank','13');
INSERT INTO `NET_BANK` VALUES ('', 'HDEB_N', 'HDFC Bank','3');
INSERT INTO `NET_BANK` VALUES ('', 'ICPRF_N', 'ICICI Bank','2');
INSERT INTO `NET_BANK` VALUES ('', 'IDBI_N', 'IDBI Bank','14');
INSERT INTO `NET_BANK` VALUES ('', 'IOB_N', 'Indian Overseas Bank','15');
INSERT INTO `NET_BANK` VALUES ('', 'NIIB_N', 'IndusInd Bank','16');
INSERT INTO `NET_BANK` VALUES ('', 'ING_N', 'ING Vysya Bank','17');
INSERT INTO `NET_BANK` VALUES ('', 'JKB_N', 'Jammu &amp; Kashmir Bank','18');
INSERT INTO `NET_BANK` VALUES ('', 'KVB_N', 'Karur Vysya Bank','19');
INSERT INTO `NET_BANK` VALUES ('', 'NKMB_N', 'Kotak Mahindra Bank','6');
INSERT INTO `NET_BANK` VALUES ('', 'LVB_N', 'Lakshmi Vilas Bank','20');
INSERT INTO `NET_BANK` VALUES ('', 'OBPRF_N', 'Oriental Bank of Commerce','21');
INSERT INTO `NET_BANK` VALUES ('', 'NPNB_N', 'Punjab National Bank','22');
INSERT INTO `NET_BANK` VALUES ('', 'SIB_N', 'South Indian Bank','23');
INSERT INTO `NET_BANK` VALUES ('', 'SCB_N', 'Standard Chartered Bank','24');
INSERT INTO `NET_BANK` VALUES ('', 'SBJ_N', 'State Bank of Bikaner and Jaipur','25');
INSERT INTO `NET_BANK` VALUES ('', 'SBH_N', 'State Bank of Hyderabad','26');
INSERT INTO `NET_BANK` VALUES ('', 'SBI_N', 'State Bank of India','1');
INSERT INTO `NET_BANK` VALUES ('', 'SBP_N', 'State Bank of Patiala','28');
INSERT INTO `NET_BANK` VALUES ('', 'UNI_N', 'Union Bank of India','27');
INSERT INTO `NET_BANK` VALUES ('', 'YES_N', 'YES Bank','29');
INSERT INTO `NET_BANK` VALUES ('', '', 'Other Banks','30');

UPDATE billing.SERVICES SET ACTIVE='Y', SHOW_ONLINE='Y', ENABLE='Y' WHERE SERVICEID IN ('M1','M2','M3','M4','M5','M6','M7','M8','M9','M10','M11','M12','ML');













