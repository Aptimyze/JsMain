<?php

include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
if(authenticated($cid))
{
	$name= getname($cid);
	$center=get_centre($cid);
	$smarty->assign("name",$name);
	$serviceObj = new Services;
	$membershipObj = new Membership;
	global $renew_discount_rate;

	$disc_1=$membershipObj->isRenewable($pid);
	if($disc_1)
		$disc_1=$renew_discount_rate;
	if($disc_1)
	{
		$smarty->assign('REN_DISC','Y');			
		$smarty->assign('disc',$disc_1);
		$smarty->assign('SHOW_DISC','N');
	}

	$Spec_arr=$membershipObj->getSpecialDiscount($pid);
	$Spec_1=$Spec_arr['DISCOUNT'];
	if($Spec_1)
	{
		list($yy,$mm,$dd)= explode("-",$Spec_arr['EDATE']);
		$timestamp= mktime(0,0,0,$mm,$dd,$yy);
		$SpecDate=date('d M Y',$timestamp);

		$smarty->assign("SpecDate",$SpecDate);
		$smarty->assign('Spec',$Spec_1);
		$smarty->assign('SPC_DISC','Y');
		$smarty->assign('SHOW_DISC','N');
	}

	if($submit)
	{
		$is_error=0;
		if($pincode){
			$sql_pin="SELECT PINCODE FROM billing.BLUEDART_PINCODE WHERE PINCODE='$pincode'";
			$res_pin=mysql_query_decide($sql_pin) or die(mysql_error_js());
			if(mysql_affected_rows($db)==0)
			{	
				$smarty->assign("check_pincode","Y");
				$smarty->assign("show_bluedart_mess","Y");
				$is_error++;
			}
		}	

		if(!$NAME1)
		{
                        $smarty->assign("check_name","Y");
                        $is_error++;
		}
		if((!$PHONE_RES || !is_numeric($PHONE_RES)) && (!$PHONE_MOB || !is_numeric($PHONE_MOB)))
		{
			$smarty->assign("check_res","Y");
                        $smarty->assign("check_mob","Y");
			$is_error++;
		}

		if($discount>60)
		{
			$smarty->assign("max_discount","Y");	
			$is_error++;
		}

		if($SERVICE[0]=='')
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
                                	$msg="Profile Highlighting comes with E-value or E-rishta pack";
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
				$msg=$membershipObj->checkRange($profileid,$SERVICE);
				if($msg!='')
					$is_error++;
			}
		}
		if(!$ADDRESS)
		{
                        $smarty->assign("check_address","Y");
                        $is_error++;
		}
		if(!$pincode)
		{
                        $smarty->assign("check_pincode","Y");
                        $is_error++;
		}

		if($is_error>=1)
		{
			$services=$serviceObj->getAllServices_crm();
	                $smarty->assign("SERVICE_MAIN",$services);
			$smarty->assign("msg",$msg);
			$smarty->assign("DISCOUNT",$discount);
			$smarty->assign("USERNAME",stripslashes($USERNAME));
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("NAME1",stripslashes($NAME1));
			$smarty->assign("EMAIL",$EMAIL);
			$smarty->assign("PHONE_RES",$PHONE_RES);
			$smarty->assign("PHONE_MOB",$PHONE_MOB);
			$smarty->assign("SERVICE",$SERVICE[0]);
			$smarty->assign("ADDRESS",stripslashes($ADDRESS));
			$smarty->assign("PINCODE",$pincode);
			$smarty->assign("COMMENTS",$COMMENTS);	
			$smarty->assign("SPC_DISC",$SPC_DISC);
			$smarty->assign("REN_DISC",$REN_DISC);
			$smarty->assign("SHOW_DISC",$SHOW_DISC);
			$smarty->assign("Spec",$Spec);
			$smarty->assign("disc",$disc);

			$smarty->assign("cid",$cid);
			$smarty->display("bluedart_pickup_form.htm");
		}
		else
		{
			$services=$serviceObj->get_matri_duration($SERVICE);
			$SERVICE=implode(",",$services);

			$sql="SELECT EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql);
			$myrow=mysql_fetch_array($result);
			
			if(!$EMAIL)			
			{
				$email=$myrow['EMAIL'];
			}
			else
				$email=$EMAIL;
			
			if($Spec)
				$discount=$Spec;

			if($disc)
				$discount=$disc;

			$service_arr=$serviceObj->getServicesAmount($SERVICE,'RS');
			foreach($service_arr as $k=>$v)
			{
				if($ser_names=='')
					$ser_names=$service_arr[$k]['NAME'];
				else
					$ser_names.=", ".$service_arr[$k]['NAME'];

				$in_price=$service_arr[$k]['PRICE'];	
				
				if($discount)
				{
					if(strstr($k,'P') || strstr($k,'C'))
					{
						$tmp_dsnt=$dsnt=floor($in_price*($discount/100));
//						$mainservice_discount_price=$in_price-$dsnt;
					}
					//$amnt=$service_arr[$k]['PRICE'];
					//$amount=$mainservice_discount_price+$in_price;
					
					$amount+=$in_price-$tmp_dsnt;
					if($tmp_dsnt)
						$tmp_dsnt=0;
				}
				else if (!$pickupagain)
					$amount+=$in_price;
			}
			
			if($discount=='' && !$pickupagain)
				$dsnt=0;

			$sql_pin_city="SELECT CITY,CSCRCD,CAREA,DESTARCD FROM billing.BLUEDART_PINCODE WHERE PINCODE='$pincode'";
			$res_pin_city=mysql_query_decide($sql_pin_city);
			$myrow_pin=mysql_fetch_array($res_pin_city);
			$city=$myrow_pin['CITY'];
			$csrcd=$myrow_pin['CSCRCD'];
			$carea=$myrow_pin['CAREA'];
			$bdelloc=$myrow_pin['DESTARCD'];

			$area=$carea.'/'.$csrcd;
			$date=date('Y-m-d');
			
			if($pickupagain=='')
				$ct=1;
			else if($pickupagain==1)
				$ct=0;

			if(!$pickupagain)
			{
				$sql_chk="SELECT REF_ID,AIRWAY_NUMBER FROM billing.BLUEDART_COD_REQUEST WHERE PROFILEID='$profileid' AND ENTRY_DT='$date' AND ACTIVE='Y'";
				$res_chk=mysql_query_decide($sql_chk) or die("$sql_chk".mysql_error_js());
				if($row_chk=mysql_fetch_array($res_chk))
				{
					$airwy=$row_chk['AIRWAY_NUMBER'];
					$ref_id=$row_chk['REF_ID'];
				}
			}
			

			if($airwy)
			{
				$msg .= "This Profile has already made a Bluedart COD Pick up request today.<br/><br/>"; 
				$msg .= "His Airway Number is : $airwy <br/><br/>";
				$msg .= "Jeevansathi Internal Request ID is : JSBD$ref_id <br/><br />";
				$msg .= "<a href=\"../jsadmin/mainpage.php?name=$name&cid=$cid\">";
				$msg .= "Click here for Main page&gt;&gt;</a> <br/><br />";
				$msg .= "If you really want to create new Pick up request for same profile then <br /><br /> ";
				$msg .= "<a href=\"../crm/bluedart_pickup_form.php?name=$name&cid=$cid&pickupagain=$ct&submit=1&is_error=0&profileid=$profileid&USERNAME=$USERNAME&email=$email&NAME1=$NAME1&PHONE_MOB=$PHONE_MOB&PHONE_RES=$PHONE_RES&SERVICE=$SERVICE&date=$date&ADDRESS=$ADDRESS&pincode=$pincode&city=$city&COMMENTS=$COMMENTS&name=$name&dsnt=$dsnt&amount=$amount&SERVICE[0]=$SERVICE\"> ";
				$msg .= "Kindly Click Here &gt;&gt;</a>";
				

				$smarty->assign("name",$name);
				$smarty->assign("cid",$cid);
				$smarty->assign("MSG",$msg);

			}
			else if(!$airwy)
			{
				$sql2="INSERT INTO billing.BLUEDART_COD_REQUEST (PROFILEID,USERNAME,EMAIL,NAME,PHONE_MOB,PHONE_RES,SERVICE,ENTRY_DT,DISCOUNT_AMNT,TOTAL_AMOUNT,ADDRESS,PINCODE,CITY,COMMENTS,OPERATOR,AREA,SENT_MAIL,DESTARCD) VALUES ('$profileid', '$USERNAME', '$email', '$NAME1', '$PHONE_MOB', '$PHONE_RES', '$SERVICE','$date','$dsnt','$amount', '$ADDRESS','$pincode','$city', '$COMMENTS', '$name','$area','N','$bdelloc')";
       				mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
				$req_id=mysql_insert_id_js();
			
				$sql_air="SELECT AIRWAY_NUMBER FROM billing.BLUEDART_AIRWAY WHERE ID='$req_id'";
				$res_air=mysql_query_decide($sql_air);
				$myrow_air=mysql_fetch_array($res_air);
				$airway_number=trim($myrow_air['AIRWAY_NUMBER']);

				$sql_update="UPDATE billing.BLUEDART_COD_REQUEST SET AIRWAY_NUMBER='$airway_number' WHERE REF_ID='$req_id'";
				$res_update=mysql_query_decide($sql_update);

				$msg.= "<div align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold; padding:3px 0px 10px 0px ;\"> Your request is successfully taken for pickup with BlueDart COD. Your BlueDart COD Details : </div><br />";
				$msg.= "<table width=\"768px\" border=\"0\" cellspacing=\"3\" cellpadding=\"2\" align=\"center\">";
				$msg.= "<tr><td align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px;font-weight:bold; padding:3px;\">Airway Number is : $airway_number </td>";
				$msg.="<td align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; padding:3px;\">Jeevansathi Internal Request-id : JSBD$req_id </td></tr>";
				$msg.= "<tr><td align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; padding:4px;\">Discount Availed  : $dsnt </td><td align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; padding:4px;\">Services Opted : $ser_names </td></tr></table>";
				$msg.= "<br /><div align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold; padding:4px;\"> Amount Need to Pick up is : <b>$amount </b></div>";
				
				$msg .= "<br /><div align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; padding:4px;\"><a href=\"#\" onClick=\"print_bill()\">Click Here to Print Bill &gt;&gt;</a></div>";

				$msg .= "<div align=\"center\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; padding:4px;\"><a href=\"../jsadmin/mainpage.php?name=$name&cid=$cid\">Continue &gt;&gt;</a></div>";

				$smarty->assign("airway_number",$airway_number);
				$smarty->assign("name",$name);
				$smarty->assign("cid",$cid);
				$smarty->assign("MSG",$msg);
			}
			$smarty->display("incentive_msg.tpl");
		}	
	}
	else
	{
		$newjs_details = get_jprofile_details($pid);
		$services=$serviceObj->getAllServices_crm();
		$pin=$newjs_details['PINCODE'];
		if($pin){
			$sql_pin="SELECT PINCODE,BDEL_LOC FROM billing.BLUEDART_PINCODE WHERE PINCODE='$pin'";
			$res_pin=mysql_query_decide($sql_pin) or die(mysql_error_js());
			if(mysql_affected_rows($db)==0)
			{	
				$smarty->assign("check_pincode","Y");
				$smarty->assign("show_bluedart_mess","Y");
			}
			else
			{
				$res_pin=mysql_query_decide($sql_pin);
				$myrow_pin_1=mysql_fetch_array($res_pin);
				$loc=$myrow_pin_1['BDEL_LOC'];
				$smarty->assign('LOC',$loc);
				
			}
		}	
		
		$smarty->assign("PINCODE",$newjs_details['PINCODE']);
		$smarty->assign("USERNAME",stripslashes($username));
		$smarty->assign("EMAIL",$newjs_details['EMAIL']);
		$smarty->assign("PHONE_RES",$newjs_details['PHONE_RES']);
		$smarty->assign("PHONE_MOB",$newjs_details['PHONE_MOB']);
		$smarty->assign("ADDRESS",$newjs_details['CONTACT']);

		$smarty->assign("CITY_VALUES",$city_values);
		$smarty->assign("SERVICE_MAIN",$services);
		$smarty->assign("PROFILEID",$pid);
        	$smarty->assign("cid",$cid);
	        $smarty->display("bluedart_pickup_form.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
