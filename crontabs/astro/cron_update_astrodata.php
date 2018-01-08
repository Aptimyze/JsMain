<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

chdir(dirname(__FILE__));
include_once("../config.php");     //ADDED BY ANAND
//include("lock.php");
//$lock_handle=get_lock("/tmp/cron_update_astrodata");

include_once("../connect.inc");
//FILE INCLUDED BY ANAND
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once("../track_astro_info.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
//END
include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");
$db=connect_db();
mysql_query('set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=10000', $db);

$sql = "SELECT MAX( ENTRY_DT )  AS ENTRY_DT , PROFILEID,TYPE FROM newjs.ASTRO_PULLING_REQUEST WHERE PENDING IN ('Y','U') AND COUNTER < 3 GROUP BY PROFILEID";
$res = mysql_query($sql,$db) or logError($sql,"ShowErrTemplate");
$objReplace = ProfileReplaceLib::getInstance();
while($row = mysql_fetch_array($res))
{
	$profileid = $row['PROFILEID'];
	$entry_dt = $row['ENTRY_DT'];

	/*Added by sriram to run the cron conditionally depending on count and entry date.*/
	list($yy,$mm,$dd) = explode("-",substr($entry_dt,0,10));
	list($gg,$ii,$ss) = explode(":",substr($entry_dt,11));

	//entry timestamp.
	$entry_timestamp = mktime($gg,$ii,$ss,$mm,$dd,$yy);

	//one hour before timestamp.
	$ohb_timestamp = mktime(date('G')-1,date('i'),date('s'),date('m'),date('d'),date('Y'));

	//one day before timestamp.
	$odb_timestamp = mktime(date('G'),date('i'),date('s'),date('m'),date('d')-1,date('Y'));
	/*End of - Added by sriram to run the cron conditionally depending on count and entry date.*/

	if($row['COUNTER']==0 || ($row['COUNTER']==1 && ($ohb_timestamp > $entry_timestamp)) || ($row['COUNTER']==2 && ($odb_timestamp > $entry_timestamp)))
	{
		//pulling data from matchstro's database.
		$url = "https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Show_AstroData?JS_UniqueID=".$profileid;

		//reading the data from matchstro's database into a file.
		$line = trim(file_get_contents($url));

		if(strstr($line,"\";\""))
		{
			$data = explode("\";",$line);

			$astrodata['DTOFBIRTH']= substr($data[1],1);
			$astrodata['BTIME'] = substr($data[2],1);
			$astrodata['CITY_BIRTH'] = rtrim(substr($data[3],1),",");
			if(strstr($astrodata['CITY_BIRTH'],"(") || strstr($astrodata['CITY_BIRTH'],")") )
			{
				$city_birth = str_replace("("," ",$astrodata['CITY_BIRTH']);
				$astrodata['CITY_BIRTH'] = str_replace(")"," ",$city_birth);
			}
														     
			$astrodata['COUNTRY_BIRTH'] = substr($data[4],1);
			$astrodata['LATITUDE']= substr($data[5],1);
			$astrodata['LONGITUDE']= substr($data[6],1);
			$astrodata['TIMEZONE']=substr($data[7],1);
			$astrodata['DST']=substr(ereg_replace("\r\n|\n|\r|\n\r","",$data[8]),1,4);
			$astro_data_found = 1;
		}
		elseif(strstr($line,";"))
		{
			$data = explode(";",$line);

			$astrodata['DTOFBIRTH']= $data[1];
			$astrodata['BTIME'] = $data[2];

			$astrodata['CITY_BIRTH'] = rtrim($data[3],",");
			if(strstr($astrodata['CITY_BIRTH'],"(") || strstr($astrodata['CITY_BIRTH'],")") )
			{
				$city_birth = str_replace("("," ",$astrodata['CITY_BIRTH']);
				$astrodata['CITY_BIRTH'] = str_replace(")"," ",$city_birth);
			}

			$astrodata['COUNTRY_BIRTH'] = $data[4];
			$astrodata['LATITUDE'] = $data[5];
			$astrodata['LONGITUDE'] = $data[6];
			$astrodata['TIMEZONE'] = $data[7];
			$astrodata['DST'] = $data[8];

			$astrodata['LAGNA_DEGREES_FULL'] = $data[9];
			$astrodata['SUN_DEGREES_FULL'] = $data[10];
			$astrodata['MOON_DEGREES_FULL'] = $data[11];
			$astrodata['MARS_DEGREES_FULL'] = $data[12];
			$astrodata['MERCURY_DEGREES_FULL'] = $data[13];
			$astrodata['JUPITER_DEGREES_FULL'] = $data[14];
			$astrodata['VENUS_DEGREES_FULL'] = $data[15];
			$astrodata['SATURN_DEGREES_FULL'] = $data[16];
			$astrodata['RAHU_DEGREES_FULL'] = $data[17];
			$astrodata['KETU_DEGREES_FULL'] = $data[18];
			$astrodata['MOON_RETRO_COMBUST'] = $data[19];
			$astrodata['MARS_RETRO_COMBUST'] = $data[20];
			$astrodata['MERCURY_RETRO_COMBUST'] = $data[21];
			$astrodata['JUPITER_RETRO_COMBUST'] = $data[22];
			$astrodata['VENUS_RETRO_COMBUST'] = $data[23];
			$astrodata['SATURN_RETRO_COMBUST'] = $data[24];
			$astrodata['VARA'] = $data[25];
			$astrodata['MASA'] = preg_replace("/\r\n|\n|\r|\n\r/","",$data[26]);
			$astro_data_found = 1;
		}
		else
		{
			$astro_data_found = 0;
		}

		//if either time or city or country of birth exists.
		if($astro_data_found && ($astrodata['BTIME'] || $astrodata['CITY_BIRTH'] || $astrodata['COUNTRY_BIRTH']))
		{
			$sql_is_same = "SELECT COUNTRY_BIRTH, CITY_BIRTH, BTIME, DTOFBIRTH, EMAIL, USERNAME, RELIGION,MANGLIK,NAKSHATRA,RASHI,SUNSIGN,ACTIVATED,SHOW_HOROSCOPE,DATE(ENTRY_DT) AS ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID='$profileid'"; //DTOFBIRTH, EMAIL, USERNAME, RELIGION,MANGLIK,RASHI,NAKSHATRA,ACTIVATED added by ANAND
			$res_is_same = mysql_query($sql_is_same,$db) or logError($sql_is_same,"ShowErrTemplate");
			$row_is_same = @mysql_fetch_array($res_is_same);
			$JPROFILE_COUNTRY = label_select("COUNTRY",$row_is_same['COUNTRY_BIRTH'],$db);
			if($JPROFILE_COUNTRY[0]!=$astrodata['COUNTRY_BIRTH'] || $row_is_same['CITY_BIRTH']!=$astrodata['CITY_BIRTH'] ||  $row_is_same['BTIME']!=substr($astrodata['BTIME'],0,5))
			{
				//CODE ADDED BY ANAND TO GET ASTRO DETAILS NAMELY MANGLIK,NAKSHATRA,MOON-SIGN,SUN-SIGN*************************
				if ($row_is_same['ACTIVATED']!='D') {

				$mysqlObjM = new Mysql;

				$date_array = explode("-",$row_is_same['DTOFBIRTH']);

				$url1 = "https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?Show_AstrologicalInformation?Birth_Year=".$date_array[0]."&Lagna_Degrees_Full=".$astrodata['LAGNA_DEGREES_FULL']."&Sun_Degrees_Full=".$astrodata['SUN_DEGREES_FULL']."&Moon_Degrees_Full=".$astrodata['MOON_DEGREES_FULL']."&Mars_Degrees_Full=".$astrodata['MARS_DEGREES_FULL'];

				$fp1 = @trim(file_get_contents($url1)) or logError("Unable to open url","ShowErrTemplate");
				
				if ($fp1){				

                			$data1 = explode("<br>",$fp1);

        			$i=0;
        			foreach ($data1 as $line2)
        			{
                			$main_data[$i] = explode(":",$line2);
                			$i++;
        			}

				if($row_is_same['RELIGION']==1 || $row_is_same['RELIGION']==9)
				{
					if($row_is_same['RELIGION']==1)
					{
						if (trim($row_is_same['NAKSHATRA'])=='' || trim($row_is_same['NAKSHATRA'])=="Don't Know")
						{
							$astrodata['NAKSHATRA'] = trim($main_data[2][1]);
							//CHECK FOR NAKSHATRA AND FIND CORRESPONDING VALUES ELSE STORE IN A SEPARATE TABLE
							if ($nakshatra_matchastro[$astrodata['NAKSHATRA']])
							{
								$astrodata['NAKSHATRA'] = $NAKSHATRA_DROP[$nakshatra_matchastro[$astrodata['NAKSHATRA']]];
							}
							else
							{
							$statement2 = "insert into newjs.UNMATCHED_NAKSHATRA_MATCHASTRO(ID,NAME,PROFILEID) values ('','".$astrodata['NAKSHATRA']."','".$profileid."')";
							$mysqlObjM->executeQuery($statement2,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement2,"ShowErrTemplate");
							}
							//CHECK FOR NAKSHATRA ENDS HERE
						}

        	        			if (!($row_is_same['RASHI']>=2 && $row_is_same['RASHI']<=count($RASHI_DROP)))
						{
							$astrodata['MOON_SIGN'] = trim($main_data[1][1]);
							//CHECK FOR RASHI/MOON-SIGN AND FIND CORRESPONDING VALUES ELSE STORE IN A SEPARATE TABLE
							if ($rashi_matchastro[$astrodata['MOON_SIGN']])
							{
								$astrodata['MOON_SIGN'] = $rashi_matchastro[$astrodata['MOON_SIGN']];
							}
							else
							{
								$statement4 = "insert into newjs.UNMATCHED_RASHI_MATCHASTRO(NAME,PROFILEID) values ('".$astrodata['MOON_SIGN']."','".$profileid."')";
								$mysqlObjM->executeQuery($statement4,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement4,"ShowErrTemplate");
							}
							//CHECK FOR RASHI/MOON-SIGN ENDS HERE
						}
					}
		
					if ($row_is_same['MANGLIK']=='D' || trim($row_is_same["MANGLIK"])=='')
					{
						$astrodata['MANGLIK'] = trim($main_data[3][1]);
						//CHECK FOR MANGLIK AND STORE CORRESPONDING VALUES
        					if ($astrodata['MANGLIK']=='false')
                					$astrodata['MANGLIK']='N';
        					else if ($astrodata['MANGLIK']=='true')
                					$astrodata['MANGLIK']='M';
        					else
                				{}
        					//CHECK FOR MANGLIK ENDS HERE
					}
				}

				//CHECK FOR SUN-SIGN AND FIND CORRESPONDING VALUES ELSE STORE IN A SEPARATE TABLE	
				if(!($row_is_same["SUNSIGN"]>=2 && $row_is_same['SUNSIGN']<=count($SUNSIGN_DROP)))
				{
					$astrodata['SUN_SIGN'] = trim($main_data[0][1]);
        				if ($sunsign_matchastro[$astrodata['SUN_SIGN']])
					{
						$astrodata['SUN_SIGN'] = $sunsign_matchastro[$astrodata['SUN_SIGN']];
					}
					else
        				{
                				$statement6 = "insert into newjs.UNMATCHED_SUNSIGN_MATCHASTRO(NAME,PROFILEID) values ('".$astrodata['SUN_SIGN']."','".$row['PROFILEID']."')";
              					$mysqlObjM->executeQuery($statement6,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement6,"ShowErrTemplate");
        				}
				}
        			//CHECK FOR SUN-SIGN ENDS HERE
				} // if ($fp1) ends here
				} // if ($row_is_same[ACTIVATED]) ends here
		 
				//CODE BY ANAND ENDS

				//check if country exists in our database, else store it in UNAVAILABLE_ASTRO_COUNTRY table.
				$sql_ctry = "SELECT VALUE FROM newjs.COUNTRY WHERE LABEL='$astrodata[COUNTRY_BIRTH]'";
				$res_ctry = mysql_query($sql_ctry,$db) or logError($sql_ctry,"ShowErrTemplate");
				$row_ctry = mysql_fetch_array($res_ctry);
				if ($row_ctry['VALUE'])
					$astrodata['COUNTRY_BIRTH_EXIST'] = $row_ctry['VALUE'];
				else
				{
					$sql  = "INSERT INTO newjs.UNAVAILABLE_ASTRO_COUNTRY(PROFILEID,COUNTRY_BIRTH,ENTRY_DT) VALUES ('$profileid','$astrodata[COUNTRY_BIRTH]',NOW())";
					mysql_query($sql,$db) or logError($sql,"ShowErrTemplate");
				}


				//store the astro details.
				 /*$sql1 = "REPLACE INTO newjs.ASTRO_DETAILS(
						PROFILEID,
						DATE,
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
						NOW(),
						'".addslashes(stripslashes($astrodata['CITY_BIRTH']))."',
						'$astrodata[DTOFBIRTH]',
						'$astrodata[BTIME]',
						'".addslashes(stripslashes($astrodata['COUNTRY_BIRTH']))."',
						'".addslashes(stripslashes($astrodata['CITY_BIRTH']))."',
						'".addslashes(stripslashes($astrodata['LATITUDE']))."',
						'".addslashes(stripslashes($astrodata['LONGITUDE']))."',
						'".addslashes(stripslashes($astrodata['TIMEZONE']))."',
						'".addslashes(stripslashes($astrodata['DST']))."',
						'".addslashes(stripslashes($astrodata['LAGNA_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['SUN_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['MOON_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['MARS_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['MERCURY_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['JUPITER_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['VENUS_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['SATURN_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['RAHU_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['KETU_DEGREES_FULL']))."',
						'".addslashes(stripslashes($astrodata['MOON_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['MARS_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['MERCURY_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['JUPITER_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['VENUS_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['SATURN_RETRO_COMBUST']))."',
						'".addslashes(stripslashes($astrodata['VARA']))."',
						'".addslashes(stripslashes($astrodata['MASA']))."',
						'".$row_is_same["SHOW_HOROSCOPE"]."')";

				mysql_query($sql1,$db) or logError($sql1,"ShowErrTemplate");*/
				if(strlen($row_is_same["SHOW_HOROSCOPE"]) === 0 || false === isset($row_is_same["SHOW_HOROSCOPE"])) {
					$row_is_same["SHOW_HOROSCOPE"] = "";
				}
				$arrParams = array(
					"CITY_BIRTH" => addslashes(stripslashes($astrodata['CITY_BIRTH'])) ,
					"DTOFBIRTH" => $astrodata['DTOFBIRTH'],
					"BTIME" => $astrodata['BTIME'],
					"COUNTRY_BIRTH" => addslashes(stripslashes($astrodata['COUNTRY_BIRTH'])),
					"PLACE_BIRTH" => addslashes(stripslashes($astrodata['CITY_BIRTH'])),
					"LATITUDE" => addslashes(stripslashes($astrodata['LATITUDE'])),
					"LONGITUDE" => addslashes(stripslashes($astrodata['LONGITUDE'])),
					"TIMEZONE" => addslashes(stripslashes($astrodata['TIMEZONE'])),
					"DST" => addslashes(stripslashes($astrodata['DST'])),
					"LAGNA_DEGREES_FULL" => addslashes(stripslashes($astrodata['LAGNA_DEGREES_FULL'])),
					"SUN_DEGREES_FULL" => addslashes(stripslashes($astrodata['SUN_DEGREES_FULL'])),
					"MOON_DEGREES_FULL" => addslashes(stripslashes($astrodata['MOON_DEGREES_FULL'])),
					"MARS_DEGREES_FULL" => addslashes(stripslashes($astrodata['MARS_DEGREES_FULL'])),
					"MERCURY_DEGREES_FULL" => addslashes(stripslashes($astrodata['MERCURY_DEGREES_FULL'])),
					"JUPITER_DEGREES_FULL" => addslashes(stripslashes($astrodata['JUPITER_DEGREES_FULL'])),
					"VENUS_DEGREES_FULL" => addslashes(stripslashes($astrodata['VENUS_DEGREES_FULL'])),
					"SATURN_DEGREES_FULL" => addslashes(stripslashes($astrodata['SATURN_DEGREES_FULL'])),
					"RAHU_DEGREES_FULL" => addslashes(stripslashes($astrodata['RAHU_DEGREES_FULL'])),
					"KETU_DEGREES_FULL" => addslashes(stripslashes($astrodata['KETU_DEGREES_FULL'])),
					"MOON_RETRO_COMBUST" => addslashes(stripslashes($astrodata['MOON_RETRO_COMBUST'])),
					"MARS_RETRO_COMBUST" => addslashes(stripslashes($astrodata['MARS_RETRO_COMBUST'])),
					"MERCURY_RETRO_COMBUST" => addslashes(stripslashes($astrodata['MERCURY_RETRO_COMBUST'])),
					"JUPITER_RETRO_COMBUST" => addslashes(stripslashes($astrodata['JUPITER_RETRO_COMBUST'])),
					"VENUS_RETRO_COMBUST" => addslashes(stripslashes($astrodata['VENUS_RETRO_COMBUST'])),
					"SATURN_RETRO_COMBUST" => addslashes(stripslashes($astrodata['SATURN_RETRO_COMBUST'])),
					"VARA" => addslashes(stripslashes($astrodata['VARA'])),
					"MASA" => addslashes(stripslashes($astrodata['MASA'])),
					"SHOW_HOROSCOPE" => $row_is_same["SHOW_HOROSCOPE"],
				);
				$objReplace->replaceASTRO_DETAILS($profileid, $arrParams);

				$key = $profileid."_KUNDLI_LINK";
				memcache_call($key,"");

				//Code added by Vibhor as discussed with Lavesh
        			include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
                                global $mysqlObj;
                                global $noOfActiveServers;
                                $mysqlObj=new Mysql;
                                for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
                                {
                                        $myDbName=getActiveServerName($activeServerId);
                                        $myDb=$mysqlObj->connect("$myDbName");
                                        $sql_pr="UPDATE HOROSCOPE_REQUEST SET UPLOAD_SEEN='U' WHERE PROFILEID_REQUEST_BY='$profileid'";
                                        $mysqlObj->executeQuery($sql_pr,$myDb);
                                }
                                //end
				$type = $row['TYPE'];

				//inserted in ASTRO_DATA_COUNT to track, total horoscope generated.
				$sql2 = "INSERT INTO MIS.ASTRO_DATA_COUNT(PROFILEID,TYPE,ENTRY_DT) VALUES ('$profileid','$type',NOW())";
				mysql_query($sql2,$db) or die(mysql_error());//logError($sql2,"ShowErrTemplate");

				//ASTRO DETAILS TRACK by ANAND
				if ($fp1 && $row_is_same['ACTIVATED']!='D'){
				track_astro_details($profileid,$db);
				}
				//ASTRO DETAILS TRACK ENDS

				//update JPROFILE modified by ANAND
				update_astro_details($profileid,$astrodata,'',$db);
				//update JPROFILE modification ends

				mysql_query($sql,$db) or logError($sql,"ShowErrTemplate");

				//SEND MAIL TO USER by ANAND
				if ($fp1 && $row_is_same['ACTIVATED']!='D')
				{
					$tempDtArr = explode(" ",$entry_dt);
					$temp_dt = $tempDtArr[0];
					unset($tempDtArr);
					if($row_is_same["ENTRY_DT"]==$temp_dt)
						$type_mail = 1;
					else
						$type_mail = 0;
				
					$canSendObj= canSendFactory::initiateClass($channel=CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$ow_is_same['EMAIL'],"EMAIL_TYPE"=>"ASTRO"),$profileid);
					$canSend = $canSendObj->canSendIt();
					if($canSend){ 
          	mail_to_user($profileid,$row_is_same['EMAIL'],$row_is_same['USERNAME'],$astrodata,$type_mail,$db);
          }
				}

        			//SEND MAIL ENDS

				$sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET PENDING='N' WHERE PROFILEID='$profileid'";
				mysql_query($sql_update,$db) or logError($sql_update,"ShowErrTemplate");
			}
			else
			{
				 $sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET COUNTER=COUNTER+1 WHERE PROFILEID='$profileid' AND ENTRY_DT='$entry_dt'";
				mysql_query($sql_update,$db) or logError($sql_update,"ShowErrTemplate");
			}
			/*include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
                        $parameters = array("KEY"=>"AI_HORO","OTHER_PROFILEID"=>$profileid,"DATA"=>$profileid);
                        sendMultipleInstantSms($parameters);*/
			delete_remote_server_astro_details($profileid);
		}
		else
		{
			$sql_update = "UPDATE newjs.ASTRO_PULLING_REQUEST SET COUNTER=COUNTER+1 , PENDING='U' WHERE PROFILEID='$profileid'";
			mysql_query($sql_update,$db) or logError($sql_update,"ShowErrTemplate");
		}
        updateProfileCompletionScore($profileid);    
	}
	unset($data);
	unset($astrodata);
}

//release_lock($lock_handle);

// function to delete the data from matchstro server once the data has been fetched
function delete_remote_server_astro_details($profileid)
{
	$f = @fopen("https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Delete_AstroData?JS_UniqueID=$profileid","r");
	if($f)
		fclose($f);
}
function label_select($columnname,$value,$db)
{
        $sql = "select SQL_CACHE LABEL from $columnname WHERE VALUE='$value'";
        $res = mysql_query($sql,$db) or logError("error",$sql) ;
        $myrow= mysql_fetch_row($res);
        return $myrow;
}

/*
 * Functin to update Profile completion score
 * @access global
 * @param $iProfileId 
 * @return void
 * @throw void
 */
function updateProfileCompletionScore($iProfileId)
{
    try{
        $cScoreObject = ProfileCompletionFactory::getInstance("API",null,$iProfileId);
        $cScoreObject->updateProfileCompletionScore();
    }catch(Exception $e)
    {
        //var_dump($e);
    }
}


?>
