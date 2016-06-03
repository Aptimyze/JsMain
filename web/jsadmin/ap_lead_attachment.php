<?php

$flag_using_php5=1;
include_once("connect.inc");
include_once("ap_functions.php");
$path =$_SERVER['DOCUMENT_ROOT']."/sugarcrm/cache/upload/";

if(authenticated($cid))
{
	$profileid 	=$_GET['PROFILEID'];
	$name 		=getname($cid);
	if($profileid && $name){
		//$profileid ="e3215680-bf48-c65e-5caa-4a644f58fcad";
		$leadData_arr= leadDetails($profileid);
		$file_id =$leadData_arr['file_id'];	
		if($file_id){
			$filename ="$path"."$file_id";
			$filename = escapeshellcmd($filename);
			system("lpr $filename");
			echo "success";
		}
		die;
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
	die;
}

?>
