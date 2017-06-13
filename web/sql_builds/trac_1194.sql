use newjs;
Update `COMMUNITY_PAGES` SET H1_TAG =REPLACE(H1_TAG,' Matrimonials','');
Update `COMMUNITY_PAGES_MAPPING` SET H1_TAG =REPLACE(H1_TAG,' Matrimonials','');
UPDATE `COMMUNITY_PAGES_MAPPING` SET `H1_TAG` = 'Brahmin Kashmiri Pandit Jammu &amp; Kashmir' WHERE `ID` = '3605';

USE newjs;
UPDATE `COMMUNITY_PAGES` SET ACTIVE='N' WHERE LABEL_NAME ='Divorcee' and TYPE = 'OTHERS';
INSERT INTO `COMMUNITY_PAGES` VALUES (1436, 'Divorcee', '', 'MSTATUS', 'D', 1, '/divorcee-matrimony-matrimonials', 'Y', 'L1_SP_1436', 'Life after divorce is challenging, as individuals have to cope up with despair, lack of faith and loneliness. As it is said \'time heals all wounds\', divorced men & women can give a marriage second chance. One has to believe that there is something better, even if it does not seem like it now. Even the society is accepting matrimony of divorced individuals. Second marriage is great and couples can have a wonderful blessed life.', 'Divorcee Matrimony -  Divorcee Matrimonial - Divorcee Marriage', 'Find Lakhs of verified Divorcee Matrimony profiles on Jeevansathi. Safe & Secured matchmaking with exclusive privacy. Add your profile Now!', '', 'Divorcee', 'Y', 'divorcee-matrimonials.jpg', 'Divorcee Matrimony', 'N');
INSERT INTO `COMMUNITY_PAGES` VALUES (1437, 'Divorcee', '', 'MSTATUS', 'D', 1, '/divorcee-grooms-boys', 'Y', 'L1_SP_1437', 'There are ups and downs in married life where at times couples decide to part ways ending the marriage in a divorce. Divorce does not mean the end of life. One can start afresh and divorced men can always give marriage another chance. Society has started accepting second matrimonial alliances of men and life does give a second chance to divorced men who can become grooms again.', 'Divorcee Grooms - Divorcee Boys - Divorcee Groom', 'Find Lakhs of verified Divorcee Grooms profiles at Jeevansathi with photos, horoscope & profile sharing. Join Free & Add your profile Now!', '', 'Divorcee Grooms', 'Y', 'divorcee-grooms-boys.jpg', 'Divorcee Grooms Matrimony', 'G');
INSERT INTO `COMMUNITY_PAGES` VALUES (1438, 'Divorcee', '', 'MSTATUS', 'D', 1, '/divorcee-brides-girls', 'Y', 'L1_SP_1438', 'An unsuccessful marriage does not mean an end to your happiness. Chances are that you may get even a better life partner who can help you to forget any of the unpleasant moments in your last matrimony. Women looking for second marriage now have social acceptance and can start their life afresh. Life brings a perfect opportunity for divorced women and they can become brides once again.', 'Divorcee Brides - Divorcee Girls - Divorcee Bride', 'Find Lakhs of verified Divorcee Brides profiles at Jeevansathi with photos, horoscope & profile sharing. Join Free & Add your profile Now!', '', 'Divorcee Brides', 'Y', 'divorcee-brides-girls.jpg', 'Divorcee Brides Matrimony', 'B');
        
USE MIS;
INSERT INTO `SOURCE`(SourceID, SourceName,GROUPNAME,ACTIVE,CPC,FORCE_EMAIL,NOREG,SourceGifType,PID) VALUES ('L1_SP_1436', 'L1_SP_1436', 'SEO_COM_SP_L1', 'Y', 0, '', '', '', 0);
INSERT INTO `SOURCE`(SourceID, SourceName,GROUPNAME,ACTIVE,CPC,FORCE_EMAIL,NOREG,SourceGifType,PID) VALUES ('L1_SP_1437', 'L1_SP_1437', 'SEO_COM_SP_G_L1', 'Y', 0, '', '', '', 0);
INSERT INTO `SOURCE`(SourceID, SourceName,GROUPNAME,ACTIVE,CPC,FORCE_EMAIL,NOREG,SourceGifType,PID) VALUES ('L1_SP_1438', 'L1_SP_1438', 'SEO_COM_SP_B_L1', 'Y', 0, '', '', '', 0);

