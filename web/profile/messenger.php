<?php
	// This page is an example of a typical page on your site.  See below for more information
	/*
	 * 	Before any of this will work:
	 *		- Copy the 'userplane' folder into the ROOT directory of your webserver
	 *		- Open the db.sql file to see what changes need to be made to your database
	 *		- Modify all the php files in the userplane folder
	 *		- Have Userplane setup your account (See the "Steps to Integration" in the ICXML.doc file)
	 */
	   
	include("connect.inc");

	$db=connect_db();

	$data=authenticated($checksum);
	$userID=$data["PROFILEID"];
	
	$myrights=get_rights($userID);
	
	if(in_array("B",$myrights) && in_array("F",$myrights))
		$membership="value";
	elseif(in_array("F",$myrights))
		$membership="full";
	else 
		$membership="free";
?>
<html>
<head>
<title>Jeevansathi Messenger</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="/profile/images/styles.css" type="text/css">
<script language="javascript">
<!--
   function redirectWin()
   {
   	window.open("http://www.jeevansathi.com/userplaneclose.php?userid=<? echo $userID; ?>","","width=100,height=100");
   }
-->
</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
-->
</style>
</head>
<body>
<!--<script src="/userplane/functions.js" type="text/javascript" language="javascript"></script>-->
<table width="190" border="0" cellpadding="0" cellspacing="1" bgcolor="#000000">
<tr>
<td><table width="190" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td height="34"><img src="/profile/images/messanger1.gif" width="190" height="68"></td>
</tr>
<tr>
<td height="161" valign="top" class="mediumblack"><div align="center">
<p><br>
 <b>You are online and can <br>
receive chat requests</b><br>
<img src="/profile/images/messanger4.gif" width="17" height="17"><br>
     <?php
     	if($membership=="free")
     	{
     		?>
<b>To initiate chat requests <br>
become a <a href="http://www.jeevansathi.com/membership/jspc" class="class7" target="_blank">full member</a><br>
     <?php
     	}
     	elseif($membership=="value")
     	{
     		?>
<b>Being a Value Added Member you can initiate chat requests as well<br>
     	<?php
     	}
     	elseif($membership=="full")
     	{
     		?>
<b>Being a Full Member you can initiate chat requests as well<br>
     	<?php
     	}
     	?>
<br>
<br>
</b>   
</div>
<p>
<table width="180" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
 <td width="13" valign="top"><img src="/profile/images/messanger3.gif" width="13" height="16"></td>
 <td width="160" class="smallblack">Don&rsquo;t close this window if you want to receive chat requests</td>
</tr>
</table></td>
</tr>
<tr>
<td><table width="190" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2"><img src="/profile/images/messanger2.gif" width="190" height="13"></td>
</tr>
<tr>
<td width="46"><a href="http://www.jeevansathi.com/profile/mess_help.php?checksum=<?php echo $checksum; ?>" target="_blank"><img src="/profile/images/messanger2_1.gif" width="46" height="14" border="0"></a></td>
<td width="144"><img src="/profile/images/messanger2_2.gif" width="144" height="14"></td>
</tr>
<tr>
<td colspan="2"><a href="http://www.jeevansathi.com/profile/quick_search.php?checksum=<?php echo $checksum; ?>&searchonline=1" target="_blank"><img src="/profile/images/messanger2_3.gif" width="190" height="44" border="0"></a></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</table>
</body>
</html>
