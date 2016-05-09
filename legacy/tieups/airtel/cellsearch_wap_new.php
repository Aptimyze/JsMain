<?php
/***************************************************************************************************************
* FILE NAME     : cellsearch3.php
* DESCRIPTION   : Generates an XML output according to a given query string.
* CREATION DATE : 31 March, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
include_once "../../profile/connect.inc";
include_once "../../profile/contact.inc";
include_once "cellsearch_wap_new.inc";
include_once("cellsearch_array.php");
//mail("vikas@jeevansathi.com","acl call",$_SERVER['REQUEST_URI']);		
$PAGELEN=10;
$db=connect_db(1);

$orig_keyword=$keyword;

/*<!-------	
//BRDS – When “Search Brides” option is selected by the user.
//BRDG – When “Search BrideGroom” option is selected by the user.
----!>*/
if($act=="BRDS" || $act=="BRDG" || $act=="SRCH" )
{
	//Commented by lavesh as now there is no keyword search.
	$wap='yes';
	$search_keyword=$act;
	
	// check for marital status criteria - WIB case
	/*if(strstr($keyword,"NEVER") && strstr($keyword,"MARRIED"))
	{
		$mstatus="NEVER MARRIED";
		$tempstr=explode("NEVER",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
		$tempstr=explode("MARRIED",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"never") && strstr($keyword,"married"))
	{
		$mstatus="NEVER MARRIED";
		$tempstr=explode("never",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
		$tempstr=explode("married",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"MARRIED"))
	{
		$mstatus="MARRIED";
		$tempstr=explode("MARRIED",$keyword);
		$keyword="";
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"married"))
	{
		$mstatus="MARRIED";
		$tempstr=explode("married",$keyword);
		$keyword="";
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"ANY"))
	{
		$tempstr=explode("ANY",$keyword);
		$keyword="";
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"ALL"))
	{
		$tempstr=explode("ALL",$keyword);
		$keyword="";
		$keyword=$tempstr[0].$tempstr[1];
	}*/

		
	//added by lavesh to log last save search of a MSISDN(cell)	
	if($next==1 || $previous==1)
	{
		$sql_1="SELECT  GENDER,AGE,RELIGION,MSTATUS,CASTE FROM newjs.WAP_SEARCHQUERY WHERE MSISDN=$MSISDN";
		$result=mysql_query_decide($sql_1) or queryDieLog(mysql_error_js(),$sql_1);
		$row=mysql_fetch_array($result);
		$act=$row["GENDER"];
		$age=$row["AGE"];
		$religion=$row["RELIGION"];
		$mstatus=$row["MSTATUS"];
		$caste=$row["CASTE"];
	}

	if($act=="BRDS" || $act=="BRDG")
	{
		if($next!=1 && $previous!=1)
		{
			$sql_1="REPLACE INTO newjs.WAP_SEARCHQUERY VALUES('','$MSISDN','$act','$age','$religion','$mstatus','$caste')";
			mysql_query_decide($sql_1) or queryDieLog(mysql_error_js(),$sql_1);
		}
	}

	if(strstr($mstatus,"earlier"))
	{
		$mstatus="MARRIED";
	}
	elseif(strstr($mstatus,"Never"))
	{
		$mstatus="NEVER MARRIED";
	}
	$sql[]="SELECT PROFILEID FROM ";

	if($act=='BRDG')
	{
		$sql[]=" newjs.SEARCH_MALE ";
	}
	else if($act=='BRDS')
	{
		$sql[]=" newjs.SEARCH_FEMALE ";
	}
	else if($gender=="M")
	{
		/*if($keyword)
		{
			$index="searchm";
			$sql[]=" newjs.SEARCH_MALE ";
		}
		else*/
			$sql[]=" newjs.SEARCH_MALE ";
	}
	else if($gender=="F")
	{
		/*if($keyword)
		{
			$index="searchf";
			$sql[]=" newjs.SEARCH_FEMALE ";
		}
		else*/
			$sql[]=" newjs.SEARCH_FEMALE ";
	}
	else
	{
		die("parameter missing");
	}

	if($age!='')
	{
		//Modified by lavesh
		/*if($age=='above 50')
		{
			$sql[]=" AGE > 50 ";
		}*/
		if($age=='35+')
		{
			$sql[]=" AGE > 35 ";
		}
		else
		{
			list($lage,$hage)=explode("-",$age);
			if($lage && $hage)
				$sql[]=" AGE BETWEEN '".$lage."' AND '".$hage."' ";
			else
				$sql[]=" AGE = '".$lage."' ";
		}
	}

	/*if($keyword)
	{
		$keyword_temp=trim(ereg_replace("[,?:!+;/\"\']",' ',$keyword));
		$keyword=ereg_replace("[ ]+",' +',$keyword_temp);

		///////////////////
		// sphinx starts //
		///////////////////

		require("/usr/local/sphinx/api/sphinxapi.php");

		$port = 3312;

		$cl = new SphinxClient ();
		$cl->SetServer ( "10.208.65.96", $port );
		$cl->SetMatchMode ( SPH_MATCH_ALL );
		$res = $cl->Query ( $keyword, $index );

		if ( $res===false )
			queryDieLog($cl->GetLastError(),"Fulltext Query failed");
		else
		{

			if ( is_array($res["matches"]) )
			{
				foreach ( $res["matches"] as $doc => $docinfo )
					$resultSet.=$doc.",";

				if($resultSet)
					$resultSet=substr($resultSet,0,-1);
			}
		}

		/////////////////
		// sphinx ends //
		/////////////////

	        //$sql[] = " (MATCH (KEYWORDS,SUBCASTE,EDUCATION) against ('+" . addslashes($keyword) . "' IN BOOLEAN MODE)) ";
		if($resultSet)
			$sql[]= " PROFILEID IN ($resultSet) ";
	}*/

	if($caste!="")
	{
		$open_field=explode("/",$caste);
		
		$sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field[0]."'";
		$res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
		if(mysql_num_rows($res_open)>0)
		{
			$row_open=mysql_fetch_array($res_open);
			if($row_open['TYPE']=='CASTE')
			{
				$CASTE=$row_open['VALUE'];
			}
			else if($row_open['TYPE']=='MTONGUE')
			{
				$MTONGUE=$row_open['VALUE'];
			}
		}

		$sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field[1]."'";
		$res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
		if(mysql_num_rows($res_open)>0)
		{
			$row_open=mysql_fetch_array($res_open);
			if($row_open['TYPE']=='CASTE')
			{
				$CASTE=$row_open['VALUE'];
			}
			else if($row_open['TYPE']=='MTONGUE')
			{
				$MTONGUE=$row_open['VALUE'];
			}
		}

		if(!is_array($CASTE) && $CASTE!="" && $CASTE!="All")
		{
			$tempcaste=$CASTE;
			unset($CASTE);
			$CASTE[0]=$tempcaste;
		}
										
		if(is_array($CASTE) && !in_array("All",$CASTE))
		{
			$seCaste=get_all_caste($CASTE);
			if(is_array($seCaste))
				$searchCaste="'" . implode($seCaste,"','") . "'";
		}
	}

	if($searchCaste=="" && $religion!="")
	{
		$sql_rel="SELECT VALUE FROM newjs.RELIGION WHERE LABEL='".$religion."'";
		$res_rel=mysql_query_decide($sql_rel) or queryDieLog(mysql_error_js(),$sql_rel);
		$row_rel=mysql_fetch_array($res_rel);
		$religion_val=$row_rel['VALUE'];

		$sql_cache="select SQL_CACHE VALUE from CASTE where PARENT='".$religion_val."' and ISALL='Y'";
		$res_cache=mysql_query_decide($sql_cache);
													
		$res_row=mysql_fetch_array($res_cache);
		$CASTE[0]=$res_row["VALUE"];
													
		$seCaste=get_all_caste($CASTE);
		if(is_array($seCaste))
			$searchCaste="'" . implode($seCaste,"','") . "'";
	}

	if($searchCaste!="")
	{
		if(strstr($searchCaste,","))
			$sql[]=" CASTE IN ($searchCaste) ";
		else
			$sql[]=" CASTE=$searchCaste ";
	}

	$mstatus=strtoupper($mstatus);
	if($mstatus!="ALL" && $mstatus!="ANY" && $mstatus!="")
	{
		if($mstatus=="NEVER MARRIED")
			$sql[]=" MSTATUS='N' ";
		else if($mstatus=="MARRIED")
			$sql[]=" MSTATUS IN ('W','D','S') ";
	}

	if($MTONGUE!="")
	{
		if(is_array($MTONGUE))
		{
			$temparr=$MTONGUE;
			unset($MTONGUE);
			$MTONGUE=implode($temparr,"','");
		}

		$sql[]=" MTONGUE ='$MTONGUE' ";
	}

	if($MSISDN)
	{
		$cell=format_cell_no($MSISDN);

                //Modified by lavesh
		$cell_profileid=wap_profileid($MSISDN);
		if($cell_profileid)
		{
			if(!$cell)
				$cell=$MSISDN;
			$sqlContact="SELECT PROFILEID,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$cell_profileid' and ACTIVATED='Y'";
		}
		else		
		//ends here
		{
			$sqlContact="SELECT PROFILEID,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' and ACTIVATED='Y'";
		}
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);

		if(mysql_num_rows($resContact)==1)
			$cellUserIsRegistered="Y";

		$rowContact=mysql_fetch_array($resContact);
		$cellProfileid=$rowContact['PROFILEID'];
		$cellGender=$rowContact['GENDER'];

		if(strstr($rowContact['SUBSCRIPTION'],"F"))
			$cellUserIsPaid="Y";
		//Sharding on CONTACTS done by Neha
		global $noOfActiveServers,$slave_activeServers;

		//print_r($slave_activeServers);
		$mysqlObj=new Mysql;

		if($cellProfileid)
		{
			$sql_sid="SELECT PROFILEID, SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID= '".$cellProfileid."'";
			$res=mysql_query($sql_sid) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row=mysql_fetch_array($res))
			{
				$serverId=$row["SERVERID"];
			}

			$sqlcontact="SELECT SENDER,RECEIVER FROM newjs.CONTACTS where SENDER='$cellProfileid' OR RECEIVER='$cellProfileid' ";
			$myDbName=$slave_activeServers[$serverId];
			$myDb=$mysqlObj->connect("$myDbName");
			$result=$mysqlObj->executeQuery($sqlcontact,$myDb) or die("1 ".mysql_error1($db2));
			while($rowContact=$mysqlObj->fetchArray($result))
			{
				if($rowContact['RECEIVER']==$cellProfileid)
					$donotDisplay[]=$rowContact['SENDER'];
				else
					$donotDisplay[]=$rowContact['RECEIVER'];
			}
			/*$sqlContact="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellProfileid."'";
			$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
			while($rowContact=mysql_fetch_array($resContact))
			{
				$donotDisplay[]=$rowContact['RECEIVER'];
			}
	print_r($donotDisplay);echo count($donotDisplay);
	die;
			$sqlContact="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellProfileid."'";
			$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
			while($rowContact=mysql_fetch_array($resContact))
			{
				$donotDisplay[]=$rowContact['SENDER'];
			}
			*/
		}
		$sqlContact="SELECT PROFILEID FROM newjs.SMS_CONTACTLIMIT WHERE COUNT>=3 AND ENTRY_DT=CURDATE()";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		while($rowContact=mysql_fetch_array($resContact))
		{
			$donotDisplay[]=$rowContact['PROFILEID'];
		}

		/******************************************************************************************
		DATA DESCRIPTION : Logging mobile number of users who have used mobile search but are
				 : un-registered.
		******************************************************************************************/
		if($cellUserIsRegistered!="Y")
		{
			$sqlUnreg="INSERT INTO newjs.SMS_UNREG_USERS VALUES('','".$cell."')";
			$resUnreg=mysql_query_decide($sqlUnreg,$db) or die(mysql_error_js());
		}
		/******************************************************************************************/

		if(count($donotDisplay)>1)
			$dontDisplay=implode("','",$donotDisplay);
		else
			$dontDisplay=$donotDisplay[0];

		if($dontDisplay)
			$sql[]=" PROFILEID NOT IN ('".$dontDisplay."') ";
	}

	$sql1=$sql[0].$sql[1];

	if(count($sql)>2)
		$sql1.=" WHERE ";

	for($sstemp=2;$sstemp<=count($sql)-1;$sstemp++)
	{
		$sql1.=$sql[$sstemp]." AND";
	}

	$sql1=rtrim($sql1,"AND");
	//added by lavesh
	$test_sql=explode("FROM",$sql1);
	if($test_sql[2]=='')
	{
		$test_sql="SELECT COUNT(*) as CNT FROM ".$test_sql[1];
		$result = mysql_query_decide($test_sql);
		$row=mysql_fetch_array($result);
		$total_rec=$row["CNT"];
	}
	else
	{
		$test_sql=$sql1;
		$result = mysql_query_decide($test_sql);	
		$total_rec = mysql_num_rows($result);
	}
	if($next==1 || $previous==1)
	{
		if($next)
			$lower=$upper+1;
		else
			$lower=$upper-23;
	}
	else
		$lower=1;
	$upper=$lower+23;

	if($total_rec)
	{
		if($upper>$total_rec)
			$upper=$total_rec;
		//echo $range=$lower."-".$upper." of ".$total_rec;
	}
	$lower_limit=$lower-1;
	//$sql1.=" LIMIT 24";
	$sql1.=" LIMIT $lower_limit,24";
	//echo $sql1;die;
	//Ends Here.
	unset($sql);

	// take connection on slave
	//mysql_close();
	$db=connect_737_lan();
	if($res=mysql_query_decide($sql1))		//in order to avoid "not a valid result resource"
	{
		// take connection on master
		//mysql_close();
		$db=connect_db(1);

		$res_cnt=mysql_num_rows($res);
		
		//if ($res_cnt==0 || $wib=="yes")
		//	mail("vikas@jeevansathi.com","SMS problem","original: $orig_keyword\nfinal: $keyword");

        /****************************************************
                DATA DESCRIPTION : Logging search...
    	*****************************************************/
		/*if($keyword)
		{
			if(strtolower($wib)=="yes")
				$source="WIB";
			elseif(strtolower($wap)=="yes")
				$source="WAP";
			else 
				$source="SMS";
				
			if($cellProfileid)
			{
				$sqlSearchLog="INSERT INTO newjs.SMS_SEARCHLOG VALUES ('','".$cellProfileid."','".$lage."','".$hage."','".$gender."','".$keyword."',now(),'".$res_cnt."','$cell','$source')";
			}
			else
			{
				$sqlSearchLog="INSERT INTO newjs.SMS_SEARCHLOG VALUES ('','0','".$lage."','".$hage."','".$gender."','".$keyword."',now(),'".$res_cnt."','$cell','$source')";
			}
			$resSearchLog=mysql_query_decide($sqlSearchLog) or queryDieLog(mysql_error_js(),$sqlSearchLog);
		}*/

		if($res_cnt>0)	//if there is no result satisfying the criterea
		{
			$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
			usort($results,"paidMemberSort");
			$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,'',$total_rec,$lower,$upper);
			echo $Ret;
		}
		else
		{
			echo "no results";
			$qs=$_SERVER['REQUEST_URI'];
			noResultLog($qs);
		}
	}
	else
	{
		queryDieLog(mysql_error_js(),$sql1);

		// take connection on master
		//mysql_close();
		$db=connect_db(1);
	}
}
//added by lavesh if new search is modified
elseif($act=='MODS')
{
	if(!$MSISDN)
		die("parameters missing");
														     
	$cell_profileid=wap_profileid($MSISDN,1);
	$sql="SELECT * FROM newjs.WAP_SEARCHQUERY WHERE MSISDN='$MSISDN'";
        $res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
        if($row=mysql_fetch_array($res))
	{
		$gender=$row["GENDER"];
		$age=$row["AGE"];
		$religion=$row["RELIGION"];
		$mstatus=$row["MSTATUS"];
		$caste=$row["CASTE"];
	}
	generlized_generateXML(array('SearchType','age','religion','mstatus','caste'),array($gender,$age,$religion,$mstatus,$caste));	
}

