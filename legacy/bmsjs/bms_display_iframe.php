<?php
/*****************************************************bms_display.php******************************************************/
/* 	
	*	Created By         :	Abhinav	Katiyar		
	*	Last Modified By   :	Abhinav Katiyar	
	*	Description        :	This file displays banners and logs the impressions
	*	Includes/Libraries :	./includes/bms_connect.php 
***************************************************************************************************************************/

include("includes/bms_display_include.php");
global $filewritestring;
global $bust;
global $timestamp;

$_LogosZone=36;      //Logos Zone where Banner String is used instead of Zone string
//$dbbms = mysql_connect("10.208.64.70","root","Km7Iv80l") or logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");

$dbbms = @mysql_connect("10.208.64.70","user","CLDLRTa9") or logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
@mysql_select_db("bms2",$dbbms);

/*****************************************************************
Function to get location details Based on IP address of surfer
input : No Input
output: Returns IP location
****************************************************************/
function getIPLocation()
{
	global $_SERVER,$dbbms;	
	$ip=$_SERVER[REMOTE_ADDR];
	$iparr=explode(".",$ip);

	$ipnum=(256*256*256)*$iparr[0]+(256*256)*$iparr[1]+256*$iparr[2]+$iparr[3];

        $sql="Select endIpNUM from GeoIP.Blocks  where endIpNum >= '$ipnum' ORDER BY endIpNUM LIMIT 1 ";
        $result=@mysql_query($sql) or logErrorBms("bms_display.php:getIPLocation:1:Could not get Location Id of ip. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
        if($myrow=mysql_fetch_array($result))
        {
                $endipnum=$myrow["endIpNUM"];
                                                                                                 
                $sql1="Select locid from GeoIP.Blocks where endIpNUM = '$endipnum' AND startIpNum <= '$ipnum'";
                $result1=mysql_query($sql1) or logErrorBms("bms_display.php:getIPLocation:1:Could not get Location Id of ip. <br><!--$sql1(".mysql_error().")-->:".mysql_errno(),$sql1,"continue","YES");
                if($myrow1=mysql_fetch_array($result1))
                {
                        $locid=$myrow1["locid"];
                        return $locid ;
                }
        }
/*        else
        {
                $locid=20476;
                return $locid;
        }*/
}

/*****************************************************************************************
	*	FUNCTION	:	GETS CRITERIA VALUES OF THE USER IF HE IS LOGGED IN
	*	INPUT		:	data
	*	OUTPUT		:	array containing value of the user		  
	 
*******************************************************************************************/
function get_user_value($data)
{
	if($data)
	{
		$profileid=$data;
	        $sql="SELECT GENDER,COUNTRY_RES,CITY_RES,INCOME,DTOFBIRTH FROM newjs.JPROFILE WHERE PROFILEID=".$profileid." ";
		$result=@mysql_query($sql) or logErrorBms("bms_display_iframe.php:display:3:Could not get user details. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
		$myrow=mysql_fetch_array($result);
		$user['GENDER']=$myrow["GENDER"];
		$user['COUNTRY_RES']=$myrow["COUNTRY_RES"];
		$user['CITY_RES']=$myrow["CITY_RES"];
		$user['DTOFBIRTH']=$myrow["DTOFBIRTH"];
	        $user['INCOME']=$myrow["INCOME"];
        	return $user;
	}
	else
	{
                $user['GENDER']="";
                $user['COUNTRY_RES']="";
                $user['CITY_RES']="";
                $user['DTOFBIRTH']="";
                $user['INCOME']="";
                return $user;
	}
}


 /*****************************************************************************************
   Parsing Function for conversion of criterias
   input : Region,Zone(s),criterias
   output: Calls Display function and returns the array containing final display string zone wise
 *****************************************************************************************/
function bannerDisplay_2($region,$zone,$data)
{
	global $_COOKIE;		
//	echo "<br>";print_r($data);echo "<br>";	
	$iplocation=getIPLocation();
	$critarr["region"]=$region; 
	$critarr["zone"]=$zone;
//	$critarr["ip"]=$iplocation;
	$userarr=get_user_value($data);
	if($userarr["GENDER"]!="")
        {
                $critarr["ip"]=$iplocation;
        }
        else
                $critarr["ip"]="";
    
	$userarr=get_user_value($data);
     	$critarr["gender"]=$userarr["GENDER"];
	if($userarr["DTOFBIRTH"])
	{	
		$dob=$userarr["DTOFBIRTH"];
		$critarr["age"]=getAgeByDate($dob);
	}
	else
		$critarr["age"]="";
	$critarr["ctc"]=$userarr["INCOME"];
        $critarr["country"]=$userarr["COUNTRY_RES"];
        if($userarr["COUNTRY_RES"]== 128)
                $critarr["country"]= 127;
	if($userarr["COUNTRY_RES"]== 127)
                $critarr["country"]= 128;
        $critarr["city"]=$userarr["CITY_RES"];

//     	echo "<br>";print_r($critarr);echo "<br>";
    	display($critarr);
}
  
 /**************************************************************************************************
  	This function is use to calculate age of user
	input  : date of birth
	output : age 
  **************************************************************************************************/ 
  
  function getAgeByDate($dob)
  {
  	
  	list($iYear,$iMonth,$iDay)=explode("-",$dob);
  	
  	$nMonth = date("m");
   	$nDay = date("d");
   	$nYear = date("Y");
   	
	$baseyear = $nYear - $iYear-1 ;
   	if ($iMonth < $nMonth OR ($iMonth == $nMonth AND $iDay <= $nDay))
   	{
       		// had birthday
		$baseyear++;
   	}
   	return $baseyear;
  }

/*******************************************************************************************
	Function for Display of Banners
  	input : Array containing Criterias,Zone,Region
    	output: returns the array containing final display string zone wise
*******************************************************************************************/
function display($critarr)
{
	global $dbbms;
    	global $filewritestring;
    	$region=$critarr["region"];
    	$zone=$critarr["zone"];
    	if(!$zone || $zone==0)
    	{
     		$sql="Select SQL_CACHE * from bms2.ZONE where RegId='$region' and ZoneStatus='active'";
     		$result=mysql_query($sql) or logErrorBms("bms_display.php:display:1:Could not get Zone listings. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
    	}
    	else
    	{
     		$sql="Select SQL_CACHE * from bms2.ZONE where ZoneId in ($zone) and ZoneStatus='active'";
     		$result=mysql_query($sql) or logErrorBms("bms_display.php:display:2:Could not get Zone listings. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
    	}
	if($myrow=mysql_fetch_array($result))
    	{
    		do
    		{
    			$currzone=$myrow["ZoneId"];
    			if($zones)
    		  	{
    		  		$zones.=",".$myrow["ZoneId"]; 
			}
    		  	else
    		  	{
    		  		$zones=$myrow["ZoneId"];
			}
    		      	$zonearr[$currzone]["maxbans"]=$myrow["ZoneMaxBans"];
			$zonearr[$currzone]["maxrot"]=$myrow["ZoneMaxBansInRot"];
			$zonearr[$currzone]["align"]=$myrow["ZoneAlignment"];
			$zonearr[$currzone]["width"]=$myrow["ZoneBanWidth"];
			$zonearr[$currzone]["height"]=$myrow["ZoneBanHeight"];
			$zonearr[$currzone]["ispop"]=$myrow["ZonePopup"];
			$zonearr[$currzone]["criterias"]=$myrow["ZoneCriterias"];
	        	$criterias=explode(",",$zonearr[$currzone]["criterias"]);

		   	$zonecriterias=Array();
			for($i=0;$i<count($criterias);$i++)
                  	{
                  		if($criterias[$i])
                  		if(is_array($zonecriterias))
                  		{
                  			if(!in_array($criterias[$i],$zonecriterias))
                  			{
                  				$cnt=count($zonecriterias);
                  				$zonecriterias[$cnt]=$criterias[$i];	
                  			}	
                  		}
                  		else 
                  		{
                  			$zonecriterias[0]=$criterias[$i];
                  		}
			}
		}
		while($myrow=mysql_fetch_array($result));
//		echo "<br>";print_r($zonecriterias);echo "<br>";echo $zones;echo "<br>";print_r($critarr);echo "<br>";
//		echo "**********QUERY*************************<br>";

      		$query=createQuery($critarr,$zonecriterias,$zones);
    		$resfin=mysql_query($query) or logErrorBms("bms_display.php:display:3:Could not retrieve the banners. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
    		if($myrow=mysql_fetch_array($resfin))
    		{
    			$i=0;
    			do 
    			{
    				$banner=$myrow["BannerId"];
    				$bannarr[$banner]["zone"]=$myrow["ZoneId"];
				$zoneval=$bannarr[$banner]["zone"];

				if($zonearr[$zoneval]["banners"])
				{
					$zonearr[$zoneval]["banners"].=",".$banner;
				}
				else
				{
					$zonearr[$zoneval]["banners"]=$banner;
				}
				$bannarr[$banner]["class"]=$myrow["BannerClass"];
				$bannarr[$banner]["isstat"]=$myrow["BannerStatic"];
				$bannarr[$banner]["priority"]=$myrow["BannerPriority"];
				$bannarr[$banner]["weight"]=$myrow["BannerWeightage"];
				$bannarr[$banner]["served"]=$myrow["BannerCount"];
				$bannarr[$banner]["gif"]=$myrow["BannerGif"];
				$bannarr[$banner]["url"]=$myrow["BannerUrl"];
				$bannarr[$banner]["default"]=$myrow["BannerDefault"];
				$bannarr[$banner]["string"]=$myrow["BannerString"];
				$bannarr[$banner]["features"]=$myrow["BannerFeatures"];
				$bannarr[$banner]["location"]=$myrow["BannerLocation"];
//				$bannarr[$banner]["location"]=$myrow["BannerLocation"];
                                $bannarr[$banner]["country"]=$myrow["BannerCountry"];
                                $bannarr[$banner]["incity"]=$myrow["BannerInCity"];
                                $bannarr[$banner]["uscity"]=$myrow["BannerUsCity"];
				$bannarr[$banner]["ip"]=$myrow["BannerIP"];
				$bannarr[$banner]["ipcity"]=$myrow["BannerCity"];
				$bannarr[$banner]["maxctc"]=$myrow["BannerCTCMax"];
				$bannarr[$banner]["minctc"]=$myrow["BannerCTCMin"];
				$bannarr[$banner]["maxage"]=$myrow["BannerAgeMax"];
				$bannarr[$banner]["minage"]=$myrow["BannerAgeMin"];
				$bannarr[$banner]["gender"]=$myrow["BannerGender"];
				$bannarr[$banner]["ctc"]   =$myrow["BannerCTC"];
				$i++;
    			}
			while($myrow=mysql_fetch_array($resfin));
//			echo "<br>";print_r($bannarr);echo "<br>";
    			filterBanners($critarr,$zonecriterias,$zones,$bannarr,$zonearr);
    		}
	}
}

  
/*******************************************************************************
	Function that finally displays the banners
  	input : List of filtered banners,array of details of all the banners,
		array of details of all the zones(required zones) and current zone
    	output: Returns the string for display in a zone
********************************************************************************/
  function actual_display($finlist,$bannarr,$zonearr,$zone)
  {
  	global $_LogosZone,$_HITSFILE,$smarty,$othersrcp; 
	global $bust , $timestamp;
  	$maxbans=$zonearr[$zone]["maxbans"];
  	$align=$zonearr[$zone]["align"];
    	$width=$zonearr[$zone]["width"];
    	$height=$zonearr[$zone]["height"];
    	$ispopup=$zonearr[$zone]["ispop"];
    	
    	//print_r($bannarr);
    	if($zone!=$_LogosZone)
    	{
    		if($ispopup!='Y')
    		{
			for($i=1;$i<=$maxbans;$i++)
			{
				$banner=$finlist[$zone][$i];
   					
				if($banner)
				{	
					$isstat=$bannarr[$banner]["isstat"];
					$gif=$bannarr[$banner]["gif"];
					$class=$bannarr[$banner]["class"];
						
					if($align=='H')
					{
						logimpression($banner);
						$sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
						$res = mysql_query($sql) or logErrorBms("bms_display_frame.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
						if(strstr(substr($gif,-3),"gif"))
							header('Content-type: image/gif');
						readfile("$gif");
					}
					elseif($align=='V')
					{	
						logimpression($banner);
						$sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
						$res = mysql_query($sql) or logErrorBms("bms_display_frame.php:2: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
						if(strstr(substr($gif,-3),"gif"))
							header('Content-type: image/gif');
						readfile("$gif");
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
					$features=$bannarr[$banner]["features"];
					$class=$bannarr[$banner]["class"];
					$gif=$bannarr[$banner]["gif"];

					if(strstr($gif,".htm"))
					{	
						$popstr = "$gif";
					}
					else
					{	
						//$popstr = "$_POPUPFILE?zone=$zone&banner=$banner&gif=$gif";
						$popstr = "http://192.168.2.220/bmsjs/jspopup.php?zone=$zone&banner=$banner&gif=$gif";
						//$popstr = "http://www.jeevansathi.com/bmsjs/jspopup.php?zone=$zone&banner=$banner&gif=$gif";
					}
					echo $echostr = $popstr."#".$features."#".$class;
					logimpression($banner);
					popupwin($echostr);
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
				$isstat=$bannarr[$banner]["isstat"];
				$gif=$bannarr[$banner]["gif"];
				//print_r($bannarr);
				$class=$bannarr[$banner]["class"];
				$bannerstring=$bannarr[$banner]["string"];
				//$echostr.="<TD height=$height>";
				if($isstat=='Y')
				{
					if($class=="Image")
					{
						logimpression($banner);
                                                $sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
                                                $res = mysql_query($sql) or logErrorBms("bms_display_frame.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
                                                readfile("$gif");

  						//$echostr.="<img src='$gif' border=0>";
					}
  					elseif($class=="Flash") 
  					{
						logimpression($banner);
                                                $sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
                                                $res = mysql_query($sql) or logErrorBms("bms_display_frame.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
                                                readfile("$gif");
  						//$echostr.="<object><embed src=\"$gif\" width=$width height=$height></embed></object>";
					}
  	   			}
  	   			else
  	   			{
  	   				if($class=="Image")
					{
					 	logimpression($banner);
                                                $sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
                                                $res = mysql_query($sql) or logErrorBms("bms_display_frame.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
                                                readfile("$gif");
  	   			 		//$echostr.="<a href='$_HITSFILE?banner=$banner&othersrcp=$othersrcp'  target='_blank'><img src='$gif' border=0></a>";
					}
					elseif($class=="Flash") 
  	   			 	{
						logimpression($banner);
                                                $sql = "INSERT INTO bms2.HITS (Id,Bust,Timestamp,BannerId) VALUES ('','$bust',NOW(),'$banner') ";
                                                $res = mysql_query($sql) or logErrorBms("bms_display_frame.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
                                                readfile("$gif");
  	   			 		//$echostr.="<object><embed src=\"$gif\" width=$width height=$height></embed></object>";
  	   			 	} 
  	   			}
  	   			$smarty->assign($bannerstring,$echostr);
  	   			$echostr="";
  	   			//unset($echostr);
   			}
   		}
   		$returnzones=$echostr;
  	}
  	return $returnzones;	    	
  }
   
/*******************************************************************************************
	Function that creates query for extraction of banners from databases
   	  input : Criterias array,zone wise criterias allowed,zone listing for querying
   	  output : Final Query On Banners Table
*****************************************************************************************/
function createQuery($critarr,$zonecriterias,$zones)
{
	//echo "critarr :############ <br>";
	//print_r($critarr);
//	print_r($zonecriterias);
//	echo "critarr :############ <br>";

	$sql="Select b.*,h.BannerCount from bms2.BANNER b,bms2.BANNERHEAP h where (b.ZoneId IN ($zones)) and b.BannerStatus='live'";
   		 
   	if(in_array("LOCATION",$zonecriterias)) 
   	//if($critarr["location"] || trim($critarr["location"]) != '')
	if($critarr["country"] || trim($critarr["country"]) != ''|| $critarr["city"] || trim($critarr["city"]) != '')
   	{
		$country = $critarr["country"];
                $city    = $critarr["city"];
                {
                        if($locstr)
                        {
                                $locstr.=" or b.BannerCountry LIKE '%$country%'";
                                if($country == 51)
                                        $locstr.="and b.BannerInCity LIKE '%$city%'";
                                if($country == 127)
                                        $locstr.="and b.BannerUsCity LIKE '%$city%'";
                        }
                        else
                        {
                                $locstr=" b.BannerCountry LIKE '%$country%'";
                                if($country == 51 && trim($city)!='')
                                        $locstr.="and b.BannerInCity LIKE '%$city%'";
                                /*if($country == 127 && trim($city)!='')
                                        $locstr.="and b.BannerUsCity LIKE '%$city%'";*/
                        }
                }

     		if($locstr)
     		{
      			if($sqlstr)
      			{
       				$sqlstr.=" or ".$locstr; 
      			}
      			else
      			{
       				$sqlstr="(".$locstr;
      			} 
     		}
   	}
	if(in_array("IP",$zonecriterias))	
   	if($critarr["ip"] || trim($critarr["ip"]) != '')
   	{
   		$ipval=$critarr["ip"];
    		$ipstr=" b.BannerIP LIKE '% $ipval %'";
   		if($sqlstr)
    		{ 
      			$sqlstr.=" or ".$ipstr; 	  
    		}   
    		else
    		{
      			$sqlstr="(".$ipstr; 	  
    		}  
   	}
   		
	if(in_array("INCOME",$zonecriterias))	
   	if($critarr["ctc"] || trim($critarr["ctc"]) != '')
   	{
   		$ctc=$critarr["ctc"]; 
		$ctcstr=" b.BannerCTC LIKE '% $ctc %' ";
    		if($sqlstr)
    		{ 
      			$sqlstr.=" or ".$ctcstr; 	  
    		}   
    		else
    		{
      			$sqlstr="(".$ctcstr; 	  
    		}  
   	}
   	if(in_array("AGE",$zonecriterias))	
   	if($critarr["age"] || trim($critarr["age"]) != '')
   	{
   		$age=$critarr["age"]; 
   		$agestr=" (b.BannerAgeMin <= '$age' and b.BannerAgeMax >= '$age') ";  
    		if($sqlstr)
    		{ 
      			$sqlstr.=" or ".$agestr; 	  
    		}   
    		else
    		{
      			$sqlstr="(".$agestr; 	  
    		}  
   	}
	if(in_array("GENDER",$zonecriterias))	
   	if($critarr["gender"] || trim($critarr["gender"]) != '')
   	{
   		$gender=$critarr["gender"]; 
   		$genderstr=" (b.BannerGender = '$gender')";  
    		if($sqlstr)
    		{ 
      			$sqlstr.=" or ".$genderstr; 	  
    		}   
    		else
    		{
      			$sqlstr="(".$genderstr; 	  
    		}  
   	}
	if($sqlstr)
	 	$sql.=" and ".$sqlstr." or b.BannerDefault = 'Y')";
	/*else
		$sql.=" and b.BannerDefault = 'Y'";*/
   	$sql.=" and h.BannerId=b.BannerId";// Order By b.ZoneId,b.BannerPriority,b.BannerId"; 
//	echo "<br>";
// 	echo $sql;
//	echo "<br>";
   	return($sql);
} 
  
  
   /*********************************************************************************************
	Function for filtering banners to get the actual banners to display	
    	input : Criterias array,zone wise criterias allowed,zone listing for querying,banners details,zones details
   	output : Assigns the display string to zone string
   *********************************************************************************************/
   function filterBanners($critarr,$zonecriterias,$zones,$bannarr,$zonearr)
   {
   	 	global $smarty,$_LOGIMPS,$filewritestring;
//   		echo "############IN FILTER BANNERS###################";	
   		$zonearray=explode(",",$zones);
//   		echo "<br>"."ZONEARRAY :";
//		print_r($zonearray);
//		echo "<br>"."ZONEARR :";
//              print_r($zonearr);

   		for($i=0;$i<count($zonearray);$i++)
   		{
   			$zone=$zonearray[$i];
   			$banners=$zonearr[$zone]["banners"];
   			$bannersinzone=explode(",",$banners);
   			for($j=0;$j<count($bannersinzone);$j++)
   			{
   				$banner=$bannersinzone[$j];
   				$zonecrit=$zonearr[$zone]["criterias"];
   				$returnarray=filterOnCriteria($bannarr,$banner,$critarr,$zonecrit);
				//echo "<br>"."RETURNARRAY: ";print_r($returnarray);echo "<br>";
   				if($returnarray["isselected"]=="True")
   				{
   					$bannarr[$banner]["dueto"]=$returnarray["criteria"];
   					
   					$pri=$bannarr[$banner]["priority"];
   					if($banzonepriority[$zone][$pri]["banners"])
   					{
   						$banzonepriority[$zone][$pri]["banners"].=",".$banner;
   					}
   					else 
   					{
   						$banzonepriority[$zone][$pri]["banners"]=$banner;
   					}
   					
   					if($bannarr[$banner]["default"]=="Y")
   					{
   						$banzonepriority[$zone][$pri]["defaultcount"]++;
   					}
   					else 
   					{
   						$banzonepriority[$zone][$pri]["notdefaultcount"]++;
   					}
   				}
   			}
   		}
   		
   		for($i=0;$i<count($zonearray);$i++)
   		{
   			$zone=$zonearray[$i];
   			$maxbans=$zonearr[$zone]["maxbans"];
   			for($j=1;$j<=$maxbans;$j++)
   			{
   				if($banzonepriority[$zone][$j]["banners"])
   				{
   					$bannerarray=explode(",",$banzonepriority[$zone][$j]["banners"]);
					//print_r($bannerarray);
					//print_r($banzonepriority[$zone]);
   					for($k=0;$k<count($bannerarray);$k++)
   					{
   						$banner=$bannerarray[$k];
   						if($banzonepriority[$zone][$j]["notdefaultcount"]>0)
   						{	
   							if($bannarr[$banner]["default"]!='Y')
   						 		$diff[$zone][$j][$banner]=$bannarr[$banner]["weight"]-$bannarr[$banner]["served"]/$bannarr[$banner]["weight"];
   						}
   						elseif($banzonepriority[$zone][$j]["defaultcount"]>0)
   						{	
   							if($bannarr[$banner]["default"]=='Y')
   								$diff[$zone][$j][$banner]=$bannarr[$banner]["weight"]-$bannarr[$banner]["served"]/$bannarr[$banner]["weight"];
   						}
   					}
   					
   					$finbanner=array_search(max($diff[$zone][$j]),$diff[$zone][$j]);
   					
   					if($finlist[$zone][$j])
   					{
   						$finlist[$zone][$j].=",".$finbanner;
   					}
   					else 
   					{
   						$finlist[$zone][$j]=$finbanner;
   					}
   				}
   			}
   			if(is_array($finlist[$zone])) $bannerlist[$zone]=implode(",",$finlist[$zone]);
   			
   			$bannerlisting=explode(",",$bannerlist[$zone]);
   			
   			for($cnt=0;$cnt<count($bannerlisting);$cnt++)
   			{
   				$banner=$bannerlisting[$cnt];
   				if($bannarr[$banner]["dueto"] && $bannarr[$banner]["default"]!="Y")
   				{
   						$filewritestring.=$banner."#".$bannarr[$banner]["dueto"]."\n";
   				}
   			}
   			  
			$string="zonedisp".$zone;
			$returnzones[$string]=actual_display($finlist,$bannarr,$zonearr,$zone);
//			echo "<br>---zonezrr :------- :";
//			echo "<br>";print_r($zonearr);echo "<br>";
			//if($zonearr[$zone]["ispop"]!='Y') 
				//$returnzones[$string].="<Img src=\"$_LOGIMPS?banlist=".$bannerlist[$zone]."&flag=1\" Width=0 height=0>";
			//else logimpression($bannerlist[$zone]);
//			echo "<br>";print_r($bannerlist[$zone]);echo "<br>";
//			echo	"%%".$returnzones[$string]."%%";
//			echo "<br> STRING :".$string;echo "<br>";
			$smarty->assign($string,$returnzones[$string]);
   		}
   }	
   
   /***************************************************************************
	Function that logs impressions(for popups only)
	Input : Banners for which to log Impressions
   *****************************************************************************/
   
   function logimpression($banlist)
   {
	global $dbbms;
   	if($banlist != 0)//$banlist and trim($banlist)!='')
   	{
	   	//$sql="Update bms2.BANNERHEAP set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId IN ($banlist)";
		$sql="Update bms2.BANNERHEAP set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId = '$banlist'";
    		mysql_query($sql) or logErrorBms("bms_display.php:logimpression:4:Could not log the impression.<br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
		//$sql="Update bms2.BANNERHEAPCOPY set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId IN ($banlist)";
		$sql="Update bms2.BANNERHEAPCOPY set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId = '$banlist'";
                mysql_query($sql) or logErrorBms("bms_display.php:logimpression:4:Could not log the impression.<br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"continue","YES");
	}  
   }
   
   function popupwin($echostr)
   {
	echo("<html><head>");
	echo("<script language=\"javascript\">");
	echo("function pop(){");
	echo("if(document.bmsform.hiddenValuesFromBMS){");
	echo("var theURL=document.bmsform.hiddenValuesFromBMS.value;");
	echo("if(theURL.length>0){");
	echo("var str=theURL.split(\",\");");
	echo("for(var i=0;i<str.length;i++){");
	echo("if(str[i]!=\"\"){");
	echo("flist=str[i].split(\"#\");");
	echo("var respstr;");
	//echo("alert(flist[0]);");
	//echo("flist[0] = $str"); 
	echo("if(flist[1]){respstr=\"ScreenX=\"+flist[1];}");
	echo("if(flist[2]){");
	echo("if(respstr){ respstr=respstr+\",ScreenY=\"+flist[2];}");
	echo("{respstr=\"ScreenY=\"+flist[2];} }");
	echo("if(flist[3]){ if(respstr) { respstr=respstr+\",left=\"+flist[3]; }");
	echo("else { respstr=\"left=\"+flist[3];} }");
	echo("if(flist[4]) {if(respstr){ respstr=respstr+\", height=\"+flist[4];} else { respstr=\"height=\"+flist[4];} }");
	echo("if(flist[5]) {if(respstr){ respstr=respstr+\",Width=\"+flist[5];}  else  { respstr=\"width=\"+flist[5];}}");
	echo("if(flist[6]=='PopUp'){ ow(flist[0],i,respstr);}else { owunder(flist[0],i,respstr);}}");
	echo(" } } } }");
	echo("</script></head>");
	echo("<body onload=\"pop();\">");
	echo("<script language=\"javascript\">");
	echo("function ow(theURL,winName,features){ window.open(theURL,winName,features); }");
	echo("function owunder(theURL,winName,features){  var win2; win2=window.open(theURL,winName,features); win2.blur(); window.focus();}");
	echo("</script>");
	echo("<form name=\"bmsform\"><input type=\"hidden\" name=\"hiddenValuesFromBMS\" value=\"$echostr\"></form>");
	echo("</body></html>");
   }
/*************************************************************************************
	Filtering banners based on various criterias(getting banners that exactly matches the criterias specified)
	Input : Banner Details,Banner,Criterias,Criterias for this zone
	Output : Whether the banner matches the criterias specified
************************************************************************************/
   
   function filterOnCriteria($bannarr,$banner,$critarr,$zonecrit)
   {
   	 	if($zonecrit && trim($zonecrit)!='')
   	 	{
			$zonecritarr=explode(",",$zonecrit);
//			echo "<br>";
//			echo "zonecritarr : ";print_r($zonecritarr);
//			echo "<br>";
//			echo "banerrarr : ";print_r($bannarr);	
//			echo "<br>";
//                      echo "banner : ";print_r($banner);
//                      echo "<br>";
//                      echo "critarr : ";print_r($critarr);
                                                                                                 
	
			if(in_array("IP",$zonecritarr))
			{
				if($bannarr[$banner]["ip"] && trim($bannarr[$banner]["ip"])!='')
				{
					if($critarr["ip"] && trim($critarr["ip"])!='')
					{
				 		$temp=explode("#",$bannarr[$banner]["ip"]);
//						echo "<br>"."TEMP : ";print_r($temp);echo "<br>";
						$string=trim($temp[1]);
				 		$string=str_replace(" ","",$string);
				 		$strarr=explode(",",$string);
				 		if(in_array($critarr["ip"],$strarr))
				 		{
				 			$resfrom["ip"]="True";
				 			$bancityarr=explode("#",$bannarr[$banner]["ipcity"]);
				 			$bancity=str_replace(" ","",$bancityarr[1]);
				 			$dueto["ip"]=$bancity;
				 			$valueip=$critarr["ip"];
				 			//echo count($dueto);
				 		}
				 		else 
				 		{
				 			$returnArray["isselected"]="False";
   	 						$returnArray["criteria"]="";
   	 						return $returnArray;
				 		}
				 	}
				 	else 
				 	{
				 		$returnArray["isselected"]="False";
   	 					$returnArray["criteria"]="";
   	 					return $returnArray;
				 	}
				}
			}

			if(in_array("LOCATION",$zonecritarr))
			{
				if($bannarr[$banner]["location"] && trim($bannarr[$banner]["location"])!='')
                                {
                                                                                                                            
                                        if($critarr["country"] && trim($critarr["country"])!='')
                                        {
                                                $country = $bannarr[$banner]["country"];
                                                $incity  = $bannarr[$banner]["incity"];
                                                $uscity  = $bannarr[$banner]["uscity"];
                                                $ctrystring=str_replace(" , ",",",$country);
                                                if (trim($incity)!='')
                                                        $incitystring=str_replace(" , ",",",$incity);
                                                                                                                            
                                                if (trim($uscity)!='')
                                                        $uscitystring=str_replace(" , ",",",$uscity);
                                                $citystring = $incitystring;
                                                if (trim($uscitystring)!='')
                                                        $citystring.=",".$uscitystring;
                                                                                                                            
                                                //$string=strtoupper($string);
                                                $countryarr=explode(",",$ctrystring);
                                                $cityarr=explode(",",$citystring);
                                                //print_r($countryarr);   echo $critarr["city"];
                                                $locationcrit = in_array($critarr["country"],$countryarr);
                                                if (trim($critarr["city"])!= '' && count($countryarr)!= 0)
                                                        $locationcrit.= " && ".in_array($critarr['city'],$cityarr);
                                                //if(in_array($critarr["country"],$countryarr)) //&& in_array($critarr["city"],$cityarr))
                                                if ($locationcrit == 1)
                                                {
                                                        $resfrom["location"]="True";
                                                        $dueto["location"]=$critarr["country"];
                                                        if (trim($critarr["city"]) != '')
                                                        {
                                                                $dueto["location"]=$critarr["country"]." |X| ".$critarr["city"];
                                                                $resfrom["city"]="True";
                                                        }
                                                                                                                            
                                                }
                                                else
                                                {      
                                                        $returnArray["isselected"]="False";
                                                        $returnArray["criteria"]="";
                                                        return $returnArray;
                                                }
                                                                                                                            
                                        }
                                        else
                                        {       
                                                $returnArray["isselected"]="False";
                                                $returnArray["criteria"]="";
                                                return $returnArray;
                                        }

						
				}
			}
				
			if(in_array("GENDER",$zonecritarr))
			{
				if($bannarr[$banner]["gender"]!='')
				 {
					if($critarr["gender"] && trim($critarr["gender"])!='')
					{
				 		if($critarr["gender"] == $bannarr[$banner]["gender"])
				 		{
				 			$resfrom["gender"]="True";
				 			$dueto["gender"]=$critarr["gender"];
				 			//echo count($dueto);
				 		}
				 		else 
				 		{
				 			$returnArray["isselected"]="False";
   	 						$returnArray["criteria"]="";
   	 						return $returnArray;
				 		}
				 	}
				 	else 
				 	{
				 		$returnArray["isselected"]="False";
   	 					$returnArray["criteria"]="";
   	 					return $returnArray;
				 	}
				}
			}
					
			if(in_array("AGE",$zonecritarr))
			{
				if($bannarr[$banner]["minage"]>=0 && $bannarr[$banner]["maxage"]>=0)
				 {
					if($critarr["age"] && trim($critarr["age"])!='')
					{
				 		if(($critarr["age"] >= $bannarr[$banner]["minage"]) && ($critarr["age"] <= $bannarr[$banner]["maxage"]))
				 		{
				 			$resfrom["age"]="True";
				 			$dueto["age"]=$critarr["age"];
				 			//echo count($dueto);
				 		}
				 		else 
				 		{
				 			$returnArray["isselected"]="False";
   	 						$returnArray["criteria"]="";
   	 						return $returnArray;
				 		}
				 	}
				 	else 
				 	{
				 		$returnArray["isselected"]="False";
   	 					$returnArray["criteria"]="";
   	 					return $returnArray;
				 	}
				}
			}		
			
			if(in_array("INCOME",$zonecritarr))
			{
//				if($bannarr[$banner]["minctc"]>=0 && $bannarr[$banner]["maxctc"]>=0)
                               if($bannarr[$banner]["ctc"] && trim($bannarr[$banner]["ctc"])!='')
			       {
					if($critarr["ctc"] && trim($critarr["ctc"])!='')
					{
//						if(($critarr["ctc"] >= $bannarr[$banner]["minctc"]) && ($critarr["ctc"] <= $bannarr[$banner]["maxctc"]))
                                                $temp=explode("#",$bannarr[$banner]["ctc"]);
//                                              echo "<br>"."TEMP : ";//print_r($temp);echo "<br>";
                                                $string=trim($temp[1]);
                                                $string=str_replace(" ","",$string);
                                                $strarr=explode(",",$string);
                                                if(in_array($critarr["ctc"],$strarr))
				 		{
				 			$resfrom["ctc"]="True";
				 			$dueto["ctc"]=$critarr["ctc"];
				 			//echo count($dueto);
				 		}
				 		else 
				 		{
				 			$returnArray["isselected"]="False";
   	 						$returnArray["criteria"]="";
   	 						return $returnArray;
				 		}
				 	}
				 	else 
				 	{
				 		$returnArray["isselected"]="False";
   	 					$returnArray["criteria"]="";
   	 					return $returnArray;
				 	}
				}
			}
					
			if($resfrom["ip"]=="True")
			{
				$savestring.=$dueto["ip"]."#";			
				$savestring.=$valueip."#";			
			}
			else 
			{
				$savestring.="##";
			}
			if($resfrom["location"]=="True")
			{
				$savestring.=$dueto["location"]."#";			
			}
			else 
			{
				$savestring.="#";
			}
			if($resfrom["ctc"]=="True")
			{
				$savestring.=$dueto["ctc"]."#";			
			}
			else 
			{
				$savestring.="#";
			}
			if($resfrom["age"]=="True")
			{
				$savestring.=$dueto["age"]."#";			
			}
			else 
			{
				$savestring.="#";
			}
			if($resfrom["gender"]=="True")
			{
				$savestring.=$dueto["gender"]."#";			
			}
			else 
			{
				$savestring.="#";
			}
			
			//echo $savestring;
			//print_r(explode("#",$savestring));
			//print_r($dueto);
			
			$returnArray["isselected"]="True";
   	 		$returnArray["criteria"]=$savestring;
			$returnArray["banner"]=$banner;
//			echo "<br>";echo "returnArray : ";print_r($returnArray);echo "<br>";
			return $returnArray;
   	 	}
   	 	else 
   	 	{
   	 		$returnArray["isselected"]="True";
   	 		$returnArray["criteria"]="";
   	 		return $returnArray;
   	 		
   	 	}
   	 	
   }

 /*Function that compare two strings and tells whether every portion of str2 is also a part of str1
 	Input : Strings
 	Output : True or false
 */  
 function bms_compStrings($str1,$str2)
 {
 	$strArr1=explode(',',$str1);
 	$strArr2=explode(',',$str2);
 	
 	for($i=0;$i<count($strArr2);$i++){
 		if(in_array($strArr2[$i],$strArr1))
 		{
 			$check=0;
 		}
 		else
 		{
 		    return 1; 
 		}
 	}
 	if($check==0) return 0;
 }
 
 
  /*Function that logs the criterias in a flat file
  	Input : String to be logged
  	Output : Writes into the file
  */	
  //echo $filewritestring;
  function writetofile($filewritestring)
  {
  	global $_LOGPATH;
//	echo $_LOGPATH;
  	$dt=date("Ymd");
  	if($filewritestring)
  	{
  		$filename="$_LOGPATH/bmsconditionaloutput".$dt.".txt";
  	
  		$fp=@fopen($filename,"a");
  		@fwrite($fp,$filewritestring);
  	}
  }
//echo "<!--";	
  bannerDisplay_2($regionstr,$zonestr,$data);
  writetofile($filewritestring);
//echo "-->";
?>
