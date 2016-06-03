<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");

	$today = date("Y-m-d");
	if($today>'2009-03-05')
		exit;

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include ("../connect.inc");
	$db = connect_slave();

	//Pune
	$sql_mt = "SELECT PROFILEID,ALLOTED_TO FROM incentive.PROFILE_ALLOCATION WHERE ALLOTED_TO IN ('bharat.vaswani','prasann.shewalkar','shashank.ghanekar','sanjana.sahni','poorti.thombre','kavita.dhumal')";
	$res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
	while($row_mt = mysql_fetch_array($res_mt))
	{
		$pid=$row_mt["PROFILEID"];
		$allot_to=$row_mt["ALLOTED_TO"];
		$sql_pid = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
                $res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
                $row_pid = mysql_fetch_array($res_pid);
                $uname = $row_pid['USERNAME'];
		$sql_pid1 = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid'";
                $res_pid1 = mysql_query($sql_pid1,$db) or die("$sql_pid1".mysql_error($db));
		$row_pid1 = mysql_fetch_array($res_pid1);
                $score = $row_pid1['SCORE'];
		$data_pune[]="\"$uname\"".","."\"$score\"".","."\"$allot_to\"\n";		
	}
	//Mumbai
	$sql_mt = "SELECT PROFILEID,ALLOTED_TO FROM incentive.PROFILE_ALLOCATION WHERE ALLOTED_TO IN ('shital.chhabria','swaleha.khan','hijli.pumpa','amit.shriyan','aparna.karmakar','harshada.chougule','urvi.savla','chandan.ghatkar','vijay.singh','sushma.upadhyay','imran.rupani','sulochana.gaikwad','priyank.visariya','vasudha.gupta','swetha.shetty','rajeev.joshi','trupti.prabhu','reena.dsouza','dipali.gandhi','Rinki Sitap','tripti.daga','ritu.singh')";
        $res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
	while($row_mt = mysql_fetch_array($res_mt))
        {
                $pid=$row_mt["PROFILEID"];
                $allot_to=$row_mt["ALLOTED_TO"];
                $sql_pid = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
                $res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
                $row_pid = mysql_fetch_array($res_pid);
                $uname = $row_pid['USERNAME'];
                $sql_pid1 = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid'";
                $res_pid1 = mysql_query($sql_pid1,$db) or die("$sql_pid1".mysql_error($db));
                $row_pid1 = mysql_fetch_array($res_pid1);
                $score = $row_pid1['SCORE'];
                $data_mum[]="\"$uname\"".","."\"$score\"".","."\"$allot_to\"\n";
        }
	//define header to write into csv file.
	$header="\"USERNAME\"".","."\"SCORE\"".","."\"ALLOTED_TO\"\n";

        $filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/branch_data_".date('Y-m-d')."_pune.txt";
        $filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/branch_data_".date('Y-m-d')."_mum.txt";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");

        if(!$fp1 || !$fp2)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
        fwrite($fp2,$header);

	for($i=0;$i<count($data_pune);$i++)
	{
		unset($line);
		$line = trim($data_pune[$i])."\n";
		fwrite($fp1,$line);
	}

	for($i=0;$i<count($data_mum);$i++)
	{
		unset($line);
		$line = trim($data_mum[$i])."\n";
                fwrite($fp2,$line);
	}
        fclose($fp1);
        fclose($fp2);
	$SITE_URL="http://www.jeevansathi.com";
	$profileid_file1 = $SITE_URL."/crm/csv_files/branch_data_".date('Y-m-d')."_pune.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/branch_data_".date('Y-m-d')."_mum.txt";

	$msg="For Pune: ".$profileid_file1;
	$msg.="\nFor Mumbai : ".$profileid_file2;

	$to="anamika.singh@jeevansathi.com";
	$sub="Branch Data";
	$from="From:vibhor.garg@jeevansathi.com";
	mail($to,$sub,$msg,$from);
?>
