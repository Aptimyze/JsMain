<?php

include_once("search.inc");
include_once("connect.inc");
include_once("contact.inc");
$db=connect_db();
$dt=date("Y-m-d");

	
	
	$data=authenticated();

	if(isset($data))
	{
		if($showtemp)
		{
			if($data['GENDER']=='M')
				$o_gen='She';
			else
				$o_gen='He';
			$smarty->assign("WHO",$o_gen);
			$smarty->assign("USERNAME",$other_username);
			$smarty->assign("profilechecksum",$profilechecksum);
			$smarty->display("ignore_layer.htm");
			die;
		}
		$chkprofilechecksum=explode("i",$profilechecksum);
	
		$profileid=$data['PROFILEID'];
		 //insert into IGNORE_PROFILE
		$sql_insert="REPLACE INTO IGNORE_PROFILE(PROFILEID,IGNORED_PROFILEID,DATE,UPDATED) VALUES ('$profileid','$chkprofilechecksum[1]',now(),'Y')";
		mysql_query_decide($sql_insert) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_insert,"ShowErrTemplate");
		echo 'true';
		die;
	}
	else
	{
		$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
        	$smarty->display("login_layer.htm");
	        die;
	}	
?>
