use jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` , `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1849',  '1843',  'eoi_mailer_ei.tpl',  'eoi_header.tpl',  'eoi_footer.tpl', NULL ,  '5',  '1',  'contacts@jeevansathi.com', NULL ,  'D', NULL , NULL , NULL ,  'Jeevansathi Contacts', NULL , NULL ,  'Y', NULL , 'Please add contacts@jeevansathi.com> to your address book to ensure delivery of this mail into you inbox',  ''
);
