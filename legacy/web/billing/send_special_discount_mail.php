<?php
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));
include_once ("../jsadmin/connect.inc");
if(!$_SERVER['DOCUMENT_ROOT'])
        $_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/authentication.class.php");
$db_slave=connect_slave81();
mysql_query("set session wait_timeout=1000",$db_slave);
$today=date('Y-m-d');
$smarty->assign('IMG_URL','http://static.jeevansathi.com');
$sql="SELECT PROFILEID,EDATE,DISCOUNT,SENT FROM billing.VARIABLE_DISCOUNT WHERE SDATE<='$today' AND EDATE>='$today' AND SENT<>'Y'";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
$i=0;
$db=connect_db();
mysql_query("set session wait_timeout=1000",$db);
while($row=mysql_fetch_array($res))
{
	$profileid=$row['PROFILEID'];
	$ARRAY[$profileid]=1;
}
$protect_obj=new protect;
foreach($ARRAY as $k=>$v)
{
	$pid=$k;
	$sql_sel="SELECT EDATE,DISCOUNT,SENT FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID='$pid' AND SDATE<='$today' AND EDATE>='$today' ORDER BY ENTRY_DT DESC LIMIT 1";
	$res_sel=mysql_query($sql_sel,$db_slave) or die(mysql_error1($sql_sel,$db_slave));
	$row_sel=mysql_fetch_assoc($res_sel);
	if($row_sel['SENT']!='Y')
	{
		$discount=$row_sel['DISCOUNT'];
		if($discount && $row_sel['EDATE'])
        	{
			$sql="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$pid' AND SUBSCRIPTION NOT LIKE '%F%'";
			$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
			$row=mysql_fetch_assoc($res);
			if($row['EMAIL'])
			{	
				list($yy,$mm,$dd)= explode("-",$row_sel['EDATE']);
				$timestamp= mktime(0,0,0,$mm,$dd,$yy);
				$edate=date('jS F Y',$timestamp);
				$username=$row['USERNAME'];
				$email=$row['EMAIL'];
				$smarty->assign('username',$username);
				$smarty->assign('discount',$discount);
				$smarty->assign('edate',$edate);
				$subject="Congratulations. Jeevansathi.com has selected you for a special discount.";
                                $profilechecksum=md5($pid)."i".$pid;
                                $echecksum=$protect_obj->js_encrypt($profilechecksum,$email);
                                $smarty->assign('echecksum',$echecksum);
                                $smarty->assign('profilechecksum',$profilechecksum);
			
				$sql_up="UPDATE billing.VARIABLE_DISCOUNT SET SENT='Y' WHERE PROFILEID='$pid' AND SDATE<='$today' AND EDATE>='$today' ORDER BY ENTRY_DT DESC LIMIT 1";
				$res_up=mysql_query($sql_up,$db) or die(mysql_error1($sql_up,$db));
				$msg=$smarty->fetch('variable_discount.htm');
				//echo $msg;
				send_email($email,$msg,$subject,$from);
			}
			
		}
	}
}	

function mysql_error1($sql,$db)
{
	echo $sql.mysql_error($db);
        mail("aman.sharma@jeevansathi.com","Error in populating variable discount csv",mysql_error($db));
}

?>
