~include_partial('global/header')`
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
~if !$csv`
	<form name="form" method="post" action="createCsv" >
		~if $f`
			<span style="color:red">Invalid Mailer Id</span>
		~/if`
		<tr>
			<td colspan="4" align="center" class="label" bgcolor="#F9F9F9" >
				<b>Enter Mailer Id For Downloading MIS</b>
			</td>
		</tr>

		<tr>
			<td height="20">
			</td>
		</tr>

		<tr>
			<td>
				<input type="textbox" name="mailer_id" ~if $mailer_id` value=~$mailer_id` ~else` placeholder="Enter mailer id" ~/if`>
				~if $error`
					<span style="color:red">Invalid Mailer Id!</span>
				~/if`
			</td>
	</tr>

	<tr>
		<td height="20">
		</td>
	</tr>

	<tr>
		<td><input type="radio" name="type" value="o" checked>Overall Response</td>
		<td><input type="radio" name="type" value="i">Individual Response</td>
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
		<td colspan="4" align="center">
			<h2>Download CSV Files<br></h2>
		</td>
	</tr>

	<tr>
		<td colspan="4" align="center" class="label" bgcolor="#F9F9F9" >
			<b>The file has been stored at location : ~$path`</b>
		</td>
	</tr>
~/if`
</table>	
