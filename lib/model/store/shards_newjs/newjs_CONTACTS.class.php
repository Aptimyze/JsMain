<?php

class newjs_CONTACTS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function updateContactDate($sender,$receiver)
        {
                if(!$sender || !$receiver)
                        throw new jsException("","PROFILEID IS BLANK IN newjs_CONTACTS.class.php");
                try
                {
                        $sql = "UPDATE newjs.CONTACTS SET TIME=now() WHERE SENDER =:SENDER AND RECEIVER = :RECEIVER AND TYPE = :TYPE";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
                        $res->bindValue(":SENDER",$sender,PDO::PARAM_INT);
                        $res->bindValue(":TYPE","I",PDO::PARAM_STR);
			$res->execute();
                        return true;
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
                return false;
        }

	public function setFILTERED($sender,$receiver)
	{
                if(!$sender || !$receiver)
                        throw new jsException("","PROFILEID IS BLANK IN newjs_CONTACTS.class.php");
		try
		{
			$sql = "UPDATE newjs.CONTACTS SET FILTERED=:FILTERED,TIME=now() WHERE SENDER =:SENDER AND RECEIVER = :RECEIVER AND TYPE = :TYPE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
			$res->bindValue(":SENDER",$sender,PDO::PARAM_INT);
			$res->bindValue(":TYPE","I",PDO::PARAM_STR);
			$res->bindValue(":FILTERED","Y",PDO::PARAM_STR);
			$res->execute();
			return true;
		}
		catch (PDOException $e)
                {
                        throw new jsException($e);
                }
		return false;
	}
	public function getIfContactSent($senders,$receiver,$contactStatus,$key='')
	{
		$idStr= str_replace("'","",$senders);
		$idArr= explode(",",$idStr);
		foreach($idArr as $k=>$v)
			$idSqlArr[]=":v$k";
		$idSql="(".(implode(",",$idSqlArr)).")";
		$sql = "SELECT SENDER FROM newjs.CONTACTS WHERE SENDER IN $idSql AND RECEIVER IN (:RECEIVER) AND TYPE = :CONTACTSTATUS";
		$res=$this->db->prepare($sql);
		foreach($idArr as $k=>$v)
			$res->bindValue(":v$k", $v, PDO::PARAM_INT);
		$res->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
		$res->bindValue(":CONTACTSTATUS",$contactStatus,PDO::PARAM_STR);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($key == 1)
				$result[$row['SENDER']]=1;
			else
				$result[]=$row['SENDER'];
		}
		return $result;
	}

public function getContactsPending($serverId)
	{
		try
		{
			$sql = "SELECT RECEIVER,count(*) as count from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TYPE='I' and FILTERED<>'Y' and TIME >= DATE_SUB(CURDATE(), INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND RECEIVER=PROFILEID AND SERVERID= :SERVERID group by RECEIVER";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SERVERID",$serverId,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row['RECEIVER'];	
				//$result['count'][] = $row['count'];		
			}
			//var_dump($result);die;
			return $result;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}	
		
	}


