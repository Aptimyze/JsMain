<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
	include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
	if(authenticated($cid))
	{
        	$serviceObj = new Services;
		$name = getname($cid);
		if($go)
		{
			$keyword = addslashes(stripslashes($keyword));
			if($criteria == "username")
				$sql = "SELECT USERNAME, PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$keyword'";
			elseif($criteria == "ivr")
				$sql = "SELECT PROFILEID FROM billing.IVR_DETAILS WHERE ID='$keyword'";

			$res = mysql_query_decide($sql) or die($sql.mysql_error());
			$row = mysql_fetch_array($res);
			$profileid = $row['PROFILEID'];
			$username = $row["USERNAME"];

			if($criteria == "ivr")
			{
				$sql = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$res = mysql_query_decide($sql) or die($sql.mysql_error());
				$row = mysql_fetch_array($res);
				$username = $row["USERNAME"];
			}

			$i=0;
			$sql_ivr = "SELECT * FROM billing.IVR_DETAILS WHERE PROFILEID='$profileid' ORDER BY ID DESC";
			$res_ivr = mysql_query_decide($sql_ivr) or die($sql_ivr.mysql_error());
			while($row_ivr = mysql_fetch_array($res_ivr))
			{
				unset($services);
                                unset($service_arr);

				if($row_ivr['ADDON_SERVICEID'])
                                        $service_str=$row_ivr['SERVICEID'].",".$row_ivr['ADDON_SERVICEID'];
                                else
                                        $service_str=$row_ivr['SERVICEID'];
                                if($service_str)
                                {
                                        $service_arr=$serviceObj->getServiceName($service_str);
                                        foreach($service_arr as $k=>$v)
                                        {
                                                $services[]=$service_arr[$k]['NAME'];
                                        }
                                        $details[$i]['MAIN_SERVICE']=implode(",",$services);
                                        unset($service_str);
                                }

				$details[$i]["IVR_CODE"] = $row_ivr['ID'];
				$details[$i]["USERNAME"] = $username;
				$details[$i]["TOTAL_AMOUNT"] = $row_ivr['TYPE'].floor($row_ivr['AMOUNT'] + $row_ivr['DISCOUNT']);
				$details[$i]["DISCOUNT"] = $row_ivr['DISCOUNT'];
				$details[$i]["PAYABLE_AMOUNT"] = $row_ivr['AMOUNT'];
				$details[$i]["GENERATED_DATE"] = $row_ivr['ENTRY_DT'];
				$details[$i]["GENERATED_BY"] = $row_ivr['GENERATED_BY'];
				$details[$i]["BILLING"] = $row_ivr['BILLING'];
				$details[$i]["BILLID"] = $row_ivr['BILLID'] ? $row_ivr['BILLID'] : "-";
				$details[$i]["BILLING_BY"] = $row_ivr['BILLING_BY'];
				$i++;
			}
			$smarty->assign("details",$details);
		}

		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("search_ivr_users.htm");
	}
	else
	{
		$smarty->assign("CID",$cid);
		$smarty->display("jsconnectError.tpl");
	}
?>
