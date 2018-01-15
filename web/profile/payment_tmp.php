<?php

/*********************************************************************************************
* FILE NAME   : payment.php
* DESCRIPTION : Displaying the form for selecting services on site and also generating sample                    invoice for the same
* Author      : Kush Asthana	
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
include("pg/functions.php");
$db=connect_db();
if($ser_main == "")
{
	header('Location: mem_comparison.php');
	die();
}




if($pm=='C')
{
	$smarty->assign("PM",'C');
}
if($dec_ag=='Y')
{
	$smarty->assign("DEC_AG",'Y');
}
if($username && $password)
{
	$data=login($username,$password);
}
else	
{
	$data=authenticated($checksum);
	leftpanel_membership();
}

/*****************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);

       // $regionstr=8;
       // include("../bmsjs/bms_display.php");
/***********************End of Portion of Code*****************************************/

include_once("benefits.php");
$smarty->assign("eRishta_benefits",benefits('P'));
$smarty->assign("eClassifieds_benefits",benefits('D'));
$smarty->assign("eValuePack_benefits",benefits('C'));
$smarty->assign("head_tab","memberships"); //flag for headnew.htm tab
$smarty->assign("HEAD_NEW",$smarty->fetch("headnew.htm"));
                                                                                                                             
$db=connect_db();

$profileid=$data["PROFILEID"]; 
$smarty->assign("USERNAME",$data["USERNAME"]); 

// getting previous subscription status
$renew_status=getRenewStatus($profileid);
if($renew_status)
{
	$curdate=date('Y-m-d');
	$days_left_expire= getTimeDiff($curdate,$renew_status['EXPIRY_DT']); //Renewal not allowed before 10 days of current subscription
	if($days_left_expire >10)
	{
		list($year,$month,$day)= explode("-",$renew_status['EXPIRY_DT']);
		$exp_dt=my_format_date($day,$month,$year);
		$smarty->assign("exp_dt",$exp_dt);
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->display("ALERT.htm");
										     
		die();
	}
} 


