<?php

include("../jsadmin/connect.inc");
include("../profile/pg/functions.php");

$data=authenticated($cid);
$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

if(isset($data))
{
	$loginname=getuser($cid);
	$walkin="BANK_TSFR";
	$center="HO";
	$deposit_branch=$center;
	$mode="CHEQUE";
	if($CMDSearch)
	{
		$smarty->assign("search","Y");
		
		$sql_s="SELECT a.ID,a.SERVICE,a.ADDON_SERVICEID,a.PROFILEID,a.USERNAME,b.AMOUNT,b.TYPE,b.CD_NUM,b.CD_DT,b.CD_CITY,b.BANK FROM incentive.PAYMENT_COLLECT as a,billing.CHEQUE_REQ_DETAILS as b where a.ID=b.REQUEST_ID and ";
		if($username!="")
		{
			$where="  a.USERNAME='$username' AND b.STATUS IN ('PENDING')";
		}
		elseif($orderid!="")
		{
			$where = " a.ID = '$orderid' ";//Allow to check the exact status
		}
		elseif($cd_num!="")
                {
                        $where = " b.CD_NUM = '$cd_num' ";//Allow to check the exact status
                }
		else
		{
			$sdate=$syear."-".$smonth."-".$sday." 00:00:00";
			$edate=$eyear."-".$emonth."-".$eday." 23:59:59";
			$where=" a.ENTRY_DT between '$sdate' AND '$edate' AND b.STATUS IN ('PENDING')";
		}

		$sql_s.=$where;
		$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());
		if($row_s=mysql_fetch_array($res_s))
		{
			$i=0;
			do
			{
			
				$serve_for=$row_s['SERVICE'];
				if($row_s['ADDON_SERVICEID'])
					$serve_for.=",".$row_s['ADDON_SERVICEID'];
				$orderarr[$i]["servefor"]=$serve_for;
				$duration=getServiceDetails($row_s['SERVICE']);
				$orderarr[$i]["username"]=$row_s['USERNAME'];
				$sql_ph="select PHONE_MOB,PHONE_RES,EMAIL from newjs.JPROFILE where USERNAME='$username'";
				$res_ph=mysql_query_decide($sql_ph) or die(mysql_error_js());
				$row_ph=mysql_fetch_array($res_ph);
				$orderarr[$i]["user_email"]=$row_ph['EMAIL'];
				$orderarr[$i]["phone_mob"]=$row_ph['PHONE_MOB'];
				$orderarr[$i]["phone_res"]=$row_ph['PHONE_RES'];
				$orderarr[$i]["orderid"]=$row_s['ID'];
				$orderarr[$i]["amount"]=$row_s['AMOUNT'];
				$orderarr[$i]["duration"]=$duration['DURATION'];
				$orderarr[$i]["cd_num"]=$row_s['CD_NUM'];
				$orderarr[$i]["cd_dt"]=$row_s['CD_DT'];
				$orderarr[$i]["cd_city"]=$row_s['CD_CITY'];
				$orderarr[$i]["bank"]=$row_s['BANK'];
				$i++;
			}while($row_s=mysql_fetch_array($res_s));
		}

		$smarty->assign("orderarr",$orderarr);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);

		$smarty->display("start_service.htm");
	}
	elseif($CMDGo)
	{
		foreach($_POST as $var=>$value)
		{

			$temp=explode("-",$var);
//			$tempid=$temp[0];
			if($value=='A')
			{
				$tempidA[]=$temp[0];
			}
			elseif($value=='R')
			{
				$tempidR[]=$temp[0];
			}
		}
		if(is_array($tempidA))
		{
			$accarr="'".implode("','",$tempidA)."'";
		}
		if(is_array($tempidR))
		{
			$rejarr="'".implode("','",$tempidR)."'";
		}

	if($accarr){
			 $sql="SELECT a.ID,a.SERVICE,a.ADDON_SERVICEID,a.DISCOUNT,a.PROFILEID,a.USERNAME,b.AMOUNT,b.TYPE,b.CD_NUM,b.CD_DT,b.CD_CITY,b.BANK,b.OBANK FROM incentive.PAYMENT_COLLECT as a,billing.CHEQUE_REQ_DETAILS as b where a.ID=b.REQUEST_ID and a.ID in ($accarr)";
			$res=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$service_selected=$row["SERVICE"];
				$profileid=$row["PROFILEID"];
				$addon_services_str=$row["ADDON_SERVICEID"];
				$username=$row["USERNAME"];
				$custname=$row["USERNAME"];
				$cdnum=$row["CD_NUM"];
				$cd_dt=$row["CD_DT"];
				$cd_city=$row["CD_CITY"];
				$bank=$row["OBANK"];
				$type=$row["TYPE"];
				$amount=$row["AMOUNT"];
				$tax_value = $TAX_RATE;

				$sql = "Select c.RIGHTS as RIGHTS, c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";
				$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				unset($subscription_ar);
				while($myrow = mysql_fetch_array($result))
				{
					$duration= $myrow["DURATION"];
					$subscription_ar[] = $myrow["RIGHTS"];
				}
				
				unset($addon_services_arr);
				if($addon_services_str)
				{
					$addon_services_arr=explode(",",$addon_services_str);
					$addon_services=implode("','",$addon_services_arr);
					$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.COMPONENTS c where a.PACKAGE = 'N' AND a.ADDON = 'Y' AND a.COMPID = c.COMPID AND a.SERVICEID in ('$addon_services')";
					$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
					while($myrow = mysql_fetch_array($result))
						$subscription_ar[] = $myrow["RIGHTS"];
				}
				if(is_array($subscription_ar))
					$subscription = implode(",",$subscription_ar);
				else
					$subscription = $subscription_ar;


				 $sql="INSERT into billing.PURCHASES (PROFILEID,SERVICEID,USERNAME, NAME, ADDRESS, GENDER,EMAIL, RPHONE, MPHONE,COMMENT, OVERSEAS,DISCOUNT,WALKIN,CENTER, ENTRYBY,ENTRY_DT, STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD)  SELECT PROFILEID,'$service_selected',USERNAME,'$custname',CONTACT,GENDER,EMAIL,PHONE_RES,PHONE_MOB,'$comment','$overseas','$discount','$walkin','$center','$loginname',now(),'DONE','$subscription','$addon_services_str','$tax_value',now(),'$deposit_branch','$ip' from newjs.JPROFILE where PROFILEID='$profileid' ";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
																	     
				$billid=mysql_insert_id_js();
																	     
				$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, STATUS, ENTRY_DT, ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) values ('$profileid','$billid','$mode','$type','$amount','$cdnum','$cd_dt','$cd_city','$bank','$obank','DONE',now(),'$loginname',now(),'$deposit_branch','$ip')";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				$receiptid= mysql_insert_id_js();
				

				$sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$subscription' where PROFILEID='$profileid'";
		                mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());

													     
				$sql="SELECT c.COMPID as COMPID, c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c  where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";
				$result_pkg=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		
				unset($comp_ar);
				while($myrow_pkg = mysql_fetch_array($result_pkg))
				{
					$dur=$myrow_pkg['DURATION'];
					$comp_ar[]=$myrow_pkg['COMPID'];
				}
				 if(is_array($comp_ar))
					$components = implode(",",$comp_ar);
				 else
					$components = $comp_ar;
		
				$activation_date=date('Y-m-d');
				$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$billid','$profileid','$service_selected','$components','Y','$activation_date','$loginname',ADDDATE('$activation_date', INTERVAL $dur MONTH))";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				
				if(strstr($service_selected,'P'))
					$subject = "Congrats!You are now an e-Rishta Member!";
				if(strstr($service_selected,'D'))
					$subject = "Congrats! You are now an e-Classified Member!";
				if(strstr($service_selected,'C'))
					$subject = "Congrats! You are now an e-Value Pack Member!";
				if(strstr($service_selected,'M'))
					$subject = "Thanks for purchasing Matri-Profile!";
																	     
				$msg = membership_mail($service_selected,$addon_services_str,$username,$type,$amount,$duration);
																	     
				$bill = printbill($receiptid,$billid);
																	     
				$sql="SELECT EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				$myrow=mysql_fetch_array($result);
				$email= $myrow['EMAIL'];
																     
				send_email("$email",$msg,$subject,'webmaster@jeevansathi.com','',"payments@jeevansathi.com",$bill);                                                                                                                             
				if(strstr($subscription,"H") || strstr($subscription,"K"))
				{
					astro_mail($username,$subscription,$email);
				}


				

		}

		if(count($tempidA) == '1')
			$sql_u="UPDATE billing.CHEQUE_REQ_DETAILS SET  STATUS='DONE' WHERE REQUEST_ID = $accarr";
		else
			$sql_u="UPDATE billing.CHEQUE_REQ_DETAILS SET STATUS='DONE'  WHERE REQUEST_ID IN ($accarr)";
		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_error_js());
	}

	if($rejarr)
	{
		
		if(count($tempidR) == '1')
			$sql_u="UPDATE billing.CHEQUE_REQ_DETAILS SET  STATUS='CANCEL' WHERE REQUEST_ID = $rejarr";
                else
                        $sql_u="UPDATE billing.CHEQUE_REQ_DETAILS SET STATUS='CANCEL'  WHERE REQUEST_ID IN ($rejarr)";

		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_error_js());
	}


		$msg="Records Have been succesfully updated<br>";

		$msg.="<a href=\"start_service.php?user=$user&cid=$cid\">Continue</a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("start_service.htm");
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

		$smarty->display("start_service.htm");
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
