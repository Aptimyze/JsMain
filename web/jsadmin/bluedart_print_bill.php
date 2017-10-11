<?php

include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

$db_slave = connect_slave();
$db_master = connect_db();

if(authenticated($cid))
{
	$name= getname($cid);
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	
	if($airway)
	{
		$sql="SELECT AIRWAY_NUMBER FROM billing.BLUEDART_COD_REQUEST WHERE AIRWAY_NUMBER='$airway'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_affected_rows($db_slave)==0)
		{
			$smarty->assign("wrong_airway","Y");
			$smarty->display('bluedart_print_bill.htm');
		}
		else
		{
			$smarty->assign("air",$airway);
			$smarty->display('bluedart_print_bill.htm');
			echo "<script>print_bill();</script>";
		}
	}
	else
			$smarty->display('bluedart_print_bill.htm');
	
}
else
{
	 $msg="Your session has been timed out<br>";
	 $msg .="<a href=\"index.htm\">";
	 $msg .="Login again </a>";
	 $smarty->assign("MSG",$msg);
	 $smarty->display("jsadmin_msg.tpl");
}

?>
