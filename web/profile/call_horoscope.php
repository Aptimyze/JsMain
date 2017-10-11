<html><head><title>jeevansathi Astro Service</title></head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="document.frmsubmit.submit();">
                                                                                                 
<form name="frmsubmit" action=" http://www.astroyogi.com/saathimatch/onlinehoro/CheckdataA.asp?id=10&sub=nonreg" method="post">
<input type=hidden name="ClientID" value="jeevansaathi">
<?php
include("connect.inc");
include("contact.inc");
include("astrofunctions.php");
$db=connect_db();
if($profilechecksum)
{
	 $arr=explode("i",$profilechecksum);
         if(md5($arr[1])!=$arr[0])
	 {
	        showProfileError();
	 }
	 else
		$profileid=$arr[1];

	$sql="SELECT PROFILEID,NUM_VIEW from HOROSCOPE_CAPTURE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		$sql="UPDATE HOROSCOPE_CAPTURE set NUM_VIEW = '".($myrow['NUM_VIEW']+1)."' where PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
	}
	else
	{
		$sql="INSERT into HOROSCOPE_CAPTURE (PROFILEID,NUM_VIEW) values ('$profileid','1')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	
	$sql="SELECT USERNAME,GENDER,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH,SUBSCRIPTION from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	if($myrow['GENDER']=="M")
		$gender="Male";
	else
		$gender="Female";
	list($year,$month,$day)=explode("-",$myrow['DTOFBIRTH']);
	$dob=$month."/".$day."/".$year;
	list($birth_hour,$birth_min)=explode(":",$myrow['BTIME']);
	$birth_place=$myrow['CITY_BIRTH'];
	$birth_country=label_select('COUNTRY',$myrow['COUNTRY_BIRTH']);
	
	$checksum_astro=getChecksum($profileid);
	echo "<input type=hidden name='checksum_astro' value='$checksum_astro'>";
	echo "<input type=hidden name='UserID' value='$profileid'>";
	echo "<input type=hidden name='dob' value='$dob'>";
	echo "<input type=hidden name='gender' value='$gender'>";
	echo "<input type=hidden name='bthour' value='$birth_hour'>";
	echo "<input type=hidden name='btmin' value='$birth_min'>";
	echo "<input type=hidden name='birth_place' value='$birth_place'>";
	echo "<input type=hidden name='birth_country' value='$birth_country[0]'>";
}
else
{
	TimedOut();
}
?>
<noscript>
<br><br>
<center><br>Kindly Press below button to complete the process.<br><br><br>
<input type=image src="images/continue_astro.gif" width="88" height="20" border="0" name=submitbt></center>
</noscript>
</form></body>
</html>

