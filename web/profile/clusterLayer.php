<?php
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))

$zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
include("connect.inc");
include("search.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
//include("clusterGlobalarrays.inc");
include("sphinxclusterGlobalarrays.inc");
include("sphinx_search_function.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include("arrays.php");
include("mapping_for_sphinx.php");
include("mapping_for_sphinx1.php");
include(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
$_SERVER['ajax_error']=1;
$db=connect_db();
if($checksum)
	$data=authenticated($checksum);
$smarty->assign("edit",$edit);
$smarty->assign("extraStype",$extraStype);
if($edit==1)
	$clusterLayerClicked=1;
else
{
	$editClusterLayerClicked=1;
	if($selectedLabel)
	{
		if(strstr($selectedLabel,'QQQ'))
			$selectedLabel=@str_replace('QQQ','+',$selectedLabel);
		$smarty->assign("selectedLabel",stripslashes($selectedLabel));
	}
}

//Height is Sp case -- No cluster need to be displayed.
if($categoryLabel=='HEIGHT' && $edit==1)
{
	$searchid=$sid;
	$sql="select LHEIGHT , HHEIGHT  FROM SEARCHQUERY where ID='$searchid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$searchrow=mysql_fetch_array($result);
	$smarty->assign("heightArray",$HEIGHT_DROP);
	$smarty->assign("heightClusterUsed",1);
	$smarty->assign("selectedlheight",$searchrow["LHEIGHT"]);
	$smarty->assign("selectedhheight",$searchrow["HHEIGHT"]);
	$smarty->assign("selectLabel",$diplayMapArray[$categoryLabel]);
	$smarty->assign("searchid",$searchid);
}
elseif($categoryLabel=='INCOME' && $edit==1)
{
	$searchid=$sid;
	$sql="select LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL FROM SEARCHQUERY where ID='$searchid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$searchrow=mysql_fetch_array($result);
	$smarty->assign("selectedLowerIncomeRs",$searchrow[0]);
	$smarty->assign("selectedUpperIncomeRs",$searchrow[1]);
	$smarty->assign("selectedLowerIncomeDo",$searchrow[2]);
	$smarty->assign("selectedUpperIncomeDo",$searchrow[3]);
	populateIncomeDropDowns();

	$smarty->assign("selectLabel",$diplayMapArray[$categoryLabel]);
	$smarty->assign("searchid",$searchid);
}
else
{
	$searchid=$sid;
	$sql="select SEARCH_TYPE , KEYWORD_TYPE , SUBCASTE,GENDER , RELIGION , CASTE , MTONGUE , LAGE , HAGE , WITHPHOTO , COUNTRY_RES , CITY_RES , KEYWORD ,  PHOTOBROWSE , ONLINE , FRESHNESS , MSTATUS , LHEIGHT , HHEIGHT , INCOME , SUBSCRIPTION , EDU_LEVEL_NEW , EDU_LEVEL , OCCUPATION , MANGLIK , DIET , RELATION , CHILDREN , BTYPE , COMPLEXION , SMOKE , DRINK , HANDICAPPED , RES_STATUS , NEWSEARCH_CLUSTERING , INCOME_CLUSTER_MAPPING , OCCUPATION_CLUSTER_MAPPING , EDUCATION_CLUSTER_MAPPING , CASTE_DISPLAY , BREAD_CRUMB , ORIGINAL_SID,CASTE_MAPPING, HOROSCOPE, SPEAK_URDU, HIJAB_MARRIAGE, SAMPRADAY, ZARATHUSHTRI, AMRITDHARI, CUT_HAIR, MATHTHAB, WSTATUS, HIV, HANDICAP, NHANDICAP, FREE_CONTACT, NEW_PROFILE, LIVE_PARENTS,PROFILEID,MEM_LOOK_ME FROM SEARCHQUERY where ID='$searchid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$searchrow=@mysql_fetch_array($result);

	$edu_level_newArr=search_display_format($searchrow["EDU_LEVEL_NEW"]);
	$edu_levelArr=search_display_format($searchrow["EDU_LEVEL"]);
	$occupationArr=search_display_format($searchrow["OCCUPATION"]);
	$incomeArr=search_display_format($searchrow["INCOME"]);
	$country_resArr=search_display_format($searchrow["COUNTRY_RES"]);
	$mstatusArr=search_display_format($searchrow["MSTATUS"]);
	$dietArr=search_display_format($searchrow["DIET"]);
	$drinkArr=search_display_format($searchrow["DRINK"]);
	$smokeArr=search_display_format($searchrow["SMOKE"]);
	$manglikArr=search_display_format($searchrow["MANGLIK"]);
	$relationArr=search_display_format($searchrow["RELATION"]);
	$havechildArr=search_display_format($searchrow["CHILDREN"]);
	$lage=$searchrow["LAGE"];
	$hage=$searchrow["HAGE"];	
	$gender=$searchrow["GENDER"];
	$caste_mapping=$searchrow['CASTE_MAPPING'];
	$lheight=$searchrow["LHEIGHT"];
	$hheight=$searchrow["HHEIGHT"];
	$religionArr=search_display_format($searchrow["RELIGION"]);
	$casteArr=search_display_format($searchrow["CASTE"]);
	$withphotoArr=search_display_format($searchrow["WITHPHOTO"]);
	$mtongueArr=search_display_format($searchrow["MTONGUE"]);
	
	//More fields added for advance search
        $live_parents=search_display_format($searchrow["LIVE_PARENTS"]);
        $mathabArr=search_display_format($searchrow["MATHTHAB"]);
	$Sub_caste=search_display_format($searchrow["SUBCASTE"]);
	$horoscopeArr=search_display_format($searchrow["HOROSCOPE"]);
	$sampradayArr=search_display_format($searchrow["SAMPRADAY"]);
	$amritdhariArr=search_display_format($searchrow["AMRITDHARI"]);
	$cut_hairArr=search_display_format($searchrow["CUT_HAIR"]);
	$turbanArr=search_display_format($searchrow["TURBAN"]);
	$urduArr=search_display_format($searchrow["URDU"]);
	$hijabArr=search_display_format($searchrow["HIJAB_MARRIAGE"]);
	$zarathustriArr=search_display_format($searchrow["ZARATHUSTRI"]);
	$wstatusArr=search_display_format($searchrow["WSTATUS"]);
	$handicappedArr=search_display_format($searchrow["HANDICAP"]);
	$nhandicappedArr=search_display_format($searchrow["NHANDICAP"]);
	$hiv=$searchrow["HIV"];
        $Contact_visible=$searchrow["FREE_CONTACT"];
        $subscriptionArr=search_display_format($searchrow["SUBSCRIPTION"]);

        $Login=$searchrow["NEW_PROFILE"];
	$btypeArr=search_display_format($searchrow["BTYPE"]);
	$complexionArr=search_display_format($searchrow["COMPLEXION"]);
	$city_resArr=search_display_format($searchrow["CITY_RES"]);
	if($searchrow["ONLINE"]=='1')
		$onlineArr=1;
	$keywords=$searchrow["KEYWORD"];
	$kwd_rule=$searchrow["KEYWORD_TYPE"];
		
	if($categoryLabel=="INDIA_NRI")
	{
		$categoryLabel="COUNTRY_RES";
		if($selectedvalue==0 && !$edit)
		{
			$smarty->assign("OtherCountry",1);
		}
	}
	elseif($categoryLabel=="UNMARRIED_MARRIED")
		$categoryLabel="MSTATUS";
	$searchLabelInfo=getSearchLabelInfo($categoryLabel);
	$groupBy=$searchLabelInfo[0];
	$dropdownArrayName=$searchLabelInfo[1];
	$arrName=$searchLabelInfo[2];
	$showOtherlabel=$searchLabelInfo[3];

	//edit cluster
	if($edit==1)
	{
		if(!is_array(${$arrName}))
			${$arrName}[0]='';	
		foreach(${$arrName} as $v)
		{
			if($categoryLabel=="OCCUPATION")
			{
				if(in_array($v,$OCC_CLUSTER_REVERSE_GROUP_ARRAY))
					$checkedMearr1[$OCC_CLUSTER_REVERSE_ONE_MATCH_ARRAY[$v][0]]='Y';
				else
				{
					 $smarty->assign("occOthes",1);
					$checkedMearr1[$v]='Y';
				}
			}
			else
				$checkedMearr1[$v]='Y';
			//Tick Asia Button When any one sub-category is clicked
			//if($categoryLabel=='COUNTRY_RES' && in_array($v,array(11,14,25,48,52,70,74,80,88,101,103,110,119,92,125,63,99)))
			if($categoryLabel=='COUNTRY_RES' && in_array($v,array(11,63,70,80,88,99,103,110,125)))
				$smarty->assign("asiaCheck",1);
			//elseif($categoryLabel=="COUNTRY_RES" && !in_array($v,array(51,128,22,126,7,11,14,25,48,52,70,74,80,88,101,103,110,119,92)))
			elseif($categoryLabel=="COUNTRY_RES" && !in_array($v,array(51,128,22,126,7,82,11,63,70,80,88,99,103,110,125)))
				$smarty->assign("OtherCountry",1);
			//Tick Asia Button When any one sub-category is clicked
			if($v=='')
				$includeNotSpecifiedUsed=1;
		}

		//include profile that haven't specified  is not used.
		if(!$includeNotSpecifiedUsed)
			$smarty->assign("includeNotSpecifiedUsed",1);
		//print_r($checkedMearr1);
		$smarty->assign("editcheckedMearr",$checkedMearr1);
		unset(${$arrName});
	}
	//edit cluster

	//Reverse Search
	$MEM_LOOK_ME=$searchrow["MEM_LOOK_ME"];
	if($MEM_LOOK_ME)
	{
		$profileid=$searchrow["PROFILEID"];
		global $r_page,$r_Gender,$r_Castes,$r_Manglik,$r_MTongue,$r_MStatus,$r_Occ,$r_CountryRes,$r_CityRes,$r_Height,$r_ELevel,$r_ELevelNew,$r_Drink,$r_Smoke,$r_Child,$r_Btype,$r_Diet,$r_Handicapped,$r_Age,$r_Income,$r_Relation,$r_Comp,$STYPE,$Sort,$r_Religion;
		getreverseData($profileid);
	}
	//Reverse Search

	$clusterArr=search($gender,$religionArr,$casteArr,$mtongueArr,$lage,$hage,$withphotoArr,$manglikArr,$mstatusArr,$havechildArr,$lheight,$hheight,$btypeArr,$complexionArr,$dietArr,$smokeArr,$drinkArr,$handicappedArr,$occupationArr,$country_resArr,$city_resArr,$edu_levelArr,$edu_level_newArr,$sortArr,$onlineArr,$incomeArr,$relationArr,$nriArr,$page,$bread_crumb,$original_sid,$allow_suggestion,$force,$searchid,$STYPE,$live_parents,$Sub_caste,$horoscopeArr,$sampradayArr,$urduArr,$hijabArr,$mathabArr,$amritdhariArr,$cut_hairArr,$turbanArr,$zarathustriArr,$wstatusArr,$handicappedArr,$nhandicappedArr,$hiv,$keywords,$kwd_rule,$Login,$Contact_visible,$subscriptionArr);
	if($heightClusterUsed)
	{
		$smarty->assign("heightArray",$HEIGHT_DROP);
		$smarty->assign("heightClusterUsed",1);
		$smarty->assign("searchid",$sid);
		$smarty->assign("categoryLabel",trim($categoryLabel));
		$smarty->display("search_cluser_layer2.htm");
		exit;
	}
	if($groupBy=='INCOME')
	{
		global $INC_CLUSTER_RANGE_ARRAY;
		$incSelectedArray=$INC_CLUSTER_RANGE_ARRAY[$selectedvalue];
		$smarty->assign("selectedLowerIncomeRs",$incSelectedArray[0]);
		$smarty->assign("selectedLowerIncomeDo",$incSelectedArray[2]);
		if($morelink==1)
		{
			$smarty->assign("selectedUpperIncomeRs",19);
			$smarty->assign("selectedUpperIncomeDo",19);
		}
		else
		{
			$smarty->assign("selectedUpperIncomeRs",$incSelectedArray[1]);
			$smarty->assign("selectedUpperIncomeDo",$incSelectedArray[3]);
		}
		populateIncomeDropDowns('',$selectedvalue);
		$smarty->assign("selectLabel",$diplayMapArray[$categoryLabel]);
		$smarty->assign("searchid",$sid);
		$smarty->assign("categoryLabel",trim($categoryLabel));
		$smarty->display("search_cluser_layer2.htm");
		exit;
	}




	if($categoryLabel=="COUNTRY_RES")
	{
		$skipelse=1;
		arsort($clusterArr);
                $smarty->assign("countryCluster",1);
		foreach($clusterArr as $countryValue=>$v)
		{
			if($countryValue==51)
				$smarty->assign("indiaCnt",$v);
			elseif($countryValue==128)
				$smarty->assign("usaCnt",$v);
			elseif($countryValue==22)
				$smarty->assign("canadaCnt",$v);
			elseif($countryValue==126)
				$smarty->assign("ukCnt",$v);

			elseif(in_array($countryValue,array(7)))
				$austaliaCnt+=$v;
			elseif(in_array($countryValue,array(11,63,70,80,88,99,103,110,125)))
			//elseif(in_array($countryValue,array(11,14,25,48,52,70,74,80,88,101,103,110,119,92,125,63,99)))
			{
				$asiaCnt+=$v;
				$labelname[]=$COUNTRY_DROP[$countryValue];
				$value[]=$countryValue;
				$cntFinalArr[]=$v;
			}
			else
			{
				$otherCnt+=$v;	
				$OtherValuesArr[]=$countryValue;
			}
		}
		if($selectedvalue==1)
			$smarty->assign("india",1);
		elseif($selectedvalue=='0')
			$smarty->assign("nonIndia",1);
		$smarty->assign("noOfAsianCountries",count($value));
		$smarty->assign("austaliaCnt",$austaliaCnt);
		$smarty->assign("asiaCnt",$asiaCnt);
		$smarty->assign("otherCnt",$otherCnt);
		if($OtherValuesArr)
			$OtherValues=implode(",",$OtherValuesArr);
		$smarty->assign("OtherValues",$OtherValues);
	}
	/*
	elseif($groupBy=='INCOME')
	{
		$skipelse=1;
		$sql="SELECT VALUE,LABEL FROM newjs.INCOME ORDER BY SORTBY ASC";
		$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
		while($row=mysql_fetch_array($result))
		{
			$labelname[]=$row['LABEL'];
			$value[]=$row['VALUE'];
			$a=$row['LABEL'];
			$cntFinalArr[]=$clusterArr[$groupBy][$a][0];
			if($categoryLabel=='INCOME' && is_array($INC_CLUSTER_ARRAY[$selectedvalue]))
			{
				if(in_array($row['VALUE'],$INC_CLUSTER_ARRAY[$selectedvalue]))
					$checkedMearr[$a]='Y';
			}
		}
	}
	*/
	elseif($categoryLabel=='EDU_LEVEL_NEW')
	{
		$skipelse=1;
		$smarty->assign("arrNameUsed","edu_level_newArr");
		//print_r($clusterArr['EDU_LEVEL_NEW']);
		$sql="SELECT SQL_CACHE LABEL , VALUE  FROM EDUCATION_LEVEL_NEW  ORDER BY SORTBY ASC";
		$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
		while($row=mysql_fetch_array($result))
		{
			$value=$row["VALUE"];
			$Label=$row["LABEL"];

			if(is_array($EDU_CLUSTER_ARRAY[$selectedvalue]))
			{
				if(in_array($value,$EDU_CLUSTER_ARRAY[$selectedvalue]))
					$checkedMearr[$value]='Y';
			}

			if(in_array($value,$EDU_CLUSTER_LAYER_ARRAY['P']))
			{
				if($clusterArr['EDU_LEVEL_NEW'][$Label][1])
				{
					$prof[$Label][0]=$clusterArr['EDU_LEVEL_NEW'][$Label][0];
					$prof[$Label][1]=$clusterArr['EDU_LEVEL_NEW'][$Label][1];
				}
			}
			elseif(in_array($value,$EDU_CLUSTER_LAYER_ARRAY['A']))
			{
				if($clusterArr['EDU_LEVEL_NEW'][$Label][1])
				{
					$post[$Label][0]=$clusterArr['EDU_LEVEL_NEW'][$Label][0];
					$post[$Label][1]=$clusterArr['EDU_LEVEL_NEW'][$Label][1];
				}
			}
			elseif(in_array($value,$EDU_CLUSTER_LAYER_ARRAY['G']))
			{
				if($clusterArr['EDU_LEVEL_NEW'][$Label][1])
				{
					$grad[$Label][0]=$clusterArr['EDU_LEVEL_NEW'][$Label][0];
					$grad[$Label][1]=$clusterArr['EDU_LEVEL_NEW'][$Label][1];
				}
			}
			else
			{
				if($clusterArr['EDU_LEVEL_NEW'][$Label][1])
				{
					$other[$Label][0]=$clusterArr['EDU_LEVEL_NEW'][$Label][0];
					$other[$Label][1]=$clusterArr['EDU_LEVEL_NEW'][$Label][1];
				}
			}
		}
                if($prof)
                {
                        $classificationEdu['Professionals']=$prof;
                        $classificationEduCnt['Professionals']=count($prof);
                }
                if($post)
                {
                        $classificationEdu['Post Graduate Degrees']=$post;
                        $classificationEduCnt['Post Graduate Degrees']=count($post);
                }
                if($grad)
                {
                        $classificationEdu['Graduate Degrees']=$grad;
                        $classificationEduCnt['Graduate Degrees']=count($grad);
                }
                if($other)
                {
                        $classificationEdu['Other Education courses']=$other;
                        $classificationEduCnt['Other Education courses']=count($other);
                }
                $smarty->assign("regionWiseCount",$classificationEduCnt);
                $smarty->assign("regionWiseMtongue",$classificationEdu);
	}
	elseif($categoryLabel=='MTONGUE')
	{
		$smarty->assign("arrNameUsed","mtongueArr");
		$skipelse=1;
		/*
		print_r($clusterArr['MTONGUE']);
		$smarty->assign("NORTH",$clusterArr['MTONGUE']);
		*/
		$sql="SELECT SQL_CACHE LABEL ,REGION, VALUE, SMALL_LABEL FROM MTONGUE WHERE REGION<>5 ORDER BY REGION desc,SORTBY_NEW ASC";
		$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
		while($row=mysql_fetch_array($result))
		{
			$value=$row["VALUE"];
			$Label=$MTONGUE_DROP[$value];
			$sLabel=$MTONGUE_DROP_SMALL[$value];
			
			if($row["REGION"]==4)
			{
				if($clusterArr['MTONGUE'][$Label][1])
				{
					$north[$sLabel][0]=$clusterArr['MTONGUE'][$Label][0];
					$north[$sLabel][1]=$clusterArr['MTONGUE'][$Label][1];
				}
			}
			elseif($row["REGION"]==3)
			{
				if($clusterArr['MTONGUE'][$Label][1])
				{
					$west[$sLabel][0]=$clusterArr['MTONGUE'][$Label][0];
					$west[$sLabel][1]=$clusterArr['MTONGUE'][$Label][1];
					if($clusterArr['MTONGUE'][$Label][0]!=19)
						$otherThanHindiMp=1;
				}
			}
			elseif($row["REGION"]==2)
			{
				if($clusterArr['MTONGUE'][$Label][1])
				{
					$south[$sLabel][0]=$clusterArr['MTONGUE'][$Label][0];
					$south[$sLabel][1]=$clusterArr['MTONGUE'][$Label][1];
				}
			}
			elseif($row["REGION"]==1)
			{
				if($clusterArr['MTONGUE'][$Label][1]>0)
				{
					$east[$sLabel][0]=$clusterArr['MTONGUE'][$Label][0];
					$east[$sLabel][1]=$clusterArr['MTONGUE'][$Label][1];
				}
			}
			elseif($row["REGION"]==0)
			{
				if($clusterArr['MTONGUE'][$sLabel][1])
				{
					$forgein[$sLabel][0]=$clusterArr['MTONGUE'][$Label][0];
					$forgein[$sLabel][1]=$clusterArr['MTONGUE'][$Label][1];
				}
			}
		}
		$commLoop=0;
		if($north)
		{
			$commLoop+=1;
			$regionWiseMtongue['North']=$north;
			$regionWiseCount['North']=count($north);
		}
		if($west)
		{
			if(!$otherThanHindiMp)
				$commLoop+=1;
			$regionWiseMtongue['West']=$west;
			$regionWiseCount['West']=count($west);
		}
		if($south)
		{
			$commLoop+=1;
			$regionWiseMtongue['South']=$south;
			$regionWiseCount['South']=count($south);
		}
		if($east)
		{
			$commLoop+=1;
			$regionWiseMtongue['East']=$east;
			$regionWiseCount['East']=count($east);
		}
		if($forgein)
		{
			$commLoop+=1;
			$regionWiseMtongue['skipheading']=$forgein;
			$regionWiseCount['skipheading']=0;
		}		
		if($commLoop>1)
		{
		$smarty->assign("regionWiseCount",$regionWiseCount);
		$smarty->assign("regionWiseMtongue",$regionWiseMtongue);
		}
		else
		{
			$skipelse=0;
			unset($regionWiseMtongue);
			unset($value);
			unset($Label);
			unset($sLabel);
		}
	}
	elseif($categoryLabel=="MSTATUS")
	{
		$skipelse=1;
		foreach($clusterArr as $k=>$v)
		{
			if(${$dropdownArrayName}[$k]=='Other' || ${$dropdownArrayName}[$k]=='Others') 
			{
				$temp_1=${$dropdownArrayName}[$k];
				$temp_2=$k;
				$temp_3=$v;
			}
			else
			{
				$labelname[]=${$dropdownArrayName}[$k];
				$value[]=$k;
				$cntFinalArr[]=$v;
				//$edit=0;
			}
			if($edit==1)
			{
				//$checkedMearr[${$dropdownArrayName}[$k]]='Y';
			}
			else
			{
				if($selectedvalue==1 || $selectedvalue=='N')
					$selectedvalue='N';
				else
				{
					if($k!='N')
					{
						$checkedMearr[${$dropdownArrayName}[$k]]='Y';
					}
				}
			}
		}
		//Other should appear at last
		if($temp_1)
		{
			$labelname[]=$temp_1;
			$value[]=$temp_2;	
			$cntFinalArr[]=$temp_3;
		}
		//Other should appear at last
	}
	elseif($categoryLabel=="MANGLIK")
	{
		$skipelse=1;
		$other_manglik=0;
		foreach($clusterArr[$categoryLabel] as $k=>$v)
		{
			if($v[1] && $v[1]!='D')
			{
				$labelname[]=$k;//hindu , muslim ...
				$cntFinalArr[]=$v[0];//no of records
				$value[]=$v[1];//search values
			}
			else
			{
				$other_manglik+=$v[0];
			}
		}
		$smarty->assign("Others",$other_manglik);	
		$smarty->assign("manglik_others","D,");
	}
	//else

	if(!$skipelse)
	{
		foreach($clusterArr[$categoryLabel] as $k=>$v)
		{
			if($k)
			{
				if($categoryLabel=='OCCUPATION')
				{
					$tempVal_1=$v[1];
					$cnt=$v[0];
					if($edit)
						$occArr[]=$tempVal_1;	

					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['B']))
					{
						$label="Entrepreneurship/ Business";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='B';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['I']))
					{
						$label="Software";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='I';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['M']))
					{
						$label="Doctor/ Medical Profession";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='M';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['D']))
					{
						$label="Defence";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='D';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['MS']))
					{
						$label="Marketing/Sales/Adv";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='MS';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['T']))
					{
						$label="Teaching";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='T';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['F']))
					{
						$label="Finance (CA, CS)";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='F';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['A']))
					{
						$label="Administration";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='A';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['MA']))
					{
						$label="Production/Maintenance";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='MA';
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['O']))
					{
						$label="zOthers";//z added so to make it come to last
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='O';
						$tempLabel=$OCCUPATION_DROP[$tempVal_1];
						if($tempLabel=='Others')
							$tempLabel='zOthers';
						/*
						$occOthersArr[$tempLabel][0]+=$cnt;
						$occOthersArr[$tempLabel][1]+=$tempVal_1;
						*/
						$occOthersArrCnt[$tempVal_1]+=$cnt;
						$occOthersArrLabel[$tempVal_1]=$tempLabel;
						
					}
					if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['G']))
					{
						$label="Government";
						$temparr[$label][0]+=$cnt;
						$temparr[$label][1]='G';
					}
				}
				else
				{
					//Other should appear at last
					if($k=='Other' || $k=='Others') 
					{
						$temp_1=$k;
						$temp_2=$v[0];
						$temp_3=$v[1];
					}
					else
					{
	                                        if($categoryLabel=="CASTE")
        	                                {
                	                                if(strstr($k,": zOthers"))
                        	                        {
                                        	                $k=str_replace(": zOthers",": Others",$k);
                                	                }
	                                        }
						$labelname[]=$k;//hindu , muslim ...
						$cntFinalArr[]=$v[0];//no of records
						$value[]=$v[1];//search values
					}

					if($categoryLabel=='EDU_LEVEL_NEW' && is_array($EDU_CLUSTER_ARRAY[$selectedvalue]))
					{
						if(in_array($v[1],$EDU_CLUSTER_ARRAY[$selectedvalue]))
							$checkedMearr[$k]='Y';
					}
				}
			}
			else
				$smarty->assign("Others",$v[0]);
		}
		//Other occ array
		//arsort($occOthersArrCnt);
		if(is_array($occOthersArrLabel))
		{
			asort($occOthersArrLabel);
			foreach($occOthersArrLabel as $k=>$v)
			{
				$occOthersArrloop[]=$k;
				if($occOthersArrLabel[$k]=='zOthers')
					$occOthersArrLabel1[]='Other Occupation';	
				else	
					$occOthersArrLabel1[]=$occOthersArrLabel[$k];	
				$occOthersArrCnt1[]=$occOthersArrCnt[$k];
				$occOthersArrVal1[]=$k;
			}
			$smarty->assign("occOthersArrVal",$occOthersArrVal1);
			$smarty->assign("occOthersArrLabel",$occOthersArrLabel1);
			$smarty->assign("occOthersArrCnt",$occOthersArrCnt1);
			$smarty->assign("noOfOthersOcc",count($occOthersArrCnt));
			$smarty->assign("occOthersArrloop",$occOthersArrloop);
		}
		//Other occ array

		//Other should appear at last
		if($temp_1)
		{
			$labelname[]=$temp_1;
			$cntFinalArr[]=$temp_2;
			$value[]=$temp_3;	
		}
		//Other should appear at last

		if(is_array($temparr))
		{
			ksort($temparr);
			//print_r($temparr);
			foreach($temparr as $k=>$v)
			{
				if($k=='zOthers')
					$k='Others';
				if($occOthersArrCnt && $k=='Others')
				{
					$smarty->assign("showOtherlabel",1);
					$smarty->assign("Others",$v[0]);
					$smarty->assign("OthersVal",$v[1]);
				}
				else
				{
					$labelname[]=$k;
					$cntFinalArr[]=$v[0];
					$value[]=$v[1];
				}
			}
		}
	}
}