//added by lavesh if new search is clicked.
elseif($act=="NSCH")
{
	if(!$MSISDN)
		die("parameters missing");


	if(!$gender)
	{
		$cell_profileid=wap_profileid($MSISDN,1);
		$sql_1="SELECT GENDER FROM  newjs.JPROFILE WHERE PROFILEID='$cell_profileid'";
		$res_1=mysql_query_decide($sql_1) or queryDieLog(mysql_error_js(),$sql_1);
		if($row_1=mysql_fetch_array($res_1))
			$gender=$row_1["GENDER"];
		else
			die("not registered");
	}
	if($gender=='M')
		//echo "Search Brides";
		generlized_generateXML(array('SearchType'),array('BRDS'));
	else
		//echo "Search Groom";
		generlized_generateXML(array('SearchType'),array('BRDG'));
}

//added by lavesh for Partner Profile Match
elseif($act=="NEWM")
{
	if(!$MSISDN)
		die("parameters missing");
	$cell_profileid=wap_profileid($MSISDN,1);
	header("Location: ".$SITE_URL."/P/Partner_Profile_Match.php?wap=1&profileid=$cell_profileid&next=$next&previous=$previous&upper=$upper&search_keyword=NEWM");
}

//added by lavesh if save search is run.
elseif($act=="SAVE")
{
	if(!$MSISDN)
		die("parameters missing");

	$cell_profileid=wap_profileid($MSISDN,1);

	if($SaveName)
	{
		$sql="SELECT SEARCH_TYPE,ID FROM SEARCH_AGENT WHERE PROFILEID = '$cell_profileid' AND SEARCH_NAME='$SaveName'";
		$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$searchrow=mysql_fetch_array($result);
		$searchid=$searchrow['ID'];													     
		/*
		if($searchrow['SEARCH_TYPE']=='A')
			header("Location: ".$SITE_URL."/P/advance_search.php?fsubmit=Y&mysearchid=$searchid&wap=1&profileid=$cell_profileid&next=$next&previous=$previous&upper=$upper&search_keyword=SAVE");
		else
			header("Location: ".$SITE_URL."/P/search.php?mysearchid=$searchid&checksum=$checksum&wap=1&profileid=$cell_profileid&next=$next&previous=$previous&upper=$upper&search_keyword=SAVE");
		*/
		header("Location: ".$SITE_URL."/P/search.php?mysearchid=$searchid&wap=1&profileid=$cell_profileid&next=$next&previous=$previous&upper=$upper&search_keyword=SAVE");

	}
	else
	{
		//echo "list save";
		$name_arr[]="ListSave";
		$val_arr[]=1;
		$i=1;		
		$sql="select SEARCH_NAME from SEARCH_AGENT where PROFILEID='$cell_profileid'";
		$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

		if(mysql_num_rows($result)>0)
		{			
			while($row=mysql_fetch_array($result))	
			{
				$save_searches=$row["SEARCH_NAME"];	
				$name_arr[$i]="search".$i;
				$val_arr[$i]=$save_searches;
				$i++;
			}
		}
		else
		{
			//die("zero saved search");
			$name_arr[]="ZeroResults";
			$val_arr[]=1;
		}
		generlized_generateXML($name_arr,$val_arr);
	}
}

