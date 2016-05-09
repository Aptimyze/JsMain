<?php

//********************THIS FILE NEEDS TO BE RUN THROUGH CRONTAB***********************//

/**
*	Filename	:	mmm_fire_test1.php
*	Description	:	This file is run through crontab after every i minutes time interval and checks the TEST field of MAIN_MAILER ,takes their mailer_id and runs test mail according
to the TEST table of mmmjs.This file checks the configuration of mailer and if it is 1 or 2 then
sends the mails which are sheduled from this server and updates their sent status to 1.This SCRIPT FIRES TEST MAILS
**/



//include "connect.inc";
include "arrays.php";
$err="";

chdir(JsConstants::$alertDocRoot.'/mmmjs/');

$r=shell_exec("pwd");
$realpath=realpath(substr($r,0,strlen($r)-1));
include_once(JsConstants::$alertDocRoot."/classes/sendmail.php");
include_once(JsConstants::$smartyDir);
$smarty=new Smarty;
$SITE_URL=JsConstants::$ser2Url.'/mmmjs';
$smarty->assign("SITE_URL",$SITE_URL);

$db=@mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or die("In connect at connecting db");
@mysql_select_db("mmmjs",$db);


//VARIABLES USED IN THIS PHP
$response_php_path=JsConstants::$ser2Url."/mmmjs/mmm_record_response.php";
$unsubscribe_link=JsConstants::$ser2Url."/mmmjs/unsubscribe.php";
$unsubscribe_PromoLink=JsConstants::$siteUrl."/P/UnsubscribePromoMailers.php";
$unsubscribe_link99=JsConstants::$mmmjs99acres."/mmmjs/unsubscribe.php";

$unsubscribe_link_naukri="http://www.naukri.com/customised/unsubscribe/unsubscribe.php?mail_type=urm";

//*******THIS SERVER ID IS CONSTANT '1' FOR SERVER 1 AND '2' FOR SERVER 2***************** 

// THIS IS SERVER 1
$sid=1;
$add_addresslist_hcm = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" > You are receiving this mail as a registered member of Jeevansathi.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br>";
$add_addresslist_tpm = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of Jeevansathi.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br></body>";
/*$add_addresslist_tpm_naukri = "<body><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of naukri.com<br>Please add info@naukri.com to your address book to ensure delivery into your inbox </font><br><br>";*/

$add_addresslist_hcm99 = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" > You are receiving this mail as a registered member of 99acres.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br>";
$add_addresslist_tpm99 = "<br><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of 99acres.com<br>Please add this id to your address book to ensure delivery into your inbox </font><br></body>";



          
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


	
		/**
		*	Function	:	get_test_emails()
		*	Input		:	
		*	Output		:	array of emails of persons to whom test mail is to be sent
		*	Description	:	this function returns an array which has all the emails to whom TEST MAIL is to be fired
		**/
