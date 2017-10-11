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
				For the ~if $range_format eq 'MY'`month of~else`period~/if` ~$displayDate`
			</font>
		</td>
	</tr>
</table>
<br />
<input type="hidden" name="header" value="~$header`">
<table width=100%>
	<tr class=formhead style="background-color:LightSteelBlue">
		<td width=4% align=center>Manager/Supervisor/Executive</td>
		<td width=3% align=center>Number of Allocations</td>
		<td width=3% align=center>Number of Fresh Visits</td>
		<td width=3% align=center>Fresh Visit %</td>
		<td width=3% align=center>Number of Profiles that Paid</td>
		<td width=3% align=center>Visit Paid Conversion %</td>
		<td width=3% align=center>Allocations Paid Conversion %</td>
		<td width=3% align=center>Sales</td>
		<td width=3% align=center>Ticket Size</td>
	</tr>

	~foreach from=$hierarchyData item=hierarchy`
		~if $newAllotedProfileCount[$hierarchy.USERNAME]`
		<tr style="background-color:~$background_color[$hierarchy.USERNAME]`">
			<!-- Manager/Supervisor/Executive -->
			<td width=4%>
			~if $allotedProfileCount[$hierarchy.USERNAME]`
				~if $hierarchy.DIRECT_REPORTEE_STATUS eq 1`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				<b><a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=ALL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style="text-decoration:underline; color:#0000FF">~$hierarchy.USERNAME`</a></b>
				~else`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=ALL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style="text-decoration:underline; color:#0000FF">~$hierarchy.USERNAME`</a>
				~/if`
			~else`
				~if $hierarchy.DIRECT_REPORTEE_STATUS eq 1`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				<b>~$hierarchy.USERNAME`</b>
				~else`
				~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				~$hierarchy.USERNAME`
				~/if`
			~/if`
			</td>
			<!-- Number of Allocations -->
			<td width=3% align=center>
				<font color="#000">
				~if $allotedProfileCount[$hierarchy.USERNAME]`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=NUM_ALLOC&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0000FF;">~$newAllotedProfileCount[$hierarchy.USERNAME]`</a>
				~else`
					~if $newAllotedProfileCount[$hierarchy.USERNAME]`
					~$newAllotedProfileCount[$hierarchy.USERNAME]`
					~else`
					0
					~/if`
				~/if`
				</font>
			</td>
			<!-- Number of Fresh Visits -->
			<td width=3% align=center>
				<font color="#000">
				~if $originalFreshVisitCount[$hierarchy.USERNAME]`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=NUM_FRESH_VISIT&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0000FF;">~$freshVisitCount[$hierarchy.USERNAME]`</a>
				~else`
					~if $freshVisitCount[$hierarchy.USERNAME]`
					~$freshVisitCount[$hierarchy.USERNAME]`
					~else`
					0
					~/if`
				~/if`
				</font>
			</td>
			<!-- Fresh Visit % -->
			<td width=3% align=center>
				<font color="#000">
					~if $freshVisitPercentage[$hierarchy.USERNAME]`
					~$freshVisitPercentage[$hierarchy.USERNAME]`%
					~else`
					0
					~/if`
				</font>
			</td>
			<!-- Number of Profiles that Paid -->
			<td width=3% align=center>
				<font color="#000">
				~if $originalPaidProfileCount[$hierarchy.USERNAME]`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=NUM_PAID&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0000FF;">~$paidProfileCount[$hierarchy.USERNAME]`</a>
				~else`
					~if $paidProfileCount[$hierarchy.USERNAME]`
					~$paidProfileCount[$hierarchy.USERNAME]`
					~else`
					0
					~/if`
				~/if`
				</font>
			</td>
			<!-- Visit Paid Conversion % -->
			<td width=3% align=center>
				<font color="#000">
					~if $visitPaidPercentage[$hierarchy.USERNAME]`
					~$visitPaidPercentage[$hierarchy.USERNAME]`%
					~else`
					0
					~/if`
				</font>
			</td>
			<!-- Allocations Paid Conversion % -->
			<td width=3% align=center>
				<font color="#000">
					~if $allotedPaidPercentage[$hierarchy.USERNAME]`
					~$allotedPaidPercentage[$hierarchy.USERNAME]`%
					~else`
					0
					~/if`
				</font>
			</td>
			<!-- Sales -->
			<td width=3% align=center>
				<font color="#000">
				~if $originalTotalSales[$hierarchy.USERNAME]`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=TOTAL_SALES&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0000FF;">~$totalSales[$hierarchy.USERNAME]`</a>
				~else`
					~if $totalSales[$hierarchy.USERNAME]`
					~$totalSales[$hierarchy.USERNAME]`
					~else`
					0
					~/if`
				~/if`
				</font>
			</td>
			<!-- Ticket Size -->
			<td width=3% align=center>
				<font color="#000">
					~if $ticketSize[$hierarchy.USERNAME]`
					~$ticketSize[$hierarchy.USERNAME]`
					~else`
					0
					~/if`
				</font>
			</td>
		</tr>
		~/if`
	~/foreach`
</table>
<br />
</html>
