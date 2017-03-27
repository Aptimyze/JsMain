<?php
/**
 *       Filename        :	search.inc
 *       Description     :
 *       Created by      :
 *       Changed by      :
 *       Changed on      :
 Changes         :       New Service added called Eclassified , changes done in result array.One Field added in array
 called CONTACT_DETAILS.
 **/
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
require_once(JsConstants::$docRoot."/P/display_result.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/P/functions.inc");
include_once(JsConstants::$docRoot."/P/arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/P/algoSuggestedProfiles.php");


function get_keyword($Gender,$city='',$country='',$mtongue='')
{
	global $smarty,$CITY_DROP,$MTONGUE_DROP_SMALL,$COUNTRY_DROP;
	if($city)
	{
		$size=count($city);
		while($size)
		{
			$size--;
			$loc=$CITY_DROP[$city[$size]];
			if($loc!='')
				$kwd[]=$loc;
		}
	}
	elseif($country)
	{
		$size=count($country);
		while($size)
		{
			$size--;
			$loc=$COUNTRY_DROP[$country[$size]];
			if($loc!='')
				$kwd[]=$loc;
		}
	}
	if($mtongue)
	{
		$size=count($mtongue);
		while($size)
		{
			$size--;
			$comm=$MTONGUE_DROP_SMALL[$mtongue[$size]];
			if($comm!='')
				$kwd[]=$comm;
		}
	}
	if($Gender=='M')
		$str="Groom";
	else
		$str="Bride";

	$array_kid=array('wedding','marriage','matrimony','engagement');
	$array_channel=array('afs_kwd_wedding','afs_kwd_marriage','afs_kwd_matrimony','afs_kwd_engagement');
	$len=count($array_kid);
	$len--;
	$val=rand(0,$len);
	if($kwd)
		$str.=",".implode(",",$kwd).",".$array_kid[$val];
	else
		$str.=",".$array_kid[$val];
	$smarty->assign("channel",$array_channel[$val]);
	return $str;
}

