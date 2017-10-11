<?php
include("connect.inc");
connect_db();
$data=authenticated($checksum);

	if($retry=='Y')
	{
		$sql="SELECT ID,SERVICEMAIN,CURTYPE,SERVEFOR,AMOUNT,DISCOUNT,GATEWAY,SET_ACTIVATE,PAYMODE,ADDON_SERVICEID FROM billing.ORDERS WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);

		$checksum=$data["CHECKSUM"];
		$id=$row['ID'];
		$gateway=$row['GATEWAY'];
		if($gateway=="CCAVENUE")
		{
			$newgateway="TRANSECUTE";
			$action_path="pg/transecute/order_transecute.php";
		}
		else
		{
			$newgateway="CCAVENUE";
			$action_path="pg/orderonline.php";
		}

		$sql="INSERT INTO billing.GATEWAY_LOG VALUES ('','$profileid',now(),'$gateway')";
		mysql_query_decide($sql);

		$sql="UPDATE billing.ORDERS SET GATEWAY='$newgateway' WHERE ID='$id'";
		mysql_query_decide($sql);

		$test_url="service_str=$service_str&service_main=$row[SERVICEMAIN]&type=$row[CURTYPE]&discount=$row[DISCOUNT]&total=$row[AMOUNT]&paymode=$row[PAYMODE]&setactivate=$row[SET_ACTIVATE]&ACTION_PATH=$action_path&checkout=true&profileid=$profileid&checksum=$checksum";
	}
	else
	{
	        foreach($_POST as $key => $value)
        	{
			$test_url.="$key=$value&";
        	}
		$test_url=substr($test_url,0,strlen($test_url)-1);
	}

	$smarty->assign("PROFILEID",$profileid);
	$smarty->assign("TEST_URL",$test_url);
	$smarty->display("frame_payment_test.htm");
?>
