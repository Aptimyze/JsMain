<?php
/* FILE NOW NOT USED. NEED TO CHECK*/
include_once("connect.inc");
global $smarty;

//Priority Array for logout case.
$defaultClusterOrderingArr=array('RELIGION','MTONGUE','CASTE','HEIGHT','EDU_LEVEL_NEW','OCCUPATION','INCOME','COUNTRY_RES','MSTATUS','HAVECHILD','DIET','DRINK','SMOKE','MANGLIK','RELATION');

$diplayMapArray=array("RELIGION"=>"Religion","MTONGUE"=>"Community","CASTE"=>"Caste","HEIGHT"=>"Height","EDU_LEVEL_NEW"=>"Education","OCCUPATION"=>"Occupation","INCOME"=>"Income","COUNTRY_RES"=>"Residence","MSTATUS"=>"Maritial-Status","HAVECHILD"=>"Have-Children","DIET"=>'Diet',"DRINK"=>"Drink","SMOKE"=>"Smoke","MANGLIK"=>"Manglik","RELATION"=>"Profile posted by");
$smarty->assign("diplayMapArray",$diplayMapArray);

/**
* Return Profileid & Original Search-Query on basis of searchid.
* @param int $sid unique searchid.
* @return array 
*/
/*
function searchqueryinfo($sid)
{
	global $db;

	$sql="SELECT PROFILEID,SQUERY FROM newjs.SEARCHQUERY_QUERY WHERE SEARCHID='$sid'";
	$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	$row=mysql_fetch_array($result);
	return $row;
}
*/
/**
* List the cluster that we need to be displayed which is based on the search of the user.
* @return array 
*/
/*
function getdisplayclusterarr($sid)
{
	
	$sql="SELECT CLUSTER_DISPLAY FROM newjs.SEARCHQUERY_QUERY WHERE SEARCHID='$sid'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");	
	$row=mysql_fetch_array($result);
	$displayCluster=$row["CLUSTER_DISPLAY"];
	//$displayCluster="CASTE,MTONGUE,OCCUPATION,EDU_LEVEL_NEW,RELIGION,INCOME,COUNTRY_RES,MSTATUS,HEIGHT,HAVECHILD,DIET,DRINK,SMOKE,MANGLIK,RELATION";
	$displayClusterArr=explode(",",$displayCluster);
	return $displayClusterArr;
}
*/
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
				//"COUNTRY_RES"=>"COUNTRY_RES#COUNTRY_RES#country_resArr"
				"COUNTRY_RES"=>"COUNTRY_RES#COUNTRY_DROP#country_resArr"
				);		
	return explode("#",$clusterTableInfo[$categoryLabel]);
}


/**
* Return the Ordering of cluster to be displayed.
* @param int $pid unique profileid of a user .
* @return array 
*/
function getClusterDisplayOrdering($pid='',$sid="")
{
	global $db,$defaultClusterOrderingArr;

	$displayClusterArr=getdisplayclusterarr($sid);//Cluster Need to be displayed

	$size=count($defaultClusterOrderingArr);

        if($pid)
        {
                $sql_lavesh="SELECT WEIGHT_ORDERING FROM twowaymatch.TRENDS WHERE PROFILEID=$pid";
                $result=mysql_query($sql_lavesh,$db) or die("$sql_lavesh".mysql_error($db));
                $row=mysql_fetch_row($result);
                $weight=$row["WEIGHT_ORDERING"];
        }
        if($weight)
        {
                $groupByFieldArr=explode(",",$weight);
                for($ll=0;$ll<$size;$ll++)
                {
                        $tempGroupBy=$defaultClusterOrderingArr[$ll];
                        if( !in_array($tempGroupBy,$groupByFieldArr) && in_array($tempGroupBy,$displayClusterArr))
                                $groupByFieldArr[]=$tempGroupBy;
                }
        }
        else
        {
                for($ll=0;$ll<$size;$ll++)
                {
                        $tempGroupBy=$defaultClusterOrderingArr[$ll];
                        if(in_array($tempGroupBy,$displayClusterArr))
                                $groupByFieldArr[]=$tempGroupBy;
                }
        }
	return $groupByFieldArr;
}

