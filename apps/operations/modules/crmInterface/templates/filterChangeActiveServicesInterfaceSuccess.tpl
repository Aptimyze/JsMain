<html>
<head>
	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
		</tr>
	</table>
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/changeActiveServicesInterface?cid=~$cid`&name=~$name`" method="post">
		<input type="hidden" name="cid" value="~$cid`">

		<br>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr class="formhead" align="center">
				<td colspan="2" style="background-color:lightblue"><font size=4>FILTER FOR MEMBERSHIP PLANS VISIBILITY</font></td>
			</tr>
			<tr class="formhead" align="center">
				<td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
			</tr>

		</table>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr align="left">
				<td class="label"><font size=2>
					Select Mother Tongue 
				</font></td>
				<td class="fieldsnew">
                                        <select id="mtongueSelect" name="mtongueFilter">
						<option value="-1">DEFAULT</option>
                                                ~foreach from=$mtongueArr key=k item=v name=mtongueLoop`
                                                      <option value="~$k`">~$v`</option>
                                                ~/foreach`
                                        </select>
				</td>
			</tr>
			<tr align="center">
				<td class="label" colspan="2" style="background-color:PeachPuff">
					<input type="submit" name="submit" value="   GO   ">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
