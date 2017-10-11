<?
	/*This script is called in both cases, in multiple contact and single contact , since parameter pass will be different in different cases
	For Mulitple contact case
		SENDERS_DATA will contact profileidschecksum seprated by comman
		TYPE_OF will be M
	For Single contact case
		SENDERS_DATA will contain only one profilechecksum
		TYPE_OF will be S
	*/

	//to zip the file before sending it
	include_once("search.inc");

        include_once("connect.inc");
        include_once("arrays.php");
        include_once("hin_arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        include_once("hits.php");
        include("manglik.php");
        include_once('functions.inc');
        include_once('ntimes_function.php');
	include_once("contact.inc");
	include_once("sphinx_search_function.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
        $jpartnerObj=new Jpartner;
        $mysqlObj=new Mysql;
	
        // connect to database
	$db_slave=connect_slave();	
        $db=connect_db();
	$data=authenticated($checksum);
	$profileid_conn=$data['PROFILEID'];
	if($profileid_conn)
	{
		$myDbName=getProfileDatabaseConnectionName($profileid_conn,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
		
		
	}
	if($type=='Contact_History')
	{
		if(!$data)
		{
		 	$message="You need to be logged in to view your contact history with $_GET[view_username]. <a href='".$SITE_URL."/profile/login.php' class='thickbox' onclick='javascript:show_login_layer(\"".$SITE_URL."/profile/login.php?SHOW_LOGIN_WINDOW=1\");return false'>Click here to login</a>.";
			$smarty->assign("MESSAGE",$message);
		}
		elseif($ERROR_MES)
		{
			//$message="There has been no contact between you and $_GET[view_username].<BR><BR> $ERROR_MES";
			$message=stripslashes($ERROR_MES);
			$smarty->assign("MESSAGE",$message);
		}
		else
		{
			if($data['GENDER']=="M")
				$other_gen='F';
			else
				$other_gen='M';
			$message=all_message_log($data["PROFILEID"],$other_profile,$contact_status,$other_gen);
			if($message)
				$smarty->assign("CON_HISTORY",$message);
			else
				$smarty->assign("MESSAGE","There has been no contact between you and $_GET[view_username]. If you like profile, <a class='cp' onclick=\"javascript:tab_express_interest()\">Express Interest</a> to get started.");
			
		}
		$file="contact_history.htm";
	}	
	if($type=='Horoscope')
	{
		
		$file="horoscope.htm";
	}
	if($type=="Compatibility")
	{
		if($data)
		{
                        login_relogin_auth($data);
                        $profileid=$data['PROFILEID'];
                        $chkprofilechecksum=explode("i",$profilechecksum);
                        //end
                        $profileid_other=$chkprofilechecksum[1]; //profileid of other person with whom logged in person is checking compatibility
                        if(strstr($data['SUBSCRIPTION'],'A'))
                                $sample="";

                        // Compatibility Check Added                    
                        include("score_compatibility.php");
                        $compatibleStatus = checkCompatibilityStatus($profileid_other);
			$showCompatibility = true;
                        if($compatibleStatus)
                        {
                                include_once('contacts_functions.php');
                                include_once('thumb_identification_array.inc');
                                $scoreArr = getScore($profileid, $profileid_other);
				$score    = $scoreArr["SCORE"];
				$dobArr   = $scoreArr["DOB"];
				$genderArr= $scoreArr["G"];
                                if($score!='')
                                        $smarty->assign("SCORE",$score);
                                else
					$showCompatibility = false;
                        }
                        else{
				$showCompatibility = false;
				$dobArr="";
				$genderArr="";
			}
			$smarty->assign("showCompatibility",$showCompatibility);

			// Start Sunshine-Compatibility Check 
			include_once("sunsign_compatibility.php");	
			$sunshineData	= getSunshineData($profileid, $profileid_other,$dobArr, $genderArr);
			$smarty->assign("user_sunshine",$sunshineData['USER_SUNSHINE']);
			$smarty->assign("viewed_user_sunshine",$sunshineData['VIEWED_USER_SUNSHINE']);
			$smarty->assign("viewed_user_sunshine_desc",$sunshineData['VIEWED_USER_SHUNSHINE_DESC']);
			$smarty->assign("sunshine_compat",$sunshineData['COMPAT']);	
			// End Sunshine-Compatibility Check
		}
		else
		{
			$message="You need to logged in to view you compatibility with $_GET[view_username]. <a href='".$SITE_URL."/profile/login.php' class='thickbox' onclick='javascript:show_login_layer(\"".$SITE_URL."/profile/login.php?SHOW_LOGIN_WINDOW=1\");return false'>Click here to login</a>.";
		}
	$smarty->assign("MESSAGE",$message);	
	$file="compat.htm";
}		
//$file="compat.htm";
if($type=="Similar_Profile")
{
	//navigation("CVS","",$_GET['SIM_USERNAME']);
	$from_viewprofile_v=1;
	$contact=getProfileidFromChecksum($other_profile);
	//revamp_get_other_relevant_pro($data['PROFILEID'],$other_profile,"viewprofile.php","viewprofile.php");
	include("simprofile_search.php");
	$file="sim_prof.htm";
}
if($file)
	$smarty->display("contact/$file");

function astro_details($profileid,$profileid_other)
        {
                global $smarty;
                $sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row = mysql_fetch_array($result);
                //send m_UserName:m_Moon_Degrees_Full:m_Mars_Degrees_Full:m_Venus_Degrees_Full:m_Lagna_Degrees_Full:f_Moon_Degrees_Full:f_Mars_Degrees_Full:f_Venus_Degrees_Full:f_Lagna_Degrees_Full:f_UserName to htm 
                $astrodata['MOON_DEGREES_FULL'] = $row['MOON_DEGREES_FULL'];
                $astrodata['MARS_DEGREES_FULL'] = $row['MARS_DEGREES_FULL'];
                $astrodata['VENUS_DEGREES_FULL'] = $row['VENUS_DEGREES_FULL'];
                $astrodata['LAGNA_DEGREES_FULL'] = $row['LAGNA_DEGREES_FULL'];
                $smarty->assign("astrodata",$astrodata);

                unset($astrodata);

                $sql_other = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid_other'";
                $result_other=mysql_query_decide($sql_other) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_other,"ShowErrTemplate");
                $row_other = mysql_fetch_array($result_other);
                //send m_UserName:m_Moon_Degrees_Full:m_Mars_Degrees_Full:m_Venus_Degrees_Full:m_Lagna_Degrees_Full:f_Moon_Degrees_Full:f_Mars_Degrees_Full:f_Venus_Degrees_Full:f_Lagna_Degrees_Full:f_UserName to htm 
                $astrodata_other['MOON_DEGREES_FULL'] = $row_other['MOON_DEGREES_FULL'];
                $astrodata_other['MARS_DEGREES_FULL'] = $row_other['MARS_DEGREES_FULL'];
                $astrodata_other['VENUS_DEGREES_FULL'] = $row_other['VENUS_DEGREES_FULL'];
                $astrodata_other['LAGNA_DEGREES_FULL'] = $row_other['LAGNA_DEGREES_FULL'];
                $smarty->assign("astrodata_other",$astrodata_other);
                unset($astrodata_other);
        }

        function horoscope_compatibility_log($profileid,$profileid_other)
        {
                $sql="REPLACE into HOROSCOPE_COMPATIBILITY(PROFILEID,PROFILEID_OTHER,DATE) values ('$profileid','$profileid_other',now()) ";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        }

function all_message_log($logged_pid,$profileid,$contact_status="",$other_gender)

{

global $db,$smarty,$myDb,$mysqlObj,$CALL_NOW;

$flag=0;
$other_who="She";
$other_more="her";
if($other_gender=="M")
{
	$other_who="He";
	$other_more="him";
}

if($_GET['NUDGE_STATUS']=="")
{
	$sql = "select ID,SENDER,TYPE,`DATE`,OBSCENE from MESSAGE_LOG where RECEIVER='$logged_pid' and SENDER='$profileid'  UNION select ID,SENDER,TYPE,`DATE`,OBSCENE from MESSAGE_LOG where SENDER='$logged_pid' and RECEIVER='$profileid'  ORDER BY `DATE` ASC ";

	$result=$mysqlObj->executeQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$start=0;
	while($myrow=$mysqlObj->fetchArray($result))

	{
		$ids=$myrow["ID"];
		if($myrow[OBSCENE]=='N')
			$id_array[]=$ids;
		if(!$prev_status)
		{
			$left_me="expressed interest";
			
		}
		elseif($myrow['TYPE']==$prev_status)
		{
			if($myrow['TYPE']=='I')
				$left_me="sent a reminder";

			elseif($prev_status=='A' || $prev_status=='D' || $prev_status=='R')
				$left_me="wrote message";
			
		}
		elseif($myrow['TYPE']!=$prev_status)
		{
			
			if($myrow['TYPE']=='A' || $myrow['TYPE']=='R')
				$left_me="accepted interest";
			if($myrow['TYPE']=='R')
				$left_me="wrote message";
			if($myrow['TYPE']=='C')
				$left_me="cancelled interest";
			if($myrow['TYPE']=='D')
				$left_me="declined interest";
			if($myrow["TYPE"]=="E")
				$left_me="cancelled interest";
			if($myrow["TYPE"]=="I")
				$left_me="expressed interest";
				

		}
	
		if($myrow["SENDER"]==$logged_pid)
		{
			$who="You";
		}	
		else
			$who=$other_who;
	
		$prev_status=$myrow['TYPE'];
		$message_log[$ids][0]=$who;
		$message_log[$ids][1]=$left_me;
		$message_log[$ids][2]=$myrow['DATE'];
	
	}



	if(is_array($id_array))

	{
		$ids_str=implode(',',$id_array);

		$k=1;

		$sql="select MESSAGE,ID from MESSAGES WHERE ID in ($ids_str) ORDER BY ID DESC";

		$result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		while($myrow=$mysqlObj->fetchArray($result))

		{



				//make_msg_read($myrow["ID"],$contact_status,$profileid);

				$flag=1;

				$message=stripslashes(nl2br($myrow['MESSAGE']));


			$all_message[$k]=$message;
			$message_log[$myrow["ID"]][3]=$all_message[$k];
			$k++;

		}

	}
}
	$tempStatus=getIncompleteUnscreenedStatus($logged_pid);
	$sender_details["INCOMPLETE"] = $tempStatus["INCOMPLETE"];
	$sender_details["ACTIVATED"] = $tempStatus["ACTIVATED"];
	$tempParam = temporaryInterestSuccess($sender_details["INCOMPLETE"], $sender_details["ACTIVATED"]);
	if($tempParam)
	{
	 	$sql="select CONTACTID,SENDER,RECEIVER,TIME as `DATE` from newjs.CONTACTS_TEMP where SENDER=$logged_pid and RECEIVER='$profileid'  AND DELIVERED='N' ";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($result))
		{
			$ids=$row['CONTACTID'];
			$message_log[$ids][0]="";
			$profilechecksum=createchecksumforsearch($logged_pid);
			if($tempParam=='incomplete')
				$message_log[$ids][1]="You had expressed interest in this profile and the same will be delivered once your profile is complete. Please <a href='/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile'>click here</a> to complete your profile";
			else
				$message_log[$ids][1]="You had expressed interest in this profile and the same will be delivered once your profile goes live";
	
		
			$message_log[$ids][2]=$row['DATE'];
			$message_log[$ids][3]="";
				
		}
	}
if(!$message_log)
	return;
	$con_his=reorder($message_log);
		
	$sql="select BKNOTE,BKDATE as `DATE` from newjs.BOOKMARKS where BOOKMARKER=$logged_pid and BOOKMARKEE='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($myrow=mysql_fetch_array($result))
	{
		$con_his[$myrow['DATE']]="FAV";
		$fav_mes=$myrow['BKNOTE'];
	}
	
	$sql="select `DATE` from newjs.EOI_VIEWED_LOG where VIEWER='$profileid' and VIEWED='$logged_pid'";
	$result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($myrow=$mysqlObj->fetchArray($result))
	{
		$con_his[$myrow['DATE']]="E".$other_who;
	}


	 $sql="select `DATE`,PROFILEID_REQUEST_BY,PROFILEID from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$logged_pid' and PROFILEID='$profileid' UNION select `DATE`,PROFILEID_REQUEST_BY,PROFILEID from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$profileid' and PROFILEID='$logged_pid' order by `DATE` ASC";

         $result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=$mysqlObj->fetchArray($result))
	{
		if($myrow['PROFILEID_REQUEST_BY']==$logged_pid)
			$con_his[$myrow['DATE']]="H".$other_who;
		else
			$con_his[$myrow['DATE']]="HYou";
	}

	$sql="select `DATE`,PROFILEID_REQ_BY,PROFILEID from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$logged_pid' and PROFILEID='$profileid' UNION select `DATE`,PROFILEID_REQ_BY,PROFILEID from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$profileid' and PROFILEID='$logged_pid' order by `DATE` ASC";

         $result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow=$mysqlObj->fetchArray($result))
        {
                if($myrow['PROFILEID_REQ_BY']==$logged_pid)
                        $con_his[$myrow['DATE']]="P".$other_who;
                else
                        $con_his[$myrow['DATE']]="PYou";

        }
	$sql="select SENDER,RECEIVER,TIMEOFINSERTION as `DATE` from userplane.CHAT_REQUESTS where SENDER='$logged_pid' and RECEIVER='$profileid' union select SENDER,RECEIVER,TIMEOFINSERTION as `DATE` from userplane.CHAT_REQUESTS where SENDER='$profileid' and RECEIVER='$logged_pid' order by `DATE` ASC";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow['RECEIVER']==$logged_pid)
                        $con_his[$myrow['DATE']]="C".$other_who;
                else
                        $con_his[$myrow['DATE']]="CYou";
	}

	/* IVR Start
	 * Callnow records fetched from CALLNOW table
	 * Call types: Call received, call missed, call made  
	*/
	if($CALL_NOW && false){
        	$sql ="SELECT CALL_STATUS,RECEIVER_PID,CALLER_PID,CALL_DT as `DATE` FROM newjs.CALLNOW where RECEIVER_PID='$logged_pid' AND CALLER_PID='$profileid' union SELECT CALL_STATUS,RECEIVER_PID,CALLER_PID,CALL_DT as `DATE` FROM newjs.CALLNOW where RECEIVER_PID='$profileid' AND CALLER_PID='$logged_pid'";
        	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        	while($myrow=mysql_fetch_array($result))
        	{
			$status =$myrow['CALL_STATUS'];
			if($myrow['RECEIVER_PID']==$logged_pid)
			{	
				if($status=='R')
					$con_his[$myrow['DATE']]="I".$other_who." called you" ;
				elseif($status=='M')
					$con_his[$myrow['DATE']]="IYou missed a call from ".$other_more ;
			}
			else
				$con_his[$myrow['DATE']]="IYou called ".$other_more;
        	}
	}
	// IVR Ends

	if(is_array($con_his))
		krsort($con_his);
	//print_r($con_his);
	$start=0;
	if(is_array($con_his))
		foreach($con_his as $key=>$val)
		{
			$date_time=explode(" ",$key);
			$date=explode("-",$date_time[0]);
			$time=explode(":",$date_time[1]);
			$format_time=mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
			$time=date("D",$format_time).", ".date("d",$format_time)." ".date("F",$format_time).", ".date("y",$format_time);
			$CON_HISTORY[$start][1]=$time;
			if(is_int($val))
			{
				$CON_HISTORY[$start][0]=$message_log[$val][0]." ".$message_log[$val][1];
				$CON_HISTORY[$start][2]=$message_log[$val][3];
			}
			if(substr($val,0,1)=='E')
			{
				$CON_HISTORY[$start][0]=substr($val,1,strlen($val)-1)." viewed your Expression of interest";
			}
			if(substr($val,0,1)=='P')
			{
				$CON_HISTORY[$start][0]=substr($val,1,strlen($val)-1)." requested for photo";
			}
			if(substr($val,0,1)=='H')
			{
				$CON_HISTORY[$start][0]=substr($val,1,strlen($val)-1)." requested for horoscope";
			}
			if(substr($val,0,1)=='C')
			{
				$CON_HISTORY[$start][0]=substr($val,1,strlen($val)-1)." requested for chat";
			}
                        if(substr($val,0,1)=='I')
                        {
                                $CON_HISTORY[$start][0]=substr($val,1,strlen($val)-1);
                        }
			if(substr($val,0,1)=='F')
			{
				$CON_HISTORY[$start][0]="You added $other_more to shortlist";
				$CON_HISTORY[$start][2]=$fav_mes;
				
			}
			$start++;
		}
	else
	{
		return false;
	}
	return $CON_HISTORY;	
}
function reorder($message_log)
{
	if(is_array($message_log))
		foreach($message_log as $key=>$val)
	{
		$time[$val[2]]=$key;
	}
	return $time;
}
function make_msg_read($msgid='',$contact_status='',$profileid='')

