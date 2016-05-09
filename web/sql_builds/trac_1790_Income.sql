use newjs;
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `INCOME`
-- 
RENAME TABLE INCOME  TO INCOME_BACKUP;

CREATE TABLE `INCOME` (
  `ID` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `LABEL` varchar(100) NOT NULL DEFAULT '',
  `VALUE` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `TYPE` enum('RUPEES','DOLLARS') NOT NULL DEFAULT 'RUPEES',
  `SORTBY` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `VISIBLE` char(1) NOT NULL DEFAULT '',
  `MIN_LABEL` varchar(100) NOT NULL,
  `MIN_VALUE` char(2) NOT NULL,
  `MAX_LABEL` varchar(100) NOT NULL,
  `MAX_VALUE` char(2) NOT NULL,
  `MAPPED_MIN_VAL` char(2) NOT NULL,
  `MAPPED_MAX_VAL` char(2) NOT NULL,
  `TRENDS_SORTBY` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `INCOME`
-- 

INSERT INTO `INCOME` VALUES (2, 'Under Rs. 1,00,000', 2, 'RUPEES', 2, 'Y', 'No Income', '0', 'Rs.1 Lakh', '2', '0', '12', 1);
INSERT INTO `INCOME` VALUES (3, 'Rs.1,00,001 - 2,00,000', 3, 'RUPEES', 3, 'Y', 'Rs.1 Lakh', '2', 'Rs.2 Lakh', '3', '0', '12', 2);
INSERT INTO `INCOME` VALUES (4, 'Rs.2,00,001 - 3,00,000', 4, 'RUPEES', 4, 'Y', 'Rs.2 Lakh', '3', 'Rs.3 Lakh', '4', '0', '12', 3);
INSERT INTO `INCOME` VALUES (5, 'Rs.3,00,001 - 4,00,000', 5, 'RUPEES', 5, 'Y', 'Rs.3 Lakh', '4', 'Rs.4 Lakh', '5', '0', '12', 4);
INSERT INTO `INCOME` VALUES (6, 'Rs.4,00,001 - 5,00,000', 6, 'RUPEES', 6, 'Y', 'Rs.4 Lakh', '5', 'Rs.5 Lakh', '6', '12', '13', 5);
INSERT INTO `INCOME` VALUES (8, 'Under $25,000', 8, 'DOLLARS', 17, 'Y', 'No Income', '0', '$25,000', '12', '0', '5', 4);
INSERT INTO `INCOME` VALUES (9, '$25,001 - 40,000', 9, 'DOLLARS', 18, 'Y', '$25,001', '12', '$40,000', '13', '5', '6', 5);
INSERT INTO `INCOME` VALUES (10, '$40,001 - 60,000', 10, 'DOLLARS', 19, 'Y', '$40,001', '13', '$60,000', '14', '6', '8', 7);
INSERT INTO `INCOME` VALUES (11, '$60,001 - 80,000', 11, 'DOLLARS', 20, 'Y', '$60,001', '14', '$80,000', '15', '8', '9', 8);
INSERT INTO `INCOME` VALUES (12, '$80,001 - 100,000', 12, 'DOLLARS', 21, 'Y', '$80,001', '15', '$100,000', '16', '9', '10', 9);
INSERT INTO `INCOME` VALUES (13, '$100,001 - 150,000', 13, 'DOLLARS', 22, 'Y', '$100,001', '16', '$150,000', '17', '10', '11', 10);
INSERT INTO `INCOME` VALUES (21, '$150,001 - 200,000', 21, 'DOLLARS', 23, 'Y', '$150,001', '17', '$200,000', '18', '11', '20', 12);
INSERT INTO `INCOME` VALUES (14, '$200,001 and above', 14, 'DOLLARS', 24, 'Y', '$200,001', '18', 'and above', '19', '20', '19', 14);
INSERT INTO `INCOME` VALUES (15, 'No Income', 15, 'RUPEES', 0, 'Y', '', '0', '', '0', '0', '0', 0);
INSERT INTO `INCOME` VALUES (16, 'Rs.5,00,001 - 7,50,000', 16, 'RUPEES', 7, 'Y', 'Rs.5 Lakh', '6', 'Rs.7.5 Lakh', '7', '12', '14', 6);
INSERT INTO `INCOME` VALUES (17, 'Rs.7,50,001 - 10,00,000', 17, 'RUPEES', 8, 'Y', 'Rs.7.5 Lakh', '7', 'Rs.10 Lakh', '8', '13', '14', 7);
INSERT INTO `INCOME` VALUES (18, 'Rs.10,00,001 - 15,00,000', 18, 'RUPEES', 9, 'Y', 'Rs.10 Lakh', '8', 'Rs.15 Lakh', '9', '14', '15', 8);
INSERT INTO `INCOME` VALUES (20, 'Rs.15,00,001 - 20,00,000', 20, 'RUPEES', 10, 'Y', 'Rs.15 Lakh', '9', 'Rs.20 Lakh', '10', '15', '16', 9);
INSERT INTO `INCOME` VALUES (22, 'Rs.20,00,001 - 25,00,000', 22, 'RUPEES', 11, 'Y', 'Rs.20 Lakh', '10', 'Rs.25 Lakh', '11', '16', '17', 10);
INSERT INTO `INCOME` VALUES (23, 'Rs.25,00,001 - 35,00,000', 23, 'RUPEES', 12, 'Y', 'Rs.25 Lakh', '11', 'Rs.35 Lakh', '20', '17', '18', 11);
INSERT INTO `INCOME` VALUES (19, 'Max Income', 19, 'RUPEES', 25, 'N', 'and above', '19', 'and above', '19', '19', '19', 0);
INSERT INTO `INCOME` VALUES (24, 'Rs.35,00,001 - 50,00,000', 24, 'RUPEES', 13, 'Y', 'Rs.35 Lakh', '20', 'Rs.50 Lakh', '21', '17', '19', 12);
INSERT INTO `INCOME` VALUES (25, 'Rs.50,00,001 - 70,00,000', 25, 'RUPEES', 14, 'Y', 'Rs.50 Lakh', '21', 'Rs.70 Lakh', '22', '18', '19', 13);
INSERT INTO `INCOME` VALUES (26, 'Rs.70,00,001 - 100,00,000', 26, 'RUPEES', 15, 'Y', 'Rs.70 Lakh', '22', 'Rs.1 Crore', '23', '18', '19', 14);
INSERT INTO `INCOME` VALUES (27, 'Rs.100,00,001 and above', 27, 'RUPEES', 16, 'Y', 'Rs.1 Crore', '23', 'and above', '19', '18', '19', 15);
        
