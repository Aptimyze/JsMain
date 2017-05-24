<?php
ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : horo_compatibilty_by_mtongue.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : MIS for displaying horoscope compatibilty count on basis on mtongue
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include('connect.inc');
$db=connect_misdb();
$db2=connect_master();
                                                                                                                             
if(authenticated($cid))
{
	if($CMDGo || $outside)
	{
		if($outside)
			list($Year,$Month) = explode("-",date("Y-m"));

	        $start_dt = $Year."-".$Month."-01 00:00:00";
                $end_dt = $Year."-".$Month."-31 23:59:59";

		$MIS_FOR = $Month." / ".$Year;
		$k=0;

		$sql_mtongue = "SELECT DISTINCT(VALUE) FROM newjs.MTONGUE";
		$res_mtongue = mysql_query_decide($sql_mtongue,$db) or die("$sql_mtongue".mysql_error_js());
		while($row_mtongue = mysql_fetch_array($res_mtongue))
		{
			$mtonguearr[] = $row_mtongue['VALUE'];
		}
		$mtongue_cnt = count ($mtonguearr);

		for ($i = 0;$i < $mtongue_cnt;$i++)
		{
			$mtongue=$mtonguearr[$i];
			$mtongue_val=label_select('MTONGUE',$mtongue);
			$mtongue_val=$mtongue_val[0];

			$sql="SELECT  DISTINCT(b.PROFILEID) FROM newjs.HOROSCOPE_COMPATIBILITY b,newjs.JPROFILE a WHERE b.DATE BETWEEN '$start_dt' AND '$end_dt' AND b.PROFILEID=a.PROFILEID AND a.MTONGUE=$mtongue";
			$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$senders.=$row['PROFILEID'].',';
			}

			if($senders)
			{
				$senders=rtrim($senders,',');
				$sql="SELECT COUNT(*) AS CNT FROM newjs.HOROSCOPE_COMPATIBILITY b,newjs.JPROFILE a WHERE b.PROFILEID in ($senders) AND MTONGUE= $mtongue AND b.PROFILEID_OTHER=a.PROFILEID AND DATE BETWEEN '$start_dt' AND '$end_dt'";
				$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$final_array[$k][0]=$row["CNT"];
					$total_array[0]+=$row["CNT"];
				}

				$sql="SELECT COUNT(*) AS CNT FROM newjs.HOROSCOPE_COMPATIBILITY b,newjs.JPROFILE a WHERE b.PROFILEID in ($senders) AND MTONGUE<>$mtongue AND b.PROFILEID_OTHER=a.PROFILEID AND DATE BETWEEN '$start_dt' AND '$end_dt'";
				$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$final_array[$k][1]=$row["CNT"];
					$total_array[1]+=$row["CNT"];
				}
			
				$used_mtongue[$k]=$mtongue_val;
				$k++;
			}
			unset($senders);
		}

		$ddarr=array("SAME MTONGUE COUNT","DIFFERENT MTONGUE COUNT");
		$smarty->assign("used_mtongue",$used_mtongue);
		$smarty->assign("total_array",$total_array);
		$smarty->assign("final_array",$final_array);
		$smarty->assign("RESULT",1);
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
															     
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2007;
		}
	}

	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("cid",$cid);
	$smarty->assign("horoscope_request",$horoscope_request);
	$smarty->assign("horoscope_req_total",$horoscope_req_total);
	$smarty->display("horo_compatibilty_by_mtongue.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

?>
