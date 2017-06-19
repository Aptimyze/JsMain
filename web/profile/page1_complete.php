<?php
/*************************************************************************************************************************
	FILENAME	: page1_complete.php
	DESCRIPTION	: In case the user fills page1-partA and goes away, he has to be shown page1-partB 
			: whenever he comes back.
	MODIFIED BY	: SHAKTI SRIVASTAVA
	MODIFIED ON	: 4 OCTOBER, 2005
*************************************************************************************************************************/

/*to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it*/

require_once("connect.inc");
require_once(JsConstants::$docRoot."/commonFiles/flag.php");

$db=connect_db();
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='page1_complete is being hit. We can wrap this to JProfileUpdateLib';
               $subject="page1_complete";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
		header("Location:".$SITE_URL);
 //ending mail part
// authenticate only if this file has not been included in login.php because in that case authenticated function will fail as the cookie will not be available in the same script. $data already comes from login.php
if($logindone!="Y")
	$data=authenticated($checksum);

$FIELDS=array(	'Relationship'=>'RELATION',
		'Gender'=>'GENDER',
		'Marital_Status'=>'MSTATUS',
		'Religion'=>'RELIGION',
		'Caste'=>'CASTE',
		'Mtongue'=>'MTONGUE',
		'Height'=>'HEIGHT',
		'Education_Level'=>'EDU_LEVEL_NEW',
		'Occupation'=>'OCCUPATION',
		'Income'=>'INCOME',
		'Phone'=>'PHONE_RES',
		'Mobile'=>'PHONE_MOB',
		'Showphone'=>'SHOWPHONE_RES',
		'Showmobile'=>'SHOWPHONE_MOB',
		'Country_Residence'=>'COUNTRY_RES',
		'City_India'=>'CITY_RES',
		'City_USA'=>'CITY_RES',
		'Information'=>'YOURINFO',
		'radioprivacy'=>'PRIVACY',
		'checkboxalert1'=>'PERSONAL_MATCHES',
		'checkboxalert2'=>'SERVICE_MESSAGES',
		'checkboxalert3'=>'PROMO_MAILS',
		'checksum'=>'',
		'profileid'=>'');

