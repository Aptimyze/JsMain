<?php
/************************************************************************************************************************
*    FILENAME           : login_intermediate_pages.php
*    DESCRIPTION        : Include all intermediate pages after user logins
*    CREATED BY         : lavesh
***********************************************************************************************************************/
 include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
function intermediate_page($return_url=0)
{
	global $data,$checksum;
	$profileid=$data["PROFILEID"];
	//Added By Vibhor
	$edit_page = 0;
	$nuser = 0;
	$sql_in="select PHONE_MOB,PHONE_RES,STD,INCOMPLETE,ACTIVATED FROM JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";

	$result_in=mysql_query_decide($sql_in) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_in,"ShowErrTemplate");

	if(mysql_num_rows($result_in) > 0)
	{
		$myrow_in=mysql_fetch_array($result_in);
                $mobile = $myrow_in["PHONE_MOB"];
		$landline= $myrow_in["PHONE_RES"];
		$std = $myrow_in["STD"];
		$activated=$myrow_in["ACTIVATED"];
		$after_login=is_incomplete($profileid);
		$jprofileUpdateObj = JProfileUpdateLib::getInstance();
		if($after_login)
		{

			$profileid=$profileid;
			$arrFields = array('INCOMPLETE'=>'Y','ACTIVATED'=>'N','PREACTIVATED'=>$activated);
			$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID");
			
			//$sql_in="UPDATE JPROFILE SET INCOMPLETE='Y',ACTIVATED ='N', PREACTIVATED='$activated' WHERE PROFILEID='$profileid'";

			//$result_in=mysql_query_decide($sql_in) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_in,"ShowErrTemplate");
			
			$profilechecksum = md5($profileid)."i".$profileid;
	                if($return_url==1)
        	                return "$SITE_URL/P/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&after_login=$after_login";
                	else
	                {
        	                echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/P/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&after_login=$after_login\"></body></html>";
                	        exit;
	                }
		}
		else
		{
			$nowDate = date('Y-m-d H:i:s');
			$arrParams = array('ACTIVATED'=>'N', 'SCREENING'=>0, 'INCOMPLETE'=>'N', 'ENTRY_DT'=>$nowDate, 'MOD_DT'=>$nowDate);
			$result = $jprofileUpdateObj->editJPROFILE($arrParams, $profileid, 'PROFILEID', " INCOMPLETE='Y'");
			if(false === $result) {
				$sql_up="update newjs.JPROFILE set ACTIVATED='N',SCREENING=0,INCOMPLETE='N',ENTRY_DT=now(),MOD_DT=now() where INCOMPLETE='Y' AND PROFILEID='$profileid'";
				logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_up,"ShowErrTemplate");
			}
//			$sql_up="update newjs.JPROFILE set ACTIVATED='N',SCREENING=0,INCOMPLETE='N',ENTRY_DT=now(),MOD_DT=now() where INCOMPLETE='Y' AND PROFILEID='$profileid'";
//        		$result_up=mysql_query_decide($sql_up) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_up,"ShowErrTemplate");
			if($myrow_in[INCOMPLETE]=='Y'){
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
				if(!FTOStateHandler::profileExistsInFTOStateLog($profileid)){
					//Fto state change after completion of page2
					$fto_action = FTOStateUpdateReason::REGISTER;
					SymfonyFTOFunctions::updateFTOState($profileid,$fto_action);
				}
			}
		}
	}
}
function is_invalid($profileid)
{
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
        $chk_phoneStatus =getPhoneStatus('',$profileid);
        if($chk_phoneStatus =='I')
                return true;
        return false;
}
function is_incomplete($profileid)
{
	$after_login = 0;
	$mandatory_fields = "USERNAME,PASSWORD,GENDER,RELIGION,CASTE,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,EDU_LEVEL_NEW,COUNTRY_RES,CITY_RES,HEIGHT,EMAIL,RELATION,INCOME,PHONE_RES,PHONE_MOB,YOURINFO";
	$sql_inc="select $mandatory_fields FROM JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
        $result_inc=mysql_query_decide($sql_inc) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_inc,"ShowErrTemplate");

        if(mysql_num_rows($result_inc) > 0)
        {
                $myrow_inc=mysql_fetch_array($result_inc);
		$username = $myrow_inc["USERNAME"];
		$password = $myrow_inc["PASSWORD"];
		$gender = $myrow_inc["GENDER"];
		$religion = $myrow_inc["RELIGION"];
		$caste = $myrow_inc["CASTE"];
		$mtongue = $myrow_inc["MTONGUE"];
                $mstatus = $myrow_inc["MSTATUS"];
		$dtofbirth = $myrow_inc["DTOFBIRTH"];
                $occupation = $myrow_inc["OCCUPATION"];
		$edu_level_new = $myrow_inc["EDU_LEVEL_NEW"];
                $country_res = $myrow_inc["COUNTRY_RES"];
                $city_res = $myrow_inc["CITY_RES"];
		if($myrow_inc[CITY_RES]=="0")
			$city_res=true;
			
                $height = $myrow_inc["HEIGHT"];
                $email = $myrow_inc["EMAIL"];
                $relation = $myrow_inc["RELATION"];
		$income = $myrow_inc["INCOME"];
                $phone_res = $myrow_inc["PHONE_RES"];
                $phone_mob = $myrow_inc["PHONE_MOB"];
                $yourinfo = $myrow_inc["YOURINFO"];
		//If country_res is not india and USA, city can be blank
		if($country_res!=51)
			$city_res=true;
		if(strlen($yourinfo)<100)
			$after_login=1;
		else if(!$username||!$password||!$gender||!$religion||!$caste||!$mtongue||!$mstatus||$dtofbirth== '0000-00-00'||!$occupation||!$edu_level_new||!$country_res||!$city_res||!$height||!$email||!$relation||!$income||(!$phone_res && !$phone_mob))
			$after_login=1;
		return $after_login;
	}
	else
		return $after_login;
}
	/*$opt_count=0;
	$sql="SELECT * FROM newjs.INTERMEDIATE_PAGE WHERE PROFILEID='$pid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_array($result))
	{
	$option=$row["MYOPTION"];
	$count_new=$row["COUNT"];
	if($option==7 && $count_new==1)
	$count_new=2;
	else
	$count_new=1;
	}
	else
	$option=2;

	$opt_count=0;

	while($option)
	{
		$opt_count++;
		if($option==2)
		{
			$verify_email = bounced_emailID($pid);
			if ($verify_email)
			{
				reflecttable(3,$count_new,$pid);
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/verify_email_Id.php?checksum=$checksum&verify_email=$verify_email\"></body></html>";
				exit;	
			}
		}
		elseif($option==3)
		{
			/*
			if($data['SUBSCRIPTION'])
			{
				$sql="SELECT VIEWED FROM billing.VOUCHER_VIEWED WHERE PROFILEID='$pid' ORDER BY ID DESC";
				$res_voucher=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				if(mysql_num_rows($res_voucher))
				{
					$row_voucher=mysql_fetch_array($res_voucher);
					if($row_voucher["VIEWED"]=='')
					{
			
						reflecttable(3,$count_new,$pid);
						echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/promo_optin.php?checksum=$checksum\"></body></html>";
						exit;	
					}
				}
			}
			*/
	/*	}
		elseif($option==4)
		{
			$sql = "Select count(*) as cnt from incentive.INVALID_PHONE where PROFILEID = '$pid'";
			$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow = mysql_fetch_array($result);
			if($myrow['cnt']>0)
				$invalid_phone='y';

			$sql = "Select count(*) as cnt from incentive.MAIN_ADMIN_POOL where PROFILEID = '$pid' AND TIMES_TRIED>=3";
			$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow = mysql_fetch_array($result);
			if($myrow['cnt']>0)
				$invalid_phone='y';

			$today = date("Y-m-d ");

			$date_before_3months = date('Y-m-d',JSstrToTime("$today -3 month"));
			if($date_before_3months>$mod_dt)
				$diff=1;

			if($diff==1)
			{
				$sql="select count(*) as cnt from newjs.USERDETAILS_PROFILES WHERE PROFILEID='$pid' AND ENTRY_DT>'$date_before_3months'";
				$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow = mysql_fetch_array($result);
				if($myrow['cnt']>0)
					$diff=0;
			}

			$sql_cont_stat = "SELECT * FROM CONTACTS_STATUS WHERE PROFILEID='$pid'";
			$res_cont_stat = mysql_query_decide($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
			if(mysql_num_rows($res_cont_stat) > 0)
			{
				$row_cont_stat = mysql_fetch_array($res_cont_stat);
				$ACCEPTED=$row_cont_stat['ACC_BY_ME']+$row_cont_stat['ACC_ME'];
				$MADESUM=$row_cont_stat['ACC_ME']+$row_cont_stat['NOT_REP']+$row_cont_stat['DEC_ME'];
				$RECEIVEDSUM=$row_cont_stat['ACC_BY_ME']+$row_cont_stat['OPEN_CONTACTS']+$row_cont_stat['DEC_BY_ME'];
				$onlyreceivedsum=$row_cont_stat['OPEN_CONTACTS'];
				if($row_cont_stat['EXPIRY_DT']!='0000-00-00')
				{
					list($year,$month,$day) = explode("-",$row_cont_stat['EXPIRY_DT']);
					$show_expiry="yes";
					$ssexpiry_dt=my_format_date($day,$month,$year);
				}
				else
				{
					$show_expiry="no";
					$ssexpiry_dt="";
				}
			}
			mysql_free_result($res_cont_stat);

			if(($diff==1 && $ACCEPTED>=1)|| $invalid_phone=='y')
			{
				reflecttable(5,$count_new,$pid);
				if($invalid_phone=='y' && $ACCEPTED<1)
					$case=1;
				elseif($invalid_phone=='y' && $ACCEPTED>0)
					$case=2;
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/userdetails.php?case=$case&checksum=$checksum&invalid_phone=$invalid_phone\"></body></html>";
				exit;	
			}

		}
		elseif($option==5)
		{
			$sql="SELECT COUNTRY_RES,PHONE_MOB FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
                        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow=mysql_fetch_array($result);
	
                        if($myrow["COUNTRY_RES"]=='51' && $myrow["PHONE_MOB"])
			{
				$phone_mob=$myrow["PHONE_MOB"];

				$sql_mob="SELECT COUNT(*) AS CNT FROM MOBILE_VERIFICATION_SMS WHERE MOBILE='$phone_mob'";
				$result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
				$myrow_mob=mysql_fetch_array($result_mob);

				if($myrow_mob["CNT"]<1)
				{
					reflecttable(6,$count_new,$pid);
					echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mobile_intermediate_page.php?checksum=$checksum&PHONE_MOB=$phone_mob\"></body></html>";
					exit;	
				}	
			}
		}

		elseif($option==6)
		{
			$sql="SELECT SHOW_HOROSCOPE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_array($result);

			if($myrow["SHOW_HOROSCOPE"]=='Y')
			{
				$sql_astro_data = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$pid'";
				$res_astro_data = mysql_query_decide($sql_astro_data);
				$row_astro_data = mysql_fetch_array($res_astro_data);
				if($row_astro_data['COUNT']==0)
				{
					reflecttable(7,$count_new,$pid);
					echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/edit_profile.php?checksum=$checksum&EditWhat=AstroData&type=L\"></body></html>";
					exit;	
				}
			}
		}
		elseif($option==7)
		{
			if($count_new==2)
				reflecttable(8,1,$pid);
			else	
				reflecttable(7,$count_new,$pid);
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?checksum=$checksum\"></body></html>";
			exit;	
		
		}
		elseif($option==8)
		{
			$sql_new="SELECT COUNT FROM newjs.INCREASE_RESPONSE WHERE PROFILEID='$pid'";
			$result_new = mysql_query_decide($sql_new) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_new,"ShowErrTemplate");
			$myrow_new = mysql_fetch_array($result_new);
			if($myrow_new['COUNT']<5)
			{
				$sql_new="SELECT RELIGION,CASTE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
				$result_new = mysql_query_decide($sql_new) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_new,"ShowErrTemplate");
				$myrow_new = mysql_fetch_array($result_new);

				//Page will be displayed if CASTE IS EMPTY OR CASTE IS SAME AS RELIGION
				if($myrow_new['CASTE']>0)
				{
					$temp=label_select("CASTE",$myrow_new['CASTE']);
					$caste=$temp[0];
					unset($temp);
														     
					$temp=label_select("RELIGION",$myrow_new['RELIGION']);
					$religion=$temp[0];
					unset($temp);
														     
					if($caste==$religion)
					{
			       			reflecttable(9,$count_new,$pid);
						echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/increase_response_subcaste.php?checksum=$checksum\"></body></html>";
						exit;	
					}
														     
				}
				else
				{
				       	reflecttable(9,$count_new,$pid);
					echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/increase_response_subcaste.php?checksum=$checksum\"></body></html>";
					exit;	
				}
			}
		}
		elseif($option==9)
		{
			if($data['SUBSCRIPTION']=='')
			{
				//removing vouchers 2721 [Added by Sadaf]
				if(0)
				{
				//$sql="SELECT COUNT FROM VOUCHER_INTERMEDIATE_VIEWED WHERE PROFILEID='$pid'";
				//$res=mysql_query_decide($sql);
				//$row=mysql_fetch_array($res);
				//if ($row['COUNT']<5)
				//{
			       		reflecttable(10,$count_new,$pid);
					echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/voucher_intermediate.php?checksum=$checksum\"></body></html>";
					exit;	
				//}
				}
			}

		}
		elseif($option==10)	
		{
			//Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST
			include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
			$jpartnerObj=new Jpartner;
			$mysqlObj=new Mysql;
			$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");

			if($jpartnerObj->calculateCountInPartnerProfile($myDb,"","","R",$pid))
                        {
			       	reflecttable(11,$count_new,$pid);
                                echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/editdesiredprofile.php?checksum=$checksum&coming_from=MYJS\"></body></html>";
				exit;	
                        }
		}
		elseif($option==11)
		{
			$sql_cont_stat = "SELECT OPEN_CONTACTS FROM CONTACTS_STATUS WHERE PROFILEID='$pid'";
                        $res_cont_stat = mysql_query_decide($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
                        if(mysql_num_rows($res_cont_stat) > 0)
                        {
				$row_cont_stat = mysql_fetch_array($res_cont_stat);
				$onlyreceivedsum=$row_cont_stat['OPEN_CONTACTS'];
			}

			if(($onlyreceivedsum >=25 && $gender=="M") || ($onlyreceivedsum >=50 && $gender=="F"))
			{
				reflecttable(2,$count_new,$pid);
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/contacts_made_received.php?checksum=$checksum&flag=I&type=R&first_time=Y\"></body></html>";
				exit;
			}
		}
	
		if($opt_count>=10)
		{
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/contacts_made_received.php?checksum=$checksum&flag=I&type=R&first_time=Y\"></body></html>";
			exit;
		}
		else
                {
                        if($option==11)
                                $option=2;
                        else
                                $option=$option+1;
                } 
	}*/
function reflecttable($exit_option,$count_new,$pid)
{
	if($count_new)
        	$sql="UPDATE INTERMEDIATE_PAGE SET MYOPTION='$exit_option' , COUNT='$count_new' WHERE PROFILEID='$pid'";
	else
		$sql="INSERT IGNORE INTO newjs.INTERMEDIATE_PAGE VALUES('',$pid,$exit_option,'1')";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
?>
