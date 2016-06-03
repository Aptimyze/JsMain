<?php
/************************************************************************************************************************
*       DESCRIPTION             : Call popup when send message in search result is  clicked
*       CREATION DATE           : 15 Dec,2007
*       CREATED BY              : Lavesh Rawat
************************************************************************************************************************/


include_once("connect.inc");
include("payment_array.php");
$db=connect_db();

if($logged)
{
	$smarty->assign("PERSON_LOGGED_IN",1);

	if($checksum)
		$data=authenticated($checksum);

	$pid=$data["PROFILEID"];
	if($pid)
	{
		$sql="select COUNTRY_RES from newjs.JPROFILE where  activatedKey=1 and PROFILEID=$pid ";
                $res=mysql_query_decide($sql);
                $row=mysql_fetch_array($res);
                if($row["COUNTRY_RES"]!=51)
                        $nri='Y';
	}
	$smarty->assign("NRI",$nri);
}

$smarty->assign("PAY_ERISHTA",$pay_erishta);
$smarty->display("contact_popup_mem.htm");

?>

														     

