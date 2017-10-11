<?php
/**
	CREATED BY:- Aman Sharma
	Creation date:-14 sep,2005
	Description:-Cron script for sending all the confirmed requests to skypak for pickups
**/


include("connect.inc");
include("common.inc");
include("func_sky.php");

	$from="skypak@jeevansathi.com";
	$email="pickup@skyfin.com,del@skyfin.com,annie@skyfin.com";
	$Cc="rajeev@jeevansathi.com,rizwan@naukri.com,mahesh@jeevansathi.com,alok@jeevansathi.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com";


	$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY,DISCOUNT from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where COURIER_TYPE='SKYPAK' and CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";

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
				$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,9);
			}
															     
			else
				$date_entry = "Not specified";
			//$services_str="'".$myrow["MAIN_SER"]."'";
			$ser_str=$myrow["MAIN_SER"];
			
			if($myrow["ADDON_SERVICEID"])
			{
				$addon_serviceid = $myrow["ADDON_SERVICEID"];
				$ser_str.=",".$addon_serviceid;
			}
			$service_arr=explode(",",$ser_str);
			$services_str=implode("','",$service_arr);
			$services_str="'".$services_str."'";

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
				$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or logError("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
				//$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
                                $TAX_RATE = billingVariables::TAX_RATE;
                                $TAX=round((($TAX_RATE/100)*$due_amount),2);
                                //$costval=floor(($due_amount+$TAX)-$myrow["DISCOUNT"]);
                                $costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				if($costval>0)
				{
					$values[] = array("sno"=>$sno,
								"id"=>$idstr_to_show,
								"profileid"=>$myrow["PROFILEID"],
								"username"=>$myrow["USERNAME"],
								"name"=>$myrow["NAME"],
								"phone_res"=>$myrow["PHONE_RES"],
								"phone_mob"=>$myrow["PHONE_MOB"],
								"service"=>$service_names,
								"address"=>($myrow["ADDRESS"]),
								"entryby"=>$exec_phone["ALIASE_NAME"],
								"exec_ph"=>$exec_phone["PHONE_NO"],
								"entrydt"=>$date_entry,
								"comments"=>$myrow["COMMENTS"],
								"pref_time"=>$date_val,
								"courier"=>$myrow["COURIER_TYPE"],
								"city"=>$myrow["CITY"],
								"amount"=>$costval
							 );
					$id_sent_arr[]=$myrow["ID"];		        
			        } 													     
				 $sno++;
				unset($services);
			}
			$count_rows=count($id_sent_arr);
			$subject="Jeevansathi.com ".date("d-M-Y")." $count_rows records";
			$msg="";													    			
																     
		/*	$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
			$smarty->assign("TODAY",$tdy);
			$smarty->assign("ROW",$values);
			$msg=$smarty->fetch("skypak.htm");			
		*/

		//	$header = "City"."||"."Request date"."||"."Request-ID"."||"."Name(Username)"."||"."Address"."||"."Phone_Res"."||"."Phone_Mob"."||"."Amount"."||"."Pref Time"."||"."Exec. Name"."||"."Exec_Phone";

		if(count($id_sent_arr)>0)
		{
			for($i=0;$i<count($values);$i++)
			{
				$line=$values[$i]['city'] ."||".$values[$i]['entrydt']."||".$values[$i]['id']."||".$values[$i]['name']."(".$values[$i]['username'].")"."||".$values[$i]['address']."||".$values[$i]['phone_res']."||".$values[$i]['phone_mob']."||".$values[$i]['amount']."||".$values[$i]['pref_time']."||".$values[$i]['entryby']."||".$values[$i]['exec_ph'];
				//$line = str_replace("\n",' ',$line);
				$line=ereg_replace("\r\n|\n\r|\n|\r",",",str_replace("\"","'",$line));
				$data .= trim($line)." \n";
			}
			//$attach="$header\n$data";
			$attach="$data";  	
			send_email_plain($email,$Cc,$Bcc,$msg,$subject,$from,$attach);
				//send_email($email,$Cc,$Bcc,$msg,$subject,$from);                                                                                                                              
				//for($i=0;$i<count($id_arr);$i++)
				//{
				if(!mysql_ping_js($db))
	                                $db=connect_db();
				$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, ID,DISCOUNT FROM incentive.PAYMENT_COLLECT where ID in (".implode(",",$id_sent_arr).")";
				mysql_query_decide($sql3) or logError("$sql3".mysql_error_js());

				$sql="UPDATE incentive.PAYMENT_COLLECT set AR_GIVEN='Y', ARAMEX_DT=now() ,ENTRYBY='cron_script',ENTRY_DT=now() where ID in (".implode(",",$id_sent_arr).")";
				mysql_query_decide($sql) or logError("$sql".mysql_error_js());
				//}

				$idstring=implode(",",$id_sent_arr);
				$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY) values ('$idstring',now(),'cron_script')";
				mysql_query_decide($sql) or logError("$sql".mysql_error_js());
		}															     
	}
	else
		;

	unset($services);
	unset($id_arr);
	unset($id_sent_arr);
	unset($values);
	unset($data);
	unset($line);
	unset($attach);
