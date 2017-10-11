<?php

/************************************************************************************************************************
*    FILENAME           : confirmation_popup.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : Display confirmation in a popup,When a non-login user contact/bookmark (multiple) user and then log-in.
*    CREATED            : For Revamp
*    CREATED By         : Lavesh Rawat
***********************************************************************************************************************/

	
include("connect.inc");
include("contact.inc");

connect_db();

$data=authenticated($checksum);
$smarty->assign("USERNAME",$data['USERNAME']);

if($action=='C')
{
	if($nmessage_profileid)
	{
		$nmessage1=explode(',',$nmessage_profileid);
		for($i=0;$i<count($nmessage1);$i++)
		{
			$profilechecksum=md5($nmessage1[$i])."i".$nmessage1[$i];
			$receivers_with_history=get_name($nmessage1[$i]);
			$nmessage.="<a  href=\"#\" onClick=\"MM_openProfileWindow('/profile/viewprofile.php?profilechecksum=$profilechecksum','','')\">$receivers_with_history</a></strong>".' , ';
			$ncount++;
			
		}
		$nmessage=rtrim($nmessage,' , ');

		$smarty->assign("unfavourable_response",'Y');
		if($ncount==1)
			$smarty->assign('error_msg',"$error_msg");

		$smarty->assign("nmessage","Can not initiate contact with $nmessage");
	}
	if(trim($ymessage))
	{
		$smarty->assign("favourable_response",'Y');
		$smarty->assign("ymessage","$ymessage Profiles have been successfully contacted by you");
	}	
}
else
{
	$smarty->assign("favourable_response",'Y');
	$smarty->assign("ymessage","$ymessage");
}

$smarty->assign("LOGIN_INDIRECT",1);
$smarty->display("confirmation_popup.htm");
		
?>
