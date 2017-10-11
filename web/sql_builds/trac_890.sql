use MIS;

DROP TABLE IF EXISTS `ShortUrlMobileHitsLog`;
CREATE TABLE `ShortUrlMobileHitsLog` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) NOT NULL,
 `URL` varchar(30) NOT NULL,
 `TIME` datetime NOT NULL,
 PRIMARY KEY (`ID`)
) 
