<?php
/*********************************************************************************************
* FILE NAME	: backend_banner.php
* DESCRIPTION	: Allows the backend people to Edit banners
* CREATION DATE	: 1 July, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{
	$sql="SELECT * FROM affiliate.BANNERS WHERE SIZE='$cat' ORDER BY TYPE";
        $res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
        while($row=mysql_fetch_array($res))
        {
                $sizes=explode("x",$row["SIZE"]);
                $dat[]=array("id"=>$row["BANNERID"]);
                unset($sizes);
        }
        $smarty->assign("dat",$dat);
	$smarty->display("all_banners.htm");
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
