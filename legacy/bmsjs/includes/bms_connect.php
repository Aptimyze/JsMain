<?php
//exit("THIS FEATURE WILL BE AVAILABLE AT 12:15 IST");
/*************************./includes/bms_connect.php**********************************************************************/
/*
	*	Created By         :	Abhinav Katiyar
	*	Last Modified By   :	Abhinav Katiyar
	*	Description        :	This file includes all the functions common to 
					the scripts and session mgmt functions
	*	Includes/Libraries :	bms_connections.php
***************************************************************************************************************************/
include_once("/usr/local/scripts/bms_config.php");
include_once("bmsArrayJs.php");
global $_TPLPATH ;
$_TPLPATH="bmsjs";
$TOUT1 = 3600;	// the timeout value in seconds for the first timeout 
$TOUT = 3600;   // added by Aman
$validcriteriaarray=array("Keywords","Farea","Industry","Location","Exp","Categories","IP","Ctc","Age","Gender","ExpResman","IndustryResman","FareaResman");
$bannerclassarr=array("Banner"=>array("Image","Flash","textlink"),
				"Mailer"=>array("MailerFlash","MailerImage"),
				"PopUp"=>array("PopUp"),
				"PopUnder"=>array("PopUnder")
				);
function getConnectionBms()
{
        global $_HOST_NAME , $_USER , $_PASSWORD , $_SITEURL , $dbbms;
        if(!$dbbms = @mysql_connect($_HOST_NAME,$_USER,$_PASSWORD))
        {
                logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
        }
        @mysql_select_db("bms2",$dbbms);
        return $dbbms;
}

$dbbms = getConnectionBms();
getLogPathBms();
$smarty = getSmartyBms();

/********************************************************************************
	 Desc :  Used for logging in users
	 If the user is valid, an array containing session id is returned
	 Else null is returned 
	 input:  Username,password,ip
	 output: Array of user info/ null
********************************************************************************/
	
