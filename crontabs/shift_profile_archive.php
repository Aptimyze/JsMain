<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
ini_set("memory_limit",-1);
include("$docRoot/crontabs/connect.inc");
include("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include("$_SERVER[DOCUMENT_ROOT]/classes/class.rc4crypt.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("$_SERVER[DOCUMENT_ROOT]/profile/connect_functions.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);

$curdate=time();


//If config file is not setting this variables.
if(!$SITE_URL_JS)
	$SITE_URL_JS="http://www.jeevansathi.com";

//Getting the date on which i have to send mail
$month_10  = mktime(0, 0, 0, date("m")-10  , date("d"), date("Y"));
$month_11=mktime(0, 0, 0, date("m")-11  , date("d"), date("Y"));
$last_week=mktime(0, 0, 0, date("m")-12  , date("d")+7, date("Y"));
$month_12=mktime(0, 0, 0, date("m")-12  , date("d"), date("Y"));

$query_limit_month=date("Y-m-d",$month_10);
//Getting diff in correct manner(in numericals.
$month_10=floor(($curdate-$month_10)/(60*60*24));
$month_11=floor(($curdate-$month_11)/(60*60*24));
$month_12=floor(($curdate-$month_12)/(60*60*24));
$last_week=floor(($curdate-$last_week)/(60*60*24));

//$last_week=mktime(0, 0, 0, date("m")-12  , date("d"), date("Y"));

$sql="select PROFILEID,EMAIL,USERNAME,datediff(now(),LAST_LOGIN_DT) as dd,SUBSCRIPTION  from newjs.JPROFILE where DATE(LAST_LOGIN_DT) < DATE_SUB(CURDATE(), INTERVAL 10 MONTH) and activatedKey=1 and ACTIVATED<>'D' limit 1";
//$sql="select PROFILEID,EMAIL,USERNAME,datediff(now(),LAST_LOGIN_DT) as dd,SUBSCRIPTION  from newjs.JPROFILE where datediff(now(),LAST_LOGIN_DT) >=$month_10 and activatedKey=1 and ACTIVATED<>'D' and PROFILEID=618185";
//$sql="select PROFILEID,EMAIL,USERNAME,datediff(now(),LAST_LOGIN_DT) as dd from newjs.JPROFILE where PROFILEID=136580";
$res=mysql_query($sql,$dbSlave) or die(mysql_error1(mysql_error($dbSlave).$sql));

$dbM=connect_db(); //Master connection.
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

while($row=mysql_fetch_assoc($res))
{
	$datediff=$row['dd'];
	$profileid=$row['PROFILEID'];
	$email=$row['EMAIL'];
	$username=$row['USERNAME'];
	$subscription=isPaid($row['SUBSCRIPTION']);
	if($datediff>=$month_10 && $datediff<$month_11)
        {
                $ten[$profileid]=array($profileid,$email,$username,$subscription);
                $ten_sink[]=$profileid;
        }
        elseif($datediff>=$month_11 && $datediff<$last_week)
        {
                $eleven[$profileid]=array($profileid,$email,$username,$subscription);
                $eleven_sink[]=$profileid;

        }
        elseif($datediff>=$last_week && $datediff<$month_12)
        {
                $lsweek[$profileid]=array($profileid,$email,$username,$subscription);
                $lsweek_sink[]=$profileid;
        }
        elseif($datediff>=$month_12)
        {
                $archive_it[$profileid]=array($profileid,$email,$username,$subscription);
                //$month_12[]=$profileid;

        }
}


if(count($ten))
{
	$ten_prof=implode(",",$ten_sink);
 	$sql="select PROFILEID from MIS.INACTIVE_USERS where PROFILEID IN($ten_prof) and SEN_DATE > DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and TYPE_MAIL=1";
	$res=mysql_query($sql,$dbSlave) or die(mysql_error1(mysql_error($dbSlave).$sql));
	while($row=mysql_fetch_assoc($res))
	{
		unset($ten[$row['PROFILEID']]);
	}
}
if(count($eleven))
{
        $ten_prof=implode(",",$eleven_sink);
        $sql="select PROFILEID from MIS.INACTIVE_USERS where PROFILEID IN($ten_prof) and SEN_DATE > DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and TYPE_MAIL=2";
        $res=mysql_query($sql,$dbSlave) or die(mysql_error1(mysql_error($dbSlave).$sql));
        while($row=mysql_fetch_assoc($res))
        {
                unset($eleven[$row['PROFILEID']]);
        }
}
if(count($lsweek))
{
        $ten_prof=implode(",",$lsweek_sink);
        $sql="select PROFILEID from MIS.INACTIVE_USERS where PROFILEID IN($ten_prof) and SEN_DATE > DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and TYPE_MAIL=3";
        $res=mysql_query($sql,$dbSlave) or die(mysql_error1(mysql_error($dbSlave).$sql));
        while($row=mysql_fetch_assoc($res))
        {
                unset($lsweek[$row['PROFILEID']]);
        }
}

mysql_close();
$dbM=connect_db(); //can be slave as well.
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
if(count($ten))
{
	foreach($ten as $key=>$val)
	{
		$profileid=$val[0];
		$email=$val[1];
		$username=$val[2];
		$subscription=$val[3];
		$to=$email;
	        //$to='dhiman_nikhil@yahoo.com';
		$subject="We miss you on Jeevansathi.com";
		$from="webmaster@jeevansathi.com";
		$message='<div>&nbsp;&nbsp;&nbsp; Hi User,
  <BR><BR></div>
<div style="margin-left:60px">
   Greetings from Jeevansathi.com. At Jeevansathi.com, we strive to make your search for a life partner easy and convenient.
  <BR><BR>
   
</div>
<div style="margin-left:60px">We have observed that it has been 10 months since you have logged into Jeevansathi.com. We would like to inform you that by not logging in regularly your profile is no longer visible to over 2 million Jeevansathi.com users. We want to be sure that you are still in the process of finding a life partner.
  <BR><BR></div>
<div style="margin-left:60px">
  In order to keep your account active and appear in the search results, please <a href="'.$SITE_URL_JS.'/profile/login.php?from_month=10">Login here</a>. 
  <br>
  <br></div>
';
if(!$subscription)
$message.='<div style="margin-left:60px">You may also upgrade your membership to eValue or eRishta membership.</div>
<div style="margin-left:60px"> Benefits of upgrading your membership are: 
  <br>
  <br>
<div style="margin-left:60px">
      a. &nbsp;&nbsp;View phone numbers and email id\'s of members
    </div>
<div style="margin-left:60px">
      b. &nbsp;&nbsp;Initiate and respond to Chats
    </div>
<div style="margin-left:60px">
      c. &nbsp;&nbsp;Send personalized messages
    </div>
<div style="margin-left:60px">
      d. &nbsp;&nbsp;Get more visibility as a featured profile 
      <br>
      <br>
    </div>
<div  style="margin-left:100px;width:230px" align="left" ><a href="'.$SITE_URL_JS.'/profile/mem_comparison.php?from_source=archived_10" title="Register FREE" style="text-decoration:none;font-size:15px;display:block;border:1px solid #cccccc;padding:4px;background-color:red;color:#ffffff" target="_blank">Become a paid member Now</a><BR></div>
</div>';
$message.='<div style="margin-left:60px">
      If you have already found your match, we thank you for using Jeevansathi.com and wish you all the best for your happy married life. We would like to invite you to submit your Success story with us and also delete your profile from Jeevansathi.com. Please <a href="'.$SITE_URL_JS.'/profile/hide_delete_revamp.php">Click Here</a>.
      <br>
      <br>
    </div>

<div align="left" style="margin-left:60px">Warm Regards,</div>
<div align="left" style="margin-left:60px">Jeevansathi.com Team.</div>
';

	send_email($to,$message,$subject,$from);
	//Entry into inactive users db.
	$dated=date("Y-m-d");
	$sql="insert ignore into MIS.INACTIVE_USERS (PROFILEID,SEN_DATE,TYPE_MAIL) values($profileid,'$dated',1)";
	mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
	}
}
if(count($eleven))
{
	foreach($eleven as $key=>$val)
	{
		$profileid=$val[0];
		$email=$val[1];
		$username=$val[2];
		$subscription=$val[3];
		$to=$email;
 		//$to='dhiman_nikhil@yahoo.com';
		$subject="We miss you on Jeevansathi.com";
		$from="webmaster@jeevansathi.com";
		$message='<div>&nbsp;&nbsp;&nbsp; Hi User,
  <BR><BR></div>
<div style="margin-left:60px">
   Greetings from Jeevansathi.com. At Jeevansathi.com, we strive to make your search for a life partner easy and convenient.
  <BR><BR>
   
</div>
<div style="margin-left:60px">We have observed that it has been 11 months since you have logged into Jeevansathi.com. We would like to inform you that by not logging in regularly your profile is no longer visible to over 2 million Jeevansathi.com users. We want to be sure that you are still in the process of finding a life partner.
  <BR><BR></div>
<div style="margin-left:60px">
  In order to keep your account active and appear in the search results, please <a href="'.$SITE_URL_JS.'/profile/login.php?from_month=11">Login here</a>. 
  <br>
  <br>
</div>
';
if(!$subscription)
$message.='<div style="margin-left:60px">You may also upgrade your membership to eValue or eRishta membership.</div>
<div style="margin-left:60px"> Benefits of upgrading your membership are: 
  <br>
  <br>
<div style="margin-left:60px">
      a. &nbsp;&nbsp;View phone numbers and email id\'s of members
    </div>
<div style="margin-left:60px">
      b. &nbsp;&nbsp;Initiate and respond to Chats
    </div>
<div style="margin-left:60px">
      c. &nbsp;&nbsp;Send personalized messages
    </div>
<div style="margin-left:60px">
      d. &nbsp;&nbsp;Get more visibility as a featured profile 
      <br>
      <br>
    </div>
<div  style="margin-left:100px;width:230px" align="left" ><a href="'.$SITE_URL_JS.'/profile/mem_comparison.php?from_source=archived_10" title="Register FREE" style="text-decoration:none;font-size:15px;display:block;border:1px solid #cccccc;padding:4px;background-color:red;color:#ffffff" target="_blank">Become a paid member Now</a><BR></div>
</div>';
$message.='<div style="margin-left:60px">
      If you have already found your match, we thank you for using Jeevansathi.com and wish you all the best for your happy married life. We would like to invite you to submit your Success story with us and also delete your profile from Jeevansathi.com. Please <a href="'.$SITE_URL_JS.'/profile/hide_delete_revamp.php">Click Here</a>.
      <br>
      <br>
    </div>

<div align="left" style="margin-left:60px">Warm Regards,</div>
<div align="left" style="margin-left:60px">Jeevansathi.com Team.</div>';
	send_email($to,$message,$subject,$from);
	//Entry into inactive users db.
	$dated=date("Y-m-d");
	$sql="insert ignore into MIS.INACTIVE_USERS (PROFILEID,SEN_DATE,TYPE_MAIL) values($profileid,'$dated',2)";
	mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
	}
}
if(count($lsweek))
{
	foreach($lsweek as $key=>$val)
	{
		$profileid=$val[0];
		$email=$val[1];
		$username=$val[2];
		$subscription=$val[3];
		$to=$email;
		// $to='dhiman_nikihil@yahoo.com';
		$subject="We miss you on Jeevansathi.com";
		$from="webmaster@jeevansathi.com";
		$message='<div>&nbsp;&nbsp;&nbsp; Hi User,
  <BR><BR></div>
<div style="margin-left:60px">
   Greetings from Jeevansathi.com. At Jeevansathi.com, we strive to make your search for a life partner easy and convenient.
  <BR><BR>
   
</div>
<div style="margin-left:60px">We have observed that it has almost 12 months since you have logged into Jeevansathi.com. We would like to inform you that by not logging in regularly your profile is no longer visible to over 2 million Jeevansathi.com users. We want to be sure that you are still in the process of finding a life partner.
  <BR><BR></div>
<div style="margin-left:60px">
In the event of not logging in the next 7 days, we will be marking your account as inactive, and all information pertaining to your account will be lost.  In order to keep your account active and appear in the search results, please <a href="'.$SITE_URL_JS.'/profile/login.php?from_month=ls_week">Login here</a>. 
  <br>
  <br>
</div>';
if(!$subscription)
$message.='<div style="margin-left:60px">You may also upgrade your membership to eValue or eRishta membership.</div>
<div style="margin-left:60px"> Benefits of upgrading your membership are: 
  <br>
  <br>
<div style="margin-left:60px">
      a. &nbsp;&nbsp;View phone numbers and email id\'s of members
    </div>
<div style="margin-left:60px">
      b. &nbsp;&nbsp;Initiate and respond to Chats
    </div>
<div style="margin-left:60px">
      c. &nbsp;&nbsp;Send personalized messages
    </div>
<div style="margin-left:60px">
      d. &nbsp;&nbsp;Get more visibility as a featured profile 
      <br>
      <br>
    </div>
<div  style="margin-left:100px;width:230px" align="left" ><a href="'.$SITE_URL_JS.'/profile/mem_comparison.php?from_source=archived_lsweek" title="Register FREE" style="text-decoration:none;font-size:15px;display:block;border:1px solid #cccccc;padding:4px;background-color:red;color:#ffffff" target="_blank">Become a paid member Now</a><BR></div>
</div>';
$message.='<div style="margin-left:60px">
      If you have already found your match, we thank you for using Jeevansathi.com and wish you all the best for your happy married life. We would like to invite you to submit your Success story with us and also delete your profile from Jeevansathi.com. Please <a href="'.$SITE_URL_JS.'/profile/hide_delete_revamp.php">Click Here</a>.
      <br>
      <br>
    </div>

<div align="left" style="margin-left:60px">Warm Regards,</div>
<div align="left" style="margin-left:60px">Jeevansathi.com Team.</div>';
	send_email($to,$message,$subject,$from);
	//Entry into inactive users db.
	$dated=date("Y-m-d");
	$sql="insert ignore into MIS.INACTIVE_USERS (PROFILEID,SEN_DATE,TYPE_MAIL) values($profileid,'$dated',3)";
	mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
	}
}
$dbM=connect_db(); //can be slave as well.
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
if(count($archive_it))
{
	
  $objUpdate = JProfileUpdateLib::getInstance();
	foreach($archive_it as $key=>$val)
	{
		
		$profileid=$val[0];
		$email=$val[1];
		$username=addslashes(stripslashes($val[2]));
		$subscription=$val[3];
		$key_ja="J1S2T3!@#";
		$activekey=bin2hex(rc4crypt::encrypt($key_ja, $profileid, 1));
		//$sql="update newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', activatedKey=0,JSARCHIVED=1, MOD_DT=now() where PROFILEID='$profileid'";
		//mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
    $objUpdate->updateJProfileForArchive($profileid);
     
		$date=date("Y-m-d");
		$sql="insert into newjs.JSARCHIVED(PROFILEID,EMAIL,USERNAME,DEACTIVE_DATE) values('$profileid','$email','$username','$date')";
		mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
		$sql="delete from MIS.INACTIVE_USERS where PROFILEID=$profileid";
		mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));

		//As per as deleteprofile file.
                $now= date("Y-m-d H:i:s");
		$sql= "UPDATE jsadmin.MARK_DELETE SET STATUS='D', DATE='$now' WHERE PROFILEID=$profileid ";
		mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
		//delete offline contacts
		//added by Neha Verma
		$sql="SELECT BILLID,ENTRY_DATE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
		$res= mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));
		if($row= mysql_fetch_array($res))
		{
			$entry_date= $row['ENTRY_DATE'];
			$bid= $row['BILLID'];
			$sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N' WHERE PROFILEID= '$profileid' AND ENTRY_DATE= '$entry_date' AND BILLID= '$bid'";
			mysql_query($sql,$dbM) or die(mysql_error1(mysql_error($dbM).$sql));

		}

		$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid 1 > /dev/null";
		$cmd = JsConstants::$php5path." -q ".$path;
		shell_exec($cmd);
		
		$to=$email;
		//$to='dhiman_nikhil@yahoo.com';
		$subject="Profile marked inactive on Jeevansathi.com";
		$from="webmaster@jeevansathi.com";
		$message='<div>&nbsp;&nbsp;&nbsp; Hi User,
  <BR><BR></div>
