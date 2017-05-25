-- version 2.6.0-alpha2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Sep 19, 2013 at 11:21 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.0
-- 
-- Database : `test`
-- 

-- --------------------------------------------------------


-- 
-- Table structure for table `CONTACT_US`
--
USE newjs; 

DROP TABLE IF EXISTS `CONTACT_US`;
CREATE TABLE `CONTACT_US` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `VALUE` varchar(4) NOT NULL DEFAULT '',
  `NAME` varchar(100) NOT NULL DEFAULT '',
  `CONTACT_PERSON` varchar(40) NOT NULL DEFAULT '',
  `PHONE` varchar(100) NOT NULL,
  `MOBILE` varchar(100) NOT NULL,
  `ADDRESS` text NOT NULL,
  `TYPE` varchar(5) NOT NULL,
  `STATE` varchar(100) NOT NULL,
  `Match_Point_Service` char(4) NOT NULL,
  `STATE_VAL` char(10) NOT NULL,
  `LATITUDE` varchar(20) NOT NULL,
  `LONGITUDE` varchar(20) NOT NULL,
  `CITY_ID` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

-- 
-- Dumping data for table `CONTACT_US`
-- 

INSERT INTO `CONTACT_US` VALUES (1, '1', 'Kochi', 'Vandana Patel/Itee Mishra', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge India Ltd. \r\n2nd Floor, Puthookallel Complex,\r\nKallor Kadanvanthara Road, Kallor\r\ncochin, Kerala 682033', '1', 'Kochi', '', 'KE', '9.977896', '76.284192', 'KE13');
INSERT INTO `CONTACT_US` VALUES (3, '3', 'Manipal Centre MG Road', 'Pranesh Kumar', '07259031517,07738399768', '07259031517,07738399768', 'Manipal centre (MG Road) North block front wing 2nd floor Room No: 203 Dickenson Road Bangalore - 42 Email bharat.vaswani@jeevansathi.com', '3', 'Bangalore', '', 'KA', '12.975014', '77.614428', 'KA02');
INSERT INTO `CONTACT_US` VALUES (4, '4', 'Koramangla', 'Manisha Joshi', '07738399766/07259031517', '07738399766/ 07259031517', 'Info Edge (India) Ltd. 127, 1st Floor, Raheja Arcade,  5th Block, Koramangla Industrial Layout,  Bangalore - 560095', '4', 'Bangalore', '', 'KA', '', '', 'KA02');
INSERT INTO `CONTACT_US` VALUES (5, '5', 'Jayanagar', 'Pranesh', '07738399766/ 07259031517', '07738399766/ 07259031517', 'Info Edge India ltd', '5', 'Bangalore', '', 'KA', '', '', 'KA02');
INSERT INTO `CONTACT_US` VALUES (82, '82', 'Yamuna Nagar', 'Ankit Garg', '9971188602', '9971188602', 'Sr Achievers Academy Building Near Jagadhri Bus Stand  Ambala Road  Jagadhri-135003 Yamunanagar', '', 'Yamuna Nagar', '', 'HA', '', '', 'HA12');
INSERT INTO `CONTACT_US` VALUES (8, '8', 'Nugambakkam', 'Fincy', '9940688246/04442977777', '9940688246/04442977777', '1st Floor, Samson Towers, 403, L Pantheon Road, near egmore museum, Egmore, Chennai – 600 008', '8', 'Chennai', '', 'TN', '13.058326', '80.248271', 'TN02');
INSERT INTO `CONTACT_US` VALUES (11, '11', 'Coimbatore', 'Megha/Vandana', '18004196299 (toll free)', '18004196299/0120-4393500', '95-A, Vyshnav Building, Race Course, Coimbatore, Tamil Nadu - 641018', '11', 'Coimbatore', '', 'TN', '11.001245', '76.977988', 'TN04');
INSERT INTO `CONTACT_US` VALUES (12, '12', 'Begumpet', 'Shyam', '09971020046/09752091284', '09971020046/09752091284', 'Info Edge (India) Ltd.6-3-1192/1/1,Office No:113 To 115,3rd Block, 1st Floor, White House, Kundan bagh, Beside Life Style, Begumpet, Hyderabad-500016.', '12', 'Hyderabad', '', 'AP', '17.413408', '78.411741', 'AP03');
INSERT INTO `CONTACT_US` VALUES (13, '13', 'Kukatpally', 'Shyam', '09971020046/09752091284', '09971020046/09752091284', 'Info Edge (India) Ltd.', '13', 'Hyderabad', '', 'AP', '17.485762', '78.378534', 'AP03');
INSERT INTO `CONTACT_US` VALUES (77, '77', 'Ludhiana', 'Bhupender Sharma', '9915018427', '9915018427', 'Second Floor, Navrang Complex, Opp. Feroze Gandhi Market, B-XX-2707, Pakhowal Road, Ludhiana', '', 'Ludhiana', '', 'PU', '', '', 'PU07');
INSERT INTO `CONTACT_US` VALUES (78, '78', 'Ranchi', 'Reshma/Sheetal', '09967236623/ 09920911103', '09967236623/ 09920911103/7738399760', '', '', 'Ranchi', '', 'JH', '', '', 'JH01');
INSERT INTO `CONTACT_US` VALUES (14, '14', 'Himayathnagar', 'Shyam Kumar Mateti', '09971020046/09752091284', '09971020046/09752091284', '', '14', 'Hyderabad', '', 'AP', '17.334841', '78.306648', 'AP03');
INSERT INTO `CONTACT_US` VALUES (16, '16', 'Ahmedabad', 'Rashmi.S.', '8511170744/18004196299(T/F)', '8511170744/18004196299(T/F)', 'Info Edge (India) Ltd. 203 & 204, Shitiratna, Panchvati Circle, C. G. Road, Ahmedabad - 380006.', '16', 'Ahmedabad', '', 'GU', '23.023031', '72.556597', 'GU01');
INSERT INTO `CONTACT_US` VALUES (17, '17', 'Surat', 'Rimpy', '8511170741/18004196299(T/F)', '8511170741/8004196299(T/F)', 'Jeevansathi.com , Proton plus,  409 4th floor , Above Croma Showroom,  Near Star Bazaar, LP Savani Road , Adajan,  Surat .', '17', 'Surat', '', 'GU', '21.194215', '72.801713', 'GU10');
INSERT INTO `CONTACT_US` VALUES (18, '18', 'Baroda', 'Rimpy Suri', '8511170741/18004196299(T/F', '8511170741/18004196299(T/F)', 'Jeevansathi.com, TF/302,SOHO Complex,41,Punit nagar,Malhar point cross road,Old Padra Road,Baroda-390020', '18', 'Baroda', '', 'GU', '22.304005', '73.289795', 'GU04');
INSERT INTO `CONTACT_US` VALUES (19, '19', 'Rajkot', 'Rimpy', '8511170741', '8511170741', '205, Pramukh Swami Arcade, A wing, Malavia Chowk, Rajkot, Gujarat', '19', 'Rajkot', '', 'GU', '22.292149', '70.799654', 'GU09');
INSERT INTO `CONTACT_US` VALUES (20, '20', 'Bhopal', 'Pooja', '9752091287/18004196299(T/F)', '9752091287/18004196299(T/F)', 'Info Edge (India) Ltd. Ground Floor 2,Harrison House, 6 Malviya Nagar,Bhopal(M.P)', '20', 'Bhopal', '', 'MP', '23.340911', '77.434387', 'MP02');
INSERT INTO `CONTACT_US` VALUES (21, '53', 'Indore', 'Varsha.R.', '09752091284/ 09971020046', '09752091284/09971020046', 'Info Edge (India) Ltd. 201, Royal Ratan Building 7, MG Road Indore, Madhya Pradesh - 452001', '21', 'Indore', '', 'MP', '22.608602', '75.882568', 'MP08');
INSERT INTO `CONTACT_US` VALUES (22, '22', 'Raipur', 'Chetan Kumar', '9752091280/09163375809', '9752091280/09163375809', 'Info Edge (India) Ltd, 2nd Floor Tank Tower, Opp Ganj police station, Jail Road Near Fafahdih Chowk , Raipur 492001  (Chhattishgarh)', '22', 'Raipur', '', 'CH', '21.529398', '81.738281', 'CH01');
INSERT INTO `CONTACT_US` VALUES (23, '23', 'Andheri East', 'Kanchan/Sonali', '7738399760 , 09323141740', '7738399760 , 09323141740', '216/222, 2nd Floor, Chintamani Plaza, Near Cine Magic, Andheri Kurla Road, Andheri East, Mumbai, Maharashtra - 400099 Email bharat.vaswani@jeevansathi.com', '23', 'Mumbai', '', 'MH', '19.116594', '72.856575', 'MH04');
INSERT INTO `CONTACT_US` VALUES (24, '24', 'Worli', 'Nisha', '7738399762 /  7738399760 , 09323141740.', '022-67029730//32', 'Info Edge (India) Ltd. 203, 2nd Floor, Sumer Kendra,  Pandurang Budukar Marg, Worli,  Mumbai - 400018', '24', 'Mumbai', '', 'MH', '19.006087', '72.817575', 'MH04');
INSERT INTO `CONTACT_US` VALUES (60, '60', 'Borivili', 'Sheetal Shah', '7738399755/  7738399760 , 09323141740', '022-67029730//32', 'Jeevansathi.com, Office No 101, Kesar Kripa,  Opp Raj Mahal Hotel, Chandravarkar Road, Above Saraswat Bank, Near to Borivili Station, Borivili West, Mumbai ï¿½ 400 092', '60', 'Mumbai', '', 'MH', '', '', 'MH04');
INSERT INTO `CONTACT_US` VALUES (26, '26', 'Thane', 'Sulochana Gaikwad', '7738399753  //   7738399760 , 09323141740', '022-67029730//32', 'Jeevansathi.com, Office No-7, 1st Floor, Shreeji Arcade,Plot No.325, Opp Nitin Casting Company, Almedia Road,Panchpakhadi,   Thane (W) 400602', '26', 'Mumbai', '', 'MH', '19.195473', '72.965249', 'MH04');
INSERT INTO `CONTACT_US` VALUES (27, '27', 'Vashi', 'Reshma', '7738399763,   7738399760, 09323141740', '022-67029730//32', 'Info Edge (India) Ltd. 1103/4/5/6, 11th Floor Maithili''s Signet, Plot No. 39/4, Sec. 30 A,  Vashi, Navi Mumbai - 400705', '27', 'Mumbai', '', 'MH', '', '', 'MH04');
INSERT INTO `CONTACT_US` VALUES (28, '28', 'Koregaon Park', 'Karishma Shaikh', '07738399768/ 07738399767', '07738399768/ 07738399767', '2nd Floor, Unit No-202, GeraSterling (Above Italics), KoregaonPark, North Main, Road, Diagonally opp to German bakery, Pune, Maharashtra - 411001 Email bharat.vaswani@jeevansathi.com', '28', 'Pune', '', 'MH', '18.539187', '73.887305', 'MH08');
INSERT INTO `CONTACT_US` VALUES (98, '98', 'Jamnagar', '', '', '', '', '', 'Jamnagar', '', 'GU', '', '', 'GU19');
INSERT INTO `CONTACT_US` VALUES (91, '91', 'Bokaro', 'Mr. Rajatnath', '9031035670', '9031035670', 'HA-22, 2nd Floor, Near Indian Bank,\r\nCity Centre, Sector-4\r\nBokaro Steel City', '', 'Bokaro', '', 'JH', '', '', 'JH04');
INSERT INTO `CONTACT_US` VALUES (30, '30', 'Chinchwad', 'Priyanka Unde/Pratichi Mitra', '07738399764/ 07738399769', '07738399764/ 07738399769', '', '30', 'Pune', 'Y', 'MH', '18.633293', '73.795809', 'MH08');
INSERT INTO `CONTACT_US` VALUES (70, '70', 'Patna', 'Kishor', '9771490294/9867259676/  7738399760 , 09323141740', '9771490294/9867259676/  7738399760 , 09323141740', 'House no.55C , near Mourya TV office, Sri Krishna puri, Patna-1', '', 'Patna', '', 'BI', '', '', 'BI06');
INSERT INTO `CONTACT_US` VALUES (31, '31', 'Nagpur', 'Neha Gupta', '7738399771, //  7738399773\r\n,', '7738399771, //  7738399773', 'Info Edge (India) Ltd. F-9, Phase-II, Achraj Towers, Chindwara Road, Sadar, Nagpur - 13 Email bharat.vaswani@jeevansathi.com', '31', 'Nagpur', '', 'MH', '21.094751', '79.07959', 'MH05');
INSERT INTO `CONTACT_US` VALUES (32, '32', 'Nashik', 'Shreyas Malve', '7738399773/ 7738399771', '7738399773/ 7738399771', 'Info Edge (India) Ltd. B-8, Kusum Pushpa Apartment Opp. Dairy Don, College Road Nashik - 5 Email bharat.vaswani@jeevansathi.com', '32', 'Nashik', '', 'MH', '19.766704', '73.806152', 'MH24');
INSERT INTO `CONTACT_US` VALUES (33, '33', 'Aurangabad', 'Maya Paikrao', '07738399770/ 07738399771', '07738399770/ 07738399771', 'Info Edge (India) Ltd. H.S.Kandi centre 2nd floor, Central Wing Jalna Road, Aurangabad - 431005.. Email bharat.vaswani@jeevansathi.com', '33', 'Aurangabad', '', 'MH', '', '', 'MH02');
INSERT INTO `CONTACT_US` VALUES (35, '21', 'Head Office', 'Vandana Patel', '18004196299(toll free)/ 0120-4393500', '8826884400', 'B-8, Sector-132, Noida-201301', '35', 'Noida', '', 'UP', '28.588884', '77.322392', 'UP25');
INSERT INTO `CONTACT_US` VALUES (36, '36', 'Connaught Place', 'Jyoti Bajaj', '9910482309', '9910482309', 'GF-4, Indraprakash Building, 21, Barakhamba Road, Near Metro Station, New Delhi -110001', '36', 'Delhi', '', 'DE', '28.630312', '77.22554', 'DE00');
INSERT INTO `CONTACT_US` VALUES (37, '37', 'Pitampura', 'Parul Singh/Prince Dua', '9910007594/9971177324', '9910007594/9971177324', '711, 7th Floor, ITL Twin Towers, B-09, Netaji Subhash Place, Opp.Wazirpur, District Centre, Pitampura, Delhi', '37', 'Delhi', '', 'DE', '', '', 'DE00');
INSERT INTO `CONTACT_US` VALUES (38, '38', 'Nehru Place', 'Shiv Kumar/Ritu Rani', '9910006341/9910006935', '9910006341/9910006935', 'GF-12A, 94, Meghdoot Buliding, Nehru Place, New Delhi - 1100019', '38', 'Delhi', '', 'DE', '28.547221', '77.251289', 'DE00');
INSERT INTO `CONTACT_US` VALUES (39, '39', 'Gurgaon', 'Aayush Singhal', '9971550281', '9971550281', 'SF-55, First Floor, Galleria Commercial Complex, DLF Phase 4, Gurgaon - 122002  B-15, Uper FF, Institutional Area, Sector 32 (Near Louis Berger) Gurgaon - 122001', '39', 'Gurgaon', '', 'HA', '28.467059', '77.081752', 'HA03');
INSERT INTO `CONTACT_US` VALUES (41, '41', 'Chandigarh', 'Bhupender Sharma', '9915018427', '9915018427', 'S.C.O 14-15, First Floor, Near I.C.I.C.I Bank, Sector-9d, Madhya Marg, Chandigarh, Punjab', '41', 'Chandigarh', '', 'PU', '', '', 'PH00');
INSERT INTO `CONTACT_US` VALUES (43, '43', 'AJC Bose Road', 'Kumarika', '9163379809/ 9163375809', '9163379809/ 9163375809', '224 , A J C Bose Road, Krishna Building , 1st Floor,Room No - 107 & 108,1st Floor,Near Beckbagan , opp to Lamartinier School For Girls, Kolkata, West Bengal - 700017', '43', 'Kolkata', 'Y', 'WB', '22.553529', '88.364089', 'WB05');
INSERT INTO `CONTACT_US` VALUES (46, '46', 'South Kolkata', 'Ms. Kumarika Bhattacharya', '9163375809/9163379809', '9163375809/9163379809', '38 Gariahat Rd, Identity Building, 1st floor Room nos-5, Near Selimpur Bus Stand, Kolkata-700031', '46', 'Kolkata', 'Y', 'WB', '22.502061', '88.356546', 'WB05');
INSERT INTO `CONTACT_US` VALUES (47, '47', 'Jamshedpur', 'Khusboo Singh', '9771490295/09163375809', '9771490295/09163375809', 'Bharat Business Centre,Room-6,2nd Flr,Ram Mandir Area,Bistupur,Near Punjab Naiotnal Bank. Jamshedpur', '47', 'Jamshedpur', '', 'JH', '22.78865', '86.184955', 'JH02');
INSERT INTO `CONTACT_US` VALUES (49, '49', 'Bhubaneshwar', 'Kumarika', '09752091280/ 09163375809', '09752091280/ 09163375809', 'Info Edge (India) Ltd. D-5 , 5th Floor Metro House Vani Vihar Square (Opp Utkal University) Bhubaneswar - 751007  Email bharat.vaswani@jeevansathi.com', '49', 'Bhubaneshwar', '', 'OR', '20.303975', '85.839666', 'OR01');
INSERT INTO `CONTACT_US` VALUES (50, '50', 'Sapru marg', 'Bhawna Piplani', '7388805533', '7388805533', '31/107, Ground Floor,  Sahu Building, Hazratganj, Opp. Universal Book Sellers,  Lucknow - 226001', '50', 'Lucknow', '', 'UP', '', '', 'UP19');
INSERT INTO `CONTACT_US` VALUES (51, '51', 'Jaipur', 'Vima Pandey', '9929607350', '9929607350', 'Info Edge (India) Ltd. 605, Crystal Palm, C Scheme, Bais Godaam, Jaipur', '51', 'Jaipur', '', 'RA', '', '', 'RA07');
INSERT INTO `CONTACT_US` VALUES (52, '52', 'Guwahati', 'Kumarika/ Chetan', '09163375809/09752091280', '09163375809/09752091280', '', '52', 'Guwahati', '', 'AS', '', '', 'AS03');
INSERT INTO `CONTACT_US` VALUES (59, '59', 'Mulund', 'Komal Thange / Vaibhavi Nalavade', '7738399752/   7738399760 , 09323141740', '022-67029730//32', 'Jeevansathi.com, Shop No 18,19 and 20 Maruti Arcade, J.N.Road,Opp Brijwasi Sweets, near to Mulund Station, Mulund West  Mumbai 400 080', '59', 'Mumbai', '', 'MH', '', '', 'MH04');
INSERT INTO `CONTACT_US` VALUES (54, '54', 'Malviya Nagar', 'Jayaprabha/Anuradha Ghosh', '9971175142/9910006538', '9971175142/9910006538', 'D-88 Lower Basement, Near Costa Coffee, Malviya Nager, New Delhi - 110017', '54', 'Delhi', 'Y', 'DE', '8.5332649', '77.208899', 'DE00');
INSERT INTO `CONTACT_US` VALUES (56, '56', 'Kamla Nagar', 'Kusum Lata/Abhishek Sinha', '9971176072/9910006927', '9971176072/9910006927', '36A, Ground Floor, Near Shakti Nagar Chowk, Kamla Nagar', '56', 'Delhi', 'Y', 'DE', '28.571355', '77.243389', 'DE00');
INSERT INTO `CONTACT_US` VALUES (57, '57', 'Hazratganj', 'Bhawna Piplani', '7388805533', '7388805533', '31/107, Ground Floor,  Sahu Building, Hazratganj,opp.universal book sellers,  Lucknow - 226001', '57', 'Lucknow', 'Y', 'UP', '26.847492', '80.945855', 'UP19');
INSERT INTO `CONTACT_US` VALUES (58, '58', 'Match point office', 'Ravindra/Megha Mishra', '9769154455/8860321523', '9769154455/8860321523', 'Info Edge India Ltd, A-88, Sector 2, NOIDA', '58', 'Noida', '', 'UP', '', '', 'UP25');
INSERT INTO `CONTACT_US` VALUES (62, '62', 'Rajouri Garden', 'Sahil/Ritu Darshi/Neha Sahni', '9971660808/9560885791/9910006735', '9971660808/9560885791/9910006735', 'J-198, First Floor, Main Najafgarj Road, Metro pillar No.419, Opp. Kukreja Hospital, Rajouri Garden, New Delhi - 110027', '62', 'Delhi', 'Y', 'DE', '28.633389', '77.086935', 'DE00');
INSERT INTO `CONTACT_US` VALUES (61, '61', 'Ghatkopar', 'Shailendra/Lakshmi', '7738399756 //  7738399760, 09323141740', '022-67029730//32', '', '61', 'Mumbai', '', 'MH', '', '', 'MH04');
INSERT INTO `CONTACT_US` VALUES (63, '63', 'Laxmi Nagar', 'Manju Singh', '9971176433', '9971176433', 'D-100, Street No.5, Ground floor, Laxmi Nagar, Near Metro Station, New Delhi-110092', '63', 'Delhi', 'Y', 'DE', '28.631235', '77.278165', 'DE00');
INSERT INTO `CONTACT_US` VALUES (64, '64', 'Andheri West', 'Sudeepta Mukherjee / Ravindra Chaudhary', '7738399759 /  7738399760 , 09323141740', '022-67029730//32', 'Jeevansathi.com. Shop No. 2, Bhavesha Co-op Housing Society,  Veera Desai Road, Opp Andheri Sports Complex,  Andheri West, Mumbai ï¿½ 400 058', '64', 'Mumbai', 'Y', 'MH', '', '', 'MH04');
INSERT INTO `CONTACT_US` VALUES (65, '65', 'Agra', 'Preeti Goyal/Kirti Singh', '9910482363/ 7388807733', '9910482363/ 7388807733', '1/133, Upper Ground Floor, Friends Shoppe, Hariparvat Crossing, M.G.Road, Near Popular Cycles, Agra', '65', 'Agra', 'Y', 'UP', '', '', 'UP01');
INSERT INTO `CONTACT_US` VALUES (66, '66', 'Kanpur', 'Kush Dixit/Amit Kumar Gupta', '8853220044/8853220055', '8853220044/8853220055', '14/121-B, Main Road, The Mall,  Parade, Opposite Raymonds Showroom,  Kanpur', '66', 'Kanpur', 'Y', 'UP', '', '', 'UP18');
INSERT INTO `CONTACT_US` VALUES (67, '67', 'J M Road', 'Kavita Dhumal', '07738399764/ 07738399767 / 07738399769/ 07738399766', '07738399764/ 07738399767 / 07738399769/ 07738399766', 'Info Edge (India) Ltd. Office.No B-1, Basement ,  CIFCO Centre, J M Road, Next to Shiv Sagar Restaurant,  Pune Deccan, Pune - 411043.Email bharat.vaswani@jeevansathi.com', '67', 'Pune', 'Y', 'MH', '', '', 'MH08');
INSERT INTO `CONTACT_US` VALUES (68, '68', 'Camp', 'Kavita', '7738399767//7738399768', '7738399767// 7738399768', '', '68', 'Pune', 'Y', 'MH', '', '', 'MH08');
INSERT INTO `CONTACT_US` VALUES (97, '97', 'Dhanbad', 'Khushboo/Kumarika', '09771490295/09163375809', '09771490295/09163375809', '', '', 'Dhanbad', '', 'JH', '', '', 'JH03');
INSERT INTO `CONTACT_US` VALUES (71, '71', 'Allahabad', 'Maya', '7388808833', '7388808833', 'Malik Nivas, 60-M /44 5-A Sangam Bihar Colony, Nawab Yusuf Road, Allahabad', '', 'Allahabad', '', 'UP', '', '', 'UP03');
INSERT INTO `CONTACT_US` VALUES (72, '72', 'Varanasi', 'Mr. Deepak Mali', '7388806633', '7388806633', 'City Air Linker, Opp. Rita Ice-cream, Shree Ram Machinery Market,  Maldahiya, Varanasi - 221002', '', 'Varanasi', '', 'UP', '', '', 'UP30');
INSERT INTO `CONTACT_US` VALUES (73, '73', 'Jabalpur', 'Rishin Paul', '9752091283/ 7738399760 , 09323141740', '9752091283/ 7738399760 , 09323141740', 'KATANGA DUPLEX NO.2, BHUMIKA STATE, NARBADA ROAD,JABALPUR.', '', 'Jabalpur', '', 'MP', '', '', 'MP09');
INSERT INTO `CONTACT_US` VALUES (74, '74', 'Nanded', 'Nisha', '9870565667', '9870565667', '', '', 'Nanded', '', 'MH', '', '', 'MH06');
INSERT INTO `CONTACT_US` VALUES (81, '81', 'Pathankot', 'Shashank Ghanekar', '9971009572', '9971009572', 'C-13, Below Mantra''s Salon, Victoria Estate, Pathankot', '', 'Pathankot', '', 'PU', '', '', 'PU08');
INSERT INTO `CONTACT_US` VALUES (80, '80', 'Lake Town', 'Sriparna Bose', '9163379809/9163375809', '9163379809/9163375809', '', '', 'Kolkata', '', 'WB', '', '', 'WB05');
INSERT INTO `CONTACT_US` VALUES (76, '76', 'Jalandhar', 'Ms. Krishma Malhotra', '9915018482', '9915018482', '8,Link Colony,Opposite Nari Niketan School,Nakodar Road,Jalandhar', '', 'Jalandhar', '', 'PU', '', '', 'PU10');
INSERT INTO `CONTACT_US` VALUES (79, '79', 'Bhavnagar', 'Kanchan/Sonali', '7738399760', '7738399760', '', '', 'Bhavnagar', '', 'GU', '', '', 'GU14');
INSERT INTO `CONTACT_US` VALUES (75, '75', 'Gwalior', 'Ms. Swati Chaturvedi', '9575569008/  7738399760', '9575569008/ 7738399760,', 'JeevanSathi.com,29, White House, City Center, Gwalior (M.P.)', '', 'Gwalior', '', 'MP', '', '', 'MP07');
INSERT INTO `CONTACT_US` VALUES (69, '69', 'Dehradun', 'Jaya Gurung', '7388873003', '7388873003', '77/1, Dilaram Chowk, Rajpur Road. Dehradun. 248001', '', 'Dehradun', '', 'UK', '', '', 'UK05');
INSERT INTO `CONTACT_US` VALUES (83, '83', 'Meerut', 'Mallika Saxena', '9910009327', '9910009327', 'M-9, Meena Bazaar, Abu Plaza Complex, Abu Lane Road, Near Hotel Rajmahal, Meerut', '', 'Meerut', '', 'UP', '', '', 'UP21');
INSERT INTO `CONTACT_US` VALUES (84, '84', 'Bareilly', 'Dinesh Gupta', '9971177904', '9971177904', '35L/5B, Rampur Garden, Near Agrasen Park, Bareilly - 243001', '', 'Bareilly', '', 'UP', '', '', 'UP06');
INSERT INTO `CONTACT_US` VALUES (99, '99', 'Ajmer', 'Kamal Kumar', '7891087786', '7891087786', 'Jeevansathi.com Deepa tent house Nasirabad Road Nagra-305001', '', 'Ajmer', '', 'RA', '', '', 'RA01');
INSERT INTO `CONTACT_US` VALUES (85, '85', 'Margao', 'Kanchan/Sonali', '7738399760', '7738399760', '', '', 'Margao', '', 'GO', '', '', 'GO02');
INSERT INTO `CONTACT_US` VALUES (86, '86', 'Panaji', 'Manisha Joshi', '7738399766/7738399764', '7738399766/7738399764', '', '', 'Panaji', '', 'GO', '', '', 'GO03');
INSERT INTO `CONTACT_US` VALUES (87, '87', 'Bilaspur', 'Kishor/Rishin', '9771490294/9752091283/7738399760', '9771490294/9752091283/7738399760', 'Jeevansathi.com,  Beside HDFC Bank , Link Road Bilaspur (C.G)', '', 'Bilaspur', '', 'CH', '', '', 'CH03');
INSERT INTO `CONTACT_US` VALUES (88, '88', 'Kolhapur', 'Kavita', '07738399767/ 07738399764', '07738399767/ 07738399764', '', '', 'Kolhapur', '', 'MH', '', '', 'MH03');
INSERT INTO `CONTACT_US` VALUES (89, '89', 'Siliguri', 'Kumarika', '9163379809/ 9163375809', '9163379809/ 9163375809', '', '', 'Siliguri', '', 'WB', '', '', 'WB37');
INSERT INTO `CONTACT_US` VALUES (90, '90', 'Ujjain', 'Mr.Gamendra', '9074766203', '9074766203', '25,2nd Floor, S.K Tower,Bakhtavar marg,Freegunj ,Near Rajkumar Hotel,Above Kesar Sarees Ujjain.', '', 'Ujjain', '', 'MP', '', '', 'MP11');
INSERT INTO `CONTACT_US` VALUES (92, '92', 'Ghaziabad', 'Sourabh Singh', '9971188452', '9971188452', 'Shop No. 22, Lower Ground Floor, Kumar Excellency Plaza, Near   Sunrise Mall, Sector 11, Vasundhara', '', 'Ghaziabad', '', 'UP', '', '', 'UP12');
INSERT INTO `CONTACT_US` VALUES (93, '93', 'Kota', 'Gaurav Mathur', '9871682929', '9871682929', 'Samarpan Royal, 277-A, Talwandi, Near Sheela Chowdhary Nursing Home, Kota - 324005', '', 'Kota', '', 'RA', '', '', 'RA09');
INSERT INTO `CONTACT_US` VALUES (94, '94', 'Solapur', 'Neha Gupta', '7738399771/ 7738399773', '7738399771/ 7738399773', '', '', 'Solapur', '', 'MH', '', '', 'MH11');
INSERT INTO `CONTACT_US` VALUES (95, '95', 'Amravati', 'Shreyas Malve', '07738399773 / 07738399771', '07738399773 / 07738399771', '', '', 'Amravati', '', 'MH', '', '', 'AP13');
INSERT INTO `CONTACT_US` VALUES (96, '96', 'Bhilai', 'Namita Sehgal', '8253022977', '8253022977', '38A, Sunder Nagar Near Vaishali Nagar police chowki Bhilai  Dist Durg (Chattisgarh)', '', 'Bhilai', '', 'CH', '', '', 'CH04');$sql_address=" SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE STATE_VAL='".substr($city_value,0,2)."'";
		$sql_address=" SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE STATE_VAL='".substr($city_value,0,2)."'";
