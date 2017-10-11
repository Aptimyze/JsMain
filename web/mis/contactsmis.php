<?php
ini_set('max_execution_time','0');
 ini_set(memory_limit,-1);
        ini_set(mysql.connect_timeout,-1);
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
mysql_query_decide('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query_decide('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysqlObj=new Mysql;
//mysql_select_db_js("newjs");
if($Submit)
{
	$total=0;
	$date1=$year1."-".$month1."-".$day1." 00:00:00";
	$date2=$year2."-".$month2."-".$day2." 23:59:59";
	if($cstatus=='ALL')
	{
		$type_arr['A']=0;
		$type_arr['I']=0;
		$type_arr['C']=0;
		$type_arr['D']=0;
		for($i=0;$i<$noOfActiveServers;$i++)
		{
			$myDbName=$slave_activeServers[$i];
			$myDb=$mysqlObj->connect("$myDbName");
			$sql="SELECT SENDER,TYPE,count(*) as COUNT from newjs.CONTACTS  as ct WHERE  `TIME` between '$date1' and '$date2' group by SENDER,TYPE ";
			$result=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				//$sql="select count(*) from newjs.PROFILEID_SERVER_MAPPING where PROFILEID=$myrow['SENDER'] and SERVERID=$i";
				
				$senders[$myrow['SENDER']][$myrow['TYPE']]=$myrow['COUNT'];
				$sender_arr[]=$myrow['SENDER'];
				
			}
			if($sender_arr)
			{
				$senders_str=implode(",",$sender_arr);
				$sql="select PROFILEID  from newjs.PROFILEID_SERVER_MAPPING where PROFILEID IN ($senders_str) and SERVERID=$i";
				$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$type_arr['A']+=$senders[$row['PROFILEID']]['A'];
					$type_arr['C']+=$senders[$row['PROFILEID']]['C'];
					$type_arr['D']+=$senders[$row['PROFILEID']]['D'];
					$type_arr['I']+=$senders[$row['PROFILEID']]['I'];
					
				}
			}
			
			unset($senders);
			unset($sender_arr);
//				$total+=$myrow['COUNT'];
		}
	
		if($type_arr)
		{
			$total=$type_arr['A']+$type_arr['I']+$type_arr['C']+$type_arr['D'];
			foreach($type_arr as $key=>$val)
			{
				$value_type[]=$key;
				$value_count[]=$val;
			}
		}

		$smarty->assign("TYPE",$value_type);
		$smarty->assign("COUNT",$value_count);	
		$smarty->assign("SHOWALL","Y");
	}
	else
	{
		$total=0;
		for($i=0;$i<$noOfActiveServers;$i++)
                {
			$myDbName=$slave_activeServers[$i];
			$myDb=$mysqlObj->connect("$myDbName");
			$sql="SELECT SENDER,count(*) as CNT from newjs.CONTACTS WHERE  `TIME` between '$date1' and '$date2' AND TYPE='$cstatus' group by SENDER ";
			$result=$mysqlObj->executeQuery($sql,$myDb) or die($sql.mysql_error_js($myDb));
			while($myrow=mysql_fetch_assoc($result))
			{
				$SENDERS[$myrow['SENDER']]=$myrow['CNT'];
				$sender_arr[]=$myrow['SENDER'];
			}
			if($sender_arr)
                        {
                                $senders_str=implode(",",$sender_arr);
                                $sql="select PROFILEID  from newjs.PROFILEID_SERVER_MAPPING where PROFILEID IN ($senders_str) and SERVERID=$i";
                                $res=$mysqlObj->executeQuery($sql,$myDb) or die($sql.mysql_error_js($myDb));
                                while($row=mysql_fetch_array($res))
                                {
					$total+=$SENDERS[$row['PROFILEID']];
                                }
                        }
			unset($SENDERS);
			unset($sender_arr);
			
			//$total+=$myrow[0];
		}
	}

	$smarty->assign("TOTAL",$total);
	$smarty->assign("PAGE","1");
	$smarty->assign("YEAR1",$year1);
	$smarty->assign("MONTH1",$month1);
	$smarty->assign("DAY1",$day1);
	$smarty->assign("YEAR2",$year2);
	$smarty->assign("MONTH2",$month2);
	$smarty->assign("DAY2",$day2);
	$smarty->assign("CSTATUS",$cstatus);

	$smarty->display("contactsmis.tpl");
}
else
{	
	$smarty->display("contactsmis.tpl");
}
?>
