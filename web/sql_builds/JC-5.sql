use MOBILE_API;

ALTER TABLE MOBILE_API.APP_NOTIFICATIONS MODIFY TITLE varchar(50) DEFAULT '';

INSERT INTO `APP_NOTIFICATIONS` VALUES (15, 'VD', 'Congratulations! You are selected for special discounts of upto {DISCOUNT}% by Jeevansathi.Offer valid till {EDATE}.Tap to avail offer.', 6, 'ALL', 'Y', 'D', NULL, 0, 'SINGLE', 'Y', '259200', 'A', 'F', 'Get {UPTO} {DISCOUNT}% OFF on all Jeevansathi Plans', 'D');

