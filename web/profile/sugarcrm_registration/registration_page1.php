<?php
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

include_once($path."/profile/connect.inc");
include_once($path."/profile/arrays.php");
include_once($path."/profile/screening_functions.php");
include_once($path."/profile/cuafunction.php");
include_once($path."/profile/hits.php");
include_once($path."/profile/registration_functions.inc");
include_once($path."/profile/auto_reg_functions.php");

include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($path."/profile/mobile_detect.php");
include_once($path."/sugarcrm/include/utils/JsToLeadFieldMapping.php");
include_once($path."/sugarcrm/custom/crons/JsSuccessAutoRegEmail.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/ProfileInsertLib.php");
$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;

$db = connect_db();

//$IMG_URL = $SITE_URL."/profile/images/registration_revamp_new";
//$smarty->assign("IMG_URL",$IMG_URL);
$smarty->assign("tabs",9);
$smarty->assign("tabe",9);
$smarty->assign("record_id",$record_id);
$LIVE_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/images_try/liveperson";
$smarty->assign("LIVE_CHAT_URL",$LIVE_CHAT_URL);

global $whichMachine;


/* Send mail in case of blank 
if($source=="" && $tieup_source=="" && $newsource=="" && $_COOKIE['JS_SOURCE']=="" && $sugar_incomplete!='Y'){
	send_email("serveralerts@jeevansathi.com","Referer is : ".$_SERVER['HTTP_REFERER'],"Registration Page :: Source Blank Referrer");
}*/
//Seconday source for sugar leads S=self;M=Sugar mail;C=Sugar call;I=Incomplete mailer
//If it is blank then set it to S (self)
$secondary_source=mysql_escape_string(trim($secondary_source));
if(!$secondary_source)
	$secondary_source='S';
$smarty->assign('SEC_SOURCE',$secondary_source);
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
/*if from sugarcrm autofollow email link starts */


