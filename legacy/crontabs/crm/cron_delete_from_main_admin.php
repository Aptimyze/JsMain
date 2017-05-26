<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include "../connect.inc";
                                                                                                                            
$db=connect_db();
                                                                                                                            
$ts             = time();
$start_time     = date("Y-m-d H:i:s");
$curdate        = date("Y-m-d",$ts);
$ts1            = $ts - 62*24*60*60;
$end_date       = date("Y-m-d",$ts1);
                                                                                                                            
$msg="";

$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE STATUS='P' AND ALLOT_TIME<='$end_date'";
$res = mysql_query($sql) or  $msg .= "\n$sql \nError :".mysql_error();
while($row = mysql_fetch_array($res))
{
	$pid_arr[] = $row['PROFILEID'];
}

$pid_str = implode("','",$pid_arr);

$sql_id         = "SELECT MAX( ID ) AS ID, PROFILEID FROM billing.SERVICE_STATUS WHERE PROFILEID IN ('$pid_str') AND SERVEFOR LIKE '%F%' GROUP BY PROFILEID";
$result_id      = mysql_query($sql_id) or  $msg .= "\n$sql_id \nError :".mysql_error();
while($myrow_id = mysql_fetch_array($result_id))
{
        $arr_id[] = $myrow_id['ID'];
}

$profile_cnt    = count($arr_id);
$num            = $profile_cnt/100 + 1;
$j2             = 0;

for($i = 0;$i < $num;$i++)
{
        for($k = $j2;$k < $j2+100;$k++)
        {
                if($arr_id[$k])
                        $temp_arr[] = $arr_id[$k];
        }
                                                                                                                            
        if($temp_arr)
        {
                $str    = implode("','",$temp_arr);
                                                                                                                            
                $sql = "Select PROFILEID, EXPIRY_DT from billing.SERVICE_STATUS where ID in ('$str') and EXPIRY_DT<= '$curdate'";
                if($res = mysql_query($sql))// or die("$sql".mysql_error());
                {
                        while($row = mysql_fetch_array($res))
                        {
                                $profileid          = $row['PROFILEID'];
				$sql2 = "REPLACE INTO incentive.MAIN_ADMIN_LOG SELECT * FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				if(mysql_query($sql2))
				{
					$sql3 = "DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
					mysql_query($sql3) or $msg .= "\n$sql3 \nError :".mysql_error();

					$sql4="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='Y' WHERE PROFILEID='$profileid'";
					mysql_query($sql4) or $msg .= "\n$sql4 \nError :".mysql_error();
				}
				else
					$msg .= "\n$sql2 \nError :".mysql_error();

				$profile_string.=$profileid.", ";
                        }
                }
                else
                {
                        $msg .= "\n$sql \nError :".mysql_error();
                }
		if($profile_arr)
                {
                        $str1   = implode("','",$profile_arr);

                }
                unset($profile_arr);
                unset($expiry_dt_arr);
        }
        $j2+=100;
        $str='';
        unset($temp_arr);
}

//if ($profileid)
//        $profile_string=implode(",",$profileid);
                                                                                                                            
$end_time=date("Y-m-d H:i:s");
                                                                                                                            
$msg.="\n Profile Ids deleted from main admin as not handled and subscription expired\n\n".$profile_string;
$msg.="\n Start time : $start_time";
$msg.="\n End time : $end_time";
mail("shiv.narayan@jeevansathi.com,vibhor.garg@jeevansathi.com","Subscription expiring records deleted from CRM","$msg");

?>
