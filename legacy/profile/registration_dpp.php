<?php
/*********************************************registration_dpp.php********************************
        Created By                      : Tanu Gupta
        Created on                      : 11-Apr-2009
        Description                     : This file is used for saving DPP detail at the time of registration
**********************************************************************************************************/
include_once("connect.inc");
include_once("search.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once("registration_functions.inc");
include_once("advance_search_functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");

$db=connect_db();
if($ajax)
{
        $data = authenticated();
        $profileid=$data['PROFILEID'];
}

//$data = authenticated($checksum);

$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;
//$profileid=$data['PROFILEID'];
$smarty->assign("profileid",$profileid);	

/****Tracking purpose********
$smarty->assign("TIEUP_SOURCE",$source);
$smarty->assign("GROUPNAME",$GROUPNAME);
$smarty->assign("SOURCE",$SOURCE);
$smarty->assign("groupname",$groupname);
$smarty->assign("reg_comp_frm_ggl",$reg1_comp_frm_ggl);
$smarty->assign("reg_comp_frm_ggl_nri",$reg1_comp_frm_ggl_nri);
$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));
*******Ends here***********/

if(!$myDb)
{
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
}
$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
if($jpartnerObj->getDPP()=='O' || $jpartnerObj->getDPP()=='S')
	$partner_exist=1;
else
	$partner_exist=0;

	//<<<<<<<<<<<<<<<<<<<<<<<<<<query to get religion and other fields>>>>>>>>>>>>>>> 
	$sql_logged="SELECT USERNAME,MTONGUE, RELIGION, GENDER, AGE, INCOME, HEIGHT,GENDER,CASTE,SCREENING, SPOUSE,YOURINFO FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
	$res_logged=mysql_query_decide($sql_logged) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_logged,"ShowErrTemplate");
	$row_logged=mysql_fetch_assoc($res_logged);
	
	$gen=$row_logged['GENDER'];

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

/****Tracking needed in previous page********/
$smarty->assign("TIEUP_SOURCE",$tieup_source);
function pixelcode($VAR)
{
        if($VAR)
        {
                $sql="SELECT PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
                $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
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
$pixelcode=str_replace('$USERNAME',$row_logged['USERNAME'],$pixelcode);
$smarty->assign("pixelcode",$pixelcode);
$smarty->assign("reg_comp_frm_ggl",$reg1_comp_frm_ggl);
$smarty->assign("reg_comp_frm_ggl_nri",$reg1_comp_frm_ggl_nri);
$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));
/*******Ends here***********/




	if(!$myDb)
	{
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
	}
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	if($jpartnerObj->getDPP()=='O' || $jpartnerObj->getDPP()=='S')
		$partner_exist=1;
	else
		$partner_exist=0;
