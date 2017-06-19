<?php
/* Description:-Cron script for sending all the confirmed requests to skypak for pickups */

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");
include(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include(JsConstants::$docRoot."/crm/common.inc");
include(JsConstants::$docRoot."/crm/func_sky.php");	
$db =connect_db();

	$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY,DISCOUNT,PREFIX_NAME,LANDMARK,AMOUNT from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where COURIER_TYPE='GHARPAY' and CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";

	$result=mysql_query_decide($sql) or logError("$sql".mysql_error_js());
	if(($count_rows = mysql_num_rows($result))>0)
	{
		while($myrow=mysql_fetch_array($result))
		{
			$ser_str		='';
			$services_str		='';
			$service_names_str	='';	
			unset($service_names_arr);
			$addon_serviceid	='';
			unset($addon_serviceid_ar);
			$pref_date_val 		='';
			$date_entry 		='';
			$contact_number 	='';
				
			if($myrow["PREF_TIME"] != "0000-00-00 00:00:00")
				$pref_date_val = get_date_format_1($myrow["PREF_TIME"]);
			else
				$pref_date_val = date("d/m/Y");

			// Service selected  
			$ser_str=$myrow["MAIN_SER"];
			if($myrow["ADDON_SERVICEID"]){
				$addon_serviceid = $myrow["ADDON_SERVICEID"];
				$ser_str.=",".$addon_serviceid;
			}
			$service_arr=explode(",",$ser_str);
			$services_str=implode("','",$service_arr);
			$services_str="'".$services_str."'";
                        $sql_ser="SELECT NAME from billing.SERVICES where SERVICEID IN($services_str)";
                        $result_ser=mysql_query_decide($sql_ser) or logError("$sql_ser=".mysql_error_js());
                        while($myrow_ser = mysql_fetch_array($result_ser))
                        	$service_names_arr[] =trim($myrow_ser["NAME"]);
                        $service_names_str =@implode(",",$service_names_arr);
			$quantity=1;
			

			$idstr_to_show =$myrow["ID"];

			// phone number added
			$phone_res =trim($myrow["PHONE_RES"]);
			$phone_mob =trim($myrow["PHONE_MOB"]);
			if($phone_res && $phone_mob)
				$contact_number =$phone_mob.", ".$phone_res;
			elseif($phone_res)
				$contact_number =$phone_res;
			elseif($phone_mob)
				$contact_number =$phone_mob;
			$amount =$myrow['AMOUNT'];

			$addressNew 	=ereg_replace("\r\n|\n\r",",",$myrow["ADDRESS"]);
			$landmarkNew	=ereg_replace("\r\n|\n\r",",",$myrow["LANDMARK"]);
			$emailNew 	=ereg_replace("\r\n|\n\r",",",$myrow["EMAIL"]);
			$commentsNew 	=ereg_replace("\r\n|\n\r",",",$myrow["COMMENTS"]);				

			if($amount>0){
				$values[] = array("sno"=>$sno,
							"id"=>$idstr_to_show,
							"profileid"=>$myrow["PROFILEID"],
							"username"=>trim($myrow["USERNAME"]),
							"name"=>trim($myrow["NAME"]),
							"contact_number"=>trim($contact_number),
							"product_desc"=>trim($service_names_str),
							"address"=>trim($addressNew),
							"entryby"=>trim($myrow["ENTRYBY"]),
							"comments"=>trim($commentsNew),
							"pref_time"=>trim($pref_date_val),
							"courier"=>trim($myrow["COURIER_TYPE"]),
							"city"=>trim($myrow["CITY"]),
							"amount"=>$amount,
							"pincode"=>trim($myrow['PIN']),
							"prefix_name"=>trim($myrow['PREFIX_NAME']),
							"landmark"=>trim($landmarkNew),
							"email_id"=>trim($emailNew),
							"quantity"=>$quantity	
						 );
				$id_sent_arr[]=$myrow["ID"];		        
			} 													     
			$sno++;
		}
		$count_rows=count($id_sent_arr);
							
		if($count_rows>0)
		{
			$header_name ="Prefix\tFirst Name\tLast Name\tContact Number\tAddress\tLandmark\tEmail\tPincode\tCity\tOrder ID\tOrder Amount\tDelivery Date\tInvoice URL\tProduct ID\tProduct Description\tQuantity\tUnit Price\tComments";
			$header_name .="\n";

			for($i=0;$i<count($values);$i++)
			{
				if($values[$i]['prefix_name']=='')
					$values[$i]['prefix_name'] = "Mr.";
				$line =$values[$i]['prefix_name']."\t".$values[$i]['name']."\t".$values[$i]['name']."\t".$values[$i]['contact_number']."\t".$values[$i]['address']."\t".$values[$i]['landmark']."\t".$values[$i]['email_id']."\t".$values[$i]['pincode']."\t".$values[$i]['city']."\t".$values[$i]['username']."\t".$values[$i]['amount']."\t".$values[$i]['pref_time']."\t\t\t".$values[$i]['product_desc']."\t".$values[$i]['quantity']."\t".$values[$i]['amount']."\t".$values[$i]['entryby'];

				$line=ereg_replace("\r\n|\n\r|\n|\r",",",$line);
				$data = trim($line)."\n";
				$output .= $data;
				unset($data);
				//fwrite($fp1,$output);		
				
				$prefix_name 	=$values[$i]['prefix_name'];
				$name		=$values[$i]['name'];
				$contact_number	=$values[$i]['contact_number'];
				$address	=$values[$i]['address'];
				$landmark	=$values[$i]['landmark'];
				$email_id	=$values[$i]['email_id'];
				$pincode	=$values[$i]['pincode'];
				$city		=$values[$i]['city'];
				$username	=$values[$i]['username'];
				$amount		=$values[$i]['amount'];
				$pref_time	=$values[$i]['pref_time'];
				$product_desc	=$values[$i]['product_desc'];
				$quantity	=$values[$i]['quantity'];
				$entryby	=$values[$i]['entryby'];	

				$sql ="INSERT INTO billing.GHARPAY_CSV_DATA(`PREFIX`,`FIRST_NAME`,`LAST_NAME`,`CONTACT_NUMBER`,`ADDRESS`,`LANDMARK`,`EMAIL`,`PINCODE`,`CITY`,`ORDER_ID`,`ORDER_AMOUNT`,`DELIVERY_DT`,`INVOICE_URL`,`PRODUCT_ID`,`PRODUCT_DESC`,`QUANTITY`,`UNIT_PRICE`,`COMMENTS`,`ENTRY_DT`) VALUES('$prefix_name','$name','$name','$contact_number','$address','$landmark','$email_id','$pincode','$city','$username','$amount','$pref_time','','','$product_desc','$quantity','$amount','$entryby',now())";
				mysql_query_decide($sql) or logError("$sql".mysql_error_js());	
			
			}
			$dataSet =$header_name.$output;
			$msg ="File name: gharPayCsvData".date('Y-m-d').".xls";
		        $from	="JeevansathiCrm@jeevansathi.com";
        		$to	="jeevansathigharpay@naukri.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,rohan.mathur@jeevansathi.com,resumeservice@naukri.com";
			//$to	="manoj.rana@naukri.com";
			$cc     ="tabassum.khan@naukri.com";
			$bcc	="manojrana975@gmail.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com";	
			$subject="Daily Ghar Pay cheque pick-up request data file";

			$filetype ="application/vnd.ms-excel";
			$filename ="gharPayCsvData".date('Y-m-d').".xls";
        		send_email($to,$msg,$subject,$from,$cc,$bcc,$dataSet,$filetype,$filename);

			if(!mysql_ping($db))
                                $db=connect_db();
			$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT,PREFIX_NAME,LANDMARK) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, ID,DISCOUNT,PREFIX_NAME,LANDMARK FROM incentive.PAYMENT_COLLECT where ID in (".implode(",",$id_sent_arr).")";
			mysql_query_decide($sql3) or logError("$sql3".mysql_error_js());

			$sql="UPDATE incentive.PAYMENT_COLLECT set AR_GIVEN='Y', ARAMEX_DT=now() ,ENTRYBY='cron_script',ENTRY_DT=now() where ID in (".implode(",",$id_sent_arr).")";
			mysql_query_decide($sql) or logError("$sql".mysql_error_js());

			$idstring=implode(",",$id_sent_arr);
			$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY) values ('$idstring',now(),'cron_script')";
			mysql_query_decide($sql) or logError("$sql".mysql_error_js());
		}															     
	}

	unset($services);
	unset($id_sent_arr);
	unset($values);
	unset($data);
	unset($line);

        function get_date_format_1($dt)
        {
                $date_time_arr = explode(" ",$dt);
                $time_arr=explode(":",$date_time_arr[1]);
                $date_arr = explode("-",$date_time_arr[0]);
                $date_val = date("d-M-y",mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]));
                return $date_val;
        }


?>
