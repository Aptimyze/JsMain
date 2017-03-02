<?php
/**
 * NEWJS_YOUR_INFO_OLD
 * 
 * This class handles all database queries to NEWJS_YOUR_INFO_OLD_YOUR_INFO_OLD 
 * @package    FTO
 * @author     Nitesh Sethi
 * @created    2013-01-24
 * @version 2.0   SVN: $Id: JSADMIN_VIEW_CONTACTS_LOG.class.php  2012.11.27 hemant.a $
 */
class YOUR_INFO_OLD extends TABLE{

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
 * @fn updateAboutMeLog
 * @brief fetches results from newjs.YOUR_INFO_OLD
 * @param PROFILEID $loginProfileId 
 * @param YOURINFO $oldInfo
 * @return 
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */	
		
	public function updateAboutMeOld($loginProfileId,$oldInfo)
	{
		try
		{
			$sql = " REPLACE INTO newjs.YOUR_INFO_OLD VALUES (:pid,:oldInfo)" ;
			$prep=$this->db->prepare($sql);
							
			$prep->bindValue(":pid", $loginProfileId, PDO::PARAM_INT);
			$prep->bindValue(":oldInfo", $oldInfo, PDO::PARAM_STR);
			
			$prep->execute();
			
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

	/**
	 * @fn getAboutMeOld
	 * @brief fetches results from newjs.YOUR_INFO_OLD
	 * @param PROFILEID $loginProfileId 
	 * @return YOUR_INFO_OLD
	 * @exception jsException for blank criteria
	 * @exception PDOException for database level error handling
	 */	

	public function getAboutMeOld($profileId)
	{
		try
		{
			if ( $profileId !== NULL )
			{
				$sql = "SELECT YOUR_INFO_OLD FROM newjs.YOUR_INFO_OLD WHERE PROFILEID = :PFID" ;
				$prep=$this->db->prepare($sql);
								
				$prep->bindValue(":PFID", $profileId, PDO::PARAM_INT);			
				$prep->execute();

				$rowSelectDetail = $prep->fetch(PDO::FETCH_ASSOC);
				return $rowSelectDetail['YOUR_INFO_OLD'];
			}
			return NULL;   
			
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
	

	}


