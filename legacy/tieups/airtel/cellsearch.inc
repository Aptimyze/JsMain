<?php
/*************************************************************************************************************************
* FILE NAME     : cellsearch.inc
* DESCRIPTION   : Searches for profiles and returns their details.
* CREATION DATE : 8 December, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
**************************************************************************************************************************/
	require_once("../../profile/display_result.inc");
	include_once("../../profile/flag.php");
	
	function displayresult($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="",$ordering="")
	{
		global $PAGELEN,$smarty,$checksum,$data;
		
		if ($data && $data["GENDER"]=="F")
			$smarty->assign("FEMALE_SEARCH","1");
		
		include("../../profile/dropdowns.php");
		
		$income_map=array("1" => "< Rs. 50K",
							"2" => "Rs. 50K - 1Lac",
							"3" => "Rs. 1 - 2Lac",
							"4" => "Rs. 2 - 3Lac",
							"5" => "Rs. 3 - 4Lac", 
							"6" => "Rs. 4 - 5Lac",
							"7" => "> Rs. 5Lac",
							"8" => "< $ 25K",
							"9" => "$ 25 - 50K",
							"10" => "$ 50 - 75K",
							"11" => "$ 75K - 1Lac",
							"12" => "$ 1 - 1.5Lac",
							"13" => "$ 1.5 - 2Lac",
							"14" => "> $ 2Lac",
							"15" => "No Income",
							"16" => "Rs. 5 - 7.5Lac",
							"17" => "Rs. 7.5 - 10Lac",
							"18" => "> Rs. 10Lac");
							
		$FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,SPOUSE,MSTATUS,BTYPE,COMPLEXION,RELIGION,MTONGUE,ENTRY_DT,LAST_LOGIN_DT,RELATION,GENDER,SUBSCRIPTION";

		if(gettype($result)=="resource")
		{
			while($myrow=mysql_fetch_row($result))
			{
				$str.="'" . $myrow[0] . "',";
			}
		}
		else if(gettype($result)=="string")
		{
			$str=$result;
		}
		else
		{
			die("parameter 1 passed to function displayresult is invalid.");
		}
		
		$str=substr($str,0,strlen($str)-1);
		
		$sql="select";
		
		if($nocalc=="")
			$sql.=" SQL_CALC_FOUND_ROWS";
			
		$sql.=" $FIELDS from JPROFILE where PROFILEID in ($str)";
		
		if($putactivate=="1")
			$sql.=" and ACTIVATED='Y'";

		if($ordering=="F")
			$sql.=" order by ENTRY_DT desc";
		elseif($ordering=="M")
			$sql.=" order by MOD_DT desc";
		elseif($ordering=="L")
			$sql.=" order by LAST_LOGIN_DT desc";
		else 
			$sql.=" order by ENTRY_DT desc";
		if($nocalc=="")
			$sql.=" limit $curcount,$PAGELEN";

		$result1=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		
		if($nocalc=="")
		{
			$sql="select FOUND_ROWS() as cnt";
			$resultcount=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			
			$countrow=mysql_fetch_row($resultcount);
			$totalrec=$countrow[0];
		}
		
		$sno=1;
		
		while($myrow=mysql_fetch_array($result1))
		{
			$resultprofiles.="'" . $myrow["PROFILEID"] . "',";
		}
		
		$resultprofiles=substr($resultprofiles,0,strlen($resultprofiles)-1);
		
		// move the pointer of the recordset back to record 1
		mysql_data_seek($result1,0);
			
		if($data["PROFILEID"]!="" && mysql_num_rows($result1)>0)
		{
			$imagesql="select RECEIVER,TYPE from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and RECEIVER in ($resultprofiles)";
			$imageresult=mysql_query_decide($imagesql)  or queryDieLog(mysql_error_js(),$imagesql);
			
			if(mysql_num_rows($imageresult)>0)
			{
				while($imagerow=mysql_fetch_array($imageresult))
				{
					$contacted1[$imagerow["RECEIVER"]]=$imagerow["TYPE"];
					$contacted2[$imagerow["RECEIVER"]]="R";
				}
			}
			
			mysql_free_result($imageresult);
			
			$imagesql="select SENDER,TYPE from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and SENDER in ($resultprofiles)";
			$imageresult=mysql_query_decide($imagesql) or queryDieLog(mysql_error_js(),$imagesql);
			
			if(mysql_num_rows($imageresult)>0)
			{
				while($imagerow=mysql_fetch_array($imageresult))
				{
					$contacted1[$imagerow["SENDER"]]=$imagerow["TYPE"];
					$contacted2[$imagerow["SENDER"]]="S";
				}
			}
			
			mysql_free_result($imageresult);
			
			$bookmarksql="select BOOKMARKEE from BOOKMARKS where BOOKMARKER='" . $data["PROFILEID"] . "' and BOOKMARKEE in ($resultprofiles)";
			$bookresult=mysql_query_decide($bookmarksql) or queryDieLog(mysql_error_js(),$bookmarksql);
			
			if(mysql_num_rows($bookresult) > 0)
			{
				while($mybooks=mysql_fetch_array($bookresult))
				{
					$bookmarks[]=$mybooks["BOOKMARKEE"];
				}
			}
			
			mysql_free_result($bookresult);
		}
		
		if($resultprofiles)
		{
			$onlinesql="select userID from userplane.users where userID in ($resultprofiles)";
			$onlineresult=mysql_query_decide($onlinesql) or queryDieLog(mysql_error_js(),$bookmarksql);
			
			if(mysql_num_rows($onlineresult) > 0)
			{
				while($myonline=mysql_fetch_array($onlineresult))
				{
					$onlinemembers[]=$myonline["userID"];
				}
			}
			
			mysql_free_result($onlineresult);
		}
		
		while($myrow=mysql_fetch_array($result1))
		{
			$income=$myrow["INCOME"];
			$occ=$myrow["OCCUPATION"];
			$occupation=$OCCUPATION_DROP["$occ"];
			
			$caste1=$myrow["CASTE"];
			$caste=$CASTE_DROP["$caste1"];

			$edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
			$edu_level=$edu_leveln[0];

			//$mtongue1=label_select("MTONGUE",$myrow['MTONGUE']);
			$sql_mtongue="SELECT SMALL_LABEL FROM newjs.MTONGUE WHERE VALUE='$myrow[MTONGUE]'";
			if($res_mtongue=mysql_query_decide($sql_mtongue))
			{
				$row_mtongue=mysql_fetch_array($res_mtongue);
				$MTONGUE=$row_mtongue['SMALL_LABEL'];
			}

			$screening=$myrow["SCREENING"];
			if(isFlagSet("YOURINFO",$screening))
				$yourinfo=$myrow["YOURINFO"];
			else 
				$yourinfo="";
				
			if(isFlagSet("FATHER_INFO",$screening))
				$fatherinfo=$myrow["FATHER_INFO"];
			else 
				$fatherinfo="";
				
			if(isFlagSet("SIBLING_INFO",$screening))
				$siblinginfo=$myrow["SIBLING_INFO"];
			else 
				$siblinginfo="";
				
			if(isFlagSet("SPOUSE",$screening))
				$spouseinfo=$myrow["SPOUSE"];
			else 
				$spouseinfo="";

			if($spouseinfo!="")
				$spouseinfo="Looking for: " . $spouseinfo;
			
			$heightn=$myrow["HEIGHT"];
			$height=$HEIGHT_DROP["$heightn"];
			$height1=explode("(",$height);
			$height2=trim($height1[0]);
			
			$mod_date=substr($myrow["MOD_DT"],0,10);
			if($mod_date!="0000-00-00" && $mod_date!="")
			{
				$mod_date1=explode("-",$mod_date);
				$mod_date=$mod_date1[2] . " " . getMonthName($mod_date1[1]) . " " . substr($mod_date1[0],2,2);
			}
			else 
				$mod_date="";

			$entry_date=substr($myrow["ENTRY_DT"],0,10);
                        if($entry_date!="0000-00-00" && $entry_date!="")
                        {
                                $entry_date1=explode("-",$entry_date);
                                $entry_date=$entry_date1[2] . " " . getMonthName($entry_date1[1]) . " " . substr($entry_date1[0],2,2);
                        }
                        else
                                $entry_date="";

			$ll_date=substr($myrow["LAST_LOGIN_DT"],0,10);
                        if($ll_date!="0000-00-00" && $ll_date!="")
                        {
                                $ll_date1=explode("-",$ll_date);
                                $ll_date=$ll_date1[2] . " " . getMonthName($ll_date1[1]) . " " . substr($ll_date1[0],2,2);
                        }
                        else
                                $ll_date="";
                                                                                                                            
	
			if($myrow["CITY_RES"]!="")
			{
				$city_res1=$myrow["CITY_RES"];
				
				if($myrow["COUNTRY_RES"]=="51")
					$residence=$CITY_INDIA_DROP["$city_res1"];
				else 
					$residence=$CITY_USA_DROP["$city_res1"];
			}
			else 
			{
				$country1=$myrow["COUNTRY_RES"];
				$residence=$COUNTRY_DROP["$country1"];
			}
			
			$newCaste=explode(":",$caste);
			if(trim($newCaste[1])!="")
				$myCaste=$newCaste[1];
			else 
				$myCaste=$newCaste[0];
			
			$subscription=explode(",",$myrow["SUBSCRIPTION"]);
			
			if(in_array("B",$subscription))
				$bold_listing=1;
			else 
				$bold_listing=0;
				
			//done for new service added called Eclassified  NEW CHANGES
			if(in_array("D",$subscription) && !in_array("S",$subscription))
				$contact_details=1;
			else 
				$contact_details=0;
				
			if($myrow["HAVEPHOTO"]=="Y")
				$havephoto="Y";
			else 
				$havephoto="N";
				
			if(is_array($bookmarks) && in_array($myrow["PROFILEID"],$bookmarks))
				$bookmarked=1;
			else 
				$bookmarked=0;
				
			if(is_array($onlinemembers) && in_array($myrow["PROFILEID"],$onlinemembers))
				$online=1;
			else 
				$online=0;
				
			if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
			{
				if(!$data)
					$havephoto="P";
				elseif($data && $myrow["PRIVACY"]=="F")
				{
					if(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
						$havephoto="P";
				}
			}
					
			if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
			{
				if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
					$havephoto="P";
				elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
				{
					if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A")) || ($contacted2[$myrow["PROFILEID"]]=="R" && $contacted1[$myrow["PROFILEID"]]=="A")))
						;
					else 
					{
						$havephoto="P";
					}
				}
				elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
				{
					if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A")) || ($contacted2[$myrow["PROFILEID"]]=="R" && $contacted1[$myrow["PROFILEID"]]=="A")))
						;
					elseif(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
						$havephoto="P";
				}
			}

/*********************************************************************************************************************
Changed By	: Shakti Srivastava
Change Date	: 6 September 2005
Reason		: Allow people to view the profile withput logging in
**********************************************************************************************************************/	
			$temp='Y'.$myrow["PROFILEID"];
			$profileurlchecksum=md5($temp)."i".$myrow["PROFILEID"];

/*********************************************************************************************************************
Changed By	: Shakti Srivastava
Change Date	: 27 July 2005
Reason		: "YOURINFO" has to be broken into two parts so that the initial 3 words can be shown in Bold in
		  the template
**********************************************************************************************************************/	
                        
			$yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,500);

			$religion=label_select("RELIGION",$myrow["RELIGION"]);

			$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
			$RESULT_ARRAY[]=array("SNO" => $sno,
						"PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
						"PROFILEID" => $myrow["PROFILEID"],
						"PHOTOCHECKSUM" => $photochecksum,
						"USERNAME" => $myrow["USERNAME"],
						"AGE" => $myrow["AGE"],
						"HEIGHT" => $height2,
						"CASTE" => $myCaste,
						"OCCUPATION" => $occupation,
						"RESIDENCE" => $residence,
						"YOURINFO" => $yourinfo,
						"MOD_DT" => $mod_date, 			/*modification date*/
						"LL_DT" => $ll_date,			/*last login date*/
						"ENTRY_DT" => $entry_date,
						"HAVEPHOTO" => $havephoto,
						"CONTACTSTATUS" => $contacted1[$myrow["PROFILEID"]], /*{A,I,D,C}*/
						"BOOKMARKED" => $bookmarked,
						"BOLDLISTING" => $bold_listing,
						//done for new service added called eclassified NEW CHANGES
						"CONTACT_DETAILS" => $contact_details,	/*the subscription field*/
						"MSTATUS" => $myrow["MSTATUS"],
						"BODY" => $myrow["BTYPE"],
						"RELIGION" => $religion[0],
						//"MTONGUE" => $myrow["MTONGUE"],
						"MTONGUE" => $MTONGUE,
						"COMPLEXION" => $myrow["COMPLEXION"],
						"RELATION" => $myrow["RELATION"],
						"SEX" => $myrow["GENDER"],
						"ONLINE" => $online,
						"SUBSCRIPTION" => $myrow["SUBSCRIPTION"],
						"DEGREE" => $edu_level,
						"PROTITLE" => $protitle,		/*the initial details in bold*/
						"PROFILEURLCHECKSUM" => $profileurlchecksum,
						"INCOME" => $income_map["$income"]);
									
			$sno++;
		}
		
		mysql_free_result($result1);
		return $RESULT_ARRAY;
	}


if (!function_exists('getMonthName'))
{	
	function getMonthName($month)
	{
		switch ($month)
		{
			case "01":
			case "1":
				return "Jan";
			case "02":
			case "2":
				return "Feb";
			case "03":
			case "3":
				return "Mar";
			case "04":
			case "4":
				return "Apr";
			case "05":
			case "5":
				return "May";
			case "06":
			case "6":
				return "Jun";
			case "07":
			case "7":
				return "Jul";
			case "08":
			case "8":
				return "Aug";
			case "09":
			case "9":
				return "Sep";
			case "10":
				return "Oct";
			case "11":
				return "Nov";
			case "12":
				return "Dec";
		}
	}
}	
	// gender of the person who is being searched is passed
	function searchBar($gender="F",$religion="",$mtongue="",$lage="",$hage="",$withphoto="",$caste="")
	{	
		global $smarty;
		
		if($lage=="" && $hage=="")
		{
			if($gender=="F")
			{
				$lage="20";
				$hage="25";
			}
			else 
			{
				$lage="23";
				$hage="27";
			}
		}
		
		if(is_array($mtongue))
			$smarty->assign("SEARCHMTONGUE",create_dd($mtongue[0],"Mtongue"));	
		else 
			$smarty->assign("SEARCHMTONGUE",create_dd($mtongue,"Mtongue"));
			
		if(is_array($caste))
			$smarty->assign("SEARCHCASTE",create_dd($caste[0],"Caste"));
		else 
			$smarty->assign("SEARCHCASTE",create_dd($caste,"Caste"));
		
		$smarty->assign("SEARCHLAGE",$lage);
		$smarty->assign("SEARCHHAGE",$hage);
		$smarty->assign("SEARCHGENDER",$gender);
		$smarty->assign("SEARCHWITHPHOTO",$withphoto);
	}
	
	function check_privacy_filtered($myprofileid,$hisprofileid)
	{
		$sql="select * from FILTERS where PROFILEID='$hisprofileid'";
		$resultfilter=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		
		if(mysql_num_rows($resultfilter)>0)
		{
			$filterrow=mysql_fetch_array($resultfilter);							
			if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y")
			{
				$sqlfilter="select count(*) from JPROFILE where PROFILEID='$myprofileid'";					
				$sql="select * from JPARTNER where PROFILEID='$hisprofileid'";
				$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
				//Get the filter values
				if(mysql_num_rows($result) > 0)
				{	
					$myrow=mysql_fetch_array($result);		
					$partnerid=$myrow["PARTNERID"];
					//first filter : age
					if($filterrow["AGE"]=="Y")
					{							
						if($myrow["LAGE"]!="" && $myrow["HAGE"]!="")
						{
							$sqlfilter.= " and AGE between '" . $myrow[LAGE] . "' and '" . $myrow[HAGE] ."'";																				
						}
					}
					//second filter : religion
					if($filterrow["RELIGION"]=="Y")
					{
						$sql="select * from PARTNER_CASTE where PARTNERID='$partnerid'";
						$resultpartner=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);						  	
						while($mypartner=mysql_fetch_row($resultpartner))
						{
							$PARTNER_CASTE[]=$mypartner[1];
						}
						
						if(is_array($PARTNER_CASTE))
						{
							$sqlfilter.=" and CASTE in ('" . implode("','",get_all_caste($PARTNER_CASTE)) . "')";
						}									
					}		
					//third filter : country of residence
					if($filterrow["COUNTRY_RES"]=="Y")
					{
						$sql="select * from PARTNER_COUNTRYRES where PARTNERID='$partnerid'";
						$resultpartner=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);						  	
						while($mypartner=mysql_fetch_row($resultpartner))
						{
							$PARTNER_COUNTRYRES[]=$mypartner[1];
						}						
						if(is_array($PARTNER_COUNTRYRES))
						{
							$sqlfilter.=" and COUNTRY_RES in ('" . implode("','",$PARTNER_COUNTRYRES) . "')";
						}	
					}	
					//fourth filter : marital status
					if($filterrow["MSTATUS"]=="Y")
					{
						$sql="select * from PARTNER_MSTATUS where PARTNERID='$partnerid'";
						$resultpartner=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);						  	
						while($mypartner=mysql_fetch_row($resultpartner))
						{
							$FILTER_MSTATUS[]=$mypartner[1];
						}	
						if(is_array($FILTER_MSTATUS))
						{
					 		$sqlfilter.=" and MSTATUS in ('" . implode("','",$FILTER_MSTATUS) . "')"; 
						}	
					}
					$resfil=mysql_query_decide($sqlfilter) or queryDieLog(mysql_error_js(),$sql);
		
					$finalfilterrow=mysql_fetch_row($resfil);				
					mysql_free_result($resfil);
					if($finalfilterrow[0] <= 0)
					{						
						return true;
					}
				}
			}
		}
		 
		return false;
	}
	
	// function to create an array of links for hierarchical search
	function create_link_array($current_rank,$rank_id,$max_rank,$searchchecksum)
	{

		$sql_value = "Select * from PHOTOBROWSE_VALUES where ID = $rank_id";
		$link_values = mysql_query_decide($sql_value) or  queryDieLog(mysql_error_js(),$sql_value);

		$myrow_values = mysql_fetch_array($link_values);
		
		$sql_link = "Select * from newjs.PHOTOBROWSE_RANKS where RANK <= $max_rank  order by RANK";		
		$link_result = mysql_query_decide($sql_link) or  queryDieLog(mysql_error_js(),$sql_link);

		
		$link_str[0] = "searchchecksum=$searchchecksum&flag_back=1&";
		$i = 1 ;
		
		while($myrow_link = mysql_fetch_array($link_result))
		{
			if($myrow_link["RANK"] <= $current_rank)
			{
				if($myrow_link["FIELD"] == "CASTE")
					$link_str[$i] = "Caste[]=".$myrow_values["CASTE"];
				elseif($myrow_link["FIELD"] == "MTONGUE")
					$link_str[$i] = "Mtongue[]=".$myrow_values["MTONGUE"];
				elseif($myrow_link["FIELD"] == "OCCUPATION")
                                        $link_str[$i] = "Occupation[]=".$myrow_values["OCCUPATION"];
				elseif($myrow_link["FIELD"] == "COUNTRY")	
					$link_str[$i] = "Country_Res[]=".$myrow_values["COUNTRY"];
				elseif($myrow_link["FIELD"] == "AGE")
					$link_str[$i] = "lage=".$myrow_values["LAGE"]."&hage=".$myrow_values["HAGE"];
				elseif ($myrow_link["FIELD"] == "INCOME")
					$link_str[$i] = "Income[]=".$myrow_values["INCOME"];				
				$link_str[$i].="&".$link_str[$i-1];				
				$link_enable = "Y";
			}
			else
				$link_enable = "N";				
				
			$values[] = array("FIELD"=>$myrow_link["FIELD"],
						"RANK"=>$myrow_link["RANK"],
						"LINK"=>$link_str[$i-1],
						"ENABLE"=>$link_enable);

			$i++;	
		}
		return $values;
	}

