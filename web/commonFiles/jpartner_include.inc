<?php
function correctPartnerMtongue($profile_mtongue_arr)
{
	
	$mtongue_string=trim(implode(",",$profile_mtongue_arr),"'");
		
		//keywords contacin array of all mtongue values specified in the string
		$keywords = preg_split("/\|#\|+|','+|,/", "$mtongue_string");
		$keywords=array_unique($keywords);
	return $keywords;
	
}

function partner_save_format($arr="")
{
	if(is_array($arr) && $arr[0]!='' && $arr[0]!='All' && $arr[0]!='DM')
	//if(is_array($arr))
	{
		$str=implode("','",$arr);
		$str="'".$str."'";
	}
	return($str);
}

function display_format($str)
{
	if($str)
	{
		$str=trim($str,"'");

		$arr=explode("','",$str);
		return $arr;
	}
	
}

function updateJprofile_PartnerDeatils($jpartnerObj,$profileid,$SAVE_DESIRED_PARTNER='',$Spouse="",$wife_working="",$married_working="")
{
	$db=connect_db();	

	$sql_1="SELECT SPOUSE,SCREENING,WIFE_WORKING,MARRIED_WORKING FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$result_1=mysql_query_decide($sql_1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_1,"ShowErrTemplate");
	$myrow_1=mysql_fetch_array($result_1);

	$previous_spouse=$myrow_1["SPOUSE"];
	$previous_wife_working=$myrow_1["WIFE_WORKING"];
	$previous_married_working=$myrow_1["MARRIED_WORKING"];

	if($SAVE_DESIRED_PARTNER=="Y")
	{
		$Spouse=$previous_spouse;
		$wife_working=$previous_wife_working;
		$married_working=$previous_married_working;
	}
	$curflag=$myrow_1["SCREENING"];

	if(trim($Spouse)=="")
		$curflag=setFlag("SPOUSE",$curflag);
	elseif($Spouse!=$myrow_1["SPOUSE"])
		$curflag=removeFlag("SPOUSE",$curflag);
	if(mysql_affected_rows_js()>0)
		$flag_sort=1;

	$spouseInfo = addslashes(stripslashes($Spouse));
	$updateParams = array('SPOUSE'=>$spouseInfo,'SCREENING'=>$curflag,'WIFE_WORKING'=>$wife_working,'MARRIED_WORKING'=>$married_working);

	//user has updated partner profile.
	if($flag_sort || $jpartnerObj->partnerProfileUpdated){
		$extra_sql1=" , SORT_DT=now() ";
		$updateParams['SORT_DT'] = date('Y-m-d h:i:s');
	}

	//user has updated partner profile.
	include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	$objUpdate = JProfileUpdateLib::getInstance();
	$result = $objUpdate->editJPROFILE($updateParams,$profileid,'PROFILEID');
	if(false === $result) {
		$sql_1 = "UPDATE newjs.JPROFILE SET SPOUSE='".addslashes(stripslashes($Spouse))."',SCREENING='$curflag',WIFE_WORKING='$wife_working',MARRIED_WORKING='$married_working'".$extra_sql1." WHERE PROFILEID='$profileid'";
		logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_1,"ShowErrTemplate");
	}
//	$sql_1 = "UPDATE newjs.JPROFILE SET SPOUSE='".addslashes(stripslashes($Spouse))."',SCREENING='$curflag',WIFE_WORKING='$wife_working',MARRIED_WORKING='$married_working'".$extra_sql1." WHERE PROFILEID='$profileid'";
//	mysql_query_decide($sql_1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_1,"ShowErrTemplate");
}

//top save matchalert
function top_save_save_partnersomeinfo($str='',$label='',$jpartnerObj)
{
	if($str && $label && $jpartnerObj)
	{
		$temp=rtrim($str,",");
		$arr=explode(",",$temp);
		if($arr[0]=="")
			$arr[0]='All';
		$str=partner_save_format($arr);
		$setfunc="set".$label;
		$jpartnerObj->$setfunc($str);	
	}
}

//****************After Submit****************
//@@@@@@@ return should be removed when search is oops based.
function validate_assign_caste($castevalue="",$jpartnerObj)
{
	global $smarty;

	$castevalue=rtrim($castevalue,",");
	$Caste=explode(",",$castevalue);

	if($Caste[0]=="")
		$Caste[0]='All';

	$caste=partner_save_format($Caste);
	$jpartnerObj->setPARTNER_CASTE($caste);
	return($Caste);
}
function validate_assign_religion($relvalue="",$jpartnerObj)
{
        global $smarty;

        $Religion=explode(",",$relvalue);
        $religion=partner_save_format($Religion);
        $jpartnerObj->setPARTNER_RELIGION($religion);
        return($Caste);
}
function validate_assign_mtongue($mtonguevalue="",$jpartnerObj)
{
	global $smarty;

	$Mtongue=explode(",",rtrim($mtonguevalue,","));

	if($Mtongue[0]=="")
		$Mtongue[0]='All';

	$mtongue=partner_save_format($Mtongue);
	$jpartnerObj->setPARTNER_MTONGUE($mtongue);
	return($Mtongue);
}

