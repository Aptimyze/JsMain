<?php
header("Location: http://www.jeevansathi.com/membership/jspc");
die();
//to zip the file before sending it
//print_r($_GET);
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
$db=connect_db();
$data=authenticated($checksum);
$smarty->assign("con_chk",'4');
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("bms_membership",1);
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
$memObj = new Membership;

	$smarty->assign("PROFILEID",$data['PROFILEID']);
if($data && $voucher_discount=='Recalculate')
{
	$profileid=$data['PROFILEID'];
	//$prev_status=getSubscriptionStatus($profileid);
	$prev_status=$memObj->isRenewable($profileid);
	$fail=0;
	if(!$prev_status && $voucher_discount )
	{
		$returned_val = check_voucher_discount_code($voucher_discount_code,$profileid);
		
		if($returned_val['CODE_EXISTS'] > 0 || "Y"==$rem)
		{
			mark_voucher_code($profileid,$voucher_discount_code,'BOOK');
			$vdr = $returned_val['PERCENT'];
			$discount_message = $returned_val['MESSAGE'];
			if($vdr)
				$voucher_discount_rate = $vdr;
			if(!$discount_message)
				$discount_message="discount for Voucher Code";

			$subtotal2=round((($voucher_discount_rate/100)*$subtotal),2);
			if($type=="DOL")
				$total=round($subtotal2);
			else
				$total=floor($subtotal2);

			$fail = 1;
			$DISCOUNT_TYPE =8;
		}

	}
	$PRICE = $PRICE - $total;
	$details['DISCOUNT_PERCENT']=$voucher_discount_rate;
	$details['DISCOUNT_TYPE']=$DISCOUNT_TYPE;
	$details['PRICE']=$PRICE;
	$details['message']=$discount_message;
	$details['dispri']=$total;
	$details['fail']=$fail;
	$details['voucher_discount_code']=$voucher_discount_code;
	$b = json_encode($details);
	die($b);
}