{

	global $smarty,$data,$myDb,$mysqlObj;



	if($msgid)

	{	

		//Decrement the counter by 1 when user views his unread message

		$sql_check_status="select FOLDERID,SENDER from newjs.MESSAGE_LOG where ID='$msgid' and RECEIVER_STATUS='U' and RECEIVER='".$data['PROFILEID']."'";

		$res_check_status=$mysqlObj->executeQuery($sql_check_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_check_status,"ShowErrTemplate");



		if($row_check_status=$mysqlObj->fetchArray($res_check_status))

		{

			$sender=$row_check_status['SENDER'];



			//Getting connection name on both sender and receiver side

			$myDbName1=getProfileDatabaseConnectionName($sender,'',$mysqlObj);

			$myDbName2=getProfileDatabaseConnectionName($data['PROFILEID'],'',$mysqlObj);

			if($myDbName1!=$myDbName2)

				$myDb1=$mysqlObj->connect("$myDbName1");



			if($row_check_status['FOLDERID']==0)

			{


				$CONTACT_STATUS_FIELD['NEW_MES']=-1;
				updatememcache($CONTACT_STATUS_FIELD,$data['PROFILEID']);

				//$sql_contact="Update CONTACTS_STATUS set NEW_MES=NEW_MES-1 where PROFILEID='".$data['PROFILEID']."'";

				

				//mysql_query_optimizer($sql_contact) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_contact,"ShowErrTemplate");

			}

		



		$sql_rec_status="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE ID='$msgid'";

		$mysqlObj->executeQuery($sql_rec_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec_status,"ShowErrTemplate");

		

		//if sender is in other shard.

		if($myDb1)

			$mysqlObj->executeQuery($sql_rec_status,$myDb1)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec_status,"ShowErrTemplate");



		}

	}	

	if($contact_status && $profileid)

	{

		$myDbName1=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);

		$myDbName2=getProfileDatabaseConnectionName($data['PROFILEID'],'',$mysqlObj);

		if($myDbName1!=$myDbName2)

			$myDb1=$mysqlObj->connect("$myDbName1");



		if($contact_status=='I' || $contact_status=='C')

			$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='$contact_status' AND IS_MSG='N'";

		elseif($contact_status=='RA')

			$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='A' AND IS_MSG='N'";

		elseif($contact_status=='RD')

			$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='D' AND IS_MSG='N'";

	}

	if($sql)

	{

		$mysqlObj->executeQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		//if sender is in other shard

		if($myDb1)

			$mysqlObj->executeQuery($sql,$myDb1)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	}



}
