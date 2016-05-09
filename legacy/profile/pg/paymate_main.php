<?php
include ("../connect.inc");
include ("functions.php");
connect_db();

$smarty->assign("CHECKSUM", $checksum);
if ($frame == 1) {

    $sql_orderid = "select ORDERID from billing.PAYMATE_ORDERID where ID='$mer_orderid'";
    $res_orderid = mysql_query_decide($sql_orderid) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_orderid, "ShowErrTemplate");
    if (mysql_num_rows($res_orderid) >= 0) {
        $myrow_orderid = mysql_fetch_array($res_orderid);
        $mer_orderid = $myrow_orderid['ORDERID'];
        
        $smarty->assign("mer_orderid", $mer_orderid);
        $smarty->assign("txn_amount", $txn_amount);
        $smarty->assign("MOBILE", $mob_no);
        
        $smarty->display("pg/paymate1.htm");
    } 
    else {
        mail("gaurav.arora@jeevansathi.com", "Online Payment details through PAYMATE", "Details are : Payment failed as orderid is not correct from PAYMATE in paymate_main.php in frame 1");
        
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        $smarty->display("pg/ordererror.htm");
        exit();
    }
} 
elseif ($frame == 3) {
    
    if ($paymate_trxid) {
        $req = "paymate_trxid=" . $paymate_trxid;
        $filename = "https://www.paymate.co.in/connect/paymate_status.aspx?paymate_trxid=" . $paymate_trxid;
        $somecontent = $req;
        
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            mail("gaurav.arora@jeevansathi.com", "PAYMATE no fopen connection in frame 3", "no fopen");
            return true;
        } 
        else {
            $res = '';
            while (!feof($handle)) {
                $res.= fread($handle, 8192);
            }
            
            fclose($handle);
                        
            $res = explode("&", $res);
            
            $res_temp = explode("=", $res[0]);
            
            if (strcmp($res_temp[0], "paymate_trxid") == 0) {
                $paymate_trxid = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[1]);
            
            if (strcmp($res_temp[0], "paymate_status") == 0) {
                $paymate_status = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[2]);
            
            if (strcmp($res_temp[0], "txn_amount") == 0) {
                $txn_amount = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[3]);
            
            if (strcmp($res_temp[0], "mer_orderid") == 0) {
                $mer_orderid = $res_temp[1];
            }
            $STATUS = $paymate_status;
            
            if ($STATUS == 400) {
                $smarty->assign("DONE", 'Y');
                $msg = "Transaction Successfull";
            } 
            elseif ($STATUS == 401) {
                $smarty->assign("DONE", 'Y');
                $msg = "Invalid User Name or Reference Number";
            } 
            elseif ($STATUS == 402) {
                $smarty->assign("DONE", 'N');
                $msg = "Transaction Under Process";
            } 
            elseif ($STATUS == 403) {
                $smarty->assign("DONE", 'Y');
                $msg = "Transaction Declined";
            } 
            elseif ($STATUS == 404) {
                $smarty->assign("DONE", 'Y');
                $msg = "Transaction Killed";
            } 
            elseif ($STATUS == 405) {
                $smarty->assign("DONE", 'N');
                $msg = "Transaction Pending";
            } 
            elseif ($STATUS == 406) {
                $smarty->assign("DONE", 'Y');
                $msg = "Empty Value";
            } 
            elseif ($STATUS == 407) {
                $smarty->assign("DONE", 'Y');
                $msg = "Invalid Mobile Number";
            } 
            elseif ($STATUS == 410) {
                $smarty->assign("DONE", 'Y');
                $msg = "Invalid Transaction Id";
            } 
            elseif ($STATUS == 471) {
                $smarty->assign("DONE", 'Y');
                $msg = "Invalid Amount";
            } 
            elseif ($STATUS == 500) {
                $smarty->assign("DONE", 'Y');
                $msg = "Timedout";
            } 
            else {
                $smarty->assign("DONE", 'Y');
                $msg = "No Reply from PAYMATE";
            }
            
            $smarty->assign("STATUS", $STATUS);
            $smarty->assign("txn_amount", $txn_amount);
            $smarty->assign("mer_orderid", $mer_orderid);
            $smarty->assign("MOBILE", $mob_no);
            $smarty->assign("paymate_trxid", $paymate_trxid);
            $smarty->display("pg/paymate3.htm");
        }
    } 
    else {
        mail("gaurav.arora@jeevansathi.com", "PAYMATE no paymate_trxid", "Details are : Payment failed as no paymate_trxid in paymate_main in frame 3");
    }
}
?>
