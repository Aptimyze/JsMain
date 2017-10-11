<?php

if (!defined ('AJAX_DEFAULT_CHAR_ENCODING'))
{
	define ('AJAX_DEFAULT_CHAR_ENCODING', 'utf-8' );
}

require_once("ajaxResponse.inc.php");

// Communication Method Defines
if (!defined ('AJAX_GET'))
{
	define ('AJAX_GET', 0);
}
if (!defined ('AJAX_POST'))
{
	define ('AJAX_POST', 1);
}

// the ajax class generates the ajax javascript for your page including the
// javascript wrappers for the PHP functions that you want to call from your page.
// It also handles processing and executing the command messages in the xml responses
// sent back to your page from your PHP functions.
class ajax
{
	var $aFunctions;		
	var $aObjects;			
	var $aFunctionRequestTypes;	
	var $aFunctionIncludeFiles;	
	var $sCatchAllFunction;		
	var $sPreFunction;		
	var $sRequestURI;		
	var $bDebug;			
	var $bExitAllowed;		
	var $bErrorHandler;	
	var $sLogFile;	
	var $sWrapperPrefix;		
	var $bStatusMessages;		
	var $bWaitCursor;	
	var $bCleanBuffer;
	var $aObjArray;			
	var $iPos;		
	var $sEncoding;	

	// Contructor
        // $sRequestURI - defaults to the current page
        // $sWrapperPrefix - defaults to "ajax_";
        // $sEncoding - defaults to AJAX_DEFAULT_CHAR_ENCODING defined above
        // $bDebug Mode - defaults to false
        // usage: $ajax = new ajax();	
	function ajax($sRequestURI="",$sWrapperPrefix="ajax_",$sEncoding=AJAX_DEFAULT_CHAR_ENCODING,$bDebug=false)
	{
		$this->aFunctions = array();
		$this->aObjects = array();
		$this->aFunctionIncludeFiles = array();
		$this->sRequestURI = $sRequestURI;
		if ($this->sRequestURI == "")
			$this->sRequestURI = $this->_detectURI();
		$this->sWrapperPrefix = $sWrapperPrefix;
		$this->setCharEncoding($sEncoding);
		$this->bDebug = $bDebug;
		$this->bWaitCursor = false;
		$this->bStatusMessages = true;
		$this->bExitAllowed = true;
		$this->bErrorHandler = false;
		$this->sLogFile = "";
		$this->bCleanBuffer = true;
	}

	// setRequestURI() sets the URI to which requests will be made	
	function setRequestURI($sRequestURI)
	{
		$this->sRequestURI = $sRequestURI;
	}

	// debugOn() enables debug messages for ajax	
	function debugOn()
	{
		$this->bDebug = true;
	}
	
	// debugOff() disables debug messages for ajax (default behavior)
	function debugOff()
	{
		$this->bDebug = false;
	}
	
	// statusMessagesOn() enables messages in the statusbar for ajax
	function statusMessagesOn()
	{
		$this->bStatusMessages = true;
	}
	
	// statusMessagesOff() disables messages in the statusbar for ajax
	function statusMessagesOff()
	{
		$this->bStatusMessages = false;
	}
	
	// waitCursor() enables the wait cursor to be displayed in the browser 
	function waitCursorOn()
	{
		$this->bWaitCursor = true;
	}
	
	// waitCursorOff() disables the wait cursor to be displayed in the browser
	function waitCursorOff()
	{
		$this->bWaitCursor = false;
	}	
	
	// exitAllowedOn() enables ajax to exit immediately after processing a request
	// and sending the response back to the browser (default behavior)
	function exitAllowedOn()
	{
		$this->bExitAllowed = true;
	}
	
	// opposite of the above one
	function exitAllowedOff()
	{
		$this->bExitAllowed = false;
	}
	
	// errorHandlerOn() turns on ajax's error handling system so that PHP errors
        // that occur during a request are trapped and pushed to the browser in the
        // form of a Javascript alert
	function errorHandlerOn()
	{
		$this->bErrorHandler = true;
	}

	function errorHandlerOff()
	{
		$this->bErrorHandler = false;
	}
	
	// setLogFile() specifies a log file that will be written to by ajax during
        // a request (used only by the error handling system at present). If you don't
        // invoke this method, or you pass in "", then no log file will be written to.
	function setLogFile($sFilename)
	{
		$this->sLogFile = $sFilename;
	}

