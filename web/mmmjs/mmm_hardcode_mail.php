<?php

/**
*	Filename	:	mmm_hardcode_mail.php
*	Description	:	This file takes the subject,from_email,mail data from the user and saves it into MAIL_DATA TABLE corresponding to mailer_di
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used 	:	a)mmm_hardcode_mail.htm
				b)mmm_message.htm
**/


include"connect.inc";

//// THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE/////////////////////// 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid,$ip))
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
/////////////AUTHENTICATION ROUTINE ENDS HERE///////////////	

/*
echo "mailer id->$mailer_id <br>";
echo "mail_type->$mail_type <br>";
echo "url ->$url<br>";
echo "subject->$subject<br>";
echo"from->$f_email<br>";
*/

		/**
		*	Function	:	insert_mail_data
		*	Input		:	mailer_id,from email, subject ,mail data
		*	Output		:
		*	Description	:Inserts the mail data into the MAIL_DATA table corresponding to mailer_id
		**/
	
	
function insert_mail_data($mailer_id,$template_name,$from,$subject,$data,$from_name,$browserUrl)
{
	global $smarty;
	//header will consist of the image tag if the respone is needed by client
	//footer will consist of link of unsubscribe php with email id passed to it
	//$header= get_header($mailer_id);
	//$footer=get_footer();
	$from=addslashes(stripslashes($from));
	$from_name=addslashes(stripslashes($from_name));
	$subject=addslashes(stripslashes($subject));
	//$data=addslashes($data);
	//echo"The tpl saved is :<br><br> $data";
	
	$data=nl2br($data);	
	$data=addslashes(stripslashes($data));
	//echo $data; die;
		
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,ACTIVE,BROWSERURL) VALUES($mailer_id,'$template_name','$from','$from_name','$subject','$data','Y','$browserUrl')";
	mysql_query($sql) or die("Could not insert into mail data".mysql_error()."<br>$sql<br>Error :".mysql_error());
	$message="Mail data has been added please ,Now you can send test mail";
	
	$sql_update_state="UPDATE MAIN_MAILER SET STATE='mdi' WHERE MAILER_ID=$mailer_id ";
	$result_update_state=mysql_query($sql_update_state) or die("could not update state of mailer: ".mysql_error());
	
	
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
}	

	
		/**
		*	Function	:	get_valid_mailers
		*	Input		:	
		*	Output		:	
		*	Description	:	This function finds all those mailers which are in 'tc'(table created) state and having mailer type ='hcm' (hardcode mail"
		**/
	
function get_valid_mailers()
{
	global $smarty;
	$sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='dc' AND MAIL_TYPE='hcm' ";
	$result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$arr[]=array("mailer_id"=>$row[MAILER_ID],
			 "mailer_name"=>$row[MAILER_NAME]);
	}
	if(sizeof($arr)==0)
	{
		$message="There is no Hard Code mailer defined";
		$smarty->assign("message",$message);
		$smarty->display("mmm_message.htm");
		die;
	}
	return $arr;
} 
	
if($submit)
{
	insert_mail_data($mailer_id,$template_name,$f_email,$subject,$data,$f_name,$browserUrl);
}
else
{
	$name="\$name";
	$arr=get_valid_mailers();
	$smarty->assign("arr",$arr);
	$smarty->assign("cid",$cid);
	$smarty->assign("name","~$name`");
	$smarty->display("mmm_hardcode_mail.htm");	
}	
	
?>
