<?php

include("../jsadmin/connect.inc");
include("../profile/pg/functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$serObj = new Services;
$membershipObj = new Membership;

$data=authenticated($cid);

if(isset($data))
{
	if($CMDSearch)
	{
		$smarty->assign("search","Y");
		$sql_s="SELECT ID,USERNAME,ORDERID,PAYMODE,SERVEFOR,AMOUNT,BILL_EMAIL,STATUS, GATEWAY,SERVICEMAIN,ENTRY_DT FROM billing.ORDERS";
		if($criteria=='U')
		//if($username!="")
		{
			$where=" WHERE USERNAME='$username' ";
		}
		if($criteria=='O')
		//elseif($orderid!="")
		{
			$sql="SELECT PROFILEID from billing.ORDERS ";
			list($part1,$part2) = explode("-",$orderid);
			$sql.= " WHERE ID = '$part2' ";//Allow to check the exact status
			if($part1 != "*")
			$sql .= " and ORDERID='$part1'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
                	$row=mysql_fetch_array($res);
			$pid=$row["PROFILEID"];
			$where=" WHERE PROFILEID='$pid' ";
			$smarty->assign("ord_id","$orderid");
		}
		elseif($criteria=='D')
		{
			$sdate=$syear."-".$smonth."-".$sday;
			$edate=$eyear."-".$emonth."-".$eday;
			$where=" WHERE ENTRY_DT >='$sdate' AND ENTRY_DT<='$edate' ";
			if($ttype!='ALL')
				$where.=" AND STATUS ='$ttype' ";
			$where.=" ORDER BY $group_by ";
		}

		$sql_s.=$where;
		$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());
		if($row_s=mysql_fetch_array($res_s))
		{
			$i=0;
			do
			{
				$sql_jp="SELECT SUBSCRIPTION from newjs.JPROFILE where USERNAME='".addslashes($row_s[USERNAME])."'";
				$result_jp=mysql_query_decide($sql_jp) or die(mysql_error_js());
				$myrow_jp=mysql_fetch_array($result_jp);
				
				$duration=getServiceDetails($row_s['SERVICEMAIN']);
				//substr($abc,8)?substr($abc,8):substr($abc,6)
				$orderarr[$i]["username"]=$row_s['USERNAME'];
				$orderarr[$i]["orderid"]=$row_s['ORDERID']."-".$row_s['ID'];
				$orderarr[$i]["paymode"]=$row_s['PAYMODE'];
				$orderarr[$i]["servefor"]=$row_s['SERVEFOR'];
				$orderarr[$i]["amount"]=$row_s['AMOUNT'];
				$orderarr[$i]["bill_email"]=$row_s['BILL_EMAIL'];
				$orderarr[$i]["status"]=$row_s['STATUS'];

				$orderarr[$i]["servefor"]=$row_s['SERVEFOR'];
				$orderarr[$i]["gateway"]=$row_s['GATEWAY'];
				$orderarr[$i]["duration"]=$duration['DURATION'];
				$orderarr[$i]["entry_dt"]=$row_s['ENTRY_DT'];
				
				$orderarr[$i]["subscription"]=$myrow_jp['SUBSCRIPTION'];
					
				if($orderarr[$i]["status"]=='Y')
				{
					$orderarr[$i]["status"]="Successful";
				}
				elseif($orderarr[$i]["status"]=='N')
				{
					$smarty->assign("FAIL","Y");
					$orderarr[$i]["status"]="Failed";
				}
				elseif($orderarr[$i]["status"]=='B')
				{
					$orderarr[$i]["status"]="OnHold";
				}
				elseif($orderarr[$i]["status"]=='A')
				{
					$orderarr[$i]["status"]="Accepted";
				}
				elseif($orderarr[$i]["status"]=='R')
				{
					$orderarr[$i]["status"]="Rejected";
				}

				$i++;
			}while($row_s=mysql_fetch_array($res_s));
		}

		$smarty->assign("orderarr",$orderarr);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);

		$smarty->display("order_records.htm");
	}
	elseif($CMDGo)
	{
		foreach($_POST as $var=>$value)
		{

			$temp=explode("-",$var);
//			$tempid=$temp[0];
		/*	if($value=='A')
			{
				$tempidA[]=$temp[0];
			}
			else*/
			if($value=='R')
			{
				$tempidR[]=$temp[1];
			}
		}
/*		if(is_array($tempidA))
		{
			$accarr="'".implode("','",$tempidA)."'";
		}*/
		if(is_array($tempidR))
		{
			$rejarr="'".implode("','",$tempidR)."'";
		}

/*	if($accarr){
		$sql="SELECT ID, ORDERID, STATUS FROM billing.ORDERS WHERE ";

		if(count($tempidA) == '1')
			$sql.="1";//for single record update without checking status
		else
			$sql.="STATUS='B'";

		$sql.=" AND ORDERID in ($accarr)";
		$res=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			//condition required in case of single record searched without status
			if(($row["STATUS"] != 'A') && ($row["STATUS"] != 'Y'))
			{ 
				//start_service($row['ORDERID'].'-'.$row['ID']);
				$membershipObj->startServiceOrder($row['ORDERID'].'-'.$row['ID']);
				//**code added to track the orders for which the status field is blank and service is started
				$sql_tmp = "INSERT INTO billing.ORDERS_STARTED(ORDERID, ENTRY_DT) VALUES('$row[ORDERID]',now())";
				$res_tmp = mysql_query_decide($sql_tmp) or die(mysql_error_js().$sql_tmp);
				//**end code
			} 
		}

		if(count($tempidA) == '1')
			$sql_u="UPDATE billing.ORDERS SET STATUS='A' WHERE 1 AND ORDERID = $accarr";
		else
			$sql_u="UPDATE billing.ORDERS SET STATUS='A' WHERE STATUS IN ('Y','B') AND ORDERID IN ($accarr)";
		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_error_js());
	}
*/
	if($rejarr){
		if($service == 'Y'){
		$sql="SELECT PROFILEID FROM billing.ORDERS WHERE ";

		if(count($tempidR) == '1')
			$sql.="1";//for single record update without checking status
		else
			$sql.="STATUS='Y'";

		$sql.=" AND ID in ($rejarr)";
		$res=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			/*$sql="UPDATE newjs.JPROFILE SET SUBSCRIPTION='' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());*/
			$jprofileObj    =JProfileUpdateLib::getInstance();
                        $updateStr      ="SUBSCRIPTION=''";
                        $paramArr       =$jprofileObj->convertUpdateStrToArray($updateStr);
                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');
			
			$sql_c="UPDATE billing.PURCHASES SET STATUS='STOPPED' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql_c) or die(mysql_error_js());
		}
	}
		if(count($tempidR) == '1')
			$sql_u="UPDATE billing.ORDERS SET STATUS='R' WHERE 1 AND ID = $rejarr";
		else
			$sql_u="UPDATE billing.ORDERS SET STATUS='R' WHERE STATUS IN ('N','Y','B') AND ID IN ($rejarr)";

		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_error_js());
	}


		$msg="Records Have been succesfully updated<br>";

		if(count($tempidR) == '1')
		$msg.="<br>Please enter the refund details.<br><br>";
		$msg.="<a href=\"order_records.php?user=$user&cid=$cid\">Continue</a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("order_records.htm");
	}
	else
	{
		$today=date("Y-m-d");
		list($yy,$mm,$dd)=explode("-",$today);

		$smarty->assign("sday",$dd);
		$smarty->assign("smonth",$mm);
		$smarty->assign("syear",$yy);
		$smarty->assign("eday",$dd);
		$smarty->assign("emonth",$mm);
		$smarty->assign("eyear",$yy);

		$smarty->assign("init","Y");
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);

		$smarty->display("order_records.htm");
	}

}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->assign("username","$username");
        $smarty->display("jsconnectError.tpl");
}

?>
