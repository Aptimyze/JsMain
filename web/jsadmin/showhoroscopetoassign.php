<?php

/**
*       Filename        :       showhoroscopetoassign.php
*       Description     :       It displays the horoscope profiles that are to be assigned by admin
*       Created by      :       Gaurav Arora
**/

include ("connect.inc");
include ("time.php");

if(authenticated($cid))
{	
	$operator_name=getname($cid);
	$smarty->assign("operator_name",$operator_name);
	if($user=="n")//show photo profiles of new users (at login new profiles are shown by def.)
	{
		$flag="U";
		$smarty->assign("showingeditedrecords",0);
	}
	elseif($user=="o")//show photo profiles of existing users
	{
//		$flag="'E','Y'";
		$flag="Y";
		$smarty->assign("showingeditedrecords",1);
	}
	$count_under_screening=0;
	$count_to_be_screened=0;
	//get the count of profiles to be screened
	//$sql="SELECT count( * ) AS count FROM newjs.JPROFILE USE INDEX(PHOTOSCREEN) LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE newjs.JPROFILE.PHOTOSCREEN NOT IN ('7', '15', '23') AND newjs.JPROFILE.PHOTOSCREEN<31 AND newjs.JPROFILE.HAVEPHOTO='$flag' AND (jsadmin.MAIN_ADMIN.PROFILEID IS NULL OR jsadmin.MAIN_ADMIN.SCREENING_TYPE<>'P')";

	//$sql="select count(*) as count from newjs.HOROSCOPE_FOR_SCREEN LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.HOROSCOPE_FOR_SCREEN.PROFILEID where (newjs.HOROSCOPE_FOR_SCREEN.UPLOADED = '') AND (jsadmin.MAIN_ADMIN.PROFILEID IS NULL OR jsadmin.MAIN_ADMIN.SCREENING_TYPE<>'H')";
	$sql="select count(*) as count from newjs.HOROSCOPE_FOR_SCREEN LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.HOROSCOPE_FOR_SCREEN.PROFILEID where newjs.HOROSCOPE_FOR_SCREEN.UPLOADED = '' AND jsadmin.MAIN_ADMIN.PROFILEID IS NULL";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	$myrow=mysql_fetch_array($result);
	$count_to_be_screened=$myrow["count"];		
	
	$sql =" SELECT SQL_CACHE USERNAME,PRIVILAGE from jsadmin.PSWRDS WHERE PRIVILAGE like '%HU%' ";
	$result = mysql_query_decide($sql) or die(mysql_error_js());
	$i=0;
	while($myrow=mysql_fetch_array($result))
	{		
//		$privilage=$myrow['PRIVILAGE'];
//		$priv=explode("+",$privilage);
//		if(count($priv)>2)
//		{}
//		else
//		{
			$keys[$myrow["USERNAME"]] = $i;
			$photo_operators[]=$myrow["USERNAME"];
			$photo_operator_total[]=0;
			$i++;
//		}
	}

	$sql1="select count(*) as count, ALLOTED_TO from jsadmin.MAIN_ADMIN,jsadmin.PSWRDS where jsadmin.MAIN_ADMIN.ALLOTED_TO=jsadmin.PSWRDS.USERNAME and PRIVILAGE like '%HU%' and SCREENING_TYPE='H' and SUBMITED_TIME='0000-00-00 00:00:00' group by ALLOTED_TO";
	//$sql1="select count(*) as count from jsadmin.MAIN_ADMIN where SCREENING_TYPE='P' and ALLOTED_TO='$myrow[USERNAME]' and SUBMITED_TIME='0000-00-00 00:00:00'";
	$result1=mysql_query_decide($sql1) or die(mysql_error_js($sql1));
	while ($myrow1=mysql_fetch_array($result1))
	{		
		$photo_operator_total[$keys[$myrow1["ALLOTED_TO"]]]=$myrow1["count"];
		$count_under_screening=$count_under_screening+$myrow1["count"];
		$i++;
	}	
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->assign("photo_operators",$photo_operators);
	$smarty->assign("photo_operator_total",$photo_operator_total);
	$smarty->assign("count_under_screening",$count_under_screening);
	$smarty->assign("count_to_be_screened",$count_to_be_screened);
	$smarty->display("showhoroscopetoassign.htm");
}
else//user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);		
	$smarty->display("jsadmin_msg.tpl");
}

?>	