if($data)
{
		$profileid=$data['PROFILEID'];

                $sql="SELECT * FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
                $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
                if($row["GENDER"]!="M" && $row["GENDER"]!="F")
                        $row["GENDER"]="";                                                                                                            
                $display_fields=array(  'GENDER'=>$row['GENDER'],
                                        'RELIGION'=>$row['RELIGION'],
                                        'CASTE'=>$row['CASTE'],
                                        'MTONGUE'=>$row['MTONGUE'],
                                        'MSTATUS'=>$row['MSTATUS'],
                                        'DTOFBIRTH'=>$row['DTOFBIRTH'],
                                        'OCCUPATION'=>$row['OCCUPATION'],
                                        'COUNTRY_RES'=>$row['COUNTRY_RES'],
                                        'CITY_RES'=>$row['CITY_RES'],
                                        'HEIGHT'=>$row['HEIGHT'],
                                        'EDU_LEVEL'=>$row['EDU_LEVEL'],
                                        'INCOME'=>$row['INCOME'],
                                        'PHONE_RES'=>$row['PHONE_RES'],
                                        'PHONE_MOB'=>$row['PHONE_MOB'],
                                        'SHOWPHONE_RES'=>$row['SHOWPHONE_RES'],
                                        'SHOWPHONE_MOB'=>$row['SHOWPHONE_MOB'],
                                        'PROMO_MAILS'=>$row['PROMO_MAILS'],
                                        'SERVICE_MESSAGES'=>$row['SERVICE_MESSAGES'],
                                        'PERSONAL_MATCHES'=>$row['PERSONAL_MATCHES'],
                                        'EDU_LEVEL_NEW'=>$row['EDU_LEVEL_NEW'],
                                        'YOURINFO'=>$row['YOURINFO'],
                                        'PRIVACY'=>$row['PRIVACY']);

	$smarty->assign("profileid",$profileid);
                                                                                                                            

	if($PSubmit)
	{
		$Information=trim($Information);
		$is_error=0;

		if($REL==1 && $Relationship=="")
		{
                        $is_error++;
                        $smarty->assign("check_relationship","Y");	
		}

		if($GEN==1 && $Gender=="")
		{
                        $is_error++;
                        $smarty->assign("check_gender","Y");
		}

		if($MAR==1 && $Marital_Status=="")
		{
                        $is_error++;
                        $smarty->assign("check_marital","Y");
		}
	
		if($RELIGION==1 && $Religion=="")
		{
                        $is_error++;
                        $smarty->assign("check_religion","Y");
		}
	 
		if($CAS==1 && $Caste=="")
		{
                        $is_error++;
                        $smarty->assign("check_caste","Y");
                }	
 
		if($MTONG==1 && $Mtongue=="")
		{
                        $is_error++;
                        $smarty->assign("check_mtongue","Y");
		}
 
		if($HGT==1 && $Height=="")
		{
                        $is_error++;
                        $smarty->assign("check_height","Y");
		}

		if($Day && $Month && $Year)
		{
	                $check_date=validate_date($Day,$Month,$Year);
			if($check_date==1)
			{
				$is_error++;
				$smarty->assign("check_date",$check_date);
			}
		}

		if($EDU==1 && $Education_Level=="")
		{
                        $is_error++;
                        $smarty->assign("check_education_level","Y");
		}

		if($EDU==1 && $Education_Level!="")
		{
		 	$Edu_old=get_old_value($Education_Level,'EDUCATION_LEVEL_NEW');
		}
	
		if($OCPN==1 && $Occupation=="")
		{
                        $is_error++;
                        $smarty->assign("check_occupation","Y");
		}

		if($INCOM==1 && $Income=="")
		{
                        $is_error++;
                        $smarty->assign("check_income","Y");
		}

		if($TELE==1 || $CELL==1)
		{
			if(trim($Phone)=="")
	                {
        	                $is_error++;
                	        $smarty->assign("check_phone","Y");
				$smarty->assign("check_phone_v","1");
				$smarty->assign("check_mobile_v","1");
        	        }
			else
			{
				$check_phone_v=checkrphone($Phone);
        		        $check_mobile_v=checkrphone($Mobile);
                                                                                                                            
	                	if ($check_mobile_v==1 && $check_phone_v==1)
	        	        {
        	        	        $is_error++;
                	        	$smarty->assign("check_phone_v",$check_phone_v);
	                	        $smarty->assign("phone_msg","Phone no. has invalid characters");
        	                	$smarty->assign("check_mobile_v",$check_mobile_v);
	                	        $smarty->assign("mobile_msg","Mobile no. has invalid characters");
		                }
			}
		}
	

		if($CTRY==1 && $Country_Residence=="")
		{
	                $is_error++;
                        $smarty->assign("check_countryres","Y");
		}

                if($CTRY==1 && $Country_Residence=='51')
                {
                        if($City_India=='')
                        {
                                $is_error++;
                                $smarty->assign("check_city_residence","Y");
                                $smarty->assign("CITY_INDIA","Y");
                        }
                }

                if($CTRY==1 && $Country_Residence=='128')
                {
                        if($City_USA=='')
                        {
                                $is_error++;
                                $smarty->assign("check_city_residence","Y");
                                $smarty->assign("CITY_USA","Y");
                        }
                }

		if($YINF==1 && (trim($Information)=="" || strlen(trim($Information))<100))
                {
                        $is_error++;
                        $smarty->assign("check_information","Y");
                        $check_information="Y";
			$infomsg="<font color=\"red\">Information cannot be blank and should be more than 100 characters long</font>";
			$smarty->assign("infomsg",$infomsg);
                }


		if($is_error>0)
		{
			maStripVARS("stripslashes");
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("display_fields",$display_fields);

			$smarty->assign("caste",create_dd("$Caste","Caste"));
			$smarty->assign("religion",create_dd("$Religion","Religion"));
			$smarty->assign("mtongue",create_dd("$Mtongue","Mtongue"));
			$smarty->assign("height",create_dd("$Height","Height"));
			$smarty->assign("education_level",create_dd("$Education_Level","Education_Level_New"));
			$smarty->assign("occupation",create_dd("$Occupation","Occupation"));
			$smarty->assign("income",create_dd("$Income","Income"));
			$smarty->assign("top_country",create_dd("","top_country"));
			$smarty->assign("country_residence",create_dd("$Country_Residence","Country_Residence"));
			$smarty->assign("city_india",create_dd("","City_India"));
			$smarty->assign("city_usa",create_dd("","City_USA"));

			$smarty->assign("RADIOPRIVACY",$radioprivacy);
			$smarty->assign("information",$Information);
			$smarty->assign("CHARACTERS",strlen($Information));
			$smarty->assign("phone",$Phone);
			$smarty->assign("mobile",$Mobile);
			$smarty->assign("day",$Day);
			$smarty->assign("month",$Month);
			$smarty->assign("year",$Year);
			$smarty->assign("CHECKBOXALERT1",$checkboxalert1);
			$smarty->assign("CHECKBOXALERT2",$checkboxalert2);
			$smarty->assign("CHECKBOXALERT3",$checkboxalert3);

			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

			$smarty->assign("NO_OF_ERROR",$is_error);
			$smarty->display("page1_complete.htm");
		}
		else
		{
			$sql_updt[]="UPDATE newjs.JPROFILE SET ";

			foreach($_POST as $key=>$value)
			{
                                if($FIELDS[$key]!='')
                                {
                                        if($FIELDS[$key]=='YOURINFO')
                                                $yourinfo_flag=1;
                                        else
                                                $sql_updt[]= $FIELDS[$key]."='".$value."'";
                                                                                                                             
                                }
			}

			$sql_up=$sql_updt[0].$sql_updt[1];
			for($i=2;$i<count($sql_updt);$i++)
			{
				$sql_up.=" , ".$sql_updt[$i];
			}

			if($EDU==1)
			{
				$sql_up.=" , EDU_LEVEL='$Edu_old' ";
			}

                        if($yourinfo_flag)
                                $sql_up.=" , YOURINFO='".addslashes(stripslashes($Information))."' ";
			$sql_up.=" , ENTRY_DT=if(INCOMPLETE='Y',now(),ENTRY_DT),MOD_DT=NOW(), INCOMPLETE ='N', SCREENING='0' WHERE PROFILEID='$profileid'";

			if($_SERVER['REQUEST_METHOD']=="POST")	
			{
				mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_up,"ShowErrTemplate");
				include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
				JProfileUpdateLib::getInstance()->removeCache($profileid);
			}

			//to check partner profile is filled by the user or not //
	        $partner_exist=0;
	        
	        $pid=$profileid;
		//Sharding Concept added by Vibhor Garg on table JPARTNER
	       	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
 		$mysqlObj=new Mysql;
	        $jpartnerObj=new Jpartner;
		$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
        	$myDb=$mysqlObj->connect("$myDbName");
	        $jpartnerObj->setPartnerDetails($pid,$myDb,$mysqlObj,"DPP");
        	$dpp = $jpartnerObj->getDPP();
	        if(strstr($dpp,'S')||strstr($dpp,'O'))
        	        $partner_exist = 1;
        	//Sharding Concept added by Vibhor Garg on table JPARTNER

			if(!$partner_exist)
			{
				//showPart2($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source);
				header("Location:/P/editdesiredprofile.php?checksum=$checksum");
			}
			else
			{
				header("Location:mainmenu.php?checksum=$checksum");
			}
		}
	}
	else
	{
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("display_fields",$display_fields);

		$smarty->assign("caste",create_dd("","Caste"));
		$smarty->assign("religion",create_dd("","Religion"));
		$smarty->assign("mtongue",create_dd("","Mtongue"));
		$smarty->assign("height",create_dd("","Height"));
		$smarty->assign("education_level",create_dd("","Education_Level_New"));
		$smarty->assign("occupation",create_dd("","Occupation"));
		$smarty->assign("income",create_dd("","Income"));
		$smarty->assign("top_country",create_dd("","top_country"));
		$smarty->assign("country_residence",create_dd("","Country_Residence"));
		$smarty->assign("city_india",create_dd("","City_India"));
		$smarty->assign("city_usa",create_dd("","City_USA"));

		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	
		$smarty->display("page1_complete.htm");
	}
}
else
{
	TimedOut();
}


function showPart2($checksum,$gender,$hit_source,$maritalstatus,$tieup_source)
{
        global $smarty;
        include("inputprofile2.php");
}

?>
