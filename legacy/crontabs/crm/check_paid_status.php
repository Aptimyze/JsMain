<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$today=date("Y-m-d");
list($yy,$mm,$dd)=explode("-",$today);
//$ts=mktime(0,0,0,$mm,$dd,$yy);
$ts=time();
$ts-=2*24*60*60;
$dbyday=date("Y-m-d",$ts);

//$st_time=$yday." 00:00:00";
//$st_time=$yday." 14:15:00";
//$end_time=$today." 14:14:59";

$sqlj="SELECT USER FROM jsadmin.UPSELL_AGENT";
$resj=mysql_query($sqlj) or die(mysql_error());
while($rowj = mysql_fetch_array($resj))
        $allot_to_array[] = $rowj['USER'];
$upsell_agent=implode("','",$allot_to_array);


$sql="UPDATE billing.PURCHASES,incentive.MAIN_ADMIN SET incentive.MAIN_ADMIN.STATUS='P' WHERE billing.PURCHASES.ENTRY_DT >= '$dbyday' AND billing.PURCHASES.STATUS='DONE' AND billing.PURCHASES.PROFILEID=incentive.MAIN_ADMIN.PROFILEID AND incentive.MAIN_ADMIN.STATUS!='P' AND incentive.MAIN_ADMIN.ALLOTED_TO NOT IN ('$upsell_agent') AND billing.PURCHASES.MEMBERSHIP='Y'";
//$sql="UPDATE billing.PAYMENT_DETAIL,incentive.MAIN_ADMIN SET incentive.MAIN_ADMIN.STATUS='P' WHERE billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_time' AND '$end_time' AND billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.PROFILEID=incentive.MAIN_ADMIN.PROFILEID";

mysql_query($sql) or logError($sql);
$cnt=mysql_affected_rows();

// Upsell profiles marked as paid
$sqlUpsell ="UPDATE billing.PURCHASES p,incentive.MAIN_ADMIN m SET m.STATUS='P' WHERE p.ENTRY_DT >='$dbyday' AND m.ALLOT_TIME<p.ENTRY_DT AND p.STATUS='DONE' AND p.PROFILEID=m.PROFILEID AND m.ALLOTED_TO IN ('$upsell_agent') AND p.MEMBERSHIP='Y'";
mysql_query($sqlUpsell) or logError($sqlUpsell);
$cntUpsell=mysql_affected_rows();
$cnt +=$cntUpsell;

//code added by sriram for storing voice log details (telecallers).

//query to find those, who's center is noida and have IUO in privilage.
$sql_jsadmin = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE CENTER='NOIDA' AND PRIVILAGE LIKE '%IUO%'";
$res_jsadmin = mysql_query($sql_jsadmin) or die($sql_jsadmin.mysql_error());
													     
while($row_jsadmin = mysql_fetch_array($res_jsadmin))
	$allowed_users_arr[] = $row_jsadmin['USERNAME'];
													     
$allowed_users_str = "'".implode("','",$allowed_users_arr)."'";
unset($allowed_users_arr);

//query to find those distinct users who became paid members today.
$sql_purch = "SELECT DISTINCT(PROFILEID) FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$st_time' AND '$end_time'";
$res_purch = mysql_query($sql_purch) or die(mysql_error()) or logError($sql_purch);
while($row_purch = mysql_fetch_array($res_purch))
{
	$profileid = $row_purch['PROFILEID'];

	//finding the entry date of the last billid.
	$sql_entry_dt = "SELECT ENTRY_DT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND ENTRY_DT BETWEEN '$st_time' AND '$end_time' ORDER BY ENTRY_DT DESC LIMIT 1";
	$res_entry_dt = mysql_query($sql_entry_dt) or logError($sql_entry_dt);
	$row_entry_dt = mysql_fetch_array($res_entry_dt);

	$details['ENTRY_DT'] = $row_entry_dt["ENTRY_DT"];

	//finding the call history for a particular user.
	$sql_crm = "SELECT ALLOT_TIME, ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' AND ALLOT_TIME BETWEEN DATE_SUB('$details[ENTRY_DT]',INTERVAL RELAX_DAYS + 30 DAY) AND '$details[ENTRY_DT]' AND ALLOTED_TO IN ($allowed_users_str)";
	$res_crm = mysql_query($sql_crm) or logError($sql_crm);
	if($row_crm = mysql_fetch_array($res_crm))
	//if(mysql_num_rows($res_crm) > 0)
	{
		$details['ALLOTED_TO'] = addslashes(stripslashes($row_crm['ALLOTED_TO']));
		$details['ALLOT_TIME'] = addslashes(stripslashes($row_crm['ALLOT_TIME']));

		//finding the user details.
		$sql_jp = "SELECT USERNAME,STD,PHONE_RES,PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res_jp = mysql_query($sql_jp) or logError($sql_jp);
		$row_jp = mysql_fetch_array($res_jp);

		$details['USERNAME'] = addslashes(stripslashes($row_jp["USERNAME"]));

		if($row_jp["PHONE_MOB"])
		{
			$details['PHONE'] = $row_jp["PHONE_MOB"];

			if(substr($details['PHONE'],0,1)==0)
				$details['PHONE'] = substr($details['PHONE'],1);

			$details['PHONE'] = substr($details['PHONE'],-10);

			if(strlen($details['PHONE']) < 10)
				$details['PHONE'] = "";
		}
		elseif($row_jp["PHONE_RES"])
		{
			$details['PHONE'] = $row_jp["STD"].$row_jp["PHONE_RES"];
			
			if(substr($details['PHONE'],0,1)==0)
				$details['PHONE'] = substr($details['PHONE'],1);

			$details['PHONE'] = substr($details['PHONE'],-10);

			if(strlen($details['PHONE']) < 10)
				$details['PHONE'] = "";
		}

		$details['PHONE'] = addslashes(stripslashes($details['PHONE']));

		//finding the count of call history.
		$sql_hist = "SELECT COUNT(*) AS COUNT FROM incentive.HISTORY WHERE PROFILEID = '$profileid' AND ENTRY_DT BETWEEN '$details[ALLOT_TIME]' AND '$details[ENTRY_DT]'";
		$res_hist = mysql_query($sql_hist) or logError($sql_hist);
		$row_hist = mysql_fetch_array($res_hist);
		$details['COUNT'] = addslashes(stripslashes($row_hist['COUNT']));

		if($details["PHONE"] && $details["COUNT"] > 0)
		{
			//storing the values.
			$sql_ins = "INSERT INTO incentive.CRM_VOICE_LOG (PROFILEID,USERNAME,PHONE,ALLOTED_TO,ENTRY_DT,ALLOT_TIME,COUNT) VALUES('$profileid','$details[USERNAME]','$details[PHONE]','$details[ALLOTED_TO]','$details[ENTRY_DT]','$details[ALLOT_TIME]','$details[COUNT]')";
			mysql_query($sql_ins) or logError($sql_ins);
		}
	}
	unset($profileid);
	unset($details);
}

$mailmsg="Total Paid : $cnt # $cntUpsell";
mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com","Check Paid Status",$mailmsg);
?>
