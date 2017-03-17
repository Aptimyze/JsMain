<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));
$flag_using_php5=1;
include("config.php");
include("connect.inc");
//include("astro/lock.php");
//$fp=get_lock("swap_jpartner");
$today=date("Y-m-d");
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");

if(CommonUtility::hideFeaturesForUptime() && JsConstants::$whichMachine == 'prod')
	successfullDie();


$mysqlObj=new Mysql;
//$db2 = connect_slave();
$LOG_PRO=array();

$db=connect_db();
mysql_query("set session wait_timeout=1000",$db);

$dbDDL=connect_ddl();
mysql_query("set session wait_timeout=10000",$dbDDL);

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query("set session wait_timeout=10000",$myDb[$myDbName]);
}

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,'shardDDL');
        $myDbDDL[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query("set session wait_timeout=10000",$myDbDDL[$myDbName]);
}


//Populate SWAP_JPARTNER from SWAP_JPARTNER of shards
foreach($myDbDDL as $k=>$v)
{
	$sql = "lock tables SWAP_JPARTNER WRITE";
	mysql_query($sql,$v) or die("populate-01".mysql_error1($v));

	$sql = "SELECT PROFILEID FROM SWAP_JPARTNER";
	$res = mysql_query($sql,$v) or die("populate-02".mysql_error1($v));
	while($row=mysql_fetch_array($res))
	{
		if($row["PROFILEID"])
			$idArr[] = "(".$row["PROFILEID"].")";
	}

	$sql="DELETE FROM SWAP_JPARTNER";
	mysql_query($sql,$v) or die("populate-03".mysql_error1($v));

	$sql="UNLOCK TABLES";
	mysql_query($sql,$v) or die("populate-04".mysql_error1($v));

	if($idArr && is_array($idArr) && count($idArr))
	{
		$sql = "REPLACE INTO SWAP_JPARTNER(PROFILEID) VALUES ".implode(",",$idArr);
		mysql_query($sql,$db) or die("populate-05".mysql_error1($db));
	}
	unset($idArr);
}
//Populate ends

// lock table SWAP_JPARTNER so that the JPARTNER trigger does not insert new records untill the lock is released
$sql="lock tables SWAP_JPARTNER WRITE, SWAP_JPARTNER1 WRITE";
mysql_query($sql,$dbDDL) or die("01".mysql_error1($db));

// insert SWAP_JPARTNER records to SWAP_JPARTNER1
$sql="INSERT IGNORE INTO SWAP_JPARTNER1 SELECT * FROM SWAP_JPARTNER";
mysql_query($sql,$dbDDL) or die("02".mysql_error1($db));

// empty SWAP_JPARTNER
$sql="DELETE FROM SWAP_JPARTNER";
mysql_query($sql,$dbDDL) or die("03".mysql_error1($db));

// release lock
$sql="UNLOCK TABLES";
mysql_query($sql,$dbDDL) or die("04".mysql_error1($db));

$timeval = time();
$timeval1 = $timeval;

$sql="SELECT LAST_TIME FROM SWAP_LOG_REV ORDER BY ID DESC LIMIT 1";
$res=mysql_query($sql,$db) or die("0".mysql_error1($db));
$row=mysql_fetch_array($res);
$last_time=$row['LAST_TIME'];
$timeval = date("YmdH0000",$last_time);

$sql="truncate table SWAP_REV";
mysql_query($sql,$dbDDL) or die("1 ".mysql_error1($dbDDL));

$sql="alter table SWAP_REV disable keys";
mysql_query($sql,$dbDDL) or die("2 ".mysql_error1($dbDDL));

$sql="SELECT J.PROFILEID FROM SWAP_JPARTNER1 AS J INNER JOIN SEARCH_MALE AS M ON J.PROFILEID=M.PROFILEID";
$res=mysql_query($sql,$db) or die("3 ".mysql_error1($db));
while($row=mysql_fetch_row($res))
{
        $profileid=$row[0];
        $sql_insert="REPLACE INTO SWAP_REV (PROFILEID,GENDER) VALUES ('$profileid','M')";
        mysql_query($sql_insert,$db) or die("3 1".mysql_error1($db));
}
mysql_free_result($res);

