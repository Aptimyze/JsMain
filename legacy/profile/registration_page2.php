<?php
$start_tm=microtime(true);
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
  $dont_zip_more=1;
  ob_start("ob_gzhandler");
}

include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("registration_functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$db=connect_db();

$data_auth=authenticated($checksum,'y');

/* SEM Pixel code logic implemented as per Track Ticket #20 */

$sem_url=$_SERVER['HTTP_HOST'];
$sem_url_1=explode(".",$sem_url);
if($sem_url_1[0]=='www')
{
  unset($sem_url_1[0]);
  $sem_url=implode('.',$sem_url_1);
}

if($SEM)
{
  $smarty->assign('SEM','1');
  $sql_sem_cstm="SELECT CONTENT,IMAGE,BOX FROM MIS.SEM_PAGE_CUSTOMIZE WHERE SOURCE='$source' AND ACTIVE='Y' AND PAGE='P2'";
  $res_sem_cstm = mysql_query_decide($sql_sem_cstm) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_sem_cstm,"ShowErrTemplate");
  while($row_sem_cstm = mysql_fetch_array($res_sem_cstm))
  {
    $sem_content=$row_sem_cstm['CONTENT'];
    $sem_images=$row_sem_cstm['IMAGE'];
    $sem_box=$row_sem_cstm['BOX'];

    if($sem_box=='B1')
      $smarty->assign('P2B1_CONTENT',$sem_content);
    else if($sem_box=='B2')
      $smarty->assign('P2B2_CONTENT',$sem_content);
    else if($sem_box=='B3')
      $smarty->assign('P2B3_CONTENT',$sem_content);
    else if($sem_box=='B4')
      $smarty->assign('P2B4_CONTENT',$sem_content);
    $smarty->assign('SEM_IMAGE',$sem_images);
  }
}

$sql_sem="SELECT GA_CODE FROM MIS.SEM_GACODE WHERE URL='$sem_url' AND ACTIVE='Y'";
$res_sem=mysql_query_decide($sql_sem) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$res_sem,"ShowErrTemplate");
if($row_sem=mysql_fetch_array($res_sem))
{
  $pixel_code=$row_sem['GA_CODE'];
  $smarty->assign('SEM_PIXEL',$pixel_code);
  $smarty->assign('sem','1');
}

if(!$data_auth){
  //header("Location: ".$SITE_URL."/profile/registration_page1.php?source=$tieupsource");
  //exit;
}

