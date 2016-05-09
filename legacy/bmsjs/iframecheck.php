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
	
	include("../profile/connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
	
	$db=connect_db();
	
	$data=authenticated($checksum);
	/*************************************Portion of Code added for display of Banners*******************************/
	//$regionstr=8;
	//include_once("../bmsjs/bms_display.php");
	/************************************************End of Portion of Code*****************************************/
//	$db=connect_db();
	
	if($data)
	{	echo "here";
		if($CMDsubmit)
		{
			// add slashes to prevent quotes problem
			maStripVARS("addslashes");
			
			$is_error=0;
			$Religion_temp = explode('|X|',$Religion);
                        $Religion = $Religion_temp[0];

                        if(!checkemail1($Email))
                        {
                                $is_error++;
                                $smarty->assign("check_email","1");
                        }
                        $profileid=$data["PROFILEID"];

                        $sql="select EMAIL from JPROFILE where PROFILEID='$profileid'";
                        $result=mysql_query($sql) or logError("Due to some temporary problem your
 request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

                        $emailrow1=mysql_fetch_row($result);

                        if($emailrow1[0]!=$Email)
                        {
                                mysql_free_result($result);

                                $sql="select count(*) from JPROFILE where EMAIL='$Email' and PROFILEID<>'$profileid'";
                                $result=mysql_query($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $emailrow=mysql_fetch_row($result);

                                $sql_d="select count(*) from OLDEMAIL where OLD_EMAIL='$Email' and PROFILEID<>'$profileid'";
                                $result_d=mysql_query($sql_d) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_d,"ShowErrTemplate");
                                $emailrow_d=mysql_fetch_row($result_d);
                                if($emailrow[0] > 0 ||  $emailrow_d[0]>0)
                                {
                                        $is_error++;
                                        $smarty->assign("check_dup_email","Y");
                                }
                        }
                        mysql_free_result($result);

                        //Wrong or blank entry validation
                        if($Relationship=="")
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

                        if($Caste=="")
                        {
                                $is_error++;
                                $smarty->assign("check_caste","Y");
                        }

                        if($Caste)
                        {
                                $sql="SELECT PARENT from CASTE WHERE VALUE=$Caste";
                                $result=mysql_query($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $myrow=mysql_fetch_row($result);

				mysql_free_result($result);
                        }
                        else
                        {
                                $is_error++;
                                $myrow[0]=-1;
                                $smarty->assign("check_caste","Y");
			}

                        if($myrow[0]!=$Religion)
                        {
                                $is_error++;
                                $Caste="";
                                $smarty->assign("check_caste","Y");
                        }

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
				
				$profileid=$data["PROFILEID"];
				
				$sql="select USERNAME,GENDER,DTOFBIRTH from JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				
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
	                        $sql="select SQL_CACHE VALUE,LABEL from CASTE where PARENT='" .$Religion. "' order by SORTBY";
        	                $res=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                	        $casteopt="";
                        	while($mycasterow=mysql_fetch_array($res))
                        	{
                                	if($Caste==$mycasterow["VALUE"])
                                        	$casteopt.="<option value=\"" . $mycasterow["VALUE"] . "\" selected>" . $mycasterow["LABEL"] . "</option>";
	                                else
        	                                $casteopt.="<option value=\"" . $mycasterow["VALUE"] . "\">" . $mycasterow["LABEL"] . "</option>";
                	        }
                        	$smarty->assign("CASTE",$casteopt);
				
				$smarty->assign("SUBCASTE",$Subcaste);
				$country_residence=create_dd($Country_Residence,"Country_Residence");
				$smarty->assign("COUNTRY_RES",$country_residence);
				$smarty->assign("CITY_INDIA",create_dd($City_Res,"City_India"));
				$smarty->assign("CITY_USA",create_dd($City_Res,"City_USA"));

				$smarty->assign("cor",$cor);
				$smarty->assign("RES_STATUS",$Rstatus);
	                        $smarty->assign("education_level",create_dd($Education_Level,"Education_Level_New"));
				$smarty->assign("EDUCATION",$Educ_Qualification);
        	                $smarty->assign("occupation",create_dd($Occupation,"Occupation_New"));
				$smarty->assign("INCOME",create_dd($Income,"Income"));
	                        $smarty->assign("DIET",$Diet);
        	                $smarty->assign("SMOKE",$Smoke);
                	        $smarty->assign("DRINK",$Drink);
                        	$smarty->assign("COMPLEXION",$Complexion);
	                        $smarty->assign("BTYPE",$Body_Type);
        	                $smarty->assign("HANDICAPPED",$Phyhcp);
				
				$smarty->assign("SHOWLINKS",$showlinks);
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("data",$data);
				print_r($data);

				/*$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("head.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));*/
				$smarty->display("../bmsjs/editprofile_1.htm");
			}
			else 
			{	print_r($data);
				$profileid=$data["PROFILEID"];
				
				$sql = "Select USERNAME,GENDER,DTOFBIRTH,EMAIL,RELATION,HEIGHT,MSTATUS,HAVECHILD,MANGLIK,RELIGION,MTONGUE,CASTE,SUBCASTE,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,DIET,SMOKE,DRINK,COMPLEXION,BTYPE,HANDICAPPED,PRIVACY,SCREENING from newjs.JPROFILE where PROFILEID ='$profileid'";
	
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				
				$curflag=$myrow["SCREENING"];
				
				if($myrow["EMAIL"]!=$Email)
					$curflag=removeFlag("EMAIL",$curflag);
					
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
				$result_education = mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_education = mysql_fetch_array($result_education);
				$edu_level_old = $myrow_education["OLD_VALUE"];
			
				$sql = "Update JPROFILE set EMAIL = '$Email',RELATION='$Relationship',HEIGHT='$Height',MSTATUS='$Marital_Status',HAVECHILD='$Has_Children',MANGLIK='$Manglik_Status',RELIGION='$Religion',MTONGUE='$Mtongue',CASTE='$Caste',SUBCASTE='$Subcaste',COUNTRY_RES='$Country_Residence',CITY_RES='$City_Res',RES_STATUS='$Rstatus',EDU_LEVEL ='$edu_level_old',EDU_LEVEL_NEW = '$Education_Level',EDUCATION='$Educ_Qualification',OCCUPATION='$Occupation',INCOME='$Income',DIET='$Diet',SMOKE='$Smoke', DRINK='$Drink',COMPLEXION='$Complexion',BTYPE='$Body_Type',HANDICAPPED='$Phyhcp',PRIVACY='$radioprivacy',SCREENING='$curflag',LAST_LOGIN_DT='$today',MOD_DT=now() where PROFILEID='$profileid'";
				mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				showPart2($checksum,$showlinks);
			}
		}
		else 
		{
			$profileid=$data["PROFILEID"];

			//if(!$callValidate)
				$showlinks = "Y"; // Variable to enable links in left panel and sub header
			
			$sql = "Select USERNAME,GENDER,DTOFBIRTH,EMAIL,RELATION,HEIGHT,MSTATUS,HAVECHILD,MANGLIK,RELIGION,MTONGUE,CASTE,SUBCASTE,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,DIET,SMOKE,DRINK,COMPLEXION,BTYPE,HANDICAPPED,PRIVACY from newjs.JPROFILE where PROFILEID ='$profileid'";
			
			$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			
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

                        $sql="select SQL_CACHE VALUE,LABEL from CASTE where PARENT='" . $myrow["RELIGION"] . "' order by SORTBY";
                        $res=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        $casteopt="";
                        while($mycasterow=mysql_fetch_array($res))
                        {
                                if($myrow["CASTE"]==$mycasterow["VALUE"])
                                        $casteopt.="<option value=\"" . $mycasterow["VALUE"] . "\" selected>" . $mycasterow["LABEL"] . "</option>";
                                else
                                        $casteopt.="<option value=\"" . $mycasterow["VALUE"] . "\">" . $mycasterow["LABEL"] . "</option>";
                        }
                        $smarty->assign("CASTE",$casteopt);
			$smarty->assign("SUBCASTE",$myrow["SUBCASTE"]);
			$smarty->assign("COUNTRY_RES",create_dd($myrow["COUNTRY_RES"],"Country_Residence"));
			$cor=$myrow["COUNTRY_RES"];
			$smarty->assign("CITY_INDIA",create_dd($myrow["CITY_RES"],"City_India"));
			$smarty->assign("CITY_USA",create_dd($myrow["CITY_RES"],"City_USA"));
                        $smarty->assign("cor",$cor);
			
			$smarty->assign("RES_STATUS",$myrow["RES_STATUS"]);
			$smarty->assign("education_level",create_dd($myrow["EDU_LEVEL_NEW"],"Education_Level_New"));
			$smarty->assign("EDUCATION",$myrow["EDUCATION"]);
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
			
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("data",$data);
			/*$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));*/
			$smarty->display("../bmsjs/editprofile_1.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	
	function showPart2($checksum,$showlinks)
	{
		global $smarty;
		include("editprofile1.php");
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
