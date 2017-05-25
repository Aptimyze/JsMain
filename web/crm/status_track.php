<?php

include("connect.inc");
include ("display_result.inc");
if(authenticated($cid))
{
	//*** code portion added by Aman for search  *************************************************************//
	if($Search)
	{
		$sql="select * from incentive.PAYMENT_COLLECT where USERNAME='$sel_name' order by ID DESC ";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$myrow=mysql_fetch_array($result);
		if($myrow["BYUSER"] == "" && $myrow["CONFIRM"] == "")
			$stage = "Contact details entered ";
		elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "")
			$stage = "Request for Pickup confirmed";
		elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "N")
			$stage="Client Invoice discarded";
		elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "Y" && $myrow["STATUS"] == "")
			$stage = "Invoice dispatched";
		elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "Y" && $myrow["STATUS"] != "")
		{
			if($myrow["STATUS"] == "C")
				$stage = "Payment collected";
			elseif($myrow["STATUS"] == "S")
				$stage = "Payment collected and Service start";
			elseif($myrow["STATUS"] == "D")
				$stage = "Payment declined";
			elseif($myrow["STATUS"] == "R")
				$stage = "Resent for payment collection";
			elseif($myrow["STATUS"] == "X" || $myrow["STATUS"] == "X1")
				$stage = "Case Closed";
		}
		$date_status_val = get_date_format($myrow["ENTRY_DT"]);
		$values[] = array(      "sno"=>$sno,
					"ID"=>$myrow["ID"],
					"REF_ID"=>"",
					"USERNAME"=>$myrow["USERNAME"],
					"NAME"=>$myrow["NAME"],
					"DISCOUNT"=>$myrow["DISCOUNT"],
					"BEGIN_DATE"=>$date_status_val,
					"STATUS_DATE"=>$date_status_val,
					"STATUS"=>$stage,
					"comments"=>$myrow["COMMENTS"],
					"entryby"=>$myrow["ENTRYBY"],
                                        "LOG"=>'N');
		$smarty->assign("show_search","Y");
                //$smarty->assign("showall",$showall);
                $smarty->assign("LOG",$log_values);
                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("ROWS",$values);
                $smarty->display("status_track.htm");
		exit();

	}
