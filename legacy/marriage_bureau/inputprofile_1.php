<?php

/************************************************************************************************************************
* 	FILE NAME	:	inputprofile_1.php
* 	DESCRIPTION 	: 	Get details for a new Marriage Bureau account
* 	MODIFY DATE	: 	24th March 2006
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	Marriage Bureau 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include_once("connectmb.inc");
$smarty_flag = 'n';
$db=connect_dbmb();
mysql_select_db_js('jsadmin');
include_once("../jsadmin/connect.inc");
$ip=getenv("REMOTE_ADDR");//Gets ipaddress of user
$jsdata=authenticated($cid);
$smarty->assign('cid',$cid);
mysql_select_db_js('marriage_bureau');
if($jsdata)
{
	if($Submit)
	{
		$error=0;
		$errortrue=1;
		$errorfalse=0;
		if($nameofbureau=="")
		{
			$error++;
			$smarty->assign("nameofbureau_error",$errortrue);
		}
		if($address=="")
		{
			$error++;
			$smarty->assign("address_error",$errortrue);
		}
		if($city=="")
		{
			$error++;
			$smarty->assign("city_error",$errortrue);
		}
		if($tel1=="")
		{
			$error++;
			$smarty->assign("tel1_error",$errortrue);
		}
		if($c_name=="")
		{
			$error++;
			$smarty->assign("c_name_error",$errortrue);
		}
		if($c_designation=="")
		{
			 $error++;
			 $smarty->assign("c_designation_error",$errortrue);        
		}
		if($c_tel=="" && $c_mob=="")
		{
			$error++;
			$smarty->assign("c_tel_error",$errortrue);
		}
		if($freeorpaid=="")
		{
			$error++;
			$smarty->assign("freeorpaid_error",$errortrue);
		}
		if($username=="")
		{
			$error++;
			$smarty->assign("username_error",$errortrue);
		}
		else
		{
			$usernameerror=check_username_mb($username);
			if($usernameerror>0)
			{
				$error++;
				$smarty->assign("username_error",$errortrue);
				$smarty->assign("usernamealreadyexists",$errortrue);
			}
		}
		if($password=="")
		{
			$error++;
			$smarty->assign("password_error",$errortrue);        
		}
		else
		{
			if($password==$password_re)
			{
				$smarty->assign("password_error",$errorfalse);
				$smarty->assign("password_mismatch",$errorfalse);
			}
			else
			{
				$error++;
				$smarty->assign("password_error",$errortrue);
				$smarty->assign("password_mismatch",$errortrue);
			}
		}
		
		$smarty->assign("nameofbureau",$nameofbureau);
		$smarty->assign("address",$address);
		$smarty->assign("city",$city);
		$smarty->assign("state",$state);
		$smarty->assign("country",$country);
		$smarty->assign("pin",$pin);
		$smarty->assign("tel1",$tel1);
		$smarty->assign("tel2",$tel2);
		$smarty->assign("fax",$fax);
		$smarty->assign("email",$email);
		$smarty->assign("c_name",$c_name);
		$smarty->assign("c_designation",$c_designation);
		$smarty->assign("c_mob",$c_mob);
		$smarty->assign("c_tel",$c_tel);
		$smarty->assign("p_name",$p_name);
		$smarty->assign("p_mob",$p_mob);
		$smarty->assign("p_tel",$p_tel);
		$smarty->assign("p_address",$p_address);
		$smarty->assign("p_city",$p_city);
		$smarty->assign("p_state",$p_state);
		$smarty->assign("p_country",$p_country);
		$smarty->assign("p_pin",$p_pin);
		$smarty->assign("freeorpaid",$freeorpaid);
		$smarty->assign("payeesameascontact",$payeesameascontact);
		$smarty->assign("membershipcharges",$membershipcharges);
		//$smarty->assign("membershipchargeslater",$membershipchargeslater);
		$smarty->assign("cpp",$cpp);
		$smarty->assign("memdetails",$memdetails);
		$smarty->assign("community_interested_in",$community_interested_in);
		$smarty->assign("username",$username);
		if($error>0)
		{
			if($error<2)
			{
				$smarty->assign("worderror","error");
				$smarty->assign("worderror1","was");
			}
			else
			{
				$smarty->assign("worderror","errors");
				$smarty->assign("worderror1","were");
			}
			$smarty->assign("numberoferrors",$error);
			$smarty->assign("error",$errortrue);
			$smarty->display('inputprofile_1.htm');
		}
		else
		{
			if(!$cpp)
				$cpp=20;
			$sql="INSERT INTO BUREAU_PROFILE(PROFILEID,NAME,ADDRESS,CITY,STATE,COUNTRY,PIN,TELEPHONE1,TELEPHONE2,FAX,EMAIL,CONTACT_NAME,CONTACT_DESIGNATION,CONTACT_PHONE,CONTACT_MOB,PAYEE_SAME_AS_CONTACT,PAYEE_NAME,PAYEE_PHONE,PAYEE_MOB,PAYEE_ADDRESS,PAYEE_CITY,PAYEE_STATE,PAYEE_COUNTRY,PAYEE_PIN,BUREAU_MEM_PAID,MEM_CHARGES,MEM_CHARGES_LATER,MEM_DETAILS,COMMUNITY_INTERESTED_IN,USERNAME,PASSWORD,ENTERED_ON,ACTIVATED,CPP,CPP_TIME) VALUES('$profileid','$nameofbureau','$address','$city','$state','$country','$pin','$tel1','$tel2','$fax','$email','$c_name','$c_designation','$c_tel','$c_mob','$payeesameascontact','$p_name','$p_tel','$p_mob','$p_address','$p_city','$p_state','$p_country','$p_pin','$freeorpaid','$membershipcharges','$membershipchargeslater','$memdetails','$community_interested_in','$username','$password',now(),'Y','$cpp',now())";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
			$id=mysql_insert_id_js();
			$checksum=md5($id) . "i" . $id;
			$data=loginmb($username,$password);
			$source=generate_source4MB($id);
			mysql_select_db_js('MIS');
                        $groupname="marriage_bureau";
                        $sql="INSERT INTO MIS.SOURCE(SourceID,SourceName,GROUPNAME,ACTIVE) VALUES('$source','$source','$groupname','Y')";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        mysql_select_db_js('newjs');
				
			$smarty->assign("mbchecksum",$data["CHECKSUM"]);
			$smarty->assign("formail","1");
			$smarty->assign("password",$password);
			$msg=$smarty->fetch('inputprofile_1.htm');
			//echo $msg;
			sendmailto_email($email,$msg);
			$smarty->assign("linkforpayment","1");
			$smarty->assign("bureauprofileid",$id);
			$smarty->display('inputprofile_1.htm');
		}
	}
	else
	{
		$errortrue=0;
		$smarty->assign("error",$errortrue);
		$smarty->display('inputprofile_1.htm');
	}
}
else
{
	$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates";
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
function sendmailto_email($email,$msg)
{
	include('../profile/comfunc.inc');
	if($email)
	        send_email($email,$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
	send_email("anshul@jeevansathi.com",$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
}
if($zipIt)
	ob_end_flush();
?>
