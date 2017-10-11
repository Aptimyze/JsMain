<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_master.php
* DESCRIPTION 	: Check the current eligibility of profile
* MADE DATE 	: 28 Nov, 2011
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
$flag_using_php5 =1;
chdir(dirname(__FILE__));
include ("$docRoot/crontabs/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");

$db_js = connect_db();
$db_slave = connect_737();
$mysqlObj= new Mysql;

// 30 Days back date
$ts=time();
$ts-=(30-1)*24*60*60;
$date_30Days=date("Y-m-d",$ts);

// Todays date
$date=date("Y-m-d");
list($yy,$mm,$dd)=explode("-",$date);
$today = mktime(0,0,0,$mm,$dd,$yy);

$squery1 = "SELECT PROFILEID FROM incentive.IN_DIALER";
$sresult1 = mysql_query($squery1,$db_js);
while($srow1 = mysql_fetch_array($sresult1))
{
	$stop=0;
	$pid = $srow1["PROFILEID"];
	$stop = check_eligibility($pid,$db_slave,$date_30Days,$today,$mysqlObj);
	if($stop)
	{
		$query = "UPDATE incentive.IN_DIALER SET ELIGIBLE='N' WHERE PROFILEID='$pid'";
		mysql_query($query,$db_js);
	}
	else
	{
		$query = "UPDATE incentive.IN_DIALER SET ELIGIBLE='Y' WHERE PROFILEID='$pid'";
                mysql_query($query,$db_js);
	}
}

function check_eligibility($profileid,$db_slave,$date_30Days,$today,$mysqlObj)
{
	//Pool checks
	$sql = "SELECT PROFILEID,ALLOTMENT_AVAIL FROM incentive.MAIN_ADMIN_POOL WHERE ANALYTIC_SCORE>=1 AND ANALYTIC_SCORE<=100 AND MTONGUE <> '1' AND PROFILEID='$profileid'";
	$res = mysql_query($sql,$db_slave) or die("$sql".mysql_error($db_slave));
	$row = mysql_fetch_array($res);
	if($row['PROFILEID']!='')
	{
		//FTO states Check
                $sql="select STATE_ID from FTO.FTO_CURRENT_STATE WHERE PROFILEID=$profileid";
                $res=mysql_query($sql,$db_slave) or die("$sql".mysql_error($db_slave));
                $row=mysql_fetch_assoc($res);
                $stateId=$row['STATE_ID'];
                if(!$stateId)
                        $ftoState="NEVER_EXPOSED";
                else
                {
                        $sql_state="SELECT STATE from FTO.FTO_STATES WHERE STATE_ID=$stateId";
                        $res_state=mysql_query($sql_state,$db_slave) or die("$sql_state".mysql_error($db_slave));
                        $row_state=mysql_fetch_assoc($res_state);
                        $ftoState=$row_state['STATE'];
                }
                if($ftoState!="EXPIRED" && $ftoState!="NEVER_EXPOSED" && $ftoState!="ACTIVE")
                        return 1;
	
                //Negative profile list
                $NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db_slave);
                $spamParamaters['FLAG_OUTBOUND_CALL']=1;
                if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($profileid,$spamParamaters))
                        return 1;

		//Do not call profiles
		$sql2 = "select PROFILEID from incentive.DO_NOT_CALL  where PROFILEID='$profileid'";
		$res2 = mysql_query($sql2,$db_slave) or die("$sql2".mysql_error($db_slave));
			$row2 = mysql_fetch_array($res2);
			if($row2['PROFILEID']!='')
        	        return 1;

		//Pre-allocated profiles
		$sql3 = "SELECT PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID='$profileid'";
		$res3 = mysql_query($sql3,$db_slave) or die("$sql3".mysql_error($db_slave));
		$row3 = mysql_fetch_array($res3);
		if($row3['PROFILEID']!='')
			return 1;

		//PARMANENT EXCLUSION RULE
		//if($row['ALLOTMENT_AVAIL']=='Y')
		{
			$excl_dnc_dt=@date('Y-m-d',time()-(30-1)*86400);
                        $excl_ni_dt=@date('Y-m-d',time()-(7-1)*86400);
                        $excl_cf_dt=@date('Y-m-d',time()-(7-1)*86400);
                        $excl_d_dt=@date('Y-m-d',time()-(30-1)*86400);
			$excl_5day_dt=@date('Y-m-d',time()-5*86400);
			$excl_3day_dt=@date('Y-m-d',time()-3*86400);
			//setting
			$sql_al = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
			$res_al = mysql_query($sql_al,$db_slave) or die("$sql_al".mysql_error($db_slave));
			if($row_al = mysql_fetch_array($res_al))
			{       
				if($row_al["MEMB_CALLS"]=='U' || $row_al["OFFER_CALLS"]=='U')
					return 1;
			}       
			//disposition
			$sql_history="SELECT ENTRY_DT,DISPOSITION FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
			$res_history = mysql_query($sql_history,$db_slave) or die("$sql_history".mysql_error($db_slave));
			if($row_history = mysql_fetch_array($res_history))
			{
				$rowDisposition =$row_history["DISPOSITION"];
				$rowDate 	=$row_history["ENTRY_DT"];

				if(($row_history["DISPOSITION"]=='D' && $row_history["ENTRY_DT"]>=$excl_d_dt) || ($row_history["DISPOSITION"]=='DNC' && $row_history["ENTRY_DT"]>=$excl_dnc_dt) || ($row_history["DISPOSITION"]=='NI' && $row_history["ENTRY_DT"]>=$excl_ni_dt)||($row_history["DISPOSITION"]=='CF' && $row_history["ENTRY_DT"]>=$excl_cf_dt))
                                        return 1;

		                //JPROFILE CHECKS
		                $sql_1= "SELECT PROFILEID,SUBSCRIPTION,ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PHONE_FLAG !='I' AND COUNTRY_RES=51 AND MTONGUE <> '1' AND LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                		$res_1=mysql_query($sql_1,$db_slave) or die($sql_1.mysql_error($db_slave));
                		if($row_1 = mysql_fetch_array($res_1))
				{
					$ENTRY_DT = $row_1['ENTRY_DT'];
					if((strstr($row_1['SUBSCRIPTION'],"F")!="")||(strstr($row_1['SUBSCRIPTION'],"D")!=""))
						return 1;
				}
                		else
                        		return 1;

				/*check login frequency Start*/
				$ENTRY_DT = substr($ENTRY_DT,0,10);
		                list($b_yy,$b_mm,$b_dd) = explode("-",$ENTRY_DT);
        		        $entry_dt = mktime(0,0,0,$b_mm,$b_dd,$b_yy);
                		$days = ($today-$entry_dt);
                		$diff = (int) ($days/(24*60*60)); // find the number of days a user has been registered with us
                		if($diff > 30)
                        		$diff =30;
				
               			$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
               			$myDb=$mysqlObj->connect("$myDbName");
               			$sql="SELECT count(*) as CNT_L FROM newjs.LOGIN_HISTORY WHERE PROFILEID='$profileid' AND LOGIN_DT>='$date_30Days'";
				$res = $mysqlObj->executeQuery($sql,$myDb);	
				$row=mysql_fetch_array($res);
				$loginCnt =$row['CNT_L'];

				$login_frequency =abs(($loginCnt/$diff)*100);		
				if($login_frequency>33)
				{
					if($rowDate>$excl_5day_dt)	
						return 1;
				}
				else if($rowDate>$excl_3day_dt)
					return 1;
				/*check login frequency Ends*/
			}
		}
		return 0;
		//END
	}
	else
		return 1;
}
	
?>