else if($act=="STTS") //---------------view all stats
{
	if(!$MSISDN)
		die("parameters missing");

	//Modified by lavesh
	/*$cell=get_cell_no($MSISDN);
	if(!$cell)
		die("not registered");*/

	$cellprofile=wap_profileid($MSISDN,1);
	//Ends Here

/*
	if($statusopt=="")
	{
		//Modified by lavesh
		//$sql="SELECT PROFILEID,LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB = '".$cell."' AND ACTIVATED='Y'";
		$sql="SELECT LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$cellprofile' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
		$row=mysql_fetch_array($res);
		//$cellprofile=$row['PROFILEID'];
		$last_login_date=$row['LAST_LOGIN_DT'];
		$cellpaid=$row['SUBSCRIPTION'];
		$sender_details=array("GENDER"=>$row['GENDER']);

		//find profiles waiting and contacted
		$sql="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellprofile."' AND TYPE='A' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$count_by_me=mysql_num_rows($res);

		$sql="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellprofile."' AND TYPE='I' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$count_by_them=mysql_num_rows($res);

		//function logCheckStatus logs how many people are using check status feature
		//Modified by lavesh
		if($cell)
			logCheckStatus($cell);
		else
			logCheckStatus($MSISDN);
		//Ends Here.

		echo $count_by_me." profiles shown interest.<BR>".$count_by_them." profiles waiting for you.";
	}
	else
*/
	{
                //Modified by lavesh
                //$sql="SELECT PROFILEID,LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB = '".$cell."' AND ACTIVATED='Y'";
                $sql="SELECT LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$cellprofile' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

		$cellUserIsRegistered="Y";

		//function logCheckStatus logs how many people are using check status feature
                //Modified by lavesh
                if($cell)
                        logCheckStatus($cell);
                else
                        logCheckStatus($MSISDN);
                //Ends Here.
		
		$row=mysql_fetch_array($res);

		//$cellprofile=$row['PROFILEID'];
		$last_login_date=$row['LAST_LOGIN_DT'];

		if(strstr($row['SUBSCRIPTION'],"F") || strstr($row['SUBSCRIPTION'],"V"))
			$cellUserIsPaid="Y";

		$cellpaid=$row['SUBSCRIPTION'];
		$sender_details=array("GENDER"=>$row['GENDER']);


		//Sharding on CONTACTS
		 global $noOfActiveServers,$slave_activeServers;

                //print_r($slave_activeServers);
                $mysqlObj=new Mysql;

                $sql_sid="SELECT PROFILEID, SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID= '".$cellprofile."'";
                $res=mysql_query($sql_sid) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
                {
                       $serverId=$row["SERVERID"];
                }

	

		//if($statusopt=="BY_ME" || $statusopt=="LISTW")
		if($statusopt=="LISTW")
		{
			$wap='yes';
			//added by lavesh to count total cnt
			//modified by neha for sharding of contacts
		 	$test_sql="SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE SENDER='".$cellprofile."' AND TYPE='A'";
			$myDbName=$slave_activeServers[$serverId];
	                $myDb=$mysqlObj->connect("$myDbName");
	                $result=$mysqlObj->executeQuery($test_sql,$myDb) or die("1 ".mysql_error1($db));
	                while($row=$mysqlObj->fetchArray($result))
	                {
				$total_rec=$row["CNT"];
			}

/*                        $result = mysql_query_decide($test_sql);
                        $row=mysql_fetch_array($result);
                        $total_rec=$row["CNT"];*/
			if($next==1 || $previous==1)
			{
				if($next)
					$lower=$upper+1;
				else
					$lower=$upper-23;
			}
			else
				$lower=1;
			$upper=$lower+23;

			if($total_rec)
			{
				if($upper>$total_rec)
					$upper=$total_rec;
			}
			$lower_limit=$lower-1;
			//ends here

			//Profiles you contacted and who have accepted you.

			
			$sql="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellprofile."' AND TYPE='A' ORDER BY TIME DESC LIMIT $lower_limit,24";
//			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
                        $res=$mysqlObj->executeQuery($test_sql,$myDb) or die("1 ".mysql_error1($db));
                        if($row=$mysqlObj->numRows($res))

//			if(mysql_num_rows($res))
			{
				$additional_parameters=array("SearchKeyword-STTS","statusopt-LISTW");
				$contact_details='list';
				$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
				usort($results,"paidMemberSort");
				$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,'',$total_rec,$lower,$upper,$additional_parameters);
				echo $Ret;
			}
			else
			{
				generlized_generateXML(array('SearchKeyword','statusopt','msg'),array('STTS','LISTW','no results'));
				//no results
			}
		}
		//else if($statusopt=="BY_THEM" || $statusopt=="LISTC")
		else if($statusopt=="LISTC")
		{
			$wap='yes';
			$additional_parameters=array("SearchKeyword-STTS","statusopt-LISTC");



			//added by lavesh to count total cnt
			//modified by Neha for sharding of CONTACTS
			$test_sql="SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE RECEIVER='".$cellprofile."' AND TYPE='I'";
			$myDbName=$slave_activeServers[$serverId];
                        $myDb=$mysqlObj->connect("$myDbName");
                        $result=$mysqlObj->executeQuery($test_sql,$myDb) or die("1 ".mysql_error1($db));
                        while($row=$mysqlObj->fetchArray($result))
                        {
                               $total_rec=$row["CNT"];
                        }


/*                        $result = mysql_query_decide($test_sql);
                        $row=mysql_fetch_array($result);
                        $total_rec=$row["CNT"];
*/
			if($next==1 || $previous==1)
			{
				if($next)
					$lower=$upper+1;
				else
					$lower=$upper-23;
			}
			else
				$lower=1;
			$upper=$lower+23;

			if($total_rec)
			{
				if($upper>$total_rec)
					$upper=$total_rec;
			}
			$lower_limit=$lower-1;
			//ends here

			//display profiles who contacted you.
			$sql="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellprofile."' AND TYPE='I' ORDER BY TIME DESC LIMIT $lower_limit,24";
			//$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$res=$mysqlObj->executeQuery($sql,$myDb) or die("1 ".mysql_error1($db));
                        if($mysqlObj->numRows($res))
//			if(mysql_num_rows($res))
			{
				$additional_parameters=array("SearchKeyword-STTS","statusopt-LISTC");
				$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
				usort($results,"paidMemberSort");
				$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,'',$total_rec,$lower,$upper,$additional_parameters);
				echo $Ret;
			}
			else
			{
				generlized_generateXML(array('SearchKeyword','statusopt','msg'),array('STTS','LISTC','no results'));
				//echo "no results";
			}
		}
	}
}
else if(($profileid || $username) && $act=="")//------------Contact
{
	if(!$MSISDN)					//check for
		die("parameter missing");		//missing parameters

	//Modified by lavesh
	/*$cell=get_cell_no($MSISDN);
	if(!$cell)
		die("not registered");*/

        $cellprofile=wap_profileid($MSISDN);

	if(!$cellprofile)
	{
		$my_msg="You Cannot contact as you are not a registered member.To register visit www.jeevansathi.com or call 0123456789";
                generlized_generateXML(array('SearchKeyword','msg'),array($SearchKeyword,$my_msg));
	}
	else
	/*$sql="SELECT PROFILEID FROM JPROFILE WHERE PHONE_MOB = '".$cell."' and ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
	if(mysql_num_rows($res)>0)*/

	{
		//$row=mysql_fetch_array($res);
		//$cellprofile=$row['PROFILEID'];
	//ends here

		$userDet=get_profile_details($cellprofile);
		if(strstr($userDet['SUBSCRIPTION'],"F"))
			$cellpaid="Y";
		else
			$cellpaid="N";
		$sender_details=array("GENDER"=>$userDet['GENDER']);


		/************************************************************************************************
		DATA DESCRIPTION: In case username is mentioned instead of profileid, we obtain the profileid
				: in the following steps and also check if user exists.
		************************************************************************************************/

		if($username)
		{
			$username=get_correct_username($username);
			if(!$username)
				die("IdDoesn'tExist");
		}

		if($username)
			$sqlGetId="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
		else if($profileid)
			$sqlGetId="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='".$profileid."' AND ACTIVATED='Y'";

		$resGetId=mysql_query_decide($sqlGetId) or queryDieLog(mysql_error_js(),$sqlGetId);

		if(mysql_num_rows($resGetId)>0)
		{
			$rowGetId=mysql_fetch_array($resGetId);
			$profileid=$rowGetId['PROFILEID'];
			$username=$rowGetId['USERNAME'];
		}
		else
		{
			die("IdDoesn'tExist");
		}

		// Obtain profile details.
		$receiverDet=get_profile_details($profileid);
		if(strstr($receiverDet['SUBSCRIPTION'],"F"))
        	        $receiverIsPaid="Y";
        	else
                	$receiverIsPaid="N";

		/************************************************************
		DATA DESCRIPTION: The function "get_contact_status" checks if there has been any previous contact between these two profileids and gets their contact status.
		************************************************************/
		$contact_status=get_contact_status($cellprofile,$profileid);		

		//if there has been no contact
		if($contact_status=="")
		{
			$tempvar="";
			$canContact=can_contact($cellprofile,$profileid,$sender_details,$tempvar);

			if($canContact)
			{
				/*******************************************
				DATA DESCRIPTION: The function "make_initial_contact" makes initial contact between these two profileids while the function "incrementContactLimit" increments this count so that a limit on the number of times a profile has been contacted thru SMS can be tracked
				*******************************************/
				// if they can contact each other
				make_initial_contact($cellprofile,$profileid,"","","","","","");
				incrementContactLimit($profileid);

				//logContacts logs all the contacts
				logContacts($cellprofile,$profileid,"I");

				/******************************************
				DATA DESCRIPTION: The message being sent to the receiver will contain all the details of user. This is done by calling the URL provided by ACL.
				*******************************************/

				//commnted and replaced by lavesh.

				/*
				$height=str_replace("&quot;",'"',$userDet['HEIGHT']);

				$responseMsg="Jeevansathi user - ". trimToNextSpace($userDet['NAME'].", ".$userDet['AGE'].", ".$height.", ".$userDet['CASTE'].", ".$userDet['MTONGUE'].", ".$userDet['EDUCATION'].", ".$userDet['OCCUPATION']." in ".$userDet['RESIDENCE'],0,60). " has contacted you. To accept SMS ACC " . $userDet['NAME'] . " to 676762";

				$responseMsg=urlencode($responseMsg);

				$fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=".urlencode($receiverDet['PHONE'])."&from=676762&text=".$responseMsg,"r");
		                fclose($fd);
				echo "done";
				*/

				//echo "contacted:".$username;
				$my_msg="Your Expression of interest has been successfully sent to Profile Id:".$username;
				generlized_generateXML(array('SearchKeyword','msg'),array('contact',$my_msg));
				//screen 5(a)
			}
			else
			{
				if($tempvar=="Gender is not opposite ")
				{
					$my_msg="You cannot contact the person from the same gender";
					generlized_generateXML(array('SearchKeyword','msg'),array('contact',$my_msg));
					//die("SameGender");
				}
				else
				{
					$my_msg="You cannot contact this profile as you do not pass the filters set by this profile";
					generlized_generateXML(array('SearchKeyword','msg'),array('contact',$my_msg));
					//die("filtered");
				}
			}
		}
		else if($contact_status=="I")
		{
			/****************************************************
			DATA DESCRIPTION: This block will be executed when there already has been an initial contact between the two users.
			****************************************************/
			$tempvar="";
			//$canContact=can_contact($cellprofile,$profileid,$sender_details,$tempvar);

			//if($canContact)
			//{
				make_initial_contact($cellprofile,$profileid,"","",1,"","","");
				incrementContactLimit($profileid);

				//logContacts logs all the contacts
				logContacts($cellprofile,$profileid,"I");

				/*********************************************
				DATA DESCRIPTION: The message that will be sent to the receiver is contructed here and again the URL given by ACL will be called.
				*********************************************/
				//Commented by lavesh.

				/*$height=str_replace("&quot;",'"',$userDet['HEIGHT']);
				$responseMsg="Jeevansathi user - ". trimToNextSpace($userDet['NAME'].", ".$userDet['AGE'].", ".$height.", ".$userDet['CASTE'].", ".$userDet['MTONGUE'].", ".$userDet['EDUCATION'].", ".$userDet['OCCUPATION']." in ".$userDet['RESIDENCE'],0,60) ." has contacted you. To accept SMS ACC " . $userDet['NAME'] . " to 676762";

				$responseMsg=urlencode($responseMsg);

				$fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=". urlencode($receiverDet['PHONE']) ."&from=676762&text=".$responseMsg,"r");
				fclose($fd);
				echo "done";*/
				
				$my_msg="Your Expression of interest has been successfully sent to Profile Id:".$username;
				generlized_generateXML(array('SearchKeyword','msg'),array('contact',$my_msg));
			//}
			/*else
			{
				if($tempvar=="Gender is not opposite ")
				{
					$my_msg="You cannot contact the person from the same gender";
					generlized_generateXML(array('type','msg'),array('contact',$my_msg));
					//die("SameGender");
				}
				else
				{
					$my_msg="You cannot contact this profile as you do not pass the filters set by this profile";
					generlized_generateXML(array('type','msg'),array('contact',$my_msg));
					//die("filtered");
				}
			}*/
		}
		//else if($contact_status=="RI") ------- changed by lavesh
		else if($contact_status=="RI" || $contact_status=="RD" || $contact_status=="C")
		{
			/*****************************************************
			DATA DESCRIPTION: This block will be executed when the user is sending response to another user who has already initiated a contact with him. In this case acceptance will be sent to the receiver.
			*****************************************************/

			send_response($cellprofile,$profileid,"A","","","");

			//logContacts logs all the contacts
			logContacts($cellprofile,$profileid,"A");

			/****************************************************
			DATA DESCRIPTION: If the cell phone user is paid, the response sent to him will consist of the receiver's username as well as his phone number. Otherwise only receiver's username will be sent to sender.
			****************************************************/

			//Commneted and replaced by lavesh
			if(!$SearchKeyword)
				$SearchKeyword='ContactsReceived';
                        if($cellpaid=="Y" || $paid=='true')
			{
				$acceptMessage="Your acceptance has been sent to this profile";
				$acceptMessage.="\nContact details \nProfile Id $username\nEmail-".$receiverDet['EMAIL']."\nPhone-".$receiverDet['PHONE'];
				$hidden=0;
			}
			else
			{
				$acceptMessage="Your acceptance has been sent to this profile";
				$acceptMessage.="\nContact details of this profile is available, become a paid member to view the details";
				$hidden=1;
			}
			generlized_generateXML(array('SearchKeyword','msg','DetailsHidden'),array($SearchKeyword,$acceptMessage,$hidden));
			/*
			if($cellpaid=="Y")
			{
				$acceptMessage="Acceptance sent to ".$receiverDet['NAME'];
				$acceptMessage.="\nDetails: Ph:".$receiverDet['PHONE'].";".$receiverDet['EMAIL'] . ". View similar profiles SMS SAME " . $receiverDet['NAME'] . " to 676762";
			}
			else
			{
				$acceptMessage="Acceptance sent to ".$receiverDet['NAME'] . " For contact details SMS INT. To view other similar profiles SMS SAME " . $receiverDet['NAME'] . " to 676762";
			}

			$fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=". urlencode($cell) ."&from=676762&text=".urlencode($acceptMessage),"r");
                        fclose($fd);
			*/

			/*****************************************************
			DATA DESCRIPTION: If the receiver is paid, he will get the message that his initial contact has been accepted by the sender and also sender's phone number.
			*****************************************************/

			/*
			if($receiverIsPaid=="Y")
			{
				$responseMsg="Contact accepted by ".$userDet['NAME']."\nDetails: Ph:".$userDet['PHONE'].";".$userDet['EMAIL'] . ". View similar profiles SMS SAME " . $userDet['NAME'] . " to 676762";
				$responseMsg=urlencode($responseMsg);

			        $fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=". urlencode($receiverDet['PHONE']) ."&from=676762&text=".$responseMsg,"r");
			        fclose($fd);
			}
			else
			{
				$responseMsg="Contact accepted by ".$userDet['NAME'] . ". For contact details SMS INT. To view other similar profiles SMS SAME " . $userDet['NAME'] . " to 676762";
				$responseMsg=urlencode($responseMsg);
 
				$fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=". urlencode($receiverDet['PHONE']) ."&from=676762&text=".$responseMsg,"r");
				fclose($fd);
			}
			echo $acceptMessage;
			echo "done";
			*/
		}
		else if($contact_status=="RA" || $contact_status=="A")
		{
			/****************************************************
			DATA DESCRIPTION: This block will be executed when there already has been acceptance between the two users. In this case they will be allowed to view details if they are paid or they will be asked to upgrade their membership if they are free members.
			****************************************************/

                        //Commneted and replaced by lavesh
			if($contact_status=="RA")
				$acceptMessage="You have already accepted the profile.";
			elseif($contact_status=="A")
				$acceptMessage="Contacted profile has already accepted you.";
                        if($cellpaid=="Y" || $paid=='true')
			{
				$hidden=0;
                                $acceptMessage.="\nProfile Id $username\nContact details \nEmail-".$receiverDet['EMAIL']."\nPhone-".$receiverDet['PHONE'];
			}
                        else
			{
                                $acceptMessage.="\nContact details of this profile is available, become a paid member to view the details";
				$hidden=1;
			}
			if(!$SearchKeyword)
				$SearchKeyword='ContactsReceived';
			generlized_generateXML(array('SearchKeyword','msg','DetailsHidden'),array($SearchKeyword,$acceptMessage,$hidden));

			/*if($cellpaid=="Y")
			{
				$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,PARENTS_CONTACT,SHOW_PARENTS_CONTACT from JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
				$row=mysql_fetch_array($result);
                                                                                                                             
				$contactmsg="The details of the user are:\n";
													     
				wf($row['SHOWPHONE_RES']=="Y" && $row['PHONE_RES']!="")
					$contactmsg.="Phone: ".$row['PHONE_RES']."\n";
                                                                                                                             
				if($row['SHOWPHONE_MOB']=="Y" && $row['PHONE_MOB']!="")
					$contactmsg.="Mobile: ".$row['PHONE_MOB']."\n";
                                                                                                                             
				if($row['EMAIL']!="")
					$contactmsg.=" E-Mail: ".$row['EMAIL'];

				echo $contactmsg;
			}
			else
			{
				echo "upgrade";
			}*/

		}
		else if($contact_status=="D" || $contact_status=="RC")
		{
			$my_msg="You cannot contact this profile as the person has declined any further communication";
			if(!$SearchKeyword)
				$SearchKeyword='contact';
			generlized_generateXML(array('SearchKeyword','msg'),array($SearchKeyword,$my_msg));
			//die("DeclinedAlready");
		}
		else
		{
			echo "error";
		}
	}
}

