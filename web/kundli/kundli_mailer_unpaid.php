<?php
include_once(JsConstants::$alertDocRoot."/kundli/commonIncludeFileForSendingMail.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$sql_loop="SELECT DISTINCT PROFILEID FROM kundli_alert.MAILER_UNPAID";
$result_loop=$mysqlObj->executeQuery($sql_loop,$localdb) or die($sql_loop);
$countMails = 0;
while($row_loop=$mysqlObj->fetchArray($result_loop))
{
	$countMails=0;
   	$profileId=$row_loop["PROFILEID"];
	$SendKundliMailersObj = new SendKundliMailers($localdb,$mysqlObj,$profileId,0);
	$finalIds = $SendKundliMailersObj->fetchMatchesForMailers($mailerLimit);
	if($finalIds)
	{
      		$matchesData = $SendKundliMailersObj->fetchMatchData($finalIds,$profileId);
		if($matchesData && count($matchesData)>5)
		{
			print_r($matchesData);
			die("done");
		}
		if($matchesData && count($matchesData))
		{
			$receiverDetails = $SendKundliMailersObj->fetchName();
			$to=$receiverDetails["EMAIL"];
			$smarty->assign("receiverName",$receiverDetails["NAME"]);
			$smarty->assign("matchesData",$matchesData);
			$protect_obj=new protect;
			$profilechecksum=md5($profileId)."i".$profileId;
			$echecksum=$protect_obj->js_encrypt($profilechecksum,$to);
			$smarty->assign("ECHECKSUM",$echecksum);
			$smarty->assign("RECEIVER_PROFILECHECKSUM",$profilechecksum);
			$smarty->assign("STYPE",31);
			$smarty->assign("PAID",0);
			$msg = $smarty->fetch("kundli_mailer.htm");
			//echo $msg;
			$subject = count($matchesData)." more Kundli matches waiting for you";
			$from="matchalert@jeevansathi.com";
			$from_name="Jeevansathi Alerts";
                        $canSendObj= canSendFactory::initiateClass($channel=CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to,"EMAIL_TYPE"=>"KUNDLI"),$profileId);
                        $canSend = $canSendObj->canSendIt();
                        if(!$canSend)
                                continue;
			send_email($to,$msg,$subject,$from,"","","","","","",1,"",$from_name);
			$SendKundliMailersObj->insertIntoContactTable($matchesData);
			$countMails++;
			$trackingFunctionsObj = new TrackingFunctions("",$mysqlObj);
			$trackingFunctionsObj->trackingMis($countMails,2);
			unset($trackingFunctionsObj);
		}
		$SendKundliMailersObj->removeIds($finalIds);
	}
	unset($SendKundliMailersObj);
}
?>
