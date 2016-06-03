<?php
include("connect.inc");
$db=connect_db();
$sql="UPDATE newjs.JPROFILE SET ACTIVATED='Y' WHERE PROFILEID='3163247'";
$res=mysql_query($sql);
?>