$sql="SELECT J.PROFILEID FROM SWAP_JPARTNER1 AS J INNER JOIN SEARCH_FEMALE AS F ON J.PROFILEID=F.PROFILEID";
$res=mysql_query($sql,$db) or die("3 ".mysql_error1($db));
while($row=mysql_fetch_row($res))
{
        $profileid=$row[0];
        $sql_insert="REPLACE INTO SWAP_REV (PROFILEID,GENDER) VALUES ('$profileid','F')";
        mysql_query($sql_insert,$db) or die("3 1".mysql_error1($db));

}
mysql_free_result($res);


$sql="alter table SWAP_REV enable keys";
mysql_query($sql,$dbDDL) or die("10 ".mysql_error1($dbDDL));

$sql="select PROFILEID from SWAP_REV where GENDER='M'";
$result=mysql_query($sql,$db) or die("12 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{
        $myDbName=getProfileDatabaseConnectionName($myrow["PROFILEID"],'',$mysqlObj);
	$mysqlObj->ping($myDb[$myDbName]);

	$sql_jp="SELECT PROFILEID,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,PARTNER_COMP,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_OCC,PARTNER_SMOKE,PARTNER_RELATION,PARTNER_RELIGION,LINCOME,LINCOME_DOL,STATE FROM newjs.JPARTNER WHERE PROFILEID='" . $myrow["PROFILEID"] . "'";
	$res_jp=mysql_query($sql_jp,$myDb[$myDbName]) or die("19 ".$myDb[$myDbName].mysql_error($myDb[$myDbName]));
	if(mysql_num_rows($res_jp))
	{
		$row_jp=mysql_fetch_assoc($res_jp);
		if($row_jp['PARTNER_MSTATUS'] && strstr($row_jp['PARTNER_MSTATUS'],"\\"))
                        $row_jp['PARTNER_MSTATUS'] = str_replace("\\","",$row_jp['PARTNER_MSTATUS']);
		if($row_jp['PARTNER_CASTE'])
		{
			$casteStr = get_all_caste_revamp_js_db($row_jp['PARTNER_CASTE'],$db);
			$row_jp['PARTNER_CASTE'] = "'".$casteStr."'";
		}
                if($row_jp['STATE']){
                    $stateArr = explode(",",$row_jp['STATE']);
                    $cityWithQuotes = "";
                    foreach($stateArr as $key => $value){
						$cityString = FieldMap::getFieldLabel('state_CITY', trim($value,"'"));
                        $cityWithQuotesArr[]= str_replace(",","','",$cityString);
                    }
                    if(is_array($cityWithQuotesArr))
                    {
						$cityWithQuotes = implode("','",$cityWithQuotesArr);
	                    if($row_jp['PARTNER_CITYRES'])
	                        $row_jp['PARTNER_CITYRES'].= ",'".$cityWithQuotes."'";
	                    else
	                        $row_jp['PARTNER_CITYRES'] = "'".$cityWithQuotes."'";
	                    unset($cityWithQuotesArr);
	                }
                }

		$filterIncome = getFilteredIncome($row_jp['LINCOME'],$row_jp['LINCOME_DOL']);

		$ins_str=$row_jp['PROFILEID'].",\"".$row_jp['CHILDREN']."\",\"".$row_jp['LAGE']."\",\"".$row_jp['HAGE']."\",\"".$row_jp['LHEIGHT']."\",\"".$row_jp['HHEIGHT']."\",\"".$row_jp['HANDICAPPED']."\",\"".$row_jp['PARTNER_BTYPE']."\",\"".$row_jp['PARTNER_CASTE']."\",\"".$row_jp['PARTNER_CITYRES']."\",\"".$row_jp['PARTNER_COMP']."\",\"".$row_jp['PARTNER_COUNTRYRES']."\",\"".$row_jp['PARTNER_DIET']."\",\"".$row_jp['PARTNER_DRINK']."\",\"".$row_jp['PARTNER_ELEVEL_NEW']."\",\"".$row_jp['PARTNER_INCOME']."\",\"".$row_jp['PARTNER_MANGLIK']."\",\"".$row_jp['PARTNER_MSTATUS']."\",\"".$row_jp['PARTNER_MTONGUE']."\",\"".$row_jp['PARTNER_OCC']."\",\"".$row_jp['PARTNER_SMOKE']."\",\"".$row_jp['PARTNER_RELATION']."\",\"".$row_jp['PARTNER_RELIGION']."\",\"".$filterIncome."\",\"".$row_jp['STATE']."\"";
		$ins_str = str_replace("'","",$ins_str);
		$sql_ins="REPLACE INTO SEARCH_MALE_REV (PROFILEID,PARTNER_CHILD,PARTNER_LAGE,PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,PARTNER_HANDICAPPED,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES, PARTNER_COMP,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_OCC,PARTNER_SMOKE,PARTNER_RELATION,PARTNER_RELIGION,PARTNER_INCOME_FILTER,PARTNER_STATE) VALUES ($ins_str)";
		mysql_query($sql_ins,$db) or die("20 $sql_ins".mysql_error1($db));
	}
}

