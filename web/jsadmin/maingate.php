<?php
/*********************************************************************************************
* FILE NAME     : maingate.php
* DESCRIPTION   : Displays maingate.html
* CREATION DATE : 22 June, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");

$data = $cid;

if(authenticated($data))
{
        $prvl_list=getprivilage($data);
        $prvl_arr=explode("+",$prvl_list);
                                                                                                                            
        if(in_array("BS",$prvl_arr))
	{
		$smarty->assign("cid",$cid);
		$smarty->display("maingate.html");
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
