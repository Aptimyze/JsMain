<?php
class SEOMiniRegistration
{
	private $MTONGUE;	
	
	public $yearArray;
	
	public $dayArray;
	
	function setMtongue($MTONGUE)
	{
		$this->MTONGUE = $MTONGUE;
	}
	function getMtongue() 
	{
		return $this->MTONGUE;
	}

	public function assign($type,$value)
	{
		if($type=='MTONGUE')
		{
			if (isset($value))
			$this->setMtongue($value);
		}
		$curDate=date('Y', JSstrToTime('-6570 days')); // Finding 18 years back year
		for($i=$curDate;$i>=1939;$i--)
        	$yearArray[]=$i;
		$this->yearArray=$yearArray;

		for($i=1;$i<=31;$i++)
        	$dayArray[]=$i;
		$this->dayArray=$dayArray;
		
   }
}
?>