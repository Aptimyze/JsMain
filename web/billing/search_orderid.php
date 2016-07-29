<?php
/**********************************************************************************************
* FILE NAME   : search_orderid.php
* DESCRIPTION : Displays the billing details of online orders for the username or emailid or 
                orderid entered by the user
* MODIFY DATE        : 7 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Display orderid first in the dropdown list
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************
*************/


include("../jsadmin/connect.inc");
include_once("comfunc_sums.php");

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
		$sql="SELECT * from billing.ORDERS";
		if($criteria=="orderid")
	        {
        	        list($orderid,$id)=explode("-",$phrase);
	                //$sql="SELECT * from billing.ORDERS where ORDERID like '$orderid' and ID like '$id'";
			$where=" where ORDERID = '$orderid' and ID = '$id'";
		}
          	elseif($criteria=="uname")
           	{
                	//$sql="SELECT * from billing.ORDERS 
			$where=" where USERNAME = '$phrase'";
                                                                                                 
           	}
           	elseif($criteria=="email")
           	{
                	//$sql="SELECT * from billing.ORDERS 
			$where=" where BILL_EMAIL = '$phrase'";
           	}

		$sql.=$where;	
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$flag=1;
			$i=0;
			while($myrow=mysql_fetch_array($result))
			{
				$profileid = $myrow["PROFILEID"];
				if(is_profile_offline($profileid) && !$offline_billing)
					$smarty->assign("ONLINE_TRYING_OFFLINE",1);
				elseif(!is_profile_offline($profileid) && $offline_billing)
					$smarty->assign("OFFLINE_TRYING_ONLINE",1);

				$arr[$i]["USERNAME"]=$myrow["USERNAME"];
				$arr[$i]["ORDERID"]=$myrow["ORDERID"]."-".$myrow["ID"];
				$arr[$i]["AMOUNT"]=$myrow["AMOUNT"];
				$arr[$i]["GATEWAY"]=$myrow["GATEWAY"];
//				$arr[$i]["SERVEFOR"]=($myrow["SERVEFOR"]=="V" ? "Value Added" : "Full Member")."-".(substr($myrow["PAYMODE"],8)?substr($myrow["PAYMODE"],8):substr($myrow["PAYMODE"],6))." months";

				/* Code added by Rahul Tara to show service names */
				if($myrow['SERVICEMAIN'])
				{
					
					if(strstr($myrow['SERVICEMAIN'],","))
					{
						$ser_arr=explode(",",$myrow['SERVICEMAIN']);
						$ser_str="'".implode("','",$ser_arr)."'";
						$sql = "Select ID,NAME from billing.SERVICES where SERVICEID IN ($ser_str)";
					}
					else
					{
						$sql = "Select ID,NAME from billing.SERVICES where SERVICEID = '$myrow[SERVICEMAIN]'";	
	        	                if($myrow["ADDON_SERVICEID"])
                	        	{
                        	        	$addon_serviceid = $myrow["ADDON_SERVICEID"];
                                		$addon_serviceid_ar = explode(",",$addon_serviceid);
	                	                for($j=0;$j<count($addon_serviceid_ar);$j++)
        		                                $addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
        	        	                $addon_serviceid_str = implode(",",$addon_serviceid_ar);

	                        	        $sql .= "OR SERVICEID in ($addon_serviceid_str) ORDER BY ID";
						unset($addon_serviceid_ar);
					}
					}
                                	$result_services = mysql_query_decide($sql) or die(mysql_error_js());
					while($myrow_result_services = mysql_fetch_array($result_services))
					{
						$services[] = $myrow_result_services["NAME"];
	
                        	        }
                        	
					if(is_array($services))
			                        $service_names = implode(",",$services);
					$arr[$i]["SERVEFOR"] = $service_names;
					unset($services);
				}
				
				$arr[$i]["ENTRY_DT"]=$myrow["ENTRY_DT"];
				$arr[$i]["BILL_ADDRESS"]=$myrow["BILL_ADDRESS"];
				$arr[$i]["PINCODE"]=$myrow["PINCODE"];
				$arr[$i]["BILL_COUNTRY"]=$myrow["BILL_COUNTRY"];
				$arr[$i]["BILL_EMAIL"]=$myrow["BILL_EMAIL"];
				$arr[$i]["BILL_PHONE"]=$myrow["BILL_PHONE"];
				$arr[$i]["STATUS"]=($myrow["STATUS"]=="Y" ? "Transaction Successful" : ($myrow["STATUS"]=="N" ? "Transaction Failed" : ($myrow["STATUS"]=="B" ? "Transaction OnHold": ($myrow["STATUS"]=="A" ? "Transaction Accept" : ($myrow["STATUS"]=="R" ? "Transaction Rejected" : ""))))); 
				$arr[$i]["PMTRECVD"]=$myrow["PMTRECVD"];
				$arr[$i]["DLVR_CUST_NAME"]=$myrow["DLVR_CUST_NAME"];
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
		$smarty->display("search_orderid.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("offline_billing",$offline_billing);

		$smarty->display("search_orderid.htm");
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
