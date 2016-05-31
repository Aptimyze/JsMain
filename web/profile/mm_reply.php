<?php
/*
*       File Name       :       mm_reply.php
*       Description     :       to reply to a particular message
*       Created by      :       Gaurav Arora
*       Created on      :       08 Sept 2005
**/
include("connect.inc");
include("contact.inc");
connect_db();

$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

$data=authenticated($checksum);

/********************************CODE FOR BMS**********************************************/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",14);
        $smarty->assign("bms_left",16);
        $smarty->assign("bms_bottom",23);
        $smarty->assign("bms_middle",15);
        $smarty->assign("bms_new_win",39);
/*******************************************************************************************/

if($data)
{
	//$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
	
	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	}

	$profileid=$data['PROFILEID'];
	 $mysql=new Mysql;
        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysql);
        $myDb=$mysql->connect("$myDbName");

	/* section of code to select USERNAME to whom the person is replying and deciding if that person can reply or not*/
	$sql="SELECT SENDER FROM MESSAGE_LOG WHERE ID='$id'";
	$result=$mysql->ExecuteQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=$mysql->fetchArray($result);
	$sender_profileid=$row['SENDER'];
	
	$sql_username="SELECT USERNAME,SUBSCRIPTION,ACTIVATED FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$row[SENDER]'";
	$result_username=mysql_query_decide($sql_username) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_username,"ShowErrTemplate");
	$row_username=mysql_fetch_array($result_username);

	$sql_rec="SELECT SUBSCRIPTION FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$result_rec=mysql_query_decide($sql_rec) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec,"ShowErrTemplate");
	$row_rec=mysql_fetch_array($result_rec);

	$sender_sub=$row_username['SUBSCRIPTION'];
	$rec_sub=$row_rec['SUBSCRIPTION'];
	if($row_username['ACTIVATED']=='D')
	{
		$can_reply='N';
	}
	else
	{
		$contact_status=get_contact_status($row['SENDER'],$profileid);
		if($contact_status=='RA' || $contact_status=='A')
		{
			if(strstr($sender_sub,'F') || strstr($rec_sub,'F') || strstr($sender_sub,'D'))
			{
				$can_reply='Y';
			}
		}
		else
			$can_reply='N';
	}
	if($can_reply=='Y')	
	{	
		$smarty->assign("TO",$row_username['USERNAME']);
		if($Submit)
		{
			//maStripVARS("stripslashes");
		 	/*added new for checking Obsence Msg and updating CONTACTS & MESSAGE_LOG TABLE*/
			if($contact_status=="A")
                        	$contact_id=get_contact_id_inbox($sender_profileid,$profileid);
                	elseif($contact_status=="RA")
                        	$contact_id=get_contact_id_inbox($profileid,$sender_profileid);
			
			$ip=FetchClientIP();
			if(strstr($ip, ","))    
			{
				$ip_new = explode(",",$ip);
				$ip = $ip_new[1];
			}
			$reply_msg=trim($reply_msg);
			$reply_msg=stripslashes(addslashes($reply_msg));
			//if mssg is obscene store it in obscene_message table and also in MESSAGE_LOG
			if($reply_msg)
			{
				//added by sriram
				//$generated_msg_log_id = generate_id_from_table("MESSAGE_LOG");
				if(obscene_message($reply_msg,$sender_profileid,$profileid))
				{
					$sql_obscene="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE) values ('$profileid','$sender_profileid',now(),'$ip','" . addslashes(nl2br($reply_msg)) . "','M')";
					mysql_query_decide($sql_obscene) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_obscene,"ShowErrTemplate");
					$id_obscene=mysql_insert_id_js();
					
					
					$msg_id=insert_into_message_log($profileid,$sender_profileid,'Y','Y',$id_obscene,'',$reply_msg);	
				}
				else
				{	
					$msg_id=insert_into_message_log($profileid,$sender_profileid,'Y','N',0,'',$reply_msg);
					
					//added by Nikhil --> Update the new message field counter by 1
					$sql_newmsg="update CONTACTS_STATUS set NEW_MES=NEW_MES+1 where PROFILEID='$sender_profileid'";
					mysql_query_decide($sql_newmsg) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_newmsg,"ShowErrTemplate");
					
				}
				
			
			}
			/*else
			{
				$sql_msglog="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE) values ('$profileid','$sender_profileid',now(),'$ip','N','N')";
				mysql_query_decide($sql_msglog) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_msglog,"ShowErrTemplate");
			}*/
			/*$sql_contact="update CONTACTS set TIME=NOW() where CONTACTID='$contact_id'";
                        mysql_query_decide($sql_contact) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_contact,"ShowErrTemplate");*/
			
			header("Location: ".$SITE_URL."/profile/mm_showmsg.php?checksum=$checksum&showmsg=y&id=$id&send=$send&msg_reply=$msg_reply&btn_reply=$btn_reply&sent_reply=Y");
			exit;
		}
	}
	else
	{
		if($contact_status=='RD')
			$error_msg="Sorry you cannot contact <b>$row_username[USERNAME]</b> because your request has been declined.<br><a href='/profile/mm_showmsg.php?checksum=$checksum&folderid=0'>&laquo; Go back to Inbox</a>";
		elseif($contact_status=='C')
			$error_msg="Sorry you cannot contact <b>$row_username[USERNAME]</b> because the user has cancelled the request.<br><a href='/profile/mm_showmsg.php?checksum=$checksum&folderid=0'>&laquo; Go back to Inbox</a>";
		elseif($contact_status=='RC' || $contact_status=='D' ||  $contact_status=='I' ||  $contact_status=='RI')
		{		echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?username=$row_username[USERNAME]&checksum=$checksum\"></body></html>";
                                exit;
		}
		elseif($row_username['ACTIVATED']=='D')
			$error_msg="Sorry you cannot contact <b>$row_username[USERNAME]</b> because this profile has been deleted from the site.<br><a href='/profile/mm_showmsg.php?checksum=$checksum&folderid=0'>&laquo; Go back to Inbox</a>";
		else
		{	
			$error_msg="To send a message to this user <a href=\"/membership/jspc\">upgrade your membership</a><br><a href='/profile/mm_showmsg.php?checksum=$checksum&folderid=0'>&laquo; Go back to Inbox</a>";
			$smarty->assign("UPGRADE_MEMBERSHIP",'Y');
		}
		$smarty->assign("ERROR_MSG",$error_msg);
		$smarty->display("contact_result.htm"); 
	}
}
else
{	
	TimedOut();
}
?>
