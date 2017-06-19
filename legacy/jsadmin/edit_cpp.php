<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

if($_SERVER["SERVER_ADDR"]=="192.168.2.220")
{
        $smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates";
        $smarty->compile_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates_c";
}
$data=authenticated($cid);

$smarty->assign("DUP",stripslashes($dup));

if(isset($data))
{
	$user=getuser($cid);
	if($submit)
	{
		@mysql_select_db_js("marriage_bureau");
		$sql_u="UPDATE marriage_bureau.BUREAU_PROFILE SET CPP='$new_cpp' WHERE PROFILEID='$bureauprofileid'";
		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_query_decide());
		
		$sql_i="INSERT INTO marriage_bureau.CPP_UPDATE_LOG(PROFILEID,CPP_OLD,CPP_NEW,REASON,CHANGEDBY,ENTRY_DT) VALUES('$bureauprofileid','$old_cpp','$new_cpp','".addslashes(stripslashes($reason))."','$user',now())";
                mysql_query_decide($sql_i) or die(mysql_error_js());

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("flag","saved");
		$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
		$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));

		$smarty->display("edit_cpp.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("bureauprofileid",$bureauprofileid);
		$smarty->assign("old_cpp",$old_cpp);
		$smarty->display("edit_cpp.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
//        $smarty->assign("username","$username");
        $smarty->display("jsconnectError.tpl");
}
?>
