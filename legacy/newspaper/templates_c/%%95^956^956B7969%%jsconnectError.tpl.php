<?php /* Smarty version 2.6.6, created on 2008-09-11 06:39:54
         compiled from jsconnectError.tpl */ ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="GENERATOR" content="Mozilla/4.61 [en] (WinNT; I) [Netscape]">
<title>Jeevansathi.com</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
</td>
</tr>
</table>
<form action="login.php" method="post">
<input type=hidden name="name" value="<?php echo $this->_tpl_vars['NAME']; ?>
">
<table width="760" border="0" cellspacing="0" cellpadding="0" align="center" height="327">
<tr>
<td width="79%" valign="top" align="center"> <br>
<b><font face="Verdana" size="2" color="#666666"><br>
<br>
Either the user name or password entered by you is incorrect !</font></b><br>
<table width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="2" class="formhead" height="30"> &#155; Please enter
your Username and Password below and resubmit the form.</td>
</tr>
<tr>
<td class="label" height="40"><b>Username :</b></td>
<td bgcolor="#F5F5F5" height="40">
<input type=text name=username value="<?php echo $this->_tpl_vars['username']; ?>
" size=30 maxlength=80 class="textboxes1">
</td>
</tr>

<tr>
<td class="label" height="40"><b>Password :</b></td>
<td bgcolor="#F5F5F5" height="40">
<input type="password" name="password" value="" size=30 maxlength=128 class="textboxes1">
</td>
</tr>
<tr valign="middle" align="center">
<td colspan="2" height="30">
<input type=submit value=" Login " name="submit2" class="buttons">
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>

