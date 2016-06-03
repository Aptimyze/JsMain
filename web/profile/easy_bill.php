<?
/***************************************************************************************************************************
FILE NAME	: easy_bill.php
DESCRIPTION	: This script generates a refernce id and displays it to the user along with the service details.
CREATED BY	: Sriram Viswanathan
DATE		: 22nd January 2007.
***************************************************************************************************************************/
include("connect.inc");
include("pg/functions.php");
$db=connect_db();
$data = authenticated($checksum);

if($data)
{
	$profileid = $data['PROFILEID'];
	$username = $data['USERNAME'];

	/*Finding the main service name and addon service(s) name.*/
	$sql_ser = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID = '$service'";
	$res_ser = mysql_query_decide($sql_ser) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row_ser = mysql_fetch_array($res_ser);

	if($boldlisting)
	{
		$addonservice = "Boldlisting,";
		$addon_serviceid_arr[] = $boldlisting;
	}
	if($matri_profile)
	{
		$addonservice .= " Matri Profile,";
		$addon_serviceid_arr[] = $matri_profile;
	}
	if($horoscope)
	{
		$addonservice .= " Horoscope,";
		$addon_serviceid_arr[] = $horoscope;
	}
	if($kundli)
	{
		$addonservice .= " Kundali,";
		$addon_serviceid_arr[] = $kundli;
	}
	if($voicemail)
	{
		$addonservice .= " Voicemail";
		$addon_serviceid_arr[] = $voicemail;
	}
	/*End of - Finding the main service name and addon service(s) name.*/

	$addonservice = rtrim($addonservice,",");//removing extra "," (if any) before displaying
	$addon_serviceid = @implode(",",$addon_serviceid_arr);//changing addon service(s) array to string.

	if($type=="RS")
		$type="Rs";

	$amount = $type.". ".$total;
	/*Code to generate and save reference id.*/
	$sql_ins = "INSERT INTO billing.EASY_BILL (PROFILEID,USERNAME,SERVICEID,ADDON_SERVICEID,TYPE,AMOUNT) VALUES('$profileid','$username','$service','$addon_serviceid','$type','$total')";
	mysql_query_decide($sql_ins) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins,"ShowErrTemplate");

	$id = mysql_insert_id_js();
	$ref_id = generate_ref_id($id);
	 $sql_upd = "UPDATE billing.EASY_BILL SET REF_ID='$ref_id', ENTRY_DT=now() WHERE ID='$id'";
	mysql_query_decide($sql_upd) or die($sql." ".mysql_error_js());// logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
	/*End of - Code to generate and save reference id.*/
	populate_locality_city();
	$smarty->assign("checksum",$checksum);
	$smarty->assign("ref_id",$ref_id);
	$smarty->assign("mainservice",$row_ser['NAME']);
	$smarty->assign("addonservice",$addonservice);
	$smarty->assign("amount",$amount);
	$smarty->assign("head_tab","memberships");
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));
	$smarty->display("easy_bill.htm");
}
else
{
	Timedout();
}

/*Function to generate 5 digit reference id*/
/*function generate_ref_id($id)
{
	$min = 10000;
	$max = 99999;

	if($id < $max)
	{
		return $id;
	}
	else
	{
		//$new_ref_id = ($id - $max) + $min;
		$new_ref_id = $id - 89999;
		if($new_ref_id > $max)
			return generate_ref_id($new_ref_id);
		else
			return $new_ref_id;
	}
}*/
?>
