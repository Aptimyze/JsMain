<?php

/**
*	Filename	:	mmm_url_mail.php
*	Description	:	This file takes the subject,from_email,url to be sended.It then fetches the page from the given url and saves as data  into MAIL_DATA TABLE corresponding to mailer_id
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	Tpls used :	   a)mmm_url_mail.htm
			   		b)mmm_message.htm
**/
ini_set('max_execution_time',0);
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


/*echo "mailer id->$mailer_id <br>";
echo "mail_type->$mail_type <br>";
echo "url ->$url<br>";
echo "subject->$subject<br>";
echo"from->$f_email<br>";
*/

		/**
		*	Function	:	insert_mail_data
		*	Input		:	mailer_id,from email, subject ,url to be sended
		*	Output		:
		*	Description	:	reads the page from url and finally stores in MAIL_DATA table as message along with that from_email and subject is also stored into the MAIL_DATA table corresponding to mailer_id
		**/	
	
function insert_mail_data($mailer_id,$template_name,$from,$subject,$url,$from_name,$browserUrl)
{
	echo $mailer_id;
	echo $from;
	echo $from_name;
	echo $subject;
	echo $url;
	global $smarty;
	$data="";
	
	$fd1 = fopen("$url","r");
	echo "HERE".$fd1;
	if($fd1)
	{
	echo "HERE".$fd1;
		while (!feof($fd1)) 
		{
			$temp= fgets($fd1, 4096);
			$data.=$temp;
		}
		echo "HERE data is calculated";
		fclose($fd1);
	}
	else
	{
		$data="";
		die("url could not be fetched. try again");
	}
	
	$from=addslashes(stripslashes($from));
	$from_name=addslashes(stripslashes($from_name));
	$subject=addslashes(stripslashes($subject));
	//$data=addslashes($data);
	//echo"The tpl saved is :<br><br> $data";die;
	$data=addslashes(stripslashes($data));
	$sql="SELECT JSWALKIN FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
	$res=mysql_query($sql) or die("checking for jswalkin mailer".mysql_error()."<br>$sql<br>");
	$row=mysql_fetch_assoc($res);
	//echo $browserUrl.'*********';die;
	if($row["JSWALKIN"]=='Y')
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,SUBJECT,DATA,ACTIVE,BROWSERURL) VALUES($mailer_id,'$template_name','$subject','$data','Y','$browserUrl')";
	else
	$sql="INSERT INTO MAIL_DATA(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,ACTIVE,BROWSERURL) VALUES($mailer_id,'$template_name','$from','$from_name','$subject','$data','Y','$browserUrl')";
	
	mysql_query($sql) or die("Could not insert into mail data".mysql_error()."<br>$sql<br>");
	
	$message="Mail data has been added to $mailer_id,Now you can send test mail";
		
	$sql_update_state="UPDATE MAIN_MAILER SET STATE='mdi' WHERE MAILER_ID=$mailer_id ";
	$result_update_state=mysql_query($sql_update_state) or die("could not update state of mailer: ".mysql_error());
		
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
		$smarty->display("mmm_url_mail.htm");
		die;
	}
}
/**
		*	Function	:	get_valid_mailers
		*	Input		:	
		*	Output		:	
		*	Description	:	This function finds all those mailers which are in 'tc'(table created) state and having mailer type ='urm' (Template mail"
		**/
function get_valid_mailers()
{
	global $smarty;
	$sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='dc' AND MAIL_TYPE='urm' ";
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

//This function returns the array of var_name and table_var_name
	
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
	$smarty->display("mmm_url_mail.htm");	
}	
?>
