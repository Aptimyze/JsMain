<?php
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include ("$docRoot/crontabs/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Scoring100_ab.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/ScoringGlobalParams_ab.class.php");

$activeServerId=0;
//Sent mail for daily tracking
$msg="\nShard $activeServerId # Start Time=".date("Y-m-d H:i:s");
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Scoring Algorithm A/B:0to100";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
ini_set('memory_limit', '300M');

$mysqlObj=new Mysql;
$parameter = "GENDER,MTONGUE,CITY_RES,ENTRY_DT,SHOW_HOROSCOPE,AGE,INCOME,SOURCE,CASTE,OCCUPATION,MOB_STATUS,LANDL_STATUS,EDU_LEVEL,MSTATUS,GET_SMS,RELIGION,EDU_LEVEL_NEW,VERIFY_EMAIL,HEIGHT,TIME_TO_CALL_START,TIME_TO_CALL_END,HAVE_CAR,OWN_HOUSE,FAMILY_STATUS,SHOWADDRESS,WORK_STATUS,DTOFBIRTH,LAST_LOGIN_DT";

$count=0;

//Slave Connection
//$myDb=connect_737();
$myDb=connect_slave111();
mysql_query('set session wait_timeout=50000',$myDb);

//Shard Connection
$shDbName=getActiveServerName($activeServerId,"slave112");
$shDb=$mysqlObj->connect($shDbName);
mysql_query('set session wait_timeout=50000',$shDb);

//Pool set of today
$pro_arrLogin =array();
$last_15day = date("Y-m-d",time()-15*86400);
$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE LOGIN_DT>='$last_15day' AND PROFILEID%9=0";
$res = mysql_query_decide($sql,$shDb) or die($sql.mysql_error($shDb));
while($row = mysql_fetch_array($res))
	$pro_arrLogin[] = $row['PROFILEID'];

// get pool set for registered profiles
$arr_reg = array();
$todayDate 	=date("Y-m-d H:i:s");
$startDt 	=date("Y-m-d H:i:s",strtotime("$todayDate -7 days"));
$sqlReg ="SELECT PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT>='$startDt' AND SUBSCRIPTION='' AND ACTIVATED='Y' AND activatedKey=1 AND PROFILEID%3=$activeServerId";
$resReg = mysql_query_decide($sqlReg,$myDb) or die($sqlReg.mysql_error($myDb));
while($rowReg =mysql_fetch_array($resReg))
	$arr_reg[] = $rowReg['PROFILEID'];

if(is_array($arr_reg))
        $pro_arrLogin = array_merge($pro_arrLogin,$arr_reg);
unset($arr_reg);

//Bifurcation depending upon the profile type
$repeat_arr =array();
$pro_arrLogin_str = implode(",",$pro_arrLogin);
if($pro_arrLogin_str){
	$sql1 = "SELECT distinct PROFILEID FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN ($pro_arrLogin_str) AND STATUS='DONE'";
	$res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
	while($row1 = mysql_fetch_array($res1))
		$repeat_arr[] = $row1['PROFILEID'];
}
$first_arr = array();
$first_arr = array_diff($pro_arrLogin,$repeat_arr);
unset($pro_arrLogin);

//Bifurcated data set with profile type
$first_arr1 =array();
$first_arr1 =array_unique($first_arr);
unset($first_arr);
$repeat_arr1 =array();
$repeat_arr1 =array_unique($repeat_arr);
unset($repeat_arr);

$currently_paid =array();
$renewal_arr =array();
$mark_zero_arr =array();
$cdate = date("Y-m-d");
$cdateminus10 = date("Y-m-d",time()-10*86400);
$sqlcp = "SELECT MAX(EXPIRY_DT) as EDATE,PROFILEID FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND ACTIVE<>'N' AND EXPIRY_DT>='$cdateminus10' AND PROFILEID%3='$activeServerId' GROUP BY PROFILEID";
$rescp = mysql_query_decide($sqlcp,$myDb) or die($sqlcp.mysql_error($myDb));
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
        	$mark_zero_arr[] = $proid;
}

$repeat_arr1 =array_diff($repeat_arr1,$currently_paid);
unset($currently_paid);

// reset array index values
$first_arr_1 =array();
$repeat_arr_1 =array();
$renewal_arr_1 =array();
$mark_zero_arr_1 =array();
$first_arr_1 =array_values($first_arr1);
unset($first_arr1);
$repeat_arr_1 =array_values($repeat_arr1);
unset($repeat_arr1);
$renewal_arr_1 =array_values($renewal_arr);
unset($renewal_arr);
$mark_zero_arr_1 =array_values($mark_zero_arr);
unset($mark_zero_arr);

