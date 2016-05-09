<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

</td>
  </tr>
</table>
        <form action="/operations.php/commoninterface/CrmLogin" method="post">
	<input type="hidden" name="httpReferer" value="~$httpReferer`">
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
        <table width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td colspan="2" class="formhead" height="30"> &#155; Please enter
              your Username and Password below and resubmit the form</td>
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
