<?php
define('sugarEntry',true);
include("../../../profile/connect.inc");
include("../../include/utils/systemProcessUsersConfig.php");

global $process_user_mapping;

$processUserId=$process_user_mapping["one_time_dedupe"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

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
$sql1="SELECT phone_home,id FROM leads where deleted<>1 and converted<>1 and phone_home<>''";
$res1=mysql_query($sql1,$db_slave) or die(mysql_error());
$count = mysql_num_rows($res1);
$totalChunks=ceil($count/$chunk);
for($i = 0; $i<$totalChunks; $i++)
{
        $trans = 0;
        $skip = $i*$chunk;
        mysql_data_seek($res1,$skip);
		$res_arr=array();
		$id_arr=array();
	while(($row=mysql_fetch_assoc($res1)) && $trans<$chunk)
	{
		$trans++;
		$sql2="select std_c from leads_cstm where id_c='".$row['id']."'";
		$res2=mysql_query($sql2,$db_slave) or die(mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$std=substr($row2['std_c'],1);
		if(strstr($row['phone_home'],"-"))
		{
			$home=@explode("-",$row['phone_home']);	
			$home_phone=$home[1];
		}	
		else
			$home_phone=$row['phone_home'];
		$home_phone=trim($home_phone);
		if(substr($home_phone,0,1)=='0')
			$home_phone=substr($home_phone,1);
		if(strlen($home_phone)>=10)
			$phone=$home_phone;
		else
			$phone=$std.$home_phone;	
		if(trim($phone))
		$res_arr[]=$phone;
		$id_arr[$phone]=$row['id'];
	}
	unset($row);
	$fin_res_arr= array();
	$landlines=@implode("','",$res_arr);
	if($landlines)
	{
		$sql="SELECT USERNAME,PHONE_WITH_STD,INCOMPLETE FROM newjs.JPROFILE WHERE  PHONE_WITH_STD IN ('$landlines')";
		$res=mysql_query($sql,$db_slave) or die(mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$resi=$row['PHONE_WITH_STD'];
			$fin_res_arr[$resi]=$row['USERNAME'].",".$row['INCOMPLETE'];
		}
	}
	if(is_array($fin_res_arr))
	{		
	foreach($fin_res_arr as $k=>$v)
	{
		$v_arr=explode(",",$v);
		$isIncomplete=false;
		if($v_arr[1]=='Y')
			$isIncomplete=true;
		$uname=$v_arr[0];
		$id=$id_arr[$k];
		if($isIncomplete)
			$sql_fin="UPDATE leads as l,leads_cstm as c SET l.status='24',c.disposition_c='23',c.username_c='$uname',c.jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.deleted<>1 and l.id=c.id_c";
		else
			$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='26',c.disposition_c='30',c.username_c='$uname',c.jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.deleted<>1 and l.id=c.id_c";
		$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
	}
	}
}

$sql2="SELECT phone_mobile,id FROM leads where deleted<>1 and converted<>1 and phone_mobile<>''";
$res1=mysql_query($sql2,$db_slave) or die(mysql_error());
$count = mysql_num_rows($res1);
$totalChunks=ceil($count/$chunk);
for($i = 0; $i<$totalChunks; $i++)
{
        $trans = 0;
        $skip = $i*$chunk;
        mysql_data_seek($res1,$skip);
		$mob_arr=array();
		$id_arr=array();
	while(($row=mysql_fetch_assoc($res1)) && $trans<$chunk)
	{
		$trans++;
		$mob_no=trim($row['phone_mobile']);
		if(substr($mob_no,0,1)=='0')
			$mob_no=substr($mob_no,1);
		$id_arr[$mob_no]=$row['id'];
		if($mob_no)
		$mob_arr[]=$mob_no;
	}
	unset($row);
	$mobiles=@implode("','",$mob_arr);
	$fin_mob_arr=array();
	if($mobiles)
	{
		$sql="SELECT USERNAME,PHONE_MOB,INCOMPLETE FROM newjs.JPROFILE WHERE  PHONE_MOB IN ('$mobiles')";
		$res=mysql_query($sql,$db_slave) or die(mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$mob=$row['PHONE_MOB'];
			$fin_mob_arr[$mob]=$row['USERNAME'].",".$row['INCOMPLETE'];
		}
	}
	if(is_array($fin_mob_arr))
	{//var_dump($fin_mob_arr);
	foreach($fin_mob_arr as $k=>$v)
	{
		$v_arr=explode(",",$v);
		$isIncomplete=false;
		if($v_arr[1]=='Y')
			$isIncomplete=true;
		$uname=$v_arr[0];
		$id=$id_arr[$k];
		if($isIncomplete)
		$sql_fin="UPDATE leads as l,leads_cstm as c SET l.status='24',c.disposition_c='23',c.username_c='$uname',c.jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.deleted<>1 and l.id=c.id_c";
		else
		$sql_fin="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='26',c.disposition_c='30',c.username_c='$uname',c.jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.deleted<>1 and l.id=c.id_c";
		$res_fin=mysql_query($sql_fin,$db) or die(mysql_error());
	}
	}
	unset($row);
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
	$sql2 = "update leads,leads_cstm set deleted = 1,status='32',disposition_c='27',modified_user_id='$processUserId',date_modified='$updateTime' where phone_mobile = '$dupMob' and date_modified < '$maxDate' and id=id_c";
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
        $sql2 = "update leads,leads_cstm set deleted = 1,status='32',disposition_c='27',modified_user_id='$processUserId',date_modified='$updateTime' where phone_home = '$dupHome' and date_modified < '$maxDate' and id=id_c";
        mysql_query($sql2,$db) or die(mysql_error());
}

//Mark converted and discarded whose email ids are already in JPROFILE
$sql="select email_address from email_addresses as e,email_addr_bean_rel as b,leads as l where b.bean_module='Leads' and b.email_address_id=e.id and l.id=b.bean_id and l.deleted<>1 and l.converted<>1 and e.deleted<>1";
$res1=mysql_query($sql,$db_slave) or die(mysql_error());
$count = mysql_num_rows($res1);
$totalChunks=ceil($count/$chunk);
for($i = 0; $i<$totalChunks; $i++)
{
        $trans = 0;
        $skip = $i*$chunk;
        mysql_data_seek($res1,$skip);
	unset($email_arr);
	while(($row=mysql_fetch_assoc($res1)) && $trans<$chunk)
	{
		$trans++;
		$email=$row['email_address'];
		$email_arr[$email]=$email;
	}
	$email_str=@implode("','",$email_arr);

	if($email_str!='')
	{
		$sql="SELECT EMAIL,USERNAME,INCOMPLETE FROM newjs.JPROFILE WHERE  EMAIL IN ('$email_str')";
		$res=mysql_query($sql,$db_slave) or die(mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$email=$row['EMAIL'];
			$uname=$row['USERNAME'];
			$isIncomplete=false;
			if($row['INCOMPLETE']=='Y')
				$isIncomplete=true;
			$sql1="select b.bean_id as bid from email_addresses as e,email_addr_bean_rel as b where b.bean_module='Leads' and b.email_address_id=e.id and e.email_address='$email'";
			$res1=mysql_query($sql1,$db_slave) or die(mysql_error());
			while($row1=mysql_fetch_array($res1))
			{
				$id=$row1['bid'];
				if($isIncomplete)
				$sql2="UPDATE leads as l,leads_cstm as c SET l.status='24',c.disposition_c='23',c.username_c='$uname',c.jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.id=c.id_c and l.deleted<>1 and l.converted<>1";
				else
				$sql2="UPDATE leads as l,leads_cstm as c SET l.converted=1,l.status='26',c.disposition_c='30',c.username_c='$uname',jsprofileid_c='$uname',modified_user_id='$processUserId',date_modified='$updateTime' where l.id='$id' and l.deleted<>1 and l.converted<>1 and l.id=c.id_c";
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
	$sql2 = "update leads,leads_cstm set deleted = 1,status='32',disposition_c='27',modified_user_id='$processUserId',date_modified='$updateTime' where id in ('$leadComma') and id=id_c";
	mysql_query($sql2, $db) or die(mysql_error());
}