$smarty->assign("Edit",$Edit);
if($Edit && !$submit_pg2)
{
	    $smarty->assign("LEAD_DATA","Y");
		$smarty->assign("TIEUP_SOURCE",$tieup_source);
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
		if($mstatus!='N')
			$smarty->assign("show_has_child","Y");
		$smarty->assign("HAVE_CHILDREN",$has_children);
		$smarty->assign("COUNTRY_CODE",$country_code);
		$smarty->assign("CITIZENSHIP",$citizenship);
		$smarty->assign("STATE_CODE",$state_code);
		$smarty->assign("PHONE",$phone);
		$smarty->assign("SHOWPHONE",$showphone);
		$smarty->assign("MOBILE",$mobile);
		$smarty->assign("SHOWMOBILE",$showmobile);
		$smarty->assign("OCCUPATION",$occupation);
		$smarty->assign("RELIGION",$religion);
		$smarty->assign("CASTE",$caste);
		$smarty->assign("MATCH_ALERT",$match_alerts);
		//$smarty->assign("SPEAK_URDU",$speak_urdu);
		$smarty->assign("COUNTRY_RESIDENCE",$country_residence);
 		$smarty->assign("CITY_RESIDENCE",$city_residence);		
		$smarty->assign("PROMO",$promo);
		$religion_temp = explode("|X|",$religion);
        $religion_val = $religion_temp[0];		

		$country_residence_val = explode("|X|",$country_residence);
		$country_residence_val = explode("|}|",$country_residence_val[0]);
		$country_residence = $country_residence_val[1];

		if($city_residence!='0')
			{
				$city_residence_val = explode("|{|",$city_residence);
				$city_residence = $city_residence_val[1];
			}
		$smarty->assign("SERVICE_MESSAGES",$service_messages);
		//$smarty->display("registration_revamp.htm");
		$smarty->assign("CONTACT_OPTION",$contact_option);
		$smarty->assign("DEGREE",$degree);
		$smarty->assign("INCOME",$income);
		$smarty->assign("DRINK",$drink);
		$smarty->assign("SMOKE",$smoke);
		$smarty->assign("record_id",$record_id);
		$smarty->assign("sugar_height",$sugar_height);
		$smarty->assign("sugar_contact_number",$sugar_contact_number);
		$smarty->assign("from_sugar_exec",$from_sugar_exec);
		if($city_residence===0)
			$smarty->assign("sugar_city","zero");
		else
			$smarty->assign("sugar_city",$city_residence);

}
if($record_id && !$submit_pg2 && !$Edit){
	$record_id=mysql_real_escape_string($record_id);
    $smarty->assign("dir_from_sugar","Y");
    $smarty->assign("from_sugar_exec",$from_sugar_exec);
	$lead_sql="SELECT * from sugarcrm.leads as leads,sugarcrm.leads_cstm as ls where leads.id='$record_id' AND ls.id_c='$record_id'";
    $lead_result=mysql_query_decide($lead_sql);
	$lead_data=array();
	$lead_row=mysql_fetch_array($lead_result);
	$sugar_username=trim($lead_row['jsprofileid_c']);
	if($lead_row){
		if(!empty($lead_row['enquirer_email_id_c']))$email=$lead_row['enquirer_email_id_c'];
		else{
			$lead_email_sql="select email_address from sugarcrm.email_addresses as ea, sugarcrm.email_addr_bean_rel as eabr where eabr.bean_id='$record_id' AND eabr.email_address_id=ea.id AND eabr.deleted <> '1'";
			$lead_email_res=mysql_query_decide($lead_email_sql);
			$lead_email_row=mysql_fetch_array($lead_email_res);
			if($lead_email_row)
				$email=$lead_email_row['email_address'];
		}
		if(!empty($email))
			$lead_data['email']=$email;
		foreach($PAGE1 as $lead_name => $profile_name){
			switch($lead_name){

			case "caste_c":{
			$caste_c=$lead_row[$lead_name];
			if(!$caste_c)
				$religion_val=$lead_row['religion_c'];
			else{
			$caste_cArr=explode("_",$caste_c);
			$religion_val=$caste_cArr[0];
			$caste=$caste_cArr[1];
			}
			if(!empty($caste))
				$lead_data['caste']=$caste;
		}
		break;
	case "city_c":{
		$city_residence=trim($lead_row['city_c']);
		$lead_data['city_residence']=$city_residence;
		if(array_key_exists($city_residence,$CITY_INDIA_DROP)||$city_residence==0){
			$country_residence=51;
			$sugar_country_res="India";
		}else{
			$city_sql="select SQL_CACHE LABEL,COUNTRY_VALUE from newjs.CITY_NEW where VALUE='$city_residence'";
			$city_result=mysql_query_decide($city_sql);
			$city_row=mysql_fetch_array($city_result);
			$sugar_city_residence=$city_row['LABEL'];
			$country_residence=$city_row['COUNTRY_VALUE'];
			if($country_residence=='128')
				$sugar_country_res='United States';
			else{
				$country_sql="select SQL_CACHE LABEL from newjs.COUNTRY_NEW where VALUE='$country_residence'";
				$country_result=mysql_query_decide($country_sql);
				$country_row=mysql_fetch_array($country_result);
				$sugar_country_res=$country_row['LABEL'];
			}
		}
	}
		break;
	case "phone_mobile":{
			if(!empty($lead_row['enquirer_mobile_no_c'])){
				$mobile=$lead_row['enquirer_mobile_no_c'];
				$country_code_mob=$lead_row['isd_enquirer_c'];
			}
			else{ 
				$mobile=$lead_row['phone_mobile'];
				$country_code_mob=$lead_row['isd_c'];
			}
	}
		break;
	case "height_c":
			{
				if($lead_row['height_c']!=0){
					$height=$lead_row['height_c'];
					$sugar_height=$height;
					$sql = "select SQL_CACHE LABEL from newjs.HEIGHT where VALUE=$height";
					$res = mysql_query_optimizer($sql) or logError("error",$sql);
					$myrow = mysql_fetch_array($res);
					$lead_data['sugar_height_label']=$myrow['LABEL'];
				}
			}
		break;
	case "date_birth_c":
		if($lead_row['date_birth_c']){
						$birthdate=$lead_row['date_birth_c'];
						$bdate_arr=explode('-',$birthdate);
						$year=$bdate_arr[0];
						$month=$bdate_arr[1];
						$day=$bdate_arr[2];
						$lead_data['birthdate']=$birthdate;
				}
				break;
	case "posted_by_c":
				{
					if($lead_row['posted_by_c']){
						switch($lead_row['posted_by_c']){
						case 2:
							$lead_data['relationship']=$lead_row['gender_c']=='M'?'2':'2D';
							break;
						case 3:
						case 5:
							$lead_data['relationship']=$lead_row['gender_c']=='M'?'6':'6D';
							break;
						default:
							$lead_data['relationship']=$lead_row['posted_by_c'];
							break;
						}
						$relationship=$lead_data['relationship'];
					}
				}
		break;

	case "have_children_c":
						{
							if($lead_row['have_children_c']==='0')
								$has_children="N";
							if($lead_row['have_children_c']=='1')
								$has_children="YS";
							$lead_data['has_children']=$has_children;
						}
				break;
	default:
		{
			$$profile_name=$lead_row[$lead_name];
			if(!empty($$profile_name))
				$lead_data[$profile_name]=$$profile_name;
		}
	break;	
		}
		}
		if(!empty($mobile)){
			if(empty($country_code_mob))$country_code_mob="+91";
			$lead_data['contact_no']="$country_code_mob-$mobile";
			$lead_data['mobile']=$mobile;
			$showphone='Y';
			$showmobile='Y';
			$contact_option='M';
		}else
			{
				$showphone='Y';
				$showmobile='Y';
				$contact_option="L";
			if(!empty($lead_row['enquirer_landline_c'])){
				$phone=$lead_row['enquirer_landline_c'];
				$country_code=$lead_row['isd_enquirer_c'];
				$state_code=$lead_row['std_enquirer_c'];
			}
			else{ 
				$phone=$lead_row['phone_home'];
				$lead_data['phone']=$phone;
				$country_code=$lead_row['isd_c'];
				$state_code=$lead_row['std_c'];
			}
			if(!empty($country_code))
				$contact_str="$country_code-";
			if(!empty($state_code))
				$contact_str.="$state_code-";
			$contact_str.=$phone;
			if(!empty($phone))$lead_data['contact_no']=$contact_str;
			}
			if(empty($country_code))$country_code="+91";

	

	//	print_r($lead_data);
		foreach($lead_data as $var_name => $var_value){
			switch($var_name){
				case "seriousness_count":
                                        $smarty->assign("seriousness_count",$var_value);
                                        break;
				case "contact_no":
					$smarty->assign("sugar_contact_number",$var_value);
					break;
				case "mtongue":
					$smarty->assign("mtongue",$mtongue);
					$smarty->assign("sugar_mtongue",$MTONGUE_DROP[$mtongue]);
					break;
				case "relationship":
					$smarty->assign("RELATIONSHIP",$relationship);
					$smarty->assign("sugar_relationship",$RELATIONSHIP_DROP[$relationship]);
					break;
				case "email":
					$smarty->assign("EMAIL",htmlspecialchars(stripslashes($email),ENT_QUOTES));
					break;
				case "gender":
					$smarty->assign("GENDER",$gender);
					if($gender=='M')$gender_str="Male";
					else 
						$gender_str="Female";
					$smarty->assign("sugar_gender",$gender_str);
					break;
		//$smarty->assign("DAY",$day);
		//$smarty->assign("MONTH",$month);
					//$smarty->assign("YEAR",$year);
				case "mstatus":
					$smarty->assign("MSTATUS",$mstatus);
					$smarty->assign("sugar_mstatus",$MSTATUS_DROP[$mstatus]);
					if($mstatus!='N')
						$smarty->assign("show_has_child","Y");
					break;

		//$smarty->assign("HAVE_CHILDREN",$has_children);
		//$smarty->assign("COUNTRY_CODE",$country_code);
		//$smarty->assign("COUNTRY_RESIDENCE",$country_residence);
					//$smarty->assign("CITIZENSHIP",$citizenship);
				case "city_residence":
					$smarty->assign("sugar_city_residence",$CITY_DROP[$city_residence]);
					$smarty->assign("sugar_country_res",$sugar_country_res);
					$smarty->assign("CITY_RESIDENCE",$city_residence);
					$smarty->assign("COUNTRY_RESIDENCE",$country_residence);
					if($city_residence===0)
						$smarty->assign("sugar_city","zero");
					else
						$smarty->assign("sugar_city",$city_residence);
					break;
				case "phone":
					$smarty->assign("PHONE",$phone);
					$smarty->assign("COUNTRY_CODE",$country_code);
					$smarty->assign("STATE_CODE",$state_code);
					break;
		//$smarty->assign("SHOWPHONE",$showphone);
				case "mobile":
					$smarty->assign("MOBILE",$mobile);
					$smarty->assign("COUNTRY_CODE_MOB",$country_code_mob);
					break;
		//$smarty->assign("SHOWMOBILE",$showmobile);
				case "occupation":
					$smarty->assign("OCCUPATION",$occupation);
					$smarty->assign("sugar_occupation",$OCCUPATION_DROP[$occupation]);
					break;
				case "religion":
					$smarty->assign("sugar_religion",$RELIGIONS[$religion_val]);
					$smarty->assign("RELIGION",$religion);
					break;
				case "caste":
					switch ($caste){

					case '148':
						$caste_label="Jewish";
						break;
					case '1':
						$caste_label="Buddhist";
						break;
					case '153':
						$caste_label="Parsi";
						break;
					default:
					    $caste_var_arr=explode(":",$CASTE_DROP[$caste]);
						$caste_label=trim($caste_var_arr[1]);
					}
					$smarty->assign("sugar_caste",$caste_label);
					$smarty->assign("CASTE",$caste);
				case "smoke":
					switch($smoke){
						case "Y":$smoke_str="Yes";
						break;
						case "N":$smoke_str="No";
						break;
						case "O":$smoke_str="Occasionally";
						break;
					default:
						$smoke="";
					}
					$smarty->assign("SMOKE",$smoke);
					$smarty->assign("sugar_smoke",$smoke_str);
					break;
				case "drink":
					switch($drink){
						case "Y":$drink_str="Yes";
						break;
						case "N":$drink_str="No";
						break;
						case "O":$drink_str="Occasionally";
						break;
					default:$drink="";
					}
					$smarty->assign("sugar_drink",$drink_str);
					$smarty->assign("DRINK",$drink);
					break;
				case "income":
					$smarty->assign("INCOME",$income);
					$smarty->assign("sugar_income",$INCOME_DROP[$income]);
					break;
				case "degree":
					$smarty->assign("DEGREE",$degree);
					$smarty->assign("sugar_degree",$EDUCATION_LEVEL_NEW_DROP[$degree]);
					break;
				case "sugar_height_label":
					$smarty->assign("sugar_height_label",$var_value);
					$smarty->assign("sugar_height",$sugar_height);
					break;
				case "birthdate":
					$smarty->assign("birthdate",$var_value);
					$smarty->assign("DAY", $day);
					$smarty->assign("MONTH",$month);
					$smarty->assign("YEAR",$year);
					break;
				case "has_children":
					$smarty->assign("HAVE_CHILDREN",$var_value);
					switch($var_value){
					case 'N':
						$smarty->assign("HAVE_CHILDREN_VAL","No");
						break;
					case 'YS':
						$smarty->assign("HAVE_CHILDREN_VAL","Yes");
					}
					break;
	}
}
if(count($lead_data)>1)
	    $smarty->assign("LEAD_DATA","Y");
		$smarty->assign("COUNTRY_CODE_MOB",$country_code_mob);
		$smarty->assign("COUNTRY_CODE",$country_code);
	    $smarty->assign("SHOWPHONE",$showphone);
	    $smarty->assign("SHOWMOBILE",$showmobile);
		$smarty->assign("CONTACT_OPTION",$contact_option);
	}			
	if($sugar_incomplete=='Y' || $sugar_username){
		 $username=$sugar_username;
		 if($username){
		     $sql="select PROFILEID,INCOMPLETE from newjs.JPROFILE where USERNAME='$username'";
		     $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate") ;
		     $row=mysql_fetch_array($res);
			 $profileid=$row['PROFILEID'];
			 $if_incomplete=$row['INCOMPLETE'];
		 }
		 if($if_incomplete=='N'){
			 $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
			 $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
			 $smarty->display("sugar_error_template.htm");
			 die;
		 }
		 else
		 {
			 $sugar_incomplete='Y';
			include_once("registration_page2a.php");
			die;
	}
	}
}
//If religion is already selected for a sugar lead assign value to religion
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
			if($secondary_source=="S")savehit($source,$_SERVER['PHP_SELF']);
		}
	}
	// if source has come in that means that the person has clicked on a banner on jeevansathi
	// we make source blank in index.php before including this file to implement this logic
	else
	{
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
			if($secondary_source=="S")savehit($source,$_SERVER['PHP_SELF']);
			//New changes added anurag start
			if($form_banner)
		    setcookie("JS_SOURCE",$source,time()+2592000,"/");
	}
}
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


