<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
include("include/utils/JsStatus_config.php");

if($_POST['id'] && $_POST['disposition'])
{
	$idString=html_entity_decode($_POST['id'],ENT_QUOTES);
	$disposition=$_POST['disposition'];
	$result=checkProfile($idString,$disposition);
	if($result)
		echo $result;
	else
		die;
}
die;
?>
