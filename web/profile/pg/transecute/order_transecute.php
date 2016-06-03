<?php
include_once("../../connect.inc");
include_once("../functions.php");
require("functions_transecute.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

connect_db();

$ip=FetchClientIP();//Gets ipaddress of user
if(strstr($ip, ","))    
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}
if($data = authenticated($checksum))
{

	$profileid = $data["PROFILEID"];
	$memObj = new Membership;
        $disc=$memObj->isRenewable($profileid);
        if($disc)
        {
                 $disc='Y';
        }
        else
        {
                $disc ='N';
        }


        $serObj = new Services;
	$service_main =$serObj->getTrueService($service_main);
        $total=$serObj->getTotalPrice($service_main,$type);
        if(strstr($service,'P'))
        {
                 $main_service=$service;
        }
        if(strstr($service,'C'))
        {
                 $main_service=$service;
        }
        if($main_service)
        {
                $serv_price=$serObj->getServicesAmount($main_service,$type);
                $serv_price1=$serv_price[$main_service][PRICE];
                if($disc =='Y')
                {
                        $serv_price1 = floor(($renew_discount_rate/100)*$serv_price1);
                        $discount=$serv_price1;
                        $total =$total-$serv_price1;
                }
                else if($avail_discount =="Y")
                {
                        $returned_val = check_voucher_discount_code($voucher_code,$profileid);
                        if($returned_val['CODE_EXISTS'] > 0 || "Y"==$rem)
                        {
                                $vdr = $returned_val['PERCENT'];
                                if($vdr)
                                        $voucher_discount_rate = $vdr;
                                $subtotal2=round((($voucher_discount_rate/100)*$serv_price1),2);
                                if($type=="DOL")
                                        $total1=round($subtotal2);
                                else
                                        $total1=floor($subtotal2);
                                $serv_price1=$subtotal2;
                                $discount=$serv_price1;
                                $total =ceil($total-$serv_price1);
                        }
                }
        }
	
	if($checkout=1)
	{
		if(strstr($paymode,"card"))
		{

			//$toid="168";
			//$key = "ZVI8mBzmllwBpMoe48xBFM0pB9y5mgvE";

			$toid = "362";
			$key = "hdEgfuE99JeTrBbsqIRPmV5iirQicbwe";

			$totype="transecute";
			$countrycode="IN";
			$redirecturl = "http://www.jeevansathi.com/profile/pg/transecute/orderOK_transecute.php";
															 
			/* get the checksum to be send to secure.transecute.com for payment */
			$testurl = "https://secure.transecute.com/transecuteicici/icicicredit/payprocesstest.php3";//to be used while testing integration
			$liveurl = "https://secure.transecute.com/transecuteicici/icicicredit/payprocess.php3";//to be used in live mode. made effective on 21-02-2008
		//	$liveurl = "https://jeevansathi.sslbuy.net/transecuteicici/icicicredit/payprocess.php3";//iwas effective before 21-02-2008 for live mode
			//$url=$testurl;
			$url=$liveurl;

                        $discount_type =$DISCOUNT_TYPE;

			//credit card payment
			$ORDER = newOrder($profileid,$paymode,$type,$total,$service_str,$service_main,$discount,$setactivate,'TRANSECUTE',$discount_type);
			if(!$ORDER){
				$smarty->display("pg/ordererror.htm");
				die;
			}
			
//echo "$toid,$totype,".floor($ORDER["AMOUNT"]).",$ORDER[ORDERID],$redirecturl,$key";
			$checksum = getchecksum($toid,$totype,floor($ORDER["AMOUNT"]),$ORDER["ORDERID"],$redirecturl,$key);

			$smarty->assign("URL",$url);
			$smarty->assign("TOID",$toid);
			$smarty->assign("TOTYPE",$totype);
			$smarty->assign("COUNTRYCODE",$countrycode);
			$smarty->assign("REDIRECTURL",$redirecturl);
			$smarty->assign("ACTIVE",$ORDER["ACTIVE"]);
			$smarty->assign("AMOUNT",floor($ORDER["AMOUNT"]));
			$smarty->assign("ORDERID",$ORDER["ORDERID"]);
			$smarty->assign("CHECKSUM",$checksum);

/*
			$smarty->assign("BILL_NAME",$ORDER["USERNAME"]);
			$smarty->assign("BILL_ADD",$ORDER["CONTACT"]);
			$smarty->assign("BILL_COUNTRY",$ORDER["COUNTRY"]);
			$smarty->assign("BILL_PHONE",$ORDER["PHONE"]);
			$smarty->assign("BILL_EMAIL",$ORDER["EMAIL"]);
			$smarty->assign("DLVR_NAME","");
			$smarty->assign("DLVR_ADD","");
			$smarty->assign("DLVR_PHONE","");
			$smarty->assign("DLVR_NOTES","");
*/
			$smarty->display("pg/transecute/trans_redirect.htm");
//			$smarty->display("pg/redirect220.htm");
		}
		elseif(strstr($paymode,"cheque"))
		{
			//cheque or draft payment
			$ORDER = newOrder($profileid,$paymode,$type,$total,$service_str,$service_main,$discount,$setactivate,"",$discount_type);

			if(!$ORDER){
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->display("pg/ordererror.htm");
				die;
			}

			$orderdate = date("Y-m-d",time());
			list($year,$month,$day) = explode("-",$orderdate);
			$orderdate = my_format_date($day,$month,$year);

			if($type=="DOL")
			{
				$paytype = "US $";
				$smarty->assign("AMOUNT",$ORDER["AMOUNT"] / $DOL_CONV_RATE);
			}
			else
			{
				$paytype = "RS.";
				$smarty->assign("AMOUNT",$ORDER["AMOUNT"]);
			}
			$service_main_details=getServiceDetails($service_main);
			$smarty->assign("PERIOD",$service_main_details["DURATION"]);
			
			$smarty->assign("ORDERID",$ORDER["ORDERID"]);
			$smarty->assign("ORDERDATE",$orderdate);
			$smarty->assign("BILL_NAME",$ORDER["USERNAME"]);
			$smarty->assign("BILL_ADD",$ORDER["CONTACT"]);
			$smarty->assign("BILL_COUNTRY",$ORDER["COUNTRY"]);
			$smarty->assign("BILL_PHONE",$ORDER["PHONE"]);
			$smarty->assign("BILL_EMAIL",$ORDER["EMAIL"]);
			$smarty->assign("PAYTYPE",$paytype);

			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

			$smarty->display("/pg/orderreceipt_cheque.htm");
		}

	}
	else
	{
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

		$smarty->display("pg/ordererror.htm");
		//echo "<b>Strange :</b> How you get this link.<br>If you get this link genuinely, then intimate to me at alok@jeevansathi.com";
	}
}
else
{
	TimedOut();
}
?>