	// cleanBufferOn() causes ajax to clean out all output buffers before outputting
        // a response (default behavior)
	function cleanBufferOn()
	{
		$this->bCleanBuffer = true;
	}
	
	function cleanBufferOff()
	{
		$this->bCleanBuffer = false;
	}
	
	// setWrapperPrefix() sets the prefix that will be appended to the Javascript
        // wrapper functions (default is "ajax_")
	function setWrapperPrefix($sPrefix)
	{
		$this->sWrapperPrefix = $sPrefix;
	}

	// setCharEncoding() sets the character encoding to be used by ajax
        // usage: $ajax->setCharEncoding("utf-8");	
	function setCharEncoding($sEncoding)
	{
		$this->sEncoding = $sEncoding;
	}
	
	// registerFunction() registers a PHP function or method to be callable through
        // ajax in your Javascript. If you want to register a function, pass in the name
        // of that function.
	// $mFunction is a string containing the function name or an object callback array
        // $sRequestType is the RequestType (AJAX_GET/AJAX_POST) that should be used
        // for this function.  Defaults to AJAX_POST.
	function registerFunction($mFunction,$sRequestType=AJAX_POST)
	{
		if (is_array($mFunction)) {
			$this->aFunctions[$mFunction[0]] = 1;
			$this->aFunctionRequestTypes[$mFunction[0]] = $sRequestType;
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}	
		else {
			$this->aFunctions[$mFunction] = 1;
			$this->aFunctionRequestTypes[$mFunction] = $sRequestType;
		}
	}
	
	// registerExternalFunction() registers a PHP function to be callable through ajax
        // which is located in some other file.  If the function is requested the external
        // file will be included to define the function before the function is called
	// $sIncludeFile is a string containing the path and filename of the include file
        // $sRequestType is the RequestType (AJAX_GET/AJAX_POST) that should be used
        // for this function.  Defaults to AJAX_POST.
	function registerExternalFunction($mFunction,$sIncludeFile,$sRequestType=AJAX_POST)
	{
		$this->registerFunction($mFunction, $sRequestType);
		
		if (is_array($mFunction)) {
			$this->aFunctionIncludeFiles[$mFunction[0]] = $sIncludeFile;
		}
		else {
			$this->aFunctionIncludeFiles[$mFunction] = $sIncludeFile;
		}
	}

	// registerCatchAllFunction() registers a PHP function to be called when ajax cannot
        // find the function being called via Javascript. Because this is technically
        // impossible when using "wrapped" functions, the catch-all feature is only useful
        // when you're directly using the ajax.call() Javascript method. Use the catch-all
        // feature when you want more dynamic ability to intercept unknown calls and handle
        // them in a custom way.
	function registerCatchAllFunction($mFunction)
	{
		if (is_array($mFunction)) {
			$this->sCatchAllFunction = $mFunction[0];
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}
		else {
			$this->sCatchAllFunction = $mFunction;
		}
	}

	// registerPreFunction() registers a PHP function to be called before ajax calls
        // the requested function. ajax will automatically add the request function's response
        // to the pre-function's response to create a single response.	
	function registerPreFunction($mFunction)
	{
		if (is_array($mFunction)) {
			$this->sPreFunction = $mFunction[0];
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}
		else {
			$this->sPreFunction = $mFunction;
		}
	}
	
	// returns true if ajax can process the request, false if otherwise
	function canProcessRequests()
	{
		if ($this->getRequestMode() != -1) return true;
		return false;
	}
	
	// returns the current request mode, or -1 if there is none
	function getRequestMode()
	{
		if (!empty($_GET["ajax"]))
			return AJAX_GET;
		
		if (!empty($_POST["ajax"]))
			return AJAX_POST;
			
		return -1;
	}

