use newjs;
CREATE TABLE `shortURL` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `entryDate` date NOT NULL,
  `ActualID` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `DateIndex` (`entryDate`)
) ENGINE=MyISAM;
