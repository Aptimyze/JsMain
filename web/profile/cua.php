<?php
/************************************************************************************************************************
* 	FILE NAME	:	cua.php
* 	DESCRIPTION 	: 	Get details for a new profile
* 	MODIFY DATE	: 	16 Feb, 2005
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	Ajax Based form 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//include_once("contact.inc");
include_once("connect.inc");
include_once("screening_functions.php");
$db=connect_db();
$data=authenticated($checksum);
$profileid=$data["PROFILEID"];
include('cuafunction.php');

checknewusername($newusername,$profileid);
$username_available1=valid_new_username($newusername);
$ava1=check_username($newusername);
$ava2=check_username_jprofile($newusername);
$ava3=isvalid_username($newusername);

if(check_username_email($profileid,$newusername) || check_obscene_word($newusername) || check_for_continuous_numerics($newusername,"") || check_for_intelligent_usage($newusername))
	$smarty->assign("invalid_username",1);
elseif($username_available1==1 && $ava1==0 && $ava2==0 && $ava3==0 && $passed_all_checks==0)
	$smarty->assign("username_available",1);
else
	$smarty->assign("username_available",0);

$smarty->display('cua.htm');
?>
