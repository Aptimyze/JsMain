<?php

/************************************************************bms_advbannerdetails.php***************************************/
/*
	*	Created By		:	Abhinav	Katiyar
	*	Last Modified By   	:	Abhinav Katiyar
	*	Description        	:	This file is used to add/modify the 
						details of a banner.
	*	Includes/Libraries	:	bms_connect.php
   						bms_functions.php
****************************************************************************************************************************/

include("./includes/bms_connect.php");
include("bms_functions.php");
include_once("bmsArrayJs.php");
//print_r($_POST);

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
$_MAXLIMITSINGLECRITERIA=2;
$_MAXLIMITTOTALCRITERIA=10;

/*************************************************************************************
	fetches criteria names corresponding to a zone
	input : zoneid
	output: comma separated criterias
**************************************************************************************/
function getZoneCriteriaNames($zoneid)
{
	global $dbbms;
	$sql="Select ZoneCriterias from ZONE where ZONE.ZoneID='$zoneid'";    
	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_bannerdetails.php:getZoneCriteriaNames :1: Could not get zone criteria names. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	return $myrow["ZoneCriterias"];
}				

/*************************************************************************************
	returns an array of banner class to which a particular banner 
	class is allowed to be changed to
	input: bannerclass
	output: array of banner classes
**************************************************************************************/
function getBannerClass($bannerclass)
{
	$bannerclassarr=array("Image"=>array("Image","Flash"),
						"textlink"=>array("Image","Flash","textlink"),
						"Flash"=>array("Image","Flash"),
						"Popup"=>array("Popup"),
						"PopUnder"=>array("PopUnder"),
						"Mailer"=>array("Mailer")
						);
	return $bannerclassarr["$bannerclass"];
}

/**************************************************************************************
	returns an array of allowed values of BannerDefault field in banner table
	input:none
	output:array of banner default values
**************************************************************************************/ 
function getBannerDefault()
{
	$bannerdefaultarr=array("Y","N");
	return $bannerdefaultarr;
}

/***************************************************************************************
	returns an array of allowed values of BannerStatic field in banner table
	input:none
	output:array of banner static values
***************************************************************************************/
function getBannerStatic()
{
	$bannerstaticarr=array("Y","N");
	return $bannerstaticarr;
}

/***************************************************************************************
	returns an array of allowed values of BannerFreeOrPaid field in banner table
	input:none
	output:array of banner free or paid values
****************************************************************************************/ 
function getBannerFreePaid()
{
	$bannerfreepaidarr=array("Free","Paid");
	return $bannerfreepaidarr;
}

/****************************************************************************************
	returns an array of allowed values of BannerIntExt(Internal or External) field in banner table
	input:none
	output:array of banner internal/external values
*****************************************************************************************/ 
function getBannerIntExt()
{
	$bannerintextarr=array("0"=>array("name"=>"Internal","value"=>"I"),
			       "1"=>array("name"=>"External","value"=>"E")
				);
	return  $bannerintextarr;
}

/*****************************************************************************************
	returns an array of allowed values of BannerKeysType field in banner table
	input:none
	output:array of banner keystype values
******************************************************************************************/ 
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
/******************************************************************************************
	checks the fields of a form
	input : regionid, zoneid, start date, enddate
	output: true- if form correct
	      : false- if form incorrect 
******************************************************************************************/
function checkForm($region,$zone,$startdate,$enddate)
{
	global $smarty,$_TPLPATH;
	$check=1;

	if($startdate > $enddate)
	{
		$errormsg="Please enter correct dates. ";
		$check=0;
	}
	if($check == 0)
	{
		$smarty->assign("errormsg",$errormsg);
	 	return false;
	}
	else 
		return true;
}

