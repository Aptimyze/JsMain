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
			return true;
	}
</script>
	<form action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/showDeletedPlusOriginalPhotos?source=new&cid=~$cid`" method="POST">
		<input type=hidden name="cid" value="~$cid`">
		<input type=hidden name="source" value="~$source`">
		<input type=hidden name="name" value="~$name`">
		<table width=760 align="CENTER" >
			<tr class="formhead" align="CENTER">
				<td colspan=3>SHOW DELETED PHOTOS
			</tr>
			<tr align="CENTER">
				<td width=30%>ENTER USERNAME
				</td>
				<td align="center"><input type="text" id="username" name="username">
				</td>
				~if $error`
					<td>
						<font color="red">
							Wrong username entered!
						</font>
					</td>
				~/if`
			</tr>
			<tr align="CENTER" class="fieldsnew">
				
				<td>
					<input type="submit" name="Submit" value="Show" onclick="return sure()">
					&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
		   </table>
	</form>

~include_partial('global/footer')`