function validate_assign_mstatus1($value="",$jpartnerObj)
{
        global $smarty;

        $mstatusvalue=rtrim($value,",");
        $temp=explode(",",$value);
        $temp1=partner_save_format($temp);
        $jpartnerObj->setPARTNER_MSTATUS($temp1);
        return($temp);
}


function validate_assign_mstatus($Marital_Status,$jpartnerObj) 
{
	global $smarty;
	$Marital_Status_arr = explode("','",$Marital_Status);
	for($i=0;$i<count($Marital_Status_arr);$i++)
	{
		$MSTATUS_ARRAY[]=$Marital_Status_arr[$i];
		$j=$i+1;
		$smarty->assign("MARITAL_STATUS".$j,"y");
	}
	if(!$Marital_Status)
	{
		$smarty->assign("MARITAL_S","y");
		$is_error++;
	}
	$mstatus=partner_save_format($MSTATUS_ARRAY);	
	$jpartnerObj->setPARTNER_MSTATUS($mstatus);
	return($MSTATUS_ARRAY);
}

function validate_assign_child($Has_Children="",$jpartnerObj)
{
	global $smarty;

	if($Has_Children == "All")
	{
		$smarty->assign("CHILDREN1","y");
	}
	elseif($Has_Children == "N")
	{
		$CHILDREN_ARRAY[]="N";
		$Has_Children_Partner="N";
		$smarty->assign("CHILDREN2","y");
	}
	elseif($Has_Children == "Y")
	{
		$CHILDREN_ARRAY[]="YT";
		$CHILDREN_ARRAY[]="YS";
		$CHILDREN_ARRAY[]="Y";
		$Has_Children_Partner="Y";
		$smarty->assign("CHILDREN3","y");
	}

	//if(!$Has_Children)
		//$Has_Children == "All";
	$jpartnerObj->setCHILDREN($Has_Children_Partner);
	return($CHILDREN_ARRAY);
}

function validate_assign_relation($relation1="",$relation2="",$relation3="",$relation4="",$jpartnerObj)
{
	global $smarty;

	if($relation1)
	{
		$relation=$relation1;
		$smarty->assign("relation1","Y");
	}
	if($relation2)
	{
		$relation.=','.$relation2;
		$smarty->assign("relation2","Y");
	}
	if($relation3)
	{
		$relation.=','.$relation3;
		$smarty->assign("relation3","Y");
	}
	if($relation4)
	{
		$relation.=','.$relation4;
		$smarty->assign("relation4","Y");
	}

	$relation=ltrim($relation,',');
	$relationarr=explode(",",$relation);
	$rel=partner_save_format($relationarr);	
	$jpartnerObj->setPARTNER_RELATION($rel);
	return $relation;
}

function validate_assign_manglik($Manglik_Status,$jpartnerObj)
{
	global $smarty;
	$Manglik_Status_arr = explode(",",$Manglik_Status);
        for($i=0;$i<count($Manglik_Status_arr);$i++)
        {
                $MANGLIK_ARRAY[]=$Manglik_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("MANGLIK_STATUS".$j,"y");
        }
	if(!$Manglik_Status)
	{
		$smarty->assign("MANGLIK_S","y");
		$is_error++;
	}

	$manglik=partner_save_format($MANGLIK_ARRAY);	
	$jpartnerObj->setPARTNER_MANGLIK($manglik);
	return($MANGLIK_ARRAY);
}

function validate_assign_diet($Diet,$jpartnerObj)
{	
	global $smarty,$is_error;

	$Diet_Status_arr = explode("','",$Diet);
        for($i=0;$i<count($Diet_Status_arr);$i++)
        {
                $DIET_ARRAY[]=$Diet_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("DIET_STATUS".$j,"y");
        }
        $diet=partner_save_format($DIET_ARRAY);
        $jpartnerObj->setPARTNER_DIET($diet);
	return($DIET_ARRAY);
}
function validate_assign_comp($Complexion,$jpartnerObj)
{
	global $smarty;
	$Complexion_Status_arr = explode("','",$Complexion);
        for($i=0;$i<count($Complexion_Status_arr);$i++)
        {
                $COMPLEXION_ARRAY[]=$Complexion_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("COMPLEXION_STATUS".$j,"y");
        }
        $comp=partner_save_format($COMPLEXION_ARRAY);
        $jpartnerObj->setPARTNER_COMP($comp);
	return($COMPLEXION_ARRAY);
}

