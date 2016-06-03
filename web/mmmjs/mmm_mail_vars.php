<?php

/**
*	Filename	:	mmm_mail_vars.php
*	Description	:This file will call a template which will take input as no of variables  that need to be embedded in hard code mails and will store in MAIL_VARS table.This file takes the variables and corresponding table fields (these are variables which will be fetched from database while creating table of mailer and these variables will be sent as data in templates)	
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	

/**
*	htms used :a)mmm_mail_vars.htm
		   b)mmm_message.htm
**/

include "connect.inc";

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
	$smarty->display("relogin.htm");
	die;
}

/////////////AUTHENTICATION ROUTINE ENDS HERE///////////////	

		/**
		*	Function	: get_valid_mailers()
		*	Input		:	
		*	Output		: array( of mailer_id )
		*	Description	:	This function will determine all the mailers who are active and for whom sub query has been formed.
		**/
	
	
function get_valid_mailers()
{
	global $smarty;
	$sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='qs' or MAIL_TYPE='crm' ";
	$result=mysql_query($sql) or die("Could not connect db in mmm_mail_vars.php ");
	$no=mysql_num_rows($result);
	if($no)
	{
		while($row=mysql_fetch_array($result))
		{
			$arr[]=array("mailer_id"=>$row['MAILER_ID'],
 				     "mailer_name"=>$row['MAILER_NAME']);
		}
	}
	else 
	{
		$message="There is no Active mailer for whome query has been formed so plaese create mailer with query  first ";
		$smarty->assign("message",$message);
		$smarty->display("mmm_message.htm");
		die;
	}
	return $arr;
}

		/**
		*	Function	:	update_state
		*	Input		:	mailer_id ,final state of mailer
		*	Output		:
		*	Description	:	This function updates the state of mailer to vd(variable defined	
		**/

function update_state($mailer_id,$state)
{
	$sql="UPDATE MAIN_MAILER SET STATE='$state' WHERE MAILER_ID='$mailer_id'";
	mysql_query($sql) or die("Could not update the state in mail_vars.php");
}
		
/*function check_field_vars($table_var_name)
{
	$str="";

	$sql="DESCRIBE TEMP";
	$result=mysql_query($sql) or die("Could not open TEMP table of gurtemp DATABASE in MMM_MAIL_VARS.PHP ");
	while($row=mysql_fetch_array($result))
	{
		$field_arr[]=array("field_name"=>$row[Field]);
	}

	$s=sizeof($table_var_name);
	for($i=0;$i<$s;$i++)
	{
		$temp=$table_var_name[$i];
		echo "inside->$temp<br>";
		if(!(in_array($table_var_name[$i],$field_arr)))
			$str.=$table_var_name[$i].",";
	}
	return $str;
}
*/
	
		/**
		*	Function	: get_table_fields
		*	Input		:	
		*	Output		:	array( of fields of table(this is the JPROFILE TABLE whose fields could be selected for sending data in mail )
		*	Description	:	this function returns the array of fields of JROFILE TABLE WHICH ARE RELEVANT TO MAILING
		**/

	