/*
elseif($act=="ACCDET")
{
	if(!$username)
		 die("parameter missing");

	$cellProfileid=wap_profileid($MSISDN,1);
	$sql="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$cell_profileid'";
	if(mysql_num_rows($res)>0)
	{
		$row=mysql_fetch_array($res);
		if(strstr($row['SUBSCRIPTION'],"F"))
			$cellUserIsPaid="Y";
		else
			$cellUserIsPaid="N";
	}
	if($cellUserIsPaid)
	{
		$sql="select PROFILEID FROM JPROFILE where USERNAME='$username'";
		$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($result);
		$profileid=$row["PROFILEID"];

		$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,PARENTS_CONTACT,SHOW_PARENTS_CONTACT from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($result);
		$contactmsg="Profileid: $username\n";
		$contactmsg="\n Contact Details \n";		

	        if($row['EMAIL']!="")
			$contactmsg.=" E-Mail: ".$row['EMAIL'];
		if($row['SHOWPHONE_RES']=="Y" && $row['PHONE_RES']!="")
			$contactmsg.="Phone: ".$row['PHONE_RES']."\n";
		if($row['SHOWPHONE_MOB']=="Y" && $row['PHONE_MOB']!="")
			$contactmsg.="Mobile: ".$row['PHONE_MOB']."\n";
	}
	else
		$contactmsg="Contact details of this profile is available, become a paid member to view the details";

	echo $contactmsg;
}
*/

