<?php
/***************************************************************************************************
	Filename	:       visitoralert_mailer.php
	Description     :       To retreive the profiles viewed that will be send as visitor alerts .
	Created by      :       Vibhor Garg
	Created On      :       05' Mar, 08
****************************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=substr(dirname(__FILE__),0,strpos(dirname(__FILE__),"/crontabs/mailers/visitoralert")) . "/web";

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
//include_once($_SERVER['DOCUMENT_ROOT']."/profile/search.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");

$mysqlObj=new Mysql;
$db=connect_737();

$db1=$mysqlObj->connect("211Slave");

$myDb1=$mysqlObj->connect("11Master");


mysql_query("set session wait_timeout = 1000",$db);
mysql_query("set session wait_timeout = 1000",$db1);
mysql_query("set session wait_timeout = 1000",$myDb1);

$date = date("Y-m-d",time()-86400);

/** code for daily count monitoring**/
if(!$php5)
	$php5=JsConstants::$php5path; //live php5
	$cronDocRoot = JsConstants::$cronDocRoot;
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring VA_MAILER");

/**code ends*/
//code for inserting new row
                 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring VA_MAILER#INSERT");

//Code for removing the previous data first after storing.
$sql="SELECT TOTAL FROM visitoralert.MAILER_VISITORS where SENT='Y'";
$result=mysql_query($sql,$myDb1) or die(mysql_error($myDb1));
$count = 0;
$total = 0;
while($row=mysql_fetch_array($result))
{
	$total=$total+$row["TOTAL"];
	$count++;
}

if($count>0)
{
	$sql="REPLACE INTO visitoralert.VISITOR_ALERT_RECORD (PROFILE_SENT,ALERT_SENT,SENT_DATE) VALUES ('$total','$count',now())";
	$result=mysql_query($sql,$myDb1) or die(mysql_error($myDb1));
	$myDb_ddl=$mysqlObj->connect("shard1DDL");
	$sql="TRUNCATE TABLE visitoralert.MAILER_VISITORS";
	$result=mysql_query($sql,$myDb_ddl) or die(mysql_error($myDb_ddl));
}

$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db);
$spamParameters['FLAG_VIEWABLE']='N';
$spammerList = $NEGATIVE_TREATMENT_LIST->getListOfSpammers($spamParameters);

//Code for removing the previous data first after storing.
$sql="Select DISTINCT(VIEWED) from newjs.VIEW_LOG_TRIGGER where DATE between '$date 00:00:00' and '$date 23:59:59'";
$res_visit=mysql_query($sql,$db1)or die("$sql".mysql_error($db1));
while($row_visit=mysql_fetch_assoc($res_visit))
{
	$profileid = $row_visit['VIEWED'];
	@mysql_ping($myDb1);
	$sql="Select ALERT_OPTION from visitoralert.VISITOR_ALERT_OPTION where PROFILEID='$profileid'";
	$res=mysql_query($sql,$myDb1) or die("$sql".mysql_error($myDb1));

	if(mysql_num_rows($res))
	{
		$row_status=mysql_fetch_assoc($res);
		$status=$row_status['ALERT_OPTION'];
	}
	else
		$status="";

	$db=connect_737();	
	$sql="Select GENDER from newjs.JPROFILE where PROFILEID='$profileid' AND (ACTIVATED='Y' OR (ACTIVATED='N' AND INCOMPLETE='Y')) AND PRIVACY<>'C'";
	$res=mysql_query($sql,$db) or die("$sql".mysql_error($db));

	// check to ensure visitor alert does not go to deleted profiles
	if(mysql_num_rows($res)<=0)
		continue;

	$row=mysql_fetch_assoc($res);

	$gender=$row['GENDER'];

	if($status != 'U')
	{
		if($status == 'D' || $status=="")
		{
			$valid_visitor=0;
		}
		else
		{
			$mysqlObj=new Mysql;
			$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
			$sql="Select COUNT(*) AS CNT from newjs.LOGIN_HISTORY where PROFILEID=$profileid and LOGIN_DT='$date'";
			$res=mysql_query($sql,$myDb) or die("$sql".mysql_error($myDb));
			$row_login=mysql_fetch_assoc($res);
			$valid_visitor=$row_login['CNT'];
		}

		if($valid_visitor==0)
		{
			$res1=visitor_alerts($profileid,$date,$gender,$spammerList);
			if(is_array($res1))
			{
				$count = count($res1);
				foreach($res1 as $key=>$val)
				{
					$row_final[]=$val;
				}
				if($count>0)
				{
					$sql="REPLACE INTO visitoralert.MAILER_VISITORS VALUES ('','$profileid','$row_final[0]','$row_final[1]','$row_final[2]','$row_final[3]','$row_final[4]','$row_final[5]','$row_final[6]','$row_final[7]','$row_final[8]','$row_final[9]','$row_final[10]','$row_final[11]','$row_final[12]','$row_final[13]','$row_final[14]','$row_final[15]','$row_final[16]','$row_final[17]','$row_final[18]','$row_final[19]','$count','N')";
					mysql_query($sql,$myDb1) or die("$sql".mysql_error($myDb1));
				}
				unset($row_final);
			}
		}
	}
}
/* Committed for trac 3480 , moved to symfony task RegularVisitorAlertsTask

$sql_count="SELECT COUNT(*) FROM visitoralert.MAILER_VISITORS WHERE SENT = 'N'";
$result_count = mysql_query($sql_count,$myDb1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_count,"ShowErrTemplate");
$myrow_count=mysql_fetch_row($result_count);
$count = $myrow_count[0];

mail('vibhor.garg@jeevansathi.com','Visitoralert Send Started'.$count,$today);
include_once("visitor_mail_new_inc.php");
mails_visitor($count);
$sql_count2="SELECT COUNT(*) FROM visitoralert.MAILER_VISITORS WHERE SENT = 'Y'";
$result_count2 = mysql_query($sql_count2,$myDb1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_count2,"ShowErrTemplate");
$myrow_count2=mysql_fetch_row($result_count2);
$count2 = $myrow_count2[0];
mail('vibhor.garg@jeevansathi.com','Visitoralert Send'.$count2,$today);
*/

