<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="vdExtension" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/extendVdOffer" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Jeevansathi Server(Slave) Lag Status</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?cid=~$cid`">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
                        <div>
                                Mis-Slave Server (10.10.18.82): &nbsp; ~$misSlave`
                        </div><br>
			<div>
				Bms-Slave Server (10.10.18.81): &nbsp; ~$bmsSlave` 
			</div><br>
                        <div>
                                111-Slave server (10.10.18.111): &nbsp; ~$slave111`
                        </div>
		</div>
		<br>
		<div style="margin:0 auto;text-align:center;">
			<input style="font-size:16px;" type="submit" name="submit" value="Submit">
			<input type="hidden" name="name" value="~$name`">
			<input type="hidden" name="cid" value="~$cid`">
		</div>
	</form>
</body>
</html>
