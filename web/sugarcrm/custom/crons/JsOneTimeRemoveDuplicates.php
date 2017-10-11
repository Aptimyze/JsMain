<?php
define('sugarEntry',true);
include("/usr/local/scripts/connect_db.php");

$day_45=mktime(0,0,0,date("m"),date("d")-45,date("Y"));
$before_45_days=date("Y-m-d",$day_45);

$db_slave=connect_slave();
mysql_select_db('sugarcrm',$db_slave);
mysql_query("set session wait_timeout=10000",$db_slave);

$db=connect_db();
mysql_select_db('sugarcrm',$db);
mysql_query("set session wait_timeout=10000",$db);

$chunk = 1000;

//Mark converted and discarded whose mobile numbers and phone numbers are already in JPROFILE
$sql="SELECT phone_mobile,phone_home FROM leads where deleted<>1 and converted<>1 and phone_mobile<>'' and phone_home<>''";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
$count = mysql_num_rows($res);
$totalChunks=ceil($count/$chunk);
for($i = 0; $i<$totalChunks; $i++)
{
        $trans = 0;
        $skip = $i*$chunk;
        mysql_data_seek($res,$skip);
	while(($row=mysql_fetch_assoc($res)) && $trans<$chunk)
	{
		$trans++;
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
	if(is_array($fin_mob_arr))
	foreach($fin_mob_arr as $k=>$v)
	{
		$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$v' where phone_mobile='$k'";
		$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
	}
	unset($row);
	if($landlines)
	{
		$sql="SELECT USERNAME,PHONE_RES FROM newjs.JPROFILE WHERE ACTIVATED='Y' and PHONE_RES IN ('$landlines') AND LAST_LOGIN_DT < '$before_45_days'";
		$res=mysql_query($sql,$db_slave) or die(mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$resi=$row['PHONE_RES'];
			$fin_res_arr[$resi]=$row['USERNAME'];
		}
	}
	if(is_array($fin_res_arr))
	foreach($fin_res_arr as $k=>$v)
	{
		$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='7', c.username_c='$v' where phone_home='$k'";
		$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
	}
}

//Soft delete older duplicate leads having same mobile number 
$sql="SELECT count( * ) cnt, phone_mobile FROM leads WHERE deleted <>1 AND converted <>1 AND phone_mobile <> '' GROUP BY phone_mobile HAVING cnt >1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
$i = 0;
while($row=mysql_fetch_assoc($res))
{
	$dupMob = $row["phone_mobile"];
	$sql1 = "select max(`date_modified`) maxDate from leads where phone_mobile = '$dupMob'";
	$res1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($res1);
	$maxDate = $row1["maxDate"];
	$sql2 = "update leads set deleted = 1 where phone_mob = '$dupMob' and date_modified < '$maxDate'";
	mysql_query($sql2,$db) or die(mysql_error());
}

//Soft delete older duplicate leads having same phone number 
$sql="SELECT count( * ) cnt, phone_home FROM leads WHERE deleted <>1 AND converted <>1 AND phone_home <> '' GROUP BY phone_home HAVING cnt >1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
$i = 0;
while($row=mysql_fetch_assoc($res))
{
        $dupHome = $row["phone_home"];
        $sql1 = "select max(`date_modified`) maxDate from leads where phone_home = '$dupHome'";
        $res1 = mysql_query($sql1);
        $row1 = mysql_fetch_array($res1);
        $maxDate = $row1["maxDate"];
        $sql2 = "update leads set deleted = 1 where phone_home = '$dupHome' and date_modified < '$maxDate'";
        mysql_query($sql2,$db) or die(mysql_error());
}

//Mark converted and discarded whose email ids are already in JPROFILE
$sql="select email_address from email_addresses as e,email_addr_bean_rel as b,leads as l where b.bean_module='Leads' and b.email_address_id=e.id and l.id=b.bean_id and l.deleted<>1 and l.converted<>1 and e.deleted<>1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
$count = mysql_num_rows($res);
$totalChunks=ceil($count/$chunk);
for($i = 0; $i<$totalChunks; $i++)
{
        $trans = 0;
        $skip = $i*$chunk;
        mysql_data_seek($res,$skip);

	while(($row=mysql_fetch_assoc($res)) && $trans<$chunk)
	{
		$trans++;
		$email=$row['email_address'];
		$email_arr[$email]=$email;
	}
	$email_str=@implode("','",$email_arr);

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
}
//Soft delete older duplicate leads having same email id 
$sql="SELECT count(id) cnt, email_address_id FROM `email_addr_bean_rel` where deleted!= 0 GROUP BY email_address_id having cnt>1";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
$i = 0;
while($row=mysql_fetch_assoc($res))
{
        $dupEmailId = $row["email_address_id"];
        $sql1 = "select bean_id from email_addr_bean_rel where email_address_id = '$dupEmailId' order by date_modified";
        $res1 = mysql_query($sql1);
	$i = 0;
	$leadIdArr = array();
	$leadComma = "";
        while($row1 = mysql_fetch_array($res1))
	{
		if($i!=0)
			$leadIdArr[] = $row1['bean_id'];
		$i++;
	}
	$leadComma = implode("','", $leadIdArr);
	$sql2 = "update leads set deleted = 1 where deleted != 0 and converted != 0 and id in ('$leadComma')";
	mysql_query($sql2, $db) or die(mysql_error());
}

