<?php
/*********************************************************************************************
* FILE NAME   : match_kundali_data.php
* DESCRIPTION : Page after Kundali match link is clicked on viewprofile page.
* DATE        : May 25, 2005
* MADE BY     :	Kush Asthana
*********************************************************************************************/


include("connect.inc");
include("contact.inc");
include("astrofunctions.php");
$db=connect_db();
$data=authenticated($checksum);
/***************************************** CODE FOR BMS ***************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);
/*************************************************************************************/
if($data)
{
        $profileid_first=$data['PROFILEID'];
	$arr=explode("i",$profilechecksum);
        if(md5($arr[1])!=$arr[0])
        {
               showProfileError();
        }
        else
               $profileid_second=$arr[1];
       
         $sql="SELECT USERNAME,GENDER,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_first'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow1=mysql_fetch_array($result);
	if($myrow1['GENDER']=="M")
		$fpgender="Male";
	else
		$fpgender="Female";
	list($fpdoby,$fpdobm,$fpdobd)=explode("-",$myrow1['DTOFBIRTH']);
	list($fptobh,$fptobm)=explode(":",$myrow1['BTIME']);
	$fpcity=$myrow1['CITY_BIRTH'];
	$fpcountry_temp=label_select('COUNTRY',$myrow1['COUNTRY_BIRTH']);
	$fpcountry=$fpcountry_temp[0];
											 
	$smarty->assign("FPGENDER",$fpgender);
	$smarty->assign("FPCITY",$fpcity);
	$smarty->assign("FPCOUNTRY",$fpcountry);
	$smarty->assign("FPNAME",$myrow1['USERNAME']);
	$smarty->assign("FPDOBD",$fpdobd);
	$smarty->assign("FPDOBM",$fpdobm);
	$smarty->assign("FPDOBY",$fpdoby);
	$smarty->assign("FPTOBH",$fptobh);
	$smarty->assign("FPTOBM",$fptobm);

	$sql="SELECT USERNAME,GENDER,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_second'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);

	if($myrow['GENDER']=="M")
		$gender="Male";
	elseif($myrow['GENDER']=="F")
		$gender="Female";
	list($year,$month,$day)=explode("-",$myrow['DTOFBIRTH']);	
	$dt_birth=my_format_date($day,$month,$year);
	if(!$myrow['BTIME'])
		$smarty->assign("NOBTIME","Y");
	else
		list($hour_birth,$min_birth)=explode(":",$myrow['BTIME']);
	if(!$myrow['CITY_BIRTH'])
		$smarty->assign("NOCITY_BIRTH","Y");
	else
		$city_birth=$myrow['CITY_BIRTH'];		
	if(!$myrow['COUNTRY_BIRTH'])
		$smarty->assign("NOCOUNTRY_BIRTH","Y");
	else
		$country_birth=label_select('COUNTRY',$myrow['COUNTRY_BIRTH']);		

	$checksum_astro=getChecksum($profileid_first);
	$smarty->assign("GENDER",$gender);
	$smarty->assign("DTOFBIRTH",$dt_birth);
	$smarty->assign("DOBDAY",$day);
	$smarty->assign("DOBMONTH",$month);
	$smarty->assign("DOBYEAR",$year);
	$smarty->assign("HOUR_BIRTH",$hour_birth);
	$smarty->assign("MIN_BIRTH",$min_birth);
	$smarty->assign("CITY_BIRTH",$city_birth);
	$smarty->assign("COUNTRY_BIRTH",$country_birth[0]);
	$smarty->assign("USERNAME",$myrow['USERNAME']);
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("CHECKSUM_ASTRO",$checksum_astro);
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->display("match_kundali.htm");
}
else
{
	TimedOut();
}
?>
