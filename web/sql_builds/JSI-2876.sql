use jeevansathi_mailer;
CREATE TABLE `EMAIL_TYPE_NEW` (
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
  KEY `TPL_LOCATION` (`TPL_LOCATION`),
  KEY `MAIL_ID` (`MAIL_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1  ;

-- 
-- Dumping data for table `EMAIL_TYPE`
-- 

INSERT INTO `EMAIL_TYPE_NEW` VALUES (18, 18, 'testmailer.tpl', 'header.tpl', 'footer.tpl', 'asdfdsf', 3, 1, 'adfdsfadsfdgfads', 'NULL', 'P', 'F', 'Y', 'N', 'NULL', 'NULL', 0, 'N', '', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1749, 1749, 'd1_education_mailer.tpl', 'NULL', 'footer.tpl', 'NULL', 9, 1, 'info@jeevansathi.com', 'NULL', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', 'D1', '"Follow the simple steps and see phone/email of members you like."', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1701, 1701, 'photo_upload1.tpl', 'reminder_header.tpl', 'footer.tpl', 'NULL', 1, 1, 'jaiswal@jeevansathi.com', '"Uploading photo test"', '', '', '', 'N', '"Amit Jaiswal"', 'webmaster@jeevansathi.com', 2, 'Y', '', '', 'suggested_profiles-suggested_profiles');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1703, 1703, 'c1_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C1', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1704, 1704, 'c1_reminder1_female.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C1', '"To get Free Trial Offer', ' upload photo & verify phone now!"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1705, 1705, 'c1_reminder1_male.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C1', '"To get Free Trial Offer', ' upload photo & verify phone now!"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1706, 1706, 'c1_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C1', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1707, 1707, 'c2_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C2', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1708, 1708, 'c2_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C2', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1709, 1709, 'c3_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C3', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1710, 1710, 'c3_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C3', '"Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- worth membership FREE"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1712, 1712, 'c2_reminder1_male.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C2', '"See Phone/Email of members for FREE by taking the Trial pack "', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1713, 1713, 'c2_reminder1_female.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C2', '"See Phone/Email of members for FREE by taking the Trial pack "', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1714, 1714, 'c3_reminder1_male.tpl', '', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'M', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C3', '"See Phone/Email of members for FREE by taking the Trial pack "', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1715, 1715, 'c3_reminder1_female.tpl', '', 'footer1.tpl', 'NULL', 6, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'NULL', 'NULL', 0, 'Y', 'C3', '"See Phone/Email of members for FREE by taking the Trial pack "', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1716, 1716, 'd1_welcome_mailer_male.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"D1', 'D2', 'D3');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1717, 1717, 'd1_welcome_mailer_female.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'F', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"D1', 'D2', 'D3');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1718, 1718, 'c2_photoreminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 7, 1, 'info@jeevansathi.com', '"upload photo reminder 2"', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', 'C2', '"To get Free Trial Offer', ' upload photo now!"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1720, 1720, 'c3_reminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 7, 1, 'info@jeevansathi.com', '"verify phone reminder 2"', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', 'C3', '"To get Free Trial Offer verify phone now!"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1740, 1740, 'd_photo_uploaded_fto_active.tpl', 'NULL', '', 'NULL', 10, 1, 'info@jeevansathi.com', '"Photo uploaded and screened and now FTO Active', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1750, 0, '', '', 'NULL', 'Jeevansathi.com', 0, 0, 'Y', '"D1', 'D2', 'D', 'D', '"', '', NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1723, 1723, 'c1_reminder2.tpl', 'reminder_header.tpl', 'footer1.tpl', 'NULL', 7, 1, 'info@jeevansathi.com', '"upload photo and verify phone reminder"', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', 'C1', '"To get Free Trial Offer', ' upload photo & verify phone now!"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1725, 1725, 'c3_photo_done_next_verify_phone.tpl', '', '', 'NULL', 10, 1, 'info@jeevansathi.com', '"photo screened now verify phone"', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'C3', '"See phone/email of members you like for FREE by taking Trial Offer"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1739, 1739, 'e_expiry.tpl', 'expiry_header.tpl', 'expiry_footer.tpl', 'unkown', 8, 1, 'info@jeevansathi.com', '"Free trial offer period has expired."', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"E1', 'E2"', '"Continue to Search');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1741, 1741, 'photo_uploaded_mailer.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 10, 1, 'info@jeevansathi.com', '"photo screened mailer for states E1', 'E2', 'E', 'E', 'E', 'F', 'G"', 0, '', '', 'NULL', '"Jeevansathi Info"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1742, 1742, 'acceptance.tpl', 'NULL', 'NULL', 'NULL', 2, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1743, 1743, 'photo_rejection_mailer.tpl', 'NULL', 'NULL', 'NULL', 10, 1, 'info@jeevansathi.com', '"Mailer sent when all the photos uploaded are rejected by the screening team"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1744, 1744, 'photo_uploaded_max_mailer.tpl', 'NULL', 'NULL', 'NULL', 10, 1, 'info@jeevansathi.com', '"Mailer sent when more than 20 photos are sent and only 20 photos are uploaded"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1746, 1746, 'photo_request_mailer.tpl', 'NULL', 'NULL', 'NULL', 11, 1, 'contacts@jeevansathi.com', '"Photo Request Mailer when requesting profile is having a photo for fto state C1"', 'D', 'N', 'N', 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', 0, 'Y', 'C1', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1747, 1747, 'photo_request_mailer.tpl', 'NULL', 'NULL', 'NULL', 11, 1, 'contacts@jeevansathi.com', '"Photo request mailer when requesting profile has no photo for state C1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1751, 0, 'NULL', 'NULL', 'Y', 'Jeevansathi.com', 0, 0, 'Y', 'C1', '"Get FREE TRIAL OFFER by uploading photo and verif', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1748, 1748, 'decline.tpl', 'NULL', 'NULL', 'NULL', 2, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1752, 1752, 'photo_request_mailer.tpl', 'NULL', 'NULL', 'NULL', 11, 1, 'contacts@jeevansathi.com', '"Photo request mailer when requesting profile has no photo for state C2"', 'D', 'N', 'N', 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', 0, 'Y', 'C2', '"Get FREE TRIAL OFFER and see phone/email of members you like."', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1753, 1753, 'photo_request_mailer.tpl', 'NULL', 'NULL', 'NULL', 11, 1, 'contacts@jeevansathi.com', '"Photo request mailer when requesting profile has no photo fto state E', 'F', 'G', 'D', 'N', 'NULL', 'Y', 0, 'p', 'NULL', 'Y', '"E1');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1754, 1754, 'eoi_mailer.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'unkown', 5, 1, 'contacts@jeevansathi.com', '"EOI Mailer Instant when receiver profile entry date is within 30 days."', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'Y', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1755, 1755, 'c2PhoneVerified.tpl', 'NULL', 'NULL', 'NULL', 12, 1, 'info@jeevansathi.com', '"when phone number is verified"', 'D', '', '', 'Y', 'Jeevansathi.com', 'photos@jeevansathi.com', 0, 'Y', 'C2', '"See phone/email of members you like for FREE by taking Trial Offer"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1756, 1756, 'eoi_mailer.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'unknown', 5, 1, 'contacts@jeevansathi.com', '"Reminder mailer."', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'Y', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1757, 1757, 'dPhoneVerified.tpl', 'NULL', 'NULL', 'NULL', 12, 1, 'info@jeevansathi.com', '"when phone number is verified"', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"D1', 'D2', 'D3');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1758, 1758, 'cancelled.tpl', 'NULL', 'NULL', 'NULL', 2, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1759, 1759, 'message_mailer.tpl', 'header.tpl', 'footer.tpl', 'NULL', 13, 1, 'contacts@jeevansathi.com', '"Mail on Receiving a message"', 'D', '', '', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', '', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1760, 1760, 'incomplete_lifestyle_mailer.tpl', 'incomplete_lifestyle_header.tpl', 'incomplete_lifestyle_footer.tpl', 'unknown', 3, 1, 'info@jeevansathi.com', '"Mailer for complete your lifestyle"', 'D', 'N', 'N', 'N', 'NULL', 'NULL', 0, 'Y', 'NULL', '"Add details of your school', ' college');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1761, 1761, 'callDirectlyMailer.tpl', 'NULL', 'NULL', 'NULL', 14, 1, 'visitoralert@jeevansathi.com', 'NULL', 'D', '', '', 'N', '"Jeevansathi Alerts"', 'NULL', 0, 'Y', '', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1762, 1762, 'incomplete_mailer.tpl', 'NULL', 'footer1.tpl', 'NULL', 3, 1, 'info@jeevansathi.com', '"This mailer will be fired to incomplete profiles."', 'D', '', '', '', 'Jeevansathi.com', '', 0, 'Y', '"IU', 'I"', '"Complete your profile & take the FREE TRIAL OFFER worth Rs.1100/-"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1763, 1763, 'incomplete_horoscope.tpl', 'NULL', 'footer1.tpl', 'NULL', 3, 1, 'info@jeevansathi.com', '"Mailer for profiles who are in D state and have no horoscope uploaded"', 'D', '', '', 'Y', 'Jeevansathi.com', 'horoscope@jeevansathi.com', 0, 'Y', '', '"Horoscopes are important in match-making for many people"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1764, 1764, 'visitor_alert.tpl', 'NULL', 'NULL', 'NULL', 15, 1, 'visitoralert@jeevansathi.com', '"visitor alert"', 'D', 'N', 'N', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1765, 1765, 'UnderScreeningMailOthers.tpl', 'UnderScreeningMailOthersHeader.tpl', 'UnderScreeningMailOthersFooter.tpl', 'unknown', 16, 1, 'info@jeevansathi.com', '"The new email is to be sent only if the time of profile submitting registration page 2 i.e. the time when it moves to screening queue is before 8am on that day OR is after 7pm on that day."', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"C1', 'C2', 'C3');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1766, 1766, 'UnderScreeningMailEFG.tpl', 'UnderScreeningMailEFGHeader.tpl', 'UnderScreeningMailEFGFooter.tpl', 'unknown', 16, 1, 'info@jeevansathi.com', '"The new email is to be sent only if the time of profile submitting registration page 2 i.e. the time when it moves to screening queue is before 8am on that day OR is after 7pm on that day."', 'D', '', '', 'N', 'Jeevansathi.com', 'NULL', 0, 'Y', '"C1', 'C2', 'C3');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1767, 1767, 'eoi_mailer.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'NULL', 5, 1, 'contacts@jeevansathi.com', '"EOI Mailer "', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', 'NULL', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1768, 1768, 'success_mailer_photo.tpl', 'NULL', 'NULL', 'NULL', 17, 1, 'customerservice@jeevansathi.com', '"Photo Upload mailer after submitting success story"', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', '', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1769, 1769, 'success_mailer_delete.tpl', 'NULL', 'NULL', 'NULL', 18, 1, 'customerservice@jeevansathi.com', '"Deletion of  Profile mailer after uploading success story"', 'D', '', '', 'N', 'NULL', 'NULL', 0, 'N', '', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1774, 1774, 'incomplete_mailer_no_fto.tpl', 'NULL', 'footer1.tpl', 'NULL', 3, 1, 'info@jeevansathi.com', '"This mailer will be fired to incomplete profiles."', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', '', 0, 'Y', '"IU', 'I"', '"Complete your profile & contact profiles you like for free"');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1771, 1771, 'registration_mailer_Page1.tpl', 'NULL', 'NULL', 'NULL', 21, 1, 'info@jeevansathi.com', 'NULL', 'D', '', '', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', '', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1773, 1773, 'under_screening.tpl', 'NULL', 'NULL', 'NULL', 20, 1, 'register@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1775, 1775, 'phoneVerification.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 22, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1776, 1776, 'under_screening_KYC.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 25, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1778, 1778, 'password_mail.htm', 'NULL', 'revamp_footer.tpl', 'NULL', 23, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1779, 1779, 'revamp_screening.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 16, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1780, 1780, 'revamp_welcome.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1782, 1782, 'top8.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"top 8 mailer to be sent after screening"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1783, 1783, 'automatedResponse.tpl', 'NULL', 'NULL', 'NULL', 28, 1, 'info@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1784, 1784, 'membership_mailer_exclusive.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Mailers to free and paid members informing them about the Exclusive plan"', 'D', 'N', 'N', 'N', '"Jeevansathi Exclusive"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1785, 1785, 'membership_mailer_7.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Paid membership mailers on 7th day after registration."', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1786, 1786, 'membership_mailer_21.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Paid membership mailers on 21st day after registration."', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1788, 1788, 'fraudAlert.tpl', 'top8_fraud_header.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"fraud alert mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1790, 1790, 'apCommentsMailer.tpl', 'top8_fraud_header.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'contacts@jeevansathi.com', '"mailer for comments added in AP Intro Calls."', 'D', 'N', 'N', 'N', '"Jeevansathi Alerts"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1791, 1791, 'duplicateProfileMailer.tpl', 'duplicate_profiles_header.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"mailer for those profiles which have been marked duplicate"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1792, 1792, 'membership_expiry.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 30, 1, 'membership@jeevansathi.com', '"Membership Expiry Mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1793, 1793, 'membership_expiry_10.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 30, 1, 'membership@jeevansathi.com', '"Membership Expiry Mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1795, 1795, 'membership_mailer_promotion_FP_RB.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Paid Membership Promotion for FP', ' RB on 10 & 20 day after payment"', 'D', 'N', 'N', 'NULL', '"Jeevansathi Membership"', 0, 'N', 'NULL', 'NULL', 'NULL');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1794, 1794, 'improveProfileScore.tpl', 'improveScoreHeader.tpl', 'revamp_footer.tpl', 'NULL', 31, 1, 'info@jeevansathi.com', '"send an Email asking the user to fill key details about his/her profile. The Email should be fired 1 day', ' 7 days', '', '', 'D', 'NULL', 'NULL', 0, '"', 'NULL', 'NULL', 'NULL');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1797, 1797, 'membership_exclusive_serviceFeedback.tpl', 'NULL', 'NULL', 'NULL', 30, 1, 'membership@jeevansathi.com', '"Jeevansathi Exclusive Service Feedback"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1798, 1798, 'fieldSalesInfo.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'info@jeevansathi.com', '"New profile allocation"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1799, 1796, 'eoi_mailer_yn.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'NULL', 5, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'Y', 'NULL', '"Please add contacts@jeevansathi.com> to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1800, 1800, '1month_discount_mailer.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'membership@jeevansathi.com', '"Jeevansathi 1 month paid membership offer plan"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1801, 1801, 'fraudAlertFemale.tpl', 'top8_fraud_header.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"fraud alert mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1803, 1802, 'eoi_mailer_filter.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'NULL', 5, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'Y', 'NULL', '"Please add contacts@jeevansathi.com> to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1804, 1804, 'variableDiscountMailer.tpl', 'NULL', 'NULL', 'NULL', 30, 1, 'membership@jeevansathi.com', '"Jeevansathi VD Mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1805, 1803, 'we_talk_for_you_usage_description.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'membership@jeevansathi.com', '"Jeevansathi We Talk For You pack usage description"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1806, 1806, 'salesCampaignFeedbackMailer.tpl', 'NULL', 'NULL', 'NULL', 32, 1, 'info@jeevansathi.com', '"Jeevansathi Sales Service Feedback"', 'D', 'N', 'N', 'N', '"Jeevansathi Feedback"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1807, 1803, 'we_talk_for_you_usage_description.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'membership@jeevansathi.com', '"Jeevansathi We Talk For You pack usage description"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1809, 1807, 'RB_Activation_mailer.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'membership@jeevansathi.com', '"Jeevansathi RB Activation"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1811, 1808, 'Exclusive_Assignment_mailer.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'membership@jeevansathi.com', '"Jeevansathi Exclusive Service"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1823, 1823, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 38, 1, 'info@jeevansathi.com', '"inactive mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1821, 1820, 'Field_Visit_Request_Mailer.tpl', 'NULL', 'NULL', 'NULL', 25, 1, 'info@jeevansathi.com', '"Jeevansathi Field Visit Request Submission"', 'D', 'N', 'N', 'N', 'Jeevansathi.com', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1822, 1822, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 37, 1, 'info@jeevansathi.com', '"inactive mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1824, 1824, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 39, 1, 'info@jeevansathi.com', '"inactive mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1825, 1825, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 40, 1, 'info@jeevansathi.com', '"inactive mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1826, 1826, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 41, 1, 'info@jeevansathi.com', '"inactive mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1827, 1827, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 34, 1, 'info@jeevansathi.com', '"incomplete mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1828, 1828, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 35, 1, 'info@jeevansathi.com', '"incomplete mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1829, 1829, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', 'NULL', 36, 1, 'info@jeevansathi.com', '"incomplete mailer"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1831, 1821, 'dpp_Review_Mailer.tpl', 'top8_dpp_Review.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"mailer to review one\\''s dpp"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1833, 1833, 'horoscope_request_mailer.tpl', 'NULL', 'NULL', 'NULL', 11, 1, 'contacts@jeevansathi.com', '"Horoscope Request Mailer"', 'D', 'N', 'N', '', 'Jeevansathi.com', '', 0, 'Y', 'C1', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1835, 1831, 'shortlistedMailer.tpl', 'NULL', 'NULL', 'NULL', 42, 1, 'contacts@jeevansathi.com', '"Mail for sending interests to recently shortlisted members. "', 'D', '', '', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', '', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1837, 1835, 'newMembershipPaymentWelcomeMail.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Mailer for new membership payments made on Jeevansathi.com"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1834, 1834, 'emailVerificationMailer.tpl', 'header.tpl', 'footer.tpl', 'NULL', 4, 1, 'info@jeevansathi.com', 'NULL', 'D', '', '', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', '', '', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1839, 1836, 'memExpiryForContactViewed.tpl', 'NULL', 'NULL', 'NULL', 29, 1, 'membership@jeevansathi.com', '"Contact details viewed by you in your last membership"', 'D', 'N', 'N', 'N', '"Jeevansathi Membership"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1841, 1837, 'exclusiveServiceIIMailer.tpl', 'NULL', 'NULL', 'NULL', 29, 1, '~$SENDER_EMAIL`', '"Jeevansathi Exclusive Service II"', 'D', 'N', 'N', 'N', '~$SENDER_NAME`', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1838, 1838, 'phoneUnverify.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 43, 1, 'info@jeevansathi.com', '"top 8 mailer to be sent after screening"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1843, 1839, 'astroCompatibiltyMailer.tpl', 'top8_dpp_Review.tpl', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"astro compatibility mail"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1840, 1840, 'reminderMailer.tpl', 'NULL', 'NULL', 'NULL', 44, 1, 'contacts@jeevansathi.com', '"Mail for sending interests to recently pending interests. "', 'D', '', '', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'N', '', '"Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1845, 1841, 'incompleteJunkRemoval.tpl', 'NULL', 'NULL', 'NULL', 45, 1, 'info@jeevansathi.com', '"incomplete mailer after removal of the junk characters."', 'D', 'N', 'N', 'N', 'Jeevansathi.com', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1847, 1842, 'deleteProfileMail.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 27, 1, '"info@jeevansathi.com  "', '"top 8 mailer to be sent after screening    "', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1849, 1843, 'eoi_mailer_ei.tpl', 'eoi_header.tpl', 'eoi_footer.tpl', 'NULL', 5, 1, 'contacts@jeevansathi.com', 'NULL', 'D', 'N', 'N', 'N', '"Jeevansathi Contacts"', 'NULL', 0, 'Y', 'NULL', '"Please add contacts@jeevansathi.com> to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1851, 1845, 'contactQuota.tpl', 'NULL', 'revamp_footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"Contact Quota increment mailer to be sent after increasing contact quota n report invalid."', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', '"Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox"', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1844, 1844, 'promotionalAlternateEmail.tpl', 'NULL', 'NULL', 'NULL', 46, 1, 'info@jeevansathi.com', '"Promotional Alternate Email"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'N', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1854, 1846, 'requestDeletionForUser.tpl', 'header.tpl', 'footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', '"Mailer requesting User to delete its profile."', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', 'NULL', 0, 'Y', 'NULL', 'NULL', '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1855, 1847, 'autoReminderOnPhotoUpload.tpl', NULL, NULL, NULL, 5, 1, 'contacts@jeevansathi.com', 'auto reminder mailer after photo uploads of the sender of interest', 'D', NULL, NULL, NULL, 'Jeevansathi Contacts', NULL, NULL, NULL, NULL, NULL, '');
INSERT INTO `EMAIL_TYPE_NEW` VALUES (1856, 1848, 'sampleAstroCompatibilityMailer.tpl', 'top8_dpp_Review.tpl', 'revamp_footer.tpl', NULL, 27, 1, 'info@jeevansathi.com', '"Sample Astro Compatibility Mail"', 'D', 'N', 'N', 'N', '"Jeevansathi Info"', NULL, NULL, NULL, NULL, NULL, '');



CREATE TABLE `MAILER_SUBJECT_NEW` (
  `MAIL_ID` int(3) unsigned DEFAULT NULL,
  `SUBJECT_TYPE` varchar(10) DEFAULT NULL,
  `SUBJECT_CODE` varchar(200) DEFAULT NULL,
  `DESCRIPTION` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='created for subject lines to be used in Mailers';

-- 
-- Dumping data for table `MAILER_SUBJECT_NEW`
-- 

INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1703, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1706, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1707, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1708, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1710, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1716, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1717, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1718, 'D', '"Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-"', '"upload photo reminder 2"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1720, 'D', '"Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-"', '"verify phone reminder 2"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1723, 'D', '"Last Day to get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-"', '"upload photo and verify phone reminder 2"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1704, 'D', '"See phone/email of members you like"', '"Verify Photo and Phone"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1705, 'D', '"See phone/email of members you like"', '"Verify Photo and Phone"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1749, 'D', '"How to see phone/email of members? Details inside."', 'NULL');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1712, 'D', '"Upload Photo to get Trial Pack of Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift"', '"Upload Photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1713, 'D', '"Upload Photo to get Trial Pack of Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift"', '"Upload Photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1714, 'D', '"Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift. "', '"Verify Phone"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1715, 'D', '"Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- as a gift. "', '"Verify Phone"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1709, 'D', '"Welcome to Jeevansathi.com. See details of Trial Pack inside"', '"welcome mailer default subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1739, 'D', '"Trial pack ends today', ' but there\\''s lot more!"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1725, 'D', '"Photo uploaded successfully. Verify Phone to get Trial Pack of Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>"', '"Photo uploaded now verify phone for state C3"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1740, 'D', '"Photo uploaded successfully. Your Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var> is Activated"', '"Photo uploaded and screened now fto active"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1741, 'D', '"Your photos are live on Jeevansathi.com"', '"When photos are uploaded except for C3 and D states"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1743, 'D', '"We could not make your photo live because of the mentioned reason"', '"Subject for the mail when none photo is approved"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1744, 'D', '"Your photos are live on Jeevansathi.com"', '"When more than 20 photos uploaded but max of only 20 photos get uploaded"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1746, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1747, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1750, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1751, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1752, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers "');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1753, 'D', '"<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> wants to see your picture. Upload Photo"', '"Subject for photo request mailers "');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1754, 'D', '"<var>{{USERNAME:profileid=~$otherProfileId`}}</var> has Expressed Interest in you"', '"For EOI Mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1756, 'D', '"Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you a reminder."', '"Reminder subject"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1742, 'D', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Accepted your interest"', '"Acceptance Mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1790, 'D', '"Update regarding your request to call ~$pog_id` as a part of your intro call service."', '"AP Intro calls comments"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1748, 'P', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var>  declined your interest"', '"Decline with photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1748, 'N', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var>  declined your interest"', '"Decline with out photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1758, 'P', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you."', '"Cancel mailer with photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1758, 'N', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Add a photo to make your profile better "', '"Cancel mailer without photo"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1755, 'D', '"Your Phone is Verified. Upload Photo and get Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-"', 'NULL');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1757, 'D', '"Phone verified successfully. Your Trial Pack worth Rs <var>{{FTO_WORTH:profileid=~$profileid`}}</var> is Activated"', 'NULL');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1759, 'D', '"Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you personal messages"', '"Subject for mail on receiving a message"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1761, 'D', '"A Jeevansathi member has shown interest in you by viewing your contact details"', '"call directly subject line "');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1760, 'D', '"Enrich your profile', ' get more response on Jeevansathi!"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1701, 'D', '"Photo upload Test ~$profileid` and something ~$profileid`"', '"Test subject for photo upload ~$profileid` and something ~$profileid`"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1762, 'D', '"See Phone/Email of members. Just take Trial Pack. No payment required"', '"For incomplete mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1763, 'D', '"One small step can get you 7 times more Response!"', '"For incomplete horoscope mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1764, 'D', '"Member <var>{{USERNAME:profileid=~$profileid1`}}</var> may be interested in you as <var>{{HE_SHE:profileid=~$profileid`}}</var> saw your profile"', '"visitor alert subject"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1765, 'D', '"Welcome to Jeevansathi"', '"Underscreening mailer subject"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1766, 'D', '"Welcome to Jeevansathi"', '"Underscreening mailer subject"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1767, 'D', '" ~$count` Members Expressed interest in you"', '"Multiple EOI"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1768, 'D', '"Your success story needs a photo!"', '"Photo request mailer for success story"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1769, 'D', '"Congratulations from Jeevansathi !"', '"profile delete mailer after uploading success story"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1774, 'D', '"Complete your profile & contact profiles you like for free"', 'NULL');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1771, 'D', '"Thank you for registering with jeevansathi.com"', '"registration page1 mailer subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1773, 'D', '"Welcome to Jeevansathi.com"', '"Screening mailer  subject line Page2"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1775, 'D', '"Verify your number immediately to activate your profile"', 'NULL');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1776, 'D', '"Your Relationship Executive will meet you soon"', '"screening mailer kyc "');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1778, 'D', '"Your Jeevansathi.com Password information"', '"Your Jeevansathi.com Password information"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1779, 'D', '"Welcome to Jeevansathi.com"', '"Screening mailer revamp subject line Page2"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1780, 'D', '"Your Profile is active now!"', '"Revamp Welcome mailer subject line."');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1782, 'D', '"Using Jeevansathi.com is really simple. Let us show you how"', '"top 8 mailer to be sent after welcome screening"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1784, 'D', '"Introducing JS EXCLUSIVE | Your Partner Search made more personalized"', '"Mailers to free and paid members informing them about the Exclusive plan"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1783, 'D', '"The changes made by you in your profile are live now"', '"edit screening profiles mail subject line"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1785, 'D', '"More Value. Less price. Contact the Profiles you like"', '"Paid membership mailers on 7th day after registration"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1786, 'D', '"Contact the Profiles you like. Know our Membership plans"', '"Paid membership mailers on 21st day after registration"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1788, 'D', '"Important Information for you"', '"Fraud Alert"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1791, 'D', '"Your profile has been marked duplicate and will not appear in search results"', '"Duplicate profiles mail"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1792, 'D', '"Your membership expires today"', '"Membership expiry mail"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1793, 'D', '"Your membership expires in ten days"', '"Membership expiry mail"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1795, 'D', '"Get more from your Jeevansathi paid membership!"', '"Get more from your Jeevansathi paid membership!"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1794, 'D', '"Get 5 times more responses. Add important details about yourself and your family.."', '"Improve Profile Score"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1797, 'D', '"Jeevansathi Exclusive Service Feedback"', '"Jeevansathi Exclusive Service Feedback"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1798, 'D', '"New profile allocation"', '"Field Sales User Info"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1796, 'D', '"Respond to members who are waiting for your response. "', '"yes no mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1800, 'D', '"Special offer: Try Jeevansathi 1 month paid membership"', '"Jeevansathi 1 month paid membership offer plan"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1801, 'D', '"Search Safe: 6 tips to have a secure experience on Jeevansathi"', '"Fraud Alert for Females"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1802, 'D', '"Did you see the interests received in your filtered folder?"', '"eoi mailer filter"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1804, 'D', '"Congratulations! You are selected for a special discount by Jeevansathi.com"', '"Jeevansathi VD Mailer"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1803, 'D', '"Details about your ""We Talk for You"" service"', '"Jeevansathi We Talk For You usage description"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1806, 'D', '"Feedback request from Jeevansathi.com"', '"Jeevansathi Sales Service Feedback"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1803, 'D', '"Details about your ""We Talk for You"" service"', '"Jeevansathi We Talk For You usage description"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1807, 'D', '" Details about your Response Booster service"', '"Jeevansathi RB Activation"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1808, 'D', '"Welcome to Jeevansathi Exclusive Membership!"', '"Jeevansathi Exclusive Service"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1819, 'D', '"We will soon make your profile inactive', ' login to continue to receive recommendations"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1820, 'D', '"Request for visit has been submitted"', '"Jeevansathi Field Visit Request Submission"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1822, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1822, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1823, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1824, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1825, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1826, 'D', '"Login at least once a week to continue to receive relevant interests', NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1827, 'D', '"We will soon make your profile inactive', ' login to continue to receive recommendations');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1828, 'D', '"We will soon make your profile inactive', ' login to continue to receive recommendations');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1829, 'D', '"We will soon make your profile inactive', ' login to continue to receive recommendations');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'Inactive_S', NULL, NULL);
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1821, 'D', '"Hi ~$username` ', ' Please review your desired partner profile"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (0, 'D', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> wants to see your horoscope. Create horoscope"', '"Subject for horoscope request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1833, 'D', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> wants to see your horoscope. Create horoscope"', '"Subject for horoscope request mailers"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1835, 'D', '~$membershipName`', '"Mailer for new membership payments made on Jeevansathi.com"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1836, 'D', '"Contact details viewed by you in your last membership"', '"Membership Expiry mail for Viewed Contacts"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1837, 'D', '"I have shortlisted some profiles for you', ' please go through them | ~$CURR_MAIL_DATE`"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1839, 'D', '"Astro compatibility report with member <var>{{USERNAME:profileid=~$otherProfile`}}</var> "', '"astro compatibility report"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1841, 'D', '"Your profile on Jeevansathi.com has been marked incomplete"', '"Email is sent when profile is marked incomplete on junk characters removal."');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1842, 'D', '"Profile deleted because of terms of use violation"', '"Deleting a profile"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1758, 'A', '"<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Please make your photo visible"', '"Cancel mailer with photo visible on accept"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1844, 'D', '"Because', ' We decide as a family !"');
INSERT INTO `MAILER_SUBJECT_NEW` VALUES (1848, 'D', '"Sample astro compatibility report"', '"sample astro compatibility report"');


CREATE TABLE `MAILER_TEMPLATE_VARIABLES_MAP_NEW` (
  `VARIABLE_NAME` varchar(100) NOT NULL,
  `VARIABLE_PROCESSING_CLASS` mediumint(9) NOT NULL DEFAULT '0',
  `DESCRIPTION` varchar(300) DEFAULT 'NA',
  `MAX_LENGTH` mediumint(9) DEFAULT '0',
  `MAX_LENGTH_SMS` mediumint(9) DEFAULT '0',
  `DEFAULT_VALUE` varchar(50) DEFAULT 'NA',
  `TPL_FORMAT` varchar(2000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `MAILER_TEMPLATE_VARIABLES_MAP_NEW`
-- 

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ABOUTPROFILE', 2, 'About Profile', 100, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ABOUT_ME', 3, 'about your info layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ACCEPT', 3, 'Accept EOI received link', 255, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('AGE', 2, 'Age', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('AGENT_ADDRESS', 6, 'Nearest Jeevnasathi Agent Address', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('AGENT_CONTACT', 6, 'Nearest Jeevnasathi Agent Contact Number', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('AGENT_NAME', 6, 'Nearest Jeevnasathi Agent Name', 100, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ALBUM_LINK', 4, 'It gives html for link of album page for a profile on condtional basis. If no photo present then gives Request photo link else photo album link with a button', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ALLCENTRESLOCATIONS', 3, 'All center location url', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ALLCENTRESLOCATIONS_N', 3, 'All center location url with no autologin', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CASTE', 2, 'Caste', 12, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CC_PEOPLE_WHO_ACCEPTED_ME_URL', 3, 'People who accepted me link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CHANGE_NUMBER', 3, 'change number', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CITY', 2, 'City', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CITY_SMALL', 2, 'truncated city if it legnth is greater 16', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CITY_WITH_COUNTRY', 2, 'City name followed by comma followed by country name', 50, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('COLLEGE_NAME', 2, 'College Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('COMPANY_NAME', 2, 'Company Name', 25, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('COMPLETE_PROFILE', 3, 'Variable for complete profile link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('COMPLETE_PROFILE_LIFESTYLE', 3, 'Edit Layer for lifestyle', 100, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('COMPLETE_PROFILE_RANDOM', 3, 'Randomly open an edit layer as defined in FTO logic except upload horoscope link', 1000, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CONTACT_NUMBER', 2, 'if mobile  number then mobile number otherwise landline number', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('DECLINE', 3, 'Decline EOI received link', 255, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('DETAILED_PROFILE_HOME', 3, 'URL of Detailed Profile', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('DTOFBIRTH', 2, 'Date of Birth', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EDUCATION', 2, 'Education', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EDUCATION_DETAIL', 2, 'education detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EDU_OCC', 3, 'about your info layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EMAIL', 2, 'email ID of the user', 0, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EOI_FILTER', 3, 'People waiting response filter link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EOI_RECEIVIED', 3, 'People awaiting response link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EXPRESS_INTEREST', 3, 'Url for expression interest', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAMILYINFO', 2, 'family info', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAMILY_INCOME', 2, 'Family Income', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAMILY_STATUS', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAMILY_TYPE', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAMILY_VALUES', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAQS_LAYER', 3, 'deleting the profile', 100, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FATHER', 2, 'father name', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAVOURITE_BOOK', 2, 'Favourite Book', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAVOURITE_MOVIE', 2, 'Favourite Movie', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FAVOURITE_TV_SHOW', 2, 'Favourite TV show', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_DAY', 5, 'fto end day', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_DAY_SINGLE_DOUBLE_DIGIT', 5, 'fto end day single digit dates', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_DAY_SUFFIX', 5, 'fto end daysuffix', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_MONTH', 5, 'fto end month', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_MONTH_UPPERCASE', 5, 'fto end month in upper case', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_END_YEAR', 5, 'fto end year', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_START_DAY', 5, 'fto start day', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_START_DAY_SINGLE_DOUBLE_DIGIT', 5, 'fto start day single digit dates', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_START_DAY_SUFFIX', 5, 'fto start daysuffix', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_START_MONTH', 5, 'fto start month', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_START_YEAR', 5, 'fto start year', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('FTO_WORTH', 5, 'Price of FTO Offer', 4, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('GENDER', 2, 'Gender', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('getDISCOUNT', 1, 'ATS Discount %', 2, 0, '10', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('getDISCOUNTDATE', 1, 'ATS Discount Valid Till Date', 13, 0, '10', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('getVALUEFRSTNO', 1, 'Number of Value-First where SMS/EMAIL has to be sent', 13, 0, '0122345678900', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('GOING_ABROAD', 2, 'Interested in Going abroad', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('GOTHRA', 2, 'user gotra', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HAVE_CAR', 2, 'Owns a car', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HEIGHT', 2, 'Height', 6, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HELP_EMAILID', 1, 'NA', 0, 0, 'help@jeevansathi.com', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HE_SHE', 2, 'Pronoun of opposite gender of a profile', 10, 10, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HIS_HER', 2, 'as per profile gender ', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HOME_PAGE', 3, 'URL of home page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('INCOME', 2, 'Income', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('INVALID_PHONE', 2, 'Y if User phone is invalid', 1, 0, 'N', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('JOB_INFO', 2, 'occupation detail', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('JS_FB_PAGE', 3, 'facbook url', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('KYC_PAGE', 3, 'NA', 1000, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('LIVING_WITH_PARENTS', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MATCH_ALERT', 3, 'match alert url in my contacts page', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MAX_NO_OF_PHOTOS', 4, 'Maximum number of photos uploaded', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MAX_PHOTO_SIZE', 4, 'Maximum Size of Photo Allowed', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MBROTHER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MEMBERSHIP', 3, 'membership page', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MEMBERSHIP_COMPARISON', 3, 'Membership comparison page', 255, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MEMBERSHIP_DETAIL', 3, 'Membership Detail Page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MOBILE_NUMBER', 2, 'Mobile number of profile', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MOTHER_OCCUPATION', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MSISTER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MSTATUS', 2, 'Marital Status', 13, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MTONGUE', 2, 'Mother Tongue', 12, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MTONGUE_SMALL', 2, 'Mapped Mother tongue if it legnth is greater 16 else Mtonuge', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MY_DPP', 3, 'dpp page', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MY_EDUCATION', 3, 'Education Layer or section', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('MY_OCCUPATION', 3, 'Occupation Layer or section', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('NAKSHATRA', 2, '10', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('NAME_PROFILE', 2, 'Name of the user of profile', 20, 6, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('NOIDALANDL', 1, 'Landline Number of Noida Inbound', 12, 0, '0120-4393500', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('NOT FOUND', 0, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('OCCUPATION', 2, 'Occupation', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('OCCUPATION_SMALL', 2, 'truncated occupation if it legnth is greater 16', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('OFFER_PAGE_URL', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('OWN_HOUSE', 2, 'Whether owns a house', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('OWN_PROFILE', 3, 'own profile view without layer', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PAIDSTATUS', 2, 'paid status for contact mailer', 2, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PASSWORD', 2, 'Password', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PGCOLLEGE_NAME', 2, 'PG College Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PHOTO_ALBUM', 3, 'URL of Photo Album', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PHOTO_EMAILID', 1, 'NA', 0, 0, 'photos@jeevansathi.com', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PHOTO_FORMATS', 4, 'Photo formats allowed', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PHOTO_REQUEST_PAGE', 3, 'Url for photo request page at contact center', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PRIVACY', 3, 'privacy settings url', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILEID', 2, 'Profile ID', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_DELETION_URL', 3, 'Profile hide/deletion url', 1000, 20, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_FAMILY', 3, 'about your info layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_HOBBIES', 3, 'about your info layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_PIC', 4, 'Url of Photo of profile', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_RELIGION', 3, 'about your info layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('PROFILE_VISITORS', 3, 'profile visitors link', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('RELIGION', 2, 'Religion', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('RELIGION_CASTE_OR_SECT_LABEL', 2, 'Labels for template [Religion & Caste] or [Religion & Sect ]', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('RELIGION_CASTE_VALUE_TEMPLATE', 2, 'Religion, Small Caste format or simply Full Caste', 50, 0, 'NA', 'NA');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('RES_STATUS', 2, 'Residential Status', 15, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SCHOOL_NAME', 2, 'School Name', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('shortlist', 3, 'NA', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SIBLINGS_INFO', 2, 'Number of Brothers and Sisters', 20, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('similarProfiles', 3, 'URL of similar profiles', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SUGGESTED_MATCHES', 3, 'suggested matches page link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('TBROTHER', 2, 'NA', 0, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('THUMB_PROFILE', 4, 'Thumbnail Profile Photo', 1, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('TOLLNO', 1, 'Toll Free Number', 11, 0, '1800-419-6299', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('TOLL_NO_KYC', 1, 'toll free mobile number for kyc mailer', 13, 0, '1800-419-6299', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('TSISTER', 2, 'NA', 10, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('UNSUBSCRIBE', 3, 'Unsubscribe link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('UPLOAD_HOROSCOPE', 3, 'upload horoscope link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('UPLOAD_PHOTO', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('USERNAME', 2, 'Username or Profile ID', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('VERIFY_PHONE', 3, 'NA', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('WEIGHT', 2, 'Weight', 8, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('YOURINFO', 2, 'about me section', 100, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('INTEREST', 3, 'Interest recieved section', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('VIEW_SIMILAR', 3, 'view similar mailer link', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HIDE_DELETE', 3, 'hide delete link', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SUCCESS_STORY', 3, 'success story link', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HOME_PAGE_MYJS', 3, 'URL of home page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('REQUEST_HOROSCOPE', 3, 'upload horoscope link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('HOROSCOPE_REQUEST_PAGE', 3, 'horoscope page link', 1000, 100, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SHORTLISTED', 3, 'NA', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('BASIC_DETAILS', 3, 'about basic details layer on my profile page', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EMAIL_VER_SUCCESS', 3, 'Email Verification', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ALTERNATE_EMAIL_VER_SUCCESS', 3, 'Alternate Email Verification', 255, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('SENT_INTEREST', 3, 'About Sent Interests', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('EOI_EXPIRING', 3, 'Interest expiring response link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('ALTERNATE_EMAIL_PROMOTION', 3, 'Alternate email promotional email.', 1000, 0, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('REQUEST_USER_TO_DELETE', 3, 'Request User to delete profile link', 1000, 1000, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('CONTACTVIEWERS', 3, 'contact viewers link', 1000, 255, 'NA', '');
INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP_NEW` VALUES ('RELIGION_CASTE_VALUE_TEMPLATE_2', 2, 'Religion, Small Caste format or simply Full Caste', 20, 0, 'NA', '');



CREATE TABLE `LINK_MAILERS_NEW` (
  `LINKID` int(11) NOT NULL AUTO_INCREMENT,
  `APP_SCREEN_ID` varchar(4) DEFAULT NULL,
  `LINK_NAME` varchar(100) DEFAULT NULL,
  `LINK_URL` varchar(200) DEFAULT NULL,
  `OTHER_GET_PARAMS` varchar(100) DEFAULT NULL,
  `REQUIRED_AUTOLOGIN` varchar(100) DEFAULT NULL,
  `OUTER_LINK` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`LINKID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

-- 
-- Dumping data for table `LINK_MAILERS_NEW`
-- 

INSERT INTO `LINK_MAILERS_NEW` VALUES (1, 'a1', 'PHOTO_ALBUM', 'profile/albumpage', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (2, 'a10', 'DETAILED_PROFILE_HOME', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (3, 'a11', 'ALLCENTRESLOCATIONS', 'profile/contact.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (4, '', 'MEMBERSHIP_COMPARISON', 'profile/mem_comparison.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (5, 'a5', 'shortlist', 'profile/contacts_made_received.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (6, '', 'similarProfiles', 'profile/simprofile_search.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (7, 'a10', 'clickOnPhoto', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (8, 'a10', 'ACCEPT', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=accept&performAction=accept', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (9, 'a10', 'DECLINE', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=decline&search_decline=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (10, 'a10', 'Phone_Email_View Contact Details', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (11, 'a2', 'HOME_PAGE', 'P/mainmenu.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (12, '', 'UNSUBSCRIBE', 'settings/alertManager', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (13, '', 'JS_FB_PAGE', 'http://www.facebook.com/jeevansathi', '', 'N', 'Y');
INSERT INTO `LINK_MAILERS_NEW` VALUES (14, 'a3', 'UPLOAD_PHOTO', 'social/addPhotos', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (15, '', 'VERIFY_PHONE', 'P/mainmenu.php', 'verify_link_from_mailer=yes', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (16, '', 'OFFER_PAGE_URL', 'fto/offer', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (17, 'a4', 'SUGGESTED_MATCHES', 'search/partnermatches', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (18, '', 'PHOTO_EMAILID', 'photos@jeevansathi.com', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (19, 'a12', 'CHANGE_NUMBER', 'P/viewprofile.php', 'ownview=1&EditWhatNew=ContactDetails', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (20, 'a10', 'EXPRESS_INTEREST', 'profile/viewprofile.php', 'kundli_type=3', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (21, 'a5', 'CC_PEOPLE_WHO_ACCEPTED_ME_URL', 'profile/contacts_made_received.php', 'page=accept&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (22, 'a6', 'PHOTO_REQUEST_PAGE', 'profile/contacts_made_received.php', 'page=photo&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (23, 'a12', 'COMPLETE_PROFILE_RANDOM', 'P/viewprofile.php', 'ownview=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (24, '', 'UPLOAD_HOROSCOPE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=AstroData', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (25, 'a12', 'COMPLETE_PROFILE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=incompletProfile&mailer=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (26, 'a12', 'COMPLETE_PROFILE_LIFESTYLE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=LifeStyle', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (27, 'a7', 'PROFILE_VISITORS', 'profile/contacts_made_received.php', 'matchedOrAll=A&page=visitors&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (28, '', 'PROFILE_DELETION_URL', 'settings/jspcSettings?hideDelete=1', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (29, '', 'FAQS_LAYER', 'profile/faqs_layer.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (30, '', 'KYC_PAGE', '/static/agentinfo', 'source=M', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (31, '', 'HELP_EMAILID', 'help@jeevansathi.com', '', 'Y', 'Y');
INSERT INTO `LINK_MAILERS_NEW` VALUES (32, '', 'PRIVACY', 'profile/revamp_privacy_settings.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (33, 'a12', 'OWN_PROFILE', 'P/viewprofile.php', 'ownview=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (34, 'a8', 'MATCH_ALERT', 'profile/contacts_made_received.php', 'page=matches&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (35, 'a13', 'MY_DPP', 'profile/dpp', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (36, '', 'MEMBERSHIP', 'profile/mem_comparison.php', 'from_source=top8Mailer', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (37, 'a9', 'EOI_RECEIVIED', 'profile/contacts_made_received.php', 'page=eoi&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (38, 'a12', 'ABOUT_ME', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=PMF', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (39, 'a12', 'EDU_OCC', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (40, 'a12', 'PROFILE_RELIGION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=RelEthnic', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (41, 'a12', 'PROFILE_FAMILY', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=FamilyDetails', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (42, 'a12', 'PROFILE_HOBBIES', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=Interests', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (43, '', 'GOOGLE_PLAY_APP', 'https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source=organic&ut', '', '', 'Y');
INSERT INTO `LINK_MAILERS_NEW` VALUES (44, '', 'NEW_MATCHES', 'search/justjoined', 'noRelaxation=1&type=NME', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (45, '', 'MEMBERSHIP_DETAIL', 'profile/mem_comparison.php', 'from_source=memMailer', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (46, 'a11', 'ALLCENTRESLOCATIONS_N', 'profile/contact.php', '', 'N', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (47, 'a12', 'MY_OCCUPATION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc&editSec=Occ', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (48, 'a12', 'MY_EDUCATION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc&editSec=Edu', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (49, '', 'I_TUNES_APP', 'https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=% {idfa}&cs=organic&cm=mailer&cn=JSIA', '', '', 'Y');
INSERT INTO `LINK_MAILERS_NEW` VALUES (50, 'a10', 'PROFILE_SHARE_LINK', 'profile/viewprofile.php', '', 'N', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (51, 'a15', 'EOI_FILTER', 'profile/contacts_made_received.php', 'page=filtered_eoi&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (54, '', 'VIEW_SIMILAR', 'search/viewSimilarProfile', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (56, '', 'HIDE_DELETE', 'settings/jspcSettings?hideDelete=1', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (55, '', 'CONTACTVIEWERS', 'inbox/17/1', 'CAME_FROM_CONTACT_MAIL=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (57, '', 'SUCCESS_STORY', 'successStory/layer', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (59, '', 'REQUEST_HOROSCOPE', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=AstroData', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (61, '', 'SHORTLISTED', 'search/shortlisted', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (63, '', 'SAVED_SEARCH', 'search/perform', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (66, '', 'KUNDLI_ALERTS', '/search/kundlialerts', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (67, '', 'SENT_INTEREST', '/inbox/6/1', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (69, '', 'EOI_EXPIRING', '/inbox/23/1', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (71, '', 'ALTERNATE_EMAIL_PROMOTION', '/P/viewprofile.php', 'ownview=1&section=contact&fieldName=ALT_EMAIL', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (53, '', 'INTEREST', 'inbox/1/1', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (58, 'a2', 'HOME_PAGE_MYJS', 'myjs/jspcPerform', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (60, '', 'HOROSCOPE_REQUEST_PAGE', 'profile/contacts_made_received.php', 'page=horoscope&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (62, '', 'BASIC_DETAILS', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=Basic', 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (64, '', 'EMAIL_VER_SUCCESS', 'static/verifyEmail', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (65, '', 'MATCHALERT_FEEDBACK', '/mailer/feedbackMatchAlertMailer', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (68, '', 'ALTERNATE_EMAIL_VER_SUCCESS', 'static/verifyAlternateEmail', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS_NEW` VALUES (70, '', 'REQUEST_USER_TO_DELETE', '/settings/jspcSettings?hideDelete=1', NULL, 'Y', 'N');


RENAME TABLE jeevansathi_mailer.EMAIL_TYPE TO jeevansathi_mailer.EMAIL_TYPE_OLD;
RENAME TABLE jeevansathi_mailer.MAILER_SUBJECT TO jeevansathi_mailer.MAILER_SUBJECT_OLD;
RENAME TABLE jeevansathi_mailer.MAILER_TEMPLATE_VARIABLES_MAP TO jeevansathi_mailer.MAILER_TEMPLATE_VARIABLES_MAP_OLD;
RENAME TABLE jeevansathi_mailer.LINK_MAILERS TO jeevansathi_mailer.LINK_MAILERS_OLD;

RENAME TABLE jeevansathi_mailer.EMAIL_TYPE_NEW TO jeevansathi_mailer.EMAIL_TYPE;
RENAME TABLE jeevansathi_mailer.MAILER_SUBJECT_NEW TO jeevansathi_mailer.MAILER_SUBJECT;
RENAME TABLE jeevansathi_mailer.MAILER_TEMPLATE_VARIABLES_MAP_NEW TO jeevansathi_mailer.MAILER_TEMPLATE_VARIABLES_MAP;
RENAME TABLE jeevansathi_mailer.LINK_MAILERS_NEW TO jeevansathi_mailer.LINK_MAILERS;