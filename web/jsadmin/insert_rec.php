<?php
die("<center>This Link has been disabled.</center>");
include("connect.inc");

if($gender && $age && $caste && !checkemail($email))
{
	$sql = "INSERT IGNORE INTO MAILER (GENDER, AGE, CASTE, EMAIL) values ('$gender', '$age', '$caste', '$email')";
	$res = mysql_query_decide($sql) or die("maraa");

	$msg = "1 Record Inserted";
}
else
{
	$smarty->assign("GENDER",$gender);
	$smarty->assign("AGE",$age);
	$smarty->assign("CASTE",$caste);
	$smarty->assign("EMAIL",$email);

	if($gender || $age || $caste || !checkemail($email) || $submit)
		$msg = "Please fill all values";
}

	$sql_caste = "select SQL_CACHE VALUE, LABEL from CASTE ";
	$res_caste = mysql_query_decide($sql_caste);
	while($myrow_caste = mysql_fetch_array($res_caste))
	{
		$values[] = array("VALUE"=>$myrow_caste["VALUE"],
				  "LABEL"=>$myrow_caste["LABEL"]);
	}

	$smarty->assign("ROWS",$values);

	$smarty->assign("MSG",$msg);
	$smarty->display("alk_inputrec.tpl");
?>
