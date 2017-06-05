<?php
/*************************************************************************************
Description : Interface to change the logic of sending the match alerts.
Developed By: Vibhor Garg
Date	    : 05-02-2008
*************************************************************************************/
include("connect.inc");
if(authenticated($cid))
{
	if($Go)
	{
		$db=connect_slave();
		if($phrase=='U')
		{
			$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED , VERIFY_EMAIL from newjs.JPROFILE where ";

                        if(is_numeric($username))
                                $sql.= "PROFILEID='$username'";
                        else
                                $sql.= "USERNAME='$username'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row = mysql_fetch_array($result);
			$Profileid = $row["PROFILEID"];
			if(!$row)
			{
				$sql="SELECT PROFILEID FROM newjs.CUSTOMISED_USERNAME WHERE OLD_USERNAME='$username'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED , VERIFY_EMAIL from newjs.JPROFILE where PROFILEID='$row[PROFILEID]'";
					$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());					
					$smarty->assign("SEARCH","YES");
				}
				else 	
					$smarty->assign("SEARCH","NO");				
			}
			else 
			{			
				$smarty->assign("SEARCH","YES");
			}
		}
		else
		{
			$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED , VERIFY_EMAIL from newjs.JPROFILE where EMAIL='$username'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row = mysql_fetch_array($result);
			$Profileid = $row["PROFILEID"];
			if(!$row)
			{
				$smarty->assign("SEARCH","YES");				
			}	
			else 
				$smarty->assign("SEARCH","NO");
		}	
		$sql="SELECT LOGIC_STATUS from newjs.MATCH_LOGIC where PROFILEID = '$Profileid'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row = mysql_fetch_array($result);
		$algo_status = $row["LOGIC_STATUS"];
		if($algo_status == "")
		{
			/*$sql="SELECT LOGIC_USED from alerts.MAILER where RECEIVER = '$Profileid'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row = mysql_fetch_array($result);
			$algo_used = $row["LOGIC_USED"];
			if($algo_used != "")
			{
				if($algo_used == '3')*/
					$algo_status = 'N';
				/*else 
					$algo_status = 'O';
			}
			else 
			{
				$sql="SELECT COUNT(*) as COUNT from newjs.CONTACTS where SENDER = '$Profileid' AND ((TYPE='A')||(TYPE='I'))";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row = mysql_fetch_array($result);
				$count = $row["COUNT"];
				if($count > 9)
					$algo_status = 'N';
				else 
					$algo_status = 'O';			
			}	*/
		}		
		$msg="Please enter the valid Username/Email.....";
		$smarty->assign("algo_status",$algo_status);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$Profileid);
		$smarty->assign("message",$msg);	
		$smarty->display("switch_match_alert_algo.htm");
	}
	else
	{
		$year=date('Y');
		$month=date('m');
		$day=date('d');
		if($save)
		{
			$sql="SELECT COUNT(*) as CNT from newjs.MATCH_LOGIC where PROFILEID = '$Profileid'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row = mysql_fetch_array($result);
			$cnt = $row["CNT"];
			$today=date("Y-m-d");
			if($cnt>0)
			{
				$sql="UPDATE newjs.MATCH_LOGIC SET LOGIC_STATUS ='$algo_new_status',MOD_DT='$today' where PROFILEID = '$Profileid'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
			else 
			{
				$sql="INSERT into newjs.MATCH_LOGIC (PROFILEID,LOGIC_STATUS,MOD_DT) VALUES('$Profileid','$algo_new_status','$today')";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
			$smarty->assign("SEARCH","NO");
			$msg="Your match alert new logic is saved/updated.....";			
		}	
		mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","SWITCH ALGO UPDATED ",$sql);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$Profileid);
		$smarty->assign("message",$msg);	
		$smarty->display("switch_match_alert_algo.htm");
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
