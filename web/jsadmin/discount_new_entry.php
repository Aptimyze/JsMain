<?php
	include_once("connect.inc");
	include_once("../profile/new_voucher.php");

	if(authenticated($cid))
	{
		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;
		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;
		for($i=2008;$i<=date('Y')+2;$i++)
			$yyarr[] = $i;

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);

		if($submit)
		{
			$is_error = 0;

			if(!$discount_usage)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_USAGE",1);
			}
			if(!$discount_name)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_NAME",1);
			}
			if($discount_usage == "M" && !$discount_code)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_CODE",1);
			}
			elseif($discount_usage == "S" && !$number_of_codes)
			{
				$is_error++;
				$smarty->assign("CHECK_NUMBER_OF_CODES",1);
			}

			if(!$discount_percent)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_PERCENT",1);
			}
			if(!$discount_duration_start_day || !$discount_duration_start_month || !$discount_duration_start_year || !$discount_duration_end_day || !$discount_duration_end_month || !$discount_duration_end_year)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_DURATION",1);
			}
			if(!$discount_message)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_MESSAGE",1);
			}

			if($is_error)
			{
				show_discount_codes();
				$smarty->assign("ERROR",1);
				$smarty->assign("DISCOUNT_USAGE",$discount_usage);
				$smarty->assign("discount_duration_start_day",$discount_duration_start_day);
				$smarty->assign("discount_duration_start_month",$discount_duration_start_month);
				$smarty->assign("discount_duration_start_year",$discount_duration_start_year);
				$smarty->assign("discount_duration_end_day",$discount_duration_end_day);
				$smarty->assign("discount_duration_end_month",$discount_duration_end_month);
				$smarty->assign("discount_duration_end_year",$discount_duration_end_year);
				$smarty->display("discount_new_entry.htm");
			}
			else
			{
				$entry_by = getname($cid);

				$discount_code = addslashes(stripslashes($discount_code));
				$discount_name = addslashes(stripslashes($discount_name));
				$start_date = $discount_duration_start_year."-".$discount_duration_start_month."-".$discount_duration_start_day;
				$end_date = $discount_duration_end_year."-".$discount_duration_end_month."-".$discount_duration_end_day;
				$discount_message = addslashes(stripslashes($discount_message));

				$sql_mul = "SELECT COUNT(*) AS COUNT FROM newjs.DISCOUNT_CODE_MULTIPLE WHERE CODE='".strtoupper($discount_code)."'";
				$res_mul = mysql_query_decide($sql_mul) or die($sql_mul.mysql_error_js());
				$row_mul = mysql_fetch_array($res_mul);

				$sql_sin = "SELECT COUNT(*) AS COUNT FROM newjs.DISCOUNT_CODE WHERE NAME_OF_CODE='".strtoupper($discount_name)."'";
				$res_sin = mysql_query_decide($sql_sin) or die($sql_sin.mysql_error_js());
				$row_sin = mysql_fetch_array($res_sin);

				if($row_mul['COUNT' > 0])
					$code_already_exists = 1;
				elseif($row_sin['COUNT' > 0])
					$code_already_exists = 1;
				else
					$code_already_exists = 0;

				if(!$code_already_exists)
				{
					$start_day_count = gregoriantojd($discount_duration_start_month, $discount_duration_start_day, $discount_duration_start_year);
					list($curyear,$curmonth,$curday) = explode("-",date('Y-m-d'));
					$today_count = gregoriantojd($curmonth,$curday,$curyear);
					$diff_days = $start_day_count - $today_count;
					if($diff_days <= 0)
						$active = 1;
					else
						$active = 0;

					if($discount_usage == "M")
					{
						if(!$active)
							$sql_ins = "INSERT INTO newjs.DISCOUNT_CODE_MULTIPLE(CODE,NAME,PERCENT,START_DATE,END_DATE,MESSAGE,ENTRY_BY,ACTIVE) VALUES('$discount_code','$discount_name','$discount_percent','$start_date','$end_date','$discount_message','$entry_by','N')";
						else
							$sql_ins = "INSERT INTO newjs.DISCOUNT_CODE_MULTIPLE(CODE,NAME,PERCENT,START_DATE,END_DATE,MESSAGE,ENTRY_BY) VALUES('$discount_code','$discount_name','$discount_percent','$start_date','$end_date','$discount_message','$entry_by')";
						mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());
					}
					elseif($discount_usage == "S")
					{
						$discount_details["NAME"] = $discount_name;
						$discount_details["PERCENT"] = $discount_percent;
						$discount_details["NUMBER_OF_CODES"] = $number_of_codes;
						$discount_details["START_DATE"] = $start_date;
						$discount_details["END_DATE"] = $end_date;
						$discount_details["MESSAGE"] = $discount_message;
						$discount_details["ENTRY_BY"] = $entry_by;
						$discount_details["ACTIVE"] = $active;
						rand_discount_no("","","",$discount_details);
						$smarty->assign("SHOW_GENERATED_CODES_LINK",1);
						$smarty->assign("discount_name",$discount_name);
					}
				}
				else
					$smarty->assign("ALREADY_EXISTS",1);

				$smarty->assign("SUBMITTED",1);
				$smarty->display("discount_new_entry.htm");
			}
		}
		elseif($view_generated_codes)
		{
			$header = "CODES";

			$sql = "SELECT CODE FROM newjs.DISCOUNT_CODE WHERE NAME_OF_CODE = '$discount_name'";
			$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
			while($row = mysql_fetch_array($res))
				$codes .= $row['CODE']."\t\n";

			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Collection_Details.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $final_data = $header."\n".$codes;
		}
		else
		{
			show_discount_codes();

			list($curyear,$curmonth,$curday) = explode("-",date("Y-m-d"));
			$ts = mktime(0,0,0,$curmonth+2, $curday,$curyear);
			list($later_year,$later_month,$later_day) = explode("-",date("Y-m-d",$ts));

			$smarty->assign("curday",$curday);
			$smarty->assign("curmonth",$curmonth);
			$smarty->assign("curyear",$curyear);
			$smarty->assign("later_day",$later_day);
			$smarty->assign("later_month",$later_month);
			$smarty->assign("later_year",$later_year);
			$smarty->display("discount_new_entry.htm");
		}
	}
	else
	{
		$msg="Your session has been timed out<br>";
		$msg .="<a href=\"index.htm\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}

	function show_discount_codes()
	{
		global $smarty;

		$i=0;
		$sql_multiple = "SELECT * FROM newjs.DISCOUNT_CODE_MULTIPLE WHERE ACTIVE='Y'";
		$res_multiple = mysql_query_decide($sql_multiple) or die($sql_multiple.mysql_error_js());
		while($row_multiple = mysql_fetch_array($res_multiple))
		{
			$multiple_use[$i]["CODE"] = $row_multiple["CODE"];
			$multiple_use[$i]["NAME"] = $row_multiple["NAME"];
			$multiple_use[$i]["PERCENT"] = $row_multiple["PERCENT"];
			$multiple_use[$i]["MESSAGE"] = $row_multiple["MESSAGE"];
			$multiple_use[$i]["START_DATE"] = substr($row_multiple["START_DATE"],0,10);
			$multiple_use[$i]["END_DATE"] = substr($row_multiple["END_DATE"],0,10);
			$i++;
		}
		$smarty->assign("multiple_use",$multiple_use);

		$j=0;
		$sql_single = "SELECT * FROM newjs.DISCOUNT_CODE WHERE ACTIVE='Y' AND PROFILEID IS NULL";
		$res_single = mysql_query_decide($sql_single) or die($sql_single.mysql_error_js());
		while($row_single = mysql_fetch_array($res_single))
		{
			if(!@in_array($row_single['NAME_OF_CODE'],$codes))
			{
				$codes[] = $row_single['NAME_OF_CODE'];
				$single_use[$j]["NAME"] = $row_single["NAME_OF_CODE"];
				$single_use[$j]["PERCENT"] = $row_single["DISCOUNT_PERCENT"];
				$single_use[$j]["MESSAGE"] = $row_single["DISCOUNT_MESSAGE"];
				$single_use[$j]["START_DATE"] = substr($row_single["DISCOUNT_START_DATE"],0,10);
				$single_use[$j]["END_DATE"] = substr($row_single["DISCOUNT_END_DATE"],0,10);
				$j++;
			}
		}
		$smarty->assign("single_use",$single_use);
	}
?>
