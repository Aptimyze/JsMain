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

	//contains function profile_percent
	include_once('functions.inc');	
	include_once('ntimes_function.php');
	//include_once('contacts_functions.php');

	//added by sriram.
	$db_master = connect_db();
	$db_slave = connect_737_ro();

	// connect to database
	$db=connect_db();
	$data=authenticated($checksum);
	if(!$data)
	{
		if($fromSearch)
				die("Login");
		$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
		$smarty->display("login_layer.htm");
		die;
	}
	if($fromSearch)
		$templateName="report_inv_search.htm";
	else
		$templateName="report_inv.htm";
		
	$profileid_conn=$data['PROFILEID'];

	$arr=explode("i",$profilechecksum);	
	if(md5($arr[1])!=$arr[0])
	{
		die('Illegal request');
	}
	else
		$profileid=$arr[1];
	if($Submit)
	{
		$submitter=$data['PROFILEID'];
		$submittee=$profileid;
		$mob='N';
		$phn='N';
		if($mobile=="true" || $callnow)
			$mob='Y';
		if($phone=="true" || $callnow)
			$phn='Y';
		$comments=htmlspecialchars($comments,ENT_QUOTES);

		$sql="replace into jsadmin.REPORT_INVALID_PHONE(SUBMITTER,SUBMITTEE,SUBMIT_DATE,PHONE,MOBILE,COMMENTS) values('$submitter','$submittee',now(),'$phn','$mob','$comments')";
		mysql_query_decide($sql);

		die('bye');
		
	}
	
	if($profileid_conn)
	{
		$myDbName=getProfileDatabaseConnectionName($profileid_conn,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
	}

	if($callnow){
		$smarty->assign("CALLNOW",$callnow);
	        $smarty->assign("PROFILECHECKSUM",$profilechecksum);
	        $smarty->display("$templateName");
		die;
	}

	//added by lavesh on 9 aug to reduce query on JPROFILE. jprofile_result array stores info of both the viewer and viewed person.Accordingly all variable and query on jprofile are replaced.
	
	include_once("reduce_jprofilequery.php");

	limiting_jprofile_query($data["PROFILEID"],$profileid);

	$NUDGES=array();
	$n_source=$jprofile_result["viewed"]["SOURCE"];
	$sender_id=$jprofile_result['viewer']['PROFILEID'];
        $receiver_id=$jprofile_result['viewer']['PROFILEID'];
        $sender_sub=$jprofile_result['viewer']['SUBSCRIPTION'];
        $receiver_sub=$jprofile_result['viewed']['SUBSCRIPTION'];

        if(strstr($sender_sub,"F,D"))
                $sen_sub="EV";
        if(strstr($sender_sub,"F"))
                $sen_sub="ER";
        if(strstr($receiver_sub,"F,D"))
                $rec_sub="EV";
        if(strstr($receiver_sub,"F"))
                $rec_sub="ER";
	$contact_status_new=get_contact_status_dp($profileid,$data["PROFILEID"]);
	$type=$contact_status_new['R_TYPE'];
        if($type=="")
                $type=$contact_status_new['TYPE'];
	//$type=$contact_status_new['R_TYPE'];

	if(($type=="" || $type=='RI' || $type=='I')&& $rec_sub=="EV")
		$allow=1;
	if(($type=='A'  && $rec_sub!="") || ($type=='A' && $sen_sub!=""))
		$allow=1;
	if(($type=='RA' && $rec_sub!="EV") || ($type=='RA' && $sen_sub!=""))
		$allow=1;
	if($type=='D' && $rec_sub!="EV")
		$allow=1;
	if($type=='RD' && $rec_sub!="EV")
		$allow=1;
	if($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $jprofile_result["viewed"]["PHONE_RES"]!="")
                                $res_phone=$jprofile_result["viewed"]["STD"]."-".$jprofile_result["viewed"]["PHONE_RES"];

                if($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $jprofile_result["viewed"]["PHONE_MOB"]!="")
                {
                        $mob_phone=$jprofile_result["viewed"]["PHONE_MOB"];
                }
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
        $smarty->assign("PHONE_NO",$res_phone);
        $smarty->assign("SHOW_MOBILE",$mob_phone);
	$smarty->display("$templateName");		
	
		
