<?php
chdir(dirname(__FILE__));
include("../includes/bms_connections.php");
/*
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM bms2.BANNERHEAP";
$res = mysql_query($sql) or mail("shobha.kumari@jeevansathi.com","error in bms_heapbackup",mysql_error($dbbms).$sql);

$csql = "Select FOUND_ROWS()";
$cres = mysql_query($csql) or mail("shobha.kumari@jeevansathi.com","error in bms_heapbackup",mysql_error($dbbms).$csql);
$crow = mysql_fetch_row($cres);

if($crow[0]!= 0)
{
*/
	$trunc_tbl = "truncate table bms2.BANNERHEAPCOPY";
	mysql_query($trunc_tbl,$dbbms) or mail("shobha.kumari@jeevansathi.com","error in truncating BANNERHEAPCOPY  bms_heapbackup",mysql_error($dbbms).$trunc_tbl);
	//$sql = "insert into bms2.BANNERHEAPCOPY(BannerId,BannerCount) select BANNERHEAP.BannerId,BANNERHEAP.BannerCount  from bms2.BANNERHEAP";

	$sql = "insert into bms2.BANNERHEAPCOPY(BannerId,BannerServed,BannerCount) select BANNERHEAP.BannerId,BANNERHEAP.BannerServed,BANNERHEAP.BannerCount  from bms2.BANNERHEAP";
	mysql_query($sql,$dbbms) or mail("shobha.kumari@jeevansathi.com","error in populating  BANNERHEAPCOPY bms_heapbackup",mysql_error($dbbms).$sql);
/*
}
else
{
	//$sql = "insert into bms2.BANNERHEAP(BannerId,BannerCount) select BANNERHEAPCOPY.BannerId,BANNERHEAPCOPY.BannerCount  from bms2.BANNERHEAPCOPY";
	$sql = "insert into bms2.BANNERHEAP(BannerId,BannerServed,BannerCount) select BANNERHEAPCOPY.BannerId,BANNERHEAPCOPY.BannerServed , BANNERHEAPCOPY.BannerCount  from bms2.BANNERHEAPCOPY";

        mysql_query($sql,$dbbms) or mail("shobha.kumari@jeevansathi.com","error in populating BANNERHEAP bms_heapbackup",mysql_error($dbbms).$sql);
}
*/
?>
