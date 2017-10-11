<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include_once("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
	include_once("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");
	include_once("$_SERVER[DOCUMENT_ROOT]/profile/functions.inc");

	//15 days before
	$ts = time();
	$ts -= 24*60*60*15;
	$before_15_days = date("Y-m-d",$ts);
	$start_dt = $before_15_days." 00:00:00";
	$end_dt = $before_15_days." 23:59:59";

	$sql = "SELECT PROFILEID FROM incentive.WELCOME_CALLS WHERE CALL_DATE BETWEEN '$start_dt' AND '$end_dt'";
	$res = mysql_query($sql) or mail_me($sql);
	while($row = mysql_fetch_array($res))
		$profileid_arr[] = $row['PROFILEID'];

	if(is_array($profileid_arr))
		$profileid_str = implode(",",$profileid_arr);

	unset($profileid_arr);

	if($profileid_str != "")
	{
		$i=0;
		$sql_part = "SELECT PROFILEID, DATE FROM newjs.JPARTNER WHERE PROFILEID IN ($profileid_str)";
		$res_part = mysql_query($sql_part) or mail_me($sql_part);
		while($row_part = mysql_fetch_array($res_part))
		{
			$jp_date[$i]['PROFILEID'] = $row_part['PROFILEID'];
			$jp_date[$i]['DATE'] = $row_part['DATE'];
			$i++;
		}
	}

	$call_date = explode("-",$before_15_days);
	$call_date_ts = mktime(0,0,0,$call_date[1],$call_date[0],$call_date[2]);

	for($i=0; $i<count($jp_date); $i++)
	{
		$profileid = $jp_date[$i]['PROFILEID'];

		$jp_mod_date = explode("-", $jp_date[$i]['DATE']);
		$jp_mod_date_ts = mktime(0,0,0,$jp_mod_date[1],$jp_mod_date[0],$jp_mod_date[2]);

		$profile_percent = profile_percent($profileid);

		$sql_upd = "UPDATE incentive.WELCOME_CALLS SET PROFILE_PERCENT_FIN = '$profile_percent'";

		if($jp_mod_date_ts > $call_date_ts)
			$sql_upd .= ", JPARTNER_EDITED = 'Y'";

		$sql_upd .= " WHERE PROFILEID='$profileid'";

		mysql_query($sql_upd) or mail_me($sql_upd);
	}

	$msg = "WELCOME_CALL table updated successfully for following profileid's $profileid_str .";

	mail("sriram.viswanathan@jeevansathi.com","Welcome call update list", $msg);

	function mail_me($sql)
	{
		mail("sriram.viswanathan@jeevansathi.com","Error : welcome_call_marking.php",$sql.mysql_error());
		exit;
	}
?>
