<?php
/**********************************************************************************************
  FILENAME : editprofile.php
  DESCRIPTION : Allows the user to edit their personal details
  MODIFIED BY : Rahul Tara
  MODIFIED ON : 25 May,2005 
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
	include("js_editprofile_change_log.php");
//	include("contact.inc");
	$db=connect_db();

	/****Added By Shakti Srivastava to link tracking**************************/
	link_track("editprofile.php");
	/**************************************************************************/
	$db=connect_db();
	$data=authenticated($checksum);
	if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
	{
		$fromprofilepage=1;
		mysql_select_db_js('marriage_bureau');
		include_once('../marriage_bureau/connectmb.inc');
		$mbdata=authenticatedmb($mbchecksum);
		if(!$mbdata)timeoutmb();
		$smarty->assign("source",$mbdata["SOURCE"]);
		$smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
		mysql_select_db_js('newjs');
		//$data=login_every_user($profileid);
		$mbureau="bureau1";
	}
	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
       $smarty->assign("bms_topright",18);
       $smarty->assign("bms_right",28);
       $smarty->assign("bms_bottom",19);
       $smarty->assign("bms_left",24);
       $smarty->assign("bms_new_win",32);
	//$regionstr=8;
	//include_once("../bmsjs/bms_display.php");
	/************************************************End of Portion of Code*****************************************/
	$lang=$_COOKIE['JS_LANG'];
	if($lang=="deleted")
		$lang="";
	
	if($data || $crmback=='admin')
	{
		if($CMDsubmit)
		{
			// add slashes to prevent quotes problem
			maStripVARS("addslashes");
			
			$Email=trim($Email);
			
			$is_error=0;

			//Added By lavesh for Caste Subcaste combination error.
			$combination_error=validate_combination($Caste,$Subcaste);
														     
                        if($combination_error)
                        {
                                $is_error++;
                                $smarty->assign("check_subcaste","Y");
                        }
			//Ends Here

			$Religion_temp = explode('|X|',$Religion);
                        $Religion = $Religion_temp[0];



			if ($crmback!='admin' || $inf_profile=='Y')
			{
                        	if($mbureau!="bureau1" && !checkemail1($Email))
                        	{
                                	$is_error++;
	                                $smarty->assign("check_email","1");
                        	}
			}
			if($crmback!='admin')
               			 $profileid=$data["PROFILEID"];
                        //$profileid=$data["PROFILEID"];


			if ($crmback!='admin' && $mbureau!="bureau1" || $inf_profile == 'Y')
			{
				$sql="select EMAIL from JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

				$emailrow1=mysql_fetch_row($result);

				if($emailrow1[0]!=$Email)
				{
					mysql_free_result($result);

					$sql="select count(*) from JPROFILE where EMAIL='$Email' and PROFILEID<>'$profileid'";

					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					$emailrow=mysql_fetch_row($result);

					$sql_d="select count(*) from OLDEMAIL where OLD_EMAIL='$Email' and PROFILEID<>'$profileid'";
					$result_d=mysql_query_decide($sql_d) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_d,"ShowErrTemplate");
					$emailrow_d=mysql_fetch_row($result_d);
					if($emailrow[0] > 0 ||  $emailrow_d[0]>0)
					{
						$is_error++;
						$smarty->assign("check_dup_email","Y");
					}
					mysql_free_result($result);
                        	}
			}
                        //mysql_free_result($result);

                        //Wrong or blank entry validation
                        if($mbureau!="bureau1" && $Relationship=="")
                        {
                                $is_error++;
                                $smarty->assign("check_relationship","Y");
                        }

                        if($Height=="")
                        {
                                $is_error++;
                                $smarty->assign("check_height","Y");
                        }

                        if($Marital_Status=="")
                        {
                                $is_error++;
                                $smarty->assign("check_marital","Y");
                        }

			if($Has_Children=="")
                        {
                                $is_error++;
                                $smarty->assign("check_children","Y");
                        }

                        if($Manglik_Status=="")
                        {
                                $is_error++;
                                $smarty->assign("check_manglik","Y");
                        }

                        if($Religion=="")
                        {
                                $is_error++;
                                $smarty->assign("check_religion","Y");
                        }

                        if($Mtongue=="")
                        {
                                $is_error++;
                                $smarty->assign("check_mtongue","Y");
                        }

			//Corrected By lavesh on 28 august 2006 as no. of error shown are wrong
                        /*if($Caste=="")
                        {
                                $is_error++;
                                $smarty->assign("check_caste","Y");
                        }*/

                        if($Caste)
                        {
                                $sql="SELECT PARENT from CASTE WHERE VALUE=$Caste";
                                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $myrow=mysql_fetch_row($result);
				mysql_free_result($result);
				
				if($myrow[0]!=$Religion)
				{
					$is_error++;
					$Caste="";
					$smarty->assign("check_caste","Y");
				}
                        }
                        else
                        {
                                $is_error++;
                                //$myrow[0]=-1;
                                $smarty->assign("check_caste","Y");
			}

			//Corrected By lavesh on 28 august 2006 as no. of error shown are wrong
                        /*if($myrow[0]!=$Religion)
                        {
                                $is_error++;
                                $Caste="";
                                $smarty->assign("check_caste","Y");
                        }*/

                        if($Country_Residence=="")
                        {
                                $is_error++;
                                $smarty->assign("check_countryres","Y");
                        }
			//If country of residence is India, city of residence 
			// should be entered by the user
			elseif($Country_Residence =="51") 
                        {
				if($City_India == "")
				{
					$is_error++;
					$smarty->assign("check_cityres","Y");
				}
				else
					$City_Res = $City_India;
                        }
                        //If country of residence is USA, city of residence
                        // should be entered by the user
			elseif($Country_Residence =="128")
			{
				if($City_USA == "")
				{
                                        $is_error++;
                                        $smarty->assign("check_cityres","Y");
				}
				else
					$City_Res = $City_USA;
			}
			else
			{
				$City_Res = "";
			}

			if($Rstatus=="")
			{
				$is_error++;
				$smarty->assign("check_rstatus","Y");
			}

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

			if($Smoke=="")
			{
                        	$is_error++;
                        	$smarty->assign("check_smoke","Y");
                        }

                        if($Drink=="")
                        {
                        	$is_error++;
				$smarty->assign("check_drink","Y");
			}

                        if($Body_Type=="")
                        {
                                $is_error++;
                                $smarty->assign("check_bodytype","Y");
                        }

                        if($Complexion=="")
                        {
                                $is_error++;
                                $smarty->assign("check_complexion","Y");
                        }
			
			if($is_error > 0)
			{
				$smarty->assign("NO_OF_ERROR",$is_error);
				$smarty->assign("RADIOPRIVACY",$radioprivacy);
				
				// remove slashes
				maStripVARS("stripslashes");

				if($crmback!='admin')
					$profileid=$data["PROFILEID"];
				
				$sql="select USERNAME,GENDER,DTOFBIRTH from JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				
				$Username=$myrow["USERNAME"];
				$Gender=$myrow["GENDER"];
				$dob=explode("-",$myrow["DTOFBIRTH"]);

				$smarty->assign("USERNAME",$Username);
				$smarty->assign("GENDER",$Gender);
                        	$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
				$smarty->assign("EMAIL",$Email);
				$smarty->assign("RELATION",$Relationship);
				$height=create_dd($Height,"Height");
				$smarty->assign("HEIGHT",$height);
				$smarty->assign("MSTATUS",$Marital_Status);
				$smarty->assign("HAVECHILD",$Has_Children);
				$smarty->assign("MANGLIK",$Manglik_Status);
				$mtongue=create_dd($Mtongue,"Mtongue");
                                $smarty->assign("MTONGUE",$mtongue);

				$smarty->assign("RELIGION",populate_religion($Religion));	

				//Changed By lavesh all queries and computation is replaced by function

				$casteopt="";

				$casteopt=caste_populate_religion($Religion,$Caste);
				//Ends Here.				

                        	$smarty->assign("CASTE",$casteopt);
				
				$smarty->assign("SUBCASTE",$Subcaste);
				$country_residence=create_dd($Country_Residence,"Country_Residence");
				$smarty->assign("CITY_INDIA",create_dd($City_Res,"City_India"));
				$smarty->assign("CITY_USA",create_dd($City_Res,"City_USA"));
				$smarty->assign("COUNTRY_RES",$country_residence);

				$smarty->assign("cor",$cor);
				$smarty->assign("RES_STATUS",$Rstatus);
				$smarty->assign("EDUCATION",$Educ_Qualification);
				$smarty->assign("education_level",create_dd($Education_Level,"Education_Level_New"));
				$smarty->assign("occupation",create_dd($Occupation,"Occupation_New"));
				$smarty->assign("INCOME",create_dd($Income,"Income"));
	                        $smarty->assign("DIET",$Diet);
        	                $smarty->assign("SMOKE",$Smoke);
                	        $smarty->assign("DRINK",$Drink);
                        	$smarty->assign("COMPLEXION",$Complexion);
	                        $smarty->assign("BTYPE",$Body_Type);
        	                $smarty->assign("HANDICAPPED",$Phyhcp);

				$smarty->assign("data",$data["PROFILEID"]);
                        	$smarty->assign("regionstr",8);

				$smarty->assign("SHOWLINKS",$showlinks);
				$smarty->assign("CHECKSUM",$checksum);
				//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));


				if($crmback=='admin')
                        	{
					$smarty->assign("inf_profile",$inf_profile);
					$smarty->assign("company",$company);
					$smarty->assign("crmback","admin");
					$smarty->assign("profileid",$profileid);
					$smarty->assign("CRMBK_GENDER",$gender);
					$smarty->assign("cid",$cid);
				}

				if($lang)
				{
					
					$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));

					$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
					$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
					$smarty->display($lang."_editprofile_1.htm");
				}
				else
				{
					//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
					//$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
					if($mbureau=="bureau1")
					{
						$smarty->assign("mb_username_profile",$data["USERNAME"]);
						$smarty->assign("checksum",$data["CHECKSUM"]);
						$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
						$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
					}
					else
					{
						//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
						//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
					}
					$smarty->display("editprofile_1.htm");
				}
			}
			else 
			{

				if($crmback!='admin')
                                 	$profileid=$data["PROFILEID"];
//				$profileid=$data["PROFILEID"];
				
				$sql = "Select USERNAME,GENDER,DTOFBIRTH,AGE,EMAIL,RELATION,HEIGHT,MSTATUS,HAVECHILD,MANGLIK,RELIGION,MTONGUE,CASTE,SUBCASTE,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,DIET,SMOKE,DRINK,COMPLEXION,BTYPE,HANDICAPPED,PRIVACY,SCREENING,KEYWORDS from newjs.JPROFILE where PROFILEID ='$profileid'";
	
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				
				$curflag=$myrow["SCREENING"];
			
				if($crmback =='admin' && $inf_profile!='Y')
					$Email = $myrow["EMAIL"];
				else if($mbureau=="bureau1")
                                        $Email = $myrow["EMAIL"];
				else
				{
					if($myrow["EMAIL"]!=$Email)
						$curflag=removeFlag("EMAIL",$curflag);
				}

				if(trim($Subcaste)=="")
					$curflag=setFlag("SUBCASTE",$curflag);
				elseif($myrow["SUBCASTE"]!=$Subcaste)
					$curflag=removeFlag("SUBCASTE",$curflag);
					
				if(trim($Gothra)=="")
					$curflag=setFlag("GOTHRA",$curflag);
				elseif($myrow["GOTHRA"]!=$Gothra)
					$curflag=removeFlag("GOTHRA",$curflag);

				if(trim($Educ_Qualification)=="")
                                        $curflag=setFlag("EDUCATION",$curflag);
                                elseif($Educ_Qualification!=$myrow["EDUCATION"])
                                        $curflag=removeFlag("EDUCATION",$curflag);
	
				$today=date("Y-m-d");
				
				$sql = "Select OLD_VALUE from newjs.EDUCATION_LEVEL_NEW where VALUE = $Education_Level ";
				$result_education = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_education = mysql_fetch_array($result_education);
				$edu_level_old = $myrow_education["OLD_VALUE"];
/******added to include age,height,caste,occupation and residency status in keywords field*************************/
				 $Gender=$myrow["GENDER"];			
				 $sql="select LABEL from HEIGHT where VALUE='$Height'";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_height=mysql_fetch_array($res);
				$height_label=$myrow_height["LABEL"];
				$height_label=str_replace("&quot","\"",$height_label);
				$height=explode("(",$height_label);
				$height_label=$height[0];														     
				$sql="select LABEL from OCCUPATION where VALUE='$Occupation'";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_occ=mysql_fetch_array($res);
				$occ_label=$myrow_occ["LABEL"];
																						     
				if($City_India!='')
				      $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
				elseif($City_USA!='')
				     $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
				 $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				 $myrow_city=mysql_fetch_array($res);
				 $city_label=$myrow_city["LABEL"];
																						     
				 $sql="select SMALL_LABEL from CASTE where VALUE='$Caste'";
         			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
         			$myrow_caste=mysql_fetch_array($res);
         			$caste_label=$myrow_caste["SMALL_LABEL"];
        				
                                $keywords=$myrow["KEYWORDS"];
				$age=$myrow["AGE"];
				$hobby=strstr($keywords,"|");
				if($Gender=='F')
					$gender="Female";
				elseif($Gender=='M')
					$gender="Male";
				$keyword=addslashes(stripslashes($age.",".$gender.",".$height_label.",".$caste_label.",".$occ_label.",".$city_label.$hobby));

				if($crmback=="admin")
					editprofile1_change_log($profileid,$Email,$Relationship,$Height,$Marital_Status,$Has_Children,$Manglik_Status,$Religion,$Mtongue,$Caste,$Subcaste,$Country_Residence,$City_Res,$Rstatus,$edu_level_old,$Education_Level,$Educ_Qualification,$Occupation,$Income,$Diet,$Smoke,$Drink,$Complexion,$Body_Type,$Phyhcp,$radioprivacy,$cid,$company);
				$sql = "Update JPROFILE set EMAIL = '$Email',RELATION='$Relationship',HEIGHT='$Height',MSTATUS='$Marital_Status',HAVECHILD='$Has_Children',MANGLIK='" . addslashes($Manglik_Status) . "',RELIGION='$Religion',MTONGUE='$Mtongue',CASTE='$Caste',SUBCASTE='$Subcaste',COUNTRY_RES='$Country_Residence',CITY_RES='$City_Res',RES_STATUS='$Rstatus',EDU_LEVEL ='$edu_level_old',EDU_LEVEL_NEW = '$Education_Level',EDUCATION='$Educ_Qualification',OCCUPATION='$Occupation',INCOME='$Income',DIET='$Diet',SMOKE='$Smoke', DRINK='$Drink',COMPLEXION='$Complexion',BTYPE='$Body_Type',HANDICAPPED='$Phyhcp',PRIVACY='$radioprivacy',SCREENING='$curflag',KEYWORDS='$keyword',LAST_LOGIN_DT='$today',MOD_DT=now() where PROFILEID='$profileid'";
/*end of protion of code*/		
			mysql_query_decide($sql) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				showPart2($checksum,$showlinks);
			}
		}
		else 
		{
			/***********************ADDED BY SHOBHA FOR JS VOICE MODULE***********************************/
			if($crmback!='admin')
                		$profileid=$data["PROFILEID"];

			/*************************************************************/


			//$profileid=$data["PROFILEID"];

			//if(!$callValidate)
				$showlinks = "Y"; // Variable to enable links in left panel and sub header
			
			$sql = "Select USERNAME,GENDER,DTOFBIRTH,EMAIL,RELATION,HEIGHT,MSTATUS,HAVECHILD,MANGLIK,RELIGION,MTONGUE,CASTE,SUBCASTE,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,DIET,SMOKE,DRINK,COMPLEXION,BTYPE,HANDICAPPED,PRIVACY,INCOMPLETE from newjs.JPROFILE where PROFILEID ='$profileid'";
			
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			
			$INCOMPLETE = $myrow['INCOMPLETE'];
			$smarty->assign("USERNAME",$myrow["USERNAME"]);
			$smarty->assign("GENDER",$myrow["GENDER"]);
			$dob=explode("-",$myrow["DTOFBIRTH"]);
			$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
			$smarty->assign("EMAIL",$myrow["EMAIL"]);
			$smarty->assign("RELATION",$myrow["RELATION"]);
			$smarty->assign("HEIGHT",create_dd($myrow["HEIGHT"],"Height"));
			$smarty->assign("MSTATUS",$myrow["MSTATUS"]);
			$smarty->assign("HAVECHILD",$myrow["HAVECHILD"]);
			$smarty->assign("MANGLIK",$myrow["MANGLIK"]);
			$smarty->assign("RELIGION",populate_religion($myrow["RELIGION"]));
			$smarty->assign("MTONGUE",create_dd($myrow["MTONGUE"],"Mtongue"));

			//Changed By lavesh 
			$casteopt="";
			$Caste=$myrow["CASTE"];
			if(in_array($Caste,array(14,149,2,154,173)))
			{
				$Caste='';
			}
			$casteopt=caste_populate_religion($myrow["RELIGION"],$Caste);
			//Ends here.
	
                        $smarty->assign("CASTE",$casteopt);
			$smarty->assign("SUBCASTE",$myrow["SUBCASTE"]);
			$cor=$myrow["COUNTRY_RES"];
			$smarty->assign("COUNTRY_RES",create_dd($myrow["COUNTRY_RES"],"Country_Residence"));
			$smarty->assign("CITY_INDIA",create_dd($myrow["CITY_RES"],"City_India"));
			$smarty->assign("CITY_USA",create_dd($myrow["CITY_RES"],"City_USA"));
                        $smarty->assign("cor",$cor);
			
			$smarty->assign("RES_STATUS",$myrow["RES_STATUS"]);
			$smarty->assign("EDUCATION",$myrow["EDUCATION"]);
			$smarty->assign("education_level",create_dd($myrow["EDU_LEVEL_NEW"],"Education_Level_New"));
			$smarty->assign("occupation",create_dd($myrow["OCCUPATION"],"Occupation"));
			$smarty->assign("INCOME",create_dd($myrow["INCOME"],"Income"));
			$smarty->assign("DIET",$myrow["DIET"]);
			$smarty->assign("SMOKE",$myrow["SMOKE"]);
			$smarty->assign("DRINK",$myrow["DRINK"]);
			$smarty->assign("COMPLEXION",$myrow["COMPLEXION"]);
			$smarty->assign("BTYPE",$myrow["BTYPE"]);
			$smarty->assign("HANDICAPPED",$myrow["HANDICAPPED"]);
			$privacy=$myrow["PRIVACY"];

                        if($privacy=="")
                                $privacy="A";

                        $smarty->assign("RADIOPRIVACY",$privacy);
			
			$smarty->assign("SHOWLINKS",$showlinks);			
			
			$smarty->assign("data",$data["PROFILEID"]);
			$smarty->assign("regionstr",8);	

			$smarty->assign("CHECKSUM",$checksum);
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));


			if($crmback=='admin')
                        {
				if($company)
                                        $smarty->assign("company",$company);

				$sql = "SELECT COUNT(*) AS CNT FROM infovision.INFOVISION_ADMIN WHERE PROFILEID='$profileid' AND TYPE='I'";
				$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row = mysql_fetch_array($res);

				$sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row1 = mysql_fetch_array($res);
				

				if ($row['CNT'] > 0 && strstr($row1['EMAIL'],'@jsxyz.com'))
					$inf_profile = 'Y';

				$smarty->assign("inf_profile",$inf_profile);
                                $smarty->assign("crmback","admin");
                                $smarty->assign("profileid",$profileid);
                                $smarty->assign("CRMBK_GENDER",$gender);
				$smarty->assign("INCOMPLETE",$INCOMPLETE);
				$smarty->assign("cid",$cid);
                        }

			if($lang)
			{
				$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
				$smarty->display($lang."_editprofile_1.htm");
			}
			else
			{
				if($mbureau=="bureau1")
				{
					$smarty->assign("mb_username_profile",$data["USERNAME"]);
					$smarty->assign("checksum",$data["CHECKSUM"]);
					$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
				else
				{
					//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
					//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				}
				//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				//$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->display("editprofile_1.htm");
			}
		}
	}
	else 
	{
		TimedOut();
	}
	
	function showPart2($checksum,$showlinks)
	{
		global $crmback , $cid , $profileid , $company , $INCOMPLETE;
		global $smarty;
		include("editprofile1.php");
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
