<?
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
mysql_select_db_js("newjs",$db2);
mysql_select_db_js("newjs",$db);
//$db=mysql_connect("172.16.3.180","vikas","CLDLRTa9") or die(mysql_error_js($db));
//mysql_select_db_js("newjs",$db);
//$mon="3";
//$year="2007";
$data=authenticated($cid);
if(isset($data))
{

	if(!isset($range))
	{
		$sql="Select MAX(SNO) from MIS.COUNTING_PREDICTIVE";
		$res=mysql_query_decide($sql,$db) or die($sql.mysql_error_js($db));
		$row=mysql_fetch_array($res);
	
		$sql="select * from MIS.COUNTING_PREDICTIVE where SNO=$row[0]";
		$res=mysql_query_decide($sql,$db) or die($sql.mysql_error_js($db));
		$row=mysql_fetch_array($res);
		$counting=unserialize($row[1]);
			
		$template="<tr><td class='label'>SNO</td><td class='label'>PAID(NEW - OLD)</td><td class='label'>UNPAID (NEW - OLD)</td><td class='label'>TOTAL (NEW - OLD)</td></tr>";
		for($i=0;$i<601;$i=$i+10)
		{
			$j=$i+10;
			$sum_new=$counting['NEW'][$i][1]+$counting['NEW'][$i][0];

			$sum_old=$couting['OLD'][$i][1]+$counting['OLD'][$i][0];
			$template.="<tr><TD class=fieldsnew>$i-$j</td><td class=fieldsnew>".$counting['NEW'][$i][1]." - ".$counting['OLD'][$i][1]." </td><td class=fieldsnew>".$counting['NEW'][$i][0]." - ".$counting['OLD'][$i][0]." </td><td  class=fieldsnew>$sum_new - $sum_old</td></tr>";
		
		}		
		
	}
	
	$smarty->assign("template",$template);	
	$smarty->assign("action",$_SERVER['REQUEST_URI']);
	$smarty->assign("MONTH",$MONTH);
	$smarty->assign("DAY",$DAY);
	$smarty->assign("YEAR",$YEAR);	
	$smarty->assign("cid",$cid);
	$smarty->display("predictive_mis.htm");
}
else
	$smarty->display("jsconnectError.tpl");
