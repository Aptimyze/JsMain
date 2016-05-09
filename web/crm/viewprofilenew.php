<?php

function viewprofile($username,$viewprofile,$agentPrivilege="")
{
	global $company , $user_login;
	global $smarty;
	$path=$_SERVER['DOCUMENT_ROOT'];

	include_once($path."/profile/arrays.php");
	include_once($path."/profile/dropdowns_temp.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");


	include_once($path."/classes/Memcache.class.php");
	include_once($path."/classes/Mysql.class.php");
	include_once($path."/classes/globalVariables.Class.php");
	include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

	// JS Social file path for defined arrays	
	if($path)
        	$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
	else
        	$symfonyFilePath = JsConstants::$docRoot."/../";
	include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");

	// authenticate the user
	$data=authenticated($checksum);

	if($data)
	{
		$PERSON_LOGGED_IN=true;
		$smarty->assign("LOGGED_PERSON_PROFILEID",$data["PROFILEID"]);
	}
	$SCREENING_MESSAGE="<span class=\"smallred\">This field is currently being screened. Please re-check shortly.</span>";
	
	$SCREENING_MESSAGE_SELF="<span class=\"smallred\">This field is currently being screened. This may take upto 24 hours to go live.</span>";
	
	if($profilechecksum!="")
	{
		$arr=explode("i",$profilechecksum);
		if(md5($arr[1])!=$arr[0])
		{
			showProfileError('','',$smarty);
		}
		else 
			$profileid=$arr[1];
	}
	elseif($username!="")
	{
		$sql="select PROFILEID from newjs.JPROFILE where USERNAME='" . addslashes(stripslashes($username)) . "'";
		$result=mysql_query_decide($sql) or die("1".mysql_error_js());
		
		if(mysql_num_rows($result) <= 0)
			showProfileError('','',$smarty);
		else 
		{
			$myrow=mysql_fetch_array($result);
			$profileid=$myrow["PROFILEID"];
			
			$profilechecksum=md5($profileid) . "i" . $profileid;
		}
		
		mysql_free_result($result);
	}
	else 
	{
		showProfileError('','',$smarty);
	}
	
	// find out whether the person whose profile is being viewed is currently online
	$sql="select count(*) from userplane.recentusers where userID='$profileid'";
	$result=mysql_query_decide($sql) or die("1".mysql_error_js());
	
	$myonline=mysql_fetch_row($result);
	
	if($myonline[0] > 0)
	{
		$smarty->assign("CHATID",$profileid);
		$smarty->assign("ISONLINE",1);
	}
	
	mysql_free_result($result);
	
	$sql="select * from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die("1".mysql_error_js());
	
	// if no profile is found for this profileid, show error message
	if(mysql_num_rows($result) <= 0)
		showProfileError('','',$smarty);
		
	$myrow=mysql_fetch_array($result);
	
	// free the recordset
	mysql_free_result($result);

	// privacy setting - let the person view the profile only if he is logged in
	// R - means that only allow logged in people to view
	// F - means that only allow logged in people to view provided they are not filtered
	// C - means that only allow logged in people to view provided there has been a contact between us
	
        // contact details of the person to be shown if he has taken the membership to show his conatct details
        // Field 'D' in SUBCSCIPTION field tells that he has taken the membership
        if(strstr($myrow['SUBSCRIPTION'],"D") && !strstr($myrow['SUBSCRIPTION'],"S"))
        {
		if($data['GENDER']!=$myrow['GENDER'])
		{
			$smarty->assign("ECLASSIFIED_MEM","Y");
	                $smarty->assign("CONTACTDETAILS","1");
        	        $CONTACTDETAILS=1;
		}
        }
	
	$PRIVACY=$myrow["PRIVACY"];
	
	$smarty->assign("AGE",$myrow["AGE"] . " years");
	
	// the profile is not to be shown if it is not activated. However, if the person is viewing his own profile, it should be allowed
		
	// indicate that the person is viewing his own profile
	if($data["PROFILEID"]==$profileid)
	{
		$PERSON_HIMSELF=true;
		$smarty->assign("SELF","1");
	}
	// assert that the person is viewing the profile of a person having the same gender
	elseif($data["GENDER"]==$myrow["GENDER"])
		$samegender=1;
		
	// if the gender is same and privacy option of F or C is set don't show the profile
	if($samegender==1 && ($PRIVACY=="F" || $PRIVACY=="C"))
		showProfileError("","S",$smarty);

	/******************************************************
	check for photographs starts here
	******************************************************/
	
	// if main photograph is there and is screened
	if($myrow["HAVEPHOTO"]=="Y")
	{
		//Symfony Photo Modification
		$screenedMainPhoto = SymfonyPictureFunctions::haveScreenedMainPhoto($profileid);
		if($screenedMainPhoto=='Y')
			$main_photo_is_screened = 1;
		$hasAlbum = SymfonyPictureFunctions::checkMorePhotos($profileid);
		if($hasAlbum==1)
			$smarty->assign("ISALBUM","1");

		
		if($main_photo_is_screened)
		{
			$smarty->assign("FULLVIEW","1");
			$album = SymfonyPictureFunctions::getAlbum($profileid,1);
			$profilePhotoUrl = $album['profile'];
			$smarty->assign("PHOTOFILE",$profilePhotoUrl);
		//Symfony Photo Modification ends
			
			// if the person is viewing his own profile
			if(!$PERSON_HIMSELF)
			{
				// if the user has chosen to hide the photo
				if($myrow["PHOTO_DISPLAY"]=="H")
				{
					$smarty->assign("PHOTOSTATUS","H");
				}
				// if the user has chosen to display photo conditionally then check for contact made and then decide which photo to show
				elseif($myrow["PHOTO_DISPLAY"]=="C")
				{
					$CHECK_FOR_PHOTO_CONTACT=1;
					$smarty->assign("PHOTOSTATUS","C");
				}
				elseif($myrow["PHOTO_DISPLAY"]=="F")
				{
					$CHECK_FOR_FILTERED=1;
					$smarty->assign("PHOTOSTATUS","F");
				}
			}
		}
		else 
			$smarty->assign("PHOTOFILE","../profile/images/photocomming.gif");
			
	}
	// main photo is being screened
	elseif($myrow["HAVEPHOTO"]=="U" || $myrow["HAVEPHOTO"]=="E")
	{
		$smarty->assign("PHOTOFILE","../profile/images/photocomming.gif");
	}
	// if the person is viewing his own profile and does not have a photo give him the option to upload photo
	else
	{
		if($PERSON_HIMSELF)
		{
			$upload_photo=1;
			$smarty->assign("UPLOADPHOTO",$upload_photo);
			$smarty->assign("PHOTOFILE","../profile/images/upload_photo1.gif");
		}
		else 
			$smarty->assign("PHOTOFILE","../profile/images/no_photo.gif");
	}
	
	/******************************************************
	check for photographs ends here
	******************************************************/
	$relationVal  =$myrow["RELATION"];
	$genderVal    =$myrow["GENDER"];	
	$contactperson=$myrow["USERNAME"];
	$smarty->assign("SOURCE",$myrow["SOURCE"]);
	$smarty->assign("PROFILENAME",$myrow["USERNAME"]);
	if(!in_array('ExcPrm',$agentPrivilege) && !in_array('ExcBSD',$agentPrivilege) && !in_array('ExcBID',$agentPrivilege) && !in_array('ExcFSD',$agentPrivilege) && !in_array('ExcFID',$agentPrivilege) && !in_array('ExcUpS',$agentPrivilege) && !in_array('ExcRnw',$agentPrivilege) && !in_array('ExcDOb',$agentPrivilege) && !in_array('ExcFP',$agentPrivilege) && !in_array('ExcDIb',$agentPrivilege) && !in_array('ExcFld',$agentPrivilege))
		$smarty->assign("RELATION",$RELATIONSHIP[$myrow["RELATION"]]);
	$smarty->assign("PROFILEGENDER",$GENDER[$myrow["GENDER"]]);
	$smarty->assign("MSTATUS",$MSTATUS[$myrow["MSTATUS"]]);
	$smarty->assign("CHILDREN",$CHILDREN[$myrow["HAVECHILD"]]);
	$smarty->assign("MANGLIK",$MANGLIK[$myrow["MANGLIK"]]);
	$smarty->assign("BODYTYPE",$BODYTYPE[$myrow["BTYPE"]]);
	$smarty->assign("COMPLEXION",$COMPLEXION[$myrow["COMPLEXION"]]);
	$smarty->assign("DIET",$DIET[$myrow["DIET"]]);
	$smarty->assign("SMOKE",$SMOKE[$myrow["SMOKE"]]);
	$smarty->assign("DRINK",$DRINK[$myrow["DRINK"]]);
	$smarty->assign("RSTATUS",$RSTATUS[$myrow["RES_STATUS"]]);
	$smarty->assign("HANDICAPPED",$HANDICAPPED[$myrow["HANDICAPPED"]]);
	//echo $myrow["GENDER"];
	$smarty->assign("GENDER",$myrow["GENDER"]);
	$smarty->assign("BLOOD_GROUP",$BLOOD_GROUP[$myrow["BLOOD_GROUP"]]);
	$smarty->assign("WEIGHT",$myrow["WEIGHT"]);
	$smarty->assign("HIV",$myrow["HIV"]);
	
	$height=$myrow["HEIGHT"];
	$height1=explode("(",$HEIGHT_DROP["$height"]);
	$smarty->assign("HEIGHT",$height1[0]);
	
	$caste=$myrow["CASTE"];
	$caste=$CASTE_DROP["$caste"];
	
	$mtongue=label_select("MTONGUE",$myrow["MTONGUE"]);
	$religion=label_select("RELIGION",$myrow["RELIGION"]);
	$income=label_select("INCOME",$myrow["INCOME"]);
	$edu_level=label_select("EDUCATION_LEVEL",$myrow["EDU_LEVEL"]);
	$edu_level_new=label_select("EDUCATION_LEVEL_NEW",$myrow["EDU_LEVEL_NEW"]);
	$family_back=label_select("FAMILY_BACK",$myrow["FAMILY_BACK"]);
	$rashi=label_select("RASHI",$myrow["RASHI"]);
	$occupation=$myrow["OCCUPATION"];
	$occupation=$OCCUPATION_DROP["$occupation"];
	
	$country_birth=$myrow["COUNTRY_BIRTH"];
	$country_birth=$COUNTRY_DROP["$country_birth"];
	
	$country_res=$myrow["COUNTRY_RES"];
	$country_res=$COUNTRY_DROP["$country_res"];
	
	$wife_working=$myrow["WIFE_WORKING"];
	if($wife_working=="Y")
		$smarty->assign("WORKINGSPOUSE","She should be working");
	elseif($wife_working=="N")
		$smarty->assign("WORKINGSPOUSE","She should be homemaker");
	elseif($wife_working=="D")
		$smarty->assign("WORKINGSPOUSE","Doesn't matter");
	elseif($wife_working=="")
		$smarty->assign("WORKINGSPOUSE","-");

	$married_working=$myrow["MARRIED_WORKING"];
	if($married_working=="Y")
		$smarty->assign("CAREER_AFTER_MARRIAGE","Plan to work after marriage.");
	else
		$smarty->assign("CAREER_AFTER_MARRIAGE","");
		
	$parents_city_same=$myrow["PARENT_CITY_SAME"];
	if($parents_city_same=="Y")
		$smarty->assign("LIVE_WITH_PARENTS","Yes");
	elseif($parents_city_same=="N")
		$smarty->assign("LIVE_WITH_PARENTS","No");
	elseif($parents_city_same=="D")
		$smarty->assign("LIVE_WITH_PARENTS","Not Applicable");
	elseif($parents_city_same=="")
		$smarty->assign("LIVE_WITH_PARENTS","-");
	$family_values=$myrow["FAMILY_VALUES"];
	if($family_values=="1")
		$smarty->assign("FAMILY_VALUES","Traditional");
	elseif($family_values=="2")
		$smarty->assign("FAMILY_VALUES","Moderate");
	elseif($family_values=="3")
		$smarty->assign("FAMILY_VALUES","Liberal");
	elseif($family_values=="")
		$smarty->assign("FAMILY_VALUES","-");
	$smarty->assign("WORK_STATUS",$WORK_STATUS[$myrow["WORK_STATUS"]]);
		
	if($caste=="")
		$caste="-";
		
	if($mtongue[0]=="")
		$mtongue[0]="-";
	
	if($religion[0]=="")
		$religion[0]="-";
	
	if($income[0]=="")
		$income[0]="-";
		
	if($edu_level[0]=="")
		$edu_level[0]="-";
		
	if($occupation=="")
		$occupation="-";
		
	if($country_birth=="")
		$country_birth="-";
		
	if($country_res=="")
		$country_res="-";
		
	if($myrow["COUNTRY_RES"]=="51")
	{
		$city_res=$myrow["CITY_RES"];
		$city_res=$CITY_DROP["$city_res"];
	}
	elseif($myrow["COUNTRY_RES"]=="128")
	{
		$city_res=$myrow["CITY_RES"];
		$city_res=$CITY_DROP["$city_res"];
	}
	else 
		$city_res="";
	
	$smarty->assign("PRIVACY",$myrow["PRIVACY"]);
	$smarty->assign("COUNTRY_BIRTH",$country_birth);
	$smarty->assign("COUNTRY_RES",$country_res);
	$smarty->assign("CITY_RES",$city_res);
	$smarty->assign("OCCUPATION",$occupation);
	$smarty->assign("EDUCATION_LEVEL",$edu_level[0]);
	if(!in_array('ExcPrm',$agentPrivilege) && !in_array('ExcBSD',$agentPrivilege) && !in_array('ExcBID',$agentPrivilege) && !in_array('ExcFSD',$agentPrivilege) && !in_array('ExcFID',$agentPrivilege) && !in_array('ExcUpS',$agentPrivilege) && !in_array('ExcRnw',$agentPrivilege) && !in_array('ExcDOb',$agentPrivilege) && !in_array('ExcFP',$agentPrivilege) && !in_array('ExcDIb',$agentPrivilege) && !in_array('ExcFld',$agentPrivilege))
		$smarty->assign("INCOME",$income[0]);
	$smarty->assign("RELIGION_SELF",$religion[0]);
	//////Display fields according to religion added by Vibhor///////
	if($religion[0] == 'Hindu')
	{
		$smarty->assign("RASHI",$rashi[0]);
		$smarty->assign("NATIVE_PLACE",$myrow["ANCESTRAL_ORIGIN"]);
		$smarty->assign("HOROSCOPE_MATCH",$myrow["HOROSCOPE_MATCH"]);
	}
	elseif($religion[0] == 'Jain')
	{
		$sql_jain = "SELECT SAMPRADAY FROM newjs.JP_JAIN WHERE PROFILEID='$profileid'";
		$res_jain=mysql_query_decide($sql_jain) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jain,"ShowErrTemplate");
		$row_jain=mysql_fetch_array($res_jain);
		$smarty->assign("SAMPRADAY",$SAMPRADAY[$row_jain['SAMPRADAY']]);
	}
	elseif($religion[0] == 'Christian')
	{
		$sql_christian = "SELECT * FROM newjs.JP_CHRISTIAN WHERE PROFILEID='$profileid'";
		$res_christian=mysql_query_decide($sql_christian) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_christian,"ShowErrTemplate");
		$row_christian=mysql_fetch_array($res_christian);
		$smarty->assign("DIOCESE",$row_christian['DIOCESE']);
		$smarty->assign("BAPTISED",$row_christian['BAPTISED']);
		$smarty->assign("READ_BIBLE",$row_christian['READ_BIBLE']);
		$smarty->assign("OFFER_TITHE",$row_christian['OFFER_TITHE']);
		$smarty->assign("SPREADING_GOSPEL",$row_christian['SPREADING_GOSPEL']);
        }
	elseif($religion[0] == 'Muslim')
	{
		$sql_muslim = "SELECT * FROM newjs.JP_MUSLIM WHERE PROFILEID='$profileid'";
		$res_muslim=mysql_query_decide($sql_muslim) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_muslim,"ShowErrTemplate");
		$row_muslim=mysql_fetch_array($res_muslim);
		$math_val = $row_muslim['MATHTHAB'];
		if($caste == "Muslim: Sunni")
			$smarty->assign("MATHTHAB",$MATHTHAB_SUNNI[$math_val]);
		elseif($caste == "Muslim: Shia")
			$smarty->assign("MATHTHAB",$MATHTHAB_SHIA[$math_val]);
		$smarty->assign("SPEAK_URDU",$jprofile_result["viewed"]["SPEAK_URDU"]);
		$smarty->assign("NAMAZ",$NAMAZ[$row_muslim['NAMAZ']]);
		$smarty->assign("ZAKAT",$row_muslim['ZAKAT']);
		$smarty->assign("FASTING",$FASTING[$row_muslim['FASTING']]);
		$smarty->assign("QURAN",$QURAN[$row_muslim['QURAN']]);
		$smarty->assign("UMRAH_HAJJ",$UMRAH_HAJJ[$row_muslim['UMRAH_HAJJ']]);
		$smarty->assign("SUNNAH_BEARD",$SUNNAH_BEARD[$row_muslim['SUNNAH_BEARD']]);
		$smarty->assign("SUNNAH_CAP",$SUNNAH_CAP[$row_muslim['SUNNAH_CAP']]);
		$smarty->assign("HIJAB",$row_muslim['HIJAB']);
		$smarty->assign("HIJAB_MARRIAGE",$row_muslim['HIJAB_MARRIAGE']);
		$smarty->assign("WORKING_MARRIAGE",$row_muslim['WORKING_MARRIAGE']);
        }
	elseif($religion[0] == 'Sikh')
	{
		$sql_sikh = "SELECT * FROM newjs.JP_SIKH WHERE PROFILEID='$profileid'";
		$res_sikh= mysql_query_decide($sql_sikh) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sikh,"ShowErrTemplate");
		$row_sikh=mysql_fetch_array($res_sikh);
		$smarty->assign("AMRITDHARI",$row_sikh['AMRITDHARI']);
		$smarty->assign("CUT_HAIR",$row_sikh['CUT_HAIR']);
		$smarty->assign("TRIM_BEARD",$row_sikh['TRIM_BEARD']);
		$smarty->assign("WEAR_TURBAN",$row_sikh['WEAR_TURBAN']);
		$smarty->assign("CLEAN_SHAVEN",$row_sikh['CLEAN_SHAVEN']);
	}
	elseif($religion[0] == 'Parsi')
	{
		$sql_parsi = "SELECT * FROM newjs.JP_PARSI WHERE PROFILEID='$profileid'";
		$res_parsi= mysql_query_decide($sql_parsi) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_parsi,"ShowErrTemplate");
		$row_parsi=mysql_fetch_array($res_parsi);
		$smarty->assign("ZARATHUSHTRI",$row_parsi['ZARATHUSHTRI']);
		$smarty->assign("PARENTS_ZARATHUSHTRI",$row_parsi['PARENTS_ZARATHUSHTRI']);
	}
	////////////
	$smarty->assign("MTONGUE",$mtongue[0]);
	$smarty->assign("CASTE",$caste);
	$smarty->assign("EDU_LEVEL_NEW",$edu_level_new[0]);
	$smarty->assign("FAMILY_BACK",$family_back[0]);
	$smarty->assign("FAMILY_TYPE",$FAMILY_TYPE[$myrow['FAMILY_TYPE']]);
        $smarty->assign("FAMILY_STATUS",$FAMILY_STATUS[$myrow['FAMILY_STATUS']]);
	$smarty->assign("MOTHER_OCC",$MOTHER_OCC_DROP[$myrow['MOTHER_OCC']]);
        $smarty->assign("T_BROTHER",$myrow['T_BROTHER']);
        $smarty->assign("M_BROTHER",$myrow['M_BROTHER']);
        $smarty->assign("T_SISTER",$myrow['T_SISTER']);
        $smarty->assign("M_SISTER",$myrow['M_SISTER']);
	if($myrow["BTIME"]!="" && $myrow["BTIME"]!="00:00")
	{
		$btime=explode(":",$myrow["BTIME"]);
		$smarty->assign("BTIMEHOUR",$btime[0]);
		$smarty->assign("BTIMEMIN",$btime[1]);
	}
	
	// Added in Symfony Project
	$screening = $myrow["SCREENING"];
	
	if($myrow["CITY_BIRTH"]=="")
		$smarty->assign("CITYBIRTH","-");
	elseif(isFlagSet("CITYBIRTH",$screening))
		$smarty->assign("CITYBIRTH",ucwords($myrow["CITY_BIRTH"]));
	elseif($PERSON_HIMSELF) 
		$smarty->assign("CITYBIRTH",ucwords($myrow["CITY_BIRTH"]) . "<br>" . $SCREENING_MESSAGE_SELF);
	else
	{	
		if ($company!='IV' || $user_login == 1) 
			$smarty->assign("CITYBIRTH",ucwords($myrow["CITY_BIRTH"]) . "<br>" . $SCREENING_MESSAGE);
	}
		
	if($myrow["SUBCASTE"]=="")
		$smarty->assign("SUBCASTE","-");
	elseif(isFlagSet("SUBCASTE",$screening))
		$smarty->assign("SUBCASTE",$myrow["SUBCASTE"]);
	elseif($PERSON_HIMSELF) 
		$smarty->assign("SUBCASTE",$myrow["SUBCASTE"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else
	{
		if ($company =='IV' && $user_login != 1)
			$smarty->assign("SUBCASTE",$SCREENING_MESSAGE);
		else
			$smarty->assign("SUBCASTE",$myrow["SUBCASTE"] . "<br>" . $SCREENING_MESSAGE);
	}

	if(trim($myrow["YOURINFO"]))
	{
		$yourinfo1=trim($myrow["YOURINFO"]);
		$len=strlen($yourinfo1);
		$flag=0;
		for($i=0;$i<$len;$i++)
		{
			if($yourinfo1[$i]==' ')
			{
				$flag++;
			}
			if($flag<3)
			{
				$subyourinfo.=$yourinfo1[$i];
			}
			else
			{
				$yourinfo.=$yourinfo1[$i];
				$flag++;
			}
		}
	}
	if($relationVal=='1'){
		$about1 ="About me";
		$about2 ="About my";
	}	
	else{
		if($genderVal=='M'){
			$about1 ="About him";
			$about2 ="About his";
		}
		else
			$about1 = $about2 ="About her";
	}
	$smarty->assign("about1",$about1);
	$smarty->assign("about2",$about2);

	if(!isFlagSet("YOURINFO",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $yourinfo = $SCREENING_MESSAGE;
                else
			$yourinfo.="<br>".$SCREENING_MESSAGE;
	}
	if ($company!= 'IV' || $user_login == 1)
		$smarty->assign("SUBYOURINFO","<b>$about1: ".$subyourinfo."</b>");
	
	if(trim($myrow["JOB_INFO"]))
		$yourinfo.="\n\n<b>$about2 work:</b> ".$myrow["JOB_INFO"];

	if(!isFlagSet("JOB_INFO",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $yourinfo = $SCREENING_MESSAGE;
                else
			$yourinfo.="<br>".$SCREENING_MESSAGE;
	}
	if(trim($myrow["SPOUSE"]))
		$yourinfo.="\n\n<b>$about2 Desired Partner Profile: </b> ".$myrow["SPOUSE"];

	if(!isFlagSet("SPOUSE",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $yourinfo = $SCREENING_MESSAGE;
                else
			$yourinfo.="<br>".$SCREENING_MESSAGE;
	}

	$smarty->assign("YOURINFO",nl2br($yourinfo));

	if(trim($myrow["FATHER_INFO"]))
		$familyinfo=$myrow["FATHER_INFO"];

	if(!isFlagSet("FATHER_INFO",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $familyinfo = $SCREENING_MESSAGE;
                else
			$familyinfo.="<br>".$SCREENING_MESSAGE;
	}

	if(trim($myrow["SIBLING_INFO"]))
		$familyinfo.=$myrow["SIBLING_INFO"];

	if(!isFlagSet("SIBLING_INFO",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $familyinfo =$SCREENING_MESSAGE;
                else
			$familyinfo.="<br>".$SCREENING_MESSAGE;
	}

	if(trim($myrow["FAMILYINFO"]))
		$familyinfo.=$myrow["FAMILYINFO"];

	if(!isFlagSet("FAMILYINFO",$screening))
	{
		if ($company== 'IV' && $user_login != 1)
                        $familyinfo=$SCREENING_MESSAGE;
                else
			$familyinfo.="<br>".$SCREENING_MESSAGE;
	}

	$smarty->assign("FAMILYINFO",nl2br($familyinfo));

	if($myrow["GOTHRA"]=="")
		$smarty->assign("GOTHRA","-");
	elseif(isFlagSet("GOTHRA",$screening))
		$smarty->assign("GOTHRA",$myrow["GOTHRA"]);
	elseif($PERSON_HIMSELF) 
		$smarty->assign("GOTHRA",$myrow["GOTHRA"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else
	{
		if ($company== 'IV' && $user_login != 1)
			$smarty->assign("GOTHRA",$SCREENING_MESSAGE);
		else
			$smarty->assign("GOTHRA",$myrow["GOTHRA"] . "<br>" . $SCREENING_MESSAGE);
	}
		
	if($myrow["NAKSHATRA"]=="")
		$smarty->assign("NAKSHATRA","-");
	elseif(isFlagSet("NAKSHATRA",$screening))
		$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);
	elseif($PERSON_HIMSELF) 
		$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else
	{
		if ($company== 'IV' && $user_login != 1)
			$smarty->assign("NAKSHATRA",$SCREENING_MESSAGE);
		else
			$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"] . "<br>" . $SCREENING_MESSAGE);
	}

	if($myrow["EDUCATION"]=="")
		$smarty->assign("EDUCATION","-");
	elseif(isFlagSet("EDUCATION",$screening))
		$smarty->assign("EDUCATION",nl2br($myrow["EDUCATION"]));
	elseif($PERSON_HIMSELF) 
		$smarty->assign("EDUCATION",nl2br($myrow["EDUCATION"]) . "<br>" . $SCREENING_MESSAGE_SELF);
	else 
	{
		if ($company=='IV' && $user_login != 1)
			$smarty->assign("EDUCATION",$SCREENING_MESSAGE);
		else
			$smarty->assign("EDUCATION",nl2br($myrow["EDUCATION"]) . "<br>" . $SCREENING_MESSAGE);
	}
	
        //*****************************************  JS Social project. New fields added for JPROFILE start  ****************************

        if($myrow["PROFILE_HANDLER_NAME"]=="")
                $smarty->assign("PROFILE_HANDLER_NAME","-");
        elseif(isFlagSet("PROFILE_HANDLER_NAME",$screening))
                $smarty->assign("PROFILE_HANDLER_NAME",nl2br($myrow["PROFILE_HANDLER_NAME"]));
        elseif($PERSON_HIMSELF)
                $smarty->assign("PROFILE_HANDLER_NAME",nl2br($myrow["PROFILE_HANDLER_NAME"]) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
        {
                if ($company=='IV' && $user_login != 1)
                        $smarty->assign("PROFILE_HANDLER_NAME",$SCREENING_MESSAGE);
                else
                        $smarty->assign("PROFILE_HANDLER_NAME",nl2br($myrow["PROFILE_HANDLER_NAME"]) . "<br>" . $SCREENING_MESSAGE);
        }

        if($myrow["PARENT_PINCODE"]=="")
                $smarty->assign("PARENT_PINCODE","-");
        elseif(isFlagSet("PARENT_PINCODE",$screening))
                $smarty->assign("PARENT_PINCODE",nl2br($myrow["PARENT_PINCODE"]));
        elseif($PERSON_HIMSELF)
                $smarty->assign("PARENT_PINCODE",nl2br($myrow["PARENT_PINCODE"]) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
        {
                if ($company=='IV' && $user_login != 1)
                        $smarty->assign("PARENT_PINCODE",$SCREENING_MESSAGE);
                else
                        $smarty->assign("PARENT_PINCODE",nl2br($myrow["PARENT_PINCODE"]) . "<br>" . $SCREENING_MESSAGE);
        }

        if($myrow["GOTHRA_MATERNAL"]=="")
                $smarty->assign("GOTHRA_MATERNAL","-");
        elseif(isFlagSet("GOTHRA_MATERNAL",$screening))
                $smarty->assign("GOTHRA_MATERNAL",nl2br($myrow["GOTHRA_MATERNAL"]));
        elseif($PERSON_HIMSELF)
                $smarty->assign("GOTHRA_MATERNAL",nl2br($myrow["GOTHRA_MATERNAL"]) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
        {
                if ($company=='IV' && $user_login != 1)
                        $smarty->assign("GOTHRA_MATERNAL",$SCREENING_MESSAGE);
                else
                        $smarty->assign("GOTHRA_MATERNAL",nl2br($myrow["GOTHRA_MATERNAL"]) . "<br>" . $SCREENING_MESSAGE);
        }

        if($myrow["COMPANY_NAME"]=="")
                $smarty->assign("COMPANY_NAME","-");
        elseif(isFlagSet("COMPANY_NAME",$screening))
                $smarty->assign("COMPANY_NAME",nl2br($myrow["COMPANY_NAME"]));
        elseif($PERSON_HIMSELF)
                $smarty->assign("COMPANY_NAME",nl2br($myrow["COMPANY_NAME"]) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
        {
                if ($company=='IV' && $user_login != 1)
                        $smarty->assign("COMPANY_NAME",$SCREENING_MESSAGE);
                else
                        $smarty->assign("COMPANY_NAME",nl2br($myrow["COMPANY_NAME"]) . "<br>" . $SCREENING_MESSAGE);
        }

        // Label scenario start
        if($myrow["THALASSEMIA"]=="")
                $smarty->assign("THALASSEMIA","-");
        else
        {
                $thalassemia =FieldMap::getFieldLabel('thalassemia',$myrow["THALASSEMIA"]);
                $smarty->assign("THALASSEMIA",nl2br($thalassemia));
        }

        if($myrow["GOING_ABROAD"]=="")
                $smarty->assign("GOING_ABROAD","-");
        else
        {
                $going_abroad =FieldMap::getFieldLabel('going_abroad',$myrow["GOING_ABROAD"]);
                $smarty->assign("GOING_ABROAD",nl2br($going_abroad));
        }

        if($myrow["OPEN_TO_PET"]=="")
                $smarty->assign("OPEN_TO_PET","-");
        else
        {
                $open_to_pet =FieldMap::getFieldLabel('open_to_pet',$myrow["OPEN_TO_PET"]);
                $smarty->assign("OPEN_TO_PET",nl2br($open_to_pet));
        }

        if($myrow["HAVE_CAR"]=="")
                $smarty->assign("HAVE_CAR","-");
        else
        {
                $have_car =FieldMap::getFieldLabel('have_car',$myrow["HAVE_CAR"]);
                $smarty->assign("HAVE_CAR",nl2br($have_car));
        }

        if($myrow["OWN_HOUSE"]=="")
                $smarty->assign("OWN_HOUSE","-");
        else
        {
                $own_house =FieldMap::getFieldLabel('own_house',$myrow["OWN_HOUSE"]);
                $smarty->assign("OWN_HOUSE",nl2br($own_house));
        }

	if(!in_array('ExcPrm',$agentPrivilege) && !in_array('ExcBSD',$agentPrivilege) && !in_array('ExcBID',$agentPrivilege) && !in_array('ExcFSD',$agentPrivilege) && !in_array('ExcFID',$agentPrivilege) && !in_array('ExcUpS',$agentPrivilege) && !in_array('ExcRnw',$agentPrivilege) && !in_array('ExcDOb',$agentPrivilege) && !in_array('ExcFP',$agentPrivilege) && !in_array('ExcDIb',$agentPrivilege) && !in_array('ExcFld',$agentPrivilege)) {
		if($myrow["FAMILY_INCOME"]=="")
			$smarty->assign("FAMILY_INCOME","-");
		else
		{
			$family_income =FieldMap::getFieldLabel('income_level',$myrow["FAMILY_INCOME"]);
			$smarty->assign("FAMILY_INCOME",nl2br($family_income));
		}
	}
        if($myrow["SECT"]=="")
                $smarty->assign("SECT","-");
        else
        {
                $SECT_VAL =$myrow["SECT"];
                $sql_sect ="select LABEL from newjs.SECT where VALUE='$SECT_VAL'";
                $result_sect=mysql_query_decide($sql_sect) or die("3".mysql_error_js());
                if($myrow_sect=mysql_fetch_array($result_sect))
                {
                        $sectVal =$myrow_sect["LABEL"];
                }
                $smarty->assign("SECT",nl2br($sectVal));
        }
        //***************************************  JS Social project. New fields for JPROFILE Ends         **************************************


        //***************************************  JS Social project. New fields for ASTRO_DETAILS Start   **************************************
        $sun_sign =$myrow['SUNSIGN'];
        if($sun_sign)
        {
                $sunsign_label =FieldMap::getFieldLabel('sunsign',$sun_sign);
                $smarty->assign("SUNSIGN",$sunsign_label);
        }
        else
                $smarty->assign("SUNSIGN","-");

        //**************************************  JS Social project. New fields for ASTRO_DETAILS Ends     ****************************************     

        //**************************************  JS Social project. New fields for JRPFILE_CONTACT Start  *****************************************
        if($myrow["HAVE_JCONTACT"]=='Y')
        {
                $sql_jcontact ="select * from newjs.JPROFILE_CONTACT where PROFILEID='$profileid'";
                $result_jcontact=mysql_query_decide($sql_jcontact) or die("3".mysql_error_js());
                if($myrow_jcontact=mysql_fetch_array($result_jcontact))
                {
                        if($myrow_jcontact["SHOWALT_MOBILE"]=='Y')
                        {
                                if($myrow_jcontact["ALT_MOBILE"]=="")
                                        $smarty->assign("ALT_MOBILE","-");
                                elseif(isFlagSet("ALT_MOBILE",$screening))
                                        $smarty->assign("ALT_MOBILE",$myrow_jcontact["ALT_MOBILE"]);
                                elseif($PERSON_HIMSELF)
                                        $smarty->assign("ALT_MOBILE",$myrow_jcontact["ALT_MOBILE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                                else
                                {
                                        if ($company== 'IV' && $user_login != 1)
                                                $smarty->assign("ALT_MOBILE",$SCREENING_MESSAGE);
                                        else
                                                $smarty->assign("ALT_MOBILE",$myrow_jcontact["ALT_MOBILE"] . "<br>" . $SCREENING_MESSAGE);
                                }

                        }
			$smarty->assign("SHOWALT_MOBILE",$myrow_jcontact["SHOWALT_MOBILE"]);
	
                        if($myrow_jcontact["SHOW_ALT_MESSENGER"]=='Y')
                        {
                                if($myrow_jcontact["ALT_MESSENGER_ID"]=="")
                                        $smarty->assign("ALT_MESSENGER_ID","-");
                                elseif(isFlagSet("ALT_MESSENGER_ID",$screening))
                                        $smarty->assign("ALT_MESSENGER_ID",$myrow_jcontact["ALT_MESSENGER_ID"]);
                                elseif($PERSON_HIMSELF)
                                        $smarty->assign("ALT_MESSENGER_ID",$myrow_jcontact["ALT_MESSENGER_ID"] . "<br>" . $SCREENING_MESSAGE_SELF);
                                else
                                {
                                        if ($company== 'IV' && $user_login != 1)
                                                $smarty->assign("ALT_MESSENGER_ID",$SCREENING_MESSAGE);
                                        else
                                                $smarty->assign("ALT_MESSENGER_ID",$myrow_jcontact["ALT_MESSENGER_ID"] . "<br>" . $SCREENING_MESSAGE);
                                }

                                if($myrow_jcontact["ALT_MESSENGER_CHANNEL"]=="")
                                        $smarty->assign("ALT_MESSENGER_CHANNEL","-");
				else{
					$alt_messenger_channel =$myrow_jcontact["ALT_MESSENGER_CHANNEL"];	
					$alt_messenger_channel =$MESSENGER_CHANNEL["$alt_messenger_channel"];
				}
                                if(isFlagSet("ALT_MESSENGER_CHANNEL",$screening))
                                        $smarty->assign("ALT_MESSENGER_CHANNEL",$alt_messenger_channel);
                                elseif($PERSON_HIMSELF)
                                        $smarty->assign("ALT_MESSENGER_CHANNEL",$alt_messenger_channel."<br>" . $SCREENING_MESSAGE_SELF);
                                else
                                {
                                        if ($company== 'IV' && $user_login != 1)
                                                $smarty->assign("ALT_MESSENGER_CHANNEL",$SCREENING_MESSAGE);
                                        else
                                                $smarty->assign("ALT_MESSENGER_CHANNEL",$alt_messenger_channel."<br>" . $SCREENING_MESSAGE);
                                }
                        }
			$smarty->assign("SHOW_ALT_MESSENGER",$myrow_jcontact["SHOW_ALT_MESSENGER"]);
			
                }
        }

        //*************************************** JS Social project. New fields for JRPFILE_CONTACT Ends    *************************************


        //*************************************** JS Social project. New fields of JPROFILE_EDUCATION Start **************************************

        if($myrow["HAVE_JEDUCATION"]=='Y')
        {
                $sql_ed="select * from newjs.JPROFILE_EDUCATION where PROFILEID='$profileid'";
                $result_ed=mysql_query_decide($sql_ed) or die("3".mysql_error_js());
                if($myrow_ed=mysql_fetch_array($result_ed))
                {
                        if($myrow_ed["PG_COLLEGE"]=="")
                                $smarty->assign("PG_COLLEGE","-");
                        elseif(isFlagSet("PG_COLLEGE",$screening))
                                $smarty->assign("PG_COLLEGE",$myrow_ed["PG_COLLEGE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("PG_COLLEGE",$myrow_ed["PG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("PG_COLLEGE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("PG_COLLEGE",$myrow_ed["PG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE);
                        }

                        /*****************************
                        $PG_DEGREE=$myrow_ed["PG_DEGREE"];
                        *****************************/

                        if($myrow_ed["UG_DEGREE"]=="")
                                $smarty->assign("UG_DEGREE","-");
                        else
                        {
                                $ug_degree =FieldMap::getFieldLabel('education',$myrow_ed["UG_DEGREE"]);
                                $smarty->assign("UG_DEGREE",nl2br($ug_degree));
                        }

                        if($myrow_ed["PG_DEGREE"]=="")
                                $smarty->assign("PG_DEGREE","-");
                        else
                        {
                                $pg_degree =FieldMap::getFieldLabel('education',$myrow_ed["PG_DEGREE"]);
                                $smarty->assign("PG_DEGREE",nl2br($pg_degree));
                        }

                        if($myrow_ed["SCHOOL"]=="")
                                $smarty->assign("SCHOOL","-");
                        elseif(isFlagSet("SCHOOL",$screening))
                                $smarty->assign("SCHOOL",$myrow_ed["SCHOOL"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("SCHOOL",$myrow_ed["SCHOOL"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("SCHOOL",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("SCHOOL",$myrow_ed["SCHOOL"] . "<br>" . $SCREENING_MESSAGE);
                        }

                        if($myrow_ed["COLLEGE"]=="")
                                $smarty->assign("COLLEGE","-");
                        elseif(isFlagSet("COLLEGE",$screening))
                                $smarty->assign("COLLEGE",$myrow_ed["COLLEGE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("COLLEGE",$myrow_ed["COLLEGE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("COLLEGE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("COLLEGE",$myrow_ed["COLLEGE"] . "<br>" . $SCREENING_MESSAGE);
                        }

                        if($myrow_ed["OTHER_UG_COLLEGE"]=="")
                                $smarty->assign("OTHER_UG_COLLEGE","-");
                        elseif(isFlagSet("OTHER_UG_COLLEGE",$screening))
                                $smarty->assign("OTHER_UG_COLLEGE",$myrow_ed["OTHER_UG_COLLEGE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("OTHER_UG_COLLEGE",$myrow_ed["OTHER_UG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("OTHER_UG_COLLEGE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("OTHER_UG_COLLEGE",$myrow_ed["OTHER_UG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE);
                        }

                        if($myrow_ed["OTHER_UG_DEGREE"]=="")
                                $smarty->assign("OTHER_UG_DEGREE","-");
                        elseif(isFlagSet("OTHER_UG_DEGREE",$screening))
                                $smarty->assign("OTHER_UG_DEGREE",$myrow_ed["OTHER_UG_DEGREE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("OTHER_UG_DEGREE",$myrow_ed["OTHER_UG_DEGREE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("OTHER_UG_DEGREE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("OTHER_UG_DEGREE",$myrow_ed["OTHER_UG_DEGREE"] . "<br>" . $SCREENING_MESSAGE);
                        }

                        if($myrow_ed["OTHER_PG_COLLEGE"]=="")
                                $smarty->assign("OTHER_PG_COLLEGE","-");
                        elseif(isFlagSet("OTHER_PG_COLLEGE",$screening))
                                $smarty->assign("OTHER_PG_COLLEGE",$myrow_ed["OTHER_PG_COLLEGE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("OTHER_PG_COLLEGE",$myrow_ed["OTHER_PG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("OTHER_PG_COLLEGE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("OTHER_PG_COLLEGE",$myrow_ed["OTHER_PG_COLLEGE"] . "<br>" . $SCREENING_MESSAGE);
                        }


                        if($myrow_ed["OTHER_PG_DEGREE"]=="")
                                $smarty->assign("OTHER_PG_DEGREE","-");
                        elseif(isFlagSet("OTHER_PG_DEGREE",$screening))
                                $smarty->assign("OTHER_PG_DEGREE",$myrow_ed["OTHER_PG_DEGREE"]);
                        elseif($PERSON_HIMSELF)
                                $smarty->assign("OTHER_PG_DEGREE",$myrow_ed["OTHER_PG_DEGREE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                        else
                        {
                                if ($company== 'IV' && $user_login != 1)
                                        $smarty->assign("OTHER_PG_DEGREE",$SCREENING_MESSAGE);
                                else
                                        $smarty->assign("OTHER_PG_DEGREE",$myrow_ed["OTHER_PG_DEGREE"] . "<br>" . $SCREENING_MESSAGE);
                        }
                }
        }
        // ******************************** JS Social Project  New fields of JPROFILE_EDUCATION Ends **********************

	$sql="select SQL_CACHE PROFILEID from newjs.HIDE_DOB where PROFILEID='$profileid'";
	$hideresult=mysql_query_decide($sql);

	$dob=explode("-",substr($myrow["PHOTODATE"],0,10));
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
		$smarty->assign("PHOTODATE",my_format_date($dob[2],$dob[1],$dob[0]));
	unset($dob);
	
	if ($hideresult && mysql_num_rows($hideresult)<=0)
	{
		$dob=explode("-",$myrow["DTOFBIRTH"]);
		$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
		
		unset($dob);
	}
	
	$dob=explode("-",substr($myrow["MOD_DT"],0,10));
	
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
		$smarty->assign("MOD_DATE",my_format_date($dob[2],$dob[1],$dob[0]));
	
	unset($dob);
	$dob=explode("-",substr($myrow["ENTRY_DT"],0,10));

        if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
                $smarty->assign("ENTRY_DATE",my_format_date($dob[2],$dob[1],$dob[0]));

	unset($dob);
	
	$dob=explode("-",substr($myrow["MOD_DT"],0,10));
	
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
		$smarty->assign("MOD_DATE",my_format_date($dob[2],$dob[1],$dob[0]));
	
	unset($dob);

	$dob=explode("-",$myrow["LAST_LOGIN_DT"]);
	
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
		$smarty->assign("LAST_LOGIN_DT",my_format_date($dob[2],$dob[1],$dob[0]));
	

	/****************************************************************************
	Hobbies section starts here
	****************************************************************************/
	
	$sql="select * from newjs.JHOBBY where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die("3".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		
		$sql="select SQL_CACHE VALUE,LABEL,TYPE from newjs.HOBBIES order by SORTBY";
		$result_hobby=mysql_query_decide($sql) or die("4".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		while($myhobby=mysql_fetch_array($result_hobby))
		{
			$HOBBIES_ARR[$myhobby["VALUE"]]=array("LABEL" => $myhobby["LABEL"],
								"TYPE" => $myhobby["TYPE"]);
		}
		
		mysql_free_result($result_hobby);
		
		$myhobbies=explode(",",$myrow["HOBBY"]);
		
		$hobbycount=count($myhobbies);
		
		for($i=0;$i<$hobbycount;$i++)
		{
			$label=$HOBBIES_ARR[$myhobbies[$i]]["LABEL"];
			$type=$HOBBIES_ARR[$myhobbies[$i]]["TYPE"];
			
			${$type}[]=$label;
		}
		
		if(is_array($HOBBY))
			$smarty->assign("HOBBY",implode(", ",$HOBBY));
			
		if(is_array($INTEREST))
			$smarty->assign("INTEREST",implode(", ",$INTEREST));
			
		if(is_array($MUSIC))
			$smarty->assign("MUSIC",implode(", ",$MUSIC));
			
		if(is_array($BOOK))
			$smarty->assign("BOOK",implode(", ",$BOOK));
			
		if(is_array($MOVIE))
			$smarty->assign("MOVIE",implode(", ",$MOVIE));
			
		if(is_array($SPORTS))
			$smarty->assign("SPORTS",implode(", ",$SPORTS));
			
		if(is_array($CUISINE))
			$smarty->assign("CUISINE",implode(", ",$CUISINE));
			
		if(is_array($DRESS))
			$smarty->assign("DRESS",implode(", ",$DRESS));
			
		if(is_array($LANGUAGE))
			$smarty->assign("LANGUAGE",implode(", ",$LANGUAGE));
			
                //********************************* JS Social Project. New fields in JHOBBY for Start ****************************************
                if($myrow["FAV_MOVIE"]=="")
                        $smarty->assign("FAV_MOVIE","-");
                elseif(isFlagSet("FAV_MOVIE",$screening))
                        $smarty->assign("FAV_MOVIE",$myrow["FAV_MOVIE"]);
                elseif($PERSON_HIMSELF)
                        $smarty->assign("FAV_MOVIE",$myrow["FAV_MOVIE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                {
                        if ($company== 'IV' && $user_login != 1)
                                $smarty->assign("FAV_MOVIE",$SCREENING_MESSAGE);
                        else
                                $smarty->assign("FAV_MOVIE",$myrow["FAV_MOVIE"] . "<br>" . $SCREENING_MESSAGE);
                }

                if($myrow["FAV_TVSHOW"]=="")
                        $smarty->assign("FAV_TVSHOW","-");
                elseif(isFlagSet("FAV_TVSHOW",$screening))
                        $smarty->assign("FAV_TVSHOW",$myrow["FAV_TVSHOW"]);
                elseif($PERSON_HIMSELF)
                        $smarty->assign("FAV_TVSHOW",$myrow["FAV_TVSHOW"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                {
                        if ($company== 'IV' && $user_login != 1)
                                $smarty->assign("FAV_TVSHOW",$SCREENING_MESSAGE);
                        else
                                $smarty->assign("FAV_TVSHOW",$myrow["FAV_TVSHOW"] . "<br>" . $SCREENING_MESSAGE);
                }

                if($myrow["FAV_FOOD"]=="")
                        $smarty->assign("FAV_FOOD","-");
                elseif(isFlagSet("FAV_FOOD",$screening))
                        $smarty->assign("FAV_FOOD",$myrow["FAV_FOOD"]);
                elseif($PERSON_HIMSELF)
                        $smarty->assign("FAV_FOOD",$myrow["FAV_FOOD"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                {
                        if ($company== 'IV' && $user_login != 1)
                                $smarty->assign("FAV_FOOD",$SCREENING_MESSAGE);
                        else
                                $smarty->assign("FAV_FOOD",$myrow["FAV_FOOD"] . "<br>" . $SCREENING_MESSAGE);
                }
                if($myrow["FAV_BOOK"]=="")
                        $smarty->assign("FAV_BOOK","-");
                elseif(isFlagSet("FAV_BOOK",$screening))
                        $smarty->assign("FAV_BOOK",$myrow["FAV_BOOK"]);
                elseif($PERSON_HIMSELF)
                        $smarty->assign("FAV_BOOK",$myrow["FAV_BOOK"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                {
                        if ($company== 'IV' && $user_login != 1)
                                $smarty->assign("FAV_BOOK",$SCREENING_MESSAGE);
                        else
                                $smarty->assign("FAV_BOOK",$myrow["FAV_BOOK"] . "<br>" . $SCREENING_MESSAGE);
                }

                if($myrow["FAV_VAC_DEST"]=="")
                        $smarty->assign("FAV_VAC_DEST","-");
                elseif(isFlagSet("FAV_VAC_DEST",$screening))
                        $smarty->assign("FAV_VAC_DEST",$myrow["FAV_VAC_DEST"]);
                elseif($PERSON_HIMSELF)
                        $smarty->assign("FAV_VAC_DEST",$myrow["FAV_VAC_DEST"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                {
                        if ($company== 'IV' && $user_login != 1)
                                $smarty->assign("FAV_VAC_DEST",$SCREENING_MESSAGE);
                        else
                                $smarty->assign("FAV_VAC_DEST",$myrow["FAV_VAC_DEST"] . "<br>" . $SCREENING_MESSAGE);
                }
                //******************************* JS Social Project. New fields in JHOBBY Ends  ****************************

		if($myrow["ALLMUSIC"]=="N")
			$smarty->assign("MUSIC","Not too keen on music");
		elseif($myrow["ALLMUSIC"]=="Y")
			$smarty->assign("MUSIC","Enjoy most forms of music");
			
		if($myrow["ALLBOOK"]=="N")
			$smarty->assign("BOOK","Not much of a reader");
		elseif($myrow["ALLBOOK"]=="Y")
			$smarty->assign("BOOK","Love reading almost anything");
			
		if($myrow["ALLMOVIE"]=="N")
			$smarty->assign("MOVIE","Not a movie buff");
		elseif($myrow["ALLMOVIE"]=="Y")
			$smarty->assign("MOVIE","Love all kinds of movies");
			
		if($myrow["ALLSPORTS"]=="N")
			$smarty->assign("SPORTS","Not a sportsperson");
			
		if($myrow["ALLCUISINE"]=="N")
			$smarty->assign("CUISINE","Not much of a food-lover");
		elseif($myrow["ALLCUISINE"]=="Y")
			$smarty->assign("CUISINE","Anything edible is great!");
			
	}
	else 
	{
		$smarty->assign("NOHOBBY","1");
	}

	mysql_free_result($result);
	
	/*************************************************************************
	Hobbies section ends here
	*************************************************************************/
	
	/*************************************************************************
	Partner Profile section starts here
	*************************************************************************/

	/***************Sharding concept done by Sadaf : start*******************/

	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");

	$jpartnerObj=new Jpartner;
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
	{
		$HAVE_PARTNER=true;
		if(($jpartnerObj->getLAGE())!="" && ($jpartnerObj->getHAGE())!="")
                {
                        $FILTER_LAGE=$jpartnerObj->getLAGE();
                        $FILTER_HAGE=$jpartnerObj->getHAGE();
                        $smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE()." to ".$jpartnerObj->getHAGE());
                }
                else 
                        $smarty->assign("PARTNER_AGE","-");
		
		if(($jpartnerObj->getLHEIGHT())!="" && ($jpartnerObj->getHHEIGHT())!="")
                {
                        $lheight=$jpartnerObj->getLHEIGHT();;
                        $lheight=$HEIGHT_DROP["$lheight"];

                        $hheight=$jpartnerObj->getHHEIGHT();
                        $hheight=$HEIGHT_DROP["$hheight"];

                        $lheight1=explode("(",$lheight);
                        $hheight1=explode("(",$hheight);

                        $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);
                }
                else
                        $smarty->assign("PARTNER_HEIGHT","-");

		if(($jpartnerObj->getCHILDREN())=="")
                        $smarty->assign("PARTNER_CHILDREN","Doesn't matter");
                elseif(($jpartnerObj->getCHILDREN())=="N")
                        $smarty->assign("PARTNER_CHILDREN","No");
                elseif(($jpartnerObj->getCHILDREN())=="Y")
                        $smarty->assign("PARTNER_CHILDREN","Yes");
		//Challenged fields made compatible with revamp w.r.t frontend(ref bug id is 37700).
                /*if(($jpartnerObj->getHANDICAPPED())=="")
                        $smarty->assign("PARTNER_HANDICAPPED","Doesn't matter");
                elseif(($jpartnerObj->getHANDICAPPED())=="N")
                        $smarty->assign("PARTNER_HANDICAPPED","No");
                elseif(($jpartnerObj->getHANDICAPPED())=="Y")
                        $smarty->assign("PARTNER_HANDICAPPED","Yes");*/
		if($jpartnerObj->getHANDICAPPED()!="")
                {
			$ph_str = substr($jpartnerObj->getHANDICAPPED(),1,strlen($jpartnerObj->getHANDICAPPED())-2);
                        $ph_val_arr = explode("','",$ph_str);
                        for($i=0;$i<count($ph_val_arr);$i++)
                        {
                                $ph_val=$ph_val_arr[$i];
                                $ph_arr[$i]=$HANDICAPPED[$ph_val];
                        }
                        if(count($ph_arr)>1)
                                $ph_fstr = implode(",",$ph_arr);
                        elseif(count($ph_arr)==1)
                                $ph_fstr = $ph_arr[0];
                        else
                                $ph_fstr = "";
                        if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
                                $showit=1;
			$smarty->assign("PARTNER_HANDICAPPED",$ph_fstr);
                }
		if($jpartnerObj->getNHANDICAPPED()!="")
                {
                        $nph_str = substr($jpartnerObj->getNHANDICAPPED(),1,strlen($jpartnerObj->getNHANDICAPPED())-2);
                        $nph_val_arr = explode("','",$nph_str);
                        for($i=0;$i<count($nph_val_arr);$i++)
                        {
                                $nph_val=$nph_val_arr[$i];
                                $nph_arr[$i]=$NATURE_HANDICAP[$nph_val];
                        }
                        if(count($nph_arr)>1)
                                $nph_fstr = implode(",",$nph_arr);
                        elseif(count($nph_arr)==1)
                                $nph_fstr = $nph_arr[0];
                        else
                                $nph_fstr = "";
                        if($showit)
                                $smarty->assign("showit",1);
                        else
                                $smarty->assign("showit",0);
                        $smarty->assign("PARTNER_NHANDICAPPED",$nph_fstr);
                }
                else
                {
                        if($showit)
                                $smarty->assign("showit",1);
                        else
                                $smarty->assign("showit",0);
                }
		//end

		unset($var_str);
		unset($var_arr);
		unset($var_final);
		if(($jpartnerObj->getPARTNER_BTYPE())!="")
		{
			$var_str=str_replace("'","",$jpartnerObj->getPARTNER_BTYPE());
			$var_arr=explode(",",$var_str);
			foreach($var_arr as $value)
			$var_final.=$BODYTYPE[$value].",";
			$var_final=substr($var_final,0,strlen($var_final)-1);
			$smarty->assign("PARTNER_BTYPE",$var_final);
		}	
		else
			$smarty->assign("PARTNER_BTYPE","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_COMP())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_COMP());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$COMPLEXION[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_COMP",$var_final);
                }
                else
                        $smarty->assign("PARTNER_COMP","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_DIET())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_DIET());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$DIET[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_DIET",$var_final);
                }
                else
                        $smarty->assign("PARTNER_DIET","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_DRINK())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_DRINK());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$DRINK[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_DRINK",$var_final);
                }
                else
                        $smarty->assign("PARTNER_DRINK","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_MANGLIK())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_MANGLIK());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$MANGLIK[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_MANGLIK",$var_final);
                }
                else
                        $smarty->assign("PARTNER_MANGLIK","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_MSTATUS())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_MSTATUS());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$MSTATUS[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_MSTATUS",$var_final);
                }
                else
                        $smarty->assign("PARTNER_MSTATUS","Doesn't matter");

		
		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_RES_STATUS())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_RES_STATUS());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$RSTATUS[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_RES_STATUS",$var_final);
                }
                else
                        $smarty->assign("PARTNER_RES_STATUS","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_SMOKE())!="")
                {
                        $var_str=str_replace("'","",$jpartnerObj->getPARTNER_SMOKE());
                        $var_arr=explode(",",$var_str);
                        foreach($var_arr as $value)
                        $var_final.=$SMOKE[$value].",";
                        $var_final=substr($var_final,0,strlen($var_final)-1);
                        $smarty->assign("PARTNER_SMOKE",$var_final);
                }
                else
                        $smarty->assign("PARTNER_SMOKE","Doesn't matter");

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_CASTE())!="")
		{
			$var_str=$jpartnerObj->getPARTNER_CASTE();
			$smarty->assign("PARTNER_CASTE",get_partner_string($var_str,"CASTE"));
		}
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
		if(($jpartnerObj->getPARTNER_RELIGION())!="")
		{
			$PARTNER_RELIGION=display_format($jpartnerObj->getPARTNER_RELIGION());
			if(count($PARTNER_RELIGION)>0)
				$PARTNER_RELIGION_STR=implode(",",$PARTNER_RELIGION);
			$smarty->assign("PARTNER_RELIGION",get_partner_string($PARTNER_RELIGION_STR,"RELIGION"));
		}
		if(($jpartnerObj->getPARTNER_COUNTRYRES())!="")
                {
			$PARTNER_COUNTRYRES=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
			if(count($PARTNER_COUNTRYRES)>0)
				$PARTNER_COUNTRYRES_STR=implode(",",$PARTNER_COUNTRYRES);
			$smarty->assign("PARTNER_COUNTRYRES",get_partner_string($PARTNER_COUNTRYRES_STR,"COUNTRY"));
		}
		if(($jpartnerObj->getPARTNER_CITYRES())!="")
                {
			$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());
                        if(count($PARTNER_CITYRES)>0)
                                $PARTNER_CITYRES_STR=implode("','",$PARTNER_CITYRES);
                        $smarty->assign("PARTNER_CITYRES",get_partner_string($PARTNER_CITYRES_STR,"CITY_NEW"));
                }
		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_ELEVEL_NEW())!="")
                {
                        $var_str=$jpartnerObj->getPARTNER_ELEVEL_NEW();
                        $smarty->assign("PARTNER_ELEVEL",get_partner_string($var_str,"EDUCATION_LEVEL_NEW"));
                }

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_ELEVEL_NEW())!="")
                {
                        $var_str=$jpartnerObj->getPARTNER_ELEVEL_NEW();
                        $smarty->assign("PARTNER_ELEVEL",get_partner_string($var_str,"EDUCATION_LEVEL_NEW"));
                }

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_OCC())!="")
		{
			$var_str=$jpartnerObj->getPARTNER_OCC();
			$smarty->assign("PARTNER_OCC",get_partner_string($var_str,"OCCUPATION"));
		}

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_MTONGUE())!="")
		{
			$var_str=$jpartnerObj->getPARTNER_MTONGUE();
			$smarty->assign("PARTNER_MTONGUE",get_partner_string($var_str,"MTONGUE"));
		}

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_COUNTRYRES())!="")
                {
                        $var_str=$jpartnerObj->getPARTNER_COUNTRYRES();
                        $smarty->assign("PARTNER_COUNTRYRES",get_partner_string($var_str,"COUNTRY"));
                }

		/*if(($jpartnerObj->getPARTNER_CITYRES())!="")
		{
			$str=$jpartnerObj->getPARTNER_CITYRES();
			$sql="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE in ($str)";
                        $dropresult=mysql_query_decide($sql) or die("7".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        $sql="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE in ($str)";
                        $dropresult=mysql_query_decide($sql) or die("8".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        $partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
                        $smarty->assign("PARTNER_CITYRES",$partner_city_str);

		}*/

		unset($var_str);
                unset($var_arr);
                unset($var_final);
		if(($jpartnerObj->getPARTNER_INCOME())!="")
		{
			$var_str=$jpartnerObj->getPARTNER_INCOME();
                        $smarty->assign("PARTNER_INCOME",get_partner_string($var_str,"INCOME"));
		}
	}
	else
		$smarty->assign("NOPARTNER",1);	

	/****************************Sharding concept done by Sadaf : end***********************************/
	
	
	/*************************************************************************
	Partner Profile section ends here
	*************************************************************************/
	
	/*************************************************************************
	Bookmarks section ends here
	*************************************************************************/
	
	if($PERSON_LOGGED_IN)
		$smarty->assign("PERSON_LOGGED_IN","1");
		
	/*************************************************************************
	Full Members section starts here
	*************************************************************************/
	
	//if($PERSON_LOGGED_IN)
	//{	
		/*********************************************************************
		Contact details section starts here
		*********************************************************************/
		
		$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,PARENTS_CONTACT,SHOW_PARENTS_CONTACT from newjs.JPROFILE where PROFILEID='$profileid'";
		$emailresult=mysql_query_decide($sql) or die("10".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$emailrow=mysql_fetch_array($emailresult);
		
		if($CONTACTDETAILS==1)
		{
			$smarty->assign("HISEMAIL",$emailrow["EMAIL"]);
		}
		else 
			$smarty->assign("BLANKEMAIL","1");
		
		if($emailrow["SHOWPHONE_RES"]=="Y" && $emailrow["PHONE_RES"]!="")
			$phone=$emailrow["PHONE_RES"];
			
		if($emailrow["SHOWPHONE_MOB"]=="Y" && $emailrow["PHONE_MOB"]!="")
		{
			if(trim($phone)=="")
				$phone=$emailrow["PHONE_MOB"];
			else 
				$phone.=", " . $emailrow["PHONE_MOB"];
		}
		
		if($CONTACTDETAILS==1)
		{
			$smarty->assign("PHONE",trim($phone));
		}
		elseif(trim($phone)!="") 
			$smarty->assign("BLANKPHONE","1");
		
		if($emailrow["CONTACT"]!="" && $emailrow["SHOWADDRESS"]=="Y")
		{
			if($CONTACTDETAILS==1)
			{
				$smarty->assign("ADDRESS",nl2br($emailrow["CONTACT"]));
			}
			else 
				$smarty->assign("BLANKADDRESS","1");
		}
		
		if($emailrow["PARENTS_CONTACT"]!="" && $emailrow["SHOW_PARENTS_CONTACT"]=="Y")
		{
			if($CONTACTDETAILS==1)
			{
				$smarty->assign("PARENTS_ADDRESS",nl2br($emailrow["PARENTS_CONTACT"]));
			}
			else 
				$smarty->assign("BLANKPARENTADDRESS","1");
		}
			
		if($emailrow["SHOWMESSENGER"]=="Y")
		{
			if($CONTACTDETAILS==1)
			{
				$mymessenger=$emailrow["MESSENGER_CHANNEL"];
				$smarty->assign("MESSENGER_CHANNEL",$MESSENGER_CHANNEL["$mymessenger"]);
				$smarty->assign("MESSENGER_ID",$emailrow["MESSENGER_ID"]);
			}
			else 
				$smarty->assign("BLANKMESSENGER","1");
		}
			
		mysql_free_result($emailresult);
		
		unset($emailrow);
		
		/*********************************************************************
		Contact details section ends here
		*********************************************************************/
		
	//}
		
	/*************************************************************************
	Full Members section ends here
	*************************************************************************/
	
	
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$smarty->assign("CHECKSUM",$checksum);


/****************Below code is to display links in contactgrid for astro services************/
	$sql="SELECT * from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die("11".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	if(strstr($myrow['SUBSCRIPTION'],"H"))
	{
//		if($username=="test4js")	
			$smarty->assign("HOROSCOPE","Y");
	}
	if(strstr($data['SUBSCRIPTION'],"K"))
	{
		if(!$myrow['BTIME'] || !$myrow['CITY_BIRTH'] || !$myrow['COUNTRY_BIRTH'])
		{
//			if($username=="swapdummy")
				$smarty->assign("REQUESTKUNDALI","Y");
		}
		else
		{
//			if($username=="kushtest")
				$smarty->assign("KUNDALI","Y");
		}
	}
/****************************Astro services section ends here**********************************/


	$smarty->assign("company",$company);
	return $smarty->fetch("../crm/profile_preview.tpl");
}	
	// function to show error message if profile does not exist or is hidden or is not activated
	function showProfileError($hidden="",$privacy="",$smarty='') 
	{
		global $checksum;
		if(!$smarty)	
			global $smarty;
		
		if($hidden=="N" || $hidden=="U" || $hidden=="P")
			$smarty->assign("MESSAGE","This profile is currently being Screened. Kindly view this profile after 24 hours");
		elseif($hidden=="H")
			$smarty->assign("MESSAGE","This profile is currently hidden. Please check after a couple of weeks");
		elseif($hidden=="D")
			$smarty->assign("MESSAGE","This profile has been deleted");
		
		if($privacy=="F")
			$smarty->assign("MESSAGE","Sorry, you cannot view this profile as you have been FILTERED");
		elseif($privacy=="C")
			$smarty->assign("MESSAGE","Sorry, you cannot view this profile as you have not been contacted by this person");
		elseif($privacy=="S")
			$smarty->assign("MESSAGE","Sorry, you cannot view this profile as you have the same gender as this person");
			
		$smarty->assign("PROFILE_HIDDEN",$hidden);
		$smarty->assign("CHECKSUM",$checksum);

		return $smarty->fetch("../crm/errorprofile.tpl");
		//$smarty->display("errorprofile.tpl");
		
		exit;
	}
	
	// returns the comma separated labels of field values
	function get_partner_string($str,$tablename)
	{
		if($str)
		{
			if($tablename == 'CITY_NEW')
                                $sql="select SQL_CACHE LABEL from newjs.$tablename where VALUE in ('$str')";
                        else
			{
				if($str=='DM' || $str=='undefined')
					$sql="select SQL_CACHE LABEL from newjs.$tablename where VALUE=''";
				else
					$sql="select SQL_CACHE LABEL from newjs.$tablename where VALUE in ($str)";
				
			}
			$dropresult=mysql_query_decide($sql) or die("12".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			while($droprow=mysql_fetch_array($dropresult))
			{
				$str1.=$droprow["LABEL"] . ", ";
			}
			
			mysql_free_result($dropresult);
			
			return substr($str1,0,-2);
		}
		else 
			return "Doesn't matter";
	}
?>
