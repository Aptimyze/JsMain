<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*********************************************************************************************
* FILE NAME     : top_filter_tables.php
* DESCRIPTION   : Deletes records from newjs.TOP_SAVE_MATCHALERT which are also in JPROFILE and OLDEMAIL AND JPROFILE_AFFILIATE
* CREATION DATE : 16 Feb, 2006
* CREATEDED BY  : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd. 
*********************************************************************************************/

include_once("connect.inc");
connect_db();

$sql1="DELETE newjs.TOP_SAVE_MATCHALERT.* FROM newjs.TOP_SAVE_MATCHALERT,newjs.JPROFILE WHERE newjs.TOP_SAVE_MATCHALERT.EMAIL = newjs.JPROFILE.EMAIL";
$res1=mysql_query($sql1) or logError("Error while deleting data corresponding to JPROFILE",$sql1);

//$sql2="DELETE newjs.TOP_SAVE_MATCHALERT.* FROM newjs.TOP_SAVE_MATCHALERT,newjs.OLDEMAIL WHERE newjs.TOP_SAVE_MATCHALERT.EMAIL = newjs.OLDEMAIL.OLD_EMAIL";
//$res2=mysql_query($sql2) or logError("Error while deleting data corresponding to OLDEMAIL",$sql2);

$sql3="DELETE newjs.TOP_SAVE_MATCHALERT.* FROM newjs.TOP_SAVE_MATCHALERT,newjs.JPROFILE_AFFILIATE WHERE newjs.TOP_SAVE_MATCHALERT.EMAIL = newjs.JPROFILE_AFFILIATE.EMAIL";
$res3=mysql_query($sql3) or logError("Error while deleting data corresponding to JPROFILE_AFFILIATE",$sql3);

?>
