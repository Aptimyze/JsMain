-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 11, 2011 at 05:33 PM
-- Server version: 5.0.27
-- PHP Version: 5.3.6
-- 
-- Database: `newjs`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `CONTACT_US`
-- 
use newjs;

DROP TABLE IF EXISTS `CONTACT_US`;
CREATE TABLE `CONTACT_US` (
  `ID` int(11) NOT NULL auto_increment,
  `VALUE` varchar(4) NOT NULL default '',
  `NAME` varchar(100) NOT NULL default '',
  `CONTACT_PERSON` varchar(40) NOT NULL default '',
  `PHONE` varchar(100) NOT NULL,
  `MOBILE` varchar(100) NOT NULL,
  `ADDRESS` text NOT NULL,
  `TYPE` varchar(5) NOT NULL,
  `STATE` varchar(100) NOT NULL,
  `Match_Point_Service` char(4) NOT NULL,
  `STATE_VAL` char(10) NOT NULL,
  `LATITUDE` varchar(20) NOT NULL,
  `LONGITUDE` varchar(20) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

-- 
-- Dumping data for table `CONTACT_US`
-- 

INSERT INTO `CONTACT_US` VALUES (1, '1', 'Kochi', 'Ragini/Vandana', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. Door No.41, 1st Floor, Jacobâ€™s DD Mall Shenoys Junction, M.G.Road  Cochin-682035', '1', 'Kochi', '', 'KE', '12.302771', '76.63084');
INSERT INTO `CONTACT_US` VALUES (3, '3', 'Manipal Centre MG Road', 'Ragini/ Vandana', '18004196299/0120-4393500', '18004196299/0120-4393500', 'Manipal centre (MG Road) North block front wing 2nd floor Room No: 203 Dickenson Road Bangalore - 42 Email bharat.vaswani@jeevansathi.com', '3', 'Bangalore', '', 'KA', '13.597939', '77.612915');
INSERT INTO `CONTACT_US` VALUES (4, '4', 'Koramangla', 'Ragini/ Vandana', '18004196299/ 0120-4393500', '18004196299 (toll free)/0120-4393500', 'Info Edge (India) Ltd. 127, 1st Floor, Raheja Arcade,  5th Block, Koramangla Industrial Layout,  Bangalore - 560095', '4', 'Bangalore', '', 'KA', '12.974878', '77.614557');
INSERT INTO `CONTACT_US` VALUES (5, '5', 'Jayanagar', 'Ragini/ Vandana', '18004196299/ 0120-4393500', '18004196299 (toll free)/ 0120-4393500', 'Info Edge India ltd', '5', 'Bangalore', '', 'KA', '', '');
INSERT INTO `CONTACT_US` VALUES (8, '8', 'Nugambakkam', 'Ragini/ Vandana', '18004196299/0120-4393500', '18004196299/0120-4393500', '1-H ,Gee Gee Emerald 151, Village Road Nungambakkam Chennai, Tamil Nadu - 600 034', '8', 'Chennai', '', 'TN', '9.828006', '77.985473');
INSERT INTO `CONTACT_US` VALUES (11, '11', 'Coimbatore', 'Ritu/Vandana', '18004196299/0120-4393500', '18004196299/0120-4393500', '95-A, Vyshnav Building, Race Course, Coimbatore, Tamil Nadu - 641018', '11', 'Coimbatore', '', 'TN', '13.432367', '80.941772');
INSERT INTO `CONTACT_US` VALUES (12, '12', 'Begumpet', 'Ragini/ Vandana', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd.6-3-1192/1/1,Office No:113 To 115,3rd Block, 1st Floor, White House,\nKundan bagh, Beside Life Style, Begumpet, Hyderabad-500016.', '12', 'Hyderabad', '', 'AP', '17.413408', '78.411741');
INSERT INTO `CONTACT_US` VALUES (13, '13', 'Kukatpally', 'Ragini/ Ritu', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd.', '13', 'Hyderabad', '', 'AP', '17.485762', '78.378534');
INSERT INTO `CONTACT_US` VALUES (14, '14', 'Himayathnagar', 'Ragini/Vandana', '18004196299/0120-4393500', '18004196299/0120-4393500', 'Info Edge (India) Ltd. 302, 3rd Floor, Pavani Estates,  H. No. 3-6-365/C, Liberty ''X'' Road,  Himayatnagar, Hyderabad', '14', 'Hyderabad', '', 'AP', '21.125498', '81.914063');
INSERT INTO `CONTACT_US` VALUES (16, '16', 'Ahmedabad', 'Neha/Vandana', '18004196299/ 0120-4393500', '-', 'Info Edge (India) Ltd. 203 & 204, Shitiratna, Panchvati Circle, C. G. Road, Ahmedabad - 380006.', '16', 'Ahmedabad', '', 'GU', '23.025544', '72.565792');
INSERT INTO `CONTACT_US` VALUES (17, '17', 'Surat', 'Ronak Sachdeva', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. 203, 2nd Floor, K.G.House, Above ABN AMRO Bank, Opp Citi Bank, Ghod Dod Road, Surat - 395007.', '17', 'Surat', '', 'GU', '22.292908', '70.79916');
INSERT INTO `CONTACT_US` VALUES (18, '18', 'Baroda', 'Rimpy Suri', '18004196299/ 0120-4393500', '-', 'Info Edge (India) Ltd. 304 - Tithi Complex Opposite Baroda Productivity Council Productivity Road, Alkapuri Baroda - 390007 Gujarat', '18', 'Baroda', '', 'GU', '21.125498', '81.914063');
INSERT INTO `CONTACT_US` VALUES (19, '19', 'Rajkot', 'Priyanka Vinda', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', '502, Pramukh Swami Arcade, A wing, Malavia Chowk, Rajkot, Gujarat', '19', 'Rajkot', '', 'GU', '21.145807', '79.088232');
INSERT INTO `CONTACT_US` VALUES (20, '20', 'Bhopal', 'Pooja', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. Ground Floor 2,Harrison House, 6 Malviya Nagar,Bhopal(M.P)', '20', 'Bhopal', '', 'MP', '8.498306', '76.94725');
INSERT INTO `CONTACT_US` VALUES (21, '53', 'Indore', 'Varsha.R.', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. 201, Royal Ratan Building 7, MG Road Indore, Madhya Pradesh - 452001', '21', 'Indore', '', 'MP', '23.240814', '77.405891');
INSERT INTO `CONTACT_US` VALUES (22, '22', 'Raipur', 'Chetan Kumar', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. F-Q, DM Plaza, Opp. Surana Bhawan, Near Rajiv Gandhi Chowk, Chota Para, Near Fire Station Raipur - 492001 (Chattisgarh)', '22', 'Raipur', '', 'MP', '21.125498', '81.914063');
INSERT INTO `CONTACT_US` VALUES (23, '23', 'Andheri East', 'Tanvi/Sonali', '022-67029730//32', '09324086055, 09323141740', '216/222, 2nd Floor, Chintamani Plaza, Near Cine Magic, Andheri Kurla Road, Andheri East, Mumbai, Maharashtra - 400099 Email bharat.vaswani@jeevansathi.com', '23', 'Mumbai', '', 'MH', '19.225583', '73.167572');
INSERT INTO `CONTACT_US` VALUES (24, '24', 'Worli', 'Dipti Sharma', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. 203, 2nd Floor, Sumer Kendra,  Pandurang Budukar Marg, Worli,  Mumbai - 400018', '24', 'Mumbai', '', 'MH', '19.064359', '72.998716');
INSERT INTO `CONTACT_US` VALUES (60, '60', 'Borivili', 'Sheetal Shah/Anshu Tiwari', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. Office No 101, Kesar Kripa,  Opp Raj Mahal Hotel, Chandravarkar Road,  Borivili West, Mumbai Ã¯Â¿Â½ 400 092', '60', 'Mumbai', '', 'MH', '19.124084', '72.847481');
INSERT INTO `CONTACT_US` VALUES (26, '26', 'Thane', 'Sulochana Gaikwad', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. Office No-7 First Floor, Shreeji Arcade,  Plot No.325, Opp Nitin Casting Company,  Almedia Road, Panchpakhadi,   Thane (W) 400602', '26', 'Mumbai', '', 'MH', '19.0407', '72.907677');
INSERT INTO `CONTACT_US` VALUES (27, '27', 'Vashi', 'Tanvi/Sonali', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. 1401, 1402 & 1403, Maithili''s Signet,  14th Floor, Plot No. 39/4, Sec. 30 A,  Vashi, Navi Mumbai - 400705', '27', 'Mumbai', '', 'MH', '19.230568', '72.853845');
INSERT INTO `CONTACT_US` VALUES (28, '28', 'Koregaon Park', 'Gausiya', '09324086055, 09323141740', '09324086055, 09323141740', '2nd Floor, Unit No-202, GeraSterling (Above Italics), KoregaonPark, North Main, Road, Diagonally opp to German bakery, Pune, Maharashtra - 411001 Email bharat.vaswani@jeevansathi.com', '28', 'Pune', '', 'MH', '18.636328', '73.794882');
INSERT INTO `CONTACT_US` VALUES (30, '30', 'Chinchwad', 'Shashank\n/Kavita', '09324086055, 09323141740', '09324086055, 09323141740', '', '30', 'Pune', 'Y', 'MH', '18.515266', '73.834696');
INSERT INTO `CONTACT_US` VALUES (31, '31', 'Nagpur', 'Neha Gupta', '09324086055, 09323141740', '09324086055, 09323141740', 'Info Edge (India) Ltd. F-9, Phase-II, Achraj Towers, Chindwara Road, Sadar, Nagpur - 13\nEmail bharat.vaswani@jeevansathi.com', '31', 'Nagpur', '', 'MH', '21.167794', '79.078785');
INSERT INTO `CONTACT_US` VALUES (32, '32', 'Nashik', 'Gayatri', '09324086055, 09323141740', '09324086055, 09323141740', 'Info Edge (India) Ltd. B-8, Kusum Pushpa Apartment Opp. Dairy Don, College Road Nashik - 5\nEmail bharat.vaswani@jeevansathi.com', '32', 'Nashik', '', 'MH', '21.187225', '79.179124');
INSERT INTO `CONTACT_US` VALUES (33, '33', 'Aurangabad', 'Maya Paikrao', '09324086055, 09323141740', '09324086055, 09323141740', 'Info Edge (India) Ltd. H.S.Kandi centre 2nd floor, Central Wing Jalna Road, Aurangabad - 431005.. Email bharat.vaswani@jeevansathi.com', '33', 'Aurangabad', '', 'MH', '26.931138', '75.790912');
INSERT INTO `CONTACT_US` VALUES (35, '21', 'Head Office', 'Ragini/Vandana', '18004196299,0120-4393500', '18004196299/ 0120-4393500', 'B-77, Sector-5, Noida-201301', '35', 'Noida', '', 'UP', '28.588884', '77.322392');
INSERT INTO `CONTACT_US` VALUES (36, '36', 'Connaught Place', 'Ragini/Vandana', '0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (I) Ltd.', '36', 'Delhi', '', 'DE00', '21.251302', '81.629646');
INSERT INTO `CONTACT_US` VALUES (37, '37', 'Pitampura', 'Parul Singh', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', '801, 8th Floor, ITL Twin Towers, B-09, Netaji Subhash Place, Opp.Wazirpur, District Centre, Pitampura, Delhi', '37', 'Delhi', '', 'DE00', '28.758028', '77.213287');
INSERT INTO `CONTACT_US` VALUES (38, '38', 'Nehru Place', 'Ragini/Vandana', '0120-4393500/ 18004196299', '18004196299/ 0120-4393500', 'GF-12A, 94, Meghdoot Buliding, Nehru Place, New Delhi-1100019', '38', 'Delhi', '', 'DE00', '28.584017', '77.24653');
INSERT INTO `CONTACT_US` VALUES (39, '39', 'Gurgaon', 'Ragini/Neha', '0120-4393500', '18004196299/ 0120-4393500', '809, 8th Floor, DLF Phase-IV, Galleria Commercial Complex, Gurgaon', '39', 'Gurgaon', '', 'HA', '28.468399', '77.083157');
INSERT INTO `CONTACT_US` VALUES (41, '41', 'Chandigarh', 'Puja Bhardwaj', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'S.C.O 14-15, First Floor, Near I.C.I.C.I Bank, Sector-9d, Madhya Marg, Chandigarh, Punjab', '41', 'Chandigarh', '', 'PU', '30.67492', '75.864778');
INSERT INTO `CONTACT_US` VALUES (43, '43', 'AJC Road', 'Kumarika', '18004196299/ 0120-4393500', '-', '224 , A J C Bose Road, Krishna Building , 1st Floor,Room No - 107 & 108,1st Floor,Near Beckbagan , opp to Lamartinier School For Girls, Kolkata, West Bengal - 700017', '43', 'Kolkata (MAtch Point Services)', 'Y', 'WB', '21.125498', '81.914063');
INSERT INTO `CONTACT_US` VALUES (46, '46', 'South Kolkata', 'Ms. Kumarika Bhattacharya', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', '38 Gariahat Rd, Identity Building, 1st floor Room nos-5, Near Selimpur Bus Stand, Kolkata-700031', '46', 'Kolkata', 'Y', 'WB', '21.125498', '81.914063');
INSERT INTO `CONTACT_US` VALUES (47, '47', 'Jamshedpur', 'Khusboo Singh', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. BHARAT BUSINESS CENTRE Module', '47', 'Jamshedpur', '', 'BI', '22.105999', '81.914063');
INSERT INTO `CONTACT_US` VALUES (49, '49', 'Bhubneshwar', 'Rupa Ray', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. D-5 , 5th Floor Metro House Vani Vihar Square (Opp Utkal University) Bhubaneswar - 751007  Email bharat.vaswani@jeevansathi.com', '49', 'Bhubneshwar', '', 'OR', '18.554811', '73.870182');
INSERT INTO `CONTACT_US` VALUES (50, '50', 'Sapru marg', 'Ragini/Vandana', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge I ltd 31/107, Ground Floor,  Sahu Building, Hazratganj,  Lucknow - 226001', '50', 'Lucknow', '', 'UP', '26.47209', '80.355313');
INSERT INTO `CONTACT_US` VALUES (51, '51', 'Jaipur', 'Chirag Pareek', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. 605, Crystal Mall, S.J.S. Highway, Bani Park, Jaipur - 302016', '51', 'Jaipur', '', 'RA', '26.931138', '75.790912');
INSERT INTO `CONTACT_US` VALUES (52, '52', 'Guwahati', 'Ragini/Vandana', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge(I) lTD', '52', 'Guwahati', '', 'AS', '', '');
INSERT INTO `CONTACT_US` VALUES (59, '59', 'Mulund', 'Komal Thange/Tripti Daga', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. Shop No 18,19 and 20  Maruti Arcade, J.N.Road,   Opp Brijwasi Sweets, Mulund West  Mumbai 400 080', '59', 'Mumbai', '', 'MH', '19.119747', '72.936106');
INSERT INTO `CONTACT_US` VALUES (54, '54', 'Malviya Nagar', 'Anuradha / Gaurav', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge I LtdD-88 Lower Basement,Near Shri Ram Sweets, Malviya Nager-New Delhi-110017', '54', 'Delhi', 'Y', 'DE00', '28.630331', '77.277156');
INSERT INTO `CONTACT_US` VALUES (56, '56', 'Kamla Nagar', 'Isha', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge I Ltd36, Block A, Ground Floor,  Northern City Extn., Scheme No. 1,  Subzi Mandi, Kamla Nagar, Delhi', '56', 'Delhi', 'Y', 'DE00', '28.630157', '77.22613');
INSERT INTO `CONTACT_US` VALUES (57, '57', 'Hazratganj', 'Lata nainwal/Vikas singh', '18004196299/0120-4393500', '0120-4393500', '31/107, Ground Floor,  Sahu Building, Hazratganj,opp.universal book depot,  Lucknow - 226001', '57', 'Lucknow', 'Y', 'UP', '26.47209', '80.355313');
INSERT INTO `CONTACT_US` VALUES (58, '58', 'Match point office', 'Sanjay Bajaj', '0120-4393500/ 18004196299', '0120-4393500/ 18004196299', 'Info Edge I ltd', '58', 'Noida / Mumbai', '', 'UP', '', '');
INSERT INTO `CONTACT_US` VALUES (62, '62', 'Rajouri Garden', 'Neha Sahni', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'J-198, First Floor, MAIN NAJAFGARH ROAD, RAJOURI GARDEN, NEW DELHI- 110027', '62', 'Delhi', 'Y', 'DE00', '28.641418', '77.121183');
INSERT INTO `CONTACT_US` VALUES (61, '61', 'Ghatkopar', 'Rajeshwar Rao', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. Shop No. 18A & 18B, Kailash Plaza,  Next to ICICI Bank, Vallabhbaug Lane,  Garodia Nagar, Ghatkopar East  Mumbai - 400 077', '61', 'Mumbai', '', 'MH', '19.230568', '72.853845');
INSERT INTO `CONTACT_US` VALUES (63, '63', 'LAXMI NAGAR ', 'Mona Sahni', '18004196299/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge India  ltd. D-100, street No.5, Ground floor, Laxmi Nagar, Delhi-110092 Landmark: Near Laxmi nagar Metro station.', '63', 'Delhi', 'Y', 'DE00', '28.681025', '77.199039');
INSERT INTO `CONTACT_US` VALUES (64, '64', 'Andheri West', 'Pushpalata Shetty/Sudeepta Mukherjee', '022-67029730//32', '09324086055, 09323141740', 'Info Edge (India) Ltd. Shop No. 2, Bhavesha Co-op Housing Society,  Veera Desai Road, Opp Andheri Sports Complex,  Andheri West, Mumbai Ã¯Â¿Â½ 400 058', '64', 'Mumbai', 'Y', 'MH', '19.114874', '72.857495');
INSERT INTO `CONTACT_US` VALUES (65, '65', 'Agra', 'Vandana/ Ritu', '18004196299/ 0120-4393500', '-', 'Info Edge (India) Ltd. 1/133, Upper Ground Floor, Friends Shoppe, Hariparvat Crossing,  M.G.Road,Opposite Holiday Inn, Agra', '65', 'Agra', 'Y', 'UP', '17.700941', '83.249846');
INSERT INTO `CONTACT_US` VALUES (66, '66', 'Kanpur', 'Archie Gupta/Pravida Srivastav', '18004196299 (toll free)/ 0120-4393500', '18004196299/ 0120-4393500', 'Info Edge (India) Ltd. 14/121-B, Main Road, The Mall,  Parade, Opposite Raymonds Showroom,  Kanpur', '66', 'Kanpur', 'Y', 'UP', '27.196542', '78.002514');
INSERT INTO `CONTACT_US` VALUES (67, '67', 'J M Road', 'Shashank /Kavita', '09324086055, 09323141740', '09324086055, 09323141740', 'Info Edge (India) Ltd. Office.No B-1, Basement ,  CIFCO Centre, J M Road, Next to Shiv Sagar Restaurant,  Pune Deccan, Pune - 411043.Email bharat.vaswani@jeevansathi.com', '67', 'Pune', 'Y', 'MH', '23.079732', '81.914063');
INSERT INTO `CONTACT_US` VALUES (68, '68', 'Camp', 'Shashank\n/Kavita', '09324086055, 09323141740', '09324086055, 09323141740', '', '68', 'Pune', 'Y', 'MH', '22.105999', '81.914063');
