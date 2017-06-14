<?php

include("connect.inc");

if(authenticated($cid))
{
	if($clearlist="YES")
	{
		//$ddl=connect_ddl();
		$ddl=connect_db();
		$sql = "TRUNCATE CHECK_MAILID";
    $result = mysql_query_decide($sql,$ddl) or die();
    mysql_close($ddl); 
	}
	else
	{
		$user = getuser($cid);
		$IdList = rtrim($IdList) ;
		if($IdList != "")
		{
			$list_comma_separated = "( " ;
			$list_comma_separated .= str_replace(" ",",",$IdList);
			$list_comma_separated .= " )";
				
			$sql = "INSERT into CHECK_MAILID_LOG(PROFILEID,USERNAME,MARKED_TIME,USER) Select PROFILEID,USERNAME,now(),'$user' from CHECK_MAILID where PROFILEID in $list_comma_separated";
			$result1 = mysql_query_decide($sql,$db) or die(mysql_error_js());        

			$sql = "DELETE from CHECK_MAILID where PROFILEID in $list_comma_separated";
			$result = mysql_query_decide($sql,$db) or die();
		}
	}
	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("mailid_marked.htm");
	

}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

?>