function loginBms ($name, $pass, $ip)
{
	global	$dbbms, $dbsums,$TOUT1, $smarty, $_SERVER, $_POST, $_GET,$_SVN,$_SVNENABLE;
	
	/* Checks whether username and password are valid or not */

	$sql ="select USERID, USER_PRIVILEGE , SITE from bms2.USERS where  USERNAME=binary '$name' and  PASSWORD = binary '$pass' AND ACTIVE='Y'";

	$result= mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:loginBms:1: Could not verify user<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	
	//if user is valid
	if (mysql_num_rows($result))
	{
		$record = mysql_fetch_array($result);
		$userid = $record["USERID"];
		$privilege = $record["USER_PRIVILEGE"];
		$site = $record["SITE"];
		$login = true;
	}
	else
	{
		$ret = NULL;
		/*$sqlsums="Select user_id from clientprofile.client_reg where user_name=binary '$name' and pswd=binary '$pass' and superuser='y'";
   	 	$resultsums=mysql_query($sqlsums,$dbsums) or logErrorBms ("bms_connect.inc:loginBms:3: Could not authenticate user from sums<br><!--$sqlsums<br>". mysql_error()."-->: ". mysql_errno(),$sqlsums);
		if (mysql_num_rows($resultsums))
		{
	
			$recordsums=mysql_fetch_array($resultsums);
			$userid=$recordsums["user_id"];
			$privilege="client";	
			$login=true;
		}*/
	}
	if($login)
	{
		$tm = time();

		//delete users who have been logged on for more the timeout without any activity
		$sql="delete from bms2.CONNECT where TIME_IN < ($tm - $TOUT1)";
		$result = mysql_query($sql,$dbbms) or logErrorBms ("bms_connect.inc:loginBms:2: Could not clean connections<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
		
		//make entry in connect
		$sqlup="insert into bms2.CONNECT (ID, USERID, USERNAME, PASSWORD,PRIVILEGE, SITE , IPADDR, TIME_IN) values ('','$userid','$name','$pass','$privilege','$site','$ip','$tm' )";

		$sqlup_con = mysql_query($sqlup,$dbbms) or logErrorBms ("bms_connect.inc:loginBms:3: Could not insert new connections<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);

		$conid	= mysql_insert_id($dbbms);
		$id	= md5($conid)."~".$conid;
		$ret["ID"]	= $id;
		$ret["USER"]	= $name;
		$ret["USERID"]	= $userid;
		$ret["PRIVILEGE"]= $privilege;
		$ret["SITE"] = $site;
		if($_SVNENABLE=="true")//for redirection to personal directories coz of svn
			$_SVN="/".$name;
	}
	/* If not valid user then set output array to NULL */
	else
	{
		$ret = NULL;
	}
	/* return output array */
	$smarty->assign("id",$id);
	return $ret;
}	

/*********************************************************************************
   Fetches smarty paths for use at different servers 
   Includes the smarty file for use and creates a smarty object 
   input: username,password,ip
   output: array of user info/ null
*********************************************************************************/
	  
function getSmartyBms()
{
	 global $_SMARTYPATHS,$_SERVER,$smarty;

        //get smarty path
        $smartypath =$_SMARTYPATHS;

        //include file containing smarty class
        include_once("$smartypath");

        //create a new object for smarty
        if(!isset($smarty))
                $smarty = new Smarty;
	$smarty->template_dir   = JsConstants::$bmsDocRoot."/bmsjs/templates";
	$smarty->compile_dir    = JsConstants::$bmsDocRoot."/bmsjs/templates_c";
        return $smarty;
}

/****************************************************************************************
sets the global variable $_LOGPATH to the value of the path of the errorlog of that server.
	input: none
	output: none
*****************************************************************************************/
function getLogPathBms()
{
	global $_LOGPATHS,$_SERVER,$_LOGPATH,$_SVN,$_ADDLOGPATH;
                                                                                                                            
        //get log path
        $_LOGPATH = $_LOGPATHS.$_SVN.$_ADDLOGPATH;
}

/***************************************************************************************
logs the error and defines the output to be seen by the user when a sql query dies
	input:  message to be logged, query, whether to continue or exit, send a mail or not
	output: error template shown , or exit or coninued with error msg displayed
****************************************************************************************/
function logErrorBms($message,$query="",$critical="exit", $sendmailto="NO")
{
	/* this function creates a log file mn_error.txt in bms/log directory*/

	global $dbbms, $smarty,$_LOGPATH,$_TPLPATH;
	getLogPathBms();
	ob_start();
 	var_dump($_SERVER);
 	$ret_val = ob_get_contents();
 	ob_end_clean();
	$errorstring="\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";

	error_log($errorstring,3,JsConstants::$docRoot . "/$_LOGPATH/bms_error".date('dmY').".txt");

	/* if critical option is set to exit then exit from the script after displaying the error message*/
	if($critical=="exit")
	{
		echo $message;
		exit;
	}
	
	/* if critical option is set to ShowErrTemplate then display error template*/
	elseif($critical=="ShowErrTemplate")
	{
		echo "<!--$message-->";
		//$smarty->assign("errormsg", $message);
		$smarty->display("./$_TPLPATH/bms_error.tpl");
		exit;
	}
	
	/* if critical option is set to continue then display message and continue */
	elseif($critical!="continue")
	{
		echo $message;
	}
	
}

/**************************************************************************************
   authenticates if the user is a valid one and has valid permissions
	input: checksum, ip address, privilege
	output: array of user info(if authentication passed): none (if user not authenticated)
***************************************************************************************/
function authenticatedBms($checksum,$ip,$priv)
{
	global	$dbbms, $TOUT1,$smarty,$_SVN,$_SVNENABLE;
	list($md, $userno)=explode("~",$checksum);

	/* check for validity of id if not then ask for login */
	if(md5($userno)!=$md)
	{
	    return NULL;
	}

	/* check whether user is a live user or not */
	$sql_chk = "select USERID,USERNAME, TIME_IN,PRIVILEGE , SITE from bms2.CONNECT where ID='$userno' and PRIVILEGE='$priv'";
	$res_chk = mysql_query($sql_chk,$dbbms) or logErrorBms ("./includes/bms_connect.php:authenticatedBms:1: Could not authenticate the user<br><!--$sql_chk<br>". mysql_error()."-->: ". mysql_errno(), $sql_chk);
	$count=mysql_num_rows($res_chk);

	if ($count > 0)	
	{
		$myrow = mysql_fetch_array($res_chk);

		/* If user is not timed out then update CONNECT with current time*/
		if (time()-$myrow["TIME_IN"] < $TOUT1)
		{
			$tm = time();
			$sql_up = "update bms2.CONNECT set TIME_IN='$tm' where ID='$userno'";
			$res_up = mysql_query($sql_up,$dbbms) or logErrorBms ("./includes/bms_connect.php:authenticatedBms:2: Could not update the connection<br><!--$sql_up<br>". mysql_error()."-->: ". mysql_errno(),$sql_up);
			$id=$md."~".$userno;
			$ret["ID"] = $id;
			$ret["USER"] = $myrow["USERNAME"];
			$ret["USERID"] = $myrow["USERID"];
			$ret["PRIVILEGE"] = $myrow["PRIVILEGE"];
			$ret["SITE"]=$myrow["SITE"];

			if ($_SVNENABLE == "true")
				$_SVN="/".$myrow["USERNAME"];
		}
		else
			$ret= NULL;
		return $ret;
	}

	//user does not exist in CONNECT asked for login	
	else
	{
		return NULL;
	}
}

/*****************************************************************************
        Fetches all the regions in which a banner can be run.
	input: none
	output: array of regions
*****************************************************************************/
function getRegions()
{
	global $dbbms;
	$sql = "select RegID,RegName,RegDesc,RegMailer , SITE from bms2.REGION";
	$result  =  mysql_query($sql,$dbbms) or logErrorBms ("./includes/bms_connect.php:getRegions:1: Could not select regions<br><!--$sql<br>". 		mysql_error()."-->: ". mysql_errno(),$sql);
	if(mysql_num_rows($result))
	{
		$i=0;
		while($myrow=mysql_fetch_array($result))
		{
			$regions[$i]["name"]=$myrow["RegName"];
			$regions[$i]["value"]=$myrow["RegID"];
			$regions[$i]["desc"]=$myrow["RegDesc"];
			$regions[$i]["ismailer"]=$myrow["RegMailer"];
			$regions[$i]["sitename"]= $myrow["SITE"];
			$i++;
		}
	}
	else
		$regions=NULL;
	return $regions;

}
/*****************************************************************************
        Fetches name , empid of all the sales executives.
        input: none
        output: array of sales executives
*****************************************************************************/
function getSalesExec() 
{
        global $dbbms;
        $sql="SELECT emp_id , name , email , valid FROM  clientprofile.sales_exec";
        $result = mysql_query($sql,$dbbms) or logErrorBms ("./includes/bms_connect.php:getSalesExec:1: Could not select sales executive<br><!--$sql<br>".mysql_error()."-->: ". mysql_errno(),$sql);
        if(mysql_num_rows($result))
        {
                $i=0;
                while($myrow=mysql_fetch_array($result))
                {
			if($myrow["valid"] == 'y')
			{
				$salesexec[$i]["EMPID"] = $myrow["emp_id"];
				$salesexec[$i]["NAME"] = $myrow["name"];
				$salesexec[$i]["EMAIL"] = $myrow["email"];
				$salesexec[$i]["VALID"] = $myrow["valid"];
				$i++;
			}
                }
        }
        else
                $salesexec=NULL;
        return $salesexec;                                                                                                   }

/*****************************************************************************
 	Fetches all the zones corresponding to the region id specified. If region id is not specified, all the zones are returned
 	input: regionid
	output: array of zone details
******************************************************************************/
function getZoneDetails($regionid='')
{
	global $dbbms;
	if ($regionid)	//  if zone info of a particular region is required
		$sql = "select * from bms2.ZONE where RegId='$regionid'";
	else
		$sql = "select * from bms2.ZONE ";
	$result = mysql_query($sql,$dbbms) or logErrorBms ("./includes/bms_connect.php:getZoneDetails:1: Could not select zones<br><!--$sql<br>".mysql_error()."-->: ". mysql_errno(),$sql);
	
	if (mysql_num_rows($result))
	{
		$i = 0;
		while($myrow=mysql_fetch_array($result))
		{
			$zones[$i]["zoneid"]=$myrow["ZoneId"];
			$zones[$i]["zonename"]=$myrow["ZoneName"];
			$zones[$i]["zonereg"]=$myrow["RegId"];
			$zones[$i]["zonedesc"]=$myrow["ZoneDesc"];
			$zones[$i]["zonemaxbans"]=$myrow["ZoneMaxBans"];
			$zones[$i]["zonemaxbansrot"]=$myrow["ZoneMaxBansInRot"];
			$zones[$i]["zonestatus"]=$myrow["ZoneStatus"];
			$zones[$i]["zoneadvbook"]=$myrow["ZoneAdvBookingPeriod"];
			$zones[$i]["zonecncl"]=$myrow["ZoneCncltionPeriodLimit"];
			$zones[$i]["zonealign"]=$myrow["ZoneAlignment"];
			$zones[$i]["zonebanwidth"]=$myrow["ZoneBanWidth"];
			$zones[$i]["zonebanheight"]=$myrow["ZoneBanHeight"];
			$zones[$i]["zonepopup"]=$myrow["ZonePopup"];
			$zones[$i]["criteriaid"]=$myrow["CriteriaId"];
			$zones[$i]["spacing"]=$myrow["ZoneSpacing"];//added by lavesh
			$zones[$i]["zoneheader"]=htmlentities($myrow["Zoneheader"]);//added by Poorva
			$i++;
		}
	}
	else
		$regions=NULL;
	return $zones;
}

/**************************************************************************************
	fetches all the criteriaid's and criteria names 
	input: none
	output: array of criterias
***************************************************************************************/
function getCriteria($sitename)
{
	global $dbbms;

        $tableToSelect;
        switch($sitename) {
                case "99acres": $tableToSelect = 'bms2.CRITERIA_MAPPING99'; break;
                case "shiksha": $tableToSelect = 'bms2.CRITERIA_MAPPING_SHIKSHA'; break;
                case "JS": $tableToSelect = 'bms2.CRITERIA_MAPPING'; break;
                default: $tableToSelect = 'bms2.CRITERIA_MAPPING';
        }
        $sql = "select CriteriaId,CriteriaName from $tableToSelect ";
	$result = mysql_query($sql,$dbbms) or logErrorBms ("./includes/bms_connect.php:getCriteria:1: Could not select criteria<br><!--$sql<br>". 		mysql_error()."-->: ". mysql_errno(),$sql);
	if(mysql_num_rows($result))
	{
		$i = 0;
		while($myrow = mysql_fetch_array($result))
		{
			$criteria[$i]["criteriaid"]=$myrow["CriteriaId"];
			$criteria[$i]["criterianame"]=$myrow["CriteriaName"];
			$i++;
		}
	}
	else
		$criteria=NULL;
	return $criteria;
}

/***************************************************************************************
	Logs out the user from the system. Removes the entry from the connect table.
	input	: checksum, ip address
	output	: true if the user is logged out successfully
		: false if the user is not logged out 
***************************************************************************************/
function logoutBms($checksum,$ip)
{
	global $dbbms;
	list($md, $userno) = explode("~",$checksum);

	if (md5($userno) != $md)
		return FALSE;

 	$sql = "delete from CONNECT where ID='$userno'";
	$res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php:logoutBms:1: Could not close the connection<br>$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	if($res)
		return TRUE;
	else
		return FALSE;
}

/****************************************************************************************
 	displays the login page to the user(when the user is not authenticated or has been idle 
  	for more than a set time.
	input: none
	output: none
*****************************************************************************************/
function TimedOutBms()
{
	global $smarty,$_TPLPATH;
	$smarty->display("./$_TPLPATH/bms_loginpage.htm");
}

/****************************************************************************************
   	returns an array of the value and label to be shown , when the day is to be selected
	input: none
	output: array of days
*****************************************************************************************/
function getDaysBms()
{
	$days=array("0"=>array("daysvalue"=>"01","daysname"=>"1"),
				"1"=>array("daysvalue"=>"02","daysname"=>"2"),
				"2"=>array("daysvalue"=>"03","daysname"=>"3"),
				"3"=>array("daysvalue"=>"04","daysname"=>"4"),
				"4"=>array("daysvalue"=>"05","daysname"=>"5"),
				"5"=>array("daysvalue"=>"06","daysname"=>"6"),
				"6"=>array("daysvalue"=>"07","daysname"=>"7"),
				"7"=>array("daysvalue"=>"08","daysname"=>"8"),
				"8"=>array("daysvalue"=>"09","daysname"=>"9"),
				"9"=>array("daysvalue"=>"10","daysname"=>"10"),
				"10"=>array("daysvalue"=>"11","daysname"=>"11"),
				"11"=>array("daysvalue"=>"12","daysname"=>"12"),
				"12"=>array("daysvalue"=>"13","daysname"=>"13"),
				"13"=>array("daysvalue"=>"14","daysname"=>"14"),
				"14"=>array("daysvalue"=>"15","daysname"=>"15"),
				"15"=>array("daysvalue"=>"16","daysname"=>"16"),
				"16"=>array("daysvalue"=>"17","daysname"=>"17"),
				"17"=>array("daysvalue"=>"18","daysname"=>"18"),
				"18"=>array("daysvalue"=>"19","daysname"=>"19"),
				"19"=>array("daysvalue"=>"20","daysname"=>"20"),
				"20"=>array("daysvalue"=>"21","daysname"=>"21"),
				"21"=>array("daysvalue"=>"22","daysname"=>"22"),
				"22"=>array("daysvalue"=>"23","daysname"=>"23"),
				"23"=>array("daysvalue"=>"24","daysname"=>"24"),
				"24"=>array("daysvalue"=>"25","daysname"=>"25"),
				"25"=>array("daysvalue"=>"26","daysname"=>"26"),
				"26"=>array("daysvalue"=>"27","daysname"=>"27"),
				"27"=>array("daysvalue"=>"28","daysname"=>"28"),
				"28"=>array("daysvalue"=>"29","daysname"=>"29"),
				"29"=>array("daysvalue"=>"30","daysname"=>"30"),
				"30"=>array("daysvalue"=>"31","daysname"=>"31")
			);
		return $days;

}

/*****************************************************************************************
   	returns an array of the value and label to be shown , when the month is to be selected
	input: none
	output: array of month
*****************************************************************************************/
function getMonthsBms()
{
	$months=array("0"=>array("monthname"=>"Jan","monthvalue"=>"01"),
				"1" =>array("monthname"=>"Feb","monthvalue"=>"02"),
				"2" =>array("monthname"=>"Mar","monthvalue"=>"03"),
				"3"=>array("monthname"=>"Apr","monthvalue"=>"04"),
				"4"=>array("monthname"=>"May","monthvalue"=>"05"),
				"5"=>array("monthname"=>"Jun","monthvalue"=>"06"),
				"6"=>array("monthname"=>"Jul","monthvalue"=>"07"),
				"7"=>array("monthname"=>"Aug","monthvalue"=>"08"),
				"8"=>array("monthname"=>"Sep","monthvalue"=>"09"),
				"9"=>array("monthname"=>"Oct","monthvalue"=>"10"),
				"10"=>array("monthname"=>"Nov","monthvalue"=>"11"),
				"11"=>array("monthname"=>"Dec","monthvalue"=>"12"),
				);
	return $months;
}

/*****************************************************************************************
	returns an array of the value and label to be shown , when the year is to be selected
	input: none
	output: array of year
*****************************************************************************************/
function getYearsBms()
{
	$curyear=date("Y");
	$years=array("0"=>array("yearsvalue"=>$curyear-1),
		     "1"=>array("yearsvalue"=>$curyear),
		     "2"=>array("yearsvalue"=>$curyear+1),
		     "3"=>array("yearsvalue"=>$curyear+2),
	);
	return $years;
}

/*****************************************************************************************
	returns a general banner class of the banner class passed to the function
	input: bannerclass
	output: bannerclass
*****************************************************************************************/
function FormatBannerClass($bannerclass)
{
	global $bannerclassarr;
	foreach($bannerclassarr as $key=>$value)
	{
		if (in_array("$bannerclass",$value))
		{	
			return $key;
		}
				
	}
	return false;
}

/*****************************************************************************************
	assigns region and zone dropdowns to the template. If zoneid is passed , that region and zone will come selected. If showcriteria is specified, zonedropdown will contain a javascript function to populate criteria dropdown on change in zone. If banner class is specified, region and zone corresponding to that banner class only will be shown.
	input: selected zone, showcriteria,bannerclass
	output: dropdown assigned to template
******************************************************************************************/
function assignRegionZoneDropDowns($bannerzoneidselected="",$showcriteria="",$bannerclass="",$site="")
{
	global $dbbms,$smarty;

	//if ($site != "")
	//	$sitequery = " and REGION.SITE ='$site'";
	if ($bannerclass == "MailerFlash" || $bannerclass == "MailerImage")
		$addquery=" and RegMailer='Y' and ZonePopup!='Y'";
	elseif ($bannerclass == "PopUp" || $bannerclass == "PopUnder")
		$addquery=" and ZonePopup='Y' and RegMailer!='Y'";
	elseif($bannerclass)
		$addquery=" and RegMailer!='Y' and ZonePopup!='Y'";
	else
		$addquery="";

	$sql = "Select CONCAT(RegName,'(',REGION.RegId,')') as regname, REGION.RegId as regid ,CONCAT(ZoneName,'(',ZoneId,')') as zonename,ZONE.ZoneId as zoneid ,ZoneCriterias as zonecriterias from REGION,ZONE where REGION.RegId=ZONE.RegId".$addquery;    
	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:assignRegionZoneDropDowns:1: Could not select from region<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

	$regionarr = array();
	$zonearr = array();
	$numfields = mysql_num_fields($result);
	
   	while($myrow=mysql_fetch_array($result))
	{
		/*to get regionname which had been selected corresponding to zoneid*/
		if ($myrow["zoneid"] == $bannerzoneidselected)
		{
			$bannerregionselected = $myrow["regname"];
			$bannerzoneselected   = $myrow["zonename"];
		}
		
		/* make arrays
		1. with reg name as key and zone concatenated with criteria as value
		2. with zonenames of selected region as key and criteria as value
		*/
		
		if (array_key_exists($myrow["regname"],$regionarr))	
		{
			$regionarr["$myrow[regname]"]=$regionarr["$myrow[regname]"]."|@X@|".$myrow["zoneid"].",".$myrow["zonename"]."|X|".$myrow["zonecriterias"];
		}	
		else
			$regionarr["$myrow[regname]"]=$myrow["regid"]."|RID|".$myrow["zoneid"].",".$myrow["zonename"]."|X|".$myrow["zonecriterias"];
	}
	foreach($regionarr as $reg=>$regvalue)
	{
		/*for zone array , to get every zone in the region*/
		if($reg == $bannerregionselected)
		{
			$regvaluearr = explode("|RID|",$regvalue);
			$zonearray = explode("|@X@|",$regvaluearr[1]);
			foreach($zonearray as $zonevalues)
			{
				$zones = explode("|X|",$zonevalues);
				$zonename = explode(",",$zones[0]);
				$zonearr["$zonename[1]"] = $zonename[0]."|ZID|".$zones[1];
			}
		}
	}
	$regzonecriteria = getRegionDropDown($regionarr,$bannerregionselected);
	$zonecriteria = getZoneDropDown($zonearr,$bannerzoneselected,$showcriteria);
	$smarty->assign("regionarr",$regzonecriteria);
	$smarty->assign("zonearr",$zonecriteria);
}

/*************************************************************************************
   	called from assignRegionZone func, assigns the region dropdown to the templates
	input: array of regions , selected region
	output: dropdown of region assigned to template
*************************************************************************************/
function getRegionDropDown($regionarr,$bannerregionselected)
{
	$regionstr="<select name=\"region\" onChange=\"FillTheZone()\" >";
	$regionstr.="<option value=\"select\">Select</option>";
	foreach($regionarr as $reg=>$zones)
	{
		if($reg==$bannerregionselected)
			$regionstr.="<option value=\"".$zones."\" selected>$reg</option>";
		else
			$regionstr.="<option value=\"".$zones."\">$reg</option>";
	}
	
	$regionstr.="</select>";
	return $regionstr;
}


/**************************************************************************************
	called from assignRegionZone func, assigns the zone dropdown to the templates
	input: array of zone , selected zone,whether to include javascript function to populate criteria dropdown or not.
	output: dropdown of zone assigned to template
***************************************************************************************/
function getZoneDropDown($zonearr,$bannerzoneselected,$showcriteria)
{
	if($showcriteria=="showcriteria")
		$zonestr="<select name=\"zone\" onChange=\"FillTheCriteria()\">";
	else
		$zonestr="<select name=\"zone\">";
		
	$zonestr.="<option value=\"select\">Select</option>";
	foreach($zonearr as $zone=>$criterias)
	{
		if($zone==$bannerzoneselected)
			$zonestr.="<option value=\"".$criterias."\" selected>$zone</option>";
		else
			$zonestr.="<option value=\"".$criterias."\">$zone</option>";
	}
	$zonestr.="</select>";
	return $zonestr;
}

/*   
	fetches an array of experience to be used for exp dropdown
	input	:	none
	output	:	array of experience 
*/
function getExp()
{
	$exp=array("0"=>array("expvalue"=>"","explabel"=>"Exp","selected"=>""),
				"1"=>array("expvalue"=>"0","explabel"=>"0","selected"=>""),
				"2"=>array("expvalue"=>"1","explabel"=>"1","selected"=>""),
				"3"=>array("expvalue"=>"2","explabel"=>"2","selected"=>""),
				"4"=>array("expvalue"=>"3","explabel"=>"3","selected"=>""),
				"5"=>array("expvalue"=>"4","explabel"=>"4","selected"=>""),
				"6"=>array("expvalue"=>"5","explabel"=>"5","selected"=>""),
				"7"=>array("expvalue"=>"6","explabel"=>"6","selected"=>""),
				"8"=>array("expvalue"=>"7","explabel"=>"7","selected"=>""),
				"9"=>array("expvalue"=>"8","explabel"=>"8","selected"=>""),
				"10"=>array("expvalue"=>"9","explabel"=>"9","selected"=>""),
				"11"=>array("expvalue"=>"10","explabel"=>"10","selected"=>""),
				"12"=>array("expvalue"=>"11","explabel"=>"11","selected"=>""),
				"13"=>array("expvalue"=>"12","explabel"=>"12","selected"=>""),
				"14"=>array("expvalue"=>"13","explabel"=>"13","selected"=>""),
				"15"=>array("expvalue"=>"14","explabel"=>"14","selected"=>""),
				"16"=>array("expvalue"=>"15","explabel"=>"15","selected"=>""),
				"17"=>array("expvalue"=>"16","explabel"=>"16","selected"=>""),
				"18"=>array("expvalue"=>"17","explabel"=>"17","selected"=>""),
				"19"=>array("expvalue"=>"18","explabel"=>"18","selected"=>""),
				"20"=>array("expvalue"=>"19","explabel"=>"19","selected"=>""),
				"21"=>array("expvalue"=>"20","explabel"=>"20","selected"=>""),
				"22"=>array("expvalue"=>"21","explabel"=>"21","selected"=>""),
				"23"=>array("expvalue"=>"22","explabel"=>"22","selected"=>""),
				"24"=>array("expvalue"=>"23","explabel"=>"23","selected"=>""),
				"25"=>array("expvalue"=>"24","explabel"=>"24","selected"=>""),
				"26"=>array("expvalue"=>"25","explabel"=>"25","selected"=>"")
	
	);
	return $exp;

}

/***************************************************************************************
	fetches an array of age to be used for age dropdown
	input	:	none
	output	:	array of age
****************************************************************************************/
function getAge()
{
	$agearr=array();
	for($i=15,$j=0;$i<=75;$i++,$j++)
	{
		$agearr[$j]["value"]=$i;
		$agearr[$j]["label"]=$i;
	}
	$agearr[$j]["value"]="1000";
	$agearr[$j]["label"]=">75";
	return $agearr;

}

/****************************************************************************************
fetches an array of farea to be used for farea dropdown . If farea specified , the seleted parameter of the array will be set 
				input: selected farea
				output: array of farea
*/
function getFareaBms($bannerfarea='')
{
	
$dbresman=getConnectionResman();
$bannerfareaarr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerfarea))));
	$sql="select FAREA,LABEL from manager.FAREA";
	$res=mysql_query($sql,$dbresman) or logErrorBms("bms_connect.inc:getFareaBms:1: Could not select from hotjobs farea<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$farea[$i]["fareaid"]=$myrow["FAREA"];
		$farea[$i]["fareaname"]=$myrow["LABEL"];
		
		if(in_array("$myrow[FAREA]",$bannerfareaarr))
			$farea[$i]["selected"]="selected";
		else 
			$farea[$i]["selected"]="";
			
		$i++;
	}
