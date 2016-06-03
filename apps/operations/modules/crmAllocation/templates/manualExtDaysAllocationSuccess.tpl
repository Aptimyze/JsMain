<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>JeevanSathi.com - CRM</title>
</head>
~include_partial('global/header')`
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
        <tr width=100% border=1>
                <td width="25%" class="formhead" align="center">Manual Extension Days Module</td>
        </tr>
</table>

~if $allocatedSuccessfully`
        <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
          <table width=760 align="CENTER" >
           <tr>
                <td height="23" class="formhead" align="center">
                        Allocation extension given till ~$moreDays` more days from today (till ~$showDeAllocationDate`).<br>
                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualExtDaysAllocation?name=~$agentName`&cid=~$cid`">Continue </a> 
                </td>
           <tr>
          </table>
~else`

<form action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualExtDaysAllocation"method=post>
<table width=60% align=center>
	~if $error eq 'Y'`	
	<tr class=fieldsnew>
		<td align=center colspan="2"><font color="red">
		~if $errorCondition.WRONG_USERNAME || $username eq ''`Username does not exist
		~else if $errorCondition.DELETED` The profile is deleted
		~else if $errorCondition.CANNOT_ALLOT`This profile is currently not alloted.
		~else if $errorCondition.DAYS_LIMIT_EXCEED`Allocation extension not allowed because maximum possible extension has already been given. 
		~else if $errorDays eq 'Y'`Please check the days entered.		
		~/if`
		</font></td>
	</tr>
	~/if`
	<tr class=fieldsnew>
		<td align=left width="50%">~if $error eq 'Y' && ($username eq '' || $errorCondition.WRONG_USERNAME || $errorCondition.DELETED)`<font color="red">~/if` USERNAME :</font></td>
		<td align=left width="50%"><input type=text name="username" value="~$username`">
		</td>
	<tr class=fieldsnew>
		<td align=left width="50%">~if $error eq 'Y' && ($days eq '' || $errorDays eq 'Y')`<font color="red">~/if` NO OF DAYS :</font></td>
		<td align=left width="50%"><input type=text name="days" value="~$days`">
		</td>
	</tr>
	<tr class=fieldsnew>
		<td align=center colspan="2">
		<input type=submit name="submit" value="submit">
		<input type="hidden" name="cid" value="~$cid`">
		<input type="hidden" name="name" value="~$agentName`">
		</td>
	</tr>
</table>
</form>
~/if`

<br><br>
~include_partial('global/footer')`
</body>
</html>
