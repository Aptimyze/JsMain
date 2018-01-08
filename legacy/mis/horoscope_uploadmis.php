<?php

ini_set("max_execution_time","0");
/************************************************************************************************************************
*    FILENAME           : horoscope_uploadmis.php
*    INCLUDED           : connect.inc 
*    DESCRIPTION        : MIS for displaying upload horoscope count by mtongue.
*    CREATED BY         : lavesh
***********************************************************************************************************************/

include('connect.inc');

$db = connect_misdb();
$db2 = connect_master();
													     
if(authenticated($cid))													     
{
	if($submit || $outside)
	{
		if($outside)                         
		{
			list($Year,$Month) = explode("-",date("Y-m"));
			$year_month_wise="month_wise";
		}
		$t_cnt=0;
		if($year_month_wise=="month_wise")
		{

			$start_dt = $Year."-".$Month."-01 00:00:00";
			$end_dt = $Year."-".$Month."-31 23:59:59";
			$MIS_FOR = $Month." / ".$Year;
			$smarty->assign("MIS_FOR",$MIS_FOR);
															     
			for($i=1; $i<=31;$i++)
				$ddarr[] = $i;
			$k=0;
			$sql="SELECT COUNT(*) AS CNT,MTONGUE,DAYOFMONTH(DATE) AS DAY FROM newjs.ASTRO_DETAILS b,newjs.JPROFILE a WHERE b.PROFILEID=a.PROFILEID AND b.HOROSCOPE_SCREENING='1' AND DATE BETWEEN '$start_dt' AND '$end_dt' GROUP BY MTONGUE,DAY";
			$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$doy = $row['DAY'] - 1;
				$mtongue = $row['MTONGUE'];
				
				if(is_array($mtongue_array))
				{
					if(in_array($mtongue,$mtongue_array))
						;
					else
					{
						$mtongue_array[]=$mtongue;
						$mtongue_val=label_select('MTONGUE',$mtongue);
					$mtongue_val=$mtongue_val[0];
					$mtongue_label_array[]=$mtongue_val;
					$k++;
					}
				}
				else
				{
					$mtongue_array[0]=$mtongue;
					$mtongue_val=label_select('MTONGUE',$mtongue);
					$mtongue_val=$mtongue_val[0];
					$mtongue_label_array[0]=$mtongue_val;
				}

				$upload_horoscope[$k][$doy]=$row["CNT"];
				$total[$doy]+=$row["CNT"];
				$total1[$k]+=$row["CNT"];
				$t_cnt+=$row["CNT"];
			}	
		}
		else
		{
			$start_dt = $Year."-"."01-01 00:00:00";
			$end_dt = $Year."-"."12-31 23:59:59";
			$smarty->assign("MIS_FOR",$Year);
			$ddarr = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		
			$k=0;
			$sql="SELECT COUNT(*) AS CNT,MTONGUE,MONTH(DATE) AS MONTH FROM newjs.ASTRO_DETAILS b,newjs.JPROFILE a WHERE b.PROFILEID=a.PROFILEID AND b.HOROSCOPE_SCREENING='1' AND DATE BETWEEN '$start_dt' AND '$end_dt' GROUP BY MTONGUE,MONTH";
			$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$moy = $row['MONTH'] - 1;
				$mtongue = $row['MTONGUE'];
				
				if(is_array($mtongue_array))
				{
					if(in_array($mtongue,$mtongue_array))
						;
					else
					{
						$mtongue_array[]=$mtongue;
						$mtongue_val=label_select('MTONGUE',$mtongue);
					$mtongue_val=$mtongue_val[0];
					$mtongue_label_array[]=$mtongue_val;
					$k++;
					}
				}
				else
				{
					$mtongue_array[0]=$mtongue;
					$mtongue_val=label_select('MTONGUE',$mtongue);
					$mtongue_val=$mtongue_val[0];
					$mtongue_label_array[0]=$mtongue_val;
				}

				$upload_horoscope[$k][$moy]=$row["CNT"];
				$total[$moy]+=$row["CNT"];
				$total1[$k]+=$row["CNT"];
				$t_cnt+=$row["CNT"];
			}
		}
		$smarty->assign("total",$total);
		$smarty->assign("total1",$total1);
		$smarty->assign("t_cnt",$t_cnt);
		$smarty->assign("RESULT",1);
		$smarty->assign("mtongue_array",$mtongue_array);
		$smarty->assign("mtongue_label_array",$mtongue_label_array);
		$smarty->assign("upload_horoscope",$upload_horoscope);
	}
	else
	{
		$mmarr = array(
				array("NAME" => "Jan", "VALUE" => "01"),
				array("NAME" => "Feb", "VALUE" => "02"),
				array("NAME" => "Mar", "VALUE" => "03"),
				array("NAME" => "Apr", "VALUE" => "04"),
				array("NAME" => "May", "VALUE" => "05"),
				array("NAME" => "Jun", "VALUE" => "06"),
				array("NAME" => "Jul", "VALUE" => "07"),
				array("NAME" => "Aug", "VALUE" => "08"),
				array("NAME" => "Sep", "VALUE" => "09"),
				array("NAME" => "Oct", "VALUE" => "10"),
				array("NAME" => "Nov", "VALUE" => "11"),
				array("NAME" => "Dec", "VALUE" => "12"),
				);
															     
		for($i=2007; $i<=2010;$i++)
			$yyarr[] = $i;

	}
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("cid",$cid);
	$smarty->display("horoscope_uploadmis.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}

?>
