<?php
/*********************************************************************************************
* FILE NAME   : resources_form.php
* DESCRIPTION : Displays the form to change the status of a resource
* MODIFY DATE        : 5 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Addition of new categories
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../jsadmin/connect.inc");
if ( authenticated($cid))
{
       	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
 
	if ( $flag == "add" )
	{
		$sql = "Select * from newjs.RESOURCES_CAT ORDER BY SORTBY";
		$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());

		while($myrow = mysql_fetch_array($result))
		{
			$values[] = array(	"CAT_ID"=>$myrow["CAT_ID"],
						"CAT_NAME"=>$myrow["CAT_NAME"],
						"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
		}
		$smarty->assign("FLAG",$flag); 
		$smarty->assign("ROWS",$values);
		$smarty->assign("CID",$cid);
		$smarty->display("resources_get_details.htm");
	}
	else if( $flag == "edit")
	{
		$sql = "Select * from newjs.RESOURCES_CAT ORDER BY SORTBY";
		$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
		while($myrow = mysql_fetch_array($result))
		{
			$values[] = array(      "CAT_ID"=>$myrow["CAT_ID"],
						"CAT_NAME"=>$myrow["CAT_NAME"],
	                                        "CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
		}

		$sql = "Select * from newjs.RESOURCES_DETAILS where ID=$id";
		$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
		$myrow = mysql_fetch_array($result);     
               
		$smarty->assign("ROWS",$values);
		$smarty->assign("FLAG",$flag); 
		$smarty->assign("NAME",$myrow["CONTACT_NAME"]);
		$smarty->assign("EMAIL",$myrow["EMAIL"]);
		$smarty->assign("TITLE",$myrow["NAME"]);
		$smarty->assign("CAT_ID",$myrow["CAT_ID"]);
		$smarty->assign("LINK",$myrow["LINK"]);
		$smarty->assign("DESCR",$myrow["DESCR"]);
		$smarty->assign("PAGE",$myrow["PAGE"]);
		$smarty->assign("VISIBLE",$myrow["VISIBLE"]);
		$smarty->assign("PAGE2",$myrow["PAGE2"]);
		$smarty->assign("ID",$myrow["ID"]);  
		$smarty->assign("CID",$cid);
		$smarty->display("resources_get_details.htm");
       	}
	else
	{
		echo "Error if you have reached here !!!!";   
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