function gtalk_users($resultprofiles)
{
	global $smarty;
	$onlinesql="select USER as profileID,0,1 from bot_jeevansathi.user_online where USER in ($resultprofiles) ";
	$onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");

	if(mysql_num_rows($onlineresult) > 0)
	{
		//Declared in connect.inc
		global $gtalk_status,$gtalk_title;

		while($myonline=mysql_fetch_array($onlineresult))
		{
			//Gtalk status find 
			$chat_flag=$myonline['chat_flag'];
			$on_off_flag=$myonline['on_off_flag'];
			$G_STATUS[$myonline['profileID']]=$gtalk_status[$chat_flag][$on_off_flag];
			$G_TITLE[$myonline['profileID']]=$gtalk_title[$chat_flag][$on_off_flag];
			$gtalk_onlinemembers[]=$myonline["profileID"];
			$gtalk_status_arr[$myonline["profileID"]]=$myonline["on_off_flag"];
		}		
		if($gtalk_onlinemembers)
		{
			$merge_array[0]=$gtalk_onlinemembers;
			$merge_array[1]=$gtalk_status_arr;
		}
	}
	$smarty->assign("G_STATUS",$G_STATUS);
	$smarty->assign("G_TITLE",$G_TITLE);

	mysql_free_result($onlineresult);
	return $merge_array;
}
function yahoo_users($resultprofiles)
{
	global $smarty;
	$onlinesql="select PROFILEID,chat_flag,online_flag from bot_jeevansathi.user_yahoo where profileID in ($resultprofiles) AND show_in_search=1 and online_flag IN(1,2,3)";
	$onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");

	if(mysql_num_rows($onlineresult) > 0)
	{
		//Declared in connect.inc
		global $yahoo_status,$yahoo_title;
		while($myonline=mysql_fetch_array($onlineresult))
		{
			$chreline_flag=$myonline['online_flag'];
			$YAHOO_STATUS[$myonline['PROFILEID']]=$yahoo_status[$chat_flag][$online_flag];
			$YAHOO_TITLE[$myonline['PROFILEID']]=$yahoo_title[$chat_flag][$online_flag];
			$yahoo_onlinemembers[]=$myonline["PROFILEID"];
			$yahoo_status_arr[$myonline["PROFILEID"]]=$myonline["online_flag"];
		}
	}
	$smarty->assign("Y_STATUS",$YAHOO_STATUS);
	$smarty->assign("Y_TITLE",$YAHOO_TITLE);
	if($yahoo_onlinemembers)
	{
		$merge_array[0]=$yahoo_onlinemembers;
		$merge_array[1]=$yahoo_status_arr;
	}

	mysql_free_result($onlineresult);
	return $merge_array;
}
function displayresults($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="",$ordering="",$newsearch="",$optional="",$inf_checksum="",$castemapping="",$crmback="",$savesearch="",$label_select_no='',$from_index="",$save_search_redirect="",$seo_string="")
{
	global $PAGELEN,$smarty,$checksum,$data,$marriage_bureau_profile,$db,$MESSENGER_CHANNEL,$income_map,$IMG_URL,$PHOTO_URL;

	$orig_scriptname = $scriptname;
	//added by lavesh
	if($scriptname=='/profile/simcontacts_search.php')
	{
		$scriptname='/P/simcontacts_search.php';
		$js_search_recomend=1;
	}
	//Ends Here

	//added by lavesh as in this case we will pick 2nd result row instead of first.
	if($scriptname=='simprofile_search_new.php' || $scriptname=='flag_single_contact_aj')
		$flag_script=1;
	if($scriptname=='simprofile_search_new.php')
		$scriptname='simprofile_search.php';
	if($scriptname=='flag_single_contact_aj')
		$scriptname='single_contact_aj.php';
	//Ends Here.

	$monthnum=array("Jan"=>"01",
			"Feb"=>"02",
			"Mar"=>"03",
			"Apr"=>"04",
			"May"=>"05",
			"Jun"=>"06",
			"Jul"=>"07",
			"Aug"=>"08",
			"Sep"=>"09",
			"Oct"=>"10",
			"Nov"=>"11",
			"Dec"=>"12");


	if($from_index)
	{
		if ($data)
		{
			$smarty->assign("length",12);
		}
		else
		{
			$PAGELEN = 6;
			$smarty->assign("length",6);
		}
		if ($newsearch!= 'isearch')
			$smarty->assign("show_record_cnt","1");
	}
	else
		$smarty->assign("length",12);


	if ($data && $data["GENDER"]=="F")
		$smarty->assign("FEMALE_SEARCH","1");

	include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

	$lang=$_COOKIE['JS_LANG'];

	/*		$income_map=array("1" => "< Rs. 50K",
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
	 */
	$FIELDS="PROFILEID,SOURCE,USERNAME,AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,SPOUSE,SHOW_HOROSCOPE,GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,LAST_LOGIN_DT,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,RELIGION,SHOWPHONE_MOB,EMAIL, PHONE_RES, SHOWPHONE_RES, MESSENGER_ID, MESSENGER_CHANNEL, SHOWMESSENGER, CONTACT, SHOWADDRESS, PARENTS_CONTACT, SHOW_PARENTS_CONTACT,MSTATUS,MOB_STATUS,LANDL_STATUS,PHONE_FLAG";

	/********************************************************************************************************************
	  Added By	:	Shakti Srivastava
Date		:	15 November, 2005
Reason		:	For optimizing search queries by fetching result set for 120 records
:	and subsequently using mysql_data_seek()
	 ********************************************************************************************************************/
	//added by lavesh to log user going beyond 50 pages
	if($curcount>=600)
	{
		if($searchchecksum)
		{
			/*$print_r = "SCRIPTNAME:".$scriptname;
			  $print_r .= "POST:".print_r($_POST,true);
			  $print_r .= "Get:".print_r($_GET,true);
			  mail("gaurav.arora@jeevansathi.com,lavesh.rawat@jeevansathi.com","Inputprofile TieupB - forceful login part",$print_r);
			 */

			$PAGENO=$curcount/12;
			$ip=FetchClientIP();
			if(strstr($ip, ","))
			{
				$ip_new = explode(",",$ip);
				$ip = $ip_new[1];
			}
			if( strstr($searchchecksum,'i') )//advance search
			{
				$arr=explode("i",$searchchecksum);
				$temp_sid=$arr[1];
			}
			else //top search
				$temp_sid=$searchchecksum;
			$sql1="REPLACE INTO newjs.SEARCH_50 VALUES('','$temp_sid','$ip',now(),$PAGENO+1)";
			//mysql_query_decide($sql1);
		}
	}
	//Ends Here.

	if($optional)
	{
		$i=0;
		if($scriptname == "/P/visitors.php")
			@mysql_data_seek($result,$curcount);
		else
			@mysql_data_seek($result,$curcount%120);
		while($myrow=mysql_fetch_row($result))
		{
			if($flag_script)
			{
				$RESULT_ARRAY_3d[]=$myrow[1];
				$str.="'" . $myrow[1] . "',";					
			}
			else
			{
				$RESULT_ARRAY_3d[]=$myrow[0];
				$str.="'" . $myrow[0] . "',";
			}
			$i++;

			if($i>=$optional)
				break;
		}
	}
	else
	{
		while($myrow=mysql_fetch_row($result))
		{
			if($flag_script)
			{
				$RESULT_ARRAY_3d[]=$myrow[1];
				$str.="'" . $myrow[1] . "',";
				$str_date[]=$myrow[2];
			}
			else
			{
				$RESULT_ARRAY_3d[]=$myrow[0];
				$str.="'" . $myrow[0] . "',";
				$str_date[]=$myrow[1];
			}
		}
	}
	//////////////////////////////////
	for($i=0;$i<count($str_date);$i++)
	{
		$year=2005;
		$date = $str_date[$i];
		if($date>365)
		{
			while($date>365)
			{
				$year++;
				if(($year%4==0)&&($year%100!=0)||($year%400)==0)
					$date-=366;
				else
					$date-=365;
			}

		}
		if(($year%4==0)&&($year%100!=0)||($year%400==0))
			$leap=1;
		else
			$leap=0;
		if($leap)
			$montharr=array("Jan"=>31,
					"Feb"=>28,
					"Mar"=>31,
					"Apr"=>30,
					"May"=>31,
					"Jun"=>30,
					"Jul"=>31,
					"Aug"=>31,
					"Sep"=>30,
					"Oct"=>31,
					"Nov"=>30,
					"Dec"=>31);
		else
			$montharr=array("Jan"=>31,
					"Feb"=>28,
					"Mar"=>31,
					"Apr"=>30,
					"May"=>31,
					"Jun"=>30,
					"Jul"=>31,
					"Aug"=>31,
					"Sep"=>30,
					"Oct"=>31,
					"Nov"=>30,
					"Dec"=>31);
		foreach($montharr as $key=>$value)
		{
			if($date<=$value)
			{
				$month=$key;
				break;
			}
			$date=$date-$value;

		}
		$day=$date;
		$date=$day."-".$month."-".$year;
		$month=$monthnum[$month];
		if($month && $day && $year)
		{
			$datestamp=mktime(0, 0, 0,$month,$day+1,$year);
			$str_date_formated[$i]=date("d-M-Y",$datestamp);
		}
	}

	//////////////////////////////////	
	$str=substr($str,0,strlen($str)-1);

	$sql="select";

	if($nocalc=="")
		$sql.=" SQL_CALC_FOUND_ROWS";

	$sql.=" $FIELDS from newjs.JPROFILE where  activatedKey=1 and PROFILEID in ($str)";

	if($putactivate=="1")
		$sql.=" and ACTIVATED='Y'";

	if($ordering=="F")
		$sql.=" order by ENTRY_DT desc";
	elseif($ordering=="M")
		$sql.=" order by MOD_DT desc";
	elseif($ordering=="L")
		$sql.=" order by LAST_LOGIN_DT desc";
	else 
		$sql.=" order by SORT_DT desc";

	if($nocalc=="")
		$sql.=" limit $curcount,$PAGELEN";
	$result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	if($nocalc=="")
	{
		$sql="select FOUND_ROWS() as cnt";
		$resultcount=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		$countrow=mysql_fetch_row($resultcount);
		$totalrec=$countrow[0];
	}

	$sno=1;
	//$mob_str="";


	//Setting the thumb icon in search file
	$i_tr=0;
	while($myrow=mysql_fetch_array($result1))
	{
		$resultprofiles.="'" . $myrow["PROFILEID"] . "',";
		//if($myrow["PHONE_MOB"])
		//         $mob_str.="'".$myrow["PHONE_MOB"]."',";
		$gender_for_icon = $myrow["GENDER"];	
		$trend[$i_tr]=calculate_user_trend($myrow);

		$i_tr++;
	}
	unset($i_tr);
	if($data['PROFILEID'])
	{	$trends_logic=getting_reverse_trend($trend);
		$smarty->assign("TRENDS_LOGIC",$trends_logic);


	}

	//Setting of thumb icon ends here.

	$resultprofiles=substr($resultprofiles,0,strlen($resultprofiles)-1);
	//$mob_str=substr($mob_str,0,-1);

	// move the pointer of the recordset back to record 1
	@mysql_data_seek($result1,0);

	if($data["PROFILEID"]!="" && mysql_num_rows($result1)>0)
	{
		//Sharding On Contacts done by Lavesh Rawat
		$contactResult=getResultSet("RECEIVER,TYPE",$data[PROFILEID],"",$resultprofiles,"","","","","","","Y","");

		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$contacted1[$contactResult[$key]["RECEIVER"]]=$contactResult[$key]["TYPE"];
				$contacted2[$contactResult[$key]["RECEIVER"]]="R";
			}
		}
		unset($contactResult);

		$contactResult=getResultSet("SENDER,TYPE,TIME",$resultprofiles,"",$data[PROFILEID],"","","","","","","Y","");
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$profile_time_arr[$i]["PROFILEID"]=$contactResult[$key]["SENDER"];
				$profile_time_arr[$i]["TIME"]=$contactResult[$key]["TIME"];
				$contacted1[$contactResult[$key]["SENDER"]]=$contactResult[$key]["TYPE"];
				$contacted2[$contactResult[$key]["SENDER"]]="S";
			}
		}
		unset($contactResult);
		//Sharding On Contacts done by Lavesh Rawat


		if($scriptname=="/P/awaiting_decline_search.php")
		{
			$imagesql="select MATCH_DATE,PROFILEID from jsadmin.OFFLINE_MATCHES where MATCH_ID='".$data['PROFILEID']."' and PROFILEID IN($resultprofiles)";
			$imageresult=mysql_query_decide($imagesql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$imagesql,"ShowErrTemplate");
			if(mysql_num_rows($imageresult)>0)
			{
				if(!$i)	
					$i=0;
				while($imagerow=mysql_fetch_array($imageresult))
				{
					//code added by Tapan Arora
					$profile_time_arr[$i]["PROFILEID"]=$imagerow["PROFILEID"];
					$profile_time_arr[$i]["TIME"]=$imagerow["MATCH_DATE"];
					//code ended by Tapan Arora
					$i++;
				}
			}

		}
		//code added by Tapan Arora to calculate days to Respond
		$days_to_respond_arr=calc_days_to_respond($profile_time_arr);
		//code addition ended by Tapan Arora

		$bookmarksql="select BOOKMARKEE,BKNOTE from BOOKMARKS where BOOKMARKER='" . $data["PROFILEID"] . "' and BOOKMARKEE in ($resultprofiles)";
		$bookresult=mysql_query_decide($bookmarksql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$bookmarksql,"ShowErrTemplate");

		if(mysql_num_rows($bookresult) > 0)
		{
			while($mybooks=mysql_fetch_array($bookresult))
			{
				$bookmarkee=$mybooks["BOOKMARKEE"];
				$bookmarks[$bookmarkee]=htmlspecialchars($mybooks["BKNOTE"],ENT_QUOTES);
			}
		}
		mysql_free_result($bookresult);

		//added by lavesh.
		$sql_1="SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='" . $data["PROFILEID"] . "' AND IGNORED_PROFILEID in ($resultprofiles)";
		$result_1=mysql_query_decide($sql_1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_1,"ShowErrTemplate");
		if(mysql_num_rows($result_1) > 0)
		{
			while($myrow_1=mysql_fetch_array($result_1))
			{	
				$ignores[]=$myrow_1["IGNORED_PROFILEID"];
			}
		}

		//Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST
		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($data["PROFILEID"],'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");

		$sql_1= "SELECT PROFILEID FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY='" . $data["PROFILEID"] . "' AND PROFILEID in ($resultprofiles)";
		$result_1 = $mysqlObj->executeQuery($sql_1,$myDb);
		while($myrow_1=$mysqlObj->fetchArray($result_1))
		{
			$photo_reqs[]=$myrow_1["PROFILEID"];
		}
		mysql_free_result($result_1);

		$sqlviewerdata="SELECT AGE,HEIGHT,HANDICAPPED,BTYPE,CASTE,COUNTRY_RES,CITY_RES,DIET,DRINK,EDU_LEVEL,EDU_LEVEL_NEW,INCOME,MANGLIK,MSTATUS,OCCUPATION,SMOKE,COMPLEXION,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
		$resviewerdata=mysql_query_decide($sqlviewerdata) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlviewerdata,"ShowErrTemplate");
		$viewerdata=mysql_fetch_assoc($resviewerdata);
		//Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST

		//ends here.

		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		$jpartnerObj=new Jpartner;
		$mysqlObj=new Mysql;
	}
	$resultprofiles = implode(",",array_diff(explode(",",$resultprofiles), [0]));
	// do this in logged out case also
	if(mysql_num_rows($result1)>0)
	{
		$sql_astro="SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ($resultprofiles)";
		$result_astro=mysql_query_decide($sql_astro) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_astro,"ShowErrTemplate");

		if(mysql_num_rows($result_astro) > 0)
		{
			while($myrow_astro=mysql_fetch_array($result_astro))
			{
				$astro_pid=$myrow_astro["PROFILEID"];
				$astro_array[]=$astro_pid;
				if($gender_for_icon != "")
				{
					if($gender == 'M')
						$gender_value = 1;
					else
						$gender_value = 2;
				}
				else
					$gender_value = "";

				if($astro_pid)
				{
					$lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
					$sun=$myrow_astro['SUN_DEGREES_FULL'];
					$mo=$myrow_astro['MOON_DEGREES_FULL'];
					$ma=$myrow_astro['MARS_DEGREES_FULL'];
					$me=$myrow_astro['MERCURY_DEGREES_FULL'];
					$ju=$myrow_astro['JUPITER_DEGREES_FULL'];
					$ve=$myrow_astro['VENUS_DEGREES_FULL'];
					$sa=$myrow_astro['SATURN_DEGREES_FULL'];
					$astro_details[$astro_pid]="$astro_pid:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
				}
			}
		}
		mysql_free_result($result_astro);
	}

	if($resultprofiles)
	{
		$onlinesql="select userID from userplane.users where userID in ($resultprofiles)";
		$onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");

		if(mysql_num_rows($onlineresult) > 0)
		{
			while($myonline=mysql_fetch_array($onlineresult))
			{
				$onlinemembers[]=$myonline["userID"];
			}
		}

		mysql_free_result($onlineresult);

		//Getting data of gmail online users.
		$gmail_data=gtalk_users($resultprofiles);
		if(is_array($gmail_data))
		{
			$gtalk_onlinemembers=$gmail_data[0];
			$gtalk_status_arr=$gmail_data[1];	
		}
		unset($gmail_data);

		//Getting data of yahoo online users.
		$yahoo_data=yahoo_users($resultprofiles);
		if(is_array($yahoo_data))
		{
			$yahoo_onlinemembers=$yahoo_data[0];
			$yahoo_status_arr=$yahoo_dta[1];
		}
		unset($yahoo_data);

		//Symfony Photo Modification
		$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ProfilePicUrl,ThumbailUrl,SearchPicUrl");

	}

	/* Code to be removed
	//code added to display info about whether mobile no of particular profile has been verified or not.
	$mobarr=array();

	if($resultprofiles && $mob_str)
	{
	$sql_mob="SELECT M.MOBILE AS MOBILE  FROM newjs.MOBILE_VERIFICATION_SMS AS M  WHERE M.MOBILE IN ($mob_str)";
	$result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
	while($row_mob=mysql_fetch_array($result_mob))
	$mobarr[]=$row_mob['MOBILE'];
	}
	//code Addition Ended 
	 */

	//If user is logged in, then check whether the user we are searching is viewed by logged in user.
	//Optimized by lavesh as this query is in loop earlier.
	if($data && $resultprofiles)
	{
		$viewer=$data['PROFILEID'];

		$db_211 = connect_211();
		$sql_view="select VIEWED from newjs.VIEW_LOG where VIEWER='$viewer' and VIEWED IN ($resultprofiles)";
		$res_view=mysql_query_decide($sql_view,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_view,"ShowErrTemplate");
		while($row_view=mysql_fetch_array($res_view))
		{
			$viewed_by_user_arr[]=$row_view["VIEWED"];
		}
		//mysql_close($db_211);

		$scriptname_arr = explode("/",$orig_scriptname);
		$cnt_scriptname = count($scriptname_arr);
		if($scriptname_arr[$cnt_scriptname - 1] == "advance_next.php" || $scriptname_arr[$cnt_scriptname - 1] == "photo_requests_list.php" || $scriptname_arr[$cnt_scriptname - 1] == "flag_single_contact_aj" || $scriptname_arr[$cnt_scriptname - 1] == "simprofile_search_new.php" || $scriptname_arr[$cnt_scriptname - 1] == "simprofile_search.php")
			$db = connect_737_ro();
		elseif($scriptname_arr[$cnt_scriptname - 1] == "visitors.php")
			$db = connect_737_lan();
		elseif($scriptname_arr[$cnt_scriptname - 1] == "single_contact_aj.php")
			$db1 = connect_db();
		else
			$db = connect_db();

	}
	//Check whether the user we are searching is viewed by login user is ended here
	while($myrow=mysql_fetch_array($result1))
	{

		$income=$myrow["INCOME"];
		$occ=$myrow["OCCUPATION"];
		if($lang=='hin')
		{
			$occupation_temp=label_select("HIN_OCCUPATION",$occ);
			$occupation=$occupation_temp[0];
			unset($occupation_temp);
		}
		else
		{
			$occupation=$OCCUPATION_DROP["$occ"];
		}

		$caste1=$myrow["CASTE"];
		if($lang=='hin')
		{
			$caste_temp=label_select("HIN_CASTE",$caste1);
			$caste=$caste_temp[0];
			unset($caste_temp);
		}
		else
		{
			$caste=$CASTE_DROP["$caste1"];
		}

		if($lang=='hin')
		{
			$mtongue1 = label_select("HIN_MTONGUE",$myrow['MTONGUE']);
		}
		else
		{
			$mtongue_temp=$myrow['MTONGUE'];
			$mtongue1[0] = $MTONGUE_DROP["$mtongue_temp"];
		}
		$mtongue = $mtongue1[0];

		if($lang=='hin')
		{
			$edu_leveln=label_select("HIN_EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
		}
		else
		{
			$edu_temp=$myrow['EDU_LEVEL_NEW'];
			$edu_leveln[0]=$EDUCATION_LEVEL_NEW_DROP["$edu_temp"];
		}
		$edu_level=$edu_leveln[0];

		$screening=$myrow["SCREENING"];
		if(isFlagSet("SUBCASTE",$screening))
			$subcaste=trim($myrow["SUBCASTE"]);
		else
			$subcaste="";

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

		if($myrow["CITY_RES"]!="")
		{
			$city_res1=$myrow["CITY_RES"];

			if($myrow["COUNTRY_RES"]=="51")
			{
				if($lang=='hin')
				{
					$residence_temp=label_select("HIN_CITY_INDIA",$city_res1);
					$residence=$residence_temp[0];
					unset($residence_temp);
				}
				else
					$residence=$CITY_INDIA_DROP["$city_res1"];
			}
			else 
				$residence=$CITY_USA_DROP["$city_res1"];
		}
		else 
		{
			$country1=$myrow["COUNTRY_RES"];
			if($lang=='hin')
			{
				$residence_temp=label_select("HIN_COUNTRY",$country1);
				$residence=$residence_temp[0];
				unset($residence_temp);
			}
			else
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
		//line changed by lavesh
		unset($contact_details);
		if(in_array("D",$subscription)) //&& !in_array("S",$subscription))
			$contact_details=1;
		else 
			$contact_details=0;

		if(in_array("F",$subscription) && !in_array("D",$subscription))
			$erishta_member = 1;
		else 
			$erishta_member = 0;

		if(in_array("D",$subscription) && in_array("S",$subscription))
			$contact_details_verified = 0;
		else
			$contact_details_verified = 1;

		if($myrow["HAVEPHOTO"]=="U")
			$havephoto="U";
		elseif($myrow["HAVEPHOTO"]=="Y")
			$havephoto="Y";
		else 
			$havephoto="N";

		if(is_array($bookmarks))
		{
			$pid=$myrow["PROFILEID"];
			if(array_key_exists($pid,$bookmarks))
			{

				$bookmarked=1;
				if($bookmarks[$pid]!='')
				{
					$bknote=ereg_replace("\r\n|\n\r|\n|\r","#n#",$bookmarks[$pid]);
					$bknotedis='';
					if(strlen($bknote)>30)
					{
						$a=$bknote;
						while(strlen($a)>30)
						{
							$display=substr($a,0,30);
							if($display[29]==" ")
							{
								$a=substr($a,30,strlen($a)-30);
							}
							else
							{
								for($i=strlen($display)-1;$i>=0;$i--)
								{
									if($display[$i]==" ")
										break;
								}
								if($i>=19)
								{
									$leftover=substr($display,$i+1,strlen($display)-$i-1);
									$display=substr($display,0,$i+1);

									$a=substr($a,30,strlen($a)-30);
									$a=$leftover.$a;

								}
								else
									$a=substr($a,30,strlen($a)-30);
							}
							$bknotedis.=$display."<br>";
						}
						$bknotedis.=$a;
					}
					else
						$bknotedis='';
				}
				else
				{
					$bookmarked=1;
					$bknote='';
					$bknotedis='';
				}

			}
			else
			{
				$bookmarked=0;
				$bknote='';
				$bknotedis='';
			}
		}
		else 
		{
			$bookmarked=0;
			$bknote='';
			$bknotedis='';
		}
		//added by lavesh
		if(is_array($ignores) && in_array($myrow["PROFILEID"],$ignores))
			$ignore=1;
		else
			$ignore=0;

		if(is_array($photo_reqs) && in_array($myrow["PROFILEID"],$photo_reqs))
			$photo_req=1;
		else
			$photo_req=0;
		//ends here.

		if(is_array($viewed_by_user_arr) && in_array($myrow["PROFILEID"],$viewed_by_user_arr))
			$viewed_by_user='yes';
		else
			$viewed_by_user='no';

		if(is_array($onlinemembers) && in_array($myrow["PROFILEID"],$onlinemembers))
			$online=1;
		else 
			$online=0;

		//Checking if user is online in gmail or not
		if(is_array($gtalk_onlinemembers) && in_array($myrow["PROFILEID"],$gtalk_onlinemembers))
		{
			$gtalk_online=1;
			$gtalk_status=$gtalk_status_arr[$myrow["PROFILEID"]];
		}
		else
		{
			$gtalk_online=0;
			unset($gtalk_status);
		}

		//checking if user is online in yahoo or not
		if(is_array($yahoo_onlinemembers) && in_array($myrow["PROFILEID"],$yahoo_onlinemembers))
		{
			$yahoo_online=1;
			$yahoo_status=$yahoo_status_arr[$myrow["PROFILEID"]];
		}
		else
		{
			$yahoo_online=0;
			unset($yahoo_status);
		}

		if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
		{
			if(!$data)
			{
				//$havephoto="P";
				$havephoto="L";
			}
			elseif($data && $myrow["PRIVACY"]=="F")
			{
				if(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
					//$havephoto="P";
					$havephoto="F";
			}
		}
		if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
		{
			//if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
			//$havephoto="P";
			if($myrow["PHOTO_DISPLAY"]=="H")
				$havephoto="H";
			elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
				$havephoto="L";
			elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
				$havephoto="L";
			elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
			{
				if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
					;
				else 
				{
					//$havephoto="P";
					$havephoto="C";
				}
			}
			elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
			{
				if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
					;
				elseif(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
					//$havephoto="P";
					$havephoto="P";
			}
		}

		/*********************************************************************************************************************
		  Changed By	: Shakti Srivastava
		  Change Date	: 27 July 2005
Reason		: "YOURINFO" has to be broken into two parts so that the initial 3 words can be shown in Bold in
the template
		 **********************************************************************************************************************/	

		$yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,300);
		//$yourinfo=str_replace('0','',$yourinfo);
		$yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
		$yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
		$yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
		$yourinfo=str_replace('  ',' ',$yourinfo);              // replace '  ' (2 spaces) with ' ' (single space)

		$temp="";
		$protitle="";
		$counter=0;
		$len=0;
		$temp=explode(" ",$yourinfo);
		for($i=0;$i<=2;$i++)
		{
			$protitle.=" ".$temp[$i];
		}

		$protitle=str_replace(',',', ',$protitle);              //replace ',' with ', '(comma, space)
		$protitle=str_replace(' ,',', ',$protitle);             //replace ' ,' (space, comma) with ', '(comma, space);
		$protitle=str_replace('/','/ ',$protitle);              //replace '/' with '/ ' (slash, space)
		$protitle=str_replace('  ',' ',$protitle);              // replace '  ' (2 spaces) with ' ' (single space)
		$yourinfo="";
		for($c=3;$c<count($temp);$c++)
		{
			$yourinfo.=" ".$temp[$c];
		}

		//added by sriram.
		if($myrow['SHOW_HOROSCOPE']=="Y" && is_array($astro_array) && in_array($myrow["PROFILEID"],$astro_array))
		{
			$horo_link="horoscope_astro.php";
			$horoscope="Y";
			$horoscope_astro="";
			if(is_array($astro_details))
			{
				$apid=$myrow["PROFILEID"];
				if($astro_details[$apid])
				{
					$horoscope_astro=$astro_details[$apid];
				}
			}

		}
		else
			$horoscope="N";

		/*
		//Correcting the logic of horoscope icon on search/favourite/ignore/photo req page
		if($myrow['SHOW_HOROSCOPE']=='Y')
		{	
		if($myrow['COUNTRY_BIRTH']!='0' && $myrow['CITY_BIRTH']!='' && $myrow['BTIME']!='')
		{
		$horo_link="horoscope_astro.php";
		$horoscope="Y";
		}
		else
		$horoscope="N";
		}
		else
		$horoscope="N";
		 */

		//code ends for showin horoscope icon
		$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
		$photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);

		$username=$myrow["USERNAME"];

		/**********************************************************************************************
		  Changed By      :       Shakti Srivastava
		  Change Date     :       4 January, 2006
Reason          :       New element, STAT_UNAME has been added for static urls. 
		 ***********************************************************************************************/
		$sumProfileid=0;

		for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
		{
			$sumProfileid=$sumProfileid+$myrow["PROFILEID"]{$tempcnt};	//sum of profileid digits
		}
		$rotator=$sumProfileid%(strlen($myrow["PROFILEID"]));		//sum mod length of profileid rotator

		for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
		{
			$newpos=($tempcnt+$rotator)%strlen($myrow["PROFILEID"]);
			$rotatedProfileidArr[$newpos]=$myrow["PROFILEID"]{$tempcnt};	//rotated profileid
		}

		ksort($rotatedProfileidArr);

		if(count($rotatedProfileidArr)>1)
			$rotatedProfileid=implode("",$rotatedProfileidArr);
		else
			$rotatedProfileid=$rotatedProfileidArr[0];

		unset($rotatedProfileidArr);
		unset($sumProfileid);

		for($tempcnt=0;$tempcnt<strlen($myrow["USERNAME"]);$tempcnt++)
		{
			$asciiChr=ord($myrow["USERNAME"]{$tempcnt});

			if($asciiChr>=33 && $asciiChr<=126)
			{
				$stat_uname=$rotatedProfileid.$myrow["USERNAME"]{$tempcnt}.$rotator;
				break;
			}
		}
		unset($rotatedProfileid);
		unset($rotator);
		/***********************************************************************************************/

		/**********************************************************************************************
		  Changed By      :       Shakti Srivastava
		  Change Date     :       17 January, 2006
Reason          :       New element, HORO_LINK has been added for links on horoscope icon
		 ***********************************************************************************************/
		if(substr($myrow["SOURCE"],0,2)=='mb')
			$mb='Y';
		else
			$mb='';

		/* Added By lavesh for manageing icon of chat request on 11 may 2006*/
		if(($myrow["PHONE_MOB"]=='')||($myrow["GET_SMS"]=='N')||($myrow["COUNTRY_RES"]!='51'))
			$chat='';
		else
			$chat='Y';
		//Addition Ends Here


		//Added By lavesh(suggested By Gaurav) for reduicing size of search.htm on 29 sep 2006

		$age=$myrow["AGE"];
		$gothra=trim($myrow["GOTHRA"]);
		$my_income=$income_map["$income"];
		$gender=$myrow["GENDER"];
		$nakshatra=trim($myrow["NAKSHATRA"]);

		if($age) 
			$info="Age: <b>$age</b> ";
		if($height2)
			$info.="Height: <b>$height2</b> ";
		if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)
			$info.="Religion: <b>".$RELIGIONS[$myrow['RELIGION']]."</b> " ;
		if($myCaste)
			$info.="Caste: <b>$myCaste</b> ";
		if($subcaste)
			$info.="($subcaste) ";
		if($gothra && $gothra!="i don't know")
			$info.="Gothra: <b>$gothra</b> ";

		if($nakshatra && $nakshatra!="i don't know" && $nakshatra!="Don't Know")
			$info.="Nakshatra: <b>$nakshatra</b> ";

		if($mtongue)				
			$info.="Community: <b>$mtongue</b> ";
		//added by Gaurav on 3 Nov 2006
		if($edu_level)
			$info.="Education: <b>$edu_level</b> ";
		if($occupation)
			$info.="Occupation: <b>$occupation</b> ";

		/*if($occupation)
		  {
		  $info.="<b>Occupation:</b>:"; 
		  if($edu_level)
		  $info.="$edu_level,";
		  $info.="$occupation ";
		  }*/

		if($my_income)	
		{
			if($crmback)
				$info.="Annual Income: <b>$my_income</b> ";
			elseif($gender=='M')
				$info.="Annual Income: <b>$my_income</b> ";
		}
		if($residence)
			$info.="City: <b>$residence</b>";

		//2 change for photo
		$profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];
		if($havephoto=='L')
		{        
			if($gender=='M')
				$image_file="login_to_view_photo_sm_b.gif";
			else
				$image_file="login_to_view_photo_sm_g.gif";
		}		
		elseif($havephoto=='C')
		{        
			if($gender=='M')
				$image_file="photo_vis_if_con_acc_sm_b.gif";
			else
				$image_file="photo_vis_if_con_acc_sm_g.gif";
		}		
		elseif($havephoto=='F')
		{        
			if($gender=='M')
				$image_file="pro_fil_sm_b.gif";
			else
				$image_file="pro_fil_sm_g.gif";
		}		
		elseif($havephoto=='H')
		{        
			if($gender=='M')
				$image_file="photo_hidden_sm_b.gif";
			else
				$image_file="photo_hidden_sm_g.gif";
		}		
		elseif($havephoto=='P')
		{        
			if($gender=='M')
				$image_file="photo_fil_sm_b.gif";
			else
				$image_file="photo_fil_sm_g.gif";
		}		
		elseif($havephoto=='U')
		{        
			if($gender=='M')
				$image_file="ph_cmgsoon_sm_b.gif";
			else
				$image_file="ph_cmgsoon_sm_g.gif";
		}		
		elseif($havephoto=='N')
		{        
			if($gender=='M')
				$image_file="Request-a-photo-male_small.gif";
			else
				$image_file="Request-a-photo-Female_small.gif";
		}		
		if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
		{
			$my_photo="<a href=\"/profile/matrimonial-$stat_uname.htm\" onclick=\"MM_openProfileWindow('/profile/matrimonial-$stat_uname.htm','','');return false;\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
		}
		elseif($havephoto=='Y'){
			//Symfony Photo Modification
			$profilePicUrlArr = $profilePicUrls[$myrow["PROFILEID"]];                  
			if ($profilePicUrlArr)
			{
				$profilePicUrl = $profilePicUrlArr["ProfilePicUrl"];
				$searchPicUrl = $profilePicUrlArr["SearchPicUrl"];
				$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
			}
			else
			{
				$profilePicUrl = null;
				$searchPicUrl = null;
				$thumbnailUrl = null;
			}
			$my_photo="<a href=\"/profile/matrimonial-$stat_uname.htm\" onclick=\"MM_openProfileWindow('/profile/matrimonial-$stat_uname.htm','','');return false;\" onMouseover=\"showtrail('$photochecksum','$username','','','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail()\"><div style=\" float:left; width:60px; height:60px; margin:0 3px 3px 0; background-image:url($thumbnailUrl)\"><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div></a>";
			//Symfony Photo Modification
		}
		else
		{
			if($crmback)
				$my_photo="<a href=\"/profile/viewprofile.php?profilechecksum=$profilechecksum&crmback=admin&cid=$cid&inf_checksum=$inf_checksum\" target=\"_blank\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
			else
				$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum?CALL_ME=/social/photoRequest%3Fshowtemp%3DY%26other_username%3D$myrow[USERNAME]%26checksum%3D%26profilechecksum%3D$profilechecksum\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\" onclick=\"MM_openBrWindow('$SITE_URL/profile/photorequest.php?checksum=$checksum&profilechecksum=$profilechecksum','','width=370,height=330'); return false;\" ></a>";
		}

		// IVR - Phone verification status	
		$chk_phoneStatus =getPhoneStatus($myrow);
		if($chk_phoneStatus =='Y')
			$mob_verified='Y';
		else
			$mob_verified='N';
		if(($myrow['SHOWPHONE_MOB']=="Y" || $myrow['SHOWPHONE_RES']=="Y") && $mob_verified=="Y")
			$show_mob="Y";
		else
			$show_mob="N";
		if(!$cnt)
			$cnt=0;
		// IVR - Ends 

		//code addition by Tapan Arora to calculate days to respond left for awaiting response
		$days_to_respond=$days_to_respond_arr[$myrow['PROFILEID']];
		//CODE ENDED BY Tapan Arora

		unset($sub);
		$sub=array();
		if($myrow["SUBSCRIPTION"])
			$sub=explode(",",$myrow["SUBSCRIPTION"]);
		if($myrow['SOURCE']=='ofl_prof' || in_array("1",$sub))
		{
			$color='#FF0000';
			$days_to_respond=$days_to_respond-15;
		}
		else
			$color='';

		if(in_array("1",$sub) && $data["PROFILEID"]!='' && $data["SUBSCRIPTION"]!='')
		{
			$jpartnerObj->setPROFILEID($myrow["PROFILEID"]);
			$myDbName=getProfileDatabaseConnectionName($myrow["PROFILEID"]);
			$myDb=$mysqlObj->connect("$myDbName");
			$jpartnerObj->setPartnerDetails($myrow["PROFILEID"],$myDb,$mysqlObj);
			if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$myrow["PROFILEID"]))
				$contact_details=member_101_details_show($viewerdata,$jpartnerObj);	
			else
				$contact_details=1;
		}

		$smarty->assign("date_of_alert",$str_date_formated);

		$contact_layer_id = "contact_layer_".$myrow['PROFILEID'];
		$contact_details_on_layer = array("USERNAME" => $myrow["USERNAME"],
				"EMAIL" => $myrow["EMAIL"],
				"PHONE" => $myrow["PHONE_RES"],
				"SHOWPHONE" => $myrow["SHOWPHONE_RES"],
				"MOBILE" => $myrow["PHONE_MOB"],
				"SHOWMOBILE" => $myrow["SHOWPHONE_MOB"],
				"MESSENGER_ID" => $myrow["MESSENGER_ID"],
				"MESSENGER_CHANNEL" => $MESSENGER_CHANNEL["$myrow[MESSENGER_CHANNEL]"],
				"SHOWMESSENGER" => $myrow["SHOWMESSENGER"],
				"CONTACT" => $myrow["CONTACT"],
				"SHOWADDRESS" => $myrow["SHOWADDRESS"],
				"PARENTS_CONTACT" => $myrow["PARENTS_CONTACT"],
				"SHOW_PARENTS_CONTACT" => $myrow["SHOW_PARENTS_CONTACT"],
				"CONTACT_LAYER_ID" => $contact_layer_id,
				"CONTACT_DETAILS_VERIFIED" => $contact_details_verified
				);

		$smarty->assign("CONTACT_DETAILS_ON_LAYER",$contact_details_on_layer);
		$contact_layer_template = $smarty->fetch("contact_details_layer.htm");

		$RESULT_ARRAY[]=array("SNO" => $sno,
				"PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
				"PROFILEID" => $myrow["PROFILEID"],
				"PHOTOCHECKSUM" => $photochecksum,
				"USERNAME" => $myrow["USERNAME"],
				"INFO" => $info,
				"AGE" => $myrow["AGE"],
				"HEIGHT" => $height2,
				"CASTE" => $myCaste,
				"MTONGUE" =>$mtongue,
				"OCCUPATION" => $occupation,
				"RESIDENCE" => $residence,
				"YOURINFO" => $yourinfo,
				"MOD_DT" => $mod_date,
				"HAVEPHOTO" => $havephoto,
				"CONTACTSTATUS" => $contacted1[$myrow["PROFILEID"]],
				"SENDER_OR_RECEIVER" => $contacted2[$myrow["PROFILEID"]],//added by lavesh(S / R)
				"PHOTO_REQ" => $photo_req,//added by lavesh
				"BOOKMARKED" => $bookmarked,
				"BKNOTE"=>$bknote,
				"BKNOTEDIS"=>$bknotedis,
				"BOLDLISTING" => $bold_listing,
				//done for new service added called eclassified NEW CHANGES
				"CONTACT_DETAILS" => $contact_details,
				"ERISHTA_MEMBER" => $erishta_member,
				"HOROSCOPE" =>$horoscope,
				"HORO_LINK" =>$horo_link,
				"HOROSCOPE_ASTRO" =>$horoscope_astro,
				"ONLINE" => $online,
				"DEGREE" => $edu_level,
				"PROTITLE" => $protitle,
				"INCOME" => $income_map["$income"],
				"STAT_UNAME"=>$stat_uname,
				"LAST_LOGIN_DT"=>$myrow['LAST_LOGIN_DT'],
				"GENDER" => $myrow["GENDER"],
				"SUBCASTE" => $subcaste,
				"GOTHRA"=> $myrow["GOTHRA"],
				"NAKSHATRA"=>$myrow["NAKSHATRA"],
				"PHOTOCHECKSUM_NEW" => $photochecksum_new,
				"CHAT_REQUEST"=>$chat,
				"MY_PHOTO" =>$my_photo,
				"version"=>$version,	
				"IGNORE"=>$ignore,
				"THREADNAME"=>$data["USERNAME"]."_".$myrow["USERNAME"],
				"MARRIAGE_BUREAU"=>$mb,
				"VIEWED_BY_USER"=>$viewed_by_user,
				"SHOW_MOB"=>$show_mob,
				"COLOR"=>$color,
				"DAYS_TO_RESPOND"=>$days_to_respond,
				"CONTACT_LAYER_TEMPLATE"=>$contact_layer_template,
				"CONTACT_LAYER_ID"=>$contact_layer_id,
				"GTALK_ONLINE"=>$gtalk_online,
				"GTALK_STATUS"=>$gtalk_status,
				"YAHOO_ONLINE"=>$yahoo_online,
				"YAHOO_STATUS"=>$yahoo_status
					);
		$sno++;
		unset($days_to_respond);
	}
	mysql_free_result($result1);
	for($z=0;$z<count($RESULT_ARRAY_3d);$z++)
	{
		$key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$RESULT_ARRAY_3d);
		$RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
	}
	/*********************************************************************************************************************
	  Changed by	: Shakti Srivastava
	  Change Date	: 26 July 2005
Reason		: Number of tables that has to be shown is needed while displaying the data in the template
	 *********************************************************************************************************************/
	$temp=count($RESULT_ARRAY);
	//print_r($RESULT_ARRAY);
	$table_cnt=ceil($temp/3);	
	$smarty->assign("RESULTS_ARRAY_COUNT",$temp);

	$smarty->assign("table_cnt",$table_cnt);

	$smarty->assign("RECORDCOUNT",$totalrec);

	if($marriage_bureau_profile)
		$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY);
	else
		$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY_FINAL);

	if( $curcount )
		$cPage = ($curcount/$PAGELEN) + 1;
	else
		$cPage = 1;
	if($scriptname=='single_contact_aj.php')
		$PAGELEN = 6;
	if(!$js_search_recomend)
	{
		if($moreurl=="")
			//pagelink($PAGELEN,$totalrec,$cPage,10,$checksum,$scriptname,$searchchecksum,$newsearch);
			pagelink($PAGELEN,$totalrec,$cPage,10,$checksum,$scriptname,$searchchecksum,$newsearch,$inf_checksum,$castemapping,$crmback,$ordering,$savesearch,$label_select_no,$from_index,"","",$save_search_redirect,$seo_string);
		else 
			//pagelink($PAGELEN,$totalrec,$cPage,10,"$checksum&$moreurl",$scriptname,$searchchecksum,$newsearch);
			pagelink($PAGELEN,$totalrec,$cPage,10,"$checksum&$moreurl",$scriptname,$searchchecksum,$newsearch,$inf_checksum,$castemapping,$crmback,$ordering,$savesearch,$label_select_no,$from_index,"","",$save_search_redirect,$seo_string);
	}

	$smarty->assign("newsearch",$newsearch);
	$smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));
	$smarty->assign("CURPAGE",$cPage);
	$smarty->assign("BACK_TO_SEARCH_PAGE",$curcount);
	$smarty->assign("SCRIPTNAME",$scriptname);

	//Added By lavesh for new search templates 
	if ($from_index && !$data)
	{
		$profile_start=($cPage-1)*6+1;

		if($profile_start+5<$totalrec)
			$profile_end=$profile_start+5;
		else
			$profile_end=$totalrec;
	}
	elseif (($from_index && $data) || !$from_index)
	{
		$profile_start=($cPage-1)*12+1;

		if($profile_start+11<$totalrec)
			$profile_end=$profile_start+11;	
		else	
			$profile_end=$totalrec;
	}

	$smarty->assign("profile_start",$profile_start);
	$smarty->assign("profile_end",$profile_end);

	if($js_search_recomend)
	{
		$a=array('jsa1','jsa2','jsa3','jsa4','jsa5','jsa6');
		$b=array('jsb1','jsb2','jsb3','jsb4','jsb5','jsb6');
		$c=array('jsc1','jsc2','jsc3','jsc4','jsc5','jsc6');
		$e=array('e1','e2','e3','e4','e5','e6','e7','e8','e9','e10','e11','e12');
		$f=array('f1','f2','f3','f4','f5','f6','f7','f8','f9','f10','f11','f12');
		$n=array('n1','n2','n3','n4','n5','n6','n7','n8','n9','n10','n11','n12');
		$l=array('l1','l2','l3','l4','l5','l6','l7','l8','l9','l10','l11','l12');
		$t=array('t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12');
		$boxheight=array('jsone','jstwo','jsthree','jsfour','jsfive','jssix');
		$flag_for_js_recomend=1;
	}
	else
	{
		$a=array('a1','a2','a3','a4','a5','a6','a7','a8','a9','a10','a11','a12');
		$b=array('b1','b2','b3','b4','b5','b6','b7','b8','b9','b10','b11','b12');
		$c=array('c1','c2','c3','c4','c5','c6','c7','c8','c9','c10','c11','c12');
		$d=array('d1','d2','d3','d4','d5','d6','d7','d8','d9','d10','d11','d12');
		$e=array('e1','e2','e3','e4','e5','e6','e7','e8','e9','e10','e11','e12');
		$f=array('f1','f2','f3','f4','f5','f6','f7','f8','f9','f10','f11','f12');
		$n=array('n1','n2','n3','n4','n5','n6','n7','n8','n9','n10','n11','n12');
		$l=array('l1','l2','l3','l4','l5','l6','l7','l8','l9','l10','l11','l12');
		$t=array('t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12');
		$boxheight=array('one','two','three','four','five','six','seven','eight','nine','ten','eleven','tewelve');
	}
	if(!$flag_for_js_recomend)
	{
		$box1="a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12";
		$box2="b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12";
		$box3="c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12";
		$box4="one,two,three,four,five,six,seven,eight,nine,ten,eleven,tewelve";
	}
	else
	{
		$box1="jsa1,jsa2,jsa3,jsa4,jsa5,jsa6";
		$box2="jsb1,jsb2,jsb3,jsb4,jsb5,jsb6";
		$box3="jsc1,jsc2,jsc3,jsc4,jsc5,jsc6";
		$box4="jsone,jstwo,jsthree,jsfour,jsfive,jssix";
	}

	$smarty->assign("box4",$box4);
	$smarty->assign("box1",$box1);
	$smarty->assign("box3",$box3);
	$smarty->assign("box2",$box2);
	$smarty->assign("a",$a);
	$smarty->assign("b",$b);
	$smarty->assign("c",$c);
	$smarty->assign("d",$d);
	$smarty->assign("e",$e);
	$smarty->assign("f",$f);
	$smarty->assign("n",$n);
	$smarty->assign("l",$l);
	$smarty->assign("t",$t);
	$smarty->assign("boxheight",$boxheight);
	//Ends here

	//return $RESULT_ARRAY_FINAL;
	return "";
}
/*if (!function_exists('getMonthName'))
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
  }	*/
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
	//Sharding Concept added by Lavesh Rawat on table JPARTNER
	$sql="select * from FILTERS where PROFILEID='$hisprofileid'";
	$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	if(mysql_num_rows($resultfilter)>0)
	{
		$filterrow=mysql_fetch_array($resultfilter);							
		if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y"|| $filterrow["CITY_RES"]=="Y" || $filterrow["INCOME"]=="Y" || $filterrow["CASTE"]=="Y")
		{
			$sqlfilter="select count(*) from JPROFILE where  activatedKey=1 and PROFILEID='$myprofileid'";

			//Sharding Concept added by Lavesh Rawat on table JPARTNER	
			include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
			$jpartnerObj= new Jpartner;
			$mysqlObj=new Mysql;

			$myDbName=getProfileDatabaseConnectionName($hisprofileid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
			$jpartnerObj->setPartnerDetails($hisprofileid,$myDb,$mysqlObj,"LAGE,HAGE,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_MSTATUS,PARTNER_MTONGUE");
			if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$hisprofileid))
			{
				if($filterrow["AGE"]=="Y" && $jpartnerObj->getLAGE() && $jpartnerObj->getHAGE() )
					$sqlfilter.= " AND AGE between ".$jpartnerObj->getLAGE()." AND ".$jpartnerObj->getHAGE();

				if($filterrow["CASTE "]=="Y" && $jpartnerObj->getPARTNER_CASTE())
				{
					//	$caste_arr=explode("','",trim($jpartnerObj->getPARTNER_CASTE(),"'"));
					$sqlfilter.=" and CASTE in (".$jpartnerObj->getPARTNER_CASTE().")";
					// implode("','",get_all_caste($caste_arr)) . "')";
				}	
				if($filterrow["RELIGION "]=="Y" && $jpartnerObj->getPARTNER_RELIGION())
					$sqlfilter.=" AND RELIGION in (".$jpartnerObj->getPARTNER_RELIGION().")";

				if($filterrow["COUNTRY_RES"]=="Y" && $jpartnerObj->getPARTNER_COUNTRYRES())
					$sqlfilter.=" AND COUNTRY_RES in (".$jpartnerObj->getPARTNER_COUNTRYRES().")";

				if($filterrow["CITY_RES"]=="Y" && $jpartnerObj->getPARTNER_CITYRES())
					$sqlfilter.=" AND CITY_RES in (".$jpartnerObj->getPARTNER_CITYRES().")";

				if($filterrow["MSTATUS"]=="Y" && $jpartnerObj->getPARTNER_MSTATUS())
					$sqlfilter.=" and MSTATUS in (".$jpartnerObj->getPARTNER_MSTATUS().")";

				if($filterrow["MTONGUE"]=="Y" && $jpartnerObj->getPARTNER_MTONGUE())
					$sqlfilter.=" and MTONGUE in (".$jpartnerObj->getPARTNER_MTONGUE().")";

				if($filterrow["INCOME"]=="Y" && $jpartnerObj->getPARTNER_INCOME())
					$sqlfilter.=" and INCOME in (".$jpartnerObj->getPARTNER_INCOME().")";
			}
			$resfil=mysql_query_decide($sqlfilter) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlfilter,"ShowErrTemplate");
			$finalfilterrow=mysql_fetch_row($resfil);

			if($finalfilterrow[0] <= 0)
				return true;
		}
	}
	return false;
}

