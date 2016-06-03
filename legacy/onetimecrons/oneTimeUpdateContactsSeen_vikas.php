<?php
include_once("connect.inc");


//for preventing timeout to maximum possible
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);
//for preventing timeout to maximum possible
$db_211 = connect_211();
$mysqlObj=new Mysql;

$db1=$mysqlObj->connect("11Master");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db1);
$db1s=$mysqlObj->connect("11Slave");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db1s);

$db2=$mysqlObj->connect("211");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db2);
$db2s=$mysqlObj->connect("211Slave");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db2s);

$db3=$mysqlObj->connect("303Master");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db3);
$db3s=$mysqlObj->connect("303Slave");
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db3s);

//mysql_query("set session wait_timeout=10000",$db1);
for($i=2;$i<3;$i++)
{
	echo $i."==";
	if($i==0){$server=$db1s;$bond=$db1;}
	else if($i==1){$server=$db2s;$bond=$db2;}
	else if($i==2){$server=$db3s;$bond=$db3;}
	
	$sql="SELECT DISTINCT SENDER as DATA from CONTACTS WHERE TYPE='I' AND SENDER %3=$i";
	$vikasres=mysql_query_decide($sql,$server) or die(mysql_error($server));

	while($vikasrow=mysql_fetch_array($vikasres))
	{
	$sql="SELECT sql_no_cache RECEIVER from CONTACTS WHERE TYPE='I' AND SENDER = " . $vikasrow['DATA'];
	$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

	while($row=mysql_fetch_array($res1))
	{
		$recarr[]=$row['RECEIVER'];
	}

	if($recarr)
	{	
	//	echo $row[RECEIVER];
		$sql2="SELECT VIEWER AS CNT FROM VIEW_LOG WHERE VIEWED='$vikasrow[DATA]' AND VIEWER in (" . implode(",",$recarr) . ")";
		$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
		while($myrow=mysql_fetch_array($res2))
		{
			$arr[]=$myrow['VIEWER'];
		}

		if($arr)
		{
			$sql3="UPDATE CONTACTS SET SEEN='Y' WHERE SENDER='$vikasrow[DATA]' AND RECEIVER IN (" . implode(",",$arr) . ")";
			//if(($row[SENDER]%3)!=($row[RECEIVER]%3))
			//if($row[RECEIVER]%3 != $i)
			//{
				
				//if($row[RECEIVER]%3==1)	
					mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
				//else if($row[RECEIVER]%3==2) 
					mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
				//else
					mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
			//}
			//mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
			//echo $sql3."<br>";
		}
		unset($arr);
	}
	
	unset($recarr);
	}
}
?>                                             
