<?php

/**
*	Filename	:	mmm_template_mail_add.php
*	Description	:	This file takes the subject,from_email,url of the template.It then fetches the template from the path given and saves as data  into MAIL_DATA TABLE corresponding to mailer_id
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used 	:	a)mmm_template_mail_add.htm
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


		/**
		*	Function	:	insert_mail_data
		*	Input		:	mailer_id,from email, subject ,url of template
		*	Output		:
		*	Description	:reads the template from url and finally stores in MAIL_DATA table as message along with that from_email and subject is also stored into the MAIL_DATA table corresponding to mailer_id
		**/
	
	
function insert_mail_data($mailer_id,$template_name,$from,$subject,$url,$from_name)
{
	global $smarty;
	$data="";
//	passthru("chmod 777 $url");
	$fd1 = fopen("$url","r");
	while (!feof($fd1)) 
	{
		$temp= fgets($fd1, 4096);
		$data.=$temp;
	}
	fclose($fd1);
		
	$from=addslashes(stripslashes($from));
	$from_name=addslashes(stripslashes($from_name));
	$subject=addslashes(stripslashes($subject));
	//echo"The tpl saved is :<br><br> $data";
	//$data=nl2br($data);	
	$data=addslashes(stripslashes($data));
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,ACTIVE) VALUES($mailer_id,'$template_name','$from','$from_name','$subject','$data','N')";
	mysql_query($sql) or die("Could not insert into mail data".mysql_error()."<br>$sql<br>");
	$message="Mail data with new template has been added corresponding to same mailer with mailer id :".$mailer_id." But it is not active,So first activate this template and then send test mail";
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
}
	
	
if($submit)
{
	insert_mail_data($mailer_id,$template_name,$f_email,$subject,$url,$f_name);
}
else
{
	$smarty->assign("mailer_id",$mailer_id);
	$smarty->assign("arr",$arr);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_template_mail_add.htm");	
}	
	
?>
