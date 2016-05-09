<?
/***************************************************************************************************************************
FILE NAME       : easy_bill.php
DESCRIPTION     : This script finds the easy bill payment centers depending on the selected City and Locality.
CREATED BY      : Sriram Viswanathan
DATE            : 22nd January 2007.
***************************************************************************************************************************/
include('connect.inc');
include('pg/functions.php');
$db = connect_db();
$data = authenticated($checksum);
if($data || $CRM)
{
	populate_locality_city();
	$city1 = explode("|X|",$city);
	$sql = "SELECT * FROM billing.EASY_BILL_LOCATIONS WHERE CITY_LABEL='$city1[0]' AND LOCALITY='$locality'";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$i=0;
	while($row = mysql_fetch_array($res))
	{
		$details[$i]['SHOP_NAME'] = $row['SHOP_NAME'];
		$details[$i]['ADDRESS'] = $row['ADDRESS'];
		$details[$i]['CITY_LABEL'] = $row['CITY_LABEL'];
		$details[$i]['LOCALITY'] = $row['LOCALITY'];
		$i++;
	}

	//smarty assigned for displaying the selected city as pre-selected in dropdown
	$smarty->assign("city",$city1[0]);
	//smarty assigned for displaying the selected city as pre-selected in dropdown

	$smarty->assign("details",$details);
	$smarty->assign("locality",$locality);
	$smarty->assign("checksum",$checksum);
	$smarty->assign("CRM",'Y');
	$smarty->display("easy_bill_address.htm");
}
else
	Timedout();
?>
