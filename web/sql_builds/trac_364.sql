use newjs;
ALTER TABLE  `SMS_DETAIL` CHANGE  `SMS_KEY`  `SMS_KEY` CHAR( 20 );

DROP TABLE IF EXISTS `SMSCONFIRM`;
CREATE TABLE `SMSCONFIRM` (
`ID` INT( 5 ) NOT NULL AUTO_INCREMENT ,
`PROFILEID` MEDIUMINT( 11 ) NOT NULL ,
`SMS_KEY` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY ( `ID` )
);

DROP TABLE IF EXISTS `SMS_TYPE`;
CREATE TABLE `SMS_TYPE` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `SMS_KEY` varchar(20) DEFAULT NULL,
 `SMS_TYPE` varchar(5) DEFAULT NULL,
 `PRIORITY` float NOT NULL,
 `SUBSCRIPTION` char(1) DEFAULT NULL,
 `GENDER` char(1) DEFAULT NULL,
 `COUNT` varchar(10) NOT NULL,
 `TIME_CRITERIA` int(2) NOT NULL,
 `CUSTOM_CRITERIA` int(2) NOT NULL DEFAULT '0',
 `SMS_SUBSCRIPTION` varchar(10) DEFAULT NULL,
 `STATUS` char(1) NOT NULL,
 `MESSAGE` varchar(200) NOT NULL,
 PRIMARY KEY (`ID`)
);