mysql_free_result($result);

$sql="select PROFILEID from SWAP_REV where GENDER='F'";
$result=mysql_query($sql,$db) or die("14 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{
	$myDbName=getProfileDatabaseConnectionName($myrow["PROFILEID"],'',$mysqlObj);
        $mysqlObj->ping($myDb[$myDbName]);

        $sql_jp="SELECT PROFILEID,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,PARTNER_COMP,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_OCC,PARTNER_SMOKE,PARTNER_RELATION,PARTNER_RELIGION,LINCOME,LINCOME_DOL,STATE FROM newjs.JPARTNER WHERE PROFILEID='" . $myrow["PROFILEID"] . "'";
        $res_jp=mysql_query($sql_jp,$myDb[$myDbName]) or die("19 ".mysql_error1($myDb[$myDbName]));
        if(mysql_num_rows($res_jp))
        {
                $row_jp=mysql_fetch_assoc($res_jp);
		if($row_jp['PARTNER_MSTATUS'] && strstr($row_jp['PARTNER_MSTATUS'],"\\"))
                        $row_jp['PARTNER_MSTATUS'] = str_replace("\\","",$row_jp['PARTNER_MSTATUS']);
		if($row_jp['PARTNER_CASTE'])
                {
                        $casteStr = get_all_caste_revamp_js_db($row_jp['PARTNER_CASTE'],$db);
                        $row_jp['PARTNER_CASTE'] = "'".$casteStr."'";
                }
                
                if($row_jp['STATE']){
                    $stateArr = explode(",",$row_jp['STATE']);
                    $cityWithQuotes = "";
                    foreach($stateArr as $key => $value){
						$cityString = FieldMap::getFieldLabel('state_CITY', trim($value,"'"));
                        $cityWithQuotesArr[]= str_replace(",","','",$cityString);
                    }
                    if(is_array($cityWithQuotesArr))
                    {
						$cityWithQuotes = implode("','",$cityWithQuotesArr);
	                    if($row_jp['PARTNER_CITYRES'])
	                        $row_jp['PARTNER_CITYRES'].= ",'".$cityWithQuotes."'";
	                    else
	                        $row_jp['PARTNER_CITYRES'] = "'".$cityWithQuotes."'";
	                    unset($cityWithQuotesArr);
	                }
                } 

                $filterIncome = getFilteredIncome($row_jp['LINCOME'],$row_jp['LINCOME_DOL']);

                $ins_str=$row_jp['PROFILEID'].",\"".$row_jp['CHILDREN']."\",\"".$row_jp['LAGE']."\",\"".$row_jp['HAGE']."\",\"".$row_jp['LHEIGHT']."\",\"".$row_jp['HHEIGHT']."\",\"".$row_jp['HANDICAPPED']."\",\"".$row_jp['PARTNER_BTYPE']."\",\"".$row_jp['PARTNER_CASTE']."\",\"".$row_jp['PARTNER_CITYRES']."\",\"".$row_jp['PARTNER_COMP']."\",\"".$row_jp['PARTNER_COUNTRYRES']."\",\"".$row_jp['PARTNER_DIET']."\",\"".$row_jp['PARTNER_DRINK']."\",\"".$row_jp['PARTNER_ELEVEL_NEW']."\",\"".$row_jp['PARTNER_INCOME']."\",\"".$row_jp['PARTNER_MANGLIK']."\",\"".$row_jp['PARTNER_MSTATUS']."\",\"".$row_jp['PARTNER_MTONGUE']."\",\"".$row_jp['PARTNER_OCC']."\",\"".$row_jp['PARTNER_SMOKE']."\",\"".$row_jp['PARTNER_RELATION']."\",\"".$row_jp['PARTNER_RELIGION']."\",\"".$filterIncome."\",\"".$row_jp['STATE']."\"";
		$ins_str = str_replace("'","",$ins_str);
                $sql_ins="REPLACE INTO SEARCH_FEMALE_REV (PROFILEID,PARTNER_CHILD,PARTNER_LAGE,PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,PARTNER_HANDICAPPED,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES, PARTNER_COMP,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_OCC,PARTNER_SMOKE,PARTNER_RELATION,PARTNER_RELIGION,PARTNER_INCOME_FILTER,PARTNER_STATE) VALUES ($ins_str)";
                mysql_query($sql_ins,$db) or die("20 ".mysql_error1($db));
        }
}

