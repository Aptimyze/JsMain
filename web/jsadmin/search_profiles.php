<?php
/*************************************************************************************
Description : Searching the profile on the basis of id and show detail w.r.t login id.
Developed By: Vibhor Garg
Date        : 18-01-2008
*************************************************************************************/

/**************************************************************************************************************
Function added by Sadaf to find if nudge now button should appear/not according to latest requirement : 2977

Parameter List :

pid       : profileid of profile being nudged
profileid : profileid of offline profile

Return Values  :

yes : if profileid is to be nudged

***************************************************************************************************************/

function manual_nudge($pid,$profileid)
{
	global $mysqlObj;
	global $data;
	if(!$mysqlObj)
		$mysqlObj=new Mysql;
	$sendersIn=$pid;
	$contactResult=getResultSet("COUNT(*) AS COUNT",$sendersIn);
	if($contactResult[0]["COUNT"]>=5)
	/*$sql="SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE SENDER='$pid'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	if($row["COUNT"]>=5)*/
	{
		$data["PROFILEID"]=$pid;
		$sql="SELECT PROFILEID,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_assoc($res);
		$trend[0]=calculate_user_trend($row);
		$reverse_score=getting_reverse_trend($trend,0);
		if($reverse_score>20)
			return "yes";
		else
			return null;
	}
	else
	{
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
                include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
                include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
		$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
		$jpartnerObj=new Jpartner;
		$jpartnerObj->setPROFILEID($pid);
		$jpartnerObj->setPartnerDetails($pid,$myDb,$mysqlObj,"PARTNER_CASTE,PARTNER_MTONGUE,PARTNER_COUNTRYRES,LAGE,HAGE,LHEIGHT,HHEIGHT");
		if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$pid))
		{
			$caste_value=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_CASTE());
			if(is_array($caste_value))
			{
				$Caste=get_all_caste($caste_value);
				if(is_array($Caste))
				{
					$Caste_clustering=implode($Caste,",");
					$Caste=implode($Caste,"','");
					$Caste="'" . $Caste . "'";
				}
				else
					$Caste="";
				$partner_caste=$Caste;    
			}
			$partner_mtongue=$jpartnerObj->getPARTNER_MTONGUE();
			$partner_countryres=$jpartnerObj->getPARTNER_COUNTRYRES();
			$partner_lage=$jpartnerObj->getLAGE();
			$partner_hage=$jpartnerObj->getHAGE();
			$partner_lheight=$jpartnerObj->getLHEIGHT();
			$partner_hheight=$jpartnerObj->getHHEIGHT();
			if($partner_caste || $partner_mtongue || $partner_countryres || $partner_lage || $partner_hage || $partner_lheight || $partner_hheight)
			{
				$sql="SELECT ";
				if($partner_caste)
					$sql.=" IF((CASTE IN ($partner_caste)),1,0) AS CASTE_MATCH,";
				else
                                        $row_temp["CASTE_MATCH"]=1;
				if($partner_mtongue)
					$sql.=" IF((MTONGUE IN ($partner_mtongue)),1,0) AS MTONGUE_MATCH,";
				else
                                        $row_temp["MTONGUE_MATCH"]=1;
				if($partner_countryres)
					$sql.=" IF((COUNTRY_RES IN($partner_countryres)),1,0) AS COUNTRY_RES_MATCH,";
				else
                                        $row_temp["COUNTRY_RES_MATCH"]=1;
				if($partner_lage && $partner_hage)
					$sql.=" IF ((AGE>=$partner_lage AND AGE<=$partner_hage),1,0) AS AGE_MATCH,";
				elseif($partner_lage)
					$sql.=" IF ((AGE>=$partner_lage),1,0) AS AGE_MATCH,";
				elseif($partner_hage)
					$sql.=" IF ((AGE<=$partner_hage),1,0) AS AGE_MATCH,";
				else
                                        $row_temp["AGE_MATCH"]=1;
				if($partner_lheight && $partner_hheight)
					$sql.=" IF((HEIGHT>=$partner_lheight AND HEIGHT<=$partner_hheight),1,0) AS HEIGHT_MATCH";
				elseif($partner_lheight)
					$sql.=" IF((HEIGHT>=$partner_lheight),1,0) AS HEIGHT_MATCH";
				elseif($partner_hheight)
					$sql.=" IF((HEIGHT<=$partner_hheight),1,0) AS HEIGHT_MATCH";
				else
                                        $row_temp["HEIGHT_MATCH"]=1;
				$sql=rtrim($sql,",");
				$sql.=" FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($res);
				$match_score=$row["CASTE_MATCH"]+$row["MTONGUE_MATCH"]+$row["COUNTRY_RES_MATCH"]+$row["AGE_MATCH"]+$row["HEIGHT_MATCH"]+$row_temp["CASTE_MATCH"]+$row_temp["MTONGUE_MATCH"]+$row_temp["COUNTRY_RES_MATCH"]+$row_temp["AGE_MATCH"]+$row_temp["HEIGHT_MATCH"];
				if($match_score==5)
				{
					mysql_free_result($res);
					return "yes";
				}
				else
				{
					if(($row["CASTE_MATCH"]==1 || $row_temp["CASTE_MATCH"]==1)&& ($row["MTONGUE_MATCH"]==1||$row_temp["MTONGUE_MATCH"]==1) && ($row["COUNTRY_RES_MATCH"]==1 || $row_temp["COUNTRY_RES_MATCH"]==1))
						return "yes_3";
					else
						return null;
				}
			}
			else
			{
				return "yes";
			}
		}
		else
		{
			return "yes";
		}
	}
}
function jpartner_search_in_array_format_m($str)
{
        if($str)
        {
                $str=trim($str,"'");
                $arr=explode("','",$str);
                return $arr;
        }
}

