~include_partial('global/header')`

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tbody>
		<tr>
			<td colspan="4" align="center">
				<h2>MAIL FIRE MENU</h2>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center" class="label" bgcolor="#F9F9F9" ><b>~$message`</b>
			</td>
		</tr>
	</tbody>
</table>

<form name="form1" method="post" action="fireMenu" onsubmit="">

<input type="hidden" name="cid" value="~$cid`">

<table width="100%" border="0" cellspacing="5" cellpadding="0" align="left">
	<tr>
		~foreach from = MmmUtility::getFireMailMenu() key = k item = i`
		<td><input type = submit name = "perform" value = ~$i`></td>
		~/foreach`
	</tr>
	<tr>
		<td colspan="6" align="center" class="label" bgcolor="#F9F9F9" ><b>~$message`</b></td>
	</tr>
	<tr>
		<td>
			<span class=blue >>>>&nbsp;READY FOR TESTING &nbsp;>>>
		</td>
		<td>
			<span class=orange >>>>&nbsp;TESTED &nbsp;>>>
		</td>
		<td>
			<span class=green >>>>&nbsp;RUNNING &nbsp;>>>
		</td>
		<td>
			<span class = green >>>>&nbsp;TESTING &nbsp;>>>
		</td>
		<td>
			<span class = green >>>>&nbsp;COMPLETED &nbsp;>>>
		</td>
	</tr>
	<tr class="bigblack">
		<td class="class4" valign=top>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				~foreach from = $readyForTest key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "readyForTest[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
			</table>
		</td>
		
		<td class="class4" valign=top>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				~foreach from = $testCompleted key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "testCompleted[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
			</table>
		</td>
		
		<td class="class4" valign=top>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				~foreach from = $running key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "running[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
			</table>
		</td>
		
		<td class="class4" valign=top>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				~foreach from = $testing key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "testing[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
				<tr>
					<td>
						<span class=red >>>>&nbsp;FIRED &nbsp;>>>
					</td>
				</tr>
				~foreach from = $fired key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "fired[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
			</table>
		</td>
		<td class="class4" valign=top>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				~foreach from = $completed key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "completed[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
				<tr>
					<td>
						<span class = red >>>>&nbsp;STOPPED &nbsp;>>>
					</td>
				</tr>
				~foreach from = $stopped key = k item = i`
				<tr>
					<td><input type = "checkbox" name = "stopped[]" value = ~$k`> ~$i`
					</td>
				</tr>
				~/foreach`
			</table>
		</td>
	</tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br>
</form>
