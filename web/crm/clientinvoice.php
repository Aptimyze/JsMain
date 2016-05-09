<?php

include("connect.inc");
include("common.inc");
include ("display_result.inc");
$PAGELEN=10;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
                                                                                                 
$sno=$j+1;
if(authenticated($cid))
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
	
	if ($Dispatch)
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
                for($i=0;$i<count($id_arr);$i++)
                {
	                $sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,DISCOUNT,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,DISCOUNT, '$id_arr[$i]' FROM incentive.PAYMENT_COLLECT where ID='$id_arr[$i]'";
	                mysql_query_decide($sql3) or die("$sql3".mysql_error_js());
	
			$sql="UPDATE incentive.PAYMENT_COLLECT set AR_GIVEN='Y', ARAMEX_DT=now() ,ENTRYBY='$name',ENTRY_DT=now() where ID='$id_arr[$i]'";
			mysql_query_decide($sql);
			
		}
		if($cnt > 0)
                        $msg = "You have successfully dispatched $cnt records.<br>";
		else
			$msg = "Sorry, You have not selected any record<br><br>";
                                                                                                 
                $msg .= "<a href=\"clientinvoice.php?name=$name&cid=$cid";
		if($showall=="Y")
			$msg .= "&showall=Y";
                                                                                                 
                $msg .= "\">Continue &gt;&gt;</a>";
                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("crm_msg.tpl");
	}
	elseif($Print)
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
                for($i=0;$i<count($proid);$i++)
                {
			$invoice[$i]['letter']=printletter($proid[$i],$id_arr[$i]);
			$invoice[$i]['invoice']=printinvoice($proid[$i],$id_arr[$i]);	
		}
		
		$smarty->assign("INVOICE",$invoice);
		$smarty->assign("cid",$cid);
		$smarty->display("print_invoice.htm");

			
	}
	else
	{
		include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
		$serviceObj = new Services;
		// code added to show sharmil all the data for his west region 
		if($name=="sharmil")
			$sql="SELECT AR_BRANCH FROM ARAMEX_BRANCHES WHERE INVOICE_BRANCH LIKE 'MP%' OR INVOICE_BRANCH LIKE 'GU%' OR INVOICE_BRANCH LIKE 'MH%'";
		else
			$sql="SELECT AR_BRANCH FROM ARAMEX_BRANCHES WHERE INVOICE_BRANCH='$centre'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($myrow=mysql_fetch_array($result))
                {
                        $ar_branch[]=$myrow['AR_BRANCH'];
                }
		if(count($ar_branch)>0)
	                $ar=implode("','",$ar_branch);
		
		if($showall=="Y")
			$sql="SELECT count(*) from incentive.PAYMENT_COLLECT,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N'  and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
		else
			$sql="SELECT count(*) from incentive.PAYMENT_COLLECT,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN('$ar') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

		if($showall=="Y")
			$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,ADDON_SERVICEID,SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";// LIMIT $j,$PAGELEN";	
		else
			$sql="SELECT incentive.PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,ENTRY_DT,ADDON_SERVICEID,SERVICE,ADDRESS,COMMENTS,PREF_TIME,COURIER_TYPE,BRANCH_CITY.LABEL as CITY,PIN,ENTRYBY from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN ('$ar') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE LIMIT $j,$PAGELEN";	
		$result=mysql_query_decide($sql) or die(mysql_error_js());
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
				$date_entry = get_date_format($myrow["ENTRY_DT"]);
                        }

                        else
                                $date_entry = "Not specified";

                        //$services[] = $myrow["SERVICE"];
			if($myrow["ADDON_SERVICEID"])
				$serviceids=rtrim($myrow["SERVICE"],",").",".$myrow["ADDON_SERVICEID"];
			else
				$serviceids=rtrim($myrow["SERVICE"],",");
			$services=$serviceObj->getServiceName($serviceids);
                        /*if($myrow["ADDON_SERVICEID"])
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
                        }*/
			$service_names='';
			foreach($services as $k=>$v)
			{
				foreach($v as $k1=>$v1)
				{
					if($service_names=='')
						$service_names.=$v1;
					else
						$service_names.=",".$v1;
				}
			}
                       // echo $service_names = implode(",",$services);

			$values[] = array("sno"=>$sno,
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

                        $profileid = $myrow["PROFILEID"];
                        $sql_log = "Select * from incentive.LOG where PROFILEID = '$profileid'";
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
                                elseif ($myrow_log["CONFIRM"] == "Y")                                                                   $stage = "Confirm Client Stage";

                                $log_values[] = array("profileid"=>$myrow_log["PROFILEID"],
                                                        "username"=>$myrow_log["USERNAME"],
                                                        "comments"=>$myrow_log["COMMENTS"],
                                                        "entryby"=>$myrow_log["ENTRYBY"],
                                                        "pref_time"=>$myrow_log["PREF_TIME"],
                                                        "stage"=>$stage,
                                                        "pref_time"=>$date_val);
                        }

			$sno++;
			unset($services);
		}

                if( $j )
                        $cPage = ($j/$PAGELEN) + 1;
                else
                        $cPage = 1;
                pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"clientinvoice.php",'','','',$showall);
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
		$smarty->display("clientinvoice.htm");
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

function get_date_format($dt)
{
        $date_time_arr = explode(" ",$dt);
        $date_arr = explode("-",$date_time_arr[0]);
        $date_val = date("d-M-Y",mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]));
        return $date_val;

}


?>
