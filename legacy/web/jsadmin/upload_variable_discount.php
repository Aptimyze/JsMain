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
					$sql="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_TEMP";
					mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js($db2));

					$sql_upload_file = "LOAD DATA LOCAL INFILE '".$_FILES['uploaded_csv']['tmp_name']."' INTO TABLE billing.VARIABLE_DISCOUNT_TEMP FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
					mysql_query_decide($sql_upload_file,$db2) or die("$sql_upload_file".mysql_error_js($db2));

					//$sql_up="UPDATE billing.VARIABLE_DISCOUNT SET ENTRY_DT=NOW() WHERE ENTRY_DT='0000-00-00'";
					//mysql_query_decide($sql_up,$db2) or die("$sql_up".mysql_error_js($db2));

					$smarty->assign("SUCCESSFUL",1);
					$cmd = PHP_BINDIR . "/php " . dirname(__FILE__) . "/populate_variable_discount.php > /dev/null &";
					passthru($cmd);
				}
			}
		}
		else
			$smarty->assign("UNAUTHORIZED",1);

		$smarty->assign("cid",$cid);
		$smarty->display("upload_variable_discount.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}
?>
