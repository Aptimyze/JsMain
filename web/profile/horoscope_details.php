<?php
	///to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

        include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	include_once("search.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");
	$db=connect_db();

	$data=authenticated($checksum);
	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
	
	if($data)
	{
		//Added for contact details and horoscope link on left panel
		login_relogin_auth($data);
		//Ends here
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

		$astro_detail_exists=0;
		$profileid = $data['PROFILEID'];
		$today = date("Y-m-d");

		//added by sriram for user's mtongue
		$sql_mtongue = "SELECT MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_mtongue = mysql_query_decide($sql_mtongue) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mtongue,"ShowErrTemplate");
		$row_mtongue = mysql_fetch_array($res_mtongue);
		$mtongue = $row_mtongue['MTONGUE'];
		//end of added by sriram.

		//section to update TYPE in newjs.ASTRO_DETAILS when the user has switched from System generated horoscope to uploaded horoscope or the other way round
		if($type)	
		{
			$objUpdate = JProfileUpdateLib::getInstance();
			$result = $objUpdate->updateASTRO_DETAILS($profileid, array('TYPE'=>$type));
			if(false === $result) {
				$sql_update = "update newjs.ASTRO_DETAILS set TYPE='$type' WHERE PROFILEID='$profileid'";
				logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
			}
//			$sql_update = "update newjs.ASTRO_DETAILS set TYPE='$type' WHERE PROFILEID='$profileid'";
//			mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
		}
		//end of section to update TYPE in newjs.ASTRO_DETAILS when the user has switched from System generated horoscope to uploaded horoscope or the other way round

		$sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) > 0)
		{
			$row1 = mysql_fetch_array($result);
			$astro_detail_exists=1;
		}
		//if user has already entered his astro details once, then show him his chart details and a button to update the horoscope.
		if($astro_detail_exists && !$update_astrodetails_now)
		{
			//if the user has clicked update button
			if($update_horoscope)
			{
				//storing in ASTRO_PULLING_REQUEST, incase the user fills the entire details but does not save the details then we use this table to save his details from cron.
				$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','C')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				//storing the click on update button.

				$sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES('$profileid','C',NOW(),'$mtongue')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}

			//if($row1['BTIME'] && !$update_horoscope && $row1['TYPE']=='S')
			//if((!$update_horoscope && $row1['TYPE']=='S') || (!$update_horoscope && $row1['TYPE']=='U' && $row1['HOROSCOPE_SCREENING']=='0'))
			if(!$update_horoscope)
			{
				$date_of_birth = explode("-",$row1['DTOFBIRTH']);
				$astrodata['DTOFBIRTH'] = my_format_date($date_of_birth[2],$date_of_birth[1],$date_of_birth[0]);
				$astrodata['BTIME'] = $row1['BTIME'];
				$astrodata['BPLACE'] = $row1['CITY_BIRTH'];
				$astrodata['BSTATE'] = $row1['PLACE_BIRTH'];

				list($astrodata['BIRTH_YR'],$astrodata['BIRTH_MON'],$astrodata['BIRTH_DAY']) = explode("-",$row1['DTOFBIRTH']);
				list($astrodata['BIRTH_HR'],$astrodata['BIRTH_MIN'],$astrodata['BIRTH_SEC']) = explode(":",$row1['BTIME']);
				$astrodata['LATITUDE']= $row1['LATITUDE'];
				$astrodata['LONGITUDE']= $row1['LONGITUDE'];
				$astrodata['TIMEZONE']=$row1['TIMEZONE'];
				$astrodata['DST']=$row1['DST'];
				$astrodata['PLACE_BIRTH']=$row1['PLACE_BIRTH'];

				$astrodata['LAGNA_DEGREES_FULL'] = $row1['LAGNA_DEGREES_FULL'];
				$astrodata['SUN_DEGREES_FULL'] = $row1['SUN_DEGREES_FULL'];
				$astrodata['MOON_DEGREES_FULL'] = $row1['MOON_DEGREES_FULL'];
				$astrodata['MARS_DEGREES_FULL'] = $row1['MARS_DEGREES_FULL'];
				$astrodata['MERCURY_DEGREES_FULL'] = $row1['MERCURY_DEGREES_FULL'];
				$astrodata['JUPITER_DEGREES_FULL'] = $row1['JUPITER_DEGREES_FULL'];
				$astrodata['VENUS_DEGREES_FULL'] = $row1['VENUS_DEGREES_FULL'];
				$astrodata['SATURN_DEGREES_FULL'] = $row1['SATURN_DEGREES_FULL'];
				$astrodata['RAHU_DEGREES_FULL'] = $row1['RAHU_DEGREES_FULL'];
				$astrodata['KETU_DEGREES_FULL'] = $row1['KETU_DEGREES_FULL'];
				$astrodata['MOON_RETRO_COMBUST'] = $row1['MOON_RETRO_COMBUST'];
				$astrodata['MARS_RETRO_COMBUST'] = $row1['MARS_RETRO_COMBUST'];
				$astrodata['MERCURY_RETRO_COMBUST'] = $row1['MERCURY_RETRO_COMBUST'];
				$astrodata['JUPITER_RETRO_COMBUST'] = $row1['JUPITER_RETRO_COMBUST'];
				$astrodata['VENUS_RETRO_COMBUST'] = $row1['VENUS_RETRO_COMBUST'];
				$astrodata['SATURN_RETRO_COMBUST'] = $row1['SATURN_RETRO_COMBUST'];
				$astrodata['VARA'] = $row1['VARA'];
				$astrodata['MASA'] = $row1['MASA'];
			}
			//if the person has choosen to show his system generated horoscope
			if(!$update_horoscope && $row1['TYPE']=='S')
			{
				$smarty->assign("horoscope_exists","Y");
				
				//if the person has choosen to show his system generated horoscope and has uploaded his horoscope also 
				if($row1['HOROSCOPE_SCREENING']=='1')
				{
					$smarty->assign("horoscope_exists_and_uploaded","Y");
				}
			}
			//if the person has uploaded his horoscope and choosen to show his uploaded horoscope
			elseif(!$update_horoscope && $row1['TYPE']=='U')
			{
				//if the person has uploaded his horoscope and has been screened
				if($row1['HOROSCOPE_SCREENING']=='1')
				{
					//if the horoscope of the person has been uploaded OFFLINE and we dont have his complete details in ASTRO_DETAILS table
					if(!$astrodata['BPLACE'])
						$smarty->assign("offline_horoscope","Y");
					//echo "show uploaded screened horoscope";
					$smarty->assign("horoscope_exists","YU1");
				}
				//if the person has uploaded his horoscope and is under screening
				else
				{
					$msg="Your scanned horoscope has been uploaded and is now under screening!<br>The horoscope will be visible on your profile within 2-3 working days.";
					$smarty->assign("msg",$msg);
					$smarty->assign("horoscope_exists","YU0");
					//$smarty->display("horoscope_status.htm");
					//exit();
				}
			}
		}
		//if the user is creating the horoscope for first time.
		else
		{
			if($new_horoscope)
			{
				//storing in ASTRO_PULLING_REQUEST, incase the user fills the entire details but does not save the details then we use this table to save his details from cron.
				$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','A')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

				//storing the click on add button.
				$sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES('$profileid','A',NOW(),'$mtongue')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
			$smarty->assign("horoscope_exists","N");
		}
		//finding the user's date of birth from JPROFILE table.
		$sql_dob = "SELECT DTOFBIRTH FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$result_dob=mysql_query_decide($sql_dob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_dob,"ShowErrTemplate");
		$row_dob = mysql_fetch_array($result_dob);

		$date_of_birth = explode("-",$row_dob['DTOFBIRTH']);
		$astrodata['DTOFBIRTH'] = my_format_date($date_of_birth[2],$date_of_birth[1],$date_of_birth[0]);
		list($astrodata['BIRTH_YR'],$astrodata['BIRTH_MON'],$astrodata['BIRTH_DAY']) = explode("-",$row_dob['DTOFBIRTH']);
		//if user clicks the DONE button from i-frame's third page.(i.e if the user want's to generate horoscope immediately)
		//if user clicks the DONE button from i-frame's third page then show him the page with 2 options ie. UPLOAD HOROSCOPE and VIEW SYSTEM GENERATED HOROSCOPE

		if($update_astrodetails_now)
		{
			//$smarty->display("horoscope_create_update.htm");
			//exit();
			
			//added by sriram
			//mysql_close();

			//$url = "http://www.matchstro.com/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Show_AstroData?JS_UniqueID=".$profileid;
			$url = "http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Show_AstroData?JS_UniqueID=".$profileid;
			//reading the data from matchstro into a file.
			$f = fopen($url,"r");
			//reading the entire file into an array.
			$fp = file($url);
			//$fp[0] ="$profileid;1976-07-17;00:11:00;Noida, Uttar Pradesh;India;28N35'00;077E20'00;-5:30;0;175.823489;90.788770;333.367421;132.414220;92.151259;31.476198;98.666959;101.372354;195.261664;15.261664;0;0;1;0;1;1;5;3";
			//$f=1;
			//print_r($fp);
			//addtional check added by sriram to prevent data with (") being stored in database.
			
			//added by sriram
			$db = connect_db();

			if(strstr($fp[0],"\";\""))
			{
				if($f)
				{
					foreach ($fp as $line)
					{
						$ms_data = explode("\";",$line);
					}
				}

				update_astrodetails($profileid,"",$mtongue);

				$date_of_birth = explode("-",substr($ms_data[1],1));
				$astrodata['DTOFBIRTH'] = my_format_date($date_of_birth[2],$date_of_birth[1],$date_of_birth[0]);
                                $astrodata['BTIME'] = substr($ms_data[2],1);
                                list($astrodata['BIRTH_YR'],$astrodata['BIRTH_MON'],$astrodata['BIRTH_DAY']) = explode("-",$astrodata['DTOFBIRTH']);
                                list($astrodata['BIRTH_HR'],$astrodata['BIRTH_MIN'],$astrodata['BIRTH_SEC']) = explode(":",$astrodata['BTIME']);
                                $astrodata['BPLACE'] = substr($ms_data[3],1);
                                $astrodata['LATITUDE'] = substr($ms_data[5],1);
                                $astrodata['LONGITUDE'] = substr($ms_data[6],1);
                                $astrodata['TIMEZONE'] = substr($ms_data[7],1);
                                $astrodata['DST'] = substr($ms_data[8],1,4);
			}
			else
			{
				if($f)
				{
					foreach ($fp as $line)
					{
						$ms_data = explode(";",$line);
					}
				}

				update_astrodetails($profileid,$ms_data,$mtongue);

				$date_of_birth = explode("-",$ms_data[1]);
				$astrodata['DTOFBIRTH'] = my_format_date($date_of_birth[2],$date_of_birth[1],$date_of_birth[0]);
				$astrodata['BTIME'] = $ms_data[2];
				list($astrodata['BIRTH_YR'],$astrodata['BIRTH_MON'],$astrodata['BIRTH_DAY']) = explode("-",$ms_data[1]);
				list($astrodata['BIRTH_HR'],$astrodata['BIRTH_MIN'],$astrodata['BIRTH_SEC']) = explode(":",$astrodata['BTIME']);
				$astrodata['BPLACE'] = $ms_data[3];
				//$astrodata['CITY_BIRTH'] = $ms_data[4];
				$astrodata['LATITUDE']= $ms_data[5];
				$astrodata['LONGITUDE']= $ms_data[6];
				$astrodata['TIMEZONE']=$ms_data[7];
				$astrodata['DST']=$ms_data[8];

				$astrodata['LAGNA_DEGREES_FULL'] = $ms_data[9];
				$astrodata['SUN_DEGREES_FULL'] = $ms_data[10];
				$astrodata['MOON_DEGREES_FULL'] = $ms_data[11];
				$astrodata['MARS_DEGREES_FULL'] = $ms_data[12];
				$astrodata['MERCURY_DEGREES_FULL'] = $ms_data[13];
				$astrodata['JUPITER_DEGREES_FULL'] = $ms_data[14];
				$astrodata['VENUS_DEGREES_FULL'] = $ms_data[15];
				$astrodata['SATURN_DEGREES_FULL'] = $ms_data[16];
				$astrodata['RAHU_DEGREES_FULL'] = $ms_data[17];
				$astrodata['KETU_DEGREES_FULL'] = $ms_data[18];
				$astrodata['MOON_RETRO_COMBUST'] = $ms_data[19];
				$astrodata['MARS_RETRO_COMBUST'] = $ms_data[20];
				$astrodata['MERCURY_RETRO_COMBUST'] = $ms_data[21];
				$astrodata['JUPITER_RETRO_COMBUST'] = $ms_data[22];
				$astrodata['VENUS_RETRO_COMBUST'] = $ms_data[23];
				$astrodata['SATURN_RETRO_COMBUST'] = $ms_data[24];
				$astrodata['VARA'] = $ms_data[25];
				$astrodata['MASA'] = $ms_data[26];
			}

			$smarty->assign("horoscope_exists","Y");
		
			$smarty->display("horoscope_create_update.htm");
			exit();
		}


		/*
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		*/
		if(!$update_astrodetails_now)
		{
			$smarty->assign("SITE_URL",$SITE_URL);
			$smarty->assign('astrodata',$astrodata);
			$smarty->assign('js_UniqueID',$profileid);
			$smarty->assign('checksum',$checksum);
			$COUNTRY_BIRTH = substr($ms_data[4],1);
			$country_residence=create_dd($COUNTRY_BIRTH,"Country_Residence");
			$smarty->assign('BIRTH_PLACE',$country_residence);
			$city_residence=create_dd($astrodata['CITY_BIRTH'],"City_India");
                        $smarty->assign('CITY_BIRTH',$city_residence);
			$smarty->display("profile_edit_horoscope.htm");
			//$smarty->display("astro_chart.htm");
		}
	}
	else
	{
		TimedOut();
	}