public function getSendersPending($chunkStr)
	{
		try
		{
        		$sql = "SELECT RECEIVER, SENDER   FROM newjs.CONTACTS WHERE TYPE IN ('I') AND FILTERED NOT IN('Y') and TIME >= DATE_SUB(CURDATE(), INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) $chunkStr ORDER BY TIME DESC";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['RECEIVER']][] = $row['SENDER'];	
				//$result['count'][] = $row['count'];		
			}
			//print_r($profileids);
			//var_dump($result);die;
			return $result;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}	
		
	}

		public function getFilterContacts($serverId,$chunkStr)
	{
		try
		{
			$sql = "SELECT count(*) as count, RECEIVER, GROUP_CONCAT( SENDER ORDER BY TIME DESC SEPARATOR ',' ) AS SENDER FROM  `CONTACTS` WHERE ".$chunkStr." AND `FILTERED` =  'Y' AND `TYPE` =  'I' AND TIME >= DATE_SUB(CURDATE(), INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) GROUP BY  `RECEIVER`";
			$res = $this->db->prepare($sql);
			//$res->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['RECEIVER']] = $row['SENDER'];
				$result[$row['COUNT']] = $row['count'];
							
			}
			return $result;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getContactsSent($senders,$receivers)
	{
		$idStr= str_replace("'","",$senders);
		$idArr= explode(",",$idStr);
		foreach($idArr as $k=>$v)
			$idSqlArr[]=":v$k";
		$idSql="(".(implode(",",$idSqlArr)).")";
		$ridStr= str_replace("'","",$receivers);
		$ridArr= explode(",",$ridStr);
		foreach($ridArr as $k=>$v)
			$ridSqlArr[]=":u$k";
		$ridSql="(".(implode(",",$ridSqlArr)).")";
		$sql = "SELECT SENDER,RECEIVER,TYPE FROM newjs.CONTACTS WHERE SENDER IN $idSql AND RECEIVER IN $ridSql";
		$res=$this->db->prepare($sql);
		foreach($idArr as $k=>$v)
			$res->bindValue(":v$k", $v, PDO::PARAM_INT);
		foreach($ridArr as $k=>$v)
			$res->bindValue(":u$k", $v, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result[]=$row;
		}
		return $result;
	}

	public function getContactsRemovedFromSearch($profileid,$seperator='')
	{
		$sql = "SELECT RECEIVER AS PID FROM newjs.CONTACTS WHERE SENDER= :PROFILEID UNION SELECT SENDER AS PID FROM newjs.CONTACTS WHERE RECEIVER= :PROFILEID AND TYPE<>'I'";

		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($seperator == 'spaceSeperator')
				$result.= $row["PID"]." ";
			else
				$result[]=$row['PID'];
		}
		return $result;
	}

	
    public function getContactAcceptanceCount($profileId,$direction='')
	{
		if($direction == "OUTBOUND")
		{
			$text = "RECEIVER = :PROFILEID";
		}
		else if($direction == "BOTH")
		{
			$text = "(SENDER = :PROFILEID OR RECEIVER = :PROFILEID)";
		}
		else
		{
			$text = "SENDER = :PROFILEID";
		}
		$sql = "SELECT count(SENDER) as COUNT FROM newjs.CONTACTS WHERE ".$text."  AND TYPE = 'A' ";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
		$res->execute();
		if($result = $res->fetch(PDO::FETCH_ASSOC))
		{
			$count = $result['COUNT'];
			return $count;
		}
		return 0;
	}
    
    public function getResponseCount($profileId)
	{
		try
		{
			$sql = "SELECT count(SENDER) as COUNT,TYPE FROM newjs.CONTACTS WHERE SENDER = :SENDER GROUP BY TYPE";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SENDER",$profileId,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row;			
			}
			return $result;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}	
		
	}
	public function getRespondedCount($profileId,$timeClause="", $filteredIn = "", $filteredNotIn = "")
	{
		try
		{
			$sql = "SELECT count(RECEIVER) as COUNT, TYPE, FILTERED FROM newjs.CONTACTS WHERE RECEIVER = :RECEIVER";
			if($timeClause)	{
				$sql.=" AND $timeClause GROUP BY TYPE, FILTERED";
      } else {
      $sql.=" GROUP BY TYPE";
      }
			$res = $this->db->prepare($sql);
			$res->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row;			
			}
      return $result;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function getOpenContactsCount($profileId)
	{
		try
		{
			$sql = "SELECT count(RECEIVER) as COUNT FROM newjs.CONTACTS WHERE RECEIVER = :RECEIVER AND TYPE ='I' AND SEEN = 'Y'";
			$res = $this->db->prepare($sql);
			$res->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result = $row;
				return $result['COUNT'];			
			}
			return 0;
		}
	    catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	public function getInBoundAcceptedContact($profileId)
	{
		$sql = "SELECT m.SENDER, m.RECEIVER, MIN( m.ID ) ID
					FROM  `MESSAGE_LOG` m
					JOIN CONTACTS c ON ( m.SENDER = c.SENDER ) 
					WHERE c.TYPE =  'A'
					AND m.SENDER = :SENDER
					AND m.type =  'A'
					GROUP BY RECEIVER
					ORDER BY ID";	
		$res = $this->db->prepare($sql);
		$res->bindValue(":SENDER",$profileId,PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		return $result;
	}
	public function getOutBoundAcceptedContact($profileId)
	{
		$sql = "SELECT m.SENDER, m.RECEIVER, MIN( m.ID ) ID
					FROM  `MESSAGE_LOG` m
					JOIN CONTACTS c ON ( m.RECEIVER = c.RECEIVER ) 
					WHERE c.TYPE =  'A'
					AND m.RECEIVER= :RECEIVER
					AND m.type =  'A'
					GROUP BY SENDER
					ORDER BY ID";	
		$res = $this->db->prepare($sql);
		$res->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		return $result;
	}



	public function getContactRecord($profileId1,$profileId2)
	{
		try
		{
			$sql = "SELECT * FROM newjs.CONTACTS where SENDER IN (:PROFILEID1,:PROFILEID2) and RECEIVER IN (:PROFILEID1,:PROFILEID2)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID1",$profileId1,PDO::PARAM_INT);
			$prep->bindValue(":PROFILEID2",$profileId2,PDO::PARAM_INT);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				return $result;
			}
			return NULL;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}

	}
	
	public function getReminderCount($sender,$receiver)
	{
		try
		{
			$sql = "SELECT COUNT FROM newjs.CONTACTS where SENDER = :SENDER and RECEIVER = :RECEIVER";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SNEDER",$sender,PDO::PARAM_INT);
			$prep->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
			$prep->execute();		
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
				return $result['COUNT'];
            }
            return NULL;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
			
	}

	public function insert($contactObj)
	{
		try
		{
			$sql = "INSERT IGNORE INTO newjs.CONTACTS VALUES (:CONTACTID,:SENDER,:RECEIVER,:TYPE,:TIME,:COUNT,:MSG_DEL,:SEEN,:FILTERED,:FOLDER)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":CONTACTID", $contactObj->getCONTACTID(), PDO::PARAM_INT);
			$prep->bindValue(":SENDER",$contactObj->getSenderObj()->getPROFILEID(),PDO::PARAM_INT);
			$prep->bindValue(":RECEIVER",$contactObj->getReceiverObj()->getPROFILEID(),PDO::PARAM_INT);
			$prep->bindValue(":COUNT", $contactObj->getCOUNT(), PDO::PARAM_INT);
			$prep->bindValue(":TYPE",$contactObj->getTYPE(),PDO::PARAM_STR);
			$prep->bindValue(":TIME",$contactObj->getTIME(),PDO::PARAM_STR);
			if($contactObj->getPageSource()=="AP")
				$prep->bindValue(":MSG_DEL","Y",PDO::PARAM_STR);
			else
				$prep->bindValue(":MSG_DEL","N",PDO::PARAM_STR);
			$prep->bindValue(":SEEN",$contactObj->getSEEN(),PDO::PARAM_STR);
			$prep->bindValue(":FILTERED",$contactObj->getFILTERED(),PDO::PARAM_STR);
			$prep->bindValue(":FOLDER",$contactObj->getFOLDER(),PDO::PARAM_STR);
			if($prep->execute())
			{
				return $prep->rowCount();
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			jsException::log($e->getMessage()."\n".$e->getTraceAsString());
		}
	}

	public function update($contactObj)
	{
		try
		{
			$sql = "UPDATE newjs.CONTACTS SET TYPE=:TYPE,TIME=:TIME,COUNT=:COUNT,MSG_DEL=:MSG_DEL,SEEN=:SEEN,FILTERED=:FILTERED,FOLDER=:FOLDER WHERE SENDER = :SENDER AND RECEIVER = :RECEIVER";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SENDER",$contactObj->getSenderObj()->getPROFILEID(),PDO::PARAM_INT);
			$prep->bindValue(":RECEIVER",$contactObj->getReceiverObj()->getPROFILEID(),PDO::PARAM_INT);
			$prep->bindValue(":COUNT", $contactObj->getCOUNT(), PDO::PARAM_INT);
			$prep->bindValue(":TYPE",$contactObj->getTYPE(),PDO::PARAM_STR);
			$prep->bindValue(":TIME",$contactObj->getTIME(),PDO::PARAM_STR);
			$prep->bindValue(":MSG_DEL",$contactObj->getMSG_DEL(),PDO::PARAM_STR);
			$prep->bindValue(":SEEN",$contactObj->getSEEN(),PDO::PARAM_STR);
			$prep->bindValue(":FILTERED",$contactObj->getFILTERED(),PDO::PARAM_STR);
			$prep->bindValue(":FOLDER",$contactObj->getFOLDER(),PDO::PARAM_STR);

			if($prep->execute())
			{
				return $prep->rowCount();
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			jsException::log($e->getMessage()."\n".$e->getTraceAsString());
		}
	}
	
	public function delete($contactObj)
	{
		try 
		{
			$sql = "DELETE FROM newjs.CONTACTS WHERE SENDER = :SENDER AND RECEIVER = :RECEIVER";
      		$prep = $this->db->prepare($sql);
      		$prep->bindValue(":SENDER",$contactObj->getSenderObj()->getPROFILEID(),PDO::PARAM_INT);
			$prep->bindValue(":RECEIVER",$contactObj->getReceiverObj()->getPROFILEID(),PDO::PARAM_INT);
			if($prep->execute())
			{
				return $prep->rowCount();
			}
			else
				return 0;
		}
		catch (PDOException $e)
		{
			jsException::log($e->getMessage()."\n".$e->getTraceAsString());
		}
	}
	public function getContactsRemovedFromSearchIncludingAwaitingContacts($profileid,$seperator='')
	{
		$sql = "SELECT RECEIVER AS PID FROM newjs.CONTACTS WHERE SENDER=:PROFILEID UNION SELECT SENDER AS PID FROM newjs.CONTACTS WHERE RECEIVER=:PROFILEID";

		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($seperator == 'spaceSeperator')
				$result.= $row["PID"]." ";
			else
				$result[]=$row['SENDER'];
		}
		return $result;
	}
        
        public function getTodayInitiatedForAP($profileid)
        {		
				$date = date("Y-m-d");
				
                $sql = "SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE SENDER=:PROFILEID AND TYPE='I' AND DATE(TIME) = '$date'";

                $res=$this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $res->execute();
				if($result = $res->fetch(PDO::FETCH_ASSOC))
				{	
					return $result['COUNT'];
				}
        }
	public function getEoiReceiversFromProfile($senderProfileid)
	{
		try
		{
			$sql = "SELECT RECEIVER FROM newjs.CONTACTS where SENDER=:SENDER AND TYPE='I' AND FILTERED!='Y'";
			$res=$this->db->prepare($sql);
			$res->bindValue(":SENDER",$senderProfileid,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
				 $return[] = $row['RECEIVER'];
			return $return;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*
	* This function is used to get the count of acceptances received of multiple senders passed.
	* @param - array of profileids, date(optional)
	* @return - array of resultset rows
	*/
	public function getAcceptanceCountForMultipleProfiles($profileArr,$dt="")
	{
		if(!$profileArr)
			throw new jsException("","PROFILEID ARRAY IS BLANK IN getAcceptanceCountForMultipleProfiles() OF newjs_CONTACTS.class.php");

		try
		{
			foreach($profileArr as $k=>$v)
				$paramArr[] = ":PROFILEID".$k;

			$sql = "SELECT COUNT(SENDER) AS C,SENDER FROM newjs.CONTACTS WHERE SENDER IN (".implode(",",$paramArr).") AND TYPE = :ACCEPT";
			if($dt)
				$sql = $sql." AND TIME <= :DATE";
			$sql = $sql." GROUP BY SENDER";
			$res=$this->db->prepare($sql);
			foreach($profileArr as $k=>$v)
				$res->bindValue($paramArr[$k],$v,PDO::PARAM_INT);
			$res->bindValue(":ACCEPT","A",PDO::PARAM_STR);
			if($dt)
				$res->bindValue(":DATE",$dt,PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}
   /*
	* This function is used to get the count of EOI sent for multiple senders passed.
	* @param - array of profileids
	* @return - array of resultset rows
	*/
	public function getEoiCountForMultipleProfiles($profileArr)
	{
		if(!$profileArr)
			throw new jsException("","PROFILEID ARRAY IS BLANK IN getEoiCountForMultipleProfiles() OF newjs_CONTACTS.class.php");

		try
		{
			foreach($profileArr as $k=>$v)
				$paramArr[] = ":PROFILEID".$k;

			$sql = "SELECT COUNT(SENDER) AS C,SENDER FROM newjs.CONTACTS WHERE SENDER IN (".implode(",",$paramArr).") GROUP BY SENDER";
			$res=$this->db->prepare($sql);
			foreach($profileArr as $k=>$v)
				$res->bindValue($paramArr[$k],$v,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
		return $output;
	}
	
	public function getContactTypeFromContactId($contactId){
		try{
			$idStr= str_replace("'","",$contactId);
			$idArr= explode(",",$idStr);
			foreach($idArr as $k=>$v)
				$idSqlArr[]=":v$k";
			$idSql="(".(implode(",",$idSqlArr)).")";
			$sql = "SELECT CONTACTID , TYPE,MSG_DEL FROM newjs.CONTACTS WHERE CONTACTID IN $idSql";
			$res=$this->db->prepare($sql);
			foreach($idArr as $k=>$v)
				$res->bindValue(":v$k", $v, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{  // print_r($row); die;
				$output[$row["CONTACTID"]]['TYP'] = $row["TYPE"];
				$output[$row["CONTACTID"]]['MSG_DEL'] = $row["MSG_DEL"];
			}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
        //print_r($output);
        return $output;
	}
   /*
	* This function is used to get the count of contacts with given set .
	* @param - where contiction and group by
	* @return - all contacts count for given condition
	*/

	public function getContactsCount($where, $group='',$time='',$skipProfile)
	{
		try{
			if(!$where)
				throw new jsException("","No where condition is specified in funcion getContactsCount OF newjs_CONTACTS.class.php");

			$sql = "SELECT COUNT(*) AS COUNT";
			if($group)
			{
				$sql = $sql.",".$group;
			}
			// if($time)
			// 	$sql = $sql.",CASE WHEN DATEDIFF(NOW( ) ,  `TIME`) <=90 THEN 0 ELSE 1 END AS TIME1 ";
			if ($time)
				$sql = $sql.",CASE
				WHEN DATEDIFF(NOW( ) ,  `TIME` ) <= ".CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT." AND DATEDIFF(NOW( ) ,  `TIME` ) >= ".CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT."  THEN 2 
				WHEN DATEDIFF(NOW( ) ,  `TIME` ) <= ".CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT." THEN 0
				WHEN DATEDIFF(NOW( ) ,  `TIME` ) > ".CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT." THEN 1
				END AS TIME1 ";
			$sql = $sql." FROM newjs.CONTACTS WHERE";
			if($where)
			{
				$count = 1;
				foreach($where as $key=>$value)
				{
					if(!is_array($value))
					{

						$sqlArr[] = " ".$key."=:VALUE".$count." ";
						$bindArr["VALUE".$count] = $value;
						$count++;
					}
					else
					{
						$str = " ".$key." IN(";

						foreach($value as $key1=>$value1)
						{
							$str = $str.":VALUE".$count.",";
							$bindArr["VALUE".$count] = $value1;
							$count++;
						}
						$str = substr($str, 0, -1);
						$str = $str.")";
						$sqlArr[] = $str;
					}
				}
				$sql = $sql.implode("AND",$sqlArr);

			}
			if($skipProfile)
			{
				$other = isset($where["RECEIVER"])?"SENDER":"RECEIVER";
				$str = " ".$other." NOT IN(";

				foreach($skipProfile as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql." AND ". $str;
			}
			if($group)
			{
				$sql = $sql." GROUP BY ".$group;
				if($time)
					$sql = $sql.",TIME1";
			}
			elseif($time)
			{
				$sql = $sql." GROUP BY TIME1";
			}
			$res=$this->db->prepare($sql);
			foreach($bindArr as $k=>$v)
				$res->bindValue($k,$v);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}


	public function getContactedProfiles($profileId,$senderReceiver,$type='',$cnt='')
	{
		try{
			if(!$profileId)
				throw new jsException("","No ProfileId is specified in funcion getContactedProfiles OF newjs_CONTACTS.class.php");
			$sql = "SELECT ";
			if($senderReceiver == "SENDER")
			{
				$sql = $sql."RECEIVER AS PROFILEID ";
			}
			else
			{
				$sql = $sql."SENDER AS PROFILEID ";
			}
			$sql = $sql.",TYPE ";
			$sql= $sql."FROM newjs.CONTACTS WHERE ".$senderReceiver." = :PROFILEID ";
			if($type)
			{
				$str = " AND TYPE IN(";
				$count = 1;
				foreach($type as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql.$str;
			}
			if($cnt)
			$sql.=" AND COUNT=:COUNT ";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			if($cnt)
				$res->bindValue(":COUNT",$cnt,PDO::PARAM_INT);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
					$res->bindValue($k,$v);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$row["TYPE"]][] = $row["PROFILEID"];
			}

		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
	public function getContactedProfileArray($condition,$skipArray)
	{
		$string = array('TYPE','SEEN','FILTER','TIME','MSG_DEL','SENDER','RECEIVER');
		try{
			if(!$condition)
			{
				throw new jsException("","conditions are not specified in getContactedProfileArray() OF newjs_CONTACTS.class.php");
			}
			$count = 0;
			foreach($condition as $key=>$value)
			{
				if($key=="WHERE")
				{
					foreach($value as $key1=>$value1)
					{
						if($key1 == "NOT_IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$str = $str = $keyName." NOT IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$keyValues =  implode(",",$keyValue);
								$arr[] = $str;
							}
						}
						if($key1 == "IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								if($keyName =="RECEIVER")
									$select = "SENDER";
								elseif($keyName == "SENDER")
									$select = "RECEIVER";
								$str = $str = $keyName." IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$arr[] = $str;
							}
						}
						if($key1 == "GREATER_THAN_EQUAL")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName.">= :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								if(in_array($keyName,$string))
									$bindArr["VALUE".$count]["TYPE"] = "STRING";
								else
									$bindArr["VALUE".$count]["TYPE"] = "INT";
								$count++;
							}
						}

						if($key1 == "LESS_THAN_EQUAL_EXPIRING")
						{
							$expiry = 1;
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName.">= :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								if(in_array($keyName,$string))
									$bindArr["VALUE".$count]["TYPE"] = "STRING";
								else
									$bindArr["VALUE".$count]["TYPE"] = "INT";
								$count++;
							}
						}

						if($key1 == "GREATER_THAN_EQUAL_EXPIRING")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName."< :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								if(in_array($keyName,$string))
									$bindArr["VALUE".$count]["TYPE"] = "STRING";
								else
									$bindArr["VALUE".$count]["TYPE"] = "INT";
								$count++;
							}
						}

						if($key1 == "LESS_THAN_EQUAL")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName."< :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								if(in_array($keyName,$string))
									$bindArr["VALUE".$count]["TYPE"] = "STRING";
								else
									$bindArr["VALUE".$count]["TYPE"] = "INT";
								$count++;
							}
						}
					}
					$where = "WHERE ".implode(" AND ",$arr);
				}
				if($key == "LIMIT")
				{
					$limit = " LIMIT ";
					$limit = $limit.$value;
				}
				if($key == "ORDER")
				{
						
					if($value)
					{
						if ( isset($expiry) )
						{
							$order = "ORDER BY ".$value." ASC";
						}
						else
						{
							$order = "ORDER BY ".$value." DESC";
						}
					}
				}

			}

			if(is_array($skipArray))
			{
				if($select == "SENDER")
					$str = "SENDER NOT IN (";
				else
					$str = "RECEIVER NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count]["VALUE"] = $value;
					$bindArr["VALUE".$count]["TYPE"] = "INT";
					$count++;
				}
				$str = substr($str, 0, -1);
				$skipProfile = $str.")";
				if(!isset($where))
					$skipProfile = "WHERE ".$skipProfile;
				else
					$skipProfile = "AND ".$skipProfile;
			}
			$sql = "SELECT ".$select." as PROFILEID,TIME,COUNT,SEEN,FILTERED,MSG_DEL,TYPE,SENDER,RECEIVER FROM newjs.CONTACTS ".$where." ".$skipProfile." ".$order." ".$limit;
			$res=$this->db->prepare($sql);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
				{
					if($v["TYPE"] =="STRING")
					{
						$res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
					}
					else
					{
						$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
					}
				}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
				$output[$row["PROFILEID"]]["COUNT"] = $row["COUNT"];
				$output[$row["PROFILEID"]]["SEEN"] = $row["SEEN"];
				$output[$row["PROFILEID"]]["FILTERED"] = $row["FILTERED"];
				$output[$row["PROFILEID"]]["MSG_DEL"] = $row["MSG_DEL"];
				$output[$row["PROFILEID"]]["TYPE"] = $row["TYPE"];
				$output[$row["PROFILEID"]]["SENDER"] = $row["SENDER"];
				$output[$row["PROFILEID"]]["RECEIVER"] = $row["RECEIVER"];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}

		return $output;
	}


	public function getContactsDetails($profileid,$profileArray)
	{
		if(!$profileid)
			throw new jsException("","profileid is not specified in getContactsDetails() OF newjs_CONTACTS.class.php");
		try{
			$count = 0;
			foreach($profileArray as $key=>$value)
			{
				$str = $str.":VALUE".$count.",";
				$bindArr["VALUE".$count]["VALUE"] = $value;
				$bindArr["VALUE".$count]["TYPE"] = "INT";
				$count++;
			}
			$str = substr($str, 0, -1);
			$sql = "SELECT * FROM newjs.CONTACTS WHERE (SENDER = :PROFILEID AND RECEIVER IN ($str)) OR (RECEIVER = :PROFILEID AND SENDER IN ($str))";
			$res=$this->db->prepare($sql);
			$res->bindValue("PROFILEID",$profileid,PDO::PARAM_INT);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
				{
					$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
				}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
	public function getNoContactAutomationData($noOfOtherProfiles,$loggedIn,$others)
	{
		$otherKeys = array_keys($others);
		$otherStr = implode(",",$otherKeys);
		unset($otherKeys);
                $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS WHERE (SENDER =:LOGGED_IN AND RECEIVER IN (".$otherStr."))";
                $res=$this->db->prepare($sql);
                $res->bindValue(":LOGGED_IN",$loggedIn,PDO::PARAM_STR);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$details[]=$row['RECEIVER'];
		}
		foreach($others as $k=>$v)
		{
			if(!in_array($v,$details))
				$othersNew[]=$v;
		}
		$otherKeys = array_keys($othersNew);
                $otherStr = implode(",",$otherKeys);
                unset($otherKeys);
		$sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS WHERE (RECEIVER=:LOGGED_IN AND SENDER IN (".$otherStr."))";
                $res=$this->db->prepare($sql);
                $res->bindValue(":LOGGED_IN",$loggedIn,PDO::PARAM_STR);
                $res->execute();
		unset($details);
                while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$details[]=$row['SENDER'];
		}
		foreach($othersNew as $k=>$v)
		{
			if(count($othersFinal)>=$noOfOtherProfiles)
				break;
			if(!in_array($v,$details))
				$othersFinal=$v;
		}
		$finalData[$loggedIn]=$othersFinal;
                return $finalData;
	}
        public function getContactedAutomationData($loggedIn,$contactStatus,$noOfLoggedInProfiles,$noOfOtherProfiles,$senderReceiverProfiles)
        {
                $sql = "SELECT ".$loggedIn.",count(*) as count FROM newjs.CONTACTS WHERE ".$loggedIn." IN (".$senderReceiverProfiles.") AND TYPE = :CONTACTSTATUS GROUP BY ".$loggedIn." HAVING count>:NUMBER_OF_OTHER_PROFILES LIMIT :NO_OF_LOGGED_IN";
                $res=$this->db->prepare($sql);
                $res->bindValue(":CONTACTSTATUS",$contactStatus,PDO::PARAM_STR);
                $res->bindValue(":NUMBER_OF_OTHER_PROFILES",$noOfOtherProfiles,PDO::PARAM_INT);
                $res->bindValue(":NO_OF_LOGGED_IN",$noOfLoggedInProfiles*10,PDO::PARAM_INT);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
			$profiles[]=$row[$loggedIn];
		if(!is_array($profiles))
			return false;
		foreach($profiles as $k=>$v)
			$pa[] = ":PROFILEID".$k;
		$str = implode(",",$pa);
		unset($pa);
		$sqlA = "SELECT SENDER,RECEIVER  FROM newjs.CONTACTS WHERE ".$loggedIn." IN (".$str.") AND TYPE = :CONTACTSTATUS";
                $resA=$this->db->prepare($sqlA);
                $resA->bindValue(":CONTACTSTATUS",$contactStatus,PDO::PARAM_STR);
		foreach($profiles as $k=>$v)
			$resA->bindValue(":PROFILEID".$k,$v,PDO::PARAM_INT);
                $resA->execute();
                while($rowA = $resA->fetch(PDO::FETCH_ASSOC))
			$details[]=$rowA;
                return $details;
        }

	/** This store function is used to get SENDER or RECEIVER for a profile ID
         * @param $parameter : Parameters to be fetched or *
         * @param $whereArr : Array specifies whether SENDER or RECEIVER to be find out
         */
	public function contactResultInfo($parameter = "*", $whereArr) {
		try {
			if (!is_array($whereArr)) {
				throw new jsException("", "whereArr array Not Set in newjs_CONTACTS.class.php -> contactResultInfo");
			}

			if (isset($whereArr['SENDER'])) {
				$where = "SENDER = :PROFILEID";
				$profileId = $whereArr['SENDER'];
			} elseif (isset($whereArr['RECEIVER'])) {
				$where = "RECEIVER = :PROFILEID";
				$profileId = $whereArr['RECEIVER'];
			}
			$sql = "SELECT " . $parameter . " FROM newjs.CONTACTS WHERE " . $where;
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->execute();
			while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
				$result[] = $row;
			}
			return $result;
		} catch (PDOException $e) {
			throw new jsException($e);
		}
	}

	public function countAcceptances($profileid, $start_dt, $end_dt)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM newjs.`CONTACTS` WHERE (`SENDER`=:PROFILEID OR `RECEIVER`=:PROFILEID) AND `TYPE`='A' AND `TIME`>=:START_DT AND TIME<=:END_DT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
			$prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
			$prep->execute();
			$row = $prep->fetch(PDO::FETCH_ASSOC);
			$res = $row['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $res;
	}

        public function countAcceptancesReceived($profileid, $start_dt, $end_dt)
        {
                try{
                        $sql = "SELECT COUNT(*) AS CNT FROM newjs.`CONTACTS` WHERE `SENDER`=:PROFILEID AND `TYPE`='A' AND `TIME`>=:START_DT AND TIME<=:END_DT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        $row = $prep->fetch(PDO::FETCH_ASSOC);
                        $res = $row['CNT'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }

        public function updateContactSeen($profileid,$type)
        {
        	try{
        		if($type == ContactHandler::INITIATED){
        			$SENDER_RECEIVER = "RECEIVER";
        			$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE ".$SENDER_RECEIVER." = :PROFILEID and TYPE = :TYPE AND FILTERED !='Y' and (`SEEN` != 'Y')" ;
				}
        		elseif($type == ContactHandler::ACCEPT){
        			$SENDER_RECEIVER = "SENDER";
        			$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE ".$SENDER_RECEIVER." = :PROFILEID and TYPE = :TYPE and (`SEEN` != 'Y')";
				}
        		elseif($type == ContactHandler::FILTERED){
					$SENDER_RECEIVER = "RECEIVER";
					$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE ".$SENDER_RECEIVER." = :PROFILEID and TYPE = :TYPE AND FILTERED='Y' and (`SEEN` != 'Y')";
				}					
        		elseif($type == ContactHandler::DECLINE){
					$SENDER_RECEIVER = "SENDER";
					$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE ".$SENDER_RECEIVER." = :PROFILEID and TYPE = :TYPE and (`SEEN` != 'Y')";
				}					
        		
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue("PROFILEID",$profileid, PDO::PARAM_INT);
        		
        		if($type == ContactHandler::FILTERED)
					$prep->bindValue("TYPE",ContactHandler::INITIATED,PDO::PARAM_STR);
				else
					$prep->bindValue("TYPE",$type,PDO::PARAM_STR);
					
        		$prep->execute();
        	}
        	catch(Execption $e){
        		throw new jsException($e);
        	}
        }

        public function getInterestReceivedDataForDuration($profileid, $stTime, $endTime){
            try{
                $ignoredStr = '';
                $sql = "SELECT * from newjs.CONTACTS WHERE RECEIVER = :RECEIVER AND TYPE = 'I' AND TIME >= :START_TIME AND TIME <= :END_TIME ORDER BY TIME ASC";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":RECEIVER",$profileid,PDO::PARAM_INT);
                $prep->bindValue(":START_TIME",$stTime,PDO::PARAM_STR);
                $prep->bindValue(":END_TIME",$endTime,PDO::PARAM_STR);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    $result['SENDER'][$row['SENDER']] = 1;
                    $result['SELF'] = $profileid;
                    $ignoredStr.=$row['SENDER'].",";
                }
                if($ignoredStr){
                    $result['IGNORED_STRING'] = rtrim($ignoredStr, ",");
                }
                return $result;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
        
        public function getInterestSentForDuration($stTime, $endTime,$remainderArray){
            try{
            	
                $sql = "SELECT * from newjs.CONTACTS WHERE `COUNT`=1 AND MSG_DEL!='Y' AND TYPE = 'I' AND `TIME` >= :START_TIME AND `TIME` <= :END_TIME AND SENDER % :DIVISOR = :REMAINDER AND SENDER % 3 = :SHARDREM  ORDER BY `TIME` DESC  ";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":START_TIME",$stTime,PDO::PARAM_STR);
                $prep->bindValue(":END_TIME",$endTime,PDO::PARAM_STR);
                $prep->bindValue(":DIVISOR",$remainderArray['divisor'],PDO::PARAM_INT);
       
                $prep->bindValue(":REMAINDER",$remainderArray['remainder'],PDO::PARAM_INT);               
                $prep->bindValue(":SHARDREM",$remainderArray['shardRemainder'],PDO::PARAM_INT);               
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $result[]=$row;                
                }
                return $result;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
		
		 public function updateCancelSeen($profileid)
        {
        	try{
					$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE RECEIVER = :PROFILEID and (TYPE = 'C' or TYPE = 'E') and (`SEEN` != 'Y') ";
								
        		
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
        		
					
        		$prep->execute();
        	}
        	catch(Execption $e){
        		throw new jsException($e);
        	}
        }

        public function getContactsExpiring($serverId, $chunkStr)
		{
			try
			{
				$sql = "SELECT RECEIVER,count(*) as count, GROUP_CONCAT( SENDER ORDER BY TIME  SEPARATOR ',' ) AS SENDER from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TYPE='I' ".$chunkStr." and FILTERED<>'Y' and DATEDIFF(NOW( ) , `TIME` ) <= ".CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT." AND DATEDIFF(NOW( ) ,  `TIME` ) >= ".CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT." AND RECEIVER=PROFILEID AND SERVERID=:SERVERID group by RECEIVER order by TIME";
				$res = $this->db->prepare($sql);
				$res->bindValue(":SERVERID",$serverId,PDO::PARAM_INT);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$result[] = $row;
				}
				return $result;
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
		}


    	public function setGroupContact()
    	{
    		try
    		{
	    		$sql = "SET SESSION group_concat_max_len = 1000000;";
	    		$res = $this->db->prepare($sql);
	            $res->execute();
    		}
    		catch(PDOException $e)
    		{
    			throw new jsException($e);	
    		}
    	}

    public function isRBContact($sender,$receiver)
	{
                if(!$sender || !$receiver)
                        throw new jsException("","PROFILEID IS BLANK IN newjs_CONTACTS.class.php");
		try
		{
			$sql = "SELECT count(*) as CNT from newjs.CONTACTS WHERE SENDER =:SENDER AND RECEIVER = :RECEIVER and MSG_DEL =:MSG_DEL";
			$res=$this->db->prepare($sql);
			$res->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
			$res->bindValue(":SENDER",$sender,PDO::PARAM_INT);
			$res->bindValue(":MSG_DEL","Y",PDO::PARAM_STR);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$val = $row['CNT'];
			return $val;
		}
		catch (PDOException $e)
                {
                        throw new jsException($e);
                }

	}	
    
    /**
     * 
     * @param type $senderId
     * @param type $startDate
     */
    public function getCountOfContactInitiated($senderId, $date)
    {
      if(!($senderId)) {
        throw new jsException("","PROFILEID IS BLANK IN getCountOfContactInitiated() OF newjs.CONTACTS.class.php");
      }
      
      if(!($date)) {
        throw new jsException("","Date IS BLANK IN getCountOfContactInitiated() OF newjs.CONTACTS.class.php");
      }
      try{
        $sql = "SELECT COUNT(*) AS CNT FROM newjs.CONTACTS WHERE SENDER = :SENDER AND TIME >= :TIME";
        $res=$this->db->prepare($sql);

        $res->bindValue(":SENDER", $senderId, PDO::PARAM_INT);
        $res->bindValue(":TIME", $date, PDO::PARAM_STR);
        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $val = $row['CNT'];
        return $val;
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
    /**
     * 
     * @param type $receiverId
     * @param type $date
     * @return type
     * @throws jsException
     */
    public function getCountOfContactAccepted($receiverId, $date)
    {
      if(!($receiverId)) {
        throw new jsException("","PROFILEID IS BLANK IN getCountOfContactAccepted() OF newjs.CONTACTS.class.php");
      }
      
      if(!($date)) {
        throw new jsException("","Date IS BLANK IN getCountOfContactInitiated() OF newjs.CONTACTS.class.php");
      }
      
      try{
        $sql = "SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER=:RECEIVER and TYPE='A' AND TIME >= :TIME";
        $res=$this->db->prepare($sql);

        $res->bindValue(":RECEIVER", $receiverId, PDO::PARAM_INT);
        $res->bindValue(":TIME", $date, PDO::PARAM_STR);
        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $val = $row['CNT'];
        return $val;
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }

    public function getRbInterestSentForDuration($interestTime,$remainderArray){
            try{
            	
                $sql = "SELECT * from newjs.CONTACTS WHERE `COUNT`<3 AND MSG_DEL!='Y' AND TYPE = 'I' AND `TIME` = :INTEREST_TIME AND SENDER % :DIVISOR = :REMAINDER AND SENDER % 3 = :SHARDREM  AND MSG_DEL='Y' ORDER BY `TIME` DESC  ";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":INTEREST_TIME",$interestTime,PDO::PARAM_STR);
               	$prep->bindValue(":DIVISOR",$remainderArray['divisor'],PDO::PARAM_INT);
                $prep->bindValue(":REMAINDER",$remainderArray['remainder'],PDO::PARAM_INT);               
                $prep->bindValue(":SHARDREM",$remainderArray['shardRemainder'],PDO::PARAM_INT);               
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $result[]=$row;                
                }
                return $result;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
}
?>
