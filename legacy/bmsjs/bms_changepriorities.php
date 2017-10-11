<?php
/****************************************************bms_changepriorities.php************************************************
        *       Created By              :       Shobha Kumari
        *       Last Modified By        :       Shobha Kumari
        *       Description             :       This file is included in file for changing priorities ; it contains all the 
						required functions
        *       Includes/Libraries      :       ./includes/bms_connect.php
****************************************************************************************************************************/

 function checkIfCorrect($critarr)
 {
	global $dbbms;
	$zoneid=$critarr["zoneid"];
	$banner=$critarr["bannerid"];
	$priority=$critarr["bannerpriority"];
	$startdt=$critarr["bannerstartdate"];
	$enddt=$critarr["bannerenddate"];
	$result=checkforAvailability($zoneid,$priority,$critarr,$startdt,$enddt,$banner);
	if($result!="false") $result="true";

	if($result=="false") return 0;
	else return 1;	
 }

 function checkforAvailability($zoneid,$priority,$critarr,$startdt,$enddt,$banner)
 {
	global $dbbms; 
	$criterias=$critarr["criteriaarr"];
	$sql="Select ZoneMaxBansInRot from bms2.ZONE where ZoneId='$zoneid'";
	$result=mysql_query($sql,$dbbms) or die(mysql_error($dbbms));

	$myrow=mysql_fetch_array($result);
	$maxbansinrot=$myrow["ZoneMaxBansInRot"];
	if($critarr["bannerdefault"]!="Y")
	{	
		for($i=0;$i<count($criterias);$i++)
		{
   		    	if($criterias[$i]=='Keywords')
	     		{
				$string=$critarr["bannerkeyword"];
    		        	$string=strtoupper(trim($string));
				$string=str_replace("[\w]+"," ",$string);
				$string=str_replace(" ,",",",$string);
				$string=str_replace(", ",",",$string);	
 				$strarr=explode(",",$string);

		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
				        	$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerKeyword Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";	
		   				$result=mysql_query($sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							$myrow["cnt"]."  ".$maxbansinrot;	
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return "false";
							}
   	           				}		 		
		 			}
				}	
       		}

		if($criterias[$i]=='Location')
	     	{
				$string=$critarr["bannerlocation"];
				$string=strtoupper(trim($string));
				$string=str_replace("[\w]+"," ",$string);
				$string=str_replace(" ,",",",$string);
				$string=str_replace(", ",",",$string);	
 				$strarr=explode(",",$string);
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
						$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerLocation Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return("false");
							}
						}		 		
					}	
				}
		}
		if($criterias[$i]=='Industry')
	     	{
				$strarr=$critarr["bannerindtype"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
						$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerIndtype Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return("false");
							}
						}
					}
				}
		    }
		if($criterias[$i]=='IP')
		{
				$strarr=$critarr["bannerip"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		 				$locids=getLocids(trim($strarr[$j]));
		 				if($locids && trim($locids)!='')
		 				{
		 					$locarr=explode(",",$locids);
							for($loc=0;$loc<count($locarr);$loc++)
							{
								$str=trim($locarr[$loc]);
				        			$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerIP Like '% $str %' and BannerId!='$banner'";
				        		$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   						if($myrow=mysql_fetch_array($result))
		   						{
									//echo $myrow["cnt"]."  ".$maxbansinrot;	
									if($myrow["cnt"]>=$maxbansinrot)
									{
										return("false");
									}
   	           						}
		 					}
						}
		    			}
				}
		}
		if($criterias[$i]=='Farea')
	     	{
				$strarr=$critarr["bannerfarea"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
						$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerFarea Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							//echo $myrow["cnt"]."  ";	
							if($myrow["cnt"]>=$maxbansinrot)
							{
								return("false");
							}
   	           				}
		 			}
				}
		}
		if($criterias[$i]=='Categories')
	     	{
				$strarr=$critarr["bannercategories"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
						$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live'  or BannerStatus='ready' or BannerStatus='booked') and BannerCategories Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							//echo $myrow["cnt"]."  ";	
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return("false");
							}
   	           				}		 		
		 			}
				}
		}
		if($criterias[$i]=='IndustryResman')
	     	{
				$strarr=$critarr["bannerresmanindtype"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
						$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerResmanIndustry Like '% $str %' and BannerId!='$banner'";
						echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
						//	echo $myrow["cnt"]."  ";	
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return("false");
							}
   	           				}	 		
		 			}
				}
		}
		if($criterias[$i]=='FareaResman')
	     	{
				$strarr=$critarr["bannerresmanfarea"];
				//print_r($strarr);	
		
				for($j=0;$j<count($strarr);$j++)
				{
		 			if($strarr[$j] && trim($strarr[$j])!="")
		 			{
		   				$str=trim($strarr[$j]);	
					        $sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerResmanFarea Like '% $str %' and BannerId!='$banner'";
					        echo "<!--$sql-->";
		   				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   				if($myrow=mysql_fetch_array($result))
		   				{
							//echo $myrow["cnt"]."  ";	
							if($myrow["cnt"]>=$maxbansinrot)
							{
				
								return("false");
							}
   	           				}
		 			}
				}
	        }
		if($criterias[$i]=='Age')
		{
			$minage=$critarr["banneragemin"];
			$maxage=$critarr["banneragemax"];
			$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and ( $maxage >= `BannerAgeMin` AND $minage <= `BannerAgeMax`) and BannerId!='$banner'";
			echo "<!--$sql-->";
			$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
			if($myrow=mysql_fetch_array($result))
			{
				//echo $myrow["cnt"]."  ";	
				if($myrow["cnt"]>=$maxbansinrot)
				{
					return("false");
				}
			}		 		
		}
		if($criterias[$i]=='Gender')
		{
			$gender=$critarr["bannergender"];
			$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and ( BannerGender='$gender') and BannerId!='$banner'";
                                echo "<!--$sql-->";
                                $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                if($myrow=mysql_fetch_array($result))
                                {	
                                        if($myrow["cnt"]>=$maxbansinrot)
                                        {
                                                return("false");
                                        }
                                }
                }
	 	if($criterias[$i]=='Ctc')
	     	{
			$ctc=$critarr["bannerctc"];
			for ($i = 0;$i<count($ctc);$i++)
			{
				if ($ctc[$i] && trim($ctc[$i]) !== "")
				{
					$ctcval=trim($ctc[$i]);
					$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (`BannerCTC` like '% $ctcval %') and BannerId!='$banner'";
					echo "<!--$sql-->";
					$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));

					if($myrow=mysql_fetch_array($result))
					{
						if($myrow["cnt"]>=$maxbansinrot)
						{
							return("false");
						}
					}
				}
			}
		}
	    }
	}
	else 
	{
		$sql="Select count(*) as cnt from bms2.BANNERCOPY where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerDefault='Y' and BannerId!='$banner'";
		echo "<!--$sql-->";
		$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		if($myrow=mysql_fetch_array($result))
		{
			if($myrow["cnt"]>=$maxbansinrot)
			{
					return("false");
			}
		}
	}
 }
 
