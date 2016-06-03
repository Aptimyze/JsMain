<?php
//to zip the file before sending it

$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

$offset=60*60*3;//time to be cached:3 hrs
header("Cache-Control: public,max-age=$offset,s-maxage=$offset");

$path= $_SERVER['DOCUMENT_ROOT'];

include_once "connect.inc";
include_once "search.inc";
include_once "search_band_functions.inc";
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");

$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

$db=connect_db();
$data=authenticated($checksum);
header('content-type: text/xml');

if(!$searchid && $data)
{
	$profileid=$data["PROFILEID"];
	$mem_key=$profileid."topsearchband1";
	$mem_key_total=$profileid."totaltopsearch1";

	//This function will check whether record exist and return data only when table is not updated.
	$is_set=memcache_call($mem_key);
	if($is_set)
	{
		$whole_content=memcache_call($mem_key_total);
		if($whole_content)
			die($whole_content);
	}
	if(1)
	{
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		$jpartnerObj=new Jpartner;
		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	 	$myDb=$mysqlObj->connect("$myDbName");

		$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
		if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
		{
			$searchrow["LAGE"]=$jpartnerObj->getLAGE();
			$searchrow["HAGE"]=$jpartnerObj->getHAGE();
			$searchrow["RELIGION"]=trim($jpartnerObj->getPARTNER_RELIGION(),"'");
			$searchrow["MTONGUE"]=trim($jpartnerObj->getPARTNER_MTONGUE(),"'");
			$searchrow["CASTE"]=trim($jpartnerObj->getPARTNER_CASTE(),"'");
			$searchrow["COUNTRY_RES"]=trim($jpartnerObj->getPARTNER_COUNTRYRES(),"'");
			$searchrow["CITY_RES"]=trim($jpartnerObj->getPARTNER_CITYRES(),"'");
			if($data['GENDER']=='F')
				$searchrow["GENDER"]='M';
			else
				$searchrow["GENDER"]='F';

			$searchrow["MSTATUS"]=trim($jpartnerObj->getPARTNER_MSTATUS(),"'");
			
			//Save with value 1 to set back the value to memcache.
			$from_jpartner=1;
			memcache_call($mem_key,1,3600);
			
		}
	}	
}
elseif($searchid)
{
	$sql="select RELIGION,SEARCH_TYPE,GENDER,IF(CASTE_DISPLAY<>'',CASTE_DISPLAY,CASTE) AS CASTE,CASTE_DISPLAY,MTONGUE,LAGE,HAGE,HAVEPHOTO AS WITHPHOTO,COUNTRY_RES,MSTATUS,ONLINE,CITY_INDIA AS CITY_RES,COUNTRY_RES FROM SEARCHQUERY where ID='$searchid'";//ONLINE is added by manoranjan
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$searchrow=mysql_fetch_array($result);
}
else
{
	if($seopages)
	       $searchrow["$field"]=$value;

	if($newfooter)
	{
		$field=explode('-',$field);
		$value=explode('-',$value);
	       	$searchrow["$field[0]"]=$value[0];
	       	$searchrow["$field[1]"]=$value[1];
	       	$searchrow["$field[2]"]=$value[2];
	}

	$searchrow["LAGE"]=21;
	$searchrow["HAGE"]=35;
	$searchrow["WITHPHOTO"]='Y';
	//This variable means no change in calculation of logout user, hence saving the data in memcache.
	$from_logout=1;

	if($source=="sulekha")
		$whole_content=memcache_call("LOGOUTDATA1_sulekha");
	else
		$whole_content=memcache_call("LOGOUTDATA1");
	
	if($seopages || $newfooter)
		$whole_content="";

        if($whole_content)
		die($whole_content);
}

$xml1 = new DomDocument;
//load the required xml file.
if($seopages)
	loadMyXml($path."/seopages/topSearchBand_opt.xml");
else
	loadMyXml($path."/profile/topSearchBand_opt.xml");

createXmlTag("registrationPage1","checkParams","SITE_URL",utf8_encode($SITE_URL));
createXmlTag("registrationPage1","checkParams","IMG_URL",utf8_encode($IMG_URL));

$db=connect_737_ro();

