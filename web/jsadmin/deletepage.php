<?php
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if(authenticated($cid))
{	
	$pid = stripslashes($pid);
	$entryby = getuser($cid);
	$now= date("Y-m-d H:i:s");
	if($c>0)
	{
		//getting the reason for deleting the profile
		$reasons = $_POST["reason"];
		if($reasons != "")
		{
			if($reasons=="Other")
			{
				if(trim($other) != '')
					$reason_ar[] = $other;
			}
			else
				$reason_ar[] = $reasons;
		}

		if(!$flag_search)
		{
			if(is_array($reason_ar))
			{
				$flag_deletion = 1;	
				$sql_act = "SELECT USERNAME,ACTIVATED,SUBSCRIPTION,MSTATUS,HAVEPHOTO,ACTIVATED,PREACTIVATED,EMAIL  FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
				$res_act = mysql_query_decide($sql_act) or die(mysql_error_js());
				$row_act = mysql_fetch_array($res_act);
				if($c==1 && ($FROM=='U' || $FROM=='SK'))
				{
					$activated=$row_act['ACTIVATED'];
					$preactivated=$row_act['PREACTIVATED'];
				}
				//added by sriram.
				if($row_act['HAVEPHOTO']=="U" || $row_act['HAVEPHOTO']=="Y")
				{
					$profileObj = new LoggedInProfile('',$pid);
					$profileObj->getDetail('', '', '*');
					$pictureServiceObj = new PictureService($profileObj);
					$PICTURE_FOR_SCREEN_NEW = new NonScreenedPicture();
					$whereCondition["PROFILEID"] = $pid;
					$pics=$PICTURE_FOR_SCREEN_NEW->get($whereCondition);
					if(is_array($pics))
					{
						foreach($pics as $k=>$v)
						{
							if($k==0)
								continue;
							$pictureid = $v['PICTUREID'];
							$pictureServiceObj->deletePhoto($pictureid,$pid);
						}
						$pictureServiceObj->deletePhoto($pics[0]['PICTUREID'],$pid);
					}
				}		
				$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
				$jprofileUpdateObj->updateJProfileForArchive($pid);

			 	//$sql="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID in ($pid)";
        	      //          mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$sql="UPDATE jsadmin.MARK_DELETE SET STATUS='D', DATE='$now' WHERE PROFILEID IN ($pid)";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$sql="DELETE FROM newjs.CONNECT WHERE PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="SELECT RECEIVE_TIME,SCREENING_VAL FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$pid' and SCREENING_TYPE='O'"; 
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$resf=mysql_fetch_array($res);
				$rec_time=$resf['RECEIVE_TIME'];
				$screeningValMainAdmin = $resf['SCREENING_VAL'];
				$date_time=explode(" ",$rec_time);
				$date_y_m_d=explode("-",$date_time[0]);
				$time_h_m_s=explode(":",$date_time[1]);
				if($date_time[1])
					$timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
				$timezone=date("T",$timestamp);
				if($timezone=="EDT")
					$timezone="EST5EDT";
				if($activated=="N" || $activated=="U")
					$screeningValMainAdmin = 0;
	
				$sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME,           SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE,SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'DELETED', SUBSCRIPTION_TYPE,'$screeningValMainAdmin','$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				//Tracking for Mis for Deleted Profiles by Tapan Arora
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
        	                //code addition ended by Tapan Arora		
				$reason = implode(",",$reason_ar);
				$tm = date("Y-M-d");	
				$user = getuser($cid);
				$sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($pid,'$username','$reason','$comments','$user','$tm')";
				mysql_query_decide($sql) or die(mysql_error_js());

				/*ADD START 10.07.2006 (Tripti) Adding an entry into NEW_EDIT_COUNT*/
				if($c==1 && ($FROM=='U' || $FROM=='SK'))
				{
					$now=date("Y-m-d");
					$sql_ne="SELECT COUNT(*) as cnt from MIS.NEW_EDIT_COUNT where SCREEN_DATE='$now' AND SCREENED_BY='$user'";
					$result_ne=mysql_query_decide($sql_ne) or die(mysql_error_js());
					$row_ne=mysql_fetch_assoc($result_ne);
					if($row_ne['cnt']==0)
					{
						$sql_ins="INSERT INTO MIS.NEW_EDIT_COUNT (SCREEN_DATE,SCREENED_BY) VALUES('$now','$user')";
						mysql_query_decide($sql_ins) or die(mysql_error_js());
					}
					if($activated=='U' || ($activated=='H' && ($preactivated=='U' || $preactivated=='N')) || ($activated=='D' && ($preactivated=='U' || $preactivated=='N')) )
					{
						$sql_ne="UPDATE MIS.NEW_EDIT_COUNT SET NEW=NEW+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
						mysql_query_decide($sql_ne) or die(mysql_error_js());
					}
					else
					{
						$sql_ne="UPDATE MIS.NEW_EDIT_COUNT SET EDIT=EDIT+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
						mysql_query_decide($sql_ne) or die(mysql_error_js());
					}			
				}

				// include_once("../profile/InstantSMS.php");
				// $sms=new InstantSMS("PROFILE_DISPPROVE",$pid);
				// $sms->send();

				if($unverify==1)
				{

					return;
				}
			}
			/*ADD END 10.07.2006 (Tripti) Adding an entry into NEW_EDIT_COUNT*/	
			else
			{
				$flag_deletion = 0;
				$msg = "Please specify at least one reason for deletion of profile.";
				$smarty->assign("MSG",$msg);
				$smarty->assign("cid",$cid);
				$smarty->assign("pid",$pid);
				$smarty->assign("c",$c);
				$smarty->assign("user",$user);
				$smarty->assign("PAGE",$PAGE);
				$smarty->assign("grp_no",$grp_no);
				$smarty->assign("year1",$year1);
				$smarty->assign("month1",$month1);
				$smarty->assign("day1",$day1);
				$smarty->assign("year2",$year2);
				$smarty->assign("month2",$month2);
				$smarty->assign("day2",$day2);
				$smarty->assign("username",$username);
				$smarty->assign("email",$email);
				$smarty->assign("medit",$medit);
				$smarty->assign("FROM",$FROM);
				$smarty->display("delete_page.tpl");
			}


				//added by sriram to prevent the query being run several times on page reload.
			if($row_act['ACTIVATED']!='D')
			{
				$producerObj=new Producer();
				if($producerObj->getRabbitMQServerConnected())
				{
					$sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$pid)), 'redeliveryCount'=>0 );
					$producerObj->sendMessage($sendMailData);
					$sendMailData = array('process' =>'USER_DELETE','data' => ($pid), 'redeliveryCount'=>0 );
					$producerObj->sendMessage($sendMailData);
				}
				else
				{
					$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $pid > /dev/null &";
					$cmd = "/usr/bin/php -q ".$path;
					passthru($cmd);
				}

				//if user is a paid member, then send a mail to anil rawat.
				if($row_act["SUBSCRIPTION"])
				{
					$deleted_username = $row_act["USERNAME"];
					$to_email = "anil.rawat@naukri.com,anamika.singh@jeevansathi.com";
					//$to_email = "shiv.narayan@jeevansathi.com";
					$subject = $deleted_username."'s profile has been deleted.";
					$delmsg = "The profile of ".$deleted_username." has been deleted".
					$delmsg .= "\nDeleted by : ".$user;
					$delmsg .= "\nDelete Reason : ".$reason;
					$delmsg .= "\nComments : ".$comments;
					send_email($to_email,$delmsg,$subject);
				}
			}
		//end of - added by sriram to prevent the query being run several times on page reload.
		}
	}

	if($c<=0)
		$msg = "Please check the records to delete<br><br>";
	else
	{
		if($flag_deletion)	
		{
			deletemail($pid, $reason, $other,$entryby,$row_act);
		}
		if($name)
		{
			$user=$name;
			$msg = "You have successfully deleted $c records<br><a href=\"searchpage.php?user=$user&cid=$cid\">Search Page</a><br>";
		}
	}
	if($FROM=="S" || $flag_search)
	{
		//$msg .= "<a href=\"searchpage.php?user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&PAGE=$PAGE&grp_no=$grp_no\">";
		$smarty->assign("grp_no",$grp_no);
		$smarty->assign("PAGE",$PAGE);
	}
	if($FROM=="AN")
	{
		$msg .= "<a href=\"admin_new.php?name=$user&cid=$cid\">";
	}
	if($FROM=="AE")
	{
		$msg .= "<a href=\"admin_edit.php?name=$user&cid=$cid\">";
	}
	if($FROM=="U")
	{
		if($medit==1)
			$msg .= "<a href=\"view_profile_count.php?user=$user&cid=$cid&val=$val\">";
		elseif($medit==2)
			$msg .= "<a href=\"screen_new.php?user=$user&cid=$cid&val=$val\">";
		else
			$msg .= "<a href=\"userview.php?user=$user&cid=$cid\">";
	}
	if($FROM=="SK")
	{
		$msg .= "<a href=\"view_skipped_profiles.php?user=$user&cid=$cid&val=$val\">";
	}
	if($FROM!="S" && !$flag_search)
		$msg .= "Continue</a>&nbsp;&nbsp;&nbsp;&nbsp; ";
	if($FROM=="U" && !$medit)
		$msg .= "<a href=\"mainpage.php?name=$user&cid=$cid\">Exit</a>";
	//$msg .= "Continue &gt;&gt;</a>";	//Comment this
	$smarty->assign("name",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("MSG",$msg);
	//$smarty->assign("medit",$medit);
	$smarty->display("jsadmin_msg.tpl");
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

function deletemail($pid,$reason,$other,$entryby="",$row_act)
{
		$mailID = "1842";
		$to=$row_act['EMAIL'];
		$username=$row_act['USERNAME'];
    	
	//EMAIL_TYPE as told by Kunal
    $canSendObj= canSendFactory::initiateClass($channel=CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to,"EMAIL_TYPE"=>"SCREEN_FAIL"),$pid);
	$canSend = $canSendObj->canSendIt();
    
    if($canSend == true){
    	$subject = "Profile deleted because of terms of use violation";

      	$email_sender = new EmailSender(MailerGroup::DELETE_PROFILE, $mailID);
      	$msg = $reason;
      	$emailTpl = $email_sender->setProfileId($pid);
        $smartyObj = $emailTpl->getSmarty();
        $smartyObj->assign("username",$username);
        $smartyObj->assign("reason",$reason);
        $email_sender->send($to);
       //  $sentStatus = $email_sender->getEmailDeliveryStatus();
      	// echo($sentStatus);die;
    }
		
		//added by sriram for archiving of mail sent to deleted profile.
		$sql = "INSERT INTO jsadmin.ON_HOLD_PROFILES(PROFILEID,REASON,TYPE,ENTRYBY,ENTRY_DT) VALUES('$pid','$msg','D','$entryby',now())";
		mysql_query_decide($sql) or die($sql.mysql_error_js());
		//end of added by sriram for archiving of mail sent to deleted profile.
}

	
?>