//mysql_close($dbresman);
	return $farea;
}

function createfareapart($farea,$column1,$column2)
{
		if(is_array($farea) && in_array($column1,$farea))
		{
			$selected ="selected";
			$ret[varname] ="ret2";
		}
		else
			$ret[varname] ="ret3";

		$ret[val]="<option value=\"$column1\" $selected>$column2</option>\n";
		return $ret;
}
function getSearchFareaBms($fareaname,$farea='',$dynamic,$sizedropdown,$tempsearchtype)
{
	$bannerfareaarr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$farea))));
	return	createfarea($fareaname,$bannerfareaarr,$dynamic,$sizedropdown,$tempsearchtype="");
}

function createfarea($fareaname,$farea,$dynamic,$sizedropdown,$tempsearchtype="")
{
	$connectdata="hotjobs1";
	$dbjobs=getConnectionSearch();
//	global $dbjobs,$connectdata;
	if($sizedropdown >1)
	{
	$multisym="[]";
	$multiple ="multiple";
	$multisize ="size=\"$sizedropdown\"";
	}
	$ret2='';
	$ret3='';
	$ret1 = "<select name=\"$fareaname".$multisym."\"";
	$ret1 .=$multisize;
	$ret1 .=" $multiple class=\"textboxes1\" onChange=\"return checkCat(this,2);\">\n";
	$ret1.="<option value=\"\">Select</option>\n";
	$sql ="select FID,FAREA,SHOW_SFAREA from hotjobs1.FAREA where VALID ='y'";
	$res=mysql_query($sql,$dbjobs) or die(mysql_error());
	while($myrow =mysql_fetch_array($res))
	{//echo $myrow[FID];
		if($myrow[FID] !='41')
		{
			if($myrow[SHOW_SFAREA] =='y' and $tempsearchtype !='role_form')
			{
				$sql1 ="select SFID,SFAREA from hotjobs1.SFAREA where VALID ='y' and FID='$myrow[FID]'";
				$res1 =mysql_query($sql1,$dbjobs);
				while($myrow1 =mysql_fetch_array($res1))
				{
					$var1 =$myrow[FID].".".$myrow1[SFID];
					$var2 =$myrow1[SFAREA];
					if($myrow[FID] =='24')
					$var2 ="IT-".$var2;
					$fareadrop=createfareapart($farea,$var1,$var2);
					$varname =$fareadrop[varname];
					$$varname .=$fareadrop[val];
				}
			}
			else
			{
					$var1 =$myrow[FID];
					$var2 =$myrow[FAREA];
					$fareadrop=createfareapart($farea,$var1,$var2);
					$varname =$fareadrop[varname];
					$$varname .=$fareadrop[val];
			}
		}
	}
	
	return $ret1.$ret2.$ret3;

}


/*   fetches an array of industry type to be used for industry type  dropdown . If industry type specified , the seleted parameter of the array will be set 
				input: selected industry type 
				output: array of industry type
*/
function getIndtypeBms($bannerindtype='')
{
	$dbjobs=getConnectionSearch();
	$bannerindtypearr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerindtype))));
	$sql="select VALUE,LABEL from hotjobs1.INDTYPE";
	$res=mysql_query($sql,$dbjobs) or logErrorBms("bms_connect.inc:getIndtypeBms:1: Could not select from hotjobs indtype<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$indtype[$i]["indid"]=$myrow["VALUE"];
		$indtype[$i]["indname"]=$myrow["LABEL"];
		
		if(in_array("$myrow[VALUE]",$bannerindtypearr))
			$indtype[$i]["selected"]="selected";
		else 
			$indtype[$i]["selected"]="";
			
		$i++;
	}

	return $indtype;
}

/***************************************************************************************
	fetches an array of all banners corresponding to a particular campaign. 
	If banner status is specified , the camapign entities at that status will be returned. 
	input: campaignid, bannerstatus
	output: array of campaigndetails
****************************************************************************************/
function getCampaignDetails($campaignid,$bannerstatus='all')
{
	global $dbbms;
	if ($bannerstatus == "")			// if no banner status is selected 
		$bannerstatus = "all";
	if ($bannerstatus == "all")
		$sql = "select * from BANNER where CampaignId='$campaignid' order by BannerBookDate desc";
	else
		$sql = "select * from BANNER where CampaignId='$campaignid' and BannerStatus='$bannerstatus' order by BannerBookDate desc";
$res=mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getCampaignDetails:1: Could not select campaign wise banners<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i = 0;
	while($myrow=mysql_fetch_array($res))	
	{
		$campaigndetails[$i]["sno"]=$i+1;
		$campaigndetails[$i]["zoneid"]=$myrow["ZoneId"];
		$campaigndetails[$i]["bannerid"]=$myrow["BannerId"];

                //added by lavesh
                $bannerid=$myrow["BannerId"];
                $sql_1="SELECT COUNT(*) AS CNT FROM bms2.DEACTIVE_HISTORY WHERE BannerId='$bannerid'";
                $res_1=mysql_query($sql_1,$dbbms) or die(mysql_error().$sql_1);
                $row_1=mysql_fetch_array($res_1);
                $cnt=$row_1["CNT"];
                if($cnt)
                {
                        //zone is not currently in use.
                        $sql_1="SELECT StartDt,EndDt,ZoneId FROM bms2.DEACTIVE_HISTORY WHERE BannerId='$bannerid'";
                        $res_1=mysql_query($sql_1,$dbbms) or die(mysql_error().$sql_1);
                        $row_1=mysql_fetch_array($res_1);
                        $startdt=$row_1["StartDt"];
                        $enddt=$row_1["EndDt"];
                        $zoneid=$row_1["ZoneId"];

                        $start_arr=explode('#',$startdt);
                        $end_arr=explode('#',$enddt);
                        $zone_arr=explode('#',$zoneid);
                        $campaigndetails[$i][0]=$start_arr;
                        $campaigndetails[$i][1]=$end_arr;
                        $campaigndetails[$i][2]=$zone_arr;

                        //if(!((count($start_arr)==count($end_arr)) && (count($start_arr)==count($zone_arr))))
                        if(! (count($start_arr)==count($end_arr)) )
                                mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','DEACTIVE HISTORY MISMATCH',$bannerid);
                }
                //Ends Here.
	
		$campaigndetails[$i]["bannerclass"]=$myrow["BannerClass"];
		$campaigndetails[$i]["bannerstatus"]=$myrow["BannerStatus"];
		$campaigndetails[$i]["bannerstatus"]=$myrow["BannerStatus"];
		$campaigndetails[$i]["bannergif"]=$myrow["BannerGif"];
		$campaigndetails[$i]["bannerurl"]=$myrow["BannerUrl"];
		$campaigndetails[$i]["mailerid"]=$myrow["MailerId"];
		$campaigndetails[$i]["bannerstartdate"]=$myrow["BannerStartDate"];
		$campaigndetails[$i]["bannerenddate"]=$myrow["BannerEndDate"];
		$campaigndetails[$i]["bannerbookdate"]=$myrow["BannerBookDate"];
		$campaigndetails[$i]["bannerlocation"]=$myrow["BannerLocation"];
		$campaigndetails[$i]["bannerip"]=$myrow["BannerIP"];
		$campaigndetails[$i]["bannerctc"]=$myrow["BannerCTC"];
		$campaigndetails[$i]["banneragemin"]=$myrow["BannerAgeMin"]; 
		$campaigndetails[$i]["banneragemax"]=$myrow["BannerAgeMax"];
		$campaigndetails[$i]["bannergender"]=$myrow["BannerGender"];
		$campaigndetails[$i]["comments"]=$myrow["COMMENTS"]; //Added by aman
		$campaigndetails[$i]["bannercriterias"]=showcriterias($myrow["BannerId"]);
		$campaigndetails[$i]["bannerzonecriterias"]=getZoneParam($myrow["ZoneId"]);
		$i++;
	}
	return $campaigndetails;
}