//Modified by anand on 27th dec 2013 as its not used.
function create_link_array($current_rank,$rank_id,$max_rank,$searchchecksum)
{
	global $_SERVER;
	mail('lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com','create_link_array() called in search.inc',$_SERVER);
}

//function added by lavesh to get the username from the profile id
function user_get_name($profileid)
{
	$sql="SELECT USERNAME from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		return $myrow["USERNAME"];
	}
	else
		return "";
}

//added by lavesh for Re-vamp .
//It specify parameters for layer.
function reload_on_layer($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage)
{
	global $smarty;
	if($redirect)
	{
		$smarty->assign('layer',1);
		if($action=='C')
		{
			if($nmessage_profileid)
			{
				$nmessage1=explode(',',$nmessage_profileid);
				for($i=0;$i<count($nmessage1);$i++)
				{
					$profilechecksum=md5($nmessage1[$i])."i".$nmessage1[$i];
					$receivers_with_history=user_get_name($nmessage1[$i]);
					$nmessage.="<a  href=\"#\" onClick=\"MM_openProfileWindow('/profile/viewprofile.php?profilechecksum=$profilechecksum','','')\">$receivers_with_history</a></strong>".' , ';
					$ncount++;
				}
				$nmessage=rtrim($nmessage,' , ');
				$smarty->assign("unfavourable_response",'Y');
				if($ncount==1)
					$smarty->assign('error_msg',"$error_msg");

				$smarty->assign("nmessage","Can not initiate contact with $nmessage");
			}
			if(trim($ymessage))
			{
				$smarty->assign("favourable_response",'Y');
				$smarty->assign("ymessage","You have successfully contacted $ymessage");
			}
		}
		elseif($action=='IG')
		{
			if(trim($ymessage))
			{
				$smarty->assign("favourable_response",'Y');
				if(strstr($ymessage,'ignored'))
					$smarty->assign("ymessage","$ymessage<br>Ignored profiles will be removed from your search after 24 hours");
				else
					$smarty->assign("ymessage","$ymessage");
			}
			if(trim($nmessage))
			{
				$smarty->assign("unfavourable_response",'Y');
				$smarty->assign("nmessage","$nmessage");
			}
		}
		else
		{
			if($ymessage)
			{
				$smarty->assign("favourable_response",'Y');
				$smarty->assign("ymessage","$ymessage");
			}
			if($nmessage)
			{
				$smarty->assign("unfavourable_response",'Y');
				$smarty->assign("nmessage","$nmessage");	
			}
		}
	}
}
//Added by lavesh on 2jan 2007
//This function will increment SEARCH_JS cookie each time search page is viewed.
//On 6-search page timeout function will ask user to login/register.
/*function login_for_6th_searchpage()
  {
  global $domain,$crmback;
  global $_COOKIE;

  if(!$_COOKIE['SEARCH_JS'])
  setcookie("SEARCH_JS","1",0,"/",$domain);//set cookie first time
  else if($_COOKIE['SEARCH_JS'])
  {
  $cookie_value=$_COOKIE['SEARCH_JS'];//value stored in cookie is string
  settype($cookie_value,"integer");//so we have to convert it to int
  if($cookie_value>=5)
  {
  setcookie("SEARCH_JS","6",0,"/",$domain);
  if(!isset($_COOKIE['JS_SOURCE']))
  {
  setcookie("JS_SOURCE","js_searchp",time()+2592000,"/",$domain);
//savehit("js_searchpage",$_SERVER['PHP_SELF']);
}
TimedOut();
exit;
}
else if($cookie_value<5)
{
if ($crmback!='admin')
{
$cookie_value=$cookie_value+1;
}
settype($cookie_value,"string");
setcookie("SEARCH_JS","$cookie_value",0,"/",$domain);//increment value
}
}
}*/

