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
                        $ser_str= implode(",",$SERVICE);
                        if(strstr($ser_str,'B') && (!strstr($ser_str,'P') && !strstr($ser_str,'C')))
                        {
                                        $smarty->assign("check_service","Y");
                                        $msg="Boldlisting comes with E-value or E-rishta pack";
                                        $is_error++;
                        }
                        elseif(strstr($ser_str,'P') && strstr($ser_str,'C'))
                        {
                                $smarty->assign("check_service","Y");
                                $msg="Select only one main service";
                                $is_error++;
                        }
                        elseif(substr_count($ser_str,'B')>1 || substr_count($ser_str,'P')>1 || substr_count($ser_str,'C')>1 || substr_count($ser_str,'A')>1 || substr_count($ser_str,'O')>1 || substr_count($ser_str,'S')>1 || substr_count($ser_str,'D')>1 || substr_count($ser_str,'I')>1 || substr_count($ser_str,'T')>1 || substr_count($ser_str,'L')>1)
                        {
                                $smarty->assign("check_service","Y");
                                $msg="Select one option for one service";
                                $is_error++;
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
			$smarty->display("easy_bill_link.htm");
		}
		else
		{
			$services=$serviceObj->get_matri_duration($SERVICE);
			$SERVICE= implode(",",$services);
			$service_arr=$serviceObj->getServicesAmount($SERVICE,'RS');
                        foreach($service_arr as $k=>$v)
                        {
                                if($ser_names=='')
                                        $ser_names=$service_arr[$k]['NAME'];
                                else
                                        $ser_names.=", ".$service_arr[$k]['NAME'];
                                $price+=$service_arr[$k]['PRICE'];
                        }
			
			$prev_paid=getSubscriptionStatus($profileid);
			if($prev_paid)
				$discount_value=round((($renew_discount_rate/100)*$price),2);
			$total_amt=$price-$discount_value;

			$type='RS';
			 $amount = $type.". ".$total;

			/*Code to generate and save reference id.*/
			$sql_ins = "INSERT INTO billing.EASY_BILL (PROFILEID,USERNAME,SERVICEID,ADDON_SERVICEID,TYPE,AMOUNT,ENTRY_BY) VALUES('$profileid','$USERNAME','$SERVICE','$addon_services_str','$type','$total_amt','$name')";
			mysql_query_decide($sql_ins) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins,"ShowErrTemplate");

			$id = mysql_insert_id_js();
			$ref_id = generate_ref_id($id);

			$sql_upd = "UPDATE billing.EASY_BILL SET REF_ID='$ref_id', ENTRY_DT=now() WHERE ID='$id'";
			mysql_query_decide($sql_upd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
			/*End of - Code to generate and save reference id.*/

			
			$msg.= "Easy Bill Ref Id =$ref_id .<br>"."Service Price=$price RS";
			if($discount_value>0)
				$msg.=", Renewal Discount=$discount_value  RS" . ", Total =$total_amt  Rs";
			$msg.="<br> Service Name: $ser_names";
			$msg .= "<br><a href=\"mainpage.php?name=$name&cid=$cid\">";
			$msg .= "Continue &gt;&gt;</a>";
		
			$smarty->assign("URL","$URL");	
			$smarty->assign("USER",$name);
			$smarty->assign("USERNAME","$USERNAME");
			$smarty->assign("SERVICE_STR","$service_str");
			$msg1=$smarty->fetch("mail_for_payment.htm");
			$from="webmaster@jeevansathi.com";
			//send_mail($email,'','aman.sharma@jeevansathi.com',$msg1,"Link for online Payment",$from);
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
		/*$sql = "Select SERVICEID, NAME from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' ";
		$result_service_main = mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow_service_main = mysql_fetch_array($result_service_main))
		{
			$service_main[] = array("SERVICEID"=>$myrow_service_main["SERVICEID"],
					"NAME"=>$myrow_service_main["NAME"]);
		}*/
		$service_main=$serviceObj->getAllServices_crm();
		$smarty->assign("SERVICE_MAIN",$service_main);
		$smarty->assign("USERNAME",stripslashes($username));
		$smarty->assign("PROFILEID",$pid);
        	$smarty->assign("cid",$cid);
        	$smarty->assign("name",$name);
	        $smarty->display("easy_bill_link.htm");
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
