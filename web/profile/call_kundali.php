<html><head><title>jeevansathi Astro Service</title></head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="document.frmsubmit.submit();">                                                                                          
<form name="frmsubmit" action="http://www.astroyogi.com/saathimatch/onlinehoro/checkdatak.asp" method="post">
<?php
include("connect.inc");
include("contact.inc");
include("astrofunctions.php");
$db=connect_db();

$data=authenticated($checksum);
if($data)
{
        $profileid_first=$data['PROFILEID'];
        $arr=explode("i",$profilechecksum);
        if(md5($arr[1])!=$arr[0])
        {
               showProfileError();
        }
        else
               $profileid_second=$arr[1];

	echo "<input type=hidden name='ClientID' value='jeevansaathi'>";

	echo "<input type=hidden name='fpgender' value='$fpgender'>";
	echo "<input type=hidden name='fpcity' value='$fpcity'>";
	echo "<input type=hidden name='fpcountry' value='$fpcountry'>";
	echo "<input type=hidden name='fpname' value='$fpname'>";
	echo "<input type=hidden name='fpdobd' value='$fpdobd'>";
	echo "<input type=hidden name='fpdobm' value='$fpdobm'>";
	echo "<input type=hidden name='fpdoby' value='$fpdoby'>";
	echo "<input type=hidden name='fptobh' value='$fptobh'>";
	echo "<input type=hidden name='fptobm' value='$fptobm'>";
	
	echo "<input type=hidden name='spgender' value='$spgender'>";
	echo "<input type=hidden name='fname' value='$fname'>";
	echo "<input type=hidden name='dobday' value='$dobday'>";
	echo "<input type=hidden name='dobmonth' value='$dobmonth'>";
	echo "<input type=hidden name='dobyear' value='$dobyear'>";
	echo "<input type=hidden name='bhour' value='$bhour'>";
	echo "<input type=hidden name='bminutes' value='$bminutes'>";
	echo "<input type=hidden name='birth_place' value='$birth_place'>";
	echo "<input type=hidden name='birth_country' value='$birth_country'>";

	echo "<input type=hidden name='astro' value='$astro'>";

	echo "<input name='checksum_astro' type=hidden value='$checksum_astro'>";
                                                                                         
                                                                                         

	$sql="INSERT into KUNDALI_CAPTURE (MATCH_BY,MATCH_TO,MATCH_DATE) values ('$profileid_first','$profileid_second',now())";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
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