	// processRequests() is the main communications engine of ajax
        // The engine handles all incoming ajax requests, calls the apporiate PHP functions
        // and passes the xml responses back to the javascript response handler
        // if your RequestURI is the same as your web page then this function should
        // be called before any headers or html has been sent.	
	function processRequests()
	{	
		
		$requestMode = -1;
		$sFunctionName = "";
		$bFoundFunction = true;
		$bFunctionIsCatchAll = false;
		$sFunctionNameForSpecial = "";
		$aArgs = array();
		$sPreResponse = "";
		$bEndRequest = false;
		$sResponse = "";
		
		$requestMode = $this->getRequestMode();
		if ($requestMode == -1) return;
	
		if ($requestMode == AJAX_POST)
		{
			$sFunctionName = $_POST["ajax"];
			
			if (!empty($_POST["ajaxargs"])) 
				$aArgs = $_POST["ajaxargs"];
		}
		else
		{	
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header ("Cache-Control: no-cache, must-revalidate");
			header ("Content-Encoding: gzip");
			header ("Pragma: no-cache");
			header("Content-type: text/xml");
			
			$sFunctionName = $_GET["ajax"];
			
			if (!empty($_GET["ajaxargs"])) 
				$aArgs = $_GET["ajaxargs"];
		}
		
		// Use ajax error handler if necessary
		if ($this->bErrorHandler) {
			$GLOBALS['ajaxErrorHandlerText'] = "";
			set_error_handler("ajaxErrorHandler");
		}
		
		if ($this->sPreFunction) {
			if (!$this->_isFunctionCallable($this->sPreFunction)) {
				$bFoundFunction = false;
				$objResponse = new ajaxResponse();
				$objResponse->addAlert("Unknown Pre-Function ". $this->sPreFunction);
				$sResponse = $objResponse->getXML();
			}
		}
		//include any external dependencies associated with this function name
		if (array_key_exists($sFunctionName,$this->aFunctionIncludeFiles))
		{
			ob_start();
			include_once($this->aFunctionIncludeFiles[$sFunctionName]);
			ob_end_clean();
		}
		
		if ($bFoundFunction) {
			$sFunctionNameForSpecial = $sFunctionName;
			if (!array_key_exists($sFunctionName, $this->aFunctions))
			{
				if ($this->sCatchAllFunction) {
					$sFunctionName = $this->sCatchAllFunction;
					$bFunctionIsCatchAll = true;
				}
				else {
					$bFoundFunction = false;
					$objResponse = new ajaxResponse();
					$objResponse->addAlert("Unknown Function $sFunctionName.");
					$sResponse = $objResponse->getXML();
				}
			}
			else if ($this->aFunctionRequestTypes[$sFunctionName] != $requestMode)
			{
				$bFoundFunction = false;
				$objResponse = new ajaxResponse();
				$objResponse->addAlert("Incorrect Request Type.");
				$sResponse = $objResponse->getXML();
			}
		}
		
		if ($bFoundFunction)
		{
			for ($i = 0; $i < sizeof($aArgs); $i++)
			{
				if (get_magic_quotes_gpc() == 1 && is_string($aArgs[$i])) {
				
					$aArgs[$i] = stripslashes($aArgs[$i]);
				}
				if (stristr($aArgs[$i],"<xjxobj>") != false)
				{
					$aArgs[$i] = $this->_xmlToArray("xjxobj",$aArgs[$i]);	
				}
				else if (stristr($aArgs[$i],"<xjxquery>") != false)
				{
					$aArgs[$i] = $this->_xmlToArray("xjxquery",$aArgs[$i]);	
				}
			}

			if ($this->sPreFunction) {
				$mPreResponse = $this->_callFunction($this->sPreFunction, array($sFunctionNameForSpecial, $aArgs));
				if (is_array($mPreResponse) && $mPreResponse[0] === false) {
					$bEndRequest = true;
					$sPreResponse = $mPreResponse[1];
				}
				else {
					$sPreResponse = $mPreResponse;
				}
				if (is_a($sPreResponse, "ajaxResponse")) {
					$sPreResponse = $sPreResponse->getXML();
				}
				if ($bEndRequest) $sResponse = $sPreResponse;
			}
			
			if (!$bEndRequest) {
				if (!$this->_isFunctionCallable($sFunctionName)) {
					$objResponse = new ajaxResponse();
					$objResponse->addAlert("The Registered Function $sFunctionName Could Not Be Found.");
					$sResponse = $objResponse->getXML();
				}
				else {
					if ($bFunctionIsCatchAll) {
						$aArgs = array($sFunctionNameForSpecial, $aArgs);
					}
					$sResponse = $this->_callFunction($sFunctionName, $aArgs);
				}
				if (is_a($sResponse, "ajaxResponse")) {
					$sResponse = $sResponse->getXML();
				}
				if (!is_string($sResponse) || strpos($sResponse, "<xjx>") === FALSE) {
					$objResponse = new ajaxResponse();
					$objResponse->addAlert("No XML Response Was Returned By Function $sFunctionName.");
					$sResponse = $objResponse->getXML();
				}
				else if ($sPreResponse != "") {
					$sNewResponse = new ajaxResponse();
					$sNewResponse->loadXML($sPreResponse);
					$sNewResponse->loadXML($sResponse);
					$sResponse = $sNewResponse->getXML();
				}
			}
		}
		
		$sContentHeader = "Content-type: text/xml;";
		if ($this->sEncoding && strlen(trim($this->sEncoding)) > 0)
			$sContentHeader .= " charset=".$this->sEncoding;
		header($sContentHeader);
		if ($this->bErrorHandler && !empty( $GLOBALS['ajaxErrorHandlerText'] )) {
			$sErrorResponse = new ajaxResponse();
			$sErrorResponse->addAlert("** PHP Error Messages: **" . $GLOBALS['ajaxErrorHandlerText']);
			if ($this->sLogFile) {
				$fH = @fopen($this->sLogFile, "a");
				if (!$fH) {
					$sErrorResponse->addAlert("** Logging Error **\n\najax was unable to write to the error log file:\n" . $this->sLogFile);
				}
				else {
					fwrite($fH, "** ajax Error Log - " . strftime("%b %e %Y %I:%M:%S %p") . " **" . $GLOBALS['ajaxErrorHandlerText'] . "\n\n\n");
					fclose($fH);
				}
			}

			$sErrorResponse->loadXML($sResponse);
			$sResponse = $sErrorResponse->getXML();
			
		}
		if ($this->bCleanBuffer) while (@ob_end_clean());
		print $sResponse;
		if ($this->bErrorHandler) restore_error_handler();
		
		if ($this->bExitAllowed)
			exit();
	}
			
