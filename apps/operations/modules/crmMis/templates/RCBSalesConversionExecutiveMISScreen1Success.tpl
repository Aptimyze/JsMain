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
		<td style="background-color:lightblue"><font size=3>RCB Sales Conversion Executive MIS</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr class="formhead" align="center">
		<td style="background-color:lightGray">
			<font size=2>
				For the ~if $range_format eq 'MY'`month of~else`period~/if` ~$displayDate`
			</font>
		</td>
	</tr>
</table>
<br />
<table width=100%>
	<tr class=formhead style="background-color:LightSteelBlue">
		<td width=4% align=center>Executive</td>
		<td width=4% align=center>No. of RCB Allocation</td>
		<td width=4% align=center>Users who paid within 15 days</td>
		<td width=4% align=center>Ticket Size(Net of TAX) in RS</td>
	</tr>

	~foreach from=$misData key=k item=v`
	<tr style="background-color:lightyellow">
		<td align=center width=4%>~$k`</td>
		<td align=center width=4%>~$v.count`</td>
		<td align=center width=4%>~$v.paid`</td>
		<td align=center width=4%>~$v.revenue`</td>
	</tr>
	~/foreach`
</table>
<br />
</html>
