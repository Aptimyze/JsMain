<?
include('connectmb.inc');
$db=connect_dbmb();
$mbdata=authenticatedmb($mbchecksum);
if($mbdata)
{
	$profileid=$mbdata["PROFILEID"];
	$source=$mbdata["SOURCE"];
	mysql_select_db_js('newjs');
	$data=login_every_user($againstprofileid);
	mysql_select_db_js('marriage_bureau');
	$sql="select BUREAU_PROFILEID FROM marriage_bureau.VIEWED where VIEWED_PROFILE='$pid' AND BUREAU_PROFILEID='$profileid' AND AGAINST_PROFILE='$againstprofileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$newamount=0;
	if($row=mysql_fetch_array($result))
	{
		$alreadyseen=1;
		$sql="select MONEY from marriage_bureau.BUREAU_PROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($result))
			$newamount=$row['MONEY'];
	}
	else
	{		
		//make monetory changes in his account
		$alreadyseen=0;
	
		$sql="select CPP,MONEY from marriage_bureau.BUREAU_PROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($result);
		if($row['CPP']<=$row['MONEY'])
		{
			include_once('../profile/contact.inc');
			$nikhil_marriage_bureau=1;
			include_once('../profile/connect.inc');
                        $custmessage="";
                        mysql_select_db_js('newjs');
			$mbureau=1;
			assign_template_pathprofile();
                        send_response($againstprofileid,$pid,'A',$custmessage,'N','Y',0);
			assign_template_pathmb();
                        mysql_select_db_js('marriage_bureau');

			$newamount=$row['MONEY']-$row['CPP'];
			$amount_charged=$row['CPP'];
			$sql="INSERT INTO marriage_bureau.VIEWED(BUREAU_PROFILEID,VIEWED_PROFILE,AGAINST_PROFILE,DAYZ) VALUES('$profileid','$pid','$againstprofileid',now())";
                        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$sql="UPDATE marriage_bureau.BUREAU_PROFILE set MONEY='$newamount' WHERE PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		else
		{
			echo "sorry you do not have enoungh money in your account";
			exit;	
		}
	}
	showdetails($pid,$againstprofileid);
	$smarty->assign('amount_charged',$amount_charged);
	$smarty->assign('alreadyseen',$alreadyseen);
	$smarty->assign('newamount',$newamount);
	$smarty->display('contact_details_popup.htm');	
}
else
{
	timeoutmb();
}
function showdetails($profileid,$againstprofileid)
{
	global $smarty;
	mysql_select_db_js('newjs');
	$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,PARENTS_CONTACT,SHOW_PARENTS_CONTACT from JPROFILE where PROFILEID='$profileid'";
	$emailresult=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$emailrow=mysql_fetch_array($emailresult);
	$email=nl2br($emailrow["EMAIL"]);
	$address=nl2br($emailrow["CONTACT"]);
	$CONTACTDETAILS=1;
	if(trim($email)!="")
	{
		$smarty->assign("HISEMAIL",$emailrow["EMAIL"]);
	}
	else
	{
		$smarty->assign("BLANKEMAIL","1");
	}
											 
	if($emailrow["SHOWPHONE_RES"]=="Y" && $emailrow["PHONE_RES"]!="")
		$phone=$emailrow["PHONE_RES"];
											 
	if($emailrow["SHOWPHONE_MOB"]=="Y" && $emailrow["PHONE_MOB"]!="")
	{
		if(trim($phone)=="")
			$phone=$emailrow["PHONE_MOB"];
		else
			$phone.=", " . $emailrow["PHONE_MOB"];
	}
											 
	if(trim($phone)!="")
	{
		$smarty->assign("PHONE",trim($phone));
	}
	else
		$smarty->assign("BLANKPHONE","1");
											 
	if($emailrow["CONTACT"]!="" && $emailrow["SHOWADDRESS"]=="Y")
	{
		$smarty->assign("ADDRESS",nl2br($emailrow["CONTACT"]));
	}
	else
                $smarty->assign("BLANKADDRESS","1");

											 
	if($emailrow["PARENTS_CONTACT"]!="" && $emailrow["SHOW_PARENTS_CONTACT"]=="Y")
	{
		 $smarty->assign("PARENTS_ADDRESS",nl2br($emailrow["PARENTS_CONTACT"]));
	}
	else
		$smarty->assign("BLANKPARENTADDRESS","1");
											 
	if($emailrow["SHOWMESSENGER"]=="Y")
	{
		$mymessenger=$emailrow["MESSENGER_CHANNEL"];
		if($MESSENGER_CHANNEL["$mymessenger"])
		{
			$smarty->assign("MESSENGER_CHANNEL",$MESSENGER_CHANNEL["$mymessenger"]);
			$smarty->assign("MESSENGER_ID",$emailrow["MESSENGER_ID"]);
		}
		else
			$smarty->assign("BLANKMESSENGER","1");
	}
	else
		$smarty->assign("BLANKMESSENGER","1");
	//Code modified by Sadaf on 10 Jul 2007 to pick ID from MESSAGE_LOG and corresponding message from MESSAGES

	$mysql=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid);
	$myDb=$mysql->connect_db($myDbname);

	$sql="SELECT ID FROM newjs.MESSAGE_LOG WHERE RECEIVER='$againstprofileid' AND SENDER='$profileid' AND FOLDERID=0 AND RECEIVER_STATUS='U' AND OBSCENE='N' AND IS_MSG='Y'";
        $result=$mysql->ExecuteQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_array($result))
	{
		$id=$row["ID"];		
		$sqlmsg="SELECT MESSAGE FROM newjs.MESSAGES WHERE ID='$id'";
		$resultmsg=$mysql->ExecuteQuery($sqlmsg,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlmsg,"ShowErrTemplate");
		$rowmsg=mysql_fetch_array($resultmsg);
		$message=$rowmsg['MESSAGE'];	
		$smarty->assign("MESSAGE",$message);
	}
	else
		$smarty->assign("MESSAGE","");
	//End of code modified by sadaf
        mysql_free_result($emailresult);
	mysql_select_db_js('marriage_bureau');
}	
?>