/*   fetches an array of categories to be used for categories dropdown . If category specified , the seleted parameter of the array will be set 
				input: selected category
				output: array of categories
*/
function getCategories($bannercategory='')
{
	$dbcat=getConnectionCategories();
	$bannercategoryarr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannercategory))));
	$bannercategoryarr[0]=trim($bannercategoryarr[0]);
	$sql="select Category,Label from BMS.Categories";
	$res=mysql_query($sql,$dbcat) or logErrorBms("bms_connect.inc:getCategories:1: Could not select categories <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$categoryarr[$i]["categoryvalue"]=$myrow["Category"];
		$categoryarr[$i]["categoryname"]=$myrow["Label"];
		
		if(in_array("$myrow[Category]",$bannercategoryarr))
			$categoryarr[$i]["selected"]="selected";
		else 
			$categoryarr[$i]["selected"]="";
			
		$i++;
	}
//	mysql_close($dbcat);
	return $categoryarr;
}

/*************************************************************************************
	fetches an array of cities to be used for ip dropdown . 
	If ip specified , the seleted parameter of the array will be set 
	input	:	selected ip
	output	:	array of cities
**************************************************************************************/
function getIpCity($bannerip)
{
	global $dbbms;
	$bannercityarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerip))));
	$sql = "select CityId,CityName from bms2.IPCITIES";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIpCity:1: Could not select cities <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$ipcitiesarr[$i]["value"] = $myrow["CityId"];
		$ipcitiesarr[$i]["name"]  = $myrow["CityName"];
		if(in_array("$myrow[CityId]",$bannercityarr))
			$ipcitiesarr[$i]["selected"] = "selected";
		else 
			$ipcitiesarr[$i]["selected"] = "";
		$i++;
	}
	return $ipcitiesarr;
}

/*************************************************************************************
        fetches an array of countries to be used for ip dropdown .
        If ip specified , the seleted parameter of the array will be set
        input   :       selected ip
        output  :       array of countries
**************************************************************************************/
function getIpCountry($bannerip)
{
        global $dbbms;
        $bannercountryarr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerip))));
        $sql="select CountryId,CountryName from bms2.IPCOUNTRIES";
        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIpCountry:1: Could not select cities <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $ipctryarr[$i]["value"] = $myrow["CountryId"];
                $ipctryarr[$i]["name"] = $myrow["CountryName"];                                                              
                if(in_array("$myrow[CountryId]",$bannercountryarr))     
                       $ipctryarr[$i]["selected"] = "selected";
                else
                       $ipctryarr[$i]["selected"] = "";
                $i++;
        }
        return $ipctryarr;
}

/*************************************************************************************
   	fetches an array of ctc to be used for ctc dropdown . 
	input: none
	output: array of ctc
*************************************************************************************/
function getCtc($bannerctc)
{
	 global $dbbms;
        $bannerctcarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerctc))));
        $sql = "select VALUE , LABEL from bms2.INCOME order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $ctcarr[$i]["value"] = $myrow["VALUE"];
                $ctcarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannerctcarr))
                {
                        $ctcarr[$i]["selected"] = "selected";
                }
                else
                        $ctcarr[$i]["selected"] = "";
                $i++;
        }

	return $ctcarr;
}

/*************************************************************************************
        fetches an array of membership to be used for ctc dropdown .
        input: none
        output: array of membership
*************************************************************************************/

function getMEM($bannermem)
{
         global $dbbms;
        $bannermemarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannermem))));
        $sql = "select VALUE , LABEL from bms2.SUBSCRIPTION order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $memarr[$i]["value"] = $myrow["VALUE"];
                $memarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannermemarr))
                {
                        $memarr[$i]["selected"] = "selected";
                }
                else
                        $memarr[$i]["selected"] = "";
                $i++;
        }
        return $memarr;
}
/*************************************************************************************
        fetches an array of religion to be used for religion dropdown .
        input: none
        output: array of religion
*************************************************************************************/
function getREL($bannerrel)
{
        global $dbbms;
        $bannerrelarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerrel))));
        $sql = "select VALUE , LABEL from bms2.RELIGION order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getREL:1: Could not select religion <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $relarr[$i]["value"] = $myrow["VALUE"];
                $relarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannerrelarr))
                {
                        $relarr[$i]["selected"] = "selected";
                }
                else
                        $relarr[$i]["selected"] = "";
                $i++;
        }
        return $relarr;
}

/*************************************************************************************
        fetches an array of education to be used for education dropdown .
        input: none
        output: array of education
*************************************************************************************/
function getEDU($banneredu)
{
         global $dbbms;
        $bannereduarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$banneredu))));
        $sql = "select VALUE , LABEL from bms2.EDUCATION order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $eduarr[$i]["value"] = $myrow["VALUE"];
                $eduarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannereduarr))
                {
                        $eduarr[$i]["selected"] = "selected";
                }
                else
                        $eduarr[$i]["selected"] = "";
                $i++;
        }
        return $eduarr;
}

/*************************************************************************************
        fetches an array of occupation to be used for occupation dropdown .
        input: none
        output: array of occupation
*************************************************************************************/
function getOCC($bannerocc)
{
         global $dbbms;
        $banneroccarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerocc))));
        $sql = "select VALUE , LABEL from bms2.OCCUPATION order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $occarr[$i]["value"] = $myrow["VALUE"];
                $occarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$banneroccarr))
                {
                        $occarr[$i]["selected"] = "selected";
                }
                else
                        $occarr[$i]["selected"] = "";
                $i++;
        }
        return $occarr;
}

//added by lavesh rawat
function getDropDownsArr($selected,$loopArr)
{
        global $dbbms;
        $selectedArr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$selected))));
	$i=0;
	foreach($loopArr as $k=>$v)
        {
                $arr[$i]["value"] = $k;
                $arr[$i]["name"]  = $v;
                if(in_array($k,$selectedArr))
                        $arr[$i]["selected"] = "selected";
                else
                        $arr[$i]["selected"] = "";
                $i++;
        }
        return $arr;
}
//added by lavesh rawat

/*************************************************************************************
        fetches an array of community to be used for community dropdown .
        input: none
        output: array of community
*************************************************************************************/
function getCOM($bannercom)
{
         global $dbbms;
        $bannercomarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannercom))));
        $sql = "select VALUE , LABEL from bms2.MTONGUE order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $comarr[$i]["value"] = $myrow["VALUE"];
                $comarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannercomarr))
                {
                        $comarr[$i]["selected"] = "selected";
                }
                else
                        $comarr[$i]["selected"] = "";
                $i++;
        }
        return $comarr;
}

function getPROPCITY($bannerpropcity)
{
         global $dbbms;
        $bannerpropcityarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerpropcity))));
        $sql = "select VALUE , LABEL from bms2.PROPCITY order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $propcityarr[$i]["value"] = $myrow["VALUE"];
                $propcityarr[$i]["name"]  = $myrow["LABEL"];                 
		if(in_array("$myrow[VALUE]",$bannerpropcityarr))
                {                         
			$propcityarr[$i]["selected"] = "selected";
                }
                else
                        $propcityarr[$i]["selected"] = "";
                $i++;
        }
        return $propcityarr;
}
function getPROPBUDGET($bannerpropinr)
{
	global $dbbms;
        $bannerpropinrarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerpropinr))));
        $sql = "select VALUE , LABEL from bms2.PROPINR order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $propinrarr[$i]["value"] = $myrow["VALUE"];
                $propinrarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannerpropinrarr))
                {
                        $propinrarr[$i]["selected"] = "selected";
                }
                else
                        $propinrarr[$i]["selected"] = "";
                $i++;
        }
        return $propinrarr;
}
function getPROPINRRENT($bannerpropinr)
{
	global $dbbms;
        $bannerproprentinrarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerpropinr))));
        $sql = "select VALUE , LABEL from bms2.PROPINRRENT order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $proprentinrarr[$i]["value"] = $myrow["VALUE"];
                $proprentinrarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannerproprentinrarr))
                {
                        $proprentinrarr[$i]["selected"] = "selected";
                }
                else
                        $proprentinrarr[$i]["selected"] = "";
                $i++;
        }
        return $proprentinrarr;
}
function getPROPTYPE($bannerproptype)
{
         global $dbbms;
        $bannerproptypearr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerproptype))));
        $sql = "select VALUE , LABEL from bms2.PROPTYPE order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $proptypearr[$i]["value"] = $myrow["VALUE"];
                $proptypearr[$i]["name"]  = $myrow["LABEL"];
                if(in_array($myrow["VALUE"],$bannerproptypearr))
                {
                        $proptypearr[$i]["selected"] = "selected";
                }
                else
                        $proptypearr[$i]["selected"] = "";
                $i++;
        }
        return $proptypearr;
}
/*************************************************************************************
        fetches an array of marital status to be used for marital status dropdown .
        input: none
        output: array of marital status
*************************************************************************************/
function getMStatus($bannermstatus)
{
	global $dbbms;
        $bannermstatusarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannermstatus))));
        $sql = "select VALUE , LABEL from bms2.MARITALSTATUS order by SORTBY";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $mstatusarr[$i]["value"] = $myrow["VALUE"];
                $mstatusarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[VALUE]",$bannermstatusarr))
                {
                        $mstatusarr[$i]["selected"] = "selected";
                }
                else
                        $mstatusarr[$i]["selected"] = "";
                $i++;
        }
        return $mstatusarr;
}

/*************************************************************************************
	fetches label of ctc to be used for ctc dropdown .
        input: integer value of ctc
        output: label of a particular ctc value
*************************************************************************************/
function getCtcLabel($value="")
{
	global $dbbms;
	$sql = "SELECT LABEL FROM bms2.INCOME WHERE VALUE='$value'";
	$res = mysql_query($sql) or die("$sql".mysql_error());
	$row = mysql_fetch_array($res);
	$label = $row["LABEL"];
	return $label;
}

/************************************************************************************
   	fetches the common template used as a header in bms
	input: array of user info
	output: header template
**************************************************************************************/
function fetchHeaderBms($data)
{
	global $smarty,$_TPLPATH;
	$smarty->assign("user",$data["USER"]);
	$smarty->assign("id",$data["ID"]);
	$header = $smarty->fetch("$_TPLPATH/bms_header.htm");
	return $header;
}

/************************************************************************************
	fetches the common template used as a footer in bms
	input: array of user info
	output: footer template
*************************************************************************************/
function fetchFooterBms()
{
	global $smarty,$_TPLPATH;
	$footer = $smarty->fetch("$_TPLPATH/bms_footer.htm");
	return $footer;	
}

