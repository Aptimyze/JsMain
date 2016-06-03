<?php
//echo("<img src=\"http://static.ieplads.com/bmsjs/gifs/zero.gif\">");
//die;

/** 
* Banner(s) Is served from this file
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/
include_once("/usr/local/scripts/bms_config.php");
include_once("classes/Mysql.class.php");
include_once("classes/Banner.class.php");
include_once("classes/Zone.class.php");
include_once("classes/UserSmarty.class.php");
include_once("display_include.php");
include_once("classes/Memcache.class.php");
global $filewritestring;
global $mailer,$subzone,$hit , $flash99 , $searchcriteria,$showall,$shikshaCriteria;
global $isTextLink;
global $xmlFormat;
global $type, $default;
$smartyObj=new UserSmarty;

/*
if($zonestr==4 || $zonestr==5 ||$zonestr==6)
	return;
*/
//Logos Zone where Banner String is used instead of Zone string
$_LogosZone = 190;

$criteriaRegex='/^(([a-zA-Z0-9]{0,4}(\,[a-zA-Z0-9]{1,4}){0,})\|){0,}([a-zA-Z0-9]{0,4}(\,[a-zA-Z0-9]{1,4}){0,}){0,1}$/';
if(($zonestr!="" && !is_numeric($zonestr)) || ($showall != "" && !is_numeric($showall)) || ($searchcriteria!="" && !preg_match($criteriaRegex,$searchcriteria)) || ($data != "" && !is_numeric($data)))
{
	$file = fopen($_LOGPATHS."/".$_TPLPATH."/logerror.txt","a");
        fwrite($file,"\n\nBMS validation error zonestr=".$zonestr." showall=".$showall." data=".$data." searchcriteria=".$searchcriteria);
        fclose($file);
        echo("<img src=\"http://static.ieplads.com/bmsjs/gifs/zero.gif\">");
        die;

}
//populating extraCondition for Active banner
$extraCondition = '';

//check the type for possible values of type
if($type){
$posible_Value_for_column = GetEnumColumnTypeValues('BANNER', 'BannerClass');
//if value passed for type is not from the possible values of column type, them make it blank.
if(!in_array($type,$posible_Value_for_column))
	$type = '';
else
	$extraCondition .= " BannerClass='$type'";
}

//check the default for possible values of default
if($default){
$posible_Value_for_default = GetEnumColumnTypeValues('BANNER', 'BannerDefault');
if(!in_array($default,$posible_Value_for_default))
	$default = '';
else
	$extraCondition .= " AND BannerFixed='$default'";
}

//listing live Banners of the current zone 
//All Operation are now limited to these banners only.
$bannerObj=new Banner ; 
$bannerObj->setActiveBannersStr($zonestr,$subzone,$showall,$extraCondition);
//Getting required details of the the current zone.
//Criteria will be used for furthur filteration of banners.
$zoneObj=new Zone($zonestr);
$zoneObj->setZoneDetails();//settting zone details 
$maxbans=$zoneObj->getZoneMaxBans();

if($zoneObj->getZoneOnRotation()=='Y')
	$zoneonRotation=1;

if($zoneObj->CriteriaInUse)
	$CriteriaInuse=explode(",",$zoneObj->CriteriaInUse);

//Getting user Info of the logged in user or searchcriteria
//Criteria will be used for furthur filteration of banners.
$memcacheObj=new UserMemcache;
if($data)
{
	$memcacheObj->userDetails($data);
	$userData=$memcacheObj->getuserData();
}
elseif($searchcriteria)
{
	$userData='';
}

$liveBanners = $bannerObj->getLiveBanners();
if($liveBanners)
{
        $filterCriteria=filterCriterias($liveBanners,$userData,$CriteriaInuse,$searchcriteria, $shikshaCriteria);
}
else
{
	exit;
	//exit("no live Banners present1");
}

if($filterCriteria)
	$filterCriteriaStr=implode(" AND  ",$filterCriteria);
else
{
	exit;
	//exit("no live Banners present2");
}


//Listing banners whose criteria(s) are fullfilled.
$bannerObj->setBannersOnBookingCriteria($filterCriteriaStr,$zonestr);
$banzonepriority=$bannerObj->getbanzonepriority();
$bannarr=$bannerObj->getbannarr();

// For selecting which banner is to be displayed in case more than one banner is running in a zone for same criteria based on the priority and weightage of the respective banners.
//criteria banners --> no-criteria banners -->default banner is the order of priority of banners
$mysqlObj=new Mysql;
$mysqlObj->connect();

