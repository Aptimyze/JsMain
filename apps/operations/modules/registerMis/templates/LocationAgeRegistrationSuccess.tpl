<html>
<head>
   <title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/registerMis/LocationAgeRegistration?cid=~$cid`" method="get">
        <input type="hidden" name="cid" value="~$cid`">

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Registration MIS</font></td>
	</tr>
	<tr></tr>
	</table>

	~if $errorMsg != ''`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		 <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr align="center">
                        <td class="label">
			<font size=2> ~$errorMsg`  </font>
                        </td>
		</tr>
		</table>
  ~/if`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">

		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
    <tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="Q" checked="yes" />
				<font size=2>Quarter Wise</font>
			</td>
			<td>
			<select name="qyear">
				~foreach from=$yyarr item=yearVal`
				~if $yearVal eq $currentYear`
				<option value=~$yearVal` selected>~$yearVal`</option>
				~else`
				<option value=~$yearVal`>~$yearVal`</option>
				~/if`
				~/foreach`
			</select>&nbsp;Year
			<br><br>
			</td>
		</tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="M" />
				<font size=2>Month Wise</font>
			</td>
			<td>
				<select name="myear">
					~foreach from=$yyarr item=yearVal`
					~if $yearVal eq $currentYear`
					<option value=~$yearVal` selected>~$yearVal`</option>
					~else`
					<option value=~$yearVal`>~$yearVal`</option>
					~/if`
					~/foreach`
				</select>&nbsp;Year
			</td>
		</tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="D" />
				<font size=2>Day Wise</font>
			</td>
			<td colspan=2>
				<select name="dmonth">
					~foreach from=$mmarr key=monthNum item=monthVal`
					~if $monthVal.VALUE eq $currentMonth`
					<option value=~$monthVal.VALUE` selected>~$monthVal.NAME`</option>
					~else`
					<option value=~$monthVal.VALUE`>~$monthVal.NAME`</option>
					~/if`
					~/foreach`
				</select> -
				<select name="dyear">
					~foreach from=$yyarr item=yearVal`
					~if $yearVal eq $currentYear`
					<option value=~$yearVal` selected>~$yearVal`</option>
					~else`
					<option value=~$yearVal`>~$yearVal`</option>
					~/if`
					~/foreach`
				</select>&nbsp;Month - Year
			</td>
		</tr>
		<tr align="center">
			<td class="label"><font size=2>
			       Select Report Format
			</font></td>
			<td class="fieldsnew">
				<input type="radio" name="report_type" value="CITY_RES" checked><font size=2>&nbsp;By City </font></input><br />
				<input type="radio" name="report_type" value="MTONGUE"><font size=2>&nbsp;By Community </font></input><br>
				<input type="radio" name="report_type" value="Age_Gender"><font size=2>&nbsp;By Age & Gender </font></input><br>
				&nbsp;
			</td>
		</tr>
		<tr align="center">
			<td class="label"><font size=2>
			       Select type of Report
			</font></td>
			<td class="fieldsnew">
				<input type="radio" name="report_format" value="HTML" checked><font size=2>&nbsp;HTML Format </font></input><br />
				<input type="radio" name="report_format" value="CSV"><font size=2>&nbsp;CSV Format </font></input><br>
				&nbsp;
			</td>
		</tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="submit" name="submit" value="SUBMIT">
			</td>
		</tr> 
		</table>
	</form>
</body>
</html>
