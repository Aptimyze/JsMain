<?php
/**
*       Filename        :       outbound.php
*       Created by      :       Shiv
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");

if (authenticated($cid))
{
	$now=time();
	$now+=60*60;
	$today=date("Y-m-d",$now)." 23:59:59";
	$date_after_30days = date('Y-m-d', time()+30*86400);
        $name= getname($cid);

	$ocnt=0;
	$ofcnt=0;

	//Follow up profiles for the day	
        //$sql="SELECT COUNT(ma.PROFILEID) AS nfcnt FROM incentive.MAIN_ADMIN AS ma JOIN newjs.JPROFILE AS ss ON ss.PROFILEID = ma.PROFILEID WHERE ma.ALLOTED_TO='$name' AND ma.STATUS='F' AND ma.ORDERS='' AND ss.SUBSCRIPTION='' AND ma.FOLLOWUP_TIME<='$today'";
	$sql="SELECT COUNT(ma.PROFILEID) AS nfcnt FROM incentive.MAIN_ADMIN AS ma JOIN newjs.JPROFILE AS ss ON ss.PROFILEID = ma.PROFILEID WHERE ma.ALLOTED_TO='$name' AND ma.STATUS='F' AND ma.ORDERS='' AND ma.FOLLOWUP_TIME<='$today'";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
	$nfcnt=$myrow['nfcnt'];

		
        $sql =" SELECT COUNT(*) as nnfcnt from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='F' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND ALLOT_TIME>='2006-01-25'";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
	$nnfcnt=$myrow['nnfcnt'];

	// New Profiles for the day
	$sql =" SELECT COUNT(*) as ncnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO='$name' AND STATUS='N' AND HANDLED='N'";
	$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$myrow = mysql_fetch_array($result);
	$ncnt = $myrow['ncnt'];

	// Subscribed Profiles expiring in next 30 days
	$sql ="SELECT PROFILEID FROM incentive.SUBSCRIPTION_EXPIRY_PROFILES WHERE ALLOTED_TO='$name' AND HANDLED = 'N'";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($result))
	{
		$pid_sub =$row['PROFILEID'];
		$sqlC =" SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='F' AND PROFILEID='$pid_sub' AND FOLLOWUP_TIME<='$today'";
		$resultC= mysql_query_decide($sqlC) or die(mysql_error_js());
		$rowC=mysql_fetch_array($resultC);
		if(!$rowC['PROFILEID'])
			$pidarr[]=$row['PROFILEID'];
	}
	$scnt =count($pidarr); 

	//Previously Handled Profiles 
	$sql ="SELECT COUNT(DISTINCT  ma.PROFILEID) AS claimcnt FROM incentive.MAIN_ADMIN AS ma JOIN newjs.JPROFILE AS ss ON ss.PROFILEID = ma.PROFILEID WHERE ma.ALLOTED_TO='$name' AND ss.SUBSCRIPTION='' AND (ma.STATUS='C' OR (ma.STATUS='F' AND ma.FOLLOWUP_TIME>'$today') OR ma.STATUS='P')";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $claimcnt=$myrow['claimcnt'];

	//Unalloted Renewal Profiles	
	$sql =" SELECT COUNT(*) as rencnt from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='R'";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $rencnt=$myrow['rencnt'];
	
	// Paid Profiles Not Due for Renewal Yet	 
	$today_start=date("Y-m-d",$now)." 00:00:00";
	$sql =" SELECT distinct PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS!='F' UNION SELECT distinct PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='F' AND FOLLOWUP_TIME>'$today'";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($result))
	{       //if($row['FOLLOWUP_TIME'] < $today_start || $row['FOLLOWUP_TIME'] > $today)
                                $pidarr[]=$row['PROFILEID'];
	}
	if(count($pidarr)>0){
		$pidarr =array_unique($pidarr);
		$str=implode("','",$pidarr);
        	$sql1= "SELECT COUNT(PROFILEID) as apcnt FROM billing.SERVICE_STATUS WHERE PROFILEID IN ('$str') AND EXPIRY_DT >='$date_after_30days' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%'";
        	$result1= mysql_query_decide($sql1) or die(mysql_error_js());
        	$myrow=mysql_fetch_array($result1);
		$apcnt=$myrow['apcnt'];
		//query end	
	}

	//Unalloted Paid Profiles
	$sql =" SELECT COUNT(*) as pcnt from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='U'";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $pcnt=$myrow['pcnt'];

	$branch = getcenter_for_walkin($name);
	if ($branch == 'NOIDA')
		$ncr = 'Y';

	//code added by sriram to show links conditionally depending on inbound/outbound users.
	$privilage = explode("+",getprivilage($cid));
        if(in_array("EXCPRM",$privilage))
                $ncr = 'N';
	if((in_array("IUO",$privilage) && in_array("PRALL",$privilage)) || (in_array("EXCPRM",$privilage)) || (in_array("ExcWFH",$privilage)))
	{
		$smarty->assign("SHOW_LINKS","Y");
	}
	elseif(in_array("IUI",$privilage) && $branch == "NOIDA")
	{
		$smarty->assign("SHOW_LINKS","N");
	}
	
	//code added by sriram to show links conditionally depending on inbound/outbound users.
	$smarty->assign("ncr",$ncr);
        $smarty->assign("ocnt",$ocnt);
        $smarty->assign("ofcnt",$ofcnt);
        $smarty->assign("nfcnt",$nfcnt);
        $smarty->assign("nnfcnt",$nnfcnt);
        $smarty->assign("apcnt",$apcnt);
	$smarty->assign("ncnt",$ncnt);
        $smarty->assign("scnt",$scnt);
	$smarty->assign("claimcnt",$claimcnt);
	$smarty->assign("rencnt",$rencnt);
	$smarty->assign("pcnt",$pcnt);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("outbound.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}                                                                                                
?>