//Added by lavesh on 1 april 2007
function count_rows_of($sql,$CNT="")
{
	if($sql)
	{
		$result = mysql_query_decide($sql);
		if($CNT=="CNT")
		{
			$row=mysql_fetch_array($result);
			return($row["CNT"]);
		}
		else
		{
			$num_rows = mysql_num_rows($result);
			return($num_rows);
		}
	}
	else
		return(0);
}

//Added by lavesh to redefine search criteria.
function change_criteria($lage,$hage,$lheight,$hheight,$Gender)
{
	global $data,$domain;
	global $_COOKIE;

	$lage_org=$lage;
	$hage_org=$hage;
	$lheight_org=$lheight;
	$hheight_org=$hheight;

	if($data)
	{
		if(!$data["crmback"] && $_COOKIE['JS_AGE'] && $_COOKIE['JS_HEIGHT'])
		{
			$myage=$_COOKIE['JS_AGE'];
			settype($myage,"integer");
			$myheight=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE['JS_HEIGHT']);
			settype($myheight,"integer");
		}
		else
		{
			$pid=$data["PROFILEID"];
			$sql="SELECT AGE,HEIGHT FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
			$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);
			$myage=$row["AGE"];
			$myheight=$row["HEIGHT"];
		}
	}

	if($Gender=='F')
	{
		$lage=$lage-2;
		if($lage<18)
			$lage=18;

		if($myage>$hage || !$myage)
			$hage=$hage+1;
		if($hage>70)
			$hage=70;

		$lheight=$lheight-2;
		if($lheight<1)
			$lheight=1;

		if($myheight>$hheight || !$myheight)
			$hheight=$hheight+1;
	}
	elseif($Gender=='M')
	{
		if($lage>$myage ||!$myage)
		{
			$lage=$lage-1;
			if($lage<21)
				$lage=21;
		}

		$hage=$hage+2;
		if($hage>70)
			$hage=70;

		if($lheight>$myheight || !$myheight)
		{
			$lheight=$lheight-1;
			if($lheight<1)
				$lheight=1;
		}
		$hheight=$hheight+2;
	}

	if($hheight>37)
		$hheight=37;
	//Note: No limit in hheight is required.
	if($lage_org!=$lage || $hage_org!=$hage || $lheight_org!=$lheight || $hheight_org!=$hheight)
		$relaxed=1;
	else
		$relaxed=0;

	$parameter=array($lage,$hage,$lheight,$hheight,$relaxed);
	return ($parameter);
}

