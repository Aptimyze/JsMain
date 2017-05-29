<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_afl_revenue.php
* DESCRIPTION   : Displays Business Sathi Revenue page after putting Head and Left panels in place
* INCLUDES	: connect.inc
* FUNCTIONS	: authenticated():Checks whether the user is authenticated or not.
*		: TimedOut():Takes action if the user is not authenticated.
* CREATION DATE : 1 July, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
$db=connect_db();
                                                                                                                            
$data=authenticated($checksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

if(isset($data))
{
	$ID=$data["AFFILIATEID"];

	$sql_stat="SELECT STATUS FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID='$ID'";
	$res_stat=mysql_query($sql_stat) or logError(mysql_error(),$sql_stat);
	$row_stat=mysql_fetch_array($res_stat);

	if($row_stat["STATUS"]=='N')
	{
		$smarty->assign("newaf","1");
                $smarty->display("business_sathi/businesssathi_confirm.htm");
	}
	else
	{
		$sql="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID='$ID' ORDER BY ENTRY_DT DESC";
		$res=mysql_query($sql) or logError(mysql_error(),$sql);
		$row=mysql_fetch_array($res);

		$smarty->assign("pay_model",$row["PAYMENT_MODEL"]);
		$smarty->assign("click_rate",$row["CLICKS_RATE"]);
		$smarty->assign("free_rate",$row["FREE_RATE"]);
		$smarty->assign("paid_reg_model",$row["PAID_REG_MODEL"]);
		$smarty->assign("paid_1_to_50",$row["PAID_1_TO_50"]);
		$smarty->assign("paid_51_to_200",$row["PAID_51_TO_200"]);
		$smarty->assign("paid_201",$row["PAID_201"]);
		$smarty->display("business_sathi/businesssathi_afl_revenue.htm");
	}
}
else
{
        TimedOut();
}
?>
