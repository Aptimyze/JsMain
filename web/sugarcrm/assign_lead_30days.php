<?php
include_once("connect.inc");
$db_slave=connect_slave();
mysql_query("set session wait_timeout=10000",$db_slave);
$ts=time();
mysql_select_db('sugarcrm',$db_slave);
$ts -= (24*60*60) * 30;
$before_30_days = date("Y-m-d",$ts);
$sql="SELECT l.id,assigned_user_id FROM leads as l where l.deleted<>1 and l.converted<>1 and l.date_entered='$before_30_days'";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$lead=$row['id'];
	$lead_arr[$lead]=$row['assigned_user_id'];
}


$sql="SELECT a.parent_id,a.after_value_string FROM leads_audit as a, leads as l WHERE a.field_name='assigned_user_id' and l.deleted<>1 and l.converted<>1 AND DATE(a.date_created)<'$before_30_days' ORDER BY date_created";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$lead=$row['parent_id'];
	$lead_arr[$lead]=$row['after_value_string'];
}
$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);
mysql_select_db('sugarcrm',$db);
foreach($lead_arr as $k=>$v)
{
	$sql="SELECT acl_roles_users.user_id FROM acl_roles_users,acl_roles where acl_roles_users.user_id='$v' and acl_roles.id=acl_roles_users.role_id and acl_roles.name='Executive'";
	$res=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($res))
	{
	$sql1="SELECT reports_to_id FROM users WHERE id='$v'";
        $res1=mysql_query($sql1) or die(mysql_error());
        $row1=mysql_fetch_assoc($res1);
        $new_user=$row1['reports_to_id'];
  	$sql_up="UPDATE leads SET assigned_user_id='$new_user' WHERE id='$k'";
        $res_up=mysql_query($sql_up) or die(mysql_error());
	}
} 
?>
