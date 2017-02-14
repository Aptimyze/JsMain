<?php

/**
*       Function        :       horoscope_save
*       Description     :       Inserts or Update the User's picture information
**/
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/classes/ProfileInsertLib.php");
include_once(JsConstants::$docRoot."/classes/ProfileDeleteLib.php");
function horoscope_save($profileid,$horoscope_content)
{
	$objUpdate = JProfileUpdateLib::getInstance();
	$objInsert = ProfileInsertLib::getInstance();
	//query to find if the user has earlier uploaded horoscope which is yet not screened in HOROSCOPE_FOR_SCREEN table
	$sql="select ID from HOROSCOPE_FOR_SCREEN where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$objDelete = ProfileDeleteLib::getInstance();
	if($row=mysql_fetch_array($res))
	{
		//query to delete the earlier uploaded horoscope which is yet not screened in HOROSCOPE_FOR_SCREEN table
		$result = $objDelete->deleteRecordHOROSCOPE_FOR_SCREEN($row[ID]);
		if(false === $result) {
			$sql_del="delete from HOROSCOPE_FOR_SCREEN where ID='$row[ID]'";
			logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");
		}
		/*$sql_del="delete from HOROSCOPE_FOR_SCREEN where ID='$row[ID]'";
		mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");*/
	}

	//query to insert the new uploaded horoscope in HOROSCOPE_FOR_SCREEN table
	$result = $objInsert->insertHOROSCOPE_FOR_SCREEN($profileid, $horoscope_content);
	if (false === $result) {
		$sql_insert=" insert into HOROSCOPE_FOR_SCREEN value ('','$profileid','$horoscope_content','')";
		logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_insert,"ShowErrTemplate");
	}
//	$sql_insert=" insert into HOROSCOPE_FOR_SCREEN value ('','$profileid','$horoscope_content','')";
//	mysql_query_decide($sql_insert) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_insert,"ShowErrTemplate");

	//query to update the TYPE='U' and HOROSCOPE_SCREENING='0' in ASTRO_DETAILS table
	$result = $objUpdate->updateASTRO_DETAILS($profileid,array('TYPE'=>'U','HOROSCOPE_SCREENING'=>'0','DATE'=>date('Y-m-d H:i:s')));
	if(false === $result) {
		$sql_update="update ASTRO_DETAILS set TYPE='U',HOROSCOPE_SCREENING='0',DATE=now() where PROFILEID='$profileid'";
		logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
	}
//	$sql_update="update ASTRO_DETAILS set TYPE='U',HOROSCOPE_SCREENING='0',DATE=now() where PROFILEID='$profileid'";
//	mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");


	//query to delete the old uploaded horoscope from HOROSCOPE table
	$result = $objDelete->deleteRecordHOROSCOPE($profileid);
	if(false === $result) {
		$sql_del_from_horoscope="delete from HOROSCOPE where PROFILEID='$profileid'";
		logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del_from_horoscope,"ShowErrTemplate");
	}
//	$sql_del_from_horoscope="delete from HOROSCOPE where PROFILEID='$profileid'";
//	mysql_query_decide($sql_del_from_horoscope) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del_from_horoscope,"ShowErrTemplate");
	return true;
}

function get_horoscope($profileid)
{
	$sql="select HOROSCOPE from HOROSCOPE_FOR_SCREEN where UPLOADED != 'D' AND PROFILEID='$profileid'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	
	return $row['HOROSCOPE'];

}
?>
