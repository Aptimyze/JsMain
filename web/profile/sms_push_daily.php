<?php
ini_set('max_execution_time','0');

$today=date("Y-m-d");
list($year,$month,$day)=explode("-",$today);
if($today<"2008-09-30")
{
	if($day%2 != 0)
		$flag=1;
	else
                $flag=0;
}
else
{
	if($day=="4" || $day=="04" || $day=="7" || $day=="07" || $day=="14" || $day=="17" || $day=="24" || $day=="27")
		$flag=1;
	else
		$flag=0;
}
if(!$flag)
	exit;
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once("connect.inc");
$db=connect_slave();

$from="9911328109";
$gsm=1;
$table='DAILY_CONTACT_SMS';	
$yest_time=time() - (24 * 60 * 60);
$yesterday=date('Y-m-d', $yest_time);
//$yesterday="2008-03-21";

//$tmp_dt=explode('-',$yesterday);
//$msg_dt=formating_date($tmp_dt[2],$tmp_dt[1]);
$msg_dt='';

daily_contact("SEARCH_FEMALE",$yesterday,$msg_dt,$from,$gsm,$table);
daily_contact("SEARCH_MALE",$yesterday,$msg_dt,$from,$gsm,$table);

function daily_contact($searchtable,$yesterday,$msg_dt,$from,$gsm,$table)
{
	$sql = "SELECT PROFILEID FROM $searchtable WHERE COUNTRY_RES='51' AND HAVE_PHONE_MOB='Y'";
	$res123 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row_main = mysql_fetch_array($res123))
	{
		$pid=$row_main["PROFILEID"];
		
		$sql_s="SELECT COUNT(*) as cnt FROM SMS_SUBSCRIPTION_DEACTIVATED WHERE PROFILEID='$pid'";
		$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());//logError($errorMsg,"$sql_s","ShowErrTemplate");
		$row_s=mysql_fetch_array($res_s);
		$sms_unsubscribe=$row_s['cnt'];

		if(!$sms_unsubscribe)
		{
			$sql_phone = "SELECT SOURCE,PHONE_MOB,LAST_LOGIN_DT FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid' AND ACTIVATED='Y'";
			$res_phone = mysql_query_decide($sql_phone) or die("$sql_phone".mysql_error_js());
			$row_phone = mysql_fetch_array($res_phone);
			$mobile=$row_phone["PHONE_MOB"];
			$source=$row_phone['SOURCE'];
			$last_login_dt=$row_phone["LAST_LOGIN_DT"];
			$last_date=last_date($last_login_dt);
			if ($mobile && $source!='ofl_prof')
			{
				$mobile=mobile_correct_format($mobile);
				$rec_is_correct1=valid_gsm_no($mobile);
				$rec_is_correct2=valid_cdma_no($mobile);
				$checkmobile = checkmphone($mobile);

				if(($rec_is_correct1  || $rec_is_correct2) && (!$checkmobile))
				{
					$contactids='';$contact_cnt=$accept_cnt=0;
					$typeIn="'I'";
			                $receiversIn=$pid;
			                $timeClause="TIME BETWEEN '$last_date 00:00:00' AND '$yesterday 23:59:59'";
					$contactResult=getResultSet("SENDER",'','',$receiversIn,'',$typeIn,'',$timeClause,'','','','','','','','','','',"'Y'");
					if(is_array($contactResult))
			                {
			                        foreach($contactResult as $key=>$value)
			                        {
		                                        $contactSender=$contactResult[$key]["SENDER"];
							$contactids.="'$contactSender',";
							$contact_cnt++;
		                                }
		                        	unset($contactResult);
			                }
					$contactResult=getResultSet("RECEIVER",$pid,'','','',"'A'",'',$timeClause,'','','','','','','','','','',"'Y'");
                                        if(is_array($contactResult))
                                        {
                                                foreach($contactResult as $key=>$value)
                                                {
                                                        $contactrec=$contactResult[$key]["RECEIVER"];
                                                        $contactids.="'$contactrec',";
							$accept_cnt++;
                                                }
                                                unset($contactResult);
                                        }
					////////////////////////////
					$sql="select PROFILEID from jsadmin.OFFLINE_MATCHES where MATCH_ID='$pid' and STATUS IN('N','NNOW') and SHOW_ONLINE='Y'";
				        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				        while($row=mysql_fetch_array($res))
        					$NUDGES[]=$row['PROFILEID'];
					$match_point_awaiting=count($NUDGES);
					unset($NUDGES);
					///////////////////////////
					$contactids=substr($contactids,0,strlen($contactids)-1);
					if($contactids!='')
					{
						@mysql_ping($db);
						$sql="select PROFILEID,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,INCOME,MTONGUE,MSTATUS,MANGLIK,EDU_LEVEL_NEW from newjs.JPROFILE where  activatedKey=1 and PROFILEID IN ($contactids)";
                				$result=mysql_query_decide($sql,$db) or die($sql.mysql_error_js()) ;
                				$i=0;
				                while($myrow=mysql_fetch_array($result))
				                {
				                        $trend[$i]['my_profileid']=$myrow['PROFILEID'];
				                        $trend[$i]['my_age']=$myrow['AGE'];
				                        $trend[$i]['my_height']=$myrow['HEIGHT'];
                        				$trend[$i]['my_mtongue']=$myrow['MTONGUE'];
					                $trend[$i]['my_caste']=$myrow['CASTE'];
				                        $trend[$i]['my_manglik']=$myrow['MANGLIK'];
				                        $trend[$i]['my_city']=$myrow['CITY_RES'];
				                        $trend[$i]['my_country']=$myrow['COUNTRY_RES'];
				                        $trend[$i]['my_education']=$myrow['EDU_LEVEL_NEW'];
				                        $trend[$i]['my_occupation']=$myrow['OCCUPATION'];
				                        $trend[$i]['my_mstatus']=$myrow['MSTATUS'];
				                        $trend[$i]['my_income']=$myrow['INCOME'];
							$my_income=$trend[$i]['my_income'];
							$my_income=getSortByIncome($my_income);
				                     /*   if($my_income==15)
                				                $my_income=0;
      					                elseif($my_income==8)
      					                        $my_income=4;
				                        elseif($my_income==9)
                        				        $my_income=5;
                        				elseif($my_income==10)
                               					$my_income=6;
				                        elseif($my_income==11)
				                                $my_income=8;
				                        elseif($my_income==12)
				                                $my_income=9;
				                        elseif($my_income==13)
				                                $my_income=9;
				                        elseif($my_income==14)
				                                $my_income=9;
					                elseif($my_income==16)
				                                $my_income=7;
				                        elseif($my_income==17)
				                                $my_income=8;
				                        elseif($my_income==18)
					                        $my_income=9;
				                       */ $trend[$i]['my_income']=$my_income;
				                        $i++;
						}
						$sms_score=getting_reverse_trend_sep($trend,$pid);
						if($sms_score)
						{
						//End
							unset($message);
							$lastdate_list=explode("-",$last_date);
							$msg_dt=formating_date($lastdate_list[2],$lastdate_list[1]);
							if($contact_cnt)
							{
								if($contact_cnt>1)
									$message.="U've received $contact_cnt online contacts";
								else
									$message.="U've received $contact_cnt online contact";
								if($match_point_awaiting)
									$message.=", ";
								else
                                                                        $message.=" ";
								
								if($match_point_awaiting>1)
                                                                        $message.="$match_point_awaiting match point suggestions";
                                                                elseif($match_point_awaiting)
                                                                        $message.="$match_point_awaiting match point suggestion";
								if($accept_cnt)
									$message.=", ";
								else
									$message.=" ";
								if($accept_cnt>1)
                                                                        $message.="$accept_cnt acceptances ";
                                                                elseif($accept_cnt)
                                                                        $message.="$accept_cnt acceptance ";
							}
							elseif($match_point_awaiting)
							{
								if($match_point_awaiting>1)
                                                                        $message.="U've received $match_point_awaiting match point suggestions";
                                                                else
                                                                        $message.="U've received $match_point_awaiting match point suggestion";
								if($accept_cnt)
                                                                        $message.=", ";
                                                                else
                                                                        $message.=" ";
                                                                if($accept_cnt>1)
                                                                        $message.="$accept_cnt acceptances ";
                                                                elseif($accept_cnt)
                                                                        $message.="$accept_cnt acceptance ";
							}
							elseif($accept_cnt)
							{
								if($accept_cnt>1)
                                                                        $message.="U've received $accept_cnt acceptances ";
                                                                elseif($accept_cnt)
                                                                        $message.="U've received $accept_cnt acceptance ";
							}
							$message.="since $msg_dt. Logon 2 jeevansathi.com&#013;SMS JS to 56300 to download JS Mobile App";
							$db=connect_db();
							$valid_rec=send_sms($message,$from,$mobile,$pid,$gsm,$table,'Y');
							$db=connect_slave();
						}
					
					}
				}
			}
		}
	}
}

