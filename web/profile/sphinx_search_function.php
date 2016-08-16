<?php
include_once("thumb_identification_array.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsContactVerify.php");	
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once("similarProfilesConfig.php");
global $whichMachine;
//if($whichMachine=='test')
//include_once('/usr/local/sphinx1/api/sphinxapi.php');
//else
include_once('/usr/local/sphinx/api/sphinxapi.php');

function SphinxObj()
{
}


function set_draft($message)
{
        global $smarty,$data;

        //Replaces the message with particular draft.
        $overwrite=1;
        $pid=$data['PROFILEID'];
        if($pid)
        {
                $sql="select MESSAGE,DRAFTID,DRAFTNAME from newjs.DRAFTS where PROFILEID='$pid' and DECLINE_MES!='Y'";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $start=0;
                while($row=mysql_fetch_array($res))
                {
                        $DRAFT[$row[1]]=htmlspecialchars($row[2],ENT_QUOTES);
                        //No need to show replace option , since the message is already from draft.
                        if($row[0]==$message)
                                $overwrite=0;
                        $DRA_MES[$row[1]]=htmlspecialchars($row[0],ENT_QUOTES);
                        $start++;


                }
                if($start>=5 && $overwrite==1)
                {
                        $smarty->assign("DRA_MES_OPTION",$DRAFT);
                        $smarty->assign("OVERFLOW",1);
                }
                if($message=="")
                        $overwrite=0;
                if($overwrite)
                        $smarty->assign("SAVE_MESSAGE",1);
                $message=stripslashes($message);
		$smarty->assign("CUST_MESSAGE",htmlspecialchars($message,ENT_QUOTES));
        }
}
function stringArr_to_asciiArr($arr,$skipp_number="")
{
	if(is_array($arr))
	{
		foreach($arr as $v)
		{
			if($skipp_number)
				$newarr[]=ord(substr($v,-1));
			else
				$newarr[]=ord($v);
		}
	}
	elseif($arr)
		$newarr[]=ord($arr);
	return $newarr;
}

function sphinx_map($str,$caste,$mtongue,$flag='')
{
	if($str)
	{
		$tmpArr=explode(",",$str);
		foreach($tmpArr as $v)
		{
			$tmpArr2=explode("-",$v);
			$finalArr[]=trim($tmpArr2[0],"'")*1000+trim($tmpArr2[1],"'");
		}
	}
	if($flag)
		return $finalArr;

	if($caste)
	{
		if($mtongue)
		{
			//$finalArr[]=$caste[0]*1000+$mtongue[0];
			foreach($mtongue as $k=>$v)
				$finalArr[]=$caste[0]*1000+$mtongue[$k];
		}
		else
			for($i=0;$i<40;$i++)	
				$finalArr[]=$caste[0]*1000+$i;;
	}
	/*
	foreach($caste as $vc)
	{
		if($mtongue[0]=='All' || !$mtongue)
		{
			for($i=0;$i<40;$i++)
				$finalArr[]=$vc*1000+$i;
		}
		else
		{
			foreach($mtongue as $vm)
			{
				$finalArr[]=$vc*1000+$vm;
			}
		}
	}

	if($str)
	{
		$tmpArr=explode(",",$str);
		foreach($tmpArr as $v)
		{
			$tmpArr2=explode("-",$v);
			$finalArr[]=$tmpArr2[0]*1000+$tmpArr2[1];
		}
	}
	*/
	return $finalArr;
}

function updateCasteMtongueArray($Caste,$Mtongue)
{
	//To check whether user is login or not
	global $data;
	global $CASTE_DROP;
	global $MTONGUE_DROP_SMALL;
	//Profileid of the suer
	$profileid=$data['PROFILEID'];

	//Getting the Caste and mtongue of user , to get the suggestion
	if($profileid)
	{
		$sql_c_c="select CASTE,MTONGUE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result_c_c=mysql_query_decide($sql_c_c) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_c_c,"ShowErrTemplate");
		if($row_c_c=mysql_fetch_row($result_c_c))
		{
			$L_MTONGUE=$row_c_c[1];
			$L_CASTE=$row_c_c[0];

			//Mtongue search is not mandatory for login user
			$imp_mtongue='no';
		}
	}
	//Considering Searched Caste and Mtongue as of logout user
	else
	{
		//Getting the Caste that being searched.
		$searchCaste1=$Caste[0];

		$L_CASTE=$searchCaste1;
		
		//Getting the Mtongue being searched
		if(is_array($Mtongue))
			$L_MTONGUE=$Mtongue[0];

		//Mtongue search is  mandatory for logout user
		$img_mtongue='yes';
	}
	
	//Suggestion will not be shown untill both condition are true.
	if(!($img_mtongue=='yes' && $L_MTONGUE==''))
	{
		if(is_array($Caste))
			$searchCaste=$Caste[0];
	
		$lcaste=$searchCaste;

		if($L_CASTE==$lcaste)
		{
			$caste_community=$L_CASTE."-".$L_MTONGUE;
			$Mtongue1=$Mtongue[0];

			$lmtongue=intval($Mtongue1);
			if($L_MTONGUE!=$lmtongue)
			{
				if($lmtongue!="")
					$not_mtongue=1;
				else
					$caste_community.="NS";
			}
			if($not_mtongue!=1)
			{
			 	$mapsql="select MAP from newjs.CASTE_COMMUNITY_MAPPING where CASTE_COMMUNITY='$caste_community'";
				$mapresult=mysql_query_decide($mapsql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$mapsql,"ShowErrTemplate");
				if($maprow=mysql_fetch_row($mapresult))
				{
					//Removing all those caste who are child of selected caste.
					if(!strstr($caste_community,"NS"))
                                        {
                                                $all_caste=implode("-$lmtongue,",get_all_caste($L_CASTE));
                                                $all_caste=$all_caste."-".$lmtongue;
                                                $all_arr=explode(",",$all_caste);
                                                foreach($all_arr as $key=>$val)
                                                {                                               
                                                        $maprow[0]=str_replace("$val,","",$maprow[0]);
                                                        $maprow[0]=str_replace(",$val","",$maprow[0]);
                                                        $maprow[0]=str_replace("$val","",$maprow[0]);

                                                }
                                        }

					$map=implode("','",explode(",",$maprow[0]));
					$first=explode(",",$maprow[0]);
					foreach($first as $key=>$val)
					{
						$second=explode("-",$val);
						$CASTE[]=$second[0];
						$MTONGUE[]=$second[1];
					}
					for($i=0;$i<count($CASTE);$i++)
					{
						if($i==0)
							$sug_str=str_replace("-","",$CASTE_DROP[$CASTE[$i]])." ".$MTONGUE_DROP_SMALL[$MTONGUE[$i]];
						else
							$sug_str.=", ".str_replace("-","",$CASTE_DROP[$CASTE[$i]])." ".$MTONGUE_DROP_SMALL[$MTONGUE[$i]];
					}

					//Assigning Suggestion parameters to template.
					global $smarty;
					$smarty->assign("SUG_CASTE_MTONGUE",$sug_str);
					 	
				}
			}
		}	
	}
	return $map;	
		
}

function search($Gender='',$Religion='',$Caste='',$Mtongue='',$Lage='',$Hage='',
$WithPhoto='',$Manglik='',$MStatus='',$HaveChild='',
$LHeight='',$HHeight='',$BType='',$Complexion='',
$Diet='',$Smoke='',$Drink='',$Handicapped='',$Occupation='',
$Country_Res='',$City_Res='',$Edu_Level='',
$Edu_Level_New='',$Sort='',$Online='',$Income='',
$Relation='',$Nri='',$page='',$bread_crumb='',$original_sid='',$caste_mapping='',$force='',$searchid='',$STYPE="",$live_parents='',$sub_caste='',$horoscope='',$sampraday='',$speak_urdu='',$hijab_wife='',$mathab='',$amritdhari='',$cut_hair='',$wear_turban='',$zarathustri='',$wstatusArr='',$handicappedArr='',$nhandicappedArr='',$hiv='',$keywords='',$kwd_rule='and',$login='',$contact_visible='',$Subscription='',$incomeRangeArr='')
{
	global $_SERVER;
	mail('lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com','search() called in sphinx_search_function.php',$_SERVER);
}



