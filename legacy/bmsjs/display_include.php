<?php

/**
* Functions related to  banner display are here.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/


/**
* @param string $liveBanners comma seperated live/active banner string.
* @param array $userData user(jeevansathi) related info.
* @param array $CriteriaInuse banner of a given zone booked maximum on which all criteria eg.AGE,INCOME,GENDER,IP-ADDRESS....
* @param string $searchcriteria search criteria of user.Criteria are sepertaed by symbol '|'.
*/
function filterCriterias($liveBanners,$userData="",$CriteriaInuse="",$searchcriteria="",$shikshaCriteria="")
{
	if($liveBanners)
	$filterCriteria[]="B.BannerId in ($liveBanners)"; //added by Varun on 5th Feb 2009

	if($CriteriaInuse)
	{
		if(is_array($userData))
		{
			if(in_array("GENDER",$CriteriaInuse))
			$filterCriteria[]="(BannerGender='' OR  BannerGender='$userData[GENDER]')";

			if(in_array("LOCATION",$CriteriaInuse))
			{
				$tempCriteria="(BannerLocation='' OR ";

				if($userData["CITY_INDIA"])
				$tempCriteria.=" BannerInCity REGEXP ' $userData[CITY_INDIA] '";
				elseif($userData["CITY_USA"])
				$tempCriteria.=" BannerUsCity REGEXP ' $userData[CITY_USA] '";
				else
				{
					if($userData["COUNTRY_RES"])
					$tempCriteria.="BannerCountry REGEXP ' $userData[COUNTRY_RES] '";
					else
					$tempCriteria.="BannerInCity='' OR BannerUsCity=''";
				}
				$tempCriteria.=" )";
				$filterCriteria[]=$tempCriteria;
				unset($tempCriteria);
			}
			if(in_array("IP",$CriteriaInuse))
			{
				$tempCriteria=getIPLocation();
				$filterCriteria[]="(BannerIP='' OR BannerIP REGEXP ' $tempCriteria ')";
				unset($tempCriteria);
			}
			if(in_array("AGE",$CriteriaInuse))
			$filterCriteria[]="(( BannerAgeMin='' OR BannerAgeMin='-1') OR (BannerAgeMin <='$userData[AGE]' AND BannerAgeMax>='$userData[AGE]'))";
			if(in_array("INCOME",$CriteriaInuse))
			$filterCriteria[]="( BannerCTC='' OR BannerCTC REGEXP ' $userData[INCOME] ')";
			if(in_array("SUBSCRIPTION",$CriteriaInuse))
			{
				$tempCriteria=explode(",",$userData["SUBSCRIPTION"]);
				$size=count($tempCriteria);
 				$j=0;

                                foreach ($tempCriteria as $key => $value)
                                {
                                        $tempstr[]="(BannerMEM REGEXP ' $value ')";
                                        if($value=='F')
                                                $skip=1;
                                }

                                $temp_str=implode(" OR ",$tempstr);
                                if(!$skip)
                                        $filterCriteria[]="(BannerMEM='' OR BannerMEM REGEXP ' R ' OR ($temp_str))";
                                else
                                        $filterCriteria[]="(BannerMEM='' OR ($temp_str))";
			}

			if(in_array("MARITALSTATUS",$CriteriaInuse))
			$filterCriteria[]="(BannerMARITALSTATUS='' OR BannerMARITALSTATUS REGEXP ' $userData[MSTATUS] ')";
			if(in_array("EDUCATION",$CriteriaInuse))
			$filterCriteria[]="(BannerEDU='' OR BannerEDU REGEXP ' $userData[EDU_LEVEL] ')";
			if(in_array("OCCUPATION",$CriteriaInuse))
			$filterCriteria[]="(BannerOCC='' OR BannerOCC REGEXP ' $userData[OCCUPATION] ')";
			if(in_array("COMMUNITY",$CriteriaInuse))
			$filterCriteria[]="(BannerCOM='' OR BannerCOM REGEXP ' $userData[MTONGUE] ')";
			if(in_array("RELIGION",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerREL='' OR BannerREL REGEXP ' $userData[RELIGION] ')";
			}

			//added by lavesh rawat
			if(in_array("VARIABLE_DISCOUNT",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerJsVd='' OR  BannerJsVd REGEXP ' $userData[VARIABLE_DISCOUNT] ') ";
			}
			if(in_array("PROFILE_STATUS",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerJsProfileStatus='' OR   BannerJsProfileStatus  REGEXP ' $userData[PROFILE_STATUS] ')";
			}
			if(in_array("GMAIL_ID",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerJsMailID='' OR  BannerJsMailID REGEXP ' $userData[GMAIL_ID] ')";
			}
			if(in_array("EOI_STATUS",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerJsEoiStatus='' OR  BannerJsEoiStatus REGEXP ' $userData[EOI_STATUS] ')";
			}
                        if(in_array("REGISTRATION_STATUS",$CriteriaInuse))
                        {
                        	$filterCriteria[]="(BannerJsRegistrationStatus='' OR BannerJsRegistrationStatus REGEXP ' 1 ')";
                        }
                        if(in_array("FTO_STATE",$CriteriaInuse))
                        {
                        	$filterCriteria[]="(BannerJsFtoStatus='' OR BannerJsFtoStatus REGEXP ' $userData[FTO_STATE] ')";
                        }
                        if(in_array("FTO_EXPIRY",$CriteriaInuse))
                        {
                        	$filterCriteria[]="(BannerJsFtoExpiry='' OR BannerJsFtoExpiry REGEXP ' $userData[FTO_EXPIRY] ')";
                        }
                        if(in_array("PROFILE_COMPLETE_STATE",$CriteriaInuse) && $userData["PROFILE_COMPLETE_STATE"]!='')
                        {
                        	$filterCriteria[]="(BannerJsProfileCompletionState='' OR BannerJsProfileCompletionState REGEXP ' $userData[PROFILE_COMPLETE_STATE] ')";
                        }
			//added by lavesh rawat
		}
		else
		{

			if($searchcriteria!='')
			{
				$searchstr = explode("|",$searchcriteria);

				if(in_array("PROPCAT",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[1] != '' )
					{
						$tempPropcat = $searchstr[1];
						if($tempPropcat=='S')
						$filterCriteria[]="(BannerPROPCAT=' ' OR BannerPROPCAT REGEXP 'Buy')";
						elseif($tempPropcat=='R')
						$filterCriteria[]="(BannerPROPCAT=' ' OR BannerPROPCAT REGEXP 'Rent')";
					}
					else
					$filterCriteria[]="(BannerPROPCAT=' ')";
				}

				if(in_array("PROPCITY",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[2] != '' )
					$filterCriteria[]="(BannerPROPCITY=' ' OR BannerPROPCITY REGEXP ' $searchstr[2] ')";
					else
					$filterCriteria[]="(BannerPROPCITY=' ')";
				}

				if(in_array("PROPINR",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[3] != '' )
					$filterCriteria[]="(BannerPROPINR=' ' OR BannerPROPINR REGEXP ' $searchstr[3] ')";
					else
					$filterCriteria[]="(BannerPROPINR=' ')";
				}

                                if(in_array("PROPTYPE",$CriteriaInuse))
                                {
                                        $acres99=1;
                                        if ($searchstr[4] != '' )
                                        {
                                                $bPropType = $searchstr[4];
                                                $bPropTypeArr = explode(",",$bPropType);
                                                foreach($bPropTypeArr as $k=>$v)
                                                {
                                                        $bPropTypeArr2[] = "BannerPROPTYPE REGEXP ' $v '";
                                                }
                                                $bPropTypeArr2Str = implode(" OR ",$bPropTypeArr2);
                                                $filterCriteria[]="(BannerPROPTYPE=' ' OR $bPropTypeArr2Str)";

                                        }
                                        else
                                                $filterCriteria[]="(BannerPROPTYPE=' ')";
                                }

				if ($searchstr[5] != '' )//jeevansathi
				{
					if(in_array("GENDER",$CriteriaInuse))
					{
						if($searchstr[5] == 'F')
						$filterCriteria[]="(BannerGender=' ' OR  BannerGender='M')";
						elseif($searchstr[5] == 'M')
						$filterCriteria[]="(BannerGender=' ' OR  BannerGender='F')";
					}
				}
				elseif ($searchstr[7] != '' )//jeevansathi
				{}
				else
				{
					if(in_array("GENDER",$CriteriaInuse))
					$filterCriteria[]="(BannerGender='')";
				}
			}

			if(!$acres99)
			{
				//logout case.
				if(in_array("LOCATION",$CriteriaInuse))
				$filterCriteria[]="(BannerLocation='')";
				if(in_array("IP",$CriteriaInuse))
				{
					$tempCriteria=getIPLocation();
					$filterCriteria[]="(BannerIP='' OR BannerIP REGEXP ' $tempCriteria ')";
					unset($tempCriteria);
				}
				if(in_array("AGE",$CriteriaInuse))
				$filterCriteria[]="(BannerAgeMin='' OR BannerAgeMin='-1')";
				if(in_array("INCOME",$CriteriaInuse))
				$filterCriteria[]="(BannerCTC='')";
				if(in_array("SUBSCRIPTION",$CriteriaInuse))
				$filterCriteria[]="(BannerMEM='')";
				if(in_array("MARITALSTATUS",$CriteriaInuse))
				$filterCriteria[]="(BannerMARITALSTATUS='')";
				if(in_array("EDUCATION",$CriteriaInuse))
				$filterCriteria[]="(BannerEDU='')";
				if(in_array("OCCUPATION",$CriteriaInuse))
				$filterCriteria[]="(BannerOCC='')";
				if(in_array("COMMUNITY",$CriteriaInuse))
				$filterCriteria[]="(BannerCOM='')";
				if(in_array("RELIGION",$CriteriaInuse))
				$filterCriteria[]="(BannerREL='')";
				if(in_array("VARIABLE_DISCOUNT",$CriteriaInuse))
					$filterCriteria[]="(BannerJsVd='')";
				if(in_array("PROFILE_STATUS",$CriteriaInuse))
					$filterCriteria[]="(BannerJsProfileStatus='')";
				if(in_array("GMAIL_ID",$CriteriaInuse))
					$filterCriteria[]="(BannerJsMailID='')";
				if(in_array("EOI_STATUS",$CriteriaInuse))
					$filterCriteria[]="(BannerJsEoiStatus='')";
                        	if(in_array("REGISTRATION_STATUS",$CriteriaInuse))
				{
					global $_COOKIE;
					if($_COOKIE["ISEARCH"])
	                        		$filterCriteria[]="(BannerJsRegistrationStatus='' OR BannerJsRegistrationStatus REGEXP ' 1 ')";
					else
	                        		$filterCriteria[]="(BannerJsRegistrationStatus='' OR BannerJsRegistrationStatus REGEXP ' 2 ')";
				}
				if(in_array("FTO_STATE",$CriteriaInuse))
					$filterCriteria[]="(BannerJsFtoStatus='')";
				if(in_array("FTO_EXPIRY",$CriteriaInuse))
					$filterCriteria[]="(BannerJsFtoExpiry='')";
                        	if(in_array("PROFILE_COMPLETE_STATE",$CriteriaInuse))
                        		$filterCriteria[]="(BannerJsProfileCompletionState='')";
				//logout case.
			}
		}
	}
	if($shikshaCriteria != '')
	{
		$shikshaCriteriaArr = explode('|', $shikshaCriteria);
		$category = $shikshaCriteriaArr[0];
		$country = $shikshaCriteriaArr[1];
		$city = $shikshaCriteriaArr[2];
		$keyword = $shikshaCriteriaArr[3];
		if($category != '' )
		$filterCriteria[] = "(shikshaCategory='' OR shikshaCategory like '% $category %')";
		else
		$filterCriteria[] = "(shikshaCategory='')";
		if($country != '' )
		$filterCriteria[] = "(shikshaCountry='' OR shikshaCountry like '% $country %')";
		else
		$filterCriteria[] = "(shikshaCountry='')";

		if($city != '' )
		$filterCriteria[] = "(shikshaCity='' OR shikshaCity like '% $city %')";
		else
		$filterCriteria[] = "(shikshaCity='')";
		if($keyword != '' ) {
			$keywordsList = preg_split(",", str_replace(" ",",",$keyword));
			array_walk($keywordsList, 'cookShikshaKeywords');
			$keywordCriteria = implode(' OR ', $keywordsList);
			$filterCriteria[] = "(shikshaKeyword='' OR ". $keywordCriteria .")";
		}
		else
		$filterCriteria[] = "(shikshaKeyword='')";
	}
	return($filterCriteria);
}
function cookShikshaKeywords(&$value, &$key){
	if($value !== ''){
		$value =   "shikshaKeyword like '%".str_replace("\.","",str_replace("-"," ",$value))."%'";
	} else {
		$value = 'shikshaKeyword = ""';
	}
}
/**
* Give the location-id corresponding to user ip-address.
* Usefull if a banner is booked on ip-address.
*/
function getIPLocation()
{
	$mysqlObj=new Mysql;
	$mysqlObj->connect();

	//$ip = FetchClientIP(); // gives the IP Address of the machine from which the user is accessing the site
	$ip=getRemoteIP();
	$iparr = explode(".",$ip);

	$ipnum = (256*256*256)*$iparr[0] + (256*256)*$iparr[1] + 256*$iparr[2] + $iparr[3];
	$sql = "Select endIpNUM from GeoIP.Blocks  where endIpNum >= '$ipnum' ORDER BY endIpNUM LIMIT 1 ";
	if($result = $mysqlObj->Query($sql))
	{
		if ($myrow=$mysqlObj->fetchArray($result))
		{
			$endipnum = $myrow["endIpNUM"];
			$sql1 = "Select locid from GeoIP.Blocks where endIpNUM = '$endipnum' AND startIpNum <= '$ipnum'";
			if($result1=$mysqlObj->Query($sql1))
			{
				if ($myrow1 = $mysqlObj->fetchArray($result1))
				{
					$locid = $myrow1["locid"];
					return $locid ;
				}
			}
		}
	}
}


