<?php
/***************************************************************************************************************************
FILE NAME       : easy_bill.php
DESCRIPTION     : This script finds and displays the reference id's and their corresponding receipt id's for a particular
		  user.
CREATED BY      : Sriram Viswanathan
***************************************************************************************************************************/
include("../jsadmin/connect.inc");
include("comfunc_sums.php");
$data=authenticated($cid);
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
if($data)
{
	$serviceObj = new Services;
	if($criteria=="ref_id")
	{
		$sql_uname = "SELECT USERNAME FROM billing.EASY_BILL WHERE REF_ID='$phrase'";
		$res_uname = mysql_query_decide($sql_uname) or logError_sums($sql_uname,0);
		$row_uname = mysql_fetch_array($res_uname);
		$phrase = $row_uname['USERNAME'];
	}
	$sql = "SELECT * FROM billing.EASY_BILL WHERE USERNAME='$phrase'";
	$res = mysql_query_decide($sql) or logError_sums($sql,0);
	if(mysql_num_rows($res) > 0)
	{
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			$profileid = $row["PROFILEID"];
			if(is_profile_offline($profileid) && !$offline_billing)
				$smarty->assign("ONLINE_TRYING_OFFLINE",1);
			elseif(!is_profile_offline($profileid) && $offline_billing)
				$smarty->assign("OFFLINE_TRYING_ONLINE",1);

			$sql_rect = "SELECT * FROM billing.EASY_BILL_RECEIPTS WHERE REF_ID LIKE '$row[REF_ID]%'";
			$res_rect = mysql_query_decide($sql_rect) or logError_sums($sql_rect,0);
			if(mysql_num_rows($res_rect) > 0)
			{
				while($row_rect = mysql_fetch_array($res_rect))
				{
					if($prev_ref_id != $row['REF_ID'])
						$details[$i]['REF_ID'] = $row['REF_ID'];

					$details[$i]['USERNAME'] = $row['USERNAME'];

					$sql_ser = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$row[SERVICEID]'";
					$res_ser = mysql_query_decide($sql_ser) or logError_sums($sql_ser,0);
					$row_ser = mysql_fetch_array($res_ser);

					$details[$i]['MAIN_SERVICE'] = $row_ser['NAME'];

					if(strstr($row['ADDON_SERVICEID'],'B'))
						$details[$i]['ADDON_SERVICE'] .= "Profile Highlighting";
					if(strstr($row['ADDON_SERVICEID'],'M'))
						$details[$i]['ADDON_SERVICE'] .= ", Matri Profile";
					if(strstr($row['ADDON_SERVICEID'],'K'))
						$details[$i]['ADDON_SERVICE'] .= ", Kundali";
					if(strstr($row['ADDON_SERVICEID'],'H'))
						$details[$i]['ADDON_SERVICE'] .= ", Horoscope";
					if(strstr($row['ADDON_SERVICEID'],'V'))
						$details[$i]['ADDON_SERVICE'] .= ", Voicemail";

					$details[$i]['CURTYPE'] = $row['TYPE'];
					$details[$i]['AMOUNT'] = $row['AMOUNT'];
					list($yy,$mm,$dd) = explode("-", substr($row['ENTRY_DT'],0,10));
					$details[$i]['ENTRY_DT'] = $dd."-".$mm."-".$yy;

					$details[$i]['RECT_ID'] = $row_rect['RECT_ID'];
					$details[$i]['RECT_AMOUNT'] = $row_rect['AMOUNT'];
					$details[$i]['TRANSACTION_DT'] = $row_rect['TRANSACTION_DT'];
					$details[$i]['CITY'] = $row_rect['CITY'];
					$details[$i]['RETAILER_NAME'] = $row_rect['RETAILER_NAME'];
					$details[$i]['CD_NUM'] = $row_rect['CD_NUM'];
					$details[$i]['CD_CITY'] = $row_rect['CD_CITY'];
					$details[$i]['CD_DT'] = $row_rect['CD_DT'];
					$details[$i]['BANK_NAME'] = $row_rect['BANK_NAME'];

					$prev_ref_id = $row['REF_ID'];
					$i++;
				}
			}
			else
			{
				$details[$i]['REF_ID'] = $row['REF_ID'];
				$details[$i]['USERNAME'] = $row['USERNAME'];

				if($row['ADDON_SERVICEID'])
                                        $service_str=$row['SERVICEID'].",".$row['ADDON_SERVICEID'];
                                else
                                        $service_str=$row['SERVICEID'];
                                if($service_str)
                                {
					unset($services);
	                                unset($service_arr);
                                        $service_arr=$serviceObj->getServiceName($service_str);
                                        foreach($service_arr as $k=>$v)
                                        {
                                                $services[]=$service_arr[$k]['NAME'];
                                        }
                                        $details[$i]['MAIN_SERVICE']=implode(",",$services);
                                        unset($service_str);
                                }

/*				$sql_ser = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$row[SERVICEID]'";
				$res_ser = mysql_query_decide($sql_ser) or logError_sums($sql_ser,0);
				$row_ser = mysql_fetch_array($res_ser);

				$details[$i]['MAIN_SERVICE'] = $row_ser['NAME'];
                                                                                                                             
				if(strstr($row['ADDON_SERVICEID'],'B'))
					$details[$i]['ADDON_SERVICE'] .= "Profile Highlighting";
				if(strstr($row['ADDON_SERVICEID'],'M'))
					$details[$i]['ADDON_SERVICE'] .= ", Matri Profile";
				if(strstr($row['ADDON_SERVICEID'],'K'))
					$details[$i]['ADDON_SERVICE'] .= ", Kundali";
				if(strstr($row['ADDON_SERVICEID'],'H'))
					$details[$i]['ADDON_SERVICE'] .= ", Horoscope";
				if(strstr($row['ADDON_SERVICEID'],'V'))
					$details[$i]['ADDON_SERVICE'] .= ", Voicemail";
  */                                                                                                                           
				$details[$i]['CURTYPE'] = $row['TYPE'];
				$details[$i]['AMOUNT'] = $row['AMOUNT'];
				list($yy,$mm,$dd) = explode("-", substr($row['ENTRY_DT'],0,10));
				$details[$i]['ENTRY_DT'] = $dd."-".$mm."-".$yy;
				$i++;
			}
		}
	}
	else
		$smarty->assign("NO_RESULT",1);

	$smarty->assign("details",$details);
	$smarty->assign("frm_eb_details",$frm_eb_details);
	$smarty->assign("USER",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("offline_billing",$offline_billing);
	$smarty->display('easy_bill.htm');
}
else
{
	$smarty->assign("cid",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
