<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include_once("connect.inc");
$db	=connect_slave81();
mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

$db2	=connect_db();
mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db2);
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

if($argv[1]=='15')
{
        $st_dt_ts=time()-15*24*60*60;
        $end_dt_ts=time()-24*60*60;
}
if($argv[1]=='2')
{
        $st_dt_ts=time()-24*60*60;
        $end_dt_ts=time()-24*60*60;
}
else
{
        $st_dt_ts=time()-2*24*60*60;
        $end_dt_ts=time()-2*24*60*60;
}

//$yest_dt_ts=time()-2*24*60*60;
//$yest_dt= date("Y-m-d",$yest_dt_ts);

$st_dt= date("Y-m-d",$st_dt_ts);
$st_dt_frt= date("dMy",$st_dt_ts);
$st_frmt=strtoupper($st_dt_frt);

$end_dt= date("Y-m-d",$end_dt_ts);
$end_dt_frt= date("dMy",$end_dt_ts);
$end_frmt=strtoupper($end_dt_frt);

$st_dt= $st_dt." 00:00:00";
$end_dt= $end_dt." 23:59:59";

//$st_dt="2010-06-03 00:00:00";
//$end_dt="2010-06-03 23:59:59";

$sql="SELECT B.PROFILEID, B.ENTRY_DT, B.ACTIVATED, A.ADNETWORK, A.ACCOUNT, A.CAMPAIGN, A.ADGROUP, A.KEYWORD, A.`MATCH` , A.LMD, B.AGE, B.GENDER, B.YOURINFO, B.FAMILYINFO, B.SPOUSE, B.FATHER_INFO, B.SIBLING_INFO, B.JOB_INFO, B.RELATION, B.HAVEPHOTO, B.COUNTRY_RES, B.CITY_RES, B.MTONGUE, B.SMOKE, B.DRINK, B.MANGLIK, B.BTYPE, B.DIET, B.SHOW_HOROSCOPE, B.SOURCE, B.SEC_SOURCE, B.INCOME, C.GROUPNAME, B.INCOMPLETE 
FROM MIS.SOURCE AS C, newjs.JPROFILE AS B
LEFT JOIN MIS.TRACK_TIEUP_VARIABLE AS A ON A.PROFILEID = B.PROFILEID
WHERE C.SourceId = B.SOURCE
AND B.ENTRY_DT
BETWEEN '$st_dt'
AND '$end_dt' GROUP BY B.PROFILEID ";


$res= mysql_query($sql,$db) or exit(1);
if(mysql_num_rows($res))
{
	$data = "Source, Secondary Source, Group, Adnetwork, Account, Campaign, Adgroup, Keyword, Match, LMD, Entry Date, Profileid, Activated, Age, Gender, Character Length, Profile Posted By, Photo, Country, City, Community, Income, n(sum), Incomplete\n";

	$sql2= "INSERT IGNORE INTO MIS.KEYWORD_PROFILE_REPORT (Source, Sec_Source, `Group`, Adnetwork, Account, Campaign, Adgroup, Keyword, `Match`, LMD, Entry_Date, Profileid, Activated, Age, Gender, Character_Length, Posted_By, Photo, Country, City, Community, Income, n_sum, Incomplete) VALUES ";
	$i=0;
	while($row= mysql_fetch_array($res))
	{
		$n=0;$char_len=0;
		if($row['SMOKE'])
                        $n++;
                if($row['DRINK'])
                        $n++;
                if($row['MANGLIK'])
                        $n++;
                if($row['BTYPE'])
                        $n++;
                if($row['DIET'])
                        $n++;
                if($row['SHOW_HOROSCOPE'])
                        $n++;

		if($row['SEC_SOURCE']=='S' || $row['SEC_SOURCE']=='')
			$sec_source="Self";
		elseif($row['SEC_SOURCE']=='M')
			$sec_source="Sugar Mailer";
		elseif($row['SEC_SOURCE']=='I')
			$sec_source="JS Mailer";
		elseif($row['SEC_SOURCE']=='C')
			$sec_source="Called";
		else
			$sec_source="";

		$char_len= strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

		$data.= $row['SOURCE'].", ".$sec_source.", ".$row['GROUPNAME'].", ".$row['ADNETWORK'].", ".$row['ACCOUNT'].", ".$row['CAMPAIGN'].", ".$row['ADGROUP'].", ".$row['KEYWORD'].", ".$row['MATCH'].", ".$row['LMD'].", ".$row['ENTRY_DT'].", ".$row['PROFILEID'].", ".$row['ACTIVATED'].", ".$row['AGE'].", ".$row['GENDER'].", ".$char_len.", ".$row['RELATION'].", ".$row['HAVEPHOTO'].", ".$row['COUNTRY_RES'].", ".$row['CITY_RES'].", ".$row['MTONGUE'].", ".$row['INCOME'].", ".$n.", ".$row['INCOMPLETE']."\n ";

		if($i<1000)
		{
			if($values!='')
                        	$values.=", ";
			$values.= "( '".mysql_real_escape_string($row['SOURCE'])."', '".$sec_source."', '".mysql_real_escape_string($row['GROUPNAME'])."', '".mysql_real_escape_string($row['ADNETWORK'])."', '".mysql_real_escape_string($row['ACCOUNT'])."', '".mysql_real_escape_string($row['CAMPAIGN'])."', '".mysql_real_escape_string($row['ADGROUP'])."', '".mysql_real_escape_string($row['KEYWORD'])."', '".mysql_real_escape_string($row['MATCH'])."', '".mysql_real_escape_string($row['LMD'])."', '".mysql_real_escape_string($row['ENTRY_DT'])."', '".mysql_real_escape_string($row['PROFILEID'])."', '".mysql_real_escape_string($row['ACTIVATED'])."', '".mysql_real_escape_string($row['AGE'])."', '".mysql_real_escape_string($row['GENDER'])."', '".$char_len."', '".mysql_real_escape_string($row['RELATION'])."', '".mysql_real_escape_string($row['HAVEPHOTO'])."', '".mysql_real_escape_string($row['COUNTRY_RES'])."', '".mysql_real_escape_string($row['CITY_RES'])."', '".mysql_real_escape_string($row['MTONGUE'])."', '".mysql_real_escape_string($row['INCOME'])."', '".$n."', '".mysql_real_escape_string($row['INCOMPLETE'])."' )";
			$i++;
		}
		else
		{
			$sql1=$sql2.$values;
       			mysql_query($sql1,$db2) or die(mysql_error($db2));
			$values='';$i=0;
		}
	}
	if($values!='')
	{	
		$sql1=$sql2.$values;
		mysql_query($sql1,$db2) or exit(1);
	}

	$sub="Keyword Profile csv";
	if($argv[1]=='15')
	{
		$msg="Keyword profile detail report in csv format for $st_frmt - $end_frmt";
	}
	if($argv[1]=='2')
	{
		$sub="2d keyword profile CSV";
		$msg="Keyword profile detail report in csv format for $end_frmt";
		$end_frmt="2d_".$end_frmt;
	}
	else
	{
		$msg="Keyword profile detail report in csv format for $end_frmt";
	}
	
	
	send_email("analytics@naukri.com,analytics1@naukri.com,nitesh.s@jeevansathi.com,nikhil.dhiman@jeevansathi.com",$msg,$sub,"noreply@jeevansathi.com","","",$data,"","$end_frmt.csv");

}

?>
