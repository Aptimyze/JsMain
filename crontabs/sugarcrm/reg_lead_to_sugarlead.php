<?php 
$fromCron=1;
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];
require_once("$path/profile/connect.inc");
require_once("$path/sugarcrm/include/utils/Jscreate_lead.php");
$db_js=connect_db();
mysql_select_db("sugarcrm",$db_js);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_js);
$date=date('Y-m-d',JSstrToTime("-1 days"));
$start=$date." 00:00:00";
$end=$date." 23:59:59";

$sql="select * from MIS.REG_LEAD where ENTRY_DT BETWEEN '$start' AND '$end' AND LEAD_CONVERSION = 'N'";
$res=mysql_query_decide($sql,$db_js) or send_email("nikhil.dhiman@jeevansathi.com","Problem in running reg_lead_to_sugarlead at line 12","Problem in reglead to sugarlead cron");
while($row=mysql_fetch_assoc($res)){
	$ldata=array();
	$ldata['last_name']=$row['EMAIL'];
	$ldata['email']=$row['EMAIL'];
	$ldata['gender_c']=$row['GENDER'];
	if($row['CASTE'])
		$ldata['caste_c']=$row['RELIGION']."_".$row['CASTE'];
	$ldata['religion_c']=$row['RELIGION'];
	$ldata['date_birth_c']=$row['DTOFBIRTH'];
	$ldata['mother_tongue_c']=$row['MTONGUE'];
	$ldata['js_source_c']=$row['SOURCE'];
	if($row['SOURCE'] && $row['SOURCE']!="unknown"){
		$sql_source="select GROUPNAME from MIS.SOURCE where SourceID='".$row['SOURCE']."'";
		$res_source=mysql_query_decide($sql_source,$db_js) or send_email("jaiswal.amit@jeevansathi.com","Problem in problem related to fetching source group","Problem in reglead to sugarlead cron");
		$row_source=mysql_fetch_assoc($res_source);
					if(strpos($row_source['GROUPNAME'],"SEO") !==false || strpos($row_source['GROUPNAME'],"jeevansathi") !==false || strpos($row_source['GROUPNAME'],"unknown") !==false)
						$ldata['source_c']=18;
					elseif(strpos($row_source['GROUPNAME'],"mobiledirect") !==false)
						$ldata['source_c']=20;
					else
						$ldata['source_c']=19;
	}
	else
		$ldata['source_c']=18;
	$ldata['mobile1']=$row['MOBILE'];
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
send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","sugarcrm reg_lead cron","Successfull reg_lead cron");
?>