/**
* Return the Ordering of cluster to be displayed.(Only top 4 cluster will be displayed and more link if more than 4 cluster is available)
* @param array $groupByFieldArr Final Ordering Of cluster to be displayed.
* @param string $originalSqlQuery search-query of the user
* @return array $clusterArr  return clusters will values and no. of results. 
* example
[COUNTRY_RES] => Array
(
    [India] => Array
	(
	    [0] => 36985
	    [1] => 51
	)

    [NRI] => Array
	(
	    [0] => 2054
	    [1] => NRI
	)

)
[MSTATUS] => Array
(
    [Never Married] => Array
	(
	    [0] => 38521
	    [1] => N
	)

    [Married Earlier] => Array
	(
	    [0] => 518
	    [1] => E
	)

)
*/
function getClusterArray($groupByFieldArr,$originalSqlQuery)
{
	global $db;
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include("arrays.php");

	//Creating Array containing info about no. of results for each Search Paramter
	$displayCluster=implode(",",$groupByFieldArr);
	$sql_lavesh="SELECT SQL_NO_CACHE $displayCluster FROM ".$originalSqlQuery;
	$result=mysql_query($sql_lavesh,$db) or die("$sql_lavesh".mysql_error($db));
	while($row=mysql_fetch_assoc($result))
		foreach($row as $key => $value)
			$listCategory[$key][$value]++;
	//Creating Array containing info about no. of results for each Search Paramter


        //calculating sum as for we have only 2 options i.e. india-nri && married-nevermarried.
	if($listCategory['COUNTRY_RES'])
	        $nri_sum_india=array_sum($listCategory['COUNTRY_RES']);
	if($listCategory['MSTATUS'])
	        $married_sum_unmarried=array_sum($listCategory['MSTATUS']);
        //calculating sum as for we have only 2 options i.e. india-nri && married-nevermarried.


        //Calculating top 4 Search criteria subcategory i.e. CASTE top 4 can be :aggarwal(100),arora(80),garwali(70),brahmin(40).
	if(!$listCategory)
		return;
	
        foreach($listCategory as $key=>$value)
        {
                arsort($listCategory[$key]);
                $k=1;
                foreach($listCategory[$key] as $key1=>$value1)
                {
                        if($value1>0)
                        {
                                $hardcode='N';
                                if(in_array($key,array('CASTE','MTONGUE','EDUCATION_LEVEL_NEW','OCCUPATION','INCOME')))
                                        $dropdownArrayName=$key."_DROP";
                                elseif($key=='RELIGION')
                                        $dropdownArrayName=$key."S";
                                elseif($key=='HAVECHILD')
                                        $dropdownArrayName='CHILDREN';
                                elseif($key=='RELATION')
                                        $dropdownArrayName='RELATIONSHIP';
                                elseif($key=='EDU_LEVEL_NEW')
                                        $dropdownArrayName='EDUCATION_LEVEL_NEW_DROP';
                                elseif($key=='COUNTRY_RES')
                                {
                                        $hardcode='Y';
                                        if($key1==51)
                                        {
                                                $clusterArr[$key]['India'][0]=$value1;
                                                $clusterArr[$key]['India'][1]='51';
						$nriCnt=$nri_sum_india-$value1;
						if($nriCnt)
						{
	                                                $clusterArr[$key]['NRI'][0]=$nriCnt;
        	                                        $clusterArr[$key]['NRI'][1]='NRI';
						}
						$moreclusterArr[$key]=2;
                                        }
                                }
                                elseif($key=='MSTATUS')
                                {
                                        $hardcode='Y';
                                        if($key1=='N')
                                        {
                                                $clusterArr[$key]['Never Married'][0]=$value1;
                                                $clusterArr[$key]['Never Married'][1]='N';
						if($married_sum_unmarried-$value1>0)
						{
	                                                $clusterArr[$key]['Married Earlier'][0]=$married_sum_unmarried-$value1;
        	                                        $clusterArr[$key]['Married Earlier'][1]='E';
							$moreclusterArr[$key]=2;
						}
                                        }
                                }
                                else
                                        $dropdownArrayName=$key;

                                if($hardcode=='N')
                                {
                                        if($key1)
                                                $topFourCategory=${$dropdownArrayName}[$key1];
                                        else
                                                $topFourCategory='Not-Specified';
                                        $clusterArr[$key][$topFourCategory][0]=$value1;
                                        $clusterArr[$key][$topFourCategory][1]=$key1;
                                }
                        }
			if($k==4 && $moreclusterArr[$key]!=2)
				$moreclusterArr[$key]=1;
                        if($k++>=4)
                                break;
                }
        }

	//alternative
	global $smarty;
	$smarty->assign("moreclusterArr",$moreclusterArr);
	//alternative

	return $clusterArr;
}