function noResultLog($qs)
{
	$sql_log="INSERT INTO newjs.SMS_QUERYLOG VALUES('',now(),'".$qs."')";
	$res_log=mysql_query_decide($sql_log) or die("There has been some problem due to which request cannot be processed.");
}

function queryDieLog($sqlerr,$sqlquery)
{
//	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/tieups/airtel/logerror.txt";
//	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /var/www/html/tieups/airtel/logerror.txt";
	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /var/www/html/tieups/airtel/logerror.txt";

	passthru($errormsg);

	die("The request cannot be processed");
}

function update_sms_block($cellUserProfileid,$triedUserProfileid)
{
	$sql_update="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cellUserProfileid."'";
	$res_update=mysql_query_decide($sql_update) or queryDieLog(mysql_error_js(),$sql_update);
	$row_update=mysql_fetch_array($res_update);

	if($row_update['BLOCK_STATUS']=='BLOCKALL')
	{
		$sql_update="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKALLEXCEPT','".$triedUserProfileid."')";
		$res_update=mysql_query_decide($sql_update) or queryDieLog(mysql_error_js(),$sql_update);
	}
	else if($row_update['BLOCK_STATUS']=='BLOCKALLEXCEPT')
	{
		$blockedUsersArr=explode(",",$row_update['BLOCKED_USERS']);

		if(!in_array($triedUserProfileid,$blockedUsersArr))
		{
			$BLOCKED_USERS=$row_update['BLOCKED_USERS'].",".$triedUserProfileid;

			$sql_replace="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKALLEXCEPT','".$BLOCKED_USERS."')";
			$res_replace=mysql_query_decide($sql_replace) or queryDieLog(mysql_error_js(),$sql_replace);
		}
	}
	else if($row_update['BLOCK_STATUS']=='BLOCKONLY')
	{
		$blockedUsersArr=explode(",",$row_update['BLOCKED_USERS']);

		if(in_array($triedUserProfileid,$blockedUsersArr))
		{
			$newBlockedUsers="";

			for($tmpcnt=0;$tmpcnt<count($blockedUsersArr);$tmpcnt++)
			{
				if($blockedUsersArr[$tmpcnt]!=$triedUserProfileid)
					$newBlockedUsers=$newBlockedUsers.",".$blockedUsersArr[$tmpcnt];
			}

			$sql_replace="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKONLY','".$newBlockedUsers."')";
			mysql_query_decide($sql_replace) or  queryDieLog(mysql_error_js(),$sql_replace);
		}
	}
}

