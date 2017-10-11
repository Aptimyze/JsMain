<?php
include("../jsadmin/connect.inc");
if(authenticated($cid))
{
        $smarty->assign("CID",$cid);
        $smarty->assign("USER",$user);
        $smarty->display("login.htm");
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                 
}
?>

