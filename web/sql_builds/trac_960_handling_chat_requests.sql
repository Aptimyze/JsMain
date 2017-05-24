use userplane;
Drop table  IF EXISTS CHAT_REQUEST_NEW;
create table CHAT_REQUEST_NEW like CHAT_REQUESTS;
rename table CHAT_REQUESTS to CHAT_REQUEST_TEMP;rename table CHAT_REQUEST_NEW to CHAT_REQUESTS; rename table CHAT_REQUEST_TEMP to CHAT_REQUEST_NEW;
create table IF NOT EXISTS DELETED_CHAT_REQUESTS like CHAT_REQUEST_NEW; 
insert into DELETED_CHAT_REQUESTS select * from CHAT_REQUEST_NEW WHERE TIMEOFINSERTION < DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH );
DELETE FROM CHAT_REQUEST_NEW WHERE TIMEOFINSERTION < DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH );
rename table CHAT_REQUEST_NEW to CHAT_REQUEST_TEMP;
rename table CHAT_REQUESTS TO CHAT_REQUEST_NEW;
rename table CHAT_REQUEST_TEMP to CHAT_REQUESTS;
insert ignore into CHAT_REQUESTS select * from CHAT_REQUEST_NEW; 
