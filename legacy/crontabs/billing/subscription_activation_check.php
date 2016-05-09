<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

die();
	/******************************************************************************************************************
	This script is used to check for those user's for whom billing has been done but something has gone wrong.
	i.e: There is entry in PURCHASES table but not in PAYMENT_DETAIL or there is entry in PURCHASES and PAYMENT_DETAIL
	but not in SERVICE_STATUS table or there is entry in PURCHASES, PAYMENT_DETAIL and SERVICE_STATUS table but
	JPROFILE has not been updated.
	******************************************************************************************************************/
	include("$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc");

	$start_time = date("Y-m-d G:i:s");

	$ts = time();
	$ts -= 24*60*60;
	$date = date("Y-m-d",$ts);
	$start_date = $date." 00:00:00";
	$end_date = $date." 23:59:59";

	$sql_purch = "SELECT BILLID,PROFILEID FROM billing.PURCHASES WHERE STATUS = 'DONE' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
	$res_purch = mysql_query($sql_purch) or mail_me($sql_purch);

	$i = 0;
	while($row_purch = mysql_fetch_array($res_purch))
	{
		$purch[$i]["BILLID"] = $row_purch['BILLID'];
		$purch[$i]["PROFILEID"] = $row_purch['PROFILEID'];
		$i++;
	}

	for($i=0; $i<count($purch); $i++)
	{
		$sql_pd = "SELECT PROFILEID,BILLID FROM billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND BILLID='".$purch[$i]["BILLID"]."' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
		$res_pd = mysql_query($sql_pd) or mail_me($sql_pd);
		if($row_pd = mysql_fetch_array($res_pd))
		{
			$purch_pd[$i]["BILLID"] = $row_pd['BILLID'];
			$purch_pd[$i]["PROFILEID"] = $row_pd['PROFILEID'];
		}
		else
		{
			$purch_not_pd[$i]["BILLID"] = $purch[$i]["BILLID"];
			$purch_not_pd[$i]["PROFILEID"] = $purch[$i]["PROFILEID"];
		}
	}

	unset($purch);
	@sort($purch_pd);

	for($i=0; $i<count($purch_pd); $i++)
	{
		$sql_ss = "SELECT BILLID,PROFILEID FROM billing.SERVICE_STATUS WHERE BILLID='".$purch_pd[$i]["BILLID"]."'";
		$res_ss = mysql_query($sql_ss) or mail_me($sql_ss);
		if($row_ss = mysql_fetch_array($res_ss))
			$purch_pd_ss[] = $row_ss['PROFILEID'];
		else
		{
			$purch_pd_not_ss[$i]["BILLID"] = $purch_pd[$i]['BILLID'];
			$purch_pd_not_ss[$i]["PROFILEID"] = $purch_pd[$i]['PROFILEID'];
		}
	}

	unset($purch_pd);
	@sort($purch_pd_ss);

	if(is_array($purch_pd_ss))
	{
		for($i=0; $i<count($purch_pd_ss); $i++)
		{
			$sql_jp = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PROFILEID = '".$purch_pd_ss[$i]."' AND SUBSCRIPTION=''";
			$res_jp = mysql_query($sql_jp) or mail_me($sql_jp);
			if($row_jp = mysql_fetch_array($res_jp))
			{
				$purch_pd_ss_not_jp[$i]["PROFILEID"] = $row_jp['PROFILEID'];
				$purch_pd_ss_not_jp[$i]["USERNAME"] = $row_jp['USERNAME'];
			}
		}
	}

	unset($purch_pd_ss);

	unset($msg);

	@sort($purch_not_pd);
        @sort($purch_pd_not_ss);
        @sort($purch_pd_ss_not_jp);

	if(is_array($purch_not_pd))
	{
		$msg .= "\nList of user's for whom entry exist in PURCHASES table but not in PAYMENT_DETAIL table.\n";

		$msg .= "BILLID     PROFILEID\n";
		for($i=0; $i<count($purch_not_pd); $i++)
			$msg .= $purch_not_pd[$i]["BILLID"]."     ".$purch_not_pd[$i]["PROFILEID"]."\n";
	}
	else
		$msg .= "\nThere is no user for whom entry exist in PURCHASES table but not in PAYMENT_DETAIL table.\n";

	if(is_array($purch_pd_not_ss))
	{
		$msg .= "\nList of user's for whom entry exist in PURCHASES and PAYMENT_DETAIL tables but not in SERVICE_STATUS table.\n";
		$msg .= "BILLID     PROFILEID\n";
		for($i=0; $i<count($purch_pd_not_ss); $i++)
			$msg .= $purch_pd_not_ss[$i]["BILLID"]."     ".$purch_pd_not_ss[$i]["PROFILEID"]."\n";
	}
	else
		$msg .= "\nThere is no user for whom entry exist in PURCHASES and PAYMENT_DETAIL tables but not in SERVICE_STATUS table.\n";

	if(is_array($purch_pd_ss_not_jp))
	{
		$msg .= "\nList of user's for whom entry exist in PURCHASES, PAYMENT_DETAIL and SERVICE_STATUS tables but JPROFILE has not been updated.\n";
		$msg .= "PROFILEID     USERNAME\n";
		for($i=0; $i<count($purch_pd_ss_not_jp); $i++)
			$msg .= $purch_pd_ss_not_jp[$i]["PROFILEID"]."     ".$purch_pd_ss_not_jp[$i]["USERNAME"]."\n";
	}
	else
		$msg .= "\nThere is no user for whom entry exist in PURCHASES, PAYMENT_DETAIL and SERVICE_STATUS tables but JPROFILE has not been updated.";

	$end_time = date("Y-m-d G:i:s");

	$msg .= "\nStart Time: ".$start_time;
	$msg .= "\nEnd Time: ".$end_time;

	mail("sriram.viswanathan@jeevansathi.com, aman.sharma@jeevansathi.com","Subscription activation check",nl2br($msg));

	function mail_me($sql)
	{
		mail("sriram.viswanathan@jeevansathi.com","Error in subscription_activation_check.php",$sql.mysql_error());
		exit;
	}
?>
