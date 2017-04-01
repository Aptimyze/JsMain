<?php
	//Below Mail function added for data_changes release
	$fileName =  $_SERVER["SCRIPT_FILENAME"];
	$http_msg=print_r($_SERVER,true);
	mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);
	//to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

	header("Cache-Control: public");
                                                                                                                             
	//Modified by lavesh
	$file=$_SERVER["DOCUMENT_ROOT"];
	//ends here.
                                                                                                                             
        $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
                                                                                                                             
        $mtime = filemtime("$file/profile/search_clustering.php");

        $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        // if both the dates are same send not modified header and exit. No need to send further output
        if ($if_modified_since == $gmdate_mod)
        {
                header("HTTP/1.0 304 Not Modified");
                header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
                exit;
        }
        // tell the browser the last modified time so that next time when the same file is requested we get to know the modified time
        else
        {
                header("Last-Modified: $gmdate_mod");
                header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
        }

        include_once "connect.inc";
        include_once "arrays.php";
        include_once "search.inc";
	include_once("functions.inc");
	include_once("cmr.php");
	//include_once("contacts_functions.php");
	//mysql_close();
	$db=connect_737();
	//print_r($_GET);
	$data=authenticated($checksum);
	if($flag=="")
	$flag='I';
	if($type=="")
	$type="R";
	if(!$ordering)
		$ordering = "S";


	$yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));    // To get the time for previous day
	$back_30_days=date("Y-m-d",$yday);
	$b31=mktime(0,0,0,date("m"),date("d")-31,date("Y"));
	$back_31_days=date("Y-m-d",$b31);
	$b90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
	$back_90_days=date("Y-m-d",$b90);	
	$self_profileid=$data['PROFILEID'];
	
		//Global declaring $DATA_3D
	$DATA_3D=array();
	//List of profiles that to be shown
	$ALLOW_PROFILES=array();

	//Profiles containing all the data
	$ARC_SAX=array();

	//Profiles that are unviewed in TYPE='R' state
	$total_unviewed=array();

	//Profiles that are viewed in TYPE='R' state
	$total_viewed=array();

	//Nugdes profiles 
	$NUDGES=array();

	//Nudge count
	$nudge_cnt=0;
	
	//Unpaid contacts count
	$unpaid_cnt=0;

	//Paid contacts count
	$paid_cnt=0;


	$dontexecute=0;
	
	$decline=0;

	$contact='SENDER';
	
	///code MODIFIED  By Tapan Arora for Members Waiting for My Reply and Awaiting Response Archive
	$yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));    // To get the time for previous day
	$back_30_days=date("Y-m-d",$yday);
	$b31=mktime(0,0,0,date("m"),date("d")-31,date("Y"));
	$back_31_days=date("Y-m-d",$b31);
	
	if($type=="RC" && $flag=="I") 
	{
		
		getting_archive_profiles();

		
	}
	elseif($type=='R' && $flag=='I')
	{
		getting_awaiting_profiles();
		
	}
	elseif($type=='R' && $flag=='D')
	{
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$self_profileid;
		$typeIn="'D'";
		$contactResult=getResultSet("SENDER,TYPE,MSG_DEL,TIME,COUNT",'','',$receiversIn,'',$typeIn);
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$contact_pid=$contactResult[$key]["SENDER"];
				$ALLOW_PROFILES[]=$contact_pid;
				$ARC_SAX[$contact_pid]=$contactResult[$key];
			}
		}
		unset($contactResult);
		$sql="select PROFILEID as SENDER,STATUS as TYPE,MATCH_DATE as TIME from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and SHOW_ONLINE='Y' and STATUS IN('REJ','NREJ') ";
		$ress=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($rows=mysql_fetch_array($ress))
		{
			$ALLOW_PROFILES[]=$rows[0];
			$NUDGES[]=$rows[0];
			$ARC_SAX[$rows[0]]=$rows;//Storing d whole records corresponding to profile
		}

	}
	if($include_declines)
	{
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$self_profileid;
		$typeIn="'D'";
		$contactResult=getResultSet("SENDER,CONTACTID,MSG_DEL,TIME,COUNT",'','',$receiversIn,'',$typeIn,'');
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$decline++;
				if($type=='R' && $flag=='I')
				{
					$total_unviewed[]=$contactResult[$key]["SENDER"];
				}	

				if($type=="RC" && $flag=="I")
				{
					$ALLOW_PROFILES[]=$contactResult[$key]["SENDER"];
				}
				$ARC_SAX[$contact_pid]=$contactResult[$key];
			}
		}
		unset($contactResult);
	}
	if($type=='R' && $flag=="I")
	{
		if(is_array($total_viewed))
			$profile_str=implode("','",$total_viewed);
		if(is_array($total_unviewed))
			$profile_str.=implode("','",$total_unviewed);
		$profileid_str="'".$profile_str."'";
	}
	else
		$profileid_str="'".implode("','",$ALLOW_PROFILES)."'";
	//$profileid_str = awaiting_decline_profiles($self_profileid,$include_declines,$with_without_message,$flag,$type);

	$more_preference=0;
	$from_cluster = 1;
	$res_search = search_pending_query($profileid_str,$lage,$hage,$lheight,$hheight,$mtongue,$City_Res,$caste,$mstatus,$havephoto,$income,$occupation,$education,$relation,$diet,$manglik,$special_search,$from_cluster,"","",$MIN_AGE,$MAX_AGE,$MIN_HEIGHT,$MAX_HEIGHT);
	@mysql_data_seek($res_search,0);
	$nri = 0;
	$eclass_val_member = 0;
	while($row_search = mysql_fetch_array($res_search))
	{
		$profileid_arr_new[] = $row_search['PROFILEID'];

		if(!@in_array($row_search['INCOME'],$income_arr))
			$income_arr[] = $row_search['INCOME'];

		if(!@in_array($row_search['EDU_LEVEL_NEW'],$edu_level_new_arr))
			$edu_level_new_arr[] = $row_search['EDU_LEVEL_NEW'];

		if(!@in_array($row_search['OCCUPATION'],$occ_arr))
			$occ_arr[] = $row_search['OCCUPATION'];

		if(!@in_array($row_search['RELATION'],$rel_arr) && ($row_search['RELATION']=="1" || $row_search['RELATION']=="2" || $row_search['RELATION']=="3"))
			$rel_arr[] = $row_search['RELATION'];

		if(!@in_array($row_search['MANGLIK'],$manglik_arr) && ($row_search['MANGLIK']=="M" || $row_search['MANGLIK']=="N"))
			$manglik_arr[] = $row_search['MANGLIK'];

		if(!@in_array($row_search['DIET'],$diet_arr) && $row_search['DIET'] != "")
			$diet_arr[] = $row_search['DIET'];

		if($row_search['COUNTRY_BIRTH']=="51" && $row_search['COUNTRY_RES']!="51" && $row_search['COUNTRY_RES'] != "0")
			$nri++;
		if(strstr($row_search['SUBSCRIPTION'],"D"))
			$eclass_val_member = 1;
	}
	
	$profileid_arr_new_str = "'".@implode("','",$profileid_arr_new)."'";

