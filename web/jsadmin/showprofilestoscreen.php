<?php
/**
*       Filename        :       showprofilestoscreen.php
*       Included        :       time.php
*       Description     :       displays the photo profiles to be screened to the operator
*       Created by      :       Anmol
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
include ("time.php");
	
if(authenticated($cid))
{
	$name= getname($cid);
	//select all the new photo profiles that are to be screened by this operator
	$sql =" SELECT jsadmin.MAIN_ADMIN.PROFILEID,SUBSCRIPTION_TYPE,jsadmin.MAIN_ADMIN.USERNAME,ALLOT_TIME,SUBMIT_TIME,HAVEPHOTO from jsadmin.MAIN_ADMIN,newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND ALLOTED_TO='$name' AND jsadmin.MAIN_ADMIN.SCREENING_TYPE='P' AND jsadmin.MAIN_ADMIN.SUBMITED_TIME='0000-00-00 00:00:00'";
	$result= mysql_query_decide($sql) or die(mysql_error_js());
	while($myrow=mysql_fetch_array($result))
	{
		//Get the remaining time left for screening the photo profile 
		$receivetime_est=$myrow['ALLOT_TIME'];
		$receivetime=getIST($receivetime_est);
		$submittime_est=$myrow['SUBMIT_TIME'];
		$status_color = get_status_color($submittime_est,$time_diff);
		$submittime=getIST($submittime_est);

		if($myrow["SUBSCRIPTION_TYPE"])//if it is a paid user's photo profile
			$bandcolor="fieldsnewgreen";
		else
			$bandcolor="fieldsnew";	
		if($myrow["HAVEPHOTO"]=='U')
		{
			$newphotousersarr[] = array("USERNAME" => $myrow["USERNAME"],
					 "PROFILEID" => $myrow["PROFILEID"],
					 "RECV_DT" => $receivetime,
					 "SUBMIT_DT" => $submittime,					 
					 "bandcolor" => $bandcolor,
					 "remaining_time" => $time_diff,
					 "status_color" => $status_color);
		}
		elseif($myrow["HAVEPHOTO"]=='Y' || $myrow["HAVEPHOTO"]=='E')
		{
			$editphotousersarr[] = array("USERNAME" => $myrow["USERNAME"],
					 "PROFILEID" => $myrow["PROFILEID"],
					 "RECV_DT" => $receivetime,
					 "SUBMIT_DT" => $submittime,					 
					 "bandcolor" => $bandcolor,
					 "remaining_time" => $time_diff,
					 "status_color" => $status_color);			
		} 
	}			 
	$smarty->assign("newphotousersarr",$newphotousersarr);				 
	$smarty->assign("editphotousersarr",$editphotousersarr);
	$smarty->assign("username",$username);
	$smarty->assign("cid",$cid);
	$smarty->display("showprofilestoscreen.tpl");		
}
else //user timed out
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}	

?>