function visitor_alerts($profileid,$date,$gender,$spammerList)
{
	$mysqlObj=new Mysql;
	$db=connect_737();
	$db1=$mysqlObj->connect("211Slave");
	$myDb1=$mysqlObj->connect("11Master");
	mysql_query("set session wait_timeout = 1000",$db);
	mysql_query("set session wait_timeout = 1000",$db1);
	mysql_query("set session wait_timeout = 1000",$myDb1);

	include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");

	$receiversIn=$profileid;
	$contactResult=getResultSet("SENDER",'','',$receiversIn);
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
			$ING_PRO[]=$contactResult[$key]["SENDER"];
		unset($contactResult);
	}
	$sendersIn=$profileid;
	$contactResult=getResultSet("RECEIVER",$sendersIn);
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
			$ING_PRO[]=$contactResult[$key]["RECEIVER"];
		unset($contactResult);
	}
	$sql_viewer="SELECT IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$profileid' UNION SELECT PROFILEID FROM IGNORE_PROFILE WHERE  IGNORED_PROFILEID='$profileid'";
	$result=mysql_query($sql_viewer,$db) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_viewer,"ShowErrTemplate");
	while($row=mysql_fetch_row($result))
		$ING_PRO[]=$row[0];

	$sql_visit="SELECT VIEWER FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWED='$profileid' AND VIEWER!='$profileid' AND DATE >= '$date 00:00:00' and DATE <= '$date 23:59:59'";
	$res_visit=mysql_query($sql_visit,$db1) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_visit,"ShowErrTemplate");

	if(mysql_num_rows($res_visit)==0)
		return null;
	else
	{
		$profileid_arr=array();
		while($row_visit=mysql_fetch_array($res_visit))
 		{   
			$profileid_arr[]=$row_visit['VIEWER'];
		}

		// remove ignored profiles
		if(is_array($ING_PRO))
		{
			$profileid_arr=array_diff($profileid_arr,$ING_PRO);

			if(!is_array($profileid_arr) || count($profileid_arr)<=0)
				return null;
		}

		// remove spammer profiles
		if(is_array($spammerList))
		{
			$profileid_arr=array_diff($profileid_arr,$spammerList);

			if(!is_array($profileid_arr) || count($profileid_arr)<=0)
	                        return null;
		}

		$profileid_str="'".implode($profileid_arr,"','")."'";

	       
		$arr_3d=get_variables_for_3d_va($profileid);
		$rel_caste=get_all_caste($arr_3d['CASTE']);

		$db=connect_737();

		$sql_rel_caste="select SQL_CACHE REL_CASTE from CASTE_COMMUNITY where PARENT_CASTE ='$arr_3d[CASTE]'";
		$sql_rel_caste_result=mysql_query($sql_rel_caste,$db);

		while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
		{
			$rel_caste[]=$myrow_sql_rel_caste_result["REL_CASTE"];
		}

		if(!is_array($rel_caste))
			$rel_caste=Array();
		
		unset($data_final_profile);

		$db=connect_737();
		$sql="SELECT AGE,HEIGHT,MANGLIK,MSTATUS,MTONGUE,COUNTRY_RES,PROFILEID,CASTE,INCOME,MTONGUE,RELIGION,GENDER, if(HAVEPHOTO='Y',0,1) AS HAVEPHOTO1, if(PHOTO_DISPLAY='A',0,1) AS PHOTO_CONDITION FROM newjs.JPROFILE where PROFILEID IN ($profileid_str) and GENDER<>'$gender' and PRIVACY<>'C' AND ACTIVATED NOT IN ('D','H') order by HAVEPHOTO1,PHOTO_CONDITION";
		$result=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=mysql_fetch_array($result))
		{
			$points=0;
			// dont send visitors of the same gender
			
			if($gender=='F')
				$points=calculate_3d_points_va($myrow['CASTE'],$myrow['RELIGION'],$myrow['MTONGUE'],$myrow['INCOME'],$gender,$profileid,'',$arr_3d,$rel_caste);       					
			else
				$points=200;//no 3d for boys

			if($points>150)
			{
				//That array will be used in pass_in_visitors_mail
				if($gender=='F')
					$data_for_dpp_f[]=$myrow;

				$data_final_profile[]=$myrow['PROFILEID'];
			}
		}
		$sql_privacy = "SELECT INCOME FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
		$result_privacy = mysql_query($sql_privacy,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_privacy,"ShowErrTemplate");

		$myrow_privacy=mysql_fetch_row($result_privacy);
		$income_user=$myrow_privacy[0];

		if($gender=='F' && is_array($data_for_dpp_f))
		{
			$jpartnerObj=get_jpartner_details($profileid);
			//Remove all the profiles that donot pass female dpp and income criteria..
			$data_final_profile=pass_in_visitors_mail($data_for_dpp_f,$jpartnerObj,$income_user,$profileid,1);
		}

		if(is_array($data_final_profile))
		{
			return $data_final_profile;
		}
		else
			return null;
	}
}

