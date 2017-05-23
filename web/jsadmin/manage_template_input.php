<?php
include("connect.inc");
                                                                                                 
/****************************************************************************************************************************
*       FILENAME        :       manage_template_input.php
        CREATED By      :       Gaurav Arora on 12 May 2005
        INCLUDED        :       connect.inc
*                              functions used :authenticated
* *       DESCRIPTION     : this file is used to add new template for a particular source against a GENDER.
*
****************************************************************************************************************************/

                                                                                                 
if(authenticated($cid))
{
		$smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                $smarty->display("manage_template_input.htm");


}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
