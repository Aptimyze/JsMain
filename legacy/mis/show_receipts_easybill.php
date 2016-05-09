<?php
/***************************************************************************************************************************
FILE NAME	: show_receipts_easybill.php
DESCRIPTION	: This script is used to display all the records for which billing has not been done (Easy bill receipts.)
CREATED BY	: Sriram Viswanathan
***************************************************************************************************************************/
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
$db2 = connect_master();

$data = authenticated($cid);
$db = connect_misdb();
                                                                                                                             
if($data)
{
	$serviceObj = new Services;

	$sql = "(SELECT eb.REF_ID AS REF_ID, eb.PROFILEID, eb.USERNAME,eb.SERVICEID, eb.ADDON_SERVICEID, eb.AMOUNT AS ACTUAL_AMOUNT, ebr.REF_ID AS MOB_NUM,ebr.RECT_ID, ebr.TRANSACTION_DT, ebr.AMOUNT AS RECT_AMOUNT, ebr.CD_NUM,ebr.CD_CITY,ebr.CD_DT, ebr.BANK_NAME FROM billing.EASY_BILL_RECEIPTS ebr, billing.EASY_BILL eb WHERE SUBSTRING(ebr.REF_ID,1,5) = eb.REF_ID AND eb.BILLING='N') UNION (SELECT eb.REF_ID AS REF_ID, eb.PROFILEID, eb.USERNAME,eb.SERVICEID, eb.ADDON_SERVICEID, eb.AMOUNT AS ACTUAL_AMOUNT, ebr.REF_ID AS MOB_NUM,ebr.RECT_ID, ebr.TRANSACTION_DT, ebr.AMOUNT AS RECT_AMOUNT, ebr.CD_NUM,ebr.CD_CITY,ebr.CD_DT, ebr.BANK_NAME FROM billing.EASY_BILL_RECEIPTS ebr, billing.EASY_BILL eb WHERE SUBSTRING(ebr.REF_ID,1,9) = eb.REF_ID AND eb.BILLING='N')";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	$i=0;
	if(mysql_num_rows($res))
	{
		while($row = mysql_fetch_array($res))
		{
			$details[$i]['REF_ID'] = $row['REF_ID'];
			$details[$i]['USERNAME'] = $row['USERNAME'];

		/*	$sql_service = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID = '$row[SERVICEID]'";
			$res_service = mysql_query_decide($sql_service) or die($sql_service.mysql_error_js());
			$row_service = mysql_fetch_array($res_service);*/
			$services=$serviceObj->getServiceName($row["SERVICEID"]);
			$service_names='';
			foreach($services as $k=>$v)
			{
				foreach($v as $k1=>$v1)
				{
					if($service_names=='')
						$service_names.=$v1;
					else
						$service_names.=",".$v1;
				}
			}

			$details[$i]['MAIN_SERVICE'] = $service_names;

			if(strstr($row['ADDON_SERVICEID'],'B'))
				$details[$i]['ADDON_SERVICE'] .= "Profile Highlighting";
			if(strstr($row['ADDON_SERVICEID'],'M'))
				$details[$i]['ADDON_SERVICE'] .= ", Matri Profile";
			if(strstr($row['ADDON_SERVICEID'],'K'))
				$details[$i]['ADDON_SERVICE'] .= ", Kundali";
			if(strstr($row['ADDON_SERVICEID'],'H'))
				$details[$i]['ADDON_SERVICE'] .= ", Horoscope";
			if(strstr($row['ADDON_SERVICEID'],'V'))
				$details[$i]['ADDON_SERVICE'] .= ", Voicemail";

			$details[$i]['ACTUAL_AMOUNT'] = $row['ACTUAL_AMOUNT'];
			$details[$i]['RECT_ID'] = $row['RECT_ID'];
			if(substr($row['REF_ID'],0,4)=='0004')
				$details[$i]['MOB_NUM'] = substr($row['MOB_NUM'],9);
			else
				$details[$i]['MOB_NUM'] = substr($row['MOB_NUM'],5);
			$details[$i]['TRANSACTION_DT'] = $row['TRANSACTION_DT'];
			$details[$i]['RECT_AMOUNT'] = $row['RECT_AMOUNT'];

			$sql_city = "SELECT CITY_RES FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
			$res_city = mysql_query_decide($sql_city) or die("$sql_city".mysql_error_js());
			$row_city = mysql_fetch_array($res_city);
			$CITY = label_select("CITY_NEW",$row_city['CITY_RES']);
			$details[$i]['CITY'] = $CITY[0];
			if($row["CD_NUM"])
			{
				$details[$i]['CD_NUM'] = $row["CD_NUM"];
				$details[$i]['CD_DT'] = $row["CD_DT"];
				$details[$i]['CD_CITY'] = $row["CD_CITY"];
				$details[$i]['BANK_NAME'] = $row["BANK_NAME"];
			}
			else
			{
				$details[$i]['CD_NUM'] = "-";
				$details[$i]['CD_DT'] = "-";
				$details[$i]['CD_CITY'] = "-";
				$details[$i]['BANK_NAME'] = "-";
			}
			$i++;
		}
	}
	else
	{
		$smarty->assign("NO_RESULT",1);
	}

	$smarty->assign("details",$details);
	$smarty->assign("cid",$cid);
	$smarty->display("show_receipts_easybill.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
