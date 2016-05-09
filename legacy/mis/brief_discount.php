<?php
include("connect.inc");

include("../billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
$db=connect_misdb();
$db2=connect_master();
mysql_select_db("billing",$db);
if(authenticated($cid))
{
	$serviceObj = new Services;
	//$sql="SELECT  pr.ENTRY_DT, pr.USERNAME,sr.NAME,if (CUR_TYPE='RS',concat('Rs ',sr.PRICE_RS_TAX),concat('$ ',sr.PRICE_DOL)) as PRICE_PAID,CUR_TYPE,DISCOUNT,DISCOUNT_TYPE,pr.DISCOUNT_REASON FROM `PURCHASES` as pr ,`SERVICES` as sr where( pr.SERVICEID=sr.SERVICEID) and  DISCOUNT>0 AND WALKIN = '$agent' and ENTRY_DT between '".urldecode($st_date)."' and '".urldecode($end_date)."' order by pr.ENTRY_DT DESC ";
	$sql="SELECT  ENTRY_DT, USERNAME,CUR_TYPE,DISCOUNT,DISCOUNT_TYPE,DISCOUNT_REASON,SERVICEID FROM `PURCHASES` where DISCOUNT>0 AND WALKIN = '$agent' and ENTRY_DT between '".urldecode($st_date)."' and '".urldecode($end_date)."' ";
	if($no_renew_discount)
                $sql.=" AND DISCOUNT_TYPE<>'1'";
        $sql.=" order by ENTRY_DT DESC ";

	$res=mysql_query_decide($sql,$db) or die("$sql - ->".mysql_error_js($db));
	$j=1;
	while($row=mysql_fetch_assoc($res))
	{
	
		$type="$";
		
		if($row['CUR_TYPE']=='RS' || $row['CUR_TYPE']=="")
			$type="Rs";
		$service_info=$serviceObj->getServicesAmount($row['SERVICEID'],$row['CUR_TYPE']);
		$service1=$serviceObj->getServiceName($row['SERVICEID']);
		$row['DISCOUNT']="$type ".$row['DISCOUNT'];

		$new=get_discount_type($row["DISCOUNT_TYPE"]);
		//$template.="<tr class='fieldsnew'><TD align=center>$j</td><td align=center>".$row["ENTRY_DT"]."</td><td align=center>".$row["USERNAME"]."</td><td align=center>".$row["NAME"]."</td><td align=center>".$row["PRICE_PAID"]."</td><td align=center>".$row["DISCOUNT"]."</td><td align=center>$new</td><td align=center>".$row['DISCOUNT_REASON']."</td></tr>";
		$template.="<tr class='fieldsnew'><TD align=center>$j</td><td align=center>".$row["ENTRY_DT"]."</td><td align=center>".$row["USERNAME"]."</td><td align=center>".$service1[$row['SERVICEID']]["NAME"]."</td><td align=center>".$service_info[$row['SERVICEID']]["PRICE"]."</td><td align=center>".$row["DISCOUNT"]."</td><td align=center>$new</td><td align=center>".$row['DISCOUNT_REASON']."</td></tr>";

		$j++;
	}
	

	$smarty->assign("template",$template);	

	$start_date=explode(" ",$st_date);
	$end_date=explode(" ",$end_date);
	list($year,$month,$day)=explode("-",$start_date[0]);
	list($year2,$month2,$day2)=explode("-",$end_date[0]);

	$smarty->assign("date1",my_format_date($day,$month,$year));
	$smarty->assign("date2",my_format_date($day2,$month2,$year2));
	$smarty->assign("cid",$cid);
	$smarty->assign("AGENT",$agent);

	$smarty->assign("cid",$cid);
	$smarty->display("brief_discount.htm");

}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
