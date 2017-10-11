~include_partial('global/header')`

<SCRIPT language="JavaScript">
	function sure()
	{
		var username = document.getElementById("username").value;
		username = username.replace(/^\s*|\s*$/,"");
		if(username == '')
		{
			alert("Please enter a username to continue to the next page");
			return false;
		}
		else
			return confirm("Are you sure to screen the marked photos?");
	}
</script>
	~if $marked neq 1`
	<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
		<tr class=label align=center>
			<td width=20%>
				&nbsp;SENDER
			</td>
			<td width=20%>
				&nbsp;SUBJECT
			</td>
			<td width=60%>
				&nbsp;MESSAGE
			</td>
		</tr>
		<tr class=fieldsnew align=center>
			<td>
				&nbsp;~$SENDER`
			</td>
			<td>
				&nbsp;~$SUBJECT`
			</td>
			<td>
				&nbsp;~$MESSAGE`
			</td>
		</tr>
	</table>
	~/if`
        ~if $alreadyAlloted eq 1`
	<div align="center">
		<b>
			This profile has already been allotted to a user in the last 30 min. <br>Please try after some time.
		</b>
	</div>
         ~else if $incorrectUser eq 1`
	<div align="center">
		<b>
			No profile with given username exists. <br>Please try again.
		</b>
	</div>
        ~else if $noPhotoExist eq 1`
	<div align="center">
		<b>
			This profile has no photos.
		</b>
	</div>
        ~else if $preprocessing eq 1`
	<div align="center">
		<b>
			Photos are still under pre-processing and will be available in a while.
		</b>
	</div>
        ~else if $preprocessing eq 1`
	<div align="center">
		<b>
			Photos are still under pre-processing.
		</b>
	</div>
        ~/if`
	<form ~if $marked eq 1` action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/masterPhotoEditSubmit?cid=~$cid`" ~else` action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/sendMailPhotosToScreen?source=mail&cid=~$cid`" ~/if` method="POST">
		<input type=hidden name="sender" value="~$SENDER`">
		<input type=hidden name="cid" value="~$cid`">
		<input type=hidden name="user" value="~$user`">
		<input type=hidden name="mailid" value="~$mailid`">
		<input type=hidden name="message" value="~$MESSAGE`">
		<input type=hidden name="subject" value="~$SUBJECT`">
		<input type=hidden name="source" value="~$source`">
		<table width=760 align="CENTER" >
			<tr class="formhead" align="CENTER">
				<td colspan=3>MARK USER FOR SCREENING
			</tr>
			<tr align="CENTER">
				<td width=30%>Enter Username
				</td>
				<td align="center"><input type="text" id='username' name="username" value="~$USERNAME`" >
				</td>
				~if $error`
					<td>
						<font color="red">
							Wrong Username entered!
						</font>
					</td>
				~/if`
			</tr>
			<tr align="CENTER" class="fieldsnew">
				<input type="hidden" name="profileid" value="~$PROFILEID`">
				<input type="hidden" name="profileData" value="~$profileData`">
				<input type="hidden" name="profileDataKeys" value="~$profileDataKeys`">
				<td>
					<input type="submit" name="Submit" value="Mark For Screen" onclick="return sure()">
					&nbsp;&nbsp;&nbsp;
					~if $marked neq 1`
						<input type="submit" name="Delete" value="Delete">
					~/if`
				</td>
			</tr>
		   </table>
	</form>

~include_partial('global/footer')`
