<?php
//to zip the file before sending it
include_once("revamp_filter.php");
/*
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include("connect.inc");
connect_db();
$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
$data = authenticated($checksum);
//print_r($_SERVER);
if($data)
	login_relogin_auth($data);
if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include('../marriage_bureau/connectmb.inc');
        $mbdata=authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign("source",$mbdata["SOURCE"]);
        $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
        mysql_select_db_js('newjs');
        //$data=login_every_user($profileid);
        $mbureau="bureau1";
}

/*************************************Portion of Code added for display of Banners*******************************
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);

//$regionstr=8;
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************
connect_db();

        /**************************Added By Shakti for link tracking**********************
        link_track("filter.php");
        /********************************************************************************

//////Function added by Vibhor Garg///////////////
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	function check_filters($Profileid)
        {
                global $smarty;
		$mysqlObj=new Mysql;
	        $jpartnerObj=new Jpartner;
		$myDbName=getProfileDatabaseConnectionName($Profileid,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
                $jpartnerObj->setPartnerDetails($Profileid,$myDb,$mysqlObj);
		$smarty->assign("LAGE",$jpartnerObj->getLAGE());
                $smarty->assign("HAGE",$jpartnerObj->getHAGE());
               	$smarty->assign("lage",$jpartnerObj->getLAGE());
                $smarty->assign("hage",$jpartnerObj->getHAGE());
		$mstatus_value=$jpartnerObj->getPARTNER_MSTATUS();
		if($mstatus_filter=="Y")
                {
			if($mstatus_value != "")
	                {
                                if(strstr($mstatus_value,'N'))
                                	$mstatus1[] = "Never Married";
                                if(strstr($mstatus_value,'W'))
                                	$mstatus1[] = "Widowed";
                                if(strstr($mstatus_value,'D'))
                                	$mstatus1[] = "Divorced";
                                if(strstr($mstatus_value,'A'))
                                	$mstatus1[] = "Annulled";
                                
				if(is_array($mstatus1))
                                        $mstatus= implode(", ",$mstatus1);

			}
                        else
                        {
                               	$mstatus="";
			}
			$smarty->assign("mstatus",$mstatus);
                        $smarty->assign("MSTATUS",$mstatus);
                }
		else
                {
                        if($mstatus_value != "")
                       	{
				if(strstr($mstatus_value,'N'))
                                        $mstatus1[] = "Never Married";
                                if(strstr($mstatus_value,'W'))
                                        $mstatus1[] = "Widowed";
                                if(strstr($mstatus_value,'D'))
                                        $mstatus1[] = "Divorced";
                                if(strstr($mstatus_value,'A'))
                                        $mstatus1[] = "Annulled";
                                
                                if(is_array($mstatus1))
                                        $mstatus= implode(", ",$mstatus1);
 
	                }
                        else
                        {
                        	$smarty->assign("check_mstatus","Y");
				$smarty->assign("mstatus_filter","N");
			}
			$smarty->assign("mstatus",$mstatus);
                        $smarty->assign("MSTATUS",$mstatus);
                }

		$partner_caste = $jpartnerObj->getPARTNER_CASTE();
                if($religion_filter=="Y")
                {
			if($partner_caste != "")
                        {
				$sql= "SELECT LABEL from newjs.CASTE where VALUE IN ($partner_caste)";
                        	$result2=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        	if(mysql_num_rows($result2)>0)
                        	{
					while($myrow2=mysql_fetch_row($result2))
                                        {
                                                if($religion != "")
                                                        $religion=$religion.", ".$myrow2[0];
                                                else
                                                        $religion=$myrow2[0];
                                        }
                              	}
                        	else
                        	{
                                	$religion="";
				}
			}
			else
                        {
                        	$religion="";
                        }
			$smarty->assign("religion",$religion);
                	$smarty->assign("RELIGION",$religion);
                }
		else
                {
                        if($partner_caste != "")
                        {
				$sql= "SELECT LABEL from newjs.CASTE where VALUE IN ($partner_caste)";
				$result2=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				if(mysql_num_rows($result2)>0)
				{
					 while($myrow2=mysql_fetch_row($result2))
                                        {
                                                if($religion != "")
                                                        $religion=$religion.", ".$myrow2[0];
                                                else
                                                        $religion=$myrow2[0];
                                        }

       		                }
                	        else
                        	{
                        		$smarty->assign("check_religion","Y");
                                	$smarty->assign("religion_filter","N");
                        	}
			}
			else
                        {
				$smarty->assign("check_religion","Y");
                                $smarty->assign("religion_filter","N");
                        }
			$smarty->assign("religion",$religion);
                        $smarty->assign("RELIGION",$religion);
                }
	
                $country = $jpartnerObj->getPARTNER_COUNTRYRES();
		if($country_filter=="Y")
                {
                        if($country != "")
			{
				$sql= "SELECT LABEL from newjs.COUNTRY where VALUE IN ($country)";
                        	$result3=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	                        if(mysql_num_rows($result3)>0)
        	                {
                	                while($myrow3=mysql_fetch_row($result3))
                        	        {
                                	        if($country_res != "")
                                        	        $country_res=$country_res.", ".$myrow3[0];
	                                        else
        	                                        $country_res=$myrow3[0];
                	                }
                        	}
	                        else
        	                {
                	                $country_res="";
				}
			}
			else
                        {
                        	$country_res="";
                        }

			$smarty->assign("country_res",$country_res);
			$smarty->assign("COUNTRY",$country_res);
                }
		else
                {
                        if($country != "")
                        {
				$sql= "SELECT LABEL from newjs.COUNTRY where VALUE IN ($country)";
        	                $result3=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                	        if(mysql_num_rows($result3)>0)
                        	{
                                	while($myrow3=mysql_fetch_row($result3))
					{
						if($country_res != "")
	                                		$country_res=$country_res.", ".$myrow3[0];
						else
							$country_res=$myrow3[0];
					}
                        	}
                        	else
                        	{
                                	$smarty->assign("check_country","Y");
                                	$smarty->assign("country_filter","N");
                        	}
			}
			else
	                {
        	  		$smarty->assign("check_country","Y");
                  		$smarty->assign("country_filter","N");
		        }
			$smarty->assign("country_res",$country_res);
                       	$smarty->assign("COUNTRY",$country_res);
                }

		$mtongue = $jpartnerObj->getPARTNER_MTONGUE();
                if($mtongue_filter=="Y")
                {
			if($mtongue != "")
			{
                        	$sql= "SELECT LABEL from MTONGUE where VALUE IN ($mtongue)";
	                        $result5=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
        	                if(mysql_num_rows($result5)>0)
                	        {
                        	        while($myrow5=mysql_fetch_row($result5))
                                        {
                                                if($mtongue_partner != "")
                                                        $mtongue_partner=$mtongue_partner.", ".$myrow5[0];
                                                else
                                                        $mtongue_partner=$myrow5[0];
                                        }
 	                        }
        	                else
                	        {
                        	        $mtongue_partner="";
				}
			}
			else
	                {
                        	$mtongue_partner="";
                        }
			$smarty->assign("mtongue",$mtongue_partner);
			$smarty->assign("MTONGUE",$mtongue_partner);
                }
		else
                {
                        if($mtongue != "")
                        {
				$sql= "SELECT LABEL from MTONGUE where VALUE IN ($mtongue)";
				$result5=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				if(mysql_num_rows($result5)>0)
                        	{
	                               	while($myrow5=mysql_fetch_row($result5))
                                        {
                                                if($mtongue_partner != "")
                                                        $mtongue_partner=$mtongue_partner.", ".$myrow5[0];
                                                else
                                                        $mtongue_partner=$myrow5[0];
                                        }
                        	}
                        	else
                        	{
                                	$smarty->assign("check_mtongue","Y");
                                	$smarty->assign("mtongue_filter","N");
                        	}
			}
			else
                        {
				$smarty->assign("check_mtongue","Y");
                                $smarty->assign("mtongue_filter","N");
                        }
			$smarty->assign("mtongue",$mtongue_partner);
                      	$smarty->assign("MTONGUE",$mtongue_partner);
                }
	}
////////Function added by Vibhor Garg////////////////////////////////////

if(isset($data) || $crmback=='admin')
{	
	if(!$crmback=='admin')
		$Profileid=$data["PROFILEID"];
	else
	{	/*added by Puneet on 13 Dec 2005 for setting filters from backend*
		$Profileid=$pid;
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("crmback",$crmback);
		/*added by Puneet ends on 13 Dec 2005 for setting filters from backend*
	}
	if($Submit)
	{
		// added by Shobha 0n 29.12.2005 to maintain a log of changes made to Filters set by member by BackeEnd User
		if($crmback=='admin')
		{
			$sql= "SELECT FILTERID, AGE, MSTATUS, RELIGION, COUNTRY_RES , MTONGUE from newjs.FILTERS where PROFILEID= $Profileid";
                        $result4= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow4=mysql_fetch_row($result4);                                                                                                                             
                        $Filterid=$myrow4[0];
                        $age_flag=$myrow4[1];
                        $mstatus_flag=$myrow4[2];
                        $religion_flag=$myrow4[3];
                        $country_flag=$myrow4[4];
                        $mtongue_flag=$myrow4[5];

			$comments="INITIAL FILTERS SET :";
			
		 	$comments.="<br>"." AGE :".$age_flag;
			$comments.="<br>"." MARITAL STATUS :".$mstatus_flag;
			$comments.="<br>"." RELIGION :".$religion_flag;
			$comments.="<br>"." COMMUNITY :".$mtongue_flag;
			$comments.="<br>"." COUNTRY :".$country_flag;

			$comments.="<br>"."MODIFIED FILTERS  :";

			if ($age_filter!= $age_flag)
				$comments.="<br>"." Changed Age From"."<b>".$age_flag."</b>"." to "."<b>".$age_filter."</b>";
			if ($mstatus_filter!= $mstatus_flag)
				$comments.="<br>"." Changed Marital Status From "."<b>".$mstatus_flag."</b>"." to "."<b>".$mstatus_filter."</b>";
			if ($religion_filter!= $religion_flag)
                                $comments.="<br>"." Changed Religion Filter From "."<b>".$religion_flag."</b>"." to "."<b>".$religion_filter."</b>";
			if ($mtongue_filter!= $mtongue_flag)
                                $comments.="<br>"." Changed Community Filter From "."<b>".$mtongue_flag."</b>"." to "."<b>".$mtongue_filter."</b>";
			if ($country_filter!= $country_flag)
                                $comments.="<br>"." Changed Country Filter From "."<b>".$country_flag."</b>"." to "."<b>".$country_filter."</b>";
			$crmuser = getname($cid);
			if (!$company)
				$COMPANY = 'JS';
			else
				$COMPANY = $company;
			$sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$Profileid','$comments','F','$COMPANY')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}

		$sql="REPLACE FILTERS (PROFILEID,FILTERID,AGE,MSTATUS,RELIGION,COUNTRY_RES,MTONGUE) VALUES ('$Profileid', '$Filterid', '$age_filter', '$mstatus_filter', '$religion_filter', '$country_filter','$mtongue_filter')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$smarty->assign("CHECKSUM",$checksum);
                if($mbureau=="bureau1")
                {
                        $smarty->assign("mb_username_profile",$data["USERNAME"]);
                        $smarty->assign("checksum",$data["CHECKSUM"]);
                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                       $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                }
                else
                {
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                }
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                $smarty->assign("AGE_FILTER",$age_filter);
                $smarty->assign("MSTATUS_FILTER",$mstatus_filter);
                $smarty->assign("MTONGUE_FILTER",$mtongue_filter);
                $smarty->assign("RELIGION_FILTER",$religion_filter);
                $smarty->assign("COUNTRY_FILTER",$country_filter);
        
	        check_filters($Profileid);
		
                if($age_filter!="Y" && $religion_filter!="Y" && $mstatus_filter!="Y" && $country_filter!="Y" && $mtongue_filter!="Y")
                {
                        $smarty->assign("FILTERNOTSET","Y");
                }
   		$smarty->assign("company",$company);	
		$smarty->display("revamp_filter.htm");
	}
	else
	{
		$smarty->assign("company",$company);
		$mysqlObj=new Mysql;
                $jpartnerObj=new Jpartner;
                $myDbName=getProfileDatabaseConnectionName($Profileid,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
		if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$Profileid))
                {
			check_filters($Profileid);
			
			$sql= "SELECT FILTERID, AGE, MSTATUS, RELIGION, COUNTRY_RES , MTONGUE from FILTERS where PROFILEID= $Profileid";  
			$result4= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow4=mysql_fetch_row($result4);
			
			$Filterid=$myrow4[0];
			$age_flag=$myrow4[1];
			$mstatus_flag=$myrow4[2];
			$religion_flag=$myrow4[3];
			$country_flag=$myrow4[4];
			$mtongue_flag=$myrow4[5];
			$smarty->assign("Filterid",$Filterid);
			$smarty->assign("age_flag",$age_flag);
			$smarty->assign("mstatus_flag",$mstatus_flag);
			$smarty->assign("religion_flag",$religion_flag);
			$smarty->assign("mtongue_flag",$mtongue_flag);
			$smarty->assign("country_flag",$country_flag);
			$smarty->assign("CHECKSUM",$checksum);
                        if($mbureau=="bureau1")
                        {
                                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                $smarty->assign("checksum",$data["CHECKSUM"]);
                                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        }
                        else
                        {
                                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));                         }
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

			$smarty->display("revamp_filter.htm");
		}
		else
		{ 
			$smarty->assign("CHECKSUM",$checksum);
                        if($mbureau=="bureau1")
                        {
                                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                $smarty->assign("checksum",$data["CHECKSUM"]);
                                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        
			}
                        else
                        {
                                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			}

			$smarty->assign("dpp","NO");
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("profileid",$Profileid);
			$smarty->display("revamp_filter.htm");
		}
	}
}
else
	TimedOut();

// flush the buffer
if($zipIt)
	ob_end_flush();*/
?>
