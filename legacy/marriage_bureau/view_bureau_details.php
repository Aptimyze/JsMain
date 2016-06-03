<?php

/************************************************************************************************************************
* 	FILE NAME	:	view_bureau_details.php	
* 	DESCRIPTION 	: 	Display bureau details for Marriage Bureau account
* 	MODIFY DATE	: 	24th April 2006
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
		$sql="SELECT NAME,ADDRESS,CITY,STATE,COUNTRY,PIN,TELEPHONE1,TELEPHONE2,FAX,EMAIL,CONTACT_NAME,CONTACT_DESIGNATION,CONTACT_PHONE,CONTACT_MOB,BUREAU_MEM_PAID,MEM_CHARGES,MEM_CHARGES_LATER,MEM_DETAILS,COMMUNITY_INTERESTED_IN,USERNAME,PASSWORD,MONEY,CPP from marriage_bureau.BUREAU_PROFILE where USERNAME='$username'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
		$smarty->assign("nameofbureau",$row['NAME']);
                $smarty->assign("address",$row['ADDRESS']);
                $smarty->assign("city",$row['CITY']);
                $smarty->assign("state",$row['STATE']);
                $smarty->assign("country",$row['COUNTRY']);
                $smarty->assign("pin",$row['PIN']);
                $smarty->assign("tel1",$row['TELEPHONE1']);
                $smarty->assign("tel2",$row['TELEPHONE2']);
                $smarty->assign("fax",$row['FAX']);
                $smarty->assign("email",$row['EMAIL']);
                $smarty->assign("c_name",$row['CONTACT_NAME']);
                $smarty->assign("c_designation",$row['CONTACT_DESIGNATION']);
                $smarty->assign("c_mob",$row['CONTACT_MOB']);
                $smarty->assign("c_tel",$row['CONTACT_PHONE']);
		$smarty->assign("freeorpaid",$row['BUREAU_MEM_PAID']);
		$smarty->assign("membershipcharges",$row['MEM_CHARGES']);
		$smarty->assign("cpp",$row['CPP']);
		$smarty->assign("username",$row['USERNAME']);
		$smarty->assign("password",$row['PASSWORD']);
		$smarty->assign("memdetails",$memdetails);
		$smarty->assign("community_interested_in",$community_interested_in);
		$smarty->assign("formail","1");
		$smarty->display('inputprofile_1.htm');
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
if($zipIt)
	ob_end_flush();
?>