	function printJavascript($sJsURI="", $sJsFile=NULL, $sJsFullFilename=NULL)
	{
		print $this->getJavascript($sJsURI, $sJsFile, $sJsFullFilename);
	}

	// getJavascript() returns the ajax javascript code that should be added to
        // your HTML page between the <head> </head> tags.	
	function getJavascript($sJsURI="", $sJsFile=NULL, $sJsFullFilename=NULL)
	{	
		if ($sJsFile == NULL) $sJsFile = "ajax_uncompressed.js";
			
		if ($sJsURI != "" && substr($sJsURI, -1) != "/") $sJsURI .= "/";
		
		$html  = "\t<script type=\"text/javascript\">\n";
		$html .= "var ajaxRequestUri=\"".$this->sRequestURI."\";\n";
		$html .= "var ajaxDebug=".($this->bDebug?"true":"false").";\n";
		$html .= "var ajaxStatusMessages=".($this->bStatusMessages?"true":"false").";\n";
		$html .= "var ajaxWaitCursor=".($this->bWaitCursor?"true":"false").";\n";
		$html .= "var ajaxDefinedGet=".AJAX_GET.";\n";
		$html .= "var ajaxDefinedPost=".AJAX_POST.";\n";

		foreach($this->aFunctions as $sFunction => $bExists) {
			$html .= $this->_wrap($sFunction,$this->aFunctionRequestTypes[$sFunction]);
		}

		$html .= "</script>\n";
		
		// Create a compressed file if necessary
		if ($sJsFullFilename) {
			$realJsFile = $sJsFullFilename;
		}
		else {
			$realPath = realpath(dirname(__FILE__));
			$realJsFile = $realPath . "/". $sJsFile;
		}
/*		$srcFile = str_replace(".js", "_uncompressed.js", $realJsFile);
		if (!file_exists($srcFile)) {
			trigger_error("The ajax uncompressed Javascript file could not be found in the <b>" . dirname($realJsFile) . "</b> folder. Error ", E_USER_ERROR);	
		}
		
		if ($this->bDebug) {
			if (!@copy($srcFile, $realJsFile)) {
				trigger_error("The ajax uncompressed javascript file could not be copied to the <b>" . dirname($realJsFile) . "</b> folder. Error ", E_USER_ERROR);
			}
		}
		else if (!file_exists($realJsFile)) {
			require(dirname($realJsFile) . "/ajaxCompress.php");
			$javaScript = implode('', file($srcFile));
			$compressedScript = ajaxCompressJavascript($javaScript);
			$fH = @fopen($realJsFile, "w");
			if (!$fH) {
				trigger_error("The ajax compressed javascript file could not be written in the <b>" . dirname($realJsFile) . "</b> folder. Error ", E_USER_ERROR);
			}
			else {
				fwrite($fH, $compressedScript);
				fclose($fH);
			}
		}*/

		$html .= "\t<script type=\"text/javascript\" src=\"" . $sJsURI . $sJsFile . "\"></script>\n";		
		
		return $html;
	}