function get_all_caste($caste)
{
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        return get_all_caste_revamp_js_db($caste,'',1);
        //REVAMP JS_DB_CASTE
}

function partner_search_matchalert($Profileid,$jpartnerObj="")
{
        global $ID;
        global $smarty;
		
	if(!$_SERVER['DOCUMENT_ROOT']);
                $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($Profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");

	$jpartnerObj->setPartnerDetails($Profileid,$myDb,$mysqlObj);

        $Gender=$jpartnerObj->getGENDER();
        $Children=$jpartnerObj->getCHILDREN();
        $lage=$jpartnerObj->getLAGE();
        $hage=$jpartnerObj->getHAGE();
        $lheight=$jpartnerObj->getLHEIGHT();
        $hheight=$jpartnerObj->getHHEIGHT();
        $Handicapped=$jpartnerObj->getHANDICAPPED();

        $caste_value=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_CASTE());

        $relation=$jpartnerObj->getPARTNER_RELATION();

        if(is_array($caste_value))
        {
                $Caste=get_all_caste($caste_value);
                $seCaste=$Caste;        //3d
                if(is_array($Caste))
                {
                        $Caste_clustering=implode($Caste,",");
                        $Caste=implode($Caste,"','");
                        $Caste="'" . $Caste . "'";
                }
                else
                        $Caste="";
                $searchCaste=$Caste;    //3d
        }
	$Mtongue=$jpartnerObj->getPARTNER_MTONGUE();
        $mtongue_value=jpartner_search_in_array_format_m($Mtongue);
        $Mtongue1=$Mtongue;     //3d

        //Special case used for hindi related caste
        if(is_array($mtongue_value) && is_array($caste_value))
        {
                if(in_array(10,$mtongue_value) || in_array(19,$mtongue_value) ||in_array(33,$mtongue_value))
                        $flag_mtongue_case=1;
        }

        $Occupation=$jpartnerObj->getPARTNER_OCC();
        $occ_value=jpartner_search_in_array_format_m($Occupation);

        $Country_Res=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_COUNTRYRES());
        $City_Res=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_CITYRES());


        $Education_new=$jpartnerObj->getPARTNER_ELEVEL_NEW();
        $education_value_new=jpartner_search_in_array_format_m($Education_new);
	
	if($Gender=="M")
        {
                $incomevalue=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_INCOME());
                if(is_array($incomevalue))
                {
                        if(in_array("7",$incomevalue))
                        {
                                $incomevalue[]="16";
                                $incomevalue[]="17";
                                $incomevalue[]="18";
                        }
                }
        }

        $Mstatus=$jpartnerObj->getPARTNER_MSTATUS();
        $mstatus_value=jpartner_search_in_array_format_m($Mstatus);

        $Manglik=$jpartnerObj->getPARTNER_MANGLIK();
        $manglik_value=jpartner_search_in_array_format_m($Manglik);


        $Btype=$jpartnerObj->getPARTNER_BTYPE();
        $btype_value=jpartner_search_in_array_format_m($Btype);

        $Complexion=$jpartnerObj->getPARTNER_COMP();
        $comp_value=jpartner_search_in_array_format_m($Complexion);

        $Smoke=$jpartnerObj->getPARTNER_SMOKE();
        $smoke_value=jpartner_search_in_array_format_m($Smoke);

        $Drink=$jpartnerObj->getPARTNER_DRINK();
        $drink_value=jpartner_search_in_array_format_m($Drink);

	$Diet=$jpartnerObj->getPARTNER_DIET();
        $diet_value=jpartner_search_in_array_format_m($Diet);

        if(is_array($Country_Res))
        {
                if(!in_array("All",$Country_Res) && !in_array("",$Country_Res))
                {
                        $insertCountry=implode($Country_Res,",");

                        for($i=0;$i<count($Country_Res);$i++)
                        {
                                if($Country_Res[$i]=="51")
                                        $country_india=1;
                                elseif($Country_Res[$i]=="128")
                                        $country_usa=1;
                                else
                                        $Country_Res1 .= "'".$Country_Res[$i]."'".",";
                        }
                        $Country_Res1 = substr($Country_Res1, 0, strlen($Country_Res1)-1);
                }
                else
                {
                        $Country_Res1= "";
                }
        }
	elseif($Country_Res!="" && $Country_Res!="All")
        {
                $insertCountry=$Country_Res;

                if($Country_Res=="51")
                        $country_india=1;
                elseif($Country_Res=="128")
                        $country_usa=1;
                else
                        $Country_Res1 = "'".$Country_Res."'";
        }
        if(is_array($City_Res))
        {
                if(!in_array("All",$City_Res) && !in_array("",$City_Res))
                {
                        $insertCity=implode($City_Res,",");

                        for($i=0;$i<count($City_Res);$i++)
                        {
                                if(is_numeric($City_Res[$i]))
                                {
                                        $country_usa=1;
                                        $city_usa[]=$City_Res[$i];
                                }
                                elseif(strlen($City_Res[$i])==2)
                                {
                                        $country_india=1;
                                        $citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res[$i]%'";
                                        $cityresult=mysql_query_decide($citysql);

                                        while($cityrow=mysql_fetch_array($cityresult))
                                        {
                                                $city_india[]=$cityrow["VALUE"];
                                        }

                                        mysql_free_result($cityresult);
					 }
                                else
                                {
                                        $country_india=1;
                                        $city_india[]=$City_Res[$i];
                                }
                        }
                }
        }
        elseif($City_Res!="" && $City_Res!="All")
        {
                $insertCity=$City_Res;
                if(is_numeric($City_Res))
                {
                        $country_usa=1;
                        $city_usa[]=$City_Res;
                }
                else
                {
                        $country_india=1;
                        if(strlen($City_Res)==2)
                        {
                                $citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res%'";
                                $cityresult=mysql_query_decide($citysql);

                                while($cityrow=mysql_fetch_array($cityresult))
                                {
                                        $city_india[]=$cityrow["VALUE"];
                                }

                                mysql_free_result($cityresult);
                        }
                        else
                                $city_india[]=$City_Res;
                }
        }
	unset($sql);

        if($Caste!="")
                $sql.= " (CASTE IN ($Caste)) AND";

        if($flag_mtongue_case)
        {
                $special_order_by="IF((MTONGUE IN ($Mtongue)),1,0)";
                $sql_return[1]=$special_order_by;
        }
        else
        {
                if(count($mtongue_value))
                        $sql.= " (MTONGUE IN ($Mtongue)) AND";
        }

        if(count($manglik_value))
                $sql.= " (MANGLIK IN ($Manglik)) AND";
        if(count($mstatus_value))
                $sql.= " (MSTATUS IN ($Mstatus)) AND";
        if($Children!="")
                $sql.= " (HAVECHILD IN ('$Children')) AND";
        if($lage!="" && $hage!="")
        {
                $sql.= " (AGE BETWEEN '$lage' AND '$hage') AND";
                $repage="AGE BETWEEN '$lage' AND '$hage'";
        }
        if($lheight!="" && $hheight!="")
        {
                $sql.= " (HEIGHT BETWEEN '$lheight' AND '$hheight') AND";
                $repheight="HEIGHT BETWEEN '$lheight' AND '$hheight'";
        }
	
        if(count($btype_value))        
                $sql.= " (BTYPE IN ($Btype)) AND";
        if(count($comp_value))
                $sql.= " (COMPLEXION IN ($Complexion)) AND";


        if(count($diet_value))
                $sql.= " (DIET IN ($Diet)) AND";
        if(count($smoke_value))
                $sql.= " (SMOKE IN ($Smoke)) AND";

        if(count($drink_value))
                $sql.= " (DRINK IN ($Drink)) AND";


        if($Handicapped!="")
                $sql.= " (HANDICAPPED IN ($Handicapped)) AND";
        if(count($occ_value))
                $sql.= " (OCCUPATION IN ($Occupation)) AND";
        if(count($education_value))
                $sql.= " (EDU_LEVEL IN ($Education)) AND";
        if(count($education_value_new))
                $sql.= " (EDU_LEVEL_NEW IN ($Education_new)) AND";

        if($Gender=="M")
        {
                if(count($incomevalue))
                        $sql .= " (INCOME IN ('" . implode($incomevalue,"','") . "')) AND";
        }
	if($country_india==1)
        {
                if(count($city_india) > 0)
                        $countrysql[]="(COUNTRY_RES = '51' and CITY_RES in ('" . implode($city_india,"','") . "'))";
                elseif($Country_Res1=="")
                        $Country_Res1="51";
                else
                        $Country_Res1.=",'51'";
        }
        if($country_usa==1)
        {
                if(count($city_usa) > 0)
                        $countrysql[]="(COUNTRY_RES = '128' and CITY_RES in ('" . implode($city_usa,"','") . "'))";
                elseif($Country_Res1=="")
                        $Country_Res1="128";
                else
                        $Country_Res1.=",'128'";
        }
        if($Country_Res1!="")
        {
                $countrysql[]="(COUNTRY_RES in ($Country_Res1))";
        }
        if(is_array($countrysql))
        {
                $countrycond=implode($countrysql," or ");
                $countrycond="(" . $countrycond . ")";
        }
        if(trim($countrycond)!="")
                $sql.=" $countrycond AND";
      
        if(count($rstatus_value))
                $sql .= " (RES_STATUS IN ($Rstatus)) AND";
        if($relation)
                $sql.=" RELATION IN ($relation) AND";

	if($sql)
        {
                $sql=substr($sql,0,-3);
                $sql_new.=$sql;
        }
        else
                $sql_new.=" 1 ";

        $sql_return[0]=$sql_new;
        return $sql_return;
}

