<?php
include ("connect.inc");
if(authenticated($cid))
{
	$operator_name=getname($cid);
	$smarty->assign("cid",$cid);
	$smarty->assign("operator_name",$operator_name);
}

$db=connect_db();
mysql_select_db_js('billing');
$user=$_GET["username"];
$cond=0;$donc=0;
if($user&&$check==1)
{
	$sql="select * from newjs.CLIENT_FEEDBACK where id =$sr";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$therow=mysql_fetch_array($result);
	$SERVICE_DT=explode("-",$therow[SERVICE_DATE]);
	$smarty->assign("ID",$therow[ID]);
	$smarty->assign("USERNAME",$therow[USERNAME]);
	$smarty->assign("PROFILEID",$therow[PROFILEID]);
	$smarty->assign("EXECUTIVE_NAME",$therow[EXECUTIVE_NAME]);
	$smarty->assign("DURATION",$therow[DURATION]);
	$smarty->assign("SERVICE_NO",$therow[SERVICE_NO]);
	$smarty->assign("SERVICE_DATE",$SERVICE_DT);
	$smarty->assign("NO_PP",$therow[NO_PP]);
	$smarty->assign("PP_IDS",$therow[PP_IDS]);
	$smarty->assign("Q1",$therow[Q1]);
	$smarty->assign("Q2",$therow[Q2]);
	$smarty->assign("Q3",$therow[Q3]);
	$smarty->assign("Q4",$therow[Q4]);
	$smarty->assign("Q5",$therow[Q5]);
	$smarty->assign("Q6",$therow[Q6]);
	$smarty->assign("CHANGES_DPP",$therow[CHANGES_DPP]);

	$smarty->display("client_feedback.htm");		
	exit;
}
if($user&&$check==0)		
{
	$cond=1;
	setcookie("INVALID","0",0,"/",$domain);
	$sql="select * from PURCHASES where USERNAME ='$user' ";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
}
else if($_POST["check"]=='0')
{	
	 $cookie_value=$_COOKIE['INVALID'];
	 settype($cookie_value,"integer");
	 if($cookie_value!=2)
	 {

		foreach($_POST as $var)
			addslashes(stripslashes($var));	
		$dos=$_POST[yeardropdown]."-".$_POST[monthdropdown]."-".$_POST[daydropdown];
		mysql_select_db_js('newjs');
		$sql="insert into CLIENT_FEEDBACK(EXECUTIVE_NAME,DURATION,SERVICE_NO,SERVICE_DATE,NO_PP,PP_IDS,Q1,Q2,Q3,Q4,Q5,Q6,CHANGES_DPP,PROFILEID,USERNAME) values ('$operator_name','$_POST[duration]','$_POST[ser_no]','$dos','$_POST[profile_no]','$_POST[ppi]','$_POST[pp1]','$_POST[pp2]','$_POST[pp3]','$_POST[pp4]','$_POST[pp5]','$_POST[pp6]','$_POST[pp7]','$_POST[profileid]','$_POST[usrname]')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");	
		$donc=1;
		setcookie("INVALID","2",0,"/",$domain);
	}
}
else if($_POST["check"]=='1')
{
	foreach($_POST as $var)
		addslashes(stripslashes($var));
	 $dos=$_POST[yeardropdown]."-".$_POST[monthdropdown]."-".$_POST[daydropdown];
	mysql_select_db_js('newjs');
	$sql="update CLIENT_FEEDBACK SET SERVICE_NO='$_POST[ser_no]',SERVICE_DATE='$dos',NO_PP='$_POST[profile_no]',PP_IDS='$_POST[ppi]',Q1='$_POST[pp1]',Q2='$_POST[pp2]',Q3='$_POST[pp3]',Q4='$_POST[pp4]',Q5='$_POST[pp5]',Q6='$_POST[pp6]',CHANGES_DPP='$_POST[pp7]' WHERE ID ='$_POST[ID]'";
	 mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$donc=2;
}
if($myrow["PROFILEID"])
{
	mysql_select_db_js('newjs');
	$sql="select * from CLIENT_FEEDBACK where USERNAME ='$user' ";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$incre =1;
	if($result)
       while($datarow=mysql_fetch_array($result)) 
	{
		$incre++;
	}
	mysql_select_db_js('billing');
	$sid=$myrow["SERVICEID"];
	$sql="select * from SERVICES where SERVICEID='$sid' ";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($result);
	$exp =explode("-",$row["NAME"]);
	$size=count($exp);
//	echo $size;
	if($row["NAME"]=='M'||$row["NAME"]=='M2')
		$smarty->assign("DURATION",'LIFE-TIME');
	else
		$smarty->assign("DURATION", $exp[$size-1]);
	$smarty->assign("SERVE",$incre);
	$smarty->assign("USERNAME",$user);
	$smarty->assign("PROFILEID",$myrow["PROFILEID"]);
	$smarty->display('client_feedback.html');
}
else
{
	$smarty->assign("INVALID",$INVALID);
	$smarty->assign("DONC",$donc);
	$smarty->assign("COND",$cond);
	$smarty->display('client.htm');
}
?>