//function new_displayresults($result,$start_from,$total_cnt,$max_results)
function new_displayresults($resultprofiles,$start_from,$total_cnt,$max_results,$scriptname='',$MORE_URL='')
{
/*
echo $resultprofiles;
echo "<br><br>";
echo $start_from;
echo "<br><br>";
echo $total_cnt;
echo "<br><br>";
echo $max_results;
echo "<br><br>";
*/
	global $data,$OCCUPATION_DROP,$smarty,$CASTE_DROP_SMALL,$MTONGUE_DROP_SMALL,$EDUCATION_LEVEL_NEW_DROP,$RELIGIONS,$HEIGHT_DROP,$Gender,$COUNTRY_DROP,$CITY_INDIA_DROP,$CITY_DROP,$income,$income_map,$from_similar,$db,$PHOTO_URL,$IMG_URL,$isMobile;
        if($total_cnt<1 && !$resultprofiles)
                return;
	$db=connect_db();
	if($data)
		include_once("express_interest.php");
	rightpanel();
	//Checking for phone verification 
	if($data[PROFILEID])
	{
		if(checkPhoneVerificationLayerCondition($data[PROFILEID]))
		{
			$smarty->assign('PH_UNVERIFIED_STATUS',1);
			$overall_cont=get_dup_overall_cnt($data[PROFILEID]);
			if($overall_cont>=$data[NOT_VALID_NUMBER_LIMIT])
				$smarty->assign('SHOW_UNVERIFIED_LAYER',1);
		}
	}
	$orig_scriptname=$scriptname;
	if(!$start_from)
		$start_from=1;

	//If coming from similar results.
	$from_similar=0;

	//Skipping pointer to it's correct place
	/*
	$skip_to=$start_from*10;
	$skip_to=$skip_to%120;
	@mysql_data_seek($result,$skip_to);
	*/
        if(($scriptname=="simprofile_search_new.php" || $scriptname=="simprofile_search.php" || $scriptname=="view_similar_profile.php"))
        {
		$from_similar=1;
                $i_tr=0;
                $skip_to=$start_from*9-9;
                $skip_to=$skip_to%90;
		
		if($scriptname=="simprofile_search.php" || $scriptname=="view_similar_profile.php")
			$flags=0;
		else
			$flags=1;
                		
				if(is_resource($resultprofiles))
				{
                @mysql_data_seek($resultprofiles,$skip_to);
		while(($myrow=mysql_fetch_array($resultprofiles)) && $i_tr<9)
                {
                        $resultprofilesnew.="'" . $myrow[$flags] . "',";
                        $i_tr++;
                }
		$resultprofiles=$resultprofilesnew;
		unset($resultprofilesnew);
				}
        }
      
//	if($_GET['seo_browse_matrimony'])
	{
		if(!$resultprofiles)
		{
			$total_cnt=0;
			return;
		}
	}
	//Added by Lavesh
	global $CURRENTPROFILEID,$suggAlgoNoOfResultsForEOI;
	if($CURRENTPROFILEID)
	{
		$resultprofiles = str_replace("'$CURRENTPROFILEID',","",$resultprofiles);
		$resultprofiles ="'".$CURRENTPROFILEID."',".$resultprofiles;
	}
	$RESULT_ARRAY_3d1=explode("','",$resultprofiles);
	
   
	for($k=0;$k<$suggAlgoNoOfResultsForEOI;$k++)
	{
		$tmp=$RESULT_ARRAY_3d1[$k];
		if($tmp)
		{
			$RESULT_ARRAY_3d[]=trim($tmp,"',");
			$actual_profiles[$k]=trim($tmp,"',");
		}
		if(count($RESULT_ARRAY_3d)>0)
		{
			$smarty->assign("SIM_TOTAL_RECORDS",1);
		}
	}
	$FIELDS="PROFILEID,SOURCE,USERNAME,AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,SPOUSE,SHOW_HOROSCOPE,GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,LAST_LOGIN_DT,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,RELIGION,SHOWPHONE_MOB,EMAIL, SHOWPHONE_RES, SHOWMESSENGER, CONTACT, SHOWADDRESS, PARENTS_CONTACT, SHOW_PARENTS_CONTACT,MSTATUS,PHOTOSCREEN,HIV,TIME_TO_CALL_START,TIME_TO_CALL_END,SORT_DT,SHOW_PARENTS_CONTACT , PARENTS_CONTACT , CONTACT , PINCODE , STD , SHOWADDRESS , PHONE_RES , PHONE_MOB , MESSENGER_ID , MESSENGER_CHANNEL,PHONE_NUMBER_OWNER , PHONE_OWNER_NAME , MOBILE_NUMBER_OWNER , MOBILE_OWNER_NAME , TIME_TO_CALL_START , TIME_TO_CALL_END,ISD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG";

	$resultprofiles=substr($resultprofiles,0,strlen($resultprofiles)-1);	
	$i_tr=0;
        $sql="select $FIELDS from newjs.JPROFILE where  activatedKey=1 and PROFILEID in($resultprofiles)";

//echo	$sqlLavesh="SELECT `PARTNER_CHILD`, `PARTNER_LAGE`, `PARTNER_HAGE`, `PARTNER_LHEIGHT`, `PARTNER_HHEIGHT`, `PARTNER_HANDICAPPED`, `PARTNER_BTYPE`, `PARTNER_CASTE`, `PARTNER_CITYRES`, `PARTNER_COMP`, `PARTNER_COUNTRYRES`, `PARTNER_DIET`, `PARTNER_DRINK`, `PARTNER_ELEVEL`, `PARTNER_ELEVEL_NEW`, `PARTNER_INCOME`, `PARTNER_MANGLIK`, `PARTNER_MSTATUS`, `PARTNER_MTONGUE`, `PARTNER_OCC`, `PARTNER_SMOKE`, `PARTNER_RELATION` FROM `SEARCH_FEMALE_REV` WHERE  PROFILEID in($resultprofiles)";
//echo	$sqlLavesh="SELECT `PROFILEID`, `PARTNER_LAGE`, `PARTNER_HAGE`, `PARTNER_LHEIGHT`, `PARTNER_HHEIGHT`,`PARTNER_CASTE`,`PARTNER_COUNTRYRES`,`PARTNER_MSTATUS`, `PARTNER_MTONGUE` FROM `SEARCH_FEMALE_REV` WHERE  PROFILEID in($resultprofiles)";

	$E_VALUE=array();
        $result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $i_tr=0;
        while(($myrow=mysql_fetch_array($result1)))
        {
		//Getting E-value data, used to show contact details.
		if(isEvalueMember($myrow['SUBSCRIPTION']))
			$E_VALUE[]=$myrow['PROFILEID'];

                if($myrow["PHONE_MOB"])
                         $mob_str.="'".$myrow["PHONE_MOB"]."',";
                //Saving all the information required for calculating trend.
	
		//this variable will be used in astro icons 
		$gender_for_icon = $myrow["GENDER"];
                $trend[$i_tr]=calculate_user_trend($myrow);
                $i_tr++;

        }
        unset($i_tr);

	//If evalue user is in the list of profiles-- filter, samegender, incomplete, under screening, logout
	$error_in_evalue_mem=check_for_all_condition($E_VALUE,$data,$gender_for_icon);
        if($data['PROFILEID'])
        {
                $trends_logic=getting_reverse_trend_in_search($trend);
                $smarty->assign("TRENDS_LOGIC",$trends_logic);

        }
        $mob_str=substr($mob_str,0,-1);

        // move the pointer of the recordset back to record 1
        @mysql_data_seek($result1,0);


	//if($data["PROFILEID"]!="" && mysql_num_rows($result)>0)
	if($data["PROFILEID"]!="" && $resultprofiles)
	{
		//Sharding On Contacts done by Lavesh Rawat
		$contactResult=getResultSet("RECEIVER,TYPE",$data["PROFILEID"],"",$resultprofiles,"","","","","","","Y","");

		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$contacted1[$contactResult[$key]["RECEIVER"]]=$contactResult[$key]["TYPE"];
				$contacted2[$contactResult[$key]["RECEIVER"]]="R";
			}
		}
		unset($contactResult);

		$contactResult=getResultSet("SENDER,TYPE,TIME",$resultprofiles,"",$data["PROFILEID"],"","","","","","","Y","");
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
		if($resultprofiles)
		{
			$incomplete=$data['INCOMPLETE'];;
			$activated=$data['ACTIVATED'];
			$temp_param=temporaryInterestSuccess($incomplete, $activated);
			if($temp_param)
			{
				$temp_prof=str_replace("'","",$resultprofiles);
				$temp_prof_arr=explode(",",$temp_prof);
				$tempCnt = ifTempContactExists($data["PROFILEID"],$temp_prof_arr);
				if(is_array($tempCnt))
				{
					foreach($tempCnt as $key=>$val)
					{
						$contacted1[$key]['SENDER']='I';
						$contacted2[$key]['SENDER']='S';
					}
				}
			}
		}
		unset($contactResult);
		//Sharding On Contacts done by Lavesh Rawat


		//code added by Tapan Arora to calculate days to Respond

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
		//Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST

		//ends here.
	}
	// do this in logged out case also
	//if(mysql_num_rows($result)>0)
		$resultprofiles = implode(",",array_diff(explode(",",$resultprofiles), [0]));
	if($resultprofiles)
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
                                        if($gender_for_icon == 'M')
                                                $gender_value = 1;
                                        else
                                                $gender_value = 2;
                                }
                                else
                                        $gender_value = "2";

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
		
		$sql_ast_det="SELECT PROFILEID FROM newjs.HOROSCOPE WHERE PROFILEID IN($resultprofiles)";
		$res_ast_det=mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ast_det,"ShowErrTemplate");
		while($row_ast_det=mysql_fetch_array($res_ast_det))
		{
			$search_horo_arr[$row_ast_det["PROFILEID"]]='Y';
		}

		mysql_free_result($result_astro);
	}
	if($resultprofiles)
	{	
		//Symfony Photo Modification
		$prof_alb=SymfonyPictureFunctions::checkMorePhotosMultipleIds($resultprofiles);
		$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ProfilePicUrl,ThumbailUrl,SearchPicUrl");
		//Symfony Photo Modification
	
		if($data)
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
		//$db=connect_db();
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
		$gmail_data=gtalk_users($resultprofiles);//uncommented by manoranjan for gatlk online user
		if(is_array($gmail_data))
		{
			$gtalk_onlinemembers=$gmail_data[0];
			$gtalk_status_arr=$gmail_data[1];
		}
		unset($gmail_data);

		//Getting data of yahoo online users.
		//$yahoo_data=yahoo_users($resultprofiles);
		if(is_array($yahoo_data))
		{
			$yahoo_onlinemembers=$yahoo_data[0];
			$yahoo_status_arr=$yahoo_dta[1];
		}
		unset($yahoo_data);

		/*  IVR - Callnow 
		 * CallSettings to access callnow feature
		 * Call now feature is enabled. */
                global $CALL_NOW;
                $callnowAccess_Arr=array();
                if($CALL_NOW){
                        //$my_rights=explode(",",$data["SUBSCRIPTION"]);
                        //if(in_array("F",$my_rights))
			$callnowAccess_Arr = callAccess($resultprofiles);
                }
	}

	$sno=0;
	while($myrow=mysql_fetch_array($result1))
	{
		$income=$myrow["INCOME"];
                $occ=$myrow["OCCUPATION"];	
		$occupation=$OCCUPATION_DROP["$occ"];
		$caste1=$myrow["CASTE"];
		$caste=str_replace("-","",$CASTE_DROP_SMALL["$caste1"]);
		$mtongue_temp=$myrow['MTONGUE'];
		$mtongue1[0] = $MTONGUE_DROP_SMALL["$mtongue_temp"];
		$mtongue = $mtongue1[0];
		$edu_temp=$myrow['EDU_LEVEL_NEW'];
		$edu_leveln[0]=$EDUCATION_LEVEL_NEW_DROP["$edu_temp"];
		$education=$edu_leveln[0];
		$screening=$myrow["SCREENING"];
		$religion=$RELIGIONS[$myrow['RELIGION']];
	
		if(isFlagSet("SUBCASTE",$screening))
			$subcaste=trim($myrow["SUBCASTE"]);
		else
			$subcaste="";
		if(isFlagSet("YOURINFO",$screening))
			$yourinfo=$myrow["YOURINFO"];
		else
			$yourinfo="";

		if(isFlagSet("FAMILYINFO",$screening))
			$familyinfo=$myrow["FAMILYINFO"];
		else
			$familyinfo="";

		if(isFlagSet("SPOUSE",$screening))
			$spouseinfo=$myrow["SPOUSE"];
		else
			$spouseinfo="";
		if($from_similar)
			$length_limit=500;
		else
			$length_limit=247;
		$yourinfo=substr($yourinfo . " " . $familyinfo . " " . $spouseinfo,0,$length_limit)."...";
		//$yourinfo=str_replace('0','',$yourinfo);
		$yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
		$yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
		$yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
		$yourinfo=str_replace('  ',' ',$yourinfo);              // replace '  ' (2 spaces) with ' ' (single space)

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
			$city=$myrow["CITY_RES"];
			$residence=$CITY_INDIA_DROP["$city"];
			if(!$residence)
				$residence=$CITY_DROP["$city"];
		/*	$sql_city="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE='$myrow[CITY_RES]'";
			$res_city=mysql_query_decide($sql_city) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_city,"ShowErrTemplate");
			$row_city=mysql_fetch_array($res_city);
			$residence=$row_city[0];*/

		}
		else
			$residence=$COUNTRY_DROP[$myrow["COUNTRY_RES"]];
			

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

		$membership_image='';		 
		if(in_array("D",$subscription)) //&& !in_array("S",$subscription))
		{
			$membership_image="evalue12.gif";
			$contact_details=1;
		}
		else
			$contact_details=0;

		if(in_array("F",$subscription) && !in_array("D",$subscription))
		{
			$membership_image="logo_erishta_1.gif";
			$erishta_member = 1;
		}
		else
			$erishta_member = 0;


		if(in_array("D",$subscription) && in_array("S",$subscription))
			$contact_details_verified = 0;
		else
			$contact_details_verified = 1;

		unset($member_101);
                if(in_array("1",$subscription))
                        $member_101=1;
                else
                        $member_101=0;

		if($myrow["HAVEPHOTO"]=="U")
			$havephoto="U";
		elseif($myrow["HAVEPHOTO"]=="Y")
			$havephoto="Y";
		else
			$havephoto="N";

		if(is_array($bookmarks))
		{
			$booked_array=return_note($bookmarks,$myrow["PROFILEID"]);
			$bookmarked=$booked_array[0];
                        $bknote=$booked_array[1];
                        $bknotedis=$booked_array[2];
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
		if(is_array($viewed_by_user_arr) && in_array($myrow["PROFILEID"],$viewed_by_user_arr))
			$viewed_by_user='yes';
		else
			$viewed_by_user='no';

		if(is_array($onlinemembers) && in_array($myrow["PROFILEID"],$onlinemembers))
			$online=1;
		else
			$online=0;
                        //ends here.

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
				if(check_privacy_filtered1($data["PROFILEID"],$myrow["PROFILEID"]))
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
		$is_album=0;
		if($havephoto=='Y')
		{
			//Symfony Photo Modification
                    	$is_album = $prof_alb[$myrow['PROFILEID']];
		}
		else
			$is_album=0;
		//added by sriram.
		 if($myrow['SHOW_HOROSCOPE']=="Y" && ((is_array($astro_array) && in_array($myrow["PROFILEID"],$astro_array)) || ($search_horo_arr[$myrow["PROFILEID"]]=='Y')))
                {
                        $horo_link="horoscope_astro.php";
                        $horoscope="Y";
                        $horoscope_astro="";
                        if(is_array($astro_details)  && in_array($myrow["PROFILEID"],$astro_array))
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

		//code ends for showin horoscope icon
   
		$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
		$photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);


		$PROFILEID=$myrow['PROFILEID'];
		$username=$myrow['USERNAME'];
		$stat_uname=stat_name($PROFILEID,$username);
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
		$age=$myrow["AGE"];
		$gothra=trim($myrow["GOTHRA"]);
		$my_income=$income_map["$income"];
		$gender=$myrow["GENDER"];
		$nakshatra=trim($myrow["NAKSHATRA"]);
		$profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];
		$big_photo="";
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
		//Symfony Photo Modification
		
		if($from_similar)
		{
			//Symfony Photo Modification
			$image_file=return_image_file_small($havephoto,$gender);
			if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
			{
				if($havephoto=='L'){
					$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; \"><a class='thickbox' href='$SITE_URL/profile/login.php?SHOW_LOGIN_WINDOW=1&WIDTH=700' onMousemove=\"showtrail2('$photochecksum','$username','event','','','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\"  ></a></div>";		
				}	
				else{
					$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; \"><a href='#' onclick='return false' onMousemove=\"showtrail2('$photochecksum','$username','event','','','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\" ></a></div>";				
				}
			}
			elseif($havephoto=='Y'){
				$my_photo="<a class='thickbox' href=\"$SITE_URL/profile/layer_photocheck.php?checksum=$checksum&profilechecksum=$profilechecksum&seq=1\"  onMousemove=\"showtrail2('$photochecksum','$username','event','','','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><div style=\" cursor:pointer;float:left; margin:0 3px 3px 0; background-image:url($thumbnailUrl)\" align='left'  oncontextmenu=\"return false;\"><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div></a>";
			}
			else
			{
				$my_photo="<a class=\"thickbox\" href=\"$SITE_URL/social/photoRequest?showtemp=Y&other_username=$username&checksum=$checksum&profilechecksum=$profilechecksum\" onMousemove=\"showtrail2('$photochecksum','$username','event','','','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\"  ></div></a>"; 
			}
			$big_photo=$profilePicUrl;	
			//Symfony Photo Modification
			
			
			$big_photo_temp=get_big_image($havephoto,$gender);
			if($big_photo_temp)
				$big_photo=$big_photo_temp;
		}
		else
		{
			if($isMobile){
				$pic_cnt++;
				$image_file=return_image_file_mobile($havephoto,$gender);
			}
			else
				$image_file=return_image_file($havephoto,$gender);
			//Symfony Photo Modification
			if($isMobile)
				$profile_ph="<a href=\"$SITE_URL/profile/layer_photocheck.php?checksum=$checksum&profilechecksum=$profilechecksum&seq=1&nav_type=SR\" id=\"pic_url$pic_cnt\" ><div style=\" float:left; margin:0 3px 3px 0; background-image:url($thumbnailUrl)\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"57\" border=\"0\" ></div></a>";
			else
				$profile_ph="<a class='thickbox' href=\"$SITE_URL/profile/layer_photocheck.php?checksum=$checksum&profilechecksum=$profilechecksum&seq=1\" ><div style=\" cursor:pointer;float:left; margin:0 3px 3px 0; background-image:url($searchPicUrl)\" align='left'  oncontextmenu=\"return false;\" galleryimg=\"NO\"><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
			if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
			{
				if($havephoto=='L'){
					if($isMobile)
						$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; align:left;\"><a class='thickbox' href='$SITE_URL/jsmb/login_home.php'><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\" ></a></div>";
					else
						$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; \"><a class='thickbox' href='$SITE_URL/profile/login.php?SHOW_LOGIN_WINDOW=1&WIDTH=700'><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\" ></a></div>";
				}
				else{
					if($isMobile)
						$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; align:left;\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\" ></div>";
					else
						$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\" ></div>";
				}
			}
			elseif($havephoto=='Y')
				$my_photo=$profile_ph;
			else
			{
				if($crmback){
					$my_photo="<a href=\"/profile/viewprofile.php?profilechecksum=$profilechecksum&crmback=admin&cid=$cid&inf_checksum=$inf_checksum\" target=\"_blank\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
				}
				else
				{
					if($isMobile)
						$my_photo="<div style=\" display:inline; margin:0 3px 3px 0; align:left;\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\"  ></div></a>";
					else
						$my_photo="<a class=\"thickbox\" href=\"$SITE_URL/social/photoRequest?showtemp=Y&other_username=$username&checksum=$checksum&profilechecksum=$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\"  vspace=\"0\" border=\"0\"  ></div></a>";
				}
			}

		}
			// IVR- Verification, Code added to check whether any Phone No. of a particular profile is Verified or not.
			//if(in_array($myrow['PHONE_MOB'],$mobarr) || in_array($myrow['PROFILEID'],$pidsArrValidPhone))
			$chk_phoneStatus = getPhoneStatus($myrow);	
			if($chk_phoneStatus=='Y')
                                $phone_verified='Y';
                        else
                                $phone_verified='N';
                        if(($myrow['SHOWPHONE_MOB']=="Y" || $myrow['SHOWPHONE_RES']=="Y") && $phone_verified=="Y")
                                $show_phone="Y";
                        else
                                $show_phone="N";


                //Information that will come adjacent to image
                $small_tag="$myrow[AGE], $height2, $religion";
                $small_tag.="<BR> $mtongue,<BR>$myCaste";
                if($subcaste && isFlagSet("SUBCASTE",$screening))
                        if($from_similar)
				$small_tag.=" (".wordwrap($subcaste,15,"<BR>",true).")";
			else
				$small_tag.=" ($subcaste)";
		$small_tag.=",<BR>";
		if($nakshatra!="Don't Know" && $nakshatra!="" && $nakshatra!="Select")
		{
			if($from_similar)
				$nakshatra=wordwrap($nakshatra,10,"<BR>",true);
		
			$small_tag.=$nakshatra."<span class=\"no_b\"> (Nakshatra),<BR></span>";
		}
		if($gothra && isFlagSet("GOTHRA",$screening))
			$small_tag.=$gothra."<span class=\"no_b\"> (Gothra),<BR></span>";
		if($education)
			$small_tag.="$education, ";
		if($income)
			$small_tag.=$income_map["$income"].", " ;
		if(!$nakshatra && !$gothra && !$income)
		{
			if($occupation)
				$small_tag.=" $occupation";
		}
		else
		if($occupation)
                        $small_tag.="<BR> $occupation";

                if($residence)
                        $small_tag.=" in $residence";