function Q_ans($pid,$profileid)
{
	global $smarty;
	$sql="Select PROFILEID,STATUS,CATEGORY from jsadmin.OFFLINE_MATCHES where MATCH_ID='$pid' AND PROFILEID='$profileid' ";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	if(mysql_num_rows($result))
	{
		$row=mysql_fetch_assoc($result);
		$status = $row["STATUS"];
		$category = $row["CATEGORY"];
		if($pid == $profileid)
		{
			$smarty->assign("sat_dpp","NA");
		}
		else 
		{
			if($status == "NNOW")
				$smarty->assign("sat_dpp","No");
			else 
				$smarty->assign("sat_dpp","Yes");
		}
		if($status == "NACC")			
			$smarty->assign("res_nudge","Nudge Accepted");	
		elseif($status == "NREJ" || $status=="NNREJ")			
			$smarty->assign("res_nudge","Nudge Rejected");
		else  			
			$smarty->assign("res_nudge","Nudged");
		if(($category) && ($status=="N" || $status=="NACC" ||  $status=="NNOW"))			
			$smarty->assign("in_pool","Yes");
		else 
			$smarty->assign("in_pool","No");
		if($status == "SL")			
			$smarty->assign("in_sl","Yes");
		else 		
			$smarty->assign("in_sl","No");
		if($status == "ACC")
		{
			$smarty->assign("sent_oc","Yes");
			$smarty->assign("res_oc","Accepted");
		}
		else if($status == "REJ")
		{
			$smarty->assign("sent_oc","Yes");
			$smarty->assign("res_oc","Rejected");
		}
		else 
		{
			$smarty->assign("sent_oc","No");
			$smarty->assign("res_oc","NA");
		}		
	}
	else 
	{
		if($pid == $profileid)
		{
			$smarty->assign("sat_dpp","NA");
		}
		else 
		{
			$sub_sql_temp = partner_search_matchalert($profileid);
	               	$sub_sql=$sub_sql_temp[0];
                	$sql="Select COUNT(*) as CNT from newjs.JPROFILE where PROFILEID = '$pid' AND ($sub_sql)";
                	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$row=mysql_fetch_assoc($result);
                	$count = $row["CNT"];
	                if($count > 0)
        	                $smarty->assign("sat_dpp","Yes");
                        else
				$smarty->assign("sat_dpp","No");
		}
		$smarty->assign("res_nudge","NA");		
		$smarty->assign("in_pool","NA");
		$smarty->assign("in_sl","NA");
		$smarty->assign("sent_oc","NA");
		$smarty->assign("res_oc","NA");
		/*$sub_sql_temp = partner_search_matchalert($pid);
		$sub_sql=$sub_sql_temp[0];
		$sql="Select COUNT(*) as CNT from newjs.JPROFILE where PROFILEID = '$profileid' AND ($sub_sql)";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_assoc($result);
		$count = $row["CNT"];
		if($count > 0)
		{
			if($pid != $profileid)
			{
				$smarty->assign("show","yes");
			}			
		}*/
		$show=manual_nudge($pid,$profileid);
		if($show=="yes_3")
		{
			$smarty->assign("THREE_DPP","Y");
			$show="yes";
		}
		$smarty->assign("show",$show);
	}
}
$flag_using_php5=1;
include("connect.inc");
include("matches_display_results.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/thumb_identification_array.inc");
$db=connect_db(); 

