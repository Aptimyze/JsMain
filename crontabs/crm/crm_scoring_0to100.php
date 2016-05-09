<?php
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include ("$docRoot/crontabs/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Scoring100.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/ScoringGlobalParams.class.php");

//Sent mail for daily tracking
$msg="\nStart Time=".date("Y-m-d H:i:s");
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Scoring Algorithm:0to100";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
ini_set('memory_limit', '200M');

$mysqlObj=new Mysql;
$parameter = "GENDER,MTONGUE,RELATION,COUNTRY_RES,CITY_RES,ENTRY_DT,DRINK,SMOKE,BTYPE,DIET,MANGLIK,HAVEPHOTO,SHOW_HOROSCOPE,AGE,YOURINFO,FATHER_INFO,SIBLING_INFO,JOB_INFO,INCOME,SOURCE,CASTE,OCCUPATION,MOB_STATUS,LANDL_STATUS,EDU_LEVEL";
global $noOfActiveServers;

/*testing*/
if($_SERVER['argv'][1]!='')
	$pid_single=$_SERVER['argv'][1];
else
	$pid_single='';
/**/

$count=0;
//Computation on all shards
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	//echo "/****************Shard$activeServerId*******************/";echo "\n";
	//Slave Connection
	$myDb=connect_737();
	mysql_query('set session wait_timeout=50000',$myDb);

	//Shard Connection
	$shDbName=getActiveServerName($activeServerId,"slave");
	$shDb=$mysqlObj->connect($shDbName);
	mysql_query('set session wait_timeout=50000',$shDb);

	//Pool set of today
	$last_15day = date("Y-m-d",time()-15*86400);
	if($pid_single!='')
		$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE PROFILEID='$pid_single'";
	else
		$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE LOGIN_DT>='$last_15day'";

	$res = mysql_query_decide($sql,$shDb) or die($sql.mysql_error($shDb));
	while($row = mysql_fetch_array($res))
	{
		$pid = $row['PROFILEID'];
		$pro_arrLogin[] = $pid;
	}
	// get pool set for registered profiles
	if($activeServerId<1){
	        $offline_arr_reg =array();
		$non_offline_arr_reg =array();
	
		$todayDate 	=date("Y-m-d H:i:s");
		$startDt 	=date("Y-m-d H:i:s",strtotime("$todayDate -7 days"));
		$sqlReg ="SELECT PROFILEID,SOURCE FROM newjs.JPROFILE WHERE ENTRY_DT>='$startDt' AND SUBSCRIPTION='' AND ACTIVATED='Y' AND activatedKey=1";
		$resReg = mysql_query_decide($sqlReg,$myDb) or die($sqlReg.mysql_error($myDb));
		while($rowReg =mysql_fetch_array($resReg)){
			$pid = $rowReg['PROFILEID'];
			$source = $rowReg['SOURCE'];
			if($pid && $source=='onoffreg'){
				$offline_arr_reg[] = $pid;
			}
			else{
				$non_offline_arr_reg[] = $pid;
			}
		}
	}
	$repeat_arr =array();
	$pro_onoffreg =array();

	//Bifurcation depending upon the profile type
	$sql_jp = "SELECT PROFILEID FROM newjs.JPROFILE WHERE SUBSCRIPTION ='' AND ACTIVATED ='Y' AND activatedKey =1 AND SOURCE ='onoffreg' AND LAST_LOGIN_DT>='$last_15day'";
	$res_jp = mysql_query_decide($sql_jp,$myDb) or die($sql_jp.mysql_error($myDb));
	while($row_jp = mysql_fetch_array($res_jp))
                $pro_onoffreg[] = $row_jp['PROFILEID'];
	if(is_array($offline_arr_reg))
		$pro_onoffreg =array_merge($pro_onoffreg,$offline_arr_reg);	

	$offline_arr = array();
        $offline_arr = array_intersect($pro_arrLogin,$pro_onoffreg);
        $pro_arrLogin1 = array_diff($pro_arrLogin,$pro_onoffreg);
	unset($pro_arrLogin);
	unset($pro_onoffreg);
	unset($offline_arr_reg);

	if(is_array($non_offline_arr_reg))
		$pro_arrLogin1 = array_merge($pro_arrLogin1,$non_offline_arr_reg);
	unset($non_offline_arr_reg);
	$pro_arrLogin_str = implode(",",$pro_arrLogin1);
	if($pro_arrLogin_str){
		$sql1 = "SELECT distinct PROFILEID FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN ($pro_arrLogin_str) AND STATUS='DONE'";
	        $res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
		while($row1 = mysql_fetch_array($res1))
	                $repeat_arr[] = $row1['PROFILEID'];
	}

	$first_arr = array();
        $first_arr = array_diff($pro_arrLogin1,$repeat_arr);
	unset($pro_arrLogin1);

	//Bifurcated data set with profile type
	$first_arr1 =array_unique($first_arr);
	unset($first_arr);
	$repeat_arr1 =array_unique($repeat_arr);
	unset($repeat_arr);
	if($offline_arr)
		$offline_arr1 =array_unique($offline_arr);
	else
		$offline_arr1 =$offline_arr;
	unset($offline_arr);

	// reset array index values
	$first_arr_1 =array_values($first_arr1);
	unset($first_arr1);
	$repeat_arr_1 =array_values($repeat_arr1);
	unset($repeat_arr1);
	$offline_arr_1 =array_values($offline_arr1);
	unset($offline_arr1);

	$ptype_arr = array("F","R","O");
	$today_arr = array("F"=>$first_arr_1,"R"=>$repeat_arr_1,"O"=>$offline_arr_1);

	$total_profiles = array();
	$total_profiles = array_merge($first_arr_1,$repeat_arr_1,$offline_arr_1);
	$total_profiles_str = implode(",",$total_profiles);
	unset($total_profiles);
	unset($first_arr_1);
        unset($repeat_arr_1);
        unset($offline_arr_1);
	$globalParamsObj = new ScoringGlobalParams($total_profiles_str);

	//Master Connection
	$maDb=connect_db();
	mysql_query('set session wait_timeout=50000',$maDb);

	//Score computation for each data set
	for($t=0;$t<count($ptype_arr);$t++)
	{
		$ptype = $ptype_arr[$t];
		$type_arr=$today_arr[$ptype];
		//echo "/****$ptype****/";echo "\n";
		if(count($type_arr)>0)
		{
			//Intercept for the profile type
			$INTERCEPT = $globalParamsObj->getIntercept($ptype);

			//Weight array for the profile type
			$weight = $globalParamsObj->getWeightParam($ptype);

			//Score computation for each profile of the data set
			$tot_type_arr =count($type_arr);
			for($j=0;$j<$tot_type_arr;$j++)
			{
				$profileid = $type_arr[$j];
				if($profileid)
				{
					$scorevars = new Scoring($profileid,$myDb,$shDb,$parameter,$ptype,$globalParamsObj);
					$scorevars->setAllModalParameters($myDb,$shDb,$ptype);
					$scorevars->setAllTransformParameters($myDb,$shDb,$ptype);
					$score = profile_score($scorevars->modals_bias,$scorevars->transformers,$weight,$INTERCEPT,$myDb,$ptype);
					if(isset($score))
					{
						$sql_up = "update incentive.MAIN_ADMIN_POOL set ANALYTIC_SCORE='$score',CUTOFF_DT=now() where PROFILEID='$profileid'";
						mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));
						updateScoreLog($profileid, $score);
						$count++;
					}
					unset($scorevars);
					unset($score);
				}
			}
		}
		unset($type_arr);
		unset($weight);
		unset($INTERCEPT);
	}
	unset($globalParamsObj);
	unset($ptype_arr);
	unset($today_arr);
}