function get_test_emails()
{
	global $err;
	$sql="SELECT EMAIL FROM TEST WHERE DELETED=0";
	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	while($row=mysql_fetch_array($result))
	{
		$arr[]=array("email"=>$row[EMAIL]);
	}
	return $arr;
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



                /**
                *       Function        :       mailsent()
                *       Input           :       mailer_id
                *       Output          :       -----------
                *       Description     :       It updates sent status for mails sent
                **/
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
                *       Function        :       update_tested_status()
                *       Input           :       mailer_id
                *       Output          :       -----------
                *       Description     :       It updates tested status for mails tested
                **/
function update_tested_status($mailer_id)
{
	global $err;
        $sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID='$mailer_id' ";
        $result1=mysql_query($sql1) or $err.="\n$sql \nError :".mysql_error();
        $row1=mysql_fetch_array($result1);
        $status=$row1['STATUS'];
        if($status=='')
        {
	        $sql="UPDATE MAIN_MAILER SET STATUS='nok' WHERE MAILER_ID='$mailer_id'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
                                                                                                 
        }
        elseif($status=='kil')
        {
	        $sql="UPDATE MAIN_MAILER SET STATUS='rok' WHERE MAILER_ID='$mailer_id'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        }
	elseif($status=='old')
        {
		$sql="UPDATE MAIN_MAILER SET STATUS='ook' WHERE MAILER_ID='$mailer_id'";
                mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
        }

	$sql="UPDATE MAIN_MAILER SET TEST='N' WHERE MAILER_ID='$mailer_id'";
        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
}                                                                                        

                /**
                *       Function        :       update_retested_status()
                *       Input           :       mailer_id
                *       Output          :       -----------
                *       Description     :       It updates retested status for mails retested
                **/
function update_retested_status($mailer_id)
{
	global $err;
        $sql="UPDATE MAIN_MAILER SET RETEST='N' WHERE MAILER_ID='$mailer_id'";
        mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
}

/*function added by Neha to check conflict profile. These 1111 should be equal to common constant MRC_NUM in 99*/
function is15DigitProfileId($profileId){
        if($profileId > 111111111111111)
                return true;

        return false;
}
/*code added by Neha ends*/


//************************* MAIN MODULE STARTS HERE**********************************//


$spamFlag='S';
$unsubscribeFlag='U';

$sql = "select MAILER_ID from MAIN_MAILER where TEST='Y'";
$result = mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
while($row=mysql_fetch_array($result))
{	
	$mailerid_test[]=$row[MAILER_ID];
	$mailerid_all[]=$row[MAILER_ID];
}

$sql = "select MAILER_ID from MAIN_MAILER where RETEST='Y'";
$result = mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
while($row=mysql_fetch_array($result))
{
	$mailerid_retest[]=$row[MAILER_ID];
	$mailerid_all[]=$row[MAILER_ID];
}

if($mailerid_all)
{
	foreach($mailerid_all as $mailer_id)
	{


	        $sql="SHOW TABLES LIKE '$mailer_id%'";
        	$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error();
	        $row=mysql_fetch_row($result);
		if($row)
			$table_name=$row[0];
		else
			$table_name=$mailer_id."mailer_s1";	


		echo $table_name;
		if(!check_mailer($mailer_id))
		{
			die;
		}
//		This array consists if info like mailtype and response type of mailer 		
		$mailer_info=get_mailer_info($mailer_id);
//		echo "MAIL TYPE : ";
		$mail_type=$mailer_info[0][mail_type];//echo "\n";
//		echo "RESPONSE TYPE : ";
		$response_type=$mailer_info[0][response_type];//echo "\n";
	//-------------------------------------------------------------------------------------------------------------------------------      

if(strpos($mailer_info[0][sub_query], "PROMO_MAILS='S'")!==false){
  
	$promoMail= 'Y';
}
else {
        $promoMail='N';
}

//-------------------------------------------------------------------------------------------------------------------------------	
		/**********************************************************************************************************************
		*ADD : Neha 05/10/2011 To insert fired date when test mail is fired in the 99acres DB FOR single click response capture mailer. 
		***********************************************************************************************************************

		if(($response_type == 'sc' ||$response_type=='fm') && $mailer_info[0]['mailer_for'] == '9')
		{
			/* gets the data from a URL *
		
		    $ch = curl_init();
		    $timeout = 5;
		    curl_setopt($ch,CURLOPT_URL,JsConstants::$mmmjs99acres."/do/MMM_Response/UpdateMailerFiredDate?q=$mailer_id");
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
		    $data = curl_exec($ch);
		    curl_close($ch);	
		}
		
		/**********************************************************************************************************************
		*END : Neha 05/10/2011 To insert fired date when test mail is fired in the 99acres DB FOR single click response capture mailer. 
		***********************************************************************************************************************/
		$mailer_for=$mailer_info[0][mailer_for];
		$jswalkin=$mailer_info[0][jswalkin];
//		This array consist of data to be send	
//		echo "MAIL DATA : ";

		$arr_mail_data=get_mail_data($mailer_id);
		$defaultsub=$arr_mail_data[subject];

//		This array consist of email ids of persons to whom TEST MAIL need to be send
//		echo "TEST MAIL IDs : ";
		$arr_test_emails=get_test_emails();		

//		echo "TABLE NAME : ";
//		$table_name= $mailer_id."mailer_s1";
//		Please check and replace if issue : Abhinav
//		$table_name= $mailer_id."mailer_s2";
//		echo "\n";
		if($mail_type=='hcm' || $mail_type=='tpm')
		{
			$template_name=$mailer_id."temp.tpl";//echo "\n";
			$fp1=fopen("$realpath/templates/$template_name","w");
			$mail_data=$arr_mail_data[data];
			fputs($fp1,$mail_data);
			fclose($fp1);
			echo "FILE CLOSE";echo "\n";echo "lo".$db."bo";
			$sql="SELECT * FROM $table_name LIMIT 0,1";//echo "\n";
			$result=mysql_query($sql)  or $err.="\n$sql \nError :".mysql_error();	
			$row=mysql_fetch_array($result);

//			TEST MAIL DATABASE
			$s=sizeof($arr_test_emails);
			for($i=0;$i<$s;$i++)
			{
//				echo "INSIDE TEST MAILING LOOP";
				echo "TABLE NAME : ";
//				$table_name=$mailer_id."mailer_s1";//echo "\n";
//				$table_name=$mailer_id."mailer_s2";//echo "\n";
				$arr=get_table_fields($mailer_id);

				$s_f=sizeof($arr);
				$sql_t = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
				$res_t = mysql_query($sql_t) or die("in mmm_fire_test1.php $sql_t");
				$row_t = mysql_fetch_array($res_t);

				$Jor9 = $row_t['MAILER_FOR'];

				for($var_count=0;$var_count<$s_f;$var_count++)
                                {
                                        echo "var name".$arr[$var_count][var_name]."\n";
                                        echo "table_var name".$arr[$var_count][table_var_name]."\n";
                                        echo"Var value is-->".$row[$arr[$var_count][table_var_name]]."\n";
                                        $$arr[$var_count][var_name]=$row[$arr[$var_count][table_var_name]];
                                        $var_name=$arr[$var_count][var_name];
                                        $smarty->assign("$var_name",$row[$arr[$var_count][table_var_name]]);
                                }

				$smarty->assign("mail_type",$mail_type);
				if($Jor9=='J')
				{
					$smarty->assign("echecksum","");
					$prof_id=$row[PROFILEID];
					$prof_id=144111;
					if($prof_id)
					{
						$checksum=md5($prof_id)."i".$prof_id;
						$echecksum=CreateChecksum($checksum,$row[EMAIL]);
						$PromoUnsubscribeChecksum="checksum=$checksum&echecksum=$echecksum";
                                                $smarty->assign("echecksum",$PromoUnsubscribeChecksum."&CMGFRMMMMJS=YESMMMJS");
					}	
				}
				$data=$smarty->fetch("$realpath/templates/$template_name");
				$startOfBodyTag= strpos($data,"<body");
				$endOfBodyTag=strpos($data, '>', strpos($data,"<body")-1);
				$completeBodyTag= substr($data,$startOfBodyTag,$endOfBodyTag-$startOfBodyTag+1);
//--------------------------------------------------------------------------------------------------------------------------------------------
//Added By Nitesh			

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
//				ADDING RESPOSE HEADER
				if($response_type=="i")
				{
					$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&response_type=$response_type&sid=$sid width=\"0\" height=\"0\" border=\"0\"></body>";
				}
				elseif($response_type='o')
				{
					$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
				}
				else 
				{
					$response_header="</body>";
				}
					
//				ADDING UNSUBSCRIBE FOOTER And FINAL MESSAGE

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
			else
			{
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
//				$message=$response_header.$data;
//				echo "SUBJECT :";
				$subject=$arr_mail_data[subject];//echo "\n";
//				echo "FROM : ";
				$from_email=$arr_mail_data[f_email];//echo "\n";	
				$from_name=$arr_mail_data[f_name];//echo "\n";	
//				echo "TO : ";
				$to_email=$arr_test_emails[$i][email];//echo "\n";
//				echo "MESSAGE : ".$message;
//				echo "SENDING MAIL";
				$sent=sendmail($from_email,$to_email,$message,$subject,$from_name);
				if($sent)
				{
//					echo "Mail Sent";
					mailsent($mailer_id);
					if(is_array($mailerid_test))
					{
						if(in_array($mailer_id,$mailerid_test))
						{
//							echo ("*******TEST*************");
							update_tested_status($mailer_id);
						}
					}
					if(is_array($mailerid_retest))
					{
						if(in_array($mailer_id,$mailerid_retest))
						{
//							echo ("*******RETEST*************");
							update_retested_status($mailer_id);
						}
					}
				}
				else 
				{
//					echo "Mail Not sent";
				}
//			echo "MAIL SENDING ENDS HERE";
			}
		}
		elseif(($mail_type=='urm'))
		{
//			echo "This is url Mail \n";

			$template_name=$mailer_id."temp.tpl";
			$fp1=fopen("$realpath/templates/$template_name","w");
			$datat=$arr_mail_data[data];
			fputs($fp1,$datat);
			fclose($fp1);
			$sql="SELECT * FROM $table_name	LIMIT 0,1";	
			$result=mysql_query($sql) or $err.="\n$sql \nError :".mysql_error() ;
			$row=mysql_fetch_array($result);
			
			$s=sizeof($arr_test_emails);
			$Jor9=$mailer_for;	
			for($i=0;$i<$s;$i++)
			{
				$unsubscribe_footer_hcm = '';
				$unsubscribe_footer_tpm = '';
				$isMrc = false;
				$table_name=$mailer_id."mailer_s2";
				$arr=get_table_fields($mailer_id);
						
				$s_f=sizeof($arr);
				$email=$row['EMAIL'];
				for($j=0;$j<$s_f;$j++)
				{
					echo "var name".$arr[$j][var_name]."\n";
					echo "table_var name".$arr[$j][table_var_name]."\n";
					echo"Var value is-->".$row[$arr[$j][table_var_name]]."\n";
					$$arr[$j][var_name]=$row[$arr[$j][table_var_name]];
					$var_name=$arr[$j][var_name];
					
					$smarty->assign("$var_name",$row[$arr[$j][table_var_name]]);
					
					
					/***********************************************************************************
					*ADD : Neha 09/06/2011 Set $token and $profileid variables to be used in the url of 
					*I am interested link  FOR single click response capture mailer. 
					************************************************************************************/
					if($arr[$j]['var_name'] == 'profileid')
					{
						$profileid = $row[$arr[$j]['table_var_name']];
					}
					/*************************************************************************************
					*END : Neha 09/06/2011 Set $token and $profileid variables to be used in the url of 
					*I am interested link  FOR single click response capture mailer
					***************************************************************************************/
						
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
				if($mailer_for=='J')
                                {
                                        $smarty->assign("echecksum","");
                                        $prof_id=$row[PROFILEID];
                                        $prof_id=144111;
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
				*ADD : Neha 09/06/2011 To insert I am interested link in the mailer template and set the token and landing page url after 
				*autologin  FOR single click response capture mailer. 
				***********************************************************************************************************************/
				if($response_type == 'sc' && $mailer_for == '9')
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

				if($response_type == 'fm' && $mailer_for == '9'){
				
					$timestamp = time();
					$url_arg = base64_encode($mailer_id.'|'.$profileid.'|'.$name.'|'.$email.'|'.$phoneNo.'|'.$timestamp);
					$interested_url = "http://www.99acres.com/do/saveFormMailerResponse/saveResponse?MMM=".$url_arg;
					$url_update_arg=base64_encode($mailer_id.'|0|'.$name.'|'.$email.'|'.$phoneNo.'|'.$timestamp);
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
				*END : Neha 09/06/2011 To insert I am interested link in the mailer template and set the token and landing page url after 
				*autologin  FOR single click response capture mailer. 
				***********************************************************************************************************************/
				if($response_type=="i")
				{
					$email=$row[EMAIL];
					$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&response_type=$response_type&sid=$sid width=\"0\" height=\"0\" border=\"0\"></body>";

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
	
//				FINAL MESSAGE
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

//				$message = $add_addresslist."<br>".$message;	
				if(!$jswalkin)
				{
					$subject=$arr_mail_data[subject];
					$from_email=$arr_mail_data[f_email];		
					$from_name=$arr_mail_data[f_name];		
				}
				$to_email=$arr_test_emails[$i][email];
				
				$sent=sendmail($from_email,$to_email,$message,$subject,$from_name);
				
					
				if($sent)
				{
				
//					echo "Mail Sent";
		//			mailsent($mailer_id);
					if(is_array($mailerid_test))
					{
						if(in_array($mailer_id,$mailerid_test))
						{
	//						echo ("TEST");
							update_tested_status($mailer_id);
						}
					}
					if(is_array($mailerid_retest))
					{
						if(in_array($mailer_id,$mailerid_retest))
						{
	//						echo("RETEST");
							update_retested_status($mailer_id);
						}
					}
				}
				else 
				{
//					echo "Mail Not sent";
				}
//				echo "Data in Template mail is is :\n$data\n"; 
					
			}
		}
	}
	if($err)
		mail("vikas@jeevansathi.com","MMMJS ERROR MESSAGES IN TEST SCRIPT","$err");
}
else
	die;

?>
