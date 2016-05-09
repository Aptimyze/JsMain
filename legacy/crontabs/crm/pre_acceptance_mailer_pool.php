<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("memory_limit","32M");
/*live*/
chdir("$docRoot/crontabs/crm");
include_once("../connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");

/*live*/
/*test*/
/*$_SERVER['DOCUMENT_ROOT']="$_SERVER[DOCUMENT_ROOT]/ser6/trunk";
include($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");*/
/*test*/
include($_SERVER['DOCUMENT_ROOT']."/profile/ntimes_function.php");

$mysqlObj=new Mysql;
global $noOfActiveServers;
$mtongue_arr = array('7','10','19','27','28','33');
$cut_off = date("Y-m-d");
$lim_30_dt = date("Y-m-d",time()-30*86400);

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
	echo "/****************Shard$activeServerId*******************/";echo "\n";
	//Slave Connection
	$myDb=connect_slave();
	mysql_query('set session wait_timeout=50000',$myDb);
	//Shard Connection
	$shDbName=getActiveServerName($activeServerId,"slave");
	$shDb=$mysqlObj->connect($shDbName);
	mysql_query('set session wait_timeout=50000',$shDb);

	/*Receiver*/
	//Login in last 15 days
	if($pid_single!='')
		$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE PROFILEID='$pid_single'";
	else
		$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE LOGIN_DT>=DATE_SUB(CURDATE(),INTERVAL 15 DAY)";
	$res = mysql_query_decide($sql,$shDb) or die($sql.mysql_error($shDb));
	while($row = mysql_fetch_array($res))
	{
		$pid = $row['PROFILEID'];
	        $NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($myDb);
	        if(!$NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($pid))
		{
			//Relation,Mtongue,Photo
			$sqlj = "SELECT RELATION,MTONGUE,HAVEPHOTO FROM newjs.JPROFILE WHERE PROFILEID=$pid AND ACTIVATED='Y' AND SUBSCRIPTION=''";
        	        $resj = mysql_query_decide($sqlj,$myDb) or die($sqlj.mysql_error($myDb));
                	$rowj = mysql_fetch_array($resj);
	                if($rowj['RELATION']!='5' && in_array($rowj['MTONGUE'],$mtongue_arr) && $rowj['HAVEPHOTO']=='Y')
			{
				//Free member
				$sqlp = "SELECT COUNT(*) AS cnt FROM billing.PAYMENT_DETAIL WHERE PROFILEID=$pid AND STATUS='DONE'";
	                        $resp = mysql_query_decide($sqlp,$myDb) or die($sqlp.mysql_error($myDb));
        	                $rowp = mysql_fetch_array($resp);
                	        if(!$rowp['cnt'])
					$pro_arr[] = $pid;
			}
		}
	}

	//Master Connection
        $db=connect_db();
        mysql_query('set session wait_timeout=50000',$db);

	/*Sender*/
	for($i=0;$i<count($pro_arr);$i++)
	{
		$proid = $pro_arr[$i];
		$highest_score = 0;
                $sender_with_highest_score = '';
		//Contacts filters
                $sql1 = "SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='$proid' AND TYPE='I' AND FILTERED<>'Y' AND TIME BETWEEN '$lim_30_dt 00:00:00' AND '$cut_off 23:59:59'";
                $res1 = mysql_query_decide($sql1,$shDb) or die($sql1.mysql_error($shDb));
                while($row1 = mysql_fetch_array($res1))
                {
                        $sender = $row1["SENDER"];
	        	$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($myDb);
		        if(!$NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($pid))
                	{
				//Relation,Photo,Privacy,Tenure
                        	$sqlj = "SELECT RELATION,HAVEPHOTO,PHOTO_DISPLAY,ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID=$sender AND ACTIVATED='Y' AND SUBSCRIPTION=''";
	                        $resj = mysql_query_decide($sqlj,$myDb) or die($sqlj.mysql_error($myDb));
        	                $rowj = mysql_fetch_array($resj);
				if($rowj['RELATION']!='5' && $rowj['HAVEPHOTO']=='Y' && $rowj['PHOTO_DISPLAY']=='A')
				{
					//Free member
					$sqlp = "SELECT COUNT(*) AS cnt FROM billing.PAYMENT_DETAIL WHERE PROFILEID=$sender AND STATUS='DONE'";
	                                $resp = mysql_query_decide($sqlp,$myDb) or die($sqlp.mysql_error($myDb));
        	                        $rowp = mysql_fetch_array($resp);
                	                if(!$rowp['cnt'])
					{
						//Score Check
						$sender_views = ntimes_count($sender,"SELECT");
						$sender_tenure = round(((time()-JSstrToTime($rowj['ENTRY_DT']))/86400)/30,0);
						$sender_score = $sender_views/$sender_tenure;
						if($sender_score>=$highest_score)
						{
							$highest_score = $sender_score; 
							$sender_with_highest_score = $sender; 
						}
					}
				}
			}
		}
		if($highest_score>0)
		{
			$sql2="insert ignore into mailer.PA_MAILER_POOL (RECEIVER,SENDER,SENT) values ('$proid','$sender_with_highest_score','N')";
        	        mysql_query($sql2,$db) or die("$sql2".mysql_error($db));
			$sql3="insert ignore into billing.OFFER_DISCOUNT (PROFILEID,SERVICEID,DISCOUNT,EXPIRY_DT) values ('$proid','P1W','0',now())";
                        mysql_query($sql3,$db) or die("$sql3".mysql_error($db));
			$count++;
		}
        }
	unset($pro_arr);
}

//Sent mail for tracking
/*live*/
$msg="$count";
$to="vibhor.garg@jeevansathi.com";
$sub="Pre-Acceptance Mailer Pool Filled <EOM>";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
/*live*/
?>
