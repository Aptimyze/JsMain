<?php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

if(authenticated($cid))
{
	$path_class=$_SERVER['DOCUMENT_ROOT'];
	if(!$path_class)
	$path_class=JsConstants::$docRoot;
	include_once("$path_class/classes/globalVariables.Class.php");
	include_once("$path_class/classes/Mysql.class.php");
	include_once("$path_class/classes/Memcache.class.php");

	$mysql=new Mysql;
	

	$sql = "SELECT PROFILEID, IPADD, USERNAME, EMAIL, ENTRY_DT,PHONE_RES, PHONE_MOB, CONTACT, ACTIVATED,CASTE FROM newjs.JPROFILE where ";

	if(strpos($username,'@'))
		$sql .= "EMAIL = '$username'";
	else
		$sql .= "USERNAME = '$username'";

	$res = mysql_query_decide($sql) or die("Unable to select from JPROFILE".mysql_error_js());

	// New code added for old email check
	if(mysql_num_rows($res)==0)
	{	
		$sql_email ="SELECT PROFILEID FROM newjs.OLDEMAIL where OLD_EMAIL='$username'";
                $result_email=mysql_query_decide($sql_email) or die("$sql_email".mysql_error_js());
                $myrow_email=mysql_fetch_array($result_email);
                $pid =$myrow_email['PROFILEID'];
                if($pid){
                	$sql ="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED ,SOURCE, VERIFY_EMAIL,CASTE from newjs.JPROFILE where PROFILEID='$pid'";
                        $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                } else {
                    $msg = "Search for : " . $username. "\n\n";
                    $msg = print_r($_SERVER,true);
                    mail("kunal.test02@gmail.com"," web/jsadmin/searchpage_userdetails in USE",$msg);
                    die("Username not found in database");
                }
	}	
	// Ends
	
	$row = mysql_fetch_array($res);

	$username=$row['USERNAME'];
	$email = $row['EMAIL'];
	$ipadd = $row['IPADD'];
	$entry_dt = $row['ENTRY_DT'];
	$phone_res = $row['PHONE_RES'];
	$phone_mob = $row['PHONE_MOB'];
	$contact = $row['CONTACT'];
	$profileid = $row['PROFILEID'];
	$activ = $row['ACTIVATED'];
	$myCaste = $row['CASTE'];
  
	//Getting connection on sharded server.
	//$myDbname=getProfileDatabaseConnectionName($profileid,'slave',$mysql);
	//$myDb=$mysql->connect($myDbname);
	$profileShard=JsDbSharding::getShardNo($profileid,'slave');
	$sql="SELECT OLD_EMAIL FROM newjs.OLDEMAIL WHERE PROFILEID='$profileid'";
	$res_email=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row_email=mysql_fetch_array($res_email))
	{
		$email.=", ".$row_email['OLD_EMAIL'];
	}
	
	if(strlen(trim($phone_res))==0 && strlen(trim($phone_mob))==0)
	{
		$smarty->assign("nophone","1");
	}
	if(strlen(trim($phone_res))==0 || strlen(trim($phone_mob))==0)
	{
		$smarty->assign("nophone","2");
	}

	$sql_pay_det = "SELECT SERVICEID,CONVERT_TZ(ENTRY_DT,'SYSTEM','right/Asia/Calcutta') as ENTRY_DT ,IPADD FROM billing.PURCHASES WHERE PROFILEID = '$profileid'";

	$res_pay_det = mysql_query_decide($sql_pay_det) or die("Unable to select from PURCHASES".mysql_error_js());
	
	if(mysql_num_rows($res_pay_det)==0)
	{
		$smarty->assign("nopaydet","1");
	}
	else
	{
		$j=0;
		while($row_pay_det = mysql_fetch_array($res_pay_det))
		{
			$sid = $row_pay_det['SERVICEID'];
			$sql_ser = "SELECT NAME FROM billing.SERVICES where SERVICEID = '$sid'";
			$res_ser = mysql_query_decide($sql_ser) or die("Unable to select from SERVICES".mysql_error_js());
			$row_ser = mysql_fetch_array($res_ser);
			$pdet[$j]['SERVICEID'] = $row_ser['NAME'];
			$pdet[$j]['ENTRY_DT'] = $row_pay_det['ENTRY_DT'];
			$pdet[$j]['IPADD'] = $row_pay_det['IPADD'];
			$j++;
		}
	}
	
	if($activ=="D")	{
    $sql_ser = "SELECT HOUSKEEPING_DONE FROM newjs.NEW_DELETED_PROFILE_LOG where PROFILEID = '$pid' ORDER BY DATE DESC LIMIT 1 ";
    $res_ser = mysql_query_decide($sql_ser) or die("Unable to select from SERVICES".mysql_error_js());
    $myrow=mysql_fetch_array($res_ser);
    $houseKeepingDone =$myrow['HOUSKEEPING_DONE'];
    
    if($houseKeepingDone == 'Y') {
      $dbMessageLog=new NEWJS_DELETED_MESSAGE_LOG($profileShard);
    } else {
      $dbMessageLog=new NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET($profileShard);
    }
		
		//$fromtable = "DELETED_MESSAGE_LOG";
	}
	else{
		$dbMessageLog=new NEWJS_MESSAGE_LOG($profileShard);
		//$fromtable = "MESSAGE_LOG";
	}
		
		$result=$dbMessageLog->getMessagesDataSearchPageDetails($profileid,'SENDER');
	//$sql_conts_i = "SELECT COUNT(*) as cnt,IP FROM newjs.$fromtable where SENDER = '$profileid' GROUP BY IP ";
	//$sql_conts_i = "SELECT CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,INET_NTOA(IP) AS IP,RECEIVER FROM newjs.$fromtable where SENDER = '$profileid' ORDER BY DATE DESC limit 20 ";

	//$result_conts_i =$mysql->ExecuteQuery($sql_conts_i,$myDb) or die("Unable to select from $fromtable".mysql_error_js());
	
	$i=0;
	//while($row_conts_i = $mysql->fetchArray($result_conts_i))
	foreach($result as $key=>$row_conts_i)
	{
		//$cont_made[$i]['COUNT'] = $row_conts_i['cnt'];
		$cont_made[$i]['DATE'] = $row_conts_i['DATE'];
		$cont_made[$i]['IP'] = $row_conts_i['IP'];
		$cont_made[$i]['RECEIVER'] = $row_conts_i['RECEIVER'];
		$i++;
	}
	
	$response=$dbMessageLog->getMessagesDataSearchPageDetails($profileid,'RECEIVER');
	//$sql_conts_a = "SELECT COUNT(*) as cnt,IP FROM newjs.$fromtable WHERE RECEIVER = '$profileid' AND TYPE='A' GROUP BY IP";
	//$sql_conts_a = "SELECT CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,INET_NTOA(IP) AS IP,RECEIVER FROM newjs.$fromtable WHERE RECEIVER = '$profileid' AND TYPE='A' ORDER BY DATE limit 20";
     //   $result_conts_a =$mysql->ExecuteQuery($sql_conts_a,$myDb) or die("Unable to select from $fromtable".mysql_error_js());
         
	$j=0;
	//while($row_conts_a =$mysql->fetchArray($result_conts_a))
	foreach($response as $key=>$row_conts_a)
	{
		//$cont_acc[$j]['COUNT'] = $row_conts_a['cnt'];
		$cont_acc[$j]['DATE'] = $row_conts_a['DATE'];
		$cont_acc[$j]['IP'] = $row_conts_a['IP'];
		$cont_acc[$j]['RECEIVER'] = $row_conts_a['RECEIVER'];
		
		$j++;
	}

	if(count($cont_acc)==0)
        {
                $smarty->assign("nocontacc","1");
        }
	
	if(count($cont_made)==0)
        {
                $smarty->assign("nocontmade","1");
        }
	
	$sql_abus = "SELECT * FROM userplane.abuses_records where MESSAGE_FROM = '$profileid'";
	
	$res_abus = mysql_query_decide($sql_abus) or die("Unable to select from abuses_records".mysql_error_js());
	
	if(mysql_num_rows($res_abus)==0)
        {
                $smarty->assign("noabdet","1");
        }
	else
	{
		$k=0;
		while($row_abus = mysql_fetch_array($res_abus))
		{
			$ab[$k]['FROM_IP'] = $row_abus['FROM_IP'];
			$ab[$k]['MESSAGE'] = strip_tags($row_abus['MESSAGE']);
			$ab[$k]['TIME'] = $row_abus['TIME'];
		
			$to_user = $row_abus['MESSAGE_TO'];
			$sql_uname = "SELECT USERNAME FROM newjs.JPROFILE where PROFILEID = '$to_user'";
			$res_uname = mysql_query_decide($sql_uname) or die("Unable to select from JPROFILE".mysql_error_js());
			$row_uname = mysql_fetch_array($res_uname);
			$ab[$k]['TO_USER'] = $row_uname['USERNAME'];

			$k++;
		}
	}
	
	$sql_gender="SELECT ORIG_GENDER,NEW_GENDER,RESPONSE_DT FROM jsadmin.PROFILE_CHANGE_REQUEST where PROFILEID='$profileid' AND REQUEST_FOR='G'";
	$res_gender=mysql_query_decide($sql_gender) or die("Unable to select from gender_records".mysql_error_js());
	
	if(mysql_num_rows($res_gender)==0)
        {
                $smarty->assign("nochangegender","1");
        }
	else
	{
		$p=0;
		while($row_gender = mysql_fetch_array($res_gender))
		{
			$abc[$p]['ORIG_GENDER'] = $row_gender['ORIG_GENDER'];
			$abc[$p]['NEW_GENDER'] = $row_gender['NEW_GENDER'];
			$abc[$p]['RESPONSE_DT'] = $row_gender['RESPONSE_DT'];
			$p++;
			if($row_gender['NEW_GENDER']=='')
				$smarty->assign("nonewgender","1");
		}
	}

	$iAmUsingMongoDb = JsConstants::$useMongoDb;
	if($iAmUsingMongoDb)
	{
		$mongoStore = new PROFILE_EDIT_LOG();
		$whereCond["PROFILEID"] = $profileid;
		$columnNotPresentArr = array('CASTE');
		$arr = $mongoStore->getRecords($whereCond,$columnNotPresentArr,'CASTE,MOD_DT','MOD_DT');
	}
	else
	{
		$sql_caste="SELECT CASTE,MOD_DT FROM newjs.EDIT_LOG WHERE PROFILEID = '$profileid' AND CASTE != '0' ORDER BY MOD_DT DESC";
		$res_caste=mysql_query_decide($sql_caste) or die("Unable to select from caste_records".mysql_error_js());
	}
	if(!is_array($arr) && $iAmUsingMongoDb)
        {
                $smarty->assign("nochangecaste","1");
        }
	else
	{
		if($iAmUsingMongoDb)
		{
			if(is_array($arr))
			{
				foreach($arr as $k=>$v)
				{
					$a[0] = $myCaste;
					if($v["CASTE"])
					{
						$mod_d[] = $v["MOD_DT"];	
						$a[] = $v["CASTE"];
						$oldestTime = $v["MOD_DT"];
					}
				}
			}
			$mod_d[]= $oldestTime;
		}
		else
		{
			while($row_caste = mysql_fetch_array($res_caste))
			{
				$a[] = $row_caste['CASTE'];
				$mod_d[] = $row_caste['MOD_DT'];
			}                        
		}
		
		for($i=0;$i<count($a);$i++)
		{
		      if($a[$i]==$a[$i-1]) 
			       continue;
		      $b=$a[$i];
		      $caste_val_new=$CASTE_DROP_SMALL["$b"];
		      $caste[]=end(explode("-",$caste_val_new));
		}
		for($i=0;$i<count($a);$i++)
		{
			if($a[$i]==$a[$i-1])
				continue;
			$mod_dt[]=$mod_d[$i];
		}
		$count_row=sizeof($caste);
	}
	
	//$smarty->assign("details",$details);
	$smarty->assign("abc",$abc);
	$smarty->assign("CASTE",$caste);
	$smarty->assign("MOD_DT",$mod_dt);
	$smarty->assign("COUNT",$count_row);

	$smarty->assign("USERNAME",$username);
	$smarty->assign("EMAIL",$email);
	$smarty->assign("IPADD",$ipadd);
	$smarty->assign("ENTRY_DT",$entry_dt);
	$smarty->assign("PHONE_RES",$phone_res);
	$smarty->assign("PHONE_MOB",$phone_mob);
	$smarty->assign("CONTACT",$contact);

	$smarty->assign("pdet",$pdet);
	$smarty->assign("cont_made",$cont_made);
	$smarty->assign("cont_acc",$cont_acc);
	$smarty->assign("ab",$ab);
	$smarty->assign("uname",$uname);
	$smarty->assign("cid",$cid);
	$smarty->display('searchpage_userdetails.htm');

}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
                                                                                                                             
}
?>
