use newjs;
-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Feb 04, 2013 at 02:55 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8
-- 
-- Database: `newjs`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `PICTURE_DISPLAY_LOGIC`
-- 

CREATE TABLE `PICTURE_DISPLAY_LOGIC` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `HAVEPHOTO` char(1) NOT NULL,
  `PHOTO_DISPLAY` char(1) NOT NULL,
  `PRIVACY` char(1) NOT NULL,
  `LOGIN_STATUS` char(1) NOT NULL,
  `FILTERS_PASSED` char(1) NOT NULL,
  `CONTACT_STATUS` varchar(2) NOT NULL,
  `IS_PHOTO_SHOWN` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=latin1 AUTO_INCREMENT=150 ;

-- 
-- Dumping data for table `PICTURE_DISPLAY_LOGIC`
-- 

INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (1, 'Y', 'A', 'A', 'Y', 'D', 'DM', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (2, 'Y', 'A', 'F', 'Y', 'N', 'N', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (3, 'Y', 'A', 'F', 'Y', 'N', 'RI', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (4, 'Y', 'A', 'F', 'Y', 'N', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (5, 'Y', 'A', 'F', 'Y', 'N', 'RD', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (6, 'Y', 'A', 'F', 'Y', 'N', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (7, 'Y', 'A', 'F', 'Y', 'N', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (8, 'Y', 'A', 'F', 'Y', 'N', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (9, 'Y', 'A', 'F', 'Y', 'Y', 'N', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (10, 'Y', 'A', 'F', 'Y', 'Y', 'RI', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (11, 'Y', 'A', 'F', 'Y', 'Y', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (12, 'Y', 'A', 'F', 'Y', 'Y', 'RD', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (13, 'Y', 'A', 'F', 'Y', 'Y', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (14, 'Y', 'A', 'F', 'Y', 'Y', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (15, 'Y', 'A', 'F', 'Y', 'Y', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (16, 'Y', 'A', 'R', 'Y', 'D', 'DM', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (17, 'Y', 'A', 'R', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (18, 'Y', 'A', 'F', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (19, 'Y', 'C', 'A', 'Y', 'D', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (20, 'Y', 'C', 'A', 'Y', 'D', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (21, 'Y', 'C', 'A', 'Y', 'D', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (22, 'Y', 'C', 'A', 'Y', 'D', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (23, 'Y', 'C', 'A', 'Y', 'D', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (24, 'Y', 'C', 'A', 'Y', 'D', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (25, 'Y', 'C', 'A', 'Y', 'D', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (26, 'Y', 'C', 'F', 'Y', 'Y', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (27, 'Y', 'C', 'F', 'Y', 'Y', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (28, 'Y', 'C', 'F', 'Y', 'Y', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (29, 'Y', 'C', 'F', 'Y', 'Y', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (30, 'Y', 'C', 'F', 'Y', 'Y', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (31, 'Y', 'C', 'F', 'Y', 'Y', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (32, 'Y', 'C', 'F', 'Y', 'Y', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (33, 'Y', 'C', 'F', 'Y', 'N', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (34, 'Y', 'C', 'F', 'Y', 'N', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (35, 'Y', 'C', 'F', 'Y', 'N', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (36, 'Y', 'C', 'F', 'Y', 'N', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (37, 'Y', 'C', 'F', 'Y', 'N', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (38, 'Y', 'C', 'F', 'Y', 'N', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (39, 'Y', 'C', 'F', 'Y', 'N', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (40, 'Y', 'C', 'R', 'Y', 'D', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (41, 'Y', 'C', 'R', 'Y', 'D', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (42, 'Y', 'C', 'R', 'Y', 'D', 'RA', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (43, 'Y', 'C', 'R', 'Y', 'D', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (44, 'Y', 'C', 'R', 'Y', 'D', 'I', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (45, 'Y', 'C', 'R', 'Y', 'D', 'A', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (46, 'Y', 'C', 'R', 'Y', 'D', 'D', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (47, 'Y', 'C', 'R', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (48, 'U', 'A', 'A', 'Y', 'D', 'DM', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (49, 'U', 'A', 'F', 'Y', 'N', 'N', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (50, 'U', 'A', 'F', 'Y', 'N', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (51, 'U', 'A', 'F', 'Y', 'N', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (52, 'U', 'A', 'F', 'Y', 'N', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (53, 'U', 'A', 'F', 'Y', 'N', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (54, 'U', 'A', 'F', 'Y', 'N', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (55, 'U', 'A', 'F', 'Y', 'N', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (56, 'U', 'A', 'F', 'Y', 'Y', 'N', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (57, 'U', 'A', 'F', 'Y', 'Y', 'RI', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (58, 'U', 'A', 'F', 'Y', 'Y', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (59, 'U', 'A', 'F', 'Y', 'Y', 'RD', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (60, 'U', 'A', 'F', 'Y', 'Y', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (61, 'U', 'A', 'F', 'Y', 'Y', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (62, 'U', 'A', 'F', 'Y', 'Y', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (63, 'U', 'A', 'R', 'Y', 'D', 'DM', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (64, 'U', 'A', 'R', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (65, 'U', 'A', 'F', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (66, 'U', 'C', 'A', 'Y', 'D', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (67, 'U', 'C', 'A', 'Y', 'D', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (68, 'U', 'C', 'A', 'Y', 'D', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (69, 'U', 'C', 'A', 'Y', 'D', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (70, 'U', 'C', 'A', 'Y', 'D', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (71, 'U', 'C', 'A', 'Y', 'D', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (72, 'U', 'C', 'A', 'Y', 'D', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (73, 'U', 'C', 'F', 'Y', 'Y', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (74, 'U', 'C', 'F', 'Y', 'Y', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (75, 'U', 'C', 'F', 'Y', 'Y', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (76, 'U', 'C', 'F', 'Y', 'Y', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (77, 'U', 'C', 'F', 'Y', 'Y', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (78, 'U', 'C', 'F', 'Y', 'Y', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (79, 'U', 'C', 'F', 'Y', 'Y', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (80, 'U', 'C', 'F', 'Y', 'N', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (81, 'U', 'C', 'F', 'Y', 'N', 'RI', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (82, 'U', 'C', 'F', 'Y', 'N', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (83, 'U', 'C', 'F', 'Y', 'N', 'RD', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (84, 'U', 'C', 'F', 'Y', 'N', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (85, 'U', 'C', 'F', 'Y', 'N', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (86, 'U', 'C', 'F', 'Y', 'N', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (87, 'U', 'C', 'R', 'Y', 'D', 'N', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (88, 'U', 'C', 'R', 'Y', 'D', 'RI', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (89, 'U', 'C', 'R', 'Y', 'D', 'RA', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (90, 'U', 'C', 'R', 'Y', 'D', 'RD', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (91, 'U', 'C', 'R', 'Y', 'D', 'I', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (92, 'U', 'C', 'R', 'Y', 'D', 'A', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (93, 'U', 'C', 'R', 'Y', 'D', 'D', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (94, 'U', 'C', 'R', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (110, 'Y', 'C', 'F', 'Y', 'N', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (105, 'U', 'A', 'F', 'Y', 'Y', 'C', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (106, 'Y', 'C', 'A', 'Y', 'D', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (107, 'Y', 'C', 'F', 'Y', 'Y', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (126, 'U', 'A', 'F', 'Y', 'Y', 'E', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (113, 'U', 'C', 'R', 'Y', 'D', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (112, 'U', 'C', 'F', 'Y', 'N', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (111, 'Y', 'C', 'R', 'Y', 'D', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (109, 'U', 'C', 'F', 'Y', 'Y', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (116, 'Y', 'C', 'R', 'Y', 'D', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (95, 'Y', 'A', 'A', 'N', 'D', 'DM', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (96, 'U', 'A', 'A', 'N', 'D', 'DM', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (119, 'Y', 'A', 'F', 'Y', 'Y', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (118, 'Y', 'A', 'F', 'Y', 'N', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (108, 'U', 'C', 'A', 'Y', 'D', 'C', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (104, 'U', 'A', 'F', 'Y', 'N', 'C', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (103, 'Y', 'A', 'F', 'Y', 'Y', 'C', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (102, 'Y', 'A', 'F', 'Y', 'N', 'C', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (97, 'N', '', '', '', '', '', 'requestPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (98, 'Y', 'C', 'A', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (99, 'U', 'C', 'A', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (100, 'Y', 'C', 'F', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (101, 'U', 'C', 'F', 'N', 'D', 'DM', 'nonLoggedInPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (114, 'Y', 'C', 'A', 'Y', 'D', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (115, 'U', 'C', 'A', 'Y', 'D', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (117, 'U', 'C', 'R', 'Y', 'D', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (120, 'U', 'A', 'F', 'Y', 'Y', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (121, 'U', 'A', 'F', 'Y', 'N', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (122, 'Y', 'C', 'F', 'Y', 'Y', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (123, 'Y', 'C', 'F', 'Y', 'N', 'RC', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (124, 'U', 'C', 'F', 'Y', 'Y', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (125, 'U', 'C', 'F', 'Y', 'N', 'RC', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (127, 'U', 'A', 'F', 'Y', 'N', 'E', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (128, 'U', 'C', 'R', 'Y', 'D', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (129, 'U', 'C', 'F', 'Y', 'N', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (130, 'U', 'C', 'A', 'Y', 'D', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (131, 'U', 'C', 'F', 'Y', 'Y', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (132, 'Y', 'C', 'F', 'Y', 'N', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (133, 'Y', 'A', 'F', 'Y', 'Y', 'E', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (134, 'Y', 'C', 'R', 'Y', 'D', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (135, 'Y', 'C', 'F', 'Y', 'Y', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (136, 'Y', 'C', 'A', 'Y', 'D', 'E', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (137, 'Y', 'A', 'F', 'Y', 'N', 'E', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (138, 'U', 'A', 'F', 'Y', 'Y', 'RE', 'underScreeningPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (139, 'U', 'A', 'F', 'Y', 'N', 'RE', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (140, 'Y', 'A', 'F', 'Y', 'Y', 'RE', 'yes');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (141, 'Y', 'A', 'F', 'Y', 'N', 'RE', 'filteredPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (142, 'U', 'C', 'R', 'Y', 'D', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (143, 'U', 'C', 'F', 'Y', 'N', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (144, 'Y', 'C', 'R', 'Y', 'D', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (145, 'Y', 'C', 'F', 'Y', 'N', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (146, 'U', 'C', 'A', 'Y', 'D', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (147, 'U', 'C', 'F', 'Y', 'Y', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (148, 'Y', 'C', 'A', 'Y', 'D', 'RE', 'contactAcceptedPhoto');
INSERT INTO `PICTURE_DISPLAY_LOGIC` VALUES (149, 'Y', 'C', 'F', 'Y', 'Y', 'RE', 'contactAcceptedPhoto');
