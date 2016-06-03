<html><head><title>jeevansathi Astro Service</title>
<SCRIPT language="JavaScript">
<!--
function callastro()
{
 location.href= "http://www.astroyogi.com/jeevansathi/";
}                                                                                                
-->
</script>

</head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="callastro();">
<form name="frmsubmit" action="" method="post">
<?php
include("connect.inc");
$db=connect_db();
	
$curdate= date('Y-m-d');
$sql="SELECT REQUEST_DT,NUM_VIEW from ASTROLINK_CAPTURE where REQUEST_DT='$curdate'";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
if(mysql_num_rows($result)>0)
{
	$myrow=mysql_fetch_array($result);
	$sql="UPDATE ASTROLINK_CAPTURE set NUM_VIEW = '".($myrow['NUM_VIEW']+1)."' where REQUEST_DT='$curdate'";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
}
else
{
	$sql="INSERT into ASTROLINK_CAPTURE (REQUEST_DT,NUM_VIEW) values ('$curdate','1')";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not
be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
?>
<noscript>
<br><br>
<center><br>Kindly Press below button to go further.<br><br><br>
<input type=image src="images/continue_astro.gif" width="88" height="20" border="0" name=submitbt></center>
</noscript>
</form></body>
</html>

