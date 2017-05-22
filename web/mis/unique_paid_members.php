<?
include("connect.inc");

//$db=connect_ddl();
$db=connect_db();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("qtr_name",$qtr_name);

		$i=0;

		if($qtr_name == 1){
			$date1 = $year.'-04-01';
			$date2 = $year.'-06-30';
		}else if($qtr_name == 2){
			$date1 = $year.'-07-01';
			$date2 = $year.'-09-30';
		}else if($qtr_name == 3){
			$date1 = $year.'-10-01';
			$date2 = $year.'-12-31';
		}else if($qtr_name == 4){
			$date1 = ($year+1).'-01-01';
			$date2 = ($year+1).'-03-31';
		}else if($qtr_name == 'All'){
			$date1 = $year.'-04-01';
                        $date2 = ($year+1).'-03-31';
		}

		$sql="CREATE TEMPORARY TABLE billing.UNIK_PAID(profileid int(11) NOT NULL default '0', UNIQUE KEY profileid(profileid)) ENGINE=MyISAM ";
		$res=mysql_query_decide($sql) or die(mysql_error_js());

		$sql2="INSERT IGNORE INTO billing.UNIK_PAID select PROFILEID from billing.PURCHASES where ENTRY_DT BETWEEN '$date1 00:00:00' and '$date2 23:59:59' and STATUS = 'DONE'";
		$res2=mysql_query_decide($sql2) or die("Not able to Insert unique Payee with reason ".mysql_error_js());

		$sql3="SELECT COUNT(*) NUM from billing.UNIK_PAID WHERE 1";
		$res3=mysql_query_decide($sql3) or die("Can not select unique payee count with reason ".mysql_error_js());
		$myrow=mysql_fetch_array($res3);

		//echo "Unique Payee in Between $date1 and $date2 is : ".$myrow["NUM"];
		$smarty->assign("qtr_cnt",$myrow["NUM"]);
		$smarty->assign("arr",$arr);
		$smarty->assign("tot_rs",$tot_rs);
		$smarty->assign("tot_dol",$tot_dol);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag","1");
		//$smarty->display("unique_paid_members.htm");
	}

	for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;

	if($year)
		$smarty->assign("yy",$year);
	else
		$smarty->assign("yy",Date('Y'));

	$smarty->assign("cid",$cid);
	$smarty->assign("yyarr",$yyarr);

	$smarty->display("unique_paid_members.htm");

}
else
{
	$smarty->display("jsconnectError.tpl");
}

?>
