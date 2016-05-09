<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Live Chat Status Manage : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="~$SITE_URL`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~$SITE_URL`/profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`
<br><br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=760 cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$username`</b></font></td>
          <td width="15%" class="formhead" align="center">&nbsp;</td>
          <td width=10% class="formhead" border=1 align='CENTER'><a href="~$SITE_URL`/jsadmin/mainpage.php?user=~$username`&cid=~$cid`">Main Page</a></td>
    </tr>
</table>
<br><br>
 <form action="livechat_status_manage.php" method="post">
  <table width=60% align="CENTER" class="fieldsnew">
    <tr align="CENTER">
      <td class="formhead" height="23">&nbsp;</td>
      <td class="formhead" height="23"><b><font size="3" color="blue">Manage Live Chat Status</font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" height="23">&nbsp;</td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=25% height="20">Current Status</td>
      <td class="fieldsnew" width="15" height="20"><b>~$STATUS`</b></td>
    </tr>
    <!--tr align="CENTER">
      <td class="label" height="23">&nbsp;</td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=25% height="20">Change With</td>
      <td class="fieldsnew" width="15" height="20">
	<select name=status>
	<option value="ON">ON</option>
	<option value="OFF">OFF</option>
	</select>
    </tr-->
    <tr align="CENTER">
      <td class="label" height="23">&nbsp;</td>
    </tr>
    <tr class="fieldsnew" align="CENTER">
      <td height="21">&nbsp</td>
      <td height="21">
	<input type=hidden name=cid value=~$cid`>
~if $STATUS eq "OFF"`
	<input type=hidden name=status value="ON">
	<input type=submit name=Submit value="Activate">
~else`
	<input type=hidden name=status value="OFF">
	<input type=submit name=Submit value="De-activate">
~/if`
	</td>
   </tr>
    <tr align="CENTER">
      <td class="fieldsnew" height="23">&nbsp;</td>
    </tr>
  </table>

 </form>
 <br><br><br><br><br><br>
~include file="foot.htm"`

</body>
</html>
