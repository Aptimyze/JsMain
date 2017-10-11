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
		<td style="background-color:lightblue"><font size=3>Field Sales Executive Efficiency MIS</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr class="formhead" align="center">
		<td style="background-color:lightGray">
			<font size=2>
				~$header`
			</font>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center" class="label"><font size=2>
			Executive Name: <b>~$exec`</b>
		</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
<br />
<table width=100%>
	<tr class=formhead style="background-color:LightSteelBlue">
		<td width=4% align=center>Sl.No.</td>
		<td width=4% align=center>Username</td>
		<td width=4% align=center>Date of Allocation</td>
		<td width=4% align=center>Date of Fresh Visit</td>
		<td width=4% align=center>Date of Payment</td>
		<td width=4% align=center>Amount</td>
	</tr>
	~if $count!=0` ~for $it=1 to $count`
		~if $details eq 'ALL' or $details eq 'NUM_ALLOC'`
		<tr style="background-color:Moccasin">
			<td width=4% align=center>~$it`</td>
			<td width=4% align=center>~$profileUsername[$profileArray[$exec][$it-1]['PROFILEID']]`</td>
			<td width=4% align=center>~$profileArray[$exec][$it-1]['ALLOT_TIME']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~if $freshVisitArray[$exec][$it-1]['ENTRY_DT']`
			~$freshVisitArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['ENTRY_DT']`
			~$paidArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['AMOUNT']`
			~$paidArray[$exec][$it-1]['AMOUNT']`
			~else`
			~/if`</td>
		</tr>
		~/if`
		~if $details eq 'NUM_FRESH_VISIT' and $freshVisitArray[$exec][$it-1]['ENTRY_DT']`
			~$tempCount = $tempCount + 1`
		<tr style="background-color:Moccasin">
			<td width=4% align=center>~$tempCount`</td>
			<td width=4% align=center>~$profileUsername[$profileArray[$exec][$it-1]['PROFILEID']]`</td>
			<td width=4% align=center>~$profileArray[$exec][$it-1]['ALLOT_TIME']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~if $freshVisitArray[$exec][$it-1]['ENTRY_DT']`
			~$freshVisitArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['ENTRY_DT']`
			~$paidArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['AMOUNT']`
			~$paidArray[$exec][$it-1]['AMOUNT']`
			~else`
			~/if`</td>
		</tr>
		~/if`
		~if ($details eq 'NUM_PAID' or $details eq 'TOTAL_SALES') and $paidArray[$exec][$it-1]['ENTRY_DT']`
			~$tempCount = $tempCount + 1`
		<tr style="background-color:Moccasin">
			<td width=4% align=center>~$tempCount`</td>
			<td width=4% align=center>~$profileUsername[$profileArray[$exec][$it-1]['PROFILEID']]`</td>
			<td width=4% align=center>~$profileArray[$exec][$it-1]['ALLOT_TIME']|date_format:"%B %e, %Y"`</td>
			<td width=4% align=center>~if $freshVisitArray[$exec][$it-1]['ENTRY_DT']`
			~$freshVisitArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['ENTRY_DT']`
			~$paidArray[$exec][$it-1]['ENTRY_DT']|date_format:"%B %e, %Y"`
			~else`
			~/if`</td>
			<td width=4% align=center>~if $paidArray[$exec][$it-1]['AMOUNT']`
			~$paidArray[$exec][$it-1]['AMOUNT']`
			~else`
			~/if`</td>
		</tr>
		~/if`
	~/for`
	~/if`
</table>
<br />
</html>