//this functions adds/updates the user's astro details in ASTRO_DETAILS table.
	function update_astrodetails($profileid,$ms_data="",$mtongue="")
	{
		$objUpdate = JProfileUpdateLib::getInstance();
		$objReplace = ProfileReplaceLib::getInstance();
		if(!$ms_data)
		{
			//$fp[0] ="$profileid;1976-07-17;00:11:00;Noida, Uttar Pradesh;India;28N35'00;077E20'00;-5:30;0;175.823489;90.788770;333.367421;132.414220;92.151259;31.476198;98.666959;101.372354;195.261664;15.261664;0;0;1;0;1;1;5;3";
			//$f=1;

			//added by sriram.
			//mysql_close();
			
			//$url = "http://www.matchstro.com/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Show_AstroData?JS_UniqueID=".$profileid;
			$url = "http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Show_AstroData?JS_UniqueID=".$profileid;
			//reading the data from matchstro into a file.
			$f = fopen($url,"r");
			//reading the entire file into an array.
			$fp = file($url);
			
			//added by sriram.
			$db = connect_db();

			//addtional check added by sriram to prevent data with (") being stored in database.
                        if(strstr($fp[0],"\";\""))
                        {
                                if($f)
                                {
                                        foreach ($fp as $line)
                                        {
                                                $ms_data = explode("\";",$line);
                                        }
                                }
				$DTOFBIRTH= substr($ms_data[1],1);
				$BTIME = substr($ms_data[2],1);
				$CITY_BIRTH = rtrim(substr($ms_data[3],1),",");
				if(strstr($CITY_BIRTH,"(") || strstr($CITY_BIRTH,")") )
				{
					$city_birth = str_replace("("," ",$CITY_BIRTH);
					$CITY_BIRTH = str_replace(")"," ",$city_birth);
				}

				$COUNTRY_BIRTH = substr($ms_data[4],1);
				$LATITUDE= substr($ms_data[5],1);
				$LONGITUDE= substr($ms_data[6],1);
				$TIMEZONE=substr($ms_data[7],1);
				$DST=substr(ereg_replace("\r\n|\n|\r|\n\r","",$ms_data[8]),1,4);
				$old = 1;
                        }
                        else
                        {
				if ($f)
				{
					foreach ($fp as $line)
					{
						$ms_data = explode(";",$line);
					}
				}
			}
		}
		if(!$old)
		{
			$DTOFBIRTH= $ms_data[1];
			$BTIME = $ms_data[2];
			$CITY_BIRTH = rtrim($ms_data[3],",");
			if(strstr($CITY_BIRTH,"(") || strstr($CITY_BIRTH,")") )
			{
				$city_birth = str_replace("("," ",$CITY_BIRTH);
				$CITY_BIRTH = str_replace(")"," ",$city_birth);
			}

			$COUNTRY_BIRTH = $ms_data[4];
			$LATITUDE= $ms_data[5];
			$LONGITUDE= $ms_data[6];
			$TIMEZONE=$ms_data[7];
			$DST=$ms_data[8];

			$LAGNA_DEGREES_FULL = $ms_data[9];
			$SUN_DEGREES_FULL = $ms_data[10];
			$MOON_DEGREES_FULL = $ms_data[11];
			$MARS_DEGREES_FULL = $ms_data[12];
			$MERCURY_DEGREES_FULL = $ms_data[13];
			$JUPITER_DEGREES_FULL = $ms_data[14];
			$VENUS_DEGREES_FULL = $ms_data[15];
			$SATURN_DEGREES_FULL = $ms_data[16];
			$RAHU_DEGREES_FULL = $ms_data[17];
			$KETU_DEGREES_FULL = $ms_data[18];
			$MOON_RETRO_COMBUST = $ms_data[19];
			$MARS_RETRO_COMBUST = $ms_data[20];
			$MERCURY_RETRO_COMBUST = $ms_data[21];
			$JUPITER_RETRO_COMBUST = $ms_data[22];
			$VENUS_RETRO_COMBUST = $ms_data[23];
			$SATURN_RETRO_COMBUST = $ms_data[24];
			$VARA = $ms_data[25];
			$MASA = ereg_replace("\r\n|\n|\r|\n\r","",$ms_data[26]);
		}
		//if either of time, city, country of birth is entered.
		//echo $BTIME."=".$CITY_BIRTH."=".$COUNTRY_BIRTH."<br>";
		if($BTIME || $CITY_BIRTH || $COUNTRY_BIRTH)
		{
			$sql_is_same = "SELECT COUNTRY_BIRTH, CITY_BIRTH, BTIME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
			$res_is_same = mysql_query_decide($sql_is_same) or logError($sql_is_same,"ShowErrTemplate");
			$row_is_same = mysql_fetch_array($res_is_same);
			$JPROFILE_COUNTRY = label_select("COUNTRY",$row_is_same['COUNTRY_BIRTH']);

			if($JPROFILE_COUNTRY[0]!=$COUNTRY_BIRTH || $row_is_same['CITY_BIRTH']!=$CITY_BIRTH ||  $row_is_same['BTIME']!=substr($BTIME,0,5))
			{
				//check if country exists in our database, else store it in UNAVAILABLE_ASTRO_COUNTRY table.
				$sql_ctry = "SELECT VALUE FROM newjs.COUNTRY WHERE LABEL='$COUNTRY_BIRTH'";
				$res_ctry = mysql_query_decide($sql_ctry) or logError($sql_ctry,"ShowErrTemplate");
				$row_ctry = mysql_fetch_array($res_ctry);
				if ($row_ctry['VALUE'])
					$COUNTRY_BIRTH_EXIST = $row_ctry['VALUE'];
				else
				{
					$sql  = "INSERT INTO newjs.UNAVAILABLE_ASTRO_COUNTRY(PROFILEID,COUNTRY_BIRTH,ENTRY_DT) VALUES ('$profileid','$COUNTRY_BIRTH',NOW())";
					mysql_query_decide($sql) or logError($sql,"ShowErrTemplate");
				}

				//store the astro details.
				/*$sql1 = "REPLACE INTO newjs.ASTRO_DETAILS(
					PROFILEID,
					CITY_BIRTH,
					DTOFBIRTH,
					BTIME,
					COUNTRY_BIRTH,
					PLACE_BIRTH,
					LATITUDE,
					LONGITUDE,
					TIMEZONE,
					DST,
					LAGNA_DEGREES_FULL,
					SUN_DEGREES_FULL,
					MOON_DEGREES_FULL,
					MARS_DEGREES_FULL,
					MERCURY_DEGREES_FULL,
					JUPITER_DEGREES_FULL,
					VENUS_DEGREES_FULL,
					SATURN_DEGREES_FULL,
					RAHU_DEGREES_FULL,
					KETU_DEGREES_FULL,
					MOON_RETRO_COMBUST,
					MARS_RETRO_COMBUST,
					MERCURY_RETRO_COMBUST,
					JUPITER_RETRO_COMBUST,
					VENUS_RETRO_COMBUST,
					SATURN_RETRO_COMBUST,
					VARA,
					MASA,
					SHOW_HOROSCOPE
					)
				VALUES (
					'$profileid',
					'".addslashes(stripslashes($CITY_BIRTH))."',
					'$DTOFBIRTH',
					'$BTIME',
					'".addslashes(stripslashes($COUNTRY_BIRTH))."',
					'".addslashes(stripslashes($CITY_BIRTH))."',
					'".addslashes(stripslashes($LATITUDE))."',
					'".addslashes(stripslashes($LONGITUDE))."',
					'".addslashes(stripslashes($TIMEZONE))."',
					'".addslashes(stripslashes($DST))."',
					'".addslashes(stripslashes($LAGNA_DEGREES_FULL))."',
					'".addslashes(stripslashes($SUN_DEGREES_FULL))."',
					'".addslashes(stripslashes($MOON_DEGREES_FULL))."',
					'".addslashes(stripslashes($MARS_DEGREES_FULL))."',
					'".addslashes(stripslashes($MERCURY_DEGREES_FULL))."',
					'".addslashes(stripslashes($JUPITER_DEGREES_FULL))."',
					'".addslashes(stripslashes($VENUS_DEGREES_FULL))."',
					'".addslashes(stripslashes($SATURN_DEGREES_FULL))."',
					'".addslashes(stripslashes($RAHU_DEGREES_FULL))."',
					'".addslashes(stripslashes($KETU_DEGREES_FULL))."',
					'".addslashes(stripslashes($MOON_RETRO_COMBUST))."',
					'".addslashes(stripslashes($MARS_RETRO_COMBUST))."',
					'".addslashes(stripslashes($MERCURY_RETRO_COMBUST))."',
					'".addslashes(stripslashes($JUPITER_RETRO_COMBUST))."',
					'".addslashes(stripslashes($VENUS_RETRO_COMBUST))."',
					'".addslashes(stripslashes($SATURN_RETRO_COMBUST))."',
					'".addslashes(stripslashes($VARA))."',
					'".addslashes(stripslashes($MASA))."',
					'Y')";
				
				mysql_query_decide($sql1) or logError($sql1,"ShowErrTemplate");*/
				$arrParams = array(
					"CITY_BIRTH" => addslashes(stripslashes($CITY_BIRTH)) ,
					"DTOFBIRTH" => $DTOFBIRTH,
					"BTIME" => $BTIME,
					"COUNTRY_BIRTH" => addslashes(stripslashes($COUNTRY_BIRTH)),
					"PLACE_BIRTH" => addslashes(stripslashes($CITY_BIRTH)),
					"LATITUDE" => addslashes(stripslashes($LATITUDE)),
					"LONGITUDE" => addslashes(stripslashes($LONGITUDE)),
					"TIMEZONE" => addslashes(stripslashes($TIMEZONE)),
					"DST" => addslashes(stripslashes($DST)),
					"LAGNA_DEGREES_FULL" => addslashes(stripslashes($LAGNA_DEGREES_FULL)),
					"SUN_DEGREES_FULL" => addslashes(stripslashes($SUN_DEGREES_FULL)),
					"MOON_DEGREES_FULL" => addslashes(stripslashes($MOON_DEGREES_FULL)),
					"MARS_DEGREES_FULL" => addslashes(stripslashes($MARS_DEGREES_FULL)),
					"MERCURY_DEGREES_FULL" => addslashes(stripslashes($MERCURY_DEGREES_FULL)),
					"JUPITER_DEGREES_FULL" => addslashes(stripslashes($JUPITER_DEGREES_FULL)),
					"VENUS_DEGREES_FULL" => addslashes(stripslashes($VENUS_DEGREES_FULL)),
					"SATURN_DEGREES_FULL" => addslashes(stripslashes($SATURN_DEGREES_FULL)),
					"RAHU_DEGREES_FULL" => addslashes(stripslashes($RAHU_DEGREES_FULL)),
					"KETU_DEGREES_FULL" => addslashes(stripslashes($KETU_DEGREES_FULL)),
					"MOON_RETRO_COMBUST" => addslashes(stripslashes($MOON_RETRO_COMBUST)),
					"MARS_RETRO_COMBUST" => addslashes(stripslashes($MARS_RETRO_COMBUST)),
					"MERCURY_RETRO_COMBUST" => addslashes(stripslashes($MERCURY_RETRO_COMBUST)),
					"JUPITER_RETRO_COMBUST" => addslashes(stripslashes($JUPITER_RETRO_COMBUST)),
					"VENUS_RETRO_COMBUST" => addslashes(stripslashes($VENUS_RETRO_COMBUST)),
					"SATURN_RETRO_COMBUST" => addslashes(stripslashes($SATURN_RETRO_COMBUST)),
					"VARA" => addslashes(stripslashes($VARA)),
					"MASA" => addslashes(stripslashes($MASA)),
					"SHOW_HOROSCOPE" => 'Y'

				);
				$objReplace->replaceASTRO_DETAILS($profileid, $arrParams);

				//Code added by Vibhor as discussed with Lavesh
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
				$mysqlObj=new Mysql;
				$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
				$myDb=$mysqlObj->connect("$myDbName");
                                $sql_pr="UPDATE HOROSCOPE_REQUEST SET UPLOAD_SEEN='U' WHERE PROFILEID_REQ_BY='$profileid'";
                                $mysqlObj->executeQuery($sql_pr,$myDb);
                                //end
                                
				$sql_type = "SELECT TYPE FROM newjs.ASTRO_PULLING_REQUEST WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC LIMIT 1";
				$res_type = mysql_query_decide($sql_type) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_type,"ShowErrTemplate");
				$row_type = mysql_fetch_array($res_type);
				$type = $row_type['TYPE'];

				//inserted in ASTRO_DATA_COUNT to track, total horoscope generated.
				$sql2 = "INSERT INTO MIS.ASTRO_DATA_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES ('$profileid','$type',NOW(),'$mtongue')";
				mysql_query_decide($sql2) or logError($sql2,"ShowErrTemplate");
                                
                                //adding mailing to gmail account to check if file is being used
                               include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
                               $cc='eshajain88@gmail.com';
                               $to='ankitshukla125@gmail.com';
                               $msg1='horoscope_details is being hit. We can wrap this to JProfileUpdateLib';
                               $subject="qc_view";
                               $msg=$msg1.print_r($_SERVER,true);
                               send_email($to,$msg,$subject,"",$cc);
                                //ending mail part
                                
				//update JPROFILE.
				if ($COUNTRY_BIRTH_EXIST){
					//$sql = "UPDATE newjs.JPROFILE SET  COUNTRY_BIRTH='$COUNTRY_BIRTH_EXIST', CITY_BIRTH='".addslashes(stripslashes($CITY_BIRTH))."',BTIME='$BTIME', SHOW_HOROSCOPE='Y' WHERE PROFILEID='$profileid'";
                                    $arrFields = array('COUNTRY_BIRTH'=>$COUNTRY_BIRTH_EXIST,'CITY_BIRTH'=>addslashes(stripslashes($CITY_BIRTH)),'BTIME'=>$BTIME,'SHOW_HOROSCOPE'=>Y);
                                }
				else{
					//$sql = "UPDATE newjs.JPROFILE SET CITY_BIRTH='".addslashes(stripslashes($CITY_BIRTH))."',BTIME='$BTIME' , SHOW_HOROSCOPE='Y' WHERE PROFILEID='$profileid'";
                                    $arrFields = array('CITY_BIRTH'=>addslashes(stripslashes($CITY_BIRTH)),'BTIME'=>$BTIME,'SHOW_HOROSCOPE'=>Y);
                                }	
                                
				//mysql_query_decide($sql) or logError($sql,"ShowErrTemplate");
                                $objUpdate->editJPROFILE($arrFields,$row[PROFILEID],"PROFILEID");
															     
				$sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET PENDING='N' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_update) or logError($sql_update,"ShowErrTemplate");
			}
			else
			{
				$sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET COUNTER=COUNTER+1 WHERE PROFILEID='$profileid' AND ENTRY_DT='$entry_dt'";
				mysql_query_decide($sql_update) or logError($sql_update,"ShowErrTemplate");
			}
		}
		
		{
//			$sql = "UPDATE newjs.JPROFILE SET SHOW_HOROSCOPE='N' WHERE PROFILEID='$profileid'";
//			mysql_query_decide($sql) or logError($sql,"ShowErrTemplate");
                        $arrFields = array('SHOW_HOROSCOPE'=>'N');
                        $objUpdate->editJPROFILE($arrFields,$profileid,"PROFILEID");
			$sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET COUNTER=COUNTER+1 , PENDING='U' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql_update) or logError($sql_update,"ShowErrTemplate");
		}

	}

	// flush the buffer
        if($zipIt)
                ob_end_flush();

?>
