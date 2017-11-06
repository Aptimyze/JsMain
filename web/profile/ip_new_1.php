<?php
/************************************************************************************************************************
* 	FILE NAME	:	ip_new_1.php
* 	DESCRIPTION 	: 	Get details for a new profile
* 	MODIFY DATE	: 	16 Feb, 2005
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	Ajax Based form 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("hits.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include("search.inc");

$db=connect_db();

$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

// assert that some things are not be shown in common templates as is the case with homepage
$smarty->assign("CAMEFROMHOMEPAGE","1");

$ip=FetchClientIP();//Gets ipaddress of user
if($source=="")
{
	if($newsource!="")
	{
		$source=$newsource;
		/*$sql_source="SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='$source'";
		$res=mysql_query_decide($sql_source) or die();
		$res_source=mysql_fetch_array($res);
		if($res_source['GROUPNAME']=='google')
		{
			$google_display=1;
			$smarty->assign("GOOGLE_DISPLAY",$google_display);
			$str=$_SERVER['HTTP_REFERER'];
			$str=strstr($str,"q=");
			$pos=strpos($str,"&");
			$key=substr($str,2,$pos-2);
			$key=explode("+",$key);
			$key=implode($key," ");
			$smarty->assign("key",$key);
		}*/
	}	
	elseif(isset($_COOKIE['JS_SOURCE']))
	{
		$source=$_COOKIE['JS_SOURCE'];
	}
}
// if source has come in that means that the person has clicked on a banner on jeevansathi
// we make source blank in index.php before including this file to implement this logic
else 
{
	savehit($source,$_SERVER['PHP_SELF']);
	
	if(isset($_COOKIE['JS_SOURCE']))
		$source=$_COOKIE['JS_SOURCE'];
}
//checking for gender cookie

if(isset($_COOKIE["JS_GENDER"]))
{
	$cookie_gender=$_COOKIE["JS_GENDER"];
}

//$smarty->assign("CREATIVE",tieup_creative($source,$cookie_gender,$lang));
tieup_creative($source,'long_page');

