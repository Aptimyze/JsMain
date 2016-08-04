<?php
/**********************************************************************************************
*       FILENAME : yourfamily.php
*
*       CREATED BY : Shobha Kumari
*
*       CREATED ON : 18.11.2005
*
*       DESCRIPTION : Allows the user to edit his/her family related details
**********************************************************************************************/

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	require_once("connect.inc");
require_once(JsConstants::$docRoot."/commonFiles/flag.php");
	
	$db=connect_db();	
	$data=authenticated($checksum);
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='yourfamily is being hit. We can wrap this to JProfileUpdateLib';
               $subject="yourfamily";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
	/*************************************Portion of Code added for display of Banners*******************************/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);
        /************************************************End of Portion of Code*****************************************/
	if($data)
	{
		if($Fsubmit)
		{
			// add slashes to prevent quotes problem
			maStripVARS("addslashes");
			
			$profileid=$data["PROFILEID"];
			
			//blank entries validation
			$is_error=0;
			if(trim($Family_Back) == "")
			{
				$is_error++;
				$smarty->assign("check_family_back","Y");
			}

			if(trim($Parent_City_Same) == "")
			{
				$is_error++;
				$smarty->assign("check_parent_city","Y");
			}

			if($is_error > 0)
			{
				$smarty->assign("NO_OF_ERROR",$is_error);
				// remove slashes
				maStripVARS("stripslashes");

				$smarty->assign("FAMILY_BACK",create_dd($Family_Back,"Family_Back"));
				$smarty->assign("FAMILY_VALUES",$Family_Values);
				$smarty->assign("GOTHRA",$Gothra);
				$smarty->assign("FATHER_INFO",$Father_Info);
				$smarty->assign("SIBLING_INFO",$Sibling_Info);
				$smarty->assign("PARENT_CITY_SAME",$Parent_City_Same);
				$smarty->assign("FAMILYINFO",$Family);
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		
				$smarty->display("yourfamily.htm");
			}
			else 
			{
				$sql="select GOTHRA,FATHER_INFO,SIBLING_INFO,FAMILYINFO,SCREENING from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			    	
				$editrow=mysql_fetch_array($result);

				$curflag=$editrow["SCREENING"];
			    	
				if(trim($Gothra)=="")
					$curflag=setFlag("GOTHRA",$curflag);
				elseif($Gothra!=$editrow["GOTHRA"])
					$curflag=removeFlag("GOTHRA",$curflag);

				if(trim($Father_Info)=="")
					$curflag=setFlag("FATHER_INFO",$curflag);
				elseif($Father_Info!=$editrow["FATHER_INFO"])
					$curflag=removeFlag("FATHER_INFO",$curflag);

				if(trim($Sibling_Info)=="")
					$curflag=setFlag("SIBLING_INFO",$curflag);
				elseif($Sibling_Info!=$editrow["SIBLING_INFO"])
					$curflag=removeFlag("SIBLING_INFO",$curflag);

				if(trim($Family)=="")
					$curflag=setFlag("FAMILYINFO",$curflag);
				elseif($Family!=$editrow["FAMILYINFO"])
					$curflag=removeFlag("FAMILYINFO",$curflag);

				$today=date("Y-m-d");

				$sql = "UPDATE newjs.JPROFILE SET FAMILY_VALUES='$Family_Values',FAMILY_BACK='$Family_Back',GOTHRA='$Gothra',FATHER_INFO='$Father_Info',SIBLING_INFO = '$Sibling_Info',PARENT_CITY_SAME='$Parent_City_Same',FAMILYINFO='$Family',SCREENING='$curflag',MOD_DT=now(),LAST_LOGIN_DT='$today' WHERE PROFILEID='$profileid' ";
				$result= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?checksum=$checksum&HAVEFAMILYINFO=Y\"></body></html>";

				/*$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
					
				$smarty->display("regcomplete.htm");*/
			}
		}
		else
		{
			$profileid=$data["PROFILEID"];
			
			$sql = "Select FAMILY_BACK,FAMILY_VALUES,GOTHRA,FATHER_INFO,SIBLING_INFO,PARENT_CITY_SAME,FAMILYINFO from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";	
			
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);

			$smarty->assign("FAMILY_BACK",create_dd($myrow["FAMILY_BACK"],"Family_Back"));
			$smarty->assign("FAMILY_VALUES",$myrow["FAMILY_VALUES"]);
			$smarty->assign("GOTHRA",$myrow["GOTHRA"]);	
			$smarty->assign("FATHER_INFO",$myrow["FATHER_INFO"]);
			$smarty->assign("SIBLING_INFO",$myrow["SIBLING_INFO"]);
			$smarty->assign("PARENT_CITY_SAME",$myrow["PARENT_CITY_SAME"]);
			$smarty->assign("FAMILYINFO",$myrow["FAMILYINFO"]);

			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			
			$smarty->display("yourfamily.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
