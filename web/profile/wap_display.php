<?php

function wap_partnersearch($profileid,$next,$previous,$upper,$search_keyword)
{
	global $wap_temp_sql,$search_keyword,$no_limit,$wap,$NoModify;
	$sql=$wap_temp_sql;
	
	$wap='yes';

	if($profileid)
	{
		$sql_1="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_1=mysql_query_decide($sql_1) or queryDieLog(mysql_error_js(),$sql_1);
		if(mysql_num_rows($res_1)==1)
		{
			$row_1=mysql_fetch_array($res_1);
			$cellUserIsRegistered="Y";
			if(strstr($row_1['SUBSCRIPTION'],"F"))
				$cellUserIsPaid="Y";
		}
	}
	if($nolimit)
	{
		$wap_sql=$sql;
	}
	else
	{
		$test_sql=explode("limit",$sql);
		$wap_sql=$test_sql[0];
	}

	//added by lavesh
	$test_sql=explode("FROM",$wap_sql);
	if($test_sql[2]=='')
	{
		$test_sql="SELECT COUNT(*) as CNT FROM ".$test_sql[1];
		$result = mysql_query_decide($test_sql);
		$row=mysql_fetch_array($result);
		$total_rec=$row["CNT"];
	}
	else
	{
		$test_sql=$wap_sql;
		$result = mysql_query_decide($test_sql);
		$total_rec = mysql_num_rows($result);
	}

	if($next==1 || $previous==1)
	{
		if($next)
			$lower=$upper+1;
		else
			$lower=$upper-23;
	}
	else
		$lower=1;
	$upper=$lower+23;

	if($total_rec)
	{
		if($upper>$total_rec)
			$upper=$total_rec;
	}
	$lower_limit=$lower-1;

	$wap_sql.="limit $lower_limit,24";
	$db=connect_737_lan();
	if($res=mysql_query_decide($wap_sql))
	{
		$res_cnt=mysql_num_rows($res);
		if($res_cnt>0)
		{
			$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
			usort($results,"paidMemberSort");
			$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,'',$total_rec,$lower,$upper);
			echo $Ret;
		}
		else
		{
			echo "no results";
			$qs=$_SERVER['REQUEST_URI'];
			noResultLog($qs);
		}
			//mysql_close();
			$db=connect_db(1);
	}
	else
	{
		queryDieLog(mysql_error_js(),$wap_sql);
		//mysql_close();
		$db=connect_db(1);
	}
	exit;
}

