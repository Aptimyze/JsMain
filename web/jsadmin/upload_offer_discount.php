<?php
	include_once("connect.inc");

	$db2 = connect_db();

	if(authenticated($cid))
	{
		$user = getname($cid);
		$privilage = explode("+",getprivilage($cid));

		if(in_array("IA",$privilage))
		{
			if($upload)
			{
				if(substr($_FILES['uploaded_csv']['name'],-3,3) != "csv")
					$smarty->assign("INVALID_FILE",1);
				else
				{
					$sql_upload_file = "LOAD DATA LOCAL INFILE '".$_FILES['uploaded_csv']['tmp_name']."' INTO TABLE billing.OFFER_DISCOUNT_TEMP FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
					mysql_query_decide($sql_upload_file,$db2) or die("$sql_upload_file".mysql_error_js($db2));
					$smarty->assign("SUCCESSFUL",1);
					$cmd = "/usr/bin/php -q populate_offer_discount.php";
					passthru($cmd);
				}
			}
		}
		else
			$smarty->assign("UNAUTHORIZED",1);

		$smarty->assign("cid",$cid);
		$smarty->display("upload_offer_discount.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}
?>
