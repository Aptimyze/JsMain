<?php

function search_contact_flow_tracking($profileid="",$stype="",$contact_id="",$matchalert_mis_variable="",$mysqlObj="")
{
	//include_once "connect.inc";
	//connect_slave();
	if($matchalert_mis_variable)
		$matchalert_mis_arr=explode("###",$matchalert_mis_variable);

	if(!$mysqlObj)
		$mysqlObj=new Mysql;

	$dt=date("Y-m-d");
	if($stype=="")
	{
		$ref_file=$_SERVER['HTTP_REFERER'];
		$save_stype=$_SERVER['REQUEST_URI'];
		$save_stype=$_SERVER['HTTP_REFERER'];
		$ref_file=$_SERVER['REQUEST_URI'];
		$sql_st="INSERT into MIS.stype_track VALUES('$save_stype','$ref_file')";
               mysql_query_decide($sql_st);
	}

	if($matchalert_mis_arr[0]!='')
	{
		$logic_used=$matchalert_mis_arr[0];
		$recomending=$matchalert_mis_arr[1];
		$is_user_active=$matchalert_mis_arr[2];

		//Sharding On Contacts done by Lavesh Rawat
		$sql_view_matchalert="INSERT IGNORE into MIS.MATCHALERT_CONTACT_BY_RECOMEND(PROFILEID,LOGIC_USED,RECOMEND,ENTRY_DT,IS_USER_ACTIVE,CONTACTID) VALUES('$profileid',$logic_used,'$recomending','$dt','$is_user_active',$contact_id)";
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	        $myDb=$mysqlObj->connect("$myDbName");
		$mysqlObj->executeQuery($sql_view_matchalert,$myDb);
	}
	if($stype)
	{
		if($stype=='VO' || $stype=='VN')
		{
			if($contact_id)
			{
		                $sql="insert ignore into MIS.SIMILLAR_CONTACT_COUNT(PROFILEID,SEARCH_TYPE,CONTACTID,DATE) VALUES ('$profileid','$stype',$contact_id,$dt)";
        		        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
                        $stype='V';
		}
		elseif($stype=='CO' || $stype=='CN' || $stype=='CN2')
		{
			if($contact_id)
			{
	                        $sql="insert ignore into MIS.SIMILLAR_CONTACT_COUNT(PROFILEID,SEARCH_TYPE,CONTACTID,DATE) VALUES ('$profileid','$stype',$contact_id,'$dt')";
        	                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
                        $stype='C';
		}
		$from_detailProfile = 'N';
		if($_POST['from_viewprofile']=='Y')
			$from_detailProfile='Y';
		//Sharding On Contacts done by Lavesh Rawat
                $sql="insert ignore into MIS.SEARCH_CONTACT_FLOW_TRACKING(PROFILEID,SEARCH_TYPE,DATE,FROM_DETAILPROFILE) VALUES ('$profileid','$stype','$dt','$from_detailProfile')";
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	        $myDb=$mysqlObj->connect("$myDbName");
		$mysqlObj->executeQuery($sql,$myDb);

		//added by lavesh
		if($contact_id)
		{
			$sql="insert ignore into MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW(PROFILEID,SEARCH_TYPE,CONTACTID,DATE,FROM_DETAILPROFILE) VALUES ('$profileid','$stype',$contact_id,'$dt','$from_detailProfile')";
			$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		        $myDb=$mysqlObj->connect("$myDbName");
			$mysqlObj->executeQuery($sql,$myDb);
		}
		//Sharding On Contacts done by Lavesh Rawat
	}
	
}


?>
