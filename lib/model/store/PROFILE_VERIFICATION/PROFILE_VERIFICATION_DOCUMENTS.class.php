<?php
/*
 * This Class provide functions for PROFILE_VERIFICATION.DOCUMENTS table
 * @author Reshu Rajput / Lavesh Rawat
 * @created March 12, 2014
*/
class PROFILE_VERIFICATION_DOCUMENTS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	//Functions for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}


        /** 
        * This function is used to insert multiple values in PROFILE_VERIFICATION_DOCUMENTS table.
        * @param docid Array of doc  ids to be inserted
	* @param profileId Array of profile ids
        * @param $attribute Array of corresponding attributes 
	* @param $doc Array of corresponding doc types
	* @param $docFormat Array of corresponding doc format
	* @param $docUrl Array of corresponding docUrl
	* @param $verificationValue Array of corresponding verification value
	* @param $uploadedBy Array of corresponding uploaded by user  
        **/
        public function insertbulkDocuments($docid,$pid,$attribute,$doc,$docFormat,$docUrl,$verificationValue,$uploadedBy)
        {
                try
                {
			$now = date("y-m-d H:i:s");
			$sql = "INSERT INTO PROFILE_VERIFICATION.DOCUMENTS (DOCUMENT_ID,PROFILEID, ATTRIBUTE ,UPLOADED_DATE,DOCUMENT_TYPE,DOC_FORMAT,DOCURL,VERIFICATION_VALUE,UPLOADED_BY) VALUES ";
                        for ($i=0;$i<count($docid);$i++)
                        {
                                $param[] = "(:DOCUMENT_ID".$i.",:PROFILEID".$i.", :ATTRIBUTE".$i.", :UPLOADED_DATE".$i.", :DOCUMENT_TYPE".$i.", :DOC_FORMAT".$i.", :DOCURL".$i.",:VERIFICATION_VALUE".$i.", :UPLOADED_BY".$i.")";
                        }
                        $paramStr = implode(",",$param);
                        $sql = $sql.$paramStr;
                        $res = $this->db->prepare($sql);
                        for ($i=0;$i<count($docid);$i++)
                        {
				$res->bindParam(":DOCUMENT_ID".$i, $docid[$i], PDO::PARAM_INT);
                                $res->bindParam(":PROFILEID".$i, $pid[$i], PDO::PARAM_INT);
                                $res->bindParam(":ATTRIBUTE".$i, $attribute[$i], PDO::PARAM_STR);
                                $res->bindParam(":UPLOADED_DATE".$i, $now, PDO::PARAM_STR);
                                $res->bindParam(":DOCUMENT_TYPE".$i, $doc[$i] , PDO::PARAM_STR);
                                $res->bindParam(":DOC_FORMAT".$i, $docFormat[$i], PDO::PARAM_STR);
                                $res->bindParam(":DOCURL".$i, $docUrl[$i], PDO::PARAM_STR);
				$res->bindParam(":VERIFICATION_VALUE".$i, $verificationValue[$i], PDO::PARAM_STR);
                                $res->bindParam(":UPLOADED_BY".$i, $uploadedBy[$i], PDO::PARAM_STR);
                        }
                        $res->execute();
			return true;			
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

        /**
        * Allot profile to screening user based on oldest 1st.
	* @param dt max upload date should be less than the passed date.
	* @return array containing profileid
        **/
        public function allottProfile($dt)
        {
		$sql = "SELECT A.PROFILEID, MAX(A.UPLOADED_DATE) AS D FROM PROFILE_VERIFICATION.DOCUMENTS A LEFT JOIN PROFILE_VERIFICATION.DOCUMENTS_SCREENING B ON A.PROFILEID = B.PROFILEID WHERE A.VERIFIED_FLAG=:FLAG AND A.DELETED_FLAG=:FLAG2 AND B.PROFILEID IS NULL GROUP BY A.PROFILEID HAVING D<:DATE ORDER BY D ASC LIMIT 1";
                $res=$this->db->prepare($sql);
                $res->bindValue(":DATE", $dt, PDO::PARAM_STR);
                $res->bindValue(":FLAG", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM["UNDER_SCREENING"], PDO::PARAM_STR);
                $res->bindValue(":FLAG2", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["NOT_DELETED"], PDO::PARAM_STR);
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                        return $row;
		return NULL;
        }

	/*This function is used to get document details according to the provided where condition
	* @param whereCondition : array of fields for equal or in where condition
	* @param order : order by value
	* @param limit : limit of records required
	* @return output : Array of document id and respective information
	*/

	public function getDocuments($fields="",$whereCondition,$orderBy="",$limit="",$groupBy="")
	{
		if(!$whereCondition)
                        throw new jsException("","WHERE CONDITION IS BLANK IN getDocuments() of PROFILE_VERIFICATION_DOCUMENTS.class.php");
		$validFields =Array("DOCUMENT_ID","PROFILEID", "ATTRIBUTE" ,"UPLOADED_DATE","DOCUMENT_TYPE","VERIFIED_FLAG","DOC_FORMAT","DOCURL", "UPLOADED_BY","VERIFICATION_VALUE","DELETED_FLAG");
	        foreach($whereCondition as $key =>$value)
        	{
			if(in_array($key,$validFields))
			{
				if(is_array($value))
	                        {
        	                        foreach($value as $k=>$v)
                	                        $valueStr[]=":v$k";
                        	        $whereConditionSql[]= "$key IN (".(implode(",",$valueStr)).")";
                        	}
                        	else
	                                $whereConditionSql[]= "$key = :$key";
			}
			else
				 throw new jsException("","Invalid field $key is passes in WHERE CONDITION in getDocuments() of PROFILE_VERIFICATION_DOCUMENTS.class.php");
                }
                $whereConditionStr = implode(" AND ",$whereConditionSql);
		try
                {
			$fields = ($fields!="")?$fields:"*";
                        $sql = "SELECT $fields FROM  PROFILE_VERIFICATION.DOCUMENTS WHERE ".$whereConditionStr;
			if($groupBy!="" && in_array($groupBy,$validFields))
				$sql.=" GROUP BY $groupBy ";
			if($orderBy!="" && in_array($orderBy,$validFields))
                                $sql.=" ORDER BY $orderBy ";
			if($limit)
				$sql .=" LIMIT $limit ";
                        $res=$this->db->prepare($sql);
                        foreach($whereCondition as $key =>$value)
                        {
				if($key =="PROFILEID" || $key=="DOCUMENT_ID")
					$pdoType = PDO::PARAM_INT;
				else
					$pdoType = PDO::PARAM_STR;
				if(is_array($value))
                                {
                                        foreach($value as $k=>$v)
                                              $res->bindValue(":v$k",$v,$pdoType);
                                }
                                else
                                        $res->bindValue(":$key",$value,$pdoType);

                        }
                        $res->execute();
			$i=0;
			$fieldsArr =($fields=="*")?$validFields:explode(",",$fields);
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				foreach($fieldsArr as $k=>$v)
                                	$output[$i][$v] = $row[$v];
				$i++;
                        }
                        return $output;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*This function is used to delete records on the basis of document ids or profile ids
	* @param idType : It can be either DOCUMENT_ID or PROFILEID
	* @param idsStr : comma separated array of ids to be deleted 
	*/
	public function deleteRowsBasedOnDocOrPid($idType,$idsStr)
        {
		if(!$idType || !($idType=="DOCUMENT_ID" || $idType=="PROFILEID"))
			throw new jsException("","idType CONDITION IS BLANK or invalid IN deleteRowsBasedOnDocOrPid() of PROFILE_VERIFICATION_DOCUMENTS.class.php");
                $idArrString = str_replace("\'","",$idsStr);
                $idArray = explode(",",$idArrString);
                for($i=0;$i<sizeof($idArray) ;$i++)
                {
                        $idsArr[]=":ID$i";
                }
		$ids= implode(",",$idsArr);
		try
		{
			$sql = "DELETE FROM PROFILE_VERIFICATION.DOCUMENTS WHERE $idType IN ($ids)";
                	$res = $this->db->prepare($sql);
                	for($i=0;$i<sizeof($idArray) ;$i++)
                	{
                        	$res->bindValue(":ID$i", $idArray[$i], PDO::PARAM_INT);
                	}
                	$res->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/**
	* This function is used to update using case.
	* @param updateArr array containing docId(key) updateValue(value) to be updated on colum columToUpdate
	* @param columToUpdate column to be updated.
	*/	
	public function multipleDocumentIdUpdate($updateArr,$columToUpdate)
	{
		try
		{
			$sql = "UPDATE PROFILE_VERIFICATION.DOCUMENTS ";
			$sql.= "SET  $columToUpdate  = ";
			$sql.= "CASE ";
			$i=0;
			foreach($updateArr as $k=>$v)
			{
				$sql.="WHEN DOCUMENT_ID = :key$i THEN :value$i ";
				$idArr[] = ":key$i";
				$i++;
			}
			$sql.= "END";
			$idKey = implode(",",$idArr);
			$sql.=" WHERE DOCUMENT_ID  in ($idKey)";
                	$res = $this->db->prepare($sql);

			$i=0;
			foreach($updateArr as $k=>$v)
                	{
                        	$res->bindValue(":key$i",  $k, PDO::PARAM_INT);
                        	$res->bindValue(":value$i",$v, PDO::PARAM_INT);
				$i++;
                	}
                	$res->execute();
			return true;
		}
                catch(PDOException $e)
		{
                        throw new jsException($e);
                }
	}
        /**
         * This function is used to create verification seal array for display from coded seal.
         * @param profileId of the user to be found
         */
        public function sealDetails($profileIdArray) {
                $sqlPart = "";
                try {
                        $sql = "SELECT ATTRIBUTE,DOCUMENT_TYPE FROM PROFILE_VERIFICATION.DOCUMENTS WHERE VERIFIED_FLAG=:vFlag AND DELETED_FLAG=:dFlag AND PROFILEID IN "; //GROUP BY ATTRIBUTE";
                        foreach($profileIdArray as $k=>$v)
                        {
                            if($sqlPart!='')
                                $sqlPart.=",";
                            $sqlPart.= ":PROFILEID".$k;
                        }
                        $sqlPart="( ".$sqlPart.") GROUP BY ATTRIBUTE";
                        $sql.=$sqlPart;
                        $res = $this->db->prepare($sql);    
                        $res->bindValue(":vFlag", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM['ACCEPTED'], PDO::PARAM_STR);
                        $res->bindValue(":dFlag", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM['NOT_DELETED'], PDO::PARAM_STR);
                        foreach($profileIdArray as $k=>$v)
                        {
                            $res->bindValue(":PROFILEID".$k,$v,PDO::PARAM_INT);
                        }
                        $res->execute();
                        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                                $seal[$row["ATTRIBUTE"]] = $row["DOCUMENT_TYPE"];
                        }
                        if ($seal)
                                return $seal;
                        else
                                return 0;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

        /**
         * This function is used to unset verified documents for unset verification seal.
         * @param profileId of the user to be found
         */
        public function unsetVerificationDoc($profileId, $attributeArr) {
                try { 
                        $where = "ATTRIBUTE IN (";
                        $comma="";
                        foreach ($attributeArr as $key => $attribute) {
                                $where .= $comma.":ATTRIBUTE" . $key;
                                $comma=",";
                        }
                        
                        $where .= ")";
                        $sql = "UPDATE PROFILE_VERIFICATION.DOCUMENTS SET VERIFIED_FLAG = :vFlag WHERE PROFILEID=:PROFILEID AND VERIFIED_FLAG = :vOldFlag AND " . $where;
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":vFlag", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM['DECLINED'], PDO::PARAM_STR);
                        $res->bindValue(":vOldFlag", PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM['ACCEPTED'], PDO::PARAM_STR);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        foreach ($attributeArr as $key => $attribute) {
                                $res->bindValue(":ATTRIBUTE" . $key, $attribute, PDO::PARAM_STR);
                        }
                        $res->execute();
                        return 1;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

    public function countVerifiedDocuments($start_dt, $end_dt, $uploaded_by)
    {
        try
        {
            $start_dt = $start_dt." 00:00:00";
            $end_dt = $end_dt." 23:59:59";
            $sql="SELECT DAYOFMONTH(`UPLOADED_DATE`) AS DD, COUNT(*) AS CNT FROM PROFILE_VERIFICATION.`DOCUMENTS` WHERE `UPLOADED_BY` = :UPLOADED_BY AND `VERIFIED_FLAG` = 'Y' AND `UPLOADED_DATE` >= :START_DATE AND `UPLOADED_DATE` <= :END_DATE GROUP BY DD";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":UPLOADED_BY", $uploaded_by, PDO::PARAM_STR);
            $prep->bindValue(":START_DATE", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$row['DD']] = $row['CNT'];
            }
            return $res;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function countVerifiedDocumentsForProfilesArr($profilesArr)
    {
        try
        {
        	if(is_array($profilesArr)){
        		$profilesStr = "'".implode("','",$profilesArr)."'";
        	} else {
        		throw new jsException('Invalid Profile Array in STORE PROFILE_VERIFICATION_DOCUMENTS function - countVerifiedDocumentsForProfilesArr');
        	}
            $sql="SELECT COUNT(*) AS CNT, PROFILEID FROM PROFILE_VERIFICATION.`DOCUMENTS` WHERE `VERIFIED_FLAG` = 'Y' AND DELETED_FLAG = 'N' AND PROFILEID IN ($profilesStr) GROUP BY PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$row['PROFILEID']] = $row['CNT'];
            }
            return $res;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

}
?>
