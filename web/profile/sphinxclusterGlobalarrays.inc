<?php

include_once("connect.inc");
global $smarty;
/*
$SPHINX_INCOME_DROP[1] = "Under Rs .5 lacs";
$SPHINX_INCOME_DROP[2] = "Rs .5lacs - 1lacs";
$SPHINX_INCOME_DROP[3] = "Rs 1lacs - 2lacs";
$SPHINX_INCOME_DROP[4] = "Rs 2lacs - 3lacs";
$SPHINX_INCOME_DROP[5] = "Rs 3lacs - 4lacs";
$SPHINX_INCOME_DROP[6] = "Rs 4lacs - 5lacs";
$SPHINX_INCOME_DROP[7] = "Rs 5lacs and above";
$SPHINX_INCOME_DROP[8] = "Under $25,000";
$SPHINX_INCOME_DROP[9] = "$25,001 - 50,000";
$SPHINX_INCOME_DROP[10] = "$50,001 - 75,000";
$SPHINX_INCOME_DROP[11] = "$75,001 - 100,000";
$SPHINX_INCOME_DROP[12] = "$100,001 - 150,000";
$SPHINX_INCOME_DROP[13] = "$150,001 - 200,000";
$SPHINX_INCOME_DROP[14] = "$200,001 and above";
$SPHINX_INCOME_DROP[15] = "No Income";
$SPHINX_INCOME_DROP[16] = "Rs.5,00,001 - 7,50,000";
$SPHINX_INCOME_DROP[17] = "Rs.7,50,001 - 10,00,000";
$SPHINX_INCOME_DROP[18] = "Rs.10,00,001 and above";
*/

//$defaultClusterOrderingArr=array('RELIGION','MTONGUE','CASTE','HEIGHT','EDU_LEVEL_NEW','OCCUPATION','INCOME','INDIA_NRI','UNMARRIED_MARRIED','HAVECHILD','DIET','DRINK','SMOKE','MANGLIK','RELATION');
/*
$defaultClusterOrderingArr=array('SUBSCRIPTION','RELIGION','HAVECHILD','MTONGUE','CASTE','HEIGHT','EDU_LEVEL_NEW','OCCUPATION','INCOME','INDIA_NRI','UNMARRIED_MARRIED','MANGLIK','DIET','RELATION');

$diplayMapArray=array("RELIGION"=>"Religion","MTONGUE"=>"Mother tongue","CASTE"=>"Caste","HEIGHT"=>"Height","EDU_LEVEL_NEW"=>"Education","OCCUPATION"=>"Occupation","INCOME"=>"Income","COUNTRY_RES"=>"Location","MSTATUS"=>"Marital Status","HAVECHILD"=>"Have-Children","DIET"=>'Diet',"DRINK"=>"Drink","SMOKE"=>"Smoke","MANGLIK"=>"Manglik","RELATION"=>"Profile posted by","INDIA_NRI"=>"Location","UNMARRIED_MARRIED"=>"Marital Status","SUBSCRIPTION"=>"Show profiles");
*/
$defaultClusterOrderingArr=array('RELIGION','HAVECHILD','MTONGUE','CASTE','HEIGHT','EDU_LEVEL_NEW','OCCUPATION','INCOME','INDIA_NRI','UNMARRIED_MARRIED','MANGLIK','DIET','RELATION');

$diplayMapArray=array("RELIGION"=>"Religion","MTONGUE"=>"Mother tongue","CASTE"=>"Caste","HEIGHT"=>"Height","EDU_LEVEL_NEW"=>"Education","OCCUPATION"=>"Occupation","INCOME"=>"Income","COUNTRY_RES"=>"Location","MSTATUS"=>"Marital Status","HAVECHILD"=>"Have-Children","DIET"=>'Diet',"DRINK"=>"Drink","SMOKE"=>"Smoke","MANGLIK"=>"Manglik","RELATION"=>"Profile posted by","INDIA_NRI"=>"Location","UNMARRIED_MARRIED"=>"Marital Status");

$smarty->assign("diplayMapArray",$diplayMapArray);