mysql_free_result($result);

$sql="truncate table SWAP_REV";
mysql_query($sql,$dbDDL) or die("16 ".mysql_error1($dbDDL));

$sql="INSERT INTO SWAP_LOG_REV (LAST_TIME) VALUES('$timeval1')";
mysql_query($sql,$db) or die("17".mysql_error1($db));

// script has executed successfully. Truncate table SWAP_JPARTNER1
$sql="truncate table SWAP_JPARTNER1";
mysql_query($sql,$dbDDL) or die("18".mysql_error1($dbDDL));

$currentTime = date("H");
$currentDay = date("D");

if(!in_array($currentTime,array("10","11","12","13")) || JsConstants::$whichMachine != 'prod'){
	$lastTimeSolrRun = date("YmdHis");
	JsMemcache::getInstance()->set('lastTimeSolrRun',$lastTimeSolrRun,1800000,'','X'); 

        if(in_array($currentTime,array(1,2,9,10,18,19)))
                callDeleteCronBasedOnId('EXPORT','N');
        else
                callDeleteCronBasedOnId('DELTA','N');
}

function mysql_error1($db)
{
	global $sql_update,$sql,$sql_total_points;
	$msg=$sql_update .":".$sql.":".$sql_total_points;
echo mysql_error($db);
	mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com,lavesh.rawat@gmail.com","Jeevansathi Error in swapping",$msg);
	mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com,lavesh.rawat@gmail.com","Jeevansathi Error in swapping",mysql_error($db));
}