//$small_tag="";

		//$small_tag="24, 5' 2\", Hindu Sindhi (dawani) <BR>Sindhi ";
		//$yourinfo="he is a down to earth girl with high moral values, very polite and a responsible girl... she is very sweet and social, gives importance to family values.. I am having one sister doing B.PHARMACY from B.I.T.MESRA,";
//	echo $small_tag;die;	

                //Setting according to boldlisting.
                if($bold_listing)
                {
                        $top_left="pd_top_left.gif";
                        $top_bg="pd_top_bg";
                        $top_right="pd_top_right.gif";
                        $top_border="blue_border_bg";
                        $bottom_bg="pd_bottom_bg";
                        $bottom_right="pd_bottom_right.gif";
                        $bottom_left="pd_bottom_left.gif";
                        $width="440px";
                        $margin="6px";

                }
                else
                {
                        $top_left="sr_top_left.gif";
                        $top_bg="sr_top_bg";
                        $top_right="sr_top_right.gif";
                        $top_border="orange_border";
                        $bottom_bg="sr_bottom_bg";
                        $bottom_right="sr_bottom_right.gif";
                        $bottom_left="sr_bottom_left.gif";
                        $width="450px";
                        $margin="3px";
                }

                //Used for search , in order to provide security.
                $PROFILE_CHECKSUM=createChecksumForSearch($myrow["PROFILEID"]);

		if($chk_phoneStatus=='I')
			$inv_no=1;
		else
			$inv_no=0;	
		/*
                if(is_array($INV_PROF))
                {
                        if(in_array($myrow['PROFILEID'],$INV_PROF))
                                $inv_no=1;
                        else
                                $inv_no=0;
                }
		*/

                //Settig offline profile status.
                $offline_profile=0;

                if(strtolower($myrow['SOURCE'])=='ofl_prof' || strstr($myrow['SUBSCRIPTION'],"T"))
                        $offline_profile=1;

                //calculating sno_actual is necessary , because profileids order can be of different from actual profileids order
                $sno_actual=array_search($myrow['PROFILEID'],$actual_profiles);
		
		$Hiv=$myrow['HIV'];
		$icons_table=set_icons($inv_no,$bookmarked,$ignore,$phone_verified,$membership_image,$horoscope,$viewed_by_user,$Hiv,$landline,$sno_actual,$myrow['PROFILEID'],$horoscope_astro);

		/* IVR - Callnow 
		 * functionality in search result page to access callnow feature 
		 * feature accessible in loggedin case only 
		*/
		$call_access = $callnowAccess_Arr[$myrow['PROFILEID']];
		$myrow["CALL_ANONYMOUS"] = $call_access;
		$myrow["CALLNOW_CHECKED"] = true;
		/* Ends IVR-Callnow functionality */

		//Creating contact gadget only for evalue users.
		$cnt_gadget="";
		if(in_array($myrow['PROFILEID'],$E_VALUE))
			$cnt_gadget=set_contact_gadget($error_in_evalue_mem,$myrow);
		$cnt_gadget=str_replace("margin:9px; padding:4px;","margin:0;padding:5px;",$cnt_gadget);
		$cnt_gadget=str_replace("margin:9px; padding:4px;","margin:0;padding:5px;",$cnt_gadget);
		
		$jeevan_seal=0;
                //Getting jeevansathi seal status of user.
                if(is_array($VIS_SEAL))
			if($VIS_SEAL[$myrow['PROFILEID']])
				$jeevan_seal=1;


                //------archive-----
                $activeProfilesArr[]=$myrow["PROFILEID"];
                //------archive-----


                 $RESULT_ARRAY[]=array("SNO" => $sno,
						"BIG_PHOTO"=>$big_photo,
						"HOROSCOPE_ASTRO"=>$horoscope_astro,
                                                "ISALBUM"=>$is_album,
                                                "TOP_LEFT"=>$top_left,
                                                "TOP_BG"=>$top_bg,
                                                "TOP_RIGHT"=>$top_right,
                                                "TOP_BORDER"=>$top_border,
                                                "BOTTOM_BG"=>$bottom_bg,
                                                "BOTTOM_RIGHT"=>$bottom_right,
                                                "BOTTOM_LEFT"=>$bottom_left,
                                                "WIDTH"=>$width,
                                                "MARGIN"=>$margin,
                                                "SMALL_TAG"=>$small_tag,
                                                "MEMBERSHIP_IMAGE"=>$membership_image,
                                                "PROFILE_CHECKSUM"=>$PROFILE_CHECKSUM,
                                                "PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
                                                "PROFILEID" => $myrow["PROFILEID"],
                                                "PHOTOCHECKSUM" => $photochecksum,
						//"USERNAME" => $myrow["USERNAME"]."(".$myrow["PROFILEID"].")"."(".$myrow["SORT_DT"].")",//TEMP
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
                                                "IGNORE"=>$ignore,
                                                "THREADNAME"=>$data["USERNAME"]."_".$myrow["USERNAME"],
                                                "MARRIAGE_BUREAU"=>$mb,
                                                "VIEWED_BY_USER"=>$viewed_by_user,
                                                "SHOW_MOB"=>$show_phone,
                                                "COLOR"=>$color,
                                                "DAYS_TO_RESPOND"=>$days_to_respond,
                                                "CONTACT_LAYER_TEMPLATE"=>$contact_layer_template,
                                                "CONTACT_LAYER_ID"=>$contact_layer_id,
                                                "GTALK_ONLINE"=>$gtalk_online,
                                                "GTALK_STATUS"=>$gtalk_status,
                                                "YAHOO_ONLINE"=>$yahoo_online,
                                                "YAHOO_STATUS"=>$yahoo_status,
                                                "icons_table" =>$icons_table,
                                                "SEAL"=>$jeevan_seal,
						"OFFLINE_PROFILE"=>$offline_profile,
                                                "MEMBER_101"=>$member_101,
						"CALL_ANONYMOUS"=>$myrow["CALL_ANONYMOUS"],
						"CALLNOW_CHECKED"=>$myrow["CALLNOW_CHECKED"],
						"CNT_GADGET"=>$cnt_gadget
                                                );
		$sno++;
		unset($days_to_respond);
	}
	$smarty->assign("afs_adpage",$start_from);
	$kwd=explode("=",$MORE_URL);
	pagination($start_from,$total_cnt,10,$MORE_URL);
	$smarty->assign("CUR_PAGE",$_SERVER['PHP_SELF']);

        //------archive-----
        foreach($RESULT_ARRAY_3d as $k=>$v)
        	if(is_array($activeProfilesArr) && in_array($v,$activeProfilesArr))
                        $RESULT_ARRAY_3dnew[]=$v;
        //------archive-----

        //Added by Lavesh
        for($z=0;$z<count($RESULT_ARRAY_3dnew);$z++)
        {
                $key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$RESULT_ARRAY_3dnew);
                $RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
        }
        //Added by Lavesh       
	$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY_FINAL);
	$smarty->assign("length",10);

}
function seal_status($profileidin)
{
        //Need to get the table for time being passing false;
        //if($profileid%4==2)
        //        return true;
        //else
	if(!$profileidin)
		return false;
}
//This function will set the icons , this function will used globally.
function set_icons($INV_MOB='',$BOOKMARKED='',$IGNORE='',$SHOW_MOB='',$MEMBERSHIP_IMAGE='',$HOROSCOPE='',$VIEWED_BY_USER='',$HIV='',$LANDLINE='',$sno=0,$profileid='',$horoscope_astro='')
{
        global $smarty;
	
	//Declare this variable in main script.
	global $initial_cnt;

        $smarty->assign("FAV_ICON",0);
        $smarty->assign("IGN_ICON",0);
        $smarty->assign("MOB_ICON",0);
        $smarty->assign("MEMBER_ICON",0);
        $smarty->assign("HOR_ICON",0);
        $smarty->assign("HIV_ICON",0);
        $smarty->assign("LAND_ICON",0);
	$smarty->assign("INV_PH",0);
        $smarty->assign("FAV_ID",$sno);
	$smarty->assign("IC_PROFILEID",$profileid);
        if($BOOKMARKED)
        {
                $smarty->assign("FAV_ICON",1);
        }
        if($IGNORE)
        {
                $smarty->assign("IGN_ICON",1);
        }
        if($SHOW_MOB=='Y')
        {
                $smarty->assign("MOB_ICON",1);
        }
        if($INV_MOB)
                $smarty->assign("INV_PH",1);

        if($MEMBERSHIP_IMAGE)
        {
                $smarty->assign("MEMBER_ICON",1);
                $smarty->assign("MEMBERSHIP_IMAGE",$MEMBERSHIP_IMAGE);

        }
        if($HOROSCOPE=='Y')
        {
                $smarty->assign("HOR_ICON",1);
		$smarty->assign("HOROSCOPE_ASTRO",$horoscope_astro);
        }

        if($HIV=='Y')
        {
                $smarty->assign("HIV_ICON",1);
        }
        if($LANDLINE)
        {
                $smarty->assign("LAND_ICON",1);
        }

        $icons_data=$smarty->fetch("icons_table.htm");
	$initial_cnt++;
        return $icons_data;
}

