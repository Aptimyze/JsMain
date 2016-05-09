<?php
/**
 * MIS_PHONEBOOK
 * 
 * This class handles all database queries to MIS_PHONEBOOK 
 * @package    FTO
 * @author     Nitesh Sethi
 * @created    2015-05-27
 * @version 2.0   SVN: $Id: MIS_PHONEBOOK.class.php  2012.11.27 hemant.a $
 */
class MIS_PHONEBOOK extends TABLE{

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
 * @fn trackUserUsingPhonebook
 * @brief fetches results from MIS_PHONEBOOK
 * @param viewerPid viewedPid 
 * @return count
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
		
	public function trackUserUsingPhonebook($viewerPid)
	{
		try
		{
			$sql="INSERT INTO MIS.PHONEBOOK (VIEWER ,DATE) VALUES (:viewerPid,:date)";
			$prep=$this->db->prepare($sql);
			$date=date("Y-m-d G:i:s");
			$prep->bindValue(":viewerPid", $viewerPid, PDO::PARAM_INT);
			$prep->bindValue(":date", $date, PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
