<?php
/**
* This class will set the connections of Mongo Database and also set database of the connection.
* @package    jeevansathi
* @author     Lavesh Rawat
* @created    14-03-2011
*/
abstract class MongoTable{

	protected $db; 	
	protected $dbName;

	/**
	* @fn __construct
	* @brief Constructor function
	* @param $dbName - database name to which the connection would be made
	*/
	protected function __construct($dbName="master") {
		$this->dbName = $dbName?$dbName:"mongodb"; // Set default connection to newjs_master
                $this->db = jsDatabaseManager::getInstance()->getDatabase($this->dbName)->getConnection();//Get connection
	}
  
  /**
   * cursorIntoArray
   * Convert mongo cursor into an array 
   * @param type $resultCursor
   */
  public function cursorIntoArray($resultCursor)
  {
    return  (json_decode(json_encode(iterator_to_array($resultCursor)),true));
  }
}
?>
