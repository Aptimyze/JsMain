<?php
//include("connect.inc");			commented by Shakti for JSIndicator MIS 21 December, 2005
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();

mysql_query("set session wait_timeout=10000",$db);
mysql_query("set session wait_timeout=10000",$db2);

if(authenticated($cid) || $JSIndicator==1)
{
	 if($outside)
        {
                $CMDGo='Y';
		if(!$today)					//Changed by Shakti Srivastava for JSIndicatorMIS
	                $today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }

	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		//if($month<10)
		//	$month="0".$month;

		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";

		$sql="SELECT CLICKS AS COUNT,DAYOFMONTH(DATE) as dd FROM newjs.VIEW_SIMILAR_COUNT WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$dd=$row['dd']-1;
				$cnt[$dd]=$row['COUNT'];
				$tot+=$row['COUNT'];
			}while($row=mysql_fetch_array($res));
		}
		//Added by lavesh for adding Caste Mapping MIS.
		$sql="SELECT count(*) as cnt,DAYOFMONTH(DATE) as dd1 from newjs.CASTE_MAPPING WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY dd1";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
			do
                        {
				$dd1=$row['dd1']-1;
				$cnt1[$dd1]=$row['cnt'];
				$tot1+=$row['cnt'];
			}while($row=mysql_fetch_array($res));
		}
		//Ends here

		//Added by lavesh for adding View Search Clustering Count MIS.
                $sql="SELECT count(*) as cnt2,DAYOFMONTH(DATE) as dd2 from MIS.SEARCHQUERY  WHERE SEARCH_TYPE='J' AND DATE BETWEEN '$st_date' AND '$end_date' GROUP BY dd2";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd2=$row['dd2']-1;
                                $cnt2[$dd2]=$row['cnt2'];
                                $tot2+=$row['cnt2'];
                        }while($row=mysql_fetch_array($res));
                }
                //Ends here
		

/*************************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       24 November, 2005
                        Reason          :       This was needed for stopping further execution of this script whenever
                                        :       indicator_mis.php was used to obtain data
*************************************************************************************************************************/
                if($JSIndicator==1)
                {
                        return;
                }
/**************************************End of Addition********************************************************************/

		//Added by lavesh
		$smarty->assign("cnt1",$cnt1);
                $smarty->assign("tot1",$tot1);	
		$smarty->assign("cnt2",$cnt2);
                $smarty->assign("tot2",$tot2);
		//Ends here.
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tot",$tot);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("view_similar_count.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("view_similar_count.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
