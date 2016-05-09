<?php
include("connect.inc");

                                                                                                 
/****************************************************************************************************************************
*       FILENAME        :       manage_templates.php
        CREATED By      :       Gaurav Arora on 12 May 2005
        INCLUDED        :       connect.inc
*                              functions used :authenticated
* *       DESCRIPTION     : this file is used to list all template for a particular source against a particular GENDER.
*
****************************************************************************************************************************/
	
if(authenticated($cid))
{
		if($Source=='S')  // if no source is selected
                {
                        $flag_error=1;
                        $smarty->assign("flag_error_source",1);
			$smarty->assign("flag",1);
			
                }
                else
                {	
                        $flag_error=0;
                        $smarty->assign("flag_error_source",0);
                }

		if($flag_error==1)  // if no source is selected
                {	
			$smarty->assign("source_err",1);
                        $smarty->assign("name",$user);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("flag",$flag);
                        $smarty->assign("source",create_dd($Source,"Source"));
                        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                        $smarty->display("manage_input_profile.htm");
                }

		else
		{
		 $sql = "Select * from BANNER_TEMPLATE where SOURCEID='$Source' ORDER BY ID desc";

        $result = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        $sno=1;                                                                                         
                                                                                                 
        while($myrow = mysql_fetch_array($result))
                {
                        $values[]=array("sno"=>$sno,
					"ID1"=>$myrow["ID"],
					"ID"=>$myrow["SOURCEID"],
                                        "DATE_UPLOAD"=>$myrow["DATE"],
                                        "STATUS"=>$myrow["STATUS"]  );
                        $sno++;
                }
	$smarty->assign("SOURCEID",$Source);
        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("ROWS",$values);
	$smarty->assign("cid",$cid);
        $smarty->display("manage_templates.htm");

}
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