function getBanDetails($bannerid) // returns all the details of a particular banner
{
	global $dbbms;
	$sql="select * from bms2.BANNERCOPY where BannerId='$bannerid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_bannerdetails.php:getBannerDetails :2: Could not get banner details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$bannerdetails["bannerid"]=$myrow["BannerId"];
		$bannerdetails["bannerzoneid"]=$myrow["ZoneId"];
		$bannerdetails["bannerclass"]=$myrow["BannerClass"];
		$bannerdetails["bannerstatic"]=$myrow["BannerStatic"];
		$bannerdetails["bannerstartdt"]=$myrow["BannerStartDate"];
		$bannerdetails["bannerenddt"]=$myrow["BannerEndDate"];
		$bannerdetails["bannerstatus"]=$myrow["BannerStatus"];
		$bannerdetails["bannerfeatures"]=$myrow["BannerFeatures"];
		$bannerdetails["bannergif"]=$myrow["BannerGif"];
		$bannerdetails["bannerurl"]=$myrow["BannerUrl"];
		$bannerdetails["bannerweightage"]=$myrow["BannerWeightage"];
		$bannerdetails["bannerdefault"]=$myrow["BannerDefault"];
		$bannerdetails["bannerpriority"]=$myrow["BannerPriority"];
		$bannerdetails["bannerstring"]=$myrow["BannerString"];
		$bannerdetails["bannerfreeorpaid"]=$myrow["BannerFreeOrPaid"];
		$bannerdetails["bannerintext"]=$myrow["BannerInternalOrExternal"];
		$bannerdetails["mailerid"]=$myrow["MailerId"];
		$bannerdetails["campaignid"]=$myrow["CampaignId"];
		$bannerdetails["bannerkeyword"]=trim(str_replace("#"," ",$myrow["BannerKeyword"])); 
		$bannerdetails["bannerkeystype"]=$myrow["BannerKeysType"];
		$bannerdetails["bannerlocation"]=trim(str_replace("#"," ",$myrow["BannerLocation"]));
		$bannerdetails["bannerip"]=$myrow["BannerCity"];
		$bannerdetails["bannerctc"]=$myrow["BannerCTC"];
		$bannerdetails["banneragemin"]=$myrow["BannerAgeMin"]; 
		$bannerdetails["banneragemax"]=$myrow["BannerAgeMax"];
		$bannerdetails["bannergender"]=$myrow["BannerGender"];
		list($bannerdetails["bannerstartyear"],$bannerdetails["bannerstartmonth"],$bannerdetails["bannerstartday"])=explode("-",$myrow["BannerStartDate"]);
		list($bannerdetails["bannerendyear"],$bannerdetails["bannerendmonth"],$bannerdetails["bannerendday"])=explode("-",$myrow["BannerEndDate"]);
		$i++;
	}
	return $bannerdetails;	
}

