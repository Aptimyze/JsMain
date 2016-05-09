<?php

/**
*       Filename        :       vocuher_intermediate.php
*       Description     :       script to show Voucher Intermediate Page after login.
*       Created by      :       Tanu Gupta
*       Created on      :       10-03-2007
**/
header("Cache-Control: public");
include "connect.inc";
connect_db();
 $data=authenticated($checksum);
if($data)
        login_relogin_auth($data);//For contacts details on left panel.
/*****bms code*****/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",4);
$smarty->assign("bms_left",5);
$smarty->assign("bms_bottom",6);
$smarty->assign("bms_right",27);
$smarty->assign("bms_new_win",33);
/*****bms code ends*****/
$sql_v="select ID,COUNT from newjs.VOUCHER_INTERMEDIATE_VIEWED WHERE PROFILEID='$data[PROFILEID]'";
$result_v=mysql_query_decide($sql_v) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_v,"ShowErrTemplate");
if(mysql_num_rows($result_v))
$row_v=mysql_fetch_array($result_v);
if($row_v['ID'])
{
	if($row_v['COUNT']<5)
		$sql_v="update newjs.VOUCHER_INTERMEDIATE_VIEWED set COUNT=COUNT+1 where PROFILEID='$data[PROFILEID]'";
}
else
	$sql_v="INSERT INTO newjs.VOUCHER_INTERMEDIATE_VIEWED values ('','$data[PROFILEID]','1')";
mysql_query_decide($sql_v) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_v,"ShowErrTemplate");
$sql_v="SELECT CLIENTID,GENDER,SLABS,AVAILABLE_IN FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01'";
$result_v=mysql_query_decide($sql_v) or logError("Due to some temporary problem, your request could not be processed. Please try after some time.",$sql_v,"ShowErrTemplate");
$i=mysql_num_rows($result_v);
$j=$i;
if($i%4!=0)
$j=$j-($i%4)+4;
while($j>0)
{
	$row_v=mysql_fetch_assoc($result_v);
	{
		if($row_v["GENDER"] || $row_v["AVAILABLE_IN"] || $row_v["SLABS"])
		$client1=array("clientid"=>$row_v["CLIENTID"],"conditions"=>1);
		else
		$client1=array("clientid"=>$row_v["CLIENTID"],"conditions"=>0);
	}
	$row_v=mysql_fetch_assoc($result_v);
	{
		if($row_v["GENDER"] || $row_v["AVAILABLE_IN"] || $row_v["SLABS"])
                $client2=array("clientid"=>$row_v["CLIENTID"],"conditions"=>1);
                else
                $client2=array("clientid"=>$row_v["CLIENTID"],"conditions"=>0);
	}
	$row_v=mysql_fetch_assoc($result_v);
	{
		if($row_v["GENDER"] || $row_v["AVAILABLE_IN"] || $row_v["SLABS"])
                $client3=array("clientid"=>$row_v["CLIENTID"],"conditions"=>1);
                else
                $client3=array("clientid"=>$row_v["CLIENTID"],"conditions"=>0);
	}
	$row_v=mysql_fetch_assoc($result_v);
	{
		if($row_v["GENDER"] || $row_v["AVAILABLE_IN"] || $row_v["SLABS"])
                $client4=array("clientid"=>$row_v["CLIENTID"],"conditions"=>1);
                else
                $client4=array("clientid"=>$row_v["CLIENTID"],"conditions"=>0);
	}
	$client[]=array($client1,$client2,$client3,$client4);
	$j-=4;
}
$smarty->assign("client",$client);
$smarty->assign("head_tab",'my jeevansathi');
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->display("voucher_intermediate.htm");

?>
