<?php

include("../jsadmin/connect.inc");
include_once("comfunc_sums.php");

include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
$serviceObj = new Services;
$data=authenticated($cid);

if(isset($data))
{
	$user=getuser($cid);
	$privilage=getprivilage($cid);
	$priv=explode("+",$privilage);

	if(in_array('BA',$priv))
	{
		$smarty->assign("ADMIN","Y");
	}
	if(trim($phrase)!="")
	{
		$sql="SELECT * from incentive.PAYMENT_COLLECT";
		if($criteria=="req_id")
	        {
			$where=" where ID='$phrase'";
		}
          	elseif($criteria=="uname")
           	{
                	//$sql="SELECT * from billing.ORDERS 
			$where=" where USERNAME = '$phrase'";
                                                                                                 
           	}
		$sql.=$where;	
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$flag=1;
			$i=0;
			while($myrow=mysql_fetch_array($result))
			{
				unset($services);
				unset($service_arr);
				$profileid = $myrow["PROFILEID"];
                                if(is_profile_offline($profileid) && !$offline_billing)
                                        $smarty->assign("ONLINE_TRYING_OFFLINE",1);
                                elseif(!is_profile_offline($profileid) && $offline_billing)
                                        $smarty->assign("OFFLINE_TRYING_ONLINE",1);

				$arr[$i]["USERNAME"]=$myrow["USERNAME"];
				$arr[$i]["REQUEST_ID"]=$myrow["ID"];
				$arr[$i]["AMOUNT"]=$myrow["AMOUNT"];
				if($myrow['ADDON_SERVICEID'])
					$service_str=$myrow['SERVICE'].",".$myrow['ADDON_SERVICEID'];
				else
					$service_str=$myrow['SERVICE'];
				if($service_str)
				{
					$service_arr=$serviceObj->getServiceName($service_str);
					foreach($service_arr as $k=>$v)
					{
						$services[]=$service_arr[$k]['NAME'];
					}
					$service_names=implode(",",$services);
					$arr[$i]["SERVEFOR"] = $service_names;
					unset($service_str);
				}
				
				$arr[$i]["ENTRY_DT"]=$myrow["ENTRY_DT"];
				$i++;	
			}
		}
		else
			$flag=0;
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("arr",$arr);
		$smarty->assign("flag",$flag);
		$smarty->assign("offline_billing",$offline_billing);
		$smarty->display("search_request_id.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("offline_billing",$offline_billing);

		$smarty->display("search_request_id.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("username","$username");
	$smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
