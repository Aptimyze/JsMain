<?php
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/../lib/model/enums/Membership.enum.class.php");
$TAX_RATE=billingVariables::TAX_RATE;
function printletter($profileid,$id)
{
	global $smarty;
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	include_once("../classes/Membership.class.php");

	$sql="SELECT SERVICE,NAME,ADDON_SERVICEID FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$profileid' and ID='$id'";
        $result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        $myrow=mysql_fetch_array($result);

        $smarty->assign("name",$myrow['NAME']);
        $smarty->assign("dated",date('M j, Y'));
	$membershipObj = new Membership;
	if($myrow["ADDON_SERVICEID"])
		$serviceid=$myrow["SERVICE"].",".$myrow["ADDON_SERVICEID"];
	else
		$serviceid=$myrow["SERVICE"];
	$msg = $membershipObj->membership_details($serviceid);
	$msg = str_replace("\par","\n",$msg);
	$msg = str_replace("Add-on Privileges","\n<b>Add-on Privileges</b>\n",$msg);
	$msg = str_replace("tenure:","tenure:\n",$msg);
	$msg = str_replace("Profile Highlighting","<b>Profile Highlighting</b>",$msg);
	$msg = str_replace("Astro Compatibility","<b>Astro Compatibility</b>",$msg);
	$msg = str_replace("Matri Profile","<b>Matri Profile</b>",$msg);
	$msg = nl2br($msg);
	$smarty->assign("msg",$msg);
	$output = $smarty->fetch("fullmem_aramex.htm");
        if($output == "ERROR")
        {
                echo "ERROR";
                exit;
        }
        return $output;

}

function printinvoice($profileid,$id)
{
        global $smarty, $TAX_RATE;
        $invoicedate=date('Y');        
        $invoiceyear= substr($invoicedate,2,2);                                                                                         
        $invoice_toassign=$invoiceyear;
        $d=$invoice_toassign+1;
        if($d<10)
                $d="0".$d;
        $invoice_toassign.=$d;
                                                                                                 
        $sql="SELECT SERVICE,USERNAME,NAME,ADDON_SERVICEID, ADDRESS, CITY, PIN, EMAIL,DISCOUNT from incentive.PAYMENT_COLLECT where PROFILEID='$profileid' and ID = '$id'";
        $result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        $myrow=mysql_fetch_array($result);
/*
        if(strstr($myrow['SERVICE'],'2'))
                $invoice_toassign.="-F02";
        if(strstr($myrow['SERVICE'],''))
                $invoice_toassign.="-F03";
        if($myrow['SERVICE']=="P4")
                $invoice_toassign.="-F04";
        if($myrow['SERVICE']=="P5")
                $invoice_toassign.="-F05";
        if($myrow['SERVICE']=="P6")
                $invoice_toassign.="-F06";
        if($myrow['SERVICE']=="P12")
                $invoice_toassign.="-F12";
*/                
	   if(strstr($myrow['SERVICEID'],'P'))
                $invoice_toassign.="-F";
        if(strstr($myrow['SERVICEID'],'D'))
                $invoice_toassign.="-D";
        if(strstr($myrow['SERVICEID'],'C'))
                $invoice_toassign.="-C";
                                                                                                               
        if(strlen($myrow['SERVICEID']) == 2)
        {
                if(strstr($myrow['SERVICEID'],'2'))
                        $invoice_toassign.="02";
                if(strstr($myrow['SERVICEID'],'3'))
                        $invoice_toassign.="03";
                if(strstr($myrow['SERVICEID'],'4'))
                        $invoice_toassign.="04";
                if(strstr($myrow['SERVICEID'],'5'))
                        $invoice_toassign.="05";
                if(strstr($myrow['SERVICEID'],'6'))
                        $invoice_toassign.="06";
        }
        else
        {
                        $invoice_toassign.="12";
        }

        $invoice_toassign.=$profileid;
	$service_str=$myrow["SERVICE"];
	$discount=$myrow["DISCOUNT"];
                                                                                                 
        if($myrow["ADDON_SERVICEID"] != '')
        {
                $service_str .= ",".$myrow["ADDON_SERVICEID"];
        }
        $service_str=str_replace(",","','",$service_str);
                                                                                                 
        $smarty->assign("name",$myrow['NAME']);
        $smarty->assign("address",$myrow['ADDRESS']);
//    	$smarty->assign("pin",$myrow['PIN']);
        $smarty->assign("accno",$myrow['PROFILEID']);
        $smarty->assign("username",$myrow['USERNAME']);
        $smarty->assign("dated",date('M j, Y'));
	$cityfromtable=$myrow['CITY'];
	if($cityfromtable)
	{
		if(is_numeric($cityfromtable)) 
		{
			$citylabel= label_select("CITY_USA",$cityfromtable);
			$smarty->assign("city",$citylabel[0]);
		} 
		else
		{
	        	$citylabel= label_select("CITY_INDIA",$cityfromtable);
	        	$smarty->assign("city",$citylabel[0]);
		}
	}
	else
	;
                                                                                                 
        $smarty->assign("cur","Rs");
                                                                                                 
        $sql="SELECT SQL_CACHE NAME, desktop_RS, desktop_DOL from billing.SERVICES where SERVICEID in ('$service_str') order by SORTBY";
        $result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
        $i=1;
	$service_cost_total=0;
        while($myrow2=mysql_fetch_array($result))
	{
		$ser[] = array( "sno" => $i,
                                "servicename" => $myrow2['NAME'],
                                "qty" => "1",
                                "feevalue" => $myrow2['desktop_RS']*(1-(billingVariables::TAX_RATE/100)),                             
                                "paise" => '00'                             
			      );
		$service_cost_total += $myrow2['desktop_RS']*(1-(billingVariables::TAX_RATE/100)); 
		$i++;
	}
	if($i<6)
	{
		for($i;$i<=6;$i++)
		{
			$ser[] = array( "sno" => '',
                                "servicename" => '',
                                "qty" => '',
                                "feevalue" => '',
                                "paise" => ''                             
                              );
			$i++;
		}
	}

		
//	$service_cost_total_net=$service_cost_total-$discount;
		if($discount>0)
			$smarty->assign("DISCOUNT",$discount);

	        $smarty->assign("service_cost_total",$service_cost_total);
	//	$TAX=round((($TAX_RATE/100)*$service_cost_total_net),2);
		$TAX=round((($TAX_RATE/100)*$service_cost_total),2);
	        $costvalue=floor(($service_cost_total+$TAX)-$discount);
	        $smarty->assign("accno",$profileid);
	        $smarty->assign("invoiceno",$invoice_toassign);
	        $cost=convert($costvalue);
	        $smarty->assign("tax",$TAX);
	        $smarty->assign("tax_rate",$TAX_RATE);
	        $smarty->assign("cost",$cost);
	        $smarty->assign("costvalue",$costvalue);
	        $smarty->assign("ser",$ser);
        $output = $smarty->fetch("INVOICE.htm");
        if($output == "ERROR")
        {
                echo "ERROR";
                exit;
        }
//	$smarty->display("INVOICE.htm");

	return $output;
	
}
?>
