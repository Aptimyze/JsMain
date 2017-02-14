<?php
/**
 * JSADMIN_VIEW_CONTACTS_LOG
 * 
 * This class handles all database queries to JSADMIN_VIEW_CONTACTS_LOG 
 * @package    FTO
 * @author     Nitesh Sethi
 * @created    2012-11-16
 * @version 2.0   SVN: $Id: JSADMIN_VIEW_CONTACTS_LOG.class.php  2012.11.27 hemant.a $
 */
class JSADMIN_VIEW_CONTACTS_LOG extends TABLE{

/**
* @fn __construct
* @brief Constructor function
* @param $dbName - Database name to which the connection would be made
*/
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
 

/**
 * @fn alreadyContact
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewedPid 
 * @return count
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
		
	public function alreadyContact($viewerPid,$viewedPid)
	{
		try
		{
			
			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER=:viewerPid AND VIEWED=:viewedPid AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
			$prep=$this->db->prepare($sql);
							
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return (($result['CNT']) ? 1 : 0);
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
	function insertAllotedContacts($viewerPid,$viewedPid,$source)
	{
		try
		{
			$date=date("Y-m-d G:i:s");
			$sql="INSERT IGNORE INTO jsadmin.VIEW_CONTACTS_LOG (`VIEWER`,`VIEWED`,`DATE`,`SOURCE`) VALUES(:viewerPid,:viewedPid,:date,:source)";
			$prep=$this->db->prepare($sql);
						
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			$prep->bindValue(":date", $date, PDO::PARAM_STR);
			$prep->bindValue(":source", $source, PDO::PARAM_STR);
			$prep->execute();
			
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function checkSpamForReceiver($viewedId, $viewedCount)
	{
		try
		{
			$today = date("Y-m-d");
			$sql = "SELECT COUNT(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED=:VIEWED AND DATE BETWEEN '$today 00:00:00' AND '$today 23:59:59' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
			$prep=$this->db->prepare($sql);
							
			$prep->bindValue(":VIEWED", $viewedId, PDO::PARAM_INT);
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return (($result['CNT'] >= $viewedCount) ? 1 : 0);
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
		
	}
	public function totalContactsViewed($viewedPid)
	{
		try
		{
			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED=:viewedPid AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result['CNT'];
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	public function totalContactsByViewer($viewerPid)
	{
		try
		{
			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER=:viewerPid AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result['CNT'];
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	/**
 * @fn 	public function FinalTotalContactsViewed($viewerPid)
RETURNS TOTAL NO VIEWED PRFOLIES COUNT OF A VIEWER PROFILE
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewerPid 
 * @return count
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
	public function FinalTotalContactsViewed($viewerPid)
	{
		try
		{
			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER=:viewerPid AND SOURCE='".CONTACT_ELEMENTS::EVALUE_TRACKING."'";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result['CNT'];
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	/**
 * @fn 	public function alreadyEvalueContactViewed($viewerPid,$viewedPid)
RETURNS if a profile is viewed by a user or not
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewerPid 
 * @return count
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
	public function alreadyEvalueContactViewed($viewerPid,$viewedPid)
	{
		try
		{
			
			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER=:viewerPid AND VIEWED=:viewedPid AND SOURCE='".CONTACT_ELEMENTS::EVALUE_TRACKING."'";
			$prep=$this->db->prepare($sql);
							
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return (($result['CNT']) ? 1 : 0);
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
	/**
 * @fn 	public function insertReplaceAllotedContacts($viewerPid,$viewedPid)
update or insert if a profile is viewed by a user using call directly feature 
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewerPid 
 * @return count
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
	public function insertReplaceAllotedContacts($viewerPid,$viewedPid,$source)
	{
		try
		{
			
			$date=date("Y-m-d G:i:s");
			$sql="Replace INTO jsadmin.VIEW_CONTACTS_LOG (`VIEWER`,`VIEWED`,`DATE`,`SOURCE`) VALUES(:viewerPid,:viewedPid,:date,:source)";
			$prep=$this->db->prepare($sql);
						
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			$prep->bindValue(":date", $date, PDO::PARAM_STR);
			$prep->bindValue(":source", $source, PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

/**
 * @fn 	public function getContactedProfileArray($viewerPid)
RETURNS TOTAL NO VIEWED PRFOLIES array OF A VIEWER PROFILE
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewerPid 
 * @return PROFILEIDS 
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
	public function getContactedProfileArray($viewerPid,$skipArray='',$limit='')
	{//print_r($skipArray); die;
		try
		{
			$str='VIEWER=:viewerPid ';

			if(is_array($skipArray))
		{ 
				$count=0;
				$str .= "AND VIEWED NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr[":VALUE".$count]["VALUE"] = $value;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
		}	
			$sql = "SELECT VIEWED,date(DATE) as DT FROM jsadmin.VIEW_CONTACTS_LOG WHERE ".$str."  ORDER BY DATE DESC";
			if($limit!='')
				$sql.=" LIMIT :LIMIT";
		$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			if($limit!='')
				$prep->bindValue(":LIMIT", intval($limit), PDO::PARAM_INT);
			foreach($bindArr as $k=>$v)
			{
				
					$prep->bindValue($k,$v["VALUE"],PDO::PARAM_INT); //here all are ints so no need to check for type of the variable.. but in case variables are other than the pid then the binding should be done onthe basis of type.
				
			}
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['VIEWED']]["TIME"]=$row['DT'];
			}



			return $result;
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}

/**
 * @fn 	public function getContactedProfileArray($viewerPid)
RETURNS ALL THE PROFILES WHO VIEWED THE CONTACTS OF THE PROFILEID PASSED IN PARAMETERS
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewedPid 
 * @return PROFILEIDS 
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
	public function getProfilesWhoViewedMyContacts($viewedPid,$skipArray='',$limit='')
	{
		try
		{

			$str='VIEWED=:viewedPid ';

			if(is_array($skipArray))
		{ 
				$count=0;
				$str .= "AND VIEWER NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr[":VALUE".$count]["VALUE"] = $value;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
		}

			$sql = "SELECT VIEWER,date(DATE) as DT FROM jsadmin.VIEW_CONTACTS_LOG WHERE ".$str." ORDER BY DATE DESC";
			if($limit!='')
				$sql.=" LIMIT :LIMIT";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			if($limit!='')
				$prep->bindValue(":LIMIT", intval($limit), PDO::PARAM_INT);
			foreach($bindArr as $k=>$v)
			{
				
					$prep->bindValue($k,$v["VALUE"],PDO::PARAM_INT); //here all are ints so no need to check for type of the variable.. but in case variables are other than the pid then the binding should be done onthe basis of type.
				
			}
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['VIEWER']]["TIME"]=$row['DT'];
			}
			return $result;
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}


/**
 * @fn 	public function totalContactsViewedEver($viewerPid)
RETURNS TOTAL NO VIEWED PRFOLIES array OF A VIEWER PROFILE
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewerPid viewerPid 
 * @return PROFILEIDS 
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */
	public function totalContactsViewedEver($viewerPid,$skipArray)
	{
		try
		{
			$viewerObj= LoggedInProfile::getInstance("",$viewerPid);
			$viewerObj->getDetail("","","SUBSCRIPTION");			
			if(CommonFunction::isPaid($viewerObj->getSUBSCRIPTION()))
			{
				$str='VIEWER=:viewerPid ';
				if(is_array($skipArray))
					{ 
				$count=0;
				$str .= "AND VIEWED NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr[":VALUE".$count]["VALUE"] = $value;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
					}

				$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE ".$str;
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
				foreach($bindArr as $k=>$v)
			{
				
					$prep->bindValue($k,$v["VALUE"],PDO::PARAM_INT); //here all are ints so no need to check for type of the variable.. but in case variables are other than the pid then the binding should be done onthe basis of type.
				
			}
				$prep->execute();
				$result = $prep->fetch(PDO::FETCH_ASSOC);
			}
			else
				$result['CNT']=0;
			return $result['CNT'];
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
/**
 * @fn 	public function totalContactsViewersEver($viewerPid)
RETURNS TOTAL NO of viewers of the given profile
 * @brief fetches results from jsadmin.VIEW_CONTACTS_LOG
 * @param viewedPid 
 * @return PROFILEIDS 
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */
	public function totalContactViewersEver($viewedPid,$skipArray)
	{
		try
		{
				$str='VIEWED=:viewedPid ';
				if(is_array($skipArray))
		{ 
				$count=0;
				$str .= "AND VIEWER NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr[":VALUE".$count]["VALUE"] = $value;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
		}

			$sql = "SELECT count(*) CNT FROM jsadmin.VIEW_CONTACTS_LOG WHERE ".$str;
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":viewedPid", $viewedPid, PDO::PARAM_INT);
			foreach($bindArr as $k=>$v)
			{
				
					$prep->bindValue($k,$v["VALUE"],PDO::PARAM_INT); //here all are ints so no need to check for type of the variable.. but in case variables are other than the pid then the binding should be done onthe basis of type.
				
			}
			$prep->execute();
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result['CNT'];
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	public function countDirectContactsView($profileid, $start_dt, $end_dt)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM jsadmin.`VIEW_CONTACTS_LOG` WHERE `VIEWER`=:PROFILEID AND `SOURCE`='D' AND `DATE`>=:START_DT AND `DATE`<=:END_DT";
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
        public function countContactsViewForDates($profileid, $start_dt, $end_dt)
        {
                try{
                        $sql = "SELECT VIEWED,DATE FROM jsadmin.`VIEW_CONTACTS_LOG` WHERE `VIEWER`=:PROFILEID AND `DATE`>=:START_DT AND `DATE`<=:END_DT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        while($row = $prep->fetch(PDO::FETCH_ASSOC)){
				$pid =$row['VIEWED'];
                                $res[$pid] =$row;
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }
    public function getViewedContact($viewedProfiles, $startDt, $endDt)
    {
        try{
            if(is_array($viewedProfiles)){
                $str = "VIEWED IN (";
                foreach($viewedProfiles as $key => $val){
                    $str = $str.":VIEWED".$key.",";
                    $bindArr[":VIEWED".$key]["VALUE"] = $val['PROFILEID'];
                }
                $str = substr($str, 0, -1);
                $str = $str.") AND";
            }
            $sql = "SELECT VIEWER, VIEWED FROM jsadmin.`VIEW_CONTACTS_LOG` WHERE $str DATE >=:START_DATE AND DATE <=:END_DATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE", $startDt, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $endDt, PDO::PARAM_STR);
            if(is_array($bindArr)){
                foreach($bindArr as $key => $val){
                    $prep->bindValue($key, $val["VALUE"], PDO::PARAM_INT);
                }
            }
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row["VIEWED"]][]=$row["VIEWER"];
            }
            return $result;
        } catch (Exception $ex) {

        }
    }


		public function contactViewedOrNot($viewer, $viewed)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM jsadmin.`VIEW_CONTACTS_LOG` WHERE `VIEWER`=:PROFILEID1 AND `VIEWED`=:PROFILEID2";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID1", $viewer, PDO::PARAM_INT);
			$prep->bindValue(":PROFILEID2", $viewed, PDO::PARAM_INT);
			$prep->execute();
			$row = $prep->fetch(PDO::FETCH_ASSOC);
			if($row['CNT']>0)
			return true;
			else return false;
			}
		catch(Exception $e){
			throw new jsException($e);
		}

	}


	public function getContactViewsForTimePeriod($start_dt,$end_dt,$currentScript=0,$totalScript=1)
	{
		try
		{
			$sql="SELECT VIEWER,VIEWED FROM jsadmin.VIEW_CONTACTS_LOG WHERE `DATE` BETWEEN :STARTDATE AND :ENDDATE AND MOD(`VIEWED`,:TOTALSCRIPT)=:CURRENTSCRIPT ORDER BY `DATE` DESC";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":STARTDATE", $start_dt, PDO::PARAM_INT);
			$prep->bindValue(":ENDDATE", $end_dt, PDO::PARAM_INT);
			$prep->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
			$prep->bindValue(":TOTALSCRIPT", $totalScript, PDO::PARAM_INT);
			$prep->execute();
			$row = $prep->fetchAll(PDO::FETCH_ASSOC);
			return $row;
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}

	}


}
