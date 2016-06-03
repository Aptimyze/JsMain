<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include ("$docRoot/crontabs/connect.inc");
$db= connect_slave();
$db2= connect_db();
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");


$yest_dt_ts=time()-2*24*60*60;
$yest_dt= date("Y-m-d",$yest_dt_ts);
$yest_dt="2008-04-01";
$st_dt= $yest_dt." 00:00:00";
//$yest_dt="2008-02-03";
$end_dt="2009-01-20 23:59:59";


$sql="SELECT B.PROFILEID, B.ENTRY_DT, B.ACTIVATED, A.ADNETWORK, A.ACCOUNT, A.CAMPAIGN, A.ADGROUP, A.KEYWORD, A.`MATCH` , A.LMD, B.AGE, B.GENDER, B.YOURINFO, B.FAMILYINFO, B.SPOUSE, B.FATHER_INFO, B.SIBLING_INFO, B.JOB_INFO, B.RELATION, B.HAVEPHOTO, B.COUNTRY_RES, B.CITY_RES, B.MTONGUE, B.SMOKE, B.DRINK, B.MANGLIK, B.BTYPE, B.DIET, B.SHOW_HOROSCOPE, B.SOURCE, B.INCOME, C.GROUPNAME
FROM MIS.SOURCE AS C, newjs.JPROFILE AS B
LEFT JOIN MIS.TRACK_TIEUP_VARIABLE AS A ON A.PROFILEID = B.PROFILEID
WHERE C.SourceId = B.SOURCE
AND B.ENTRY_DT
BETWEEN '$st_dt'
AND '$end_dt' GROUP BY B.PROFILEID ";


$res= mysql_query($sql,$db) or die(mysql_error1($db));
if(mysql_num_rows($res))
{
	$data = "Source, Group, Adnetwork, Account, Campaign, Adgroup, Keyword, Match, LMD, Entry Date, Profileid, Activated, Age, Gender, Character Length, Profile Posted By, Photo, Country, City, Community, Income, n(sum)\n";
	
	$sql2= "INSERT IGNORE INTO MIS.KEYWORD_PROFILE_REPORT (Source, `Group`, Adnetwork, Account, Campaign, Adgroup, Keyword, `Match`, LMD, Entry_Date, Profileid, Activated, Age, Gender, Character_Length, Posted_By, Photo, Country, City, Community, Income, n_sum) VALUES ";
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

		$char_len= strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

		$data.= $row['SOURCE'].", ".$row['GROUPNAME'].", ".$row['ADNETWORK'].", ".$row['ACCOUNT'].", ".$row['CAMPAIGN'].", ".$row['ADGROUP'].", ".$row['KEYWORD'].", ".$row['MATCH'].", ".$row['LMD'].", ".$row['ENTRY_DT'].", ".$row['PROFILEID'].", ".$row['ACTIVATED'].", ".$row['AGE'].", ".$row['GENDER'].", ".$char_len.", ".$row['RELATION'].", ".$row['HAVEPHOTO'].", ".$row['COUNTRY_RES'].", ".$row['CITY_RES'].", ".$row['MTONGUE'].", ".$row['INCOME'].", ".$n."\n ";

		if($i<1000)
		{
			if($values!='')
                        	$values.=", ";
			$values.= "( '".$row['SOURCE']."', '".$row['GROUPNAME']."', '".$row['ADNETWORK']."', '".$row['ACCOUNT']."', '".$row['CAMPAIGN']."', '".$row['ADGROUP']."', '".$row['KEYWORD']."', '".$row['MATCH']."', '".$row['LMD']."', '".$row['ENTRY_DT']."', '".$row['PROFILEID']."', '".$row['ACTIVATED']."', '".$row['AGE']."', '".$row['GENDER']."', '".$char_len."', '".$row['RELATION']."', '".$row['HAVEPHOTO']."', '".$row['COUNTRY_RES']."', '".$row['CITY_RES']."', '".$row['MTONGUE']."', '".$row['INCOME']."', '".$n."' )";
			$i++;
		}
		else
		{
			$sql1=$sql2.$values;
       			//mysql_query($sql1,$db2) or die(mysql_error1($db2));
			$values='';$i=0;
		}
	}

	if($values!='')
	{	
		$sql1=$sql2.$values;
		//mysql_query($sql1,$db2) or die(mysql_error1($db2));
	}

	$dir='daily_kid_profile_report';
	if(!is_dir($dir))
        	mkdir($dir);
	$fp=fopen("$dir/$yest_dt.csv","w+");
        if($fp)
        {
		fwrite($fp,$data);
		fclose($fp);
        }
	$yest_dt_frt= date("dMy",$yest_dt_ts);
	$yest_frmt=strtoupper($yest_dt_frt);	
	send_email("analytics@naukri.com"," Keyword profile detail report in csv format for $yest_dt ","Keyword Profile csv","noreply@jeevansathi.com","puneet.makkar@jeevansathi.com,neha.verma@99acres.com","",$data,"","$yest_frmt.csv");
	//send_email("puneet.makkar@jeevansathi.com"," Keyword profile detail report in csv format for $yest_dt ","Keyword Profile csv","noreply@jeevansathi.com","puneet.makkar@jeevansathi.com,neha.verma@99acres.com","","jai shri ram","","$yest_dt.csv");

}

function mysql_error1($db)
{
	mail("puneet.makkar@jeevansathi.com,neha.verma@99acres.com","Error in keyword profile detail cron csv",mysql_error($db));
}


?>
