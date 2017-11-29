<?php
/*********************************************************************************************
* FILE NAME   	: priority_change.php
* DESCRIPTION 	: Change the priorities of the online dialing at runtime
* MADE DATE   	: 30 Aug, 2010
* MODIFIED DATE : 12 Apr, 2016
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$start = @date('H:i:s');

$dir ="/home/developer/jsdialer";
include_once($dir.'/plugins/predis-1.1/autoload.php');
$ifSingleRedis ='tcp://172.10.18.75:6380';

// Redis Data fetch 
$client = new Predis\Client($ifSingleRedis);
$pro_array =$online_array = $client->zRange('online_user', 0, -1);

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");


//Remove profiles who are online but already priortized
$last_pro_array = array();
if($start != '00:00:00')
{
	$sql11= "SELECT userID FROM js_crm.last_recentusers";
	$res11=	mysql_query($sql11,$db_js_111) or die($sql11.mysql_error($db_js_111));
	while($myrow1 = mysql_fetch_array($res11))
        	$last_pro_array[] = $myrow1["userID"];
}
$sql12= "TRUNCATE js_crm.last_recentusers";
$res12=	mysql_query($sql12,$db_js_111) or die($sql12.mysql_error($db_js_111));

for($r=0;$r<count($pro_array);$r++)
{
	$sql13= "INSERT IGNORE INTO js_crm.last_recentusers VALUES ($pro_array[$r])";
	$res13=	mysql_query($sql13,$db_js_111) or die($sql13.mysql_error($db_js_111));
}

//Compute users who tried to pay in last one hour
$pro_array2 = array();
$sql2= "SELECT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>DATE_SUB(now(),INTERVAL 1 HOUR) AND PAGE=3";
$res2=mysql_query($sql2,$db_js) or die($sql2.mysql_error($db_js));
while($myrow2 = mysql_fetch_array($res2))
{
	$pro_array2[] = $myrow2["PROFILEID"];
	$pro_array[] = $myrow2["PROFILEID"];
	$online_array[] = $myrow2["PROFILEID"];
}
for($d=0;$d<count($pro_array2);$d++)
{
        $sql13= "INSERT IGNORE INTO js_crm.last_recentusers VALUES ($pro_array2[$d])";
        $res13= mysql_query($sql13,$db_js_111) or die($sql13.mysql_error($db_js_111));
}

$pro_array = array_diff($pro_array,$last_pro_array);
$pro_array = array_unique($pro_array);
$pro_str = @implode("','",$pro_array);

//Compute all the active campaigns
/*$sqlc= "SELECT CAMPAIGN FROM incentive.CAMPAIGN WHERE ACTIVE = 'Y' AND CAMPAIGN!='PUNE_JS'";
$resc=mysql_query($sqlc,$db_js) or die($sqlc.mysql_error($db_js));
while($myrowc = mysql_fetch_array($resc))
	$camp_array[] = $myrowc["CAMPAIGN"];*/
$camp_array = array("JS_NCRNEW","MAH_JSNEW","JS_NCRNEW_Auto");

//Compute Suffix for active leadids
$sql_lf="SELECT LEAD_ID_SUFFIX FROM incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
$res_lf=mysql_query($sql_lf,$db_js) or die($sql_lf.mysql_error($db_js));
$row_lf=mysql_fetch_assoc($res_lf);
$suffix = $row_lf['LEAD_ID_SUFFIX'];