function displayresult($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="",$ordering="")
{
	global $PAGELEN,$smarty,$checksum,$data;
	
	if ($data && $data["GENDER"]=="F")
		$smarty->assign("FEMALE_SEARCH","1");
	include_once("arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	
	/*$income_map=array("1" => "< Rs. 50K",
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
						"18" => "> Rs. 10Lac");*/
						
	$FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,FAMILYINFO,JOB_INFO,SPOUSE,MSTATUS,BTYPE,COMPLEXION,RELIGION,MTONGUE,ENTRY_DT,LAST_LOGIN_DT,RELATION,GENDER,SUBSCRIPTION,HAVECHILD,SUBCASTE,GOTHRA";

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
		
	$sql.=" $FIELDS from JPROFILE where  activatedKey=1 and PROFILEID in ($str)";
	
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
		$cellprofile=$data["PROFILEID"];
                $sql_sid="SELECT PROFILEID, SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID= '".$cellprofile."'";
                $res=mysql_query($sql_sid) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
                       $serverId=$row["SERVERID"];
		global $noOfActiveServers,$slave_activeServers,$mysqlObj;
		$myDbName=$slave_activeServers[$serverId];
                $myDb=$mysqlObj->connect("$myDbName");

		$imagesql="select RECEIVER,TYPE from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and RECEIVER in ($resultprofiles)";
		$imageresult=mysql_query($imagesql,$myDb)  or queryDieLog(mysql_error_js(),$imagesql);
		
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
		$imageresult=mysql_query_decide($imagesql,$myDb) or queryDieLog(mysql_error_js(),$imagesql);
		
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
		{
			$yourinfo=$myrow["YOURINFO"];
			$opendesc = $myrow["YOURINFO"];
		}
		else
		{
			$opendesc="";
			$yourinfo="";
		}
			
		$gothra=trim($myrow["GOTHRA"]);
		if($gothra=="i don't know")
			$gothra='';

		if(isFlagSet("SUBCASTE",$screening))
			$subcaste=trim($myrow["SUBCASTE"]);
		else
			$subcaste="";

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
		if(isFlagSet("FAMILYINFO",$myrow["SCREENING"]))
			$familyinfo = $myrow["FAMILYINFO"];
		else
			$familyinfo = "";

		if(isFlagSet("JOB_INFO",$myrow["SCREENING"]) && $jobinfo)
			$jobinfo = "My Job : ".$myrow["JOB_INFO"];
		else
			$jobinfo = "";

		if($fatherinfo || $siblinginfo || $familyinfo)
			$family="My Family : ".$fatherinfo.$siblinginfo.$familyinfo;
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
														    

			
		$country1=$myrow["COUNTRY_RES"];
		$country_res =$COUNTRY_DROP["$country1"];

		if($myrow["CITY_RES"]!="")
		{
			$city_res1=$myrow["CITY_RES"];
			
			if($myrow["COUNTRY_RES"]=="51")
				$residence=$CITY_INDIA_DROP["$city_res1"];
			else 
				$residence=$CITY_USA_DROP["$city_res1"];
			$city_res = $residence;
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
				if(wap_check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
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
				elseif(wap_check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
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
					"SUBCASTE" => $subcaste,
					"GOTHRA" => $gothra,
					"OCCUPATION" => $occupation,
					"COUNTRY_RES"=> $country_res,
					"CITY_RES"=>$city_res,
					"RESIDENCE" => $residence,
					"YOURINFO" => $yourinfo,
					"OPEN_DESC"=> $opendesc,
					"SPOUSE"=>$spouseinfo,
					"JOB_INFO"=>$jobinfo,
					"FAMILY"=>$family,
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
					"HAVECHILD"=>$myrow["HAVECHILD"],
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

function wap_check_privacy_filtered($myprofileid,$hisprofileid)
{
	$sql="select * from FILTERS where PROFILEID='$hisprofileid'";
	$resultfilter=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
	if(mysql_num_rows($resultfilter)>0)
	{
		$filterrow=mysql_fetch_array($resultfilter);							
		if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y")
		{
			$sqlfilter="select count(*) from JPROFILE where  activatedKey=1 and PROFILEID='$myprofileid'";					
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

function noResultLog($qs)
{
	$sql_log="INSERT INTO newjs.SMS_QUERYLOG VALUES('',now(),'".$qs."')";
	$res_log=mysql_query_decide($sql_log) or die("There has been some problem due to which request cannot be processed.");
}

function queryDieLog($sqlerr,$sqlquery)
{
	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /var/www/html/tieups/airtel/logerror.txt";
	passthru($errormsg);
	die("The request cannot be processed");
}


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

function generateXML($results,$longdesc_limit,$shortdesc_limit,$cellUserIsRegistered="",$cellUserIsPaid="",$lista="",$total_rec="",$lower="",$upper="",$additional_parameters="")
{
	global $wap,$MSTATUS,$CHILDREN,$BODYTYPE,$COMPLEXION,$RSTATUS;
	global $search_keyword,$contact_details;

	$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	$Ret.="<ProfileList>\n";
															     
	if($cellUserIsRegistered=="Y")
		$Ret.="<registered>true</registered>\n";
	else
		$Ret.="<registered>false</registered>\n";
												     
	if($cellUserIsPaid=="Y")
		$Ret.="<cellupaid>true</cellupaid>\n";
	else
		$Ret.="<cellupaid>false</cellupaid>\n";

	if($search_keyword)
		$Ret.="<SearchKeyword>$search_keyword</searchKeyword>\n";

	$Ret.="<NoModify>1</NoModify>\n";

	if($total_rec)
	{
		$Ret.="<total>$total_rec</total>\n";
		if($lower)
			$Ret.="<lower>$lower</lower>\n";
		else
			$Ret.="<lower>0</lower>\n";
		$Ret.="<upper>$upper</upper>\n";
	}

	if(is_array($additional_parameters))
	{
		for($i=0;$i<count($additional_parameters);$i++)
		{
			$temp=explode("-",$additional_parameters[$i]);
			$Ret.="<$temp[0]>$temp[1]</$temp[0]>\n";
		}
	}

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
												     
		if($shortdesc)
		{
			$longdesc="";
			$opendesc="";
			$career="";
			$spouse="";
			$family="";
												     
			if($results[$a]['USERNAME'])
				$longdesc="Profileid: ".str_replace($arr_search,$arr_repl,$results[$a]['USERNAME'])."\n";
												     
			if($results[$a]['AGE'])
			{
				$longdesc.=str_replace($arr_search,$arr_repl,$results[$a]['AGE']);
				$profiledetails['AGE']="Age : ".str_replace($arr_search,$arr_repl,$results[$a]['AGE'])." years";
			}
			if($results[$a]['HEIGHT'])
			{
				$longdesc.=", ".$results[$a]['HEIGHT'];
				$profiledetails['HEIGHT']="Height : ".$results[$a]['HEIGHT'];
			}
			if($results[$a]['CASTE'])
			{
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']);
				$profiledetails['CASTE']="Caste : ".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']);
			}
			//added by lavesh
			if($results[$a]["SUBCASTE"])
			{
				$longdesc.="(".str_replace($arr_search,$arr_repl,$results[$a]['SUBCASTE']).")";
			}
			if($results[$a]["GOTHRA"])
			{
				$longdesc.=",GOTHRA: ".str_replace($arr_search,$arr_repl,$results[$a]['GOTHRA']);
			}	
			if($results[$a]['MTONGUE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['MTONGUE']);

			if($results[$a]['DEGREE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE']);
												     
			if($results[$a]['OCCUPATION'])
			{
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']);
				$profiledetails['OCCUPATION']="Profession : ".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']);
			}
			if($results[$a]['MSTATUS'])
			{
				$profiledetails['MSTATUS']="Marital Status : ".str_replace($arr_search,$arr_repl,$MSTATUS[$results[$a]['MSTATUS']]);	
			}
			if($results[$a]['HAVECHILD'])
			{
				$profiledetails['HAVECHILD']="Have Children : ".str_replace($arr_search,$arr_repl,$CHILDREN[$results[$a]['HAVECHILD']]);
			}

			if($results[$a]['RESIDENCE'])
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE']);
			
			if($results[$a]['INCOME'])
			{
				$profiledetails['INCOME']="Annual Income : ".str_replace($arr_search,$arr_repl,$results[$a]['INCOME']);
			}
			if($results[$a]['COUNTRY_RES'])
			{
				if($results[$a]['COUNTRY_RES'] && $results[$a]['CITY_RES'])
				{
					$profiledetails['RESIDENCE']="City : ".str_replace($arr_search,$arr_repl,$results[$a]['CITY_RES'])." , ".str_replace($arr_search,$arr_repl,$results[$a]['COUNTRY_RES']);
				}
				else
					$profiledetails['RESIDENCE']="Country : ".str_replace($arr_search,$arr_repl,$results[$a]['COUNTRY_RES']);
			}
			if($cellGender=="F" && $results[$a]['INCOME'])
			{
				$longdesc.=", ".str_replace($arr_search,$arr_repl,$results[$a]['INCOME']);
			}

			if($results[$a]["OPEN_DESC"])
			{
				$opendesc.=str_replace($arr_search,$arr_repl,$results[$a]['OPEN_DESC']);
			}
			if($results[$a]["JOB_INFO"])
			{
				$career.=str_replace($arr_search,$arr_repl,$results[$a]['JOB_INFO']);
			}
			if($results[$a]["SPOUSE"])
			{
				$spouse.=str_replace($arr_search,$arr_repl,$results[$a]['SPOUSE']);
			}
			if($results[$a]["FAMILY"])
			{
				$family.=str_replace($arr_search,$arr_repl,$results[$a]['FAMILY']);
			}
		}
		$longdesc=trimToNextSpace($longdesc,0,$longdesc_limit);
		$longdesc=trim($longdesc,",");
												     
		$Ret="\t<Profile>\n";
		$Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";
		$Ret.="\t\t<username>".str_replace($arr_search,$arr_repl,$results[$a]['USERNAME'])."</username>\n";
												     
		//if($shortdesc)
			//$Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";
												     
		if($longdesc)
			$Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";
															     
		if(strstr($results[$a]['SUBSCRIPTION'],"F") || strstr($results[$a]['SUBSCRIPTION'],"V"))
			$Ret.="\t\t<paid>true</paid>\n";
		else
			$Ret.="\t\t<paid>false</paid>\n";

		if($wap=="yes")
		{
			$Ret.="\t\t<ProfileDescription>\n";
			$Ret.="\t\t\t<photo>";
			if($results[$a]['HAVEPHOTO']=='Y')
				$Ret.="http://ser4.jeevansathi.com/profile/convert_image_to_gif.php?profileid=" . $results[$a]['PROFILEID'];
			$Ret.="</photo>\n";
			$Ret.="\t\t\t<OpenDesc>".$opendesc."</OpenDesc>\n";
			$Ret.="\t\t\t<Career>".$career."</Career>\n";
			$Ret.="\t\t\t<DesiredPartner>".$spouse."</DesiredPartner>\n";
			$Ret.="\t\t\t<Family>".$family."</Family>\n";

			
			$Ret.="\t\t\t<ProfileDetails>\n";
			$Ret.="\t\t\t\t<Height>".$profiledetails['HEIGHT']."</Height>\n";
			$Ret.="\t\t\t\t<Age>".$profiledetails['AGE']."</Age>\n";
			$Ret.="\t\t\t\t<Residence>".$profiledetails['RESIDENCE']."</Residence>\n";
			$Ret.="\t\t\t\t<Caste>".$profiledetails['CASTE']."</Caste>\n";
			$Ret.="\t\t\t\t<Occupation>".$profiledetails['OCCUPATION']."</Occupation>\n";
			$Ret.="\t\t\t\t<MaritalStatus>".$profiledetails['MSTATUS']."</MaritalStatus>\n";
			$Ret.="\t\t\t\t<Children>".$profiledetails['HAVECHILD']."</Children>\n";
			$Ret.="\t\t\t\t<Income>".$profiledetails['INCOME']."</Income>\n";
			$Ret.="\t\t\t</ProfileDetails>\n";

			$Ret.="\t\t</ProfileDescription>\n";

		}
		elseif($contact_details=='list')
		{
			if($cellUserIsPaid=="Y" || strstr($results[$a]['SUBSCRIPTION'],"F") )
			{
				$pid=$results[$a]['PROFILEID'];
				$tempusername=str_replace($arr_search,$arr_repl,$results[$a]['USERNAME']);
				$ContactDetails.="\n\t\tProfile Id $tempusername\n\t\tContact details \n";

				$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,PHONE_RES,PHONE_MOB from JPROFILE where  activatedKey=1 and PROFILEID=$pid";
				$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
				$row=mysql_fetch_array($result);

				if($row['EMAIL']!="")
					$ContactDetails.="\t\tEmail-".str_replace($arr_search,$arr_repl,$row['EMAIL'])."\n";
				if($row['SHOWPHONE_RES']=="Y" && $row['PHONE_RES']!="")
					$ContactDetails.="\t\tPhone-".str_replace($arr_search,$arr_repl,$row['PHONE_RES'])."\n";
				if($row['SHOWPHONE_MOB']=="Y" && $row['PHONE_MOB']!="")
					$ContactDetails.="\t\tMobile-".str_replace($arr_search,$arr_repl,$row['PHONE_MOB'])."\n";
			}
			else
				$ContactDetails="\n\t\tContact details of this profile is available, you need to be a paid member to view the details\n";
			$Ret.="\t\t<ContactDetails>$ContactDetails\t\t</ContactDetails>\n";
			unset($ContactDetails);	
		}

		$Ret.="\t</Profile>\n";
		echo $Ret;
		unset($Ret);
	}
												     
	$Ret="</ProfileList>\n";

	return $Ret;
}

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
			return substr($str,$start,$spacePos);
		}
	}

}
?>
