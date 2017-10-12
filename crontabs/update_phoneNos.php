<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
ini_set('max_execution_time','0');
include('connect.inc');
$db=connect_db();
$sql="SELECT max(PROFILEID) as pid FROM newjs.JPROFILE";
$res = mysql_query($sql) or die(mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$row=mysql_fetch_array($res);
$maxpid=$row['pid'];
for($k=1;$k<=$maxpid;$k++)
{
	
	$sql="SELECT PHONE_MOB FROM newjs.JPROFILE where PROFILEID='$k'";
	$res = mysql_query($sql) or die(mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(!($row=mysql_fetch_array($res))) continue;
	$p=$row['PHONE_MOB'];
	$profileid=$k;
	$originalnumber=$p;
	
	$len=strlen($p);
	if(strlen($p)<6)
                $p="";
	else
	{
		$p=leaveonlydigits($p);
		if(strlen($p)<6)
                        $p="";
		else
		{
			$flag=tenth_digit_nine($p);
			if($flag==1)
			{
				$mb=1;
				$flag_0_91=digits_0_91($p);
				if($flag_0_91==1)
					$p=take_last_ten_digits($p);
				else
					$p=$originalnumber;
			}
			else
				$p=$originalnumber;
			if(strlen($p)<6)
				$p="";
		}
	}
	/*if($originalnumber || $p){
	echo "<br>";
	echo "original number=$originalnumber<br>";
	echo "final number=$p";
	echo "<br>";
	if($originalnumber!=$p || strlen($originalnumber)!=strlen($p))
		echo "query<br>";
	}*/

	if($originalnumber!=$p || strlen($originalnumber)!=strlen($p))
	{
		$q++;
		$sql="UPDATE newjs.JPROFILE SET PHONE_MOB='$p' where PROFILEID='$profileid'";
		$res = mysql_query($sql) or die(mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		JProfileUpdateLib::getInstance()->removeCache($profileid);
	}
}
function leaveonlydigits($num)
{
	$len=strlen($num);
	$newnumber="";
	for($i=0;$i<$len;$i++)
	{
		$digit=substr($num,$i,1);
		if(($digit>0 && $digit<10) || $digit=="0")
			$newnumber=$newnumber.$digit;
	}
	return $newnumber;
}
function tenth_digit_nine($num)
{
	$len=strlen($num);
	$pos=$len-10;
	if(substr($num,$pos,1)=="9")
		return 1;
	else
		return 0;
}
function take_last_ten_digits($num)
{
	$len=strlen($num);
	$first_digit=$len-10;
	$newnumber=substr($num,$first_digit,10);
	return $newnumber;
}
function digits_0_91($num)
{
	if(strlen($num)==10)return 1;
        $f1=$len-11;//for 0
	$f2=$len-12;//for 91
	$f2=$len-13;//for 091
        if(strlen($num)==11 && substr($num,$f1,1)=="0")
		return 1;
	else if(strlen($num)==12 && substr($num,$f2,2)=="91")
		return 1;
	else if(strlen($num)==13 && substr($num,$f2,2)=="091")
		return 1;
	else
		return 0;
}
?>
