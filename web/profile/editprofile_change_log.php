<?php
include_once('connect.inc');
include_once('arrays.php');

function editprofile_change_log($formdata)
{
	global $RELATIONSHIP,$MANGLIK,$MSTATUS,$CHILDREN,$BODYTYPE,$COMPLEXION, $DIET,$SMOKE,$DRINK,$RSTATUS,$HANDICAPPED;

	$sql = "Select * from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$formdata[profileid]'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);

	$comments="";

	switch($formdata["EditWhat"])
	{
		case 'Demographics':
				if(trim($formdata["Email"]))
				if($myrow["EMAIL"]!=$formdata["Email"])
                $comments.="<br><b>"." EMAIL : "."</b><br>"."Changed From "."<b>".$myrow["EMAIL"]."</b><br>"." To "."<b>".$formdata["Email"]."</b>";
				if($myrow["RELATION"]!=$formdata["Relationship"])
				{
					$val = $myrow["RELATION"];
					$comments.="<br><b>"." Relationship : "."</b><br>"."Changed From "."<b>".$RELATIONSHIP[$val]."</b><br>"." To "."<b>".$RELATIONSHIP[$formdata["Relationship"]]."</b>";
				}
				if($myrow["HEIGHT"]!=$formdata["Height"])
				{
					$val = $myrow["HEIGHT"];
					$orig_val = label_select("HEIGHT",$val);
					$new_val = label_select("HEIGHT",$formdata["Height"]);
			 
					$comments.="<br><b>"." Height : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["MSTATUS"]!=$formdata["Marital_Status"])
				{
					$val = $myrow["MSTATUS"];
					$comments.="<br><b>"." Marital Status : "."</b><br>"."Changed From "."<b>".$MSTATUS[$val]."</b><br>"." To "."<b>".$MSTATUS[$formdata["Marital_Status"]]."</b>";
				}
				if($myrow["HAVECHILD"]!=$formdata["Has_Children"])
				{
					$val = $myrow["HAVECHILD"];
                			$comments.="<br><b>"." Has Children : "."</b><br>"."Changed From "."<b>".$CHILDREN[$val]."</b><br>"." To "."<b>".$CHILDREN[$formdata["Has_Children"]]."</b>";
        			}
				if($myrow["CASTE"]!=$formdata["Caste"])
				{
					$val =$myrow["CASTE"];
					$orig_val = label_select("CASTE",$val);
					$new_val = label_select("CASTE",$formdata["Caste"]);
					$comments.="<br><b>"." Caste : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["COUNTRY_RES"]!=$formdata["Country_Residence"])
				{
					$ctry_change = 1;
					$val =$myrow["COUNTRY_RES"];
					$orig_val = label_select("COUNTRY",$val);
					$new_val =  label_select("COUNTRY",$formdata["Country_Residence"]);
					$comments.="<br><b>"." Country of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["CITY_RES"]!=$formdata["City_Res"])
				{
					$val =$myrow["CITY_RES"];
					if ($ctry_change == 1)
					{
						if($formdata["Country_Residence"] == '51')
						{
							$orig_val = label_select("CITY_INDIA",$val);
							$new_val =  label_select("CITY_INDIA",$formdata["City_Res"]);
							$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
						}
						elseif($formdata["Country_Residence"] == '128')
						{
							$orig_val = label_select("CITY_USA",$val);
							$new_val =  label_select("CITY_USA",$formdata["City_Res"]);
							$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
						}
					}
				}
				if($myrow["OCCUPATION"]!=$formdata["Occupation"])
				{
					$val =$myrow["OCCUPATION"];
					$orig_val = label_select("OCCUPATION",$val);
					$new_val =  label_select("OCCUPATION",$formdata["Occupation"]);
					$comments.="<br><b>"." Occupation: "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["INCOME"]!=$formdata["Income"])
				{
					$val =$myrow["INCOME"];
					$orig_val = label_select("INCOME",$val);
					$new_val =  label_select("INCOME",$formdata["Income"]);
					$comments.="<br><b>"." Income : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}

				break;
		case 'ProfileSumm':
				if(trim($formdata["Information"])!=$myrow["YOURINFO"])
                			$comments.="<br><b>"." Your Info : "."</b><br>"."Changed From "."<b>".$myrow["YOURINFO"]."</b><br>"." To "."<b>".$formdata["Information"]."</b>";
				break;
				
		case 'EduOcc':
				if(trim($formdata["Job_Info"])!=$myrow["JOB_INFO"])
                			$comments.="<br><b>"." Job related information : "."</b><br>"."Changed From "."<b>".$myrow["JOB_INFO"]."</b><br>"." To "."<b>".$formdata["Job_Info"]."</b>";
				if($formdata["Educ_Qualification"]!=$myrow["EDUCATION"])
                			$comments.="<br><b>"." Education Qualification : "."</b><br>"."Changed From "."<b>".$myrow["EDUCATION"]."</b><br>"." To "."<b>".$formdata["Educ_Qualification"]."</b>";

				if($myrow["EDU_LEVEL"]!=$formdata["Education_Level_Old"])
				{
			 
					$val =$myrow["EDU_LEVEL"];
					$orig_val = label_select("EDUCATION_LEVEL",$val);
					$new_val =  label_select("EDUCATION_LEVEL",$formdata["Education_Level_Old"]);
					$comments.="<br><b>"." Highest Degree : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["EDU_LEVEL_NEW"]!=$formdata["Education_Level"])
				{
					$val =$myrow["EDU_LEVEL_NEW"];
					$orig_val = label_select("EDUCATION_LEVEL_NEW",$val);
					$new_val =  label_select("EDUCATION_LEVEL_NEW",$formdata["Education_Level"]);
					$comments.="<br><b>"." Education Level: "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["OCCUPATION"]!=$formdata["Occupation"])
				{
					$val =$myrow["OCCUPATION"];
					$orig_val = label_select("OCCUPATION",$val);
					$new_val =  label_select("OCCUPATION",$formdata["Occupation"]);
					$comments.="<br><b>"." Occupation : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["INCOME"]!=$formdata["Income"])
				{
					$val =$myrow["INCOME"];
					$orig_val = label_select("INCOME",$val);
					$new_val =  label_select("INCOME",$formdata["Income"]);
					$comments.="<br><b>"." Income : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["MARRIED_WORKING"]!=$formdata["Married_Working"])
                			$comments.="<br><b>"." Preference to work after marriage"."</b><br>"."Changed From "."<b>".$myrow["MARRIED_WORKING"]."</b><br>"." To "."<b>".$formdata["Married_Working"]."</b>";
				break;
		case 'RelEthnic':
				if($myrow["SUBCASTE"]!=$formdata["Subcaste"])
                			$comments.="<br><b>"." SUBCASTE : "."</b><br>"."Changed From "."<b>".$myrow["SUBCASTE"]."</b><br>"." To "."<b>".$formdata["Subcaste"]."</b>";

				$Religion_temp = explode('|X|',$formdata["Religion"]);
				$Religion = $Religion_temp[0];

				if($myrow["RELIGION"]!=$Religion)
				{
					$val =$myrow["RELIGION"];
					$orig_val = label_select("RELIGION",$val);
					$new_val = label_select("RELIGION",$Religion);
					$comments.="<br><b>"." Religion : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["MTONGUE"]!=$formdata["Mtongue"])
				{
					$val =$myrow["MTONGUE"];
					$orig_val = label_select("MTONGUE",$val);
					$new_val = label_select("MTONGUE",$formdata["Mtongue"]);
					$comments.="<br><b>"." Community : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["CASTE"]!=$formdata["Caste"])
				{
					$val =$myrow["CASTE"];
					$orig_val = label_select("CASTE",$val);
					$new_val = label_select("CASTE",$formdata["Caste"]);
					$comments.="<br><b>"." Caste : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if(trim($formdata["Nakshatram"])!=$myrow["NAKSHATRA"])
                			$comments.="<br><b>"." Nakshatra : "."</b><br>"."Changed From "."<b>".$myrow["NAKSHATRA"]."</b><br>"." To "."<b>".$formdata["Nakshatram"]."</b>";
				if(trim($formdata["Gothra"])!=$myrow["GOTHRA"])
					$comments.="<br><b>"." Gothra : "."</b><br>"."Changed From "."<b>".$myrow["GOTHRA"]."</b><br>"." To "."<b>".$formdata["Gothra"]."</b>";
				break;
	case 'FamilyDetails':
				if($myrow["FAMILY_BACK"]!=$formdata["Family_Back"])
				{
					$val =$myrow["FAMILY_BACK"];
					$orig_val = label_select("FAMILY_BACK",$val);
					$new_val =  label_select("FAMILY_BACK",$formdata["Family_Back"]);
					$comments.="<br><b>"." Family Background : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($myrow["FAMILY_VALUES"]!=$formdata["Family_Values"])
				{
					$val =$myrow["FAMILY_VALUES"];
					if($val == 1)
						$orig_val= "Traditional";
					elseif($val == 2)
						$orig_val= "Moderate";
					elseif($val == 3)
						$orig_val= "Liberal";
			 
					if($formdata["Family_Values"] == 1)
						$new_val= "Traditional";
					elseif($formdata["Family_Values"] == 2)
						$new_val= "Moderate";
					elseif($formdata["Family_Values"] == 3)
						$new_val= "Liberal";
			 
					$comments.="<br><b>"." Family Values : "."</b><br>"."Changed From "."<b>".$orig_val."</b><br>"." To "."<b>".$new_val."</b>";
				}
				if($formdata["Father_Info"]!=$myrow["FATHER_INFO"])
                			$comments.="<br><b>"." Father related information : "."</b><br>"."Changed From "."<b>".$myrow["FATHER_INFO"]."</b><br>"." To "."<b>".$formdata["Father_Info"]."</b>";
 
        			if($formdata["Sibling_Info"]!=$myrow["SIBLING_INFO"])
                			$comments.="<br><b>"." Sibling related information : "."</b><br>"."Changed From "."<b>".$myrow["SIBLING_INFO"]."</b><br>"." To "."<b>".$formdata["Sibling_Info"]."</b>";

				if($myrow["PARENT_CITY_SAME"]!=$formdata["Parent_City_Same"])
                			$comments.="<br><b>"." Parent live in the same city or not : "."</b><br>"."Changed From "."<b>".$myrow["PARENT_CITY_SAME"]."</b><br>"." To "."<b>".$formdata["Parent_City_Same"]."</b>";
 
        			if($formdata["Family"]!=$myrow["FAMILYINFO"])
                			$comments.="<br><b>"." Extended Family related information : "."</b><br>"."Changed From "."<b>".$myrow["FAMILYINFO"]."</b><br>"." To "."<b>".$formdata["Family"]."</b>";
				break;

		case 'AstroData':
				if($myrow["COUNTRY_BIRTH"]!=$formdata["Country_Birth"])
				{
					$val =$myrow["COUNTRY_BIRTH"];
					$orig_val = label_select("COUNTRY",$val);
					$new_val =  label_select("COUNTRY",$formdata["Country_Birth"]);
					$comments.="<br><b>"." Country of Birth : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				if($formdata["City_Birth"]!=$myrow["CITY_BIRTH"])
					$comments.="<br><b>"." City of Birth : "."</b><br>"."Changed From "."<b>".$myrow["CITY_BIRTH"]."</b><br>"." To "."<b>".$formdata["City_Birth"]."</b>";
			 
				//$btime=$Hour_Birth.":".$Min_Birth;
        			//if($myrow["BTIME"]!=$btime)
                		//	$comments.="<br><b>"." Time of Birth : "."</b><br>"."Changed From "."<b>".$myrow["BTIME"]."</b><br>"." To "."<b>".$btime."</b>";

				if($myrow["MANGLIK"]!=$formdata["Manglik_Status"])
        			{
                			$val = $myrow["MANGLIK"];
                			$comments.="<br><b>"." Manglik Status : "."</b><br>"."Changed From "."<b>".$MANGLIK[$val]."</b><br>"." To "."<b>".$MANGLIK[$formdata["Manglik_Status"]]."</b>";
        			}
				if (!$formdata["display_horo"])
					$formdata["display_horo"] = 'N';
				if($myrow["SHOW_HOROSCOPE"]!=$formdata["display_horo"])
                			$comments.="<br><b>"." Horoscope display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOW_HOROSCOPE"]."</b><br>"." To "."<b>".$formdata["display_horo"]."</b>";
				if(trim($formdata["Nakshatram"])!=$myrow["NAKSHATRA"])
                                        $comments.="<br><b>"." Nakshatra : "."</b><br>"."Changed From "."<b>".$myrow["NAKSHATRA"]."</b><br>"." To "."<b>".$formdata["Nakshatram"]."</b>";
				break;

		case 'LifeStyle':
				if($myrow["DIET"]!=$formdata["Diet"])
				{
					$val =$myrow["DIET"];
					$comments.="<br><b>"." Diet : "."</b><br>"."Changed From "."<b>".$DIET[$val]."</b><br>"." To "."<b>".$DIET[$formdata["Diet"]]."</b>";
				}
				if($myrow["SMOKE"]!=$formdata["Smoke"])
				{
					$val =$myrow["SMOKE"];
					$comments.="<br><b>"." Smoke : "."</b><br>"."Changed From "."<b>".$SMOKE[$val]."</b><br>"." To "."<b>".$SMOKE[$formdata["Smoke"]]."</b>";
				}
				if($myrow["DRINK"]!=$formdata["Drink"])
				{
					$val =$myrow["DRINK"];
					$comments.="<br><b>"." Drink : "."</b><br>"."Changed From "."<b>".$DRINK[$val]."</b><br>"." To "."<b>".$DRINK[$formdata["Drink"]]."</b>";
				}
				if($myrow["COMPLEXION"]!=$formdata["Complexion"])
				{
					$val =$myrow["COMPLEXION"];
					$comments.="<br><b>"." Complexion : "."</b><br>"."Changed From "."<b>".$COMPLEXION[$val]."</b><br>"." To "."<b>".$COMPLEXION[$formdata["Complexion"]]."</b>";
				}
				if($myrow["BTYPE"]!=$formdata["Body_Type"])
				{
					$val =$myrow["BTYPE"];
					$comments.="<br><b>"." Body Type : "."</b><br>"."Changed From "."<b>".$BODYTYPE[$val]."</b><br>"." To "."<b>".$BODYTYPE[$formdata["Body_Type"]]."</b>";
				}
				if($myrow["HANDICAPPED"]!=$formdata["Phyhcp"])
				{
					$val =$myrow["HANDICAPPED"];
					$comments.="<br><b>"." Handicapped : "."</b><br>"."Changed From "."<b>".$HANDICAPPED[$val]."</b><br>"." To "."<b>".$HANDICAPPED[$formdata["Phyhcp"]]."</b>";
				}
				break;
	case 'ContactDetails':
				if(trim($formdata["Email"]))
                                	if($myrow["EMAIL"]!=$formdata["Email"])
                		$comments.="<br><b>"." EMAIL : "."</b><br>"."Changed From "."<b>".$myrow["EMAIL"]."</b><br>"." To "."<b>".$formdata["Email"]."</b>";
				if($myrow["COUNTRY_RES"]!=$formdata["Country_Residence"])
				{
					$ctry_change = 1;
					$val =$myrow["COUNTRY_RES"];
					$orig_val = label_select("COUNTRY",$val);
					$new_val =  label_select("COUNTRY",$formdata["Country_Residence"]);
					$comments.="<br><b>"." Country of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}

				if ($ctry_change == 1)
				{
					$val =$myrow["CITY_RES"];
					if(is_numeric($val))
						$orig_val = label_select("CITY_USA",$val);
                                        else
                                        	$orig_val = label_select("CITY_INDIA",$val);

					if($formdata["Country_Residence"] == '51')
					{
						if($myrow["CITY_RES"]!=$formdata["City_India"])
						{
							if($formdata["Country_Residence"] == '51')
							{
								$new_val =  label_select("CITY_INDIA",$formdata["City_India"]);
								$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
							}
						}
					}
					elseif($formdata["Country_Residence"] == '128')
					{
						if($myrow["CITY_RES"]!=$formdata["City_USA"])
                                                {
							$new_val =  label_select("CITY_USA",$formdata["City_USA"]);
							$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
						}
					}
					//$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
				}
				elseif ($formdata["City_India"] || $formdata["City_USA"])
				{
					$val =$myrow["CITY_RES"];
					if(is_numeric($val))
						$orig_val = label_select("CITY_USA",$val);
                                        else
                                        	$orig_val = label_select("CITY_INDIA",$val);

					if($formdata["Country_Residence"] == '51')
                                        {
                                                if($myrow["CITY_RES"]!=$formdata["City_India"])
                                                {
                                                        if($formdata["Country_Residence"] == '51')
                                                        {
                                                                $new_val =  label_select("CITY_INDIA",$formdata["City_India"]);
                                                                $comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
                                                        }
                                                }
                                        }
                                        elseif($formdata["Country_Residence"] == '128')
                                        {
                                                if($myrow["CITY_RES"]!=$formdata["City_USA"])
                                                {
                                                        $new_val =  label_select("CITY_USA",$formdata["City_USA"]);
                                                        $comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
                                                }
                                        }
				}
				if($myrow["RES_STATUS"]!=$formdata["Rstatus"])
				{
					$val = $myrow["RES_STATUS"];
					$comments.="<br><b>"." Residence Status : "."</b><br>"."Changed From "."<b>".$RSTATUS[$val]."</b><br>"." To "."<b>".$RSTATUS[$formdata["Rstatus"]]."</b>";
				}
				if(trim($formdata["Parents_Contact"])!=$myrow["PARENTS_CONTACT"])
					$comments.="<br><b>"." Contact address of parents : "."</b><br>"."Changed From "."<b>".$myrow["PARENTS_CONTACT"]."</b><br>"." To "."<b>".$formdata["Parents_Contact"]."</b>";
			
				if (!$formdata["Show_Parents_Contact"]) 
					$formdata["Show_Parents_Contact"] = 'Y';
				if($myrow["SHOW_PARENTS_CONTACT"]!=$formdata["Show_Parents_Contact"])
					$comments.="<br><b>"." Parents Contact Address Display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOW_PARENTS_CONTACT"]."</b><br>"." To "."<b>".$formdata["Show_Parents_Contact"]."</b>";
			 
				if(trim($formdata["Address"])!=$myrow["CONTACT"])
					$comments.="<br><b>"." Contact address : "."</b><br>"."Changed From "."<b>".$myrow["CONTACT"]."</b><br>"." To "."<b>".$formdata["Address"]."</b>";
			 
				if(trim($formdata["pincode"])!=$myrow["PINCODE"])// || !is_numeric($formdata["pincode"]))
					$comments.="<br><b>"." Pincode : "."</b><br>"."Changed From "."<b>".$myrow["PINCODE"]."</b><br>"." To "."<b>".$formdata["pincode"]."</b>";
			
				if(!$formdata["showAddress"])
					$formdata["showAddress"] = 'Y';
				if($myrow["SHOWADDRESS"]!=$formdata["showAddress"])
					$comments.="<br><b>"." Contact Address Display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOWADDRESS"]."</b><br>"." To "."<b>".$formdata["showAddress"]."</b>";
			 
				if($formdata["Country_Code"]!=$myrow["ISD"])
				{
					$comments.="<br><b>"." ISD Code : "."</b><br>"."Changed From "."<b>".$myrow["ISD"]."</b><br>"." To "."<b>".$formdata["Country_Code"]."</b>";
				}
			 
				if($formdata["State_Code"]!=$myrow["STD"])
					$comments.="<br><b>"." STD Code : "."</b><br>"."Changed From "."<b>".$myrow["STD"]."</b><br>"." To "."<b>".$formdata["State_Code"]."</b>";
				if(trim($formdata["Phone"])!=$myrow["PHONE_RES"])
					$comments.="<br><b>"." Phone (R) : "."</b><br>"."Changed From "."<b>".$myrow["PHONE_RES"]."</b><br>"." To "."<b>".$formdata["Phone"]."</b>";
			 
				if(trim($formdata["Mobile"])!=$myrow["PHONE_MOB"])
					$comments.="<br><b>"." Phone (M) : "."</b><br>"."Changed From "."<b>".$myrow["PHONE_MOB"]."</b><br>"." To "."<b>".$formdata["Mobile"]."</b>";
			
				if(!$formdata["Showphone"]) 
					$formdata["Showphone"] = 'Y';
				if($myrow["SHOWPHONE_RES"]!=$formdata["Showphone"])
					$comments.="<br><b>"." Phone No Display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOWPHONE_RES"]."</b><br>"." To "."<b>".$formdata["Showphone"]."</b>";
			 
				if(!$formdata["Showmobile"])
					$formdata["Showmobile"] = 'Y';
				if($myrow["SHOWPHONE_MOB"]!=$formdata["Showmobile"])
					$comments.="<br><b>"." Mobile No Display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOWPHONE_MOB"]."</b><br>"." To "."<b>".$formdata["Showmobile"]."</b>";
			 
				if(trim($formdata["Messenger_ID"])!=$myrow["MESSENGER_ID"])
					$comments.="<br><b>"." Messenger ID : "."</b><br>"."Changed From "."<b>".$myrow["MESSENGER_ID"]."</b><br>"." To "."<b>".$formdata["Messenger_ID"]."</b>";
			 
				if($myrow["MESSENGER_CHANNEL"]!=$formdata["Messenger"])
				{
					if($myrow["MESSENGER_CHANNEL"]==1)
						$orig_val="Yahoo";
					if($myrow["MESSENGER_CHANNEL"]==2)
						$orig_val="MSN";
					if($myrow["MESSENGER_CHANNEL"]==3)
						$orig_val="Skype";
					if($myrow["MESSENGER_CHANNEL"]==5)
						$orig_val="ICQ";
					 if($myrow["MESSENGER_CHANNEL"]==7)
                                                $orig_val="Rediff Bol";
					if($myrow["MESSENGER_CHANNEL"]==4)
						$orig_val="Others";
			 
			 
					if($formdata["Messenger"]==1)
						$new_val="Yahoo";
					if($formdata["Messenger"]==2)
						$new_val="MSN";
					if($formdata["Messenger"]==3)
						$new_val="Skype";
					if($formdata["Messenger"]==5)
						$new_val="ICQ";
					if($formdata["Messenger"]==7)
                                                $new_val="Rediff Bol";
					if($formdata["Messenger"]==4)
						$new_val="Others";
					$comments.="<br><b>"." Messenger Channel Display Settings: "."</b><br>"."Changed From "."<b>".$orig_val."</b><br>"." To "."<b>".$new_val."</b>";
				}
				if (!$formdata["showMessenger"])
					$formdata["showMessenger"] = 'Y';
				if($myrow["SHOWMESSENGER"]!=$formdata["showMessenger"])
				{
					$comments.="<br><b>"." Messenger Id display settings : "."</b><br>"."Changed From "."<b>".$myrow["SHOWMESSENGER"]."</b><br>"." To "."<b>".$formdata["showMessenger"]."</b>";
				}

				if (!$formdata["GET_SMS"])
					$formdata["GET_SMS"] = 'N';
				if($myrow["GET_SMS"]!=$formdata["GET_SMS"])
				{
					$comments.="<br><b>"." Option to avail jeevansathi SMS service : "."</b><br>"."Changed From "."<b>".$myrow["GET_SMS"]."</b><br>"." To "."<b>".$formdata["GET_SMS"]."</b>";
				}
				break;
		case 'Filters':
				if($myrow["PRIVACY"]!=$formdata["radioprivacy"])
				{
					if ($myrow["PRIVACY"] == 'A')
						$val = "Allow my detailed profile to be viewed by all visitors";
					elseif ($myrow["PRIVACY"] == 'R')
						$val = "Allow my detailed profile to be viewed only by registered users.";
					elseif ($myrow["PRIVACY"] == 'F')
						$val = "Allow my detailed profile to be viewed only by those registered users who pass my filters";
					elseif ($myrow["PRIVACY"] == 'C')
						$val = "Don't show my detailed profile or summary profile to any user";
			 
					if ($formdata["radioprivacy"] == 'A')
						$val1 = "Allow my detailed profile to be viewed by all visitors";
					elseif ($formdata["radioprivacy"] == 'R')
						$val1 = "Allow my detailed profile to be viewed only by registered users.";
					elseif ($formdata["radioprivacy"] == 'F')
						$val1 = "Allow my detailed profile to be viewed only by those registered users who pass my filters";                 elseif ($formdata["radioprivacy"] == 'C')
						$val1 = "Don't show my detailed profile or summary profile to any user";
					$comments.="<br><b>"." Privacy Settings : "."</b><br>"."Changed From "."<b>".$val."</b><br>"." To "."<b>".$val1."</b>";
				}
				break;
	}

	$crmuser = getname($formdata["cid"]);
	$company = $formdata['company'];
	$profileid = $formdata['profileid'];

        if ($comments!="")
        {
                $sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$profileid','".addslashes(stripslashes($comments))."','E','$company')";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
        }

}
?>
