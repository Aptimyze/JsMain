<?php
/*
This class performs the search in case of bandhan.com and doctors republic
*/
class BandhanSearch
{
	private $bandhan;
	private $doctorsrepublic;

	public function __construct($source)
	{
		if($source == 1)
			$this->doctorsrepublic=1;
		elseif($source == 2)
			$this->bandhan=1;
	}

	/*
	This function is used to perform search
	$param - logic, 1 = last day; 2 = only urls of 15 days and 3 = full data of 15 days and 4 = full data of 1 day , searchFrmTables (optional) if search needs to be performed through search tables
	*/
	public function perfromSearch($logic,$searchFrmTables='')
	{
		$genderArr = array("M","F");

                header("Content-Type: application/rss+xml");
                $str="";
                $str.= "<?xml version='1.0' standalone='yes'?>\n<rss version='2.0'>\n<channel>\n";
                $str.=  "<title>jeevansathi.com</title>\n";
                $str.= "<link>".sfConfig::get('app_site_url')."</link>\n";
                $str.= "<description></description>\n<date>".date("Y-m-d")."</date>\n";
                echo $str;

		if($logic==1)		//LAST DAY
                {
                        $file=fopen(sfConfig::get("sf_web_dir")."/profile/search-terms-top-200.txt","r") or die("Unable to open file!");
                        while(!feof($file))
                        {
                                $keywd = fgets($file);
                                foreach($genderArr as $gender)
                                {
                                        $SearchParametersObj = new SearchBasedOnParameters;
                                        $loggedInProfileObj = Profile::getInstance('newjs_master');
                                        $paramArr["GENDER"] = $gender;
                                        $paramArr["KEYWORD"] = $keywd;
                                        $paramArr["KEYWORD_TYPE"] = "AND";
                                        if($this->doctorsrepublic)
                                                $paramArr["EDU_LEVEL_NEW"] = array("17","19","21");
                                        $SearchParametersObj->getSearchCriteria($paramArr);
                                        $SearchParametersObj->setNoOfResults(SearchConfig::$summary_profile_count);
                                        $SearchServiceObj = new SearchService('solr','array',1);
                                        $SearchParametersObj->setSORT_LOGIC(SearchSortTypesEnums::popularSortFlag);
                                        $responseObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults");
                                        $bdObj = new BandhanDisplay($responseObj);
                                        $bdObj->generateDisplay($logic);
                                unset($SearchParametersObj);
                                unset($paramArr);
                                unset($SearchServiceObj);
                                unset($responseObj);
                                unset($bdObj);
                                }
                        }
                        fclose($file);
                }
		elseif($logic==2 || $logic==3 || $logic==4)		//Only Profile Urls or All Data of 15 days or all data of 1 day
                {
			if($searchFrmTables)		//Search performed from search tables
			{
				$limit = "";
				foreach($genderArr as $gender)
				{
					if($gender == "M")
						$tableObj = new NEWJS_SEARCH_MALE("newjs_slave");
					elseif($gender == "F")
						$tableObj = new NEWJS_SEARCH_FEMALE("newjs_slave");
	
					if($this->doctorsrepublic)	
						$dataArr = $tableObj->getDataForOtherApis("DOCTOR",$limit);
					else
						$dataArr = $tableObj->getDataForOtherApis("",$limit);
					unset($tableObj);

					if($dataArr && is_array($dataArr))
					{
						$bdObj = new BandhanDisplay("",$dataArr);
						unset($dataArr);
                                        	$bdObj->generateDisplay($logic);
                                        	unset($bdObj);
					}
				}
			}
			else		//Search performed through solr
			{
				if($logic==4)
					$b15 = time() - (2 * 24 * 60 * 60);
				else
					$b15 = time() - (15 * 24 * 60 * 60);
				foreach($genderArr as $gender)
				{
					$SearchParametersObj = new SearchBasedOnParameters;
					$paramArr["GENDER"] = $gender;
					$paramArr["LENTRY_DT"] = date('Y-m-d', $b15)."T00:00:00Z";
					$paramArr["HENTRY_DT"] = date("Y-m-d")."T00:00:00Z";
					if($this->doctorsrepublic)
						$paramArr["EDU_LEVEL_NEW"] = array("17","19","21");
					$SearchParametersObj->getSearchCriteria($paramArr);
					$SearchParametersObj->setNoOfResults(SearchConfig::$summary_profile_count_all);
					$SearchServiceObj = new SearchService('solr','array',1);
					$SearchParametersObj->setSORT_LOGIC(SearchSortTypesEnums::popularSortFlag);
					$responseObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults");

					$bdObj = new BandhanDisplay($responseObj);
					$bdObj->generateDisplay($logic);
					unset($bdObj);
					if($responseObj->getTotalResults()>SearchConfig::$summary_profile_count_all);
					{
						$x = $responseObj->getTotalResults()-SearchConfig::$summary_profile_count_all;
						$currentPage = 2;
						unset($responseObj);
						while($x>0)
						{
							$responseObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults",'',$currentPage);
							$x = $x - SearchConfig::$summary_profile_count_all;
							$currentPage++;
							$bdObj = new BandhanDisplay($responseObj);
							$bdObj->generateDisplay($logic);
							unset($bdObj);
							unset($responseObj);
						}
					}
					unset($SearchParametersObj);
					unset($paramArr);
					unset($SearchServiceObj);
				}
			}
                }
		$str="";
                $str.= "</channel>\n</rss>\n";
                echo $str;
	}
}
?>
