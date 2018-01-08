<?php

/**
 * Auto Select actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
/**
 * Auto Select feature.<p></p>
 * 	
 *  
 * @author Nikhil dhiman
 */

class autoSelectAction extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	private $_type;
	private $_default;
	private $_linked;
	private $_query;
	private $_json;
	private $defaultExist;
	 /**
     * Automatically calls before the action to execute.
     *
     */
	public function preExecute()
	{
		$this->defaultExist=0;
		$this->fObj=new FieldOrder;
	}
	
	/**
     * Handles Detailed profile of user, all validations, 
     * error message are handled in this.
     *@param $request contains sfWebrequest parameter send by symfony
     *
     */
	public function execute($request)
	{
		$this->getResponse()->setContentType('application/json');
		$type=$request->getParameter("t");
		$def=$this->updateDefault($request);
		$linked=explode(",",$request->getParameter("l"));
		$query=$request->getParameter("q");
		$this->fObj->setDefault($type,$linked,$query,$def);
		
		$this->fObj->UpdateSelect();
		
		echo json_encode($this->fObj->getJson());
		return sfView::NONE;
			
	}
	/**
	 * 
	 */
	 public function updateDefault($request)
	 {
			$def=$request->getParameter("d");
			if($def && $def!='null')
				$default=explode(",",$def);
			else
				$default=array();
			return $default;
	 }
	
}
?>
