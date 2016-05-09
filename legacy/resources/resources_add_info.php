<?php
//print_r($_POST);
//print_r($_GET);
include("../jsadmin/connect.inc");
if (authenticated($cid))
{ 
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));

	if ( $flag == "add")
	{    
		$sql = "Insert into newjs.RESOURCES_DETAILS( CAT_ID,EMAIL,CONTACT_NAME,NAME,LINK,DESCR,VISIBLE,PAGE2,PAGE,SORTBY )  select '$CatId','$Email','$Name','$Title','$Link','$Desc','t','n','-1',SORTBY+1 from newjs.RESOURCES_DETAILS order by SORTBY DESC limit 1";
		$result = mysql_query_decide($sql,$db);
		$text = "Resource successfully added";
        }
	elseif($flag == "edit")
	{
		$sql =" Update newjs.RESOURCES_DETAILS set CAT_ID = '$CatId',EMAIL='$Email',CONTACT_NAME='$Name',NAME='$Title', LINK='$Link', DESCR='$Desc', VISIBLE='$Show', PAGE2 ='$Page2',PAGE='$page',SORTBY=(select SORTBY+1 from (SELECT SORTBY FROM newjs.RESOURCES_DETAILS order by SORTBY DESC limit 1)as x) where ID='$id'";

		$result = mysql_query_decide($sql,$db);
		$text = "Resource successfully edited.";
	}    
	elseif ($flag == "delete")
	{
		$sql = "Delete from newjs.RESOURCES_DETAILS where ID=$id";
		$result = mysql_query_decide($sql,$db);
		$text = "Resource successfully deleted.";
	}
	else
	{
		die("Error if you have reached here.");
	}
	$sql ="Select count(*) as cnt from newjs.RESOURCES_DETAILS where CAT_ID ='$CatId' and PAGE ='$page'  and  VISIBLE = 'y'";
	$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
	$myrow = mysql_fetch_array($result);
	if($myrow[cnt] >30)
	{
		echo "<html><head><script>alert('No. of entries on page-$pageno exceeds more than 30.Hence,this entry can not be shown on the live page');</script></head></html>";
	}
             
	$smarty->assign("CID",$cid);  
	$smarty->assign("TEXT",$text);
	$smarty->display("resources_add_info.htm");
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