<div style="margin-left:60px">
   Greetings from Jeevansathi.com. At Jeevansathi.com, we strive to make your search for a life partner easy and convenient.
  <BR><BR>
   
</div>
<div style="margin-left:60px">We have observed that its over 1 year since you have logged into Jeevansathi.com. We would like to inform you that we have marked your profile as INACTIVE and your profile is no longer visible to over 2 million Jeevansathi.com users. We want to be sure that you are still in the process of finding a life partner.
  <BR><BR></div>
<div style="margin-left:60px">
If you wish to restore your profile <a href="'.$SITE_URL_JS.'/profile/retrieve_archived.php?activekey='.$activekey.'&directmail=1">Click here</a>. 
  <br>
  <br>
</div>

<div style="margin-left:60px">
      If you have already found your match, we thank you for using Jeevansathi.com and wish you all the best for your happy married life. We would like to invite you to submit your Success story with us. Please <a href="'.$SITE_URL_JS.'/success/success_stories.php">Click Here</a>.
      <br>
      <br>
    </div>

<div align="left" style="margin-left:60px">Warm Regards,</div>
<div align="left" style="margin-left:60px">Jeevansathi.com Team.</div>';
	send_email($to,$message,$subject,$from);
	}
	
	unset($objUpdate);
}
function mysql_error1($msg)
{
        echo $msg;
        //die;
        send_email("nikhil.dhiman@jeevansathi.com","error in shift profile archive",$msg);
	exit;
}
?>
