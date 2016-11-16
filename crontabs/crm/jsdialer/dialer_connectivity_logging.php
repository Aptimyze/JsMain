<?php
include("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$dialerLogObj =new DialerLog();
$suffix = '041016';

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$termination_status_labels = array("1"=>'Handled' ,"2"=>'Busy',"3"=>'Machine' ,"4"=>'No Answer' ,"5"=>'Nuisance' ,"6"=>'Abandoned' ,"7"=>'Rejected' ,"8"=>'Invalid Number' ,"9"=>'Overflow' ,"10"=>'Trunk line overflow',"11"=>'RONA' ,"12"=>'Modem answer' ,"13"=>'Fax answer' ,"14"=>'Discarded');

//Compute all the active campaigns
$sqlc= "SELECT CAMPAIGN FROM incentive.CAMPAIGN WHERE ACTIVE = 'Y' AND CAMPAIGN!='PUNE_JS'";
$resc=mysql_query($sqlc,$db_js) or die($sqlc.mysql_error($db_js));
while($myrowc = mysql_fetch_array($resc))
        $camp_array[] = $myrowc["CAMPAIGN"];

if(count($camp_array)>0)
{
        for($i=0;$i<count($camp_array);$i++)
        {
                $campaign_name = $camp_array[$i];
		$today = @date("Y-m-d 00:00:00",time()-86400);//When server is on IST

		/*$squery1 = "SELECT easycode,old_priority,lastonlinepriority,lastpriortizationt,PROFILEID,LAST_CALL_DATE,LAST_CALL_TIME FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('noida$suffix','mumbai$suffix') AND LAST_CALL_DATE>='$today'";
		$squery1 .= " UNION SELECT easycode,old_priority,lastonlinepriority,lastpriortizationt,PROFILEID,LAST_CALL_DATE,LAST_CALL_TIME FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('noida$suffix','mumbai$suffix') AND lastpriortizationt>='$today'";*/

		$squery0 = "select data_context.contact as ecode from thread with (nolock) LEFT JOIN segment  with (nolock) ON segment.thread = thread.code LEFT JOIN data_context with (nolock) ON data_context.code = thread.data_context where segment.start_time>='$today'";
                $squery0 .= " UNION SELECT easycode as ecode FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('noida$suffix','mumbai$suffix','delhi$suffix','renewal$suffix') AND lastpriortizationt>='$today'";
		$sresult0 = mssql_query($squery0,$db_dialer) or $dialerLogObj->logError($squery0,$campaign_name,$db_dialer,1);
		while($srow0 = mssql_fetch_array($sresult0))
		{
			$ecode = $srow0["ecode"];
			if($ecode)
			{
				$squery1 = "SELECT old_priority,lastonlinepriority,lastpriortizationt,PROFILEID,SCORE FROM easy.dbo.ct_$campaign_name where Lead_Id IN ('noida$suffix','mumbai$suffix','delhi$suffix','renewal$suffix') AND easycode=$ecode";
				$sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaign_name,$db_dialer,1);
				if($srow1 = mssql_fetch_array($sresult1))
				{
					$profileid = $srow1["PROFILEID"];
					$offline_priority = $srow1["old_priority"];
					$analytic_score = $srow1["SCORE"];	
					$online_priority = $srow1["lastonlinepriority"];
					if($srow1["lastpriortizationt"])
					{
						//$priortization_time = @date("Y-m-d H:i:s",@strtotime($srow1["lastpriortizationt"]));//When server is on IST
						$priortization_time = @date("Y-m-d H:i:s",@strtotime($srow1["lastpriortizationt"])+10.5*3600);//When server is on EST;
					}
					else
						$priortization_time = '0000-00-00 00:00:00';

					$first_dt_onORafter_priortization='0000-00-00 00:00:00';
					$last_dt_before_priortization='0000-00-00 00:00:00';
					$squery2 = "select call_thread.termination_status,segment.start_time from thread with (nolock) LEFT JOIN call_thread  with (nolock) ON call_thread.code = thread.code LEFT JOIN segment  with (nolock) ON segment.thread = thread.code LEFT JOIN data_context with (nolock) ON data_context.code = thread.data_context where data_context.contact=$ecode";
					$sresult2 = mssql_query($squery2,$db_dialer) or $dialerLogObj->logError($squery2,$campaign_name,$db_dialer,1);
					while($srow2 = mssql_fetch_assoc($sresult2))
					{
						if($srow2["start_time"])
						{
							//$dial_time = @date("Y-m-d H:i:s",@strtotime($srow2["start_time"]));//When server is on IST
							$dial_time = @date("Y-m-d H:i:s",@strtotime($srow2["start_time"])+10.5*3600);//When server is on EST;
							$termination_status = $termination_status_labels[$srow2["termination_status"]];
						}
						else
						{
							$dial_time = '0000-00-00 00:00:00';
							$termination_status = '';
						}
						if($dial_time>=$priortization_time && $first_dial_time_after_priortization='0000-00-00 00:00:00')
						{
							$first_dt_onORafter_priortization = $dial_time;
							$first_ts_onORafter_priortization = $termination_status;
						}
						elseif($dial_time<$priortization_time)
						{
							$last_dt_before_priortization = $dial_time;
							$last_ts_before_priortization = $termination_status;
						}
					}

					if($first_dt_onORafter_priortization!='0000-00-00 00:00:00')
					{
						$require_dial_time = $first_dt_onORafter_priortization;
						$termination_status = $first_ts_onORafter_priortization;
					}
					else
					{
						$require_dial_time = $last_dt_before_priortization;
						$termination_status = $last_ts_before_priortization;
					}
				
					if($require_dial_time<$today)
					{
						 $require_dial_time = '0000-00-00 00:00:00';
						 $termination_status = '';
					}
	       
					$squery3 = "INSERT ignore into js_crm.DIALER_CONNECTIVITY_LOG (PROFILEID,LOG_DATE,CAMPAIGN,OFFLINE_PRIORITY,ONLINE_PRIORITY,PRIORTIZATION_TIME,TERMINATION_STATUS,DIAL_TIME,SCORE) VALUES ('$profileid','$today','$campaign_name','$offline_priority','$online_priority','$priortization_time','$termination_status','$require_dial_time','$analytic_score')";
					$sresult3 = mysql_query($squery3,$db_js) or die($squery3.mysql_error($db_js));
				}
			}
		}
	}
}
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Dialer connectivity logging";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);

?>
