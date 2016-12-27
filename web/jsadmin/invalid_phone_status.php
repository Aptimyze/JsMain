<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/JProfileUpdateLib.php");
if(authenticated($cid))
{
	$timestamp  = date("Y-m-d H:m:s");
	$user 	    = getname($cid);
	$loop       = 0;
	$message    ='OPS';
	if($_POST['pids']){
		$pids =$_POST['pids'];
		$tot = count($pids);
	}	
	if($_POST['submit'] =='Submit')	
	{
		/* when profileid selected for invalid/delete mark */
		$smarty->assign("cid",$cid);	
		$smarty->assign("user",$user);

		/* New Code added for marking valid profiles */
		$arrValid	=array();
		$arrInvalid 	=array();	
		for($i=0;$i<$tot;$i++){	
			$selId  = $pids[$i];
			$selIdVal = $_POST["$selId"];	
			if($selIdVal ==1){
				$arrValid[] = $selId; 
				$CntValid =count($arrValid);
			}
			else if($selIdVal ==2){
				$arrInvalid[] = $selId;
				$CntInvalid = count($selId);	
			}
		}			
		if($CntValid >0){
			markValidProfiles($arrValid,$message,$user) ;
			$msgVer ="Profiles selected to mark valid have been successfully verified.<br>";
			$validity =1;
		}
		if($CntInvalid >0){
			if($validity)
				$smarty->assign("MSGVer",$msgVer);
			$smarty->assign("pids",$arrInvalid);
			$smarty->display("invalid_phone_delete.tpl");
			exit;
		}
		else{
                	$smarty->assign("MSG",$msgVer);
                	$smarty->display("invalid_phone_status.tpl");
			exit;
		}
		/* End New Code */
	}
	else if($_POST['confirm']=='Confirm')
	{
		$comments   = $_POST['comments'];
		$reason	    = $_POST['reason'];	

		if($reason =='invalid')	
		{
			/*  profileid selected for Invalid Mark */
			$actionStatus ='I';
			while($loop <$tot)
			{
				$profile_id = $pids[$loop];
				phoneUpdateProcess($profile_id,'','',$actionStatus,$message,$user);

                                $sql_update="UPDATE jsadmin.REPORT_INVALID_PHONE SET `VERIFIED`='I' WHERE SUBMITTEE='$profile_id'";
                                 mysql_query_decide($sql_update) or die(mysql_error_js());

                                $sql_comments="REPLACE INTO jsadmin.INVALID_PHONE_STATUS(`DATE`,`PROFILE_ID`,`COMMENTS_REASON`,`STATUS`) VALUES('$timestamp','$profile_id','$comments','I')";
                                $result=mysql_query_decide($sql_comments) or die(mysql_error_js());
				$loop++;
			}
			$msg="Selected Profile have been marked Invalid.<br>";
		}
		else if($reason =='delete')
		{
			/* profile id selected for delete  */
			$other   = "Invalid Phone";
			while($loop <$tot)
			{
				$pid = $pids[$loop];

				// Update Reoprt Invalid Phone Status for the profiled
				$sql_update="UPDATE jsadmin.REPORT_INVALID_PHONE SET `VERIFIED`='D' WHERE SUBMITTEE='$pid'";
				 mysql_query_decide($sql_update) or die(mysql_error_js());	
	
				// Comments added for Invalid and Deleted Marked Profiles (log table for invalid and deleted profiles)	
                		$sql_comments="REPLACE INTO jsadmin.INVALID_PHONE_STATUS(`DATE`,`PROFILE_ID`,`COMMENTS_REASON`,`STATUS`) VALUES('$timestamp','$pid','$comments','D')";
                		$result=mysql_query_decide($sql_comments) or die(mysql_error_js());

	                	$sql= "INSERT INTO jsadmin.MARK_DELETE(PROFILEID, STATUS, M_DATE, DATE, REASON, COMMENTS, ENTRY_BY) VALUES('$pid','D','$timestamp','$timestamp','$other','$comments','$user')";
        	        	$res= mysql_query_decide($sql) or die(mysql_error_js());
						$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
						$jprofileUpdateObj->updateJProfileForArchive($pid);
						$arrFields = array('MOB_STATUS'=>'N','LANDL_STATUS'=>'N','PHONE_FLAG'=>'');
						$jprofileUpdateObj->editJPROFILE($arrFields,$pid,"PROFILEID",'');
						
                                //$sql="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',MOB_STATUS='N',LANDL_STATUS='N',PHONE_FLAG='',activatedKey=0 where PROFILEID='$pid'";
                                //mysql_query_decide($sql) or die("$sql".mysql_error_js());

                                $sql="DELETE FROM newjs.CONNECT WHERE PROFILEID='$pid'";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());

                                $sql="SELECT RECEIVE_TIME FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$pid' and SCREENING_TYPE='O'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $resf=mysql_fetch_array($res);
                                $rec_time=$resf['RECEIVE_TIME'];
                                $date_time=explode(" ",$rec_time);
                                $date_y_m_d=explode("-",$date_time[0]);
                                $time_h_m_s=explode(":",$date_time[1]);
                                if($date_time[1])
                                     $timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
                                     $timezone=date("T",$timestamp);
                                     if($timezone=="EDT")
                                        $timezone="EST5EDT";
                                $sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME,           SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE,SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'DELETED', SUBSCRIPTION_TYPE, SCREENING_VAL,'$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());

                                $sql_act = "SELECT USERNAME,ACTIVATED,SUBSCRIPTION,MSTATUS  FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
                                $res_act = mysql_query_decide($sql_act) or die(mysql_error_js());
                                $row_act = mysql_fetch_array($res_act);
				$username = $row_act['USERNAME'];

				//Tracking for Mis for Deleted Profiles
                                $subscription=$row_act['SUBSCRIPTION'];
                                $mstatus=$row_act['MSTATUS'];
                                $now=date("Y-m-d");
                                if($val=="new")
                                        $mod_type="N";
                                else
                                        $mod_type="E";
                                if($subscription!='')
                                        $subs_type="P";
                                else
                                        $subs_type="F";
                                if($mstatus=="S")
                                        $mtype="S";
                                elseif($mstatus=="A")
                                        $mtype="A";

                                $sql="UPDATE MIS.TRACK_DELETED_PROFILES SET COUNT=COUNT+1  WHERE ENTRY_DT='$now' AND MOD_TYPE='$mod_type' AND SUBS_TYPE='$subs_type' AND MSTATUS='$mtype'";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if(mysql_affected_rows_js()==0 && ($mtype=="S" || $mtype=="A"))
                                {

                                        $sql_track="INSERT INTO MIS.TRACK_DELETED_PROFILES VALUES('',1,'$now','$mod_type','$subs_type','$mtype')";
                                        mysql_query_decide($sql_track) or die("$sql_track".mysql_query_decide());
                                }

                                $time = date("Y-M-d");
                                $sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($pid,'$username','$other','$comments','$user','$time')";
                                mysql_query_decide($sql) or die(mysql_error_js());
				$loop++;
			}
			$msg="Selected profile have been deleted successfully .<br>";
		}
		$smarty->assign("MSG",$msg);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("invalid_phone_status.tpl");
	}
	else
	{
		/* Default Status of the page 
		*  List of profileids whose status is marked as invalid form JS application
		*/
		
		$sql="SELECT SUBMITTER,SUBMITTEE,SUBMIT_DATE,COMMENTS,PHONE,MOBILE from jsadmin.REPORT_INVALID_PHONE WHERE VERIFIED='N' ORDER BY SUBMIT_DATE DESC LIMIT 30";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$data_values = array();
		$i=0;
		while($myrow=mysql_fetch_array($result))
		{	
			$srNo =$i+1;
			$data_values[$i] = array(
						"SNo"=>"$srNo",
						"submitter"=>$myrow['SUBMITTER'],
                        	                "submittee"=>$myrow['SUBMITTEE'],
                                	        "date_time"=>$myrow['SUBMIT_DATE'],
                                        	"comments"=>$myrow['COMMENTS'],
						);
			$ids[] = $myrow['SUBMITTER'];
			$ids[] = $myrow['SUBMITTEE'];
			$i++;
		}
		$idStr = implode("','",array_unique($ids));
		$idStr = "'".$idStr."'";
		$sqlUser= "SELECT PROFILEID,USERNAME,PHONE_RES,PHONE_MOB,ISD,STD FROM newjs.JPROFILE WHERE PROFILEID in($idStr)";
		$resUser=mysql_query_decide($sqlUser) or die(mysql_error_js());
			$userData = array();
                        while($rowUser=mysql_fetch_assoc($resUser))
			{
				$phone_res ="";
				$phone_mob ="";
				if(trim($rowUser['PHONE_RES']))
					$phone_res = $rowUser['ISD']."-".$rowUser['STD']."-".$rowUser['PHONE_RES']."(R)";
				if(trim($rowUser['PHONE_MOB']))
					$phone_mob = $rowUser['PHONE_MOB']."(M)";
//				$phoneNoArr = implode(",",array($phone_res,$phone_mob));	
                                $phStr = "";
                                if($phone_res && $phone_mob)
                                        $phStr = ",";
                                $phoneNoArr = $phone_res.$phStr.$phone_mob;
				$userData[$rowUser['PROFILEID']] = array(
										"USERNAME"=>$rowUser['USERNAME'],
										"PHONE_RES"=>$phone_res,
										"PHONE_MOB"=>$phone_mob,
										"PHONE_ARRAY"=> $phoneNoArr
									);
			
			}
			foreach($data_values as $k=>$v)
			{
				$data_values[$k]["submitterUser"] = $userData[$v['submitter']]['USERNAME'];
				$data_values[$k]["submitteeUser"] = $userData[$v['submittee']]['USERNAME'];
				$data_values[$k]["phoneNo"] = $userData[$v['submittee']]['PHONE_ARRAY'];
			}
		$smarty->assign("dataVal",$data_values);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->display("invalid_phone_status.tpl");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}


