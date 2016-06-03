use newjs;
update  `SMS_TYPE` set status='N'
WHERE sms_key
IN (
'EOI_WEEKLY',  'ACCEPT_WEEKLY'
)
