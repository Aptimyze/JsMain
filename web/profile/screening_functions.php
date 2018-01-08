<?php
/**********************Code Added by sriram on May 21 2007******************/
//function to check weather new username selected is same as email id.
//returns true if username is same as email id.
function check_username_email($profileid,$newusername)
{
	$sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($res);
	$email_id = explode("@",$row['EMAIL']);

	if(strtolower($email_id[0]) == strtolower($newusername))
		return true;
}

function check_same_username_email($username,$email)
{
	$email_id = explode("@",$email);
	if(strtolower($email_id[0]) == strtolower($username))
		return true;
}

//function to check weather there is any obscene word.
//retruns true, if an obscene word is found
function check_obscene_word($string_to_check)
{
	$string_to_check = remove_special_characters($string_to_check,"alphabets");
	$sql = "SELECT SQL_CACHE WORD FROM newjs.OBSCENE_WORDS";
	$res = mysql_query_decide($sql) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	$string_to_check_array = explode(" ",$string_to_check); 

	while($row = mysql_fetch_array($res))
	{
		// var_dump($row['WORD']);
		if(in_array($row['WORD'],$string_to_check_array))
		{
			return true;
		}
	}
}

//function to check for continuous numbers in a given string.
//returns true if the allowed continuous number's limit is exceeded in the string.
function check_for_continuous_numerics($string,$allowed_cont_num_len="")
{
	$string = remove_special_characters($string,"numbers");
	if(!$allowed_cont_num_len)
		$allowed_cont_num_len = 6;

	$string_length = strlen($string);
	for($i = 0; $i < $string_length; $i++)
	{
		if(is_numeric($string[$i]) && is_numeric($string[$i-1]))
			$count_numeric++;
		else
			$count_numeric = 1;

		if($count_numeric >= $allowed_cont_num_len)
			return true;
	}
}

//function to check intelligent usage of words.
//retruns true if any intelligent usage of word is found.
function check_for_intelligent_usage($string_to_check)
{
	$string_to_check = remove_special_characters($string_to_check);
	$sql = "SELECT WORD FROM jsadmin.MISUSED_WORDS";
	$res = mysql_query_decide($sql) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($row = mysql_fetch_array($res))
	{
		if(strstr($string_to_check,$row['WORD']))
			return true;
	}
}

//function to check limit of words.
//retruns true if any limit above min threshold
function check_for_minimum_character($string_to_check)
{
	$string_length = strlen($string_to_check);
	if($string_length>=100)
	return true;
	else
	return false;
}


