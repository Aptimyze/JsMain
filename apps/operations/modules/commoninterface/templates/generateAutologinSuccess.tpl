~include_partial('global/header')`

<SCRIPT language="JavaScript">
	function sure()
	{
		var username = document.getElementById("username").value;
		var email = document.getElementById("email").value;
		username = username.replace(/^\s*|\s*$/,"");
		if(username==''&& email=='')
		{
			alert("Please enter a username or email to continue to the next page");
			return false;
		}
	}
	function backend()
	{
		var url = document.getElementById("autologinUrl").value;
		var win = window.open(url, '_blank');
		win.focus();
		return true;
	}
</script>
	<form action="~sfConfig::get('app_site_url')`/operations.php/commoninterface/generateAutologinLink?source=master&cid=~$cid`" method="POST">
		<input type=hidden name="cid" value="~$cid`">
		<input type=hidden name="name" value="~$name`">
		<input type=hidden name="profileid" value="~$profileid`">
		<input type=hidden id="autologinUrl" value="~$autologinUrl`">
		<table width=760 align="CENTER" >
			<tr class="formhead" align="CENTER">
				<td colspan=3>GENERATE AUTOLOGIN LINK FOR USER
			</tr>
			<tr align="CENTER">
				<td width=30%>Enter Username
				</td>
				<td align="center"><input type="text" id='username' name="username" value="~$username`" >
				</td>
				~if $error && $username`
					<td>
						<font color="red">
							Wrong Username entered!
						</font>
					</td>
				~/if`
			</tr>
                        <tr align="CENTER">
                                <td width=20%>
                                </td>
                                <td align="center">OR
                                </td>
                        </tr>

                        <tr align="CENTER">
                                <td width=30%>Enter Email
                                </td>
                                <td align="center"><input type="text" id='email' name="email" value="~$email`" >
                                </td>
                                ~if $error && $email`
                                        <td>
                                                <font color="red">
                                                        Wrong Email entered!
                                                </font>
                                        </td>
                                ~/if`
                        </tr>
			&nbsp;&nbsp;&nbsp;
                        <tr align="CENTER">
				<td colspan=3>
				~if $autologinUrl`
					<input type="submit" name="reset" value="AUTO LOGIN" onclick="return backend();">
				~else`
					<input type="submit" name="submit" value="Generate autologin" onclick="return sure()">
				~/if`
				<td>
                        </tr>
		   </table>
	</form>
~include_partial('global/footer')`