function select_random_simlogics($j,$contactedby="",$profileid_receiver="")
{
	if($j>0)
	{
		$sql="SELECT LOGIC FROM newjs.SIM_TEMP_RECORD WHERE SENDER='$contactedby'  AND RECEIVER='$profileid_receiver'ORDER BY ID DESC";
		$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		if($row["LOGIC"]=='NEW')
			return 1;
	}
	else
	{
		$sql="SELECT OLD_CNT-NEW_CNT AS LOGIC FROM newjs.RANDOM_SIM_LOGIC WHERE TO_DT=CURDATE()";
		$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$logic=$row["LOGIC"];
		if($logic>0)
			return 1;
	}
}

function update_random_simlogics($increment_variable="",$contactedby="",$profileid_receiver="",$logic="",$db1="")
{
	$sql="UPDATE newjs.RANDOM_SIM_LOGIC SET $increment_variable=$increment_variable+1 WHERE TO_DT=CURDATE()";
	if($db1)
		$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
	else
		$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
	if(mysql_affected_rows_js()==0)
	{
		if($increment_variable=="NEW_CNT")
			$sql="INSERT IGNORE INTO newjs.RANDOM_SIM_LOGIC VALUES('0','1',CURDATE())";
		else
			$sql="INSERT IGNORE INTO newjs.RANDOM_SIM_LOGIC VALUES('1','0',CURDATE())";
		if($db1)
			$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
		else
			$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
	}

	$sql="REPLACE INTO newjs.SIM_TEMP_RECORD VALUES('','$contactedby','$profileid_receiver','$logic')";
	if($db1)
		$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
	else
		$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
}
function calc_days_to_respond($data_3d)
{
	$curdate=date('Y-m-d');
	for($i=0;$i<count($data_3d);$i++)
	{
		$contact_date=substr($data_3d[$i]["TIME"],0,10);
		list($yy,$mm,$dd)=explode("-",$contact_date);
		$Time=mktime(0,0,0,$mm,$dd+30,$yy);
		$extended_date=date("Y-m-d",$Time);
		$days_to_left=getTime_Diff($curdate,$extended_date);
		$days_to_respond_arr[$data_3d[$i]["PROFILEID"]]=$days_to_left;
		unset($Time);
		unset($days_to_left);


	}
	return $days_to_respond_arr;
}
function revamp_get_other_relevant_pro($data,$contacted,$from="",$scriptname="",$limit="",$page="1",$newAlgo="hide")
{       $contactedby = $data["PROFILEID"];
        global $smarty,$remove_viewed_profile,$db;
	$remove_viewed_profile=1;
	if(!$limit)
		$limit=9;
	if ($data['PROFILEID']) {
                if ($scriptname == "AjaxContact.php" || $scriptname == "view_similar_profile.php")
                        $profileid_receiver = getProfileidFromChecksum($contacted);
                else {
                        $split = explode("i", $contacted);
                        $profileid_receiver = $split[1];
                }
                if ($newAlgo == "show") {
                        $viewer = $data['PROFILEID'];
                        $viewedGender = $data['GENDER'];
                        if ($viewedGender == 'M')
                                $viewedGender = 'FEMALE';
                        else
                                $viewedGender = 'MALE';
                        $viewed = $profileid_receiver;
                        $totalResult = $limit * 3;
                        $similarProfileids = getSimilarProfilesForLoggedInCase($viewer, $viewed, $viewedGender, $db, "EOI");
                        $countTotal = sizeof($similarProfileids);

                        //Number of page count based on 18 result per pae
                        $pageLimit = ceil($countTotal / $limit);
                        $smarty->assign("pageLimit", ($pageLimit + 1));
                        //Getting array of 18 from 54
                        if ($similarProfileids) {
                                $similarProfileids = "'" . implode("','", array_slice($similarProfileids, ($page - 1) * $limit, $limit)) . "',";
                                //Display Result
                                new_displayresults($similarProfileids, $start_from_blank, 10, 10, "view_similar_profile.php");
                        }
                } else {
                        $smarty->assign("contact", $profileid_receiver);
                        $new_logic_flag = 1;
                        include_once("simprofile_search_new.php");
                        if($new_logic_flag){
				return $res;
			}
                        $db = connect_db();
                }
        }
	unset($senders);

	if(!$new_logic_flag)
	{
		if($scriptname=="AjaxContact.php"|| $scriptname=="view_similar_profile.php")
			$profileid_receiver=getProfileidFromChecksum($contacted);
		else
		{
			$split=explode("i",$contacted);
			$profileid_receiver=$split[1];
		}
		$receiversIn=$profileid_receiver;
		$sendersNotIn=$contactedby;
		$contactResult=getResultSet("SENDER",'',$sendersNotIn,$receiversIn);
                if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
				$senders[]=$contactResult[$key]["SENDER"];
		}
		unset($contactResult);

		if(count($senders)>1)
		{
			$sender_list=implode(",",$senders);
		}
		else if(count($senders)==1)
		{
			$sender_list=$senders[0];
		}
		else
			$sender_list="''";

		unset($senders);

		$sql="SELECT VIEWED_PROFILE FROM newjs.SIM_PROFILE_LOG WHERE PROFILEID='$contactedby' union all select IGNORED_PROFILEID from IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' union all select PROFILEID from IGNORE_PROFILE WHERE IGNORED_PROFILEID='$contactedby' AND UPDATED='Y'";
		$res=mysql_query_decide($sql) or logError("Error while retrieving data from newjs.CONTACTS",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_row($res))                
		{
			$previous_rec.=$row[0] . ",";
		}
		$sendersIn=$contactedby;
		$contactResult=getResultSet("RECEIVER",$sendersIn);
                if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
				$previous_rec.=$contactResult[$key]["RECEIVER"].",";
		}
		unset($contactResult);

		$receiversIn=$contactedby;
		$contactResult=getResultSet("SENDER",'','',$receiversIn);
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
				$previous_rec.=$contactResult[$key]["SENDER"].",";
		}
		unset($contactResult);
		$previous_rec .= "'".$profileid_receiver."'";
		$previous_rec=rtrim($previous_rec,',');

                if($sender_list!="''")
		{
			$db2=connect_db4();
			$sql="SELECT RECEIVER AS PROFILEID , COUNT(*) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.")";
			if(trim($previous_rec)!="")
				$sql.=" AND RECEIVER NOT IN (".$previous_rec.")";

			$sql.=" GROUP BY RECEIVER ORDER BY CNT DESC LIMIT $limit";

			$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$sql,"ShowErrTemplate");
			$db=connect_db();

			if(mysql_num_rows($res)==0)
			{
				$db2=connect_db4();
				$sql="SELECT RECEIVER  AS PROFILEID ,COUNT(*) AS CNT FROM newjs.CONTACTS_SEARCH2 WHERE SENDER IN (".$sender_list.")";
				if(trim($previous_rec)!="")
					$sql.=" AND RECEIVER NOT IN (".$previous_rec.")";

				$sql.=" GROUP BY RECEIVER ORDER BY CNT DESC LIMIT $limit";

				$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH2",$sql,"ShowErrTemplate");
				$db=connect_db();
			}
			if(mysql_num_rows($res)>0)
			{
				$smarty->assign("STYPE","CO");
				updateSimTempLog($res,$db1,$contactedby);
				/*
				   while($myviews=mysql_fetch_array($res))
				   {
				   $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
				   mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
				   }
				 */
				mysql_data_seek($res,0);
				if($limit==9)
					set_results($res,"single_contact",9);
				else {
                                        if ($newAlgo == "hide") {
                                                return $res;
                                        }
                                        new_displayresults($res, $start_from, 10, 10, "view_similar_profile.php");
                                }
                        }
			else
			{	
				$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid_receiver."'";
				$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
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

				$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM ";
				if($gender=='M')
					$sql.=" newjs.SEARCH_MALE ";
				else if($gender=='F')
					$sql.=" newjs.SEARCH_FEMALE ";

				$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
				if(trim($previous_rec)!="")
					$sql.=" AND PROFILEID NOT IN (".$previous_rec.")";
				$sql.=" LIMIT $limit";
				$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");

				if(mysql_num_rows($res)>0)
				{
					updateSimTempLog($res,$db1,$contactedby);
					/*
					   while($myviews=mysql_fetch_array($res))
					   {
					   $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
					   mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
					   }
					 */
					mysql_data_seek($res,0);

					if($limit==9)
						set_results($res,"single_contact",9);
					else { 
                                        if ($newAlgo == "hide") {
                                                return $res;
                                        }
                                        new_displayresults($res, $start_from, 10, 10, "view_similar_profile.php");
                                        }
				}
				else 
				{
					log_no_similar_profiles($contactedby,$profileid_receiver);
				}
			}
		}
		else
		{
			$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid_receiver."'";
			$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			$age=$row['AGE'];
			$caste=$row['CASTE'];
			$gender=$row['GENDER'];
			$mtongue=$row['MTONGUE'];

			$lage=$age;
			$hage=$age;

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
			$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM ";

			if($gender=='M')
				$sql.=" newjs.SEARCH_MALE ";
			else
				$sql.=" newjs.SEARCH_FEMALE ";

			$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
			if(trim($previous_rec)!="")
				$sql.=" AND PROFILEID NOT IN (".$previous_rec.")";
			$sql.=" LIMIT $limit";
			$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");

			if(mysql_num_rows($res)>0)
			{
				updateSimTempLog($res,$db1,$contactedby);
				/* TESTED
				   while($myviews=mysql_fetch_array($res))
				   {
				   $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
				   mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
				   }
				 */
				mysql_data_seek($res,0);
				if($limit==9)
					set_results($res,"single_contact",9);
				else {
                                        if ($newAlgo == "hide") {
                                                return $res;
                                        }
                                        new_displayresults($res, $start_from, 10, 10, "view_similar_profile.php");
                                }//displayresults($res,"",$scriptname,"","","1","","","","");
			}
			else 
				log_no_similar_profiles($contactedby,$profileid_receiver);
		}
	}
}

