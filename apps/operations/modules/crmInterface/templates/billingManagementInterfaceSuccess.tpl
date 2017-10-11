<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	</head>
	<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Billing Management Interface</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		~assign var=num value=1`
		<div width="100%" style="background-color:lightblue;text-align:left;padding:20px;font-size:12px;font-weight:bold;">
			~if $showOptions`
			<div>
				~$num++`.&nbsp;&nbsp;<a href="/operations.php/crmInterface/serviceDateChangeInterface?name=~$name`&cid=~$cid`">Change Activation Dates of Services using billid</a>
			</div>
			<br>
			<div>
				~$num++`.&nbsp;&nbsp;<a href="/operations.php/crmInterface/serviceActivationChangeInterface?name=~$name`&cid=~$cid`">De-activate/Re-activate Services using billid</a>
			</div>
			<br>
			~/if`
		</div>
		<input type="hidden" name="name" value="~$name`">
		<input type="hidden" name="cid" value="~$cid`">
	</body>
</html>