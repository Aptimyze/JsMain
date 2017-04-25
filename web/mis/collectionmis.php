<?php

//include("connect.inc");			commented by Shakti for JSIndicatorMIS, 28 November, 2005

include_once ("../profile/pg/functions.php");
 // included for dollar conversion rate
include_once ("connect.inc");

$db = connect_rep();

$data = authenticated($checksum);
if (isset($data) || $JSIndicator) {
    if ($outside) {
        $CMDGo = 'Y';
        $viewmode = 'N';
        $vtype = 'D';
        $branch = 'ALL';
        if (!$today) $today = date("Y-m-d");
        list($dyear, $dmonth, $d) = explode("-", $today);
    }
    
    $smarty->assign('viewmode', $viewmode);
    
    if (($vtype == 'Q' && $qyear >= '2017') || 
        ($vtype == 'M' && $myear >= '2017') || 
        ($vtype == 'D' && ($dyear > '2017'||( $dyear == '2017' && $dmonth >= '04') ))) {
        $tableName = "PAYMENT_DETAIL_NEW";
        $condition = "IN ('DONE','BOUNCE','CANCEL', 'REFUND', 'CHARGE_BACK')";
    }
    else{
        $tableName = "PAYMENT_DETAIL";
        $condition = "='DONE'";
    }
    
    if ($CMDGo && $viewmode == 'O') {
        $flag = 1;
        $mmarr = array('Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar');
        $qtrarr = array('Apr-Jun', 'Jul-Sep', 'Oct-Dec', 'Jan-Mar');
        
        if ($vtype == 'Q') {
            $yr1 = $qyear;
            $yr2 = $qyear + 1;
            $mnth1 = 04;
            $mnth2 = 03;
        } 
        elseif ($vtype == 'M') {
            $yr1 = $myear;
            $yr2 = $myear + 1;
            $mnth1 = 04;
            $mnth2 = 03;
        } 
        elseif ($vtype == 'D') {
            $yr1 = $yr2 = $dyear;
            $mnth1 = $mnth2 = $dmonth;
        }
        
        $sql_min = "SELECT MIN(BILLID) AS MIN,MAX(BILLID) AS MAX FROM billing.$tableName WHERE ENTRY_DT BETWEEN '$yr1-$mnth1-01 00:00:00' AND '$yr2-$mnth2-31 23:59:59'";
        $res_min = mysql_query_decide($sql_min, $db) or die("$sql_min" . mysql_error_js($db));
        $row_min = mysql_fetch_assoc($res_min);
        $min = $row_min['MIN'];
        $max = $row_min['MAX'];
        
        /*		$sql_max="SELECT MAX(BILLID) AS MAX FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$dyear-$dmonth-30 00:00:00' AND '$dyear-$dmonth-31 23:59:59'";
        $res_max=mysql_query_decide($sql_max,$db) or die("$sql_max".mysql_error_js($db));
        $row_max=mysql_fetch_assoc($res_max);
        $max=$row_max['MAX'];
        */
        
        /*$sql_offline="SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE BILLID BETWEEN '$min' AND '$max'";
        $res_offline=mysql_query_decide($sql_offline,$db) or die("$sql_offline".mysql_error_js($db));
        while($row_offline=mysql_fetch_array($res_offline))
        {
        $offline_bills[]=$row_offline['BILLID'];
        }
        $offline_str=@implode(',',$offline_bills);*/
        
        $offline_str = offline_online_sale($yr1, $yr2, $mnth1, $mnth2);
        if ($branch != "ALL") {
            $bflag = 'N';
            if ($vtype == 'Q') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $qflag = 1;
                $qyearp1 = $qyear + 1;
                $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,QUARTER(billing.$tableName.ENTRY_DT) as qtr,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                $sql.= " GROUP BY QUARTER(billing.$tableName.ENTRY_DT),eb";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'] - 1;
                    if ($qtr < 1) {
                        $qtr+= 3;
                    } 
                    else {
                        $qtr-= 1;
                    }
                    if ($branch == "HO") {
                        if ($row['eb'] == "OFFLINE") {
                            $amt[$qtr]["rj"] = $row['amt'];
                            $tot["rj"]+= $row['amt'];
                             //$amt[$qtr]["rj"];
                            $amttot[$qtr]+= $row['amt'];
                             //$amt[$qtr]["rj"];
                            $hotot+= $row['amt'];
                             //$tot["rj"];
                            
                        } 
                        elseif ($row['eb'] == "ONLINE") {
                            $amt[$qtr]["ol"] = $row['amt'];
                            $tot["ol"]+= $row['amt'];
                             //$amt[$qtr]["ol"];
                            $amttot[$qtr]+= $row['amt'];
                             //$amt[$qtr]["ol"];
                            $hotot+= $row['amt'];
                             //$tot["ol"];
                            
                        } 
                        elseif ($row['eb'] == "ARAMEX") {
                            $amt[$qtr]["ar"] = $row['amt'];
                             //$row['amt'];
                            $tot["ar"]+= $row['amt'];
                             //$amt[$qtr]["ar"];
                            $amttot[$qtr]+= $row['amt'];
                             //$amt[$qtr]["ar"];
                            $hotot+= $row['amt'];
                             //$tot["ar"];
                            
                        } 
                        elseif ($row['eb'] == "BANK_TSFR") {
                            $amt[$qtr]["bt"] = $row['amt'];
                             //$row['amt'];
                            $tot["bt"]+= $row['amt'];
                             //$amt[$qtr]["ar"];
                            $amttot[$qtr]+= $row['amt'];
                             //$amt[$qtr]["ar"];
                            $hotot+= $row['amt'];
                             //$tot["ar"];
                            
                        }
                    } 
                    else {
                        $amt[$qtr]+= $row['amt'];
                        $amt2a[$qtr] = $row['amt'];
                        $tot+= $amt2a[$qtr];
                    }
                }
                $smarty->assign("amttot", $amttot);
                $smarty->assign("hotot", $hotot);
            } 
            elseif ($vtype == 'M') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $mflag = 1;
                $myearp1 = $myear + 1;
                if ($pay_exec == "Selected") {
                    $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,month(billing.$tableName.ENTRY_DT) as mm,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                    if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                    $sql.= " GROUP BY month(billing.$tableName.ENTRY_DT),eb";
                    
                    $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                    while ($row = mysql_fetch_array($res)) {
                        $mm = $row['mm'];
                        if ($mm <= 3) {
                            $mm+= 8;
                        } 
                        else {
                            $mm-= 4;
                        }
                        if ($branch == "HO") {
                            if ($row['eb'] == "OFFLINE") {
                                $amt[$mm]["rj"] = $row['amt'];
                                $tot["rj"]+= $row['amt'];
                                 //$amt[$mm]["rj"];
                                $amttot[$mm]+= $row['amt'];
                                 //$amt[$mm]["rj"];
                                $hotot+= $row['amt'];
                                 //$tot["rj"];
                                
                            } 
                            elseif ($row['eb'] == "ONLINE") {
                                $amt[$mm]["ol"] = $row['amt'];
                                $tot["ol"]+= $row['amt'];
                                 //$amt[$mm]["ol"];
                                $amttot[$mm]+= $row['amt'];
                                 //$amt[$mm]["ol"];
                                $hotot+= $row['amt'];
                                 //$tot["ol"];
                                
                            } 
                            elseif ($row['eb'] == "ARAMEX") {
                                $amt[$mm]["ar"] = $row['amt'];
                                $tot["ar"]+= $row['amt'];
                                 //$amt[$mm]["ar"];
                                $amttot[$mm]+= $row['amt'];
                                 //$amt[$mm]["ar"];
                                $hotot+= $row['amt'];
                                 //$tot["ar"];
                                
                            } 
                            elseif ($row['eb'] == "BANK_TSFR") {
                                $amt[$mm]["bt"] = $row['amt'];
                                $tot["bt"]+= $row['amt'];
                                 //$amt[$mm]["ar"];
                                $amttot[$mm]+= $row['amt'];
                                 //$amt[$mm]["ar"];
                                $hotot+= $row['amt'];
                                 //$tot["ar"];
                                
                            }
                        } 
                        else {
                            $amt[$mm]+= $row['amt'];
                            $amt2a[$mm] = $row['amt'];
                            $tot+= $amt2a[$mm];
                        }
                    }
                } 
                else {
                    if ($pay_exec == 'P') {
                        unset($amt);
                        unset($amt1);
                        unset($tota);
                        unset($totb);
                        $pflag = 1;
                        $modearr = array('CASH', 'CHEQUE', 'DD', 'TT', 'ONLINE', 'OTHER', 'CREDIT', 'CCOFFLINE', 'BANK_TRSFR_ONLINE');
                        $year = $myear;
                        $yearp1 = $year + 1;
                        
                        //				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PAYMENT_DETAIL.MODE as mode FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PAYMENT_DETAIL.MODE";
                        
                        $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,month(billing.$tableName.ENTRY_DT) as mm,billing.$tableName.MODE as mode FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                        if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                        $sql.= " GROUP BY mm,mode ORDER BY mode";
                        
                        $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                        while ($row = mysql_fetch_array($res)) {
                            $mode = $row['mode'];
                            $i = array_search($mode, $modearr);
                            $mm = $row['mm'];
                            if ($mm <= 3) {
                                $mm+= 8;
                            } 
                            else {
                                $mm-= 4;
                            }
                            $amt[$i][$mm] = $row['amt'];
                            $amt1[$mm][$i] = $row['amt'];
                            $tota[$i]+= $amt[$i][$mm];
                            $totb[$mm]+= $amt1[$mm][$i];
                        }
                    } 
                    elseif ($pay_exec == 'E') {
                        unset($amt);
                        unset($amt1);
                        unset($tota);
                        unset($totb);
                        $eflag = 1;
                        $year = $myear;
                        $yearp1 = $year + 1;
                        if ($branch == "HO") {
                            $userarr[] = "ONLINE";
                            $userarr[] = "OFFLINE";
                            $userarr[] = "ARAMEX";
                            $userarr[] = "BANK_TSFR";
                        } 
                        else {
                            $sql_e = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BU%' AND CENTER='$branch'";
                            $res_e = mysql_query_decide($sql_e, $db) or die("$sql_e" . mysql_error_js($db));
                            while ($row_e = mysql_fetch_array($res_e)) {
                                $userarr[] = $row_e['USERNAME'];
                            }
                        }
                        
                        //				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PURCHASES.WALKIN as walkin FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PURCHASES.WALKIN";
                        
                        $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,month(billing.$tableName.ENTRY_DT) as mm,billing.PURCHASES.WALKIN as walkin FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                        if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                        $sql.= " GROUP BY mm,walkin";
                        
                        $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                        while ($row = mysql_fetch_array($res)) {
                            $walkin = $row['walkin'];
                            $i = array_search($walkin, $userarr);
                            $mm = $row['mm'];
                            if ($mm <= 3) {
                                $mm+= 8;
                            } 
                            else {
                                $mm-= 4;
                            }
                            $amt[$i][$mm] = $row['amt'];
                            $amt1[$mm][$i] = $row['amt'];
                            $tota[$i]+= $amt[$i][$mm];
                            $totb[$mm]+= $amt1[$mm][$i];
                        }
                    }
                }
                $smarty->assign("amttot", $amttot);
                $smarty->assign("hotot", $hotot);
            } 
            elseif ($vtype == 'D') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $dflag = 1;
                
                for ($i = 0; $i < 31; $i++) {
                    $ddarr[$i] = $i + 1;
                }
                
                $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,DAYOFMONTH(billing.$tableName.ENTRY_DT) as dd,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                $sql.= " GROUP BY DAYOFMONTH(billing.$tableName.ENTRY_DT),eb";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $dd = $row['dd'] - 1;
                    if ($branch == "HO") {
                        if ($row['eb'] == "OFFLINE") {
                            $amt[$dd]["rj"] = $row['amt'];
                            $tot["rj"]+= $row['amt'];
                             //$amt[$dd]["rj"];
                            $amttot[$dd]+= $row['amt'];
                             //$amt[$dd]["rj"];
                            $hotot+= $row['amt'];
                             //$tot["rj"];
                            
                        } 
                        elseif ($row['eb'] == "ONLINE") {
                            $amt[$dd]["ol"] = $row['amt'];
                            $tot["ol"]+= $row['amt'];
                             //$amt[$dd]["ol"];
                            $amttot[$dd]+= $row['amt'];
                             //$amt[$dd]["ol"];
                            $hotot+= $row['amt'];
                             //$tot["ol"];
                            
                        } 
                        elseif ($row['eb'] == "ARAMEX") {
                            $amt[$dd]["ar"] = $row['amt'];
                            $tot["ar"]+= $row['amt'];
                             //$amt[$dd]["ar"];
                            $amttot[$dd]+= $row['amt'];
                             //$amt[$dd]["ar"];
                            $hotot+= $row['amt'];
                             //$tot["ar"];
                            
                        } 
                        elseif ($row['eb'] == "BANK_TSFR") {
                            $amt[$dd]["bt"] = $row['amt'];
                            $tot["bt"]+= $row['amt'];
                             //$amt[$dd]["ar"];
                            $amttot[$dd]+= $row['amt'];
                             //$amt[$dd]["ar"];
                            $hotot+= $row['amt'];
                             //$tot["ar"];
                            
                        }
                    } 
                    else {
                        $amt[$dd]+= $row['amt'];
                        $amt2a[$dd] = $row['amt'];
                        $tot+= $amt2a[$dd];
                    }
                }
                $smarty->assign("amttot", $amttot);
                $smarty->assign("hotot", $hotot);
            }
        } 
        else {
            $bflag = 'A';
            $sql_b = "SELECT NAME FROM billing.BRANCHES";
            $res_b = mysql_query_decide($sql_b, $db) or die("$sql_b" . mysql_error_js($db));
            while ($row_b = mysql_fetch_array($res_b)) {
                $brancharr[] = strtoupper($row_b['NAME']);
            }
            
            $brancharr[] = "OFFLINE-REVENUE-WITHOUT-TAX";
            $brancharr[] = "REVENUE-WITHOUT-TAX";
            $brancharr[] = "Total-excluding-Misc-(without-tax)";
            $brancharr[] = "MISC-REVENUE-WITHOUT-TAX";
            
            if ($vtype == 'Q') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $qflag = 1;
                $qyearp1 = $qyear + 1;
                $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,QUARTER(billing.$tableName.ENTRY_DT) as qtr,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                $sql.= " GROUP BY QUARTER(billing.$tableName.ENTRY_DT),center,eb ORDER BY center,eb";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                if ($row = mysql_fetch_array($res)) {
                    do {
                        $center = strtoupper($row['center']);
                        $k = array_search($center, $brancharr);
                        $qtr = $row['qtr'] - 1;
                        if ($qtr < 1) {
                            $qtr+= 3;
                        } 
                        else {
                            $qtr-= 1;
                        }
                        if ($center == 'HO') {
                            if ($row['eb'] == 'OFFLINE') {
                                $amt[$k][$qtr]["rj"] = $row['amt'];
                                $tota[$k]["rj"]+= $row['amt'];
                                 //$amt[$k][$qtr]["rj"];
                                
                            } 
                            elseif ($row['eb'] == 'ONLINE') {
                                $amt[$k][$qtr]["ol"] = $row['amt'];
                                $tota[$k]["ol"]+= $row['amt'];
                                 //$amt[$k][$qtr]["ol"];
                                
                            } 
                            elseif ($row['eb'] == 'ARAMEX') {
                                $amt[$k][$qtr]["ar"] = $row['amt'];
                                $tota[$k]["ar"]+= $row['amt'];
                                 //$amt[$k][$qtr]["ar"];
                                
                            } 
                            elseif ($row['eb'] == 'BANK_TSFR') {
                                $amt[$k][$qtr]["bt"] = $row['amt'];
                                $tota[$k]["bt"]+= $row['amt'];
                                 //$amt[$k][$qtr]["ar"];
                                
                            }
                            $amt1[$qtr][$k] = $row['amt'];
                            $totb[$qtr]+= $row['amt'];
                             //$amt1[$qtr][$k];
                            
                        } 
                        else {
                            $amt[$k][$qtr]+= $row['amt'];
                            $amt2a[$k][$qtr] = $row['amt'];
                            $amt1[$qtr][$k] = $row['amt'];
                            $tota[$k]+= $row['amt'];
                             //$amt2a[$k][$qtr];
                            $totb[$qtr]+= $row['amt'];
                             //$amt1[$qtr][$k];
                            
                        }
                    } while ($row = mysql_fetch_array($res));
                }

                $sql = "SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,QUARTER(ENTRY_DT) as qtr from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' GROUP BY qtr";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'] - 1;
                    if ($qtr < 1) {
                        $qtr+= 3;
                    } 
                    else {
                        $qtr-= 1;
                    }

                    $totb[$qtr]+= $row['amt'];

                    $totm[$qtr]+= $row['amt'];
                }
                
               $center = "Total-excluding-Misc-(without-tax)";
                $k_1 = array_search($center, $brancharr);
                
                if ($offline_str != '') {
                    $center = "OFFLINE-REVENUE-WITHOUT-TAX";
                    $k = array_search($center, $brancharr);
                    $sql = "SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE, AMOUNT )) AS amt_net,QUARTER(a.ENTRY_DT) as qtr FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND a.STATUS $condition and a.BILLID IN ($offline_str) group by qtr";
                    $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                    while ($row = mysql_fetch_array($res)) {
                        $qtr = $row['qtr'] - 1;
                        if ($qtr < 1) {
                            $qtr+= 3;
                        } 
                        else {
                            $qtr-= 1;
                        }
                        $amt[$k][$qtr]+= round($row['amt'], 2);
                        $tota[$k]+= round($row['amt'], 2);
                         //$amt2a[$k][$qtr];
                        $totc[$qtr]+= round($row['amt'], 2);
                         //$amt1[$qtr][$k];
                        $totb[$qtr]+= round($row['amt_net'], 2);
                         //$amt1[$qtr][$k];
                        
                        // New data
                        $amt[$k_1][$qtr]+= round($row['amt'], 2);
                        $tota[$k_1]+= round($row['amt'], 2);
                    }
                }
                
                $center = "REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                $sql = "SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, QUARTER(a.ENTRY_DT) as qtr FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND a.STATUS $condition ";
                if ($offline_str != '') $sql.= "and b.BILLID NOT IN ($offline_str) ";
                $sql.= " group by qtr";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'] - 1;
                    if ($qtr < 1) {
                        $qtr+= 3;
                    } 
                    else {
                        $qtr-= 1;
                    }
                    $amt[$k][$qtr]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                     //$amt2a[$k][$qtr];
                    $totc[$qtr]+= round($row['amt'], 2);
                     //$amt1[$qtr][$k];
                    
                    // New data
                    $amt[$k_1][$qtr]+= round($row['amt'], 2);
                    $tota[$k_1]+= round($row['amt'], 2);
                }
                
                $center = "MISC-REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                
                $sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/(1+ b.TAX_RATE/100),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,QUARTER(a.ENTRY_DT) as qtr from billing.REV_PAYMENT as a, billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' and b.SALEID=a.SALEID GROUP BY qtr";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'] - 1;
                    if ($qtr < 1) {
                        $qtr+= 3;
                    } 
                    else {
                        $qtr-= 1;
                    }
                    $amt[$k][$qtr]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                     //$amt2a[$k][$qtr];
                    $totc[$qtr]+= round($row['amt'], 2);
                     //$amt1[$qtr][$k];
                    
                }
            } 
            elseif ($vtype == 'M') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $mflag = 1;
                $myearp1 = $myear + 1;
                $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,month(billing.$tableName.ENTRY_DT) as mm,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                if ($offline_str != '') $sql.= "and billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                $sql.= " group by  month(billing.$tableName.ENTRY_DT),center,eb ORDER BY center,eb";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $center = strtoupper($row['center']);
                    $k = array_search($center, $brancharr);
                    $mm = $row['mm'];
                    if ($mm <= 3) {
                        $mm+= 8;
                    } 
                    else {
                        $mm-= 4;
                    }
                    if ($center == 'HO') {
                        if ($row['eb'] == 'OFFLINE') {
                            $amt[$k][$mm]["rj"] = $row['amt'];
                            $tota[$k]["rj"]+= $row['amt'];
                             //$amt[$k][$mm]["rj"];
                            
                        } 
                        elseif ($row['eb'] == 'ONLINE') {
                            $amt[$k][$mm]["ol"] = $row['amt'];
                            $tota[$k]["ol"]+= $row['amt'];
                             //$amt[$k][$mm]["ol"];
                            
                        } 
                        elseif ($row['eb'] == 'ARAMEX') {
                            $amt[$k][$mm]["ar"] = $row['amt'];
                            $tota[$k]["ar"]+= $row['amt'];
                             //$amt[$k][$mm]["ar"];
                            
                        } 
                        elseif ($row['eb'] == 'BANK_TSFR') {
                            $amt[$k][$mm]["bt"] = $row['amt'];
                            $tota[$k]["bt"]+= $row['amt'];
                             //$amt[$k][$mm]["ar"];
                            
                        }
                        $amt1[$mm][$k] = $row['amt'];
                        $totb[$mm]+= $row['amt'];
                         //$amt1[$mm][$k];
                        
                    } 
                    else {
                        $amt[$k][$mm]+= $row['amt'];
                        $tota[$k]+= $row['amt'];
                         //$amt2a[$k][$mm];
                        $totb[$mm]+= $row['amt'];
                         //$amt1[$mm][$k];
                        
                    }
                }
                    
                $sql = "SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,MONTH(ENTRY_DT) as qtr from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' GROUP BY qtr";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'];
                    if ($qtr <= 3) {
                        $qtr+= 8;
                    } 
                    else {
                        $qtr-= 4;
                    }
                    
                    //$amt[$k][$qtr]+=$row['amt'];
                    //$tota[$k]+=$row['amt'];//$amt2a[$k][$qtr];
                    $totb[$qtr]+= $row['amt'];
                     //$amt1[$qtr][$k];
                    
                    // New Data
                    $totm[$qtr]+= $row['amt'];
                }

                $center = "Total-excluding-Misc-(without-tax)";
                $k_1 = array_search($center, $brancharr);
                
                if ($offline_str != '') {
                    $center = "OFFLINE-REVENUE-WITHOUT-TAX";
                    $k = array_search($center, $brancharr);
                    $sql = "SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE, AMOUNT )) AS amt_net, MONTH(a.ENTRY_DT) as qtr FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND a.STATUS $condition and b.BILLID IN ($offline_str) group by qtr";
                    $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                    while ($row = mysql_fetch_array($res)) {
                        $qtr = $row['qtr'];
                        if ($qtr <= 3) {
                            $qtr+= 8;
                        } 
                        else {
                            $qtr-= 4;
                        }
                        
                        $amt[$k][$qtr]+= round($row['amt'], 2);
                        $tota[$k]+= round($row['amt'], 2);
                         //$amt2a[$k][$qtr];
                        $totc[$qtr]+= round($row['amt'], 2);
                         //$amt1[$qtr][$k];
                        $totb[$qtr]+= round($row['amt_net'], 2);
                         //$amt1[$qtr][$k];
                        
                        // New data
                        $amt[$k_1][$qtr]+= round($row['amt'], 2);
                        $tota[$k_1]+= round($row['amt'], 2);
                    }
                }
                $center = "REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                $sql = "SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, MONTH(a.ENTRY_DT) as qtr FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND a.STATUS $condition";
                if ($offline_str != '') $sql.= " and b.BILLID NOT IN ($offline_str) ";
                $sql.= " group by qtr";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'];
                    if ($qtr <= 3) {
                        $qtr+= 8;
                    } 
                    else {
                        $qtr-= 4;
                    }
                    $amt[$k][$qtr]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                     //$amt2a[$k][$qtr];
                    $totc[$qtr]+= round($row['amt'], 2);
                     //$amt1[$qtr][$k];
                    
                    // New data
                    $amt[$k_1][$qtr]+= round($row['amt'], 2);
                    $tota[$k_1]+= round($row['amt'], 2);
                }
                
                $center = "MISC-REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                
                $sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/(1+ b.TAX_RATE/100),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,MONTH(a.ENTRY_DT) as qtr from billing.REV_PAYMENT as a,billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND b.SALEID=a.SALEID GROUP BY qtr";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $qtr = $row['qtr'];
                    if ($qtr <= 3) {
                        $qtr+= 8;
                    } 
                    else {
                        $qtr-= 4;
                    }
                    $amt[$k][$qtr]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                     //$amt2a[$k][$qtr];
                    $totc[$qtr]+= round($row['amt'], 2);
                     //$amt1[$qtr][$k];
                    
                }
            } 
            elseif ($vtype == 'D') {
                unset($amt);
                unset($amt1);
                unset($tota);
                unset($totb);
                $dflag = 1;
                
                for ($i = 0; $i < 31; $i++) {
                    $ddarr[$i] = $i + 1;
                }
                
                $sql = "SELECT sum(if(billing.$tableName.TYPE='DOL',billing.$tableName.AMOUNT*billing.$tableName.DOL_CONV_RATE,billing.$tableName.AMOUNT)) as amt,DAYOFMONTH(billing.$tableName.ENTRY_DT) as dd,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.$tableName,billing.PURCHASES WHERE billing.$tableName.STATUS $condition AND billing.$tableName.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND billing.PURCHASES.BILLID=billing.$tableName.BILLID ";
                if ($offline_str != '') $sql.= "AND billing.PURCHASES.BILLID NOT IN ($offline_str) ";
                $sql.= " GROUP BY DAYOFMONTH(billing.$tableName.ENTRY_DT),center,eb ORDER BY center,eb";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $center = strtoupper($row['center']);
                    $k = array_search($center, $brancharr);
                    $dd = $row['dd'] - 1;
                    if ($center == 'HO') {
                        if ($row['eb'] == 'OFFLINE') {
                            $amt[$k][$dd]["rj"] = $row['amt'];
                            $tota[$k]["rj"]+= $row['amt'];
                             //$amt[$k][$dd]["rj"];
                            
                        } 
                        elseif ($row['eb'] == 'ONLINE') {
                            $amt[$k][$dd]["ol"] = $row['amt'];
                            $tota[$k]["ol"]+= $row['amt'];
                             //$amt[$k][$dd]["ol"];
                            
                        } 
                        elseif ($row['eb'] == 'ARAMEX') {
                            $amt[$k][$dd]["ar"] = $row['amt'];
                            $tota[$k]["ar"]+= $row['amt'];
                             //$amt[$k][$dd]["ar"];
                            
                        } 
                        elseif ($row['eb'] == 'BANK_TSFR') {
                            $amt[$k][$dd]["bt"] = $row['amt'];
                            $tota[$k]["bt"]+= $row['amt'];
                             //$amt[$k][$dd]["ar"];
                            
                        }
                        $amt1[$dd][$k] = $row['amt'];
                        $totb[$dd]+= $row['amt'];
                         //$amt1[$dd][$k];
                        
                    } 
                    else {
                        $amt[$k][$dd]+= $row['amt'];
                        $amta[$k][$dd] = $row['amt'];
                        $amt1[$dd][$k] = $row['amt'];
                        $tota[$k]+= $row['amt'];
                         //$amta[$k][$dd];
                        $totb[$dd]+= $row['amt'];
                         //$amt1[$dd][$k];
                        
                    }
                }

                $sql = "SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,DAYOFMONTH(ENTRY_DT) as dd from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' GROUP BY dd";
                
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $dd = $row['dd'] - 1;
                    
                    //$amt[$k][$dd]+=$row['amt'];
                    //$amta[$k][$dd]=$row['amt'];
                    //$tota[$k]+=$row['amt'];//$amta[$k][$dd];
                    $totb[$dd]+= $row['amt'];
                     //$amt1[$dd][$k];
                    
                    // New data
                    $totm[$dd]+= $row['amt'];
                }

                $center = "Total-excluding-Misc-(without-tax)";
                $k_1 = array_search($center, $brancharr);
                
                if ($offline_str != '') {
                    $center = "OFFLINE-REVENUE-WITHOUT-TAX";
                    $k = array_search($center, $brancharr);
                    
                    $sql = "SELECT SUM(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, SUM(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE, AMOUNT)) AS amt_net, DAYOFMONTH(a.ENTRY_DT) as dd FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND a.STATUS $condition and b.BILLID IN ($offline_str) group by dd";
                    $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                    while ($row = mysql_fetch_array($res)) {
                        $dd = $row['dd'] - 1;
                        $amt[$k][$dd]+= round($row['amt'], 2);
                        $amta[$k][$dd] = round($row['amt'], 2);
                        $tota[$k]+= round($row['amt'], 2);

                        $totc[$dd]+= round($row['amt'], 2);

                        $totb[$dd]+= round($row['amt_net'], 2);

                        $amt[$k_1][$dd]+= round($row['amt'], 2);
                        $tota[$k_1]+= round($row['amt'], 2);
                    }
                }
                
                //Code Added by sriram on 6th June 2007 to show Revenue and Misc-revenue without tax daywise.
                $center = "REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                $sql = "SELECT SUM(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, DAYOFMONTH(a.ENTRY_DT) as dd FROM billing.$tableName a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND a.STATUS $condition ";
                if ($offline_str != '') $sql.= "and b.BILLID NOT IN ($offline_str) ";
                $sql.= " group by dd";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $dd = $row['dd'] - 1;
                    $amt[$k][$dd]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                    $totc[$dd]+= round($row['amt'], 2);
                    
                    // New data
                    $amt[$k_1][$dd]+= round($row['amt'], 2);
                    $tota[$k_1]+= round($row['amt'], 2);
                }
                
                $center = "MISC-REVENUE-WITHOUT-TAX";
                $k = array_search($center, $brancharr);
                $sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/(1+ b.TAX_RATE/100),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,DAYOFMONTH(a.ENTRY_DT) as dd from billing.REV_PAYMENT as a,billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND b.SALEID=a.SALEID GROUP BY dd";
                $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
                while ($row = mysql_fetch_array($res)) {
                    $dd = $row['dd'] - 1;
                    $amt[$k][$dd]+= round($row['amt'], 2);
                    $tota[$k]+= round($row['amt'], 2);
                    $totc[$dd]+= round($row['amt'], 2);
                }
               
                
            }
        }

       if ($JSIndicator == 1) {
            return;
        }
        
        /**************************************End of Addition*******************************************************************/
        $smarty->assign("brancharr", $brancharr);
        $smarty->assign("branch", $branch);
        $smarty->assign("amt", $amt);
        $smarty->assign("tot", $tot);
        $smarty->assign("tota", $tota);
        $smarty->assign("totb", $totb);
        $smarty->assign("totc", $totc);
        $smarty->assign("totm", $totm);
        $smarty->assign("tot1", $tot1);
        $smarty->assign("tot2", $tot2);
        $smarty->assign("ddarr", $ddarr);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("qtrarr", $qtrarr);
        $smarty->assign("flag", $flag);
        $smarty->assign("pflag", $pflag);
        $smarty->assign("qflag", $qflag);
        $smarty->assign("mflag", $mflag);
        $smarty->assign("dflag", $dflag);
        $smarty->assign("eflag", $eflag);
        $smarty->assign("bflag", $bflag);
        $smarty->assign("qyear", $qyear);
        $smarty->assign("qyearp1", $qyearp1);
        $smarty->assign("year", $year);
        $smarty->assign("yearp1", $yearp1);
        $smarty->assign("myear", $myear);
        $smarty->assign("myearp1", $myearp1);
        $smarty->assign("dyear", $dyear);
        $smarty->assign("dmonth", $dmonth);
        $smarty->assign("dmonthp1", $dmonthp1);
        $smarty->assign("mode", $mode);
        $smarty->assign("walkin", $walkin);
        $smarty->assign("userarr", $userarr);
        $smarty->assign("modearr", $modearr);
        
        $smarty->display("collectionmis.htm");
    } else if ($CMDGo && $viewmode == 'N') {
        $flag = 1;
        $mmarr = array('Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar');
        $qtrarr = array('Apr-Jun', 'Jul-Sep', 'Oct-Dec', 'Jan-Mar');
        
        if ($vtype == 'Q') {
            $yr1 = $qyear;
            $yr2 = $qyear + 1;
            $mnth1 = 04;
            $mnth2 = 03;
        } 
        elseif ($vtype == 'M') {
            $yr1 = $myear;
            $yr2 = $myear + 1;
            $mnth1 = 04;
            $mnth2 = 03;
        } 
        elseif ($vtype == 'D') {
            $yr1 = $yr2 = $dyear;
            $mnth1 = $mnth2 = $dmonth;
        }
        
        $sql_min = "SELECT MIN(BILLID) AS MIN,MAX(BILLID) AS MAX FROM billing.$tableName WHERE ENTRY_DT BETWEEN '$yr1-$mnth1-01 00:00:00' AND '$yr2-$mnth2-31 23:59:59'";
        $res_min = mysql_query_decide($sql_min, $db) or die("$sql_min" . mysql_error_js($db));
        $row_min = mysql_fetch_assoc($res_min);
        $min = $row_min['MIN'];
        $max = $row_min['MAX'];
        
        $offline_str = offline_online_sale($yr1, $yr2, $mnth1, $mnth2);
        $bflag = 'A';

        if ($vtype == 'Q') {
			unset($totalAmount);
			unset($totalDiscount);
			unset($totalCollFromSubSales);
			unset($totalSerTaxSubsSales);
			unset($totalCollFromSubsSalesAfterTax);
			unset($totalCommissions);
			unset($totalCollSubsSalesAfterTaxAndComm);
			unset($totalCollAdSales);
			unset($totalTaxAdSales);
			unset($totalCollAdSalesAfterSerTax);
			unset($finalTotal);
            $qflag = 1;
            $qyearp1 = $qyear + 1;

            // All Sales
            $sql = "SELECT sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE,0)) AS dol_amt,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT)) AS inr_amt,"
                    . " QUARTER(a.ENTRY_DT) AS qtr,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT/(1+TAX_RATE/100))) AS inr_tax,"
                    . " sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE/(1+TAX_RATE/100),0)) AS dol_tax,"
                    . " SUM(IF(TYPE='DOL',a.APPLE_COMMISSION*DOL_CONV_RATE,a.APPLE_COMMISSION)) AS apple,"
                    . " SUM(IF(TYPE='DOL',a.FRANCHISEE_COMMISSION*DOL_CONV_RATE,a.FRANCHISEE_COMMISSION)) AS franchisee,"
                    . " sum(IF(TYPE='DOL',b.DISCOUNT*DOL_CONV_RATE,b.DISCOUNT)) AS discount"
                    . " FROM billing.$tableName a, billing.PURCHASES b"
                    . " WHERE a.BILLID = b.BILLID"
                    . " AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00'"
                    . " AND '$qyearp1-03-31 23:59:59'"
                    . " AND a.STATUS $condition"
                    . " AND AMOUNT != '0'"      //condition added to remove 100% discount cases 
                    . " GROUP BY qtr";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $qtr = $row['qtr'] - 1;
                if ($qtr < 1) {
                    $qtr+= 3;
                } 
                else {
                    $qtr-= 1;
                }
                $row1 = round($row['dol_amt']+$row['inr_amt']+$row['discount'],2);
                $totalAmount[$qtr] = $row1;
                $totalAmount['total'] += $row1;
                $row2 = round($row['discount'],2);
                $totalDiscount[$qtr] = $row2;
                $totalDiscount['total'] += $row2;
                $row3 = round($row1 - $row2,2);
                $totalCollFromSubSales[$qtr] = $row3;
                $totalCollFromSubSales['total'] += $row3;
                //$row4 = round((billingVariables::NET_OFF_TAX_RATE)*$row['inr_amt'],2);
                $row4 = round($row['inr_amt']-$row['inr_tax'],2) + round($row['dol_amt']-$row['dol_tax'],2);
                $totalSerTaxSubsSales[$qtr] = $row4;
                $totalSerTaxSubsSales['total'] += $row4;
                $row5 = round($row3-$row4,2);
                $totalCollFromSubsSalesAfterTax[$qtr] = $row5;
                $totalCollFromSubsSalesAfterTax['total'] += $row5;
                $row6 = round(($row['apple']+$row['franchisee']),2);
                $totalCommissions[$qtr] = $row6;
                $totalCommissions['total'] += $row6;
                $row7 = $row5-$row6;
                $totalCollSubsSalesAfterTaxAndComm[$qtr] = $row7;
                $totalCollSubsSalesAfterTaxAndComm['total'] += $row7;
                $totalCollAdSales[$qtr] += 0;
                $totalCollAdSales['total'] += 0;
	            $totalTaxAdSales[$qtr] += 0;
	            $totalTaxAdSales['total'] += 0;
	            $totalCollAdSalesAfterSerTax[$qtr] += 0;
	            $totalCollAdSalesAfterSerTax['total'] += 0;
	            $finalTotal[$qtr] += $row7;
                $finalTotal['total'] += $row7;
            }
            
            // Misc Sales
            $sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE,a.AMOUNT)) as amt, QUARTER(a.ENTRY_DT) as qtr from billing.REV_PAYMENT as a, billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' and b.SALEID=a.SALEID GROUP BY qtr";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $qtr = $row['qtr'] - 1;
                if ($qtr < 1) {
                    $qtr+= 3;
                } 
                else {
                    $qtr-= 1;
                }

                $row8 = round($row['amt'],2);
                $totalCollAdSales[$qtr] += $row8;
                $totalCollAdSales['total'] += $row8;
                $row9 = round((billingVariables::NET_OFF_TAX_RATE)*$row8,2);
                $totalTaxAdSales[$qtr] += $row9;
                $totalTaxAdSales['total'] += $row9;
                $row10 = $row8-$row9;
                $totalCollAdSalesAfterSerTax[$qtr] += $row10;
                $totalCollAdSalesAfterSerTax['total'] += $row10;
                $finalTotal[$qtr] += $row10;
                $finalTotal['total'] += $row10;
            }
        }

        elseif ($vtype == 'M') {
            unset($totalAmount);
			unset($totalDiscount);
			unset($totalCollFromSubSales);
			unset($totalSerTaxSubsSales);
			unset($totalCollFromSubsSalesAfterTax);
			unset($totalCommissions);
			unset($totalCollSubsSalesAfterTaxAndComm);
			unset($totalCollAdSales);
			unset($totalTaxAdSales);
			unset($totalCollAdSalesAfterSerTax);
			unset($finalTotal);
            $mflag = 1;
            $myearp1 = $myear + 1;

            $sql = "SELECT sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE,0)) AS dol_amt,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT)) AS inr_amt,"
                    . " MONTH(a.ENTRY_DT) AS month,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT/(1+TAX_RATE/100))) AS inr_tax,"
                    . " sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE/(1+TAX_RATE/100),0)) AS dol_tax,"
                    . " SUM(IF(TYPE='DOL',a.APPLE_COMMISSION*DOL_CONV_RATE,a.APPLE_COMMISSION)) AS apple,"
                    . " SUM(IF(TYPE='DOL',a.FRANCHISEE_COMMISSION*DOL_CONV_RATE,a.FRANCHISEE_COMMISSION)) AS franchisee,"
                    . " sum(IF(TYPE='DOL',b.DISCOUNT*DOL_CONV_RATE,b.DISCOUNT)) AS discount"
                    . " FROM billing.$tableName a, billing.PURCHASES b"
                    . " WHERE a.BILLID = b.BILLID"
                    . " AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00'"
                    . " AND '$myearp1-03-31 23:59:59'"
                    . " AND AMOUNT != '0'"          //condition added to remove 100% discount cases 
                    . " AND a.STATUS $condition"
                    . " GROUP BY month";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $mm = $row['month'];
                if ($mm <= 3) {
                    $mm+= 8;
                } 
                else {
                    $mm-= 4;
                }
                $row1 = round($row['dol_amt']+$row['inr_amt']+$row['discount'],2);
                $totalAmount[$mm] = $row1;
                $totalAmount['total'] += $row1;
                $row2 = round($row['discount'],2);
                $totalDiscount[$mm] = $row2;
                $totalDiscount['total'] += $row2;
                $row3 = round($row1 - $row2,2);
                $totalCollFromSubSales[$mm] = $row3;
                $totalCollFromSubSales['total'] += $row3;
                //$row4 = round((billingVariables::NET_OFF_TAX_RATE)*$row3,2);
                $row4 = round($row['inr_amt']-$row['inr_tax'],2) + round($row['dol_amt'] - $row['dol_tax'],2);
                $totalSerTaxSubsSales[$mm] = $row4;
                $totalSerTaxSubsSales['total'] += $row4;
                $row5 = round($row3-$row4,2);
                $totalCollFromSubsSalesAfterTax[$mm] = $row5;
                $totalCollFromSubsSalesAfterTax['total'] += $row5;
                $row6 = round(($row['apple']+$row['franchisee']),2);
                $totalCommissions[$mm] = $row6;
                $totalCommissions['total'] += $row6;
                $row7 = $row5-$row6;
                $totalCollSubsSalesAfterTaxAndComm[$mm] = $row7;
                $totalCollSubsSalesAfterTaxAndComm['total'] += $row7;
                $totalCollAdSales[$mm] += 0;
                $totalCollAdSales['total'] += 0;
	            $totalTaxAdSales[$mm] += 0;
	            $totalTaxAdSales['total'] += 0;
	            $totalCollAdSalesAfterSerTax[$mm] += 0;
	            $totalCollAdSalesAfterSerTax['total'] += 0;
	            $finalTotal[$mm] += $row7;
                $finalTotal['total'] += $row7;
            }
            
            $sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE,a.AMOUNT)) as amt, MONTH(a.ENTRY_DT) as month from billing.REV_PAYMENT as a, billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' and b.SALEID=a.SALEID GROUP BY month";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $mm = $row['month'];
                if ($mm <= 3) {
                    $mm+= 8;
                } 
                else {
                    $mm-= 4;
                }

                $row8 = round($row['amt'],2);
                $totalCollAdSales[$mm] += $row8;
                $totalCollAdSales['total'] += $row8;
                $row9 = round((billingVariables::NET_OFF_TAX_RATE)*$row8,2);
                $totalTaxAdSales[$mm] += $row9;
                $totalTaxAdSales['total'] += $row9;
                $row10 = $row8-$row9;
                $totalCollAdSalesAfterSerTax[$mm] += $row10;
                $totalCollAdSalesAfterSerTax['total'] += $row10;
                $finalTotal[$mm] += $row10;
                $finalTotal['total'] += $row10;
            }
        } 
        elseif ($vtype == 'D') {
            unset($totalAmount);
			unset($totalDiscount);
			unset($totalCollFromSubSales);
			unset($totalSerTaxSubsSales);
			unset($totalCollFromSubsSalesAfterTax);
			unset($totalCommissions);
			unset($totalCollSubsSalesAfterTaxAndComm);
			unset($totalCollAdSales);
			unset($totalTaxAdSales);
			unset($totalCollAdSalesAfterSerTax);
			unset($finalTotal);
            $dflag = 1;
            
            for ($i = 0; $i < 31; $i++) {
                $ddarr[$i] = $i + 1;
            }
            
            $sql = "SELECT sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE,0)) AS dol_amt,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT)) AS inr_amt,"
                    . " DAYOFMONTH(a.ENTRY_DT) AS day,"
                    . " sum(IF(TYPE='DOL',0,AMOUNT/(1+TAX_RATE/100))) AS inr_tax,"
                    . " sum(IF(TYPE='DOL',AMOUNT*DOL_CONV_RATE/(1+TAX_RATE/100),0)) AS dol_tax,"
                    . " SUM(IF(TYPE='DOL',a.APPLE_COMMISSION*DOL_CONV_RATE,a.APPLE_COMMISSION)) AS apple,"
                    . " SUM(IF(TYPE='DOL',a.FRANCHISEE_COMMISSION*DOL_CONV_RATE,a.FRANCHISEE_COMMISSION)) AS franchisee,"
                    . " sum(IF(TYPE='DOL',b.DISCOUNT*DOL_CONV_RATE,b.DISCOUNT)) AS discount"
                    . " FROM billing.$tableName a, billing.PURCHASES b"
                    . " WHERE a.BILLID = b.BILLID"
                    . " AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00'"
                    . " AND '$dyear-$dmonth-31 23:59:59'"
                    . " AND a.STATUS $condition"
                    . " AND AMOUNT != '0'"          //condition added to remove 100% discount cases 
                    . " GROUP BY day";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $dd = $row['day'] - 1;

                $row1 = round($row['dol_amt']+$row['inr_amt']+$row['discount'],2);
                $totalAmount[$dd] = $row1;
                $totalAmount['total'] += $row1;
                $row2 = round($row['discount'],2);
                $totalDiscount[$dd] = $row2;
                $totalDiscount['total'] += $row2;
                $row3 = round($row1 - $row2,2);
                $totalCollFromSubSales[$dd] = $row3;
                $totalCollFromSubSales['total'] += $row3;
                //$row4 = round((billingVariables::NET_OFF_TAX_RATE)*$row3,2);
                $row4 = round($row['inr_amt']-$row['inr_tax'],2) + round($row['dol_amt'] - $row['dol_tax'], 2);
                $totalSerTaxSubsSales[$dd] = $row4;
                $totalSerTaxSubsSales['total'] += $row4;
                $row5 = round($row3-$row4,2);
                $totalCollFromSubsSalesAfterTax[$dd] = $row5;
                $totalCollFromSubsSalesAfterTax['total'] += $row5;
                $row6 = round(($row['apple']+$row['franchisee']),2);
                $totalCommissions[$dd] = $row6;
                $totalCommissions['total'] += $row6;
                $row7 = $row5-$row6;
                $totalCollSubsSalesAfterTaxAndComm[$dd] = $row7;
                $totalCollSubsSalesAfterTaxAndComm['total'] += $row7;
                $totalCollAdSales[$dd] += 0;
                $totalCollAdSales['total'] += 0;
	            $totalTaxAdSales[$dd] += 0;
	            $totalTaxAdSales['total'] += 0;
	            $totalCollAdSalesAfterSerTax[$dd] += 0;
	            $totalCollAdSalesAfterSerTax['total'] += 0;
	            $finalTotal[$dd] += $row7;
                $finalTotal['total'] += $row7;
            }
            
           	$sql = "SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE,a.AMOUNT)) as amt, DAYOFMONTH(a.ENTRY_DT) as day from billing.REV_PAYMENT as a, billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' and b.SALEID=a.SALEID GROUP BY day"; 
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            while ($row = mysql_fetch_array($res)) {
                $dd = $row['day'] - 1;

                $row8 = round($row['amt'],2);
                $totalCollAdSales[$dd] += $row8;
                $totalCollAdSales['total'] += $row8;
                $row9 = round((billingVariables::NET_OFF_TAX_RATE)*$row8,2);
                $totalTaxAdSales[$dd] += $row9;
                $totalTaxAdSales['total'] += $row9;
                $row10 = $row8-$row9;
                $totalCollAdSalesAfterSerTax[$dd] += $row10;
                $totalCollAdSalesAfterSerTax['total'] += $row10;
                $finalTotal[$dd] += $row10;
                $finalTotal['total'] += $row10;
            }

        }

        if ($JSIndicator == 1) {
            return;
        }
        
        $smarty->assign("brancharr", $brancharr);
        $smarty->assign("branch", $branch);
        $smarty->assign("amt", $amt);
        $smarty->assign("totalAmount", $totalAmount);
        $smarty->assign("totalDiscount", $totalDiscount);
        $smarty->assign("totalCollFromSubSales", $totalCollFromSubSales);
        $smarty->assign("totalSerTaxSubsSales", $totalSerTaxSubsSales);
        $smarty->assign("totalCollFromSubsSalesAfterTax", $totalCollFromSubsSalesAfterTax);
        $smarty->assign("totalCommissions", $totalCommissions);
        $smarty->assign("totalCollSubsSalesAfterTaxAndComm", $totalCollSubsSalesAfterTaxAndComm);
        $smarty->assign("totalCollAdSales", $totalCollAdSales);
        $smarty->assign("totalTaxAdSales", $totalTaxAdSales);
        $smarty->assign("totalCollAdSalesAfterSerTax", $totalCollAdSalesAfterSerTax);
        $smarty->assign("finalTotal", $finalTotal);
        $smarty->assign("ddarr", $ddarr);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("qtrarr", $qtrarr);
        $smarty->assign("flag", $flag);
        $smarty->assign("pflag", $pflag);
        $smarty->assign("qflag", $qflag);
        $smarty->assign("mflag", $mflag);
        $smarty->assign("dflag", $dflag);
        $smarty->assign("eflag", $eflag);
        $smarty->assign("bflag", $bflag);
        $smarty->assign("qyear", $qyear);
        $smarty->assign("qyearp1", $qyearp1);
        $smarty->assign("year", $year);
        $smarty->assign("yearp1", $yearp1);
        $smarty->assign("myear", $myear);
        $smarty->assign("myearp1", $myearp1);
        $smarty->assign("dyear", $dyear);
        $smarty->assign("dmonth", $dmonth);
        $smarty->assign("dmonthp1", $dmonthp1);
        $smarty->assign("mode", $mode);
        $smarty->assign("walkin", $walkin);
        $smarty->assign("userarr", $userarr);
        $smarty->assign("modearr", $modearr);
        $smarty->assign("viewmode", $viewmode);
        $smarty->display("collectionmis.htm");
    }
    else {
        $user = getname($checksum);
        for ($i = 0; $i < 12; $i++) {
            $mmarr[$i] = $i + 1;
        }
        for ($i = 2004; $i <= date("Y"); $i++) {
            $yyarr[$i - 2004] = $i;
        }
        $privilage = getprivilage($checksum);
        $priv = explode("+", $privilage);
        if (in_array('MA', $priv) || in_array('MB', $priv)) {
            $smarty->assign("VIEWALL", "Y");
            
            //run query : select all branches
            $sql = "SELECT * FROM billing.BRANCHES";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            if ($row = mysql_fetch_array($res)) {
                $i = 0;
                do {
                    $brancharr[$i]["id"] = $row['ID'];
                    $brancharr[$i]["name"] = $row['NAME'];
                    
                    $i++;
                } while ($row = mysql_fetch_array($res));
            }
            
            $smarty->assign("brancharr", $brancharr);
        } 
        else {
            
            // run query : select branch of user
            $sql = "SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
            $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js($db));
            if ($row = mysql_fetch_array($res)) {
                $branch = strtoupper($row['CENTER']);
            }
            
            $smarty->assign("ONLYBRANCH", "Y");
            $smarty->assign("branch", $branch);
        }
        
        $smarty->assign("priv", $priv);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("yyarr", $yyarr);
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->display("collectionmis.htm");
    }
} 
else {
    $smarty->assign("user", $username);
    $smarty->display("jsconnectError.tpl");
}
?>
