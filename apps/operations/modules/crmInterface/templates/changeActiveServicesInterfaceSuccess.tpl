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
							<input type="submit" name="submit" value="Change Mtongue">
						</td>
						</tr>
					</table>
				
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
			~foreach from=$servDet key=k item=v`
			<div align="center" style="margin: 0px auto; padding: 20px 30px; width: 45%; font-size: 21px; color: rgb(255, 255, 255); background-color: firebrick;" width="50%">~$servArr.$k`</div>
			<table width=50% align=center>
				<tr class=formhead style="background-color:LightSteelBlue" height="25">
					<td width=~$durPerc`% align=center>Service Name - Duration</td>
					<td width=~$durPerc`% align=center>Show Online Flag</td>
				</tr>
				~foreach from=$v key=kk item=vv`
				<tr height="50">
					<td align=center style="background-color:lightblue;font-size:18px;">~$vv.NAME`</td>
					<td align=center style="background-color:lightblue">
						<input class="serviceDisc" type="radio" name="~$vv.SERVICEID`" value="Y" ~if $vv.SHOW_ONLINE eq 'Y'` checked ~/if` style="text-align:center;font-size:16px;"> Show
						<input class="serviceDisc" type="radio" name="~$vv.SERVICEID`" value="N" ~if $vv.SHOW_ONLINE neq 'Y'` checked ~/if` style="text-align:center;font-size:16px;"> Hide
					</td>
				</tr>
				~/foreach`
			</table>
			<br><br>
			<div style="margin:0 auto;text-align:center;">
				<input type="submit" name="submit" value="Apply Visibilty Changes" class="visibiltyApplyFilter" onclick="return confirmSubmit();"">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br><br>
			~/foreach`
		</form>
	</body>
	<script type="text/javascript">
		var currentMtongueFilter = "~$mtongueFilter`";
	</script>
</html>