function pagination($whereis,$total_record,$results_to_show,$MORE_URL='')
{
	global $smarty,$PAGELEN;
	if(!$PAGELEN) $PAGELEN=10;
	
	if($total_record >0)
	{
		$show=$total_record%$PAGELEN;


		if(!$show)
		{
			//$pages=1;
			$show=$PAGELEN;
		}
		//else
			//$pages=1;

		//$pages+=intval($total_record/10);
		$pages=ceil($total_record/$PAGELEN);
		if($whereis==1)
			$prev=0;
		else
			$prev=$whereis-1;
		
		if($pages==$whereis)
			$next=0;
		else
			$next=$whereis+1;
	
		pagesarr($whereis,$pages);
		/*	
		for($i=1;$i<=$pages;$i++)
		{
			$total_pages[$i]=$i;
		}
		$smarty->assign("total_pages",$total_pages);
		$smarty->assign("total_pages",0);
		*/
		$smarty->assign("whereis",$whereis);
		$smarty->assign("next",$next);
		$smarty->assign("prev",$prev);
		$smarty->assign("pages",$pages);
		$smarty->assign("MORE_URL",$MORE_URL);
	
	}
}
function return_note($bookmarks,$profileid)
{
	$pid=$profileid;
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
	$bookmark_arr[0]=$bookmarked;
	$bookmark_arr[1]=$bknote;
	$bookmark_arr[2]=$bknotedis;
	return $bookmark_arr;
}
function get_big_image($havephoto,$gender)
{
	global $IMG_URL;
			$VIEWPROFILE_IMAGE_URL="$IMG_URL/profile";
                        if($havephoto=='L')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_b.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_g.gif";
                        }
                        elseif($havephoto=='C')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_b.jpg";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_g.jpg";
                        }
                        elseif($havephoto=='F')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_b.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_g.gif";
                        }
                        elseif($havephoto=='H')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/ph_hidden_b.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/ph_hidden_g.gif";
                        }
                        elseif($havephoto=='P')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_b.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_b.gif";
                        }
                        elseif($havephoto=='U')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_b.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_g.gif";
                        }
                        elseif($havephoto=='N')
                        {
                                if($gender=='M')
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/Request-a-photo-male.gif";
                                else
                                        $image_file="$VIEWPROFILE_IMAGE_URL/images/ser2/Request-a-photo-Female.gif";
                        }
                        return $image_file;
}
function return_image_file_small($havephoto,$gender)
{
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
                        return $image_file;
}

