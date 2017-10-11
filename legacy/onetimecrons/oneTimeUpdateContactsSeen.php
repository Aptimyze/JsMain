<?php
include_once("connect.inc");
include_once("sms_inc.php");
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
$db=connect_db();
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db);
$dbs=connect_slave();
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$dbs);
$the_flag=$argv[1];
$ankit1=0;
$ankit2=3;
if($the_flag==7 || $the_flag==1 || $the_flag==2)
{
	$ankit1=$argv[2];
	$ankit2=$ankit1+1;
	$the_flag2=$argv[2];
	if(!$the_flag2 && $the_flag2!=0)
		die("please enter a second argument\n");
}
if($the_flag==4)
{
	/*
	$sql="SELECT sql_no_cache BOOKMARKER,BOOKMARKEE FROM newjs.BOOKMARKS ";
	$res1=mysql_query_decide($sql,$dbs)  or die(mysql_error($dbs));
	while($row=mysql_fetch_array($res1))
	{
	//      echo $row[RECEIVER];
		$sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWED='$row[BOOKMARKEE]' AND VIEWER='$row[BOOKMARKER]'";
		$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
		$myrow=mysql_fetch_array($res2);
		if($myrow[CNT]>0)
		{
			$sql3="UPDATE newjs.BOOKMARKS SET SEEN='Y' WHERE BOOKMARKER='$row[BOOKMARKER]' AND BOOKMARKEE='$row[BOOKMARKEE]'";
			mysql_query_decide($sql3,$db)  or die(mysql_error($db));
		}
	}
	*/

}
else if($the_flag==5)
{
        $sql="SELECT sql_no_cache SENDER,RECEIVER FROM userplane.CHAT_REQUESTS";
        $res1=mysql_query_decide($sql,$dbs)  or die(mysql_error($dbs));
        while($row=mysql_fetch_array($res1))
        {
        //      echo $row[RECEIVER];
                $sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[RECEIVER]' AND VIEWED='$row[SENDER]'";
                $res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
                $myrow=mysql_fetch_array($res2);
                if($myrow[CNT]>0)
                {
                        $sql3="UPDATE userplane.CHAT_REQUESTS SET SEEN='Y' WHERE SENDER='$row[SENDER]' AND RECEIVER='$row[RECEIVER]'";
                        mysql_query_decide($sql3,$db)  or die(mysql_error($db));
                }
        }


}
else if($the_flag==6)
{
        $sql="SELECT sql_no_cache ID,PROFILEID,MATCH_ID FROM jsadmin.OFFLINE_MATCHES";
        $res1=mysql_query_decide($sql,$dbs)  or die(mysql_error($dbs));
        while($row=mysql_fetch_array($res1))
        {
        //      echo $row[RECEIVER];
                $sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[MATCH_ID]' AND VIEWED='$row[PROFILEID]'";
                $res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
                $myrow=mysql_fetch_array($res2);
                if($myrow[CNT]>0)
                {
                        $sql3="UPDATE jsadmin.OFFLINE_MATCHES SET SEEN='Y' WHERE ID='$row[ID]'";
                        mysql_query_decide($sql3,$db)  or die(mysql_error($db));
                }
        }
}
else if($the_flag==7)
{
	$table='VIEW_LOG_TRIGGER_'.$the_flag2;
        $sql="SELECT sql_no_cache VIEWER,VIEWED FROM $table";
        $res1=mysql_query_decide($sql,$db_211)  or die(mysql_error($db_211));
        while($row=mysql_fetch_array($res1))
        {
        //      echo $row[RECEIVER];
                $sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[VIEWED]' AND VIEWED='$row[VIEWER]'";
                $res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
                $myrow=mysql_fetch_array($res2);
                if($myrow[CNT]>0)
                {
                        $sql3="UPDATE $table SET SEEN='Y' WHERE VIEWED='$row[VIEWED]' AND VIEWER='$row[VIEWER]'";
                        mysql_query_decide($sql3,$db_211)  or die(mysql_error($db_211));
                }
        }
}
for($i=$ankit1;$i<$ankit2;$i++)
{
	
	if($i==0){$server=$db1s;$bond=$db1;}
	else if($i==1){$server=$db2s;$bond=$db2;}
	else if($i==2){$server=$db3s;$bond=$db3;}

	if($the_flag==1)
	{echo "$i\n\n";	
		$sql="SELECT sql_no_cache CONTACTID,SENDER,RECEIVER from CONTACTS WHERE TYPE='I' AND SENDER %3=$i ";
		$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

		while($row=mysql_fetch_array($res1))
		{
			$sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWED='$row[SENDER]' AND VIEWER='$row[RECEIVER]'";
			$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
			$myrow=mysql_fetch_array($res2);
			if($myrow[CNT]>0)
			{
				$sql3="UPDATE CONTACTS SET SEEN='Y' WHERE CONTACTID='$row[CONTACTID]' AND  TYPE='I'";
				$sql4="UPDATE MESSAGE_LOG SET SEEN='Y' WHERE SENDER='$row[SENDER]' AND RECEIVER='$row[RECEIVER]'";
				if($row[RECEIVER]%3 != $i)
				{
					
					if($row[RECEIVER]%3==1)
					{	
						mysql_query_decide($sql4,$db2)  or die(mysql_error($db2));	
						mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));
					}	
					else if($row[RECEIVER]%3==2) 
					{
						mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
						mysql_query_decide($sql4,$db3)  or die(mysql_error($db3));	
					}
					else
					{
						mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
						mysql_query_decide($sql4,$db1)  or die(mysql_error($db1));	
					}
				}
				mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
				mysql_query_decide($sql4,$bond)  or die(mysql_error($bond));	
			}
		}
	}
	else if($the_flag==2)
	{echo "$i\n\n";
		$sql="SELECT sql_no_cache CONTACTID,SENDER,RECEIVER from CONTACTS WHERE TYPE IN('A','D') AND SENDER %3=$i";
		$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

		while($row=mysql_fetch_array($res1))
		{
		//	echo $row[RECEIVER];
			$sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[SENDER]' AND VIEWED='$row[RECEIVER]'";
			$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
			$myrow=mysql_fetch_array($res2);
			if($myrow[CNT]>0)
			{
				$sql3="UPDATE CONTACTS SET SEEN='Y' WHERE CONTACTID='$row[CONTACTID]' AND  TYPE IN('A','D')";
				$sql4="UPDATE MESSAGE_LOG SET SEEN='Y' WHERE SENDER='$row[RECEIVER]' AND RECEIVER='$row[SENDER]'";
				if($row[RECEIVER]%3 != $i)
				{
					
					if($row[RECEIVER]%3==1)	
					{
						mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
						mysql_query_decide($sql4,$db2)  or die(mysql_error($db2));	
					}
					else if($row[RECEIVER]%3==2) 
					{
						mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
						mysql_query_decide($sql4,$db3)  or die(mysql_error($db3));
					}
					else
					{
						mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
						mysql_query_decide($sql4,$db1)  or die(mysql_error($db1));
					}
				}
				mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
				mysql_query_decide($sql4,$bond)  or die(mysql_error($bond));	
				//echo $sql3."<br>";
			}
		}
		
	}
	else if($the_flag==3)
	{echo "$i\n\n";
		$sql="SELECT sql_no_cache PROFILEID,PROFILEID_REQ_BY from PHOTO_REQUEST WHERE PROFILEID %3=$i";
		$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

		while($row=mysql_fetch_array($res1))
		{
		//	echo $row[RECEIVER];
			$sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[PROFILEID_REQ_BY]' AND VIEWED='$row[PROFILEID]'";
			$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
			$myrow=mysql_fetch_array($res2);
			if($myrow[CNT]>0)
			{
				$sql3="UPDATE PHOTO_REQUEST SET SEEN='Y' WHERE PROFILEID='$row[PROFILEID]' AND PROFILEID_REQ_BY='$row[PROFILEID_REQ_BY]'";
				//if(($row[SENDER]%3)!=($row[RECEIVER]%3))
				if($row[PROFILEID_REQ_BY]%3 != $i)
				{
					
					if($row[PROFILEID_REQ_BY]%3==1)	
						mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
					else if($row[PROFILEID_REQ_BY]%3==2) 
						mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
					else
						mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
				}
				mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
				//echo $sql3."<br>";
			}
		}
	}			
	else if($the_flag==8)
	{echo "$i\n\n";
		$sql0="SELECT sql_no_cache DISTINCT PROFILEID_REQ_BY from PHOTO_REQUEST WHERE PROFILEID_REQ_BY %3=$i ";
		$res0=mysql_query_decide($sql0,$server)  or die(mysql_error($server));
		while($row0=mysql_fetch_array($res0))
                {
			$sql00="SELECT PHOTODATE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$row0[PROFILEID_REQ_BY]' AND HAVEPHOTO='Y'";
			$res00=mysql_query_decide($sql00,$db)  or die(mysql_error($db));
			$myrow0=mysql_fetch_array($res00);
                        if($myrow0[PHOTODATE]!='0000-00-00 00:00:00' && mysql_num_rows($res00)>0)
                        {
				$sql="SELECT sql_no_cache PROFILEID from PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$row0[PROFILEID_REQ_BY]'";
				$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

				while($row=mysql_fetch_array($res1))
				{
					$sql3="UPDATE PHOTO_REQUEST SET UPLOAD_SEEN='Y',UPLOAD_DATE='$myrow0[PHOTODATE]' WHERE PROFILEID='$row[PROFILEID]' AND PROFILEID_REQ_BY='$row0[PROFILEID_REQ_BY]'";
					
					if($row[PROFILEID]%3 != $i)
					{
						
						if($row[PROFILEID]%3==1)	
							mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
						else if($row[PROFILEID]%3==2) 
							mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
						else
							mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
					}
					mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
				}
			}
		}
	}			
	else if($the_flag==9)
	{echo "$i\n\n";
		$sql="SELECT sql_no_cache PROFILEID,PROFILEID_REQUEST_BY from HOROSCOPE_REQUEST WHERE PROFILEID %3=$i";
		$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

		while($row=mysql_fetch_array($res1))
		{
		//	echo $row[RECEIVER];
			$sql2="SELECT COUNT(*) AS CNT FROM VIEW_LOG WHERE VIEWER='$row[PROFILEID_REQUEST_BY]' AND VIEWED='$row[PROFILEID]'";
			$res2=mysql_query_decide($sql2,$db_211)  or die(mysql_error($db_211));
			$myrow=mysql_fetch_array($res2);
			if($myrow[CNT]>0)
			{
				$sql3="UPDATE HOROSCOPE_REQUEST SET SEEN='Y' WHERE PROFILEID='$row[PROFILEID]' AND PROFILEID_REQUEST_BY='$row[PROFILEID_REQUEST_BY]'";
				//if(($row[SENDER]%3)!=($row[RECEIVER]%3))
				if($row[PROFILEID_REQUEST_BY]%3 != $i)
				{
					
					if($row[PROFILEID_REQUEST_BY]%3==1)	
						mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
					else if($row[PROFILEID_REQUEST_BY]%3==2) 
						mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
					else
						mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
				}
				mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
				//echo $sql3."<br>";
			}
		}
	}			
	else if($the_flag==10)
	{echo "$i\n\n";
                $sql0="SELECT sql_no_cache DISTINCT PROFILEID_REQUEST_BY from HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY %3=$i";
                $res0=mysql_query_decide($sql0,$server)  or die(mysql_error($server));
                while($row0=mysql_fetch_array($res0))
                {
                        $sql00="SELECT COUNT(*) AS COUNTER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$row0[PROFILEID_REQUEST_BY]' AND SHOW_HOROSCOPE='Y'";
                        $res00=mysql_query_decide($sql00,$db)  or die(mysql_error($db));
                        $myrow0=mysql_fetch_array($res00);
			if($myrow0[COUNTER]>0)
                        {
				$sql000="SELECT DATE FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$row0[PROFILEID_REQUEST_BY]'";	
				$res000=mysql_query_decide($sql000,$db)  or die(mysql_error($db));
				$DATE=mysql_fetch_array($res000);
				$DATE_JI=$DATE[DATE];
				if(mysql_num_rows($res000)==0)
				{
					$sql001="SELECT COUNT(*) AS Coke FROM newjs.HOROSCOPE WHERE PROFILEID='$row0[PROFILEID_REQUEST_BY]'";
					$res001=mysql_query_decide($sql001,$db)  or die(mysql_error($db));
					$myrow01=mysql_fetch_array($res001);
					if($myrow01[Coke]>0)
					{
						$sql002="SELECT SUBMITED_TIME FROM jsadmin.MAIN_ADMIN_LOG WHERE PROFILEID='$row0[PROFILEID_REQUEST_BY]' AND SCREENING_TYPE ='H'";
						$res002=mysql_query_decide($sql002,$db)  or die(mysql_error($db));
						$DATE_=mysql_fetch_array($res002);
						$DATE_JI=$DATE_[SUBMITED_TIME];
					}
					else
						continue;
				}
			
				$sql="SELECT sql_no_cache PROFILEID from HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$row0[PROFILEID_REQUEST_BY]'";
				$res1=mysql_query_decide($sql,$server)  or die(mysql_error($server));

				while($row=mysql_fetch_array($res1))
				{
					$sql3="UPDATE HOROSCOPE_REQUEST SET UPLOAD_SEEN='Y',UPLOAD_DATE='$DATE_JI' WHERE PROFILEID='$row[PROFILEID]' AND PROFILEID_REQUEST_BY='$row0[PROFILEID_REQUEST_BY]'";
					//if(($row[SENDER]%3)!=($row[RECEIVER]%3))
					if($row[PROFILEID]%3 != $i)
					{
						
						if($row[PROFILEID]%3==1)	
							mysql_query_decide($sql3,$db2)  or die(mysql_error($db2));	
						else if($row[PROFILEID]%3==2) 
							mysql_query_decide($sql3,$db3)  or die(mysql_error($db3));
						else
							mysql_query_decide($sql3,$db1)  or die(mysql_error($db1));
					}
					mysql_query_decide($sql3,$bond)  or die(mysql_error($bond));	
					//echo $sql3."<br>";
				}
			}
		}			
	}
}
$id =1;
$mob ="9711291198";
$message="cron is over for $the_flag";
send_sms($message,'',$mob,$id,'','Y');
?>                                             
