<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
$path=$_SERVER['DOCUMENT_ROOT'];
include_once("../connect.inc");
include_once("$path/profile/config.php");
include_once("$path/crm/negativeListFlagArray.php");
include_once("$path/classes/NEGATIVE_TREATMENT_LIST.class.php");

$db	=connect_db();
$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db);

$sql="INSERT IGNORE INTO incentive.NEGATIVE_TREATMENT_LIST(PROFILEID,ENTRY_BY,TYPE,ENTRY_DT) SELECT  PROFILEID,'system','Non-serious Profile',now() FROM jsadmin.NON_SERIOUS_PROFILES";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="INSERT IGNORE INTO incentive.NEGATIVE_TREATMENT_LIST(PROFILEID,ENTRY_DT,ENTRY_BY,TYPE) SELECT  SPAMMER,SPAM_DATE,'system','Spammer' FROM jsadmin.SPAMMERS WHERE SPAM_DATE>='2010-01-01'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql ="select PROFILEID,TYPE,ENTRY_BY,DATE_FORMAT(ENTRY_DT,'%Y-%m-%d') AS ENTRY_DT from incentive.NEGATIVE_PROFILE_LIST WHERE PROFILEID!='' ORDER BY ID ASC";
$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{

	$profileid =$row['PROFILEID'];
	$type	   =$row['TYPE'];	
	$name  	   =$row['ENTRY_BY'];
	$entry_dt  =$row['ENTRY_DT'];
	$addParameters =array();

        $f_viewable     =$negativeListFlagArray["$type"]['FLAG_VIEWABLE'];
        $f_inbox_eoi    =$negativeListFlagArray["$type"]['FLAG_INBOX_EOI'];
        $f_contact      =$negativeListFlagArray["$type"]['FLAG_CONTACT_DETAIL'];
        $f_outbound     =$negativeListFlagArray["$type"]['FLAG_OUTBOUND_CALL'];
        $f_inbound      =$negativeListFlagArray["$type"]['FLAG_INBOUND_CALL'];
	$f_chat_init    =$negativeListFlagArray["$type"]['CHAT_INITIATION'];	
	
        $sqlTreatmentList ="select PROFILEID from incentive.NEGATIVE_TREATMENT_LIST where PROFILEID='$profileid'";
        $resTreatmentList =mysql_query_decide($sqlTreatmentList,$db) or die("$sqlTreatmentList".mysql_error_js());
        $rowTreatmentList =mysql_fetch_array($resTreatmentList);
        $pidTreatmentList =$rowTreatmentList['PROFILEID'];

	if($pidTreatmentList){
		if($f_viewable=='N')
			$addParameters['FLAG_VIEWABLE']		=$f_viewable;
		if($f_inbox_eoi=='N')
			$addParameters['FLAG_INBOX_EOI']	=$f_inbox_eoi;
		if($f_contact=='N')
			$addParameters['FLAG_CONTACT_DETAIL']	=$f_contact;
		if($f_outbound=='N')
			$addParameters['FLAG_OUTBOUND_CALL']	=$f_outbound;
		if($f_inbound=='N')
			$addParameters['FLAG_INBOUND_CALL']	=$f_inbound;
		if($f_chat_init=='N')
			$addParameters['CHAT_INITIATION']	=$f_chat_init;

		$addParameters['TYPE']="$type";
                $NEGATIVE_TREATMENT_LIST->UpdateRecords($addParameters,$profileid);
	}
	else{
		$addParameters["TYPE"]			=$type;
		$addParameters["ENTRY_BY"]		=$name;
		$addParameters["ENTRY_DT"]              =$entry_dt;

		$addParameters["FLAG_VIEWABLE"]		=$f_viewable;
		$addParameters["FLAG_INBOX_EOI"]	=$f_inbox_eoi;
		$addParameters["FLAG_CONTACT_DETAIL"]	=$f_contact;
		$addParameters["FLAG_OUTBOUND_CALL"]	=$f_outbound;
		$addParameters["FLAG_INBOUND_CALL"]	=$f_inbound;
		$addParameters["CHAT_INITIATION"]	=$f_chat_init;
		$NEGATIVE_TREATMENT_LIST->addRecords($addParameters,$profileid);
	}
}

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_FEMALE B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_MALE B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_FEMALE_TEXT B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_MALE_TEXT B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_FEMALE_REV B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql="DELETE B.* FROM incentive.NEGATIVE_TREATMENT_LIST A, newjs.SEARCH_MALE_REV B WHERE A.PROFILEID = B.PROFILEID AND FLAG_VIEWABLE='N'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql ="UPDATE incentive.NEGATIVE_TREATMENT_LIST SET TYPE='Block Inbound Call' WHERE TYPE='Abusive Caller'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql ="UPDATE incentive.NEGATIVE_TREATMENT_LIST SET TYPE='Abusive chat with other members' WHERE TYPE='Abusive Chat'";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

$sql ="UPDATE incentive.NEGATIVE_TREATMENT_LIST SET TYPE='Others' WHERE TYPE=''";
mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

?>