$smarty->assign("Caste_display",$searchrow["CASTE_DISPLAY"]);
//added by Manoranjan for implementing online search also
if($searchrow["SEARCH_TYPE"]!='O')
{
	$smarty->assign("searchonline",$searchrow["ONLINE"]);
}

/* For Browse Community Section */
if($searchrow["CASTE"] && !$searchrow["RELIGION"])
{
	if(!strstr($searchrow["CASTE"],","))
	{
        $sqlT="SELECT PARENT FROM newjs.CASTE WHERE VALUE='$searchrow[CASTE]'";
        $resultT=mysql_query_decide($sqlT) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $searchrowT=mysql_fetch_array($resultT);
        $searchrow["RELIGION"]=$searchrowT['PARENT'];
	}
}
/* End of Browse Matrimony Section */

if(!$STYPE)						     
	$STYPE = $searchrow["SEARCH_TYPE"];
if($searchrow["MTONGUE"])
	$Mtongue=explode(",",$searchrow["MTONGUE"]);

$caste_top_band=$searchrow["CASTE"];
createXmlTag("registrationPage1","checkParams","casteVal",utf8_encode($searchrow["CASTE"]));

// Previous code
if($searchrow["CASTE"])
	$Caste=explode(",",$searchrow["CASTE"]);
if($searchrow["CASTE_DISPLAY"])
	$Caste_display=explode(",",$searchrow["CASTE_DISPLAY"]);

for($i=0;$i<count($Caste_display);$i++)
{
	$caste_label=small_label_select("CASTE",$Caste_display[$i]);
	$caste_label[0]=ltrim($caste_label[0],'-');
	$Caste_res.=$caste_label[0].";";
}

for($i=0;$i<count($Mtongue);$i++)
{
	$mtongue_label=label_select("MTONGUE",$Mtongue[$i]);
	$Mtongue_res.=$mtongue_label[0].";";
}

if($searchrow["CASTE_DISPLAY"])
	$Caste_val=$searchrow["CASTE_DISPLAY"];
if($searchrow["MTONGUE"])
	$Mtongue_val=$searchrow["MTONGUE"];

$smarty->assign("Caste_val",$Caste_val);
$Caste_res=substr($Caste_res,0,-1);
$smarty->assign("Caste_res",$Caste_res);

$smarty->assign("Mtongue_val",$Mtongue_val);
$Mtongue_res=substr($Mtongue_res,0,-1);
$smarty->assign("Mtongue_res",$Mtongue_res);
// Previous code

//Gender -->
$Gender=$searchrow["GENDER"];
createXmlTag("registrationPage1","checkParams","Gender",utf8_encode($Gender));
//Gender -->

//Age-->
$lage=$searchrow["LAGE"];
$hage=$searchrow["HAGE"];
fillAgeArray($Gender,$lage,$hage);
//Age-->

//Mstaus-->
$hp_mstatus=$searchrow["MSTATUS"];
if($hp_mstatus=='DONT_MATTER')
	$doesnt_mater_arr_2=1;
else
{
	if(strstr($hp_mstatus,"D"))
		createXmlTag("registrationPage1","checkParams","hp_mstatus",utf8_encode('E'));
	else
		createXmlTag("registrationPage1","checkParams","hp_mstatus",utf8_encode($hp_mstatus));
}
//Mstaus-->

//Have-Photo-->
$Photos=$searchrow["WITHPHOTO"];
createXmlTag("registrationPage1","checkParams","Photos",utf8_encode($Photos));
//Have-Photo-->

//Extra Parameters
createXmlTag("registrationPage1","checkParams","CHECKSUM",utf8_encode($checksum));
createXmlTag("registrationPage1","checkParams","SEARCHID",utf8_encode($searchid));

createXmlTag("registrationPage1","checkParams","STYPE",utf8_encode(topSearchStype($STYPE,$E_CLASS_SEARCH,$E_CLASS,$searchonline)));
createXmlTag("registrationPage1","checkParams","E_CLASS",utf8_encode($E_CLASS));

//Variable,coming from Community Page to display a line, added on Top Search Band.
if($matri)
	$smarty->assign("matri",1);
//Extra Parameters

