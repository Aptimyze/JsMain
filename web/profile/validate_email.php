<?php

/*****************************************************

This file verify the user who requires double opt-in 
	
******************************************************/
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","validate_email.php in USE",$msg);
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path."/profile/connect.inc");
$db = connect_db();
	
if($profileid)
{
	$sql = "SELECT EMAIL,YOURINFO,USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after sometime.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$email=$row['EMAIL'];
	$USERNAME=$row['USERNAME'];
	$about_yourself=$row['YOURINFO'];

	$sql = "INSERT IGNORE INTO newjs.DOUBLE_OPTIN (PROFILEID,EMAIL,ENTRY_DATE) VALUES ('$profileid', '$email',NOW())";
	$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after sometime.",$sql,"ShowErrTemplate");

	if(strlen(trim($about_yourself)) > 100)
	{
		$sql = "UPDATE newjs.JPROFILE SET INCOMPLETE = 'N',TIMESTAMP = NOW() WHERE PROFILEID = $profileid";
		$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after sometime.",$sql,"ShowErrTemplate");
	}
	else
	{
		$sql = "UPDATE newjs.JPROFILE SET INCOMPLETE = 'Y',TIMESTAMP = NOW() WHERE PROFILEID = $profileid";
		$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after sometime.",$sql,"ShowErrTemplate");

	}

}

$smarty->assign("var_in","0");
$smarty->assign("LOGOUT","1");
$smarty->assign("CURRENTUSERNAME","");
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("head_tab",'my jeevansathi');
$smarty->assign("email_verify","1");
$smarty->assign("USERNAME","$USERNAME");
$smarty->display("logout_1.htm");

?>