function updateBucket($myrow_sql_rel_caste_result,$db)
{
        //MTONGUE SCORE
	$allHindiArr=array(7,10,13,19,28,33,41);
	$religionSameAsCasteArr=array(14,149,154,2);
        $par_mtongue=$myrow_sql_rel_caste_result["PARTNER_MTONGUE"];
	$pid=$myrow_sql_rel_caste_result["PROFILEID"];
        if($par_mtongue)
        {
                if(strstr($par_mtongue,","))
                {
                        $par_mtongue=trim($par_mtongue,"'");
                        $par_mtongueArr=explode("','",$par_mtongue);
                        //if(count(array_diff($par_mtongueArr,$allHindiArr))==0 && count(array_diff($allHindiArr,$par_mtongueArr)==0))
                        if(count(array_diff($par_mtongueArr,$allHindiArr))==0)
                                $mtongueScore=10;
                        else
                                $mtongueScore=2;

                }
                else
                        $mtongueScore=10;

        }
        else
                $mtongueScore=2;
        //MTONGUE SCORE

        //MSTAUS
        $par_mstatus=$myrow_sql_rel_caste_result["PARTNER_MSTATUS"];
        if($par_mstatus)
        {
                if($par_mstatus=='N' or $par_mstatus=="'N'")
                        $mstatusScore=10;
                elseif(!strstr($par_mstatus,"N"))
                        $mstatusScore=10;
                else
                        $mstatusScore=1;
        }
        else
                $mstatusScore=1;
        //MSTAUS

        //COUNTRYRES
        $par_countryres=$myrow_sql_rel_caste_result["PARTNER_COUNTRYRES"];
        if($par_countryres)
        {
                $temp=explode(",",$par_countryres);
                if(count($temp)<5)
                        $countryScore=5;
                else
                        $countryScore=2;
        }
        else
                $countryScore=2;
        //COUNTRYRES


        $par_caste=$myrow_sql_rel_caste_result["PARTNER_CASTE"];
        $par_casteOrg=$myrow_sql_rel_caste_result["PARTNER_CASTE"];
        unset($par_casteArr);
        if($par_caste)
        {
                $par_caste=trim($par_caste,"'");
                $par_caste=trim($par_caste,"',");
                $par_casteArr=explode("','",$par_caste);
                $par_caste=implode(",",$par_casteArr);
        }
        $Bucket=0;
        $religionIsCaste=0;

        //if( strstr($par_casteOrg,14) || strstr($par_casteOrg,149) || strstr($par_casteOrg,154) || strstr($par_casteOrg,2) )
	if(is_array($par_casteArr) && array_intersect($par_casteArr,$religionSameAsCasteArr))
        {
                $Bucket=0;
                $religionIsCaste=1;
        }

        if(!$religionIsCaste)
        {
        if(count($par_casteArr)==1)
                $Bucket=1;
        if(count($par_casteArr)>1)
        {
                $flag=0;
                $flag1=0;
		unset($arr);
		$par_caste=str_replace("other","'other'",$par_caste);//added
                $sqlBucket="SELECT PARENT_CASTE,REL_CASTE FROM CASTE_COMMUNITY WHERE REL_CASTE IN ($par_caste) GROUP BY REL_CASTE,PARENT_CASTE";
                $resBucket=mysql_query($sqlBucket,$db) or die("$pid- $sqlBucket".mysql_error1($db));

                while($rowBucket=mysql_fetch_array($resBucket))
                {
                        $pc=$rowBucket['PARENT_CASTE'];
                        $rc=$rowBucket['REL_CASTE'];
                        $arr[$pc][]=$rc;
                        $flag=1;
                }
                if($flag)
                {
                        foreach($arr as $k=>$v)
                        {
                                if(count($v)>1)
                                {
                                        $flag1=array_diff($par_casteArr,$v);
                                        if(count($flag1)==0)
                                                $Bucket=1;
                                }
                                if($Bucket==1)
                                        break;
                        }
                }
        }
        }

        if($Bucket)
                $casteScore=10;
        else
                $casteScore=2;
        $Tscore=$mtongueScore+$mstatusScore+$countryScore+$casteScore;
	return $Tscore;
}

function getFilteredIncome($lincome,$lincome_dol)
{
	if($lincome_dol || $lincome_dol=='0')
	{
		if($lincome || $lincome=='0')
		{
			$rArr["minIR"] = $lincome;
			$rArr["maxIR"] = 19;
			$dArr["minID"] = $lincome_dol;
			$dArr["maxID"] = 19;
			$incomeType = "B";
			$incomeMappingObj = new IncomeMapping($rArr,$dArr,$incomeType);
			$incomeValues = $incomeMappingObj->getAllIncomes(1);
			unset($incomeMappingObj);
			$incomeValueStr = implode(",",$incomeValues);
		}
		else
		{
			$dArr["minID"] = $lincome_dol;
			$dArr["maxID"] = 19;
			$incomeType = "D";
			$incomeMappingObj = new IncomeMapping("",$dArr,$incomeType);
			$incomeValues = $incomeMappingObj->getAllIncomes();
			unset($incomeMappingObj);
			$incomeValueStr = implode(",",$incomeValues);
		}
	}
	else
	{
		if($lincome || $lincome=='0')
		{
			$rArr["minIR"] = $lincome;
			$rArr["maxIR"] = 19;
			$incomeType = "R";
			$incomeMappingObj = new IncomeMapping($rArr,"",$incomeType);
			$incomeValues = $incomeMappingObj->getAllIncomes();
			unset($incomeMappingObj);
			$incomeValueStr = implode(",",$incomeValues);
		}
	}
	return $incomeValueStr;
}
?>