//function to remove special characters from a string.
//retiurns string with special characters removed.
function remove_special_characters($string,$return_what="")
{
	$string_removed_special_characters = preg_replace('/[^a-zA-Z0-9\'\s]/','',$string);
	$string_replaced_special_characters = preg_replace('/[^a-zA-Z\'\s]/', ' ', $string);
	$string_replaced_special_characters = preg_replace('/[\.]/', '', $string_replaced_special_characters);

	$string = array_unique(array_merge(explode(" ",$string_removed_special_characters),explode(" ",$string_replaced_special_characters)));
	$string = implode(' ',$string);

	$let_go_dots = 0;
	$string = strtolower($string);

	$special_arr = array(".com",".co.in",".net");
	for($i = 0; $i < count($special_arr); $i++)
	{
		if(strstr($string,$special_arr[$i]))
		{
			$let_go_dots = 1;
			break;
		}
	}
	for($i = 0; $i < strlen($string); $i++)
	{
		if($retrun_what == "alphabets")
		{
			if((ord($string[$i]) >= 97 && ord($string[$i]) <= 122) || ord($string[$i]) == 32)
				$string_actual .= $string[$i];
		}
		elseif($return_what == "numbers")
		{
			if((ord($string[$i]) >= 48 && ord($string[$i]) <= 57))
				$string_actual .= $string[$i];
		}
		else
		{
			if((ord($string[$i]) >= 97 && ord($string[$i]) <= 122) || (ord($string[$i]) >= 48 && ord($string[$i]) <= 57) || ($let_go_dots && ord($string[$i]) == 46) || (ord($string[$i]) == 32))
				$string_actual .= $string[$i];
		}
	}
	return $string_actual;
}
/**********************Code Added by sriram on May 21 2007******************/
/*********************Functions moved by sriram from jsadmin/newuser.php as no screening process from backend****/
function makes_username_changes($profileid,$newusername)
{
	//echo $profileid." = >  ".$newusername."<br>";
	global $db;
	include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	$objUpdate = JProfileUpdateLib::getInstance();
	$result = $objUpdate->editJPROFILE(array("USERNAME"=>$newusername),$profileid,'PROFILEID');
	if(false === $result) {
		$sql="UPDATE newjs.JPROFILE SET USERNAME='$newusername' where PROFILEID='$profileid'";
		logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	//$row=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	$sql="INSERT into newjs.NAMES VALUES('$newusername')";
	$row=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$sql="UPDATE newjs.CONNECT SET USERNAME='$newusername' WHERE PROFILEID='$profileid'";
        $row=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	mysql_select_db_js('userplane',$db);
	$sql="UPDATE userplane.OFFLINE_MESSAGES SET FROM_USERNAME='$newusername' where FROM_PROFILEID='$profileid'";
	$row=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

function thingstodobox($profileid)
{
	global $smarty;
	$sql="select MOD_DT,GENDER,INCOMPLETE,DATE_SUB(left(ENTRY_DT,10), INTERVAL 20 DAY) as ENT_DT,PHONE_MOB,HAVEPHOTO , SOURCE , FAMILY_BACK,FAMILY_VALUES,GOTHRA,FATHER_INFO,SIBLING_INFO,PARENT_CITY_SAME,FAMILYINFO,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,JOB_INFO from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
											 
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		$afl_source = $myrow["SOURCE"];
		$phone_mob = $myrow["PHONE_MOB"];
		if($phone_mob == '')
			$smarty->assign("PHONE_MOB_NULL",'Y');
		else
		{
			$smarty->assign("PHONE_MOB",$phone_mob);
		}
		 if($myrow["FAMILY_BACK"] == '' || $myrow["FAMILY_VALUES"] == '' || $myrow["GOTHRA"] == '' || $myrow["FATHER_INFO"] == '' || $myrow["SIBLING_INFO"] == '' || $myrow["FAMILYINFO"] == '' || $myrow["PARENT_CITY_SAME"] == '')
                                $HAVEFAMILYINFO = 'N';
		else
			$HAVEFAMILYINFO = 'Y';
											 
		if ($myrow["EDU_LEVEL"] == '' || $myrow["EDU_LEVEL_NEW"] == '' || $myrow["EDUCATION"] == '' || $myrow["OCCUPATION"] == '' || $myrow["INCOME"] == '' || $myrow["JOB_INFO"] == '')
			$HAVEEDUINFO = 'N';
		else
			$HAVEEDUINFO = 'Y';
											 
		//Sharding Concept added by Vibhor Garg on table JPARTNER

		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

		$mysqlObj=new Mysql;
                $jpartnerObj=new Jpartner;
                $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
		if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
			$HAVEPARTNER = 'N';
		else
			$HAVEPARTNER = 'Y';
		//Sharding Concept added by Vibhor Garg on table JPARTNER		

		$sql = "SELECT COUNT(*) AS CNT FROM newjs.JHOBBY WHERE PROFILEID = '$profileid'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($res);
		if ($row["CNT"] == 0)
			$HAVEHOBBY = 'N';
		else
											 
			$HAVEHOBBY = 'Y';
		if ($HAVEEDUINFO == 'N' ||  $HAVEFAMILYINFO == 'N' || $myrow["HAVEPHOTO"] == 'N' || $HAVEPARTNER =='N' || $HAVEHOBBY == 'N')
			$SHOWTHINGSTODO = 'Y';
		$smarty->assign("SHOWTHINGSTODO",$SHOWTHINGSTODO);
		$smarty->assign("HAVEPARTNER",$HAVEPARTNER);
		$smarty->assign("HAVEEDUINFO",$HAVEEDUINFO);
		$smarty->assign("HAVEFAMILYINFO",$HAVEFAMILYINFO);
		$smarty->assign("HAVEHOBBY",$HAVEHOBBY);
		$smarty->assign("HAVEPHOTO",$myrow["HAVEPHOTO"]);
		$THINGSTODO=$smarty->fetch('../jsadmin/thingstodo.htm');
	}
	else
		$THINGSTODO="";
	return $THINGSTODO;
}
/*********************End of -- Functions moved by sriram from jsadmin/newuser.php as no screening process from backend****/

if(!function_exists("getAge"))
{
	function getAge($newDob)
	{
		$today=date("Y-m-d");
		$datearray=explode("-",$newDob);
		$todayArray=explode("-",$today);
		
		$years=($todayArray[0]-$datearray[0]);
		
		if(intval($todayArray[1]) < intval($datearray[1]))
			$years--;
		elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
			$years--;
		
		return $years;
	}
}

function gender_related_changes($pid, $previous_gender)
{
	//selecting age and height to fill default values in JPARTNER.
	$sql_jprof = "SELECT AGE,HEIGHT FROM newjs.JPROFILE WHERE PROFILEID = '$pid'";
	$res_jprof = mysql_query_decide($sql_jprof) or die("$sql_jprof".mysql_error_js());
	$row_jprof = mysql_fetch_array($res_jprof);

	$age = $row_jprof['AGE'];
	$height = $row_jprof['HEIGHT'];

	//Sharding Concept added by Vibhor Garg on table JPARTNER

        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;
        $myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
	$jpartnerObj->setPROFILEID($pid);
        $jpartnerObj->deletePartnerProfile($myDb,$mysqlObj);
 	
	//Sharding Concept added by Vibhor Garg on table JPARTNER         	



	// get partner id of the person to delete the entries from the corresponding PARTNER_ tables
	/*$partner_id_sql         = "SELECT PARTNERID FROM newjs.JPARTNER WHERE PROFILEID='$pid'";
	$partner_id_res         = mysql_query_decide($partner_id_sql);
	$partner_id_row         = mysql_fetch_array($partner_id_res);
	$partner_id             = $partner_id_row['PARTNERID'];

	// delete the corresponding entries from PARTNER_ tables
	for($i=0;$i<count($partner_tbl_arr);$i++)
	{
		$del_partner_sql = "DELETE FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
		mysql_query_decide($del_partner_sql) or die(mysql_error_js());
	}

	// update SEARCH_MALE / FEMALE tables in sync with the change of gender
	$sql="DELETE FROM  newjs.JPARTNER WHERE PARTNERID='$partner_id'";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
	*/
	if($previous_gender=='M')
	{
		$sql="DELETE from newjs.SEARCH_MALE where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$sql="DELETE from newjs.SEARCH_MALE_REV where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$sql="DELETE from newjs.SEARCH_MALE_TEXT where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());

		//if previous_gender was M then current gender must be F.
		$hage = $age+7;
		if($age < 21)
			$lage = 21;
		else
			$lage = $age;
		if($hage > 70)
			$hage = 70;

		$lheight = $height;
		if($height <= 20)
			$hheight = $height + 10;
		else
			$hheight=30;
		if($height > $hheight)
			$hheight = 32;

		//Sharding Concept added by Vibhor Garg on table JPARTNER

        	$jpartnerObj->setGENDER('M');
		$jpartnerObj->setLAGE($lage);
		$jpartnerObj->setHAGE($hage);
		$jpartnerObj->setLHEIGHT($lheight);
		$jpartnerObj->setHHEIGHT($hheight);
		$tdate=date("Y-m-d");
		$jpartnerObj->setDATE($tdate);
		$jpartnerObj->setDPP('R');
		$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

        	//Sharding Concept added by Vibhor Garg on table JPARTNER   		

		//$sql_jpartner = "INSERT into newjs.JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE,DPP) values ('$pid','M','$lage','$hage','$lheight','$hheight',now(),'R')";
		//mysql_query_decide($sql_jpartner) or die("$sql_jpartner".mysql_error_js());
	}
	if($previous_gender=='F')
	{
		$sql="DELETE from newjs.SEARCH_FEMALE where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$sql="DELETE from newjs.SEARCH_FEMALE_REV where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$sql="DELETE from newjs.SEARCH_FEMALE_TEXT where PROFILEID='$pid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());

		//if previous_gender was F then current gender must be M.
		if($age < 25)
			$lage = 18;
		else
			$lage = $age - 7;
		$hage=$age;

		$hheight = $height;
		if($height > 10)
			$lheight = $height - 10;
		else
			$lheight = 1;

		//Sharding Concept added by Vibhor Garg on table JPARTNER

                $jpartnerObj->setGENDER('F');
                $jpartnerObj->setLAGE($lage);
                $jpartnerObj->setHAGE($hage);
                $jpartnerObj->setLHEIGHT($lheight);
                $jpartnerObj->setHHEIGHT($hheight);
                $tdate=date("Y-m-d");
                $jpartnerObj->setDATE($tdate);
                $jpartnerObj->setDPP('R');
                $jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

                //Sharding Concept added by Vibhor Garg on table JPARTNER    

		//$sql_jpartner="INSERT into newjs.JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE,DPP) values ('$pid','F','$lage','$hage','$lheight','$hheight',now(),'R')";
		//mysql_query_decide($sql_jpartner) or die("$sql_jpartner".mysql_error_js());
	}

	delete_record($pid);

	//Setting the value in memcache, that will be checked in authentication function while user is online.
	set_memcache_value($pid);
}

function delete_record($pid)
{
	$path=JsConstants::$docRoot;
	include_once("$path/classes/globalVariables.Class.php");
	include_once("$path/classes/Mysql.class.php");
	include_once("$path/classes/Memcache.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	
	
	global $mysqlObj;
	global $noOfActiveServers;
	$mysqlObj=new Mysql;
        $myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);	
	for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
	{
	        $myDbName=getActiveServerName($activeServerId);
        	$myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
		$mysqlObj->executeQuery("set session wait_timeout=1000",$myDbarr[$myDbName]);

	}
	
	$messageShardCount=0;
	$dbMessageLogObj1=new NEWJS_MESSAGE_LOG("shard1_master");
	$dbMessageLogObj2=new NEWJS_MESSAGE_LOG("shard2_master");
	$dbMessageLogObj3=new NEWJS_MESSAGE_LOG("shard3_master");
	$dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG("shard1_master");
	$dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG("shard2_master");
	$dbDeletedMessageLogObj3=new NEWJS_DELETED_MESSAGE_LOG("shard3_master");
//	$myDb=$mysqlObj->connect("$myDbName");
	
	//Sharding of CONTACTS done by Sadaf

	$sendersIn=$pid;
	$contactResult=getResultSet("CONTACTID,TYPE,RECEIVER",$sendersIn);
	if(is_array($contactResult))
	{	
		foreach($contactResult as $key=>$value)
		{
			$contact_id=$contactResult[$key]["CONTACTID"];
			$sender_profileid=$pid;
			$receiver_profileid=$contactResult[$key]["RECEIVER"];

			deleteFromContacts($contact_id,$sender_profileid,$receiver_profileid);

			//added by sriram,lavesh for updation of fields in leftpanel
			if($contactResult[$key]["TYPE"]!="C")
			{
				if($contactResult[$key]['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$receiver_profileid'";
				elseif($contactResult[$key]['TYPE']=='A')
					 $sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$receiver_profileid'";
				elseif($contactResult[$key]['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$receiver_profileid'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
		}
	}
	unset($contactResult);

	//modified by sriram for updation in leftpanel
	$receiversIn=$pid;
	$contactResult=getResultSet("CONTACTID,TYPE,SENDER",'','',$receiversIn);
	if(is_array($contactResult))
        {
		foreach($contactResult as $key=>$value)
		{
			$contact_id=$contactResult[$key]["CONTACTID"];
			$sender_profileid=$contactResult[$key]["SENDER"];
			$receiver_profileid=$pid;
			
			deleteFromContacts($contact_id,$sender_profileid,$receiver_profileid);

			if($contactResult[$key]["TYPE"]!="C")
			{
				if($contactResult[$key]["TYPE"]=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$sender_profileid'";
				elseif($contactResult[$key]["TYPE"]=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$sender_profileid'";
				elseif($contactResult[$key]["TYPE"]=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
		}
	}
	//added by sriram
	$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$pid'";
	mysql_query_decide($sql_del);

	$sql_del = "DELETE FROM userplane.CHAT_REQUESTS WHERE SENDER='$pid' OR RECEIVER='$pid'";
        mysql_query_decide($sql_del);

	//$sql_del = "DELETE FROM userplane.DELETED_CHAT_REQUESTS WHERE SENDER='$pid' OR RECEIVER='$pid'";
        //mysql_query_decide($sql_del);

	if(count($myDbarr))
        foreach($myDbarr as $key=>$val)
	{
		$messageShardCount++;
		$mysqlObj->ping($myDbarr[$key]);
		$myDb=$myDbarr[$key];
		if($messageShardCount==1)
		{
			$dbMessageLogObj=$dbMessageLogObj1;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj1;
		}
		if($messageShardCount==2)
		{
			$dbMessageLogObj=$dbMessageLogObj2;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj2;
		}
		if($messageShardCount==3)
		{
			$dbMessageLogObj=$dbMessageLogObj3;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj3;
		}

		$sql1="DELETE FROM newjs.PHOTO_REQUEST WHERE PROFILEID='$pid' OR PROFILEID_REQ_BY='$pid'";
                $mysqlObj->executeQuery($sql1,$myDb);

		$sql1="DELETE FROM newjs.DELETED_PHOTO_REQUEST WHERE PROFILEID='$pid' OR PROFILEID_REQ_BY='$pid'";
                $mysqlObj->executeQuery($sql1,$myDb);

		$sql1="DELETE FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID='$pid' OR PROFILEID_REQUEST_BY='$pid'";
                $mysqlObj->executeQuery($sql1,$myDb);

                $sql1="DELETE FROM newjs.DELETED_HOROSCOPE_REQUEST WHERE PROFILEID='$pid' OR PROFILEID_REQUEST_BY='$pid'";
                $mysqlObj->executeQuery($sql1,$myDb);

		$sql1="DELETE FROM newjs.DELETED_PROFILE_CONTACTS WHERE SENDER='$pid' OR RECEIVER='$pid'";
		$mysqlObj->executeQuery($sql1,$myDb);

		//Deleting records from all shrading servers
		$mysqlObj->ping($myDbarr[$key]);
		$myDb=$myDbarr[$key];	
		//Deleting contacts from newjs.MESSAGE_LOG
		
		$result=$dbMessageLogObj->getAllMessageIdLog($pid,'SENDER');
		//$sql  = "SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER='$pid'";
        	//$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
	        //while($row=$mysqlObj->fetchArray($res))
	        if(is_array($result)){
				foreach($result as $key=>$row)
				{
						$dbMessageLogObj->deleteMessageLogById($row);
						//$sql1="DELETE FROM newjs.MESSAGE_LOG WHERE ID='$id'";
						//$mysqlObj->executeQuery($sql1,$myDb) or die(mysql_error_js($myDb));
				}
			}
		$res=$dbMessageLogObj->getAllMessageIdLog($pid,'RECEIVER');
	       // $sql  = "SELECT ID FROM newjs.MESSAGE_LOG WHERE RECEIVER='$pid'";
        	//$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
	        //while($row=$mysqlObj->fetchArray($res))
	        if(is_array($res)){
				foreach($res as $key=>$row)
				{
						$dbMessageLogObj->deleteMessageLogById($row);
						//$sql1="DELETE FROM newjs.MESSAGE_LOG WHERE ID='$id'";
						//$mysqlObj->executeQuery($sql1,$myDb) or die(mysql_error_js($myDb));
				}
			}
	
		//Deleting contacts from newjs.DELETED_MESSAGE_LOG
		$resp=$dbDeletedMessageLogObj->getAllMessageIdLog($pid,'SENDER');
		//$sql  = "SELECT ID FROM newjs.DELETED_MESSAGE_LOG WHERE SENDER='$pid'";
	      //  $res=$mysqlObj->executeQuery($sql,$myDb) or die("$sql".mysql_error_js());
        	//while($row=$mysqlObj->fetchArray($res))
        	if(is_array($resp)){
				foreach($resp as $key=>$row)
				{
						$dbDeletedMessageLogObj->deleteMessageLogById($row);
						//$sql1="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID='$id'";
						//$mysqlObj->executeQuery($sql1,$myDb)  or die("$sql1".mysql_error_js());
				}
			}
			$response=$dbDeletedMessageLogObj->getAllMessageIdLog($pid,'RECEIVER');
		//$sql  = "SELECT ID FROM newjs.DELETED_MESSAGE_LOG WHERE RECEIVER='$pid'";
        	//$res=$mysqlObj->executeQuery($sql,$myDb)  or die("$sql".mysql_error_js());
	        //while($row=$mysqlObj->fetchArray($res))
	        if(is_array($response)){
				foreach($response as $key=>$row)
				{
						$dbDeletedMessageLogObj->deleteMessageLogById($row);
						//$sql1="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID='$id'";
						//$mysqlObj->executeQuery($sql1,$myDb)  or die("$sql1".mysql_error_js());
				}
			}
	}
}?>