/*
else if($act=="FIND") //------  Searching for profiles for chatting
{
	// check for marital status criteria - WIB case
	if(strstr($keyword,"NEVER") && strstr($keyword,"MARRIED"))
	{
		$mstatus="NEVER MARRIED";
		$tempstr=explode("NEVER",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
		$tempstr=explode("MARRIED",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"never") && strstr($keyword,"married"))
	{
		$mstatus="NEVER MARRIED";
        $tempstr=explode("never",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
		$tempstr=explode("married",$keyword);
		$keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"MARRIED"))
	{
		$mstatus="MARRIED";
		$tempstr=explode("MARRIED",$keyword);
        $keyword="";
        $keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"married"))
	{
		$mstatus="MARRIED";
        $tempstr=explode("married",$keyword);
        $keyword="";
        $keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"ANY"))
	{
		$tempstr=explode("ANY",$keyword);
        $keyword="";
        $keyword=$tempstr[0].$tempstr[1];
	}
	elseif(strstr($keyword,"ALL"))
	{
		$tempstr=explode("ALL",$keyword);
        $keyword="";
        $keyword=$tempstr[0].$tempstr[1];
	}
	
    $sql[]="SELECT PROFILEID FROM ";
                                                                                                                         
    if($gender=="M")
    {
		$index="searchm";
		$sql[]=" newjs.SEARCH_MALE ";
    }
    else if($gender=="F")
    {
		$index="searchf";
		$sql[]=" newjs.SEARCH_FEMALE ";
    }
    else
    {
		die("parameter missing");
    }
                                                                                                                         
    if($age!='')
    {
        list($lage,$hage)=explode("-",$age);
                                                                                                                     
        if($lage && $hage)
			$sql[]=" AGE BETWEEN '".$lage."' AND '".$hage."' ";
        else
			$sql[]=" AGE = '".$lage."' ";
    }
                                                                                                                             
	if($keyword)
	{
		$keyword_temp=trim(ereg_replace("[,?:!+;/\"\']",' ',$keyword));
		$keyword=ereg_replace("[ ]+",' +',$keyword_temp);
														    
		///////////////////
		// sphinx starts //
		///////////////////

		require("/usr/local/sphinx/api/sphinxapi.php");

		$port = 3312;

		$cl = new SphinxClient ();
		$cl->SetServer ( "10.208.65.96", $port );
		$cl->SetMatchMode ( SPH_MATCH_ALL );
		$res = $cl->Query ( $keyword, $index );

		if ( $res===false )
		{
			queryDieLog($cl->GetLastError(),"Fulltext Query failed");
		} 
		else
		{
			//print "Query '$q' retrieved $res[total] of $res[total_found] matches in $res[time] sec.\n";
			//	foreach ( $res["words"] as $word => $info )
			//		print "    '$word' found $info[hits] times in $info[docs] documents\n";

			if ( is_array($res["matches"]) )
			{
				foreach ( $res["matches"] as $doc => $docinfo )
					$resultSet.=$doc.",";

				if($resultSet)
					$resultSet=substr($resultSet,0,-1);

			}
		}

		/////////////////
		// sphinx ends //
		/////////////////

		if($resultSet)
			$sql[]= " PROFILEID IN ($resultSet) ";
	}
 
    if($searchCaste!="")
    {
        if(strstr($searchCaste,","))
			$sql[]=" CASTE IN ($searchCaste) ";
        else
			$sql[]=" CASTE=$searchCaste ";
    }
    
    if($MTONGUE!="")
    {
        if(is_array($MTONGUE))
        {
            $temparr=$MTONGUE;
            unset($MTONGUE);
            $MTONGUE=implode($temparr,"','");
        }
        $sql[]=" MTONGUE ='$MTONGUE' ";
    }
        
	if($MSISDN)
	{
		$cell=format_cell_no($MSISDN);
                                                                                                                             
                //Modified by lavesh
                $cell_profileid=wap_profileid($MSISDN);
                if($cell_profileid)
                        $sqlContact="SELECT PROFILEID,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$cell_profileid' and ACTIVATED='Y'";
			//Getting phone user's profileid, gender and subscription	
			//$sqlContact="SELECT PROFILEID,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
		//Ends Here.
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);

		if(mysql_num_rows($resContact)>0)
			$cellUserIsRegistered="Y";

		$rowContact=mysql_fetch_array($resContact);
		$cellProfileid=$rowContact['PROFILEID'];
		$cellGender=$rowContact['GENDER'];

		if(strstr($rowContact['SUBSCRIPTION'],"F") || strstr($rowContact['SUBSCRIPTION'],"V"))
			$cellUserIsPaid="Y";

		//Profiles contacted by phone user should not be displayed in search results.	
		$sqlContact="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellProfileid."'";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		while($rowContact=mysql_fetch_array($resContact))
		{
			$donotDisplay[]=$rowContact['RECEIVER'];
		}
                 
		//Profiles who have contacted phone user should also not be displayed in search results
		$sqlContact="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellProfileid."'";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		while($rowContact=mysql_fetch_array($resContact))
		{
			$donotDisplay[]=$rowContact['SENDER'];
		}

		$sqlContact="SELECT PROFILEID FROM newjs.SMS_CONTACTLIMIT WHERE COUNT>=3 AND ENTRY_DT=CURDATE()";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
        while($rowContact=mysql_fetch_array($resContact))
        {
			$donotDisplay[]=$rowContact['PROFILEID'];
        }

		//DATA DESCRIPTION : Logging mobile number of users who have used mobile search but are
		//  		   : un-registered.
		$sqlUnreg="INSERT INTO newjs.SMS_UNREG_USERS VALUES('','".$cell."')";
		$resUnreg=mysql_query_decide($sqlUnreg);

	}

	//All user's who have set BLOCKALL should not be displayed in search results
	$sqlBlock="SELECT PROFILEID FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKALL'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$donotDisplay[]=$rowBlock['PROFILEID'];
	}

	//All user's who have blocked only the phone user should not be displayed in search results
	$sqlBlock="SELECT PROFILEID,BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKONLY'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$arrayToMatch=explode(",",$rowBlock['BLOCKED_USERS']);

		if(in_array($cellProfileid,$arrayToMatch))
			$donotDisplay[]=$rowBlock['PROFILEID'];

		unset($arrayToMatch);
	}

	$sqlBlock="SELECT PROFILEID,BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKALLEXCEPT'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$arrayToMatch=explode(",",$rowBlock['BLOCKED_USERS']);

		if(!in_array($cellProfileid,$arrayToMatch))
			$donotDisplay[]=$rowBlock['PROFILEID'];

		unset($arrayToMatch);
	}


	if(count($donotDisplay)>1)
		$dontDisplay=implode("','",$donotDisplay);
	else
		$dontDisplay=$donotDisplay[0];

	if($dontDisplay)
		$sql[]=" PROFILEID NOT IN ('".$dontDisplay."') ";
														     
    $sql[]=" HAVE_PHONE_MOB = 'Y' ";
                                                                                                                         
    $sql1=$sql[0].$sql[1];
                                                                                                                             
	if(count($sql)>2)
	{
		if($cellUserIsPaid=="Y")
			$sql1.=" WHERE GET_SMS='Y' AND ";
		else
			$sql1.=" WHERE GET_SMS='Y' AND E_RISHTA='Y' AND ";
	}
                                                                                                                             
    for($sstemp=2;$sstemp<=count($sql)-1;$sstemp++)
    {
		$sql1.=$sql[$sstemp]." AND";
    }
                                                                                                                         
    $sql1=rtrim($sql1,"AND");
        
	$sql1.=" LIMIT 24";
 //       $sql1.=" ORDER BY E_RISHTA DESC,SORT_DT DESC LIMIT 24";
	unset($sql);

	@//mysql_close();
	$db=connect_737_lan();
	
	if($res=mysql_query_decide($sql1))             //in order to avoid "not a valid result resource"
    {
		@//mysql_close();
		$db=connect_db(1);
		$res_cnt=mysql_num_rows($res);

		//DATA DESCRIPTION : Logging search...
		if($keyword)
		{
			if(strtolower($wib)=="yes")
				$source="WIB";
			elseif(strtolower($wap)=="yes")
				$source="WAP";
			else 
				$source="SMS";
				
			if($cellProfileid)
			{
				$sqlSearchLog="INSERT INTO newjs.SMS_SEARCHLOG VALUES ('','".$cellProfileid."','".$lage."','".$hage."','".$gender."','".$keyword."',now(),'".$res_cnt."','$cell','$source')";
			}
			else
			{
				$sqlSearchLog="INSERT INTO newjs.SMS_SEARCHLOG VALUES ('','0','".$lage."','".$hage."','".$gender."','".$keyword."',now(),'".$res_cnt."','$cell','$source')";
			}
			$resSearchLog=mysql_query_decide($sqlSearchLog) or queryDieLog(mysql_error_js(),$sqlSearchLog);
		}

        if($res_cnt>0)      //if there is no result satisfying the criterea
        {
			$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
			usort($results,"paidMemberSort");
			$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid);
			echo $Ret;
        }
        else
        {
            echo "no results";
            $qs=$_SERVER['REQUEST_URI'];
            noResultLog($qs);
        }
    }
    else
    {
		queryDieLog(mysql_error_js(),$sql1);
    }
}
*/

