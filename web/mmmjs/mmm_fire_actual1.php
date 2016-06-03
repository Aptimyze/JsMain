<?php
//*********THIS FILE NEEDS TO BE RUN THROUGH CRONTAB************************* 

/**
*	Filename	:	mmm_fire_actual1.php
*	Description	:	This file is run through crontab after every 1 minutes time interval and checks the FIRE field of MAIN_MAILER ,takes their mailer_id and fires actual mail .This file checks the configuration of mailer and if it is 1 or 2 then sends the mails which are sheduled from this server and updates their sent status to 1.This SCRIPT FIRES ACTUAL MAILS
**/

ini_set("max_execution_time","0");

include_once(JsConstants::$alertDocRoot."/classes/sendmail.php");


include "arrays.php";

$err="";
chdir(JsConstants::$alertDocRoot.'/mmmjs');

$r=shell_exec("pwd");
$realpath=realpath(substr($r,0,strlen($r)-1));

include_once(JsConstants::$smartyDir);
$smarty=new Smarty;


$SITE_URL=JsConstants::$ser2Url.'/mmmjs';
$smarty->assign("SITE_URL",$SITE_URL);

// connection string
$db=@mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or logerror1("In connect at connecting db","");

mysql_query('set session wait_timeout=1000,interactive_timeout=1000,net_read_timeout=1000',$db);
@mysql_select_db("mmmjs",$db);


/*************************************Header variables********************************/
$add_addresslist_hcm = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" > You are receiving this mail as a registered member of Jeevansathi.com<br>Please add info@jeevansathi.com to your address book to ensure delivery into your inbox </font><br>";
$add_addresslist_tpm = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of Jeevansathi.com<br>Please add info@jeevansathi.com to your address book to ensure delivery into your inbox </font><br></body>";

$add_addresslist_hcm99 = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" > You are receiving this mail as a registered member of 99acres.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br>";
$add_addresslist_tpm99 = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of 99acres.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br></body>";
/*************************************************************************************/

//VARIABLES USED IN THIS PHP
$response_php_path=JsConstants::$ser2Url."/mmmjs/mmm_record_response.php";
$unsubscribe_link=JsConstants::$ser2Url."/mmmjs/unsubscribe.php";
$unsubscribe_PromoLink=JsConstants::$siteUrl."/P/UnsubscribePromoMailers.php";
$unsubscribe_link99=JsConstants::$mmmjs99acres."/mmmjs/unsubscribe.php";

//************THIS SERVER ID IS CONSTANT '1' FOR SERVER 1 AND '2' FOR SERVER 2**************** 

//THIS IS SERVER 1
$sid=$argv[2];

function getIST()
{
	//gmt date
	$gmt = gmdate("H-i-s-m-d-Y");
	$ga = explode("-",$gmt);
	
	$gmtm = mktime($ga[0],$ga[1],$ga[2],$ga[3],$ga[4],$ga[5]);
	
	//adding 5:30 mins to gmt to get ist
	$toadd = 19800;
	
	$retime = $gmtm + $toadd;
	return $retime;
}

/**
		*	Function	:	get_mail_data
		*	Input		:	mailer_id
		*	Output		:	array of mail data (i.e. subject ,from address and content etc.)
		*	Description	:	this function finds the mail data for a particular mailer_id
		**/
function CreateChecksum($plainText,$email)
{
		$cur_dir=realpath(dirname(__FILE__));
        include_once($cur_dir."/../classes/authentication.class.php");
        $protect=new protect;
        return $protect->js_encrypt($plainText,$email);
}
function get_mail_data($mailer_id)
{
        global $err;
	$sql="SELECT * FROM MAIL_DATA WHERE MAILER_ID=$mailer_id AND ACTIVE='Y'";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	$row=mysql_fetch_array($result);
	$arr=array("subject"=>$row[SUBJECT],
	"f_email"=>$row[F_EMAIL],
	"f_name"=>$row[F_NAME],
	"data"=>stripslashes($row[DATA]),
	"browserUrl"=>$row[BROWSERURL]
	);
	return $arr;
}

		/**
		*	Function	:	check_mailer
		*	Input		:	mailer_id
		*	Output		:	0 or 1
		*	Description	:	this function checks whether the mailer exist or not
		**/
		
function check_mailer($mailer_id)
{
        global $err;
	$sql="SELECT COUNT(*) AS COUNT FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	$row=mysql_fetch_array($result);
	$no=$row[COUNT];
	return $no;
}


