<?php
/*
* This class is used for caching solr request by saving url in a cache or a table.
*/
Class SearchResultscache
{
	public function __construct()
	{
		$this->useMemcacheOrTable = SearchConfig::$solrSearchCache;
	}

	/* 
	* add url into cache
	*/
	public function add($searchId,$url,$profileIdArr)
	{
		if($profileIdArr && is_array($profileIdArr))
			$profileids = implode(",",$profileIdArr);
		if($this->useMemcacheOrTable == 'table')
		{
			$newjs_SOLR_SEARCH_CACHE = new newjs_SOLR_SEARCH_CACHE;
			$newjs_SOLR_SEARCH_CACHE->add($searchId,$url);
		}
		else
		{
			$key = $searchId."_SEARCH_URL";
			JsMemcache::getInstance()->set($key,$url,3600);
		}
		$page = 1;
		$this->addProfiles($searchId,$profileIdArr,$page);
	}

	/*
	* fetch url from cache
	*/
	public function get($searchId,$searchEngine)
	{
		if($this->useMemcacheOrTable == 'table')
		{
			if($searchEngine=='solr')
			{
				$newjs_SOLR_SEARCH_CACHE = new newjs_SOLR_SEARCH_CACHE;
				return $newjs_SOLR_SEARCH_CACHE->get($searchId);
			}
		}
		else
		{
			if($searchEngine=='solr')
                        {
				$key = $searchId."_SEARCH_URL";
				$arr["URL"] = JsMemcache::getInstance()->get($key);
				if(!$arr["URL"])
				{
					$SearchUtility =  new SearchUtility;
					$responseObj = $SearchUtility->restoreSearchResultsCacheBySearchId($searchId);
					if($responseObj!='I')
					{
						$arr["URL"] = $responseObj->getUrlToSave();
						JsMemcache::getInstance()->set($key,$arr["URL"],3600);
					}
				}
				if(!$arr["URL"])
					return null;
				return $arr;
			}
		}
	}

	/*
	This function saves SearchConfig::$profilesPerPage profileids in memcache depending on the page they are coming
	@param searchId,array of profileids, page no
	*/
	public function addProfiles($searchId,$profileIdArr,$page)
	{
		if($profileIdArr && is_array($profileIdArr))
                        $profileids = implode(",",$profileIdArr);
		$key = $searchId."_SEARCH_PAGE_".$page."_IDS";
		JsMemcache::getInstance()->set($key,$profileids,3600);
	}

	/*
	This function returns profileids in memcache depending on the page they are coming
	@param searchId, page no
	*/
	public function getProfiles($searchId,$page)
	{
		$key = $searchId."_SEARCH_PAGE_".$page."_IDS";
		return JsMemcache::getInstance()->get($key);
	}

	/*
	This function returns the profileid depending on the offset passed from view profile page
	@param searchId,offset
	*/
	public function getProfile($searchId,$offset,$fp="")
	{
		if($fp)
		{
			if(SearchConfig::$featureProfileCache)
                        {
                                $key = $searchId."_FEATUREPROFILE";
                                $profileids = JsMemcache::getInstance()->get($key);
                        }
                        else
                        {
                                $fpcObj = new NEWJS_FEATURED_PROFILE_CACHE;
                                $profileids = $fpcObj->fetch($searchId);
                                unset($fpcObj);
                        }

			if($profileids)
                        {
                                $temp = explode(",",$profileids);
                              	$output = $temp[$offset-1];
                                unset($temp);
                        }
		}
		else
		{
			$profilesPerPage = SearchCommonFunctions::getProfilesPerPageOnSearch();
			if(is_array($offset))
			{
				$profileId=  "----->>>".print_r($offset,true);
				$profileId.=  "----->>>".print_r($_POST,true);
				$profileId.=  "----->>>".print_r($_GET,true);
				$http_msg="::::---->>>".print_r($_SERVER,true);
				mail("reshu.rajput@gmail.com","lr2","$profileId: $http_msg");
				
			}
			$rem = $offset%$profilesPerPage;
			if($rem==0)
				$page = $offset/$profilesPerPage; 
			else
				$page = floor($offset/$profilesPerPage)+1;
			$profileids = $this->getProfiles($searchId,$page);
			if(!$profileids)
			{
				$params = $this->get($searchId,'solr');
				if(is_null($params))
					return $params;
				$tempPage = ($page-1)*$profilesPerPage;
				$params = $params["URL"]."&start=".$tempPage."&rows=".$profilesPerPage;
				unset($tempPage);
				$profileObj = LoggedInProfile::getInstance('newjs_master');
				if($profileObj->getPROFILEID())
				{
						//if($profileObj->getPROFILEID()%7>2)
						if($profileObj->getPROFILEID()%4==0 || $profileObj->getPROFILEID()%4==1)
										$solrServerUrl = JsConstants::$solrServerProxyUrl1."/select";
						else
										$solrServerUrl = JsConstants::$solrServerProxyUrl."/select";
				}else{
                                        $solrServerUrl = JsConstants::$solrServerLoggedOut."/select";
                                }
				$res = CommonUtility::sendCurlPostRequest($solrServerUrl,$params);
				$res = unserialize($res);
				if($res['response']['docs'])
					foreach($res['response']['docs'] as $v)
						$pidArr[]=$v['id'];
				if($pidArr && is_array($pidArr))
				{
					$this->addProfiles($searchId,$pidArr,$page);
					$profileids = implode(",",$pidArr);
				}
			}

			if($profileids)
			{
				$temp = explode(",",$profileids);
				if($rem==0)
					$output = $temp[$profilesPerPage-1];
				else
					$output = $temp[$rem-1];
				unset($temp);
			}
		}
		return $output;
	}
}
?>
