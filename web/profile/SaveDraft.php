<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt && !$dont_zip_now)
		ob_start("ob_gzhandler");
	//end of it

	include_once("connect.inc");
	include_once("contacts_functions.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	// contains array definitions
	//added by sriram.
	// connect to database
	$db=connect_db();
	$data=authenticated();
	$paid=0;
	if($data['SUBSCRIPTION'])
		$paid=1;
	$pid=$data['PROFILEID'];
	$username=$data['USERNAME'];
	
	if($pid)
	{
			$id=$DRAFT_ID;
			$name=urldecode($DRAFT_NAME);
			$mes=urldecode($DRAFT_MES);
			$d_status=$D_STATUS;
			if(!$d_status)
			{
//				$profilechecksum=$profilechecksum;
				$arr=explode("i",$profilechecksum);
				$contacted_pid=$arr[1];
				$sendersIn=$pid;
				$receiversIn=$contacted_pid;
				$contactResult=getResultSet("TYPE",$sendersIn,'',$receiversIn);
				if(is_array($contactResult))
					$d_status=$contactResult[0]["TYPE"];
				else
				{
					$contactResult=getResultSet("TYPE",$receiversIn,'',$sendersIn);
					if(is_array($contactResult))
	                                        $d_status=$contactResult[0]["TYPE"]; 
					else
						$d_status='';
				}
			}
			 $sql="select count(*) as count from DRAFTS where PROFILEID='$pid'";
			if($d_status=="D")
				$sql.=" AND DECLINE_MES!='N'";
			else
				$sql.=" AND DECLINE_MES='N'";
			 $res=mysql_query_decide($sql) or die("ERROR#Please try after some time");
			 $row=mysql_fetch_array($res);
			 $total_dra=$row[0];
			 if($total_dra>=5 && $id=="")
				die("ERROR#This operation is not allowed");
			if($id)
			{
				$sql="update DRAFTS set DRAFTNAME='".addslashes(stripslashes($name))."',MESSAGE='".addslashes(stripslashes($mes))."',CREATE_TIME=now() where DRAFTID=$id";
				mysql_query_decide($sql) or die("ERROR#Please try after some time");
			}
			else
			{
				if($d_status=="D")
					$sql="insert ignore into DRAFTS (DRAFTNAME,MESSAGE,PROFILEID,CREATE_TIME,DECLINE_MES) values('".addslashes(stripslashes($name))."','".addslashes(stripslashes($mes))."',$pid,now(),'Y')";
				else
					 $sql="insert ignore into DRAFTS (DRAFTNAME,MESSAGE,PROFILEID,CREATE_TIME) values('".addslashes(stripslashes($name))."','".addslashes(stripslashes($mes))."',$pid,now())";
				mysql_query_decide($sql) or die("ERROR#Please try after some time");
			}
			
			
	}
	else
		die("ERROR#You need to login first to save message");

	echo htmlspecialchars(stripslashes($name),ENT_QUOTES);die;