function validate_assign_btype($Body_Type,$jpartnerObj)
{
	global $smarty;
        $Body_Type_Status_arr = explode("','",$Body_Type);
        for($i=0;$i<count($Body_Type_Status_arr);$i++)
        {
                $BODYTYPE_ARRAY[]=$Body_Type_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("BODYTYPE_STATUS".$j,"y");
        }
	$btype=partner_save_format($BODYTYPE_ARRAY);
        $jpartnerObj->setPARTNER_BTYPE($btype);
	return($BODYTYPE_ARRAY);
}

function validate_assign_smoke($Smoke,$jpartnerObj)
{
	global $smarty;
	$Smoke_Status_arr = explode(",",$Smoke);
        for($i=0;$i<count($Smoke_Status_arr);$i++)
        {
                $SMOKE_ARRAY[]=$Smoke_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("SMOKE".$j,"y");
        }
        $smoke=partner_save_format($SMOKE_ARRAY);
        $jpartnerObj->setPARTNER_SMOKE($smoke);
	return($SMOKE_ARRAY);
}

function validate_assign_drink($Drink,$jpartnerObj) 
{
	global $smarty;
	$Drink_Status_arr = explode(",",$Drink);
        for($i=0;$i<count($Drink_Status_arr);$i++)
        {
                $DRINK_ARRAY[]=$Drink_Status_arr[$i];
                $j=$i+1;
                $smarty->assign("DRINK".$j,"y");
        }
        $drink=partner_save_format($DRINK_ARRAY);
        $jpartnerObj->setPARTNER_DRINK($drink);
	return($DRINK_ARRAY);
}

function  validate_assign_handicapped($Handicapped="",$jpartnerObj)
{
	global $smarty;
	if($Handicapped =="All")
	{
		$Handicapped_Search="";
		$Handicapped_Partner="";
		$smarty->assign("HANDICAPPED1","y");
	}
	elseif($Handicapped == "Not Handicapped" || $Handicapped=='N')
	{
		$Handicapped_Search= "'N'";
		$Handicapped_Partner="N";
		$smarty->assign("HANDICAPPED2","y");
	}
	elseif($Handicapped == "Only Handicapped" || strstr($Handicapped,'1'))
	{
		$Handicapped_Search= "'1','2','3','4'";
		$Handicapped_Partner="Y";
		$smarty->assign("HANDICAPPED3","y");
	}
	if(!$Handicapped)
	{
		$Handicapped_Search="";
		$Handicapped_Partner="";
	}
	$jpartnerObj->setHANDICAPPED($Handicapped_Partner);	
	return($Handicapped_Search);
}

function validate_assign_occ($occupationvalue="",$jpartnerObj)
{
	global $smarty;

	$Occupation=explode(",",rtrim($occupationvalue,","));

	if($Occupation[0]=="")
		$Occupation[0]='All';
	
        $occ=partner_save_format($Occupation);
        $jpartnerObj->setPARTNER_OCC($occ);
	return($Occupation);
}

function validate_assign_income($incomevalue="",$jpartnerObj)
{
	global $smarty;

	$incomevalue=rtrim($incomevalue,",");
	$Income=explode(",",$incomevalue);
	if($Income[0]=="")
		$Income[0]='All';

        $inc=partner_save_format($Income);
        $jpartnerObj->setPARTNER_INCOME($inc);
	return($Income);
}


function validate_assign_edulevelnew($education_level_new_value="",$jpartnerObj)
{
	global $smarty;

	$Education_Level_New=explode(",",rtrim($education_level_new_value,","));

	if($Education_Level_New[0]=="")
		$Education_Level_New[0]='All';

        $edunew=partner_save_format($Education_Level_New);
        $jpartnerObj->setPARTNER_ELEVEL_NEW($edunew);
	return($Education_Level_New);
}

function validate_assign_country($country_residencevalue="",$jpartnerObj)
{
	global $smarty;

	$Country_Residence=explode(",",rtrim($country_residencevalue,","));

	if($Country_Residence[0]=="")
		$Country_Residence[0]='All';

        $country=partner_save_format($Country_Residence);
        $jpartnerObj->setPARTNER_COUNTRYRES($country);
	return($Country_Residence);
}

function validate_assign_city($city_indiavalue="",$jpartnerObj)
{
	global $smarty;

	$City_India=explode(",",rtrim($city_indiavalue,","));

	if($City_India[0]=="")
		$City_India[0]='All';

        $city=partner_save_format($City_India);
        $jpartnerObj->setPARTNER_CITYRES($city);
	return($City_India);
}
//POINTER

