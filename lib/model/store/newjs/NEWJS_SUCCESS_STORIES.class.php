<?php
class NEWJS_SUCCESS_STORIES extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function fetchProfile()
        {
			try
			{
					$sql="SELECT SQL_CACHE ID,USERNAME,NAME_H,NAME_W,WEDDING_DATE,CONTACT_DETAILS,SEND_EMAIL,EMAIL,COMMENTS,USERNAME_H,USERNAME_W,PIC_URL,DATETIME FROM newjs.SUCCESS_STORIES WHERE UPLOADED='N' ORDER BY DATETIME ASC LIMIT 1";
					$prep = $this->db->prepare($sql);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[] = $result;
					}
						return $res;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function fetchProfileWith($userNameH,$userNameW,$email,$sendEmail,$id)
		{
			try
			{
				$sql="select ID,PIC_URL,UPLOADED from newjs.SUCCESS_STORIES where ((USERNAME_H= :USERNAME_H AND USERNAME_H is not null AND USERNAME_H!='') or( USERNAME_W= :USERNAME_W AND USERNAME_W is not null AND USERNAME_W!='') or (EMAIL= :EMAIL  AND EMAIL is not null AND EMAIL!='') or (SEND_EMAIL= :SEND_EMAIL  AND SEND_EMAIL is not null AND SEND_EMAIL!='')) AND  ID!= :ID and UPLOADED='A'";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":USERNAME_H",$userNameH,PDO::PARAM_STR);
				$prep->bindValue(":USERNAME_W",$userNameW,PDO::PARAM_STR);
				$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
				$prep->bindValue(":SEND_EMAIL",$sendEmail,PDO::PARAM_STR);
				$prep->bindValue(":ID",$id,PDO::PARAM_INT);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[] = $result;
				}
				return $res;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
			
        	
		public function fetchSkippedProfiles()
        {
			try 
			{
				$sql="SELECT ID,NAME_H,NAME_W,USERNAME_H,USERNAME_W,WEDDING_DATE,SKIP_COMMENTS,DATETIME FROM newjs.SUCCESS_STORIES WHERE UPLOADED='S' ORDER BY DATETIME";
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
		public function fetchStoryDetail($whereArr)
        {
			try 
			{
				foreach($whereArr as $key=>$val)
				$whereClause = "$key='$val' AND";
				$whereClause=substr($whereClause,0,strlen($whereClause)-3);
				$sql="SELECT ID,COMMENTS,DATETIME,EMAIL,EMAIL_W,WEDDING_DATE,CONTACT_DETAILS,SEND_EMAIL,PIC_URL,USERNAME_H,USERNAME_W,NAME_H,NAME_W,UPLOADED FROM newjs.SUCCESS_STORIES WHERE $whereClause";

				
				$prep=$this->db->prepare($sql);
				$prep->execute();
				while($row = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$result[] = $row;
				}
				return  $result;
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}	
		
		public function updateUploaded($uploaded,$id)
        {
			try 
			{
				$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED=:UPLOADED WHERE ID=:ID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":UPLOADED",$uploaded,PDO::PARAM_STR);
				$prep->bindValue(":ID",$id,PDO::PARAM_INT);
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
		
		public function fetchContactDetails($id)
		{
			try 
			{
				$sql = "SELECT SEND_EMAIL,EMAIL,CONTACT_DETAILS FROM newjs.SUCCESS_STORIES WHERE ID=:ID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":ID",$id,PDO::PARAM_INT);
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
		
		public function getDateTime($sid)
		{
			try 
			{
				$sql = "SELECT newjs.SUCCESS_STORIES.DATETIME FROM newjs.SUCCESS_STORIES,newjs.INDIVIDUAL_STORIES WHERE  newjs.INDIVIDUAL_STORIES.SID=:SID AND newjs.INDIVIDUAL_STORIES.STORYID=newjs.SUCCESS_STORIES.ID";
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
		
		public function getMoreStory()
		{
			try
			{
				$sql = "SELECT SQL_CACHE A.PROFILEID FROM newjs.PROFILE_DEL_REASON A LEFT JOIN newjs.SUCCESS_STORIES B ON A.USERNAME = B.USERNAME WHERE DEL_REASON = 1 AND B.USERNAME IS NULL ORDER BY B.ID DESC limit 500";
				$prep = $this->db->prepare($sql);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[] = $result[PROFILEID];
				}
				return $res;
			}
			catch(PDOException $e)
			{
					
				throw new jsException($e);
			}
		}

		public function getCount($username)
		{
			try{
				$sql="SELECT COUNT(*) AS CNT  FROM newjs.SUCCESS_STORIES WHERE USERNAME = :USERNAME and PHOTO<>''";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
				$result = $prep->fetch(PDO::FETCH_ASSOC);
				return $result[CNT];
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
		public function ReplaceRecord($sObj)
		{
			
			try{
				$sql="REPLACE INTO newjs.SUCCESS_STORIES(`NAME_H`,`NAME_W`,`NAME`,`USERNAME`,`WEDDING_DATE`,`CONTACT_DETAILS`,`EMAIL`,`EMAIL_W`,`COMMENTS`,`DATETIME`,`USERNAME_H`,`USERNAME_W`,`PIC_URL`,`UPLOADED`,`SEND_EMAIL`,`SKIP_COMMENTS`) values(:name_h,:name_w,:name,:username,:date,:contact_address,:EMAIL,:EMAIL1,:ss_story,:now,:username_h,:username_w,'',:uploaded,:send_email,:skipcomments)";
				$prep = $this->db->prepare($sql);
				
				
				$prep->bindValue(":name_h",$sObj->getNAME_H(),PDO::PARAM_STR);
				$prep->bindValue(":name_w",$sObj->getNAME_W(),PDO::PARAM_STR);
				$prep->bindValue(":name",$sObj->getNAME(),PDO::PARAM_STR);
				$prep->bindValue(":username",$sObj->getUSERNAME(),PDO::PARAM_STR);
				$prep->bindValue(":date",$sObj->getWEDDING_DATE(),PDO::PARAM_STR);
				$prep->bindValue(":contact_address",$sObj->getCONTACT_DETAILS(),PDO::PARAM_STR);
				$prep->bindValue(":EMAIL",$sObj->getEMAIL(),PDO::PARAM_STR);
				$prep->bindValue(":EMAIL1",$sObj->getEMAIL_W(),PDO::PARAM_STR);
				$prep->bindValue(":ss_story",$sObj->getCOMMENTS(),PDO::PARAM_STR);
				$prep->bindValue(":username_h",$sObj->getUSERNAME_H(),PDO::PARAM_STR);
				$prep->bindValue(":username_w",$sObj->getUSERNAME_W(),PDO::PARAM_STR);
				$prep->bindValue(":now",$sObj->getDATETIME(),PDO::PARAM_STR);
				$prep->bindValue(":uploaded",$sObj->getUPLOADED(),PDO::PARAM_STR);
				$prep->bindValue(":send_email",$sObj->getSEND_EMAIL(),PDO::PARAM_STR);
				$prep->bindValue(":skipcomments",$sObj->getSKIP_COMMENTS(),PDO::PARAM_STR);
				$prep->execute();
				$sObj->setID($this->db->lastInsertId());
				
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function UpdateRecord($sObj)
		{
			
			$sql="update newjs.SUCCESS_STORIES set `NAME_H`=:NAME_H,`NAME_W`=:NAME_W,`NAME`=:NAME,`USERNAME`=:USERNAME,`WEDDING_DATE`=:WEDDING_DATE,`CONTACT_DETAILS`=:CONTACT_DETAILS,`EMAIL`=:EMAIL,`EMAIL_W`=:EMAIL_W,`COMMENTS`=:COMMENTS,`DATETIME`=:DATETIME,`USERNAME_H`=:USERNAME_H,`USERNAME_W`=:USERNAME_W,`PIC_URL`=:PIC_URL,`UPLOADED`=:UPLOADED,`SEND_EMAIL`=:SEND_EMAIL,`SKIP_COMMENTS`=:SKIP_COMMENTS  where ID=:ID";
				$prep = $this->db->prepare($sql);
				
				
				$prep->bindValue(":ID",$sObj->getID(),PDO::PARAM_INT);
				$prep->bindValue(":NAME_H",$sObj->getNAME_H(),PDO::PARAM_STR);
				$prep->bindValue(":NAME_W",$sObj->getNAME_W(),PDO::PARAM_STR);
				$prep->bindValue(":NAME",$sObj->getNAME(),PDO::PARAM_STR);
				$prep->bindValue(":USERNAME",$sObj->getUSERNAME(),PDO::PARAM_STR);
				$prep->bindValue(":WEDDING_DATE",$sObj->getWEDDING_DATE(),PDO::PARAM_STR);
				$prep->bindValue(":CONTACT_DETAILS",$sObj->getCONTACT_DETAILS(),PDO::PARAM_STR);
				$prep->bindValue(":EMAIL",$sObj->getEMAIL(),PDO::PARAM_STR);
				$prep->bindValue(":EMAIL_W",$sObj->getEMAIL_W(),PDO::PARAM_STR);
				$prep->bindValue(":COMMENTS",$sObj->getCOMMENTS(),PDO::PARAM_STR);
				$prep->bindValue(":DATETIME",$sObj->getDATETIME(),PDO::PARAM_STR);
				$prep->bindValue(":USERNAME_H",$sObj->getUSERNAME_H(),PDO::PARAM_STR);
				$prep->bindValue(":USERNAME_W",$sObj->getUSERNAME_W(),PDO::PARAM_STR);
				$prep->bindValue(":PIC_URL",$sObj->getPIC_URL(),PDO::PARAM_STR);
				$prep->bindValue(":UPLOADED",$sObj->getUPLOADED(),PDO::PARAM_STR);
				$prep->bindValue(":SEND_EMAIL",$sObj->getSEND_EMAIL(),PDO::PARAM_STR);
				$prep->bindValue(":SKIP_COMMENTS",$sObj->getSKIP_COMMENTS(),PDO::PARAM_STR);
				$prep->execute();
		}		
		
		public function fetchStoryById($id)
		{
			try{
					$sql="SELECT ID,COMMENTS,DATETIME,EMAIL,EMAIL_W,WEDDING_DATE,CONTACT_DETAILS,PIC_URL,USERNAME_H,USERNAME_W,NAME_H,NAME_W,UPLOADED FROM newjs.SUCCESS_STORIES WHERE ID=:ID";

				
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ID",$id);
					$prep->execute();
					if($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$result = $row;
					}
					return $result;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		public function skipRecords($id,$comment,$uploaded)
		{
			try 
			{
				$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED= :UPLOADED,SKIP_COMMENTS=:SKIP_COMMENTS WHERE ID=:ID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":UPLOADED",$uploaded,PDO::PARAM_STR);
				$prep->bindValue("SKIP_COMMENTS",$comment,PDO::PARAM_STR);
				$prep->bindValue(":ID",$id,PDO::PARAM_INT);
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
            	throw new jsException($e);
            }
		}
		
		public function getIdCount($username)
		{			
			try 
			{
				$sql="SELECT COUNT(ID) as CNT FROM newjs.SUCCESS_STORIES WHERE USERNAME=:USERNAME";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
				$prep->execute();
				if($row = $prep->fetch(PDO::FETCH_ASSOC))
				{
						$result = $row['CNT'];
				}
                return $result;
			}
            catch (PDOException $e)
            {
            	throw new jsException($e);
            }
		}


		public function getId($username)
		{			
			try 
			{
				$sql="SELECT ID FROM newjs.SUCCESS_STORIES WHERE USERNAME=:USERNAME";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
				$prep->execute();
				if($row = $prep->fetch(PDO::FETCH_ASSOC))
				{
						$result = $row['ID'];
				}
                return $result;
			}
            catch (PDOException $e)
            {
            	throw new jsException($e);
            }
		}	


	/*
        This function is used to update records on newjs.SUCCESS_STORIES table
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

                        $sql = "UPDATE newjs.SUCCESS_STORIES SET $setValues WHERE ID = :ID";
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
        //functions for innodb transactions
	    public function startTransaction()
	    {
			$this->db->beginTransaction();
		}
		
		public function commitTransaction()
		{
			$this->db->commit();
		} 
		
		public function affectedRows()
		{
			$prep = $this->db->prepare('SELECT FOUND_ROWS() as CNT');
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
				return $result['CNT'];
			else
				return 0;
		}
		public function fetchUnscreenedStoryCount()
        {
			try
			{
					$sql="SELECT COUNT(ID) AS COUNT FROM newjs.SUCCESS_STORIES WHERE UPLOADED='N'";
					$prep = $this->db->prepare($sql);
					$prep->execute();
					$result = $prep->fetch(PDO::FETCH_ASSOC);
			
						$res = $result["COUNT"];
				
						return $res;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
}
?>
