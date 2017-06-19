<?php
/**
	CREATED BY:- Aman Sharma
	Creation date:-14 sep,2005
	Description:-Cron script for Resending all the confirmed requests to skypak for pickups
**/


include("connect.inc");
include("common.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	
	$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,SERVICES.NAME as SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY,DISCOUNT from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where COURIER_TYPE='SKYPAK' and CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";

	$result=mysql_query_decide($sql) or logError("$sql".mysql_error_js());
	if(($count_rows = mysql_num_rows($result))>0)
	{
		while($myrow=mysql_fetch_array($result))
		{
			$due_amount='';
			$costval='';
                        $service_names='';
                        $entry_val='';
                        $entry_date='';
			$services_str='';
			$addon_serviceid='';
			unset($addon_serviceid_ar);
			$services_str='';

			$id_arr[]=$myrow["ID"];
			if($myrow["PREF_TIME"] != "0000-00-00 00:00:00")
			{
				$date_val = get_date_format($myrow["PREF_TIME"]);
			}
			else
				$date_val = "Not specified";
															     
			if($myrow["ENTRY_DT"] != "0000-00-00 00:00:00")
			{
				$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,11);
			}
															     
			else
				$date_entry = "Not specified";
			$services_str="'".$myrow["MAIN_SER"]."'";
			
															     
			$services[] = $myrow["SERVICE"];

			if($myrow["ADDON_SERVICEID"])
				{
					$addon_serviceid = $myrow["ADDON_SERVICEID"];
					$addon_serviceid_ar = explode(",",$addon_serviceid);
					for($i=0;$i<count($addon_serviceid_ar);$i++)
						$addon_serviceid_ar[$i]="'".$addon_serviceid_ar[$i]."'";
					$addon_serviceid_str = implode(",",$addon_serviceid_ar);
					$services_str.=",".$addon_serviceid_str;
					$sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
					$result_services = mysql_query_decide($sql) or logError("$sql".mysql_error_js());
					while($myrow_result_services = mysql_fetch_array($result_services))
					{
						$services[] = "<br>".$myrow_result_services["NAME"];
																     
					}
				}
				$l=0;
				$kk=0;
				$idstr_to_show='';
				$exec_phone='';
				$l=strlen($myrow["ID"]);
				if($l<10)
				{
					$tmp_len=10-$l;
					for($kk=0;$kk<$tmp_len;$kk++)
						$idstr_to_show.='0';
				}
					$idstr_to_show.=$myrow["ID"];
																     
				$exec_phone=get_phone_no($myrow["ENTRYBY"]);
				$service_names = implode(",",$services);
				$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or logError("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
			//	$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
                                $TAX_RATE = billingVariables::TAX_RATE;
                                $TAX=round((($TAX_RATE/100)*$due_amount),2);
                                $costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				$values[] = array("sno"=>$sno,
							"id"=>$idstr_to_show,
							"profileid"=>$myrow["PROFILEID"],
							"username"=>$myrow["USERNAME"],
							"name"=>$myrow["NAME"],
							"phone_res"=>$myrow["PHONE_RES"],
							"phone_mob"=>$myrow["PHONE_MOB"],
							"service"=>$service_names,
							"address"=>nl2br($myrow["ADDRESS"]),
							"entryby"=>$exec_phone["ALIASE_NAME"],
							"exec_ph"=>$exec_phone["PHONE_NO"],
							"entrydt"=>$date_entry,
							"comments"=>$myrow["COMMENTS"],
							"pref_time"=>$date_val,
							"courier"=>$myrow["COURIER_TYPE"],
							"city"=>$myrow["CITY"],
							"amount"=>$costval
						 );
																     
				 $sno++;
				unset($services);
			}

			$subject="Jeevansathi.com ".date("d-M-Y")." $count_rows records";
			$from="skypak@jeevansathi.com";
	
		
		//	$email="aman.sharma";															     
			$email="pickup@skyfin.com,del@skyfin.com,annie@skyfin.com,samirgupta@skyfin.com,navin@skyfin.com";
			//$email="alok@jeevansathi.com";
			$Cc="rajeev@jeevansathi.com,rizwan@naukri.com,mahesh@jeevansathi.com,alok@jeevansathi.com";
			$Bcc="aman.sharma@jeevansathi.com";
																     
			$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
			$smarty->assign("TODAY",$tdy);
			$smarty->assign("ROW",$values);
			$msg=$smarty->fetch("skypak.htm");			

			send_email($email,$msg,$subject,$from,$Cc,$Bcc);

			//for($i=0;$i<count($id_arr);$i++)
			//{
			$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, ID,DISCOUNT FROM incentive.PAYMENT_COLLECT where ID in (".implode(",",$id_arr).")";
			mysql_query_decide($sql3) or logError("$sql3".mysql_error_js());

			$sql="UPDATE incentive.PAYMENT_COLLECT set STATUS='', ARAMEX_DT=now() ,ENTRYBY='cron_resend_script',ENTRY_DT=now() where ID in (".implode(",",$id_arr).")";
			mysql_query_decide($sql) or logError("$sql".mysql_error_js());
			//}

			$idstring=implode(",",$id_arr);
			$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY,RESEND) values ('$idstring',now(),'cron_resend_script','Y')";
			mysql_query_decide($sql) or logError("$sql".mysql_error_js());
																     
	}
	else
		;

function get_phone_no($exe)
{
	$sql_no="SELECT ALIASE_NAME,PHONE_NO from incentive.EXECUTIVES where EXE_NAME='$exe'";
	$result_no = mysql_query_decide($sql_no) or logError("$sql_no".mysql_error_js());
	$row_exec=mysql_fetch_array($result_no);
	if(!$row_exec['ALIASE_NAME'])
	{
		$row_exec['ALIASE_NAME']='rajeev';
		$row_exec['PHONE_NO']='0120-5303116';
	}
	return $row_exec;
}
															     
function get_date_format($dt)
{
	$date_time_arr = explode(" ",$dt);
	$time_arr=explode(":",$date_time_arr[1]);
	$date_arr = explode("-",$date_time_arr[0]);
	$date_val = date("d-M-Y H:i:s",mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]));
	return $date_val;
														     
}
?>
