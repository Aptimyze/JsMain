<?php
include("connect.inc");
$db=connect_db();
$sql="update JPROFILE set CASTE=IF(CASTE=14,242,IF(CASTE=145,136,CASTE)),MTONGUE=IF(MTONGUE=8,12,IF(MTONGUE=26,31,IF(MTONGUE=11,34,MTONGUE))),HIV='N',HEIGHT=HEIGHT+5,SCREENING=SCREENING | 4063232,SCREENING = if((SCREENING & 16384 != 16384)||(SCREENING & 32768 != 32768 )||(SCREENING & 64 != 64 ),(4194239 & SCREENING),SCREENING),FAMILYINFO=concat(trim(FATHER_INFO),'\n',trim(SIBLING_INFO),'\n',trim(FAMILYINFO)),FATHER_INFO='',SIBLING_INFO='', INCOME=IF(INCOME=10,11,IF(INCOME=11,12,IF(INCOME=12,13,IF(INCOME=13,21,INCOME))))";
/*
$sql="UPDATE JPROFILE SET CASTE=242 WHERE CASTE=14";
$res=mysql_query($sql) or die("1".mysql_error());
echo "\n--------".$sql." completed..";

$sql="UPDATE JPROFILE SET MTONGUE=12 WHERE MTONGUE=8";
$res=mysql_query($sql) or die("2".mysql_error());
echo "\n--------".$sql." completed..";


$sql="UPDATE JPROFILE  SET MTONGUE=31 WHERE MTONGUE=26";
$res=mysql_query($sql) or die("3".mysql_error());
echo "\n--------".$sql." completed..";

$sql="UPDATE JPROFILE  SET MTONGUE=34 WHERE MTONGUE=11";
$res=mysql_query($sql) or die("4".mysql_error());
echo "\n--------".$sql." completed..";

$sql="UPDATE JPROFILE  SET HIV='N'";
$res=mysql_query($sql) or die("5".mysql_error());
echo "\n--------".$sql." completed..";

$sql="UPDATE TABLE JPROFILE SET HEIGHT=HEIGHT+5";
$res=mysql_query($sql) or die("6".mysql_error());
echo "\n--------".$sql." completed..";
*/

/*$sql="UPDATE newjs.JPROFILE SET CASTE = '136' WHERE CASTE ='145'";
$res=mysql_query($sql) or die("7".mysql_error());
echo "\n--------".$sql." completed..";
*/


?>