	// _detectURL() returns the current URL based upon the SERVER vars
        // used internally
	function _detectURI() {
		$aURL = array();

		// Try to get the request URL
		if (!empty($_SERVER['REQUEST_URI'])) {
			$aURL = parse_url($_SERVER['REQUEST_URI']);
		}

		// Fill in the empty values
		if (empty($aURL['scheme'])) {
			if (!empty($_SERVER['HTTP_SCHEME'])) {
				$aURL['scheme'] = $_SERVER['HTTP_SCHEME'];
			} else {
				$aURL['scheme'] = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ? 'https' : 'http';
			}
		}

		if (empty($aURL['host'])) {
			if (!empty($_SERVER['HTTP_HOST'])) {
				if (strpos($_SERVER['HTTP_HOST'], ':') > 0) {
					list($aURL['host'], $aURL['port']) = explode(':', $_SERVER['HTTP_HOST']);
				} else {
					$aURL['host'] = $_SERVER['HTTP_HOST'];
				}
			} else if (!empty($_SERVER['SERVER_NAME'])) {
				$aURL['host'] = $_SERVER['SERVER_NAME'];
			} else {
				print "ajax Error: ajax failed to automatically identify your Request URI.";
				print "Please set the Request URI explicitly when you instantiate the ajax object.";
				exit();
			}
		}

		if (empty($aURL['port']) && !empty($_SERVER['SERVER_PORT'])) {
			$aURL['port'] = $_SERVER['SERVER_PORT'];
		}

		if (empty($aURL['path'])) {
			if (!empty($_SERVER['PATH_INFO'])) {
				$sPath = parse_url($_SERVER['PATH_INFO']);
			} else {
				$sPath = parse_url($_SERVER['PHP_SELF']);
			}
			$aURL['path'] = $sPath['path'];
			unset($sPath);
		}

		if (!empty($aURL['query'])) {
			$aURL['query'] = '?'.$aURL['query'];
		}

		$sURL = $aURL['scheme'].'://';
		if (!empty($aURL['user'])) {
			$sURL.= $aURL['user'];
			if (!empty($aURL['pass'])) {
				$sURL.= ':'.$aURL['pass'];
			}
			$sURL.= '@';
		}

		// Add the host
		$sURL.= $aURL['host'];

		if (!empty($aURL['port']) && (($aURL['scheme'] == 'http' && $aURL['port'] != 80) || ($aURL['scheme'] == 'https' && $aURL['port'] != 443))) {
			$sURL.= ':'.$aURL['port'];
		}

		$sURL.= $aURL['path'].@$aURL['query'];

		unset($aURL);
		return $sURL;
	}
	
	function _isObjectCallback($sFunction)
	{
		if (array_key_exists($sFunction, $this->aObjects)) return true;
		return false;
	}
	
	function _isFunctionCallable($sFunction)
	{
		if ($this->_isObjectCallback($sFunction)) {
			if (is_object($this->aObjects[$sFunction][0])) {
				return method_exists($this->aObjects[$sFunction][0], $this->aObjects[$sFunction][1]);
			}
			else {
				return is_callable($this->aObjects[$sFunction]);
			}
		}
		else {
			return function_exists($sFunction);
		}	
	}

	// calls the function, class method, or object method with the supplied arguments	
	function _callFunction($sFunction, $aArgs)
	{
		if ($this->_isObjectCallback($sFunction)) {
			$mReturn = call_user_func_array($this->aObjects[$sFunction], $aArgs);
		}
		else {
			$mReturn = call_user_func_array($sFunction, $aArgs);
		}
		return $mReturn;
	}

	// generates the javascript wrapper for the specified PHP function	
	function _wrap($sFunction,$sRequestType=AJAX_POST)
	{
		$js = "function ".$this->sWrapperPrefix."$sFunction(){return ajax.call(\"$sFunction\", arguments, ".$sRequestType.");}\n";		
		return $js;
	}

