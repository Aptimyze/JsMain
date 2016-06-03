use sms;
CREATE TABLE `PromoSms` (
  `PHONE` varchar(20) DEFAULT NULL,
  `Count` int(5) DEFAULT '0',
  `Source` varchar(200) DEFAULT NULL,
  `DATE` date DEFAULT '0000-00-00',
  KEY `PHONE` (`PHONE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
