<?php
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","profile/edunoccupation.php in USE",$msg);
/**********************************************************************************************
*  	FILENAME : edunoccupation.php
*
*  	CREATED BY : Shobha Kumari
*
*	CREATED ON : 18.11.2005 
*
*	DESCRIPTION : Allows the user to edit his/her education/occupation details
**********************************************************************************************/

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");

	$db=connect_db();
	
	$data=authenticated($checksum);

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
		if($CMDsubmit)
		{
			// add slashes to prevent quotes problem
			maStripVARS("addslashes");

			$is_error=0;

			if($Education_Level=="")
                        {
                        	$is_error++;
                                $smarty->assign("check_education_level","Y");
			}	

			if($Occupation=="")
			{
				$is_error++;
				$smarty->assign("check_occupation","Y");
			}
			
			if($Income=="")
			{
				$is_error++;
				$smarty->assign("check_income","Y");
			}
			if($is_error > 0)
			{
				$smarty->assign("NO_OF_ERROR",$is_error);
				
				// remove slashes
				maStripVARS("stripslashes");
				
				$profileid=$data["PROFILEID"];
				
	                        $smarty->assign("education_level",create_dd($Education_Level,"Education_Level_New"));
				$smarty->assign("EDUCATION",$Educ_Qualification);
        	                $smarty->assign("occupation",create_dd($Occupation,"Occupation_New"));
				$smarty->assign("INCOME",create_dd($Income,"Income"));
				$smarty->assign("JOB_INFO",$Job_Info);

				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

				$smarty->display("edunoccupation.htm");
			}
			else 
			{
				$profileid=$data["PROFILEID"];
				
				$sql = "Select USERNAME EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,JOB_INFO,SCREENING from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$profileid'";
	
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				
				$curflag=$myrow["SCREENING"];
				
				if(trim($Educ_Qualification)=="")
                                        $curflag=setFlag("EDUCATION",$curflag);
                                elseif($Educ_Qualification!=$myrow["EDUCATION"])
                                        $curflag=removeFlag("EDUCATION",$curflag);
				if(trim($Job_Info)=="")
					$curflag=setFlag("JOB_INFO",$curflag);
				elseif($Job_Info!=$editrow["JOB_INFO"])
					$curflag=removeFlag("JOB_INFO",$curflag);
	
				$today=date("Y-m-d");
				
				$sql = "Select OLD_VALUE from newjs.EDUCATION_LEVEL_NEW where VALUE = $Education_Level ";
				$result_education = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_education = mysql_fetch_array($result_education);
				$edu_level_old = $myrow_education["OLD_VALUE"];
	
				$sql="select LABEL from OCCUPATION where VALUE='$Occupation'";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_occ=mysql_fetch_array($res);
				$occ_label=$myrow_occ["LABEL"];

				$sql = "Update JPROFILE set EDU_LEVEL ='$edu_level_old',EDU_LEVEL_NEW = '$Education_Level',EDUCATION='$Educ_Qualification',OCCUPATION='$Occupation',INCOME='$Income',JOB_INFO='$Job_Info',SCREENING='$curflag',LAST_LOGIN_DT='$today',MOD_DT=now() where PROFILEID='$profileid'";
				mysql_query_decide($sql) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?checksum=$checksum&HAVEEDUINFO=Y\"></body></html>";

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

			$sql = "Select USERNAME EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,JOB_INFO from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$profileid'";
			
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			
			$smarty->assign("USERNAME",$myrow["USERNAME"]);
			$smarty->assign("JOB_INFO",$myrow["JOB_INFO"]);
			$smarty->assign("education_level",create_dd($myrow["EDU_LEVEL_NEW"],"Education_Level_New"));
			$smarty->assign("EDUCATION",$myrow["EDUCATION"]);
			$smarty->assign("occupation",create_dd($myrow["OCCUPATION"],"Occupation"));
			$smarty->assign("INCOME",create_dd($myrow["INCOME"],"Income"));
			
			$smarty->assign("SHOWLINKS",$showlinks);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->display("edunoccupation.htm");
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
