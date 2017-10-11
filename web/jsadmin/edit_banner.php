<?php
/*********************************************************************************************
* FILE NAME	: edit_banner.php
* DESCRIPTION	: Allows the backend people to Edit banners
* CREATION DATE	: 1 July, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();

$data=authenticated($cid);
$smarty->assign("cid",$cid);

$sql="SELECT SIZE FROM affiliate.BANNERS WHERE NEW_CATEGORY='Y'";
$res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
$rows=mysql_num_rows($res);
$smarty->assign("rows",$rows);
                                                                                                                            
while($myrow=mysql_fetch_array($res))
{
        $det[]=array("size"=>$myrow["SIZE"]);
}
$smarty->assign("det",$det);

if(isset($data))
{
	$smarty->display("edit_banner.htm");
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
