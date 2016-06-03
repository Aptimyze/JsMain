<?php
/*      Filename        :       view_service_details.php MIS.
*       Description     :       file is used for showing details of users to which mail has been sent for them to 
				verify various services like D, K or H. 
				MIS user can show the bill details of the customer.
				MIS user also has the option to finally verify the services of a customer by changing
				details either CONTACT or ASTRO as per customer's  request.
*       Created by      :       Puneet
*       Changed by      :
*       Changed on      :       22-8-2005
*       Changes         :
**/

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$mmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
																     
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);
	 
	if($branch)
	{
		$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$branch'";                         
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$cityarr[]=$row['VALUE'];
		}
	}

	if($cityarr)
	{
		$city_str=implode("','",$cityarr);
														     
		$sql="SELECT DISTINCT p.PROFILEID, p.USERNAME ,p.RPHONE ,p.BILLID FROM billing.PURCHASES AS p left join newjs.JPROFILE AS j ON j.PROFILEID=p.PROFILEID WHERE p.ENTRY_DT between '".$year."-".$month."-01 00:00:00' AND '".$year."-".$month."-31 23:59:59' AND p.STATUS='DONE' AND p.VERIFY_SERVICE='A' AND j.CITY_RES in ('$city_str')";
	}
	else
	{
		$sql="SELECT DISTINCT PROFILEID,USERNAME,RPHONE,BILLID FROM billing.PURCHASES WHERE ENTRY_DT between '".$year."-".$month."-01 00:00:00' AND '".$year."-".$month."-31 23:59:59' AND STATUS='DONE' AND VERIFY_SERVICE='A'";
	}
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	
	while($row=mysql_fetch_array($res))
	     $contacts[]=array("username"=>$row['USERNAME'],"profileid"=>$row['PROFILEID'],"phone"=>$row['RPHONE'],"billid"=>$row['BILLID'],"checksum"=>md5($row['PROFILEID'])."i".$row['PROFILEID']);
	
	$smarty->assign("contacts",$contacts);
	$smarty->assign("month",$mmarr[$month-1]);
	$smarty->assign("year",$year);
	$smarty->assign("cid",$cid);
	$smarty->display("view_service_details.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsconnectError.tpl");

}
?>
