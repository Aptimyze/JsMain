<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("bounced_mail.php");

$data=authenticated($cid);

if($data)
{
	if($CMDGo)
	{
		$i = 0;
		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";

		$connection = explode("i",$cid);
		$center = getcenter($connection[1],"");
		$center = strtoupper($center);

		$user = getname($cid);
		$priv = getprivilage($cid);

		$sql = "SELECT VALUE FROM incentive.BRANCHES  WHERE NAME = '$center'";
		$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($myrow=mysql_fetch_array($res))
		{
			// query to find the nearest js branch for a particular city
			$sql = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$myrow[VALUE]'";
			$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$citylist[$i]= $row['VALUE'];
				$i++;
			}
		}
		$branchlist = implode("','",$citylist);

		// in case user logged in is Billing admin the entire list is to be shown to him/her
		if (strstr($priv,"BA"))
		{
			if ($showall == 1) //  to show the entire list of those usernames whose cheques have bounced
				$sql = "SELECT BILLID , PROFILEID , ACTION  , REMINDER_DT FROM billing.BOUNCED_CHEQUE_HISTORY WHERE STATUS = 'BOUNCE' AND BOUNCE_DT BETWEEN '$st_date' AND '$end_date'";
			else
				$sql = "SELECT BILLID , PROFILEID , ACTION , REMINDER_DT FROM billing.BOUNCED_CHEQUE_HISTORY WHERE STATUS = 'BOUNCE' AND REMINDER_DT <= CURDATE()  AND DISPLAY = 'Y' AND BOUNCE_DT BETWEEN '$st_date' AND '$end_date'";
		}
		else
		{
		  // to show the entire list of those usernames whose cheques have bounced for logged in  user's branch
			if ($showall == 1)
                                $sql = "SELECT b.BILLID , b.PROFILEID , b.ACTION  , b.REMINDER_DT FROM billing.BOUNCED_CHEQUE_HISTORY b LEFT JOIN newjs.JPROFILE j ON j.PROFILEID = b.PROFILEID WHERE  j.CITY_RES IN ('$branchlist') AND  b.STATUS = 'BOUNCE' AND b.BOUNCE_DT BETWEEN '$st_date' AND '$end_date'";
                        else
                                $sql = "SELECT b.BILLID , b.PROFILEID , b.ACTION , b.REMINDER_DT FROM billing.BOUNCED_CHEQUE_HISTORY b LEFT JOIN newjs.JPROFILE j ON j.PROFILEID = b.PROFILEID WHERE  j.CITY_RES IN ('$branchlist') AND b.STATUS = 'BOUNCE' AND b.REMINDER_DT <= CURDATE()  AND b.DISPLAY = 'Y' AND b.BOUNCE_DT BETWEEN '$st_date' AND '$end_date'";
		}
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($myrow=mysql_fetch_array($res))
		{
			$i=0;
			do
			{
				$pid = $myrow['PROFILEID'];
				$profileid[$i] = $myrow['PROFILEID'];
				$action[$pid]["action"] = $myrow['ACTION'];
				$action[$pid]["reminder_dt"] = $myrow['REMINDER_DT'];
				$i++;
			}while($myrow=mysql_fetch_array($res));
		}
		$j =0;
		for ($i = 0;$i < count($profileid);$i++)
		{
			$sql1 = "SELECT b.STATUS , p.USERNAME , b.BOUNCE_DT, b.REASON, p.WALKIN, p.ENTRY_DT FROM billing.PAYMENT_DETAIL b, billing.PURCHASES p WHERE p.PROFILEID=b.PROFILEID AND p.PROFILEID = '$profileid[$i]' ORDER BY b.ENTRY_DT DESC LIMIT 1";
			$result = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
			$row = mysql_fetch_array($result);
			if ($row['STATUS'] == 'BOUNCE')
			{
				$arr[$j]["username"]=$row['USERNAME'];
				$arr[$j]["center"]=$row['CENTER'];
				$arr[$j]["bounce_dt"]=$row['BOUNCE_DT'];
				$arr[$j]["action"]=$action[$profileid[$i]]["action"];
				$arr[$j]["reminder_dt"] = $action[$profileid[$i]]["reminder_dt"];
				$arr[$j]["entry_dt"]=substr($row['ENTRY_DT'],0,10);
				$arr[$j]["bounce_reason"]=$row['REASON'];
				$arr[$j]["sale_by"]=$row['WALKIN'];
				$j++;
			}
		}
		$smarty->assign("flag","1");
		$smarty->assign("user",$user);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("arr",$arr);
		$smarty->assign("cid",$cid);
		$smarty->display("bounced_cheque_mis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2004;
                }
		$sql="SELECT NAME FROM billing.BRANCHES";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$brancharr[]=strtoupper($row['NAME']);
		}

		$smarty->assign("brancharr",$brancharr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
                $smarty->display("bounced_cheque_mis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