//********************************************code portion for pickup mail ends here**************************************//



//********************************************code for closed cases starts here******************************************//	
	
	$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY,DISCOUNT from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where COURIER_TYPE='SKYPAK' and CONFIRM='Y' and AR_GIVEN='Y' and STATUS='X' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";

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
			$date_val = "Not specified";
															     
			if($myrow["ENTRY_DT"] != "0000-00-00 00:00:00")
			{
				$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,9);
			}
															     
			else
				$date_entry = "Not specified";
			//$services_str="'".$myrow["MAIN_SER"]."'";
			$ser_str=$myrow["MAIN_SER"];
			
															     

			if($myrow["ADDON_SERVICEID"])
				{
					$addon_serviceid = $myrow["ADDON_SERVICEID"];
					$ser_str.=",".$addon_serviceid;
				}
			$service_arr=explode(",",$ser_str);
                        $services_str=implode("','",$service_arr);
                        $services_str="'".$services_str."'";
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
				$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or logError("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
				//$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
                                $TAX_RATE = billingVariables::TAX_RATE;
                                $TAX=round((($TAX_RATE/100)*$due_amount),2);
                                //$costval=floor(($due_amount+$TAX)-$myrow["DISCOUNT"]);
                                $costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				$values[] = array("sno"=>$sno,
							"id"=>$idstr_to_show,
							"profileid"=>$myrow["PROFILEID"],
							"username"=>$myrow["USERNAME"],
							"name"=>$myrow["NAME"],
							"phone_res"=>$myrow["PHONE_RES"],
							"phone_mob"=>$myrow["PHONE_MOB"],
							"service"=>$service_names,
							"address"=>($myrow["ADDRESS"]),
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

			$subject="(Case-Closed) Jeevansathi.com ".date("d-M-Y")." $count_rows records";
			$msg="";													    			
																     
		/*	$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
			$smarty->assign("TODAY",$tdy);
			$smarty->assign("ROW",$values);
			$msg=$smarty->fetch("skypak.htm");			
		*/

		//	$header = "City"."||"."Request date"."||"."Request-ID"."||"."Name(Username)"."||"."Address"."||"."Phone_Res"."||"."Phone_Mob"."||"."Amount"."||"."Pref Time"."||"."Exec. Name"."||"."Exec_Phone";
                for($i=0;$i<count($values);$i++)
                {
                        $line=$values[$i]['city'] ."||".$values[$i]['entrydt']."||".$values[$i]['id']."||".$values[$i]['name']."(".$values[$i]['username'].")"."||".$values[$i]['address']."||".$values[$i]['phone_res']."||".$values[$i]['phone_mob']."||".$values[$i]['amount']."||".$values[$i]['pref_time']."||".$values[$i]['entryby']."||".$values[$i]['exec_ph']."||".$values[$i]['comments'];
                        //$line = str_replace("\n",' ',$line);
			$line=ereg_replace("\r\n|\n\r|\n|\r",",",str_replace("\"","'",$line));
                        $data .= trim($line)." \n";
                }
                //$attach="$header\n$data";
		$attach="$data";  	
		send_email_plain($email,$Cc,$Bcc,$msg,$subject,$from,$attach);


			$sql="UPDATE incentive.PAYMENT_COLLECT set STATUS='X1', ARAMEX_DT=now() ,ENTRYBY='cron_resend_script',ENTRY_DT=now() where ID in (".implode(",",$id_arr).")";
                        mysql_query_decide($sql) or logError("$sql".mysql_error_js());
			$idstring=implode(",",$id_arr);
			$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY,RESEND) values ('$idstring',now(),'cron_script','X')";
			mysql_query_decide($sql) or logError("$sql".mysql_error_js());
																     
	}
	else
		;

	unset($services);
	unset($id_arr);
	unset($values);
	unset($data);
        unset($line);
        unset($attach);
	
//******************************Code for closed cases ends here *********************************************************//	



