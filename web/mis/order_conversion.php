<?php

/*********************************************************************************************
 * FILE NAME     : order_conversion.php
 * DESCRIPTION   : It provides MIS details for online member conversions.
 * CREATION DATE : 4 july, 2005
 * CREATED BY    : Aman Sharma
 * Copyright  2005, InfoEdge India Pvt. Ltd.
 *********************************************************************************************/

include ("connect.inc");

$db = connect_misdb();
@mysql_select_db("billing", $db);

$flag = 0;

if (authenticated($checksum)) {
    if ($outside) {
        $CMDGo = 'Y';
        $vtype = 'D';
        $channel = "Cur";
        $today = date("Y-m-d");
        list($yr1, $mm, $d) = explode("-", $today);
    }

    $eligibleSourceArr = array('Desktop'=>'desktop','New Mobile Website'=>'mobile_website','Web View Android App'=>'JSAA_mobile_website','Legacy Android App'=>'Android_app','Old Mobile Website'=>'old_mobile_website','iOS App'=>'iOS_app');
    
    if ($CMDGo && $channel == "Cur") {
        if ($vtype == 'D') {
            for ($i = 0; $i < 31; $i++) {
                $mmarr[$i] = $i + 1;
            }
            
            $st_date = $yr1 . "-" . $mm . "-01 00:00:00";
            $end_date = $yr1 . "-" . $mm . "-31 23:59:59";
            $sel_type = 'Day';
            $sql1 = "min(DAYOFMONTH(t1.ENTRY_DT))";
            $num = 31;
            $smarty->assign("mm", $mm);
            $smarty->assign("yr1", $yr1);
            $smarty->assign("dt", $mmarr[$mm - 1] . "-" . $yr1);
        } 
        elseif ($vtype == 'M') {
            $mmarr = array(
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            );
            
            $st_date = $yr . "-01-01 00:00:00";
            $end_date = $yr . "-12-31 23:59:59";
            $sel_type = 'MONTH';
            $sql1 = "MONTH(t1.ENTRY_DT)";
            $num = 12;
            $smarty->assign("dt", $yr);
        }
        
        $sql = "select ";
        if ($vtype == 'M') {
            $sql.= "distinct ";
        }
        $sql.= "t2.PROFILEID as cnt,$sql1 as dd,t1.CURTYPE from ORDERS as t1,PURCHASES as t2 where t1.PROFILEID=t2.PROFILEID and t2.STATUS='DONE' and t2.ENTRY_DT >= t1.ENTRY_DT and t1.ENTRY_DT BETWEEN '$st_date' and  '$end_date' and PAYMODE like '%card%'";
        if ($vtype == 'D') {
            $sql.= " group by t2.PROFILEID,t1.CURTYPE";
        }

        $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js());
        while ($row = mysql_fetch_array($res)) {
            $cnt1 = 1;
            $curtype = $row['CURTYPE'];
            if ($curtype == 'DOL') $j = 0;
            else $j = 1;
            
            $dd = $row['dd'] - 1;
            
            $con_arr[$dd]+= $cnt1;
            $con_arr_cur[$dd][$j]+= $cnt1;
            $total_con_cur[$j]+= $cnt1;
            $total_con+= $cnt1;
        }
        
        $sql = "select ";
        if ($vtype == 'M') {
            $sql.= "distinct ";
        }
        $sql.= " PROFILEID AS cnt,$sql1 as dd,CURTYPE from ORDERS t1 where ENTRY_DT BETWEEN '$st_date'  and '$end_date' and PAYMODE like '%card%' ";
        if ($vtype == 'D') {
            $sql.= " group by PROFILEID,CURTYPE";
        }

        $res = mysql_query_decide($sql, $db) or die(mysql_error_js());
        while ($row = mysql_fetch_array($res)) {
            $cnt1 = 1;
            $curtype = $row['CURTYPE'];
            if ($curtype == 'DOL') $j = 0;
            else $j = 1;
            
            $dd = $row['dd'] - 1;
            
            $tot_arr[$dd]+= $cnt1;
            $tot_arr_cur[$dd][$j]+= $cnt1;
            $total_cur[$j]+= $cnt1;
            $total+= $cnt1;
        }
        
        for ($i = 0; $i < $num; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $cnt_cur[$i][$j]["C"] = $con_arr_cur[$i][$j];
                $cnt_cur[$i][$j]["N"] = $tot_arr_cur[$i][$j] - $con_arr_cur[$i][$j];
                $cnt_cur[$i][$j]["T"] = $tot_arr_cur[$i][$j];
                if ($tot_arr_cur[$i][$j] > 0) $cnt_cur[$i][$j]["P"] = round(($con_arr_cur[$i][$j] / $tot_arr_cur[$i][$j] * 100) , 2) . "%";
            }
            $cnt[$i]["C"] = $con_arr[$i];
            $cnt[$i]["N"] = $tot_arr[$i] - $con_arr[$i];
            $cnt[$i]["T"] = $tot_arr[$i];
            if ($tot_arr[$i] > 0) $cnt[$i]["P"] = round(($con_arr[$i] / $tot_arr[$i] * 100) , 2) . "%";
        }
        
        for ($i = 0; $i < 2; $i++) {
            if ($total_cur[$i]) {
                $tot_not_con_cur[$i] = $total_cur[$i] - $total_con_cur[$i];
                $totp_cur[$i] = round(($total_con_cur[$i] / $total_cur[$i] * 100) , 2) . "%";
            }
        }
        
        if ($total) {
            $tot_not_con = $total - $total_con;
            $totp = round(($total_con / $total * 100) , 2) . "%";
        }

        $smarty->assign("totn", $tot_not_con);
        $smarty->assign("totn_cur", $tot_not_con_cur);
        $smarty->assign("sel_type", $sel_type);
        $smarty->assign("cnt_cur", $cnt_cur);
        $smarty->assign("tota_cur", $total_con_cur);
        $smarty->assign("totb_cur", $total_cur);
        $smarty->assign("totp_cur", $totp_cur);
        $smarty->assign("cnt", $cnt);
        $smarty->assign("tota", $total_con);
        $smarty->assign("totb", $total);
        $smarty->assign("totp", $totp);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("flag", "1");
        $smarty->assign("cid", $checksum);
        $smarty->display("order_conversion.htm");
    } else if ($CMDGo && $channel == "Chn") {
    	if ($vtype == 'D') {
            for ($i = 0; $i < 31; $i++) {
                $mmarr[$i] = $i + 1;
            }
            
            $st_date = $yr1 . "-" . $mm . "-01 00:00:00";
            $end_date = $yr1 . "-" . $mm . "-31 23:59:59";
            $sel_type = 'Day';
            $sql1 = "min(DAYOFMONTH(t1.ENTRY_DT))";
            $num = 31;
            $smarty->assign("mm", $mm);
            $smarty->assign("yr1", $yr1);
            $smarty->assign("dt", $mmarr[$mm - 1] . "-" . $yr1);
        } 
        elseif ($vtype == 'M') {
            $mmarr = array(
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            );
            
            $st_date = $yr . "-01-01 00:00:00";
            $end_date = $yr . "-12-31 23:59:59";
            $sel_type = 'MONTH';
            $sql1 = "MONTH(t1.ENTRY_DT)";
            $num = 12;
            $smarty->assign("dt", $yr);
        }
        
        $sql = "select ";
        if ($vtype == 'M') {
            $sql.= "distinct ";
        }
        $sql.= "t2.PROFILEID as cnt,$sql1 as dd,t1.CURTYPE, t3.SOURCE from ORDERS as t1,PURCHASES as t2,ORDERS_DEVICE as t3 where t1.PROFILEID=t2.PROFILEID and t2.STATUS='DONE' and t2.ENTRY_DT >= t1.ENTRY_DT and t1.ENTRY_DT BETWEEN '$st_date' and  '$end_date' and PAYMODE like '%card%' and t1.ID=t3.ID";
        if ($vtype == 'D') {
            $sql.= " group by t2.PROFILEID,t1.CURTYPE";
        }

        $res = mysql_query_decide($sql, $db) or die("$sql" . mysql_error_js());
        while ($row = mysql_fetch_array($res)) {
            $cnt1 = 1;
            $curtype = $row['CURTYPE'];
            $source = $row['SOURCE'];
            if ($curtype == 'DOL') $j = 0;
            else $j = 1;
            
            $dd = $row['dd'] - 1;
            if (in_array($source, $eligibleSourceArr)) {
	            $con_arr[$source][$dd] += $cnt1;
	            $con_arr_cur[$source][$dd][$j] += $cnt1;
	            $total_con_cur[$source][$j] += $cnt1;
	            $total_con[$source] += $cnt1;
	        }
        }
        
        $sql = "select ";
        if ($vtype == 'M') {
            $sql.= "distinct ";
        }
        $sql.= " t1.PROFILEID AS cnt,$sql1 as dd,t1.CURTYPE, t2.SOURCE from ORDERS as t1,ORDERS_DEVICE as t2 where t1.ENTRY_DT BETWEEN '$st_date'  and '$end_date' and t1.PAYMODE like '%card%' and t1.ID=t2.ID";
        if ($vtype == 'D') {
            $sql.= " group by t1.PROFILEID,t1.CURTYPE";
        }

        $res = mysql_query_decide($sql, $db) or die(mysql_error_js());
        while ($row = mysql_fetch_array($res)) {
            $cnt1 = 1;
            $curtype = $row['CURTYPE'];
            $source = $row['SOURCE'];
            if ($curtype == 'DOL') $j = 0;
            else $j = 1;
            
            $dd = $row['dd'] - 1;
            if (in_array($source, $eligibleSourceArr)) {
	            $tot_arr[$source][$dd] += $cnt1;
	            $tot_arr_cur[$source][$dd][$j] += $cnt1;
	            $total_cur[$source][$j] += $cnt1;
	            $total[$source] += $cnt1;
	        }
        }
        
        for ($i = 0; $i < $num; $i++) {
            for ($j = 0; $j < 2; $j++) {
            	foreach ($eligibleSourceArr as $key => $source) {
            		$cnt_cur[$source][$i][$j]["C"] = $con_arr_cur[$source][$i][$j];
	                $cnt_cur[$source][$i][$j]["N"] = $tot_arr_cur[$source][$i][$j] - $con_arr_cur[$source][$i][$j];
	                $cnt_cur[$source][$i][$j]["T"] = $tot_arr_cur[$source][$i][$j];
	                if ($tot_arr_cur[$source][$i][$j] > 0) {
	                	$cnt_cur[$source][$i][$j]["P"] = round(($con_arr_cur[$source][$i][$j] / $tot_arr_cur[$source][$i][$j] * 100) , 2) . "%";
	                }
            	}
            }
            foreach ($eligibleSourceArr as $key => $source) {
	            $cnt[$source][$i]["C"] = $con_arr[$source][$i];
	            $cnt[$source][$i]["N"] = $tot_arr[$source][$i] - $con_arr[$source][$i];
	            $cnt[$source][$i]["T"] = $tot_arr[$source][$i];
	            if ($tot_arr[$source][$i] > 0) {
	            	$cnt[$source][$i]["P"] = round(($con_arr[$source][$i] / $tot_arr[$source][$i] * 100) , 2) . "%";
	            }
        	}
        }
        
        for ($i = 0; $i < 2; $i++) {
        	foreach ($eligibleSourceArr as $key => $source) {
	            if ($total_cur[$source][$i]) {
	                $tot_not_con_cur[$source][$i] = $total_cur[$source][$i] - $total_con_cur[$source][$i];
	                $totp_cur[$source][$i] = round(($total_con_cur[$source][$i] / $total_cur[$source][$i] * 100) , 2) . "%";
	            }
        	}
        }
        
        foreach($total as $key => $value) {
        	if ($total[$key]) {
            	$tot_not_con[$key] = $total[$key] - $total_con[$key];
            	$totp[$key] = round(($total_con[$key] / $total[$key] * 100) , 2) . "%";
        	}
        }
        
        $smarty->assign("totn", $tot_not_con);
        $smarty->assign("source_arr", $eligibleSourceArr);
        $smarty->assign("totn_cur", $tot_not_con_cur);
        $smarty->assign("sel_type", $sel_type);
        $smarty->assign("cnt_cur", $cnt_cur);
        $smarty->assign("tota_cur", $total_con_cur);
        $smarty->assign("totb_cur", $total_cur);
        $smarty->assign("totp_cur", $totp_cur);
        $smarty->assign("cnt", $cnt);
        $smarty->assign("tota", $total_con);
        $smarty->assign("totb", $total);
        $smarty->assign("totp", $totp);
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("flag", "1");
        $smarty->assign("cid", $checksum);
        $smarty->display("order_conversion.htm");
    }
    else {
        for ($i = 0; $i < 12; $i++) {
            $mmarr[$i] = $i + 1;
        }
        
        for ($i = 2004; $i <= date("Y"); $i++) {
            $yyarr[$i - 2004] = $i;
        }
        $smarty->assign("mmarr", $mmarr);
        $smarty->assign("flag", "0");
        $smarty->assign("yyarr", $yyarr);
        $smarty->assign("cid", $checksum);
        $smarty->display("order_conversion.htm");
    }
} 
else {
    $smarty->display("jsconnectError.tpl");
}
?>
