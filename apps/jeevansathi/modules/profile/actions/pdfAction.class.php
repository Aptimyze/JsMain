<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 */
class pdfAction extends sfAction
{
        public function execute($request)
	{
          
          $response = $this->getResponse();

          $profilechecksum=$request->getParameter("profilechecksum");
	      $username=$request->getParameter("username");
       
          $url=sfConfig::get("app_site_url")."/profile/viewprofile.php?PRINT=1&pdf=yes&profilechecksum=".$profilechecksum;
          
          $file=PdfCreation::PdfFile($url);
          // Adding the file to the Response object
          $response->clearHttpHeaders();
          $response->setHttpHeader('Pragma: public', true);
          $response->setContentType('application/pdf');
          $response->setHttpHeader('Content-Disposition','attachment; filename="'.$username.'.pdf"');

	      $response->setContent($file);	
          $response->sendHttpHeaders();
          pclose($fp);	
          return sfView::NONE;
        }
} 