function return_image_file($havephoto,$gender)
{
			if($havephoto=='L')
                        {
                                if($gender=='M')
                                        $image_file="ic_login_to_view_b_100.gif";
                                else
                                        $image_file="ic_login_to_view_g_100.gif";
                        }
                        elseif($havephoto=='C')
                        {
                                if($gender=='M')
                                        $image_file="ic_photo_vis_if_b_100.gif";
                                else
                                        $image_file="ic_photo_vis_if_g_100.gif";
                        }
                        elseif($havephoto=='F')
                        {
                                if($gender=='M')
                                        $image_file="ic_filtered_b_100.gif";
                                else
                                        $image_file="ic_filtered_g_100.gif";
                        }
                        elseif($havephoto=='H')
                        {
                                if($gender=='M')
                                        $image_file="ic_hidden_b_100.gif";
                                else
                                        $image_file="ic_hidden_g_100.gif";
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
                                        $image_file="ic_photo_coming_b_100.gif";
                                else
                                        $image_file="ic_photo_coming_g_100.gif";
                        }
                        elseif($havephoto=='N')
                        {
                                if($gender=='M')
                                        $image_file="ic_request_photo_b_100.gif";
                                else
                                        $image_file="ic_request_photo_g_100.gif";
                        }	
			return $image_file;
}
function return_image_file_mobile($havephoto,$gender)
{
			if($havephoto=='L')
                        {
                                if($gender=='M')
                                        $image_file="ic_login_to_view_b_60x60.gif";
                                else
                                        $image_file="ic_login_to_view_g_60x60.gif";
                        }
                        elseif($havephoto=='C')
                        {
                                if($gender=='M')
                                        $image_file="ic_photo_vis_if_b_60x60.gif";
                                else
                                        $image_file="ic_photo_vis_if_g_60x60.gif";
                        }
                        elseif($havephoto=='F')
                        {
                                if($gender=='M')
                                        $image_file="ic_filtered_b_60x60.gif";
                                else
                                        $image_file="ic_filtered_g_60x60.gif";
                        }
                        elseif($havephoto=='H')
                        {
                                if($gender=='M')
                                        $image_file="ic_hidden_b_60x60.gif";
                                else
                                        $image_file="ic_hidden_g_60x60.gif";
                        }
                        elseif($havephoto=='P')
                        {
                                if($gender=='M')
                                        $image_file="photo_fil_sm_b_60x60.gif";
                                else
                                        $image_file="photo_fil_sm_g_60x60.gif";
                        }
			elseif($havephoto=='U')
                        {
                                if($gender=='M')
                                        $image_file="ic_photo_coming_b_60x60.gif";
                                else
                                        $image_file="ic_photo_coming_g_60x60.gif";
                        }
                        elseif($havephoto=='N')
                        {
                                if($gender=='M')
                                        $image_file="ic_no_photo_b_60x60.gif";
                                else
                                        $image_file="ic_no_photo_g_60x60.gif";
                        }	
			return "mobilejs/".$image_file;
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function Cluster($notnewpage="")
{
        global $groupByFieldArr,$res,$moreclusterArr,$data,$_COOKIE,$searchGender,$Country_Res1;

include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        include("arrays.php");
	include("mapping_for_sphinx.php");
	include("mapping_for_sphinx1.php");

	if( is_array($Country_Res1) && !in_array(51,$Country_Res1) )
		$UsaCountry=1;
/*
	if($data["PROFILEID"])	
	{
		//$my_income=$_COOKIE["JS_INCOME"];
		//if(!$my_income )
		{
                	$sql_1="SELECT INCOME FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
	                $res_1=mysql_query_decide($sql_1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_1,"ShowErrTemplate");
                        $myrow_1=mysql_fetch_array($res_1);
			$my_income=$myrow_1["INCOME"];	

			if($data["GENDER"]=='M')
				$my_income_array=explode(",",$INCOME[$my_income]['LESS']);
			if($data["GENDER"]=='F')
				$my_income_array=explode(",",$INCOME[$my_income]['MORE']);
			//print_r($my_income_array);die;
			
		}
	}
*/
	//TEMPNEW
	/*
	unset($groupByFieldArr);
	$groupByFieldArr[]="SUBSCRIPTION";
	*/
	//TEMPNEW
        foreach($groupByFieldArr as $k1=>$v1)
        {
		$v1=strtoupper($v1);
                $sphinx_char_mapped_to_int=0;
		//ZERO RESULTS
		if($res[$k1]['matches']=='')
			//continue;
			return ;
		//ZERO RESULTS
		$reset=0;

		if($v1=='HEIGHT')	
			$clusterArr[$v1][$label][1]='';	

		if($v1=='INCOME')
		{
			if($searchGender=='F')
			{
				$clusterArr['INCOME']["No income"][0]=0;
				$clusterArr['INCOME']["Below Rs.3 lakhs"][0]=0;
				$clusterArr['INCOME']["Rs. 3 lakhs +"][0]=0;
				$clusterArr['INCOME']["Rs. 5 lakhs +"][0]=0;
			}
			else
			{
				$clusterArr['INCOME']["Below Rs. 5 lakhs"][0]=0;
				$clusterArr['INCOME']["Rs. 5 lakhs +"][0]=0;
				$clusterArr['INCOME']["Rs. 7.5 lakhs +"][0]=0;
				$clusterArr['INCOME']["Rs. 10 lakhs +"][0]=0;
			}
		}

		if($v1=='OCCUPATION')
		{
			$tempOccArr=array("Businessmen","Software","Medical Professional","Defence","Marketing/Sales/Adv","Teaching","Finance (CA, CS)","Administration","Production/Maintenance","Government");
			for($i=0;$i<10;$i++)
			{
				$clusterArr['OCCUPATION'][$tempOccArr[$i]][0]=0;
			}
		}
		if($v1=='EDU_LEVEL_NEW')
		{
			$tempOccArr=array("Professionals","Doctors","Engineers/ MBA's","Post Graduates");
			for($i=0;$i<4;$i++)
			{
				$clusterArr['EDU_LEVEL_NEW'][$tempOccArr[$i]][0]=0;
			}
		}

                foreach($res[$k1]['matches'] as $v)
                {
			/*	
			if($v1=='INCOME' &&  $data["GENDER"] && !in_array($v['attrs']['@groupby'],$my_income_array))
				continue;
			*/
			
                        //more than 1 cluster should appear
                        if(count($res[$k1]['matches'])==1)
				break;
			//TRACKING WORK
                        //more than 1 cluster should appear

                        //More Option only if more than 4 cluster are there
			//if(!in_array($v1,array('EDU_LEVEL_NEW','OCCUPATION','INCOME')))
			if(in_array($v1,array('EDU_LEVEL_NEW','OCCUPATION','SUBSCRIPTION')) || (!$UsaCountry && $v1=='INCOME') )
				;
			else
			{
	                        $reset++;
        	                if($reset>4)
                	                $moreclusterArr[$v1]=1;
				if($reset>4 && $v1!='MANGLIK' && $v1!='DIET')
                                	break;
			}
                        //More Option only if more than 4 cluster are there

                        //if(in_array($v1,array('CASTE','MTONGUE','EDUCATION_LEVEL_NEW','OCCUPATION')))
                        if(in_array($v1,array('CASTE','EDUCATION_LEVEL_NEW','OCCUPATION')))
                                $dropdownArrayName=$v1."_DROP";
                        elseif($v1=='RELIGION')
                                $dropdownArrayName=$v1."S";
                        elseif($v1=='HAVECHILD')
			{
                                $dropdownArrayName='SPHINX_CHILDREN';
				$sphinx_char_mapped_to_int=2;
			}
                        elseif($v1=='RELATION')
                                $dropdownArrayName='RELATIONSHIP';
                        elseif($v1=='EDU_LEVEL_NEW')
                                $dropdownArrayName='EDUCATION_LEVEL_NEW_DROP';
                        elseif($v1=='INCOME')
                                $dropdownArrayName="SPHINX_INCOME_DROP";
			elseif($v1=='MTONGUE')
				$dropdownArrayName="MTONGUE_DROP_SMALL";
                        else
                        {
                                $sphinx_char_mapped_to_int=1;

				if($notnewpage==1)
        	                        $dropdownArrayName=$v1;
				else
					$dropdownArrayName="SPHINX_".$v1;
                        }
                        if($v1=='INDIA_NRI')
                        {
                                $cnt=$v['attrs']['@count'];
                                if($cnt>0)
                                {
                                        if($v['attrs']['@groupby']==1)
                                                $label='India';
                                        else
                                                $label="NRI";
                                        $clusterArr[$v1][$label][0]=$cnt;
                                        $clusterArr[$v1][$label][1]=$v['attrs']['@groupby'];
                                }
                        }
			elseif($v1=='EDU_LEVEL_NEW')
			{
				$tempVal_1=$v['attrs']['@groupby'];
				$cnt=$v['attrs']['@count'];
				if(in_array($tempVal_1,$EDU_CLUSTER_ARRAY['P'])  || $tempVal_1=='P')
				{
					$label="Professionals";
					$clusterArr[$v1][$label][0]+=$cnt;
                                	$clusterArr[$v1][$label][1]='P';
				}
				if(in_array($tempVal_1,$EDU_CLUSTER_ARRAY['D']) || $tempVal_1=='D')
				{
					$label="Doctors";
					$clusterArr[$v1][$label][0]+=$cnt;
                                	$clusterArr[$v1][$label][1]='D';
				}
				if(in_array($tempVal_1,$EDU_CLUSTER_ARRAY['E']) || $tempVal_1=='E')
				{
					$label="Engineers/ MBA's";
					$clusterArr[$v1][$label][0]+=$cnt;
                                	$clusterArr[$v1][$label][1]='E';
				}
				if(in_array($tempVal_1,$EDU_CLUSTER_ARRAY['A']) || $tempVal_1=='A')
				{
					$label="Post Graduates";
					$clusterArr[$v1][$label][0]+=$cnt;
                                	$clusterArr[$v1][$label][1]='A';
				}
				/*
				if(in_array($tempVal_1,$EDU_CLUSTER_ARRAY['G']))
				{
					$label="Graduates";
					$clusterArr[$v1][$label][0]+=$cnt;
                                	$clusterArr[$v1][$label][1]='G';
				}
				*/
			}
                        elseif($v1=='OCCUPATION')
                        {
                                $tempVal_1=$v['attrs']['@groupby'];
                                $cnt=$v['attrs']['@count'];
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['B']) || $tempVal_1=='B')
                                {
                                        $label="Businessmen";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='B';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['I']) || $tempVal_1=='I')
                                {
                                        $label="Software";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='I';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['M']) || $tempVal_1=='M')
                                {
                                        $label="Medical Professional";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='M';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['D']) || $tempVal_1=='D')
                                {
                                        $label="Defence";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='D';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['MS']) || $tempVal_1=='MS')
                                {
                                        $label="Marketing/Sales/Adv";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='MS';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['T']) || $tempVal_1=='T')
                                {
                                        $label="Teaching";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='T';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['F']) || $tempVal_1=='F')
                                {
                                        $label="Finance (CA, CS)";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='F';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['A']) || $tempVal_1=='A')
                                {
                                        $label="Administration";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='A';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['MA']) || $tempVal_1=='MA')
                                {
                                        $label="Production/Maintenance";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='MA';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['O']) || $tempVal_1=='O')
                                {
                                        $label="Others";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='O';
                                }
                                if(in_array($tempVal_1,$OCC_CLUSTER_ARRAY['G']) || $tempVal_1=='G')
                                {
                                        $label="Government";
                                        $clusterArr[$v1][$label][0]+=$cnt;
                                        $clusterArr[$v1][$label][1]='G';
                                }
                        }
                        elseif($v1=='INCOME' && !$UsaCountry)
                        {
                                $tempVal_1=$v['attrs']['@groupby'];
                                $cnt=$v['attrs']['@count'];


				if($searchGender=='F')
				{
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['M0'])  || $tempVal_1=='M0')
					{
						$label="No income";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='M0';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['M1'])  || $tempVal_1=='M1')
					{
						$label="Below Rs.3 lakhs";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='M1';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['M2'])  || $tempVal_1=='M2')
					{
						$label="Rs. 3 lakhs +";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='M2';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['M3'])  || $tempVal_1=='M3')
					{
						$label="Rs. 5 lakhs +";
                                        	$clusterArr[$v1][$label][0]+=$cnt;
	                                        $clusterArr[$v1][$label][1]='M3';
        	                        }
				}
				else
				{
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['F0']) || $tempVal_1=='F0')
					{
						$label="Below Rs. 5 lakhs";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='F0';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['F1']) || $tempVal_1=='F1')
					{
						$label="Rs. 5 lakhs +";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='F1';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['F2']) || $tempVal_1=='F2')
					{
						$label="Rs. 7.5 lakhs +";
						$clusterArr[$v1][$label][0]+=$cnt;
						$clusterArr[$v1][$label][1]='F2';
					}
					if(in_array($tempVal_1,$INC_CLUSTER_ARRAY['F3']) || $tempVal_1=='F3')
					{
						$label="Rs. 10 lakhs +";
                                        	$clusterArr[$v1][$label][0]+=$cnt;
	                                        $clusterArr[$v1][$label][1]='F3';
        	                        }
				}
                        }

                        elseif($v1=='UNMARRIED_MARRIED')
                        {
                                $cnt=$v['attrs']['@count'];
                                if($cnt>0)
                                {
                                        if($v['attrs']['@groupby']==1)
                                                $label='Never Married';
                                        else
                                                $label="Married Earlier";
                                        $clusterArr[$v1][$label][0]=$cnt;
                                        $clusterArr[$v1][$label][1]=$v['attrs']['@groupby'];
                                }
                        }
			elseif($v1=='MANGLIK' && in_array($v['attrs']['@groupby'],array(0,68)) && is_numeric($v['attrs']['@groupby']))
			{
				//Not to consider blank or  doest'nt matter MANGLIK 
			}
                        else
                        {
				if($dropdownArrayName=='SPHINX_SUBSCRIPTION')
				{
					$label=${"SPHINX_ORIGINAL_SUBSCRIPTION"}[$v['attrs']['@groupby']];
				}
				else
	                                $label=${$dropdownArrayName}[$v['attrs']['@groupby']];
                                $cnt=$v['attrs']['@count'];
                                if($cnt>0 && $label) //prevent Not-Specified to come on left panel.
                                //if($cnt>0)
                                {
                                        if(!$label)
                                                $label="Not-Specified";

                                        $clusterArr[$v1][$label][0]=$cnt;

                                        if($sphinx_char_mapped_to_int)
                                        {
						if($sphinx_char_mapped_to_int==2)
						{
							//$clusterArr[$v1][$label][1]=${"SPHINX_ORIGINAL_CHILDREN"}[$v['attrs']['@groupby']];
							$tempGroup=${"SPHINX_ORIGINAL_CHILDREN"}[$v['attrs']['@groupby']];
							if($tempGroup)
								$clusterArr[$v1][$label][1]=$tempGroup;
							else
								$clusterArr[$v1][$label][1]=$v['attrs']['@groupby'];
						}
						else
						{
							$tempGroup=${"SPHINX_ORIGINAL_".$v1}[$v['attrs']['@groupby']];
							if($tempGroup)
								$clusterArr[$v1][$label][1]=$tempGroup;
							else
								$clusterArr[$v1][$label][1]=$v['attrs']['@groupby'];
							//$clusterArr[$v1][$label][1]=${"SPHINX_ORIGINAL_".$v1}[$v['attrs']['@groupby']];
						}
                                        }
                                        else
                                        {
                                                $clusterArr[$v1][$label][1]=$v['attrs']['@groupby'];
                                        }
                                }
                        }
                }
        }
	global $smarty;

	if(!$notnewpage)
	{
       	if(is_array($clusterArr['SUBSCRIPTION']))
        {
                //added for A.P
                if($clusterArr['SUBSCRIPTION']['Q'])
                {
                        $subtrackForPaid=$clusterArr['SUBSCRIPTION']['Q'][0];
                        $clusterArr['SUBSCRIPTION']['D'][0]+=$clusterArr['SUBSCRIPTION']['Q'][0];
                        $clusterArr['SUBSCRIPTION']['O'][0]+=$clusterArr['SUBSCRIPTION']['Q'][0];
                        if(!$clusterArr['SUBSCRIPTION']['O'][1])
                                $clusterArr['SUBSCRIPTION']['O'][1]='O';
                        if(!$clusterArr['SUBSCRIPTION']['D'][1])
                                $clusterArr['SUBSCRIPTION']['D'][1]='D';
                }
                unset($clusterArr['SUBSCRIPTION']['Q']);
                //added for A.P

                if(!$clusterArr['SUBSCRIPTION']['F'])
                {
                        $clusterArr['SUBSCRIPTION']['F'][0]=0;
                        $clusterArr['SUBSCRIPTION']['F'][1]='F';//added for A.P
                }

                if($clusterArr['SUBSCRIPTION']['D'])
                        if($clusterArr['SUBSCRIPTION']['D'][0]>0)
                                $clusterArr['SUBSCRIPTION']['F'][0]+=$clusterArr['SUBSCRIPTION']['D'][0];

                if($clusterArr['SUBSCRIPTION']['O'])
                        if($clusterArr['SUBSCRIPTION']['O'][0]>0)
                                $clusterArr['SUBSCRIPTION']['F'][0]+=$clusterArr['SUBSCRIPTION']['O'][0];

                //added for A.P
                if($subtrackForPaid)
                        $clusterArr['SUBSCRIPTION']['F'][0]-=$subtrackForPaid;
                //added for A.P

                foreach($clusterArr['SUBSCRIPTION'] as $k=>$v)
                {
                        $LabeledIndex=$SPHINX_SUBSCRIPTION[$k];
                        $clusterArr['SUBSCRIPTION'][$LabeledIndex]=$v;
                        unset($clusterArr['SUBSCRIPTION'][$k]);
                }
        }
	}
	
	if(is_array($clusterArr['EDU_LEVEL_NEW']))
	{
		foreach($clusterArr['EDU_LEVEL_NEW'] as $k=>$v)
		{
			if($v[0]==0)
				unset($clusterArr['EDU_LEVEL_NEW'][$k]);
			else
				$no_unset_edu=1;
		}
		if(!$no_unset_edu)
			unset($clusterArr['EDU_LEVEL_NEW']);
	}
	if($clusterArr['EDU_LEVEL_NEW'])
		$moreclusterArr['EDU_LEVEL_NEW']=1;

	if(is_array($clusterArr['INCOME']))
	{
		foreach($clusterArr['INCOME'] as $k=>$v)
		{
			if($v[0]==0)
				unset($clusterArr['INCOME'][$k]);	
			else
				$no_unset_inc=1;
		}

		if(!$no_unset_inc)
			unset($clusterArr['INCOME']);
		//print_r($clusterArr['INCOME']);
	}


	if(is_array($clusterArr['OCCUPATION']))
	{
		foreach($clusterArr['OCCUPATION'] as $kk=>$vv)
		{
			$sorttop4occ[$kk]=$vv[0];
		}
		arsort($sorttop4occ);
		unset($sorttop4occ['Others']);
		$clusterArr['OCCUPATION']['q'][0]=11;	
		$clusterArr['OCCUPATION']['q'][1]=11;	
		foreach($sorttop4occ as $kk=>$vv)
		{
			$tempOcc[$kk][0]=$clusterArr['OCCUPATION'][$kk][0];	
			$tempOcc[$kk][1]=$clusterArr['OCCUPATION'][$kk][1];	
		}
		$tempOccArr=$clusterArr['OCCUPATION'];
		foreach($clusterArr['OCCUPATION'] as $k=>$v)
		{
			if($k!='q')
				unset($clusterArr['OCCUPATION'][$k]);
		}
		foreach($tempOcc as $k=>$v)
		{
			if($tempOccArr[$k][0])
			{
				$clusterArr['OCCUPATION'][$k][0]=$tempOccArr[$k][0];
				$clusterArr['OCCUPATION'][$k][1]=$tempOccArr[$k][1];
			}
			if($loop_4++>2)
				break;
		}
		unset($clusterArr['OCCUPATION']['q']);
	}
	if($clusterArr['OCCUPATION'])
		$moreclusterArr['OCCUPATION']=1;

	foreach($groupByFieldArr as $kkk=>$vvv)
	{
		if(count($clusterArr[$vvv])<2 && $vvv!='HEIGHT')
			unset($clusterArr[$vvv]);
	}
	/*
	$min=0;
	if(is_array($clusterArr['OCCUPATION']))
	{
		$a=array_chunk($clusterArr['OCCUPATION'],4,'Y');
		$aa=$a[0];
		$aaa=array_diff_assoc($clusterArr['OCCUPATION'],$aa);	
		arsort($aaa);
		print_r($clusterArr['OCCUPATION']);
		//Retaing original poistion in cluster
		$clusterArr['OCCUPATION']['q'][0]=11;	
		$clusterArr['OCCUPATION']['q'][1]=11;	
		//Retaing original poistion in cluster

		foreach($clusterArr['OCCUPATION'] as $k=>$v)
		{
			if($k!='q')
				unset($clusterArr['OCCUPATION'][$k]);
		}
		$len=0;
		foreach($aa as $k=>$v)
		{
			if($v[0]>$min)
			{
				$clusterArr['OCCUPATION'][$k][0]=$v[0];
				$clusterArr['OCCUPATION'][$k][1]=$v[1];
				$len++;
			}
		}
		foreach($aaa as $k=>$v)
		{
			if($len>3)
				$moreclusterArr['OCCUPATION']=1;
			if($len>3)
				break;
			if($v[0]>$min && $v[1]!='O')
			{
				$clusterArr['OCCUPATION'][$k][0]=$v[0];
				$clusterArr['OCCUPATION'][$k][1]=$v[1];
				$len++;
			}
		}
		unset($clusterArr['OCCUPATION']['q']);
		//print_r($clusterArr['OCCUPATION']);
	}
	if(count($clusterArr['OCCUPATION'])<2)
		unset($clusterArr['OCCUPATION']);
	*/
	if(count($clusterArr)==0)
		$smarty->assign("NoClusterToDisplay",1); 
	if($moreclusterArr)
		$smarty->assign("moreclusterArr",$moreclusterArr);
        return $clusterArr;
}

