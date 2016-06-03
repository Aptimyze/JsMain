<?php
/***************************************************************************************************************************
FILE NAME       : bluedart_search.php
***************************************************************************************************************************/
include("../jsadmin/connect.inc");
include("comfunc_sums.php");
$data=authenticated($cid);
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
if($data)
{
	$serviceObj = new Services;
	
	$sql = "SELECT * FROM billing.BLUEDART_COD_REQUEST WHERE USERNAME='$phrase'";
	$res = mysql_query_decide($sql) or logError_sums($sql,0);
	if(mysql_num_rows($res) > 0)
	{
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			$profileid = $row["PROFILEID"];
			{
				$details[$i]['REF_ID'] = $row['REF_ID'];
				$details[$i]['USERNAME'] = $row['USERNAME'];
				$details[$i]['AIRWAY_NUMBER'] = $row['AIRWAY_NUMBER'];
				$active = $row['ACTIVE'];
				if($active=='N')
					$details[$i]['ACTIVE'] = 'Cancelled';
				else
					$details[$i]['ACTIVE'] = 'Active';
				$service_str=$row['SERVICE'];
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

				$details[$i]['DISCOUNT'] = $row['DISCOUNT_AMNT'];
				$details[$i]['AMOUNT'] = $row['TOTAL_AMOUNT'];
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
	$smarty->display('bluedart_search.htm');
}
else
{
	$smarty->assign("cid",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