/*
else if($act=="CHAT" && $MSISDN)
{
	if($username || $profileid)
	{
		//Modified by lavesh
		//$cell=get_cell_no($MSISDN);
		//if(!$cell)
			//die("not registered");

		$cellProfileid=wap_profileid($MSISDN,1);
		//Ends Here


		//Getting profileid,subscription of cell user
		//$sql="SELECT PROFILEID,SUBSCRIPTION,USERNAME,GENDER FROM JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
		$sql="SELECT PROFILEID,SUBSCRIPTION,USERNAME,GENDER FROM JPROFILE WHERE PROFILEID='$cellProfileid' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);

		//$cellProfileid=$row['PROFILEID'];
		$cellUsername=$row['USERNAME'];
		$cellGender=$row['GENDER'];

		if(strstr($row['SUBSCRIPTION'],'F') || strstr($row['SUBSCRIPTION'],'V'))
			$cellUserIsPaid='Y';

		if($username)
		{
			$username=get_correct_username($username);
			if(!$username)
				die("invalid user");
			
			$sql="SELECT PROFILEID,PHONE_MOB,SUBSCRIPTION,GENDER FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			$row=mysql_fetch_array($res);
			$triedUser=$row['PROFILEID'];
			$triedUserPhone=$row['PHONE_MOB'];
			$triedGender=$row['GENDER'];

			if(strstr($row['SUBSCRIPTION'],'F') || strstr($row['SUBSCRIPTION'],'V'))
				$contactedUserIsPaid='Y';
		}
		else
		{
			$sql="SELECT PHONE_MOB,SUBSCRIPTION,GENDER FROM JPROFILE WHERE PROFILEID='".$profileid."' and ACTIVATED='Y'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			if(mysql_num_rows($res)>0)
			{
				$row=mysql_fetch_array($res);
				$triedUser=$profileid;
				$triedUserPhone=$row['PHONE_MOB'];
				$triedGender=$row['GENDER'];
															     
				if(strstr($row['SUBSCRIPTION'],'F') || strstr($row['SUBSCRIPTION'],'V'))
					$contactedUserIsPaid='Y';
			}
			else 
				die("invalid user");
		}
		
		// check for same gender
		if($cellGender==$triedGender)
			die("SameGenderChat");

		$sql="SELECT * FROM SMS_BLOCK WHERE PROFILEID='".$triedUser."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);

		if($cellUserIsPaid=="Y" || $contactedUserIsPaid=="Y")
		{
			if(!$msg)
			{
				$sql="select ACCEPTED from CHAT_INVITATION where SENDER='$cellProfileid' and RECEIVER='$triedUser' and TIME > DATE_SUB(NOW(),INTERVAL 36 HOUR) order by TIME desc limit 1";
				$chatres=mysql_query_decide($sql);

				if(mysql_num_rows($chatres)>0)
				{
					$chatrow=mysql_fetch_array($chatres);
					if($chatrow["ACCEPTED"]=="Y")
						die("sendmsg");
					else
						die("wait");
				}
				else 
				{
					$sql="select count(*) from CHAT_INVITATION where SENDER='$cellProfileid' and RECEIVER='$triedUser' and TIME > DATE_SUB(NOW(),INTERVAL 60 HOUR) order by TIME desc limit 1";
					$chatres=mysql_query_decide($sql);
					$chatrow=mysql_fetch_row($chatres);
					
					if($chatrow[0]>0)
						die("expired");
					else 
					{
						$sql="select * from CHAT_INVITATION where SENDER='$triedUser' and RECEIVER='$cellProfileid' and TIME > DATE_SUB(NOW(),INTERVAL 36 HOUR) order by TIME desc limit 1";
						$chatres=mysql_query_decide($sql);
						
						if(mysql_num_rows($chatres))
						{
							$chatrow=mysql_fetch_array($chatres);
							if($chatrow["ACCEPTED"]=="Y")
								die("sendmsg");
							else 
							{
								$sql="update CHAT_INVITATION set ACCEPTED='Y',TIME=NOW() where ID='" . $chatrow["ID"] . "'";
								mysql_query_decide($sql);
								die("sendmsg");
							}
						}
					}
				}
			}
			else
			{
				$sql="select ACCEPTED from CHAT_INVITATION where SENDER='$cellProfileid' and RECEIVER='$triedUser' and TIME > DATE_SUB(NOW(),INTERVAL 36 HOUR) order by TIME desc limit 1";
				$inviteres=mysql_query_decide($sql);
				if(mysql_num_rows($inviteres))
				{
					$inviterow=mysql_fetch_array($inviteres);
					if($inviterow["ACCEPTED"]=="N")
						die("wait");
				}
				else
				{
					$sql="select * from CHAT_INVITATION where SENDER='$triedUser' and RECEIVER='$cellProfileid' and TIME > DATE_SUB(NOW(),INTERVAL 36 HOUR) order by TIME desc limit 1";
					$inviteres=mysql_query_decide($sql);
					
					if(!mysql_num_rows($inviteres))
					{
						$sql="select * from CHAT_INVITATION where SENDER='$triedUser' and RECEIVER='$cellProfileid' and TIME > DATE_SUB(NOW(),INTERVAL 60 HOUR) order by TIME desc limit 1";
						$inviteres=mysql_query_decide($sql);
						
						if(!mysql_num_rows($inviteres))
							die("invite");
						else 
							die("expired");
					}
					else
					{
						$inviterow=mysql_fetch_array($inviteres);
						$sql="update CHAT_INVITATION set ACCEPTED='Y',TIME=NOW() where ID='" . $inviterow["ID"] . "'";
						mysql_query_decide($sql);
					}
				}
			}

			// check for filters
			if(check_filters($cellProfileid,$triedUser))
			{
				$sql="select * from CHAT_INVITATION where SENDER='$triedUser' and RECEIVER='$cellProfileid' and TIME > DATE_SUB(NOW(),INTERVAL 36 HOUR) order by TIME desc limit 1";
				$inviteres=mysql_query_decide($sql);
				
				if(!mysql_num_rows($inviteres))
					die("filtered");
			}
			
			if($row['BLOCK_STATUS']=="BLOCKALL")
			{
				echo "blocked";
			}
			else if($row['BLOCK_STATUS']=="UNBLOCKALL")
			{
				echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
				if(!$msg)
				{
					acp_msg_receiver($triedUserPhone,$cellProfileid);
					update_sms_block($cellProfileid,$triedUser);
					update_contacts($cellProfileid,$triedUser);
					log_chat_invitation($cellProfileid,$triedUser);
				}
			}
			else if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
			{
				$blockedUserArr=explode(",",$row['BLOCKED_USERS']);

				if(in_array($cellProfileid,$blockedUserArr))
				{
					echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
					if(!$msg)
					{
						acp_msg_receiver($triedUserPhone,$cellProfileid);
						update_sms_block($cellProfileid,$triedUser);
						update_contacts($cellProfileid,$triedUser);
						log_chat_invitation($cellProfileid,$triedUser);
					}
				}
				else
				{
					echo "blocked";
				}
			}
			else if($row['BLOCK_STATUS']=="BLOCKONLY")
			{
				$blockedUserArr=explode(",",$row['BLOCKED_USERS']);

				if(in_array($cellProfileid,$blockedUserArr))
				{
					echo "blocked";
				}
				else
				{
					echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
					if(!$msg)
					{
						acp_msg_receiver($triedUserPhone,$cellProfileid);
						update_sms_block($cellProfileid,$triedUser);
						update_contacts($cellProfileid,$triedUser);
						log_chat_invitation($cellProfileid,$triedUser);
					}
				}
			}
			else
			{
				echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
				if(!$msg)
				{
					acp_msg_receiver($triedUserPhone,$cellProfileid);
					update_sms_block($cellProfileid,$triedUser);
					update_contacts($cellProfileid,$triedUser);
					log_chat_invitation($cellProfileid,$triedUser);
				}
			}
		}
		else
		{
			echo "pay";
		}
	}
	else
	{
		echo "error";
	}
}
*/

/*else if($act=="BLOCKALL" && $MSISDN) 
{
	$cell=get_cell_no($MSISDN);
        if(!$cell)
                die("not registered");
	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];

	$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES('".$cellprofile."','BLOCKALL','')";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

	echo "done";
}
*/

/*
else if($act=="UNBLOCKALL" && $MSISDN)  //----- UN-Blocking all users
{
	$cell=get_cell_no($MSISDN);
        if(!$cell)
		die("not registered");
	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];

	$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellprofile."','UNBLOCKALL','')";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	echo "done";
}
*/

/*
else if($act=="BLOCKALLEXCEPT" && $MSISDN) //-----block all user except specified in username passed and earlied submited.
{
        $cell=get_cell_no($MSISDN);
        if(!$cell)
                die("not registered");

	if($username)
	{
		$username=get_correct_username($username);
		if(!$username)
			die("no user");

		$sql="SELECT PROFILEID,USERNAME FROM JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$cell_profileid=$row['PROFILEID'];
		$cell_username=$row['USERNAME'];

		$sql="SELECT PROFILEID FROM JPROFILE WHERE USERNAME='".$username."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$block_user=$row['PROFILEID'];

		if($block_user!='')
		{
			$sql="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$row=mysql_fetch_array($res);

			if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
			{
				$profiles=$row['BLOCKED_USERS'].",".$block_user;
			}
			else
			{
				$profiles=$block_user;
			}

			$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cell_profileid."','BLOCKALLEXCEPT','".$profiles."')";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			echo "done";
		}
	}
	else
	{
		die("error");
	}
}
*/

/*
else if($act=="BLOCKONLY" && $MSISDN) // ------ Blocks a Particular user.
{
	if($username)
	{
		$cell=get_cell_no($MSISDN);
		if(!$cell)
			die("not registered");

		$sql="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
	        $res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
        	$row=mysql_fetch_array($res);
	        $cell_profileid=$row['PROFILEID'];
		$cell_username=$row['USERNAME'];

		$username=get_correct_username($username);
		if(!$username)
			die("no user");
			
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$block_user=$row['PROFILEID'];

		if($block_user!='')
		{
			$sql="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$row=mysql_fetch_array($res);

			if($row['BLOCK_STATUS']=="BLOCKONLY")
			{
				$profiles=$row['BLOCKED_USERS'].",".$block_user;
			}
			else
			{
				$profiles=$block_user;
			}
                                                                                                                             
			$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cell_profileid."','BLOCKONLY','".$profiles."')";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			echo "done";
		}
		else if($block_user=="")
		{
			echo "no user";
		}
	}
	else
	{
		die("error");
	}
}
*/