function get_variables_for_3d_va($profileid,$neglect_cookies='')
{
	$db=connect_737();

	$fields=" CASTE,MTONGUE,INCOME,RELIGION,AGE ";

	$sql="SELECT  $fields  FROM JPROFILE WHERE PROFILEID='$profileid'";

	$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow_get_value=mysql_fetch_array($res);

	$arr_3d['CASTE']=$myrow_get_value['CASTE'];
	$arr_3d['MTONGUE']=$myrow_get_value['MTONGUE'];
	
	$arr_3d['INCOME']=$myrow_get_value['INCOME'];
	$arr_3d['RELIGION']=$myrow_get_value['RELIGION'];
	$arr_3d['AGE']=$myrow_get_value['AGE'];
	if(!$arr_3d['CASTE'])
		$arr_3d['CASTE']=0;
	if(!$arr_3d['MTONGUE'])
		$arr_3d['MTONGUE']=0;
	if(!$arr_3d['INCOME'])
		$arr_3d['INCOME']=0;
	if(!$arr_3d['RELIGION'])
		$arr_3d['RELIGION']=0;
	if(!$arr_3d['AGE'])
		$arr_3d['AGE']=0;

	return($arr_3d);
}

function calculate_3d_points_va($caste,$religion,$mtongue,$income,$my_gender='',$profileid,$neglect_cookies='',$arr_3d='',$rel_caste='')
{
		// if same caste add 200 points
	if($caste==$arr_3d['CASTE'])
		$points=200;
	// for related castes of same religion add 125 points
	elseif(in_array($caste,$rel_caste) && $religion==$arr_3d['RELIGION'])
		$points=125;
	// for related castes of different religions add 75 points
	elseif(in_array($caste,$rel_caste))
		$points=75;
	// for same religion and not related castes no points will be added
	elseif($religion==$arr_3d['RELIGION'])
		$points=0;
	else
		$points=-100;

	// hindi/delhi, hindi/mp, hindi/up will be considered same
	if($arr_3d['MTONGUE']==10 || $arr_3d['MTONGUE']==19 || $arr_3d['MTONGUE']==33)
	{
		if($mtongue==10 || $mtongue==19 || $mtongue==33)
			$points+=100;
		// punjabi, haryanvi, bihari and rajasthani are related to hindi
		elseif($mtongue==28 || $mtongue==7 || $mtongue==13 || $mtongue==27)
			$points+=50;
	}
	// same mother tongue
	elseif($arr_3d['MTONGUE']==$mtongue)
		$points+=100;
	// marathi and konkani are related
	elseif(($arr_3d['MTONGUE']==20 || $arr_3d['MTONGUE']==34) && ($mtongue==20 || $mtongue==34))
		$points+=50;
	// oriya and bengali are related
	elseif(($arr_3d['MTONGUE']==25 || $arr_3d['MTONGUE']==6) && ($mtongue==25 || $mtongue==6))
		$points+=50;
	// bengali and assamese are related
	elseif(($arr_3d['MTONGUE']==6 || $arr_3d['MTONGUE']==5) && ($mtongue==5 || $mtongue==6))
		$points+=50;
	else
		$points+=-100;
	
	// for females points to be given for income
	if($my_gender=='F')
	{
		$incomeComparison=compareIncomes($arr_3d['INCOME'],$income);
		// if females income is less than male's
		if($incomeComparison=="smaller")
			$points+=90;
		// if females income is equal to male's
		elseif($incomeComparison=="equal")
			$points+=100;
		else
			$points+=-100;
	}
	
	return $points;
}