function formating_date($day,$month)
{
	if($month=="01" || $month=="1")
		$month="Jan";
	elseif($month=="02" || $month=="2")
		$month="Feb";
	elseif($month=="03" || $month=="3")
		$month="Mar";
	elseif($month=="04" || $month=="4")
		$month="Apr";
	elseif($month=="05" || $month=="5")
		$month="May";
	elseif($month=="06" || $month=="6")
		$month="Jun";
	elseif($month=="07" || $month=="7")
		$month="Jul";
	elseif($month=="08" || $month=="8")
		$month="Aug";
	elseif($month=="09" || $month=="9")
		$month="Sep";
	elseif($month=="10")
		$month="Oct";
	elseif($month=="11")
		$month="Nov";
	else
		$month="Dec";

	if(strlen($day)==1)
		$day= "0" . $day;
	
	$str=$day.'-'.$month;
	return($str);
}

//Function to calculate which date to be displayed in the sms : last login date or last sms date whichever is later
function last_date($last_login_dt)
{
	$today=date("Y-m-d");
	//$today="2009-03-07";
	list($smsyear,$smsmonth,$smsday)=explode("-",$today);
	list($loginyear,$loginmonth,$loginday)=explode("-",$last_login_dt);
	if($smsday=="4" || $smsday=="04")
	{
		if($smsmonth=="1" || $smsmonth=="01")
			$sms_stamp=mktime(0,0,0,12,27,date($smsyear)-1);
		else
			$sms_stamp=mktime(0,0,0,date($smsmonth)-1,27,date($smsyear));
	}
	elseif($smsday=="7" || $smsday=="07")
	{
		$sms_stamp=mktime(0,0,0,date("m"),4,date("Y"));
	}
	elseif($smsday=="14")
		$sms_stamp=mktime(0,0,0,date("m"),7,date("Y"));
	elseif($smsday=="17")
		$sms_stamp=mktime(0,0,0,date("m"),14,date("Y"));
	elseif($smsday=="24")
		$sms_stamp=mktime(0,0,0,date("m"),17,date("Y"));
	elseif($smsday=="27")
                $sms_stamp=mktime(0,0,0,date("m"),24,date("Y"));
	$last_sms_date=date("Y-m-d",$sms_stamp);
	list($last_sms_year,$last_sms_month,$last_sms_day)=explode("-",$last_sms_date);
	if($loginmonth && $loginday && $loginyear)
                $login_stamp=mktime(0,0,0,date($loginmonth),date($loginday),date($loginyear));
        else
                $login_stamp='';
	if($login_stamp>$sms_stamp)
		return $last_login_dt;
	else
		return $last_sms_date;
}

function getting_reverse_trend_sep($trend,$profileid)
{
	$message_data='';
	$req_score=0;
        if(count($trend))
        {
                $sql4="select PROFILEID FROM twowaymatch.TRENDS where PROFILEID=$profileid";
                $res4=mysql_query_decide($sql4)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");;
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
                                $sql_final2=" ".implode(" , ",$sql_array)." ";
                                unset($sql_array);
                                $sql5=" SELECT $sql_final as score from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";
                                $result5=mysql_query_decide($sql5) or die(mysql_error_js());
                                if($row5=mysql_fetch_array($result5))
                                {
                                        $scores=$row5['score'];
					if($scores>=20)
					{
						$req_score=1;
						break;
					}
				}
                        }
                }
        }
        return $req_score;
}
?>
