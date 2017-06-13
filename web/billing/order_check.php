<?php
include "../jsadmin/connect.inc";
include "../P/pg/functions.php";
connect_db();
include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";
if (authenticated($cid)) {

    if ($Order_Id) {
        $membershipObj = new Membership;
        $Order_Id      = trim($Order_Id);
        $id_arr        = explode("-", $Order_Id);
        $id            = $id_arr[1];
        $sql           = " select PROFILEID from billing.ORDERS where ID=$id";
        $result        = mysql_query_decide($sql) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception('Failure,Go back & try again'));
        $myrow_1       = mysql_fetch_array($result);
        if ($myrow_1['PROFILEID'] > 0) {
            $dup      = false;
            $AuthDesc = 'Y';
            if(empty($force)) {
            	$ret      = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
            } else {
            	$ret = true;
            	$dup = false;
            }
            $pid      = $myrow_1['PROFILEID'];
            $sql1     = "SELECT COUNT(*) AS CNT FROM billing.PURCHASES WHERE PROFILEID='$pid' AND ORDERID='$id'";
            $res1     = mysql_query_decide($sql1) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception('Failure,Go back & try again'));
            $row1     = mysql_fetch_assoc($res1);

            //ret should work for voucher code working
            //        echo "Ret-$ret\n";
            if ((!$dup && $ret) || ($row1['CNT'] < 1)) {
                $membershipObj->startServiceOrder($Order_Id);
                $sql_tmp = "INSERT INTO billing.ORDERS_STARTED(ORDERID, ENTRY_DT) VALUES('$Order_Id',now())";
                $res_tmp = mysql_query_decide($sql_tmp) or die(mysql_error_js() . $sql_tmp);

                echo "Service Started\n";
            } else {
                echo "Service can not be started again\n";
            }

        } else {
            echo " orderid not available\n";
        }

    }
    $smarty->assign("CID", $cid);
    $smarty->assign("username", getuser($cid));
    $smarty->display("../jsadmin/order_check.htm");
} else //user timed out
{
    $msg = "Your session has been timed out  ";
    $msg .= "<a href=\"../jsadmin/mainpage.php\">";
    $msg .= "Login again </a>";
    $smarty->assign("MSG", $msg);
    $smarty->display("../jsadmin/jsadmin_msg.tpl");
}