//$smarty->assign("Caste",$Caste);
//$smarty->assign("Mtongue",$Mtongue);
$temp_caste=serialize($Caste);
if($searchrow["CITY_RES"]=='DONT_MATTER')
	$doesnt_mater_arr_3=1;
elseif($searchrow["CITY_RES"])
	$city=$searchrow["CITY_RES"];
else
	$city=$searchrow["COUNTRY_RES"];

//NEW XML-TAGS Created
$religion_string =religionInTopSearchBand($searchrow["RELIGION"]);
createXmlTag("registrationPage1","religion_label","religion_string",utf8_encode($religion_string));

$caste_string =populate_caste_topband($temp_caste,$STYPE);
createXmlTag("registrationPage1","caste_label","caste_string",utf8_encode($caste_string));

$country_string =cityInTopSearchBand($city);
createXmlTag("registrationPage1","country_label","country_string",utf8_encode($country_string));

$mtongue_caste =populate_mtongue_caste(0,$Mtongue_val);
createXmlTag("registrationPage1","mtongue_label","mtongue_string",utf8_encode($mtongue_caste));
//NEW XML-TAGS Created Ends

if($searchrow["RELIGION"]=='DONT_MATTER')
	$doesnt_mater_arr_0=1;
if($searchrow["MTONGUE"]=='DONT_MATTER')
	$doesnt_mater_arr_1=1;

//NEW XML-TAGS Created
createXmlTag("registrationPage1","checkParams","doesnt_mater_arr_0",utf8_encode($doesnt_mater_arr_0));
createXmlTag("registrationPage1","checkParams","doesnt_mater_arr_1",utf8_encode($doesnt_mater_arr_1));
createXmlTag("registrationPage1","checkParams","doesnt_mater_arr_2",utf8_encode($doesnt_mater_arr_2));
createXmlTag("registrationPage1","checkParams","doesnt_mater_arr_3",utf8_encode($doesnt_mater_arr_3));
createXmlTag("registrationPage1","checkParams","SOURCE",utf8_encode($source));
//NEW XML-TAGS Created Ends

//save the changes made in the loaded xml file.
$content =$xml1->saveXML();

if($from_jpartner || $from_logout)
{
	if($from_jpartner)
	{
		memcache_call($mem_key_total,$content,3600);
	}
	elseif($from_logout)
	{
		if(!$seopages && !$newfooter){
		if($source=="sulekha")
			memcache_call("LOGOUTDATA1_sulekha",$content,36000);
		else
			memcache_call("LOGOUTDATA1",$content,36000);
		}
	}
}
die($content);

//=====================================       FUNCTIONS ADDED      ============================================

function gendereInTopSearchBand($Gender)
{
	global $data;
	if($Gender)
		return $Gender;

	if($data["GENDER"]=='F')
		return 'M';
	else
		return 'F';
}

