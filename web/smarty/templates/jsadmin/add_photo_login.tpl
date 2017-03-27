<html>
<head>
<title>Add offline photo @ jeevansathi.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~include file"=head.htm"`
<table width=760 cellspacing="1" cellpadding='3' ALIGN="CENTER" >
<tr width=100% class="formhead">
        <td width=25% class="formhead" border=1 align="center"><font><b>Welcome : ~$OPERATOR_NAME`</b></font></td>
        <td width="25%" class="formhead" align="center"><a href="mainpage.php?cid=~$CID`">Click here to go to main page</a></td>
        <td width=25% class="formhead" align='CENTER'><a href="logout.php?cid=~$CID`">Logout</a>
        </td>
</tr>
</table>
<br /><br />
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <form name="registerd" method="post" action="add_photo_login.php">
        <table width="49%" border="0" cellspacing="1" cellpadding="4" align="center">       
	~if $RELOGIN` 
          <tr>
          	<td colspan=2>
          	<font color="red" size=1>
          		No user with this name exists
          	</font>
          	</td>
          </tr>	   
          ~/if`
	 ~if $NO_SELECT`
          <tr>
                <td colspan=2>
                <font color="red" size=1>
                        You have to select atleast one photo
                </font>
                </td>
          </tr>
          ~/if`

        	<tr>
            <td width="49%" class="label" bgcolor="#F9F9F9">Username</td>
            <td width="51%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~$username`" size="18" maxlength="40" class="textboxes1">
            </td>
          </tr>
        	<tr>
           <td bgcolor="#F9F9F9" class="label">Main Photo</td>
		<td><input type="checkbox" name="mainphoto" value="Y" ~if $MAINPHOTO eq "Y"` checked ~/if`></td>
		</tr>
		<tr>
           <td bgcolor="#F9F9F9" class="label">Photo Album 1</td>
              <td><input type="checkbox" name="photoalbum1" value="Y" ~if $PHOTOALBUM1 eq "Y"` checked ~/if`></td>
		</tr>
		<tr>
           <td bgcolor="#F9F9F9" class="label">Photo Album 2</td>
              <td><input type="checkbox" name="photoalbum2" value="Y" ~if $PHOTOALBUM2 eq "Y"` checked ~/if`></td>
          </tr>
          <tr valign="middle" align="center">
            <td width="49%" bgcolor="#F9F9F9" height="44">
              <input type="submit" name="login" value="  Submit  " class="textboxes1">
            </td>
            <td width="51%" bgcolor="#F9F9F9" height="44">
              <input type="reset" name="reset" value="  Reset  " class="textboxes1">
            </td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <br>
        <br>
      <input type="hidden" name="operator_name" value="~$OPERATOR_NAME`">  
      <input type="hidden" name="cid" value="~$CID`">  
      </form>


      </td>
    </tr>
  </table>


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td valign="top" bgcolor="#C4BFC4"><img src="../billing/images/spacer.gif" width="1" height="2"></td>
  </tr>
  <tr>
    <td valign="middle" bgcolor="#EFEFEF" align="center" height="18" class="bottom">
      <a href="https://www.naukri.com/infoedge/naukhome.htm" class="bottom" target="_blank">About
      Us</a> - <a href="https://www.naukri.com/infoedge/clients/clients.htm" class="bottom" target="_blank">Our
      Clients</a> - <a href="https://www.naukri.com/infoedge/pageview.htm" class="bottom" target="_blank">
      Check Hits</a> - <a href="https://www.naukri.com/infoedge/naukri.htm" class="bottom" target="_blank">
      Disclaimer</a> - <a href="https://www.naukri.com/faq" class="bottom" target="_blank">
      F.A.Q's</a> - <a href="https://www.naukri.com" class="bottom"> Home</a> -
      <a href="https://www.naukri.com/contact/" class="bottom" target="_blank">Contact
      Us</a></td>
  </tr>
</table>

</body>
</html>
