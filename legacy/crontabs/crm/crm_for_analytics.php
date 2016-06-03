<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");

	ini_set("max_execution_time","0");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");

	$header1="\"Profileid\"".","."\"Login Date\"\n";
        $filename1 =$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/csv_for_analytics1.txt";
        $fp1 = fopen($filename1,"w+");
        if(!$fp1)
                die("no file pointer");
        fwrite($fp1,$header1);

	$header2="\"Sender\"".","."\"Receiver\"".","."\"Type\"".","."\"Date\"\n";
        $filename2 =$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/csv_for_analytics2.txt";
        $fp2 = fopen($filename2,"w+");
        if(!$fp2)
                die("no file pointer");
        fwrite($fp2,$header2);
	write_contents_to_file_analytics1($fp1);
	write_contents_to_file_analytics2($fp2);
        fclose($fp1);
        fclose($fp2);
	function write_contents_to_file_analytics1($fp1)
        {
		global $slave_activeServers;
		global $noOfActiveServers;
		$mysqlObj=new Mysql;
		for($i=0;$i<$noOfActiveServers;$i++)
		{
			$myDbName=$slave_activeServers[$i];
	                $db=$mysqlObj->connect("$myDbName");
               		$sql = "Select * from newjs.LOGIN_HISTORY where LOGIN_DT>=ADDDATE(CURDATE(),-8) AND LOGIN_DT<CURDATE()";
                	$res = mysql_query($sql,$db) or logError($sql,$db);
                	while($row = mysql_fetch_array($res))
                	{
                                $profileid = $row['PROFILEID'];
                                $LOGIN_DT = $row['LOGIN_DT'];
                                $line="\"$profileid\"".","."\"$LOGIN_DT\"";
                                $data = trim($line)."\n";
                                $output = $data;
                                unset($data);
                                unset($DPP);
                                fwrite($fp1,$output);
                	}
		}
        }
	function write_contents_to_file_analytics2($fp2)
        {
		global $slave_activeServers;
                global $noOfActiveServers;
                $mysqlObj=new Mysql;
                for($i=0;$i<$noOfActiveServers;$i++)
                {
                        $myDbName=$slave_activeServers[$i];
                        $db=$mysqlObj->connect("$myDbName");
                        mysql_query("set session wait_timeout=10000",$db);
	                $sql = "Select SENDER,RECEIVER,TYPE,date(TIME) as SENDING_DT from newjs.CONTACTS where Date(TIME)>=ADDDATE(CURDATE(),-8) AND Date(TIME)<CURDATE()";
        	        $res = mysql_query($sql,$db) or logError($sql,$db);
                	while($row = mysql_fetch_array($res))
                	{
				$sender = $row['SENDER'];
				$receiver = $row['RECEIVER'];
                                $type = $row['TYPE'];
				$sdate = $row['SENDING_DT'];
                                $line="\"$sender\"".","."\"$receiver\"".","."\"$type\"".","."\"$sdate\"";
                                $data = trim($line)."\n";
                                $output = $data;
                                unset($data);
                                unset($DPP);
                                fwrite($fp2,$output);
                	}
        	}
	}
?>