//Sent mail for daily tracking
$msg="$count"."\nEnd Time=".date("Y-m-d H:i:s");
mail($to,$sub,$msg,$from);

/**
* This function is used to return the score of the profile using final parameter values and their corresponding weights.
*/
function profile_score($modals,$transformers,$WEIGHT_arr,$INTERCEPT,$myDb,$ptype)
{
	$score=0;
	$final_arr = array_merge($modals,$transformers);
	$set_arr = array_keys($WEIGHT_arr);
	for($i=0;$i<count($set_arr);$i++)
	{
		$set_param = $set_arr[$i];
		$score += $WEIGHT_arr["$set_param"]*$final_arr["$set_param"];
	}
	unset($final_arr);
	unset($set_arr);

	$score += $INTERCEPT;
	$fscore = 1/(1+exp(-$score));

	if($ptype=='R')
	{
		if($fscore>10)
                	$fscore=$fscore*2;
		else
			$fscore=$fscore*2.4;
	}
	elseif($ptype=='O')
	{
		if($fscore>10)
                        $fscore=$fscore*1.2;
                else
                        $fscore=$fscore*1.8;
	}

	$fscore=round($fscore,6)*100;
	//Finally the percentile calculation
	$sql = "select percentile from scoring_new.PERCENTILE where Score<='$fscore' order by percentile desc limit 1";
	$res = mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));
	if($row = mysql_fetch_array($res))
		$percentile = $row["percentile"];
	else
		$percentile = 0;
	
	return $percentile;
}

function updateScoreLog($profileid, $score) {
	global $maDb;
	$sql_up = "INSERT INTO incentive.`SCORE_UPDATE_LOG` VALUES ('',  '".$profileid."',  '".$score."',  NOW())";
	mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));	
}

?>