INSERT INTO `SMS_TYPE` VALUES (1, 'PROFILE_APPROVE', 'I', 2.1, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Jeevansathi.com team has screened & made live your profile {USERNAME}. Login with password {PASSWORD} & Contact members u like via Expression of Interest');
INSERT INTO `SMS_TYPE` VALUES (2, 'PROFILE_DISAPPROVE', 'I', 2.3, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your Profile {USERNAME} is not complete & hence you cannot use Jeevansathi. Login with your Password {PASSWORD} & complete your profile.');
INSERT INTO `SMS_TYPE` VALUES (3, 'DETAIL_CONFIRM', 'I', 3, 'A', 'A', 'SINGLE', 20, 0, 'SERVICE', 'Y', 'Your profile details are- Date of Birth:{DTOFBIRTH}, {MSTATUS}, {GENDER}. To modify call toll free on {TOLLNO}, or on {NOIDALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (4, 'MTONGUE_CONFIRM', 'I', 4, 'A', 'A', 'SINGLE', 20, 0, 'SERVICE', 'Y', 'You have entered {MTONGUE} as your Mother Tongue on your Jeevansathi.com profile {USERNAME}. To confirm, send M to {VALUEFRSTNO}. To modify call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (5, 'REGISTER_CONFIRM', 'I', 1, 'A', 'A', 'SINGLE', 5, 0, 'SERVICE', 'Y', 'A profile has been registered on Jeevansathi.com with your phone nos. In case you have not registered then send "NO" to {VALUEFRSTNO}.');
INSERT INTO `SMS_TYPE` VALUES (6, 'REGISTER_RESPONSE', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Thank you for informing Jeevansathi.com. We are removing your number. Sometimes people accidently type wrong numbers. For clarification call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (7, 'FORGOT_PASSWORD', 'I', 11, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your jeevansathi.com username is {USERNAME_FULL} and password is {PASSWORD}. Login now to find your Jeevansathi.');
INSERT INTO `SMS_TYPE` VALUES (8, 'PHONE_VERIFY_REG', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'N', 'Thank you for verifying your number. Visit www.Jeevansathi. com or click {BACK_MATCH_URL} to find people who would like your profile. Helpline {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (9, 'PAYMENT_PHONE_VERIFY', 'I', 82, 'P', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'To view contact details of other members on Jeevansathi, its compulsory for you to verify yr own phone nos. SMS {VERIFY_CODE} from your mobile to {VALUEFRSTNO} to verify.');
INSERT INTO `SMS_TYPE` VALUES (10, 'PHOTO_APPROVE', 'I', 70, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your photo is screened & made live on Jeevansathi. Click {BACK_MATCH_URL} to see {NOSLIKEME} members whose criteria u match. Express Interest to contact for free.');
INSERT INTO `SMS_TYPE` VALUES (11, 'PHOTO_DISAPPROVE', 'I', 71.2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, we regret to inform you that your photo could not go live as the picture quality was not good. Please upload another photo on Jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (12, 'PAYMENT_ANY', 'I', 22, 'P', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Thank you for buying Jeevansathi.com membership. We have received a payment of Rs.{PAYMENT} for your profile ID {USERNAME}. Your membership will be activated soon.');
INSERT INTO `SMS_TYPE` VALUES (13, 'PAYMENT_MEMBERSHIP', 'I', 22, 'P', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Paid membership for yr profile {USERNAME} on Jeevansathi. com has been activated. Logon to {URL_CONTACTS} to send emails, chat requests or view phone nos.');
INSERT INTO `SMS_TYPE` VALUES (14, 'PHONE_VERIFY', 'I', 10, 'F', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your phone number has been verified for your profile ID {USERNAME} on Jeevansathi.com. See phone no of other members call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (15, 'PHONE_VERIFY', 'I', 10, 'P', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your phone number has been verified for your profile ID {USERNAME} on Jeevansathi. com. See phone/email of profiles you like at {URL_ACCEPT}');
INSERT INTO `SMS_TYPE` VALUES (16, 'PHONE_UNVERIFY', 'I', 10, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Phone verification has failed for your profile ID {USERNAME}. Please call customer care at {TOLLNO} for help from the number you wish to verify.');
INSERT INTO `SMS_TYPE` VALUES (17, 'AP_EDIT', 'I', 5, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'To help you find better matches,Jeevansathi. com team has edited parts of your Desired Partner Profile. Click {UDESPID} to see. Helpline {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (18, 'MADELIVE_1', 'D', 6, 'A', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Dear {USERNAME} for help in using Jeevansathi. com website, call {TOLLNO} or visit http://www.jeevansathi.com/P/faq_main.php');
INSERT INTO `SMS_TYPE` VALUES (19, 'MADELIVE_7', 'D', 6, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Dear {USERNAME} for help in using Jeevansathi.com website contact {ANAME} in {ACITY} at {AMOBILE}, {ALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (20, 'MADELIVE_35', 'D', 6, 'A', 'A', 'SINGLE', 35, 0, 'SERVICE', 'Y', 'Dear {USERNAME} to get phone no. of profiles you like on Jeevansathi.com website contact {ANAME} in {ACITY} at {AMOBILE},{ALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (21, 'MADELIVE_28', 'D', 6, 'A', 'A', 'SINGLE', 28, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, there are {FRDDPP} profiles from your community on Jeevansathi.com. To know about them contact {ANAME}: {ACITY} at {AMOBILE}, {ALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (22, 'MADELIVE_14', 'D', 6, 'A', 'A', 'SINGLE', 14, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, to know how to contact profiles on Jeevansathi.com call {ANAME} in {ACITY} at {AMOBILE},{ALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (23, 'MADELIVE_21', 'D', 6, 'A', 'A', 'SINGLE', 21, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, other members have asked to see photo on your profile. To add Photo contact {ANAME} in {ACITY} at {AMOBILE},{ALANDL}.');
INSERT INTO `SMS_TYPE` VALUES (24, 'MADELIVE_10', 'D', 6, 'A', 'A', 'SINGLE', 10, 0, 'SERVICE', 'Y', 'Dear {USERNAME} the Jeevansathi service center in {ACITY} is at {AADDRSS}.');
INSERT INTO `SMS_TYPE` VALUES (25, 'PHOTO_REQUEST', 'D', 50, 'A', 'M', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi profile {URL_PROFILE} -{AGE}yrs, {HEIGHT}, {CASTE} wants to see your photo. Upload or Email to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (26, 'PHOTO_REQUEST', 'D', 50, 'A', 'F', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi profile {URL_PROFILE} -{AGE}yrs, {HEIGHT}, {EDU_LEVEL},{INCOME} ,{CITY_RES} wants to see your photo. Upload or email to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (27, 'EOI', 'D', 45, 'F', 'M', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member {AGE}yrs,{HEIGHT}, {MTONGUE}, {CASTE}, {EDU_LEVEL} in {CITY_RES} has liked your profile. Click {URL_PROFILE} or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (28, 'EOI', 'D', 45, 'F', 'F', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member {AGE}yrs,{HEIGHT}, {MTONGUE}, {EDU_LEVEL}, {INCOME}, {CITY_RES} has liked your profile. Click {URL_PROFILE} or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (29, 'EOI', 'D', 45, 'P', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member {AGE} yrs,{HEIGHT},{CASTE},{EDU_LEVEL},{INCOME},{OCCUPATION} has liked yr profile. Click {URL_EOI} & accept to contact info');
INSERT INTO `SMS_TYPE` VALUES (30, 'EOI', 'D', 45, 'F', 'M', 'MUL', 1, 0, 'SERVICE', 'Y', '{EOI_COUNT} Jeevansathi members liked yr profile incl-{AGE} yrs,{HEIGHT},{WEIGHT} - {URL_PROFILE} To see all click {URL_EOI} Call {TOLLNO}');
INSERT INTO `SMS_TYPE` VALUES (31, 'EOI', 'D', 45, 'F', 'F', 'MUL', 1, 0, 'SERVICE', 'Y', '{EOI_COUNT} Jeevansathi members liked yr profile incl-{AGE} yrs,{HEIGHT},{INCOME} - {URL_PROFILE} To see all click {URL_EOI} Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (32, 'EOI', 'D', 45, 'P', 'A', 'MUL', 1, 0, 'SERVICE', 'Y', '{EOI_COUNT} Jeevansathi members liked yr profile incl-{AGE}yrs, {CASTE} , {EDU_LEVEL}, {INCOME}, {URL_PROFILE} To see all click {URL_EOI}');
INSERT INTO `SMS_TYPE` VALUES (33, 'ACCEPT', 'D', 38, 'F', 'M', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member-{AGE}yrs, {HEIGHT}, {WEIGHT}, {EDU_LEVEL}, {OCCUPATION}, {CITY_RES} has Accepted yr Expression of  Interest. Click {URL_ACCEPT} or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (34, 'ACCEPT', 'D', 38, 'F', 'F', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member- {AGE}yrs, {EDU_LEVEL}, {INCOME},{OCCUPATION} in {CITY_RES} has Accepted yr Expression of  Interest. Click {URL_ACCEPT} or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (35, 'ACCEPT', 'D', 38, 'F', 'A', 'MUL', 1, 0, 'SERVICE', 'Y', '{ACCEPT_COUNT} Jeevansathi members have liked yr profile/Accepted yr Expression of Interest. To see all click {URL_ACCEPT} Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (36, 'ACCEPT', 'D', 38, 'P', 'A', 'MUL', 1, 0, 'SERVICE', 'Y', '{ACCEPT_COUNT} Jeevansathi members have liked yr profile/Accepted yr Expression of Interest. To see all click {URL_ACCEPT}');
INSERT INTO `SMS_TYPE` VALUES (37, 'MEM_EXPIRE_A15', 'D', 16, 'P', 'A', 'SINGLE', -15, 0, 'SERVICE', 'Y', 'Membership on yr Jeevansathi profile {USERNAME} will expire on {EXPDATE}. Renew & ensure you dont loose details of members contacted earlier. Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (38, 'MEM_EXPIRE_A10', 'D', 16, 'P', 'A', 'SINGLE', -10, 0, 'SERVICE', 'Y', 'Membership on yr Jeevansathi profile {USERNAME} will expire on {EXPDATE}. Renew & ensure you dont loose details of members contacted earlier. Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (39, 'MEM_EXPIRE_A5', 'D', 16, 'P', 'A', 'SINGLE', -5, 0, 'SERVICE', 'Y', 'Membership on yr Jeevansathi profile {USERNAME} will expire on {EXPDATE}. Renew & ensure you dont loose details of members contacted earlier. Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (40, 'MEM_EXPIRE_B1', 'D', 16, 'F', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Membership on yr Jeevansathi profile {USERNAME} has expired. Renew before {AFTREXPDATE} & ensure you dont loose details of members contacted earlier. Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (41, 'MEM_EXPIRE_B5', 'D', 16, 'F', 'A', 'SINGLE', 5, 0, 'SERVICE', 'Y', 'Membership on yr Jeevansathi profile {USERNAME} has expired. Renew before {AFTREXPDATE} & ensure you dont loose details of members contacted earlier. Call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (42, 'INVALID_EMAIL', 'D', 2, 'A', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Mails to your current email id are bouncing back. To continue using the website update your email id on jeevansathi.com or click {EDIT_CONTACT_LAYER}');
INSERT INTO `SMS_TYPE` VALUES (43, 'DISCOUNT_0', 'D', 49, 'F', 'A', 'SINGLE', 0, 0, 'PROMO', 'Y', 'Congrats! Dear {USERNAME},get special discount of {DISCOUNT}% on Jeevansathi. com membership. To avail call {TOLLNO} or call {NOIDALANDL} before {DISCOUNTDATE}.');
INSERT INTO `SMS_TYPE` VALUES (44, 'DISCOUNT_3', 'D', 49, 'F', 'A', 'SINGLE', 3, 0, 'PROMO', 'Y', 'Last 4 days left to get your exclusive discount of {DISCOUNT}% on Jeevansathi. com memberships. To avail call {TOLLNO} or call {NOIDALANDL} before {DISCOUNTDATE}.');
INSERT INTO `SMS_TYPE` VALUES (45, 'DISCOUNT_7', 'D', 49, 'F', 'A', 'SINGLE', 7, 0, 'PROMO', 'Y', 'Dont Miss it!! Your special discount of {DISCOUNT}% on Jeevansathi. com is ending today. To avail call {TOLLNO} or call {NOIDALANDL} before {DISCOUNTDATE}.');
INSERT INTO `SMS_TYPE` VALUES (46, 'CALLNOW_FAIL', 'D', 48, 'F', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member {AGE}yrs, {HEIGHT}, {CASTE}, {INCOME} had called your phone but your number was out of reach. Click {URL_PROFILE} & view details.');
INSERT INTO `SMS_TYPE` VALUES (47, 'PHONE_VERIFY', 'MON', 82, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', 'Verify your phone number to make your profile {USERNAME} appear on top of  "Search results" on Jeevansathi. SMS {VERIFY_CODE} from your mobile to {VALUEFRSTNO} to verify.');
INSERT INTO `SMS_TYPE` VALUES (48, 'PHOTO_REQ_WEEK', 'MON', 50, 'A', 'A', 'MUL', 7, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} members on Jeevansathi.com have requested you for your photo. Login to Jeevansathi.com & upload photos or email with profile id to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (49, 'TAKE_MEMB', 'MON', 114, 'F', 'A', 'SINGLE', 60, 0, 'PROMO', 'Y', 'See contact nos on Jeevansathi.com /Chat with prospective partners. Click on "Contact Details" button on  Jeevansathi.com or call {TOLLNO} for help.');
INSERT INTO `SMS_TYPE` VALUES (50, 'ADD_PHOTO', 'TUE', 51, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, Jeevansathi members are not responding to your messages as yr profile does not have a photo. Email photo with profile id to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (51, 'MATCHALERT', 'TUE', 102, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y',  'Dear {USERNAME}, Jeevansathi. com has found {MATCH_COUNT} matches which suite your criteria. Check these on {URL_MATCH} Or login the "My Contacts" section');
INSERT INTO `SMS_TYPE` VALUES (52, 'PHOTO_REQ_OFF_WEEK', 'WED', 50, 'A', 'A', 'MUL', 150, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} Jeevansathi members want to see your son/daughters photo. Courier to D-13,Sector 2,Noida-201301. Write your phone number or profile ID on the photo.');
INSERT INTO `SMS_TYPE` VALUES (53, 'ADD_PHOTO', 'WED', 52, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, Jeevansathi members are rejecting yr "Express Interest messages" as yr profile does not have a photo. Email photo to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (54, 'PHOTO_UPLOAD', 'WED', 67, 'A', 'A', 'SINGLE', 7, 1, 'SERVICE', 'Y', 'Jeevansathi.com member {AGE}yrs, {MTONGUE}, {CASTE} -{PHOTO_UPLOADER_PROFILE} has put a photo. You had earlier requested {PHOTO_UPLOADER} to upload a photo.');
INSERT INTO `SMS_TYPE` VALUES (55, 'PHOTO_UPLOAD', 'WED', 67, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Jeevansathi profile-{PHOTO_UPLOADER_PROFILE} uploaded a photo in response to yr request. You should add photo to increase response. Send to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (56, 'PHOTO_UPLOAD', 'WED', 67, 'A', 'A', 'MUL', 7, 1, 'SERVICE', 'Y', '{PHTOUPNO} Jeevansathi members have put photos on their profiles. You had requested all these members for their photos. To see all click {PHOTO_REQUEST_SENT}');
INSERT INTO `SMS_TYPE` VALUES (57, 'PHOTO_UPLOAD', 'WED', 67, 'A', 'A', 'MUL', 7, 0, 'SERVICE', 'Y', '{PHTOUPNO} Jeevansathi members whose photos u wanted to see have put their photos. See all at {PHOTO_REQUEST_SENT} Ad yr own photo- email to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (58, 'PHONE_VERIFY', 'THU', 82, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', 'Jeevansathi.com requires you to verify your phone to get the "verified number" stamp. SMS from yr mobile {VERIFY_CODE} & send to {VALUEFRSTNO} to verify your phone nos');
INSERT INTO `SMS_TYPE` VALUES (59, 'PROFILE_COMPLETE', 'THU', 97, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', 'Your profile {USERNAME} on Jeevansathi.com is {PROF_PERCENT}% complete. If you add more information then 87% times more people will contact you.');
INSERT INTO `SMS_TYPE` VALUES (60, 'PHOTO_NCR', 'THU', 53, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, Jeevansathi members are not responding to your messages as yr profile does not have a photo. SMS "photo" to {PHOTOHELPNO} for home-pickup of photo.');
INSERT INTO `SMS_TYPE` VALUES (61, 'HOROSCOPE_UPLOAD', 'THU', 68, 'A', 'A', 'SINGLE', 7, 1, 'SERVICE', 'Y', 'Jeevansathi profile {HOROSCOPE_UPLOADER} - {HOROSCOPE_UPLOADER_PROFILE} has uploaded a horoscope in response to your request. Login to see yr Guna compatibility. Help-{TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (62, 'HOROSCOPE_UPLOAD', 'THU', 68, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Jeevansathi profile {HOROSCOPE_UPLOADER} -{HOROSCOPE_UPLOADER_PROFILE} uploaded a horoscope in response to your request. See Free Guna compatibility by updating yr birth details.');
INSERT INTO `SMS_TYPE` VALUES (63, 'PHOTO_REQ_OFF_WEEK', 'FRI', 50, 'A', 'A', 'MUL', 150, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} Jeevansathi members want to see yr profiles photo. Just write "Free Post -201951" on any envelope & post it. No Stamps to be put. Write yr Phone-No on photo');
INSERT INTO `SMS_TYPE` VALUES (64, 'ADD_PHOTO', 'FRI', 69, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Dear {USERNAME} your profile is not being viewed & contacted by other Jeevansathi members as it does not have a photo. Email photo to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (65, 'ADD_PHOTO_OFFLINE', 'SAT', 69, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Yr profile {USERNAME} is being ignored by  Jeevansathi members as it doesnt have a photo. Courier it to D-13,Sector 2,Noida-201301. Write your phone-No on photo');
INSERT INTO `SMS_TYPE` VALUES (66, 'EOI_WEEKLY', 'SAT', 43.5, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'Jeevansathi member {AGE}yrs, {HEIGHT}, {CASTE}, {EDU_LEVEL}, {INCOME}, {CITY_RES} has liked your profile. Click {URL_PROFILE} or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (67, 'EOI_WEEKLY', 'SAT', 43.5, 'A', 'A', 'MUL', 7, 0, 'SERVICE', 'Y', 'This week {EOI_COUNT} Jeevansathi members have liked your profile. To see all click {URL_EOI} Or call {TOLLNO}.');
INSERT INTO `SMS_TYPE` VALUES (68, 'ACCEPT_WEEKLY', 'SAT', 40, 'A', 'A', 'MUL', 7, 0, 'SERVICE', 'Y', 'This week {ACCEPT_COUNT} Jeevansathi members have liked yr profile/Accepted yr Expression of Interest. To see all click {URL_ACCEPT}');
INSERT INTO `SMS_TYPE` VALUES (69, 'ADD_PHOTO_OFFLINE', 'SUN', 69, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', '{USERNAME} on Jeevansathi is being ignored as no photo is there. Put photo in an envelope & write Free Post -201951 & post it. No Stamps to be put. Write Phone-No.');
INSERT INTO `SMS_TYPE` VALUES (70, 'PHOTO_REQ_WEEK', 'MON', 50, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} member on Jeevansathi.com has requested you for your photo. Login to Jeevansathi.com & upload photos or email with profile id to photos@jeevansathi.com');
INSERT INTO `SMS_TYPE` VALUES (73, 'ACCEPT', 'D', 38, 'P', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Jeevansathi member-{AGE}yrs,{HEIGHT},{INCOME},{CASTE},{EDU_LEVEL},{OCCUPATION} in {CITY_RES} has Accepted yr Expression of Interest. Click {URL_ACCEPT}');
INSERT INTO `SMS_TYPE` VALUES (71, 'PHOTO_REQ_OFF_WEEK', 'WED', 50, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} Jeevansathi member wants to see your son/daughters photo. Courier to D-13,Sector 2,Noida-201301. Write your phone number or profile ID on the photo.');
INSERT INTO `SMS_TYPE` VALUES (72, 'PHOTO_REQ_OFF_WEEK', 'FRI', 50, 'A', 'A', 'SINGLE', 150, 0, 'SERVICE', 'Y', '{PHOTO_REQUEST_COUNT} Jeevansathi member wants to see yr profiles photo. Just write "Free Post -201951" on any envelope & post it. No Stamps to be put. Write yr Phone-No on photo');
INSERT INTO `SMS_TYPE` VALUES (74, 'ACCEPT_WEEKLY', 'SAT', 40, 'A', 'A', 'SINGLE', 7, 0, 'SERVICE', 'Y', 'This week {ACCEPT_COUNT} Jeevansathi member has liked yr profile/Accepted yr Expression of Interest. To see all click {URL_ACCEPT}');

DROP TABLE IF EXISTS `TEMP_SMS_DETAIL`;
CREATE TABLE `TEMP_SMS_DETAIL` (
 `PROFILEID` int(12) NOT NULL,
 `SMS_TYPE` char(3) NOT NULL,
 `SMS_KEY` varchar(20) NOT NULL,
 `MESSAGE` text NOT NULL,
 `ADD_DATE` date DEFAULT NULL,
 `PHONE_MOB` varchar(12) NOT NULL,
 `PRIORITY` float NOT NULL,
 UNIQUE KEY `PROFILEID_2` (`PROFILEID`,`SMS_KEY`)
);

DROP TABLE IF EXISTS `SMS_TEMP_TABLE`;
CREATE TABLE `SMS_TEMP_TABLE` (
 `PROFILEID` mediumint(11) unsigned NOT NULL DEFAULT '0',
 `GENDER` char(1) NOT NULL DEFAULT '',
 `USERNAME` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
 `SUBSCRIPTION` varchar(10) NOT NULL DEFAULT '',
 `PHONE_MOB` varchar(100) NOT NULL DEFAULT '',
 `PASSWORD` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
 `CASTE` smallint(8) unsigned DEFAULT '0',
 `DTOFBIRTH` date NOT NULL DEFAULT '0000-00-00',
 `MSTATUS` char(2) NOT NULL,
 `MTONGUE` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `CITY_RES` varchar(4) NOT NULL DEFAULT '',
 `WEIGHT` tinyint(4) NOT NULL,
 `AGE` tinyint(4) NOT NULL DEFAULT '0',
 `HEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `EDU_LEVEL` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `INCOME` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `ENTRY_DT` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `MOB_STATUS` char(1) NOT NULL,
 `LAST_LOGIN_DT` date NOT NULL DEFAULT '0000-00-00',
 `HAVEPHOTO` char(1) NOT NULL DEFAULT '',
 `OCCUPATION` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `COUNTRY_RES` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `SERVICE_MESSAGES` char(1) NOT NULL DEFAULT '',
 `GET_SMS` char(1) NOT NULL DEFAULT '',
 `SOURCE` varchar(10) DEFAULT NULL,
 PRIMARY KEY (`PROFILEID`),
 UNIQUE KEY `USERNAME` (`USERNAME`),
 KEY `PHONE_MOB` (`PHONE_MOB`),
 KEY `LAST_LOGIN_DT` (`LAST_LOGIN_DT`),
 KEY `SOURCE` (`SOURCE`)
);

DROP TABLE IF EXISTS `SMS_CONTACTS`;
CREATE TABLE `SMS_CONTACTS` (
 `ID` int(3) NOT NULL AUTO_INCREMENT,
 `STATE` varchar(30) DEFAULT NULL,
 `BRANCH` varchar(50) DEFAULT NULL,
 `LOCALITY` varchar(100) DEFAULT NULL,
 `ADDRESS` varchar(200) DEFAULT NULL,
 `PINCODE` int(6) DEFAULT NULL,
 `AGENT_NAME` varchar(30) DEFAULT NULL,
 `SMS_NAME` varchar(30) DEFAULT NULL,
 `EMAIL` varchar(50) DEFAULT NULL,
 `SMS_MOBILE` varchar(50) DEFAULT NULL,
 `OUTBOUND_MOBILE` varchar(50) DEFAULT NULL,
 `LANDLINE_1` varchar(50) DEFAULT NULL,
 `LANDLINE_2` varchar(50) DEFAULT NULL,
 PRIMARY KEY (`ID`)
);

INSERT INTO `SMS_CONTACTS` ( `ID` , `STATE` , `BRANCH` , `LOCALITY` , `ADDRESS` , `PINCODE` , `AGENT_NAME` , `SMS_NAME` , `EMAIL` , `SMS_MOBILE` , `OUTBOUND_MOBILE` , `LANDLINE_1` , `LANDLINE_2` ) VALUES (1,"Bihar","Patna","Krishna Puri","House no.55C ,Rajesh kumar path, near Mourya TV office, Sri Krishna puri, Patna",800001,"Kishor","Kishor","patna@jeevansathi.com",9771490294,9835628966,"0612-2540253",""),(2,"Chattisgarh","Raipur","Raipur","F-Q,DM Plaza,Opp-Surana Bhawan,Rajiv Chowk,Chota Para (Nr Fire Station)",492001,"Chetan","Chetan","chetan.kumar@jeevansathi.com",9752091280,8889344844,"0771-4056092",""),(3,"Delhi","Delhi","Laxmi Nagar","D-100,Street No.5,Vikas Marg,Laxmi Nagar,Near Metro station",110092,"Mallika","Rahul","mallika.saxena@jeevansathi.com",9910009327,8447680006,"011-43051437",""),(4,"Delhi","Delhi","Nehru Place","GF-12A, 94, Meghdoot Buliding, Nehru Place",110007,"Emmi","Rahul","emmi.sharma@jeevansathi.com",9910006952,8860333040,"011-46546396",""),(5,"Delhi","Delhi","Kamla Nagar","36-A, Ground Flr, Kamla Nagar,Near Shakti Nagar Chowk",110007,"Isha","Rahul","isha.mehra@jeevansathi.com",9910007538,8860252554,"011-64617545",""),(6,"Delhi","Delhi","CP","GF-4, Indra Prakash Building,21 Barakhamba Road, CP",110001,"Mona","Rahul","mona.sahni@jeevansathi.com",9910006958,9999385956,"011-64728959",""),(7,"Delhi","Delhi","Laxmi Nagar","D-100,Street No.5,Vikas Marg,Laxmi Nagar,Near Metro station",110092,"Survit","Rahul","survit.chakravarty@jeevansathi.com",9910006834,9971188579,"011-43051439","011-43051438"),(8,"Delhi","Delhi","Malviya Nagar","D-88, Lower Basement,Near Shri Ram Sweets, Malviya Nagar",110017,"Anuradha","Rahul","anuradha.ghosh@jeevansathi.com",9910006538,9971109762,"011-405298725","011-40507325"),(9,"Delhi","Delhi","Malviya Nagar","D-88, Lower Basement,Near Shri Ram Sweets, Malviya Nagar",110017,"Shiv","Shiv","shiv.kumar@jeevansathi.com",9910006341,9899460063,"011-46076783",""),(10,"Delhi","Delhi","Nehru Place","GF-12A, 94, Meghdoot Buliding, Nehru Place",110019,"Ritu","Rahul","ritu.rani@jeevansathi.com",9910006935,8860101138,"011-46546396",""),(11,"Delhi","Delhi","Pitampura","801,ITL Twin Towers,B-9,NSP,Opp.Wazirpur District Centre,Pitampura",110034,"Parul","Rahul","parul.singh@jeevansathi.com",9910007594,9716040111,"011-42470689",""),(12,"Delhi","Delhi","Rajouri Garden","J-198, Shop No.-1 FF  Main Najafgarh Road, Rajouri Garden,Near Metro Pillar No.419",110027,"Neha","Rahul","neha.sahni@jeevansathi.com",9910006735,9999076827,"011-45541437",""),(13,"Delhi","Delhi","Rajouri Garden","J-198, Shop No.-1 FF  Main Najafgarh Road, Rajouri Garden,Near Metro Pillar No.419",110027,"shashank","shashank","shashank.ghanekar@jeevansathi.com",9910006826,9811011662,"","011-45541437"),(14,"Delhi","Delhi","Rajouri Garden","J-198, Shop No.-1 FF  Main Najafgarh Road, Rajouri Garden,Near Metro Pillar No.419",110027,"Sonia","Rahul","sonia.bahl@jeevansathi.com",9910006728,9958210343,"011-45541436",""),(15,"Delhi","Delhi","Kamla Nagar","36-A, Ground Flr, Kamla Nagar,Near Shakti Nagar Chowk",110007,"Abhishek","Abhishek","abhishek.s@jeevansathi.com",9910006927,9999851814,"011-45019513",""),(16,"Delhi NCR","Noida","Noida","B-77, Sector 5, Noida",201301,"Vandana","Rahul","vandana.patel@jeevansathi.com",9910006954,"","0120-4303192",""),(17,"Gujarat","Ahmedabad","CG Road","203 & 204, Shitiratna, Panchvati Circle, C. G. Road",380006,"Rashmi","Rahul","sevani.kumar@jeevansathi.com",8511170744,9825800086,"079-40233621",""),(18,"Gujarat","Baroda","Baroda","TF/302, SOHO Complex,Above Red Chillies,Malhar Point Cross Roads,Old Padra Road, Baroda-",390007,"Rimpy","Rahul","rimpy.suri@jeevansathi.com",8511170741,9714826268,"0265-3257824","0265-2311200"),(19,"Gujarat","Rajkot","Rajkot","205, Pramukh Swami Arcade, A wing, Malavia Chowk",360001,"Priyanka","Rahul","priyanka.vinda@jeevansathi.com",8511170742,9924975626,"0281-6640837",""),(20,"Gujarat","Surat","Surat","303, K.G.House, Above RBS Bank, Opp Citi Bank, Ghod Dod Road",395007,"Ronak","Ronak","ronak.sachdev@jeevansathi.com",8511170743,8000058191,"",""),(21,"Haryana","Gurgaon","Gurgaon","SF 55,1ST FLOOR,GALLERIA COMMERCIAL COMPLEX ,Gurgaon",122002,"Gaurav","Gaurav","gaurav.tripathi@jeevansathi.com","",9015597955,"0124-6464122",""),(22,"Jharkhand","Jamshedpur","Jamshedpur","Bharat Business Centre,Room-6,2nd Flr,Ram Mandir Area,Bistupur",831001,"Khushboo","Rahul","khushboo.singh@jeevansathi.com",9771490295,8092099929,"0657-6572351",""),(23,"Karnataka","Bangalore","Dickenson Road","N 203 Manipal centre (MG Road) North block Front wing",560042,"Afshan","Rahul","afshan.tabassum@jeevansathi.com",7259031516,9986506238,"080-40439009",""),(24,"Karnataka","Bangalore","Dickenson Road","N 203 Manipal centre (MG Road) North block Front wing",560042,"Pumpa","Rahul","hijli.pumpa@jeevansathi.com",7259031515,9164752087,"080-40439053",""),(25,"Karnataka","Bangalore","Koramangla","127, 1st Flr, Raheja Arcade, 5th Block, Koramangla Industrial Layout",560095,"Aneesa","Rahul","aneesa.banu@jeevansathi.com",7259031517,9986124585,"080-40927728",""),(26,"Madhya Pradesh","Bhopal","Bhopal","Harrison House, Ground Floor 2, 6 Malviya Nagar (NEAr Airtel office)",462003,"Pooja","Rahul","pooja.tiwari@jeevansathi.com",9752091287,8964004422,"0755-4202346",""),(27,"Madhya Pradesh","Indore","Indore","201, Royal Ratan Building, 7 MG Road",452001,"Varsha","Rahul","varsha.radhwani@jeevansathi.com",9752091284,9584288887,"0731-4010310",""),(28,"Madhya Pradesh","Jabalpur","NARBADA ROAD","KATANGA DUPLEX NO.2, BHUMIKA STATE, NARBADA ROAD,JABALPUR",482001,"Rishin","Rishin","jabalpur@jeevansathi.com",9752091283,"7415124111/222/333  ","0761-4042028",""),(29,"Madhya Pradesh","Gwalior","Lashkar","c/o Engineers Combine IFO Alankar Building Hospital Road Lashkar, Gwalior",474009,"Archna","Rahul","archana.gwalior@jeevansathi.com",9752091286,9806830300,"",""),(30,"Maharashtra","Aurangabad","Aurangabad","H.S. Kandi Center, 2nd Flr, Central Wing,Above IndusInd Bank,Jalna Road",431005,"Maya","Rahul","maya.paikrao@jeevansathi.com",7738399770,9765231555,"0240-6611788",""),(31,"Maharashtra","Mumbai","Andheri(West)","Bhavesha Bldg,No 2,Veera Desai Road,Opp Andheri Sports Complex,Andheri(W)",400058,"Jai","Jai","jai.chauhan@jeevansathi.com",7738399761,9833118829,"022-65157118",""),(32,"Maharashtra","Mumbai","Andheri(West)","Bhavesha Bldg,No 2,Veera Desai Road,Opp Andheri Sports Complex,Andheri(W)",400058,"Sudeepta","Rahul","sudeepta.mukherjee@jeevansathi.com",7738399759,9820837743,"022-65157118","022-65157119"),(33,"Maharashtra","Mumbai","Borivilli(W)","101, Kesar Kripa,Opp Raj Mahal Hotel, Chandravarkar Road, Borivili(W)",400092,"Anshu","Anshu","anshu.tiwari@jeevansathi.com",7738399754,8879106668,"022-65298503",""),(34,"Maharashtra","Mumbai","Borivilli(W)","101, Kesar Kripa,Opp Raj Mahal Hotel, Chandravarkar Road, Borivili(W)",400092,"Sheetal","Rahul","sheetal.shah@jeevansathi.com",7738399755,9920911103,"022-28936861",""),(35,"Maharashtra","Mumbai","Ghatkopar(E)","18A,Kailash Plaza,Near ICICI Bank,Vallabhbaug Lane,Garodia Nagar,Ghatkopar(E)",400077,"Gurmeet","Rahul","gurmeet.singh@jeevansathi.com",7738399757,9820225985,"022-65298505",""),(36,"Maharashtra","Mumbai","Ghatkopar(E)","18A,Kailash Plaza,Near ICICI Bank,Vallabhbaug Lane,Garodia Nagar,Ghatkopar(E)",400077,"Vijetha","Rahul","vijetha.poojary@jeevansathi.com",7738399756,9867758372,"022-65298505",""),(37,"Maharashtra","Mumbai","Mulund (West)","No 18-20,Maruti Arcade, J.N.Road,Opp Brijwasi Sweets, Mulund(West)",400080,"Komal","Rahul","komal.thange@jeevansathi.com",7738399752,7738393847,"022-65258442",""),(38,"Maharashtra","Mumbai","Mulund (West)","No 18-20,Maruti Arcade, J.N.Road,Opp Brijwasi Sweets, Mulund(West)",400080,"Vaibhavi","Rahul","Vaibhavi.nalavade@jeevansathi.com",7738399751,9833556619,"022-65258442",""),(39,"Maharashtra","Mumbai","Andheri(East)","216, Chintamani Plaza, Near Cine Magic, Andheri Kurla Road, Andheri(E)",400099,"Kanchan","Rahul","kanchan.waral@jeevansathi.com",7738399760,9324086055,"022-40071721",""),(40,"Maharashtra","Mumbai","Vashi","1401, Maithali Signet, Opp Vashi Station, Sector 30A, Vashi",400703,"Paramjeet","Rahul","Paramjeet.singh@jeevansathi.com",7738399758,9833628112,"",""),(41,"Maharashtra","Mumbai","Worli","203,Sumer Kendra,Pandurang Budukar Marg,Worli",400018,"Aparna","Rahul","aparna.karmakar@jeevansathi.com",7738399762,9320435992,"022-43511900",""),(42,"Maharashtra","Mumbai","Chembur","202-203,Swastik chambers,CST Road , Chembur(E)",400071,"Aparna","Rahul","aparna.karmakar@jeevansathi.com",7738399763,9320435992,"022-42555100",""),(43,"Maharashtra","Nagpur","Nagpur","F-9, Phase-II, Achraj Towers, Chindwara Road,Sadar",440013,"Neha","Rahul","neha.gupta@jeevansathi.com",7738399771,8888859585,"0712-3298713",""),(44,"Maharashtra","Nashik/ Nasik","Nashik","B-8, Kusum Pushpa Apartment,Opp. Dairy Don, College Road",422005,"Shreyas","Shreyas","shreyas.malve@jeevansathi.com",7738399772,9595097779,"0253-6574227","0253-6450112"),(45,"Maharashtra","Pune","Deccan","B-1,Basement,CIFCO Centre,JM Road,Near Shiv Sagar,Deccan",411043,"Preeti","Rahul","preeti.thapa@jeevansathi.com",7738399765,9823555182,"020-64001015",""),(46,"Maharashtra","Pune","Deccan","B-1,Basement,CIFCO Centre,JM Road,Near Shiv Sagar,Deccan",411043,"Gausiya","Rahul","gausiya.hannure@jeevansathi.com",7738399769,8007197786,"020-64000310",""),(47,"Maharashtra","Pune","Deccan","B-1,Basement,CIFCO Centre,JM Road,Near Shiv Sagar,Deccan",411043,"Kavita","Rahul","kavita.dhumal@jeevansathi.com",7738399767,8698950303,"020-64001016",""),(48,"Maharashtra","Pune","Deccan","B-1,Basement,CIFCO Centre,JM Road,Near Shiv Sagar,Deccan",411043,"Priyanka","Pranav","priyanka.unde@jeevansathi.com",7738399764,9923888976,"020-65008286",""),(49,"Maharashtra","Pune","Deccan","B-1,Basement,CIFCO Centre,JM Road,Near Shiv Sagar,Deccan",411043,"Manisha","Rahul","manisha.joshi@Infoedge.com",7738399766,9545877755,"020-64000304",""),(50,"Maharashtra","Pune","Koregaon Park","2nd Flr, Gera Sterling (Opp German Bakery),North Main Road, Koregaon Park",411001,"Karishma","Rahul","karishma.Shaikh@jeevansathi.com",7738399768,8007301786,"020-64000126","020-41407170"),(51,"Maharashtra","Thane","Thane (West)","1st Flr, No-7,Shreeji Arcade,No.325, Almedia Road, Panchpakhadi",400602,"Sulochana","Rahul","sulochana.gaikwad@jeevansathi.com",7738399753,7666409060,"",""),(52,"Orissa","Bhubaneshwar","Bhubaneshwar","D-5, 5th Flr, Metro House, Bani Vihar Square",751007,"Kumarika","Rahul","kumarika.b@jeevansathi.com","",8100003500,"0674-6450203",""),(53,"Punjab","Chandigarh","Chandigarh","SCO 14-15, First Floor, Sector-9D",160009,"Harnoor","Rahul","harnoor.bedi@jeevansathi.com",9915018427,9914143311,"0172-5062246",""),(54,"Punjab","Ludhiana","Ludhiana","Navrang Complex,Nr Feroz Gandhi mkt,Opp Nehru Sidhant Kendra,Pakhowal Road",141001,"Ripsy","Rahul","ripsy.arora@jeevansathi.com",9915018550,8968911119,"0161-5035568","0161-5074434"),(55,"Rajasthan","Jaipur","Jaipur","605, Crystal Mall, S.J.S. Highway, Bani Park",302016,"Vima","Rahul","vima.pandey@jeevansathi.com","",9928600024,"0141-4048910",""),(56,"Uttar Pradesh","Agra","Agra","1/133,Friends Shoppe Upr Ground Flr,Hariparvat Crossing(Opp Holiday Inn),MG Road",282004,"Kirty","Rahul","kirti.singh@jeevansathi.com",7388807733,9368451313,"0562-4051975",""),(57,"Uttar Pradesh","Kanpur","Kanpur","The Mall, 14/121-B, Main Road, Parade, Opp Raymonds",208001,"Archie","Rahul","archie.gupta@jeevansathi.com",8853220044,9807088989,"0512-6452028",""),(58,"Uttar Pradesh","Kanpur","Kanpur","The Mall, 14/121-B, Main Road, Parade, Opp Raymonds",208001,"Pravida","Rahul","pravida.srivastava@jeevansathi.com",8853220055,9918901137,"0512-6452028",""),(59,"Uttar Pradesh","Kanpur","Kanpur","The Mall, 14/121-B, Main Road, Parade, Opp Raymonds",208001,"Annie","Rahul","annie.gakhar@jeevansathi.com",8853220033,7388336699,"0512-6452028",""),(60,"Uttar Pradesh","Lucknow","Lucknow","31/107,Ground Flr, Sahu Building, Opp Universal Booksellers, Hazratganj",226001,"Bhawna","Rahul","bhawna.piplani@jeevansathi.com",7388805533,9919046111,"0522-4074927","0522-4074925"),(61,"Uttar Pradesh","Lucknow","Lucknow","31/107,Ground Flr, Sahu Building, Opp Universal Booksellers, Hazratganj",226001,"Vartika","Rahul","vartika.kesherwani@jeevansathi.com",7388804433,9919846460,"0522-4074929",""),(62,"Uttar Pradesh","Varanasi","Varanasi","C/25,Sri Ram Machinery Market,Opp.Rita Icecream Parlour, Varanasi",221002,"Rita","Rahul","varanasi@jeevansathi.com",7388806633,9721223355,"0542-3273355",""),(63,"West Bengal","Kolkata","Lake Town","336,Canal street,Gr. Floor, near Pizza hut,Lake town",700017,"Sriparna","Rahul","sriparna.bose@jeevansathi.com","",9007723210,"033-40030941",""),(64,"West Bengal","Kolkata","Gariahat","Identity Building No 5,1st Flr,38 Gariahat Road South(Near Selimpur Bus Stand)",700031,"Kumarika","Rahul","kumarika.b@jeevansathi.com","",8100003500,"033-40021760",""),(65,"West Bengal","Kolkata","AJC Bose Road","224 A J C Bose Road,KRISHNA BUILDING- 1st Floor,Module # 107 & 108,(Near Beckbagan Crossing)",700017,"Ishita","Rahul","ishita.biswas@jeevansathi.com","",9903980996,"033-40021765",""),(66,"Uttranchal","Dehradun","Dehradun","77/1, Dilaram Chowk, Rajpur Road",248001,"Rakhi","Rahul","rakhi.sharma@jeevansathi.com","",7500555576,"","");

UPDATE `SMS_TYPE` SET `TIME_CRITERIA` = '150' WHERE `ID` = '64';
UPDATE `SMS_TYPE` SET `MESSAGE` = 'Your profile {USERNAME} on Jeevansathi.com is {PROF_PERCENT}% complete. If you add more information then 87% more people will contact you.' WHERE `ID` = '59' LIMIT 1 ;

ALTER TABLE SMSCONFIRM ADD ENTRY_DT DATE;

UPDATE `SMS_TYPE` SET `STATUS` = 'N' WHERE `ID` = '51';

UPDATE `SMS_TYPE` SET `MESSAGE` = 'Jeevansathi member {AGE}yrs,{HEIGHT}, {MTONGUE}, {CASTE}, {EDU_LEVEL} in {CITY_RES} has liked your profile. Click {URL_PROFILE}' WHERE `ID` = '27';

UPDATE `SMS_TYPE` SET `MESSAGE` = 'Jeevansathi member {AGE}yrs,{HEIGHT}, {MTONGUE}, {EDU_LEVEL}, {INCOME}, {CITY_RES} has liked your profile. Click {URL_PROFILE}' WHERE `ID` = '28';

UPDATE `SMS_TYPE` SET `MESSAGE` = '{EOI_COUNT} Jeevansathi members liked yr profile incl-{AGE} yrs,{HEIGHT},{WEIGHT} - {URL_PROFILE}. To see all click {URL_EOI}' WHERE `ID` = '30';

UPDATE `SMS_TYPE` SET `MESSAGE` = '{EOI_COUNT} Jeevansathi members liked yr profile incl-{AGE} yrs,{HEIGHT},{INCOME} - {URL_PROFILE}. To see all click {URL_EOI}' WHERE `ID` = '31';

UPDATE `SMS_TYPE` SET `STATUS` = 'N' WHERE `ID` = '4';
UPDATE `SMS_TYPE` SET `TIME_CRITERIA` = '20' WHERE `ID` = '5';
ALTER TABLE `TEMP_SMS_DETAIL` ADD `SENT` VARCHAR( 1 ) NOT NULL ;
UPDATE `SMS_TYPE` SET `MESSAGE` = 'Mails to your current email id are bouncing back. To continue using the website update your email id on jeevansathi .com or click {EDIT_CONTACT_LAYER}.' WHERE `ID` = '42';
