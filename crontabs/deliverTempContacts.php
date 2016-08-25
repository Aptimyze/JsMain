<?php

	/*********************************************************************************************
	Script name	:	deliverTemporaryContacts.php
	Script Type	:	Cron
	Created On	:	12 Sep 09
	Created By	:	Tanu Gupta
	Description	:	Delivers temporary contacts made by incomplete/underscreened profiles after they get activated/completed
	**********************************************************************************************/

	$curFilePath = dirname(__FILE__)."/";
	include_once("/usr/local/scripts/DocRoot.php");
	chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$deliverCounts=array("delivered"=>0,"error"=>0);
ini_set("memory_limit","512M");
        // connect to database
        
		$slave=connect_slave();
		$db=connect_db();
	$ts=time(); 	
	$ts4=$ts-72*60*60;
	$before4days=date("Y-m-d",$ts4); //Before 4 days
	
	$ts1 = $ts-24*60*60;
	$before1day=date("Y-m-d",$ts1); //Previous day
	$startTime = $before4days." 00:00:00";
	$endTime = $before1day." 23:59:59";

	//Selects profiles who got active in past 3 days from yesterday
 	echo $sql = "SELECT DISTINCT(b.PROFILEID) screenedProfiles,b.GENDER,b.SUBSCRIPTION,b.ACTIVATED,b.INCOMPLETE,b.SOURCE FROM jsadmin.MAIN_ADMIN_LOG a, newjs.JPROFILE b WHERE a.PROFILEID=b.PROFILEID AND a.SUBMITED_TIME BETWEEN '$startTime' AND '$endTime' AND a.SCREENING_TYPE = 'O' AND b.ACTIVATED='Y' AND b.INCOMPLETE='N' and b.activatedKey=1";
	$res = mysql_query($sql,$slave);
	while($row = mysql_fetch_array($res))
	{
		$profileid = $row["screenedProfiles"];
		$profilesArr[] = "'".$profileid."'";
		$activeProfiles[$profileid]["GENDER"] = $row["GENDER"];
		$activeProfiles[$profileid]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
		$activeProfiles[$profileid]["ACTIVATED"] = $row["ACTIVATED"];
		$activeProfiles[$profileid]["INCOMPLETE"] = $row["INCOMPLETE"];
		$activeProfiles[$profileid]["PROFILEID"] = $row["screenedProfiles"];
		$activeProfiles[$profileid]["SOURCE"] = $row["SOURCE"];
	}

	//Select profiles who got complete in past 3 days from yesterday
	$sql = "SELECT PROFILEID,GENDER,SUBSCRIPTION,ACTIVATED,INCOMPLETE,SOURCE FROM JPROFILE WHERE ACTIVATED='Y' AND INCOMPLETE='N' AND ENTRY_DT BETWEEN '$startTime' AND '$endTime' AND activatedKey=1";
	$res = mysql_query($sql,$slave);// or die(mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$profileid = $row["PROFILEID"];
		$profilesArr[] = "'".$profileid."'";
		$activeProfiles[$profileid]["GENDER"] = $row["GENDER"];
		$activeProfiles[$profileid]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
		$activeProfiles[$profileid]["ACTIVATED"] = $row["ACTIVATED"];
		$activeProfiles[$profileid]["INCOMPLETE"] = $row["INCOMPLETE"];
		$activeProfiles[$profileid]["PROFILEID"] = $row["PROFILEID"];
		$activeProfiles[$profileid]["SOURCE"] = $row["SOURCE"];
	}

	if($activeProfiles)
	{
		
		$chunkOf500_2 = array_chunk($profilesArr,500);
        $availableChunks_2 = count($chunkOf500_2);
		//Selects temporary contacts of the above profiles which are not delivered yet.
		for($j = 0; $j<$availableChunks_2;$j++)
		{
			$tempProfilesArr=$chunkOf500_2[$j];
		$profileids = implode(",", $tempProfilesArr);
		$sql = "SELECT * FROM CONTACTS_TEMP WHERE SENDER IN ($profileids) AND DELIVERED='N'";
		$res = mysql_query($sql,$slave);// or die(mysql_error());
		$i = 0;
		$tempContact=array();
		while($row = mysql_fetch_array($res))
		{
			$tempContact[$i]["SENDER"] = $row["SENDER"];
			$tempContact[$i]["RECEIVER"] = $row["RECEIVER"];
			$tempContact[$i]["STYPE"] = $row["STYPE"];
			$tempContact[$i]["CUST_MESSAGE"] = $row["CUST_MESSAGE"];
			$tempContact[$i]["DRAFT_NAME"] = $row["DRAFT_NAME"];
			$tempContact[$i]["DRAFT_MESSAGE"] = $row["DRAFT_MESSAGE"];
			$i++;
		}

		if($tempContact)
	{
		
			$deliveredProfiles = array();
			$notDeliveredProfilesError = array();
			foreach($tempContact as $key=>$val)
			{
				$sender_profileid = $val["SENDER"];
				$receiver_profileid = $val["RECEIVER"];
				$stype = $val["STYPE"];
				$custmessage = $val["CUST_MESSAGE"];
				$draft_name = $val["DRAFT_NAME"];
				$draft_message = $val["DRAFT_MESSAGE"];
				$sender_details = $activeProfiles[$sender_profileid];
				$receiver_details = get_profile_details($receiver_profileid);
				$contactDelivered = deliverContact($sender_profileid, $receiver_profileid, $sender_details, $receiver_details, $draft_name, $draft_message, $custmessage, $stype);
				setDeliveredInTempContacts($sender_profileid,$receiver_profileid,$contactDelivered["ERROR"],$db);
				if($contactDelivered["ERROR"])
					$deliverCounts["error"]++;
				else
					$deliverCounts["delivered"]++;
			}
		
	}
unset($tempContact);
unset($deliveredProfiles);
unset($notDeliveredProfilesError);


		}
	}
