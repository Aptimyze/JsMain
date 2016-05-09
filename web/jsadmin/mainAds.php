<?php
/*********************************************************************************************
* FILE NAME     : mainAds.php
* DESCRIPTION   : Displays mainAds.htm
* CREATION DATE : 3 September, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");

if(authenticated($cid))
{
        $prvl_list=getprivilage($cid);
        $prvl_arr=explode("+",$prvl_list);
                                                                                                                            
        if(in_array("WD",$prvl_arr))
	{
		$smarty->assign("cid",$cid);
		$smarty->display("mainAds.htm");
	}
	else
        {
                $msg="You do not have the privilage to view this data";
		$smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
        }
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