$now = date("Y-m-d G:i:s");
$smarty->assign("LEADID",$leadid);
$smarty->assign("YEAR_OF_BIRTH",$year);
$smarty->assign("MONTH_OF_BIRTH",$month);
$smarty->assign("DAY_OF_BIRTH",$day);
$smarty->assign("COUNTRY_RESI",$country_residence);
$smarty->assign("CITY_RES",$city_residence);
$smarty->assign("checksum",$checksum);
if($page2submit_x || $page2submit || $about_yourself || $page_submit || $page2submit_y)
{
  $smarty->assign("gender",$gender);
  $smarty->assign("EMAIL",$email);
  $smarty->assign("yourHeading",$yourHeading);
  $smarty->assign("TIEUP_SOURCE",$tieup_source);
  $smarty->assign("HITSOURCE",$hit_source);
  $smarty->assign("NEWIP",$newip);
  $smarty->assign("ADNETWORK",$adnetwork);
  $smarty->assign("ADNETWORK1",$adnetwork1);
  $smarty->assign("ACCOUNT",$account);
  $smarty->assign("CAMPAIGN",$campaign);
  $smarty->assign("ADGROUP",$adgroup);
  $smarty->assign("KEYWORD",$keyword_tieup);
  $smarty->assign("MATCH",$match);
  $smarty->assign("LMD",$lmd);
  $smarty->assign("SHOWLOGIN",$showlogin);
  $smarty->assign("FROMMARRIAGEBUREAU",$fromprofilepage);
  $smarty->assign("GROUPNAME",$groupname);
  $smarty->assign("groupname",$groupname);
  $smarty->assign("CURRENT_DATE",date('Y-n-j'));
  $smarty->assign("PROFILEID",$profileid);
  $smarty->assign("RELIGION",$religion);
  $smarty->assign("CASTE",$caste);

  $is_error=0;
  //yourinfo
  $length=strlen($about_yourself);
  if($length>=100)
  {
    $smarty->assign("profileComplete",1);
  }
  //yourinfo

  //name
  /*	if(($fname_user && !ereg("^[a-zA-Z\.\, ]+$",$fname_user)) || ($lname_user && !ereg("^[a-zA-Z\.\, ]+$",$lname_user)))
      {
      $smarty->assign("usernameError",1);
      $page2_error=1;
      }
   */
  if($length<=99 || !$about_yourself)
  {
    $is_error++;
    $smarty->assign("yourinfoError",'1');
  }
  if(!$degree)
  {
    $is_error++;
    $smarty->assign("degree_err",'1');
  }
  if(!$occupation)
  {
    $is_error++;
    $smarty->assign("occupation_err",'1');
  }
  if(!$income)
  {
    $is_error++;
    $smarty->assign("income_err",'1');
  }

  $country_residence=explode('|X',$country_residence);
  $country_residence=$country_residence[0];

  /*	if(($country_residence!='51') && ($country_residence!='128'))
      {
      if(!$city_live)
      {
      $is_error++;
      $smarty->assign("city_live_err",'1');
      }
      }
   */
  //name
  if($is_error==0)
  {
    $checksum1=$protect_obj->js_decrypt($checksum);
    $profileid=getProfileidFromChecksum($checksum1);
    if($profileid)
    {
      $about_yourself=mysql_real_escape_string(stripslashes($about_yourself));
      if($fname_user || $lname_user)
      {
        if($gender == "M")
          $name_of_user = "Mr.".$fname_user." ".$lname_user;
        elseif($gender == "F")
          $name_of_user = "Ms.".$fname_user." ".$lname_user;
        else	
          $name_of_user =$fname_user." ".$lname_user;
        $sql_name = "REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES('$profileid','".addslashes(stripslashes($name_of_user))."')";
        mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
      }
      $length=strlen($about_yourself);

      //EDITED by SANDEEP for updating EDU_LEVEL in JPROFILE
      $edu_level=get_old_value($degree,"EDUCATION_LEVEL_NEW");

      if($city_live)
      {
        if($length <= '99')
        {
          $sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='Y',EDU_LEVEL_NEW='$degree',OCCUPATION='$occupation',INCOME='$income',CITY_RES='$city_live',EDU_LEVEL='$edu_level',MOD_DT='$now' WHERE PROFILEID=$profileid    and activatedKey=1 ";
          mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
        }
        elseif($length >= '100')
        {
          $sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='N',EDU_LEVEL_NEW='$degree',OCCUPATION='$occupation',INCOME='$income',CITY_RES='$city_live',EDU_LEVEL='$edu_level',MOD_DT='$now' WHERE PROFILEID=$profileid    and activatedKey=1 ";
          mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");

          //Fto state change after completion of page2
          $fto_action = FTOStateUpdateReason::REGISTER;
          SymfonyFTOFunctions::updateFTOState($profileid,$fto_action);
        }
        /* Tracking Query for the Reg Count */
        $sql = "UPDATE MIS.REG_COUNT SET PAGE2='Y' WHERE PROFILEID='$profileid'";
        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        /* Ends Here */
      }
      else
      {
        if($length <= '99')
        {
          $sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='Y',EDU_LEVEL_NEW='$degree',OCCUPATION='$occupation',INCOME='$income',EDU_LEVEL='$edu_level',MOD_DT='$now' WHERE PROFILEID=$profileid   and activatedKey=1 ";
          mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
        }
        elseif($length >= '100')
        {
          $sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='N',EDU_LEVEL_NEW='$degree',OCCUPATION='$occupation',INCOME='$income',EDU_LEVEL='$edu_level',MOD_DT='$now' WHERE PROFILEID=$profileid   and activatedKey=1 ";
          mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");

          //Fto state change after completion of page2
          $fto_action = FTOStateUpdateReason::REGISTER;
          SymfonyFTOFunctions::updateFTOState($profileid,$fto_action);

        }
        //EDITION BY SANDEEP ENDS HERE

        /* Tracking Query for the Reg Count */
        $sql = "UPDATE MIS.REG_COUNT SET PAGE2='Y' WHERE PROFILEID='$profileid'";
        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        /* Ends Here */
      }


      $sql_upd_jp = "UPDATE newjs.JPROFILE SET ";

      if($diet)
      {
        $jprofile_update[] = "DIET='".$diet."'";
      }
      if($drink)
      {
        $jprofile_update[] = "DRINK='".$drink."'";
      }
      if($smoke)
      {
        $jprofile_update[] = "SMOKE='".$smoke."'";
      }
      if($body_type)
      {
        $jprofile_update[] = "BTYPE='".$body_type."'";
      }
      if($complexion)
      {
        $jprofile_update[] = "COMPLEXION='".$complexion."'";
      }
      if($country_residence=='51')
        $residentStatus='1';
      if($residentStatus)
      {
        $jprofile_update[] = "RES_STATUS='".$residentStatus."'";
      }

      $jprofile_update[] = "MOD_DT='".$now."'";
      if(count($jprofile_update) > 0)
      {
        $jprofile_update_str = @implode(", ",$jprofile_update);
        $sql_upd_jp .= $jprofile_update_str." WHERE PROFILEID='$profileid'   and activatedKey=1 ";
        mysql_query_decide($sql_upd_jp) or logError("Due to a temporary problem your request could not be processed.Please try after a couple of minutes",$sql_upd_jp,"ShowErrTemplate");
      }
      unset($jprofile_update);

      $sql_pg="UPDATE MIS.REG_LEAD SET INCOMPLETE='N' WHERE EMAIL='$email'";
      mysql_query_decide($sql_pg) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_pg,"ShowErrTemplate");


      $indianTimeUnexploded = getISTDateTime();
      $indianTime = explode(':', $indianTimeUnexploded);
      $weekdayToday = date('w', JSstrToTime($indianTime[0] . '-' . $indianTime[1] . '-' . $indianTime[2] . ' ' . $indianTime[3] . ':' . $indianTime[4] . ':' . $indianTime[5]));
    
      // Under Screening Mailer attached to the first page
      $sql="SELECT MSTATUS,AGE,EMAIL,USERNAME FROM newjs.JPROFILE WHERE activatedKey=1 and PROFILEID = '$profileid'";
      $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
      $row=mysql_fetch_array($result);
      $age=$row["AGE"];	
      $email=$row["EMAIL"];
      $username=$row["USERNAME"];

      // Trac #1625 Starts
      //If it is sunday or profile is registered after 6:30PM or before 7:30AM
      if (($weekdayToday == 0) ||
          ($indianTime[3] > 18) ||
          (($indianTime[3] == 18) && $indianTime[4] >= 30) ||
          ($indianTime[3] < 7) ||
          (($indianTime[3] == 7) && $indianTime[4] <= 30)
          ) 
      { // Send new Screening Mail
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
        SymfonyEmailFunctions::sendEmail(16, $profileid); // send new screening mail.
      }
      else { 
        $smarty->assign("username",$username);
        $msg =$smarty->fetch('Under_Screening.html');
        send_email($email,$msg,"Welcome to Jeevansathi.com","register@jeevansathi.com","","","","","","Y");
      }
      // Trac #1625 Ends

      if($gender=='F')
      {
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        //		$Min_Age=($row['AGE']>29)?$row['AGE']-2:(($row['AGE']>26)?$row['AGE']-1:(($row['AGE']>22)?$row['AGE']:21));
        //		$Max_Age=($row['AGE']>33)?$row['AGE']+15:(($row['AGE']==33)?47:(($row['AGE']==32)?44:(($row['AGE']==31)?42:$row['AGE']+10)));

        /* Commenting as per Bug#47748
           if($income)
           {
           $new_partner_income=get_income_sortby_new($income,'','F');
           $new_partner_income=explode(",",$new_partner_income);
           $new_partner_income=implode("','",$new_partner_income);
           $DPP['Income']="'$new_partner_income'";
           }
         */

        $sql = "SELECT DISTINCT REL_CASTE FROM newjs.CASTE_COMMUNITY WHERE PARENT_CASTE = '$caste'";
        $res = mysql_query_decide($sql) or logError("error",$sql);

        if(mysql_num_rows($res)<1)
        {
          $def="'$religion'";
        }
        else
        {       $abc="";
          while($rowed = mysql_fetch_array($res))
          {
            $abc.=$rowed[REL_CASTE].",";
          }
          $abc=rtrim($abc,",");
          $sql1="SELECT DISTINCT PARENT FROM newjs.CASTE WHERE VALUE IN ($abc,$caste)";
          $res1 = mysql_query_decide($sql1) or logError("error",$sql1);
          $def="'";
          while($row1 = mysql_fetch_assoc($res1))
          {
            $def.=$row1[PARENT]."','";
          }
          $def=rtrim(rtrim($def,"'"),",");
        }
        $partner_religion_str=$def;					
        $jpartnerObj=new Jpartner;
        $mysqlObj=new Mysql;
        if(!$myDb)
        {
          $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
          $myDb=$mysqlObj->connect("$myDbName");
        }
        $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
        $jpartnerObj->setPROFILEID($profileid);
        if($row['MSTATUS']=='N')
        {
          $jpartnerObj->setPARTNER_MSTATUS("'N'");
          $age/=4;
        }
        //				$jpartnerObj->setPARTNER_INCOME($DPP['Income']);

        //added by prinka		
        //$jpartnerObj->setPARTNER_RELIGION($partner_religion_str);
        $religion_val=$jpartnerObj->getPARTNER_RELIGION();	
        $jpartnerObj->setPARTNER_RELIGION($religion_val);
        //added by prinka		
        $jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

        /* Commenting Filter logic as per Trac#368, as their will be a seperate page for setting the filters.
           $age_filter=$mstatus_filter=$religion_filter=$country_filter=$mtongue_filter=$caste_filter=$city_filter=$income_filter='N';	
           if($age<=8.00)
           $age_filter=$mstatus_filter=$religion_filter=$income_filter='Y';
           else if($age>10.00)
           $income_filter='Y';
           else
           $mstatus_filter=$income_filter='Y';
           $sql="INSERT ignore INTO newjs.FILTERS(PROFILEID,AGE,MSTATUS,RELIGION,COUNTRY_RES,MTONGUE,CASTE,CITY_RES,INCOME) VALUES ('$profileid', '$age_filter', '$mstatus_filter', '$religion_filter', '$country_filter','$mtongue_filter','$caste_filter','$city_filter','$income_filter')";
           mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
         */
      }
      $phone=explode('-',$phone);
      $phone=$phone[2];

      /* As Requirment we have shifted IVR-  Phone No. Verification Code after profile completation in second page
       * Scenarios checked for IVR call: 1. junk number exist (no ivr call)
       2. Duplicate Exist (no ivr call)
       3. ivr call (if neither junk nor duplicate)
       */
      include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
      if($mobile){
        $ivr_phone 	=$mobile;
        $phoneType	='M';
        $ivr_std 	='';
      }
      else if($phone){
        $ivr_phone 	=$phone;
        $phoneType	='L';
        $ivr_std 	=trim($state_code);
        if($ivr_std)
          $ivr_phone	=$ivr_std."-".$phone;
      }
      if($ivr_phone){
        $chk_junk =chkJunkNumberList($ivr_phone,$phoneType);
        if($chk_junk)
          phoneUpdateProcess($profileid,'',$phoneType,'J');
        else{
          $chk_duplicate =chkDuplicatePhone($ivr_phone,$phoneType,$profileid);
          if($chk_duplicate=='U')
            ivrPhoneVerification($profileid,$ivr_phone,$ivr_std,'register'); // IVR call goes to new numbers
        }
      }
      /* IVR - code ends */

      if($leadid)
      {
        $sql="UPDATE MIS.LEAD_CONVERSION SET LEAD_COMPLETED='Y' WHERE LEADID='$leadid'";
        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
      }

      /* SMS Code for sending sms to users */
      include_once "InstantSMS.php";
      $sms = new InstantSMS("REGISTER_CONFIRM", $profileid);
      $sms->send();
      /* Ends Here of SMS code */

      //include 3rd page.

      include_once("registration_page3.php");
      exit;
    }
    else
    {
      //else mail
      //$http_msg = "User Agent : $HTTP_USER_AGENT\n #Referer : $HTTP_REFERER \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
      //$http_msg .= implode(",",$HTTP_POST_VARS);
      //$http_msg=print_r($_SERVER,true);
      //mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank 2',$http_msg);
    }

  }
  else
  {
    // print_r($smarty->_tpl_vars);
    // $smarty->assign("fname_user",$fname_user);
    // $smarty->assign("lname_user",$lname_user);
    $about_yourself=htmlspecialchars(stripslashes($about_yourself),ENT_QUOTES);
    $smarty->assign("about_yourself",$about_yourself);
    $smarty->assign("DIET",$diet);
    $smarty->assign("DRINK",$drink);
    $smarty->assign("SMOKE",$smoke);
    $smarty->assign("BTYPE",$body_type);
    $smarty->assign("COMPLEX",$complexion);
    $smarty->assign("RSTATUS",$residentStatus);
    $smarty->assign("INCOME",$income);
    $smarty->assign("OCCUPATION",$occupation);
    $smarty->assign("DEGREE",$degree);
    $smarty->assign("checksum",$checksum);
  }
}
if(!$is_error)
{
  if(!$profileid)
  {
    //$http_msg = "User Agent : $HTTP_USER_AGENT\n #Referer : $HTTP_REFERER \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
    //$http_msg .= implode(",",$HTTP_POST_VARS);
    $http_msg=print_r($_SERVER,true);
    //mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank-----source1',$http_msg);
  }
  $checksum=md5($profileid)."i".$profileid;
  $checksum=$protect_obj->js_encrypt($checksum);
  $smarty->assign("checksum",$checksum);
}


