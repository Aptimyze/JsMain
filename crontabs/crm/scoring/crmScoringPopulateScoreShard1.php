<?php

include("/home/developer/jsdialer/MysqlDbConstants.class.php");
include("Scoring.class.php");

//Sent mail for daily tracking
$date =date("Y-m-d");
$msg="\nPopulate Score # Start Time=".date("Y-m-d H:i:s");
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Scoring Algorithm Score Computation Shard-1";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
ini_set('memory_limit', '300M');

//DB Connection
//$maDb = mysql_connect("master.js.jsb9.net","privUser","Pr!vU3er!") or die("Unable to connect to js server at ".$start);
//$myDb = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
//$shDb1 = mysql_connect("productshard2slave.js.jsb9.net:3309","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
$maDb = mysql_connect(MysqlDbConstants::$master1['HOST'],MysqlDbConstants::$master1['USER'],MysqlDbConstants::$master1['PASS']) or die("Unable to connect to js server".$start);
$myDb = mysql_connect(MysqlDbConstants::$slave111_sel['HOST'],MysqlDbConstants::$slave111_sel['USER'],MysqlDbConstants::$slave111_sel['PASS']) or die("Unable to connect to js server".$start);
$shDb1 = mysql_connect(MysqlDbConstants::$shard1Slave112['HOST'],MysqlDbConstants::$shard1Slave112['USER'],MysqlDbConstants::$shard1Slave112['PASS']) or die("Unable to connect to js server".$start);

mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$maDb);
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$myDb);
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$shDb1);

$parameter = "GENDER,MTONGUE,CITY_RES,ENTRY_DT,SHOW_HOROSCOPE,AGE,INCOME,SOURCE,CASTE,OCCUPATION,MOB_STATUS,LANDL_STATUS,EDU_LEVEL,MSTATUS,GET_SMS,RELIGION,EDU_LEVEL_NEW,VERIFY_EMAIL,HEIGHT,TIME_TO_CALL_START,TIME_TO_CALL_END,HAVE_CAR,OWN_HOUSE,FAMILY_STATUS,SHOWADDRESS,WORK_STATUS,DTOFBIRTH,LAST_LOGIN_DT";

//Pool set of today model wise
$modelType_arr = array("N","R","E","P");
for($t=0;$t<count($modelType_arr);$t++)
{
	$modelArr = array();
        $modelType = $modelType_arr[$t];
	$sql = "SELECT DISTINCT(PROFILEID) FROM test.ANALYTIC_SCORE_POOL WHERE MODEL='$modelType' AND SCORE IS NULL AND PROFILEID%6=0";
	$res = mysql_query($sql,$myDb) or die($sql.mysql_error($myDb));
	while($row = mysql_fetch_array($res))
        	$modelArr[] = $row['PROFILEID'];

        if(count($modelArr)>0)
        {
                //Compute score model wise
                $tot_modelArr =count($modelArr);
                for($j=0;$j<$tot_modelArr;$j++)
                {
                        $profileid = $modelArr[$j];
                        if($profileid)
                        {
				if($modelType=="P")
					$score = 0;
				else
                                {
					$shard = ($profileid%3)+1;
						
                                        $scorevars = new Scoring($profileid,$myDb,$shDb1,$parameter,$modelType,$shard);
                                        foreach($scorevars->newmodel as $key=>$val){
                                                if(!$val)
                                                        $val ="";
                                                $scorevars->newmodel[$key] =$val;
                                        }
                                        $newmodelJson=json_encode($scorevars->newmodel,true);
                                        $flag = "";
                                        if($modelType=="R")
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
                                                /*$hit_log1 =$profileid."#".$newmodelJson;
                                                $fileName1 ="score_hit_log_for_nullResponse_".$date.".txt";
                                                passthru("echo '$hit_log1' >>/tmp/$fileName1");*/
                                        }
                                        else{
                                                $score1 =round($score1,0);
                                        }
					// temporary_logging   
                                        /*$hit_log = $flag."#".$profileid."#".$score1."#".$newmodelJson;
                                        $fileName ="score_hit_log_".$date.".txt";
                                        passthru("echo '$hit_log' >>/tmp/$fileName");*/
                                }
                                if(isset($score))
                                {
                                        if($score1!='NULL'){
                                        $sql_up = "update incentive.MAIN_ADMIN_POOL set ANALYTIC_SCORE='$score',CUTOFF_DT=now() where PROFILEID='$profileid'";
                                        mysql_query($sql_up,$maDb) or die($sql_up.mysql_error($maDb));
                                        updateScoreLog($profileid, $score, $modelType);
                                        }
                                }
                                unset($scorevars);
                                unset($score);
                        }
                }
        }
        unset($type_arr);
}
unset($modelType_arr);
unset($today_arr);

//Sent mail for daily tracking
$msg="\n Populate Score # End Time=".date("Y-m-d H:i:s");
mail($to,$sub,$msg,$from);

function updateScoreLog($profileid, $score, $modelType) {
	if($modelType == 'R')
		$model = 'RENEWAL';
        elseif($modelType =='P')
                $model = 'NO_API';
	elseif($modelType=='N')
		$model = 'NEVER_PAID';
	elseif($modelType=='E')
		$model = 'EVER_PAID';

	global $maDb;
	$sql_up = "INSERT INTO incentive.`SCORE_UPDATE_LOG_NEW_MODEL` VALUES ('',  '".$profileid."',  '".$score."', '".$model."',  NOW())";
	mysql_query($sql_up,$maDb) or die($sql_up.mysql_error($maDb));	

	global $myDb;
	$sql_up = "UPDATE test.ANALYTIC_SCORE_POOL SET SCORE='$score' WHERE PROFILEID='$profileid' AND MODEL='$modelType'";
        mysql_query($sql_up,$myDb) or die($sql_up.mysql_error($myDb));
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