/**
* Function that finally displays the banners
* @param int-array $finlist list of banners to be dispalayed. 
* @param int-array $bannarr all info of all live banners of a current zone.
* @param int $zone zoneid
* @param ref-obj $zoneObj object refering to zone class.It is passed as value set by zone class earlier need to be used and creating object agaian will distroy all set value.
  @return string $returnzones Returns the string for display in a zone
*/
function actual_display($finlist,$bannarr,$zone,$zoneObj)
{
	global $_LogosZone,$_HITSFILE,$smarty,$othersrcp , $_SERVER , $_SITEURL,$data;
	global $mailer , $hit , $dbbms , $flash99 , $isTextLink , $searchid;
	global $showall, $searchcriteria;
	global $smartyObj;
	global $JavaScriptFormat, $json,$Spotlight;

	$mysqlObj=new Mysql;
	$mysqlObj->connect();

	$maxbans = $zonearr[$zone]["maxbans"];
	$maxbans=$zoneObj->getZoneMaxBans();
	$align = $zoneObj->getZoneAlignment();
	$width = $zoneObj->getZoneBanWidth();
	$height = $zoneObj->getZoneBanHeight();
	$ispopup = $zoneObj->getZonePopup();
	$spacing=$zoneObj->getZoneSpacing();

	if(!$spacing)
	$spacing=2;
	//New
	global $xmlFormat;

	/*
	if($xmlFormat)
	{
		//header("Content-type: application/xhtml+xml");
		$arr_search=array('&','<','>',"'",'"');
		$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
		$Ret='';
		$Ret='<?xml version="1.0" encoding="ISO-8859-1"?>';
		$Ret.="\n<urls>\n";
		for($i=1;$i<=$maxbans;$i++)
		{
			$banner=$finlist[$zone][$i];
			logimpression($banner);

			if($xmlFormat)
			{
				$gif = $bannarr[$banner]["BannerGif"];
				$url = $bannarr[$banner]["BannerUrl"];
				$j=$i;
				$gifs=str_replace($arr_search,$arr_repl,$gif);
				$urls=str_replace($arr_search,$arr_repl,$url);
				$Ret.="<bannerURL$j>$gifs</bannerURL$j>\n";
				$Ret.="<landingURL$j>$urls</landingURL$j>\n";
			}
		}
		$Ret.="</urls>";
		echo $Ret;
		exit;
	}
	*/

	//code added by Anuraag for mantis 4447 
	if($json) {
		$jsonCode = "var bnr=[];";
		$banners_in_zone=count($finlist[$zone]);
		for($i=1;$i<= $banners_in_zone;$i++)
		{
			$banner=$finlist[$zone][$i];
			logimpression($banner);
			//imageurl
			$gif = $bannarr[$banner]["BannerGif"];
			//if we are getting url with zero.gif then we need to ignore this - as this gives us a blank banner
			if(strstr($gif,"zero.gif"))
			continue;

                        //Changing Url to Banner hits file
                        //$url = $bannarr[$banner]["BannerUrl"];
                        $url="$_HITSFILE?banner=$banner";

			$jsonCode.="bnr.push({img:'$gif', url:'$url'});";
		}
		echo $jsonCode;
		exit;
	}

	//code added by Varun for mantis 3875
	//code added to return all the information regarding property gallery banners in Javascript format
	if($JavaScriptFormat)
	{
		//$arr_search=array('&','<','>',"'",'"');
		//$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');

		$banners_in_zone=count($finlist[$zone]);
		//$java_script_code.=" MultiArray = new Array($banners_in_zone);";
		$java_script_code.=" PG_BMS_Array =[";
		//$count_banner=0;
                $jj=1;
		$i=1;
		foreach($finlist[$zone] as $fzi=>$fzv)
                {
                        if($banners_in_zone<$jj)
                                break;
                        $jj++;
			$banner=$fzv;
			logimpression($banner);
			//imageurl
			$gif = $bannarr[$banner]["BannerGif"];

			//if we are getting url with zero.gif then we need to ignore this - as this gives us a blank banner
			if(strstr($gif,"zero.gif"))
			continue;

                        if($i!=1)
                        {
                                $java_script_code.=" ,";
                        }

			// $url = $bannarr[$banner]["BannerUrl"];
			//pgid
			$pg_id= $bannarr[$banner]["PG_ID"];

			if(!$pg_id)
			$from_src='PGLINK';
			else
			$from_src=$pg_id;

			//profileid
			$profileid= $bannarr[$banner]["PROFILEID"];

			$url="$_HITSFILE?banner=$banner";
			$java_script_code.="{";
			$java_script_code.=" imageurl : '$gif' ,"; //image url
			$java_script_code.=" landingurl : '$url&from_src=$from_src', "; //source url
			$java_script_code.=" pgid : '$pg_id-$banner-$profileid'"; //source url
			$java_script_code.=" }";

			//if($i!=1)
			//{
			//$java_script_code.=" ,";
			//}
			$i++;
		}

		$java_script_code.="];";
		//echo "</script>";

		if($banners_in_zone)
		{
			//$java_script_code.= "<script>";
			$java_script_code.= "propgallery.build();";
			//$java_script_code.= "</script>";
		}

		echo $java_script_code;
		exit;
	}
	if($bannarr[$banner]["PG_ID"])
	{
		$bannarr[$banner]["BannerUrl"]=$bannarr[$banner]["BannerUrl"]."?from_src=".$bannarr[$banner]["PG_ID"];
	}
        elseif($Spotlight) // Case for The New Spot Light Section
        {
                //echo "INSIDE SPOT LIGHT SECTION";
                $banners_in_zone=count($finlist[$zone]);
                
                if($Spotlight==1)
                $java_script_code.=" SL_BMS_Array1 =[";
                else if($Spotlight==2)
                $java_script_code.=" SL_BMS_Array2 =[";
                
                for($i=1;$i<= $banners_in_zone;$i++)
                {
                        $banner=$finlist[$zone][$i];
                        logimpression($banner);
                        //imageurl
                        $gif = $bannarr[$banner]["BannerGif"];

                        //if we are getting url with zero.gif then we need to ignore this - as this gives us a blank banner
                        if(strstr($gif,"zero.gif"))
                        continue;
                        
                        if($i!=1)
                        {
                                $java_script_code.=" ,";
                        }
                        $url="$_HITSFILE?banner=$banner";
                        $java_script_code.="{";
                        $java_script_code.=" imageurl : '$gif' ,"; //image url
                        $java_script_code.=" landingurl : '$url' "; //source url
                        $java_script_code.=" }";
                }
                $java_script_code.="];";

                if($banners_in_zone)
                {
                        if($Spotlight==1)
                        $java_script_code.= "sl.build1();";
                        elseif($Spotlight==2)
                        $java_script_code.= "sl.build2();";
                }
                echo $java_script_code;
                exit;
        }


	//Ends Here
	if($zone!=$_LogosZone)
	{
		if($ispopup!='Y')
		{
			if ($showall == 1 && !$mailer)
			{
				//if(count($finlist)==1)
				if(count($finlist[$zone])==1)
				{
					$tempCurrentbanner=$finlist[$zone][1];
					$tempCurrentbannerClass=$bannarr[$tempCurrentbanner]['BannerClass'];

					if(in_array($tempCurrentbannerClass,array('html','html','swf')))
					$onlyHtmlBannerInShowAll=1;
					/*
					foreach($finlist[$zone] as $k=>$v)
					{
					$tmp[$k]=$k;
					}
					if(count($tmp)<1)
					{
					$tempCurrentbanner=$finlist[$zone][1];
					$tempCurrentbannerClass=$bannarr[$tempCurrentbanner]['BannerClass'];

					if(in_array($tempCurrentbannerClass,array('html','html','swf')))
					$onlyHtmlBannerInShowAll=1;
					}
					*/
				}
				if(!$onlyHtmlBannerInShowAll)
				{
					echo ("<HTML><HEAD></HEAD>");
					echo ("<BODY style=\"margin:0px\">");
					echo ("<Table border=0 cellpadding=0 cellspacing=0 align=center style=\"border:0px\">");
					if(trim($zoneObj->getZoneHeader()) != '') {
						echo "<TH>";
						echo $zoneObj->getZoneheader();
						echo "</TH>";
					}
					if($align=='H')
					{
						echo ("<TR>");
					}
				}
			}

			for($i=1;$i<=$maxbans;$i++)
			{
				$banner=$finlist[$zone][$i];
				if($banner)
				{
					$isstat = $bannarr[$banner]["BannerStatic"];
					$gif = $bannarr[$banner]["BannerGif"];
					$class = $bannarr[$banner]["BannerClass"];
/*
					if($bannarr[$banner]["PG_ID"])
					{
						$banner=$banner."&from_src=".$bannarr[$banner]["PG_ID"];
					}
*/
					
					$url = $bannarr[$banner]["BannerUrl"];

					if($class == 'textlink')
					{
						logimpression($banner);
						$captureclick 	= $_HITSFILE."?banner=".$banner;
						servetextlink($captureclick,$gif);
					}
					elseif($class=='flv' || $class=='wmv')
					{
						if($align=='H' && $showall == 1)
						echo("<TD width=\"1\">");
						elseif($align=='V' && $showall == 1)
						echo("<tr><TD>");

						logimpression($banner);

						if($class=='wmv')
						{
							echo("<OBJECT ID=\"MediaPlayer\" WIDTH=$width height=$height CLASSID=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" STANDBY=\"Loading Video...\" TYPE=\"application/x-oleobject\"><PARAM NAME=\"FileName\" VALUE=\"".JsConstants::$bmsVideoUrl."/99acres/$gif\"><param name=\"DisplaySize\" value=\"false\"><PARAM name=\"ShowControls\" VALUE=\"false\"><param name=\"ShowStatusBar\" value=\"true\"><PARAM name=\"ShowDisplay\" VALUE=\"false\"><PARAM name=\"AutoRewind\" VALUE=\"True\"><PARAM name=\"autostart\" VALUE=\"true\"><EMBED TYPE=\"application/x-mplayer2\" SRC=\"".JsConstants::$bmsVideoUrl."/99acres/$gif\" NAME=\"MediaPlayer\" WIDTH=$width height=$height ShowControls=\"0\" ShowStatusBar=\"1\" ShowDisplay=\"0\" autostart=\"1\" loop=\"true\" DisplaySize=\"0\" pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/\"> </EMBED></OBJECT>");
						}
						else
						{
							echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height><param name=\"movie\" value=\"".JsConstants::$bmsUrl."/bmsjs/flv_videoserve.swf?_vidName=$gif&_headVid=$url\" /><param name=\"quality\" value=\"high\" /><embed src=\"".JsConstants::$bmsUrl."/bmsjs/flv_videoserve.swf?_vidName=$gif&_headVid=$url\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=$width height=$height></embed></object>");
						}

						if ($showall == 1 && $align=='H')
						{
							echo ("</TD><TD>&nbsp;</TD>");
						}
						if ($showall == 1 && $align=='V')
						echo ("</TD></TR><TR><TD height=1></TD></TR>");
					}
					elseif($class!= 'textlink')
					{
						if($align=='H') // in case of horizontal alignment of the banners
						{
							if ($mailer == 1 || $flash99 == 1) // for banner display in matchalert
							{
								if(!$hit)
								{
									logimpression($banner);
									if(strstr(substr($gif,-3),"BannerGif"))
									@header("Content-Type:image/gif");
									if (!is_numeric($gif))
									readfile("$gif");
								}
								else
								{
									$dt=Date('Y-m-d');

									$sql="Update bms2.BANNERMIS set Clicks=Clicks+1 where BannerId='$banner' and Date='$dt'";
									$result = $mysqlObj->Query($sql);

									if($mysqlObj->affectedRows()==0)
									{
										$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
										$result = $mysqlObj->Query($sql);
									}

									if($url)
									{
										if($othersrcp && trim($othersrcp)!='')
										$url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
										if($url)
										header("Location: $url");
									}
								}
							}
							else
							{
								logimpression($banner);
								if ($showall == 1 && !$onlyHtmlBannerInShowAll)
								{
									echo("<TD width=\"1\">");
								}
								if($isstat != 'Y' && !$onlyHtmlBannerInShowAll)	// if the banner is clickable
								echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");

								if($class =='html' || strstr(substr($gif,-3),"htm") || strstr(substr($gif,-4),"html")) // if banner is an html file
								{
									if($zone=='7' || $zone=='9' || $zone=='10' || $zone=='4' || $zone=='6')
									{
										$gen=explode('|',$searchcriteria);
										if(strstr($gif,'banner/google/') && $gen[7]!='')
										{
											$file_arr=explode('banner/google/',$gif);
											$file_name=end($file_arr);
											if($gen[7]=='M')
											{
												$str1="Groom_";
												$str3="_targeting_females";
											}
											elseif($gen[7]=='F')
											{
												$str1="Bride_";
												$str3="_targeting_males";
											}
											$subzone = $bannarr[$banner]["BannerPriority"];
											if($zone==4 || $zone==6)
											{
												$str2="search_page";
												if($zone=='4' && $subzone=='1')
												$str3.="_top_right";
												elseif($subzone=='1')
												$str3.="_Bottom_2";
												else
												$str3.="_Bottom_1";
											}
											elseif($zone==7)
											{
												$str2="profile_page";
												$str3.="_top_right";
											}
											elseif($zone==9)
											{
												$str2="profile_page";
												$str3.="_Bottom";
											}
											elseif($zone=='10')
											{
												$str2="profile_page";
												$str3.="_Middle_right";
											}

											$str=$str1.$str2.$str3;
											$smartyObj->assignValue("channel",$str);
											$smartyObj->displayTemplate("../../banner/google_search_profile/$file_name");
											exit();
										}
									}
                                    if($class=='Flash-shoshkelle')
                                        echo ("<a href='$_HITSFILE?banner=$banner' target='_blank'>");
									readfile("$gif");
                                    if($class=='Flash-shoshkelle')
                                        echo ("</a>");
									if ($showall == 1 && !$onlyHtmlBannerInShowAll)
									{
										echo ("</TD><TD>&nbsp;</TD>");
									}
								}
								elseif( (strstr(substr($gif,-3),"swf")) || strstr($gif,".swf?")) // if the banner is a flash file
								{
									// in case of slug and banner
									if($class=='Flash-shoshkelle-slug' && !$onlyHtmlBannerInShowAll)
									{
										//Code added by Varun on 3 January 2008 to include code for Slug Image banner
										//exploding the flash name by dot and removing the swf ext.
										$exp_gif=explode(".swf",$gif);
										//appending the .jpg ext at the end which points to the slug image banner
										$slug_gif=$exp_gif[0].".gif";
										echo ("var BannerString = \"<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0' id='skb' height='$height' width='$width'><param value='always' name='allowScriptAccess'/><param name='movie' value='$gif?clickTAG=$_HITSFILE?banner=$banner'/><param value='transparent' name='wmode'/><param name='quality' value='high'><embed name='skb' type='application/x-shockwave-flash' allowscriptaccess='always' src='$gif?clickTAG=$_HITSFILE?banner=$banner' wmode='transparent' height='$height' width='$width'></embed></object>\";var SlugBannerString=\"$slug_gif\";");
										if($gif)
										{
											//echo "<script language='JavaScript' type='text/JavaScript'>";
											echo "
											if(showShoskele) { showShoskele(BannerString,SlugBannerString); }
											else {
												if(BannerString){
												document.getElementById('shoskeleBanner').innerHTML = BannerString;
												document.getElementById('shoskeleBanner').style.visibility = 'visible';
												}
												if(SlugBannerString){
												document.getElementById('slug_image').src = SlugBannerString;
												document.getElementById('slug_image').style.visibility = 'visible';
												document.getElementById('slug_div').style.display = 'block';
												}
												
											}
											document.getElementById('slug_area').href='$_HITSFILE?banner=$banner';
											document.getElementById('slug_area').target='_blank';
											setTimeout(\"showHideLayers('shoskeleBanner','','hide')\",10000);
											";
											//echo "</script>";
										}
									}
									//in case of only banner and no slug
									elseif($class=='Flash-shoshkelle' && !$onlyHtmlBannerInShowAll)
									{
                                                                                echo ("BannerString = \"<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0' height='$height' width='$width'><param value='always' name='allowScriptAccess'/><param name='movie' value='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner'/><param value='transparent' name='wmode'/><param name='quality' value='high'><embed type='application/x-shockwave-flash' allowscriptaccess='always' src='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner' wmode='transparent' height='$height' width='$width'></embed></object>\";var d=document;d.find=d.getElementById;d.find('shoskeleBanner').innerHTML = BannerString;d.find('shoskeleBanner').style.display = 'block';");
									}     
									//Extra Parameter will be send with flas file to record hits.
									elseif($url)
									echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" /><param name=\"quality\" value=\"high\" /><param name=\"wmode\" value=\"transparent\" /><embed src=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" width=$width height=$height wmode=\"transparent\"></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									else
									echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif\" /><param name=\"quality\" value=\"high\" /><param name=\"wmode\" value=\"transparent\" /><embed src=\"$gif\" width=$width height=$height wmode=\"transparent\"></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									//ends

									if ($showall == 1 && !$onlyHtmlBannerInShowAll)
									{
										echo("</TD><TD>&nbsp;</TD>");
									}
								}
								elseif(strstr(substr($gif,-3),"php")) // if banner is a php file
								{
									echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$gif?profileid=$data&searchid=$searchid\"></body></html>";
								}
								else
								{
									if($gif==JsConstants::$bmsStaticUrl."/bmsjs/gifs/zero.gif" && $showall==1)
									echo("<img src=\"$gif\" border=0\" vspace=\"0\"></a>");
									else
									echo("<img src=\"$gif\" border=0\" hspace=\"$spacing\"></a>");
									if ($showall == 1)
									echo("</TD><TD>&nbsp;</TD>");
								}
							}
						}
						elseif($align=='V') // in case of vertical alignment of banners
						{
							if ($mailer == 1 || $flash99 == 1) // for banner display in matchalert
							{
								if(!$hit)
								{
									logimpression($banner);
									if(strstr(substr($gif,-3),"BannerGif"))
									@header("Content-Type:image/gif");
									elseif( (strstr(substr($gif,-3),"swf")) || strstr($gif,".swf?")) // if the banner is a flash file
									@header("Content-Type:application/x-shockwave-flash");

									if (!is_numeric($gif))
									readfile("$gif");
								}
								else
								{
									$dt=Date('Y-m-d');

									$sql="Update bms2.BANNERMIS set Clicks=Clicks+1 where BannerId='$banner' and Date='$dt'";
									$result = $mysqlObj->Query($sql);

									if($mysqlObj->affectedRows()==0)
									{
										$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
										$result = $mysqlObj->Query($sql);
									}

									if($url)
									{
										if($othersrcp && trim($othersrcp)!='')
										$url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
										if($url)
										echo "</table></html><html><META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=$url\"></html>";
									}

								}
							}
							else
							{
								logimpression($banner);
								if ($showall == 1 && !$onlyHtmlBannerInShowAll)
								echo("<tr><TD>");
								if($isstat != 'Y' && !$onlyHtmlBannerInShowAll)	// if the banner is clickable
								echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");
								if($class =='html' || strstr(substr($gif,-3),"htm") || strstr(substr($gif,-4),"html"))  // if the banner is a html file
								{
									if($zone=='7' || $zone=='9' || $zone=='10' || $zone=='4' || $zone=='6')
									{
										$gen=explode('|',$searchcriteria);
										if(strstr($gif,'banner/google/') && $gen[7]!='')
										{
											$file_arr=explode('banner/google/',$gif);
											$file_name=end($file_arr);
											if($gen[7]=='M')
											{
												$str1="Groom_";
												$str3="_targeting_females";
											}
											elseif($gen[7]=='F')
											{
												$str1="Bride_";
												$str3="_targeting_males";
											}
											$subzone = $bannarr[$banner]["BannerPriority"];
											if($zone==4 || $zone==6)
											{
												$str2="search_page";
												if($zone=='4' && $subzone=='1')
												$str3.="_top_right";
												elseif($subzone=='1')
												$str3.="_Bottom_2";
												else
												$str3.="_Bottom_1";
											}
											elseif($zone==7)
											{
												$str2="profile_page";
												$str3.="_top_right";
											}
											elseif($zone==9)
											{
												$str2="profile_page";
												$str3.="_Bottom";
											}
											elseif($zone=='10')
											{
												$str2="profile_page";
												$str3.="_Middle_right";
											}

											$str=$str1.$str2.$str3;
											$smartyObj->assignValue("channel",$str);
											$smartyObj->displayTemplate("../../banner/google_search_profile/$file_name");
											exit();
										}
									}
									readfile($gif);
									if ($showall == 1 && !$onlyHtmlBannerInShowAll)
									echo ("</TD></TR><TR><TD height=1></TD></TR>");
								}
								elseif( (strstr(substr($gif,-3),"swf")) || strstr($gif,".swf?")) // if the banner is a flash file
								{
									// case for banner and slug
									if($class=='Flash-shoshkelle-slug')
									{
										//Code added by Varun on 3 January 2008 to include code for Slug Image banner
										//exploding the flash name by dot and removing the swf ext.
										$exp_gif=explode(".swf",$gif);
										//appending the .jpg ext at the end which points to the slug image banner
										$slug_gif=$exp_gif[0].".gif";
										echo ("var BannerString = \"<script language='JavaScript' type='text/JavaScript'>function findObj(n, d) {var p,i,x; if(!d) d=document; if((p=n.indexOf('?'))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);if(!x && d.getElementById) x=d.getElementById(n); return x;}function showHideLayers() { var i,p,v,obj,args=showHideLayers.arguments;for (i=0; i<(args.length-2); i+=3) if ((obj=findObj(args[i]))!=null) { v=args[i+2];if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }obj.visibility=v; }}</script><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0' id='skb' height='$height' width='$width'><param name='movie' value='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner'/><param name='quality' value='high'><embed name='skb' type='application/x-shockwave-flash' src='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner' height='$height' width='$width'></embed></object>\"; var SlugBannerString=\"$slug_gif\";");
										if($gif)
										{
											//echo "<script language='JavaScript' type='text/JavaScript'>";
											echo "
											if(showShoskele) { showShoskele(BannerString,SlugBannerString); }
											else {
												if(BannerString){
												document.getElementById('shoskeleBanner').innerHTML = BannerString;
												document.getElementById('shoskeleBanner').style.visibility = 'visible';
												}
												if(SlugBannerString){
												document.getElementById('slug_image').src = SlugBannerString;
												document.getElementById('slug_image').style.visibility = 'visible';
												document.getElementById('slug_div').style.display = 'block';
												}	
											}
											document.getElementById('slug_area').href='$_HITSFILE?banner=$banner';
											document.getElementById('slug_area').target='_blank';
											setTimeout(\"showHideLayers('shoskeleBanner','','hide')\",10000);
											";
											//echo "</script>";
										}

									}
									 // case for only banner and no slug
                                                                        elseif($class=='Flash-shoshkelle')
                                                                        {
                                                                                echo ("BannerString = \"<script language='JavaScript' type='text/JavaScript'>function findObj(n, d) {var p,i,x; if(!d) d=document; if((p=n.indexOf('?'))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);if(!x && d.getElementById) x=d.getElementById(n); return x;}function showHideLayers() { var i,p,v,obj,args=showHideLayers.arguments;for (i=0; i<(args.length-2); i+=3) if ((obj=findObj(args[i]))!=null) { v=args[i+2];if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }obj.visibility=v; }}</script><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0' height='$height' width='$width'><param name='movie' value='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner'/><param name='quality' value='high'><embed type='application/x-shockwave-flash' src='$gif?clickTAG=".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?banner=$banner' height='$height' width='$width'></embed></object>\"; var d=document;d.find=d.getElementById;d.find('shoskeleBanner').innerHTML = BannerString;d.find('shoskeleBanner').style.display = 'block';");						
                                                                        }
									elseif($url)
									echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" /><param name=\"quality\" value=\"high\" /><param name=\"wmode\" value=\"transparent\" /><embed src=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" width=$width height=$height wmode=\"transparent\"></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");

									else
									echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif\" /><param name=\"quality\" value=\"high\" /><param name=\"wmode\" value=\"transparent\" /><embed src=\"$gif\" width=$width height=$height wmode=\"transparent\"></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									//ends here

									if ($showall == 1 && !$onlyHtmlBannerInShowAll)
									echo ("</TD></TR><TR><TD height=1></TD></TR>");
								}
								elseif(strstr(substr($gif,-3),"php")) // if banner is a php file
								{
									echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$gif?profileid=$data&searchid=$searchid\"></body></html>";
								}
								else
								{
									if($gif==JsConstants::$bmsStaticUrl."/bmsjs/gifs/zero.gif" && $showall==1)
									echo("<img src=\"$gif\" border=0\" vspace=\"0\"></a>");
									else
									echo("<img src=\"$gif\" border=0\" vspace=\"$spacing\"></a>");
									if ($showall == 1)
									echo ("</TD></TR><TR><TD height=1></TD></TR>");

								}
							}
						}
					}
				}
			}
			if ($showall == 1 && !$onlyHtmlBannerInShowAll)
			{
				if($align=='H')
				{
					echo("</tr>");
				}
				$showallstr.="</TABLE></BODY></HTML>";
				echo ("</TABLE></BODY></HTML>");
			}

		}
		else  // for pop up/popunder / banner in new window
		{
			for($i=1;$i<=$maxbans;$i++)
			{
				$banner = $finlist[$zone][$i];

				if ($banner)
				{
					logimpression($banner);
					$features=$bannarr[$banner]["BannerFeatures"];
					$class=$bannarr[$banner]["BannerClass"];
					$gif=$bannarr[$banner]["BannerGif"];

					if(strstr(substr($gif,-3),"htm")) // if the popup / popunder banner is a html file
					{
						$popstr = "$gif";
					}
					else
					{
						// to create an html file out of .gif
						$popstr = "$_SITEURL/bmsjs/jspopup.php?zone=$zone&banner=$banner&gif=$gif";
					}
					$echostr = $popstr."#".$features."#".$class;
					popupwin($echostr); // function for opening popup/popunder
				}
			}
		}
	}
	else
	{
		for($i=1;$i<=$maxbans;$i++)
		{
			$banner=$finlist[$zone][$i];

			if($banner)
			{
				$isstat=$bannarr[$banner]["BannerStatic"];
				$gif=$bannarr[$banner]["BannerGif"];
				$class=$bannarr[$banner]["BannerClass"];
				$bannerstring=$bannarr[$banner]["BannerString"];
				if(strstr(substr($gif,-3),"htm"))
				{
					//to zip the file before sending it

					$zipIt = 0;
					if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
					$zipIt = 1;
					if($zipIt)
					ob_start("ob_gzhandler");
					//end of it
				}

				if($isstat=='Y')
				{
					logimpression($banner);
					if(strstr(substr($gif,-3),"htm"))
					readfile("$gif");
					else
					echo("<img src=\"$gif\" border=0\"></a>");

				}
				else
				{	logimpression($banner);
				echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");
				if(strstr(substr($gif,-3),"htm"))
				readfile("$gif");
				else
				echo("<img src=\"$gif\" border=0\"></a>");

				}
				$smarty->assign($bannerstring,$echostr);
				$echostr="";
			}
		}
		$returnzones=$echostr;
	}
	return $returnzones;
}

function servetextlink($captureclick,$gif)
{
	echo("
                   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\">
                     <tr>
                       <td>
                        <div style=\"overflow: hidden;padding-left: 0px;\" align=\"left\">
                                     <a  href=\"$captureclick\" class=\"class4\" target=\"_blank\"><b><span>$gif</span></b></a>
                       </div>
                      </td>
                    </tr>
                   </table>
        ");
}

/*****************************************************************************************
Function that opens a banner in a new window , creates pop up/popunder
Input : String including url of gif alongwith the parameters (height , width etc) for pop up/popunder
****************************************************************************************/
function popupwin($echostr)
{
	$str = "";
	$str .= "function pop(){";
	$str .= "if(1){";//'$echostr'){";
	$str .= "var theURL=\"$echostr\";";
	$str .= "if(theURL.length>0){";
	$str .= "var str=theURL.split(\",\");";
	$str .= "for(var i=0;i < str.length;i++){";
	$str .= "if(str[i]!=\"\"){";
	$str .= "flist=str[i].split(\"#\");";
	$str .= "var respstr;";
	$str .= "if(flist[1]){respstr=\"ScreenX=\"+flist[1];}";
	$str .= "if(flist[2]){";
	$str .= "if(respstr){ respstr=respstr+\",ScreenY=\"+flist[2];}";
	$str .= "{respstr=\"ScreenY=\"+flist[2];} }";
	$str .= "if(flist[3]){ if(respstr) { respstr=respstr+\",left=\"+flist[3]; }";
	$str .= "else { respstr=\"left=\"+flist[3];} }";
	$str .= "if(flist[4]) {if(respstr){ respstr=respstr+\", Width=\"+flist[4];} else { respstr=\"width=\"+flist[4];} }";
	$str .= "if(flist[5]) {if(respstr){ respstr=respstr+\",height=\"+flist[5];}  else  { respstr=\"height=\"+flist[5];}}";
	$str .= "if(flist[6]=='PopUp'){ ow(flist[0],i,respstr);}else { owunder(flist[0],i,respstr);}}";
	$str .= " } } } }";
	$str .= "function ow(theURL,winName,features){ window.open(theURL,winName,features); }";
	$str .= "function owunder(theURL,winName,features){ var win2; win2=window.open(theURL,winName,features);";
	$str .= "if(win2){win2.blur(); window.focus();}}";
	$str .= "pop();";
	echo $str;
}

function logimpression($banlist)
{
	if($banlist != 0)
	{
		global $memcacheObj;
		//$memcacheObj=new UserMemcache;
		$memcacheObj->logBannerImpression($banlist);

		//$sql="Update bms2.BANNERHEAP set BannerCount=(BannerCount+1) , BannerServed=(BannerServed+1) where BannerId = '$banlist'";
		//mysql_query($sql) or logErrorBms("Error in logging impressions",$sql,"continue","YES");
	}
}

/**
* @param table tablename
* @param column column of table
* return array possible values of column type.
*/

function GetEnumColumnTypeValues( $table, $column)
{
	$mysqlObj=new Mysql;
	$mysqlObj->connect();

	// Create a SQL Query to get the Columns Type information,
	// Open a database connection, execute the query, and retrieve
	// the result.

	$sql = "show columns from bms2.$table like '$column'";
	$result = $mysqlObj->query($sql) or die(mysql_error());
	$myrow=$mysqlObj->fetchArray($result);

	$enum = $myrow['Type'];
	$off  = strpos($enum,"(");
	$enum = substr($enum, $off+1, strlen($enum)-$off-2);
	$values = explode(",",$enum);

	// For each value in the array, remove the leading and trailing
	// single quotes, convert two single quotes to one. Put the result
	// back in the array in the same form as CodeCharge needs.

	for( $n = 0; $n < Count($values); $n++) {
		$val = substr( $values[$n], 1,strlen($values[$n])-2);
		$val = str_replace("''","'",$val);
		$values[$n] = $val;
		//$values[$n] = array( $val, $val );
	}

	// return the values array to the caller
	return $values;
}

function defaultbanner()
{
	global $_SITEURL;
	echo("<img src=\"".JsConstants::$bmsStaticUrl."/bmsjs/gifs/zero.gif\">");
}
function getRemoteIP() 
{
	return FetchClientIP();
}

function topbanner_display($finlist,$bannarr,$zone,$zoneObj)
{
global $_LogosZone,$_HITSFILE , $_SERVER ;
global $mailer ;
global $showall;

$maxbans = $zonearr[$zone]["maxbans"];
$maxbans=$zoneObj->getZoneMaxBans();
$align = $zoneObj->getZoneAlignment();
$width = $zoneObj->getZoneBanWidth();
$height = $zoneObj->getZoneBanHeight();
$ispopup = $zoneObj->getZonePopup();
$spacing=$zoneObj->getZoneSpacing();

if(!$spacing)
            $spacing=2;
if($zone!=$_LogosZone)
{
    if($ispopup!='Y')
    {
        if ($showall == 1 && !$mailer)
        {
            if(count($finlist[$zone])==1)
            {
                $tempCurrentbanner=$finlist[$zone][1];
                $tempCurrentbannerClass=$bannarr[$tempCurrentbanner]['BannerClass'];
                if(in_array($tempCurrentbannerClass,array('html','html','swf')))
                            $onlyHtmlBannerInShowAll=1;
            }
            if(!$onlyHtmlBannerInShowAll)
            {
                echo ("");
                echo ("<div id='bms_container' class=''>");
                echo ('<div id="banner-Prev" class="banner-Prev" ><i class="setMid"></i><i class="iconS arrowB-L-Icon"></i></div>');
                echo ("<div id='carousel' class='items' style='position:absolute'>");
                if(trim($zoneObj->getZoneHeader()) != '') 
                {
                    echo $zoneObj->getZoneheader();
                }
                if($align=='H')
                {
                    echo ("<ul class='wrapper'>");
                }
            }
        }
        for($i=1;$i<=$maxbans;$i++)
        {
            $banner=$finlist[$zone][$i];
            if($banner)
            {
                $isstat = $bannarr[$banner]["BannerStatic"];
                $gif = $bannarr[$banner]["BannerGif"];
                $class = $bannarr[$banner]["BannerClass"];                        
                $url = $bannarr[$banner]["BannerUrl"];
                if($class!= 'textlink')
                {
                    if($align=='H') 
                    {
                        logimpression($banner);
                        if ($showall == 1 && !$onlyHtmlBannerInShowAll)
                        {
                            echo("<li class='boxBodr lf'>");
                        }
                        if($isstat != 'Y' && !$onlyHtmlBannerInShowAll)	// if the banner is clickable
                            echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");

                        if($gif=="http://static.ieplads.com/bmsjs/gifs/zero.gif" && $showall==1)
                            echo("<img src=\"$gif\" border=0\" vspace=\"0\"></a>");
                        else
                            echo("<img src=\"$gif\" border=0\" hspace=\"$spacing\"></a>");
                        if ($showall == 1)
                            echo("</li>");
                    }
                }
            }
        }

        if ($showall == 1 && !$onlyHtmlBannerInShowAll)
        {
            if($align=='H')
            {
                echo("</ul>");
            }

            echo ("</div><div id='banner-Next' class='banner-Next'><i class='setMid'></i><i class='iconS arrowB-R-Icon'></i>
                  </div></div>");
            echo ("");
        }
    }
}

}

?>
