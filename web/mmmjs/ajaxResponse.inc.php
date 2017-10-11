<?php
class ajaxResponse
{
	var $xml;
	var $sEncoding;

	function ajaxResponse($sEncoding=AJAX_DEFAULT_CHAR_ENCODING)
	{
		$this->setCharEncoding($sEncoding);
	}
	
	function setCharEncoding($sEncoding)
	{
		$this->sEncoding = $sEncoding;
	}
	
	function addAssign($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"as","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	function addAppend($sTarget,$sAttribute,$sData)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"ap","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	function addPrepend($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"pp","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	function addReplace($sTarget,$sAttribute,$sSearch,$sData)
	{
		$sDta = "<s><![CDATA[$sSearch]]></s><r><![CDATA[$sData]]></r>";
		$this->xml .= $this->_cmdXML(array("n"=>"rp","t"=>$sTarget,"p"=>$sAttribute),$sDta);
	}
	
	function addClear($sTarget,$sAttribute)
	{
		$this->addAssign($sTarget,$sAttribute,'');
	}
	
	function addAlert($sMsg)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"al"),$sMsg);
	}
	
	function addRedirect($sURL)
	{
		$this->addScript('window.location = "'.rawurlencode($sURL).'";');
	}

	function addScript($sJS)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"js"),$sJS);
	}
	
	function addRemove($sTarget)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"rm","t"=>$sTarget),'');
	}
	
	function addCreate($sParent, $sTag, $sId, $sType="")
	{
		if ($sType)
		{
			trigger_error("The \$sType parameter of addCreate has been deprecated.  Use the addCreateInput() method instead.", E_USER_WARNING);
			return;
		}
		$this->xml .= $this->_cmdXML(array("n"=>"ce","t"=>$sParent,"p"=>$sId),$sTag);
	}
	
	function addInsert($sBefore, $sTag, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ie","t"=>$sBefore,"p"=>$sId),$sTag);
	}
	
	function addCreateInput($sParent, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ci","t"=>$sParent,"p"=>$sId,"c"=>$sType),$sName);
	}
	
	function addInsertInput($sBefore, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ii","t"=>$sBefore,"p"=>$sId,"c"=>$sType),$sName);
	}
	
	function addEvent($sTarget,$sEvent,$sScript)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ev","t"=>$sTarget,"p"=>$sEvent),$sScript);
	}
	
	function addHandler($sTarget,$sEvent,$sHandler)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"ah","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}
	
	function addRemoveHandler($sTarget,$sEvent,$sHandler)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"rh","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}
	
	function addIncludeScript($sFileName)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"in"),$sFileName);
	}
	
	function getXML()
	{
		$sXML = "<?xml version=\"1.0\"";
		if ($this->sEncoding && strlen(trim($this->sEncoding)) > 0)
			$sXML .= " encoding=\"".$this->sEncoding."\"";
		$sXML .= " ?"."><xjx>" . $this->xml . "</xjx>";
		
		return $sXML;
	}
	
	function loadXML($sXML)
	{
		$sNewXML = "";
		$iStartPos = strpos($sXML, "<xjx>") + 5;
		$sNewXML = substr($sXML, $iStartPos);
		$iEndPos = strpos($sNewXML, "</xjx>");
		$sNewXML = substr($sNewXML, 0, $iEndPos);
		$this->xml .= $sNewXML;
	}

	function _cmdXML($aAttributes, $sData)
	{
		$xml = "<cmd";
		foreach($aAttributes as $sAttribute => $sValue)
			$xml .= " $sAttribute=\"$sValue\"";
		if ($sData && !stristr($sData,'<![CDATA['))
			$xml .= "><![CDATA[$sData]]></cmd>";
		else if ($sData)
			$xml .= ">$sData</cmd>";
		else
			$xml .= "></cmd>";
		
		return $xml;
	}
	
}// end class ajaxResponse
?>
