<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/display_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_functions.php");

/*  Variable defined
 * $MGR: set when managed logged in and viewing the report
 * $role: role of the logged in person
 * $name: username of the logged in person      
 * $outside :variable set when viewed report through Jump link
*/

$db = connect_misdb();
$db2 = connect_master();

$data = authenticated($cid);
$name =getname($cid);
$role ='TC';
//$MGR='1';

// Check added when mis viewed through Jump       
if($outside=='Y'){
        $submit ='1';
        $day_month_wise ='D';
        $dateArray =explode("-",date("Y-m-d"));
        $month =$dateArray[1];
        $year_d =$dateArray[0];
}

if($data)
{
	if($submit)
	{
		if(!$j)
        		$j=1;
		$PAGELEN=12;
		$pagination=1;

                // Manager logged in case check
                if($MGR){
                        if(is_array($namesArr)){
                                $nameStr =implode("','",$namesArr);
                                $nameStr ="'".$nameStr."'";
                        }
			else{
                                $namesArr=getMng_EmployeeNames($name,$role);
                                $nameStr =implode("','",$namesArr);
                                $nameStr ="'".$nameStr."'";
			}
                }
                else
                        $nameStr ="'".$name."'";
                // Ends Manager logged in

		if($day_month_wise=="D")
		{
			
			if($start_dt=='' && $end_dt==''){
				$start_dt = $year_d."-".$month."-01";
				$end_dt = $year_d."-".$month."-31";
			}			
			$smarty->assign("head_label","Details for the duration $start_dt to $end_dt");
		}
                elseif($day_month_wise=="M")
                {
			if($start_dt=='' && $end_dt==''){	
                        	$start_dt = $year_m."-01-01";
                        	$end_dt = $year_m."-12-31";
			}
			$smarty->assign("head_label","Details for the duration $start_dt to $end_dt");
		}
                elseif($day_month_wise=="R")
                {
			if($start_dt=='' && $end_dt==''){
                        	$start_dt = $year_r1."-".$month_r1."-".$day_r1;
                        	$end_dt = $year_r2."-".$month_r2."-".$day_r2;
			}
			$smarty->assign("head_label","Details for the duration $start_dt to $end_dt");	
		}

		$count = getTBC_count($start_dt,$end_dt,$nameStr);
		//$count =20;
		if($count){
                	$profile_start=($j)*$PAGELEN-$PAGELEN;
                	if($profile_start+11<$count)
                	        $profile_end=$profile_start+11;
                	else
                	        $profile_end=$count;
		}else
			$profile_start=1;
			
		$sql_call = "SELECT MATCH_ID, CALL_DATE, TELECALLER FROM Assisted_Product.AP_CALL_HISTORY WHERE `CALL_DATE` BETWEEN '$start_dt' AND '$end_dt' AND CALL_STATUS='Y' AND TELECALLER IN($nameStr)";
		if($pagination)
			$sql_call .=" LIMIT $profile_start,$PAGELEN";	
		$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
		$i=0;
		$usernameArr=array();	
		$dateArr=array();
		$timeArr =array();
		$telecaller =array();
		while($row_call = mysql_fetch_array($res_call))
		{
			$call_date[$i] = $row_call['CALL_DATE'];
			$match_id[$i] = $row_call['MATCH_ID'];
			$telecaller[$i]= $row_call['TELECALLER'];
			$i++;
		}
		$username = getUsername($match_id);
		for($k=0;$k<count($match_id);$k++)
		{
			$matchid	=$match_id[$k];
			$usernameArr[] 	=$username[$matchid];	
			$dateTimeArr 	=datetime_format1($call_date[$k]);
			$dateArr[]	=$dateTimeArr[0];
			$timeArr[]	=$dateTimeArr[1];
			$telecallerArr[]=$telecaller[$k];
		}

		pagination($j,$count,$PAGELEN,"");
       		$curPage="ap_mis_profile_tc.php?cid=$cid&start_dt=$start_dt&end_dt=$end_dt&day_month_wise=$day_month_wise&submit=1&MGR=$MGR";
       		$smarty->assign("CUR_PAGE",$curPage);
		$smarty->assign("pagination",$pagination);

		$smarty->assign("RESULT",1);
		$smarty->assign("usernameArr",$usernameArr);
		$smarty->assign("dateArr",$dateArr);
		$smarty->assign("timeArr",$timeArr);
		$smarty->assign("telecallerArr",$telecallerArr);
	}
	else
	{
                // Condition before submit state

                // Manager logged in case check
                if($MGR){
                        $namesArr=getMng_EmployeeNames($name,$role);
                        $smarty->assign("namesArr",$namesArr);
                        $smarty->assign("MGR",$MGR);
                }
                // Ends Manager logged in case

		$date = date("Y-m");
		list($curyear,$curmonth) = explode("-",$date);

                for($i=1;$i<=31;$i++)
                        $ddarr[] = $i;

		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;

		for($i=2007;$i<=$curyear+2;$i++)
			$yyarr[] = $i;

		$smarty->assign("top_label","Month");
		$smarty->assign("curyear",$curyear);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->display("ap_mis_profile_tc.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

function getTBC_count($start_dt, $end_dt, $nameStr)
{
	$sql_call = "SELECT count(MATCH_ID) AS COUNT FROM Assisted_Product.AP_CALL_HISTORY WHERE `CALL_DATE` BETWEEN '$start_dt' AND '$end_dt' AND CALL_STATUS='Y' AND TELECALLER IN($nameStr)";             
	$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
        $row_call = mysql_fetch_array($res_call);
       	$count_tot= $row_call['COUNT'];
	return $count_tot;
}

function getUsername($profileidArr)
{
	if(!is_array($profileidArr))
		return array();
	$profileidStr =implode(",",$profileidArr);
	$sql_jprofile ="SELECT `USERNAME`,`PROFILEID` FROM newjs.JPROFILE where PROFILEID IN($profileidStr)";
	$res_jprofile = mysql_query_decide($sql_jprofile) or die($sql_jprofile.mysql_error_js());
	$i=0;
	while($row = mysql_fetch_array($res_jprofile))
	{
		$profileid = $row['PROFILEID'];
	        $usernameArr[$profileid] = $row['USERNAME'];
	}
	return $usernameArr;
}

// function manipulates the datetime format ,return array(0=>date,1=>time)
function datetime_format1($dateTime)
{
        $dateTimeArr    =array();
        $dateTime       =trim($dateTime);
        $arr = explode(" ",$dateTime);
        $date =$arr['0'];
        if($date){
                $dateArr        =explode("-",$date);
                $dateTimestamp  = mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr["0"]);
                $date           = date("d/ m/ Y",$dateTimestamp);
        }
        $time =$arr['1'];
        if($time){
                $timeArr        =explode(":",$time);
                $Timestamp      = mktime($timeArr[0],$timeArr[1],0);
                $time           = date("g.i A",$Timestamp);
        }
  
        $dateTimeArr    = array("$date","$time");
        return $dateTimeArr;
}


?>