function validate_assign_age($Min_Age="",$Max_Age="",$jpartnerObj)
{
	global $smarty,$is_error;

	if($Min_Age > $Max_Age)
	{
		$smarty->assign("check_age","Y");
		$is_error++;
	}
	$jpartnerObj->setLAGE($Min_Age);
	$jpartnerObj->setHAGE($Max_Age);
}
function validate_assign_height($Min_Height="",$Max_Height="",$jpartnerObj)
{
	global $smarty,$is_error;

	if($Min_Height > $Max_Height)
	{
		$smarty->assign("check_height","Y");
		$is_error++;
	}
	$jpartnerObj->setLHEIGHT($Min_Height[0]);
	$jpartnerObj->setHHEIGHT($Max_Height[0]);

}
function validate_assign_profile($Profile="",$jpartnerObj)
{
	global $smarty;

	if($Profile == "Doesnt Matter")
		$smarty->assign("PROFILE1","y");
	elseif($Profile == "Y")
		$smarty->assign("PROFILE2","y");
	elseif($Profile == "N")
		$smarty->assign("PROFILE3","y");

	if(!$Profile)
	{
		$smarty->assign("PROFILE","y");
	}
	$smarty->assign("PROF","$Profile");
	//$jpartnerObj->
}

function validate_assign_spouse($Spouse="",$jpartnerObj)
{
	global $smarty;

	$smarty->assign("SPOUSE",$Spouse);
	//$jpartnerObj
}
function validate_assign_nri_cosmo($nri="",$cosmo="",$jpartnerObj)
{
	global $smarty;

	$smarty->assign("nri",$nri);
	$smarty->assign("cosmo",$cosmo);

	if($nri && $cosmo)
		$jpartnerObj->setPARTNER_NRI_COSMO("'N','R'");
	elseif($nri)
		$jpartnerObj->setPARTNER_NRI_COSMO("'N'");
	elseif($cosmo)	
		$jpartnerObj->setPARTNER_NRI_COSMO("'R'");
}
/***********************************AFTER SUMBIT******************************************************/

function smarty_desiredgender($Gender="")
{
	global $smarty;

	if($Gender)
		$smarty->assign("G",$Gender);
	else
		$smarty->assign("G",'F');
}


function smarty_desiredrelation($relation="")
{
	if($relation)
	{
		global $smarty;
		$smarty->assign("posted_by",1);
		if(strstr($relation,'1'))
			$smarty->assign("relation1",'Y');
		if(strstr($relation,'2'))
			$smarty->assign("relation2",'Y');
		if(strstr($relation,'4'))
			$smarty->assign("relation4",'Y');
		if(strstr($relation,'5'))
			$smarty->assign("relation3",'Y');
	}
}
function smarty_desirednricosmo($nri_cosmo="")
{
	if($nri_cosmo)
	{
		global $smarty;
		$smarty->assign("nri_cosmo",1);
		//if($nri_cosmo=='N')
		if(strstr($nri_cosmo,'N'))
			$smarty->assign("nri",1);
		if(strstr($nri_cosmo,'R'))
		 //elseif($nri_cosmo=='R')
			$smarty->assign("cosmo",1);
	}
}
function smarty_desiredCaste($caste="",$caste_mtongue="")
{
	global $smarty;

	if($caste || $caste_mtongue)
	{
		if($caste_mtongue)
		//If caste mapping logic is used in save search.Then add all mapped caste in CASTE field of advance search while ignoring mapped Mtongue. 
		{
			$RELATED_CHECK=explode("+",$caste_mtongue);

			for($i=0;$i<count($RELATED_CHECK);$i++)
			{
				$map_caste_temp=explode("-",$RELATED_CHECK[$i]);
				$map_caste_array[$i].=$map_caste_temp[0];
			}
			$map_caste_array=array_unique($map_caste_array);
		}
		if($caste)
		{		
			$caste_value=display_format($caste);
		}

		if(is_array($map_caste_array))
			$caste_value=array_merge($caste_value,$map_caste_array);
	}
	if(count($caste_value))
		$smarty->assign("caste",create_dd($caste_value,"Caste"));
	else
	{
		$smarty->assign("f_caste","1");
		$smarty->assign("caste",create_dd("","Caste"));
	}
}
function smarty_desiredMtongue($mtongue="")
{
	global $smarty;
	
	if($mtongue)
		$mtongue_value=display_format($mtongue);

	if(count($mtongue_value))
	{
		$smarty->assign("top_mtongue",create_dd("","top_mtongue"));
		$smarty->assign("mtongue",create_dd($mtongue_value,"Mtongue","","","AS"));
	}
	else
	{
		$smarty->assign("f_mtongue","1");
		$smarty->assign("top_mtongue",create_dd("","top_mtongue"));
		$smarty->assign("mtongue",create_dd("","Mtongue","","","AS"));
	}

}
function smarty_desiredOccupation($occupation="")
{
	global $smarty;

	if($occupation)
		$occ_value=display_format($occupation);

	if(count($occ_value))
	{
		$smarty->assign("occupation",create_dd($occ_value,"Occupation"));
	}
	else
	{
		$smarty->assign("f_occupation","1");
		$smarty->assign("occupation",create_dd("$occ_value","Occupation"));
	}

}
function smarty_desiredCountry($country="")
{
	global $smarty;

	if($country)
		$country_value=display_format($country);

	if(count($country_value))
	{
		$smarty->assign("country_residence",create_dd($country_value,"Country_Residence"));
	}
	else
	{
		$smarty->assign("f_country","1");
		$smarty->assign("country_residence",create_dd("","Country_Residence"));
	}

}
function smarty_desiredCity($city="")
{
	global $smarty;

	if($city)
		$city_value=display_format($city);

	if(count($city_value))
	{
		$city_india=create_dd($city_value,"City_India");
		$city_usa=create_dd($city_value,"City_USA");
	}
	else
	{
		$smarty->assign("f_city","1");
		$city_india=create_dd("","City_India");
		$city_usa=create_dd("","City_USA");
	}
	$city_india .=  $city_usa;
	$smarty->assign("city_india",$city_india);

}
function smarty_desiredElevel($elevel="")
{
	global $smarty;

	if($elevel)
		$education_value=display_format($elevel);	

	if(count($education_value))
		$smarty->assign("education_level",create_dd($education_value,"Education_Level"));
	else
	{
		$smarty->assign("f_education","1");
		$smarty->assign("education_level",create_dd("","Education_Level"));
	}

}