function get_table_fields($flag)
{
	if($flag=='J')
	{
		$neglect_arr=array("EMAIL",
			"IPADD",
			"SOURCE",
			"PROMO",
			"HEARD",
			"ACTIVATED",
			"MESSENGER_ID",
			"MESSENGER_CHANNEL",
			"SCREENING",
			"CONTACT",
			"SHOWPHONE_RES",
			"SHOWPHONE_MOB",
			"HAVEPHOTO",
			"PHOTO_DISPLAY",
			"PHOTOSCREEN",
			"PREACTIVATED",
			"KEYWORDS",
			"PHOTODATE",
			"PHOTOGRADE",
			"TIMESTAMP",
			"PROMO_MAILS",
			"SERVICE_MESSAGES",
			"PERSONAL_MATCHES",
			"SHOWADDRESS",
			"UDATE",
			"SHOWMESSENGER",
			"PINCODE");

		$sql="DESCRIBE newjs.JPROFILE";
		$result=mysql_query($sql) or die("Could not open TEMP table of gurtemp DATABASE in MMM_MAIL_VARS.PHP ".mysql_error());
		while($row=mysql_fetch_array($result))
		{
			if(!in_array($row['Field'],$neglect_arr))
				$field_arr[]=array("field_name"=>$row['Field']);
		}
		return $field_arr;
	}
	if($flag=='9')
	{
		$sql = "DESCRIBE property.PROFILE";
		$db99 = connect_db_99('property');
		$result=mysql_query($sql,$db99) or die("Could not open TEMP table of gurtemp DATABASE in MMM_MAIL_VARS.PHP ".mysql_error());
		mysql_close($db99);
		while($row=mysql_fetch_array($result))
                {
			$field_arr[]=array("field_name"=>$row['Field']);
		}
		return $field_arr;
	}
}
		
		/**
		*	Function	:check_assigned_values
		*	Input		:variable name(array) and corresponding table 						 
		*	Output		:True/False
		*	Description	:It will check whether corresponding to a variable name 							does there exist a table  variable name or not 
		*
		**/

function check_assigned_values($var_name,$table_var_name)
{
	$s1=sizeof($var_name);
	$s2=sizeof($table_var_name);
	
	$check=1;
	for($i=0;$i<$s1;$i++)
	{
		if(($var_name[$i]=="") || ($table_var_name[$i]==""))
			$check=0;
	}
	return $check;	
}
		
		/**
		*	Function	:	if_email
		*	Input		:	array(of selected fields of table)
		*	Output		:
		*	Description	:	 This function checks whether you have selected an Email or not		
		**/		
		

function if_email($table_var_name)
{
	$i=0;
	$s=sizeof($table_var_name);
	for($i=0;$i<$s;$i++)
	{
		if($table_var_name[$i]=="EMAIL")
		{
			$i=1;
		}
	}
	return $i;
}

		/**
		*	Function	:insert_vars
		*	Input		:variable name(array) and corresponding table 						*						*				variale name(array)
		*	Output		:
		*	Description	:This will insert the varible and corresponding table_variable name into MAIL_VAR 							table
		**/

function insert_vars($var_name,$table_var_name,$mailer_id,$email)
{
	$str="";
	/***********************************************************
	*ADD : Neha 14/07/2012  To insert Only EMAIL for 99 mailers. 
	************************************************************/
	// ADDED : Neha 20/02/2013 : adding NAME along with EMAIL and PROFILEID against ticket #1314
	$profileAlreadyAdded = "";
	$sql = "SELECT MAILER_FOR, RESPONSE_TYPE FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$mailer_for = $row['MAILER_FOR'];
	$response = $row['RESPONSE_TYPE'];
	if($mailer_for == '9') 
	{ 
		$mailer_id_arr = explode("_",$mailer_id);
		$mailer_id = $mailer_id_arr[0];
		$str.="( $mailer_id ,'$email' ,'EMAIL'),('$mailer_id' ,'profileid' ,'PROFILEID'),('$mailer_id','name','NAME'),('$mailer_id','phone','PHONE') ";
		echo "str is $str<br>";
		$sql="INSERT INTO MAIL_VARS(MAILER_ID,VAR_NAME,TABLE_VAR_NAME) VALUES ('$mailer_id','$email','EMAIL'),('$mailer_id' ,'profileid' ,'PROFILEID'),('$mailer_id','name','NAME'),('$mailer_id','phone','PHONE') ";
	}
	/*************************************************************
	*END : Neha 14/07/2012 To insert Only EMAIL for 99 mailers. 
	**************************************************************/
	else 
	{
		$str.="('$mailer_id','$email' ,'EMAIL')";
		echo "str is $str<br>";
		$s=sizeof($var_name);

		if($s)
		{
			$str.=",";
			for($i=0;$i<$s;$i++)
			{	
				echo "more than 1 mail vars";
				$str.=" (".$mailer_id.",'".$var_name[$i]."','".$table_var_name[$i]."'),";
			}
			$str=substr($str,0,(strlen($str)-1) );
		}

		$sql="INSERT INTO MAIL_VARS(MAILER_ID,VAR_NAME,TABLE_VAR_NAME) VALUES $str";
	}
	mysql_query($sql) or die("Could not insert into MAIL_VARS tables".mysql_error());
} 
			
