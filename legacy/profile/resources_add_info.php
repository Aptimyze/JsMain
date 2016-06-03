<?php
/*********************************************************************************************
* FILE NAME   : resources.php
* DESCRIPTION : Addition of new resource submitted by the user 
* MODIFY DATE        : 5 February, 2009
* MODIFIED BY        : Ankit Aggarwal
* REASON             : Addition of new categories
* Copyright  2009, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
//print_r($_POST);
include("connect.inc");
$db = connect_db();
	if(!strstr( $Link, "http://"))
	{
		$Link = "http://".$Link;	
	}
	$sql = "Insert into newjs.RESOURCES_DETAILS( NAME,CONTACT_NAME,EMAIL,LINK,DESCR,VISIBLE,PAGE ) Values ('$Title','$Name','$Email','$Link','$Desc','t','-1')";
	$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
	$text = "Resource successfully added.";
	$from = "webmaster@jeevansathi.com";
	$headers = "From: $from";
	$to = "marketing@jeevansathi.com";
	$subject = addslashes(stripslashes($Link))." wants to list their site on Jeevansathi.com";
	 $message = "Title :".addslashes(stripslashes($Title))."\r\nNAME :".addslashes(stripslashes($Name))."\r\nEMAIL :".addslashes(stripslashes($Email))."\r\nURL :".addslashes(stripslashes($Link))."\r\nDESCRIPTION :".addslashes(stripslashes($Desc));
	//mail($to,$subject,$message,$headers);

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("Resources",1);
$smarty->display("confirmation_1.htm");
?>

