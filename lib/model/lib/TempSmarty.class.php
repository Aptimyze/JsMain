<?php
class TempSmarty{

	function assign($key,$val)
	{
		$this->tempData[$key]=$val;
	}
        function fetch(){ }
	function display(){}
	function getTemplateVars()
	{
		return $this->tempData;
	}
}
?>