/* Populating Drop Downs */

$option_string="";
$sql = "SELECT SQL_CACHE el.VALUE AS VALUE, el.LABEL AS LABEL, el.GROUPING AS GROUPING FROM EDUCATION_LEVEL_NEW el, EDUCATION_GROUPING eg WHERE el.GROUPING = eg.VALUE ORDER BY eg.SORTBY,el.SORTBY";
$res = mysql_query_decide($sql) or logError("error",$sql);
$i=0;
$first_group = true;

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
    if($first_group)
    {
      $optg=$EDUCATION_GROUPING_DROP[$group];
      $option_string.= "<optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
      $first_group = false;
    }
    elseif($group == count($EDUCATION_GROUPING_DROP))
    {
      $option_string.= "</optgroup><optgroup label=\"&nbsp;\">";
      $optg='';
    }
    else
    {
      $optg=$EDUCATION_GROUPING_DROP[$group];
      $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
    }

  }
  if($group_count == (count($EDUCATION_GROUPING_DROP)+1) && !$done_once)
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
$sql = "SELECT SQL_CACHE VALUE, LABEL from OCCUPATION ORDER BY SORTBY";
$res = mysql_query_decide($sql) or logError("error",$sql);
while($row = mysql_fetch_array($res))
{
  if($occupation == $row['VALUE'])
    $option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
  else
    $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
}