/************************************************************************************
	fetches all the details of a banner
	input : bannerid
	output: array of bannerproperties
*************************************************************************************/	
function getBannerDetails($bannerid) // returns all the details of a particular banner
{
	global $dbbms;
	$sql = "select * from bms2.BANNER where BannerId = '$bannerid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_bannerdetails.php:getBannerDetails :2: Could not get banner details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i = 0;
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
		$bannerdetails["bannerfixed"]=$myrow["BannerFixed"];
		$bannerdetails["bannerpriority"]=$myrow["BannerPriority"];
		$bannerdetails["bannerstring"]=$myrow["BannerString"];
		$bannerdetails["bannerfreeorpaid"]=$myrow["BannerFreeOrPaid"];
		$bannerdetails["bannerintext"]=$myrow["BannerInternalOrExternal"];
		$bannerdetails["mailerid"]=$myrow["MailerId"];
		$bannerdetails["campaignid"]=$myrow["CampaignId"];
		$bannerdetails["bannerkeyword"]=trim(str_replace("#"," ",$myrow["BannerKeyword"])); 
		$bannerdetails["bannerkeystype"]=$myrow["BannerKeysType"];
		//$bannerdetails["bannerlocation"]=trim(str_replace("#"," ",$myrow["BannerLocation"]));
		$bannerdetails["bannercategories"]=$myrow["BannerCategories"];
		$bannerdetails["bannerip"]=$myrow["BannerCity"];
                $bannerdetails["bannerShikshaCountries"]=trim(str_replace('#','',$myrow["shikshaCountry"]));
                $bannerdetails["bannerShikshaCities"]=trim(str_replace('#','',$myrow["shikshaCity"]));
                $bannerdetails["bannerShikshaCategories"]=trim(str_replace('#','',$myrow["shikshaCategory"]));
                $bannerdetails["bannerShikshaKeyword"]=trim(str_replace('#','',$myrow["shikshaKeyword"]));

		$bannerdetails["bannerlocation"]=trim(str_replace("#"," ",$myrow["BannerLocation"]));
		list($ctry,$city)=explode("|X|",$bannerdetails["bannerlocation"]);
               	list($indiancity,$uscity)=explode("$",$city);
               	//$bannerdetails["bannerlocation"];
               	$bannerdetails["bannerloc_ctry"]=trim($ctry);
               	$bannerdetails["bannerloc_incity"]=trim($indiancity);
               	$bannerdetails["bannerloc_uscity"]=trim($uscity);
               	$bannerdetails["bannercountry"]=$myrow["BannerCountry"];
               	$bannerdetails["bannerincity"]=$myrow["BannerInCity"];
               	$bannerdetails["banneruscity"]=$myrow["BannerUsCity"];
		$bannerdetails["bannerctc"]=$myrow["BannerCTC"];
		$bannerdetails["bannermem"]=$myrow["BannerMEM"];
		$bannerdetails["bannermstatus"]=$myrow["BannerMARITALSTATUS"];
		$bannerdetails["banneredu"]=$myrow["BannerEDU"];
		$bannerdetails["bannerrel"]=$myrow["BannerREL"];
		$bannerdetails["bannerocc"]=$myrow["BannerOCC"];
		$bannerdetails["bannercom"]=$myrow["BannerCOM"];

		$bannerdetails["bannerctcmin"]=$myrow["BannerCTCMin"];
		$bannerdetails["bannerctcmax"]=$myrow["BannerCTCMax"];
		$bannerdetails["banneragemin"]=$myrow["BannerAgeMin"]; 
		$bannerdetails["banneragemax"]=$myrow["BannerAgeMax"];
		$bannerdetails["bannergender"]=$myrow["BannerGender"];
		
		$bannerdetails["bannerpropcity"]=$myrow["BannerPROPCITY"];
		$bannerdetails["bannerpropinr"]=$myrow["BannerPROPINR"];
		$bannerdetails["bannerproptype"]=$myrow["BannerPROPTYPE"];
		$bannerdetails["bannerpropcat"] = $myrow["BannerPROPCAT"];

		//added by lavesh rawat
		$bannerdetails["bannerJsVd"]=$myrow["BannerJsVd"];
		$bannerdetails["bannerJsProfileStatus"]=$myrow["BannerJsProfileStatus"];
		$bannerdetails["bannerJsMailID"]=$myrow["BannerJsMailID"];
		$bannerdetails["bannerJsEoiStatus"]=$myrow["BannerJsEoiStatus"];
		$bannerdetails["bannerJsRegistrationStatus"]=$myrow["BannerJsRegistrationStatus"];
		$bannerdetails["bannerJsFtoStatus"]=$myrow["BannerJsFtoStatus"];
		$bannerdetails["bannerJsFtoExpiry"]=$myrow["BannerJsFtoExpiry"];
		$bannerdetails["bannerJsProfileCompletionState"]=$myrow["BannerJsProfileCompletionState"];
		//added by lavesh rawat

		list($bannerdetails["bannerstartyear"],$bannerdetails["bannerstartmonth"],$bannerdetails["bannerstartday"])=explode("-",$myrow["BannerStartDate"]);
		list($bannerdetails["bannerendyear"],$bannerdetails["bannerendmonth"],$bannerdetails["bannerendday"])=explode("-",$myrow["BannerEndDate"]);
		$i++;
	}
	return $bannerdetails;	
}

/****************************************************************************************
		returns the label corresponding to value specified. The type of 
		value (whether it is ctc  or indtype or city id  or category id
		input: value, type
		output: label of the corresponding value
****************************************************************************************/
function get_farea_bms($value,$var)
{	
	global $dbbms;

	// value is simply value. var is like ctc, city etc.

	$s_arr_value = explode (",",$value);
	$k = 0;
	$ret = '';

	while($s_arr_value[$k])
	{
		if($var == "priv")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "indtype")
		{
			$temp_farea = $s_arr_value[$k];
		}
		else if($var == "farea")
		{
			$temp_farea = $s_arr_value[$k];
		}
		else if($var == "searchfarea")
		{
			$temp_farea1 = explode(".",$s_arr_value[$k]);
			$temp_farea = $temp_farea1[0];
			$temp_sfarea = $temp_farea1[1];
		}
		else if($var == "ctc")
		{
			$temp_farea1	= explode(",",$s_arr_value[$k]);
			$temp_farea	= $temp_farea1[0];
		}
		else if($var == "mem")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "mstatus")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "rel")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "edu")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "propcity")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "propinr")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "proprentinr")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "proptype")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "com")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "com")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }
		else if($var == "country")
                {
                        $temp_farea1    = explode(",",$s_arr_value[$k]);
                        $temp_farea     = $temp_farea1[0];
                }

		else if($var == "city")
		{
			$temp_farea1 = explode(".",$s_arr_value[$k]);
			$temp_farea = $temp_farea1[0];
		}
		else
		{
			;//do nothing
		}

		if ($var == "indtype")
		{
			$sql = "select SQL_CACHE VALUE, LABEL from hotjobs1.INDTYPE where VALUE = '$temp_farea' ";
			$res = mysql_query($sql,$dbjobs) or logErrorBms("bms_connect.inc:get_farea_bms:1: Could not select industry <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		}
		elseif ($var == "farea")
		{
			$sql = "select SQL_CACHE FAREA, LABEL  from manager.FAREA where FAREA = '$temp_farea' ";
			$res = mysql_query($sql,$dbresman) or logErrorBms("bms_connect.inc:get_farea_bms:2: Could not select farea <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		}
		
		elseif ($var == "searchfarea")
		{
			if($temp_sfarea)
				$sql = "select SQL_CACHE FAREA,SFAREA from hotjobs1.FAREA,hotjobs1.SFAREA where FAREA.FID = '$temp_farea' AND SFAREA.SFID = '$temp_sfarea' AND SFAREA.FID = FAREA.FID;";
			else
				$sql = "select SQL_CACHE FAREA from hotjobs1.FAREA where FID = '$temp_farea' ";
			$res = mysql_query($sql,$dbjobs) or logErrorBms("bms_connect.inc:get_farea_bms:2: Could not select search farea <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		}
		elseif ($var == "ctc")  // to find label for income
		{
			$sql = "select SQL_CACHE LABEL from bms2.INCOME where ID = '$temp_farea' ";
			$res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select income <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
//			mysql_close($dbcat);
		}
		elseif ($var == "mem")  // to find label for subscription
                {
                        $sql = "select SQL_CACHE LABEL from bms2.SUBSCRIPTION where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select membership <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
//                      mysql_close($dbcat);
                }
		elseif ($var == "mstatus")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.MARITALSTATUS where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "rel")  // to find label for religion
                {
                        $sql = "select SQL_CACHE LABEL from bms2.RELIGION where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "edu")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.EDUCATION where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "propinr")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.PROPINR where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "proprentinr")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.PROPINRRENT where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "propcity")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.PROPCITY where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "proptype")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.PROPTYPE where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "com")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.MTONGUE where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }
		elseif ($var == "occ")  // to find label for marital status
                {
                        $sql = "select SQL_CACHE LABEL from bms2.OCCUPATION where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select marital status <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
                }

		elseif ($var == "country")  // to find label for city
                {
                        $sql = "select SQL_CACHE LABEL from bms2.COUNTRY where VALUE = '$temp_farea' ";
                        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:3: Could not select COUNTRY <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
//                      mysql_close($dbcat);
                }

		else
		{
			$sql = "select SQL_CACHE CityName from bms2.IPCITIES where CityId= '$temp_farea' ";
			$res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:get_farea_bms:4: Could not select cities <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		}
		
		$myrow = mysql_fetch_array($res);
		
		if($var == "indtype")
		{
			$ret .= $myrow["LABEL"].", ";
		}
		else if($var == "farea" )
		{
			$ret .= $myrow["LABEL"].", ";
		}
		else if($var == "searchfarea" )
		{
			$ret .= $myrow["FAREA"];
			
			if($myrow["SFAREA"])
				$ret.=" - ".$myrow["SFAREA"];
			$ret.=", ";
		}
		else if($var == "ctc")
		{
			$ret .= $myrow["LABEL"]."<br>+ ";
		}
		else if($var == "mem")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "mstatus")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "rel")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "occ")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "edu")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "com")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "propinr")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "proptype")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "proprentinr")
                {
                	$ret .= $myrow["LABEL"].", ";
                }
		else if($var == "propcity")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "country")
                {
                        $ret .= $myrow["LABEL"].", ";
                }
		else if($var == "city")
		{
			$ret .= $myrow["CityName"].", ";
		}
	$k++;
	}
	return substr($ret,0,strlen($ret)-2);	
}


