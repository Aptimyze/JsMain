<?php

/***********************************************************************************************************************
* FILE NAME     : payment_mis_details.php
* DESCRIPTION   : Shows details of all the users from a particular business affiliate.
* FUNCTIONS     : authenticated()       		: To check if the user is authenticated or not
*               : TimedOut()            		: To take action if the user is not authenticated
* CREATION DATE : 21 feb, 2006
* CREATED BY  	: Puneet Makkar
* Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

include("connect.inc");
//mysql_close($db);
$db=connect_slave();
$db2=connect_db();

$user=getname($cid);
$smarty->assign("user",$user);
$smarty->assign("cid",$cid);
 
if(authenticated($cid))
{
		
	$today=date("Y-m-d");
	
	if($start_date > $entry_dt)
	{
		$strt=$start_date;
	}
	else if($start_date <= $entry_date)
	{
		$strt=$entry_date;
	}
	if($end_date > $today)
	{
		$last=$today;
	}	
	else if($end_date <= $today)
	{
		$last=$end_date;
	}

	$sql="SELECT USERNAME,PASSWORD,EMAIL,IPADD FROM newjs.JPROFILE WHERE SOURCE LIKE 'af".$id."%' AND ENTRY_DT BETWEEN '".$strt."' AND '".$last."'";
	$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$aff_data[]=array(	"USERNAME"=>$row['USERNAME'],
					"PASSWORD"=>$row['PASSWORD'],
					"EMAIL"=>$row['EMAIL'],
					"IPADD"=>$row['IPADD']
				  );
	}

	//$smarty->assign("PAYMENT_MODEL",$);
	$smarty->assign("DATA",$aff_data);
	$smarty->assign("USERNAME",$username);
	$smarty->assign("start_date",$start_date);
	$smarty->assign("end_date",$end_date);
	unset($aff_data);
	$smarty->display("payment_mis_details.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