$cc='nitesh.s@jeevansathi.com';
			$to='nitesh.s@jeevansathi.com';
                       echo  $subject="Temp Contacts --- Delivered = ".$deliverCounts["delivered"]." --Error--".$deliverCounts["error"]."<EOM>";
                        $msg='';
                        send_email($to,$msg,$subject,"",$cc);
	//If not delivered temporary contacts are available, deliver them and mark DELIVERED
	

	/*********
	Inputs are profiles successfully delivered, profiles not delivered due to some error, Error due to which profile didnt deliver, 
	Sets DELIVERED in CONTACTS_TEMP table as 'Y' or 'E'
	If successfully delivered to profiles then sets as "Y"
	If error occurres and not able to deliver contacts to profiles then sets as "E"
	**********/
	function setDeliveredInTempContacts($sender,$receiver,$error="",$db)
	{
                if($error)
                {
			$sql = "UPDATE CONTACTS_TEMP SET DELIVERED='E', DELIVER_TIME=now(), COMMENTS='$error' WHERE SENDER  = '$sender' AND RECEIVER='$receiver' AND DELIVERED ='N'";
			
			mysql_query($sql,$db);
                }
		else
                {
			$sql = "UPDATE CONTACTS_TEMP SET DELIVERED='Y', DELIVER_TIME=now() WHERE SENDER ='$sender' AND RECEIVER='$receiver' AND DELIVERED='N'";
			
			mysql_query($sql,$db);
                }
	}
	
	/************
	Checks whether receiver is filtered or not
	Initiates Contact between sender and receiver
	Inserts relevant values in CONTACTS_ONCE to send email
	************/
	function deliverContact($sender_profileid, $receiver_profileid, $sender_details, $receiver_details, $draft_name, $draft_message, $custmessage, $stype)
	{
		$success = false;
		$error = false;
		$error = deliverContactError($sender_details, $receiver_details);
		if(!$error)
		{
			$CONTACT_STATUS_FIELD['NOT_REP']=1;
			updatememcache($CONTACT_STATUS_FIELD,$sender_profileid,1);
			$DRA_MES = array($draft_name=>$draft_message);
			$filtered = getFilteredContact($sender_profileid, $receiver_profileid);
			makeInitialContact($sender_profileid,$receiver_profileid,$filtered,$stype,$receiver_details["SOURCE"],$receiver_details["SUBSCRIPTION"],$sender_details["SUBSCRIPTION"]);
			get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,"I",$DRA_MES);
			$success = true;
		}
		$delivered = array("ERROR"=>$error,"SUCCESS"=>$success);
		return $delivered;
	}

	/********Checks whether temporary contact has to be delivered or not through
	Gender check
	If receiver is under screening
	If any contact exists between sender and receiver
	If sennder is offline member then disallow him for making contact
	returns error message if any.
	***************/
	function deliverContactError($sender_details, $receiver_details)
	{
		$contactError = false;
		if(!$sender_details["PROFILEID"] && !$receiver_details["PROFILEID"])
		{
			$contactError = "BLANK_PROFILEID";
			return $contactError;
		}
		if($sender_details["GENDER"] == $receiver_details["GENDER"])
		{
			$contactError = "WRONG_GENDER";
			return $contactError;
		}
		if($receiver_details["ACTIVATED"]=="N")
		{
			$contactError = "NOT_ACTIVATED";
			return $contactError;
		}
                if($receiver_details["ACTIVATED"]=="U")
                {
                        $contactError = "UNDER_SCREENED";
                        return $contactError;
                }
                if($receiver_details["ACTIVATED"]=="H")
                {
                        $contactError = "HIDDEN";
                        return $contactError;
                }
                if($receiver_details["ACTIVATED"]=="D")
                {
                        $contactError = "DELETED";
                        return $contactError;
                }
                if($sender_details["SOURCE"] == "ofl_prof")
		{
                        $contactError = "SENDER_OFFLINE";
                        return $contactError;
		}
		if(get_contact_status($sender_details["PROFILEID"], $receiver_details["PROFILEID"]))
		{
			$contactError = "CONTACTED";
			return $contactError;
		}
		return $contactError;
	}

	/********Initiates Contact
	Inserts relevant values in CONTACTS table
	If receiver is an offline member, sets it as a nudge entry
	Search tracking
	*********/
	function makeInitialContact($sender_profileid,$receiver_profileid,$filtered,$stype,$source,$recSub,$senSub)
	{
		try {
			$senderObj = new Profile("",$sender_profileid);
			$senderObj->getDetail("","","*");
			$receiverObj = new Profile("",$sender_profileid);
			$receiverObj->getDetail("","","*");
			//send instant JSPC/JSMS notification
			$producerObj = new Producer();
			if ($producerObj->getRabbitMQServerConnected()) {
				//Add for contact roster
				$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'INITIATE', 'body' => array('sender' => array('profileid'=>$senderObj->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($senderObj->getPROFILEID()),'username'=>$senderObj->getUSERNAME()), 'receiver' => array('profileid'=>$receiverObj->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($receiverObj->getPROFILEID()),"username"=>$receiverObj->getUSERNAME()),"filter"=>$filtered)), 'redeliveryCount' => 0);
				$producerObj->sendMessage($chatData);
			}
			unset($producerObj);
		} catch (Exception $e) {
			throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
		}
                $contact_id=insertIntoContacts($sender_profileid,$receiver_profileid,'I','Y',1,$filtered,$recSub,$senSub);
                //script and function to track search to contact flow
                include_once("search_contact_flow_tracking.php");
                search_contact_flow_tracking($sender_profileid,$stype,$contact_id,"",$mysqlObj);
	}

	/*******
	Inserts relevant values in OFFLINE_MACTHES table and OFFLINE_NUDGE_LOG table
	*******/
	function setNudge($sender_profileid,$receiver_profileid)
	{
                $sql="insert ignore into jsadmin.OFFLINE_MATCHES (STATUS,CATEGORY,SHOW_ONLINE,MATCH_ID,PROFILEID,MATCH_DATE,MOD_DATE) values('NACC',2,'N','$sender_profileid','$receiver_profileid',now(),now())";
                $res=mysql_query($sql,$db);//  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if(mysql_affected_rows_js()>=1)
                {
                        $sql="insert into jsadmin.OFFLINE_NUDGE_LOG(`SENDER`,`RECEIVER`,`DATE`,`TYPE`,`V_CON`) values('$sender_profileid','$receiver_profileid',now(),'NACC','Y')";
                        mysql_query($sql,$db);//   or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                }

	}
?>

