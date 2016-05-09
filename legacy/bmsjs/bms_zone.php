<?PHP
/*****************************************************bms_zone.php**********************************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : used for adding/editing a zone
   *  Includes/Libraries : ./includes/bms_connect.php
****************************************************************************************************************************/
include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
global $dbbms;
global $sitename;
/**************************************************************************
	fetches the array of criterias corresponding to a criteria id
	input: criteria id
	output: array of criterias
**************************************************************************/
function getZoneCriterias($criteriaid)
{
	global $dbbms , $sitename;
	$zonecriteria="";

        $tableToSelect = "bms2.CRITERIA_MAPPING";
        switch($sitename) {
                case "99acres": $tableToSelect = 'bms2.CRITERIA_MAPPING99'; break;
                case "shiksha": $tableToSelect = 'bms2.CRITERIA_MAPPING_SHIKSHA'; break;
                case "JS": $tableToSelect = 'bms2.CRITERIA_MAPPING'; break;
                default: $tableToSelect = 'bms2.CRITERIA_MAPPING';
        }
        $sql="select * from $tableToSelect where CriteriaId='$criteriaid'";
	$result = mysql_query($sql,$dbbms) or logErrorBms ("./includes/bms_connect.php:getCriteria:1: Could not select criteria<br><!--$sql<br>". 		mysql_error()."-->: ". mysql_errno(),$sql);
	if(mysql_num_rows($result))
	{
		$myrow=mysql_fetch_array($result);
		for($i=0;$i<mysql_num_fields($result);$i++)
		{
			if($myrow[mysql_field_name($result,$i)]=="Y")
				$zonecriteria=$zonecriteria.mysql_field_name($result,$i).",";
		}
		$zonecriteria=substr($zonecriteria,0,-1);
	}
	else
		$zonecriteria=NULL;
	return $zonecriteria;
}

/******************************************************************************
	checks if the details entered are valid or not
	input	:	zone name, zone desc, zone id, zone max banners, zone adv bookin
			period, zone cancellation period, zone banner width, zone banner 							     height, zoneid, regionid
	output	:	true - if details correct
		:	false - if details incorrect
*******************************************************************************/
function checkForm($zonename,$zonedesc,$zonemaxbans,$zoneadvbook,$zonecncl,$zonebanwidth,$zonebanheight,$zoneid="",$regid="")
{	
	global $smarty,$_TPLPATH;
	global $dbbms;
	$check=1;
	$zonename=trim(addslashes($zonename));
	$zonedesc=trim(addslashes($zonedesc));
	if($zonename=="")
	{
		$errormsg="To continue, please enter name of the zone.";
		$check= 0;
	}
	elseif($zonedesc=="")
	{
		$errormsg="To continue, please enter the description of the zone.";
		$check=0;
	}
	elseif($zonemaxbans=="")
	{
		$errormsg="To continue, please enter the max banners allowed in the zone.";
		$check=0;
	}
	elseif($zoneadvbook=="")
	{
		$errormsg="To continue, please enter the adv booking period of the zone.";
		$check=0;
	}
	elseif($zonecncl=="")
	{
		$errormsg="To continue, please enter the cancellation period of the zone.";
		$check=0;
	}
	elseif($zonebanwidth=="")
	{
		$errormsg="To continue, please enter the banner width in the zone.";
		$check=0;
	}
	elseif($zonebanheight=="")
	{
		$errormsg="To continue, please enter the banner height of the zone.";
		$check=0;
	}
	else
	{
		if($zoneid)
		{
			$q="select ZoneId from bms2.ZONE where ZoneName='$zonename' and RegId='$regid' and  ZoneId<>'$zoneid'" ;
			$res=mysql_query($q,$dbbms);
			if(mysql_num_rows($res))
			{
					$errormsg="A zone alredy exists with the name \"".$zonename."\" . Please name this zone differently. ";
					$check= 0;
			}
		}
		else
		{
			$q="select ZoneId from bms2.ZONE where ZoneName='$zonename' and RegId='$regid' " ;
			$res=mysql_query($q,$dbbms);
			if(mysql_num_rows($res))
			{
					$errormsg="A zone alredy exists with the name \"".$zonename."\" . Please name this zone differently. ";
					$check= 0;
			}
		}
	}
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
	 	return 0;
	}
	else 
		return 1;
	
}

