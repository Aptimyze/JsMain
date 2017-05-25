<?php
/***************************************************************************************************************************
FILE NAME		: misc_deferral_mis.php
DESCRIPTION		: This file deferral mis for Miscellaneous Revenue.
DATE			: June 26th 2007.
CREATED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("../profile/pg/functions.php");

$db = connect_misdb();
$db2 = connect_master();
if(1)//authenticated($cid))
{
	list($current_month,$current_year) = explode("-",date('m-Y'));
	if($submit)
	{
			//collection date start and end.
			$collection_date_start = $collection_year_start."-".$collection_month_start."-01 00:00:00";
			$collection_date_end = $collection_year_end."-".$collection_month_end."-31 23:59:59";
			$collection_between = date("F,Y",mktime(0,0,0,$collection_month_start,1,$collection_year_start))." to ".date("F,Y",mktime(0,0,0,$collection_month_end,1,$collection_year_end));

			//find the details of payments made in the selected collection duration.
			$sql_collection = "SELECT rm.SALEID, rm.START_DATE, MONTH(rm.START_DATE) AS START_MONTH, rm.END_DATE,rm.TOTAL_AMT,rp.AMOUNT, MONTH(rp.ENTRY_DT) AS COLLECTION_MONTH, rp.ENTRY_DT FROM billing.REV_MASTER rm, billing.REV_PAYMENT rp WHERE rm.SALEID = rp.SALEID AND rp.ENTRY_DT BETWEEN '$collection_date_start' AND '$collection_date_end'";
			$res_collection = mysql_query_decide($sql_collection) or die($sql_collection.mysql_error_js());
			while($row_collection = mysql_fetch_array($res_collection))
			{
				$collection_month = $row_collection['COLLECTION_MONTH'];
				list($start_year,$start_month,$start_month) = explode("-",$row_collection['START_DATE']);
				$collected = $row_collection["TOTAL_AMT"];

				//total duration of service (in months).
				$total_duration = ceil((getTimeDiff($row_collection['START_DATE'],$row_collection['END_DATE']))/31);
				//duration till payment received (in months)
				$duration_before_payment = ceil((getTimeDiff($row_collection['START_DATE'],$row_collection['ENTRY_DT']))/31);

				//remaining duration(in months)
				if($total_duration > $duration_before_payment)
					$duration_after_payment = ceil((getTimeDiff($row_collection['ENTRY_DT'],$row_collection['END_DATE']))/31);
				else
					$duration_after_payment = 0;

				//calculating collection, carry forward, brought forward, assigned and revenue.
				$i = ltrim($start_month,'0');
				for($j = $start_month; $j < $start_month + $total_duration; $j++)
				{
					if($i > 12)
					{
						$start_year++;
						$i = 1;
					}

					if($collection_month == $i)
					{
						$collection[$start_year][$i] += $collected;
						if($duration_before_payment)
							$assign = (($collected / $total_duration) * $duration_before_payment);
						else
							$assign = ($collected / $total_duration);

						$carry_forward[$start_year][$i] += ($collected - $assign);
						$brought_forward[$start_year][$i] += 0;
					}
					else
					{
						$collection[$start_year][$i] += 0;
						$carry_forward[$start_year][$i] += 0;
						if($collection_month < $i)
						{
							$assign = (($collected / $total_duration));
						}
						if($j > $collection_month)
							$brought_forward[$start_year][$i] += ($collected / $total_duration);
					}

					$carry_forward[$start_year][$i] = round($carry_forward[$start_year][$i],2);

					$assigned[$start_year][$i] += $assign;
					$assigned[$start_year][$i] = round($assigned[$start_year][$i],2);

					$brought_forward[$start_year][$i] = round($brought_forward[$start_year][$i],2);
					$revenue[$start_year][$i] = $assigned[$start_year][$i];//no need

					$i++;
				}
			}

			$deferral_date_start = $deferral_year_start."-".$deferral_month_start."-01";
			$deferral_date_end = $deferral_year_end."-".$deferral_month_end."-31";
			$deferral_between = date("F,Y",mktime(0,0,0,$deferral_month_start,1,$deferral_year_start))." to ".date("F,Y",mktime(0,0,0,$deferral_month_end,1,$deferral_year_end));

			$deferral_duration = ceil((getTimeDiff($deferral_date_start,$deferral_date_end)/31));
			$smarty->assign("deferral_duration",$deferral_duration);
			$smarty->assign("deferral_month_start",$deferral_month_start);

			$deferral_year_loop = $deferral_year_end + 1;
			$smarty->assign("deferral_year_loop",$deferral_year_loop);
			$smarty->assign("deferral_year_start",$deferral_year_start);

			$smarty->assign("collection",$collection);
			$smarty->assign("carry_forward",$carry_forward);
			$smarty->assign("brought_forward",$brought_forward);
			$smarty->assign("assigned",$assigned);
			$smarty->assign("revenue",$revenue);
			$smarty->assign("collection_between",$collection_between);
			$smarty->assign("deferral_between",$deferral_between);
			$smarty->assign("RESULT",1);
/*print_r($collection)."<br>";
print_r($carry_forward)."<br>";
print_r($brought_forward)."<br>";
print_r($assigned)."<br>";
die;*/
		}
		else
		{
			//month array
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

			//year array
			for($i=2006; $i <= date('Y')+1; $i++)
				$yyarr[] = $i;

			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);
			$smarty->assign("current_month",$current_month);
			$smarty->assign("current_year",$current_year);
		}
		$smarty->assign("cid",$cid);
		$smarty->display("misc_deferral_mis.htm");
	}
	//if user timed out or user not authenticated.
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}
?>
