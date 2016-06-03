<?php

/*************************bms_advbannerdetails.php***********************************/
/*
	*	Created By		:	Abhinav	Katiyar
	*	Last Modified By   	:	Abhinav Katiyar
	*	Description        	:	This file is used to add/modify the 
						details of a banner.
	*	Includes/Libraries	:	bms_connect.php
   						bms_functions.php
*/


include("./includes/bms_connect.php");
include("bms_functions.php");
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}


$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
$_MAXLIMITSINGLECRITERIA=2;
$_MAXLIMITTOTALCRITERIA=2;
/*$bannerstatusarr=array("wait"=>array("new","cancel"),
						"new"=>array("live","cancel"),
						"live"=>array(),
						"cancel"=>array("wait")
						);
*/						
/* fetches criteria names corresponding to a zone
			input : zoneid
			output: comma separated criterias
*/
function getZoneCriteriaNames($zoneid)
{
	global $dbbms;
echo	$sql="Select ZoneCriterias from ZONE where ZONE.ZoneID='$zoneid'";    
    $result=mysql_query($sql,$dbbms) or logErrorBms("bms_bannerdetails.php:getZoneCriteriaNames :1: Could not get zone criteria names. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	print_r($myrow["ZoneCriterias"]);
	return $myrow["ZoneCriterias"];

}				


/*   returns an array of banner class to which a particular banner class is allowed to be changed to
				input: bannerclass
				output: array of banner classes
*/
function getBannerClass($bannerclass)
{
	$bannerclassarr=array("Image"=>array("Image","Flash"),
						"Flash"=>array("Image","Flash"),
						"Popup"=>array("Popup"),
						"PopUnder"=>array("PopUnder"),
						"Mailer"=>array("Mailer")
						);
	return $bannerclassarr["$bannerclass"];

}

/*   returns an array of allowed values of BannerDefault field in banner table
				input:none
				output:array of banner default values
*/ 
function getBannerDefault()
{
	$bannerdefaultarr=array("Y","N");
	return $bannerdefaultarr;
}
/*   returns an array of allowed values of BannerStatic field in banner table
				input:none
				output:array of banner static values
*/ 
function getBannerStatic()
{
	$bannerstaticarr=array("Y","N");
	return $bannerstaticarr;
}
/*   returns an array of allowed values of BannerFreeOrPaid field in banner table
				input:none
				output:array of banner free or paid values
*/ 
function getBannerFreePaid()
{
	$bannerfreepaidarr=array("Free","Paid");
	return $bannerfreepaidarr;
}
/*   returns an array of allowed values of BannerIntExt(Internal or External) field in banner table
				input:none
				output:array of banner internal/external values
*/ 
function getBannerIntExt()
{
	$bannerintextarr=array("0"=>array("name"=>"Internal","value"=>"I"),
							"1"=>array("name"=>"External","value"=>"E")
						);
	return  $bannerintextarr;

}
/*   returns an array of allowed values of BannerKeysType field in banner table
				input:none
				output:array of banner keystype values
*/ 
/*function getBannerKeysType()
{
	$bannerkeysarr=array('and','or');
	return $bannerkeysarr;
}
*/
function getBannerKeysType($bannerkeystype)
{
	$bannerkeysarr=array("0"=>array("value"=>"and","label"=>"and","selected"=>""),
						 "1"=>array("value"=>"or","label"=>"or","selected"=>"")
						);
	foreach($bannerkeysarr as $bannerkey=>$bannervalue)
	{
		if($bannervalue["value"]==$bannerkeystype)
		{
			$bannerkeysarr[$bannerkey]["selected"]="selected";
		}
	}
	return $bannerkeysarr;
}
/* checks the fields of a form
			input : regionid, zoneid, start date, enddate
			output: true- if form correct
			 	  : false- if form incorrect 
*/
function checkForm($region,$zone,$startdate,$enddate)
{

	global $smarty,$_TPLPATH;
	$check=1;
	if($startdate>$enddate)
	{
		$errormsg="Please enter correct dates. ";
		$check=0;
	}
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
	 	return false;
	}
	else 
		return true;
}