//To show denomination in Cluster if muslim/christian is choosen
if($categoryLabel=='CASTE')
{
	if($religionArr)
	{
		foreach($religionArr as $k=>$v)
			if($v<2 OR $v>3)
				$fTemp=1;

		if(!$fTemp)
		{
			$diplayMapArray['CASTE']='Denomination';
			$smarty->assign("diplayMapArray",$diplayMapArray);
		}
	}
}
/*
for($i=95;$i<400;$i++)
{
	//echo $i."--";
	unset($labelname[$i]);
}*/
//print_r($labelname);

//To show denomination in Cluster if muslim/christian is choosen
/*
echo "<br>";
echo "labelname-->";
print_r($labelname);

echo "<br>";
echo "labelvalue-->";
print_r($value);

echo "<br>";
echo "ARRNAME--->";
print_r($arrName);

echo "<br>";
echo "cntFinalArr--->";
print_r($cntFinalArr);

echo "<br>";
echo "selectedvalue--->";
print_r($selectedvalue);

echo "<br>";
echo "checkedMearr--->";
print_r($checkedMearr);

echo "<br>";
echo "showOtherlabel--->";
print_r($cshowOtherlabel);

echo "<br>";
echo "sid---".$sid;
*/
$smarty->assign("categoryLabel",$categoryLabel);
$smarty->assign("selectLabel",$diplayMapArray[$categoryLabel]);
$smarty->assign("labelname",$labelname);
$smarty->assign("labelvalue",$value);
$smarty->assign("arrName",$arrName);
$smarty->assign("cntFinalArr",$cntFinalArr);
$smarty->assign("selectedvalue",$selectedvalue);
$smarty->assign("checkedMearr",$checkedMearr);
$smarty->assign("showOtherlabel",$showOtherlabel);
$smarty->display("search_cluser_layer2.htm");

