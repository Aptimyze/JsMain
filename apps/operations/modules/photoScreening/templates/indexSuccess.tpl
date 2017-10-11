<div id = "content"> 
~include_partial('global/header')`
<br><br><br>
	<form name="registerd" method="post" action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/login">
        	<table width="49%" border="0" cellspacing="1" cellpadding="4" align="center">
          		<tr>
            			<td class="formhead" valign="middle" colspan="2">&#155; Registered Users Login here</td>
          		</tr>
          		<tr>
            			<td width="49%" class="label" bgcolor="#F9F9F9">Username</td>
            			<td width="51%" bgcolor="#F9F9F9">
              				<input type="text" name="username" size="18" maxlength="40" class="textboxes1">
            			</td>
          		</tr>
          		<tr>
            			<td width="49%" class="label" bgcolor="#F9F9F9">Password</td>
            			<td width="51%" bgcolor="#F9F9F9">
              				<input type="password" name="password" size="18" maxlength="40" class="textboxes1">
            			</td>
          		</tr>
          		<tr valign="middle" align="LEFT">
            			<td width="49%" class="label" bgcolor="#F9F9F9">&nbsp;</td>
              			<td bgcolor="#F9F9F9"><input type="submit" name="login" value="  Login  " class="textboxes1"></td>
	  		</tr>
            		<tr valign="top" align="right">
            			<td colspan="2" bgcolor="#F9F9F9" class="lftpan">&nbsp;</td>
          		</tr>
        	</table>
      	</form>
<br><br><br>
~include_partial('global/footer')`
</div>
