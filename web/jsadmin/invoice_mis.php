<?php
include("connect.inc");
include("../profile/arrays.php");
include("../crm/common.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag",1);

		if($day)
		{
			$st_date=$year."-".$month."-".$day." 00:00:00";
			$end_date=$eyear."-".$emonth."-".$eday." 23:59:59";
		}
		else
		{
			$st_date=$year."-".$month."-01 00:00:00";
			$end_date=$eyear."-".$emonth."-31 23:59:59";
		}
		$sql="SELECT *  FROM incentive.INVOICE_TRACK WHERE TIME BETWEEN '$st_date' AND '$end_date' and RESEND='' ";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$sno=1;
		while($row=mysql_fetch_array($res))
		{
			$sent_ar=explode(",",$row["SENT_TO"]);
			foreach($sent_ar as $sid)
			{
			$sql_name="SELECT USERNAME FROM incentive.PAYMENT_COLLECT where ID ='$sid'" ;
        	        $res_name=mysql_query_decide($sql_name,$db) or die(mysql_error_js());
			$row_name=mysql_fetch_array($res_name);
			$sent_name[]=$row_name["USERNAME"];
			}
			$namestr=implode(", ",$sent_name);
			$details[]=array('sno'=>$sno++,
					'MAILID'=>$row['MAILID'],
					'SENT_TO'=>$namestr,
					'SENT_BY'=>$row['SENT_BY'],
					'TIME'=>$row['TIME']
				       );	
			unset($sent_ar);
			unset($sent_name);
		}

		$smarty->assign("res_arr",$details);

		$smarty->assign("day",$day);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("eday",$eday);
		$smarty->assign("eyear",$eyear);
		$smarty->assign("emonth",$emonth);
		$smarty->assign("cid",$cid);
		unset($details);
       		$smarty->display("invoice_mis.htm");
		
	}

	
	elseif($download)
	{
			$msg='';
			$sno=1;
	
			$sql="SELECT SENT_TO,TIME from incentive.INVOICE_TRACK where MAILID='$id'";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        $myrow=mysql_fetch_row($result);
			$pc_id=explode(',',$myrow[0]);
			$date_entry = substr(get_date_format($myrow[1]),0,11);
			$i=0;
        	        $j=0;
			foreach($pc_id as $pid)
		//	for($j=0;$j<count($pc_id);$j++)
			{
				$due_amount='';
				$costval='';
				$service_names='';
				$entry_val='';
				$entry_date='';
				$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,DISCOUNT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where  incentive.PAYMENT_COLLECT.ID='$pid' and CONFIRM='Y' and AR_GIVEN='Y' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";
			$result=mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
	//                      $address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
				if($myrow["PREF_TIME"] != "0000-00-00 00:00:00")
				{
					$date_val = get_date_format($myrow["PREF_TIME"]);
				}
				else
					$date_val = "Not specified";
				
			/*												     
				if($myrow["ENTRY_DT"] != "0000-00-00 00:00:00")
				{
					$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,11);
				//		$date_entry = get_date_format($myrow["ENTRY_DT"]);
				}
																     
				else
					$date_entry = "Not specified";*/
				
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
				$idstr__to_show='';
				$exec_phone='';
				$l=strlen($myrow["ID"]);
				if($l<10)
				{
					$tmp_len=10-$l;
					for($kk=0;$kk<$tmp_len;$kk++)
						$idstr__to_show.='0';
				}
					$idstr__to_show.=$myrow["ID"];
                                                                                                                             
                        	$exec_phone=get_phone_no($myrow["ENTRYBY"]);
															     
				$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or die("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
				//$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
				$TAX_RATE = billingVariables::TAX_RATE;
				$TAX=round((($TAX_RATE/100)*$due_amount),2);
				$costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				$values[] = array("sno"=>$sno,
							"id"=>$idstr__to_show,
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
	}
				 $sno++;
				//unset($services);
			}

			 $header = "City"."\t"."Request date"."\t"."Request-ID"."\t"."Name(Username)"."\t"."Address"."\t"."Phone_Res"."\t"."Phone_Mob"."\t"."Amount"."\t"."Pref Time"."\t"."Exec. Name"."\t"."Exec_Phone";
                                for($i=0;$i<count($values);$i++)
                                {
                                        $line=$values[$i]['city']."\t".$values[$i]['entrydt']."\t".$values[$i]['id']."\t".$values[$i]['name']."(".$values[$i]['username'].")"."\t".$values[$i]['address']."\t".$values[$i]['phone_res']."\t".$values[$i]['phone_mob']."\t"."Rs ".$values[$i]['amount']."\t".$values[$i]['pref_time']."\t".$values[$i]['entryby']."\t".$values[$i]['exec_ph'];
                                        //$line = str_replace("\t".'$', '', $line);
					//$line = str_replace("<br />",' ',$line);
				//	$line = str_replace("\t",'&',$line);
				//	$line = str_replace("\n",'&',$line);
					$line=ereg_replace("\r\n|\n\r|\n|\r"," , ",str_replace("\"","'",$line));
                                        $data .= trim($line)."\t \n";
                                }
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition:attachment; filename=sky.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				echo $output = $header."\n".$data;
	}




	
	elseif($mail)
	{
		$name=getname($cid);
		$cnt=0;
		foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "cb" )
                        {
                                $cnt=$cnt+1;
                                $id=ltrim($key, "cb");
                                $id_arr[] = $id;
                                                                                                                             
                        }
                }
		foreach($id_arr as $id)
		{
			$msg='';
			$sno=1;
	
			$sql="SELECT SENT_TO from incentive.INVOICE_TRACK where MAILID='$id'";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        $myrow=mysql_fetch_row($result);
			$pc_id=explode(',',$myrow[0]);
			$i=0;
        	        $j=0;
			foreach($pc_id as $pid)
		//	for($j=0;$j<count($pc_id);$j++)
			{
				$due_amount='';
				$costval='';
				$service_names='';
				$entry_val='';
				$entry_date='';
				$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,DISCOUNT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where  incentive.PAYMENT_COLLECT.ID='$pid' and CONFIRM='Y' and AR_GIVEN='Y' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";
			$result=mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
	//                      $address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
				if($myrow["PREF_TIME"] != "0000-00-00 00:00:00")
				{
					$date_val = get_date_format($myrow["PREF_TIME"]);
				}
				else
					$date_val = "Not specified";
																     
				if($myrow["ENTRY_DT"] != "0000-00-00 00:00:00")
				{
					$date_entry = substr(get_date_format($myrow["ENTRY_DT"]),0,11);
				//		$date_entry = get_date_format($myrow["ENTRY_DT"]);
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
				$idstr__to_show='';
				$exec_phone='';
				$l=strlen($myrow["ID"]);
				if($l<10)
				{
					$tmp_len=10-$l;
					for($kk=0;$kk<$tmp_len;$kk++)
						$idstr__to_show.='0';
				}
					$idstr__to_show.=$myrow["ID"];
                                                                                                                             
                        	$exec_phone=get_phone_no($myrow["ENTRYBY"]);
															     
				$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
				$result_amt=mysql_query_decide($sql_amt) or die("$sql_amt<br>".mysql_error_js());
				$myrow_amt = mysql_fetch_array($result_amt);
				//$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
				$due_amount=$myrow_amt["amt"];
				$TAX_RATE = billingVariables::TAX_RATE;
				$TAX=round((($TAX_RATE/100)*$due_amount),2);
				$costval=floor(($due_amount)-$myrow["DISCOUNT"]);
				$values[] = array("sno"=>$sno,
							"id"=>$idstr__to_show,
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
	}
				 $sno++;
				//unset($services);
			}

			$subject="Jeevansathi.com ".date("d-M-Y")." ".count($pc_id)." records";
			$from="skypak@jeevansathi.com";
			$email="pickup@skyfin.com,del@skyfin.com,annie@skyfin.com,samirgupta@skyfin.com,navin@skyfin.com";
			//$email="alok@jeevansathi.com";
			$Cc="rajeev@jeevansathi.com,rizwan@naukri.com,mahesh@jeevansathi.com,alok@jeevansathi.com";
			$Bcc="aman.sharma@jeevansathi.com";
			
			$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
	                $smarty->assign("TODAY",$tdy);
			$smarty->assign("ROW",$values);
			$smarty->assign("cid",$cid);
			$msg=$smarty->fetch("../crm/skypak.htm");
														     
			send_mail_again($email,$Cc,$Bcc,$msg,$subject,$from);                                                                                                                             
                $idstring=implode(",",$pc_id);
                $sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY,RESEND) values ('$idstring',now(),'$name','Y')";
                        mysql_query_decide($sql) or die("$sql".mysql_error_js());
                unset($values);
		unset($pc_id);
	}
	$msg="Mail sent successfully to  previous mailid -".implode(",",$id_arr);
        $msg .= "<a href='invoice_mis.php?name=$name&cid=$cid'>Back</a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}	
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("invoice_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}

function get_date_format($dt)
{
        $date_time_arr = explode(" ",$dt);
        $time_arr=explode(":",$date_time_arr[1]);
        $date_arr = explode("-",$date_time_arr[0]);
        $date_val = date("d-M-Y H:i:s",mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]));
        return $date_val;
                                                                                                                             
}

function send_mail_again($email,$Cc,$Bcc,$msg,$subject,$from)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
	fputs($fd, "Cc: $Cc\n");
        fputs($fd, "Bcc: $Bcc\n");
        fputs($fd, "From: $from \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}
function get_phone_no($exe)
{
        $sql_no="SELECT ALIASE_NAME,PHONE_NO from incentive.EXECUTIVES where EXE_NAME='$exe'";
        $result_no = mysql_query_decide($sql_no);
        $row_exec=mysql_fetch_array($result_no);
	if(!$row_exec['ALIASE_NAME'])
        {
                $row_exec['ALIASE_NAME']='rajeev';
                $row_exec['PHONE_NO']='0120-5303116';
        }

        return $row_exec;
}


?>