function cityInTopSearchBand($cityRes)
{
	$topFiveCities[128]='USA';
	$topFiveCities[125]='United Arab Emirates';
	$topFiveCities[126]='United Kingdom';
	$topFiveCities[7]='Australia';
	$topFiveCities[22]='Canada';

	$mappedToMoreCity=array('DE00','MH04');

	$moreCity['DE00'][0]="DE00,UP25,HA03,HA02,UP12,UP47,UP48";//Delhi, Noida, Gurgaon, ***Greater Noida***, Faridabad, Ghaziabad, ***Sahibabad***
	$moreCity['DE00'][1]='Delhi NCR';

	$moreCity['MH04'][0]='MH04,MH12,MH28,MH29';//Mumbai, ***Navi Mumbai**, Thane, ***Bhyander**
	$moreCity['MH04'][1]='Mumbai Region';

	$topInidanCities="'DE00','MH04','KA02','MH08','AP03','WB05','TN02','GU01','UP19','PH00','MH05','RA07','HA03','MP02','MP08','UP25','BI06','OR01','UP12','PU07'";

	foreach($topFiveCities as $k=>$v)
	{
		if("$k"==$cityRes)
			$option_string.= "<option value=\"$k\" selected>$v</option>";
		else
			$option_string.= "<option value=\"$k\">$v</option>";
	}
	$option_string .= "<option value=\"\" disabled></option>";//seperator
	if($cityRes==51)
		$option_string.= "<option value=\"51\" selected>All India</option>";
	else
		$option_string.= "<option value=\"51\">All India</option>";
	
	if($cityRes)
	{
		if(strstr($cityRes,","))
		{
			$cityRes1=str_replace("'","",$cityRes);
			$cityRes1=str_replace('"',"",$cityRes1);
			$cityResTempArr=explode(",",$cityRes1);
			sort($cityResTempArr);
			$cityResTempStr=implode("",$cityResTempArr);
		}
	}

	$option_string.= "<option value=\"\" disabled></option>";//seperator
        $sql = "SELECT SQL_CACHE VALUE,LABEL FROM CITY_NEW  WHERE VALUE IN ($topInidanCities)  order by LABEL ";
        $res = mysql_query_decide($sql) or logError("error",$sql);
        while($row = mysql_fetch_array($res))
	{
		if(in_array($row[0],$mappedToMoreCity))
		{
			$tempV=$row[0];	
			$lableMore=$moreCity[$tempV][1];
			$valueMore=$moreCity[$tempV][0];

			unset($valueMoreTempStr);
			if($cityResTempStr)
			{
				$valueMoreTempArr1=explode(",",$valueMore);
				sort($valueMoreTempArr1);
				$valueMoreTempStr=implode("",$valueMoreTempArr1);		
			}
			if($cityResTempStr && $cityResTempStr==$valueMoreTempStr)
                                $option_string.= "<option value=\"$valueMore\" selected>$lableMore</option>";
                        else
                                $option_string.= "<option value=\"$valueMore\">$lableMore</option>";
		}
		else
		{
			if($cityRes==$row[0])
				$option_string.= "<option value=\"$row[0]\" selected>$row[1]</option>";
			else
				$option_string.= "<option value=\"$row[0]\">$row[1]</option>";
		}
	}
	return $option_string;
}

function religionInTopSearchBand($religion_val)
{
	global $smarty;
	createXmlTag("registrationPage1","checkParams","selectedRel",utf8_encode($religion_val));

	$doesntmatter_religion = 0;
        $sql = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.RELIGION ORDER BY SORTBY";
        $res = mysql_query_decide($sql) or logError("error",$sql);
        while($row = mysql_fetch_array($res))
        {
                $religion_label_arr[] = $row['LABEL'];
                $religion_value = $row['VALUE'];

                if(!$doesntmatter_religion)
                        $doesntmatter_religion = 1;

                $sql_caste = "SELECT SQL_CACHE VALUE,LABEL,ISGROUP from CASTE WHERE PARENT='$religion_value' ORDER BY SORTBY";
                $res_caste = mysql_query_decide($sql_caste) or logError("error",$sql);
                while($row_caste = mysql_fetch_array($res_caste))
                {
                        $caste_value = $row_caste['VALUE'];
                        $caste_label_arr = explode(": ",$row_caste['LABEL']);
                        if($caste_label_arr[1])
                                $caste_label = $caste_label_arr[1];
                        else
                                $caste_label = $caste_label_arr[0];

			//NEW
			if($row_caste['ISGROUP']=='Y')
			{
				$caste_label="All ".$caste_label;
				$list_all_caste_labels.=$caste_value.",";
			}
			//NEW
                        $caste_str .= $caste_value."$".$caste_label."#";
                }
                $religion_str = $religion_value."|X|".$caste_str;
                $religion_value_arr[] = substr($religion_str,0,strlen($religion_str)-1);
                unset($caste_str);
                unset($religion_str);
        }
	//print_r($religion_value_arr);
        for($i=0;$i<count($religion_value_arr);$i++)
        {
                $temp_rel = explode("|X|",$religion_value_arr[$i]);
                if($religion_val == $temp_rel[0])
                        $option_string .= "<option value=\"$religion_value_arr[$i]\" selected=\"yes\">$religion_label_arr[$i]</option>";
                else
                        $option_string .= "<option value=\"$religion_value_arr[$i]\">$religion_label_arr[$i]</option>";
        }
	$list_all_caste_labels=",".$list_all_caste_labels;

	createXmlTag("registrationPage1","checkParams","list_all_caste_labels",utf8_encode($list_all_caste_labels));
	//$proc1->setParameter("","list_all_caste_labels",$list_all_caste_labels);	

	return $option_string;
}

