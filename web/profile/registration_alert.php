<?php

/************************************************************************************************************************
*    DESCRIPTION        : Alert on the second page of the registration for the weight field. 
*    CREATED BY         : Anurag Gautam
***********************************************************************************************************************/

include("connect.inc");

if($number)
{
	$smarty->display("registration_alert1.htm");
	die;
}
else
{
	$smarty->display("registration_alert.htm");
	die;
}

?>
