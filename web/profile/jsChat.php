<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

//include_once("jsChat.inc");
include_once("sphinxclusterGlobalarrays.inc");
include_once("search.inc");
include_once("sphinx_search_function.php");
//echo $_SERVER['DOCUMENT_ROOT'];
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
$registration=$_GET["registration"];

require_once("connect.inc");
$db=connect_db();

$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj();
			$authenticationLoginObj->setTrackLogin(0);
			$data=$authenticationLoginObj->authenticate(null,$gcm);

if(!$data['PROFILEID'])
	die;
if($data)
$profileid = $data["PROFILEID"];

//fetching record from memcache and updating CONTACTS_STATUS Table.
/*$memcache_cont_stat=unserialize(memcache_call($profileid));
if(!$memcache_cont_stat || $memcache_cont_stat['FIRST_TIME']==1)
{
	put_back_to_contact_status();
}	
	*/
$userName=$data["USERNAME"];

$user_checksum=createChecksumForSearch($profileid);

//$pass=$password[0];
$pass=$profileid;
//$subscript=$subscription[0];
if($data['SUBSCRIPTION']){
$subscript=$data['SUBSCRIPTION'];
}

$user_email=$data['EMAIL'];
$user_gender=$data['GENDER'];
//$user_messenger_id=$messenger_id[0];
//$user_messenger_channel=$messenger_channel[0];
if($user_gender == "F"){
	$require_gender="M";
	$search_table="SEARCH_MALE";
}else{
	$require_gender="F";
	$search_table="SEARCH_FEMALE";
}



$random_number = rand(1,10);


//$userName=str_replace(" ","_",$userName);
$subsriptCheck="F";
$smarty->assign("PASSWORD","$pass");
$smarty->assign("PROFILEID","$profileid");
$smarty->assign("userName","$userName");
$smarty->assign("registration","$registration");
$smarty->assign("USER_CHECKSUM","$user_checksum");
//$smarty->assign("resource",$random_number);
//echo strlen($subscript);

//please make it review by some body

if(strlen($subscript) !== 0){
	$pos = strpos($subscript, $subsriptCheck);
	if($pos === false){
		$smarty->assign("SUBSCRIPT",false);
	}else{
		$smarty->assign("SUBSCRIPT",true);
	}
}else{
	//echo "setting subscript as false";
	$smarty->assign("SUBSCRIPT",false);
}

$chatBar=1;
$gender=$require_gender;
$onlineArr=1;
$requiresOnlyCount=1;

//print_r("registration is ".$registration);
 $db=connect_db();
 
 
	//if($registration == true){
	if(strpos($user_email,"gmail")){
		$bot_sql="SELECT COUNT(*) as CNT from bot_jeevansathi.user_info where profileID = '$profileid'";
		$bot_result=mysql_query_decide($bot_sql) or logError("error",$bot_sql);
		$bot_row = mysql_fetch_array($bot_result);
		$bot_cnt = $bot_row["CNT"];
		//echo "bot_cnt is >>>>".$bot_cnt;
		if($bot_cnt == 0)
		{
			//echo "comng for inserting data into bot_jeevansathi";
		$sql_bot_entry="insert into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('$user_email','0','1','$profileid','$userName')";
		mysql_query_decide($sql_bot_entry);
		
//		$sql_invite_entry="insert into bot_jeevansathi.invites(`gmailid`) values('$user_email')";
//		mysql_query_decide($sql_invite_entry);
		$sql_invite_entry="insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('$userName','$user_email')";
		mysql_query_decide($sql_invite_entry);
		$sql_invite_entry="insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('$profileid','$user_email')";
		mysql_query_decide($sql_invite_entry);
		
		//commented by hemant because this email should not go while sending bot request as per changed requirement
		//send_chat_request_email($profileid,$user_email,$userName);
			
		}
	}
//}


//check wheather user is registered in openfire or not, if not then register a new user

// plz check for some user lik thanu and gv its giving error
$db=connect_openfire();
$sql="SELECT COUNT(*) as CNT from openfire.ofUser where username = '$profileid'";
$result=mysql_query_decide($sql) or logError("error",$sql);
$row = mysql_fetch_array($result);
$cnt = $row["CNT"];
//echo "cnt is >>>>".$cnt;
if($cnt == 0)
{
	$registration=true;
	$smarty->assign("registration","$registration");
}

 
	 $db=connect_db();


$smarty->display("jsChat.htm");

// flush the buffer
if($zipIt)
        ob_end_flush();

exit;
?>