if($data)
{
	if($profileid)
	{
		$sql="SELECT COUNTRY_RES,CITY_RES,INCOMPLETE, SUBSCRIPTION from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		$incomplete=$myrow['INCOMPLETE'];
		if($incomplete=="Y") // Incomplete Profiles check
		{
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->display("incomplete_profile.htm");
			die();
		}

		$subscription=$myrow['SUBSCRIPTION'];
		$country_res=$myrow['COUNTRY_RES'];
		$city_res=$myrow['CITY_RES'];
	}
	if($myrow["COUNTRY_RES"]=='51')
        {
		$smarty->assign("indian","Y");
		$sql_near="SELECT VALUE from incentive.BRANCH_CITY where PICKUP='Y' ";
		$result_near=mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row_near=mysql_fetch_array($result_near))
		{
			if($row_near["VALUE"]!="GU")
				$near_ar[]=$row_near["VALUE"];
		}
	  	if(in_array($myrow["CITY_RES"],$near_ar))
		{	
               		$smarty->assign("COURIER",'Y');
		}


/**************Code for checking easy bill outlets********************************************************************/
		$sql_easy_bill="select distinct(CITY_VALUE) from billing.EASY_BILL_LOCATIONS";
		$result_easy=mysql_query_decide($sql_easy_bill) ;
		while($row_easy=mysql_fetch_array($result_easy))
                {
                        $easy_ar[]=$row_easy["CITY_VALUE"];
                }
		if(in_array($myrow["CITY_RES"],$easy_ar))
                {
                        $smarty->assign("EASY_BILL_OPT",'Y');
                }
/***************************code for easy bill ends here****************************************************************/
	}
	

	if($myrow["COUNTRY_RES"]!='')
	{
/***** Setting Payment Type automatically depending on Country of Residence ********/
		if($myrow['COUNTRY_RES'] != 51)
			$type='DOL';
		else
			$type='RS';
	}	
	else
		$type='RS';
		
		$paypal_country_array=array(5,7,8,12,17,22,24,25,28,31,32,33,34,39,40,42,45,48,49,50,55,56,57,58,59,65,68,69,70,73,76,81,82,86,93,94,103,104,105,107,108,109,112,113,116,119,121,126,127,128,130);

	if($checkout1)
	{

	/**** Code for error validation *****************/
		$error=0;
		if(!$service)
		{
			$error++;
			$smarty->assign("CHECK_SERVICE","Y");
		}
	/**** Code for error validation Ends Here*****************/
		
		if($error>0)
		{
		/*********** If error then rethrowing the page with selected values ******/
			$services_list=getServices($type,$ser_main);
			$smarty->assign("SERVICE_SELECTED",$service);		
			//$smarty->assign("SERVICES_NAME",$services_list['NAME']);
			//$smarty->assign("SERVICES_PRICE",$services_list['PRICE']);
			$smarty->assign("SER_MAIN",$ser_main);
			$smarty->assign("SERVICES_LIST",$services_list);		
			$smarty->assign("TYPE",$type);
			$smarty->assign("PAYMODE",$paymode);
			$smarty->assign("VOICEMAIL",$voicemail);
			$smarty->assign("HOROSCOPE",$horoscope);
			$smarty->assign("BOLDLISTING",$boldlisting);
			$smarty->assign("MATRI_PROFILE",$matri_profile);
			$smarty->assign("KUNDLI",$kundli);
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("DEC_AG",$dec_ag);
			$smarty->display("payment.htm");
		}
		else
		{
			savehits_payment($profileid,'3');
			$services_list=getServices($type,$ser_main);
                        $smarty->assign("SERVICE_SELECTED",$service);
                        //$smarty->assign("SERVICES_NAME",$services_list['NAME']);
                        //$smarty->assign("SERVICES_PRICE",$services_list['PRICE']);
                        $smarty->assign("SER_MAIN",$ser_main);
			$ser_det=getServiceDetails($service);
			$smarty->assign("SERVICE_DUR",$ser_det["DURATION"]);
                        $smarty->assign("SERVICES_LIST",$services_list);
                        $smarty->assign("TYPE",$type);
                        $smarty->assign("PAYMODE",$paymode);
                        $smarty->assign("VOICEMAIL",$voicemail);
                        $smarty->assign("HOROSCOPE",$horoscope);
                        $smarty->assign("BOLDLISTING",$boldlisting);
                        $smarty->assign("MATRI_PROFILE",$matri_profile);
                        $smarty->assign("KUNDLI",$kundli);
                        $smarty->assign("CHECKSUM",$data["CHECKSUM"]);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                        $smarty->assign("DEC_AG",$dec_ag);

/***********code for price calculation***********************************************************************************/
			$service_main_details=getServiceDetails($service);
                        $duration=$service_main_details['DURATION'];
			$i=0;
                        $services=service_assign_toarray($service_main_details,$services,$type,$i);
                        $smarty->assign("MAIN_PRICE",$services[$i]["price"]);
/****** if User clicks on remove service on bill page, then removing selected service *****/
                        if($voicemail)
                        {
                                $i++;
                                $voicemailid=$voicemail.$duration;
                                $voicemail_details= getServiceDetails($voicemailid);
                                $services=service_assign_toarray($voicemail_details,$services,$type,$i);
                        	$smarty->assign("voicemail_price",$services[$i]["price"]);
                        }
                        if($horoscope)
                        {
                                $i++;
                                $horoscopeid=$horoscope.$duration;
                                $horoscope_details= getServiceDetails($horoscopeid);
                                $services=service_assign_toarray($horoscope_details,$services,$type,$i);
                        	$smarty->assign("horoscope_price",$services[$i]["price"]);
                        }
                        if($kundli)
                        {
                                $i++;
                                $kundliid=$kundli.$duration;
                                $kundli_details= getServiceDetails($kundliid);
                                $services=service_assign_toarray($kundli_details,$services,$type,$i);
                        	$smarty->assign("kundli_price",$services[$i]["price"]);
                        }
                        if($boldlisting)
                        {
                                $i++;
                                $boldlistingid=$boldlisting.$duration;
                                $boldlisting_details= getServiceDetails($boldlistingid);
                                $services=service_assign_toarray($boldlisting_details,$services,$type,$i);
                        	$smarty->assign("boldlisting_price",$services[$i]["price"]);
                        }
                        if($matri_profile)
                        {
                                $i++;
                                $matri_profileid=$matri_profile.$duration;
                                $matri_profile_details= getServiceDetails($matri_profileid);
                                $services=service_assign_toarray($matri_profile_details,$services,$type,$i);
                        	$smarty->assign("matri_profile_price",$services[$i]["price"]);
                        }
                        for($i=0;$i<count($services);$i++)
                        {
                                $subtotal += $services[$i]["price"]; //Price for selected service
                        }

			$prev_status=getSubscriptionStatus($profileid);
                        $festive_discount=0;
                        /*                                                                                                     
                        //code added by sriram for giving discount depending on score.
                        $score = get_special_discount($profileid);
                        if(!$prev_status && $service =='P3' && $score)
                        {
                                $score_discount_rate = 40;
                                $discount_value=round((($score_discount_rate/100)*$subtotal),2);
                                $subtotal2=$subtotal-$discount_value;
                                if($type=="DOL")
                                        $total=round($subtotal2);
                                else
                                        $total=floor($subtotal2);
                                                                                                                             
                                if($festive_discount)
                                        $smarty->assign("FESTIVE","Y");
                                                                                                                             
                                $smarty->assign("DISCOUNT","Y");
                                $smarty->assign("SUBTOTAL2",$subtotal2);
                                $smarty->assign("DISCOUNT_VALUE",$discount_value);
                                $smarty->assign("SCORE_DISCOUNT_RATE",$score_discount_rate);
                        }
			//Code for giving discount to previosly subscribed users
                        elseif($prev_status || $festive_discount)*/
                        if($prev_status || $festive_discount)
                        {
                                $discount_value=round((($renew_discount_rate/100)*$subtotal),2);
                                $subtotal2=$subtotal-$discount_value;
                                if($type=="DOL")
                                        $total=round($subtotal2);
                                else
                                        $total=floor($subtotal2);
                                                                                                                             
                                if($festive_discount)
                                        $smarty->assign("FESTIVE","Y");
                                                                                                                             
                                $smarty->assign("DISCOUNT","Y");
                                $smarty->assign("SUBTOTAL2",$subtotal2);
                                $smarty->assign("DISCOUNT_VALUE",$discount_value);
                                $smarty->assign("RENEW_DISCOUNT_RATE",$renew_discount_rate);
                        }
			else
                        {
                                if($type=="DOL")
                                        $total=round($subtotal);
                                else
                                        $total=floor($subtotal);
                                                                                                                             
                                $smarty->assign("DISCOUNT","N");
                        }
                        if(count($paid_service)>0)
                                $smarty->assign("SERVICE_STR",implode(",",$paid_service));
                        if(count($addon)>0)
                                $smarty->assign("ADDON",implode(",",$addon));
                        $smarty->assign("ROW",$services);
                        $smarty->assign("TYPE",$type);
                        $smarty->assign("SUBTOTAL",$subtotal);
                        $smarty->assign("TAX",$tax);
                        $smarty->assign("TAX_RATE",$tax_rate);
                        $smarty->assign("TOTAL",$total);
                        $smarty->assign("PAYMODE",$paymode);
                        $smarty->assign("SERVICE_MAIN",$services[0]["value"]);
/*********************************************************************************************************************/

                        $smarty->display("payment2.htm");
			exit();
		}
	}
	if($checkout2)
	{

	/**** Code for error validation *****************/
		$error=0;
		if(!$service)
		{
			$error++;
			$smarty->assign("CHECK_SERVICE","Y");
		}
		if(!$paymode)
		{
			$error++;
			$smarty->assign("CHECK_PAYMODE","Y");
		}
	/**** Code for error validation Ends Here*****************/
		
		if($error>0)
		{
		/*********** If error then rethrowing the page with selected values ******/
			$services_list=getServices($type,$ser_main);
			$smarty->assign("SERVICE_SELECTED",$service);		
			$ser_det=getServiceDetails($service);
                        $smarty->assign("SERVICE_DUR",$ser_det["DURATION"]);
			$smarty->assign("SER_MAIN",$ser_main);
			$smarty->assign("SERVICES_LIST",$services_list);		
			$smarty->assign("TYPE",$type);
			$smarty->assign("PAYMODE",$paymode);
			$smarty->assign("VOICEMAIL",$voicemail);
			$smarty->assign("HOROSCOPE",$horoscope);
			$smarty->assign("BOLDLISTING",$boldlisting);
			$smarty->assign("MATRI_PROFILE",$matri_profile);
			$smarty->assign("KUNDLI",$kundli);
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("DEC_AG",$dec_ag);
			$smarty->assign("DISCOUNT",$DISCOUNT);
			$smarty->assign("SUBTOTAL",$subtotal);
			$smarty->assign("TOTAL",$total);
			$smarty->assign("TYPE",$type);
			$smarty->assign("RENEW_DISCOUNT_RATE",$renew_discount_rate);
			$smarty->assign("SCORE_DISCOUNT_RATE",$score_discount_rate);
			$smarty->assign("MAIN_PRICE",$main_price);
			$smarty->assign("boldlisting_price",$boldlisting_price);
			$smarty->assign("matri_profile_price",$matri_profile_price);
			$smarty->assign("kundli_price",$kundli_price);
			$smarty->assign("horoscope_price",$horoscope_price);
			$smarty->assign("horoscope_price",$horoscope_price);
			$smarty->display("payment2.htm");
		}
		else
		{
			if($subscription != '')
			{
				// getting prvious subscription status to give renewal discount
				$renew_status=getRenewStatus($profileid);
				if($renew_status)
				{
					$curdate=date('Y-m-d');
					$days_left_expire= getTimeDiff($curdate,$renew_status['EXPIRY_DT']); //Renewal not allowed before 10 days of current subscription	
					if($days_left_expire > 0)
					{
						if($days_left_expire >10)
						{
							list($year,$month,$day)= explode("-",$renew_status['EXPIRY_DT']);
							$exp_dt=my_format_date($day,$month,$year);
							$smarty->assign("exp_dt",$exp_dt);
							$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
							$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
							$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
							$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
							$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
							$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
							$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
							$smarty->display("ALERT.htm");

							die();
						}
						else
						{
	// field set to mark entry in SERVICE_STATUS after current subscription expiry date
							$smarty->assign("SETACTIVATE","Y");
						}
					}
				}
			}
			$i=0;
			//$service=$ser_main.$ser_duration;
			$service_main_details=getServiceDetails($service);
			$duration=$service_main_details['DURATION'];
			$services=service_assign_toarray($service_main_details,$services,$type,$i);	
			unset($addon);
/****** if User clicks on remove service on bill page, then removing selected service *****/
			if($rem=="Y")
			{
				$paid_service_orig=explode(",",$service_str);
				$paid_service_rem[]=substr($rem_ser,0,1);
				$paid_service1=array_values(array_diff($paid_service_orig,$paid_service_rem));
				if(in_array("H",$paid_service1))
					$horoscope="H";
				if(in_array("B",$paid_service1))
					$boldlisting="B";
				if(in_array("K",$paid_service1))
					$kundli="K";
				if(in_array("V",$paid_service1))
					$voicemail="V";
				if(in_array("M",$paid_service1))
					$matri_profile="M";
			}	
			if($voicemail)
			{
				$i++;
				$voicemailid=$voicemail.$duration;
				$voicemail_details= getServiceDetails($voicemailid);
				$services=service_assign_toarray($voicemail_details,$services,$type,$i);
				$paid_service[]=$voicemail;	
				$addon[]=$voicemail;
			}
			if($horoscope)
			{
				$i++;
				$horoscopeid=$horoscope.$duration;
				$horoscope_details= getServiceDetails($horoscopeid);
				$services=service_assign_toarray($horoscope_details,$services,$type,$i);	
				$paid_service[]=$horoscope;	
				$addon[]=$horoscope;
			}
			if($kundli)
			{
				$i++;
				$kundliid=$kundli.$duration;
				$kundli_details= getServiceDetails($kundliid);
				$services=service_assign_toarray($kundli_details,$services,$type,$i);	
				$paid_service[]=$kundli;
				$addon[]=$kundli;
			}
			if($boldlisting)
			{
				$i++;
				$boldlistingid=$boldlisting.$duration;
				$boldlisting_details= getServiceDetails($boldlistingid);
				$services=service_assign_toarray($boldlisting_details,$services,$type,$i);	
				$paid_service[]=$boldlisting;
				$addon[]=$boldlisting;
			}
			if($matri_profile)
			{
				$i++;
				$matri_profileid=$matri_profile.$duration;
				$matri_profile_details= getServiceDetails($matri_profileid);
				$services=service_assign_toarray($matri_profile_details,$services,$type,$i);	
				$paid_service[]=$matri_profile;
				$addon[]=$matri_profile;
			}
			unset($subtotal);
			for($i=0;$i<count($services);$i++)
			{
				$subtotal += $services[$i]["price"]; //Price for selected service
			}

//Code for giving discount to previosly subscribed users

//code for diwali festive discount
			/*$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>''";
			$res_city=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row_city=mysql_fetch_array($res_city))
			{
				$crm_city_arr[]=$row_city['VALUE'];
			}*/

			//if(is_array($crm_city_arr))
			//{
				//if(in_array($city_res,$crm_city_arr) && $duration>=5)
				/*$sh_date=date("Y-m-d H:i:s");
				if($sh_date<='2005-10-31 13:30:00')
				{
					if($duration>=5)
					{
						$festive_discount=1;
					}
				}*/
			//}

			$prev_status=getSubscriptionStatus($profileid);
                        $festive_discount=0;
                      /*  //code added by sriram for giving discount depending on score.
                        $score = get_special_discount($profileid);
                        if(!$prev_status && $service=='P3' && $score)
                        {
                                $score_discount_rate = 40;
                                $discount_value=round((($score_discount_rate/100)*$subtotal),2);
                                $subtotal2=$subtotal-$discount_value;
                                if($type=="DOL")
                                        $total=round($subtotal2);
                                else
                                        $total=floor($subtotal2);
                                                                                                                             
                                if($festive_discount)
                                        $smarty->assign("FESTIVE","Y");
                                                                                                                             
                                $smarty->assign("DISCOUNT","Y");
                                $smarty->assign("SUBTOTAL2",$subtotal2);
                                $smarty->assign("DISCOUNT_VALUE",$discount_value);
                                $smarty->assign("SCORE_DISCOUNT_RATE",$score_discount_rate);
                        }
			//Code for giving discount to previosly subscribed users
                        elseif($prev_status || $festive_discount)*/
                        if($prev_status || $festive_discount)
                        {
                                $discount_value=round((($renew_discount_rate/100)*$subtotal),2);
                                $subtotal2=$subtotal-$discount_value;
                                if($type=="DOL")
                                        $total=round($subtotal2);
                                else
                                        $total=floor($subtotal2);
                                                                                                                             
                                if($festive_discount)
                                        $smarty->assign("FESTIVE","Y");
                                                                                                                             
                                $smarty->assign("DISCOUNT","Y");
                                $smarty->assign("SUBTOTAL2",$subtotal2);
                                $smarty->assign("DISCOUNT_VALUE",$discount_value);
                                $smarty->assign("RENEW_DISCOUNT_RATE",$renew_discount_rate);
                        }
                        else
                        {
                                if($type=="DOL")
                                        $total=round($subtotal);
                                else
                                        $total=floor($subtotal);
                                                                                                                             
                                $smarty->assign("DISCOUNT","N");
                        }
			            $discount_without_tax=round(($discount_value*100)/(100+$tax_rate),2);
                        $smarty->assign("discount_without_tax",$discount_without_tax);

			if(count($paid_service)>0)	
				$smarty->assign("SERVICE_STR",implode(",",$paid_service));
			if(count($addon)>0)
                                $smarty->assign("ADDON",implode(",",$addon));
			$smarty->assign("ROW",$services);	
			$smarty->assign("TYPE",$type);	
	                $smarty->assign("SUBTOTAL",$subtotal);
	                $smarty->assign("TAX",$tax);
	                $smarty->assign("TAX_RATE",$tax_rate);
        	        $smarty->assign("TOTAL",$total);
        	        $smarty->assign("PAYMODE",$paymode);
        	        $smarty->assign("SERVICE_MAIN",$services[0]["value"]);

/**************to select payment gateway # CCAVENUE OR TRANSECUTE*********************/
// These lines commented to implement net banking options

			$sql="SELECT ACTIVE from billing.PAYMENT_GATEWAY";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_array($result);
			$action=$myrow['ACTIVE'];

//			if($profileid=='136580')  // for testing CCAvenue through test4js
//				$action="CCAVENUE";

/*			if($action=="CCAVENUE")
				$action_path="pg/orderonline.php";
			elseif($action=="TRANSECUTE")
				$action_path="pg/transecute/order_transecute.php";
*/		

//Forcefully directing requests to specific gateways on request	
// 	Paypal discontinued on 17th Nov 2005
			if($paymode == "pcard")
			{
				$action="PAYPAL";
				$mode_name="Pay Pal";
				$action_path="pg/order_paypal.php";
			}
			// payseal stopped by shiv on 22nd Jan - Sunday - 6:55 pm
			elseif($type == "RS" && $paymode == "card")
			{
				$action="PAYSEAL";
				$mode_name="Credit Cards: Visa / Master Card";
                                $action_path="pg/order_payseal.php";
			}
			//elseif($type == "RS" && $paymode == "card3" && $profileid=="156193")
			elseif($type == "RS" && $paymode == "card3")
                        {
                                $action="ITZ";
				$mode_name="ITZ Cash Cards";
                                $action_path="pg/order_itz.php";
                        }
                        elseif($type == "RS" && $paymode == "card4")
                        //elseif($type == "RS" && $paymode == "card4" && $profileid=="62")
                        {
                                $action="PAYMATE";
				$mode_name="Pay through Mobile: Only for Citibank customers";
                                $action_path="pg/order_paymate.php";
                                $smarty->assign("PAYMATE",'Y');
                        }
			//elseif($paymode == "card" || $paymode == "cheque")
			elseif($paymode == "card")
			{
				//forcefully stop transecute by shiv at 26thDec 12:23pm
				// forceful stop from transecute removed by shiv on 23rd dec 1:12pm ist
				$action="TRANSECUTE";
				$action_path="pg/transecute/order_transecute.php";
			}
			elseif($paymode == "cheque")
			{
				//forcefully stop transecute by shiv at 26thDec 12:23pm
				// forceful stop from transecute removed by shiv on 23rd dec 1:12pm ist
			//	$action="TRANSECUTE";
				$action_path="pg/transecute/cheque_request.php";
				$mode_name="Cheque and Draft";
			}
			elseif($paymode == "easybill")
                        {
                                //forcefully stop transecute by shiv at 26thDec 12:23pm
                                // forceful stop from transecute removed by shiv on 23rd dec 1:12pm ist
                                $action="Easy Bill";
                                $action_path="easy_bill.php";
                                $mode_name="Easy Bill";
                        }
			else
			{
				$action="CCAVENUE";
				if($paymode=="card1")
					$mode_name="Net Banking and Online Transfer";
				else
					$mode_name="Credit Cards: American Express / Diners Club";
				$action_path="pg/orderonline.php";
			}	
			/*if($profileid=="144111")
                        {
                                //forcefully stop transecute by shiv at 26thDec 12:23pm
                                // forceful stop from transecute removed by shiv on 23rd dec 1:12pm ist
                                //$action="TRANSECUTE";
				$action="PAYPAL";
                                //$action_path="pg/transecute/order_transecute.php";
				//$action_path="pg/orderonline.php";
				$action_path="pg/order_paypal.php";
				//$smarty->assign("TOTAL","10.00");
				$smarty->assign("TOTAL","10690.00");
                        }*/

			//if($type=="DOL")
			// dol payments send to payseal on 14th feb 2006
			if(is_array($paypal_country_array))
			{
				if(in_array($country_res,$paypal_country_array) && $paymode!="pcard" && $paymode!="card2" && $paymode!="card1" && $paymode!="card4")
				{
					$action="PAYSEAL";
					$mode_name="Credit Cards: Visa / Master Card";
					$action_path="pg/order_payseal.php";
					//$action="PAYPAL";
					//$action_path="pg/order_paypal.php";
				}
			}

			//$action_path="pg/orderonline.php";
                        if($paymode=="cheque")
                        {
                                $action="";
                                $smarty->assign("CHEQUE","Y");
				$mode_name="Cheque and Draft";
				
				$action_path="pg/transecute/cheque_request.php";
                                //$action_path="pg/transecute/order_transecute.php";
                        }
			if($paymode=="airex")
                        {
                                $smarty->assign("REQ_COURIER","Y");
                        }

			$smarty->assign("ACTION_PATH",$action_path);
			$smarty->assign("GATEWAY",$action);
			$smarty->assign("MODE_NAME","$mode_name");
/*****************************Ends here ***********************************************/
	
			savehits_payment($profileid,'4');
			$ip = FetchClientIP();

			include("suspected_ip.php");
			$suspected_check=doubtfull_ip("$ip");

			if($suspected_check)
				send_email('vikas@jeevansathi.com',$profileid,"Payment Tried by Profileid of suspected email-id","payment@jeevansathi.com");

			$subject = "Bill for your online subscription";

			$smarty->assign("PROFILEID",$profileid);
			//section added by Gaurav on 7 July for integrating ITZ and PAYmate
			$smarty->assign("SERVICE_SELECTED",$service);		
			$smarty->assign("VOICEMAIL",$voicemail);
                        $smarty->assign("HOROSCOPE",$horoscope);
                        $smarty->assign("BOLDLISTING",$boldlisting);
                        $smarty->assign("MATRI_PROFILE",$matri_profile);
                        $smarty->assign("KUNDLI",$kundli);
			//end of section added by Gaurav on 7 July for integrating ITZ and PAYmate

			$smarty->assign("SER_MAIN",$ser_main);
			$smarty->assign("SER_DURATION",$duration);
			$smarty->assign("VOICEMAILID",$voicemailid);
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->display("payment_1.htm");
		}	
	}
	else
	{
		savehits_payment($profileid,'2');
		$ser_duration="6";
		$services_list=getServices($type,$ser_main);  // setting array for services to be displayed
		$smarty->assign("SERVICES_LIST",$services_list);
		$smarty->assign("SERVICES_NAME",$services_list['NAME']);
		$smarty->assign("SERVICES_PRICE",$services_list['PRICE']);
		$smarty->assign("TYPE",$type);
		$smarty->assign("SER_MAIN",$ser_main);
		$smarty->assign("SER_DURATION",$ser_duration);
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("DOL_CONV_RATE",$DOL_CONV_RATE); //Global variable
		$smarty->assign("PROFILEID",$profileid);
		$smarty->assign("SERVICE_SELECTED",$service);
		$smarty->assign("PAYMODE",$paymode);
		$smarty->assign("VOICEMAIL",$voicemail);
		$smarty->assign("HOROSCOPE",$horoscope);
		if($service!="")
			$smarty->assign("BOLDLISTING",$boldlisting);
		else
			$smarty->assign("BOLDLISTING",'B');
		$smarty->assign("MATRI_PROFILE",$matri_profile);
		$smarty->assign("KUNDLI",$kundli);

		$smarty->display("payment.htm");
	}
}
else
{
	$smarty->assign("LOGIN_PAGE_KAHANI","UPGRADE");
	
	TimedOut("Thank you for your keen interest. To upgrade to a FULL membership, please log in to your account. If you are a new user, then REGISTER NOW!");
}

