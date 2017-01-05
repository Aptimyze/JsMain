<?php
include("/home/developer/jsdialer/MysqlDbConstants.class.php");

//Sent mail for daily tracking
$msg="\nPopulate Pool # Start Time=".date("Y-m-d H:i:s");
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Scoring Algorithm Pool Set";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
ini_set('memory_limit', '300M');

//DB Connection
//$myDb = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
$myDb = mysql_connect(MysqlDbConstants::$slave111_sel['HOST'],MysqlDbConstants::$slave111_sel['USER'],MysqlDbConstants::$slave111_sel['PASS']) or die("Unable to connect to js server".$start);

// Truncate Table
$sqlTrunc ="Truncate table test.ANALYTIC_SCORE_POOL";
mysql_query($sqlTrunc,$myDb) or die($sqlTrunc.mysql_error($myDb));

//Pool set of last 15 days logins
$pro_arrLogin =array();
$last_15day = date("Y-m-d",time()-15*86400);
$sql = "SELECT DISTINCT(PROFILEID) FROM MIS.LOGIN_TRACKING WHERE DATE>='$last_15day'";
$res = mysql_query($sql,$myDb) or die($sql.mysql_error($myDb));
while($row = mysql_fetch_array($res))
	$pro_arrLogin[] = $row['PROFILEID'];

//Pool set of last 7 days registered profiles
$arr_reg = array();
$todayDate 	=date("Y-m-d H:i:s");
$startDt 	=date("Y-m-d H:i:s",strtotime("$todayDate -7 days"));
$sqlReg ="SELECT PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT>='$startDt' AND SUBSCRIPTION='' AND ACTIVATED='Y' AND activatedKey=1";
$resReg = mysql_query($sqlReg,$myDb) or die($sqlReg.mysql_error($myDb));
while($rowReg =mysql_fetch_array($resReg))
	$arr_reg[] = $rowReg['PROFILEID'];

//Merged both pools
if(is_array($arr_reg))
        $pro_arrLogin = array_merge($pro_arrLogin,$arr_reg);
unset($arr_reg);
$pro_arrLogin = array_unique($pro_arrLogin);

//Bifurcation depending upon the profile type
$everPaidArr =array();
$pro_arrLogin_str = implode(",",$pro_arrLogin);
if($pro_arrLogin_str){
	$sql1 = "SELECT DISTINCT(PROFILEID) FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN ($pro_arrLogin_str) AND STATUS='DONE'";
	$res1 = mysql_query($sql1,$myDb) or die($sql1.mysql_error($myDb));
	while($row1 = mysql_fetch_array($res1))
		$everPaidArr[] = $row1['PROFILEID'];
}
$neverPaidArr = array();
$neverPaidArr = array_diff($pro_arrLogin,$everPaidArr);
unset($pro_arrLogin);
$currently_paid =array();
$renewal_arr =array();
$paidNotInRenewalArr =array();
$cdate = date("Y-m-d");
$cdateminus10 = date("Y-m-d",time()-10*86400);
$sqlcp = "SELECT MAX(EXPIRY_DT) as EDATE,PROFILEID FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND ACTIVE<>'N' AND EXPIRY_DT>='$cdateminus10' GROUP BY PROFILEID";
$rescp = mysql_query($sqlcp,$myDb) or die($sqlcp.mysql_error($myDb));
while($rowcp = mysql_fetch_array($rescp))
{
	$proid = $rowcp['PROFILEID'];
	$expiryDate = $rowcp['EDATE'];
	$currently_paid[] = $proid;
	$eminus30 = date("Y-m-d",strtotime($expiryDate)-30*86400);
        $eminus40 = date("Y-m-d",strtotime($expiryDate)-40*86400);
        if($cdate>$eminus40 && $cdate<=$eminus30)
	        $renewal_arr[] = $proid;
        if($cdate<=$eminus40)
        	$paidNotInRenewalArr[] = $proid;
}
$everPaidArr =array_diff($everPaidArr,$currently_paid);
unset($currently_paid);

// reset array index values
$neverPaidArr_1 =array();
$everPaidArr_1 =array();
$renewal_arr_1 =array();
$paidNotInRenewalArr_1 =array();
$neverPaidArr_1 =array_values($neverPaidArr);
unset($neverPaidArr);
$everPaidArr_1 =array_values($everPaidArr);
unset($everPaidArr);
$renewal_arr_1 =array_values($renewal_arr);
unset($renewal_arr);
$paidNotInRenewalArr_1 =array_values($paidNotInRenewalArr);
unset($paidNotInRenewalArr);

//Model wise array
$modelType_arr = array("P","R","E","N");
$today_arr = array("N"=>$neverPaidArr_1,"E"=>$everPaidArr_1,"R"=>$renewal_arr_1,"P"=>$paidNotInRenewalArr_1);

for($t=0;$t<count($modelType_arr);$t++)
{
        $modelType = $modelType_arr[$t];
        $modelArr = $today_arr[$modelType];
        if(count($modelArr)>0)
        {
                //Populate pool model wise
                $tot_modelArr =count($modelArr);
                for($j=0;$j<$tot_modelArr;$j++)
                {
                        $profileid = $modelArr[$j];
                        if($profileid)
			{
				$sql_up = "INSERT INTO test.ANALYTIC_SCORE_POOL (PROFILEID,MODEL) VALUES ('".$profileid."', '".$modelType."')";
				mysql_query($sql_up,$myDb) or die($sql_up.mysql_error($myDb)); 
			}
		}
	}
}

//Sent mail for daily tracking
$msg="\n Populate Pool # End Time=".date("Y-m-d H:i:s");
mail($to,$sub,$msg,$from);

?>