/**********************************************************************************
   	Add/edit a zone
	input: zone information, action(zone to be added or edited)
	output: none
***********************************************************************************/
function AddEditZone($regid,$zonename,$zonedesc,$zonemaxbans,$zonerotallowed,$zonemaxbansinrot,$zonestatus,$zoneadvbook,$zonecncl,$zonealign,$zonebanwidth,$zonebanheight,$zonepopup,$zoneid,$criteriaid,$zonespace,$action,$zoneheader='')//action can be add or edit. Zone header added by Poorva Misra
{	global $dbbms;
	$curdate=date("Y-m-d");
	$zonecriterias=getZoneCriterias($criteriaid);

	if($action=="add")
	{
		//ZoneSpacing added by lavesh
		//$sql="insert into bms2.ZONE(ZoneId,RegId,ZoneName,ZoneDesc,ZoneMaxBans,ZoneRotAllowed,ZoneMaxBansInRot,ZoneStatus,ZoneAdvBookingPeriod,ZoneCncltionPeriodLimit,ZoneAlignment,ZoneSpacing,ZoneBanWidth,ZoneBanHeight,ZonePopup,ZoneEntryDt,CriteriaId,ZoneCriterias) values('','$regid','$zonename','$zonedesc','$zonemaxbans','$zonerotallowed','$zonemaxbansinrot','$zonestatus','$zonespace','$zoneadvbook','$zonecncl','$zonealign','$zonebanwidth','$zonebanheight','$zonepopup','$curdate','$criteriaid','$zonecriterias')";
		$sql="insert into bms2.ZONE(ZoneId,RegId,ZoneName,ZoneDesc,ZoneMaxBans,ZoneRotAllowed,ZoneMaxBansInRot,ZoneStatus,ZoneAdvBookingPeriod,ZoneCncltionPeriodLimit,ZoneAlignment,ZoneSpacing,ZoneBanWidth,ZoneBanHeight,ZonePopup,ZoneEntryDt,CriteriaId,ZoneCriterias,Zoneheader) values('','$regid','$zonename','$zonedesc','$zonemaxbans','$zonerotallowed','$zonemaxbansinrot','$zonestatus','$zoneadvbook','$zonecncl','$zonealign','$zonespace','$zonebanwidth','$zonebanheight','$zonepopup','$curdate','$criteriaid','$zonecriterias','$zoneheader')";
	}
	elseif($action=="edit"&&$zoneid)
	{
		//ZoneSpacing added by lavesh
		$sql="update bms2.ZONE set  ZoneName='$zonename',ZoneDesc='$zonedesc', ZoneMaxBans='$zonemaxbans', ZoneRotAllowed='$zonerotallowed', ZoneMaxBansInRot='$zonemaxbansinrot',ZoneStatus='$zonestatus', ZoneAdvBookingPeriod='$zoneadvbook', ZoneCncltionPeriodLimit='$zonecncl', ZoneAlignment='$zonealign', ZoneBanWidth='$zonebanwidth',ZoneBanHeight='$zonebanheight',ZonePopup='$zonepopup',CriteriaId='$criteriaid',  ZoneModDt='$curdate',ZoneCriterias='$zonecriterias',ZoneSpacing='$zonespace', Zoneheader = '$zoneheader' where ZoneId='$zoneid'";
	}
	else
		echo "check query";
	echo "<!--$sql-->";
	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_zone.php: AddEditZone:1: Could not add/edit zone <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
}

/********************************************************************************
   	Delete a zone
	input: zoneid
	output: none
*******************************************************************************/
function deleteZone($zoneid)
{
	global $dbbms;

	//added by lavesh
	$sql="INSERT INTO bms2.DELETED_ZONE (SELECT * FROM bms2.ZONE where ZoneId='$zoneid')";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_zone.php: deleteZone:1: Could not delete zone <br>    <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	//Ends Here

	$sql="delete from bms2.ZONE where ZoneId='$zoneid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_zone.php: deleteZone:1: Could not delete zone <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
}

