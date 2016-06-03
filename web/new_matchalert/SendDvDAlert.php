<?php
class SendMatchAlert 
{
	private $profileId;
	private $DbArr, $localdb;
	public function __construct($whichProfileId, $DbArr, $localdb, $mysqlObj)
	{
		$this->profileId = $whichProfileId;
		$this->DbArr = $DbArr;
		$this->localdb = $localdb;
		$this->mysqlObj = $mysqlObj;
	}
	
	public function send($idForTarcking) 
	{
		$db = $this->localdb;
		$myDbArr = $this->DbArr;
		$mysqlObj = $this->mysqlObj;
		$receiverObj=new Receiver($this->profileId,$db,1);//get receiver profile
		if($receiverObj->getSameGenderError()!='Y')
		{
			global $idForTarcking;

			if($receiverObj->getHasTrend()!= true)
			{
                                $strategyDvD=new StrategyDvD($receiverObj,$db,$myDbArr,$mysqlObj);
                                $strategyDvD->doProcessing();
			}
			else
			{
				die("PlEASE CONTACTS LAVESH FOR THIS");
				//mail
			}
		}
		else
		{
			$sameGenderArr.=$profileid. " , ";
			//mail
		}
	}
	
}
?>