function updateCluster($sid='',$religion='',$caste='',$mtongue='',$height='',$Edu_Level='',$Edu_Level_New='',$Occupation='',$Income='',$Country_Res='',$City_Res='',$Mstatus='',$HaveChild='',$Diet='',$Drink='',$Smoke='',$Manglik='',$Relation='',$NEW_SEARCH_CLUSTERING='',$bread_crumb='')
{
	//-------------------------------------------------UsedCluster -----------------------------------------------------------
	//-------------------------------------------------UsedCluster -----------------------------------------------------------
	/*
	if($NEW_SEARCH_CLUSTERING=='Y' && $sid)
	{
	        $sql_b="SELECT BREAD_CRUMB FROM SEARCHQUERY WHERE ID='$sid'";
        	$result_b=mysql_query($sql_b) or die("$sql".mysql_error($db));
	        $row_b=mysql_fetch_row($result_b);
        	$bread_crumb=$row_b[0];

	        //TEMP
        	$RELIGION1=array(1,9);
	        $arr=getSearchLabelInfo('RELIGION');
        	$dropdownArrayName=$arr[1];
	        foreach($RELIGION1 as $k=>$v)
        	{
                	$RELIGION[]=${$dropdownArrayName}[$v];
	        }
        	$MTONGUE=array(12,14,30);
	        $INCOME=array(3,4,5);
        	//TEMP

	        if($bread_crumb)
        	{
                	if(!strstr($bread_crumb,$currentCluster))
                        	$bread_crumb=$bread_crumb.",".$currentCluster;
	        }
        	else
                	$bread_crumb=$currentCluster;

	        if($bread_crumb)
        	{
                	$bread_crumb_arr=explode(",",$bread_crumb);
	        }
        	sort($bread_crumb_arr);

	        foreach($bread_crumb_arr as $key=>$value)
        	{
                	$usedCluster[$value][]=${$value};
	        }
	}
	*/
	//-------------------------------------------------UsedCluster -----------------------------------------------------------
	//-------------------------------------------------UsedCluster -----------------------------------------------------------

        //Religion
        if(!$religion && !$caste && !$mtongue)
                $clusterDisplayedArr[]="RELIGION";
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
	        $clusterDisplayedArr[]="COUNTRY_RES";
/*
echo "<br>";
echo "<br>";
print_r($Mstatus);
echo "<br>";
echo "<br>";
*/
	if(!$Mstatus)
	        $clusterDisplayedArr[]="MSTATUS";
	if(!$HaveChild && @count(array_intersect($Mstatus,array('A','O','D','W','S')))>0)
	        $clusterDisplayedArr[]="HAVECHILD";
	if(!$Diet)
	        $clusterDisplayedArr[]="DIET";
	if(!$Drink)	
	        $clusterDisplayedArr[]="DRINK";
	if(!$Smoke)
        	$clusterDisplayedArr[]="SMOKE";
	if(!$Manglik)
	        $clusterDisplayedArr[]="MANGLIK";
	if(!$Relation)
	        $clusterDisplayedArr[]="RELATION";

	if(is_array($clusterDisplayedArr))
		$clusterDisplayedStr=implode(",",$clusterDisplayedArr);
	return ($clusterDisplayedStr);
}

function search_display_format($str='')
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
	                return $arr; 
		}
        }
        return '';
}


?>
