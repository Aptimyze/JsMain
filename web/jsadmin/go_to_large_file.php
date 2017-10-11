<?php

include_once("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	$name = getname($cid);
	$date=date('Y-m-d');
	if($submit)
	{
		if($submit=='Activate Large File')
		{
			$leadid = date('dmy',time()+86400);
			$entry_dt = date('Y-m-d',time()+86400);
			$activation_time = date('dmy_his');

			$sql="INSERT INTO incentive.LARGE_FILE (DATA_LIMIT,LEAD_ID_SUFFIX,ENTRY_DT) VALUES ('$limit','$leadid','$entry_dt')";
			$res=mysql_query($sql) or die(mysql_error());

			//Noida,Mah,Delhi
      			$sql="SELECT * FROM incentive.IN_DIALER";
			$result=mysql_query($sql);
      			if($result)
      			{
				$sql="RENAME TABLE incentive.IN_DIALER TO incentive.IN_DIALER_$activation_time";
	                        mysql_query($sql,$db) or die("$sql".mysql_error($db));

				$sql="CREATE TABLE incentive.IN_DIALER (PROFILEID int(11) NOT NULL,ELIGIBLE char(2) NOT NULL DEFAULT 'Y', PRIORITY tinyint(2) DEFAULT NULL, ENTRY_DATE date DEFAULT '0000-00-00', PRIMARY KEY (PROFILEID))";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));
			}
			else
			{
				$sql="CREATE TABLE incentive.IN_DIALER (PROFILEID int(11) NOT NULL,ELIGIBLE char(2) NOT NULL DEFAULT 'Y', PRIORITY tinyint(2) DEFAULT NULL, ENTRY_DATE date DEFAULT '0000-00-00', PRIMARY KEY (PROFILEID))";
	                        mysql_query($sql,$db) or die("$sql".mysql_error($db));
			}
			//End

			//Renewal
			$sql="SELECT * FROM incentive.RENEWAL_IN_DIALER";
                        $result=mysql_query($sql);
                        if($result)
                        {
                                $sql="RENAME TABLE incentive.RENEWAL_IN_DIALER TO incentive.RENEWAL_IN_DIALER_$activation_time";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));

                                $sql="CREATE TABLE incentive.RENEWAL_IN_DIALER (PROFILEID int(11) NOT NULL,ELIGIBLE char(2) NOT NULL DEFAULT 'Y', PRIORITY tinyint(2) DEFAULT NULL, CAMPAIGN_TYPE char(30) DEFAULT '',ENTRY_DATE date DEFAULT '0000-00-00', PRIMARY KEY (PROFILEID))";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));
                        }
                        else
                        {
                                $sql="CREATE TABLE incentive.RENEWAL_IN_DIALER (PROFILEID int(11) NOT NULL,ELIGIBLE char(2) NOT NULL DEFAULT 'Y', PRIORITY tinyint(2) DEFAULT NULL, CAMPAIGN_TYPE char(30) DEFAULT '',ENTRY_DATE date DEFAULT '0000-00-00', PRIMARY KEY (PROFILEID))";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));
                        }
			//End
		}
		else
		{
			$sql="UPDATE incentive.LARGE_FILE SET DATA_LIMIT='$limit' WHERE ENTRY_DT='$entry_dt'";
                        $res=mysql_query($sql) or die(mysql_error());
		}
	}
	$sql="SELECT * FROM incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$limit = $row['DATA_LIMIT'];
	$leadid = $row['LEAD_ID_SUFFIX'];
	$entry_dt = $row['ENTRY_DT'];

	$smarty->assign('cid',$cid);
	$smarty->assign('limit',$limit);
	$smarty->assign('leadid',$leadid);
	$smarty->assign('entry_dt',$entry_dt);
	$smarty->assign('name',$name);
	
	$smarty->display("go_to_large_file.htm");
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