/**********************************************************************************
	returns an array containing the criterias corresponding to a banner
	input: banner id
	output: array containg banner criterias
**********************************************************************************/
function showcriterias($banner)
{
	global $dbbms;
	$sql = "Select * from bms2.BANNER where BannerId='$banner'";
        $result = mysql_query($sql,$dbbms);

	if ($myrow = mysql_fetch_array($result))
	{
		$default = $myrow["BannerDefault"];
		$fixed   = $myrow["BannerFixed"];

		if ($default == 'Y')
		{
		       $criteria=" No Criteria ";
		}
		elseif ($fixed == 'Y')
		{
			 $criteria=" Default ";
		}
		else
		{
			 if ($myrow["BannerLocation"] && trim($myrow["BannerLocation"]) != '')
			 {
				$locarr		= explode("#",$myrow["BannerLocation"]);
				$location	= trim($locarr[1]);
				/*$criteria.="<B>Location--></B>$location<br>";
				$returnarr["selected"]["Location"]=$location;*/
				$locarr         = explode("#",$myrow["BannerLocation"]);
                                $location       = trim($locarr[1]);
                                list($countryarr,$cityarr) = explode("|X|",$location);
                                $uscityarr      = explode("$",$cityarr);
                                $cityarr        = $uscityarr[0];
                                                                                                                            
                                if(count($countryarr >= 1))
                                        $country        = explode(",",$countryarr);
                                else
                                        $country = trim($countryarr);
                                                                                                                            
                                if(count($cityarr >= 1))
                                        $city        = explode(",",$cityarr);
                                else
                                        $city = trim($cityarr);
                                                                                                                            
                                if(count($uscityarr[1] >= 1))
                                        $uscity        = explode(",",$uscityarr[1]);
                                else
                                        $uscity = trim($uscityarr[1]);
                                                                                                                            
                                for ($i = 0;$i < count($country);$i++)
                                {
                                        if ($ctrystr)
                                        {
                                                $ctrystr.=" , ".get_farea_bms(trim($country[$i]),"country");
                                        }
                                        else
                                        {
                                                $ctrystr = get_farea_bms(trim($country[$i]),"country");
                                        }
                                }
				for ($i = 0;$i < count($city);$i++)
                                {
                                        if ($citystr)
                                        {
                                                $citystr.=" , ".getLocCity(trim($city[$i]));
                                        }
                                        else
                                        {
                                                $citystr = getLocCity(trim($city[$i]));
                                        }
                                }
                                for ($i = 0;$i < count($uscity);$i++)
                                {
                                        if ($uscitystr)
                                        {
                                                $uscitystr.=" , ".getLocCity(trim($uscity[$i]));
                                        }
                                        else
                                        {
                                                $uscitystr = getLocCity(trim($uscity[$i]));
                                        }
                                }
				$criteria.="<B>Location--></B>COUNTRY : $ctrystr<br>";
                                if($citystr)
                                        $criteria.="<B>CITIES</B> : $citystr<br>";
                                if($uscitystr)
                                        $criteria.="<B>CITIES</B> : $uscitystr<br>";
                                $returnarr["selected"]["Location"]=$location;
                        }
			if($myrow["BannerCity"] && trim($myrow["BannerCity"])!='')
			{
				$cityarr	= explode("#",$myrow["BannerCity"]);
				$ipstr		= str_replace(" ","",$cityarr[1]);
				$city 		= explode(",",$ipstr);

				for ($i = 0;$i < count($city);$i++)
				{
					if ($citystr)
					{
						$citystr.=",".get_farea_bms($city[$i],"city");
					}
					else
					{
						$citystr = get_farea_bms($city[$i],"city");
					}
				}
																     
				$criteria.="<B>Cities--></B>$citystr<br>";
				$returnarr["selected"]["IP"] = $citystr;
			 }

			if($myrow["BannerGender"]!='')
			{
				$criteria.="<B>Gender--></b>".$myrow["BannerGender"]."<br>";				
				$returnarr["selected"]["Gender"]=$myrow["BannerGender"];
			}

			if ($myrow["BannerCTC"] != "")
			{
				$criteria.="<B>CTC--></b>";
				$ctc	= substr($myrow["BannerCTC"],1,-1);
				$ctcarr	= explode(',',$ctc);
				
				for ($i = 0; $i < count($ctcarr); $i++)
				{
					$sql	= "SELECT LABEL FROM bms2.INCOME WHERE VALUE='$ctcarr[$i]'";
					$res 	= mysql_query($sql) or die("$sql".mysql_error());
					$row 	= mysql_fetch_row($res);
					$ctclabel[$i]	= $row[0];
					$criteria.=$ctclabel[$i]." +<br>";
				}
				$criteria = substr($criteria,0,-5);
				$criteria = $criteria."<br>";
				$returnarr["selected"]["Ctc"]=$criteria;
			}

			//added by lavesh rawat
			if ($myrow["BannerJsVd"]!="")
			{
				$criteria.="<B> VARIABLE DISCOUNT --></b>";
				$tempVal = substr($myrow["BannerJsVd"],1,-1);
				$tempArr=explode(",",$tempVal);
				global $vdArray;
				foreach($tempArr as $v)
				{
					$v=trim($v," ");
					$criteria.=$vdArray[$v]." +<br>";
				}
				$criteria = substr($criteria,0,-5);
				$criteria = $criteria."<br>";
				$returnarr["selected"]["vd"]=$criteria;
			}
                        if ($myrow["BannerJsProfileStatus"]!="")
                        {
                                $criteria.="<B> Profile Status --></b>";
                                $tempVal = substr($myrow["BannerJsProfileStatus"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $profileStatus;
				foreach($tempArr as $v)
                                {
					$v=trim($v," ");
                                        $criteria.=$profileStatus[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["profileStatus"]=$criteria;
                        }
                        if ($myrow["BannerJsMailID"]!="")
                        {
                                $criteria.="<B> Mail ID --></b>";
                                $tempVal = substr($myrow["BannerJsMailID"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $mailID;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$mailID[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["jsMailID"]=$criteria;
                        }
                        if ($myrow["BannerJsEoiStatus"]!="")
                        {
                                $criteria.="<B> EOI STATUS --></b>";
                                $tempVal = substr($myrow["BannerJsEoiStatus"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $eoiStatus;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$eoiStatus[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["vd"]=$criteria;
                        }

                        if ($myrow["BannerJsRegistrationStatus"]!="")
                        {
                                $criteria.="<B> REGISTRATION STATUS --></b>";
                                $tempVal = substr($myrow["BannerJsRegistrationStatus"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $registrationStatus;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$registrationStatus[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["jsRegistrationStatus"]=$criteria;
                        }

                        if($myrow["BannerJsFtoStatus"]!="")
                        {
                                $criteria.="<B>FTO STATUS --></b>";
                                $tempVal = substr($myrow["BannerJsFtoStatus"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $ftoState_array;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$ftoState_array[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["jsFtoStatus"]=$criteria;
                        }

                        if($myrow["BannerJsFtoExpiry"]!="")
                        {
                                $criteria.="<B>FTO Expiry --></b>";
                                $tempVal = substr($myrow["BannerJsFtoExpiry"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $ftoExpiry_array;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$ftoExpiry_array[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["jsFtoExpiry"]=$criteria;

                        }

                        if($myrow["BannerJsProfileCompletionState"]!="")
                        {
                                $criteria.="<B>Profile Completion --></b>";
                                $tempVal = substr($myrow["BannerJsProfileCompletionState"],1,-1);
                                $tempArr=explode(",",$tempVal);
                                global $profileCompletionState_array;
                                foreach($tempArr as $v)
                                {
                                        $v=trim($v," ");
                                        $criteria.=$profileCompletionState_array[$v]." +<br>";
                                }
                                $criteria = substr($criteria,0,-5);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["jsProfileCompletionState"]=$criteria;
                        }
			//added by lavesh rawat

			if ($myrow["BannerMARITALSTATUS"] != "")
                        {
                                $criteria.="<B>MARITAL STATUS--></b>";
                                $mstatus    = substr($myrow["BannerMARITALSTATUS"],1,-1);
                                $mstatusarr = explode(',',$mstatus);
                                                                                                                            
                                for ($i = 0; $i < count($mstatusarr); $i++)
                                {
					$mstatusval = trim($mstatusarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.MARITALSTATUS WHERE VALUE='$mstatusval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $mstatuslabel[$i]   = $row[0];
                                        $criteria.=$mstatuslabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-2);
                                $criteria = $criteria."<br>";
                                $returnarr["selected"]["MSTATUS"]=$criteria;
                        }
			if ($myrow["BannerMEM"] != "")
                        {
                                $criteria.="<B>SUBSCRIPTION--></b>";
                                $mem    = substr($myrow["BannerMEM"],1,-1);
                                $memarr = explode(',',$mem);
                                for ($i = 0; $i < count($memarr); $i++)
                                {
					$memval = trim($memarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.SUBSCRIPTION WHERE VALUE='$memval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $memlabel[$i]   = $row[0];
                                        $criteria.=$memlabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
				$criteria= $criteria."<br>";
                                $returnarr["selected"]["MEM"]=$criteria;
                        }
			if ($myrow["BannerREL"] != "")
                        {
                                $criteria.="<B>RELIGION--></b>";
                                $rel    = substr($myrow["BannerREL"],1,-1);
                                $relarr = explode(',',$rel);
                                for ($i = 0; $i < count($relarr); $i++)
                                {
                                        $relval = trim($relarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.RELIGION WHERE VALUE='$relval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $rellabel[$i]   = $row[0];
                                        $criteria.=$rellabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["REL"]=$criteria;
                        }
			if ($myrow["BannerEDU"] != "")
                        {
                                $criteria.="<B>EDUCATION--></b>";
                                $edu    = substr($myrow["BannerEDU"],1,-1);
                                $eduarr = explode(',',$edu);
                                for ($i = 0; $i < count($eduarr); $i++)
                                {
                                        $eduval = trim($eduarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.EDUCATION WHERE VALUE='$eduval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $edulabel[$i]   = $row[0];
                                        $criteria.=$edulabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["EDU"]=$criteria;
                        }
			if ($myrow["BannerOCC"] != "")
                        {
                                $criteria.="<B>OCCUPATION--></b>";
                                $occ    = substr($myrow["BannerOCC"],1,-1);
                                $occarr = explode(',',$occ);
                                for ($i = 0; $i < count($occarr); $i++)
                                {
                                        $occval = trim($occarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.OCCUPATION WHERE VALUE='$occval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $occlabel[$i]   = $row[0];
                                        $criteria.=$occlabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["OCC"]=$criteria;
                        }
			if ($myrow["BannerCOM"] != "")
                        {
                                $criteria.="<B>COMMUNITY--></b>";
                                $com    = substr($myrow["BannerCOM"],1,-1);
                                $comarr = explode(',',$com);
                                for ($i = 0; $i < count($comarr); $i++)
                                {
                                        $comval = trim($comarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.MTONGUE WHERE VALUE='$comval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $comlabel[$i]   = $row[0];
                                        $criteria.=$comlabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["COM"]=$criteria;
                        }
			if ($myrow["BannerPROPCITY"] != "")
                        {
                                $criteria.="<B>PROP CITY--></b>";
                                $propcity    = substr($myrow["BannerPROPCITY"],1,-1);
                                $propcityarr = explode(',',$propcity);
                                for ($i = 0; $i < count($propcityarr); $i++)
                                {
                                        $propcityval = trim($propcityarr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.PROPCITY WHERE VALUE='$propcityval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $propcitylabel[$i]   = $row[0];
                                        $criteria.=$propcitylabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["PROPCITY"]=$criteria;
                        }
			if ($myrow["BannerPROPTYPE"] != "")
                        {
                                $criteria.="<B>PROPERTY TYPE--></b>";
                                $proptype    = substr($myrow["BannerPROPTYPE"],1,-1);
                                $proptypearr = explode(',',$proptype);
                                for ($i = 0; $i < count($proptypearr); $i++)
                                {
                                        $proptypeval = trim($proptypearr[$i]);
                                        $sql    = "SELECT LABEL FROM bms2.PROPTYPE WHERE VALUE='$proptypeval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $proptypelabel[$i]   = $row[0];
                                        $criteria.=$proptypelabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["PROPTYPE"]=$criteria;
                        }
			if ($myrow["BannerPROPINR"] != "")
                        {
                                $criteria.="<B>PROPERTY INR--></b>";
                                $propinr    = substr($myrow["BannerPROPINR"],1,-1);
                                $propinrarr = explode(',',$propinr);
				$propcategory = $myrow["BannerPROPCAT"];
                                for ($i = 0; $i < count($propinrarr); $i++)
                                {
                                        $propinrval = trim($propinrarr[$i]);
					if ($propcategory == 'Buy')
                                        	$sql    = "SELECT LABEL FROM bms2.PROPINR WHERE VALUE='$propinrval'";
					elseif ($propcategory == 'Rent')
						$sql    = "SELECT LABEL FROM bms2.PROPINRRENT WHERE VALUE='$propinrval'";
                                        $res    = mysql_query($sql) or die("$sql".mysql_error());
                                        $row    = mysql_fetch_row($res);
                                        $propinrlabel[$i]   = $row[0];
                                        $criteria.=$propinrlabel[$i]." , ";
                                }
                                $criteria = substr($criteria,0,-3);
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["PROPINR"]=$criteria;
                        }
			if ($myrow["BannerPROPCAT"] != "" && $myrow["BannerPROPINR"] == "")
                        {
                                $criteria.="<B>PROPERTY CATEGORY--></b>".$myrow["BannerPROPCAT"];
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["PROPCAT"]=$criteria;
                        }
			elseif ($myrow["BannerPROPCAT"] != "")
			{
				$criteria.="<B>PROPERTY CATEGORY--></b>".$myrow["BannerPROPCAT"];
                                $criteria= $criteria."<br>";
                                $returnarr["selected"]["PROPCAT"]=$criteria;
			}
			if ($myrow["BannerAgeMin"] >= 0 && $myrow["BannerAgeMax"] >= 0)
			{
				$criteria.="<B>AGE--></b>".$myrow["BannerAgeMin"]." to ".$myrow["BannerAgeMax"]."<br>";
				$returnarr["selected"]["Age"]=$myrow["BannerAgeMin"].",".$myrow["BannerAgeMax"];
			}
                        if ($myrow["shikshaCountry"] != '' && $myrow["shikshaCountry"] != '')
                        {
                                $criteria.="<B>Shiksha Countries--></b>". $myrow["shikshaCountry"]."<br>";
                                $returnarr["selected"]["shikshaCountry"]=$myrow["shikshaCountry"];
                        }
                        if ($myrow["shikshaCategory"] != '' && $myrow["shikshaCategory"] != '')
                        {
                                $criteria.="<B>Shiksha Categories--></b>".$myrow["shikshaCategory"]."<br>";
                                $returnarr["selected"]["shikshaCategory"]=$myrow["shikshaCategory"];
                        }
                        if ($myrow["shikshaKeyword"] != '' && $myrow["shikshaKeyword"] != '')
                        {
                                $criteria.="<B>Shiksha Keywords--></b>".$myrow["shikshaKeyword"]."<br>";
                                $returnarr["selected"]["shikshaKeyword"]=$myrow["shikshaKeyword"];
                        }
                        if ($myrow["shikshaCity"] != '' && $myrow["shikshaCity"] != '')
                        {
                                $criteria.="<B>Shiksha City--></b>".$myrow["shikshaCity"]."<br>";
                                $returnarr["selected"]["shikshaCity"]=$myrow["shikshaCity"];
                        }
		}
	}
	$returnarr["criteria"]=$criteria;
	return $returnarr;
}

/**********************************************************************
        created by lavesh on 20 july.
        return current status of banner
***********************************************************************/
function current_banner_status($bannerid)
{
        $sql="SELECT BannerStatus FROM bms2.BANNER WHERE BannerId='$bannerid'";
        $res=mysql_query($sql) or die(mysql_error().$sql);
        $row=mysql_fetch_array($res);
        return($row["BannerStatus"]);
}

/****************************************************************************
   	updates the status of a banner
	input: bannerid,bannerstatus
	output:none
*****************************************************************************/
function updateBannerStatus($bannerid,$bannerstatus)
{
        global $dbbms;

        //added by lavesh to record history of deactivated banners
        if($bannerstatus=='deactive')
        {
                if(current_banner_status($bannerid)=='live')
                {
                        $dat=date("Y-m-d");
                        $sql="SELECT COUNT(*) AS CNT FROM bms2.DEACTIVE_HISTORY WHERE BannerId='$bannerid'";
                        $res=mysql_query($sql) or die(mysql_error().$sql);
                        $row=mysql_fetch_array($res);
                        $cnt=$row["CNT"];
                        if($cnt)
                        {
                                $sql="SELECT StartDt,EndDt,D.ZoneId as dzoneid , B.ZoneId as bzoneid,BannerStartDate FROM bms2.DEACTIVE_HISTORY D,BANNER B WHERE D.BannerId='$bannerid' AND D.BannerId=B.BannerId";
                                $res=mysql_query($sql) or die(mysql_error().$sql);
                                $row=mysql_fetch_array($res);
                                $new_start_dt=$row["StartDt"].'#'.$row["BannerStartDate"];
                                $new_end_dt=$row["EndDt"].'#'.$dat;
                                $new_zoneid=$row["dzoneid"].'#'.$row["bzoneid"];

                                $sql="UPDATE bms2.DEACTIVE_HISTORY SET StartDt='$new_start_dt',EndDt='$new_end_dt',ZoneId='$new_zoneid' WHERE BannerId='$bannerid'";
                                $res=mysql_query($sql) or die(mysql_error().$sql);
                        }
                        else
                        {
                                $sql="INSERT INTO bms2.DEACTIVE_HISTORY(BannerId,StartDt,EndDt,ZoneId)(SELECT BannerId,BannerStartDate,'$dat',ZoneId FROM bms2.BANNER WHERE BannerId='$bannerid')";
                                mysql_query($sql) or die(mysql_error().$sql);
                        }
                }
        }
        //Ends here

        $sql = "update bms2.BANNER set BannerStatus = '$bannerstatus' where BannerId = '$bannerid'";
        $res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php: updateBannerStatus:1: Could not update banner status<br>    <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

        //added by lavesh to alter colum criteriaInUse of zone table.(ops changes)
        //bannerid is passed here.
        if($bannerid)
        {
                $bannerStatusChanged=1;
		$skipphp5=1;
                include_once("cron/bms_banners_in_use.php");
                $sql_ops="UPDATE BANNER SET BannerCountry=CONCAT(' ', BannerCountry,' ') WHERE BannerCountry<>'' AND BannerId = '$bannerid'";
                mysql_query($sql_ops,$dbbms);
                $sql_ops="UPDATE BANNER SET BannerUsCity=CONCAT(' ',BannerUsCity,' ') WHERE BannerUsCity<>'' AND BannerId = '$bannerid'";
                mysql_query($sql_ops,$dbbms);
                $sql_ops="UPDATE BANNER SET BannerInCity=CONCAT(' ',BannerInCity,' ') WHERE BannerInCity<>'' AND BannerId = '$bannerid'";
                mysql_query($sql_ops,$dbbms);
        }
        //ends here.


        //Commented by lavesh as of no use.
        /*$sql = "SELECT BannerFixed FROM bms2.BANNER WHERE BannerId = '$bannerid'";
        $res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php: updateBannerStatus:1: Could not fetch banner fixed<br>    <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

        $row = mysql_fetch_array($res);*/

        if (1)//$row['BannerFixed']!= 'Y')
        {
                if ($bannerstatus == "live")
                        makeBannerHeapEntry($bannerid);
        }
}

/*****************************************************************************
	makes the entry of a banner in the banner heap table(only called when the banner is made live)	  
	input: bannerid
	output:none
******************************************************************************/
function makeBannerHeapEntry($bannerid)
{
	global $dbbms;
	$sqlexists = "select * from bms2.BANNERHEAP where BannerId = '$bannerid'";
	$resexists = mysql_query($sqlexists,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :1: Could not select from banner heap<br>	<!--$sqlexists<br>". mysql_error()."-->: ". mysql_errno(), $sqlexists, "ShowErrTemplate");

	if (mysql_num_rows($resexists))
	{
		//$sql = "update bms2.BANNERHEAP set BannerCount=0 where BannerId='$bannerid'";
		//$sql1 = "update bms2.BANNERHEAPCOPY set BannerCount=0 where BannerId='$bannerid'";
	}
	else
	{
		$sql = "insert into bms2.BANNERHEAP values('$bannerid','0','0')";
		$sql1 = "insert into bms2.BANNERHEAPCOPY values('$bannerid','0','0')";
		$res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :2: Could not insert into banner heap<br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		$res = mysql_query($sql1,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :2: Could not insert into banner heap<br> <!--$sql1<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	}
	//$res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :2: Could not insert into banner heap<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	//$res = mysql_query($sql1,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :2: Could not insert into banner heap<br> <!--$sql1<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

	/*$sqlcount="select b.BannerId as bannerid from bms2.BANNER a, bms2.BANNER b where a.ZoneId=b.ZoneId and a.BannerPriority=b.BannerPriority and a.BannerDefault=b.BannerDefault and a.BannerId='$bannerid'";
	$rescount=mysql_query($sqlcount,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :3: Could not select from banner heap<br>	<!--$sqlcount<br>". mysql_error()."-->: ". mysql_errno(), $sqlcount, "ShowErrTemplate");

	while ($myrow = mysql_fetch_array($rescount))
	{
		$str.="'$myrow[bannerid]',";
	}
	$str = substr($str,0,-1);
	$str="(".$str.")";
	$sqlreplace = "update bms2.BANNERHEAP set BANNERCOUNT=0 where BannerId in $str";
	$rescount = mysql_query($sqlreplace,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :4: Could not update banner heap<br>	<!--$sqlreplace<br>". mysql_error()."-->: ". mysql_errno(), $sqlreplace, "ShowErrTemplate");
	$sqlreplace = "update bms2.BANNERHEAPCOPY set BANNERCOUNT=0 where BannerId in $str";
        $rescount = mysql_query($sqlreplace,$dbbms) or logErrorBms("./includes/bms_connect.php: makeBannerHeapEntry :4: Could not update banner heap<br>  <!--$sqlreplace<br>". mysql_error()."-->:
". mysql_errno(), $sqlreplace, "ShowErrTemplate");*/
}

/******************************************************************************
 	fetches array banner priorities allowed in a zone.
 	if priority is specified , selected field of the array will be set
	input : zoneid
	output: array of priority
*******************************************************************************/
function getBannerPriority($zoneid,$bannerpriority)
{
	global $dbbms;
	$sql = "SELECT max(BannerPriority)+1  as maxpriority FROM bms2.BANNER  WHERE ZoneId = '$zoneid' ";
	$res = mysql_query($sql,$dbbms) or logErrorBms("./includes/bms_connect.php: getBannerPriority :1: Could not select max banner priority<br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$maxpriority = $myrow["maxpriority"];

	if (!$maxpriority)
		$maxpriority = 1;
	$sqlzone = "select ZoneMaxBans as zonemaxbans from ZONE where ZONE.ZoneId = '$zoneid'";
	$reszone = mysql_query($sqlzone,$dbbms) or logErrorBms("./includes/bms_connect.php: getBannerPriority :2: Could not select max zone banners<br>	<!--$sqlzone<br>". mysql_error()."-->: ". mysql_errno(), $sqlzone, "ShowErrTemplate");
	$myrowzone = mysql_fetch_array($reszone);
	$maxbannerallowed = $myrowzone["zonemaxbans"];

	if ($maxpriority > $maxbannerallowed)
		$maxpriority = $maxbannerallowed;

	return getBannerPriorityArr($maxpriority,$bannerpriority);

}

/*************************************************************************************
	used in the getBannerPriority function to return the array of priorities
	input : max priority allowed , seleted banner priority
	output: banner priority array
*************************************************************************************/
function getBannerPriorityArr($maxpriority,$bannerpriority)
{
	for ($i = 0;$i < $maxpriority;$i++)
	{	
		$bannerpriorityarr[$i]["value"] = $i+1;
		$bannerpriorityarr[$i]["name"]  = $i+1;
		if (($i+1) == $bannerpriority)
		{
			$bannerpriorityarr[$i]["selected"] = "selected";
		}
	}
	return $bannerpriorityarr;
}

/*************************************************************************************
	changes the status of a campaign 
	input : campaign id and campaignstatus
	output: none
**************************************************************************************/
function ChangeCampaignStatus($campaignid,$campaignstatus)
{
	global $dbbms;
	$sql="update bms2.CAMPAIGN  set CampaignStatus='$campaignstatus' where CampaignId='$campaignid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:ChangeCampaignStatus:4: Could not change status of a campaign. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

}

/***************************************************************************************
	Returns Location Id listing for the given city
	input : cityid
	output: array of location ids
***************************************************************************************/
function getLocids($city)
{
 	global $dbbms;
 	$sql = "Select LocIds from IPCITIES where CityId = '$city'";
 	$result = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getLocids:1: Could not select cities <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

 	if ($myrow = mysql_fetch_array($result))
 	{
 		$locids = $myrow["LocIds"];
 	}
 	return $locids;
}
 
/****************************************************************************************
	Used for formatting a given string(removing space , backslashes etc.)
	input : string
	output: formatted string
****************************************************************************************/
function parseKeywords($keywords)
{
	$keywords=trim($keywords);
	$keywords=strtoupper($keywords);
	$keywords=preg_replace("/\s+/"," ",$keywords);
	$keywords=str_replace("#","|X|",$keywords);	
	$keywords=str_replace("C++","C~~",$keywords); 
	$keywords=stripslashes(urldecode($keywords));
	$keywords=str_replace("C~~","C++",$keywords);
	$keywords=str_replace("\"\"",",",$keywords);
	$keywords=str_replace("\"","",$keywords);
	$keywords=str_replace("''",",",$keywords);
	$keywords=str_replace("'","",$keywords);
	$keywords=str_replace(" AND ",",",$keywords);
	$keywords=str_replace(" OR ",",",$keywords);
	$keywords=str_replace(" NOT ",",",$keywords);
	$keywords=str_replace(")(",",",$keywords);
	$keywords=str_replace("(","",$keywords);
	$keywords=str_replace(")","",$keywords);
	$keywords=str_replace("!","",$keywords);
	$keywords=str_replace(" / ",",",$keywords);
	$keywords=str_replace("/ ",",",$keywords);
	$keywords=str_replace(" /",",",$keywords);
	$keywords=str_replace("/",",",$keywords);
	$keywords=str_replace(" , ",",",$keywords);		
	$keywords=str_replace(" ,",",",$keywords);	
	$keywords=str_replace(", ",",",$keywords);
	$keysarr=explode(",",$keywords);
            /*for($i=0;$i<count($keysarr);$i++)
            {
                if(strpos(trim($keysarr[$i]),' ')!==false)
                {
                    $addkeys=explode(' ',$keysarr[$i]);
                    for($j=0;$j<count($addkeys);$j++)
                    {
                        $cnt=count($keysarr);
                        if(trim($addkeys[$j])!='')
                        {
                        	if(!in_array($addkeys[$j],$keysarr))	$keysarr[$cnt]=$addkeys[$j];
                        }
                    }
                }
            }*/
	$keywords=implode(",",$keysarr);
	return $keywords;
}

/*******************************************************************************
	returns an array of region name, zone name and banner height and width 
	allowed	in a zone
	input : zoneid
	output: array of zone info
********************************************************************************/
function getZoneParam($zoneid)
{
	global $dbbms;
	$sql = "select ZONE.ZoneName as zonename, REGION.RegName as regionname, ZONE.ZoneBanHeight as zoneheight,ZONE.ZoneBanWidth as zonewidth from bms2.REGION,bms2.ZONE where REGION.RegId=ZONE.RegId and ZONE.ZoneId = '$zoneid'";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getZoneParam:1: Could not select zone parameters. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$zoneparam["zonename"]		= $myrow["zonename"];
	$zoneparam["regionname"]	= $myrow["regionname"];
	//$zoneparam["heightwidth"]	= $myrow["zoneheight"]." x ".$myrow["zonewidth"] ;
	$zoneparam["heightwidth"]       = $myrow["zonewidth"]." x ".$myrow["zoneheight"] ;

	return $zoneparam;
}

/*******************************************************************************
	returns an array of campaign start and end date 
	input : campaignid
	output: array of campaign start and end dates
*******************************************************************************/
function getCampaignDate($campaignid)
{
	global $dbbms,$smarty;
	$sql = "select CampaignStartDt,CampaignEndDt from bms2.CAMPAIGN where CampaignId='$campaignid'";
	$res = mysql_query($sql,$dbbms);
	$myrow = mysql_fetch_array($res);
	$campaignarr["startdate"]	= $myrow["CampaignStartDt"];
	$campaignarr["enddate"]		= $myrow["CampaignEndDt"];

	return $campaignarr;
}

/*******************************************************************************
	fetches the details of all campaigns corresponding to a status/company
	input:campaignstatus
	output:array of all campaigns with above status
*******************************************************************************/
function getCampaigns($campaignstatus = "",$companyid ="",$campaignid ="")
{
	global $dbbms;
	if (!$campaignstatus)
		$campaignstatus ="all";
	if ($companyid)
		$addquery =" where CompanyId='$companyid'";
	if ($campaignid)
	{
		if ($addquery)
			$addquery.=" and CampaignId = '$campaignid' ";
		else
			$addquery.=" where CampaignId = '$campaignid' ";
	}
	if ($campaignstatus == "all")
		$sql = "select * from bms2.CAMPAIGN   ".$addquery." order by CampaignEntryDate desc";
	else
	{
		if ($addquery)
			$sql = "select * from bms2.CAMPAIGN ".$addquery." and CampaignStatus = '$campaignstatus'  order by CampaignEntryDate desc";
		else
			$sql = "select * from bms2.CAMPAIGN where  CampaignStatus = '$campaignstatus'  order by CampaignEntryDate desc";
	}

	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:getCampaignDetailsStatusWise:3: Could not get status wise campaign details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i = 0;
	while ($myrow = mysql_fetch_array($res))
	{
		$campaigndetails[$i]["campaignid"]=$myrow["CampaignId"];
		$campaigndetails[$i]["campaignname"]=$myrow["CampaignName"];
		$campaigndetails[$i]["campaigntype"]=$myrow["CampaignType"];
		$campaigndetails[$i]["campaignstatus"]=$myrow["CampaignStatus"];
		$campaigndetails[$i]["campaignentrydate"]=$myrow["CampaignEntryDate"];
		$campaigndetails[$i]["companyid"]=$myrow["CompanyId"];
		$campaigndetails[$i]["campaignimpression"]=$myrow["CampaignImpressions"];
		$campaigndetails[$i]["transactionid"]=$myrow["TransactionId"];
 		$campaigndetails[$i]["executiveid"]=$myrow["CampaignExecutiveId"];
		$campaigndetails[$i]["campaignstartdate"]=$myrow["CampaignStartDt"];
		$campaigndetails[$i]["campaignenddate"]=$myrow["CampaignEndDt"];
		$campaigndetails[$i]["showmis"]=$myrow["Showmis"];
		$campaigndetails[$i]["misdetails"]=$myrow["Misoption"];
		$campaigndetails[$i]["comments"]=$myrow["COMMENTS"]; //Added by aman
		$i++;
	}
	return $campaigndetails;
}

/****************************************************************************
	fetches the various possible campaign status
	input: none
	ouput: array of campaign status
*****************************************************************************/
function getCampaignStatusArr()
{
	$campaignstatusarr = array("all","new","active","deactive","served");
	return $campaignstatusarr;
}
/****************************************************************************
        creates dropdown for client mis options
        input: selected option, type viz misoption , minormax ,labelselected
        ouput: dropdown of mis options
*****************************************************************************/
function create_dd($selected,$cname,$minormax=0,$labelselect="")
{
	global $dbbms,$smarty;
        if(is_array($selected))
        {
                $s_arr = $selected;
                //$selected = array();
        }
        elseif($selected!="")
        {
                $s_arr=explode(",",$selected);
        }
        else
                $s_arr=array();
                                                                                                                             
        $muli ="[]";
        if ($cname == "misopt")
        {
                $sql = "select SQL_CACHE  OptionLabel, OptionId from bms2.MISOPTIONS";
                $res = mysql_query($sql,$dbbms) or die("$sql".mysql_error());
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
			 if(in_array($myrow["OptionId"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[OptionId]\" selected>$myrow[OptionLabel]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[OptionId]\">$myrow[OptionLabel]</option>\n";
                        }
                }
        }
	if ($cname == "city")
        {
                $sql = "select SQL_CACHE  VALUE, LABEL from bms2.LOC_CITIES";
                $res = mysql_query($sql,$dbbms) or die("$sql".mysql_error());
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                         if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }

	return $ret;
}
function getLocInCity($bannercity)
{
        global $dbbms;
        $loc_cityarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannercity))));
        $sql = "select VALUE as value,LABEL from bms2.LOC_CITIES where COUNTRY='51'";
        $res = mysql_query($sql) or logErrorBms("bms_connect.inc:getLocCity:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $loc_citiesarr[$i]["name"]  = $myrow["LABEL"];
                $loc_citiesarr[$i]["value"]  = $myrow["value"];
                if(in_array("$myrow[value]",$loc_cityarr))
                {
                        $loc_citiesarr[$i]["selected"] = "selected";
                }
                else
                {
                        $loc_citiesarr[$i]["selected"] = "";
                }
                 $i++;
        }
        return $loc_citiesarr;
}
function getLocUsCity($bannercity)
{
        //print_r($bannerip);
        global $dbbms;
        $loc_cityarr = explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannercity))));
        $sql = "select VALUE as value,LABEL from bms2.LOC_CITIES where COUNTRY='127'";
        $res = mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getLocCity:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $loc_citiesarr[$i]["value"] = $myrow["value"];
                $loc_citiesarr[$i]["name"]  = $myrow["LABEL"];
                if(in_array("$myrow[value]",$loc_cityarr))
                        $loc_citiesarr[$i]["selected"] = "selected";
                else
                        $loc_citiesarr[$i]["selected"] = "";
                 $i++;
        }
        return $loc_citiesarr;
}
function getLocCntry($country)
{
        global $dbbms;
        $banneripstr=implode("','",$country);
        $banneripstr="('".$banneripstr."')";
        $ipcitiesstr="";
        $sql="select LABEL from bms2.COUNTRY where VALUE in $banneripstr";
        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_advbannerdetails.php :getIpLoc:1: Could not select ip cities<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
                                                                                                                            
        while($myrow=mysql_fetch_array($res))
        {
                $ipcitiesstr.=str_replace(","," , ",trim($myrow["LABEL"]))." , ";
        }
        $ipcitiesstr=substr($ipcitiesstr,0,-3);
        return $ipcitiesstr;
}

function getLocCity($city)
{
        global $dbbms;
        $ipcitiesstr="";
        $sql="select LABEL from bms2.LOC_CITIES where VALUE='$city'";
        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_advbannerdetails.php :getIpLoc:1: Could not select ip cities<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
        while($myrow=mysql_fetch_array($res))
        {
                $ipcitiesstr.=str_replace(" , "," , ",trim($myrow["LABEL"]))." , ";
        }       $ipcitiesstr=substr($ipcitiesstr,0,-3);
        return $ipcitiesstr;
}
function getLocCountry($bannerctry)
{
        global $dbbms;
        $loc_countryarr=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerctry))));
        $sql="select VALUE , LABEL from bms2.COUNTRY";
        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_connect.inc:getLocCountry:1: Could not select cities <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $loc_ctryarr[$i]["value"] = $myrow["VALUE"];
                $loc_ctryarr[$i]["name"] = $myrow["LABEL"];                                                                                                                             
                if(in_array("$myrow[VALUE]",$loc_countryarr))
                       $loc_ctryarr[$i]["selected"] = "selected";
                else
                       $loc_ctryarr[$i]["selected"] = "";
                $i++;
        }
        return $loc_ctryarr;
}

function getConnection99acres()
{
        
        if(!$db99acres = @mysql_connect(MysqlDbConstants::$BMS_99[HOST],MysqlDbConstants::$BMS_99[USER],MysqlDbConstants::$BMS_99[PASS]))
        {
                logErrorBms("Billing Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
        }
        @mysql_select_db("sums",$db99acres);
        return $db99acres;
}

function getConnection205()
{
        if(!$db205 = @mysql_connect(MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]))
        {
                logErrorBms("Billing Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
        }
        @mysql_select_db("billing",$db205);
        return $db205;
}
                                                                                                                            
function authenticated($checksum="")
{
        global $TOUT;
	$db205 = getConnection205();
        list($md, $userno)=explode("i",$checksum);
	//echo  $TOUT;                                                                                                                              
        if(md5($userno)!=$md)
            return NULL;
        else
        {
        $sql_chk = "select * from jsadmin.CONNECT where ID=$userno";
        $res_chk = mysql_query($sql_chk,$db205) or die("Could not Authenticate User: auError $sql_chk ".mysql_error());
        $count = mysql_num_rows($res_chk);
                                                                                                                             
        if ($count > 0)
        {
                $myrow = mysql_fetch_array($res_chk);
                if (time()-$myrow["TIME"] < $TOUT)
                {
                        $tm = time();
                        $sql_up = "update jsadmin.CONNECT set TIME=$tm where ID=$userno";
                        $res_up = mysql_query($sql_up,$db205) or die("Could not Authenticate User: upError");
                                                                                                                             
                        $ret["ID"] = $md."i".$userno;
                        $ret["USER"]=$myrow["USER"];
                                                                                                                             
                        //$ret = TRUE;
                }
                else
                {
                        $ret = NULL;
                }
        }
        else
        {
                $ret = NULL;
        }
        }
                                                                                                                             
return $ret;
}

