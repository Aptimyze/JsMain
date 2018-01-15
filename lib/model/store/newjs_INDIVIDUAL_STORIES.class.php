<?php
class newjs_INDIVIDUAL_STORIES extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
        //functions for innodb transactions
	    public function startTransaction()
	    {
			$this->db->beginTransaction();
		}
		
		public function commitTransaction()
		{
			$this->db->commit();
		}       	
        
		public function getStories($searchParam)
        {
			try 
			{
				if(isset($searchParam))
				{ 
					if($searchParam->getParentType() != '')
					{
						$whereSql = $searchParam->getParentType()."= :".$searchParam->getParentType();
						if($searchParam->getMappedType() != '')
							$whereSql .= " AND ".$searchParam->getMappedType()."= :".$searchParam->getMappedType();
					}
					else
						$whereSql = "Year = :Year";
					
					$sql="SELECT SID,NAME1,NAME2,HEADING,STORY,FRAME_PIC_URL,MAIN_PIC_URL,SQUARE_PIC_URL  FROM newjs.INDIVIDUAL_STORIES WHERE $whereSql AND STATUS='A' ORDER BY SID DESC";
					$prep=$this->db->prepare($sql);
					
					if($searchParam->getParentType() != '')
					{
						$prep->bindValue(":".$searchParam->getParentType(),$searchParam->getParentValue(),PDO::PARAM_INT);
						if($searchParam->getMappedType() != '')
							$prep->bindValue(":".$searchParam->getMappedType(),$searchParam->getMappedValue(),PDO::PARAM_INT);
					}
					else
						$prep->bindValue(":Year",$searchParam->getYear(),PDO::PARAM_INT);
						
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					return $res;
				}	
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
		public function getCompleteStory($year)
		{
			try
			{
				if($year)
				{
					$sql="SELECT SID FROM INDIVIDUAL_STORIES WHERE YEAR =:YEAR AND STATUS='A' ORDER BY SID DESC";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result[SID];
					}
					return $res;
				}
			}
			catch(PDOException $e)
			{
					
				throw new jsException($e);
			}
		}

		public function getStoryCountForYear($year){
			try
			{
				if($year)
				{
					$sql="SELECT COUNT(*) AS CNT FROM INDIVIDUAL_STORIES WHERE YEAR =:YEAR AND STATUS='A'";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					$result = $prep->fetch(PDO::FETCH_ASSOC);
					$res = $result['CNT'];
					return $res;
				}
			}
			catch(PDOException $e)
			{
					
				throw new jsException($e);
			}	
		}
		
		public function getCompleteStoryDetail($sid)
		{
			try
			{
				if($sid)
				{
					$sql="SELECT SID,NAME1,NAME2,YEAR,HEADING,STORY,MAIN_PIC_URL,SQUARE_PIC_URL FROM INDIVIDUAL_STORIES WHERE SID= :SID AND STATUS='A'";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":SID",$sid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res= $result;
					}
					return $res;
				}
			}
			catch(PDOException $e)
			{
					
				throw new jsException($e);
			}
		}
		
		public function ReplaceRecord($iObj)
		{
			
			try{
				$sql="REPLACE INTO newjs.INDIVIDUAL_STORIES(STORYID,NAME1,NAME2,CASTE,RELIGION,CITY,COUNTRY,OCCUPATION,MTONGUE,HEADING,STORY,STATUS,YEAR) values(:STORYID,:NAME1,:NAME2,:CASTE,:RELIGION,:CITY,:COUNTRY,:OCCUPATION,:MTONGUE,:HEADING,:STORY,:STATUS,:YEAR)";
				$prep = $this->db->prepare($sql);
				
				
				$prep->bindValue(":STORYID",$iObj->getSTORYID(),PDO::PARAM_INT);
				$prep->bindValue(":NAME1",$iObj->getNAME1(),PDO::PARAM_STR);
				$prep->bindValue(":NAME2",$iObj->getNAME2(),PDO::PARAM_STR);
				$prep->bindValue(":CASTE",$iObj->getCASTE(),PDO::PARAM_INT);
				$prep->bindValue(":RELIGION",$iObj->getRELIGION(),PDO::PARAM_INT);
				$prep->bindValue(":CITY",$iObj->getCITY(),PDO::PARAM_STR);
				$prep->bindValue(":COUNTRY",$iObj->getCOUNTRY(),PDO::PARAM_INT);
				$prep->bindValue(":OCCUPATION",$iObj->getOCCUPATION(),PDO::PARAM_INT);
				$prep->bindValue(":MTONGUE",$iObj->getMTONGUE(),PDO::PARAM_INT);
				$prep->bindValue(":HEADING",$iObj->getHEADING(),PDO::PARAM_STR);
				$prep->bindValue(":STORY",$iObj->getSTORY(),PDO::PARAM_STR);
				$prep->bindValue(":STATUS",$iObj->getSTATUS(),PDO::PARAM_STR);
				$prep->bindValue(":YEAR",$iObj->getYEAR(),PDO::PARAM_INT);
				$prep->execute();
				$iObj->setSID($this->db->lastInsertId());
				
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function UpdateRecord($iObj)
		{
			
			try{
				$sql="UPDATE newjs.INDIVIDUAL_STORIES set STORYID=:STORYID,NAME1=:NAME1,NAME2=:NAME2,CASTE=:CASTE,RELIGION=:RELIGION,CITY=:CITY,COUNTRY=:COUNTRY,OCCUPATION=:OCCUPATION,MTONGUE=:MTONGUE,HEADING=:HEADING,STORY=:STORY,STATUS=:STATUS,YEAR=:YEAR,HOME_PIC_URL=:HOME_PIC_URL,MAIN_PIC_URL=:MAIN_PIC_URL,FRAME_PIC_URL=:FRAME_PIC_URL,SQUARE_PIC_URL=:SQUARE_PIC_URL WHERE SID=:SID";
				$prep = $this->db->prepare($sql);
				
				
				$prep->bindValue(":SID",$iObj->getSID(),PDO::PARAM_INT);
				$prep->bindValue(":STORYID",$iObj->getSTORYID(),PDO::PARAM_INT);
				$prep->bindValue(":NAME1",$iObj->getNAME1(),PDO::PARAM_STR);
				$prep->bindValue(":NAME2",$iObj->getNAME2(),PDO::PARAM_STR);
				$prep->bindValue(":CASTE",$iObj->getCASTE(),PDO::PARAM_INT);
				$prep->bindValue(":RELIGION",$iObj->getRELIGION(),PDO::PARAM_INT);
				$prep->bindValue(":CITY",$iObj->getCITY(),PDO::PARAM_STR);
				$prep->bindValue(":COUNTRY",$iObj->getCOUNTRY(),PDO::PARAM_INT);
				$prep->bindValue(":OCCUPATION",$iObj->getOCCUPATION(),PDO::PARAM_INT);
				$prep->bindValue(":MTONGUE",$iObj->getMTONGUE(),PDO::PARAM_INT);
				$prep->bindValue(":HEADING",$iObj->getHEADING(),PDO::PARAM_STR);
				$prep->bindValue(":STORY",$iObj->getSTORY(),PDO::PARAM_STR);
				$prep->bindValue(":STATUS",$iObj->getSTATUS(),PDO::PARAM_STR);
				$prep->bindValue(":YEAR",$iObj->getYEAR(),PDO::PARAM_INT);
				$prep->bindValue(":HOME_PIC_URL",$iObj->getHOME_PIC_URL(),PDO::PARAM_STR);
				$prep->bindValue(":MAIN_PIC_URL",$iObj->getMAIN_PIC_URL(),PDO::PARAM_STR);
				$prep->bindValue(":FRAME_PIC_URL",$iObj->getFRAME_PIC_URL(),PDO::PARAM_STR);
				$prep->bindValue(":SQUARE_PIC_URL",$iObj->getSQUARE_PIC_URL(),PDO::PARAM_STR);
				$prep->execute();
				
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function getMaxID()
		{
			try 
			{		
				$sql="SELECT MAX(SID) AS SID FROM newjs.INDIVIDUAL_STORIES";
				$prep=$this->db->prepare($sql);
				$prep->execute();
				$result = $prep->fetch(PDO::FETCH_ASSOC);
				return $result['SID'];
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}			
		}
		public function getPictureInfoByStoryId($storyId)
		{
			try {
				$sql="SELECT SID,HEADING,STORY,HOME_PIC_URL,MAIN_PIC_URL,FRAME_PIC_URL,SQUARE_PIC_URL FROM newjs.INDIVIDUAL_STORIES WHERE STORYID=:STORYID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":STORYID",$storyId,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
						return $result;
				}
				return NULL;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function getPictureInfoBySID($sid)
		{
			try {
				$sql="SELECT STATUS,NAME1,NAME2,CASTE,RELIGION,CITY,COUNTRY,OCCUPATION,MTONGUE,SID,STORYID,HEADING,STORY,HOME_PIC_URL,MAIN_PIC_URL,FRAME_PIC_URL,SQUARE_PIC_URL,YEAR FROM newjs.INDIVIDUAL_STORIES WHERE SID=:SID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":SID",$sid,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
						return $result;
				}
				return NULL;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
			
		}
		
		public function updateStatus($status,$sid)
		{
			try
			{
				$sql = "UPDATE newjs.INDIVIDUAL_STORIES SET STATUS=:STATUS WHERE SID=:SID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
				$prep->bindValue(":SID",$sid,PDO::PARAM_INT);
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
            	jsException::log($e);
            }
		}
		public function updateStatusbyStoryId($status,$id)
		{
			try
			{
				$sql = "UPDATE newjs.INDIVIDUAL_STORIES SET STATUS=:STATUS WHERE STORYID=:STORYID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
				$prep->bindValue(":STORYID",$id,PDO::PARAM_INT);
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
            	jsException::log($e);
            }
		}
		public function RemovePhoto()
		{
			try
			{
				$sql = "UPDATE newjs.INDIVIDUAL_STORIES SET HOME_PIC_URL='',MAIN_PIC_URL='',FRAME_PIC_URL='',SQUARE_PIC_URL='' WHERE SID=:SID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":SID",$sid,PDO::PARAM_INT);
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
            	jsException::log($e);
            }
		}
		
		public function getRightPanelStory()
        {
			try 
			{
					$sql="SELECT SID,NAME1,YEAR,NAME2,HOME_PIC_URL,MAIN_PIC_URL,FRAME_PIC_URL,SQUARE_PIC_URL,STORY FROM newjs.INDIVIDUAL_STORIES,SUCCESS_POOL WHERE STORYID=ID_POOL AND CURRENT_LIVE='Y' order by SID limit 4";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					return $res;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
	
	/*
        This function is used to update records on newjs.INDIVIDUAL_STORIES table
        @param - parameter array where index is the column name to up updated and value has the values of the column to be updated, id on which update takes place
        @return - true/false
        */
        public function edit($paramArr=array(),$Id)
        {
                try
                {
                        foreach($paramArr as $key=>$val)
                        {
                                $set[] = $key." = :".$key;
                        }
                        $setValues = implode(",",$set);

                        $sql = "UPDATE newjs.INDIVIDUAL_STORIES SET $setValues WHERE SID = :ID";
                        $res = $this->db->prepare($sql);
                        foreach($paramArr as $key=>$val)
                        {
                                $res->bindValue(":".$key, $val);
                        }
                        $res->bindValue(":ID",$Id,PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return false;
        }
}
?>
