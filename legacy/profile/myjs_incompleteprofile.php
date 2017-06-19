<?php

//to zip the file before sending it
/*
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
*/
//end of it

include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("registration_functions.inc");
include_once("screening_functions.php");
include_once("cuafunction.php");

$sb=connect_db();
$data=authenticated($checksum);
$profileid=$data["PROFILEID"];

$smarty->assign("checksum",$checksum);

if($fsubmit)
{
	$sql = "SELECT GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($res);
	$gender=$row["GENDER"];

	//Field 1 :	
	if($relationship)
	{
		if($relationship!='PRESENT')
			$updateArr[]="RELATION=$relationship";
	}

	//Field 2:
	if(!$fname_user && !$lname_user)
		;
	elseif(($fname_user && !ereg("^[a-zA-Z\.\, ]+$",$fname_user)) || ($lanme_user && !ereg("^[a-zA-Z\.\, ]+$",$lname_user)))
		;
	else
	{
		if($fname_user!='PRESENT')
		{
			if($gender == "M")
				$name_of_user = "Mr.".$fname_user." ".$lname_user;
			elseif($gender == "F")
				$name_of_user = "Ms.".$fname_user." ".$lname_user;
		}
	}

	//Field 3:
	if($username)
	{
		if($username!='PRESENT')
		{
			$username_flag = validate_username($username);
			if(!$username_flag)
				$updateArr[]="USERNAME='$username'";
		}
	}


	//Field 4:
	if($mstatus)
	{
		if($mstatus!='PRESENT')
			$updateArr[]="MSTATUS='$mstatus'";	
		else
		{
			if($mstatus=='A' || $mstatus='I' || $mstatus='M')
			{
				if($mstatus=='A' && $mstatus_year && $mstatus_month && $mstatus_day)
				{
					$mstatus_date=$mstatus_date = $mstatus_year."-".$mstatus_month."-".$mstatus_day;
					$mstatus_reason=$mstatus_reason_married;
				}

				if($mstatus=='I' && $mstatus_reason_awaiting)
					$mstatus_reason=$mstatus_reason_awaiting;

				if($mstatus=='M' && $mstatus_reason_married) 
					$mstatus_reason=$mstatus_reason_married;
			}
		}
	}

	//Field 5:
	if($has_children)
	{
		if($has_children!='PRESENT')
			$updateArr[]="HAVECHILD='$has_children'";
	}

	//Field 6:
	if($country_residence)
	{
		if($country_residence!='PRESENT')
			$updateArr[]="COUNTRY_RES='$country_residence'";
	}

	//Field 7:
	if($citizenship)
	{
		if($citizenship!='PRESENT')
			$updateArr[]="CITIZENSHIP='$citizenship'";
	}

	//Field 8:
	if($city_residence)
	{
		if($city_residence!='PRESENT')
		{
			$city_residence_arr=explode("##",$city_residence);
			$updateArr[]="CITY_RES='$city_residence_arr[0]'";
		}
	}

	if($mtongue)
	{
		if($mtongue!='PRESENT')
		{
			$updateArr[]="MTONGUE='$mtongue'";
		}
	}


	//Field 9:

	if($phone)
	{
		if($phone!='PRESENT')
		{
			if(is_numeric($phone))
				if(!checkrphone($phone))
					$updateArr[]="PHONE_RES='$phone'";
		}
	}

	if($country_code)
	{
		if($country_code!='PRESENT')
			$updateArr[]="ISD=$country_code";
	}

	if($state_code)
	{
		if($state_code!='PRESENT')
			$updateArr[]="STD=$state_code";
	}

	if($showphone)
	{
		if($showphone!='PRESENT')
			$updateArr[]="SHOWPHONE_RES='$showphone'";
	}


	if($phone_number_owner)
	{
		if($phone_number_owner!='PRESENT')
		{
                        $phone_number_owner = addslashes(stripslashes(trim($phone_number_owner)));
			$updateArr[]="PHONE_NUMBER_OWNER='$phone_number_owner'";
		}
	}

	if($phone_owner_name)
	{
		if($phone_owner_name!='PRESENT')
		{
			if(ereg("^[a-zA-Z\.\, ]+$",$phone_owner_name))
				$updateArr[]="PHONE_OWNER_NAME='$phone_owner_name'";
		}
	}

	//Field 10:
	if($mobile)
	{
		if($mobile!='PRESENT')
			if(is_numeric($mobile))
				if(!checkmphone($mobile))
					$updateArr[]="PHONE_MOB='$mobile'";
	}
	if($showmobile)
	{
		if($showmobile!='PRESENT')
			$updateArr[]="SHOWPHONE_MOB='$showmobile'";
	}


	if($mobile_number_owner)
	{
		if($mobile_number_owner!='PRESENT')
                        $mobile_number_owner = addslashes(stripslashes(trim($mobile_number_owner)));
				$updateArr[]="MOBILE_NUMBER_OWNER='$mobile_number_owner'";
	}
	if($mobile_owner_name)
	{
		if($mobile_owner_name!='PRESENT')
			if(ereg("^[a-zA-Z\.\, ]+$",$mobile_owner_name))
				$updateArr[]="MOBILE_OWNER_NAME='$mobile_owner_name'";
	}


	//Field 11:
	if($time_to_call_end && $start_am_pm) 	
	{
		if($time_to_call_start!='PRESENT')
		{
			$time_to_call_start = $time_to_call_start." ".$start_am_pm;
			$updateArr[]="TIME_TO_CALL_START='$time_to_call_start'";	
		}
	}


	//Field 12:
	if($time_to_call_end && $end_am_pm)
	{
		if($time_to_call_end!='PRESENT')
		{
			$time_to_call_end = $time_to_call_end." ".$end_am_pm;
			$updateArr[]="TIME_TO_CALL_END='$time_to_call_end'";
		}
	}


	//Field 13-15
	if($degree)
	{
		if($degree!='PRESENT')
			$updateArr[]="EDU_LEVEL_NEW='$degree'";
	}


	if($occupation)
	{
		if($occupation!='PRESENT')
			$updateArr[]="OCCUPATION='$occupation'";
	}


	if($income)
	{
		if($income!='PRESENT')
			$updateArr[]="INCOME='$income'";
	}


	//Field 16-18
	if($match_alerts)
	{
		if($match_alerts!='PRESENT')
			$updateArr[]="PERSONAL_MATCHES='$match_alerts'";
	}
	if($promo)
	{
		if($promo!='PRESENT')
			$updateArr[]="PROMO_MAILS='$promo'";
	}
	if($service_messages)
	{
		if($service_messages!='PRESENT')
			$updateArr[]="SERVICE_MESSAGES='$service_messages'";
	}

	//if($termsandconditions=='Y')
	{
		if($profileid && $name_of_user)
		{
			$sql_name = "REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES('$profileid','".addslashes(stripslashes($name_of_user))."')";
			mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
		}

		if($mstatus=='A' || $mstatus='I' || $mstatus='M')
		{
			$sql_a = "REPLACE INTO newjs.ANNULLED(PROFILEID,COURT,DATE,REASON,ENTRY_DT,SCREENED,MSTATUS) VALUES('$profileid','$court','$mstatus_date','$mstatus_reason',now(),'N','$mstatus')";
        		mysql_query_decide($sql_a) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");
		}

		if(is_array($updateArr))
		{
			$Updatestr=implode(",",$updateArr);
			$sql="UPDATE newjs.JPROFILE SET $Updatestr,MOD_DT=now() WHERE PROFILEID=$profileid";
			$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
	}
	$myjs_incompleteprofile=1;
	include("mainmenu.php");
}
else
{
	if(!$data)
	{
        	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
	        $smarty->display("login_layer.htm");
        	die;
	}

	$_SERVER['ajax_error']=1;
	$sql = "SELECT MTONGUE,STD,GENDER,RELATION,USERNAME,MSTATUS,HAVECHILD,COUNTRY_RES,CITY_RES,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITIZENSHIP,PHONE_MOB,PHONE_RES,PHONE_OWNER_NAME,MOBILE_OWNER_NAME FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($res);

	$gender=$row["GENDER"];
	$smarty->assign("gender",$gender);
	$smarty->assign("PHONE_RES",$row["PHONE_RES"]);
	$smarty->assign("PHONE_MOB",$row["PHONE_MOB"]);
	$smarty->assign("phone_owner_name",$row["PHONE_OWNER_NAME"]);
	$smarty->assign("mobile_owner_name",$row["MOBILE_OWNER_NAME"]);
	$smarty->assign("STD",$row["STD"]);
	$CONTACTNO=0;
	if($row["PHONE_RES"])
		$CONTACTNO+=1;
	if($row["PHONE_MOB"])
		$CONTACTNO+=1;
	$smarty->assign("CONTACTNO",$CONTACTNO);
//TEMP
//unset($row);
//TEMP
	if(!$row["MTONGUE"])
		popMtongue();
	$relation=$row["RELATION"];
	$smarty->assign("relation",$relation);

	//Name Of User
	$sql_name="SELECT COUNT(*) as CNT FROM incentive.NAME_OF_USER WHERE PROFILEID=$profileid AND NAME<>''";
	$res_name=mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
	$row_name=mysql_fetch_array($res_name);
	if($row_name['CNT']>0)
		$smarty->assign("fullname",1);
	//Name Of User

	//Username
	$displayname=$row["USERNAME"];
	$smarty->assign("displayname",$displayname);
	//Username

	//
	$mstatus=$row["MSTATUS"];
	$smarty->assign("mstatus",$mstatus);
	//

	//
	$child=$row["HAVECHILD"];
	$smarty->assign("child",$child);
	//

	//Country Res
	$country=$row["COUNTRY_RES"];
	$smarty->assign("country",$country);
	$country_residence=create_dd($country,"Country_Residence");
	$smarty->assign("COUNTRY_RES",$country_residence);
	//Country Res

	//CityRes
	global $CITY_USA_DROP;
	$city_res=$row['CITY_RES'];
	if($CITY_USA_DROP[$city_res])
		unset($city_res);
	$smarty->assign("CITY_RES",$city_res);
	//CityRes

	//Contact
	//$contactNo=1;
	if($row["PHONE_RES"] || $row["PHONE_MOB"])
	{
		if($row["PHONE_OWNER_NAME"] || $row["MOBILE_OWNER_NAME"])
		{
			$smarty->assign("contactNo",1);
			$contactNo=1;
		}
	}
	//Contact

	if($country==51 || $row["CITIZENSHIP"])
		$smarty->assign("cityzenship",'1');
	if($country && $city_res && $contactNo)
		$smarty->assign("cityzenshipOnly",'1');
		

	//Time Of Call
	if($row["TIME_TO_CALL_END"] || $row["TIME_TO_CALL_START"])
		$smarty->assign("timeOfCall",1);
	//Time Of Call

	if($displayname && $mstatus  && $country && $city_res && $contactNo && $row["TIME_TO_CALL_END"] && $row["TIME_TO_CALL_START"])
	{
		$smarty->assign("basic_info_tab",1);
	}

	if($row["EDU_LEVEL_NEW"])
		$smarty->assign("edu",1);
	else
		$smarty->assign("education_level",create_dd($Education_Level,"Education_Level_New"));

	if($row["OCCUPATION"])
		$smarty->assign("work",1);
	else	
		$smarty->assign("occupation",create_dd($Occupation,"Occupation_New"));

	if($row["INCOME"])
		$smarty->assign("income",1);
	else
		$smarty->assign("INCOME",create_dd($Income,"Income"));


	if($row["EDU_LEVEL_NEW"] && $row["OCCUPATION"] && $row["INCOME"])
		$smarty->assign("educarrer_info_tab",1);
		


        $sql = "select SQL_CACHE VALUE, LABEL from COUNTRY order by LABEL";
	$res = mysql_query_optimizer($sql) or logError("error",$sql);
	while($myrow = mysql_fetch_array($res))
	{
		$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
	}
	$smarty->assign("CITIZEN",$ret);

	for($i=1;$i<12;$i++)
		$looptime[]=$i;	
	$smarty->assign("looptime",$looptime);

	for($i=1937;$i<2008;$i++)
		$loopyear[]=$i;
	$smarty->assign("loopyear",$loopyear);

	for($i=1;$i<32;$i++)
		$loopdate[]=$i;
	$smarty->assign("loopdate",$loopdate);

	
		

	$smarty->display("myjs_incompleteprofile.htm");
}

function popMtongue()
{
	$sql = "SELECT SQL_CACHE VALUE, LABEL, REGION FROM MTONGUE WHERE REGION <> '5' AND ID<>1 ORDER BY REGION DESC,SORTBY_NEW";
	$res = mysql_query_optimizer($sql) or logError("error",$sql);
	$ret = "";
	while($myrow = mysql_fetch_array($res))
	{
		$mtongue_region=$myrow['REGION'];
		if($mtongue_region!=$mtongue_region_old)
		{
			if($mtongue_region==4)
			{
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "<optgroup label=\"Communities in North India\">";
				$mtongueval.="%";
				$mtonguelab.="%";
			}
			elseif($mtongue_region==3)
			{
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "</optgroup>\n";
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "<optgroup label=\"Communities in West India\">\n";
				$mtongueval.="%";
				$mtonguelab.="%";
			}
			elseif($mtongue_region==2)
			{
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "</optgroup>\n";
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "<optgroup label=\"Communities in South India\">\n";
				$mtongueval.="%";
				$mtonguelab.="%";
			}
			elseif($mtongue_region==1)
			{
				$ret .= "</optgroup>\n";
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "<optgroup label=\"Communities in East India\">\n";
				$mtongueval.="%";
				$mtonguelab.="%";
			}
			elseif($mtongue_region==0)
			{
				$ret .= "</optgroup>\n";
				$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
				$ret .= "<optgroup label=\"----------\"></optgroup>\n";
				$mtongueval.="%";
				$mtonguelab.="%";
			}
			$mtongue_region_old=$mtongue_region;
		}
		$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
	}
	global $smarty;
	$smarty->assign("mtongueDropDown",$ret);
}
?>
