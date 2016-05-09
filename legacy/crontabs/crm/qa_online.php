<?php 
	$curFilePath = dirname(__FILE__)."/";
	include_once("/usr/local/scripts/DocRoot.php");
	include ("$docRoot/crontabs/connect.inc");	
	include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	$db = connect_db();

	$ts = time();
	$today = date("Y-m-d",$ts);
	$ts -= 24*60*60;
	$last_day = date("Y-m-d",$ts);
	$entryDt =date('Y-m-d');
	
	$header="PROFILEID|SCORE|EMAIL|MOBILE_NO|LANDLINE_NO<br>";
	$data1 =trim($header)."<br>";
	$data2 =trim($header)."<br>";

	$sql_sub = "SELECT DISTINCT(PROFILEID) FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>'$last_day' AND ENTRY_DT<'$today' AND STATUS = 'DONE' AND TYPE = 'RS'";
        $res_sub = mysql_query($sql_sub,$db) or die($sql_sub.mysql_error($db));
        while($row_sub = mysql_fetch_array($res_sub))
        	$pro_arr[] = $row_sub['PROFILEID'];
	
	if(count($pro_arr)>1)
	        $pro_str = implode(",",$pro_arr);
	elseif(count($pro_arr)==1)
		$pro_str = $pro_arr[0];
	else
		$pro_str = '';

	if($pro_str != ''){
		$sql = "SELECT PROFILEID, COUNT( * ) AS cnt,MAX(ENTRY_DT) FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN ($pro_str) GROUP BY PROFILEID ORDER BY cnt";
		$res = mysql_query($sql,$db) or die($sql.mysql_error($db));
		while($row = mysql_fetch_array($res)){
			$count = $row['cnt'];
			if($count==1)
				$first_time[] = $row['PROFILEID'];
			elseif($count>1)
				$repeaters[] = $row['PROFILEID'];
		}
	
		if(count($first_time)>1)
			$first_time_str = implode(",",$first_time);
		elseif(count($first_time)==1)
			$first_time_str = $first_time[0];
		else
			$first_time_str = '';
		
		if(count($repeaters)>1)
			$repeaters_str = implode(",",$repeaters);
		elseif(count($repeaters)==1)
			$repeaters_str = $repeaters[0];
		else
			$repeaters_str = '';
		
		if($first_time_str != ''){
			$sql1 = "SELECT jp.PROFILEID As ID, GENDER, EMAIL, PHONE_RES As Residence, PHONE_MOB AS Mobile, map.SCORE As ProfScore FROM newjs.JPROFILE jp INNER JOIN incentive.MAIN_ADMIN_POOL map ON (jp.PROFILEID=map.PROFILEID) WHERE jp.PROFILEID IN($first_time_str) AND jp.SOURCE<>'ofl_prof' AND jp.SUBSCRIPTION NOT LIKE '%T%' ORDER BY ProfScore";
			$res1 = mysql_query($sql1,$db) or die($sql1.mysql_error($db));
			while($row1 = mysql_fetch_array($res1)){
				$profileid=$row1["ID"];
				$score=$row1["ProfScore"];
				$email=$row1["EMAIL"];
				$mob_no=$row1["Mobile"];
				$landline_no=$row1["Residence"];
				$line=$profileid."|".$score."|".$email."|".$mob_no."|".$landline_no;
				$data1 .= trim($line)."<br>";
				//fwrite($fp1,$data);
			
				$sql ="insert into billing.QA_ONLINE_CSV_DATA(`PROFILEID`,`SCORE`,`EMAIL`,`MOB_NO`,`RES_NO`,`ENTRY_DT`,`TYPE`) VALUES('$profileid','$score','$email','$mob_no','$landline_no','$entryDt','N')";
				mysql_query($sql,$db) or die($sql.mysql_error($db));
			}
		}
		
		if($repeaters_str != ''){
			$sql2 = "SELECT jp.PROFILEID As ID, GENDER, EMAIL, PHONE_RES As Residence, PHONE_MOB AS Mobile, map.SCORE As ProfScore FROM newjs.JPROFILE jp INNER JOIN incentive.MAIN_ADMIN_POOL map ON (jp.PROFILEID=map.PROFILEID) WHERE jp.PROFILEID IN($repeaters_str) AND jp.SOURCE<>'ofl_prof' AND jp.SUBSCRIPTION NOT LIKE '%T%' ORDER BY ProfScore";
			$res2 = mysql_query($sql2,$db) or die($sql2.mysql_error($db));
			while($row2 = mysql_fetch_array($res2)){
				$profileid=$row2["ID"];
				$score=$row2["ProfScore"];
				$email=$row2["EMAIL"];
				$mob_no=$row2["Mobile"];
				$landline_no=$row2["Residence"];
				$line=$profileid."|".$score."|".$email."|".$mob_no."|".$landline_no;
				$data2 .= trim($line)."<br>";
				//fwrite($fp2,$data);

                                $sql ="insert into billing.QA_ONLINE_CSV_DATA(`PROFILEID`,`SCORE`,`EMAIL`,`MOB_NO`,`RES_NO`,`ENTRY_DT`,`TYPE`) VALUES('$profileid','$score','$email','$mob_no','$landline_no','$entryDt','R')";
                                mysql_query($sql,$db) or die($sql.mysql_error($db));
			}
		}
	}
	$file1 = "qa_online_".date('Y-m-d')."_new";
	$file2 = "qa_online_".date('Y-m-d')."_renewal";	

	$msg="1st time payers who paid the day before: $file1.htm"."<br>";
	$msg.="\nRepeat payers who paid the day before: $file2.htm";

	$to="Sandhya.singh@jeevansathi.com,ashima.arora@jeevansathi.com";
	$cc="vibhav.verma@naukri.com";
	$bcc="manoj.rana@naukri.com,vibhor.garg@jeevansathi.com";
	//$to ="manoj.rana@naukri.com";
	$sub="QA Online";
	$from="JeevansathiCrm@jeevansathi.com";

	$attachmentArr =array("$file1"=>"$data1","$file2"=>"$data2");
	send_email_attach($to,$msg,$sub,$from,$cc,$bcc,$attachmentArr);
	
?>
