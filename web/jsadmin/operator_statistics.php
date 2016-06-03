<?php
/************************************************************************************************************
File		: operator_statistics.php	
Description 	: Number and details of each profiles assigned to the selected Operator
Developed By	: Vibhor Garg
Date		: 16-01-2008
*************************************************************************************************************/
include_once("connect.inc");

if(authenticated($cid))
{
	if($transfer)
	{
		if($doTransfer)
		{
			$sql="SELECT PROFILEID FROM jsadmin.OFFLINE_ASSIGNED WHERE OPERATOR='$operator'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js($db));
			if(mysql_num_rows($res))
			{
				while($row=mysql_fetch_assoc($res))
				{
					$logvalues.="('".$row["PROFILEID"]."','".$operatorSelected."',NOW()),";
					$tablevalues.="'".$row["PROFILEID"]."',";
				}
				$logvalues=rtrim($logvalues,",");
				$tablevalues=rtrim($tablevalues,",");
				$sql="INSERT INTO jsadmin.OFFLINE_ASSIGNLOG(PROFILEID,OPERATOR,ASSIGN_DATE) VALUES $logvalues";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js($db));
				if($res)
				{
					$sql="UPDATE jsadmin.OFFLINE_ASSIGNED SET OPERATOR='$operatorSelected' WHERE PROFILEID IN ($tablevalues)";
					mysql_query_decide($sql) or die("$sql".mysql_error_js($db));
					$smarty->assign("transferDone","1");
				}
			}
		}
		elseif($operator)
		{
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$operator'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_assoc($res);
			$loc=$row["CENTER"];
			$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE CENTER='$loc' AND PRIVILAGE LIKE '%OB%'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			if(mysql_num_rows($res))
			{
				while($row=mysql_fetch_assoc($res))
					$operatorArray[]=$row["USERNAME"];
			}
			else
				$smarty->assign("noOperators",1);
			$smarty->assign("transfer",$transfer);
			$smarty->assign("operator",$operator);
			$smarty->assign("operatorArray",$operatorArray);

		}
	}
	elseif($View)
	{
		$operator_name = $_POST["operator"];
		
		if($operator_name=="All")
			$sql="Select COUNT(*) AS CNT from OFFLINE_ASSIGNED";
		else
			$sql="Select COUNT(*) AS CNT from OFFLINE_ASSIGNED where OPERATOR = '".$operator_name."'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_assoc($result);
		$count = $row["CNT"];		
			
		if($operator_name=="All")
			$sql="Select PROFILEID from OFFLINE_ASSIGNED";
		else
			$sql="Select PROFILEID from OFFLINE_ASSIGNED where OPERATOR = '".$operator_name."'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$profileArray[] = $row["PROFILEID"];		
		}		
		$active_count = 0;
		for($i=0;$i<$count;$i++)
		{
			$active_count++;
			unset($dayremain);
			unset($count_pool);
			unset($count_sl);
			unset($dateArray);
			unset($accAllowedArray);
			unset($accRemainArray);
			unset($uname);
			unset($pass);
			unset($phone_res);
			unset($phone_mob);
			unset($std);
			unset($is_deleted);
			unset($contact_no);
			$sql="SELECT BILLID,ACTIVE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileArray[$i]' ORDER BY BILLID DESC LIMIT 1";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$type="unbilled";
			$dayremain=0;
			if(mysql_num_rows($res))
			{	
				$row=mysql_fetch_assoc($res);
				if($row["ACTIVE"]=="Y")
					$type="billed";
				else
					$type="unbilled";
				$billid=$row["BILLID"];
						
				if($type=="billed")
				{
					$sql="SELECT DATEDIFF(EXPIRY_DT,NOW()) AS DAYREMAIN FROM billing.SERVICE_STATUS WHERE BILLID='$billid' AND ACTIVATED='Y'";
					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					$row=mysql_fetch_assoc($res);
					$dayremain=$row["DAYREMAIN"];
				}
				else
					$dayremain=0;
		
				$sql="Select COUNT(*) AS CNTP from OFFLINE_MATCHES where PROFILEID = '".$profileArray[$i]."' AND CATEGORY !='' AND (STATUS='NACC'||STATUS='N'||STATUS='NNOW')";
				$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				$row=mysql_fetch_assoc($result);
				$count_pool = $row["CNTP"];
				
				$sql="Select COUNT(*) AS CNTSL from OFFLINE_MATCHES where PROFILEID = '".$profileArray[$i]."' AND STATUS = 'SL' ";
				$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				$row=mysql_fetch_assoc($result);
				$count_sl = $row["CNTSL"];		
				
				$sql="Select ENTRY_DATE,ACC_UPGRADED,ACC_ALLOWED,ACC_MADE from OFFLINE_BILLING where PROFILEID = '".$profileArray[$i]."' ORDER BY BILLID DESC";
				$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				$row=mysql_fetch_assoc($result);
				$dateArray = $row["ENTRY_DATE"];
				$accAllowedArray = $row["ACC_ALLOWED"] + $row["ACC_UPGRADED"];
				$accRemainArray = $row["ACC_ALLOWED"]+ $row["ACC_UPGRADED"] - $row ["ACC_MADE"]; 		
			}
			$sql="Select USERNAME,PASSWORD,PHONE_RES,PHONE_MOB,STD,ACTIVATED from newjs.JPROFILE where PROFILEID = '".$profileArray[$i]."'";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_assoc($result);
			$uname = $row["USERNAME"];
			$pass= $row["PASSWORD"];
			$phone_res = $row["PHONE_RES"];
			$phone_mob = $row["PHONE_MOB"];
			$std = $row["STD"];
			$is_deleted=$row["ACTIVATED"];
			if($std != "")
			{
				if($phone_mob != "")
				if($phone_res != "")
					$contact_no = $phone_mob." , ".$std."-".$phone_res;
				else
					$contact_no = $phone_mob;
				else
					if($phone_res != "")
					      $contact_no = $std." - ".$phone_res;
					else
					      $contact_no = "NA";

			 }
			 else
			 {
				if($phone_mob != "")
					if($phone_res != "")
					       $contact_no = $phone_mob." , ".$phone_res;
					else
					       $contact_no = $phone_mob;
					else
						 if($phone_res != "")
							$contact_no = $phone_res;
					 else
							$contact_no = "NA";

			 }
			$tableArray[$profileArray[$i]] = array($type,$uname,$pass,$contact_no,$count_pool,$count_sl,$accAllowedArray,$accRemainArray,$dateArray,$dayremain);
			
		}
		
		$smarty->assign('tableArray',$tableArray);
		$smarty->assign("searchFlag","1");
		$smarty->assign("operator_name",$operator_name);	
		$smarty->assign("assigned_profile",$active_count);		
		$smarty->assign("profileArray",$profileArray);
	}
	$sql="Select USERNAME from PSWRDS where PRIVILAGE LIKE '%OB%'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($result))
	{
		$userArray[]=$row["USERNAME"];	
	}	
	$smarty->assign("CHECKSUM",$cid);	
	$smarty->assign("userArray",$userArray);
	$smarty->display("operator_statistics.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
