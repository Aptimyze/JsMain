<?php
/**
 * CLASS FTOContactDetails
 * FTOContactDetails class Manage the contacts limit for FTO users
 * 
 * PHP versions 4 and 5	
 * @package   FTO
 * @author    Pankaj Khandelwal <pankaj.khandelwal@jeevansathi.com>
 * @copyright 2012 Pankaj Khandelwal
 * @version   SVN: 
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
 */
class FTOContactDetails{
	private  $inBoundLimit;
	private  $outBoundLimit;
	private  $totalAcceptanceLimit;
	private  $FTOFlag;
	private  $inBoundCount;
	private  $outBoundCount;
	private  $totalCount;
	
	public function __construct(Profile $loggedInProfile)
	{
		$flag = $loggedInProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
		if(empty($flag))
		{
			$this->inBoundLimit = ACCEPTANCE_LIMIT::FTO_ACCEPTANCE_INBOUND_LIMIT;
			$this->outBoundLimit = ACCEPTANCE_LIMIT::FTO_ACCEPTANCE_OUTBOUND_LIMIT;
			$this->totalAcceptanceLimit = ACCEPTANCE_LIMIT::FTO_ACCEPTANCE_TOTAL_LIMIT;
			if (ACCEPTANCE_LIMIT_CHECKS_FLAG::TOTAL_ON == true)
				$this->FTOFlag = 'T';
			elseif (ACCEPTANCE_LIMIT_CHECKS_FLAG::INBOUND_ON == true)
				$this->FTOFlag = 'I';
		}
		else
		{
			$this->inBoundLimit = $loggedInProfile->getPROFILE_STATE()->getFTOStates()->getInboundAcceptLimit();
			$this->outBoundLimit = $loggedInProfile->getPROFILE_STATE()->getFTOStates()->getOutboundAcceptLimit();
			$this->totalAcceptanceLimit = $loggedInProfile->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit();
			$this->FTOFlag = $loggedInProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
			$FTOContactViewedObj = new FTO_FTO_CONTACT_VIEWED;
			$count = $FTOContactViewedObj->getTotalCount($loggedInProfile);
			$this->inBoundCount = $count["INBOUND"];
			$this->outBoundCount = $count['OUTBOUND'];
			$this->totalCount = $count['TOTAL'];
		
		}
		
	}
	
	public function getInBoundLimit()
	{
		
		return $this->inBoundLimit;
	}
	public function getOutBoundLimit()
	{
		return $this->outBoundLimit;
	}
	public function getTotalAcceptanceLimit()
	{
		
		return $this->totalAcceptanceLimit;
	}
	public function getFTOFlag()
	{
		return $this->FTOFlag;
	}
	public function getInBoundCount()
	{
		return $this->inBoundCount;
	}
	public function getOutBoundCount()
	{
		return $this->outBoundCount;
	}
	public function getTotalCount()
	{
		return $this->totalCount;
	}
	
	private function setInboundLimit($inBoundLimit){$this->inBoundLimit = $inBoundLimit;}
	private function setOutBoundLimit($outBoundLimit){$this->outBoundLimit = $outBoundLimit;}
	private function setTotalAcceptanceLimit($totalAcceptanceLimit){$this->totalAcceptanceLimit = $totalAcceptanceLimit;}
	/**
* getFTOContactDetailsArr
* return the FTO contact limit array for fto user
* @access public
* reutn array with FTO flag inbound outbound and total limit
*/	
	public function getFTOContactDetailsArr()
	{
		$FTOContactDetailsArr["FLAG"] = $this->getFTOFlag();
		$FTOContactDetailsArr["INBOUND_LIMIT"] = $this->getInBoundLimit();
		$FTOContactDetailsArr["OUTBOUND_LIMIT"] = $this->getOutBoundLimit();
		$FTOContactDetailsArr["TOTAL_LIMIT"] = $this->getTotalAcceptanceLimit();
		
		return $FTOContactDetailsArr;
	}
	
	
	/**
* updateFTOContactViewedLog
* update the FTO contact Viewed log table for FTO users 
* @param ContactHandler $contactHandler 
* @access public
*/
	public function updateFTOContactViewedLog($sender,$receiver)
	{
		if($sender->getPROFILE_STATE()->getFTOStates()->getSubState() != FTOSubStateTypes::NEVER_EXPOSED)
		{
			$FTOProfile = $sender;
			$otherProfile = $receiver;
			//uncomment below if condition if FTO acceptance count won't be affected if other user is EVALUE
			//if ($otherProfile->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() != "EVALUE")
			//{	
				$FTOFlag = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
				$inBoundLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getInboundAcceptLimit(); 
				$outBoundLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getOutBoundAcceptLimit(); 
				$totalAcceptanceLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit(); 
				$acceptanceType = 'I';
				$FTOContactViewedObj = new FTO_FTO_CONTACT_VIEWED; 
				$count = $FTOContactViewedObj->getTotalCount($FTOProfile);
				if(!$FTOContactViewedObj->getContactViewed($FTOProfile,$otherProfile)) 
				{ 
					if($FTOFlag == 'I' && $acceptanceType == 'I')
					{
						if ($count['INBOUND'] < $inBoundLimit)
						{
							$update = true;
						}
					}
					else if ($FTOFlag == 'T')
					{
						if($count['TOTAL']<$totalAcceptanceLimit)
						{
							if($acceptanceType == 'I')
							{
								if ($count['INBOUND'] < $inBoundLimit)
								{
									$update = true;
								}
							}
						}
					}
				}
			//}
			if($update)
			{
				$FTOContactViewedObj->insertContactViewed($FTOProfile,$otherProfile,$acceptanceType);
			}	
		}
		
		if($receiver->getPROFILE_STATE()->getFTOStates()->getSubState() != FTOSubStateTypes::NEVER_EXPOSED)
		{
			$FTOProfile = $receiver;
			$otherProfile = $sender;
			//uncomment below if condition if FTO acceptance count won't be affected if other user is EVALUE
			//if ($otherProfile->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() != "EVALUE")
			//{
				$FTOFlag = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
				$inBoundLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getInboundAcceptLimit(); 
				$outBoundLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getOutBoundAcceptLimit(); 
				$totalAcceptanceLimit = $FTOProfile->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit(); 
				$acceptanceType1 = 'O';
				$FTOContactViewedObj = new FTO_FTO_CONTACT_VIEWED; 
				$count = $FTOContactViewedObj->getTotalCount($FTOProfile);
				if(!$FTOContactViewedObj->getContactViewed($FTOProfile,$otherProfile)) 
				{ 
					if ($FTOFlag == 'T')
					{
						
						if($count["TOTAL"]<$totalAcceptanceLimit)
						{
							if($acceptanceType1 == 'O')
							{
								if($count['OUTBOUND']< $outBoundLimit)
								{
									$update1 = true;
								}
							}
						}
					}
				}
			//}
			if($update1)
			{
				$FTOContactViewedObj->insertContactViewed($FTOProfile,$otherProfile,$acceptanceType1);
			}	
		}
	}
		

	
}
