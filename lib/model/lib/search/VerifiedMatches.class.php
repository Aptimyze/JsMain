<?php
/**
* This class handles searches/saves related to profiles which are Fso Verified
* @author : Ankit Shukla
* @package Search
* @subpackage SearchTypes
* @since 2016-02-17
*/
class verifiedMatches extends PartnerProfile
{
        /*
         * @param - loggedInprofile Object
         */
	public function __construct($loggedInProfileObj)
	{
		parent::__construct($loggedInProfileObj);
	}
        /*
	* This function will set the criteria for search that is for matches which are fso verified
	*/
	public function getSearchCriteria($searchId='')
	{
		$this->getDppCriteria();
                $this->setFSO_VERIFIED('F,');
                if($searchId)
                {
                        $paramArr['ID'] = $searchId;
                        $SEARCHQUERYobj = new SEARCHQUERY;
                        $arr = $SEARCHQUERYobj->get($paramArr,SearchConfig::$possibleSearchParamters);

                        if(is_array($arr[0]))
                        {
                                foreach($arr[0] as $field=>$value)
                                {
                                        if(strstr(SearchConfig::$possibleSearchParamters,$field))
                                                eval ('$this->set'.$field.'($value);');
                                }
                                unset($arr);
                        }
                }
                $channel =  SearchChannelFactory::getChannel();
		$this->stype =  $channel::getSearchTypeVerifiedMatches();
		
                $this->setSEARCH_TYPE($this->stype);
	}
}