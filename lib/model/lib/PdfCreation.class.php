<?php
class PdfCreation{
static function PdfFile($url,$isArray=0)
{
	if($isArray)
	{
		$urlTemp=$url;
		unset($url);
		foreach($urlTemp as $key=>$val)
			$url.= " \"".$val. "\"";
	}
	else
		$url= " \"".$url. "\"";
	$cmd = sfConfig::get("sf_root_dir")."/".sfConfig::get("app_wkhtmltopdf")." $url -";
	  $fp = popen($cmd,"r");
          while (!feof($fp)) { 
              $file.=fread($fp, 1024); 
          }
          return $file;
 }
 static function setResponse($filename,$filecontent)
 {
		
		$context = sfContext::getInstance();
		$response = $context->getResponse();
		$response->clearHttpHeaders();
		$response->setHttpHeader('Pragma: public', true);
		$response->setContentType('application/pdf');
		$response->setHttpHeader('Content-Disposition','attachment; filename="'.$filename.'"');

		$response->setContent($filecontent);	
		$response->sendHttpHeaders();
		pclose($fp);
 }
}         
