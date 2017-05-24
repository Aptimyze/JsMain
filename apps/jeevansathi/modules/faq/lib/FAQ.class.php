<?php
class FAQ
{
	private $m_objQAData;
	
	private $m_iTracePath;
	
	//View Paramters , Which are used in template(i.e. tpl)
	private $m_szTracePath; // 
	
	private $m_arrLink;
	
	private $m_iTrace;
	private $m_arrFAQLabel;
	private $m_iCurrent;
	
	public function getTracePath()
	{
		return $this->m_szTracePath;
	}
	
	public function getTrace()
	{
		return $this->m_iTrace;
	}
	
	public function getFAQLabel()
	{
		return $this->m_arrFAQLabel;
	}
	
	public function getCurrentSelection()
	{
		return $this->m_iCurrent;
	}
	
	public function getLinkArray()
	{
		return $this->m_arrLink;
	}
	
	public function __construct()
	{
		$this->m_ojbQAData = new FEEDBACK_QADATA();
		$this->m_iTracePath = 1;
	}
	
	public function ProcessData(sfWebRequest $request=null)
	{
		$arrFAQ = array();
		
		if($request && $request->getParameter("tracepath") !=null )
		{	
			$this->m_iTracePath = $request->getParameter("tracepath");
		}
		
		$this->m_ojbQAData->fetch_FAQ($arrFAQ,$this->m_iTracePath);
		$i =0;
		
		foreach($arrFAQ as $row)
		{
			if(($row['ID']==0)||($row['ID']==15)||($row['ID']==19)||($row['ID']==18)||($row['ID']==60))
				$arr[$i]["chk"]=1;
			else
				$arr[$i]["chk"]=0;
			$arr[$i]["id"]=$row['ID'];
			$arr[$i]["name"]=$row['QUESTION'];
			$arr[$i]["answer"]=$row['ANSWER'];
			$i++;
		}
		
		$this->m_szTracePath = "0." . $this->m_iTracePath ;
		$this->m_arrLink = $arr;
		
		// Now get FAQ Label Which will be shown in Left pan
		$arrFAQLabel =array();
		
		$this->m_ojbQAData->fetchFAQLabel($arrFAQLabel);
		
		$i=0;
		foreach($arrFAQLabel as $row)
		{
			$arrstart[$i]["id"]=$row['ID'];
			$arrstart[$i]["name"]=$row['QUESTION'];
			if($this->m_iTracePath==$arrstart[$i]["id"])
				$current=$arrstart[$i]["id"];
			elseif(!$this->m_iTracePath)
					$current=1;
	
			$i++;
		}
		
		$this->m_iTrace = 0;
		$this->m_arrFAQLabel = $arrstart;
		$this->m_iCurrent = $current;
	}
	
}

?>
