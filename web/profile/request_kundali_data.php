<?php
/*********************************************************************************************
* FILE NAME   : request_kundali_data.php
* DESCRIPTION : Request for Kundali data if the data is not available for the user.
* DATE        : May 25, 2005
* MADE BY     :	Kush Asthana
*********************************************************************************************/


include("connect.inc");
include("contact.inc");
$db=connect_db();
$data=authenticated($checksum);

/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);

//$regionstr=8;
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/
//$db=connect_db();

//print_r($data);
if($data)
{
	$arr=explode("i",$profilechecksum);
        if(md5($arr[1])!=$arr[0])
        {
               showProfileError();
        }
        else
               $profileid_second=$arr[1];
	$msg="";
	if($Submit || $flag=="BLOCATOR") // flag is set through match_kundali_data.php for wrong city
	{
		$sql="SELECT USERNAME,EMAIL,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_second'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		if(mysql_num_rows($result)>0)
		{
			if($data['GENDER']=='M')
				$gen="his";
			else
				$gen="her";
			$msg.="<html><body>Dear $myrow[USERNAME],<br><br><b>Congratulations!!!</b><br><br>A JeevanSathi Member <b>$data[USERNAME]</b> is interested in you and wants to match $gen horoscope with yours. So, kindly mention your ";
			if(!$myrow['BTIME'])
				$msg_arr[] =	"<b>Time of Birth</b>";
			if(!$myrow['CITY_BIRTH']||!$myrow['COUNTRY_BIRTH'])
				$msg_arr[] =	"<b>Place of Birth</b>";
			if(count($msg_arr)>0)
				$msg_arr_str= implode(" and ",$msg_arr);
			if($msg_arr_str=='')
				$msg_arr_str="<b>Place of Birth</b>";
			
			$msg .= "$msg_arr_str in your Astro Data.<br><br><a href=\"http://www.jeevansathi.com/P/activate_astro.php\" target=\"_blank\">Click here </a>to edit Astro Data.<br><br><br>Regards,<br>The JeevanSathi.com Team</body></html>";

			if(trim($myrow['EMAIL']) && ($data["PROFILEID"]!=$profileid_second))
				send_email($myrow['EMAIL'],$msg,'Astro Data Request','webmaster@jeevansathi.com');
		}

		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("USERNAME",$myrow['USERNAME']);
		$smarty->display('astro_request_sent.htm');
		
//sendmail function to be included here after overall testing
	}
	else
	{
		$sql="SELECT USERNAME,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_second'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);

		
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
		$smarty->assign("DTOFBIRTH",$dt_birth);
		$smarty->assign("HOUR_BIRTH",$hour_birth);
		$smarty->assign("MIN_BIRTH",$min_birth);
		$smarty->assign("CITY_BIRTH",$city_birth);
		$smarty->assign("COUNTRY_BIRTH",$country_birth[0]);
		$smarty->assign("USERNAME",$myrow['USERNAME']);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->display("kundali_matcher_form1.htm");
	}
}
else
{
	TimedOut();
}
?>