/*******************************************************************************************
	shows populated banner details form 
	input	:	array of banner details, zoneid
	output	:	none
*******************************************************************************************/
function showBannerDetailsForm($bannerdetails,$zoneid)
{
	//print_r ($bannerdetails);
	global $smarty,$_TPLPATH;
	$smarty->assign("agearr",getAge());
	$smarty->assign("ctcarr",getCtc($bannerdetails["bannerctc"]));
	$smarty->assign("bannerpriorityarr",getBannerPriority($zoneid,$bannerdetails["bannerpriority"]));
	$smarty->assign("ipcitiesarr",getIpCity($bannerdetails["bannerip"]));
	$smarty->assign("loc_ctryarr",getLocCountry($bannerdetails["bannerloc_ctry"]));
	$smarty->assign("loc_Incityarr",getLocInCity($bannerdetails["bannerloc_incity"]));
	$smarty->assign("loc_Uscityarr",getLocUsCity($bannerdetails["bannerloc_uscity"]));
	$smarty->assign("memarr",getMEM($bannerdetails["bannermem"]));
	$smarty->assign("mstatusarr",getMStatus($bannerdetails["bannermstatus"]));
	$smarty->assign("relarr",getREL($bannerdetails["bannerrel"]));
	$smarty->assign("eduarr",getEDU($bannerdetails["banneredu"]));
	$smarty->assign("occarr",getOCC($bannerdetails["bannerocc"]));
	$smarty->assign("comarr",getCOM($bannerdetails["bannercom"]));
	$smarty->assign("propcityarr",getPROPCITY($bannerdetails["bannerpropcity"]));
	$smarty->assign("proptypearr",getPROPTYPE($bannerdetails["bannerproptype"]));
	$smarty->assign("propinrarr",getPROPBUDGET($bannerdetails["bannerpropinr"]));
	$smarty->assign("proprentinrarr",getPROPINRRENT($bannerdetails["bannerpropinr"]));
	$smarty->assign("bannerpropcat",$bannerdetails["bannerpropcat"]);

	//added by lavesh rawat
	global $vdArray,$profileStatus,$mailID,$eoiStatus,$registrationStatus,$ftoState_array,$ftoExpiry_array,$profileCompletionState_array;
	$smarty->assign("vdArr",getDropDownsArr($bannerdetails["bannerJsVd"],$vdArray));
	$smarty->assign("profileStatusArr",getDropDownsArr($bannerdetails["bannerJsProfileStatus"],$profileStatus));
	$smarty->assign("mailIDArr",getDropDownsArr($bannerdetails["bannerJsMailID"],$mailID));
	$smarty->assign("eoiStatusArr",getDropDownsArr($bannerdetails["bannerJsEoiStatus"],$eoiStatus));
	$smarty->assign("registrationStatusArr",getDropDownsArr($bannerdetails["bannerJsRegistrationStatus"],$registrationStatus));
	$smarty->assign("ftoStateArr",getDropDownsArr($bannerdetails["bannerJsFtoStatus"],$ftoState_array));
	$smarty->assign("ftoExpiryArr",getDropDownsArr($bannerdetails["bannerJsFtoExpiry"],$ftoExpiry_array));
	$smarty->assign("profileCompletionStateArr",getDropDownsArr($bannerdetails["bannerJsProfileCompletionState"],$profileCompletionState_array));
	//added by lavesh rawat
}

