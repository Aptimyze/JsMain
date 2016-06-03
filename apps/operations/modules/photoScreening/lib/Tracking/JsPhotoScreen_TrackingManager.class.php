<?php
/**
 * JsPhotoScreen_TrackingManager Class 
 * Implementing logic for calling tracking as per given parameters
 * Possible list of paramter as mentioned in JsPhotoScreen_Enum::$arrTRACKING_PARAMS
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 20th Sept 2014
 */
/**
 * JsPhotoScreen_TrackingManager
 * 
 * @module  PhotoScreen Tracking
 * @author  Kunal Verma
 */

class JsPhotoScreen_TrackingManager
{	
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * m_enCurrentOperation : Specify which type of tracking to be called
	 * @access private
	 * @var Enum 
	 */
	private $m_enCurrentOperation;
	
	/**
	 * m_arrParams : Array of tracking params
	 * @access private
	 * @var Integer 
	 */
	private $m_arrParams;
	
	/**
	 * Declaring and Defining Member Function
	 */
	 
	/**
	 * Constructor
	 * @access public
	 * @param $arrParams : Array of tracking params
	 * @return void
	 */
	public function __construct($arrParams)
	{
		//Ctor
		//Init member varaibles
		$this->m_enCurrentOperation = $arrParams[JsPhotoScreen_Enum::$arrTRACKING_PARAMS['SOURCE']];
		$this->m_arrParams = $arrParams;
		if(!$this->isValidOperation())
		{
			throw new jsException("","Invalid operation specified in JsPhotoScreen_TrackingManager");
		}
		$this->Process();
	}
	
	/**
	 * Process
	 * The tracking request : this is called in constructor
	 * @access private
	 * @param void
	 * @return void
	 */
	private function Process()
	{
		//Run logic
		try{
			$objSourceTracking = null;
			switch($this->m_enCurrentOperation)
			{
				case JsPhotoScreen_Enum::enTRACK_SOURCE_NEW :
				{
					$objSourceTracking = new JsPhotoScreen_Track_SourceNew($this->m_arrParams);
				}
				break;
				case JsPhotoScreen_Enum::enTRACK_SOURCE_EDIT :
				{
					$objSourceTracking = new JsPhotoScreen_Track_SourceEdit($this->m_arrParams);
				}
				break;
			}
			$objSourceTracking->trackThis(); 
			if($this->m_arrParams['TRACK_WRONG_ENTRY'])
			{
				$objSourceTracking->trackWrongScreeningEntries();
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	/**
	 * isValidOperation
	 * Check for the valid operation 
	 * @access private
	 * @param void
	 * @return Boolean 
	 */
	private function isValidOperation()
	{
		if($this->m_enCurrentOperation == JsPhotoScreen_Enum::szTRACK_SOURCE_NEW)
			$this->m_enCurrentOperation = JsPhotoScreen_Enum::enTRACK_SOURCE_NEW;
		
		if($this->m_enCurrentOperation == JsPhotoScreen_Enum::szTRACK_SOURCE_EDIT)
			$this->m_enCurrentOperation = JsPhotoScreen_Enum::enTRACK_SOURCE_EDIT;
		
		return ($this->m_enCurrentOperation && is_numeric($this->m_enCurrentOperation) && 
				$this->m_enCurrentOperation < JsPhotoScreen_Enum::enTRACK_SOURCE_END && 
				$this->m_enCurrentOperation >= JsPhotoScreen_Enum::enTRACK_SOURCE_NEW );
	}
}
?>
