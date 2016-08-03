USE jeevansathi_mailer;

INSERT INTO jeevansathi_mailer.EMAIL_TYPE ( ID , MAIL_ID , TPL_LOCATION , HEADER_TPL , FOOTER_TPL , TEMPLATE_EX_LOCATION , MAIL_GROUP , CUSTOM_CRITERIA , SENDER_EMAILID , DESCRIPTION , MEMBERSHIP_TYPE , GENDER , PHOTO_PROFILE , REPLY_TO_ENABLED , FROM_NAME , REPLY_TO_ADDRESS , MAX_COUNT_TO_BE_SENT , REQUIRE_AUTOLOGIN , FTO_FLAG , PRE_HEADER , PARTIALS ) VALUES ('', '1836', 'memExpiryForContactViewed.tpl', NULL , NULL , NULL , '29', '1', 'membership@jeevansathi.com', 'Contact details viewed by you in your last membership', 'D', NULL , NULL , NULL , 'Jeevansathi Membership', NULL , NULL , NULL , NULL , NULL , '');

INSERT INTO jeevansathi_mailer.MAILER_SUBJECT (  MAIL_ID ,  SUBJECT_TYPE ,  SUBJECT_CODE ,  DESCRIPTION ) VALUES ('1836',  'D',  'Contact details viewed by you in your last membership',  'Membership Expiry mail for Viewed Contacts');

use billing;
CREATE TABLE `MEM_EXPIRY_CONTACTS_LOG` (
 `PROFILEID` int(11) NOT NULL,
 `ENTRY_DT` datetime NOT NULL,
 KEY `PROFILEID` (`PROFILEID`),
 KEY `ENTRY_DT` (`ENTRY_DT`)
) ENGINE=InnoDB;
~                                                                      