if($submitReg || $submitReg_x)
{

		/*Profile score**********/
		include_once("sem.php"); // Function for the Calculating the Score
		$profile_score = profileScore($profileid);  // Findind the Score of the Profile

		$sql = "INSERT IGNORE INTO MIS.PROFILE_SCORE (`PROFILE_ID` , `SCORE` ) VALUES ( '$profileid' , '$profile_score')";
		$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Pleasetry after some time.",$sql,"ShowErrTemplate");
		$smarty->assign("profile_score",$profile_score);
		/**********Ends here**************/


                        $jpartnerObj->setPROFILEID($profileid);
                        $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
                        if($partner_exist)
                        {
                                //check_filter($jpartnerObj,$lage,$hage,$partner_mstatus_arr,$partner_caste_arr,"",$partner_mtongue_arr);
                                check_filter($jpartnerObj,$mysqlObj,$myDb,$profileid);
                        }
                        $jpartnerObj->setGENDER($Gender);
                        $jpartnerObj->setLAGE($Min_Age);
                        $jpartnerObj->setHAGE($Max_Age);
                        $jpartnerObj->setLHEIGHT($Min_Height);
                        $jpartnerObj->setHHEIGHT($Max_Height);
			$jpartnerObj->setDPP('R');		

			
			$partner_mstatus_str = partner_save_format($partner_mstatus_arr);
			$partner_mtongue_str = partner_save_format($partner_mtongue_arr);
			$partner_caste_str = partner_save_format($partner_caste_arr);

			if(is_array($partner_religion_arr) && $partner_religion_arr[0]!='' && $partner_religion_arr[0]!='All')
			{
				foreach($partner_religion_arr as $key=>$val)
				{
					$rel[$i] = explode("|X|",$val);
					$preligion[] = "'".$rel[$i][0]."'";
					$i++;
				}
				$partner_religion_str = implode(",",$preligion);
			}

			$jpartnerObj->setPARTNER_MSTATUS($partner_mstatus_str);
			$jpartnerObj->setPARTNER_MTONGUE($partner_mtongue_str);
			$jpartnerObj->setPARTNER_RELIGION($partner_religion_str);
			$jpartnerObj->setPARTNER_CASTE($partner_caste_str);

                        $jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);

			/***********Screening of about DPP************/
			$curflag = $row_logged['SCREENING'];
			$oldSpouseDetail = $row_logged['SPOUSE'];
			if(trim($spouseDetail)=="")
				$curflag=setFlag("SPOUSE",$curflag);
			else
				$curflag=removeFlag("SPOUSE",$curflag);
			/************Ends here****************/

			$spouseDetail = trim(strip_tags($spouseDetail));
			
		        $sql_spouse="UPDATE newjs.JPROFILE SET SPOUSE = '$spouseDetail',SCREENING='$curflag' WHERE PROFILEID=$profileid";
		        $res_spouse=mysql_query_decide($sql_spouse) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_spouse,"ShowErrTemplate");

			$profilechecksum=md5($profileid) . "i" . $profileid;

			$smarty->assign("Regd_REDIRECTURL","$SITE_URL/profile/viewprofile.php?checksum=".$checksum."&profilechecksum=".$profilechecksum."&from_registration=1"."#photohere");
			$smarty->display("login_redirect.htm");

			//header("Location:".$SITE_URL."/profile/viewprofile.php?checksum=".$checksum."&profilechecksum=".$profilechecksum."&from_registration=1"."#photohere");

}
else
{
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
                        $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $count= mysql_num_rows($res);
                        echo "DPP matches : $count";
                        die;
                }


		$smarty->assign('percent',profile_percent_new($profileid));
		strlen($row_logged['YOURINFO'])>=100?$smarty->assign('completeProfile',true):$smarty->assign('completeProfile',false);
		//Default values for  mother-tongue and caste are the caste n mother tongue of user
		if($row_logged['MTONGUE'])
		{
			$originalMtongue = $row_logged['MTONGUE'];
			//all hindi is mapped for default vals if mtongue is anything in all hindi
			$all_hindi_arr=array(10,19,33,7,28,13,41);
			if(in_array($row_logged['MTONGUE'],$all_hindi_arr))
				$row_logged['MTONGUE']="10,19,33,7,28,13,41";
			$DPP['Mtongue']=$row_logged['MTONGUE'];
			$smarty->assign('mapped_mton',$DPP['Mtongue']);	
		}
		if($row_logged['CASTE'])
			$DPP['Caste']=$row_logged['CASTE'];
		if($Gender=='M')	//<<<<<<<<<<If a girl is looking for groom>>>>>>>>>>>>>>>>
		{
			//Lowest limit is girl's age or '21'
			$DPP['Lage']=$row_logged['AGE'];
			if($DPP['Lage']<'21')
				$DPP['Lage']='21';
			//Highest limit is girl's age +5 
			$DPP['Hage']=$row_logged['AGE']+5;
			
			//Lowest height limit is girl's height
			$DPP['Lheight']=$row_logged['HEIGHT'];
			//Highest height limit is girl's height +10 
			$DPP['Hheight']=$row_logged['HEIGHT']+10;

			//Default value for Income is set of all incomes more than girl's income 
			if($row_logged['INCOME'])	
			{
				$DPP['Income']=$INCOME[$row_logged['INCOME']]['MORE'];
				// If girl's income is highest, default value is highest income
				if($DPP['Income']=='')
					$DPP['Income']="'14'";
			}
		}
		else			//<<<<<<<<<<If a boy is looking for bride>>>>>>>>>>>>>>>>
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
	if($DPP['Hage']>=70)
		$DPP['Hage']=70;
	if($DPP['Hheight']>=37)
		$DPP['Hheight']=37;

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
	fill_MSgadget('Mstatus',$DPP['Mstatus'],$not_muslim); 			

	$DPP['Religion']=$row_logged['RELIGION'];
	$smarty->assign('mapped_rel',$row_logged['RELIGION']);	
	fill_MSgadget_reg('Religion',$DPP['Religion'],1); 		
	$caste=$row_logged['CASTE'];
	$mtongue=$row_logged['MTONGUE'];

	$caste_community = $caste."-".$originalMtongue;
	$sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING WHERE CASTE_COMMUNITY = '$caste_community'";
	$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$mapped_caste=@implode(",",$mapped_caste_arr);
		$smarty->assign('mapped_caste',$mapped_caste);	
	}
	else
		$smarty->assign('mapped_caste',$caste);
	//Done for mapping Indian cities which are in alphanumeric form to numeric values
	/*if($DPP['Religion'] && ($DPP['Caste']=='' || $DPP['Caste']=="'DM'"))
		fill_MSgadget_reg('Religion',$DPP['Religion'],'1'); 			
	else	
		fill_MSgadget_reg('Religion',$DPP['Caste'],$DPP['Religion']); 			*/
	fill_MSgadget_reg('Mtongue',$originalMtongue); 		

$smarty->assign("CHECKSUM",$checksum);
$smarty->display("registration_dpp.htm");
}
if($zipIt && !$dont_zip_now)
                ob_end_flush();

?>