$smarty->assign("CONTACT_OPTION",'M');

$now = date("Y-m-d G:i:s");
$today= CommonUtility::makeTime(date("Y-m-d"));
if($submit_pg2) // for the IE
{
	/*Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute*/
	$ts = time();
	$current_time = date("Y-m-d G:i:s",$ts);
	$ts -= 60;
	$before_one_minute = date("Y-m-d G:i:s",$ts);
//print_r($_REQUEST);

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
		$errors=array();
		validate_relationship($relationship,$is_error,$errors);
		$email=strtolower($email);
		$activated=validate_email($email,$is_error,$errors);

                                      if($activated == "D")
									  {
										  $link = "<a href=\"".$SITE_URL."/profile/faq_other.php?retrieve_profile=1&email=$email\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\" target=\"_blank\">";
										  $smarty->assign('prof_act','D');
									  }
                                      else
                                          $link = "<a href=\"\" name=\"forgot_password_link\" id=\"forgot_password_link\">";
				      $messages = ".<a>";
				      $link .="click here";
				      $link .= "</a>";
	//if registration is done by executive then autogenerate password
	    if($from_sugar_exec=='Y')
		{
			$password=createJSPassword(6);	
		}

		validate_password($password,$is_error,$errors);
//Validation starts here

		validate_gender($gender,$is_error,$erorrs);
        $age=validate_dob($day,$month,$year,$is_error,$gender,$errors);
		$date_of_birth = $year."-".$month."-".$day;

		validate_maritalStatus($mstatus,$has_children,$gender,$is_error,$errors);
		if($mstatus!='N' && $mstatus!="")
			$smarty->assign("show_has_child",1);
		validate_height($height,$is_error,$errors);
		
//Starts Country and City Residence Validation

		$country_residence_val = explode("|X|",$country_residence);
        $country_residence_val = explode("|}|",$country_residence_val[0]);
		$country_residence = $country_residence_val[1];
// 		validate_country($country_residence,$is_error,$errors);

		//Blank the city field if country is not india or usa
		if($country_residence!=51 && $country_residence!=128)
			$city_residence="";
 		if($city_residence!='0')
		{
			$city_residence_val = explode("|{|",$city_residence);
	                $city_residence = $city_residence_val[1];
		}
		if($country_residence==51 || $country_residence==128)
			validate_city($country_residence,$is_error,$errors);
		if($country_residence==51 && ($city_residence=="DE00" || $city_residence=="MH04" || $city_residence=="MH08"))
		{
			validate_pincode($pincode,$is_error,$errors);	
		}
		else
			$pincode="";
//end country and city residence	
//Start validation of contact number

		if(!$frommarriagebureau)
                {
                        if(!is_numeric($phone))
                        $phone = "";
                        if(!is_numeric($mobile))
                        $mobile = "";
						validate_contact_number($phone,$mobile,$state_code,$country_code,$country_res,$country_code_mob,$showmobile,$showphone,$is_error,$errors);
                }
//end Validating contact number
		
//Starts validating mtongue

		validate_mtongue($mtongue,$is_error,$errors);

//Ends validating mtongue                

		//starts Validating religion
$check_partner_caste=validate_religionandcaste($religion,$is_error,$caste,$errors,'N');
$religion_temp = explode("|X|",$religion);
$religion_val = $religion_temp[0];

//Ends Validating religion and caste

 	      if(!$termsandconditions)
              {
                     $is_error++;
		     $smarty->assign("terms_err","1");
					 $errors[]="terms_err1";
			  }

//Validation ends here
//Starts validating JAMAAT
               if($religion == '2' && $caste == '152')
                 validate_jamaat($jamaat,$is_error,$errors);
 
 //Ends validating JAMAAT
//Starts validating casteMuslim
              if($religion == '2')
                validate_casteMuslim($casteMuslim,$is_error,$errors);

//Ends validating casteMuslim
//	}
if(!empty($record_id)){		
	if(!$degree)
	{
		$is_error++;
		$smarty->assign("degree_err",'1');
		$errors[]="degree_err1";
	}
	if(!$occupation)
	{
		$is_error++;
		$smarty->assign("occupation_err",'1');
		$errors[]="occupation_err1";
	}
	if(!$income)
	{
		$is_error++;
		$smarty->assign("income_err",'1');
		$errors[]="income_err1";
	}
}
	if($is_error)
	{

		foreach($errors as $error_str){
			$error_name=substr($error_str,0,-1);
			$error_value=substr($error_str,-1);
			$smarty->assign($error_name,$error_value);
		}
		//print_r($smarty->_tpl_vars);
		$smarty->assign("TIEUP_SOURCE",$tieup_source);
		$smarty->assign("phone",$phone);
		$smarty->assign("mtongue",$mtongue);
		$smarty->assign("NAME",$fname);
		$smarty->assign("RELATIONSHIP",$relationship);
		$smarty->assign("COUNTRY_CODE_MOB",$country_code_mob);
		$smarty->assign("COUNTRY_CODE",$country_code);
		$smarty->assign("STATE_CODE",$state_code);
		$smarty->assign("PINCODE",$pincode);
		$smarty->assign("EMAIL",htmlspecialchars(stripslashes($email),ENT_QUOTES));
		$smarty->assign("GENDER",$gender);
		$smarty->assign("DAY",$day);
		$smarty->assign("MONTH",$month);
		$smarty->assign("YEAR",$year);
		$smarty->assign("MSTATUS",$mstatus);
		$smarty->assign("HAVE_CHILDREN",$has_children);
		$smarty->assign("COUNTRY_CODE",$country_code);
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
                $smarty->assign("CASTE_MUSLIM",$casteMuslim);
		$smarty->assign("CASTE",$caste);
                $smarty->assign("JAMAAT",$jamaat);
		$smarty->assign("MATCH_ALERT",$match_alerts);
		//$smarty->assign("SPEAK_URDU",$speak_urdu);
               
		$smarty->assign("PROMO",$promo);
		$smarty->assign("SERVICE_EMAIL",$service_email);
		$smarty->assign("SERVICE_SMS",$service_sms);
		$smarty->assign("SERVICE_CALL",$service_call);
		$smarty->assign("MEMB_MAILS",$memb_mails);
		$smarty->assign("MEMB_SMS",$memb_sms);
		$smarty->assign("MEMB_IVR",$memb_ivr);
               
		//$smarty->display("registration_revamp.htm");
		$smarty->assign("CONTACT_OPTION",$contact_option);
		$smarty->assign("DEGREE",$degree);
		$smarty->assign("INCOME",$income);
		$smarty->assign("DRINK",$drink);
		$smarty->assign("SMOKE",$smoke);
		$smarty->assign("record_id",$record_id);
		if($record_id){
			$smarty->assign("LEAD_DATA","Y");
			$smarty->assign("Edit","Edit");
			$smarty->assign("sugar_contact_number",$sugar_contact_number);
			$smarty->assign("from_sugar_exec",$from_sugar_exec);
			$smarty->assign("sugar_height",$sugar_height);
			$smarty->assign("sugar_city",$sugar_city);
		    $smarty->assign("PASSWORD",$password);
		}
  	}
	else
	{
		 	$sql = "SELECT LABEL FROM newjs.HEIGHT WHERE VALUE='$height'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow_height=mysql_fetch_array($res);
                        $height_label=$myrow_height["LABEL"];
                        $height_label=substr($height_label,0,10);

                        $sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_residence'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        $myrow_city=mysql_fetch_array($res);
                        $city_label=$myrow_city["LABEL"];

                        if($gender=='M')
                        $gender_key="Male";
                        elseif($gender=='F')
                        $gender_key="Female";

                        $keyword=addslashes(stripslashes($gender_key.",".$age.",".$Caste_label.",".$height_label.",".$city_label));
			while(1)
			{
				$username=username_gen();
				$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$username'";
				$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$row_username=mysql_fetch_array($res_username);

				$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$username'";
	                        $res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                        $row_username2=mysql_fetch_array($res_username2);
			
				 if($row_username['cnt']==0 && $row_username2['cnt']==0)
					break;
			}

			/* If offline user is registring the profile,then no mailers should be promted.Only Match Alert Mailer will go to onoffreg source users*/

			if($_COOKIE['OPERATOR']!="" && $source!='onoffreg')
			{
				if($tieup_source=='101')
				{
					$tieup_source='101';
					$activated='Y';
				}
				else
				{
					$tieup_source="ofl_prof";
					$activated='Y';
				}
				// till here
				$incomplete='N';
				$match_alerts='N';
				$promo='N';
				$service_messages='N';
				$ANNULLED_SCREEN='Y';
				$SCREENING=131071;
				send_email("nikhil.dhiman@jeevansathi.com","testmessage","1--$profileid---$_COOKIE[OPERATOR]-----$source-----$tieup_source","offline@jeevansathi.com");

			}
			else if($_COOKIE['OPERATOR']!="" && $source=='onoffreg')
			{
				$service_email='S';
				$service_sms='S';
				$service_call='S';
				$incomplete='N';
				$promo='N';
				$memb_sms='S';
				$memb_ivr='S';
				$memb_mails='S';
			}
			else
			{
				$incomplete='Y';
				$activated='N';
				$ANNULLED_SCREEN='N';
				$SCREENING=0;
			}

			
			$country_code = explode('+',$country_code);
			$country_code = $country_code[1];


			 if($relationship=='2')
			 	$smarty->assign("yourHeading","Your Son");
			 elseif($relationship=='2D')
			 	$smarty->assign("yourHeading","Your Daughter");
			 elseif($relationship=='6' || $relationship=='6D')
			 	$smarty->assign("yourHeading","Your Sibling");
			 elseif($relationship=='4')
			 	$smarty->assign("yourHeading","Your Relative/Friend");
			 elseif($relationship=='5')
			 	$smarty->assign("yourHeading","Your Client");

			 if($relationship=='2D')
                               $relationship = '2';
	                 elseif($relationship=='6')
	                       $relationship = '3';
	                 elseif($relationship=='6D')
			        $relationship = '3';

			 if($mstatus=='N')
			       $has_children='';

			 if($service_messages=='')
			       $service_messages='U';

			 if($promo=='')
			       $promo='U';

			 if($match_alerts=='')
			       $match_alerts='U';
			 if($service_sms=='')
			   
				 $service_sms='U';

			 if($service_call=='')

				 $service_call='U';


			 if($promo=='')
				 $promo='U';

			if($memb_sms=='')
				$memb_sms='U';
			if($memb_ivr=='')
				$memb_ivr='U';
			if($memb_mails=='')

				$memb_mails='U';
			 if($service_email=='')
				 $service_email='U';
			  if($service_email=='S')
				  $match_def='A';
			  else
				  $match_def='U';
			 if($memb_sms=='S')
				 $sms_def='Y';
			 else
				 $sms_def='N';
			 $show_horoscope='Y'; /* Inserting  Y by default at the time of Registration as per Bug 43859 */
			$email=trim($email);
			 //Field for identifying the team to which profile belong
			 if($record_id)
				 $crm_team='online';

			$arrCheck = array('drink','speak_urdu','smoke','city_residence','pincode');
			foreach ( $arrCheck as $item) {
				if(!$$item) {
					$$item = '';
				}
			}
			include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
			$passwordEncrypted=PasswordHashFunctions::createHash($password);
			$objInsert = ProfileInsertLib::getInstance();
			$arrFields = array(
				"RELATION" => $relationship,
				"EMAIL" => $email,
				"PASSWORD" => $passwordEncrypted,
				"USERNAME" => $username,
				"GENDER" => $gender,
				"DTOFBIRTH" => $date_of_birth,
				"MSTATUS" => $mstatus,
				"HAVECHILD" => $has_children,
				"HEIGHT" => $height,
				"COUNTRY_RES" => $country_residence,
				"CITIZENSHIP" => '',
				"ISD" => $country_code,
				"STD" =>  $state_code,
				"CITY_RES" =>  $city_residence,
				"PHONE_RES" =>  $phone,
				"SHOWPHONE_RES" =>  $showphone,
				"PHONE_NUMBER_OWNER" =>  '',
				"PHONE_OWNER_NAME" =>  '',
				"PHONE_MOB" =>  $mobile,
				"SHOWPHONE_MOB" =>  $showmobile,
				"MOBILE_NUMBER_OWNER" =>  '',
				"MOBILE_OWNER_NAME" =>  '',
				"TIME_TO_CALL_START" =>  '',
				"TIME_TO_CALL_END" =>  '',
				"EDU_LEVEL_NEW" =>  $degree,
				"OCCUPATION" =>  $occupation,
				"INCOME" =>  $income,
				"MTONGUE" =>  $mtongue,
				"RELIGION" =>  $religion_val,
				"SPEAK_URDU" =>  $speak_urdu,
				"CASTE" =>  $caste,
				"PROMO_MAILS" =>  $promo,
				"SERVICE_MESSAGES" =>  $service_email,
				"ENTRY_DT" =>  $now,
				"MOD_DT" =>  $now,
				"LAST_LOGIN_DT" =>  $today,
				"SORT_DT" =>  $now,
				"AGE" =>  $age,
				"IPADD" => $ip,
				"SOURCE" =>  $tieup_source,
				"ACTIVATED" =>  'N',
				"INCOMPLETE" =>  'Y',
				"KEYWORDS" =>  $keyword,
				"SCREENING" => 0,
				"YOURINFO" => '',
				"DRINK" =>  $drink,
				"SMOKE" => $smoke,
				"CRM_TEAM" => $crm_team,
				"PERSONAL_MATCHES" => $match_def,
				"GET_SMS" => $sms_def,
				"SHOW_HOROSCOPE" => $show_horoscope,
				"SEC_SOURCE" => $secondary_source,
				"SERIOUSNESS_COUNT" => $seriousness_count,
				"PINCODE" =>  $pincode,
                                "SECT" => $casteMuslim,
			);
			$result = $objInsert->insertJPROFILE($arrFields);
			if(false === $result) {
				$sql = "INSERT INTO JPROFILE (RELATION,EMAIL,PASSWORD,USERNAME,GENDER,DTOFBIRTH,MSTATUS,HAVECHILD,HEIGHT,COUNTRY_RES,CITIZENSHIP,ISD,STD,CITY_RES,PHONE_RES,SHOWPHONE_RES,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_MOB,SHOWPHONE_MOB,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,MTONGUE,RELIGION,SPEAK_URDU,CASTE,PROMO_MAILS,SERVICE_MESSAGES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,AGE,IPADD,SOURCE,ACTIVATED,INCOMPLETE,KEYWORDS,SCREENING,YOURINFO,DRINK,SMOKE,CRM_TEAM,PERSONAL_MATCHES,GET_SMS,SHOW_HOROSCOPE,SEC_SOURCE,SERIOUSNESS_COUNT,PINCODE,SECT) VALUES('$relationship','$email','$passwordEncrypted','$username','$gender','$date_of_birth','$mstatus','$has_children','$height','$country_residence','','$country_code','$state_code','$city_residence','$phone','$showphone','','','$mobile','$showmobile','','','','','$degree','$occupation','$income','$mtongue','$religion_val','$speak_urdu','$caste','$promo','$service_email','$now','$now','$today','$now','$age','$ip','$tieup_source','N','Y','$keyword',0,'','$drink','$smoke','$crm_team','$match_def','$sms_def','$show_horoscope','$secondary_source','$seriousness_count','$pincode','$casteMuslim')";
				logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
                        if($jamaat){
                            $sql = "INSERT INTO newjs.JP_MUSLIM(PROFILEID,JAMAAT) VALUES ('$result','$jamaat')";
                            mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        }
//			$sql = "INSERT INTO JPROFILE (RELATION,EMAIL,PASSWORD,USERNAME,GENDER,DTOFBIRTH,MSTATUS,HAVECHILD,HEIGHT,COUNTRY_RES,CITIZENSHIP,ISD,STD,CITY_RES,PHONE_RES,SHOWPHONE_RES,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_MOB,SHOWPHONE_MOB,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,MTONGUE,RELIGION,SPEAK_URDU,CASTE,PROMO_MAILS,SERVICE_MESSAGES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,AGE,IPADD,SOURCE,ACTIVATED,INCOMPLETE,KEYWORDS,SCREENING,YOURINFO,DRINK,SMOKE,CRM_TEAM,PERSONAL_MATCHES,GET_SMS,SHOW_HOROSCOPE,SEC_SOURCE,SERIOUSNESS_COUNT,PINCODE) VALUES('$relationship','$email','$passwordEncrypted','$username','$gender','$date_of_birth','$mstatus','$has_children','$height','$country_residence','','$country_code','$state_code','$city_residence','$phone','$showphone','','','$mobile','$showmobile','','','','','$degree','$occupation','$income','$mtongue','$religion_val','$speak_urdu','$caste','$promo','$service_email','$now','$now','$today','$now','$age','$ip','$tieup_source','N','Y','$keyword',0,'','$drink','$smoke','$crm_team','$match_def','$sms_def','$show_horoscope','$secondary_source','$seriousness_count','$pincode')";
//			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
//			$id=mysql_insert_id_js();
        $id  = $result;
      //New Login
      $authWeb = new WebAuthentication();
        $authWeb->loginFromReg();
		//Added By Lavesh Rawat for Sharding Purpose
		assignServerToProfile($id);
		//Added By Lavesh Rawat for Sharding Purpose
		$profileid=$id;

		$arrAlertFields = array(
			"PROFILEID" => $profileid,
			"MEMB_CALLS" => $memb_ivr,
			"OFFER_CALLS" => $memb_ivr,
			"SERV_CALLS_SITE" => $service_call,
			"SERV_CALLS_PROF" => $service_call,
			"MEMB_MAILS" => $memb_mails,
			"CONTACT_ALERT_MAILS" => $service_email,
			"KUNDLI_ALERT_MAILS" => $service_email,
			"PHOTO_REQUEST_MAILS" => $service_email,
			"SERVICE_MAILS" => $service_email,
			"SERVICE_SMS" => $service_sms,
			"SERVICE_MMS" => $service_sms,
			"SERVICE_USSD" => $service_sms,
			"PROMO_USSD" => $memb_sms,
			"PROMO_MMS" => $memb_sms,
		);
		$result = $objInsert->insertJPROFILE_ALERTS($arrAlertFields);
		if($result === false) {
			$sql2="INSERT INTO newjs.JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS) VALUES ('$profileid','$memb_ivr','$memb_ivr','$service_call','$service_call','$memb_mails','$service_email','$service_email','$service_email','$service_email','$service_sms','$service_sms','$service_sms','$memb_sms','$memb_sms')";
			logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql2,"ShowErrTemplate");
		}
//		$sql2="INSERT INTO newjs.JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS) VALUES ('$profileid','$memb_ivr','$memb_ivr','$service_call','$service_call','$memb_mails','$service_email','$service_email','$service_email','$service_email','$service_sms','$service_sms','$service_sms','$memb_sms','$memb_sms')";
//		mysql_query_decide($sql2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql2,"ShowErrTemplate");
		$sql = "INSERT INTO MIS.REG_COUNT(PROFILEID,PAGE1) VALUES ('$profileid','Y')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		//For EDIT_LOG_JPROFILE_ALERTS
		$now = date("Y-m-d H-i-s");
		$sql3="INSERT IGNORE INTO newjs.JPROFILE_ALERTS_LOG(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS,FROM_PAGE,MOD_DT) VALUES ('$profileid','$memb_ivr','$memb_ivr','$service_call','$service_call','$memb_mails','$service_email','$service_email','$service_email','$service_email','$service_sms','$service_sms','$service_sms','$memb_sms','$memb_sms','S','$now')";
		mysql_query_decide($sql3) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql3,"ShowErrTemplate");
		/* Sending to the 2nd Page for use in 3rd Page */
		

		/* Trac#1021 code ends*/

		$smarty->assign("PROFILEID",$profileid);
		$smarty->assign("RELIGION",$religion_val);
		$smarty->assign("CASTE",$caste);
		$smarty->assign("DRINK",$drink);
		$smarty->assign("SMOKE",$smoke);

		/* Ends Here */

		// Inserting Default Entries in Jpartner 

		$jpartnerObj=new Jpartner;
		$mysqlObj=new Mysql;
		
		if(!$myDb)
		{
		        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		        $myDb=$mysqlObj->connect("$myDbName");
		}

	        $jpartnerObj->setPROFILEID($profileid);

		if($gender=='M')
		     $jpartnerObj->setGENDER('F');
		else
		     $jpartnerObj->setGENDER('M');
	
		if($gender=='M')
	        {
                       if($age<25)
			       	$lage=18;
		       else
			       	$lage=$age-5;
			$hage=$age;
		}
		else
		{
			$lage=($age>29)?$age-2:(($age>26)?$age-1:(($age>22)?$age:21));
			$hage=($age>33)?$age+15:(($age==33)?47:(($age==32)?44:(($age==31)?42:$age+10)));
/*
			$hage=$age+5;
			if($age<21)
				$lage=21;
			else
				$lage=$age;
			if($hage > 70)
				$hage = 70;
*/		}
						
                $jpartnerObj->setLAGE($lage);
                $jpartnerObj->setHAGE($hage);
			
		if($gender=='M')
		{
			$lheight=$height-10;
			$hheight=$height;
		}
		else
		{
			$lheight=$height;
			$hheight = $height+10;
		}	

                $jpartnerObj->setLHEIGHT($lheight);
                $jpartnerObj->setHHEIGHT($hheight);
                $jpartnerObj->setDPP('R');

		$MTONGUE=array(10,7,33,19,28,13);

		//Insert into partner table if mtongue selected is within all hindi mtongue

		if(in_array($mtongue,$MTONGUE))
		{
			foreach($MTONGUE as $key=>$val)
				$mtongue_val.="'".$val."',";
			$mtongue_val=substr($mtongue_val,0,strlen($mtongue_val)-1);
		}
		else
			$mtongue_val="'".$mtongue."'";
		
		$jpartnerObj->setPARTNER_MTONGUE($mtongue_val);

		$religion_partner.="'".$religion_val."'"."'";
		$religion_partner=substr($religion_partner,0,strlen($religion_partner)-1);
		$jpartnerObj->setPARTNER_RELIGION($religion_partner);
		
		$caste_community = $caste."-".$mtongue;
		$sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING WHERE CASTE_COMMUNITY = '$caste_community'";
		$res = mysql_query_decide($sql) or die(mysql_error()) or logError("error",$sql);
		$row = mysql_fetch_assoc($res);
		if($row)
		{
			$caste_community_arr = @explode(",",$row['MAP']);
			for($i=0;$i<count($caste_community_arr);$i++)
			{
				$temp_caste_arr = @explode("-",$caste_community_arr[$i]);
				if(!@in_array($temp_caste_arr[0],$mapped_caste_arr))
					$mapped_caste_arr[] = $temp_caste_arr[0];
			}
		}

		if(is_array($mapped_caste_arr))
		{
			if(!in_array($caste,$mapped_caste_arr))
			      $mapped_caste_arr[]=$caste;
		}
		else      
			      $mapped_caste_arr[]=$caste;

		$mapped_caste="'".@implode("','",$mapped_caste_arr)."'";
		$jpartnerObj->setPARTNER_CASTE($mapped_caste);

		if($mstatus=="N")
			$jpartnerObj->setPARTNER_MSTATUS("'".$mstatus."'");

		$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
		
		/* Default Jpartner Insertion Ends Here */

		//Added by neha verma to store registration caused by sources leading to home page
		if(isset($_COOKIE['JS_SOURCE_HOME']))
		{
			$source = $_COOKIE['JS_SOURCE_HOME'];
			$sql = "INSERT INTO MIS.REG_HOME (DATE,SOURCEID,PROFILEID) VALUES('$now','$source','$id')";
			$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			setcookie("JS_SOURCE_HOME","",time() - 3600,"/");
		}
		//end of code added by neha
		
		//added by Neha Verma for archiving contact info
		//EMAIL
                    
		if($email!='')
		{
			$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'EMAIL')";
			$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$email')";
			$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//PHONE_RES
		if($phone!='')
		{
			$country_code = explode('+',$country_code);
	                $country_code = $country_code[1];

			$phone = $country_code."-".$state_code."-".$phone;
			$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_RES')";
			$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$phone')";
			$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//MOBILE_RES
		if($mobile)
		{
			$country_code = explode('+',$country_code);
			$country_code = $country_code[1];

			$arch_mobile = $country_code_mob."-".$mobile;
			$sql_id_mob= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_MOB')";
			$res_id_mob= mysql_query_decide($sql_id_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_mob= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$arch_mobile')";
			$res_info_mob= mysql_query_decide($sql_info_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//end


		//Assingning profileid to offline OPERATOR
		if($_COOKIE['OPERATOR']!='')
		{
			if($tieup_source=='101')
			{
				$assigned_101="REPLACE INTO jsadmin.ASSIGNED_101 (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) values('$id','".$_COOKIE['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
				$assigned_101="insert into jsadmin.ASSIGNLOG_101 (`PROFILEID`,`OPERATOR`,`DATE`) values('$id','".$_COOKIE['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
			}
			else if($source=='onoffreg')
			{
				 $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$source','$now')";
				 mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

			}
			else
			{
				$offline_assigned="REPLACE INTO jsadmin.OFFLINE_ASSIGNED (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");

				$offline_assigned="INSERT INTO jsadmin.OFFLINE_ASSIGNLOG (`PROFILEID`,`OPERATOR`,`ASSIGN_DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");
			}
		}
		else if($source=='onoffreg' && $_COOKIE['JS_LEAD']) 
                {
                        $lead=$_COOKIE['JS_LEAD'];
                        $sql_up="UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set username_c='$username',jsprofileid_c='$username',converted=1,status=6,refered_by='Registration done by self' where l.id=lc.id_c and l.id='$lead'";
                        mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_up,"ShowErrTemplate");
                        $sql_op="SELECT user_name FROM sugarcrm.leads l, sugarcrm.users as u where l.assigned_user_id=u.id  and l.id='$lead'";
                        $res_op=mysql_query_decide($sql_op) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_op,"ShowErrTemplate");
                        $row_op=mysql_fetch_assoc($res_op);
                        $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$row_op['user_name']."','$source','$now')";
                        mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

                }
		else{
			include_once($path."/sugarcrm/include/utils/systemProcessUsersConfig.php");
                        global $process_user_mapping;
			$nowDate =date("Y-m-d H:i:s");
			if($record_id!='')
				if($from_sugar_exec=='')
			{
			$processUserId=$process_user_mapping["auto_registration"];
                        if(!$processUserId)
                                $processUserId=1;
			$sql_up="UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set jsprofileid_c='$username',username_c='$username',converted=1,status=24,refered_by='Registration done by self',date_modified='$nowDate',disposition_c='23',modified_user_id='$processUserId' where l.id=lc.id_c and l.id='$record_id' and deleted!='1'";
			mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some    time.",$sql_up,"ShowErrTemplate");

			}else
				{
			$processUserId=$process_user_mapping["register_lead_button"];
                        if(!$processUserId)
                                $processUserId=1;
			$sql_up="UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set jsprofileid_c='$username',username_c='$username',converted=1,status=24,refered_by='Followup call',date_modified='$nowDate',disposition_c='23',modified_user_id='$processUserId' where l.id=lc.id_c and l.id='$record_id' and deleted!='1'";
			mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_up,"ShowErrTemplate");
			//Send password by Sms and email here to lead
			smsPasswordAfterRegistration($id,$mobile,$email,$password,$username);
			}
		}
		//CODE ADDED BY Tapan Arora for capture outer variable
		if($adnetwork || $account || $campaign || $adgroup || $keyword_tieup || $match || $lmd)
		{
			$sql="INSERT INTO MIS.TRACK_TIEUP_VARIABLE VALUES('','$adnetwork','$account','$campaign','$adgroup','$keyword_tieup','$match','$lmd',$id,now())";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//CODE Ended By Tapan Arora


		if(isset($_COOKIE['SEARCH_REDIFF']))
		{
			$sql_rediff = "INSERT INTO MIS.REDIFF_SRCH_REG (PROFILEID,ENTRY_DT) VALUES ('$id','$now')";
			mysql_query_decide($sql_rediff) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_rediff,"ShowErrTemplate");
		}

		//Added By lavesh to email authority for informing about suspected email-id.
		if($suspected_check)
//		send_email('vikas@jeevansathi.com',$id,"Profileid of suspected email-id","register@jeevansathi.com");

		if($lang)
		{
			$sql="INSERT INTO MIS.LANG_REGISTER VALUES ('','$id','$lang')";
			mysql_query_decide($sql);
		}

		//Not to add when operator is registring the profile
		if($_COOKIE['OPERATOR']=="")
		{
			$sql_incomp="INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES('$id','$now')";
			mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");
		}


		$vpin=vpin_gen();
		$sql_incomp="INSERT IGNORE INTO infovision.INF_USER_PIN (PROFILEID,VPIN) VALUES ('$id','$vpin')";
		mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");

		if($hit_source!='O')
		{
			$sql_name_insert="INSERT INTO NAMES VALUES ('$username')";
			mysql_query_decide($sql_name_insert) or logError("NAMES TABLE ENTRY NOT DONE.",$sql_name_insert,"ShowErrTemplate");
		}


		if(substr($source,0,2)!="mb")
		{
			$cookies['PROFILEID']=$id;
			$cookies['USERNAME']=$username;
			$cookies['GENDER']=$gender;
			$cookies['SUBSCRIPTION']='';
			$cookies['ACTIVATED']='N';
			$cookies['SOURCE']=$tieup_source;

			$protect_obj->setcookies($cookies);
			$checksum=md5($id)."i".$id;
			$checksum=$protect_obj->js_encrypt($checksum);
		}

		setcookie("JS_SOURCE","",0,"/",$domain);
		//cookie deleted by Tapan Arora after registration
		setcookie("JS_ADNETWORK","",0,"/");
		setcookie("JS_ACCOUNT","",0,"/");
		setcookie("JS_CAMPAIGN","",0,"/");
		setcookie("JS_ADGROUP","",0,"/");
		setcookie("JS_KEYWORD","",0,"/");
		setcookie("JS_MATCH","",0,"/");
		//code ended by Tapan Arora
		setcookie("JS_LMD","",0,"/");


		// Mailer on Registration
    if ('C' == $secondary_source) {

    	$emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($id,$email);
		(new emailVerification())->sendVerificationMail($id,$emailUID);
		first_time_registration_mail($id);
    }

		// Mailer Intergrated for the duplicate contact number holders.
		/* Commenting this issue as per Mantis 4781 */
		/*
		if($mobile)
		{
			$sql = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED = 'Y' AND PHONE_MOB = '$mobile'";
			$res = mysql_query_decide($sql)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");

			while($row = mysql_fetch_array($res))
			{
			       $oldprofile[] = $row['PROFILEID'];
			       $username1[] = $row['USERNAME'];
			}
			
			if(is_array($oldprofile))
			{
				$oldprofile=implode(",",$oldprofile);
				$username1=implode(",",$username1);
			}
			if($username1)
			{
				$contact = "Mobile";
				$msg = "Hi,</br>User Name <b>$username</b> has registered with the $contact number $mobile.</br>We already have profile $username1 with the same number";
				$cc ="productsupport@jeevansathi.com";
				$to ="mahesh@jeevansathi.com";
				$from = "info@jeevansathi.com";
				$subject = "Duplicate $contact Number Tracked";
			        if($whichMachine!='test')
					send_email($to,$msg,$subject,$from,$cc);
			}
		}

		if($phone)
		{
			$sql = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED = 'Y' AND PHONE_RES = '$phone'";
			$res = mysql_query_decide($sql)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");
		
			while($row = mysql_fetch_array($res))
			{
			       $oldprofile[] = $row['PROFILEID'];
			       $username1[] = $row['USERNAME'];
			}
			
			if(is_array($oldprofile))
			{
				$oldprofile=implode(",",$oldprofile);
				$username1=implode(",",$username1);
			}
			if($username1)
			{
				$contact = "Phone";
				$msg = "Hi,</br>User Name <b>$username</b> has registered with the $contact number $phone.</br>We already have profile $username1 with the same number";

				$cc ="productsupport@jeevansathi.com";
				$to ="mahesh@jeevansathi.com";
				$from = "info@jeevansathi.com";
				$subject = "Duplicate $contact Number Tracked";
			        if($whichMachine!='test')
					send_email($to,$msg,$subject,$from,$cc);
			}
		}
		*/	

		if($fname)
		{
			$name_of_user=explode(" ",$fname);
			$smarty->assign("fname_user",$name_of_user[0]);
			$smarty->assign("lname_user",$name_of_user[1]);
		}

		// Redirecting to the Second page

		$smarty->assign('PHONE',$phone);
		$smarty->assign('MOBILE',$mobile);
		$smarty->assign('STATE_CODE',$state_code);
		
		// For updating Lead Table in MIS
		if($email)
		{
			$sql="UPDATE MIS.REG_LEAD SET LEAD_CONVERSION ='Y' WHERE EMAIL='$email'";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$sql="UPDATE MIS.MINI_REG_AJAX_LEAD SET CONVERTED ='Y' WHERE EMAIL='$email'";
			            mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		
		if($leadid)
		{
			$sql="INSERT INTO MIS.LEAD_CONVERSION (LEADID,LEAD_CONVERTED,LEAD_COMPLETED) VALUES ('$leadid','Y','N')";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
if(!$record_id)
	include_once("registration_page2.php");
else 
	include_once("registration_page2a.php");
		die;
	}
}
}
else
{
	@setcookie("JS_SHORT_FORM","1",0,"/");
	if($_COOKIE['OPERATOR']!="" && $source!='101' && $source!='onoffreg')
	{
		$email=date("YmdHis")."@jeevansathi.com";
		$smarty->assign("EMAIL",$email);
	}

	$smarty->assign("CHECKBOXALERT1","A");
	$smarty->assign("CHECKBOXALERT2","S");
	$smarty->assign("TIEUP_SOURCE",$source);

        /* Code added for New Landing Page , commenting for some time as we dont need that right now (Small landing page)/
        $smarty->assign("TIEUPSOURCE",$source);
        /* End */
	
	$smarty->assign("HITSOURCE",$hit_source);
	$smarty->assign("NEWIP",$newip);
	$smarty->assign("SHOWPHONE",'Y');
	if(!$showmobile)
	$smarty->assign("SHOWMOBILE","Y");
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
	
	// Lead Mailer

	if($leadid)
	{
		$sql_leadid="SELECT EMAIL,RELATION,GENDER,DTOFBIRTH,RELIGION,MTONGUE,ISD,MOBILE FROM MIS.REG_LEAD WHERE LEADID='$leadid'";
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
		if($country_Code)
               		$smarty->assign("COUNTRY_CODE",$country_Code);
		if($mobile)
                	$smarty->assign("MOBILE",$mobile);

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

/*		$option_string="";
		$sql = "SELECT SQL_CACHE VALUE, LABEL, GROUPING FROM EDUCATION_LEVEL_NEW ORDER BY GROUPING,SORTBY";
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
				       // $optg = $dropdowns->getElementsByTagName("professionalDegrees")->item(0)->nodeValue;
					$optg="Professional Degrees";
				        $option_string.= "<optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";

				}
				elseif($group == "1")
				{
				        //$optg = $dropdowns->getElementsByTagName("postGraduateDegrees")->item(0)->nodeValue;
					$optg="Post-Graduate Degrees";
				        $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
				}
		 		elseif($group == "2")
				{
				        //$optg = $dropdowns->getElementsByTagName("graduateDegrees")->item(0)->nodeValue;
					$optg="Graduate Degrees";
				        $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
				}
				elseif($group == "3")
				{
				        $option_string.= "</optgroup><optgroup label=\"&nbsp;\">";
					$optg='';
				        //$optg = $dropdowns->getElementsByTagName("others")->item(0)->nodeValue;
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

			$smarty->assign('income',$option_string); */
			
			// caste Dropdown generation

			$option_string="";
			$sql_caste = "SELECT SQL_CACHE VALUE,LABEL,PARENT from newjs.CASTE WHERE REG_DISPLAY!='N' ORDER BY SORTBY";
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
                        
                        $sql = "select SQL_CACHE VALUE,LABEL from SECT where PARENT_RELIGION=2 ORDER BY SORTBY";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				$casteMuslim_label_arr[] = $row['LABEL'];
				$casteMuslim_value = $row['VALUE'];
				$casteMuslim_str= $casteMuslim_value;
				$caste_str="";
				$casteMuslim_value_arr[] = substr($casteMuslim_str,0,strlen($casteMuslim_str)-1);
			}

			$option_string="";		
			for($i=0;$i<count($casteMuslim_value_arr);$i++)
			{
				if($casteMuslim_lead)
				      $casteMuslim_val=$casteMuslim_lead;

				$temp_rel = explode("|X|",$casteMuslim_value_arr[$i]);

				if($casteMuslim_val == $temp_rel[0])
					$option_string.= "<option value=\"$casteMuslim_value_arr[$i]\" selected=\"yes\">$casteMuslim_label_arr[$i]</option>";
				else
					$option_string.= "<option value=\"$casteMuslim_value_arr[$i]\">$casteMuslim_label_arr[$i]</option>";
			}
			$smarty->assign("casteMuslim",$option_string);

                        $sql = "select SQL_CACHE VALUE,LABEL from JAMAAT ORDER BY SORTBY";
 			$res = mysql_query_decide($sql) or logError("error",$sql);
 			while($row = mysql_fetch_array($res))
 			{
 				$jamaat_label_arr[] = $row['LABEL'];
 				$jamaat_value = $row['VALUE'];
 				$jamaat_value_arr[] = $jamaat_value;
 			}
 			$option_string="";		
 			for($i=0;$i<count($jamaat_value_arr);$i++)
 			{
 				if($jamaat_lead)
 				      $jamaat_val=$jamaat_lead;
 
 				$temp_rel = explode("|X|",$jamaat_value_arr[$i]);
 
 				if($jamaat_val == $temp_rel[0])
 					$option_string.= "<option value=\"$jamaat_value_arr[$i]\" selected=\"yes\">$jamaat_label_arr[$i]</option>";
 				else
 					$option_string.= "<option value=\"$jamaat_value_arr[$i]\">$jamaat_label_arr[$i]</option>";
 			}
 			$smarty->assign("jamaat",$option_string);
			$curDate=date('Y', JSstrToTime('-6570 days'));
			for($i=$curDate;$i>=1941;$i--)
				     $yearArray[]=$i;
			$smarty->assign('yearArray',$yearArray);
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

				degreeDropDown();
				occupationDropDown();
				incomeDropDown();
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
			
			if($lead)
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
	
				$sql="INSERT IGNORE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,RELIGION,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,TYPE) VALUES ('$email','$relationship','$gender','$dateofbirth','$religion_val','$now','Y','$mtongue','$source','$country_Code','$mobile','$type')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				if(mysql_affected_rows($db)==0)
				{
					$sql_lead="SELECT LEAD_CONVERSION FROM MIS.REG_LEAD WHERE EMAIL='$email'";
					$res_lead=mysql_query_decide($sql_lead) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead,"ShowErrTemplate");
					$row_lead=mysql_fetch_array($res_lead);
					$lead_flag=$row_lead['LEAD_CONVERSION'];
					
					if($lead_flag=='N')
					{
						$sql_lead_1="REPLACE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,RELIGION,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,TYPE) VALUES ('$email','$relationship','$gender','$dateofbirth','$religion_val','$now','Y','$mtongue','$source','$country_Code','$mobile','$type')";
						mysql_query_decide($sql_lead_1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_lead_1,"ShowErrTemplate");
					}
				}
				$leadid=mysql_insert_id();
				$smarty->assign("EMAIL",$email);
				$smarty->assign("RELATIONSHIP",$relationship);
				$smarty->assign("GENDER",$gender);
				$smarty->assign("DAY",$day);
				$smarty->assign("MONTH",$month);
				$smarty->assign("YEAR",$year);
				$smarty->assign("mtongue",$mtongue);
				$smarty->assign("LEADID",$leadid);
                                $smarty->assign("COUNTRY_CODE",$country_Code);
                                $smarty->assign("MOBILE",$mobile);
			}
			/*
			if($record_id){
			require_once($_SERVER['DOCUMENT_ROOT']."/profile/lead_register_page.php");
			$result=sugar_get_lead_data($record_id,'Leads');
			$bday=$result['birthdate'];
			$bday_array=explode("-",$bday);
			$smarty->assign("GENDER",$result['gender_c']);
			$smarty->assign("mtongue",$result['mother_tongue_c']);
			$smarty->assign("DAY",$bday_array['2']);
			$smarty->assign("MONTH",$bday_array['1']);
			$smarty->assign("YEAR",$bday_array['0']);
			$smarty->assign("MOBILE",$result['phone_mobile']);
			}
 */
			
			// Changes End
		

			$smarty->display("sugarcrm_registration/sugarcrm_registration_pg1a.htm");
// flush the buffer
if($zipIt && !$dont_zip_now)
ob_end_flush();

?>
