INSERT INTO jeevansathi_mailer.EMAIL_TYPE (MAIL_ID, TPL_LOCATION, HEADER_TPL, FOOTER_TPL, TEMPLATE_EX_LOCATION, MAIL_GROUP, CUSTOM_CRITERIA, SENDER_EMAILID, DESCRIPTION, MEMBERSHIP_TYPE, GENDER, PHOTO_PROFILE, REPLY_TO_ENABLED, FROM_NAME, REPLY_TO_ADDRESS, MAX_COUNT_TO_BE_SENT, REQUIRE_AUTOLOGIN, FTO_FLAG, PRE_HEADER, PARTIALS) VALUES (1858, 'negativeTreatmentDeleteProfileEmail.tpl', NULL, 'revamp_footer.tpl', NULL, 27, 1, 'info@jeevansathi.com`', 'Profile deleted due to negative treatment', 'D', NULL, NULL, NULL, 'Jeevansathi Info`', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');


CREATE TABLE incentive.NEGATIVE_DELETE_EMAIL_LOG(
  PROFILEID int(11) DEFAULT NULL,
  ENTRY_DT datetime DEFAULT NULL,
  KEY PROFILEID (PROFILEID,ENTRY_DT)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        
