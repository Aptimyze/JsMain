<?php
/**********************************************************************************************
  FILENAME    : increase_response_subcaste.php
  DESCRIPTION : Ask the user to edit some personal data upto max of 5 times.Done to increase response for user as most of                    search are based on specifuc caste and subcaste.
  INCLUDE     : connect.inc
  CREATED BY  : Lavesh Rawat
  CREATED ON  : 12 Septeber,2006
**********************************************************************************************/

include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");

$db=connect_db();
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='increase_response_subcaste is being hit. We can wrap this to JProfileUpdateLib';
               $subject="increase_response_subcaste";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
//Bms code
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
//Ends here.

$data=authenticated($checksum);
if($data)
{

	if($Submit=="Update my Caste Details")
	{
		$data=authenticated($checksum);
		$pid=$data["PROFILEID"];

		if($Caste)
		{
			$combination_error=validate_combination($Caste,$Subcaste);
															     
			if($combination_error)
			{
				$is_error++;
				$smarty->assign("check_subcaste","Y");
			}
		}
		else
		{
			 $is_error++;
			 $smarty->assign("check_caste",'Y');
		}

		if($Mtongue=='')
		{
			$is_error++;
			$smarty->assign("check_mtongue",'Y');
		}
		
		if($is_error>0)
		{
			$smarty->assign("NO_OF_ERROR",$is_error);

			$caste=caste_populate_religion($religion,$Caste);
			$smarty->assign("caste",$caste);

			$Subcaste=stripslashes($Subcaste);

			$smarty->assign("subcaste",$Subcaste);
			$smarty->assign("gothra",$Gothra);
			$smarty->assign("caste_value",$Caste);

			$nak_array=loadnakshatra($Mtongue,$Nakshatram);
			$smarty->assign("nak_array",$nak_array);

			$mtongue=create_dd($Mtongue,"Mtongue");
			$smarty->assign("mtongue",$mtongue);

			$smarty->assign("username",$row['USERNAME']);
			$smarty->assign("CHECKSUM",$checksum);
		}
		else
		{
			$Gothra=addslashes(stripslashes($Gothra));
			$Gothra=redo_gothra($Gothra);
			$Subcaste=addslashes(stripslashes($Subcaste));
			$Nakshatram=addslashes(stripslashes($Nakshatram));

			$sql="SELECT SUBCASTE,SCREENING,GOTHRA FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
			$res = mysql_query_decide($sql) or logError("Error in getting code value after submit",$sql);
			$row = mysql_fetch_array($res);
																     
			$curflag=$row["SCREENING"];

			if(trim($Subcaste)=="")
				$curflag=setFlag("SUBCASTE",$curflag);
			elseif($row["SUBCASTE"]!=stripslashes($Subcaste))
				$curflag=removeFlag("SUBCASTE",$curflag);

			if(trim($Gothra)=="")
				$curflag=setFlag("GOTHRA",$curflag);
			elseif($row["GOTHRA"]!=$Gothra)
				$curflag=removeFlag("GOTHRA",$curflag);
			
			$sql="UPDATE newjs.INCREASE_RESPONSE SET UPDATE_RECORD='Y' WHERE PROFILEID='$pid'";
			mysql_query_decide($sql) or logError("Error ",$sql);

			$sql="UPDATE newjs.JPROFILE SET CASTE='$Caste',SUBCASTE='$Subcaste',MTONGUE='$Mtongue',GOTHRA='$Gothra',NAKSHATRA='$Nakshatram',SCREENING='$curflag' WHERE PROFILEID='$pid'";
			mysql_query_decide($sql) or logError("Error in updating",$sql);

			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?CHECKSUM=$checksum\"></body></html>";
			exit;
		}

	}
	else
	{
		$data=authenticated($checksum);
		$pid=$data['PROFILEID'];

		$sql="UPDATE newjs.INCREASE_RESPONSE SET COUNT=COUNT+1 WHERE PROFILEID='$pid'";
		mysql_query_decide($sql) or logError("Error in getting code value",$sql);
		if(mysql_affected_rows_js()==0)
		{
			$sql="INSERT INTO newjs.INCREASE_RESPONSE(PROFILEID,COUNT) VALUES('$pid','1')";
			mysql_query_decide($sql) or logError("Error in getting code value",$sql);		
		}
		
		$sql="SELECT RELIGION,SUBCASTE,CASTE,MTONGUE,USERNAME,NAKSHATRA,GOTHRA FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
		$res = mysql_query_decide($sql) or logError("Error in getting code value",$sql);
		$row = mysql_fetch_array($res);

		$Nakshatram=$row['NAKSHATRA'];
		$nak_array=loadnakshatra($row['MTONGUE'],$Nakshatram);
		$smarty->assign("nak_array",$nak_array);

		$religion=$row['RELIGION'];
		$Mtongue=$row['MTONGUE'];
		$Caste=$row['CASTE'];

		$caste=caste_populate_religion($religion,$Caste);
		$smarty->assign("caste",$caste);

		$mtongue=create_dd($Mtongue,"Mtongue");
		$smarty->assign("mtongue",$mtongue);

		$data=authenticated($checksum);

		$smarty->assign("username",$row['USERNAME']);
		$smarty->assign("gothra",$row['GOTHRA']);
		$smarty->assign("subcaste",$row['SUBCASTE']);
		$smarty->assign("CHECKSUM",$checksum);
	}
	login_relogin_auth($data);
	$smarty->assign("religion",$religion);
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->display("increase_response_subcaste.htm");
}

else
{
	timedOut();

}


?>

