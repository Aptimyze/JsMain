<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
/*
function is used to perform all(no-joins) select operations on CONTACTS/DELETED_PROFILE_CONTACTS table.
* @param string $parameteres Parameters need to be fetched.
* @param string $sendersIn comma seperated values of senders 
* @param string $sendersNotIn comma seperated values of senders in not-in clause
* @param string $receiversIn   comma seperated values of receivers
* @param string $receiversNotIn comma seperated values of receivers in not-in clause
* @param string $typeIn comma seperated values of contact type
* @param string $typeNotIn comma seperated values of contact type in not-in clause
* @param string $timeClause full time clause (eg 'TIME>'208-09-09')
* @param string $groupBy 
* @param string $orderBy
* @param string $high_priority need to set if query is of HIGH_PRIORITY 
* @param string $is_query_cache need to set if query need to be cached
* @param int $limit number of results need to fetched. 
* @param int/char queryOnSlave set if u want query to be run on slave
* @param string $tableName default:CONTACTS other option DELETED_PROFILE_CONTACTS.
* @param seenIn 
* @param seenNotIn 
* @param filteredIn
* @param filteredNotIn
* @param int $foundRows equivalent to found_rows of sql.this will store in vaiable found_rows. 
* @return array 
* @author : Lavesh rawat
* @copyright : Copyright 2008 Infoedge India Ltd.
*/
function getResultSet($parameters="",$sendersIn="",$sendersNotIn="",$receiversIn="",$receiversNotIn="",$typeIn="",$typeNotIn="",$timeClause="",$groupBy="",$orderBy="",$high_priority="",$is_query_cache="",$limit="",$queryOnSlave="",$tableName="",$seenIn="",$seenNotIn="",$filteredIn="",$filteredNotIn="",$foundRows="")
{
	global $slave_activeServers,$activeServers,$noOfActiveServers,$mysqlObj;

	if(!$parameters)
		$parameters="CONTACTID,SENDER,RECEIVER,TYPE,TIME,COUNT,MSG_DEL,SEEN,FILTERED";
	if($foundRows)
	{
		$parameters="SQL_CALC_FOUND_ROWS ".$parameters;
	}

	if($sendersIn || $receiversIn) 
	{
		$noOfSenders=0;
		$noOfReceivers=0;

		$parametersArrTemp=explode(",",$parameters);
		for($i=0;$i<count($parametersArrTemp);$i++)
		{
			if(strstr($parametersArrTemp[$i],'as'))	
			{
				 $tempArr=explode("as",$parametersArrTemp[$i]);
				 $parametersArr[$i]=trim($tempArr[1]);
			}
			elseif(strstr($parametersArrTemp[$i],'AS'))
			{
				 $tempArr=explode("AS",$parametersArrTemp[$i]);
				 $parametersArr[$i]=trim($tempArr[1]);
			}
			else
				 $parametersArr[$i]=trim($parametersArrTemp[$i]);
		}
		$no_of_parameter=count($parametersArr);
		if($sendersIn)
		{
			$senderArr=explode(",",$sendersIn); 	
			foreach($senderArr as $key=>$val)
			{
				if(is_int($val))
					$sen_arr[]=$val;
			}
			if($sen_arr)
				$sendersIn=implode(",",$sen_arr);	

			$noOfSenders=count($senderArr);
		}
		if($receiversIn)
		{
			$receiverArr=explode(",",$receiversIn);
			foreach($receiverArr as $key=>$val)
			{
                                if(is_int($val))
                                        $rec_arr[]=$val;
                        }
			if($rec_arr)
				$receiversIn=implode(",",$rec_arr);
			$noOfReceivers=count($receiverArr);
		}

		unset($ShardingBySenders);

		if($noOfSenders && $noOfReceivers && ($noOfSenders<$noOfReceivers)) 
			$ShardingBySenders=1;
		elseif($noOfSenders==$noOfReceivers)
			$ShardingBySenders=1;
		elseif($noOfSenders>0 && $noOfReceivers==0)
			$ShardingBySenders=1;

                if(!$mysqlObj)
                        $mysqlObj=new Mysql;
		//$db3=$mysqlObj->connect($activeServers[0]);
		$db3=$mysqlObj->connect("master");

                if($ShardingBySenders && $noOfSenders<3)
                {
                        foreach($senderArr as $key=>$val)
                        {
                                $serverId=getProfileDatabaseId($val,$db3,$mysqlObj);
                                $shard[$serverId][]=$val;
                        }
                }
                elseif(!$ShardingBySenders && $noOfReceivers<3)
                {
                        foreach($receiverArr as $key=>$val)
                        {
                                $serverId=getProfileDatabaseId($val,$db3,$mysqlObj);
                                $shard[$serverId][]=$val;
                        }
                }
                else
                {
                        if($ShardingBySenders)
                                $sql="SELECT PROFILEID, SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID IN ($sendersIn)";
                        else
                                $sql="SELECT PROFILEID, SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID IN ($receiversIn)";
                        $res=mysql_query($sql,$db3) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        while($row=mysql_fetch_array($res))
                        {
                                $serverId=$row["SERVERID"];
                                $profileiId=$row["PROFILEID"];
                                $shard[$serverId][]=$profileiId;
                        }
                }

		$high_priority='';
		if($high_priority)
			$high_priority=" HIGH_PRIORITY ";
		if($is_query_cache)
			$is_query_cache=" SQL_CACHE ";

		if($tableName=="DELETED_PROFILE_CONTACTS")
			$sql_head="SELECT $is_query_cache$high_priority$parameters FROM newjs.DELETED_PROFILE_CONTACTS";	
		else
			$sql_head="SELECT $is_query_cache$high_priority$parameters FROM newjs.CONTACTS";	
		

		if($sendersNotIn)
			$sql_where[]="SENDER NOT IN ($sendersNotIn)";
		if($receiversNotIn)
			$sql_where[]="RECEIVER NOT IN ($receiversNotIn)";
		if($typeIn)
			$sql_where[]="TYPE IN ($typeIn)";
		if($typeNotIn)
			$sql_where[]="TYPE NOT IN ($typeNotIn)";
		if($seenIn)
			$sql_where[]="SEEN IN($seenIn)";
		if($seenNotIn)
			$sql_where[]="SEEN NOT IN($seenNotIn)";

		if($filteredIn){
			if($filteredIn == "'Y'"){
			$sql_where[]="FILTERED IN('Y', 'J')";
			}else
			$sql_where[]="FILTERED IN($filteredIn)";
		}
		if($filteredNotIn){
			if($filteredNotIn == "'Y'"){
			$sql_where[]="FILTERED NOT IN('Y', 'J')";
			}else
			$sql_where[]="FILTERED NOT IN($filteredNotIn)";
		}
		if($timeClause)
			$sql_where[]=$timeClause;	
		if($sql_where)
			$sql_where_str=implode(" AND ",$sql_where);
		if($groupBy)
			$sql_group_by=" GROUP BY ".$groupBy;
		if($orderBy)
			$sql_order_by=" ORDER BY ".$orderBy;
		if($limit)
			$sql_limit=" LIMIT $limit";

		for($i=0;$i<$noOfActiveServers;$i++)
		{
			if(count($shard[$i])>0)
			{
				unset($sql_where1);
				unset($sql_where1_str);
				$shardProfiles=implode(",",$shard[$i]);

				if($ShardingBySenders)
				{
					if($receiversIn)
						$sql_where1[]="SENDER IN ($shardProfiles) AND RECEIVER IN ($receiversIn)";
					else
						$sql_where1[]="SENDER IN ($shardProfiles)";
				}
				else
				{
					if($sendersIn)
						$sql_where1[]="RECEIVER IN ($shardProfiles) AND SENDER IN ($sendersIn)";
					else
						$sql_where1[]="RECEIVER IN ($shardProfiles)";
				}
				
				if($sql_where1)
					$sql_where1_str=implode(" AND ",$sql_where1);

				if($sql_where1_str || $sql_where_str)
				{
					if($sql_where_str)
						$sql_where_str=" AND ".$sql_where_str;
					$sql=$sql_head." WHERE ".$sql_where1_str.$sql_where_str.$sql_group_by.$sql_order_by;
				}
				else
					$sql=$sql_head.$sql_group_by.$sql_order_by;
				$sql=$sql.$sql_limit;
				if($queryOnSlave)
					$myDbName=$slave_activeServers[$i];
				else	
					$myDbName=$activeServers[$i];

				$myDb=$mysqlObj->connect("$myDbName");
				$result=$mysqlObj->executeQuery($sql,$myDb);
				$l=0;
				while($myrow=$mysqlObj->fetchArray($result))
				{
					for($k=0;$k<$no_of_parameter;$k++)
					{
						$columnName=$parametersArr[$k];

						if($foundRows)
						{
							if(strstr($columnName,'SQL_CALC_FOUND_ROWS'))
							{
								$columnName=ltrim($columnName,'SQL_CALC_FOUND_ROWS');
								$columnName=trim($columnName);
							}
						}
						$finalResult[$l][$columnName]=$myrow[$columnName];
					}	
					$l+=1;		
				}

				if($foundRows)
				{
					$temp_sql="select found_rows() as cnt";
					$result_temp=mysql_query($temp_sql,$myDb);
					$row_temp=mysql_fetch_row($result_temp);
					$finalResult[0]['found_rows']=$row_temp[0];
				}
			}		
		}
	}		
	else
	{
		global $_SERVER;
		$http_msg=print_r($_SERVER,true);
		global $data;
		$datastr=print_r($data,true);
		$http_msg1=$http_msg."::".$datastr;
		$dt=date("Y-m-d");
                mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","contacts function error:$dt",$http_msg1);
		exit("SenderIn / ReceiverIn compulsory.");
	}
	return $finalResult;
}

