<?php
	//created by lavesh
	ini_set('max_execution_time','0');
	include("connect.inc");
	connect_db();

	$sql = "SELECT RELATION,PARTNERID FROM JPARTNER WHERE RELATION<>''";
	$res = mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$rel=$row["RELATION"];
		$pid=$row["PARTNERID"];
		$sql1="INSERT IGNORE INTO PARTNER_RELATION VALUES('$pid','$rel')";
		$res1=mysql_query_decide($sql1) or die(mysql_error_js());

		if(in_array($rel,array(2,3,4,6)))
		{
			if($rel==2)
				$sql1="INSERT IGNORE INTO PARTNER_RELATION VALUES('$pid','3')";
			elseif($rel==3)			
				$sql1="INSERT IGNORE INTO PARTNER_RELATION VALUES('$pid','2')";
			elseif($rel==4)			
				$sql1="INSERT IGNORE INTO PARTNER_RELATION VALUES('$pid','6')";
			elseif($rel==6)			
				$sql1="INSERT IGNORE INTO PARTNER_RELATION VALUES('$pid','4')";		
			$res1=mysql_query_decide($sql1) or die(mysql_error_js());
		}
		
	}

?>
