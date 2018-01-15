<?php
/*********************************************************************************************************************
Filename    : view_similar_logic.php
Description : Track count of old/new logic used for View similar profiles from confirmation page/search tables [2105]
Created By  : Sadaf Alam
Created On  : 15 December 2007
**********************************************************************************************************************/

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid) || $JSIndicator)
{
	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
        if($outside)
        {
                $CMDGo='Y';
                $searchType='ALL';
                $searchMonth=$todMonth;
                $searchYear=$todYear;
                $monthDays=$todDay-1;
        }
	if($CMDGo)
	{
		if($monthDays==0)
                {
                if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')
                   ||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
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
		if(!$searchMonth)
		$searchMonth=$monthEntered;
		if(!$searchYear)
		$searchYear=$yearEntered;

		$startDate=$searchYear."-".$searchMonth."-01";
		$endDate=$searchYear."-".$searchMonth."-".$monthDays;
		
		$sql="SELECT COUNT(*) AS CNT, DAY(DATE) AS DAY, SEARCH_TYPE FROM MIS.SIMILLAR_CONTACT_COUNT WHERE DATE BETWEEN '$startDate' AND '$endDate' GROUP BY SEARCH_TYPE, DAY(DATE)";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($res))
		{
			$searchType=$row["SEARCH_TYPE"];
			$day=$row["DAY"];
			$cnt=$row["CNT"];
			$table[$searchType][$day]=$cnt;
			$table[$searchType]["monthtotal"]+=$cnt;
			$table[$day]["daytotal"]+=$cnt;
			$table["grandtotal"]+=$cnt;
		}
		$searchTypeArray=array("CO"=>"Confirmation Page (Old Logic)",
					"CN"=>"Confirmation Page (New Logic)",
					"VO"=>"Search Page (Old Logic)",
					"VN"=>"Search Page (New Logic)");
		$smarty->assign("searchTypeArray",$searchTypeArray);
		$smarty->assign("table",$table);
		$smarty->assign("monthDaysArray",$monthDaysArray);
		$smarty->assign("searchMonth",$searchMonth);
		$smarty->assign("searchYear",$searchYear);
		$smarty->assign("search",1);
		$smarty->assign("cid",$cid);
		$smarty->display("view_similar_logic.htm");		
	}
	else
	{
		$k=0;
                while($k<=5)
                {
                        $yearArray[]=$todYear+$k;
                        $k++;
                }
                $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
                $smarty->assign('yearArray',$yearArray);
                $smarty->assign('monthArray',$monthArray);
                $smarty->assign('todYear',$todYear);
                $smarty->assign('todMonth',$todMonth);
                $smarty->assign("cid",$cid);
                $smarty->display("view_similar_logic.htm");

	}
}


?>
