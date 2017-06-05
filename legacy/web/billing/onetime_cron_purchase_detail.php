<?php
include("../jsadmin/connect.inc");
ini_set('max_execution_time',0);
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$sql_price="SELECT PRICE_RS_TAX,PRICE_DOL,SERVICEID FROM billing.SERVICES";
$res_price= mysql_query($sql_price,$db) or die(mysql_error());
while($row_price=mysql_fetch_array($res_price))
{
	$amount_rs[$row_price['SERVICEID']]=$row_price['PRICE_RS_TAX'];
	$amount_dol[$row_price['SERVICEID']]=$row_price['PRICE_DOL'];
}


$sql="SELECT MAX(BILLID) AS MAX_BILL FROM billing.PURCHASES";
$res=mysql_query($sql,$db) or die(mysql_error());
$row=mysql_fetch_assoc($res);
$BILL_MAX=$row['MAX_BILL'];
$i=0;
$j=1000;
while($i<=$BILL_MAX)
{
	$sql="SELECT P.BILLID,P.SERVICEID,P.PROFILEID,P.ADDON_SERVICEID,CUR_TYPE,STATUS,ACTIVATED_ON,ACTIVATE_ON,EXPIRY_DT,P.DISCOUNT,P.TAX_RATE FROM billing.PURCHASES AS P, billing.SERVICE_STATUS AS S WHERE P.BILLID=S.BILLID AND P.BILLID >= $i AND P.BILLID<$j ";
	$res=mysql_query($sql,$db) or die($sql.mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$billid=$row['BILLID'];
		$serviceid=$row['SERVICEID'];
		$pid=$row['PROFILEID'];
		$status=$row['STATUS'];
		$addon_id=$row['ADDON_SERVICEID'];
		$cur_type=$row['CUR_TYPE'];
		$start_date=$row['ACTIVATED_ON'];
		if($start_date=='0000-00-00')
			$start_date=$row['ACTIVATE_ON'];
		$end_date=$row['EXPIRY_DT'];
		$discount=round(($row['DISCOUNT']*($row['TAX_RATE']+100)/100),2);
		$share='100';
		if($addon_id)
		{
			$sum=0;
			$serviceid.=",".$addon_id;
			$service_arr=explode(",",$serviceid);
			if($cur_type=='RS')
				$price_arr=$amount_rs;
			else
				$price_arr=$amount_dol;
			foreach($service_arr as $k=>$v)
			{
				$amount[$v]=$price_arr[$v];
				$sum+=$amount[$v];	
			}
			foreach($amount as $sid=>$price)
			{
				$share=round(($price/$sum)*100,2);
				$disc=$share/100*$discount;
				$net_price=round(($price-$disc),2);
				$sql_insert="INSERT INTO billing.PURCHASE_DETAIL(BILLID,SERVICEID,CUR_TYPE,PRICE,DISCOUNT,NET_AMOUNT,START_DATE,END_DATE,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE,SHARE,PROFILEID,STATUS,DEFERRABLE) VALUES($billid,'$sid','$cur_type','$price','$disc','$net_price','$start_date','$end_date','$start_date','$end_date','$share','$pid','$status','Y')";
				$res_insert=mysql_query($sql_insert,$db) or die($sql_insert.mysql_error());
			}
			unset($amount);

		}
		else
		{	
			if($cur_type=='RS')
				$price=$amount_rs[$serviceid];
			else
				$price=$amount_dol[$serviceid];

			$net_price=$price-$discount;
			$sql_insert="INSERT INTO billing.PURCHASE_DETAIL(BILLID,SERVICEID,CUR_TYPE,PRICE,DISCOUNT,NET_AMOUNT,START_DATE,END_DATE,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE,SHARE,PROFILEID,STATUS,DEFERRABLE) VALUES($billid,'$serviceid','$cur_type','$price','$discount','$net_price','$start_date','$end_date','$start_date','$end_date','$share','$pid','$status','Y')";
			$res_insert=mysql_query($sql_insert,$db) or die($sql_insert.mysql_error());
		}
	}
	@mysql_ping($db);
	$i=$j;
	$j=$j+1000;
}
?>