function get_sid($mailer_id)
{
        global $err;
	$tabarr = array();
        $sql = "select SID from MAILER_SERVER where MAILER_ID = '$mailer_id'";
        $res = mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        while($row = mysql_fetch_array($res))
        {
                $tabarr[] = $row[SID];
        }
        return $tabarr;
}


		/**
		*	Function	:	get_mailer_info
		*	Input		:	mailer_id
		*	Output		:	array consisting the information of mailer(specified by mailer_id)
		*	Description	:	It finds information of mail_type and response_type of the mailer for given mailer_id
		**/
function get_mailer_info($mailer_id)
{
        global $err;
	$sql="SELECT MAIL_TYPE,RESPONSE_TYPE,MAILER_FOR,JSWALKIN,SUB_QUERY FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	$row=mysql_fetch_array($result);
	$mailer_info[0]=array("mail_type"=>$row[MAIL_TYPE],
	"response_type"=>$row[RESPONSE_TYPE],
	"mailer_for"=>$row[MAILER_FOR],
	"jswalkin"=>$row[JSWALKIN],
	"sub_query"=>$row[SUB_QUERY]
	);

	return $mailer_info;
}


		/**
		*	Function	:	get_table_fields
		*	Input		:	mailer_id
		*	Output		:	array of fields(which needs to be sent in mail as data) 
		*	Description	:	It returns the array of fields of mailer_id FROM MAIL_VARS TABLE
		**/
function get_table_fields($mailer_id)
{
        global $err;
	$sql="SELECT VAR_NAME,TABLE_VAR_NAME FROM MAIL_VARS WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	while($row=mysql_fetch_array($result))
	{
		$field_arr[]=array("var_name"=>$row[VAR_NAME],
		"table_var_name"=>$row[TABLE_VAR_NAME]
		);
	}
	return $field_arr;
}