//************************end of code for search *******************************************************************//
		
	if ($showall == 'Y')
	{
		$PAGELEN=20;
		$LINKNO=10;
		$START=1;
		if (!$j )
			$j = 0;
		$sno = $j + 1;
	}
	if($remove)
	{
		
		if($log == 'Y')
		{
			//$sql = "Update incentive.LOG set DISPLAY = 'N' where ID = $id";
			$sql = "Update incentive.LOG set DISPLAY = 'N' where REF_ID = '$ref_id'";
			mysql_query_decide($sql) or die(mysql_error_js());	
       
			$sql = "Update incentive.PAYMENT_COLLECT  set DISPLAY = 'N' where ID = '$id'";
			mysql_query_decide($sql) or die(mysql_error_js());
			echo "<font color=blue>$user</font> successfully discarded";
		}
		elseif( $log == 'N')
		{
			$sql = "Update incentive.PAYMENT_COLLECT set DISPLAY = 'N' where ID ='$id'";
			mysql_query_decide($sql) or die(mysql_error_js());
			echo "<font color=blue>$user</font> successfully discarded";
		}
	
	}
	else
	{
		$user = getname($cid);

		/*****************************************/
                // changes made by shobha on 22.12.2005
                // in case user is crm admin
                // link 'Show all' is to be shown'
                // following query changed for the same
                /*****************************************/
	        if($privilage = getprivilage($cid))
        	{
                	$priv_arr = explode("+",$privilage);
                	if(is_array($priv_arr))
                	{
                        	if(in_array('PSA',$priv_arr))
					$showlink = 'Y';
                	}
			if(is_array($priv_arr))
                        {
                                if(in_array('IUI',$priv_arr))
                                        $show_search = 'Y';
                        }

        	}
		if($showall!='Y')
		{
			if ($status_type=='')
				$status_type='pending';
			$smarty->assign("status_type",$status_type);
		}
		else
			$smarty->assign("status_type","");
		
		if($status_type!='det_enter')
		{
			if ($showall == 'Y')
			{
				// query added to find 'TOTALREC' for pagelink function (in case of crm admin)

				//$sql_cnt = "SELECT SQL_CALC_FOUND_ROWS distinct(REF_ID) from incentive.LOG where BYUSER = 'Y' AND CONFIRM = 'Y' AND AR_GIVEN = '' AND STATUS = '' AND DISPLAY <> 'N'";

				 $sql_cnt = "SELECT SQL_CALC_FOUND_ROWS DISTINCT (l.REF_ID) FROM incentive.LOG l LEFT JOIN incentive.PAYMENT_COLLECT p ON l.REF_ID = p.ID WHERE l.BYUSER = 'Y' AND l.CONFIRM = 'Y' AND l.AR_GIVEN = '' AND l.STATUS = '' AND l.DISPLAY <> 'N' AND p.DISPLAY <> 'N'";

				$result_cnt = mysql_query_decide($sql_cnt) or die(mysql_error_js());

				$csql = "Select FOUND_ROWS()";
				$cres = mysql_query_decide($csql) or die(mysql_error_js());
				$crow = mysql_fetch_row($cres);

				//$sql_pid ="select distinct(REF_ID) from incentive.LOG where BYUSER = 'Y' AND CONFIRM = 'Y' AND AR_GIVEN = '' AND STATUS = '' AND DISPLAY <> 'N' LIMIT $j,$PAGELEN";
				$sql_pid ="SELECT DISTINCT (l.REF_ID) FROM incentive.LOG l LEFT JOIN incentive.PAYMENT_COLLECT p ON l.REF_ID = p.ID WHERE l.BYUSER = 'Y' AND l.CONFIRM = 'Y' AND l.AR_GIVEN = '' AND l.STATUS = '' AND l.DISPLAY <> 'N' AND p.DISPLAY <> 'N' LIMIT $j,$PAGELEN";
			}
			else
			{
				$sql_pid = "Select distinct(REF_ID) from incentive.LOG where ENTRYBY = '$user' AND BYUSER = 'Y' AND CONFIRM = 'Y' AND AR_GIVEN = '' AND STATUS = '' AND DISPLAY <> 'N'";
			}
			if ($showall == 'Y')
				$TOTALREC = $crow[0];
			else
				$sno = 1;

			$result_pid = mysql_query_decide($sql_pid) or die(mysql_error_js());

			while($myrow_pid = mysql_fetch_array($result_pid))
			{
				$pid = $myrow_pid["PROFILEID"];
				$sql_begin="Select ENTRY_DT from incentive.LOG where REF_ID='$myrow_pid[REF_ID]' and BYUSER = 'Y' AND CONFIRM = 'Y' AND AR_GIVEN = '' AND STATUS = '' AND DISPLAY <> 'N'";
				$result_begin = mysql_query_decide($sql_begin) or die(mysql_error_js());
				$myrow_begin = mysql_fetch_array($result_begin);
				$days=days_diff($myrow_begin["ENTRY_DT"]);
				$date_begin_val = get_date_format($myrow_begin["ENTRY_DT"]);
				$sql = "Select * from incentive.PAYMENT_COLLECT where ID = '$myrow_pid[REF_ID]' AND DISPLAY <> 'N'  ";
				if($showall!='Y' && $status_type!='all')
				{
					$sql.=" and ";
					if($status_type=="pending" || $status_type=='')
					{
						$sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='Y' and STATUS='' ";
					}
					if($status_type=="discarded")
					{
						$sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='N' ";
					}
					if($status_type=="collected")
					{
						$sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='Y' and STATUS='C' ";
					}
					if($status_type=="serv_start")
                                        {
                                                $sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='Y' and STATUS='S' ";
                                        }
					if($status_type=="denied")
					{
						$sql.="  BYUSER='Y' AND  CONFIRM='Y' and AR_GIVEN='Y' and STATUS='D' ";
					}
					if($status_type=="resent")
					{
						$sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='Y' and STATUS='R' ";
					}
					if($status_type=="closed")
					{
						$sql.="  BYUSER='Y' AND CONFIRM='Y' and AR_GIVEN='Y' and STATUS IN('X','X1') ";
					}
				}
				else
					$sql.="  and BYUSER='Y'";
				$result = mysql_query_decide($sql) or die(mysql_error_js());
				if(mysql_num_rows($result))
				{
					unset($red);
					$myrow = mysql_fetch_array($result);
					$date_status_val = get_date_format($myrow["ENTRY_DT"]);

					if($myrow["BYUSER"] == "" && $myrow["CONFIRM"] == "")
						$stage = "Contact details entered ";
					elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "")
						$stage = "Request for Pickup confirmed";
					elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "N")
						$stage="Client Invoice discarded";
					elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "Y" && $myrow["STATUS"] == "")		
					{
					if($days>10)
						$red='Y';
						$stage = "Invoice dispatched";
					}
					elseif($myrow["BYUSER"] == "Y" && $myrow["CONFIRM"] == "Y" && $myrow["AR_GIVEN"]== "Y" && $myrow["STATUS"] != "")
					{
						if($myrow["STATUS"] == "C")
							$stage = "Payment collected";
						elseif($myrow["STATUS"] == "S")
							$stage = "Payment collected and Service start";
						elseif($myrow["STATUS"] == "D")	
							$stage = "Payment declined";
						elseif($myrow["STATUS"] == "R")
							$stage = "Resent for payment collection";
						elseif($myrow["STATUS"] == "X" || $myrow["STATUS"] == "X1")
							$stage = "Case Closed";
					}
					$username = $myrow["USERNAME"];
					$sql_log = "Select distinct * from incentive.LOG where REF_ID = $myrow_pid[REF_ID] order by ID ";
					$result1=mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());
					while($myrow1=mysql_fetch_array($result1))
					{ 
						$log_values[] = array("profileid"=>$myrow1["PROFILEID"],
									"comments"=>$myrow1["COMMENTS"],
									"entryby"=>$myrow1["ENTRYBY"],
									"logid"=>$myrow1["REF_ID"]
									);
					}
					$values[] = array(	"sno"=>$sno,
								"ID"=>$myrow["ID"],
								"REF_ID"=>$myrow_pid["REF_ID"],	
								"USERNAME"=>$myrow["USERNAME"],
								"NAME"=>$myrow["NAME"],
								"DISCOUNT"=>$myrow["DISCOUNT"],
								"STATUS_DATE"=>$date_status_val,
								"STATUS"=>$stage,
								"BEGIN_DATE"=>$date_begin_val,
								"comments"=>$myrow["COMMENTS"],
								"entryby"=>$myrow["ENTRYBY"],
								"LOG"=>'Y',
								"RED"=>$red
									);
					$sno++; 				
				}
			}
		}
		if ($showall == 'Y')
			$sno=$j+1;

		/*****************************************/
		// changes made by shobha on 22.12.2005 
		// in case user is crm admin
		// link 'Show all' is to be shown'
		// following query changed for the same
		/*****************************************/
		//if($showall!='Y' && $status_type=="det_enter")
		if($status_type=="det_enter" || $status_type=="all")
		{
			$sql = "Select * from incentive.PAYMENT_COLLECT where ENTRYBY = '$user' AND BYUSER = 'Y' AND CONFIRM = 'Y' AND AR_GIVEN = '' AND STATUS = '' AND DISPLAY <> 'N'";
			$result = mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow = mysql_fetch_array($result))
			{
				$date_status_val = get_date_format($myrow["ENTRY_DT"]);
				$stage = "Contact details entered ";
				$date_begin_val = get_date_format($myrow["ENTRY_DT"]);
		
				$username = $myrow["USERNAME"];
				$values[] = array(      "sno"=>$sno,
							"ID"=>$myrow["ID"],
							"REF_ID"=>"",
							"USERNAME"=>$myrow["USERNAME"],
							"NAME"=>$myrow["NAME"],
							"DISCOUNT"=>$myrow["DISCOUNT"],
							"STATUS_DATE"=>$date_status_val,
							"STATUS"=>$stage,
							"BEGIN_DATE"=>$date_begin_val,
							"comments"=>$myrow["COMMENTS"],
							"entryby"=>$myrow["ENTRYBY"],
							"LOG"=>'N');

				$sno++;
			}
		}
		if ($showall == 'Y')
		{
			if( $j )
				$cPage = ($j/$PAGELEN) + 1;
			else
				$cPage = 1;

			pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"status_track.php",'','','',$showall);
			$smarty->assign("COUNT",$TOTALREC);
			$smarty->assign("CURRENTPAGE",$cPage);
			$no_of_pages=ceil($TOTALREC/$PAGELEN);
			$smarty->assign("NO_OF_PAGES",$no_of_pages);
		}
		$smarty->assign("show_search",$show_search);
		$smarty->assign("showlink",$showlink);
		$smarty->assign("showall",$showall);
		$smarty->assign("LOG",$log_values);
		$smarty->assign("name",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("ROWS",$values);
		$smarty->display("status_track.htm");
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
function days_diff($date1)
{
	$date2=date('Y-m-d');
	$date1_arr=explode(" ",$date1);
	$d1 = explode("-",$date1_arr[0]);
	$d2=  explode("-",$date2);
	$tm1=mktime(0,0,0,$d1[1],$d1[2],$d1[0]);
	$tm2=mktime(0,0,0,$d2[1],$d2[2],$d2[0]);
	$tm=$tm2-$tm1;
	$days=$tm/(24 * 60 * 60);
	return $days;
}
?>
