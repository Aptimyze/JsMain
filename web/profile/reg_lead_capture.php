<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once ($path . "/profile/connect.inc");
include_once ($path . "/profile/arrays.php");
$db = connect_db();
//Gets ipaddress of user
$ip = FetchClientIP();
if (strstr($ip, ",")) {
    $ip_new = explode(",", $ip);
    $ip = $ip_new[1];
}
if ($action == 'lead_capture') {
    if ($email_val) {
        if (!checkemail($email_val)) {
            $time = date("Y-m-d G:i:s");
            $email_val = mysql_escape_string($email_val);
            $sql1 = "select LEADID,MOBILE	from MIS.REG_LEAD where EMAIL=" . "'$email_val'";
            $result = mysql_query_decide($sql1, $db) or die;
            $row = mysql_fetch_array($result);
            $leadId = $row[LEADID];
            if (check_mobile_phone($mobile, 51)) $mobile = "";
            if ($leadId) {
                switch ($type) {
                    case 'T':
                        echo $sql = "UPDATE MIS.REG_LEAD SET MTONGUE=" . "'$mtongue'" . "WHERE EMAIL=" . "'$email_val'";
                        mysql_query_decide($sql, $db) or die;;
                    break;
                    case 'M':
                        if ($mobile && $mobile!=$row[MOBILE]) {
                       echo     $sql1 = "UPDATE MIS.REG_LEAD SET MOBILE=" . "'$mobile'" . " WHERE EMAIL=" . "'$email_val'";
                            mysql_query_decide($sql1, $db) or die;
                        }
                    break;
                }
            } else {
                if ($mobile) {
                    $sql = "INSERT IGNORE INTO MIS.REG_LEAD (EMAIL,MOBILE,ENTRY_DT,IPADD,SOURCE) VALUES ('$email_val','$mobile','$time','$ip','$source')";
                    $res = mysql_query_decide($sql, $db) or die;
                }else echo "err";
            }
         }else echo 'err';
    }
	else echo "err";
    die;
}
