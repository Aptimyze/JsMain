<?php
/**
*       Filename        :       outbound1.php.php
*       Created by      :       Kush 
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
//if(authenticated($cid))
if (1)
{
        $name= getname($cid);
	
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
                                                                                                 
?>

