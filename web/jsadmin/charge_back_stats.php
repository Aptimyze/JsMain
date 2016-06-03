<?php

include("../jsadmin/connect.inc");
include("../classes/Services.class.php");
$data = authenticated($cid);
if($data)
{
	if($submit)
	{
		if(($criteria=="uname" || $criteria=="email") && ($phrase!=''))
		{
			if($criteria=="uname")
				$sql="select PROFILEID,USERNAME from newjs.JPROFILE where USERNAME='$phrase' ";
			elseif($criteral=="email")
				$sql="select PROFILEID,USERNAME from newjs.JPROFILE where EMAIL='$phrase' ";

			$res=mysql_query_decide($sql) or die(mysql_error_js().$sql);
			$row=mysql_fetch_array($res);
			$pid=$row["PROFILEID"];
			$username = $row["USERNAME"];

			$sql=" SELECT * from billing.CHARGE_BACK_LOG where PROFILEID ='$pid' ORDER BY ID DESC LIMIT 1";
			$res=mysql_query_decide($sql) or die(mysql_error_js().$sql);
			$row=mysql_fetch_array($res);
		}
		else if($phrase!='')
		{
			if($criteria=="orderid")
				$sql=" SELECT * from billing.CHARGE_BACK_LOG where ORDERID ='$phrase'";
			elseif($criteria=="fund_transfer_no")
				$sql=" SELECT * from billing.CHARGE_BACK_LOG where FT_NO ='$phrase'";

			$res=mysql_query_decide($sql) or die(mysql_error_js().$sql);
			$row=mysql_fetch_array($res);

			if($row['PROFILEID']){
				$sql_username = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$res_username = mysql_query_decide($sql_username) or die(mysql_error_js().$sql_username);
				$row_username = mysql_fetch_array($res_username);
				$username = $row_username["USERNAME"];
			}
		}
		//if(mysql_num_rows($res) > 0)
		if($row['SERVICEID']!='')
		{
			/*
			$sql_service = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$row[SERVICEID]'";
			$res_service = mysql_query_decide($sql_service) or die($sql_service.mysql_error_js());
			$row_service = mysql_fetch_array($res_service);

			$row["SERVICE"] = $row_service["NAME"];

			if(strstr($row['ADDON'],"B"))
				$row["ADDON_SERVICE"] = "Bold Listing";
			if(strstr($row['ADDON'],"V"))
				$row["ADDON_SERVICE"] .= ", Voicemail";
			if(strstr($row['ADDON'],"H"))
				$row["ADDON_SERVICE"] .= ", Horoscope";
			if(strstr($row['ADDON'],"K"))
				$row["ADDON_SERVICE"] .= ", Kundali";
			if(strstr($row['ADDON'],"M"))
				$row["ADDON_SERVICE"] .= ", Matri-Profile";
			*/

                        $serviceid      =$row['SERVICEID'];
                        $addonId        =$row['ADDON'];
                        $serviceIdStr   ="$serviceid,$addonId";

                        $serviceObj     =new Services();
                        $serviceNameArr =$serviceObj->getServiceName($serviceIdStr);
                        $row["SERVICE"] =$serviceNameArr["$serviceid"]['NAME'];
                        $row["ADDON_SERVICE"] =$serviceNameArr["$addonId"]['NAME'];

			$row["CONTACTS_MADE"] = nl2br(str_replace(" ","&nbsp;",$row["CONTACTS_MADE"]));
			$row["CONTACTS_ACC"] = nl2br(str_replace(" ","&nbsp;",$row["CONTACTS_ACC"]));

			$smarty->assign('row',$row);
			$smarty->assign('username',$username);
			$smarty->assign("RESULT_FOUND",1);
		}
		else
			$smarty->assign("RESULT_FOUND",0);

		$smarty->assign("flag",1);
	}
	$smarty->assign('user',$user);
	$smarty->assign('cid',$cid);
	$smarty->display("charge_back_stats.htm");
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
	$smarty->assign('cid',$cid);
	$smarty->assign('user',$user);
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>

	
