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
	include_once("hin_arrays.php");
	// contains screening information
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	// contains values and labels for dropdowns
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include_once("hits.php");
	include("manglik.php");
	include_once("contact.inc");

	//contains function profile_percent
	include_once('functions.inc');	
	include_once('ntimes_function.php');
	//include_once('contacts_functions.php');

	//added by sriram.
	$db_master = connect_db();
	$db_slave = connect_737_ro();
	$db_211=connect_211();
	// connect to database
	$db=connect_db();
	$data=authenticated($checksum);
	if(!$data && ($from_search || $from_album))
	{
		die("LOGIN");
	}
	if(!$data)
		die('Illegal request, please login first.');
	$profileid_conn=$data['PROFILEID'];
	if($profileid_conn)
	{
		$myDbName=getProfileDatabaseConnectionName($profileid_conn,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
	}


	//added by lavesh on 9 aug to reduce query on JPROFILE. jprofile_result array stores info of both the viewer and viewed person.Accordingly all variable and query on jprofile are replaced.
	
	include_once("reduce_jprofilequery.php");
	$arr=explode("i",$profilechecksum);
	if(md5($arr[1])!=$arr[0])
	{
		die('Illegal request');
	}
	else
		$profileid=$arr[1];

	limiting_jprofile_query($data["PROFILEID"],$profileid);

	$NUDGES=array();
	$n_source=$jprofile_result["viewed"]["SOURCE"];

	//If profile is coming from search make entry into view log trigger and view log
	if($from_search)
	{
		$privacy=$jprofile_result["viewer"]["PRIVACY"];
		if($privacy!='C' && $data["PROFILEID"]!=$profileid && $data['GENDER']!=$jprofile_result['viewed']['GENDER'])
		{
			//$SUFFIX=getsuffix($profileid);
			$sql_trig="REPLACE INTO VIEW_LOG_TRIGGER  (VIEWER,VIEWED,DATE) VALUES ('$data[PROFILEID]','$profileid',now())";
                         //mysql_query_optimizer($sql_trig);
                         mysql_query_decide($sql_trig,$db_211);
		}
		$sql="select count(*) as cnt from VIEW_LOG where VIEWER='$data[PROFILEID]' and VIEWED='$profileid'";
		$res=mysql_query_decide($sql,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
                $VIEWED_USER=$cnt=$row['cnt'];
		if($cnt<=0)
                {
                        $sql="insert ignore into VIEW_LOG(VIEWER,VIEWED,DATE,VIEWED_MMM) values ('$data[PROFILEID]','$profileid',now(),'Y')";
                        //mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        mysql_query_decide($sql,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                }
	}

	$contact_status_new=get_contact_status_dp($profileid,$data["PROFILEID"]);
	if($_GET)
	{
		
		foreach($_GET as $key=>$val)
		{
			$smarty->assign(strtoupper($key),$val);
			$smarty->assign($key,$val);
		}
	}
	if($from_search)
		$smarty->assign("SHOW_CROSS",1);

	//Assigning username back
        $smarty->assign("PROFILENAME",$jprofile_result['viewed']['USERNAME']);
	
	if($jprofile_result['viewer']['GENDER']==$jprofile_result['viewed']['GENDER'])
		$samegender=1;
	else if(check_spammer_filter($jprofile_result))
		$filter=1;
	if($type_of_action!="")
	{
		if($type_of_action=='Accept Interest')
		{
			$message="This member has moved to <a href='/profile/contacts_made_received.php?page=accept&filter=A' class='blink'>Accepted member</a> folder in 'My Contacts'";
			$con_headline="You have accepted interest sent by ".$jprofile_result['viewed']['USERNAME'];
			$smarty->assign("no_interest",1);
		}
		elseif($type_of_action=='Not Interested')
		{
			$message="This member has moved to <a href='/profile/contacts_made_received.php?page=decline&filter=M' class='blink'>Members I was not interested in</a> folder in 'My Contacts'";
			$con_headline="You have declined interest sent by ".$jprofile_result['viewed']['USERNAME'];
		}
		$jprofile_result['CON_DET_MESSAGE']=$message;
		$jprofile_result['CON_HEADLINE']=$con_headline;
		
	}
	//If contact is coming from coming from album page.
	if($from_album)
		$smarty->assign("FROM_ALBUM",1);
	
	//Used to show not interested option
	if($to_do=="decline")
		$smarty->assign("search_decline",1);
	$smarty->assign("FROM_LAYER_DP",1);
		
	//$smarty->assign("SHOW_CONTACT",1);
	express_page($jprofile_result,$data,$contact_status_new,$NUDGES,$spammer,$filter,$contact_limit_reached,$samegender);
	if($from_album)
	{
		
		$smarty->assign("FROM_PROF_ALB",1);
		$r_type=$contact_status_new[R_TYPE];
		$type=$contact_status_new[TYPE];
		if($r_type=="RI")
		{
			$smarty->assign("updatedStatus","I");
		}
		else if($type=="I")
		{
			if($to_do=="accept")
				$smarty->assign("updatedStatus","A");
			else
				$smarty->assign("updatedStatus","D");
		}
		$smarty->assign("EXPRESS_LAYER",$smarty->fetch("dp_express_interest_layer_fixed.htm"));
                $smarty->display("invoke_contact_engine.htm");
	}
	
?>		
