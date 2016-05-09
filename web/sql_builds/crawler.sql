-- MySQL dump 10.13  Distrib 5.1.47, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: crawler
-- ------------------------------------------------------
-- Server version	5.1.47

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `crawler_JS_competition_blood_group_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_blood_group_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_blood_group_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of  blood group between JS and co';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_blood_group_values_mapping`
--

LOCK TABLES `crawler_JS_competition_blood_group_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_blood_group_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_blood_group_values_mapping` VALUES (2,'O+','','7'),(1,'A+','','1'),(1,'A-','','2'),(1,'B+','','3'),(1,'B-','','4'),(1,'O+','','7'),(1,'O-','','8'),(1,'AB+','','5'),(1,'AB-','','6'),(2,'A+','','1'),(2,'A-','','2'),(2,'AB+','','5'),(2,'AB-','','6'),(2,'B+','','3'),(2,'B-','','4'),(2,'O+','','7'),(2,'O-','','8');
/*!40000 ALTER TABLE `crawler_JS_competition_blood_group_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_caste_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_caste_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_caste_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of caste between JS and competiti';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_caste_values_mapping`
--

LOCK TABLES `crawler_JS_competition_caste_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_caste_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_caste_values_mapping` VALUES (1,'Brahmin','',25),(1,'Adi Dravida','',16),(1,'Agri','',127),(1,'Ahom','',127),(1,'Ambalavasi','',127),(1,'Arora','',18),(1,'Arunthathiyar','',127),(1,'Arya Vysya','',19),(1,'Baghel/Pal/Gaderiya','',127),(1,'Baidya','',127),(1,'Baishnab','',127),(1,'Baishya','',127),(1,'Balija','',127),(1,'Banik','',127),(1,'Bari','',127),(1,'Barujibi','',127),(1,'Besta','',127),(1,'Bhandari','',22),(1,'Bhatia','',127),(1,'Bhavsar','',23),(1,'Bhovi','',127),(1,'Billava','',24),(1,'Boyer','',127),(1,'Brahmbatt','',127),(1,'Brahmin','',25),(1,'Brahmin - Anavil','',27),(1,'Brahmin - Audichya','',127),(1,'Brahmin - Barendra','',127),(1,'Brahmin - Bhatt','',127),(1,'Brahmin - Bhumihar','',28),(1,'Brahmin - Deshastha','',30),(1,'Brahmin - Dhiman','',127),(1,'Brahmin - Dravida','',127),(1,'Brahmin - Garhwali','',31),(1,'Brahmin - Goswami','',34),(1,'Brahmin - Halua','',127),(1,'Brahmin - Havyaka','',35),(1,'Brahmin - Hoysala','',127),(1,'Brahmin - Iyengar','',65),(1,'Brahmin - Iyer','',66),(1,'Brahmin - Jhadua','',127),(1,'Brahmin - Jhijhotiya','',127),(1,'Brahmin - Karhade','',37),(1,'Brahmin - Kashmiri Pandit','',38),(1,'Brahmin - Kota','',127),(1,'Brahmin - Kulin','',127),(1,'Brahmin - Kumaoni','',40),(1,'Brahmin - Madhwa','',41),(1,'Brahmin - Modh','',127),(1,'Brahmin - Mohyal','',127),(1,'Brahmin - Nagar','',43),(1,'Brahmin - Panda','',127),(1,'Brahmin - Pareek','',127),(1,'Brahmin - Pushkarna','',127),(1,'Brahmin - Rarhi','',127),(1,'Brahmin - Rudraj','',127),(1,'Brahmin - Sakaldwipi','',127),(1,'Brahmin - Sanadya','',127),(1,'Brahmin - Sanketi','',127),(1,'Brahmin - Saraswat','',46),(1,'Brahmin - Saryuparin','',47),(1,'Brahmin - Shrimali','',127),(1,'Brahmin - Smartha','',48),(1,'Brahmin - Sri Vishnava','',127),(1,'Brahmin - Tyagi','',127),(1,'Brahmin - Vaidiki','',49),(1,'Brahmin - Viswa','',50),(1,'Brahmin - Vyas','',127),(1,'Brahmo','',127),(1,'Bunt','',51),(1,'Chambhar','',52),(1,'Chandravanshi Kahar','',127),(1,'Chasa','',127),(1,'Chaudary','',127),(1,'Chaurasia','',53),(1,'Chettiar','',54),(1,'Chhetri','',127),(1,'CKP','',55),(1,'Coorgi','',56),(1,'Devanga','',57),(1,'Dhangar','',58),(1,'Dheevara','',127),(1,'Dhiman','',127),(1,'Dhoba','',127),(1,'Dhobi','',127),(1,'Ezhava','',59),(1,'Ezhuthachan','',127),(1,'Gabit','',127),(1,'Gandla','',127),(1,'Ganiga','',127),(1,'Garhwali','',127),(1,'Gavali','',127),(1,'Gavara','',127),(1,'Ghumar','',127),(1,'Goala','',127),(1,'Goan','',127),(1,'Goud','',60),(1,'Gounder','',61),(1,'Gowda','',62),(1,'Gudia','',127),(1,'Gujjar','',63),(1,'Gupta','',64),(1,'Hegde','',127),(1,'Jaiswal','',70),(1,'Jangam','',127),(1,'Jat','',71),(1,'Jatav','',72),(1,'Kaibarta','',127),(1,'Kalar','',74),(1,'Kalinga','',127),(1,'Kalita','',127),(1,'Kalwar','',127),(1,'Kamboj','',127),(1,'Kamma','',73),(1,'Kansari','',127),(1,'Kapu','',75),(1,'Karana','',127),(1,'Karmakar','',127),(1,'Karuneegar','',127),(1,'Kasar','',127),(1,'Kashyap','',127),(1,'Kayastha','',76),(1,'Khandayat','',77),(1,'Khandelwal','',127),(1,'Kharwar','',127),(1,'Khatik','',127),(1,'Khatri','',78),(1,'Koli','',79),(1,'Kongu Vellala Gounder','',80),(1,'Konkani','',127),(1,'Kori','',127),(1,'Koshti','',81),(1,'Kshatriya','',82),(1,'Kshatriya - Agnikula','',127),(1,'Kudumbi','',127),(1,'Kulalar','',127),(1,'Kulita','',127),(1,'Kumawat','',83),(1,'Kumhar','',127),(1,'Kummari','',127),(1,'Kunbi','',84),(1,'Kurmi','',85),(1,'Kuruba','',86),(1,'Kuruhina Shetty','',127),(1,'Kurumbar','',127),(1,'Kushwaha','',87),(1,'Kutchi','',127),(1,'Leva Patil','',127),(1,'Lingayat','',89),(1,'Lohana','',90),(1,'Lohar','',127),(1,'Lubana','',127),(1,'Madiga','',127),(1,'Mahajan','',127),(1,'Mahar','',127),(1,'Maheshwari','',91),(1,'Mahindra','',127),(1,'Majabi','',127),(1,'Mala','',127),(1,'Mali','',93),(1,'Mapila','',127),(1,'Maratha','',94),(1,'Maruthuvar','',127),(1,'Marwari','',95),(1,'Matang','',127),(1,'Maurya','',96),(1,'Meena','',127),(1,'Meenavar','',127),(1,'Mehra','',127),(1,'Menon','',97),(1,'Meru Darji','',127),(1,'Modak','',127),(1,'Mogaveera','',100),(1,'Monchi','',127),(1,'Mudaliar','',98),(1,'Mudaliar - Arcot','',99),(1,'Mudiraj','',127),(1,'Mukkulathor','',127),(1,'Muthuraja','',127),(1,'Nadar','',101),(1,'Naicker','',127),(1,'Naidu','',102),(1,'Naik','',127),(1,'Nair','',103),(1,'Nair -  Vilakkithala','',127),(1,'Namasudra','',127),(1,'Nambiar','',104),(1,'Namosudra','',127),(1,'Napit','',127),(1,'Nepali','',106),(1,'Nhavi','',127),(1,'Oswal','',127),(1,'Padmashali','',108),(1,'Panchal','',127),(1,'Panicker','',127),(1,'Parkava Kulam','',127),(1,'Patel','',109),(1,'Patel - Dodia','',127),(1,'Patel - Kadva','',110),(1,'Patel - Leva','',111),(1,'Patnaick','',127),(1,'Patra','',127),(1,'Pillai','',114),(1,'Prajapati','',115),(1,'Rajaka','',127),(1,'Rajput','',116),(1,'Rajput - Garhwali','',117),(1,'Rajput - Kumaoni','',118),(1,'Ramdasia','',127),(1,'Ravidasia','',127),(1,'Rawat','',127),(1,'Reddy','',119),(1,'Saha','',127),(1,'Sahu','',127),(1,'Saini','',127),(1,'Saliya','',127),(1,'Scheduled Caste','',121),(1,'Scheduled Tribe','',127),(1,'Senai Thalaivar','',127),(1,'Shah','',127),(1,'Shimpi','',122),(1,'Sindhi','',123),(1,'Somvanshi','',124),(1,'Sonar','',125),(1,'Soni','',127),(1,'Sozhiya Vellalar','',126),(1,'Srisayani','',127),(1,'SSK','',127),(1,'Subarna Banik','',127),(1,'Sundhi','',127),(1,'Sutar','',127),(1,'Tamboli','',127),(1,'Tanti','',127),(1,'Tantuway','',127),(1,'Telaga','',127),(1,'Teli','',127),(1,'Thakkar','',127),(1,'Thakur','',127),(1,'Thevar','',127),(1,'Thigala','',127),(1,'Thiyya','',127),(1,'Uppara','',127),(1,'Vaddera','',127),(1,'Vaish','',127),(1,'Vaishnav','',127),(1,'Vaishnava','',127),(1,'Vaishya','',127),(1,'Valmiki','',127),(1,'Vankar','',127),(1,'Vannar','',127),(1,'Vanniyar','',127),(1,'Varshney','',127),(1,'Veera Saivam','',127),(1,'Veerashaiva','',127),(1,'Vellalar','',127),(1,'Vishwakarma','',127),(1,'Vysya','',127),(1,'Yadav','',127),(1,'Agarwal','',17),(1,'Naidu - Balija','',21),(1,'Baniya','',20),(1,'Kshatriya - Bhavasar','',127),(1,'6000 Niyogi','',26),(1,'Brahmin - Davadnya','',29),(1,'Brahmin - Danua','',127),(1,'Brahmin - Gowd Saraswat','',32),(1,'Brahmin - Gour','',33),(1,'Brahmin - Kanyakubja','',36),(1,'Brahmin - Kokanastha','',39),(1,'Brahmin - Maithili','',42),(1,'Brahmin - Shivhalli','',127),(1,'Devadiga','',127),(1,'Devendra Kula Vellalar','',127),(1,'Ediga','',127),(1,'Mannuru Kapu','',127),(1,'Kumbhakar/Kumbhar','',127),(1,'Mahendra','',127),(1,'Mahishya','',92),(1,'Malla','',127),(1,'Mukulathur','',127),(1,'Nayak','',127),(1,'Nai','',107),(1,'OBC (Barber Naayee)','',107),(1,'Ramgharia','',127),(1,'Sadgop','',120),(1,'Swarnakar','',127),(1,'Vanniyakullak Kshatriya','',127),(1,'Nair -  Velethadathu','',127),(1,'Veluthedathu Nair','',127),(1,'Vellama','',127),(1,'Viswabrahmin','',127),(1,'Vokaliga','',127),(1,'Born Again ','',3),(1,'Brethren ','',127),(1,'Catholic - Knanaya ','',127),(1,'Catholic - Malankara ','',127),(1,'Catholic - Roman ','',127),(1,'Catholic - Syrian ','',127),(1,'Evangelical ','',7),(1,'Indian Orthodox ','',127),(1,'Jacobite ','',8),(1,'Jacobite - Knanaya ','',127),(1,'Knanaya ','',127),(1,'Manglorean ','',127),(1,'Marthomite ','',9),(1,'Nadar ','',10),(1,'Pentecost ','',13),(1,'Protestant ','',11),(1,'Syrian ','',12),(1,'Syrian - Orthodox ','',127),(1,'Syro - Malabar ','',127),(1,'Digambar','',127),(1,'Shwetamber','',127),(1,'Shia','',127),(1,'Sunni','',127),(1,'Arora','',127),(1,'Gursikh','',127),(1,'Jat','',127),(1,'Kamboj','',127),(1,'Kesadhari','',127),(1,'Khatri','',127),(1,'Khashap Rajpoot','',127),(1,'Labana','',127),(1,'Mazhbi','',127),(1,'Ramdasia','',127),(1,'Ramgarhia','',127),(1,'Saini','',127),(2,'Ad Dharmi','',127),(2,'Adi Dravida','',16),(2,'Agri','',127),(2,'Ahom','',127),(2,'Ambalavasi','',127),(2,'Arora','',18),(2,'Arunthathiyar','',127),(2,'Arya Vysya','',19),(2,'Baidya','',127),(2,'Baishnab','',127),(2,'Baishya','',127),(2,'Balija','',127),(2,'Banik','',127),(2,'Bari','',127),(2,'Barujibi','',127),(2,'Besta','',127),(2,'Bhandari','',22),(2,'Bhatia','',127),(2,'Bhavasar Kshatriya','',127),(2,'Bhovi','',127),(2,'Billava','',24),(2,'Boyer','',127),(2,'Brahmbatt','',127),(2,'Brahmin Anavil','',27),(2,'Brahmin Audichya','',127),(2,'Brahmin Barendra','',127),(2,'Brahmin Bhatt','',127),(2,'Brahmin Bhumihar','',28),(2,'Brahmin Daivadnya','',29),(2,'Brahmin Deshastha','',30),(2,'Brahmin Dhiman','',127),(2,'Brahmin Dravida','',127),(2,'Brahmin Garhwali','',31),(2,'Brahmin Gaur','',33),(2,'Brahmin Halua','',127),(2,'Brahmin Havyaka','',35),(2,'Brahmin Hoysala','',127),(2,'Brahmin Iyengar','',65),(2,'Brahmin Iyer','',66),(2,'Brahmin Jhadua','',127),(2,'Brahmin Kanyakubj','',36),(2,'Brahmin Karhade','',37),(2,'Brahmin Kota','',127),(2,'Brahmin Kulin','',127),(2,'Brahmin Madhwa','',41),(2,'Brahmin Maithil','',42),(2,'Brahmin Modh','',127),(2,'Brahmin Mohyal','',127),(2,'Brahmin Nagar','',43),(2,'Brahmin Namboodiri','',127),(2,'Brahmin Narmadiya','',44),(2,'Brahmin Niyogi','',127),(2,'Brahmin Panda','',127),(2,'Brahmin Pandit','',127),(2,'Brahmin Pushkarna','',127),(2,'Brahmin Rarhi','',127),(2,'Brahmin Rigvedi','',45),(2,'Brahmin Rudraj','',127),(2,'Brahmin Sakaldwipi','',127),(2,'Brahmin Sanadya','',127),(2,'Brahmin Sanketi','',127),(2,'Brahmin Saraswat','',46),(2,'Brahmin Saryuparin','',47),(2,'Brahmin Shrimali','',127),(2,'Brahmin Smartha','',48),(2,'Brahmin Tyagi','',127),(2,'Brahmin Vaidiki','',49),(2,'Brahmin Vyas','',127),(2,'Chambhar','',52),(2,'Chandravanshi Kahar','',127),(2,'Chasa','',127),(2,'Chaudary','',127),(2,'Chaurasia','',53),(2,'Chettiar','',54),(2,'Chhetri','',127),(2,'CKP','',55),(2,'Coorgi','',56),(2,'Devandra Kula Vellalar','',127),(2,'Devang Koshthi','',127),(2,'Devanga','',57),(2,'Dhangar','',58),(2,'Dheevara','',127),(2,'Dhiman','',127),(2,'Dhoba','',127),(2,'Dhobi','',127),(2,'Ezhava','',59),(2,'Ezhuthachan','',127),(2,'Gabit','',127),(2,'Gandla','',127),(2,'Ganiga','',127),(2,'Garhwali','',127),(2,'Gavara','',127),(2,'Ghumar','',127),(2,'Goala','',127),(2,'Goan','',127),(2,'Goud','',60),(2,'Gounder','',61),(2,'Gowda','',62),(2,'Gudia','',127),(2,'Gujjar','',63),(2,'Gupta','',64),(2,'Jaiswal','',70),(2,'Jangam','',127),(2,'Jat','',71),(2,'Jatav','',72),(2,'Kadava Patel','',127),(2,'Kaibarta','',127),(2,'Kalar','',74),(2,'Kalinga','',127),(2,'Kalita','',127),(2,'Kalwar','',127),(2,'Kamboj','',127),(2,'Kamma','',73),(2,'Kansari','',127),(2,'Kapu','',75),(2,'Karana','',127),(2,'Karmakar','',127),(2,'Karuneegar','',127),(2,'Kasar','',127),(2,'Kashyap','',127),(2,'Kayastha','',76),(2,'Khandayat','',77),(2,'Khandelwal','',127),(2,'Kharwar','',127),(2,'Khatri','',78),(2,'Koli','',79),(2,'Kongu Vellala Gounder','',80),(2,'Konkani','',127),(2,'Kori','',127),(2,'Kshatriya','',82),(2,'Kudumbi','',127),(2,'Kulalar','',127),(2,'Kulita','',127),(2,'Kumawat','',83),(2,'Kumbhar','',127),(2,'Kumhar','',127),(2,'Kummari','',127),(2,'Kunbi','',84),(2,'Kurmi','',85),(2,'Kurmi Kshatriya','',127),(2,'Kuruba','',86),(2,'Kuruhina Shetty','',127),(2,'Kurumbar','',127),(2,'Kutchi','',127),(2,'Lambadi','',127),(2,'Leva patel','',127),(2,'Leva patil','',127),(2,'Lohana','',90),(2,'Lohar','',127),(2,'Lubana','',127),(2,'Madiga','',127),(2,'Mahajan','',127),(2,'Mahar','',127),(2,'Maheshwari','',91),(2,'Majabi','',127),(2,'Mala','',127),(2,'Mali','',93),(2,'Manipuri','',127),(2,'Mapila','',127),(2,'Maratha','',94),(2,'Maruthuvar','',127),(2,'Matang','',127),(2,'Meena','',127),(2,'Meenavar','',127),(2,'Mehra','',127),(2,'Meru Darji','',127),(2,'Modak','',127),(2,'Mogaveera','',100),(2,'Mudiraj','',127),(2,'Mukkulathor','',127),(2,'Muthuraja','',127),(2,'Nadar','',101),(2,'Naicker','',127),(2,'Naidu','',102),(2,'Naik','',127),(2,'Nair','',103),(2,'Nambiar','',104),(2,'Namosudra','',127),(2,'Napit','',127),(2,'Nayaka','',127),(2,'Nepali','',106),(2,'Nhavi','',127),(2,'Oswal','',127),(2,'Pal','',127),(2,'Panchal','',127),(2,'Panicker','',127),(2,'Parkava Kulam','',127),(2,'Pasi','',127),(2,'Patel','',109),(2,'Patra','',127),(2,'Pillai','',114),(2,'Prajapati','',115),(2,'Rajaka','',127),(2,'Rajbonshi','',127),(2,'Rajput','',116),(2,'Ramdasia','',127),(2,'Ravidasia','',127),(2,'Rawat','',127),(2,'Reddy','',119),(2,'Sadgope','',120),(2,'Saha','',127),(2,'Sahu','',127),(2,'Saini','',127),(2,'Saliya','',127),(2,'Senai Thalaivar','',127),(2,'Settibalija','',127),(2,'Shimpi','',122),(2,'Sindhi','',123),(2,'Sonar','',125),(2,'Soni','',127),(2,'Sourashtra','',127),(2,'Sozhiya Vellalar','',126),(2,'Sundhi','',127),(2,'Swakula Sali','',127),(2,'Tamboli','',127),(2,'Tanti','',127),(2,'Telaga','',127),(2,'Teli','',127),(2,'Thakkar','',127),(2,'Thakur','',127),(2,'Thigala','',127),(2,'Thiyya','',127),(2,'Uppara','',127),(2,'Vaddera','',127),(2,'Vaish','',127),(2,'Vaishnav','',127),(2,'Vaishnava','',127),(2,'Vaishya','',127),(2,'Vaishya Vani','',127),(2,'Valmiki','',127),(2,'Vania','',127),(2,'Vaniya','',127),(2,'Vanjari','',127),(2,'Vankar','',127),(2,'Vannar','',127),(2,'Vannia Kula Kshatriyar','',127),(2,'Varshney','',127),(2,'Veera Saivam','',127),(2,'Velama','',127),(2,'Vellalar','',127),(2,'Vilakkithala Nair','',127),(2,'Vokkaliga','',127),(2,'Vysya','',127),(2,'Yadav','',127),(2,'Agarwal','',17),(2,'Baniya','',20),(2,'Anavil Brahmin','',27),(2,'Audichya Brahmin','',127),(2,'Barendra Brahmin','',127),(2,'Bhatt Brahmin','',127),(2,'Bhumihar Brahmin','',28),(2,'Daivadnya Brahmin','',29),(2,'Deshastha Brahmin','',30),(2,'Dhiman Brahmin','',127),(2,'Dravida Brahmin','',127),(2,'Brahmin Danua','',127),(2,'Danua Brahmin','',127),(2,'Garhwali Brahmin','',31),(2,'Gaur Brahmin','',33),(2,'Goswami/Gosavi Brahmin','',34),(2,'Brahmin Goswami/Gosavi','',34),(2,'Halua Brahmin','',127),(2,'Havyaka Brahmin','',35),(2,'Hoysala Brahmin','',127),(2,'Iyengar Brahmin','',65),(2,'Iyer Brahmin','',66),(2,'Jhadua Brahmin','',127),(2,'Kanyakubj Brahmin','',36),(2,'Karhade Brahmin','',37),(2,'Brahmin Kokanastha','',39),(2,'Kokanastha Brahmin','',39),(2,'Kota Brahmin','',127),(2,'Kulin Brahmin','',127),(2,'Brahmin Kumoani','',40),(2,'Kumoani Brahmin','',40),(2,'Madhwa Brahmin','',41),(2,'Maithil Brahmin','',42),(2,'Modh Brahmin','',127),(2,'Mohyal Brahmin','',127),(2,'Nagar Brahmin','',43),(2,'Namboodiri Brahmin','',127),(2,'Narmadiya Brahmin','',44),(2,'Niyogi Brahmin','',127),(2,'Panda Brahmin','',127),(2,'Pandit Brahmin','',127),(2,'Pushkarna Brahmin  ','',127),(2,'Rarhi Brahmin  ','',127),(2,'Rigvedi Brahmin  ','',45),(2,'Rudraj Brahmin  ','',127),(2,'Sakaldwipi Brahmin  ','',127),(2,'Sanadya Brahmin  ','',127),(2,'Sanketi Brahmin  ','',127),(2,'Saraswat Brahmin  ','',46),(2,'Saryuparin Brahmin  ','',47),(2,'Brahmin Shivhalli','',127),(2,'Shivhalli Brahmin  ','',127),(2,'Shrimali Brahmin  ','',127),(2,'Smartha Brahmin  ','',48),(2,'Brahmin Sri Vaishnava','',127),(2,'Sri Vaishnava Brahmin  ','',127),(2,'Tyagi Brahmin  ','',127),(2,'Vaidiki Brahmin  ','',49),(2,'Vyas Brahmin  ','',127),(2,'Bunt (Shetty)','',51),(2,'Devadiga','',127),(2,'Ediga','',127),(2,'Gawali','',127),(2,'Munnuru Kapu','',127),(2,'Agnikula Kshatriya','',127),(2,'Mahendra','',127),(2,'Mahishya','',92),(2,'Malla','',127),(2,'Mudaliyar','',98),(2,'Nai','',107),(2,'Patnaick/Sistakaranam','',127),(2,'Ramgariah','',127),(2,'SC','',121),(2,'ST','',127),(2,'SKP','',127),(2,'Srisayana','',127),(2,'Veluthedathu Nair','',127),(2,'Viswabrahmin','',127),(2,'Viswakarma','',127),(2,'Born Again ','',3),(2,'Brethren ','',127),(2,'Catholic - Knanaya ','',127),(2,'Catholic - Latin ','',127),(2,'Catholic - Roman ','',127),(2,'Catholic - Syrian ','',127),(2,'Evangelical ','',7),(2,'Jacobite ','',8),(2,'Jacobite - Knanaya ','',127),(2,'Jacobite - Syrian ','',127),(2,'Knanaya ','',127),(2,'Marthomite ','',9),(2,'Pentecost ','',13),(2,'Syrian - Orthodox ','',127),(2,'Syro - Malabar ','',127),(2,'Arora','',127),(2,'Jat','',127),(2,'Kamboj','',127),(2,'Khatri','',127),(2,'Khashap Rajpoot','',127),(2,'Mazhbi','',127),(2,'Ramdasia','',127),(2,'Ramgarhia','',127),(2,'Saini','',127),(3,'Adi Dravida','',16),(3,'Agri','',127),(3,'Ahom','',127),(3,'Ambalavasi','',127),(3,'Arora','',18),(3,'Arunthathiyar','',127),(3,'Arya Vysya','',19),(3,'Baidya','',127),(3,'Balija Naidu','',21),(3,'Banik','',127),(3,'Bari','',127),(3,'Barujibi','',127),(3,'Besta','',127),(3,'Bhandari','',22),(3,'Bhatia','',127),(3,'Bhavsar','',23),(3,'Bhovi','',127),(3,'Billava','',24),(3,'Boyer','',127),(3,'Brahmin','',25),(3,'Brahmin 6000 Niyogi','',26),(3,'Brahmin Barendra','',127),(3,'Brahmin Bhumihar','',28),(3,'Brahmin Gaur','',33),(3,'Brahmin Goswami','',34),(3,'Brahmin Iyengar','',65),(3,'Brahmin Kulin','',127),(3,'Brahmin Madhwa','',41),(3,'Brahmin Narmadiya','',44),(3,'Brahmin Pushkarna','',127),(3,'Brahmin Rarhi','',127),(3,'Brahmin Rigvedi','',45),(3,'Brahmin Rudraj','',127),(3,'Brahmin Sanadya','',127),(3,'Brahmin Saryuparin','',47),(3,'Brahmin Shivalli','',127),(3,'Brahmin Smartha','',48),(3,'Brahmin Tyagi','',127),(3,'Brahmin Viswa','',50),(3,'Bunt','',51),(3,'Chambhar','',52),(3,'Chaurasia','',53),(3,'Chettiar','',54),(3,'Chhetri','',127),(3,'CKP','',55),(3,'Coorgi','',56),(3,'Devanga','',57),(3,'Dhangar','',58),(3,'Dheevara','',127),(3,'Dhobi','',127),(3,'Ezhava','',59),(3,'Ezhuthachan','',127),(3,'Gandla','',127),(3,'Ganiga','',127),(3,'Ganigashetty','',127),(3,'Garhwali','',127),(3,'Gavali','',127),(3,'Gavara','',127),(3,'Ghumar','',127),(3,'Goala','',127),(3,'Goud','',60),(3,'Gounder','',61),(3,'Gowda','',62),(3,'Gupta','',64),(3,'Hegde','',127),(3,'Jaiswal','',70),(3,'Jat','',71),(3,'Jatav','',72),(3,'Kaibarta','',127),(3,'Kalar','',74),(3,'Kamboj','',127),(3,'Kamma','',73),(3,'Kapu','',75),(3,'Kapu Munnuru','',127),(3,'Karana','',127),(3,'Karmakar','',127),(3,'Kashyap','',127),(3,'Kayastha','',76),(3,'Khandayat','',77),(3,'Khandelwal','',127),(3,'Khatik','',127),(3,'Khatri','',78),(3,'Koli','',79),(3,'Kongu Vellala Gounder','',80),(3,'Kori','',127),(3,'Koshti','',81),(3,'Kshatriya','',82),(3,'Kulalar','',127),(3,'Kumawat','',83),(3,'Kummari','',127),(3,'Kunbi','',84),(3,'Kurmi','',85),(3,'Kuruba','',86),(3,'Kurumbar','',127),(3,'Kushwaha','',87),(3,'Leva Patidar','',88),(3,'Leva Patil','',127),(3,'Lingayat','',89),(3,'Lohana','',90),(3,'Lohar','',127),(3,'Lubana','',127),(3,'Madiga','',127),(3,'Mahajan','',127),(3,'Maheshwari','',91),(3,'Mahisya','',92),(3,'Mala','',127),(3,'Mali','',93),(3,'Mallah','',127),(3,'Maratha','',94),(3,'Maruthuvar','',127),(3,'Marwari','',95),(3,'Maurya','',96),(3,'Meena','',127),(3,'Meenavar','',127),(3,'Menon','',97),(3,'Meru','',127),(3,'Meru Darji','',127),(3,'Modak','',127),(3,'Mogaveera','',100),(3,'Monchi','',127),(3,'Mudaliar','',98),(3,'Mudaliar Arcot','',99),(3,'Mudiraj','',127),(3,'Muthuraja','',127),(3,'Nadar','',101),(3,'Naicker','',127),(3,'Naidu','',102),(3,'Nair','',103),(3,'Nair Vilakkithala','',127),(3,'Namasudra','',127),(3,'Nambiar','',104),(3,'Namboodiri','',105),(3,'Napit','',127),(3,'Nepali','',106),(3,'Oswal','',127),(3,'Padmashali','',108),(3,'Panicker','',127),(3,'Parkava Kulam','',127),(3,'Patel','',109),(3,'Patel Dodia','',127),(3,'Patel Kadva','',110),(3,'Patel Leva','',111),(3,'Patil','',112),(3,'Patil Leva','',113),(3,'Patnaick','',127),(3,'Pillai','',114),(3,'Prajapati','',115),(3,'Rajaka','',127),(3,'Rajput','',116),(3,'Rajput Rohella/Tank','',127),(3,'Reddy','',119),(3,'Sadgope','',120),(3,'Saha','',127),(3,'Sahu','',127),(3,'Saini','',127),(3,'Saliya','',127),(3,'Scheduled Caste','',121),(3,'Scheduled Tribe','',127),(3,'Senai Thalaivar','',127),(3,'Shah','',127),(3,'Shetty','',127),(3,'Shimpi','',122),(3,'Sindhi','',123),(3,'Somvanshi','',124),(3,'Sonar','',125),(3,'Sozhiya Vellalar','',126),(3,'Srisayani','',127),(3,'Sutar','',127),(3,'Tamboli','',127),(3,'Tantuway','',127),(3,'Telaga','',127),(3,'Teli','',127),(3,'Thevar','',127),(3,'Thigala','',127),(3,'Thiyya','',127),(3,'Uppara','',127),(3,'Vaddera','',127),(3,'Vaishnav','',127),(3,'Vaishnav Vanik','',127),(3,'Valmiki','',127),(3,'Vanjari','',127),(3,'Vankar','',127),(3,'Vannar','',127),(3,'Vanniyar','',127),(3,'Varshney','',127),(3,'Veera Saivam','',127),(3,'Veerashaiva','',127),(3,'Vellalar','',127),(3,'Vellalar Devandra Kula','',127),(3,'Vishwakarma','',127),(3,'Vysya','',127),(3,'Yadav','',127),(3,'Agarwal','',17),(3,'Vaish/Baniya','',127),(3,'Anavil Brahmin','',27),(3,'Audichya Brahmin','',127),(3,'Davadnya Brahmin','',29),(3,'Deshastha Brahmin','',30),(3,'Dhiman Brahmin','',127),(3,'Garhwali Brahmins','',31),(3,'Gowd Saraswat Brahmin','',32),(3,'Havyaka Brahmin','',35),(3,'Iyer Brahmin','',66),(3,'Brahmin Kanyakubja','',36),(3,'Karhade Brahmin','',37),(3,'Kashmiri Pandit Brahmin','',38),(3,'Brahmin Kokanastha','',39),(3,'Kumaoni Brahmins','',40),(3,'Maithil Brahmin','',42),(3,'Nagar Brahmin','',43),(3,'Malayalee Namboodiri','',127),(3,'Saraswat Brahmins','',46),(3,'Vaidiki Brahmin','',49),(3,'Chandraseniya Kayastha Prabhu','',55),(3,'Devadiga','',127),(3,'Devendra Kula Vellalar','',127),(3,'Ediga','',127),(3,'Mannuru Kapu','',127),(3,'Karuneekar','',127),(3,'Kumbara','',127),(3,'Lambani','',127),(3,'Mukulathur','',127),(3,'Naik/Nayaka','',127),(3,'Naik/Nayaka','',127),(3,'OBC/Barber/Naayee','',107),(3,'Garhwali Rajput','',117),(3,'Kumaoni Rajput','',118),(3,'Saurashtra','',127),(3,'Swarnakar','',127),(3,'Vaish/Baniya','',127),(3,'Vanniyakullak Shatriya','',127),(3,'Velethadathu Nair','',127),(3,'Vellama','',127),(3,'Viswabrahmin','',127),(3,'Vokaliga','',127),(3,'Tamil Yadava','',127),(3,'Brahmin Sakaldwipiya','',127),(3,'Hindu-Others','',127),(3,'Born Again','',3),(3,'Bretheren','',127),(3,'Catholic','',4),(3,'Malankara','',127),(3,'CMS','',5),(3,'CSI','',6),(3,'Orthodox','',127),(3,'Jacobite','',8),(3,'Knanaya','',127),(3,'Nadar','',10),(3,'Pentacost','',13),(3,'Protestant','',11),(3,'Arora','',127),(3,'Gursikh','',127),(3,'Jat','',127),(3,'Kambhoj','',127),(3,'Kesadhari','',127),(3,'Khatri','',127),(3,'Rajput','',127),(3,'Lubana','',127),(3,'Majabi','',127),(3,'Ramdasia','',127),(3,'Ramgharia','',127),(3,'Saini','',127),(3,'Sikh-Others','',127);
/*!40000 ALTER TABLE `crawler_JS_competition_caste_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_citizenship_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_citizenship_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_citizenship_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` smallint(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of citizenship between JS and com';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_citizenship_values_mapping`
--

LOCK TABLES `crawler_JS_competition_citizenship_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_citizenship_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_citizenship_values_mapping` VALUES (2,'Citizen','',1),(1,'Citizen','',1),(1,'Permanent Resident','',2),(1,'Student Visa','',4),(1,'Temporary Visa','',5),(1,'Work Permit','',3),(2,'Citizen','',1),(2,'Permanent Resident','',2),(2,'Work Permit','',3),(2,'Student Visa','',4),(2,'Temporary Visa','',5);
/*!40000 ALTER TABLE `crawler_JS_competition_citizenship_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_city_res_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_city_res_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_city_res_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(100) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` varchar(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of city of residence between JS a';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_city_res_values_mapping`
--

LOCK TABLES `crawler_JS_competition_city_res_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_city_res_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_city_res_values_mapping` VALUES (1,'Agra','','UP25'),(1,'Gurgaon','','HA03'),(2,'Bangalore','','KA02'),(3,'Delhi','','DE00'),(1,'Agartala','','TR01'),(1,'Agra','','UP01'),(1,'Ahmedabad','','GU01'),(1,'Ahmednagar','','UP31'),(1,'Ajmer','','RA01'),(1,'Akola','','MH01'),(1,'Aligarh','','UP02'),(1,'Allahabad','','UP03'),(1,'Alwar','','RA02'),(1,'Ambala','','HA01'),(1,'Amravati','','AP13'),(1,'Amritsar','','PU01'),(1,'Amroha','','UP32'),(1,'Anand','','GU02'),(1,'Anantapur','','AP14'),(1,'Asansol','','WB01'),(1,'Aurangabad','','MH02'),(1,'Bahraich','','UP33'),(1,'Baleshwar','','OR05'),(1,'Balurghat','','WB08'),(1,'Bangalore','','KA02'),(1,'Bankura','','WB09'),(1,'Baranagar','','WB10'),(1,'Bareilly','','UP06'),(1,'Bathinda','','PU02'),(1,'Belgaum','','KA03'),(1,'Bellary','','KA10'),(1,'Bhagalpur','','BI09'),(1,'Bharatpur','','RA03'),(1,'Bhatpara','','WB13'),(1,'Bhilwara','','RA04'),(1,'Bhind','','MP13'),(1,'Bhopal','','MP02'),(1,'Bidar','','KA04'),(1,'Bijapur','','KA11'),(1,'Bikaner','','RA05'),(1,'Bilaspur','','MP03'),(1,'Budaun','','UP34'),(1,'Burhanpur','','MP14'),(1,'Chandigarh','','PH00'),(1,'Chandrapur','','MH16'),(1,'Chennai','','TN02'),(1,'Chhindwara','','MP04'),(1,'Coimbatore','','TN04'),(1,'Cuddalore','','TN05'),(1,'Cuttack','','OR02'),(1,'Damoh','','MP26'),(1,'Dehradun','','UP07'),(1,'Delhi','','DE00'),(1,'Dewas','','MP15'),(1,'Dhanbad','','BI02'),(1,'Dhule','','MH17'),(1,'Dibrugarh','','AS01'),(1,'Dimapur','','NA01'),(1,'Dispur','','AS02'),(1,'Durg','','MP06'),(1,'Durgapur','','WB03'),(1,'Eluru','','AP17'),(1,'Ernakulam','','KE13'),(1,'Erode','','TN06'),(1,'Etawah','','UP08'),(1,'Faizabad','','UP09'),(1,'Faridabad','','HA02'),(1,'Faridkot','','PU03'),(1,'Fatehpur','','UP10'),(1,'Firozabad','','UP11'),(1,'Gandhidham','','GU17'),(1,'Gandhinagar','','GU05'),(1,'Ganganagar','','RA06'),(1,'Gangtok','','SI01'),(1,'Gaya','','BI03'),(1,'Ghaziabad','','UP12'),(1,'Goa','','GO00'),(1,'Gondiya','','MH18'),(1,'Gorakhpur','','UP13'),(1,'Gulbarga','','KA06'),(1,'Guna','','MP16'),(1,'Guntur','','AP02'),(1,'Gurdaspur','','PU05'),(1,'Gurgaon','','HA03'),(1,'Guwahati','','AS03'),(1,'Gwalior','','MP07'),(1,'Haridwar','','UP15'),(1,'Hassan','','KA16'),(1,'Hathras','','UP37'),(1,'Hoshiarpur','','PU06'),(1,'Hyderabad','','1216'),(1,'Imphal','','MA01'),(1,'Indore','','MP08'),(1,'Itanagar','','AR01'),(1,'Jabalpur','','MP09'),(1,'Jaipur','','RA07'),(1,'Jaisalmer','','RA11'),(1,'Jalandhar','','PU10'),(1,'Jammu','','JK04'),(1,'Jamshedpur','','BI04'),(1,'Jhansi','','UP16'),(1,'Jodhpur','','RA08'),(1,'Jorhat','','AS04'),(1,'Junagadh','','GU06'),(1,'Kakinada','','AP04'),(1,'Kandla','','GU07'),(1,'Kannauj','','UP17'),(1,'Kannur','','KE14'),(1,'Kanpur','','UP18'),(1,'Katihar','','BI13'),(1,'Khammam','','AP22'),(1,'Khandwa','','MP17'),(1,'Kharagpur','','WB04'),(1,'Kochi','','1470'),(1,'Kohima','','NA02'),(1,'Kolhapur','','MH03'),(1,'Kolkata','','WB05'),(1,'Kollam','','KE15'),(1,'Kota','','RA09'),(1,'Kottayam','','KE04'),(1,'Kozhikode','','KE06'),(1,'Krishnanagar','','WB27'),(1,'Kurnool','','AP05'),(1,'Latur','','MH22'),(1,'Leh','','JK02'),(1,'Lucknow','','UP19'),(1,'Ludhiana','','PU07'),(1,'Machilipatnam','','AP06'),(1,'Madurai','','TN09'),(1,'Mahbubnagar','','AP23'),(1,'Malappuram','','KE16'),(1,'Malegaon','','MH23'),(1,'Mandya','','KA21'),(1,'Mangalore','','KA08'),(1,'Mathura','','UP20'),(1,'Meerut','','UP21'),(1,'Mirzapur','','UP39'),(1,'Moga','','PU15'),(1,'Moradabad','','UP22'),(1,'Morena','','MP18'),(1,'Mumbai','','MH04'),(1,'Munger','','BI14'),(1,'Muzaffarnagar','','UP24'),(1,'Muzaffarpur','','BI05'),(1,'Mysore','','KA09'),(1,'Nagpur','','MH05'),(1,'Nanded','','MH06'),(1,'Nashik','','MH24'),(1,'Navsari','','GU23'),(1,'Nellore','','AP07'),(1,'New Delhi','','DE00'),(1,'Nizamabad','','AP25'),(1,'Noida','','UP25'),(1,'Ongole','','AP26'),(1,'Palakkad','','KE07'),(1,'Palanpur','','GU08'),(1,'Pali','','RA14'),(1,'Panihati','','WB32'),(1,'Panipat','','HA06'),(1,'Parbhani','','MH25'),(1,'Pathankot','','PU08'),(1,'Patiala','','PU09'),(1,'Patna','','BI06'),(1,'Pondicherry','','PO00'),(1,'Pune','','MH08'),(1,'Purnia','','BI15'),(1,'Raichur','','KA22'),(1,'Raipur','','MP12'),(1,'Rajahmundry','','TN12'),(1,'Rajkot','','GU09'),(1,'Ramagundam','','AP29'),(1,'Rampur','','UP42'),(1,'Ranchi','','BI07'),(1,'Ratlam','','MP21'),(1,'Rewa','','MP22'),(1,'Rohtak','','HA04'),(1,'Roorkee','','UP28'),(1,'Rourkela','','OR04'),(1,'Saharanpur','','UP29'),(1,'Salem','','214'),(1,'Sambalpur','','OR09'),(1,'Sangli','','MH09'),(1,'Satna','','MP24'),(1,'Shahjahanpur','','UP44'),(1,'Shillong','','ME01'),(1,'Shimla','','HP03'),(1,'Shimoga','','KA23'),(1,'Sikar','','RA15'),(1,'Silchar','','AS05'),(1,'Sirsa','','HA05'),(1,'Sitapur','','UP45'),(1,'Solapur','','MH11'),(1,'Srinagar','','JK03'),(1,'Surat','','GU10'),(1,'Thalassery','','KE17'),(1,'Thanjavur','','TN15'),(1,'Thiruvananthapuram','','KE08'),(1,'Tiruppur','','TN35'),(1,'Tiruvannamalai','','TN37'),(1,'Tonk','','RA16'),(1,'Tuticorin','','TN18'),(1,'Udaipur','','RA10'),(1,'Ujjain','','MP11'),(1,'Vadodara','','GU11'),(1,'Valsad','','GU25'),(1,'Vapi','','GU12'),(1,'Varanasi','','UP30'),(1,'Vellore','','TN19'),(1,'Warangal','','AP09'),(1,'Yavatmal','','MH27'),(2,'Cuddapah','','AP01'),(2,'Guntur','','AP02'),(2,'Hyderabad','','1216'),(2,'Kakinada','','AP04'),(2,'Kurnool','','AP05'),(2,'Machilipatnam','','AP06'),(2,'Nellore','','AP07'),(2,'Warangal','','AP09'),(2,'Adoni','','AP12'),(2,'Amravati','','AP13'),(2,'Anantapur','','AP14'),(2,'Eluru','','AP17'),(2,'Guntakal','','AP19'),(2,'Hindupur','','AP20'),(2,'Khammam','','AP22'),(2,'Mahbubnagar','','AP23'),(2,'Nandyal','','AP24'),(2,'Nizamabad','','AP25'),(2,'Ongole','','AP26'),(2,'Proddatur','','AP27'),(2,'Ramagundam','','AP29'),(2,'Tenali','','AP30'),(2,'Itanagar','','AR01'),(2,'Dibrugarh','','AS01'),(2,'Guwahati','','AS03'),(2,'Jorhat','','AS04'),(2,'Silchar','','AS05'),(2,'Bokaro','','BI01'),(2,'Dhanbad','','BI02'),(2,'Gaya','','BI03'),(2,'Jamshedpur','','BI04'),(2,'Muzaffarpur','','BI05'),(2,'Patna','','BI06'),(2,'Ranchi','','BI07'),(2,'Arrah','','BI08'),(2,'Bhagalpur','','BI09'),(2,'Chapra','','BI11'),(2,'Katihar','','BI13'),(2,'Munger','','BI14'),(2,'Purnia','','BI15'),(2,'New Delhi','','DE00'),(2,'Ahmedabad','','GU01'),(2,'Anand','','GU02'),(2,'Gandhinagar','','GU05'),(2,'Junagadh','','GU06'),(2,'Kandla','','GU07'),(2,'Palanpur','','GU08'),(2,'Rajkot','','GU09'),(2,'Surat','','GU10'),(2,'Vadodara','','GU11'),(2,'Vapi','','GU12'),(2,'Gandhidham','','GU17'),(2,'Morvi','','GU21'),(2,'Nadiad','','GU22'),(2,'Navsari','','GU23'),(2,'Valsad','','GU25'),(2,'Ambala','','HA01'),(2,'Faridabad','','HA02'),(2,'Gurgaon','','HA03'),(2,'Rohtak','','HA04'),(2,'Sirsa','','HA05'),(2,'Panipat','','HA06'),(2,'Dalhousie','','HP01'),(2,'Kasauli','','HP02'),(2,'Shimla','','HP03'),(2,'Gulmarg','','JK01'),(2,'Leh','','JK02'),(2,'Srinagar','','JK03'),(2,'Jammu','','JK04'),(2,'Ankola','','KA01'),(2,'Bangalore','','KA02'),(2,'Belgaum','','KA03'),(2,'Bidar','','KA04'),(2,'Gulbarga','','KA06'),(2,'Mangalore','','KA08'),(2,'Mysore','','KA09'),(2,'Bellary','','KA10'),(2,'Bijapur','','KA11'),(2,'Hassan','','KA16'),(2,'Hospet','','KA17'),(2,'Mandya','','KA21'),(2,'Raichur','','KA22'),(2,'Shimoga','','KA23'),(2,'Kochi','','1470'),(2,'Kottayam','','KE04'),(2,'Kozhikode','','KE06'),(2,'Palakkad','','KE07'),(2,'Thiruvananthapuram','','KE08'),(2,'Cherthala','','KE12'),(2,'Kannur','','KE14'),(2,'Kollam','','KE15'),(2,'Malappuram','','KE16'),(2,'Thalassery','','KE17'),(2,'Thrissur','','KE18'),(2,'Vadakara','','KE19'),(2,'Kanhangad','','KE20'),(2,'Imphal','','MA01'),(2,'Shillong','','ME01'),(2,'Akola','','MH01'),(2,'Kolhapur','','MH03'),(2,'Mumbai','','MH04'),(2,'Nagpur','','MH05'),(2,'Nashik','','MH24'),(2,'Pune','','MH08'),(2,'Shirdi','','MH10'),(2,'Solapur','','MH11'),(2,'Thane','','MH12'),(2,'Ulhasnagar','','MH13'),(2,'Bhiwandi','','MH14'),(2,'Bhusawal','','MH15'),(2,'Chandrapur','','MH16'),(2,'Dhule','','MH17'),(2,'Gondiya','','MH18'),(2,'Latur','','MH22'),(2,'Malegaon','','MH23'),(2,'Parbhani','','MH25'),(2,'Yavatmal','','MH27'),(2,'Bhopal','','MP02'),(2,'Chhindwara','','MP04'),(2,'Durg','','MP06'),(2,'Gwalior','','MP07'),(2,'Indore','','MP08'),(2,'Jabalpur','','MP09'),(2,'Khajuraho','','MP10'),(2,'Ujjain','','MP11'),(2,'Raipur','','MP12'),(2,'Bhind','','MP13'),(2,'Burhanpur','','MP14'),(2,'Dewas','','MP15'),(2,'Guna','','MP16'),(2,'Khandwa','','MP17'),(2,'Morena','','MP18'),(2,'Ratlam','','MP21'),(2,'Rewa','','MP22'),(2,'Satna','','MP24'),(2,'Damoh','','MP26'),(2,'Dimapur','','NA01'),(2,'Kohima','','NA02'),(2,'Cuttack','','OR02'),(2,'Baleshwar','','OR05'),(2,'Raurkela','','OR08'),(2,'Sambalpur','','OR09'),(2,'Chandigarh','','PH00'),(2,'Pondicherry','','PO00'),(2,'Amritsar','','PU01'),(2,'Bathinda','','PU02'),(2,'Faridkot','','PU03'),(2,'Gurdaspur','','PU05'),(2,'Hoshiarpur','','PU06'),(2,'Ludhiana','','PU07'),(2,'Pathankot','','PU08'),(2,'Patiala','','PU09'),(2,'Jalandhar','','PU10'),(2,'Batala','','PU12'),(2,'Moga','','PU15'),(2,'Ajmer','','RA01'),(2,'Alwar','','RA02'),(2,'Bharatpur','','RA03'),(2,'Bhilwara','','RA04'),(2,'Bikaner','','RA05'),(2,'Ganganagar','','RA06'),(2,'Jaipur','','RA07'),(2,'Jodhpur','','RA08'),(2,'Kota','','RA09'),(2,'Udaipur','','RA10'),(2,'Jaisalmer','','RA11'),(2,'Beawar','','RA12'),(2,'Pali','','RA14'),(2,'Sikar','','RA15'),(2,'Tonk','','RA16'),(2,'Gangtok','','SI01'),(2,'Arcot','','TN01'),(2,'Chennai','','TN02'),(2,'Chidambaram','','TN03'),(2,'Coimbatore','','TN04'),(2,'Cuddalore','','TN05'),(2,'Erode','','TN06'),(2,'Kanniyakumari','','TN07'),(2,'Kodaikanal','','TN08'),(2,'Madurai','','TN09'),(2,'Rajahmundry','','TN12'),(2,'Salem','','214'),(2,'Thanjavur','','TN15'),(2,'Vellore','','TN19'),(2,'Kumbakonam','','TN27'),(2,'Nagercoil','','TN30'),(2,'Tiruppur','','TN35'),(2,'Valparai','','TN36'),(2,'Tiruvannamalai','','TN37'),(2,'Agartala','','TR01'),(2,'Agra','','UP01'),(2,'Aligarh','','UP02'),(2,'Allahabad','','UP03'),(2,'Ayodhya','','UP05'),(2,'Bareilly','','UP06'),(2,'Dehradun','','UP07'),(2,'Etawah','','UP08'),(2,'Faizabad','','UP09'),(2,'Fatehpur','','UP10'),(2,'Firozabad','','UP11'),(2,'Ghaziabad','','UP12'),(2,'Gorakhpur','','UP13'),(2,'Hapur','','UP14'),(2,'Jhansi','','UP16'),(2,'Kannauj','','UP17'),(2,'Kanpur','','UP18'),(2,'Lucknow','','UP19'),(2,'Mathura','','UP20'),(2,'Meerut','','UP21'),(2,'Moradabad','','UP22'),(2,'Muzaffarnagar','','UP24'),(2,'Noida','','UP25'),(2,'Rae Bareli','','UP26'),(2,'Rishikesh','','UP27'),(2,'Roorkee','','UP28'),(2,'Saharanpur','','UP29'),(2,'Varanasi','','UP30'),(2,'Amroha','','UP32'),(2,'Bahraich','','UP33'),(2,'Budaun','','UP34'),(2,'Hathras','','UP37'),(2,'Maunath Bhanjan','','UP38'),(2,'Rampur','','UP42'),(2,'Sambhal','','UP43'),(2,'Shahjahanpur','','UP44'),(2,'Sitapur','','UP45'),(2,'Asansol','','WB01'),(2,'Barddhaman','','WB02'),(2,'Kolkata','','WB05'),(2,'Baharampur','','WB06'),(2,'Bally','','WB07'),(2,'Balurghat','','WB08'),(2,'Bankura','','WB09'),(2,'Baranagar','','WB10'),(2,'Basirhat','','WB12'),(2,'Bhatpara','','WB13'),(2,'Brahmapur','','WB14'),(2,'English Bazar','','WB21'),(2,'Habra','','WB22'),(2,'Haldia','','WB23'),(2,'Haora','','WB24'),(2,'Krishnanagar','','WB27'),(2,'Medinipur','','WB29'),(2,'Nabadwip','','WB30'),(2,'Naihati','','WB31'),(2,'Panihati','','WB32'),(2,'Raiganj','','WB33'),(2,'Raniganj','','WB34'),(2,'Santipur','','WB35'),(2,'Serampore','','WB36'),(2,'Titagarh','','WB39'),(3,'Agartala','','TR01'),(3,'Agra','','UP01'),(3,'Ahmedabad','','GU01'),(3,'Ahmednagar','','UP31'),(3,'Aizwal','','MI01'),(3,'Ajmer','','RA01'),(3,'Akola','','MH01'),(3,'Aligarh','','UP02'),(3,'Allahabad','','UP03'),(3,'Alwar','','RA02'),(3,'Ambala','','HA01'),(3,'Amravati','','AP13'),(3,'Amritsar','','PU01'),(3,'Amroha','','UP32'),(3,'Anand','','GU02'),(3,'Anantapur','','AP14'),(3,'Arrah','','BI08'),(3,'Aurangabad','','MH02'),(3,'Baharampur','','WB06'),(3,'Bahraich','','UP33'),(3,'Balurghat','','WB08'),(3,'Bangalore','','KA02'),(3,'Bankura','','WB09'),(3,'Bareilly','','UP06'),(3,'Bathinda','','PU02'),(3,'Belgaum','','KA03'),(3,'Bellary','','KA10'),(3,'Bhagalpur','','BI09'),(3,'Bharatpur','','RA03'),(3,'Bhilai','','MP01'),(3,'Bhilwara','','RA04'),(3,'Bhind','','MP13'),(3,'Bhopal','','MP02'),(3,'Bhubaneshwar','','OR01'),(3,'Bidar','','KA04'),(3,'Bihar Sharif','','BI10'),(3,'Bijapur','','KA11'),(3,'Bikaner','','RA05'),(3,'Bilaspur','','MP03'),(3,'Bokaro','','BI01'),(3,'Burhanpur','','MP14'),(3,'Calicut','','KE06'),(3,'Chandigarh','','PH00'),(3,'Chandrapur','','MH16'),(3,'Chennai','','TN02'),(3,'Chhindwara','','MP04'),(3,'Coimbatore','','TN04'),(3,'Cuddalore','','TN05'),(3,'Cuttack','','OR02'),(3,'Damoh','','MP26'),(3,'Dehradun','','UP07'),(3,'Delhi','','DE00'),(3,'Dewas','','MP15'),(3,'Dhanbad','','BI02'),(3,'Dharwad','','KA05'),(3,'Dhule','','MH17'),(3,'Dibrugarh','','AS01'),(3,'Dimapur','','NA01'),(3,'Dispur','','AS02'),(3,'Durg','','MP06'),(3,'Eluru','','AP17'),(3,'English Bazar','','WB21'),(3,'Erode','','TN06'),(3,'Etawah','','UP08'),(3,'Faizabad','','UP09'),(3,'Faridabad','','HA02'),(3,'Faridkot','','PU03'),(3,'Fatehpur','','UP10'),(3,'Firozabad','','UP11'),(3,'Ganganagar','','RA06'),(3,'Gangtok','','SI01'),(3,'Gaya','','BI03'),(3,'Ghaziabad','','UP12'),(3,'Gondiya','','MH18'),(3,'Gorakhpur','','UP13'),(3,'Gulbarga','','KA06'),(3,'Guna','','MP16'),(3,'Guntur','','AP02'),(3,'Gurdaspur','','PU05'),(3,'Gurgaon','','HA03'),(3,'Guwahati','','AS03'),(3,'Gwalior','','MP07'),(3,'Haridwar','','UP15'),(3,'Hassan','','KA16'),(3,'Hathras','','UP37'),(3,'Hoshiarpur','','PU06'),(3,'Hubli','','KA07'),(3,'Hyderabad','','1216'),(3,'Imphal','','MA01'),(3,'Indore','','MP08'),(3,'Itanagar','','AR01'),(3,'Jabalpur','','MP09'),(3,'Jaipur','','RA07'),(3,'Jaisalmer','','RA11'),(3,'Jalandhar','','PU10'),(3,'Jammu','','JK04'),(3,'Jamshedpur','','BI04'),(3,'Jhansi','','UP16'),(3,'Jodhpur','','RA08'),(3,'Jorhat','','AS04'),(3,'Junagadh','','GU06'),(3,'Kakinada','','AP04'),(3,'Kannauj','','UP17'),(3,'Kannur','','KE14'),(3,'Kanpur','','UP18'),(3,'Katihar','','BI13'),(3,'Khammam','','AP22'),(3,'Khandwa','','MP17'),(3,'Kochi','','1470'),(3,'Kohima','','NA02'),(3,'Kolhapur','','MH03'),(3,'Kolkata','','WB05'),(3,'Kollam','','KE15'),(3,'Kota','','RA09'),(3,'Kottayam','','KE04'),(3,'Krishnanagar','','WB27'),(3,'Kurnool','','AP05'),(3,'Latur','','MH22'),(3,'Leh','','JK02'),(3,'Lucknow','','UP19'),(3,'Ludhiana','','PU07'),(3,'Machilipatnam','','AP06'),(3,'Madurai','','TN09'),(3,'Mahbubnagar','','AP23'),(3,'Malappuram','','KE16'),(3,'Mandya','','KA21'),(3,'Mangalore','','KA08'),(3,'Mathura','','UP20'),(3,'Meerut','','UP21'),(3,'Mirzapur','','UP39'),(3,'Moga','','PU15'),(3,'Moradabad','','UP22'),(3,'Morena','','MP18'),(3,'Mumbai','','MH04'),(3,'Munger','','BI14'),(3,'Muzaffarnagar','','UP24'),(3,'Muzaffarpur','','BI05'),(3,'Mysore','','KA09'),(3,'Nagercoil','','TN30'),(3,'Nagpur','','MH05'),(3,'Nanded','','MH06'),(3,'Navsari','','GU23'),(3,'Nellore','','AP07'),(3,'Nizamabad','','AP25'),(3,'Noida','','UP25'),(3,'Ongole','','AP26'),(3,'Palakkad','','KE07'),(3,'Palanpur','','GU08'),(3,'Pali','','RA14'),(3,'Panipat','','HA06'),(3,'Parbhani','','MH25'),(3,'Patiala','','PU09'),(3,'Patna','','BI06'),(3,'Pune','','MH08'),(3,'Purnia','','BI15'),(3,'Rae Bareli','','UP26'),(3,'Raichur','','KA22'),(3,'Raiganj','','WB33'),(3,'Raipur','','MP12'),(3,'Rajkot','','GU09'),(3,'Rampur','','UP42'),(3,'Ranchi','','BI07'),(3,'Ratlam','','MP21'),(3,'Rewa','','MP22'),(3,'Rohtak','','HA04'),(3,'Rourkela','','OR04'),(3,'Saharanpur','','UP29'),(3,'Salem','','214'),(3,'Sambalpur','','OR09'),(3,'Sangli','','MH09'),(3,'Satna','','MP24'),(3,'Shahjahanpur','','UP44'),(3,'Shillong','','ME01'),(3,'Shimla','','HP03'),(3,'Shimoga','','KA23'),(3,'Sikar','','RA15'),(3,'Silchar','','AS05'),(3,'Sirsa','','HA05'),(3,'Sitapur','','UP45'),(3,'Solapur','','MH11'),(3,'Srinagar','','JK03'),(3,'Surat','','GU10'),(3,'Thane','','MH12'),(3,'Thrissur','','KE18'),(3,'Tiruvannamalai','','TN37'),(3,'Tonk','','RA16'),(3,'Udaipur','','RA10'),(3,'Ujjain','','MP11'),(3,'Vadodara','','GU11'),(3,'Valsad','','GU25'),(3,'Varanasi','','UP30'),(3,'Vellore','','TN19'),(3,'Vishakhapatnam','','AP11'),(3,'Warangal','','AP09'),(3,'Yavatmal','','MH27');
/*!40000 ALTER TABLE `crawler_JS_competition_city_res_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_country_res_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_country_res_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_country_res_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of country between JS and competi';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_country_res_values_mapping`
--

LOCK TABLES `crawler_JS_competition_country_res_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_country_res_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_country_res_values_mapping` VALUES (1,'India','India',51),(2,'India','98',51),(3,'India','INDI',51),(1,'Afghanistan','',1),(1,'Albania','',2),(1,'Algeria','',3),(1,'American Samoa','',60),(1,'Andorra','',127),(1,'Angola','',4),(1,'Anguilla','',127),(1,'Antigua and Barbuda','',127),(1,'Argentina','',5),(1,'Armenia','',6),(1,'Australia','',7),(1,'Austria','',8),(1,'Azerbaijan','',127),(1,'Bahamas','',9),(1,'Bahrain','',10),(1,'Bangladesh','',11),(1,'Barbados','',127),(1,'Belarus','',127),(1,'Belgium','',12),(1,'Belize','',127),(1,'Bermuda','',13),(1,'Bhutan','',14),(1,'Bolivia','',15),(1,'Bosnia and Herzegovina','',16),(1,'Botswana','',127),(1,'Brazil','',17),(1,'Brunei','',18),(1,'Bulgaria','',19),(1,'Burkina Faso','',127),(1,'Burundi','',127),(1,'Cambodia','',20),(1,'Cameroon','',21),(1,'Canada','',22),(1,'Cape Verde','',127),(1,'Cayman Islands','',127),(1,'Central African Republic','',127),(1,'Chad','',127),(1,'Chile','',24),(1,'China','',25),(1,'Colombia','',26),(1,'Comoros','',127),(1,'Congo','',27),(1,'Cook Islands','',127),(1,'Costa Rica','',28),(1,'Cuba','',30),(1,'Cyprus','',31),(1,'Czech Republic','',32),(1,'Denmark','',33),(1,'Dominica','',127),(1,'Dominican Republic','',127),(1,'East Timor','',127),(1,'Ecuador','',34),(1,'Egypt','',35),(1,'El Salvador','',36),(1,'Equatorial Guinea','',127),(1,'Eritrea','',127),(1,'Estonia','',127),(1,'Ethiopia','',127),(1,'Finland','',39),(1,'France','',40),(1,'French Guiana','',127),(1,'French Polynesia','',127),(1,'Gambia','',127),(1,'Georgia','',69),(1,'Germany','',42),(1,'Ghana','',43),(1,'Gibraltar','',44),(1,'Greece','',45),(1,'Greenland','',127),(1,'Grenada','',127),(1,'Guadeloupe','',127),(1,'Guam','',127),(1,'Guatemala','',127),(1,'Guinea','',127),(1,'Guinea-Bissau','',127),(1,'Guyana','',127),(1,'Haiti','',46),(1,'Honduras','',127),(1,'Hungary','',49),(1,'Iceland','',50),(1,'India','',51),(1,'Indonesia','',52),(1,'Iran','',53),(1,'Iraq','',54),(1,'Ireland','',55),(1,'Israel','',56),(1,'Italy','',57),(1,'Jamaica','',58),(1,'Japan','',59),(1,'Jordan','',127),(1,'Kazakhstan','',61),(1,'Kenya','',62),(1,'Kiribati','',127),(1,'Kuwait','',63),(1,'Kyrgyzstan','',64),(1,'Laos','',127),(1,'Latvia','',65),(1,'Lebanon','',66),(1,'Lesotho','',127),(1,'Liberia','',127),(1,'Libya','',67),(1,'Liechtenstein','',127),(1,'Lithuania','',68),(1,'Luxembourg','',127),(1,'Macedonia','',127),(1,'Madagascar','',127),(1,'Malawi','',127),(1,'Malaysia','',70),(1,'Maldives','',71),(1,'Mali','',72),(1,'Malta','',73),(1,'Martinique','',127),(1,'Mauritius','',74),(1,'Mexico','',76),(1,'Moldova','',127),(1,'Monaco','',127),(1,'Mongolia','',75),(1,'Montserrat','',127),(1,'Morocco','',77),(1,'Mozambique','',127),(1,'Myanmar','',78),(1,'Namibia','',79),(1,'Nepal','',80),(1,'Netherlands','',81),(1,'Netherlands Antilles','',127),(1,'New Caledonia','',127),(1,'New Zealand','',82),(1,'Nicaragua','',83),(1,'Niger','',127),(1,'Nigeria','',84),(1,'North Korea','',85),(1,'Norway','',86),(1,'Oman','',87),(1,'Pakistan','',88),(1,'Panama','',89),(1,'Papua New Guinea','',127),(1,'Paraguay','',90),(1,'Peru','',91),(1,'Philippines','',92),(1,'Poland','',93),(1,'Portugal','',94),(1,'Puerto Rico','',127),(1,'Qatar','',96),(1,'Reunion','',127),(1,'Romania','',97),(1,'Russia','',98),(1,'Rwanda','',127),(1,'San Marino','',127),(1,'Sao Tome and Principe','',127),(1,'Saudi Arabia','',99),(1,'Senegal','',100),(1,'Seychelles','',101),(1,'Sierra Leone','',102),(1,'Singapore','',103),(1,'Slovakia','',104),(1,'Slovenia','',127),(1,'Solomon Islands','',127),(1,'Somalia','',106),(1,'South Africa','',107),(1,'Spain','',109),(1,'Sri Lanka','',110),(1,'Sudan','',111),(1,'Suriname','',127),(1,'Swaziland','',127),(1,'Sweden','',112),(1,'Switzerland','',113),(1,'Taiwan','',116),(1,'Tajikistan','',117),(1,'Tanzania','',118),(1,'Thailand','',119),(1,'Togo','',127),(1,'Trinidad and Tobago','',127),(1,'Tunisia','',120),(1,'Turkey','',121),(1,'Turkmenistan','',122),(1,'Turks and Caicos Islands','',127),(1,'Uganda','',123),(1,'Ukraine','',124),(1,'United Arab Emirates','',125),(1,'United Kingdom','',126),(1,'Uruguay','',127),(1,'Uzbekistan','',127),(1,'Vanuatu','',127),(1,'Venezuela','',127),(1,'Vietnam','',127),(1,'Wallis and Futuna','',127),(1,'Yemen','',127),(1,'Yugoslavia','',127),(1,'Zambia','',127),(1,'Zimbabwe','',127),(2,'Afghanistan','',1),(2,'Albania','',2),(2,'Algeria','',3),(2,'American Samoa','',60),(2,'Andorra','',127),(2,'Angola','',4),(2,'Anguilla','',127),(2,'Antigua and Barbuda','',127),(2,'Argentina','',5),(2,'Armenia','',6),(2,'Australia','',7),(2,'Austria','',8),(2,'Azerbaijan','',127),(2,'Bahamas','',9),(2,'Bahrain','',10),(2,'Bangladesh','',11),(2,'Barbados','',127),(2,'Belarus','',127),(2,'Belgium','',12),(2,'Belize','',127),(2,'Benin','',127),(2,'Bermuda','',13),(2,'Bhutan','',14),(2,'Bolivia','',15),(2,'Bosnia and Herzegovina','',16),(2,'Botswana','',127),(2,'Brazil','',17),(2,'British Virgin Islands','',127),(2,'Brunei','',18),(2,'Bulgaria','',19),(2,'Burkina Faso','',127),(2,'Burundi','',127),(2,'Cambodia','',20),(2,'Cameroon','',21),(2,'Canada','',22),(2,'Cape Verde','',127),(2,'Cayman Islands','',127),(2,'Central African Republic','',127),(2,'Chad','',127),(2,'Chile','',24),(2,'China','',25),(2,'Colombia','',26),(2,'Comoros','',127),(2,'Congo','',27),(2,'Cook Islands','',127),(2,'Costa Rica','',28),(2,'Croatia','',29),(2,'Cuba','',30),(2,'Cyprus','',31),(2,'Czech Republic','',32),(2,'Denmark','',33),(2,'Dominica','',127),(2,'Dominican Republic','',127),(2,'East Timor','',127),(2,'Ecuador','',34),(2,'Egypt','',35),(2,'El Salvador','',36),(2,'Equatorial Guinea','',127),(2,'Eritrea','',127),(2,'Estonia','',127),(2,'Ethiopia','',127),(2,'Fiji','',38),(2,'Finland','',39),(2,'France','',40),(2,'French Guiana','',127),(2,'French Polynesia','',127),(2,'Gambia','',127),(2,'Georgia','',69),(2,'Germany','',42),(2,'Ghana','',43),(2,'Gibraltar','',44),(2,'Greece','',45),(2,'Greenland','',127),(2,'Grenada','',127),(2,'Guadeloupe','',127),(2,'Guam','',127),(2,'Guatemala','',127),(2,'Guinea','',127),(2,'Guinea-Bissau','',127),(2,'Guyana','',127),(2,'Haiti','',46),(2,'Honduras','',127),(2,'Hong Kong','',48),(2,'Hungary','',49),(2,'Iceland','',50),(2,'India','',51),(2,'Indonesia','',52),(2,'Iran','',53),(2,'Iraq','',54),(2,'Ireland','',55),(2,'Israel','',56),(2,'Italy','',57),(2,'Jamaica','',58),(2,'Japan','',59),(2,'Jordan','',127),(2,'Kazakhstan','',61),(2,'Kenya','',62),(2,'Kiribati','',127),(2,'Kuwait','',63),(2,'Kyrgyzstan','',64),(2,'Laos','',127),(2,'Latvia','',65),(2,'Lebanon','',66),(2,'Lesotho','',127),(2,'Liberia','',127),(2,'Libya','',67),(2,'Liechtenstein','',127),(2,'Lithuania','',68),(2,'Luxembourg','',127),(2,'Macau','',127),(2,'Macedonia','',127),(2,'Madagascar','',127),(2,'Malawi','',127),(2,'Malaysia','',70),(2,'Maldives','',71),(2,'Mali','',72),(2,'Malta','',73),(2,'Martinique','',127),(2,'Mauritius','',74),(2,'Mexico','',76),(2,'Monaco','',127),(2,'Mongolia','',75),(2,'Montserrat','',127),(2,'Morocco','',77),(2,'Mozambique','',127),(2,'Myanmar','',78),(2,'Namibia','',79),(2,'Nepal','',80),(2,'Netherlands','',81),(2,'Netherlands Antilles','',127),(2,'New Caledonia','',127),(2,'New Zealand','',82),(2,'Nicaragua','',83),(2,'Niger','',127),(2,'Nigeria','',84),(2,'Norway','',86),(2,'Oman','',87),(2,'Pakistan','',88),(2,'Panama','',89),(2,'Papua New Guinea','',127),(2,'Paraguay','',90),(2,'Peru','',91),(2,'Philippines','',92),(2,'Poland','',93),(2,'Portugal','',94),(2,'Puerto Rico','',127),(2,'Qatar','',96),(2,'Reunion','',127),(2,'Romania','',97),(2,'Russia','',98),(2,'Rwanda','',127),(2,'Saint Kitts and Nevis','',127),(2,'Saint Lucia','',127),(2,'San Marino','',127),(2,'Sao Tome and Principe','',127),(2,'Saudi Arabia','',99),(2,'Senegal','',100),(2,'Seychelles','',101),(2,'Sierra Leone','',102),(2,'Singapore','',103),(2,'Slovakia','',104),(2,'Slovenia','',127),(2,'Somalia','',106),(2,'South Africa','',107),(2,'Spain','',109),(2,'Sri Lanka','',110),(2,'Sudan','',111),(2,'Suriname','',127),(2,'Swaziland','',127),(2,'Sweden','',112),(2,'Switzerland','',113),(2,'Taiwan','',116),(2,'Tajikistan','',117),(2,'Tanzania','',118),(2,'Thailand','',119),(2,'Togo','',127),(2,'Trinidad and Tobago','',127),(2,'Tunisia','',120),(2,'Turkey','',121),(2,'Turkmenistan','',122),(2,'Turks and Caicos Islands','',127),(2,'Uganda','',123),(2,'Ukraine','',124),(2,'United Arab Emirates','',125),(2,'United Kingdom','',126),(2,'Uruguay','',127),(2,'Uzbekistan','',127),(2,'Vanuatu','',127),(2,'Venezuela','',127),(2,'Vietnam','',127),(2,'Yemen','',127),(2,'Zambia','',127),(2,'Zimbabwe','',127),(3,'Afghanistan','',1),(3,'Albania','',2),(3,'Algeria','',3),(3,'American Samoa','',60),(3,'Andorra','',127),(3,'Angola','',4),(3,'Anguilla','',127),(3,'Antigua and Barbuda','',127),(3,'Argentina','',5),(3,'Armenia','',6),(3,'Australia','',7),(3,'Austria','',8),(3,'Azerbaijan','',127),(3,'Bahamas','',9),(3,'Bahrain','',10),(3,'Bangladesh','',11),(3,'Barbados','',127),(3,'Belarus','',127),(3,'Belgium','',12),(3,'Belize','',127),(3,'Benin','',127),(3,'Bermuda','',13),(3,'Bhutan','',14),(3,'Bolivia','',15),(3,'Bosnia and Herzegovina','',16),(3,'Botswana','',127),(3,'Brazil','',17),(3,'Brunei','',18),(3,'Bulgaria','',19),(3,'Burkina Faso','',127),(3,'Burundi','',127),(3,'Cambodia','',20),(3,'Cameroon','',21),(3,'Canada','',22),(3,'Cape Verde','',127),(3,'Cayman Islands','',127),(3,'Central African Republic','',127),(3,'Chad','',127),(3,'Chile','',24),(3,'China','',25),(3,'Colombia','',26),(3,'Comoros','',127),(3,'Congo','',27),(3,'Cook Islands','',127),(3,'Costa Rica','',28),(3,'Croatia','',29),(3,'Cuba','',30),(3,'Cyprus','',31),(3,'Czech Republic','',32),(3,'Denmark','',33),(3,'Dominica','',127),(3,'Dominican Republic','',127),(3,'East Timor','',127),(3,'Ecuador','',34),(3,'Egypt','',35),(3,'El Salvador','',36),(3,'Equatorial Guinea','',127),(3,'Eritrea','',127),(3,'Estonia','',127),(3,'Ethiopia','',127),(3,'Fiji','',38),(3,'Finland','',39),(3,'France','',40),(3,'French Guiana','',127),(3,'French Polynesia','',127),(3,'Gambia','',127),(3,'Georgia','',69),(3,'Germany','',42),(3,'Ghana','',43),(3,'Gibraltar','',44),(3,'Greece','',45),(3,'Greenland','',127),(3,'Grenada','',127),(3,'Guadeloupe','',127),(3,'Guam','',127),(3,'Guatemala','',127),(3,'Guinea','',127),(3,'Guyana','',127),(3,'Haiti','',46),(3,'Honduras','',127),(3,'Hong Kong','',48),(3,'Hungary','',49),(3,'Iceland','',50),(3,'India','',51),(3,'Indonesia','',52),(3,'Iran','',53),(3,'Iraq','',54),(3,'Ireland','',55),(3,'Israel','',56),(3,'Italy','',57),(3,'Jamaica','',58),(3,'Japan','',59),(3,'Jordan','',127),(3,'Kazakhstan','',61),(3,'Kenya','',62),(3,'Kiribati','',127),(3,'Kuwait','',63),(3,'Kyrgyzstan','',64),(3,'Laos','',127),(3,'Latvia','',65),(3,'Lebanon','',66),(3,'Lesotho','',127),(3,'Liberia','',127),(3,'Libya','',67),(3,'Liechtenstein','',127),(3,'Lithuania','',68),(3,'Luxembourg','',127),(3,'Macau','',127),(3,'Madagascar','',127),(3,'Malaysia','',70),(3,'Maldives','',71),(3,'Mali','',72),(3,'Malta','',73),(3,'Martinique','',127),(3,'Mauritius','',74),(3,'Mexico','',76),(3,'Moldova','',127),(3,'Monaco','',127),(3,'Mongolia','',75),(3,'Montserrat','',127),(3,'Morocco','',77),(3,'Mozambique','',127),(3,'Myanmar','',78),(3,'Namibia','',79),(3,'Nepal','',80),(3,'Netherlands','',81),(3,'Netherlands Antilles','',127),(3,'New Caledonia','',127),(3,'New Zealand','',82),(3,'Nicaragua','',83),(3,'Niger','',127),(3,'Nigeria','',84),(3,'Norway','',86),(3,'Oman','',87),(3,'Others','',127),(3,'Pakistan','',88),(3,'Panama','',89),(3,'Papua New Guinea','',127),(3,'Paraguay','',90),(3,'Peru','',91),(3,'Philippines','',92),(3,'Poland','',93),(3,'Portugal','',94),(3,'Puerto Rico','',127),(3,'Qatar','',96),(3,'Reunion','',127),(3,'Romania','',97),(3,'Russia','',98),(3,'Rwanda','',127),(3,'Saint Lucia','',127),(3,'San Marino','',127),(3,'Sao Tome and Principe','',127),(3,'Saudi Arabia','',99),(3,'Senegal','',100),(3,'Seychelles','',101),(3,'Sierra Leone','',102),(3,'Singapore','',103),(3,'Slovakia','',104),(3,'Slovenia','',127),(3,'Solomon Islands','',127),(3,'Somalia','',106),(3,'South Africa','',107),(3,'Spain','',109),(3,'Sri Lanka','',110),(3,'Sudan','',111),(3,'Suriname','',127),(3,'Swaziland','',127),(3,'Sweden','',112),(3,'Switzerland','',113),(3,'Taiwan','',116),(3,'Tajikistan','',117),(3,'Tanzania','',118),(3,'Thailand','',119),(3,'Togo','',127),(3,'Trinidad and Tobago','',127),(3,'Tunisia','',120),(3,'Turkey','',121),(3,'Turkmenistan','',122),(3,'Turks and Caicos Islands','',127),(3,'Uganda','',123),(3,'Ukraine','',124),(3,'United Arab Emirates','',125),(3,'United Kingdom','',126),(3,'Uruguay','',127),(3,'Uzbekistan','',127),(3,'Vanuatu','',127),(3,'Venezuela','',127),(3,'Vietnam','',127),(3,'Yemen','',127),(3,'Zambia','',127),(3,'Zimbabwe','',127);
/*!40000 ALTER TABLE `crawler_JS_competition_country_res_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_diet_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_diet_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_diet_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of diet between JS and competitio';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_diet_values_mapping`
--

LOCK TABLES `crawler_JS_competition_diet_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_diet_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_diet_values_mapping` VALUES (3,'Vegetarian','','V'),(1,'Veg','','V'),(1,'Non-Veg','','N'),(1,'Eggetarian','','E'),(1,'Jain','','J'),(2,'Vegetarian','','V'),(2,'Non Vegetarian','','N'),(2,'Eggetarian','','E'),(3,'Vegetarian','','V'),(3,'Non Vegetarian','','N'),(3,'Vegetarian with eggs','','E'),(3,'Jain','','J'),(1,'Occasionally Non-Veg','','N'),(1,'Vegan','','V');
/*!40000 ALTER TABLE `crawler_JS_competition_diet_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_drink_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_drink_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_drink_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of drink between JS and competiti';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_drink_values_mapping`
--

LOCK TABLES `crawler_JS_competition_drink_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_drink_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_drink_values_mapping` VALUES (1,'No','','N'),(1,'Occasionally','','O'),(1,'Yes','','Y'),(2,'No','','N'),(2,'Occasionally','','O'),(2,'Yes','','Y'),(3,'No','','N'),(3,'Occasionally','','O'),(3,'Yes','','Y'),(2,'Non-drinker','','N'),(2,'Light / Social drinker','','O');
/*!40000 ALTER TABLE `crawler_JS_competition_drink_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_edu_level_new_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_edu_level_new_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_edu_level_new_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of education between JS and compe';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_edu_level_new_values_mapping`
--

LOCK TABLES `crawler_JS_competition_edu_level_new_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_edu_level_new_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_edu_level_new_values_mapping` VALUES (1,'Masters - Education','',11),(2,'BAMS','',25),(2,'BHMS','',26),(2,'MBBS','',17),(2,'BDS','',17),(2,'B.E / B.Tech','',3),(2,'B Pharm','',4),(2,'CA','',7),(2,'CS','',8),(2,'ICWA','',10),(2,'MCA/PGDCA','',18),(2,'ME/M Tech','',13),(2,'MS (Engg.)','',13),(2,'M Pharm','',14),(2,'MD / MS (Medical)','',19),(2,'Ph D','',21),(2,'MA','',11),(2,'M Com','',12),(2,'M Sc','',15),(2,'MBA/PGDM','',16),(2,'BA','',1),(2,'BCom','',2),(2,'BSc','',5),(2,'Trade School','',24),(2,'Diploma','',9),(1,'Bachelors - Engineering/ Technology','',3),(1,'Bachelors - Computers/ IT','',3),(1,'Masters - Engineering/ Technology','',13),(1,'Masters - Arts','',11),(1,'Masters - Commerce','',12),(1,'Masters - Science','',15),(1,'Masters - Management','',16),(1,'Masters - Law','',20),(1,'Bachelors - Arts','',1),(1,'Bachelors - Commerce','',2),(1,'Bachelors - Management','',5),(1,'Bachelors - Law','',6),(3,'BHM','',26),(3,'BE B.Tech','',3),(3,'B.Pharm','',4),(3,'CA','',7),(3,'CS','',8),(3,'ICWA','',10),(3,'MBBS','',17),(3,'BDS','',17),(3,'MCA PGDCA','',18),(3,'MCA PGDCA  part time','',18),(3,'MD MS','',19),(3,'M.Tech','',13),(3,'ME M.Tech','',13),(3,'PhD   doctorate','',21),(3,'MA','',11),(3,'M.Com','',12),(3,'M.Sc','',15),(3,'MBA PGDM','',16),(3,'MBA PGDM  part time','',16),(3,'PGDBM','',16),(3,'ML LLM','',20),(3,'B.A.','',1),(3,'B.Com','',2),(3,'B.Sc','',5),(3,'LLB','',6),(3,'BL LLB','',6),(3,'Diploma','',9),(3,'Others','',22);
/*!40000 ALTER TABLE `crawler_JS_competition_edu_level_new_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_field_name_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_field_name_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_field_name_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_NAME` varchar(50) NOT NULL,
  `JS_FIELD_NAME` varchar(50) NOT NULL,
  `MAPPING_REQUIRED` char(1) NOT NULL,
  `ACTION` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='mapping of field names between js and competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_field_name_mapping`
--

LOCK TABLES `crawler_JS_competition_field_name_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_field_name_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_field_name_mapping` VALUES (1,'se','se','','detail_view'),(1,'Country of Residence','COUNTRY_RES','Y','detail_view'),(1,'Caste / Sect','CASTE','Y','detail_view'),(1,'Date of Birth','DTOFBIRTH','','detail_view'),(1,'Time of Birth','BTIME','','detail_view'),(1,'City of Birth','CITY_BIRTH','','detail_view'),(1,'Country of Birth','COUNTRY_BIRTH','Y','detail_view'),(1,'Gender','GENDER','Y','detail_view'),(1,'Marital Status','MSTATUS','Y','detail_view'),(1,'Age','AGE','','detail_view'),(1,'Blood Group','BLOOD_GROUP','Y','detail_view'),(1,'Mother Tongue','MTONGUE','Y','detail_view'),(1,'Residency Status','CITIZENSHIP','Y','detail_view'),(1,'Current Residence','CITY_RES','Y','detail_view'),(1,'Religion','RELIGION','Y','detail_view'),(1,'Education','EDU_LEVEL_NEW','Y','detail_view'),(1,'No. of Sisters','T_SISTERS','Y','detail_view'),(1,'No. of Brothers','T_BROTHERS','Y','detail_view'),(1,'Diet','DIET','Y','detail_view'),(1,'Drink','DRINK','Y','detail_view'),(1,'Smoke','SMOKE','Y','detail_view'),(1,'Sub caste / sect','SUBCASTE','','detail_view'),(1,'Height','HEIGHT','Y','detail_view'),(1,'Mobile','PHONE_MOB','','contact_detail_view'),(1,'Landline','PHONE_RES','','contact_detail_view'),(1,'STD','STD','','contact_detail_view'),(2,'Age','AGE','','detail_view'),(2,'Marital status','MSTATUS','Y','detail_view'),(2,'Blood Group','BLOOD_GROUP','Y','detail_view'),(2,'Mother Tongue','MTONGUE','Y','detail_view'),(2,'Resident Status','CITIZENSHIP','Y','detail_view'),(2,'City','CITY_RES','Y','detail_view'),(2,'Education Category','EDU_LEVEL_NEW','Y','detail_view'),(2,'No. of Brother(s)','T_BROTHERS','Y','detail_view'),(2,'No. of Sister(s)','T_SISTERS','Y','detail_view'),(2,'Eating Habits','DIET','Y','detail_view'),(2,'Drinking habits','DRINK','Y','detail_view'),(2,'Smoking habits','SMOKE','Y','detail_view'),(2,'Caste','CASTE','Y','detail_view'),(2,'Sub Caste','SUBCASTE','','detail_view'),(2,'Height','HEIGHT','Y','detail_view'),(2,'Religion','RELIGION','Y','detail_view'),(2,'Country','COUNTRY_RES','Y','detail_view'),(2,'Phone','PHONE_MOB','','contact_detail_view'),(3,'Age','AGE','','detail_view'),(3,'Date of Birth','DTOFBIRTH','','detail_view'),(3,'Time of Birth','BTIME','','detail_view'),(3,'Place of Birth','CITY_BIRTH','','detail_view'),(3,'Marital Status','MSTATUS','Y','detail_view'),(3,'Mother Tongue','MTONGUE','Y','detail_view'),(3,'City','CITY_RES','Y','detail_view'),(3,'Country','COUNTRY_RES','Y','detail_view'),(3,'Education','EDU_LEVEL_NEW','Y','detail_view'),(3,'Eating Habits','DIET','Y','detail_view'),(3,'Drinking','DRINK','Y','detail_view'),(3,'Smoking','SMOKE','Y','detail_view'),(3,'Height','HEIGHT','Y','detail_view'),(3,'Religion','RELIGION','Y','detail_view'),(3,'Caste','CASTE','Y','detail_view'),(3,'STD','STD','','contact_detail_view'),(3,'Phone','PHONE_RES','','contact_detail_view'),(3,'Mobile','PHONE_MOB','','contact_detail_view'),(3,'Email','EMAIL','','contact_detail_view'),(2,'Name','NAME','','detail_view');
/*!40000 ALTER TABLE `crawler_JS_competition_field_name_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_gender_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_gender_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_gender_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of gender between JS and competit';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_gender_values_mapping`
--

LOCK TABLES `crawler_JS_competition_gender_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_gender_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_gender_values_mapping` VALUES (1,'Bride','Female','F'),(1,'Groom','Male','M'),(1,'Male','Male','M'),(1,'Female','Female','F'),(2,'Male','M','M'),(2,'Female','F','F'),(3,'Male','M','M'),(3,'Female','F','F');
/*!40000 ALTER TABLE `crawler_JS_competition_gender_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_height_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_height_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_height_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of height between JS and competit';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_height_values_mapping`
--

LOCK TABLES `crawler_JS_competition_height_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_height_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_height_values_mapping` VALUES (1,'4\' 0\"','1',1),(1,'4\' 1\"','',2),(1,'4\' 2\"','',3),(1,'4\' 3\"','',4),(1,'4\' 4\"','',5),(1,'4\' 5\"','',6),(1,'4\' 6\"','',7),(1,'4\' 7\"','',8),(1,'4\' 8\"','',9),(1,'4\' 9\"','',10),(1,'4\' 10\"','',11),(1,'4\' 11\"','',12),(1,'5\' 0\"','',13),(1,'5\' 1\"','',14),(1,'5\' 2\"','',15),(1,'5\' 3\"','',16),(1,'5\' 4\"','',17),(1,'5\' 5\"','',18),(1,'5\' 6\"','',19),(1,'5\' 7\"','',20),(1,'5\' 8\"','',21),(1,'5\' 9\"','',22),(1,'5\' 10\"','',23),(1,'5\' 11\"','',24),(1,'6\' 0\"','',25),(1,'6\' 1\"','',26),(1,'6\' 2\"','',27),(1,'6\' 3\"','',28),(1,'6\' 4\"','',29),(1,'6\' 5\"','',30),(1,'6\' 6\"','',31),(1,'6\' 7\"','',32),(1,'6\' 8\"','',33),(1,'6\' 9\"','',34),(1,'6\' 10\"','',35),(1,'6\' 11\"','',36),(1,'7\'','37',37),(2,'4\' 0\"','',1),(2,'4\' 1\"','',2),(2,'4\' 2\"','',3),(2,'4\' 3\"','',4),(2,'4\' 4\"','',5),(2,'4\' 5\"','',6),(2,'4\' 6\"','',7),(2,'4\' 7\"','',8),(2,'4\' 8\"','',9),(2,'4\' 9\"','',10),(2,'4\' 10\"','',11),(2,'4\' 11\"','',12),(2,'5\' 0\"','',13),(2,'5\' 1\"','',14),(2,'5\' 2\"','',15),(2,'5\' 3\"','',16),(2,'5\' 4\"','',17),(2,'5\' 5\"','',18),(2,'5\' 6\"','',19),(2,'5\' 7\"','',20),(2,'5\' 8\"','',21),(2,'5\' 9\"','',22),(2,'5\' 10\"','',23),(2,'5\' 11\"','',24),(2,'6\' 0\"','',25),(2,'6\' 1\"','',26),(2,'6\' 2\"','',27),(2,'6\' 3\"','',28),(2,'6\' 4\"','',29),(2,'6\' 5\"','',30),(2,'6\' 6\"','',31),(2,'6\' 7\"','',32),(2,'6\' 8\"','',33),(2,'6\' 9\"','',34),(2,'6\' 10\"','',35),(2,'6\' 11\"','',36),(2,'7\'','',37),(3,'4\' 0\"','',1),(3,'4\' 1\"','',2),(3,'4\' 2\"','',3),(3,'4\' 3\"','',4),(3,'4\' 4\"','',5),(3,'4\' 5\"','',6),(3,'4\' 6\"','',7),(3,'4\' 7\"','',8),(3,'4\' 8\"','',9),(3,'4\' 9\"','',10),(3,'4\' 10\"','',11),(3,'4\' 11\"','',12),(3,'5\' 0\"','',13),(3,'5\' 1\"','',14),(3,'5\' 2\"','',15),(3,'5\' 3\"','',16),(3,'5\' 4\"','',17),(3,'5\' 5\"','',18),(3,'5\' 6\"','',19),(3,'5\' 7\"','',20),(3,'5\' 8\"','',21),(3,'5\' 9\"','',22),(3,'5\' 10\"','',23),(3,'5\' 11\"','',24),(3,'6\' 0\"','',25),(3,'6\' 1\"','',26),(3,'6\' 2\"','',27),(3,'6\' 3\"','',28),(3,'6\' 4\"','',29),(3,'6\' 5\"','',30),(3,'6\' 6\"','',31),(3,'6\' 7\"','',32),(3,'6\' 8\"','',33),(3,'6\' 9\"','',34),(3,'6\' 10\"','',35),(3,'6\' 11\"','',36),(3,'7\'','',37);
/*!40000 ALTER TABLE `crawler_JS_competition_height_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_mstatus_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_mstatus_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_mstatus_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of mstatus between JS and competi';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_mstatus_values_mapping`
--

LOCK TABLES `crawler_JS_competition_mstatus_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_mstatus_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_mstatus_values_mapping` VALUES (1,'Never Married','','N'),(2,'Unmarried','1','N'),(3,'Never Married','','N'),(1,'Never Married','','N'),(1,'Divorced','','D'),(1,'Widowed','','W'),(1,'Annulled','','A'),(2,'Unmarried','','N'),(2,'Widow/Widower','','W'),(2,'Divorced','','D'),(2,'Awaiting divorce','','S'),(3,'Never Married','','N'),(3,'Widowed','','W'),(3,'Divorced','','D'),(3,'Married','','M');
/*!40000 ALTER TABLE `crawler_JS_competition_mstatus_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_mtongue_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_mtongue_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_mtongue_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of mtongue group between JS and c';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_mtongue_values_mapping`
--

LOCK TABLES `crawler_JS_competition_mtongue_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_mtongue_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_mtongue_values_mapping` VALUES (1,'Hindi','Hindi',10),(1,'Marathi','Marathi',20),(2,'Hindi','17',10),(2,'Marathi','33',20),(3,'Hindi','HIND',10),(1,'Hindi','',10),(1,'Bhojpuri','',33),(1,'Awadhi','',33),(1,'Garhwali','',33),(1,'Chattisgarhi','',19),(1,'Punjabi','',27),(1,'Maithili','',7),(1,'Magahi','',7),(1,'Marwari','',28),(1,'Rajasthani','',28),(1,'Haryanavi','',13),(1,'Himachali/Pahari','',14),(1,'Kashmiri','',15),(1,'Dogri','',15),(1,'Marathi','',20),(1,'Gujarati','',12),(1,'Konkani','',34),(1,'Sindhi','',30),(1,'Tamil','',31),(1,'Telugu','',3),(1,'Kannada','',16),(1,'Malayalam','',17),(1,'Bengali','',6),(1,'Oriya','',25),(1,'Assamese','',5),(1,'Lepcha','',29),(1,'Nepali','',29),(2,'Hindi ','',10),(2,'Awadhi ','',33),(2,'Bhojpuri ','',33),(2,'Garhwali ','',33),(2,'Chatisgarhi ','',19),(2,'Punjabi ','',27),(2,'Bihari ','',7),(2,'Maithili ','',7),(2,'Magahi ','',7),(2,'Marwari ','',28),(2,'Rajasthani ','',28),(2,'Haryanvi ','',13),(2,'Himachali/Pahari ','',14),(2,'Kashmiri ','',15),(2,'Dogri ','',15),(2,'Marathi ','',20),(2,'Gujarati ','',12),(2,'Konkani ','',34),(2,'Sindhi ','',30),(2,'Tamil ','',31),(2,'Telugu ','',3),(2,'Kannada ','',16),(2,'Malayalam ','',17),(2,'Bengali ','',6),(2,'Oriya ','',25),(2,'Assamese ','',5),(2,'Nepali ','',29),(2,'Lepcha ','',29),(3,'Hindi','',10),(3,'Awadhi','',33),(3,'Bhojpuri','',33),(3,'Garhwali','',33),(3,'Chatisgarhi','',19),(3,'Punjabi','',27),(3,'Bihari','',7),(3,'Maithili','',7),(3,'Magahi','',7),(3,'Kashmiri','',15),(3,'Dogri','',15),(3,'Sindhi','',30),(3,'Marathi','MARA',20),(3,'Gujarati','',12),(3,'Kutchi','',12),(3,'Rajasthani','',28),(3,'Marwari','',28),(3,'Haryanvi','',13),(3,'Himachali Pahari','',14),(3,'Konkani','',34),(3,'Tamil','',31),(3,'Telugu','',3),(3,'Kannada','',16),(3,'Malayalam','',17),(3,'Bengali','',6),(3,'Oriya','',25),(3,'Assamese','',5),(3,'Nepali','',29),(3,'Lepcha','',29),(3,'Bhutia/Sikkimese','',29),(3,'Limbu','',29);
/*!40000 ALTER TABLE `crawler_JS_competition_mtongue_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_religion_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_religion_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_religion_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of religion between JS and compet';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_religion_values_mapping`
--

LOCK TABLES `crawler_JS_competition_religion_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_religion_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_religion_values_mapping` VALUES (1,'Buddhist','',7),(1,'Hindu','Hindu',1),(1,'Muslim','',2),(1,'Christian ','',3),(1,'Sikh','',4),(1,'Parsi','',5),(1,'Jain ','',9),(1,'Jewish','',6),(2,'Buddhist ','',7),(2,'Hindu ','1',1),(2,'Christian - Catholic ','',3),(2,'Christian - Orthodox ','',3),(2,'Christian - Protestant ','',3),(2,'Christian - Others ','',3),(2,'Muslim - Shia ','',2),(2,'Muslim - Sunni ','',2),(2,'Muslim - Others ','',2),(2,'Jain - Digambar ','',9),(2,'Jain - Shwetambar ','',9),(2,'Jain - Others ','',9),(2,'Sikh ','',4),(2,'Parsi ','',5),(2,'Jewish ','',6),(3,'Buddhist ','',7),(3,'Hindu','HIND',1),(3,'Muslim','',2),(3,'Christian','',3),(3,'Sikh','',4),(3,'Parsi','',5),(3,'Jain','',9),(3,'Jewish','',6);
/*!40000 ALTER TABLE `crawler_JS_competition_religion_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_smoke_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_smoke_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_smoke_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of smoke between JS and competiti';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_smoke_values_mapping`
--

LOCK TABLES `crawler_JS_competition_smoke_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_smoke_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_smoke_values_mapping` VALUES (1,'No','','N'),(1,'Occasionally','','O'),(1,'Yes','','Y'),(2,'No','','N'),(2,'Occasionally','','O'),(2,'Yes','','Y'),(3,'No','','N'),(3,'Yes','','Y'),('2','Non-smoker','','N'),('2','Light / Social smoker','','O');
/*!40000 ALTER TABLE `crawler_JS_competition_smoke_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_t_brothers_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_t_brothers_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_t_brothers_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of tbrothers between JS and compe';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_t_brothers_values_mapping`
--

LOCK TABLES `crawler_JS_competition_t_brothers_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_t_brothers_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_t_brothers_values_mapping` VALUES (1,'2','',2),(1,'1','',1),(1,'3','',3),(1,'0','',0),(2,'0','',0),(2,'3','',3),(2,'1','',1),(3,'3','',3),(3,'1','',1),(2,'2','',2),(3,'2','',2),(3,'0','',0),(2,'None','',0);
/*!40000 ALTER TABLE `crawler_JS_competition_t_brothers_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_t_sisters_values_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_t_sisters_values_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_t_sisters_values_mapping` (
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `COMPETITION_FIELD_VALUE` varchar(255) NOT NULL,
  `JS_FIELD_VALUE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='contains mapping of values of tsisters between JS and compet';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_t_sisters_values_mapping`
--

LOCK TABLES `crawler_JS_competition_t_sisters_values_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_t_sisters_values_mapping` DISABLE KEYS */;
INSERT INTO `crawler_JS_competition_t_sisters_values_mapping` VALUES (2,'None','',0),(1,'1','',1),(1,'2','',2),(1,'3','',3),(1,'0','',0),(2,'1','',1),(2,'2','',2),(2,'3','',3),(2,'0','',0),(3,'1','',1),(3,'2','',2),(3,'3','',3),(3,'0','',0);
/*!40000 ALTER TABLE `crawler_JS_competition_t_sisters_values_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_JS_competition_user_mapping`
--

DROP TABLE IF EXISTS `crawler_JS_competition_user_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_JS_competition_user_mapping` (
  `MAPPING_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) NOT NULL,
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_ID` varchar(255) NOT NULL,
  PRIMARY KEY (`MAPPING_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='stores mapping of competition user ids to JS user ids';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_JS_competition_user_mapping`
--

LOCK TABLES `crawler_JS_competition_user_mapping` WRITE;
/*!40000 ALTER TABLE `crawler_JS_competition_user_mapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `crawler_JS_competition_user_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_competition_accounts`
--

DROP TABLE IF EXISTS `crawler_competition_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_competition_accounts` (
  `ACCOUNT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_ID` int(11) NOT NULL,
  `USERNAME` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `NO_OF_CONTACT_VIEWS_ALLOWED` smallint(5) NOT NULL,
  `NO_OF_CONTACT_DETAILS_VIEWED` smallint(5) NOT NULL,
  `ACTIVE` char(1) NOT NULL,
  `PAID` char(1) NOT NULL,
  `GENDER` char(1) NOT NULL,
  `RELIGION` tinyint(3) NOT NULL,
  `MTONGUE` tinyint(3) NOT NULL,
  `AGE` tinyint(4) NOT NULL,
  PRIMARY KEY (`ACCOUNT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='login details of accounts created on competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_competition_accounts`
--

LOCK TABLES `crawler_competition_accounts` WRITE;
/*!40000 ALTER TABLE `crawler_competition_accounts` DISABLE KEYS */;
INSERT INTO `crawler_competition_accounts` VALUES (1,1,'smita12_1987@yahoo.com','smita12',400,25,'Y','Y','F',0,0,0),(2,2,'amit20_1985@yahoo.com','amit20',400,0,'N','','M',0,0,0),(3,2,'smita12_1987@yahoo.in','smita12',40,17,'Y','Y','F',0,0,0),(4,3,'shiny87sm','shiny',5000,20,'Y','Y','F',0,0,0),(5,3,'sharma_a1981','sharma_a1981',5000,25,'Y','Y','M',0,0,0);
/*!40000 ALTER TABLE `crawler_competition_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_detail_view_history`
--

DROP TABLE IF EXISTS `crawler_detail_view_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_detail_view_history` (
  `DETAIL_VIEW_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_ID` int(11) NOT NULL,
  `ACCOUNT_ID` int(11) NOT NULL,
  `COMPETITION_ID` varchar(50) NOT NULL,
  `TIME` datetime NOT NULL,
  `CRAWL_ERROR` char(1) NOT NULL,
  `CRAWL_ERROR_MESSAGE` text,
  `UNEXPECTED_RESPONSE` char(1) NOT NULL,
  `CONTACT_DETAIL_VIEW` char(1) NOT NULL,
  `URL` text NOT NULL,
  `DATA` text NOT NULL,
  PRIMARY KEY (`DETAIL_VIEW_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=latin1 COMMENT='log of all detail views made on competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crawler_login_history`
--

DROP TABLE IF EXISTS `crawler_login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_login_history` (
  `LOGIN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_ID` int(11) NOT NULL,
  `ACCOUNT_ID` int(11) NOT NULL,
  `TIME` datetime NOT NULL,
  `ERROR_MESSAGE` text NOT NULL,
  PRIMARY KEY (`LOGIN_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='log of all logins made on competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_login_history`
--

LOCK TABLES `crawler_login_history` WRITE;
/*!40000 ALTER TABLE `crawler_login_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `crawler_login_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_no_mapping`
--

DROP TABLE IF EXISTS `crawler_no_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_no_mapping` (
  `NO_MAPPING_ID` int(11) NOT NULL AUTO_INCREMENT,
  `HISTORY_ID` int(11) NOT NULL,
  `ACTION` varchar(50) NOT NULL,
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_FIELD_NAME` varchar(100) DEFAULT NULL,
  `COMPETITION_FIELD_LABEL` varchar(50) NOT NULL,
  `NO_MATCH` char(1) NOT NULL,
  `NO_MAP` char(1) NOT NULL,
  PRIMARY KEY (`NO_MAPPING_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=208 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crawler_priority_communities`
--

DROP TABLE IF EXISTS `crawler_priority_communities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_priority_communities` (
  `COMMUNITY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MTONGUE` tinyint(3) NOT NULL,
  `GENDER` char(1) NOT NULL,
  `LAGE` tinyint(4) DEFAULT NULL,
  `HAGE` tinyint(4) NOT NULL,
  `COUNTRY_RES` tinyint(3) NOT NULL,
  `LHEIGHT` tinyint(3) NOT NULL,
  `HHEIGHT` tinyint(3) NOT NULL,
  `RELIGION` tinyint(3) NOT NULL,
  `MSTATUS` char(2) NOT NULL,
  `TO_BE_SEARCHED` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`COMMUNITY_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 COMMENT='contains parameters defining groups of users being crawled o';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_priority_communities`
--

LOCK TABLES `crawler_priority_communities` WRITE;
/*!40000 ALTER TABLE `crawler_priority_communities` DISABLE KEYS */;
INSERT INTO `crawler_priority_communities` VALUES (1,20,'F',25,30,51,1,37,1,'N','Y'),(2,20,'F',31,50,51,1,37,1,'N','N'),(3,20,'F',18,24,51,1,37,1,'N','N'),(4,10,'F',25,30,51,1,37,1,'N','N'),(5,19,'F',25,30,51,1,37,1,'N','N'),(6,33,'F',25,30,51,1,37,1,'N','N'),(7,7,'F',25,30,51,1,37,1,'N','N'),(8,28,'F',25,30,51,1,37,1,'N','N'),(9,13,'F',25,30,51,1,37,1,'N','N'),(10,19,'F',25,30,51,1,37,1,'N','N'),(11,10,'F',31,50,51,1,37,1,'N','N'),(12,19,'F',31,50,51,1,37,1,'N','N'),(13,33,'F',31,50,51,1,37,1,'N','N'),(14,7,'F',31,50,51,1,37,1,'N','N'),(15,28,'F',31,50,51,1,37,1,'N','N'),(16,13,'F',31,50,51,1,37,1,'N','N'),(17,19,'F',31,50,51,1,37,1,'N','N'),(18,10,'F',18,24,51,1,37,1,'N','N'),(19,19,'F',18,24,51,1,37,1,'N','N'),(20,33,'F',18,24,51,1,37,1,'N','N'),(21,7,'F',18,24,51,1,37,1,'N','N'),(22,28,'F',18,24,51,1,37,1,'N','N'),(23,13,'F',18,24,51,1,37,1,'N','N'),(24,19,'F',18,24,51,1,37,1,'N','N'),(25,27,'F',25,30,51,1,37,1,'N','N'),(26,27,'F',31,50,51,1,37,1,'N','N'),(27,27,'F',18,24,51,1,37,1,'N','N'),(28,20,'M',26,30,51,1,37,1,'N','N'),(29,20,'M',31,50,51,1,37,1,'N','N'),(30,10,'M',26,30,51,1,37,1,'N','N'),(31,19,'M',26,30,51,1,37,1,'N','N'),(32,33,'M',26,30,51,1,37,1,'N','N'),(33,7,'M',26,30,51,1,37,1,'N','N'),(34,28,'M',26,30,51,1,37,1,'N','N'),(35,13,'M',26,30,51,1,37,1,'N','N'),(36,19,'M',26,30,51,1,37,1,'N','N'),(37,10,'M',31,50,51,1,37,1,'N','N'),(38,19,'M',31,50,51,1,37,1,'N','N'),(39,33,'M',31,50,51,1,37,1,'N','N'),(40,7,'M',31,50,51,1,37,1,'N','N'),(41,28,'M',31,50,51,1,37,1,'N','N'),(42,13,'M',31,50,51,1,37,1,'N','N'),(43,19,'M',31,50,51,1,37,1,'N','N'),(44,27,'M',26,30,51,1,37,1,'N','N'),(45,27,'M',31,50,51,1,37,1,'N','N'),(46,20,'M',23,25,51,1,37,1,'N','N'),(47,10,'M',23,25,51,1,37,1,'N','N'),(48,19,'M',23,25,51,1,37,1,'N','N'),(49,33,'M',23,25,51,1,37,1,'N','N'),(50,7,'M',23,25,51,1,37,1,'N','N'),(51,28,'M',23,25,51,1,37,1,'N','N'),(52,13,'M',23,25,51,1,37,1,'N','N'),(53,19,'M',23,25,51,1,37,1,'N','N'),(54,27,'M',23,25,51,1,37,1,'N','N');
/*!40000 ALTER TABLE `crawler_priority_communities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_search_history`
--

DROP TABLE IF EXISTS `crawler_search_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_search_history` (
  `SEARCH_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_ID` int(11) NOT NULL,
  `COMMUNITY_ID` int(11) NOT NULL,
  `ACCOUNT_ID` int(11) NOT NULL,
  `TIME` datetime NOT NULL,
  `PAGE_NO` tinyint(3) NOT NULL,
  `CRAWL_ERROR` char(1) NOT NULL,
  `CRAWL_ERROR_MESSAGE` text,
  `UNEXPECTED_RESPONSE` char(1) NOT NULL,
  `URL` text NOT NULL,
  `DATA` text NOT NULL,
  PRIMARY KEY (`SEARCH_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=467 DEFAULT CHARSET=latin1 COMMENT='log os all searches made on competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crawler_search_results`
--

DROP TABLE IF EXISTS `crawler_search_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_search_results` (
  `SEARCH_ID` int(11) NOT NULL,
  `SITE_ID` int(11) NOT NULL,
  `COMPETITION_ID` varchar(255) DEFAULT NULL,
  `DETAIL_VIEW_PARSED` char(1) NOT NULL,
  `CONTACT_DETAILS_PARSED` char(1) NOT NULL,
  `se` varchar(50) NOT NULL,
  `QUALITY` char(1) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `DTOFBIRTH` date NOT NULL,
  `BTIME` varchar(5) NOT NULL,
  `CITY_BIRTH` varchar(250) NOT NULL,
  `COUNTRY_BIRTH` tinyint(3) NOT NULL,
  `GENDER` char(1) NOT NULL,
  `MSTATUS` char(2) NOT NULL,
  `AGE` tinyint(4) NOT NULL,
  `BLOOD_GROUP` char(2) NOT NULL,
  `MTONGUE` tinyint(3) NOT NULL,
  `CITIZENSHIP` smallint(5) NOT NULL,
  `RELIGION` tinyint(3) NOT NULL,
  `CASTE` tinyint(3) NOT NULL,
  `COUNTRY_RES` tinyint(3) NOT NULL,
  `CITY_RES` varchar(4) NOT NULL,
  `EDU_LEVEL_NEW` tinyint(3) NOT NULL,
  `T_BROTHER` tinyint(3) NOT NULL,
  `T_SISTER` tinyint(3) NOT NULL,
  `DIET` char(1) NOT NULL,
  `DRINK` char(1) NOT NULL,
  `SMOKE` char(1) NOT NULL,
  `GOTHRA` varchar(250) NOT NULL,
  `SUBCASTE` varchar(250) NOT NULL,
  `HEIGHT` tinyint(3) NOT NULL,
  `STD` varchar(5) NOT NULL,
  `PHONE_RES` varchar(100) NOT NULL,
  `PHONE_MOB` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `DE_DUPED` char(1) NOT NULL,
  UNIQUE KEY `unique profile` (`SITE_ID`,`COMPETITION_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crawler_sites`
--

DROP TABLE IF EXISTS `crawler_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_sites` (
  `SITE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_URL` varchar(100) NOT NULL,
  `TO_BE_CRAWLED` char(1) NOT NULL,
  `PRIORITY` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`SITE_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='contains info of sites being crawled';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_sites`
--

LOCK TABLES `crawler_sites` WRITE;
/*!40000 ALTER TABLE `crawler_sites` DISABLE KEYS */;
INSERT INTO `crawler_sites` VALUES (1,'www.shaadi.com','Y',2),(2,'www.bharatmatrimony.com','Y',3),(3,'www.simplymarry.com','Y',1);
/*!40000 ALTER TABLE `crawler_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_sites_actions`
--

DROP TABLE IF EXISTS `crawler_sites_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_sites_actions` (
  `SITE_ID` int(11) NOT NULL,
  `ACTION` varchar(50) NOT NULL,
  `LOGIN_REQUIRED` char(1) NOT NULL,
  `PAID_LOGIN_REQUIRED` char(1) NOT NULL,
  `RESULTS_PER_PAGE` tinyint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='stores action specific parameters of competition sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_sites_actions`
--

LOCK TABLES `crawler_sites_actions` WRITE;
/*!40000 ALTER TABLE `crawler_sites_actions` DISABLE KEYS */;
INSERT INTO `crawler_sites_actions` VALUES (1,'search','','',10),(1,'detail_view','Y','',0),(1,'contact_detail_view','','Y',0),(2,'search','','',11),(2,'detail_view','Y','',0),(2,'contact_detail_view','','Y',0),(3,'search','','',20),(3,'detail_view','Y','',0),(3,'contact_detail_view','','Y',0);
/*!40000 ALTER TABLE `crawler_sites_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_sites_urls`
--

DROP TABLE IF EXISTS `crawler_sites_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_sites_urls` (
  `URL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITE_ID` int(11) NOT NULL,
  `ACTION` varchar(50) NOT NULL,
  `URL` varchar(200) NOT NULL,
  `REQUEST_METHOD` enum('POST','GET') DEFAULT NULL,
  PRIMARY KEY (`URL_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='contains base url of actions on each site to be crawled';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_sites_urls`
--

LOCK TABLES `crawler_sites_urls` WRITE;
/*!40000 ALTER TABLE `crawler_sites_urls` DISABLE KEYS */;
INSERT INTO `crawler_sites_urls` VALUES (1,1,'login','www.shaadi.com/registration/user/login2.php','POST'),(2,1,'search','ww2.shaadi.com/search/matrimonial/result','POST'),(3,1,'logout','www.shaadi.com/registration/user/logout.php','POST'),(4,1,'search_pagination','ww2.shaadi.com/search/matrimonial/result','GET'),(5,1,'detail_view','www.shaadi.com/partner_search/matrimonial_search/showprofile.php','GET'),(6,1,'contact_detail_view','www.shaadi.com/ssi/p-action/contact-details.php','POST'),(7,2,'login','profile.bharatmatrimony.com/login/memlogin.php','POST'),(8,2,'search','profile.bharatmatrimony.com/search/fetchrsearchresult.php','POST'),(9,2,'search_pagination','profile.hindimatrimony.com/search/fetchrsearchresult.php','POST'),(10,2,'logout','profile.hindimatrimony.com/login/logout.php','GET'),(11,2,'detail_view','profile.hindimatrimony.com/profiledetail/viewprofile.php','GET'),(12,2,'contact_detail_view','profile.hindimatrimony.com/assuredcontact/assuredinsertphonerequest.php','POST'),(13,3,'login','www.simplymarry.com/timesmatri/faces/jsp/login.jsp','POST'),(14,3,'logout','www.simplymarry.com/timesmatri/faces/jsp/logout.jsp','GET'),(15,3,'search','www.simplymarry.com/timesmatri/faces/jsp/searchResult.jsp','POST'),(16,3,'search_pagination','www.simplymarry.com/timesmatri/faces/jsp/searchResult.jsp','POST'),(17,3,'detail_view','www.simplymarry.com/timesmatri/faces/jsp/profileDetail.jsp','GET'),(18,3,'contact_detail_view','www.simplymarry.com/timesmatri/faces/jsp/vcd_paid.jsp','GET');
/*!40000 ALTER TABLE `crawler_sites_urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crawler_sites_urls_parameters`
--

DROP TABLE IF EXISTS `crawler_sites_urls_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crawler_sites_urls_parameters` (
  `URL_ID` int(11) NOT NULL,
  `PARAMETER` varchar(50) NOT NULL,
  `FIELD_NAME` varchar(50) NOT NULL,
  `PARENT_CLASS` varchar(50) NOT NULL,
  `TYPE` enum('VARIABLE','COOKIE','HEADER') NOT NULL,
  `MAPPING_REQUIRED` char(1) NOT NULL,
  `DYNAMIC` char(1) NOT NULL,
  `VALUE` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='stores parameters to be sent for different urls';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crawler_sites_urls_parameters`
--

LOCK TABLES `crawler_sites_urls_parameters` WRITE;
/*!40000 ALTER TABLE `crawler_sites_urls_parameters` DISABLE KEYS */;
INSERT INTO `crawler_sites_urls_parameters` VALUES (1,'login','USERNAME','CrawlerUser','VARIABLE','','',''),(1,'password','PASSWORD','CrawlerUser','VARIABLE','','',''),(2,'gender','GENDER','CrawlerPriorityCommunity','VARIABLE','Y','',''),(2,'agefrom','LAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(2,'ageto','HAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(2,'mothertonguearray[]','MTONGUE','CrawlerPriorityCommunity','VARIABLE','Y','',''),(2,'countryofresidence','COUNTRY_RES','CrawlerPriorityCommunity','VARIABLE','Y','',''),(4,'pg_show_from','pageNo','Crawler','VARIABLE','','Y',''),(4,'pg_searchresults_id','actionId','Crawler','VARIABLE','','Y',''),(5,'profileid','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',''),(6,'profileid','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',''),(6,'se','se','CrawlerCompetitionProfile','VARIABLE','','',''),(6,'show_number','','','VARIABLE','','','Y'),(7,'ID','USERNAME','CrawlerUser','VARIABLE','','',''),(7,'PASSWORD','PASSWORD','CrawlerUser','VARIABLE','','',''),(9,'COUNTRYRIGHT','COUNTRY_RES','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'DISPLAY_FORMAT','DISPLAY_FORMAT','','VARIABLE','','','one'),(9,'ENDAGE','HAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(9,'ENDHEIGHT','HHEIGHT','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'GENDER','GENDER','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'MARITAL_STATUS','MSTATUS','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'MOTHERTONGUERIGHT','MTONGUE','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'RELIGION','RELIGION','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'SAVE_TYPE','SAVE_TYPE','','VARIABLE','','','R'),(9,'SEARCH_TYPE','SEARCH_TYPE','','VARIABLE','','','QUICK'),(9,'STAGE','LAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(9,'STHEIGHT','LHEIGHT','CrawlerPriorityCommunity','VARIABLE','Y','',''),(9,'STLIMIT','pageNo','Crawler','VARIABLE','','Y',''),(9,'facet','facet','','VARIABLE','','','N'),(9,'wherefrom','wherefrom','','VARIABLE','','','frmpaging'),(11,'id','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',''),(12,'matid','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',''),(12,'userphoneavailable','userphoneavailable','','VARIABLE','','','1'),(13,'jsf_state_64','jsf_state_64','','VARIABLE','','','H4sIAAAAAAAAAM1WS2wbRRgev%2BqkTUPitEkRtGwJrwqyeZRUYCPR2E4US3ZSxQ4gOGzHu2N7k%2FXOMvs73lClogfKgQsHioRUBAfgQu8gbggkTiAB4sIJceAEXHlcYGbWbztqAkViLI1n%2F5n9%2F3%2B%2B73%2FsrV9QpMbQxAvZLbyDVQvbZXW9uEV0SLz%2B1XPvjLnnrCBCnoMQCtVeRFeRGOHWKurw4TI0Lt%2BugWmpq9it5LATiX7%2F6WeTl78OoeAKOmpRbKxgHSjLoGGoMOJWqGV4ztMXpZqR%2BhCfx8TSk%2FrG2vqyVMcWufr7%2BOWbc3%2F8HEThDBqqcBs6NUgWRXVas4HtAorJG8yKG8zmgZl2OZFFQ%2BKxhstEOBzlx3cwM7EN8tFz%2FuIDEAIUJLYQDQM6ulrIZbXkUj6TAjQ6u%2BU6sxYtm7bKV8KzibZnS4zh3azpgnftm9NvfYHfDqFABoVd8yUiAQvUw2JugdUGcLINIKBhqb9EWdUB%2F%2BKeWsI6cdUVLhMHY66Yw31ghQCN8Pd0kjEytkG8FhGSxiSlFsH2lwp7%2Bbubf%2F4aRIHnUWQHWzXuXQDQJcrKKnawXiFqddc3yAhXw7ZNUCtQ5VTySfiwIcWEJbFL1NVMOr28pqXWc7mltbSWWbu0Wchr%2BeVCfxjkCXySfuXGmx9%2F9HhIMFsfEY43rsFdmGrdPK5Zpr2tVUzDILbmAVroxKFOithx1M1MilYdahMbCrisrqxv5JY3tNRqJsvdSOclSHd32RgCFNZMY97%2FmwMUcuoGoAi%2Fs74NKKADjxDhQxJs%2F8wCoGOEMcr8JPA8p28AOsEI1JitSDVJDvb2I%2BcSck%2F6cFxYltwPDeA%2BMkAWFYsHe%2BkvEA8AnWyD1OHYPhEREOKTYprynK4zAXkm6AMfLZq2wTOEU%2FZop0W9ia%2BqLQGI0DDygIE8yzj%2BhKU%2F%2FOni3rfrnwdRMIuOaLqFXRfQeEfipYSI592EVpev%2BK%2F7LnenaKPIeM4OQw8NCEViqc%2BIWE36nmaqjoXawxMsTF%2FJyszsgGWvxdcAlEcHFTCxuE9o68QhR1yXFw3XOQjMjvzvC5PDOHBG6njqDpu7p7Ua3i%2Fm7pfaHuNB344y%2Fd%2FElhDPyc15MV3gFapJU800JD1ydxL1XKS7dmVsIGXCYj%2B%2B%2B95v1159IigKa6N2NZuDPLdWqxYJu37rxuljb%2FzwWrNTnenBRlpc4xunAL1fIWa5AvHz5x0voZSoDTOiYMfnL4hnnVqUxaefXBS%2FxnYJV01rN15gpFjjEQpKLp9QipTxkhifdzzFpZZpKNPYEL%2BEskMYmLxlzWDLLNvxKi9pFkkoddOASnxhcY6bcbAhYnoGqBNfFGabAouUQEr2o%2FTe21Oq8GCOdQZznui8XgE63qaYV8H%2FiGSuWZIs5Lleips0nOgN3k6CPvgfEKT8c4ZO3Z6hs73lJiX6SJHytndXRxoK4aFZCskzovcEoZExXUSNNYmS6omx189lrHlE%2BqJXMOzL6KBxmJb3gF9%2BDlb3OGpTKWrbvMwrQJUUd0yxlRxmbPcAJTDSbXm6N0uSNQAeTCjWZqD5ZXBnU2W0C949z9%2FkXfDhwV0wR6BCjf3aYOtWnG5l%2BsqSDia1k%2FyzTwWzSlwB0qZLmLS41%2Fh8icgnsTqrqrNmVXQ6%2FyNXs0m9sSqCrZbNUh%2B9gI4UJVR%2BKFx3HM%2F7G6UCTbpFDAAA'),(13,'jsf_tree_64','jsf_tree_64','','VARIABLE','','','H4sIAAAAAAAAAK2Sz07bQBDGh0BEwh9V0ANXpFaCImTTiltuiQQkCkIiUKnKIVrWE8epvbusx8VcuHHnAXiCiifoE%2FTGlZfolTNjDHEOSObQy2pnNL%2Fv%2B2btu39QjS3sa%2Bs7wgg5Qie6HAqJMZcmDKSgQCvnxCL2yCaSEouHQgkf7eei2dKR0QoV7f1Ye7jtPN5XYK4P9YEcBaFnuQ%2Bdfpct3NzCfbFwpyzc91o0uvBhIF%2BrVijimGC1Oxa%2FhBsK5bs8Hyifx5aKsbZ3DldQ6cP8IHMmRj72p5ijszFKaqQmsS9R%2F8drNP7cbfxdub75XQFIDQDMxFkMSLKzynUtrw3BlyxL6uRek9jOiKLQOeDjKCGT0AmmRLCI1mqbRzYThY0ShUOMYw7Km88NAm%2FnFczO5bz3tRDbLBFrq0maGVlgW%2B%2FBeigtMjhrLrwC3S1Bexjyuk2tQxSqNUL580ynBFWZXaeXWciX%2BVZIb5dI8%2FeKhPKaCZHmX7UWaj9QTVKMfipB97SNCOrPxJDvjKy%2FjZy2vwd4caw1GfME8UmDvnUDAAA%3D'),(13,'jsf_viewid','jsf_viewid','','VARIABLE','','','%2Fjsp%2Flogin.jsp'),(13,'loginform%3A_link_hidden_','loginform%3A_link_hidden_','','VARIABLE','','',NULL),(13,'loginform%3Ac','USERNAME','CrawlerUser','VARIABLE','','',NULL),(13,'loginform%3AloginBtn.x','loginform%3AloginBtn.x','','VARIABLE','','','68'),(13,'loginform%3AloginBtn.y','loginform%3AloginBtn.y','','VARIABLE','','','9'),(13,'loginform%3Apwd','PASSWORD','CrawlerUser','VARIABLE','','',NULL),(13,'loginform_SUBMIT','loginform_SUBMIT','','VARIABLE','','','1'),(16,'action','action','','VARIABLE','','','SearchResultActionBean.performSearchNewAction'),(2,'community','RELIGION','CrawlerPriorityCommunity','VARIABLE','Y','',NULL),(8,'wherefrm','wherefrm','','VARIABLE','','','paging'),(8,'facet','facet','','VARIABLE','','','N'),(8,'STHEIGHT','LHEIGHT','CrawlerPriorityCommunity','VARIABLE','Y','',''),(8,'STAGE','LAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(8,'SEARCH_TYPE','SEARCH_TYPE','','VARIABLE','','','REGULARSEARCH'),(8,'SAVE_TYPE','SAVE_TYPE','','VARIABLE','','','R'),(8,'STLIMIT','STLIMIT','','VARIABLE','','','1'),(8,'COUNTRYRIGHT','COUNTRY_RES','CrawlerPriorityCommunity','VARIABLE','Y','',''),(8,'DISPLAY_FORMAT','DISPLAY_FORMAT','','VARIABLE','','','one'),(8,'RELIGION','RELIGION','CrawlerPriorityCommunity','VARIABLE','Y','',NULL),(8,'ENDAGE','HAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(8,'ENDHEIGHT','HHEIGHT','CrawlerPriorityCommunity','VARIABLE','Y','',''),(8,'MARITAL_STATUS','MSTATUS','CrawlerPriorityCommunity','VARIABLE','Y','',NULL),(8,'GENDER','GENDER','CrawlerPriorityCommunity','VARIABLE','Y','',''),(8,'MOTHERTONGUERIGHT','MTONGUE','CrawlerPriorityCommunity','VARIABLE','Y','',''),(16,'searchResultForm_SUBMIT','searchResultForm_SUBMIT','','VARIABLE','','','1'),(16,'viewType','viewType','','VARIABLE','','',''),(15,'ag','AGE_RANGE','CrawlerPriorityCommunity','VARIABLE','','',NULL),(15,'community1_dis','MTONGUE','CrawlerPriorityCommunity','VARIABLE','Y','',NULL),(15,'cn','COUNTRY_RES','CrawlerPriorityCommunity','VARIABLE','Y','',''),(15,'gn','GENDER','CrawlerPriorityCommunity','VARIABLE','Y','',''),(15,'indexForm%3A_id16.x','indexForm%3A_id16.x','','VARIABLE','','','47'),(15,'indexForm%3A_id16.y','indexForm%3A_id16.y','','VARIABLE','','','17'),(15,'maxAge','HAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(15,'minAge','LAGE','CrawlerPriorityCommunity','VARIABLE','','',''),(15,'mt','MTONGUE','CrawlerPriorityCommunity','VARIABLE','Y','',''),(15,'rg','RELIGION','CrawlerPriorityCommunity','VARIABLE','Y','',''),(15,'action','action','','VARIABLE','','','SearchResultActionBean.searchResult_initAction'),(16,'cols','cols','','VARIABLE','','',NULL),(16,'jsCall','jsCall','','VARIABLE','','',NULL),(16,'jsf_tree_64','jsf_tree_64','','VARIABLE','','','H4sIAAAAAAAAAK1avY%2FcxhXn3p3sSBZi2S5SGAmcD8CxE%2ByR84YfAyHNnWDpnJNl%2BGQjgWAceLs8La1dkuLOyqcmXXr3iZE%2B8F%2BQNilSxVWAFOnSBUjhJnVIDpecRz4uJ4maxb7H997MvJn3m98M%2BdW%2FrGvr3Lqb5o%2BnYRbOFtF09fwynEXrQsyW8SyUcZpMH%2BZRdCbzzUxu8uh%2BmISPo%2FxHrfI4XWVpEiXyvV9%2B52%2B%2Fff%2FfX%2B9ZB4%2Bs6%2BezRbyc54Xeev%2FRadHEoWrisG7iUGvi0LSJ26fWq%2BezrXS8DNdrab1%2B%2Bln4LDxchsnjw8I%2BTh4XZjdbs5P5U%2BtX1t4j6%2BXzsmVZuLzxSPN5cPFZNJO3r7JNXnf1RWTj9h%2B%2BevvPr%2F36i9%2FvWdZVZlnW%2FrrshrUpf68V8l5HPujIEyVn0nqn7OvVVPWlGdZ0IVfL6b3i58FGZhv5MLqS0jo4j%2BfM2Oc0Tp4oHyeju5eVv99WNrxrM%2BnbeJVwSwmuQUc%2BDJNoeTdPN5nyAYOOBCMduVYOyNZ7IirhTSX4I01U7iwjlLANU0mOHtTOmvk6JJbQbLOW6Wq6Dp9FaxnKaPrxyVnx%2F6z8X4fjhevbdLY%2BPvkgXBUL%2BzhNZBgnUS6tveSZ3kMmrRtPN%2FHsyfmymNPuAG%2BMrL29pu8%2FGZmuu3mYLeLZyapY93W%2Fe%2BmcdOT9JvpPR6IX1bMKk%2FnRRspipNbNi3Adzz6Jo8%2BPZNLm9%2FtDSfowzMNVJMv0lD1jtp6hu7XSKSK8a9YPVR7VIEXh9WOzpRzPa58ArRZv16r7Wd253uofymX5e6fIkFxsVhedDOmj9SilX0k%2FryW3ku7XEtc7zZj%2BzHGJmmBBR3lzHYX5bFHNxXrXmLe9EURUaKr31VU6jy%2Bfn1VBT5uljYx74EXgAYA%2BaGBdl5c78ksjE9GVuyU19ry7HUz6q4G7epf5TvjduqCp5b01R7mIbaorCS1aDnq6a6Vr64uCM%2BRgD8x4d5sryukHQ2V8Fi2LDflERqu6QYTFX9ZKoJScUroGyKPafJBE96NkI63rebTeLOXZ0XODDLoo6a6HkuXoyQKhP4MAPRvFUUuPK3Rfd2hH%2FJ%2BXa1d%2Bq5k2bpTJozRdRmFyvIhmTy7SqyKjs%2FqvSbF6gb4mPT%2FrJ90TYzOTLVKZ6nF8k6Z92LrsZwuHQBt%2FjAZVRq4WhaIS%2Fs7S3Br5WhSgopgwIV9oUTgRJegVLRElcLQo1EYQkKFJS59SdveRUilsSknNiqDSI3pTRey5gmwYgaLoleY%2BjvNSRTgho7TdtChtsz6UyEjXsSVeWTkOCuXsnMzGCbAT2b7DSa1Laj1S261cpQ1ILcJGz9PxzXPRs1GW1JW%2Fq7X3Rw2LmmGPU8KTpDgv3Yvn8%2FJEe5CVp6zdRVMNigUoz4zMBxufZ4RlldMo6VFWzTwfnPfhTJmM4ZmycvVAXURTJmOQpqx8PVC3apXJGKopK6EHIqusz0aoQNzRA3UB6%2BAijy6NwuCS7tO1PWLqeZf8VlrXxqF6%2BaDgxyWr1e2OR2k91IBLIpdnhCQeQ6G8ncuyceLYiW6fBBqPBJoeRVBaEmg8QWl9m9Q6pLbhvKp%2BG6xSooufQoEu3xviusfpcrNKalwrL2684cuDJPp8nYVZlFeA9MFWehheLIuz%2BCuXUVjeSd0JZYiwc2hGXhg3HPPv3j6gFVH%2BfI1WQ2CEI0GNrOXPX6k1HuxE1a2VsNswf6fCCCOcFawN8w8yzNCeha14G%2BafZBjXKIzXhvkGLcag5z822Tf0rbP8%2BZNBD5iNCpyR1IjZVNEyhypE5lCFyEjewhyyNWfs%2FKys0IbNBi%2B3kBOzsZPQc87s5gioxCFYJVO%2BTSijNjiGr3cabZfRVlrocmmlpbZyBmRr%2FQ2e2NIYJ9vhiKUy6BUnsaUxTk4kJzuHt1%2FGe%2FSMbIBcgZzMX3tPokQPTXJ7U6ZER3%2FqCKE%2FdQKOngaAn%2BLdZfB0Nii%2F2SnZiQnDY5gTMHcn0pSwPTnCDiasjXkt3k7uUWneTSAaqxZvJw%2FIMCZ4y7wWbyefkGFM8JZ5Ld5OPiXD7ISRxqrd0iZzFKbUmJBQ5iM2xvo3HlTF%2BiSKtJcPSuxlgiqogITpgASZAJ1BWUDifmAEwAGuzWDnUtw6CQxJgmxfkDuMIIFJUAycCYrGMkHRWCZIPBIUjWUC7zIuRwDkAn5ajXXymy2BrXwnXxbHm7lGF9WzIfrVu4cszcFG5BfsoYJ54TeRW3QzIiTgoO0ByKsN6L%2Bj2U1VwTHBPGAax6SoKjATzAOmcUyKqsLg2yNspXFMiqoCM8E8YBrHpKgqMBPMA6bR%2BG%2BoMEBdWgBJW4CkLQDUERigewmptNTWDySlAW4CrsDJ1jnabKF%2FWUCAK7gUQoFLJshluAHqzAvu2PshZYUoMbgmiAyejZ3I9j1qtwCPglwg7wjAI%2BuYvDkADzE2cBDrAofhp%2F8160J3jE811lVBrVf%2BGFWEj5HKJ0e4%2B%2FVBn53B6JuCyioYYWcQGCFVMMLOIDBCqmCEnUH%2FNEuGGWFnsJtjNFb%2FNzsDgYuyf7VAAYig2BkIH4cyYWfcpuqN2xSWchuxM25T2MNtEyzgtsChTNgZx681OHkrwMnzPyfP%2F5zc8jn5NoOTbzM4%2BTaDk28zOL4DAB%2BDDb5ABN%2FW2RnYDTt7ZR6HqzSZ3%2BmQNLDxq%2BMeGgx8XdBSOGn5wxeNJSdcz%2FJ0uazvGsv2z2pFPUDmbvLtd3TT8ju6af0d3Rd%2F%2BcXvbq3fWW6%2Fe9srG92v%2FknrW1kePYvTzZpYcQVh0E2vXcb5WlJ2CMc5GzrdIr66n2zNBfYefXOOvMFpu6h39mAZkn0FQGM6SKIr0gytBw62Ptm8z%2Bv2qBgU0eCA6Dk3etnD2w8TlIjWvb3VCkqLqNLEk9b1aB7L6mOgyo4Vi1p9IBQns3jeMVYhGKkFUlt%2BNPfDkVd376X5Slq3VLMfVR92lKrC862ha%2Fnyi6qP0lRm2X8AAsxa3yUrAAA%3D'),(16,'jsf_state_64','actionId2','Crawler','VARIABLE','','Y',''),(16,'jsf_viewid','jsf_viewid','','VARIABLE','','','%2Fjsp%2FsearchResult.jsp'),(16,'matchTechId','matchTechId','','VARIABLE','','',NULL),(16,'searchResultForm%3A_id442','actionId','Crawler','VARIABLE','','Y',NULL),(16,'searchResultForm%3A_id443','searchResultForm%3A_id443','','VARIABLE','','',NULL),(16,'searchResultForm%3A_id444','searchResultForm%3A_id444','','VARIABLE','','',NULL),(16,'searchResultForm%3A_link_hidden_','pageNo','Crawler','VARIABLE','','Y',NULL),(16,'searchResultForm%3Asearchincid%3A_id429','pageNo2','Crawler','VARIABLE','','Y',NULL),(16,'searchResultForm%3Asearchincid%3A_id431','searchResultForm%3Asearchincid%3A_id431','','VARIABLE','','',NULL),(16,'searchResultForm%3Asearchincid%3AresultSBy','searchResultForm%3Asearchincid%3AresultSBy','','VARIABLE','','','Browse'),(16,'searchResultForm%3Asearchincid%3AeditParam','searchResultForm%3Asearchincid%3AeditParam','','VARIABLE','','','advancedSearch'),(17,'profileId','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',NULL),(18,'detailProfile','COMPETITION_ID','CrawlerCompetitionProfile','VARIABLE','','',NULL);
/*!40000 ALTER TABLE `crawler_sites_urls_parameters` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-05-27 17:53:27
