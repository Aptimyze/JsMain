use newjs;
update `SMS_TYPE` set status='N' where ID not in (27, 28, 30, 31, 33, 34, 35, 36, 66, 67, 68, 73, 74) and sms_type!='I';
