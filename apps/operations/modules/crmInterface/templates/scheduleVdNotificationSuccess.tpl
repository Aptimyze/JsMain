~include_partial('global/header')`

<form action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/scheduleVdNotification?cid=~$cid`" method="POST">
	<input type=hidden name=cid value="~$cid`">
	<table width=760 align=center >
		<tr class="formhead" align=center><td colspan=3>	
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?cid=~$cid`">Back</a>
		</tr>
		<tr class="formhead" align=center><td colspan=5>SCHEDULE VARIABLE DISCOUNT NOTIFICATION</tr>

		~if $errorMsg0`
			<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>~$errorMsg0`</b></font></td></tr>			
		~else`
			~if !$successMsg and $errorMsg`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2 color="red"><b>~$errorMsg`</b></font></td></tr>			
			~/if`

			~if $successMsg`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>~$successMsg`</b></font></td></tr>			
			~else`
				<tr align=center>
					<td width=40%>Enter Frequency</td>
					<td><input type=number min=1 max=10 name=frequency value=~$frequency`></td>
					<td><input type=submit name=isCheck value=Check></td>
				</tr>

				~if $frequency gt 0`
					<tr class="formhead" align=center>
						<td width=40%>Frequency</td>
						<td width=60% colspan=100%>Entry Date</td>
					</tr>
					~foreach from=$frequencyArr key=K item=ff`
						<tr align=center>
							<td class=fieldsnew width=40%>~$K`</td>
							<td class=fieldsnew width=60% colspan=100%>
								<select name="selectedDateArr[]">
									~$i=0`
									~foreach from=$dateArr key=k item=dd`
										~if $k ge $K and $i lt 10`
				             				<option value="~$dd`" ~if $k eq $K`selected ~/if`>~$dd`</option>
				             				~$i++`
				             			~/if`
									~/foreach`
								</select>
							</td>
						</tr>
					~/foreach`
					<tr align=center>
						<td class=fieldsnew colspan=100%>
							<input type=submit name=isDone value=Done>
						</td>
					</tr>
				~/if`
			~/if`
		~/if`
	</table>
</form>

~include_partial('global/footer')`
