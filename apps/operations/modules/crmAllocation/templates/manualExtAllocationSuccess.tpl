<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Jeevansathi.com - Assign Profile</title>
	</meta>	
</head>

~include_partial('global/header')`
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
        <tr width=100% border=1>
                <td width="25%" class="formhead" align="center">Manual Extension Module</td>
        </tr>
</table>
~if $allocatedSuccessfully`
        <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
          <table width=760 align="CENTER" >
           <tr>
                <td height="23" class="formhead" align="center">
			Manual Allocation/Extension Done Successfully.<br>
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualExtAllocation?name=~$agentName`&cid=~$cid`">Continue </a> 
                </td>
           <tr>
          </table>
~else`

~if $error eq 'Y'`
<table width="60%" align="center">
        <tr class="label">
                <td align="center">
                <span class=red>
			~if $username eq ''` No Username provided
			~else if $errorCondition.WRONG_USERNAME` Username does not exist			
                        ~else if $errorCondition.DELETED` The profile is deleted
			~else if $errorCondition.DAYS_LIMIT_EXCEED`Allocation extension not allowed because maximum possible extension has already been given.
			~else if $errorCondition.CANNOT_ALLOT || $errorCondition.INVALID_FTO_STATE || $errorCondition.PAID` 	
				Manual allocation for this profile is not permitted. Please email your supervisor to get the allocation 
			~/if`
                </span>
                </td>
        </tr>
</table>
~/if`
<form method=post action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualExtAllocation" name="insertForm">
<table width=100% align="CENTER" >
	<tr class=fieldsnew>
		<td align="center" width="30%">
			~if $error eq 'Y' && $username eq ''`<font color="red">Username*</font>~else`Username*~/if`
		</td>
		<td>
			<input type="text" name="username" value="~$username`">
		</td>
	</tr>
	<tr class=fieldsnew>
		<td align="center">
			~if $error eq 'Y' && $call_source eq ''`<font color="red">Call Source*</font>~else`Call Source*~/if` 
		</td>   
		<td>
			<select name="call_source">
				<option value="">Select</option>
				~foreach from=$callSource item=callSourceVal key=callSourceKey`
					<option value="~$callSourceVal.value`" ~if $callSourceVal.value eq $call_source` selected ~/if`>~$callSourceVal.name`</option>
				~/foreach`
			</select>
		</td>   
	</tr>
        <tr class=fieldsnew>
                <td align="center">
                ~if $error eq 'Y' && !$follow_date`<font color="red">Follow Up Time*</font>~else`Follow Up Time*~/if`
                </td>
                 <td>
                        <select name="follow_date" class="textbox">
                                ~$followupDate.follow_time|decodevar`
                        </select> at Hrs.
                        <select name="follow_hour" class="textbox">
                                ~$followupDate.hour|decodevar`
                        </select> Min.
                        <select name="follow_min" class="textbox">
                                ~$followupDate.min|decodevar`
                        </select>
                </td>
        </tr>
	<tr class=fieldsnew>
		<td align=center>
			~if $error eq 'Y' && $comments eq ''`<font color="red">Comments*</font>~else`Comments*~/if`
		</td>
		<td>
			<textarea name="comments" rows="5" cols="60">~$comments`</textarea>
		</td>
	</tr>
	<tr class=fieldsnew>
		<td align=center colspan="2">
			<input type="hidden" name="cid" value=~$cid`>
			<input type="hidden" name="name" value=~$agentName`>
			<input type="submit" name="submit" value="submit">

		</td>
	</tr>
</table>
</form>
~/if`
<br><br><br>
~include_partial('global/footer')`
</body>
</html>
