<?php
/**
 * 
 * StoreTable
 * Class for running queries on store, basically used in PhpUnit Framework.
 * 
 *  
 * @package TABLE
 * @subpackage STORE TABLE
 * @author Kunal Verma
 * @created 13th April 2015
 */

/**
 * StoreTable
 * Extend Abstract Class TABLE
 */
class StoreTable extends TABLE{
	/**
	 * Constructore
	 * @access public
	 * @return void
	 */
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	/**
	 * getDBObject
	 * Getter for Data base object
	 * @return DB Object
	 * @access public
	 */
	public function getDBObject()
	{
		return $this->db;
	}
}
?>