if($submit)
//when user submits the no of variable then it is passed to page where he gets the text box
{
	$sql = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$flag = $row['MAILER_FOR'];
	$field_arr=get_table_fields($flag);
	$smarty->assign("field_arr",$field_arr);
	$smarty->assign("mailer_id",$mailer_id);
	$nno=array();
// Here we are creating an array of size ($no-1) so that we can produce $no-1 select boxses in tpl
	$s=$no-1;
	for($i=0;$i<$s;$i++)
	{
		$nno[]=array("count"=>($i));
	}
	$smarty->assign("no",$no);
	$smarty->assign("nno",$nno);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_assign_vars.htm");
	die;
}
elseif($assign_values)
{
// When user assigns the variable name to the correspodinf fields name
	$message="";
	if($table_var_name)
	{
		$check1=check_assigned_values($var_name,$table_var_name);
//		$check2=if_email($table_var_name);
		if($check1==0) //this will call back the tpl for further filling of varibles
		{
			$message=">>> You have not filled the all enteries please fill it again >>>";
		}
	}
/*	elseif($check2==0)
	{
		$message.="One of the Feild must be Email so please select Email ";	
	}*/

	if($message)
	{
//		echo "check failed<br>";
//		$arr=get_new_mailers();
		$field_arr=get_table_fields('J');
//		print_r($field_arr);
		$smarty->assign("field_arr",$field_arr);
	        $nno=array();
                // Here we are creating an array of size ($no-1) so that we can produce $no-1 select boxses in tpl
	        $s=$no-1;
        	for($i=0;$i<$s;$i++)
        	{
                	$nno[]=array("count"=>($i));
        	}
//        	print_r($nno);
//		$message="You have not filled the all enteries please fill it again";	
		$smarty->assign("arr",$arr);
		$smarty->assign("message",$message);
		$smarty->assign("nno",$nno);
		$smarty->assign("mailer_id",$mailer_id);
		$smarty->assign("var_name",$var_name);
		$smarty->assign("table_var_name",$table_var_name);
		$smarty->assign("cid",$cid);
		$smarty->display("mmm_assign_vars.htm");
		die;
	}			

	$result=insert_vars($var_name,$table_var_name,$mailer_id,$email);
	$state="vd";
	update_state($mailer_id,$state);
	$message="Mail Variables and corresponding field names have been added in the MAIL_VARS table. Now you can create table for following mailer ";
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
						
}
else
{	
// When first time user comes then it will take to the page where user enters the variables in the hard code mail. 
	$mailer_id_arr=get_valid_mailers();
	//ADDED By Neha on 14/07/2012: To insert mailer_for in $mailer_id_arr to allow only EMAIL in assign variable link.
	foreach($mailer_id_arr as $key=>$value){
		$mailer_id = $value['mailer_id'];
		$sql = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$flag = $row['MAILER_FOR'];
		$res_type = $row['RESPONSE_TYPE'];
		$mailer_id_arr[$key]['mailer_for'] = $flag;
	}
	//ADDED By Neha on 14/07/2012: To insert mailer_for in $mailer_id_arr to allow only EMAIL in assign variable link ends here.
	$smarty->assign("mailer_id_arr",$mailer_id_arr);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_mail_vars.htm");
	die;			
}

?>
