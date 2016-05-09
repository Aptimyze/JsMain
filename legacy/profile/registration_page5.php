<?php
/*********************************************registration_dpp.php********************************
        Created By                      : Tanu Gupta
        Created on                      : 11-Apr-2009
        Description                     : This file is used for saving DPP detail at the time of registration
**********************************************************************************************************/
//to zip the file before sending it
$start_tm=microtime(true);
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}

include_once("connect.inc");
include_once("search.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once("registration_functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
include_once("advance_search_functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
$db=connect_db();
$now = date("Y-m-d G:i:s");

if(!$sem)
{
	if(!$data_auth){
		$data_auth=authenticated($checksum,'y');
		if(!$data_auth)	{
			header("Location: ".$SITE_URL."/profile/registration_page1.php");
			exit;
		}
	}
}

/* Assign sem for Track ID #20 */

$smarty->assign('sem',$sem);

if($ajax)
{
	$data = authenticated();
	$profileid=$data['PROFILEID'];
}
$smarty->assign("profileid",$profileid);	
populateIncomeDropDowns();
//<<<<<<<<<<<<<<<<<<<<<<<<<<query to get religion and other fields>>>>>>>>>>>>>>> 
$sql_logged="SELECT MSTATUS,USERNAME,MTONGUE, RELIGION, GENDER, AGE, INCOME, HEIGHT,GENDER,CASTE,SCREENING, SPOUSE,YOURINFO FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
$res_logged=mysql_query_decide($sql_logged) or die(mysql_error_js());
$row_logged=mysql_fetch_assoc($res_logged);
$inc=$row_logged['INCOME'];
$gen=$row_logged['GENDER'];
$mas=$row_logged['MSTATUS'];
if($gen=='F')
{
	$looking='Groom';
	$Gender="M";
}
else
{
	$looking='Bride';
	$Gender="F";
}
$smarty->assign("looking",$looking);
$smarty->assign("Gender",$Gender);
$smarty->assign("LOGIN",1);
$smarty->assign("USERNAME",$row_logged['USERNAME']);
$smarty->assign("checksum",$checksum);
$smarty->assign("TIEUP_SOURCE",$tieup_source);

if($gen=='F' && $record_id){
	if($skip_to_next_page6)
	{
		include_once("registration_page6.php");
		die;
	}
}


/****Tracking needed in previous page********
$smarty->assign("TIEUP_SOURCE",$tieup_source);
function pixelcode($VAR)
{
	if($VAR)
	{
		$sql="SELECT PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		return $row[PIXELCODE];	
	}
}
if($groupname)
	$VAR = $groupname;
elseif($GROUPNAME)
	$VAR = $GROUPNAME;
elseif($SOURCE)
	$VAR = $SOURCE;
$pixelcode = pixelcode($VAR);
$smarty->assign("pixelcode",$pixelcode);
$smarty->assign("reg_comp_frm_ggl",$reg1_comp_frm_ggl);
$smarty->assign("reg_comp_frm_ggl_nri",$reg1_comp_frm_ggl_nri);
$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));
*******Ends here***********/
$profilechecksum=md5($profileid) . "i" . $profileid;
$smarty->assign("profilechecksum",$profilechecksum);
$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
//If coming from sugarcrm flow
if($record_id){
$record_id=mysql_real_escape_string($record_id);
$smarty->assign("RECORD_ID",$record_id);
}
if($submitReg || $submitReg_x)
{
	/*Profile score**********/
	include_once("sem.php"); // Function for the Calculating the Score
	$profile_score = profileScore($profileid);  // Findind the Score of the Profile

	$sql = "INSERT IGNORE INTO MIS.PROFILE_SCORE (`PROFILE_ID` , `SCORE` ) VALUES ( '$profileid' , '$profile_score')";
	$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Pleasetry after some time.",$sql,"ShowErrTemplate");
	$smarty->assign("profile_score",$profile_score);
	/**********Ends here**************/

	/**********Set Jpartner values********/
	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;
	if(!$myDb)
	{
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
	}
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	/************Ends here****************/

	$jpartnerObj->setPROFILEID($profileid);

	if($jpartnerObj->getDPP()=='O' || $jpartnerObj->getDPP()=='S')
		$partner_exist=1;
	else
		$partner_exist=0;
	if($partner_exist)
	{
		//check_filter($jpartnerObj,$lage,$hage,$partner_mstatus_arr,$partner_caste_arr,"",$partner_mtongue_arr);
		check_filter($jpartnerObj,$mysqlObj,$myDb,$profileid);
	}


	/************Update Partner details********/
	$jpartnerObj->setGENDER($Gender);
	$jpartnerObj->setLAGE($Min_Age);
	$jpartnerObj->setHAGE($Max_Age);
	$jpartnerObj->setLHEIGHT($Min_Height);
	$jpartnerObj->setHHEIGHT($Max_Height);
	$jpartnerObj->setDPP('R');		

//trac 1684 fix for improper entry in Mtongue by nitesh
	$partner_mtongue_arr=correctPartnerMtongue($partner_mtongue_arr);

/*********Changes for Sindhi map******/

	if($partner_mtongue_arr)
	{
		foreach($partner_mtongue_arr as $key=>$val)
		{
			if($val == 70)
			{
				unset($val);
				$partner_mtongue_arr[$key][$val] = 30;
			}
		}
	}
	/***********Ends here***************/
	if($partner_mstatus_arr)
		$partner_mstatus_str = partner_save_format($partner_mstatus_arr);
	else
		$partner_mstatus_str = stripslashes($partner_mstatus_str);
	$partner_mtongue_str = partner_save_format($partner_mtongue_arr);
	$partner_caste_str = partner_save_format($partner_caste_arr);
	if($partner_income_arr)
		$partner_income_str = partner_save_format($partner_income_arr);
	else
		$partner_income_str = stripslashes($partner_income_str);
	if(is_array($partner_religion_arr) && $partner_religion_arr[0]!='' && $partner_religion_arr[0]!='All' && $partner_religion_arr[0]!='DM')
	{
		foreach($partner_religion_arr as $key=>$val)
		{
			$rel[$i] = explode("|X|",$val);
			$preligion[] = "'".$rel[$i][0]."'";
			$i++;
		}
		$partner_religion_str = implode(",",$preligion);
	}
	else if($partner_religion_str)
	{
		$rel = explode(",",$partner_religion_str);
		foreach($rel as $key=>$val)
                {
                        $rel[$i] = explode("|X|",$val);
                        $preligion[] = "'".$rel[$i][0]."'";
                        $i++;
                }
		$partner_religion_str = str_replace("\'","",implode(",",$preligion));
	}
	
	if($_POST["rsLIncome"] || $_POST["rsLIncome"]=='0' || $_POST["rsHIncome"] || $_POST["rsHIncome"]=='0' || $_POST["doLIncome"] || $_POST["doLIncome"]=='0' || $_POST["doHIncome"] || $_POST["doHIncome"]=='0')
	{
		if($rsLIncome || $rsLIncome =='0')
		{
			 $cur_sort_arr["minIR"]=intval($rsLIncome);
			 $rsIncomeMentioned=1;
		}
		if($rsHIncome || $rsHIncome=='0')
			$cur_sort_arr["maxIR"]=intval($rsHIncome);
		if($doLIncome || $doLIncome=='0')
		{
			$cur_sort_arr["minID"]=intval($doLIncome);
	                $doIncomeMentioned=1;
		}
		if($doHIncome || $doHIncome =='0')
			$cur_sort_arr["maxID"]=intval($doHIncome);

		if($rsIncomeMentioned && $doIncomeMentioned)
			$cur_sort_arr["currency"]='both';
		elseif($rsIncomeMentioned)
			$cur_sort_arr["currency"]='rupees';
		else
			$cur_sort_arr["currency"]='dollar';
		
	        if(!($rsIncomeMentioned && $doIncomeMentioned))
		{
			$arrMapped=get_mapped_values($cur_sort_arr,$db);
			if($rsIncomeMentioned)
			{
				$cur_sort_arr["minID"]=$arrMapped["minID"];
				$cur_sort_arr["maxID"]=$arrMapped["maxID"];
			}
			else
			{
				$cur_sort_arr["minIR"]=$arrMapped["minIR"];
				$cur_sort_arr["maxIR"]=$arrMapped["maxIR"];
			}
			$cur_sort_arr["currency"]='both';
		}
		$incomeArrStr=get_pincome_str($cur_sort_arr,$db,$return);

		if($rsLIncome!='' && $rsHIncome!=''){
			$doLIncome=$cur_sort_arr["minID"];
			$doHIncome=$cur_sort_arr["maxID"];
	        }
		else if($doLIncome!='' && $doHIncome!=''){
			$rsLIncome=$cur_sort_arr["minIR"];
			$rsHIncome=$cur_sort_arr["maxIR"];
		}
	}
	
	$jpartnerObj->setPARTNER_INCOME($incomeArrStr);
	$jpartnerObj->setLINCOME($rsLIncome);
	$jpartnerObj->setHINCOME($rsHIncome);
	$jpartnerObj->setLINCOME_DOL($doLIncome);
	$jpartnerObj->setHINCOME_DOL($doHIncome);

	$jpartnerObj->setPARTNER_MSTATUS($partner_mstatus_str);
	$jpartnerObj->setPARTNER_MTONGUE($partner_mtongue_str);
	$jpartnerObj->setPARTNER_RELIGION($partner_religion_str);
	$jpartnerObj->setPARTNER_CASTE($partner_caste_str);

	$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

	if($partner_mstatus_str)
		$mstatus_filter='Y';
	else
		$mstatus_filter='';
	
	if($partner_religion_str)
		$religion_filter='Y';
	else
		 $religion_filter='';

	 if($partner_caste_str)
	 	$caste_filter='Y';
	 else
	 	 $caste_filter='';
	
	if($profileid)
	{
		$sql="INSERT ignore INTO newjs.FILTERS(PROFILEID,MSTATUS,RELIGION,CASTE) VALUES ('$profileid','$mstatus_filter','$religion_filter','$caste_filter')";
		 mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed.Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}



	/**************Update partner details ends here*********/
	$spouseDetail = trim(strip_tags($spouseDetail));
	if($tieup_source=="ofl_prof")
	{
		$sql_spouse="UPDATE newjs.JPROFILE SET SPOUSE = '$spouseDetail',MOD_DT='$now' WHERE PROFILEID='$profileid' and  activatedKey=1";
		$res_spouse=mysql_query_decide($sql_spouse) or die(mysql_error_js());
	}
	else
	{
		/***********Screening of about DPP************/
		$curflag = $row_logged['SCREENING'];
		$oldSpouseDetail = $row_logged['SPOUSE'];
		if(trim($spouseDetail)=="")
			$curflag=setFlag("SPOUSE",$curflag);
		else
			$curflag=removeFlag("SPOUSE",$curflag);
		/************Ends here****************/
		$sql_spouse="UPDATE newjs.JPROFILE SET SPOUSE = '$spouseDetail',SCREENING='$curflag',MOD_DT='$now' WHERE PROFILEID='$profileid' and  activatedKey=1";
		$res_spouse=mysql_query_decide($sql_spouse) or die(mysql_error_js());
	}



	/* Tracking Query for the Reg Count */
		$sql = "UPDATE MIS.REG_COUNT SET PAGE5='Y' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	/* Ends Here */

	//	$profilechecksum=md5($profileid) . "i" . $profileid;
		if($gen=='F')
		{
			include("registration_page6.php");
			die;
		}
		else if($gen=='M')
		{
			if($isMobile){
				header("LOCATION:$SITE_URL/P/mainmenu.php");
			}
			else{
			if($skip_to_fto)
			$smarty->assign("Regd_REDIRECTURL","$SITE_URL/fto/offer?fromReferer=0");
			else
			$smarty->assign("Regd_REDIRECTURL","$SITE_URL/fto/offer?fromReferer=0&profilechecksum=$profilechecksum");
			$smarty->display("login_redirect.htm");
			}
		}
}
else
{

	/************Changes made for offline product*********/
	if($ajax)
	{
		if($Gender == 'M')
			$sql = "SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM newjs.SEARCH_MALE WHERE AGE BETWEEN '$Min_Age' AND '$Max_Age' AND HEIGHT BETWEEN '$Min_Height' AND '$Max_Height' ";
		elseif($Gender == 'F')
			$sql = "SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM newjs.SEARCH_FEMALE WHERE AGE BETWEEN '$Min_Age' AND '$Max_Age' AND HEIGHT BETWEEN '$Min_Height' AND '$Max_Height' ";

		if($partner_religion && $partner_religion!='DM')
		{
			$partner_religion_arr=explode(',',$partner_religion);
			foreach($partner_religion_arr as $k=>$v)
			{
				$religion=explode('|X|',$v);
				$religionArr[]=$religion[0];
			}
			$partner_religion=implode(",",$religionArr);
			$sql.=" AND RELIGION IN ($partner_religion) ";
		}
		if($partner_caste && $partner_caste!='DM')
		{
			$caste=explode(",",$partner_caste);
			$seCaste=get_all_caste($caste);
			if(is_array($seCaste))
			{
				$searchCaste=implode($seCaste,"','");
				$searchCaste="'" . $searchCaste . "'";
				$sql.=" AND CASTE IN ($searchCaste) ";
			}
		}
		if($partner_mtongue && $partner_mtongue!='DM')
		{
			$mton_arr=explode(",",$partner_mtongue);
                        foreach($mton_arr as $k=>$v)
                                if(!strstr($v,'#'))
                                        $mton_new[]=$v;
                        $partner_mtongue=implode(',',$mton_new);

			$sql.=" AND MTONGUE IN ($partner_mtongue) ";
		}
		if($partner_mstatus && $partner_mstatus!='DM')
		{
			$mstat_arr=explode(',',$partner_mstatus );
			$partner_mstatus="'".implode("','",$mstat_arr)."'";
			$sql.=" AND MSTATUS IN ($partner_mstatus) ";
		}
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$count= mysql_num_rows($res);
		echo "DPP matches : $count";
		die;
	}
	/********************Changes for offline product ends here***********/

	/*********Needed detail in previous dpp page*******
	$smarty->assign('percent',profile_percent_new($profileid));
	strlen($row_logged['YOURINFO'])>=100?$smarty->assign('completeProfile',true):$smarty->assign('completeProfile',false);
	**************Ends here*************/

	//Default values for  mother-tongue and caste are the caste and mother tongue of user
	if($row_logged['MTONGUE'])
	{
		$originalMtongue = $row_logged['MTONGUE'];
		//all hindi is mapped for default vals if mtongue is anything in all hindi
		$all_hindi_arr=array(10,19,33,7,28,13,41); //All hindi mother tongues
		if(in_array($row_logged['MTONGUE'],$all_hindi_arr))
			$row_logged['MTONGUE']="10,19,33,7,28,13,41";
		$DPP['Mtongue']=$row_logged['MTONGUE'];
		$smarty->assign('mapped_mton',$DPP['Mtongue']);	
	}
	if($row_logged['CASTE'])
		$DPP['Caste']=$row_logged['CASTE'];

	if($Gender=='M')		//If a girl is looking for groom
	{
		//Lowest limit is girl's age or '21'
		$DPP['Lage']=($row_logged['AGE']>29)?$row_logged['AGE']-2:(($row_logged['AGE']>26)?$row_logged['AGE']-1:(($row_logged['AGE']>22)?$row_logged['AGE']:21));
		$DPP['Hage']=($row_logged['AGE']>33)?$row_logged['AGE']+15:(($row_logged['AGE']==33)?47:(($row_logged['AGE']==32)?44:(($row_logged['AGE']==31)?42:$row_logged['AGE']+10)));
		//Lowest height limit is girl's height
		$DPP['Lheight']=$row_logged['HEIGHT'];
		//Highest height limit is girl's height +10 
		$DPP['Hheight']=$row_logged['HEIGHT']+10;

		/****************Needed in previous registration dpp page
		//Default value for Income is set of all incomes more than girl's income 
		if($row_logged['INCOME'])	
		{
			$DPP['Income']=$INCOME[$row_logged['INCOME']]['MORE'];
			// If girl's income is highest, default value is highest income
			if($DPP['Income']=='')
			$DPP['Income']="'14'";
		}
		**********************Ends here***************/
	}
	else			//If a boy is looking for bride
	{
		//Lowest limit for age  is '18' or boy's age-5, whichever is highest
		$DPP['Lage']=$row_logged['AGE']-5;
		if($DPP['Lage']<'18')
		$DPP['Lage']='18';
		//Highest limit is boy's age  
		$DPP['Hage']=$row_logged['AGE'];

		//Lowest height limit is boy's height-10
		$DPP['Lheight']=$row_logged['HEIGHT']-10;
		//Highest height limit is boy's height 
		$DPP['Hheight']=$row_logged['HEIGHT'];

	}

	/**********Values set if logged in user has max age/height*****/
	if($DPP['Hage']>=70)
		$DPP['Hage']=70;
	if($DPP['Hheight']>=37)
		$DPP['Hheight']=37;
	/************Ends here**********/

	if($DPP['Lage'])
		$smarty->assign("MIN_AGE",$DPP['Lage']); 				
	elseif($Gender=='M')
		$smarty->assign("MIN_AGE",'21');
	if($DPP['Hage']) 				
		$smarty->assign("MAX_AGE",$DPP['Hage']);		
	else
		$smarty->assign("MAX_AGE",'40');
	if($DPP['Hheight'])	
		$smarty->assign("maxheight",create_dd($DPP['Hheight'],"Height")); 	
	else
		$smarty->assign("maxheight",create_dd('37',"Height")); 	
	$smarty->assign("minheight",create_dd($DPP['Lheight'],"Height"));

	//Married option appears only in case for Muslim users
	if($row_logged['RELIGION']!='2')
		$not_muslim=1;
	if($Gender=='M')
	{	
			if($mas=='N')
				fill_MSgadget('Mstatus',"'N'",$not_muslim);
			else
				fill_MSgadget('Mstatus',$DPP['Mstatus'],$not_muslim);
	}
	else
		fill_MSgadget('Mstatus',$DPP['Mstatus'],$not_muslim); 			

	if($Gender=='M')
	{
		$sql = "SELECT DISTINCT REL_CASTE FROM newjs.CASTE_COMMUNITY WHERE PARENT_CASTE = '$row_logged[CASTE]'";
	        $res = mysql_query_decide($sql) or logError("error",$sql);
	        	
		if(mysql_num_rows($res)<1)
		{
			$def="'$row_logged[RELIGION]'";
		}
		else
		{	$abc="";		
			while($row = mysql_fetch_array($res))
			{
				$abc.=$row[REL_CASTE].",";
			}
			$abc=rtrim($abc,",");
			$sql1="SELECT DISTINCT PARENT FROM newjs.CASTE WHERE VALUE IN ($abc,$row_logged[CASTE])";
			$res1 = mysql_query_decide($sql1) or logError("error",$sql1);
			$def="'";
			while($row1 = mysql_fetch_assoc($res1))
			{
				$def.=$row1[PARENT]."','";
			}
			$def=rtrim(rtrim($def,"'"),",");
		}
		$DPP['Religion']="'".$def."'";
		$smarty->assign('mapped_rel',$def);
		if($row_logged['INCOME'])
		{
			$new_partner_income=get_income_sortby_new($row_logged['INCOME'],'','F');
			$new_partner_income=explode(",",$new_partner_income);
			$new_partner_income=implode("','",$new_partner_income);
			$DPP['Income']="'$new_partner_income'";
		}

		$DPP['Religion']=$row_logged['RELIGION'];
		$smarty->assign('mapped_rel',$row_logged['RELIGION']);
		fill_MSgadget_reg('Religion',$DPP['Religion'],1); 		
		//fill_MSgadget('Religion',"'$row_logged[CASTE]'","$def");			
		fill_MSgadget('Income',$DPP['Income'],1);
	}
	else
	{
		$DPP['Religion']=$row_logged['RELIGION'];
		$smarty->assign('mapped_rel',$row_logged['RELIGION']);
//		$smarty->assign('mapped_caste',$caste);
		fill_MSgadget_reg('Religion',$DPP['Religion'],1); 		
		fill_MSgadget_reg('Income',$DPP['Income'],1);
	}
	//For showing mapped castes based on community at top
	/**************Getting mapped castes************/
	$caste=$row_logged['CASTE'];
	$mtongue=$row_logged['MTONGUE'];
	$caste_community = $caste."-".$originalMtongue;
	$sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING WHERE CASTE_COMMUNITY = '$caste_community'";
	$res = mysql_query_decide($sql) or die(mysql_error()) or logError("error",$sql);
	$row = mysql_fetch_assoc($res);
	if($row['MAP'])
	{
		$caste_community_arr = @explode(",",$row['MAP']);
		for($i=0;$i<count($caste_community_arr);$i++)
		{
			$temp_caste_arr = @explode("-",$caste_community_arr[$i]);
			if(!@in_array($temp_caste_arr[0],$mapped_caste_arr))
			$mapped_caste_arr[] = $temp_caste_arr[0];
		}
		$mapped_caste="'".@implode("','",$mapped_caste_arr)."'";
		$smarty->assign('mapped_caste',$mapped_caste);	
	}
	else
	{
		$caste="'".$caste."'";
		$smarty->assign('mapped_caste',$caste);
	}
	/******************Mapped castes ends here*********/

	fill_MSgadget_reg('Mtongue',$originalMtongue); 		

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("tieup_source",$tieup_source);
	/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif","JSREGPAGE5URL"));
		/* Ends Here */
	$smarty->display("registration_pg5.htm");
}
// flush the buffer
if($zipIt && !$dont_zip_now)
ob_end_flush();
?>
