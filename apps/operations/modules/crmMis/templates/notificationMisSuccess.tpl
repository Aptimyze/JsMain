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
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/notificationMis" method="post">
		<input type="hidden" name="cid" value="~$cid`">

		<br>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr class="formhead" align="center">
				<td colspan="2" style="background-color:lightblue"><font size=4>Notification Report</font></td>
			</tr>
			<tr class="formhead" align="center">
				<td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
			</tr>

		</table>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr align="left">
				<td class="label">
					<font size=2> Select Month and Year	</font>
				</td>
				<td class="fieldsnew">
					<select id="month" name="monthValue">
						~foreach from=$monthArr item=monthVal`
						      <option value="~$monthVal.VALUE`" ~if $monthVal.VALUE eq $todayMonth` selected="yes" ~/if`>~$monthVal.NAME`</option>
						~/foreach`
					</select>
					&nbsp;
					<select id="year" name="yearValue">
						~foreach from=$yearArr item=yearVal`
						      <option value="~$yearVal.VALUE`" ~if $yearVal.VALUE eq $todayYear` selected="yes" ~/if`>~$yearVal.NAME`</option>
						~/foreach`
					</select>
				</td>
			</tr>		
                        <tr align="left">
                                <td class="label"><font size=2>
                                        Select Channel Type
                                </font></td>
                                <td class="fieldsnew">
                                        <select id="channel" name="channelKey">
                                                ~foreach from=$channelArr item=channel key=keyVal`
                                                      <option value="~$keyVal`">~$channel`</option>
                                                ~/foreach`
                                        </select>
                                </td>
                        </tr>
			<tr align="left">
				<td class="label"><font size=2>
					Select Notification Type 
				</font></td>
				<td class="fieldsnew">
                                        <select id="notification" name="notificationKey">
						<option value="">ALL</option>
                                                ~foreach from=$notificationArr item=notification`
                                                      <option value="~$notification.NOTIFICATION_KEY`">~$notification.NOTIFICATION_KEY` &nbsp;&nbsp;&nbsp; (~if $notification.FREQUENCY eq 'I'`Instant ~else`Scheduled ~/if`)</option>
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
