<?php
	$path = $_SERVER['DOCUMENT_ROOT'];

        include_once($path."/profile/connect.inc");
        include_once($path."/profile/arrays.php");
        include_once($path."/profile/screening_functions.php");
        include_once($path."/profile/cuafunction.php");
        include_once($path."/profile/hits.php");
	include_once("registration_functions.inc");

        include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
        $jpartnerObj=new Jpartner;
        $mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");

        $db = connect_db();
        $xml = new DomDocument;
        $proc = new xsltprocessor;
	
	$proc->setParameter("","GROUPNAME",$groupname);
	$proc->setParameter("","SITE_URL",$SITE_URL);
	$IMG_URL = $SITE_URL."/profile/images/registration_new";
	$proc->setParameter("","IMG_URL",$IMG_URL);
	$LIVE_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/images_try/liveperson";
	$proc->setParameter("","LIVE_CHAT_URL",$LIVE_CHAT_URL);

	$MORE = "http://www.google.com/transliterate/indic/about_hi.html";
	$proc->setParameter("","MORE",$MORE);

	$now = date("Y-m-d G:i:s");

	if(!$ip)
	{
		//Gets ipaddress of user
		$ip = FetchClientIP();
		if(strstr($ip, ","))
		{
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
	}

	$submit_pg2 = $submit_pg2_hidden;
	if($submit_pg2 || $ajax_submit_pg2)
	{
		if($submit_pg2)
		{
			//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
			if($_COOKIE["JS_SHORT_FORM"])
			{
				$sql="SELECT COUNT(*) AS CNT FROM FROM_GOOGLE_HITS WHERE DATE=CURDATE()";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row=mysql_fetch_array($res);
				$cnt=$row['CNT'];

				if($cnt>0)
					$sql="UPDATE FROM_GOOGLE_HITS set SITE=SITE+1 WHERE DATE=CURDATE()";
				else
					$sql="INSERT FROM_GOOGLE_HITS(DATE,SITE) values ('$now','1')";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
		}
		$sql_upd_jp = "UPDATE newjs.JPROFILE SET ";

		if($residency_status)
		{
			$jprofile_update[] = "RES_STATUS='".$residency_status."'";
		}
		if($diet)
		{
			$jprofile_update[] = "DIET='".$diet."'";
		}
		if($drink)
		{
			$jprofile_update[] = "DRINK='".$drink."'";
		}
		if($smoke)
		{
			$jprofile_update[] = "SMOKE='".$smoke."'";
		}
		if($blood_group)
		{
			$jprofile_update[] = "BLOOD_GROUP='".$blood_group."'";
		}
		if($hiv)
		{
			$jprofile_update[] = "HIV='".$hiv."'";
		}
		if($body_type)
		{
			$jprofile_update[] = "BTYPE='".$body_type."'";
		}
		if($weight)
		{
			$jprofile_update[] = "WEIGHT='".$weight."'";
		}
		if($complexion)
		{
			$jprofile_update[] = "COMPLEXION='".$complexion."'";
		}
		if($handicapped)
		{
			$jprofile_update[] = "HANDICAPPED='".$handicapped."'";
			if($nature_of_handicap)
			{
				$jprofile_update[] = "NATURE_HANDICAP='".$nature_of_handicap."'";
			}
		}
		if($spoken_languages_arr && is_string($spoken_languages_arr))
		{
			$spoken_languages_arr = @explode(",",$spoken_languages_arr);
			$spoken_languages_str = @implode(",",$spoken_languages_arr);

/*			$spoken_languages_str = "'".@implode("','",$spoken_languages_arr)."'";
			$spoken_languages_str=addslashes($spoken_languages_str);
			$jprofile_update[] = "SPOKEN_LANGUAGES='".addslashes($spoken_languages_str)."'";
*/
			$sql = "INSERT IGNORE INTO newjs.JHOBBY(PROFILEID,HOBBY) VALUES ($profileid,\"$spoken_languages_str\")";
			$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		elseif(count($spoken_languages_arr) > 0 && is_array($spoken_languages_arr))
		{
			$spoken_languages_str = @implode(",",$spoken_languages_arr);

/*			$spoken_languages_str = "'".@implode("','",$spoken_languages_arr)."'";
			$jprofile_update[] = "SPOKEN_LANGUAGES='".addslashes($spoken_languages_str)."'";
			$spoken_languages_str=addslashes($spoken_languages_str);
*/
			$sql = "INSERT IGNORE INTO newjs.JHOBBY(PROFILEID,HOBBY) VALUES ($profileid,\"$spoken_languages_str\")";
			$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		if($messenger_id || $messenger_channel || $showmessenger)
		{
			$jprofile_update[] = "MESSENGER_ID='".addslashes(stripslashes($messenger_id))."'";
			$jprofile_update[] = "MESSENGER_CHANNEL='".$messenger_channel."'";
			$jprofile_update[] = "SHOWMESSENGER='".$showmessenger."'";
			if($messenger_id)
				$msgr=$messenger_id."@".$MESSENGER_CHANNEL[$messenger_channel];

			$sql_arch_check = "SELECT cai.NEW_VAL FROM newjs.CONTACT_ARCHIVE ca, newjs.CONTACT_ARCHIVE_INFO cai WHERE ca.PROFILEID='$profileid' AND ca.CHANGEID=cai.CHANGEID AND ca.FIELD = 'MESSENGER'";
			$res_arch_check = mysql_query_decide($sql_arch_check) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_arch_check = mysql_fetch_array($res_arch_check);
			if(strtolower(trim($msgr)) != strtolower(trim($row_arch_check['NEW_VAL'])))
			{
				$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'MESSENGER')";
				$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

				$changeid=mysql_insert_id_js();


				$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$msgr')";
				$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
		}
/*
		if($orkut_username && $orkut_username != "Username")
		{
			$jprofile_update[] = "ORKUT_HANDLE='".mysql_real_escape_string($orkut_username)."'";
		}
*/
		if($contact_address)
		{
			$jprofile_update[] = "CONTACT='".addslashes(stripslashes(mysql_real_escape_string($contact_address)))."'";
			$jprofile_update[] = "SHOWADDRESS='".$showaddress."'";

			$sql_arch_check = "SELECT cai.NEW_VAL FROM newjs.CONTACT_ARCHIVE ca, newjs.CONTACT_ARCHIVE_INFO cai WHERE ca.PROFILEID='$profileid' AND ca.CHANGEID=cai.CHANGEID AND ca.FIELD = 'CONTACT'";
			$res_arch_check = mysql_query_decide($sql_arch_check) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_arch_check = mysql_fetch_array($res_arch_check);
			if(strtolower(trim($contact_address)) != strtolower(trim($row_arch_check['NEW_VAL'])))
			{
				$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'CONTACT')";
				$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

				$changeid=mysql_insert_id_js();
				$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$contact_address')";
				$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_info,"ShowErrTemplate");
			}
		}
		if($family_values)
		{
			$jprofile_update[] = "FAMILY_VALUES='".$family_values."'";
		}
		if($family_type)
		{
			$jprofile_update[] = "FAMILY_TYPE='".$family_type."'";
		}
		if($family_status)
		{
			$jprofile_update[] = "FAMILY_STATUS='".$family_status."'";
		}
		if($father_occupation)
		{
//			$jprofile_update[] = "FATHER_OCC='".$father_occupation."'";
			$jprofile_update[] = "FAMILY_BACK='".$father_occupation."'";
		}
		if($mother_occupation)
		{
			$jprofile_update[] = "MOTHER_OCC='".$mother_occupation."'";
		}
		if($brothers)
		{
			$jprofile_update[] = "T_BROTHER='".$brothers."'";
		}
		if($married_brothers)
		{
			$jprofile_update[] = "M_BROTHER='".$married_brothers."'";
		}
		if($sisters)
		{
			$jprofile_update[] = "T_SISTER='".$sisters."'";
		}
		if($married_sisters)
		{
			$jprofile_update[] = "M_SISTER='".$sisters."'";
		}
		if($live_with_parents)
		{
			//$jprofile_update[] = "LIVE_WITH_PARENTS='".$live_with_parents."'";
			$jprofile_update[] = "PARENT_CITY_SAME='".$live_with_parents."'";
		}
		if(!$about_family_default)  // Changes by Anurag
		{
			loadMyXML($path."/profile/registration_pg2_eng.xml");
                        $registration_pg2 = $xml->getElementsByTagName("registrationPage2")->item(0);
			$help = $registration_pg2->getElementsByTagName("help")->item(0);
			$about_family_default = $help->getElementsByTagName("writeAboutYourFamily")->item(0)->nodeValue;
		}
		if($about_family && $about_family != $about_family_default)
		{
			$jprofile_update[] = "FAMILYINFO='".addslashes(stripslashes(mysql_real_escape_string($about_family)))."'";
		}
		
		if(!$about_education_default)    // Anurag changes
                {
                        loadMyXML($path."/profile/registration_pg2_eng.xml");
                        $registration_pg2 = $xml->getElementsByTagName("registrationPage2")->item(0);
	                $help =	$registration_pg2->getElementsByTagName("help")->item(0);
                        $about_education_default = $help->getElementsByTagName("aboutYourEducation")->item(0)->nodeValue;
	        } // 
		if($about_education && $about_education != $about_education_default)   // Anurag Changes one line only
		{
			$jprofile_update[] = "EDUCATION='".addslashes(stripslashes(mysql_real_escape_string($about_education)))."'";
		}
		if($work_status)
		{
			$jprofile_update[] = "WORK_STATUS='".$work_status."'";
		}
		if($married_working)
		{
			$jprofile_update[] = "MARRIED_WORKING='".$married_working."'";
		}
		if(!$about_work_default)    // Anurag changes
                {
		       	loadMyXML($path."/profile/registration_pg2_eng.xml");
			$registration_pg2 = $xml->getElementsByTagName("registrationPage2")->item(0);
			$help =	 $registration_pg2->getElementsByTagName("help")->item(0);
		        $about_work_default = $help->getElementsByTagName("aboutYourWork")->item(0)->nodeValue;
	        } 
		if($about_work && stripslashes($about_work) != stripslashes($about_work_default))
		{
			$jprofile_update[] = "JOB_INFO='".addslashes(stripslashes(mysql_real_escape_string($about_work)))."'";
		}
		if($subcaste)
		{
			$jprofile_update[] = "SUBCASTE='".addslashes(stripslashes(mysql_real_escape_string($subcaste)))."'";
		}
		if($gotra)
		{
			$jprofile_update[] = "GOTHRA='".addslashes(stripslashes(mysql_real_escape_string($gotra)))."'";
		}
		if($ancestral_origin)
		{
			$jprofile_update[] = "ANCESTRAL_ORIGIN='".addslashes(stripslashes(mysql_real_escape_string($ancestral_origin)))."'";
		}
		if($manglik)
		{
			$jprofile_update[] = "MANGLIK='".$manglik."'";
		}
		if($nakshatra)
		{
			if($nakshatra == 1)
				$nakshatra = "Don't Know";
			elseif($nakshatra == 2)
				$nakshatra = "Anuradha/Anusham/Anizham";
			elseif($nakshatra == 3)
				$nakshatra = "Ardra/Thiruvathira";
			elseif($nakshatra == 4)
				$nakshatra = "Ashlesha/Ayilyam";
			elseif($nakshatra == 5)
				$nakshatra = "Ashwini/Ashwathi";
			elseif($nakshatra == 6)
				$nakshatra = "Bharani";
			elseif($nakshatra == 7)
				$nakshatra = "Chitra/Chitha";
			elseif($nakshatra == 8)
				$nakshatra = "Dhanista/Avittam";
			elseif($nakshatra == 9)
				$nakshatra = "Hastha/Atham";
			elseif($nakshatra == 10)
				$nakshatra = "Jyesta/Kettai";
			elseif($nakshatra == 11)
				$nakshatra = "Krithika/Karthika";
			elseif($nakshatra == 12)
				$nakshatra = "Makha/Magam";
			elseif($nakshatra == 13)
				$nakshatra = "Moolam/Moola";
			elseif($nakshatra == 14)
				$nakshatra = "Mrigasira/Makayiram";
			elseif($nakshatra == 15)
				$nakshatra = "Poorvabadrapada/Puratathi";
			elseif($nakshatra == 16)
				$nakshatra = "Poorvashada/Pooradam";
			elseif($nakshatra == 17)
				$nakshatra = "Poorvapalguni/Puram/Pubbhe";
			elseif($nakshatra == 18)
				$nakshatra = "Punarvasu/Punarpusam";
			elseif($nakshatra == 19)
				$nakshatra = "Pushya/Poosam/Pooyam";
			elseif($nakshatra == 20)
				$nakshatra = "Revathi";
			elseif($nakshatra == 21)
				$nakshatra = "Rohini";
			elseif($nakshatra == 22)
				$nakshatra = "Shatataraka/Sadayam/Sadabist";
			elseif($nakshatra == 23)
				$nakshatra = "Shravan/Thiruvonam";
			elseif($nakshatra == 24)
				$nakshatra = "Swati/Chothi";
			elseif($nakshatra == 25)
				$nakshatra = "Uttrabadrapada/Uthratadhi";
			elseif($nakshatra == 26)
				$nakshatra = "Uttarapalguni/Uthram";
			elseif($nakshatra == 27)
				$nakshatra = "Uttarashada/Uthradam";
			elseif($nakshatra == 28)
				$nakshatra = "Vishaka/Vishakam";
			$jprofile_update[] = "NAKSHATRA='".addslashes($nakshatra)."'";
		}
		if($rashi)
		{
			$jprofile_update[] = "RASHI='".$rashi."'";
		}
		if($horoscope_match)
		{
			$jprofile_update[] = "HOROSCOPE_MATCH='".$horoscope_match."'";
/*
			if($horoscope_match == "")
			{
				$sql_astro = "INSERT INTO MIS.ASTRO_COMMUNITY_WISE(PROFILEID,MTONGUE,ENTRY_DT) VALUES('$profileid','$mtongue','$now')";
				mysql_query_decide($sql_astro) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_astro,"ShowErrTemplate");
			}
*/
		}
		if($horoscope)
		{
			$jprofile_update[] = "SHOW_HOROSCOPE='".$horoscope."'";
		}
		
		if(!$about_yourself_default)
		{
			loadMyXML($path."/profile/registration_pg2_eng.xml");
			$registration_pg2 = $xml->getElementsByTagName("registrationPage2")->item(0);
			$help = $registration_pg2->getElementsByTagName("help")->item(0);
			$about_yourself_default = $help->getElementsByTagName("writeAboutYourSelf")->item(0)->nodeValue;
		}
		
		if(strstr($about_yourself,'My hobbies')&&strstr($about_yourself,'What my friends like about me')&&strstr($about_yourself,'What makes you angry')&&strstr($about_yourself,'What makes you happy')&&strstr($about_yourself,'What qualities do you like in a woman')&&strstr($about_yourself,'What is your dream you would like to accomplish'))
		{
			$about_yourself = '';
		}
		if($about_yourself && stripslashes($about_yourself) != stripslashes($about_yourself_default))
		{
			$jprofile_update[] = "YOURINFO='".addslashes(stripslashes(mysql_real_escape_string($about_yourself)))."'";
			if(strlen(trim($about_yourself)) > 100)
			{
				/* Check for Double opt-in for other email domain

				 $non_doubt_email_array=array('yahoo.com','yahoo.co.in','in.com','rediffmail.com','hotmail.com','gmail.com','indiatimes.com','sify.com','yahoo.co.uk','rediff.com','yahoomail.com','aol.com','hotmail.co.uk','yahoo.ca','sancharnet.in','msn.com','india.com','yahoo.com.au','yahoo.com.in','yahoo.fr','vsnl.net');
				$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                        $row=mysql_fetch_array($result);
	                        $email=$row["EMAIL"];

				$email_domain=explode("@",$email);
				if(!in_array($email_domain[1],$non_doubt_email_array))
				{
					$sql = "SELECT COUNT(*) AS CNT FROM newjs.DOUBLE_OPTIN WHERE PROFILEID='$profileid'";
					$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					$row=mysql_fetch_array($res);
					$cnt=$row['CNT'];
					if($cnt==0)
						$jprofile_update[] = "INCOMPLETE='Y'";
					else
						$jprofile_update[] = "INCOMPLETE='N'";
				}*/
				
				$jprofile_update[] = "INCOMPLETE='N'";

				if($_SERVER["SERVER_NAME"] == "www.jeevansathi.com")
					$registration_complete = "Y";
				        
				if($groupname == "google")
					$reg_comp_frm_ggl = 1;
				elseif($groupname == "Google_NRI")
					$reg_comp_frm_ggl_nri = 1;
			}
		}
		if($about_desired_partner)
		{
			$jprofile_update[] = "SPOUSE='".addslashes(stripslashes(mysql_real_escape_string($about_desired_partner)))."'";
		}
		if(count($jprofile_update) > 0)
		{
			$jprofile_update_str = @implode(", ",$jprofile_update);
			$sql_upd_jp .= $jprofile_update_str." WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql_upd_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}

		unset($jprofile_update);

		if($diocese)
		{
			$fields[] = "DIOCESE";
			$values[] = addslashes(stripslashes(mysql_real_escape_string($diocese)));
		}
		if($baptised)
		{
			$fields[] = "BAPTISED";
			$values[] = $baptised;
		}
		if($read_bible)
		{
			$fields[] = "READ_BIBLE";
			$values[] = $read_bible;
		}
		if($offer_tithe)
		{
			$fields[] = "OFFER_TITHE";
			$values[] = $offer_tithe;
		}
		if($spreading_gospel)
		{
			$fields[] = "SPREADING_GOSPEL";
			$values[] = $spreading_gospel;
		}

		if(count($fields) > 0)
		{
			$sql_ch = "SELECT COUNT(*) AS COUNT FROM newjs.JP_CHRISTIAN WHERE PROFILEID='$profileid'";
			$res_ch = mysql_query_decide($sql_ch) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ch,"ShowErrTemplate");
			$row_ch = mysql_fetch_array($res_ch);
			if($row_ch['COUNT'] > 0)
			{
				for($i=0;$i<count($fields);$i++)
					$update_arr[] = "$fields[$i] = '$values[$i]'";

				$update_str = @implode(",",$update_arr);

				$sql_upd_ch = "UPDATE newjs.JP_CHRISTIAN SET $update_str WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd_ch) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_ch,"ShowErrTemplate");

				unset($update_arr);
				unset($update_str);
			}
			else
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
				
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "INSERT INTO newjs.JP_CHRISTIAN($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}

			unset($fields);
			unset($values);
		}

		if($maththab)
		{
			$fields[] = "MATHTHAB";
			if($maththab == "150")
				$maththab ="4";
			elseif($maththab == "258")
				$maththab ="1";
			elseif($maththab == "259")
				$maththab ="2";
			elseif($maththab == "260")
				$maththab ="3";
			elseif($maththab == "254")
				$maththab ="1";
			elseif($maththab == "255")
				$maththab ="2";
			elseif($maththab == "256")
				$maththab ="3";
			elseif($maththab == "257")
				$maththab ="4";
			$values[] = $maththab;
		}
		if($namaz)
		{
			$fields[] = "NAMAZ";
			$values[] = $namaz;
		}
		if($zakat)
		{
			$fields[] = "ZAKAT";
			$values[] = $zakat;
		}
		if($fasting)
		{
			$fields[] = "FASTING";
			$values[] = $fasting;
		}
		if($umrah_hajj)
		{
			$fields[] = "UMRAH_HAJJ";
			$values[] = $umrah_hajj;
		}
		if($quran)
		{
			$fields[] = "QURAN";
			$values[] = $quran;
		}
		if($sunnah_beard)
		{
			$fields[] = "SUNNAH_BEARD";
			$values[] = $sunnah_beard;
		}
		if($sunnah_cap)
		{
			$fields[] = "SUNNAH_CAP";
			$values[] = $sunnah_cap;
		}
		if($hijab)
		{
			$fields[] = "HIJAB";
			$values[] = $hijab;
		}
		if($hijab_marriage)
		{
			$fields[] = "HIJAB_MARRIAGE";
			$values[] = $hijab_marriage;
		}
		if($working_marriage)
		{
			$fields[] = "WORKING_MARRIAGE";
			$values[] = $working_marriage;
		}
		if(count($fields) > 0)
		{
			$sql_mus = "SELECT COUNT(*) AS COUNT FROM newjs.JP_MUSLIM WHERE PROFILEID='$profileid'";
			$res_mus = mysql_query_decide($sql_mus) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mus,"ShowErrTemplate");
			$row_mus = mysql_fetch_array($res_mus);
			if($row_mus['COUNT'] > 0)
			{
				for($i=0;$i<count($fields);$i++)
					$update_arr[] = "$fields[$i] = '$values[$i]'";

				$update_str = @implode(",",$update_arr);

				$sql_upd_mus = "UPDATE newjs.JP_MUSLIM SET $update_str WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd_mus) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_mus,"ShowErrTemplate");

				unset($update_arr);
				unset($update_str);
			}
			else
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
				
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "INSERT INTO newjs.JP_MUSLIM($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}

			unset($fields);
			unset($values);
		}

		if($amritdhari)
		{
			$fields[] = "AMRITDHARI";
			$values[] = $amritdhari;
		}
		if($cut_hair)
		{
			$fields[] = "CUT_HAIR";
			$values[] = $cut_hair;
		}
		if($trim_beard)
		{
			$fields[] = "TRIM_BEARD";
			$values[] = $trim_beard;
		}
		if($wear_turban)
		{
			$fields[] = "WEAR_TURBAN";
			$values[] = $wear_turban;
		}
		if($clean_shaven)
		{
			$fields[] = "CLEAN_SHAVEN";
			$values[] = $clean_shaven;
		}

		if(count($fields) > 0)
		{
			$sql_sik = "SELECT COUNT(*) AS COUNT FROM newjs.JP_SIKH WHERE PROFILEID='$profileid'";
			$res_sik = mysql_query_decide($sql_sik) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sik,"ShowErrTemplate");
			$row_sik = mysql_fetch_array($res_sik);

			if($row_sik['COUNT'] > 0)
			{
				for($i=0;$i<count($fields);$i++)
				$update_arr[] = "$fields[$i] = '$values[$i]'";

				$update_str = @implode(",",$update_arr);

				$sql_upd_sik = "UPDATE newjs.JP_SIKH SET $update_str WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd_sik) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_sik,"ShowErrTemplate");

				unset($update_arr);
				unset($update_str);
			}
			else
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;

				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "INSERT INTO newjs.JP_SIKH($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}

			unset($fields);
			unset($values);
		}
		if($sampraday)
		{
			$sql = "REPLACE INTO newjs.JP_JAIN(PROFILEID,SAMPRADAY) VALUES('$profileid','$sampraday')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		if($zarathushtri)
		{
			$fields[] = "ZARATHUSHTRI";
			$values[] = $zarathushtri;
		}
		if($parents_zarathushtri)
		{
			$fields[] = "PARENTS_ZARATHUSHTRI";
			$values[] = $parents_zarathushtri;
		}
		if(count($fields) > 0)
		{
			$sql_par = "SELECT COUNT(*) AS COUNT FROM newjs.JP_PARSI WHERE PROFILEID='$profileid'";
			$res_par = mysql_query_decide($sql_par) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_par,"ShowErrTemplate");
			$row_par = mysql_fetch_array($res_par);
			if($row_par['COUNT'] > 0)
			{
				for($i=0;$i<count($fields);$i++)
					$update_arr[] = "$fields[$i] = '$values[$i]'";
					
				$update_str = @implode(",",$update_arr);
				
				$sql_upd_par = "UPDATE newjs.JP_PARSI SET $update_str WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd_par) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_par,"ShowErrTemplate");
				
				unset($update_arr);
				unset($update_str);
			}
			else
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
			
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "REPLACE INTO newjs.JP_PARSI($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}

			unset($fields);
			unset($values);
		}

		$jpartnerObj->setPROFILEID($profileid);
		$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);

		if($partner_handicapped_arr && is_string($partner_handicapped_arr))
		{
			$partner_handicapped_arr = explode(",",$partner_handicapped_arr);
			$partner_handicapped_str = partner_save_format($partner_handicapped_arr);
			$jpartnerObj->setHANDICAPPED($partner_handicapped_str);
		}
		elseif(count($partner_handicapped_arr) > 0 && is_array($partner_handicapped_arr))
		{
			$partner_handicapped_str = partner_save_format($partner_handicapped);
			$jpartnerObj->setHANDICAPPED($partner_handicapped_str);
		}

		$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

		if($submit_pg2)
		{
			// Screening Mail to the user after finishing the second page
			
			$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
	                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                $row=mysql_fetch_array($result);

		        $email=$row["EMAIL"];
		        $username=$row["USERNAME"];
        		$smarty->assign("username",$username);
			$msg =$smarty->fetch('Under_Screening.html');
			
			send_email($email,$msg,"Welcome to Jeevansathi.com","register@jeevansathi.com","","","","","","Y");

			include_once("sem.php"); // Function for the Calculating the Score
			$profile_score = profileScore($profileid);  // Findind the Score of the Profile

			//Inserting the Values to the Database
			$sql = "INSERT IGNORE INTO MIS.PROFILE_SCORE (`PROFILE_ID` , `SCORE` ) VALUES ( '$profileid' , '$profile_score')";
			$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Pleasetry after some time.",$sql,"ShowErrTemplate");	

			/* Earlier user was redirecting to the Membership Page

			echo "<html><body><META HTTP-EQUIV=\"refresh\"CONTENT=\"0;URL=$SITE_URL/profile/mem_comparison.php?checksum=$checksum&registration_complete=$registration_complete&reg_comp_frm_ggl=$reg_comp_frm_ggl&flag=1&profile_score=$profile_score&groupname=$groupname&reg_comp_frm_ggl_nri=$reg_comp_frm_ggl_nri\"></body></html>";*/
			
			// Redirecting to the Viewprofile once finished the registration Page 2

			$profilechecksum=md5($profileid) . "i" . $profileid;
			
			$anurag = strlen(trim($about_yourself));
			
			
			//Added y manoranjan for implementing jschat by manoranjan
			//echo "<html><body><META HTTP-EQUIV=\"refresh\"CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum&registration_complete=$registration_complete&reg1_comp_frm_ggl=$reg_comp_frm_ggl&flag=1&profile_score=$profile_score&groupname=$groupname&reg1_comp_frm_ggl_nri=$reg_comp_frm_ggl_nri#photohere\"></body></html>";
			$smarty->assign("Regd_REDIRECTURL","$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum&registration_complete=$registration_complete&reg_comp_frm_ggl=$reg_comp_frm_ggl&flag=1&profile_score=$profile_score&groupname=$groupname&reg_comp_frm_ggl_nri=$reg_comp_frm_ggl_nri#photohere");
			$smarty->display("login_redirect.htm");
			
			
		}
		elseif($ajax_submit_pg2)
			echo profile_percent_new($profileid)."%";
		die;
	}
	else
	{
		$proc->setParameter("","TIEUP_SOURCE",$tieup_source);
		$proc->setParameter("","MTONGUE",$mtongue);
		$proc->setParameter("","YEAR_OF_BIRTH",$year);
		$proc->setParameter("","MONTH_OF_BIRTH",$month);
		$proc->setParameter("","DAY_OF_BIRTH",$day);

		if(!$country_residence)
		{
			$jp_select_fields ="DTOFBIRTH,RELIGION,MTONGUE,CASTE,COUNTRY_RES,CITY_RES,RES_STATUS,DIET,DRINK,SMOKE,BLOOD_GROUP,HIV,BTYPE,WEIGHT,COMPLEXION,HANDICAPPED,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,CONTACT,SHOWADDRESS,FAMILY_VALUES,FAMILY_TYPE,FAMILY_STATUS,FAMILY_BACK AS FATHER_OCC,MOTHER_OCC,T_BROTHER,M_BROTHER,T_SISTER,M_SISTER,FAMILYINFO,EDUCATION,WORK_STATUS,MARRIED_WORKING,JOB_INFO,SUBCASTE,GOTHRA,ANCESTRAL_ORIGIN,MANGLIK,NAKSHATRA,RASHI,HOROSCOPE_MATCH,SHOW_HOROSCOPE,PHOTO_DISPLAY,YOURINFO,SPOUSE";

			$sql_jp = "SELECT $jp_select_fields FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res_jp = mysql_query_decide($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
			$row_jp = mysql_fetch_assoc($res_jp);
			foreach($row_jp as $key => $value)
				$proc->setParameter("",$key,$value);

			list($year_of_birth,$month_of_birth,$day_of_birth) = @explode("-",$row_jp['DTOFBIRTH']);
			$proc->setParameter("","YEAR_OF_BIRTH",$year_of_birth);
			$proc->setParameter("","MONTH_OF_BIRTH",$month_of_birth);
			$proc->setParameter("","DAY_OF_BIRTH",$day_of_birth);
			
			/* For Finding the Spoken Languages */
			$sql="SELECT * FROM newjs.JHOBBY where PROFILEID='$profileid'";
			$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed.Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($result) > 0)
			{
		                $myrow=mysql_fetch_array($result);
				$sql="select VALUE,LABEL,TYPE from HOBBIES order by SORTBY";
				$result_hobby=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				while($myhobby=mysql_fetch_array($result_hobby))
				{
					$HOBBIES_ARR[$myhobby["VALUE"]]=array("LABEL" => $myhobby["VALUE"],
									      "TYPE" =>  $myhobby["TYPE"]);
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
				if(is_array($LANGUAGE))
				{
					$spoken_languages = implode(",",$LANGUAGE);
				}
			}
			/* End of the Section */
		
			$country_residence = $row_jp['COUNTRY_RES'];
			//$spoken_languages = $row_jp['SPOKEN_LANGUAGES'];
			$mtongue = $row_jp['MTONGUE'];
			$religion_val = $row_jp['RELIGION'];
			$caste = $row_jp['CASTE'];

			if($religion_val == "2")
			{
				$muslim_select_fields = "MATHTHAB,NAMAZ,ZAKAT,FASTING,UMRAH_HAJJ,QURAN,SUNNAH_BEARD,SUNNAH_CAP,HIJAB,HIJAB_MARRIAGE,WORKING_MARRIAGE";
				$sql_muslim = "SELECT $muslim_select_fields FROM newjs.JP_MUSLIM WHERE PROFILEID='$profileid'";
				$res_muslim = mysql_query_decide($sql_muslim) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_muslim,"ShowErrTemplate");
				$row_muslim = mysql_fetch_assoc($res_muslim);
				foreach($row_muslim as $key => $value)
					$proc->setParameter("",$key,$value);
			}
			elseif($religion_val == "3")
			{
				$christian_select_fields = "DIOCESE,BAPTISED,READ_BIBLE,OFFER_TITHE,SPREADING_GOSPEL";
				$sql_christian = "SELECT $christian_select_fields FROM newjs.JP_CHRISTIAN WHERE PROFILEID='$profileid'";
				$res_christian = mysql_query_decide($sql_christian) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_christian,"ShowErrTemplate");
				$row_christian = mysql_fetch_assoc($res_christian);
				foreach($row_christian as $key => $value)
					$proc->setParameter("",$key,$value);
			}
			elseif($religion_val == "4")
			{
				$sikh_select_fields = "AMRITDHARI,CUT_HAIR,TRIM_BEARD,WEAR_TURBAN,CLEAN_SHAVEN";
				$sql_sikh = "SELECT $sikh_select_fields FROM newjs.JP_SIKH WHERE PROFILEID='$profileid'";
				$res_sikh = mysql_query_decide($sql_christian) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sikh,"ShowErrTemplate");
				$row_sikh = mysql_fetch_assoc($res_sikh);
				foreach($row_sikh as $key => $value)
					$proc->setParameter("",$key,$value);
			}
			elseif($religion_val == "5")
			{
				$parsi_select_fields = "ZARATHUSHTRI, PARENTS_ZARATHUSHTRI";
				$sql_parsi = "SELECT $parsi_select_fields FROM newjs.JP_PARSI WHERE PROFILEID='$profileid'";
				$res_parsi = mysql_query_decide($sql_parsi) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_muslim,"ShowErrTemplate");
				$row_parsi = mysql_fetch_assoc($res_parsi);
				foreach($row_parsi as $key => $value)
					$proc->setParameter("",$key,$value);
			}
			elseif($religion_val == "9" && $caste == "175")
			{
				$jain_select_fields = "SAMPRADAY";
				$sql_jain = "SELECT $jain_select_fields FROM newjs.JP_MUSLIM WHERE PROFILEID='$profileid'";
				$res_jain = mysql_query_decide($sql_jain) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jain,"ShowErrTemplate");
				$row_jain = mysql_fetch_assoc($res_jain);
				foreach($row_jain as $key => $value)
					$proc->setParameter("",$key,$value);
			}

			$jpartner_select_fields = "PARTNER_CITYRES, PARTNER_DIET, PARTNER_DRINK, PARTNER_SMOKE, HANDICAPPED";

			$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj,$jpartner_select_fields);

			$proc->setParameter("","PARTNER_CITYRES_STR",$jpartnerObj->getPARTNER_CITYRES);
			$proc->setParameter("","PARTNER_DIET_STR",$jpartnerObj->getPARTNER_DIET);
			$proc->setParameter("","PARTNER_DRINK_STR",$jpartnerObj->getPARTNER_DRINK);
			$proc->setParameter("","PARTNER_SMOKE_STR",$jpartnerObj->getPARTNER_SMOKE);
			$proc->setParameter("","PARTNER_HANDICAPPED_STR",$jpartnerObj->getHANDICAPPED);
			$proc->setParameter("","SPOKEN_LANGUAGES_STR",$spoken_languages);
		}

		if($groupname=='wchutney')
		{
			$proc->setParameter("","SCRIPT_NAME",$script_name);
			$proc->setParameter("","HTTP_REFERER",$http_referer);
			$proc->setParameter("","REMOTE_HOST",$remote_host);
			$proc->setParameter("","RFR",$rfr);
			$proc->setParameter("","GROUPNAME",$groupname);
		}

		

		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
		{
			$data=$id;
			$profileid=$id;
		}
		else
		{
			$data=authenticated($checksum,'y');
			$profileid=$data["PROFILEID"];
			if(!$profileid)
			{
				$data = login($username,$password);
				$checksum = $data['CHECKSUM'];
				$profileid = $data['PROFILEID'];
			}
			$jpartnerObj->setPROFILEID($profileid);
		}

		if($data["BUREAU"]==1 && $_COOKIE["JSMBLOGIN"])
		{
			include_once($path.'/marriage_bureau/connectmb.inc');
			mysql_select_db_js('marriage_bureau');
			$data=authenticatedmb($mbchecksum);
			if(!$data)
				timeoutmb();
			$proc->setParameter("","MBCHECKSUM",$data["CHECKSUM"]);
			$proc->setParameter("",'SOURCE',$data["SOURCE"]);
			mysql_select_db_js('newjs');
			$proc->setParameter("","FROMMARRIAGEBUREAU","1");
		}

		if($source=="")
		{
			if($newsource!="")
				$source=$newsource;
			elseif(isset($_COOKIE['JS_SOURCE']))
				$source=$_COOKIE['JS_SOURCE'];
		}
		// if source has come in that means that the person has clicked on a banner on jeevansathi
		// we make source blank in index.php before including this file to implement this logic
		else
		{
			if(isset($_COOKIE['JS_SOURCE']))
				$source=$_COOKIE['JS_SOURCE'];
		}

		//checking for gender cookie
		if(isset($_COOKIE["JS_GENDER"]))
			$cookie_gender=$_COOKIE["JS_GENDER"];
		$proc->setParameter("","SOURCE",$tieup_source);

		//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
		if($_COOKIE["JS_SHORT_FORM"])
		{
			$sql="SELECT COUNT(*) AS CNT FROM FROM_GOOGLE_HITS WHERE DATE=CURDATE()";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			$cnt=$row['CNT'];

			if($cnt>0)
				$sql="UPDATE FROM_GOOGLE_HITS set PAGE1=PAGE1+1 WHERE DATE=CURDATE()";
			else
				$sql="INSERT FROM_GOOGLE_HITS(DATE,PAGE1) values ('$now','1')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3

		if($frommarriagebureau)
			$proc->setParameter("","FROMMARRIAGEBUREAU",$frommarriagebureau);

		$proc->setParameter("","CHECKSUM",$checksum);
		$proc->setParameter("","USERNAME",$username);
		$proc->setParameter("","PASSWORD",$password);
		$proc->setParameter("","PROFILEID",$profileid);
		if($gender == "M")
			$name_of_user = "Mr.".$fname_user." ".$lname_user;
		elseif($gender == "F")
			$name_of_user = "Ms.".$fname_user." ".$lname_user;
		$proc->setParameter("","FULL_NAME",$name_of_user);
		$proc->setParameter("","FIRST_NAME",$fname_user);
		$proc->setParameter("","TIEUP_SOURCE",$tieup_source);
		$proc->setParameter("","HITSOURCE",$hit_source);

		$proc->setParameter("","RELIGION_ETHNICITY_SHOW","1");
		
		if($country_residence != $citizenship)
			$proc->setParameter("","SHOW_RESIDENT_STATUS","1");
		else
			$proc->setParameter("","SHOW_RESIDENT_STATUS","0");
		if($country_residence == '51')
			$proc->setParameter("","SHOW_RESIDENT_STATUS","2");
		if($religion_val == "1")
			$proc->setParameter("","CASTE_SEL","HINDU");
		elseif($religion_val == "2")
			$proc->setParameter("","CASTE_SEL","MUSLIM");
		elseif($religion_val == "3")
			$proc->setParameter("","CASTE_SEL","CHRISTIAN");
		elseif($religion_val == "4")
			$proc->setParameter("","CASTE_SEL","SIKH");
		elseif($religion_val == "5")
			$proc->setParameter("","CASTE_SEL","PARSI");
		elseif($religion_val == "6")
		{
			$proc->setParameter("","RELIGION_ETHNICITY_SHOW","0");
			$proc->setParameter("","NEXT",$religion_val);
		}
		elseif($religion_val == "7")
		{
			$proc->setParameter("","RELIGION_ETHNICITY_SHOW","0");
			$proc->setParameter("","NEXT",$religion_val);
		}
		elseif($religion_val == "9")
		{
			$proc->setParameter("","CASTE_SEL","JAIN");
			if($caste != "175")
				$proc->setParameter("","RELIGION_ETHNICITY_SHOW","0");
		}
		$proc->setParameter("","GENDER",$gender);

		loadMyXml($path."/profile/registration_pg2_eng.xml", $path."/profile/registration_common_eng.xml");

		$profile_percent = profile_percent_new($profileid);
		$proc->setParameter("","PROFILE_PERCENT",$profile_percent);

		$sql = "SELECT LABEL FROM newjs.COUNTRY_NEW WHERE VALUE = '$country_residence'";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$row = mysql_fetch_array($res);
		createXmlTag("registrationPage2","populate","country",utf8_encode($row["LABEL"]));

		$sql = "SELECT LABEL, VALUE FROM newjs.CITY_NEW WHERE VALUE='$city_residence'";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$row = mysql_fetch_array($res);
		$proc->setParameter("","CITY_LABEL",utf8_encode($row['LABEL']));

		$sql = "SELECT LABEL, VALUE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = '$country_residence' AND TYPE!='STATE'";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","partnerCity",utf8_encode($row['LABEL']),"value",$row['VALUE']);

		$sql = "SELECT LABEL, VALUE FROM newjs.CITY_NEW WHERE TYPE!='STATE' ORDER BY SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","partnerCityAll",utf8_encode($row['LABEL']),"value",$row['VALUE']);

		$sql = "SELECT LABEL, VALUE, MTONGUE_VAL FROM newjs.LANGUAGES WHERE VISIBLE='Y' ORDER BY ALPHA_SORT";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
		{
			createXmlTag("registrationPage2","populate","spokenLanguages",$row['LABEL'],"value",$row['VALUE']);

			$mtongue_arr = @explode(",",$row["MTONGUE_VAL"]);
			if(@in_array($mtongue,$mtongue_arr) && !$spoken_languages)
			{
				$spoken_languages_str = "'".$row['VALUE']."'";
				$proc->setParameter("","SPOKEN_LANGUAGES_STR",$spoken_languages_str);
			}
		}

		$sql = "SELECT LABEL, VALUE FROM FAMILY_BACK ORDER BY SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","fatherOccupation",$row['LABEL'],"value",$row['VALUE']);

		$sql = "SELECT LABEL, VALUE FROM MOTHER_OCC ORDER BY SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","motherOccupation",$row['LABEL'],"value",$row['VALUE']);

		//apply condition for KANNAD later.
		$sql = "SELECT OTHERS, KANNAD, VALUE FROM newjs.NAKSHATRA";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","nakshatra",$row['OTHERS'],"value",$row['VALUE']);

		$sql = "SELECT LABEL,VALUE FROM newjs.RASHI";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
			createXmlTag("registrationPage2","populate","rashi",$row['LABEL'],"value",$row['VALUE']);

		if($religion_val == "2")
		{
			$sql_maththab = "SELECT VALUE,LABEL FROM newjs.CASTE WHERE PARENT = '$caste'";
			$res_maththab = mysql_query_decide($sql_maththab) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_maththab,"ShowErrTemplate");
			while($row_maththab = mysql_fetch_array($res_maththab))
				createXmlTag("registrationPage2","populate","maththab",utf8_encode($row_maththab['LABEL']),"value",$row_maththab['VALUE']);
		}

		$xml->saveXML();

		$xsl = new DomDocument;
		$file_string = file_get_contents($path."/profile/registration_pg2.xsl");
		$file_string = trim_whitespace($file_string);
		$xsl->loadXML("$file_string");
		$proc->importStyleSheet($xsl);
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo $proc->transformToXML($xml);
		die;
	}
?>