function sphinxupdateCluster($sid='',$religion='',$caste='',$mtongue='',$height='',$Edu_Level='',$Edu_Level_New='',$Occupation='',$Income='',$Country_Res='',$City_Res='',$Mstatus='',$HaveChild='',$Diet='',$Drink='',$Smoke='',$Manglik='',$Relation='',$NEW_SEARCH_CLUSTERING='',$bread_crumb='',$contact_visible='',$Subscription='')
{
        if($religion[0]=='DONT_MATTER')
                unset($religion);
        if($mtongue[0]=='DONT_MATTER')
                unset($mtongue);
        if($Mstatus[0]=='DONT_MATTER')
                unset($Mstatus);
        if($Country_Res[0]=='DONT_MATTER')
                unset($Country_Res);
        if($City_Res[0]=='DONT_MATTER')
                unset($City_Res);
        //Religion
        if(!$religion && !$caste && !$mtongue)
	{
                $clusterDisplayedArr[]="RELIGION";
	}
        //Religion

        //Community
        if($religion)
        {
                if($mtongue)
                {
                        //if is a super community
			if(count($mtongue)>1 && !strstr($bread_crumb,"MTONGUE"))
			{
                        	$clusterDisplayedArr[]="MTONGUE";
			}
                }
                else
                        $clusterDisplayedArr[]="MTONGUE";
        }
        //Community

        //Caste
        if($mtongue)
        {
                if($caste)
		{
	                //if is a super caste
			 if(count($caste)>1 && !strstr($bread_crumb,"CASTE"))
			{
                        	$clusterDisplayedArr[]="CASTE";
			}
		}
                elseif($religion)
                {
                        $clusterDisplayedArr[]="CASTE";
                }
        }                       
        //Caste
	if(!$height)
	        $clusterDisplayedArr[]="HEIGHT";

	if(!$Edu_Level && !$Edu_Level_New)
	{
	        $clusterDisplayedArr[]="EDU_LEVEL_NEW";
	}
	if(!$Occupation)
	        $clusterDisplayedArr[]="OCCUPATION";
	if(!$Income)	
        	$clusterDisplayedArr[]="INCOME";
	if(!$Country_Res && !$City_Res)
	{
	        //$clusterDisplayedArr[]="COUNTRY_RES";
	        $clusterDisplayedArr[]="INDIA_NRI";
	}
	if(!$Mstatus)
	{
	        //$clusterDisplayedArr[]="MSTATUS";
	        $clusterDisplayedArr[]="UNMARRIED_MARRIED";
	}
	if(!$HaveChild && @count(array_intersect($Mstatus,array('A','O','D','W','S')))>0)
	        $clusterDisplayedArr[]="HAVECHILD";
	if(!$Diet)
	        $clusterDisplayedArr[]="DIET";
	/*
	if(!$Drink)	
	        $clusterDisplayedArr[]="DRINK";
	if(!$Smoke)
        	$clusterDisplayedArr[]="SMOKE";
	*/
	if(!$Manglik)
	        $clusterDisplayedArr[]="MANGLIK";
	if(!$Relation)
	        $clusterDisplayedArr[]="RELATION";

	//New
	/*
	if(!$contact_visible && !$Subscription)
		$clusterDisplayedArr[]="SUBSCRIPTION";
	*/
	//New
        if(!is_array($clusterDisplayedArr))
                return ;

	global $data,$defaultClusterOrderingArr,$db;

        $size=count($defaultClusterOrderingArr);
	$pid=$data["PROFILEID"];

        if($pid)
        {
		if(!$db) $db = connect_db();
                $sql_lavesh="SELECT WEIGHT_ORDERING FROM twowaymatch.TRENDS WHERE PROFILEID=$pid";
                $result=mysql_query($sql_lavesh,$db) or die("$sql_lavesh".mysql_error($db));
                $row=mysql_fetch_array($result);
                $weight=$row["WEIGHT_ORDERING"];
        }
        if($weight)
        {
                $tArray=explode(",",$weight);
		foreach($tArray as $k=>$v)
		{
			if(in_array($v,$clusterDisplayedArr))
				$groupByFieldArr[]=$v;
		}
		/*
                for($ll=0;$ll<$size;$ll++)
                {
                        $tempGroupBy=$defaultClusterOrderingArr[$ll];
                        if( !in_array($tempGroupBy,$groupByFieldArr) && in_array($tempGroupBy,$clusterDisplayedArr))
                                $groupByFieldArr[]=$tempGroupBy;
			print_r($groupByFieldArr);
                }
		*/
		//print_r($groupByFieldArr);
        }
        else
        {
                for($ll=0;$ll<$size;$ll++)
                {
                        $tempGroupBy=$defaultClusterOrderingArr[$ll];
                        if(in_array($tempGroupBy,$clusterDisplayedArr))
                                $groupByFieldArr[]=$tempGroupBy;
                }
        }
        return $groupByFieldArr;
}

/**
* 
* @param string $categoryLabel Search Parameter Used
* @return array 
*/
function getSearchLabelInfo($categoryLabel)
{
        $clusterTableInfo=array("RELIGION"=>"RELIGION#RELIGIONS#religionArr",
                                "MTONGUE"=>"MTONGUE#MTONGUE_DROP#mtongueArr",
                                "CASTE"=>"CASTE#CASTE_DROP#casteArr",
                                "INCOME"=>"INCOME#INCOME_DROP#incomeArr",
                                "EDU_LEVEL_NEW"=>"EDU_LEVEL_NEW#EDUCATION_LEVEL_NEW_DROP#edu_level_newArr",
                                "OCCUPATION"=>"OCCUPATION#OCCUPATION_DROP#occupationArr",
                                "MSTATUS"=>"MSTATUS#MSTATUS#mstatusArr",
                                "HAVECHILD"=>"HAVECHILD#CHILDREN#havechildArr",
                                "DIET"=>"DIET#DIET#dietArr#'diet preference'",
                                "DRINK"=>"DRINK#DRINK#drinkArr#drinking preference",
                                "SMOKE"=>"SMOKE#SMOKE#smokeArr#smoke preference",
                                "MANGLIK"=>"MANGLIK#MANGLIK#manglikArr#manglik status",
                                "RELATION"=>"RELATION#RELATIONSHIP#relationArr",
                                "COUNTRY_RES"=>"COUNTRY_RES#COUNTRY_DROP#country_resArr",
                                "HEIGHT"=>"HEIGHT#HEIGHT_DROP#height_resArr",
				"SUBSCRIPTION"=>"SUBSCRIPTION#SUBSCRIPTION#subscriptionArr"
                                );
        return explode("#",$clusterTableInfo[$categoryLabel]);
}

function search_display_format($str='',$addoption='')
{
        global $MEM_LOOK;
        if($str)
        {
                if(is_array($str))
                        return $str;
                {
                        if($MEM_LOOK)
                        {
                                $str=trim($str,"'");
                                $arr=explode("','",$str);
                        }
                        else
                                $arr=explode(",",$str);
			if($addoption)
				$arr[]=$addoption;
			if($arr)
				$arr=array_unique($arr);
                        return $arr;
                }
        }
        return '';
}

?>
