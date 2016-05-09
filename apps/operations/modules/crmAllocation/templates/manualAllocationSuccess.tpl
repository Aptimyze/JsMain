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
                <td width="25%" class="formhead" align="center">Manual Module</td>
        </tr>
</table>
~if $allocatedSuccessfully`
        <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
          <table width=760 align="CENTER" >
           <tr>
                <td height="23" class="formhead" align="center">
                	Record Inserted.<br>
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualAllocation?name=~$agentName`&cid=~$cid`">Continue </a> 
                </td>
           <tr>
          </table>
~else`

~if $error eq 'Y'`
<table width="60%" align="center">
        <tr class="label">
                <td align="center">
                <span class=red>
			~if $username eq ''` No Username provided~/if`
			~if $errorCondition.WRONG_USERNAME` Username does not exist~/if`			
                        ~if $errorCondition.DELETED` The profile is deleted~/if`
			~if $errorCondition.ALLOTED_TO` This user is already alloted to ~$errorCondition.ALLOTED_TO`~/if`
			~if $errorCondition.INVALID_FTO_STATE` Manual allocation for this profile is not permitted. Please email your supervisor to get the allocation ~/if`
                </span>
                </td>
        </tr>
</table>
~/if`
<form method=post action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/manualAllocation" name="insertForm">
<table width=100% align="CENTER" >
	<tr class=fieldsnew>
		<td align="center" width="30%">
		~if ($error eq 'Y' && $username eq '') || $errorCondition.WRONG_USERNAME || $errorCondition.DELETED`<font color="red">Username*</font>~else`Username*~/if` 
		</td>
		<td>
			<input type="text" name="username" value="~$username`">
		</td>
	</tr>
	<tr class=fieldsnew>
		<td align="center">Allot To </td>
		<td>
			<select name="allot_to">
				~foreach from=$execArr item=execArrVal key=execArrKey`
					<option value="~$execArrVal`" ~if $execArrVal eq $allot_to` selected ~/if`>~$execArrVal`</option>
				~/foreach`
			</select>
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
                ~if $error eq 'Y' && !$paidProfile && !$follow_date`<font color="red">Follow Up Time*</font>~else`Follow Up Time~/if`
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
	~if $showAllotTime eq 'Y'`
		<tr class=fieldsnew>
			<td align="center">Allot Time 
			</td>
			<td>
				<input type=text name="allot_time" value="~$allot_time`" size=20 maxlength=99 class=textfield tabindex="1" id="field_5_3">
				<script type="text/javascript">
			        <!--
				document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_5_3\', \'datetime\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
			       //-->
			        </script>
				<font color="red">*If not provided, current time will be used</font>
			</td>
		</tr>
	~/if`
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
