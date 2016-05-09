<html><head><title>jeevansathi Astro Service</title></head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="document.frmsubmit.submit();">
                                                                                                 
<form name="frmsubmit" action=" http://www.astroyogi.com/saathimatch/onlinehoro/checkdatak.asp" method="post">
<input type=hidden name="ClientID" value="jeevansaathi">
<?php
include("connect.inc");
include("contact.inc");
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
		
		$sql="SELECT USERNAME,GENDER,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_first'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow1=mysql_fetch_array($result);
                if($myrow1['GENDER']=="M")
                        $fpgender="Male";
                else
                        $fpgender="Female";
                list($fpdoby,$fpdobm,$fpdobd)=explode("-",$myrow1['DTOFBIRTH']);
                list($fptobh,$fptobm)=explode(":",$myrow1['BTIME']);
                $fpcity=$myrow1['CITY_BIRTH'];
                $fpcountry_temp=label_select('COUNTRY',$myrow1['COUNTRY_BIRTH']);
                $fpcountry=$fpcountry_temp[0];

		$checksum=md5($profileid_first+100)."i".($profileid_first+100);
	        echo "<input type=hidden name='checksum' value='$checksum'>";
	        echo "<input type=hidden name='fpgender' value='$fpgender'>";
	        echo "<input type=hidden name='fpcity' value='$fpcity'>";
	        echo "<input type=hidden name='fpcountry' value='$fpcountry'>";
	        echo "<input type=hidden name='fpname' value='$myrow1[USERNAME]'>";
	        echo "<input type=hidden name='fpdobd' value='$fpdobd'>";
	        echo "<input type=hidden name='fpdobm' value='$fpdobm'>";
	        echo "<input type=hidden name='fpdoby' value='$fpdoby'>";
	        echo "<input type=hidden name='fptobh' value='$fptobh'>";
	        echo "<input type=hidden name='fptobm' value='$fptobm'>";

		$sql="SELECT USERNAME,GENDER,DTOFBIRTH,BTIME,CITY_BIRTH,COUNTRY_BIRTH from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid_second'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
	        if($myrow['GENDER']=="M")
			$spgender="Male";
		else
			$spgender="Female";
		list($dobyear,$dobmonth,$dobday)=explode("-",$myrow['DTOFBIRTH']);
		list($bhour,$bminutes)=explode(":",$myrow['BTIME']);
		$birth_place=$myrow['CITY_BIRTH'];
		$birth_country_temp=label_select('COUNTRY',$myrow['COUNTRY_BIRTH']);
		$birth_country=$birth_country_temp[0];

	        echo "<input type=hidden name='spgender' value='$spgender'>";
	        echo "<input type=hidden name='birth_place' value='$birth_place'>";
	        echo "<input type=hidden name='birth_country' value='$birth_country'>";
	        echo "<input type=hidden name='fname' value='$myrow[USERNAME]'>";
	        echo "<input type=hidden name='dobday' value='$dobday'>";
	        echo "<input type=hidden name='dobmonth' value='$dobmonth'>";
	        echo "<input type=hidden name='dobyear' value='$dobyear'>";
	        echo "<input type=hidden name='bhour' value='$bhour'>";
	        echo "<input type=hidden name='bminutes' value='$bminutes'>";

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
