-- MySQL dump 9.08
--
-- Host: localhost    Database: jsadmin
---------------------------------------------------------
-- Server version	4.0.14-standard

--
-- Table structure for table 'NEWSPPR_SOURCE'
--

CREATE TABLE NEWSPPR_SOURCE (
  ID tinyint(3) NOT NULL auto_increment,
  VALUE varchar(250) NOT NULL default '',
  LABEL varchar(250) NOT NULL default '',
  SORTBY tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

--
-- Dumping data for table 'NEWSPPR_SOURCE'
--

INSERT INTO NEWSPPR_SOURCE VALUES (1,'afl_nphtd','Hindustan Times ( Delhi )',11);
INSERT INTO NEWSPPR_SOURCE VALUES (2,'afl_nptoid','Times Of India ( Delhi )',22);
INSERT INTO NEWSPPR_SOURCE VALUES (3,'afl_np_bt','Bhaskar Times',2);
INSERT INTO NEWSPPR_SOURCE VALUES (4,'afl_nptoim','Times Of India (Mumbai)',23);
INSERT INTO NEWSPPR_SOURCE VALUES (5,'afl_enp_ie','Indian Express (English)',13);
INSERT INTO NEWSPPR_SOURCE VALUES (6,'afl_npdhb','Deccan Herald ( Bangalore )',6);
INSERT INTO NEWSPPR_SOURCE VALUES (7,'afl_np_mm','Malyalam Manorama',14);
INSERT INTO NEWSPPR_SOURCE VALUES (8,'afl_hnp_ht','Hindustan (Hindi)',10);
INSERT INTO NEWSPPR_SOURCE VALUES (9,'afl_hnp_db','Dainik Bhaskar (Hindi)',3);
INSERT INTO NEWSPPR_SOURCE VALUES (10,'afl_hnpdjd','Dainik Jagran ( Hindi , Delhi )',4);
INSERT INTO NEWSPPR_SOURCE VALUES (11,'afl_gnp_db','Divya Bhaskar (Gujarati)',7);
INSERT INTO NEWSPPR_SOURCE VALUES (12,'afl_hnpntd','Navbharat ( Hindi , Delhi )',15);
INSERT INTO NEWSPPR_SOURCE VALUES (13,'afl_hnp_au','Amar Ujala (Hindi)',1);
INSERT INTO NEWSPPR_SOURCE VALUES (14,'afl_hnp_rp','Rajasthan Patrika (Hindi)',18);
INSERT INTO NEWSPPR_SOURCE VALUES (15,'afl_hnppkd','Punjab Kesari ( Delhi )',16);
INSERT INTO NEWSPPR_SOURCE VALUES (16,'afl_nphuch','Hindu ( Chennai )',8);
INSERT INTO NEWSPPR_SOURCE VALUES (17,'afl_nptgc','The Telegraph ( Calcutta )',20);
INSERT INTO NEWSPPR_SOURCE VALUES (18,'afl_npdch','Deccan Chronicle ( Hyderabad )',5);
INSERT INTO NEWSPPR_SOURCE VALUES (19,'afl_np_stn','Statesman',19);
INSERT INTO NEWSPPR_SOURCE VALUES (20,'afl_hnppkj','Punjab Kesari ( Jalandhar )',17);
INSERT INTO NEWSPPR_SOURCE VALUES (21,'afl_nptoia','Times Of India ( Ahmedabad )',21);
INSERT INTO NEWSPPR_SOURCE VALUES (22,'afl_nphtp','Hindustan Times ( Patna )',12);
INSERT INTO NEWSPPR_SOURCE VALUES (23,'afl_nphud','Hindu ( Delhi )',9);

