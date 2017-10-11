<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td valign="top" width="40%" align="center"><img src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
	<tr>
		<td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr class="formhead" align="center">
		<td style="background-color:lightblue"><font size=3>Renewal Follow-up Status MIS</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr class="formhead" align="center">
		<td style="background-color:lightGray">
			<font size=2>
				Executive Name: <b>~$exec`</b>
			</font>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center" class="label"><font size=2>
			Selected Column : <b>~$column`</b>
		</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
<br />
<table width=100%>
	<tr class=formhead style="background-color:LightSteelBlue">
		<td width=4% align=center>Sl.No.</td>
		<td width=4% align=center>Username</td>
		<td width=4% align=center>Allocated On</td>
		<td width=4% align=center>Paid On</td>
		<td width=4% align=center>Last Handled On</td>
		<td width=4% align=center>To Be Followed-up On</td>
		<td width=4% align=center>Subscription Expires On</td>
		<td width=4% align=center>Will Get De-allocated On</td>
	</tr>
	~if $count!=0`
	~for $it=1 to $count`
		<tr style="background-color:#F0F0F0">
			<td width=4% align=center>~$it`</td>
			<td width=4% align=center><b><a href = "~sfConfig::get('app_site_url')`/crm/get_history.php?name=~$exec`&cid=~$cid`&USERNAME=~$profileData[$it-1]['USERNAME']`&GetHistory=1" style="text-decoration:underline; color:#0000FF">~$profileData[$it-1]['USERNAME']`</a></b></td>
			<td width=4% align=center>~$profileData[$it-1]['ALLOT_TIME']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~$profileData[$it-1]['PAID_ON']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~$profileData[$it-1]['LAST_HANDLED_DATE']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~if $profileData[$it-1]['FOLLOWUP_TIME'] neq '0000-00-00 00:00:00'`
			~$profileData[$it-1]['FOLLOWUP_TIME']|date_format:"%B %e, %Y"`
			~/if`</td>
			<td width=4% align=center>~$profileData[$it-1]['SUBS_EXPIRY']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~$profileData[$it-1]['DE_ALLOCATION_DT']|date_format:"%B %e, %Y"`</td>
		</tr>
	~/for`
	~/if`
</table>
<br />
</html>
