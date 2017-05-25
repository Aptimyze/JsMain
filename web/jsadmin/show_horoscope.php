<?php
/**
*       Filename        :       show_horoscope.php
*       Description     :       displays the horoscope to be screened to the operator
*       Created by      :       Gaurav
**/
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
	//print_r($_POST);
	//print_r($_GET);
	/*$smarty->assign("mainphoto","Y");
	$smarty->assign("profilephoto","Y");
	$smarty->assign("thumbnail","Y");
	$smarty->assign("albumphoto1","Y");
	$smarty->assign("albumphoto2","Y");
	*/
	//$checksum=md5($profileid+5)."i".($profileid+5);
	$profilechecksum=md5($profileid)."i".($profileid);
	//count_photos is the no. of photos to be screened
	//$smarty->assign("count_photos","5");
	$smarty->assign("username",$username);
	$smarty->assign("profileid",$profileid);
	//$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$smarty->assign("cid",$cid);

	$smarty->display("show_horoscope.htm");
}
else//user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}
?>
