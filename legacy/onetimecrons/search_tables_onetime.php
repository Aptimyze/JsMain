<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
//include_once("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/profile/mysql_multiple_connections.php");
//INCLUDE FILE ENDS

if(!isset($_SERVER['argv'][1]))
        die("Please Specify Argumnets\n");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

if($_SERVER['argv'][1] == 1)
	$table = array('SEARCH_FEMALE');	
elseif($_SERVER['argv'][1] == 2)
	$table = array('SEARCH_MALE');

foreach($table as $k=>$v)
{
	$statement = "SELECT PROFILEID FROM newjs.".$v;
        $result = $mysqlObj->executeQuery($statement,$dbS) or $mysqlObj->logError($statement);
        while($row = $mysqlObj->fetchArray($result))
        {
		$statement1 = "SELECT MOD_DT,PHOTODATE FROM newjs.JPROFILE WHERE PROFILEID = ".$row["PROFILEID"];
		$result1 = $mysqlObj->executeQuery($statement1,$dbS) or $mysqlObj->logError($statement1);
		$row1 = $mysqlObj->fetchArray($result1);
		$statement2 = "UPDATE newjs.".$v." SET MOD_DT = \"".$row1["MOD_DT"]."\",PHOTODATE = \"".$row1["PHOTODATE"]."\" WHERE PROFILEID = ".$row["PROFILEID"];
		$mysqlObj->executeQuery($statement2,$dbM) or $mysqlObj->logError($statement2);
	}
	echo $v." DONE\n";
}

/*

$active_db = $dbM;

$table = array('SEARCH_FEMALE','SEARCH_MALE');
foreach($table as $k=>$v)
{
	$statement = "SELECT PROFILEID FROM newjs.".$v;
	$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
        while($row = $mysqlObjM->fetchArray($result))
        {
		$statement1 = "SELECT J.SHOWPHONE_MOB AS SHOWPHONE_MOB,J.SHOWPHONE_RES AS SHOWPHONE_RES,J.PHONE_FLAG AS PHONE_FLAG,J.MOB_STATUS AS MOB_STATUS,J.LANDL_STATUS AS LANDL_STATUS,J.PHONE_MOB AS PHONE_MOB,J.PHONE_RES AS PHONE_RES,J.STD AS STD,JC.SHOWALT_MOBILE AS SHOWALT_MOBILE,JC.ALT_MOBILE AS ALT_MOBILE,JC.ALT_MOB_STATUS AS ALT_MOB_STATUS FROM newjs.JPROFILE J LEFT JOIN JPROFILE_CONTACT JC ON J.PROFILEID = JC.PROFILEID WHERE J.PROFILEID = ".$row["PROFILEID"];
		$result1 = $mysqlObjM->executeQuery($statement1,$dbM) or die($statement1);
        	$row1 = $mysqlObjM->fetchArray($result1);
		
		$phoneArr["PROFILEID"] = $row["PROFILEID"];
		$phoneArr["PHONE_FLAG"] = $row1["PHONE_FLAG"];
		$phoneArr["MOB_STATUS"] = $row1["MOB_STATUS"];
		$phoneArr["LANDL_STATUS"] = $row1["LANDL_STATUS"];
		$phoneArr["PHONE_MOB"] = $row1["PHONE_MOB"];
		$phoneArr["PHONE_RES"] = $row1["PHONE_RES"];
		$phoneArr["ALT_MOBILE"] = $row1["ALT_MOBILE"];
		$phoneArr["ALT_MOB_STATUS"] = $row1["ALT_MOB_STATUS"];

		foreach($phoneArr as $kk=>$vv)
		{
			if(!$vv)
				$phoneArr[$kk]="";
		}

		if($row1["PHONE_MOB"])
		{
			if($row1["SHOWPHONE_MOB"]=="N")
				$mobile_hidden = 1;
			else
				$mobile_hidden = 0;
		}
		else
			$mobile_hidden = 1;
		if($row1["PHONE_RES"])
		{
			if($row1["SHOWPHONE_RES"]=="N")
				$landline_hidden = 1;
			else
				$landline_hidden = 0;
		}
		else
			$landline_hidden = 1;
		if($row1["ALT_MOBILE"])
		{
			if($row1["SHOWALT_MOBILE"]=="N")
				$alt_hidden = 1;
			else
				$alt_hidden = 0;
		}
		else
			$alt_hidden = 1;

		if($mobile_hidden && $landline_hidden && $alt_hidden)
			$check_phone = "H";	//Hidden

		if(!$check_phone)
		{
			if($row1["PHONE_MOB"] || $row1["PHONE_RES"] || $row1["ALT_MOBILE"])
			{
				$check_phone = getPhoneStatus($phoneArr);
				if($check_phone=="Y")
					$check_phone = "V";     //Verified
				elseif($check_phone=="I")
					$check_phone = "I";     //Invalid
				else
				{
					if($row1["PHONE_MOB"])
						$mob_invalid = checkMobileNumber($row1["PHONE_MOB"],$row["PROFILEID"],$dbM);
					else
						$mob_invalid = "N";
					if($row1["ALT_MOBILE"])
						$alt_invalid = checkMobileNumber($row1["ALT_MOBILE"],$row["PROFILEID"],$dbM);
					else
						$alt_invalid = "N";
					if($row1["PHONE_RES"])
						$land_invalid = checkLandlineNumber($row1["PHONE_RES"],$row1["STD"],$row["PROFILEID"],$dbM);
					else
						$land_invalid = "N";

					if($mob_invalid=="N" && $alt_invalid=="N" && $land_invalid=="N")
						$check_phone = "I";	//Invalid
					else
						$check_phone = "P";     //Phone not verified, not invalid and atleast one not hidden
				}
			}
			else
			{
				$check_phone="N";	//Phone does not exist
			}
		}
		else
		{
			if(!$row1["PHONE_MOB"] && !$row1["PHONE_RES"] && !$row1["ALT_MOBILE"])
				$check_phone = "N";	//Phone does not exist
		}

		$updateStatement = "UPDATE newjs.".$v." SET CHECK_PHONE = \"".$check_phone."\" WHERE PROFILEID = ".$row["PROFILEID"];
		$mysqlObjM->executeQuery($updateStatement,$dbM) or die($updateStatement);
		unset($check_phone);
		unset($phoneArr);
	}
	echo $v."\n";
}

$table = array('SEARCH_MALE_REV','SEARCH_FEMALE_REV');
$columns = array('PARTNER_HANDICAPPED','PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_SMOKE','PARTNER_RELATION','PARTNER_RELIGION');

foreach($table as $k=>$v)
{
	$statement = "SELECT PROFILEID,".implode(",",$columns)." FROM newjs.".$v;
	$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
        while($row = $mysqlObjM->fetchArray($result))
        {
		$flag =0;
		$updateStr = "UPDATE newjs.".$v." SET ";
		foreach($columns as $kk=>$vv)
		{
			$x = $row[$vv];
			$temp = explode(",",$row[$vv]);
			foreach($temp as $kkk=>$vvv)
			{
				$vvv = trim($vvv,"'");
				$temp[$kkk] = $vvv;
			}
			$row[$vv] = implode(",",$temp);
			if($x)
			{
				$updateStr = $updateStr.$vv." = \"".$row[$vv]."\",";
				$flag = 1;
			}
		}
		$updateStr = rtrim($updateStr,",");
		$updateStr = $updateStr." WHERE PROFILEID = ".$row["PROFILEID"];
		if($flag)
			$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);
	}
	echo $v."\n";
}
*/
echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
