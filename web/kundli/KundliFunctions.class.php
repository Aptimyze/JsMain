<?php
class KundliFunctions
{
	private $db;
        private $mysqlObj;
        private $receiverId;
        private $tempIds;

        function __construct($db,$mysqlObj,$receiverId)
        {
                $this->db = $db;
                $this->mysqlObj = $mysqlObj;
                $this->receiverId = $receiverId;
                $this->tempIds = array();
        }

	public function fetchMatchIds($limit)
        {
                if (count($this->tempIds))
                        $whereStatement = "AND MATCHID NOT IN (".implode(",",$this->tempIds).")";
                $select_statement = "SELECT MATCHID FROM kundli_alert.API_OUTPUT WHERE PROFILEID = ".$this->receiverId." AND STATUS = \"A\" ".$whereStatement." ORDER BY VENUS DESC , MARS DESC , GUNA DESC,ENTRY_DT DESC LIMIT ".$limit;
                $result = $this->mysqlObj->executeQuery($select_statement,$this->db) or $this->mysqlObj->logError($select_statement);
                while ($row = $this->mysqlObj->fetchArray($result))
                {
                        $matchingIds[] = $row["MATCHID"];
                }
                if (count($matchingIds))
                        $this->tempIds = array_merge($this->tempIds,$matchingIds);
                return $matchingIds;
        }

	public function handleAstroAPIOutput($ApiOutput)
	{
		$ApiOutput = trim($ApiOutput);
		if ($ApiOutput)
		{
			$ApiOutputArr = explode("<br/>",$ApiOutput);
			foreach($ApiOutputArr as $k=>$v)
			{
				if (strpos($v,"true"))
					$matchesArr[] = $v;
			}

			/*No more tracking required	
			if($matchesArr)	
				$this->trackApiOutput(count($ApiOutputArr)-2,count($matchesArr));		//TRACK API OUTPUT
			else
				$this->trackApiOutput(count($ApiOutputArr)-2,0);		//TRACK API OUTPUT
			*/

			unset($ApiOutputArr);
			if ($matchesArr)
			{
				foreach($matchesArr as $k=>$v)
				{
					$tempArr[] = explode(" ",$v);
				}
				if ($tempArr)
				{
					foreach($tempArr as $k=>$v)
					{
						$requiredMatchesArr[$k]["PROFILEID"] = $this->receiverId;
						foreach ($v as $kk=>$vv)
						{
							$tempVar1 = trim($vv);
							if ($tempVar1 && $tempVar1!="true")
							{
								$tempVar2 = explode(":",$tempVar1);
								if ($kk == 0)
									$requiredMatchesArr[$k]["MATCHID"] = trim($tempVar2[0]);
								else
									$requiredMatchesArr[$k][trim($tempVar2[0])] = trim($tempVar2[1]);
							}
						}
						if ($requiredMatchesArr[$k])
						{
							$requiredMatchesArr[$k]["STATUS"] = "\"A\"";
						}
					}
				}
			}
		}

		if($requiredMatchesArr)
		{
			foreach($requiredMatchesArr as $k=>$v)
                	{	
				$index = count($requiredMatchesArr)-$k;
                        	$scoreArr[$k] = $this->getAstroScore($v["Ve"],$v["Ma"],$v["Guna"],$index);
                	}

			asort($scoreArr);
                	$sortedArr = array_reverse($scoreArr,true);
                	$count = 0;

			foreach($sortedArr as $k=>$v)
                	{
                	        $finalArr[] = implode(",",$requiredMatchesArr[$k]);
                	        $count++;
                	        if ($count==30)
                	                break;
                	}
		}
	
		if ($finalArr)
			return $finalArr;
		else
			return 0;
	}

	public function getAstroScore($ve,$ma,$guna,$index)
        {
                if($ve == 1 && $ma == 1)
                        $score = 9;
                else if($ve == 1 && $ma == 0)
                        $score = 8;
                else if($ve == 1 && $ma == -1)
                        $score = 7;
                else if($ve == 0 && $ma == 1)
                        $score = 6;
                else if($ve == 0 && $ma == 0)
                        $score = 5;
                else if($ve == 0 && $ma == -1)
                        $score = 4;
                else if($ve == -1 && $ma == 1)
                        $score = 3;
                else if($ve == -1 && $ma == 0)
                        $score = 2;
                else if($ve == -1 && $ma == -1)
                        $score = 1;
                $score = $score*10000000 + $guna*100000+ $index;
                return $score;
        }


	public function performDbAction($matchArr,$table)
	{
		$tableArr = explode(" ",$table);
		$table = $tableArr[0];
		unset($tableArr);

		$insert_statement = "REPLACE INTO kundli_alert.API_OUTPUT(PROFILEID,MATCHID,GUNA,LAGNA,SUN,MERCURY,JUPITER,SATURN,MARS,VENUS,STATUS) VALUES ";
		foreach ($matchArr as $k=>$v)
		{
			$insert_statement = $insert_statement."(".$v."),";
		}	
		$insert_statement = rtrim($insert_statement,",");
		$this->mysqlObj->executeQuery($insert_statement,$this->db) or die($insert_statement);

		$update_statement = "UPDATE kundli_alert.API_OUTPUT a SET ENTRY_DT = (SELECT sf.ENTRY_DT AS ENTRY_DT FROM ".$table." sf WHERE sf.PROFILEID=a.MATCHID) WHERE a.PROFILEID = ".$this->receiverId." AND a.STATUS = \"A\"";
		$this->mysqlObj->executeQuery($update_statement,$this->db) or $this->mysqlObj->logError($update_statement);
	}

