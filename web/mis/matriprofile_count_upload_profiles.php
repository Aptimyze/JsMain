<?php
include('../mis/connect.inc');

$db = connect_misdb();
$db2 = connect_master();
if(authenticated($checksum))
{
	$user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
	{
		list($date_today,$date_today_nozero,$month_today_num,$month_today,$year_today) = explode("-",date('d-j-m-M-Y'));
		$smarty->assign("month_today_num",$month_today_num);
		$smarty->assign("month_today",$month_today);
		$smarty->assign("year_today",$year_today);
		$smarty->assign("date_today",$date_today);
		$smarty->assign("date_today_nozero",$date_today_nozero);

		$smarty->assign("flag","1");
		if($submit)
		{
			$smarty->assign("checksum",$checksum);
			if(!checkdate($cd_month_from,$cd_day_from,$cd_year_from) || !checkdate($cd_month_to,$cd_day_to,$cd_year_to))
			{
				$smarty->assign("flag","1");
				$smarty->assign("flag1","1");
				$smarty->assign("checksum",$checksum);
				$smarty->display('matriprofile_count_upload_profiles.htm');
			}
			else
			{
				$smarty->assign("flag","1");
				$smarty->assign("flag1","2");	
				$fromdate = $cd_year_from."-".$cd_month_from."-".$cd_day_from;
				
				$todate = $cd_year_to."-".$cd_month_to."-".$cd_day_to;
				if($fromdate>$todate)
				{
					$temp = $fromdate;
					$fromdate = $todate;
					$todate = $temp;
				}

				//$sql_req_upload = "SELECT COUNT(*) FROM billing.PURCHASES as a left join billing.UPLOAD_MATRI_STATUS as b  ON a.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' AND (SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M' ) AND ENTRY_DT BETWEEN '$fromdate' AND '$todate' ORDER BY ENTRY_DT ASC";
				$sql_req_upload = "SELECT COUNT(*) FROM billing.PURCHASES as a left join billing.UPLOAD_MATRI_STATUS as b  ON a.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' AND (SERVICEID LIKE '%M%' OR ADDON_SERVICEID REGEXP 'M' ) AND ENTRY_DT BETWEEN '$fromdate' AND '$todate' ORDER BY ENTRY_DT ASC";

				$result_req_upload = mysql_query_decide($sql_req_upload,$db) or die("$sql_req_upload".mysql_error_js($db));

				$row_req_upload = mysql_fetch_row($result_req_upload);

				$smarty->assign("UPLOAD_REQUESTS",$row_req_upload[0]);

				$sql_uploaded = "SELECT COUNT(*) FROM billing.UPLOAD_MATRI_STATUS WHERE STATUS = 'Y' AND UPLOAD_DATE BETWEEN '$fromdate' AND '$todate'";

				$result_uploaded = mysql_query_decide($sql_uploaded,$db) or die("$sql_uploaded".mysql_error_js($db));

				$row_uploaded = mysql_fetch_row($result_uploaded);

				$smarty->assign("UPLOADED",$row_uploaded[0]);
				
				$smarty->assign("checksum",$checksum);
				
				$smarty->display('matriprofile_count_upload_profiles.htm');
			}
		}
		else
		{
			$smarty->assign("checksum",$checksum);
			$smarty->display('matriprofile_count_upload_profiles.htm');
		}
	}
        else
        {
                echo "You don't have permission to view this mis";
                die;
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
