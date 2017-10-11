<?php
include('connect.inc');
$profile_det_sql = "SELECT USERNAME,GENDER,DTOFBIRTH,ACTIVATED,MSTATUS,COUNTRY_RES,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = '$pid' ";
$profile_det_res= mysql_query_decide($profile_det_sql) or die("$profile_det_sql".mysql_error_js());
$profile_det_row= mysql_fetch_array($profile_det_res);
$user		= $profile_det_row["USERNAME"];
$gender		= $profile_det_row["GENDER"];
$dtofbirth	= $profile_det_row["DTOFBIRTH"];
$activated	= $profile_det_row["ACTIVATED"];
$maritalstatus  = $profile_det_row["MSTATUS"];
$country	= $profile_det_row["COUNTRY_RES"];
$city		= $profile_det_row["CITY_RES"];
if($country!=$new_country)
{
	$change_req_sql = "INSERT INTO jsadmin.PROFILE_CHANGE_REQUEST (PROFILEID,ORIG_USERNAME,ORIG_GENDER,ORIG_DTOFBIRTH,MEMBERSHIP_STATUS,MSTATUS,CHANGE_DETAILS,USER,REQUEST_DT,REQUEST_FOR,COUNTRY,NEW_COUNTRY,CITY,NEW_CITY) VALUES ('$pid','$user','$gender','$dtofbirth','$activated','$maritalstatus','$message','online',NOW(),'C','$country','$new_country','$city','$new_city')";
	mysql_query_decide($change_req_sql) or die("$change_req_sql".mysql_error_js());
}
?>
