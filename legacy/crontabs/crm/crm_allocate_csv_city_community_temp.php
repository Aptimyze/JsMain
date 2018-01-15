<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	ini_set("max_execution_time","0");
	chdir("$docRoot/crontabs/crm");
	include ("../connect.inc");
	include_once("../comfunc.inc");
	include("allocate_functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");

	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

	$SITE_URL="http://www.jeevansathi.com";
	//include ("connect.inc");
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;

	$db = connect_db();

	for($i=0;$i<$noOfActiveServers;$i++)
	{
		$myDbName=$activeServers[$i];
		$myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
	}

	$ts = time();
	$ts -= 30*24*60*60;
	$last_day = date("Y-m-d",$ts);

	//NRI Profiles
	$nri_arr = array(7,125,126);

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"".","."\"EVER_PAID\"\n";

	$filename7 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri.txt";
	

	$fp7 = fopen($filename7,"w+");

        if(!$fp7)
        {
                die("no file pointer");
        }

	fwrite($fp7,$header);
	
	$sql_pid = "SELECT PROFILEID, SCORE, MTONGUE from incentive.MAIN_ADMIN_POOL where PROFILEID IN ('4111737','4086652','4111748','4111755','4111856','4111858','4111956','4111960','4112018','4112032','4112044','4112104','4112120','1098335','4112154','4112184','4112196','4112242','4112275','4067885','4112277','4112290','4112308','4070896','4096769','4087697','4112401','4112438','4112440','4112518','4112606','4112629','4112652','4112687','4112740','3650350','4112779','4112792','4112794','4112840','4112844','4112850','4112871','4112894','4112906','4112930','4112967','4105140','4113070','4113238','4113281','4113355','4113358','4113420','4113425','4113430','4113431','4113446','4113662','4113716','4070653','4113847','4113915','4114060','4114103','4114105','4114259','4114263','4114266','4114268','4114270','4114274','4114294','4114297','4114307','4114329','4114334','4114347','4114367','4114375','4114380','4114412','4114418','4114426','4114427','4114437','4091399','4114452','4114454','4114463','4114469','4114478','4114482','4114488','4114491','4114493','4114496','4114531','4114556','4114569','4114578','4114582','4114584','4114590','4114602','4114632','4114643') AND SCORE>250";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	$row_pid = mysql_fetch_array($res_pid);
	while($row_pid = mysql_fetch_array($res_pid))
	{
		$profileid = $row_pid['PROFILEID'];
		$mtongue = $row_pid['MTONGUE'];
		$score = $row_pid['SCORE'];

		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$mysqlObj->ping($myDbArray[$myDbName]);
		$myDb=$myDbArray[$myDbName];
		$jpartnerObj->setPROFILEID($profileid);
		if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
			$DPP=1;
		else
			$DPP=0;
		write_contents_to_file($profileid,$mtongue,$score,'',$DPP,'',1);
	}
	fclose($fp7);

	$profileid_file7 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri.txt";

	$msg.="\nFor Nri : ".$profileid_file7;
	$to="vibhor.garg@jeevansathi.com";
	//$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,deepak.sharma@naukri.com, samrat.chadha@naukri.com, kamaljeet.singh@naukri.com";
	$bcc="vibhor.garg@jeevansathi.com";
	$sub="Daily CSV for calling";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);
?>
