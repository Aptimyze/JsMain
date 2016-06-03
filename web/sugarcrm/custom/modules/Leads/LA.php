<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
include($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");
include("custom/modules/Leads/config.php");
include("include/utils/Jsde_duplicate.php");
$db=connect_db();

$duplicateObj=new Duplicate;
if($_REQUEST['std'])
	$std=trim($_REQUEST['std']);
if($_REQUEST['phone_home'])
	$phone_home=trim($_REQUEST['phone_home']);
if($_REQUEST['phone_mobile'])
	$phone_mobile=trim($_REQUEST['phone_mobile']);
if($_REQUEST['enquirer_landline_c'])
	$enquirer_landline_c=trim($_REQUEST['enquirer_landline_c']);
if($_REQUEST['enquirer_mobile_no_c'])
	$enquirer_mobile_no_c=trim($_REQUEST['enquirer_mobile_no_c']);
if($_REQUEST['enquirer_email_id_c'])
	$enquirer_email_id_c=trim($_REQUEST['enquirer_email_id_c']);
if($_REQUEST['lead'])
{
	$lead=trim($_REQUEST['lead']);
	if($lead)
	{
		$ignoreList[]=$lead;
		$leadInfo=$duplicateObj->getLeadDetailInSugar($lead);
		/*Ignore this ajax call if any number has changed --Added by Jaiswal*/
		if($_REQUEST["edit"]){
			$fieldArr=array(
				"phone_home",
				"phone_mobile",
				"enquirer_mobile_no_c",
				"enquirer_landline_c"
			);
			foreach ($fieldArr as $field_name){
				if($$field_name){
					if($field_name=="phone_home"){
						if($std==$leadInfo["std_c"]&& $$field_name==$leadInfo[$field_name])
die;
					}
					elseif($field_name=="enquirer_landline_c"){
						if($std==$leadInfo["std_enquirer_c"]&& $$field_name==$leadInfo[$field_name])
die;
					}
					elseif($$field_name==$leadInfo[$field_name])
						die;
			}
			}
		}
		//Ignore code ends here
		if($leadInfo["PROFILEID"])
			$ignoreProfile[]=$leadInfo["PROFILEID"];
	}
}
if($phone_home || $phone_mobile || $enquirer_landline_c || $enquirer_mobile_no_c || $enquirer_email_id_c)
{
	$duplicate=0;
	if($phone_home)
		$duplicate=$duplicateObj->isDuplicatePhone($std,$phone_home,$ignoreList,$ignoreProfile);
	if($enquirer_landline_c)
		$duplicate=$duplicateObj->isDuplicatePhone($std,$enquirer_landline_c,$ignoreList,$ignoreProfile);
	if($phone_mobile)
		$duplicate=$duplicateObj->isDuplicateMobile($phone_mobile, $ignoreList,$ignoreProfile);
	if($enquirer_mobile_no_c)
		$duplicate=$duplicateObj->isDuplicateMobile($enquirer_mobile_no_c,$ignoreList,$ignoreProfile);
	if($enquirer_email_id_c)
		$duplicate=$duplicateObj->isDuplicateEmail($enquirer_email_id_c,$ignoreList,$ignoreProfile);
	if($duplicate)
	{
		
$msg='<font color="red">Duplicate Record!!!</font>';
		echo $msg;
		die;
	}
	die;
}
die;
?>