	// _xmlToArray() takes a string containing ajax xjxobj xml or xjxquery xml
        // and builds an array representation of it to pass as an argument to
        // the php function being called. Returns an array.
	function _xmlToArray($rootTag, $sXml)
	{
		$aArray = array();
		$sXml = str_replace("<$rootTag>","<$rootTag>|~|",$sXml);
		$sXml = str_replace("</$rootTag>","</$rootTag>|~|",$sXml);
		$sXml = str_replace("<e>","<e>|~|",$sXml);
		$sXml = str_replace("</e>","</e>|~|",$sXml);
		$sXml = str_replace("<k>","<k>|~|",$sXml);
		$sXml = str_replace("</k>","|~|</k>|~|",$sXml);
		$sXml = str_replace("<v>","<v>|~|",$sXml);
		$sXml = str_replace("</v>","|~|</v>|~|",$sXml);
		$sXml = str_replace("<q>","<q>|~|",$sXml);
		$sXml = str_replace("</q>","|~|</q>|~|",$sXml);
		
		$this->aObjArray = explode("|~|",$sXml);
		
		$this->iPos = 0;
		$aArray = $this->_parseObjXml($rootTag);
		
		return $aArray;
	}
	
	// _parseObjXml() is a recursive function that generates an array from the
        // contents of $this->aObjArray. Returns an array.
	function _parseObjXml($rootTag)
	{
		$aArray = array();
		
		if ($rootTag == "xjxobj")
		{
			while(!stristr($this->aObjArray[$this->iPos],"</xjxobj>"))
			{
				$this->iPos++;
				if(stristr($this->aObjArray[$this->iPos],"<e>"))
				{
					$key = "";
					$value = null;
						
					$this->iPos++;
					while(!stristr($this->aObjArray[$this->iPos],"</e>"))
					{
						if(stristr($this->aObjArray[$this->iPos],"<k>"))
						{
							$this->iPos++;
							while(!stristr($this->aObjArray[$this->iPos],"</k>"))
							{
								$key .= $this->aObjArray[$this->iPos];
								$this->iPos++;
							}
						}
						if(stristr($this->aObjArray[$this->iPos],"<v>"))
						{
							$this->iPos++;
							while(!stristr($this->aObjArray[$this->iPos],"</v>"))
							{
								if(stristr($this->aObjArray[$this->iPos],"<xjxobj>"))
								{
									$value = $this->_parseObjXml("xjxobj");
									$this->iPos++;
								}
								else
								{
									$value .= $this->aObjArray[$this->iPos];
								}
								$this->iPos++;
							}
						}
						$this->iPos++;
					}
					
					$aArray[$key]=$value;
				}
			}
		}
		
		if ($rootTag == "xjxquery")
		{
			$sQuery = "";
			$this->iPos++;
			while(!stristr($this->aObjArray[$this->iPos],"</xjxquery>"))
			{
				if (stristr($this->aObjArray[$this->iPos],"<q>") || stristr($this->aObjArray[$this->iPos],"</q>"))
				{
					$this->iPos++;
					continue;
				}
				$sQuery	.= $this->aObjArray[$this->iPos];
				$this->iPos++;
			}
			
			parse_str($sQuery, $aArray);
			if (get_magic_quotes_gpc() == 1) {
				$newArray = array();
				foreach ($aArray as $sKey => $sValue) {
					if (is_string($sValue))
						$newArray[$sKey] = stripslashes($sValue);
					else
						$newArray[$sKey] = $sValue;
				}
				$aArray = $newArray;
			}
		}
		
		return $aArray;
	}
		
}// end class ajax 

function ajaxErrorHandler($errno, $errstr, $errfile, $errline)
{
	$errorReporting = error_reporting();
	if ($errorReporting == 0) return;
	
	if ($errno == E_NOTICE) {
		$errTypeStr = "NOTICE";
	}
	else if ($errno == E_WARNING) {
		$errTypeStr = "WARNING";
	}
	else if ($errno == E_USER_NOTICE) {
		$errTypeStr = "USER NOTICE";
	}
	else if ($errno == E_USER_WARNING) {
		$errTypeStr = "USER WARNING";
	}
	else if ($errno == E_USER_ERROR) {
		$errTypeStr = "USER FATAL ERROR";
	}
	else if ($errno == E_STRICT) {
		return;
	}
	else {
		$errTypeStr = "UNKNOWN: $errno";
	}
	$GLOBALS['ajaxErrorHandlerText'] .= "\n----\n[$errTypeStr] $errstr\nerror in line $errline of file $errfile";
}

?>
