<?php
/**************************************************************************************************************************
	*	FILENAME     : 	skypak_no_bill_mis.php
	*	CREATED BY   : 	Aman Sharma
	*       DESCRIPTION  : 	This script gives details of those requests for which billing has not been done. 
	*	FILE INCLUDED :  connect.inc
**************************************************************************************************************************/

include_once("connect.inc");

$db=connect_misdb();
$db2=connect_master();
$city_sel=$city;
if(authenticated($cid))
{
	{	
		$flag = 1;
		if ($allrecords == 1)
		{
			$st_date=$year."-".$month."-01 00:00:00";
                	$end_date=$year."-".$month."-31 23:59:59";
		}
		else
		{
			$st_date=$year."-".$month."-$day 00:00:00";
			$end_date=$year."-".$month."-$day 23:59:59";
		}
		
		// query to find MAILID of those to whom the mail was sent
		$sql="SELECT SENT_TO , SENT_BY,TIME  FROM incentive.INVOICE_TRACK WHERE TIME BETWEEN '$st_date' AND '$end_date' AND SENT_TO <> '' AND RESEND <>'Y'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                $i = 0;
                while($row=mysql_fetch_array($res))
                {
			$sent_time=$row["TIME"];
                        $sent_arr = explode(",",$row["SENT_TO"]);
			if (count($sent_arr) > 1)
				$sentstring = implode("','",$sent_arr);
			else
				$sentstring = $sent_arr[0];
			$j = 0;
			
			$sql_details = "SELECT ID ,PROFILEID, CITY, USERNAME , PHONE_RES, PHONE_MOB, STATUS FROM incentive.PAYMENT_COLLECT where ID IN ('$sentstring') AND STATUS IN ('S','') ";
			if($city_sel!='')
				$sql_details .=" and CITY='$city_sel' ";
			$sql_details .=" ORDER BY ID ASC";
			$res_details=mysql_query_decide($sql_details,$db) or die("$sql_details".mysql_error_js());
			while($row_details=mysql_fetch_array($res_details))
			{
				$billing=check_Billing($row_details["PROFILEID"],$sent_time);
				if($billing!='Y')
				{
					$id=$row_details["ID"];
					$sql="SELECT ENTRYBY FROM incentive.LOG WHERE REF_ID='$id' LIMIT 1";
					$res_entryby=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					$row_entryby=mysql_fetch_array($res_entryby);
					$sent_name[$i][$j]["ENTRYBY"]=$row_entryby["ENTRYBY"];

                        		$sent_name[$i][$j]["name"]= $row_details["USERNAME"];
					$city=label_select("CITY_INDIA",$row_details['CITY']);

					if ($row_details["STATUS"] == "C"){
                                		$stage = "Payment collected";
						$row_color="orange";
					}
                                	elseif($row_details["STATUS"] == "S"){
                                		$stage = "Payment collected and Service start";
						$row_color="green";
					}
                                	elseif($row_details["STATUS"] == "D"){
                                		$stage = "Payment declined";
						$row_color="grey";
					}
                                	elseif($row_details["STATUS"] == "R"){
                                 		$stage = "Resent for payment collection";
						$row_color="grey";
					}
					elseif($row_details["STATUS"] == ''){
						$stage = "No Response";
						$row_color="red";
					}
					elseif($row_details["STATUS"] == 'X' || $row_details["STATUS"] == 'X1'){
                                                $stage = "Case Closed";
                                                $row_color="grey";
                                        }
					$sent_name[$i][$j]["status"]=$stage;
					$sent_name[$i][$j]["CITY"]=$city[0];
					$sent_name[$i][$j]["REQID"]=$row_details["ID"];
					$sent_name[$i][$j]["PHONE"]="Res : ".$row_details["PHONE_RES"]."<br>Mob : ".$row_details["PHONE_MOB"];
					$sent_name[$i][$j]["ROWCOLOR"]=$row_color;
					$j++;
				}
                        }
			$i++;
                        $details[]=array("sno"=>$i,
					 "SENTBY"=>$row["SENT_BY"],
					 "MAILID"=>$row["MAILID"]);
                }
		$smarty->assign("flag",$flag);
		$smarty->assign("details",$details);
		$smarty->assign("sent_name",$sent_name);
                $smarty->assign("cid",$cid);
		$smarty->assign("day",$day);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$city_lbl=label_select('BRANCH_CITY',$city_sel,'incentive'); 
                $smarty->assign("CITY_LBL",$city_lbl[0]);
                unset($details);
		unset($sent_name);
                $smarty->display("skypak_no_bill_misdetails.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}

function check_Billing($profileid,$sent_time)
{
	global $db;

	$sql_chk="SELECT count(*) as cnt from billing.PURCHASES where PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT >'$sent_time' ";
	$res_chk=mysql_query_decide($sql_chk,$db) or die("$sql".mysql_error_js());
	$row_chk=mysql_fetch_array($res_chk);

	if($row_chk["cnt"]>0)
		$bill='Y';

	return $bill;
}
/*else
{
        $smarty->display("jsconnectError.tpl");
}*/
?>
