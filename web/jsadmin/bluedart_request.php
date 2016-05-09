<?php

/**************************************************************************************
  *       Filename        :       bluedart_request.php
  *       Description     :       Bluedart final pickup request form.
  *       Created by      :       Anurag Gautam
***************************************************************************************/

include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

$db_master = connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

if(authenticated($cid))
{
	$name= getname($cid);
        $smarty->assign("name",$name);

	$sql="SELECT * FROM billing.BLUEDART_COD_REQUEST WHERE SENT_MAIL='N' AND ACTIVE='Y'";
	$res= mysql_query($sql,$db_master) or die(mysql_error($db_master));
	$ajb=0;
	$anu=0;
	while($row=mysql_fetch_array($res))
	{
		$ref_id[]=$row['REF_ID'];
		$airway[]=$row['AIRWAY_NUMBER'];
		$username[]=$row['USERNAME'];
		$entry_date[]=$row['ENTRY_DT'];

		$mob=$row['PHONE_MOB'];
		$mob=str_replace("+91","",$mob);
		$mobArr[]=$mob;
		$sl[]=$anu+1;
		$phone=$row['PHONE_RES'];
		$std=$row['STD'];
		$landline[]=$phone;

		$service=$row['SERVICE'];
		$serviceObj = new Services;
		$service_arr=$serviceObj->getServicesAmount($service,'RS');
		$flag=true;
		foreach($service_arr as $k=>$v)
		{
			if($flag)
				$tmp.=$service_arr[$k]['NAME'];
			else	
				$tmp.=','.$service_arr[$k]['NAME'];
			$flag=false;	
		}
		$ser_names[$ajb]=$tmp;
		$ajb++;
		$anu++;
		$tmp='';

		$ser_name[]=implode("",$ser_names);
		$discount[]=$row['DISCOUNT_AMNT'];
		$total_amount[]=$row['TOTAL_AMOUNT'];

		$address[]=$row['ADDRESS'];
		$pincode[]=$row['PINCODE'];
		$city[]=$row['CITY'];
		$AREA[]=$row['AREA'];

		$operator[]=$row['OPERATOR'];
		$comments[]=$row['COMMENTS'];
	}
	
	/* Assigning value */

	$smarty->assign('CITY',$city);
	$smarty->assign('SL',$sl);
	$smarty->assign('REF_ID',$ref_id);
	$smarty->assign("AIRWAY",$airway);
	$smarty->assign("MOBILE",$mobArr);
	$smarty->assign("LANDLINE",$landline);
	$smarty->assign("PINCODE",$pincode);
	$smarty->assign("SERVICE",$ser_names);
	$smarty->assign("USERNAME",$username);
	$smarty->assign("ENTRY_DATE",$entry_date);
	$smarty->assign("OPERATOR",$operator);
	$smarty->assign("COMMENTS",$comments);
        $smarty->assign("cid",$cid);
	$smarty->display("bluedart_request.htm");
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

?>
