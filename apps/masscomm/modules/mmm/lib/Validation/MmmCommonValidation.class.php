<?php
/*
* This class will contain the common validation .....
* @author lavesh
*/
class MmmCommonValidation
{
	/**
	* This function checks if value is not string of spaces ....
	* @param $value
	* @return Boolean
	*/
	public static function valueExists($value)
	{
		return trim($value)=='';
	}
	
	/**
	* This function will validate the content of a url for any smarty error present .....
	* @param url web url of a html file
	*/
	public static function validateUrlForSmartyErrors($url,$mailerId='')
	{
		$smarty = MmmUtility::createSmartyObject();
		$testPath = $smarty->template_dir."test";
		if($mailerId)
			$name = $mailerId."_".time();
		else
			$name = rand(1,1000000000)."_".time();
		$pathOfHtmlFile = $testPath."/".$name.".html";

		$handle = fopen($pathOfHtmlFile, "w+");

		if(CommonUtility::isUrlExistsUsingCurl($url)=='N')
			return 'D';

		$content = CommonUtility::sendCurlGetRequest($url);

		if(strpos($content,'<body>') === false) return 'B';

		fwrite($handle, $content);
		fclose($handle);
                $SmartyValidation = new SmartyValidation;
                $response = SmartyValidation::isSyntaxError($smarty,$pathOfHtmlFile);
		unlink($pathOfHtmlFile);
		if($response=='E')
			return $response;
	}
}
?>
