<?php
include("connect.inc");
include ("../crm/display_result.inc");
if(authenticated($cid))
{
	if($CMDDispatch)
	{
		$str=implode("','",$v_profileid);
		$sql="UPDATE billing.VOUCHER_OPTIN SET DISPATCHED='Y' WHERE PROFILEID IN ('$str')";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN('$str')";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		$user.=$row["USERNAME"].",";
		$user=substr($user,0,strlen($user)-1);
		echo "<html><body>Vouchers are successfully marked as dispatched for user <font color=blue>$user</font>.</body></html>";
		exit;
	}
	else
	{
	$clientid[]="TAN93";
	$client_name[]="Tanishq";
	$sql="SELECT * FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND TYPE='P'";
	$res=mysql_query_decide($sql);
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$clientid[$i]=$row['CLIENTID'];
		$client_name[$i]=$row['CLIENT_NAME'];
		$i++;
	}
	$smarty->assign("client_name",$client_name);
	$smarty->assign("clientid",$clientid);
	//print_r($v_profileid);
	for($i=0;$i<count($v_profileid);$i++)
	{
		$sql="SELECT PROFILEID,OPTIONS_AVAILABLE,NAME,CONTACT,CITY_RES,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_OPTIN WHERE DISPATCHED!='Y' AND PROFILEID='$v_profileid[$i]' ORDER BY ID DESC";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
                        $options=explode(',',$row['OPTIONS_AVAILABLE']);
			$j=0;$m=0;
			unset($pvoucher);
			unset($evoucher);
                        for($k=0;$k<count($clientid);$k++)
                        {
//print_r($clientid);
                                if(in_array($clientid[$k],$options))
				{
					$sql_type="SELECT CLIENT_NAME,HEADLINE,TYPE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid[$k]'";
					//$sql_type="SELECT TYPE,VOUCHER_NO FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$clientid[$k]' AND PROFILEID='$profileid'";
					$res_type=mysql_query_decide($sql_type) or die(mysql_error_js());
					$row_type=mysql_fetch_array($res_type);
					//$voucher_no=$row_type['VOUCHER_NO'];
					$pvoucher[$m]=array("NAME"=>$row_type["CLIENT_NAME"],
							    "HEADING"=>$row_type["HEADLINE"]);
					$m++;						
				}
                        }
			//print_r($pvoucher);
			$sql_contact="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res_contact=mysql_query_decide($sql_contact);
			$row_contact=mysql_fetch_array($res_contact);
                        $sql_member="SELECT NAME FROM billing.SERVICES WHERE SERVICEID=(SELECT SERVICEID FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND BILLID=(SELECT MAX(BILLID) FROM billing.PURCHASES WHERE PROFILEID='$profileid'))";
                        $res_member=mysql_query_decide($sql_member) or die(mysql_error_js());
                        $row_member=mysql_fetch_array($res_member);
			$smarty->assign("membership",$row_member['NAME']);
			$smarty->assign("evoucher",$evoucher);
			$smarty->assign("pvoucher",$pvoucher);
			$smarty->assign("username",$row_contact['USERNAME']);
			$smarty->assign("name",$row['NAME']);
			$smarty->assign("contact",$row['CONTACT']);
			$sql_city = "select SQL_CACHE LABEL from newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
			$res_city = mysql_query_decide($sql_city) or logError("error",$sql) ;
			$row_city= mysql_fetch_array($res_city);
			$smarty->assign("city_res",$row_city['LABEL']);
			$smarty->assign("phone_res",$row['PHONE_RES']);
			$smarty->assign("phone_mob",$row["PHONE_MOB"]);
			$smarty->assign("email",$row_contact['EMAIL']);
			$smarty->assign("cid",$cid);
			$a.=$smarty->fetch("voucher_template.htm");
		}
        }
	echo $a;
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>

