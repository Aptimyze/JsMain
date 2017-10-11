-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Mar 22, 2011 at 11:19 AM
-- Server version: 5.0.27
-- PHP Version: 5.1.6
-- 
-- Database: `sugarcrm`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `acl_roles`
-- 

DROP TABLE IF EXISTS `acl_roles`;
CREATE TABLE `acl_roles` (
  `id` char(36) NOT NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `modified_user_id` char(36) default NULL,
  `created_by` char(36) default NULL,
  `name` varchar(150) default NULL,
  `description` text,
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_aclrole_id_del` (`id`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `acl_roles`
-- 

INSERT INTO `acl_roles` VALUES ('c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2009-07-09 13:30:27', '2009-09-07 10:46:55', '1', '1', 'Executive', 'Define roles of an executive.', 0);
INSERT INTO `acl_roles` VALUES ('1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2009-07-20 05:21:22', '2009-09-07 10:48:08', '1', '1', 'Manager', 'Defines role of a Manager', 0);
INSERT INTO `acl_roles` VALUES ('793edc9c-2ba9-f1db-fe11-4c205047ea4c', '2010-06-22 05:54:56', '2010-06-22 05:54:56', '1', '1', 'Operator', 'For operating leads', 0);
INSERT INTO `acl_roles` VALUES ('bf80890a-e90e-66c0-97e4-4c62383e4936', '2010-08-11 05:42:43', '2010-08-11 05:42:43', '1', '1', 'Supervisor', NULL, 0);
INSERT INTO `acl_roles` VALUES ('7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2010-11-02 11:28:52', '2010-11-02 11:28:52', '1', '1', 'Registration Executive', 'executives that will belong to the registration team', 0);

-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Mar 22, 2011 at 11:20 AM
-- Server version: 5.0.27
-- PHP Version: 5.1.6
-- 
-- Database: `sugarcrm`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `acl_roles_actions`
-- 

DROP TABLE IF EXISTS `acl_roles_actions`;
CREATE TABLE `acl_roles_actions` (
  `id` varchar(36) NOT NULL,
  `role_id` varchar(36) default NULL,
  `action_id` varchar(36) default NULL,
  `access_override` int(3) default NULL,
  `date_modified` datetime default NULL,
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_acl_role_id` (`role_id`),
  KEY `idx_acl_action_id` (`action_id`),
  KEY `idx_aclrole_action` (`role_id`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `acl_roles_actions`
-- 

INSERT INTO `acl_roles_actions` VALUES ('38589bd2-2e30-b23e-fb7f-4a55f1dbf056', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'bfb6cbfc-3111-cf55-d660-4a51a653c3b4', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3904fb8f-db2a-4a91-b6bf-4a55f1cb6943', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c82e2a33-5868-33c2-a898-4a51a6efaa19', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('398dfa4e-b1e1-105c-201d-4a55f1b14cb5', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c5475644-d4cb-b39d-e60e-4a51a6e46e77', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3a76fb50-4a08-861e-4cf0-4a55f19b3572', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c9ed0c9e-2b09-3abd-e512-4a51a6d2c106', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3ad9e4c0-8d9e-d2b1-c819-4a55f1b9b049', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c8feccc9-8e34-a3be-b351-4a51a66f2d67', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3b5aacdb-6d92-80fe-d8ee-4a55f115a508', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c34b8b63-a720-c24d-68e6-4a51a6313505', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3bce3397-628e-7a49-0521-4a55f138c286', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c19c7514-5263-da4e-a480-4a51a66a07ee', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3c4cb7a0-a191-a15c-aa39-4a55f1641aea', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7c432b37-ea2a-d7b9-c311-4a51a60790ee', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3ce158fc-cc42-5dd3-472f-4a55f17b6056', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7f9d1df5-e605-d3fb-4d30-4a51a66ea3e4', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3d714aa4-81b9-2c3e-1828-4a55f1b914d8', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7e60506a-fee5-fd92-b9ed-4a51a6292345', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3e19ba9f-3a22-8f26-6491-4a55f1525afe', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '8e860689-95f2-0642-ed7c-4a51a6c7e095', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3ea01e03-e52c-76ea-7a32-4a55f1eaaef4', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '8c24a282-40e3-ad37-cb11-4a51a6e4b0f7', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3f15649f-8e96-a130-4a47-4a55f101f78a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7da633be-b46c-d8e7-8307-4a51a629d5b0', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('3f88b651-fd80-f475-068b-4a55f1d33a9d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7cfc3153-3448-ba6e-249b-4a51a6b5e8b4', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('402fd181-603a-1556-a0dd-4a55f117bace', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3cb89bea-e83c-eab2-eb71-4a51a6aa9814', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('40cc1d99-4e54-3468-c903-4a55f1a46e6a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '40651b52-e8d9-8d6e-a925-4a51a6e560df', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4142dd15-4f1b-8bbe-e731-4a55f1a1277a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3f941b68-b1b0-0cac-06b2-4a51a6876d7c', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('41cecd40-e92d-18f1-5398-4a55f1adcd67', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '4a4d87f7-86ee-ddc7-7ffa-4a51a6142558', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('42567267-6ac5-3de8-af2e-4a55f1921f9f', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '43d67b32-e417-dd94-abb0-4a51a649952c', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('42fe5a7a-7510-8047-752b-4a55f103d121', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3eb7418e-841c-c40f-c1de-4a51a6e3e253', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4378bcee-2b7b-4732-3984-4a55f1d3c8d5', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3de61db6-6c14-94e8-63fd-4a51a6e4e4a4', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('43f38b85-4278-31af-c10a-4a55f1bc209e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'e0299b25-1315-cece-33b5-4a51a68a1364', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4479547c-0574-3016-96d1-4a55f1a667eb', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'eb98311c-34eb-0885-074d-4a51a6da1181', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('451424c9-6787-4dae-1534-4a55f1852aa6', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'e7255e45-1056-7dfd-9314-4a51a6b628e9', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('45935173-e379-936c-b02f-4a55f1c86aed', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'f3593ea1-d472-5c46-e503-4a51a618b72b', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4613a8e2-5926-2d42-9ed1-4a55f12518a9', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'f10083be-3a6c-786c-548e-4a51a625b9d4', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('46983126-3d80-0e96-12b7-4a55f10ca3d8', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'e440466c-c562-8306-6ed5-4a51a607d0e8', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('472788f9-6798-a572-487a-4a55f1c926c6', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'e1f46267-fc8b-ab57-4ad2-4a51a65a4738', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4b751d6b-b06a-ee58-0591-4a55f138aaba', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '713d9d56-3b2a-38fa-65b1-4a51a63a0c7c', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4c064f28-3d90-220c-ea18-4a55f1a95763', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7aa3856e-aa3c-902c-dbc0-4a51a639d838', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4cc06b0f-1de0-5187-0daa-4a55f18d89ec', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '799b290e-b8c5-ffe7-69e8-4a51a6aa018e', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4d6d42a1-92cd-080d-701f-4a55f1cc417d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7e04d084-aaf0-8e82-a427-4a51a63ff627', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4dff633f-de6d-5d4f-1753-4a55f14afcfb', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7c24e1d9-50e2-10b3-cee8-4a51a6ee404e', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4e725309-5df9-3c91-4ce0-4a55f1eb8b99', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '78373e7c-f306-9fdd-ca8c-4a51a634a405', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4f4a5a8f-fbfe-344e-e8c0-4a55f10731a1', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '74901e8a-ab7f-a10a-bc96-4a51a6bd9bf9', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('4fe275a5-b1a5-933a-fe6e-4a55f182d22e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '703baf02-571f-c91b-8759-4a51a643a3ad', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5084249b-4947-ea4e-63c4-4a55f1e99fdd', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '791f0ca7-241b-04b1-69d4-4a51a61a95c1', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5131e0bd-eb65-bf35-b973-4a55f1d55cd7', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '774a67ec-7e16-c968-596e-4a51a632d964', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('51b610d6-d930-cb36-6d54-4a55f187bdf2', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7d8c76b0-a06b-3262-be24-4a51a61ce6f5', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5228ccab-7cb9-0e47-0cbf-4a55f1132ec5', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7c033b9d-9678-b3aa-adf0-4a51a6d942ab', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('529f2337-2233-5a33-e361-4a55f1d886e7', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '75854284-62a7-371c-6ef3-4a51a6e89155', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('53c2fb1f-27ba-86d2-d1fd-4a55f1bd7e64', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '730f751b-c4be-dc7f-d80b-4a51a623e6fa', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('55f598d8-f47f-0e4d-74b1-4a55f128eb2b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '79838ab3-595d-7f71-1fc9-4a51a6296189', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('566e030f-6bde-1a74-5139-4a55f1e58dca', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '911de040-91e8-5220-d3c4-4a51a67e7901', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('56e2d50a-9be6-6526-94f4-4a55f146cd39', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7f3fcede-ebb3-2a8c-7938-4a51a6c3b6df', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('575a1dd5-e32a-3ce3-636e-4a55f17bd4b2', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '9385bb70-cbbc-d085-13af-4a51a68b3a5b', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('57e8980e-042d-1e04-8c52-4a55f16bc763', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '92841ab8-e5f9-2c9a-4b79-4a51a6176db7', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('585bac94-9b29-494b-824f-4a55f139f2be', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7d6adc18-dcb3-844d-d010-4a51a688120b', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('58cecf5c-2708-0294-36a1-4a55f1312004', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7bb28ad6-1c0a-77f5-5ae0-4a51a6a7732e', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5940bcd0-95a9-ef6a-40d3-4a55f1659b69', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b140aab9-6c1c-f4c6-3966-4a51a610cd6c', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('59b05142-16b7-b236-969d-4a55f14f2f4b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b511c6a1-6dd7-5ccd-d099-4a51a67a2148', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5a269124-8e6a-7e91-b76d-4a55f1338d3e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b411d1cf-8437-810d-53fd-4a51a6a01a01', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5a98d9fb-0424-3769-c881-4a55f1ae5fba', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b6fa78bb-9aeb-2bba-e54a-4a51a6bcf14c', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5b0b75de-5002-cdf8-a981-4a55f1634756', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b5be0bc6-a1d6-3325-920e-4a51a63e43d8', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5b7d4075-7625-5408-7d07-4a55f1b11564', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b34da496-561a-e5cb-d60f-4a51a61dcfad', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5beec275-3f6d-dbc1-d98c-4a55f10c6747', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'b26fafc8-55bb-83f2-041b-4a51a6f468c1', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5c60d650-b875-afdf-8a79-4a55f1d769e3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '62c8b425-fa33-7f3b-9f88-4a51a6feacee', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5cd7bb5d-460d-3330-db9a-4a55f1e19999', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '66f0207d-76c3-1839-312b-4a51a6ab76ae', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5d6398ed-cba1-2643-2d6f-4a55f1c02b3a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '65fd6f9a-5c16-1a5a-059e-4a51a6eaf852', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5de8096b-5018-abe7-03b2-4a55f11ef0bd', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '68660a6d-3b5e-da12-dceb-4a51a6568ad8', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5e61c878-2f8f-91d5-c93b-4a55f12d2ca5', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '67ae51f3-74dc-6961-8283-4a51a672ccc1', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5ef9e55e-80c3-4053-0aca-4a55f10fb952', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '64ffe441-ed87-7f72-f19c-4a51a665ea85', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('5fb81b2b-4138-a08b-6850-4a55f1d35578', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '63f59bfb-8d48-b9f3-c063-4a51a638165c', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('606bbbd3-40cd-f45a-4957-4a55f1608253', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'd697d5c2-2fa3-d06e-ec92-4a51a6c929f8', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('628a6074-9d8f-ee11-3129-4a55f118b6d4', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'dc189ba4-2dd9-c5d6-982f-4a51a6a3003b', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('631ab71b-0059-fe67-e37b-4a55f1f0bdc3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'dac13aed-19c7-bdc2-5df7-4a51a6ca36ea', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('644d6f4e-c766-1d92-c928-4a55f18afaee', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'de14d9ce-f8cb-fc5d-ccee-4a51a6cae9d5', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('678a99fe-4419-11bd-e17d-4a55f13efc6e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'dd2aeedf-825b-18fc-f9dd-4a51a6e8f989', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('686e1ad5-3b56-5f78-bec9-4a55f1ef6284', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'd864b77a-d3af-caee-ebbc-4a51a61e1b80', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('68efb317-f593-5ad2-bb12-4a55f17fedf9', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'd790914e-9e95-cde6-cc7f-4a51a6acf0ba', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('6afed2c0-97d0-7198-f25d-4a55f1265d2c', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2c60df0c-c138-f78d-3b75-4a51a6534027', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('6d5484c4-a315-1da5-e53b-4a55f12db871', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3173349f-0b07-43fb-5354-4a51a69fa818', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('6e2d9b16-7a6e-880f-992c-4a55f1f06640', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '30385c32-d223-b568-7767-4a51a6424311', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('6eab8cc4-ab75-5f0f-484b-4a55f10ca546', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '35652b1a-79b9-bd73-53de-4a51a65fd85f', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('6f1e6f27-5f76-2d4e-35bd-4a55f1c8535e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '332fb287-a3ca-17d2-13fa-4a51a6742888', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('709f7a44-0f83-ab2f-20c9-4a55f1a4fb9a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2eee8c89-0162-21e4-4ab3-4a51a646444c', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7128f635-4f3b-4607-3e91-4a55f11cc8c3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2ddaa1bf-b126-82ab-5c0f-4a51a6ddf433', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('719c96dc-465a-4c2d-2dcc-4a55f14ddcad', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '90cadcfa-3365-9cf3-3eca-4a51a6b3742b', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('72423213-28f2-c363-57a7-4a55f1790520', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '95dc1b23-98bd-c1b0-5296-4a51a6c74f82', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('73032d40-0ddf-aa44-8d1f-4a55f115a14a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '94d1acde-9bbc-9f68-0ac5-4a51a62f2ac2', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('73912bcc-390f-d3ba-8a01-4a55f142262b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '99c2b29e-714e-7292-9140-4a51a6163881', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('741d1106-7af7-b2b7-60ac-4a55f1c82df1', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '96995755-c0aa-539f-4a4f-4a51a6ae9ec2', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('74947025-c5fb-870b-f945-4a55f13562fe', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '9341eba7-ed7a-97f6-118e-4a51a6b14c64', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('750b9022-435a-b754-123e-4a55f11e944c', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '91f304c4-2f52-d657-41c4-4a51a60a61f9', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('759b9156-973d-9266-b269-4a55f171e0f4', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c07f8faa-fb44-bc82-049f-4a51a6de4eee', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7638ba0e-5f32-0c69-193e-4a55f19c7158', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c3903fd6-c120-f120-e2bd-4a51a662c208', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('76db21f0-9bbc-44cd-2132-4a55f172594d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c2d2e6ee-70cb-f4fd-adb7-4a51a611c38f', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('779585c9-0e16-a15b-ce04-4a55f183b070', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c50ea9a9-afd6-9169-d2c2-4a51a630c54a', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('784c28d2-534a-8dde-8f65-4a55f1f7e82d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c446065d-b11a-66ea-a039-4a51a6fb4640', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7905d633-fc4d-7d28-2ea2-4a55f1304428', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c21bd49d-616a-1ae8-6c94-4a51a6a8a5d6', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7994032b-f5c7-8389-66aa-4a55f1433ada', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c151fb25-0a6f-2238-972f-4a51a69547c6', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7a55d2fc-a986-abd2-e93c-4a55f1335791', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '1e4e5d0b-5e0a-a519-22c0-4a51a6d912ef', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7b036b22-142e-b4fb-5b85-4a55f17d20ee', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '24cede5a-07b5-0b39-1036-4a51a6fa4865', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7bb15dd9-faa3-ee68-183b-4a55f179a325', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2383c89d-4ce6-07c6-1f9c-4a51a615c0b9', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7c542e4a-b9c0-0ca3-f46b-4a55f17455e6', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2781a8de-481c-389c-b1d2-4a51a63cfb4c', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7d067349-d373-44c4-4acc-4a55f1c7d655', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2616637e-53e2-e5f0-c8a4-4a51a61e762a', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7db1aa3a-1e32-f3b4-0f19-4a55f1464191', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '2144c457-cf54-b310-0f12-4a51a6ad74c7', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7e3b0670-34e4-e4f8-5d60-4a55f1360c66', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '1fcafb8c-34ef-f572-90fc-4a51a635c87f', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7ec56dc3-43e3-fe84-455a-4a55f1289970', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '51eebf40-cef0-f526-d794-4a51a688c477', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('7fa56489-e633-ef42-540c-4a55f1901c5e', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '5598e9d7-d707-f290-ed83-4a51a64d65bb', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('803e863d-8456-b0f0-e6c8-4a55f104fea6', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '5499014b-b125-a621-31a5-4a51a62f0557', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('80d4d63b-4e9d-e206-edb1-4a55f1d37c20', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '58e6c267-60ad-f684-e3a0-4a51a621071a', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('81825e28-ca18-98b4-0b95-4a55f1d0de00', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '56ae374e-924f-4200-355b-4a51a6d997da', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8207e032-83a0-b768-b230-4a55f1ef4b3b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '53b9172c-9627-ead3-7a2f-4a51a62de353', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8293bc77-315f-6f5b-0ab9-4a55f1f940bd', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '52ebfe9a-c4d4-80e0-d146-4a51a6f6213e', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('831ff715-6205-87e1-abcc-4a55f18457cf', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '79641b81-e952-a709-587c-4a51a6e8a2ea', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('83cfc35b-3473-6eb7-b8e5-4a55f11c3716', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7da77e2d-e953-a117-6f65-4a51a6476829', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8481cf1a-8307-70fc-19cb-4a55f10b5f1f', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7ccd156b-ed09-5cea-0451-4a51a623af6b', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('851a3b11-d3a8-f4b6-5e00-4a55f1dc3c9b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7f34ca09-5cff-320f-d692-4a51a6f19226', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('85de3141-7502-1227-9ba0-4a55f107a3d4', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7e58e565-df43-d5b5-03f7-4a51a6220c54', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8695aed8-a178-d0e5-9532-4a55f165d26f', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7c1f3fc8-9615-ac5f-0719-4a51a64b92c0', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8728cc0e-c083-a08e-8af8-4a55f128512a', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '7b11214b-067b-84c2-1ff8-4a51a6f97cfb', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('887ee8ca-f724-577a-b677-4a55f12cda63', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '31adbb19-69be-08cd-b500-4a51a6474ad9', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('893a79eb-2f09-8b6c-a92f-4a55f15a7536', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '373077c0-2fad-2140-2a95-4a51a63555be', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8a16260c-0e90-2acd-9dc2-4a55f18fd219', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '358d55e6-c77a-8d7b-0f0a-4a51a6c9729f', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8aaf6393-ab9e-69a3-faf7-4a55f1987bba', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '3aff187b-6266-fea5-0a7d-4a51a61cf2c8', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8b6ee9c4-1039-1f8c-6743-4a55f1e79c20', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '394c07ac-ec44-9753-aa8c-4a51a6ecb767', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8c57e853-aaba-8c03-a8d2-4a55f1e7abd3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '34320a32-e8f1-c97a-97cc-4a51a6ae08a1', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8d191d85-a8f0-7555-5fec-4a55f1f4a355', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '32f6b592-ca1b-4bdc-16c9-4a51a63832f1', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8dd2f99d-bcb5-d14d-c2c2-4a55f1718a3d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '4feb491e-235c-c47a-6833-4a51a6b1225e', -98, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8e65c144-bafb-e56b-a544-4a55f165d0a6', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '58908154-957a-d4c5-8f84-4a51a60094eb', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8efbf80b-293a-f634-f0c5-4a55f106f517', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '55033fc0-673c-c62b-891b-4a51a6a23fb8', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('8fa1a579-657c-210b-d9f7-4a55f10eb86b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '5c17c7e6-f07d-f777-0062-4a51a651a623', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('904e9048-4161-908b-5946-4a55f192ae9f', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '5a85c2db-eec0-3f38-394d-4a51a6f7f872', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('91026658-44b9-0d43-16f3-4a55f13cc8c8', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '531182c5-bda6-41d7-3963-4a51a64d3f77', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('91cb0dd8-5998-7067-919d-4a55f1fdb1ff', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '5150f12d-9847-9650-f394-4a51a6fa47cb', 0, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('92546ac3-9fa2-30b8-7edd-4a55f1b64856', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c2a9cfad-8f45-ddc6-c801-4a51a67a5b84', 89, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('92e3de50-bb62-9373-addb-4a55f155a95f', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c9d8c958-692c-f02f-8367-4a51a62ef913', -99, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('9364c495-dfc1-6332-a1c1-4a55f1ba11cd', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c910c459-7609-7c25-8d6b-4a51a643ccbb', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('9427df3e-af9b-962c-b857-4a55f121d3da', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'cb9b50bb-7e3f-6500-4698-4a51a6d9040a', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('94ddb1ae-abed-4df1-3627-4a55f174b7b3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'caa6c9c8-d720-820f-79e4-4a51a6be7670', 90, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('95a4d3af-9f36-c8ae-78b6-4a55f100b07d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c829691f-cf35-9c18-285e-4a51a63adebb', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('96507907-83a2-3734-408d-4a55f185f4a3', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'c75e29cd-c14c-0e8b-169b-4a51a64fa9a9', 75, '2010-11-30 06:33:04', 0);
INSERT INTO `acl_roles_actions` VALUES ('96f8e0de-635e-498c-fe9a-4a55f19c44eb', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'cbf806d8-8681-eb91-4e9c-4a51a666b76a', -98, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('989c6e10-5475-b2de-57f6-4a55f1ed43ea', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '101a24bc-7e11-6ea1-bbd8-4a51a68be7f5', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('9928a9a6-0af7-fdf5-b216-4a55f161b78d', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'f2390a83-f868-04bf-beef-4a51a6435081', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('99ba4983-ac53-7c97-a6c1-4a55f1854c3b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '130a2cd5-4b13-b361-d3db-4a51a636271a', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('9a5b4381-6a56-a1f0-d701-4a55f1630e39', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', '10fe377e-b1f7-2a6c-100d-4a51a69d0fbd', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('9b397c86-5270-d82f-598d-4a55f11eac38', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'e34b0ca4-e4e4-8106-a5e0-4a51a6195167', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('9cb85fa9-2a3f-5d0f-62e6-4a55f19af83b', 'c5c8fb66-163f-665b-21a8-4a55f0bacec4', 'd7ef0165-dcc8-91cf-47cd-4a51a621bb5d', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('985c1216-88db-efc2-bbf4-4a63ff7fd3e5', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'bfb6cbfc-3111-cf55-d660-4a51a653c3b4', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('98ee4086-30c6-70c0-eec9-4a63ff7309aa', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c82e2a33-5868-33c2-a898-4a51a6efaa19', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9cb9ef8e-5f01-ee7f-f29e-4a63ff614bbc', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c5475644-d4cb-b39d-e60e-4a51a6e46e77', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9d289588-4cd3-41eb-69bd-4a63ff55fd24', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c9ed0c9e-2b09-3abd-e512-4a51a6d2c106', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9deab323-cc44-8e05-ffb1-4a63ff308aaf', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c8feccc9-8e34-a3be-b351-4a51a66f2d67', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9e5d0c5e-5423-21a9-05f5-4a63ff9c360c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c34b8b63-a720-c24d-68e6-4a51a6313505', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9ed729ff-6996-d3a4-cb68-4a63ffe5fc49', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c19c7514-5263-da4e-a480-4a51a66a07ee', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9f54db81-5134-1bdf-22a8-4a63ffc3749b', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7c432b37-ea2a-d7b9-c311-4a51a60790ee', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('9ffb187e-3059-7182-413d-4a63ff05c00f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7f9d1df5-e605-d3fb-4d30-4a51a66ea3e4', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a0778d0b-17f8-70f6-d787-4a63fffb0bf6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7e60506a-fee5-fd92-b9ed-4a51a6292345', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a0f3445a-bb4c-0994-4860-4a63ffc8415d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '8e860689-95f2-0642-ed7c-4a51a6c7e095', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a169e689-5db7-319f-37db-4a63ffe41bf6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '8c24a282-40e3-ad37-cb11-4a51a6e4b0f7', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a1e4c107-e183-8990-544b-4a63ff5d4d89', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7da633be-b46c-d8e7-8307-4a51a629d5b0', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a266e648-2b7a-4274-4735-4a63ff1e879c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7cfc3153-3448-ba6e-249b-4a51a6b5e8b4', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a305d3ec-8067-4248-bbf4-4a63ff98f324', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3cb89bea-e83c-eab2-eb71-4a51a6aa9814', 89, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a3b40ccd-dac1-acb7-90d9-4a63ffb26332', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '40651b52-e8d9-8d6e-a925-4a51a6e560df', -99, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a43ae6b1-da32-3e37-dbfa-4a63ff4edac5', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3f941b68-b1b0-0cac-06b2-4a51a6876d7c', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a5495946-1f50-09ad-04a2-4a63ff22cbcb', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '4a4d87f7-86ee-ddc7-7ffa-4a51a6142558', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a69cf31e-d783-269f-b935-4a63fff40d72', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '43d67b32-e417-dd94-abb0-4a51a649952c', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a7525178-5b08-1a33-75ee-4a63ff4cfdd3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3eb7418e-841c-c40f-c1de-4a51a6e3e253', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a7f01540-a11b-d6c2-832e-4a63ff8d27de', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3de61db6-6c14-94e8-63fd-4a51a6e4e4a4', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a8681f47-b873-d8e5-f345-4a63ff2417f8', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'e0299b25-1315-cece-33b5-4a51a68a1364', 89, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a8e319ef-60ab-7a83-e73c-4a63ff4c878d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'eb98311c-34eb-0885-074d-4a51a6da1181', -99, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a967a280-a0e4-a209-ed27-4a63ff7475d3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'e7255e45-1056-7dfd-9314-4a51a6b628e9', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('a9dceec4-c37c-d71c-e466-4a63ffed853e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'f3593ea1-d472-5c46-e503-4a51a618b72b', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('aa5af467-5da2-f38b-cc7f-4a63ff59a0fa', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'f10083be-3a6c-786c-548e-4a51a625b9d4', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('aacc76d6-6bd2-aa37-8c71-4a63ff564ed2', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'e440466c-c562-8306-6ed5-4a51a607d0e8', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ab58acff-c4a9-ea78-8da8-4a63ffeed1ca', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'e1f46267-fc8b-ab57-4ad2-4a51a65a4738', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('abd63a9b-8ae1-2f3d-c5a9-4a63ffd9a111', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '713d9d56-3b2a-38fa-65b1-4a51a63a0c7c', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ac613d23-cf9a-f77f-ab45-4a63ffe12cfe', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7aa3856e-aa3c-902c-dbc0-4a51a639d838', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('acfcfdb6-061d-17e3-6ea8-4a63ffca2dc0', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '799b290e-b8c5-ffe7-69e8-4a51a6aa018e', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ad7fca5b-81d9-0f1f-aa0a-4a63ff02280d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7e04d084-aaf0-8e82-a427-4a51a63ff627', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ae03641b-1a7a-f143-5cb4-4a63ffcf602e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7c24e1d9-50e2-10b3-cee8-4a51a6ee404e', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ae7f79a5-b8ac-177e-3349-4a63fffe9e1e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '78373e7c-f306-9fdd-ca8c-4a51a634a405', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('aef24ab6-e16a-bfce-102e-4a63ffcd3ed5', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '74901e8a-ab7f-a10a-bc96-4a51a6bd9bf9', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('af6ba586-46a7-3d51-1b2e-4a63ffc147d4', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '703baf02-571f-c91b-8759-4a51a643a3ad', 89, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('aff6ac5f-2b43-7b18-85be-4a63ffc226fc', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '791f0ca7-241b-04b1-69d4-4a51a61a95c1', -99, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b083abb0-2d6c-6b1d-4367-4a63ff110efd', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '774a67ec-7e16-c968-596e-4a51a632d964', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b132ce31-d71b-3268-573f-4a63ff1ac6bf', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7d8c76b0-a06b-3262-be24-4a51a61ce6f5', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b1e28217-e28b-5a50-11cb-4a63ffb8835a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7c033b9d-9678-b3aa-adf0-4a51a6d942ab', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b2842ab0-5fba-0a72-ad94-4a63ffcd9ffd', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '75854284-62a7-371c-6ef3-4a51a6e89155', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b3386aee-3a87-561f-b962-4a63ffc05b55', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '730f751b-c4be-dc7f-d80b-4a51a623e6fa', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b3dc90af-9d1b-780a-f18c-4a63ffaa4abc', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '79838ab3-595d-7f71-1fc9-4a51a6296189', 89, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b47d2e0f-3482-c4b7-0bc0-4a63ff66d5dc', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '911de040-91e8-5220-d3c4-4a51a67e7901', -99, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b542f5b1-a307-09be-6d13-4a63ff4d7a0f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7f3fcede-ebb3-2a8c-7938-4a51a6c3b6df', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b5e983c5-ac76-573b-faab-4a63ff2bec44', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '9385bb70-cbbc-d085-13af-4a51a68b3a5b', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b6914ba4-d6b6-1c3e-88e5-4a63ff00c82a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '92841ab8-e5f9-2c9a-4b79-4a51a6176db7', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b75971db-6bff-a391-4156-4a63fff7d631', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7d6adc18-dcb3-844d-d010-4a51a688120b', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b829570a-adf9-4ac3-8641-4a63fff4bc8d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7bb28ad6-1c0a-77f5-5ae0-4a51a6a7732e', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b8a921e6-f26c-b369-efe1-4a63fff2271f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b140aab9-6c1c-f4c6-3966-4a51a610cd6c', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b92c3681-4d9c-4cff-13cd-4a63ff641c27', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b511c6a1-6dd7-5ccd-d099-4a51a67a2148', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('b9be5dcb-72f8-4cb0-a4bd-4a63ff24ef5b', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b411d1cf-8437-810d-53fd-4a51a6a01a01', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('ba444eec-286a-ce2d-db2c-4a63ff45b03c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b6fa78bb-9aeb-2bba-e54a-4a51a6bcf14c', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('babffca9-2413-1efc-b7ba-4a63ff37e0d3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b5be0bc6-a1d6-3325-920e-4a51a63e43d8', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bb43a8f5-1701-1471-bf5b-4a63ff75e53e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b34da496-561a-e5cb-d60f-4a51a61dcfad', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bbe2e342-75f2-86de-c239-4a63ffe5a95b', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'b26fafc8-55bb-83f2-041b-4a51a6f468c1', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bc6a7490-028e-2c88-3990-4a63ff98ba8c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '62c8b425-fa33-7f3b-9f88-4a51a6feacee', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bcf0a8ed-e8da-0e72-69ea-4a63ffbe20d0', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '66f0207d-76c3-1839-312b-4a51a6ab76ae', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bd6d84f7-f78f-81a4-676a-4a63ffcc4a51', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '65fd6f9a-5c16-1a5a-059e-4a51a6eaf852', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('be3d5ff6-d56a-1795-575a-4a63ffbc7a3f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '68660a6d-3b5e-da12-dceb-4a51a6568ad8', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('becb3a87-260f-295a-7aab-4a63fff282b8', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '67ae51f3-74dc-6961-8283-4a51a672ccc1', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bf78bc34-f31b-4f93-592c-4a63ff71a272', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '64ffe441-ed87-7f72-f19c-4a51a665ea85', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('bfff7b3c-bec8-ef87-bad9-4a63ff9b451d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '63f59bfb-8d48-b9f3-c063-4a51a638165c', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c0b3a0af-151b-2c81-f334-4a63ffe22780', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'd697d5c2-2fa3-d06e-ec92-4a51a6c929f8', -98, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c1ddf2bb-2f7c-790b-fc22-4a63ffccbbf1', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'dc189ba4-2dd9-c5d6-982f-4a51a6a3003b', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c2ad9b73-7934-2762-6cd7-4a63ff6d999d', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'dac13aed-19c7-bdc2-5df7-4a51a6ca36ea', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c38bb246-d291-c59c-ae37-4a63ff9b9de7', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'de14d9ce-f8cb-fc5d-ccee-4a51a6cae9d5', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c463bdee-5da5-d9f5-dad9-4a63ff0f5d32', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'dd2aeedf-825b-18fc-f9dd-4a51a6e8f989', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c4f786c1-f861-ba60-243a-4a63ff1df908', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'd864b77a-d3af-caee-ebbc-4a51a61e1b80', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c593bde8-e2f2-63ae-9afa-4a63ff3681dd', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'd790914e-9e95-cde6-cc7f-4a51a6acf0ba', 0, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c615d73d-90ba-4b4a-aeb0-4a63ff6d6803', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2c60df0c-c138-f78d-3b75-4a51a6534027', 89, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c6e60559-4a62-ac73-160b-4a63ff0b714f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3173349f-0b07-43fb-5354-4a51a69fa818', -99, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c76ad86f-a052-efad-f40d-4a63ff3be5ca', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '30385c32-d223-b568-7767-4a51a6424311', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c7ec9645-7223-c4a9-d9cd-4a63ffb554b6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '35652b1a-79b9-bd73-53de-4a51a65fd85f', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c87e45c7-5d7d-fd16-c358-4a63ffb0b617', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '332fb287-a3ca-17d2-13fa-4a51a6742888', 90, '2010-11-30 06:37:47', 0);
INSERT INTO `acl_roles_actions` VALUES ('c92611dc-7dab-5f66-f1c5-4a63fffe2793', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2eee8c89-0162-21e4-4ab3-4a51a646444c', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('c9a847b8-d063-ad17-8f5f-4a63ff138acf', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2ddaa1bf-b126-82ab-5c0f-4a51a6ddf433', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ca2699ce-73e0-6f3a-36eb-4a63ff6d1671', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '90cadcfa-3365-9cf3-3eca-4a51a6b3742b', 89, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('caad8864-e017-82ce-9eac-4a63ff51cb27', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '95dc1b23-98bd-c1b0-5296-4a51a6c74f82', -99, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cb548509-f75b-1bc6-e323-4a63ff4cfe4a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '94d1acde-9bbc-9f68-0ac5-4a51a62f2ac2', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cbdc92fe-8511-d2be-ab7c-4a63ff3568f3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '99c2b29e-714e-7292-9140-4a51a6163881', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cc7f2be1-9a0d-9ede-7341-4a63ff39ebd1', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '96995755-c0aa-539f-4a4f-4a51a6ae9ec2', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cd028fb6-c806-6804-25d8-4a63ff29540c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '9341eba7-ed7a-97f6-118e-4a51a6b14c64', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cd7fae6a-e7ec-0705-9eb0-4a63ff15a85f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '91f304c4-2f52-d657-41c4-4a51a60a61f9', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ce0eae8c-eec5-9956-dc9e-4a63ffd7d86c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c07f8faa-fb44-bc82-049f-4a51a6de4eee', 89, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cebfafe8-faaf-3d8b-29a8-4a63ffe0f889', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c3903fd6-c120-f120-e2bd-4a51a662c208', -99, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cf4964e9-04d4-7306-6cdc-4a63ff27b1e6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c2d2e6ee-70cb-f4fd-adb7-4a51a611c38f', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('cfecccf8-0140-1506-b6a3-4a63ff49bd75', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c50ea9a9-afd6-9169-d2c2-4a51a630c54a', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d07cd43e-037b-ca36-26a1-4a63ff3b17bd', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c446065d-b11a-66ea-a039-4a51a6fb4640', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d1213b9f-689d-4d76-898a-4a63ff064b55', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c21bd49d-616a-1ae8-6c94-4a51a6a8a5d6', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d1c7ef03-21ca-f87d-5169-4a63ff507caa', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c151fb25-0a6f-2238-972f-4a51a69547c6', 90, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d24eaf76-855a-3d3b-f748-4a63ff7a3848', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '1e4e5d0b-5e0a-a519-22c0-4a51a6d912ef', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d2d4ee07-948f-af66-7c86-4a63ff2ce7ef', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '24cede5a-07b5-0b39-1036-4a51a6fa4865', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d351fb8c-afbb-a4bd-1929-4a63ffdb73eb', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2383c89d-4ce6-07c6-1f9c-4a51a615c0b9', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d3d5cf10-52d9-b830-bf97-4a63ffcc6ef0', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2781a8de-481c-389c-b1d2-4a51a63cfb4c', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d454019e-c20b-5c1b-b65b-4a63ffd23b6f', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2616637e-53e2-e5f0-c8a4-4a51a61e762a', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d4d59af2-51e8-b1dc-8dd5-4a63ffed8278', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '2144c457-cf54-b310-0f12-4a51a6ad74c7', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d55cd560-e875-c553-feb3-4a63ff38f1ff', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '1fcafb8c-34ef-f572-90fc-4a51a635c87f', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d5da8365-20ba-57d0-9b43-4a63ff6757d1', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '51eebf40-cef0-f526-d794-4a51a688c477', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d67ba83c-f349-028e-5f94-4a63ff32dfbb', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '5598e9d7-d707-f290-ed83-4a51a64d65bb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d70eb336-58cd-acde-16dd-4a63ff801198', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '5499014b-b125-a621-31a5-4a51a62f0557', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d79130ac-5089-aecc-ac17-4a63ff0766f6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '58e6c267-60ad-f684-e3a0-4a51a621071a', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d83f62d0-3934-2ac5-825f-4a63ffa04430', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '56ae374e-924f-4200-355b-4a51a6d997da', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d8c6580a-032c-e9ff-9b7d-4a63ff65de97', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '53b9172c-9627-ead3-7a2f-4a51a62de353', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('d964cba2-b514-a15d-9f5c-4a63ff821b3e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '52ebfe9a-c4d4-80e0-d146-4a51a6f6213e', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('da15f327-3c10-ceec-6c11-4a63ff2cd3d3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '79641b81-e952-a709-587c-4a51a6e8a2ea', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('db185a5e-8a68-0888-ba5d-4a63fff38c80', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7da77e2d-e953-a117-6f65-4a51a6476829', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('dbbcb69d-d5a3-b0b7-88be-4a63ff35be93', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7ccd156b-ed09-5cea-0451-4a51a623af6b', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('dc46a3f2-f3c7-9bcb-80d5-4a63ff7a740a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7f34ca09-5cff-320f-d692-4a51a6f19226', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('dcd8bf1d-6a14-9d44-b809-4a63ff3661c8', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7e58e565-df43-d5b5-03f7-4a51a6220c54', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('dd985de9-8491-a999-d8b1-4a63ff8a288e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7c1f3fc8-9615-ac5f-0719-4a51a64b92c0', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('de2bdb87-7cc6-b051-444d-4a63ff97136e', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '7b11214b-067b-84c2-1ff8-4a51a6f97cfb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ded66266-e202-cf49-e4ec-4a63ff73f5b3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '31adbb19-69be-08cd-b500-4a51a6474ad9', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('df7c1c55-dd0e-30cf-6fa9-4a63ff01fbf9', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '373077c0-2fad-2140-2a95-4a51a63555be', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e030a83f-76a8-6339-3656-4a63ffb6e363', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '358d55e6-c77a-8d7b-0f0a-4a51a6c9729f', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e0ca05f7-8b96-6ae8-2d33-4a63ff069058', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '3aff187b-6266-fea5-0a7d-4a51a61cf2c8', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e16a8bbc-ec74-8039-481c-4a63ff470bf6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '394c07ac-ec44-9753-aa8c-4a51a6ecb767', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e204052e-7918-c6ef-cc6f-4a63ffa4a2e7', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '34320a32-e8f1-c97a-97cc-4a51a6ae08a1', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e2a7cc8f-431d-0da4-e470-4a63ffd782b6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '32f6b592-ca1b-4bdc-16c9-4a51a63832f1', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e3429941-775f-0f34-eb06-4a63ffade4e3', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '4feb491e-235c-c47a-6833-4a51a6b1225e', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e3e9c16b-a231-22d6-9cd5-4a63fff19d17', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '58908154-957a-d4c5-8f84-4a51a60094eb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e4d741ab-e979-5e08-6913-4a63ffc78879', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '55033fc0-673c-c62b-891b-4a51a6a23fb8', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e568ded9-b70f-077b-3b01-4a63ff1506b9', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '5c17c7e6-f07d-f777-0062-4a51a651a623', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e60320ec-b248-9d5f-0ed3-4a63ff486f8a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '5a85c2db-eec0-3f38-394d-4a51a6f7f872', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e68b5f0f-f60e-e59e-096c-4a63ffb47713', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '531182c5-bda6-41d7-3963-4a51a64d3f77', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('e99fe6f6-b062-acbd-3f3a-4a63ff62fd63', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '5150f12d-9847-9650-f394-4a51a6fa47cb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ea2cc6eb-40cc-419a-53b9-4a63ff3132df', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c2a9cfad-8f45-ddc6-c801-4a51a67a5b84', -98, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('eaeec243-a20a-d908-f2f7-4a63ff900446', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c9d8c958-692c-f02f-8367-4a51a62ef913', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ebd2b0c8-7c98-4d64-c398-4a63ff858923', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c910c459-7609-7c25-8d6b-4a51a643ccbb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ec718066-3b7a-b07e-aa54-4a63ff395ff8', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'cb9b50bb-7e3f-6500-4698-4a51a6d9040a', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ed224b05-9392-58b1-7698-4a63ff59d4db', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'caa6c9c8-d720-820f-79e4-4a51a6be7670', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('edbdb340-5b23-45b0-9126-4a63fff09bbc', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c829691f-cf35-9c18-285e-4a51a63adebb', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('ee4c47bf-10c9-219d-511f-4a63ff0e88f0', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'c75e29cd-c14c-0e8b-169b-4a51a64fa9a9', 0, '2010-11-30 06:37:48', 0);
INSERT INTO `acl_roles_actions` VALUES ('eee5c7ee-31f5-d54a-c7da-4a63ffd55982', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'cbf806d8-8681-eb91-4e9c-4a51a666b76a', -98, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('ef78f4d8-8cda-943c-1e65-4a63ff1ab6b6', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '101a24bc-7e11-6ea1-bbd8-4a51a68be7f5', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('f001155a-1461-270b-26ab-4a63ffd1be3a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'f2390a83-f868-04bf-beef-4a51a6435081', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('f09788f2-6dfc-b127-ee06-4a63ff4368a5', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '130a2cd5-4b13-b361-d3db-4a51a636271a', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('f11f6e13-9773-870b-ea7a-4a63ff8e1a54', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', '10fe377e-b1f7-2a6c-100d-4a51a69d0fbd', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('f1b8b691-7f49-cab5-3f31-4a63ff0f469c', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'e34b0ca4-e4e4-8106-a5e0-4a51a6195167', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('f2457bfb-9301-5e3d-d4b2-4a63ff64302a', '1c9222ea-b137-3220-b9e0-4a63fe0b9714', 'd7ef0165-dcc8-91cf-47cd-4a51a621bb5d', 0, '2010-07-27 07:50:29', 1);
INSERT INTO `acl_roles_actions` VALUES ('76c65506-f541-f366-4f2f-4ccff7544518', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'bfb6cbfc-3111-cf55-d660-4a51a653c3b4', -98, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('779996e5-e700-da43-355e-4ccff7782f00', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c82e2a33-5868-33c2-a898-4a51a6efaa19', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('786d75ac-8d30-845e-a019-4ccff7535560', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c5475644-d4cb-b39d-e60e-4a51a6e46e77', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('79592d5a-991f-6eed-5d57-4ccff7fc2b96', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c9ed0c9e-2b09-3abd-e512-4a51a6d2c106', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('79fd7c40-61d6-3c19-7f35-4ccff74942e4', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c8feccc9-8e34-a3be-b351-4a51a66f2d67', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7a975215-f5c2-f7cb-1eee-4ccff79e68bf', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c34b8b63-a720-c24d-68e6-4a51a6313505', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7b4126f4-9642-9432-dc6a-4ccff7bcc838', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c19c7514-5263-da4e-a480-4a51a66a07ee', -99, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7bf2381e-860e-e051-1559-4ccff72848b3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7c432b37-ea2a-d7b9-c311-4a51a60790ee', -98, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7c9f717e-a10a-8498-2183-4ccff7e067ff', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7f9d1df5-e605-d3fb-4d30-4a51a66ea3e4', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7d3afdbb-78e0-a6ea-acec-4ccff759589c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7e60506a-fee5-fd92-b9ed-4a51a6292345', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7de7326a-019a-0830-0f86-4ccff7434b0d', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '8e860689-95f2-0642-ed7c-4a51a6c7e095', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7eb2cbb4-ee54-ee09-cb6a-4ccff7a11d07', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '8c24a282-40e3-ad37-cb11-4a51a6e4b0f7', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('7f657797-ed34-bd5c-a28e-4ccff7e80d8b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7da633be-b46c-d8e7-8307-4a51a629d5b0', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('802c3bf1-ce29-079a-017b-4ccff7eb0f8a', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7cfc3153-3448-ba6e-249b-4a51a6b5e8b4', 0, '2010-12-28 12:42:56', 0);
INSERT INTO `acl_roles_actions` VALUES ('80e97d91-e04c-0234-290a-4ccff701026a', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3cb89bea-e83c-eab2-eb71-4a51a6aa9814', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('818d05e4-fac8-4b3c-63cb-4ccff767a9e4', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '40651b52-e8d9-8d6e-a925-4a51a6e560df', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('823e60b2-0868-09a2-ae58-4ccff7aa14d3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3f941b68-b1b0-0cac-06b2-4a51a6876d7c', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('83020406-ae3d-50f5-1b46-4ccff7f5247c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '4a4d87f7-86ee-ddc7-7ffa-4a51a6142558', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('83af5af5-db44-9624-522c-4ccff77291c0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '43d67b32-e417-dd94-abb0-4a51a649952c', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('84610aa0-0c84-88cb-3d1e-4ccff766cc81', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3eb7418e-841c-c40f-c1de-4a51a6e3e253', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('852573ee-5841-16fb-798c-4ccff76670cd', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3de61db6-6c14-94e8-63fd-4a51a6e4e4a4', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('85e75d8b-c992-d32f-f72d-4ccff7fa1156', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'e0299b25-1315-cece-33b5-4a51a68a1364', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('86a9d1c7-0b0c-5bf1-4344-4ccff783b5aa', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'eb98311c-34eb-0885-074d-4a51a6da1181', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8756293b-4798-7b4b-188b-4ccff752b694', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'e7255e45-1056-7dfd-9314-4a51a6b628e9', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('880f0f7b-2708-1151-6af8-4ccff7c80d79', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'f3593ea1-d472-5c46-e503-4a51a618b72b', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('88b9b2fe-97ee-577b-56a1-4ccff7861774', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'f10083be-3a6c-786c-548e-4a51a625b9d4', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('89613ec4-29c9-c297-a659-4ccff7349e1e', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'e440466c-c562-8306-6ed5-4a51a607d0e8', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8a2ad9ce-7583-915c-f149-4ccff7c814d3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'e1f46267-fc8b-ab57-4ad2-4a51a65a4738', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8b1797ff-e314-d567-a87e-4ccff766d1b8', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '713d9d56-3b2a-38fa-65b1-4a51a63a0c7c', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8be82f0b-a443-60a5-b695-4ccff759821c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7aa3856e-aa3c-902c-dbc0-4a51a639d838', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8ca91a68-be9f-b941-3a1f-4ccff77e90e9', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '799b290e-b8c5-ffe7-69e8-4a51a6aa018e', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8d5b55a6-fbaf-bc41-e025-4ccff73b5172', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7e04d084-aaf0-8e82-a427-4a51a63ff627', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8e3e4bae-a214-23ad-6c54-4ccff72074d8', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7c24e1d9-50e2-10b3-cee8-4a51a6ee404e', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8f0509ab-c216-2b2a-1730-4ccff7d771f0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '78373e7c-f306-9fdd-ca8c-4a51a634a405', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('8fc7a8a3-2338-e0ca-6fc2-4ccff721dc98', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '74901e8a-ab7f-a10a-bc96-4a51a6bd9bf9', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9082a1ef-4f18-309d-fdb1-4ccff7544209', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '703baf02-571f-c91b-8759-4a51a643a3ad', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9150e4a9-06f3-f559-bc95-4ccff7821f21', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '791f0ca7-241b-04b1-69d4-4a51a61a95c1', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9214c92d-0ffd-bcf7-adac-4ccff7c25abf', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '774a67ec-7e16-c968-596e-4a51a632d964', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('92e559ba-ae05-6f1a-6ed4-4ccff7d799f6', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7d8c76b0-a06b-3262-be24-4a51a61ce6f5', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('93a270a0-07bb-f1fe-fef4-4ccff71d70da', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7c033b9d-9678-b3aa-adf0-4a51a6d942ab', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9471c549-6461-ccbe-e988-4ccff7668fd5', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '75854284-62a7-371c-6ef3-4a51a6e89155', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9534ce09-5866-deaf-b7ea-4ccff77ef38b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '730f751b-c4be-dc7f-d80b-4a51a623e6fa', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('96029bb3-f609-476a-3923-4ccff7324154', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '79838ab3-595d-7f71-1fc9-4a51a6296189', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('96c8e6b0-e49f-5774-8437-4ccff762f701', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '911de040-91e8-5220-d3c4-4a51a67e7901', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('977189fe-8d0f-9c22-2b54-4ccff78833c9', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7f3fcede-ebb3-2a8c-7938-4a51a6c3b6df', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('981dc72a-9b44-99d2-cbad-4ccff79c650d', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '9385bb70-cbbc-d085-13af-4a51a68b3a5b', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('98bea319-efe1-36d0-d45b-4ccff7d3d9f0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '92841ab8-e5f9-2c9a-4b79-4a51a6176db7', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('999abc4c-e4c0-2cac-0a9d-4ccff78b2a0a', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7d6adc18-dcb3-844d-d010-4a51a688120b', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9a736342-df89-6581-1020-4ccff70a8f34', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7bb28ad6-1c0a-77f5-5ae0-4a51a6a7732e', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9b637da9-18b8-3e51-869e-4ccff75f18ab', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b140aab9-6c1c-f4c6-3966-4a51a610cd6c', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9c30bb56-cd02-5383-855e-4ccff7e2addd', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b511c6a1-6dd7-5ccd-d099-4a51a67a2148', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9cddfdf1-ea67-6824-217f-4ccff76d5580', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b411d1cf-8437-810d-53fd-4a51a6a01a01', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9da59593-3b25-f218-4741-4ccff75f081b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b6fa78bb-9aeb-2bba-e54a-4a51a6bcf14c', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9e83c74b-5682-59d7-b1aa-4ccff7878263', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b5be0bc6-a1d6-3325-920e-4a51a63e43d8', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('9f89965a-97a7-fd10-bcd6-4ccff7c29daa', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b34da496-561a-e5cb-d60f-4a51a61dcfad', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a04a97e7-758e-efcf-6253-4ccff7a7ff98', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'b26fafc8-55bb-83f2-041b-4a51a6f468c1', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a1121e15-3b9d-09b4-246a-4ccff7ca94c6', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '62c8b425-fa33-7f3b-9f88-4a51a6feacee', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a1d41185-eb9b-1f4e-9522-4ccff71e6740', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '66f0207d-76c3-1839-312b-4a51a6ab76ae', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a29909b5-62f0-5f3d-83a5-4ccff7ab000b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '65fd6f9a-5c16-1a5a-059e-4a51a6eaf852', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a36a3134-89fe-fd26-88ab-4ccff7a84bb1', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '68660a6d-3b5e-da12-dceb-4a51a6568ad8', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a416ed67-ee52-e322-7564-4ccff74a1eab', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '67ae51f3-74dc-6961-8283-4a51a672ccc1', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a4da4985-1fb1-2619-f807-4ccff7106aa0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '64ffe441-ed87-7f72-f19c-4a51a665ea85', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a59ba095-f75b-1c49-1771-4ccff70d10a2', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '63f59bfb-8d48-b9f3-c063-4a51a638165c', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a67960ce-c2af-66e3-9a49-4ccff7b85563', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'd697d5c2-2fa3-d06e-ec92-4a51a6c929f8', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a72244d5-d224-3edf-68a7-4ccff7d314d1', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'dc189ba4-2dd9-c5d6-982f-4a51a6a3003b', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a7fd15ff-e744-cd7f-93a0-4ccff7524fe9', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'dac13aed-19c7-bdc2-5df7-4a51a6ca36ea', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a8c5e572-f5c7-ae07-102d-4ccff701f9c8', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'de14d9ce-f8cb-fc5d-ccee-4a51a6cae9d5', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('a996134e-a8c6-6f4d-aa1b-4ccff725a669', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'dd2aeedf-825b-18fc-f9dd-4a51a6e8f989', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('aa6e1801-6c54-287d-a2fd-4ccff7b3bc74', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'd864b77a-d3af-caee-ebbc-4a51a61e1b80', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('ab28e39d-5626-57ef-c035-4ccff7a34287', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'd790914e-9e95-cde6-cc7f-4a51a6acf0ba', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('abe8d654-bde4-ad74-58bd-4ccff7f97799', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2c60df0c-c138-f78d-3b75-4a51a6534027', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('acdb044e-4c60-608c-4c8b-4ccff741620e', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3173349f-0b07-43fb-5354-4a51a69fa818', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('adb57f9e-bbc3-df3a-6b24-4ccff7928fe0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '30385c32-d223-b568-7767-4a51a6424311', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('ae600a58-61f1-4050-a9dd-4ccff7490021', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '35652b1a-79b9-bd73-53de-4a51a65fd85f', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('af33b214-757d-c95c-034b-4ccff7e5b542', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '332fb287-a3ca-17d2-13fa-4a51a6742888', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('afe3e5f7-6b0d-fb20-67cd-4ccff78739c3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2eee8c89-0162-21e4-4ab3-4a51a646444c', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b0a21ee2-4c0b-1618-6d74-4ccff7e49758', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2ddaa1bf-b126-82ab-5c0f-4a51a6ddf433', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b16695b0-8836-f870-9c81-4ccff7b46248', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '90cadcfa-3365-9cf3-3eca-4a51a6b3742b', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b2291b09-60e3-f9cb-00f5-4ccff7e5a41c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '95dc1b23-98bd-c1b0-5296-4a51a6c74f82', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b2e6b698-372d-37cb-2e0d-4ccff714b22c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '94d1acde-9bbc-9f68-0ac5-4a51a62f2ac2', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b42ca812-09e9-0242-e57c-4ccff7f740d6', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '99c2b29e-714e-7292-9140-4a51a6163881', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b4e3dc1b-0f85-1a11-023a-4ccff700c6b3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '96995755-c0aa-539f-4a4f-4a51a6ae9ec2', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b5a35a0b-0933-4cc4-af63-4ccff7e0397f', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '9341eba7-ed7a-97f6-118e-4a51a6b14c64', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b650084d-bc37-30e3-ad2f-4ccff78d68bf', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '91f304c4-2f52-d657-41c4-4a51a60a61f9', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b731198f-23a2-f255-ae2c-4ccff769b30a', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c07f8faa-fb44-bc82-049f-4a51a6de4eee', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b818b224-f0af-0937-b7b9-4ccff7ccd919', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c3903fd6-c120-f120-e2bd-4a51a662c208', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b8f7ae3c-1de5-0988-fae2-4ccff756a890', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c2d2e6ee-70cb-f4fd-adb7-4a51a611c38f', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('b9b8b5b7-1667-4a0d-2b67-4ccff704d932', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c50ea9a9-afd6-9169-d2c2-4a51a630c54a', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('ba78c831-254c-5260-aae7-4ccff765242f', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c446065d-b11a-66ea-a039-4a51a6fb4640', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bb338884-bd9e-a086-30ef-4ccff7a45287', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c21bd49d-616a-1ae8-6c94-4a51a6a8a5d6', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bc20c2d4-a289-7785-205d-4ccff7eb8d66', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c151fb25-0a6f-2238-972f-4a51a69547c6', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bce25fcb-ff89-9f7a-0f76-4ccff7f30702', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '1e4e5d0b-5e0a-a519-22c0-4a51a6d912ef', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bdb68e1e-770d-ea33-b0ac-4ccff7452e8b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '24cede5a-07b5-0b39-1036-4a51a6fa4865', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bedde814-70c0-6648-00fa-4ccff7fda85c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2383c89d-4ce6-07c6-1f9c-4a51a615c0b9', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('bfb055f0-929f-09dc-bd01-4ccff75b0286', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2781a8de-481c-389c-b1d2-4a51a63cfb4c', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c0901266-91d4-0e6a-e513-4ccff7ea1dd9', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2616637e-53e2-e5f0-c8a4-4a51a61e762a', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c1694ad9-62f3-a68a-692b-4ccff7217232', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '2144c457-cf54-b310-0f12-4a51a6ad74c7', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c23abf19-f7c6-08ef-236e-4ccff781bf5b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '1fcafb8c-34ef-f572-90fc-4a51a635c87f', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c2e35c16-1b7b-923f-4424-4ccff7ba072f', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '51eebf40-cef0-f526-d794-4a51a688c477', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c3b108f2-b66f-2ed4-fdec-4ccff70f030b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '5598e9d7-d707-f290-ed83-4a51a64d65bb', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c46e7d0e-ca19-6cef-802b-4ccff720b78d', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '5499014b-b125-a621-31a5-4a51a62f0557', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c536ed35-88a7-40b0-1f3d-4ccff7e6065a', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '58e6c267-60ad-f684-e3a0-4a51a621071a', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c60f31dc-4348-8c0f-c801-4ccff7ec761c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '56ae374e-924f-4200-355b-4a51a6d997da', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c6c30072-568c-6cfb-5c4c-4ccff7ce3e32', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '53b9172c-9627-ead3-7a2f-4a51a62de353', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c78382ff-321e-e802-bd0f-4ccff7741cde', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '52ebfe9a-c4d4-80e0-d146-4a51a6f6213e', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c86cfd54-841a-20a1-25d5-4ccff71a9c81', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '79641b81-e952-a709-587c-4a51a6e8a2ea', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c93039b5-b9c5-723c-24a8-4ccff725271c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7da77e2d-e953-a117-6f65-4a51a6476829', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('c9e227ed-6c47-bc45-e9eb-4ccff75b0ba6', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7ccd156b-ed09-5cea-0451-4a51a623af6b', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cac70e1e-419e-2878-1203-4ccff7c94165', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7f34ca09-5cff-320f-d692-4a51a6f19226', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cb8249ce-d828-554c-c3a7-4ccff7c14439', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7e58e565-df43-d5b5-03f7-4a51a6220c54', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cc3e23f0-a5c6-0de3-1aa4-4ccff7d47fb3', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7c1f3fc8-9615-ac5f-0719-4a51a64b92c0', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cd1b670d-3add-8bde-c42b-4ccff770ef61', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '7b11214b-067b-84c2-1ff8-4a51a6f97cfb', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cdd9b61d-fcf4-2fbe-529a-4ccff783926c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '31adbb19-69be-08cd-b500-4a51a6474ad9', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('ce9017c2-3a6c-e142-b6d7-4ccff79f9de5', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '373077c0-2fad-2140-2a95-4a51a63555be', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('cfb65fb4-b049-825d-45a4-4ccff7690086', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '358d55e6-c77a-8d7b-0f0a-4a51a6c9729f', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d07c5b9f-ddc8-3601-bc95-4ccff7776563', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '3aff187b-6266-fea5-0a7d-4a51a61cf2c8', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d12f4168-3a4d-5704-b6ae-4ccff789f39d', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '394c07ac-ec44-9753-aa8c-4a51a6ecb767', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d1f88e1d-d41d-c5ed-72ac-4ccff7e09152', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '34320a32-e8f1-c97a-97cc-4a51a6ae08a1', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d2b4992e-65ae-a099-ca74-4ccff7e61034', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '32f6b592-ca1b-4bdc-16c9-4a51a63832f1', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d3617d2c-b49d-b70e-4b9e-4ccff7464fff', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '4feb491e-235c-c47a-6833-4a51a6b1225e', -98, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d4244761-a9c7-40d8-e284-4ccff781dbdd', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '58908154-957a-d4c5-8f84-4a51a60094eb', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d4e0daac-4c87-a2f4-81e2-4ccff77830cb', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '55033fc0-673c-c62b-891b-4a51a6a23fb8', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d59a9935-ce86-a26e-050e-4ccff7d00543', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '5c17c7e6-f07d-f777-0062-4a51a651a623', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d670468e-26bd-4497-6e28-4ccff769ef7b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '5a85c2db-eec0-3f38-394d-4a51a6f7f872', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d73238d8-b9fb-b80d-450a-4ccff78d47f0', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '531182c5-bda6-41d7-3963-4a51a64d3f77', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d7e997a1-3e3b-57d6-c629-4ccff78b1486', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', '5150f12d-9847-9650-f394-4a51a6fa47cb', 0, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d8c6e682-c548-5b9a-ceaa-4ccff77c7cf4', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c2a9cfad-8f45-ddc6-c801-4a51a67a5b84', 89, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('d9926eb6-4a8a-5f1a-1344-4ccff77a9e5c', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c9d8c958-692c-f02f-8367-4a51a62ef913', -99, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('da525615-b77a-25f4-bcfd-4ccff7fe4f96', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c910c459-7609-7c25-8d6b-4a51a643ccbb', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('db126a7a-1237-f339-6120-4ccff7a103f6', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'cb9b50bb-7e3f-6500-4698-4a51a6d9040a', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('dbe7b197-93e8-7781-37c4-4ccff7290371', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'caa6c9c8-d720-820f-79e4-4a51a6be7670', 90, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('dc99ec26-05d3-8115-3cd1-4ccff7a17f99', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c829691f-cf35-9c18-285e-4a51a63adebb', 75, '2010-12-28 12:42:57', 0);
INSERT INTO `acl_roles_actions` VALUES ('dd5b4fd3-aae6-1cf4-ff26-4ccff70d139b', '7c60dbc8-bf00-02e0-5cab-4ccff5e65b4c', 'c75e29cd-c14c-0e8b-169b-4a51a64fa9a9', 75, '2010-12-28 12:42:57', 0);

-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Mar 22, 2011 at 11:21 AM
-- Server version: 5.0.27
-- PHP Version: 5.1.6
-- 
-- Database: `sugarcrm`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `acl_actions`
-- 

DROP TABLE IF EXISTS `acl_actions`;
CREATE TABLE `acl_actions` (
  `id` char(36) NOT NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `modified_user_id` char(36) default NULL,
  `created_by` char(36) default NULL,
  `name` varchar(150) default NULL,
  `category` varchar(100) default NULL,
  `acltype` varchar(100) default NULL,
  `aclaccess` int(3) default NULL,
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_aclaction_id_del` (`id`,`deleted`),
  KEY `idx_category_name` (`category`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `acl_actions`
-- 

INSERT INTO `acl_actions` VALUES ('2c60df0c-c138-f78d-3b75-4a51a6534027', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'access', 'Leads', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('2ddaa1bf-b126-82ab-5c0f-4a51a6ddf433', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'view', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('2eee8c89-0162-21e4-4ab3-4a51a646444c', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'list', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('30385c32-d223-b568-7767-4a51a6424311', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'edit', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('3173349f-0b07-43fb-5354-4a51a69fa818', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'delete', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('332fb287-a3ca-17d2-13fa-4a51a6742888', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'import', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('35652b1a-79b9-bd73-53de-4a51a65fd85f', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'export', 'Leads', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('703baf02-571f-c91b-8759-4a51a643a3ad', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'access', 'Contacts', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('730f751b-c4be-dc7f-d80b-4a51a623e6fa', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'view', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('75854284-62a7-371c-6ef3-4a51a6e89155', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'list', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('774a67ec-7e16-c968-596e-4a51a632d964', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'edit', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('791f0ca7-241b-04b1-69d4-4a51a61a95c1', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'delete', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7c033b9d-9678-b3aa-adf0-4a51a6d942ab', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'import', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7d8c76b0-a06b-3262-be24-4a51a61ce6f5', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'export', 'Contacts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('bfb6cbfc-3111-cf55-d660-4a51a653c3b4', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'access', 'Accounts', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('c19c7514-5263-da4e-a480-4a51a66a07ee', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'view', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c34b8b63-a720-c24d-68e6-4a51a6313505', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'list', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c5475644-d4cb-b39d-e60e-4a51a6e46e77', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'edit', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c82e2a33-5868-33c2-a898-4a51a6efaa19', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'delete', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c8feccc9-8e34-a3be-b351-4a51a66f2d67', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'import', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c9ed0c9e-2b09-3abd-e512-4a51a6d2c106', '2009-07-06 07:24:13', '2009-07-06 07:24:13', '1', NULL, 'export', 'Accounts', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('1e4e5d0b-5e0a-a519-22c0-4a51a6d912ef', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'access', 'Opportunities', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('1fcafb8c-34ef-f572-90fc-4a51a635c87f', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'view', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('2144c457-cf54-b310-0f12-4a51a6ad74c7', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'list', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('2383c89d-4ce6-07c6-1f9c-4a51a615c0b9', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'edit', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('24cede5a-07b5-0b39-1036-4a51a6fa4865', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'delete', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('2616637e-53e2-e5f0-c8a4-4a51a61e762a', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'import', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('2781a8de-481c-389c-b1d2-4a51a63cfb4c', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'export', 'Opportunities', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('713d9d56-3b2a-38fa-65b1-4a51a63a0c7c', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'access', 'Cases', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('74901e8a-ab7f-a10a-bc96-4a51a6bd9bf9', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'view', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('78373e7c-f306-9fdd-ca8c-4a51a634a405', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'list', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('799b290e-b8c5-ffe7-69e8-4a51a6aa018e', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'edit', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7aa3856e-aa3c-902c-dbc0-4a51a639d838', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'delete', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7c24e1d9-50e2-10b3-cee8-4a51a6ee404e', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'import', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7e04d084-aaf0-8e82-a427-4a51a63ff627', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'export', 'Cases', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c07f8faa-fb44-bc82-049f-4a51a6de4eee', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'access', 'Notes', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('c151fb25-0a6f-2238-972f-4a51a69547c6', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'view', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c21bd49d-616a-1ae8-6c94-4a51a6a8a5d6', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'list', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c2d2e6ee-70cb-f4fd-adb7-4a51a611c38f', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'edit', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c3903fd6-c120-f120-e2bd-4a51a662c208', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'delete', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c446065d-b11a-66ea-a039-4a51a6fb4640', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'import', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c50ea9a9-afd6-9169-d2c2-4a51a630c54a', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'export', 'Notes', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('d697d5c2-2fa3-d06e-ec92-4a51a6c929f8', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'access', 'EmailTemplates', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('d790914e-9e95-cde6-cc7f-4a51a6acf0ba', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'view', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('d864b77a-d3af-caee-ebbc-4a51a61e1b80', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'list', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('dac13aed-19c7-bdc2-5df7-4a51a6ca36ea', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'edit', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('dc189ba4-2dd9-c5d6-982f-4a51a6a3003b', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'delete', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('dd2aeedf-825b-18fc-f9dd-4a51a6e8f989', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'import', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('de14d9ce-f8cb-fc5d-ccee-4a51a6cae9d5', '2009-07-06 07:24:14', '2009-07-06 07:24:14', '1', NULL, 'export', 'EmailTemplates', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('3cb89bea-e83c-eab2-eb71-4a51a6aa9814', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'access', 'Calls', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('3de61db6-6c14-94e8-63fd-4a51a6e4e4a4', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'view', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('3eb7418e-841c-c40f-c1de-4a51a6e3e253', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'list', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('3f941b68-b1b0-0cac-06b2-4a51a6876d7c', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'edit', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('40651b52-e8d9-8d6e-a925-4a51a6e560df', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'delete', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('43d67b32-e417-dd94-abb0-4a51a649952c', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'import', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('4a4d87f7-86ee-ddc7-7ffa-4a51a6142558', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'export', 'Calls', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('62c8b425-fa33-7f3b-9f88-4a51a6feacee', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'access', 'Emails', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('63f59bfb-8d48-b9f3-c063-4a51a638165c', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'view', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('64ffe441-ed87-7f72-f19c-4a51a665ea85', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'list', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('65fd6f9a-5c16-1a5a-059e-4a51a6eaf852', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'edit', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('66f0207d-76c3-1839-312b-4a51a6ab76ae', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'delete', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('67ae51f3-74dc-6961-8283-4a51a672ccc1', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'import', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('68660a6d-3b5e-da12-dceb-4a51a6568ad8', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'export', 'Emails', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('90cadcfa-3365-9cf3-3eca-4a51a6b3742b', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'access', 'Meetings', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('91f304c4-2f52-d657-41c4-4a51a60a61f9', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'view', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('9341eba7-ed7a-97f6-118e-4a51a6b14c64', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'list', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('94d1acde-9bbc-9f68-0ac5-4a51a62f2ac2', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'edit', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('95dc1b23-98bd-c1b0-5296-4a51a6c74f82', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'delete', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('96995755-c0aa-539f-4a4f-4a51a6ae9ec2', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'import', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('99c2b29e-714e-7292-9140-4a51a6163881', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'export', 'Meetings', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c2a9cfad-8f45-ddc6-c801-4a51a67a5b84', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'access', 'Tasks', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('c75e29cd-c14c-0e8b-169b-4a51a64fa9a9', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'view', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c829691f-cf35-9c18-285e-4a51a63adebb', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'list', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c910c459-7609-7c25-8d6b-4a51a643ccbb', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'edit', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('c9d8c958-692c-f02f-8367-4a51a62ef913', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'delete', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('caa6c9c8-d720-820f-79e4-4a51a6be7670', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'import', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('cb9b50bb-7e3f-6500-4698-4a51a6d9040a', '2009-07-06 07:24:15', '2009-07-06 07:24:15', '1', NULL, 'export', 'Tasks', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('cbf806d8-8681-eb91-4e9c-4a51a666b76a', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'access', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('d7ef0165-dcc8-91cf-47cd-4a51a621bb5d', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'view', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('e34b0ca4-e4e4-8106-a5e0-4a51a6195167', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'list', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('f2390a83-f868-04bf-beef-4a51a6435081', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'edit', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('101a24bc-7e11-6ea1-bbd8-4a51a68be7f5', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'delete', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('10fe377e-b1f7-2a6c-100d-4a51a69d0fbd', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'import', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('130a2cd5-4b13-b361-d3db-4a51a636271a', '2009-07-06 07:24:16', '2010-07-27 07:50:29', '1', NULL, 'export', 'Trackers', 'Tracker', -99, 1);
INSERT INTO `acl_actions` VALUES ('7c432b37-ea2a-d7b9-c311-4a51a60790ee', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'access', 'Bugs', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('7cfc3153-3448-ba6e-249b-4a51a6b5e8b4', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'view', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7da633be-b46c-d8e7-8307-4a51a629d5b0', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'list', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7e60506a-fee5-fd92-b9ed-4a51a6292345', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'edit', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7f9d1df5-e605-d3fb-4d30-4a51a66ea3e4', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'delete', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('8c24a282-40e3-ad37-cb11-4a51a6e4b0f7', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'import', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('8e860689-95f2-0642-ed7c-4a51a6c7e095', '2009-07-06 07:24:16', '2009-07-06 07:24:16', '1', NULL, 'export', 'Bugs', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('51eebf40-cef0-f526-d794-4a51a688c477', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'access', 'Project', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('52ebfe9a-c4d4-80e0-d146-4a51a6f6213e', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'view', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('53b9172c-9627-ead3-7a2f-4a51a62de353', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'list', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('5499014b-b125-a621-31a5-4a51a62f0557', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'edit', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('5598e9d7-d707-f290-ed83-4a51a64d65bb', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'delete', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('56ae374e-924f-4200-355b-4a51a6d997da', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'import', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('58e6c267-60ad-f684-e3a0-4a51a621071a', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'export', 'Project', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('79641b81-e952-a709-587c-4a51a6e8a2ea', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'access', 'ProjectTask', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('7b11214b-067b-84c2-1ff8-4a51a6f97cfb', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'view', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7c1f3fc8-9615-ac5f-0719-4a51a64b92c0', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'list', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7ccd156b-ed09-5cea-0451-4a51a623af6b', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'edit', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7da77e2d-e953-a117-6f65-4a51a6476829', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'delete', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7e58e565-df43-d5b5-03f7-4a51a6220c54', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'import', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7f34ca09-5cff-320f-d692-4a51a6f19226', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'export', 'ProjectTask', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b140aab9-6c1c-f4c6-3966-4a51a610cd6c', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'access', 'EmailMarketing', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('b26fafc8-55bb-83f2-041b-4a51a6f468c1', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'view', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b34da496-561a-e5cb-d60f-4a51a61dcfad', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'list', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b411d1cf-8437-810d-53fd-4a51a6a01a01', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'edit', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b511c6a1-6dd7-5ccd-d099-4a51a67a2148', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'delete', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b5be0bc6-a1d6-3325-920e-4a51a63e43d8', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'import', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('b6fa78bb-9aeb-2bba-e54a-4a51a6bcf14c', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'export', 'EmailMarketing', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('e0299b25-1315-cece-33b5-4a51a68a1364', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'access', 'Campaigns', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('e1f46267-fc8b-ab57-4ad2-4a51a65a4738', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'view', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('e440466c-c562-8306-6ed5-4a51a607d0e8', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'list', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('e7255e45-1056-7dfd-9314-4a51a6b628e9', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'edit', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('eb98311c-34eb-0885-074d-4a51a6da1181', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'delete', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('f10083be-3a6c-786c-548e-4a51a625b9d4', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'import', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('f3593ea1-d472-5c46-e503-4a51a618b72b', '2009-07-06 07:24:17', '2009-07-06 07:24:17', '1', NULL, 'export', 'Campaigns', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('31adbb19-69be-08cd-b500-4a51a6474ad9', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'access', 'ProspectLists', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('32f6b592-ca1b-4bdc-16c9-4a51a63832f1', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'view', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('34320a32-e8f1-c97a-97cc-4a51a6ae08a1', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'list', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('358d55e6-c77a-8d7b-0f0a-4a51a6c9729f', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'edit', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('373077c0-2fad-2140-2a95-4a51a63555be', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'delete', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('394c07ac-ec44-9753-aa8c-4a51a6ecb767', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'import', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('3aff187b-6266-fea5-0a7d-4a51a61cf2c8', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'export', 'ProspectLists', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('4feb491e-235c-c47a-6833-4a51a6b1225e', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'access', 'Prospects', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('5150f12d-9847-9650-f394-4a51a6fa47cb', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'view', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('531182c5-bda6-41d7-3963-4a51a64d3f77', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'list', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('55033fc0-673c-c62b-891b-4a51a6a23fb8', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'edit', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('58908154-957a-d4c5-8f84-4a51a60094eb', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'delete', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('5a85c2db-eec0-3f38-394d-4a51a6f7f872', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'import', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('5c17c7e6-f07d-f777-0062-4a51a651a623', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'export', 'Prospects', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('79838ab3-595d-7f71-1fc9-4a51a6296189', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'access', 'Documents', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('7bb28ad6-1c0a-77f5-5ae0-4a51a6a7732e', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'view', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7d6adc18-dcb3-844d-d010-4a51a688120b', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'list', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('7f3fcede-ebb3-2a8c-7938-4a51a6c3b6df', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'edit', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('911de040-91e8-5220-d3c4-4a51a67e7901', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'delete', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('92841ab8-e5f9-2c9a-4b79-4a51a6176db7', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'import', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('9385bb70-cbbc-d085-13af-4a51a68b3a5b', '2009-07-06 07:24:18', '2009-07-06 07:24:18', '1', NULL, 'export', 'Documents', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('cb992467-1c28-df11-4ec8-4c4e8f34d18f', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'access', 'Users', 'module', 89, 0);
INSERT INTO `acl_actions` VALUES ('d1a81b53-5980-a1c0-462e-4c4e8f8a5fb5', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'view', 'Users', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('d8b21648-5748-c768-8e04-4c4e8f6ed6e6', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'list', 'Users', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('db587061-f86d-c4f2-95b7-4c4e8f8d85b0', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'edit', 'Users', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('ddef4bf6-0629-5085-d1ef-4c4e8ffa8e0e', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'delete', 'Users', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('e0e45b72-2153-d7c5-386d-4c4e8f5d8829', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'import', 'Users', 'module', 90, 0);
INSERT INTO `acl_actions` VALUES ('e327f600-caa9-bf32-80a8-4c4e8f0b81c6', '2010-07-27 07:51:03', '2010-07-27 07:51:03', '1', '1', 'export', 'Users', 'module', 90, 0);