/*
else if($act=="VIEWBLOCK" && $MSISDN) 
{
	//DATA DESCRIPTION : The steps given below are for the case when 
	//	 	   : user tries to view his blocked users.

        //Modified by lavesh
        $cell=get_cell_no($MSISDN);
        if(!$cell)
                die("not registered");

	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];
        //Ends Here

	$sql="SELECT BLOCK_STATUS, BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$blocked_users_arr=explode(",",$row['BLOCKED_USERS']);

	if(count($blocked_users_arr)>1)
		$blocked_users=implode("','",$blocked_users_arr);	//Profile IDs of users who have been blocked
	else
		$blocked_users=$blocked_users_arr[0];


	//We need to find the username of all the users who have been blocked. 
	//This is accomplished in the steps below.

	$sqlUname="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN ('".$blocked_users."')";
	$resUname=mysql_query_decide($sqlUname) or queryDieLog(mysql_error_js(),$sqlUname);
	while($rowUname=mysql_fetch_array($resUname))
	{
		$blockedUnameArr[]=$rowUname['USERNAME'];
	}

	if(count($blockedUnameArr)>1)
		$blockedUname=implode(",",$blockedUnameArr);
	else
		$blockedUname=$blockedUnameArr[0];

	if($row['BLOCK_STATUS']=="BLOCKALL")
	{
		echo "User has blocked all other users.";
	}
	else if($row['BLOCK_STATUS']=="UNBLOCKALL")
	{
		echo "User has un-blocked all other users.";
	}
	else if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
	{
		echo "User has blocked all users except- ".$blockedUname;
	}
	else if($row['BLOCK_STATUS']=="BLOCKONLY")
	{
		echo "User has blocked only- ".$blockedUname;
	}
	else
	{
		echo "No block status available";
	}
}
*/
/*
else if($act=="MAT")
{
	//mysql_close($db);
	$db=connect_slave();

        $cell=get_cell_no($MSISDN);
        if(!$cell)
                die("not registered");

	$sql="SELECT SA.*,JP.SUBSCRIPTION FROM alerts.SMS_ALERTS AS SA,newjs.JPROFILE AS JP WHERE JP.PHONE_MOB='".$cell."' AND SA.RECEIVER=JP.PROFILEID AND JP.ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

	if(mysql_num_rows($res)>0)
	{
		$cellUserIsRegistered="Y";
		$row=mysql_fetch_array($res);
		$matches="";
		$user="USER";
		for($tmpcnt=1;$tmpcnt<=20;$tmpcnt++)
		{
			$usr=$user.$tmpcnt;
			$matches.="'".$row[$usr]."',";
		}

		if(strstr($row['SUBSCRIPTION'],"F") || strstr($row['SUBSCRIPTION'],"V"))
			$cellUserIsPaid="Y";

		$results=displayresult($matches,0,"cellsearch.php","","",1,"","","");
		usort($results,"paidMemberSort");
		$Ret=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid);
		echo $Ret;
	}
	else
		echo "no results";

	//mysql_close($db);
	$db=connect_db(1);
}
*/

else if($act=="SAME" && $MSISDN) //---------
{
        global $wap;
        $wap='yes';

    /***************************************************************************************************
            DATA DESCRIPTION : The steps given below are for Similar Profile Search
    ***************************************************************************************************/

	if(!$username && !$profileid)
		die("parameter missing");                                                                                                                       
	if($username)
        {
        	$username=get_correct_username($username);
	        if(!$username)
        	    die("invalid user");
    	}
	//Modified by lavesh
	/*
        $cell=format_cell_no($MSISDN);
	$sql="SELECT PROFILEID,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."' AND ACTIVATED='Y'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	*/
        $sender_id=wap_profileid($MSISDN);
        //Ends Here
	if($sender_id)
	{	
		$sql="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$sender_id'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		if(mysql_num_rows($res)>0)
		{
			$row=mysql_fetch_array($res);
			//$sender_id=$row['PROFILEID'];
			
			if(strstr($row['SUBSCRIPTION'],"F"))
				$cellUserIsPaid="Y";
			else
				$cellUserIsPaid="N";

			$cellUserIsRegistered="Y";
		}
		else
		{
			$cellUserIsRegistered="N";
			$cellUserIsPaid="N";
		}
	}
	else
	{
		$cellUserIsRegistered="N";
		$cellUserIsPaid="N";
	}

	if($username)
	{
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$profileid=$row['PROFILEID'];
	}

	//Sharding on CONTACTS done by Neha Verma
	if($sender_id)
		$contactResult=getResultSet("SENDER","",$sender_id,$profileid);
	if(is_array($contactResult))
        {
	        foreach($contactResult as $key=>$value)
                {
	                $senders[]=$contactResult[$key]["SENDER"];
                }
         }
         unset($contactResult);
	//End
	//These steps find all the users who have contacted the given profileid
	/*$sql="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER = '".$profileid."' AND SENDER <> '".$sender_id."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$senders[]="'".$row['SENDER']."'";
	}
*/
	if(count($senders)>1)
	{
		$sender_list=implode(",",$senders);
	}
	else if(count($senders)==1)
	{
		$sender_list=$senders[0];
	}
	else
	{
		$sender_list="''";
	}

	//Sharding on CONTACTSdone by Neha Verma
	if($sender_id)
	        $contactResult=getResultSet("RECEIVER",$sender_id);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                {
                        $previous_rec_arr[]=$contactResult[$key]["RECEIVER"];
                }
         }
         unset($contactResult);
/*
	// List of all RECEIVERS whom the cell user has already contacted
	$sql="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$sender_id."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$previous_rec_arr[]=$row['RECEIVER'];
	}
*/
	if(count($previous_rec_arr)>1)
		$previous_rec=implode("','",$previous_rec_arr);
	else if(count($previous_rec_arr)==1)
		$previous_rec=$previous_rec_arr[0];
	else
		$previous_rec="''";

	$previous_rec.="','".$profileid;


	if($sender_list!="''")
	{
		//mysql_close($db);
		//$db=connect_slave();
                $db2=connect_db4();

		$test_sql="SELECT COUNT(*) as CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.") AND RECEIVER NOT IN ('".$previous_rec."') GROUP BY RECEIVER";
		$result = mysql_query_decide($test_sql);
		$row=mysql_fetch_array($result);
		$total_rec=$row["CNT"];

		if($next==1 || $previous==1)
		{
			if($next)
				$lower=$upper+1;
			else
				$lower=$upper-23;
		}
		else
			$lower=1;

		$upper=$lower+23;

		if($total_rec)
		{
			if($upper>$total_rec)
				$upper=$total_rec;
			//echo $range=$lower."-".$upper." of ".$total_rec;
		}
		$lower_limit=$lower-1;

		$sql="SELECT RECEIVER,SUM(WEIGHT) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.") AND RECEIVER NOT IN ('".$previous_rec."') GROUP BY RECEIVER ORDER BY CNT DESC LIMIT $lower_limit,24";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		//mysql_close($db);
		$db=connect_db(1);

		$additional_parameters=array("SearchKeyword-SAME","username-$username");

		if(mysql_num_rows($res)>0)
		{
			//call display results and create XML
			$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
			uasort($results,"paidMemberSort");
			$XML_STRING=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,$total_rec="",$lower="",$upper="",$additional_parameters);
			echo $XML_STRING;
		}
		else
		{
			//search on basis of age,caste,etc
			$res=noSimilarProfiles($profileid);
//--------no res
			$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
			uasort($results,"paidMemberSort");
			$XML_STRING=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid,"",$total_rec="",$lower="",$upper="",$additional_parameters);
			echo $XML_STRING;
		}
	}
	else
	{
		//search on basis of age,caste,etc
		$res=noSimilarProfiles($profileid);
//--------no res
		$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
		uasort($results,"paidMemberSort");
		$XML_STRING=generateXML($results,120,7,$cellUserIsRegistered,$cellUserIsPaid);
		echo $XML_STRING;
	}
}

else if($act=="INT")
{
	$cell=format_cell_no($MSISDN);

        //Modified by lavesh
        $cellprofile=wap_profileid($MSISDN);
        //Ends Here

	//$sql_insert="INSERT INTO newjs.SMS_INT_PHONE VALUES('','$cell',now())";
	//mysql_query_decide($sql_insert) or queryDieLog(mysql_error_js(),$sql_insert);

	if($cellprofile)
	{
		//$sqlUpgrade="INSERT IGNORE INTO newjs.SMS_UPGRADE VALUES('','".$row['PROFILEID']."')";
		$sqlUpgrade="INSERT IGNORE INTO newjs.SMS_UPGRADE VALUES('',$cellprofile)";
		mysql_query_decide($sqlUpgrade) or queryDieLog(mysql_error_js(),$sqlUpgrade);
	}
	else
	{
		$sql="SELECT PROFILEID FROM JPROFILE WHERE PHONE_MOB='$cell' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		if($row=mysql_fetch_array($res))
		{
			$sqlUpgrade="INSERT IGNORE INTO newjs.SMS_UPGRADE VALUES('','".$row['PROFILEID']."')";
			mysql_query_decide($sqlUpgrade) or queryDieLog(mysql_error_js(),$sqlUpgrade);
		}
		else
		{
			//capture phone number if record not found - ajay from ACL said so
			/******************************************************************************************
			DATA DESCRIPTION : Logging mobile number of users who have used mobile search but are
					 : un-registered.
			******************************************************************************************/
			$sqlUnreg="INSERT INTO newjs.SMS_UNREG_USERS VALUES('','".$cell."')";
			$resUnreg=mysql_query_decide($sqlUnreg);
			/******************************************************************************************/
		}
	}

	//added and commented by lavesh
	//echo "A JeevanSathi executive will call you shortly.";
	$my_msg="Your Request has been sent.One of our executives will get in touch with you shortly.For Payment options and packs,kindly visit www.jeevansathi.com";
	generlized_generateXML(array('type','msg','SearchKeyword'),array('upgrade',$my_msg,$SearchKeyword));
}

elseif($MSISDN && $user_logged)
//users try to login.(comes from screen 11)
{
	$flag=0;
	if($myname && $password)
	{
                $sql="SELECT PROFILEID,PHONE_MOB FROM JPROFILE WHERE USERNAME='$myname' AND PASSWORD='$password' AND ACTIVATED<>'D'";                $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if(mysql_num_rows($result) < 1)
                {
                        $sql="SELECT PROFILEID,PHONE_MOB FROM JPROFILE WHERE EMAIL='$myname' AND PASSWORD='$password' AND ACTIVATED<>'D'";
                        $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                }
                $myrow = mysql_fetch_array($result);
                $id=$myrow['PROFILEID'];
		$mob=$myrow['PHONE_MOB'];
		if($id)
			$flag=1;

		if($flag==0)
		{
			 generlized_generateXML(array('LoginFailed'),array(1));
			//unsuccessfull login screen
		}
		else
		{
			login_wap($MSISDN,$myname);
			if( format_cell_no($mob)!=format_cell_no($MSISDN) )
			{
				if($mob)
				{
			 		generlized_generateXML(array('ReplaceMobile'),array(1));
					//prompt screen to replace mobile no.
				}
				else
				{	
			 		generlized_generateXML(array('AddMobile'),array(1));
					//prompt screen to add mobile no.
				}
			}
			else
			{
				generlized_generateXML(array('RegisteredHome'),array(1));
				//registered user home page
			}
		}
	}
	else
	{
		generlized_generateXML(array('LoginFailed'),array(1));
		//unsucessful login screen(username/password not entered)		
	}
}
elseif($Home)
{
	$cell_profileid=wap_profileid($MSISDN);
	if($cell_profileid)
		generlized_generateXML(array('RegisteredHome'),array(1));
	else
		generlized_generateXML(array('UnregisteredHome'),array(1));
}

