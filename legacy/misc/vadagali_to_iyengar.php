<?php
	include("connect.inc");
	$db = connect_737_ro();
	
	$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE='132'";
	$res = mysql_query_decide($sql) or mail_me($sql);
	while($row = mysql_fetch_array($res))
		$profileid_arr[] = $row['PROFILEID'];

	@//mysql_close($db);
	$db = connect_db();

	$profileid_str = @implode("','",$profileid_arr);

	$sql = "UPDATE newjs.JPROFILE SET CASTE='65' WHERE PROFILEID IN ('$profileid_str')";
	mysql_query_decide($sql) or mail_me($sql);
	$count = mysql_affected_rows_js();

	$msg = "Count: $count";
	$msg .= "\nProfile ID's: ".print_r($profileid_arr,true);

	mail("sriram.viswanathan@jeevansathi.com","Vadagali-Brahmin Iyengar",$msg);

	function mail_me($sql)
	{
		mail("sriram.viswanathan@jeevansathi.com","Error:Vadagali-Brahmin Iyengar",$sql.mysql_error_js());
		exit;
	}
?>
