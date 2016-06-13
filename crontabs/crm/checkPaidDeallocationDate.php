<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db_slave = connect_slave();
$db = connect_db();

$today=date("Y-m-d");
list($yy,$mm,$dd)=explode("-",$today);

$ts=time();
$ts-=2*24*60*60;
$dbyday=date("Y-m-d",$ts);

$upsellPrivilege ='%ExcUpS%';
$sql ="SELECT USERNAME from jsadmin.PSWRDS WHERE ACTIVE='Y' AND PRIVILAGE LIKE '$upsellPrivilege'";
$res = mysql_query($sql,$db_slave) or logError($sql);
while($row = mysql_fetch_array($res))
	$agentArr[] =$row['USERNAME'];

$sql_crm ="select m.PROFILEID,m.ALLOTED_TO from incentive.MAIN_ADMIN m,billing.PURCHASES p where p.ENTRY_DT >= '$dbyday' AND p.STATUS='DONE' AND p.PROFILEID=m.PROFILEID";
$res_crm = mysql_query($sql_crm,$db_slave) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{
	$allotedTo =$row_crm['ALLOTED_TO'];
	if(in_array("$allotedTo", $agentArr))
		continue;

        $profileid =$row_crm['PROFILEID'];
        $sql1 ="SELECT EXPIRY_DT from billing.SERVICE_STATUS where PROFILEID='$profileid' AND SERVEFOR LIKE '%F%' order by ID DESC LIMIT 1";
        $res1 = mysql_query($sql1,$db_slave) or logError($sql1);
        if($row1 = mysql_fetch_array($res1))
	{
        	$exp_dt =$row1['EXPIRY_DT']; 		       
	
	
		$sql2="SELECT MAX(ID) ID from incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid'";
		$res2=mysql_query($sql2,$db_slave) or logError($sql2);
		if($row2=mysql_fetch_array($res2))
			$id =$row2['ID'];	

		if($id){
			$deAllocDate=$exp_dt;
			$sql3 ="update incentive.CRM_DAILY_ALLOT set DE_ALLOCATION_DT='$deAllocDate' where ID='$id'";
			
			mysql_query($sql3,$db) or logError($sql3);
		}
	}
}
?>