function mailsent($mailer_id)
{
        global $err;
	$date=date("Y-m-j");
        $sql="select MAILER_ID from MAIL_SENT where DATE='$date' and MAILER_ID='$mailer_id'";
        $result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        if(mysql_num_rows($result)>0)
        {
        	$sql="UPDATE MAIL_SENT SET SENT=(SENT+1) WHERE MAILER_ID ='$mailer_id' AND DATE='$date'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	}
        else
        {
        	$sql="INSERT INTO MAIL_SENT(DATE,MAILER_ID,SENT) VALUES('$date','$mailer_id',1)";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        }
}

		/**
		*	Function	:	update_sent_status
		*	Input		:	mailer_id,email
		*	Output		:	
		*	Description	: This function updates the SENT FIELD OF TABLE WHEN MAIL IS SUCESSFULY SENT TO HIM
		**/

function update_sent_status($mailer_id,$email)
{
        global $err,$sid;
	$table_name=$mailer_id."mailer_s$sid";
	$sql="UPDATE $table_name SET SENT=1 WHERE EMAIL='$email'";
	mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
}


function update_notsent_status($mailer_id,$email)
{
        global $err,$sid;
        $table_name=$mailer_id."mailer_s$sid";
        $sql="UPDATE $table_name SET SENT=2 WHERE EMAIL='$email'";
        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
}


		/**
		*	Function	:	update_mailer_status
		*	Input		:	mailer_id,time,config_id,event
		*	Output		:
		*	Description	:This function updates the mailer STATUS depending upon its configuration and event 
		*	$event could be 's' (start) or 'e' (end)
		**/


function update_mailer_status($mailer_id,$time,$sid,$event)
{
        global $err;
	if($event=='s')//WHEN MAILER IS STARTING
        {
       		$sql="UPDATE MAILER_SERVER SET STIME='$time' WHERE MAILER_ID='$mailer_id' AND SID='$sid'";
            	mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
   	}
   	elseif($event=='e')//WHEN MAILER IS ENDING
 	{
     		$sql="UPDATE MAILER_SERVER SET ETIME='$time' WHERE MAILER_ID='$mailer_id' AND SID='$sid'";
  		mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
  	}
}

 /**
                *       Function        :       get_test_emails()
                *       Input           :
                *       Output          :       array of emails of persons to whom test mail is to be sent
                *       Description     :       this function returns an array which has all the
emails to whom TEST MAIL is to be fired
                **/
                                                                                                 
function get_test_emails($table_name)
{
        global $err;
	$sql="SELECT EMAIL FROM $table_name where SENT=0";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	while($row=mysql_fetch_array($result))
	{
		$arr[]=array("email"=>$row[EMAIL]);
	}
	return $arr;
}

function update_running_status($mailer_id)
{
        global $err;
        $sql1="SELECT S1_FIRE,S2_FIRE FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id' ";
        $result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
        $row1=mysql_fetch_array($result1);
        $s1_fire=$row1['S1_FIRE'];
	$s2_fire=$row1['S2_FIRE'];
	if($s1_fire=='Y' || $s2_fire=='Y')
	{
	        $sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID='$mailer_id' ";
        	$result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
	        $row1=mysql_fetch_array($result1);
        	$status=$row1['STATUS'];
	        if($status=='nok')
        	{
                	$sql="UPDATE MAIN_MAILER SET STATUS='run' WHERE MAILER_ID='$mailer_id'";
	                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        	}	
	        elseif($status=='rok')
        	{
			$sql="UPDATE MAIN_MAILER SET STATUS='res' WHERE MAILER_ID='$mailer_id'";
	                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        	}
		elseif($status=='ook')
	        {
	        	$sql="UPDATE MAIN_MAILER SET STATUS='orn' WHERE MAILER_ID='$mailer_id'";
        	        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	        }
	}
        $sql="UPDATE MAIN_MAILER SET S1_FIRE='N',S2_FIRE='N' WHERE MAILER_ID='$mailer_id'";
        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
}

function update_ran_status($mailer_id)
{
        global $err;
        $sql="UPDATE MAIN_MAILER SET S1_FIRED='Y' WHERE MAILER_ID='$mailer_id'";
        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
                                                                                                 
        $sql1="SELECT S1_FIRED,S2_FIRED FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id' ";
        $result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
        $row1=mysql_fetch_array($result1);
        $s1_fired=$row1['S1_FIRED'];
        $s2_fired=$row1['S2_FIRED'];
       
        if($s1_fired=='Y'/* && $s2_fired=='Y'*/)
        {
                $sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID='$mailer_id' ";
                $result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
                $row1=mysql_fetch_array($result1);
                $status=$row1['STATUS'];
                if($status=='run' || $status=='res' || $status=='orn')
                {
                        $sql="UPDATE MAIN_MAILER SET STATUS='ran' WHERE MAILER_ID='$mailer_id'";
                        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
                }
                $sql="UPDATE MAIN_MAILER SET S1_FIRED='N' WHERE MAILER_ID='$mailer_id'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
                                                                                            
                $sql="UPDATE MAIN_MAILER SET S2_FIRED='N' WHERE MAILER_ID='$mailer_id'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        }
}

//******************MAIN MODULE STARTS HERE*******************************
$t=0;
$spamFlag='S';
$unsubscribeFlag='U';
$pid1=getmypid();
$mailer_id= $argv[1];		
$sql="UPDATE MAIN_MAILER SET PID1='$pid1' WHERE MAILER_ID='$mailer_id'";
mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();


if(!check_mailer($mailer_id))
{
	//echo" This mailer does not exist\n";
	die;
}
//This array consist of info about mailer (attributes like mail type,response type etc. )
$mailer_info=get_mailer_info($mailer_id,$remote_server);
$mail_type=$mailer_info[0][mail_type];
$jswalkin=$mailer_info[0][jswalkin];
// This array consist of data to be sended
$arr_mail_data=get_mail_data($mailer_id,$remote_server);
$defaultsub=$arr_mail_data[subject];

//-------------------------------------------------------------------------------------------------------------------------------	
if(strpos($mailer_info[0][sub_query], "PROMO_MAILS='S'")!==false){

	$promoMail= 'Y';
}
else {

       $promoMail='N';
}
//-------------------------------------------------------------------------------------------------------------------------------
// Response Type
$table_name= $mailer_id."mailer_s$sid";

$response_type=$mailer_info[0][response_type];

/**********************************************************************************************************************
*ADD : Neha 21/06/2011 To insert fired date when mail sent in the 99acres DB FOR single click response capture mailer. 
***********************************************************************************************************************/
if(($response_type == 'sc' ||$response_type=='fm') && $mailer_info[0]['mailer_for'] == '9')
{
	/* gets the data from a URL */

    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,"http://www.99acres.com/do/MMM_Response/UpdateMailerFiredDate?q=$mailer_id");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
    $data = curl_exec($ch);
    curl_close($ch);	
}

/**********************************************************************************************************************
*END : Neha 21/06/2011 To insert fired date when mail sent in the 99acres DB FOR single click response capture mailer. 
***********************************************************************************************************************/
/*function added by Neha to check conflict profile.these 111 should be equal to MRC_NUM common constant in 99*/
function is15DigitProfileId($profileId){
        if($profileId > 111111111111111)
                return true;

        return false;
}
/*code added by Neha ends*/

//$arr_test_emails=get_test_emails($table_name);	
//$table_name= $mailer_id."mailer_s2";
if($mail_type=='hcm' || $mail_type=='tpm')
{
	//For Hard Code Mail
//	echo "This is Hard Code/Template Mail \n";
	$template_name=$mailer_id."actual.tpl";
//	echo "FILE OPEN";echo "\n";
	$fp1=fopen("$realpath/templates/$template_name","w");
	$mail_data=$arr_mail_data[data];
	fputs($fp1,$mail_data);
	fclose($fp1);
//	echo "FILE CLOSE";echo "\n";
	//THIS PART FETCHES DATA FROM THE (mailer_id)mailer_s1 TABLE  i.e EMAIL etc FOR SENDING
	$sql1="SELECT COUNT(*) as cnt FROM $table_name where SENT=0";
	$result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
	$row1=mysql_fetch_array($result1);
	$count=$row1['cnt'];
	if($count>0)
	{
		$sql="SELECT * FROM $table_name	where SENT=0";
		$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
		// array of fields
		$arr=get_table_fields($mailer_id);
		$s_f=sizeof($arr);
		$s_time=getIST();
		update_mailer_status($mailer_id,$s_time,$sid,'s');
		update_running_status($mailer_id);	
		while($row=mysql_fetch_array($result))
		{
//			echo "INSIDE MAILING LOOP ";echo "\n";
			$table_name=$mailer_id."mailer_s$sid";
			$table_name;
			// ADDING UNSUBSCRIBE FOOTETR
			$sql_t = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
                        $res_t = mysql_query($sql_t) or die("in mmm_fire_actual1.php $sql_t");
                        $row_t = mysql_fetch_array($res_t);
                        $Jor9 = $row_t['MAILER_FOR'];
                        
			for($j=0;$j<$s_f;$j++)
			{

				$$arr[$j][var_name]=$row[$arr[$j][table_var_name]];

				//*CODE ADDED TO GET LABEL INPLACE OF VALUES **//
				$var_value=$row[$arr[$j][table_var_name]];
				$column_name=$arr[$j][table_var_name];
				$var_name=$arr[$j][var_name];
				$var_value=$row[$arr[$j][table_var_name]];
				if(array_key_exists("$column_name",$from_array))
				{
					$var_label=$from_array[$column_name][$var_value];                                        }
				elseif(array_key_exists("$column_name",$from_table))
				{
					$var_label=label_select($from_table[$column_name],$var_value);
				}
				elseif(in_array("$column_name",$from_date))
				{
					list($date,$time)=explode(" ",$var_value);
					list($yy,$mm,$dd)=explode("-",$date);
					$var_label=my_format_date($dd,$mm,$yy);
				}
				elseif(in_array("$column_name",$from_direct))
				{
					$var_label=$var_value;
				}
				$smarty->assign("$var_name",$var_label);

				//****CODE ADDITION ENDS HERE******//
				
				 


			}
			if($Jor9=='J')
			{
				$smarty->assign("echecksum","");
				$prof_id=$row[PROFILEID];
				if($prof_id)
				{
					$checksum=md5($prof_id)."i".$prof_id;
					$echecksum=CreateChecksum($checksum,$row[EMAIL]);
					$PromoUnsubscribeChecksum="checksum=$checksum&echecksum=$echecksum";
                                        $smarty->assign("echecksum",$PromoUnsubscribeChecksum."&CMGFRMMMMJS=YESMMMJS");
				}
			}
			
			$smarty->assign("mail_type",$mail_type);
			$data=$smarty->fetch("$realpath/templates/$template_name");
			$startOfBodyTag= strpos($data,"<body");
			$endOfBodyTag=strpos($data, '>', strpos($data,"<body")-1);
			$completeBodyTag= substr($data,$startOfBodyTag,$endOfBodyTag-$startOfBodyTag+1);
//--------------------------------------------------------------------------------------------------------------------------------------------
			

if($Jor9=='J'){
	if ($promoMail=='Y'){
	
			if($arr_mail_data['browserUrl']!=''){
			$browserUrl=$arr_mail_data['browserUrl'];
			$string="$completeBodyTag
<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td height=\"10\"></td>
</tr>
<tr>
<td style=\"font-family: Arial,Helvetica,sans-serif; font-size: 11px; height: 30px; color:#999;\" align=\"center\">If you are unable to view this mailer, please <a style=\"color: rgb(153, 153, 153);\" href=\"$browserUrl\" target=\"_blank\">click here</a></td>
</tr>
</table><br><br>

<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"591\">
<tr><td style=\"text-align:right; height:45px; font-family:arial; \"><a style=\"color:#258ec0; font-size:11px;  border:1px solid #c4c4c4;background:#dbdbdb; font-weight:bold;  padding:6px;text-decoration:none\" href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$spamFlag\"> Report spam</a>  <a href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$unsubscribeFlag\" style=\"color:#258ec0; font-size:11px; text-decoration:none; border:1px solid #c4c4c4;background:#dbdbdb; padding:6px;font-weight:bold;\">Unsubscribe</a>
<br>
</td>
</tr>
</table><br>";
			}
			else{
			$string="$completeBodyTag
<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"591\">
<tr><td style=\"text-align:right; height:45px; font-family:arial; \"><a style=\"color:#258ec0; font-size:11px;  border:1px solid #c4c4c4;background:#dbdbdb; font-weight:bold;  padding:6px;text-decoration:none\" href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$spamFlag\"> Report spam</a>  <a href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$unsubscribeFlag\" style=\"color:#258ec0; font-size:11px; text-decoration:none; border:1px solid #c4c4c4;background:#dbdbdb; padding:6px;font-weight:bold;\">Unsubscribe</a>
<br>
</td>
</tr>
</table><br>";
			}
		$data=str_replace($completeBodyTag, $string , $data);
	}
	if ($promoMail=='N'){			
	         $unsubscribe_footer_hcm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Use this link to Unsubscribe </a>";
                 $unsubscribe_footer_tpm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Use this link to Unsubscribe </a></body>";
                }

}

else if($Jor9=='9'){
		$unsubscribe_footer_hcm="<br><a href=$unsubscribe_link99?mailer_id=$mailer_id>Use this link to Unsubscribe </a>";
		$unsubscribe_footer_tpm="<br><a href=$unsubscribe_link99?mailer_id=$mailer_id>Use this link to Unsubscribe </a></body>";
}
//--------------------------------------------------------------------------------------------------------------------------------------------

			// ADDING RESPONSE HEADER
			if($response_type=="i")
			{
				$email=$row[EMAIL];
				$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&sid=$sid&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
			}
			elseif($response_type='o')
			{
				$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
			}
			else
			{
				$response_header="</body>";
			}
			
			// FINAL MESSAGE
		if($mail_type!='tpm'){
			if($Jor9=='J'){
				if ($promoMail=='N')
                   	     		$message="<html><body>".$response_header.$data.$add_addresslist_hcm.$unsubscribe_footer_hcm."</body></html>";
				else
				  	   $message="<html><body>".$response_header.$data.$add_addresslist_hcm."</body></html>";
			}
       			 else if($Jor9=='9'){
                  		      $message="<html><body>".$response_header.$data.$add_addresslist_hcm99.$unsubscribe_footer_hcm."</body></html>";
			}
		}
		else{
			$message=preg_replace("/<\/body>/i",$response_header,$data);
			if($Jor9=='J'){
				if ($promoMail=='N'){
					$message=preg_replace("/<\/body>/i",$add_addresslist_tpm,$message);
					$message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
				}
				else{
					$message=preg_replace("/<\/body>/i",$add_addresslist_tpm,$message);			
				}
			}
			else if($Jor9=='9'){
					$message=preg_replace("/<\/body>/i",$add_addresslist_tpm99,$message);
					$message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
			}
		}
			$subject=$arr_mail_data[subject];
			$from_email=$arr_mail_data[f_email];
			$from_name=$arr_mail_data[f_name];
			$to_email=$row[EMAIL];

			//**********CODE FOR TESTING*************
/*
			$email="abhinavkatiyar2004@yahoo.com";
			$t++;
			if($t==10)
			die("****died****");
			$sent=sendmail($from_email,$email,$message,$subject);
*/
			//********CODE FOR TESTING*************

			if(strlen($message)>10)
				$sent=sendmail($from_email,$to_email,$message,$subject,$from_name);


			if($sent)
			{	//echo "Mail Sent";
				update_sent_status($mailer_id,$to_email);
				mailsent($mailer_id);
				//update_mailer_status(1,$mailer_id,$to_email);
			}
			else
			{//echo "Mail Not sent";
				update_notsent_status($mailer_id,$to_email);
			}
//				$e_time=getIST();
//				update_mailer_status($mailer_id,$e_time,$sid,'e',$remote_server);
		}
//		echo "OUT OF SENDING LOOP";
		update_ran_status($mailer_id);
		$e_time=getIST();
		update_mailer_status($mailer_id,$e_time,$sid,'e');
	}
	else
	{
		update_running_status($mailer_id);
		update_ran_status($mailer_id);
	}
}
elseif($mail_type=='urm')
{

	//echo "This is Url Mail \n";
	$template_name=$mailer_id."actual.tpl";
	$fp1=fopen("$realpath/templates/$template_name","w");
	$datat=$arr_mail_data[data];
	fputs($fp1,$datat);
	fclose($fp1);


	$sql1="SELECT COUNT(*) as cnt FROM $table_name where SENT=0";
	
	$result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error() ;
	$row1=mysql_fetch_array($result1);
	$count=$row1['cnt'];
	if($count>0)
	{
		$sql="SELECT * FROM $table_name	where SENT=0";
		$result=mysql_query($sql)  or $err.="\n$sql \nError :".mysql_error();
		$arr=get_table_fields($mailer_id,$remote_server);
		$s_f=sizeof($arr);
		$s_time=getIST();
		update_mailer_status($mailer_id,$s_time,$sid,'s',$remote_server);
		update_running_status($mailer_id);
		while($row=mysql_fetch_array($result))
		{
			$unsubscribe_footer_hcm = '';
			$unsubscribe_footer_tpm = '';
			$isMrc = false;
			$email=$row['EMAIL'];
			$table_name=$mailer_id."mailer_s$sid";
			$sql_t = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
			$res_t = mysql_query($sql_t) or die("in mmm_fire_actual1.php $sql_t");
			$row_t = mysql_fetch_array($res_t);
			$Jor9 = $row_t['MAILER_FOR'];
			for($j=0;$j<$s_f;$j++)
			{
				
				$$arr[$j][var_name]=$row[$arr[$j][table_var_name]];

				$var_name=$arr[$j][var_name];
				/***********************************************************************************
				*ADD : Neha 21/06/2011 Set $token and $profileid variables to be used in the url of 
				*I am interested link  FOR single click response capture mailer. 
				************************************************************************************/						
				if($arr[$j]['var_name'] == 'profileid')
				{
					$profileid = $row[$arr[$j]['table_var_name']];
				}
				/*************************************************************************************
				*END : Neha 21/06/2011 Set $token and $profileid variables to be used in the url of 
				*I am interested link  FOR single click response capture mailer
				***************************************************************************************/
				$smarty->assign("$var_name",$row[$arr[$j][table_var_name]]);
				if($arr[$j]['var_name'] == 'name')
                                {
                                                $name = $row[$arr[$j]['table_var_name']];
                                                $smarty->assign("name",$name);
                                }
                                if($arr[$j]['var_name'] == 'phone')
                                {
                                                $phoneNo = $row[$arr[$j]['table_var_name']];
                                                if(empty($phoneNo))
                                                        $smarty->assign("phone","-");
                                                else
                                                        $smarty->assign("phone",$phoneNo);
                                }

			}

                        if($Jor9=='J')
                        {        
                                $smarty->assign("echecksum","");
                                $prof_id=$row[PROFILEID];
                                if($prof_id)
                                {       
                                        $checksum=md5($prof_id)."i".$prof_id;
                                        $echecksum=CreateChecksum($checksum,$row[EMAIL]);
					$PromoUnsubscribeChecksum="checksum=$checksum&echecksum=$echecksum";
                                        $smarty->assign("echecksum",$PromoUnsubscribeChecksum."&CMGFRMMMMJS=YESMMMJS");

                                }       

                        }       
			$smarty->assign("mail_type",$mail_type);
	
			/**********************************************************************************************************************
			*ADD : Neha 21/06/2011 To insert I am interested link in the mailer template and set the token and landing page url after 
			*autologin  FOR single click response capture mailer. 
			***********************************************************************************************************************/
			if($response_type == 'sc' && $mailer_info[0]['mailer_for'] == '9')
			{
				$timestamp = time();
				$url_arg = base64_encode($mailer_id.'|'.$profileid.'|'.$timestamp);
				$interested_url = "http://www.99acres.com/do/MMM_Response/authUser?MMM=".$url_arg;
				$smarty->assign("interested_url",$interested_url);
				$isMrc = is15DigitProfileId($profileid);
                                if($isMrc)
                                        $smarty->assign("isMrc",'Y');
                                else
                                        $smarty->assign("isMrc",'N');
			}

			if($response_type == 'fm' && $mailer_info[0]['mailer_for'] == '9'){

				$timestamp = time();
                                $url_arg = base64_encode($mailer_id.'|'.$profileid.'|'.$name.'|'.$email.'|'.$phoneNo.'|'.$timestamp);
                                $interested_url = "http://www.99acres.com/do/saveFormMailerResponse/saveResponse?MMM=".$url_arg;
                                $url_update_arg=base64_encode($mailer_id.'|'.$profileid.'|'.$name.'|'.$email.'|'.$phoneNo.'|'.$timestamp);
                                $updateResponse_url="http://www.99acres.com/do/saveFormMailerResponse/saveResponse?MMM=".$url_update_arg."&update=Y";
                                $smarty->assign("interested_url",$interested_url);
                                $smarty->assign("update_response_url",$updateResponse_url);
				$isMrc = is15DigitProfileId($profileid);
                                if($isMrc)
                                        $smarty->assign("isMrc",'Y');
                                else
                                        $smarty->assign("isMrc",'N');
                         }
                                $smarty->assign("PROFILEID",$profileid);
                                $smarty->assign("email",$email);

			/**********************************************************************************************************************
			*END : Neha 21/06/2011 To insert I am interested link in the mailer template and set the token and landing page url after 
			*autologin  FOR single click response capture mailer. 
			***********************************************************************************************************************/
		
			if($response_type=="i")
			{
				$email=$row[EMAIL];
				$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&sid=$sid&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
			}
			elseif($response_type=='o' || $response_type=='sc'|| $response_type=='fm')
			{
				$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
			}
			else
			{
				$response_header="</body>";
			}
			if($jswalkin)
                        {
                                $sqlbranch="SELECT NEAR_BRANCH FROM jsadmin.MMM_NEARBRANCH WHERE CITY_VALUE='$row[CITY_RES]'";
                                $resbranch=mysql_query($sqlbranch) or die("Error while finding nearest branch of user's city_res $sql_branch ".mysql_error());
                                if(mysql_num_rows($resbranch))
                                {
                                        $rowbranch=mysql_fetch_assoc($resbranch);
                                        $sqldet="SELECT FROM_EMAIL,SUBJECT,SIGNATURE FROM jsadmin.MMM_BRANCH WHERE BRANCH_VALUE='$rowbranch[NEAR_BRANCH]' AND DISABLED=''";
                                        $resdet=mysql_query($sqldet) or die("Error while finding from id,subject and signature of branch");
                                        if(mysql_num_rows($resdet))
                                        {
                                                $rowdet=mysql_fetch_assoc($resdet);
                                                $from_email=$rowdet["FROM_EMAIL"];
                                                $subject=$rowdet["SUBJECT"];
                                                $smarty->assign("signature",nl2br($rowdet["SIGNATURE"]));
                                                $sqldet="SELECT VENUE_NAME,VENUE_CPERSON,VENUE_NUMBER FROM jsadmin.MMM_VENUE WHERE BRANCH='$rowbranch[NEAR_BRANCH]'";
                                                $resdet=mysql_query($sqldet) or die("Error while finding venues of a particular branch $sqldet ".mysql_error());
                                                if(mysql_num_rows($resdet))
                                                {
                                                        while($rowdet=mysql_fetch_assoc($resdet))
                                                        {
                                                                        $venue[]=array("venue_name"=>nl2br($rowdet["VENUE_NAME"]),
                                                                                "venue_cperson"=>nl2br($rowdet["VENUE_CPERSON"]),
                                                                                "venue_number"=>nl2br($rowdet["VENUE_NUMBER"]));
                                                        }
                                                        $smarty->assign("venue",$venue);
                                                        unset($venue);
							}
                                        }
                                        else
                                        {
                                                $smarty->assign("venue",0);
                                                if($row["COUNTRY_RES"]=="51")
                                                $from_email="riya@jeevansathi.com";
                                                else
                                                $from_email="prisha@jeevansathi.com";
                                                $signature="Bhawna D."."<br><br>"."Sr.Manager"."<br><br>"."JeevanSathi.com";
                                                $smarty->assign("signature",$signature);
                                                $subject=$defaultsub;

                                        }
                                }
                                else
                                {
                                        $smarty->assign("venue",0);
                                        if($row["COUNTRY_RES"]=="51")
                                        $from_email="riya@jeevansathi.com";
                                        else
                                        $from_email="prisha@jeevansathi.com";
                                        $signature="Bhawna D."."<br><br>"."Sr.Manager"."<br><br>"."JeevanSathi.com";
                                        $smarty->assign("signature",$signature);
                                        $subject=$defaultsub;
                                }
                        }
                        
			$data=$smarty->fetch("$realpath/templates/$template_name");
			$startOfBodyTag= strpos($data,"<body");
			$endOfBodyTag=strpos($data, '>', strpos($data,"<body")-1);
			$completeBodyTag= substr($data,$startOfBodyTag,$endOfBodyTag-$startOfBodyTag+1);
if($Jor9=='J'){
	if ($promoMail=='Y'){
	
			if($arr_mail_data['browserUrl']!=''){
			$browserUrl=$arr_mail_data['browserUrl'];
			$string="$completeBodyTag
<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td height=\"10\"></td>
</tr>
<tr>
<td style=\"font-family: Arial,Helvetica,sans-serif; font-size: 11px; height: 30px; color:#999;\" align=\"center\">If you are unable to view this mailer, please <a style=\"color: rgb(153, 153, 153);\" href=\"$browserUrl\" target=\"_blank\">click here</a></td>
</tr>
</table><br><br>

<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"591\">
<tr><td style=\"text-align:right; height:45px; font-family:arial; \"><a style=\"color:#258ec0; font-size:11px;  border:1px solid #c4c4c4;background:#dbdbdb; font-weight:bold;  padding:6px;text-decoration:none\" href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$spamFlag\"> Report spam</a>  <a href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$unsubscribeFlag\" style=\"color:#258ec0; font-size:11px; text-decoration:none; border:1px solid #c4c4c4;background:#dbdbdb; padding:6px;font-weight:bold;\">Unsubscribe</a>
<br>
</td>
</tr>
</table><br>";
			}
			else{
			$string="$completeBodyTag
<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"591\">
<tr><td style=\"text-align:right; height:45px; font-family:arial; \"><a style=\"color:#258ec0; font-size:11px;  border:1px solid #c4c4c4;background:#dbdbdb; font-weight:bold;  padding:6px;text-decoration:none\" href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$spamFlag\"> Report spam</a>  <a href=\"$unsubscribe_PromoLink"."?"."$PromoUnsubscribeChecksum&mailer_id=$mailer_id&flag=$unsubscribeFlag\" style=\"color:#258ec0; font-size:11px; text-decoration:none; border:1px solid #c4c4c4;background:#dbdbdb; padding:6px;font-weight:bold;\">Unsubscribe</a>
<br>
</td>
</tr>
</table><br>";
			}
		$data=str_replace($completeBodyTag, $string , $data);
	}
	if ($promoMail=='N'){			
	         $unsubscribe_footer_hcm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Use this link to Unsubscribe </a>";
                 $unsubscribe_footer_tpm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Use this link to Unsubscribe </a></body>";
                }

}

else if($Jor9=='9' && !$isMrc){
		$unsubscribe_footer_hcm="<br><a href=$unsubscribe_link99?mailer_id=$mailer_id>Use this link to Unsubscribe </a>";
		$unsubscribe_footer_tpm="<br><a href=$unsubscribe_link99?mailer_id=$mailer_id>Use this link to Unsubscribe </a></body>";
}
			// FINAL MESSAGE
                        $message=preg_replace("/<\/body>/i",$response_header,$data);
				if($Jor9=='J'){
					if ($promoMail=='N'){
						$message=preg_replace("/<\/body>/i",$add_addresslist_tpm,$message);
						$message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
					}
					else{
						$message=preg_replace("/<\/body>/i",$add_addresslist_tpm,$message);			
					}
				}
				else if($Jor9=='9'){
						$message=preg_replace("/<\/body>/i",$add_addresslist_tpm99,$message);
						$message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
				}	
			if(!$jswalkin)
			{
				$subject=$arr_mail_data[subject];
				$from_email=$arr_mail_data[f_email];
				$from_name=$arr_mail_data[f_name];
			}
			$to_email=$row[EMAIL];

			//**********CODE FOR TESTING*************
/*
			$email="abhinavkatiyar2004@yahoo.com";
			$t++;
			if($t==10)
			die("****died****");
			$sent=sendmail($from_email,$email,$message,$subject);
*/
			//********CODE FOR TESTING*************

			if(strlen($message)>10)
				$sent=sendmail($from_email,$to_email,$message,$subject,$from_name);
			
			if($sent)
			{	//echo "Mail Sent";
				update_sent_status($mailer_id,$to_email);
				mailsent($mailer_id,$remote_server);
			}
			else
			{//echo "Mail Not sent";
				update_notsent_status($mailer_id,$to_email);
			}
			//echo "Data in Template mail is is :\n$data\n";
		}
		update_ran_status($mailer_id);
		$e_time=getIST();
		update_mailer_status($mailer_id,$e_time,$sid,'e',$remote_server);
	}
	else
	{
		update_running_status($mailer_id);
		update_ran_status($mailer_id);
	}
}
mail("abhinav.katiyar@jeevansathi.com","MMMJS ERROR MESSAGES IN MAIN SCRIPT","$err");
?>
