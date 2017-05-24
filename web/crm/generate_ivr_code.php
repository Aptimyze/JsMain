<?php
ini_set('display_errors','On');
include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/crm/func_sky.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
if(authenticated($cid))
{
	$name= getname($cid);
	$center=get_centre($cid);
	$smarty->assign("name",$name);
	$serviceObj = new Services;
	$membershipObj = new Membership;
	if($submit)
	{
		$is_error=0;
		if($SERVICE[0]=="")
		{
			$smarty->assign("check_service","Y");
                        $is_error++;
		}
		else
                {
			
                        if(strstr($service_str,'B') && ((!strstr($service_str,'P') && !strstr($service_str,'C'))))
                        {
                                {
                                        $smarty->assign("check_service","Y");
                                        $msg="Boldlisting comes with E-value or E-rishta pack";
                                        $is_error++;
                                }
                        }
                        elseif(strstr($service_str,'P') && strstr($service_str,'C'))
                        {
                                $smarty->assign("check_service","Y");
                                $msg="Select only one main service";
                                $is_error++;
                        }
                        elseif(substr_count($service_str,'B')>1 || substr_count($service_str,'P')>1 || substr_count($service_str,'C')>1 || substr_count($service_str,'A')>1 || substr_count($service_str,'O')>1 || substr_count($service_str,'S')>1 || substr_count($service_str,'D')>1 || substr_count($service_str,'I')>1 || substr_count($service_str,'T')>1 || substr_count($service_str,'L')>1)
                        {
                                $smarty->assign("check_service","Y");
                                $msg="Select one option for one service";
                                $is_error++;
                        }
			elseif($discount)
			{
				if(!strstr('C',$service_str) && !strstr('P',$service_str))
				{
					$is_error++;
                                	$smarty->assign("check_discount","Y");
                                	$smarty->assign("DISCOUNT",$discount);
				}
				else
				{
					foreach($SERVICE as $k=>$v)
	                                {
        	                                if(strstr($v,'C') || strstr($v,'P'))
                	                        	$main_service=$v;
					}

                        		$sql_pr = " Select SUM(desktop_RS) AS PRICE_RS_TAX from billing.SERVICES where SERVICEID=$main_service ";
                        		$result_pr = mysql_query_decide($sql_pr) or die($sql.mysql_error_js());
                        		$myrow_pr=mysql_fetch_array($result_pr);
                        		$max_limit= 0.25*$myrow_pr["PRICE_RS_TAX"];
                        		if($discount>$max_limit)
                        		{
                        		        $is_error++;
                        		        $smarty->assign("check_discount","Y");
                        		        $smarty->assign("DISCOUNT",$discount);
                        		}
				}
			}
			else
                        {
                                $smarty->assign("check_service","Y");
                                //$msg=check_service($SERVICE,$profileid);
                                $msg=$membershipObj->checkRange($profileid,$SERVICE);
                                if($msg!='')
                                        $is_error++;
                        }

                }
		if($is_error>=1)
		{

			$service_main=$serviceObj->getAllServices_crm();
			$smarty->assign("SERVICE_MAIN",$service_main);
			$smarty->assign("USERNAME",stripslashes($USERNAME));
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("SERVICE",$SERVICE);
			$smarty->assign("msg",$msg);
			$smarty->assign("cid",$cid);
			$smarty->display("generate_ivr_code.htm");
		}
		else
		{
			$services=$serviceObj->get_matri_duration($SERVICE);
                        $str_service= implode("','",$services);
                        $SERVICE= implode(",",$services);
			$service_arr=$serviceObj->getServicesAmount($SERVICE,'RS');
                        foreach($service_arr as $k=>$v)
                        {
				if(strstr($service_arr[$k]['SERVICEID'],'P')||strstr($service_arr[$k]['SERVICEID'],'C'))
					$price1+=$service_arr[$k]['PRICE'];
                                if($ser_names=='')
                                        $ser_names=$service_arr[$k]['NAME'];
                                else
                                        $ser_names.=", ".$service_arr[$k]['NAME'];
                                $price+=$service_arr[$k]['PRICE'];
                        }

			$prev_paid=getSubscriptionStatus($profileid);
			if($prev_paid)
				$discount_value=round((($renew_discount_rate/100)*$price1),2);
			elseif($discount)
				$discount_value = $discount;
			$total_amt=$price-$discount_value;

			$amount = $type.". ".$total;

			/*Code to generate and save IVR code.*/
			$sql_ins = "INSERT INTO billing.IVR_DETAILS(PROFILEID,USERNAME,SERVICEID,ADDON_SERVICEID,TYPE,AMOUNT,DISCOUNT,ENTRY_DT,GENERATED_BY) VALUES('$profileid','$USERNAME','$SERVICE','$addon_services_str','$type','$total_amt','$discount_value',now(),'$name')";
			mysql_query_decide($sql_ins) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins,"ShowErrTemplate");

			$ivr_code = mysql_insert_id_js();
			/*End of - Code to generate and save reference id.*/
			
			$msg .= "Booking reference number = $ivr_code<br>"."Service Price = $price $type";
			if($discount_value>0)
				$msg .= ", Renewal Discount = $discount_value $type, Total =$total_amt $type";
			$msg.="<br> Service Name: $ser_names";

			$msg .= "<br><a href=\"mainpage.php?name=$name&cid=$cid\">";
			$msg .= "Continue &gt;&gt;</a>";
		
			$smarty->assign("URL","$URL");	
			$smarty->assign("USER",$name);
			$smarty->assign("USERNAME","$USERNAME");
			$smarty->assign("SERVICE_STR","$service_str");
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("incentive_msg.tpl");
		}	
	}
	else
	{
		$sql="SELECT USERNAME,INCOMPLETE from newjs.JPROFILE where PROFILEID='$pid'";
		$result = mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($result);
		if($row["INCOMPLETE"]=="Y")
		{
			$msg="This user's profile is incomplete.So payment request can't be generated";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
			die();
		}
		$username=$row["USERNAME"];		
		/*$sql = "SELECT SERVICEID, NAME from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' ";
		$result_service_main = mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow_service_main = mysql_fetch_array($result_service_main))
		{
			$service_main[] = array("SERVICEID"=>$myrow_service_main["SERVICEID"],
					"NAME"=>$myrow_service_main["NAME"]);
		}
		*/
		if(!getSubscriptionStatus($pid))
			$smarty->assign("ALLOW_DISCOUNT",1);
		$service_main=$serviceObj->getAllServices_crm();
		$smarty->assign("SERVICE_MAIN",$service_main);
		$smarty->assign("USERNAME",stripslashes($username));
		$smarty->assign("PROFILEID",$pid);
        	$smarty->assign("cid",$cid);
        	$smarty->assign("name",$name);
	        $smarty->display("generate_ivr_code.htm");
	}
}
else//user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