//SELECT SQL_CACHE SQL_CALC_FOUND_ROWS J.PROFILEID AS PROFILEID FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN SEARCH_MALE ON J.PROFILEID=SEARCH_MALE.PROFILEID WHERE P.CASTE IS NULL AND J.GENDER='F'

	//recheck this query.
	//$sql_cosmo_check = "SELECT COUNT(*) AS COUNT FROM newjs.JPARTNER AS j LEFT JOIN newjs.PARTNER_CASTE as pc ON j.PARTNERID = pc.PARTNERID WHERE PROFILEID IN ($profileid_arr_new_str) AND pc.CASTE IS NULL";
	$sql_cosmo_check = "SELECT COUNT(*) AS COUNT FROM newjs.COSMO WHERE PROFILEID IN ($profileid_arr_new_str)";
	$res_cosmo_check = mysql_query_decide($sql_cosmo_check) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo_check,"ShowErrTemplate");
	$row_cosmo_check = mysql_fetch_array($res_cosmo_check);
	if($row_cosmo_check['COUNT'] > 0)
	{
		$more_preference=1;
		$smarty->assign("SHOW_COSMO",1);
	}

	//mysql_close();
	$db = connect_db();
	$sql_online_check = "SELECT COUNT(*) AS COUNT FROM userplane.users WHERE userID IN ($profileid_arr_new_str)";
	$res_online_check = mysql_query_decide($sql_online_check) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_online_check,"ShowErrTemplate");
	$row_online_check = mysql_fetch_array($res_online_check);
	if($row_online_check['COUNT'] > 0)
	{
		$more_preference=1;
		$smarty->assign("SHOW_MEM_ONLINE",1);
	}

	//$min_income = min($income_arr);
	//$max_income = max($income_arr);

	sort($income_arr);
	sort($edu_level_new_arr);
	sort($occ_arr);

