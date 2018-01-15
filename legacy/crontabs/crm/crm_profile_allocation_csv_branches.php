<?php 
  	$curFilePath = dirname(__FILE__)."/"; 
 	include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
        $SITE_URL="http://ser6.jeevansathi.com";

	$db = connect_737();

	/*Section to write the data in file from respective tables*/

	//define header to write into csv file.
        $header="\"PROFILEID\"".","."\"EXECUTIVE\"".","."\"BRANCH\""."\"SCORE\"".","."\"CUTOFF_DT\"".","."\"ALLOT_DT\"\n";
	$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_allocation_branches.txt";
        $fp = fopen($filename,"w+");
        if(!$fp)
        {
                die("no file pointer");
        }
        fwrite($fp,$header);
	$sql_pid = "SELECT PROFILEID,ALLOTED_TO,ALLOT_DT from incentive.PROFILE_ALLOCATION_TECH where 1";
        $res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
        $row_pid = mysql_fetch_array($res_pid);
        while($row_pid = mysql_fetch_array($res_pid))
        {
                $profileid = $row_pid['PROFILEID'];
		$alloted_to = $row_pid['ALLOTED_TO'];
		$allot_dt = $row_pid['ALLOT_DT'];
                write_contents_to_file_allocation($profileid,$alloted_to,$allot_dt);
        }
        fclose($fp);
	$profileid_file = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_allocation_branches.txt";
        $msg="For data allocation branches: ".$profileid_file;

        $to="anamika.singh@jeevansathi.com";
        $bcc="vibhor.garg@jeevansathi.com";
        $sub="Daily CSV for allocation branches";
        $from="From:vibhor.garg@jeevansathi.com";
        $from .= "\r\nBcc:$bcc";

        mail($to,$sub,$msg,$from);
	function write_contents_to_file_allocation($profileid,$alloted_to,$allot_dt)
        {
                global $fp,$db;
         
                $sql1 = "SELECT ANALYTIC_SCORE,CUTOFF_DT FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$profileid'";

                $res1 = mysql_query($sql1,$db) or logError($sql1,$db);
                if ($row1 = mysql_fetch_array($res1))
                {
                        $analytic_score = $row1['ANALYTIC_SCORE'];
			$cutoff_dt = $row1['CUTOFF_DT'];
		}
		
		$sql2 = "SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME ='$alloted_to'";

                $res2 = mysql_query($sql2,$db) or logError($sql2,$db);
                if ($row2 = mysql_fetch_array($res2))
                        $branch = $row2['CENTER'];
			
		$line="\"$profileid\"".","."\"$alloted_to\"".","."\"$branch\"".","."\"$analytic_score\"".","."\"$cutoff_dt\"".","."\"$allot_dt\"";

		$data = trim($line)."\n";
		$output = $data;
		unset($data);
		fwrite($fp,$output);
        }

?>
