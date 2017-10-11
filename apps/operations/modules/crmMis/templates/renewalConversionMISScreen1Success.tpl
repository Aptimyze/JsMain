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
		<td style="background-color:lightblue"><font size=3>Renewal Conversion MIS</font></td>
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
		<td align=center>Metric</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$k`</td>
		~/foreach`
		<td align=center>Total</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Number of subscriptions expiring</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.expiry`</td>
		~/foreach`
		<td align=center>~$totData.expiry`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Number of subscriptions renewed before E-30</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.renewE30`</td>
		~/foreach`
		<td align=center>~$totData.renewE30`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Number of subscriptions renewed on [E-30, E]</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.renewE30E`</td>
		~/foreach`
		<td align=center>~$totData.renewE30E`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Number of subscriptions renewed on ]E, E+10]</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.renewEE10`</td>
		~/foreach`
		<td align=center>~$totData.renewEE10`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Number of subscriptions renewed after E+10</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.renewE10`</td>
		~/foreach`
		<td align=center>~$totData.renewE10`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Total subscriptions renewed as of current date</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.tsrc`</td>
		~/foreach`
		<td align=center>~$totData.tsrc`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Conversion % Upto E+10</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.convPercUptoE10`</td>
		~/foreach`
		<td align=center>~$totData.convPercUptoE10`</td>
	</tr>
	<tr style="background-color:lightyellow">
		<td align=center class=formhead>Conversion %</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.convPerc`</td>
		~/foreach`
		<td align=center>~$totData.convPerc`</td>
	</tr>
	<tr style="background-color:lightGray">
		<td align=center class=formhead>Total Revenue from renewed subscriptions</td>
		~foreach from=$misData key=k item=v`		
			<td align=center>~$v.totalRev`</td>
		~/foreach`
		<td align=center>~$totData.totalRev`</td>
	</tr>
</table>
<br />
</html>
