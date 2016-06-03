<?php
/**
 * MobSaveSearchAction
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class MobSaveSearchAction extends sfActions
{
	public function executeMobSaveSearch($request)
	{		
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$this->savedSearches = null;
		$this->maxSaveSearches=0;
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
		{
			$this->loggedIn = true;
			$profileMemcacheObj = new ProfileMemcacheService($loggedInProfileObj);
			$saveSearchCount=$profileMemcacheObj->get("SAVED_SEARCH");
			if($saveSearchCount && $saveSearchCount>0)
			{
			  ob_start();
			  $request->setParameter('useSfViewNone','1');
			  $request->setParameter('perform','listing');
                	  sfContext::getInstance()->getController()->getPresentationFor('search','saveSearchCallV1');
                	  $savedSearchesResponse = json_decode(ob_get_contents()); //we can also get output from above command.
		      	  ob_end_clean();
			  if($savedSearchesResponse->saveDetails && $savedSearchesResponse->saveDetails->details)
				$this->savedSearches = $savedSearchesResponse->saveDetails->details;
			  if(sizeOf($this->savedSearches)>=SearchConfig::$maxSaveSearchesAllowed)
				$this->maxSaveSearches=1;
			}

		}
		$this->setTemplate("mobile/MobSaveSearch");
	}
}