/***********************************************************************************
This function is selecting main services and their prices and returning it in array
***********************************************************************************/
function getServices($type,$ser_main)
{
	$sql="SELECT NAME, SERVICEID, ";
	if($type=="DOL")
		$sql .= "PRICE_DOL as PRICE";
	else
		$sql .= "PRICE_RS_TAX as PRICE";
											 
	$sql .= " from billing.SERVICES where PACKAGE='Y' and ADDON ='N' and SERVICEID like '".$ser_main."%' and SHOW_ONLINE='Y' order by ID DESC";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result))
	{
		$duration=ltrim($myrow["SERVICEID"],$ser_main)." Months";
		$services_list[] = array("NAME" =>$myrow["NAME"],
					"SERVICEID"=>$myrow["SERVICEID"],
					"PRICE"=>$myrow["PRICE"],
					"DURATION"=>$duration
					);
	}
	return $services_list;
}

/***********************************************************************************
This function is making an aaray for selected services( main + addons) and then returning the services array with their prices, name and SERVICEID.  This returned array is then smarty assigned from the calling script to display the Invoice on next page.
***********************************************************************************/
function service_assign_toarray($service_details,$assignto,$type,$index)
{
	$assignto[$index]["value"]=$service_details["SERVICEID"];
	if(strstr($service_details["SERVICEID"],'P'))
		$assignto[$index]["name"]="e-Rishta";
	elseif(strstr($service_details["SERVICEID"],'D'))
		$assignto[$index]["name"]="e-Classifieds";
	elseif(strstr($service_details["SERVICEID"],'C'))
                $assignto[$index]["name"]="e-ValuePack";
	elseif(strstr($service_details["SERVICEID"],'B'))
                $assignto[$index]["name"]="Bold-Listing";
	elseif(strstr($service_details["SERVICEID"],'M'))
                $assignto[$index]["name"]="Matri-Profile";
	else
	$assignto[$index]["name"]=$service_details["NAME"];

	$assignto[$index]["DURATION"]=$service_details["DURATION"]." Months";
	if($type=="DOL")
		$assignto[$index]["price"]=$service_details["PRICE_DOL"];
	else
		$assignto[$index]["price"]=$service_details["PRICE_RS_TAX"];
	return $assignto;
}
function savehits_payment($profileid,$pg_no)
{
	$sql_hit="INSERT into billing.PAYMENT_HITS(PROFILEID,PAGE,ENTRY_DT) values('$profileid','$pg_no',now())";
	mysql_query_decide($sql_hit);
}

/*function get_special_discount($profileid)
{
        //finding the score for the user.
        $sql_score = "SELECT PROFILEID FROM billing.SCORE_MAILER WHERE PROFILEID='$profileid'";
        $res_score = mysql_query_decide($sql_score) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row_score = mysql_fetch_array($res_score);
        if($row_score['PROFILEID'] >0)
                return 1;
        else
                return 0;
}*/
	
?>
