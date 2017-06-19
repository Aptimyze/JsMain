<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	$db_master = connect_db();

        $prevDateTime = date("Y-m-d", time()-1*24*60*60);
	$incomeVal ='16';
	$familyIncome ='17';	
	$username =array();

	$sql="SELECT USERNAME from newjs.JPROFILE WHERE ENTRY_DT>='$prevDateTime 00:00:00' AND ENTRY_DT<='$prevDateTime 23:59:59' AND INCOMPLETE='N' AND (INCOME>'$incomeVal' OR FAMILY_INCOME>'$familyIncome')";
	$res=mysql_query($sql,$db_master) or die(mysql_error($db_master)); 
	while($row = mysql_fetch_array($res))
	{
		$username[] =$row['USERNAME'];		
	}	
	$usernameStr =implode(",",$username);

mail("manoj.rana@naukri.com,rohan.mathur@jeevansathi.com","E-Sathi calling profiles Mail", "$usernameStr");
//mail("manoj.rana@naukri.com","E-Sathi calling profiles Mail", "$usernameStr");
?>
