<?php

include "connect.inc";
include_once "../profile/pg/functions.php"; // included for dollar conversion rate
$db = connect_misdb();
if (authenticated($checksum)) {
    if ($CMDGo) {
        $smarty->assign("flag", "1");
        //added by sriram to provied entrydate-wise or depositdate-wise search
        if ($date_wise == "deposit_dt") {
            $date_wise = "a.DEPOSIT_DT";
            $st_date   = $year . "-" . $month . "-" . $day;
            $end_date  = $year2 . "-" . $month2 . "-" . $day2;
        } else {
            $date_wise = "a.ENTRY_DT";
            $st_date   = $year . "-" . $month . "-" . $day . " 00:00:00";
            $end_date  = $year2 . "-" . $month2 . "-" . $day2 . " 23:59:59";
        }
        //end of - added by sriram to provied entrydate-wise or depositdate-wise search
        $sql = "SELECT a.RECEIPTID,b.USERNAME,b.SERVICEID,b.CENTER,b.DUEAMOUNT,b.DISCOUNT,a.PROFILEID,a.BILLID,a.MODE, a.SOURCE, a.TYPE,a.AMOUNT as amt,a.CD_NUM,a.CD_DT,a.CD_CITY,a.BANK,a.OBANK,a.STATUS,a.BOUNCE_DT,a.ENTRY_DT,a.ENTRYBY,b.WALKIN,a.DEPOSIT_BRANCH,a.TRANS_NUM, a.DOL_CONV_RATE, b.ORDERID AS ORDER_ID FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.BILLID=b.BILLID AND $date_wise BETWEEN '$st_date' AND '$end_date' ";

        if ($mode) {
            if ($mode == "ALL_CASH") {
                $sql .= "AND a.SOURCE IN ('CASH','BANK_TRSFR_CASH','EB_CASH')";
            } elseif ($mode == "ALL_CHEQUE") {
                $sql .= "AND a.SOURCE IN ('CHEQUE','DD','BANK_TRSFR_CHQ','EB_CHEQUE')";
            } elseif ($mode == "ALL_ONLINE") {
                $sql .= "AND a.SOURCE IN ('ONLINE','BANK_TRSFR_ONLINE','IVR')";
            } else {
                $sql .= " AND a.SOURCE='$mode' ";
            }

            $smarty->assign("MODE", $mode);
        }
        if ($mode2 == 'DONE' || $mode2 == 'REFUND') {

            $sql .= " AND a.STATUS='$mode2' ";

        }
        if ($branch != '') {
            $branch = strtoupper($branch);
            $sql .= " AND UPPER(a.$BRANCH_TYPE)='$branch' ";
        }
        if ($exec != '') {
            if ($group == 'WALKIN' || $group == 'ENTRYBY') {
                $sql .= " AND b.$group='$exec' ";
            }
        }
        /*elseif($group=='ALLOTED_TO')
        {
        $sql.=" AND c.$group='$exec' AND b.PROFILEID=c.PROFILEID  AND c.PAYMENT_DT BETWEEN '$st_date' AND '$end_date' ";
        }*/
        $sql .= " ORDER BY a.ENTRY_DT ASC";
        $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js());

        $billOrdObj = new billing_ORDERS('newjs_slave');

        if ($row = mysql_fetch_array($res)) {
            $i = 0;
            do {
                $profileid = $row['PROFILEID'];
                $billid    = $row['BILLID'];
                $entry_dt  = $row['ENTRY_DT'];
                $pur_order_id = ltrim($row['ORDER_ID'], 0);
                if (is_numeric($pur_order_id) && !empty($pur_order_id) && $pur_order_id!=0) {
                	$ordDet = $billOrdObj->getOrderDetailsForIdStr($pur_order_id);
                    $gateway = $ordDet[$pur_order_id]['GATEWAY'];
                }
                /*$billOrdDevObj = new billing_ORDERS_DEVICE('newjs_slave');
                if($mode == 'ONLINE' || $row['SOURCE'] == 'ONLINE'){
                $orderId = $billOrdDevObj->getPaymentSourceFromBillidStr($billid);
                }*/
                $arr[$i]["username"] = $row['USERNAME'];
                $sid                 = $row['SERVICEID'];
                if (($row['MODE'] == 'CASH' || $row['MODE'] == 'GHAR_PAY_CASH') && $row['WALKIN'] == 'ONLINE') {
                    $sql_cent          = "SELECT CENTER from jsadmin.PSWRDS where USERNAME='$row[ENTRYBY]'";
                    $res_cent          = mysql_query_decide($sql_cent, $db) or die("$sql" . mysql_error_js());
                    $row_cent          = mysql_fetch_array($res_cent);
                    $arr[$i]["center"] = $row_cent['CENTER'];
                } else {
                    $arr[$i]["center"] = $row['CENTER'];
                }

                $arr[$i]["dueamt"]         = $row['DUEAMOUNT'];
                $arr[$i]["discount"]       = $row['DISCOUNT'];
                $arr[$i]["receiptid"]      = $row['RECEIPTID'];
                $arr[$i]["deposit_branch"] = $row['DEPOSIT_BRANCH'];
                $arr[$i]["billid"]         = get_billid($entry_dt, $row['BILLID'], $sid);
                $arr[$i]["mode"]           = $row['SOURCE'];
                $arr[$i]["status"]         = $row['STATUS'];
                $arr[$i]["entryby"]        = $row['ENTRYBY'];
                if (!empty($row['TRANS_NUM'])) {
                    $arr[$i]["transaction_number"] = $row['TRANS_NUM'];
                } else {
                    $arr[$i]["transaction_number"] = $orderId[$billid]['ORDERID'] . "-" . $orderId[$billid]['ID'];
                }
                if ($arr[$i]["mode"] == 'CHEQUE' || $arr[$i]["mode"] == 'DD' || $arr[$i]["mode"] == 'GHAR_PAY_CHEQUE') {
                    $arr[$i]["bank"]    = $row['BANK'];
                    $arr[$i]["cd_num"]  = $row['CD_NUM'];
                    $cd_dt              = $row['CD_DT'];
                    list($yy, $mm, $dd) = explode("-", $cd_dt);
                    $arr[$i]["cd_dt"]   = $dd . "/" . $mm . "/" . $yy;
                    $arr[$i]["cd_city"] = $row['CD_CITY'];
                    if ($arr[$i]["status"] == 'BOUNCE') {
                        list($yy, $mm, $dd)   = explode("-", $row['BOUNCE_DT']);
                        $arr[$i]["bounce_dt"] = my_format_date($dd, $mm, $yy);
                    }
                }
                $arr[$i]["type"]     = $row['TYPE'];
                $arr[$i]["amt_paid"] = $row['amt'];
                if ($row['TYPE'] == 'DOL') {
                    $gross_amt = $row['amt'] * $row['DOL_CONV_RATE'];
                } else {
                    $gross_amt = $row['amt'];
                }
/*
 *JSC-3028: Commented because now 70% amount is stored directly in payment_details and payment_details_new so need not calculate again
                if ($gateway == 'APPLEPAY') {
                	$gross_amt = round(($gross_amt*0.70),2);
                }
*/
                if ($mode2 == 'DONE' || $mode2 == 'REFUND') {
                    $total_paid += $gross_amt;
                } elseif ($mode2 == 'ACTUAL') {
                    if ($row['STATUS'] == 'DONE') {
                        $total_done += $gross_amt;
                    }

                    if ($row['STATUS'] == 'REFUND') {
                        $total_refund += $gross_amt;
                    }

                }
                if ($arr[$i]["mode"] == 'ONLINE') {
                    if ($arr[$i]["type"] == 'DOL') {
                        $arr[$i]["amt_paid"] = $row['amt'] * $row['DOL_CONV_RATE'];
                        $arr[$i]["type"]     = "RS";
                    } else {
                        $arr[$i]["amt_paid"] = $row['amt'];
                    }
/*
 *JSC-3028: Commented because now 70% amount is stored directly in payment_details and payment_details_new so need not calculate again
                    if ($gateway == 'APPLEPAY') {
	                	$arr[$i]["amt_paid"] = round(($arr[$i]["amt_paid"]*0.70),2);
	                }
 */
                }
                list($edt, $time)    = explode(" ", $entry_dt);
                list($yy, $mm, $dd)  = explode("-", $edt);
                $arr[$i]["entry_dt"] = $dd . "/" . $mm . "/" . $yy;
                //$arr[$i]["service_type"]=get_service($sid);
                //$temp=get_service_amt($sid,$row["TYPE"],$arr[$i]["mode"]);
                //$arr[$i]["service_type"]=$temp["name"];
                //$arr[$i]["amt_topay"]=$temp["amt"];
                unset($gateway);
                unset($pur_order_id);
                unset($ordDet);
                $i++;
            } while ($row = mysql_fetch_array($res));
        }

        $smarty->assign("arr", $arr);
        $smarty->assign("day", $day);
        $smarty->assign("month", $month);
        $smarty->assign("year", $year);
        $smarty->assign("day2", $day2);
        $smarty->assign("month2", $month2);
        $smarty->assign("year2", $year2);
        $smarty->assign("wtype", $wtype);
        $smarty->assign("exec", $exec);
        $smarty->assign("group", $group);
        if ($mode2 == 'DONE' || $mode2 == 'REFUND') {
            $smarty->assign("total_paid", round($total_paid, 2));
        } elseif ($mode2 == 'ACTUAL') {
            $smarty->assign("total_paid", $total_done - $total_refund);
        }

        $smarty->assign("checksum", $checksum);
        $smarty->display("month_record_acc.htm");
    } else {
        $user      = getname($checksum);
        $privilage = getprivilage($checksum);
        $priv      = explode("+", $privilage);
        $center    = getcenter_for_operator($user);
        for ($i = 0; $i < 12; $i++) {
            $mmarr[$i] = $i + 1;
        }
        for ($i = 2004; $i <= date("Y"); $i++) {
            $yyarr[$i - 2004] = $i;
        }
        for ($i = 0; $i < 31; $i++) {
            $ddarr[$i] = $i + 1;
        }

        if (in_array('BA', $priv)) {
            $smarty->assign("VIEWALL", "Y");

            $sql = "SELECT NAME FROM billing.BRANCHES";
            $res = mysql_query_decide($sql) or die(mysql_error_js());
            while ($row = mysql_fetch_array($res)) {
                $brancharr[] = strtoupper($row['NAME']);
            }

            $sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE REGEXP 'BA|BU'";
            $res = mysql_query_decide($sql) or die(mysql_error_js());
            while ($row = mysql_fetch_array($res)) {
                $usernamearr[] = $row['USERNAME'];
            }
        } elseif (in_array('BU', $priv)) {
            $brancharr[] = strtoupper($center);
            $sql         = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE CENTER='$center' AND PRIVILAGE REGEXP 'BU'";
            $res         = mysql_query_decide($sql) or die(mysql_error_js());
            while ($row = mysql_fetch_array($res)) {
                $usernamearr[] = $row['USERNAME'];
            }
        }
        $smarty->assign("brancharr", $brancharr);
        $smarty->assign("usernamearr", $usernamearr);

/*                $privilage=getprivilage($checksum);
$priv=explode("+",$privilage);
if(in_array('MA',$priv) || in_array('MB',$priv))
{
$smarty->assign("VIEWALL","Y");
//run query : select all branches
$sql="SELECT * FROM billing.BRANCHES";
$res=mysql_query_decide($sql) or die(mysql_error_js());
if($row=mysql_fetch_array($res))
{
$i=0;
do
{
$branch[$i]["id"]=$row['ID'];
$branch[$i]["name"]=$row['NAME'];

$i++;
}while($row=mysql_fetch_array($res));
}

$smarty->assign("branch",$branch);
}
else
{
// run query : select branch of user
$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
$res=mysql_query_decide($sql) or die(mysql_error_js());
if($row=mysql_fetch_array($res))
{
$branch=$row['CENTER'];
}

$smarty->assign("branch",$branch);
}
 */
//                $smarty->assign("priv",$priv);
        list($cur_year, $cur_month, $cur_day) = explode("-", date("Y-m-d"));
        $smarty->assign("cur_day", $cur_day);
        $smarty->assign("cur_month", $cur_month);
        $smarty->assign("cur_year", $cur_year);

        $smarty->assign("ddarr", $ddarr);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("yyarr", $yyarr);
        $smarty->assign("checksum", $checksum);
        $smarty->display("month_record_acc.htm");
    }
} else {
    $smarty->display("jsconnectError.tpl");
}
/*
function get_service($sid)
{
global $db;
/*if($sid=='S1')
$service="F3";
if($sid=='S2')
$service="F6";
if($sid=='S3')
$service="F12";
if($sid=='S4')
$service="V3";
if($sid=='S5')
$service="V6";
if($sid=='S6')
$service="V12";*

$sql="SELECT ";

return $service;
}
 */