function get_jpartner_details($profileid)
{
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");

	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;

	if($profileid)//profileid is viewed profileid
	{
		$viewedDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$viewedDb=$mysqlObj->connect("$viewedDbName");
	}

	$jpartnerObj->setPartnerDetails($profileid,$viewedDb,$mysqlObj);
	return $jpartnerObj;
}

function pass_in_visitors_mail($all_data,$jpartnerObj,$income,$profileid,$from_where=0)
{

	$lage=$jpartnerObj->getLAGE();
	 $hage=$jpartnerObj->getHAGE();
	if(!$lage || !$hage)
		$no_age=1;
	$lheight=$jpartnerObj->getLHEIGHT();
	$hheight=$jpartnerObj->getHHEIGHT();
	if(!$lheight || !$hheight)
		$no_height=1;

	$manglik=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_MANGLIK()));
	$mstatus=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_MSTATUS()));
	$caste=display_format($jpartnerObj->getPARTNER_CASTE());
	if($caste)
		$all_caste=get_all_caste($caste);
	$religion=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_RELIGION()));
	$community=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_MTONGUE()));
	$country=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_COUNTRYRES()));
	$par_income=explode(",",remove_quotes_mail($jpartnerObj->getPARTNER_INCOME()));

	//If income is not defined in dpp , then use actual income of profile.
	if(count($par_income)>0 && $par_income[0]!="")
	{
		$act_income=$par_income;
	}

	if(is_array($all_data))
	foreach($all_data as $key=>$val)
	{
		$row=$val;
		$allow=1;
		$oth_age=$row['AGE'];
		$oth_height=$row['HEIGHT'];
		$oth_manglik=$row['MANGLIK'];
		$oth_mstatus=$row['MSTATUS'];
		$oth_caste=$row['CASTE'];
		$oth_religion=$row['RELIGION'];
		$oth_community=$row['MTONGUE'];
		$oth_country=$row["COUNTRY_RES"];
		$oth_income=$row['INCOME'];
		$oth_prof=$row['PROFILEID'];
		if(!$no_age)
		if(!($oth_age>=$lage && $oth_age<=$hage))
		{
			$allow=0;
		}
		if(!$no_height)
		if($allow==1)
			if(!($oth_height>=$lheight && $oth_height<=$hheight))
				$allow=0;
		if($allow==1)
			$allow=check_in_array_mail($mstatus,$oth_mstatus);
		if($allow==1)
			$allow=check_in_array_mail($country,$oth_country);
		if($allow==1)
			$allow=check_in_array_mail($all_caste,$oth_caste);
		if($allow==1)
			$allow=check_in_array_mail($religion,$oth_religion);
		if($allow==1)
			$allow=check_in_array_mail($community,$oth_community);
		if($allow==1)
			$allow=check_in_array_mail($manglik,$oth_manglik);
		if($allow==1)
		if(is_array($act_income))
		{
			if(in_array($oth_income,$act_income))
				$allow=1;
			else
				$allow=0;
		}
		if($allow==1)
		{
			$data_final_profile[]=$row['PROFILEID'];
		}


	}
	return $data_final_profile;
}

function check_in_array_mail($array,$value)
{
	if(count($array)>0 )
		if(!(count($array)==1 && $array[0]=="" && $array[0]!='DM'))
		if(!in_array($value,$array))
		{
			return 0;
		}
	return 1;
}

function remove_quotes_mail($value)
{
	return str_replace("'","",$value);
}

?>
