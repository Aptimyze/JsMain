<?php
$start_tm=microtime(true);
if(!$reg_page6)
{
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
}
//end of it
//sleep(2);
include_once("connect.inc");
include_once("arrays.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
connect_db();
$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
$data = authenticated($checksum);
//print_r($_POST);
if($data)
	login_relogin_auth($data);

$smarty->assign("profilechecksum",$profilechecksum);
$smarty->assign("isMobile",$isMobile);
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
        $mbureau="bureau1";
}

connect_db();

        /**************************Added By Shakti for link tracking**********************/
        link_track("revamp_filter.php");
        /*********************************************************************************/

	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	function display_format_filter($str)
	{
		if($str)
		{
			$str=trim($str,"'");

			$arr=explode("','",$str);
			return $arr;
		}

	}
        function get_partner_string_from_array($arr,$tablename)
        {
                global $lang;
                if(is_array($arr))
                {
                        $str=implode("','",$arr);
                        $sql="select SQL_CACHE distinct LABEL from $tablename where VALUE in ('$str')";
                        $dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $str1.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        return substr($str1,0,-2);
                }
                elseif($lang=="hin")
                        return "मान्य नही";
                else
                        return "   - ";
        }

	function check_filters($Profileid)
        {
		global $MSTATUS;
                global $smarty;
		$mysqlObj=new Mysql;
	        $jpartnerObj=new Jpartner;
		$myDbName=getProfileDatabaseConnectionName($Profileid,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
                $jpartnerObj->setPartnerDetails($Profileid,$myDb,$mysqlObj);
		$smarty->assign("LAGE",$jpartnerObj->getLAGE());
                $smarty->assign("HAGE",$jpartnerObj->getHAGE());
		if($jpartnerObj->getLAGE()=='')//to be changed soon
		{
			if($data[GENDER]='F')
				$smarty->assign("lage",21);
			else
				$smarty->assign("lage",18);
		}
		else
	               	$smarty->assign("lage",$jpartnerObj->getLAGE());
	
		if($jpartnerObj->getHAGE()=='')
			$smarty->assign("hage",70);
		else
                $smarty->assign("hage",$jpartnerObj->getHAGE());

		$count_check=0;//FOr shading alternate div's
		$smarty->assign("check_mstatus","N");
		$smarty->assign("check_religion","N");
		$smarty->assign("check_city","N");
		$smarty->assign("check_country","N");
		$smarty->assign("check_caste","N");
		$smarty->assign("check_mtongue","N");
		$smarty->assign("check_income","N");

		$mstatus_value=display_format_filter($jpartnerObj->getPARTNER_MSTATUS());
		if($mstatus_filter=="Y")
                {
			if($mstatus_value[0] != "")
	                {
				for($ll=0;$ll<count($mstatus_value);$ll++)
				{
					$PARTNER_MSTATUS[]=$MSTATUS[$mstatus_value[$ll]];
				}
                                
				if(is_array($PARTNER_MSTATUS))
                                        $mstatus= implode(", ",$PARTNER_MSTATUS);
		
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
                        if($mstatus_value[0] != "")
                       	{
                                for($ll=0;$ll<count($mstatus_value);$ll++)
                                {
                                        $PARTNER_MSTATUS[]=$MSTATUS[$mstatus_value[$ll]];
                                }
 				if(is_array($PARTNER_MSTATUS))
                                        $mstatus= implode(", ",$PARTNER_MSTATUS);
	                }
                        else
                        {
				$count_check ++;
                        	$smarty->assign("check_mstatus","Y");
				$smarty->assign("mstatus_filter","N");
			}
			$smarty->assign("mstatus",$mstatus);
                        $smarty->assign("MSTATUS",$mstatus);
                }
		//code for city
                $dpp_city = $jpartnerObj->getPARTNER_CITYRES();
                if($city_filter=="Y")
                {
                        if($dpp_city!="Y")
                        {
				$dpp_city=trim($dpp_city,"'");
                                $sql= "SELECT LABEL from newjs.CITY_NEW where VALUE IN ('$dpp_city')";
                                $result7=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                                if(mysql_num_rows($result7)>0)
                                {
                                        while($myrow6=mysql_fetch_row($result7))
                                        {
                                                if($city_partner != "")
                                                        $city_partner=$city_partner.", ".$myrow7[0];
                                                else
                                                        $city_partner=$myrow7[0];
                                        }
                                }
                                else
                                {
                                        $city_partner="";
                                }
                        }
                        else
                        {
                                $city_partner="";
                        }
                        $smarty->assign("city",$city_partner);
                        $smarty->assign("CITY",$city_partner);
                }
                else
                {
                        if($dpp_city != "")
                        {
				$dpp_city=trim($dpp_city,"'");
                                $sql= "SELECT LABEL from newjs.CITY_NEW where VALUE IN ('$dpp_city')";
                                $result7=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                                if(mysql_num_rows($result7)>0)
                                {
                                        while($myrow7=mysql_fetch_row($result7))
                                        {
                                                if($city_partner != "")
                                                        $city_partner=$city_partner.", ".$myrow7[0];
                                                else
                                                        $city_partner=$myrow7[0];
                                        }
                                }
                                else
                                {
                                        $count_check ++;
                                        $smarty->assign("check_city","Y");
                                        $smarty->assign("city_filter","N");

                                }
                        }
                        else
                        {
                                $count_check ++;
                                $smarty->assign("check_city","Y");
                                $smarty->assign("city_filter","N");
                        }
                        $smarty->assign("city",$city_partner);
                        $smarty->assign("CITY",$city_partner);
                }

//added by ankit for caste
		$dpp_caste = $jpartnerObj->getPARTNER_CASTE();
		 if($caste_filter=="Y")
                {
			if($dpp_caste!="Y")
			{
				$dpp_caste=trim($dpp_caste,"'");
                               $sql= "SELECT LABEL from newjs.CASTE where VALUE IN ('$dpp_caste')";
                                $result6=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                                if(mysql_num_rows($result6)>0)
                                {
                                        while($myrow6=mysql_fetch_row($result6))
                                        {
                                                if($caste_partner != "")
                                                        $caste_partner=$caste_partner.", ".$myrow6[0];
                                                else
                                                        $caste_partner=$myrow6[0];
                                        }
                                }
                                else
                                {
                                        $caste_partner="";
                                }
                        }
                        else
                        {
                                $caste_partner="";
                        }
                        $smarty->assign("caste",$caste_partner);
                        $smarty->assign("CASTE",$caste_partner);
                }
                else
                {
                        if($dpp_caste != "")
                        {
				$dpp_caste=trim($dpp_caste,"'");
                                $sql= "SELECT LABEL from newjs.CASTE where VALUE IN ('$dpp_caste')";
                                $result6=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                                if(mysql_num_rows($result6)>0)
                                {
                                        while($myrow6=mysql_fetch_row($result6))
                                        {
                                                if($caste_partner != "")
                                                        $caste_partner=$caste_partner.", ".$myrow6[0];
                                                else
                                                        $caste_partner=$myrow6[0];
                                        }
                                }
                                else
                                {
                                        $count_check ++;
                                        $smarty->assign("check_caste","Y");
                                        $smarty->assign("caste_filter","N");
                                }
                        }
                        else
                        {
                                $count_check ++;
                                $smarty->assign("check_caste","Y");
                                $smarty->assign("caste_filter","N");
                        }
                        $smarty->assign("caste",$caste_partner);
                        $smarty->assign("CASTE",$caste_partner);
                }

		//ankit's code end

include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
		
		$partner_income=display_format_filter($jpartnerObj->getPARTNER_INCOME());
		$cur_sort_arr["minID"]=$jpartnerObj->getLINCOME_DOL();
		$cur_sort_arr["maxID"]=$jpartnerObj->getHINCOME_DOL();
		$cur_sort_arr["minIR"]=$jpartnerObj->getLINCOME();
		$cur_sort_arr["maxIR"]=$jpartnerObj->getHINCOME();
		global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
		
		if($income_filter=="Y")
		{
                        if($partner_income[0] != "" || $cur_sort_arr[minID] !="" || $cur_sort_arr[maxID] !="" || $cur_sort_arr[minIR] !="" || $cur_sort_arr[maxIR] !="")
                        {
                	        $varr=getIncomeText($cur_sort_arr);
                	        if($varr)
                	               $arr[]=implode(",&nbsp;&nbsp;&nbsp;&nbsp;",$varr);
			}
			else
			{
				$arr="";
		 	}
			$smarty->assign("income",$arr);
		}
		else
		{
                        if($partner_income[0] != "" || $cur_sort_arr[minID] !="" || $cur_sort_arr[maxID] !="" || $cur_sort_arr[minIR] !="" || $cur_sort_arr[maxIR] !="")
                        {
                	        $varr=getIncomeText($cur_sort_arr);
                	        if($varr)
                	               $arr[]=implode(",&nbsp;&nbsp;&nbsp;&nbsp;",$varr);
			}
                        else
                        {
                                $count_check ++;
                                $smarty->assign("check_income","Y");
                                $smarty->assign("income_filter","N");
                        }

                        $smarty->assign("income",$arr);
		}

		$partner_caste = display_format_filter($jpartnerObj->getPARTNER_RELIGION());
                if($religion_filter=="Y")
                {
			if($partner_caste[0] != "")
                        {
				$religion=get_partner_string_from_array($partner_caste,"RELIGION");
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
                        if($partner_caste[0] != "")
                        {
				$religion=get_partner_string_from_array($partner_caste,"RELIGION");
			}
			else
                        {
                                $count_check ++;
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
				$country=trim($country,"'");
				$sql= "SELECT LABEL from newjs.COUNTRY where VALUE IN ( '$country')";
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
				$country=trim($country,"'");
				$sql= "SELECT LABEL from newjs.COUNTRY where VALUE IN ('$country')";
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
	                                $count_check ++;
                                	$smarty->assign("check_country","Y");
                                	$smarty->assign("country_filter","N");
                        	}
			}
			else
	                {
                                $count_check ++;
        	  		$smarty->assign("check_country","Y");
                  		$smarty->assign("country_filter","N");
		        }
			$smarty->assign("country_res",$country_res);
                       	$smarty->assign("COUNTRY",$country_res);
                }

		$mtongue = display_format_filter($jpartnerObj->getPARTNER_MTONGUE());
                if($mtongue_filter=="Y")
                {
			if($mtongue[0] != "")
			{
				$mtongue_partner=get_partner_string_from_array($mtongue,"MTONGUE");
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
                        if($mtongue[0] != "")
                        {
				$mtongue_partner=get_partner_string_from_array($mtongue,"MTONGUE");
			}
			else
                        {
                                $count_check ++;
				$smarty->assign("check_mtongue","Y");
                                $smarty->assign("mtongue_filter","N");
                        }
			$smarty->assign("mtongue",$mtongue_partner);
                      	$smarty->assign("MTONGUE",$mtongue_partner);
                }
	$count_check=7-$count_check;
	$smarty->assign("count_check",$count_check);
	}

if(isset($data) || $crmback=='admin')
{	
	include_once("sphinx_search_function.php");
	savesearch_onsubheader($data["PROFILEID"]);
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	$smarty->assign("manage_filter",1);
	$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("leftpanel_settings.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	//$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	$smarty->assign("gli",$data[GENDER]);

	if($data["SUBSCRIPTION"]!='')
	{
		$sub=explode(",",$data["SUBSCRIPTION"]);
		if(in_array("T",$sub))
		{
			include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
			$assistedProductOnline=1;
			$liveDPP=fetchCurrentDPP($data["PROFILEID"]);
			$APeditID=$liveDPP["DPP_ID"];
			$sqlAP="SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$data[PROFILEID]' AND ONLINE='Y' AND ROLE='ONLINE' AND CREATED_BY='ONLINE'";
			if($liveDPP["DPP_ID"])
				$sqlAP.=" AND DPP_ID>'$liveDPP[DPP_ID]'";
			$sqlAP.=" ORDER BY DPP_ID DESC LIMIT 1";
			$resAP=mysql_query_decide($sqlAP) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAP,"ShowErrTemplate");
			if(mysql_num_rows($resAP))
			{
				$rowAP=mysql_fetch_assoc($resAP);
				$APeditID=$rowAP["DPP_ID"];
				$smarty->assign("apEditMsg",1);
				if(!is_array($currentDPP))
					$currentDPP=$rowAP;
			}
			else
			{
				if(!is_array($currentDPP))
					$currentDPP=$liveDPP;
			}
			if($APeditID)
				$smarty->assign("APeditID",$APeditID);

			if($cameFrom=="EDITDPP")
				$smarty->assign("APShowMessage",1);
		}
	}
	if(!$crmback=='admin')
		$Profileid=$data["PROFILEID"];
	else
	{	/*added by Puneet on 13 Dec 2005 for setting filters from backend*/
		$Profileid=$pid;
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("crmback",$crmback);
		/*added by Puneet ends on 13 Dec 2005 for setting filters from backend*/
	}

	if($Submit)
	{		
		if($selectId)
		{
			$filterParam=array("age_filter"=>"AGE", "city_res_filter"=>"CITY_RES","country_res_filter"=>"COUNTRY_RES", "mtongue_filter"=>"MTONGUE","caste_filter"=>"CASTE","mstatus_filter"=>"MSTATUS","income_filter"=>"INCOME","religion_filter"=>"RELIGION");
			$updStr="";
			unset($_POST["Submit"]);
			unset($_POST["selectId"]);
			unset($_POST["noFilter"]);
			unset($_POST["NOT_UPDATE_HARDSOFT"]);
			foreach($_POST as $key=>$val)
			{
				if($filterParam[$key])
				{			
					$updStr.="$filterParam[$key]="."'$val',";
				}
				else
					die($key."A_E");
			}
					if(count($_POST)===1)
					{	
							$spanid=substr($updStr,0,strpos($updStr,'=',0)).'_';
					}
			if($noFilter){
				$updStr="";
				$sql="update newjs.FILTERS set $updStr COUNT=COUNT+5,HARDSOFT='Y' where PROFILEID='$Profileid'";
			}
			else
			{
				if($NOT_UPDATE_HARDSOFT)
					$sql="update newjs.FILTERS set $updStr HARDSOFT=HARDSOFT where PROFILEID='$Profileid'";
				else
					$sql="update newjs.FILTERS set $updStr HARDSOFT='Y' where PROFILEID='$Profileid'";
			}
			
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			

			if(!mysql_affected_rows_js())
			{
				$sql="insert ignore into newjs.FILTERS set $updStr HARDSOFT='Y',PROFILEID=$Profileid";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
		}
		// added by Shobha 0n 29.12.2005 to maintain a log of changes made to Filters set by member by BackeEnd User
		if($crmback=='admin')
		{
			$sql= "SELECT FILTERID, AGE, MSTATUS, RELIGION, COUNTRY_RES , MTONGUE,CASTE,CITY_RES,INCOME from newjs.FILTERS where PROFILEID= $Profileid";
                        $result4= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow4=mysql_fetch_row($result4);                                                                                                                             
                        $Filterid=$myrow4[0];
                        $age_flag=$myrow4[1];
                        $mstatus_flag=$myrow4[2];
                        $religion_flag=$myrow4[3];
                        $country_flag=$myrow4[4];
                        $mtongue_flag=$myrow4[5];
			$caste_flag=$myrow4[6];
			$city_flag=$myrow4[7];
			$income_flag=$myrow4[8];

			$comments="INITIAL FILTERS SET :";
			
		 	$comments.="<br>"." AGE :".$age_flag;
			$comments.="<br>"." MARITAL STATUS :".$mstatus_flag;
			$comments.="<br>"." RELIGION :".$religion_flag;
			$comments.="<br>"." COMMUNITY :".$mtongue_flag;
			$comments.="<br>"." COUNTRY :".$country_flag;
			$comments.="<br>"." CITY :".$city_flag;
			$comments.="<br>"." CASTE :".$caste_flag;
			$comments.="<br>"." INCOME :".$income_flag;
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
			if ($city_filter!= $city_flag)
				$comments.="<br>"." Changed City Filter From "."<b>".$city_flag."</b>"." to "."<b>".$city_filter."</b>";
			if ($caste_filter!=$caste_flag)
				$comments.="<br>"." Changed Caste Filter From "."<b>".$caste_flag."</b>"." to "."<b>".$caste_filter."</b>";
			if ($income_filter!=$income_flag)
				$comments.="<br>"." Changed Income Filter From "."<b>".$income_flag."</b>"." to "."<b>".$income_filter."</b>";
			$crmuser = getname($cid);
			if (!$company)
				$COMPANY = 'JS';
			else
				$COMPANY = $company;
			$sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$Profileid','$comments','F','$COMPANY')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		

			
		if($assistedProductOnline)
		{
			$sqlAP="UPDATE Assisted_Product.AP_DPP_FILTER_ARCHIVE SET AGE_FILTER='$age_filter',MSTATUS_FILTER='$mstatus_filter',RELIGION_FILTER='$religion_filter',COUNTRY_RES_FILTER='$country_filter',MTONGUE_FILTER='$mtongue_filter',CASTE_FILTER='$caste_filter',CITY_RES_FILTER='$city_filter',INCOME_FILTER='$income_filter' WHERE PROFILEID='$Profileid' AND STATUS NOT IN('OBS')";
			mysql_query_decide($sqlAP) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$sqlAP="UPDATE Assisted_Product.AP_TEMP_DPP SET AGE_FILTER='$age_filter',MSTATUS_FILTER='$mstatus_filter',RELIGION_FILTER='$religion_filter',COUNTRY_RES_FILTER='$country_filter',MTONGUE_FILTER='$mtongue_filter',CASTE_FILTER='$caste_filter',CITY_RES_FILTER='$city_filter',INCOME_FILTER='$income_filter' WHERE PROFILEID='$Profileid'";
                        mysql_query_decide($sqlAP) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
	        check_filters($Profileid);
	        
		if($spanid)
		die("$spanid");
		else
		die("$Filterid");
	}
	else
	{
			//Removing fromPage variable, if user has already set filters and by other means coming to this page by bookmarking help or history.back function or some others way.
			if($fromPage=='loginDeclineRedirect' ||$fromPage=='filter_redirect')
			{
				
				$sql="select PROFILEID from newjs.FILTERS where PROFILEID='$Profileid' and (HARDSOFT='Y' OR COUNT>3)";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				if($row=mysql_fetch_row($res))
					$fromPage="";
			}		
			
			if($fromPage=='loginDeclineRedirect' ||$fromPage=='filter_redirect')
			{
					
				if($fromPage=='loginDeclineRedirect')
				{
				$sql="Update newjs.FILTERS set AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=COUNT+1, HARDSOFT='N' where PROFILEID='$Profileid'";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

					if(!mysql_affected_rows_js())
					{				
					$sql="INSERT IGNORE into newjs.FILTERS set AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=1, HARDSOFT='N', PROFILEID=$Profileid";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					}
				}
				$sql="Select COUNT FROM newjs.FILTERS where PROFILEID=$Profileid";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
				$myrow=mysql_fetch_row($res);
				if($myrow[0]>2)
				$smarty->assign('dontSetFilter','0');
				else
				$smarty->assign('dontSetFilter','1');
				$smarty->assign('filter_redirect','1');
				$smarty->assign('fromPage','filter_redirect');
				$sql="Select RELIGION FROM newjs.JPROFILE where PROFILEID=$Profileid";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
				$myrow=mysql_fetch_row($res);
				if($myrow[0]=="1" ||$myrow[0]=="2"||$myrow[0]=="3")
				$smarty->assign('religion_check','1');
			}
			$smarty->assign("company",$company);
			$mysqlObj=new Mysql;
		        $jpartnerObj=new Jpartner;
		        $myDbName=getProfileDatabaseConnectionName($Profileid,'',$mysqlObj);
		        $myDb=$mysqlObj->connect("$myDbName");
			check_filters($Profileid);
		
			$sql= "SELECT FILTERID, AGE, MSTATUS, RELIGION, COUNTRY_RES , MTONGUE,CASTE,CITY_RES,INCOME from FILTERS where PROFILEID= $Profileid";  
			$result4= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow4=mysql_fetch_row($result4);

			$Filterid=$myrow4[0];
			$age_flag=$myrow4[1];
			$mstatus_flag=$myrow4[2];
			$religion_flag=$myrow4[3];
			$country_flag=$myrow4[4];
			$mtongue_flag=$myrow4[5];
			$caste_flag=$myrow4[6];
			$city_flag=$myrow4[7];
			$income_flag=$myrow4[8];

			$smarty->assign("Filterid",$Filterid);
			$smarty->assign("age_flag",$age_flag);
			$smarty->assign("mstatus_flag",$mstatus_flag);
			$smarty->assign("religion_flag",$religion_flag);
			$smarty->assign("mtongue_flag",$mtongue_flag);
			$smarty->assign("country_flag",$country_flag);
			$smarty->assign("caste_flag",$caste_flag);
			$smarty->assign("city_flag",$city_flag);
			$smarty->assign("income_flag",$income_flag);
			$smarty->assign("CHECKSUM",$checksum);
			if($mbureau=="bureau1")
			{
				$smarty->assign("mb_username_profile",$data["USERNAME"]);
				$smarty->assign("checksum",$data["CHECKSUM"]);
			}
			if($reg_page6){
				$smarty->assign('REG_P6','1');
				 if(!isset($_COOKIE["ISEARCH"]))
								 $smarty->assign('ISEARCH_COOKIE_NOTSET','1');
			}
		/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"https://track.99acres.com/images/zero.gif","JSREGPAGE6URL"));
		/* Ends Here */	
			$smarty->display("revamp_filter_new.htm");
		}
	
}
else
{
        if($Submit)
        {
                die('You have logged out or Your Session has expired');
        }
	else if($from_mail==1)
                $smarty->assign("login_mes","Please login to see your filters");

        TimedOut();
}
if(!$reg_page6){
	// flush the buffer
	if($zipIt)
		ob_end_flush();
}
?>
