<?php

include("connect.inc");
include("common.inc");
include ("display_result.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/../lib/model/enums/Membership.enum.class.php");

$PAGELEN=10;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
                                                                                                 
$sno=$j+1;

if(authenticated($cid))
//if(1)
{
	$name= getname($cid);
	$center_label=get_centre($cid);
	if($center_label!="HO")	
	{
	        $sql="SELECT VALUE from BRANCH_CITY where UPPER(LABEL) ='".strtoupper($center_label)."'";        $myrow=mysql_fetch_array(mysql_query_decide($sql));
        	$centre=$myrow['VALUE'];
	}
	else
		$centre="HO";	
//	if($centre=="HO" || $name == "mahesh")
//		$smarty->assign("showlink","Y");

	if($privilage = getprivilage($cid))
	{
		$priv_arr = explode("+",$privilage);
		if(is_array($priv_arr))
		{
			if(in_array('PSA',$priv_arr))
				$smarty->assign("showlink","Y");
		}
	}	  
	
	if($Mail)
        {
                $cnt=0;
                foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "cb" )
                        {
                                $cnt=$cnt+1;
                                list($pid ,$id)= explode("|X|",ltrim($key, "cb"));
                                $proid[] = $pid;
                                $id_arr[] = $id;
                                                                                                                             
                        }
                }
                $i=0;
		$j=0;
                for($j=0;$j<count($proid);$j++)
                {
			$due_amount='';	
			$costval='';
			$service_names='';
			$entry_val='';
			$entry_date='';
			$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,PAYMENT_COLLECT.SERVICE as MAIN_SER,ADDON_SERVICEID,SERVICES.NAME as SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,DISCOUNT,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where PROFILEID=$proid[$j] and  incentive.PAYMENT_COLLECT.ID=$id_arr[$j] and CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE order by PAYMENT_COLLECT.CITY";
                $result=mysql_query_decide($sql) or die(mysql_error_js());
                while($myrow=mysql_fetch_array($result))
                {
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
                                $result_services = mysql_query_decide($sql) or die(mysql_error_js());
                                while($myrow_result_services = mysql_fetch_array($result_services))
                                {
                                        $services[] = "<br>".$myrow_result_services["NAME"];
			
				}
                        }
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
       	                $service_names = implode(",",$services);
			$sql_amt="SELECT SUM(desktop_RS) as amt from billing.SERVICES where SERVICEID in ($services_str)";
		        $result_amt=mysql_query_decide($sql_amt) or die("$sql_amt<br>".mysql_error_js());
			$myrow_amt = mysql_fetch_array($result_amt);
		//	$due_amount=$myrow_amt["amt"]-$myrow["DISCOUNT"];
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
                        unset($services);
		}

        	$subject="Jeevansathi.com ".date("d-M-Y")." ".count($proid)." records";
		$from="skypak@jeevansathi.com";
	
		//$email="aman.sharma";	
                $email="pickup@skyfin.com,del@skyfin.com,annie@skyfin.com,samirgupta@skyfin.com,navin@skyfin.com";
	        //$email="alok@jeevansathi.com";
                $Cc="rajeev@jeevansathi.com,rizwan@naukri.com,mahesh@jeevansathi.com";
                $Bcc="aman.sharma@jeevansathi.com";
	
		$tdy=substr(get_date_format(Date('Y-m-d')),0,11);
		$smarty->assign("TODAY",$tdy);
		$smarty->assign("ROW",$values);
                $smarty->assign("cid",$cid);
                $msg=$smarty->fetch("skypak.htm");
        
		send_email($email,$msg,$subject,$from,$Cc,$Bcc);                                                                                  
		unset($values);
	
	 
         	for($i=0;$i<count($id_arr);$i++)
                {
                        $sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,DISCOUNT,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,DISCOUNT, '$id_arr[$i]' FROM incentive.PAYMENT_COLLECT where ID='$id_arr[$i]'"; 
                        mysql_query_decide($sql3) or die("$sql3".mysql_error_js());

                                                                                                                             
                        $sql="UPDATE incentive.PAYMENT_COLLECT set STATUS='', ARAMEX_DT=now() ,ENTRYBY='$name',ENTRY_DT=now() where ID='$id_arr[$i]'";              
		        mysql_query_decide($sql) or die("$sql".mysql_error_js());

                                                                                                                             
                }
		
		$idstring=implode(",",$id_arr);	
		$sql="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY,RESEND) values ('$idstring',now(),'$name','Y')";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());

                      
		$msg="Mail has been sent successfully";	
       		$msg .= "<a href='resend_mail_for_skypak.php?name=$name&cid=$cid'>Back</a>";
		$smarty->assign("MSG",$msg);
	        $smarty->display("jsadmin_msg.tpl");
        }
	else
	{
                $sql="SELECT AR_BRANCH FROM ARAMEX_BRANCHES WHERE INVOICE_BRANCH='$centre'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($myrow=mysql_fetch_array($result))
                {
                        $ar_branch[]=$myrow['AR_BRANCH'];
                }
		if(count($ar_branch)>0)
	                $ar=implode("','",$ar_branch);
		
		if($showall=="Y")
			$sql="SELECT count(*) from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
		else
			$sql="SELECT count(*) from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN('$ar') and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

		if($showall=="Y")
			$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,ADDON_SERVICEID,SERVICES.NAME as SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ORDER BY incentive.PAYMENT_COLLECT.ID LIMIT $j,$PAGELEN";
		else
			$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,ADDON_SERVICEID,SERVICES.NAME as SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN ('$ar') and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ORDER BY incentive.PAYMENT_COLLECT.ID LIMIT $j,$PAGELEN";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$index=0;
		while($myrow=mysql_fetch_array($result))
		{
//			$address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
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

                        $services[] = $myrow["SERVICE"];
                        if($myrow["ADDON_SERVICEID"])
                        {
                                $addon_serviceid = $myrow["ADDON_SERVICEID"];
                                $addon_serviceid_ar = explode(",",$addon_serviceid);
                                for($i=0;$i<count($addon_serviceid_ar);$i++)
                                        $addon_serviceid_ar[$i]="'".$addon_serviceid_ar[$i]."'";
                                $addon_serviceid_str = implode(",",$addon_serviceid_ar);

                                $sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
                                $result_services = mysql_query_decide($sql) or die(mysql_error_js());
                                while($myrow_result_services = mysql_fetch_array($result_services))
                                {
                                        $services[] = "<br>".$myrow_result_services["NAME"];

                                }
                        }

                        $service_names = implode(",",$services);

			$values[$index] = array("sno"=>$sno,
						"id"=>$myrow["ID"],
						"profileid"=>$myrow["PROFILEID"],
						"username"=>$myrow["USERNAME"],
						"name"=>$myrow["NAME"],
					  	"phone_res"=>$myrow["PHONE_RES"],
						"phone_mob"=>$myrow["PHONE_MOB"],
						"service"=>$service_names,
					  	"address"=>nl2br($myrow["ADDRESS"]),
						"entryby"=>$myrow["ENTRYBY"],
						"entrydt"=>$date_entry,
						"comments"=>$myrow["COMMENTS"],
                                          	"stage"=>"Confirm Client Stage",
						"pref_time"=>$date_val,
						"courier"=>$myrow["COURIER_TYPE"],
						"city"=>$myrow["CITY"]
					 );

                        $sql_log = "Select * from incentive.LOG where REF_ID = '$myrow[ID]'";
                        $result_log = mysql_query_decide($sql_log);
                        while($myrow_log = mysql_fetch_array($result_log))
                        {
                                if($myrow_log["PREF_TIME"] != "")
                                {
					$date_val = get_date_format($myrow_log["PREF_TIME"]); 
                                }
                                else
                                        $date_val = "";
                                if ($myrow_log["CONFIRM"] == "")
                                        $stage = "Payment Entry Stage";
                                elseif ($myrow_log["CONFIRM"] == "Y")
					$stage = "Confirm Client Stage";

                                $log_values[$index][] = array("profileid"=>$myrow_log["PROFILEID"],
                                                        "username"=>$myrow_log["USERNAME"],
                                                        "comments"=>$myrow_log["COMMENTS"],
                                                        "entryby"=>$myrow_log["ENTRYBY"],
                                                        "pref_time"=>$myrow_log["PREF_TIME"],
                                                        "stage"=>$stage,
                                                        "pref_time"=>$date_val);
                        }

			$sno++;
			unset($services);
			$index++;
		}

                if( $j )
                        $cPage = ($j/$PAGELEN) + 1;
                else
                        $cPage = 1;
                pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"resend_mail_for_skypak.php",'','','',$showall);
                $smarty->assign("COUNT",$TOTALREC);
                $smarty->assign("CURRENTPAGE",$cPage);
                $no_of_pages=ceil($TOTALREC/$PAGELEN);
                $smarty->assign("NO_OF_PAGES",$no_of_pages);
		$smarty->assign("ROW",$values);
                $smarty->assign("LOG",$log_values);
		$smarty->assign("name",$name);
		if($showall=="Y")
			$smarty->assign("showall","Y");
		$smarty->assign("cid",$cid);
		$smarty->display("resend_mail_for_skypak.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

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

function get_date_format($dt)
{
        $date_time_arr = explode(" ",$dt);
	$time_arr=explode(":",$date_time_arr[1]);
        $date_arr = explode("-",$date_time_arr[0]);
        $date_val = date("d-M-Y H:i:s",mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]));
        return $date_val;

}
?>
