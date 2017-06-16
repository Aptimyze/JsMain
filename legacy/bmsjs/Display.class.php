<?php

/** 
* Banner(s) Is served from this file
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

include_once("classes/Mysql.class.php");
include_once("classes/Banner.class.php");
include_once("classes/Zone.class.php");
include_once("classes/Memcache.class.php");
include_once("classes/UserSmarty.class.php");
include_once("display_include.php");

global $filewritestring;
global $mailer,$subzone,$hit , $flash99 , $searchcriteria,$showall;
global $isTextLink;

//Logos Zone where Banner String is used instead of Zone string
$_LogosZone = 190;

//listing live Banners of the current zone 
//All Operation are now limited to these banners only.
$bannerObj=new Banner ; 
$bannerObj->setActiveBannersStr($zonestr,$subzone,$showall);


//Getting required details of the the current zone.
//Criteria will be used for furthur filteration of banners.
$zoneObj=new zone($zonestr);
$zoneObj->setZoneDetails();//settting zone details 
$maxbans=$zoneObj->getZoneMaxBans();
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

if($bannerObj->getLiveBanners())
{
	$filterCriteria=filterCriterias($bannerObj->getLiveBanners(),$userData,$CriteriaInuse,$searchcriteria);
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
/*
echo "diplayed Banner is --->";
print_r($finlist);
*/
$string="zonedisp".$zonestr;
$returnzones[$string]=actual_display($finlist,$bannarr,$zonestr,$zoneObj);
//$smarty->assign($string,$returnzones[$string]);
$smartyObj=new UserSmarty;
$smartyObj->assignValue($string,$returnzones[$string]);
?>
