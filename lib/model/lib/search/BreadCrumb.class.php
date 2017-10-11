<?php
/**
This class is used to generate the contents to be displayed on "See full criteria" on search and also to show the text of "You have searched for" on search
**/
class BreadCrumb
{
	private $outputArr=array();
	private $searchParametersLabelsTextLength = 65;
	private $searchParametersLabelsTextLengthMobile = 200;

	public function __construct()
	{
	}

	/**
	This function generates the labels of the parameters searched which are to be displayed on full criteria pop up
	@params 1) SearchParametersObj 2) engine
	@return array with index as the cluster name and value as the cluster values
	**/
	public function getSearchParametersLabels($SearchParamtersObj,$engine)
	{
		if(is_array($SearchParamtersObj))
		{
			$arr = $SearchParamtersObj;
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			unset($SearchParamtersObj);
			$SearchParamtersObj = new UserSavedSearches($loggedInProfileObj);
			foreach($arr as $field=>$value)
				if(strstr($SearchParamtersObj->possibleSearchParamters,$field))
					eval ('$SearchParamtersObj->set'.$field.'($value);');
			$SearchParamtersObj->setSEARCH_TYPE(SearchTypesEnums::SaveSearch);
			$SearchParamtersObj->setNEWSEARCH_CLUSTERING('');
			unset($loggedInProfileObj);

		}
		$search_clusters = FieldMap::getFieldLabel("search_clusters",1,1);
		$solr_clusters = FieldMap::getFieldLabel($engine."_clusters",1,1);
		$fieldMapArr = SearchConfig::fieldMapArrayLabelMapping();

		foreach($search_clusters as $k=>$v)
		{
			if($solr_clusters[$k] == "AGE")
			{
				if($SearchParamtersObj->{"getL".$solr_clusters[$k]}() && $SearchParamtersObj->{"getH".$solr_clusters[$k]}())
				{
					if($SearchParamtersObj->{"getL".$solr_clusters[$k]}() == $SearchParamtersObj->{"getH".$solr_clusters[$k]}())
						$outputArr[$v] = $SearchParamtersObj->{"getL".$solr_clusters[$k]}();
					else
						$outputArr[$v] = $SearchParamtersObj->{"getL".$solr_clusters[$k]}()." to ".$SearchParamtersObj->{"getH".$solr_clusters[$k]}();
				}
			}
			elseif($solr_clusters[$k] == "HEIGHT")
			{
				if($SearchParamtersObj->{"getL".$solr_clusters[$k]}() && $SearchParamtersObj->{"getH".$solr_clusters[$k]}())
				{
					if($SearchParamtersObj->{"getL".$solr_clusters[$k]}() == $SearchParamtersObj->{"getH".$solr_clusters[$k]}())
						$outputArr[$v] = str_replace("&quot;","\"",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getL".$solr_clusters[$k]}()));
					else
						$outputArr[$v] = str_replace("&quot;","\"",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getL".$solr_clusters[$k]}()))." to ".str_replace("&quot;","\"",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getH".$solr_clusters[$k]}()));
				}
			}
			elseif($solr_clusters[$k] == "INCOME")
			{
				if(($SearchParamtersObj->{"getL".$solr_clusters[$k]}() || $SearchParamtersObj->{"getL".$solr_clusters[$k]}() == '0') && ($SearchParamtersObj->{"getH".$solr_clusters[$k]}() || $SearchParamtersObj->{"getH".$solr_clusters[$k]}() == '0'))
				{
					if($SearchParamtersObj->{"getL".$solr_clusters[$k]}() == '0' && $SearchParamtersObj->{"getH".$solr_clusters[$k]}() == '0')
					{
						$temp[] = "No Income";
					}
					else
					{
						if(FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getL".$solr_clusters[$k]}()))
							$temp[] = FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getL".$solr_clusters[$k]}())." to ".FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getH".$solr_clusters[$k]}());
						else
							$temp[] = "Rs.0  to ".FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"getH".$solr_clusters[$k]}());
					}
				}
						
				if(($SearchParamtersObj->{"getL".$solr_clusters[$k]."_DOL"}() || $SearchParamtersObj->{"getL".$solr_clusters[$k]."_DOL"}() == '0') && ($SearchParamtersObj->{"getH".$solr_clusters[$k]."_DOL"}() || $SearchParamtersObj->{"getH".$solr_clusters[$k]."_DOL"}() == '0'))
				{
					if($SearchParamtersObj->{"getL".$solr_clusters[$k]."_DOL"}() == '0' && $SearchParamtersObj->{"getH".$solr_clusters[$k]."_DOL"}() == '0')
					{
						$temp[] = "No Income";
					}
					else
					{
						if(FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]."_DOL"],$SearchParamtersObj->{"getL".$solr_clusters[$k]."_DOL"}()))
							$temp[] = FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]."_DOL"],$SearchParamtersObj->{"getL".$solr_clusters[$k]."_DOL"}())." to ".FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]."_DOL"],$SearchParamtersObj->{"getH".$solr_clusters[$k]."_DOL"}());
						else
							$temp[] = "$0 to ".FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]."_DOL"],$SearchParamtersObj->{"getH".$solr_clusters[$k]."_DOL"}());
					}
				}

				if($temp)
				{
					foreach($temp as $a=>$b)
					{
						if(strstr($b,"and"))
							$temp[$a] = str_replace("to ","",$b);
					}

					if($temp[0]==$temp[1])
					{
						if($temp[0]=="No Income")
							$outputArr[$v] = "No Income";
						else
							$outputArr[$v] = implode(", ",$temp);
					}
					else
						$outputArr[$v] = implode(", ",$temp);
					unset($temp);
				}
			}
			elseif($solr_clusters[$k] == "EDUCATION_GROUPING")
			{
				if($SearchParamtersObj->getEDU_LEVEL_NEW())
				{
					if(strstr($SearchParamtersObj->getEDU_LEVEL_NEW(),","))
					{
						$temp = explode(",",$SearchParamtersObj->getEDU_LEVEL_NEW());
						foreach($temp as $kk=>$vv)
                                                {
                                                        $temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel("education",$vv));
                                                        if(!$temp[$kk])
                                                                unset($temp[$kk]);
                                                }
                                                $outputArr[$v] = implode(", ",$temp);
                                                unset($temp);
					}
					else
					{
						$outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel("education",$SearchParamtersObj->getEDU_LEVEL_NEW()));
					}
				}
			}
			elseif($solr_clusters[$k] == "OCCUPATION_GROUPING")
			{
				if($SearchParamtersObj->getOCCUPATION())
                                {
                                        if(strstr($SearchParamtersObj->getOCCUPATION(),","))
                                        {
                                                $temp = explode(",",$SearchParamtersObj->getOCCUPATION());
                                                foreach($temp as $kk=>$vv)
                                                {
                                                        $temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel("occupation",$vv));
                                                        if(!$temp[$kk])
                                                                unset($temp[$kk]);
                                                }
                                                $outputArr[$v] = implode(", ",$temp);
                                                unset($temp);
                                        }
					else
					{       
						$outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel("occupation",$SearchParamtersObj->getOCCUPATION()));
					}
                                }
			}
			elseif($solr_clusters[$k] == "LAST_ACTIVITY")
			{
				if($SearchParamtersObj->getONLINE()==SearchConfig::$onlineSearchFlag)
				{
					$activityOutput[] = "Online";
				}
				
				if($SearchParamtersObj->{"get".$solr_clusters[$k]}())
                                {
                                        if(strstr($SearchParamtersObj->{"get".$solr_clusters[$k]}(),","))
                                        {
                                                $temp = explode(",",$SearchParamtersObj->{"get".$solr_clusters[$k]}());
                                                foreach($temp as $kk=>$vv)
                                                {
                                                        $temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$vv));
                                                        if(!$temp[$kk])
                                                                unset($temp[$kk]);
                                                }
                                                $activityOutput[] = implode(", ",$temp);
                                                unset($temp);
                                        }
                                        else
                                        {
                                                $activityOutput[] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->getLAST_ACTIVITY()));
                                        }
                                }

				if($SearchParamtersObj->getONLINE()==SearchConfig::$onlineSearchFlag || $SearchParamtersObj->{"get".$solr_clusters[$k]}())
				{
					$outputArr[$v] = implode(", ",$activityOutput);
					unset($activityOutput);
				}
			}
			elseif($solr_clusters[$k] == "CITY_INDIA")
			{
				if($SearchParamtersObj->{"get".$solr_clusters[$k]}())
				{
					if(strstr($SearchParamtersObj->{"get".$solr_clusters[$k]}(),","))
                                        {
                                                $temp = explode(",",$SearchParamtersObj->{"get".$solr_clusters[$k]}());
                                                foreach($temp as $kk=>$vv)
                                                {
                                                        $temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$vv));
                                                        if(!$temp[$kk])
                                                                unset($temp[$kk]);
                                                }
                                                $outputArr[$v] = implode(", ",$temp);
                                                unset($temp);
                                        }
					elseif($SearchParamtersObj->{"get".$solr_clusters[$k]}()==SearchConfig::$allLabel)
                                        {
                                                $outputArr[$v] = SearchConfig::$allLabel;
                                        }       
                                        elseif(in_array($SearchParamtersObj->{"get".$solr_clusters[$k]}(),SearchConfig::$dont_all_labels))
                                        {
                                                $outputArr[$v] = SearchConfig::$doesntMatterLabel;
                                        }
					else
					{
						$outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"get".$solr_clusters[$k]}()));

					}
				}
				elseif($SearchParamtersObj->getCITY_RES())
				{
					if(strstr($SearchParamtersObj->getCITY_RES(),","))
                                        {
                                                $temp = explode(",",$SearchParamtersObj->getCITY_RES());
                                                foreach($temp as $kk=>$vv)
                                                {
                                                        $temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$vv));
                                                        if(!$temp[$kk])
                                                                unset($temp[$kk]);
                                                }
                                                $outputArr[$v] = implode(", ",$temp);
                                                unset($temp);
                                        }
					elseif($SearchParamtersObj->{"get".$solr_clusters[$k]}()==SearchConfig::$allLabel)
                                        {
                                                $outputArr[$v] = SearchConfig::$allLabel;
                                        }
                                        elseif(in_array($SearchParamtersObj->{"get".$solr_clusters[$k]}(),SearchConfig::$dont_all_labels))
                                        {
                                           	$outputArr[$v] = SearchConfig::$doesntMatterLabel;
                                        }
                                        else
                                        {
                                                $outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->getCITY_RES()));

                                        }
				}
			}
			elseif($solr_clusters[$k] == "CASTE")
			{
				if($SearchParamtersObj->getCASTE())
					$tempString = $SearchParamtersObj->getCASTE();
				elseif($SearchParamtersObj->getCASTE_DISPLAY())
					$tempString = $SearchParamtersObj->getCASTE_DISPLAY();

				if($tempString)
				{
					if(strstr($tempString,","))
					{
						$temp = explode(",",$tempString);
						foreach($temp as $kk=>$vv)
						{
							$temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$vv));
							if(!$temp[$kk])
								unset($temp[$kk]);
						}
						$outputArr[$v] = implode(", ",$temp);
						unset($temp);
					}
					elseif($tempString==SearchConfig::$allLabel)
					{
						$outputArr[$v] = SearchConfig::$allLabel;
					}
					elseif(in_array($tempString,SearchConfig::$dont_all_labels))
					{
						$outputArr[$v] = SearchConfig::$doesntMatterLabel;
					}
					else
					{
						$outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$tempString));
					}
				}
			}
			else
			{
				if($SearchParamtersObj->{"get".$solr_clusters[$k]}())
				{
					if(strstr($SearchParamtersObj->{"get".$solr_clusters[$k]}(),","))
					{
						$temp = explode(",",$SearchParamtersObj->{"get".$solr_clusters[$k]}());
                                                $temp = array_unique($temp);
						foreach($temp as $kk=>$vv)
						{
							$temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$vv));
							if(!$temp[$kk])
								unset($temp[$kk]);
						}
						$outputArr[$v] = implode(", ",$temp);
						unset($temp);
					}
					elseif($SearchParamtersObj->{"get".$solr_clusters[$k]}()==SearchConfig::$allLabel)
					{
						$outputArr[$v] = SearchConfig::$allLabel;
					}
					elseif(in_array($SearchParamtersObj->{"get".$solr_clusters[$k]}(),SearchConfig::$dont_all_labels))
					{
						if($solr_clusters[$k]=="MSTATUS")
							$outputArr[$v] = SearchConfig::$allLabel;
						else
							$outputArr[$v] = SearchConfig::$doesntMatterLabel;
					}
					else
					{
						$outputArr[$v] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$solr_clusters[$k]],$SearchParamtersObj->{"get".$solr_clusters[$k]}()));
					}
				}
			}
		}