function originalSearchBreadcrumb($Gender='',$Religion="",$Caste='',$Mtongue='',$Lage='',$Hage='',$WithPhoto='',$MStatus="",$Manglik='',$Hchild='',$Lheight='',$Hheight='',$Btype='',$Complexion='',$Diet='',$Smoke='',$Drink='',$Handicap='',$Occupation='',$Country='',$City='',$edu_levelArr='',$Edu_level_new='',$Online='',$Income='',$Live_parents='',$Subcaste='',$Horoscope='',$Sampraday='',$Urdu='',$Hijab='',$Maththab='',$Amritdhari='',$Cut_hair='',$Turban='',$Zarathustri='',$Wstatus='',$Handicap='',$Nhandicap='',$Hiv='',$Keyword='',$Kwd_rule='',$Login='',$Contact_visible='',$incomeRangeArr='')
{
	global $smarty,$CASTE_DROP,$MATHTHAB,$MTONGUE_DROP_SMALL,$RELIGIONS,$MSTATUS,$HEIGHT_DROP,$SAMPRADAY,$CHILDREN,$BODYTYPE,$COMPLEXION,$DIET,$DRINK,$SMOKE,$HANDICAPPED,$OCCUPATION_DROP,$COUNTRY_DROP,$EDUCATION_LEVEL_NEW_DROP,$EDUCATION_LEVEL_DROP,$INCOME_DROP,$WORK_STATUS,$NATURE_HANDICAP,$CITY_DROP,$MANGLIK;
	$GEN=array('Y'=>"Yes",'N'=>"No",'D'=>"Not decided");
       if($Gender=='M')
                $arr[]="Groom";
        else
                $arr[]="Bride";

        if($Lage && $Hage)
                $arr[]=$Lage." to ".$Hage;
	if($Lheight && $Hheight)
        {
		$lh=explode('(',$HEIGHT_DROP[$Lheight]);
                $hh=explode('(',$HEIGHT_DROP[$Hheight]);
                $arr[]=$lh[0]." to ".$hh[0];
        }

        if($WithPhoto)
                $arr[]="with photo";
	if($Hchild)
        {
                foreach($Hchild as $k=>$v)
                {
                        $varr[]=$CHILDREN[$v];
                }
                $arr[]="Children: ".implode(",",$varr);
                unset($varr);
                unset($str);
        }
	if($Live_parents)
        {
                $varr[]=$GEN[$Live_parents];
                $arr[]="Living with parents: ".implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Religion)
        {
		foreach($Religion as $k=>$v)
		{
			if($v!=='DONT_MATTER')
				$varr[]=$RELIGIONS[$v];
		}
		if($varr)
	                $arr[]=implode(",",$varr);
		unset($varr);
		unset($str);
        }
        if($Mtongue)
	{
                foreach($Mtongue as $k=>$v)
                {
                        if(strstr($v,"|#|"))
                        {
                                $mtongue=explode('|#|',$v);
                                foreach($mtongue as $k1=>$v1)
                                        $varr[]=$MTONGUE_DROP_SMALL[$v1];
                        }
                        elseif($MTONGUE_DROP_SMALL[$v])
                                $varr[]=$MTONGUE_DROP_SMALL[$v];
                }
		if($varr)
	                $arr[]=implode(",",$varr);
		unset($varr);
		unset($str);
	}
        if($Caste)
	{
		foreach($Caste as $k=>$v)
		{
			if($v!='DM')
			$varr[]=$CASTE_DROP[$v];
		}
		if($varr)
	                $arr[]=implode(",",$varr);
		unset($varr);
		unset($str);
	}
        if($MStatus)
	{
		foreach($MStatus as $k=>$v)
		{
			if($v!=='DONT_MATTER')
                        	$varr[]=$MSTATUS[$v];
                }
		if($varr)
                	$arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Manglik)
	{
		foreach($Manglik as $k=>$v)
		{
			if($v!='X')
                        $varr[]=$MANGLIK[$v];
                }
                $arr[]="Manglik: ".implode(",",$varr);
                unset($varr);
                unset($str);
	}
	if($Subcaste)
        {
                $arr[]=stripslashes($Subcaste);
        }
        if($Sampraday)
        {
                foreach($Sampraday as $k=>$v)
                {
			if($v!='X')
                        $varr[]=$SAMPRADAY[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Urdu)
        {
                foreach($Urdu as $k=>$v)
                {
                        $varr[]="Urdu Must";
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Hijab)
        {
                foreach($Hijab as $k=>$v)
                {
                        $varr[]=$GEN[$v];
                }
                $arr[]="Wear Hijab:".implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Maththab)
        {
                foreach($Maththab as $k=>$v)
                {
                        $varr[]=$MATHTHAB[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
        }
	 if($Amritdhari)
        {
                foreach($Amritdhari as $k=>$v)
                {
			if($v!='X')
                        $varr[]=$GEN[$v];
                }
                $arr[]="Amritdhari: ".implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Cut_hair)
        {
                foreach($Cut_hair as $k=>$v)
                {
			if($v!='X')
                        $varr[]=$GEN[$v];
                }
                $arr[]="Cuts hair: ".implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Turban)
        {
                foreach($Turban as $k=>$v)
                {
			if($v!='X')
                        $varr[]=$GEN[$v];
                }
                $arr[]="Wear turban: ".implode(",",$varr);
                unset($varr);
                unset($str);
        }
        if($Zarathustri)
        {
                foreach($Zarathustri as $k=>$v)
                {
                        $varr[]=$GEN[$v];
                }
		$arr[]="Zarathustri: ".implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Btype)
	{
		foreach($Btype as $k=>$v)
		{
                        $varr[]=$BODYTYPE[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Complexion)
	{
		foreach($Complexion as $k=>$v)
		{
                        $varr[]=$COMPLEXION[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Diet)
	{
		foreach($Diet as $k=>$v)
		{
                        $varr[]=$DIET[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Smoke)
	{
		foreach($Smoke as $k=>$v)
		{
                        $varr[]=$SMOKE[$v];
                }
                $arr[]="Smoke: ".implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Drink)
	{
		foreach($Drink as $k=>$v)
		{
                        $varr[]=$DRINK[$v];
                }
                $arr[]="Drink: ".implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Handicap)
	{
		foreach($Handicap as $k=>$v)
		{
                        $varr[]=$HANDICAPPED[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Nhandicap)
	{
		foreach($Nhandicap as $k=>$v)
		{
                        $varr[]=$NATURE_HANDICAP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Occupation)
	{
		foreach($Occupation as $k=>$v)
		{
                        $varr[]=$OCCUPATION_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Country)
	{
		foreach($Country as $k=>$v)
		{
                        $varr[]=$COUNTRY_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($City)
	{
		foreach($City as $k=>$v)
		{
                        $varr[]=$CITY_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Edu_level_new)
	{
		foreach($Edu_level_new as $k=>$v)
		{
                        $varr[]=$EDUCATION_LEVEL_NEW_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
	elseif($edu_levelArr)
	{
		foreach($edu_levelArr as $k=>$v)
                {
                        $varr[]=$EDUCATION_LEVEL_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
	elseif($Edu_level_new)
	{
		foreach($Edu_level_new as $k=>$v)
                {
                        $varr[]=$EDUCATION_LEVEL_DROP[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
	}
        if($Online)
	{
		$arr[]="Only profiles available for chat";
	}
        if($Income)
	{
		if($incomeRangeArr)
		{
			global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
			$varr=getIncomeText($incomeRangeArr);
		}
		else
		{
			foreach($Income as $k=>$v)
			{
                	        $varr[]=$INCOME_DROP[$v];
	                }
		}
		if($varr)
	                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Wstatus)
	{
		foreach($Wstatus as $k=>$v)
		{
                        $varr[]=$WORK_STATUS[$v];
                }
                $arr[]=implode(",",$varr);
                unset($varr);
                unset($str);
	}
        if($Hiv=='N')
	{
                $arr[]="HIV: No";
	}
	elseif($Hiv=='Y')
	{
                $arr[]="HIV: Yes";
	}
        if($Keyword)
	{
                $arr[]=stripslashes($Keyword);
		if($Kwd_rule)
			$arr[]=$Kwd_rule;
	}
        if($Login)
	{
                $arr[]="New Profiles from last login";
	}
        if($Contact_visible)
	{
                $arr[]="Profiles with their contact information visible";
	}

 	$str=implode(", ",$arr);
	$smarty->assign("COMPLETE_SEARCH",$str);
	if(strlen($str)>200)
	{
		$str=substr($str,0,197)."...";
	}
	$smarty->assign("ORIGINAL_SEARCH",$str);
}

function pagesarr($n,$l)
{
	global $smarty;
	//1 The current page of the search results is the first populated. (n)
        $arr[]=$n;

        //2 : The 5 pages before and after the current page are populated next, given that these are real numbers. (n-5…n…n+5)
        $start=( ($n-5) < 1 ? 1 : ($n-5) );
        $end=( ($n+5) > $l ? $l : ($n+5) );
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }

        //3 Next, the first 10 pages and then the last 10 pages are populated. (1…10 | n-5…n…n+5 | last-9…last)
        $start=1;
        $end=( ($l>9) ? 10 : ($l-9) );
        for($i=1;$i<=$end;$i++)//3
        {
                $arr[]=$i;
        }

        $start=( (($l-9)>1) ? ($l-9) : 1);
        $end=$l;
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }


        //4 Next the space between pages 10 and n-5 is divided into 10 equal parts and 10 page numbers are populated by rounding them off.
        $a=$n-5-10;
        if($a>20)       
        {
                $start=$a/10;
                $end=$n-5;
                for($i=$start;$i<$end;$i=$i+$start)
                {
			$tempK=round(10+$i);
			if($tempK<=$n-4)
			{
	                        $arr[]=$tempK;
			}
                }
        }
	
        //5 The space between pages n+5 and last-9 are divided into 10 equal parts and 10 page numbers are populated by rounding them off
        $a=$l-9-($n+5);
        if($a>20)
        {
                $start=$a/10;
                $end=$l-9;
                for($i=$start;$i<$end;$i=$i+$start)
                {
			$tempK=round(($n+5)+$i);
			if($tempK<=$l-8)
			{
	                        $arr[]=$tempK;
        	                $arr111[]=$tempK;
			}
                }
        }
        $arr=array_unique($arr);
        sort($arr);
	$smarty->assign("total_pages",$arr);
}

function getOccupationLabelFromUsedValue($arr)
{
	if(in_array(13,$arr))
		$arr_new[]="Entrepreneurship/ Business";
	if(in_array(17,$arr))
		$arr_new[]="Software";
	if(in_array(24,$arr))
		$arr_new[]="Doctor/ Medical Profession";
	if(in_array(34,$arr))
		$arr_new[]="Defence";
	if(in_array(2,$arr))
		$arr_new[]="Marketing/Sales/Adv";
	if(in_array(31,$arr))
		$arr_new[]="Teaching";
	if(in_array(1,$arr))
		$arr_new[]="Finance (CA, CS)";
	if(in_array(12,$arr))
		$arr_new[]="Administration";
	if(in_array(25,$arr))
		$arr_new[]="Production/Maintenance";
	if(in_array(33,$arr))
		$arr_new[]="Government";
/*
	if(in_array(42,$arr))
		$arr_new[]="Others";
*/

	global $OCCUPATION_DROP,$OCC_GROUP_ARRAY,$OCC_CLUSTER_REVERSE_GROUP_ARRAY;
	foreach($arr as $k=>$v)
	{
		//if(!in_array($v,$OCC_GROUP_ARRAY) && !in_array($v,array(13,17,24,34,2,31,1,12,25,33)))
		if(!in_array($v,$OCC_GROUP_ARRAY) && !in_array($v,$OCC_CLUSTER_REVERSE_GROUP_ARRAY))
		{
			$occ=$OCCUPATION_DROP[$v];
			if($v==43)
				$arr_new[]='Other Occupation';
			elseif($occ)
				$arr_new[]=$occ;
		}
	}
	return $arr_new;
}

function  findOnlineProfiles($flagTemp="")
{
        $mysqlObj=new Mysql;
        $dbSlave=$mysqlObj->connect("master");

        $onlinesql="select userID from userplane.users";
        $onlineresult=mysql_query($onlinesql,$dbSlave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
        $onlinestrArr="";
        while($myonline=mysql_fetch_array($onlineresult))
        {
		/* Ignore_str is not passing into this fn
                if($profileid && $ignore_str)
                {
                        if(!strstr($myonline["userID"],$ignore_str))
                                $onlinestr.="'" . $myonline["userID"] . "',";
                }
                else
                        $onlinestr.="'" . $myonline["userID"] . "',";
		*/
		$onlinestrArr[]=(int)$myonline["userID"];
        }
	
	//Temp
	if($flagTemp1 && 0)
	{
		if($onlinestrArr)
			return $onlinestrArr;
		else
			return NULL;
	}
	//Temp

        mysql_free_result($onlineresult);

        //Get gmail online profiles
        $onlinesql="select USER as profileID from bot_jeevansathi.user_online";
        //$onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
        $onlineresult=mysql_query($onlinesql,$dbSlave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
        while($myonline=mysql_fetch_array($onlineresult))
        {
		/*
                if($profileid && $ignore_str)
                {
                        if(!strstr($myonline["profileID"],$ignore_str))
                                $onlinestr.="'" . $myonline["profileID"] . "',";

                }
                else
                        $onlinestr.="'" . $myonline["profileID"] . "',";
		*/
		$onlinestrArr[]=(int)$myonline["profileID"];
        }
        mysql_free_result($onlineresult);
	if($onlinestrArr)
		return $onlinestrArr;
	else
		return NULL;

        //Get yahoo online profiles
/*
        $onlinesql="select PROFILEID from bot_jeevansathi.user_yahoo where show_in_search=1 and online_flag IN(1,2,3)";
        $onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
        while($myonline=mysql_fetch_array($onlineresult))
        {

                if($profileid && $ignore_str)
                {
                        if(!strstr($myonline["PROFILEID"],$ignore_str))
                                $onlinestr.="'" . $myonline["PROFILEID"] . "',";

                }
                else
                        $onlinestr.="'" . $myonline["PROFILEID"] . "',";
        }
        mysql_free_result($onlineresult);
*/
        $onlinestr=substr($onlinestr,0,strlen($onlinestr)-1);
        return $onlinestr;
}

function reverseTextSearch($label,$arr)
{
	//$text=" @$label ( 99999 | ";
	$text=" @$label ( ";
	foreach ($arr as $k) {$text .= " $k |";}
	$text = substr($text,0,-1);
	$text.=")";
	return $text;
}

function getreverseData($profileid,$FP='')
{
	if($FP)
	{
        	global $r_page,$r_Gender,$r_Castes,$r_Manglik,$r_MTongue,$r_MStatus,$r_Height,$r_Age,$r_Income,$STYPE,$Sort,$r_Religion;
        	$sqlR="SELECT GENDER,AGE,HEIGHT,MTONGUE,CASTE,MSTATUS,INCOME,MANGLIK,RELIGION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
	}
	else
	{
        	global $r_page,$r_Gender,$r_Castes,$r_Manglik,$r_MTongue,$r_MStatus,$r_Occ,$r_CountryRes,$r_CityRes,$r_Height,$r_ELevel,$r_ELevelNew,$r_Drink,$r_Smoke,$r_Child,$r_Btype,$r_Diet,$r_Handicapped,$r_Age,$r_Income,$r_Relation,$r_Comp,$STYPE,$Sort,$r_Religion;
        	$sqlR="SELECT GENDER,AGE,HEIGHT,MTONGUE,DIET,SMOKE,CITY_RES,DRINK,BTYPE,COMPLEXION,HANDICAPPED,CASTE,MSTATUS,COUNTRY_RES,OCCUPATION,EDU_LEVEL_NEW,INCOME,MANGLIK,RELIGION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
	}
        $resR=mysql_query_decide($sqlR) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlR,"ShowErrTemplate");
        $rowR=mysql_fetch_array($resR);
        $r_Gender=$rowR['GENDER'];
        $r_Age=$rowR['AGE'];
        $r_Height=$rowR['HEIGHT'];
        $r_MTongue=search_display_format($rowR['MTONGUE'],99999);
	if(!$FP)
	{
		$r_Diet=search_display_format($rowR['DIET'],99999);
		$r_CityRes=search_display_format($rowR['CITY_RES'],99999);
		$r_Smoke=search_display_format($rowR['SMOKE'],99999); 
		$r_Drink=search_display_format($rowR['DRINK'],99999);
		$r_Btype=search_display_format($rowR['BTYPE'],99999);
		$r_Comp=search_display_format($rowR['COMPLEXION'],99999);
		$r_Handicapped=search_display_format($rowR['HANDICAPPED'],99999);
		$r_CountryRes=search_display_format($rowR['COUNTRY_RES'],99999);
		$r_Occ=search_display_format($rowR['OCCUPATION'],99999);
		$r_ELevelNew=search_display_format($rowR['EDU_LEVEL_NEW'],99999);
	}
	$r_MStatus=search_display_format($rowR['MSTATUS'],99999);
        $r_Caste=search_display_format($rowR['CASTE']);
	if(is_array($r_Caste))
	{
		foreach ($r_Caste as $c)
  		      $r_Castes = getcasteparent($c);
	}
	$r_Castes[]=99999;
        $r_Income=search_display_format($rowR['INCOME'],99999);
        $r_Manlik=search_display_format($rowR['MANGLIK'],99999);
        $r_Religion=search_display_format($rowR['RELIGION'],99999);
        /*
        $r_Gender=$rowR['GENDER'];
        $r_Age=21;      
        $r_Height=15;
        $r_MTongue=array(21,18);
        //$r_CityRes=array('DE00','MH04');
        //$r_Diet=array('V','N');
        //$r_Smoke=array('N','Y'); 
        //$r_Drink=array('Y');
        //$r_Btype=array(4,3);
        //$r_Comp=array(5,4);
        //$r_Handicapped=array('N');
        //$r_Castes=array(12,13);
        //$r_MStatus=array('N');
        //$r_CountryRes=array(51,128);
        //$r_Occ=array(4);
        //$r_ELevelNew=array(9);
        //$r_Income=array(4);
        //$r_Manglik=array('M');
        //$r_Religion=array(2,4);
        */
}


function is_crawlers() 
{
	$sites = 'Google|Yahoo|msnbot'; // Add the rest of the search-engines 
	return (preg_match("/$sites/", $_SERVER['HTTP_USER_AGENT']) > 0) ? true : false;  
}
?>
