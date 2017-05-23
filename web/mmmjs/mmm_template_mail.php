<?php

/**
*	Filename	:	mmm_template_mail.php
*	Description	:	This file takes the subject,from_email,url of the template.It then fetches the template from the path given and saves as data  into MAIL_DATA TABLE corresponding to mailer_id
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used 	:	a)mmm_template_mail.htm
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
	
	
function insert_mail_data($mailer_id,$template_name,$from,$subject,$url,$from_name,$browserUrl)
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
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,ACTIVE,BROWSERURL) VALUES($mailer_id,'$template_name','$from','$from_name','$subject','$data','Y','$browserUrl')";
	mysql_query($sql) or die("Could not insert into mail data".mysql_error()."<br>$sql<br>");
	$message="Mail data has been added ,Now you can send test mail";
	$sql_update_state="UPDATE MAIN_MAILER SET STATE='mdi' WHERE MAILER_ID=$mailer_id ";
	$result_update_state=mysql_query($sql_update_state) or die("could not update state of mailer: ".mysql_error());
		
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
}
	
	//NOTE : THIS FUNCTION IS NOT CURRENTLY IN USE 

		/**
		*	Function	: check_data
		*	Input		:	mailer_id ,url ,from email , subject
		*	Output		:
		*	Description	:	It checks whether there exist any data in MAIL_DATA TABLE corresponding to this mailer_id if so then displays the message
		**/
function check_data($mailer_id,$url,$f_email,$subject)
{
	global $smarty;
	$sql="SELECT ID FROM MAIL_DATA WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or die ("Could not get count from maill data");
	$n=mysql_num_rows($result);
	echo "no of results ->$n<br>";
	if($n>0)
	{
		$message="Data For mailer $mailer_id already exist in database if you want to overwrite then click overwrite ";
		
		if(!$mailer_id)
			$mailer_id=0;
		$arr=get_valid_mailers();
		$smarty->assign("arr",$arr);
		$smarty->assign("message",$message);
		$smarty->assign("overwrite",1);
		$smarty->assign("url",$url);
		$smarty->assign("mailer_id",$mailer_id);
		$smarty->assign("f_email",$f_email);
		$smarty->assign("subject",$subject);
		$smarty->assign("cid",$cid);
		$smarty->display("mmm_template_mail.htm");
		die;
	}
}

		/**
		*	Function	:	get_valid_mailers
		*	Input		:	
		*	Output		:	
		*	Description	:	This function finds all those mailers which are in 'tc'(table created) state and having mailer type ='tpm' (Template mail"
		**/
function get_valid_mailers()
{
	global $smarty;
	$sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='dc' AND MAIL_TYPE='tpm' ";
	$result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$arr[]=array("mailer_id"=>$row[MAILER_ID],
			 "mailer_name"=>$row[MAILER_NAME]);
	}
	if(sizeof($arr)==0)
	{
		$message="There is no mailer of Template Type ";
		$smarty->assign("message",$message);
		$smarty->display("mmm_message.htm");
		die;
	}
	return $arr;
} 
	
if($submit)
{
	//check_data($mailer_id,$url,$f_email,$subject);
	insert_mail_data($mailer_id,$template_name,$f_email,$subject,$url,$f_name,$browserUrl);
	
}
elseif($overwrite)
{
	echo "mailer_id->$mailer_id<br>";
	echo "url->$url<br>";
	insert_mail_data($mailer_id,$f_email,$subject,$url,$mail_type,$browserUrl);
}
else
{
	$arr=get_valid_mailers();
	$smarty->assign("arr",$arr);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_template_mail.htm");	
}	
	
?>