//******************************Code for Resend cases Starts here*****************************************************//

	
	$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY,DISCOUNT from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where COURIER_TYPE='SKYPAK' and CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";

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
			$date_val = "Not specified";
															     
			if($myrow["ENTRY_DT"] != "0000-00-00 00:00:00")
			{
				$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,9);
			}
															     
			else
				$date_entry = "Not specified";
		//	$services_str="'".$myrow["MAIN_SER"]."'";
			$ser_str=$myrow["MAIN_SER"];
															     

			if($myrow["ADDON_SERVICEID"])
				{
					$addon_serviceid = $myrow["ADDON_SERVICEID"];
					$ser_str.=",".$addon_serviceid;
				}
			$service_arr=explode(",",$ser_str);
                        $services_str=implode("','",$service_arr);
                        $services_str="'".$services_str."'";
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
				$sql_amt="SELECT SUM(desltop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or logError("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
				//$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
                                $TAX_RATE = billingVariables::TAX_RATE;
                                $TAX=round((($TAX_RATE/100)*$due_amount),2);
                                //$costval=floor(($due_amount+$TAX)-$myrow["DISCOUNT"]);
                                $costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				if($costval>0)
				{
					$values[] = array("sno"=>$sno,
								"id"=>$idstr_to_show,
								"profileid"=>$myrow["PROFILEID"],
								"username"=>$myrow["USERNAME"],
								"name"=>$myrow["NAME"],
								"phone_res"=>$myrow["PHONE_RES"],
								"phone_mob"=>$myrow["PHONE_MOB"],
								"service"=>$service_names,
								"address"=>($myrow["ADDRESS"]),
								"entryby"=>$exec_phone["ALIASE_NAME"],
								"exec_ph"=>$exec_phone["PHONE_NO"],
								"entrydt"=>$date_entry,
								"comments"=>$myrow["COMMENTS"],
								"pref_time"=>$date_val,
								"courier"=>$myrow["COURIER_TYPE"],
								"city"=>$myrow["CITY"],
								"amount"=>$costval
							 );
					$id_sent_arr[]=$myrow["ID"];
				}													     
				 $sno++;
				unset($services);
			}
			$count_rows=count($id_sent_arr);
			$subject="(Resend Cases) Jeevansathi.com ".date("d-M-Y")." $count_rows records";
			$msg="";													    			
		/*	$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
			$smarty->assign("TODAY",$tdy);
			$smarty->assign("ROW",$values);
			$msg=$smarty->fetch("skypak.htm");			
		*/

		//	$header = "City"."||"."Request date"."||"."Request-ID"."||"."Name(Username)"."||"."Address"."||"."Phone_Res"."||"."Phone_Mob"."||"."Amount"."||"."Pref Time"."||"."Exec. Name"."||"."Exec_Phone";
		if(count($id_sent_arr)>0)
                {
			for($i=0;$i<count($values);$i++)
			{
				$line=$values[$i]['city'] ."||".$values[$i]['entrydt']."||".$values[$i]['id']."||".$values[$i]['name']."(".$values[$i]['username'].")"."||".$values[$i]['address']."||".$values[$i]['phone_res']."||".$values[$i]['phone_mob']."||".$values[$i]['amount']."||".$values[$i]['pref_time']."||".$values[$i]['entryby']."||".$values[$i]['exec_ph']."||".$values[$i]['comments'];
				//$line = str_replace("\n",' ',$line);
				$line=ereg_replace("\r\n|\n\r|\n|\r",",",str_replace("\"","'",$line));
				$data .= trim($line)." \n";
			}
			//$attach="$header\n$data";
			$attach="$data";  	
			send_email_plain($email,$Cc,$Bcc,$msg,$subject,$from,$attach);
				//send_email($email,$Cc,$Bcc,$msg,$subject,$from);                                                                                                                              
				//for($i=0;$i<count($id_arr);$i++)
				//{
				$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, ID,DISCOUNT FROM incentive.PAYMENT_COLLECT where ID in (".implode(",",$id_sent_arr).")";
				mysql_query_decide($sql3) or logError("$sql3".mysql_error_js());

				$sql="UPDATE incentive.PAYMENT_COLLECT set STATUS='', ARAMEX_DT=now() ,ENTRYBY='cron_resend_script',ENTRY_DT=now() where ID in (".implode(",",$id_sent_arr).")";
				mysql_query_decide($sql) or logError("$sql".mysql_error_js());
				//}

				$idstring=implode(",",$id_sent_arr);
				$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY,RESEND) values ('$idstring',now(),'cron_script','Y')";
				mysql_query_decide($sql) or logError("$sql".mysql_error_js());
		}																     
	}
	else
		;

?>
