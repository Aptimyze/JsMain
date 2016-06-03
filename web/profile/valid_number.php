<?php

header("Location:$SITE_URL/profile/login.php");
exit;

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now)
ob_start("ob_gzhandler");
//end of it

$path = $_SERVER['DOCUMENT_ROOT'];

include_once($path."/profile/connect.inc");
include_once($path."/profile/connect_reg.inc");
include_once($path."/profile/connect_functions.inc");

//print_r($_POST);
//print_r($_GET);

$db = connect_db();
$data=authenticated($checksum);
$profileid=$data["PROFILEID"];

if(!$data)
{
	TimedOut();
	exit;
}

$IMG_URL2 = $IMG_URL."/profile/images/valid_number";
$smarty->assign("IMG_URL2",$IMG_URL2);

if($post_login)
	$smarty->assign("post_login",1);

$smarty->assign("bms_topright",18);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_right",28);
$smarty->assign("PROFILECHECKSUM",$profilechecksum);
$smarty->assign("CHECKSUM",$checksum);

if($submit || $submit_x)
{
	$is_error = 0;

	$country_residence_val = explode("|X|",$country_residence);
	$country_residence_val = explode("|}|",$country_residence_val[0]);
        $country_residence = $country_residence_val[1];

	if($country_residence=="")
        {
               $is_error++;
	       $smarty->assign("countryResidence_err",'1');
        }
	
	if($country_residence!=51 && $country_residence!=128)
			$city_residence="";
	
	$city_residence_val = explode("|{|",$city_residence);
        $city_residence = $city_residence_val[1];

	if($country_residence==51 || $country_residence==128)
	{
		if($city_residence=="" )
		{
			$city_residence = '0';
			$smarty->assign("cityResidence_err",'1');
		}
	}

	$checksum=$protect_obj->js_decrypt($checksum);
	$profileid=getProfileidFromChecksum($checksum);

	$sql_sel= "SELECT PHONE_RES,PHONE_MOB FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res_sel= mysql_query_decide($sql_sel) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row_sel= mysql_fetch_assoc($res_sel);
	$curr_land=$row_sel['PHONE_RES'];
	$curr_mob=$row_sel['PHONE_MOB'];
	
	if(!is_numeric($phone))
              $phone = "";
        if(!is_numeric($mobile))
              $mobile = "";

	if(!$phone)
        {
              if(!$mobile)
              {
                    $is_error++;
		    $smarty->assign("phone_err",'1');
              }
        }
        else
        {
              if($country_code=="")
              {
                     $is_error++;
		     $smarty->assign("COUNTRY_CODE_ERR",'1');
              }
              elseif($country_code=="" && checkrphone($country_code))
              {
                     $is_error++;
		     $smarty->assign("COUNTRY_CODE_ERR",'2');
              }
	      elseif($country_res == "51")
              {
                      if(!$state_code)
                      {
                             $is_error++;
		   	     $smarty->assign("STATE_CODE_ERR",'1');
                      }
 		      elseif($state_code && checkrphone($state_code))
                      {
                             $is_error++;
			     $smarty->assign("STATE_CODE_ERR",'2');
                      }
               }
	       elseif($phone && checkrphone($phone))
               {
                     $is_error++;
		     $smarty->assign("PHONE_ERR",'2');
               }
	       elseif($phone==$curr_land)
	       {
		     $is_error++;
	       }

	       if($phone_owner_name == "")
	       {
		       $is_error++;
		       $smarty->assign("phone_owner_err",'1');
	       }
	       elseif(is_numeric($phone_owner_name))
	       {
		       $is_error++;
		       $smarty->assign("phone_name_error",'1');
	       }
        }	

	if(!$mobile)
        {
              if(!$phone)
              {
                   $is_error++;
		   $smarty->assign("phone_err",'1');
              }
        }
        else
        {
               if($country_code_mob=="")
               {
   	               $is_error++;
	 	       $smarty->assign("COUNTRY_CODE_MOBILE_ERR",'1');
               }
               elseif($country_code_mobile=="" && checkrphone($country_code_mob))
               {
                       $is_error++;
		       $smarty->assign("COUNTRY_CODE_MOBILE_ERR",'2');
               }
 	       elseif($mobile && checkrphone($mobile))
               {
                        $is_error++;
			$smarty->assign("MOBILE_ERR",'2');
               }
	       elseif($mobile==$curr_mob)
	       {
		     $is_error++;
	       }

	       if($mobile_owner_name == "")
	       {
		       $is_error++;
		       $smarty->assign("mobile_owner_err",'1');
	       }
	       elseif(is_numeric($mobile_owner_name))
	       {
		       $is_error++;
		       $smarty->assign("mobile_name_error",'1');
	       }
        }

	if($is_error)
	{
	        $smarty->assign("COUNTRY_RESIDENCE",$country_residence);
                $smarty->assign("CITY_RESIDENCE",$city_residence);

                $smarty->assign("COUNTRY_CODE",$country_code);
		$smarty->assign("STATE_CODE",$state_code);

                $smarty->assign("PHONE",$phone);
                $smarty->assign("MOBILE",$mobile);

		$smarty->assign("SHOWPHONE_LAND",$showphone);
		$smarty->assign("SHOWPHONE_MOB",$showmobile);
		
		$smarty->assign("MOBILE_NUMBER_OWNER",$mobile_number_owner);
		$smarty->assign("MOBILE_OWNER_NAME",$mobile_owner_name);

		$smarty->assign("PHONE_NUMBER_OWNER",$phone_number_owner);
		$smarty->assign("PHONE_OWNER_NAME",$phone_owner_name);
	}
	else
	{
		$sql_sel= "SELECT PHONE_RES, PHONE_MOB FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_sel= mysql_query_decide($sql_sel) or die(mysql_error_js());
		$row_sel= mysql_fetch_assoc($res_sel);
		if(($row_sel['PHONE_RES']!=$phone)||($row_sel['PHONE_MOB']!=$mobile))
		{
			//$sql_ip1="DELETE FROM incentive.INVALID_PHONE where PROFILEID='$profileid'";
			//$result_ip1=mysql_query_decide($sql_ip1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ip1,"ShowErrTemplate");
			$sql_ip2="UPDATE incentive.MAIN_ADMIN_POOL SET TIMES_TRIED=0 where PROFILEID='$profileid'";
			$result_ip2=mysql_query_decide($sql_ip2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ip2,"ShowErrTemplate");
		}

		$country_code = explode('+',$country_code);
                $country_code = $country_code[1];

		$sql = "UPDATE newjs.JPROFILE SET COUNTRY_RES='$country_residence',CITY_RES='$city_residence',ISD='$country_code',STD='$state_code',PHONE_RES = '$phone',PHONE_WITH_STD='$state_code$phone',PHONE_NUMBER_OWNER='$phone_number_owner',PHONE_OWNER_NAME='$phone_owner_name',SHOWPHONE_RES='$showphone',PHONE_MOB ='$mobile',MOBILE_NUMBER_OWNER='$mobile_number_owner',MOBILE_OWNER_NAME='$mobile_owner_name',SHOWPHONE_MOB='$showmobile' WHERE PROFILEID ='$profileid'";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

		/* IVR-  Phone No. Verification Code
                 * return true/false */
                
                /* End of IVR code */
		/* Marking Valid - Invalid by IVR System */
		
		
		 include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
		 getPhoneValidity($profileid);

	 	 header("Location:".$SITE_URL."/profile/mainmenu.php");die;
	}

}
	if($profileid)
	{
		$sql_1="SELECT GENDER,ISD,STD,PHONE_RES,COUNTRY_RES,CITY_RES,PHONE_MOB,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,PHONE_NUMBER_OWNER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_1= mysql_query_decide($sql_1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_1,"ShowErrTemplate");
		while($row = mysql_fetch_array($res_1))
		{
			$phone_res = $row['PHONE_RES'];
			$gender = $row['GENDER'];
			$phone_mob = $row['PHONE_MOB'];
			$std = $row['STD'];
			$isd = $row['ISD'];
			$country_res = $row['COUNTRY_RES'];
			$city_res = $row['CITY_RES'];
			$phone_owner_name = $row['PHONE_OWNER_NAME'];
			$phone_number_owner = $row['PHONE_NUMBER_OWNER'];
			$mobile_owner_name = $row['MOBILE_OWNER_NAME'];
			$mobile_number_owner = $row['MOBILE_NUMBER_OWNER'];
		}
	
		$smarty->assign('phone',$phone_res);
		$smarty->assign('gender',$gender);
		$smarty->assign('MOBILE',$phone_mob);
		$smarty->assign('STATE_CODE',$std);
		$smarty->assign('COUNTRY_CODE',$isd);
		$smarty->assign('COUNTRY_RESIDENCE',$country_res);
		$smarty->assign('CITY_RESIDENCE',$city_res);
		$smarty->assign('MOBILE_OWNER_NAME',$mobile_owner_name);
		$smarty->assign('PHONE_OWNER_NAME',$phone_owner_name);
		$smarty->assign('PHONE_NUMBER_OWNER',$phone_number_owner);
		$smarty->assign('MOBILE_NUMBER_OWNER',$mobile_number_owner);
	}

	/*Country city dropdown creation*/

	//Top city dropdown

	$top_city_str="11|{|DE00$"."New Delhi#22|{|MH04$"."Mumbai#80|{|KA02$"."Bangalore#40|{|AP03$"."Hyderabad#20|{|MH08$"."Pune#44|{|TN02$"."Chennai#33|{|WB05$"."Kolkata# |{} $ #";
	
	
	$sql = "SELECT SQL_CACHE VALUE,LABEL,TOP_COUNTRY,ISD_CODE FROM newjs.COUNTRY_NEW ORDER BY ALPHA_ORDER";
	$res = mysql_query_decide($sql) or logError("error",$sql);
	$x = 0;
	while($row = mysql_fetch_array($res))
	{
		$country_isd_code1 = $row['ISD_CODE'];
		$country_isd_code = "+".$country_isd_code1;
		$country_label_arr[] = $row['LABEL'];
		$country_value = $row['VALUE'];

		$citizenship_arr[$x]["VALUE"] = $row['VALUE'];
		$citizenship_arr[$x]["LABEL"] = $row['LABEL'];
		$x++;
		
		if(($country_value==128 || $country_value==51))
		{
			$sql_city = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='$country_value' AND TYPE!='STATE' ORDER BY SORTBY";
			$res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
			while($row_city = mysql_fetch_array($res_city))
			{
				$city_value = $row_city['VALUE'];
				$city_label = $row_city['LABEL'];
				$city_std_code = $row_city['STD_CODE'];
				$city_str .= $city_std_code."|{|".$city_value."$".$city_label."#";
				
			}
		}

		$row_value='0';
		$row_others="Others";

		$city_str .= $row_value."$".$row_others."#";


		if(!($country_value==128 || $country_value==51))
			$city_str="";
		
		if($country_value==51)
			$city_str=$top_city_str.$city_str;
		$country_str = $country_isd_code."|}|".$country_value."|X|".$city_str;
		$country_value_arr[] = substr($country_str,0,strlen($country_str)-1);

	       if($row["TOP_COUNTRY"] == "Y")
     	       {
               		 $top_country_label_arr[] = $row["LABEL"];
               		 $top_country_value_arr[] = substr($country_str,0,strlen($country_str)-1);
               }

	       unset($city_str);
	       unset($country_str);
       }
       for($i=0;$i<count($top_country_value_arr);$i++)
       {
		$temp_country = explode("|X|",$top_country_value_arr[$i]);
		$temp_country = explode("|}|",$temp_country[0]);
		if($country_residence == $temp_country[1])
		$option_string.= "<option value=\"$top_country_value_arr[$i]\" selected=\"yes\">".$top_country_label_arr[$i]."</option>";
		else
		$option_string.= "<option value=\"$top_country_value_arr[$i]\">".$top_country_label_arr[$i]."</option>";
		
	}
	$option_string.= "<optgroup label=\"-----\"></optgroup>";

	//By default selecting country_residence as india if not selected
	if($country_residence=="")
		$country_residence=51;

	for($i=0;$i<count($country_value_arr);$i++)
	{
		$temp_country = explode("|X|",$country_value_arr[$i]);
		$temp_country = explode("|}|",$temp_country[0]);
		if($country_residence == $temp_country[1])
			$option_string.= "<option value=\"$country_value_arr[$i]\" selected=\"yes\">".$country_label_arr[$i]."</option>";
		else
			$option_string.= "<option value=\"$country_value_arr[$i]\">".$country_label_arr[$i]."</option>";

	}
	$smarty->assign('country_res',$option_string);


$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->display("valid_number.htm");

// flush the buffer
if($zipIt && !$dont_zip_now)
		ob_end_flush();
?>
