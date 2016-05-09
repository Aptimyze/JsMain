<?php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("manglik.php");

include("arrays.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

	//global $smarty,$data;
$db=connect_db();	

function get_partner_string_from_array($str,$tablename)
		{
			 if(is_array($str))
                        $str=implode("','",$str);
	                if($str)
        	        {
				$sql="select SQL_CACHE LABEL from $tablename where VALUE in ('$str')";
				$dropresult=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
													 
				while($droprow=mysql_fetch_array($dropresult))
				{
					$str1.=$droprow["LABEL"] . ", ";
				}
													 
				mysql_free_result($dropresult);
													 
				return substr($str1,0,-2);
			}
			else
				return "Doesn't Matter";
		}
	

		
	$sql="select * from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die(mysql_error_js());//("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	// if no profile is found for this profileid, show error message
	
	$myrow=mysql_fetch_array($result);

        // free the recordset
	mysql_free_result($result);

	$smarty->assign("AGE",$myrow["AGE"] . " years");

        // the profile is not to be shown if it is not activated. However, if the person is viewing his own profile, it should be allowed
	
	
	$PRIVACY=$myrow["PRIVACY"];
	
        
	
        //code added by nikhil dhiman on 25 May 2007 For Setting Manglik Status
	$return_data=manglik($profileid,'viewed'); 
	$manglik_data=explode("+",$return_data);
	$smarty->assign("Own_Manglik_Status",$manglik_data[0]);
	     //$return_data=manglik($data['PROFILEID'],'viewer');    
	     $manglik_data=explode("+",$return_data);
	     $smarty->assign("Own_Manglik",$manglik_data[1]);
	

	$family_back=label_select("FAMILY_BACK",$myrow["FAMILY_BACK"]);
	$family_type=$FAMILY_TYPE[$myrow['FAMILY_TYPE']];
	$family_status=$FAMILY_STATUS[$myrow['FAMILY_STATUS']];
	$mother_occ=label_select("MOTHER_OCC",$myrow['MOTHER_OCC']);
	$tbrother=$myrow['T_BROTHER'];
	$mbrother=$myrow['M_BROTHER'];
	$tsister=$myrow['T_SISTER'];
	$msister=$myrow['M_SISTER'];
	$smarty->assign("FAMILY_BACK",$family_back[0]);
	$smarty->assign("MOTHER_OCC",$mother_occ[0]);
	$smarty->assign("FAMILY_TYPE",$family_type);
	$smarty->assign("FAMILY_STATUS",$family_status);
        if($tbrother==4)
		$tbrother="3+";
	if($mbrother==4)
		$mbrother="3+";
	if($tsister==4)
		$tsister="3+";
	if($msister==4)
		$msister="3+";
	$smarty->assign("T_BROTHER",$tbrother);
        $smarty->assign("M_BROTHER",$mbrother);
        $smarty->assign("T_SISTER",$tsister);
        $smarty->assign("M_SISTER",$msister);

        /******************************************************
	check for photographs starts here
	******************************************************/
	
	// if main photograph is there and is screened
	if($myrow["HAVEPHOTO"]=="Y")
	{
		//Symfony Photo Modification
            	$is_album = SymfonyPictureFunctions::checkMorePhotos($profileid);
		if($is_album > 0)
               	{
                 	$smarty->assign("ISALBUM","1");
             	}

		// if the main photo is screened
		if(SymfonyPictureFunctions::haveScreenedMainPhoto($profileid))
		{
			$album = SymfonyPictureFunctions::getAlbum($profileid);
			$smarty->assign("FULLVIEW","1");
			$smarty->assign("PHOTOFILE",$album['profile']);
			//Symfony Photo Modification ends
			
			// if the person is viewing his own profile
			//if(!$PERSON_HIMSELF)
			//{
				// if the user has chosen to hide the photo
				if($myrow["PHOTO_DISPLAY"]=="H")
				{
					$smarty->assign("FULLVIEW","");
					$smarty->assign("ISALBUM","");
					$smarty->assign("PHOTOFILE","images/photo_hidden.gif");
				}
				// if the user has chosen to display photo conditionally then check for contact made and then decide which photo to show
				elseif($myrow["PHOTO_DISPLAY"]=="C")
				{
					$smarty->assign("FULLVIEW","");
					$smarty->assign("ISALBUM","");
					$CHECK_FOR_PHOTO_CONTACT=1;
					$smarty->assign("PHOTOFILE","images/photo_visible_if_user_accept.gif");
					//$smarty->assign("PHOTOFILE","images/photovisible_only.gif");
				}
				elseif($myrow["PHOTO_DISPLAY"]=="F")
				{
					$CHECK_FOR_FILTERED=1;
					$smarty->assign("PHOTOFILE","images/photo_visible_not_filtered.gif");
				}
			//}
		}
		else 
			$smarty->assign("PHOTOFILE","images/photocomming.gif");
			
	}
	// main photo is being screened
	elseif($myrow["HAVEPHOTO"]=="U" || $myrow["HAVEPHOTO"]=="E")
	{
		$smarty->assign("PHOTOFILE","images/photocomming.gif");
	}
	else
	{
		$smarty->assign("PHOTOFILE","images/no_photo.gif");
	}
	
	/******************************************************
	check for photographs ends here
	******************************************************/

	$smarty->assign("PROFILENAME",$myrow["USERNAME"]);
	$smarty->assign("RELATION",$RELATIONSHIP[$myrow["RELATION"]]);
	$smarty->assign("PROFILEGENDER",$GENDER[$myrow["GENDER"]]);
	$smarty->assign("MSTATUS",$MSTATUS[$myrow["MSTATUS"]]);

	if($myrow["MSTATUS"]=='A')
	{
		//fetching annulled reason
		$sql_a ="select REASON,SCREENED from newjs.newjs.`ANNULLED` where PROFILEID='$profileid'";
		$res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
		if($row_a=mysql_fetch_row($res_a))
		{
				$smarty->assign("Annulled_Reason",nl2br($row_a[0]));
		}
	}
        $smarty->assign("CHILDREN",$CHILDREN[$myrow["HAVECHILD"]]);
        $smarty->assign("MANGLIK",$MANGLIK[$myrow["MANGLIK"]]);
        $smarty->assign("BODYTYPE",$BODYTYPE[$myrow["BTYPE"]]);
        $smarty->assign("COMPLEXION",$COMPLEXION[$myrow["COMPLEXION"]]);
        $smarty->assign("DIET",$DIET[$myrow["DIET"]]);
        $smarty->assign("SMOKE",$SMOKE[$myrow["SMOKE"]]);
        $smarty->assign("DRINK",$DRINK[$myrow["DRINK"]]);
        $smarty->assign("RSTATUS",$RSTATUS[$myrow["RES_STATUS"]]);
        $smarty->assign("HANDICAPPED",$HANDICAPPED[$myrow["HANDICAPPED"]]);
	$smarty->assign("GENDER",$myrow["GENDER"]);

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
		//$smarty->assign("CAREER_AFTER_MARRIAGE","Yes");
	/*elseif($married_working=="N")
		$smarty->assign("CAREER_AFTER_MARRIAGE","No");
	elseif($married_working=="D")
		$smarty->assign("CAREER_AFTER_MARRIAGE","Not decided");
	elseif($married_working=="")
		$smarty->assign("CAREER_AFTER_MARRIAGE","-");*/
		
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
	if($family_values=="")
		$smarty->assign("FAMILY_VALUES","-");
	else
		$smarty->assign("FAMILY_VALUES",$FAMILY_VALUES[$family_values]);
	
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
		$city_res=$CITY_INDIA_DROP["$city_res"];
	}
        elseif($myrow["COUNTRY_RES"]=="128")
	{
		$city_res=$myrow["CITY_RES"];
		$city_res=$CITY_USA_DROP["$city_res"];
	}
        else
                $city_res="";

        $smarty->assign("COUNTRY_BIRTH",$country_birth);
        $smarty->assign("COUNTRY_RES",$country_res);
        $smarty->assign("CITY_RES",$city_res);
        $smarty->assign("OCCUPATION",$occupation);
        $smarty->assign("EDUCATION_LEVEL",$edu_level[0]);
        $smarty->assign("INCOME",$income[0]);
        $smarty->assign("RELIGION",$religion[0]);
        $smarty->assign("MTONGUE",$mtongue[0]);
        $smarty->assign("CASTE",$caste);
	$smarty->assign("EDU_LEVEL_NEW",$edu_level_new[0]);
	$smarty->assign("FAMILY_BACK",$family_back[0]);

        if($myrow["BTIME"]!="")
        {
                $btime=explode(":",$myrow["BTIME"]);
                $smarty->assign("BTIMEHOUR",$btime[0]);
                $smarty->assign("BTIMEMIN",$btime[1]);
        }

        if($myrow["CITY_BIRTH"]=="")
                $smarty->assign("CITYBIRTH","-");
        elseif(isFlagSet("CITYBIRTH",$myrow["SCREENING"]))
                $smarty->assign("CITYBIRTH",ucwords($myrow["CITY_BIRTH"]));
        else
                $smarty->assign("CITYBIRTH",$SCREENING_MESSAGE);

        if($myrow["SUBCASTE"]=="")
                $smarty->assign("SUBCASTE","-");
        elseif(isFlagSet("SUBCASTE",$myrow["SCREENING"]))
                $smarty->assign("SUBCASTE",$myrow["SUBCASTE"]);
        else
                $smarty->assign("SUBCASTE",$SCREENING_MESSAGE);

	//if(isFlagSet("YOURINFO",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("YOURINFO",$myrow["SCREENING"]))
	{
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
		$smarty->assign("SUBYOURINFO","<b>".$subyourinfo."</b>");
	}
	
	//if(isFlagSet("JOB_INFO",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("JOB_INFO",$myrow["SCREENING"]))
	{
		if(trim($myrow["JOB_INFO"]))
			$yourinfo.="\n\n<b>My Job:</b>".$myrow["JOB_INFO"];
	}
	//if(isFlagSet("SPOUSE",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("SPOUSE",$myrow["SCREENING"]))
        {
		if(trim($myrow["SPOUSE"]))
			$yourinfo.="\n\n<b>Looking for:</b> ".$myrow["SPOUSE"];
	}

	$smarty->assign("YOURINFO",nl2br($yourinfo));

	//if(isFlagSet("FATHER_INFO",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("FATHER_INFO",$myrow["SCREENING"]))
	{
		if(trim($myrow["FATHER_INFO"]))
			$familyinfo=$myrow["FATHER_INFO"];
	}

	//if(isFlagSet("SIBLING_INFO",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("SIBLING_INFO",$myrow["SCREENING"]))
	{
		if(trim($myrow["SIBLING_INFO"]))
			$familyinfo.=$myrow["SIBLING_INFO"];
	}

	//if(isFlagSet("FAMILYINFO",$myrow["SCREENING"]) || $PERSON_HIMSELF)
	if(isFlagSet("FAMILYINFO",$myrow["SCREENING"]))
	{
		if(trim($myrow["FAMILYINFO"]))
			$familyinfo.=$myrow["FAMILYINFO"];
	}
	$smarty->assign("FAMILYINFO",nl2br($familyinfo));

        if($myrow["GOTHRA"]=="")
                $smarty->assign("GOTHRA","-");
        elseif(isFlagSet("GOTHRA",$myrow["SCREENING"]))
                $smarty->assign("GOTHRA",$myrow["GOTHRA"]);
        else
                $smarty->assign("GOTHRA",$SCREENING_MESSAGE);

        if($myrow["NAKSHATRA"]=="")
                $smarty->assign("NAKSHATRA","-");
        elseif(isFlagSet("NAKSHATRA",$myrow["SCREENING"]))
                $smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);
        else
                $smarty->assign("NAKSHATRA",$SCREENING_MESSAGE);

        if($myrow["SPOUSE"]=="")
                $smarty->assign("SPOUSE","-");
        elseif(isFlagSet("SPOUSE",$myrow["SCREENING"]))
                $smarty->assign("SPOUSE",nl2br($myrow["SPOUSE"]));
        else
                $smarty->assign("SPOUSE",$SCREENING_MESSAGE);

       if($myrow["EDUCATION"]=="")
                $smarty->assign("EDUCATION","-");
        elseif(isFlagSet("EDUCATION",$myrow["SCREENING"]))
                $smarty->assign("EDUCATION",nl2br($myrow["EDUCATION"]));
	//elseif($PERSON_HIMSELF) 
		//$smarty->assign("EDUCATION",nl2br($myrow["EDUCATION"]) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
                $smarty->assign("EDUCATION",$SCREENING_MESSAGE);

	$sql="select SQL_CACHE PROFILEID from newjs.HIDE_DOB where PROFILEID='$profileid'";
	$hideresult=mysql_query_decide($sql);
	
	if ($hideresult && mysql_num_rows($hideresult)<=0)
	{
		$dob=explode("-",$myrow["DTOFBIRTH"]);
		$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
		
		unset($dob);
	}

        unset($dob);
        $dob=explode("-",$myrow["LAST_LOGIN_DT"]);
        $smarty->assign("LAST_LOGIN_DT",my_format_date($dob[2],$dob[1],$dob[0]));

        /****************************************************************************
        Hobbies section starts here
        ****************************************************************************/

        $sql="select * from newjs.JHOBBY where PROFILEID='$profileid'";
        $result=mysql_query_decide($sql) or die(mysql_error_js());//("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

        if(mysql_num_rows($result) > 0)
        {
                $myrow=mysql_fetch_array($result);

                $sql="select SQL_CACHE VALUE,LABEL,TYPE from newjs.HOBBIES order by SORTBY";
                $result_hobby=mysql_query_decide($sql) or die(mysql_error_js());//("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

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

	//Sharding Concept added by Vibhor Garg on table JPARTNER

        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");

        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);

        if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
	{    
		$HAVE_PARTNER=true;
                if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                {
                        $FILTER_LAGE=$jpartnerObj->getLAGE();
                        $FILTER_HAGE=$jpartnerObj->getHAGE();
                        $smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " to " . $jpartnerObj->getHAGE());
                }
                else
                        $smarty->assign("PARTNER_AGE","-");

		if($jpartnerObj->getLHEIGHT()!="" && $jpartnerObj->getHHEIGHT()!="")
                {
                        $lheight=$jpartnerObj->getLHEIGHT();
                        $lheight=$HEIGHT_DROP["$lheight"];

                        $hheight=$jpartnerObj->getHHEIGHT();
                        $hheight=$HEIGHT_DROP["$hheight"];

                        $lheight1=explode("(",$lheight);
                        $hheight1=explode("(",$hheight);

                        $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);
                }
                else
                       $smarty->assign("PARTNER_HEIGHT","-");

                if($jpartnerObj->getCHILDREN()=="")
                        $smarty->assign("PARTNER_CHILDREN","   - ");
                elseif($jpartnerObj->getCHILDREN()=="N")
                        $smarty->assign("PARTNER_CHILDREN","No");
                elseif($jpartnerObj->getCHILDREN()=="Y")
                        $smarty->assign("PARTNER_CHILDREN","Yes");

                if($jpartnerObj->getHANDICAPPED()=="")
                        $smarty->assign("PARTNER_HANDICAPPED","   - ");
                elseif($jpartnerObj->getHANDICAPPED()=="N")
                        $smarty->assign("PARTNER_HANDICAPPED","No");
                elseif($jpartnerObj->getHANDICAPPED()=="Y")
                        $smarty->assign("PARTNER_HANDICAPPED","Yes");
		
		$p_manglik=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $p_mtongue=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $return_data1=partnermanglik($p_mtongue,$p_manglik);
                $manglik_data1=explode("+",$return_data1);
                $smarty->assign("Partner_Manglik_Status",$manglik_data1[0]);
                $smarty->assign("Partner_Manglik",$manglik_data1[1]);

		$temp=display_format($jpartnerObj->getPARTNER_BTYPE());
                for($ll=0;$ll<count($temp);$ll++)
                	$PARTNER_BTYPE[]=$BODYTYPE[$temp[$ll]];
                unset($temp);

	        $temp=display_format($jpartnerObj->getPARTNER_COMP());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_COMP[]=$COMPLEXION[$temp[$ll]];
		unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_DIET());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_DIET[]=$DIET[$temp[$ll]];
		unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_DRINK());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_DRINK[]=$DRINK[$temp[$ll]];
                unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_MANGLIK());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_MANGLIK[]=$MANGLIK[$temp[$ll]];
		unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_MSTATUS());
		$FILTER_MSTATUS=$temp;
		for($ll=0;$ll<count($temp);$ll++)
               	{ 
                	$PARTNER_MSTATUS[]=$MSTATUS[$temp[$ll]];
                }
                unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_RES_STATUS());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_RES_STATUS[]=$RSTATUS[$temp[$ll]];
		unset($temp);

		$temp=display_format($jpartnerObj->getPARTNER_SMOKE());
		for($ll=0;$ll<count($temp);$ll++)
			$PARTNER_SMOKE[]=$SMOKE[$temp[$ll]];
		unset($temp);
		
		$PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
		$PARTNER_ELEVEL_NEW=display_format($jpartnerObj->getPARTNER_ELEVEL_NEW());
		$PARTNER_MTONGUE=display_format($jpartnerObj->getPARTNER_MTONGUE());
		$PARTNER_OCC=display_format($jpartnerObj->getPARTNER_OCC());
		$PARTNER_COUNTRYRES=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
		$PARTNER_INCOME=display_format($jpartnerObj->getPARTNER_INCOME());
  		$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());	
		if(is_array($PARTNER_BTYPE))
                       $smarty->assign("PARTNER_BTYPE",implode(", ",$PARTNER_BTYPE));
                else
                        $smarty->assign("PARTNER_BTYPE","   - ");

                if(is_array($PARTNER_COMP))
                        $smarty->assign("PARTNER_COMP",implode(", ",$PARTNER_COMP));
                else
                        $smarty->assign("PARTNER_COMP","   - ");

                if(is_array($PARTNER_DIET))
                        $smarty->assign("PARTNER_DIET",implode(", ",$PARTNER_DIET));
                else
                        $smarty->assign("PARTNER_DIET","   - ");

                if(is_array($PARTNER_DRINK))
                        $smarty->assign("PARTNER_DRINK",implode(", ",$PARTNER_DRINK));
                else
                        $smarty->assign("PARTNER_DRINK","   - ");

                if(is_array($PARTNER_MANGLIK))
                        $smarty->assign("PARTNER_MANGLIK",implode(", ",$PARTNER_MANGLIK));
                else
                        $smarty->assign("PARTNER_MANGLIK","   - ");

		if(is_array($PARTNER_MSTATUS))
                        $smarty->assign("PARTNER_MSTATUS",implode(", ",$PARTNER_MSTATUS));
                else
                        $smarty->assign("PARTNER_MSTATUS","   - ");

                if(is_array($PARTNER_RES_STATUS))
                        $smarty->assign("PARTNER_RES_STATUS",implode(", ",$PARTNER_RES_STATUS));
                else
                        $smarty->assign("PARTNER_RES_STATUS","   - ");

                if(is_array($PARTNER_SMOKE))
                        $smarty->assign("PARTNER_SMOKE",implode(", ",$PARTNER_SMOKE));
                else
                       $smarty->assign("PARTNER_SMOKE","   - ");

		$smarty->assign("PARTNER_CASTE",get_partner_string_from_array($PARTNER_CASTE,"CASTE"));

                $smarty->assign("PARTNER_ELEVEL_NEW",get_partner_string_from_array($PARTNER_ELEVEL_NEW,"EDUCATION_LEVEL_NEW"));

                $smarty->assign("PARTNER_MTONGUE",get_partner_string_from_array($PARTNER_MTONGUE,"MTONGUE"));

                $smarty->assign("PARTNER_OCC",get_partner_string_from_array($PARTNER_OCC,"OCCUPATION"));

                $smarty->assign("PARTNER_COUNTRYRES",get_partner_string_from_array($PARTNER_COUNTRYRES,"COUNTRY"));

                $smarty->assign("PARTNER_INCOME",get_partner_string_from_array($PARTNER_INCOME,"INCOME"));
			
     		if(is_array($PARTNER_CITYRES))
                {
                        $str=implode("','",$PARTNER_CITYRES);
			$str="'".$str."'";
                        $sql="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE in ($str)";
                        $dropresult=mysql_query_decide($sql) or die(mysql_error_js());//("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        $sql="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE in ($str)";
                       $dropresult=mysql_query_decide($sql) or die(mysql_error_js());//("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        $partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
                        $smarty->assign("PARTNER_CITYRES",$partner_city_str);
                }
        }
        else
        {
                $smarty->assign("NOPARTNER","1");
        }
        
        
	
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
        //$smarty->assign("CHECKSUM",$checksum);
//        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
  //      $smarty->assign("HEAD",$smarty->fetch("head.htm"));
    //    $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
      //  $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
 //       $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
   //     $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	  $smarty->assign("self_profile","Y");
	  $smarty->assign("PRINT_VERSION","Y");
	  $smarty->display("profile_by_mail.htm");


//	$smarty->assign("SELPROFILE",$smarty->fetch("profile_by_mail.htm");


	// function to show error message if profile does not exist or is hidden or is not activated
	

	// returns the comma separated labels of field values
?>