if(count($camp_array)>0)
{
	for($i=0;$i<count($camp_array);$i++)
	{
		$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

		$campaign_name = $camp_array[$i];
		//Connection at DialerDB
		//Part-1 : Priortization
		$cnt1=0;
		if($campaign_name == 'JS_RENEWAL')
			 $squery1 = "SELECT easycode,old_priority,PROFILEID,AGENT,DISCOUNT_PERCENT,SCORE FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('renewal$suffix') AND PROFILEID IN ('$pro_str') AND Dial_Status!=0";
		else
			$squery1 = "SELECT easycode,old_priority,PROFILEID,AGENT,VD_PERCENT,SCORE FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('noida$suffix','mumbai$suffix','delhi$suffix','noidaAuto$suffix') AND PROFILEID IN ('$pro_str') AND Dial_Status!=0";
		$sresult1 = mssql_query($squery1,$db_dialer) or logError($squery1,$campaign_name,$db_dialer,1);
		while($srow1 = mssql_fetch_array($sresult1))
		{
			$today = @date("Y-m-d",time());//When server is on IST
			$ecode = $srow1["easycode"];
			$opriority = $srow1["old_priority"];
			$profileid = $srow1["PROFILEID"];
			$allocated = trim($srow1["AGENT"]);
			if($campaign_name == 'JS_RENEWAL')
				$discount = trim($srow1["DISCOUNT_PERCENT"]);
			else
				$discount = trim($srow1["VD_PERCENT"]);
			$analytic_score = $srow1["SCORE"];
			/*Check weather this profiled dialed today or not*/
			$dialed_today = 0;
		 	$squery2 = "select segment.start_time from thread with (nolock) LEFT JOIN segment  with (nolock) ON segment.thread = thread.code LEFT JOIN data_context with (nolock) ON data_context.code = thread.data_context where data_context.contact=$ecode";
                        $sresult2 = mssql_query($squery2,$db_dialer) or logError($squery2,$campaign_name,$db_dialer,1);
                        while($srow2 = mssql_fetch_assoc($sresult2))
                        {
                                if($srow2["start_time"])
				{
                                        //$dial_date = @date("Y-m-d",@strtotime($srow2["start_time"]));//When server is on IST
					$dial_date = @date("Y-m-d",@strtotime($srow2["start_time"])+9.5*3600);//When server is on EST
				}
				if($dial_date>=$today)
					$dialed_today = 1;
                        }
			/*end*/
			$npriority = '';
			$ignore = 0;
			$query2 = "";
			$query1 = "";
			$query = "";
			if(in_array($profileid,$pro_array2))//Tried to pay in last one hour
			{
				if($allocated)//Allocated to agent
				{
					$cnt1++;
					$npriority='9';
					if($dialed_today)
                                        	$query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
					else
						$query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='1' FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and code='$ecode' and priority!='10' and Dial_Status='2'";
					$query = "UPDATE easy.dbo.ph_contact SET priority = '$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
					$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$npriority','1',now(),'P','$campaign_name','PAYMENT_HIT','Y')";
				}
				else
				{
					$npriority='0';
					if($dialed_today)
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
                                        else
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='2' FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and code='$ecode' and priority!='10' and Dial_Status='1'";
					$query = "UPDATE easy.dbo.ph_contact SET priority = '$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
					$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$npriority','2',now(),'P','$campaign_name','PAYMENT_HIT','N')";
				}
			}
			else
			{
				$cnt1++;
				if($allocated)//Allocated to agent
				{
					if($analytic_score>=91 && $analytic_score<=100)
						$npriority = '7';
					elseif($analytic_score>=81 && $analytic_score<=90)
						$npriority = '6';
					else
						$ignore = 1;
					if($dialed_today)
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
                                        else
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='1',LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and code='$ecode' and priority!='10' and Dial_Status='2'";
					$query = "UPDATE easy.dbo.ph_contact SET priority = '$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
					$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$npriority','1',now(),'P','$campaign_name','ONLINE','Y')";
				}
				else
				{
					if($analytic_score>=96 && $analytic_score<=100)
                                                $npriority = '9';
                                        elseif($analytic_score>=91 && $analytic_score<=95)
                                                $npriority = '8';
                                        elseif($analytic_score>=86 && $analytic_score<=90)
                                                $npriority = '7';
                                        elseif($analytic_score>=81 && $analytic_score<=85)
						$npriority = '6';
					elseif($analytic_score>=76 && $analytic_score<=80)
						$npriority = '5';
					elseif($analytic_score>=71 && $analytic_score<=75)
						$npriority = '4';
					elseif($analytic_score>=61 && $analytic_score<=70)
						$npriority = '3';
					else
						$ignore = 1;
					if($dialed_today)
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
                                        else
                                                $query2 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='1',LAST_LOGIN_DATE=getdate() FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and code='$ecode' and priority!='10' and Dial_Status='2'";
					$query = "UPDATE easy.dbo.ph_contact SET priority = '$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
					$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$npriority','1',now(),'P','$campaign_name','ONLINE','N')";
				}
			}
			if(!$ignore)
			{
				if(!$db_dialer)
					$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123");
				if($query2!="")
				{
					//echo $query2;echo "#";
                                	mssql_query($query2,$db_dialer) or logError($query2,$campaign_name,$db_dialer,1);
				}
				if($query1!="")
				{
					$executeQuery = 1;
					if($analytic_score>41 && $analytic_score<=90 && $campaign_name == 'JS_NCRNEW')
						$executeQuery = 0;
					//echo $query1;echo "#";
					if($executeQuery)
						mssql_query($query1,$db_dialer) or logError($query1,$campaign_name,$db_dialer,1);
				}
				if($query!="")
				{
					//echo $query;echo "#";
					mssql_query($query,$db_dialer) or logError($query,$campaign_name,$db_dialer,1);
				}
				if($log_query!="")
                                {
                                        //echo $log_query;echo "#";
                                        mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
                                }
			}
		}
		echo "*****************************************";
		echo "P#".$cnt1;
		echo "*****************************************";
		echo "\n";

		//Part-2.1 : Depriortization
		$cnt2=0;
		$dep_array = array();
		//if(isset($opriority))//Previously online
		{
			$squery1 = "SELECT easycode,old_priority,PROFILEID,easy.dbo.ct_$campaign_name.AGENT,SCORE,priority FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE Lead_Id IN ('noida$suffix','mumbai$suffix','delhi$suffix','renewal$suffix','noidaAuto$suffix') AND status=0 AND priority BETWEEN 6 and 9 AND Dial_Status!=0";
	                $sresult1 = mssql_query($squery1,$db_dialer) or logError($squery1,$campaign_name,$db_dialer,1);
        	        while($srow1 = mssql_fetch_array($sresult1))
                	{
                        	$ecode = $srow1["easycode"];
	                        $opriority = $srow1["old_priority"];
				$profileid = $srow1["PROFILEID"];
                	        $allocated = trim($srow1["AGENT"]);
				$npriority = $srow1["priority"];
				if($opriority>=0 && !in_array($profileid,$online_array) && $opriority!=$npriority)
				{
					$cnt2++;
					$ds = 0 ;
					if($allocated)
					{
						$ds = 1;
						$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='2' WHERE easycode='$ecode' and Dial_Status='1'";
						mssql_query($query1,$db_dialer) or logError($query1,$campaign_name,$db_dialer,1);
						$query = "UPDATE easy.dbo.ph_contact SET priority = '$opriority' WHERE code='$ecode'";
						mssql_query($query,$db_dialer) or logError($query,$campaign_name,$db_dialer,1);
					}
					else
					{
						$query = "UPDATE easy.dbo.ph_contact SET priority = '$opriority' WHERE code='$ecode'";
						mssql_query($query,$db_dialer) or logError($query,$campaign_name,$db_dialer,1);
					}
					$dep_array[] = $profileid;
					if($ds)
						$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$opriority','2',now(),'D','$campaign_name','PREVIOUS_ONLINE','Y')";
					else
						$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$opriority','',now(),'D','$campaign_name','PREVIOUS_ONLINE','N')";
					mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
                        	}
			}
		}
		echo "*****************************************";
                echo "D1#".$cnt2;
                echo "*****************************************";
		echo "\n";

		//Part-2.2 : Depriortization
		$cnt3=0;
		//if(isset($opriority))//Previously visited membership page
		{
			$squery1 = "SELECT easycode,old_priority,PROFILEID,easy.dbo.ct_$campaign_name.AGENT,priority,SCORE FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE Lead_Id IN ('noida$suffix','mumbai$suffix','delhi$suffix','renewal$suffix','noidaAuto$suffix') AND status=0 AND Dial_Status=2 AND priority=0 and old_priority!=0";
	                $sresult1 = mssql_query($squery1,$db_dialer) or logError($squery1,$campaign_name,$db_dialer,1);
        	        while($srow1 = mssql_fetch_array($sresult1))
                	{
                        	$ecode = $srow1["easycode"];
	                        $opriority = $srow1["old_priority"];
				$profileid = $srow1["PROFILEID"];
                	        $allocated = trim($srow1["AGENT"]);
				$npriority = $srow1["priority"];
				if($opriority>=0 && !in_array($profileid,$online_array) && $opriority!=$npriority)
				{
					$cnt3++;
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status='1' WHERE easycode='$ecode' and Dial_Status='2'";
					mssql_query($query1,$db_dialer) or logError($query1,$campaign_name,$db_dialer,1);
					$query = "UPDATE easy.dbo.ph_contact SET priority = '$opriority' WHERE code='$ecode'";
					mssql_query($query,$db_dialer) or logError($query,$campaign_name,$db_dialer,1);
					if(!in_array($profileid,$dep_array))
					{
						$log_query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$opriority','1',now(),'D','$campaign_name','PREVIOUS_MEM_VISITED','')";
	                                        mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
					}
                        	}
			}
		}
		echo "*****************************************";
                echo "D2#".$cnt3;
                echo "*****************************************";
		echo "\n";
		mssql_close($db_dialer);
	}
	echo "\n"."Thread Started At $start Completed At ".@date('H:i:s')."\n";
}
else
{
	echo $msg = "Not Pass : ".date("Y-m-d H:i:s")."=>".$depriortize."\n";
}
function logError($sql,$campaignName='',$dbConnect='',$ms='')
{
	$dialerLogObj =new DialerLog();
	$dialerLogObj->logError($sql,$campaignName,$dbConnect,$ms);
}
?>
