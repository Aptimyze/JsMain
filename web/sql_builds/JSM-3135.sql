use newjs;
UPDATE  `SMS_TYPE` SET  `MESSAGE` =  'Photo on {USERNAME} not uploaded. Reason: {PHOTO_REJECTION_REASON}. Upload new: {PHOTO_UPLOAD_URL}' WHERE  `ID` =  '11';


use jeevansathi_mailer;
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'We could not make your photo live because of the mentioned reason' WHERE  `MAIL_ID` =  '1743';