//print_r($_POST);
//if(($data && $_POST)||($data && $_GET))
if(($data && $thru1)||($data && $services))
{
	$profileid=$data['PROFILEID'];
	//One month mailer
	$services_temp =explode(",",$services);	
	if(in_array("P1",$services_temp) || in_array("C1",$services_temp) || in_array("P1W",$services_temp) || in_array("P2W",$services_temp))
	{
		$renewCheck =$memObj->isRenewable($profileid);

                $sql_VD = "SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID = $profileid AND EDATE>=NOW()";
                $result_VD = mysql_query_decide($sql_VD) or logError_sums($sql_VD,1);
                $row_VD = mysql_fetch_assoc($result_VD);
		if($row_VD!='' || $renewCheck)
			die("<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/P/mem_comparison.php?checksum=$checksum\">");
		else{
			$sql_PPU = "SELECT PROFILEID FROM billing.OFFER_DISCOUNT WHERE PROFILEID = $profileid AND EXPIRY_DT>=NOW()";
	        	$result_PPU = mysql_query_decide($sql_PPU) or logError_sums($sql_PPU,1);
        		$row_PPU = mysql_fetch_assoc($result_PPU);
        		if($row_PPU!='')
				$smarty->assign("disable_choose_option",1);
			else
				die("<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/P/mem_comparison.php?checksum=$checksum\">");
		}
	}
	//end
        $sql_order = "SELECT COUNTRY_RES,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = $profileid ";
        $result = mysql_query_decide($sql_order) or logError_sums($sql_order,1);
        $row = mysql_fetch_assoc($result);
	$smarty->assign("COURIER",'N');
	$smarty->assign("EASY_BILL",'N');
	if($mode)
		$smarty->assign("mode",$mode);
	else
		$smarty->assign("mode","first");
	//if($mode=='cheque')
	//	$smarty->assign("mode",$mode);
        if ($row[COUNTRY_RES] == '51')
        {
	        $cur_type='RS';
                $smarty->assign("indian","Y");
                $sql_near="SELECT VALUE from incentive.BRANCH_CITY where PICKUP='Y' ";
                $result_near=mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row_near=mysql_fetch_array($result_near))
                {
                        if($row_near["VALUE"]!="GU")
                                $near_ar[]=$row_near["VALUE"];
                }
                if(in_array($row["CITY_RES"],$near_ar))
                {
                        $smarty->assign("COURIER",'Y');
                }
/**************Code for checking easy bill outlets********************************************************************/
                $sql_easy_bill="select distinct(CITY_VALUE) from billing.EASY_BILL_LOCATIONS WHERE ACTIVE='Y'";
                $result_easy=mysql_query_decide($sql_easy_bill) ;
                while($row_easy=mysql_fetch_array($result_easy))
                {
                        $easy_ar[]=$row_easy["CITY_VALUE"];
                }
                if(in_array($row["CITY_RES"],$easy_ar))
                {
                        $smarty->assign("EASY_BILL",'Y');
                }
/***************************code for easy bill ends here****************************************************************/
	}
        else
        {
	        $cur_type='DOL';
		$paypal_country_array=array(5,7,8,12,17,22,24,25,28,31,32,33,34,39,40,42,45,48,49,50,55,56,57,58,59,65,68,69,70,73,76,81,82,86,93,94,103,104,105,107,108,109,112,113,116,119,121,126,127,128,130);
		if(in_array($row[COUNTRY_RES],$paypal_country_array))
			$smarty->assign("GO_PAYSEAL",1);
	}
	global $renew_discount_rate;
	$serObj = new Services;

	$disc=$memObj->isRenewable($profileid);
	if($disc)
	{
		 $disc='Y';
		$DISCOUNT_TYPE=1;
	}
	else
	{
		$disc ='N';
		$returned_val = check_voucher_discount_code($voucher_code,$profileid);//print_r($returned_val);
		if($returned_val['CODE_EXISTS'] > 0 )//$avail_discount == 'Y')
		{
			$smarty->assign('voucher_code',$voucher_code);
			$smarty->assign('DISCOUNT_MSG',$DISCOUNT_MSG);
			$smarty->assign('DISCOUNT',$DISCOUNT);
			$smarty->assign('avail_discount','Y');
			$smarty->assign("DISCOUNT_TYPE",$DISCOUNT_TYPE);
		}
		$Spec_arr=$memObj->getSpecialDiscount($profileid);
		$Spec=$Spec_arr['DISCOUNT'];
                $smarty->assign('Spec',$Spec);
		if($Spec)
		{
		 	$disc='Y';
			$DISCOUNT_TYPE='5';
			$renew_discount_rate=$Spec;
		}

	}

	$smarty->assign('DISC',$disc);
        $smarty->assign('CURRENCY',$cur_type);
	$check = 0;
	if($profileid)
	{
		savehits_payment($profileid,"2");
		//if(isset($payment_source))
		if($from_source)
		{
			sourcetracking_payment($profileid,'2',$from_source);
			$smarty->assign('from_source',$from_source);
		}
/*		if(isset($payment_source))
		{
			$smarty->assign("payment_source",$payment_source);
		}
*/
		$smarty->assign('USERNAME',$data[USERNAME]);
		if($serObj->getFestive())
			$Fest=1;
		else
			$Fest=0;
		$PRICE=0;$ii=0;$assem='';
		if($returned_val['CODE_EXISTS'] > 0 )
                {
		$PRICE-=$DISCOUNT;
		}
		if($services)
		{
			$arr =explode(",",$services);
			for($i = 0;$i<count($arr);$i++)
			if($arr[$i])
			{
				$var=$arr[$i];
				if(strstr($var,'P'))
				{
					 $main_service=$var;
				}
				if(strstr($var,'C'))
				{
					 $main_service=$var;
				}
				if(strstr($var,'B'))
				{
					$bold=$var;
					$cb3=1;
				}
				if(strstr($var,'T'))
				{
					$T_arr=$var;
				}
				if(strstr($var,'A'))
				{
					$A_arr=$var;
				}
				if(strstr($var,'M'))
				{
					$cb5=$var;
				}
			}
		}
		else
			if($cb3)
				$bold=$cb3;
		
		if($main_service)
		{
			$serv_dura=$serObj->getDuration($main_service,'M');
			
			/* Changes done as per Bug 38582 */

			$Resp_Duration=$serObj->getDuration($T_arr,'M');
			$Bold_Duration=$serObj->getDuration($bold,'M');
			
			if($Resp_Duration > $serv_dura) 
			{
				$Resp_Duration=$serv_dura;
				$T_arr="T".$Resp_Duration;
			}

			if($Bold_Duration > $serv_dura)
			{
				$Bold_Duration=$serv_dura;
				$bold="B".$Bold_Duration;
			}
			
			/* Ends Here */

			$serv_name=$serObj->getServiceName($main_service);//,$bold,$A_arr,$cb5);			
			$serv_name=$serv_name[$main_service][NAME];
			$call_dur=$serObj->getServiceDirectCalls($main_service);
			$disc_str=$serObj->getDiscountStr($main_service);
                        if($disc_str && $main_service!='PL' && $main_service!='CL')
                                $off_str=$disc_str;
			if($call_dur)
				$serv_name=$serv_name."<span class=\"black\" style=\"margin:0; padding:0;font-size:15px\"> [ $call_dur Direct Calls Available ]</span>";
			if($off_str)
				$serv_name=$serv_name."<br><b class='mar_clr t14'>$off_str</b>";
			$serv_price=$serObj->getServicesAmount($main_service,$cur_type);
			$serv_price=$serv_price[$main_service][PRICE];
			$serv_price_disc=$serObj->getDiscountedPrice($DISCOUNT_TYPE,$serv_price,$main_service,$profileid);
			$serv_price_display=$serv_price;
			
			if($disc =='Y')
			{
                	       // $serv_price = ceil((1-($renew_discount_rate/100))*$serv_price);
				$serv_price = $serv_price-$serv_price_disc;
				$msg=$serObj->getDiscountMsg($DISCOUNT_TYPE,$Spec);
				$smarty->assign('DISCOUNT_MSG',$msg);
			}
			//if($Fest && ($main_service=='PL' || $main_service=='CL'))
                	$festiveOfferLookupObj  =new billing_FESTIVE_OFFER_LOOKUP();
                	$festiveDiscountPercent =$festiveOfferLookupObj->getPercDiscountOnService($main_service);
			if($Fest && $festiveDiscountPercent>0)
			{
				if($DISCOUNT_TYPE!=1)
				{
					$mon_off=1;
					$discount=$serObj->getDiscountedPrice('6',$serv_price,$main_service,$profileid);
					$serv_price = $serObj->getOfferPrice($serv_price,$festiveDiscountPercent);
					$smarty->assign("DISCOUNT",$discount);
					$msg=$serObj->getDiscountMsg('6',$festiveDiscountPercent);
					$smarty->assign('DISCOUNT_FMSG',$msg);
					$smarty->assign('Fest',1);
					if($Spec)
						$DISCOUNT_TYPE=9;
					elsE
						$DISCOUNT_TYPE=6;
				}
			}
			elseif($Fest)
			{
				if($DISCOUNT_TYPE!=1)
					$DISCOUNT_TYPE=6;
				else
					$DISCOUNT_TYPE=7;
			}
			$PRICE+=$serv_price;
			$smarty->assign('MNAME',$serv_name);
			$smarty->assign('MPRICE',$serv_price);	
			$smarty->assign('MID',$main_service);
			$check+=1;
			$ii=1;
			$assem.=$main_service.",";
			$smarty->assign('MPRICE_SHOWN',$serv_price_display);
		}
		$check*=10;
		if($cb3 && $bold)
		{
			$serv_name=$serObj->getServiceName($bold);//,$bold,$A_arr,$cb5);                        
                        $serv_name=$serv_name[$bold][NAME];
                        $serv_price=$serObj->getServicesAmount($bold,$cur_type);
			$serv_price=$serv_price[$bold][PRICE];
			$PRICE =$PRICE+$serv_price;
                        $smarty->assign('BNAME',$serv_name);
                        $smarty->assign('BPRICE',$serv_price); 
                        $check+=1;
			$smarty->assign('FORB',++$ii);
			$smarty->assign('BOLD',$bold);
			$assem.=$bold.",";
		}
		$check*=10;
		if($T_arr)
                {
                        $serv_name=$serObj->getServiceName($T_arr);//,$bold,$T_arr,$cb5);                        
                        $serv_name=$serv_name[$T_arr][NAME];
                        $serv_price=$serObj->getServicesAmount($T_arr,$cur_type);
			$serv_price=$serv_price[$T_arr][PRICE];
			$PRICE =$PRICE+$serv_price;
                        $smarty->assign('TNAME',$serv_name);
                        $smarty->assign('TPRICE',$serv_price);
                        $check+=1;
			$smarty->assign('FORT',++$ii);
			$smarty->assign('RB',$T_arr);
			$assem.=$T_arr.",";
		}
                $check*=10;
                if($cb5)
                {
			if(!$main_service)
				$cb5='M';
			$matri_dura=substr($cb5,1);		
			if($matri_dura > $serv_dura)
				$cb5='M'.$serv_dura;
                        $serv_name=$serObj->getServiceName($cb5);//,$bold,$A_arr,$cb5);                        
                        $serv_name=$serv_name[$cb5][NAME];
                        $serv_price=$serObj->getServicesAmount($cb5,$cur_type);
			$serv_price=$serv_price[$cb5][PRICE];
			$PRICE =$PRICE+$serv_price;
                        $smarty->assign('MANAME',$serv_name);
                        $smarty->assign('MAPRICE',$serv_price);
                        $check+=1;
			$smarty->assign('FORM',++$ii);
			$smarty->assign('MATRO',$cb5);
			$assem.=$cb5.",";
                }
		$check*=10;
		if($A_arr)
                {
                        $serv_name=$serObj->getServiceName($A_arr);//,$bold,$A_arr,$cb5);                        
                        $serv_name=$serv_name[$A_arr][NAME];
                        $serv_price=$serObj->getServicesAmount($A_arr,$cur_type);
			$serv_price=$serv_price[$A_arr][PRICE];
			$PRICE =$PRICE+$serv_price;
                        $smarty->assign('ANAME',$serv_name);
                        $smarty->assign('APRICE',$serv_price);
                        $check+=1;
			$smarty->assign('FORA',++$ii);
			$smarty->assign('ASTRO',$A_arr);
			$assem.=$A_arr;
                }
		if($main_service && ($disc =='Y' || $mon_off))
		{
			$smarty->assign("DISCOUNTED_PRICE",$serv_price_disc);
		}
		$smarty->assign("DISCOUNT_TYPE",$DISCOUNT_TYPE);
		$smarty->assign('IDS',$assem);
		$smarty->assign('PRICE',$PRICE);
		$smarty->assign('CHECK',$check);
		$smarty->assign('CHECK1',$check);
		$smarty->display("payment.htm");
	}
}
else if($data)
{
	die("<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/P/mem_comparison.php?checksum=$checksum\">");
	
}
else
{
        Timedout();
}

function get_special_discount($profileid)
{
        $sql_score = "SELECT PROFILEID FROM billing.SCORE_MAILER WHERE PROFILEID='$profileid'";
        $res_score = mysql_query_decide($sql_score) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row_score = mysql_fetch_array($res_score);
        if($row_score['PROFILEID'] >0)
                return 1;
        else
                return 0;
}
        // flush the buffer
        if($zipIt && !$dont_zip_now)
                ob_end_flush();

?>
