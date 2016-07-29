<?php
include("../jsadmin/connect.inc");

$data=authenticated($cid);

if(isset($data))
{
	if((trim($syear)=="" || trim($smonth)=="" || trim($sday)=="" || trim($eyear)=="" || trim($emonth)=="" || trim($eday)==""))
	{
		$error=1;
	}
	else
	{
		$error=0;
		$sql="SELECT USERNAME,EMAIL,BILLID,STATUS,WALKIN,SERVICEID,ENTRY_DT FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$syear-$smonth-$sday' AND '$eyear-$emonth-$eday' ORDER BY BILLID";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$i=0;
			do
			{
				$arr[$i]["service_name"]=getservicename($row['SERVICEID']);
				$arr[$i]["username"]=$row['USERNAME'];
				$arr[$i]["billid"]=$row['BILLID'];
				$arr[$i]["email"]=$row['EMAIL'];
				$arr[$i]["status"]=$row['STATUS'];
				$arr[$i]["walkin"]=$row['WALKIN'];
				$arr[$i]["entrydt"]=$row['ENTRY_DT'];
				$i++;
			}while($row=mysql_fetch_array($res));
		}
	}
	if($error==1)
	{
		header('Location:http://www.jeevansathi.com/billing/billing_start.php?cid='.$cid);
                die("here");
		$smarty->assign("CID",$cid);
		$smarty->assign("USER",$user);
		$smarty->display("billingview.php");
	}
	else
	{
		$smarty->assign("arr",$arr);
		$smarty->assign("CID",$cid);
		$smarty->assign("USER",$user);
		$smarty->display("search_bill_records.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->assign("username","$username");
        $smarty->display("jsconnectError.tpl");
}

function getservicename($sid)
{
	$sql="SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$sid'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$row=mysql_fetch_array($res);
	$sname=$row['NAME'];

	return $sname;
}
?>
