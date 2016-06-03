<?php

/**
*	Filename	:	mmm_url_mail_add.php
*	Description	:	This file takes the subject,from_email,url to be sended.It then fetches the page from the given url and saves as data  into MAIL_DATA TABLE corresponding to mailer_id
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used :	   a)mmm_url_mail_add.htm
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


echo "mailer id->$mailer_id <br>";
echo "mail_type->$mail_type <br>";
echo "url ->$url<br>";
echo "subject->$subject<br>";
echo"from->$f_email<br>";


		/**
		*	Function	:	insert_mail_data
		*	Input		:	mailer_id,from email, subject ,url to be sended
		*	Output		:
		*	Description	:	reads the page from url and finally stores in MAIL_DATA table as message along with that from_email and subject is also stored into the MAIL_DATA table corresponding to mailer_id
		**/	
	
function insert_mail_data($mailer_id,$template_name,$from,$subject,$url,$from_name)
{
	echo $mailer_id;
	echo $from;
	echo $subject;
	echo $url;
	global $smarty;
	$data="";
	$fd1 = fopen("$url","r");
	if($fd1)
	{
		while (!feof($fd1)) 
		{
			$temp= fgets($fd1, 4096);
			$data.=$temp;
		}
		fclose($fd1);
	}
	else
	{
		die("url could not be fetched. Try again");
	}
	$from=addslashes(stripslashes($from));
	$from_name=addslashes(stripslashes($from_name));
	$subject=addslashes(stripslashes($subject));
	//$data=addslashes($data);
	//echo"The tpl saved is :<br><br> $data";
	$data=addslashes(stripslashes($data));
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,ACTIVE) VALUES($mailer_id,'$template_name','$from','$from_name','$subject','$data','N')";
	mysql_query($sql) or die("Could not insert into mail data".mysql_error()."<br>$sql<br>");
	
	$message="New mail data corresponding to existing url mailer with mailer id : ".$mailer_id."has been added but is not active presently,Now you can can activate this template and send test mail";
		
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
}

//NOTE : THIS FUNCTION IS NOT CURRENTLY IN USE 

		/**
		*	Function	:	check_data
		*	Input		:	mailer_id ,url ,from email , subject
		*	Output		:
		*	Description	:	It checks whether there exist any data in MAIL_DATA TABLE corresponding to this mailer_id if so then displays the message
		**/

	
if($submit)
{
	//check_data($mailer_id,$url,$f_email,$subject);
	insert_mail_data($mailer_id,$template_name,$f_email,$subject,$url,$f_name);
}
else
{
	$smarty->assign("mailer_id",$mailer_id);
	$smarty->assign("arr",$arr);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_url_mail_add.htm");	
}	
?>
