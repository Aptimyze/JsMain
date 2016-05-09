<?php
include('connect.inc');

$db=connect_db();
$sql="SELECT USERNAME,EDATE,DISCOUNT,EMAIL FROM billing.VARIABLE_DISCOUNT AS VD, newjs.JPROFILE as J WHERE VD.PROFILEID=J.PROFILEID AND SENT<>'Y'";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$username=$row['USERNAME'];
	$discount=$row['DISCOUNT'];
	$email=$row['EMAIL'];
	list($yy,$mm,$dd)= explode("-",$row['EDATE']);
	$timestamp= mktime(0,0,0,$mm,$dd,$yy);
        $edate=date('jS F',$timestamp);
	if($email && $discount && $row['EDATE'])
	{
		$smarty->assign('username',$username);
		$smarty->assign('discount',$discount);
		$smarty->assign('email',$email);
	}
	
}

?>
