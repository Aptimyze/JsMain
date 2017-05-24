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

class pagev1Action extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	private $allowed=array("disclaimer"=>1,"privacypolicy"=>2,"thirdparty"=>3,"privacyfeatures"=>4,"fraudalert"=>5);
	private $inner=array(1=>"terms",2=>"policy",3=>"thirdpar",4=>"features",5=>"fraud");
	public function execute($request)
	{
		//echo("aaa");die;
		$apiObj=ApiResponseHandler::getInstance();
		$type=$request->getParameter("type");
		$type_index=$this->allowed[$request->getParameter("type")]?$this->allowed[$request->getParameter("type")]:"disclaimer";
		$this->innerTemplate=$this->inner[$type_index];
		if($this->innerTemplate)
			$data[data]=$this->getPartial($this->innerTemplate);
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($data);
		$apiObj->generateResponse();
		die;
	}
}
?>