//print_r($income_arr);die;
//print_r($edu_level_new_arr);die;
//print_r($occ_arr);die;
//print_r($rel_arr);
//print_r($manglik_arr);
//print_r($diet_arr);

	//mysql_close();
	$db=connect_737();

	$sql_inc = "SELECT SQL_CACHE LABEL, VALUE, OLD_VALUE FROM newjs.INCOME_CLUSTER WHERE VISIBLE = 'N' ORDER BY SORTBY DESC";
	$res_inc = mysql_query_decide($sql_inc) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_inc,"ShowErrTemplate");
	$j=0;
	while($row_inc = mysql_fetch_array($res_inc))
	{
		$income_cluster_inserted = 0;
		$temp_old_val_arr = explode(",",$row_inc['OLD_VALUE']);
		for($i=0; $i<count($income_arr);$i++)
		{
			if(@in_array($income_arr[$i],$temp_old_val_arr) && !@in_array($row_inc['LABEL'],$income_cluster))
			{
				if(strlen($row_inc['LABEL']) > 17)
					$inc_label = substr($row_inc['LABEL'],0,11)." +";
				else
					$inc_label = $row_inc['LABEL'];
				if(!@in_array($inc_label,$income_cluster))
				{
					$income_cluster[$j]['LABEL'] = $inc_label;
					$income_cluster[$j]['VALUE'] = $row_inc['VALUE'];
					$income_cluster_inserted = 1;
				}
			}
		}
		if($income_cluster_inserted)
			$j++;
		unset($temp_old_val_arr);
	}
	//@sort($income_cluster);

	$sql_occ = "SELECT SQL_CACHE LABEL, VALUE, OLD_VALUE FROM newjs.OCCUPATION_CLUSTER WHERE VISIBLE = 'N' ORDER BY SORTBY";
	$res_occ = mysql_query_decide($sql_occ) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_occ,"ShowErrTemplate");
	$j=0;
	while($row_occ = mysql_fetch_array($res_occ))
	{
		$occupation_cluster_inserted = 0;
		$temp_old_val_arr = explode(",",$row_occ['OLD_VALUE']);
		for($i=0; $i<count($occ_arr); $i++)
		{
			if(@in_array($occ_arr[$i],$temp_old_val_arr) && !@in_array($row_occ['LABEL'],$occupation_cluster))
			{
				$occupation_cluster[$j]['LABEL'] = $row_occ['LABEL'];
				$occupation_cluster[$j]['VALUE'] = $row_occ['VALUE'];
				$occupation_cluster_inserted = 1;
			}
		}
		if($occupation_cluster_inserted)
			$j++;
		unset($temp_old_val_arr);
	}
	@sort($occupation_cluster);

	$sql_edu = "SELECT SQL_CACHE LABEL, VALUE, OLD_VALUE FROM newjs.EDUCATION_LEVEL_NEW_CLUSTER WHERE VISIBLE = 'N' ORDER BY SORTBY ";
	$res_edu = mysql_query_decide($sql_edu) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_edu,"ShowErrTemplate");
	$j=0;
	while($row_edu = mysql_fetch_array($res_edu))
	{
		$education_cluster_inserted = 0;
		$temp_old_val_arr = explode(",",$row_edu['OLD_VALUE']);
		for($i=0; $i<count($edu_level_new_arr); $i++)
		{
			if(@in_array($edu_level_new_arr[$i],$temp_old_val_arr) && !@in_array($row_edu['LABEL'],$education_cluster))
			{
				$education_cluster[$j]['LABEL'] = $row_edu['LABEL'];
				$education_cluster[$j]['VALUE'] = $row_edu['VALUE'];
				$education_cluster_inserted = 1;
			}
		}
		if($education_cluster_inserted)
			$j++;
		unset($temp_old_val_arr);
	}
	@sort($education_cluster);

	if(@in_array("1",$rel_arr) && (@in_array("2",$rel_arr) || @in_array("3",$rel_arr)))
	{
		$more_preference=1;
		$smarty->assign("SHOW_RELATION",1);
	}
	if(@in_array("M",$manglik_arr) && @in_array("N",$manglik_arr))
	{
		$more_preference=1;
		$smarty->assign("SHOW_MANGLIK",1);
	}
	if(count($diet_arr) > 1)
	{
		if(in_array("V",$diet_arr))
			$smarty->assign("SHOW_VEG",1);
		if(in_array("N",$diet_arr))
			$smarty->assign("SHOW_NONVEG",1);
		if(in_array("J",$diet_arr))
			$smarty->assign("SHOW_JAIN",1);

		$more_preference=1;
		$smarty->assign("SHOW_DIET",1);
	}

	if($nri)
	{
		$more_preference=1;
		$smarty->assign("SHOW_NRI",1);
	}
	if($eclass_val_member)
	{
		$more_preference=1;
		$smarty->assign("SHOW_ECLASS",1);
	}

	$smarty->assign("income_cluster",$income_cluster);
	$smarty->assign("occupation_cluster",$occupation_cluster);
	$smarty->assign("education_cluster",$education_cluster);

	$income_str = "'".implode("','",$income_arr)."'";
	$edu_level_new_str = "'".implode("','",$edu_level_new_arr)."'";
	$occ_str = "'".implode("','",$occ_arr)."'";

	$sql_income = "SELECT SQL_CACHE ID, LABEL, OLD_VALUE FROM newjs.INCOME_CLUSTER WHERE VISIBLE = 'Y' ORDER BY SORTBY";
	$res_income = mysql_query_decide($sql_income) or logError("error",$sql_income);
	while($myrow_income = mysql_fetch_array($res_income))
	{
		$temp_old_val_arr = explode(",",$myrow_income['OLD_VALUE']);
		for($i=0;$i<count($income_arr);$i++)
		{
			if(@in_array($income_arr[$i],$temp_old_val_arr) && !strstr($income_label,$myrow_income['LABEL']))
			{
				$income_label.="'"."$myrow_income[LABEL]"."',";
				$income_value.="'"."$myrow_income[ID]"."',";//value is replaced by ID
			}
		}
		unset($temp_old_val_arr);
	}
	if(count(explode(",",$income_value)) > 2)
		$smarty->assign("SHOW_MORE_INCOME",1);

	$sql_occ = "SELECT SQL_CACHE ID,LABEL, OLD_VALUE FROM newjs.OCCUPATION_CLUSTER WHERE VISIBLE = 'Y' ORDER BY SORTBY";
	$res_occ = mysql_query_decide($sql_occ) or logError("error",$sql_occ);
	while($myrow_occ = mysql_fetch_array($res_occ))
	{
		$temp_old_val_arr = explode(",",$myrow_occ['OLD_VALUE']);
                for($i=0;$i<count($occ_arr);$i++)
                {
			if(@in_array($occ_arr[$i],$temp_old_val_arr) && !strstr($occ_label,$myrow_occ['LABEL']))
                        {
				$occ_label.="'"."$myrow_occ[LABEL]"."',";
				$occ_value.="'"."$myrow_occ[ID]"."',";//value is replaced by ID
			}
		}
		unset($temp_old_val_arr);
	}
	if(count(explode(",",$occ_value)) > 2)
		$smarty->assign("SHOW_MORE_OCC",1);

	$sql_edu = "SELECT SQL_CACHE ID,LABEL, OLD_VALUE FROM newjs.EDUCATION_LEVEL_NEW_CLUSTER WHERE VISIBLE = 'Y' ORDER BY SORTBY";
	$res_edu = mysql_query_decide($sql_edu) or logError("error",$sql_edu);
	while($myrow_edu = mysql_fetch_array($res_edu))
	{
		$temp_old_val_arr = explode(",",$myrow_edu['OLD_VALUE']);
                for($i=0;$i<count($edu_level_new_arr);$i++)
                {
			if(@in_array($edu_level_new_arr[$i],$temp_old_val_arr) && !strstr($edu_label,$myrow_edu['LABEL']))
                        {
				$edu_label.="'"."$myrow_edu[LABEL]"."',";
				$edu_value.="'"."$myrow_edu[ID]"."',";//value is replaced by ID
			}
		}
		unset($temp_old_val_arr);
	}
	if(count(explode(",",$edu_value)) > 2)
		$smarty->assign("SHOW_MORE_EDU",1);

	$edu_label=substr($edu_label,0,-1);
	$edu_value=substr($edu_value,0,-1);
	$occ_label=substr($occ_label,0,-1);
	$occ_value=substr($occ_value,0,-1);
	$income_label=substr($income_label,0,-1);
	$income_value=substr($income_value,0,-1);
	$edu_value="new Array($edu_value)";
	$edu_label="new Array($edu_label)";
	$occ_value="new Array($occ_value)";
	$occ_label="new Array($occ_label)";
	$income_value="new Array($income_value)";
	$income_label="new Array($income_label)";
	$smarty->assign("EDU_LABEL",$edu_label);
	$smarty->assign("OCC_VALUE",$occ_value);
	$smarty->assign("OCC_LABEL",$occ_label);
	$smarty->assign("INCOME_VALUE",$income_value);
	$smarty->assign("INCOME_LABEL",$income_label);
	$smarty->assign("EDU_VALUE",$edu_value);