if($camefrom=="ajax")
{
	/****  check for banner sources*****/

	$sql="SELECT GROUPNAME,FORCE_EMAIL FROM MIS.SOURCE WHERE SOURCEID = '$tieup_source'";
	$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($result);

	$force_mail=$row["FORCE_EMAIL"];

	if($force_mail=='Y')
	{
		$email_validation='Y';
	}
	/*
	if($row["GROUPNAME"]=="google")
		$ajax_google=1;
	else
		$ajax_google=0;*/
	$is_error=0;
	$Email=trim($Email);
	if($Caste)
	{	
		$sql="SELECT PARENT,SMALL_LABEL from CASTE WHERE VALUE='$Caste'";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_row($result);
		$Caste_label=$myrow[1];
		$Religion=$myrow[0];
	}
	//age calculation from DOB
	$array = array($Year, $Month, $Day);
	if($array)
	{
		$date_of_birth= implode("-", $array);
		$age=getAge($date_of_birth);
	}
	else
		$age=0;
	
	//$Religion_temp = explode('|X|',$Religion);
	//$Religion = $Religion_temp[0];
	
	// added by Gaurav Arora on 25 July for new template of input profile.
	
	/*if($Showmobile)
		$showmobile="N";
	else
		$showmobile="Y";
	if($Showphone)
		$showphone='N';
	else
		$showphone='Y';	*/
	
	$showmobile=$Showmobile;
	$showphone=$Showphone;
	// end of code added for new template of input profile.

	if(!$GET_SMS)
		$GET_SMS="N";	
	
	if($tieup_source=="")
		$tieup_source="IP";
		
	$today=date("Y-m-d H:i:s");


	if($Country_Residence==51)
		$City_Res=$City_India;
	elseif($Country_Residence==128)	
		$City_Res=$City_USA;
	else
		$City_Res="";

	if(!$checkboxalert1)
		$checkboxalert1='U';
	if(!$checkboxalert2)
		$checkboxalert2='U';
	if(!$checkboxalert3)
		$checkboxalert3='U';

	while(1)
	{
		$Username=username_gen();
		$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$Username'";
		$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$row_username=mysql_fetch_array($res_username);
												    
		$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$Username'";
		$res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$row_username2=mysql_fetch_array($res_username2);
												    
		if($row_username['cnt']==0 && $row_username2['cnt']==0)
			break;
	}

	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{	
		$off_line='N';
		$sql = "INSERT INTO JPROFILE_AFFILIATE(PASSWORD,EMAIL,USERNAME,GENDER,MSTATUS,RELIGION,CASTE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,OFFLINE,CITY_RES,PHONE_MOB,SHOWPHONE_MOB,INCOME,PERSONAL_MATCHES,SERVICE_MESSAGES,PROMO_MAILS,PROMO,GET_SMS,STD,ISD,MTONGUE) VALUES ('$Password1','$Email','$Username','$Gender','$Marital_Status','$Religion','$Caste','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'Y','$showphone','$age','$ip','$tieup_source','N','N','$off_line','$City_Res','$Mobile','$showmobile','$Income','$checkboxalert1','$checkboxalert2','$checkboxalert3','$checkboxalert3','$GET_SMS','$State_Code','$Country_Code','$Mtongue')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	}
	else
	{
		$sql="select LABEL from HEIGHT where VALUE='$Height'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow_height=mysql_fetch_array($res);
		$height_label=$myrow_height["LABEL"];
		$height_label=substr($height_label,0,10);

		if($City_India!='')
		      $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
		elseif($City_USA!='')
		      $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		$myrow_city=mysql_fetch_array($res);
		$city_label=$myrow_city["LABEL"];

		if($Gender=='M') 
			$gender="Male";
		elseif($Gender=='F')
			$gender="Female";

		$keyword=addslashes(stripslashes($gender.",".$age.",".$Caste_label.",".$height_label.",".$city_label));
		$sql = "INSERT INTO JPROFILE(USERNAME,PASSWORD,EMAIL,GENDER,MSTATUS,RELIGION,CASTE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,CITY_RES,PHONE_MOB,SHOWPHONE_MOB,INCOME,KEYWORDS,PERSONAL_MATCHES,SERVICE_MESSAGES,PROMO_MAILS,PROMO,GET_SMS,STD,ISD,MTONGUE) VALUES ('$Username','$Password1','$Email','$Gender','$Marital_Status','$Religion','$Caste','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'Y','$showphone','$age','$ip','$tieup_source','N','N','$City_Res','$Mobile','$showmobile','$Income','$keyword','$checkboxalert1','$checkboxalert2','$checkboxalert3','$checkboxalert3','$GET_SMS','$State_Code','$Country_Code','$Mtongue')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	}
	$id=mysql_insert_id_js();

	if(isset($_COOKIE['SEARCH_REDIFF']))
	{
		$sql_rediff = "INSERT INTO MIS.REDIFF_SRCH_REG (PROFILEID,ENTRY_DT) VALUES ('$id',NOW())";
		mysql_query_decide($sql_rediff) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_rediff,"ShowErrTemplate");
	}
	
	if($lang)
	{
		$sql="INSERT INTO MIS.LANG_REGISTER VALUES ('','$id','$lang')";
		mysql_query_decide($sql);
	}
	$sql_incomp="INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES('$id',now())";
	mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");

	$sql_name_insert="INSERT INTO NAMES VALUES ('$Username')";
	mysql_query_decide($sql_name_insert) or logError("NAMES TABLE ENTRY NOT DONE.",$sql_name_insert,"ShowErrTemplate");
	$tm=time();

	if($Gender=='M')
	{
		$hheight=$Height;
		if($Height>10)
			$lheight=$Height-10;
		else
			$lheight=1;
	}
	else
	{
		$lheight=$Height;
		if($Height<=20)
			$hheight=$Height+10;
		else
			$hheight=30;

	}

	if($Gender=='M')
	{
		if($age<25)	
			$lage=18;
		else
			$lage=$age-7;
		$hage=$age;
	}
	else
	{
		$hage=$age+7;
		if($age<21)
			$lage=21;
		else
			$lage=$age;
	}
	if(($lage==21 && $hage==7) || ($lheight==0 && $hheight==10) || $lage==0)
	{
		$err_msg="PROFILEID:$id<br>GENDER:$Gender<br>LAGE:$lage<br>HAGE:$hage<br>LHEIGHT:$lheight<br>HHEIGHT:$hheight<br>SOURCE=$tieup_source";
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
		send_email('gaurav.arora@jeevansathi.com',$err_msg,"Error in JPARTNER_PAGE3 in  ip_new_1.php on line 260","register@jeevansathi.com");
	}

	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{
		if($Gender=='M')
			$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','F','$lage','$hage','$lheight','$hheight',now())";
		elseif($Gender=='F')
			$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','M','$lage','$hage','$lheight','$hheight',now())";
		else
			$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','','$lage','$hage','$lheight','$hheight',now())";
											 
		mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");

		$checksum_aff=md5($id) . "i" . $id;

		$smarty->assign("ID_AFF",$id);
	}
	else
	{
		if($Gender=='M')
			$sql_jpartner="INSERT into JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','F','$lage','$hage','$lheight','$hheight',now())";
		else
			$sql_jpartner="INSERT into JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','M','$lage','$hage','$lheight','$hheight',now())";

		mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");

		// end of code added for fields to be inserted in JPARTNER table
		$sql="insert into CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,TIME1,GENDER,ACTIVATED) values ('','$Username','$Password1','$id','','$tm','$Gender','N')";
		$result= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	
		$checkid=mysql_insert_id_js();
		$checksum=md5($checkid) . "i" . $checkid;

		// set a cookie with the name JSLOGIN that stores the checksum and expires when the session ends and is available on the entire domain
		setcookie("JSLOGIN",$checksum,0,"/");
	}

	setcookie("JS_SOURCE","",0,"/");
//main part of the ajax based form...it returns the username,id & checksum generated dynamically
echo "<?xml version='1.0' encoding='UTF-8'?><SPAN class=\"red\">$Username|$id|$checksum</SPAN>";
	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{
		$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE_AFFILIATE where ID='$id'";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$myrow1=mysql_fetch_array($result);

		if($myrow1['USERNAME'])
			$msg="Dear $myrow1[USERNAME]<br><br>Thank you for registering on JeevanSathi.com and becoming an esteemed member of this family.<br><br>Now you are just one step away from your dream life partner. Just click on the following link to activate your matrimonial profile on our website.<br><br>This will make your profile visible to the members according to the privacy preferences you have chosen. Hence we request you to activate your matrimonial profile.<br><br><a href=\"$SITE_URL/profile/validate_function.php?checksum=$checksum_aff\" target=\"_blank\">Activate</a><br><br>(IMPORTANT: Your profile will not be displayed on our website if you ignore the activation process.)<br><br>You can use the following Username and Password to login<br>Username: $myrow1[USERNAME]<br>Password: $myrow1[PASSWORD]<br><br>Hope to see you on JeevanSathi.com soon!<br><br>With warm Regards<br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\" target=\"_blank\">www.jeevansathi.com</a>";
		else
			$msg="Dear Member<br><br>Thank you for registering on JeevanSathi.com and becoming an esteemed member of this family.<br><br>Now you are just one step away from your dream life partner. Just click on the following link to activate your matrimonial profile on our website.<br><br>This will make your profile visible to the members according to the privacy preferences you have chosen. Hence we request you to activate your matrimonial profile.<br><br><a href=\"$SITE_URL/profile/validate_function.php?checksum=$checksum_aff\" target=\"_blank\">Activate</a><br><br>(IMPORTANT: Your profile will not be displayed on our website if you ignore the activation process.)<br><br>Hope to see you on JeevanSathi.com soon!<br><br>With warm Regards<br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\" target=\"_blank\">www.jeevansathi.com</a>";

		send_email($myrow1["EMAIL"],$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
	}
	else
	{
		/*
		$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE where PROFILEID=$id";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$myrow1=mysql_fetch_array($result);
		send_email($myrow1["EMAIL"],$msg,"Thank you for registering with jeevansathi.com ","register@jeevansathi.com");*/
		$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE where PROFILEID=$id";
                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$myrow1=mysql_fetch_array($result);
		$smarty->assign("EMAIL",$myrow1["EMAIL"]);
		$smarty->assign("USERNAME",$myrow1["USERNAME"]);
		$smarty->assign("PASSWORD",$myrow1["PASSWORD"]);
		$msg = $smarty->fetch("automated_response.htm");
                send_email($myrow1["EMAIL"],$msg,"Thank you for registering with jeevansathi.com ","register@jeevansathi.com");
	}
}
else
{

	//temporary
	$sql="SELECT GROUPNAME FROM MIS.SOURCE WHERE SOURCEID = '$source'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        $row=mysql_fetch_array($result);
                                                                                                 
        if($row["GROUPNAME"]=="google")
                $ajax_google=1;
        else
                $ajax_google=0;
	$smarty->assign("ajax_google",$ajax_google);
	//till here...will be removed subsequently
	if($submit_google)
	{
		$sql = "INSERT INTO PROFILE_BRIEF(NAME,AGE,GENDER,MSTATUS,CASTE,EMAIL) Values ('".addslashes(stripslashes($username))."','$age','$gender_profile','$Marital_Status','$Caste','$email')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		
		$smarty->assign("username",$username);
		$smarty->assign("gender",$gender_profile);
		$smarty->assign("marital",$Marital_Status);
		$smarty->assign("caste",create_dd("$Caste","Caste"));
		$smarty->assign("email",$email);
	}	

	$smarty->assign("religion",populate_religion(1));
	$smarty->assign("caste",create_dd("$Caste","Caste"));
	$smarty->assign("mtongue",create_dd("","Mtongue"));
	$smarty->assign("country_residence",create_dd("","Country_Residence"));
	$smarty->assign("top_country",create_dd("51","top_country"));
	$city_india=create_dd("","City_India");	
//	$smarty->assign("family_back",create_dd("","Family_Back"));
	$smarty->assign("height",create_dd(8,"Height"));

	//added by gaurav arora on 25 July for new input profile pages
	$smarty->assign("maritalstatus",$Marital_Status);

	$income=create_dd("","Income");
	$smarty->assign("income",$income);
	$smarty->assign("city_india",$city_india);
	$city_usa=create_dd("","City_USA");
	$smarty->assign("city_usa",$city_usa);
	$smarty->assign("CHECKBOXALERT1","A");
	$smarty->assign("CHECKBOXALERT2","S");
	//$smarty->assign("CHECKBOXALERT3","S");
	$smarty->assign("showphone","Y");
	$smarty->assign("showmobile","Y");
	$smarty->assign("TIEUPSOURCE",$source);
	$smarty->assign("HITSOURCE",$hit_source);	

	/*      code added by Nikhil Tandon to add a hidden field countrycodes seprated by $*/
	$ccc=create_code("COUNTRY");
	$csc=create_code("CITY_INDIA");
	$smarty->assign("country_isd_code",$ccc);
	$smarty->assign("india_std_code",$csc);
	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile_tieupA.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->display("inputprofile_tieupA.htm");
	}
}
// flush the buffer
if($zipIt)
ob_end_flush();
?>
