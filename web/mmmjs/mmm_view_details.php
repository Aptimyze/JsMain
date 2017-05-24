<?
//include "connect.inc";
include "mmm_view_template.php" ;
//include "arrays.php";
echo "MAILERID : ";
echo $mailer_id;echo "<br>";

include_once(JsConstants::$smartyDir);
$smarty=new Smarty;

$smarty->setTemplateDir(JsConstants::$alertDocRoot."/mmmjs/templates2");
$smarty->setCompileDir(JsConstants::$alertDocRoot."/mmmjs/templates_c2");

$db=@mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or logerror1("In connect at connecting db","");

@mysql_select_db("mmmjs",$db);



//// THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE///////////////////////

/*
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid,$ip))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
*/


//*********MAIN MODULE STARTS HERE*****************************8//

$sql="SELECT * FROM MAIN_MAILER  WHERE MAILER_ID=$mailer_id";
$result=mysql_query($sql) or die("could not select values from main mailer table".mysql_error());
$row=mysql_fetch_array($result);
$mailer_name=$row['MAILER_NAME'];
$client_name=$row['CLIENT_NAME'];
$mail_type=$row['MAIL_TYPE'];
$sub_query=$row['SUB_QUERY'];
$response_type=$row['RESPONSE_TYPE'];
$state=$row['STATE'];
$period_of_stay=$row['PERIOD_OF_STAY'];
$company_name=$row['COMPANY_NAME'];
$city=$row['CITY'];
$test=$row['TEST'];
$retest=$row['RETEST'];
$s1_fire=$row['S1_FIRE'];
$s2_fire=$row['S2_FIRE'];
$s1_fired=$row['S1_FIRED'];
$s2_fired=$row['S2_FIRED'];
$stop=$row['STOP'];
$sent=$row['SENT'];
$del=$row['DEL'];

//************GET ALL TEMPLATE OF A PARTICULAR MAILER ID WHOSE TEMPLATE IS NOT ACTIVE*******//
$sql="SELECT * FROM MAIL_DATA  WHERE MAILER_ID=$mailer_id AND ACTIVE!='Y'";
$result=mysql_query($sql) or die("could not select values from mail data table".mysql_error());
while($row=mysql_fetch_array($result))
{
	$arr[]=array( "mailer_id"=>$row['MAILER_ID'],
			"template_name"=>$row['TEMPLATE_NAME'],
        		"f_email"=>$row['F_EMAIL'],
                        "subject"=>$row['SUBJECT'],
			"date"=>$row['DATA'],
			"active"=>$row['ACTIVE']);
}

if($flag!="change")
{
	$sql="SELECT * FROM MAIL_DATA  WHERE MAILER_ID=$mailer_id AND ACTIVE='Y'";
	$result=mysql_query($sql) or die("could not select values from mail data table".mysql_error());
	$row=mysql_fetch_array($result);
	$template_name=$row['TEMPLATE_NAME'];	
	$f_email=$row['F_EMAIL'];
	$subject=$row['SUBJECT'];
	$data=$row['DATA'];
}
else
{
        $sql="SELECT * FROM MAIL_DATA  WHERE MAILER_ID=$mailer_id AND TEMPLATE_NAME='$tempname1'";
        $result=mysql_query($sql) or die("could not select values from mail data table".mysql_error());
        $row=mysql_fetch_array($result);
        $template_name=$row['TEMPLATE_NAME'];
        $f_email=$row['F_EMAIL'];
        $subject=$row['SUBJECT'];
        $data=$row['DATA'];
echo "SHOW_TEMPLATE : ".$show_template;

}


$smarty->assign("mailer_name",$mailer_name);
$smarty->assign("client_name",$client_name);
$smarty->assign("mail_type",$mail_type);
$smarty->assign("sub_query",$sub_query);
$smarty->assign("response_type",$response_type);
$smarty->assign("state",$state);
$smarty->assign("period_of_stay",$period_of_stay);
$smarty->assign("city",$city);
$smarty->assign("test",$test);
$smarty->assign("retest",$retest);
$smarty->assign("s1_fire",$s1_fire);
$smarty->assign("s2_fire",$s2_fire);
$smarty->assign("s1_fired",$s1_fired);
$smarty->assign("s2_fired",$s2_fired);
$smarty->assign("stop",$stop);
$smarty->assign("sent",$sent);
$smarty->assign("del",$del);

$smarty->assign("arr",$arr);
$smarty->assign("mailer_id",$mailer_id);
$smarty->assign("tempname1",stripslashes($tempname1));
$smarty->assign("f_email",$f_email);
$smarty->assign("subject",$subject);

//############################

$data=mmm_view_template($mailer_id,$data);

//##############################
$smarty->assign("data",$data);
//*****This part is for hiding activate button in view details window where mailer is not in complete or tested phase********//
if($show_template=='N')
	$smarty->assign("show_activate","N");

$smarty->assign("cid",$cid);
$smarty->template_dir=JsConstants::$alertDocRoot."/mmmjs/templates";
$smarty->display("mmm_view_details.htm");
?>
