<?php
/**
*	Filename	:	mmm_create_mailer.php
*	Included	:	connect.inc
*	Called From	:	From Create Mailer Link in Menu
*	Description	:	This file takes Client name, type of mail,type of response as input and creates a new mailer and returns the mailer_id of the mailer
*
**/

/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used 	:	a)mmm_create_mailer.htm
			   	b)mmm_create_mailer_message.htm
**/

include "connect.inc";

/**** THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE**********************/ 

$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
	$auth=1;
	$un = getuser($cid,$ip);
	$tm=getIST();
	//setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
	$smarty->display("mmm_relogin.htm");
	die;
}	

$smarty->assign("cid",$cid);

/****************************AUTHENTICATION ROUTINE ENDS HERE*********************************/	


		/**
		*	Function	:	create_mailer
		*	Input		:	client_name, mail_type,response_type
		*	Output		:
		*	Description	:	creates a mailer and passes mailer_id to the message template
		**/

function create_mailer($mailer_name,$client_name,$mail_type,$response_type,$company,$pos,$uniqueid,$mailer_for)
{
	global $smarty;
	$ctime=getIST();


        //Query for naukri db
        if($mailer_for=="N")
		$sql="INSERT INTO MAIN_MAILER(MAILER_NAME,CLIENT_NAME,CTIME,MAIL_TYPE,RESPONSE_TYPE,COMPANY_NAME,PERIOD_OF_STAY,UNIQUEID,MAILER_FOR)values('$mailer_name','$client_name','$ctime','$mail_type','$response_type','$company','$pos','$uniqueid','$mailer_for')";

        else
	{
        //Query for jeevansathi db or 99acres db
		if($mailer_for=="J") 
		{
			if($_POST["jswalkin"])
			{
				$sql="INSERT INTO MAIN_MAILER(MAILER_NAME,CLIENT_NAME,CTIME,MAIL_TYPE,RESPONSE_TYPE,COMPANY_NAME,PERIOD_OF_STAY,UNIQUEID,MAILER_FOR,NAUKRI_STATE,JSWALKIN)values('$mailer_name','$client_name','$ctime','$mail_type','$response_type','$company','$pos','$uniqueid','$mailer_for','com','Y')";
			}
			else
			$sql="INSERT INTO MAIN_MAILER(MAILER_NAME,CLIENT_NAME,CTIME,MAIL_TYPE,RESPONSE_TYPE,COMPANY_NAME,PERIOD_OF_STAY,UNIQUEID,MAILER_FOR,NAUKRI_STATE)values('$mailer_name','$client_name','$ctime','$mail_type','$response_type','$company','$pos','$uniqueid','$mailer_for','com')";
		}
		else
                $sql="INSERT INTO MAIN_MAILER(MAILER_NAME,CLIENT_NAME,CTIME,MAIL_TYPE,RESPONSE_TYPE,COMPANY_NAME,PERIOD_OF_STAY,UNIQUEID,MAILER_FOR,NAUKRI_STATE)values('$mailer_name','$client_name','$ctime','$mail_type','$response_type','$company','$pos','$uniqueid','$mailer_for','com')";
	}
        mysql_query($sql) or die("could not insert into mailer database");
	$mailer_id=mysql_insert_id();
//	echo $uniqueid;echo ("###");
	$a=md5($uniqueid);
//	echo $a; 
//	$url="http://ser2.jeevansathi.com/mmmjs/client_mis.php?uniqueid=".md5($uniqueid)."&mailer_id=".$mailer_id;
	$smarty->assign("mailer_name",$mailer_name);	
	$smarty->assign("client_name",$client_name);
	$smarty->assign("ctime",$ctime);
	$smarty->assign("mailer_id",$mailer_id);
//	$smarty->assign("url",$url);
	$smarty->display("mmm_create_mailer_message.htm");
		
}	

if($mailer)
{
	$uniqueid = uniqid(rand(), true);
	create_mailer($mailer_name,$client_name,$mail_type,$response_type,$company,$pos,$uniqueid,$mailer_for);
}
else 
{
	$smarty->assign("cid",$cid);
	$smarty->assign("city",$city);
	$smarty->display("mmm_create_mailer.htm");
}
	
?>
