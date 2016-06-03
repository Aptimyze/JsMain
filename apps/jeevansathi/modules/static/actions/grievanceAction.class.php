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

class grievanceAction extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	
	public function execute($request)
	{ 
		if(MobileCommon::isMobile())
				$this->forward("faq","feedback");

		$type=$request->getParameter("type");
		$whichLayout = $request->getParameter("grievance");
		if($whichLayout == "1")
		{
			$this->layout=1;
			$title="Jeevansathi Matrimonials- Griveance";
		}
		else
		{
			$this->layout=0;
			$title="Jeevansathi Matrimonials- Summon";
		}
		$response=sfContext::getInstance()->getResponse();		
		$title=htmlspecialchars_decode($title,ENT_QUOTES);
		$response->setTitle($title);
		$type_index=$this->allowed[$request->getParameter("type")]?$this->allowed[$request->getParameter("type")]:"disclaimer";	
	}
}
?>
