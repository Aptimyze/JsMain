<?php
/**
 * @brief This class is store class of user saved searches (newjs.JPARTNER table)
 * @author Lavesh Rawat
 * @created 2012-08-21
 */

class newjs_JPARTNER extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /*
        * This Function is to udpate partner prefence into the table.
        * @param updateArr key-value pair for records to be updated.
        */
	public function addRecords($updateArr)
	{
                try
                {
			$key = 'DATE';
			$updateArr[$key] = "'".date("Y-m-d H:i:s")."'";

			foreach($updateArr as $k=>$v)
			{
                                if(trim($v,"'")!='')
                                {
					$v = stripslashes($v);
                                        $columnNames.= $k.",";
                                        $values.=":".$k.",";
                                        if(in_array($k,SearchConfig::$integerSearchParameters))
                                        {
						$v = trim($v,"'");
                                                $bindMeInt[$k] = $v;
                                        }
                                        else
					{
                                        	if(in_array($k,SearchConfig::$noQuotesInJpartner))
							$v = trim($v,"'");
						else
							$v = "'".trim($v,"'")."'";
                                                $bindMeStr[$k] = $v;
					}
                                }
			}
//print_r($bindMeStr);
			$columnNames = rtrim($columnNames,",");
			$values = rtrim($values,",");

			$sql = "REPLACE INTO newjs.JPARTNER ($columnNames) VALUES ($values)";
			$res = $this->db->prepare($sql);
                        if(is_array($bindMeInt))
                                foreach($bindMeInt as $k=>$v)
                                        $res->bindValue(":$k", $v, PDO::PARAM_INT);
                        if(is_array($bindMeStr))
                                foreach($bindMeStr as $k=>$v)
                                        $res->bindValue(":$k", $v, PDO::PARAM_STR);

			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException("","No Insertion In JPARTNER table : store:JPARTNER.class.php");
		}
	}

        /**
        This function is used to get dpp information (JPARTNER table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array dpp search paramters info. Return null in case of no matching rows found.
        **/
	public function get($paramArr=array(),$fields="*")
	{
		foreach($paramArr as $key=>$val)
                	${$key} = $val;

                if(!$PROFILEID)
                        throw new jsException("","PROFILEID IS BLANK IN get() of JPARTNER.class.php");
                try
		{
			$detailArr='';

                        $sql = "SELECT $fields FROM newjs.JPARTNER WHERE ";
			if($PROFILEID)
				$sql.="PROFILEID = :PROFILEID";
                        if($ID)
                        {
                                if($PROFILEID)
                                        $sql.=" AND ";
                                $sql.="ID = :ID";
                        }
                        $res = $this->db->prepare($sql);

			if($PROFILEID)
                        	$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
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

	public function getDataForMultipleProfiles($profileIdArr,$fields="*")
	{
                if(!is_array($profileIdArr))
                        throw new jsException("","PROFILEID IS BLANK IN get() of JPARTNER.class.php");
                try
		{
			$detailArr='';

			foreach($profileIdArr as $key=>$p)
			{
				if($key == 0)
					$str = ":PROFILEID".$key;
				else
					$str .= ",:PROFILEID".$key;
			}

                        $sql = "SELECT $fields FROM newjs.JPARTNER WHERE PROFILEID IN ($str) ";
			$res = $this->db->prepare($sql);

			foreach($profileIdArr as $key=>$p)
			{
				$res->bindValue(":PROFILEID$key", $p, PDO::PARAM_INT);
			}

                        $res->execute();

                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				if($row['PROFILEID'])
                                	$detailArr[$row['PROFILEID']] = $row;
				else
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
	public function getCount($where,$profileid)
	{
		try{
			if($where)
			{
				$sql="select  COUNT(*) AS CNT FROM newjs.JPARTNER where ";
			
				foreach($where as $key=>$val)
				{
					$arr[]="$key:$key";
				}
				$sql.=implode(" and ",$arr);
				$res = $this->db->prepare($sql);
				foreach($where as $key=>$val)
				{
					$res->bindValue(":".$key,$val[0],$val[1]);
				}
				$res->execute();
				$row = $res->fetch(PDO::FETCH_ASSOC);
				return $row[CNT];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}	
	}
	public function UpdatePage5($partnerObj)
	{
		try{
			$sql="update newjs.JPARTNER set LAGE=:LAGE,HAGE=:HAGE,LHEIGHT=:LHEIGHT,HHEIGHT=:HHEIGHT,LINCOME=:LINCOME,HINCOME=:HINCOME,LINCOME_DOL=:LINCOME_DOL,HINCOME_DOL=:HINCOME_DOL,PARTNER_CASTE=:CASTE,PARTNER_RELIGION=:RELIGION,PARTNER_MSTATUS=:MSTATUS,PARTNER_MTONGUE=:MTONGUE,DATE=now() where PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":LAGE",$partnerObj->getLAGE(),PDO::PARAM_STR);
			$res->bindValue(":HAGE",$partnerObj->getHAGE(),PDO::PARAM_STR);
			$res->bindValue(":LHEIGHT",$partnerObj->getLHEIGHT(),PDO::PARAM_STR);
			$res->bindValue(":HHEIGHT",$partnerObj->getHHEIGHT(),PDO::PARAM_STR);
			$res->bindValue(":LINCOME",$partnerObj->getLINCOME(),PDO::PARAM_STR);
			$res->bindValue(":HINCOME",$partnerObj->getHINCOME(),PDO::PARAM_STR);
			$res->bindValue(":LINCOME_DOL",$partnerObj->getLINCOME_DOL(),PDO::PARAM_STR);
			$res->bindValue(":HINCOME_DOL",$partnerObj->getHINCOME_DOL(),PDO::PARAM_STR);
			$res->bindValue(":CASTE",$partnerObj->getCASTE(),PDO::PARAM_STR);
			$res->bindValue(":RELIGION",$partnerObj->getRELIGION(),PDO::PARAM_STR);
			$res->bindValue(":MSTATUS",$partnerObj->getMSTATUS(),PDO::PARAM_STR);
			$res->bindValue(":MTONGUE",$partnerObj->getMTONGUE(),PDO::PARAM_STR);
			$res->bindValue(":PROFILEID",$partnerObj->getPROFILEID(),PDO::PARAM_INT);
			
			$res->execute();
			
			
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	
	public function isDppSetByUser($profileId){
		try{
			$sql = "SELECT DPP FROM newjs.JPARTNER where PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output = $row['DPP'];
			}
			return $output;
		} catch (Exception $e){
			throw new jsException($e);
		}
	}
	public function selectPartnerCaste($p_caste,$offset,$limit)
	{
                try
                {
                        $sql  =  "SELECT PROFILEID,PARTNER_CASTE FROM newjs.JPARTNER WHERE PARTNER_CASTE LIKE :PARTNER_CASTE LIMIT ".$offset.",".$limit;
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PARTNER_CASTE","%".$p_caste."%",PDO::PARAM_STR);
                        $res->execute();
			while ($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[]=$row;
			}
			return $output;
                }
                catch (Exception $e){
                        throw new jsException($e);
                }
	}
	public function updateCaste($profileid,$caste,$oldCaste)
	{
		try
		{
			$sql  =  "UPDATE newjs.JPARTNER SET PARTNER_CASTE=:PARTNER_CASTE WHERE PROFILEID=:PROFILEID AND PARTNER_CASTE=:OLD_CASTE";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->bindValue(":PARTNER_CASTE",$caste,PDO::PARAM_STR);
			$res->bindValue(":OLD_CASTE",$oldCaste,PDO::PARAM_STR);
			$res->execute();
                }
		catch (Exception $e){
                        throw new jsException($e);
                }
	}	

	public function getDppDataForProfiles($limit,$offset)
	{
		try
		{
			$sql = "SELECT PROFILEID,GENDER,LINCOME,HINCOME from newjs.JPARTNER LIMIT :OFFSETVAL, :LIMITVAL";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":LIMITVAL", $limit, PDO::PARAM_INT);
			$prep->bindParam(":OFFSETVAL", $offset, PDO::PARAM_INT);
			$prep->execute();
			while ($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$resultArr[] = $row;          
			}

			return $resultArr;
		}
		catch (Exception $e){
			throw new jsException($e);
		}
	}

	public function updateIncomeValueForProfile($profileId,$hincome,$partnerIncome,$oldValue)
	{
		try
		{
			$sql = "UPDATE newjs.JPARTNER set HINCOME = :HINCOME , PARTNER_INCOME = :PARTNERINCOME WHERE PROFILEID = :PROFILEID AND HINCOME = :OLDVALUE";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":HINCOME", $hincome, PDO::PARAM_INT);
			$prep->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->bindParam(":OLDVALUE", $oldValue, PDO::PARAM_INT);
			$prep->bindParam(":PARTNERINCOME", $partnerIncome, PDO::PARAM_STR);
			$prep->execute();
		}
		catch (Exception $e){
			throw new jsException($e);
		}
	}
		
}
?>
