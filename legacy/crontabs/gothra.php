<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	ini_set('max_execution_time','0');
	include("connect.inc");
	$db=connect_db();

		//adding mailing to gmail account to check if file is being used
	include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='gothra.php is being hit. We can wrap this to JProfileUpdateLib';
               $subject="gothra.php";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 	//ending mail part
	$sql="select max(PROFILEID) as MAX1 from JPROFILE";
	$result=mysql_query($sql) or die(mysql_error());

	$myrow=mysql_fetch_array($result);

	$count=$myrow["MAX1"];

	$sql="select ORIGINAL,FINAL from GOTHRA";
	$result=mysql_query($sql) or die(mysql_error());

	while($myrow=mysql_fetch_array($result))
	{
		$temp=strtolower($myrow["ORIGINAL"]);
		$gothra_arr["$temp"]=strtoupper(substr($myrow["FINAL"],0,1)) . strtolower(substr($myrow["FINAL"],1));
	}

	for($i=1;$i<=$count;$i++)
	{
		$sql="select GOTHRA from JPROFILE where PROFILEID=$i";
		$res=mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($res)>0)
		{
			$myrow=mysql_fetch_array($res);

			$origstr=strtolower($myrow["GOTHRA"]);

			$finalstr=$gothra_arr["$origstr"];

			$sql="update JPROFILE set GOTHRA='" . addslashes($finalstr) . "',TIMESTAMP=TIMESTAMP where PROFILEID=$i";
			mysql_query($sql) or die(mysql_error());
		}
	}

?>
