/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  palash
 * Created: 7 Mar, 2017
 */

use jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1847',  'autoReminderOnPhotoUpload.tpl', NULL , NULL , NULL ,  '5',  '1',  'contacts@jeevansathi.com',  'auto reminder mailer after photo uploads of the sender of interest',  'D', NULL , NULL , NULL ,  'Jeevansathi Contacts', NULL , NULL , NULL , NULL , NULL ,  ''
);