$smarty->assign('occupation',$option_string);

$option_string="";
$sql = "SELECT SQL_CACHE VALUE, LABEL, TYPE from INCOME WHERE VISIBLE <> 'N' ORDER BY SORTBY";
$res = mysql_query_decide($sql) or logError("error",$sql);
while($row = mysql_fetch_array($res))
{
  if($row['TYPE'] == "RUPEES" && !$indian_currency_label)
  {
    $indian_currency_label = 1;
    $optg="Income in Indian Rupee";
    $option_string.= "<optgroup label=\"$optg\">";
  }
  elseif($row['TYPE'] == "DOLLARS" && !$us_currency_label)
  {
    $us_currency_label=1;
    $optg="Income in US Dollars";
    $option_string.= "</optgroup><optgroup label= \"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
  }

  if($row['VALUE'] == "15")
    $option_string.= "</optgroup><optgroup label= \"&nbsp;\"></optgroup>";

  if($income == $row['VALUE'])
    $option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
  else
    $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";	
}

$smarty->assign('income',$option_string);

$country_residence=explode('|X',$country_residence);
$country_residence=$country_residence[0];
$smarty->assign('COUNTRY_RES',$country_residence);

if($country_residence != '51' && $country_residence != '128')
{	
  $option_string="";
  $sql_city = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='$country_residence' AND TYPE!='STATE' ORDER BY SORTBY";
  $res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
  while($row_city = mysql_fetch_array($res_city))
  {
    $city_value = $row_city['VALUE'];
    $city_label = $row_city['LABEL'];

    if($city_res == $row_city['VALUE'])
      $option_string.= "<option value=\"$row_city[VALUE]\" selected=\"yes\">$row_city[LABEL]</option>";
    else
      $option_string.= "<option value=\"$row_city[VALUE]\">$row_city[LABEL]</option>";
  }

  $option_string.= "<option value=0>Others</option>";

  $smarty->assign('city_res',$option_string);
  $smarty->assign('SHOW_CITY','1');
}
/* Ends Here */

		$smarty->assign("gender",$gender);
		$smarty->assign('p_percent',profile_percent_new($profileid));
		$smarty->assign('REGISTRATION',1);
		$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
		if(!isset($_COOKIE["ISEARCH"]))
			             $smarty->assign('ISEARCH_COOKIE_NOTSET','1');
/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif","JSREGPAGE2URL"));
		/* Ends Here */
		$smarty->display("registration_pg2.htm");


// flush the buffer
if($zipIt && !$dont_zip_now)
  ob_end_flush();

  function getISTDateTime() {
    $orgTZ = date_default_timezone_get();
    date_default_timezone_set("Asia/Calcutta");
    $retval = date("Y:m:d:H:i:s");
    date_default_timezone_set($orgTZ);
    return $retval;
  }
?>
