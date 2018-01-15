<?php
include_once('../connect.inc');
$db=connect_db();
$now = date("Y-m-d G:i:s");
$ts = time();
    $current_time = date("Y-m-d G:i:s",$ts);
    $ts -= 60;
    $before_one_minute = date("Y-m-d G:i:s",$ts);

    $sql_ip = "SELECT IP FROM newjs.BLOCK_IP WHERE IP = '$ip' AND TIME BETWEEN '$before_one_minute' AND '$current_time'";
    $res_ip = mysql_query_decide($sql_ip) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",      $sql_ip,"ShowErrTemplate");
    if(mysql_num_rows($res_ip) > 1)
    {
        die("Too many requests !");
    }
    else
    {
        $sql_ip_ins = "INSERT INTO newjs.BLOCK_IP(IP,TIME) VALUES('$ip','$now')";
        mysql_query_decide($sql_ip_ins) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",        $sql_ip_ins,"ShowErrTemplate");
    }
    /*End of - Code to check spammer, checking for request from same ip. Block lead creation if request > 5 within 1 minute*/
if($_REQUEST[email]){
        $flag=checkemail($email);
        if($flag && $flag !=2){
            $err=1;
            echo "Err:Invalid email";die;
		}
		if($gender=='M')
			$gender_c='F';
		if($gender=='F')
			$gender_c='M';
				$link=$SITE_URL."/sugarcrm/custom/crons/create_sugar_lead.php?email=$email&last_name=$email&gender_c=$gender_c&source_c=17&posted_by_c=0&viewed_profileid_c=$viewed_profileid&from_dp_banner=1";
				$handle = curl_init();
				curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_HEADER, 1);
				curl_setopt($handle,CURLOPT_MAXREDIRS, 5);
				curl_setopt($handle,CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($handle,CURLOPT_CONNECTTIMEOUT, 20);
				curl_setopt($handle, CURLOPT_URL,$link);
				curl_exec($handle);
				curl_close($handle);
}
