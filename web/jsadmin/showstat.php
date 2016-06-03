<?php 
ini_set("max_execution_time","0");
include_once("connect.inc");
include("../crm/mainmenunew.php");
include("../crm/viewprofilenew.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("../profile/functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$key = "SHOW_STAT_PAGE_".$name;
$name = "jstech";
$time = "5";

if (JsMemcache::getInstance()->get($key)) {
        JsMemcache::getInstance()->set($key, $name, $time);
        exit("Please refresh after 10 seconds.");
} else
        JsMemcache::getInstance()->set($key, $name, $time);

if(authenticated($cid))
{
        /*if(function_exists(connect_slave))
                $db_slave = connect_slave();
        else
                $db_slave = connect_db2();*/
	
	$name= getname($cid);	
	$privilage = getprivilage($cid);
	$priv = explode("+",$privilage);
	$smarty->assign("cid",$cid);
	if(in_array('BU',$priv) || in_array('BA',$priv)) //billing entry operator
        {
		$smarty->assign("BILLING","Y");
        }
	if(in_array('IUP',$priv)) //Aramex entry operator
        {
		$smarty->assign("ARAMEX","Y");
        }
	if(in_array('IUI',$priv) || in_array('FTA',$priv) || in_array('EdtDPP',$priv)) //Inbound calls operator
        {
		$smarty->assign("INBOUND_OPERATOR","Y");
        }
	if(in_array('OA',$priv) || in_array('OB',$priv))
		$smarty->assign("OFFLINE_EXECUTIVE",1);

	$sql="SELECT INCOMPLETE,USERNAME,GENDER from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql);
	$myrow=mysql_fetch_array($result);
	$username=$myrow['USERNAME'];
	$incomplete=$myrow['INCOMPLETE'];
	$smarty->assign("CRM_GENDER",$myrow['GENDER']);
	$checksum=md5($profileid)."i".$profileid;
	$pmsg=viewprofile($username,"internal",$priv); 
	if($incomplete=='Y')
	{
		$isql="select REASON from jsadmin.INCOMPLETE where PROFILEID='$profileid'";
		$iresult=mysql_query_decide($isql) or die(mysql_error_js());
		if($irow=mysql_fetch_array($iresult))
		{
			$why_inc=nl2br($irow['REASON']);
		}
		$smarty->assign("INCOMPLETE","Y");
		$smarty->assign("WHY_INC",$why_inc);
	}
	profileview($profileid,$checksum,$priv,$cid);
        $msg= $smarty->fetch("../crm/login1.tpl");
	$smarty->assign("SCREENED_BY",$screened_by);	
	
	$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$profileid);
	$iPCS = $cScoreObject->getProfileCompletionScore();
    $incentiveMAINADMINObj = new incentive_MAIN_ADMIN();
    $allotedAgent = $incentiveMAINADMINObj->getAllotedExecForProfile($profileid);
    if($allotedAgent == $name)
        $smarty->assign("ISALLOTED", "Y");
	$smarty->assign("PROFILE_PERCENT",$iPCS);
	$smarty->assign("USERNAME",$username);
	$smarty->assign("profileChecksum",$checksum);
	$smarty->assign("pid",$profileid);
	$smarty->assign("msg",$msg);
	$smarty->assign("pmsg",$pmsg);
	if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://crm.jeevansathi.com'){
		$machineURL = 'http://www.jeevansathi.com';
	} else {
		$machineURL = JsConstants::$siteUrl;
	}
	$smarty->assign('machineURL',$machineURL);
	//$smarty->assign("cid",$cid);
	$smarty->display("showstat.htm");
}
else
{
	$msg="Your session has been timed out<br> <br> ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