function populate_caste_topband($Caste,$religion)
{
	$Caste=unserialize($Caste);	

	if(is_array($Caste))
		$sel_arr_caste = $Caste;

	if(count($sel_arr_caste)>1)
		$noSelect = 1;

	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
	$sql_religion = "select SQL_CACHE a.LABEL , a.VALUE, a.ISALL, a.ISGROUP from CASTE as a,CASTE as b where a.PARENT=b.PARENT and b.ISALL='Y' order by b.TOP_SORTBY,a.SORTBY";
	//REVAMP JS_DB_CASTE

	$res_religion = mysql_query_decide($sql_religion) or logError("error",$sql_religion);
	$ret_religion = "";
	while($myrow_religion = mysql_fetch_array($res_religion))
	{
		if ($myrow_religion['ISALL'] == 'Y')
		{
			$ret_religion .= "<option  value=\"\" disabled=\"disabled\"></option>";
			$ret_religion1 = "<option  value=\"\" disabled=\"disabled\"></option>";
			$class = "dropbg";
		}
		//REVAMP JS_DB_CASTE
		elseif (is_part_of_a_group($myrow_religion['VALUE']) && $myrow_religion['ISGROUP'] == 'Y')
		{
			$class = "dropcolour";
		}
		else
		{
			$class = "";
		}
		if(@ereg(":",$myrow_religion['LABEL']))
		{
			$label=explode(":",$myrow_religion['LABEL']);
			$reg=$label[1];
		}
		else
			
$reg=$myrow_religion['LABEL'];

		if($myrow_religion['ISGROUP']=='Y')
			$reg="All ".$reg;

		if(is_array($sel_arr_caste) && in_array($myrow_religion["VALUE"],$sel_arr_caste))
		{
			if($myrow_religion['ISGROUP']=='Y'){
				$ret_religion1 = "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" style=\"color:#e06400;\" selected>$reg</option>";
				$ret_religion .= "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" style=\"color:#e06400;\" selected>$reg</option>";
			}
			else{	
				
				if($noSelect==1)
				{
					$ret_religion1 = "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" >$reg</option>";
					$ret_religion .= "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\">$reg</option>";
				}
				else
				{
					$ret_religion1 = "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" selected>$reg</option>";
					$ret_religion .= "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" selected>$reg</option>";
				}
			}
		}
		else
		{
			if ($myrow_religion['ISALL'] == 'Y'){
				$ret_religion .= "<option class=\"$class\" style=\"background-color:#ffd84f\" value=\"$myrow_religion[VALUE]\">$reg</option>";
				$ret_religion1 = "<option class=\"$class\" style=\"background-color:#ffd84f\" value=\"$myrow_religion[VALUE]\">$reg</option>";
			}
			else
			{
				if($myrow_religion['ISGROUP']=='Y'){
					$ret_religion .= "<option class=\"$class\" style=\"color:#e06400;\" value=\"$myrow_religion[VALUE]\">$reg</option>";
					$ret_religion1 = "<option class=\"$class\" style=\"color:#e06400;\" value=\"$myrow_religion[VALUE]\">$reg</option>";
				}
				else{
					$ret_religion .= "<option class=\"$class\" value=\"$myrow_religion[VALUE]\">$reg</option>";
					$ret_religion1 = "<option class=\"$class\" value=\"$myrow_religion[VALUE]\">$reg</option>";
				}
			}
		}
	}
	return $ret_religion;
}

