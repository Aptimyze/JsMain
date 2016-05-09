<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt && !$dont_zip_now)
		ob_start("ob_gzhandler");
	//end of it

	//Sharding+Combining
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	//Sharding+Combining

	// common include file
	include_once("connect.inc");
	// contains array definitions
	include_once("arrays.php");
	// contains screening information
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	// contains values and labels for dropdowns
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	//contains function profile_percent
	include_once('functions.inc');	
	include_once('ntimes_function.php');
	//include_once('contacts_functions.php');
	include_once("mobile_detect.php");

	//added by sriram.
	$db_master = connect_db();
	$db_slave = connect_737_ro();
	$db_211=connect_211();
	// connect to database
	$db=connect_db();
	$data=authenticated($checksum);
	if(!$data && $from_search)
	{
		die("LOGIN");
	}
	$start="FIRST TEST";
	$middle="MIDDLE TEST";
	$end="END TEST";
	
	include_once("reduce_jprofilequery.php");
        $arr=explode("i",$profilechecksum);
        if(md5($arr[1])!=$arr[0])
        {
                die('Illegal request');
        }
        else
                $profileid=$arr[1];
	//Check encrypt value is same as provided
	if(!(md5($start.$profileid.$middle.$data['PROFILEID'].$end)==$both_users))
		die;

        limiting_jprofile_query($data["PROFILEID"],$profileid);

        $NUDGES=array();
        $n_source=$jprofile_result["viewed"]["SOURCE"];

	if($show_con==1)
        {
                $my_prof=$jprofile_result['viewer']['PROFILEID'];
                $oth_prof=$jprofile_result['viewed']['PROFILEID'];
                $true_result=insert_into_alloted_contacts($my_prof,$oth_prof,$jprofile_result);
                //if 2 is the return value, then some intrusion is done.
                if($true_result==2)
                        die;
        }
	if($from_search)
		$smarty->assign("SHOW_CROSS",1);
	set_address($jprofile_result,"","direct_call");
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	if($isMobile){
		navigation($nav_type,"","");
		//Mobile/lanline number formatting to make it work for adding to phonebook
		$mob_num="+".str_replace("-","",$smarty->_tpl_vars['SHOW_MOBILE']);
		$landl_num="+".str_replace("-","",$smarty->_tpl_vars['PHONE_NO']);
		if(substr($landl_num,3,1) ==='0')
			$landl_num=substr($landl_num,0,3).substr($landl_num,4);
		$smarty->assign("MOB_NUM",$mob_num);
		$smarty->assign("LANDL_NUM",$landl_num);
		$smarty->assign("PROFILENAME",$jprofile_result['viewed']['USERNAME']);	
		$smarty->assign("NO_SHIFT_MES",1);
		$smarty->assign("SHOW_CONTACT",1);
		$smarty->assign("PAID",1);
		$header=$smarty->fetch("mobilejs/jsmb_header.html");
		$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
		$smarty->assign("HEADER",$header);
		$smarty->assign("FOOTER",$footer);
		$smarty->display("mobilejs/jsmb_express-interest.html");
	}
	else{
		if($fromNewSearch)
			$smarty->display("search_contact_layer.html");
		else if($from_search)
			$smarty->display("call_directly_tuple.htm");
		else
			$smarty->display("call_directly.htm");
	}
	if($true_result==1)
		sendViewContactMailer($my_prof, $oth_prof, $jprofile_result);
	
function insert_into_alloted_contacts($my_prof,$oth_prof, $jprofile_result)
{
	global $smarty;
        $date=date("Y-m-d G:i:s");
	$sql1 = "SELECT count(*) cnt FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER='$my_prof' AND VIEWED='$oth_prof' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
	$res1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row1 = mysql_fetch_array($res1);
	if(!$row1[0])
	{
		if(strstr($_SERVER["HTTP_REFERER"],'simprofile_search.php'))
			$source = "VSP";
		elseif(strstr($_SERVER["HTTP_REFERER"],'search.php') || strstr($_SERVER["HTTP_REFERER"],'/search/'))
			$source = "S";
		elseif(strstr($_SERVER["HTTP_REFERER"],'viewprofile.php'))
			$source = "VDP";
		elseif(strstr($_SERVER["HTTP_REFERER"],'view_similar_profile.php'))
			$source = "VSP";
		elseif(strstr($_SERVER["HTTP_REFERER"],'contacts_made_received.php'))
			$source = "C";
		$sql="insert ignore into jsadmin.VIEW_CONTACTS_LOG (`VIEWER`,`VIEWED`,`DATE`,`SOURCE`) values('$my_prof','$oth_prof','$date','".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_affected_rows_js()>0)
		{
			$sql="update jsadmin.CONTACTS_ALLOTED set VIEWED=VIEWED+1,LAST_VIEWED=now() where PROFILEID='$my_prof'";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$updated = 1;
		}
		else
			return 2;
	}
	else
		return 2;
	$sql="select ALLOTED-VIEWED as VIEWED,ALLOTED from jsadmin.CONTACTS_ALLOTED where PROFILEID='$my_prof'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_assoc($res))
		$smarty->assign("LEFT_ALLOTED",$row['VIEWED']);
		$smarty->assign("ALLOTED",$row[ALLOTED]);
        return $updated;
}
?>
