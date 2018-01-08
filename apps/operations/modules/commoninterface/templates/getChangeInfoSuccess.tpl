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
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/commoninterface/getChangeInfo?cid=~$cid`" method="post">
        <input type="hidden" name="cid" value="~$cid`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
                        ~if $isSubmit != '1'`
                                <a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ~else`
                                <a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/getChangeInfo?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ~/if`
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>User Info Change History</font></td>
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
        ~if $isSubmit != '1'`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
                <tr align="center">
			<td class="label">
				<font size=2>Enter Username</font>
			</td>
			<td class="fieldsnew">
				<input type="text" name="user_username" />
			</td>
		</tr>
		<tr></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="submit" name="submit" value="SUBMIT">
			</td>
		</tr>
		</table>
        ~else`
                <table width='100%' align=center style="table-layout: fixed;">
                        <tr class=formhead style="background-color:#C3D69B">
                                        <td align=center><strong>USERNAME: </strong>~$username`</td>
                                </tr>
                </table>
                <table width='100%' align=center style="table-layout: fixed;">
                        <tr class=formhead style="background-color:#C3D69B">
                                <td align=center style="width:150px;"><strong>Modified Date</strong></td>
                                ~foreach from=$fieldsToGetLabel key=ky item=dtColumn`
                                        ~if $ky eq "EMAIL"`
                                                <td  align=center style="width:300px;">~$dtColumn`</td>
                                        ~else`
                                                <td  align=center style="width:150px;">~$dtColumn`</td>
                                        ~/if`
                                ~/foreach`
                                <td align=center style="width:150px;"><strong>Modified Date</strong></td>
                        </tr>
                        ~foreach from=$changedArray key=ky item=changedArr`
                                <tr class=formhead style="background-color:LightSteelBlue">
                                        <td  align=center>~$changedArr["MOD_DT"]`</td>
                                        ~foreach from=$fieldsToGetLabel key=ky item=dtColumn`
                                                <td  align=center>~$changedArr[$ky]`</td>
                                        ~/foreach`
                                        <td  align=center>~$changedArr["MOD_DT"]`</td>
                                </tr>
                        ~/foreach`
                </table>
        ~/if`
	</form>
</body>
</html>