for($j=1;$j<=$maxbans;$j++)
{
	if($banzonepriority[$zonestr][$j]["banners"])
	{
		$bannerarray=explode(",",$banzonepriority[$zonestr][$j]["banners"]);

		$tempCntNonDefault=$banzonepriority[$zonestr][$j]["notdefaultcount"];
		$tempCntDefault=$banzonepriority[$zonestr][$j]["defaultcount"];
		$tempCntFixed=$banzonepriority[$zonestr][$j]["fixedcount"];	

		for($k=0;$k<count($bannerarray);$k++)
		{	
			$banner=$bannerarray[$k];
			if($tempCntNonDefault>0)//criteria banners
			{
				if($bannarr[$banner]["BannerDefault"]!='Y' && $bannarr[$banner]["BannerFixed"]!='Y')
				{
					if($tempCntNonDefault==1)
						$diff[$zonestr][$j][$banner]=1;
					else
					{
						$banServed=$memcacheObj->getBannerServed($banner);
						$banWeight=$bannarr[$banner]["BannerWeightage"];
						$diff[$zonestr][$j][$banner]=$banWeight-$banServed/$banWeight;
						/*
						echo "<br>";
						echo $banServed.'--'.$banWeight.'--'.$banner;
						echo "+++++++++++".$diff[$zonestr][$j][$banner];
						*/
					}
				}
			}
			elseif($tempCntDefault>0)//no-criteria banners
			{
				if($bannarr[$banner]["BannerDefault"]=='Y')
				{
					if($tempCntDefault==1)
						$diff[$zonestr][$j][$banner]=1;
					else
					{
						$banServed=$memcacheObj->getBannerServed($banner);
						$banWeight=$bannarr[$banner]["BannerWeightage"];
						$diff[$zonestr][$j][$banner]=$banWeight-$banServed/$banWeight;
					}
				}
			}
			elseif($tempCntFixed>0)//default banner
			{
				if($bannarr[$banner]["BannerFixed"]=='Y')
				{
					if($tempCntFixed==1)
						$diff[$zonestr][$j][$banner]=1;
					else
					{
						$banServed=$memcacheObj->getBannerServed($banner);
						$banWeight=$bannarr[$banner]["BannerWeightage"];
						$diff[$zonestr][$j][$banner]=$banWeight-$banServed/$banWeight;
					}
				}
			}
		}
		$finbanner=array_search(max($diff[$zonestr][$j]),$diff[$zonestr][$j]);

                if($zoneonRotation)
                {
                        $temp_weight=$bannarr[$banner]["BannerWeightage"];
                        $testarr[$temp_weight][]=$finbanner;
                        if( !is_array($weightagearr) || !in_array($temp_weight,$weightagearr))
                                $weightagearr[]=$temp_weight;
                }
                else
		{
			if($finlist[$zonestr][$j])
			{
				$finlist[$zonestr][$j].=",".$finbanner;
			}
			else
			{
				$finlist[$zonestr][$j]=$finbanner;
			}
		}
	}
}

if($zoneonRotation)
{
        //print_r($testarr);
	if(is_array($weightagearr) && $weightagearr[0]!='')
	        rsort($weightagearr);
        //print_r($weightagearr);               
        $j=1;
        for($i=0;$i<count($weightagearr);$i++)
        {
                $ll=$weightagearr[$i];
                $maxbans=count($testarr[$ll]);

                $top_banner=rand(0,$maxbans-1);
                //echo "---".$top_banner;
                $loop=$maxbans+$top_banner;

                for($jj=$top_banner;$jj<$loop;$jj++)
                {
                        if($jj<$maxbans)
                                $k=$jj;
                        else
                                $k=$jj-$maxbans;
                        $finlist[$zonestr][$j]=$testarr[$ll][$k];
                        $j=$j+1;
                }
        }
}
/*
echo "diplayed Banner is --->";
print_r($finlist);
*/
$string="zonedisp".$zonestr;
if($tb==true)
    $returnzones[$string]=topbanner_display($finlist,$bannarr,$zonestr,$zoneObj);
else
    $returnzones[$string]=actual_display($finlist,$bannarr,$zonestr,$zoneObj);
//$smarty->assign($string,$returnzones[$string]);
$smartyObj->assignValue($string,$returnzones[$string]);
?>
