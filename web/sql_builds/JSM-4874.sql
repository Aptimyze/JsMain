use newjs;
CREATE TABLE `CITY_NEW_BAKUPDEC` LIKE `CITY_NEW`;
INSERT INTO `CITY_NEW` ( `ID` , `LABEL` , `VALUE` , `TYPE` , `SORTBY` , `DD_TOP` , `DD_TOP_SORTBY` , `STD_CODE` , `COUNTRY_VALUE` )
VALUES (
'', 'Amravati (Maharastra)', 'MH46', 'CITY', '0', NULL , NULL , NULL , '51'
);


update CITY_NEW target
join
(
  SELECT ID, (
@rownumber := @rownumber +1
) AS rownum
FROM CITY_NEWB
CROSS JOIN (

SELECT @rownumber :=0
)r
ORDER BY LABEL ASC
) source on target.ID = source.ID    
set SORTBY = rownum 