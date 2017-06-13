USE newjs;

UPDATE COMMUNITY_PAGES SET VALUE = '128,22,126,125,88,7' WHERE VALUE='128' and LABEL_NAME='NRI' and ACTIVE='Y' and TYPE='COUNTRY';

UPDATE COMMUNITY_PAGES SET TYPE = 'COUNTRY',
VALUE = '128,22,126,125,88,7' WHERE ID = '998' LIMIT 1 ;

UPDATE COMMUNITY_PAGES SET TYPE = 'COUNTRY',
VALUE = '128,22,126,125,88,7' WHERE ID = '999' LIMIT 1 ;

UPDATE `COMMUNITY_PAGES` 
SET `CONTENT` = 'NRI, are the people who are born in India, but are living in different parts of the world. NRI''s in spite of living in foreign countries always want their children to get married to the bride or groom living in India. The cultures and the values of Indian society are always appreciated, respected and also plays a major role in stability of the marriage bond. NRI matrimonies are performed according to the Indian traditions and they look for educated and well mannered partners for their children.' 
WHERE `ID` = '691' LIMIT 1 ;

