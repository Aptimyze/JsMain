<?php
/*
*	Name		:	unassigned_zone.php
*	Description	:	This file displays the zone stats of unassigned profiles
*	Created By	:	Tripti Singh
*	Created On	:	7th July, 2006
*	Copyright 2001, Info Edge Pvt. Ltd.
*/

include("connect.inc");
include("../jsadmin/time1.php");
$db=connect_misdb();
$db2=connect_master();
$data=authenticated($cid);

if(isset($data))
{
	$zone_arr=array('Comfort','Rush','RedAlert','Expired','Total');
	$smarty->assign("zone_arr",$zone_arr);
	$table = "newjs.JPROFILE";	//Data is to be analyzed from this table
	$now=date('Y-m-d H:i:s');
	$now=getIST($now);		//Get the IST corresponding to server time
	$total=Array(0,0,0,0,0);	//create an array
	if($from=="MP")
	{
		$sql="SELECT DATE AS MOD_DT FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON PHOTOS_FROM_MAIL.ID=SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL AND ATTACHMENT='Y'";
	}
	if($from=="P")
	{
		if($val=="new")
			$sql="SELECT PHOTODATE AS MOD_DT FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (HAVEPHOTO='U' AND PHOTOSCREEN=0)";
		elseif($val=="edit")
			$sql="SELECT PHOTODATE AS MOD_DT FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (HAVEPHOTO='Y' AND PHOTOSCREEN=0)";
	}
	else
	{
		if($val=="new")
		$sql = "SELECT J.PROFILEID,MOD_DT FROM $table J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID WHERE ACTIVATED='N' AND INCOMPLETE='N' AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y') AND activatedKey=1 UNION SELECT J.PROFILEID,MOD_DT FROM $table J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON A.PROFILEID=J.PROFILEID WHERE A.PROFILEID IS NOT NULL AND activatedKey=1";
	elseif($val=="edit")
		$sql="SELECT MOD_DT FROM $table J USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = J.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON J.PROFILEID = A.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ((ACTIVATED = 'Y' && A.PROFILEID IS NULL && activatedKey=1) AND SCREENING < '1099511627775')";
	}
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_assoc($result))
	{
		//Fetch MOD_DT and calculate the Submit time taking holidays etc. into account
		$mod_dt=$row['MOD_DT'];
		$submit_dt=newtime($mod_dt,0,$screen_time,0);
		$mod_dt=getIST($mod_dt);
		$submit_dt=getIST($submit_dt);
		//Extract all values in submit date in separate variables
		$arr=explode(' ',$submit_dt);
		$date=explode('-',$arr[0]);
		$time=explode(':',$arr[1]);
		$year=$date[0];
		$month=$date[1];
		$day=$date[2];
		$hour=$time[0];
		$minute=$time[1];
		$second=$time[2];
		//Make a new time 
		$new_int_time=mktime($hour-10,$minute,$second,$month,$day,$year);
		$comfort=date('Y-m-d H:i:s',$new_int_time);
		$comfort=getIST($comfort);
		//Make a new time
		$new_int_time=mktime($hour-8,$minute,$second,$month,$day,$year);
		$rush=date('Y-m-d H:i:s',$new_int_time);
		$rush=getIST($rush);
		if($now>=$mod_dt && $now<$comfort)	//Comfort zone: 0 to 2 hrs
			$total[0]++;
		elseif($now>=$comfort && $now<$rush)	//Rush zone:	2 to 4 hrs
			$total[1]++;
		elseif($now>=$rush && $now<$submit_dt)	//Red Alert:	4 to 12 hrs
			$total[2]++;
		else					//Expired zone:	After 12 hours
			$total[3]++;
		$total[4]++;				//Total
	}
	$smarty->assign("total",$total);	//Array for total profiles
	$smarty->display("unassigned_zone.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.htm");
}
?>