/* Function used to mark the phone numbers in Verified status (Used only in file:invalid_phone_status.php)
 * aparameter: array of profileid
 * Mark both Mobile and Landline in Verified State based on the PROFILED
*/
function markValidProfiles($pids,$message,$username)
{
	if(!is_array($pids))
		return;
	$profileidStr =implode("','",$pids);		
	$profileidStr ="'".$profileidStr."'";
	$newProfileArr =array();
$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
				$profileid=$profileid;
				$arrFields = array();
        $sql ="update jsadmin.REPORT_INVALID_PHONE SET VERIFIED='Y' where `SUBMITTEE` IN ($profileidStr)";
        mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);

        $sqlValid= "SELECT PROFILEID,STD,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID IN($profileidStr)";
        $resValid=mysql_query_decide($sqlValid) or die(mysql_error_js());
        while($rowV = mysql_fetch_array($resValid))
        {
                $profileid      = $rowV['PROFILEID'];
                $std            = $rowV['STD'];
                $mob            = $rowV['PHONE_MOB'];
                $landline       = $rowV['PHONE_RES'];

		if($mob){
			//$query_param ="MOB_STATUS='Y',PHONE_FLAG=''";
			$arrFields['MOB_STATUS']='Y';
			$arrFields['PHONE_FLAG']='';
			
			$phone_type ='M';
		}
		else if($landline){
			//$query_param ="LANDL_STATUS='Y',PHONE_FLAG=''";
			$arrFields['LANDL_STATUS']='Y';
			$arrFields['PHONE_FLAG']='';
			$phone_type ='L';
		}	
		
						
				$exrtaWhereCond = "";
				$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
	//	$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='$profileid'";
//		mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);

		$sqlAlt ="SELECT ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
		$resAlt =mysql_query_decide($sqlAlt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAlt);
		if($rowAlt = mysql_fetch_array($resAlt))
		{
			if(trim($rowAlt['ALT_MOBILE']))
			{
        // $memObject=new UserMemcache;
        // $memObject->delete("JPROFILE_CONTACT_".$profileid);
        // unset($memObject);
			$arrParams = array('ALT_MOB_STATUS'=>'Y');
			$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
			//	$sqlAltUp="UPDATE newjs.JPROFILE_CONTACT SET `ALT_MOB_STATUS`='Y' WHERE `PROFILEID` =  '".$profileid."'";
              //                  mysql_query_decide($sqlAltUp) or logError("Could not update profile details in JPROFILE_CONTACT ",$sqlAltUp);
			}
		}
		$sql ="insert into jsadmin.PHONE_VERIFIED_LOG (`PROFILEID`,`PHONE_TYPE`,`PHONE_NUM`,`MSG`,`OP_USERNAME`,`ENTRY_DT`) VALUES ('$profileid','$phone_type','$phone_num','$message','$username',now())";
	mysql_query_decide($sql) or logError("Could not insert profile details in PHONE_VERIFIED_LOG ",$sql);
	}
}
?>
