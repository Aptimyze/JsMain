<?php
include_once('connect.inc');
include_once('arrays.php');
function editprofile1_change_log($profileid,$Email,$Relationship,$Height,$Marital_Status,$Has_Children,$Manglik_Status,$Religion,$Mtongue,$Caste,$Subcaste,$Country_Residence,$City_Res,$Rstatus,$edu_level_old,$Education_Level,$Educ_Qualification,$Occupation,$Income,$Diet,$Smoke,$Drink,$Complexion,$Body_Type,$Phyhcp,$radioprivacy,$cid,$company)
{
	global $RELATIONSHIP,$MANGLIK,$MSTATUS,$CHILDREN,$BODYTYPE,$COMPLEXION, $DIET,$SMOKE,$DRINK,$RSTATUS,$HANDICAPPED;
	$sql = "Select USERNAME,GENDER,DTOFBIRTH,AGE,EMAIL,RELATION,HEIGHT,MSTATUS,HAVECHILD,MANGLIK,RELIGION,MTONGUE,CASTE,SUBCASTE,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,EDU_LEVEL_NEW,EDUCATION,OCCUPATION,INCOME,DIET,SMOKE,DRINK,COMPLEXION,BTYPE,HANDICAPPED,PRIVACY,SCREENING,KEYWORDS from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$profileid'";
                                                                                                        
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	$comments="";

	if($myrow["EMAIL"]!=$Email)
		$comments.="<br><b>"." EMAIL : "."</b><br>"."Changed From "."<b>".$myrow["EMAIL"]."</b><br>"." To "."<b>".$Email."</b>";
	if($myrow["SUBCASTE"]!=$Subcaste)
		$comments.="<br><b>"." SUBCASTE : "."</b><br>"."Changed From "."<b>".$myrow["SUBCASTE"]."</b><br>"." To "."<b>".$Subcaste."</b>";
	if($myrow["GOTHRA"]!=$Gothra)
		$comments.="<br><b>"." GOTHRA : "."</b><br>"."Changed From "."<b>".$myrow["GOTHRA"]."</b><br>"." To "."<b>".$Gothra."</b>";
	if($Educ_Qualification!=$myrow["EDUCATION"])
		$comments.="<br><b>"." Education Qualification : "."</b><br>"."Changed From "."<b>".$myrow["EDUCATION"]."</b><br>"." To "."<b>".$Educ_Qualification."</b>";
	
	if($myrow["RELATION"]!=$Relationship)
	{
		$val = $myrow["RELATION"];
		$comments.="<br><b>"." Relationship : "."</b><br>"."Changed From "."<b>".$RELATIONSHIP[$val]."</b><br>"." To "."<b>".$RELATIONSHIP[$Relationship]."</b>";
	}
	if($myrow["HEIGHT"]!=$Height)
	{
		$val = $myrow["HEIGHT"];
		$orig_val = label_select("HEIGHT",$val);
		$new_val = label_select("HEIGHT",$Height);

                $comments.="<br><b>"." Height : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
	}
	if($myrow["MSTATUS"]!=$Marital_Status)
	{
		$val = $myrow["MSTATUS"];
		$comments.="<br><b>"." Marital Status : "."</b><br>"."Changed From "."<b>".$MSTATUS[$val]."</b><br>"." To "."<b>".$MSTATUS[$Marital_Status]."</b>";
	}
	if($myrow["HAVECHILD"]!=$Has_Children)
	{
		$val = $myrow["HAVECHILD"];
                $comments.="<br><b>"." Has Children : "."</b><br>"."Changed From "."<b>".$CHILDREN[$val]."</b><br>"." To "."<b>".$CHILDREN[$Has_Children]."</b>";
	}
	if($myrow["MANGLIK"]!=$Manglik_Status)
	{
		$val = $myrow["MANGLIK"];
                $comments.="<br><b>"." Manglik Status : "."</b><br>"."Changed From "."<b>".$MANGLIK[$val]."</b><br>"." To "."<b>".$MANGLIK[$Manglik_Status]."</b>";
	}
	if($myrow["RELIGION"]!=$Religion)
	{
		$val =$myrow["RELIGION"];
		$orig_val = label_select("RELIGION",$val);
                $new_val = label_select("RELIGION",$Religion);
		$comments.="<br><b>"." Religion : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
	}
	if($myrow["MTONGUE"]!=$Mtongue)
	{
                $val =$myrow["MTONGUE"];
                $orig_val = label_select("MTONGUE",$val);
                $new_val = label_select("MTONGUE",$Mtongue);
                $comments.="<br><b>"." Community : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
        }
	if($myrow["CASTE"]!=$Caste)
	{
		$val =$myrow["CASTE"];     
		$orig_val = label_select("CASTE",$val);
                $new_val = label_select("CASTE",$Caste);
                $comments.="<br><b>"." Caste : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
	}
	if($myrow["COUNTRY_RES"]!=$Country_Residence)
	{
		$ctry_change = 1;
		$val =$myrow["COUNTRY_RES"];
		$orig_val = label_select("COUNTRY",$val);
		$new_val =  label_select("COUNTRY",$Country_Residence);
		$comments.="<br><b>"." Country of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
	}
	if($myrow["CITY_RES"]!=$City_Res)
        {
                $val =$myrow["CITY_RES"];
		if ($ctry_change == 1)
		{
			if($Country_Residence == '51')
			{
				$orig_val = label_select("CITY_INDIA",$val);
		                $new_val =  label_select("CITY_INDIA",$City_Res);
				$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
			}
			elseif($Country_Residence == '127')
			{
				$orig_val = label_select("CITY_USA",$val);
                                $new_val =  label_select("CITY_USA",$City_Res);
				$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
			}
		}
                //$comments.="<br><b>"." City of Residence : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
        }
	if($myrow["RES_STATUS"]!=$Rstatus)
	{
		$val = $myrow["RES_STATUS"];
		$comments.="<br><b>"." Residence Status : "."</b><br>"."Changed From "."<b>".$RSTATUS[$val]."</b><br>"." To "."<b>".$RSTATUS[$Rstatus]."</b>";
	}
	if($myrow["EDU_LEVEL"]!=$edu_level_old)
        {

		$val =$myrow["EDU_LEVEL"];
                $orig_val = label_select("EDUCATION_LEVEL",$val);
		$new_val =  label_select("EDUCATION_LEVEL",$edu_level_old);
                $comments.="<br><b>"." Highest Degree : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";		
	}
	if($myrow["EDU_LEVEL_NEW"]!=$Education_Level)
        {
                                                                                                                            
                $val =$myrow["EDU_LEVEL_NEW"];
                $orig_val = label_select("EDUCATION_LEVEL_NEW",$val);
                $new_val =  label_select("EDUCATION_LEVEL_NEW",$Education_Level);
                $comments.="<br><b>"." Education Level: "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
        }
	if($myrow["OCCUPATION"]!=$Occupation)
        {
                                                                                                                            
                $val =$myrow["OCCUPATION"];
                $orig_val = label_select("OCCUPATION",$val);
                $new_val =  label_select("OCCUPATION",$Occupation);
                $comments.="<br><b>"." Occupation: "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
        }
	if($myrow["INCOME"]!=$Income)
        {
                $val =$myrow["INCOME"];
                $orig_val = label_select("INCOME",$val);
                $new_val =  label_select("INCOME",$Income);
                $comments.="<br><b>"." Income : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
        }
	if($myrow["DIET"]!=$Diet)
	{
		$val =$myrow["DIET"];
		$comments.="<br><b>"." Diet : "."</b><br>"."Changed From "."<b>".$DIET[$val]."</b><br>"." To "."<b>".$DIET[$Diet]."</b>";
	}
	if($myrow["SMOKE"]!=$Smoke)
        {
                $val =$myrow["SMOKE"];
		$comments.="<br><b>"." Smoke : "."</b><br>"."Changed From "."<b>".$SMOKE[$val]."</b><br>"." To "."<b>".$SMOKE[$Smoke]."</b>";
        }
	if($myrow["DRINK"]!=$Drink)
        {
                $val =$myrow["DRINK"];
		$comments.="<br><b>"." Drink : "."</b><br>"."Changed From "."<b>".$DRINK[$val]."</b><br>"." To "."<b>".$DRINK[$Smoke]."</b>";
        }
	if($myrow["COMPLEXION"]!=$Complexion)
	{
		$val =$myrow["COMPLEXION"];
		$comments.="<br><b>"." Complexion : "."</b><br>"."Changed From "."<b>".$COMPLEXION[$val]."</b><br>"." To "."<b>".$COMPLEXION[$Complexion]."</b>";
	}
	if($myrow["BTYPE"]!=$Body_Type)
        {
                $val =$myrow["BTYPE"];
                $comments.="<br><b>"." Body Type : "."</b><br>"."Changed From "."<b>".$BODYTYPE[$val]."</b><br>"." To "."<b>".$BODYTYPE[$Body_Type]."</b>";
        }
	if($myrow["HANDICAPPED"]!=$Phyhcp)
        {
                $val =$myrow["HANDICAPPED"];
                $comments.="<br><b>"." Handicapped : "."</b><br>"."Changed From "."<b>".$HANDICAPPED[$val]."</b><br>"." To "."<b>".$HANDICAPPED[$Phyhcp]."</b>";
        }
	if($myrow["PRIVACY"]!=$radioprivacy)
        {
		if ($myrow["PRIVACY"] == 'A')
                	$val = "Allow my detailed profile to be viewed by all visitors";
		elseif ($myrow["PRIVACY"] == 'R')
			$val = "Allow my detailed profile to be viewed only by registered users.";
		elseif ($myrow["PRIVACY"] == 'F')
			$val = "Allow my detailed profile to be viewed only by those registered users who pass my filters";
		elseif ($myrow["PRIVACY"] == 'C')
			$val = "Don't show my detailed profile or summary profile to any user";

		if ($radioprivacy == 'A')
                        $val1 = "Allow my detailed profile to be viewed by all visitors";
                elseif ($radioprivacy == 'R')                         
			$val1 = "Allow my detailed profile to be viewed only by registered users.";                 
		elseif ($radioprivacy == 'F')
                        $val1 = "Allow my detailed profile to be viewed only by those registered users who pass my filters";                 elseif ($radioprivacy == 'C')
                        $val1 = "Don't show my detailed profile or summary profile to any user";
                $comments.="<br><b>"." Privacy Settings : "."</b><br>"."Changed From "."<b>".$val."</b><br>"." To "."<b>".$val1."</b>";
        }
	//echo $comments;
	$crmuser = getname($cid);
        if ($comments!="")
        {
                //echo $comments;
                $sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$profileid','".addslashes(stripslashes($comments))."','E','$company')";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
        }
}
function editprofile2_change_log($profileid,$Country_Birth,$City_Birth,$btime,$Nakshatram,$Job_Info,$Married_Working,$Wife_Working,$Information,$Spouse,$Family_Values,$Family_Back,$Gothra,$Father_Info,$Sibling_Info,$Parent_City_Same,$Family,$Parents_Contact,$showAddress,$Show_Parents_Contact,$Address,$pincode,$Phone,$Mobile,$Showphone,$Showmobile,$Messenger_ID,$Messenger,$showMessenger,$display_horo,$GET_SMS,$State_Code,$ISD,$cid,$company)
{
	//$sql="select COUNTRY_BIRTH , CITY_BIRTH,NAKSHATRA,BTIME,JOB_INFO,YOURINFO,SPOUSE,GOTHRA,FATHER_INFO,SIBLING_INFO,FAMILYINFO,PARENTS_CONTACT,CONTACT,SCREENING,PHONE_RES,PHONE_MOB,MESSENGER_ID from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'"
	$sql="select COUNTRY_BIRTH ,CITY_BIRTH , BTIME , NAKSHATRA,JOB_INFO,MARRIED_WORKING, WIFE_WORKING , YOURINFO,SPOUSE,FAMILY_VALUES,FAMILY_BACK,GOTHRA,FATHER_INFO,SIBLING_INFO ,PARENT_CITY_SAME,FAMILYINFO , PARENTS_CONTACT,SHOWADDRESS , SHOW_PARENTS_CONTACT ,CONTACT,PINCODE,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,SHOW_HOROSCOPE,GET_SMS,STD,ISD FRom JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$editrow=mysql_fetch_array($result);
	$comments="";    

	if($editrow["COUNTRY_BIRTH"]!=$Country_Birth)
	{
                $val =$editrow["COUNTRY_BIRTH"];
                $orig_val = label_select("COUNTRY",$val);
                $new_val =  label_select("COUNTRY",$Country_Birth);
                $comments.="<br><b>"." Country of Birth : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";
	}                                                               
	if($City_Birth!=$editrow["CITY_BIRTH"])
		$comments.="<br><b>"." City of Birth : "."</b><br>"."Changed From "."<b>".$editrow["CITY_BIRTH"]."</b><br>"." To "."<b>".$City_Birth."</b>";

	//$btime=$Hour_Birth.":".$Min_Birth;
	if($editrow["BTIME"]!=$btime)
		$comments.="<br><b>"." Time of Birth : "."</b><br>"."Changed From "."<b>".$editrow["BTIME"]."</b><br>"." To "."<b>".$btime."</b>";

	if(trim($Nakshatram)!=$editrow["NAKSHATRA"])
		$comments.="<br><b>"." Nakshatra : "."</b><br>"."Changed From "."<b>".$editrow["NAKSHATRA"]."</b><br>"." To "."<b>".$Nakshatram."</b>";

	if($editrow["WIFE_WORKING"]!=$Wife_Working)
		$comments.="<br><b>"." Prefer wife to be working"."</b><br>"."Changed From "."<b>".$editrow["WIFE_WORKING"]."</b><br>"." To "."<b>".$Wife_Working."</b>";

	if($editrow["SHOW_HOROSCOPE"]!=$display_horo)
		$comments.="<br><b>"." Horoscope display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOW_HOROSCOPE"]."</b><br>"." To "."<b>".$display_horo."</b>";

	if(trim($Job_Info)!=$editrow["JOB_INFO"])
		$comments.="<br><b>"." Job related information : "."</b><br>"."Changed From "."<b>".$editrow["JOB_INFO"]."</b><br>"." To "."<b>".$Job_Info."</b>";

	if(trim($Information)!=$editrow["YOURINFO"])
		$comments.="<br><b>"." Your Info : "."</b><br>"."Changed From "."<b>".$editrow["YOURINFO"]."</b><br>"." To "."<b>".$Information."</b>"; 
	if(trim($Spouse)!=$editrow["SPOUSE"])
		$comments.="<br><b>"." Spouse related information  : "."</b><br>"."Changed From "."<b>".$editrow["SPOUSE"]."</b><br>"." To "."<b>".$Spouse."</b>"; 

	if($editrow["FAMILY_BACK"]!=$Family_Back)
	{
		$val =$editrow["FAMILY_BACK"];
                $orig_val = label_select("FAMILY_BACK",$val);
                $new_val =  label_select("FAMILY_BACK",$Family_Back);
                $comments.="<br><b>"." Family Background : "."</b><br>"."Changed From "."<b>".$orig_val[0]."</b><br>"." To "."<b>".$new_val[0]."</b>";	
	}
	if($editrow["FAMILY_VALUES"]!=$Family_Values)
        {
                $val =$editrow["FAMILY_BACK"];
		if($val == 1)
                        $orig_val= "Traditional";
                elseif($val == 2)
                        $orig_val= "Moderate";
                elseif($val == 3)
                        $orig_val= "Liberal";

		if($Family_Values == 1) 
			$new_val= "Traditional";
		elseif($Family_Values == 2) 
			$new_val= "Moderate";
   		elseif($Family_Values == 3)
			$new_val= "Liberal";

                $comments.="<br><b>"." Family Values : "."</b><br>"."Changed From "."<b>".$orig_val."</b><br>"." To "."<b>".$new_val."</b>";
        }
		
	if(trim($Gothra)!=$editrow["GOTHRA"])
		$comments.="<br><b>"." Gothra : "."</b><br>"."Changed From "."<b>".$editrow["GOTHRA"]."</b><br>"." To "."<b>".$Gothra."</b>";
	if($Father_Info!=$editrow["FATHER_INFO"])
		$comments.="<br><b>"." Father related information : "."</b><br>"."Changed From "."<b>".$editrow["FATHER_INFO"]."</b><br>"." To "."<b>".$Father_Info."</b>";

	if($Sibling_Info!=$editrow["SIBLING_INFO"])
		$comments.="<br><b>"." Sibling related information : "."</b><br>"."Changed From "."<b>".$editrow["SIBLING_INFO"]."</b><br>"." To "."<b>".$Sibling_Info."</b>";

	if($editrow["PARENT_CITY_SAME"]!=$Parent_City_Same)
		$comments.="<br><b>"." Parent live in the same city or not : "."</b><br>"."Changed From "."<b>".$editrow["PARENT_CITY_SAME"]."</b><br>"." To "."<b>".$Parent_City_Same."</b>";

	if($Family!=$editrow["FAMILYINFO"])
		$comments.="<br><b>"." Extended Family related information : "."</b><br>"."Changed From "."<b>".$editrow["FAMILYINFO"]."</b><br>"." To "."<b>".$Family."</b>";
	
	if(trim($Parents_Contact)!=$editrow["PARENTS_CONTACT"])
		$comments.="<br><b>"." Contact address of parents : "."</b><br>"."Changed From "."<b>".$editrow["PARENTS_CONTACT"]."</b><br>"." To "."<b>".$Parents_Contact."</b>";

	if($editrow["SHOW_PARENTS_CONTACT"]!=$Show_Parents_Contact)
		$comments.="<br><b>"." Parents Contact Address Display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOW_PARENTS_CONTACT"]."</b><br>"." To "."<b>".$Show_Parents_Contact."</b>";

	if(trim($Address)!=$editrow["CONTACT"])
		$comments.="<br><b>"." Contact address : "."</b><br>"."Changed From "."<b>".$editrow["CONTACT"]."</b><br>"." To "."<b>".$Address."</b>";

	if(trim($pincode)!=$editrow["PINCODE"])// || !is_numeric($pincode))
		$comments.="<br><b>"." Pincode : "."</b><br>"."Changed From "."<b>".$editrow["PINCODE"]."</b><br>"." To "."<b>".$pincode."</b>";

	if($editrow["SHOWADDRESS"]!=$showAddress)
                $comments.="<br><b>"." Contact Address Display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOWADDRESS"]."</b><br>"." To "."<b>".$showAddress."</b>";

	if($ISD!=$editrow["ISD"])
	{
		$comments.="<br><b>"." ISD Code : "."</b><br>"."Changed From "."<b>".$editrow["ISD"]."</b><br>"." To "."<b>".$ISD."</b>";
	}

	if($State_Code!=$editrow["STD"])
		$comments.="<br><b>"." STD Code : "."</b><br>"."Changed From "."<b>".$editrow["STD"]."</b><br>"." To "."<b>".$State_Code."</b>";
	if(trim($Phone)!=$editrow["PHONE_RES"])
		$comments.="<br><b>"." Phone (R) : "."</b><br>"."Changed From "."<b>".$editrow["PHONE_RES"]."</b><br>"." To "."<b>".$Phone."</b>";

	if(trim($Mobile)!=$editrow["PHONE_MOB"])
		$comments.="<br><b>"." Phone (M) : "."</b><br>"."Changed From "."<b>".$editrow["PHONE_MOB"]."</b><br>"." To "."<b>".$Mobile."</b>";

	if($editrow["SHOWPHONE_RES"]!=$Showphone)
		$comments.="<br><b>"." Phone No Display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOWPHONE_RES"]."</b><br>"." To "."<b>".$Showphone."</b>";

        if($editrow["SHOWPHONE_MOB"]!=$Showmobile)
		$comments.="<br><b>"." Mobile No Display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOWPHONE_MOB"]."</b><br>"." To "."<b>".$Showmobile."</b>";

	if(trim($Messenger_ID)!=$editrow["MESSENGER_ID"])
		$comments.="<br><b>"." Messenger ID : "."</b><br>"."Changed From "."<b>".$editrow["MESSENGER_ID"]."</b><br>"." To "."<b>".$Messenger_ID."</b>";

	if($editrow["MESSENGER_CHANNEL"]!=$Messenger)
	{
		if($editrow["MESSENGER_CHANNEL"]==1)
			$orig_val="Yahoo";
        	if($editrow["MESSENGER_CHANNEL"]==2)
			$orig_val="MSN";
		if($editrow["MESSENGER_CHANNEL"]==3)
                        $orig_val="Skype";
        	if($editrow["MESSENGER_CHANNEL"]==5)
                        $orig_val="ICQ";
        	if($editrow["MESSENGER_CHANNEL"]==4)
                        $orig_val="Others";


		if($Messenger==1)
                        $new_val="Yahoo";
                if($Messenger==2)
                        $new_val="MSN";
                if($Messenger==3)
                        $new_val="Skype";
                if($Messenger==5)
                        $new_val="ICQ";
                if($Messenger==4)
                        $new_val="Others";
		$comments.="<br><b>"." Messenger Channel Display Settings: "."</b><br>"."Changed From "."<b>".$orig_val."</b><br>"." To "."<b>".$new_val."</b>";
	}
	if($editrow["SHOWMESSENGER"]!=$showMessenger)
        {
                $comments.="<br><b>"." Messenger Id display settings : "."</b><br>"."Changed From "."<b>".$editrow["SHOWMESSENGER"]."</b><br>"." To "."<b>".$showMessenger."</b>";
        }
	if($editrow["GET_SMS"]!=$GET_SMS)
        {
                $comments.="<br><b>"." Option to avail jeevansathi SMS service : "."</b><br>"."Changed From "."<b>".$editrow["GET_SMS"]."</b><br>"." To "."<b>".$GET_SMS."</b>";
        }
	//echo $comments;
	$crmuser = getname($cid);
        if ($comments!="")
        {
                //echo $comments;
                $sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$profileid','".addslashes(stripslashes($comments))."','E','$company')";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
        }

}
?>
