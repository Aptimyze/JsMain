<?php
include("connect.inc");

//connect_db();

function authenticate_id($unique_id,$mailer_id)
{
	list($var1,$var2)=explode("i",$unique_id);
	if(md5($mailer_id)!=$var1 || $mailer_id!=$var2 || md5($var2)!=$var1)
		return 0;
	else
		return 1;	
}

$condition=authenticate_id($unique_id,$mailer_id);
/*
 * CODE ADDED BY NEHA on 1st october 2012: against Bug 63142 for individual resposne mailers unable to track open count as it is captured in MAIL_OPEN_INDIVIDUAL
 */
$sql = "SELECT RESPONSE_TYPE FROM mmmjs.MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
$res=mysql_query($sql) or die("$sql \n".mysql_error());
while($row=mysql_fetch_array($res)){
	$response_type = $row['RESPONSE_TYPE'];
}
/*
 * Code Added by Neha ends here
 */
if($condition)
{
	if($response_type == 'i'){ // condition added by Neha against bug 63142
		$sql="SELECT DATE,SUM(OPEN_COUNT) as OPEN_COUNT FROM mmmjs.MAIL_OPEN_INDIVIDUAL WHERE MAILER_ID='$mailer_id' GROUP BY DATE";
	}
	else {
		$sql="SELECT DATE,OPEN_COUNT,UN_COUNT FROM mmmjs.MAIL_UNSUBSCRIBE WHERE MAILER_ID='$mailer_id'";
	}
	
	$res=mysql_query($sql) or die("$sql \n".mysql_error());
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$k=$i+1;
		$mailer_arr[$i]["ID"]=$k;
		$mailer_arr[$i]["DATE"]=$row['DATE'];
		$mailer_arr[$i]["OPEN"]=$row['OPEN_COUNT'];
		if($response_type != 'i')
		$mailer_arr[$i]["UNSUBSCRIBE"]=$row['UN_COUNT'];
		$i++;
	}
//	print_r($mailer_arr);
	$mailer_name=getmailername($mailer_id);
        $sql="SELECT DATE,SENT FROM MAIL_SENT WHERE MAILER_ID='$mailer_id'";
        $res=mysql_query($sql) or die("$sql \n".mysql_error());
	$no_of_sent=0;
	while($row=mysql_fetch_array($res))
	{
		$no_of_sent=$no_of_sent+$row['SENT'];
	}
	$smarty->assign("mailer_id",$mailer_id);
	$smarty->assign("mailer_name",$mailer_name);
	$smarty->assign("no_of_sent",$no_of_sent);
	$smarty->assign("mailer_arr",$mailer_arr);
	$smarty->display("mmm_client_mis.htm");
}
else
{
	echo "ID INCORRECT";
}
?>
