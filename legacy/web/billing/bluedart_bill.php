<?php

include_once("../jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/../lib/model/enums/Membership.enum.class.php");

if($air)
{
        $membershipObj = new Membership;
	$serviceObj = new Services;
	$air=trim($air);

	$sql="SELECT * FROM billing.BLUEDART_COD_REQUEST WHERE AIRWAY_NUMBER='$air'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		$pid=$row['PROFILEID'];
		$username=$row['USERNAME'];
		$email=$row['EMAIL'];
		$ref_id=$row['REF_ID'];
		$name=$row['NAME'];
		$landline=$row['PHONE_RES'];
		$mobile=$row['PHONE_MOB'];
		$bill_date=$row['ENTRY_DT'];
		$discount=$row['DISCOUNT_AMNT'];
		$total_amount=$row['TOTAL_AMOUNT'];
		$address=$row['ADDRESS'];
		$pincode=$row['PINCODE'];
		$city=$row['CITY'];
		$service=$row['SERVICE'];
		$area=$row['AREA'];
		$destcode=$row['DESTARCD'];
		$tax_rate=billingVariables::TAX_RATE;
		$barcode=$SITE_URL.'/billing/barcode.php?barcode='.$air.'&width=236&height=56';
		$barcode="<img src='$barcode'></img>";
		$b_dt=explode("-",$bill_date);
		$yr=$b_dt[0];
		$dt=$b_dt[2];
		$mnth=$b_dt[1];
		$mnth=date('F', mktime(0,0,0,$mnth,1));
		$bill_date=$mnth.' '.$dt.', '.$yr;
		$amount_in_word=convert($total_amount);
		
		$ser_1=explode(',',$service);
		$cnt=count($ser_1);
		if(is_array($ser_1))
		{
			for($i=0;$i<$cnt;$i++)
			{
				$ser=$ser_1[$i];
				$cnt_val[]=$i+1;
				$ttl_price=$serviceObj->getServicesAmount($ser,'RS');
				$service_name=$ttl_price[$ser][NAME];
				$total_price=$ttl_price[$ser][PRICE_RS];
				$total_price_tax=$ttl_price[$ser][PRICE];
				
				$ttl_price_val[]=$ttl_price;
				$service_name_val[]=$service_name;
				$total_price_val[]=$total_price;
				$total_price_tax_val[]=$total_price_tax;

				$tax_rs=$total_price*$tax_rate/100;
				$tax_rs=round($tax_rs,2);
				$amount_collect=$total_price+$tax_rs; 
				
				$tax_rs_val+=$tax_rs;
				$total_price_value+=$total_price;	
			}
		}
		
		$smarty->assign('ttl_price_val',$ttl_price_val);
		$smarty->assign('total_price_value',$total_price_value);
		$smarty->assign('service_name_val',$service_name_val);
		$smarty->assign('total_price_val',$total_price_val);
		$smarty->assign('total_price_tax_val',$total_price_tax_val);
		$smarty->assign('tax_rs_val',$tax_rs_val);
		$smarty->assign('amount_collect_val',$amount_collect_val);
		$smarty->assign('cnt_val',$cnt_val);


		$smarty->assign('cnt',$cnt);
		$smarty->assign('destcode',$destcode);
		$smarty->assign('airway',$air);
		$smarty->assign('pid',$pid);
		$smarty->assign('area',$area);
		$smarty->assign('username',$username);
		$smarty->assign('email',$email);
		$smarty->assign('ref_id',$ref_id);
		$smarty->assign('name',$name);
		$smarty->assign('landline',$landline);
		$smarty->assign('mobile',$mobile);
		$smarty->assign('date',$bill_date);
		$smarty->assign('final_amount',$total_amount);
		$smarty->assign('address',$address);
		$smarty->assign('pincode',$pincode);
		$smarty->assign('city',$city);
		$smarty->assign('barcode',$barcode);
		$smarty->assign('tax_rate',$tax_rate);
		$smarty->assign('service',$service_name);

		$smarty->assign('amount_in_word',$amount_in_word);
		$smarty->assign('amount_collect',$amount_collect);
		$smarty->assign('discount',$discount);
		$smarty->assign('total_price_wt_tax',$total_price);
		$smarty->assign('rate',$total_price_tax);
		$smarty->assign('tax_rs',$tax_rs);
	}

	$bill=$smarty->fetch("../jsadmin/bluedart_bill.htm");
	echo $bill;
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("../billing_msg.tpl");

}
?>

