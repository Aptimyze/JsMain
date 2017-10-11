<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Offline System</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<script language="JavaScript">
<!--

function loadForm()
{
        document.form1.submit();
}                                                                                 

-->
</script>
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="ap_tbc_profile.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=CHECKDELETED value="~$CHECKDELETED`">
<table width=760 cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr class="formhead">
          <td width="35%" class="formhead" align="center"><a href="mainpage.php?cid=~$cid`">Main Page</a></td>
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br><br>
<br>
<table width="60%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">Pull the profile for TBC </td>
          </tr>
          <tr>

	  ~if $errMsg eq '1'`
	  <tr>
	   <td valign="middle" colspan="2"> <font color="red">* Please enter valid Username/Email</font></td>
	  </tr>
	  ~/if`
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Select</td>
            <td width="70%" bgcolor="#F9F9F9" >
		<select name="phrase" class="textboxes1" >
			<option value="U" ~if $phrase eq 'U'`selected~/if`>By Username</option>
			<option value="E" ~if $phrase eq 'E'`selected~/if`>By Email</option>
		</select>
            </td>
          </tr>
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Username/Email</td>

            <td width="70%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~$USER_NAME`" size="18" maxlength="40" class="textboxes1">
            </td>
          </tr>

          <tr valign="middle" align="center">
	    <td width=30% class="fieldsnew"></td>
              <td class="fieldsnew"><input type="submit" name="Go" value="Submit" class="textboxes1"></td></tr>
</table>
<br>	
</form> 

</body>
</html>
