~include_partial('global/header')`
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

	~if !$link`
		<form name="form1" method="post" action="clientMISLink" >
			<tr>
				<td colspan="4" align="center" class="label" bgcolor="#F9F9F9">
					<b>Enter Mailer Id For CLIENT MIS</b>
				</td>
			</tr>

			<tr>
				<td height="20">
				</td>
			</tr>

			<tr>
				<td>
					<input type="textbox" name="mailer_id" ~if $f` value=~$mailer_id` ~else` placeholder="Enter mailer id" ~/if`>
				</td>

				<td>
					<span style="color:red">Invalid Mailer Id!</span>
				</td>
			</tr>

			<tr>
				<td height="20">
				</td>
			</tr>
														 
			<tr>
				<td>
					<input type=submit name="submit" value="submit">
				</td>
			</tr>
		</form>
	~else`
		<tr>
			<td colspan="4" align="center" class="label" bgcolor="#F9F9F9" >
				<b>Unique id to see mis for this mailer id is : ~$link`</b>
			</td>
		</tr>
	~/if`
</table>
