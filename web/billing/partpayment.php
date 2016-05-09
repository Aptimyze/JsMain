<?php
include("../jsadmin/connect.inc");
if(authenticated($cid))
{
	echo "This module will come shortly";
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("../billing_msg.tpl");
                                                                                                 
}
?>
                                                                                                 