/******************************************************************************************
	used to execute query for saving banner details
	input : sql query
	output: none
*******************************************************************************************/
function saveAdvBannerDetails($sqlsavebanner)
{	echo "<!--$sqlsavebanner-->";
	global $dbbms;
	$res = mysql_query($sqlsavebanner,$dbbms) or logErrorBms("bms_bannerdetails.php:saveAdvBannerDetails :5: Could not save banner details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
}

/******************************************************************************************
	checks the validity of details filled in a form
	input : array of banner details
	output: true -  if details correct
	      : false - if details incorrect
*******************************************************************************************/
function getCheckAdvBannerDetails($criteriavaluesarr)
{
	global $smarty,$_MAXLIMITSINGLECRITERIA,$_MAXLIMITTOTALCRITERIA,$_TPLPATH;
	//print_r($criteriavaluesarr);
	$propcategory = $criteriavaluesarr["bannerpropcat"];
	$inputcriteriaarr=$criteriavaluesarr["criteriaarr"];
	$check=1;
	$sql="";

	if($criteriavaluesarr["bannerdefault"]!="Y" && $criteriavaluesarr["bannerfixed"]!="Y")
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
		if(in_array("Location",$inputcriteriaarr))
		{	
			if(count($criteriavaluesarr["bannerloc_country"])>0)//&&count($criteriavaluesarr["bannerloc_country"])<=$_MAXLIMITSINGLECRITERIA)
			{	
				if (count($criteriavaluesarr["bannerloc_country"]) > 1)
					$cntrystr = implode(" , ",$criteriavaluesarr["bannerloc_country"]);
				else
					$cntrystr = $criteriavaluesarr["bannerloc_country"][0];
				//echo "<br>"."COUNT".count($criteriavaluesarr["bannerloc_incity"]);
				if (count($criteriavaluesarr["bannerloc_incity"]) > 1)
					$incitystr = implode(" , ",$criteriavaluesarr["bannerloc_incity"]);
				else
					$incitystr = $criteriavaluesarr["bannerloc_incity"][0];
				if (count($criteriavaluesarr["bannerloc_uscity"]) > 1)
					$uscitystr = implode(" , ",$criteriavaluesarr["bannerloc_uscity"]);
				else
					$uscitystr = $criteriavaluesarr["bannerloc_uscity"][0];
				$locationstr = "# ".$cntrystr." |X| "." $incitystr"." $ ".$uscitystr." #";
					$updateclause.=" BannerLocation='$locationstr' , BannerCountry='$cntrystr' , BannerInCity='$incitystr', BannerUsCity='$uscitystr' , ";
				
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
			/**** added by shobha ***/
			if (count($criteriavaluesarr["bannerctc"]) >= 0 && count($criteriavaluesarr["bannerctc"])<=$_MAXLIMITSINGLECRITERIA)
			{
				//$updateclause.=" BannerCtcMin='$criteriavaluesarr[bannerctcmin]' , BannerCtcMax='$criteriavaluesarr[bannerctcmax]' , ";
				$ctcstr = "# ".implode(" , ",$criteriavaluesarr["bannerctc"])." #";
				$updateclause.=" BannerCTC='$ctcstr' , ";
				
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
			$updateclause.=" BannerCTC='' , ";
		}
		if(in_array("MEM",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannermem"]) >= 0 )//&& count($criteriavaluesarr["bannermem"])<=$_MAXLIMITSINGLECRITERIA)
                        {
                                //$updateclause.=" BannerCtcMin='$criteriavaluesarr[bannerctcmin]' , BannerCtcMax='$criteriavaluesarr[bannerctcmax]' , ";
                                $memstr = "# ".implode(" , ",$criteriavaluesarr["bannermem"])." #";
                                $updateclause.=" BannerMEM='$memstr' , ";
                                                                                                                            
                                //$cnfrmmsg.="You have selected BannerCtcMin as \"$criteriavaluesarr[bannerctcmin]\" and BannerCtcMax as \"$criteriavaluesarr[bannerctcmax]\".";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner membership";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerMEM='' , ";
                }
		if(in_array("MARITALSTATUS",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannermstatus"]) >= 0 )//&& count($criteriavaluesarr["bannermem"])<=$_MAXLIMITSINGLECRITERIA)
                        {
                                //$updateclause.=" BannerCtcMin='$criteriavaluesarr[bannerctcmin]' , BannerCtcMax='$criteriavaluesarr[bannerctcmax]' , ";
                                $mstatusstr = "# ".implode(" , ",$criteriavaluesarr["bannermstatus"])." #";
                                $updateclause.=" BannerMARITALSTATUS='$mstatusstr' , ";
                                                                                                                            
                                //$cnfrmmsg.="You have selected BannerCtcMin as \"$criteriavaluesarr[bannerctcmin]\" and BannerCtcMax as \"$criteriavaluesarr[bannerctcmax]\".";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner marital status";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerMARITALSTATUS='' , ";
                }
		if(in_array("REL",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannerrel"]) >= 0 )
                        {
                                $relstr = "# ".implode(" , ",$criteriavaluesarr["bannerrel"])." #";
                                $updateclause.=" BannerREL='$relstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner religion";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerREL='' , ";
                }
		if(in_array("OCC",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannerocc"]) >= 0 )
                        {
                                $occstr = "# ".implode(" , ",$criteriavaluesarr["bannerocc"])." #";
                                $updateclause.=" BannerOCC='$occstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner occupation";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerOCC='' , ";
                }
		if(in_array("EDU",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["banneredu"]) >= 0 )
                        {
                                $edustr = "# ".implode(" , ",$criteriavaluesarr["banneredu"])." #";
                                $updateclause.=" BannerEDU='$edustr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner education";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerEDU='' , ";
                }
		if(in_array("COM",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannercom"]) >= 0 )
                        {
                                $comstr = "# ".implode(" , ",$criteriavaluesarr["bannercom"])." #";
                                $updateclause.=" BannerCOM='$comstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner community";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerCOM='' , ";
                }
		if(in_array("PROPCITY",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannerpropcity"]) >= 0 )
                        {
                                $propcitystr = "# ".implode(" , ",$criteriavaluesarr["bannerpropcity"])." #";
                                $updateclause.=" BannerPROPCITY='$propcitystr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner city";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerPROPCITY='' , ";
                }

		if(in_array("PROPINR",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
			if ($propcategory == 'Buy')
			{
                        	if (count($criteriavaluesarr["bannerpropinr"]) > 0 )
                        	{
                                	$propinrstr = "# ".implode(" , ",$criteriavaluesarr["bannerpropinr"])." #";
                                	$updateclause.=" BannerPROPINR='$propinrstr' , BannerPROPCAT='$propcategory' ,";
                        	}
                        	else
                        	{
                                	$errormsg="Please enter correct values of banner price range";
                                	$check=0;
                        	}
			}
			elseif ($propcategory == 'Rent')
			{
				if (count($criteriavaluesarr["bannerproprentinr"]) > 0 )
                                {
                                        $propinrstr = "# ".implode(" , ",$criteriavaluesarr["bannerproprentinr"])." #";
                                        $updateclause.=" BannerPROPINR='$propinrstr' , BannerPROPCAT='$propcategory' ,";
                                }
                                else
                                {
                                        $errormsg="Please enter correct values of banner price range";
                                        $check=0;
                                }
			}
                }
                else
                {
                        $updateclause.=" BannerPROPINR='' , ";
                }
		if(in_array("PROPCAT",$inputcriteriaarr) && !(in_array("PROPINR",$inputcriteriaarr)))
                {
                        /**** added by shobha ***/
                        if ($criteriavaluesarr["bannerpropcat"])
                        {
                                $updateclause.=" BannerPROPCAT='$propcategory' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner property category";
                                $check=0;
                        }
                }

		if(in_array("PROPTYPE",$inputcriteriaarr))
                {
                        /**** added by shobha ***/
                        if (count($criteriavaluesarr["bannerproptype"]) >= 0 )
                        {
                                $proptypestr = "# ".implode(" , ",$criteriavaluesarr["bannerproptype"])." #";
                                $updateclause.=" BannerPROPTYPE='$proptypestr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner property type";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerPROPTYPE='' , ";
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
                if(in_array("SHIKSHA_COUNTRY",$inputcriteriaarr))
                {
                        if (count($criteriavaluesarr["shikshaCountry"]) > 0 )
                        {
                                $relstr = "# ". implode(" , ", $criteriavaluesarr["shikshaCountry"])." #";
                                $updateclause.=" shikshaCountry ='$relstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of shiksha country";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" shikshaCountry ='' , ";
                }
                if(in_array("SHIKSHA_CATEGORY",$inputcriteriaarr))
                {
                        if (count($criteriavaluesarr["shikshaCategory"]) >= 0 )
                        {
                                $relstr = "# ".implode(" , ",$criteriavaluesarr["shikshaCategory"])." #";
                                $updateclause.=" shikshaCategory='$relstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of shiksha country";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" shikshaCategory ='' , ";
                }
                if(in_array("SHIKSHA_CITY",$inputcriteriaarr))
                {
                        if (count($criteriavaluesarr["shikshaCities"]) >= 0 )
                        {
                                $relstr = "# ".implode(" , ",$criteriavaluesarr["shikshaCities"])." #";
                                $updateclause.=" shikshaCity ='$relstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of shiksha city";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" shikshaCity ='' , ";
                }

                if(in_array("SHIKSHA_KEYWORD",$inputcriteriaarr))
                {
                        if ($criteriavaluesarr["shikshaKeyword"] != '' )
                        {
                                $relstr = "# ".$criteriavaluesarr["shikshaKeyword"]." #";
                                $updateclause.=" shikshaKeyword ='$relstr' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of shiksha Keyword";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" shikshaKeyword='' , ";
                }


		//added by lavesh rawat
                if(in_array("vd",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["vd"])
			{
				$tempVal="# ".implode(" , ",$criteriavaluesarr["vd"])." #";
                                $updateclause.=" BannerJsVd='$tempVal' , ";
			}
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable Discount values";
                                $check=0;
                        }
                }
                else
                        $updateclause.=" BannerJsVd='' , ";


                if(in_array("profileStatus",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["profileStatus"])
			{
				$tempVal="# ".implode(" , ",$criteriavaluesarr["profileStatus"])." #";
                                $updateclause.=" BannerJsProfileStatus='$tempVal' , ";
			}
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable Profile Status values";
                                $check=0;
                        }
                }
                else
                        $updateclause.=" BannerJsProfileStatus='' , ";


                if(in_array("jsMailID",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsMailID"])
			{
				$tempVal="# ".implode(" , ",$criteriavaluesarr["jsMailID"])." #";
                                $updateclause.=" BannerJsMailID='$tempVal' , ";
			}
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable Gmail Id ";
                                $check=0;
                        }
                }
                else
                        $updateclause.=" BannerJsMailID='' , ";


                if(in_array("jsEoiStatus",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsEoiStatus"])
			{
				$tempVal="# ".implode(" , ",$criteriavaluesarr["jsEoiStatus"])." #";
                                $updateclause.=" BannerJsEoiStatus='$tempVal' , ";
			}
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable Eoi Status Values";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerJsEoiStatus='' , ";
                }
                if(in_array("jsRegistrationStatus",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsRegistrationStatus"])
                        {
                                $tempVal="# ".implode(" , ",$criteriavaluesarr["jsRegistrationStatus"])." #";
                                $updateclause.=" BannerJsRegistrationStatus='$tempVal' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable Eoi Status Values";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerJsRegistrationStatus='' , ";
                }

                if(in_array("jsFtoStatus",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsFtoStatus"])
                        {
                                $tempVal="# ".implode(" , ",$criteriavaluesarr["jsFtoStatus"])." #";
                                $updateclause.=" BannerJsFtoStatus='$tempVal' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable FTO Status Values";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerJsFtoStatus='' , ";
                }


                if(in_array("jsFtoExpiry",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsFtoExpiry"])
                        {
                                $tempVal="# ".implode(" , ",$criteriavaluesarr["jsFtoExpiry"])." #";
                                $updateclause.=" BannerJsFtoExpiry='$tempVal' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner Variable FTO Status Values";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerJsFtoExpiry='' , ";
                }


                if(in_array("jsProfileCompletionState",$inputcriteriaarr))
                {
                        if($criteriavaluesarr["jsProfileCompletionState"])
                        {
                                $tempVal="# ".implode(" , ",$criteriavaluesarr["jsProfileCompletionState"])." #";
                                $updateclause.=" BannerJsProfileCompletionState='$tempVal' , ";
                        }
                        else
                        {
                                $errormsg="Please enter correct values of banner completion status";
                                $check=0;
                        }
                }
                else
                {
                        $updateclause.=" BannerJsProfileCompletionState='' , ";
                }

		//added by lavesh rawat

	}
	else
	{
		//condn added by lavesh rawat
		$updateclause.=" BannerLocation='',BannerIP='',BannerCity='',BannerCtcMin='-1',BannerCtcMax='-1',BannerAgeMin='-1',  BannerAgeMax='-1', BannerGender='', BannerMARITALSTATUS='' , BannerMEM ='' , BannerCTC = '' , BannerREL='' , BannerEDU='',BannerOCC='',BannerCOM='',shikshaKeyword='', shikshaCountry='', shikshaCity = '', shikshaCategory = '', BannerPROPCITY='', BannerPROPINR='', BannerPROPTYPE='', BannerPROPCAT='',BannerJsVd='',BannerJsProfileStatus='',BannerJsMailID='',BannerJsEoiStatus='',BannerJsRegistrationStatus='',BannerJsFtoStatus='',BannerJsFtoExpiry='',BannerJsProfileCompletionState='',";
	}
	
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
		return 0;
	}
	else
	{
	 	$bannerstring="bnrstr".$criteriavaluesarr["zoneid"]."p".$criteriavaluesarr["bannerpriority"];
		$updateclause.=" BannerPriority='$criteriavaluesarr[bannerpriority]' , BannerWeightage ='$criteriavaluesarr[bannerweightage]',  ZoneId='$criteriavaluesarr[zoneid]' , BannerDefault='$criteriavaluesarr[bannerdefault]', BannerFixed='$criteriavaluesarr[bannerfixed]', BannerClass='$criteriavaluesarr[bannerclass]', BannerStartDate='$criteriavaluesarr[bannerstartdate]', BannerEndDate='$criteriavaluesarr[bannerenddate]' , BannerGif='$criteriavaluesarr[bannergif]', Bannerurl='$criteriavaluesarr[bannerurl]', BannerInternalOrExternal='$criteriavaluesarr[bannerintext]',BannerFeatures='$criteriavaluesarr[bannerfeaturelist]', MailerId='$criteriavaluesarr[mailerid]' , BannerStatic='$criteriavaluesarr[bannerstatic]' , BannerString='$bannerstring'";
 		$sql="update bms2.BANNER set ".$updateclause." where BannerId='$criteriavaluesarr[bannerid]'";
		//$smarty->assign("cnfrmmsg",$cnfrmmsg);
		return $sql;
	}
		
}

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
	$site = $data["SITE"];
	$smarty->assign("site",$site);
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
		$criteriavaluesarr["bannerpropcategory"] = $_POST["bannerpropcat"];
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
				//echo $sqlsavebanner;	
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
				{
					$cnfrmmsg="The banner details have saved.";
				}
				$showcriterias=showcriterias($bannerid);
				$cnfrmmsg.="You have seleted the following criterias:"."<br>".$showcriterias["criteria"];
				$cnfrmmsg.="<BR />Please <a href=\"bms_campaigndetails.php?id=$id&campaignid=$campaignid\">click here</a> to go back to campaign details.";
				$smarty->assign("cnfrmmsg",$cnfrmmsg);
				$smarty->display("./$_TPLPATH/bms_confirmation.htm");
			}
			else
				$smarty->display("./$_TPLPATH/bms_error.htm");
		}
		else
		{
			if ($bannerfixed == 'Y')
			{
				$smarty->assign("bannerfixed",$bannerfixed);
				$smarty->assign("errormsg","A default banner is already running in this zone.There can be only a single default banner in a zone hence the banner cannot be processed.Go back to book the banner for a different criterion");
			}
			else
				$smarty->assign("errormsg","Due to non-availability of the banner your request to book the banner cannot be processed.Click on the button below to save the details for future.");
			$smarty->display("./$_TPLPATH/bms_bookingerror.htm");
		}
		
		exit;
	}
	else
	{
		$bannerstartdate=$startyear."-".$startmonth."-".$startday;
		$bannerenddate=$endyear."-".$endmonth."-".$endday;
		$zonearr=explode("|ZID|",$zone);
		$zoneid=$zonearr[0];
		$zonecriteriastr=$zonearr[1];
		if($bannerdefault!='Y')
			$bannerdefault='N';
		if($bannerfixed!='Y')
			$bannerfixed='N';
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
		$smarty->assign("mailerid",$mailerid);
		
		if($bannerdefault!='Y' && $bannerfixed!='Y')
		{
			if($zonecriteriastr)
			{
				$zonecriteriaarr=explode(",",$zonecriteriastr);//print_r($zonecriteriaarr);
				foreach($zonecriteriaarr as $zonecriterias)
				{
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
			if ($bannerdefault == 'Y')	
				$smarty->assign("cnfrmmsg","No Criteria banner. No criteria to be filled.Please select priority and weight and continue.");
			elseif ($bannerfixed == 'Y')
				$smarty->assign("cnfrmmsg","Default banner. No criteria to be filled.Please select priority and weight and continue.");
			
		}
		//$smarty->assign("site",$site);
		$smarty->assign("campaignid",$campaignid);
		$smarty->assign("bannerdefault",$bannerdefault);
		$smarty->assign("bannerfixed",$bannerfixed);
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