function smarty_desiredElevelNew($elevelnew)
{
	global $smarty;

	if($elevelnew)
		$education_new_value=display_format($elevelnew);

	if(count($education_new_value))
		$smarty->assign("education_level_new",create_dd($education_new_value,"Education_Level_New"));
	else
	{
		$smarty->assign("f_education_new","1");
		$smarty->assign("education_level_new",create_dd("","Education_Level_New"));
	}
		
}

function smarty_desiredMstatus($mstatus="")
{
	global $smarty;

	if($mstatus)
	{
		if(strstr($mstatus,'N'))
			$smarty->assign("MARITAL_STATUS2","y");
		if(strstr($mstatus,'W'))
			$smarty->assign("MARITAL_STATUS3","y");
		if(strstr($mstatus,'D'))
			$smarty->assign("MARITAL_STATUS4","y");
		if(strstr($mstatus,'S'))
			$smarty->assign("MARITAL_STATUS5","y");
		if(strstr($mstatus,'O'))
			$smarty->assign("MARITAL_STATUS6","y");
		if(strstr($mstatus,'A'))
			$smarty->assign("MARITAL_STATUS7","y");
	}	
}

function smarty_desiredManglik($manglik="")
{
	global $smarty;

	if($manglik)
	{
		if(strstr($manglik,'M'))
			$smarty->assign("MANGLIK_STATUS2","y");
		if(strstr($manglik,'N'))
			$smarty->assign("MANGLIK_STATUS3","y");
	}
}

function smarty_desiredBtype($btype="")
{
	global $smarty;

	if($btype)
	{
		if(strstr($btype,'1'))
			$smarty->assign("BODY_TYPE2","y");
		if(strstr($btype,'2'))
			$smarty->assign("BODY_TYPE3","y");
		if(strstr($btype,'3'))
			$smarty->assign("BODY_TYPE4","y");
		if(strstr($btype,'4'))
			$smarty->assign("BODY_TYPE5","y");
	}
}
function smarty_desiredComp($comp="")
{
	global $smarty;

	if(strstr($comp,'1'))
		$smarty->assign("COMPLEXION2","y");
	if(strstr($comp,'2'))
		$smarty->assign("COMPLEXION3","y");
	if(strstr($comp,'3'))
		$smarty->assign("COMPLEXION4","y");
	if(strstr($comp,'4'))
		$smarty->assign("COMPLEXION5","y");
	if(strstr($comp,'5'))
		$smarty->assign("COMPLEXION6","y");

}
function smarty_desiredSmoke($smoke="")
{
	global $smarty;

	if(strstr($smoke,'Y'))
		$smarty->assign("SMOKE2","y");
	if(strstr($smoke,'N'))
		$smarty->assign("SMOKE3","y");
	if(strstr($smoke,'O'))
		$smarty->assign("SMOKE4","y");
}
function smarty_desiredDrink($drink="")
{
	global $smarty;
	if(strstr($drink,'Y'))
		$smarty->assign("DRINK2","y");
	if(strstr($drink,'N'))
		$smarty->assign("DRINK3","y");
	if(strstr($drink,'O'))
		$smarty->assign("DRINK4","y");

}
function smarty_desiredChildren($children="")
{
	global $smarty;

	if(strstr($children,'N'))
		$smarty->assign("CHILDREN2","y");
	if( strstr($children,'Y') || strstr($children,'YT') || strstr($children,'YS') )
		$smarty->assign("CHILDREN3","y");
}
function smarty_desireHandicapped($handicapped="")
{
	global $smarty;

	if($handicapped)
	{
		if($handicapped=='N')
			$smarty->assign("HANDICAPPED2","y");
		else
			$smarty->assign("HANDICAPPED3","y");
	}
	else
		$smarty->assign("HANDICAPPED1","y");

}
function smarty_desiredDiet($diet="")
{
	global $smarty;

	if(strstr($diet,'V'))
		$smarty->assign("DIET2","y");
	if(strstr($diet,'N'))
		$smarty->assign("DIET3","y");
	if(strstr($diet,'J'))
		$smarty->assign("DIET4","y");

}
function smarty_desiredResStatus($rstatus="")
{
	global $smarty;

	if($rstatus)
	{
		if(strstr($rstatus,1))
			$smarty->assign("r0","1");
		if(strstr($rstatus,2))
			$smarty->assign("r1","1");
		if(strstr($rstatus,3))
			$smarty->assign("r2","1");
		if(strstr($rstatus,4))
			$smarty->assign("r3","1");
		if(strstr($rstatus,5))
			$smarty->assign("r4","1");
		if(strstr($rstatus,6))
			$smarty->assign("r5","1");
	}
	else
		$smarty->assign("r0", 1);
}

