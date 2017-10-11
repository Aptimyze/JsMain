<html>
<head>
<script language="JavaScript">
	function checkblank()
	{
		if(document.logincustomer.oc_uid.value == "")
		{
        		alert("Please Enter Offline Customer UserId");
        		return false;
		}
		if(document.logincustomer.oc_passwd.value =="") 
                {
                        alert("Please Enter Offline Customer Password");
                        return false;
                }
		
        return true;
	}

                                                                                                                             
</script>
<title>Jeevansathi.com</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
</head>
<body>
<table width="775" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #f09329">
  <tr>
    <td><img src="http://ser4.jeevansathi.com/profile/images/js-logo.gif" width="226" height="63" hspace="15" vspace="5"></td>
  </tr>
  <tr>
    <td bgcolor="#bc001d" height="6"></td>
  </tr>
  <tr>
    <td height="1"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#f09329"></td>
  </tr>
  <tr>
    <td height="2" bgcolor="#dfddde"></td>
  </tr>
  <tr>
    <td><br>
      <table width="650" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #bcd171">
	<form name= "logincustomer" method= "post" action= "login_customer.php">
	<input type="hidden" name="cid" value="~$cid`">
      <tr>
        <td height="25" colspan="2" align="center" class="heading" bgcolor="#e6f7ad"><strong>Operators Login</strong></td>
      </tr>
     <tr class="formhead" height="23">
		<td colspan="2" align="center">~$msg`</td>
	</tr>
	<tr>
	<td colspan= "2">&nbsp;</td>
	<tr>
      <tr>
        <td class= "label" align="right" class="regular-text">Offline Customer UserID :&nbsp;</td>
        <td class= "fieldsnew"><input type="text" name="oc_uid"></td>
      </tr>
      <tr>
        <td align="right" height="10"></td>
        <td></td>
      </tr>
      <tr>
        <td class= "label" align="right" >Password :&nbsp;</td>
        <td class= "fieldsnew"><input type="password" name="oc_passwd"></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class= "fieldsnew" align="center">
	<td align="center" colspan="2"><input type= "submit" name= "submit" value= "submit" onclick= "return checkblank();" style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
	</td>
	</tr>	
</form>
    </table>      
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p><br>
                </p></td>
  </tr>
</table>
</body>
</html>
