<?php
include_once(JsConstants::$docRoot."/mis/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$db=connect_misdb();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
        $searchMonth='';
        $searchYear='';
        $monthDays=0;
	$searchFlag=0;
        $index=array('A','D');
        if(!$today)
		$today=date("Y-m-d");
        if(!$today)
		$today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
        if($outside)
        {
                $CMDGo='Y';
                $searchMonth=$todMonth;
                $searchYear=$todYear;
                $monthDays=$todDay;
                $searchFlag=1;
        }
	$searchdate_timestamp= mktime(0,0,0,$monthEntered,31,$yearEntered);
	$searchdate=date("Y-m-d",$searchdate_timestamp);
	if($searchMonth=='')
		$searchMonth=$monthEntered;
	if($searchYear=='')
		$searchYear=$yearEntered;
	if($monthDays==0)
	{
	if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')
	   ||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
		$monthDays=31;
	elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
			$monthDays=30;
		elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
			$monthDays=29;
			else
			$monthDays=28;
	}
	$k=1;
	while($k<=$monthDays)
	{
		$monthDaysArray[]=$k;
		$k++;
	}
	$date1 = $searchYear."-".$searchMonth."-01";
	$date2 = $searchYear."-".$searchMonth."-".$monthDays;
	$contactTrackingObj = new MIS_SUMMARY_RESPONSE_TRACKING;	
	$trackingData = $contactTrackingObj->getData($date1,$date2);
	foreach($trackingData as $k=>$v)
	{
		$trackingString = formatTrackingString($v['TRACKING_STRING']);
		list($yr,$mth,$dt) =explode("-",$v['DATE']);
		$dt = ltrim($dt,'0');
		$completeTrackingData[$trackingString][$dt][$v['CONTACT_TYPE']]=$v['COUNT'];
		$perDayTotal[$dt][$v['CONTACT_TYPE']]+=$v['COUNT'];
		$grandTotal[$v['CONTACT_TYPE']]+=$v['COUNT'];
		$perStringTotal[$trackingString][$v['CONTACT_TYPE']]+=$v['COUNT'];
	}
	if($CMDGo!='Y' && $CMDGo!='GO!')
        {
	        $k=-4;
                while($k<=5)
                {
                        $yearArray[]=$todYear+$k;
                        $k++;
                }

                $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		$smarty->assign('CHECKSUM',$checksum);
		$smarty->assign('yearArray',$yearArray);
		$smarty->assign('monthArray',$monthArray);
	}
	if($CMDGo=="GO!")
                $searchFlag=1;
	$smarty->assign("index",$index);
	$smarty->assign("grandTotal",$grandTotal);
	$smarty->assign("perDayTotal",$perDayTotal);
	$smarty->assign("perStringTotal",$perStringTotal);
	$smarty->assign("completeTrackingData",$completeTrackingData);
	$smarty->assign('searchFlag',$searchFlag);
	$smarty->assign('searchMonth',$searchMonth);
	$smarty->assign('searchYear',$searchYear);

	$smarty->assign('monthDaysArray',$monthDaysArray);
	$smarty->display("responseCount.htm");
}
else
{
	$smarty->assign('$user',$username);
	$smarty->display("jsconnectError.tpl");
}
function getTimeDiff1($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= $timestamp_diff / (24*60*60);
                return $days_diff;
        }
        elseif($date2 == $date1)
                return 0;
        else
                return -1;
}
function formatTrackingString($trackingString)
{
	$trackingStringArr = explode("-",$trackingString);
	$formattedString='';
	foreach($trackingStringArr as $k=>$v)
	{
		if($k!=0)
			$formattedString.="->";
		switch($v)
		{
			case JSTrackingPageType::OTHER:
				$formattedString.="OTHER";
				break;
			case JSTrackingPageType::CONTACT_AWAITING:
				$formattedString.="CONTACT_AWAITING";
				break;
			case JSTrackingPageType::PROFILE_PAGE:
				$formattedString.="PROFILE_PAGE";
				break;
			case JSTrackingPageType::CONTACT_OTHER:
				$formattedString.="CONTACT_OTHER";
				break;
			case JSTrackingPageType::SEARCH:
				$formattedString.="SEARCH";
				break;
			case JSTrackingPageType::MYJS_AWAITING:
				$formattedString.="MYJS_AWAITING";
				break;
			case JSTrackingPageType::EOI_MAILER:
				$formattedString.="EOI_MAILER";
				break;
			case JSTrackingPageType::YN_MAILER:
				$formattedString.="YN_MAILER";
				break;
			case JSTrackingPageType::EOI_FILTER_MAILER:
				$formattedString.="EOI_FILTER_MAILER";
				break;
			case JSTrackingPageType::MOBILE_AWAITING:
				$formattedString.="MOBILE_AWAITING";
				break;
			case JSTrackingPageType::SMS:
				$formattedString.="SMS";
				break;
			case JSTrackingPageType::MYJS_ANDROID_APP:
                                $formattedString.="MYJS_ANDROID_APP";
                                break;
			case JSTrackingPageType::GCM_PROFILE_PAGE:
				$formattedString.="GCM_PROFILE_PAGE";
				break;
			case JSTrackingPageType::PROFILE_PAGE_APP:
				$formattedString.="PROFILE_PAGE_APP";
				break;
			case JSTrackingPageType::INBOX_EOI_APP:
				$formattedString.="INBOX_EOI_APP";
				break;
			case JSTrackingPageType::SHORTLIST_APP:
				$formattedString.="SHORTLIST_APP";
				break;
			case JSTrackingPageType::MOBILE_FILTER:
				$formattedString.="MOBILE_FILTER";
				break;
			case JSTrackingPageType::SHORTLIST_JSMS:
				$formattedString.="SHORTLIST_JSMS";
				break;
			case JSTrackingPageType::MYJS_EOI_JSMS:
				$formattedString.="MYJS_EOI_JSMS";
				break;
			case JSTrackingPageType::PROFILE_PAGE_JSMS:
				$formattedString.="PROFILE_PAGE_JSMS";
				break;
			case JSTrackingPageType::PHONEBOOK_JSMS:
				$formattedString.="JSMS: Phonebook";
				break;
			case JSTrackingPageType::CONTACT_VIEWERS_JSMS:
				$formattedString.="JSMS: People who viewed my contacts";
				break;
			case JSTrackingPageType::FILTERED_INTEREST_ANDROID:
				$formattedString.="JS ANDROID: Filtered Interest";
				break;
			case JSTrackingPageType::FILTERED_INTEREST_JSMS:
				$formattedString.="JSMS: Filtered Interest";
				break;
			case JSTrackingPageType::CONTACTS_VIEWED_ANDROID:
				$formattedString.="JS Android: PhoneBook";
				break;
			case JSTrackingPageType::CONTACT_VIEWERS_ANDROID:
				$formattedString.="JS Android: People Who viewed My Contacts";
				break;
			case JSTrackingPageType::MOBILE_AWAITING_IOS:
				$formattedString.="JS IOS:AWAITING contact";
				break;
			case JSTrackingPageType::SHORTLIST_IOS:
				$formattedString.="JS IOS:SHORTLIST contact";
				break;
			case JSTrackingPageType::PHONEBOOK_IOS:
				$formattedString.="JS IOS:PHONEBOOK contact";
				break;
			case JSTrackingPageType::CONTACT_VIEWERS_IOS:
				$formattedString.="JS IOS:CONTACT_VIEWERS contact";
				break;
			case JSTrackingPageType::MYJS_EOI_IOS:
				$formattedString.="JS IOS:MYJS_EOI contact";
				break;
			case JSTrackingPageType::PROFILE_PAGE_IOS:
				$formattedString.="JS IOS:PROFILE_PAGE contact";
				break;
			case JSTrackingPageType::FILTERED_INTEREST_IOS:
				$formattedString.="JS IOS:Filtered Ios Interest";
				break;
			case JSTrackingPageType::EXCLUSIVE_SERVICE2_MAILER_RTYPE:
				$formattedString.="JS Exclusive Servicing II Mailer";
				break;
			case JSTrackingPageType::INTEREST_ARCHIVED:
				$formattedString.="INTEREST_ARCHIVED";
				break;
			case JSTrackingPageType::INTEREST_ARCHIVED_JSMS:
				$formattedString.="INTEREST_ARCHIVED_JSMS";
				break;
			case JSTrackingPageType::INTEREST_EXPIRING:
				$formattedString.="INTEREST_EXPIRING";
				break;
			case JSTrackingPageType::INTEREST_EXPIRING_JSPC_MYJS:
				$formattedString.="INTEREST_EXPIRING_JSPC_MYJS";
				break;
			case JSTrackingPageType::INTEREST_EXPIRING_JSMS:
				$formattedString.="INTEREST_EXPIRING_JSMS";
				break;

			case JSTrackingPageType::INTEREST_EXPIRING_IOS:
				$formattedString.="INTEREST_EXPIRING_IOS";
				break;

			case JSTrackingPageType::INTEREST_EXPIRING_MYJS_IOS:
				$formattedString.="INTEREST_EXPIRING_MYJS_IOS";
				break;

			case JSTrackingPageType::INTEREST_ARCHIVED_IOS:
				$formattedString.="INTEREST_ARCHIVED_IOS";
				break;
			case JSTrackingPageType::INTEREST_EXPIRING_ANDROID:
				$formattedString.="INTEREST_EXPIRING_ANDROID";
				break;
			case JSTrackingPageType::INTEREST_EXPIRING_ANDROID_MYJS:
				$formattedString.="INTEREST_EXPIRING_ANDROID_MYJS";
				break;
			case JSTrackingPageType::EXPIRING_INTEREST_MAILER:
				$formattedString.="EXPIRING_INTEREST_MAILER";
				break;
			case JSTrackingPageType::INTEREST_ARCHIVED_ANDROID:
				$formattedString.="INTEREST_ARCHIVED_ANDROID";
				break;
                        case JSTrackingPageType::PENDING_EOI_ANDROID:
                                $formattedString.="PENDING_EOI_ANDROID";
                                break;
                        case JSTrackingPageType::FILTERED_EOI_ANDROID:
                                $formattedString.="FILTERED_EOI_ANDROID";
                                break;
		}
	}
	return $formattedString;
}
?>