/*
		if($SearchParamtersObj->getGENDER() == "M")
			$outputArr["Looking for"] = "Male";
		elseif($SearchParamtersObj->getGENDER() == "F")
			$outputArr["Looking for"] = "Female";
*/
		$clusterOrderingObj = new ClusterOrdering($SearchParamtersObj);
		$orderingArr = $clusterOrderingObj->getClusterOrdering("",1,1);
		unset($clusterOrderingObj);
		foreach($orderingArr as $k=>$v)
		{
			if($outputArr && is_array($outputArr) && array_key_exists($v,$outputArr))
			{
				if($outputArr[$v])
					$this->outputArr[$v] = $outputArr[$v];
			}
		}

		if($this->outputArr["Location"] == $this->outputArr["Country"])
			unset($this->outputArr["Location"]);

	//	if($SearchParamtersObj->getSEARCH_TYPE()=="A")
	//	{
			$advanceParameters = explode(",",SearchConfig::$advanceSearchMoreParameters);
			foreach($advanceParameters as $k=>$v)
			{
				if($v=="ENTRY_DT")
				{
					if($SearchParamtersObj->{"getL".$v}() && $SearchParamtersObj->{"getH".$v}())
					{
						$ldateArr = explode("T",$SearchParamtersObj->{"getL".$v}());
						$ldate = $ldateArr[0];
						unset($ldateArr);
						$hdateArr = explode("T",$SearchParamtersObj->{"getH".$v}());
						$hdate = $hdateArr[0];
						unset($hdateArr);
						$this->outputArr[$this->createLabelForAdvanceSearch($v,$SearchParamtersObj->getGENDER())] = $ldate." to ".$hdate;
					}
				}
				elseif($v=="SUBCASTE" || $v=="KEYWORD")
				{
					if($SearchParamtersObj->{"get".$v}())
						$this->outputArr[$this->createLabelForAdvanceSearch($v,$SearchParamtersObj->getGENDER())] = $SearchParamtersObj->{"get".$v}();
				}
				elseif($v=="ONLINE")
				{
					if($SearchParamtersObj->{"get".$v}())
						$this->outputArr[$this->createLabelForAdvanceSearch($v,$SearchParamtersObj->getGENDER())] = "Yes";
				}
				else
				{
					if($SearchParamtersObj->{"get".$v}())
					{
						if(strstr($SearchParamtersObj->{"get".$v}(),","))
						{
							$temp = explode(",",$SearchParamtersObj->{"get".$v}());
							foreach($temp as $kk=>$vv)
							{
								$temp[$kk] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$v],$vv));
								if(!$temp[$kk])
									unset($temp[$kk]);
							}
							$tempStr = implode(", ",$temp);
							unset($temp);
							$this->outputArr[$this->createLabelForAdvanceSearch($v,$SearchParamtersObj->getGENDER())] = $tempStr;
							unset($tempStr);
						}
						else
						{
							$this->outputArr[$this->createLabelForAdvanceSearch($v,$SearchParamtersObj->getGENDER())] = str_replace("&amp;","&",FieldMap::getFieldLabel($fieldMapArr[$v],$SearchParamtersObj->{"get".$v}()));
						}
					}
				}
			}
	//	}

		foreach($this->outputArr as $k=>$v)
		{
			if(!$v)
				unset($this->outputArr[$k]);
		}
		return $this->outputArr;
	}

	/**
	This function generates the text for "You have searched for"
	@params 
	@return text
	**/
	public function getSearchParametersLabelsText($isMobile='')
	{
		if($this->outputArr)
		{
			$str = implode(", ",$this->outputArr);
			if($isMobile == 1)
			{
				if(strlen($str)>$this->searchParametersLabelsTextLengthMobile)
					$str = substr($str,0,$this->searchParametersLabelsTextLengthMobile - 3)." ...";
			}
			else
			{
				if(strlen($str)>$this->searchParametersLabelsTextLength)
					$str = substr($str,0,$this->searchParametersLabelsTextLength)." ...";
			}
			return $str;
		}
		else
			return null;
	}

	private function createLabelForAdvanceSearch($param,$gender)
	{
		if($param == "LIVE_PARENTS")
			$label = "Living with parents";
		elseif($param == "EDU_LEVEL_NEW")
			$label = "Education";
		elseif($param == "HIJAB_MARRIAGE")
			$label = "Hijab After Marraige";
		elseif($param == "SPEAK_URDU")
			$label = "Speak Urdu";
		elseif($param == "CUT_HAIR")
		{
			if($gender=="M")
				$label = "Boy cut his hair";
			elseif($gender=="F")
				$label = "Girl cut her hair";
		}
		elseif($param == "WEAR_TURBAN")
			$label = "Wear Turban";
		elseif($label == "WORK_STATUS")
			$label = "Work Status";
		elseif($param == "BTYPE")
			$label = "Body Type";
		elseif($param == "NATURE_HANDICAP")
			$label  = "Nature of Handicap";
		elseif($param == "KEYWORD_TYPE")
			$label = "Keyword Occurence";
		elseif($param == "ENTRY_DT")
			$label = "Entry Date";
		elseif($param == "SUBCASTE")
			$label = "Subcaste-Keyword";
		else
			$label = substr($param,0,1).strtolower(substr($param,1));
		return $label;
	}
}
?>