//function to populate CASTE based on MTONGUE in top search band
function populate_mtongue_caste($top,$sel_val=0)
{
	global $Mtongue,$MTONGUE_REGION_DROP;

	$sql_caste_label="SELECT SQL_CACHE ISALL,LABEL,VALUE,ISGROUP from CASTE";
	$result_caste_label= mysql_query_decide($sql_caste_label);
	while($myrow=mysql_fetch_array($result_caste_label))
	{
		$caste_outside_val=$myrow["VALUE"];
		if($myrow['ISGROUP']=='Y')
			$all_array[]=$caste_outside_val;
		$caste_outside["$caste_outside_val"]["LABEL"]=$myrow["LABEL"];
		$caste_outside["$caste_outside_val"]["ISALL"]=$myrow["ISALL"];
	}

	$sql_caste="SELECT SQL_CACHE HINDU_CASTE AS CASTE,MTONGUE from CASTE_COMMUNITY_TOP_BAND";
	$caste_community_res=mysql_query_decide($sql_caste);
	while($caste_community_row=mysql_fetch_array($caste_community_res))
	{
		$caste_community_mtongue=$caste_community_row["MTONGUE"];
		$caste_community_caste=$caste_community_row["CASTE"];
		$caste_community_arr["$caste_community_mtongue"]="$caste_community_caste";
	}

	$sql="SELECT SQL_CACHE REGION, VALUE, SMALL_LABEL AS LABEL FROM MTONGUE ORDER BY REGION desc,SORTBY_NEW ASC";
	$result=mysql_query_decide($sql);
	while($myrow=mysql_fetch_array($result))
	{
		unset($mtongue_caste);
		unset($caste_label);
		unset($caste_value);
		$strtemp = '';

		//section to set groups based on REGION
		$mtongue_region=$myrow['REGION'];
		if($mtongue_region!=$mtongue_region_old)
		{
			//insert a blank value at the beginning of every REGION group
			$str[] = ''."|X|".$mtongue_region;
			$mtongue_label[]='';
		}
		$mtongue_region_old=$mtongue_region;
		//end of section to set groups based on REGION

		$mtongue_label[]=$myrow['LABEL'];
		
		$strtemp = $myrow['VALUE']."|X|";

		$myrow_mtongue=$myrow[VALUE];
		$myrow_caste['CASTE']=$caste_community_arr["$myrow_mtongue"];
		if($myrow_caste['CASTE'])
			$caste_value=explode(",",$myrow_caste['CASTE']);
		else
			$caste_value = array();

		for($i=0;$i<count($caste_value);$i++)
		{
			$caste_val_in=$caste_value[$i];
			if(in_array($caste_val_in,$all_array))
				$allVar="All ";
			else
				$allVar='';
			if(@ereg(":",$caste_outside["$caste_val_in"]['LABEL']))
				$label=explode(":",$caste_outside["$caste_val_in"]['LABEL']);
			if($caste_outside["$caste_val_in"]['ISALL']=='Y')
				$caste_label[]="$allVar".$caste_outside["$caste_val_in"]['LABEL'];
			else
				$caste_label[]=$allVar.$label[1];
															    	 
		}
		//if no CASTE is there to be shown for a particular COMMUNITY, then show all CASTE
		if(!count($caste_value))
		{
			$sql_caste_label = "select SQL_CACHE ISALL,VALUE, SMALL_LABEL AS LABEL from CASTE order by SORTBY";
			$result_caste_label= mysql_query_decide($sql_caste_label);
			while($myrow_caste_label=mysql_fetch_array($result_caste_label))
			{
				$caste_value[]=$myrow_caste_label['VALUE'];
				if($myrow_caste_label['ISALL']=='Y')
					//$caste_label[]="#".$myrow_caste_label['LABEL'];
					$caste_label[]="".$myrow_caste_label['LABEL'];
				else
					$caste_label[]=$myrow_caste_label['LABEL'];
			}
		}
		if(is_array($caste_label))
		{
			$caste_label=implode(",",$caste_label);
			$caste_value=implode(",",$caste_value);
		}
		$strtemp .= $caste_value."$".$caste_label;//."#";
			
		$str[] = $strtemp;
	}

	for($j=5;$j>0;$j--)
	{
		$caste_value_new = array();
		$caste_label_new = array();
		$mtongue_region_wise = $MTONGUE_REGION_DROP[$j];
		$strtemp_new = $mtongue_region_wise."|X|";

		$myrow_caste['CASTE']=$caste_community_arr["$mtongue_region_wise"];
		if($myrow_caste['CASTE'])
			$caste_value_new=explode(",",$myrow_caste['CASTE']);
		else
			$caste_value_new = array();

		for($i=0;$i<count($caste_value_new);$i++)
		{
			$caste_val_in=$caste_value_new[$i];
			if(@ereg(":",$caste_outside["$caste_val_in"]['LABEL']))
				$label=explode(":",$caste_outside["$caste_val_in"]['LABEL']);

			if(in_array($caste_val_in,$all_array))
				$allVar="All ";
			else
				$allVar='';
				
			if($caste_outside["$caste_val_in"]['ISALL']=='Y')
				$caste_label_new[]="#$allVar".$caste_outside["$caste_val_in"]['LABEL'];
			else
				$caste_label_new[]=$allVar.$label[1];
		}
		//if no CASTE is there to be shown for a particular COMMUNITY, then show all CASTE
		if(!count($caste_value))
		{
			$sql_caste_label = "select SQL_CACHE ISALL,VALUE, SMALL_LABEL AS LABEL from CASTE order by SORTBY";
			$result_caste_label= mysql_query_decide($sql_caste_label);
			while($myrow_caste_label=mysql_fetch_array($result_caste_label))
			{
				$caste_value_new[]=$myrow_caste_label['VALUE'];
				if(in_array($caste_val_in,$all_array))
					$allVar="All ";
				else
					$allVar='';
				if($myrow_caste_label['ISALL']=='Y')
					$caste_label_new[]="#$allVar".$myrow_caste_label['LABEL'];
				else
					$caste_label_new[]=$allVar.$myrow_caste_label['LABEL'];
			}
		}
		if(is_array($caste_label_new))
		{
			$caste_label_new=implode(",",$caste_label_new);
			$caste_value_new=implode(",",$caste_value_new);
		}
		$strtemp_new .= $caste_value_new."$".$caste_label_new;//."#";
															     
		$str_new[$j] = $strtemp_new;
		unset($caste_label_new);
		unset($caste_value_new);
	}

	if(is_array($Mtongue))
	{
		$Mtongue_str_chk=implode(",",$Mtongue);
		$MtongueTemp=explode("','",$Mtongue_str_chk);
		sort($MtongueTemp);
		$Mtongue_str_chk=implode(",",$MtongueTemp);
		unset($MtongueTemp);
	} 

	for($x=0;$x<count($str);$x++)
	{
		$str_temp = explode('|X|',$str[$x]);
		$str_val = $str_temp[0];
		if($str_val)
		{	
			if($x>5)//To not to show the sub content of All Hindi
			{
				if($sel_val == $str_val)
					$newstr .="<option value=\"" . $str[$x] . "\" selected>" . $mtongue_label[$x] . "</option>\n";
				else
					$newstr .="<option value=\"" . $str[$x] . "\">" . $mtongue_label[$x] . "</option>\n";
			}
		}
		else
		{
			if($str_temp[1]!=5){
				$newstr .= "<option value=\"\" disabled></option>\n";
			}				
			if($str_temp[1]==5)
			{
				// All Hindi will come below North Option.
				//if($Mtongue_str_chk=="10,19,33,7,28,13" )
				if($Mtongue_str_chk==$MTONGUE_REGION_DROP[$str_temp[1]])
				{
					$flag_allhindi.="<option value=\"" . $str_new[5] . "\" selected>" . "All Hindi" . "</option>\n";
				}
				else
					$flag_allhindi.="<option value=\"" . $str_new[5] . "\">" . "All Hindi" . "</option>\n";
			}
			else if($str_temp[1]==4)
			{
				if($Mtongue_str_chk==$MTONGUE_REGION_DROP[$str_temp[1]])
					$newstr .="<option value=\"".$str_new[4]."\" selected style=\"font-weight: bold;color:#e06400\">"."All North"."</option>";
				else
					$newstr .="<option value=\"".$str_new[4]."\" style=\"font-weight: bold;color:#e06400\">"."All North"."</option>";
				$newstr.=$flag_allhindi;
			}
			else if($str_temp[1]==3)
			{
				if($Mtongue_str_chk==$MTONGUE_REGION_DROP[$str_temp[1]])
					 $newstr .="<option value=\"".$str_new[3]."\" selected style=\"font-weight: bold;color:#e06400\">"."All West"."</option>";
				else
					$newstr .="<option value=\"".$str_new[3]."\" style=\"font-weight: bold;color:#e06400\">"."All West"."</option>";
			}
			else if($str_temp[1]==2)
			{
			if($Mtongue_str_chk==$MTONGUE_REGION_DROP[$str_temp[1]])
				$newstr .="<option value=\"".$str_new[2]."\" selected style=\"font-weight: bold;color:#e06400\">"."All South"."</option>";
			else
				$newstr .="<option value=\"".$str_new[2]."\" style=\"font-weight: bold;color:#e06400\">"."All South"."</option>";
			}
			else if($str_temp[1]==1)
			{
				if($Mtongue_str_chk==$MTONGUE_REGION_DROP[$str_temp[1]])
					$newstr .="<option value=\"".$str_new[1]."\" selected style=\"font-weight: bold;color:#e06400\">"."All East"."</option>";
				else
					$newstr .="<option value=\"".$str_new[1]."\" style=\"font-weight: bold;color:#e06400\">"."All East"."</option>";
			}
			else if($str_temp[1]==0)
			{
				$newstr.="<option value=\"\" disabled style=\"font-weight: bold;color:#000000\">"."--------------"."</option>";
			}
		}
	}
	return $newstr;
}

