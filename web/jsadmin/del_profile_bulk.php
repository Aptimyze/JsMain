<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it
include_once(JsConstants::$docRoot."/crm/func_sky.php");
include_once(JsConstants::$docRoot."/jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
global $cnt;

$data=authenticated($cid);
if($data)
{
$name=getname($cid);
$db=connect_ddl();
if($send)
{
	$cnt++;
        $subject=$SUB;
        $from="webmaster@jeevansathi.com";
        $Cc=$CC;
        $Bcc=$BCC;
        $msg=nl2br($BODY);
	$subs= explode(",",$type);
	$len= count($subs);
	if($subs[0]=="Paid")
	{
		$profiles=array();
		$profile=explode(",",$profiles);
		$k=1;
		while($k<=$len)
		{
			$pid= $profile[$k];
			$sql1="SELECT WALKIN FROM billing.PURCHASES WHERE PROFILEID= '$pid' AND STATUS= 'DONE' ORDER BY BILLID";
			$res1= mysql_query_decide($sql1) or die(mysql_error_js());
			$row1= mysql_fetch_array($res1);
			$walkin=$row1['WALKIN'];

			$sql2="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME= '$walkin'";
			$res2=mysql_query_decide($sql2) or die(mysql_error_js());
			if(mysql_num_rows($res2)>0)
			{
				$row2= mysql_fetch_assoc($res2);
				$Cc= $row2['EMAIL'];	
			}
			$Cc="anamika.singh@jeevansathi.com,".$Cc;
			$Cc= "";
			$email=$subs[$k];
			$email1=$email;
			send_mail($email1,$Cc,$Bcc,$msg,$subject,$from);
			$sql= "delete from jsadmin.T_EMAILS where EMAIL= '$email' ";
			$res= mysql_query_decide($sql) or die (mysql_error_js());
			$k++;
		}
	}
	else
	{
		for($j=1;$j<=$len;$j++)
		{
			$email=$subs[$j];
                        $email1=$email;

			$sql= "delete from jsadmin.T_EMAILS where EMAIL= '$email' ";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());
		        send_mail($email1,$Cc,$Bcc,$msg,$subject,$from);
		}
	}
	
	$msg=$subs[0]." Profiles are mailed.<br>";
	$smarty->assign("cnt",$cnt);
        $smarty->assign("PAID",$PAID);
        $smarty->assign("FREE",$FREE);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
	$sql_t="SELECT COUNT(*) AS CNT FROM jsadmin.T_EMAILS";
	$res_t= mysql_query_decide($sql_t) or die(mysql_error_js());
	$row= mysql_fetch_array($res_t);
	if($row['CNT']==0)
	{
		$sql= "DROP TABLE jsadmin.T_EMAILS";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		 $smarty->display("jsadmin_msg.tpl");
	}
	else
	        $smarty->display("mail_marked.htm");
	
}
elseif($confirm)
{
	$pid= array();
	$pid= explode(',',$profiles);
//print_r($pid);
	$reason;
	$j=0;
	$date= date("Y-m-d H:i:s");
	$len=count($pid);
	if($reason=='I')
	{
		$jprofileObj =JProfileUpdateLib::getInstance();
		while($j<$len)
		{
			$profile=$pid[$j];
			/*$sql2="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID=$profile";
	        	mysql_query_decide($sql2) or die(logError($sql2,$db));*/
			$extraStr ="PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0";
			$jprofileObj->updateJProfileForBilling('',$profile,'PROFILEID',$extraStr);

	       		$tm = date("Y-M-d");
	        	$sql1= "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID=$profile";
	        	$res1=mysql_query_decide($sql1) or die(logError($sql1,$db));
	        	$row1=mysql_fetch_assoc($res1);
	        	$username=$row1['USERNAME'];
	        	$sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($profile,'$username','Immediate deletion','$comments','$name','$tm')";
	        	mysql_query_decide($sql) or die(logError($sql,$db));
	        	$producerObj=new Producer();
	        	if($producerObj->getRabbitMQServerConnected())
				{
					$sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$profile)), 'redeliveryCount'=>0 );
					$producerObj->sendMessage($sendMailData);
					$sendMailData = array('process' =>'USER_DELETE','data' => ($profile), 'redeliveryCount'=>0 );
					$producerObj->sendMessage($sendMailData);
				}
				else
				{
					$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profile > /dev/null &";
	        		$cmd = JsConstants::$php5path." -q ".$path;
	        		passthru($cmd);
				}
	        	
			$j++;
		}
		$msg="Selected Profiles are deleted Immediately.<br>";
                //$msg.="<a href=\"mainpage.php?cid=$cid&name=$name\">Continue</a>";
                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");

	}
	elseif($reason=='A')
	{
		while($j<$len)
		{
			$profile=$pid[$j];
			$sql= "INSERT INTO jsadmin.MARK_DELETE(PROFILEID, STATUS, M_DATE, DATE, REASON, COMMENTS, ENTRY_BY) VALUES('$profile','M','$date','$date','abuse','$comments','$name')";
			$res= mysql_query_decide($sql) or die(mysql_error_js());
			
			$sql1="SELECT EMAIL from newjs.JPROFILE WHERE PROFILEID='$profile' ";
                        $res1=mysql_query_decide($sql1) or logError($sql);
                        $row= mysql_fetch_array($res1);
                        $email= $row['EMAIL'];
			$subject="Your Profile is marked for Deletion";
                        $from="abuse@jeevansathi.com";
                        $Cc="";
                        $Bcc="";
                        $msg="We found that your profile was violating our terms and conditions creating inconvenience to other users, because of which we have decided to delete your profile. (For paid profiles alone: You are not eligible for any refunds as mentioned in clause 13 of our terms and conditions). Should you have any questions, please contact the people copied on the mail";                                        
			send_mail($email,$Cc,$Bcc,$msg,$subject,$from);
			$j++;
		}
		$msg="Selected Profiles are marked for deletion.<br>";
	        $msg.="<a href=\"searchpage.php?cid=$cid&name=$name\">Search Page</a>";
	        $smarty->assign("name",$name);
	        $smarty->assign("cid",$cid);
	        $smarty->assign("MSG",$msg);
	        $smarty->display("jsadmin_msg.tpl");
	}
	elseif($reason=='P')
	{
		while($j<$len)
                {
                        $profile=$pid[$j];
                        $sql= "INSERT INTO jsadmin.MARK_DELETE(PROFILEID, STATUS, M_DATE, DATE, REASON, COMMENTS, ENTRY_BY) VALUES('$profile','M','$date','$date','policy','$comments','$name') ";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());

			$sql_info= "SELECT SUBSCRIPTION, EMAIL, PROFILEID FROM newjs.JPROFILE WHERE PROFILEID= '$profile' ";
			$res_info= mysql_query_decide($sql_info) or die(mysql_error_js());
			$row_info= mysql_fetch_assoc($res_info);
			$paid=$row_info['SUBSCRIPTION'];
			$email= $row_info['EMAIL'];
			if($email!='')
			{
				if($paid!='')
					$PAID=$PAID.",".$email;
				else
					$FREE= $FREE.",".$email;
			}				
			$j++;
                }
		if(substr($profiles,-1)==',')
			$profiles_n= substr($profiles,0,-1);
		elseif(substr($profiles,0,1)==',')
			$profiles_n=substr($profiles,1);
		else
			$profiles_n=$profiles;
		$sql1="INSERT INTO jsadmin.T_EMAILS (EMAIL) SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID IN ($profiles_n) ";
                $res1= mysql_query_decide($sql1);
		if(!$res1)
		{
			$sql2= "CREATE TABLE jsadmin.T_EMAILS (EMAIL varchar(100))";
			$res2= mysql_query_decide($sql2,$db) or die(mysql_error_js());
			$sql1="INSERT INTO jsadmin.T_EMAILS (EMAIL) SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID IN ($profiles_n) ";
			$res1= mysql_query_decide($sql1) or die(mysql_error_js());	
		}
		$msg.="Selected Profiles are marked for deletion.<br>";
		$smarty->assign("PAID",$PAID);
		$smarty->assign("FREE",$FREE);
		$smarty->assign("profiles",$profiles);
	        $smarty->assign("name",$name);
	        $smarty->assign("cid",$cid);
	        $smarty->assign("MSG",$msg);
	        $smarty->display("mail_marked.htm");
	}
	else
	{
		$c=1; $send_mail=1;$reasons=$reason;$pid=$profiles;$flag_search=1;$flag_deletion = 1;
		$sql= "INSERT INTO jsadmin.MARK_DELETE(PROFILEID, STATUS, M_DATE, DATE, REASON, COMMENTS, ENTRY_BY) VALUES('$pid','M','$date','$date','$reason','$comments','$name')";
                $res= mysql_query_decide($sql) or die(mysql_error_js());
		include("deletepage.php");
		exit;

	}
}
elseif($cancel)
{
	$msg="Go back to main page <br>";
        $msg .="<a href=\"mainpage.php\">";
        $msg .="Click here </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
elseif($Delete=='Delete')
{
	$smarty->assign("c",1);
	$smarty->assign("count",$count);
	$smarty->assign("submit","Y");
	$smarty->assign("cid",$cid);
        $smarty->assign("name",$name);
        $smarty->display("del_profile_bulk.htm");

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

?>
