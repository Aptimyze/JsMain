use jeevansathi_mailer;
CREATE TABLE `LINK_MAILERS` (
  `LINKID` int(11) NOT NULL AUTO_INCREMENT,
  `APP_SCREEN_ID` varchar(4) DEFAULT NULL,
  `LINK_NAME` varchar(100) DEFAULT NULL,
  `LINK_URL` varchar(100) DEFAULT NULL,
  `OTHER_GET_PARAMS` varchar(100) DEFAULT NULL,
  `REQUIRED_AUTOLOGIN` varchar(100) DEFAULT NULL,
  `OUTER_LINK` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`LINKID`)
) ENGINE=MyISAM;


INSERT INTO `LINK_MAILERS` VALUES (1, 'a1', 'PHOTO_ALBUM', 'profile/albumpage', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (2, 'a10', 'DETAILED_PROFILE_HOME', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (3, 'a11', 'ALLCENTRESLOCATIONS', 'profile/contact.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (4, NULL, 'MEMBERSHIP_COMPARISON', 'profile/mem_comparison.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (5, NULL, 'shortlist', 'profile/contacts_made_received.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (6, NULL, 'similarProfiles', 'profile/simprofile_search.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (9, 'a10', 'clickOnPhoto', 'profile/viewprofile.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (10, 'a10', 'ACCEPT', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=accept', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (11, 'a10', 'DECLINE', 'profile/viewprofile.php', 'CAME_FROM_CONTACT_MAIL=1&button=decline&search_decline=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (13, 'a10', 'Phone_Email_View Contact Details', 'profile/viewprofile.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (14, 'a2', 'HOME_PAGE', 'P/mainmenu.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (15, NULL, 'UNSUBSCRIBE', 'profile/unsubscribe.php', '', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (16, NULL, 'JS_FB_PAGE', 'http://www.facebook.com/jeevansathi', NULL, 'N', 'Y');
INSERT INTO `LINK_MAILERS` VALUES (17, 'a3', 'UPLOAD_PHOTO', 'social/addPhotos', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (18, NULL, 'VERIFY_PHONE', 'P/mainmenu.php', 'verify_link_from_mailer=yes', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (19, NULL, 'OFFER_PAGE_URL', 'fto/offer', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (20, 'a4', 'SUGGESTED_MATCHES', 'search/partnermatches', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (21, NULL, 'PHOTO_EMAILID', 'photos@jeevansathi.com', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (22, 'a12', 'CHANGE_NUMBER', 'P/viewprofile.php', 'ownview=1&EditWhatNew=ContactDetails', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (23, 'a10', 'EXPRESS_INTEREST', 'profile/viewprofile.php', 'kundli_type=3', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (24, 'a5', 'CC_PEOPLE_WHO_ACCEPTED_ME_URL', 'profile/contacts_made_received.php', 'page=accept&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (25, 'a6', 'PHOTO_REQUEST_PAGE', 'profile/contacts_made_received.php', 'page=photo&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (26, 'a12', 'COMPLETE_PROFILE_RANDOM', 'P/viewprofile.php', 'ownview=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (27, NULL, 'UPLOAD_HOROSCOPE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=AstroData', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (28, 'a12', 'COMPLETE_PROFILE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=incompletProfile&mailer=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (29, 'a12', 'COMPLETE_PROFILE_LIFESTYLE', 'P/viewprofile.php', 'ownview=1&EditWhatNew=LifeStyle', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (30, 'a7', 'PROFILE_VISITORS', 'profile/contacts_made_received.php', 'page=visitors&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (31, NULL, 'PROFILE_DELETION_URL', 'profile/hide_delete_revamp.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (32, NULL, 'FAQS_LAYER', 'profile/faqs_layer.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (33, NULL, 'KYC_PAGE', '/static/agentinfo', 'source=M', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (34, NULL, 'HELP_EMAILID', 'help@jeevansathi.com', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (35, NULL, 'PRIVACY', 'profile/revamp_privacy_settings.php', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (36, 'a12', 'OWN_PROFILE', 'P/viewprofile.php', 'ownview=1', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (37, 'a8', 'MATCH_ALERT', 'profile/contacts_made_received.php', 'page=matches&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (38, 'a13', 'MY_DPP', 'profile/dpp', NULL, 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (39, NULL, 'MEMBERSHIP', 'profile/mem_comparison.php', 'from_source=top8Mailer', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (40, 'a9', 'EOI_RECEIVIED', 'profile/contacts_made_received.php', 'page=eoi&filter=R', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (41, 'a12', 'ABOUT_ME', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=PMF', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (42, 'a12', 'EDU_OCC', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (43, 'a12', 'PROFILE_RELIGION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=RelEthnic', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (44, 'a12', 'PROFILE_FAMILY', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=FamilyDetails', 'Y', 'N');
INSERT INTO `LINK_MAILERS` VALUES (45, 'a12', 'PROFILE_HOBBIES', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=Interests', 'Y', 'N');
