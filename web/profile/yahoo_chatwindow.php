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
	if(!strstr($data['EMAIL'],"@yahoo"))
	{
		$no_yahooid=1;
		 $smarty->assign("no_yahooid","$no_yahooid");
		$smarty->display("yahoo_chat_win_3.htm");
                exit;
	}
	
	$sql="select PROFILEID,chat_flag,jeevansathi_id from bot_jeevansathi.user_yahoo where profileID IN($data[PROFILEID],$sendersid) and chat_flag=1";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		if($row['profileID']==$data["PROFILEID"])
			 $smarty->assign("CHATMSG","$multiple_chat");
		else
			$smarty->assign("CHATMSG"," $row[jeevansathi_ID] cannot receive your chat request because $opposite_g is in a chat already");
                $smarty->display("yahoo_chat_win_3.htm");
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
                        $smarty->display("yahoo_chat_win_3.htm");
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
                                $smarty->display("yahoo_chat_win_3.htm");
                                exit;
                        }
                }
		$sql="select GENDER, USERNAME, EMAIL, AGE from newjs.JPROFILE where  activatedKey=1 and PROFILEID = '$sendersid' AND EMAIL LIKE '%@yahoo%'";
		$resgender=mysql_query_decide($sql);
		if($genderrow=mysql_fetch_array($resgender))
		{
			if($genderrow["GENDER"]==$data["GENDER"])
			{
				$smarty->assign("CHATMSG","$opposite_gender");
				$smarty->display("yahoo_chat_win_3.htm");
				exit;
			}

			//add filter condition
			if(!check_privacy_filtered1($receiversid,$sendersid))
			{
				$smarty->assign("CHATMSG","Chat cannot be initiated because you do not meet $genderrow[USERNAME]'s filters");
				$smarty->display("yahoo_chat_win_3.htm");
				exit;
			}
			else
			{
				$sql="select * from bot_jeevansathi.user_yahoo where PROFILEID =$data[PROFILEID]";
				$res=mysql_query_decide($sql) or die(mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					if($row['online_flag']==0)
					{
						$smarty->assign("CHATMSG",'To chat with this member, log into yahoo chat with the id <b>'.$data['EMAIL'].'</b> and click on the \'Chat on yahoo\' link again ');
						$smarty->display("yahoo_chat_win_3.htm");
						exit;
					}
					if($row['online_flag']==4)
					{
						$smarty->assign("CHATMSG",'Jeevansathi chat assistent already sent you friend request, but you haven\'t added him as your friend, you can only chat with our jeevansathi users by adding jeevansathi chat assistent in your friend list.');
                                                $smarty->display("yahoo_chat_win_3.htm");
                                                exit;
					}
					$profilelink=$SITE_URL."/profile/viewprofile.php?username=$data[USERNAME]";
					$k=strlen($data['EMAIL'])-strpos($data['EMAIL'],"@");	
					$sender=substr($data['EMAIL'],0,strpos($data['EMAIL'],"@"));
					$receiver=substr($genderrow["EMAIL"],0,strpos($genderrow['EMAIL'],"@"));
					$path_to_client="/home/nikhil/Desktop/freehoo_bck/freehoo-3.5.2/udpclient";
					$first_mes="*User*$data[USERNAME]*wants*to*chat*with*you.*Visit*$profilelink*to*view*their*profile.";
					$second_mes="*To*approve*chat*request,*send*'yes'.*To*decline*chat*request,*send*'no'.";
					$third_mes="*Waiting*for*$genderrow[USERNAME]\'s*approval*for*chat...";
				//dd	echo system('/home/ashish/Desktop/udpclient ".$recver.'*user*checkjs4*wants*to*chat*with\r\n*you*Visit*www.jeevansathi.com/xyz');
					echo system("$path_to_client $receiver$first_mes");
					echo system("$path_to_client $receiver$second_mes");
					echo system("$path_to_client /forward*$receiver*$sender");
					echo system("$path_to_client $sender$third_mes");	

//					echo system('/home/ashish/Desktop/udpclient '.$recver.'*to*approve*send*yes*to*Decline*send*no.');
//					echo system('/home/ashish/Desktop/udpclient /alert*'.$sender.'*'.$recver);
//					echo system('/home/ashish/Desktop/udpclient '.$sender.'*Awaiting*remote*users*approval');
//					passthru("/home/nikhil/Desktop/

	
					$smarty->assign('receiversid',$receiversid);
					$smarty->assign("sendersid",$sendersid);

					$smarty->assign("CHATMSG","Chat request successful.<BR>");
					$smarty->display("yahoo_chat_win_3.htm");
					exit;
				}
				else
				{
					$email=$data['EMAIL'];
					$sql="insert into bot_jeevansathi.invites_yahoo (EMAIL) values('$email')";
					mysql_query_decide($sql) or die(mysql_error_js());
                                        $smarty->assign("CHATMSG","Jeevansathi chat assistent sent you a friend request , you can only chat with jeevansathi users by adding him as your friend.");
                                        $smarty->display("yahoo_chat_win_3.htm");
                                        exit;

				}
			}
		}
		else
		{
			$smarty->assign("CHATMSG","You do not have a yahoo id registered with us");
			$smarty->display("yahoo_chat_win_3.htm");
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
	$smarty->display("yahoo_chat_win_1.htm");
}
?>
