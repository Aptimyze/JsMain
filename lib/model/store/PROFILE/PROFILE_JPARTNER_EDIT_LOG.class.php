<?php
/**
 * @brief This class is store class for tracking edits of DPP (PROFILE.JPARTNER_EDIT_LOG  table)
 * @author Reshu Rajput
 * @created Jun 10 2016
 */

class PROFILE_JPARTNER_EDIT_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /*
        * This Function is to udpate partner prefence edit log into the table.
        * @param updateArr key-value pair for records to be updated.
        */
	public function addRecords($updateArr)
	{
                try
                {
			$key = 'DATE';
			$updateArr[$key] = date("Y-m-d H:i:s");
		
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

										$bindMeStr[$k] = $v;
					}
        }
			}

			$columnNames = rtrim($columnNames,",");
			$values = rtrim($values,",");
			
			$sql = "INSERT INTO PROFILE.JPARTNER_EDIT_LOG ($columnNames) VALUES ($values)";
			
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
			jsException::nonCriticalError("No Insertion In JPARTNER_EDIT_LOG");
		}
	}

        /**
        This function is used to get dpp information (JPARTNER edit log table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array dpp search paramters info. Return null in case of no matching rows found.
        **/
	public function get($paramArr=array(),$fields="*")
	{
		foreach($paramArr as $key=>$val)
                	${$key} = $val;

                if(!$PROFILEID)
                        jsException::nonCriticalError("PROFILEID IS BLANK IN get() of JPARTNER_EDIT_LOG.class.php");
                try
		{
			$detailArr='';

                        $sql = "SELECT $fields FROM PROFILE.JPARTNER_EDIT_LOG WHERE ";
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
                     jsException::nonCriticalError($e);
                }
                return NULL;
	}

	
		
}
?>
