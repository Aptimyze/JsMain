<?php
/***********************************************************************************
Filename     :  voucher_frequency.php
Description  :  Display frequency distribution of voucher download, email (2082)
Created On   :  27 August 2007 
Created By   :  Sadaf Alam
************************************************************************************/
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
		$sql="SELECT COUNT(*) AS CNT FROM billing.VOUCHER_CLIENTS WHERE TYPE='E'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_assoc($result);
		$cnt=$row["CNT"];	
		
  		for($i=1;$i<=$cnt;$i++)
		$cntArray[]=$i;
		
		$sql="SELECT NUM,DOWNLOAD,EMAIL,DAY(ENTRY_DATE) AS DAY FROM MIS.VOUCHER_DOWNLOAD WHERE ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
		 	$day=$row["DAY"];
			$num=$row["NUM"];
			$table["DOWN"][$day][$num]=$row["DOWNLOAD"];
			$table["EMAIL"][$day][$num]=$row["EMAIL"];
			$table["CLAIM"][$day][$num]=$row["EMAIL"]+$row["DOWNLOAD"];
			$table["DOWNTOTAL"][$num]+=$row["DOWNLOAD"];
			$table["EMAILTOTAL"][$num]+=$row["EMAIL"];
			$table["CLAIMTOTAL"][$num]+=$row["DOWNLOAD"]+$row["EMAIL"];
			$table["DOWNDAYTOT"][$day]+=$row["DOWNLOAD"];
			$table["EMAILDAYTOT"][$day]+=$row["EMAIL"];
			$table["CLAIMDAYTOT"][$day]+=$row["DOWNLOAD"]+$row["EMAIL"];
			$table["DOWNGRANDTOT"]+=$row["DOWNLOAD"];
			$table["EMAILGRANDTOT"]+=$row["EMAIL"];
			$table["CLAIMGRANDTOT"]+=$row["EMAIL"]+$row["DOWNLOAD"];
		}
		$smarty->assign("cntArray",$cntArray);
		$smarty->assign("table",$table);
		$smarty->assign("searchFlag","1");
                $smarty->assign("searchMonth",$searchMonth);
                $smarty->assign("searchYear",$searchYear);
                $smarty->assign("monthDays",$monthDays);
                $smarty->assign("monthDaysArray",$monthDaysArray);
		$smarty->assign("CHECKSUM",$checksum);
                $smarty->display("voucher_frequency.htm");

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
		$smarty->display("voucher_frequency.htm");
	}
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