function getCritArray($bannerid)
{
   $bannerdetails=getBanDetails($bannerid);
   $criteriavaluesarr["zoneid"]=$bannerdetails["bannerzoneid"];
   $criteriavaluesarr["bannerid"]=$bannerdetails["bannerid"];
   $criteriavaluesarr["bannerstartdate"]=$bannerdetails["bannerstartdt"];
   $criteriavaluesarr["bannerenddate"]=$bannerdetails["bannerenddt"];
   $criteriavaluesarr["bannerdefault"]=$bannerdetails["bannerdefault"];
   $criteriavaluesarr["bannerstatic"]=$bannerdetails["bannerstatic"];
   $criteriavaluesarr["bannerurl"]=$bannerdetails["bannerurl"];
   $criteriavaluesarr["bannergif"]=$bannerdetails["bannergif"];
   $criteriavaluesarr["mailerid"]=$bannerdetails["mailerid"];
   $criteriavaluesarr["bannerclass"]=$bannerdetails["bannerclass"];
   $criteriavaluesarr["bannerintext"]=$bannerdetails["bannerintext"];
   $criteriavaluesarr["bannerfeaturelist"]=$bannerdetails["bannerfeatures"];
   $criteriavaluesarr["campaignid"]=$bannerdetails["campaignid"];
   $criteriavaluesarr["bannerweightage"]=$bannerdetails["bannerweightage"];
   $criteriavaluesarr["bannerpriority"]=$bannerdetails["bannerpriority"];

   if($bannerdetails["bannerkeyword"]!="")
	$criteriavaluesarr["criteriaarr"][]="Keywords";
   $criteriavaluesarr["bannerkeyword"]=$bannerdetails["bannerkeyword"];
   $criteriavaluesarr["bannerkeystype"]=$bannerdetails["bannerkeystype"];

   if($bannerdetails["bannerlocation"]!="")
       $criteriavaluesarr["criteriaarr"][]="Location";
   $criteriavaluesarr["bannerlocation"]=$bannerdetails["bannerlocation"];

   if($bannerdetails["bannerip"]!="")
   {
	$criteriavaluesarr["criteriaarr"][]="IP";
	$criteriavaluesarr["bannerip"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerip"]))));
   }
   else
       $criteriavaluesarr["bannerip"]="";

   if($bannerdetails["bannerctc"]!="")
   {
	$criteriavaluesarr["criteriaarr"][]="Ctc";
	$criteriavaluesarr["bannerctc"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerctc"]))));
   }
   else
       $criteriavaluesarr["bannerctc"]="";

   if($bannerdetails["banneragemin"] >= 0 && $bannerdetails["banneragemax"] >= 0)
	$criteriavaluesarr["criteriaarr"][]="Age";
   	$criteriavaluesarr["banneragemin"]=$bannerdetails["banneragemin"];
   	$criteriavaluesarr["banneragemax"]=$bannerdetails["banneragemax"];

   if($bannerdetails["bannergender"]!="")
	$criteriavaluesarr["criteriaarr"][]="Gender";
        $criteriavaluesarr["bannergender"]=$bannerdetails["bannergender"];

    return $criteriavaluesarr;
}
?>