function fillAgeArray($gender="",$lage="",$hage="")
{
	global $smarty,$data;
	if($gender=='M')
		$startAge=21;
	elseif($data["GENDER"]=='F' && !$gender)
		$startAge=21;
	else
		$startAge=18;
	$i=0;

	for($k=$startAge;$k<71;$k++)
	{
		if($lage && $hage)
		{
			if($k==$lage)
				$flagLage=$k;
			if($k==$hage)
				$flagHage=$k;
		}
		$ageArray[$k]=$i;
		createXmlTag("registrationPage1","populate","ageshow","$k","value","$k");
		$i++;
	}

	if(!$flagLage)
		$flagLage=$startAge;
	if(!$flagHage)
		$flagHage=70;
	createXmlTag("registrationPage1","checkParams","selectedlage",utf8_encode($flagLage));
	createXmlTag("registrationPage1","checkParams","selectedhage",utf8_encode($flagHage));
}                                                                                                                             

function casteInTopSearchBand()
{
	//REVAMP JS_DB_CASTE
	$sql_religion_top = "select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL from CASTE where ISALL='Y' order by TOP_SORTBY";
	//REVAMP JS_DB_CASTE
	$result_religion_top= mysql_query_decide($sql_religion_top);
	while($myrow_religion_top=mysql_fetch_array($result_religion_top))
	{
		$caste_label.="#".$myrow_religion_top['LABEL'].",";
		$caste_value.=$myrow_religion_top['VALUE'].",";
	}

	$sql_caste_label = "select SQL_CACHE a.LABEL , a.VALUE, a.ISALL from CASTE as a,CASTE as b where a.PARENT=b.PARENT and b.ISALL='Y' order by b.TOP_SORTBY,a.SORTBY";
	$result_caste_label= mysql_query_decide($sql_caste_label);
	while($myrow_caste_label=mysql_fetch_array($result_caste_label))
	{
		if($myrow_caste_label['ISALL']=='Y')
		{
			$caste_label.="#".$myrow_caste_label['LABEL'].",";
		}
		else
		{
			if(@ereg(":",$myrow_caste_label['LABEL']))
			{
				$label=explode(":",$myrow_caste_label['LABEL']);
				$caste_label.=$label[1].",";
			}
			else
				$caste_label.=$myrow_caste_label['LABEL'].",";
		}
		$caste_value.=$myrow_caste_label['VALUE'].",";
	}
	$caste_label=substr($caste_label,0,-1);
	$caste_value=substr($caste_value,0,-1);
	$all_caste_str = $caste_value."$".$caste_label;
	return $all_caste_str;
}

function topSearchStype($STYPE='',$E_CLASS_SEARCH='',$E_CLASS='',$searchonline='')
{
	if($STYPE=='K')         
		return 'K';
	elseif($STYPE=='A')
		return 'A';
	elseif($STYPE=='P')
		return 'P';
	elseif($E_CLASS_SEARCH ==1 || $E_CLASS =='D')
		return 'E';
	elseif($searchonline ==1 && $STYPE !='A')
		return 'C';
	else
		return 'Q';
}

?>
