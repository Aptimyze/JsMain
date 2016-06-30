<?php
include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$table_name = array("SEARCH_FEMALE","SEARCH_MALE");

foreach($table_name as $k=>$v)
{
	$sql = "SELECT PROFILEID FROM newjs.".$v." WHERE CHECK_PHONE = 'H' AND DATE(ENTRY_DT)>='".DateConstants::PhoneMandatoryLive."'";
	$result = $mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
	while($row = $mysqlObj->fetchArray($result))
	{
		$sql_caste = "SELECT J.CASTE AS CASTE,J.SHOWPHONE_MOB AS SHOWPHONE_MOB,J.SHOWPHONE_RES AS SHOWPHONE_RES,J.PHONE_FLAG AS PHONE_FLAG,J.MOB_STATUS AS MOB_STATUS,J.LANDL_STATUS AS LANDL_STATUS,J.PHONE_MOB AS PHONE_MOB,J.PHONE_RES AS PHONE_RES,J.STD AS STD,JC.SHOWALT_MOBILE AS SHOWALT_MOBILE,JC.ALT_MOBILE AS ALT_MOBILE,JC.ALT_MOB_STATUS AS ALT_MOB_STATUS FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT JC ON J.PROFILEID = JC.PROFILEID WHERE J.PROFILEID = ".$row["PROFILEID"];
		$result_caste = $mysqlObj->executeQuery($sql_caste,$dbM,'',1) or $mysqlObj->logError($sql_caste,1);
		$row_caste=$mysqlObj->fetchArray($result_caste);

		$check_phone = getPhoneStatusForSearch($row_caste,$profileid,$db);

		if($check_phone!='H')
		{
			$sql1 = "UPDATE newjs.".$v." SET CHECK_PHONE = '".$check_phone."' WHERE PROFILEID = ".$row["PROFILEID"];
			$mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
		}
	}
	
	$sql1 = "DELETE FROM newjs.".$v." WHERE CHECK_PHONE IN ('I','N','K','P') AND DATE(ENTRY_DT)>='".DateConstants::PhoneMandatoryLive."'";
	$mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
	echo $v." DONE\n";
}

mysql_close($dbM);
mysql_close($dbS);

function getPhoneStatusForSearch($row1,$profileid,$db)
{
        $flagPhone = 0;
        $phoneArr["PROFILEID"] = $profileid;
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
                $check_phone = "H";     //Hidden

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
                                        $mob_invalid = checkMobileNumber($row1["PHONE_MOB"],$profileid,$db);
                                else
                                        $mob_invalid = "N";
                                if($row1["ALT_MOBILE"])
                                        $alt_invalid = checkMobileNumber($row1["ALT_MOBILE"],$profileid,$db);
                                else
                                        $alt_invalid = "N";
                                if($row1["PHONE_RES"])
                                        $land_invalid = checkLandlineNumber($row1["PHONE_RES"],$row1["STD"],$profileid,$db);
                                else
                                        $land_invalid = "N";

                                if($mob_invalid=="N" && $alt_invalid=="N" && $land_invalid=="N")
                                        $check_phone = "I";     //Invalid
                                else
                                        $check_phone = "P";     //Phone not verified, not invalid and atleast one not hidden
                        }
                }
                else
                        $check_phone="N";       //Phone does not exist
        }
	else
        {
                if(!$row1["PHONE_MOB"] && !$row1["PHONE_RES"] && !$row1["ALT_MOBILE"])
                        $check_phone = "N";     //Phone does not exist
                else
                {
                        $check_stat = getPhoneStatus($phoneArr);
                        if($check_stat=="Y")
                                $check_phone = "H";     //Verified and Hidden
                        elseif($check_phone=="I")
                                $check_phone = "I";     //Invalid
                        else
                        {
                                if($row1["PHONE_MOB"])
                                        $mob_invalid = checkMobileNumber($row1["PHONE_MOB"],$profileid,$db);
                                else
                                        $mob_invalid = "N";
                                if($row1["ALT_MOBILE"])
                                        $alt_invalid = checkMobileNumber($row1["ALT_MOBILE"],$profileid,$db);
                                else
                                        $alt_invalid = "N";
                                if($row1["PHONE_RES"])
                                        $land_invalid = checkLandlineNumber($row1["PHONE_RES"],$row1["STD"],$profileid,$db);
                                else
                                        $land_invalid = "N";

                                if($mob_invalid=="N" && $alt_invalid=="N" && $land_invalid=="N")
                                        $check_phone = "I";     //Invalid
                                else
                                        $check_phone = "K";     //Phone not verified, not invalid and hidden
                        }
                }
        }

	return $check_phone;
}
?>
