<?php
/**
 * @brief This class list the possible kundli matches of a user based on DPP .
 * @author Reshu Rajput
 * @created 2016-08-08
 */
class KundliMatches extends PartnerProfile
{
    
       /**
        * Constructor function.
        * @constructor
        * @access public        
        * @param LoggedInProfile $loggedInProfileObj logged in profile object
        */
        public function __construct($loggedInProfileObj)
        {
					$this->loggedInProfileObj = $loggedInProfileObj;
					parent::__construct($loggedInProfileObj);
        }

			/*
	* This function will set the criteria for search.
	*/
				public function getSearchCriteria($sz_callType='')
				{
					
					$this->getDppCriteria();
					$channel =  SearchChannelFactory::getChannel();
          $this->stype =  $channel::getSearchTypeMembersLookingForMe();
					$this->setSEARCH_TYPE($this->stype);
					$this->setHOROSCOPE('Y'); // Horoscope should be present for all the profiles
					if($this->loggedInProfileObj->getBTIME()=="" || $this->loggedInProfileObj->getCITY_BIRTH()=="" || $this->loggedInProfileObj->getCOUNTRY_BIRTH()=="")
          {
						$this->setSHOW_RESULT_FOR_SELF('N');
					}
					else
							$this->setSHOW_RESULT_FOR_SELF('ISKUNDLIMATCHES');
					
				}
				
				
				public function getGunaMatches($SearchResponseObj)
				{
					if(is_array($SearchResponseObj->getsearchResultsPidArr()))
					{
						$gunaScoreObj = new gunaScore();
						$gunaData = $gunaScoreObj->getGunaScore($this->loggedInProfileObj->getPROFILEID(),$this->loggedInProfileObj->getCASTE(),implode(",",$SearchResponseObj->getsearchResultsPidArr()),$this->loggedInProfileObj->getGENDER(),'1');
						if(is_array($gunaData))
						{
							foreach($gunaData as $i=>$v)
							{
									foreach($v as $pid=>$guna)
									{
										if($guna>=18)
											$finalSearchPidsArr[]=$pid;
									}
							}
						
							$SearchResponseObj->setsearchResultsPidArr(array_values(array_intersect($SearchResponseObj->getsearchResultsPidArr(),$finalSearchPidsArr)));
						
							$searchResultsArr = $SearchResponseObj->getresultsArr();
							foreach($searchResultsArr as $i=>$value)
							{
								if(!in_array($value["id"],$finalSearchPidsArr))
									unset($searchResultsArr[$i]);
							}
							$SearchResponseObj->setresultsArr(array_values($searchResultsArr));
						}
						else
						{
							$SearchResponseObj->setsearchResultsPidArr(null);
							$SearchResponseObj->setresultsArr(array());
						}
					}
					//print_r($SearchResponseObj); die;
					return $SearchResponseObj;
				}


}
?>