use jsadmin;
CREATE TABLE `AUTO_EXPIRY` (
  `PROFILEID` int(11) NOT NULL,
  `TYPE` char(1) NOT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`PROFILEID`)
);

use newjs;
ALTER TABLE  `COMMUNITY_PAGES` ADD  `SORTBY` INT DEFAULT  '0' NOT NULL ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '850'  and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '851' and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '114' and SMALL_LABEL='Jat' LIMIT 1 ;

UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '850'  and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '851' and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '114' and SMALL_LABEL='Jat' LIMIT 1 ;

UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '850'  and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '851' and SMALL_LABEL='Jat' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikh-Jat' WHERE  `ID` =  '114' and SMALL_LABEL='Jat' LIMIT 1 ;


UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikkim/Nepali' WHERE  `ID` =  '907' LIMIT 1 ;

UPDATE  `COMMUNITY_PAGES` SET  `SMALL_LABEL` =  'Sikkim/Nepali' WHERE  `ID` =  '909' LIMIT 1 ;
UPDATE  `COMMUNITY_PAGES` SET  `TYPE` =  'RELIGION' WHERE  `ID` IN('910',911,912,913,914,915,916,917,918,919,920,922,924,925);

update COMMUNITY_PAGES set SORTBY='13' where TYPE='MTONGUE' and VALUE='33' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='MTONGUE' and VALUE='20' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='MTONGUE' and VALUE='10' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='MTONGUE' and VALUE='27' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='12' where TYPE='MTONGUE' and VALUE='3' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='MTONGUE' and VALUE='6' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='11' where TYPE='MTONGUE' and VALUE='31' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='MTONGUE' and VALUE='12' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='MTONGUE' and VALUE='17' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='MTONGUE' and VALUE='16' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='14' where TYPE='MTONGUE' and VALUE='19' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='MTONGUE' and VALUE='7' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='10' where TYPE='MTONGUE' and VALUE='28' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='MTONGUE' and VALUE='25' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='15' where TYPE='MTONGUE' and VALUE='34' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='16' where TYPE='MTONGUE' and VALUE='14' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='17' where TYPE='MTONGUE' and VALUE='13' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='18' where TYPE='MTONGUE' and VALUE='5' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='19' where TYPE='MTONGUE' and VALUE='15' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='20' where TYPE='MTONGUE' and VALUE='29' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='OCCUPATION' and VALUE='20' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='OCCUPATION' and VALUE='31' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='OCCUPATION' and VALUE='1' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='OCCUPATION' and VALUE='13' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='OCCUPATION' and VALUE='24' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='OCCUPATION' and VALUE='35' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='OCCUPATION' and VALUE='22' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='OCCUPATION' and VALUE='34' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='OCCUPATION' and VALUE='33' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='CITY' and VALUE='DE00' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='CITY' and VALUE='MH04' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='CITY' and VALUE='KA02' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='CITY' and VALUE='MH08' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='CITY' and VALUE='AP03' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='CITY' and VALUE='WB05' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='CITY' and VALUE='TN02' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='CITY' and VALUE='UP19' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='CITY' and VALUE='GU01' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='10' where TYPE='CITY' and VALUE='PH00' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='11' where TYPE='CITY' and VALUE='MH05' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='12' where TYPE='CITY' and VALUE='RA07' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='15' where TYPE='CITY' and VALUE='HA03' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='13' where TYPE='CITY' and VALUE='UP25' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='14' where TYPE='CITY' and VALUE='MP08' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='16' where TYPE='CITY' and VALUE='BI06' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='17' where TYPE='CITY' and VALUE='OR01' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='18' where TYPE='CITY' and VALUE='UP12' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='19' where TYPE='CITY' and VALUE='UP18' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='20' where TYPE='CITY' and VALUE='HA02' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='21' where TYPE='CITY' and VALUE='PU07' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='22' where TYPE='CITY' and VALUE='MH12' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='RELIGION' and VALUE='1' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='RELIGION' and VALUE='2' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='RELIGION' and VALUE='3' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='RELIGION' and VALUE='4' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='RELIGION' and VALUE='9' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='RELIGION' and VALUE='7' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='CASTE' and VALUE='25' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='14' where TYPE='CASTE' and VALUE='152' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='CASTE' and VALUE='76' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='12' where TYPE='CASTE' and VALUE='116' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='10' where TYPE='CASTE' and VALUE='94' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='CASTE' and VALUE='78' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='CASTE' and VALUE='17' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='15' where TYPE='CASTE' and VALUE='18' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='CASTE' and VALUE='82' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='16' where TYPE='CASTE' and VALUE='175' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='17' where TYPE='CASTE' and VALUE='146' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='13' where TYPE='CASTE' and VALUE='123' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='18' where TYPE='CASTE' and VALUE='20' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='19' where TYPE='CASTE' and VALUE='121' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='11' where TYPE='CASTE' and VALUE='103' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='CASTE' and VALUE='89' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='CASTE' and VALUE='71' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='20' where TYPE='CASTE' and VALUE='270' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='21' where TYPE='CASTE' and VALUE='109' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='22' where TYPE='CASTE' and VALUE='174' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='23' where TYPE='CASTE' and VALUE='156' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='24' where TYPE='CASTE' and VALUE='64' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='CASTE' and VALUE='4' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='25' where TYPE='CASTE' and VALUE='129' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='26' where TYPE='CASTE' and VALUE='143' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='CASTE' and VALUE='66' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='27' where TYPE='CASTE' and VALUE='134' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='28' where TYPE='CASTE' and VALUE='70' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='COUNTRY' and VALUE='128' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='COUNTRY' and VALUE='125' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='COUNTRY' and VALUE='126' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='COUNTRY' and VALUE='7' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='COUNTRY' and VALUE='22' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='COUNTRY' and VALUE='88' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='STATE' and VALUE='MH' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='STATE' and VALUE='UP' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='STATE' and VALUE='KA' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='STATE' and VALUE='AP' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='STATE' and VALUE='TN' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='STATE' and VALUE='WB' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='STATE' and VALUE='MP' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='STATE' and VALUE='GU' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='STATE' and VALUE='HA' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='10' where TYPE='STATE' and VALUE='BI' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='11' where TYPE='STATE' and VALUE='KE' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='12' where TYPE='STATE' and VALUE='RA' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='13' where TYPE='STATE' and VALUE='PU' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='14' where TYPE='STATE' and VALUE='OR' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='15' where TYPE='STATE' and VALUE='AS' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='16' where TYPE='STATE' and VALUE='JK' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='17' where TYPE='STATE' and VALUE='HP' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='COUNTRY' and VALUE='128,22,126,125,88,7' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='9' where TYPE='SPECIAL_CASES' and VALUE='Leucoderma' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='8' where TYPE='SPECIAL_CASES' and VALUE='Diabetic' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='7' where TYPE='SPECIAL_CASES' and VALUE='Cancer Survivor' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='6' where TYPE='SPECIAL_CASES' and VALUE='Handicapped' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='4' where TYPE='SPECIAL_CASES' and VALUE='Dumb' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='5' where TYPE='SPECIAL_CASES' and VALUE='Blind' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='3' where TYPE='SPECIAL_CASES' and VALUE='Deaf' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='1' where TYPE='SPECIAL_CASES' and VALUE='HIV' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='2' where TYPE='SPECIAL_CASES' and VALUE='Thalassemia' and PAGE_SOURCE IN('N','B','G');
update COMMUNITY_PAGES set SORTBY='10' where TYPE='MSTATUS' and VALUE='D' and PAGE_SOURCE IN('N','B','G');