//print_r($_GET);
	$smarty->assign("flag",$flag);
	$smarty->assign("VFLAG",$vflag);
	$smarty->assign("searched_lage",$lage);
	$smarty->assign("searched_hage",$hage);
	$smarty->assign("searched_lheight",$lheight);
	$smarty->assign("searched_hheight",$hheight);
	$smarty->assign("searched_City_Res",$City_Res);
	$smarty->assign("searched_mtongue",$mtongue);
	$smarty->assign("searched_caste",$caste);
	$smarty->assign("searched_mstatus",$mstatus);
	$smarty->assign("searched_havephoto",$havephoto);
	$smarty->assign("searched_include_declines",$include_declines);
	$smarty->assign("searched_income",$income);
	$smarty->assign("searched_education",$education);
	$smarty->assign("searched_occupation",$$occupation);
	$smarty->assign("searched_relation",$relation);
	$smarty->assign("searched_diet",$diet);
	$smarty->assign("searched_manglik",$manglik);
	$smarty->assign("searched_special_search",$special_search);
	$smarty->assign("searched_with_without_message",$with_without_message);
	$smarty->assign("searched_ordering",$ordering);
	$smarty->assign("MIN_AGE",$MIN_AGE);
	$smarty->assign("MAX_AGE",$MAX_AGE);
	$smarty->assign("MIN_HEIGHT",$MIN_HEIGHT);
	$smarty->assign("MAX_HEIGHT",$MAX_HEIGHT);
	$smarty->assign("total_awaiting",$total_awaiting);
	$smarty->assign("newsearch_clustering_type",$newsearch_clustering_type);
	$smarty->assign("type",$type);	

	if($more_preference)
		$smarty->assign("SHOW_MORE_PREFERENCE",1);

	$smarty->display("awaiting_decline_cluster.htm");
?>
