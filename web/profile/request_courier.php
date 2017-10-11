<?php
include("connect.inc");
include("pg/functions.php");

$db=connect_db();
$data=authenticated($checksum);
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
//$smarty->assign("HEAD",$smarty->fetch("headold_payment.htm"));
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelold_payment.htm"));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("DEC_AG",$dec_ag);

if($data)
{
	$pid=$data["PROFILEID"];
	if($submit)
        {
		
                $is_error=0;
		if(trim($NAME1)=="" || !ereg("[a-z,A-Z]",$NAME1))
                {
                        $smarty->assign("check_name","Y");
                        $is_error++;
                }
                if((trim($PHONE_RES)=="" || !is_numeric(trim($PHONE_RES))))
                {
                        $is_error++;
                        $smarty->assign("check_res","Y");
                }
		if((trim($PHONE_MOB)=="" || !is_numeric(trim($PHONE_MOB))))
		{
			$is_error++;
			$smarty->assign("check_mob","Y");
		}
		if(trim($ADDRESS)=="")
                {
                        $smarty->assign("check_address","Y");
                        $is_error++;
                }
		if($pref_day=='' || $pref_month=='' || $pref_year=='' || !checkdate($pref_month,$pref_day,$pref_year))
		{
			$is_error++;
			$smarty->assign("CHECK_DATE","Y");
		}
		/*check added by sriram for preferred date -- past date, today's and tomorrow's date are not accepted*/
                $entered_timestamp = mktime(0,0,0,$pref_month,$pref_day,$pref_year);
		$after2_days = mktime(0,0,0,date('m'),date('d')+2,date('Y'));
                if($entered_timestamp < $after2_days)
                {
                        $is_error++;
                        $smarty->assign("CHECK_DATE","Y");

			list($after2_year,$after2_month,$after2_date) = explode("-",date('Y-m-d',$after_two_days));
                        $smarty->assign("after2_date",$after2_date);
                        $smarty->assign("after2_month",$after2_month);
                        $smarty->assign("after2_year",$after2_year);

                }
		/*end of - check added by sriram for preferred date -- past date, today's and tomorrow's date are not accepted*/
		if($is_error>=1)
                {
			$sql_near="SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y'";
			$result_near=mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$i=0;
			while($row_near=mysql_fetch_array($result_near))
			{
				if($row_near["VALUE"]!="GU")
				{
					$near_ar[$i]['LABEL']=$row_near["LABEL"];
					$near_ar[$i]['VALUE']=$row_near["VALUE"];
					$i++;
				}
			}

			$smarty->assign("REQUESTID",$REQUESTID);
                        $smarty->assign("STP",$stp);
			$smarty->assign("SERVICE_MAIN",$service_main);
			$smarty->assign("MAIN_SER_NAME",$MAIN_SER_NAME);
		        $smarty->assign("ADDON_ARR",explode(",",$ADDON_SER));
			$smarty->assign("ADDON_SER",$ADDON_SER);
			$smarty->assign("ADDON_SERVICES",$ADDON_SERVICES);
                        $smarty->assign("USERNAME",stripslashes($USERNAME));
			$smarty->assign("CHECKSUM",$checksum);
                        $smarty->assign("PROFILEID",$profileid);
                        $smarty->assign("NAME1",stripslashes($NAME1));
                        $smarty->assign("EMAIL",$EMAIL);
                        $smarty->assign("PHONE_RES",$PHONE_RES);
                        $smarty->assign("PHONE_MOB",$PHONE_MOB);
                        $smarty->assign("SERVICE",$SERVICE);
                        $smarty->assign("ADDRESS",stripslashes($ADDRESS));
                        $smarty->assign("COMMENTS",stripslashes($COMMENTS));
			$smarty->assign("CUR_TYPE",$CUR_TYPE);
			$smarty->assign("AMOUNT",$AMOUNT);
                        $smarty->assign("COURIER",$courier_type);

			$smarty->assign("PREF_DAY",$pref_day);
                        $smarty->assign("PREF_MONTH",$pref_month);
                        $smarty->assign("PREF_YEAR",$pref_year);
                        $smarty->assign("PREF_DATE",$pref_date);

                        $smarty->assign("pref_time",$pref_time);
			$smarty->assign("NEAR_ARR",$near_ar);
	                $smarty->assign("CITY_RES",$city_res);
			for($i=0;$i<31;$i++)
                        {
                                $ddarr[$i]=$i+1;
                        }
                        for($i=0;$i<12;$i++)
                        {
                                $mmarr[$i]=$i+1;
                        }
                        for($i=0;$i<10;$i++)
                        {
                                $yyarr[$i]=$i+2005;
                        }
                        $smarty->assign("ddarr",$ddarr);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("yyarr",$yyarr);

                        $smarty->display("pay_req.htm");
                }
                else
		{
			$main_ser_details=getServiceDetails($SERVICE);				
			$duration=$main_ser_details["DURATION"];
			if($ADDON_SER)
			{
				$addon_services=explode(",",$ADDON_SER);
				for($i=0;$i<count($addon_services);$i++)
                                        //$addon_services[$i] = $addon_services[$i].$duration;
					$addon_services[$i] = $addon_services[$i];
                                $addon_services_str = implode(",",$addon_services);
			}

			$sql="select CITY_RES,PINCODE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$pid'";
		        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
       			$myrow=mysql_fetch_array($result);
        		$pin=$myrow["PINCODE"];
			$city=$city_res;	
		//	$city=$myrow["CITY_RES"];	

			$pref_time=$pref_year."-".$pref_month."-".$pref_day;
			//$sql2 = "REPLACE INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,ENTRY_DT,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,ADDON_SERVICEID) VALUES ('$pid','".addslashes(stripslashes($USERNAME))."','".addslashes(stripslashes($NAME1))."','$EMAIL','$PHONE_RES','$PHONE_MOB','$SERVICE','".addslashes(stripslashes($ADDRESS))."','$city','$pin','Y','',now(),'USER','".addslashes(stripslashes($COMMENTS))."','$pref_time','SKYPAK','$addon_services_str')";
			$sql2  = "UPDATE incentive.PAYMENT_COLLECT SET NAME='".addslashes(stripslashes($NAME1))."' , EMAIL='$EMAIL',PHONE_RES='$PHONE_RES',PHONE_MOB = '$PHONE_MOB' , SERVICE ='$SERVICE' , ADDRESS='".addslashes(stripslashes($ADDRESS))."',CITY='$city',PIN='$pin' , BYUSER='Y' , CONFIRM='' , COMMENTS='".addslashes(stripslashes($COMMENTS))."', PREF_TIME ='$pref_time', COURIER_TYPE='SKYPAK' , ADDON_SERVICEID='$addon_services_str' ,PICKUP_TYPE='CHEQ_REQ_USER' WHERE ID='$REQUESTID'";
                                                                                                                             
                        $result=mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
			
			//$req_id=mysql_insert_id_js();

			$msg="Your request for courier has been picked up successfully." ;
			//$msg1="Your request id is:- $req_id .";
			$msg.="<br>Your request id is:- $REQUESTID .";
			$msg.="<br>Please remember this ID for further communication.";
			$smarty->assign("MSG",$msg);
			$smarty->display("thanks_sky.htm");
		}
	}
	else
	{
		$pid=$data["PROFILEID"];
	
		/*$sql="select CONFIRM,AR_GIVEN,STATUS from incentive.PAYMENT_COLLECT  where PROFILEID='$pid' and BILLING!='Y' and DISPLAY<>'N' order by ID desc";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result)>0 && $dec_ag!='Y')
		{
			$myrow=mysql_fetch_array($result);
			if($myrow["STATUS"]=='D')
			{
				$msg="Last time, You declined the payment to our representative.To make new request  ";
				$msg.="<a href=\"/P/payment.php?ser_main=C&dec_ag=Y\"> Click here</a>";
                                $smarty->assign("MSG",$msg);
                                $smarty->display("thanks_sky.htm");
                                exit;
			}
			elseif($myrow["CONFIRM"]=='' && $myrow["AR_GIVEN"]=='')
			{
				$msg="Your contact details have been entered and your request is already in a queue";
	                	$smarty->assign("MSG",$msg);
        	        	$smarty->display("thanks_sky.htm");
	                	exit;
			}
			elseif($myrow["CONFIRM"]=='Y')
			{
				$msg="Your request has been confirmed and our representative will contact you shortly";
	                        $smarty->assign("MSG",$msg);
        	                $smarty->display("thanks_sky.htm");
                	        exit;
			}
		}
		else
		{*/
			$sql = "SELECT SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row = mysql_fetch_array($result);
			$service_main = $row['SERVICE'];
			$addon = $row['ADDON_SERVICEID'];

			$username=$data["USERNAME"];
			$main_ser=$service_main;
			$addon_ser=$addon;
			$amt=$row['AMOUNT'];

			$sql="select EMAIL,PHONE_MOB,PHONE_RES,CONTACT from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$pid'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_array($result);
			$email=$myrow["EMAIL"];

			$main_ser_name=service_name($main_ser);

			if($row["ADDON_SERVICEID"])
			{
				$addon_serviceid = $row["ADDON_SERVICEID"];
				$addon_serviceid_ar = explode(",",$addon_serviceid);
				for($j=0;$j<count($addon_serviceid_ar);$j++)
					$addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
				$addon_serviceid_str = implode(",",$addon_serviceid_ar);
														    
				$sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
				$result_services = mysql_query_decide($sql) or die(mysql_error_js());
				while($myrow_result_services = mysql_fetch_array($result_services))
				{
					$add_on_services[] = "<br>".$myrow_result_services["NAME"];
				}
				$addon_service_names = implode(",",$add_on_services);
			}

			//$addon_arr=explode(",",$addon_ser);
			$sql_near="SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y";
			$result_near=mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$i=0;
			while($row_near=mysql_fetch_array($result_near))
			{
				if($row_near["VALUE"]!="GU")
				{
					$near_ar[$i]['LABEL']=$row_near["LABEL"];
					$near_ar[$i]['VALUE']=$row_near["VALUE"];
					$i++;
				}
			}


			$smarty->assign("STP",$stp);
			$smarty->assign("REQUESTID",$REQUESTID);
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
			$smarty->assign("EMAIL",$email);
			$smarty->assign("USERNAME",$username);
			$smarty->assign("PHONE_MOB",$myrow["PHONE_MOB"]);
			$smarty->assign("PHONE_RES",$myrow["PHONE_RES"]);
			$smarty->assign("ADDRESS",$myrow["CONTACT"]);
			$smarty->assign("SERVICE",$main_ser);
			$smarty->assign("ADDON_SER",$addon_ser);
			$smarty->assign("MAIN_SER_NAME",$main_ser_name);
			$smarty->assign("ADDON_ARR",$addon_arr);
			$smarty->assign("ADDON_SERVICES",$addon_service_names);
			$smarty->assign("CUR_TYPE",$row['CUR_TYPE']);
			$smarty->assign("AMOUNT",$amt);
			$smarty->assign("NEAR_ARR",$near_ar);
	                $smarty->assign("CITY_RES",$city_res);
			for($i=0;$i<31;$i++)
                        {
                                $ddarr[$i]=$i+1;
                        }
                        for($i=0;$i<12;$i++)
                        {
                                $mmarr[$i]=$i+1;
                        }
                        for($i=0;$i<10;$i++)
                        {
                                $yyarr[$i]=$i+2005;
                        }
                        $smarty->assign("ddarr",$ddarr);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("yyarr",$yyarr);

			$smarty->display("pay_req.htm");
			unset($add_on_services);
		//}
	}

}
else
{
	Timedout();
}

function service_name($id)
{
	$sql="select NAME from billing.SERVICES where SERVICEID='$id'";	
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $myrow=mysql_fetch_array($result);
        $name=$myrow["NAME"];
	return $name;
}


?>
