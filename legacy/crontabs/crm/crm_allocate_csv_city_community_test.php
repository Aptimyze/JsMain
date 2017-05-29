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

	//finding south communities.
	/*$sql_mt = "SELECT * FROM incentive.REGION_MTONGUE_MAPPING WHERE REGION='S'";
	$res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
	while($row_mt = mysql_fetch_array($res_mt))
		$south_arr = explode(",",$row_mt['MTONGUE']);*/

	//cities under south region
	$sql_mt = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE IN_REGION='S'";
        $res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
        while($row_mt = mysql_fetch_array($res_mt))
                $south_arr[] = $row_mt['VALUE'];
	
	//Delhi-NCR
	$dncr_arr = array('DE00','UP25','HA03','HA02','UP12');

	//findng cities which fall under PUNE branch.
        $sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY='MH08'";
        $res_city = mysql_query($sql_city,$db) or die($sql_city.mysql_error($db));
        while($row_city = mysql_fetch_array($res_city))
                $pune_city_arr[] = $row_city['VALUE'];

	//finding west cities
	$sql_west = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'GU%' UNION SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MP%' UNION SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MH%'";
	$res_west = mysql_query($sql_west,$db) or die($sql_west.mysql_error($db));
	while($row_west = mysql_fetch_array($res_west))
		$west_city_arr[] = $row_west['VALUE'];

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

        $filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_test.txt";

        $fp1 = fopen($filename1,"w+");

        if(!$fp1)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
	
	unset($pidarr);
	unset($pidstr);

	$sql_pid = "SELECT PROFILEID, SCORE, MTONGUE from incentive.TEMP_CSV_PROFILES where 1";
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
		write_contents_to_file($profileid,$mtongue,$score,'',$DPP);
	}
        fclose($fp1);

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_test.txt";

	$msg="For test: ".$profileid_file1;

	$to="vibhor.garg@jeevansathi.com";
	$sub="Daily CSV for testing";
	$from="From:vibhor.garg@jeevansathi.com";

	mail($to,$sub,$msg,$from);
?>
