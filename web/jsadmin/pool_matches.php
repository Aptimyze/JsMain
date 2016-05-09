<?php
/******************************************************************************************************************
Filename    : pool_matches.php
Description : Display the matches currently in pool for the offline customer [2586]
Created By  : Sadaf Alam
Created On  : 24 January 2008
*******************************************************************************************************************/
include("connect.inc");
include("matches_display_results.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$dbslave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbslave);

if(1)//(authenticated($cid))
{
	comp_info($profileid);
	$sql="SELECT ID,MATCH_ID FROM jsadmin.OFFLINE_MATCHES WHERE DATEDIFF(NOW(),MATCH_DATE)>3 AND CATEGORY='' AND STATUS='NNOW' AND NNOW_3CRITERIA<>'Y'";
	$res=mysql_query_decide($sql,$dbslave) or logError($sql);
	if(mysql_num_rows($res))
	{
		$category='';
		while($row=mysql_fetch_assoc($res))
		{
			$sqldet="SELECT SOURCE,SUBSCRIPTION,SHOWPHONE_RES,SHOWPHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$row[MATCH_ID]'";
			$resdet=mysql_query_decide($sqldet,$db) or logError($sqldet);
			$rowdet=mysql_fetch_assoc($resdet);
			
			unset($sub);
			$sub=array();
			if($rowdet["SUBSCRIPTION"])
				$sub=explode(",",$rowdet["SUBSCRIPTION"]);
			if($rowdet["SOURCE"]=="ofl_prof" || in_array("1",$sub))
			$category=4;
			else
			{
				if(in_array("D",$sub))
					$category=3;
				elseif($rowdet["SUBSCRIPTION"]!='')
				{
					if($rowdet["SHOWPHONE_RES"]=="Y" || $rowdet["SHOWPHONE_MOB"]=="Y")
					$category=5;
				}
				else
				{
					/* In case of manual nudge, free profile added to pool irrespective of number verified and agreed to show any number : Added by Sadaf for 3185*/
		
					/*$sqlmob="SELECT ID FROM newjs.MOBILE_VERIFICATION_SMS WHERE MOBILE='$rowdet[PHONE_MOB]'";
					$resmob=mysql_query_decide($sqlmob) or logError($sqlmob);
					if(mysql_num_rows($res))
					{*/
						if($rowdet["SHOWPHONE_RES"]=="Y" || $rowdet["SHOWPHONE_MOB"]=="Y")
						$category=6;
					//}
				}
			}
			$sqldet="UPDATE jsadmin.OFFLINE_MATCHES SET CATEGORY='$category' WHERE ID='$row[ID]'";
			//echo $sqldet;
			mysql_query_decide($sqldet,$db) or logError($sqldet);
		}
	}
	if($searchid)
        {
                $sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$searchid'";
                $res=mysql_query_decide($sql,$db) or logError($sql);
                if(mysql_num_rows($res)>0)
                {
                        $row=mysql_fetch_assoc($res);
                        $searchpid=$row["PROFILEID"];
                        $sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$searchpid' AND PROFILEID='$profileid' AND STATUS IN('N','NACC','NNOW') AND CATEGORY!=''";
                        $res=mysql_query_decide($sql,$db) or logError($sql);
                        $row=mysql_fetch_assoc($res);
                        if($row["CNT"]>0)
                        {
                                assigndetails($profileid,$searchpid);
                                $viewprofile=$smarty->fetch("displayprofile.htm");
                                $smarty->assign("viewprofile",$viewprofile);
                                $smarty->assign("cid",$cid);
                                $smarty->assign("profileid",$profileid);
                                $smarty->assign("SEARCHED_PROFILE",1);
                                $smarty->assign("POOL",1);
                                $smarty->display("pool_matches.htm");
                                exit;
                        }
                        else
                        $smarty->assign("NOTINPOOL",1);
                }
                else
                $smarty->assign("WRONGID",1);

        }
	elseif($profile)
	{
		foreach($profile as $key=>$value)
		{
			$sql="SELECT CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
			$res=mysql_query_decide($sql,$db) or logError($sql);
			$row=mysql_fetch_assoc($res);
			$cat=$row["CATEGORY"];	
			$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS='SL',MOD_DATE=now()";
			if($cat!=1 && $cat!=2)
			$sql.=", SHOW_ONLINE='N'";
			$sql.=" WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
			$res=mysql_query_decide($sql,$db) or logError($sql);
			if($cat!=1 && $cat!=2)
			{
				$sql="INSERT INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$profileid' AND RECEIVER='$value' AND TYPE IN('N','NOW')) OR (SENDER='$value' AND RECEIVER='$profileid' AND TYPE IN('NACC','NREJ'))";
				mysql_query_decide($sql,$db) or logError($sql);
				$sql="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$profileid' AND RECEIVER='$value' AND TYPE IN ('N','NNOW')) OR (SENDER='$value' AND RECEIVER='$profileid' AND TYPE IN('NACC','NREJ'))";
				mysql_query_decide($sql,$db) or logError($sql);
			}
		}
		if(count($profile)>1)
		$smarty->assign("SHORTLISTED","N");	
		else
		$smarty->assign("SHORTLISTED",1);
		$shortlisted_pid=implode("','",$profile);
	}
	elseif($shortlist)
	{
		if($sl_profileid)
		{
			$searched_profileid=$sl_profileid;
			$notevar="note".$sl_profileid;
			$note=$_POST[$notevar];
		}
		$note=addslashes(stripslashes($note));
		$sql="SELECT CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND MATCH_ID='$searched_profileid'";
		$res=mysql_query_decide($sql,$db) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$cat=$row["CATEGORY"];
		$sql="UPDATE jsadmin.OFFLINE_MATCHES SET NOTE='$note',STATUS='SL',MOD_DATE=now()";
		if($cat!=1 && $cat!=2)
		$sql.=", SHOW_ONLINE='N'";
		$sql.=" WHERE PROFILEID='$profileid' AND MATCH_ID='$searched_profileid'";
		mysql_query_decide($sql,$db) or logError($sql);
		if($cat!=1 && $cat!=2)
		{
			$sql="INSERT INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$profileid' AND RECEIVER='$searched_profileid' AND TYPE IN('N','NNOW')) OR (SENDER='$searched_profileid' AND RECEIVER='$profileid' AND TYPE IN('NACC','NREJ'))";
			mysql_query_decide($sql,$db) or logError($sql);
			$sql="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$profileid' AND RECEIVER='$searched_profileid' AND TYPE IN('N','NNOW')) OR (SENDER='$searched_profileid' AND RECEIVER='$profileid' AND TYPE IN('NACC','NREJ'))";
			mysql_query_decide($sql,$db) or logError($sql);
		
		}
		$shortlisted_pid=$searched_profileid;
		$smarty->assign("SHORTLISTED","1");
		
	}
	unset($age);
	unset($education);
	unset($mtongue);
	unset($city);
	unset($caste);
	unset($mstatus);
	unset($occupation);
	unset($age_dd);
        unset($education_dd);
        unset($mtongue_dd);
        unset($city_dd);
        unset($caste_dd);
        unset($mstatus_dd);
        unset($occupation_dd);
	$age=array();
	$education=array();
	$mtongue=array();
	$city=array();
	$caste=array();
	$mstatus=array();
	$occupation=array();
	$PAGELEN=10;
	if(!$j)
	$j=0;
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid,'slave',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
	$jpartnerObj=new Jpartner;
	$jpartnerObj->setPROFILEID($profileid);
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
	{
		$my_partner_caste=$jpartnerObj->getPARTNER_CASTE();
		$my_partner_cityres=$jpartnerObj->getPARTNER_CITYRES();
		$my_partner_countryres=$jpartnerObj->getPARTNER_COUNTRYRES();
		$my_partner_mstatus=$jpartnerObj->getPARTNER_MSTATUS();
		$my_partner_lage=$jpartnerObj->getLAGE();
		$my_partner_hage=$jpartnerObj->getHAGE();
	}
	$sqlgen="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$resgen=mysql_query_decide($sqlgen,$db) or die(mysql_error_js());
	$rowgen=mysql_fetch_assoc($resgen);
	if($rowgen["GENDER"]=="F")
		$my_partner_gender="M";
	else
		$my_partner_gender="F";
	if($searchsubmit)
	{
		if($searchqueryid)
		{
			$sql="SELECT * FROM jsadmin.OFFLINE_SEARCHQUERY WHERE ID='$searchqueryid'";
			$res=mysql_query_decide($sql,$db) or logError($sql);
			$row=mysql_fetch_assoc($res);
			$caste_val=$row["CASTE"];
			$education_val=$row["EDU_LEVEL_NEW"];
			$mtongue_val=$row["MTONGUE"];
			$occupation_val=$row["OCCUPATION"];
			$mstatus_val=$row["MSTATUS"];
			$lage_val=$row["LAGE"];
			$hage_val=$row["HAGE"];
			$city_val=$row["CITY_RES"];
			$photo=$row["HAVEPHOTO"];
		}
		else
		{
			$insertsql="INSERT INTO jsadmin.OFFLINE_SEARCHQUERY(LAGE,HAGE,CASTE,EDU_LEVEL_NEW,MTONGUE,CITY_RES,MSTATUS,OCCUPATION,HAVEPHOTO) VALUES('$lage_val','$hage_val','$caste_val','$education_val','$mtongue_val','$city_val','$mstatus_val','$occupation_val','$photo')";
			mysql_query_decide($insertsql,$db) or logError($insertsql);
			$searchqueryid=mysql_insert_id_js();
		}
		$caste_val=stripslashes($caste_val);
		$education_val=stripslashes($education_val);
		$mtongue_val=stripslashes($mtongue_val);
		$city_val=stripslashes($city_val);
		$occupation_val=stripslashes($occupation_val);
		$mstatus_val=stripslashes($mstatus_val);

		$sql="(SELECT MATCH_ID AS PROFILEID,EMAIL,AGE,CASTE,EDU_LEVEL_NEW,MTONGUE,COUNTRY_RES,CITY_RES,MSTATUS,OCCUPATION,CATEGORY,MATCH_DATE FROM jsadmin.OFFLINE_MATCHES JOIN newjs.JPROFILE ON MATCH_ID=JPROFILE.PROFILEID WHERE OFFLINE_MATCHES.PROFILEID='$profileid' AND CATEGORY!='' AND STATUS IN ('N','NACC','NNOW') ";
		if($lage_val && $hage_val)
			$sql.=" AND (AGE>=$lage_val AND AGE<=$hage_val)";
		if($caste_val && $caste_val!="zero")
			$sql.=" AND CASTE IN('".str_replace("zero","",$caste_val)."')";
		elseif($caste_val)
			$sql.=" AND CASTE IN('')";
		if($education_val && $education_val!="zero")
			$sql.=" AND EDU_LEVEL_NEW IN('".str_replace("zero","0",$education_val)."')";
		elseif($education_val)
			$sql.=" AND EDU_LEVEL_NEW IN('0')";
		if($mtongue_val && $mtongue_val!="zero")
			$sql.=" AND MTONGUE IN('".str_replace("zero","",$mtongue_val)."')";
		elseif($mtongue_val)
			$sql.=" AND MTONGUE IN('')";
		if($city_val && $city_val!="zero")
			$sql.=" AND CITY_RES IN('".str_replace("zero","",$city_val)."')";
		elseif($city_val)
			$sql.=" AND CITY_RES IN('')";
		if($occupation_val && $occupation_val!="zero")
			$sql.=" AND OCCUPATION IN('".str_replace("zero","",$occupation_val)."')";
		elseif($occupation_val)
			$sql.=" AND OCCUPATION IN('')";
		if($mstatus_val && $mstatus_val!="zero")
			$sql.=" AND MSTATUS IN('".str_replace("zero","",$mstatus_val)."')";
		elseif($mstatus_val)
			$sql.=" AND MSTATUS IN('')";
		if($photo)
			$sql.=" AND HAVEPHOTO IN ('Y')";
		$sql.=" )";
		$sql.=" UNION (SELECT A.PROFILEID,A.EMAIL,A.AGE,A.CASTE,0 AS EDU_LEVEL_NEW,A.MTONGUE,A.COUNTRY_RES,A.CITY_RES,A.MSTATUS,A.OCCUPATION,'7' AS CATEGORY,'0000-00-00' AS MATCH_DATE FROM jsadmin.AFFILIATE_DATA AS A LEFT OUTER JOIN newjs.JPROFILE ON A.EMAIL=JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NULL AND A.GENDER='$my_partner_gender'";
		if($lage_val && $hage_val)
                        $sql.=" AND (A.AGE>=$lage_val AND A.AGE<=$hage_val)";
		elseif($my_partner_lage && $my_partner_hage)
                        $sql.=" AND (A.AGE>=$my_partner_lage && A.AGE<=$my_partner_hage)";
                elseif($my_partner_lage)
                         $sql.=" AND (A.AGE>=$my_partner_lage)";
                elseif($my_partner_hage)
                         $sql.=" AND (A.AGE<=$my_partner_hage)";
		if($caste_val && $caste_val!="zero")
			$sql.=" AND A.CASTE IN('".str_replace("zero","",$caste_val)."')";	
		elseif($caste_val)
                        $sql.=" AND A.CASTE IN('')";
                elseif($my_partner_caste)
                        $sql.=" AND A.CASTE IN ($my_partner_caste)";
                if($my_partner_countryres)
                        $sql.=" AND A.COUNTRY_RES IN ($my_partner_countryres)";
		 if($city_val && $city_val!="zero")
                        $sql.=" AND A.CITY_RES IN('".str_replace("zero","",$city_val)."')";
                elseif($city_val)
                        $sql.=" AND A.CITY_RES IN('')";
                elseif($my_partner_cityres)
                        $sql.=" AND A.CITY_RES IN ($my_partner_cityres)";
		if($mstatus_val && $mstatus_val!="zero")
                        $sql.=" AND A.MSTATUS IN('".str_replace("zero","",$mstatus_val)."')";
                elseif($mstatus_val)
                        $sql.=" AND A.MSTATUS IN('')";
                elseif($my_partner_mstatus)
                        $sql.=" AND A.MSTATUS IN ($my_partner_mstatus)";
		$sql.=") ORDER BY CATEGORY,MATCH_DATE DESC";
		$sql2=$sql." LIMIT $j,$PAGELEN";
		$smarty->assign("searchsubmit",1);
		$smarty->assign("lage_val",$lage_val);
		$smarty->assign("hage_val",$hage_val);
		$smarty->assign("caste_val",$caste_val);
		$smarty->assign("mtongue_val",$mtongue_val);
		$smarty->assign("education_val",$education_val);
		$smarty->assign("occupation_val",$occupation_val);
		$smarty->assign("mstatus_val",$mstatus_val);
		$smarty->assign("city_val",$city_val);
		$smarty->assign("photo",$photo);
	}
	else
	{
		$sql="(SELECT MATCH_ID AS PROFILEID,EMAIL,AGE,CASTE,EDU_LEVEL_NEW,MTONGUE,COUNTRY_RES,CITY_RES,MSTATUS,OCCUPATION,CATEGORY,MATCH_DATE FROM jsadmin.OFFLINE_MATCHES JOIN newjs.JPROFILE ON MATCH_ID=JPROFILE.PROFILEID WHERE OFFLINE_MATCHES.PROFILEID='$profileid' AND CATEGORY!='' AND STATUS IN ('N','NACC','NNOW')";
		if($shortlisted_pid)
			$sql.=" AND MATCH_ID NOT IN('$shortlisted_pid')";
		$sql.=") UNION (SELECT A.PROFILEID,A.EMAIL,A.AGE,A.CASTE,0 AS EDU_LEVEL_NEW,A.MTONGUE,A.COUNTRY_RES,A.CITY_RES,A.MSTATUS,A.OCCUPATION,'7' AS CATEGORY,'0000-00-00' AS MATCH_DATE FROM jsadmin.AFFILIATE_DATA AS A LEFT OUTER JOIN newjs.JPROFILE ON A.EMAIL=JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NULL AND A.GENDER='$my_partner_gender'";
		if($my_partner_caste)
			$sql.=" AND A.CASTE IN ($my_partner_caste)";
		if($my_partner_countryres)
			$sql.=" AND A.COUNTRY_RES IN ($my_partner_countryres)";
		if($my_partner_cityres)
			$sql.=" AND A.CITY_RES IN ($my_partner_cityres)";
		if($my_partner_mstatus)
			$sql.=" AND A.MSTATUS IN ($my_partner_mstatus)";
		if($my_partner_lage && $my_partner_hage)
			$sql.=" AND (A.AGE>=$my_partner_lage && A.AGE<=$my_partner_hage)";
		elseif($my_partner_lage)
			 $sql.=" AND (A.AGE>=$my_partner_lage)";
		elseif($my_partner_hage)
			 $sql.=" AND (A.AGE<=$my_partner_hage)";
		$sql.=") ORDER BY CATEGORY,MATCH_DATE DESC";
		$sql2=$sql." LIMIT $j,$PAGELEN";
	}
	$res=mysql_query_decide($sql,$dbslave) or die(mysql_error_js());
	$totalcount=mysql_num_rows($res);
	unset($nullcity);
	while($row=mysql_fetch_assoc($res))
	{
		if(!in_array($row["AGE"],$age) && trim($row["AGE"]))
			$age[]=$row["AGE"];
		if(!in_array($row["CASTE"],$caste))
		{
			if($row["CASTE"])
				$caste[]=$row["CASTE"];
			elseif(!in_array("zero",$caste))
				$caste[]="zero";
		}
		if(!in_array($row["EDU_LEVEL_NEW"],$education))
		{
			if($row["EDU_LEVEL_NEW"])
				$education[]=$row["EDU_LEVEL_NEW"];
			elseif(!in_array("zero",$education))
				$education[]="zero";
		}
		if(!in_array($row["MTONGUE"],$mtongue))
		{
			if($row["MTONGUE"])
				$mtongue[]=$row["MTONGUE"];
			elseif(!in_array("zero",$mtongue))
				$mtongue[]="zero";
		}
		if(!in_array($row["OCCUPATION"],$occupation))
		{
			if($row["OCCUPATION"])
				$occupation[]=$row["OCCUPATION"];
			elseif(!in_array("zero",$occupation))
				$occupation[]="zero";
		}
		if(!in_array($row["MSTATUS"],$mstatus))
		{
			if($row["MSTATUS"])
				$mstatus[]=$row["MSTATUS"];
			elseif(!in_array("zero",$mstatus))
				$mstatus[]="zero";
		}
		unset($cityarr);
		if($row["CITY_RES"]=='' && !$nullcity)
		{
			$city[]=array($row["COUNTRY_RES"],$row["CITY_RES"]);
			$nullcity=1;
		}
		elseif($row["CITY_RES"]!='')
		{
			$cityarr=array($row["COUNTRY_RES"],$row["CITY_RES"]);
			if(!in_array($cityarr,$city))
				$city[]=array($row["COUNTRY_RES"],$row["CITY_RES"]);
		}
	}
	//Creating drop down for age
	if(is_array($age) && count($age)>1)
	{
		$lowerage=min($age);
		$higherage=max($age);
		asort($age);
		$lowerage_dd.="<select class=\"main-text\" name=\"lage_val\">";
		$higherage_dd.="<select class=\"main-text\" name=\"hage_val\">";
		foreach($age as $key=>$value)
		{
			if($lowerage==$value)
				$lowerage_dd.="<option value=\"$value\" selected>$value</option>";
			else
				$lowerage_dd.="<option value=\"$value\">$value</option>";
			if($higherage==$value)
				$higherage_dd.="<option value=\"$value\" selected>$value</option>";
			else
				$higherage_dd.="<option value=\"$value\">$value</option>";
		}
		$lowerage_dd.="</select>";
		$higherage_dd.="</select>";
	}
	elseif(is_array($age) && count($age)==1)
	{
		$lowerage_dd.="<select class=\"main-text\" disabled><option value=\"$age[0]\">$age[0]</option></select>";
		$higherage_dd.="<select class=\"main-text\" disabled><option value=\"$age[0]\">$age[0]</option></select>";
	}
	else
	{
		$lowerage_dd.="<select class=\"main-text\" disabled><option>Select</option></select>";
		$higherage_dd.="<select class=\"main-text\" disabled><option>Select</option></select>";
	}
	$smarty->assign("lowerage_dd",$lowerage_dd);
	$smarty->assign("higherage_dd",$higherage_dd);


	//Creating drop down for caste	
	if(is_array($caste) && count($caste)>1)
	{
		$caste_dd="<select class=\"main-text\" name=\"caste_val\"><option value=\"".implode("','",$caste)."\" selected>All</option>";
		foreach($caste as $value)
		{
			if($value!="zero")
				$caste_dd.="<option value=\"$value\">$CASTE_DROP[$value]</option>";
			else
				$caste_dd.="<option value=\"zero\" style=\"color:grey\">No value</option>";
		}
		$caste_dd.="</select>";
	}
	elseif(is_array($caste) && count($caste)==1)
	{
		if($caste[0]!="zero")
			$caste_dd="<select class=\"main-text\" disabled><option value=\"$caste[0]\">".$CASTE_DROP[$caste[0]]."</option></select>";
		else
			$caste_dd="<select class=\"main-text\" disabled><option value=\"zero\" style=\"color:grey\">No Value</option></select>";
	}
	else
		$caste_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
	$smarty->assign("caste_dd",$caste_dd);	

	//Creating drop down for education
	if(is_array($education) && count($education)>1)
	{
		$education_dd="<select class=\"main-text\" name=\"education_val\"><option value=\"".implode("','",$education)."\" selected>All</option>";
		foreach($education as $value)
		{
			if($value!="zero")
				$education_dd.="<option value=\"$value\">$EDUCATION_LEVEL_NEW_DROP[$value]</option>";
			else
				$education_dd.="<option value=\"zero\" style=\"color:grey\">No Value</option>";
		}
		$education_dd.="</select>";
	}
	elseif(is_array($education) && count($education)==1)
	{
		if($education[0]!="zero")
			$education_dd="<select class=\"main-text\" disabled><option value=\"$education[0]\">".$EDUCATION_LEVEL_NEW_DROP[$education[0]]."</option></select>";
		else
		{
			$education_dd="<select class=\"main-text\" disabled><option value=\"zero\" style=\"color:grey\">No value</option></select>";
		}
	}
	else
		$education_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
	$smarty->assign("education_dd",$education_dd);

	//Creating drop down for city
	if(is_array($city) && count($city)>1)
        {
                $city_dd="<select class=\"main-text\" name=\"city_val\">";
		unset($allvalue);
		$countvalue=0;
                foreach($city as $value)
                {
                        unset($valuerow);
			if($value[1])
			{
				if($value[0]=="51")
					$valuerow=$CITY_INDIA_DROP[$value[1]];
				elseif($value[0]=="128")
					$valuerow=$CITY_USA_DROP[$value[1]];
			}
			/*else
			{
				$value[1]="zero";
				$valuerow="No Value";
			}*/
			if($valuerow)
			{
				/*if($valuerow=="No Value")
					$city_dd.="<option value=$value[1] style=\"color:grey\">$valuerow</option>";
				else*/
					$city_dd.="<option value=\"$value[1]\">$valuerow</option>";
				$countvalue++;
				$allvalue.=$value[1]."','";
			}
		}
		if($nullcity)
		{
			$city_dd.="<option value=\"zero\" style=\"color:grey\">No Value</option>";
			$countvalue++;
			$allvalue.="zero"."','";
		}
		$allvalue=trim($allvalue,"','");
		if($countvalue>=2)
	                $city_dd.="<option value=\"$allvalue\" selected>All</option></select>";
		elseif($countvalue==1)
		{
			$city_dd_temp=explode("city\">",$city_dd);
			$city_dd="<select disabled class=\"main-text\">".$city_dd_temp[1];
		}
		else
			$city_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
		
        }
        elseif(is_array($city) && count($city)==1)
        {
		unset($valuerow);
		if($city[0][1])
		{
			if($city[0][0]=="51")
				$valuerow=$CITY_INDIA_DROP[$city[0][1]];
			elseif($city[0][0]=="128")
				$valuerow=$CITY_USA_DROP[$city[0][1]];
		}
		else
		{
			$city[0][1]="zero";
			$valuerow="No Value";
		}
		if($valuerow)
			$city_dd="<select class=\"main-text\" disabled><option value=\"$city[0][1]\">$valuerow</option></select>";
		else
			$city_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
        }
        else
                $city_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
        $smarty->assign("city_dd",$city_dd);


	//Creating drop down for marital status
	if(is_array($mstatus) && count($mstatus)>1)
        {
                $mstatus_dd="<select class=\"main-text\" name=\"mstatus_val\"><option value=\"".implode("','",$mstatus)."\" selected>All</option>";
                foreach($mstatus as $value)
                {
			if($value!="zero")
				$mstatus_dd.="<option value=\"$value\">$MSTATUS[$value]</option>";
			else
				$mstatus_dd.="<option value=\"zero\" style=\"color:grey\">No value</option>";
                }
                $mstatus_dd.="</select>";
        }
        elseif(is_array($mstatus) && count($mstatus)==1)
        {
		if($mstatus[0]!="zero")
	                $mstatus_dd="<select class=\"main-text\" disabled><option value=\"$mstatus[0]\">".$MSTATUS[$mstatus[0]]."</option></select>";
		else
			$mstatus_dd="<select class=\"main-text\" disabled><option value=\"zero\">No Value</option></select>";
        }
        else
                $mstatus_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
        $smarty->assign("mstatus_dd",$mstatus_dd);



	//Creating drop down for occupation
	if(is_array($occupation) && count($occupation)>1)
        {
                $occupation_dd="<select class=\"main-text\" name=\"occupation_val\"><option value=\"".implode("','",$occupation)."\" selected>All</option>";
                foreach($occupation as $value)
		{
                	if($value!="zero")
		 		$occupation_dd.="<option value=\"$value\">$OCCUPATION_DROP[$value]</option>";
			else
				$occupation_dd.="<option value=\"zero\" style=\"color:grey\">No value</option>";
		}
                $occupation_dd.="</select>";
        }
        elseif(is_array($occupation) && count($occupation)==1)
	{
         	if($occupation[0]!="zero")
		        $occupation_dd="<select class=\"main-text\" disabled><option value=\"$occupation[0]\">".$OCCUPATION_DROP[$occupation[0]]."</option></select>";
		else
			$occupation_dd="<select class=\"main-text\" disabled><option value=\"zero\">No Value</option></select>";
	}
        else
                $occupation_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
        $smarty->assign("occupation_dd",$occupation_dd);
	

	//Creating drop down for community
	if(is_array($mtongue) && count($mtongue)>1)
        {
                $mtongue_dd="<select class=\"main-text\" name=\"mtongue_val\"><option value=\"".implode("','",$mtongue)."\" selected>All</option>";
                foreach($mtongue as $value)
		{
                	if($value!="zero") 
				$mtongue_dd.="<option value=\"$value\">$MTONGUE_DROP_SMALL[$value]</option>";
			else
				$mtongue_dd.="<option value=\"zero\" style=\"color:grey\">No value</option>";
		}
                $mtongue_dd.="</select>";
        }
        elseif(is_array($mtongue) && count($mtongue)==1)
	{
         	if($mtongue[0]!="zero")
		       $mtongue_dd="<select class=\"main-text\" disabled><option value=\"$mtongue[0]\">".$MTONGUE_DROP_SMALL[$mtongue[0]]."</option></select>";
		else
			$mtongue_dd="<select class=\"main-text\" disabled><option value=\"zero\">No Value</option></select>";
	}
        else
                $mtongue_dd="<select class=\"main-text\" disabled><option>Select</option></select>";
        $smarty->assign("mtongue_dd",$mtongue_dd);
	$res2=mysql_query_decide($sql2,$dbslave) or logError($sql2);
	if(mysql_num_rows($res2))
	{
		if(mysql_num_rows($res)>0)
		{
			$smarty->assign("searchbar",1);
		}
		displayresults($res2,$j,"/jsadmin/pool_matches.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid&searchsubmit=$searchsubmit&searchqueryid=$searchqueryid",'','','','','',"admin",$profileid,$cid);
	}
	else
	{
		if($searchsubmit)
			$smarty->assign("NORES",1);
		else
			$smarty->assign("NOREC",1);
		
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("profileid",$profileid);
	$smarty->display("pool_matches.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