	public function fetchAstroDetails($profileSet,$gender)
	{
		$profileIdStr = implode(",",$profileSet).",".$this->receiverId;
		$matchString = "";
		if ($gender == "F")
		{
			$match_gender = 2;
			$receiver_gender = 1;
		}
		else
		{
			$match_gender = 1;
			$receiver_gender = 2;
		}

		$statement = "SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN (".$profileIdStr.") ORDER BY FIELD (PROFILEID,".$profileIdStr.")";	
		$result = $this->mysqlObj->executeQuery($statement,$this->db) or die($statement);
		while ($row = $this->mysqlObj->fetchArray($result))
		{
			if ($row["PROFILEID"] == $this->receiverId)
			{
				$nativeString = $row["PROFILEID"].":".$receiver_gender.":".$row['LAGNA_DEGREES_FULL'].":".$row['SUN_DEGREES_FULL'].":".$row['MOON_DEGREES_FULL'].":".$row['MARS_DEGREES_FULL'].":".$row['MERCURY_DEGREES_FULL'].":".$row['JUPITER_DEGREES_FULL'].":".$row['VENUS_DEGREES_FULL'].":".$row['SATURN_DEGREES_FULL'];
			}
			else
			{
        			$matchString = $matchString.$row["PROFILEID"].":".$match_gender.":".$row['LAGNA_DEGREES_FULL'].":".$row['SUN_DEGREES_FULL'].":".$row['MOON_DEGREES_FULL'].":".$row['MARS_DEGREES_FULL'].":".$row['MERCURY_DEGREES_FULL'].":".$row['JUPITER_DEGREES_FULL'].":".$row['VENUS_DEGREES_FULL'].":".$row['SATURN_DEGREES_FULL']."@";
			}
		}
		$param = $nativeString."&".$matchString;
		return $param;
	}

	public function updateDate($main_table,$topId,$bottomId,$kundli_paid,$start_dt,$end_dt)
	{
		if($topId && $bottomId)
		{
			$statement = "SELECT PROFILEID,ASTRO_ENTRY_DT FROM ".$main_table." WHERE PROFILEID IN (".$topId.",".$bottomId.")";
             		$result = $this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
              		while($row = $this->mysqlObj->fetchArray($result))
            		{
                    		$updateArr[$row["PROFILEID"]] = $row["ASTRO_ENTRY_DT"];
           		}
	
               		if($start_dt && $end_dt)
             		{
                    		if(JSstrToTime($start_dt)>JSstrToTime($updateArr[$topId]))
                        	      	$update_start_dt = $start_dt;
                     		else
                        	    	$update_start_dt = $updateArr[$topId];

                      		if(JSstrToTime($end_dt)>JSstrToTime($updateArr[$bottomId]))
                              		$update_end_dt = $updateArr[$bottomId];
                    		else
                             		$update_end_dt = $end_dt;
            		}
              		else
           		{
                	   	$update_start_dt = $updateArr[$topId];
                	     	$update_end_dt = $updateArr[$bottomId];
           		}
		}
		else
		{
			$update_start_dt = "";
                       	$update_end_dt = "";
		}

              		if($kundli_paid)
                    		$table = "kundli_alert.KUNDLI_RECEIVER_PAID";
              		else
                    		$table = "kundli_alert.KUNDLI_RECEIVER_UNPAID";

            	$update_statement = "UPDATE ".$table." SET START_DT = \"".$update_start_dt."\", END_DT = \"".$update_end_dt."\" WHERE PROFILEID = ".$this->receiverId;
           	$this->mysqlObj->executeQuery($update_statement,$this->db) or $this->mysqlObj->logError($update_statement);
	}

	public function insertIntoMailerTable($finalIds,$kundli_paid)
	{
		if($kundli_paid)
			$table_name = "kundli_alert.MAILER_PAID";
		else
			$table_name = "kundli_alert.MAILER_UNPAID";

		$insert_statement = "REPLACE INTO ".$table_name."(PROFILEID,MATCHID,GUNA,LAGNA,SUN,MERCURY,JUPITER,SATURN,MARS,VENUS,ENTRY_DT) SELECT PROFILEID,MATCHID,GUNA,LAGNA,SUN,MERCURY,JUPITER,SATURN,MARS,VENUS,ENTRY_DT FROM kundli_alert.API_OUTPUT WHERE PROFILEID = ".$this->receiverId." AND MATCHID IN (".implode(",",$finalIds).") ORDER BY FIELD (MATCHID,".implode(",",$finalIds).")";
                $this->mysqlObj->executeQuery($insert_statement,$this->db) or $this->mysqlObj->logError($insert_statement);
	}

	public function updateTable()
        {
                if(count($this->tempIds))
                {
                        $statement = "UPDATE kundli_alert.API_OUTPUT SET STATUS = \"N\" WHERE MATCHID IN (".implode(",",$this->tempIds).") AND PROFILEID = ".$this->receiverId;
                        $this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
                }
        }

	public function trackApiOutput($count1,$count2)
	{
		$statement = "INSERT INTO kundli_alert.TRACK_API_OUTPUT(PROFILEID,MATCHES_SENT,MATCHES_TRUE,DATE) VALUES (\"".$this->receiverId."\",".$count1.",".$count2.",NOW())";
		$this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
	}

	public function unsetTempId()
	{
		$this->tempIds = array();
	}
}
?>