function update_contacts($cellUserProfileid,$triedUserProfileid)
{
	$sql="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='".$cellUserProfileid."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);

	$sender_details=array("GENDER"=>$row['GENDER']);

	$contact_status=get_contact_status($cellUserProfileid,$triedUserProfileid);
														     
	if($contact_status=="")
	{
		$tempvar="";
		if(can_contact($cellUserProfileid,$triedUserProfileid,$sender_details,$tempvar))
		{
				make_initial_contact($cellUserProfileid,$triedUserProfileid,"","","","","","");
		}
	}
	else if($contact_status=="I")
	{
		$tempvar="";
		if(can_contact($cellUserProfileid,$triedUserProfileid,$sender_details,$tempvar))
		{
			make_initial_contact($cellUserProfileid,$triedUserProfileid,"","",1,"","","");
		}
	}
	else if($contact_status=="RI")
	{
		send_response($cellUserProfileid,$triedUserProfileid,"A","","","");
	}
}

/* Function that will be utilised while sorting the array on the basis of paid members*/
function paidMemberSort($a,$b)
{
	if(strstr($a['SUBSCRIPTION'],"F") || strstr($a['SUBSCRIPTION'],"V"))
		$aIsPaid='Y';

	if(strstr($b['SUBSCRIPTION'],"F") || strstr($b['SUBSCRIPTION'],"V"))
		$bIsPaid='Y';


	if($aIsPaid=='Y' && $bIsPaid=='Y')
	{
		return 0;
	}
	else if($aIsPaid=='Y' && $bIsPaid!='Y')
	{
		return -1;
	}
	else if($aIsPaid!='Y' && $bIsPaid=='Y')
	{
		return 1;
	}
	else if($aIsPaid!='' && $bIsPaid=='')
	{
		return 0;
	}
}