$ptype_arr = array("F","R","C","Z");
$today_arr = array("F"=>$first_arr_1,"R"=>$repeat_arr_1,"C"=>$renewal_arr_1,"Z"=>$mark_zero_arr_1);

$total_profiles = array();
$total_profiles = array_merge($first_arr_1,$repeat_arr_1,$renewal_arr_1,$mark_zero_arr_1);
$total_profiles_str = implode(",",$total_profiles);
unset($total_profiles);
unset($first_arr_1);
unset($repeat_arr_1);
unset($renewal_arr_1);
unset($mark_zero_arr_1);
$globalParamsObj = new ScoringGlobalParams_ab($total_profiles_str);

//Master Connection
$maDb=connect_db();
mysql_query('set session wait_timeout=50000',$maDb);
$date =date("Y-m-d");

//Score computation for each data set
for($t=0;$t<count($ptype_arr);$t++)
{
	$ptype = $ptype_arr[$t];
	$type_arr=$today_arr[$ptype];
	if(count($type_arr)>0)
	{
		//Score computation for each profile of the data set
		$tot_type_arr =count($type_arr);
		for($j=0;$j<$tot_type_arr;$j++)
		{
			$profileid = $type_arr[$j];
			if($profileid)
			{
				if($ptype=="Z")
					$score=0;
				else
                                {
                                        $scorevars = new Scoring_ab($profileid,$myDb,$shDb,$parameter,$ptype,$globalParamsObj);
                                        foreach($scorevars->newmodel as $key=>$val){
                                                if(!$val)
                                                        $val ="";
                                                $scorevars->newmodel[$key] =$val;
                                        }
                                        $newmodelJson=json_encode($scorevars->newmodel,true);
                                        $flag = "";
                                        if($ptype=="C")
                                        {
                                                $flag = "RENHIT";
                                                $response = sendPostData("http://172.10.18.111:2211/jsScore",$newmodelJson);
                                        }
                                        else
                                        {
                                                $flag = "OTHIT";
                                                $response = sendPostData("http://jsscoring.analytics.resdex.com:9000/jsScore",$newmodelJson);
                                        }
                                        $score = round(json_decode($response,true),0);
                                        // temporary_logging
                                        $score1 = json_decode($response,true);
                                        if(!is_numeric($score1)){
                                                $score1 ='NULL';
                                                $hit_log1 =$profileid."#".$newmodelJson;
                                                $fileName1 ="score_hit_log_for_nullResponse".$date.".txt";
                                                passthru("echo '$hit_log1' >>/tmp/$fileName1");
					}
                                        else{
                                                $score1 =round($score1,0);
					}
                                        // temporary_logging   
                                        $hit_log = $flag."#".$profileid."#".$score1."#".$newmodelJson;
                                        $fileName ="score_hit_log".$date.".txt";
                                        passthru("echo '$hit_log' >>/tmp/$fileName");
                                }
				if(isset($score))
				{
					if($score1!='NULL'){
                                        $sql_up = "update incentive.MAIN_ADMIN_POOL set ANALYTIC_SCORE='$score',CUTOFF_DT=now() where PROFILEID='$profileid'";
                                        mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));
					updateScoreLog($profileid, $score, $ptype);
					$count++;
					}
				}
                                unset($scorevars);
                                unset($score);
			}
		}
	}
	unset($type_arr);
}
unset($globalParamsObj);
unset($ptype_arr);
unset($today_arr);

//Sent mail for daily tracking
$msg="$count"."\nShard $activeServerId # End Time=".date("Y-m-d H:i:s");
mail($to,$sub,$msg,$from);

function updateScoreLog($profileid, $score, $ptype) {
	if($ptype == 'C')
		$model = 'RENEWAL';
        elseif($ptype =='Z')
                $model = 'NO_API';
	elseif($ptype=='F')
		$model = 'NEVER_PAID';
	elseif($ptype=='R')
		$model = 'EVER_PAID';

	global $maDb;
	$sql_up = "INSERT INTO incentive.`SCORE_UPDATE_LOG_NEW_MODEL` VALUES ('',  '".$profileid."',  '".$score."', '".$model."',  NOW())";
	mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));	
}

function sendPostData ($url, $post) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
?>
