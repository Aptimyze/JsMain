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
		<td style="background-color:lightblue"><font size=3>Field Sales Executive Performance MIS</font></td>
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
		<td width=4% align=center>Employee ID</td>
		~foreach from=$result item=dd`
		<td width=3% align=center>~$dd.DATE`</td>
		~/foreach`
		<td width=4% align=center>Total</td>
	</tr>

	~foreach from=$hierarchyData item=hierarchy`
	<tr style="background-color:~$background_color[$hierarchy.USERNAME]`">
		<td width=4%>
		~if $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['FRESH_VISITS'] or $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID'] or $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['SALES']`
			~if $hierarchy.DIRECT_REPORTEE_STATUS eq 1`
			~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
			<b><a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=ALL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style="text-decoration:underline; color:#000000">~$hierarchy.USERNAME`</a></b>
			~else`
			~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
			<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=ALL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style="text-decoration:underline; color:#000000">~$hierarchy.USERNAME`</a>
			~/if`
		~else`
			~if $hierarchy.DIRECT_REPORTEE_STATUS eq 1`
			~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				<b>~$hierarchy.USERNAME`</a></b>
			~else`
			~for $it=0 to $hierarchy.LEVEL`&nbsp;&nbsp;&nbsp;~/for`
				~$hierarchy.USERNAME`</a>
			~/if`
		~/if`
		</td>
		<td width=3% align=center>~$emp_id_arr[$hierarchy.USERNAME]`</td>
		~foreach from=$result item=dd key=kkk`
		<td width=3% align=center>
			~if $smarty.now|date_format:"%Y-%m-%d" gte $kkk`
			<font color="#FF0000">
				~if $individual_result[$kkk][$hierarchy.USERNAME]['FRESH_VISITS']`
				<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=~$kkk`&details=VD&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#FF0000;">~$dd[$hierarchy.USERNAME]['FRESH_VISITS']`</a>
				~else`
					~if $dd[$hierarchy.USERNAME]['FRESH_VISITS']`
						~$dd[$hierarchy.USERNAME]['FRESH_VISITS']`
					~else`
						0
					~/if`
				~/if`
			</font>
			<br />
			<font color="#0080FF">
				~if $individual_result[$kkk][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
				<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=~$kkk`&details=PP&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0080FF;">~$dd[$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`</a>
				~else`
					~if $dd[$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
						~$dd[$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
					~else`
						0
					~/if`
				~/if`
			</font>
			<br />
			<font color="#31B404">
				~if $individual_result[$kkk][$hierarchy.USERNAME]['SALES']`
				<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=~$kkk`&details=SL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#31B404;">~$dd[$hierarchy.USERNAME]['SALES']`</a>
				~else`
					~if $dd[$hierarchy.USERNAME]['SALES']`
						~$dd[$hierarchy.USERNAME]['SALES']`
					~else`
						0
					~/if`
				~/if`
			</font>
			~/if`
		</td>
		~/foreach`
		<td width=4% align=center style="background-color:PaleGreen">
			<b>
				<font color="#FF0000">
					~if $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['FRESH_VISITS']`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=VD&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#FF0000;">~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['FRESH_VISITS']`</a>
					~else`
						~if $execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['FRESH_VISITS']`
							~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['FRESH_VISITS']`
						~else`
							0
						~/if`
					~/if`
				</font>
				<br />
				<font color="#0080FF">
					~if $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=PP&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#0080FF;">~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`</a>
					~else`
						~if $execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
							~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['PROFILES_COUNT_WHO_PAID']`
						~else`
							0
						~/if`
					~/if`
				</font>
				<br />
				<font color="#31B404">
					~if $individual_execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['SALES']`
					<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutivePerformanceMisResultScreen2?exec=~$hierarchy.USERNAME`&date=TOTAL&details=SL&range_format=~$range_format`&cid=~$cid`&startDate=~$start_date`&endDate=~$end_date`" style = "text-decoration:underline; color:#31B404;">~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['SALES']`</a>
					~else`
						~if $execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['SALES']`
							~$execWiseAndDayWiseSummation['EXEC_WISE'][$hierarchy.USERNAME]['SALES']`
						~else`
							0
						~/if`
					~/if`
				</font>
			</b>
		</td>
	</tr>
	~/foreach`
</table>
<br />
</html>
