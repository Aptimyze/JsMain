use jeevansathi_mailer;

INSERT INTO jeevansathi_mailer.EMAIL_TYPE (`ID`, `MAIL_ID`, `TPL_LOCATION`, `HEADER_TPL`, `FOOTER_TPL`, `TEMPLATE_EX_LOCATION`, `MAIL_GROUP`, `CUSTOM_CRITERIA`, `SENDER_EMAILID`, `DESCRIPTION`, `MEMBERSHIP_TYPE`, `GENDER`, `PHOTO_PROFILE`, `REPLY_TO_ENABLED`, `FROM_NAME`, `REPLY_TO_ADDRESS`, `MAX_COUNT_TO_BE_SENT`, `REQUIRE_AUTOLOGIN`, `FTO_FLAG`, `PRE_HEADER`, `PARTIALS`) 
VALUES ('', 1820, 'Field_Visit_Request_Mailer.tpl', NULL, NULL, NULL, 25, 1, 'info@jeevansathi.com', 'Jeevansathi Field Visit Request Submission', 'D', NULL, NULL, NULL, 'Jeevansathi.com', 'NULL', NULL, NULL, NULL, NULL, '');

INSERT INTO jeevansathi_mailer.MAILER_SUBJECT (`MAIL_ID`, `SUBJECT_TYPE`, `SUBJECT_CODE`, `DESCRIPTION`) 
VALUES (1820, 'D', 'Request for visit has been submitted', 'Jeevansathi Field Visit Request Submission');

use newjs;

INSERT INTO newjs.SMS_TYPE VALUES 
(NULL, 'FIELD_VISIT_SCHEDULE', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your request for verification visit by Jeevansathi has been submitted and will be processed within 48 hours. For any queries, please call us at 18004196299.');