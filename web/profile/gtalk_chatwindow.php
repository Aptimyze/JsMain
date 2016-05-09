<?php
//Created by Shiv for customising the chat window
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
          $zipIt = 1;
if($zipIt)
          ob_start("ob_gzhandler");

include('connect.inc');
$db=connect_db();
$data=authenticated($checksum);

$multiple_chat='You cannot be involved in more than one chats at a time.';
$decline_contact='This user has declined your contact and hence you cannot chat with him/her.';
$cancel_contact='This user has cancelled your contact and hence you cannot chat with him/her';
$opposite_gender="You can chat with people of the opposite gender only.";
if($data)
{
	//receivers id the id of the person who has initiated the contact.
	//sendersid is the id of the person who this person wants to contact.
	$receiversid=$data["PROFILEID"];
	$sql="select EMAIL,GENDER from newjs.JPROFILE where  activatedKey=1 and PROFILEID=$data[PROFILEID]";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$data["EMAIL"]=$row['EMAIL'];
	$data['GENDER']=$row['GENDER'];

	//Please reomve this field.
	//$data['SUBSCRIPTION']='D,F';
	if($data['GENDER']=='F')
		$opposite_g='he';
	else
		$opposite_g='she';
	
	if(!strstr($data['EMAIL'],"@gmail.com"))
	{
		$no_gmailid=1;
		 $smarty->assign("no_gmailid","$no_gmailid");
		$smarty->display("gtalk_chat_win_3.htm");
                exit;
	}
	
	$sql="select profileID,chat_flag,jeevansathi_ID from bot_jeevansathi.user_info where profileID IN($data[PROFILEID],$sendersid) and chat_flag=1";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		if($row['profileID']==$data["PROFILEID"])
			 $smarty->assign("CHATMSG","$multiple_chat");
		else
			$smarty->assign("CHATMSG"," $row[jeevansathi_ID] cannot receive your chat request because $opposite_g is in a chat already");
                $smarty->display("gtalk_chat_win_3.htm");
                        exit;
	}	

	$myrights=get_rights($receiversid);
	if(in_array("F",$myrights))
	{
		$strKey = "";
                //Sharding On Contacts done by Lavesh Rawat
                $contactResult=getResultSet("count(*) as CNT","$receiversid","",$sendersid,"","'D'");
                $rowdecline[0]=$contactResult[0]['CNT']; 
                if($rowdecline[0] > 0)
                {
                        $smarty->assign("CHATMSG","$decline_contact");
                        $smarty->display("gtalk_chat_win_3.htm");
                        exit;
                }
                else
                {
                        //Sharding On Contacts done by Lavesh Rawat
                        $contactResult=getResultSet("count(*) as CNT","$sendersid","",$receiversid,"","'C'");
                        $rowdecline[0]=$contactResult[0]['CNT'];
                        if($rowdecline[0] > 0)
                        {
                                $smarty->assign("CHATMSG","$cancel_contact");
                                $smarty->display("gtalk_chat_win_3.htm");
                                exit;
                        }
                }
		$sql="select GENDER, USERNAME, EMAIL, AGE from newjs.JPROFILE where  activatedKey=1 and PROFILEID = '$sendersid' AND EMAIL LIKE '%@gmail.com'";
		$resgender=mysql_query_decide($sql);
		if($genderrow=mysql_fetch_array($resgender))
		{
			if($genderrow["GENDER"]==$data["GENDER"])
			{
				$smarty->assign("CHATMSG","$opposite_gender");
				$smarty->display("gtalk_chat_win_3.htm");
				exit;
			}

			//add filter condition
			if(check_privacy_filtered1($receiversid,$sendersid))
			{
				$smarty->assign("CHATMSG","Chat cannot be initiated because you do not meet $genderrow[USERNAME]'s filters");
				$smarty->display("gtalk_chat_win_3.htm");
				exit;
			}
			else
			{
				$sql="select * from bot_jeevansathi.user_info where profileID =$data[PROFILEID]";
				$res=mysql_query_decide($sql) or die(mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					if($row['on_off_flag']==0)
					{
						$smarty->assign("CHATMSG",'To chat with this member, log into google talk or gmail chat with the id <b>'.$data['EMAIL'].'</b> and click on the \'Chat on google talk\' link again ');
						$smarty->display("gtalk_chat_win_3.htm");
						exit;
					}
					if($row['on_off_flag']==4)
					{
						$msg="You can only chat with our jeevansathi users  by accepting chat invitation sent by jeevansathi chat assistent, so wait for the invitation of jeevansathichat on gtalk/gmail";
						$smarty->assign("CHATMSG",$msg);
						//$smarty->assign("CHATMSG",'Jeevansathi chat assistent already sent you friend request, but you haven\'t added him as your friend, you can only chat with our jeevansathi users by adding jeevansathi chat assistent in your friend list.');
                                                $smarty->display("gtalk_chat_win_3.htm");
                                                exit;
					}
					$profileilink=$SITE_URL."/profile/viewprofile.php?username=$data[USERNAME]";


					$sql="insert into bot_jeevansathi.requests  values ('','" .$data["EMAIL"]."','".$genderrow["EMAIL"]."','". $data["USERNAME"] ."','" .$genderrow["USERNAME"] ."','$profilelink')";
					mysql_query_decide($sql);
	
					$smarty->assign('receiversid',$receiversid);
					$smarty->assign("sendersid",$sendersid);

					$smarty->assign("CHATMSG","Chat request successful.<BR>");
					$smarty->display("gtalk_chat_win_3.htm");
					exit;
				}
				else
				{
					$email=$data['EMAIL'];
					$sql="insert into bot_jeevansathi.invites_high (gmailid) values('$email')";
					mysql_query_decide($sql) or die(mysql_error_js());
                                        $smarty->assign("CHATMSG","Jeevansathi chat assistent sent you a friend request , you can only chat with jeevansathi users by adding him as your friend.");
                                        $smarty->display("gtalk_chat_win_3.htm");
                                        exit;

				}
			}
		}
		else
		{
			$smarty->assign("CHATMSG","You do not have a gtalk id registered with us");
			$smarty->display("gtalk_chat_win_3.htm");
			exit;
		}
	}
	else
	{
		// show page for paying
		$smarty->display("gtalk_chat_win_2.htm");
	}

}
else
{
	// show page for logging in
	$smarty->assign("sendersid",$sendersid);
	$smarty->display("gtalk_chat_win_1.htm");
}
?>