if($data)
{
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	if($add_x)
	{
		if(checkForm($zonename,$zonedesc,$zonemaxbans,$zoneadvbook,$zonecncl,$zonebanwidth,$zonebanheight,"",$regionid,$zonespace))
		{	
			if($zonemaxbansrot)
				$zonerotallowed="Y";
			else
				$zonerotallowed="N";			
			AddEditZone($regionid,$zonename,$zonedesc,$zonemaxbans,$zonerotallowed,$zonemaxbansrot,$zonestatus,$zoneadvbook,$zonecncl,$zonealign,$zonebanwidth,$zonebanheight,$zonepopup,"",$zonecriteria,$zonespace,"add",$zoneheader);
			$cnfrmmsg="New zone \"".stripslashes($zonename)."\" has been saved";
			$smarty->assign("cnfrmmsg",$cnfrmmsg);
			$zones=getZoneDetails($regionid);
			$smarty->assign("zones",$zones);
			$criteria=getCriteria($sitename);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("id",$id);
			$smarty->assign("regionid",$regionid);
			$smarty->assign("sitename",$sitename);
			$smarty->display("./$_TPLPATH/bms_zone.htm");
		}
		else
		{
			$zones=getZoneDetails($regionid);
			$smarty->assign("zones",$zones);
			$criteria=getCriteria($sitename);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("zonename",stripslashes($zonename));
			$smarty->assign("zonedesc",stripslashes($zonedesc));
			$smarty->assign("zoneid",$zoneid);
			$smarty->assign("zonemaxbans",$zonemaxbans);
			$smarty->assign("zonemaxbansrot",$zonemaxbansrot);
			$smarty->assign("zoneadvbook",$zoneadvbook);
			$smarty->assign("zonecncl",$zonecncl);
			$smarty->assign("zonestatus",$zonestatus);
			$smarty->assign("zonealign",$zonealign);
			$smarty->assign("zonebanwidth",$zonebanwidth);
			$smarty->assign("zonebanheight",$zonebanheight);
			$smarty->assign("zonepopup",$zonepopup);
			$smarty->assign("zonecriteria",$zonecriteria);
			$smarty->assign("id",$id);
			$smarty->assign("regionid",$regionid);
			$smarty->assign("sitename",$sitename);
			$smarty->display("./$_TPLPATH/bms_zone.htm");
		}
	}
	elseif($edit_x)
	{
		list($zoneid)=explode("|X|",$zone);
		if(checkForm($zonename,$zonedesc,$zonemaxbans,$zoneadvbook,$zonecncl,$zonebanwidth,$zonebanheight,$zoneid,$regionid,$zonespace))
		{	//echo "is mailer is".$ismailer;
			if($zonemaxbansrot)
				$zonerotallowed="Y";
			else
			{
				$zonemaxbansrot="1";
				$zonerotallowed="N";
			}
			echo "<!--zonepopup:$zonepopup-->";
			AddEditZone($regid,$zonename,$zonedesc,$zonemaxbans,$zonerotallowed,$zonemaxbansrot,$zonestatus,$zoneadvbook,$zonecncl,$zonealign,$zonebanwidth,$zonebanheight,$zonepopup,$zoneid,$zonecriteria,$zonespace,"edit",$zoneheader);
			$cnfrmmsg="The zone \"".stripslashes($zonename)."\" has been edited";
			$smarty->assign("cnfrmmsg",$cnfrmmsg);
			$zones=getZoneDetails($regionid);
			$smarty->assign("zones",$zones);
			$smarty->assign("id",$id);
			$smarty->assign("regionid",$regionid);
			$criteria=getCriteria($sitename);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("sitename",$sitename);
			$smarty->display("./$_TPLPATH/bms_zone.htm");
		}
		else
		{
			$zones=getZoneDetails($regionid);
			$smarty->assign("zones",$zones);
			$criteria=getCriteria($sitename);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("zonename",stripslashes($zonename));
			$smarty->assign("zonedesc",stripslashes($zonedesc));
			$smarty->assign("zoneid",$zoneid);
			$smarty->assign("zonemaxbans",$zonemaxbans);
			$smarty->assign("zonemaxbansrot",$zonemaxbansrot);
			$smarty->assign("zoneadvbook",$zoneadvbook);
			$smarty->assign("zonecncl",$zonecncl);
			$smarty->assign("zonestatus",$zonestatus);
			$smarty->assign("zonealign",$zonealign);
			$smarty->assign("zonebanwidth",$zonebanwidth);
			$smarty->assign("zonebanheight",$zonebanheight);
			$smarty->assign("zonepopup",$zonepopup);
			//echo "zzz".$zonepopup;
			$smarty->assign("zonecriteria",$zonecriteria);
			$smarty->assign("id",$id);
			$smarty->assign("regionid",$regionid);
			$smarty->assign("sitename",$sitename);
			$smarty->display("./$_TPLPATH/bms_zone.htm");
		}
	}
	elseif($deletee_x)
	{
		list($zoneid)=explode("|X|",$zone);
		deleteZone($zoneid);
		$cnfrmmsg="The zone \"".stripslashes($zonename)."\" has been deleted";
		$smarty->assign("cnfrmmsg",$cnfrmmsg);
		$zones=getZoneDetails($regionid);
		$smarty->assign("zones",$zones);
		$criteria=getCriteria($sitename);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("id",$id);
		$smarty->assign("regionid",$regionid);
		$smarty->assign("sitename",$sitename);
		$smarty->display("./$_TPLPATH/bms_zone.htm");
	}
	else
	{
		if($regionid)	
		{	
			//echo "in region";
			$smarty->assign("regionid",$regionid);
			$zones=getZoneDetails($regionid);
			$smarty->assign("zones",$zones);
		}
		$smarty->assign("id",$id);
		$smarty->assign("sitename",$sitename);
		$smarty->display("./$_TPLPATH/bms_zone.htm");
	}
}
else
	TimedOutBms();
