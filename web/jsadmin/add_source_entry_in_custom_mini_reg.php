<?php
include("connect.inc");
if(authenticated($cid))
{
	if($Submit)
	{
	foreach($_POST as $name=>$val){
		$var[$name]=mysql_real_escape_string(trim($val));
		$smarty->assign($name,$val);
		if(in_array($name,array("source", "category", "heading", "coup_img", "story")) && !$val)
			$err=1;
	}
	if(!$err){
		$db=connect_db();
		$query="INSERT IGNORE INTO MIS.MINI_REG_CUSTOMIZE values ('$var[source]','$var[heading]','$var[coup_img]','$var[category]','$var[story]')";
		mysql_query_decide($query) or die("Error in connecting db ".mysql_error());
		$smarty->assign("message","Source entry in mini_reg_customize successful");
	}
	else{
		$smarty->assign("err",$err);
		$smarty->assign("message","All fields are mandatory and should not be blank");
	foreach($_POST as $name=>$val){
		$smarty->assign($name,$val);
	}
	}
	}
	$smarty->assign("name",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$username);
	$smarty->assign("username",$username);
	$smarty->display('add_source_entry_in_custom_mini_reg.html');
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->assign("user",$username);
	$smarty->display("jsadmin_msg.tpl");
}
?>
