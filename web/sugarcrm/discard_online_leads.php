<?php
include("connect.inc");
$db_slave=connect_slave();
$ts=time();
mysql_select_db('sugarcrm',$db_slave);
mysql_query("set session wait_timeout=10000",$db_slave);
$ts -= (24*60*60) * 45;
$before_45_days = date("Y-m-d",$ts);

$sql="SELECT phone_mobile,phone_home FROM leads where deleted<>1 and status<>'6' and converted <> 1 and (phone_mobile<>'' or phone_home<>'')";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
while($row=mysql_fetch_array($res))
{
	if($row['phone_mobile'])	
		$mob_arr[]=$row['phone_mobile'];
	if(strstr($row['phone_home'],"-"))
	{
		$home=@explode("-",$row['phone_home']);	
		$phone=$home[1];
	}	
	else
		$phone=$row['phone_home'];
	if($phone)
		$res_arr[]=$phone;
}
unset($row);
$mobiles=@implode("','",$mob_arr);
$landlines=@implode("','",$res_arr);
if($mobiles!='')
{
	$sql="SELECT USERNAME,PHONE_MOB,DATEDIFF(now(),LAST_LOGIN_DT) AS DAYS FROM newjs.JPROFILE WHERE ACTIVATED='Y' and PHONE_MOB IN ('$mobiles')";
	$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
	while($row=mysql_fetch_array($res))
	{
		$mob=$row['PHONE_MOB'];
		if($row['DAYS']<=45)
			$fin_mob_arr[$mob]=$row['USERNAME'];
		else
			$open_mob_arr[$mob]=$row['USERNAME'];
	}
}
unset($row);


if(is_array($fin_mob_arr))
	update_leads($fin_mob_arr,'7','phone_mobile');
if(is_array($open_mob_arr))
	update_leads($open_mob_arr,'9','phone_mobile');

if($landlines!='')
{
	$sql="SELECT USERNAME,PHONE_RES,DATEDIFF(now(),LAST_LOGIN_DT) AS DAYS FROM newjs.JPROFILE WHERE ACTIVATED='Y' and PHONE_RES IN ('$landlines')";
	$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
	while($row=mysql_fetch_array($res))
	{
		$resi=$row['PHONE_RES'];
		if($row['DAYS']<=45)
		{
			if(!in_array($row['USERNAME'],$fin_mob_arr))
				$fin_res_arr[$resi]=$row['USERNAME'];
		}
		else
		{
			if(!in_array($row['USERNAME'],$open_mob_arr))
				$open_res_arr[$resi]=$row['USERNAME'];
		}
	}
}

if(is_array($fin_res_arr))
	update_leads($fin_res_arr,'7','phone_home');
if(is_array($open_res_arr))
	update_leads($open_res_arr,'9','phone_home');

$sql="select email_address from email_addresses as e,email_addr_bean_rel as b,leads as l where b.bean_module='Leads' and b.email_address_id=e.id and l.id=b.bean_id and l.deleted<>1  and l.status<>'6' and e.deleted<>1 and b.deleted<>1";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
while($row=mysql_fetch_array($res))
{
	$email=$row['email_address'];
	$email_arr[$email]=$email;
}
$email_str=@implode("','",$email_arr);


if($email_str!='')
{
	$db=connect_db();
        mysql_query("set session wait_timeout=10000",$db);
        mysql_select_db('sugarcrm',$db);
	$sql="SELECT EMAIL,USERNAME,DATEDIFF(now(),LAST_LOGIN_DT) AS DAYS FROM newjs.JPROFILE WHERE ACTIVATED='Y' and EMAIL IN ('$email_str') ";
	$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
        while($row=mysql_fetch_array($res))
        {
		$email=$row['EMAIL'];
		$uname=$row['USERNAME'];
		$sql1="select b.bean_id as bid from email_addresses as e,email_addr_bean_rel as b where b.bean_module='Leads' and b.email_address_id=e.id and e.email_address='$email'";
		$res1=mysql_query($sql1,$db_slave) or die(mysql_error1($sql,$db_slave));
		while($row1=mysql_fetch_array($res1))
	        {
			$id=$row1['bid'];
			if($row['DAYS']<=45)
			{
				if(!in_array($row['USERNAME'],$fin_res_arr))
					$sql2="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$uname' where l.id='$id' and l.id=c.id_c and l.deleted<>1 and l.converted<>1 and  l.status<>'6'";
			}
			else
			{
				if(!in_array($row['USERNAME'],$open_res_arr))
					$sql2="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='9', c.username_c='$uname' where l.id='$id' and l.id=c.id_c and l.deleted<>1 and l.converted<>1 and  l.status<>'6'";
			}
			$res2=mysql_query($sql2,$db) or die(mysql_error1($sql2,$db));
			$msg.=$sql2."\n";
		}
	}
//	mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Username updated",$msg);
}

function update_leads($lead_arr,$status,$field)
{
	$db=connect_db();
	mysql_query("set session wait_timeout=10000",$db);
	mysql_select_db('sugarcrm',$db);
        foreach($lead_arr as $k=>$v)
        {
                $sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='$status', c.username_c='$v' where l.id=c.id_c and $field='$k' and l.status<>'6'";
                $res_fin=mysql_query($sql_fin,$db) or die(mysql_error1($sql_fin,$db));
                $msg1.=$sql_fin.":: $field\n";
        }
//	mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Username updated",$msg1);
	//echo $msg1;

}
function mysql_error1($sql,$db)
{
        echo $msg=$sql."\n".mysql_error($db);
        mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Error in leads matchalert",$msg);
}

//echo $msg;
//mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Username updated","DONE");
?>