function get_service_amt($sid, $type, $mode)
{
    global $db;

    if ($type == 'RS') {
        $fldname = "PRICE_RS";
    } elseif ($type == 'DOL') {
        $fldname = "PRICE_DOL";
    }

    $sql = "SELECT NAME,$fldname as amt FROM billing.SERVICES WHERE SERVICEID='$sid'";
    $res = mysql_query_decide($sql, $db) or die(mysql_query_decide());
    $row = mysql_fetch_array($res);

    if ($mode == 'ONLINE') {
        if ($type == 'DOL') {
            $amt = $row['amt'] * $DOL_CONV_RATE;
        } else {
            $amt = $row['amt'];
        }

    } else {
        $amt = $row['amt'];
    }

    $name = $row['NAME'];

    $data = array("name" => $name, "amt" => $amt);
    return $data;
}

function get_billid($billdt, $billid, $sid)
{
    $billyear        = substr($billdt, 2, 2);
    $billid_toassign = $billyear;
    $d               = $billid_toassign + 1;
    if ($d < 10) {
        $d = "0" . $d;
    }

    $billid_toassign .= $d;

    if ($sid == "S1") {
        $billid_toassign .= "-F03";
    }

    if ($sid == "S2") {
        $billid_toassign .= "-F06";
    }

    if ($sid == "S3") {
        $billid_toassign .= "-F12";
    }

    if ($sid == "S4") {
        $billid_toassign .= "-V03";
    }

    if ($sid == "S5") {
        $billid_toassign .= "-V06";
    }

    if ($sid == "S6") {
        $billid_toassign .= "-V12";
    }

    $no_zero = 6 - strlen($billid);
    for ($i = 0; $i < $no_zero; $i++) {
        $billid_toassign .= "0";
    }
    $billid_toassign .= $billid;

    return $billid_toassign;
}
