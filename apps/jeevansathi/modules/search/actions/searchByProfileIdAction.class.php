<?php

/**
 * profile actions.
 * searchByProfileId
 * Controller to search profiles by typing profile id
 * @package    jeevansathi
 * @subpackage search
 * @author     Nitesh Sethi
 */
class searchByProfileIdAction extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$this->PREV_URL=$_SERVER['HTTP_REFERER'];
	}
	
}
