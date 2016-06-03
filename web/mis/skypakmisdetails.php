<?php
/**************************************************************************************************************************
	*	FILENAME     : 	skypakmis.php
	*	CREATED BY   : 	Shobha Kumari
	*       DESCRIPTION  : 	This script gives a daily mis of the username and membership status to whom 
				the mail (skypak) has been sent.
	*	FILE INCLUDED :  connect.inc
**************************************************************************************************************************/

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	{	
		$flag = 1;
		$st_date=$year."-".$month."-$day 00:00:00";
		$end_date=$year."-".$month."-$day 23:59:59";
		
		// query to find MAILID of those to whom the mail was sent
		$sql="SELECT SENT_TO , SENT_BY  FROM incentive.INVOICE_TRACK WHERE TIME BETWEEN '$st_date' AND '$end_date' AND SENT_TO <> '' AND RESEND=''";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                $i = 0;
                while($row=mysql_fetch_array($res))
                {
                        $sent_arr = explode(",",$row["SENT_TO"]);
			if (count($sent_arr) > 1)
				$sentstring = implode("','",$sent_arr);
			else
				$sentstring = $sent_arr[0];
			$j = 0;
			$sql_details = "SELECT ID , CITY, USERNAME , STATUS FROM incentive.PAYMENT_COLLECT where ID IN ('$sentstring')" ;
			$res_details=mysql_query_decide($sql_details,$db) or die("$sql_details".mysql_error_js());
			while($row_details=mysql_fetch_array($res_details))
			{
				if($row_details["USERNAME"]!='')
				{
					$id=$row_details["ID"];
					$sql="SELECT ENTRYBY FROM incentive.LOG WHERE REF_ID='$id' LIMIT 1";
					$res_entryby=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					$row_entryby=mysql_fetch_array($res_entryby);
					$sent_name[$i][$j]["ENTRYBY"]=$row_entryby["ENTRYBY"];

                        		$sent_name[$i][$j]["name"]= $row_details["USERNAME"];
					$city=label_select("CITY_INDIA",$row_details['CITY']);

					if ($row_details["STATUS"] == "C")
                                		$stage = "Payment collected";
                                	elseif($row_details["STATUS"] == "S")
                                		$stage = "Payment collected and Service start";
                                	elseif($row_details["STATUS"] == "D")
                                		$stage = "Payment declined";
                                	elseif($row_details["STATUS"] == "R")
                                 		$stage = "Resent for payment collection";
					elseif($row_details["STATUS"] == '')
						$stage = "No Response";
					elseif($row_details["STATUS"] == 'X' || $row_details["STATUS"] == 'X1')
                                                $stage = "Case Closed";
					$sent_name[$i][$j]["status"]=$stage;
					$sent_name[$i][$j]["CITY"]=$city[0];
					$sent_name[$i][$j]["REQID"]=$row_details["ID"];
				}
				$j++;
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
                unset($details);
		unset($sent_name);
                $smarty->display("skypakmisdetails.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}

/*else
{
        $smarty->display("jsconnectError.tpl");
}*/
?>
