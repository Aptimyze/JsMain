<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");
	$db=connect_db();
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com"," web/profile/deleteprofile.php in USE",$msg);
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	if($from_search_error)
		$smarty->assign("from_search_error",1);
	$data=authenticated($checksum);
	if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
        {
                $fromprofilepage=1;
                mysql_select_db_js('marriage_bureau');
                include('../marriage_bureau/connectmb.inc');
                $mbdata=authenticatedmb($mbchecksum);
                if(!$mbdata)timeoutmb();
                $smarty->assign("source",$mbdata["SOURCE"]);
                $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
                mysql_select_db_js('newjs');
                //$data=login_every_user($profileid);
                $mbureau="bureau1";
        }
	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	//$regionstr=8;
	//$zonestr="18";
	//include("../bmsjs/bms_display.php");
	/************************************************End of Portion of Code*****************************************/
	//$db=connect_db();

	/********************Added By Shakti for link tracking*******************************/
	link_track("deleteprofile.php");
	/*************************************************************************************/
	$smarty->assign("head_tab","my jeevansathi");
	if($data)
	{
		//This is required since we have to fill the username,email and name field for success story
		if(true)
		{
			$smarty->assign("GENDER",$data['GENDER']);
			$smarty->assign("TEMPLATES_DIR",$smarty->template_dir);
			
			$sql="select EMAIL from JPROFILE where activatedKey=1 and PROFILEID=".$data['PROFILEID'];
			$res=mysql_query_decide($sql);
			if($row=mysql_fetch_row($res))
			{
				if($data['GENDER']=='M')
				{
					$name=$name_m;
					$who="H";
				}
				else
				{
					$who="W";
					$name=$name_w;
				}
				
				$smarty->assign("EMAIL_".$who,$row[0]);
				$smarty->assign("USERNAME_".$who,$data['USERNAME']);
				
				if($name=="")
				{
					$sql="select NAME from incentive.NAME_OF_USER where PROFILEID=".$data['PROFILEID'];
					$res=mysql_query_decide($sql);
					if($row=mysql_fetch_row($res))
						$smarty->assign("NAME_$who",$row[0]);
				}

			}

		}

		login_relogin_auth($data);//added for contact details on leftpanel.

		$del_reason_arr = array(
					array('value' => 'I found my match on Jeevansathi.com'),
					array('value' => 'I found my match on another matrimonial site'),
					array('value' => 'I found my match elsewhere'),
					array('value' => 'I am unhappy with Jeevansathi.com services'),
					array('value' => 'Other reasons')
					);
		$smarty->assign("del_reason_arr",$del_reason_arr);

		$profileid=$data["PROFILEID"];

		$sql ="Select PASSWORD from JPROFILE where  activatedKey=1 and PROFILEID = $profileid";
                $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow = mysql_fetch_array($result);
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		if($CMDhide)
		{
			$Hpasswd=stripslashes($Hpasswd);
			$is_error=0;
			if(trim($HIDEFOR)=='')
			{
				$is_error++;
                                $smarty->assign("FLAG_DURATION","1");
			}
                        if(!PasswordHashFunctions::validatePassword($Hpasswd, $myrow['PASSWORD']))
			{
				$is_error++;
                                $smarty->assign("FLAG_PSWD_H","1");
			}
			if($is_error)
			{
                                if($mbureau=="bureau1")
                                {
                                        $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                        $smarty->assign("checksum",$data["CHECKSUM"]);
                                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                                }
                                else
                                {
                                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                }
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("story",$story);
				$smarty->assign("HIDEFOR",$HIDEFOR);
				$smarty->assign("delete_reason",$delete_reason);
                                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        	$smarty->display("hide_delete.htm");
			}
			else  
			{
				$objProfileUpdate = JProfileUpdateLib::getInstance('newjs_master');
				$res = $objProfileUpdate->updateHideJPROFILE('',$profileid,$HIDEFOR);
				if(false === $res ) {
					$sql="update JPROFILE set PREACTIVATED=if(ACTIVATED<>'H',ACTIVATED,PREACTIVATED), ACTIVATED='H', ACTIVATE_ON=DATE_ADD(CURDATE(), INTERVAL $HIDEFOR DAY) where PROFILEID='$profileid'";
					logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				}
//				$sql="update JPROFILE set PREACTIVATED=if(ACTIVATED<>'H',ACTIVATED,PREACTIVATED), ACTIVATED='H', ACTIVATE_ON=DATE_ADD(CURDATE(), INTERVAL $HIDEFOR DAY) where PROFILEID='$profileid'";
//				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
				$data["ACTIVATED"]='H';
				$protect_obj->setcookies($data);

				if($mbureau=="bureau1")
				{
					$smarty->assign("mb_username_profile",$data["USERNAME"]);
					$smarty->assign("checksum",$data["CHECKSUM"]);
					$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
				else
				{
											 
					$smarty->assign("CHECKSUM",$checksum);
					$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				}											 
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

				$smarty->assign("HEADING","Profile Hidden");
				$smarty->assign("MSG","Your profile has been hidden.It will automatically be activated after the period you selected<br>To unhide it now click on the link below");
				$smarty->assign("LINK1","<a href=\"deleteprofile.php?checksum=$checksum\">Unhide your profile</a>");
				$smarty->display("confirmation1.htm");
			}
		}
		elseif($CMDunhide)
		{
			$Hpasswd=stripslashes($Hpasswd);	
                        if(!PasswordHashFunctions::validatePassword($Hpasswd, $myrow['PASSWORD']))
			{
                                if($mbureau=="bureau1")
                                {
                                        $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                        $smarty->assign("checksum",$data["CHECKSUM"]);
                                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                                }
                                else
                                {
                                                                                                 
                                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));                                                                                                 
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                }
                                $smarty->assign("FLAG_PSWD_H","1");
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("story",$story);
				$smarty->assign("HIDEFOR",$HIDEFOR);
				$smarty->assign("delete_reason",$delete_reason);
                                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                                $smarty->display("hide_delete.htm");
			}  
			else
			{
				$objProfileUpdate = JProfileUpdateLib::getInstance('newjs_master');
				$res = $objProfileUpdate->updateUnHideJPROFILE('',$profileid);
				if(false === $res) {
					$sql="update JPROFILE set ACTIVATED=PREACTIVATED where PROFILEID='$profileid'";
					logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				}
//				$sql="update JPROFILE set ACTIVATED=PREACTIVATED where PROFILEID='$profileid'";
//				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
				$sql="select PREACTIVATED from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
				$act_result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$act_row=mysql_fetch_row($act_result);
				
				$preactivated=$act_row[0];
				
				$data["ACTIVATED"]=$preactivated;
				$protect_obj->setcookies($data);

				if($mbureau=="bureau1")
				{
					$smarty->assign("mb_username_profile",$data["USERNAME"]);
					$smarty->assign("checksum",$data["CHECKSUM"]);
					$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
				else
				{
					$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				}
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("story",$story);
				$smarty->assign("HIDEFOR",$HIDEFOR);
				$smarty->assign("delete_reason",$delete_reason);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

				$smarty->assign("HEADING","Profile Activated");
				$smarty->assign("MSG","Your profile has been activated.<br>To hide/delete it click on the link below");
				$smarty->assign("LINK1","<a href=\"deleteprofile.php?checksum=$checksum\">Hide/Delete your profile</a>");
				$smarty->display("confirmation1.htm");
			}
		}
		elseif($CMDdelete)
		{
			//include_one("uploadphoto_inc.php");
			$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
			$default_extension = ".jpg";

			$is_error=0;
			if(trim($delete_reason)=='')
			{
				$is_error++;
                                $smarty->assign("FLAG_DEL_REASON","1");
			}
			if($delete_reason!='I found my match on Jeevansathi.com' && trim($specify_reason)=='')
			{
				$is_error++;
                                $smarty->assign("FLAG_SPECIFY_REASON","1");
			}
                        if(!PasswordHashFunctions::validatePassword($Dpasswd, $myrow['PASSWORD']))
			{
				$is_error++;
                                $smarty->assign("FLAG_PSWD_D","1");
			}
			else
				$smarty->assign("DPASSWORD",$Dpasswd);

			if($delete_reason=='I found my match on Jeevansathi.com')
			{
				include("uploadphoto_inc.php");
				$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
				$default_extension = ".jpg";
				global $max_filesize;
				global $file;
				$max_filesize=1048576;
				$photo_error=1;
				if($married_photo)
				{
					$filename="married_photo";
				
					if(!upload($filename,$acceptable_file_types,$default_extension))
					{
						
						$is_error++;
						$smarty->assign("FLAG_INVALID_FILE","1");
					}
				}
	
				if($SUBMIT_SUCCESS!="")
				{
					
					$email_h=trim($email_h);
					$email_w=trim($email_w);
					$name_h=htmlspecialchars(stripslashes(trim($name_h)),ENT_QUOTES);
					$name_w=htmlspecialchars(stripslashes(trim($name_w)),ENT_QUOTES);
					$contact_details=htmlspecialchars(stripslashes(trim($contact_details)),ENT_QUOTES);
					$comments=htmlspecialchars(stripslashes(trim($comments)),ENT_QUOTES);
					$username_h=trim($username_h);
					$username_w=trim($username_w);
					$Day=trim($Day);
					$Month=trim($Month);
					$Year=trim($Year);
					$check_date=0;
					$check_email_h='';
					$check_email_w='';
					$invalidh=1;
					$invalidw=1;
					$username_h_error='';
					$username_w_error='';
					$email_h_error='';
					$email_w_error='';
					$Subscription_error='';
					$email_h_db='';
					$email_w_db='';
					$username_h_db='';
					$username_w_db='';
					check_all_submission();
					
				}
				else
					$smarty->assign("ST","unchecked");
				
				
				if(!($name_h && $name_w && $contact_details && $comments && $photo_error && $check_date  && $username_h_error=="" && $username_w_error=="" &&$email_h_error=="" && $email_w_error=="" && $Subscription_error=="") && $SUBMIT_SUCCESS!="" || ($SUBMIT_SUCCESS!="" && $is_error))
				{
					maStripVARS("stripslashes");
					$smarty->assign("Submit",1);
					$smarty->assign("ST","checked");
					$smarty->assign("showstorybox","inline");
					$smarty->assign("EMAIL_H_ERROR",$email_h_error);
					$smarty->assign("EMAIL_W_ERROR",$email_w_error);
					$smarty->assign("USERNAME_H_ERROR",$username_h_error);
					$smarty->assign("USERNAME_W_ERROR",$username_w_error);
					$smarty->assign("SUB_ERROR",$Subscription_error);
					$smarty->assign("NAME_H_ERROR",$name_h_error);
					$smarty->assign("NAME_W_ERROR",$name_w_error);
			
					if(!$photo_error)
						$smarty->assign("PHOTO_ERROR","1");
					if($invalidh)
						$smarty->assign("INVALIDH","1");
					if($invalidw)
						$smarty->assign("INVALIDW","1");
						
					$smarty->assign("NAME_H",$name_h);
					$smarty->assign("NAME_W",$name_w);
					$smarty->assign("USERNAME_H",$username_h);
					$smarty->assign("USERNAME_W",$username_w);
					$smarty->assign("EMAIL_H",$email_h);
					$smarty->assign("EMAIL_W",$email_w);
					$smarty->assign("CONTACTDETAILS",$contact_details);
					$smarty->assign("Day",$Day);
					$smarty->assign("Month",$Month);
					$smarty->assign("Year",$Year);
					$smarty->assign("check_date",$check_date);
					$smarty->assign("STORY",$comments);
					
					if(!$name_h||!$name_w || !$contact_details || !$email || !$comments)
						$smarty->assign("MSG",'1');
					if(!($username_h &&  $username_w))
						$smarty->assign("MSG_USR",'1');
					$msg="You have not entered some of the fields.";
					$msg.="<a href=\"success_stories.php?send_story=1\">Enter again</a>";
					$smarty->assign("MSG",$msg);
					$send_story=1;
					$is_error=1;
				}
				else
					$Success_Sub=1; //VAriable proves that success story is not submitted
			}

			if($is_error)
			{
				if($mbureau=="bureau1")
				{
					$smarty->assign("mb_username_profile",$data["USERNAME"]);
					$smarty->assign("checksum",$data["CHECKSUM"]);
					$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
                else
                {
                                                                                 
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));                                                                                                 
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                }
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("story",$story);
				$smarty->assign("HIDEFOR",$HIDEFOR);
				$smarty->assign("delete_reason",$delete_reason);
				$smarty->assign("specify_reason",$specify_reason);
                                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                                $smarty->display("hide_delete.htm");
				
			}
			else  
			{	
				if($married_photo)
				{
					$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
					$fcontent = fread($fp,filesize($file["tmp_name"]));
					fclose($fp);
					$photo_content = addslashes($fcontent);
					$photo_status = "Y";
				}
				else
					$photo_status = "N";

				//If Profile is deleted by saying ' i found my match on js' but not submitted the story than need not to go inside
				if($Success_Sub && $SUBMIT_SUCCESS!="")
				{
					//maStripVARS("addslashes");
					if($username_h=="")
						$username_h=$username_h_db;
					if($username_w=="")
						$username_w=$username_w_db;
					if($email_h=="")
						$email_h=$email_h_db;
					if($email_w=="")
						$email_w=$email_w_db;
						
					if($data['GENDER']=='F')
						$username=$username_w;
					else
						$username=$username_h;
					
					$message.="NAME: ".$name_h."\t".$name_w."\t\r\n";
					$message.="USERNAME: ".$username_h."\t".$username_w."\t\r\n";//previously HUSBAND'S USERNAME
					//$message.="WIFE'S USERNAME: ".$username_w."\n";
					$message.="EMAIL: ".$email_h."\t".$email_w."\t\r\n";
					$message.="CONTACT DETAILS: ".$contact_details."\t\r\n";
					$message.="STORY: ".$comments."\t\r\n";
					if(!$married_photo)
						$message=nl2br($message);	
	
					send_email('success.story@jeevansathi.com,vivek@jeevansathi.com,ankit.k@jeevansathi.com',$message,"Success Story Received",$from="",$cc="",$bcc="",$fcontent,$_FILES[$filename]['type'],$_FILES[$filename]['name']);

					$date=$Year."-".$Month."-".$Day;
					if($data)
					{
						$gender=$data['GENDER'];
			
						if($gender=='F')
						{
							$send_email=$email_h;
							$EMAIL=$email_w;
							$EMAIL1=$email_h;
						}
						else
						{
							$send_email=$email_w;
							$EMAIL=$email_h;
							$EMAIL1=$email_w;
						}
					}
					else
					{
						$send_email=$email_w;
						$EMAIL=$email_h;
						$EMAIL1=$email_w;
					}
			
					$sql="insert into SUCCESS_STORIES(`NAME_H`,`NAME_W`,`USERNAME`,`WEDDING_DATE`,`CONTACT_DETAILS`,`EMAIL`,`EMAIL_W`,`COMMENTS`,`DATETIME`,`USERNAME_H`,`USERNAME_W`,`UPLOADED`,`SEND_EMAIL`) values('$name_h','$name_w','$username','$date','$contact_details','$EMAIL','$EMAIL1','$comments',now(),'$username_h','$username_w','N','$send_email')";
					
					mysql_query_decide($sql) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				}

				$sql_jp="SELECT USERNAME,EMAIL,GENDER,ACTIVATED,CONTACT FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
				$res_jp = mysql_query_decide($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
				$row_jp = mysql_fetch_array($res_jp);

				
				

				//insert delete reason
				$delete_reason = htmlspecialchars($delete_reason,ENT_QUOTES);
				$specify_reason = htmlspecialchars($specify_reason,ENT_QUOTES);
				$sql_del = "REPLACE INTO newjs.PROFILE_DEL_REASON(USERNAME,DEL_REASON, SPECIFIED_REASON,PROFILE_DEL_DATE,PROFILEID) VALUES('$row_jp[USERNAME]','$delete_reason', '$specify_reason',now(),'$profileid')";
				
				mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");

				//Mark Delete Single Profile
				$objProfileUpdate = JProfileUpdateLib::getInstance('newjs_master');
				$res = $objProfileUpdate->deactiveSingleProfile($profileid);
				if(false === $res) {
					$sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', activatedKey=0, MOD_DT=now() where PROFILEID='$profileid'";
					logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				}
//				$sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', activatedKey=0, MOD_DT=now() where PROFILEID='$profileid'";
//                                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				//added by neha
				$now= date("Y-m-d H:i:s");
				$sql= "UPDATE jsadmin.MARK_DELETE SET STATUS='D', DATE='$now' WHERE PROFILEID=$profileid ";
				mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				//delete offline contacts
                                //added by Neha Verma

				$sql="SELECT BILLID,ENTRY_DATE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
				$res= mysql_query_decide($sql) or die(mysql_error_js());
				$row= mysql_fetch_array($res);
				$entry_date= $row['ENTRY_DATE'];
				$bid= $row['BILLID'];
				$sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N' WHERE PROFILEID= '$profileid' AND ENTRY_DATE= '$entry_date' AND BILLID= '$bid'";
				$res= mysql_query_decide($sql) or die(mysql_error_js());
/*

				$SQL= "SELECT PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
				$RES= mysql_query_decide($SQL) or die (mysql_error_js());
				if(mysql_num_rows($RES)>0)
				{
					$sqldel1="INSERT ignore INTO jsadmin.DELETED_OFFLINE_MATCHES(ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE) SELECT ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
                			mysql_query_decide($sqldel1) or logError($sqldel1);
                			$sqldel2="DELETE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
                			mysql_query_decide($sqldel2) or logError($sqldel2);
				}

				$SQL1= "SELECT PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('ACC','SL','REJ')";
                                $RES1= mysql_query_decide($SQL1) or die (mysql_error_js());
                                if(mysql_num_rows($RES1)>0)
				{
					$sql_d= "UPDATE jsadmin.OFFLINE_MATCHES SET SHOW_ONLINE= 'N' WHERE PROFILEID= '$profileid' OR MATCH_ID= '$profileid' ";
                                	$res_d= mysql_query_decide($sql_d) or die (mysql_error_js());
	
				}
				
				$SQL2= "SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid'";
				$RES2= mysql_query_decide($SQL2) or die(mysql_error_js());
				if(mysql_num_rows($RES2)>0)
				{
                			$sqldel="INSERT ignore INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid' ";
                			mysql_query_decide($sqldel) or logError($sqldel);
                			$sqldel="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid'";
                			mysql_query_decide($sqldel) or logError($sqldel);
				}
*/
				
				// delete the contacts of this person
				//added by sriram to prevent the query on CONTACTS table being run several times on page reload.
				if($row_jp['ACTIVATED']!='D')
				{
					$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
					$cmd = "/usr/bin/php -q ".$path;
					passthru($cmd);
				}
				//end of - added by sriram to prevent the query on CONTACTS table being run several times on page reload.
				// log the person out as the profile has been deleted
				logout($checksum);
                                if($mbureau=="bureau1")
                                {
                                        $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                        $smarty->assign("checksum",$data["CHECKSUM"]);
                                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
                                else
                                {
                                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                }
	
				$smarty->assign("CHECKSUM",$checksum);
                                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("HEADING","Profile Deleted");

				$smarty->assign("MSG","Your profile has been deleted.<br><br><span class=\"mediumred\">Note: If you have accidently deleted your profile and wish to re-activate it, please contact <a href=\"mailto:webmaster@jeevansathi.com?SUBJECT=UNDELETE\">webmaster@jeevansathi.com</a> immediately.</span><br><br>To create a new profile click on the link below");
				if($mbureau=="bureau1")
                                {
                                        $source=$mbdata['SOURCE'];
                                        $mbchecksum=$mbdata['CHECKSUM'];
                                        $smarty->assign("LINK1","<a href=\"inputprofile.php?source=$source&mbchecksum=$mbchecksum\">Create a new profile</a>");
                                }
                                else
					$smarty->assign("LINK1","<a href=\"inputprofile.php\">Create a new profile</a>");
				$smarty->display("profile_deleted.htm");
			}
		}	
		else 
		{
                        if($mbureau=="bureau1")
                        {
                                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                $smarty->assign("checksum",$data["CHECKSUM"]);
                                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        
			}
                        else
                        {
                                                                                                 
                                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                        }
			$smarty->assign("CHECKSUM",$checksum);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->display("hide_delete.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	

	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
