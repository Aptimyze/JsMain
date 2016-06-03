<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
        <td valign="top" width="30%" bgcolor="#efefef"></td>
        <td valign="top" width="40%" bgcolor="#efefef" align="center"><img src="../profile/images/logo_1.gif" width="209" hei
ght="63" usemap="#Map" border="0"></td>
        <td valign="bottom" width="30%" bgcolor="#efefef">
        </td>
</tr>
</tbody>
</table>
        <form action="/operations.php/commoninterface/CrmLogin" method="post">
  <input type=hidden name="name" value="~$NAME`">
  <table width="760" border="0" cellspacing="0" cellpadding="0" align="center" height="327">
    <tr>
      <td width="79%" valign="top" align="center"> <br>
        <b><font face="Verdana" size="2" color="#666666"><br>
        <br>
	~if $EXPIRE eq 'Y'`
        Your account has expired. Kindly contact your boss to renew it.
        ~elseif $CID neq ''`
        Your session is Timed out. Please Login again
        ~else`
        Either the user name or password entered by you is incorrect !
        ~/if`
        </font></b><br>
        <table width="100%" align="center" cellpadding="4" cellspacing="1" >
          <tr>
            <td colspan="2" class="formhead" height="30"> &#155; Please enter
              your Username and Password below and resubmit the form to acces
              Resume Services</td>
          </tr>
          <tr>
            <td class="label" height="40"><b>Username :</b></td>
            <td bgcolor="#F5F5F5" height="40">
              <input type=text name=username value="~$username`" size=30 maxlength=80 class="textboxes1">
            </td>
          </tr>

          <tr>
            <td class="label" height="40"><b>Password :</b></td>
            <td bgcolor="#F5F5F5" height="40">
              <input type="password" name="password" value="" size=30 maxlength=128 class="textboxes1">
            </td>
          </tr>
          <input type="hidden" name="authFailure" value=1/>
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
