<?php

/****************** Include Files  ********************/
$flag_using_php5=1;
include_once("time.php");
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/arrays.php");
include_once("../profile/functions.inc");
include_once("../profile/manglik.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("ap_common.php");
include_once("ap_functions.php");

/*************   Include Files Ends  ****************/

if(!authenticated($cid))
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
        exit;
}

if($profileid==''){
	$err ="No Extra Detail form exist for this User";
	echo $err;
	die;
}

/* Section to get role of the logged in user
 *  Roles defines as -> SE,QA,DISPATCHER,TELECALLER      
*/
$role = fetchRole($cid);
$smarty->assign("ROLE",$role);
/* Ends */

// case: form gets submitted 
if($submit!='' && $profileid)
{
		
		// Date of birth
		if($year && $month && $day){
                	$arrayDate = array($year, $month, $day);
			$birthDate= implode("-", $arrayDate);
			$birthTime ="00:00:00";
			if($hour && $minute && $second){
				$arrayTime = array($hour, $minute, $second);
				$birthTime = implode(":",$arrayTime);
			}
               		$date_of_birth =$birthDate." ".$birthTime;
		}

		if($submit =="submit"){		
                	$sql = "INSERT INTO Assisted_Product.AP_EFORM_DETAILS (PROFILEID,FNAME,LNAME,DOB,CITY_LOC,CITY_LOC_OTHER,CITY_BIRTH,CITY_BIRTH_OTHER,CITY_NATIVE,CITY_NATIVE_OTHER,OCCUPATION,COMPANY,EDU_LEVEL,COLLEGE,MANGLIK,FNAME_CONTACT,LNAME_CONTACT,RELATION,FAMILY_INFO) VALUES('$profileid','$fname','$lname','$date_of_birth','$city_location','$city_location_other','$city_birth','$city_birth_other','$city_native','$city_native_other','$occupation','$company','$degree','$college','$manglik','$fname_contact','$lname_contact','$relationship','$family_info')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $msg ="Form has been saved successfully";
                }
		elseif($submit=='update'){
                        $sql = "UPDATE Assisted_Product.AP_EFORM_DETAILS SET `PROFILEID`='$profileid',`FNAME`='$fname',`LNAME`='$lname',`DOB`='$date_of_birth',`CITY_LOC`='$city_location',`CITY_LOC_OTHER`='$city_location_other',`CITY_BIRTH`='$city_birth',`CITY_BIRTH_OTHER`='$city_birth_other',`CITY_NATIVE`='$city_native',`CITY_NATIVE_OTHER`='$city_native_other',`OCCUPATION`='$occupation',`COMPANY`='$company',`EDU_LEVEL`='$degree',`COLLEGE`='$college',`MANGLIK`='$manglik',`FNAME_CONTACT`='$fname_contact',`LNAME_CONTACT`='$lname_contact',`RELATION`='$relationship',`FAMILY_INFO`='$family_info'";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$msg ="Form has been updated successfully";
		}		

}
if($profileid)	// Detail form gets viewed
{
	$sql ="SELECT * FROM Assisted_Product.AP_EFORM_DETAILS WHERE PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	if(mysql_num_rows($res))
		$buttonType ="update";
	else
		$buttonType ="submit";	
	while($row =mysql_fetch_array($res))
	{
		$fname 			=$row['FNAME'];	
                $lname 			=$row['LNAME'];
                $fname_contact 		=$row['FNAME_CONTACT'];
                $lname_contact		=$row['LNAME_CONTACT'];
                $date_of_birth		=$row['DOB'];
                $occupation		=$row['OCCUPATION'];
                $company		=$row['COMPANY'];
                $degree			=$row['EDU_LEVEL'];
                $college		=$row['COLLEGE'];
                $manglik		=$row['MANGLIK'];
                $relationship		=$row['RELATION'];
                $family_info		=$row['FAMILY_INFO'];
                $city_location		=$row['CITY_LOC'];	
                $city_loc_other		=$row['CITY_LOC_OTHER']; 
                $city_birth		=$row['CITY_BIRTH']; 
                $city_birth_other	=$row['CITY_BIRTH_OTHER']; 
                $city_native		=$row['CITY_NATIVE']; 
                $city_native_other	=$row['CITY_NATIVE_OTHER']; 
	}

	if($date_of_birth !='00-00-00 00:00:00'){	
		$dobArr = explode(" ",$date_of_birth);	
		$dobDate =explode("-",$dobArr[0]);
		$DAY =$dobDate[2];
		$MONTH =$dobDate[1];
		$YEAR =$dobDate[0];	
	
		$dobTime =explode(":",$dobArr[1]);
		$HOUR =$dobTime[0];
		$MINUTE =$dobTime[1];
		$SECOND =$dobTime[2];
	}

	$smarty->assign("DAY",$DAY);
        $smarty->assign("MONTH",$MONTH);
        $smarty->assign("YEAR",$YEAR);
        $smarty->assign("HOUR",$HOUR);
        $smarty->assign("MINUTE",$MINUTE);
        $smarty->assign("SECOND",$SECOND);

	$smarty->assign("fname",$fname);
        $smarty->assign("lname",$lname); 
        $smarty->assign("fname_contact",$fname_contact);
        $smarty->assign("lname_contact",$lname_contact);
        $smarty->assign("occupation",$occupation);
        $smarty->assign("company",$company);
        $smarty->assign("degree",$degree);
        $smarty->assign("college",$college);
        $smarty->assign("manglik",$manglik);
        $smarty->assign("relationship",$relationship);
        $smarty->assign("family_info",$family_info);
        $smarty->assign("city_location_other",$city_loc_other);
        $smarty->assign("city_birth_other",$city_birth_other);
	$smarty->assign("city_native_other",$city_native_other);
}


	/* Populating Drop Downs */
                $option_string="";
                $sql = "SELECT SQL_CACHE VALUE, LABEL, GROUPING FROM newjs.EDUCATION_LEVEL_NEW ORDER BY GROUPING,SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $i=0;
                while($row = mysql_fetch_array($res))
                {
                        $group = $row['GROUPING'];

                        //array to group degrees.
                        if(isset($group_old) && $group_old != $group)
                        $i++;
                        $group_values[$i] .= $row['VALUE']."|#|";

                        if($group_old != $group)
                        {
                                $group_count++;
                                if($group == "0")
                                {
                                        $optg="Professional Degrees";
                                        $option_string.= "<optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";

                                }
                                elseif($group == "1")
                                {
                                        $optg="Post-Graduate Degrees";
                                        $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
                                }
                                elseif($group == "2")
                                {
                                        $optg="Graduate Degrees";
                                        $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
                                }
                                elseif($group == "3")
                                {
                                        $option_string.= "</optgroup><optgroup label=\"&nbsp;\">";
                                        $optg='';
                                }

                        }
                        if($group_count == "4" && !$done_once)
                        {
                                $done_once = 1;
                                $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup>";
                        }

                        if($degree == $row['VALUE'])
                                $option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
                        else
                                $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";

                        $group_old = $group;
                }

                $smarty->assign("degree",$option_string);
                unset($option_string);
                $option_string="";

                $sql = "SELECT SQL_CACHE VALUE, LABEL from newjs.OCCUPATION ORDER BY SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                while($row = mysql_fetch_array($res))
                {
                        if($occupation == $row['VALUE'])
                                $option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
                        else
                                $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
                }
		$smarty->assign('occupation',$option_string);
                unset($option_string);
                $option_string="";

                $country_residence='51';
                if($country_residence)
                {
                        $option_string="";
                        $sql_city = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='$country_residence' AND TYPE!='STATE' ORDER BY SORTBY";
                        $res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
                        while($row_city = mysql_fetch_array($res_city))
                        {
                                $city_value = $row_city['VALUE'];
                                $city_label = $row_city['LABEL'];

                                if($city_location == $row_city['VALUE'])
                                        $option_string_L.= "<option value=\"$row_city[VALUE]\" selected=\"yes\">$row_city[LABEL]</option>";
                                else
                                        $option_string_L.= "<option value=\"$row_city[VALUE]\">$row_city[LABEL]</option>";
			
                               if($city_birth == $row_city['VALUE'])
                                        $option_string_B.= "<option value=\"$row_city[VALUE]\" selected=\"yes\">$row_city[LABEL]</option>";
                                else
                                        $option_string_B.= "<option value=\"$row_city[VALUE]\">$row_city[LABEL]</option>";

                               if($city_native == $row_city['VALUE'])
                                        $option_string_N.= "<option value=\"$row_city[VALUE]\" selected=\"yes\">$row_city[LABEL]</option>";
                                else
                                        $option_string_N.= "<option value=\"$row_city[VALUE]\">$row_city[LABEL]</option>";
				
                        }

                        $option_string_L.= "<option value=0>Others</option>";
			$option_string_B.= "<option value=0>Others</option>";
			$option_string_N.= "<option value=0>Others</option>";

                        $smarty->assign('city_location',$option_string_L);
			$smarty->assign('city_birth',$option_string_B);
			$smarty->assign('city_native',$option_string_N);	
                }
	/* Ends Here */

	// Year Dropdown created
        $curDate = '1991';
        for($i=$curDate;$i>=1939;$i--)
        	$yearArray[]=$i;
        $smarty->assign('yearArray',$yearArray);
        $smarty->assign("CURRENT_DATE",date('Y-n-j'));

	// Hour Dropdown created
        $curHour = '0';
        for($i=$curHour;$i<=24;$i++){
		if($i >=0 && $i <10)
			$i ="0".$i;
        	$hourArray[]=$i;
	}
        $smarty->assign('hourArray',$hourArray);

	// Minutes/Seconds Dropdown created
        $curTime = '1';
        for($i=$curTime;$i<=60;$i++){
		if($i >0 && $i <10)
			$i ="0".$i;
        	$min_sec_Array[]=$i;
	}
        $smarty->assign('min_sec_Array',$min_sec_Array);

	if( ($role=='TC') && ($list=='MYPROFILE' || $list=='CALL' || $list=='PULL') )
		$action =$buttonType;
	else
		$action ="";

	$smarty->assign("msg",$msg);
	$smarty->assign("action",$action);
	$smarty->assign("profileid",$profileid);
	$smarty->assign("cid",$cid);
	$smarty->assign("list",$list);
	$smarty->assign("ex_form_print",$ex_form_print);
	$smarty->display("ap_profile_extra_form.htm");
	
?>
