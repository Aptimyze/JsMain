<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : send_nudges.php
Description : Nudge new profiles for each active offline customer that meet OC's DPP, remove profiles that are in the pool but fall out of OC's DPP [2586]
Created By  : Sadaf Alam
Created On  : 15 Jan 2008
****************************************************************************************************************************/

chdir(dirname(__FILE__));

include("$docRoot/matchalert/connect.inc");
include("$docRoot/matchalert/nudges_partner_search_matchalert.inc");
//include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("newjs",$db) or die(mysql_error());

$dbslave=connnect_11862();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbslave);
mysql_select_db("matchalerts",$dbslave) or die(mysql_error());

$dbmaster=connect_main_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbmaster);
mysql_select_db("newjs",$dbmaster) or die(mysql_error());

global $noOfActiveServers;
global $slave_activeServers;
for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$slaveDbName=$slave_activeServers[$serverId];
	$slaveDb=$mysqlObj->connect("$slaveDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$slaveDb);
	$slaveDbArray[$slaveDbName]=$slaveDb;
	//unset($slaveDb);
	//unset($slaveDbName);
}
$today=date("Y-m-d H:i:s");
$sql="SELECT PROFILEID,CHANGE_DPP FROM jsadmin.OFFLINE_BILLING WHERE ACTIVE='Y'";
$res=mysql_query($sql,$db) or logError("$sql");
while($row=mysql_fetch_assoc($res))
{
	//unset($myDbName);
	//unset($myDb);
	//unset($check_dpp);
	$profileid=$row["PROFILEID"];
	$check_dpp=$row["CHANGE_DPP"];
	//echo "\n$profileid     ".$rowdet["GENDER"]."     ".$searchgender;
	unset($previous_rec_arr);
	$previous_rec_arr=skipped_records($profileid);
	if($previous_rec_arr)
		$previous_rec_str=implode($previous_rec_arr,"','");
	$sqldet="SELECT PROFILEID,CASTE,USERNAME,GENDER,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$resultdet=mysql_query($sqldet,$db) or logError("$sqldet");
	$trendrow=mysql_fetch_array($resultdet);
	$rec_caste=$trendrow["CASTE"];

	$username=$trendrow["USERNAME"];
	if($trendrow["GENDER"]=='M')
                $searchgender='F';
        else
                $searchgender='M';

	$sql_religion="SELECT PARENT FROM newjs.CASTE WHERE VALUE='$rec_caste'";
	$res_religion=mysql_query($sql_religion,$db) or logError("$sql_religion");
	if($myrow_religion=mysql_fetch_array($res_religion))
	{
		$parent_rel=$myrow_religion["PARENT"];
		if(in_array($parent_rel,array(7,1,9)))
			$update_parent_rel=1;
		else
			$update_parent_rel=$parent_rel;
	}
	$jpartnerObj->setPROFILEID($profileid);
	$myDbName=getProfileDatabaseConnectionName($profileid,'slave',$mysqlObj,$db);
	mysql_ping($slaveDbArray[$myDbName]);
	$myDb=$slaveDbArray[$myDbName];
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	$sql_where_temp=partner_search_matchalert($profileid,$jpartnerObj);
	$sql_where=$sql_where_temp[0];
	if(trim($previous_rec_str))
        	$sql_where_1=" AND PROFILEID NOT IN ('$previous_rec_str')";
	$sqlmatch="SELECT SQL_CALC_FOUND_ROWS PROFILEID,PARTNER_LAGE,PARTNER_HAGE,PARTNER_CASTE,PARTNER_MTONGUE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,PARTNER_COUNTRYRES ";
	//$sqlmatch="SELECT SQL_CALC_FOUND_ROWS PROFILEID , ";
                //$sqlmatch.=reverse_partner_profile($profileid);
	if($searchgender=='M')
		$sqlmatch.=" FROM matchalerts.SEARCH_MALE WHERE RELIGION=$update_parent_rel AND ";
	else
		$sqlmatch.=" FROM matchalerts.SEARCH_FEMALE WHERE RELIGION=$update_parent_rel AND ";
	$sqlmatch.=$sql_where.$sql_where_1;//.' HAVING LAVESH=1';
	//echo "\n".$profileid."   ".$sqlmatch;
	//connection string changed. 
	//@mysql_close($db);
	//$db=connect_11862($db);
	//mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
	//mysql_select_db("matchalerts",$db);
	//connection string changed.
	//echo "\n".$profileid."     ".$myDb."      ".$myDbName."      ".$row["CHANGE_DPP"]."     ".$sqlmatch;
	$result_matches=mysql_query($sqlmatch,$dbslave) or logError("$sqlmatch");
	//echo "\nNumber of results for ".$profileid."   ".mysql_num_rows($result_matches);

	//connection string restored to old one
	//@mysql_close($db);
	//$db=connect_db();
	//mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
	//mysql_select_db("matchalerts",$db);
	//connection string restored to old one

	if(mysql_num_rows($result_matches)>0)
	{
		while($row_matches=mysql_fetch_assoc($result_matches))
		{
			unset($put_in_pool);
			//unset($myDbName);
			//unset($myDb);
			$myDbName=getProfileDatabaseConnectionName($row_matches["PROFILEID"],"slave",$mysqlObj,$db);
			mysql_ping($slaveDbArray[$myDbName]);
			$myDb=$slaveDbArray[$myDbName];
			$sqldet="SELECT COUNT(*) AS COUNT FROM CONTACTS WHERE SENDER='$row_matches[PROFILEID]'";
			$resdet=$mysqlObj->executeQuery($sqldet,$myDb);
			$rowdet=$mysqlObj->fetchAssoc($resdet);
			if($rowdet["COUNT"]>=10)
			{
				$trend[0]=calculate_user_trend($trendrow);
				$reverse_score=getting_reverse_trend($trend,$row_matches["PROFILEID"]);
				if($reverse_score>=30)
					$put_in_pool=1;
				else
					$put_in_pool=0;
			}
			else
			{
				//$jpartnerObj->setPROFILEID($profileid);
				//$jpartnerObj->setPartnerDetails($row_matches["PROFILEID"],$myDb,$mysqlObj);	
				//if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$row_matches["PROFILEID"]))
				//{
					/*$caste_value=jpartner_search_in_array_format_m($jpartnerObj->getPARTNER_CASTE());
					if(is_array($caste_value))
					{
		                                $Caste=nudges_get_all_caste($caste_value);
                		                if(is_array($Caste))
                                		{
		                                        $Caste_clustering=implode($Caste,",");
                		                        $Caste=implode($Caste,"','");
                                		        $Caste="'" . $Caste . "'";
		                                }
                		                else
        	                        	        $Caste="";
	        	                        $partner_caste=$Caste;
                        		}*/
					$partner_caste=$row_matches["PARTNER_CASTE"];
					$partner_mtongue=$row_matches["PARTNER_MTONGUE"];//$jpartnerObj->getPARTNER_MTONGUE();
					$partner_countryres=$row_matches["PARTNER_COUNTRYRES"];//$jpartnerObj->getPARTNER_COUNTRYRES();
					$partner_lage=$row_matches["PARTNER_LAGE"];//$jpartnerObj->getLAGE();
					$partner_hage=$row_matches["PARTNER_HAGE"];//$jpartnerObj->getHAGE();
					$partner_lheight=$row_matches["PARTNER_LHEIGHT"];//$jpartnerObj->getLHEIGHT();
					$partner_hheight=$row_matches["PARTNER_HHEIGHT"];//jpartnerObj->getHHEIGHT();
					if($partner_caste || $partner_mtongue || $partner_countryres || $partner_lage || $partner_hage || $partner_lheight || $partner_hheight)
					{
						$sql="SELECT ";
						if($partner_caste)
							$sql.=" IF((CASTE IN ($partner_caste)),1,0) AS CASTE_MATCH,";
						else
							$row_temp["CASTE_MATCH"]=1;
						if($partner_mtongue)
							$sql.=" IF((MTONGUE IN ($partner_mtongue)),1,0) AS MTONGUE_MATCH,";
						else
							$row_temp["MTONGUE_MATCH"]=1;
						if($partner_countryres)
							$sql.=" IF((COUNTRY_RES IN($partner_countryres)),1,0) AS COUNTRY_RES_MATCH,";
						else
							$row_temp["COUNTRY_RES_MATCH"]=1;
						if($partner_lage && $partner_hage)
							$sql.=" IF ((AGE>=$partner_lage AND AGE<=$partner_hage),1,0) AS AGE_MATCH,";
						elseif($partner_lage)
							$sql.=" IF ((AGE>=$partner_lage),1,0) AS AGE_MATCH,";
						elseif($partner_hage)
							$sql.=" IF ((AGE<=$partner_hage),1,0) AS AGE_MATCH,";
						else
							$row_temp["AGE_MATCH"]=1;
						if($partner_lheight && $partner_hheight)
							$sql.=" IF((HEIGHT>=$partner_lheight AND HEIGHT<=$partner_hheight),1,0) AS HEIGHT_MATCH";
						elseif($partner_lheight)
							$sql.=" IF((HEIGHT>=$partner_lheight),1,0) AS HEIGHT_MATCH";
						elseif($partner_hheight)
							$sql.=" IF((HEIGHT<=$partner_hheight),1,0) AS HEIGHT_MATCH";
						else
							$row_temp["HEIGHT_MATCH"]=1;
						$sql=rtrim($sql,",");
						$sql.=" FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
						$res_score=mysql_query($sql,$db) or logError("$sql");
						$row=mysql_fetch_assoc($res_score);
						$match_score=$row["CASTE_MATCH"]+$row["MTONGUE_MATCH"]+$row["COUNTRY_RES_MATCH"]+$row["AGE_MATCH"]+$row["HEIGHT_MATCH"]+$row_temp["CASTE_MATCH"]+$row_temp["MTONGUE_MATCH"]+$row_temp["COUNTRY_RES_MATCH"]+$row_temp["AGE_MATCH"]+$row_temp["HEIGHT_MATCH"];
						if($match_score==5)
						{
							mysql_free_result($res_score);
							$put_in_pool=1;
						}
						else
						{
							mysql_free_result($res_score);
							$put_in_pool=0;
						}
					}
					else
						$put_in_pool=1;
				//}
				//else
					//$put_in_pool=1;
			}
			if($put_in_pool)
			{
				$category='';
				unset($sub);
				$sub=array();
				$sqldet="SELECT SHOWPHONE_RES,SHOWPHONE_MOB,PHONE_MOB,SUBSCRIPTION,SOURCE,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID='$row_matches[PROFILEID]'";
				$resdet=mysql_query($sqldet,$db) or logError("$sqldet");
				$rowdet=mysql_fetch_assoc($resdet);
				
				if($rowdet["SUBSCRIPTION"])
					$sub=explode(",",$rowdet["SUBSCRIPTION"]);
				
				if($rowdet["SOURCE"]=="ofl_prof" || in_array("1",$sub))
				$category=4;
				else
				{
					if(in_array("D",$sub))
						$category=3;
					elseif($rowdet["SUBSCRIPTION"]!="")
					{
						if(($rowdet["SHOWPHONE_MOB"]=="Y")||($rowdet["SHOWPHONE_RES"]=="Y"))
						$category=5;
					}
					else
					{
						//$sqlmob="SELECT ID FROM newjs.MOBILE_VERIFICATION_SMS WHERE MOBILE='$rowdet[PHONE_MOB]'";
						//$resmob=mysql_query($sqlmob,$db) or logError("$sqlmob");//die(mysql_error());
						$chk_phoneStatus =getPhoneStatus($rowdet);
						if($chk_phoneStatus =='Y')
						{
							if(($rowdet["SHOWPHONE_RES"]=="Y") || ($rowdet["SHOWPHONE_MOB"]=="Y"))
							$category=6;
						}
					}
				}
				$sqlins="INSERT IGNORE INTO jsadmin.OFFLINE_MATCHES(PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE) VALUES('$profileid','$row_matches[PROFILEID]','N','$category',NOW(),'SEND_MAIL')";
				mysql_query($sqlins,$dbmaster) or logError("$sqlins");

				$sqlins="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$row_matches[PROFILEID]',NOW(),'N')";
				mysql_query($sqlins,$dbmaster) or logError("$sqlins");
			}
		}
	}
	if($check_dpp=="Y")
	{
		$previous_rec_arr=skipped_records($profileid,1);
		if($previous_rec_arr)
		{
			$previous_rec_str=implode($previous_rec_arr,"','");
			mysql_select_db("newjs",$db);
			$sql_where_temp=notpartner_search_matchalert($profileid,$jpartnerObj);
			$sql_where=$sql_where_temp[0];
			$sqlmatch="SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM ";
			if($searchgender=="M")
			$sqlmatch.="newjs.JPROFILE WHERE (RELIGION!='$update_parent_rel' OR $sql_where)";	
			else
			$sqlmatch.="newjs.JPROFILE WHERE (RELIGION!='$update_parent_rel' OR $sql_where)";
			$sqlmatch.=" AND PROFILEID IN('$previous_rec_str')";
			//connection string changed. 
			//@mysql_close($db);
			//$db=connect_11862($db);
			//mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			//mysql_select_db("matchalerts",$db);
			//connection string changed. 
			//echo "\nDpp changed query      ".$profileid."    ".$myDbName."     ".$sqlmatch;
			
			$resultmatches=mysql_query($sqlmatch,$db) or logError("$sqlmatch");

			//echo "\n Number of results   ".mysql_num_rows($resultmatches);

			//connection string restored to old one
			//@mysql_close($db);        
			//$db=connect_db();
			//mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			//mysql_select_db("matchalerts",$db);
			//connection string restored to old one
			if(mysql_num_rows($resultmatches)>0)
			{
				while($rowmatch=mysql_fetch_assoc($resultmatches))
				{
					$sqldel="INSERT IGNORE INTO jsadmin.DELETED_OFFLINE_MATCHES(ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE) SELECT ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$rowmatch[PROFILEID]' AND PROFILEID='$profileid'";
					mysql_query($sqldel,$dbmaster) or logError("$sqldel");
					$sqldel="DELETE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$rowmatch[PROFILEID]' AND PROFILEID='$profileid'";
					//echo "\ndelete query    ".$sqldel;
					mysql_query($sqldel,$dbmaster) or logError("$sqldel");
					$sqldel="INSERT INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$rowmatch[PROFILEID]' AND RECEIVER='$profileid') OR (SENDER='$profileid' AND RECEIVER='$rowmatch[PROFILEID]')";
					mysql_query($sqldel,$dbmaster) or logError("$sqldel");
	
					$sqldel="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE (SENDER='$rowmatch[PROFILEID]' AND RECEIVER='$profileid') OR (SENDER='$profileid' AND RECEIVER='$rowmatch[PROFILEID]')";
					//echo "\ndelete query     ".$sqldel;
					mysql_query($sqldel,$dbmaster) or logError("$sqldel");
				}
			}
		
		}
		$sqlmatch="UPDATE jsadmin.OFFLINE_BILLING SET CHANGE_DPP='N' WHERE PROFILEID='$profileid'";
		$resmatch=mysql_query($sqlmatch,$dbmaster) or logError("$sqlmatch");
	}
}
function skipped_records($profileid,$change_dpp="0")
{
	global $db;
	$sql="SELECT MATCH_ID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid'";
	if($change_dpp)
	$sql.="AND STATUS IN ('N','NACC')";
	$res=mysql_query($sql,$db) or logError("$sql");
	if(mysql_num_rows($res)>0)
	{
		while($row=mysql_fetch_assoc($res))
		$previous_rec_arr[]=$row["MATCH_ID"];
	}
	if($previous_rec_arr)
	return $previous_rec_arr;
	else
	return ;
}
function calculate_user_trend($myrow)
{
	$i=0;
        if($myrow)
        {
                $trend['my_profileid']=$myrow['PROFILEID'];
                $trend['my_age']=$myrow['AGE'];
                $trend['my_height']=$myrow['HEIGHT'];
                $trend['my_mtongue']=$myrow['MTONGUE'];
                $trend['my_caste']=$myrow['CASTE'];
                $trend['my_manglik']=$myrow['MANGLIK'];
                $trend['my_city']=$myrow['CITY_RES'];
                $trend['my_country']=$myrow['COUNTRY_RES'];
                $trend['my_education']=$myrow['EDU_LEVEL_NEW'];
                $trend['my_occupation']=$myrow['OCCUPATION'];
                $trend['my_mstatus']=$myrow['MSTATUS'];
                $trend['my_income']=$myrow['INCOME'];
                $my_income=$trend['my_income'];
                $trend['my_income']=$my_income;
                return $trend;
        }
}

function getting_reverse_trend($trend,$profileid)
{
	global $db;
	$sql4="select * FROM twowaymatch.TRENDS where PROFILEID=$profileid";
	$res4=mysql_query($sql4,$db)  or logError("$sql4");
	if($row4=mysql_fetch_array($res4))                                
	{
		foreach($trend as $key=>$val)
		{
			foreach($val as $kk=>$vv)
				$$kk=$vv;
			$sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";

			$sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";

			$sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
	$sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";

			$sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";

			if($my_mstatus!='N')
				$sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
			else
				$sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

			if($my_manglik=='M')
				$sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
			else
				$sql_array[]="( W_MANGLIK * MANGLIK_N_P)";

			if($my_country=='51')
				$sql_array[]="( W_NRI * NRI_N_P)";
			else
				$sql_array[]="( W_NRI * NRI_M_P)";

			$sql_final="(".implode("+",$sql_array).")";
			unset($sql_array);

			$sql5=" SELECT $sql_final as score from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";

			$result5=mysql_query($sql5,$db) or logError("$sql5");
			$row5=mysql_fetch_assoc($result5);
			$score=$row5["score"];
			return $score;

		}
	}
}


?>
