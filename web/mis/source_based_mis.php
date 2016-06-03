<?php
ini_set("max_execution_time",0);
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db = connect_misdb();

$sourcearr=array('google', 'overture', 'Rediff_tgt', 'Yahoo_Tgt' ,'Google_NRI','MSN_group','rediff_pilot_july');
//$sourcearr=array('google', 'overture', 'Rediff_tgt');

$ts=time();
$ts-=3*24*60*60;
$today=date("Y-m-d" , $ts);
//$today = '2006-07-30';
$start_dt = $today;

list($ddate_yyyy,$ddate_mon,$d)=explode("-",$today);
//$start_dt = $ddate_yyyy."-".$ddate_mon."-"."01";
/***************************************************************
	To find the total number of clicks
**************************************************************/
$srcnt = count($sourcearr);

for ($i=0;$i < $srcnt;$i++)
{
	unset($header);
	unset($data);

	$header="Day".","."clicks".","."Total Profiles".","."conv".","."Activated profiles".","."Activated profiles %".","."inactivated profiles".","."paid".","."Male>25".","."Female".","."Delhi".","."Maharashtra".","."Mumbai".","."Bangalore".","."NRI".","."irrelavant profiles(age wise)".","."irrelavant profiles(age wise %)".","."irrelavant profiles(location wise)".","."irrelavant profiles(location wise)%"."\n";

	$filename = "/usr/local/indicators/".$sourcearr[$i]."_".$ddate_mon."-".$ddate_yyyy.".csv";
        //$filename = "/usr/local/apache/sites/jeevansathi.com/htdocs/crm/indicators/".$sourcearr[$i]."_".$ddate_mon."-".$ddate_yyyy.".csv";

	if (!file_exists($filename))
	{
		$fp = fopen($filename,"w+");
        	if(!$fp)
        	{
                	die("no file pointer");
        	}
		fwrite($fp,$header);
	}
	else
	{
		$fp = fopen($filename,"a");
        	if(!$fp)
                	die("no file pointer");
	}

	// to find clicks
	$sql = "SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND SOURCEGP ='$sourcearr[$i]'";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_array($res);
	{
		$CLICKS = $row['CNT'];
	}

	$ACTIVATED = 0;
	$INACTIVATED = 0;
	$TOTALPROFILES = 0;

	$sql = "SELECT SUM(COUNT) AS CNT , ACTIVATED , SUBSCRIPTION FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND SOURCEGP ='$sourcearr[$i]' AND ENTRY_MODIFY='E' AND INCOMPLETE='N' GROUP BY ACTIVATED,SUBSCRIPTION ";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
		if ($row['ACTIVATED'] == 'Y' || $row['ACTIVATED'] == 'H')
			$ACTIVATED += $row['CNT'];
		else
			$INACTIVATED += $row['CNT'];

		if ($row['SUBSCRIPTION'] <> '')
			$PAID += $row['CNT'];

		$TOTALPROFILES += $row['CNT'];
        }
	if ($CLICKS)
                $CONVERSION = round(($TOTALPROFILES/$CLICKS)*100,2);     // conversion
	if ($TOTALPROFILES)
		$ACTIVE_PER = round(($ACTIVATED/$TOTALPROFILES)*100,2);


	$sql = "SELECT SUM(COUNT) AS CNT , AGE , GENDER FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND SOURCEGP ='$sourcearr[$i]' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND ENTRY_MODIFY='E' GROUP BY GENDER,AGE";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
		$gender=$row['GENDER'];
		$age=$row['AGE'];
		if($gender=='M')
                {
			if($age>=25)
				$MALE_AGE_COUNT += $row['CNT'];
			else
				$IRREV_MALE_PROFILES+=$row['CNT'];
		}
		else
			$FEMALECOUNT += $row['CNT'];
		
        }


	$sql = "SELECT SUM(COUNT) AS CNT , CITY_RES FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND SOURCEGP ='$sourcearr[$i]' AND CITY_RES IN('DE00','MH01','MH02','MH03','MH04','MH05','MH06','MH07','MH08','MH09','MH10','MH11','MH12','MH13','MH','KA02') AND ACTIVATED IN ('Y','H') AND ENTRY_MODIFY='E' AND INCOMPLETE='N' GROUP BY CITY_RES";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
		if ($row['CITY_RES'] == 'DE00')
			$DELHIPROFILES = $row['CNT'];
		elseif ($row['CITY_RES'] == 'KA02')
			$BANGPROFILES = $row['CNT'];
		else
		{
			$MAHARPROFILES += $row['CNT'];
			if ($row['CITY_RES'] == 'MH04')
				$MUMBAIPROFILES = $row['CNT'];

		}
        }
	
	$sql = "SELECT SUM(COUNT) AS CNT , COUNTRY_RES FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND SOURCEGP ='$sourcearr[$i]' AND ACTIVATED IN ('Y','H') AND COUNTRY_RES <>'51' AND ENTRY_MODIFY='E' AND INCOMPLETE='N' GROUP BY COUNTRY_RES";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
		if ($row['COUNTRY_RES'] == '88')
			$PAKCOUNT = $row['CNT'];
			
		$NRIPROFILES += $row['CNT'];
        }

	unset($srcarr);

	$sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS  WHERE ENTRY_DT BETWEEN '$start_dt' AND '$today' AND ACTIVATED IN ('Y','H') AND MTONGUE = '1' AND SOURCEGP ='$sourcearr[$i]' AND ENTRY_MODIFY='E' AND INCOMPLETE='N'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$FOREIGNCOUNT = $row['CNT'];
	}

	$IRREV_LOC_PROFILES = $FOREIGNCOUNT + $PAKCOUNT;
	$TOTAL_IRREV_PROFILES = $IRREV_LOC_PROFILES + $IRREV_MALE_PROFILES;

	if ($TOTAL_IRREV_PROFILES)
	{
		$IRREV_LOC_PER = round(($IRREV_LOC_PROFILES/$TOTAL_IRREV_PROFILES) * 100,2);
		$IRREV_AGE_PER = round(($IRREV_MALE_PROFILES/$TOTAL_IRREV_PROFILES) * 100,2);
	}
	$line=$today.",".$CLICKS.",".$TOTALPROFILES.",".$CONVERSION.",".$ACTIVATED.",".$ACTIVE_PER.",".$INACTIVATED.",".$PAID.",".$MALE_AGE_COUNT.",".$FEMALECOUNT.",".$DELHIPROFILES.",".$MAHARPROFILES.",".$MUMBAIPROFILES.",".$BANGPROFILES.",".$NRIPROFILES.",".$IRREV_MALE_PROFILES.",".$IRREV_AGE_PER.",".$IRREV_LOC_PROFILES.",".$IRREV_LOC_PER;
	$data.= trim($line)."\n";
        //echo $data;
	fwrite($fp,$data);
	fclose($fp);

	$fp = fopen($filename,"r");
	if(!$fp)
	{
		die("no file pointer");
	}
	$content=fread($fp,filesize($filename));
	fclose($fp);

	$file_name = $sourcearr[$i]."_".$ddate_mon."-".$ddate_yyyy.".csv";
	$subject = "JS Daily MIS for Source: ".$sourcearr[$i];

	$bcc="shiv.narayan@jeevansathi.com";
	$cc="vivek@jeevansathi.com,ayesha@naukri.com";

	$to="geetu.ahuja@naukri.com,aparna.singh@naukri.com,vinny.ganju@naukri.com,kavita.malhotra@naukri.com,madhurima.sil@naukri.com,darshan@jeevansathi.com";

	send_email($to,"",$subject,$from,$cc,$bcc,$content,"csv",$file_name);
	unset($content);
	unset($CLICKS);
	unset($TOTALPROFILES);
	unset($CONVERSION);
	unset($ACTIVATED);
	unset($ACTIVE_PER);
	unset($INACTIVATED);
	unset($PAID);
	unset($MALE_AGE_COUNT);
	unset($FEMALECOUNT);
	unset($DELHIPROFILES);
	unset($MAHARPROFILES);
	unset($MUMBAIPROFILES);
	unset($BANGPROFILES);
	unset($NRIPROFILES);
	unset($IRREV_MALE_PROFILES);
	unset($IRREV_AGE_PER);
	unset($IRREV_LOC_PROFILES);
	unset($IRREV_LOC_PER);

}

?>