/* 
	shows populated banner details form 
	input	:	array of banner details, zoneid
	output	:	none
*/
function showBannerDetailsForm($bannerdetails,$zoneid)
{

	global $smarty,$_TPLPATH;
/*	$smarty->assign("bannerkeytypearr",getBannerKeysType($bannerdetails["bannerkeystype"]));
	$smarty->assign("exparr",getExp());
	$smarty->assign("agearr",getAge());
	$smarty->assign("fareaarr",getSearchFareaBms("bannerfarea",$bannerdetails["bannerfarea"],'','3',''));
	$smarty->assign("ctcarr",getCtc());
	$smarty->assign("indtypearr",getIndtypeBms($bannerdetails["bannerindtype"]));
	$smarty->assign("categoryarr",getCategories($bannerdetails["bannercategories"]));
	$smarty->assign("resmanfareaarr",getFareaBms($bannerdetails["bannerresmanfarea"]));
	$smarty->assign("resmanindtypearr",getIndtypeBms($bannerdetails["bannerresmanindustry"]));
*/	$smarty->assign("bannerpriorityarr",getBannerPriority($zoneid,$bannerdetails["bannerpriority"]));

	$smarty->assign("ipcitiesarr",getIpCity($bannerdetails["bannerip"]));

}


/* used to execute query for saving banner details
			input : sql query
			output: none
*/
function saveAdvBannerDetails($sqlsavebanner)
{	echo "<!--$sqlsavebanner-->";
	global $dbbms;
	$res=mysql_query($sqlsavebanner,$dbbms) or logErrorBms("bms_bannerdetails.php:saveAdvBannerDetails :5: Could not save banner details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		
}

/* checks the validity of details filled in a form
			input : array of banner details
			output: true -  if details correct
				  : false - if details incorrect
*/
function getCheckAdvBannerDetails($criteriavaluesarr)
{
	global $smarty,$_MAXLIMITSINGLECRITERIA,$_MAXLIMITTOTALCRITERIA,$_TPLPATH;
	$inputcriteriaarr=$criteriavaluesarr["criteriaarr"];
	$check=1;
	$sql="";
	if($criteriavaluesarr["bannerdefault"]!="Y")
	{
		if(count($inputcriteriaarr)>$_MAXLIMITTOTALCRITERIA)
		{
			$errormsg="Please enter only two";
			$check=0;
		}
		elseif(count($inputcriteriaarr)>$_MAXLIMITTOTALCRITERIA)
		{
			$errormsg="Please enter at least one";
			$check=0;
		}
		if(in_array("Keywords",$inputcriteriaarr))
		{
			if(trim($criteriavaluesarr["bannerkeyword"])&&$criteriavaluesarr["bannerkeystype"])
			{
				$keywordstr=parseKeywords($criteriavaluesarr["bannerkeyword"]);
				$keywordstr=str_replace(","," , ",$keywordstr);
				$keywordstr="# ".$keywordstr." #";
				$updateclause.=" BannerKeyword='$keywordstr' , BannerKeystype='$criteriavaluesarr[bannerkeystype]' , ";
				
				//$cnfrmmsg.="You have selected Bannerkeyword as \"$criteriavaluesarr[bannerkeyword]\" and BannerKeysType  as \"$criteriavaluesarr[bannerkeystype]\".";
			}
			else
			{
				$errormsg="Please enter correct values of banner keywords an keystype.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerKeyword='' , BannerKeystype='' , ";
			
			
		}
		if(in_array("Farea",$inputcriteriaarr))
		{
			if(count($criteriavaluesarr["bannerfarea"])>0&&count($criteriavaluesarr["bannerfarea"])<=$_MAXLIMITSINGLECRITERIA)
			{
				
				$fareastr=implode(" , ",$criteriavaluesarr["bannerfarea"]);
				
				//$cnfrmmsg.="You have selected BannerFarea as \"$fareastr\".";
				
				$fareastr="# ".$fareastr." #";
				$updateclause.=" BannerFarea='$fareastr' , ";
				
				
			}
			else
			{
				$errormsg="Please enter correct values of banner farea.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerFarea='' , ";
		}
		if(in_array("Industry",$inputcriteriaarr))
		{	if(count($criteriavaluesarr["bannerindtype"])>0&&count($criteriavaluesarr["bannerindtype"])<=$_MAXLIMITSINGLECRITERIA)
			{
				$indstr=implode(" , ",$criteriavaluesarr["bannerindtype"]);
				//$cnfrmmsg.="You have selected BannerIndustry as \"$indstr\".";
				$indstr="# ".$indstr." #";
				$updateclause.=" BannerIndtype='$indstr' , ";
				
				
			
			}
			else
			{
				$errormsg="Please enter correct values of banner industry.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerIndtype='' , ";
		}
		if(in_array("Location",$inputcriteriaarr))
		{
			if(trim($criteriavaluesarr["bannerlocation"]))
			{
				$locationstr=parseKeywords($criteriavaluesarr["bannerlocation"]); 
				$locationstr=str_replace(","," , ",$locationstr);
				$locationstr="# $locationstr #";
				$updateclause.=" BannerLocation='$locationstr' , ";
				
				//$cnfrmmsg.="You have selected BannerLocation as \"$criteriavaluesarr[bannerlocation]\".";
			}
			else
			{
				$errormsg="Please enter correct values of banner location.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerLocation='' , ";
		}
		if(in_array("Exp",$inputcriteriaarr))
		{
			if($criteriavaluesarr["bannerexpmin"]>=0&&$criteriavaluesarr["bannerexpmax"]>=0&&($criteriavaluesarr["bannerexpmin"]<=$criteriavaluesarr["bannerexpmax"]))
			{
				$updateclause.=" BannerExpMin='$criteriavaluesarr[bannerexpmin]' , BannerExpMax='$criteriavaluesarr[bannerexpmax]' ,";
				
				//$cnfrmmsg.="You have selected BannerExpMin as \"$criteriavaluesarr[bannerexpmin]\" and  BannerExpMax as \"$criteriavaluesarr[bannerexpmax]\" .";
			}
			else
			{
				$errormsg="Please enter correct values of banner expmin and banner exp max.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerExpMin='-1' , BannerExpMax='-1' ,";
		}
		if(in_array("Categories",$inputcriteriaarr))
		{
			if($criteriavaluesarr["bannercategories"]&&count($criteriavaluesarr["bannercategories"])<=$_MAXLIMITSINGLECRITERIA)
			{
				
				$categorystr=implode(" , ",$criteriavaluesarr["bannercategories"]);
				//$cnfrmmsg.="You have selected Banner category as \"$categorystr\".";
				$categorystr="# ".$categorystr." #";
				$updateclause.=" BannerCategories='$categorystr' , ";
				
			}
			else
			{
				$errormsg="Please enter correct values of banner categories.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerCategories='' ,";
		}
		
		if(in_array("IP",$inputcriteriaarr))
		{
				if(count($criteriavaluesarr["bannerip"])>0&&count($criteriavaluesarr["bannerip"])<=$_MAXLIMITSINGLECRITERIA)
			{
				$ipstr=implode(" , ",$criteriavaluesarr["bannerip"]);
				//$cnfrmmsg.="You have selected BannerIP as \"$ipstr\".";
				$iplocationstr=getIpLoc($criteriavaluesarr["bannerip"]);
				$ipstr="# ".$ipstr." #";
				$iplocationstr="# ".$iplocationstr." #";
				$updateclause.=" BannerIP='$iplocationstr' , BannerCity='$ipstr' , ";
				
				
			
			}
			else
			{
				$errormsg="Please enter correct values of banner ip.";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerIP='' , BannerCity='' , ";
		}
		if(in_array("Ctc",$inputcriteriaarr))
		{
			if($criteriavaluesarr["bannerctcmin"]>=0&&$criteriavaluesarr["bannerctcmax"]>=0&&($criteriavaluesarr["bannerctcmin"]<=$criteriavaluesarr["bannerctcmax"]))
			{
				$updateclause.=" BannerCtcMin='$criteriavaluesarr[bannerctcmin]' , BannerCtcMax='$criteriavaluesarr[bannerctcmax]' , ";
				//$cnfrmmsg.="You have selected BannerCtcMin as \"$criteriavaluesarr[bannerctcmin]\" and BannerCtcMax as \"$criteriavaluesarr[bannerctcmax]\".";
			}
			else
			{
				$errormsg="Please enter correct values of banner ctc";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerCtcMin='-1' , BannerCtcMax='-1' , ";
		}
		if(in_array("Age",$inputcriteriaarr))
		{
			if($criteriavaluesarr["banneragemin"]>=0&&$criteriavaluesarr["banneragemax"]>=0&&($criteriavaluesarr["banneragemin"]<=$criteriavaluesarr["banneragemax"]))
			{
				$updateclause.=" BannerAgeMin='$criteriavaluesarr[banneragemin]' , BannerAgeMax='$criteriavaluesarr[banneragemax]' ,";
				//$cnfrmmsg.="You have selected BannerAgeMin as \"$criteriavaluesarr[banneragemin]\" and BannerAgeMax as \"$criteriavaluesarr[banneragemax]\".";
				
			}	
			else
			{
				$errormsg="Please enter correct values of banner age";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerAgeMin='-1' , BannerAgeMax='-1' ,";
		}
		if(in_array("Gender",$inputcriteriaarr))
		{
			if($criteriavaluesarr["bannergender"])
			{
				$updateclause.=" BannerGender='$criteriavaluesarr[bannergender]' , ";
				
				//$cnfrmmsg.="You have selected BannerGender as \"$criteriavaluesarr[bannergender]\" .";
			}	
			else
			{
				$errormsg="Please enter correct values of banner age";
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerGender='' , ";
		}
		if(in_array("ExpResman",$inputcriteriaarr))
		{
			if($criteriavaluesarr["bannerresmanexpmin"]>=0&&$criteriavaluesarr["bannerresmanexpmax"]>=0&&($criteriavaluesarr["bannerresmanexpmin"]<=$criteriavaluesarr["bannerresmanexpmax"]))
			{
				$updateclause.=" BannerResmanExpMin='$criteriavaluesarr[bannerresmanexpmin]' , BannerResmanExpMax='$criteriavaluesarr[bannerresmanexpmax]' ,";
				
				//$cnfrmmsg.="You have selected BannerResmanExpMin as \"$criteriavaluesarr[bannerresmanexpmin]\" and  BannerResmanExpMax as \"$criteriavaluesarr[bannerresmanexpmax]\" .";
			}
			else
			{
				$errormsg="Please enter correct values of banner resman expmin and banner resman exp max.".$criteriavaluesarr["$bannerresmanexpmin"].$criteriavaluesarr["$bannerresmanexpmax"];
				$check=0;
			}
		}
		else
		{
			$updateclause.=" BannerResmanExpMin='-1' , BannerResmanExpMax='-1' ,";
		}
		if(in_array("IndustryResman",$inputcriteriaarr))
		{
			if(count($criteriavaluesarr["bannerresmanindtype"])>0&&count($criteriavaluesarr["bannerresmanindtype"])<=$_MAXLIMITSINGLECRITERIA)
			{
				$indstr=implode(" , ",$criteriavaluesarr["bannerresmanindtype"]);
				//$cnfrmmsg.="You have selected BannerResmanIndustry as \"$indstr\".";
				
				$indstr="# ".$indstr." #";
				$updateclause.=" BannerResmanIndustry='$indstr' , ";
			}
			else
			{
				$errormsg="Please enter correct values of banner industry.";
				$check=0;
			}
		
		}
		else
		{
			$updateclause.=" BannerResmanIndustry='' , ";
		}
		if(in_array("FareaResman",$inputcriteriaarr))
		{
			if(count($criteriavaluesarr["bannerresmanfarea"])>0&&count($criteriavaluesarr["bannerresmanfarea"])<=$_MAXLIMITSINGLECRITERIA)
			{
				
				$fareastr=implode(" , ",$criteriavaluesarr["bannerresmanfarea"]);
			
				//$cnfrmmsg.="You have selected BannerResmanfarea as \"$fareastr\".";
				
				$fareastr="# ".$fareastr." #";
				$updateclause.=" BannerResmanFarea='$fareastr' , ";
			}
			else
			{
				$errormsg="Please enter correct values of banner farea.";
				$check=0;
			}
		
		}
		else
		{
			$updateclause.=" BannerResmanFarea='' , ";
		}
		
	}
	else
	{
		$updateclause.=" BannerKeyword='' , BannerKeystype='',BannerFarea='',BannerIndtype='',BannerLocation='',BannerIP='',BannerCity='',BannerCtcMin='-1',BannerCtcMax='-1',BannerAgeMin='-1',  BannerAgeMax='-1', BannerGender='', BannerResmanExpMin='-1', BannerResmanExpMax='-1' ,BannerResmanIndustry='' ,BannerResmanFarea='' , BannerExpMin='-1',BannerExpMin='-1' , ";
	}
	
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
		return 0;
	}
	else
	{
		$bannerstring="bnrstr".$criteriavaluesarr["zoneid"]."p".$criteriavaluesarr["bannerpriority"];
		$updateclause.=" BannerPriority='$criteriavaluesarr[bannerpriority]' , BannerWeightage ='$criteriavaluesarr[bannerweightage]',  ZoneId='$criteriavaluesarr[zoneid]' , BannerDefault='$criteriavaluesarr[bannerdefault]', BannerClass='$criteriavaluesarr[bannerclass]', BannerStartDate='$criteriavaluesarr[bannerstartdate]', BannerEndDate='$criteriavaluesarr[bannerenddate]' , BannerGif='$criteriavaluesarr[bannergif]', Bannerurl='$criteriavaluesarr[bannerurl]', BannerInternalOrExternal='$criteriavaluesarr[bannerintext]',BannerFeatures='$criteriavaluesarr[bannerfeaturelist]' ,BannerStatic='$criteriavaluesarr[bannerstatic]' , BannerString='$bannerstring' ";
		$sql="update bms2.BANNER set ".$updateclause." where BannerId='$criteriavaluesarr[bannerid]'";
		//$smarty->assign("cnfrmmsg",$cnfrmmsg);
		return $sql;
	}
		
}

/*function checkChangedOrNot($bannerid,$criteriavaluesarr)
{
	$bannerdetails=getBannerDetails($bannerid);
	//echo "<br /><br />";
	//print_r($bannerdetails);
	//if(($banneroldstartdate!=$bannerstartdate)||($banneroldenddate!=$bannerenddate))
	//	return true;
	//else 
	if($bannerdetails["bannerzoneid"]!=$criteriavaluesarr["zoneid"]||$bannerdetails["bannerstartdate"]!=$criteriavaluesarr["bannerstartdt"]||$bannerdetails["bannerenddt"]!=$criteriavaluesarr["bannerenddate"]||$bannerdetails["bannerdefault"]!=$criteriavaluesarr["bannerdefault"]||$bannerdetails["bannerpriority"]!=$criteriavaluesarr["bannerpriority"])
	{
	//if($bannerdetails["bannerzoneid"]!=$criteriavaluesarr["zoneid"])
		//echo "cauhet";
		//if($bannerdetails["bannerstartdate"]!=$criteriavaluesarr["bannerstartdate"])
		//{
		//echo "cauhet";
		//}	
		return true;	
	}
elseif($bannerdetails["bannerkeyword"]!=$criteriavaluesarr["bannerkeyword"]||$bannerdetails["bannerkeystype"]!=$criteriavaluesarr["bannerkeystype"]||$bannerdetails["bannerlocation"]!=$criteriavaluesarr["bannerlocation"]||$bannerdetails["bannerexpmin"]!=$criteriavaluesarr["bannerexpmin"]||$bannerdetails["bannerexpmax"]!=$criteriavaluesarr["bannerexpmax"]||$bannerdetails["bannerresmanexpmin"]!=$criteriavaluesarr["bannerresmanexpmin"]||$bannerdetails["bannerresmanexpmax"]!=$criteriavaluesarr["bannerresmanexpmax"]||$bannerdetails["bannerctcmin"]!=$criteriavaluesarr["bannerctcmin"]||$bannerdetails["bannerctcmax"]!=$criteriavaluesarr["bannerctcmax"]||$bannerdetails["banneragemin"]!=$criteriavaluesarr["banneragemin"]||$bannerdetails["banneragemax"]!=$criteriavaluesarr["banneragemax"]||$bannerdetails["bannergender"]!=$criteriavaluesarr["bannergender"])
{echo "second";
	
	return true;
}
else return false;
return true;
	
}
*/
function getIpLoc($banneriparr)
{
	global $dbbms;
	$banneripstr=implode("','",$banneriparr);
	$banneripstr="('".$banneripstr."')";
	$ipcitiesstr="";
	$sql="select LocIds from bms2.IPCITIES where CityId in $banneripstr";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_advbannerdetails.php :getIpLoc:1: Could not select ip cities<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	while($myrow=mysql_fetch_array($res))
	{
		$ipcitiesstr.=str_replace(","," , ",trim($myrow["LocIds"]))." , ";
	}
	$ipcitiesstr=substr($ipcitiesstr,0,-3);
	return $ipcitiesstr;
}
function checkStatus($bannerid)
{
	$checkstatus="checkstatus";
	include("bms_checklive.php");
	if($newstatus)
		return $newstatus;
	else
		return 0;
}

if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	$smarty->assign("bannerid",$bannerid);
	if($saveadvbannerdetails_x||$saveadvbannerdetails||$savebasicdetails_x)
	{
		$check=true;
		while (list($key,$value) = each($_POST))
		{
			if(!$savebasicdetails_x)
			{
				$criteriavaluesarr["$key"]=$value;
				if($key!="id"&&$key!="campaignid"&&$key!="bannerid")
					$value=rawurlencode(serialize($value));
				$smarty->assign("$key",$value);
				
			}
			else
			{
				if($key!="id"&&key!="campaignid"&&key!="bannerid")			
					$value=unserialize(rawurldecode($value));
				$criteriavaluesarr["$key"]=$value;
				
				$smarty->assign("$key",$value);
			}
		}
		$criteriavaluesarr["bannerid"]=$_POST["bannerid"];
		if(!$savebasicdetails_x)
		{
			if(checkIfAvail($criteriavaluesarr))
				$check=true;
			else
				$check=false;
		}		
				
		if($check)
		{
			$sqlsavebanner=getCheckAdvBannerDetails($criteriavaluesarr);
			if($sqlsavebanner)
			{
				saveAdvBannerDetails($sqlsavebanner);
				if(!$savebasicdetails_x)
				{
					$newstatus=checkStatus($criteriavaluesarr["bannerid"]);
					if($newstatus)
					{
						$cnfrmmsg="The Banner status is '$newstatus' and the Campaign is live<BR />.";
						updateBannerStatus($bannerid,$newstatus);
						ChangeCampaignStatus($campaignid,"active");
					}
				}
				else
					$cnfrmmsg="The banner details have saved.";
				$showcriterias=showcriterias($bannerid);
				$cnfrmmsg.="You have seleted the following criterias:".$showcriterias["criteria"];
				$cnfrmmsg.="<BR />Please <a href=\"bms_campaigndetails.php?id=$id&campaignid=$campaignid\">click here</a> to go back to campaign details.";
				$smarty->assign("cnfrmmsg",$cnfrmmsg);
				$smarty->display("./$_TPLPATH/bms_confirmation.htm");
			}
			else
				$smarty->display("./$_TPLPATH/bms_error.htm");
		}
		else
		{
			$smarty->assign("errormsg","Due to non-availability of the banner your request to book the banner cannot be processed.Click on the button below to save the details for future.");
			$smarty->display("./$_TPLPATH/bms_bookingerror.htm");
		}
		
		exit;
		
echo "1";		
	}
	else
	{
echo "2";
		$bannerstartdate=$startyear."-".$startmonth."-".$startday;
		$bannerenddate=$endyear."-".$endmonth."-".$endday;
		$zonearr=explode("|ZID|",$zone);
		$zoneid=$zonearr[0];
		echo $zonecriteriastr=$zonearr[1];
		if($bannerdefault!='Y')
			$bannerdefault='N';
		$smarty->assign("bannerstartdate",$bannerstartdate);
		$smarty->assign("bannerenddate",$bannerenddate);
		$smarty->assign("zoneid",$zoneid);
		$smarty->assign("bannerdefault",$bannerdefault);
		$smarty->assign("bannerclass",$bannerclass);
		$smarty->assign("bannergif",$bannergif);
		$smarty->assign("bannerurl",$bannerurl);
		$smarty->assign("bannerintext",$bannerintext);
		$smarty->assign("bannerfeaturelist",$bannerfeaturelist);
		$smarty->assign("bannerstatic",$bannerstatic);
		$smarty->assign("bannerstatus",$bannerstatus);
		
		if($bannerdefault!='Y')
		{
			if($zonecriteriastr)
			{
				$zonecriteriaarr=explode(",",$zonecriteriastr);
				foreach($zonecriteriaarr as $zonecriterias)
				{
					echo $zonecriterias;
					$smarty->assign("$zonecriterias",$zonecriterias);
				}
				$smarty->assign("bannerdefault","N");
			}
			else
			{
				$bannerdefault='Y';
				$smarty->assign("cnfrmmsg","No criteria zone. Please select priority and weight and continue.");
			
			}
		}
		else
		{	
			$smarty->assign("cnfrmmsg","Default banner. No criteria to be filled.Please select priority and weight and continue.");
			
		}
		
		$smarty->assign("campaignid",$campaignid);
		$smarty->assign("bannerdefault",$bannerdefault);
		$bannerdetails=getBannerDetails($bannerid);
		$smarty->assign("bannerdetails",$bannerdetails);
		showBannerDetailsForm($bannerdetails,$zoneid);
		$smarty->display("./$_TPLPATH/bms_advbannerdetails.htm");
	}
}
else
{
	TimedOutBms();
}
?>
