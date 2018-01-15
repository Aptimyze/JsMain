<?php
/*
 * Author: Lavesh Rawat
 * This cron is used to remove hidden and deleted profile from search engine like solr.
*/
class SearchIndexingTask extends sfBaseTask
{
	private $searchEngine = 'solr';
	private $outputFormat = 'array';

 	protected function configure()
  	{
		$this->addArguments(array(
			new sfCommandArgument('TYPE', sfCommandArgument::REQUIRED, 'My argument'),
        		new sfCommandArgument('pid', sfCommandArgument::OPTIONAL, 'My argument'),
                ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'SearchIndexing';
	    $this->briefDescription = 'generate MIS data for FTO';
	    $this->detailedDescription = <<<EOF
	This cron will remove hidden and deleted profile from search.This function will be called in background from non-symfony files.
        Also we will run these cron immediately after solr-indexer is run as because of delay in master slave deleted profile is still there is search tables and come again after indexer is run.
	Call it with:

	  [php symfony cron:SearchIndexing ALL/PROFILEID/EXPORT pid] 
	Pass the argument as ALL if all the deleted pid in a specified time need to be removed else if specified profile need to be removed pass PROFILEID and pid(int value).
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		if(!sfContext::hasInstance())
                	sfContext::createInstance($this->configuration);

		$type = $arguments["TYPE"];
		$pid  = $arguments["pid"];

		if($type=='EXPORT')
		{
			/* solr full indexing */	
			$url = JsConstants::$solrServerUrl."/dataimport?command=full-import";
			CommonUtility::sendCurlGetRequest($url);
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl1){
                                $url = JsConstants::$solrServerUrl1."/dataimport?command=full-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl2){
                                $url = JsConstants::$solrServerUrl2."/dataimport?command=full-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl3){
                                $url = JsConstants::$solrServerUrl3."/dataimport?command=full-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
		}
                else if($type=='DELTA')
		{
                        /* solr delta indexing */	
			$url = JsConstants::$solrServerUrl."/dataimport?command=delta-import";
			CommonUtility::sendCurlGetRequest($url);
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl1){
                                $url = JsConstants::$solrServerUrl1."/dataimport?command=delta-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl2){
                                $url = JsConstants::$solrServerUrl2."/dataimport?command=delta-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
                        if(JsConstants::$solrServerUrl!=JsConstants::$solrServerUrl3){
                                $url = JsConstants::$solrServerUrl3."/dataimport?command=delta-import";
                                CommonUtility::sendCurlGetRequest($url);   
                        }
                            
			$deletedHiddenProfilesObj = new newjs_HIDDEN_DELETED_PROFILES('newjs_masterDDL');
                        $profilesArr = $deletedHiddenProfilesObj->getProfiles();
                        if($profilesArr)
			{
				$strProfilesArr = implode(" ",$profilesArr);
                                $SearchServiceObj = new SearchService($this->searchEngine,$this->outputFormat);
                                $SearchServiceObj->deleteIdsFromSearch($strProfilesArr);
                                $deletedHiddenProfilesObj->truncateTable(date('Y-m-d h:i:s',strtotime('-4 Hours')));
                        }
		}
		elseif($type == 'PROFILEID')
		{	
			if($pid)
			{
				$pid = $this->replaceAllSpaces($pid);
                                //in case of real time indexing delete them
                                if(JsConstants::$realTimeIndex == 1){
                                    $SearchServiceObj = new SearchService($this->searchEngine,$this->outputFormat);
                                    $SearchServiceObj->deleteIdsFromSearch($pid);
                                }
                                $deletedHiddenProfilesObj = new newjs_HIDDEN_DELETED_PROFILES();
                                $deletedHiddenProfilesObj->insertProfile($pid);

				//delete entries from NEWJS_SEARCH_MALE
				$searchMaleObj = new NEWJS_SEARCH_MALE();
				$searchMaleObj->deleteRecord($pid);
				
				//delete entries from NEWJS_SEARCH_FEMALE
				$searchMaleObj = new NEWJS_SEARCH_FEMALE();
				$searchMaleObj->deleteRecord($pid);
				
			}			
			else
				die("Invalid 2nd arguments value");
			
		}
		else
			die("Invalid 1st arguments value");
  	}

	/*
	* replace multiple spaces if present.
	*/
	public function replaceAllSpaces($pid)
	{
		return str_replace(' ','',$pid);
	}
}