/*
function is used to enter records in CONTACTS table.
* @param int $sender_profileid sender
* @param int $receiver_profileid receiver
* @param char $type contact type
* @param string $time set if current time need to be entered.
* @param int $count  value of COUNT column
* @author : Lavesh rawat
* @copyright : Copyright 2008 Infoedge India Ltd.
*/
function insertIntoContacts($sender_profileid,$receiver_profileid,$type,$time,$count,$filtered,$recSub,$senSub)
{
	global $mysqlObj;
	if(!$mysqlObj)
		$mysqlObj=new Mysql;
	if(!$generated_contact_id)
		 $generated_contact_id=generate_id_from_table("CONTACTS");

        $myDbName=getProfileDatabaseConnectionName($sender_profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $affectedDb[0]=$myDb;

        $myDbName=getProfileDatabaseConnectionName($receiver_profileid,'',$mysqlObj);
        $viewedDb=$mysqlObj->connect("$myDbName");
        if(!in_array($viewedDb,$affectedDb))
                $affectedDb[1]=$viewedDb;

	$currentTime=date("Y-m-d H:i:s");

	if(!$type)
		$type='I';

	$folder=getContactsFolder($sender_profileid,$receiver_profileid,$type,$filtered,$recSub,$senSub);

	$sql_update="INSERT IGNORE INTO CONTACTS(CONTACTID,SENDER,RECEIVER,TYPE,TIME,COUNT,FILTERED,FOLDER) values('$generated_contact_id','$sender_profileid','$receiver_profileid','$type','$currentTime','$count','$filtered','$folder')";
	//$sql_update="INSERT IGNORE INTO CONTACTS(CONTACTID,SENDER,RECEIVER,TYPE,TIME,COUNT,FILTERED) values('$generated_contact_id','$sender_profileid','$receiver_profileid','$type','$currentTime','$count','$filtered')";
	for($ll=0;$ll<count($affectedDb);$ll++)
        {
                $tempDb=$affectedDb[$ll];

                if(count($affectedDb)==2)
                {
                        $mysqlObj->executeQuery($sql_update,$tempDb,'','skipExit') or ($query_died=1);
                        if(mysql_affected_rows($tempDb) && !$query_died)
                                $updateTable[$ll]=1;
                        if($query_died)
                                $show_query_logerror=1;
                        unset($query_died);

                }
                else
                {
                        $mysqlObj->executeQuery($sql_update,$tempDb);
                }
                unset($tempDb);
        }
	/***********************Update seen while writing message from any tuple************************/ 
	updateContactsSeen($sender_profileid, $receiver_profileid, $_REQUEST['who'], $contact_status, $_REQUEST['viewed_profile'], "" );
	/************************Ends here**************************************************************/
	
	if(count($affectedDb)==2)
        {
                if(count($updateTable)==1)
                {
                        if(!$updateTable[0])
                        {
                                $tempDb=$affectedDb[0];
                                $mysqlObj->executeQuery($sql_update,$tempDb,'','skipExit') or ($query_died=1);

                                if(!mysql_affected_rows($tempDb) || $query_died)
                                {
                                        //Delete
                                        $tempDb1=$affectedDb[1];
                                        $sql_del="DELETE FROM CONTACTS WHERE CONTACTID=$generated_contact_id";
                                        $mysqlObj->executeQuery($sql_del,$tempDb1) or ($query_died==1);
                                        if($query_died){
											$show_query_logerror=1;
											//$mysqlObj->logError($sql_update,"",$tempDb);
										}
                                }
                        }

                        if(!$updateTable[1])
                        {
                                $tempDb=$affectedDb[1];
                                $mysqlObj->executeQuery($sql_update,$tempDb,'','skipExit') or ($query_died=1);
                                if(!mysql_affected_rows($tempDb) || $query_died)
                                {
                                        //Delete
                                        $tempDb1=$affectedDb[0];
                                        $sql_del="DELETE FROM CONTACTS WHERE CONTACTID=$generated_contact_id";
                                        $mysqlObj->executeQuery($sql_del,$tempDb1) or ($query_died==1);
                                        if($query_died){
											$show_query_logerror=1;
											//$mysqlObj->logError($sql_update,"",$tempDb);
										}
                                }
                        }

                }
		elseif($show_query_logerror==1)
                {
                        $mysqlObj->logError($sql_update,"",$tempDb);
                }
        }
	return $generated_contact_id;
}

/*
function is used to enter records in CONTACTS table.
* @param int $sender_profileid sender
* @param int $receiver_profileid receiver
* @param int $contact_id unique key to identify contact between 2 users.
* @param char $flag_response contact type
* @param string $time set if current time need to be entered.
* @param int $count_increment_by incremented value of COUNT column
* @msg_del string  related to obscene.
* @author : Lavesh rawat
* @copyright : Copyright 2008 Infoedge India Ltd.
*/
function updateContactsTable($sender_profileid,$receiver_profileid,$contact_id="",$flag_response="",$time="",$count_increment_by="",$msg_del="",$updateString="",$recSub="",$senSub="")
{
	
	global $mysqlObj;

	if(!$mysqlObj)
		$mysqlObj=new Mysql;

        $myDbName=getProfileDatabaseConnectionName($sender_profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $affectedDb[0]=$myDb;

        $myDbName=getProfileDatabaseConnectionName($receiver_profileid,'',$mysqlObj);
        $viewedDb=$mysqlObj->connect("$myDbName");
        if(!in_array($viewedDb,$affectedDb))
                $affectedDb[1]=$viewedDb;

	if($flag_response)
		$folder=getContactsFolder($sender_profileid,$receiver_profileid,$flag_response,'',$recSub,$senSub);

	if($folder)
	{
		if($folder=='blank')
			$updateArr[]="FOLDER=''";
		else
			$updateArr[]="FOLDER='$folder'";
	}

	if($time)
	{
		$currentTime=date("Y-m-d H:i:s");
		$updateArr[]="TIME='$currentTime'";
	}
	if($flag_response)
		$updateArr[]="TYPE='$flag_response'";

	if($count_increment_by)
		$updateArr[]="COUNT=COUNT+$count_increment_by";

	if($msg_del)
		$updateArr[]="MSG_DEL='$msg_del'";
	
	$updateArr[]="SEEN=''";

	if($updateString)
		$updateArr[]=$updateString;
	
	$updateStr=implode(",",$updateArr);

	if($contact_id)
		$sql_update="UPDATE CONTACTS SET $updateStr where CONTACTID='$contact_id'";
	else
		$sql_update="UPDATE CONTACTS set $updateStr where SENDER='$sender_profileid' and RECEIVER='$receiver_profileid'";
			
	/***********************Update seen while taking any action from any tuple************************/
	updateContactsSeen($sender_profileid, $receiver_profileid, $_REQUEST['who'], $contact_status, $_REQUEST['viewed_profile'] ,"");
	/************************Ends here**************************************************************/

	for($ll=0;$ll<count($affectedDb);$ll++)
	{
		$tempDb=$affectedDb[$ll];
		$mysqlObj->executeQuery($sql_update,$tempDb);
	
		if(!mysql_affected_rows($tempDb))
                {
                        if($sender_profileid && $receiver_profileid)
                        {
                                $sql_update_new="UPDATE CONTACTS set $updateStr where SENDER='$sender_profileid' and RECEIVER='$receiver_profileid'";
                                $mysqlObj->executeQuery($sql_update_new,$tempDb);
                        }
                }
		unset($tempDb);
	}

}

function deleteFromContacts($contact_id="",$sender_profileid="",$receiver_profileid="")
{
        global $mysqlObj;

        if(!$mysqObj)
                $mysqlObj=new Mysql;

        $myDbName=getProfileDatabaseConnectionName($sender_profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $affectedDb[0]=$myDb;
        unset($myDbName);
        unset($myDb);

        $myDbName=getProfileDatabaseConnectionName($receiver_profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        if(!in_array($myDb,$affectedDb))
                $affectedDb[1]=$myDb;
        unset($myDbName);
        unset($myDb);

        if($contact_id)
                $sql="DELETE FROM newjs.CONTACTS WHERE CONTACTID='$contact_id'";
        else
                $sql="DELETE FROM newjs.CONTACTS WHERE SENDER='$sender_profileid' AND RECEIVER='$receiver_profileid'";

        for($i=0;$i<count($affectedDb);$i++)
        {
                $tempDb=$affectedDb[$i];
                $mysqlObj->executeQuery($sql,$tempDb);
                unset($tempDb);
        }
}

function updateContactsSeen($sender_profileid, $receiver_profileid, $who, $contact_status, $fromViewProfile, $messageUpdate )
{
		if($messageUpdate)
		{
			$message_upd = 1;
		}
		if($contact_status=="")
		{
			$who="";
			$contact_status="";
		}
		elseif($contact_status)
		{
			if(strstr($contact_status,"R"))
			{
				$who='SENDER';
				$contact_status=substr($contact_status,1,1);
			}
		}
		if(!$fromViewProfile && (($who=='SENDER' && ($contact_status=='A' OR $contact_status=='D')) || ($who!='SENDER' && ($contact_status=='I' || $contact_status=='A'))))
		{
			global $updatecontact,$run_on;
			if($who=='SENDER')
				$run_on='SENDER';
			else
				$run_on='RECEIVER';
			$updatecontact=1;
		}
		$profileid = $receiver_profileid;
                $mypid = $sender_profileid;
        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/alterContactFunctions.txt",var_export($_SERVER,true)."\n",FILE_APPEND);
                include_once("alter_seen_table.php");
}

function displayPhoto($profileid, $gender, $havePhoto, $photoDisplay, $privacy)
{
	global $IMG_URL,$PHOTO_URL;
	if ($havePhoto =='Y')
	{

		if ($privacy == 'R' || $privacy == 'F' || $photoDisplay == 'C' || $photoDisplay == 'F')
		{
			if( $gender == 'M')
				$displayPhoto="$IMG_URL/profile/ser4_images/search_page_boy_protected.gif";
			else
				$displayPhoto="$IMG_URL/profile/ser4_images/search_page_girl_protected.gif";
		}
		elseif($photoDisplay == 'H')
		{
			if($gender=='M')
				$displayPhoto="$IMG_URL/profile/ser4_images/photo_hidden_sm_b.gif";
			else
				$displayPhoto="$IMG_URL/profile/ser4_images/photo_hidden_sm_g.gif";
		}
		else
		{
			$photoChecksum = md5($profileid+5)."i".($profileid+5);
			//Symfony Photo Modification.
			$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($profileid);
			$profilePicObj = $profilePicObjs[$profileid];
			if ($profilePicObj)
				$displayPhoto=$profilePicObj->getThumbailUrl();
			else
				$displayPhoto=null;
			//Symfony Photo Modification.
		}
	}
	else
	{
		if($havePhoto =='U')
		{
			if ($gender == 'M')
				$displayPhoto="$IMG_URL/profile/ser4_images/ph_cmgsoon_sm_b.gif";
			else
				$displayPhoto="$IMG_URL/profile/ser4_images/ph_cmgsoon_sm_g.gif";
		}
		else
		{
			if ($gender == 'M')
				$displayPhoto = "$IMG_URL/profile/ser4_images/Request-a-photo-male_small.gif";
			else
				$displayPhoto = "$IMG_URL/profile/ser4_images/Request-a-photo-Female_small.gif";
		}
	}
	return $displayPhoto;
}

function getContactsFolder($sender_profileid,$receiver_profileid,$type,$filtered,$recSub,$senSub)
{
	$recSubArr=array();
	$senSubArr=array();
	if($recSub)
		$recSubArr=explode(",",$recSub);
	if($senSub)
		$senSubArr=explode(",",$senSub);
	if(in_array("T",$senSubArr))
		$senderAA=1;
	if(in_array("T",$recSubArr))
		$recAA=1;
	if($senderAA || $recAA)
	{
		if($type=='I')
		{
			if($recAA)
			{
				if($filtered)
					$folder='FIL';
				else
					$check='SL';
			}
			else
			{
				if($recSub)
				{
					if(in_array("D",$recSubArr))
						$check='SL';
				}
			}
			if($check=='SL')
			{
				$rowCall=getIntroCallHistory1($sender_profileid,$receiver_profileid);
				if($rowCall)
				{
					if($rowCall["CALL_STATUS"]=="Y")
                                                $folder='';
                                        else
                                                $folder='SL';
				}
				else
					$folder='SL';
			}
		}
		elseif($type=='A')
		{
			$sendersIn="'$sender_profileid'";
                        $receiversIn="'$receiver_profileid'";
                        $folderResultSet=getResultSet("FOLDER",$sendersIn,'',$receiversIn);
			if($folderResultSet[0]["FOLDER"])
				$folder='';
			else
			{
				$sendersIn="'$receiver_profileid'";
				$receiversIn="'$sender_profileid'";
				$folderResultSet=getResultSet("FOLDER",$sendersIn,'',$receiversIn);
				if($folderResultSet[0]["FOLDER"])
					$folder='';
				else
				{
					$rowCall=getIntroCallHistory1($sender_profileid,$receiver_profileid);
					if($rowCall)
					{
						if($rowCall["CALL_STATUS"]=="Y")
							$folder='';
						else
							$folder='SL';
					}
					else
						$folder='SL';
				}
			}
		}
		elseif($type=='D' || $type=='C')
		{
			$check=0;
			$sendersIn="'$sender_profileid'";
			$receiversIn="'$receiver_profileid'";
			$folderResultSet=getResultSet("FOLDER",$sendersIn,'',$receiversIn);
			if($folderResultSet[0]["FOLDER"]=='TBD')
			{
				$rowCall=getIntroCallHistory1($sender_profileid,$receiver_profileid);
				if($rowCall)
				{
					if($rowCall["CALL_STATUS"]=="Y")
						$folder='';
					else
						$folder='blank';
				}
				else
					$folder='blank';
			}
			elseif($folderResultSet[0]["FOLDER"]=="DIS")
				$folder='';
			else
			{
				$sendersIn="'$receiver_profileid'";
                                $receiversIn="'$sender_profileid'";
 				$folderResultSet=getResultSet("FOLDER",$sendersIn,'',$receiversIn);
				if($folderResultSet[0]["FOLDER"]=='TBD')
				{
					$rowCall=getIntroCallHistory1($sender_profileid,$receiver_profileid);
					if($rowCall)
					{
						if($rowCall["CALL_STATUS"]=="Y")
							$folder='';
						else
							$folder='blank';
					}
					else
						$folder='blank';
				}
				elseif($folderResultSet[0]["FOLDER"]=="DIS")
					$folder='';
				else
					$folder='blank';
			}
		}
		return $folder;
		
	}
	else
		return '';
}
?>
