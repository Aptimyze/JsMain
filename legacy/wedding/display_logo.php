<?php
/*********************************************************************************************
* FILE NAME     : display_logo.php
* DESCRIPTION   : Displays logo of the advertiser
* CREATION DATE : 6 September, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
$db=connect_db();

$sql="SELECT LOGO FROM wedding_classifieds.LISTINGS WHERE ADV_ID='$ADV_ID'";
$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
$row=mysql_fetch_array($res);
header("Content-type: image/jpeg");
echo $row['LOGO'];
?>