function noSimilarProfiles($profileid)
{
	$sql="SELECT AGE,GENDER,CASTE,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='".$profileid."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);

	$age=$row['AGE'];
	$caste=$row['CASTE'];
	$gender=$row['GENDER'];
	$mtongue=$row['MTONGUE'];

	$lage=$age-2;
	$hage=$age+2;

	if(!is_array($caste) && $caste!="" && $caste!="All")
	{
		$tempcaste=$caste;
		unset($caste);
		$caste[0]=$tempcaste;
	}
                                                                                                                             
	if(is_array($caste) && !in_array("All",$caste))
	{
		$seCaste=get_all_caste($caste);
		if(is_array($seCaste))
			$searchCaste="'" . implode($seCaste,"','") . "'";
	}

	$sql="SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM ";
                                                                                                                             
	if($gender=='M')
		$sql.=" newjs.SEARCH_MALE ";
	else if($gender=='F')
		$sql.=" newjs.SEARCH_FEMALE ";
                                                                                                                             
	$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue' ORDER BY E_RISHTA,SORT_DT DESC LIMIT 24";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

	return $res;
}

function generateXML($results,$longdesc_limit,$shortdesc_limit,$cellUserIsRegistered="",$cellUserIsPaid="",$lista="")
{
	$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	$Ret.="<ProfileList>\n";
                                                                                                                             
	if($cellUserIsRegistered=="Y")
		$Ret.="<registered>true</registered>\n";
	else
		$Ret.="<registered>false</registered>\n";
												     
	if($cellUserIsPaid=="Y")
		$Ret.="<paid>true</paid>\n";
	else
		$Ret.="<paid>false</paid>\n";

	echo $Ret;
	unset($Ret);
												     
	$arr_search=array('&','<','>',"'",'"');
	$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
												     
	for($a=0;$a<count($results);$a++)
	{
		unset($shortdesc);
		unset($longdesc);
												     
		if($results[$a]['USERNAME'])
			$shortdesc=$results[$a]['USERNAME'];
												     
		$shortdesc=str_replace($arr_search,$arr_repl,$shortdesc);
		$shortdesc=substr($shortdesc,0,$shortdesc_limit);
//		$shortdesc=trimToNextSpace($shortdesc,0,$shortdesc_limit);
												     
		if($shortdesc)
		{
			$longdesc="";
												     
			if($results[$a]['USERNAME'])
				$longdesc=str_replace($arr_search,$arr_repl,$results[$a]['USERNAME']);
												     
			if($results[$a]['AGE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['AGE']);
												     
			if($results[$a]['HEIGHT'])
				$longdesc.=", ".$results[$a]['HEIGHT'];
												     
			if($results[$a]['CASTE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']);
												     
			if($results[$a]['MTONGUE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['MTONGUE']);

			if($results[$a]['DEGREE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE']);
												     
			if($results[$a]['OCCUPATION'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']);
												     
			if($results[$a]['RESIDENCE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE']);
												     
			if($cellGender=="F" && $results[$a]['INCOME'])
			{
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['INCOME']);
			}
			else if($cellGender=="M")
			{
				if($results[$a]['BODY'])
					$longdesc.=", ".str_replace($arr_search,$arr_repl,$BODYTYPE[$results[$a]['BODY']]);

				if($results[$a]['COMPLEXION'])
					$longdesc.=", ".str_replace($arr_search,$arr_repl,$COMPLEXION[$results[$a]['COMPLEXION']]);
			}
		}
//		$longdesc=str_replace($arr_search,$arr_repl,$longdesc);
//		$longdesc=substr($longdesc,0,$longdesc_limit);
		$longdesc=trimToNextSpace($longdesc,0,$longdesc_limit);
		$longdesc=trim($longdesc,",");
												     
		$Ret="\t<Profile>\n";
		$Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";
                $Ret.="\t\t<username>".$results[$a]['USERNAME']."</username>\n";
												     
		if($shortdesc)
			$Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";
												     
		if($longdesc)
			$Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";
                                                                                                                             
		if(strstr($results[$a]['SUBSCRIPTION'],"F") || strstr($results[$a]['SUBSCRIPTION'],"V"))
			$Ret.="\t\t<paid>true</paid>\n";
		else
			$Ret.="\t\t<paid>false</paid>\n";
                                                                                                                             
		$Ret.="\t</Profile>\n";
		echo $Ret;
		unset($Ret);
	}
												     
	$Ret="</ProfileList>\n";

	return $Ret;
}

/**********************************************************************************************************************
*  FUNCTION NAME:  contactLimitExceeded()
*  DESCRIPTION  :  Checks if the contact limit thru SMS for a day has been exceeded for a particular user.
*  RETURNS      :  False if it has not exceeded. True if it has exceeded.
**********************************************************************************************************************/
function contactLimitExceeded($profileid)
{
	$today=Date("Y-m-d");

	$sql="SELECT COUNT FROM newjs.SMS_CONTACTLIMIT WHERE PROFILEID='".$profileid."' AND ENTRY_DT='".$today."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	
	if($row['COUNT']<=2)
		return false;
	else
		return true;
}

/**********************************************************************************************************************
*  FUNCTION NAME:  incrementContactLimit()
*  DESCRIPTION  :  Increments the count in the newjs.SMS_CONTACTLIMIT table whenever a contact is made. This helps in 
*		:  keeping a tab on the number of times a person has been contacted in a day thru SMS
*  RETURNS      :  Nothing
**********************************************************************************************************************/
function incrementContactLimit($profileid)
{
	$sql="SELECT ID FROM newjs.SMS_CONTACTLIMIT WHERE PROFILEID='".$profileid."' AND ENTRY_DT=CURDATE()";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);

	if(mysql_num_rows($res)>0)
	{
		$sqlUpdt="UPDATE newjs.SMS_CONTACTLIMIT SET COUNT=COUNT+1 WHERE ID='".$row['ID']."'";
		$resUpdt=mysql_query_decide($sqlUpdt) or queryDieLog(mysql_error_js(),$sqlUpdt);
	}
	else
	{
		$sqlUpdt="INSERT INTO newjs.SMS_CONTACTLIMIT (ID,PROFILEID,COUNT,ENTRY_DT) VALUES ('','".$profileid."','1',NOW())";
		$resUpdt=mysql_query_decide($sqlUpdt) or queryDieLog(mysql_error_js(),$sqlUpdt);
	}
}

/**********************************************************************************************************************
*  FUNCTION NAME:  trimToNextSpace()
*  DESCRIPTION  :  Trims a string to the next space after the character limit specified.
*  PARAMETER	:  $str - the string that has to be trimmed.
		:  $start - the index of the character from which trimming is to be started.
		:  $charLimit - the character limit upto which the string has to be trimmed
*  RETURNS      :  False if it has not exceeded. True if it has exceeded.
**********************************************************************************************************************/
function trimToNextSpace($str,$start,$charLimit)
{
	if($charLimit>=strlen($str))
	{
		return $str;
	}
	else if($str{$charLimit}==" ")
	{
		return substr($str,$start,$charLimit);
	}
	else
	{
		for($sstmpcnt=$charLimit;$sstmpcnt>=0;$sstmpcnt--)
		{
			if($str{$sstmpcnt}==" " || $str{$sstmpcnt}==",")
			{
				$spacePos=$sstmpcnt;
				break;
			}
		}

		if($spacePos==0)
		{
			//$spacePos=strlen($str);
			return substr($str,$start,$charLimit);
		}
		else
		{
/*
		$spacePos=strpos(substr($str,$charLimit,strlen($str))," ");

		if($spacePos==0)
			$spacePos=strlen($str);
		else
			$spacePos=$charLimit+$spacePos;

*/
		return substr($str,$start,$spacePos);
		}
	}
}
function logCheckStatus($MSISDN)
{
	$sql="INSERT INTO newjs.SMS_CHECKSTATUS_LOG VALUES('','".$MSISDN."',now())";
	mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
}
function logContacts($sender,$receiver,$type)
{
	$sql="INSERT INTO newjs.SMS_CONTACT_LOG VALUES('','".$sender."','".$receiver."','".$type."',now())";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
}
?>