$data = authenticated($cid);
if(isset($data))
{	
	comp_info($profileid);
	$smarty->assign("profileid",$profileid);
    $smarty->assign("cid",$cid);
    if($searchid)
	{
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$searchid' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			$search_pid=$row["PROFILEID"];	
			Q_ans($search_pid,$profileid);
			assigndetails($profileid,$search_pid);
			$viewprofile=$smarty->fetch("displayprofile.htm");
			$profilechecksum=md5($search_pid)."i".$search_pid;
			$smarty->assign("PROFILECHECKSUM",$profilechecksum);	
			$smarty->assign("viewprofile",$viewprofile);
			$smarty->assign("search_pid",$search_pid);
			$smarty->assign("flagd","yes");
			$smarty->display("search_profile.htm");
		}
		else
		{
				$smarty->assign("flagd","no");			
				$smarty->display("search_profile.htm");
		}
	}
	elseif($nudgenow)
	{
		if($three_dpp=="Y")
                        $sql="INSERT INTO jsadmin.OFFLINE_MATCHES (PROFILEID,MATCH_ID,STATUS,MATCH_DATE,NOTE,NNOW_3CRITERIA) VALUES('$profileid','$search_pid','NNOW',now(),'SEND_MAIL','Y')";
                else
                        $sql="INSERT INTO jsadmin.OFFLINE_MATCHES (PROFILEID,MATCH_ID,STATUS,MATCH_DATE,NOTE) VALUES('$profileid','$search_pid','NNOW',now(),'SEND_MAIL')";
		$res=mysql_query_decide($sql) or logError($sql);
                $sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$search_pid',NOW(),'NNOW')";
                $res=mysql_query_decide($sql) or logError($sql);
		$name=getname($cid);
		if(trim($nudgemsg))
		{
			$nudgemsg=htmlentities(nl2br(trim($nudgemsg)),ENT_QUOTES);
			$sql="REPLACE INTO jsadmin.OFFLINE_OPERATOR_MESSAGES(PROFILEID,MATCH_ID,OPERATOR,MESSAGE,DATE) VALUES('$profileid','$search_pid','$name','$nudgemsg',NOW())";
			mysql_query_decide($sql) or logError($sql);
		}
                Q_ans($search_pid,$profileid);
                assigndetails($profileid,$search_pid);
                $viewprofile=$smarty->fetch("displayprofile.htm");
                $profilechecksum=md5($search_pid)."i".$search_pid;
                $smarty->assign("PROFILECHECKSUM",$profilechecksum);
                $smarty->assign("viewprofile",$viewprofile);
                $smarty->assign("flagd","yes");
                $smarty->assign("search_pid",$search_pid);
                $smarty->display("search_profile.htm");

	}
	elseif($enter)
	{
		/*$sql="SELECT SOURCE,SUBSCRIPTION,SHOWPHONE_RES,SHOWPHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$search_pid'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$source=$row["SOURCE"];
		$sub=$row["SUBSCRIPTION"];
		$s_res=$row["SHOWPHONE_RES"];
		$s_mob=$row["SHOWPHONE_MOB"];
		$sql="SELECT MOBILE FROM newjs.MOBILE_VERIFICATION_SMS WHERE PROFILEID='$search_pid'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$mob=$row["MOBILE"];
		if($sub == 'D')	
		$cat = '3';
		elseif(($source == "ofl_prof")||($source == "OFL_PROF"))
		$cat = '4';
		elseif(($sub != " ")&&(($s_res=='Y')||($s_mob == 'Y')))
		$cat = '5';
		elseif(($sub == " ")&&(($s_res=='Y')||($s_mob == 'Y'))&&($mob == " "))	
		$cat = '6';
		else
		$cat = '';
		$sql="INSERT INTO jsadmin.OFFLINE_MATCHES (PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE) VALUES('$profileid','$search_pid','NNOW','$cat',now())";*/
		$smarty->assign("addmsg",1);
		/*if($three_dpp=="Y")
			$sql="INSERT INTO jsadmin.OFFLINE_MATCHES (PROFILEID,MATCH_ID,STATUS,MATCH_DATE,NNOW_3CRITERIA) VALUES('$profileid','$search_pid','NNOW',now(),'Y')";
		else
			$sql="INSERT INTO jsadmin.OFFLINE_MATCHES (PROFILEID,MATCH_ID,STATUS,MATCH_DATE) VALUES('$profileid','$search_pid','NNOW',now())";
		$res=mysql_query_decide($sql) or logError($sql);
		$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$search_pid',NOW(),'NNOW')";
		$res=mysql_query_decide($sql) or logError($sql);
		Q_ans($search_pid,$profileid);*/
		assigndetails($profileid,$search_pid);
		$viewprofile=$smarty->fetch("displayprofile.htm");
		$profilechecksum=md5($search_pid)."i".$search_pid;
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);	
		$smarty->assign("viewprofile",$viewprofile);
		$smarty->assign("flagd","yes");
		$smarty->assign("search_pid",$search_pid);
		$smarty->assign("three_dpp",$three_dpp);
		$smarty->display("search_profile.htm");
	}
	else 
	{
		$smarty->display("search_profile.htm");
	}	
}
else 
{
	 $msg="Your session has been timed out  ";
	 $smarty->assign("cid",$cid);
         $smarty->assign("MSG",$msg);
         $smarty->display("jsadmin_msg.tpl");
}
?>
