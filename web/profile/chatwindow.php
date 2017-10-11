<?php
//Created by nikhil for customising the chat window
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
          $zipIt = 1;
if($zipIt)
          ob_start("ob_gzhandler");

include('connect.inc');
$db=connect_db();
//$ALLOW_LOGIN_FROM_CHAT=1;
$data=authenticated($checksum);
$checksum=$data["CHECKSUM"];

$uniqueid=2.4;
$smarty->assign("uniqueid",$uniqueid);
if($status=="receiver" || $status=="offline")
{
	if($status=="receiver")
        {
                $sql="insert into userplane.chat_window_opened (SENDER,RECEIVER,DATE) values ('" .$sendersid."','".$receiversid."',now())";
                mysql_query_decide($sql);
        }
	$smarty->assign('receiversid',$receiversid);
	$smarty->assign("sendersid",$sendersid);
	$smarty->assign("senderusername",$senderusername);
	$smarty->assign("receiverusername",$receiverusername);
	$smarty->assign("status",$status);
	//if($senderusername=="test4js")
		$smarty->display('newchatwindow.html');
	/*else
		$smarty->display('chatwindow.html');*/
	exit;
}
else if($status=="sender" || $status=="sender_offline")
{
	if($data)
	{
		//receivers id the id of the person who has initiated the contact.
		//sendersid is the id of the person who this person wants to contact.
		$myrights=get_rights($receiversid);
		if(in_array("F",$myrights))
		{
			$strKey = "";

			//Sharding On Contacts done by Lavesh Rawat
		        $contactResult=getResultSet("count(*) as CNT","$receiversid","",$sendersid,"","'D'");
			$rowdecline[0]=$contactResult[0]['CNT'];
			if($rowdecline[0] > 0)
			{
				$smarty->assign("CHATMSG","This user has declined your contact and hence you cannot chat with him/her");
				$smarty->display("chat_win_3.htm");
				exit;
			}
			else
			{
				//mysql_free_result($resdecline);

				//Sharding On Contacts done by Lavesh Rawat
				$contactResult=getResultSet("count(*) as CNT","$sendersid","",$receiversid,"","'C'");
				$rowdecline[0]=$contactResult[0]['CNT'];
				if($rowdecline[0] > 0)
				{
					$smarty->assign("CHATMSG","This user has cancelled your contact and hence you cannot chat with him/her");
					$smarty->display("chat_win_3.htm");
					exit;
				}
			}		
			
		
			$sql="select count(*) from userplane.blocked where userID='" . $sendersid . "' and destinationUserID='$receiversid'";
			$resdecline=mysql_query_decide($sql);
			$rowdecline=mysql_fetch_row($resdecline);
			if($rowdecline[0] > 0)
			{
				$smarty->assign("CHATMSG","This user has declined your contact and hence you cannot chat with him/her");
				$smarty->display("chat_win_3.htm");
				exit;
			}
			
			$sql="select GENDER from JPROFILE where  activatedKey=1 and PROFILEID = '$sendersid'";
			$resgender=mysql_query_decide($sql);
			$genderrow=mysql_fetch_array($resgender);
			if($genderrow["GENDER"]==$data["GENDER"])
			{
				$smarty->assign("CHATMSG","You can chat with people of the opposite gender only");
				$smarty->display("chat_win_3.htm");
				exit;
			}
			$smarty->assign('receiversid',$receiversid);
			$smarty->assign("sendersid",$sendersid);
			$smarty->assign("senderusername",$senderusername);
			$smarty->assign("receiverusername",$receiverusername);
			$smarty->assign("status",$status);
			//if($receiverusername=="test4js")
				$smarty->display('newchatwindow.html');
			/*else
				$smarty->display('chatwindow.html');*/
			exit;
		}
		else
		{
			// show page for paying
			$smarty->display("chat_win_2.htm");
		}
	
	}
	else
	{
		// show page for logging in
		$smarty->assign("sendersid",$sendersid);
		$smarty->assign("senderusername",$senderusername);
		$smarty->display("chat_win_1.htm");
	}
}
?>
