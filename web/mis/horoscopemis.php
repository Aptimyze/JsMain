<?php
/**************************************************************************************************************************
FILE NAME	: horoscopemis.php
DESCRIPTION	: This scripts selects and displays the daily clicks and horoscope generated from various horoscope links.
CREATED BY	: Sriram Viswanathan
**************************************************************************************************************************/
include('connect.inc');
$db = connect_misdb();

$data = authenticated($cid);

if($data)
{
	if($outside)
	{
		list($Year,$Month) = explode("-",date("Y-m"));
		$submit = 1;
	}
	if($submit)
	{
		
		$start_dt = $Year."-".$Month."-01 00:00:00";
		$end_dt = $Year."-".$Month."-31 23:59:59";

		$MIS_FOR = $Month." / ".$Year;

		//if community wise data is required.
		if($community_wise)
		{
			//to display all mtongue labels at the top row of MIS.
			$mtongue_value = fill_mtongue_row();
			for($i = 0; $i < max($mtongue_value); $i++)
			{
				$MTONGUE = label_select("MTONGUE",$i+1);
				$mtongue[$i]['LABEL'] = $MTONGUE[0];
			}

			//finding community wise count of clicks on horoscope from various locations.
			$sql = "SELECT COUNT(DISTINCT PROFILEID) AS COUNT, MTONGUE, TYPE FROM MIS.ASTRO_CLICK_COUNT WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY MTONGUE,TYPE";
			$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$i = array_search($row['MTONGUE'], $mtongue_value);

				if($row['TYPE']=="L")
					$horoscope_clicks['LOGIN'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="E")
					$horoscope_clicks['EDIT'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="C")
					$horoscope_clicks['UPDATE'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="A")
					$horoscope_clicks['CREATE'][$i] = $row['COUNT'];

				$horoscope_clicks['TOTAL'][$i] = $horoscope_clicks['LOGIN'][$i] + $horoscope_clicks['EDIT'][$i] + $horoscope_clicks['UPDATE'][$i] + $horoscope_clicks['CREATE'][$i];
			}

			//finding community wise count of generated horoscope from various locations.
			$sql = "SELECT COUNT(DISTINCT PROFILEID) AS COUNT, MTONGUE, TYPE FROM MIS.ASTRO_DATA_COUNT WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY MTONGUE,TYPE";
			$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$i = array_search($row['MTONGUE'], $mtongue_value);

				if($row['TYPE']=="L")
					$horoscope_generated['LOGIN'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="E")
					$horoscope_generated['EDIT'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="C")
					$horoscope_generated['UPDATE'][$i] = $row['COUNT'];
				elseif($row['TYPE']=="A")
					$horoscope_generated['CREATE'][$i] = $row['COUNT'];

				$horoscope_generated['TOTAL'][$i] = $horoscope_generated['LOGIN'][$i] + $horoscope_generated['EDIT'][$i] + $horoscope_generated['UPDATE'][$i] + $horoscope_generated['CREATE'][$i];
			}

			//calculating percentage.
			for($i = 0; $i < max($mtongue_value); $i++)
			{
				$ddarr[] = $i;

				if($horoscope_clicks['LOGIN'][$i])
					$horoscope_percentage['LOGIN'][$i] = round((($horoscope_generated['LOGIN'][$i] / $horoscope_clicks['LOGIN'][$i]) * 100),2)."%";
				if($horoscope_clicks['CREATE'][$i])
					$horoscope_percentage['CREATE'][$i] = round((($horoscope_generated['CREATE'][$i] / $horoscope_clicks['CREATE'][$i]) * 100),2)."%";
				if($horoscope_clicks['UPDATE'][$i])
					$horoscope_percentage['UPDATE'][$i] = round((($horoscope_generated['UPDATE'][$i] / $horoscope_clicks['UPDATE'][$i]) * 100),2)."%";
				if($horoscope_clicks['EDIT'][$i])
					$horoscope_percentage['EDIT'][$i] = round((($horoscope_generated['EDIT'][$i] / $horoscope_clicks['EDIT'][$i]) * 100),2)."%";
				if($horoscope_clicks['TOTAL'][$i])
					$horoscope_percentage['TOTAL'][$i] = round((($horoscope_generated['TOTAL'][$i] / $horoscope_clicks['TOTAL'][$i]) * 100),2)."%";
			}

			//finding community wise count of users who select Add Horoscope = Yes at the time of registration.
			$sql = "SELECT COUNT(DISTINCT PROFILEID) AS COUNT, MTONGUE FROM MIS.ASTRO_COMMUNITY_WISE WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY MTONGUE";
			$res = mysql_query_decide($sql) or die($sql.mysql_query_decide($sql));
			while($row = mysql_fetch_array($res))
			{
				$i = array_search($row['MTONGUE'], $mtongue_value);
				$horoscope_show['REG_ADD_HORO'][$i] = $row['COUNT'];
			}

			$smarty->assign("mtongue",$mtongue);
			$smarty->assign("COMMUNITY_WISE",1);
		}
		//day-wise data.
		else
		{
			//finding day-wise count of horoscope clicks / generated from various locations.
			$sql = "SELECT DAYOFMONTH(ENTRY_DT) AS ENTRY_DT,CLICKS_LOGIN,CLICKS_CREATE,CLICKS_UPDATE,CLICKS_EDIT,GENERATED_LOGIN,GENERATED_CREATE,GENERATED_UPDATE,GENERATED_EDIT,REG_ADD_HORO FROM MIS.ASTRO_DAILY_COUNT WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt'";
			$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$day = $row['ENTRY_DT'] - 1;
				$horoscope_clicks['LOGIN'][$day] = $row['CLICKS_LOGIN'];
				$horoscope_clicks['CREATE'][$day] = $row['CLICKS_CREATE'];
				$horoscope_clicks['UPDATE'][$day] = $row['CLICKS_UPDATE'];
				$horoscope_clicks['EDIT'][$day] = $row['CLICKS_EDIT'];

				$horoscope_clicks['TOTAL'][$day] = $row['CLICKS_LOGIN'] + $row['CLICKS_CREATE'] + $row['CLICKS_UPDATE'] + $row['CLICKS_EDIT'];

				$horoscope_generated['LOGIN'][$day] = $row['GENERATED_LOGIN'];
				$horoscope_generated['CREATE'][$day] = $row['GENERATED_CREATE'];
				$horoscope_generated['UPDATE'][$day] = $row['GENERATED_UPDATE'];
				$horoscope_generated['EDIT'][$day] = $row['GENERATED_EDIT'];

				$horoscope_generated['TOTAL'][$day] = $row['GENERATED_LOGIN'] + $row['GENERATED_CREATE'] + $row['GENERATED_UPDATE'] + $row['GENERATED_EDIT'];
				$horoscope_show['REG_ADD_HORO'][$day] = $row['REG_ADD_HORO'];

			}

			//calculating percentage.
			for($i=1;$i<=31;$i++)
			{
				$ddarr[]=$i;

				$j = $i-1;
				if($horoscope_clicks['LOGIN'][$j])
					$horoscope_percentage['LOGIN'][$j] = round((($horoscope_generated['LOGIN'][$j] / $horoscope_clicks['LOGIN'][$j]) * 100),2)."%";
				if($horoscope_clicks['CREATE'][$j])
					$horoscope_percentage['CREATE'][$j] = round((($horoscope_generated['CREATE'][$j] / $horoscope_clicks['CREATE'][$j]) * 100),2)."%";
				if($horoscope_clicks['UPDATE'][$j])
					$horoscope_percentage['UPDATE'][$j] = round((($horoscope_generated['UPDATE'][$j] / $horoscope_clicks['UPDATE'][$j]) * 100),2)."%";
				if($horoscope_clicks['EDIT'][$j])
					$horoscope_percentage['EDIT'][$j] = round((($horoscope_generated['EDIT'][$j] / $horoscope_clicks['EDIT'][$j]) * 100),2)."%";
				if($horoscope_clicks['TOTAL'][$j])
					$horoscope_percentage['TOTAL'][$j] = round((($horoscope_generated['TOTAL'][$j] / $horoscope_clicks['TOTAL'][$j]) * 100),2)."%";
			}
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("horoscope_clicks",$horoscope_clicks);
		$smarty->assign("horoscope_generated",$horoscope_generated);
		$smarty->assign("horoscope_percentage",$horoscope_percentage);
		$smarty->assign("horoscope_show",$horoscope_show);
		$smarty->assign("MIS_FOR",$MIS_FOR);
		$smarty->assign("RESULT",1);
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

		$year = date('Y');
		for($y=2004;$y<=$year;$y++)
		{
			$yyarr[] = $y;
		}

		$smarty->assign("curmonth",date('m'));
		$smarty->assign("curyear",date('Y'));
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->display("horoscopemis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

function fill_mtongue_row()
{
	$sql = "SELECT LABEL, VALUE FROM newjs.MTONGUE WHERE 1";
	$res = mysql_query_decide($sql) or die($sql.mysql_query_decide());
	while($row = mysql_fetch_array($res))
		$mtongue_value[] = $row['VALUE'];

	return $mtongue_value;
}
?>
