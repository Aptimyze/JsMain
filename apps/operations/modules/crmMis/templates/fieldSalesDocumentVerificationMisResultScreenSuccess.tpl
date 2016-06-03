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
		<td style="background-color:lightblue"><font size=3>Field Sales Document Verification MIS</font></td>
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
		<td width=4% align=center>Manager/Supervisor/Executive</td>
		~foreach from=$ddarr item=dd`
		<td width=3% align=center>~$dd`</td>
		~/foreach`
		<td width=4% align=center>Total</td>
	</tr>

	~foreach from=$hierarchyData item=hierarchy`
	<tr style="background-color:~$background_color[$hierarchy.USERNAME]`">
		<td width=4%>
			~if $hierarchy.DIRECT_REPORTEE_STATUS eq 1`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
					<b>~$hierarchy.USERNAME`</b>
			~else`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
					~$hierarchy.USERNAME`
			~/if`
		</td>
		~foreach from=$ddarr item=dd`
			<td width=3% align=center>
				~if $cntArr[$hierarchy.USERNAME][$dd]`
					~$cntArr[$hierarchy.USERNAME][$dd]`
				~/if`
				</b>
			</td>
		~/foreach`
		<td width=3% align=center style="background-color:PaleGreen">
			~if $cntArr[$hierarchy.USERNAME]['TOTAL'] gt 0`
				~$cntArr[$hierarchy.USERNAME]['TOTAL']`
			~/if`
			</b>
		</td>
	</tr>
	~/foreach`
</table>
<br />
</html>
