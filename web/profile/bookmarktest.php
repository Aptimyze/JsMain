<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include("$path/profile/connect.inc");

$db=connect_db();

$data=authenticated($bookmarker);
$bkmarker=$data["PROFILEID"];
$bkdate=date("Y-m-d");
$text=addslashes($text);
if($edit)
{
	if($csbookmarkee)
	list($temp,$bookmarkee)=explode("i",$csbookmarkee);		
	$sql="UPDATE newjs.BOOKMARKS SET BKNOTE='$text',BKDATE='$bkdate' WHERE BOOKMARKER='$bkmarker' AND BOOKMARKEE='$bookmarkee'";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
else
{
	if($bookmarkee)
	list($temp,$bkmarkee)=explode("i",$bookmarkee);
	$sql="REPLACE INTO newjs.BOOKMARKS(BOOKMARKER,BOOKMARKEE,BKDATE,BKNOTE) VALUES('$bkmarker','$bkmarkee','$bkdate','$text')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
?>
