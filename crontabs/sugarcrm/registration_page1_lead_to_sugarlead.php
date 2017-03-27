<?php 
$fromCron=1;
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];
require_once("$path/profile/connect.inc");
require_once("$path/sugarcrm/include/utils/Jscreate_lead.php");
$db_js=connect_slave();
mysql_select_db("sugarcrm",$db_js);
//$date=date('Y-m-d');
$date=date('Y-m-d',strtotime("-1 days"));
$start=$date." 00:00:00";
$end=$date." 23:59:59";

$sql="select * from newjs.REGISTRATION_PAGE1 where ENTRY_DT BETWEEN '$start' AND '$end' AND CONVERTED = 'N'";
$res=mysql_query_decide($sql,$db_js) or send_email("jaiswal.amit@jeevansathi.com","Problem in running reg_lead_to_sugarlead at line 12","Problem in reglead to sugarlead cron");
while($row=mysql_fetch_assoc($res)){
	$ldata=array();
	$ldata['last_name']=$row['EMAIL'];
	$ldata['email']=$row['EMAIL'];
	$ldata['gender_c']=$row['GENDER'];
	if($row['CASTE'])
		$ldata['caste_c']=$row['RELIGION']."_".$row['CASTE'];
	$ldata['date_birth_c']=$row['DTOFBIRTH'];
	$ldata['mother_tongue_c']=$row['MTONGUE'];
	$ldata['js_source_c']=$row['SOURCE'];
	$ldata['mobile1']=$row['PHONE_MOB'];
	$ldata['isd_c']=$row['ISD'];
	$ldata['dtofbirth_c']=$row['DTOFBIRTH'];
	$ldata['source_c']=12;
	$relation=$row['RELATION'];
	switch($relation){
		case '2':
		case '2D':
			$posted_by='2';
	        break;
		case '6':
	    case '6D':
			$posted_by='3';
			break;
		default:
			$posted_by=$relation;
	}
    $ldata['posted_by_c']=$posted_by;
    $ldata['opt_in_c']='1';
    $ldata['status']='13';
    $ldata['disposition_c']='24';
    $ldata['checkJprofile']='1';
	jscreate_lead($ldata);
}
?>
