<?php
/**Script written by Aman Sharma for Qc-Screening Module**/
include("connect.inc");
$dbmis=connect_slave();
$db=connect_db();

if(authenticated($checksum))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$end_date=$year2."-".$month2."-".$day2." 23:59:59";
		$date1=$day."-".$month."-".$year;	
		$date2=$day2."-".$month2."-".$year2;	
		$smarty->assign("date1","$date1");
		$smarty->assign("date2","$date2");
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$sql="select SCREENED_BY,COUNT(*) as cnt from jsadmin.SCREENING_LOG where SCREENED_TIME between '$st_date' and '$end_date' and ENTRY_TYPE='M' group by SCREENED_BY ";
		$res=mysql_query_decide($sql,$dbmis) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$i=0;
			do
			{
				$arr[$i]['screened_by']=$row['SCREENED_BY'];
				$arr[$i]['cnt']=$row['cnt'];
				$screener=$row['SCREENED_BY'];
				$sql1="select COUNT(*) as ungraded from jsadmin.SCREENING_LOG where SCREENED_BY='$screener' and SCREENED_TIME between '$st_date' and '$end_date' and ENTRY_TYPE='M' and GRADED<>'Y'";
		                $res1=mysql_query_decide($sql1,$dbmis) or die("$sql1".mysql_error_js());
				$row1=mysql_fetch_array($res1);
				$arr[$i]['not_graded']=$row1['ungraded'];
				$i++;
			}
			while($row=mysql_fetch_array($res));
		}
		$smarty->assign("arr", $arr);
		$smarty->assign("checksum",$checksum);
		$smarty->display("qc_screening.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
		for($i=2005;$i<=date("Y");$i++)
                {
                        $yyarr[$i-2005]=$i;
                }
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
		$dt_arr=explode("-",date("Y-m-d"));
		$smarty->assign("dd",$dt_arr[2]);
		$smarty->assign("mm",$dt_arr[1]);
		$smarty->assign("yy",$dt_arr[0]);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("checksum",$checksum);
                $smarty->display("qc_screening.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
