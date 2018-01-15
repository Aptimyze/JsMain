-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Feb 19, 2013 at 10:49 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `jeevansathi_mailer`
-- new databases added

CREATE DATABASE jeevansathi_mailer;

-- --------------------------------------------------------

-- 
-- Table structure for table `EMAIL_COMMUNICATION`
-- 
USE jeevansathi_mailer;

DROP TABLE IF EXISTS `EMAIL_COMMUNICATION`;
CREATE TABLE `EMAIL_COMMUNICATION` (
  `EMAIL_ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `EMAIL_TYPE_ID` int(11) NOT NULL,
  `SENT_TO` int(11) NOT NULL,
  `SENT_DATE` date NOT NULL,
  `BOUNCED` char(1) NOT NULL,
  `SUBJECT_ID` smallint(6) NOT NULL,
  PRIMARY KEY (`EMAIL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store all the send emails data' AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `EMAIL_COMMUNICATION`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `EMAIL_MIS_DATA`
-- 

DROP TABLE IF EXISTS `EMAIL_MIS_DATA`;
CREATE TABLE `EMAIL_MIS_DATA` (
  `EMAIL_ID` bigint(20) NOT NULL,
  `LINK_NAME1` varchar(20) NOT NULL,
  `CLICKED_DATE_LINK_NAME` date NOT NULL,
  `LINKNAME2` varchar(20) NOT NULL,
  `CLICKED_DATE_LINK_NAME2` date NOT NULL,
  `OPEN_RATE` varchar(20) NOT NULL,
  `CLICK_THROUGH_RATE` varchar(20) NOT NULL,
  `UNSUBCRIBE_PAGE_VIEW` smallint(6) NOT NULL,
  `UNSUBSCRIBED` smallint(6) NOT NULL,
  `GMAIL_SPAM` smallint(6) NOT NULL,
  PRIMARY KEY (`EMAIL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `EMAIL_MIS_DATA`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `EMAIL_TYPE`
-- 

DROP TABLE IF EXISTS `EMAIL_TYPE`;
CREATE TABLE `EMAIL_TYPE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MAIL_ID` int(11) DEFAULT NULL,
  `TPL_LOCATION` varchar(500) DEFAULT NULL,
  `HEADER_TPL` varchar(100) DEFAULT NULL,
  `FOOTER_TPL` varchar(100) DEFAULT NULL,
  `TEMPLATE_EX_LOCATION` varchar(500) DEFAULT NULL,
  `MAIL_GROUP` int(3) DEFAULT NULL,
  `CUSTOM_CRITERIA` int(3) DEFAULT '1',
  `SENDER_EMAILID` varchar(100) DEFAULT NULL,
  `DESCRIPTION` text,
  `MEMBERSHIP_TYPE` varchar(50) DEFAULT 'D',
  `GENDER` char(1) DEFAULT NULL,
  `PHOTO_PROFILE` char(1) DEFAULT NULL,
  `REPLY_TO_ENABLED` char(1) DEFAULT NULL,
  `FROM_NAME` varchar(100) DEFAULT NULL,
  `REPLY_TO_ADDRESS` varchar(100) DEFAULT NULL,
  `MAX_COUNT_TO_BE_SENT` int(5) DEFAULT NULL,
  `REQUIRE_AUTOLOGIN` char(1) DEFAULT NULL,
  `FTO_FLAG` varchar(100) DEFAULT NULL,
  `PRE_HEADER` varchar(200) DEFAULT NULL,
  `PARTIALS` varchar(500) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `MAIL_GROUP` (`MAIL_GROUP`),
  KEY `MEMBERSHIP_TYPE` (`MEMBERSHIP_TYPE`),
  KEY `GENDER` (`GENDER`),
  KEY `PHOTO_PROFILE` (`PHOTO_PROFILE`),
  KEY `TPL_LOCATION` (`TPL_LOCATION`)
) ENGINE=MyISAM AUTO_INCREMENT=1765 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1765 ;

-- 
-- Dumping data for table `EMAIL_TYPE`
-- 

INSERT INTO `EMAIL_TYPE` VALUES (18, 18, 'testmailer.tpl', 'header.tpl', 'footer.tpl', 'asdfdsf', 3, 1, 'adfdsfadsfdgfads', NULL, 'P', 'F', 'Y', NULL, NULL, NULL, NULL, NULL, '', NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1749, 1749, 'd1_education_mailer.tpl', NULL, 'footer.tpl', NULL, 9, 1, 'info@jeevansathi.com', NULL, 'D', '', '', NULL, NULL, NULL, NULL, NULL, 'D1', 'Follow the simple steps and see phone/email of members you like.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1701, 1701, 'photo_upload1.tpl', 'reminder_header.tpl', 'footer.tpl', NULL, 1, 1, 'jaiswal@jeevansathi.com', 'Uploading photo test', '', '', '', NULL, 'Amit Jaiswal', 'webmaster@jeevansathi.com', 2, 'Y', '', '', 'suggested_profiles-suggested_profiles');
INSERT INTO `EMAIL_TYPE` VALUES (1703, 1703, 'c1_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C1', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1704, 1704, 'c1_reminder1_female.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, NULL, NULL, NULL, 'Y', 'C1', 'To get Free Trial Offer, upload photo & verify phone now!', '');
INSERT INTO `EMAIL_TYPE` VALUES (1705, 1705, 'c1_reminder1_male.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, NULL, NULL, NULL, 'Y', 'C1', 'To get Free Trial Offer, upload photo & verify phone now!', '');
INSERT INTO `EMAIL_TYPE` VALUES (1706, 1706, 'c1_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C1', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1707, 1707, 'c2_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C2', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1708, 1708, 'c2_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C2', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1709, 1709, 'c3_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C3', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1710, 1710, 'c3_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C3', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1712, 1712, 'c2_reminder1_male.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, NULL, NULL, NULL, 'Y', 'C2', 'See Phone/Email of members for FREE by taking the Trial pack ', '');
INSERT INTO `EMAIL_TYPE` VALUES (1713, 1713, 'c2_reminder1_female.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, NULL, NULL, NULL, 'Y', 'C2', 'See Phone/Email of members for FREE by taking the Trial pack ', '');
INSERT INTO `EMAIL_TYPE` VALUES (1714, 1714, 'c3_reminder1_male.tpl', '', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'M', '', NULL, NULL, NULL, NULL, 'Y', 'C3', 'See Phone/Email of members for FREE by taking the Trial pack ', '');
INSERT INTO `EMAIL_TYPE` VALUES (1715, 1715, 'c3_reminder1_female.tpl', '', 'footer1.tpl', NULL, 6, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, NULL, NULL, NULL, 'Y', 'C3', 'See Phone/Email of members for FREE by taking the Trial pack ', '');
INSERT INTO `EMAIL_TYPE` VALUES (1716, 1716, 'd1_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'D1,D2,D3,D4', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1717, 1717, 'd1_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', NULL, 4, 1, 'info@jeevansathi.com', NULL, 'D', 'F', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'D1,D2,D3,D4', 'Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE', '');
INSERT INTO `EMAIL_TYPE` VALUES (1718, 1718, 'c2_photoreminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 7, 1, 'info@jeevansathi.com', 'upload photo reminder 2', 'D', '', '', NULL, NULL, NULL, NULL, NULL, 'C2', 'To get Free Trial Offer, upload photo now!', '');
INSERT INTO `EMAIL_TYPE` VALUES (1720, 1720, 'c3_reminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 7, 1, 'info@jeevansathi.com', 'verify phone reminder 2', 'D', '', '', NULL, NULL, NULL, NULL, NULL, 'C3', 'To get Free Trial Offer verify phone now!', '');
INSERT INTO `EMAIL_TYPE` VALUES (1740, 1740, 'd_photo_uploaded_fto_active.tpl', NULL, '', NULL, 10, 1, 'info@jeevansathi.com', 'Photo uploaded and screened and now FTO Active\r\n', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'D1,D2,D3,D4', 'See phone/email of members you like for FREE by EXPRESSING INTEREST in members. Refer to T&Cs for details', '');
INSERT INTO `EMAIL_TYPE` VALUES (1723, 1723, 'c1_reminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', NULL, 7, 1, 'info@jeevansathi.com', 'upload photo and verify phone reminder', 'D', '', '', NULL, NULL, NULL, NULL, NULL, 'C1', 'To get Free Trial Offer, upload photo & verify phone now!', '');
INSERT INTO `EMAIL_TYPE` VALUES (1725, 1725, 'c3_photo_done_next_verify_phone.tpl', '', '', NULL, 10, 1, 'info@jeevansathi.com', 'photo screened now verify phone', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'C3', 'See phone/email of members you like for FREE by taking Trial Offer', '');
INSERT INTO `EMAIL_TYPE` VALUES (1739, 1739, 'e_expiry.tpl', 'expiry_header.tpl', 'expiry_footer.tpl', 'unkown', 8, 1, 'info@jeevansathi.com', 'Free trial offer period has expired.', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'E1,E2', 'Continue to Search, Express Interest, Accept other members. All are  free services of Jeevansathi.com', '');
INSERT INTO `EMAIL_TYPE` VALUES (1741, 1741, 'photo_uploaded_mailer.tpl', NULL, NULL, NULL, 10, 1, 'info@jeevansathi.com', 'photo screened mailer for states E1,E2,E3,E4,E5,F,G', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'E1,E2,E3,E4,E5,F,G', NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1742, 1742, 'acceptance.tpl', NULL, NULL, NULL, 2, 1, 'contacts@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1743, 1743, 'photo_rejection_mailer.tpl', NULL, NULL, NULL, 10, 1, 'info@jeevansathi.com', 'Mailer sent when all the photos uploaded are rejected by the screening team', 'D', NULL, NULL, NULL, 'Jeevansathi.com', NULL, NULL, 'Y', NULL, NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1744, 1744, 'photo_uploaded_max_mailer.tpl', NULL, NULL, NULL, 10, 1, 'info@jeevansathi.com', 'Mailer sent when more than 20 photos are sent and only 20 photos are uploaded', 'D', NULL, NULL, NULL, 'Jeevansathi.com', NULL, NULL, 'Y', NULL, NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1746, 1746, 'photo_request_with_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo Request Mailer when requesting profile is having a photo for fto state C1', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'C1', 'Get FREE TRIAL OFFER by uploading photo and verifing phone.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1747, 1747, 'photo_request_no_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo request mailer when requesting profile has no photo for state C1\r\n', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'C1', 'Get FREE TRIAL OFFER by uploading photo and verifing phone.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1748, 1748, 'decline.tpl', NULL, NULL, NULL, 2, 1, 'contacts@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1750, 1750, 'photo_request_with_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo Request Mailer when requesting profile is having a photo for fto state C2', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'C2', 'Get FREE TRIAL OFFER and see phone/email of members you like.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1751, 1751, 'photo_request_with_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo Request Mailer when requesting profile is having a photo for fto state E,F,G and Paid', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'E1,E2,E3,E4,E5,F,G', 'Picture on your profile increases response', '');
INSERT INTO `EMAIL_TYPE` VALUES (1752, 1752, 'photo_request_no_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo request mailer when requesting profile has no photo for state C2', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'C2', 'Get FREE TRIAL OFFER and see phone/email of members you like.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1753, 1753, 'photo_request_no_photo.tpl', NULL, NULL, NULL, 11, 1, 'info@jeevansathi.com', 'Photo request mailer when requesting profile has no photo fto state E,F,G and Paid', 'D', NULL, NULL, 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'E1,E2,E3,E4,E5,F,G', 'Picture on your profile increases response', '');
INSERT INTO `EMAIL_TYPE` VALUES (1754, 1754, 'eoi_mailer.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'unkown', 5, 1, 'contacts@jeevansathi.com', 'EOI Mailer Instant when receiver profile entry date is within 30 days.', 'D', NULL, NULL, NULL, 'Jeevansathi.com', NULL, NULL, 'Y', NULL, 'Don''t wait, accept if you like the profile', '');
INSERT INTO `EMAIL_TYPE` VALUES (1755, 1755, 'c2PhoneVerified.tpl', NULL, NULL, NULL, 12, 1, 'info@jeevansathi.com', 'when phone number is verified', 'D', '', '', 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', NULL, 'Y', 'C2', 'See phone/email of members you like for FREE by taking Trial Offer', '');
INSERT INTO `EMAIL_TYPE` VALUES (1756, 1756, 'eoi_mailer.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'unknown', 5, 1, 'contacts@jeevansathi.com', 'Reminder mailer.', 'D', NULL, NULL, NULL, 'Jeevansathi.com', NULL, NULL, 'Y', NULL, 'Don''t wait, accept if you like the profile', '');
INSERT INTO `EMAIL_TYPE` VALUES (1757, 1757, 'dPhoneVerified.tpl', NULL, NULL, NULL, 12, 1, 'info@jeevansathi.com', 'when phone number is verified', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'D1,D2,D3,D4', 'See phone/email of members you like for FREE by EXPRESSING INTEREST in members. Refer to T&Cs for details', '');
INSERT INTO `EMAIL_TYPE` VALUES (1758, 1758, 'cancelled.tpl', NULL, NULL, NULL, 2, 1, 'contacts@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1759, 1759, 'message_mailer.tpl', NULL, NULL, NULL, 13, 1, 'contacts@jeevansathi.com', 'Mail on Receiving a message', 'D', '', '', NULL, NULL, NULL, NULL, NULL, '', 'View the message sent by this member', '');
INSERT INTO `EMAIL_TYPE` VALUES (1760, 1760, 'incomplete_lifestyle_mailer.tpl', 'incomplete_lifestyle_header.tpl', 'incomplete_lifestyle_footer.tpl', 'unknown', 3, 1, 'info@jeevansathi.com', 'Mailer for complete your lifestyle', 'D', NULL, NULL, NULL, NULL, NULL, NULL, 'Y', NULL, 'Add details of your school, college, workplace, lifestyle etc.', '');
INSERT INTO `EMAIL_TYPE` VALUES (1761, 1761, 'callDirectlyMailer.tpl', NULL, NULL, NULL, 14, 1, 'info@jeevansathi.com', NULL, 'D', '', '', NULL, NULL, NULL, NULL, 'Y', '', NULL, '');
INSERT INTO `EMAIL_TYPE` VALUES (1762, 1762, 'incomplete_mailer.tpl', NULL, 'footer1.tpl', NULL, 3, 1, 'info@jeevansathi.com', 'This mailer will be fired to incomplete profiles.', 'D', '', '', '', 'Jeevansathi.com', '', 0, 'Y', 'IU,I', 'Complete your profile & take the FREE TRIAL OFFER worth Rs.1100/-', '');
INSERT INTO `EMAIL_TYPE` VALUES (1763, 1763, 'incomplete_horoscope.tpl', NULL, 'footer1.tpl', NULL, 3, 1, 'info@jeevansathi.com', 'Mailer for profiles who are in D state and have no horoscope uploaded', 'D', '', '', 'Y', 'Jeevansathi.com', 'horoscope@jeevansathi.com', NULL, 'Y', '', 'Horoscopes are important in match-making for many people', '');
INSERT INTO `EMAIL_TYPE` VALUES (1764, 1764, 'visitor_alert.tpl', NULL, NULL, NULL, 15, 1, 'visitoralert@jeevansathi.com', 'visitor alert', 'D', NULL, NULL, NULL, 'Jeevansathi.com', NULL, NULL, 'Y', NULL, NULL, '');

-- --------------------------------------------------------
-- 
-- Table structure for table `LINK_MAILERS`
-- 

DROP TABLE IF EXISTS `LINK_MAILERS`;
CREATE TABLE `LINK_MAILERS` (
  `LINKID` int(11) NOT NULL AUTO_INCREMENT,
  `LINK_NAME` varchar(100) DEFAULT NULL,
  `LINK_URL` varchar(100) DEFAULT NULL,
  `OTHER_GET_PARAMS` varchar(100) DEFAULT NULL,
  `REQUIRED_AUTOLOGIN` varchar(100) DEFAULT NULL,
  `OUTER_LINK` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`LINKID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- 
-- Dumping data for table `LINK_MAILERS`
-- 

INSERT INTO `LINK_MAILERS` VALUES (1, 'PHOTO_ALBUM', 'profile/albumpage', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (2, 'DETAILED_PROFILE_HOME', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (3, 'ALLCENTRESLOCATIONS', 'profile/contact.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (4, 'MEMBERSHIP_COMPARISON', 'profile/mem_comparison.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (5, 'shortlist', 'profile/contacts_made_received.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (6, 'similarProfiles', 'profile/simprofile_search.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (9, 'clickOnPhoto', 'profile/viewprofile.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (10, 'ACCEPT', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=accept', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (11, 'DECLINE', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=decline&search_decline=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (13, 'Phone_Email_View Contact Details', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (14, 'HOME_PAGE', 'P/mainmenu.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (15, 'UNSUBSCRIBE', 'profile/unsubscribe.php', 'matchalertTrack=1&logic_used=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (16, 'JS_FB_PAGE', 'http://www.facebook.com/jeevansathi', NULL, 'N', 'Y');
INSERT INTO `LINK_MAILERS` VALUES (17, 'UPLOAD_PHOTO', 'social/addPhotos', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (18, 'VERIFY_PHONE', 'P/mainmenu.php', 'verify_link_from_mailer=yes', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (19, 'OFFER_PAGE_URL', 'fto/offer', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (20, 'SUGGESTED_MATCHES', 'search/partnermatches', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (21, 'PHOTO_EMAILID', 'photos@jeevansathi.com', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (22, 'CHANGE_NUMBER', 'P/viewprofile.php', 'ownview=1&EditWhatNew=ContactDetails', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (23, 'EXPRESS_INTEREST', 'profile/viewprofile.php', 'kundli_type=3', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (24, 'CC_PEOPLE_WHO_ACCEPTED_ME_URL', 'profile/contacts_made_received.php', 'page=accept&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (25, 'PHOTO_REQUEST_PAGE', 'profile/contacts_made_received.php', 'page=photo&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (26, 'COMPLETE_PROFILE_RANDOM', 'P/viewprofile.php', 'ownview=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (27, 'UPLOAD_HOROSCOPE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=AstroData', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (28, 'COMPLETE_PROFILE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=incompletProfile', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (29, 'COMPLETE_PROFILE_LIFESTYLE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=LifeStyle', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (30, 'PROFILE_VISITORS', 'profile/contacts_made_received.php', 'page=visitors&filter=R', 'Y', 'N');

-- --------------------------------------------------------

-- 
-- Table structure for table `MAILER_ID_TYPE`
-- 

DROP TABLE IF EXISTS `MAILER_ID_TYPE`;
CREATE TABLE `MAILER_ID_TYPE` (
  `MAIL_ID` smallint(6) NOT NULL,
  `MAIL_GROUP` smallint(6) NOT NULL,
  `CUSTOM_CRITERIA` smallint(6) NOT NULL,
  `GENDER` set('M','F','D') DEFAULT 'D',
  `MEMBERSHIP_TYPE` set('U','P','D') DEFAULT 'D',
  `STATE_FLAG` set('C1','C2','C3','D1','D2','D3','D4','E1','E2','E3','D') DEFAULT 'D',
  PRIMARY KEY (`MAIL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `MAILER_ID_TYPE`
-- 

INSERT INTO `MAILER_ID_TYPE` VALUES (1, 1, 1, 'M', 'P,D', 'E1');
INSERT INTO `MAILER_ID_TYPE` VALUES (2, 1, 1, 'M,F', 'P', 'C1,C2,C3');

-- --------------------------------------------------------

-- 
-- Table structure for table `MAILER_SUBJECT`
-- 

DROP TABLE IF EXISTS `MAILER_SUBJECT`;
CREATE TABLE `MAILER_SUBJECT` (
  `MAIL_ID` int(3) unsigned DEFAULT NULL,
  `SUBJECT_TYPE` varchar(10) DEFAULT NULL,
  `SUBJECT_CODE` varchar(200) DEFAULT NULL,
  `DESCRIPTION` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='created for subject lines to be used in Mailers';

-- 
-- Dumping data for table `MAILER_SUBJECT`
-- 

INSERT INTO `MAILER_SUBJECT` VALUES (1703, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1706, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1707, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1708, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1710, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1716, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1717, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1718, 'D', 'Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-', 'upload photo reminder 2');
INSERT INTO `MAILER_SUBJECT` VALUES (1720, 'D', 'Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-', 'verify phone reminder 2');
INSERT INTO `MAILER_SUBJECT` VALUES (1723, 'D', 'Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-', 'upload photo and verify phone reminder 2');
INSERT INTO `MAILER_SUBJECT` VALUES (1704, 'D', 'See phone/email of members you like', 'Verify Photo and Phone');
INSERT INTO `MAILER_SUBJECT` VALUES (1705, 'D', 'See phone/email of members you like', 'Verify Photo and Phone');
INSERT INTO `MAILER_SUBJECT` VALUES (1749, 'D', 'How to see phone/email of members? Details inside.', NULL);
INSERT INTO `MAILER_SUBJECT` VALUES (1712, 'D', 'Upload Photo to get Trial Pack of Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift', 'Upload Photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1713, 'D', 'Upload Photo to get Trial Pack of Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift', 'Upload Photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1714, 'D', 'Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift. ', 'Verify Phone');
INSERT INTO `MAILER_SUBJECT` VALUES (1715, 'D', 'Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift. ', 'Verify Phone');
INSERT INTO `MAILER_SUBJECT` VALUES (1709, 'D', 'Welcome to Jeevansathi.com. See details of Trial Pack inside', 'welcome mailer default subject line');
INSERT INTO `MAILER_SUBJECT` VALUES (1739, 'D', 'Trial pack ends today, but there''s lot more!', 'When FTO pack has expired for FTO user.');
INSERT INTO `MAILER_SUBJECT` VALUES (1725, 'D', 'Photo uploaded successfully. Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>', 'Photo uploaded now verify phone for state C3');
INSERT INTO `MAILER_SUBJECT` VALUES (1740, 'D', 'Photo uploaded successfully. Your Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var> is Activated', 'Photo uploaded and screened now fto active');
INSERT INTO `MAILER_SUBJECT` VALUES (1741, 'D', 'Your photos are live on Jeevansathi.com', 'When photos are uploaded except for C3 and D states');
INSERT INTO `MAILER_SUBJECT` VALUES (1743, 'D', 'Photos rejected on Jeevansathi.com', 'Subject for the mail when none photo is approved');
INSERT INTO `MAILER_SUBJECT` VALUES (1744, 'D', 'Your photos are live on Jeevansathi.com', 'When more than 20 photos uploaded but max of only 20 photos get uploaded');
INSERT INTO `MAILER_SUBJECT` VALUES (1746, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers');
INSERT INTO `MAILER_SUBJECT` VALUES (1747, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers');
INSERT INTO `MAILER_SUBJECT` VALUES (1750, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers');
INSERT INTO `MAILER_SUBJECT` VALUES (1751, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers');
INSERT INTO `MAILER_SUBJECT` VALUES (1752, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers ');
INSERT INTO `MAILER_SUBJECT` VALUES (1753, 'D', 'Member <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo', 'Subject for photo request mailers ');
INSERT INTO `MAILER_SUBJECT` VALUES (1754, 'D', 'Congratulations! <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has expressed interest in you.', 'For EOI Mailer');
INSERT INTO `MAILER_SUBJECT` VALUES (1756, 'D', 'Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you a remidner.', 'Reminder subject');
INSERT INTO `MAILER_SUBJECT` VALUES (1742, 'D', 'Congrats, <var>{{USERNAME:profileid=~$otherProfile`}}</var> has Accepted your interest. See <var>{{HIS_HER:profileid=~$profileid`}}</var> phone/email', 'Acceptance Mailer');
INSERT INTO `MAILER_SUBJECT` VALUES (1748, 'P', '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has responded to your interest ', 'Decline with photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1748, 'N', '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Declined your interest. Add a photo to make your profile better ', 'Decline with out photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1758, 'P', '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. See suggested matches ', 'Cancel mailer with photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1758, 'N', '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Add a photo to make your profile better ', 'Cancel mailer without photo');
INSERT INTO `MAILER_SUBJECT` VALUES (1755, 'D', 'Your Phone is Verified. Upload Photo and get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-', NULL);
INSERT INTO `MAILER_SUBJECT` VALUES (1757, 'D', 'Phone verified successfully. Your Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var> is Activated', NULL);
INSERT INTO `MAILER_SUBJECT` VALUES (1759, 'D', 'Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you a personal message', 'Subject for mail on receiving a message');
INSERT INTO `MAILER_SUBJECT` VALUES (1761, 'D', 'A Jeevansathi member has shown interest in you by viewing your contact details', 'call directly subject line ');
INSERT INTO `MAILER_SUBJECT` VALUES (1760, 'D', 'Enrich your profile, get more response on Jeevansathi!', 'incomplete lifestyle ');
INSERT INTO `MAILER_SUBJECT` VALUES (1701, 'D', 'Photo upload Test ~$profileid` and something ~$profileid`', 'Test subject for photo upload ~$profileid` and something ~$profileid`');
INSERT INTO `MAILER_SUBJECT` VALUES (1762, 'D', 'See Phone/Email of members. Just take Trial Pack. No payment required', 'For incomplete mailer');
INSERT INTO `MAILER_SUBJECT` VALUES (1763, 'D', 'One small step can get you 7 times more Response!', 'For incomplete horoscope mailer');
INSERT INTO `MAILER_SUBJECT` VALUES (1764, 'D', 'Member <var>{{USERNAME:profileid=~$profileid1`}}</var> may be interested in you as <var>{{HE_SHE:profileid=~$profileid`}}</var> saw your profile', 'visitor alert subject');

-- --------------------------------------------------------

-- 
-- Table structure for table `MAILER_TEMPLATE_VARIABLES_MAP`
-- 

DROP TABLE IF EXISTS `MAILER_TEMPLATE_VARIABLES_MAP`;
CREATE TABLE `MAILER_TEMPLATE_VARIABLES_MAP` (
  `VARIABLE_NAME` varchar(100) NOT NULL,
  `VARIABLE_PROCESSING_CLASS` mediumint(9) NOT NULL DEFAULT '0',
  `DESCRIPTION` varchar(300) DEFAULT 'NA',
  `MAX_LENGTH` mediumint(9) DEFAULT '0',
  `MAX_LENGTH_SMS` mediumint(9) DEFAULT '0',
  `DEFAULT_VALUE` varchar(50) DEFAULT 'NA',
  `TPL_FORMAT` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `MAILER_TEMPLATE_VARIABLES_MAP`
-- 

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PROFILEID', 2, 'Profile ID', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('USERNAME', 2, 'Username or Profile ID', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PASSWORD', 2, 'Password', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('DTOFBIRTH', 2, 'Date of Birth', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MSTATUS', 2, 'Marital Status', 13, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('GENDER', 2, 'Gender', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MTONGUE', 2, 'Mother Tongue', 12, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('AGE', 2, 'Age', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('HEIGHT', 2, 'Height', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CASTE', 2, 'Caste', 12, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('EDUCATION', 2, 'Education', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('OCCUPATION', 2, 'Occupation', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CITY', 2, 'City', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('getDISCOUNT', 1, 'ATS Discount %', 2, 0, '10', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('getDISCOUNTDATE', 1, 'ATS Discount Valid Till Date', 13, 0, '10', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('TOLLNO', 1, 'Toll Free Number', 11, 0, '1800-419-6299', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('getVALUEFRSTNO', 1, 'Number of Value-First where SMS/EMAIL has to be sent', 13, 0, '0122345678900', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('NOIDALANDL', 1, 'Landline Number of Noida Inbound', 12, 0, '0120-4393500', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('NOT FOUND', 0, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('RELIGION', 2, 'Religion', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('WEIGHT', 2, 'Weight', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('INCOME', 2, 'Income', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('RES_STATUS', 2, 'Residential Status', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('GOING_ABROAD', 2, 'Interested in Going abroad', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAMILY_INCOME', 2, 'Family Income', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COMPANY_NAME', 2, 'Company Name', 25, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('OWN_HOUSE', 2, 'Whether owns a house', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('HAVE_CAR', 2, 'Owns a car', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAVOURITE_MOVIE', 2, 'Favourite Movie', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAVOURITE_BOOK', 2, 'Favourite Book', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAVOURITE_TV_SHOW', 2, 'Favourite TV show', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COLLEGE_NAME', 2, 'College Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PGCOLLEGE_NAME', 2, 'PG College Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('SCHOOL_NAME', 2, 'School Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('SIBLINGS_INFO', 2, 'Number of Brothers and Sisters', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('HOME_PAGE', 3, 'URL of home page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PHOTO_ALBUM', 3, 'URL of Photo Album', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('DETAILED_PROFILE_HOME', 3, 'URL of Detailed Profile', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('THUMB_PROFILE', 4, 'Thumbnail Profile Photo', 1, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PROFILE_PIC', 4, 'Url of Photo of profile', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('ALLCENTRESLOCATIONS', 3, 'All center location url', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('UNSUBSCRIBE', 3, 'Unsubscribe link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('NAME_PROFILE', 2, 'Name of the user of profile', 20, 6, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('UPLOAD_PHOTO', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('VERIFY_PHONE', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('OFFER_PAGE_URL', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PHOTO_EMAILID', 1, 'NA', 0, 0, 'photos@jeevansathi.com', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('SUGGESTED_MATCHES', 3, 'suggested matches page link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('JS_FB_PAGE', 3, 'facbook url', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CONTACT_NUMBER', 2, 'if mobile  number then mobile number otherwise landline number', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CHANGE_NUMBER', 3, 'change number', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_WORTH', 5, 'Price of FTO Offer', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('AGENT_NAME', 6, 'Nearest Jeevnasathi Agent Name', 100, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('AGENT_ADDRESS', 6, 'Nearest Jeevnasathi Agent Address', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('AGENT_CONTACT', 6, 'Nearest Jeevnasathi Agent Contact Number', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MOBILE_NUMBER', 2, 'Mobile number of profile', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('INVALID_PHONE', 2, 'Y if User phone is invalid', 1, 0, 'N', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_START_DAY', 5, 'fto start day', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_START_DAY_SUFFIX', 5, 'fto start daysuffix', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_START_YEAR', 5, 'fto start year', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_START_MONTH', 5, 'fto start month', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_DAY', 5, 'fto end day', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_DAY_SUFFIX', 5, 'fto end daysuffix', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_YEAR', 5, 'fto end year', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_MONTH', 5, 'fto end month', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_DAY_SINGLE_DOUBLE_DIGIT', 5, 'fto end day single digit dates', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_START_DAY_SINGLE_DOUBLE_DIGIT', 5, 'fto start day single digit dates', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MEMBERSHIP_COMPARISON', 3, 'Membership comparison page', 255, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FTO_END_MONTH_UPPERCASE', 5, 'fto end month in upper case', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('RELIGION_CASTE_OR_SECT_LABEL', 2, 'Labels for template [Religion & Caste] or [Religion & Sect ]', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CITY_WITH_COUNTRY', 2, 'City name followed by comma followed by country name', 50, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('RELIGION_CASTE_VALUE_TEMPLATE', 2, 'Religion, Small Caste format or simply Full Caste', 50, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('EXPRESS_INTEREST', 3, 'Url for expression interest', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MAX_NO_OF_PHOTOS', 4, 'Maximum number of photos uploaded', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PHOTO_FORMATS', 4, 'Photo formats allowed', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MAX_PHOTO_SIZE', 4, 'Maximum Size of Photo Allowed', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CC_PEOPLE_WHO_ACCEPTED_ME_URL', 3, 'People who accepted me link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PHOTO_REQUEST_PAGE', 3, 'Url for photo request page at contact center', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('ACCEPT', 3, 'Accept EOI received link', 255, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('DECLINE', 3, 'Decline EOI received link', 255, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MTONGUE_SMALL', 2, 'Mapped Mother tongue if it legnth is greater 16 else Mtonuge', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('OCCUPATION_SMALL', 2, 'truncated occupation if it legnth is greater 16', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('CITY_SMALL', 2, 'truncated city if it legnth is greater 16', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('GOTHRA', 2, 'user gotra', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('NAKSHATRA', 2, '10', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('YOURINFO', 2, 'about me section', 100, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAMILYINFO', 2, 'family info', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAMILY_VALUES', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MOTHER_OCCUPATION', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAMILY_TYPE', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('TBROTHER', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MBROTHER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FAMILY_STATUS', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('TSISTER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MSISTER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('TSISTER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('LIVING_WITH_PARENTS', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COMPLETE_PROFILE', 3, 'Complete profile link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('UPLOAD_HOROSCOPE', 3, 'upload horoscope link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('FATHER', 2, 'father name', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('EDUCATION_DETAIL', 2, 'education detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('JOB_INFO', 2, 'occupation detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COMPLETE_PROFILE', 3, 'Variable for complete profile link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('EDUCATION_DETAIL', 2, 'education detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('JOB_INFO', 2, 'occupation detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('ALBUM_LINK', 4, 'It gives html for link of album page for a profile on condtional basis. If no photo present then gives Request photo link else photo album link with a button', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COMPLETE_PROFILE_LIFESTYLE', 3, 'Edit Layer for lifestyle', 100, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PROFILE_VISITORS', 3, 'profile visitors link', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('HE_SHE', 2, 'Pronoun of opposite gender of a profile', 10, 10, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('COMPLETE_PROFILE_RANDOM', 3, 'Randomly open an edit layer as defined in FTO logic except upload horoscope link', 1000, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('HIS_HER', 2, 'as per profile gender ', 10, 0, 'NA', '');

-- --------------------------------------------------------
        
