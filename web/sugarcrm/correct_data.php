<?php
include("connect.inc");
$db_slave=connect_slave();
$ts=time();
mysql_select_db('sugarcrm',$db_slave);
mysql_query("set session wait_timeout=10000",$db_slave);
$ts -= (24*60*60) * 55;
$before_55_days = date("Y-m-d",$ts);

$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);
mysql_select_db('sugarcrm',$db);

$sql="SELECT l.id, email_address, phone_mobile, phone_home FROM email_addresses AS e, email_addr_bean_rel AS b, leads AS l WHERE b.bean_module = 'Leads' AND b.email_address_id = e.id AND l.id = b.bean_id AND l.deleted <>1 AND l.converted=1 AND l.status='7' AND e.deleted <>1 and b.deleted<>1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$landline=$row['phone_home'];
	$mobile=$row['phone_mobile'];
	$email=$row['email_address'];
	$lead=$row['id'];
	$flag=0;
	if(!($email=='' && $mobile=='' && $landline==''))
	{
echo "(". $neha++.")";
echo		$sql_sel="SELECT USERNAME FROM newjs.JPROFILE WHERE ACTIVATED='Y' AND DATE(LAST_LOGIN_DT) > '$before_55_days'";
		if($email!='')
		{
			$sql_sel1=$sql_sel." AND EMAIL='$email'";
			$res_sel=mysql_query($sql_sel1,$db_slave) or die(mysql_error());
			$row_sel=mysql_fetch_assoc($res_sel);
			if($row_sel['USERNAME']!='')
			{
				$uname=$row_sel['USERNAME'];
        echo "\n email ***".    $sql1="UPDATE leads_cstm set username_c='$uname' where id_c='$lead'";
				$res1=mysql_query($sql1,$db) or die(mysql_error());
				continue;
			}
		}
	echo " :";
		if($mobile!='')
			$sql_w[]=" PHONE_MOB='$mobile'";
		if($landline!='')
			$sql_w[]=" PHONE_RES='$landline'";
		$sql_wstr=implode(" OR ",$sql_w);
		if($sql_wstr!='')
		{
	echo "\n".		$sql_sel=$sql_sel." AND (".$sql_wstr." ) LIMIT 1" ;
	echo "pakka dia";
			$res_sel=mysql_query($sql_sel,$db_slave) or die(mysql_error());
			$row_sel=mysql_fetch_assoc($res_sel);
			if($row_sel['USERNAME']!='')
			{
				$uname=$row_sel['USERNAME'];
	echo "\n".			$sql1="UPDATE leads_cstm set username_c='$uname' where id_c='$lead'";
				$res1=mysql_query($sql1,$db) or die(mysql_error());
			}
			else
				$flag=1;
		}
		else
			$flag=1;
	}
	else
		$flag=1;
	if($flag==1)
	{
	echo "\n".	$sql1="UPDATE leads set converted='0',l.status='1' where id='$lead'";
		$res1=mysql_query($sql1,$db) or die(mysql_error());
	}
}

?>
