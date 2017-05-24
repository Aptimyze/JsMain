<?php

/************************************************************************************************************************
*    FILENAME           : showprivilege.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : displays the list of all the privileges
*    CREATED BY         : shobha 
***********************************************************************************************************************/


include ("connect.inc");

if (authenticated($cid))
{     
	$i=0;
	$sql  = "select * from jsadmin.PRIVILAGE" ;
	$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($result))
	{
		$priv[$i]["ID"]=$row['ID'];
		$priv[$i]["VALUE"]=$row['VALUE'];
		$priv[$i]["LABEL"]=$row['LABEL'];
		$priv[$i]["ACTIVE"]=$row['ACTIVE'];
	
		$i++;
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("priv",$priv);
	$smarty->display("showprivilege.htm");
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