function set_results($result,$page,$to_show)
{
	global $data,$OCCUPATION_DROP,$smarty,$CASTE_DROP_SMALL,$MTONGUE_DROP,$EDUCATION_LEVEL_NEW_DROP,$RELIGIONS,$HEIGHT_DROP,$MTONGUE_DROP_SMALL,$SITE_URL,$CITY_DROP,$income_map,$PHOTO_URL,$IMG_URL;
	$FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,MTONGUE,HAVEPHOTO,SCREENING,PHOTO_DISPLAY,PRIVACY,GENDER,PHOTOSCREEN,RELIGION,CASTE,OCCUPATION,INCOME,CITY_RES";
	while(($myrow=mysql_fetch_array($result)) && $i_tr<12)
	{
		$resultprofiles.="'" . $myrow["PROFILEID"] . "',";
	}
	$resultprofiles=substr($resultprofiles,0,strlen($resultprofiles)-1);

	//Added by Lavesh
	$RESULT_ARRAY_3d1=explode("','",$resultprofiles);
	for($k=0;$k<10;$k++)
	{
		$tmp=$RESULT_ARRAY_3d1[$k];
		if($tmp)
		{
			$RESULT_ARRAY_3d[]=trim($tmp,"',");
		}
	}
	//Added by Lavesh

	// get photo version details	
	if($resultprofiles)
	{
		//Symfony Photo Modification
		unset($profilePicUrls);
		$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ProfilePicUrl,ThumbailUrl,SearchPicUrl");
		//Symfony Photo Modification
	}

	$sql="select $FIELDS from newjs.JPROFILE where  activatedKey=1 and PROFILEID in($resultprofiles) limit 0,$to_show";
	$result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result1))
	{
		$religion=$RELIGIONS[$myrow['RELIGION']];
		$mtongue_temp=$myrow['MTONGUE'];
		//$mtongue1[0] = substr($MTONGUE_DROP_SMALL["$mtongue_temp"],0,12)."..";
		$mtongue1[0] = $MTONGUE_DROP_SMALL["$mtongue_temp"];;

		$heightn=$myrow["HEIGHT"];
		$height=$HEIGHT_DROP["$heightn"];
		$height1=explode("(",$height);
		$height2=trim($height1[0]);

		if($myrow["HAVEPHOTO"]=="U")
			$havephoto="U";
		elseif($myrow["HAVEPHOTO"]=="Y")
			$havephoto="Y";
		else
			$havephoto="N";
		if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
		{
			//if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
			//$havephoto="P";
			if($myrow["PHOTO_DISPLAY"]=="H")
				$havephoto="H";
			elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
				$havephoto="L";
			elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
				$havephoto="L";
		}

		$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
		$photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);
		$profilechecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
		$PROFILEID=$myrow['PROFILEID'];
		$username=$myrow['USERNAME'];
		$gender=$myrow['GENDER'];
		$stat_uname=stat_name($PROFILEID,$username);
		$age=$myrow["AGE"];
		//$image_file=return_image_file($havephoto,$gender);
		if($gender=='F')
			$image_file="ic_g_blank_60.gif";
		else
			$image_file="ic_b_blank_60.gif";

		if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
		{
			{
				//$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; float:left; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" ></div>";
				$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div>";
			}
		}
		elseif($havephoto=='Y'){
			//Symfony Photo Modification
			unset($profilePicUrlArr);
			$profilePicUrlArr = $profilePicUrls[$myrow["PROFILEID"]];                  
			if ($profilePicUrlArr)
			{
				$profilePicUrl = $profilePicUrlArr["ProfilePicUrl"];
				$searchPicUrl = $profilePicUrlArr["SearchPicUrl"];
				$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
			}
			else
			{
				$profilePicUrl = null;
				$searchPicUrl = null;
				$thumbnailUrl = null;
			}
			$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($thumbnailUrl) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div>";
			//Symfony Photo Modification
		}
		else
		{
			if($crmback)
			{
				//    $my_photo="<img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\">";
				$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div>";
			}
			else
			{
				//$my_photo="<div style=\" display:inline; margin:0 3px 3px 0;float:left; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\"></div>";
				$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div>";
			}

		}

		/*	$income_map=array("1" => "< Rs. 50K",
			"2" => "Rs. 50K - 1Lac",
			"3" => "Rs. 1 - 2Lac",
			"4" => "Rs. 2 - 3Lac",
			"5" => "Rs. 3 - 4Lac",
			"6" => "Rs. 4 - 5Lac",
			"7" => "> Rs. 5Lac",
			"8" => "< $ 25K",
			"9" => "$ 25 - 40K",
			"10" => "$ 40 - 60K",
			"11" => "$ 60 - 80K",
			"12" => "$ 80K - 1lac",
			"13" => "$ 1 - 1.5lac",
			"21" => "$ 1.5 - 2lac",
			"14"=>"> $ 2lac",
			"15"=>"No Income",
			"16"=>"Rs. 5 - 7.5lac",
			"17"=>"Rs. 7.5 - 10lac",
			"18"=>"Rs. 10 - 15lac",
			"20"=>"Rs. 15 - 20lac",
			"22"=>"Rs. 20 - 25lac",
			"23"=>"> Rs. 25lac");
		 */	

		$occ=$myrow["OCCUPATION"];
		$occupation=$OCCUPATION_DROP["$occ"];	
		$caste1=$myrow["CASTE"];
		$caste=str_replace("-","",$CASTE_DROP_SMALL["$caste1"]);
		$newCaste=explode(":",$caste);
		if(trim($newCaste[1])!="")
			$myCaste=$newCaste[1];
		else
			$myCaste=$newCaste[0];
		if($myrow["CITY_RES"]!="")
			$residence=$CITY_DROP[$myrow["CITY_RES"]];
		else
			$residence="";
		$income=$myrow["INCOME"];
		$small_tag="$myrow[AGE], $height2, $religion";
		$small_tag.="<BR> $mtongue1[0], $myCaste";
		if($sub_caste)
			$small_tag.="<BR> $sub_caste";
		if($occupation)
			$small_tag.="<BR> $occupation";
		if($income)
			$small_tag.=", ".$income_map["$income"];
		if($residence)
			$small_tag.="<BR> $residence";

		//Used for search , in order to provide security.
		$PROFILE_CHECKSUM=createChecksumForSearch($myrow["PROFILEID"]);
		$RESULT_ARRAY[]=array("SNO" => $sno,
				"PROFILE_CHECKSUM"=>$PROFILE_CHECKSUM,
				"PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
				"PROFILEID" => $myrow["PROFILEID"],
				"PHOTOCHECKSUM" => $photochecksum,
				"USERNAME" => $myrow["USERNAME"],
				"RELIGION" => $religion,
				"AGE" => $myrow["AGE"],
				"HEIGHT" => $height2,
				"MTONGUE" =>$mtongue1[0],
				"HAVEPHOTO" => $havephoto,
				"STAT_UNAME"=>$stat_uname,
				"SMALL_TAG"=>$small_tag,
				"PHOTOCHECKSUM_NEW" => $photochecksum_new,
				"MY_PHOTO" =>$my_photo,
				"COLOR"=>$color
				);
		$sno++;


	}
	//Added by Lavesh
	for($z=0;$z<count($RESULT_ARRAY_3d);$z++)
	{
		$key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$RESULT_ARRAY_3d);
		$RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
	}
	$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY_FINAL);
	//Added by Lavesh
	//$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY);
	$smarty->assign("length",$to_show);
}
?>
