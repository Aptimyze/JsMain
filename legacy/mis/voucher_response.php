<?php
/**************************************************************************************************************************
Filename     :  voucher_response.php
Description  :  File to display responses in voucher delivery - Clicks, Opt In/Out/No Response, Email/Download (Issue 2082)
Created On   :  24 August 2007
Created By   :  Sadaf Alam
***************************************************************************************************************************/
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();
$data=authenticated($checksum);
if(isset($data)|| $JSIndicator)
{
	$searchMonth='';
        $searchYear='';
        $monthDays=0;
        if(!$today)
        $today=date("Y-m-d");       
	list($todYear,$todMonth,$todDay)=explode("-",$today);        
	if($outside)
        {
                $go="Y";
                $month=$todMonth;
                $year=$todYear;
                $monthDays=$todDay-1;
        }
	if($go)
	{
		$searchYear=$year;
		$searchMonth=$month;
		if(!$monthDays)
		{
			if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
				$monthDays=31;
			elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
                                $monthDays=30;
                        elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
                                $monthDays=29;
                                else
                                $monthDays=28;
		}
                $k=1;
                while($k<=$monthDays)
                {
                        $monthDaysArray[]=$k;
                        $k++;
                }
		
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' GROUP BY DAY";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$day=$row["DAY"];
			$table["paid"][$day]=$row["CNT"];
			$table["paidtotal"]+=$row["CNT"];
		}

		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE CLICK='Y' AND ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$day=$row["DAY"];
			$cnt=$row["CNT"];
			$table["clickabs"][$day]=$cnt;
			$table["clicktotalabs"]+=$cnt;
		}

		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='Y' AND RES_FROM='M' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$cnt=$row["CNT"];
			$day=$row["DAY"];
			$table["optinmabs"][$day]=$cnt;
			$table["optinmtotalabs"]+=$cnt;
			$table["optintotalabs"]+=$cnt;
			$table["optinabs"][$day]+=$cnt;
		}
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='Y' AND RES_FROM='P' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$cnt=$row["CNT"];
			$day=$row["DAY"];
			$table["optinpabs"][$day]=$cnt;
			$table["optinptotalabs"]+=$cnt;			
			$table["optinabs"][$day]+=$cnt;
			$table["optintotalabs"]+=$cnt;
		}

		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='N' AND RES_FROM='M' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_assoc($result))
                {
                        $cnt=$row["CNT"];
                        $day=$row["DAY"];
                        $table["optoutmabs"][$day]=$cnt;
                        $table["optoutmtotalabs"]+=$cnt;
                        $table["optouttotalabs"]+=$cnt;
                        $table["optoutabs"][$day]=$cnt;
                }
                $sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='N' AND RES_FROM='P' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_assoc($result))
                {
                        $cnt=$row["CNT"];
                        $day=$row["DAY"];
                        $table["optoutpabs"][$day]=$cnt;
                        $table["optoutptotalabs"]+=$cnt;
                        $table["optoutabs"][$day]+=$cnt;
                        $table["optouttotalabs"]+=$cnt;
                }
		
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='' AND VISIT_FROM='M' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_assoc($result))
                {
                        $cnt=$row["CNT"];
                        $day=$row["DAY"];
                        $table["noresmabs"][$day]=$cnt;
                        $table["noresmtotalabs"]+=$cnt;
                        $table["norestotalabs"]+=$cnt;
                        $table["noresabs"][$day]+=$cnt;
                }
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND VIEWED='' AND VISIT_FROM='P' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_assoc($result))
                {
                        $cnt=$row["CNT"];
                        $day=$row["DAY"];
                        $table["norespabs"][$day]=$cnt;
                        $table["noresptotalabs"]+=$cnt;
                        $table["noresabs"][$day]+=$cnt;
                        $table["norestotalabs"]+=$cnt;
                }
		
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND CLAIM='D' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$day=$row["DAY"];
			$table["downloadabs"][$day]+=$row["CNT"];
			$table["downabs"][$day]+=$row["CNT"];
			$table["downloadtotalabs"]+=$row["CNT"];
			$table["downtotalabs"]+=$row["CNT"];
		}
		$sql="SELECT COUNT(*) AS CNT,DAY(ENTRY_DATE) AS DAY FROM billing.VOUCHER_VIEWED WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND CLAIM='M' GROUP BY ENTRY_DATE ORDER BY ENTRY_DATE";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$day=$row["DAY"];
			$table["emailabs"][$day]=$row["CNT"];
			$table["downabs"][$day]+=$row["CNT"];
			$table["emailtotalabs"]+=$row["CNT"];
			$table["downtotalabs"]+=$row["CNT"];
		}
	//	var_dump($table);
		$smarty->assign("table",$table);
		$smarty->assign("searchFlag","1");
		$smarty->assign("searchMonth",$searchMonth);
		$smarty->assign("searchYear",$searchYear);
		$smarty->assign("monthDays",$monthDays);
		$smarty->assign("monthDaysArray",$monthDaysArray);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("voucher_response.htm");
		

	}
	else
	{                
		$k=0;
                while($k<=5)
                {
                        $yearArray[]=$todYear-$k;
                        $k++;
                }
                $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
                $smarty->assign('yearArray',$yearArray);
                $smarty->assign('monthArray',$monthArray);
                $smarty->assign('todYear',$todYear);
                $smarty->assign('todMonth',$todMonth);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("voucher_response.htm");
	}
}
else
{
	$smarty->assign('user',$user);
        $smarty->display("jsconnectError.tpl");
}
?>