function smarty_desiredAge($lage="",$hage="")
{
	global $smarty;
	if($hage)
		$smarty->assign("MAX_AGE",$hage);
	if($lage)
		$smarty->assign("MIN_AGE",$lage);
}
function smarty_desiredHeight($lheight="",$hheight="")
{
	global $smarty;

	$smarty->assign("maxheight",create_dd($hheight,"Height",1));
	$smarty->assign("minheight",create_dd($lheight,"Height"));
}
function smarty_desiredIncome($income)
{
	global $smarty;

	//$incomevalue=explode(",",$income);
	$incomevalue=display_format($income);

	if(count($incomevalue))
	{
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME  order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$ret = "";
		$ret.="<option value=\"All\">All</option>";
		$incomeval.="All#";
		$incomelab.="ALL#";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $incomevalue))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
				$incomeval.=$myrow["VALUE"]."#";
				$incomelab.=$myrow["LABEL"]."#";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
				$incomeval.=$myrow["VALUE"]."#";
				$incomelab.=$myrow["LABEL"]."#";
			}
		}
		$smarty->assign("incomeval",$incomeval);
		$smarty->assign("incomelab",$incomelab);
		$smarty->assign("income",$ret);
	}
	else
	{
		$smarty->assign("f_income","1");
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$ret = "";
		$ret.="<option value=\"All\" selected>ALL</option>";
		$incomeval.="All#";
		$incomelab.="ALL#";
		while($myrow = mysql_fetch_array($res))
		{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
				$incomeval.=$myrow["VALUE"]."#";
				$incomelab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("incomeval",$incomeval);
		$smarty->assign("incomelab",$incomelab);
		$smarty->assign("income",$ret);
	}
}
function smarty_income($tempFlag=1)
{
	global $smarty;
	if($tempFlag==1)
	{
		$smarty->assign("SHOWINCOME","1");
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$ret = "";
		$ret.="<option value=\"All\">ALL</option>";
		$retval.="All#";
		$retlab.="ALL#";
		while($myrow = mysql_fetch_array($res))
		{
			$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			$retval.=$myrow["VALUE"]."#";
			$retlab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("retval",$retval);
		$smarty->assign("retlab",$retlab);
		$smarty->assign("income",$ret);

	}
	else
	{	
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);

		$ret = "";
		$ret.="<option value=\"All\">ALL</option>";

		$incomeval.="All#";
		$incomelab.="All#";

		while($myrow = mysql_fetch_array($res))
		{
			$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			$incomeval.=$myrow["VALUE"]."#";
			$incomelab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("incomelab",$incomelab);
		$smarty->assign("incomeval",$incomeval);
		$smarty->assign("income",$ret);
	}
}

function smarty_mstatus($mstatus="")
{
	global $smarty;

	if($mstatus=='N')
	      $smarty->assign("MARITAL_STATUS2","y");
	else
	      $smarty->assign("MARITAL_STATUS1","y");
}

function smarty_caste($caste="")
{
	global $smarty;

        $smarty->assign("caste",create_dd("$caste","Caste"));
}

function smarty_age($gender,$age)
{
	global $smarty;
	if($gender=="M")
	{
		$desired_min=$age-10;
		if($desired_min<18)
			$desired_min=18;
		$smarty->assign("MIN_AGE",$desired_min);
		$smarty->assign("MAX_AGE",$age);
	}
	else
	{
		$desired_min=$age;
		if($desired_min<21)
			$desired_min=21;
		$smarty->assign("MIN_AGE",$desired_min);

		$hage=$age+10;
		if($hage>70)
			$hage=70;
		$smarty->assign("MAX_AGE",$hage);
	}
}

function smarty_height($gender,$height)
{
	global $smarty;

	if($gender=="M")
	{
		$U_HEIGHT=$global_profile_info["HEIGHT"];
		$smarty->assign("maxheight",create_dd("$U_HEIGHT","Height"));	
		$smarty->assign("minheight",create_dd("","Height"));
	}
	else
	{
		$L_HEIGHT=$age_row["HEIGHT"];	
		$smarty->assign("maxheight",create_dd("","Height",1));
		$smarty->assign("minheight",create_dd("$L_HEIGHT","Height"));
	}
}

function smarty_default_info()
{
	global $smarty;

	$smarty->assign("MIN_AGE","20");
	$smarty->assign("MAX_AGE","40");
	$smarty->assign("G","");
	$smarty->assign("maxheight",create_dd("","Height",1));
	$smarty->assign("minheight",create_dd("","Height"));
	$smarty->assign("caste",create_dd("","Caste"));
}

function populate_PartnerDisplayForm_From_Jprofile($profileid,$partner_exist="",$flag="")
{
	global $smarty;

include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	$default_sql="select AGE,HEIGHT,RELIGION,CASTE,MSTATUS,MANGLIK,MTONGUE,SPOUSE,WIFE_WORKING,MARRIED_WORKING from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$default_res=mysql_query_decide($default_sql) or logError($ERROR_STRING,$default_sql);
	if($default_row=mysql_fetch_assoc($default_res))
	{
		if(!$partner_exist && $flag!='search')
		{
			$smarty->assign("d_caste",$default_row['CASTE']);
			$smarty->assign("d_manglik",$default_row['MANGLIK']);
			$smarty->assign("d_mstatus",$default_row['MSTATUS']);
		}
		$global_profile_info["AGE"]=$default_row['AGE'];
		$global_profile_info["HEIGHT"]=$default_row['HEIGHT'];
		$global_profile_info["CASTE"]=$default_row['CASTE'];
		$global_profile_info["MSTATUS"]=$default_row['MSTATUS'];
		//$global_profile_info[""]=$default_row[''];

		$smarty->assign("SPOUSE",$default_row['SPOUSE']);
		$smarty->assign("wife_working",$default_row['WIFE_WORKING']);
		$smarty->assign("married_working",$default_row['MARRIED_WORKING']);

		$religionVal=$default_row["RELIGION"];	
	
		if($religionVal)
		{
			$d_religion=$RELIGIONS[$religionVal];
			if(!$d_religion)
			{
				$sql_religion="select LABEL FROM RELIGION where VALUE='$religionVal'";
				$res_religion=mysql_query_decide($sql_religion)  or logError($ERROR_STRING,$sql_religion);
				$row_religion=mysql_fetch_assoc($res_religion);
				$d_religion=$row_religion['LABEL'];		
			}

			if(!($d_religion=='Hindu' || $d_religion=='Jain' || $d_religion=='Parsi' || $d_religion=='Sikh' ))
			{
				$smarty->assign("def_manglik","NO");
			}
		}
	}
	return $global_profile_info;
}

function check_filter($jpartnerObj,$mysqlObj,$myDb,$profileid='',$filter='')
{
	global $ERROR_STRING;
	if(!$profileid)
		$profileid=$jpartnerObj->getPROFILEID();
	$db=connect_db();	

	$sql1= "SELECT SOURCE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
        $res1= mysql_query_decide($sql1) or die(logError($ERROR_STRING,$sql1,"ShowErrTemplate"));
        $row1= mysql_fetch_assoc($res1);
        $source=$row1["SOURCE"];

	$sql="select * from FILTERS where PROFILEID='$profileid'";
	$resultfilter=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");

	
	if(mysql_num_rows($resultfilter) > 0)
	{
		$filterrow=mysql_fetch_array($resultfilter);
		
		$sql="SELECT LAGE,HAGE,PARTNER_MSTATUS,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_MTONGUE,PARTNER_RELIGION,PARTNER_INCOME FROM JPARTNER WHERE PROFILEID=$profileid";
                $result = $mysqlObj->executeQuery($sql,$myDb);
                $myrow =$mysqlObj->fetchAssoc($result);

		$sql="UPDATE FILTERS SET ";
		if($filterrow["AGE"]=="Y")
			if($myrow['LAGE']!=$jpartnerObj->getLAGE() || $myrow['HAGE']!=$jpartnerObj->getHAGE())
				$setcondn[]= " AGE='N'";

		if($filterrow["MSTATUS"]=="Y")
			if($myrow['PARTNER_MSTATUS']!=$jpartnerObj->getPARTNER_MSTATUS())
				$setcondn[]=" MSTATUS='N'";

		if($source=='ofl_prof')
		{
			$setcondn[]=" RELIGION='Y'";
			$setcondn[]="MTONGUE = 'Y'";
			$setcondn[]="CASTE = 'Y'";
		}
		else
		{
			if($filterrow["CASTE"]=="Y")
				if($myrow['PARTNER_CASTE']!=$jpartnerObj->getPARTNER_CASTE())
					$setcondn[]=" CASTE='N'";

			if($filterrow["RELIGION"]=="Y")
				if($myrow['PARTNER_RELIGION']!=$jpartnerObj->getPARTNER_RELIGION())
					$setcondn[]=" RELIGION='N'";

			if($filterrow["MTONGUE"]=="Y")
	                        if($myrow['PARTNER_MTONGUE']!=$jpartnerObj->getPARTNER_MTONGUE())
	                                $setcondn[]="MTONGUE = 'N'";

		}

		if($filterrow["COUNTRY_RES"]=="Y")
			if($myrow['PARTNER_COUNTRYRES']!=$jpartnerObj->getPARTNER_COUNTRYRES())
				$setcondn[]="COUNTRY_RES='N'";

		if($filterrow["CITY_RES"]=="Y")
			if($myrow['PARTNER_CITYRES']!=$jpartnerObj->getPARTNER_CITYRES())
				$setcondn[]="CITY_RES='N'";

		if($filterrow["INCOME_RES"]=="Y")
			if($myrow['PARTNER_INCOME']!=$jpartnerObj->getPARTNER_INCOME())
				$setcondn[]="INCOME='N'";

		if(is_array($setcondn))
		{	
			$setcondnstr=implode(" , ",$setcondn);
			$sql.=$setcondnstr." WHERE PROFILEID='$profileid'";
		//	die($sql);
			if ($filter == '')
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
		}
	}
	else
	{
		if($source=='ofl_prof')
		{
//			 $sql_offline="INSERT INTO FILTERS VALUES($profileid,'','N','N','Y','N','Y')";
			 $sql_offline="INSERT INTO FILTERS(PROFILEID,FILTERID,AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE) VALUES($profileid,'','N','N','Y','','N','','Y')";

                        mysql_query_decide($sql_offline) or logError($$ERROR_STRING,$sql_offline,"ShowErrTemplate");
		}

	}
}
function get_all_cities($City_Res)
{
        global $db;
	$city_str="";
	if(class_exists('sfConfig') && class_exists("FieldMap"))
	{
		$city_str=implode(",",array_keys(FieldMap::getFieldLabel("city_india",'',1)));
		
	}
        if(is_array($City_Res))
        {
                if(!in_array("All",$City_Res) && !in_array("",$City_Res))
                {
                        $insertCity=implode($City_Res,",");

                        for($i=0;$i<count($City_Res);$i++)
                        {
                                if(is_numeric($City_Res[$i]))
                                        $city_usa[]=$City_Res[$i];
                                elseif(strlen($City_Res[$i])==2)
                                {
					if($city_str)
					{
						preg_match_all ( "/\,($City_Res[$i].*?)\,/" , $city_str , $matches);
						if(is_array($matches[1]))
							foreach ($matches as $key => $value)
								$city_india[]=$matches[1][$i];
					}
					else
					{
	                                        $citysql="select SQL_CACHE VALUE from newjs.CITY_NEW where VALUE like '$City_Res[$i]%'";
        	                                $cityresult=mysql_query($citysql,$db) or logError(mysql_error($db),$citysql);
	
                        	                while($cityrow=mysql_fetch_array($cityresult))
        	                                        $city_india[]=$cityrow["VALUE"];
                	                        mysql_free_result($cityresult);
					}
                                 }
                                else
                                        $city_india[]=$City_Res[$i];
                        }
                }
        }
	elseif($City_Res!="" && $City_Res!="All")
        {
                $insertCity=$City_Res;
                if(is_numeric($City_Res))
                        $city_usa[]=$City_Res;
                else
                {
                        if(strlen($City_Res)==2)
                        {
				if($city_str)
				{
					preg_match_all ( "/\,($City_Res[$i].*?)\,/" , $city_str , $matches);
					$city_india=$matches[1];
				}
				else
				{
	                                $citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res%'";
        	                        $cityresult=mysql_query($citysql,$db) or logError(mysql_error($db),$citysql);

                	                while($cityrow=mysql_fetch_array($cityresult))
                        	                $city_india[]=$cityrow["VALUE"];
                                	mysql_free_result($cityresult);
				}
                        }
                        else
                                $city_india[]=$City_Res;
                }
        }
        if(is_array($city_india))
        {
                foreach($city_india as $key=>$value)
                        $city_res_list[]=$value;
        }
        if(is_array($city_usa))
        {
                foreach($city_usa as $key=>$value)
                        $city_res_list[]=$value;
        }
        if(is_array($city_res_list))
                return $city_res_list;
        else
                return '';

}
?>
