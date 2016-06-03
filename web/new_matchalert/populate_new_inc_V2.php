<?php
/*********************************************************************************************
* FILE NAME     : populate_inc.php
* DESCRIPTION   : Populates the tables before using them.
* CREATION DATE : 8 July, 2005
* CREATED BY    : Shakti Srivastava
* MODIFIED BY   : Lavesh Rawat on nov 2007
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

function populate()
{
	$date = new DateTime();
	$date->sub(new DateInterval('P2D'));
	$removeNotPhotoProfilesDate = $date->format('Y-m-d');

	$sql="TRUNCATE TABLE matchalerts.JPARTNER";
	$result=mysql_query($sql) or logerror1("In populate_inc.php at QUERY 1 OF TABLE NO :$i",$sql,"NO","Y");

	$sql="TRUNCATE TABLE matchalerts.TRENDS_SEARCH_MALE";
	$result=mysql_query($sql) or logerror1("In populate_inc.php at QUERY 1 OF TABLE NO :$i",$sql,"NO","Y");

	$sql="TRUNCATE TABLE matchalerts.TRENDS_SEARCH_FEMALE";
	$result=mysql_query($sql) or logerror1("In populate_inc.php at QUERY 1 OF TABLE NO :$i",$sql,"NO","Y");

	passthru(JsConstants::$php5path." -q shard_jpartner_dump.php >> ".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");

	$tablename[0]="BOOKMARKS";
	$tablename[1]="CASTE";
	$tablename[2]="CITY_NEW";
	$tablename[3]="CITY_NEW";
	$tablename[4]="HEIGHT";
	$tablename[5]="OCCUPATION";
	$tablename[6]="FILTERS";
	$tablename[7]="MTONGUE";
	$tablename[8]="BRANCHES";
	$tablename[9]="IGNORE_PROFILE";

	$sql="TRUNCATE TABLE  matchalerts.JPROFILE";
	$result=mysql_query($sql) or logerror1("In populate_inc.php at truncation of JPROFILE",$sql,"NO","Y");

	$sql="ALTER TABLE  matchalerts.JPROFILE DISABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while disabling keys of JPROFILE",$sql,"NO","Y");

	if($_SERVER['argv'][2]!="")
		$interval=$_SERVER['argv'][2]." DAY";
	else
		$interval="6 MONTH";
  
	$day_of_week=date("w");
  if( in_array($day_of_week,array('1','3','5'))){
    $conditionNew = "PERSONAL_MATCHES in ('A','O') AND ";
  }else{
    $conditionNew = "PERSONAL_MATCHES='A' AND ";
  }
  $conditionNew.= "(ACTIVATED='Y' OR ACTIVATED = 'N') AND ";
  $conditionNew .= "(((jp.ENTRY_DT >= DATE_SUB( now( ) , INTERVAL 15 DAY )) || (jp.ENTRY_DT < DATE_SUB( now( ) , INTERVAL 15 DAY )) && (jp.MOB_STATUS = 'Y' || jp.LANDL_STATUS = 'Y' || jpc.ALT_MOB_STATUS = 'Y')) && (jp.LAST_LOGIN_DT >= DATE_SUB( now( ) , INTERVAL 3 MONTH )))";
  
  $sql="INSERT INTO  matchalerts.JPROFILE SELECT jp.PROFILEID,jp.USERNAME,jp.PASSWORD,jp.GENDER,jp.RELIGION,jp.CASTE,jp.SECT,jp.MANGLIK,jp.MTONGUE,jp.MSTATUS,jp.DTOFBIRTH,jp.OCCUPATION,jp.COUNTRY_RES,jp.CITY_RES,jp.HEIGHT,jp.EDU_LEVEL,jp.EMAIL,jp.IPADD,jp.ENTRY_DT,jp.MOD_DT,jp.RELATION,jp.COUNTRY_BIRTH,jp.SOURCE,jp.INCOMPLETE,jp.PROMO,jp.DRINK,jp.SMOKE,jp.HAVECHILD,jp.RES_STATUS,jp.BTYPE,jp.COMPLEXION,jp.DIET,jp.HEARD,jp.INCOME,jp.CITY_BIRTH,jp.BTIME,jp.HANDICAPPED,jp.NTIMES,jp.SUBSCRIPTION,jp.SUBSCRIPTION_EXPIRY_DT,jp.ACTIVATED,jp.ACTIVATE_ON,jp.AGE,jp.GOTHRA,jp.GOTHRA_MATERNAL,jp.NAKSHATRA,jp.MESSENGER_ID,jp.MESSENGER_CHANNEL,jp.PHONE_RES,jp.PHONE_MOB,jp.FAMILY_BACK,jp.SCREENING,jp.CONTACT,jp.SUBCASTE,jp.YOURINFO,jp.FAMILYINFO,jp.SPOUSE,jp.EDUCATION,jp.LAST_LOGIN_DT,jp.SHOWPHONE_RES,jp.SHOWPHONE_MOB,jp.HAVEPHOTO,jp.PHOTO_DISPLAY,jp.PHOTOSCREEN,jp.PREACTIVATED,jp.KEYWORDS,jp.PHOTODATE,jp.PHOTOGRADE,jp.TIMESTAMP,jp.PROMO_MAILS,jp.SERVICE_MESSAGES,jp.PERSONAL_MATCHES,jp.SHOWADDRESS,jp.UDATE,jp.SHOWMESSENGER,jp.PINCODE,jp.PARENT_PINCODE,jp.PRIVACY,jp.EDU_LEVEL_NEW,jp.FATHER_INFO,jp.SIBLING_INFO,jp.WIFE_WORKING,jp.JOB_INFO,jp.MARRIED_WORKING,jp.PARENT_CITY_SAME,jp.PARENTS_CONTACT,jp.SHOW_PARENTS_CONTACT,jp.FAMILY_VALUES,jp.SORT_DT,jp.VERIFY_EMAIL,jp.SHOW_HOROSCOPE,jp.GET_SMS,jp.STD,jp.ISD,jp.MOTHER_OCC,jp.T_BROTHER,jp.T_SISTER,jp.M_BROTHER,jp.M_SISTER,jp.FAMILY_TYPE,jp.FAMILY_STATUS,jp.FAMILY_INCOME,jp.CITIZENSHIP,jp.BLOOD_GROUP,jp.HIV,jp.THALASSEMIA,jp.WEIGHT,jp.NATURE_HANDICAP,jp.ORKUT_USERNAME,jp.WORK_STATUS,jp.ANCESTRAL_ORIGIN,jp.HOROSCOPE_MATCH,jp.SPEAK_URDU,jp.PHONE_NUMBER_OWNER,jp.PHONE_OWNER_NAME,jp.MOBILE_NUMBER_OWNER,jp.MOBILE_OWNER_NAME,jp.RASHI,jp.SUNSIGN,jp.TIME_TO_CALL_START,jp.TIME_TO_CALL_END,jp.MOB_STATUS,jp.LANDL_STATUS,jp.PHONE_FLAG,jp.PHONE_WITH_STD,jp.CRM_TEAM,jp.activatedKey,jp.PROFILE_HANDLER_NAME,jp.GOING_ABROAD,jp.OPEN_TO_PET,jp.HAVE_CAR,jp.OWN_HOUSE,jp.COMPANY_NAME,jp.HAVE_JCONTACT,jp.HAVE_JEDUCATION,jp.JSARCHIVED,jp.SEC_SOURCE,jp.SERIOUSNESS_COUNT,jp.ID_PROOF_TYP,jp.ID_PROOF_NO,jp.VERIFY_ACTIVATED_DT FROM newjs.JPROFILE  as jp LEFT JOIN newjs.JPROFILE_CONTACT as jpc ON jpc.PROFILEID = jp.profileid WHERE ".$conditionNew;
	$result=mysql_query($sql) or logerror1("In populate_inc.php while inserting data in JPROFILE",$sql,"NO","Y");

	$sql="ALTER TABLE  matchalerts.JPROFILE ENABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while enabling keys of JPROFILE",$sql,"NO","Y");

	//Now we populate the remaining tables with the data from the 'newjs' database.
	for($i=0;$i<=9;$i++)
	{ 
		$sql="TRUNCATE TABLE  matchalerts.$tablename[$i]";
		$result=mysql_query($sql) or logerror1("In populate_inc.php at QUERY 1 OF TABLE NO :$i",$sql,"NO","Y");

		$sql="ALTER TABLE  matchalerts.$tablename[$i] DISABLE KEYS";
		$result=mysql_query($sql) or logerror1("In populate_inc.php at QUERY 2 OF TABLE NO :$i",$sql,"NO","Y");

		$sql="INSERT INTO  matchalerts.$tablename[$i] SELECT * FROM newjs.$tablename[$i]";
		$result=mysql_query($sql) or mail("lavesh.rawat@gmail.com","matchalerts $tablename[$i] structure changed","matchalerts $tablename[$i] structure changed");

		$sql="ALTER TABLE  matchalerts.$tablename[$i] ENABLE KEYS";
		$result=mysql_query($sql) or  logerror1("In populate_inc.php at QUERY 4 OF TABLE NO :$i",$sql,"NO","Y");
	}

	//Now Populating Trends table.


        $today=date("Y-m-d");
        $ts = time();
        $ts-=3*24*60*60;
        $dtdt=date("Y-m-d",$ts);
        $sql="REPLACE INTO  matchalerts.TRENDS SELECT * FROM twowaymatch.TRENDS WHERE ENTRY_DT>'$dtdt'";
        $result=mysql_query($sql) or mail("lavesh.rawat@gmail.com"," matchalerts.TRENDS structure changed","matchalerts.TRENDS structure changed");

	$sql="TRUNCATE TABLE  matchalerts.SEARCH_FEMALE";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while truncating SEARCH_FEMALE",$sql,"NO","Y");

	$sql="ALTER TABLE  matchalerts.SEARCH_FEMALE DISABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while disabling keys for SEARCH_FEMALE",$sql,"NO","Y");

        $sql="INSERT IGNORE INTO  matchalerts.SEARCH_FEMALE (PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,ENTRY_DT,EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,HAVEPHOTO) SELECT PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,DATE(ENTRY_DT),EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,'N' FROM newjs.SEARCH_FEMALE WHERE HAVEPHOTO<>'Y'";
	$sql.=" AND ENTRY_DT<'$removeNotPhotoProfilesDate'" ;
        $result=mysql_query($sql) or logerror1("In populate_inc.php while inserting data into SEARCH_FEMALE",$sql,"NO","Y");

        $sql="INSERT IGNORE INTO  matchalerts.SEARCH_FEMALE (PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,ENTRY_DT,EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,HAVEPHOTO) SELECT PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,DATE(PHOTODATE),EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,'Y' FROM newjs.SEARCH_FEMALE WHERE HAVEPHOTO='Y'";
        $result=mysql_query($sql) or logerror1("In populate_inc.php while inserting data into SEARCH_FEMALE",$sql,"NO","Y");

        $sql="SELECT MAX(ENTRY_DT) as max FROM  matchalerts.SEARCH_FEMALE";
        $result=mysql_query($sql) or logerror1("In populate_inc.php --search",$sql,"NO","Y");
        $row=mysql_fetch_array($result);
        $max=$row["max"];
        if($max)
        {
                $max_arr=explode("-",$max);
                $date_temp  = mktime(0, 0, 0,$max_arr[1],$max_arr[2]-1,$max_arr[0]);
                $max_minus1=date("Y-m-d",$date_temp);
                $sql="UPDATE  matchalerts.SEARCH_FEMALE SET ENTRY_DT='$max_minus1' where ENTRY_DT='$max'";
                mysql_query($sql) or logerror1("In populate_inc.php --search1",$sql,"NO","Y");
        }

	$sql="ALTER TABLE  matchalerts.SEARCH_FEMALE ENABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while enabling keys for SEARCH_MALE",$sql,"NO","Y");

	$sql="TRUNCATE TABLE  matchalerts.SEARCH_MALE";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while truncating SEARCH_MALE",$sql,"NO","Y");

	$sql="ALTER TABLE  matchalerts.SEARCH_MALE DISABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while disabling keys for SEARCH_MALE",$sql,"NO","Y");

        $sql="INSERT IGNORE INTO  matchalerts.SEARCH_MALE (PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,ENTRY_DT,EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,HAVEPHOTO) SELECT PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,DATE(ENTRY_DT),EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,'N' FROM newjs.SEARCH_MALE WHERE HAVEPHOTO<>'Y'";
	$sql.=" AND ENTRY_DT<'$removeNotPhotoProfilesDate'" ;
        $result=mysql_query($sql) or logerror1("In populate_inc.php while inserting data into SEARCH_MALE",$sql,"NO","Y");

        $sql="INSERT IGNORE INTO  matchalerts.SEARCH_MALE (PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,ENTRY_DT,EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,HAVEPHOTO) SELECT PROFILEID,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,DRINK,SMOKE,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,AGE,INCOME,RELATION,SORT_DT,PROFILE_SCORE,TOTAL_POINTS,DATE(PHOTODATE),EDU_LEVEL_NEW,LAST_LOGIN_DT,RELIGION,'Y' FROM newjs.SEARCH_MALE WHERE HAVEPHOTO='Y'";
        $result=mysql_query($sql) or logerror1("In populate_inc.php while inserting data into SEARCH_MALE",$sql,"NO","Y");


        $sql="SELECT MAX(ENTRY_DT) as max FROM  matchalerts.SEARCH_MALE";
        $result=mysql_query($sql) or logerror1("In populate_inc.php --search",$sql,"NO","Y");
        $row=mysql_fetch_array($result);
        $max=$row["max"];
        if($max)
        {
                $max_arr=explode("-",$max);
                $date_temp  = mktime(0, 0, 0,$max_arr[1],$max_arr[2]-1,$max_arr[0]);
                $max_minus1=date("Y-m-d",$date_temp);
                $sql="UPDATE  matchalerts.SEARCH_MALE SET ENTRY_DT='$max_minus1' where ENTRY_DT='$max'";
                mysql_query($sql) or logerror1("In populate_inc.php --search1",$sql,"NO","Y");
        }
	// wait for 5 minutes for completing above command.
	usleep(300000000);

	$sql="SELECT COUNT(*) FROM matchalerts.JPARTNER";
	$result=mysql_query($sql);// or logerror1("In populate_inc.php --search",$sql,"NO","Y");
	$row=mysql_fetch_array($result);
	if($row[0]<600000)
	{
		mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","JPARTNER NOT POPULATED","JPARTNER NOT POPULATED");
	}


	$exitLoop=0;
	$countLoooping=0;
	while($exitLoop==0)
	{
		$fp = fopen("/tmp/shardJpartner.txt", "w+");
		if (flock($fp, LOCK_EX))
		{
        		flock($fp, LOCK_UN); // unlock
			fclose($fp);
			$exitLoop = 1;
		}
		else
		{
			//echo "Hereeee";
			$countLoooping++;	
			usleep(300000000);
			if($countLoooping==5)
				mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","Locking Issue : populate_new_inc_V2.php","Locking Issue : populate_new_inc_V2.php");
		}
	}

	$sql="ALTER TABLE  matchalerts.SEARCH_MALE ENABLE KEYS";
	$result=mysql_query($sql) or logerror1("In populate_inc.php while enabling keys for SEARCH_MALE",$sql,"NO","Y");

	//The code below was initially in mailer_inc.php and was copied here
	$sql="TRUNCATE TABLE  matchalerts.MAILER";
	mysql_query($sql) or logerror1("In mailer_inc.php while truncating MAILER",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.LAGE=JPARTNER.LAGE, SEARCH_FEMALE.HAGE=JPARTNER.HAGE WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.AGE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE set HAGE=100 WHERE HAGE=0";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.LAGE=JPARTNER.LAGE, SEARCH_MALE.HAGE=JPARTNER.HAGE WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.AGE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE set HAGE=100 WHERE HAGE=0";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.CASTE_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.RELIGION='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.CASTE_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.RELIGION='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.MTONGUE_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.MTONGUE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.MTONGUE_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.MTONGUE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.COUNTRY_RES_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.COUNTRY_RES='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.AGE_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.AGE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.RELIGION_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.RELIGION='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.COUNTRY_RES_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.COUNTRY_RES='Y'";
	mysql_query($sql) or logerror1("",$sql);

        $sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.CITY_RES_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.CITY_RES='Y'";
        mysql_query($sql) or logerror1("",$sql);

        $sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.CITY_RES_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.CITY_RES='Y'";
        mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.AGE_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.AGE='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.RELIGION_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.RELIGION='Y'";
	mysql_query($sql) or logerror1("",$sql);


	$sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.MSTATUS_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.MSTATUS='Y'";
	mysql_query($sql) or logerror1("",$sql);

	$sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.MSTATUS_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.MSTATUS='Y'";
	mysql_query($sql) or logerror1("",$sql);

        $sql="update  matchalerts.SEARCH_FEMALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_FEMALE.INCOME_FILTER='Y' WHERE SEARCH_FEMALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_FEMALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.INCOME='Y'";
        mysql_query($sql) or logerror1("",$sql);

        $sql="update  matchalerts.SEARCH_MALE, matchalerts.JPARTNER, matchalerts.FILTERS set SEARCH_MALE.INCOME_FILTER='Y' WHERE SEARCH_MALE.PROFILEID=JPARTNER.PROFILEID AND SEARCH_MALE.PROFILEID=FILTERS.PROFILEID AND FILTERS.INCOME='Y'";
        mysql_query($sql) or logerror1("",$sql);

	$sql="DELETE FROM matchalerts.TRENDS WHERE INITIATED=0 and ACCEPTED=0 and DECLINED=0"; 
	mysql_query($sql) ;

	$sql="CREATE TEMPORARY TABLE matchalerts.BILLING SELECT DATE(MAX(ENTRY_DT)) AS ENTRY_DT,PROFILEID FROM billing.PURCHASES where STATUS='DONE' GROUP BY PROFILEID";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_FEMALE A ,matchalerts.BILLING B SET A.ENTRY_DT=B.ENTRY_DT WHERE A.PROFILEID=B.PROFILEID AND A.ENTRY_DT<B.ENTRY_DT";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_MALE A ,matchalerts.BILLING B SET A.ENTRY_DT=B.ENTRY_DT WHERE A.PROFILEID=B.PROFILEID AND A.ENTRY_DT<B.ENTRY_DT";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_FEMALE A ,newjs.SEARCH_FEMALE_TEXT B SET A.HIV=B.HIV WHERE A.PROFILEID=B.PROFILEID";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_MALE A ,newjs.SEARCH_MALE_TEXT B SET A.HIV=B.HIV WHERE A.PROFILEID=B.PROFILEID";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_MALE SET MSTATUS_MATCHALERT = IF(MSTATUS='N','N','M'),CITY_ZONE = IF(COUNTRY_RES=51,'','F')";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_FEMALE SET MSTATUS_MATCHALERT = IF(MSTATUS='N','N','M'),CITY_ZONE = IF(COUNTRY_RES=51,'','F')";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_MALE S,matchalerts.zone_mapping_table Z SET S.CITY_ZONE = Z.ZONE_CODE WHERE S.COUNTRY_RES = 51 AND SUBSTRING(S.CITY_RES,1,2) = Z.VALUE";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_FEMALE S,matchalerts.zone_mapping_table Z SET S.CITY_ZONE = Z.ZONE_CODE WHERE S.COUNTRY_RES = 51 AND SUBSTRING(S.CITY_RES,1,2) = Z.VALUE";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_MALE S, newjs.MTONGUE M SET S.MTONGUE_ZONE = IF(M.REGION=4,'N',IF(M.REGION=3,'W',IF(M.REGION=2,'S',IF(M.REGION=1,'E','F')))) WHERE M.REGION!=5 AND S.MTONGUE = M.VALUE";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="UPDATE matchalerts.SEARCH_FEMALE S, newjs.MTONGUE M SET S.MTONGUE_ZONE = IF(M.REGION=4,'N',IF(M.REGION=3,'W',IF(M.REGION=2,'S',IF(M.REGION=1,'E','F')))) WHERE M.REGION!=5 AND S.MTONGUE = M.VALUE";
	$result=mysql_query($sql) or logerror1("matchalerts",$sql,"NO","N");

	$sql="DELETE  matchalerts.JPARTNER.* FROM JPARTNER LEFT JOIN  matchalerts.JPROFILE ON JPARTNER.PROFILEID=JPROFILE.PROFILEID WHERE JPROFILE.PROFILEID IS NULL";
    	mysql_query($sql) or logerror1("In populate_inc.php while deleting from JPARTNER",$sql,"NO","Y");

	$lastmonth = date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
	$sql="DELETE  matchalerts.JPARTNER.* FROM  matchalerts.JPROFILE , matchalerts.JPARTNER WHERE INCOMPLETE='Y' AND ENTRY_DT<'$lastmonth' AND JPROFILE.PROFILEID=JPARTNER.PROFILEID";
	$result=mysql_query($sql) or logerror1("In mailer_inc.php at while deleting from JPARTNER",$sql);
}
?>
