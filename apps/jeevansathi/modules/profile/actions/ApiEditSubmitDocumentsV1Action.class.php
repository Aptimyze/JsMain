<?php

/**
 * profile actions.
 * ApiEditSubmitDocumentsV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api 
 * @author     Md. Shahjahan
 */
class ApiEditSubmitDocumentsV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
        ob_start();
        sfContext::getInstance()->getController()->getPresentationFor('profile','ApiEditSubmitV1');
        $returnDocumentUpload = ob_get_contents(); 
        ob_end_clean();
       
        if($request->getParameter('internally'))
        {
            return sfView::NONE;
        }
        return $returnDocumentUpload;
        die();
	}
}