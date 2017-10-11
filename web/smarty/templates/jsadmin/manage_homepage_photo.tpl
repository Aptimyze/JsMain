<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Feedback Details : Jeevansathi.com Matrimonial Services</title>
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
 <form action="manage_homepage_photo.php" method="post">
  <table width=60% align="CENTER" class="fieldsnew">
    <tr align="CENTER">
      <td class="formhead" height="23">&nbsp;</td>
      <td class="formhead" height="23"><b><font size="3" color="blue">Manage Home Page Photo</font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="formhead" height="23">&nbsp;</td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=25% height="20">&nbsp;</td>
	<td class="fieldsnew"><input type=checkbox name=clear_prev_list value=yes> Clear Previous List</td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=25% height="20">Give User names</td>
      <td class="fieldsnew" width="15" height="20">
	<textarea name="usernames"  class="testbox" cols="55" rows="3"></textarea><br>Please give comma separeted usernames.</td>
    </tr>
    <tr align="CENTER">
      <td class="label" height="23">&nbsp;</td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=25% height="20">Gender</td>
      <td class="fieldsnew" width="15" height="20">
	<select name=gender>
	<option value="F">FEMALE</option>
	<option value="M">MALE</option>
	</select>
    </tr>
    <tr align="CENTER">
      <td class="label" height="23">&nbsp;</td>
    </tr>
    <tr class="fieldsnew" align="CENTER">
      <td height="21">&nbsp</td>
      <td height="21"><input type=submit name=Submit value="Submit"></td>
	<input type=hidden name=cid value=~$cid`>
   </tr>
  </table>

 </form>
 

 <br><br><br><br><br><br>
~include file="foot.htm"`


</body>
</html>
