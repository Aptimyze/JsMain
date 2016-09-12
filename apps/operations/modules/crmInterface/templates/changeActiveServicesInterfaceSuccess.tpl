<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	</head>
	<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<form name="applyServices" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/changeActiveServicesInterface" id="applyServices" method="POST">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
				</tr>
				<tr class="formhead" align="center" width="100%">
					<td colspan="3" style="background-color:lightblue" height="30">
						<font size=3>Change Active Services Interface</font>
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
			~if $successMsg`
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;color:green;">
				~$successMsg`
			</div>
			~/if`
			~if $errorMsg`
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;color:red;">
				~$errorMsg`
			</div>
			~/if`
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;">
				1) Currently checked services are active<br>
				2) Unchecked services will be de-activated on front-end only<br>
				3) To add more services contact tech team
			</div>
			<br>
			<table width=50% align=center>
				<tr class=formhead style="background-color:LightSteelBlue" height="25">
					<td width=~$durPerc`% align=center>Service Name - Duration</td>
					<td width=~$durPerc`% align=center>Show Online Flag</td>
				</tr>
				~foreach from=$servDet key=k item=v`
				<tr height="50">
					<td align=center style="background-color:lightblue;font-size:18px;">~$v.NAME`</td>
					<td align=center style="background-color:lightblue">
						<input class="serviceDisc" type="radio" name="~$v.SERVICEID`" value="Y" ~if $v.SHOW_ONLINE eq 'Y'` checked ~/if` style="text-align:center;font-size:16px;"> Show
						<input class="serviceDisc" type="radio" name="~$v.SERVICEID`" value="N" ~if $v.SHOW_ONLINE neq 'Y'` checked ~/if` style="text-align:center;font-size:16px;"> Hide
					</td>
				</tr>
				~/foreach`
			</table>
			<br><br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;" type="submit" name="submit" value="Apply Values">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br><br>
		</form>
	</body>
</html>