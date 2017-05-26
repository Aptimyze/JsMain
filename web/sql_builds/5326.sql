use newjs;

update JPROFILE set CITY_RES='MH24' WHERE CITY_RES='MH07';
delete from CITY_NEW WHERE VALUE in ('MH07','KE01','KE03','KE02');
update EDIT_LOG set CITY_RES='MH24' WHERE CITY_RES='MH07';
update CITY_NEW set LABEL='Nashik/ Nasik' where VALUE='MH24';
update CITY_NEW set LABEL='Vizag/ Vishakapatnam' where VALUE='AP11';
update CITY_NEW set LABEL='Pune/ Chindwad' where VALUE='MH08';
update CITY_NEW set LABEL='Kozhikhode/ Calicut' where VALUE='KE06';
update JPROFILE set CITY_RES='KE06' WHERE CITY_RES='KE01';
update EDIT_LOG set CITY_RES='KE06' WHERE CITY_RES='KE01';
update CITY_NEW set ALPHA_ORDER=ALPHA_ORDER+1 WHERE ALPHA_ORDER>2521;
insert into CITY_NEW (LABEL,VALUE,BANNERID,STD_CODE,COUNTRY_VALUE,ALPHA_ORDER,USA_STATE) VALUES ('Ropar/ Rupnagar/ Roopnagar','PU17','PU17',1881,51,2522,'N');
update JPROFILE set CITY_RES='KE13' WHERE CITY_RES IN ('KE03','KE02');
update EDIT_LOG set CITY_RES='KE13' WHERE CITY_RES IN ('KE03','KE02');
update CITY_NEW set LABEL='Cochin/ Kochi/ Ernakulam' where VALUE='KE13';

use twowaymatch;
delete from CITY_FEMALE_PERCENT where CITY in ('MH07','KE01','KE03','KE02');
delete from CITY_MALE_PERCENT where CITY in ('MH07','KE01','KE03','KE02');

use jsadmin;
delete from MMM_NEARBRANCH where CITY_VALUE in ('MH07','KE01','KE03','KE02');

use incentive;
delete from BRANCH_CITY WHERE VALUE in ('MH07','KE01','KE03','KE02');
delete FROM ARAMEX_BRANCHES WHERE AR_BRANCH in ('MH07','KE01','KE03','KE02');