/*
function populateIncomeDropDowns()
{
	global $db,$smarty;
	$sql="SELECT LABEL,MIN_LABEL,MIN_VALUE,MAX_LABEL,MAX_VALUE,TYPE FROM  INCOME WHERE VISIBLE =  'Y' ORDER BY SORTBY";
	$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($row=mysql_fetch_array($result))
	{
		if($row["LABEL"]=='No Income')
		{
			$maxLabel[0][$row['MAX_VALUE']]='No Income';
			$maxLabel[1][$row['MAX_VALUE']]='No Income';
		}
		else
		{
			if($row["TYPE"]=='RUPEES')
			{
				if(isset($row['MIN_LABEL']))
					$minLabel[0][$row['MIN_VALUE']]=$row['MIN_LABEL'];
				if(isset($row['MAX_LABEL']))
					$maxLabel[0][$row['MAX_VALUE']]=$row['MAX_LABEL'];
			}
			else
			{
				if(isset($row['MIN_LABEL']))
					$minLabel[1][$row['MIN_VALUE']]=$row['MIN_LABEL'];
				if(isset($row['MAX_LABEL']))
					$maxLabel[1][$row['MAX_VALUE']]=$row['MAX_LABEL'];
			}
		}
	}
	$smarty->assign("MAX_LABEL_RS",$maxLabel[0]);
	$smarty->assign("MIN_LABEL_RS",$minLabel[0]);
	$smarty->assign("MAX_LABEL_DO",$maxLabel[1]);
	$smarty->assign("MIN_LABEL_DO",$minLabel[1]);
}
*/
?>
