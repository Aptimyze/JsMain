use jeevansathi_mailer;

INSERT INTO jeevansathi_mailer.EMAIL_TYPE VALUES (1800, 1800, '1month_discount_mailer.tpl', NULL, NULL, NULL, 25, 1, 'membership@jeevansathi.com', 'Jeevansathi 1 month paid membership offer plan', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO jeevansathi_mailer.MAILER_SUBJECT VALUES (1800, 'D', 'Special offer: Try Jeevansathi 1 month paid membership', 'Jeevansathi 1 month paid membership offer plan');

use incentive;

CREATE TABLE `BACKEND_LINK_MAILER` (
 `PROFILEID` int(11) NOT NULL DEFAULT '0',
 `SENT_DATE` date NOT NULL DEFAULT '0000-00-00',
  KEY `SENT_DATE` (`SENT_DATE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1  

use billing;

UPDATE SERVICES SET SHOW_ONLINE='Y' WHERE SERVICEID="P1";  
