<?php
include("connect.inc");
$db_slave=connect_slave();
$ts=time();
mysql_select_db('sugarcrm',$db_slave);
mysql_query("set session wait_timeout=10000",$db_slave);
$ts -= (24*60*60) * 45;
$before_45_days = date("Y-m-d",$ts);

$sql="SELECT phone_mobile,phone_home FROM leads where deleted<>1 and converted<>1 and phone_mobile<>'' and phone_home<>''";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$mob_arr[]=$row['phone_mobile'];
	if(strstr($row['phone_home'],"-"))
	{
		$home=@explode("-",$row['phone_home']);	
		$res_arr[]=$home[1];
	}	
	else
		$res_arr[]=$row['phone_home'];
}
unset($row);
$mobiles=@implode("','",$mob_arr);
$landlines=@implode("','",$res_arr);
if($mobiles)
{
	$sql="SELECT USERNAME,PHONE_MOB FROM newjs.JPROFILE WHERE ACTIVATED='Y' and PHONE_MOB IN ('$mobiles') AND DATE(LAST_LOGIN_DT) < '$before_45_days'";
	$res=mysql_query($sql,$db_slave) or die(mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$mob=$row['PHONE_MOB'];
		$fin_mob_arr[$mob]=$row['USERNAME'];
	}
}
unset($row);
if($landlines)
{
	/*if($mobiles)
		$landlines=$landlines."','".$mobiles;*/
	$sql="SELECT USERNAME,PHONE_RES FROM newjs.JPROFILE WHERE ACTIVATED='Y' and PHONE_RES IN ('$landlines') AND DATE(LAST_LOGIN_DT) < '$before_45_days'";
	$res=mysql_query($sql,$db_slave) or die(mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$resi=$row['PHONE_RES'];
		$fin_res_arr[$resi]=$row['USERNAME'];
	}
}

$sql="select email_address from email_addresses as e,email_addr_bean_rel as b,leads as l where b.bean_module='Leads' and b.email_address_id=e.id and l.id=b.bean_id and l.deleted<>1 and l.converted<>1 and e.deleted<>1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$email=$row['email_address'];
	$email_arr[$email]=$email;
}
$email_str=@implode("','",$email_arr);

$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);
mysql_select_db('sugarcrm',$db);

if($email_str!='')
{
	$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED='Y' and EMAIL IN ('$email_str')";
	$res=mysql_query($sql,$db_slave) or die(mysql_error());
        while($row=mysql_fetch_array($res))
        {
		$email=$row['EMAIL'];
		$uname=$row['USERNAME'];
		$sql1="select b.bean_id as bid from email_addresses as e,email_addr_bean_rel as b where b.bean_module='Leads' and b.email_address_id=e.id and e.email_address='$email'";
		$res1=mysql_query($sql1,$db_slave) or die(mysql_error());
		while($row1=mysql_fetch_array($res1))
	        {
			$id=$row1['bid'];
			$sql2="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$uname' where l.id='$id' and l.deleted<>1 and l.converted<>1";
			$res2=mysql_query($sql2,$db) or die(mysql_error());
		}
	}
}
if(is_array($fin_mob_arr))
foreach($fin_mob_arr as $k=>$v)
{
	$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$v' where phone_mobile='$k'";
	$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
}

if(is_array($fin_res_arr))
foreach($fin_res_arr as $k=>$v)
{
	$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$v' where phone_home='$k'";
	$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
}


?>
