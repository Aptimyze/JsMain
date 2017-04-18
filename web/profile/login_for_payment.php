<?php
include("connect.inc");
include("pg/functions.php");
$db=connect_db();
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
$memObj = new Membership;
$serObj = new Services;
$id_arr=explode("i",$id);
$id_sel=$id_arr[1];
if(md5($id_arr[1])!=$id_arr[0])
{
	die("invalid URL");
}

if($username && $password)
{
	$data=login($username,$password);
}
if($password && !$data)
{
	$smarty->assign("CHECK_PASSWORD","Y");
	$smarty->assign("PASSWORD",$password);
}
else
	$data1=authenticated($checksum);
	
	
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
$db=connect_db();
if($id)
{
	
			$sql="SELECT PROFILEID,USERNAME,SERVICE,ADDON_SERVICEID,DISCOUNT from incentive.PAYMENT_COLLECT where ID='$id_sel'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_array($result);
			$profileid=$myrow["PROFILEID"];
			$username=$myrow["USERNAME"];
			$service=$myrow["SERVICE"];
			$addon_ser=$myrow["ADDON_SERVICEID"];
			if($addon_ser!='')
			{
				$service2=$service;
				$service3=$service.",".$addon_ser;
			}
			else
			{
				$service2=$service;
				$service3=$service;
			}
			$spl_discount=$myrow["DISCOUNT"];
	if($profileid)
        {
                $sql="SELECT COUNTRY_RES,CITY_RES,INCOMPLETE, SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$profileid'";                
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow=mysql_fetch_array($result);
                $incomplete=$myrow['INCOMPLETE'];
                $subscription=$myrow['SUBSCRIPTION'];
                $country_res=$myrow['COUNTRY_RES'];
                $city_res=$myrow['CITY_RES'];
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

			if($addon_ser)
			{
				$arr =explode(",",$addon_ser);
				for($i = 0;$i<count($arr);$i++)
					if($arr[$i])
					{
						$var=$arr[$i];
						if(strstr($var,'B'))
						{
							$boldlisting="B";
							 $bold=$var;
						}
						if(strstr($var,'A'))
						{
							$aonly=$var;
							$astro_compatiblity="A";
						}
						if(strstr($var,'M'))
						{
							$cb5=$var;
							$matri_profile="M";
						}

					}

			}

			$i=0;
			//$service=$ser_main.$ser_duration;
			$service_main_details=getServiceDetails($service);
			$duration=$service_main_details['DURATION'];
			$services=service_assign_toarray($service_main_details,$services,$type,$i);
		//	print_r($services);
			if($services[0][value]=='')
				$i=-1;
			if($boldlisting)
			{
				$i++;
				$boldlistingid=$bold;
				$boldlisting_details= getServiceDetails($boldlistingid);
				$services=service_assign_toarray($boldlisting_details,$services,$type,$i);	
				$paid_service[]=$boldlisting;
				$addon[]=$boldlisting;
			}
			if($matri_profile)
			{
				$i++;
				$matri_profileid=$cb5;
				$matri_profile_details= getServiceDetails($matri_profileid);
				$services=service_assign_toarray($matri_profile_details,$services,$type,$i);	
				$paid_service[]=$matri_profile;
				$addon[]=$matri_profile;
			}
			if($astro_compatiblity)
			{
				$i++;
				$astro_compatiblityid=$aonly;
				$astro_compatiblity_details= getServiceDetails($astro_compatiblityid);
				$services=service_assign_toarray($astro_compatiblity_details,$services,$type,$i);	
				$paid_service[]=$astro_compatiblity;
				$addon[]=$astro_compatiblity;
			}
			for($i=0;$i<count($services);$i++)
			{
                                if(strstr($services[$i]['value'],'P')||strstr($services[$i]['value'],'C'))
                                        $price1=$services[$i]['price'];
				$subtotal+= $services[$i]["price"]; //Price for selected service
			}
//Code for giving discount to previosly subscribed users
			$prev_status=getSubscriptionStatus($profileid);
			$paymode="card";

			$festive_discount=0;
			if($prev_status || $spl_discount)
			{ 
				if($prev_status)
					$discount_value=round((($renew_discount_rate/100)*$price1),2);
				$subtotal2=$subtotal-$discount_value;
				if($type=="RS")
					$subtotal2=$subtotal2-$spl_discount;
				if($type=="DOL")
				{
					$spl_discount=0;
        	        	        $total=round($subtotal2);
				}
		
				else
	                	        $total=floor($subtotal2);

				$total_discount=$discount_value+$spl_discount;
				$total_discount_save=floor(($total_discount*100)/(100+$tax_rate));
				if($spl_discount)
					$smarty->assign("FESTIVE","Y");
				if($discount_value>0)
					$smarty->assign("DISCOUNT","Y");
				$smarty->assign("SUBTOTAL2",$subtotal2);
				$smarty->assign("DISCOUNT_VALUE",$discount_value);
				$smarty->assign("SPL_DISCOUNT_VALUE",$spl_discount);
				$smarty->assign("RENEW_DISCOUNT_RATE",$renew_discount_rate);
				$smarty->assign("TOTAL_DISCOUNT",$total_discount_save);
				$total_discount_inctax=ceil($total_discount_save*((100+$tax_rate)/100));
				$smarty->assign("TOTAL_DISCOUNT_INCTAX",$total_discount_inctax);
				$smarty->assign("chk_ids",(($total_discount_inctax*23)+57));
				$spt=md5($total_discount_inctax);
				$smarty->assign("spt",$spt);
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
			$smarty->assign("BcKlinK",1);

			if($type == "RS" && $paymode == "card")
			{
				$action="PAYSEAL";
                                $action_path="pg/order_payseal.php";
			}

			$smarty->assign("ACTION_PATH",$action_path);
/*****************************Ends here ***********************************************/
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("USERNAME",$username);
			$smarty->assign("SERVICE_SELECTED",$service);		
			$smarty->assign("VOICEMAIL",$voicemail);
                        $smarty->assign("HOROSCOPE",$horoscope);
                        $smarty->assign("BOLDLISTING",$boldlisting);
                        $smarty->assign("MATRI_PROFILE",$matri_profile);
                        $smarty->assign("ASTRO_COMPATIBLITY",$astro_compatiblity);
                        $smarty->assign("KUNDLI",$kundli);

			$smarty->assign("SER_MAIN",$ser_main);
			$smarty->assign("SER_DURATION",$ser_duration);
			$smarty->assign("VOICEMAILID",$voicemailid);
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("revamp_head.htm"));
                       // $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
			$smarty->assign("ID",$id);
			if($data1["USERNAME"]==$username)
				$smarty->assign("logged_in","Y");
			if($data || $confirm_x)
			{
				$smarty->assign("service_main",$service2);
				$arr =explode(",",$services2);
				for($i = 0;$i<count($arr);$i++)
				if($arr[$i])
				{
					$var=$arr[$i];
					if(strstr($var,'P'))
					{
						 $main_service2=$var;
					}
					if(strstr($var,'C'))
					{
						 $main_service2=$var;
					}
				}
				$smarty->assign("SER_MAIN",$main_service2);
				$smarty->assign("SERVICE2",$service2);
				$smarty->assign("SERVICE3",$service3);

				$smarty->display("login_for_payment_redirect.htm");
			}
			else
				$smarty->display("login_for_payment.htm");
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
											 
//	$sql .= " from billing.SERVICES where PACKAGE='Y' and ADDON ='N' and SERVICEID like '".$ser_main."%' AND SERVICEID not in ('P1','D1','C1') order by ID DESC";
	//$sql .= " from billing.SERVICES where PACKAGE='Y' and ADDON ='N' order by ID DESC";
	$sql .= " from billing.SERVICES where PACKAGE='Y' and ADDON ='N' and SERVICEID like '".$ser_main."%' and SHOW_ONLINE_NEW LIKE '%,-1,%' order by ID DESC";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result))
	{
		$services_list[] = array("NAME" =>$myrow["NAME"],
					"SERVICEID"=>$myrow["SERVICEID"],
					"PRICE"=>$myrow["PRICE"]
					);
		//$serviceidarr[]=$myrow["SERVICEID"];
		//$servicepricearr[]=$myrow["PRICE"];
	}
	/*if($serviceidarr)
	{
		$services_list["NAME"]=implode(",",$serviceidarr);
		$services_list["PRICE"]=implode(",",$servicepricearr);
	}*/
	return $services_list;
}

/***********************************************************************************
This function is making an aaray for selected services( main + addons) and then returning the services array with their prices, name and SERVICEID.  This returned array is then smarty assigned from the calling script to display the Invoice on next page.
***********************************************************************************/
function service_assign_toarray($service_details,$assignto,$type,$index)
{
	$assignto[$index]["value"]=$service_details["SERVICEID"];
	$assignto[$index]["name"]=$service_details["NAME"];
	if($type=="DOL")
		$assignto[$index]["price"]=$service_details["PRICE_DOL"];
	else
		$assignto[$index]["price"]=$service_details["PRICE_RS_TAX"];
	return $assignto;
}
?>
