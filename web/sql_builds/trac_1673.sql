use newjs;

update SMS_TYPE set status = 'N' where sms_key in ('MATCH_ALERT', 'INCOMPLETE');
