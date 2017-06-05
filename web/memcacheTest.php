<?php
include_once(JsConstants::$docRoot."/profile/connect.inc");
$db = connect_db();
$memcache = new Memcache;
$memcache->connect(JsConstants::$memcache["HOST"],JsConstants::$memcache["PORT"]);
if($_GET)
{
	$type = $_GET["type"];
	$username = $_GET["user"];
}
else if ($argv)
{
	$type = $argv[1];
	$username = $argv[2];
}
if($type == 'f')
	$memcache->flush();
		$date = date("Y-m-d");
		echo "new mailercount:"; echo $memcache->get("transactionIpCount".$date);
				echo "<br>";
		echo "old mailer count:";echo $memcache->get("oldMailerCount".$date);
				echo "<br>";


echo "<br>";
if($username)
{
	$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME = '$username'";
	$res = mysql_query($sql,$db);
	$row = mysql_fetch_assoc($res);
		echo "<br>";
echo	$profileid = $row["PROFILEID"];
echo "<br>";
echo "showConsentMessage : "; 
echo $memcache->get("showConsentMsg_".$profileid);
				echo "<br>";
		
	if($type=='d')
		$memcache->delete($profileid);
	if($type=='g')
	{
		$memData = unserialize($memcache->get($profileid));
		if($memData)
		{
			foreach($memData as $key=>$value)
			{
				echo $key."=>".$value;
				if($_GET)
				echo "<br>";
				else
				echo "\n";
			}
		}
		echo "<br>";
		echo "PHONE_VERIFIED:";echo $phone = $memcache->get($profileid."_PHONE_VERIFIED");
		echo "<br>";
		echo "appPromo:"; echo $memcache->get($profileid."_appPromo");
		echo "<br>";
		echo "verification seal:"; echo $memcache->get("VerificationSeal_".$profileid);
		echo "<br>";
		$ccArray = array("AWAITING_RESPONSE","FILTERED","ACC_ME","MY_MESSAGE","VISITOR_ALERT","PHOTO_REQUEST","PHOTO_REQUEST_BY_ME","HOROSCOPE_REQUEST_BY_ME","HOROSCOPE","INTRO_CALLS","INTRO_CALLS_COMPLETE","ACC_BY_ME","NOT_REP","BOOKMARK","DEC_ME","DEC_BY_ME","CONTACTS_VIEWED","PEOPLE_WHO_VIEWED_MY_CONTACTS","IGNORED_PROFILES","NOT_INTERESTED","NOT_INTERESTED_BY_ME");
		foreach($ccArray as $k=>$v)
		{

		echo "<br>";
		 echo "<br>"; echo "<br>"; echo "<br>"; echo "<br>"; echo "<br>";
                echo $v.":"; echo $memcache->get($profileid."_".$v);
                echo "<br>";
		}
	}
}


