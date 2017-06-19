<?
/*******************************************************************************************
Filename     :  voucher_client.php
Description  :  Display no: of vouchers downloaded/emailed clientwise
Created On   :  27 August 2007
Created By   :  Sadaf Alam
********************************************************************************************/

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();
$data=authenticated($checksum);

if(isset($data)||$JSIndicator)
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
                $month=$todMonth;                $year=$todYear;
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
		$sql="SELECT CLIENTID,CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE TYPE='E'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$clientid=$row["CLIENTID"];
			$clientArray[$clientid]=$row["CLIENT_NAME"];
		}
		$sql="SELECT NUM_DOWN,NUM_MAIL,CLIENTID,DAY(ENTRY_DATE) AS DAY FROM MIS.VOUCHER_CLIENT_NO WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$day=$row["DAY"];
			$clientid=$row["CLIENTID"];
			$table["DOWN"][$day][$clientid]=$row["NUM_DOWN"];
			$table["EMAIL"][$day][$clientid]=$row["NUM_MAIL"];
			$table["CLAIM"][$day][$clientid]=$row["NUM_DOWN"]+$row["NUM_MAIL"];
			$table["DOWNTOTAL"][$clientid]+=$row["NUM_DOWN"];
			$table["EMAILTOTAL"][$clientid]+=$row["NUM_MAIL"];
			$table["CLAIMTOTAL"][$clientid]+=$row["NUM_DOWN"]+$row["NUM_MAIL"];
			$table["DOWNDAYTOT"][$day]+=$row["NUM_DOWN"];
			$table["EMAILDAYTOT"][$day]+=$row["NUM_MAIL"];
			$table["CLAIMDAYTOT"][$day]+=$row["NUM_MAIL"]+$row["NUM_DOWN"];
			$table["DOWNGRANDTOT"]+=$row["NUM_DOWN"];
			$table["EMAILGRANDTOT"]+=$row["NUM_MAIL"];
			$table["CLAIMGRANDTOT"]+=$row["NUM_MAIL"]+$row["NUM_DOWN"];	
		}

		$smarty->assign("table",$table);
		$smarty->assign("clientArray",$clientArray);
		$smarty->assign("monthDays",$monthDays);
		$smarty->assign("searchFlag","1");
		$smarty->assign("searchMonth",$searchMonth);
		$smarty->assign("searchYear",$searchYear);
		$smarty->assign("monthDaysArray",$monthDaysArray);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("voucher_client.htm");
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
                $smarty->display("voucher_client.htm");

	}
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