elseif($MSISDN && ($login || $action) )
{
	if($action=='ValidationConfirmed')
	//confirm its mobile no.
	{
		$cell=get_cell_no($MSISDN);
                $myname=mobile_no_username($cell);
		login_wap($MSISDN,$myname);	
		generlized_generateXML(array('RegisteredHome'),array(1));
	}
	elseif($action)
	//updates its mobile no.	
	{
                $cell_profileid=wap_profileid($MSISDN,1);
		
		if($action=='add')
		{
			$sql = "UPDATE newjs.JPROFILE SET PHONE_MOB='$MSISDN' WHERE PROFILEID='$cell_profileid'";
			mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		}
		generlized_generateXML(array('RegisteredHome'),array(1));
	}
	else
	//User clicks on Matrimony
	{
		$cell=get_cell_no($MSISDN);
		if($cell)
		{
			$username=mobile_no_username($cell);
			generlized_generateXML(array('UserValidation','ConfirmUsername'),array(1,$username));
		}
		else
		{
			if(repeated_user($MSISDN)==1)
				generlized_generateXML(array('RepeatedHome'),array(1));
			else
				generlized_generateXML(array('UnregisteredHome'),array(1));
		}
	}
}

else
{
	echo "error";
}

// function to send the SMS for chat invitation
/*function acp_msg_receiver($triedUserPhone,$cellProfileid)
{
	$userDet=get_profile_details($cellProfileid);
	$responseMsg="Jeevansathi user - ". trimToNextSpace($userDet['NAME'].", ".$userDet['AGE'].", ".$height.", ".$userDet['CASTE'].", ".$userDet['MTONGUE'].", ".$userDet['EDUCATION'].", ".$userDet['OCCUPATION']." in ".$userDet['RESIDENCE'],0,60). " wants to chat\nTo accept SMS YES " . $userDet['NAME'] . " to 676762";

	//$fd=fopen("http://203.122.58.209/servlet/com.aclwireless.comm.listeners.TestServlet?userId=idg1sat&pass=pag1sat&msgtype=3&selfid=true&contenttype=1&dlrreq=false&intpush=false&to=".urlencode($triedUserPhone)."&from=62&text=".urlencode($responseMsg),"r");
	$fd=fopen("http://203.122.58.164:7000/servlet/com.aclwireless.pullconnectivityresponsetakerexternal.listeners.RequestListener?appid=g1sat&userid=idg1sat&pass=pag1sat&contenttype=1&to=".urlencode($triedUserPhone)."&from=676762&text=".urlencode($responseMsg),"r");
	fclose($fd);
}*/

// function to solve the problem of case sensitivity
function get_correct_username($username)
{
	$sql="select USERNAME from NAMES where USERNAME='$username'";
    $result=mysql_query_decide($sql);

    if(mysql_num_rows($result)>1)
    {
        $sql="select USERNAME from JPROFILE where USERNAME='$username' and ACTIVATED='Y'";
        $res=mysql_query_decide($sql);

        if(mysql_num_rows($res)==0)
			return "";
        else
			return $username;
    }
	elseif(mysql_num_rows($result)==1)
	{
		$myrow=mysql_fetch_array($result);
		
		$sql="select count(*) from JPROFILE where USERNAME='" . $myrow["USERNAME"] . "' and ACTIVATED='Y'";
		$res=mysql_query_decide($sql);
		$resrow=mysql_fetch_row($res);
		
		if($resrow[0]==1)
			return $myrow["USERNAME"];
		else 
			return "";
	}
	else
		return "";
}

// function to give the last ten chars of the cell no.
function format_cell_no($cell)
{
	if(strlen($cell)>10)
		$cell=substr($cell,strlen($cell)-10,10);

	return $cell;
}

//added by lavesh
function repeated_user($MSISDN)
{
	$sql1="select COUNT(*) as cnt FROM newjs.WAP_SEARCHQUERY WHERE MSISDN='$MSISDN'";
        $res1=mysql_query_decide($sql1) or queryDieLog(mysql_error_js(),$sql1);
        $row1=mysql_fetch_array($res1);
	return($row1["cnt"]);
}

function mobile_no_username($cell)
{
	$sql="select USERNAME FROM JPROFILE where PHONE_MOB='$cell' and ACTIVATED='Y'";
        $result=mysql_query_decide($sql);
        $myrow=mysql_fetch_array($result);
	return($myrow["USERNAME"]);	
}

function wap_profileid($MSISDN,$quiting="")
{
	$sql="SELECT PROFILEID FROM newjs.WAP_LOGIN WHERE MOBILE='$MSISDN'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];
	if($cell_profileid)
		return($cell_profileid);
	else
	{
		if($quiting)
			die("not registered");
		else
			return;
	}
}

function login_wap($MSISDN,$myname="")
{
	if($MSISDN)
	{
		if($myname)
			$sql="select PROFILEID FROM JPROFILE where USERNAME='$myname'";
		else
		{
			$cell=format_cell_no($MSISDN);
			$sql="select PROFILEID FROM JPROFILE where PHONE_MOB='$cell' and ACTIVATED='Y'";
		}
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result);
		$pid=$myrow["PROFILEID"];

		if($pid)
		{
			$sql="REPLACE INTO newjs.WAP_LOGIN VALUES('','$MSISDN','$pid')";
			mysql_query_decide($sql);
		}
	}
}
//ends here

// function to get the correct cell no. and to find whether the cell no. is registered in our database. Also checks for deleted profiles and whether the cell no. is in more than 1 profile
function get_cell_no($cell)
{
	$cell=format_cell_no($cell);

 	$sql="select count(*) from JPROFILE where PHONE_MOB='$cell' and ACTIVATED='Y'";
	$result=mysql_query_decide($sql);

	$myrow=mysql_fetch_row($result);
	
	if($myrow[0]==0)
		return "";
	elseif($myrow[0]==1)
		return $cell;
	else
		return "";
}

function log_chat_invitation($sender,$receiver)
{
	$sql="insert into CHAT_INVITATION(ID,SENDER,RECEIVER,TIME,ACCEPTED) values ('','$sender','$receiver',NOW(),'N')";
	mysql_query_decide($sql);
}

function check_filters($profileid_self,$profileid)
{
    if($profileid_self)
		$PERSON_LOGGED_IN=true;
		
    $sql="select * from JPARTNER where PROFILEID='$profileid'";
    $result=mysql_query_decide($sql);
                                                                                                                 
    if(mysql_num_rows($result) > 0)
    {
        $HAVE_PARTNER=true;
                                                                                                             
        $myrow=mysql_fetch_array($result);
                                                                                                             
        if($myrow["LAGE"]!="" && $myrow["HAGE"]!="")
        {
            $FILTER_LAGE=$myrow["LAGE"];
            $FILTER_HAGE=$myrow["HAGE"];                                
        }                        
    }
    $PARTNER_TABLES=array(array("TABLE" => "PARTNER_BTYPE",
                                    "VALUEARRAY" => "BODYTYPE"),
                            array("TABLE" => "PARTNER_CASTE",
                                    "VALUEARRAY" => ""),
                            array("TABLE" => "PARTNER_COUNTRYRES",
                                    "VALUEARRAY" => ""),
							array("TABLE" => "PARTNER_MTONGUE",
                                    "VALUEARRAY" => ""),
                    );
    $partnerid=$myrow["PARTNERID"];
	for($i=0;$i<count($PARTNER_TABLES);$i++)
	{
        $sql="select * from " . $PARTNER_TABLES[$i]["TABLE"] . " where PARTNERID='$partnerid'";
        $resultpartner=mysql_query_decide($sql);
                                                                                                             
        while($mypartner=mysql_fetch_row($resultpartner))
        {
            if($PARTNER_TABLES[$i]["VALUEARRAY"]=="")
				${$PARTNER_TABLES[$i]["TABLE"]}[]=$mypartner[1];
            else
            {
                if($PARTNER_TABLES[$i]["TABLE"]=="PARTNER_MSTATUS")
					$FILTER_MSTATUS[]=$mypartner[1];
                                                                                                     
                ${$PARTNER_TABLES[$i]["TABLE"]}[]=${$PARTNER_TABLES[$i]["VALUEARRAY"]}[$mypartner[1]];
            }
        }
	}

    if($PERSON_LOGGED_IN && $HAVE_PARTNER)
    {
    	// check whether the person being viewed has set the filters
    	$sql="select * from FILTERS where PROFILEID='$profileid'";
    	$resultfilter=mysql_query_decide($sql);
                                                                                                                 
    	if(mysql_num_rows($resultfilter) > 0)
    	{
        	$filterrow=mysql_fetch_array($resultfilter);
                                                                                                             
        	if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y")
        	{
				$sql="select count(*) from JPROFILE where PROFILEID='" . $profileid_self . "'";
            	if($filterrow["AGE"]=="Y")
					$sql.= " and AGE between '$FILTER_LAGE' and '$FILTER_HAGE'";
            	if($filterrow["MSTATUS"]=="Y" && is_array($FILTER_MSTATUS))
					$sql.=" and MSTATUS in ('" . implode("','",$FILTER_MSTATUS) . "')";
            	if($filterrow["RELIGION"]=="Y" && is_array($PARTNER_CASTE))
					$sql.=" and CASTE in ('" . implode("','",get_all_caste($PARTNER_CASTE)) . "')";
            	if($filterrow["COUNTRY_RES"]=="Y" && is_array($PARTNER_COUNTRYRES))
					$sql.=" and COUNTRY_RES in ('" . implode("','",$PARTNER_COUNTRYRES) . "')";
				if($filterrow["MTONGUE"]=="Y" && is_array($PARTNER_MTONGUE))
					$sql.=" and MTONGUE in ('" . implode("','",$PARTNER_MTONGUE) . "')";
					
            	$resfil=mysql_query_decide($sql);
                                                                     
            	$finalfilterrow=mysql_fetch_row($resfil);
				// if the person is filtered
            	if($finalfilterrow[0] <= 0)
					$filtered=1;

				mysql_free_result($resfil);
        	}
		}
                                                                                                                             
    	mysql_free_result($resultfilter);
    }
    
    return $filtered;
}	

?>
