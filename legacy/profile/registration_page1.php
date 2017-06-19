<?php
$start_tm=microtime(true);
unset($get_post);
if(is_array($_GET))
{
        foreach($_GET as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($_POST))
{
        foreach($_POST as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($get_post))
        $get_post_string = @implode("&",$get_post);

header("Location:/register/page1?$get_post_string");
die;


//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now)
{
	$dont_zip_more=1;
	ob_start("ob_gzhandler");
}
//end of it

$path = $_SERVER['DOCUMENT_ROOT'];
include_once("mobile_detect.php");
if($isMobile){
	include_once('../jsmb/register.php');
	die;
}

include_once($path."/profile/connect.inc");
include_once($path."/profile/arrays.php");
include_once($path."/profile/screening_functions.php");
include_once($path."/profile/cuafunction.php");
include_once($path."/profile/hits.php");
include_once($path."/profile/registration_functions.inc");
include_once($path."/profile/mobile_detect.php");

include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;

$db = connect_db();

$smarty->assign("tabs",9);
$smarty->assign("tabe",9);
if($redirect_url)
{
	$smarty->assign("REDIRECT_URL",$redirect_url);
	$smarty->assign("LOGIN_ERR",$LOGIN_ERR);
	$smarty->assign("VIEW_USERNAME",$view_username);
}
$LIVE_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/images_try/liveperson";
$smarty->assign("LIVE_CHAT_URL",$LIVE_CHAT_URL);
global $whichMachine;

/* Send mail in case of blank */

/*if($source=="" && $tieup_source=="" && $newsource=="" && $_COOKIE['JS_SOURCE']=="")
	send_email("serveralerts@jeevansathi.com","Referer is : ".$_SERVER['HTTP_REFERER'],"Registration Page :: Source Blank Referrer");*/

// Offline Changes Anurag starts

$lang=$_COOKIE['JS_LANG'];

//To prevent suprious attack ,checking operator name in PSWRDS table.
$ch_operator=$_GET['operator'];

if($ch_operator!='' && $ch_operator!='deleted')
{
	$smarty->assign("operator",$_GET['operator']);
	setcookie("OPERATOR",$_GET['operator'],0,"/",$domain);
	setcookie("JS_SOURCE",'hpblack',time()+2592000,"/",$domain);
	$_COOKIE['OPERATOR']=$_GET['operator'];
}
else
{
	//Unset all the parameters used while not registring offline profile.
	setcookie("OPERATOR","",0,"/",$domain);
	$_COOKIE['OPERATOR']='';
	if($source=='ofl_prof')
		$source='hpblack';
}

if($lang=="deleted")
	$lang="";

// New Changes as per mantis 4075 (For tracking purpose of cookies) 

if((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"])))
{
	$cookie_str.=":JS_ADNETWORK=".$_COOKIE["JS_ADNETWORK"];
	$cookie_str.=":JS_ACCOUNT=".$_COOKIE["JS_ACCOUNT"];
	$cookie_str.=":JS_CAMPAIGN=".$_COOKIE["JS_CAMPAIGN"];
	$cookie_str.=":JS_ADGROUP=".$_COOKIE["JS_ADGROUP"];
	$cookie_str.=":JS_KEYWORD=".$_COOKIE["JS_KEYWORD"];
	$cookie_str.=":JS_MATCH=".$_COOKIE["JS_MATCH"];
	$cookie_str.=":JS_LMD=".$_COOKIE["JS_LMD"];
	setcookie('JS_CAMP',$cookie_str,time()+2592000,"/");
	setcookie("JS_ADNETWORK","",0,"/");
	setcookie("JS_ACCOUNT","",0,"/");
	setcookie("JS_CAMPAIGN","",0,"/");
	setcookie("JS_ADGROUP","",0,"/");
	setcookie("JS_KEYWORD","",0,"/");
	setcookie("JS_MATCH","",0,"/");
	setcookie("JS_LMD","",0,"/");
}

if(!((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"]))))
{
	if(isset($_COOKIE["JS_CAMP"])){
		$cookies=explode(":",$_COOKIE["JS_CAMP"]);
		$adnet=explode("=",$cookies[1]);
		$acnt=explode("=",$cookies[2]);
		$camp=explode("=",$cookies[3]);
		$adgr=explode("=",$cookies[4]);
		$keywd=explode("=",$cookies[5]);
		$mtch=explode("=",$cookies[6]);
		$lm=explode("=",$cookies[7]);
	}

	if($adnetwork==""){
		if($adnet)
		      $adnetwork=$adnet[1];
	}
	if($account==""){
		if($acnt)
		      $account=$acnt[1];
	}
	if($campaign==""){
		if($camp)
		      $campaign=$camp[1];
	}
	if($adgroup==""){
		if($adgr)
		      $adgroup=$adgr[1];
	}
	if($keyword==""){
		if($keywd)
		      $keyword=$keywd[1];
	}
	if($match==""){
		if($mtch)
		      $match=$mtch[1];
	}
	if($lmd==""){
		if($lm)
		      $lmd=$lm[1];
	}
	
}
else
{
	if($adnetwork==""){
		if(isset($_COOKIE["JS_ADNETWORK"]))
			$adnetwork=$_COOKIE["JS_ADNETWORK"];
	}
	if($account==""){
		if(isset($_COOKIE["JS_ACCOUNT"]))
			$account=$_COOKIE["JS_ACCOUNT"];
	}
	if($campaign==""){
		if(isset($_COOKIE["JS_CAMPAIGN"]))
			$campaign=$_COOKIE["JS_CAMPAIGN"];
	}
	if($adgroup==""){
		if(isset($_COOKIE["JS_ADGROUP"]))
			$adgroup=$_COOKIE["JS_ADGROUP"];
	}
	if($keyword==""){
		if(isset($_COOKIE["JS_KEYWORD"]))
			$keyword=$_COOKIE["JS_KEYWORD"];
	}
	if($match==""){
		if(isset($_COOKIE["JS_MATCH"]))
			$match=$_COOKIE["JS_MATCH"];
	}
	if($lmd==""){
		if(isset($_COOKIE["JS_LMD"]))
			$lmd=$_COOKIE["JS_LMD"];
	}
}

//Ends here change of Mantis 4075 

$keyword_tieup=$keyword;
$smarty->assign("ADNETWORK",$adnetwork);
//trac#1603, adnetwork1 variable will contains site_id of a advertising network
$smarty->assign("ADNETWORK1",$adnetwork1);
$smarty->assign("ACCOUNT",$account);
$smarty->assign("CAMPAIGN",$campaign);
$smarty->assign("ADGROUP",$adgroup);
$smarty->assign("KEYWORD",$keyword_tieup);
$smarty->assign("MATCH",$match);
$smarty->assign("LMD",$lmd);
// assert that some things are not be shown in common templates as is the case with homepage

$smarty->assign("CAMEFROMHOMEPAGE","1");
$smarty->assign("SHOWLOGIN",$showlogin);
$smarty->assign("TIEUP_SOURCE",$source);


//Gets ipaddress of user
$ip = FetchClientIP();
if(strstr($ip, ","))
{
	$ip_new = explode(",",$ip);
	$ip = $ip_new[1];
}

//to check suspected registration from ip address.
include_once($path."/profile/suspected_ip.php");
$suspected_check=doubtfull_ip("$ip");

if($Showphone=='N')
$Showphone='';
if($Showmobile=='N')
$Showmobile='';

if(substr($source,0,2)=="mb" || $frommarriagebureau==1)
{
	$fromprofilepage=1;
	include_once($path."/marriage_bureau/connectmb.inc");
	mysql_select_db_js('marriage_bureau');
	$data=authenticatedmb($mbchecksum);
	if(!$data)
	timeoutmb();
	if($source)
	if($data["SOURCE"]!=$source)
	timeoutmb();

	$source=$data["SOURCE"];
	mysql_select_db_js('newjs');
	
	$smarty->assign("FROMMARRIAGEBUREAU",$fromprofilepage);
}

/* Display for Header Template content */
tieup_creative($source);
/* End Display Header Template content */
if((!$submit_pg2) && (!$submit_pg2_x))
{
	if($source=="" && $tieup_source=="")
	{
		if($newsource!="")
		$source=$newsource;
		elseif(isset($_COOKIE['JS_SOURCE']))
		{
			$source=$_COOKIE['JS_SOURCE'];
			$source=mysql_real_escape_string($source);
			if(!strstr(strtolower($source),"af"))
		        {
		                $sql_check="select SourceID from MIS.SOURCE where SourceID='$source'";
		                $res_check=mysql_query_decide($sql_check);
		                if(!mysql_fetch_row($res_check))
		                {
		                        mysql_query_decide("insert into MIS.UNKNOWN_SOURCE(SOURCE,`DATE`) values('$source',now())");
		                        $source="unknown";
		                }
		        }
		}
		else
		{
			$source="unknown";
			savehit($source,$_SERVER['PHP_SELF']);
		}
		$form_option='a';
	}
	// if source has come in that means that the person has clicked on a banner on jeevansathi
	// we make source blank in index.php before including this file to implement this logic
	else
	{
			$source=mysql_real_escape_string($source);
/*Trac#1327 form option ends*/
			if($source!='onoffreg')
			{
				if(isset($_COOKIE['JS_SOURCE']) && $source!='ofl_prof' && $source!='101')
					$source=$_COOKIE['JS_SOURCE'];
			}
			if(!strstr(strtolower($source),"af"))
			{
				$sql_check="select SourceID from MIS.SOURCE where SourceID='$source'";
				$res_check=mysql_query_decide($sql_check);
				if(!mysql_fetch_row($res_check))
				{
					mysql_query_decide("insert into MIS.UNKNOWN_SOURCE(SOURCE,`DATE`) values('$source',now())");
					$source="unknown";
				}
			}
			savehit($source,$_SERVER['PHP_SELF']);
			//New changes added anurag start
			if($form_banner)
		   		 setcookie("JS_SOURCE",$source,time()+2592000,"/");
	}
}

if(substr($source,0,2)!="mb" && !$frommarriagebureau)
{
	$newip = 1;
	$showlogin = 0;
}
if($source=='mailer_adc'){
	header("Location: $SITE_URL/site_down.htm");
}
$source=mysql_real_escape_string($source);

$DEFAULT_US = array("Google NRI US","rediff_us_fm","yahoo_nri","sulekha_us_fm");

$sql = "SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='$source'";
$ressource=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate") ;
if(mysql_num_rows($ressource))
{
	$mysource=mysql_fetch_array($ressource);
	$groupname=$mysource["GROUPNAME"];
	if($groupname=="google")
	      $smarty->assign("reg_comp_frm_ggl","1");
	elseif($groupname=="Google_NRI")
	      $smarty->assign("reg_comp_frm_ggl_nri","1");

	if(in_array($groupname,$DEFAULT_US))
	{
		$country_code=128;
	}
	
	$smarty->assign("GROUPNAME",$groupname);
	$smarty->assign("groupname",$groupname);
}

/* Display for Header Template content */
tieup_creative($source);
/* End Display Header Template content */

if(isset($_COOKIE["JS_GENDER"]))
{
	$cookie_gender=$_COOKIE["JS_GENDER"];
}


$smarty->assign('LEADID',$leadid);

$now = date("Y-m-d G:i:s");
$today=date("Y-m-d");

if($sempages || $sem){
	$smarty->assign('SEM','1');
	$sql_sem_cstm="SELECT CONTENT,IMAGE,BOX FROM MIS.SEM_PAGE_CUSTOMIZE WHERE SOURCE='$source' AND ACTIVE='Y' AND PAGE='P1'";
	$res_sem_cstm = mysql_query_decide($sql_sem_cstm) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_sem_cstm,"ShowErrTemplate");
	while($row_sem_cstm = mysql_fetch_array($res_sem_cstm))
	{
		 $sem_content=$row_sem_cstm['CONTENT'];
		 $sem_box=$row_sem_cstm['BOX'];
		 if($sem_box=='B1')
			 $smarty->assign('P1B1_CONTENT',$sem_content);
		 else if($sem_box=='B2')
			 $smarty->assign('P1B2_CONTENT',$sem_content);
		 else if($sem_box=='B3')
		 {
			 $sem_images=$row_sem_cstm['IMAGE'];
			 $smarty->assign('P1B3_CONTENT',$sem_content);
		 }
		 else if($sem_box=='B4')
			 $smarty->assign('P1B4_CONTENT',$sem_content);
		 $smarty->assign('SEM_IMAGE',$sem_images);
	}
}

if($submit_pg2 || $submit_pg2_x) // for the IE
{
	/*Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute*/
	$ts = time();
	$current_time = date("Y-m-d G:i:s",$ts);
	$ts -= 60;
	$before_one_minute = date("Y-m-d G:i:s",$ts);

	$sql_ip = "SELECT IP FROM newjs.BLOCK_IP WHERE IP = '$ip' AND TIME BETWEEN '$before_one_minute' AND '$current_time'";
	$res_ip = mysql_query_decide($sql_ip) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ip,"ShowErrTemplate");
	if(mysql_num_rows($res_ip) > 5)
	{
		die("Too many requests !");
	}
	else
	{
		$sql_ip_ins = "INSERT INTO newjs.BLOCK_IP(IP,TIME) VALUES('$ip','$now')";
		mysql_query_decide($sql_ip_ins) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ip_ins,"ShowErrTemplate");
	}
	/*End of - Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute*/
	if($hit_source!='O')
	{
		/****  check for banner sources*****/
		$sql="SELECT FORCE_EMAIL FROM MIS.SOURCE WHERE SOURCEID = '$tieup_source'";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($result);

		$force_mail=$row["FORCE_EMAIL"];
		if($force_mail=='Y')
   	             $email_validation='Y';

		$is_error=0;

		if(!$relationship)
		{
			$is_error++;
			$smarty->assign("relationship_err","1");
		}
		if(!$frommarriagebureau)
		{
		   if(1)
	           {
			 if(1)
			 {
				$email_flag = checkemail($email);
                                $old_email_flag = checkoldemail($email);
				if($email=="")
				{
					$is_error++;
					$smarty->assign("email_err",'4');
				} 
				 elseif($email_flag == 1 || $af_email_flag == 1)
                                 {
				      $is_error++;
				      $smarty->assign("email_err","1");
				 }
				 elseif($email_flag == 2 || $old_email_flag == 2 || $af_email_flag == 2) // For Existing email
                                 {
                                      $activated = get_profile_active_status($email);
                                      if($activated == "D")
	                                          $link = "<a href=\"".$SITE_URL."/profile/faq_other.php?retrieve_profile=1&email=$email\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\" target=\"_blank\">";
                                      else
        	                                  $link = "<a href=\"\" name=\"forgot_password_link\" id=\"forgot_password_link\">";
				      if($activated=='D')
                	                        $smarty->assign('prof_act','D');

				      $smarty->assign("email_err",'5');
				      $is_error++;
				 }
				 elseif($email_flag == 3 || $old_email_flag == 3)
                                 {
                                        $is_error++;
					$smarty->assign("email_err",'2');
                                 }
                                 elseif($email_flag == 4)
                                 {
                                        $is_error++;
					$smarty->assign("email_err",'3');
                                 }
                          
                        }
                        elseif(!$email)
                        {
                                $is_error++;
				$smarty->assign("email_err",'4');
                        }
   		}
		
 		$entered_password = $password;

                $password = trim($password);
                if(!$password)
                {
                        $is_error++;
			$smarty->assign("passwrd_err",'1');
                }
                elseif($password && !$frommarriagebureau)
                {
                        if(strlen($password)>40 || strlen($password)<6)
                        {
                                $is_error++;
				$smarty->assign("passwwrd_err",'2');
                        }
                        elseif($entered_password != $password)
                        {
                                $is_error++;
				$smarty->assign("passwrd_err",'3');
                        }
                }

                if(!$gender)
                {
                        $is_error++;
			$smarty->assign("gender_err",'1');
                }

                if(!$day || !$month || !$year)
                {
                        $is_error++;
			$smarty->assign("dtOfBirth_err",'1');
                }
                elseif(!checkdate($month,$day,$year))
                {
                        $is_error++;
			$smarty->assign("dtOfBirth_err",'1');
                }
 		else
                {
	                $date_of_birth = $year."-".$month."-".$day;
                        $age = getAge($date_of_birth);
				
			function GetLastDayofMonth($year, $month){
			    for ($day=31; $day>=28; $day--){
			            if (checkdate($month, $day, $year)){
				                return $day;
				    }
		    	    }
		        }
			$no_of_days=GetLastDayofMonth($year,$month);
			
			if($day>$no_of_days)
			{
                                $is_error++;
				$smarty->assign("dtOfBirth_err",'4');
			}
                        else if($gender == "M" && $age < 21)
                        {
                                $is_error++;
				$smarty->assign("dtOfBirth_err",'2');
                        }
                        elseif($gender == "F" && $age < 18)
                        {
                                $is_error++;
				$smarty->assign("dtOfBirth_ERR",'3');
                        }
                }
		if($gender=='F')
			$mstatus=$mstatus_female;
		
 		if(!$mstatus)
                {
                        $is_error++;
			$smarty->assign("mstatus_err",'1');
			$smarty->assign("mstatus_err1",'1');
                }
		if($mstatus !='N' && !$has_children)
		{
			$is_error++;
			$smarty->assign("has_children_err",'1');
		}
		if($mstatus!='N' && $mstatus!="")
		{
		      $smarty->assign("show_has_child",1);
		}
		if($mstatus=='M' && $gender=='F')
		{
			$is_error++;
			$smarty->assign("mstatus_err",'1');
			$smarty->assign("mstatus_err3",'1');
		}

		if(!$height)
                {
                        $is_error++;
			$smarty->assign("height_err",'1');
                }
       	        $country_residence_val = explode("|X|",$country_residence);
                $country_residence_val = explode("|}|",$country_residence_val[0]);
                $country_residence = $country_residence_val[1];
                if($country_residence=="")
                {
                        $is_error++;
			$smarty->assign("countryResidence_err",'1');
                }

		//Blank the city field if country is not india or usa
		if($country_residence!=51 && $country_residence!=128)
			$city_residence="";

 		if($city_residence!='0')
		{
			$city_residence_val = explode("|{|",$city_residence);
	                $city_residence = $city_residence_val[1];
			if($country_residence==51 || $country_residence==128)
			{
				if($city_residence=="" )
				{
					$is_error++;	
					$smarty->assign("cityResidence_err",'1');
				}
			}
		}	
		if(!$frommarriagebureau)
                {

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

                                if($country_residence == "51")
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
                                if($phone && checkrphone($phone))
                                {
                                        $is_error++;
					$smarty->assign("PHONE_ERR",'2');
                                }
                                if(!$showphone)
                                {
                                        $is_error++;
					$smarty->assign("SHOW_PHONE_ERR",'1');
                                }
				if($phone)
					$smarty->assign("PHONE_DISPLAY",'1');
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
								if($country_code_mob==="0")
									$country_code_mob="+91";
								if(strpos($country_code_mob,'+')===false)
									$country_code_mob="+".$country_code_mob;
                                if(!$showmobile)
                                {
                                        $is_error++;
					$smarty->assign("SHOW_MOBILE_ERR",'1');
                                }
                        }
                }
		if(!$mtongue)
                {
                        $is_error++;
			$smarty->assign("mtongue_err",'1');
                }
 		if(!$religion)
                {
                        $is_error++;
			$smarty->assign("religion_err",'1');
                }
                elseif($religion)
                {
                        $religion_temp = explode("|X|",$religion);
                        $religion_val = $religion_temp[0];
                        $check_partner_caste = 1;

			if($religion_val!='2' && $mstatus=='M')
			{
				$is_error++;
				$smarty->assign("religion_err2",'1');
				$smarty->assign("mstatus_err",'1');
			}
			
                        if($religion_val == "5" || $religion_val == "6" || $religion_val == "7" || $religion_val == "8"||$religion_val=="10")
                        {
                                $sql = "SELECT VALUE FROM newjs.CASTE WHERE PARENT='$religion_val'";
                                $res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $row = mysql_fetch_array($res);
                                $caste = $row["VALUE"];
                                $check_partner_caste = 0;
                        }
                }

		if($caste=="")
                {
                        $is_error++;
			$smarty->assign("caste_err",'1');
                }
		else
		{
			   $sql = "SELECT PARENT,SMALL_LABEL FROM CASTE WHERE VALUE='$caste'";
                           $result = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                           $myrow = mysql_fetch_array($result);
                           $caste_label = $myrow["SMALL_LABEL"];

                           if($religion != "" && $myrow['PARENT'] != $religion_val)
                           {
                                   $is_error++;
		         	   $smarty->assign("caste_err",'1');
                           }
		}

 	      if(!$termsandconditions)
              {
                     $is_error++;
		     $smarty->assign("terms_err","1");
              }
	}
	else
	{
		$is_error = 0;
                if(!$frommarriagebureau)
                {
                        $email_flag = checkemail($email);
                        $old_email_flag = checkoldemail($email);
			if($email=="")
			{
				$is_error++;
				$smarty->assign("email_err",'4');
			}
                        if($email_flag == 1 || $af_email_flag == 1)
                        {
                                $is_error++;
				$smarty->assign("email_err",'1');
                        }
                      elseif($email_flag == 2 || $old_email_flag == 2)
                        {
                                $activated = get_profile_active_status($email);

                                if($activated == "D")
                                	$link = "<a href=\"".$SITE_URL."/profile/faq_other.php?retrieve_profile=1&email=$email\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\" target=\"_blank\">";
                                else
	                                $link = "<a href=\"\" name=\"forgot_password_link\" id=\"forgot_password_link\">";
				if($activated=='D')
					$smarty->assign('prof_act','D');
                                $is_error++;
				$smarty->assign("email_err","5");	
                        }
                        elseif($email_flag == 3 || $old_email_flag == 3)
                        {
                                $is_error++;
				$smarty->assign("email_err","3");	
                        }
			elseif($email_flag == 4)
                        {
                                $is_error++;
				$smarty->assign("email_err","4");	
                        }
                }
                if($year && $month && $day)
                $date_of_birth = $year."-".$month."-".$day;
	}
	if($is_error)
	{
		//print_r($smarty->_tpl_vars);
		$smarty->assign("TIEUP_SOURCE",$tieup_source);
		$smarty->assign("SEM",$sem);
		$smarty->assign("phone",$phone);
		$smarty->assign("mtongue",$mtongue);
		$smarty->assign("NAME",$fname);
		$smarty->assign("RELATIONSHIP",$relationship);
		$smarty->assign("COUNTRY_CODE_MOB",$country_code_mob);
		$smarty->assign("COUNTRY_CODE",$country_code);
		$smarty->assign("STATE_CODE",$state_code);
		$smarty->assign("EMAIL",htmlspecialchars(stripslashes($email),ENT_QUOTES));
		$smarty->assign("GENDER",$gender);
		$smarty->assign("DAY",$day);
		$smarty->assign("MONTH",$month);
		$smarty->assign("YEAR",$year);
		$smarty->assign("MSTATUS",$mstatus);
		$smarty->assign("HAVE_CHILDREN",$has_children);
		$smarty->assign("COUNTRY_RESIDENCE",$country_residence);
		$smarty->assign("CITIZENSHIP",$citizenship);
		$smarty->assign("STATE_CODE",$state_code);
		$smarty->assign("CITY_RESIDENCE",$city_residence);
		$smarty->assign("PHONE",$phone);
		$smarty->assign("SHOWPHONE",$showphone);
		$smarty->assign("MOBILE",$mobile);
		$smarty->assign("SHOWMOBILE",$showmobile);
		$smarty->assign("OCCUPATION",$occupation);
		$smarty->assign("RELIGION",$religion);
		$smarty->assign("CASTE",$caste);
		$smarty->assign("PROMO",$promo);
		$smarty->assign("SERVICE_EMAIL",$service_email);
		$smarty->assign("SERVICE_SMS",$service_sms);
		$smarty->assign("SERVICE_CALL",$service_call);
		$smarty->assign("MEMB_MAILS",$memb_mails);
		$smarty->assign("MEMB_SMS",$memb_sms);
		$smarty->assign("MEMB_IVR",$memb_ivr);
		$smarty->assign("SEC_SOURCE",$secondary_source);
  	}
	else
	{
			include_once("registration_submit.inc");
	}
	}
}
else
{

	if($from_google)
	{
		$array = array($year, $month, $day);
		$date_of_birth= implode("-", $array);

		$sql = "INSERT INTO FROM_GOOGLE(GENDER,EMAIL,DTOFBIRTH,PHONE_RES,PHONE_MOB,SHOWPHONE_MOB) Values ('$Gender','$Email','$date_of_birth','$Phone','$Mobile','$Showmobile')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

		$smarty->assign("GENDER",$gender);
		if($_COOKIE['OPERATOR']=="")    

		$smarty->assign("EMAIL",$email);
		$smarty->assign("DAY",$day);
		$smarty->assign("MONTH",$month);
		$smarty->assign("COUNTRY_CODE",$country_Code);
		$smarty->assign("STATE_CODE",$state_Code);
		$smarty->assign("PHONE",$phone);
		$smarty->assign("MOBILE",$mobile);
		$smarty->assign("TIEUP_SOURCE",$tieup_source);
		$smarty->assign("SEC_SOURCE",$secondary_source);

		$smarty->assign("NEWIP",$newip);

		if($Showmobile)
		$smarty->assign("SHOWPHONE",'N');
		else
		$smarty->assign("SHOWPHONE",'Y');

		$sql="SELECT COUNT(*) AS CNT FROM FROM_GOOGLE_HITS  WHERE DATE=CURDATE()";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$cnt=$row['CNT'];
		if($cnt>0)
		$sql="UPDATE FROM_GOOGLE_HITS SET PAGE0=PAGE0+1 WHERE DATE=CURDATE()";
		else
		$sql="INSERT INTO FROM_GOOGLE_HITS(DATE,PAGE0) VALUES ('$now''1')";

		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

	@setcookie("JS_SHORT_FORM","1",0,"/");
	if($_COOKIE['OPERATOR']!="" && $source!='101' && $source!='onoffreg')
	{
		$email=date("YmdHis")."@jeevansathi.com";
		$smarty->assign("EMAIL",$email);
	}

	$smarty->assign("CHECKBOXALERT1","A");
	$smarty->assign("CHECKBOXALERT2","S");

        /* Code added for New Landing Page , commenting for some time as we dont need that right now (Small landing page)/
        $smarty->assign("TIEUPSOURCE",$source);
        /* End */
	
	$smarty->assign("HITSOURCE",$hit_source);
	$smarty->assign("NEWIP",$newip);
	$smarty->assign("SHOWPHONE",'Y');
	if(!$showmobile)
	$smarty->assign("SHOWMOBILE","Y");
	$smarty->assign("TIEUP_SOURCE",$source);
        
	if(!$service_email)
		$service_email = "S";
      	if(!$service_sms)
		$service_sms = "S";
      	if(!$service_call)
		$service_call = "S";

      	if(!$promo)
		$promo = "U";
      	if(!$memb_sms)
        	$memb_sms = "S";
      	if(!$memb_ivr)
        	$memb_ivr = "S";
      	if(!$memb_mails)
		$memb_mails = "S";
      
      $smarty->assign('PROMO',$promo);
      $smarty->assign('SERVICE_EMAIL',$service_email);
      $smarty->assign('SERVICE_SMS',$service_sms);
      $smarty->assign('SERVICE_CALL',$service_call);
      $smarty->assign('MEMB_SMS',$memb_sms);
      $smarty->assign('MEMB_IVR',$memb_ivr);
      $smarty->assign('MEMB_MAILS',$memb_mails);
}	
	/* As per Mantis 4855 :: Requirements from vizury */
	
	if($bannersource)
	{
	    	setcookie("JS_SOURCE",$bannersource,time()+2592000,"/");
		savehit($bannersource,'/profile/registration_page1.php');

		if($email)
		{
			$now = date("Y-m-d G:i:s");
			$sql_banner="INSERT IGNORE INTO MIS.REG_LEAD (EMAIL,GENDER,ENTRY_DT,INCOMPLETE,SOURCE,IPADD) VALUES ('$email','$gender','$now','Y','$bannersource','$ip')";
			mysql_query_decide($sql_banner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_banner,"ShowErrTemplate");

			if(mysql_affected_rows($db)==0)
			{
				$sql_lead="SELECT LEAD_CONVERSION FROM MIS.REG_LEAD WHERE EMAIL='$email'";
				$res_lead=mysql_query_decide($sql_lead) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead,"ShowErrTemplate");
				$row_lead=mysql_fetch_array($res_lead);
				$lead_flag=$row_lead['LEAD_CONVERSION'];
				
				if($lead_flag=='N')
				{
					$sql_lead_1="REPLACE INTO MIS.REG_LEAD (EMAIL,GENDER,ENTRY_DT,INCOMPLETE,SOURCE,IPADD) VALUES ('$email','$gender','$now','Y','$bannersource','$ip')";
					mysql_query_decide($sql_lead_1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead_1,"ShowErrTemplate");
				}
			}
			$leadid=mysql_insert_id();
		}

		$smarty->assign('EMAIL',$email);
		$smarty->assign('GENDER',$gender);
		$smarty->assign('LEADID',$leadid);
	}
	
	// Lead Mailer

	if($leadid)
	{
		$sql_leadid="SELECT EMAIL,RELATION,GENDER,DTOFBIRTH,RELIGION,CASTE,MTONGUE,ISD,MOBILE FROM MIS.REG_LEAD WHERE LEADID='$leadid'";
		$res_leadid=mysql_query_decide($sql_leadid) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_leadid,"ShowErrTemplate");
		$row_leadid=mysql_fetch_array($res_leadid);
		
		$email=$row_leadid['EMAIL'];
		$relation=$row_leadid['RELATION'];
		$gender=$row_leadid['GENDER'];
		$dob=$row_leadid['DTOFBIRTH'];
		$religion_lead=$row_leadid['RELIGION'];
		$mtongue=$row_leadid['MTONGUE'];
		$country_Code =$row_leadid['ISD'];
		$mobile =$row_leadid['MOBILE'];	
		$caste =$row_leadid['CASTE'];	
	
		$occurence=strpos($country_Code,"+");
		if($occurence === false)
			$isd=$country_Code;
		else
		{
			$isd_val=explode('+',$country_Code);
			$isd=$isd_val[1];
		}

		$a=explode("-",$dob);
		$day=$a[2];
		$month=$a[1];
		$year=$a[0];
		
		if($email)
			$smarty->assign("EMAIL",$email);
		if($relation)
			$smarty->assign("RELATIONSHIP",$relation);
		if($gender)
			$smarty->assign("GENDER",$gender);
		if($dob)
		{
			$smarty->assign("DAY",$day);
			$smarty->assign("MONTH",$month);
			$smarty->assign("YEAR",$year);
		}
		if($mtongue)
			$smarty->assign("mtongue",$mtongue);
		if($mobile)
                	$smarty->assign("MOBILE",$mobile);
		if($caste)
                	$smarty->assign("CASTE",$caste);
	}

	// Lead Mailer Ends Here 

	$height=create_dd($height,"Height","","","Y");
	$smarty->assign("height",$height);
	$time_to_call_array = array("12","1","2","3","4","5","6","7","8","9","10","11");
	for($i=0;$i<count($time_to_call_array);$i++)
	{
		$smarty->assign('timeToCall',$time_to_call_starts_array[$i]);
	}

        for($i=1;$i<12;$i++)
                $looptime[]=$i;
        $smarty->assign("looptime",$looptime);

	/*Country city dropdown creation*/

	//top city dropdown
	$sql = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE DD_TOP='Y' ORDER BY DD_TOP_SORTBY";
	$res = mysql_query_decide($sql) or logError("error",$sql);
	$top_city_str="";
	while($row = mysql_fetch_array($res))
	{
		$top_city_str = $top_city_str.$row["STD_CODE"]."|{|".$row["VALUE"]."$".$row["LABEL"]."#";
	}
	$top_city_str = $top_city_str." |{} $ #";

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

	/* Changes done for Mini Registration Page */

	
	if($lead || $mini_reg || $leadid)
	{
		if(!$sulekha_lead)
		{
			$caste_temp=explode('||',$caste);
			$caste=$caste_temp[0];
			$religion_val=$caste_temp[1];
		}

		$occ=strpos($country_Code,"+");
		if($occ === false)
			$isd=$country_Code;
		else
		{
			$isd_val=explode('+',$country_Code);
			$isd=$isd_val[1];
		}
		
		if($isd)
		{
			$sql_isd="SELECT VALUE FROM newjs.COUNTRY_NEW WHERE ISD_CODE='$isd'";
			$res_isd = mysql_query_decide($sql_isd) or logError("error",$sql_isd);
			while($row_isd = mysql_fetch_array($res_isd))
			{
				if($row_isd['VALUE']!='51')
					$country_residence=$row_isd['VALUE']."|X";	
			}
		}
		
	}

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

	$option_string="";

	// caste Dropdown generation

	$option_string="";
	$sql_caste = "SELECT SQL_CACHE VALUE,LABEL,PARENT from newjs.CASTE WHERE REG_DISPLAY<>'N' ORDER BY SORTBY";
	$res=mysql_query_decide($sql_caste);
	while($row=mysql_fetch_array($res))
	{
		$REL_CA[$row[2]][0][]=$row[0];
		$REL_CA[$row[2]][1][]=$row[1];
	}

	$sql = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.RELIGION WHERE VALUE <> '8' ORDER BY SORTBY";
	$res = mysql_query_decide($sql) or logError("error",$sql);
	while($row = mysql_fetch_array($res))
	{
		$religion_label_arr[] = $row['LABEL'];
		$religion_value = $row['VALUE'];
		$row_caste=$REL_CA[$religion_value];	
		$start_loop=0;
		while($row_caste)
		{
			if(!$row_caste[0][$start_loop])
				break;
			$caste_value = $row_caste[0][$start_loop];
			$caste_label_arr = explode(": ",$row_caste[1][$start_loop]);
			if($caste_label_arr[1])
			$caste_label = $caste_label_arr[1];
			else
			$caste_label = $caste_label_arr[0];

			$caste_str .= $caste_value."$".$caste_label."#";
			$start_loop++;
		}
		$religion_str= $religion_value."|X|".$caste_str;
		$caste_str="";
		$religion_value_arr[] = substr($religion_str,0,strlen($religion_str)-1);
	}
	$option_string="";		
	for($i=0;$i<count($religion_value_arr);$i++)
	{
		if($religion_lead)
		      $religion_val=$religion_lead;

		$temp_rel = explode("|X|",$religion_value_arr[$i]);

		if($religion_val == $temp_rel[0])
			$option_string.= "<option value=\"$religion_value_arr[$i]\" selected=\"yes\">$religion_label_arr[$i]</option>";
		else
			$option_string.= "<option value=\"$religion_value_arr[$i]\">$religion_label_arr[$i]</option>";
	}
	$smarty->assign("religion",$option_string);
	
	populate_day_month_year();
	$curDate=date('Y', JSstrToTime('-6570 days')); // Finding 18 years back year
//			$curDate = '1991';
	for($i=$curDate;$i>=1939;$i--)
		     $yearArray[]=$i;
//	$smarty->assign('yearArray',$yearArray); /* Now year will be populated from function populate_day_month_year() defined in connect_dd.inc for making concicetency in site */
	$smarty->assign("CURRENT_DATE",date('Y-n-j'));

	//For annullled date
	$option_string="";
	for($i=1937;$i<=date("Y");$i++)
	{
		if($mstatus_year==$i)
			$option_string.="<option value=$i selected >$i</option>";
		else
			$option_string.="<option value=$i>$i</option>";
	}
	$smarty->assign("year_var",$option_string);
	$option_string="";
	for($i=1;$i<32;$i++)
	{
		 if($mstatus_day==$i)
			$option_string.="<option value=$i selected >$i</option>";
		 else
			$option_string.="<option value=$i>$i</option>";
	}
	$smarty->assign("day_var",$option_string);
	$option_string="";
	//end of annulled calculation

	// AUTO Fill the Form from MITR_ID

	if($mitr_id)
	{
		$sql_mitr="select EMAIL,NAME,LNAME,MOBILE from newjs.MOB_PROFILE where ID='$mitr_id'";
		$res_mitr=mysql_query_decide($sql_mitr);
		if($row=mysql_fetch_array($res_mitr))
		{
			$MITR_EMAIL=$row['EMAIL'];
			$MITR_NAME=$row['NAME'];
			$MITR_NAME_L=$row['LNAME'];
			$MITR_MOBILE=$row[3];
			if(strlen($MITR_MOBILE) > '10')
			{
				$MITR_MOBILE=substr("$MITR_MOBILE",-10);
			}

			$smarty->assign("MOBILE",$MITR_MOBILE);
				$smarty->assign("EMAIL",$MITR_EMAIL);
				$smarty->assign("FNAME_USER",$MITR_NAME);
				$smarty->assign("LNAME_USER",$MITR_NAME_L);
			}
		}
		
		if($form_banner)
		{
			$smarty->assign("GENDER",$fGender);
			$smarty->assign("EMAIL",$Email);
			$smarty->assign("DAY",$Day);
			$smarty->assign("MONTH",$Month);
			$smarty->assign("YEAR",$Year);
			$smarty->assign("NAME",$Name);
			$smarty->assign("RELATIONSHIP",$relationship);
		}
		
		if($lead || $mini_reg)
		{
			$array = array($year, $month, $day);
			$dateofbirth= implode("-", $array);
			$now = date("Y-m-d G:i:s");
			
			/*  New check added for checking the status of lead */
			$type ="";
			if($sulekha_lead){
				$country_Code =str_replace('+','',$country_Code);
				$email =trim($email);
				$sql_j ="SELECT count(*) AS CNT from newjs.JPROFILE where EMAIL='$email'";
				$res_j=mysql_query_decide($sql_j) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_j,"ShowErrTemplate");
				$row_j=mysql_fetch_array($res_j);
				$count_j=$row_j['CNT'];	
				if($count_j)
					$type ="INV";
				else
					$type ="A";
			}
			/*  Ends new check */
		
			
			/* Mapping of Gender Based upon Relatiosnhip */

			if($relationship=='1' || $relationship=='2' || $relationship=='6' || $relationship=='4')
				$gender='M';
			elseif($relationship=='2D' || $relationship=='6D' || $relationship=='1D' || $relationship=='4D')
				$gender='F';
			
			if($gender=='F')
				$mstatus_selection=$smarty->assign('SHOW_MSTATUS_FEMALE','1');
			
			/* Making Compatiable as per Jprofile Table */

			if($relationship=='1D')
				$relationship='1';
			elseif($relationship=='4D')
				$relationship='4';
		//Adding checks for email and mobile before saving leads	
			if(!checkemail($email)){
				if($country_Code && $country_Code!="+91")
					$lead_country="";
				else
					$lead_country="51";
				if(check_mobile_phone($mobile,$lead_country))
					$mobile='';
				$sql="INSERT IGNORE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,CASTE,RELIGION,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,TYPE,IPADD) VALUES ('$email','$relationship','$gender','$dateofbirth','$caste','$religion_val','$now','Y','$mtongue','$source','$country_Code','$mobile','$type','$ip')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			if(mysql_affected_rows($db)==0)
			{
				$sql_lead="SELECT LEAD_CONVERSION FROM MIS.REG_LEAD WHERE EMAIL='$email'";
				$res_lead=mysql_query_decide($sql_lead) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead,"ShowErrTemplate");
				$row_lead=mysql_fetch_array($res_lead);
				$lead_flag=$row_lead['LEAD_CONVERSION'];
				
				if($lead_flag=='N')
				{
					$sql_lead_1="REPLACE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,CASTE,RELIGION,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,TYPE,IPADD) VALUES ('$email','$relationship','$gender','$dateofbirth','$caste','$religion_val','$now','Y','$mtongue','$source','$country_Code','$mobile','$type','$ip')";
					mysql_query_decide($sql_lead_1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead_1,"ShowErrTemplate");
				}
			}
			$leadid=mysql_insert_id();
			}
			$smarty->assign("EMAIL",$email);
			$smarty->assign("RELATIONSHIP",$relationship);
			$smarty->assign("GENDER",$gender);
			$smarty->assign("DAY",$day);
			$smarty->assign("MONTH",$month);
			$smarty->assign("YEAR",$year);
			$smarty->assign("mtongue",$mtongue);
			$smarty->assign("LEADID",$leadid);
			$smarty->assign("MOBILE",$mobile);
			$smarty->assign("CASTE",$caste);
		}
		/* SEM Pixel code logic implemented as per Track Ticket #20 */
		$sem_url=$_SERVER['HTTP_HOST'];
		$sem_url_1=explode(".",$sem_url);
		if($sem_url_1[0]=='www')
		{
			unset($sem_url_1[0]);
			$sem_url=implode('.',$sem_url_1);
		}

		$sql_sem="SELECT GA_CODE FROM MIS.SEM_GACODE WHERE URL='$sem_url' AND ACTIVE='Y'";
		$res_sem=mysql_query_decide($sql_sem) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$res_sem,"ShowErrTemplate");
		if($row_sem=mysql_fetch_array($res_sem))
			$pixel_code=$row_sem['GA_CODE'];
		$smarty->assign('SEM_PIXEL',$pixel_code);
		$smarty->assign('REGISTRATION',1);
		if(!isset($_COOKIE["ISEARCH"]))
			$smarty->assign('ISEARCH_COOKIE_NOTSET','1');
$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif","JSREGPAGE1URL"));
		/* Ends Here */
		$smarty->display("registration_pg1.htm");

		

/* flush the buffer */
if($zipIt && !$dont_zip_now)
	ob_end_flush();
?>
