<?php
class PICTURE_FOR_SCREEN_NEW extends TABLE
{
	private $validSet;
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
                $this->validSet = array_merge(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS, Array("TITLE","KEYWORD","PICTUREID","ORDERING","PROFILEID","PICFORMAT","SCREEN_BIT"));
              
        }


        /**
        This function is used to get pictures info (profile pic/album) from non-screened table (PICTURE_FOR_SCREEN_NEW table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
        public function get($paramArr=array())
        {
					
                foreach($paramArr as $key=>$val)
                        ${$key} = $val;

                if(!$PROFILEID && !$PICTUREID && !$CURRENTSCRIPT && !$TOTALSCRIPT)
                        throw new jsException("","PROFILEID & PICTUREID or Total script and current script IS BLANK IN get() of PICTURE_NEW.class.php");
                try
                {
                        $fields="*";
                        $detailArr='';
                        $fields = $fields?$fields:$this->getFields();
                        $sql = "SELECT $fields FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE ";
                        if($PROFILEID)
                                $sql.="PROFILEID = :PROFILEID";
                        if($PICTUREID)
			{	
				if($PROFILEID)
					$sql.=" AND ";
                                $sql.="PICTUREID = :PICTUREID";
			}
			if($CURRENTSCRIPT>='0' && $TOTALSCRIPT)
				$sql.="PICTUREID%:TOTALSCRIPT=:CURRENTSCRIPT";
                        if($ORDERING || $ORDERING=='0')
                                $sql.=" AND ORDERING= :ORDERING";
			if($SCREEN_BIT)
			{
			 	if(is_array($SCREEN_BIT))
                			$sql.= " AND LOCATE(:SCREEN_VAL, SCREEN_BIT, :SCREEN_POS) =:SCREEN_POS";                
				else
					$sql.=" AND SCREEN_BIT= :SCREEN_BIT";
			}
			if($EditPictures)
				$sql.= " AND $EditPictures";
			if($OriginalPicUrl == 1)
				$sql.=" AND COALESCE(OriginalPicUrl, '') != ''";
			else if($OriginalPicUrl == 2)
				$sql.=" AND COALESCE(OriginalPicUrl, '') = ''";
			if($MainPicUrl)
				$sql.= " AND MainPicUrl ".$MainPicUrl." ";
			if($specialCond)
				$sql.= " AND $specialCond";
			if($LIMIT)
				$sql.=" LIMIT :LIMIT ";
				
                        $res = $this->db->prepare($sql);
                        if($PROFILEID)
                                $res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
                        if($PICTUREID)
                                $res->bindValue(":PICTUREID", $PICTUREID, PDO::PARAM_INT);
                        if($ORDERING || $ORDERING=='0')
                                $res->bindValue(":ORDERING", $ORDERING, PDO::PARAM_INT);
                    
													
			if($SCREEN_BIT)
			{
			 	if(is_array($SCREEN_BIT))
				{
					$res->bindValue(":SCREEN_VAL", $SCREEN_BIT[1], PDO::PARAM_INT);
					$res->bindValue(":SCREEN_POS", $SCREEN_BIT[0], PDO::PARAM_INT);
				}
				else
                                	$res->bindValue(":SCREEN_BIT", $SCREEN_BIT, PDO::PARAM_STR);
			}
			if($CURRENTSCRIPT>='0' && $TOTALSCRIPT)
			{
				$res->bindValue(":TOTALSCRIPT", $TOTALSCRIPT, PDO::PARAM_INT);
	                        $res->bindValue(":CURRENTSCRIPT", $CURRENTSCRIPT, PDO::PARAM_INT);
			}
                        if($LIMIT)
				 $res->bindValue(":LIMIT", $LIMIT, PDO::PARAM_INT);
				
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
                        return $detailArr;
                }

                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }
        /**
        This function is used to edit/alter pictures info (profile pic/album) of screened table (PICTURE_NEW table).
        * @param paramArr array contains where condition on basis of which entry will be fetched.
        * @param pictureId int update is only only by pictureId.
        * @returns true on sucess
        **/
        public function edit($paramArr=array(),$pictureId,$profileId)
        {
                if(!$pictureId)
                        throw new jsException("","PICTUREID IS BLANK IN edit() of PICTURE_FOR_SCREEN_NEW.class.php");
                try
                {
			foreach($paramArr as $key=>$val)
			{
				if(in_array($key,$this->validSet))
					$set[] = $key." = :".$key;
			}

                        $setValues = implode(",",$set);

                        $sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET $setValues WHERE PICTUREID = :PICTUREID AND PROFILEID = :PROFILEID";
                        $res = $this->db->prepare($sql);
                        foreach($paramArr as $key=>$val)
                        {
				if(in_array($key,$this->validSet))
                                	$res->bindValue(":".$key, $val);
                        }
                        $res->bindValue(":PICTUREID",$pictureId,PDO::PARAM_INT);
                        $res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

        /**
        This function is used to insert pictures info (profile pic/album) of screened table (PICTURE_NEW table).
        * @param paramArr array contains key value pair for insertion
        * @returns true on sucess
        **/
        public function ins($paramArr=array())
        {
		foreach($paramArr as $key=>$val)
		{
			if(in_array($key,$this->validSet) && $key!='MobileAppPicUrl')
			{
				$set[] = ":".$key;
				$fieldsSet[]= $key;
				${$key} = $val?$val:'';
			}
		}

                $setValues = implode(",",$set);
                $fieldsSetString = implode(",",$fieldsSet);
		
		//Exception handling
		if(!$PROFILEID)
		       throw new jsException("","PICTUREID IS BLANK IN edit() of PICTURE_FOR_SCREEN_NEW.class.php");
		//Exception handling

                try
                {
                        $sql = "REPLACE INTO newjs.PICTURE_FOR_SCREEN_NEW ($fieldsSetString) VALUES ($setValues)";
                        $res = $this->db->prepare($sql);
			$pdoIntSet = array("PROFILEID","PICTUREID","ORDERING");
			foreach($fieldsSet as $index=>$field)
			{
				$pdoType = in_array($field,$pdoIntSet)?PDO::PARAM_INT:PDO::PARAM_STR;
				$res->bindParam(":".$field, ${$field},$pdoType);
			}
                        
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }


        /**
        This function is used to delete a photo from PICTURE_FOR_SCREEN_NEW table.
        If PICTUREID is found then it deletes photo based on PICTUREID as its a primary key else a combination of PROFILEID & ORDERING (a unique key) is used to delete        the entry.
        * @param  paramArr array contains where condition on basis of which entry will get deleted.
        * @return rowCount int numbers of rows get deleted.
        **/
        public function del($paramArr=array())
        {
		$PICTUREID=$paramArr["PICTUREID"];
		$PROFILEID=$paramArr["PROFILEID"];
		if(array_key_exists("ORDERING",$paramArr))
			$ORDERING=$paramArr["ORDERING"];

		$sql = "DELETE FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PICTUREID = :PICTUREID AND PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
		if($PROFILEID && $PICTUREID)
		{
	                $res->bindParam("PICTUREID", $PICTUREID, PDO::PARAM_INT);
			$res->bindParam("PROFILEID", $PROFILEID, PDO::PARAM_INT);
		}
		elseif($PROFILEID && $ORDERING)
		{
	                $res->bindParam("PROFILEID", $PROFILEID, PDO::PARAM_INT);
	                $res->bindParam("ORDERING", $ORDERING, PDO::PARAM_STR);
		}	
                $res->execute();
                return $res->rowCount();
        }

        public function updateOrdering($paramArr=array())
        {
                $PROFILEID=$paramArr["PROFILEID"];
                $ORDERING=$paramArr["ORDERING"];
		if($paramArr["INCREASE_ORDERING"])
                	$sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET ORDERING=ORDERING+1 WHERE PROFILEID = :PROFILEID ";
		else
	                $sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET ORDERING=ORDERING-1 WHERE PROFILEID = :PROFILEID ";
                //if($ORDERING || $ORDERING == 0)     		//$ORDERING==0 for incrementing the order of all photos by 1 except profile pic
		if(isset($ORDERING))
                        $sql.= " AND ORDERING > :ORDERING";

		if($paramArr["INCREASE_ORDERING"])
			$sql.=" ORDER BY ORDERING DESC";
		else
			$sql.=" ORDER BY ORDERING ASC";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
                //if(@$ORDERING || $ORDERING == 0)		//$ORDERING==0 for incrementing the order of all photos by 1 except profile pic
		if(isset($ORDERING))
                        $res->bindValue(":ORDERING",$ORDERING,PDO::PARAM_INT);
                $res->execute();
        }

        /**
        This function is used to get maximum ordering in the table for a particular profileId in order to check next available ordering value.
        * @param  profileId int unique value for which records need to be fetched.
        * @return MAX int maximum ordering of the table 
        **/
        public function getMaxOrdering($profileId)
        {
               if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN getMaxOrdering() of PICTURE_FOR_SCREEN_NEW.class.php");

                $sql = "SELECT MAX(ORDERING) as MAX FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID ";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                return $row['MAX'];
        }

        /**
        *This function is used to get pictureid of main nonscreened profilepic
        * @param  profileId int unique value for which record need to be fetched.
        * @return PICTUREID 
        **/
        public function getPICTUREIDIFNONSCREENED($profileId)
        {
               if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN getPICTUREIDIFNONSCREENED() of PICTURE_FOR_SCREEN_NEW.class.php");

                $sql = "SELECT PICTUREID FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID AND ORDERING = :ORDERING";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                 $res->bindValue(":ORDERING", 0, PDO::PARAM_INT);
                $res->execute();
                $result = $res->fetch(PDO::FETCH_ASSOC);
                return $result["PICTUREID"];
        }
	
	public function deleteRowsBasedOnPicId($picIdArr)
	{
		if(!is_array($picIdArr)){
                $picIdArrString = str_replace("\'","",$picIdArr);
		$picIdArray = explode(",",$picIdArrString);
                $condition="";
                }
                else {
                     $profileId=$picIdArr["profileId"];
                     $condition="AND PROFILEID = :PROFILEID";
                     $picIds=implode(",",$picIdArr["picId"]);
                }
		for($i=0;$i<sizeof($picIdArray) ;$i++)
                {
	                $picIds.=":PICTUREID$i";
                        if($i != (sizeof($picIdArray)-1))
        		        $picIds.=',';
                }

		$sql = "DELETE FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PICTUREID IN ($picIds)".$condition;
		$res = $this->db->prepare($sql);
		for($i=0;$i<sizeof($picIdArray) ;$i++)
                {
	                $res->bindValue(":PICTUREID$i", $picIdArray[$i], PDO::PARAM_INT);
                }
                if($profileId)
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        

                $res->execute();
	}
        public function profilePictureStatusArr($profileId) {
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                
                $sql = "SELECT PICTUREID,ORDERING,SCREEN_BIT AS STATUS FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                   if($row["STATUS"]==$bitStatus["APPROVE"] || $row["STATUS"]==$bitStatus["FACE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"])
                        $result["APPROVED"][]= $row["PICTUREID"];
                   elseif($row["STATUS"]==$bitStatus["DELETE"])
                        $result["DELETED"][]= $row["PICTUREID"];
                   
                   if($row["ORDERING"]==0 && $row["STATUS"]==$bitStatus["FACE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"].$bitStatus["APPROVE"])
                        $result["ProfilePic"]=$row["PICTUREID"];
                }
                return $result;      
        }
        public function isProfileScreened($profileId) {
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                $sql = "SELECT count(*) as count FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID AND (SCREEN_BIT LIKE '%".$bitStatus["EDIT"]."%' OR SCREEN_BIT LIKE '%".$bitStatus["DEFAULT"]."%' OR (ORDERING=0 AND SCREEN_BIT LIKE '".$bitStatus["FACE"]."%".$bitStatus["DEFAULT"]."%')) ";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                if($row["count"]>0)
                        return 0;
                else
                        return 1;
        }
        public function isSuitableForSubmit($profileId) {
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                $sql = "SELECT count(*) as count FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID AND (ORDERING=0 AND SCREEN_BIT LIKE '%".$bitStatus["DEFAULT"]."%')";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                if($row["count"]>0)
                        return 0;
                else
                        return 1;
        }
        
        
        
        public function getScreenBit($pictureId) {
                $sql = "SELECT ORDERING,SCREEN_BIT FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PICTUREID = :PICTUREID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PICTUREID", $pictureId, PDO::PARAM_INT);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                        
                return $row;
                
        }
        public function pictureScreenStatus($profileId,$paramArr='') {
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                $bitPosition = array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,  array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)));
                
                $finalStatus = 1;
                $sql = "SELECT CASE"

                        . " when SCREEN_BIT LIKE '".$paramArr["noOperationPerformed"]."' OR OriginalPicUrl ='' then 0"
                        . " when (ORDERING = 0 AND OriginalPicUrl='' AND SCREEN_BIT LIKE '".$bitStatus["DEFAULT"].$bitStatus["RESIZE"]."%') OR (ORDERING != 0 AND OriginalPicUrl='' AND SCREEN_BIT LIKE '".$bitStatus["RESIZE"]."') then 1"
                        . " when (ORDERING != 0 AND OriginalPicUrl='' AND SCREEN_BIT LIKE '".$bitStatus["FACE"]."' AND SCREEN_BIT NOT LIKE '%".$bitStatus["EDIT"]."%') OR (ORDERING = 0 AND SCREEN_BIT LIKE '".$bitStatus["FACE"]."%' AND SCREEN_BIT NOT LIKE '".$bitStatus["FACE"]."%".$bitStatus["EDIT"]."%') then 2"
                        . " when ((ORDERING=0 AND SCREEN_BIT LIKE '".$bitStatus["FACE"]."%".$bitStatus["EDIT"]."%') OR (ORDERING!=0 AND SCREEN_BIT = '".$bitStatus["EDIT"]."')) then 3"
                        . " when ((ORDERING=0 AND (SCREEN_BIT LIKE '1%".$bitStatus["APPROVE"]."%' OR SCREEN_BIT LIKE '".$bitStatus["FACE"]."%".$bitStatus["DELETE"]."%')) OR (ORDERING!=0 AND (SCREEN_BIT = '".$bitStatus["APPROVE"]."' OR SCREEN_BIT = '".$bitStatus["DELETE"]."'))) then 4"
                        . " ELSE 5 END AS STATUS,PICTUREID, UPDATED_TIMESTAMP FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = :PROFILEID ";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                        $pictureId=$row["PICTUREID"];
                        unset($row["PICTUREID"]);
                        $picStatusArr[$pictureId]=$row;
                        
                        if($paramArr["require"]=="CHECK" && $row["STATUS"]==0){
                                $finalStatus = 0;
                        }
                } 
                if($paramArr["require"]=="CHECK"){
                                return $finalStatus;
                }
                return $picStatusArr;
        }
        public function updateScreenBit($paramArr=array())
        {
                $update = "";
                if ($paramArr["urls"]) {
                        $comma = ",";
                        foreach ($paramArr["urls"] as $type => $url) {
                                $update.=$type . "='" . $url . "'" . $comma;
                        }
                }
                $update.=" SCREEN_BIT='".$paramArr["bit"]."'";
		$sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET ".$update." WHERE PROFILEID = :PROFILEID AND PICTUREID = :PICTUREID ";
		
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$paramArr["profileId"],PDO::PARAM_INT);
                $res->bindValue(":PICTUREID",$paramArr["pictureId"],PDO::PARAM_INT);
                $res->execute();
                return $res->rowCount();
        }
        public function setWatermark($paramArr)
        {
                if(is_array($paramArr)){
                foreach($paramArr["watermark"] AS $pictureId => $value){
                $sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET WATERMARK=:WATERMARK WHERE PROFILEID = :PROFILEID AND PICTUREID = :PICTUREID";
		
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$paramArr["profileId"],PDO::PARAM_INT);
                $res->bindValue(":WATERMARK",$value,PDO::PARAM_INT);
                $res->bindValue(":PICTUREID",$pictureId,PDO::PARAM_INT);
                $res->execute();
                }
                return $res->rowCount();
                }
        }
        
        public function updateScreeningDecision($paramArr=array())
        { 
                $condition="SCREEN_BIT = CASE";
                $conditionTitle="";
                $loop=0;
                foreach($paramArr["FINAL"] as $pictureID => $data){
                        if($data["bit"]){
                        $condition.=" WHEN PICTUREID=:PICTUREID".$loop." THEN :BIT".$loop." ";
                        $pictureIdData[":PICTUREID".$loop]=$pictureID;
                        $pictureBit[":BIT".$loop]=$data["bit"];
			
                        
                        if($data["title"]){
                                $pictureTitle[":TITLE".$loop]='';//$data["title"];
                                $conditionTitle.=" WHEN PICTUREID=:PICTUREID".$loop." THEN :TITLE".$loop." ";
                        }
                        
                        $pictureIDs[]=":PICTUREID".$loop;
                        $loop++;
                                
                        }
                }
                $condition.=" END";
                
                $pictureIDs=  implode(",", $pictureIDs);
                
		if(is_array($pictureTitle)){
                        $condition.=",TITLE = CASE".$conditionTitle."ELSE TITLE END ";
                }
                
                
                $sql = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET ".$condition." WHERE PROFILEID = :PROFILEID AND PICTUREID IN (".$pictureIDs.")";
		$res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$paramArr["profileId"],PDO::PARAM_INT);
                foreach($pictureIdData as $pictureIDname => $id){
                       $res->bindValue($pictureIDname,$id,PDO::PARAM_INT);
                }
                foreach($pictureBit as $pictureBitname => $bit){
                       $res->bindValue($pictureBitname,$bit,PDO::PARAM_STR);
                }
		if(is_array($pictureTitle)){
                foreach($pictureTitle as $pictureTitleName => $title){
                       $res->bindValue($pictureTitleName,$title,PDO::PARAM_STR);
                }}
                $res->execute();
                
        }
        public function UpdatePresentImageCorrupt(){
                $sql = "UPDATE newjs.`PICTURE_FOR_SCREEN_NEW` SET MainPicUrl=REPLACE(MainPicUrl,'http://www.jeevansathi.com/', 'JS/' ),OriginalPicUrl=REPLACE(OriginalPicUrl,'http://www.jeevansathi.com/', 'JS/' ) WHERE MainPicUrl NOT LIKE '%media%' AND MainPicUrl LIKE '%jeevansathi%'";
		$res = $this->db->prepare($sql);
                $res->execute();
        }
	/*
         * This function inserts the PICTURE_FOR_SCREEN_NEW data for backup in PICTURE_FOR_SCREEN_NEW_BKP having ordering 0 on the basis of provided screenbit 
         * @param array $params value that will replace 2 leading zeros
         */
        public function insertPictureScreenBackupBit($params){
          $sql = "REPLACE INTO newjs.`PICTURE_FOR_SCREEN_NEW_BKP` SELECT * FROM newjs.`PICTURE_FOR_SCREEN_NEW` WHERE UPDATED_TIMESTAMP > ( now( ) - INTERVAL :MINUTES_UPDATED minute ) AND `SCREEN_BIT` = :SCREENBIT AND `ORDERING` = 0";
          $res = $this->db->prepare($sql);
          $res->bindValue(':MINUTES_UPDATED',$params['duration'],PDO::PARAM_INT);
          $res->bindValue(':SCREENBIT',$params['screen_bit'],PDO::PARAM_STR);
          $res->execute();
        }
        /*
         * This function updates the PICTURE_FOR_SCREEN_NEW having ordering 0 and updates screenbit first to value on the basis of provided screenbit 
         * @param array $params value that will replace 2 leading zeros
         */
        public function updatePictureScreenBit($params){
          $sql = "UPDATE newjs.`PICTURE_FOR_SCREEN_NEW` SET `SCREEN_BIT`= CONCAT(:PREFIXVALUE,SUBSTRING(SCREEN_BIT,3)) WHERE UPDATED_TIMESTAMP > ( now( ) - INTERVAL :MINUTES_UPDATED minute ) AND `SCREEN_BIT` = :SCREENBIT AND `ORDERING` = 0";
          $res = $this->db->prepare($sql);
          $res->bindValue(':MINUTES_UPDATED',$params['duration'],PDO::PARAM_INT);
          $res->bindValue(':PREFIXVALUE',$params['prefix'],PDO::PARAM_STR);
          $res->bindValue(':SCREENBIT',$params['screen_bit'],PDO::PARAM_STR);
          $res->execute();
        }
        
        
        /*This functio is used for distributed photo servers and retrieve data when no cron is executed*/
         public function getPreProcessData($paramArr){
                                                                
                 if(array_key_exists("MainPicUrl",$paramArr))
                {                                                    
	                try
	                {
	                        $sql = "SELECT DISTINCT(A.PROFILEID) FROM PICTURE_FOR_SCREEN_NEW A, PICTURE_FOR_SCREEN_NEW B WHERE A.PROFILEID=B.PROFILEID AND A.MainPicUrl LIKE :MainPicURl AND B.MainPicUrl NOT LIKE :MainPicURl AND (B.OriginalPicUrl= '' OR A.OriginalPicUrl = '')";
	                        $res = $this->db->prepare($sql);
	                        $res->bindValue(":MainPicURl", $paramArr["MainPicUrl"], PDO::PARAM_STR);
	
	                        $res->execute();
	                        while($row = $res->fetch(PDO::FETCH_ASSOC))
	                        {
	                                $detailArr[] = $row["PROFILEID"];
	                        }
	                        return $detailArr;
	                }
	
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	               }	
                return NULL;
        }
        
        
        /*This functio is used for distributed photo servers and set all the pictures to default server enum*/
         public function getPreDistributedData($params){
            if(is_array($params))
            {   
							foreach($params as $key=>$val)
                        ${$key} = $val;                                      
						try
						{
									
										$sql = "SELECT * FROM PICTURE_FOR_SCREEN_NEW where ";
										foreach(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS as $k=>$v)
										{
											$cond[]= $v." NOT LIKE :JSPIC ";
										}
										$sql .= implode(" OR ",$cond);
										if($CURRENTSCRIPT>='0' && $TOTALSCRIPT)
										{
												$sql.=" AND PICTUREID%:TOTALSCRIPT=:CURRENTSCRIPT";
											
										}
										if($LIMIT)
											$sql.=" LIMIT :LIMIT ";
										$res = $this->db->prepare($sql);
										$res->bindValue(':JSPIC',$DISTRIBUTEDCONST,PDO::PARAM_STR);
										if($CURRENTSCRIPT>='0' && $TOTALSCRIPT)
										{
												$res->bindValue(":TOTALSCRIPT", $TOTALSCRIPT, PDO::PARAM_INT);
	                      $res->bindValue(":CURRENTSCRIPT", $CURRENTSCRIPT, PDO::PARAM_INT);
	                   }
	                  if($LIMIT)
											$res->bindValue(":LIMIT", $LIMIT, PDO::PARAM_INT);
				
										$res->execute();
										while($row = $res->fetch(PDO::FETCH_ASSOC))
										{
														$detailArr[] = $row;
										}
										return $detailArr;
						}

						catch(PDOException $e)
						{
										throw new jsException($e);
						}
					 }    
                return NULL;
        }

}
?>
