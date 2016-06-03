<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

$matchalertServer = 1;

//INCLUDE FILES HERE
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/newMatches/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");
include_once(JsConstants::$alertDocRoot."/newMatches/StrategyNTvsNEW.php");
include_once(JsConstants::$alertDocRoot."/newMatches/StrategyTvsNEW.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//INCLUDE FILE ENDS

$total_scripts = $_SERVER['argv'][1];
$this_script = $_SERVER['argv'][2];
if(!$total_scripts || (!$this_script && $this_script!=0) || $total_scripts<=$this_script)
{
	die("Invalid Parameters");
}

$mysqlObj = new Mysql;
$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=100000,interactive_timeout=100000,net_read_timeout=100000',$localdb);

$db_fast = $localdb;

$sql = "SELECT PROFILEID FROM new_matches_emails.RECEIVER WHERE SENT = 'N' AND PROFILEID%".$total_scripts."=".$this_script;
//$sql = $sql." LIMIT 50";
$result = $mysqlObj->executeQuery($sql,$localdb);
while($row = $mysqlObj->fetchArray($result))
{
	$profileId=$row["PROFILEID"];
//echo $profileId."\n\n";

	$sql1 = "UPDATE new_matches_emails.RECEIVER SET SENT = 'Y' WHERE PROFILEID = ".$profileId;
	$mysqlObj->executeQuery($sql1,$localdb);

	$receiverObj=new Receiver($profileId,$localdb);//get receiver profile

	if($receiverObj->getSameGenderError()=='N' && $receiverObj->getIsPartnerProfileExist()=="Y")
	{
		if($receiverObj->getHasTrend() != true || $receiverObj->getSwitchToDpp()==1)
               	{
			$StrategyObj = new StrategyNTvsNEW($receiverObj,$localdb);
			$StrategyObj->doProcessing();
		}
		else
		{
			$StrategyObj = new StrategyTvsNEW($receiverObj,$localdb);
			$StrategyObj->doProcessing();
		}
		unset($StrategyObj);
	}
	else
	{
		$gap=MailerConfigVariables::getNoOfDays();
                $zeropid=$profileId;
               	$sql_y="INSERT INTO new_matches_emails.GENDER_OR_JPARTNER_ERROR(PROFILEID,DATE) VALUES($zeropid,$gap)";
               	$mysqlObj->executeQuery($sql_y,$localdb);
		$sameGenderArr.=$profileId. " , ";
	}
}

if($sameGenderArr)
{
	mail("lavesh.rawat@jeevansathi.com","Same Gender Error or DPP data missing","PROFILEID's = ".$sameGenderArr);
}
?>
