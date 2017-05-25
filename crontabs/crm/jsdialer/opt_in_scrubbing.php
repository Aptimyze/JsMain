<?php
die('obselete file');
/*********************************************************************************************
* FILE NAME   	: opt_in_scrubbing.php
* DESCRIPTION 	: Re-start profiles who were marked in DNC but opt-in for calls
* MADE DATE 	: 12 May, 2015
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$dialerLogObj =new DialerLog();

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");


function compute_dnc_array($db_dialer,$campaign_name)
{
	$dnc_array = array();
	$squery1 = "SELECT PROFILEID FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE Dial_Status='9'";
        $sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaign_name,$db_dialer,1);
	while($srow1 = mssql_fetch_array($sresult1))
		$dnc_array[] = $srow1["PROFILEID"];
        return $dnc_array;
}

function compute_opt_in_array($db_js,$dnc_array)
{
	$opt_in_profiles = array(); 
        $profileid_str = implode(",",$dnc_array);
        if($profileid_str!='')
        {       
                $sql_vd="select PROFILEID from newjs.CONSENT_DNC WHERE PROFILEID IN ($profileid_str)";
                $res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
                while($row_vd = mysql_fetch_array($res_vd))
                        $opt_in_profiles[] = $row_vd["PROFILEID"];
        }
        return $opt_in_profiles;
}
function compute_eligible_in_array($db_js,$dnc_array,$renewal='')
{
        $eligible_profiles = array();
        $profileid_str = implode(",",$dnc_array);
        if($profileid_str!='')
        {
		if($renewal)
			$table ='incentive.RENEWAL_IN_DIALER';
		else
			$table ='incentive.IN_DIALER';
		$sql_vd="select PROFILEID from ".$table." WHERE PROFILEID IN ($profileid_str) AND ELIGIBLE!='N'";	
                $res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
                while($row_vd = mysql_fetch_array($res_vd))
                        $eligible_profiles[] = $row_vd["PROFILEID"];
        }
        return $eligible_profiles;
}
function start_opt_in_profiles($campaign_name,$opt_in_profile,$db_dialer,$db_js_111)
{
	$squery1 = "SELECT easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$opt_in_profile'";
        $sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaign_name,$db_dialer,1);
        while($srow1 = mssql_fetch_array($sresult1))
        {
                $ecode = $srow1["easycode"];
                $proid = $srow1["PROFILEID"];
		$alloted = $srow1['AGENT'];
		if($ecode!='')
		{
			if($alloted)
				$dialStatus ='2';
			else
				$dialStatus ='1';
			$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status=$dialStatus WHERE easycode='$ecode'";
			mssql_query($query1,$db_dialer) or $dialerLogObj->logError($query1,$campaign_name,$db_dialer,1);

			$updateString ='Dial_Status='.$dialStatus;
			$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$updateString',now(),'OPTIN')";
			mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
                }
        }
}

// MAH_JSNEW OPT-IN Check
$msg= "MAH_JSNEW - Start time:".@date('H:i:s');
$dnc_array = compute_dnc_array($db_dialer,'MAH_JSNEW');
$opt_in_array = compute_opt_in_array($db_js,$dnc_array);
$opt_in_array1 = compute_eligible_in_array($db_js,$opt_in_array);
for($i=0;$i<count($opt_in_array1);$i++)
	start_opt_in_profiles('MAH_JSNEW',$opt_in_array1[$i],$db_dialer,$db_js_111);
unset($dnc_array);
unset($opt_in_array);
unset($opt_in_array1);
$msg.=" End time:".@date('H:i:s')."\n";

// JS_NCRNEW OPT-IN Check
$msg.= "JS_NCRNEW - Start time:".@date('H:i:s');
$dnc_array = compute_dnc_array($db_dialer,'JS_NCRNEW');
$opt_in_array = compute_opt_in_array($db_js,$dnc_array);
$opt_in_array1 = compute_eligible_in_array($db_js,$opt_in_array);
for($i=0;$i<count($opt_in_array1);$i++)
	start_opt_in_profiles('JS_NCRNEW',$opt_in_array1[$i],$db_dialer,$db_js_111);
unset($dnc_array);
unset($opt_in_array);
unset($opt_in_array1);
$msg.=" End time:".@date('H:i:s')."\n";

// Renewal OPT-IN Check
$msg.= "JS_RENEWAL - Start time:".@date('H:i:s');
$dnc_array = compute_dnc_array($db_dialer,'JS_RENEWAL');
$opt_in_array = compute_opt_in_array($db_js,$dnc_array);
$opt_in_array1 = compute_eligible_in_array($db_js,$opt_in_array,'1');
for($i=0;$i<count($opt_in_array1);$i++)
        start_opt_in_profiles('JS_RENEWAL',$opt_in_array1[$i],$db_dialer,$db_js_111);
unset($dnc_array);
unset($opt_in_array);
unset($opt_in_array1);
$msg.=" End time:".@date('H:i:s')."\n";

// Renewal-Mah OPT-IN Check
$msg.= "OB_RENEWAL_MAH - Start time:".@date('H:i:s');
$dnc_array = compute_dnc_array($db_dialer,'OB_RENEWAL_MAH');
$opt_in_array = compute_opt_in_array($db_js,$dnc_array);
$opt_in_array1 = compute_eligible_in_array($db_js,$opt_in_array,'1');
for($i=0;$i<count($opt_in_array1);$i++)
        start_opt_in_profiles('OB_RENEWAL_MAH',$opt_in_array1[$i],$db_dialer,$db_js_111);
unset($dnc_array);
unset($opt_in_array);
unset($opt_in_array1);
$msg.=" End time:".@date('H:i:s')."\n";
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Dialer updates JS_NCRNEW|MAH_JSNEW|JS_RENEWAL|OB_RENEWAL_MAH Campaign OPT-IN done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);

?>
