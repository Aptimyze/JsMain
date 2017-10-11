<?php
include("time.php");
include("connect.inc");
include("../profile/pg/functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
if(authenticated($cid))
{
	$serviceObj = new Services;
		
	if($save)
	{
		$msgval = array();
		$sqlval = array();
		$sql = "UPDATE incentive.PAYMENT_COLLECT set ";	
		if($SERVICE_NOW)
		{
			$services=$serviceObj->get_matri_duration($SERVICE_NOW);
			$ser_str=implode(",",$services);
                        $sqlval[] = " SERVICE = '$ser_str' ";
                }
		/*if(count($addon_services)>0)
		{
			$dur_arr=getServiceDetails($SERVICE_NOW);
			$duration=$dur_arr["DURATION"];
			for($i=0;$i<count($addon_services);$i++)
				$addon_arr[]=$addon_services[$i].$duration;
			$addon_str=implode(",",$addon_arr);
		}*/
		$sqlval[] = " ADDON_SERVICEID = '$addon_str' ";
		$sqlval[] = " ADDRESS = '$address' ";
		$sqlval[] = " PHONE_RES = '$phone_res' ";
		$sqlval[] = " PHONE_MOB = '$phone_mob' ";
		$sqlval[] = " DISCOUNT = '$discount' ";
		$sqlval[] = " PREFIX_NAME = '$prefix_name' ";
		$sqlval[] = " PIN = '$pincode' ";
		$sqlval[] = " LANDMARK = '$LANDMARK' ";
		$sqlval[] = " NAME = '$name_req' ";
		if($pref_time)
		{
			$sqlval[] = " PREF_TIME = '$pref_time '";
		}
		if(count($sqlval))
		{
			$sql = $sql.implode(",",$sqlval)." where ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());  
			$msg = "Record updated successfully";
               	}
		else
			$msg="Nothing got updated <br>Please try again.";

                $msg .= "<br><br><a href=\"../crm/confirmclient.php?user=$user&cid=$cid\">Continue &gt;&gt;</a>";

                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
		$smarty->assign("flag",'saved');
                $smarty->display("jsadmin_msg.tpl");
	}
	else
	{
	/*	$sql = "Select SERVICEID, NAME from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' AND ADDON = 'N'";
                $result_service_main = mysql_query_decide($sql) or die(mysql_error_js());
                while($myrow_service_main = mysql_fetch_array($result_service_main))
                {
                        $service_main[] = array("SERVICEID"=>$myrow_service_main["SERVICEID"],
                                                "NAME"=>$myrow_service_main["NAME"]);
                }
                $smarty->assign("SERVICE_MAIN",$service_main);*/
	
		 $sql="SELECT * from incentive.PAYMENT_COLLECT where ID=$id";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($result))
		{
			$id=$row["ID"];
			$UNAME=$row["USERNAME"];
			$name_req=$row["NAME"];
			$phone_res=$row["PHONE_RES"];
			$phone_mob=$row["PHONE_MOB"];
			$service=$row["SERVICE"];
			$addon_service=$row["ADDON_SERVICEID"];
			$address=$row["ADDRESS"];
			$city=$row["CITY"];
			$pref_time=$row["PREF_TIME"];
			$discount=$row["DISCOUNT"];
			$prefix_name=$row["PREFIX_NAME"];
			$pincode=$row["PIN"];
			$landmark=$row["LANDMARK"];
		}
		if($addon_service)
		{
			if(strstr($addon_service,"B"))
				$smarty->assign("BOLD_LISTING_SELECTED","Y");
			if(strstr($addon_service,"K"))
				$smarty->assign("KUNDLI_SELECTED","Y");
			if(strstr($addon_service,"H"))
				$smarty->assign("HOROSCOPE_SELECTED","Y"); 
			if(strstr($addon_service,"M"))
				$smarty->assign("MATRI_PROFILE_SELECTED","Y"); 
		}
		$service_main=$serviceObj->getAllServices_crm();
		$smarty->assign("ID",$id);
		$smarty->assign("CID",$cid);
		$smarty->assign("UNAME",$UNAME);
		$smarty->assign("SERVICE_MAIN",$service_main);
		$smarty->assign("SERVICE",$service);
		$smarty->assign("ADDON_SERVICE",$addon_service);
		$smarty->assign("NAME_REQ",$name_req);
		$smarty->assign("PHONE_RES",$phone_res);
		$smarty->assign("PHONE_MOB",$phone_mob);
		$smarty->assign("ADDRESS",$address);
		$smarty->assign("PREF_TIME",$pref_time);
		$smarty->assign("DISCOUNT",$discount);
		$smarty->assign("prefix_name",$prefix_name);
		$smarty->assign("pincode",$pincode);
		$smarty->assign("LANDMARK",$landmark);		

		//$smarty->assign("SHOW",$SHOW);
		$smarty->display("edit_paymentcontact_details.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->assign("user",$user);
	$smarty->display("jsadmin_msg.tpl");
}


?>
