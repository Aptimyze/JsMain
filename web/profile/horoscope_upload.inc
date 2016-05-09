<?php

/**
*       Function        :       horoscope_save
*       Description     :       Inserts or Update the User's picture information
**/

function horoscope_save($profileid,$horoscope_content)
{
	//query to find if the user has earlier uploaded horoscope which is yet not screened in HOROSCOPE_FOR_SCREEN table
	$sql="select ID from HOROSCOPE_FOR_SCREEN where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	if($row=mysql_fetch_array($res))
	{
		//query to delete the earlier uploaded horoscope which is yet not screened in HOROSCOPE_FOR_SCREEN table
		$sql_del="delete from HOROSCOPE_FOR_SCREEN where ID='$row[ID]'";
		mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");
	}

	//query to insert the new uploaded horoscope in HOROSCOPE_FOR_SCREEN table
	$sql_insert=" insert into HOROSCOPE_FOR_SCREEN value ('','$profileid','$horoscope_content','')";
	mysql_query_decide($sql_insert) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_insert,"ShowErrTemplate");

	//query to update the TYPE='U' and HOROSCOPE_SCREENING='0' in ASTRO_DETAILS table
	$sql_update="update ASTRO_DETAILS set TYPE='U',HOROSCOPE_SCREENING='0',DATE=now() where PROFILEID='$profileid'";
	mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");

	//query to delete the old uploaded horoscope from HOROSCOPE table	
	$sql_del_from_horoscope="delete from HOROSCOPE where PROFILEID='$profileid'";
	mysql_query_decide($sql_del_from_horoscope) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del_from_horoscope,"ShowErrTemplate");
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
